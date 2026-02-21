/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmHRAKUDataSet");

R4.FrmHRAKUDataSet = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHRAKUDataSet";
    me.sys_id = "R4K";

    me.grid_id = "#FrmHRAKUDataSet_table";
    me.grid_url = me.sys_id + "/" + me.id + "/" + "fnCallListDialog";

    //グループ名
    me.grNm = "";
    //経理処理日
    me.keiriDt = "";

    me.option = {
        rowNum: 999999,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: true,
        multiselectWidth: 20,
        shrinkToFit: me.ratio === 1.5,
    };
    me.colModel = [
        {
            label: " 行No",
            width: 50,
            align: "left",
            name: "ID",
            index: "ID",
            sortable: false,
        },
        {
            label: "仕訳No",
            width: 77,
            align: "left",
            name: "SHIWAKE_NO",
            index: "SHIWAKE_NO",
            sortable: false,
        },
        {
            label: "仕訳データ生成日",
            width: 80,
            align: "left",
            name: "SHIWAKE_CRE_DATE",
            index: "SHIWAKE_CRE_DATE",
            sortable: false,
        },
        {
            label: "借方_勘定科目名",
            width: 126,
            align: "left",
            name: "L_KANJYOU_NM",
            index: "L_KANJYOU_NM",
            sortable: false,
        },
        {
            label: "借方_補助科目名",
            width: 126,
            align: "left",
            name: "L_HOJYO_NM",
            index: "L_HOJYO_NM",
            sortable: false,
        },
        {
            label: "借方_部門コード",
            width: 60,
            align: "left",
            name: "L_FUTAN_BUMON_CD",
            index: "L_FUTAN_BUMON_CD",
            sortable: false,
        },
        {
            label: "借方_金額",
            width: 80,
            align: "right",
            name: "L_AMOUNT",
            index: "L_AMOUNT",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            label: "貸方_勘定科目名",
            width: 126,
            align: "left",
            name: "R_KANJYOU_NM",
            index: "R_KANJYOU_NM",
            sortable: false,
        },
        {
            label: "貸方_補助科目名",
            width: 126,
            align: "left",
            name: "R_HOJYO_NM",
            index: "R_HOJYO_NM",
            sortable: false,
        },
        {
            label: "貸方_部門コード",
            width: 60,
            align: "left",
            name: "R_FUTAN_BUMON_CD",
            index: "R_FUTAN_BUMON_CD",
            sortable: false,
        },
        {
            label: "明細_フリー1",
            width: 235,
            align: "left",
            name: "FREE1_DETAIL",
            index: "FREE1_DETAIL",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmHRAKUDataSet.btn",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //選択
    $(".FrmHRAKUDataSet.btnChoose").click(function () {
        var selRowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        if (selRowIds.length == 0) {
            me.clsComFnc.FncMsgBox("W9999", "選択されませんでした");
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnChooseClick;
        me.clsComFnc.FncMsgBox("QY999", "更新します。よろしいですか？");
    });
    //キャンセル
    $(".FrmHRAKUDataSet.btnCancel").click(function () {
        $(".FrmHRAKUDataSet.body").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.Page_Load = function () {
        $(".FrmHRAKUDataSet.body").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 546 : 690,
            width: me.ratio === 1.5 ? 1260 : 1300,
            resizable: false,
            title: "グループ設定",
            open: function () {
                me.grNm = $("#grNm").val();
                me.keiriDt = $("#keiriDt").val();
            },
            close: function () {
                if ($("#FrmHRAKUDataSetDialogDiv").length > 0) {
                    $("#FrmHRAKUDataSetDialogDiv").remove();
                    $("#groupName").remove();
                    $("#dealDate").remove();
                }
                $(".FrmHRAKUDataSet.body").remove();
            },
        });
        $(".FrmHRAKUDataSet.body").dialog("open");

        //画面初期化
        var complete_fun = function (returnFLG, result) {
            $(".FrmHRAKUDataSet.btnChoose").button("disable");
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG != "nodata") {
                $(".FrmHRAKUDataSet.btnChoose").button("enable");
            } else {
                setTimeout(() => {
                    me.clsComFnc.FncMsgBox("W0024");
                }, 100);
            }
        };
        var gridWidth = $(".FrmHRAKUDataSet.R4-content").width() - 16;
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.grid_url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, gridWidth);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 409 : 506
        );
    };
    //**********************************************************************
    //処 理 名：選択ボタンをクリック
    //関 数 名：btnChooseClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：選択ボタンをクリックする時
    //**********************************************************************
    me.btnChooseClick = function () {
        var selRowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var idStr = "";
        for (var i = 0; i < selRowIds.length; i++) {
            if (i > 0) {
                idStr += ",";
            }
            var rowId = selRowIds[i];
            var idData = $(me.grid_id).jqGrid("getCell", rowId, "ID");
            idStr += idData;
        }
        var data = {
            idStr: idStr,
            //グループ名
            grNm: me.grNm,
            //経理処理日
            keiriDt: me.keiriDt,
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnChooseClick";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "already") {
                    var msg = "ID：";
                    for (var i = 0; i < result["data"].length; i++) {
                        if (i > 0) {
                            msg += "、";
                        }
                        msg += result["data"][i]["ID"];
                    }
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "既に設定されたデータがあるので、確認してください。" +
                            "<br>" +
                            msg
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.FncMsgBox("I9999", "グループを設定しました");
            $(".FrmHRAKUDataSet.body").dialog("close");
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHRAKUDataSet = new R4.FrmHRAKUDataSet();
    o_R4_FrmHRAKUDataSet.load();
});
