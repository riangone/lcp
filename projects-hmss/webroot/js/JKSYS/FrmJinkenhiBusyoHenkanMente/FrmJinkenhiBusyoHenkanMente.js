// '**********************************************************************
//  人件費部署変換マスタ
// '**********************************************************************
Namespace.register("JKSYS.FrmJinkenhiBusyoHenkanMente");

JKSYS.FrmJinkenhiBusyoHenkanMente = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "JKSYS/FrmJinkenhiBusyoHenkanMente";
    me.grid_id = "#JKSYS_FrmJinkenhiBusyoHenkanMente_sprList";

    me.BusyoArr = new Array();
    me.BusyoArrJKB = new Array();

    me.selRowID = undefined;

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
            name: "BEF_BUSYO_CD",
            label: "コード",
            index: "BEF_BUSYO_CD",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "BEFORE_NM",
            label: "名称",
            index: "BEFORE_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "AFT_BUSYO_CD",
            label: "コード",
            index: "AFT_BUSYO_CD",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "AFTER_NM",
            label: "名称",
            index: "AFTER_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmJinkenhiBusyoHenkanMente.btnSelect",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiBusyoHenkanMente.cmdCan",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiBusyoHenkanMente.cmdReg",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiBusyoHenkanMente.cmdDel",
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

    //選択ボタンクリック
    $(".FrmJinkenhiBusyoHenkanMente.btnSelect").click(function () {
        me.btnSelect_Click();
    });

    //キャンセルボタンクリック
    $(".FrmJinkenhiBusyoHenkanMente.cmdCan").click(function () {
        me.cmdCan_Click();
    });

    //登録ボタンクリック
    $(".FrmJinkenhiBusyoHenkanMente.cmdReg").click(function () {
        me.cmdReg_Click();
    });

    //削除ボタンクリック
    $(".FrmJinkenhiBusyoHenkanMente.cmdDel").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdDel_Click;
        //確認メッセージ
        me.clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmJinkenhiBusyoHenkanMente.txtAfter").on("blur", function () {
        me.txtAfter_Validating();
    });
    $(".FrmJinkenhiBusyoHenkanMente.txtBefore").on("blur", function () {
        me.txtBefore_Validating();
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
        //画面初期化
        me.Formit(true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面初期化(画面起動時)
	 '関 数 名：Formit
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.Formit = function (load) {
        //画面初期化(一覧)
        me.Formit2();
        var data = {};
        //データ取得(人件費部署変換マスタ)
        var url = me.id + "/" + "FncGetBUSYOCNV";
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                $(".FrmJinkenhiBusyoHenkanMente").attr("disabled", true);
                $(".FrmJinkenhiBusyoHenkanMente button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(me.grid_id).jqGrid("setSelection", 0, true);
            //コントロール初期化
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").attr("disabled", false);
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").val("");
            $(".FrmJinkenhiBusyoHenkanMente.lblBefore").val("");
            $(".FrmJinkenhiBusyoHenkanMente.txtAfter").val("");
            $(".FrmJinkenhiBusyoHenkanMente.lblAfter").val("");
            $(".FrmJinkenhiBusyoHenkanMente.cmdDel").button("disable");
            //部門マスタ取得
            if (load) {
                me.getAllBusyoNM();
            }
        };
        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 690);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 230 : 260
        );
        if (load) {
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "BEF_BUSYO_CD",
                        numberOfColumns: 2,
                        titleText: "変換前",
                    },
                    {
                        startColumnName: "AFT_BUSYO_CD",
                        numberOfColumns: 2,
                        titleText: "変換後",
                    },
                ],
            });

            $(me.grid_id).jqGrid("bindKeys");
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面初期化(一覧クリア)
	 '関 数 名：Formit2
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.Formit2 = function () {
        //スプレッド上
        $(me.grid_id).jqGrid("clearGridData");
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
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        //行が選択されていない場合ｴﾗｰ
        if (id == null || id == undefined) {
            me.clsComFnc.FncMsgBox("I0010");
            return;
        } else {
            me.selRowID = id;

            var rowData = $(me.grid_id).jqGrid("getRowData", id);
            //選択した行の内容を入力領域に表示する
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").val(
                me.clsComFnc.FncNv(rowData["BEF_BUSYO_CD"])
            );
            $(".FrmJinkenhiBusyoHenkanMente.lblBefore").val(
                me.clsComFnc.FncNv(rowData["BEFORE_NM"])
            );
            $(".FrmJinkenhiBusyoHenkanMente.txtAfter").val(
                me.clsComFnc.FncNv(rowData["AFT_BUSYO_CD"])
            );
            $(".FrmJinkenhiBusyoHenkanMente.lblAfter").val(
                me.clsComFnc.FncNv(rowData["AFTER_NM"])
            );

            //ｺﾝﾄﾛｰﾙの制御
            me.subCtlForm();

            //-----背景色設定（正常）-----
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmJinkenhiBusyoHenkanMente.txtAfter").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
        }
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
        //画面項目クリア
        me.subClearForm();

        //-----背景色設定（正常）-----
        $(".FrmJinkenhiBusyoHenkanMente.txtBefore").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmJinkenhiBusyoHenkanMente.txtAfter").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
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
        if (me.fucInputChk() == false) {
            return;
        }
        var txtBefore = $.trim(
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").val()
        );
        var txtAfter = $.trim($(".FrmJinkenhiBusyoHenkanMente.txtAfter").val());
        //重複・存在チェック(新規登録の場合のみ)
        if (
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").prop("disabled") ==
            false
        ) {
            //データ取得(部署マスタ)
            if (me.FncGetBusyoNM(txtBefore) == "") {
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtBefore"
                );
                //存在しない場合
                me.clsComFnc.FncMsgBox("W9999", "部署コードが存在しません。");
                return;
            }
        }
        //変更後部署コード存在チェック
        if (me.FncGetBusyoNM(txtAfter) == "") {
            me.clsComFnc.ObjFocus = $(".FrmJinkenhiBusyoHenkanMente.txtAfter");
            //存在しない場合
            me.clsComFnc.FncMsgBox("W9999", "部署コードが存在しません。");
            return;
        }
        //変換前、変換後部署コードチェック
        if (txtBefore == txtAfter) {
            me.clsComFnc.ObjFocus = $(".FrmJinkenhiBusyoHenkanMente.txtAfter");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "変換前・変換後の部署コードが同じです。"
            );
            return;
        }
        if (
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").prop("disabled") == true
        ) {
            //修正の場合
            var url = me.id + "/" + "FncUpdBUSYOCNV";
        } else {
            //新規登録の場合
            var url = me.id + "/" + "FncInsBUSYOCNV";
        }
        //***** データを登録する **********
        var data = {
            txtAfter: txtAfter,
            txtBefore: txtBefore,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiBusyoHenkanMente.txtBefore"
                    );
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //登録完了メッセージ
                me.clsComFnc.FncMsgBox("I0005");
                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.Formit(false);
            }
        };
        me.ajax.send(url, data, 0);
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
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (me.selRowID) {
            id = me.selRowID;
        }
        var rowData = $(me.grid_id).jqGrid("getRowData", id);

        var url = me.id + "/" + "FncDelBUSYOCNV";
        var data = {
            BEFORE: rowData["BEF_BUSYO_CD"],
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //完了メッセージ
                me.clsComFnc.FncMsgBox("I0004");
                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.Formit(false);
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：コントロール制御
	 '関 数 名：subCtlForm
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.subCtlForm = function () {
        $(".FrmJinkenhiBusyoHenkanMente.txtBefore").attr("disabled", true);
        $(".FrmJinkenhiBusyoHenkanMente.cmdDel").button("enable");

        $(".FrmJinkenhiBusyoHenkanMente.txtAfter").select();
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
        //コントロール初期化
        $(".FrmJinkenhiBusyoHenkanMente.txtBefore").attr("disabled", false);
        $(".FrmJinkenhiBusyoHenkanMente.txtAfter").val("");
        $(".FrmJinkenhiBusyoHenkanMente.lblAfter").val("");
        $(".FrmJinkenhiBusyoHenkanMente.txtBefore").val("");
        $(".FrmJinkenhiBusyoHenkanMente.lblBefore").val("");
        $(".FrmJinkenhiBusyoHenkanMente.cmdDel").button("disable");
    };
    /*
	 '**********************************************************************
	 '処 理 名：変換前部署コードのフォーカスを抜けた時
	 '関 数 名：txtBefore_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.txtBefore_Validating = function () {
        $(".FrmJinkenhiBusyoHenkanMente.lblBefore").val(
            me.clsComFnc.FncNv(
                me.FncGetBusyoNM(
                    $.trim($(".FrmJinkenhiBusyoHenkanMente.txtBefore").val())
                )
            )
        );
        if ($(".FrmJinkenhiBusyoHenkanMente.lblBefore").val() != "") {
            //-----背景色設定（正常）-----
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：変換後部署コードのフォーカスを抜けた時
	 '関 数 名：txtAfter_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.txtAfter_Validating = function () {
        $(".FrmJinkenhiBusyoHenkanMente.lblAfter").val(
            me.clsComFnc.FncNv(
                me.FncGetBusyoNM(
                    $.trim($(".FrmJinkenhiBusyoHenkanMente.txtAfter").val())
                )
            )
        );
        if ($(".FrmJinkenhiBusyoHenkanMente.lblAfter").val() != "") {
            //-----背景色設定（正常）-----
            $(".FrmJinkenhiBusyoHenkanMente.txtAfter").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fucInputChk
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fucInputChk = function () {
        var checkRet = 0;
        //変更前部署コード 必須チェック・入力値チェック・桁数チェック
        checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmJinkenhiBusyoHenkanMente.txtBefore"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2,
            3
        );
        switch (checkRet) {
            case -1:
                //必須エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtBefore"
                );
                me.clsComFnc.FncMsgBox("W0001", "変更前部署コード");
                return false;

            case -2:
                //不正文字エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtBefore"
                );
                me.clsComFnc.FncMsgBox("W0002", "変更前部署コード");
                return false;

            case -3:
                //桁数エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtBefore"
                );
                me.clsComFnc.FncMsgBox("W0003", "変更前部署コード");
                return false;
        }

        //変更後部署コード 必須チェック・入力値チェック・桁数チェック
        checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmJinkenhiBusyoHenkanMente.txtAfter"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2,
            3
        );
        switch (checkRet) {
            case -1:
                //必須エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtAfter"
                );
                me.clsComFnc.FncMsgBox("W0001", "変更後部署コード");
                return false;

            case -2:
                //不正文字エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtAfter"
                );
                me.clsComFnc.FncMsgBox("W0002", "変更後部署コード");
                return false;

            case -3:
                //桁数エラー
                me.clsComFnc.ObjFocus = $(
                    ".FrmJinkenhiBusyoHenkanMente.txtAfter"
                );
                me.clsComFnc.FncMsgBox("W0003", "変更後部署コード");
                return false;
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：変換前、変換後データ取得
	 '関 数 名：FncGetBusyoNM
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FncGetBusyoNM = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }

            for (key in me.BusyoArr) {
                //存在する場合
                if (strCD == me.BusyoArr[key]["BUSYO_CD"]) {
                    return me.BusyoArr[key]["BUSYO_NM"];
                }
            }

            return "";
        } catch (e) {
            return "";
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：変換前、変換後データ存在チェック
	 '関 数 名：checkBusyoNM
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.checkBusyoNM = function (strCD) {
        try {
            for (key in me.BusyoArrJKB) {
                if (strCD == me.BusyoArrJKB[key]["BEF_BUSYO_CD"]) {
                    return true;
                }
            }
            return false;
        } catch (e) {
            return false;
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：部門マスタ
	 '関 数 名：getAllBusyoNM
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getAllBusyoNM = function () {
        var url = me.id + "/" + "FncGetBUMON";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.BusyoArr = result["BUMON"];
            }
        };

        me.ajax.send(url, "", 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmJinkenhiBusyoHenkanMente =
        new JKSYS.FrmJinkenhiBusyoHenkanMente();
    o_JKSYS_FrmJinkenhiBusyoHenkanMente.load();
    o_JKSYS_JKSYS.FrmJinkenhiBusyoHenkanMente =
        o_JKSYS_FrmJinkenhiBusyoHenkanMente;
});
