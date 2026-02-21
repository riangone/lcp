Namespace.register("JKSYS.FrmLoginSel");

JKSYS.FrmLoginSel = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "JKSYS/FrmJKSYSLoginSel";
    me.grid_id = "#FrmJKSYSLoginSel_sprList";
    me.strTougetu = "";
    me.refreshFlg = false;

    me.option = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "ユーザＩＤ",
            index: "SYAIN_NO",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "STYLE_NM",
            label: "所属",
            index: "STYLE_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
            hidden: true,
        },
        {
            name: "PATTERN_NM",
            label: "パターン",
            index: "PATTERN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "USER_ID",
            label: "済/未",
            index: "USER_ID",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmLoginSel.Button1",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginSel.Button3",
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
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmLoginSel.Button1").click(function () {
        me.Button1_Click();
    });

    // '**********************************************************************
    // '入力ボタンを押すと処理します
    // '**********************************************************************
    $(".FrmLoginSel.Button3").click(function () {
        me.Button3_Click();
    });

    // '**********************************************************************
    // 'システム区分変更
    // '**********************************************************************
    $(".FrmLoginSel.cboSysKB").click(function () {
        me.cboSysKB_SelectedIndexChanged();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.init_control;
    me.init_control = function () {
        base_load();
        me.frmLoginSel_Load();
    };

    //**********************************************************************
    //処 理 名：フォームロードイベント
    //関 数 名：frmLoginSel_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：フォームロードイベント
    //**********************************************************************
    me.frmLoginSel_Load = function () {
        var url = me.id + "/fncGetLoginInfo";
        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.init(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 750);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 272 : 338
        );
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function (rowid) {
                var selIRow = parseInt(rowid) + 1;
                var getDataCount = $(me.grid_id).jqGrid(
                    "getGridParam",
                    "records"
                );
                if (selIRow == getDataCount) {
                    $(me.grid_id).jqGrid("setSelection", 0, true);
                }
                $(me.grid_id).jqGrid("setSelection", selIRow, true);
            },
        });
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowid) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
                me.UserID = rowData["SYAIN_NO"];
                me.cboSysKB = $(".FrmLoginSel.cboSysKB").val();
                me.ShowDialog();
            },
        });

        var url_load = me.id + "/fncLoadDeal";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                //コンボボックスに当月年月を設定
                me.strTougetu = me.clsComFnc.FncNv(
                    result["data"][0]["TOUGETU"]
                );
                //システム区分追加
                $("<option></option>")
                    .val("6")
                    .text("人事給与システム")
                    .appendTo(".FrmLoginSel.cboSysKB");
                $(".FrmLoginSel.cboSysKB").trigger("focus");
            } else {
                //コントロールマスタが存在していない場合
                $("<option></option>").appendTo(".FrmLoginSel.cboSysKB");

                $(".FrmLoginSel").attr("disabled", true);
                $(".FrmLoginSel button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url_load, "", 1);
    };

    //**********************************************************************
    //処 理 名：入力ボタンを押すと処理します
    //関 数 名：Button3_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.Button3_Click = function () {
        var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");
        if (getDataCount == 0) {
            me.clsComFnc.FncMsgBox("I0010");
            return;
        }
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        if (rowData && rowData["SYAIN_NO"]) {
            me.UserID = rowData["SYAIN_NO"];
            me.cboSysKB = $(".FrmLoginSel.cboSysKB").val();
            me.ShowDialog();
        }
    };

    //**********************************************************************
    //処 理 名：システム区分変更
    //関 数 名：cboSysKB_SelectedIndexChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.cboSysKB_SelectedIndexChanged = function () {
        $(me.grid_id).jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：Button1_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.Button1_Click = function () {
        $(me.grid_id).jqGrid("clearGridData");

        var data = {
            KJNBI: me.strTougetu,
            SYAIN_NO: $(".FrmLoginSel.UcUserID").val(),
            cboSysKB: $(".FrmLoginSel.cboSysKB").val(),
        };
        me.complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(me.grid_id).trigger("focus");
            $(me.grid_id).jqGrid("setSelection", 0);
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };

    me.ShowDialog = function () {
        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                UserID: me.UserID,
                cboSysKB: me.cboSysKB,
            })
        );

        me.url = "JKSYS/FrmJKSYSLoginEdit";
        me.ajax.receive = function (result) {
            function before_close() {
                if (me.refreshFlg) {
                    me.Button1_Click();
                    me.refreshFlg = false;
                }
            }
            $(".FrmLoginSel." + "dialogsFrmLoginEdit").hide();
            $(".FrmLoginSel." + "dialogsFrmLoginEdit").append(result);
            o_JKSYS_JKSYS.FrmLoginSel.FrmLoginEdit.before_close = before_close;
        };

        me.ajax.send(me.url, "", 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmLoginSel = new JKSYS.FrmLoginSel();
    o_JKSYS_FrmLoginSel.load();
    o_JKSYS_JKSYS.FrmLoginSel = o_JKSYS_FrmLoginSel;
});
