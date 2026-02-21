/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                担当
 * YYYYMMDD           #ID                          XXXXXX                              FCSDL
 * 20171226 		  #2807						   依頼								   YIN
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmUriBusyoCnv");

R4.FrmUriBusyoCnv = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    //グローバル変数
    me.Mode = "";
    me.CMNNO = "";

    me.id = "FrmUriBusyoCnv";
    me.sys_id = "R4K";

    me.colModel = [
        {
            name: "CMN_NO",
            label: "注文書番号",
            index: "CMN_NO",
            sortable: false,
            width: 92,
        },
        {
            name: "UC_NO",
            label: "UC_NO",
            index: "UC_NO",
            sortable: false,
            width: 120,
        },
        {
            name: "SYAIN_NO",
            label: "社員番号",
            index: "SYAIN_NO",
            sortable: false,
            width: 80,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            sortable: false,
            width: 225,
        },
        {
            name: "URI_BUSYO_CD",
            label: "売上部署",
            index: "URI_BUSYO_CD",
            sortable: false,
            width: 70,
        },
        {
            name: "BUSYO_NM",
            label: "売上部署名",
            index: "BUSYO_NM",
            sortable: false,
            width: 190,
        },
        {
            name: "BUSYO_CD",
            label: "変換部署",
            index: "BUSYO_CD",
            sortable: false,
            width: 70,
        },
        {
            name: "BUSYO_NM",
            label: "変換部署名",
            index: "BUSYO_NM",
            sortable: false,
            width: 190,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmUriBusyoCnv.cmdSearch.Tab.Enter",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUriBusyoCnv.cmdInsert.Tab.Enter",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUriBusyoCnv.cmdUpdate.Tab.Enter",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUriBusyoCnv.cmdDelete.Tab.Enter",
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
        //ﾌｫｰﾑﾛｰﾄﾞ
        me.frmUriBusyoCnv_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $("#FrmUriBusyoCnv_sprList").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        colModel: me.colModel,
        rownumbers: true,
        rownumWidth: 25,
        height: me.ratio === 1.5 ? 230 : 260,
        width: me.ratio === 1.5 ? 1040 : 1107,
        //選択行の修正画面を呼び出す  Enter Key
        ondblClickRow: function () {
            me.fncUpdata();
        },
    });
    //スプレッド上でエンター押下時に修正処理
    $("#FrmUriBusyoCnv_sprList").jqGrid("bindKeys", {
        onEnter: function () {
            me.fncUpdata();
        },
    });
    //make the jqGrid's label left
    //$(".ui-jqgrid-sortable").css("text-align", "left");

    // dialog
    $("#FrmUriBusyoCnvEdit").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        //20171226 YIN UPD S
        // width : 550,
        // height : 350,
        width: 560,
        height: 375,
        classes: {
            "ui-dialog": "RemoveCloseMark",
        },
        open: function () {
            $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
        },
        close: function () {
            //clear the dialog
            $("#FrmUriBusyoCnvEdit").html("");
            var txtSYAINNO = $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter")
                .val()
                .trimEnd();
            var txtSYAINKN = $(".FrmUriBusyoCnv.txtSYAINKN.Tab.Enter")
                .val()
                .trimEnd();
            me.fncListSel(1, txtSYAINNO, txtSYAINKN);
        },
    });

    //検索ボタンクリック
    $(".FrmUriBusyoCnv.cmdSearch.Tab.Enter").click(function () {
        var txtSYAINNO = $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter")
            .val()
            .trimEnd();
        var txtSYAINKN = $(".FrmUriBusyoCnv.txtSYAINKN.Tab.Enter")
            .val()
            .trimEnd();
        me.fncListSel(1, txtSYAINNO, txtSYAINKN);
    });

    //新規登録ボタン押下時
    $(".FrmUriBusyoCnv.cmdInsert.Tab.Enter").click(function () {
        //プロパティ1:新規2:修正
        me.Mode = 1;
        //新規画面
        me.ajax.receive = function (result) {
            $("#FrmUriBusyoCnvEdit").dialog(
                "option",
                "title",
                "売上部署変換画面"
            );
            $("#FrmUriBusyoCnvEdit").dialog("open");
            $("#FrmUriBusyoCnvEdit").html(result);
        };
        var url = me.sys_id + "/" + "FrmUriBusyoCnvEdit" + "/" + "index";
        me.ajax.send(url, "", 0);
    });

    //修正ボタン押下時
    $(".FrmUriBusyoCnv.cmdUpdate.Tab.Enter").click(function () {
        me.fncUpdata();
    });
    //削除ボタン押下時
    $(".FrmUriBusyoCnv.cmdDelete.Tab.Enter").click(function () {
        var rowcount = $("#FrmUriBusyoCnv_sprList").jqGrid(
            "getGridParam",
            "reccount"
        );
        if (rowcount == 0) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象行を選択してください");
            return;
        }
        var selRow = $("#FrmUriBusyoCnv_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        if (selRow < 0) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象行を選択してください");
            return;
        }
        //削除確認ﾒｯｾｰｼﾞ表示
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeletData;
        me.clsComFnc.FncMsgBox("QY004");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmUriBusyoCnv_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmUriBusyoCnv_Load = function () {
        //画面項目ｸﾘｱ
        $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter").val("");
        $(".FrmUriBusyoCnv.txtSYAINKN.Tab.Enter").val("");
        var txtSYAINNO = $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter")
            .val()
            .trimEnd();
        var txtSYAINKN = $(".FrmUriBusyoCnv.txtSYAINKN.Tab.Enter")
            .val()
            .trimEnd();
        me.fncListSel(0, txtSYAINNO, txtSYAINKN);
        $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter").trigger("focus");
    };

    //修正画面
    me.fncUpdata = function () {
        //プロパティ1:新規2:修正
        me.Mode = 2;
        //テーブルの選択行を第一列の値
        var selRow = $("#FrmUriBusyoCnv_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowdata = $("#FrmUriBusyoCnv_sprList").jqGrid("getRowData", selRow);
        me.CMNNO = rowdata["CMN_NO"].trimEnd();
        //修正dialog
        me.ajax.receive = function (result) {
            $("#FrmUriBusyoCnvEdit").dialog(
                "option",
                "title",
                "売上部署変換画面"
            );
            $("#FrmUriBusyoCnvEdit").dialog("open");
            $("#FrmUriBusyoCnvEdit").html(result);
        };
        var url = me.sys_id + "/" + "FrmUriBusyoCnvEdit" + "/" + "index";
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：削除
    //関 数 名：fncDeletData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：削除ボタン押下時
    //**********************************************************************
    me.fncDeletData = function () {
        //テーブルの選択の行を第一列の値
        var selRow = $("#FrmUriBusyoCnv_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowdata = $("#FrmUriBusyoCnv_sprList").jqGrid("getRowData", selRow);
        var SEL_CMNNO = rowdata["CMN_NO"].trimEnd();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                var txtSYAINNO = $(".FrmUriBusyoCnv.txtSYAINNO.Tab.Enter")
                    .val()
                    .trimEnd();
                var txtSYAINKN = $(".FrmUriBusyoCnv.txtSYAINKN.Tab.Enter")
                    .val()
                    .trimEnd();
                me.fncListSel(1, txtSYAINNO, txtSYAINKN);
            }
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncDeletData";
        me.ajax.send(url, SEL_CMNNO, 0);
    };

    //jqGridの表示と画面の設定
    //isload       (0:初期処理 /1:その他)
    //txtSYAINNO    社員番号
    //txtSYAINKN    社員名カナ
    me.fncListSel = function (isload, txtSYAINNO, txtSYAINKN) {
        $(".FrmUriBusyoCnv.cmdUpdate.Tab.Enter").button("enable");
        $(".FrmUriBusyoCnv.cmdDelete.Tab.Enter").button("enable");
        var data = {
            mark: isload,
            SYAINNO: txtSYAINNO,
            SYAINKN: txtSYAINKN,
        };

        var url = me.sys_id + "/" + me.id + "/" + "fncListSel";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                $("#FrmUriBusyoCnv_sprList").jqGrid("clearGridData");
                if (result["data"].length == 0) {
                    $(".FrmUriBusyoCnv.cmdUpdate.Tab.Enter").button("disable");
                    $(".FrmUriBusyoCnv.cmdDelete.Tab.Enter").button("disable");
                } else {
                    for (key in result["data"]) {
                        var columns = {
                            CMN_NO: result["data"][key]["CMN_NO"],
                            UC_NO: result["data"][key]["UC_NO"],
                            SYAIN_NO: result["data"][key]["SYAIN_NO"],
                            SYAIN_NM: result["data"][key]["SYAIN_NM"],
                            URI_BUSYO_CD: result["data"][key]["URI_BUSYO_CD"],
                            BUSYO_NM: result["data"][key]["BUSYO_NM"],
                            BUSYO_CD: result["data"][key]["BUSYO_CD"],
                            BUSYO_NM: result["data"][key]["BUSYO_NM"],
                        };
                        $("#FrmUriBusyoCnv_sprList").jqGrid(
                            "addRowData",
                            parseInt(key) + 1,
                            columns
                        );
                    }
                    $("#FrmUriBusyoCnv_sprList").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                }
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
    var o_R4_FrmUriBusyoCnv = new R4.FrmUriBusyoCnv();
    o_R4_FrmUriBusyoCnv.load();
    o_R4K_R4K_FrmUriBusyoCnv = o_R4_FrmUriBusyoCnv;
});
