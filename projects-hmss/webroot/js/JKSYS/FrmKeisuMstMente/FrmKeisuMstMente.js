/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("JKSYS.FrmKeisuMstMente");

JKSYS.FrmKeisuMstMente = function () {
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmKeisuMstMente";
    me.sys_id = "JKSYS";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.grid_id = "#FrmKeisuMstMente_FpSpread1";
    me.Keisu = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKeisuMstMente.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKeisuMstMente.cmdSelect",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKeisuMstMente.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKeisuMstMente.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKeisuMstMente.cmdRegist",
        type: "button",
        handle: "",
    });
    me.col = {
        KEISU_SYURUI_CD: "",
        KEISU_SYURUI_NM: "",
        KOUMOKU_NO: "",
        KOUMOKU_NM: "",
        RANGE_FROM: "",
        RANGE_TO: "",
        KEISU: "",
        ATAI_1: "",
    };
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "KEISU_SYURUI_CD",
            label: "係数種類コード",
            index: "KEISU_SYURUI_CD",
            width: 100,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "KEISU_SYURUI_NM",
            label: "係数種類",
            index: "KEISU_SYURUI_NM",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "KOUMOKU_NO",
            label: "項目番号",
            index: "KOUMOKU_NO",
            width: 100,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "KOUMOKU_NM",
            label: "項目",
            index: "KOUMOKU_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "RANGE_FROM",
            label: "From",
            index: "RANGE_FROM",
            width: 58,
            align: "right",
            sortable: false,
        },
        {
            name: "RANGE_TO",
            label: "To",
            index: "RANGE_TO",
            width: 58,
            align: "right",
            sortable: false,
        },
        {
            name: "KEISU",
            label: "係数",
            index: "KEISU",
            width: 58,
            align: "right",
            sortable: false,
        },
        {
            name: "ATAI_1",
            label: "値1",
            index: "ATAI_1",
            width: 180,
            align: "left",
            hidden: true,
            sortable: false,
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

    //検索条件_営業業績
    $(".FrmKeisuMstMente.rdbEigyouSch").change(function () {
        me.rdbEigyouSch_Click();
    });
    //検索条件_店長
    $(".FrmKeisuMstMente.rdbTencyouSch").change(function () {
        me.rdbTencyouSch_Click();
    });
    //検索条件_係数種類
    $(".FrmKeisuMstMente.cmbKeisuSch").change(function () {
        me.cmbKeisuSch_SelectionChangeCommitted();
    });
    //営業業績
    $(".FrmKeisuMstMente.rdbEigyou").change(function () {
        me.rdbEigyou_Click();
    });
    //店長
    $(".FrmKeisuMstMente.rdbTencyou").change(function () {
        me.rdbTencyou_Click();
    });
    //係数種類
    $(".FrmKeisuMstMente.cmbKeisu").change(function () {
        me.cmbKeisu_SelectedIndexChanged();
    });
    //検索
    $(".FrmKeisuMstMente.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });
    //選択
    $(".FrmKeisuMstMente.cmdSelect").click(function () {
        me.cmdSelect_Click();
    });
    //登録ボタン
    $(".FrmKeisuMstMente.cmdRegist").click(function () {
        me.cmdRegist_Click();
    });
    //キャンセル
    $(".FrmKeisuMstMente.cmdCancel").click(function () {
        me.cmdCancel_Click();
    });
    //削除
    $(".FrmKeisuMstMente.cmdDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdDelete_Click;
        me.clsComFnc.FncMsgBox("QY004");
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

        me.frmKeisuMstMente_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：frmKeisuMstMente_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.frmKeisuMstMente_Load = function () {
        //初期処理
        //画面項目クリア
        me.subClearForm();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        me.initSpread();

        $(".FrmKeisuMstMente.rdbEigyouSch").trigger("focus");
    };
    /*
     '**********************************************************************
     '処 理 名：奨励金区分_営業業績ラジオボタン（検索）
     '関 数 名：rdbEigyouSch_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.rdbEigyouSch_Click = function () {
        me.cmbKeisuSch_SelectionChangeCommitted();
        //奨励金処理マスタ営業業績項目の取得
        me.url = me.sys_id + "/" + me.id + "/fncSyoureikinMstEigyou";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(".FrmKeisuMstMente.cmbKeisuSch").empty();
            $("<option></option>")
                .val("999999")
                .text("")
                .appendTo(".FrmKeisuMstMente.cmbKeisuSch");

            var dt = result["data"];
            for (var i = 0; i < result["row"]; i++) {
                $("<option></option>")
                    .val(dt[i]["CODE"])
                    .text(dt[i]["MEISYO"])
                    .appendTo(".FrmKeisuMstMente.cmbKeisuSch");
            }
        };

        me.ajax.send(me.url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：奨励金区分_店長ラジオボタン（検索）
     '関 数 名：rdbTencyouSch_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.rdbTencyouSch_Click = function () {
        me.cmbKeisuSch_SelectionChangeCommitted();
        //店長チェックONの場合、コンボボックス項目取得
        me.url = me.sys_id + "/" + me.id + "/fncSyoureikinMstTencyou";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(".FrmKeisuMstMente.cmbKeisuSch").empty();
            $("<option></option>")
                .val("999999")
                .text("")
                .appendTo(".FrmKeisuMstMente.cmbKeisuSch");

            var dt = result["data"];
            for (var i = 0; i < result["row"]; i++) {
                $("<option></option>")
                    .val(dt[i]["CODE"])
                    .text(dt[i]["MEISYO"])
                    .appendTo(".FrmKeisuMstMente.cmbKeisuSch");
            }
        };

        me.ajax.send(me.url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：係数種類（検索）
     '関 数 名：cmbKeisuSch_SelectionChangeCommitted
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmbKeisuSch_SelectionChangeCommitted = function () {
        //一覧、入力エリアのクリア
        me.subListInputClear();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        $(me.grid_id).jqGrid("clearGridData");
    };
    /*
     '**********************************************************************
     '処 理 名：検索ボタン
     '関 数 名：cmdSearch_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdSearch_Click = function () {
        //入力チェック1
        if (!me.fncInputChk1()) {
            return;
        }
        //検索項目設定
        me.fncSearchSpread();
    };
    /*
     '**********************************************************************
     '処 理 名：選択ボタン
     '関 数 名：cmdSelect_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdSelect_Click = function () {
        //行が選択されていない場合ｴﾗｰ
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (id == null) {
            me.clsComFnc.FncMsgBox("W9999", "行を選択してください。");
            return;
        }
        //選択項目反映
        if ($("input[name='kinKbnSch']:checked").val() == "rdbEigyouSch") {
            $(".FrmKeisuMstMente.rdbEigyou").prop("checked", "checked");
        } else {
            $(".FrmKeisuMstMente.rdbTencyou").prop("checked", "checked");
        }
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        $(".FrmKeisuMstMente.cmbKeisu").empty();
        $("<option></option>")
            .val(me.clsComFnc.FncNv(rowData["KEISU_SYURUI_CD"]))
            .text(me.clsComFnc.FncNv(rowData["KEISU_SYURUI_NM"]))
            .appendTo(".FrmKeisuMstMente.cmbKeisu");

        $(".FrmKeisuMstMente.cmbKomok").empty();
        $("<option></option>")
            .val(me.clsComFnc.FncNv(rowData["KOUMOKU_NO"]))
            .text(me.clsComFnc.FncNv(rowData["KOUMOKU_NM"]))
            .appendTo(".FrmKeisuMstMente.cmbKomok");

        var kinKbn = $("input[name='kinKbn']:checked").val();
        var ATAI_1 = rowData["ATAI_1"];
        if (
            (kinKbn == "rdbEigyou" && ATAI_1 == "1") ||
            kinKbn == "rdbTencyou"
        ) {
            $(".FrmKeisuMstMente.txtHaniS").prop("disabled", false);
            $(".FrmKeisuMstMente.txtHaniE").prop("disabled", false);
        } else {
            $(".FrmKeisuMstMente.txtHaniS").prop("disabled", true);
            $(".FrmKeisuMstMente.txtHaniE").prop("disabled", true);
            $(".FrmKeisuMstMente.txtHaniS").val("");
            $(".FrmKeisuMstMente.txtHaniE").val("");
        }

        $(".FrmKeisuMstMente.txtHaniS").val(rowData["RANGE_FROM"]);
        $(".FrmKeisuMstMente.txtHaniE").val(rowData["RANGE_TO"]);
        $(".FrmKeisuMstMente.txtKeisu").val(rowData["KEISU"]);

        //画面項目クリア
        me.subClearFormSel();
    };
    /*
     '**********************************************************************
     '処 理 名：奨励金区分_営業業績ラジオボタン
     '関 数 名：rdbEigyou_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.rdbEigyou_Click = function () {
        //奨励金処理マスタ営業業績項目の取得
        me.url = me.sys_id + "/" + me.id + "/fncSyoureikinMstEigyou";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.Keisu = result["data"];
            $(".FrmKeisuMstMente.cmbKeisu").empty();
            $(".FrmKeisuMstMente.cmbKeisu").text("");
            $("<option></option>")
                .val("999999")
                .text("")
                .appendTo(".FrmKeisuMstMente.cmbKeisu");

            var dt = result["data"];
            for (var i = 0; i < result["row"]; i++) {
                $("<option></option>")
                    .val(dt[i]["CODE"])
                    .text(dt[i]["MEISYO"])
                    .appendTo(".FrmKeisuMstMente.cmbKeisu");
            }

            // VBで実行
            me.cmbKeisu_SelectedIndexChanged();
        };

        $(".FrmKeisuMstMente.cmbKomok").empty();
        $(".FrmKeisuMstMente.cmbKomok").text("");

        me.ajax.send(me.url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：奨励金区分_店長ラジオボタン
     '関 数 名：rdbTencyou_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.rdbTencyou_Click = function () {
        //奨励金処理マスタ店長項目の取得
        me.url = me.sys_id + "/" + me.id + "/fncSyoureikinMstTencyou";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.Keisu = result["data"];
            $(".FrmKeisuMstMente.cmbKeisu").empty();
            $(".FrmKeisuMstMente.cmbKeisu").text("");
            $("<option></option>")
                .val("999999")
                .text("")
                .appendTo(".FrmKeisuMstMente.cmbKeisu");

            var dt = result["data"];
            for (var i = 0; i < result["row"]; i++) {
                $("<option></option>")
                    .val(dt[i]["CODE"])
                    .text(dt[i]["MEISYO"])
                    .appendTo(".FrmKeisuMstMente.cmbKeisu");
            }

            // VBで実行
            me.cmbKeisu_SelectedIndexChanged();
        };

        $(".FrmKeisuMstMente.cmbKomok").empty();
        $(".FrmKeisuMstMente.cmbKomok").text("");

        me.ajax.send(me.url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：係数種類
     '関 数 名：cmbKeisu_SelectedIndexChanged
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmbKeisu_SelectedIndexChanged = function () {
        var kinKbn = $("input[name='kinKbn']:checked").val();
        var cmbKeisu = $(".FrmKeisuMstMente.cmbKeisu").val();
        var ATAI_1 = "";
        for (var i = 0; i < me.Keisu.length; i++) {
            if (me.Keisu[i]["CODE"] == cmbKeisu) {
                ATAI_1 = me.Keisu[i]["ATAI_1"];
                break;
            }
        }
        if (
            (kinKbn == "rdbEigyou" && ATAI_1 == "1") ||
            kinKbn == "rdbTencyou"
        ) {
            $(".FrmKeisuMstMente.txtHaniS").prop("disabled", false);
            $(".FrmKeisuMstMente.txtHaniE").prop("disabled", false);
        } else {
            $(".FrmKeisuMstMente.txtHaniS").prop("disabled", true);
            $(".FrmKeisuMstMente.txtHaniE").prop("disabled", true);
            $(".FrmKeisuMstMente.txtHaniS").val("");
            $(".FrmKeisuMstMente.txtHaniE").val("");
        }
        if (cmbKeisu == "999999") {
            $(".FrmKeisuMstMente.cmbKomok").empty();
        } else {
            me.data = {
                kinKbn: kinKbn,
                cmbKeisu: cmbKeisu,
            };

            me.url = me.sys_id + "/" + me.id + "/fncSyoureikinMstKmk";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                $(".FrmKeisuMstMente.cmbKomok").empty();
                $("<option></option>")
                    .val("999999")
                    .text("")
                    .appendTo(".FrmKeisuMstMente.cmbKomok");

                var dt = result["data"];
                for (var i = 0; i < result["row"]; i++) {
                    $("<option></option>")
                        .val(dt[i]["CODE"])
                        .text(dt[i]["MEISYO"])
                        .appendTo(".FrmKeisuMstMente.cmbKomok");
                }
            };

            me.ajax.send(me.url, me.data, 0);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：登録ボタン
     '関 数 名：cmdRegist_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdRegist_Click = function () {
        //入力チェック2
        me.fncInputChk2();
    };
    /*
     '**********************************************************************
     '処 理 名：登録ボタン
     '関 数 名：fncKeisuMstIns
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncKeisuMstIns = function () {
        var isrdbEigyou = !$(".FrmKeisuMstMente.rdbEigyou").prop("disabled");
        var kinKbn = $("input[name='kinKbn']:checked").val();
        var cmbKeisu = $(".FrmKeisuMstMente.cmbKeisu").val();
        var cmbKomok = $(".FrmKeisuMstMente.cmbKomok").val();
        var txtHaniE = $(".FrmKeisuMstMente.txtHaniE").val();
        var txtHaniS = $(".FrmKeisuMstMente.txtHaniS").val();
        var txtKeisu = $(".FrmKeisuMstMente.txtKeisu").val();

        me.data = {
            isrdbEigyou: isrdbEigyou,
            kinKbn: kinKbn,
            cmbKeisu: cmbKeisu,
            cmbKomok: cmbKomok,
            txtHaniS: txtHaniS,
            txtHaniE: txtHaniE,
            txtKeisu: txtKeisu,
        };

        me.url = me.sys_id + "/" + me.id + "/fncKeisuMstIns";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                //完了メッセージ
                if (!$(".FrmKeisuMstMente.rdbEigyou").prop("disabled")) {
                    me.clsComFnc.FncMsgBox("I0002");

                    me.cmdCancel_Click();
                } else {
                    me.clsComFnc.FncMsgBox("I0003");

                    //検索項目設定
                    me.fncSearchSpread();
                }
            }
        };

        me.ajax.send(me.url, me.data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：削除ボタン
     '関 数 名：cmdDelete_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdDelete_Click = function () {
        var kinKbn = $("input[name='kinKbn']:checked").val();
        var cmbKeisu = $(".FrmKeisuMstMente.cmbKeisu").val();
        var cmbKomok = $(".FrmKeisuMstMente.cmbKomok").val();

        me.data = {
            kinKbn: kinKbn,
            cmbKeisu: cmbKeisu,
            cmbKomok: cmbKomok,
        };

        me.url = me.sys_id + "/" + me.id + "/fncKeisuMstDel";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
            //検索項目設定
            me.fncSearchSpread();
        };

        me.ajax.send(me.url, me.data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：キャンセルボタン
     '関 数 名：cmdCancel_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdCancel_Click = function () {
        //画面項目クリア
        me.subClearForm();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        $(me.grid_id).jqGrid("clearGridData");
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
        $(".FrmKeisuMstMente.rdbEigyouSch").prop("checked", false);
        $(".FrmKeisuMstMente.rdbTencyouSch").prop("checked", false);
        $(".FrmKeisuMstMente.cmbKeisuSch").empty();
        $(".FrmKeisuMstMente.cmdSearch").button("enable");

        me.subListInputClear();

        $(".FrmKeisuMstMente.cmdCancel").button("enable");
        $(".FrmKeisuMstMente.cmdRegist").button("enable");
        $(".FrmKeisuMstMente.cmdDelete").button("disable");
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目クリア（検索時）
     '関 数 名：subClearFormSch
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subClearFormSch = function () {
        me.subListInputClear();
        $(".FrmKeisuMstMente.cmdSelect").button("enable");

        $(".FrmKeisuMstMente.cmdCancel").button("enable");
        $(".FrmKeisuMstMente.cmdRegist").button("disable");
        $(".FrmKeisuMstMente.cmdDelete").button("disable");
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目クリア（選択時）
     '関 数 名：subClearFormSel
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subClearFormSel = function () {
        $(".FrmKeisuMstMente.cmdSelect").button("enable");

        $(".FrmKeisuMstMente.rdbEigyou").prop("disabled", true);
        $(".FrmKeisuMstMente.rdbTencyou").prop("disabled", true);
        $(".FrmKeisuMstMente.cmbKeisu").prop("disabled", true);
        $(".FrmKeisuMstMente.cmbKomok").prop("disabled", true);

        $(".FrmKeisuMstMente.cmdCancel").button("enable");
        $(".FrmKeisuMstMente.cmdRegist").button("enable");
        $(".FrmKeisuMstMente.cmdDelete").button("enable");

        //初期色セット
        me.subResetColor();
    };
    /*
     '**********************************************************************
     '処 理 名：一覧、入力エリアクリア
     '関 数 名：subListInputClear
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subListInputClear = function () {
        $(".FrmKeisuMstMente.cmdSelect").button("disable");
        $(".FrmKeisuMstMente.rdbEigyou").prop("disabled", false);
        $(".FrmKeisuMstMente.rdbEigyou").prop("checked", false);
        $(".FrmKeisuMstMente.rdbTencyou").prop("disabled", false);
        $(".FrmKeisuMstMente.rdbTencyou").prop("checked", false);
        $(".FrmKeisuMstMente.cmbKeisu").empty();
        $(".FrmKeisuMstMente.cmbKomok").empty();
        $(".FrmKeisuMstMente.cmbKeisu").prop("disabled", false);
        $(".FrmKeisuMstMente.cmbKomok").prop("disabled", false);
        $(".FrmKeisuMstMente.txtHaniS").val("");
        $(".FrmKeisuMstMente.txtHaniE").val("");
        $(".FrmKeisuMstMente.txtKeisu").val("");

        $(".FrmKeisuMstMente.cmdRegist").button("enable");

        //初期色セット
        me.subResetColor();
    };
    /*
     '**********************************************************************
     '処 理 名：初期色
     '関 数 名：subResetColor
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subResetColor = function () {
        $(".FrmKeisuMstMente.rdbEigyouSchDiv").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmKeisuMstMente.rdbTencyouSchDiv").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmKeisuMstMente.rdbEigyouDiv").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.rdbTencyouDiv").css(me.clsComFnc.GC_COLOR_NORMAL);

        $(".FrmKeisuMstMente.cmbKeisu").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.cmbKomok").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.txtKeisu").css(me.clsComFnc.GC_COLOR_NORMAL);
    };
    /*
     '**********************************************************************
     '処 理 名：入力チェック1
     '関 数 名：fncInputChk1
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncInputChk1 = function () {
        //初期色セット
        me.subResetColor();

        //検索条件_奨励金区分 必須チェック
        if ($("input[name='kinKbnSch']:checked").val() == undefined) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.rdbEigyouSch");
            $(".FrmKeisuMstMente.rdbEigyouSchDiv").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            $(".FrmKeisuMstMente.rdbTencyouSchDiv").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.FncMsgBox("W0001", "検索条件_奨励金区分");
            return false;
        }

        return true;
    };
    /*
     '**********************************************************************
     '処 理 名：入力チェック2
     '関 数 名：fncInputChk2
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncInputChk2 = function () {
        var ptnNumeric = /^-?[0-9]+$/;
        var ptnNumericDot = /^-?[0-9]+(\.[0-9]{1,2})?$/;
        //初期色セット
        me.subResetColor();
        //登録モードの場合のみチェック
        if (!$(".FrmKeisuMstMente.rdbEigyou").prop("disabled")) {
            //奨励金区分 必須チェック
            if ($("input[name='kinKbn']:checked").val() == undefined) {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.rdbEigyou");
                $(".FrmKeisuMstMente.rdbEigyouDiv").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                $(".FrmKeisuMstMente.rdbTencyouDiv").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0001", "奨励金区分");
                return;
            }
            //係数種類 必須チェック
            if ($(".FrmKeisuMstMente.cmbKeisu option:selected").text() == "") {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.cmbKeisu");
                $(".FrmKeisuMstMente.cmbKeisu").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0001", "係数種類");
                return;
            }
            //項目 必須チェック
            if ($(".FrmKeisuMstMente.cmbKomok option:selected").text() == "") {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.cmbKomok");
                $(".FrmKeisuMstMente.cmbKomok").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0001", "項目");
                return;
            }
        }

        //範囲指定ありの場合のみチェック
        if (!$(".FrmKeisuMstMente.txtHaniS").prop("disabled")) {
            if ($(".FrmKeisuMstMente.txtHaniS").val() == "") {
                me.clsComFnc.FncMsgBox("W0001", "範囲From");
                return;
            }
            if ($(".FrmKeisuMstMente.txtHaniE").val() == "") {
                me.clsComFnc.FncMsgBox("W0001", "範囲To");
                return;
            }
        }

        //範囲指定 どちらかが入力されている場合、チェック対象とする
        //範囲From 必須チェック, 数値チェック（正規化）, 桁数チェック（"."除外桁数）
        var checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmKeisuMstMente.txtHaniS"),
            1,
            me.clsComFnc.INPUTTYPE.NONE
        );
        if (checkRet == -1) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniS");
            me.clsComFnc.FncMsgBox("W0001", "範囲From");
            return;
        }
        if ($("input[name='kinKbn']:checked").val() == "rdbEigyou") {
            if (
                $(".FrmKeisuMstMente.txtHaniS").val() != "" &&
                !ptnNumeric.test($(".FrmKeisuMstMente.txtHaniS").val())
            ) {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniS");
                $(".FrmKeisuMstMente.txtHaniS").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0002", "範囲From");
                return;
            }
        } else {
            if (
                $(".FrmKeisuMstMente.txtHaniS").val() != "" &&
                !ptnNumericDot.test($(".FrmKeisuMstMente.txtHaniS").val())
            ) {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniS");
                $(".FrmKeisuMstMente.txtHaniS").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0002", "範囲From");
                return;
            }
        }
        var s1 = $(".FrmKeisuMstMente.txtHaniS").val();
        s1 = s1.replace(/\./g, "");
        if (s1.length > 6) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniS");
            $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0003", "範囲From");
            return;
        }

        //範囲To 必須チェック, 数値チェック（正規化）, 桁数チェック（"."除外桁数）
        var checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmKeisuMstMente.txtHaniE"),
            1,
            me.clsComFnc.INPUTTYPE.NONE
        );
        if (checkRet == -1) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniE");
            me.clsComFnc.FncMsgBox("W0001", "範囲To");
            return;
        }
        if ($("input[name='kinKbn']:checked").val() == "rdbEigyou") {
            if (
                $(".FrmKeisuMstMente.txtHaniE").val() != "" &&
                !ptnNumeric.test($(".FrmKeisuMstMente.txtHaniE").val())
            ) {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniE");
                $(".FrmKeisuMstMente.txtHaniE").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0002", "範囲To");
                return;
            }
        } else {
            if (
                $(".FrmKeisuMstMente.txtHaniE").val() != "" &&
                !ptnNumericDot.test($(".FrmKeisuMstMente.txtHaniE").val())
            ) {
                me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniE");
                $(".FrmKeisuMstMente.txtHaniE").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.FncMsgBox("W0002", "範囲To");
                return;
            }
        }
        var s2 = $(".FrmKeisuMstMente.txtHaniE").val();
        s2 = s2.replace(/\./g, "");
        if (s2.length > 6) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniE");
            $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0003", "範囲To");
            return;
        }

        //範囲チェック
        if (
            parseInt($(".FrmKeisuMstMente.txtHaniS").val() * 100) >
            parseInt($(".FrmKeisuMstMente.txtHaniE").val() * 100)
        ) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtHaniS");
            $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_ERROR);
            $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0006", "範囲");
            return;
        }

        //係数 必須チェック, 数値チェック（正規化）, 桁数チェック（"."除外桁数）
        checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmKeisuMstMente.txtKeisu"),
            1,
            me.clsComFnc.INPUTTYPE.NONE
        );
        if (checkRet == -1) {
            $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_NORMAL);
            $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_NORMAL);
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtKeisu");
            me.clsComFnc.FncMsgBox("W0001", "係数");
            return;
        }
        if (!ptnNumericDot.test($(".FrmKeisuMstMente.txtKeisu").val())) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtKeisu");
            $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_NORMAL);
            $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_NORMAL);
            $(".FrmKeisuMstMente.txtKeisu").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0002", "係数");
            return;
        }
        var s3 = $(".FrmKeisuMstMente.txtKeisu").val();
        s3 = s3.replace(/\./g, "");
        if (s3.length > 3) {
            me.clsComFnc.ObjFocus = $(".FrmKeisuMstMente.txtKeisu");
            $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_NORMAL);
            $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_NORMAL);
            $(".FrmKeisuMstMente.txtKeisu").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W0003", "係数");
            return;
        }

        $(".FrmKeisuMstMente.txtHaniS").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.txtHaniE").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmKeisuMstMente.txtKeisu").css(me.clsComFnc.GC_COLOR_NORMAL);

        if (
            !$(".FrmKeisuMstMente.rdbEigyou").prop("disabled") &&
            $(".FrmKeisuMstMente.txtHaniS").prop("disabled")
        ) {
            //データを登録する
            me.fncKeisuMstIns();
        } //登録モードの場合のみ存在チェック/範囲指定ありの場合のみ範囲が他ﾃﾞｰﾀと重なっていないかチェック
        else {
            var isrdbEigyou = !$(".FrmKeisuMstMente.rdbEigyou").prop(
                "disabled"
            );
            var kinKbn = $("input[name='kinKbn']:checked").val();
            var isHaniS = !$(".FrmKeisuMstMente.txtHaniS").prop("disabled");
            var cmbKeisu = $(".FrmKeisuMstMente.cmbKeisu").val();
            var cmbKomok = $(".FrmKeisuMstMente.cmbKomok").val();
            var txtHaniE = $(".FrmKeisuMstMente.txtHaniE").val();
            var txtHaniS = $(".FrmKeisuMstMente.txtHaniS").val();

            me.data = {
                isrdbEigyou: isrdbEigyou,
                kinKbn: kinKbn,
                cmbKeisu: cmbKeisu,
                isHaniS: isHaniS,
                cmbKomok: cmbKomok,
                txtHaniS: txtHaniS,
                txtHaniE: txtHaniE,
            };

            me.url = me.sys_id + "/" + me.id + "/fncKeisuMstChk";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                } else if (!result["data"]["fncKeisuMst"]) {
                    me.clsComFnc.FncMsgBox("W0004");
                    return;
                } else if (!result["data"]["fncKeisuMstChk"]) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "指定された範囲は他項目と重なっています。"
                    );
                    return;
                }
                //データを登録する
                me.fncKeisuMstIns();
            };

            me.ajax.send(me.url, me.data, 0);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：スプレッド（検索）の設定
     '関 数 名：fncSearchSpread
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncSearchSpread = function () {
        // jqgridデータクリア
        $(me.grid_id).jqGrid("clearGridData");

        var kinKbnSch = $("input[name='kinKbnSch']:checked").val();
        var cmbKeisuSch = $(".FrmKeisuMstMente.cmbKeisuSch").val();
        var data = {
            kinKbnSch: kinKbnSch,
            cmbKeisuSch: cmbKeisuSch,
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };
    me.complete_fun = function (returnFLG, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }
        if (returnFLG == "nodata") {
            me.clsComFnc.FncMsgBox("I0001");
            return;
        }

        $(me.grid_id).jqGrid("setSelection", 0, true);
        //画面項目クリア
        me.subClearFormSch();
    };
    /*
     '**********************************************************************
     '処 理 名：スプレッドの初期値設定
     '関 数 名：initSpread
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.initSpread = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 535);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 100 : 130
        );

        $(me.grid_id).jqGrid("bindKeys");

        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "RANGE_FROM",
                    numberOfColumns: 2,
                    titleText: "範囲",
                },
            ],
        });
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_JKSYS_FrmKeisuMstMente = new JKSYS.FrmKeisuMstMente();
    o_JKSYS_FrmKeisuMstMente.load();
});
