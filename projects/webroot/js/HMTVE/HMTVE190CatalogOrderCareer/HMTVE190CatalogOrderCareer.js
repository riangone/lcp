/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE190CatalogOrderCareer");

HMTVE.HMTVE190CatalogOrderCareer = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE190CatalogOrderCareer";
    me.HMTVE = new HMTVE.HMTVE();
    me.maxday = "";
    // jqgrid
    me.grid_id = "#HMTVE190CatalogOrderCareer_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnETSearchClick";
    me.pager = "#HMTVE190CatalogOrderCareer_pager";
    me.option = {
        pagerpos: "center",
        // viewrecords : false,
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
        recordpos: "right",
    };
    me.colModel = [
        {
            name: "ORDER_DATE",
            label: "注文日",
            index: "ORDER_DATE",
            width: 143,
            align: "left",
            sortable: false,
        },
        {
            name: "ORDER_NO",
            label: "注文番号",
            index: "ORDER_NO",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "CATALOG_CD",
            label: "コード",
            index: "CATALOG_CD",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "CATALOG_KB_NM",
            label: "カタログ種類",
            index: "CATALOG_KB_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "CATALOG_NM",
            label: "カタログ名称",
            index: "CATALOG_NM",
            width: 250,
            align: "left",
            sortable: false,
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            formatter: "integer",
            width: 48,
            align: "right",
            sortable: false,
        },
        {
            name: "ORDER_NUM",
            label: "注文数",
            index: "ORDER_NUM",
            formatter: "integer",
            width: 55,
            align: "right",
            sortable: false,
        },
        {
            name: "KINGAKU",
            label: "合計",
            index: "KINGAKU",
            formatter: "integer",
            width: 90,
            align: "right",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE190CatalogOrderCareer.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE190CatalogOrderCareer.btnETSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE190CatalogOrderCareer.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE190CatalogOrderCareer.btnDecide",
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

    //表示ボタンクリック
    $(".HMTVE190CatalogOrderCareer.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    //FROM_年change
    $(".HMTVE190CatalogOrderCareer.ddlYearStart").change(function () {
        me.DLselectchange();
    });
    //FROM_月change
    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").change(function () {
        me.DLselectchange();
    });
    //TO_年change
    $(".HMTVE190CatalogOrderCareer.ddlYearEnd").change(function () {
        me.DLselectchange2();
    });
    //TO_月change
    $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").change(function () {
        me.DLselectchange2();
    });
    $(".HMTVE190CatalogOrderCareer.ddlYearStart").change(function () {
        me.Clear_PageLayout();
    });
    $(".HMTVE190CatalogOrderCareer.ddlYearEnd").change(function () {
        me.Clear_PageLayout();
    });
    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").change(function () {
        me.Clear_PageLayout();
    });
    $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").change(function () {
        me.Clear_PageLayout();
    });
    $(".HMTVE190CatalogOrderCareer.ddlDayStart").change(function () {
        me.Clear_PageLayout();
    });
    $(".HMTVE190CatalogOrderCareer.ddlDayEnd").change(function () {
        me.Clear_PageLayout();
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
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1020);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 240);
        $(me.grid_id).jqGrid("bindKeys");
        $(me.grid_id).jqGrid("setFrozenColumns");
        me.Clear_PageLayout();
        me.url = me.sys_id + "/" + me.id + "/" + "pageload";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "W9999") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE190CatalogOrderCareer.ddlYearStart"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "表示できる部署が存在しません。管理者にお問い合わせください。"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                $(".HMTVE190CatalogOrderCareer.btnETSearch").button("disable");
            } else {
                if (result["data"]["BusyoCD"] == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE190CatalogOrderCareer.ddlYearStart"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "表示できる部署が存在しません。管理者にお問い合わせください。"
                    );
                    $(".HMTVE190CatalogOrderCareer.btnETSearch").button(
                        "disable"
                    );
                    return false;
                }
                if (result["data"]["getTerm"].length != 0) {
                    if (result["data"]["getTerm"][0]["IVENTMAX"] == null) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE190CatalogOrderCareer.ddlYearStart"
                        );
                        me.clsComFnc.FncMsgBox("E9999", "履歴は存在しません。");
                    } else {
                        //コンボリストを設定する()
                        me.setDdlYmd(
                            result["data"]["getTerm"][0],
                            result["data"]["sysDate"]
                        );
                        $(".HMTVE190CatalogOrderCareer.ddlYearStart").trigger(
                            "focus"
                        );
                    }
                    //店舗名を表示する
                    me.ExpressShopName(result["data"]["getShop"]);
                } else {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE190CatalogOrderCareer.ddlYearStart"
                    );
                    me.clsComFnc.FncMsgBox("E9999", "履歴は存在しません。");
                    return;
                }
                // $(".HMTVE190CatalogOrderCareer.ddlYearStart").focus();
            }
        };
        me.ajax.send(me.url, data, 1);
    };
    // '**********************************************************************
    // '処 理 名：表示ボタンのイベント
    // '関 数 名：btnETSearch_Click
    // '戻 り 値：なし
    // '処理説明：テータを取得して、表示します
    // '**********************************************************************
    me.btnETSearch_Click = function () {
        //１．入力チェックを行う
        if (me.CheckYM() == false) {
            return false;
        }
        //２．画面項目のクリア処理
        me.Clear_PageLayout();
        var data = {
            ddlYearStart: $(".HMTVE190CatalogOrderCareer.ddlYearStart").val(),
            ddlMonthStart: $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val(),
            ddlDayStart: $(".HMTVE190CatalogOrderCareer.ddlDayStart").val(),
            ddlYearEnd: $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val(),
            ddlMonthEnd: $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val(),
            ddlDayEnd: $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val(),
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["records"] == 0) {
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            if (result["page"] == "1") {
                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", "0");
            } else {
                //ページをめくる後,１行目を選択状態にする
                var selRow = $(".ui-pg-selbox").val() * (result["page"] - 1);
                $(me.grid_id).jqGrid("setSelection", selRow);
            }
            $(".HMTVE190CatalogOrderCareer.pnlList").show();
            $(".HMTVE190CatalogOrderCareer.txtForecast_L1").trigger("focus");
            // $(me.grid_id).jqGrid('setSelection', 0);
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun, 1);
    };
    // '**********************************************************************
    // '処 理 名：店舗名
    // '関 数 名：ExpressShopName
    // '引 数   ：objdr
    // '戻 り 値：なし
    // '処理説明：店舗名を表示する
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    me.ExpressShopName = function (BusyoCD) {
        try {
            if (BusyoCD.length > 0) {
                $(".HMTVE190CatalogOrderCareer.lblPosition").val(
                    BusyoCD[0]["BUSYO_RYKNM"]
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    // '**********************************************************************
    // '処 理 名：コンボリスト年月日
    // '関 数 名：setDdlYmd
    // '引 数   ：objdr
    // '戻 り 値：なし
    // '処理説明：コンボリスト年月日を設定する
    // '2009/04/02 UPD clsdb追加0
    // '**********************************************************************
    me.setDdlYmd = function (objdr, sysDate) {
        try {
            var sysDate = new Date(sysDate);
            var sysYear = sysDate.getFullYear();
            var sysMonth = sysDate.getMonth() + 1;
            var sysDay = sysDate.getDate();
            var flg = 0;
            //日付_FROM_年にセットする
            if (objdr) {
                var IVENTMIN = parseInt(objdr["IVENTMIN"].substring(0, 4));
                var IVENTMAX = parseInt(objdr["IVENTMAX"].substring(0, 4));
                for (var index = IVENTMAX; index >= IVENTMIN; index--) {
                    $("<option></option>")
                        .val(index)
                        .text(index)
                        .appendTo(".HMTVE190CatalogOrderCareer.ddlYearStart");
                }
                if (sysMonth == "1") {
                    $(".HMTVE190CatalogOrderCareer.ddlYearStart").val(
                        sysYear - 1
                    );
                    if (
                        $(".HMTVE190CatalogOrderCareer.ddlYearStart").val() !=
                        sysYear - 1
                    ) {
                        $(".HMTVE190CatalogOrderCareer.ddlYearStart").val(
                            sysYear
                        );
                        flg = flg + 1;
                    }
                } else {
                    $(".HMTVE190CatalogOrderCareer.ddlYearStart").val(sysYear);
                }
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".HMTVE190CatalogOrderCareer.ddlYearStart");
            }
            for (var index = 1; index <= 12; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE190CatalogOrderCareer.ddlMonthStart");
            }
            if (sysMonth == "1") {
                if (flg == 1) {
                    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val("1");
                } else {
                    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val("12");
                }
            } else {
                if (sysMonth < 11) {
                    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val(
                        "0" + (sysMonth - 1)
                    );
                } else {
                    $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val(
                        sysMonth - 1
                    );
                }
            }
            //月のコンボリストを設定する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE190CatalogOrderCareer.ddlMonthStart");
            //日のコンボリストを選択する
            me.DLselectchange();
            if (sysDay < 10) {
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").val("0" + sysDay);
            } else {
                if (me.maxday < sysDay) {
                    $(".HMTVE190CatalogOrderCareer.ddlDayStart").val(me.maxday);
                } else {
                    $(".HMTVE190CatalogOrderCareer.ddlDayStart").val(sysDay);
                }
            }
            if (flg == 1) {
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").val("01");
            }
            if (objdr) {
                var IVENTMIN = parseInt(objdr["IVENTMIN"].substring(0, 4));
                var IVENTMAX = parseInt(objdr["IVENTMAX"].substring(0, 4));
                for (var index = IVENTMAX; index >= IVENTMIN; index--) {
                    $("<option></option>")
                        .val(index)
                        .text(index)
                        .appendTo(".HMTVE190CatalogOrderCareer.ddlYearEnd");
                }
            }
            $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val(sysYear);
            //日付_TO_年にセットする
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE190CatalogOrderCareer.ddlYearEnd");

            for (var index = 1; index <= 12; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE190CatalogOrderCareer.ddlMonthEnd");
            }
            if (sysMonth < 10) {
                $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val(
                    "0" + sysMonth
                );
            } else {
                $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val(sysMonth);
            }
            //月のコンボリストを設定する
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE190CatalogOrderCareer.ddlMonthEnd");
            //日のコンボリストを選択する
            me.DLselectchange2();
            if (sysDay < 10) {
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val("0" + sysDay);
            } else {
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val(sysDay);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年月日
	 '関 数 名：DLselectchange
	 '戻 り 値：なし
	 '処理説明：年月日を処理
	 '**********************************************************************
	 */
    me.DLselectchange = function () {
        try {
            var zdr = 0,
                j = "";
            var strDay = $.trim(
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").val()
            );
            if (
                $(".HMTVE190CatalogOrderCareer.ddlYearStart").val() % 400 ==
                    0 ||
                ($(".HMTVE190CatalogOrderCareer.ddlYearStart").val() % 4 == 0 &&
                    $(".HMTVE190CatalogOrderCareer.ddlYearStart").val() % 100 !=
                        0)
            ) {
                var temp =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(
                                  ".HMTVE190CatalogOrderCareer.ddlMonthStart"
                              ).val()
                          ) == 2
                            ? 29
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(
                                  ".HMTVE190CatalogOrderCareer.ddlMonthStart"
                              ).val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                var temp =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(
                                  ".HMTVE190CatalogOrderCareer.ddlMonthStart"
                              ).val()
                          ) == 2
                            ? 28
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(
                                  ".HMTVE190CatalogOrderCareer.ddlMonthStart"
                              ).val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE190CatalogOrderCareer.ddlDayStart").children().remove();

            me.maxday = zdr;
            for (var i = 1; i <= zdr; i++) {
                if (i < 10) {
                    j = "0" + i;
                } else {
                    j = "" + i;
                }
                $("<option></option>")
                    .val(j)
                    .text(j)
                    .appendTo(".HMTVE190CatalogOrderCareer.ddlDayStart");
            }
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE190CatalogOrderCareer.ddlDayStart");
            if (strDay == "") {
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").get(
                    0
                ).selectedIndex = 0;
            } else if (parseInt(strDay) > parseInt(zdr)) {
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").get(
                    0
                ).selectedIndex = 1;
            } else {
                $(".HMTVE190CatalogOrderCareer.ddlDayStart").val(strDay);
            }
            $(".HMTVE190CatalogOrderCareer.ddlDayStart").val("");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年月日
	 '関 数 名：DLselectchange2
	 '戻 り 値：なし
	 '処理説明：年月日を処理
	 '**********************************************************************
	 */
    me.DLselectchange2 = function () {
        try {
            var zdr = 0,
                j = "";
            var strDay = $.trim(
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val()
            );
            if (
                $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val() % 400 == 0 ||
                ($(".HMTVE190CatalogOrderCareer.ddlYearEnd").val() % 4 == 0 &&
                    $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val() % 100 !=
                        0)
            ) {
                var temp =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                          ) == 2
                            ? 29
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                var temp =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                    ) %
                        2 ==
                    0
                        ? parseInt(
                              $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                          ) == 2
                            ? 28
                            : 30
                        : 31;
                zdr =
                    parseInt(
                        $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                    ) <= 7
                        ? temp
                        : parseInt(
                              $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val()
                          ) %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE190CatalogOrderCareer.ddlDayEnd").children().remove();

            for (var i = 1; i <= zdr; i++) {
                if (i < 10) {
                    j = "0" + i;
                } else {
                    j = "" + i;
                }
                $("<option></option>")
                    .val(j)
                    .text(j)
                    .appendTo(".HMTVE190CatalogOrderCareer.ddlDayEnd");
            }
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE190CatalogOrderCareer.ddlDayEnd");
            if (strDay == "") {
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").get(
                    0
                ).selectedIndex = 0;
            } else if (parseInt(strDay) > parseInt(zdr)) {
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").get(
                    0
                ).selectedIndex = 1;
            } else {
                $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val(strDay);
            }
            $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val("");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：Clear_Again
	 '処理説明：
	 '**********************************************************************
	 */
    me.Clear_PageLayout = function () {
        try {
            $(me.grid_id).jqGrid("clearGridData");
            $(".HMTVE190CatalogOrderCareer.pnlList").hide();
        } catch (ex) {
            console.log(ex);
        }
    };
    // '**********************************************************************
    // '処 理 名：入力チェック
    // '関 数 名：CheckYM
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：入力チェックを行う
    // '**********************************************************************
    me.CheckYM = function () {
        var ddlY = $(".HMTVE190CatalogOrderCareer.ddlYearStart").val();
        var ddlM = $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val();
        var ddlD = $(".HMTVE190CatalogOrderCareer.ddlDayStart").val();
        if (
            ddlY == "" ||
            ddlM == "" ||
            ddlD == "" ||
            ddlY == null ||
            ddlM == null ||
            ddlD == null
        ) {
            if (ddlY == "" || ddlY == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlYearStart"
                );
            } else if (ddlM == "" || ddlM == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlMonthStart"
                );
            } else if (ddlD == "" || ddlD == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlDayStart"
                );
            }
            me.clsComFnc.FncMsgBox("W9999", "注文日を選択してください！");
            return false;
        }
        var ddlY = $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val();
        var ddlM = $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val();
        var ddlD = $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val();
        if (
            ddlY == "" ||
            ddlM == "" ||
            ddlD == "" ||
            ddlY == null ||
            ddlM == null ||
            ddlD == null
        ) {
            if (ddlY == "" || ddlY == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlYearEnd"
                );
            } else if (ddlM == "" || ddlM == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlMonthEnd"
                );
            } else if (ddlD == "" || ddlD == null) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE190CatalogOrderCareer.ddlDayEnd"
                );
            }
            me.clsComFnc.FncMsgBox("W9999", "注文日を選択してください！");
            return false;
        }
        var start_ymd =
            $(".HMTVE190CatalogOrderCareer.ddlYearStart").val() +
            $(".HMTVE190CatalogOrderCareer.ddlMonthStart").val() +
            $(".HMTVE190CatalogOrderCareer.ddlDayStart").val();
        var end_ymd =
            $(".HMTVE190CatalogOrderCareer.ddlYearEnd").val() +
            $(".HMTVE190CatalogOrderCareer.ddlMonthEnd").val() +
            $(".HMTVE190CatalogOrderCareer.ddlDayEnd").val();
        if (start_ymd > end_ymd) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE190CatalogOrderCareer.ddlYearStart"
            );
            me.clsComFnc.FncMsgBox("W9999", "注文日の大小関係が不正です");
            return false;
        }
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE190CatalogOrderCareer =
        new HMTVE.HMTVE190CatalogOrderCareer();
    o_HMTVE_HMTVE190CatalogOrderCareer.load();
    o_HMTVE_HMTVE.HMTVE190CatalogOrderCareer =
        o_HMTVE_HMTVE190CatalogOrderCareer;
});
