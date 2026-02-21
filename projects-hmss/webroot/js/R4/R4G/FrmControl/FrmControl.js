/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * 20201117           bug                         AJAX.SEND パラメータ数              LQS
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmControl");

R4.FrmControl = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmControl";
    me.sys_id = "R4G";

    me.colModel = [
        {
            label: "処理",
            name: "name",
            index: "name",
            sortable: false,
            width: 320,
        },
        {
            label: "状態",
            name: "status",
            index: "status",
            sortable: false,
            width: 100,
        },
        {
            label: "解除",
            name: "check",
            index: "check",
            width: 40,
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },

            align: "center",
        },
    ];

    me.mydata = [
        {
            name: "ダウンロード",
            status: "",
            check: "",
        },
        {
            name: "インポート",
            status: "",
            check: "",
        },
        {
            name: "注文書データCSV作成",
            status: "",
            check: "",
        },
        {
            name: "登録予定データCSV作成",
            status: "",
            check: "",
        },
        {
            name: "新車納品書データCSV作成",
            status: "",
            check: "",
        },
        {
            name: "売掛データCSV作成",
            status: "",
            check: "",
        },
        {
            name: "会計データCSV作成",
            status: "",
            check: "",
        },
        {
            name: "注文書個別ダウンロード",
            status: "",
            check: "",
        },
        {
            name: "登録予定個別ダウンロード",
            status: "",
            check: "",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmControl.cmdAction",
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
    $(".FrmControl.cmdAction").click(function () {
        var selrow = new Array();
        for (var i = 1; i <= me.mydata.length; i++) {
            var selrow;
            rowData = $("#FrmControl_sprList").jqGrid("getRowData", i);
            if (rowData["check"] == "Yes") {
                selrow.push(i);
            }
        }
        //ロック解除の対象が1つも選択されていない場合は、エラー
        if (selrow == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ロックを解除する対象を選択して下さい!"
            );
            return;
        }
        $(".FrmControl.cmdAction").button("disable");
        data = {
            request: selrow,
        };
        //url = "R4/FrmControl/Fnc_UnLock";
        var funcName = "fncunLock";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        me.ajax.receive = function (result) {
            me.buttonable();
            result = eval("(" + result + ")");
            if ((result["result"] = true)) {
                me.clsComFnc.MsgBoxBtnFnc.Close = me.subSpreadReShow;
                me.clsComFnc.FncMsgBox("I9999", "ロックを解除しました");
            } else {
                me.clsComFnc.FncMsgBox("E9999", "result['data']");
            }
        };
        me.ajax.send(url, data, 0);
        me.ajax.beforeLogin = me.buttonable;
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //jqGrid start
    $("#FrmControl_sprList").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow : false,
        height: "240",
        colModel: me.colModel,
        multikey: "ctrlKey",
    });

    //初期化
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.subSpreadReShow();
    };
    // '**********************************************************************
    // '処 理 名：データグリッドの再表示
    // '関 数 名：subSpreadReShow
    // '引    数：objDr (I) オブジェクト
    // '戻 り 値：無し
    // '処理説明：データグリッドを再表示する
    // '**********************************************************************
    me.subSpreadReShow = function () {
        //url = "R4/FrmControl/subSpreadReShow";
        var funcName = "subSpreadReShow";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                //全ての選択を解除します。
                $("#FrmControl_sprList").jqGrid("clearGridData");
                for (var i = 0; i < me.mydata.length; i++) {
                    if (result["data"][0]["LOCK_ID_" + (i + 1)] == 0) {
                        me.mydata[i]["status"] = "停止";
                    } else {
                        me.mydata[i]["status"] = "実行中";
                    }
                    $("#FrmControl_sprList").jqGrid(
                        "addRowData",
                        i + 1,
                        me.mydata[i]
                    );
                }
                $(":input").eq(1).trigger("focus");
            } else {
                me.clsComFnc.FncMsgBox("E9999", "result['data']");
            }
            $(":input").each(function () {
                if (this.type == "checkbox") {
                    $(this).attr("class", "Tab");
                }
            });
            //ShifキーとTabキーのバインド
            me.clsComFnc.Shif_TabKeyDown();

            //Tabキーのバインド
            me.clsComFnc.TabKeyDown();

            //Enterキーのバインド
            me.clsComFnc.EnterKeyDown();
        };
        // 20201117 lqs upd S
        // me.ajax.send(url, '', 1, '');
        me.ajax.send(url, "", 1);
        // 20201117 lqs upd E
    };
    me.buttonable = function () {
        $(".FrmControl.cmdAction").button("enable");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmControl = new R4.FrmControl();
    o_R4_FrmControl.load();
});
