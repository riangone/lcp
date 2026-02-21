/**
 * 説明：
 *
 * @author WANGYING,LIQIUSHUANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmUxJokenToroku");

APPM.FrmUxJokenToroku = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==============================
    // = 宣言 start =
    // ==============================

    // ========== 変数 start ==========

    me.id = "FrmUxJokenToroku";
    me.sys_id = "APPM";
    me.Mode = o_APPM_APPM.FrmUxJokenIchiranSansho.Mode;
    me.UxId = o_APPM_APPM.FrmUxJokenIchiranSansho.UxId;
    me.curDataUpdDate = "";
    me.result = 0;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmUxJokenToroku.btnSet",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.btnToroku",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.btnCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.displayDateFrom",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.displayDateTo",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.loginYear",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.expirationDateFrom",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.expirationDateTo",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.inspectionDate",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.vehicleInspectionDate",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.vehicleInspectionResultDateFrom",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmUxJokenToroku.vehicleInspectionResultDateTo",
        type: "datepicker",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==============================
    // = 宣言 end =
    // ==============================

    // ========== イベント start ==========
    //全件送付チェッククリック
    $(".FrmUxJokenToroku.allExpress").click(function () {
        me.fncallExpress();
    });
    //登録ボタンクリック
    $(".FrmUxJokenToroku.btnToroku").click(function () {
        me.btnToroku();
    });
    //キャンセルボタンクリック
    $(".FrmUxJokenToroku.btnCancel").click(function () {
        me.btnCancel();
    });
    //初度登録年月
    $(".FrmUxJokenToroku.loginYear").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmUxJokenToroku.loginYear")) == false &&
            $(".FrmUxJokenToroku.loginYear").val() != ""
        ) {
            $(".FrmUxJokenToroku.loginYear").val("");
            $(".FrmUxJokenToroku.loginYear").trigger("focus");
        }
    });
    //点検年月
    $(".FrmUxJokenToroku.inspectionDate").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmUxJokenToroku.inspectionDate")) ==
                false &&
            $(".FrmUxJokenToroku.inspectionDate").val() != ""
        ) {
            $(".FrmUxJokenToroku.inspectionDate").val("");
            $(".FrmUxJokenToroku.inspectionDate").trigger("focus");
        }
    });
    //車検年月
    $(".FrmUxJokenToroku.vehicleInspectionDate").on("blur", function () {
        if (
            clsComFnc.CheckDate3(
                $(".FrmUxJokenToroku.vehicleInspectionDate")
            ) == false &&
            $(".FrmUxJokenToroku.vehicleInspectionDate").val() != ""
        ) {
            $(".FrmUxJokenToroku.vehicleInspectionDate").val("");
            $(".FrmUxJokenToroku.vehicleInspectionDate").trigger("focus");
        }
    });
    //設定ボタンクリック
    $(".FrmUxJokenToroku.btnSet").click(function () {
        var dateFrom = $(".FrmUxJokenToroku.displayDateFrom").val();
        var dateTo = $(".FrmUxJokenToroku.displayDateTo").val();
        me.searchList(dateFrom, dateTo, "");
    });

    // ========== イベント end ==========

    // ========== 関数 start ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();
        $(".FrmUxJokenToroku.displayDateFrom").trigger("focus");
        if (me.Mode != 1) {
            //DB検索処理を実行する
            var url = me.sys_id + "/" + me.id + "/" + "fncGetInformation";
            var arr = {
                id: me.UxId,
            };
            var data = {
                request: arr,
            };
            ajax.receive = function (result) {
                result = $.parseJSON(result);

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                } else if (result["row"] <= 0) {
                    clsComFnc.FncMsgBox("W9999", "該当データがありません。");
                    return;
                } else if (result["row"] == 1) {
                    me.curDataUpdDate = result["data"][0]["UPD_DATE"];
                    if (result["message"]["row"] > 0) {
                        $(".FrmUxJokenToroku.txtMesseJi").val(
                            result["data"][0]["MESSEJI_ID"] +
                                ":" +
                                result["message"]["data"][0]["TAITORU"]
                        );
                    } else {
                        $(".FrmUxJokenToroku.txtMesseJi").val(
                            result["data"][0]["MESSEJI_ID"]
                        );
                    }

                    var fDisplayDateFrom =
                        result["data"][0]["HYOJI_ST_YMD"].substring(0, 4) +
                        "/" +
                        result["data"][0]["HYOJI_ST_YMD"].substring(4, 6) +
                        "/" +
                        result["data"][0]["HYOJI_ST_YMD"].substring(6, 8);
                    var fDisplayDateTo =
                        result["data"][0]["HYOJI_ED_YMD"].substring(0, 4) +
                        "/" +
                        result["data"][0]["HYOJI_ED_YMD"].substring(4, 6) +
                        "/" +
                        result["data"][0]["HYOJI_ED_YMD"].substring(6, 14);
                    $(".FrmUxJokenToroku.displayDateFrom").val(
                        fDisplayDateFrom
                    );
                    $(".FrmUxJokenToroku.displayDateTo").val(fDisplayDateTo);

                    me.searchList(fDisplayDateFrom, fDisplayDateTo, result);
                }
            };
            ajax.send(url, data, 0);
        }

        //画面項目の利用可否を切替&ボタン表示を切替
        //0：参照、1：新規登録、2：変更、3：削除
        if (me.Mode == 0) {
            $(".FrmUxJokenToroku.btnToroku").button("disable");
            $(".FrmUxJokenToroku.btnSet").button("disable");
            $(".FrmUxJokenToroku.showDateBlock").block({
                overlayCSS: { opacity: 0 },
            });
            $(".FrmUxJokenToroku.DIVBLOCK1").block({
                overlayCSS: { opacity: 0 },
            });
            $(".FrmUxJokenToroku.DIVBLOCK").block({
                overlayCSS: { opacity: 0 },
            });
            $(".FrmUxJokenToroku.showDateBlock")
                .find("input")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("select")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("input")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK1")
                .find("input")
                .attr("disabled", "disabled");
        }
        if (me.Mode == 1) {
            $(".FrmUxJokenToroku.form").css("visibility", "hidden");
            $(".FrmUxJokenToroku.btnToroku").button("disable");
        }
        if (me.Mode == 2) {
            $(".FrmUxJokenToroku.showDateBlock")
                .find("input")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.btnSet").button("disable");
            $(".FrmUxJokenToroku.showDateBlock").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            $(".FrmUxJokenToroku.btnToroku").text("更新");
        }
        if (me.Mode == 3) {
            $(".FrmUxJokenToroku.btnToroku").text("削除");
            $(".FrmUxJokenToroku.DIVBLOCK1").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            $(".FrmUxJokenToroku.DIVBLOCK").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            $(".FrmUxJokenToroku.showDateBlock")
                .find("input")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.btnSet").button("disable");
            $(".FrmUxJokenToroku.showDateBlock").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("select")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("input")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK1")
                .find("input")
                .attr("disabled", "disabled");
        }
    };

    me.searchList = function (dateFrom, dateTo, searchModel) {
        if (me.Mode == 1) {
            if (showDateCheck() == false) {
                return;
            }
        }

        dateFrom =
            dateFrom.substring(0, 4) +
            dateFrom.substring(5, 7) +
            dateFrom.substring(8, 10);
        dateTo =
            dateTo.substring(0, 4) +
            dateTo.substring(5, 7) +
            dateTo.substring(8, 10);
        //入力されたIDに該当するデータを入力候補として一覧表示する
        var url = me.sys_id + "/" + me.id + "/" + "FncAutoComplete";
        var arr = {
            dateFrom: dateFrom,
            dateTo: dateTo,
        };
        var data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                availableTags = new Array();
                for (i = 0; i < result["row"]; i++) {
                    availableTags.push({
                        label:
                            "" +
                            result["data"][i]["MESSEJI_ID"] +
                            ":" +
                            result["data"][i]["TAITORU"] +
                            "",
                        value:
                            result["data"][i]["MESSEJI_ID"] +
                            ":" +
                            result["data"][i]["TAITORU"],
                    });
                }

                //メッセージのオートコンプリート
                $(".FrmUxJokenToroku.txtMesseJi").autocomplete({
                    source: availableTags,
                });
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            var url = me.sys_id + "/" + me.id + "/" + "fncGetTCodeData";
            var arr = {
                dateFrom: dateFrom,
                dateTo: dateTo,
            };
            var data = {
                request: arr,
            };
            ajax.receive = function (result) {
                result = $.parseJSON(result);
                if (result["result"] == true) {
                    if (me.Mode == 1) {
                        $(".FrmUxJokenToroku.form").css(
                            "visibility",
                            "visible"
                        );
                        $(".FrmUxJokenToroku.btnToroku").button("enable");
                        $(".FrmUxJokenToroku.showDateBlock")
                            .find("input")
                            .attr("disabled", "disabled");
                        $(".FrmUxJokenToroku.btnSet").button("disable");
                        $(".FrmUxJokenToroku.showDateBlock").block({
                            overlayCSS: { opacity: 0 },
                        });
                    }
                    //・性別
                    var strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["gender"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["gender"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["gender"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.gender").html(strSelect);
                    //・カテゴリ
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["category"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["category"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["category"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.category").html(strSelect);
                    //・年代
                    var strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["year"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["year"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["year"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.eraFrom").html(strSelect);
                    $(".FrmUxJokenToroku.eraTo").html(strSelect);
                    //・メーカー名
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["manufacture"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["manufacture"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["manufacture"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.manufacture").html(strSelect);
                    //・固定化区分
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["classification"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["classification"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["classification"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.classification").html(strSelect);
                    //・パックdeメンテ現在加入 ・（DZM）延長保証現在加入 ・ボディーコーティング現在加入
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["maintenance"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["maintenance"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["maintenance"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.packageMaintenance").html(strSelect);
                    $(".FrmUxJokenToroku.masterMaintenance").html(strSelect);
                    $(".FrmUxJokenToroku.bodyCoating").html(strSelect);
                    //・点検
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["tenken"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["tenken"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["tenken"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.inspection").html(strSelect);
                    //・車検
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["cartenken"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["cartenken"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["cartenken"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.vehicleInspection").html(strSelect);
                    //・点検ステータス ・車検ステータス
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["inspection"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["inspection"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["inspection"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.inspectionStatus").html(strSelect);
                    $(".FrmUxJokenToroku.vehicleInspectionStatus").html(
                        strSelect
                    );
                    //・車点検ＤＭ発信結果タイプ名称
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (
                        var i = 0;
                        i < result["vehicleInspection"]["row"];
                        i++
                    ) {
                        strSelect +=
                            "<option value='" +
                            result["vehicleInspection"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "'>" +
                            result["vehicleInspection"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.vehicleInspectionName").html(
                        strSelect
                    );
                    //・管理拠点 ・サービス拠点
                    var strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["place"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["place"]["data"][i]["BUSYO_CD"] +
                            "'>" +
                            result["place"]["data"][i]["BUSYO_RYKNM"] +
                            "</option>";
                    }
                    $(".FrmUxJokenToroku.management").html(strSelect);
                    $(".FrmUxJokenToroku.serviceManagement").html(strSelect);
                    if (searchModel != "") {
                        var fDisplayTimeFrom =
                            searchModel["data"][0]["HYOJI_ST_HM"].substring(
                                0,
                                2
                            ) +
                            ":" +
                            searchModel["data"][0]["HYOJI_ST_HM"].substring(
                                2,
                                4
                            );
                        var fDisplayTimeTo =
                            searchModel["data"][0]["HYOJI_ED_HM"].substring(
                                0,
                                2
                            ) +
                            ":" +
                            searchModel["data"][0]["HYOJI_ED_HM"].substring(
                                2,
                                4
                            );
                        $(".FrmUxJokenToroku.displayTimeFrom").val(
                            fDisplayTimeFrom
                        );
                        $(".FrmUxJokenToroku.displayTimeTo").val(
                            fDisplayTimeTo
                        );
                        if (searchModel["data"][0]["ZENKENSOFU_FLG"] == "01") {
                            $(".FrmUxJokenToroku.allExpress").prop(
                                "checked",
                                true
                            );
                            $(".FrmUxJokenToroku.DIVBLOCK").block({
                                overlayCSS: {
                                    opacity: 0,
                                },
                            });
                            $(".FrmUxJokenToroku.DIVBLOCK")
                                .find("select")
                                .attr("disabled", "disabled");
                            $(".FrmUxJokenToroku.DIVBLOCK")
                                .find("input")
                                .attr("disabled", "disabled");
                        } else {
                            $(".FrmUxJokenToroku.gender").val(
                                searchModel["data"][0]["SEIBETSU_KBN"]
                            );
                            $(".FrmUxJokenToroku.category").val(
                                searchModel["data"][0]["KATEGORI"]
                            );
                            $(".FrmUxJokenToroku.eraFrom").val(
                                searchModel["data"][0]["NENDAI_FROM"]
                            );
                            $(".FrmUxJokenToroku.eraTo").val(
                                searchModel["data"][0]["NENDAI_TO"]
                            );
                            $(".FrmUxJokenToroku.birthday").val(
                                searchModel["data"][0]["TANJYO_TUKI"]
                            );
                            $(".FrmUxJokenToroku.carName").val(
                                searchModel["data"][0]["SHASHU"]
                            );
                            $(".FrmUxJokenToroku.manufacture").val(
                                searchModel["data"][0]["MAKER_CD"]
                            );
                            $(".FrmUxJokenToroku.classification").val(
                                searchModel["data"][0]["KOTEIKA_KBN"]
                            );
                            $(".FrmUxJokenToroku.management").val(
                                searchModel["data"][0]["KANRI_CHIMU_CD"]
                            );
                            $(".FrmUxJokenToroku.serviceManagement").val(
                                searchModel["data"][0]["SABISU_CHIMU_CD"]
                            );
                            $(".FrmUxJokenToroku.loginYear").val(
                                searchModel["data"][0]["SHONENDO_TOROKU_YM"]
                            );
                            if (
                                searchModel["data"][0][
                                    "SHAKEN_MANRYO_YMD_FROM"
                                ] != null
                            ) {
                                var fExpirationDateFrom =
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_FROM"
                                    ].substring(0, 4) +
                                    "/" +
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_FROM"
                                    ].substring(4, 6) +
                                    "/" +
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_FROM"
                                    ].substring(6, 8);
                                $(".FrmUxJokenToroku.expirationDateFrom").val(
                                    fExpirationDateFrom
                                );
                            }
                            if (
                                searchModel["data"][0][
                                    "SHAKEN_MANRYO_YMD_TO"
                                ] != null
                            ) {
                                var fExpirationDateTo =
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_TO"
                                    ].substring(0, 4) +
                                    "/" +
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_TO"
                                    ].substring(4, 6) +
                                    "/" +
                                    searchModel["data"][0][
                                        "SHAKEN_MANRYO_YMD_TO"
                                    ].substring(6, 8);
                                $(".FrmUxJokenToroku.expirationDateTo").val(
                                    fExpirationDateTo
                                );
                            }
                            $(".FrmUxJokenToroku.packageMaintenance").val(
                                searchModel["data"][0]["PAKKUDEMENTE_KANYU_FLG"]
                            );
                            $(".FrmUxJokenToroku.masterMaintenance").val(
                                searchModel["data"][0][
                                    "MATSUDAENCHOHOSHO_KANYU_FLG"
                                ]
                            );
                            $(".FrmUxJokenToroku.bodyCoating").val(
                                searchModel["data"][0][
                                    "BODEIKOTEINGU_KANYU_FLG"
                                ]
                            );
                            $(".FrmUxJokenToroku.inspection").val(
                                searchModel["data"][0]["TENKEN1"]
                            );
                            $(".FrmUxJokenToroku.inspectionDate").val(
                                searchModel["data"][0]["TENKEN_YMD"]
                            );
                            $(".FrmUxJokenToroku.inspectionStatus").val(
                                searchModel["data"][0]["TENKEN_SUTETASU"]
                            );
                            $(".FrmUxJokenToroku.vehicleInspection").val(
                                searchModel["data"][0]["SHAKEN9"]
                            );
                            $(".FrmUxJokenToroku.vehicleInspectionDate").val(
                                searchModel["data"][0]["SHAKEN_YMD"]
                            );
                            $(".FrmUxJokenToroku.vehicleInspectionStatus").val(
                                searchModel["data"][0]["SHAKEN_SUTETASU"]
                            );
                            if (
                                searchModel["data"][0][
                                    "DM_HASSHIN_KEKKA_DATE_FROM"
                                ] != null
                            ) {
                                var fVehicleInspectionResultDateFrom =
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_FROM"
                                    ].substring(0, 4) +
                                    "/" +
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_FROM"
                                    ].substring(4, 6) +
                                    "/" +
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_FROM"
                                    ].substring(6, 8);
                                $(
                                    ".FrmUxJokenToroku.vehicleInspectionResultDateFrom"
                                ).val(fVehicleInspectionResultDateFrom);
                            }
                            if (
                                searchModel["data"][0][
                                    "DM_HASSHIN_KEKKA_DATE_TO"
                                ] != null
                            ) {
                                var fVehicleInspectionResultDateTo =
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_TO"
                                    ].substring(0, 4) +
                                    "/" +
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_TO"
                                    ].substring(4, 6) +
                                    "/" +
                                    searchModel["data"][0][
                                        "DM_HASSHIN_KEKKA_DATE_TO"
                                    ].substring(6, 8);
                                $(
                                    ".FrmUxJokenToroku.vehicleInspectionResultDateTo"
                                ).val(fVehicleInspectionResultDateTo);
                            }

                            $(".FrmUxJokenToroku.vehicleInspectionName").val(
                                searchModel["data"][0][
                                    "DM_HASSHIN_KEKKA_MEISHO"
                                ]
                            );
                        }
                    }
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            ajax.send(url, data, 0);
        };
        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：[全件送付]チェッククリック
    //'関 数 名：me.fncallExpress
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.fncallExpress = function () {
        if ($(".FrmUxJokenToroku.allExpress").is(":checked")) {
            $(".FrmUxJokenToroku.DIVBLOCK").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            //初期値
            $(".FrmUxJokenToroku.DIVBLOCK").find("select").val("");
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("select")
                .attr("disabled", "disabled");
            $(".FrmUxJokenToroku.DIVBLOCK").find("input").val("");
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("input")
                .attr("disabled", "disabled");
        } else {
            $(".FrmUxJokenToroku.DIVBLOCK").unblock();
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("select")
                .attr("disabled", false);
            $(".FrmUxJokenToroku.DIVBLOCK")
                .find("input")
                .attr("disabled", false);
        }
    };

    function showDateCheck() {
        //表示期間(自)入力有無チェック
        if ($(".FrmUxJokenToroku.displayDateFrom").val() == "") {
            clsComFnc.FncMsgBox("W9999", "表示期間(自)は必須入力です。");
            return false;
        }
        //表示期間(至)入力有無チェック
        if ($(".FrmUxJokenToroku.displayDateTo").val() == "") {
            clsComFnc.FncMsgBox("W9999", "表示期間(至)は必須入力です。");
            return false;
        }
        //表示期間(自)YYYY/MM/DDの型チェック
        if (
            clsComFnc.CheckDate($(".FrmUxJokenToroku.displayDateFrom")) == false
        ) {
            clsComFnc.FncMsgBox("W0022", "表示期間(自)", "「YYYY/MM/DD」");
            return false;
        }
        //表示期間(至)YYYY/MM/DDの型チェック
        if (
            clsComFnc.CheckDate($(".FrmUxJokenToroku.displayDateTo")) == false
        ) {
            clsComFnc.FncMsgBox("W0022", "表示期間(至)", "「YYYY/MM/DD」");
            return false;
        }
        //表示期間過去日チェック
        var date = new Date();
        var day = date.getDate();
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        month = pad(month.toString(), 2);
        day = pad(day.toString(), 2);
        var today = year + "/" + month + "/" + day;
        if ($(".FrmUxJokenToroku.displayDateFrom").val() < today) {
            clsComFnc.FncMsgBox("W9999", "表示期間が不正です。");
            return false;
        }
        //表示期間日付期間のチェック
        if (
            $(".FrmUxJokenToroku.displayDateFrom").val() >
            $(".FrmUxJokenToroku.displayDateTo").val()
        ) {
            clsComFnc.FncMsgBox(
                "W9999",
                "表示期間（至）は表示期間（自）以降の日付を入力してください。"
            );
            return false;
        }

        return true;
    }
    //'**********************************************************************
    //'処 理 名：[登録]ボタンクリック
    //'関 数 名：me.btnToroku
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnToroku = function () {
        if (me.Mode == 1 || me.Mode == 2) {
            //メッセージ・必須入力チェック
            if ($(".FrmUxJokenToroku.txtMesseJi").val() == "") {
                clsComFnc.FncMsgBox("W9999", "メッセージを入力してください。");
                return;
            }
            //表示時間 (自)入力有無チェック
            if ($(".FrmUxJokenToroku.displayTimeFrom").val() == "") {
                clsComFnc.FncMsgBox("W9999", "表示時間 (自)は必須入力です。");
                return;
            }
            //表示時間(至)入力有無チェック
            if ($(".FrmUxJokenToroku.displayTimeTo").val() == "") {
                clsComFnc.FncMsgBox("W9999", "表示時間(至)は必須入力です。");
                return;
            }
            //表示時間(自)HH24：MIの型チェック
            if (
                !$(".FrmUxJokenToroku.displayTimeFrom")
                    .val()
                    .match("(([01]\\d)|(2[0-3])):[0-5]\\d(:[0-5]\\d)?")
            ) {
                clsComFnc.FncMsgBox("W0022", "表示時間(自)", "「HH24:MI」");
                return;
            }
            //表示時間(至)HH24：MIの型チェック
            if (
                !$(".FrmUxJokenToroku.displayTimeTo")
                    .val()
                    .match("(([01]\\d)|(2[0-3])):[0-5]\\d(:[0-5]\\d)?")
            ) {
                clsComFnc.FncMsgBox("W0022", "表示時間(至)", "「HH24:MI」");
                return;
            }
            //車点検ＤＭ発信結果日時(自)YYYY/MM/DDの型チェック
            if (
                clsComFnc.CheckDate(
                    $(".FrmUxJokenToroku.vehicleInspectionResultDateFrom")
                ) == false &&
                $(".FrmUxJokenToroku.vehicleInspectionResultDateFrom").val() !=
                    ""
            ) {
                clsComFnc.FncMsgBox(
                    "W0022",
                    "車点検ＤＭ発信結果日時(自)",
                    "「YYYY/MM/DD」"
                );
                return;
            }
            //車点検ＤＭ発信結果日時(至)YYYY/MM/DDの型チェック
            else if (
                clsComFnc.CheckDate(
                    $(".FrmUxJokenToroku.vehicleInspectionResultDateTo")
                ) == false &&
                $(".FrmUxJokenToroku.vehicleInspectionResultDateTo").val() != ""
            ) {
                clsComFnc.FncMsgBox(
                    "W0022",
                    "車点検ＤＭ発信結果日時(至)",
                    "「YYYY/MM/DD」"
                );
                return;
            }
            //車点検ＤＭ発信結果日時日付期間のチェック
            else if (
                $(".FrmUxJokenToroku.vehicleInspectionResultDateFrom").val() !=
                    "" &&
                $(".FrmUxJokenToroku.vehicleInspectionResultDateTo").val() !=
                    "" &&
                $(".FrmUxJokenToroku.vehicleInspectionResultDateFrom").val() >
                    $(".FrmUxJokenToroku.vehicleInspectionResultDateTo").val()
            ) {
                clsComFnc.FncMsgBox(
                    "W9999",
                    "車点検ＤＭ発信結果日時（至）は車点検ＤＭ発信結果日時（自）以降の日付を入力してください。"
                );
                return;
            }
            //車検満了日(自)YYYY/MM/DDの型チェック
            else if (
                clsComFnc.CheckDate(
                    $(".FrmUxJokenToroku.expirationDateFrom")
                ) == false &&
                $(".FrmUxJokenToroku.expirationDateFrom").val() != ""
            ) {
                clsComFnc.FncMsgBox(
                    "W0022",
                    "車検満了日(自)",
                    "「YYYY/MM/DD」"
                );
                return;
            }
            //車検満了日(至)YYYY/MM/DDの型チェック
            else if (
                clsComFnc.CheckDate($(".FrmUxJokenToroku.expirationDateTo")) ==
                    false &&
                $(".FrmUxJokenToroku.expirationDateTo").val() != ""
            ) {
                clsComFnc.FncMsgBox(
                    "W0022",
                    "車検満了日(至)",
                    "「YYYY/MM/DD」"
                );
                return;
            }
            //車検満了日日付期間のチェック
            if (
                $(".FrmUxJokenToroku.expirationDateFrom").val() != "" &&
                $(".FrmUxJokenToroku.expirationDateTo").val() != "" &&
                $(".FrmUxJokenToroku.expirationDateFrom").val() >
                    $(".FrmUxJokenToroku.expirationDateTo").val()
            ) {
                clsComFnc.FncMsgBox(
                    "W9999",
                    "車検満了日（至）は車検満了日（自）以降の日付を入力してください。"
                );
                return;
            }

            //表示期間＋表示時間大小チェック
            var from =
                $(".FrmUxJokenToroku.displayDateFrom").val() +
                " " +
                $(".FrmUxJokenToroku.displayTimeFrom").val();
            var to =
                $(".FrmUxJokenToroku.displayDateTo").val() +
                " " +
                $(".FrmUxJokenToroku.displayTimeTo").val();
            if (from > to) {
                clsComFnc.FncMsgBox("W9999", "表示期間が不正です。");
                return;
            }

            var carName = $(".FrmUxJokenToroku.carName").val();
            if (clsComFnc.GetByteCount(carName) > 30) {
                clsComFnc.FncMsgBox("W0003", "車種名");
                return;
            }

            var url = me.sys_id + "/" + me.id + "/" + "FncToroku";
            var displayDateFrom = $(".FrmUxJokenToroku.displayDateFrom").val(); //表示期間開始日
            var displayDateTo = $(".FrmUxJokenToroku.displayDateTo").val(); //表示期間終了日
            displayDateFrom = displayDateFrom.replaceAll("/", "");
            displayDateTo = displayDateTo.replaceAll("/", "");
            var arr = {
                id: $(".FrmUxJokenToroku.txtMesseJi").val().substring(0, 6),
                dateFrom: displayDateFrom,
                dateTo: displayDateTo,
                gender: $(".FrmUxJokenToroku.gender").val(),
                category: $(".FrmUxJokenToroku.category").val(),
                eraFrom: $(".FrmUxJokenToroku.eraFrom").val(),
                eraTo: $(".FrmUxJokenToroku.eraTo").val(),
                birthday: $(".FrmUxJokenToroku.birthday").val(),
                carName: carName,
                manufacture: $(".FrmUxJokenToroku.manufacture").val(),
                classification: $(".FrmUxJokenToroku.classification").val(),
                management: $(".FrmUxJokenToroku.management").val(),
                serviceManagement: $(
                    ".FrmUxJokenToroku.serviceManagement"
                ).val(),
                loginYear: $(".FrmUxJokenToroku.loginYear").val(),
                expirationDateFrom: $(
                    ".FrmUxJokenToroku.expirationDateFrom"
                ).val(),
                expirationDateTo: $(".FrmUxJokenToroku.expirationDateTo").val(),
                packageMaintenance: $(
                    ".FrmUxJokenToroku.packageMaintenance"
                ).val(),
                masterMaintenance: $(
                    ".FrmUxJokenToroku.masterMaintenance"
                ).val(),
                bodyCoating: $(".FrmUxJokenToroku.bodyCoating").val(),
                //点検
                tenken: $(".FrmUxJokenToroku.inspection").val(),
                //点検年月
                tenkenymd: $(".FrmUxJokenToroku.inspectionDate").val(),
                //点検ステータス
                tenkensutetasu: $(".FrmUxJokenToroku.inspectionStatus").val(),
                //車検
                shaken: $(".FrmUxJokenToroku.vehicleInspection").val(),
                //車検年月
                shakenymd: $(".FrmUxJokenToroku.vehicleInspectionDate").val(),
                //車検ステータス
                shakensutetasu: $(
                    ".FrmUxJokenToroku.vehicleInspectionStatus"
                ).val(),
                //車点検ＤＭ発信結果日時
                dmhasshinkekkaDateFrom: $(
                    ".FrmUxJokenToroku.vehicleInspectionResultDateFrom"
                )
                    .val()
                    .replace(/\//g, ""),
                dmhasshinkekkaDateTo: $(
                    ".FrmUxJokenToroku.vehicleInspectionResultDateTo"
                )
                    .val()
                    .replace(/\//g, ""),
                //車点検ＤＭ発信結果タイプ名称
                dmhasshinkekkameisho: $(
                    ".FrmUxJokenToroku.vehicleInspectionName"
                ).val(),
            };
            //条件指定チェック
            if (!$(".FrmUxJokenToroku.allExpress").is(":checked")) {
                var str = "";
                for (key in arr) {
                    if (key != "id" && key != "dateFrom" && key != "dateTo") {
                        str += arr[key];
                    }
                }
                if (str == "") {
                    clsComFnc.FncMsgBox("W9999", "条件を指定してください。");
                    return;
                }
            }
            var data = {
                request: arr,
            };
            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    if (result["checkId"]["row"] == 0) {
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されたメッセージIDは登録されていません。"
                        );
                        return;
                    } else if (result["objectNm"]["row"] == 0) {
                        clsComFnc.FncMsgBox("W9999", "対象件数が０件です。");
                        return;
                    } else {
                        objData = result["objectNm"]["data"];
                        var objNum = result["objectNm"]["row"];
                        //新規登録
                        if (me.Mode == 1) {
                            var saiBan = 0;
                            var urlSaiban =
                                me.sys_id + "/" + me.id + "/" + "fncSaiban";
                            var dataSaiban = {
                                request: {},
                            };
                            ajax.receive = function (result) {
                                result = eval("(" + result + ")");
                                if (result["result"]) {
                                    if (result["row"] > 0) {
                                        saiBan = parseInt(
                                            result["data"][0]["REMBAN"]
                                        );
                                    }
                                    insData(objNum, saiBan, objData);
                                } else {
                                    clsComFnc.FncMsgBox(
                                        "E9999",
                                        result["data"]
                                    );
                                    return;
                                }
                            };
                            ajax.send(urlSaiban, dataSaiban, 0);
                        } else if (me.Mode == 2) {
                            //变更
                            var urlHaita =
                                me.sys_id + "/" + me.id + "/" + "fncHaita";
                            var arrHaita = {
                                uxId: me.UxId,
                            };
                            var dataHaita = {
                                request: arrHaita,
                            };
                            ajax.receive = function (result) {
                                result = eval("(" + result + ")");
                                if (result["result"]) {
                                    if (
                                        me.curDataUpdDate ==
                                        result["data"][0]["UPD_DATE"]
                                    ) {
                                        updData(objNum, objData);
                                    } else {
                                        clsComFnc.FncMsgBox(
                                            "W9999",
                                            "他のユーザによって変更されていま。"
                                        );
                                        return;
                                    }
                                } else {
                                    clsComFnc.FncMsgBox(
                                        "E9999",
                                        result["data"]
                                    );
                                    return;
                                }
                            };
                            ajax.send(urlHaita, dataHaita, 0);
                        }
                    }
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            ajax.send(url, data, 0);
        } else if (me.Mode == 3) {
            var urlHaita = me.sys_id + "/" + me.id + "/" + "fncHaita";
            var arrHaita = {
                uxId: me.UxId,
            };
            var dataHaita = {
                request: arrHaita,
            };
            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    if (me.curDataUpdDate == result["data"][0]["UPD_DATE"]) {
                        delData();
                    } else {
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "他のユーザによって変更されていま。"
                        );
                        return;
                    }
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            ajax.send(urlHaita, dataHaita, 0);
        }
    };

    //'**********************************************************************
    //'処 理 名：データ新規登録
    //'関 数 名：insData
    //'引 数 　：objNum（対象件数）,customerNo（お客様No）,vinvmivds（VIN-WMIVDS）,vinvis（VIN-VIS）,objYM（点検年月）,inspectEndDate（車検満了日）
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function insData(objNm, saiBan, objData) {
        var now = new Date();
        var year = now.getFullYear().toString();
        var month = (now.getMonth() + 1).toString();
        month.length == 1 ? (month = "0" + month) : (month = month);
        var UXId = year + month + pad(saiBan.toString(), 4); //UX条件ID
        var msgId = $(".FrmUxJokenToroku.txtMesseJi").val().substring(0, 6); //メッセージID
        var sofu = "00"; //全件送付フラグ
        if ($(".FrmUxJokenToroku.allExpress").is(":checked")) {
            sofu = "01";
        }
        var gender = $(".FrmUxJokenToroku.gender").val(); //性別区分
        var category = $(".FrmUxJokenToroku.category").val(); //カテゴリ
        var eraFrom = $(".FrmUxJokenToroku.eraFrom").val(); //年代（自）
        var eraTo = $(".FrmUxJokenToroku.eraTo").val(); //年代（至）
        var birthday = $(".FrmUxJokenToroku.birthday").val(); //誕生月
        var carName = $(".FrmUxJokenToroku.carName").val(); //車種
        var manufacture = $(".FrmUxJokenToroku.manufacture").val(); //メーカーコード
        var classification = $(".FrmUxJokenToroku.classification").val(); //固定化区分
        var management = $(".FrmUxJokenToroku.management").val(); //管理チームコード
        var serviceManagement = $(".FrmUxJokenToroku.serviceManagement").val(); //サービスチームコード
        var loginYear = $(".FrmUxJokenToroku.loginYear").val(); //初度登録年月
        var expirationDateFrom = $(
            ".FrmUxJokenToroku.expirationDateFrom"
        ).val(); //車検満了日（自）
        var expirationDateTo = $(".FrmUxJokenToroku.expirationDateTo").val(); //車検満了日（至）
        var packageMaintenance = $(
            ".FrmUxJokenToroku.packageMaintenance"
        ).val(); //パックdeメンテ現在加入フラグ
        var masterMaintenance = $(".FrmUxJokenToroku.masterMaintenance").val(); //（DZM）延長保証現在加入フラグ
        var bodyCoating = $(".FrmUxJokenToroku.bodyCoating").val(); //ボディコーティング現在加入フラグ
        var inspection = $(".FrmUxJokenToroku.inspection").val(); //商品1コード（点検）
        var inspectionDate = $(".FrmUxJokenToroku.inspectionDate").val(); //商品1（年月）
        var inspectionStatus = $(".FrmUxJokenToroku.inspectionStatus").val(); //商品1（ステータス）
        var vehicleInspection = $(".FrmUxJokenToroku.vehicleInspection").val(); //商品9コード（車検）
        var vehicleInspectionDate = $(
            ".FrmUxJokenToroku.vehicleInspectionDate"
        ).val(); //商品9（年月）
        var vehicleInspectionStatus = $(
            ".FrmUxJokenToroku.vehicleInspectionStatus"
        ).val(); //商品9（ステータス）
        var vehicleInspectionResultDateFrom = $(
            ".FrmUxJokenToroku.vehicleInspectionResultDateFrom"
        ).val(); //車点検ＤＭ発信結果日時（自）
        var vehicleInspectionResultDateTo = $(
            ".FrmUxJokenToroku.vehicleInspectionResultDateTo"
        ).val(); //車点検ＤＭ発信結果日時（至）
        var vehicleInspectionName = $(
            ".FrmUxJokenToroku.vehicleInspectionName"
        ).val(); //車点検ＤＭ発信結果タイプ名称
        var displayDateFrom = $(".FrmUxJokenToroku.displayDateFrom").val(); //表示期間開始日
        var displayDateTo = $(".FrmUxJokenToroku.displayDateTo").val(); //表示期間終了日
        var displayTimeFrom = $(".FrmUxJokenToroku.displayTimeFrom").val(); //表示開始時間
        var displayTimeTo = $(".FrmUxJokenToroku.displayTimeTo").val(); //表示終了時間
        expirationDateFrom = expirationDateFrom.replaceAll("/", "");
        expirationDateTo = expirationDateTo.replaceAll("/", "");
        displayDateFrom = displayDateFrom.replaceAll("/", "");
        displayDateTo = displayDateTo.replaceAll("/", "");
        displayTimeFrom = displayTimeFrom.replace(":", "");
        displayTimeTo = displayTimeTo.replace(":", "");
        var arr = {
            saiBan: saiBan,
            uxId: UXId,
            msgId: msgId,
            sofu: sofu,
            objNum: objNm,
            gender: gender,
            category: category,
            eraFrom: eraFrom,
            eraTo: eraTo,
            birthday: birthday,
            carName: carName,
            manufacture: manufacture,
            classification: classification,
            management: management,
            serviceManagement: serviceManagement,
            loginYear: loginYear,
            expirationDateFrom: expirationDateFrom,
            expirationDateTo: expirationDateTo,
            packageMaintenance: packageMaintenance,
            masterMaintenance: masterMaintenance,
            bodyCoating: bodyCoating,
            inspection: inspection,
            inspectionDate: inspectionDate,
            inspectionStatus: inspectionStatus,
            vehicleInspection: vehicleInspection,
            vehicleInspectionDate: vehicleInspectionDate,
            vehicleInspectionStatus: vehicleInspectionStatus,
            vehicleInspectionResultDateFrom: vehicleInspectionResultDateFrom,
            vehicleInspectionResultDateTo: vehicleInspectionResultDateTo,
            vehicleInspectionName: vehicleInspectionName,
            displayDateFrom: displayDateFrom,
            displayDateTo: displayDateTo,
            displayTimeFrom: displayTimeFrom,
            displayTimeTo: displayTimeTo,
            objData: objData,
        };
        var url = me.sys_id + "/" + me.id + "/" + "insData";
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //新規登録完成
                me.result = 1;
                $("#dialogsToroku").dialog("close");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        ajax.send(url, data, 0);
    }

    //'**********************************************************************
    //'処 理 名：データ更新
    //'関 数 名：updData
    //'引 数 　：objNum（対象件数）,customerNo（お客様No）,vinvmivds（VIN-WMIVDS）,vinvis（VIN-VIS）,objYM（点検年月）,inspectEndDate（車検満了日）
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function updData(objNum, objData) {
        var msgId = $(".FrmUxJokenToroku.txtMesseJi").val().substring(0, 6); //メッセージID
        var sofu = "00"; //全件送付フラグ
        if ($(".FrmUxJokenToroku.allExpress").is(":checked")) {
            sofu = "01";
        }
        var gender = $(".FrmUxJokenToroku.gender").val(); //性別区分
        var category = $(".FrmUxJokenToroku.category").val(); //カテゴリ
        var eraFrom = $(".FrmUxJokenToroku.eraFrom").val(); //年代（自）
        var eraTo = $(".FrmUxJokenToroku.eraTo").val(); //年代（至）
        var birthday = $(".FrmUxJokenToroku.birthday").val(); //誕生月
        var carName = $(".FrmUxJokenToroku.carName").val(); //車種
        var manufacture = $(".FrmUxJokenToroku.manufacture").val(); //メーカーコード
        var classification = $(".FrmUxJokenToroku.classification").val(); //固定化区分
        var management = $(".FrmUxJokenToroku.management").val(); //管理チームコード
        var serviceManagement = $(".FrmUxJokenToroku.serviceManagement").val(); //サービスチームコード
        var loginYear = $(".FrmUxJokenToroku.loginYear").val(); //初度登録年月
        var expirationDateFrom = $(
            ".FrmUxJokenToroku.expirationDateFrom"
        ).val(); //車検満了日（自）
        var expirationDateTo = $(".FrmUxJokenToroku.expirationDateTo").val(); //車検満了日（至）
        var packageMaintenance = $(
            ".FrmUxJokenToroku.packageMaintenance"
        ).val(); //パックdeメンテ現在加入フラグ
        var masterMaintenance = $(".FrmUxJokenToroku.masterMaintenance").val(); //（DZM）延長保証現在加入フラグ
        var bodyCoating = $(".FrmUxJokenToroku.bodyCoating").val(); //ボディコーティング現在加入フラグ
        var inspection = $(".FrmUxJokenToroku.inspection").val(); //商品1コード（点検）
        var inspectionDate = $(".FrmUxJokenToroku.inspectionDate").val(); //商品1（年月）
        var inspectionStatus = $(".FrmUxJokenToroku.inspectionStatus").val(); //商品1（ステータス）
        var vehicleInspection = $(".FrmUxJokenToroku.vehicleInspection").val(); //商品9コード（車検）
        var vehicleInspectionDate = $(
            ".FrmUxJokenToroku.vehicleInspectionDate"
        ).val(); //商品9（年月）
        var vehicleInspectionStatus = $(
            ".FrmUxJokenToroku.vehicleInspectionStatus"
        ).val(); //商品9（ステータス）
        var vehicleInspectionResultDateFrom = $(
            ".FrmUxJokenToroku.vehicleInspectionResultDateFrom"
        ).val(); //車点検ＤＭ発信結果日時（自）
        var vehicleInspectionResultDateTo = $(
            ".FrmUxJokenToroku.vehicleInspectionResultDateTo"
        ).val(); //車点検ＤＭ発信結果日時（至）
        var vehicleInspectionName = $(
            ".FrmUxJokenToroku.vehicleInspectionName"
        ).val(); //車点検ＤＭ発信結果タイプ名称
        var displayDateFrom = $(".FrmUxJokenToroku.displayDateFrom").val(); //表示期間開始日
        var displayDateTo = $(".FrmUxJokenToroku.displayDateTo").val(); //表示期間終了日
        var displayTimeFrom = $(".FrmUxJokenToroku.displayTimeFrom").val(); //表示開始時間
        var displayTimeTo = $(".FrmUxJokenToroku.displayTimeTo").val(); //表示終了時間
        expirationDateFrom = expirationDateFrom.replaceAll("/", "");
        expirationDateTo = expirationDateTo.replaceAll("/", "");
        displayDateFrom = displayDateFrom.replaceAll("/", "");
        displayDateTo = displayDateTo.replaceAll("/", "");
        displayTimeFrom = displayTimeFrom.replace(":", "");
        displayTimeTo = displayTimeTo.replace(":", "");
        var arr = {
            uxId: me.UxId,
            msgId: msgId,
            sofu: sofu,
            objNum: objNum,
            gender: gender,
            category: category,
            eraFrom: eraFrom,
            eraTo: eraTo,
            birthday: birthday,
            carName: carName,
            manufacture: manufacture,
            classification: classification,
            management: management,
            serviceManagement: serviceManagement,
            loginYear: loginYear,
            expirationDateFrom: expirationDateFrom,
            expirationDateTo: expirationDateTo,
            packageMaintenance: packageMaintenance,
            masterMaintenance: masterMaintenance,
            bodyCoating: bodyCoating,
            inspection: inspection,
            inspectionDate: inspectionDate,
            inspectionStatus: inspectionStatus,
            vehicleInspection: vehicleInspection,
            vehicleInspectionDate: vehicleInspectionDate,
            vehicleInspectionStatus: vehicleInspectionStatus,
            vehicleInspectionResultDateFrom: vehicleInspectionResultDateFrom,
            vehicleInspectionResultDateTo: vehicleInspectionResultDateTo,
            vehicleInspectionName: vehicleInspectionName,
            displayDateFrom: displayDateFrom,
            displayDateTo: displayDateTo,
            displayTimeFrom: displayTimeFrom,
            displayTimeTo: displayTimeTo,
            objData: objData,
        };
        var url = me.sys_id + "/" + me.id + "/" + "updData";
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //更新
                me.result = 1;
                $("#dialogsToroku").dialog("close");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        ajax.send(url, data, 0);
    }

    //'**********************************************************************
    //'処 理 名：データ削除
    //'関 数 名：delData
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function delData() {
        var url = me.sys_id + "/" + me.id + "/" + "fncDelData";
        var arr = {
            uxId: me.UxId,
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //削除
                me.result = 1;
                $("#dialogsToroku").dialog("close");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    }

    //'**********************************************************************
    //'処 理 名：補足結果桁
    //'関 数 名：pad
    //'引 数 　：num  文字列
    //'引 数 　：n  桁
    //'戻 り 値：補足結果文字列
    //'処理説明：
    //'**********************************************************************
    function pad(num, n) {
        return (
            Array(n > num.length ? n - ("" + num).length + 1 : 0).join(0) + num
        );
    }
    //'**********************************************************************
    //'処 理 名：[キャンセル]ボタンクリック
    //'関 数 名：me.btnCancel
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnCancel = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.FncCancelConfirm;
        clsComFnc.FncMsgBox("QY999", "キャンセルします。よろしいですか？");
    };

    //'**********************************************************************
    //'処 理 名：[キャンセル(YES)]ボタンクリック
    //'関 数 名：me.FncCancelConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncCancelConfirm = function () {
        $("#dialogsToroku").dialog("close");
    };

    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_APPM_FrmUxJokenToroku = new APPM.FrmUxJokenToroku();
    o_APPM_FrmUxJokenToroku.load();
    o_APPM_APPM.FrmUxJokenToroku = o_APPM_FrmUxJokenToroku;
});
