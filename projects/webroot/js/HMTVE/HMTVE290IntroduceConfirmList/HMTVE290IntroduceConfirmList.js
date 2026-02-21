/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE290IntroduceConfirmList");

HMTVE.HMTVE290IntroduceConfirmList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";

    me.sys_id = "HMTVE";
    me.id = "HMTVE290IntroduceConfirmList";
    me.grid_id = "#HMTVE290IntroduceConfirmList_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";

    me.last_selected_id = "";
    me.PatternID = gdmz.SessionPatternID;

    me.allBusyo = [];

    me.option = {
        caption: "",
        rowNum: 100,
        rownumbers: true,
        multiselect: false,
        viewrecords: false,
        scroll: 50,
    };

    me.colModel = [
        {
            label: "受理№",
            width: 80,
            align: "left",
            name: "JYURI_NO",
            index: "JYURI_NO",
            sortable: false,
        },
        {
            label: "提供日",
            width: 80,
            align: "left",
            name: "JYURI_DT",
            index: "JYURI_DT",
            sortable: false,
        },
        {
            label: "店舗",
            width: 80,
            align: "left",
            name: "BUSYO_RYKNM",
            index: "BUSYO_RYKNM",
            sortable: false,
        },
        {
            label: "担当者",
            name: "SYAIN_NM",
            index: "SYAIN_NM",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            label: "お客様",
            name: "OKYAKU_NM",
            index: "OKYAKU_NM",
            width: me.ratio === 1.5 ? 155 : 175,
            align: "left",
            sortable: false,
        },
        {
            label: "紹介者・窓口会社",
            name: "SYOUKAI_NM",
            index: "SYOUKAI_NM",
            width: me.ratio === 1.5 ? 155 : 175,
            align: "left",
            sortable: false,
        },
        {
            label: "店長<br/>ﾁｪｯｸ",
            name: "MANEGER_CHK",
            index: "MANEGER_CHK",
            width: 50,
            align: "left",
            sortable: false,
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "○",
                    2: "×",
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
            editable: true,
        },
        {
            label: "担当者<br/>ﾁｪｯｸ",
            name: "TANTO_CHK",
            index: "TANTO_CHK",
            width: 50,
            align: "left",
            sortable: false,
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "○",
                    2: "×",
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                ee.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
            editable: true,
        },
        {
            label: "承認",
            name: "SYOUNIN_FLG",
            index: "SYOUNIN_FLG",
            width: me.ratio === 1.5 ? 50 : 60,
            align: "left",
            sortable: false,
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "承認",
                    2: "不可",
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
            editable: true,
        },
        {
            label: "不備理由",
            name: "FUBI_RIYU",
            index: "FUBI_RIYU",
            width: me.ratio === 1.5 ? 120 : 140,
            align: "left",
            sortable: false,
            editoptions: {
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
            editable: true,
        },
        {
            label: "商談ﾌﾗｸﾞ",
            name: "SYOUDAN_FLG",
            index: "SYOUDAN_FLG",
            hidden: true,
        },
        {
            label: "店長チェック_チェック用",
            name: "MANEGER_CHK_CHK",
            index: "MANEGER_CHK_CHK",
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "○",
                    2: "×",
                },
            },
            hidden: true,
        },
        {
            label: "担当者チェック_チェック用",
            name: "TANTO_CHK_CHK",
            index: "TANTO_CHK_CHK",
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "○",
                    2: "×",
                },
            },
            hidden: true,
        },
        {
            label: "承認_チェック用",
            name: "SYOUNIN_FLG_CHK",
            index: "SYOUNIN_FLG_CHK",
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "○",
                    2: "×",
                },
            },
            hidden: true,
        },
        {
            label: "不備理由_チェック用",
            name: "FUBI_RIYU_CHK",
            index: "FUBI_RIYU_CHK",
            hidden: true,
        },
        {
            label: "店舗コード",
            name: "BUSYO_CD",
            index: "BUSYO_CD",
            hidden: true,
        },
        {
            label: "担当者コード",
            name: "SYAIN_NO",
            index: "SYAIN_NO",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE290IntroduceConfirmList.btnExpression",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE290IntroduceConfirmList.btnExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE290IntroduceConfirmList.btnLogin",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE290IntroduceConfirmList.btnConfirm",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMTVE.TabKeyDown();

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //登録ボタンクリック
    $(".HMTVE290IntroduceConfirmList.btnLogin").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
            $(me.grid_id).jqGrid("editRow", me.last_selected_id, true);
        };
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        //登録ボタンの確認メッセージの表示
        me.clsComFnc.FncMsgBox(
            "QY999",
            "紹介者確認データを更新します。よろしいですか？"
        );
    });
    //確認済みへボタンクリック
    $(".HMTVE290IntroduceConfirmList.btnConfirm").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
            $(me.grid_id).jqGrid("editRow", me.last_selected_id, true);
        };
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnConfirm_Click;
        //確認済みへボタンの確認メッセージの表示
        me.clsComFnc.FncMsgBox("QY999", "確定処理を行います。よろしいですか？");
    });
    //Excel出力ボタンクリック
    $(".HMTVE290IntroduceConfirmList.btnExcel").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnExcel_Click;
        //Excel出力ボタンの確認メッセージの表示
        me.clsComFnc.FncMsgBox("QY999", "EXCELを出力します。よろしいですか？");
    });
    //表示ボタンクリック
    $(".HMTVE290IntroduceConfirmList.btnExpression").click(function () {
        me.btnExpression_Click();
    });
    $(".HMTVE290IntroduceConfirmList.rdoNotConfirm").click(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.rdoConfirm").click(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.rdoTwo").click(function () {
        me.Clear_Again();
    });
    //FROM_年change
    $(".HMTVE290IntroduceConfirmList.ddlYear").change(function () {
        me.DLselectchange();
    });
    //FROM_月change
    $(".HMTVE290IntroduceConfirmList.ddlMonth").change(function () {
        me.DLselectchange();
    });
    //TO_年change
    $(".HMTVE290IntroduceConfirmList.ddlYear2").change(function () {
        me.DLselectchange2();
    });
    //TO_月change
    $(".HMTVE290IntroduceConfirmList.ddlMonth2").change(function () {
        me.DLselectchange2();
    });
    $(".HMTVE290IntroduceConfirmList.ddlYear").change(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.ddlYear2").change(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.ddlMonth").change(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.ddlMonth2").change(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.ddlDay").change(function () {
        me.Clear_Again();
    });
    $(".HMTVE290IntroduceConfirmList.ddlDay2").change(function () {
        me.Clear_Again();
    });
    //部署change
    $(".HMTVE290IntroduceConfirmList.txtPosition").change(function () {
        me.FoucsMove();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        try {
            base_init_control();

            //ページロード
            me.Page_Load();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面初期化
	 '関 数 名：Page_Load
	 '処理説明：ページ初期化
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        try {
            //表示設定,紹介者ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE290IntroduceConfirmList.tblDetail").hide();
            $(".HMTVE290IntroduceConfirmList.trInfo").hide();

            var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
            var data = {
                txtPosition: $(
                    ".HMTVE290IntroduceConfirmList.txtPosition"
                ).val(),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    $(".HMTVE290IntroduceConfirmList.btnExpression").button(
                        "disable"
                    );
                    $(".HMTVE290IntroduceConfirmList.btnExcel").button(
                        "disable"
                    );
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").button(
                        "disable"
                    );
                    $(".HMTVE290IntroduceConfirmList.btnLogin").button(
                        "disable"
                    );
                    if (result["data"]["message"]) {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return;
                }
                var complete_fun = function (_returnFLG, data) {
                    if (data["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", data["error"]);
                        return;
                    }

                    me.allBusyo = result["data"]["HBUSYO"];

                    $(me.grid_id).jqGrid("setGridParam", {
                        //選択行の修正画面を呼び出す
                        onSelectRow: function (rowId, _status, e) {
                            var focusIndex =
                                typeof e != "undefined"
                                    ? e.target.cellIndex !== undefined
                                        ? e.target.cellIndex
                                        : e.target.parentElement.cellIndex
                                    : false;

                            if (me.last_selected_id != "") {
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.last_selected_id
                                );
                            }
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowId
                            );
                            $(me.grid_id).setColProp("MANEGER_CHK", {
                                editable: false,
                            });
                            $(me.grid_id).setColProp("TANTO_CHK", {
                                editable: false,
                            });
                            $(me.grid_id).setColProp("FUBI_RIYU", {
                                editable: false,
                            });
                            $(me.grid_id).setColProp("SYOUNIN_FLG", {
                                editable: false,
                            });
                            //紹介者ﾃｰﾌﾞﾙ_店長チェック、紹介者ﾃｰﾌﾞﾙ_担当者チェック、紹介者ﾃｰﾌﾞﾙ_商談、紹介者ﾃｰﾌﾞﾙ_不備理由を入力不可(ReadOnly=True)にする
                            if (
                                $(
                                    ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                                ).is(":checked")
                            ) {
                                if (
                                    me.PatternID ==
                                        me.HMTVE.CONST_ADMIN_PTN_NO ||
                                    me.PatternID ==
                                        me.HMTVE.CONST_HONBU_PTN_NO ||
                                    me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                                ) {
                                    $(me.grid_id).setColProp("SYOUNIN_FLG", {
                                        editable: true,
                                    });
                                } else if (
                                    me.PatternID !=
                                        me.HMTVE.CONST_ADMIN_PTN_NO ||
                                    me.PatternID !=
                                        me.HMTVE.CONST_HONBU_PTN_NO ||
                                    me.PatternID != me.HMTVE.CONST_TESTER_PTN_NO
                                ) {
                                    //紹介者ﾃｰﾌﾞﾙ_店長チェック、紹介者ﾃｰﾌﾞﾙ_担当者チェック、紹介者ﾃｰﾌﾞﾙ_不備理由を入力可(ReadOnly=False)にする
                                    if (
                                        me.PatternID ==
                                        me.HMTVE.CONST_MANAGER_PTN_NO
                                    ) {
                                        $(me.grid_id).setColProp(
                                            "MANEGER_CHK",
                                            {
                                                editable: true,
                                            }
                                        );
                                    }
                                    if (
                                        result["data"]["SyainNM"] ==
                                        $.trim(rowData["SYAIN_NM"])
                                    ) {
                                        $(me.grid_id).setColProp("TANTO_CHK", {
                                            editable: true,
                                        });
                                    }
                                    if (
                                        $(me.grid_id).getColProp("MANEGER_CHK")
                                            .editable ||
                                        $(me.grid_id).getColProp("TANTO_CHK")
                                            .editable
                                    ) {
                                        $(me.grid_id).setColProp("FUBI_RIYU", {
                                            editable: true,
                                        });
                                    }
                                }
                            }
                            $(me.grid_id).jqGrid("editRow", rowId, {
                                keys: true,
                                focusField: focusIndex,
                            });

                            me.last_selected_id = rowId;

                            if (e) {
                                if (e.target && e.target.name) {
                                    me.lastCol = e.target.name;
                                } else {
                                    $td = $(e.target).closest("tr.jqgrow>td");
                                    if ($td && $td.length > 0) {
                                        var iCol = $.jgrid.getCellIndex($td[0]),
                                            colModel = $(this).jqGrid(
                                                "getGridParam",
                                                "colModel"
                                            ),
                                            targetCell = colModel[iCol];
                                        me.lastCol = targetCell.name;
                                    }
                                }

                                $(
                                    "#" + me.last_selected_id + "_" + me.lastCol
                                ).trigger("focus");
                                $(
                                    "#" + me.last_selected_id + "_" + me.lastCol
                                ).select();
                            }
                            var up_next_sel =
                                gdmz.common.jqgrid.setKeybordEvents(
                                    me.grid_id,
                                    e,
                                    me.last_selected_id
                                );
                            if (up_next_sel && up_next_sel.length == 2) {
                                me.upsel = up_next_sel[0];
                                me.nextsel = up_next_sel[1];
                            }
                        },
                    });
                    $(me.grid_id).jqGrid("bindKeys");
                    //画面項目をクリアする
                    me.ClearScreen();
                    //ボタンの設定
                    if (
                        me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        //画面項目5.部署コードにフォーカス移動
                        $(".HMTVE290IntroduceConfirmList.txtPosition").trigger(
                            "focus"
                        );
                    } else if (
                        me.PatternID != me.HMTVE.CONST_ADMIN_PTN_NO ||
                        me.PatternID != me.HMTVE.CONST_HONBU_PTN_NO ||
                        me.PatternID != me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        //Excel出力ボタンのVisible = False
                        $(".HMTVE290IntroduceConfirmList.btnExcel").hide();
                        //部署コードにSessionをセットする
                        $(".HMTVE290IntroduceConfirmList.txtPosition").val(
                            result["data"]["BusyoCD"]
                        );

                        me.FoucsMove();

                        //部署コードを入力不可にする
                        $(".HMTVE290IntroduceConfirmList.txtPosition").prop(
                            "disabled",
                            "disabled"
                        );
                        //日付_FROM_年にフォーカス移動
                        $(".HMTVE290IntroduceConfirmList.ddlYear").trigger(
                            "focus"
                        );
                    }
                    //登録ボタンを非表示にする
                    $(".HMTVE290IntroduceConfirmList.btnLogin").hide();
                    //確認済へボタンを非表示にする
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").hide();
                    //コンボリストを設定する()
                    me.getObjectTerm(result);
                    //店舗名を表示する
                    me.ExpressShopName(result);
                };
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    "",
                    "",
                    me.option,
                    {},
                    complete_fun
                );
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_id,
                    me.ratio === 1.5 ? 1023 : 1090
                );
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 255 : 300
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：表示ボタンのイベント
	 '関 数 名：btnExpression_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：検索画面の表示
	 '**********************************************************************
	 */
    me.btnExpression_Click = function () {
        try {
            //画面項目のクリア処理,紹介者ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE290IntroduceConfirmList.tblDetail").hide();
            $(".HMTVE290IntroduceConfirmList.trInfo").hide();
            //登録ボタンを非表示にする
            $(".HMTVE290IntroduceConfirmList.btnLogin").hide();
            //確認済みへボタンを非表示にする
            $(".HMTVE290IntroduceConfirmList.btnConfirm").hide();
            //入力チェックを行う
            if (me.CheckImport() == false) {
                return;
            }
            //データの取得
            //紹介者ﾃｰﾌﾞﾙの生成
            $(me.grid_id).jqGrid("clearGridData");
            var complete_fun = function (returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }

                if (me.CreateIntroducer() == false) {
                    return;
                }

                //紹介者ﾃｰﾌﾞﾙを表示する(Visible=True)
                $(".HMTVE290IntroduceConfirmList.tblDetail").show();
                $(".HMTVE290IntroduceConfirmList.trInfo").show();
                //画面制御を行う
                //項目の制御
                //パート１
                me.setItemsManagementPart1();

                //登録ボタンを表示する(Visible=True)
                $(".HMTVE290IntroduceConfirmList.btnLogin").show();
                if (
                    me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                    me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                ) {
                    //確認済みへボタンを表示する(Visible=True)
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").show();
                } else {
                    //確認済みへボタンを非表示にする(Visible=False)
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").hide();
                }
                if (
                    $(".HMTVE290IntroduceConfirmList.rdoNotConfirm").is(
                        ":checked"
                    )
                ) {
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").button(
                        "enable"
                    );
                    $(".HMTVE290IntroduceConfirmList.btnLogin").button(
                        "enable"
                    );

                    $(me.grid_id).jqGrid("setSelection", 0);
                } else {
                    $(".HMTVE290IntroduceConfirmList.btnConfirm").button(
                        "disable"
                    );
                    $(".HMTVE290IntroduceConfirmList.btnLogin").button(
                        "disable"
                    );
                }
            };
            var data = {
                rdoTwo: $(".HMTVE290IntroduceConfirmList.rdoTwo").is(
                    ":checked"
                ),
                rdoConfirm: $(".HMTVE290IntroduceConfirmList.rdoConfirm").is(
                    ":checked"
                ),
                rdoNotConfirm: $(
                    ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                ).is(":checked"),
                txtPosition: $(
                    ".HMTVE290IntroduceConfirmList.txtPosition"
                ).val(),
                ddlYear: $(".HMTVE290IntroduceConfirmList.ddlYear").val(),
                ddlMonth: $(".HMTVE290IntroduceConfirmList.ddlMonth").val(),
                ddlDay: $(".HMTVE290IntroduceConfirmList.ddlDay").val(),
                ddlYear2: $(".HMTVE290IntroduceConfirmList.ddlYear2").val(),
                ddlMonth2: $(".HMTVE290IntroduceConfirmList.ddlMonth2").val(),
                ddlDay2: $(".HMTVE290IntroduceConfirmList.ddlDay2").val(),
            };
            gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        } catch (ex) {
            console.log(ex);
        }
    };

    me.fncCheck = function () {
        try {
            //２．入力チェック
            $(me.grid_id).jqGrid("saveRow", me.last_selected_id);
            var rowDatas = $(me.grid_id).jqGrid("getRowData");
            for (var index = 0; index < rowDatas.length; index++) {
                if (
                    (rowDatas[index]["MANEGER_CHK"] == "2" ||
                        rowDatas[index]["TANTO_CHK"] == "2") &&
                    rowDatas[index]["FUBI_RIYU"] == ""
                ) {
                    $(me.grid_id).jqGrid("setSelection", index);
                    me.clsComFnc.ObjFocus = $("#" + index + "_FUBI_RIYU");
                    me.clsComFnc.FncMsgBox("E0012", "不備理由");
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount(
                        $.trim(rowDatas[index]["FUBI_RIYU"])
                    ) > 100
                ) {
                    $(me.grid_id).jqGrid("setSelection", index);
                    me.clsComFnc.ObjFocus = $("#" + index + "_FUBI_RIYU");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "不備理由は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録ボタンクリックのイベント
	 '関 数 名：btnLogin_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：入力データの登録
	 '**********************************************************************
	 */
    me.btnLogin_Click = function () {
        try {
            if (!me.fncCheck()) {
                return;
            }
            $(me.grid_id).jqGrid("saveRow", me.last_selected_id);

            // var rows = $(me.grid_id).jqGrid("getDataIDs");
            // for (index in rows)
            // {
            // var rowData = $(me.grid_id).jqGrid('getRowData', rows[index]);
            // //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
            // if (rowData['SYOUDAN_FLG'] == '1')
            // {
            // //背景色(#ffcc99)に変更する
            // $("#" + rows[index] + " td").css("background-color", "#ffcc99");
            // }
            // }

            var jqgridData = [];
            var rowData = $(me.grid_id).jqGrid("getRowData");
            for (var index = 0; index < rowData.length; index++) {
                var obj = {
                    JYURI_NO: rowData[index]["JYURI_NO"],
                    MANEGER_CHK: rowData[index]["MANEGER_CHK"],
                    TANTO_CHK: rowData[index]["TANTO_CHK"],
                    SYOUNIN_FLG: rowData[index]["SYOUNIN_FLG"],
                    FUBI_RIYU: rowData[index]["FUBI_RIYU"],
                };
                jqgridData.push(obj);
            }
            var url = me.sys_id + "/" + me.id + "/" + "btnLogin_Click";
            var data = {
                CONST_ADMIN_PTN_NO: me.HMTVE.CONST_ADMIN_PTN_NO,
                CONST_HONBU_PTN_NO: me.HMTVE.CONST_HONBU_PTN_NO,
                CONST_TESTER_PTN_NO: me.HMTVE.CONST_TESTER_PTN_NO,
                jqgrid: jqgridData,
                rdoTwo: $(".HMTVE290IntroduceConfirmList.rdoTwo").is(
                    ":checked"
                ),
                rdoConfirm: $(".HMTVE290IntroduceConfirmList.rdoConfirm").is(
                    ":checked"
                ),
                rdoNotConfirm: $(
                    ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                ).is(":checked"),
                txtPosition: $(
                    ".HMTVE290IntroduceConfirmList.txtPosition"
                ).val(),
                ddlYear: $(".HMTVE290IntroduceConfirmList.ddlYear").val(),
                ddlMonth: $(".HMTVE290IntroduceConfirmList.ddlMonth").val(),
                ddlDay: $(".HMTVE290IntroduceConfirmList.ddlDay").val(),
                ddlYear2: $(".HMTVE290IntroduceConfirmList.ddlYear2").val(),
                ddlMonth2: $(".HMTVE290IntroduceConfirmList.ddlMonth2").val(),
                ddlDay2: $(".HMTVE290IntroduceConfirmList.ddlDay2").val(),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    //５．画面制御
                    if (
                        me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                        me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        //画面項目NO5、画面項目NO6をクリアする
                        $(".HMTVE290IntroduceConfirmList.txtPosition").val("");
                        $(".HMTVE290IntroduceConfirmList.lblPosition").val("");
                        //フォーカスを画面項目NO5に移動する
                        $(".HMTVE290IntroduceConfirmList.txtPosition").trigger(
                            "focus"
                        );
                    } else if (
                        (me.PatternID != me.HMTVE.CONST_ADMIN_PTN_NO &&
                            me.PatternID != me.HMTVE.CONST_HONBU_PTN_NO) ||
                        me.PatternID != me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        //フォーカスを画面項目NO7.日付_FROM_年に移動する
                        $(".HMTVE290IntroduceConfirmList.ddlYear").trigger(
                            "focus"
                        );
                    }
                    //登録ボタンの共通
                    me.BtnLoginCommon();
                };

                me.clsComFnc.FncMsgBox("I0016");
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：確認済みへボタンクリックのイベント
	 '関 数 名：btnConfirm_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：入力データの登録
	 '**********************************************************************
	 */
    me.btnConfirm_Click = function () {
        try {
            $(me.grid_id).jqGrid("saveRow", me.last_selected_id);
            var rows = $(me.grid_id).jqGrid("getDataIDs");
            var rowDatas = $(me.grid_id).jqGrid("getRowData");
            if (me.fncConfirmInputChk() == false) {
                //背景色の制御
                // for (index in rows)
                // {
                // var rowData = $(me.grid_id).jqGrid('getRowData', rows[index]);
                // //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                // if (rowData['SYOUDAN_FLG'] == '1')
                // {
                // //背景色(#ffcc99)に変更する
                // $("#" + rows[index] + " td").css("background-color", "#ffcc99");
                // }
                // }
                return;
            }
            //３．入力チェックを行う
            var flag = false;
            for (index in rows) {
                if (me.BtnConfrimCheckImport(index, rowDatas) == false) {
                    // var rowData = $(me.grid_id).jqGrid('getRowData', rows[index]);
                    // //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                    // if (rowData['SYOUDAN_FLG'] == '1')
                    // {
                    // //背景色(#ffcc99)に変更する
                    // $("#" + rows[index] + " td").css("background-color", "#ffcc99");
                    // }
                    flag = true;
                    break;
                }
                //Ⅰ.紹介者ﾃｰﾌﾞﾙ_店長チェックにチェックが入っている AND 紹介者ﾃｰﾌﾞﾙ_担当者チェックにチェックが入っている AND
                //紹介者ﾃｰﾌﾞﾙ_承認で"承認"か"不可"が選択されている場合
            }
            if (flag) {
                return;
            }
            //入力チェックを行う
            if (me.CheckImport2() == false) {
                return;
            }

            $(me.grid_id).jqGrid("saveRow", me.last_selected_id);

            var rowData = $(me.grid_id).jqGrid("getRowData");

            var jqgridData = [];
            for (var index = 0; index < rowData.length; index++) {
                if (
                    rowData[index]["MANEGER_CHK"] != "" &&
                    rowData[index]["TANTO_CHK"] != "" &&
                    rowData[index]["SYOUNIN_FLG"] != ""
                ) {
                    var obj = {
                        JYURI_NO: rowData[index]["JYURI_NO"],
                    };
                    jqgridData.push(obj);
                }
            }
            var url = me.sys_id + "/" + me.id + "/" + "btnConfirm_Click";
            var data = {
                jqgrid: jqgridData,
                rdoConfirm: $(".HMTVE290IntroduceConfirmList.rdoConfirm").val(),
                rdoNotConfirm: $(
                    ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                ).is(":checked"),
                txtPosition: $(".HMTVE290IntroduceConfirmList.txtPosition").is(
                    ":checked"
                ),
                ddlYear: $(".HMTVE290IntroduceConfirmList.ddlYear").val(),
                ddlMonth: $(".HMTVE290IntroduceConfirmList.ddlMonth").val(),
                ddlDay: $(".HMTVE290IntroduceConfirmList.ddlDay").val(),
                ddlYear2: $(".HMTVE290IntroduceConfirmList.ddlYear2").val(),
                ddlMonth2: $(".HMTVE290IntroduceConfirmList.ddlMonth2").val(),
                ddlDay2: $(".HMTVE290IntroduceConfirmList.ddlDay2").val(),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    var complete_fun = function (returnFLG, result) {
                        if (result["error"]) {
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $(me.grid_id).jqGrid(
                                    "editRow",
                                    me.last_selected_id,
                                    true
                                );
                            };
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            return;
                        }
                        //５．画面制御
                        if (me.CreateIntroducer() == false) {
                            //紹介者ﾃｰﾌﾞﾙを表示する(Visible=False)
                            $(".HMTVE290IntroduceConfirmList.tblDetail").hide();
                            $(".HMTVE290IntroduceConfirmList.trInfo").hide();
                            //登録ボタンを表示する(Visible=False)
                            $(".HMTVE290IntroduceConfirmList.btnLogin").hide();
                            //確認済へボタンを非表示にする
                            $(
                                ".HMTVE290IntroduceConfirmList.btnConfirm"
                            ).hide();
                        } else {
                            me.setItemsManagementPart1();
                            //紹介者ﾃｰﾌﾞﾙを表示する(Visible=True)
                            $(".HMTVE290IntroduceConfirmList.tblDetail").show();
                            $(".HMTVE290IntroduceConfirmList.trInfo").show();
                            //登録ボタンを表示する(Visible=True)
                            $(".HMTVE290IntroduceConfirmList.btnLogin").show();
                            if (
                                me.PatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                                me.PatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                                me.PatternID == me.HMTVE.CONST_TESTER_PTN_NO
                            ) {
                                //確認済みへボタンを表示する(Visible=True)
                                $(
                                    ".HMTVE290IntroduceConfirmList.btnConfirm"
                                ).show();
                            } else {
                                //確認済みへボタンを非表示にする(Visible=False)
                                $(
                                    ".HMTVE290IntroduceConfirmList.btnConfirm"
                                ).hide();
                            }
                            $(me.grid_id).jqGrid("setSelection", 0);
                        }
                    };
                    var data = {
                        rdoTwo: $(".HMTVE290IntroduceConfirmList.rdoTwo").is(
                            ":checked"
                        ),
                        rdoConfirm: $(
                            ".HMTVE290IntroduceConfirmList.rdoConfirm"
                        ).is(":checked"),
                        rdoNotConfirm: $(
                            ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                        ).is(":checked"),
                        txtPosition: $(
                            ".HMTVE290IntroduceConfirmList.txtPosition"
                        ).val(),
                        ddlYear: $(
                            ".HMTVE290IntroduceConfirmList.ddlYear"
                        ).val(),
                        ddlMonth: $(
                            ".HMTVE290IntroduceConfirmList.ddlMonth"
                        ).val(),
                        ddlDay: $(".HMTVE290IntroduceConfirmList.ddlDay").val(),
                        ddlYear2: $(
                            ".HMTVE290IntroduceConfirmList.ddlYear2"
                        ).val(),
                        ddlMonth2: $(
                            ".HMTVE290IntroduceConfirmList.ddlMonth2"
                        ).val(),
                        ddlDay2: $(
                            ".HMTVE290IntroduceConfirmList.ddlDay2"
                        ).val(),
                    };
                    gdmz.common.jqgrid.reloadMessage(
                        me.grid_id,
                        data,
                        complete_fun
                    );
                };
                me.clsComFnc.FncMsgBox(
                    "I9999",
                    "完了データを確認済みへ移動しました。"
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：Excel出力ボタンクリック
	 '関 数 名：btnExcel_Click
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：Excel出力ボタンクリック
	 '**********************************************************************
	 */
    me.btnExcel_Click = function () {
        try {
            //１．入力チェックを行う
            if (me.CheckImport() == false) {
                return;
            }
            var url = me.sys_id + "/" + me.id + "/" + "btnExcel_Click";
            var data = {
                rdoTwo: $(".HMTVE290IntroduceConfirmList.rdoTwo").is(
                    ":checked"
                ),
                rdoConfirm: $(".HMTVE290IntroduceConfirmList.rdoConfirm").is(
                    ":checked"
                ),
                rdoNotConfirm: $(
                    ".HMTVE290IntroduceConfirmList.rdoNotConfirm"
                ).is(":checked"),
                txtPosition: $(
                    ".HMTVE290IntroduceConfirmList.txtPosition"
                ).val(),
                ddlYear: $(".HMTVE290IntroduceConfirmList.ddlYear").val(),
                ddlMonth: $(".HMTVE290IntroduceConfirmList.ddlMonth").val(),
                ddlDay: $(".HMTVE290IntroduceConfirmList.ddlDay").val(),
                ddlYear2: $(".HMTVE290IntroduceConfirmList.ddlYear2").val(),
                ddlMonth2: $(".HMTVE290IntroduceConfirmList.ddlMonth2").val(),
                ddlDay2: $(".HMTVE290IntroduceConfirmList.ddlDay2").val(),
                lblPosition: $(
                    ".HMTVE290IntroduceConfirmList.lblPosition"
                ).val(),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (
                        result["error"] == "W0024" ||
                        result["error"] == "W0015"
                    ) {
                        me.clsComFnc.FncMsgBox(result["error"]);
                    } else if (result["error"] == "W0001") {
                        me.clsComFnc.FncMsgBox("W0001", "出力先");
                    } else if (result["error"] == "W9999") {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "テンプレートファイルが存在しません。"
                        );
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return;
                }
                window.location.href = result["data"]["url"];
                //5．上記が正常終了した場合は、終了メッセージを表示する
                me.clsComFnc.FncMsgBox("I0018");
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録ボタン共通画面
	 '関 数 名：BtnLoginCommon
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：登録ボタンクリックの画面制御の共通
	 '**********************************************************************
	 */
    me.BtnLoginCommon = function () {
        try {
            //画面項目NO18.紹介者ﾃｰﾌﾞﾙをクリアする
            $(me.grid_id).jqGrid("clearGridData");
            //日付_FROM_年、日付_FROM_月、日付_FROM_日、日付_TO_年、日付_TO_月、日付_TO_日は空白を選択する
            $(".HMTVE290IntroduceConfirmList.ddlYear").val("");
            $(".HMTVE290IntroduceConfirmList.ddlMonth").val("");
            $(".HMTVE290IntroduceConfirmList.ddlDay").val("");
            $(".HMTVE290IntroduceConfirmList.ddlYear2").val("");
            $(".HMTVE290IntroduceConfirmList.ddlMonth2").val("");
            $(".HMTVE290IntroduceConfirmList.ddlDay2").val("");

            $(".HMTVE290IntroduceConfirmList.rdoConfirm").prop(
                "checked",
                false
            );
            $(".HMTVE290IntroduceConfirmList.rdoConfirm").prop(
                "checked",
                false
            );
            //対象_未確認にチェックを入れる
            $(".HMTVE290IntroduceConfirmList.rdoNotConfirm").prop(
                "checked",
                "checked"
            );
            //画面項目NO18.紹介者ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE290IntroduceConfirmList.tblDetail").hide();
            $(".HMTVE290IntroduceConfirmList.trInfo").hide();
            //画面項目NO19.登録ボタンを非表示にする(Visible=false)
            $(".HMTVE290IntroduceConfirmList.btnLogin").hide();
            //画面項目NO20.確認済へボタンを非表示にする(Visible=false)
            $(".HMTVE290IntroduceConfirmList.btnConfirm").hide();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：対象期間
	 '関 数 名：getObjectTerm
	 '引 数 　：strSql
	 '戻 り 値：なし
	 '処理説明：対象期間を取得する
	 '**********************************************************************
	 */
    me.getObjectTerm = function (result) {
        try {
            var ddlYear = result["data"]["HDTINTRODUCEDATA"];
            //日付_FROM_年にセットする
            if (ddlYear[0]) {
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlYear");
                var HI_MIN = parseInt(ddlYear[0]["HI_MIN"].substring(0, 4));
                var HI_MAX = parseInt(ddlYear[0]["HI_MAX"].substring(0, 4));
                for (var index = HI_MIN; index <= HI_MAX; index++) {
                    $("<option></option>")
                        .val(index)
                        .text(index)
                        .appendTo(".HMTVE290IntroduceConfirmList.ddlYear");
                }
            }
            //月のコンボリストを設定する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlMonth");
            for (var index = 1; index <= 12; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlMonth");
            }
            //日のコンボリストを選択する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlDay");
            for (var index = 1; index <= 31; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlDay");
            }
            //日付_TO_年にセットする
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlYear2");
            if (ddlYear[0]) {
                var HI_MIN = parseInt(ddlYear[0]["HI_MIN"].substring(0, 4));
                var HI_MAX = parseInt(ddlYear[0]["HI_MAX"].substring(0, 4));
                for (var index = HI_MIN; index <= HI_MAX; index++) {
                    $("<option></option>")
                        .val(index)
                        .text(index)
                        .appendTo(".HMTVE290IntroduceConfirmList.ddlYear2");
                }
            }
            //月のコンボリストを設定する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlMonth2");
            for (var index = 1; index <= 12; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlMonth2");
            }
            //日のコンボリストを選択する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlDay2");
            for (var index = 1; index <= 31; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlDay2");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：店舗名
	 '関 数 名：ExpressShopName
	 '引 数 　：strSql
	 '戻 り 値：なし
	 '処理説明：店舗名を表示する
	 '**********************************************************************
	 */
    me.ExpressShopName = function (result) {
        try {
            //画面項目No.6(部署名)に⑦－１．抽出データ("BUSYO_RYKNM")を表示する
            for (var index in result["data"]["ExpressShopName"]) {
                if (
                    result["data"]["HBUSYO"][index]["ExpressShopName"] ==
                    $(".HMTVE290IntroduceConfirmList.txtPosition").val()
                ) {
                    $(".HMTVE290IntroduceConfirmList.lblPosition").val(
                        result["data"]["ExpressShopName"][index]["BUSYO_RYKNM"]
                    );
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：CheckImport
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：入力チェックを行う
	 '**********************************************************************
	 */
    me.CheckImport = function () {
        try {
            //対象_確認済み　又は　対象_両方　を選択した場合
            if (
                $(".HMTVE290IntroduceConfirmList.rdoConfirm").is(":checked") ||
                $(".HMTVE290IntroduceConfirmList.rdoTwo").is(":checked")
            ) {
                //日付_FROM_年に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlYear"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_FROM_年を入力してください"
                    );
                    return false;
                }
                //日付_FROM_月項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlMonth"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_FROM_月を入力してください"
                    );
                    return false;
                }
                //日付_FROM_日項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlDay").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlDay"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_FROM_日を入力してください"
                    );
                    return false;
                }
                //日付_TO_年に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear2").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlYear2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_TO_年を入力してください"
                    );
                    return false;
                }
                //日付_TO_月項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth2").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlMonth2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_TO_月を入力してください"
                    );
                    return false;
                }
                //日付_TO_日項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlDay2").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlDay2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付_TO_日を入力してください"
                    );
                    return false;
                }
                //日付_FROM_年＝"" OR 日付_FROM_月＝"" OR 日付_FROM_日＝""の場合、エラー
                if (
                    $(".HMTVE290IntroduceConfirmList.ddlYear").val() == "" ||
                    $(".HMTVE290IntroduceConfirmList.ddlMonth").val() == "" ||
                    $(".HMTVE290IntroduceConfirmList.ddlDay").val() == ""
                ) {
                    //エラー項目にフォーカス移動
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlYear"
                        );
                    } else if (
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlMonth"
                        );
                    } else {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlDay"
                        );
                    }
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "受理日(開始)の年・月・日のいずれかが入力されていません！"
                    );
                    return false;
                }
                //日付_TO_年＝"" OR 日付_TO_月＝"" OR 日付_TO_日＝""の場合、エラー
                if (
                    $(".HMTVE290IntroduceConfirmList.ddlYear2").val() == "" ||
                    $(".HMTVE290IntroduceConfirmList.ddlMonth2").val() == "" ||
                    $(".HMTVE290IntroduceConfirmList.ddlDay2").val() == ""
                ) {
                    //エラー項目にフォーカス移動
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear2").val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlYear2"
                        );
                    } else if (
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlMonth2"
                        );
                    } else {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE290IntroduceConfirmList.ddlDay2"
                        );
                    }
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "受理日(開始)の年・月・日のいずれかが入力されていません！"
                    );
                    return false;
                }
                //日付_FROM_年 ≠"" AND 日付_TO_年≠"" の場合、エラー
                if (
                    $(".HMTVE290IntroduceConfirmList.ddlYear").val() != "" &&
                    $(".HMTVE290IntroduceConfirmList.ddlYear2").val() != "" &&
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlMonth").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlDay").val()
                    ) >
                        parseInt(
                            $(".HMTVE290IntroduceConfirmList.ddlYear2").val() +
                                $(
                                    ".HMTVE290IntroduceConfirmList.ddlMonth2"
                                ).val() +
                                $(".HMTVE290IntroduceConfirmList.ddlDay2").val()
                        )
                ) {
                    //エラー項目にフォーカス移動
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.ddlYear"
                    );
                    //メッセージ内容："受理日の大小関係が不正です！"
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "受理日の大小関係が不正です"
                    );
                    return false;
                }
            }
            if (
                $(".HMTVE290IntroduceConfirmList.rdoNotConfirm").is(":checked")
            ) {
                //提供日(開始)の年・月・日に値がセットされているのに、セットされている項目以外の年・月・日いずれかがセットされていない場合エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlDay").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(開始)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlDay").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(開始)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                if ($(".HMTVE290IntroduceConfirmList.ddlDay").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(開始)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                //提供日(終了)の年・月・日に値がセットされているのに、セットされている項目以外の年・月・日いずれかがセットされていない場合エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear2").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlDay2").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(終了)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth2").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear2").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlDay2").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(終了)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                if ($(".HMTVE290IntroduceConfirmList.ddlDay2").val() != "") {
                    if (
                        $(".HMTVE290IntroduceConfirmList.ddlYear2").val() ==
                            "" ||
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val() == ""
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "提供日(終了)の年・月・日のいずれかが入力されていません！"
                        );
                        return false;
                    }
                }
                if (
                    $(".HMTVE290IntroduceConfirmList.ddlYear").val() != "" &&
                    $(".HMTVE290IntroduceConfirmList.ddlYear2").val() != "" &&
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlMonth").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlDay").val()
                    ) >
                        parseInt(
                            $(".HMTVE290IntroduceConfirmList.ddlYear2").val() +
                                $(
                                    ".HMTVE290IntroduceConfirmList.ddlMonth2"
                                ).val() +
                                $(".HMTVE290IntroduceConfirmList.ddlDay2").val()
                        )
                ) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "提供日の大小関係が不正です"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：CheckImport2
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：入力チェックを行う
	 '**********************************************************************
	 */
    me.CheckImport2 = function () {
        try {
            //対象_確認済み　又は　対象_両方　を選択した場合
            if (
                $(".HMTVE290IntroduceConfirmList.rdoConfirm").is(":checked") ||
                $(".HMTVE290IntroduceConfirmList.rdoTwo").is(":checked")
            ) {
                //日付_FROM_年に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlYear").trigger("focus");
                    return false;
                }
                //日付_FROM_月項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlMonth").trigger(
                        "focus"
                    );
                    return false;
                }
                //日付_FROM_日項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlDay").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlDay").trigger("focus");
                    return false;
                }
                //日付_TO_年に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlYear2").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlYear2").trigger(
                        "focus"
                    );
                    return false;
                }
                //日付_TO_月項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlMonth2").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlMonth2").trigger(
                        "focus"
                    );
                    return false;
                }
                //日付_TO_日項目に未入力データがある場合、エラー
                if ($(".HMTVE290IntroduceConfirmList.ddlDay2").val() == "") {
                    $(".HMTVE290IntroduceConfirmList.ddlDay2").trigger("focus");
                    return false;
                }
                //日付_FROM_年 ≠"" AND 日付_TO_年≠"" の場合、エラー
                if (
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlYear").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlMonth").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlDay").val()
                    ) >
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlYear2").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlMonth2").val() +
                            $(".HMTVE290IntroduceConfirmList.ddlDay2").val()
                    )
                ) {
                    //エラー項目にフォーカス移動
                    $(".HMTVE290IntroduceConfirmList.ddlYear").trigger("focus");
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者ﾃｰﾌﾞﾙ
	 '関 数 名：CreateIntroducer
	 '引 数 　：strSql
	 '戻 り 値：なし
	 '処理説明：紹介者ﾃｰﾌﾞﾙの生成処理
	 '**********************************************************************
	 */
    me.CreateIntroducer = function () {
        try {
            var rowData = $(me.grid_id).jqGrid("getRowData");
            if (rowData.length == 0) {
                //エラーメッセージを表示し、処理を中止する
                me.clsComFnc.FncMsgBox("W0024");
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面
	 '関 数 名：ClearScreen
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：画面をクリア
	 '**********************************************************************
	 */
    me.ClearScreen = function () {
        try {
            //部署コード
            $(".HMTVE290IntroduceConfirmList.txtPosition").val("");
            //部署名
            $(".HMTVE290IntroduceConfirmList.lblPosition").val("");
            //日付_FROM_年
            $(".HMTVE290IntroduceConfirmList.ddlYear").val("");
            //日付_FROM_月
            $(".HMTVE290IntroduceConfirmList.ddlMonth").val("");
            //日付_FROM_日
            $(".HMTVE290IntroduceConfirmList.ddlDay").val("");
            //日付_TO_年
            $(".HMTVE290IntroduceConfirmList.ddlYear2").val("");
            //日付_TO_月
            $(".HMTVE290IntroduceConfirmList.ddlMonth2").val("");
            //日付_TO_日
            $(".HMTVE290IntroduceConfirmList.ddlDay2").val("");
            //対象_未確認をデフォルトで選択
            $(".HMTVE290IntroduceConfirmList.rdoNotConfirm").prop(
                "checked",
                "checked"
            );
            //紹介者ﾃｰﾌﾞﾙ
            $(me.grid_id).jqGrid("clearGridData");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：BtnConfrimCheckImport関数から移動
	 '関 数 名：fncConfirmInputChk
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：BtnConfrimCheckImport関数から移動
	 '**********************************************************************
	 */
    me.fncConfirmInputChk = function () {
        try {
            var rowDatas = $(me.grid_id).jqGrid("getRowData");
            for (var index = 0; index < rowDatas.length; index++) {
                //1.紹介者ﾃｰﾌﾞﾙ_店長チェック(チェックが入っている場合は１、入っていない場合はNULL)(RowCnt)≠紹介者ﾃｰﾌﾞﾙ_店長チェック_チェック用(RowCnt)の場合
                if (
                    rowDatas[index]["MANEGER_CHK"] !=
                    rowDatas[index]["MANEGER_CHK_CHK"]
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.btnLogin"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "データが変更されています。更新処理を行ってから確定処理を行ってください！"
                    );
                    return false;
                }
                //2.紹介者ﾃｰﾌﾞﾙ_担当者チェック(チェックが入っている場合は１、入っていない場合はNULL)(RowCnt)≠紹介者ﾃｰﾌﾞﾙ_担当者チェック_チェック用(RowCnt)の場合
                if (
                    rowDatas[index]["TANTO_CHK"] !=
                    rowDatas[index]["TANTO_CHK_CHK"]
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.btnLogin"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "データが変更されています。更新処理を行ってから確定処理を行ってください！"
                    );
                    return false;
                }
                //3.紹介者ﾃｰﾌﾞﾙ_承認.value≠紹介者ﾃｰﾌﾞﾙ_承認_チェック用(NULLの場合は0)の場合
                if (rowDatas[index]["SYOUNIN_FLG"] == "") {
                    $(me.grid_id).jqGrid("setCell", index, 13, 0);
                }
                if (
                    rowDatas[index]["SYOUNIN_FLG"] !=
                    rowDatas[index]["SYOUNIN_FLG_CHK"]
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.btnLogin"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "データが変更されています。更新処理を行ってから確定処理を行ってください！"
                    );
                    return false;
                }
                //4.紹介者ﾃｰﾌﾞﾙ_不備理由≠紹介者ﾃｰﾌﾞﾙ_不備理由_チェック用の場合
                if (
                    rowDatas[index]["FUBI_RIYU"] !=
                    rowDatas[index]["FUBI_RIYU_CHK"]
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid(
                            "editRow",
                            me.last_selected_id,
                            true
                        );
                    };
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.btnLogin"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "データが変更されています。更新処理を行ってから確定処理を行ってください！"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：確認済みへボタンの入力チェック
	 '関 数 名：BtnConfrimCheckImport
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：確認済みへボタンの入力チェックを行う
	 '**********************************************************************
	 */
    me.BtnConfrimCheckImport = function (RowCnt, rowDatas) {
        try {
            //5.紹介者ﾃｰﾌﾞﾙ_店長チェックにチェックが入っている AND 紹介者ﾃｰﾌﾞﾙ_担当者チェックにチェックが入っている AND 紹介者ﾃｰﾌﾞﾙ_承認で"承認"か"不可"
            if (
                rowDatas[RowCnt]["MANEGER_CHK"] != "" &&
                rowDatas[RowCnt]["TANTO_CHK"] != "" &&
                rowDatas[RowCnt]["SYOUNIN_FLG"] != "" &&
                rowDatas[RowCnt]["SYAIN_NM"] == ""
            ) {
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォーカス
	 '関 数 名：FoucsMove
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：フォーカス移動時
	 '**********************************************************************
	 */
    me.FoucsMove = function () {
        try {
            //Ⅰー１．入力チェックを行う
            //画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
            $(".HMTVE290IntroduceConfirmList.lblPosition").val("");
            if ($(".HMTVE290IntroduceConfirmList.txtPosition").val() != "") {
                var Regex = /[^a-zA-Z0-9\-]/;
                if (
                    Regex.test(
                        $.trim(
                            $(".HMTVE290IntroduceConfirmList.txtPosition").val()
                        )
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE290IntroduceConfirmList.txtPosition"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "部署");
                    return;
                }

                $(".HMTVE290IntroduceConfirmList.lblPosition").val("");
                for (var index in me.allBusyo) {
                    if (
                        me.allBusyo[index]["BUSYO_CD"] ==
                        $.trim(
                            $(".HMTVE290IntroduceConfirmList.txtPosition").val()
                        )
                    ) {
                        $(".HMTVE290IntroduceConfirmList.lblPosition").val(
                            me.allBusyo[index]["BUSYO_RYKNM"]
                        );
                        break;
                    }
                }

                me.Clear_Again();
                $(".HMTVE290IntroduceConfirmList.ddlYear").trigger("focus");
            } else {
                me.Clear_Again();
                $(".HMTVE290IntroduceConfirmList.ddlYear").trigger("focus");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年月日
	 '関 数 名：DLselectchange
	 '引 数 1 ：strsender
	 '引 数 2 ：e
	 '戻 り 値：なし
	 '処理説明：年月日を処理
	 '**********************************************************************
	 */
    me.DLselectchange = function () {
        try {
            var zdr = 0,
                j = "";
            var strDay = $.trim(
                $(".HMTVE290IntroduceConfirmList.ddlDay").val()
            );
            if (
                $(".HMTVE290IntroduceConfirmList.ddlYear").val() % 400 == 0 ||
                ($(".HMTVE290IntroduceConfirmList.ddlYear").val() % 4 == 0 &&
                    $(".HMTVE290IntroduceConfirmList.ddlYear").val() % 100 != 0)
            ) {
                var temp =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                          ) == 2
                            ? 29
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                var temp =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                          ) == 2
                            ? 28
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE290IntroduceConfirmList.ddlDay").children().remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlDay");
            for (var i = 1; i <= zdr; i++) {
                if (i < 10) {
                    j = "0" + i;
                } else {
                    j = "" + i;
                }
                $("<option></option>")
                    .val(j)
                    .text(j)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlDay");
            }
            if (strDay == "") {
                $(".HMTVE290IntroduceConfirmList.ddlDay").get(
                    0
                ).selectedIndex = 0;
            } else if (parseInt(strDay) > parseInt(zdr)) {
                $(".HMTVE290IntroduceConfirmList.ddlDay").get(
                    0
                ).selectedIndex = 1;
            } else {
                $(".HMTVE290IntroduceConfirmList.ddlDay").val(strDay);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年月日
	 '関 数 名：DLselectchange2
	 '引 数 1 ：strsender
	 '引 数 2 ：e
	 '戻 り 値：なし
	 '処理説明：年月日を処理
	 '**********************************************************************
	 */
    me.DLselectchange2 = function () {
        try {
            var zdr = 0,
                j = "";
            var strDay = $.trim(
                $(".HMTVE290IntroduceConfirmList.ddlDay2").val()
            );
            if (
                $(".HMTVE290IntroduceConfirmList.ddlYear2").val() % 400 == 0 ||
                ($(".HMTVE290IntroduceConfirmList.ddlYear2").val() % 4 == 0 &&
                    $(".HMTVE290IntroduceConfirmList.ddlYear2").val() % 100 !=
                        0)
            ) {
                var temp =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                          ) == 2
                            ? 29
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                var temp =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                          ) == 2
                            ? 28
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE290IntroduceConfirmList.ddlMonth2").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE290IntroduceConfirmList.ddlDay2").children().remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE290IntroduceConfirmList.ddlDay2");
            for (var i = 1; i <= zdr; i++) {
                if (i < 10) {
                    j = "0" + i;
                } else {
                    j = "" + i;
                }
                $("<option></option>")
                    .val(j)
                    .text(j)
                    .appendTo(".HMTVE290IntroduceConfirmList.ddlDay2");
            }
            if (strDay == "") {
                $(".HMTVE290IntroduceConfirmList.ddlDay2").get(
                    0
                ).selectedIndex = 0;
            } else if (parseInt(strDay) > parseInt(zdr)) {
                $(".HMTVE290IntroduceConfirmList.ddlDay2").get(
                    0
                ).selectedIndex = 1;
            } else {
                $(".HMTVE290IntroduceConfirmList.ddlDay2").val(strDay);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：項目の制御パート１
	 '関 数 名：setItemsManagementPart1
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：項目の制御パート１を処理
	 '**********************************************************************
	 */
    me.setItemsManagementPart1 = function () {
        try {
            var ids = $(me.grid_id).jqGrid("getDataIDs");
            for (var index = 0; index < ids.length; index++) {
                var rowData = $(me.grid_id).jqGrid("getRowData", ids[index]);
                //紹介者ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞ＝"1"の場合
                if (rowData["SYOUDAN_FLG"] == "1") {
                    //背景色(#ffcc99)に変更する
                    $(me.grid_id + " " + "#" + ids[index])
                        .find("td")
                        .css("background-color", "#ffcc99");
                }
                if (rowData["MANEGER_CHK"] == "1") {
                    $(me.grid_id).setCell(
                        ids[index],
                        "MANEGER_CHK",
                        rowData["MANEGER_CHK"],
                        {
                            background: "#6bb4ea",
                        }
                    );
                } else if (rowData["MANEGER_CHK"] == "2") {
                    $(me.grid_id).setCell(
                        ids[index],
                        "MANEGER_CHK",
                        rowData["MANEGER_CHK"],
                        {
                            background: "#FF0000",
                        }
                    );
                }
                if (rowData["TANTO_CHK"] == "1") {
                    $(me.grid_id).setCell(
                        ids[index],
                        "TANTO_CHK",
                        rowData["TANTO_CHK"],
                        {
                            background: "#6bb4ea",
                        }
                    );
                } else if (rowData["TANTO_CHK"] == "2") {
                    $(me.grid_id).setCell(
                        ids[index],
                        "TANTO_CHK",
                        rowData["TANTO_CHK"],
                        {
                            background: "#FF0000",
                        }
                    );
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関数 名：Clear_Again
	 '処理説明：
	 '**********************************************************************
	 */
    me.Clear_Again = function () {
        try {
            $(me.grid_id).jqGrid("clearGridData");
            $(".HMTVE290IntroduceConfirmList.tblDetail").hide();
            $(".HMTVE290IntroduceConfirmList.btnLogin").hide();
            $(".HMTVE290IntroduceConfirmList.btnConfirm").hide();
            $(".HMTVE290IntroduceConfirmList.trInfo").hide();
        } catch (ex) {
            console.log(ex);
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE290IntroduceConfirmList =
        new HMTVE.HMTVE290IntroduceConfirmList();

    o_HMTVE_HMTVE290IntroduceConfirmList.load();
});
