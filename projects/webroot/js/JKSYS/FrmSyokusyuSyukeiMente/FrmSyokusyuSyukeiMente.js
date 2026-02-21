/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmSyokusyuSyukeiMente");

JKSYS.FrmSyokusyuSyukeiMente = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "JKSYS";
    me.id = "FrmSyokusyuSyukeiMente";
    me.g_url1 = me.sys_id + "/" + me.id + "/FncGetHSSTTLKBNMST";
    me.g_url2 = me.sys_id + "/" + me.id + "/FncGetCODEMST";

    //jqgrid2 選択した行id
    me.lastSel2 = 0;

    /*
     * jqgrid1データ
     */
    me.grid_id1 = "#FrmSyokusyuSyukeiMente_spr_List1";
    /**jqgrid1カラム**/
    me.colModel1 = [
        {
            name: "SYOKUSYU_TTL_KB",
            label: "職種集計区分",
            index: "SYOKUSYU_TTL_KB",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SYOKUSYU_TTL_KB_NM",
            label: "職種区分名",
            index: "SYOKUSYU_TTL_KB_NM",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "ORDER_NO",
            label: "出力順",
            index: "ORDER_NO",
            width: 60,
            align: "right",
            sortable: false,
        },
    ];
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };

    /*
     * jqgrid2データ
     */
    me.grid_id2 = "#FrmSyokusyuSyukeiMente_spr_List2";
    /**jqgrid2カラム**/
    me.colModel2 = [
        {
            name: "CODE",
            label: "職種コード",
            index: "CODE",
            hidden: true,
        },
        {
            name: "MEISYOU",
            label: "職種コード",
            index: "MEISYOU",
            hidden: true,
        },
        {
            name: "display_code",
            label: "職種コード",
            index: "display_code",
            width: 240,
            align: "left",
            editable: true,
            sortable: false,
            edittype: "select",
            formatter: "select",
        },
    ];
    me.option2 = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        rownumWidth: 60,
        multiselectWidth: 40,
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    /**選択**/
    me.controls.push({
        id: ".FrmSyokusyuSyukeiMente.btnSelect",
        type: "button",
        handle: "",
    });
    /**キャンセル**/
    me.controls.push({
        id: ".FrmSyokusyuSyukeiMente.cmdCan",
        type: "button",
        handle: "",
    });
    /**登録**/
    me.controls.push({
        id: ".FrmSyokusyuSyukeiMente.cmdReg",
        type: "button",
        handle: "",
    });
    /**削除**/
    me.controls.push({
        id: ".FrmSyokusyuSyukeiMente.cmdDel",
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

    // 選択ボタンクリック
    $(".FrmSyokusyuSyukeiMente.btnSelect").click(function () {
        me.btnSelect_Click();
    });
    //  キャンセルボタンクリック
    $(".FrmSyokusyuSyukeiMente.cmdCan").click(function () {
        me.cmdCan_Click();
    });
    // 登録ボタンクリック
    $(".FrmSyokusyuSyukeiMente.cmdReg").click(function () {
        me.cmdReg_Click();
    });
    // 削除ボタンクリック
    $(".FrmSyokusyuSyukeiMente.cmdDel").click(function () {
        me.cmdDel_Click();
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

        //画面起動時,初期値設定
        me.FrmSyokusyuSyukeiMente_Load();
    };

    /*
	 '**********************************************************************
	 '処 理 名：初期値設定
	 '関 数 名：FrmSyokusyuSyukeiMente_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FrmSyokusyuSyukeiMente_Load = function () {
        me.formit(true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面初期化(画面起動時)
	 '関 数 名：formit
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.formit = function (flgLoad) {
        //画面初期化(一覧)
        me.formit2();

        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                $(".FrmSyokusyuSyukeiMente").attr("disabled", true);
                $(".FrmSyokusyuSyukeiMente button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            //第一行データを選択する
            $(me.grid_id1).jqGrid("setSelection", 0, true);
            $(me.grid_id1).trigger("focus");

            if (flgLoad) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id2,
                    me.g_url2,
                    me.colModel2,
                    "",
                    "",
                    me.option,
                    "",
                    me.complete_fun2
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id2, 360);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id2,
                    me.ratio === 1.5 ? 121 : 155
                );

                $(me.grid_id2).jqGrid("setGridParam", {
                    //選択行の修正画面を呼び出す
                    onSelectRow: function (rowId) {
                        $(me.grid_id2).jqGrid("saveRow", me.lastSel2);
                        $(me.grid_id2).jqGrid("editRow", rowId, false);
                        me.lastSel2 = rowId;
                    },
                });
                //右クリックを禁止する
                $(me.grid_id2).unbind("contextmenu");
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id2,
                    "",
                    me.complete_fun2
                );
            }
        };

        if (flgLoad) {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id1,
                me.g_url1,
                me.colModel1,
                "",
                "",
                me.option,
                "",
                complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id1, 420);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id1,
                me.ratio === 1.5 ? 104 : 130
            );
            $(me.grid_id1).jqGrid("bindKeys");
        } else {
            gdmz.common.jqgrid.reloadMessage(me.grid_id1, "", complete_fun);
        }

        //コントロール初期化
        me.subClearForm();
    };
    me.complete_fun2 = function (_bErrorFlag, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }

        var valuestr = "";
        if (result["records"] > 0) {
            valuestr = "" + ":" + "";
        }
        result["rows"].map(function (value) {
            valuestr += ";" + value["cell"][0] + ":" + value["cell"][1];
        });

        $(me.grid_id2).setColProp("display_code", {
            editoptions: {
                value: valuestr,
            },
        });

        //第一行データを選択する
        $(me.grid_id2).jqGrid("setSelection", 0, true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：選択ボタンクリック
	 '関 数 名：btnSelect_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnSelect_Click = function () {
        //行が選択されていない場合ｴﾗｰ
        var rowid = $(me.grid_id1).jqGrid("getGridParam", "selrow");
        if (!rowid) {
            me.clsComFnc.FncMsgBox("I0010");
            return;
        }

        //選択した行の内容を入力領域に表示する
        var rowData = $(me.grid_id1).jqGrid("getRowData", rowid);
        $(".FrmSyokusyuSyukeiMente.txtKbn").val(
            me.clsComFnc.FncNv(rowData["SYOKUSYU_TTL_KB"])
        );
        $(".FrmSyokusyuSyukeiMente.txtKbnNM").val(
            me.clsComFnc.FncNv(rowData["SYOKUSYU_TTL_KB_NM"])
        );
        $(".FrmSyokusyuSyukeiMente.txtOrder").val(
            me.clsComFnc.FncNv(rowData["ORDER_NO"])
        );

        //職種コードスプレット初期化
        var data = {
            txtKbn: me.clsComFnc.FncNv($.trim(rowData["SYOKUSYU_TTL_KB"])),
            alldatas: $(me.grid_id2).jqGrid("getRowData"),
        };
        var complete_fun2 = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            //ｺﾝﾄﾛｰﾙの制御
            $(".FrmSyokusyuSyukeiMente.txtKbn").attr("disabled", true);
            $(".FrmSyokusyuSyukeiMente.cmdDel").button("enable");
            $(".FrmSyokusyuSyukeiMente.txtKbnNM").select();
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id2, data, complete_fun2);
    };
    /*
	 '**********************************************************************
	 '処 理 名：キャンセルボタンクリック
	 '関 数 名：cmdCan_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdCan_Click = function () {
        gdmz.common.jqgrid.reloadMessage(me.grid_id2, "", me.complete_fun2);
        me.subClearForm();
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面項目クリア
	 '関 数 名：subClearForm
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.subClearForm = function () {
        $(".FrmSyokusyuSyukeiMente.cmdDel").button("disable");
        $(".FrmSyokusyuSyukeiMente.txtKbn").attr("disabled", false);
        $(".FrmSyokusyuSyukeiMente.txtKbn").val("");
        $(".FrmSyokusyuSyukeiMente.txtKbnNM").val("");
        $(".FrmSyokusyuSyukeiMente.txtOrder").val("");
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録ボタンクリック
	 '関 数 名：cmdReg_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdReg_Click = function () {
        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }

        $(me.grid_id2).jqGrid("saveRow", me.lastSel2);
        var rows = $(me.grid_id2).jqGrid("getRowData");

        //職種コード 必須チェック
        var checkRet = 9;
        for (var i = 0; i < rows.length; i++) {
            if (me.clsComFnc.FncNv(rows[i]["display_code"]) != "") {
                checkRet = 1;
                break;
            }
            checkRet = 9;
        }
        if (checkRet == 9) {
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                $(me.grid_id2).jqGrid("setSelection", me.lastSel2, true);
            };
            me.clsComFnc.ObjFocus = $(me.grid_id2);
            me.clsComFnc.FncMsgBox("W0001", "職種コード");
            return false;
        }

        //重複チェック
        var insertData = [];
        for (var intRow = 0; intRow < rows.length - 1; intRow++) {
            for (
                var CheckRow = intRow + 1;
                CheckRow < rows.length;
                CheckRow++
            ) {
                if (
                    me.clsComFnc.FncNv(rows[intRow]["display_code"]) != "" &&
                    me.clsComFnc.FncNv(rows[CheckRow]["display_code"]) != ""
                ) {
                    if (
                        me.clsComFnc.FncNv(rows[intRow]["display_code"]) ==
                        me.clsComFnc.FncNv(rows[CheckRow]["display_code"])
                    ) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(me.grid_id2).jqGrid(
                                "setSelection",
                                CheckRow,
                                true
                            );
                        };
                        me.clsComFnc.ObjFocus = $(me.grid_id2);
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "職種コードが重複しています。"
                        );
                        return false;
                    }
                }
            }

            if (me.clsComFnc.FncNv(rows[intRow]["display_code"]) != "") {
                insertData.push(rows[intRow]["display_code"]);
            }
        }
        if (me.clsComFnc.FncNv(rows[rows.length - 1]["display_code"]) != "") {
            insertData.push(rows[rows.length - 1]["display_code"]);
        }

        //(評語職種集計区分マスタ)
        me.url = me.sys_id + "/" + me.id + "/FncUpdInsHSSTTLKBNMST";
        me.data = {
            txtKbn: $.trim($(".FrmSyokusyuSyukeiMente.txtKbn").val()),
            txtKbnNM: $.trim($(".FrmSyokusyuSyukeiMente.txtKbnNM").val()),
            txtOrder: $.trim($(".FrmSyokusyuSyukeiMente.txtOrder").val()),
            isDisabled: $(".FrmSyokusyuSyukeiMente.txtKbn").prop("disabled"),
            data: insertData,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //登録完了メッセージ
                me.clsComFnc.FncMsgBox("I0005");

                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.formit();
            } else {
                if (result["error"] == "W9999") {
                    //重複チェック(新規登録の場合のみ)
                    me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbn");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "職種集計区分が重複しています。"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fncInputChk
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fncInputChk = function () {
        //職種集計区分 必須チェック・入力値チェック・桁数チェック
        var checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmSyokusyuSyukeiMente.txtKbn"),
            1,
            me.clsComFnc.INPUTTYPE.NUMBER1,
            3
        );
        if (checkRet == -1) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbn");
            me.clsComFnc.FncMsgBox("W0001", "職種集計区分");
            return false;
        }
        if (checkRet == -2) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbn");
            me.clsComFnc.FncMsgBox("W0002", "職種集計区分");
            return false;
        }
        if (checkRet == -3) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbn");
            me.clsComFnc.FncMsgBox("W0003", "職種集計区分");
            return false;
        }
        //職種集計区分名 必須チェック・桁数チェック
        var checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmSyokusyuSyukeiMente.txtKbnNM"),
            1,
            me.clsComFnc.INPUTTYPE.NONE,
            30
        );
        if (checkRet == -1) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbnNM");
            me.clsComFnc.FncMsgBox("W0001", "職種集計区分名");
            return false;
        }
        if (checkRet == -2) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbnNM");
            me.clsComFnc.FncMsgBox("W0002", "職種集計区分名");
            return false;
        }
        if (checkRet == -3) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtKbnNM");
            me.clsComFnc.FncMsgBox("W0003", "職種集計区分名");
            return false;
        }
        //出力順 入力値チェック・桁数チェック
        var checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmSyokusyuSyukeiMente.txtOrder"),
            0,
            me.clsComFnc.INPUTTYPE.NUMBER1,
            2
        );
        if (checkRet == -2) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtOrder");
            me.clsComFnc.FncMsgBox("W0002", "出力順");
            return false;
        }
        if (checkRet == -3) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyuSyukeiMente.txtOrder");
            me.clsComFnc.FncMsgBox("W0003", "出力順");
            return false;
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：削除ボタンクリック
	 '関 数 名：cmdDel_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdDel_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesDeleteFnc;
        //確認メッセージ
        me.clsComFnc.FncMsgBox("QY004");
    };
    /*
	 '**********************************************************************
	 '処 理 名：削除[はい]ボタンクリック
	 '関 数 名：YesDeleteFnc
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.YesDeleteFnc = function () {
        me.url = me.sys_id + "/" + me.id + "/FncDelHSSTTLKBNMST";
        me.data = {
            txtKbn: $.trim($(".FrmSyokusyuSyukeiMente.txtKbn").val()),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //完了メッセージ
                me.clsComFnc.ObjFocus = $(me.grid_id1);
                me.clsComFnc.FncMsgBox("I0004");

                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.formit();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面初期化(一覧クリア)
	 '関 数 名：formit2
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.formit2 = function () {
        //スプレッド上
        $(me.grid_id1).jqGrid("clearGridData");
        //スプレッド下
        $(me.grid_id2).jqGrid("clearGridData");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyokusyuSyukeiMente = new JKSYS.FrmSyokusyuSyukeiMente();
    o_JKSYS_FrmSyokusyuSyukeiMente.load();
});
