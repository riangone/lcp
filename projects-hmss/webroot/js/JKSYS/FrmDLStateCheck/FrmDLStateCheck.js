/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmDLStateCheck");

JKSYS.FrmDLStateCheck = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.sys_id = "JKSYS";
    me.id = "FrmJKSYSDLStateCheck";
    me.grid_id = "#JKSYS_FrmDLStateCheck_sprList";
    me.col = {
        DT: "",
        FILE_NM: "",
        MESSAGE: "",
        PARA2: "",
        STATE: "",
        STEP: "",
    };
    me.pager = "";
    me.sidx = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmDLStateCheck.cmdDisp",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmDLStateCheck.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.option = {
        rownumbers: true,
        rownumWidth: me.ratio === 1.5 ? 35 : 60,
        caption: "",
        rowNum: 0,
        multiselect: false,
    };
    me.colModel = [
        {
            name: "checkbox",
            label: "確認",
            index: "checkbox",
            width: 35,
            align: "center",
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
        },
        {
            name: "PARA2",
            label: "実行プログラム",
            index: "PARA2",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "FILE_NM",
            label: "ファイル名",
            index: "FILE_NM",
            width: 80,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "DT",
            label: "実行開始日時",
            index: "DT",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "STEP",
            label: "ステップ",
            index: "STEP",
            width: 70,
            align: "left",
            sortable: false,
        },
        {
            name: "STATE",
            label: "状態",
            index: "STATE",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "MESSAGE",
            label: "メッセージ",
            index: "MESSAGE",
            //20201113 YIN UPD S
            // width : 120,
            width: me.ratio === 1.5 ? 440 : 520,
            //20201113 YIN UPD E
            align: "left",
            sortable: false,
        },
        {
            name: "",
            label: "部品",
            index: "",
            width: 50,
            align: "center",
            sortable: false,
            hidden: true,
        },
    ];

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

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.FrmDLStateCheck_Load();
    };
    //再表示(F5)ボタンクリック
    $(".FrmDLStateCheck.cmdDisp").click(function () {
        me.cmdDisp_Click();
    });
    //更新ボタンクリック
    $(".FrmDLStateCheck.cmdUpdate").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdUpdate_Click;
        me.clsComFnc.FncMsgBox("QY010");
    });
    //ファンクションキー 押下時
    shortcut.add("F5", function () {
        //ｽﾌﾟﾚｯﾄﾞを再表示する
        me.cmdDisp_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：FrmDLStateCheck_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FrmDLStateCheck_Load = function () {
        //ｽﾌﾟﾚｯﾄﾞを表示する
        var url = me.sys_id + "/" + me.id + "/frmDLStateCheck_load";
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                $(".FrmDLStateCheck button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //ログ出力先取得
            me.fncGetPath();
        };
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            {},
            complete_fun
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 320 : 390
        );
        //20201113 YIN UPD S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 720);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1018 : 1100
        );
        //20201113 YIN UPD E
        $(me.grid_id).jqGrid("bindKeys");
    };
    /*
     '**********************************************************************
     '処 理 名：ログ出力先取得
     '関 数 fncGetPath
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncGetPath = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetPath";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                $(".FrmDLStateCheck.lblLogPath").val(result["data"]);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, {}, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：更新ボタンクリック
     '関 数 名：cmdUpdate_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdUpdate_Click = function () {
        var rows = $(me.grid_id).jqGrid("getRowData");
        var arr = new Array();
        //ﾁｪｯｸが入っている行を更新する
        for (key in rows) {
            if (rows[key]["checkbox"] == "Yes") {
                arr.push(rows[key]);
            }
        }
        me.url = me.sys_id + "/" + me.id + "/fncHFTS_TRANSFER_LIST_Upd";
        me.data = {
            data: arr,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                var completeFnc = function () {
                    me.clsComFnc.FncMsgBox("I0012");
                };
                //ｽﾌﾟﾚｯﾄﾞを再表示する
                me.cmdDisp_Click(completeFnc);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：再表示(F5)ボタンクリック
     '関 数 名：cmdDisp_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdDisp_Click = function (completeFnc) {
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (completeFnc) {
                completeFnc();
            }
        };
        //ｽﾌﾟﾚｯﾄﾞを再表示する
        gdmz.common.jqgrid.reloadMessage(me.grid_id, {}, complete_fun);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmDLStateCheck = new JKSYS.FrmDLStateCheck();
    o_JKSYS_FrmDLStateCheck.load();
});
