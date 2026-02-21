/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("HMTVE.HMTVE230PresentOrderCareer");

HMTVE.HMTVE230PresentOrderCareer = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE230PresentOrderCareer";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE230PresentOrderCareer_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/btn_Click";
    me.pager = "#HMTVE230PresentOrderCareer_pager";
    me.option = {
        rowNum: 9,
        rowList: [9, 15, 25],
        caption: "",
        rownumbers: false,
        multiselect: false,
        autoScroll: true,
        //shrinkToFit : false,
        colModel: me.colModel,
        pager: me.pager, //分页容器
        //pagerpos : "center",
        recordpos: "right",
        datatype: "json",
    };
    me.colModel = [
        {
            name: "HIDUKE",
            label: "展示会開催期間",
            index: "HIDUKE",
            width: 168,
            align: "left",
            sortable: false,
        },
        {
            name: "IVENT_NM",
            label: "イベント名",
            index: "IVENT_NM",
            width: 412,
            align: "left",
            sortable: false,
        },
        {
            name: "HINMEI",
            label: "品名",
            index: "HINMEI",
            width: 245,
            align: "left",
            sortable: false,
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            width: 70,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "ORDER_NUM",
            label: "注文数",
            index: "ORDER_NUM",
            width: 55,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "KINGAKU",
            label: "合計",
            index: "KINGAKU",
            width: 90,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE230PresentOrderCareer.btn",
        type: "button",
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

    // 表示ボタン
    $(".HMTVE230PresentOrderCareer.btn").click(function () {
        me.btn_Click();
    });
    //年月日
    $(".HMTVE230PresentOrderCareer.DropDownList").change(function () {
        $(".HMTVE230PresentOrderCareer.pnlList").hide();
    });
    //年月
    $(".HMTVE230PresentOrderCareer.ddlYearB").change(function () {
        me.DLBEselectchange("B");
    });
    $(".HMTVE230PresentOrderCareer.ddlYearE").change(function () {
        me.DLBEselectchange("E");
    });
    $(".HMTVE230PresentOrderCareer.ddlMonthB").change(function () {
        me.DLBEselectchange("B");
    });
    $(".HMTVE230PresentOrderCareer.ddlMonthE").change(function () {
        me.DLBEselectchange("E");
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

        //プロシージャ:画面初期化
        me.Page_Load();
    };

    gdmz.common.jqgrid.init2(
        me.grid_id,
        me.g_url,
        me.colModel,
        me.pager,
        "",
        me.option
    );
    gdmz.common.jqgrid.set_grid_width(
        me.grid_id,
        $(".HMTVE230PresentOrderCareer fieldset").width()
    );
    gdmz.common.jqgrid.set_grid_height(me.grid_id, 236);
    $(me.grid_id).jqGrid("bindKeys");

    /*
	 '************************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ページ初期化
	 '************************************************************************
	 */
    me.Page_Load = function () {
        //画面項目履歴ﾃｰﾌﾞﾙを非表示にする
        $(".HMTVE230PresentOrderCareer.pnlList").hide();
        //画面項目をクリアする
        me.Page_clear();
        if (gdmz.SessionPatternID) {
            //コンボリストを設定する
            //対象期間を取得する
            var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //画面項目No5．展示会開催期間(年)FROMにフォーカス移動
                $(".HMTVE230PresentOrderCareer.ddlYearB").trigger("focus");
                var objdr = result["data"];
                if (objdr.length == 0 || !objdr[0]["IVENTMAX"]) {
                    //取得データが存在しない場合は、メッセージ表示後、処理を中断する
                    me.clsComFnc.FncMsgBox("E9999", "履歴は存在しません。");
                    me.Page_clear();
                } else {
                    //コンボリストに日付を設定する
                    me.Page_Drop(objdr[0]["IVENTMIN"], objdr[0]["IVENTMAX"]);
                }
                //店舗名を表示する
                me.Page_ShopNameSave();
            };
            me.ajax.send(url, "", 0);
        }
    };
    /*
	 '************************************************************************
	 '処 理 名：ページクリア
	 '関 数 名：Page_clear
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：画面項目をクリアする
	 '************************************************************************
	 */
    me.Page_clear = function () {
        //画面項目NO5,NO6,NO7,NO8,NO9,NO10,NO11をクリアする
        $(".HMTVE230PresentOrderCareer.ddlYearB").val("");
        $(".HMTVE230PresentOrderCareer.ddlYearE").val("");
        $(".HMTVE230PresentOrderCareer.ddlMonthB").val("");
        $(".HMTVE230PresentOrderCareer.ddlMonthE").val("");
        $(".HMTVE230PresentOrderCareer.ddlDayB").val("");
        $(".HMTVE230PresentOrderCareer.ddlDayE").val("");
        $(".HMTVE230PresentOrderCareer.lblShopMei").val("");
        //画面項目No13.履歴ﾃｰﾌﾞﾙをクリアする
        $(me.grid_id).jqGrid("clearGridData");
    };
    /*
	 '************************************************************************
	 '処 理 名：コンボリストを設定する
	 '関 数 名：Page_Drop
	 '引 数 １：(I)　strBegin 開催期間FROM
	 '引 数 ２：(I)　strEnd   開催期間TO
	 '戻 り 値 ：なし
	 '処理説明 ：ページ初期化時、コンボリストに日付を設定する
	 '************************************************************************
	 */
    me.Page_Drop = function (strBegin, strEnd) {
        //展示会開催期間(年)FROM
        var iYearB = parseInt(strBegin.substring(0, 4));
        //展示会開催期間(年)TO
        var iYearE = parseInt(strEnd.substring(0, 4));

        //年のコンボリストを設定する
        me.DropYearSet(iYearB, iYearE);
        //月のコンボリストを設定する
        me.DropMonthSet();
        //日のコンボリストを設定する
        me.DropDaySet();
    };
    /*
	 '************************************************************************
	 '処 理 名：コンボリスト(年)を設定する
	 '関 数 名：DropYearSet
	 '引 数 １：(I)　strYearB 開催期間(年)FROM
	 '引 数 ２：(I)　strYearE 開催期間(年)TO
	 '戻 り 値：なし
	 '処理説明：年のコンボリストを設定する
	 '************************************************************************
	 */
    me.DropYearSet = function (strYearB, strYearE) {
        //年のデフォルトは空白を設定する
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE230PresentOrderCareer.ddlYearB");
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE230PresentOrderCareer.ddlYearE");
        //展示会開催期間(年)FROMと展示会開催期間(年)TOにセットする
        for (var i = strYearB; i <= strYearE; i++) {
            $("<option></option>")
                .val(i)
                .text(i)
                .prependTo(".HMTVE230PresentOrderCareer.ddlYearB");
            $("<option></option>")
                .val(i)
                .text(i)
                .prependTo(".HMTVE230PresentOrderCareer.ddlYearE");
        }
        //対象年月に初期値をセットする
        //システム日付を取得する
        var sysDate = new Date();
        var sysYear = parseInt(sysDate.getFullYear());

        $(".HMTVE230PresentOrderCareer.ddlYearB").val(sysYear);
        $(".HMTVE230PresentOrderCareer.ddlYearE").val(sysYear);
        if (sysYear <= strYearE && sysYear >= strYearB) {
            $(".HMTVE230PresentOrderCareer.ddlYearB").val(sysYear);
            $(".HMTVE230PresentOrderCareer.ddlYearE").val(sysYear);
        } else {
            $(".HMTVE230PresentOrderCareer.ddlYearB").val(strYearE);
            $(".HMTVE230PresentOrderCareer.ddlYearE").val(strYearE);
        }
    };
    /*
	 '************************************************************************
	 '処 理 名：コンボリスト(月)を設定する
	 '関 数 名：DropMonthSet
	 '引 数  ：なし
	 '戻 り 値：なし
	 '処理説明：月のコンボリストを設定する
	 '************************************************************************
	 */
    me.DropMonthSet = function () {
        //月のデフォルトは空白を設定する
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE230PresentOrderCareer.ddlMonthB");
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE230PresentOrderCareer.ddlMonthE");
        //展示会開催期間(月)FROMと展示会開催期間(月)TOのコンボリストに1～12をセットする
        for (var index = 12; index >= 1; index--) {
            value = "" + index;
            if (index < 10) {
                value = "0" + index;
            }
            $("<option></option>")
                .val(value)
                .text(value)
                .prependTo(".HMTVE230PresentOrderCareer.ddlMonthB");
            $("<option></option>")
                .val(value)
                .text(value)
                .prependTo(".HMTVE230PresentOrderCareer.ddlMonthE");
        }

        var sysDate = new Date();
        var month = parseInt(sysDate.getMonth()) + 1;
        var sysMonth = month < 10 ? "0" + month : month;

        $(".HMTVE230PresentOrderCareer.ddlMonthB").val(sysMonth);
        $(".HMTVE230PresentOrderCareer.ddlMonthE").val(sysMonth);
    };
    /*
	 '************************************************************************
	 '処 理 名：コンボリスト(日)を設定する
	 '関 数 名：DropDaySet
	 '引    数：なし
	 '戻 り 値：なし
	 '処理説明：日のコンボリストを設定する
	 '************************************************************************
	 */
    me.DropDaySet = function () {
        //展示会開催期間(日)FROMと展示会開催期間(日)TOのコンボリストをセットする
        me.DLBEselectchange("B");
        me.DLBEselectchange("E");
        var sysDate = new Date();
        var sysYear = parseInt(sysDate.getFullYear());
        var sysMonth = parseInt(sysDate.getMonth()) + 1;
        var sysDay = new Date(sysYear, sysMonth, 0).getDate();

        $(".HMTVE230PresentOrderCareer.ddlDayB").val("01");
        $(".HMTVE230PresentOrderCareer.ddlDayE").val(sysDay);
    };
    /*
	 '************************************************************************
	 '処 理 名：ページ初期化とき、店舗名を表示する
	 '関 数 名：Page_ShopNameSave
	 '引    数：なし
	 '戻 り 値：なし
	 '処理説明：店舗名を表示する
	 '************************************************************************
	 */
    me.Page_ShopNameSave = function () {
        //店舗名を取得する
        var url = me.sys_id + "/" + me.id + "/" + "Page_ShopNameSave";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var objdr = result["data"];
            if (objdr.length > 0) {
                //画面項目(店舗名)に抽出データ("BUSYO_RYKNM")を表示する
                $(".HMTVE230PresentOrderCareer.lblShopMei").val(
                    objdr[0]["BUSYO_RYKNM"]
                );
            } else {
                $(".HMTVE230PresentOrderCareer.lblShopMei").val("");
            }
        };
        me.ajax.send(url, "", 0);
    };
    /*
	 '************************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fncInputCheck
	 '引    数：なし
	 '戻 り 値：なし
	 '処理説明：入力チェック
	 '************************************************************************
	 */
    me.fncInputCheck = function () {
        //画面項目NO5.展示会開催期間(年)FROM ＝"" OR 画面項目NO6.展示会開催期間(月)FROM ＝""
        //OR 画面項目NO7.展示会開催期間(日)FROM ＝"" OR 画面項目NO8.展示会開催期間(年)TO ＝""
        //OR 画面項目NO9.展示会開催期間(月)TO＝ "" OR 画面項目NO10.展示会開催期間(日)TO ＝"" の場合
        // ｴﾗｰﾒｯｾｰｼﾞを表示する
        var ddlYearB = $(".HMTVE230PresentOrderCareer.ddlYearB").val();
        var ddlYearE = $(".HMTVE230PresentOrderCareer.ddlYearE").val();
        var ddlMonthB = $(".HMTVE230PresentOrderCareer.ddlMonthB").val();
        var ddlMonthE = $(".HMTVE230PresentOrderCareer.ddlMonthE").val();
        var ddlDayB = $(".HMTVE230PresentOrderCareer.ddlDayB").val();
        var ddlDayE = $(".HMTVE230PresentOrderCareer.ddlDayE").val();
        if (
            ddlYearB == "" ||
            ddlYearE == "" ||
            ddlMonthB == "" ||
            ddlMonthE == "" ||
            ddlDayB == "" ||
            ddlDayE == ""
        ) {
            //エラーメッセージを表示して、処理を中止する
            //メッセージ内容："展示会開催年月を選択してください！"
            $(me.grid_id).jqGrid("clearGridData");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催年月を選択してください！"
            );
            return false;
        } else if (!me.Drop_Check()) {
            //展示会開催期間の大小関係判断する
            me.clsComFnc.ObjFocus = $(".HMTVE230PresentOrderCareer.ddlYearB");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間の大小関係が不正です！"
            );
            return false;
        }
        return true;
    };
    /*
	 '************************************************************************
	 '処 理 名：表示ボタンクリックする
	 '関 数 名：btn_Click
	 '引    数：なし
	 '戻 り 値：なし
	 '処理説明：表示ボタンクリック
	 '************************************************************************
	 */
    me.btn_Click = function () {
        $(".HMTVE230PresentOrderCareer.pnlList").hide();
        //入力チェックを行う
        if (!me.fncInputCheck()) {
            return;
        }
        //画面項目のクリア処理
        $(me.grid_id).jqGrid("clearGridData");
        //データの取得:履歴テーブルの生成(GRIDVIEW)
        //履歴データ取得:表示編集①　履歴データ　参照
        var ddlYearB = $(".HMTVE230PresentOrderCareer.ddlYearB").val();
        var ddlYearE = $(".HMTVE230PresentOrderCareer.ddlYearE").val();
        var ddlMonthB = $(".HMTVE230PresentOrderCareer.ddlMonthB").val();
        var ddlMonthE = $(".HMTVE230PresentOrderCareer.ddlMonthE").val();
        var ddlDayB = $(".HMTVE230PresentOrderCareer.ddlDayB").val();
        var ddlDayE = $(".HMTVE230PresentOrderCareer.ddlDayE").val();
        var data = {
            strB: ddlYearB + ddlMonthB + ddlDayB,
            strE: ddlYearE + ddlMonthE + ddlDayE,
        };
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            //イベント名処理
            var ids = $(me.grid_id).jqGrid("getDataIDs");
            for (var i = 0; i < ids.length; i++) {
                var rowData = $(me.grid_id).jqGrid("getRowData", ids[i]);
                $(
                    me.grid_id +
                        " tr[id='" +
                        i +
                        "']" +
                        " td[aria-describedby='HMTVE230PresentOrderCareer_tblMain_IVENT_NM']"
                ).html(me.SubStr(rowData["IVENT_NM"]));
            }
            $(".HMTVE230PresentOrderCareer.pnlList").show();
            //１行目を選択状態にする
            $(me.grid_id).jqGrid("setSelection", "0");
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    /*
	 '************************************************************************
	 '処 理 名：イベント名処理
	 '関 数 名： SubStr
	 '引 数 １：(I)sString String
	 '戻 り 値：String
	 '処理説明：イベント名処理
	 '************************************************************************
	 */
    me.SubStr = function (sString) {
        if (sString.length <= 34) {
            return sString;
        }
        var sNewStr = sString.substring(0, 34) + "...";
        return sNewStr;
    };
    /*
	 '************************************************************************
	 '処 理 名：展示会開催期間の大小関係処理
	 '関 数 名： Drop_Check
	 '引 数 １：なし
	 '戻 り 値：Boolean
	 '処理説明：展示会開催期間の大小関係判断する
	 '************************************************************************
	 */
    me.Drop_Check = function () {
        var ddlYearB = $(".HMTVE230PresentOrderCareer.ddlYearB").val();
        var ddlYearE = $(".HMTVE230PresentOrderCareer.ddlYearE").val();
        var ddlMonthB = $(".HMTVE230PresentOrderCareer.ddlMonthB").val();
        var ddlMonthE = $(".HMTVE230PresentOrderCareer.ddlMonthE").val();
        var ddlDayB = $(".HMTVE230PresentOrderCareer.ddlDayB").val();
        var ddlDayE = $(".HMTVE230PresentOrderCareer.ddlDayE").val();
        //展示会開催期間FROM
        var ddlB = ddlYearB + "/" + ddlMonthB + "/" + ddlDayB;
        //展示会開催期間TO
        var ddlE = ddlYearE + "/" + ddlMonthE + "/" + ddlDayE;
        //展示会開催期間の大小関係が不正です
        if (new Date(ddlE) < new Date(ddlB)) {
            return false;
        }
        return true;
    };
    me.DLBEselectchange = function (name) {
        var value = "";
        var j = 0;
        if (
            $(".HMTVE230PresentOrderCareer.ddlYear" + name).val() % 400 == 0 ||
            ($(".HMTVE230PresentOrderCareer.ddlYear" + name).val() % 4 == 0 &&
                $(".HMTVE230PresentOrderCareer.ddlYear" + name).val() % 100 !=
                    0)
        ) {
            var temp =
                parseInt(
                    $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                ) %
                    2 ==
                0
                    ? parseInt(
                          $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                      ) == 2
                        ? 29
                        : 30
                    : 31;
            j =
                parseInt(
                    $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                ) <= 7
                    ? temp
                    : parseInt(
                          $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                      ) %
                          2 ==
                      0
                    ? 31
                    : 30;
        } else {
            var temp =
                parseInt(
                    $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                ) %
                    2 ==
                0
                    ? parseInt(
                          $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                      ) == 2
                        ? 28
                        : 30
                    : 31;
            j =
                parseInt(
                    $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                ) <= 7
                    ? temp
                    : parseInt(
                          $(".HMTVE230PresentOrderCareer.ddlMonth" + name).val()
                      ) %
                          2 ==
                      0
                    ? 31
                    : 30;
        }
        $(".HMTVE230PresentOrderCareer.ddlDay" + name)
            .children()
            .remove();
        for (var i = 1; i <= j; i++) {
            if (i < 10) {
                value = "0" + i;
            } else {
                value = "" + i;
            }
            $("<option></option>")
                .val(value)
                .text(value)
                .appendTo(".HMTVE230PresentOrderCareer.ddlDay" + name);
        }
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMTVE230PresentOrderCareer.ddlDay" + name);
        $(".HMTVE230PresentOrderCareer.ddlDay" + name).val("");
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE230PresentOrderCareer =
        new HMTVE.HMTVE230PresentOrderCareer();
    o_HMTVE_HMTVE230PresentOrderCareer.load();
});
