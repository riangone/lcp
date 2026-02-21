/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE150PublicityOrderCareer");

HMTVE.HMTVE150PublicityOrderCareer = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE150PublicityOrderCareer";
    me.HMTVE = new HMTVE.HMTVE();
    // jqgrid
    me.grid_id = "#HMTVE150PublicityOrderCareer_grdExView";
    me.pager = "#HMTVE150PublicityOrderCareer_pager";
    me.g_url = me.sys_id + "/" + me.id + "/" + "getgrdExView";
    me.option = {
        rowNum: 9,
        caption: "",
        rownumbers: false,
        multiselect: false,
        autoScroll: true,
        colModel: me.colModel,
        pager: me.pager, //分页容器
        recordpos: "right",
        rowList: [9, 15, 25],
        shrinkToFit: true,
        datatype: "json",
    };

    me.colModel = [
        {
            label: "展示会開催期間",
            name: "HIDUKE",
            index: "HIDUKE",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 90 : 165,
            sortable: false,
        },
        {
            label: "イベント名",
            name: "IVENT_NM",
            index: "IVENT_NM",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 175 : 215,
            formatter: function (celval) {
                if (celval.length <= 34) {
                    return celval;
                }
                var sNewStr = celval.substring(0, 34) + "...";
                return sNewStr;
            },
            sortable: false,
        },
        {
            label: "品名",
            name: "HINMEI",
            index: "HINMEI",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 125 : 215,
            sortable: false,
        },
        {
            label: "単価",
            name: "TANKA",
            index: "TANKA",
            align: "right",
            search: false,
            width: 50,
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            label: "数量",
            name: "SURYO",
            index: "SURYO",
            align: "right",
            search: false,
            width: 50,
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            label: "金額",
            name: "KINGAKU",
            index: "KINGAKU",
            align: "right",
            search: false,
            width: 80,
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
        id: ".HMTVE150PublicityOrderCareer.btnShow",
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
    // 表示ボタン
    $(".HMTVE150PublicityOrderCareer.btnShow").click(function () {
        me.btnShow_Click();
    });
    //年月
    $(".HMTVE150PublicityOrderCareer.ddlYear").change(function () {
        me.ddlYear_SelectedIndexChanged();
    });
    //年月
    $(".HMTVE150PublicityOrderCareer.ddlMonth").change(function () {
        me.ddlYear_SelectedIndexChanged();
    });
    //年月
    $(".HMTVE150PublicityOrderCareer.ddlMonth2").change(function () {
        me.ddlYear_SelectedIndexChanged();
    });
    //年月
    $(".HMTVE150PublicityOrderCareer.ddlYear2").change(function () {
        me.ddlYear_SelectedIndexChanged();
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
        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 236);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMTVE150PublicityOrderCareer fieldset").width()
        );
        $(me.grid_id).jqGrid("bindKeys");
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
        $(".HMTVE150PublicityOrderCareer.grdExView").hide();
        var url = me.sys_id + "/" + me.id + "/" + "getYM";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "展示会が設定されていません。先に展示会データ登録を行ってください！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            if (result["data"] && result["data"].length > 0) {
                //コンボリストに日付を設定する
                me.ddl_YMSet(result["data"][0]);
                if (result["data"][0]["BUSYO_RYKNM"]) {
                    $(".HMTVE150PublicityOrderCareer.lblShopNa").val(
                        result["data"][0]["BUSYO_RYKNM"]
                    );
                }
                $(".HMTVE150PublicityOrderCareer.ddlYear").trigger("focus");
            }
        };
        me.ajax.send(url, "", 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：表示ボタンのイベント
	 '関 数 名：btnShow_Click
	 '戻 り 値：なし
	 '処理説明：入力チェックして、データの取得して、表示する
	 '**********************************************************************
	 */
    me.btnShow_Click = function () {
        if (
            !$(".HMTVE150PublicityOrderCareer.ddlYear").val() ||
            !$(".HMTVE150PublicityOrderCareer.ddlMonth").val() ||
            !$(".HMTVE150PublicityOrderCareer.ddlYear2").val() ||
            !$(".HMTVE150PublicityOrderCareer.ddlMonth2").val()
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE150PublicityOrderCareer.ddlYear");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催年月を選択してください！"
            );
            return;
        }
        if (
            $(".HMTVE150PublicityOrderCareer.ddlYear").val() != "" &&
            $(".HMTVE150PublicityOrderCareer.ddlYear2").val() != ""
        ) {
            if (
                $(".HMTVE150PublicityOrderCareer.ddlYear").val() +
                    $(".HMTVE150PublicityOrderCareer.ddlMonth").val() >
                $(".HMTVE150PublicityOrderCareer.ddlYear2").val() +
                    $(".HMTVE150PublicityOrderCareer.ddlMonth2").val()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE150PublicityOrderCareer.ddlYear"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "展示会開催年月の大小関係が不正です！"
                );
                return;
            }
        }
        var arr = {
            ddlYearStart: $(".HMTVE150PublicityOrderCareer.ddlYear").val(),
            ddlMonthStart: $(".HMTVE150PublicityOrderCareer.ddlMonth").val(),
            ddlYearEnd: $(".HMTVE150PublicityOrderCareer.ddlYear2").val(),
            ddlMonthEnd: $(".HMTVE150PublicityOrderCareer.ddlMonth2").val(),
        };

        var data = {
            request: arr,
        };

        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMTVE150PublicityOrderCareer.grdExView").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var objDR = $(me.grid_id).jqGrid("getRowData");
            if (objDR.length == 0) {
                //データがありません。
                me.clsComFnc.FncMsgBox("W9999", "データがありません。");
            } else {
                $(".HMTVE150PublicityOrderCareer.grdExView").show();
                if (result["page"] == "1") {
                    //１行目を選択状態にする
                    $(me.grid_id).jqGrid("setSelection", 0);
                } else {
                    //ページをめくる後,１行目を選択状態にする
                    var selRow =
                        $(".ui-pg-selbox").val() * (result["page"] - 1);
                    $(me.grid_id).jqGrid("setSelection", selRow);
                }
                // $(me.grid_id).jqGrid('setSelection', 0);
                // フォーカスの設定
                $(me.grid_id).trigger("focus");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun, 1);
    };
    /*
	 '**********************************************************************
	 '処 理 名：日付コンボリストの設定
	 '関 数 名：ddl_YMSet
	 '引 数 １：objdr
	 '戻 り 値：なし
	 '処理説明：日付コンボリストの設定します
	 '**********************************************************************
	 */
    me.ddl_YMSet = function (date) {
        //対象年月に初期値をセットする
        //システム日付を取得する
        var now = new Date(date["getNowDate"]);
        var sysYear = now.getFullYear();
        var sysMonth = now.getMonth();
        sysMonth += 1;
        //年のデフォルトは空白を設定する
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE150PublicityOrderCareer.ddlYear2");
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE150PublicityOrderCareer.ddlYear");
        //展示会開催期間(年)FROMと展示会開催期間(年)TOにセットする
        for (
            var i = date["IVENTMIN"].substring(0, 4);
            i <= date["IVENTMAX"].substring(0, 4);
            i++
        ) {
            $("<option></option>")
                .val(i)
                .text(i)
                .prependTo(".HMTVE150PublicityOrderCareer.ddlYear");
            $("<option></option>")
                .val(i)
                .text(i)
                .prependTo(".HMTVE150PublicityOrderCareer.ddlYear2");
        }
        //月のデフォルトは空白を設定する
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE150PublicityOrderCareer.ddlMonth2");
        $("<option></option>")
            .val("")
            .text("")
            .prependTo(".HMTVE150PublicityOrderCareer.ddlMonth");
        //月のデフォルトは1～12を設定する
        for (var index = 12; index >= 1; index--) {
            value = "" + index;
            if (index < 10) {
                value = "0" + index;
            }
            $("<option></option>")
                .val(value)
                .text(value)
                .prependTo(".HMTVE150PublicityOrderCareer.ddlMonth2");
            $("<option></option>")
                .val(value)
                .text(value)
                .prependTo(".HMTVE150PublicityOrderCareer.ddlMonth");
        }
        var strSelectYearF = "";
        var strSelectMonthF = "";
        var strSelectYearT = "";
        var strSelectMonthT = "";
        if (
            sysYear <= date["IVENTMAX"].substring(0, 4) &&
            sysYear >= date["IVENTMIN"].substring(0, 4)
        ) {
            strSelectYearF = sysYear;
            strSelectMonthF = sysMonth > 9 ? sysMonth : "0" + sysMonth;

            var strDateT = new Date(date["getNowDate"]);
            strDateT.setMonth(sysMonth + 1);
            if (strDateT.getFullYear() <= date["IVENTMAX"].substring(0, 4)) {
                strSelectYearT = strDateT.getFullYear();
                strSelectMonthT =
                    strDateT.getMonth() > 9
                        ? strDateT.getMonth()
                        : "0" + strDateT.getMonth();
            } else {
                strSelectYearT = strSelectYearF;
                strSelectMonthT = strSelectMonthF;
            }
        } else if (sysYear < date["IVENTMIN"].substring(0, 4)) {
            strSelectYearF = date["IVENTMIN"].substring(0, 4);
            strSelectMonthF = date["IVENTMIN"].substring(4, 6);
            strSelectYearT = strSelectYearF;
            strSelectMonthT = strSelectMonthF;
        } else if (sysYear > date["IVENTMAX"].substring(0, 4)) {
            strSelectYearF = date["IVENTMAX"].substring(0, 4);
            strSelectMonthF = date["IVENTMAX"].substring(4, 6);
            strSelectYearT = strSelectYearF;
            strSelectMonthT = strSelectMonthF;
        }
        $(".HMTVE150PublicityOrderCareer.ddlYear").val(strSelectYearF);
        $(".HMTVE150PublicityOrderCareer.ddlMonth").val(strSelectMonthF);
        $(".HMTVE150PublicityOrderCareer.ddlYear2").val(strSelectYearT);
        $(".HMTVE150PublicityOrderCareer.ddlMonth2").val(strSelectMonthT);
    };
    /*
	 '**********************************************************************
	 '処 理 名：コンボリストのインデックスの変換処理
	 '関 数 名：ddlYear_SelectedIndexChanged
	 '戻 り 値：なし
	 '処理説明：コンボリストのインデックスの変換処理
	 '**********************************************************************
	 */
    me.ddlYear_SelectedIndexChanged = function () {
        $(".HMTVE150PublicityOrderCareer.grdExView").hide();
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE150PublicityOrderCareer =
        new HMTVE.HMTVE150PublicityOrderCareer();
    o_HMTVE_HMTVE150PublicityOrderCareer.load();
    o_HMTVE_HMTVE.HMTVE150PublicityOrderCareer =
        o_HMTVE_HMTVE150PublicityOrderCareer;
});
