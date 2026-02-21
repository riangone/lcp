/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															   担当
 * YYYYMMDD						#ID							　　　　XXXXXX															  GSDL
 * 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("HMTVE.HMTVE410SyucchoTenjikaiEntry");

HMTVE.HMTVE410SyucchoTenjikaiEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.hmtve = new HMTVE.HMTVE();

    // ========== 変数 start ==========

    me.id = "HMTVE410SyucchoTenjikaiEntry";
    me.sys_id = "HMTVE";
    me.grid_id = "#HMTVE410SyucchoTenjikaiEntry_sprList";
    me.HidNull = "";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnETSearch_Click";
    // 選択されていた年
    me.HidYBefore = "";
    me.HidYAfter = "";
    // 部署
    me.busyoName = "";
    // 開催日
    me.sysdataTo = "";
    // 対象期間を取得する
    me.urlDate = me.sys_id + "/" + me.id + "/" + "getTermDate";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
    };

    me.colModel = [
        {
            name: "LIST_MEISAI_NO",
            label: "NO",
            index: "LIST_MEISAI_NO",
            sortable: false,
            align: "left",
            width: 28,
        },
        {
            name: "KAISAI_YMD",
            label: "開催日",
            index: "KAISAI_YMD",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 63 : 77,
        },
        {
            name: "START_TIME",
            label: "開始<br />時間",
            index: "START_TIME",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 33 : 40,
        },
        {
            name: "END_TIME",
            label: "終了<br />時間",
            index: "END_TIME",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 33 : 40,
        },
        {
            name: "PLACE",
            label: "開催場所",
            index: "PLACE",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 130 : 141,
        },
        {
            name: "DEMO_CARS",
            label: "使用デモカー",
            index: "DEMO_CARS",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 130 : 141,
        },
        {
            name: "TENPO_CD",
            label: "",
            index: "TENPO_CD",
            sortable: false,
            align: "left",
            width: 28,
        },
        {
            name: "BUSYO_RYKNM",
            label: "店舗",
            index: "BUSYO_RYKNM",
            sortable: false,
            align: "left",
            width: 110,
        },
        {
            name: "SYAIN_NO",
            label: "",
            index: "SYAIN_NO",
            sortable: false,
            align: "left",
            width: 41,
        },
        {
            name: "SYAIN_NM",
            label: "社員",
            index: "SYAIN_NM",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 80 : 102,
        },
        {
            name: "RAIJYO_SU",
            label: "来場",
            index: "RAIJYO_SU",
            sortable: false,
            align: "right",
            width: 34,
        },
        {
            name: "ENQUETE_SU",
            label: "ｱﾝ<br />ｹｰﾄ",
            index: "ENQUETE_SU",
            sortable: false,
            align: "right",
            width: 34,
        },
        {
            name: "ABHOT_SU",
            label: "AB",
            index: "ABHOT_SU",
            sortable: false,
            align: "right",
            width: 34,
        },
        {
            name: "MITUMORI_SU",
            label: "見積",
            index: "MITUMORI_SU",
            sortable: false,
            align: "right",
            width: 34,
        },
        {
            name: "SEIYAKU_SU",
            label: "成約",
            index: "SEIYAKU_SU",
            sortable: false,
            align: "right",
            width: 34,
        },
        {
            label: "",
            name: "",
            index: "EDIT",
            width: me.ratio === 1.5 ? 60 : 80,
            align: "center",
            formatter: function (_cellvalue, options) {
                var detail =
                    '<button onclick="btnEdit_Click(' +
                    options.rowId +
                    ")\" id = 'btnEdit' class=\"HMTVE410SyucchoTenjikaiEntry btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>編集</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE410SyucchoTenjikaiEntry.Button",
        type: "button",
        handle: "",
    });

    //開催日
    me.controls.push({
        id: ".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // クリアボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnTopClear").click(function () {
        me.btnTopClear_Click();
    });

    //キャンセルボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnCancel").click(function () {
        me.clearPage();
    });

    //表示ボタンボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });

    //Excelボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnExcel").click(function () {
        me.btnExcel_Click();
    });

    //登録ボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnLand").click(function () {
        //入力チェック
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLand_Click;
        me.clsComFnc.FncMsgBox("QY999", "データを登録します。よろしいですか？");
    });

    //削除ボタン
    $(".HMTVE410SyucchoTenjikaiEntry.btnDelete").click(function () {
        //チェック
        if (!me.checkText("txtAcceptNo", "lblAcceptNo")) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "No." +
                $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val() +
                "のデータを削除します。よろしいですか？"
        );
    });

    //部署 change
    $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").change(function () {
        me.FoucsMove("txtExhibitTitle1");
    });
    $(".HMTVE410SyucchoTenjikaiEntry.txtPost").change(function () {
        me.FoucsMove("txtPost");
    });

    //change
    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").change(function () {
        me.DLselectchange();
    });
    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").change(function () {
        me.DLselectchange();
    });
    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").change(function () {
        me.DLselectchange2();
    });
    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").change(function () {
        me.DLselectchange2();
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
        me.Page_Load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページロード
    //'**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMTVE410SyucchoTenjikaiEntry fieldset").width()
        );
        //20240806 caina upd s
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 210);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 140 : 192
        );
        //20240806 caina upd e
        //jqgrid title設定
        me.setJqgrid();
        me.fncSetInit(true);
    };

    //'**********************************************************************
    //'処 理 名：jqgrid title設定
    //'関 数 名：setJqgrid
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：jqgrid title設定
    //'**********************************************************************
    me.setJqgrid = function () {
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "TENPO_CD",
                    numberOfColumns: 2,
                    titleText: "店舗",
                },
                {
                    startColumnName: "SYAIN_NO",
                    numberOfColumns: 2,
                    titleText: "社員",
                },
            ],
        });
        //タイトルを削除
        $(me.grid_id + "_BUSYO_RYKNM").remove();
        $(me.grid_id + "_TENPO_CD").remove();
        $(me.grid_id + "_SYAIN_NO").remove();
        $(me.grid_id + "_SYAIN_NM").remove();
        $(".ui-jqgrid-labels.jqg-third-row-header").remove();
        $(me.grid_id).jqGrid("bindKeys");
    };

    //'**********************************************************************
    //'処 理 名：対象期間初期化
    //'関 数 名：fncDateInit
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：対象期間初期化
    //'**********************************************************************
    me.fncDateInit = function () {
        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear");
        $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlMonth");
        $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay");
        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear2");
        $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2");
        $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay2");
    };

    //'**********************************************************************
    //'処 理 名：ページ初期化
    //'関 数 名：fncSetInit
    //'引    数：first_load
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.fncSetInit = function (first_load) {
        //画面項目をクリアする
        me.clearPage("all");
        if (
            gdmz.SessionUserId.toString() != null &&
            gdmz.SessionUserId.toString() != ""
        ) {
            var url = me.sys_id + "/" + me.id + "/" + "pageLoad";
            var data = {
                txtExhibitTitle1: $.trim(
                    $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val()
                ),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");

                if (result["result"] == false) {
                    me.buttonDisEna("disable");
                    if (result["data"] && result["data"]["msg"]) {
                        me.clsComFnc.FncMsgBox(
                            result["data"]["msg"],
                            result["error"]
                        );
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                } else {
                    me.buttonDisEna("enable");
                    //システム日付を取得する
                    me.sysdataTo = new Date(
                        result["data"]["strStartDate"]
                    ).Format("yyyy/MM/dd");
                    var sysdataFrom = me.sysdataTo.substring(0, 8) + "01";

                    if (
                        result["data"]["getTermDate"].length == 0 ||
                        (result["data"]["getTermDate"].length > 0 &&
                            result["data"]["getTermDate"][0]["HI_MIN"] ==
                                null &&
                            result["data"]["getTermDate"][0]["HI_MAX"] == null)
                    ) {
                        me.fncDateInit();
                        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val(
                            me.sysdataTo
                        );
                        if (
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.ddlDirector option"
                            ).length == 0
                        ) {
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE410SyucchoTenjikaiEntry.ddlDirector"
                                );
                        }
                        me.HidNull = "DataNull";
                    } else {
                        //コンボリストに日付を設定する
                        //年のコンボリストを設定する
                        me.setYear(
                            "ddlYear",
                            sysdataFrom,
                            result["data"]["getTermDate"]
                        );
                        me.setYear(
                            "ddlYear2",
                            me.sysdataTo,
                            result["data"]["getTermDate"]
                        );
                        //月のコンボリストを設定する
                        me.setMonth("ddlMonth", sysdataFrom);
                        me.setMonth("ddlMonth2", me.sysdataTo);
                        //日のコンボリストを選択する
                        me.setDay("ddlDay", "ddlYear", "ddlMonth", sysdataFrom);
                        me.setDay(
                            "ddlDay2",
                            "ddlYear2",
                            "ddlMonth2",
                            me.sysdataTo
                        );

                        //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
                        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val(
                            me.sysdataTo
                        );
                        if (
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.ddlDirector option"
                            ).length == 0
                        ) {
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE410SyucchoTenjikaiEntry.ddlDirector"
                                );
                        }
                    }

                    //部署コード:店舗名を表示する
                    me.busyoName = result["data"]["FncBusyoMstValue"];

                    //ログイン情報のチェック
                    if (result["data"]["GetBusyoMstValue"].length > 0) {
                        if (
                            $.trim(
                                result["data"]["GetBusyoMstValue"][0][
                                    "HDT_TENPO_CD"
                                ]
                            ) != "" &&
                            gdmz.SessionUserId.toString() != "69421"
                        ) {
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1"
                            ).val(result["data"]["SessionBusyoCD"]);
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1"
                            ).attr("disabled", "disabled");
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1"
                            ).val(
                                result["data"]["GetBusyoMstValue"][0][
                                    "BUSYO_RYKNM"
                                ]
                            );
                            if (first_load) {
                                $(".HMTVE410SyucchoTenjikaiEntry.txtPost").val(
                                    result["data"]["SessionBusyoCD"]
                                );
                                $(".HMTVE410SyucchoTenjikaiEntry.txtPost").attr(
                                    "disabled",
                                    "disabled"
                                );
                                $(
                                    ".HMTVE410SyucchoTenjikaiEntry.lblPost1"
                                ).text(
                                    result["data"]["GetBusyoMstValue"][0][
                                        "BUSYO_RYKNM"
                                    ]
                                );
                                //担当者コンボボックスの編集
                                me.SetTantouList(
                                    gdmz.SessionUserId.toString()
                                );
                            }
                        } else {
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1"
                            ).val("");
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1"
                            ).removeAttr("disabled");
                            $(
                                ".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1"
                            ).val("");
                            if (first_load) {
                                $(".HMTVE410SyucchoTenjikaiEntry.txtPost").val(
                                    ""
                                );
                                $(
                                    ".HMTVE410SyucchoTenjikaiEntry.txtPost"
                                ).removeAttr("disabled");
                                $(
                                    ".HMTVE410SyucchoTenjikaiEntry.lblPost1"
                                ).text("");
                                //担当者コンボボックスの編集
                                me.SetTantouList("");
                            }
                        }
                    }
                }
            };
            me.ajax.send(url, data, 0);

            //フォーカス移動
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").trigger("focus");
        }
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンのイベント
    //'関 数 名：btnETSearch_Click
    //'引    数：scrope
    //'戻 り 値：無し
    //'処理説明：検索実行
    //'**********************************************************************
    me.btnETSearch_Click = function () {
        //画面クリア処理
        $(".HMTVE410SyucchoTenjikaiEntry.tblDetail").hide();

        //入力チェックを行う
        //FROM＿年月日
        if (me.checkYMD("ddlYear", "ddlMonth", "ddlDay") == false) {
            return;
        }
        //To＿年月日
        if (me.checkYMD("ddlYear2", "ddlMonth2", "ddlDay2") == false) {
            return;
        }

        if (
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() != "" &&
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() != ""
        ) {
            var f =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() +
                "/" +
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() +
                "/" +
                $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val();
            var t =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() +
                "/" +
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() +
                "/" +
                $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val();
            if (f > t) {
                if (me.HidReshow != "RESHOWSEARCH") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE410SyucchoTenjikaiEntry.ddlYear"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "開催日の大小関係が不正です！"
                    );
                    return;
                }
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").trigger("focus");
                return;
            }
        }
        //データを表示する
        me.showData();
    };

    //'**********************************************************************
    //'処 理 名：Excelファイル
    //'関 数 名：btnExcel_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：Exceファイル生成処理
    //'**********************************************************************
    me.btnExcel_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnExcel_Click";
        var txtExhibitTitle1 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val()
        );
        var ddlYear = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val());
        var ddlMonth = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val()
        );
        var ddlDay = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val());
        var ddlYear2 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val()
        );
        var ddlMonth2 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val()
        );
        var ddlDay2 = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val());

        var data = {
            txtExhibitTitle1: txtExhibitTitle1,
            ddlYear: ddlYear,
            ddlMonth: ddlMonth,
            ddlDay: ddlDay,
            ddlYear2: ddlYear2,
            ddlMonth2: ddlMonth2,
            ddlDay2: ddlDay2,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"]) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "W9999" && result["msg"]) {
                    me.clsComFnc.FncMsgBox(result["error"], result["msg"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.buttonDisEna = function (buttonDisEna) {
        if (buttonDisEna == "disable") {
            $(".HMTVE410SyucchoTenjikaiEntry.btnETSearch").button("disable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnExcel").button("disable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnTopClear").button("disable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnDelete").button("disable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnLand").button("disable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnCancel").button("disable");
        } else {
            $(".HMTVE410SyucchoTenjikaiEntry.btnETSearch").button("enable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnExcel").button("enable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnTopClear").button("enable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnDelete").button("enable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnLand").button("enable");
            $(".HMTVE410SyucchoTenjikaiEntry.btnCancel").button("enable");
        }
    };

    //'**********************************************************************
    //'処 理 名：クリアボタンのイベント
    //'関 数 名：btnTopClear_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：クリアボタンクリック
    //'**********************************************************************
    me.btnTopClear_Click = function () {
        //ﾃｰﾌﾞﾙを非表示にする
        $(me.grid_id).jqGrid("clearGridData");

        //画面初期化
        me.fncSetInit(false);
    };

    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnLand_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：登録ボタン押下時の処理
    //'**********************************************************************
    me.btnLand_Click = function () {
        if (
            $.trim($(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val()) == ""
        ) {
            //新規
            var url = me.sys_id + "/" + me.id + "/" + "getMaxNo";

            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                } else {
                    if (
                        result["data"].length > 0 &&
                        result["data"][0]["MAXNO"] != null
                    ) {
                        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val(
                            result["data"][0]["MAXNO"]
                        );
                    } else {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "採番処理に失敗しました"
                        );
                        return;
                    }
                    //入力チェック
                    if (me.checkInputs() == false) {
                        return;
                    }
                    me.btnLand_func();
                }
            };

            me.ajax.send(url, "", 0);
        } else {
            //入力チェック
            if (me.checkInputs() == false) {
                return;
            }
            //修正
            me.btnLand_func();
        }
    };
    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnLand_func
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：追加処理/更新処理
    //'**********************************************************************
    me.btnLand_func = function () {
        //コンボリスト
        me.HidYBefore = $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val();
        me.HidYAfter = $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val();

        var txtExhibitTitle1 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val()
        );
        var txtAcceptNo = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val()
        );
        var txtAcceptDate = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val()
        );
        var txtPost = $.trim($(".HMTVE410SyucchoTenjikaiEntry.txtPost").val());
        var ddlDirector = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector").val()
        );
        var txtPlace = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtPlace").val()
        );
        var txtStartTime = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val()
        );
        var txtEndTime = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val()
        );
        var txtDemoCars = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtDemoCars").val()
        );
        var txtRaijoSu = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtRaijoSu").val()
        );
        var txtEnqueteSu = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtEnqueteSu").val()
        );
        var txtABHotSu = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtABHotSu").val()
        );
        var txtMitumoriSu = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtMitumoriSu").val()
        );
        var txtSeiyakuSu = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtSeiyakuSu").val()
        );

        //更新対象のﾃﾞｰﾀの取得
        var url = me.sys_id + "/" + me.id + "/" + "btnLand_Click";
        var data = {
            txtExhibitTitle1: txtExhibitTitle1,
            txtAcceptNo: txtAcceptNo,
            txtAcceptDate: txtAcceptDate,
            txtPost: txtPost,
            ddlDirector: ddlDirector,
            txtPlace: txtPlace,
            txtStartTime: txtStartTime,
            txtEndTime: txtEndTime,
            txtDemoCars: txtDemoCars,
            txtRaijoSu: txtRaijoSu ? parseFloat(txtRaijoSu) : "",
            txtEnqueteSu: txtEnqueteSu ? parseFloat(txtEnqueteSu) : "",
            txtABHotSu: txtABHotSu ? parseFloat(txtABHotSu) : "",
            txtMitumoriSu: txtMitumoriSu ? parseFloat(txtMitumoriSu) : "",
            txtSeiyakuSu: txtSeiyakuSu ? parseFloat(txtSeiyakuSu) : "",
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"]) {
                //再表示
                me.reShow("Land", result["data"]);
            } else {
                if (result["error"] == "W9999" && result["msg"]) {
                    me.clsComFnc.FncMsgBox(result["error"], result["msg"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：画面再表示
    //'関 数 名：reShow
    //'引    数：flag
    //'戻 り 値：無し
    //'処理説明：画面再表示
    //'**********************************************************************
    me.reShow = function (flag, strDate) {
        if (
            $(".HMTVE410SyucchoTenjikaiEntry.tblDetail").is(":hidden") == false
        ) {
            //画面制御
            me.clearPage();
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val(me.sysdataTo);
            //対象期間を取得する
            if (
                strDate["getTermDate"].length == 0 ||
                (strDate["getTermDate"].length > 0 &&
                    strDate["getTermDate"][0]["HI_MIN"] == null &&
                    strDate["getTermDate"][0]["HI_MAX"] == null)
            ) {
                me.fncDateInit();
                me.HidNull = "DataNull";
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                //年のコンボリストを設定しなおす
                me.setYear(
                    "ddlYear",
                    me.sysdataTo,
                    strDate["getTermDate"],
                    "RESHOW"
                );
                me.setYear(
                    "ddlYear2",
                    me.sysdataTo,
                    strDate["getTermDate"],
                    "RESHOW"
                );
                //登録ボタン押下時に選択されていた年を選択する
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val(me.HidYBefore);
                if ($(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() == null) {
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val(
                        strDate["getTermDate"][0]["HI_MIN"].substring(0, 4)
                    );
                }
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val(me.HidYAfter);
                if ($(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() == null) {
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val(
                        strDate["getTermDate"][0]["HI_MAX"].substring(0, 4)
                    );
                }

                if (me.HidNull == "DataNull") {
                    me.setMonth("ddlMonth", me.sysdataTo, "RESHOW");
                    me.setMonth("ddlMonth2", me.sysdataTo, "RESHOW");
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val("");
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val("");

                    me.HidNull = "-1";
                }
                //再表示処理を行う
                //日付_FROM_年≠""　AND 日付_FROM_月≠"" AND 日付_FROM_日≠"" AND
                //日付_TO_年 ≠ "" AND 日付_TO_月 ≠ "" AND 日付_TO_日≠""の場合(全て””の場合再表示されます。)
                me.HidReshow = "RESHOWSEARCH";
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    me.btnETSearch_Click();
                };
                if (flag != "Land") {
                    //削除が完了しました。
                    me.clsComFnc.FncMsgBox("I0017");
                } else {
                    //登録が完了しました。
                    me.clsComFnc.FncMsgBox("I0016");
                }
            }
        } else {
            //画面制御
            me.clearPage();
            //現在の時刻
            var sysdata = strDate["startDate"];
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val(me.sysdataTo);
            //対象期間を取得する
            if (
                strDate["getTermDate"].length == 0 ||
                (strDate["getTermDate"].length > 0 &&
                    strDate["getTermDate"][0]["HI_MIN"] == null &&
                    strDate["getTermDate"][0]["HI_MAX"] == null)
            ) {
                me.fncDateInit();
                me.HidNull = "DataNull";
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                $(".HMTVE410SyucchoTenjikaiEntry.tblDetail").hide();
            } else {
                //年のコンボリストを設定しなおす
                me.setYear(
                    "ddlYear",
                    me.sysdataTo,
                    strDate["getTermDate"],
                    "RESHOW"
                );
                me.setYear(
                    "ddlYear2",
                    me.sysdataTo,
                    strDate["getTermDate"],
                    "RESHOW"
                );
                if (me.HidNull == "DataNull") {
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val(
                        me.sysdataTo.substring(0, 4)
                    );
                    if (
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() == ""
                    ) {
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear")
                            .find("option")
                            .remove();
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear");
                        $("<option></option>")
                            .val(sysdata.substring(0, 4))
                            .text(me.sysdataTo.substring(0, 4))
                            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear");
                        ".HMTVE410SyucchoTenjikaiEntry.ddlYear".val(
                            me.sysdataTo.substring(0, 4)
                        );
                    }
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val(
                        me.sysdataTo.substring(0, 4)
                    );
                    if (
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() == ""
                    ) {
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2")
                            .find("option")
                            .remove();
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear2");
                        $("<option></option>")
                            .val(me.sysdataTo.substring(0, 4))
                            .text(me.sysdataTo.substring(0, 4))
                            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlYear2");
                        ".HMTVE410SyucchoTenjikaiEntry.ddlYear2".val(
                            me.sysdataTo.substring(0, 4)
                        );
                    }
                    me.setMonth("ddlMonth", me.sysdataTo, "RESHOW");
                    me.setMonth("ddlMonth2", me.sysdataTo, "RESHOW");
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val(
                        me.sysdataTo.substring(5, 7)
                    );
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val(
                        me.sysdataTo.substring(5, 7)
                    );
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlDay")
                        .find("option")
                        .remove();
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2")
                        .find("option")
                        .remove();
                    me.addDays("ddlDay", 31);
                    me.addDays("ddlDay2", 31);
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val(
                        me.sysdataTo.substring(8, 10)
                    );
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val(
                        me.sysdataTo.substring(8, 10)
                    );
                    me.HidNull = "-1";
                } else {
                    //登録ボタン押下時に選択されていた年を選択する
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val(
                        me.HidYBefore
                    );
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val(
                        me.HidYAfter
                    );
                }
                //完了メッセージの表示
                if (flag != "Land") {
                    //削除が完了しました。
                    me.clsComFnc.FncMsgBox("I0017");
                } else {
                    //登録が完了しました。
                    me.clsComFnc.FncMsgBox("I0016");
                }
            }
            $(".HMTVE410SyucchoTenjikaiEntry.tblDetail").hide();
        }
    };

    //'**********************************************************************
    //'処 理 名：削除ボタンのイベント
    //'関 数 名：btnDelete_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：情報削除
    //'**********************************************************************
    me.btnDelete_Click = function () {
        //コンボリスト
        me.HidYBefore = $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val();
        me.HidYAfter = $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val();
        var url = me.sys_id + "/" + me.id + "/" + "btnDelete_Click";

        var data = {
            txtAcceptNo: $.trim(
                $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val()
            ),
            txtExhibitTitle1: $.trim(
                $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val()
            ),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["data"]["msg"] != "") {
                    me.clsComFnc.FncMsgBox(result["data"]["msg"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            } else {
                me.reShow("Delete", result["data"]);
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：編集ボタンのイベント
    //'関 数 名：btnEdit_Click
    //'引    数：rowid
    //'戻 り 値：無し
    //'処理説明：紹介者レコード変更
    //'**********************************************************************
    btnEdit_Click = function (rowid) {
        me.clearPage();
        //選択行の値を入力ﾃｰﾌﾞﾙに表示する
        var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
        //No.
        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val(
            rowData["LIST_MEISAI_NO"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val("");
        }
        //開催日
        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val(
            rowData["KAISAI_YMD"]
        );
        if (
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val() == "&nbsp;"
        ) {
            $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val("");
        }
        //開始時刻
        $(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val(
            rowData["START_TIME"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val("");
        }
        //終了時刻
        $(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val(rowData["END_TIME"]);
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val("");
        }
        //開催場所
        $(".HMTVE410SyucchoTenjikaiEntry.txtPlace").val(rowData["PLACE"]);
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtPlace").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtPlace").val("");
        }
        //使用デモカー
        $(".HMTVE410SyucchoTenjikaiEntry.txtDemoCars").val(
            rowData["DEMO_CARS"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtDemoCars").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtDemoCars").val("");
        }
        //店舗
        $(".HMTVE410SyucchoTenjikaiEntry.txtPost").val(rowData["TENPO_CD"]);
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtPost").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtPost").val("");
        }
        $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text(
            rowData["BUSYO_RYKNM"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text("");
        }
        //来場
        $(".HMTVE410SyucchoTenjikaiEntry.txtRaijoSu").val(rowData["RAIJYO_SU"]);
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtRaijoSu").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtRaijoSu").val("");
        }
        //アンケート
        $(".HMTVE410SyucchoTenjikaiEntry.txtEnqueteSu").val(
            rowData["ENQUETE_SU"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtEnqueteSu").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtEnqueteSu").val("");
        }
        //ABホット
        $(".HMTVE410SyucchoTenjikaiEntry.txtABHotSu").val(rowData["ABHOT_SU"]);
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtABHotSu").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtABHotSu").val("");
        }
        //見積
        $(".HMTVE410SyucchoTenjikaiEntry.txtMitumoriSu").val(
            rowData["MITUMORI_SU"]
        );
        if (
            $(".HMTVE410SyucchoTenjikaiEntry.txtMitumoriSu").val() == "&nbsp;"
        ) {
            $(".HMTVE410SyucchoTenjikaiEntry.txtMitumoriSu").val("");
        }
        //成約
        $(".HMTVE410SyucchoTenjikaiEntry.txtSeiyakuSu").val(
            rowData["SEIYAKU_SU"]
        );
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtSeiyakuSu").val() == "&nbsp;") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtSeiyakuSu").val("");
        }
        //担当者
        me.SetTantouList(rowData["SYAIN_NO"]);
    };

    //'**********************************************************************
    //'処 理 名：担当者コンボボックスの編集
    //'関 数 名：SetTantouList
    //'引    数：strSyainNo:社員番号
    //'戻 り 値：無し
    //'処理説明：担当者コンボボックスの編集
    //'**********************************************************************
    me.SetTantouList = function (strSyainNo) {
        $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector").find("option").remove();
        //部署に所属する社員を取得する
        me.url = me.sys_id + "/" + me.id + "/" + "getEmploye";

        var data = {
            txtPost: $.trim($(".HMTVE410SyucchoTenjikaiEntry.txtPost").val()),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                if (result["data"].length > 0) {
                    //コンボリストを設定する
                    for (var i = 0; i < result["data"].length; i++) {
                        $("<option></option>")
                            .val(result["data"][i]["SYAIN_NO"])
                            .text(result["data"][i]["SYAIN_NM"])
                            .appendTo(
                                ".HMTVE410SyucchoTenjikaiEntry.ddlDirector"
                            );
                    }
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDirector");
                    //コンボリストを選択する
                    var foundNM_array = result["data"].filter(function (
                        element
                    ) {
                        return element["SYAIN_NO"] == strSyainNo;
                    });
                    if (foundNM_array.length > 0) {
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector").val(
                            strSyainNo
                        );
                    } else {
                        $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector").val("");
                    }
                }
            }
        };
        me.ajax.send(me.url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：入力チェック
    //'関 数 名：checkInputs
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：入力をチェックする
    //'**********************************************************************
    me.checkInputs = function () {
        //№のチェック
        if (me.checkText("txtAcceptNo", "lblAcceptNo") == false) {
            return false;
        }
        //開催日のチェック
        if (me.checkText("txtAcceptDate", "lblAcceptDate") == false) {
            return false;
        }
        //開催場所のチェック
        if (me.checkText("txtPlace", "lblPlace") == false) {
            return false;
        }
        //使用デモカーのチェック
        if (me.checkText("txtDemoCars", "lblDemoCars") == false) {
            return false;
        }
        //部署コードのチェック
        if (me.checkText("txtPost", "lblPost") == false) {
            return false;
        }
        //部署が存在するかチェックを行う
        if ($(".HMTVE410SyucchoTenjikaiEntry.txtPost").val() != "") {
            if ($(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text() == "") {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry.txtPost"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    $(".HMTVE410SyucchoTenjikaiEntry.lblPost").text() +
                        "が存在しません。"
                );
                return false;
            }
        }
        //担当者のチェック
        if ($(".HMTVE410SyucchoTenjikaiEntry.ddlDirector").val() == "") {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry.ddlDirector"
            );
            me.clsComFnc.FncMsgBox("W9999", "担当者を入力してください。");
            return false;
        }
        //桁数チェックを行う
        //No
        if (me.checkLength("txtAcceptNo", "lblAcceptNo", 10) == false) {
            return false;
        }
        //開催日
        if (me.checkLength("txtAcceptDate", "lblAcceptDate", 10) == false) {
            return false;
        }
        //開始時刻
        if (me.checkLength("txtStartTime", "lblStartTime", 5) == false) {
            return false;
        }
        //終了時刻
        if (me.checkLength("txtEndTime", "lblEndTime", 5) == false) {
            return false;
        }
        //開催場所
        if (me.checkLength("txtPlace", "lblPlace", 100) == false) {
            return false;
        }
        //使用デモカー
        if (me.checkLength("txtDemoCars", "lblDemoCars", 100) == false) {
            return false;
        }
        //部署
        if (me.checkLength("txtPost", "lblPost", 3) == false) {
            return false;
        }
        //書式チェックを行う
        //開催日
        if (
            me.clsComFnc.CheckDate(
                $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate")
            ) == false
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "開催日は yyyy/mm/dd 形式で入力してください"
            );
            return false;
        }
        //時刻
        if (
            !/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/.test(
                $.trim($(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val())
            )
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry.txtStartTime"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "開始時刻は hh:mm 形式で入力してください"
            );
            return false;
        }
        if (
            !/^([0-1][0-9]|[2][0-3]):[0-5][0-9]$/.test(
                $.trim($(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val())
            )
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry.txtEndTime"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "終了時刻は hh:mm 形式で入力してください"
            );
            return false;
        }
        //来場者数
        if (!me.notNumberCheck("txtRaijoSu")) {
            return false;
        }
        //アンケート回収数
        if (!me.notNumberCheck("txtEnqueteSu")) {
            return false;
        }
        //ABホット数
        if (!me.notNumberCheck("txtABHotSu")) {
            return false;
        }
        //見積数
        if (!me.notNumberCheck("txtMitumoriSu")) {
            return false;
        }
        //成約数
        if (!me.notNumberCheck("txtSeiyakuSu")) {
            return false;
        }
        return true;
    };

    me.notNumberCheck = function (inp) {
        var val = $.trim($(".HMTVE410SyucchoTenjikaiEntry." + inp).val());
        if (val != "") {
            if (isNaN(val)) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry." + inp
                );
                me.clsComFnc.FncMsgBox("W9999", "入力されている値が不正です。");
                return false;
            }
        }
        return true;
    };

    //'**********************************************************************
    //'処 理 名：データの表示
    //'関 数 名：showData
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：GridViewのデータを表示する
    //'**********************************************************************
    me.showData = function () {
        var txtExhibitTitle1 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val()
        );
        var ddlYear = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val());
        var ddlMonth = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val()
        );
        var ddlDay = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val());
        var ddlYear2 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val()
        );
        var ddlMonth2 = $.trim(
            $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val()
        );
        var ddlDay2 = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val());

        var data = {
            txtExhibitTitle1: txtExhibitTitle1,
            ddlYear: ddlYear,
            ddlMonth: ddlMonth,
            ddlDay: ddlDay,
            ddlYear2: ddlYear2,
            ddlMonth2: ddlMonth2,
            ddlDay2: ddlDay2,
        };

        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.buttonDisEna("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                me.buttonDisEna("enable");
                if (returnFLG == "nodata") {
                    //対象期間を取得する
                    if (result["isExistMeisaiData"] == false) {
                        me.fncDateInit();
                    }

                    if (me.HidReshow != "RESHOWSEARCH") {
                        //該当データはありません。
                        me.clsComFnc.FncMsgBox("W0024");
                    }
                } else {
                    //画面制御を行う
                    $(".HMTVE410SyucchoTenjikaiEntry.tblDetail").show();
                    //１行目を選択状態にする
                    $(me.grid_id).jqGrid("setSelection", "0");
                }
                me.HidReshow = "-1";
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：年、月、日のチェック
    //'関 数 名：checkYMD
    //'引    数：ddlY:年, ddlM:月, ddlD:日
    //'戻 り 値：Boolean
    //'処理説明：年、月、日をチェックする
    //'**********************************************************************
    me.checkYMD = function (ddlY, ddlM, ddlD) {
        if (
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlY + " option").length ==
                1 &&
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlM + " option").length ==
                1 &&
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlD + " option").length == 1
        ) {
            if (me.HidReshow != "RESHOWSEARCH") {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録されているデータが存在しません。登録を行ってください"
                );
            } else {
                $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").trigger("focus");
            }
            return false;
        }
        if (
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val() != "" ||
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlM).val() != "" ||
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlD).val() != ""
        ) {
            //年＝"" OR 月＝"" OR 日＝""の場合
            if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val() == "") {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry." + ddlY
                );
                if (ddlY == "ddlYear") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(開始)の年・月・日のいずれかが入力されていません！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(終了)の年・月・日のいずれかが入力されていません！"
                    );
                }
                return false;
            }
            if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlM).val() == "") {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry." + ddlM
                );
                if (ddlY == "ddlYear") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(開始)の年・月・日のいずれかが入力されていません！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(終了)の年・月・日のいずれかが入力されていません！"
                    );
                }
                return false;
            }
            if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlD).val() == "") {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry." + ddlD
                );
                if (ddlY == "ddlYear") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(開始)の年・月・日のいずれかが入力されていません！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "日付(終了)の年・月・日のいずれかが入力されていません！"
                    );
                }
                return false;
            }
        }
        return true;
    };

    //'**********************************************************************
    //'処 理 名：入力ﾃｰﾌﾞﾙの画面項目の桁数チェック
    //'関 数 名：checkLength
    //'引    数：textBox:テキストボックス, lable:レッボ, length:Integer
    //'戻 り 値：Boolean
    //'処理説明：桁数チェックする
    //'**********************************************************************
    me.checkLength = function (textBox, lable, length) {
        if (
            me.clsComFnc.GetByteCount(
                $(".HMTVE410SyucchoTenjikaiEntry." + textBox).val()
            ) > length
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry." + textBox
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                $(".HMTVE410SyucchoTenjikaiEntry." + lable).text() +
                    "は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        return true;
    };

    //'**********************************************************************
    //'処 理 名：入力ﾃｰﾌﾞﾙの画面項目のチェック
    //'関 数 名：checkText
    //'引    数：textBox:テキストボックス, lable:レッボ
    //'戻 り 値：Boolean
    //'処理説明：入力テキストをチェックする
    //'**********************************************************************
    me.checkText = function (textBox, lable) {
        if ($.trim($(".HMTVE410SyucchoTenjikaiEntry." + textBox).val()) == "") {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE410SyucchoTenjikaiEntry." + textBox
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                $(".HMTVE410SyucchoTenjikaiEntry." + lable).text() +
                    "を入力してください。"
            );
            return false;
        }
        return true;
    };

    //'**********************************************************************
    //'処 理 名：画面項目クリア
    //'関 数 名：clearPage
    //'引    数：scrope
    //'戻 り 値：無し
    //'処理説明：画面項目をクリアする
    //'**********************************************************************
    me.clearPage = function (scrope) {
        if (scrope == "all") {
            $(".HMTVE410SyucchoTenjikaiEntry.txtExhibitTitle1").val("");
            $(".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1").val("");
        }

        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptNo").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtAcceptDate").val("");

        if (
            $(".HMTVE410SyucchoTenjikaiEntry.txtPost").attr("disabled") ==
            undefined
        ) {
            $(".HMTVE410SyucchoTenjikaiEntry.txtPost").val("");
            $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text("");
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector")
                .find("option")
                .remove();
        }

        $(".HMTVE410SyucchoTenjikaiEntry.txtStartTime").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtEndTime").val("");

        $(".HMTVE410SyucchoTenjikaiEntry.txtPlace").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtDemoCars").val("");

        $(".HMTVE410SyucchoTenjikaiEntry.txtRaijoSu").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtEnqueteSu").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtABHotSu").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtMitumoriSu").val("");
        $(".HMTVE410SyucchoTenjikaiEntry.txtSeiyakuSu").val("");
    };

    //'**********************************************************************
    //'処 理 名：年のセット
    //'関 数 名：setYear
    //'引 数   ：ddlY:年, sysdata, flg
    //'戻 り 値：なし
    //'処理説明：年をセットする
    //'**********************************************************************
    me.setYear = function (ddlY, sysdata, objdr, flg) {
        if (flg == undefined) {
            flg = "";
        }
        $(".HMTVE410SyucchoTenjikaiEntry." + ddlY)
            .find("option")
            .remove();
        //空白行をセットする
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE410SyucchoTenjikaiEntry." + ddlY);
        var strMin = objdr[0]["HI_MIN"];
        var strMax = objdr[0]["HI_MAX"];
        var min = 0;
        var max = 0;
        if (strMin != "0" && strMin != null && strMin != "") {
            min = strMin.substring(0, 4);
        }

        if (strMax != "0" && strMax != null && strMax != "") {
            max = strMax.substring(0, 4);
            for (var i = min; i <= max; i++) {
                $("<option></option>")
                    .val(i)
                    .text(i)
                    .prependTo(".HMTVE410SyucchoTenjikaiEntry." + ddlY);
            }
        }
        //デフォルト日付を指定する
        var str = sysdata.substring(0, 4);
        if (max < str) {
            $("<option></option>")
                .val(str)
                .text(str)
                .prependTo(".HMTVE410SyucchoTenjikaiEntry." + ddlY);
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val(str);
        } else {
            if (flg != "RESHOW") {
                $(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val(str);
                if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val() == null) {
                    $(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val(max);
                }
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：月のセット
    //'関 数 名：setMonth
    //'引 数   ：ddlM, sysdata, flg
    //'戻 り 値：なし
    //'処理説明：月をセットする
    //'**********************************************************************
    me.setMonth = function (ddlM, sysdata, flg) {
        if (flg == undefined) {
            flg = "";
        }
        $(".HMTVE410SyucchoTenjikaiEntry." + ddlM)
            .find("option")
            .remove();
        //日付_FROMと日付_TOにセットする
        for (var i = 1; i <= 12; i++) {
            if (i < 10) {
                $("<option></option>")
                    .val("0" + i)
                    .text("0" + i)
                    .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddlM);
            } else {
                $("<option></option>")
                    .val(i)
                    .text(i)
                    .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddlM);
            }
        }
        //デフォルト日付を指定する
        if (flg != "RESHOW") {
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlM).val(
                sysdata.substring(5, 7)
            );
        }
        //空白行をセットする
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddlM);
    };

    //'**********************************************************************
    //'処 理 名：日のセット
    //'関 数 名：setDay
    //'引 数   ：ddlD, ddlY, ddlM, sysdata
    //'戻 り 値：なし
    //'処理説明：日をセットする
    //'**********************************************************************
    me.setDay = function (ddlD, ddlY, ddlM, sysdata) {
        var year = 0;
        var month = 0;
        if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val() != "") {
            year = $(".HMTVE410SyucchoTenjikaiEntry." + ddlY).val();
        } else {
            return;
        }
        if ($(".HMTVE410SyucchoTenjikaiEntry." + ddlM).val() != "") {
            month = $(".HMTVE410SyucchoTenjikaiEntry." + ddlM).val();
        } else {
            return;
        }

        $(".HMTVE410SyucchoTenjikaiEntry." + ddlD)
            .find("option")
            .remove();
        //日付_FROMと日付_TOにセットする
        if (month == "04" || month == "06" || month == "09" || month == "11") {
            me.addDays(ddlD, 30);
        } else {
            if (month == "02") {
                if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
                    me.addDays(ddlD, 29);
                } else {
                    me.addDays(ddlD, 28);
                }
            } else {
                me.addDays(ddlD, 31);
            }
        }
        //デフォルト日付を指定する
        if (sysdata) {
            $(".HMTVE410SyucchoTenjikaiEntry." + ddlD).val(
                sysdata.substring(8, 10)
            );
        }
        //空白行をセットする
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddlD);
    };

    //'**********************************************************************
    //'処 理 名：日の増加
    //'関 数 名：addDays
    //'引 数   ：ddl, DayNum
    //'戻 り 値：なし
    //'処理説明：日を増加する
    //'**********************************************************************
    me.addDays = function (ddl, DayNum) {
        for (var i = 1; i <= DayNum; i++) {
            if (i < 10) {
                $("<option></option>")
                    .val("0" + i)
                    .text("0" + i)
                    .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddl);
            } else {
                $("<option></option>")
                    .val(i)
                    .text(i)
                    .appendTo(".HMTVE410SyucchoTenjikaiEntry." + ddl);
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：フォーカス
    //'関 数 名：FoucsMove
    //'引 数   ：txt
    //'戻 り 値：なし
    //'処理説明：フォーカス移動時
    //'**********************************************************************
    me.FoucsMove = function (txt) {
        //Ⅰー１．入力チェックを行う
        //画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
        var txtValue = $(".HMTVE410SyucchoTenjikaiEntry." + txt).val();
        if (txtValue != "") {
            var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
            if (!objRegEX_AN.test($.trim(txtValue))) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE410SyucchoTenjikaiEntry." + txt
                );
                me.clsComFnc.FncMsgBox("E0013", "部署");
                if (txt == "txtPost") {
                    if (
                        $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text() != ""
                    ) {
                        $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text("");
                    }
                    $(".HMTVE410SyucchoTenjikaiEntry.ddlDirector")
                        .find("option")
                        .remove();
                } else {
                    if (
                        $(
                            ".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1"
                        ).val() != ""
                    ) {
                        $(".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1").val(
                            ""
                        );
                    }
                }
                return;
            }

            //Ⅰー３．店舗名を表示する
            var foundNM = undefined;
            var selCellVal = me.clsComFnc.FncNv($.trim(txtValue));
            if (me.busyoName) {
                var foundNM_array = me.busyoName.filter(function (element) {
                    return element["BUSYO_CD"] == selCellVal;
                });
                if (foundNM_array.length > 0) {
                    foundNM = foundNM_array[0];
                }
            }

            if (txt == "txtPost") {
                $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text(
                    foundNM ? foundNM["BUSYO_RYKNM"] : ""
                );
                //入力ﾃｰﾌﾞﾙ_担当者のコンボリストを設定する
                me.SetTantouList("");
            } else {
                $(".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1").val(
                    foundNM ? foundNM["BUSYO_RYKNM"] : ""
                );
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").trigger("focus");
            }
        } else {
            if (txt == "txtPost") {
                $(".HMTVE410SyucchoTenjikaiEntry.lblPost1").text("");
            } else {
                $(".HMTVE410SyucchoTenjikaiEntry.lblExhibitTitle1").val("");
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").trigger("focus");
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：
    //'関 数 名：DLselectchange
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：ドロップダウンが変化時
    //'**********************************************************************
    me.DLselectchange = function () {
        var i = 0,
            zdr = 0,
            day = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val());
        if (
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() % 400 == 0 ||
            ($(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() % 4 == 0 &&
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear").val() % 100 != 0)
        ) {
            zdr =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() <= "07"
                    ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() % 2 == 0
                        ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() == 2
                            ? 29
                            : 30
                        : 31
                    : $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() % 2 == 0
                    ? 31
                    : 30;
        } else {
            zdr =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() <= "07"
                    ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() % 2 == 0
                        ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() == 2
                            ? 28
                            : 30
                        : 31
                    : $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth").val() % 2 == 0
                    ? 31
                    : 30;
        }
        $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").children().remove();
        for (var i = 1; i <= 9; i++) {
            $("<option></option>")
                .val("0" + i)
                .text("0" + i)
                .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay");
        }
        for (i = 10; i <= parseInt(zdr); i++) {
            $("<option></option>")
                .val(i)
                .text(i)
                .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay");
        }
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay");
        if (day == "") {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").get(0).selectedIndex =
                parseInt(zdr);
        } else if (parseInt(day) > parseInt(zdr)) {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").get(0).selectedIndex = 0;
        } else {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay").val(day);
        }
    };

    //'**********************************************************************
    //'処 理 名：
    //'関 数 名：DLselectchange2
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：ドロップダウンが変化時
    //'**********************************************************************
    me.DLselectchange2 = function () {
        var i = 0,
            zdr = 0,
            day = $.trim($(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val());
        if (
            $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() % 400 == 0 ||
            ($(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() % 4 == 0 &&
                $(".HMTVE410SyucchoTenjikaiEntry.ddlYear2").val() % 100 != 0)
        ) {
            zdr =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() <= "07"
                    ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() % 2 ==
                      0
                        ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() ==
                          2
                            ? 29
                            : 30
                        : 31
                    : $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() % 2 ==
                      0
                    ? 31
                    : 30;
        } else {
            zdr =
                $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() <= "07"
                    ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() % 2 ==
                      0
                        ? $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() ==
                          2
                            ? 28
                            : 30
                        : 31
                    : $(".HMTVE410SyucchoTenjikaiEntry.ddlMonth2").val() % 2 ==
                      0
                    ? 31
                    : 30;
        }
        $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").children().remove();
        for (var i = 1; i <= 9; i++) {
            $("<option></option>")
                .val("0" + i)
                .text("0" + i)
                .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay2");
        }
        for (i = 10; i <= parseInt(zdr); i++) {
            $("<option></option>")
                .val(i)
                .text(i)
                .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay2");
        }
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE410SyucchoTenjikaiEntry.ddlDay2");
        if (day == "") {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").get(0).selectedIndex =
                parseInt(zdr);
        } else if (parseInt(day) > parseInt(zdr)) {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").get(0).selectedIndex = 0;
        } else {
            $(".HMTVE410SyucchoTenjikaiEntry.ddlDay2").val(day);
        }
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE410SyucchoTenjikaiEntry =
        new HMTVE.HMTVE410SyucchoTenjikaiEntry();
    o_HMTVE_HMTVE410SyucchoTenjikaiEntry.load();
});
