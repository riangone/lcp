/**
 * 説明：
 *
 *
 * @author YINHUAIYU
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmOshiraseJokenToroku");

APPM.FrmOshiraseJokenToroku = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==============================
    // = 宣言 start =
    // ==============================

    // ========== 変数 start ==========

    me.id = "FrmOshiraseJokenToroku";
    me.sys_id = "APPM";
    me.Mode = o_APPM_APPM.FrmOshiraseJokenIchiranSansho.Mode;
    me.oshiraseId = o_APPM_APPM.FrmOshiraseJokenIchiranSansho.oshiraseId;
    me.upddt = "";
    me.backflg = "";
    me.result = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //設定
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.btnSet",
        type: "button",
        handle: "",
    });
    //登録
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.btnToroku",
        type: "button",
        handle: "",
    });
    //キャンセル
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.btnCancel",
        type: "button",
        handle: "",
    });
    //表示日
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.hyojiymd",
        type: "datepicker",
        handle: "",
    });
    //初年度登録年月
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.shonendotorokuym",
        type: "datepicker3",
        handle: "",
    });
    //車検満了日From
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.shakenmanryoFrom",
        type: "datepicker",
        handle: "",
    });
    //車検満了日To
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.shakenmanryoTo",
        type: "datepicker",
        handle: "",
    });
    //点検年月
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.tenkenymd",
        type: "datepicker3",
        handle: "",
    });
    //車検年月
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.shakenymd",
        type: "datepicker3",
        handle: "",
    });
    //車点検ＤＭ発信結果日時From
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom",
        type: "datepicker",
        handle: "",
    });
    //車点検ＤＭ発信結果日時To
    me.controls.push({
        id: ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo",
        type: "datepicker",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==============================
    // = 宣言 end =
    // ==============================

    // ========== イベント start ==========
    //設定ボタンクリック
    $(".FrmOshiraseJokenToroku.btnSet").click(function () {
        me.btnSet();
    });
    //全件送付チェッククリック
    $(".FrmOshiraseJokenToroku.zenkensofu").click(function () {
        me.fncallExpress();
    });
    //登録ボタンクリック
    $(".FrmOshiraseJokenToroku.btnToroku").click(function () {
        me.btnToroku();
    });
    //キャンセルボタンクリック
    $(".FrmOshiraseJokenToroku.btnCancel").click(function () {
        me.btnCancel();
    });
    //初年度登録年月
    $(".FrmOshiraseJokenToroku.shonendotorokuym").on("blur", function () {
        if (
            $(".FrmOshiraseJokenToroku.shonendotorokuym").val() != "" &&
            $(".FrmOshiraseJokenToroku.shonendotorokuym").val() != null
        ) {
            if (
                clsComFnc.CheckDate3(
                    $(".FrmOshiraseJokenToroku.shonendotorokuym")
                ) == false
            ) {
                $(".FrmOshiraseJokenToroku.shonendotorokuym").val("");
                $(".FrmOshiraseJokenToroku.shonendotorokuym").trigger("focus");
            }
        }
    });
    //点検年月
    $(".FrmOshiraseJokenToroku.tenkenymd").on("blur", function () {
        if (
            $(".FrmOshiraseJokenToroku.tenkenymd").val() != "" &&
            $(".FrmOshiraseJokenToroku.tenkenymd").val() != null
        ) {
            if (
                clsComFnc.CheckDate3($(".FrmOshiraseJokenToroku.tenkenymd")) ==
                false
            ) {
                $(".FrmOshiraseJokenToroku.tenkenymd").val("");
                $(".FrmOshiraseJokenToroku.tenkenymd").trigger("focus");
            }
        }
    });
    //車検年月
    $(".FrmOshiraseJokenToroku.shakenymd").on("blur", function () {
        if (
            $(".FrmOshiraseJokenToroku.shakenymd").val() != "" &&
            $(".FrmOshiraseJokenToroku.shakenymd").val() != null
        ) {
            if (
                clsComFnc.CheckDate3($(".FrmOshiraseJokenToroku.shakenymd")) ==
                false
            ) {
                $(".FrmOshiraseJokenToroku.shakenymd").val("");
                $(".FrmOshiraseJokenToroku.shakenymd").trigger("focus");
            }
        }
    });
    // ========== イベント end ==========

    // ========== 関数 start ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    //'**********************************************************************
    //'処 理 名：画面項目の利用可否を切替&ボタン表示を切替
    //'関 数 名：me.load
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：0：参照、1：新規登録、2：変更、3：削除
    //'**********************************************************************
    me.load = function () {
        base_load();

        $(".FrmOshiraseJokenToroku.hyojiymd").trigger("focus");

        //画面項目の利用可否を切替&ボタン表示を切替
        //0：参照、1：新規登録、2：変更、3：削除
        if (me.Mode == 1) {
            $(".FrmOshiraseJokenToroku.form").hide();
            $(".FrmOshiraseJokenToroku.footer").hide();
            $(".FrmOshiraseJokenToroku.btnToroku").button("disable");
        } else {
            //DB検索処理を実行する
            var url = me.sys_id + "/" + me.id + "/" + "fncGetInformation";
            var data = {
                OSHIRASEJOKEN_ID: me.oshiraseId,
            };
            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["row"] <= 0) {
                    $("#FrmOshiraseJokenIchiranSanshodialogs").dialog("close");
                    me.subMsgOutput(-99, "該当データがありません");
                    return;
                }
                if (result["row"] == 1) {
                    //表示日
                    $(".FrmOshiraseJokenToroku.hyojiymd").val(
                        result["data"][0]["HYOJI_YMD"].substring(0, 4) +
                            "/" +
                            result["data"][0]["HYOJI_YMD"].substring(4, 6) +
                            "/" +
                            result["data"][0]["HYOJI_YMD"].substring(6, 8)
                    );

                    me.result = result;

                    me.FrmOshiraseJokenToroku_load();
                }
            };
            ajax.send(url, data, 0);
        }
    };

    me.FrmOshiraseJokenToroku_load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FncAutoComplete";

        var data = {
            //表示日
            hyojiymd: $(".FrmOshiraseJokenToroku.hyojiymd")
                .val()
                .replace(/\//g, ""),
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
                            result["data"][i]["TAITORU"] +
                            "",
                    });
                }

                //メッセージのオートコンプリート
                $(".FrmOshiraseJokenToroku.txtMesseJi").autocomplete({
                    source: availableTags,
                });
            } else {
                me.subMsgOutput(-9, result["data"]);
                return;
            }

            var url = me.sys_id + "/" + me.id + "/" + "fncGetTCodeData";
            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"]) {
                    //・性別
                    var strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["seibetsu"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["seibetsu"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["seibetsu"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.seibetsu").html(strSelect);

                    //・カテゴリ
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["kategori"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["kategori"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["kategori"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.kategori").html(strSelect);

                    //・年代
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["nendai"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["nendai"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["nendai"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.nendaiFrom").html(strSelect);
                    $(".FrmOshiraseJokenToroku.nendaiTo").html(strSelect);

                    //・メーカー名
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["makerNm"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["makerNm"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["makerNm"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.makerNm").html(strSelect);

                    //・固定化区分
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["koteikakbn"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["koteikakbn"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["koteikakbn"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.koteikakbn").html(strSelect);

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
                    $(".FrmOshiraseJokenToroku.pakkudementekanyu").html(
                        strSelect
                    );
                    $(".FrmOshiraseJokenToroku.matsudaenchohoshokanyu").html(
                        strSelect
                    );
                    $(".FrmOshiraseJokenToroku.bodeikoteingukanyu").html(
                        strSelect
                    );

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
                    $(".FrmOshiraseJokenToroku.tenkensutetasu").html(strSelect);
                    $(".FrmOshiraseJokenToroku.shakensutetasu").html(strSelect);

                    //・車点検ＤＭ発信結果タイプ名称
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (
                        var i = 0;
                        i < result["dmhasshinkekkameisho"]["row"];
                        i++
                    ) {
                        strSelect +=
                            "<option value='" +
                            result["dmhasshinkekkameisho"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "'>" +
                            result["dmhasshinkekkameisho"]["data"][i][
                                "NAIBU_CD_MEISHO"
                            ] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.dmhasshinkekkameisho").html(
                        strSelect
                    );

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
                    $(".FrmOshiraseJokenToroku.tenken").html(strSelect);

                    //・車検
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["shaken"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["shaken"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["shaken"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.shaken").html(strSelect);

                    //・管理拠点 ・サービス拠点
                    strSelect = "";
                    strSelect += "<option value=''></option>";
                    for (var i = 0; i < result["place"]["row"]; i++) {
                        strSelect +=
                            "<option value='" +
                            result["place"]["data"][i]["BUSYO_CD"] +
                            "'>" +
                            result["place"]["data"][i]["BUSYO_RYKNM"] +
                            "</option>";
                    }
                    $(".FrmOshiraseJokenToroku.kanrichimu").html(strSelect);
                    $(".FrmOshiraseJokenToroku.sabisuchimu").html(strSelect);

                    if (me.Mode != 1) {
                        me.dataSet();
                    }
                } else {
                    me.subMsgOutput(-9, result["data"]);
                    return;
                }
            };
            ajax.send(url, data, 0);
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：画面の値設定
    //'関 数 名：me.dataSet
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.dataSet = function () {
        var result = me.result;

        //更新日時
        me.upddt = result["data"][0]["UPD_DATE"];
        //メッセージ
        $(".FrmOshiraseJokenToroku.txtMesseJi").val(
            result["data"][0]["MESSEJI_ID"] +
                ":" +
                result["data"][0]["TAITORU"] +
                ""
        );

        //表示時間
        $(".FrmOshiraseJokenToroku.hyojihm").val(
            result["data"][0]["HYOJI_HM"].substring(0, 2) +
                ":" +
                result["data"][0]["HYOJI_HM"].substring(2, 4)
        );
        //性別
        $(".FrmOshiraseJokenToroku.seibetsu").val(
            result["data"][0]["SEIBETSU_KBN"]
        );
        //カテゴリ
        $(".FrmOshiraseJokenToroku.kategori").val(
            result["data"][0]["KATEGORI"]
        );
        //年代
        $(".FrmOshiraseJokenToroku.nendaiFrom").val(
            result["data"][0]["NENDAI_FROM"]
        );
        $(".FrmOshiraseJokenToroku.nendaiTo").val(
            result["data"][0]["NENDAI_TO"]
        );
        //誕生月
        $(".FrmOshiraseJokenToroku.tanjyotuki").val(
            result["data"][0]["TANJYO_TUKI"]
        );
        //車種名
        $(".FrmOshiraseJokenToroku.shashuNm").val(result["data"][0]["SHASHU"]);
        //メーカー名
        $(".FrmOshiraseJokenToroku.makerNm").val(result["data"][0]["MAKER_CD"]);
        //固定化区分
        $(".FrmOshiraseJokenToroku.koteikakbn").val(
            result["data"][0]["KOTEIKA_KBN"]
        );
        //管理拠点
        $(".FrmOshiraseJokenToroku.kanrichimu").val(
            result["data"][0]["KANRI_CHIMU_CD"]
        );
        //サービス管理拠点
        $(".FrmOshiraseJokenToroku.sabisuchimu").val(
            result["data"][0]["SABISU_CHIMU_CD"]
        );
        //初年度登録年月
        $(".FrmOshiraseJokenToroku.shonendotorokuym").val(
            result["data"][0]["SHONENDO_TOROKU_YM"]
        );
        //車検満了日
        if (
            result["data"][0]["SHAKEN_MANRYO_YMD_FROM"] != null &&
            result["data"][0]["SHAKEN_MANRYO_YMD_FROM"] != ""
        ) {
            $(".FrmOshiraseJokenToroku.shakenmanryoFrom").val(
                result["data"][0]["SHAKEN_MANRYO_YMD_FROM"].substring(0, 4) +
                    "/" +
                    result["data"][0]["SHAKEN_MANRYO_YMD_FROM"].substring(
                        4,
                        6
                    ) +
                    "/" +
                    result["data"][0]["SHAKEN_MANRYO_YMD_FROM"].substring(6, 8)
            );
        }
        if (
            result["data"][0]["SHAKEN_MANRYO_YMD_TO"] != null &&
            result["data"][0]["SHAKEN_MANRYO_YMD_TO"] != ""
        ) {
            $(".FrmOshiraseJokenToroku.shakenmanryoTo").val(
                result["data"][0]["SHAKEN_MANRYO_YMD_TO"].substring(0, 4) +
                    "/" +
                    result["data"][0]["SHAKEN_MANRYO_YMD_TO"].substring(4, 6) +
                    "/" +
                    result["data"][0]["SHAKEN_MANRYO_YMD_TO"].substring(6, 8)
            );
        }
        //パックdeメンテ現在加入
        $(".FrmOshiraseJokenToroku.pakkudementekanyu").val(
            result["data"][0]["PAKKUDEMENTE_KANYU_FLG"]
        );
        //（DZM）延長保証現在加入
        $(".FrmOshiraseJokenToroku.matsudaenchohoshokanyu").val(
            result["data"][0]["MATSUDAENCHOHOSHO_KANYU_FLG"]
        );
        //ボディーコーティング現在加入
        $(".FrmOshiraseJokenToroku.bodeikoteingukanyu").val(
            result["data"][0]["BODEIKOTEINGU_KANYU_FLG"]
        );
        //点検
        $(".FrmOshiraseJokenToroku.tenken").val(result["data"][0]["TENKEN1"]);
        //点検年月
        $(".FrmOshiraseJokenToroku.tenkenymd").val(
            result["data"][0]["TENKEN_YMD"]
        );
        //点検ステータス
        $(".FrmOshiraseJokenToroku.tenkensutetasu").val(
            result["data"][0]["TENKEN_SUTETASU"]
        );
        //車検
        $(".FrmOshiraseJokenToroku.shaken").val(result["data"][0]["SHAKEN9"]);
        //車検年月
        $(".FrmOshiraseJokenToroku.shakenymd").val(
            result["data"][0]["SHAKEN_YMD"]
        );
        //車検ステータス
        $(".FrmOshiraseJokenToroku.shakensutetasu").val(
            result["data"][0]["SHAKEN_SUTETASU"]
        );
        //車点検ＤＭ発信結果日時
        if (
            result["data"][0]["DM_HASSHIN_KEKKA_DATE_FROM"] != null &&
            result["data"][0]["DM_HASSHIN_KEKKA_DATE_FROM"] != ""
        ) {
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").val(
                result["data"][0]["DM_HASSHIN_KEKKA_DATE_FROM"].substring(
                    0,
                    4
                ) +
                    "/" +
                    result["data"][0]["DM_HASSHIN_KEKKA_DATE_FROM"].substring(
                        4,
                        6
                    ) +
                    "/" +
                    result["data"][0]["DM_HASSHIN_KEKKA_DATE_FROM"].substring(
                        6,
                        8
                    )
            );
        }
        if (
            result["data"][0]["DM_HASSHIN_KEKKA_DATE_TO"] != null &&
            result["data"][0]["DM_HASSHIN_KEKKA_DATE_TO"] != ""
        ) {
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo").val(
                result["data"][0]["DM_HASSHIN_KEKKA_DATE_TO"].substring(0, 4) +
                    "/" +
                    result["data"][0]["DM_HASSHIN_KEKKA_DATE_TO"].substring(
                        4,
                        6
                    ) +
                    "/" +
                    result["data"][0]["DM_HASSHIN_KEKKA_DATE_TO"].substring(
                        6,
                        8
                    )
            );
        }
        //車点検ＤＭ発信結果タイプ名称
        $(".FrmOshiraseJokenToroku.dmhasshinkekkameisho").val(
            result["data"][0]["DM_HASSHIN_KEKKA_MEISHO"]
        );
        //全件送付
        if (result["data"][0]["ZENKENSOFU_FLG"] == "01") {
            $(".FrmOshiraseJokenToroku.zenkensofu").prop("checked", true);
            $(".FrmOshiraseJokenToroku.table1").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            me.InputClear();
            me.InputDisable(true, "Part");
        }

        if (me.Mode == 0) {
            $(".FrmOshiraseJokenToroku.btnToroku").button("disable");
            $(".FrmOshiraseJokenToroku.form").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            me.InputDisable(true, "all");
        }
        if (me.Mode == 2) {
            //表示日
            $(".FrmOshiraseJokenToroku.hyojiymd").attr("disabled", "disabled");

            $(".FrmOshiraseJokenToroku.hyojiymddiv").block({
                overlayCSS: {
                    opacity: 0,
                },
            });

            //設定ボタン
            $(".FrmOshiraseJokenToroku.btnSet").button("disable");
            $(".FrmOshiraseJokenToroku.btnToroku").text("更新");
        }
        if (me.Mode == 3) {
            $(".FrmOshiraseJokenToroku.btnToroku").text("削除");
            $(".FrmOshiraseJokenToroku.form").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            me.InputDisable(true, "all");
        }
    };

    //'**********************************************************************
    //'処 理 名：[設定]ボタンクリック
    //'関 数 名：me.btnSet
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnSet = function () {
        //入力有無チェック[表示日]
        if ($(".FrmOshiraseJokenToroku.hyojiymd").val() == "") {
            $(".FrmOshiraseJokenToroku.hyojiymd").trigger("focus");
            me.subMsgOutput(-99, "表示日は必須入力です。");
            return;
        }
        //YYYY/MM/DDの型チェック[表示日]
        if (
            clsComFnc.CheckDate($(".FrmOshiraseJokenToroku.hyojiymd")) == false
        ) {
            me.subMsgOutput(
                -22,
                "表示日",
                $(".FrmOshiraseJokenToroku.hyojiymd"),
                "「YYYY/MM/DD」"
            );
            return;
        }
        //表示日未来日チェック
        var date = new Date();
        var day = date.getDate() + 1;
        var month = date.getMonth() + 1;
        var year = date.getFullYear();
        day = day < 10 ? "0" + day : day;
        month = month < 10 ? "0" + month : month;
        var today = year + "/" + month + "/" + day;
        if ($(".FrmOshiraseJokenToroku.hyojiymd").val() < today) {
            $(".FrmOshiraseJokenToroku.hyojiymd").trigger("focus");
            me.subMsgOutput(-99, "表示日は明日以降を指定してください。");
            return;
        }

        $(".FrmOshiraseJokenToroku.form").show();
        $(".FrmOshiraseJokenToroku.footer").show();
        $(".FrmOshiraseJokenToroku.btnToroku").button("enable");

        me.FrmOshiraseJokenToroku_load();

        //表示日
        $(".FrmOshiraseJokenToroku.hyojiymd").attr("disabled", "disabled");

        $(".FrmOshiraseJokenToroku.hyojiymddiv").block({
            overlayCSS: {
                opacity: 0,
            },
        });

        //設定ボタン
        $(".FrmOshiraseJokenToroku.btnSet").button("disable");
    };

    //'**********************************************************************
    //'処 理 名：[全件送付]チェッククリック
    //'関 数 名：me.fncallExpress
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.fncallExpress = function () {
        if ($(".FrmOshiraseJokenToroku.zenkensofu").is(":checked")) {
            $(".FrmOshiraseJokenToroku.table1").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            me.InputClear();
            me.InputDisable(true, "Part");
        } else {
            $(".FrmOshiraseJokenToroku.table1").unblock();
            me.InputDisable(false, "Part");
        }
    };
    //'**********************************************************************
    //'処 理 名：画面データクリア
    //'関 数 名：me.InputClear
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：個人属性,車両属性,車検・点検属性データクリア
    //'**********************************************************************
    me.InputClear = function () {
        //性別
        $(".FrmOshiraseJokenToroku.seibetsu").val("");
        //カテゴリ
        $(".FrmOshiraseJokenToroku.kategori").val("");
        //年代
        $(".FrmOshiraseJokenToroku.nendaiFrom").val("");
        $(".FrmOshiraseJokenToroku.nendaiTo").val("");
        //誕生月
        $(".FrmOshiraseJokenToroku.tanjyotuki").val("");
        //車種名
        $(".FrmOshiraseJokenToroku.shashuNm").val("");
        //メーカー名
        $(".FrmOshiraseJokenToroku.makerNm").val("");
        //固定化区分
        $(".FrmOshiraseJokenToroku.koteikakbn").val("");
        //管理拠点
        $(".FrmOshiraseJokenToroku.kanrichimu").val("");
        //サービス管理拠点
        $(".FrmOshiraseJokenToroku.sabisuchimu").val("");
        //初年度登録年月
        $(".FrmOshiraseJokenToroku.shonendotorokuym").val("");
        //車検満了日
        $(".FrmOshiraseJokenToroku.shakenmanryoFrom").val("");
        $(".FrmOshiraseJokenToroku.shakenmanryoTo").val("");
        //パックdeメンテ現在加入
        $(".FrmOshiraseJokenToroku.pakkudementekanyu").val("");
        //（DZM）延長保証現在加入
        $(".FrmOshiraseJokenToroku.matsudaenchohoshokanyu").val("");
        //ボディーコーティング現在加入
        $(".FrmOshiraseJokenToroku.bodeikoteingukanyu").val("");
        //点検
        $(".FrmOshiraseJokenToroku.tenken").val("");
        //点検年月
        $(".FrmOshiraseJokenToroku.tenkenymd").val("");
        //点検ステータス表示日
        $(".FrmOshiraseJokenToroku.tenkensutetasu").val("");
        //車検
        $(".FrmOshiraseJokenToroku.shaken").val("");
        //車検年月
        $(".FrmOshiraseJokenToroku.shakenymd").val("");
        //車検ステータス
        $(".FrmOshiraseJokenToroku.shakensutetasu").val("");
        //車点検ＤＭ発信結果日時
        $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").val("");
        $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo").val("");
        //車点検ＤＭ発信結果タイプ名称
        $(".FrmOshiraseJokenToroku.dmhasshinkekkameisho").val("");
    };
    //'**********************************************************************
    //'処 理 名：画面のコントロールは可否を使用する
    //'関 数 名：me.InputDisable
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面のコントロールは可否を使用する
    //'**********************************************************************
    me.InputDisable = function (disable, Range) {
        if (disable) {
            if (Range == "all") {
                //表示日
                $(".FrmOshiraseJokenToroku.hyojiymd").attr(
                    "disabled",
                    "disabled"
                );

                $(".FrmOshiraseJokenToroku.hyojiymddiv").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });

                //設定ボタン
                $(".FrmOshiraseJokenToroku.btnSet").button("disable");

                //メッセージ
                $(".FrmOshiraseJokenToroku.txtMesseJi").attr(
                    "disabled",
                    "disabled"
                );

                //全件送付
                $(".FrmOshiraseJokenToroku.zenkensofu").attr(
                    "disabled",
                    "disabled"
                );

                //表示時間
                $(".FrmOshiraseJokenToroku.hyojihm").attr(
                    "disabled",
                    "disabled"
                );
            }

            //性別
            $(".FrmOshiraseJokenToroku.seibetsu").attr("disabled", "disabled");
            //カテゴリ
            $(".FrmOshiraseJokenToroku.kategori").attr("disabled", "disabled");
            //年代
            $(".FrmOshiraseJokenToroku.nendaiFrom").attr(
                "disabled",
                "disabled"
            );
            $(".FrmOshiraseJokenToroku.nendaiTo").attr("disabled", "disabled");
            //誕生月
            $(".FrmOshiraseJokenToroku.tanjyotuki").attr(
                "disabled",
                "disabled"
            );
            //車種名
            $(".FrmOshiraseJokenToroku.shashuNm").attr("disabled", "disabled");
            //メーカー名
            $(".FrmOshiraseJokenToroku.makerNm").attr("disabled", "disabled");
            //固定化区分
            $(".FrmOshiraseJokenToroku.koteikakbn").attr(
                "disabled",
                "disabled"
            );
            //管理拠点
            $(".FrmOshiraseJokenToroku.kanrichimu").attr(
                "disabled",
                "disabled"
            );
            //サービス管理拠点
            $(".FrmOshiraseJokenToroku.sabisuchimu").attr(
                "disabled",
                "disabled"
            );
            //初年度登録年月
            $(".FrmOshiraseJokenToroku.shonendotorokuym").attr(
                "disabled",
                "disabled"
            );
            //車検満了日
            $(".FrmOshiraseJokenToroku.shakenmanryoFrom").attr(
                "disabled",
                "disabled"
            );
            $(".FrmOshiraseJokenToroku.shakenmanryoTo").attr(
                "disabled",
                "disabled"
            );
            //パックdeメンテ現在加入
            $(".FrmOshiraseJokenToroku.pakkudementekanyu").attr(
                "disabled",
                "disabled"
            );
            //（DZM）延長保証現在加入
            $(".FrmOshiraseJokenToroku.matsudaenchohoshokanyu").attr(
                "disabled",
                "disabled"
            );
            //ボディーコーティング現在加入
            $(".FrmOshiraseJokenToroku.bodeikoteingukanyu").attr(
                "disabled",
                "disabled"
            );
            //点検
            $(".FrmOshiraseJokenToroku.tenken").attr("disabled", "disabled");
            //点検年月
            $(".FrmOshiraseJokenToroku.tenkenymd").attr("disabled", "disabled");
            //点検ステータス
            $(".FrmOshiraseJokenToroku.tenkensutetasu").attr(
                "disabled",
                "disabled"
            );
            //車検
            $(".FrmOshiraseJokenToroku.shaken").attr("disabled", "disabled");
            //車検年月
            $(".FrmOshiraseJokenToroku.shakenymd").attr("disabled", "disabled");
            //車検ステータス
            $(".FrmOshiraseJokenToroku.shakensutetasu").attr(
                "disabled",
                "disabled"
            );
            //車点検ＤＭ発信結果日時
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").attr(
                "disabled",
                "disabled"
            );
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo").attr(
                "disabled",
                "disabled"
            );
            //車点検ＤＭ発信結果タイプ名称
            $(".FrmOshiraseJokenToroku.dmhasshinkekkameisho").attr(
                "disabled",
                "disabled"
            );
        } else {
            if (Range == "all") {
                //表示日
                $(".FrmOshiraseJokenToroku.hyojiymd").attr("disabled", false);

                $(".FrmOshiraseJokenToroku.hyojiymddiv").unblock();

                //設定ボタン
                $(".FrmOshiraseJokenToroku.btnSet").button("enable");

                //メッセージ
                $(".FrmOshiraseJokenToroku.txtMesseJi").attr("disabled", false);

                //全件送付
                $(".FrmOshiraseJokenToroku.zenkensofu").attr("disabled", false);

                //表示時間
                $(".FrmOshiraseJokenToroku.hyojihm").attr("disabled", false);
            }

            //性別
            $(".FrmOshiraseJokenToroku.seibetsu").attr("disabled", false);
            //カテゴリ
            $(".FrmOshiraseJokenToroku.kategori").attr("disabled", false);
            //年代
            $(".FrmOshiraseJokenToroku.nendaiFrom").attr("disabled", false);
            $(".FrmOshiraseJokenToroku.nendaiTo").attr("disabled", false);
            //誕生月
            $(".FrmOshiraseJokenToroku.tanjyotuki").attr("disabled", false);
            //車種名
            $(".FrmOshiraseJokenToroku.shashuNm").attr("disabled", false);
            //メーカー名
            $(".FrmOshiraseJokenToroku.makerNm").attr("disabled", false);
            //固定化区分
            $(".FrmOshiraseJokenToroku.koteikakbn").attr("disabled", false);
            //管理拠点
            $(".FrmOshiraseJokenToroku.kanrichimu").attr("disabled", false);
            //サービス管理拠点
            $(".FrmOshiraseJokenToroku.sabisuchimu").attr("disabled", false);
            //初年度登録年月
            $(".FrmOshiraseJokenToroku.shonendotorokuym").attr(
                "disabled",
                false
            );
            //車検満了日
            $(".FrmOshiraseJokenToroku.shakenmanryoFrom").attr(
                "disabled",
                false
            );
            $(".FrmOshiraseJokenToroku.shakenmanryoTo").attr("disabled", false);
            //パックdeメンテ現在加入
            $(".FrmOshiraseJokenToroku.pakkudementekanyu").attr(
                "disabled",
                false
            );
            //（DZM）延長保証現在加入
            $(".FrmOshiraseJokenToroku.matsudaenchohoshokanyu").attr(
                "disabled",
                false
            );
            //ボディーコーティング現在加入
            $(".FrmOshiraseJokenToroku.bodeikoteingukanyu").attr(
                "disabled",
                false
            );
            //点検
            $(".FrmOshiraseJokenToroku.tenken").attr("disabled", false);
            //点検年月
            $(".FrmOshiraseJokenToroku.tenkenymd").attr("disabled", false);
            //点検ステータス
            $(".FrmOshiraseJokenToroku.tenkensutetasu").attr("disabled", false);
            //車検
            $(".FrmOshiraseJokenToroku.shaken").attr("disabled", false);
            //車検年月
            $(".FrmOshiraseJokenToroku.shakenymd").attr("disabled", false);
            //車検ステータス
            $(".FrmOshiraseJokenToroku.shakensutetasu").attr("disabled", false);
            //車点検ＤＭ発信結果日時
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").attr(
                "disabled",
                false
            );
            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo").attr(
                "disabled",
                false
            );
            //車点検ＤＭ発信結果タイプ名称
            $(".FrmOshiraseJokenToroku.dmhasshinkekkameisho").attr(
                "disabled",
                false
            );
        }
    };
    //'**********************************************************************
    //'処 理 名：[登録]ボタンクリック
    //'関 数 名：me.btnToroku
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：お知らせ条件登録
    //'**********************************************************************
    me.btnToroku = function () {
        if (me.Mode == 1 || me.Mode == 2) {
            //メッセージ・必須入力チェック
            if ($(".FrmOshiraseJokenToroku.txtMesseJi").val() == "") {
                me.subMsgOutput(-99, "メッセージは必須入力です。");
                return;
            }
            var osdata = {
                //表示日
                hyojiymd: $(".FrmOshiraseJokenToroku.hyojiymd")
                    .val()
                    .replace(/\//g, ""),
                //表示時間
                hyojihm: $(".FrmOshiraseJokenToroku.hyojihm")
                    .val()
                    .replace(/:/g, ""),
                //性別
                seibetsu: $(".FrmOshiraseJokenToroku.seibetsu").val(),
                //カテゴリ
                kategori: $(".FrmOshiraseJokenToroku.kategori").val(),
                //年代
                nendaiFrom: $(".FrmOshiraseJokenToroku.nendaiFrom").val(),
                nendaiTo: $(".FrmOshiraseJokenToroku.nendaiTo").val(),
                //誕生月
                tanjyotuki: $(".FrmOshiraseJokenToroku.tanjyotuki").val(),
                //車種名
                shashuNm: $(".FrmOshiraseJokenToroku.shashuNm").val(),
                //メーカー名
                makerNm: $(".FrmOshiraseJokenToroku.makerNm").val(),
                //固定化区分
                koteikakbn: $(".FrmOshiraseJokenToroku.koteikakbn").val(),
                //管理拠点
                kanrichimu: $(".FrmOshiraseJokenToroku.kanrichimu").val(),
                //サービス管理拠点
                sabisuchimu: $(".FrmOshiraseJokenToroku.sabisuchimu").val(),
                //初年度登録年月
                shonendotorokuym: $(
                    ".FrmOshiraseJokenToroku.shonendotorokuym"
                ).val(),
                //車検満了日
                shakenmanryoFrom: $(".FrmOshiraseJokenToroku.shakenmanryoFrom")
                    .val()
                    .replace(/\//g, ""),
                shakenmanryoTo: $(".FrmOshiraseJokenToroku.shakenmanryoTo")
                    .val()
                    .replace(/\//g, ""),
                //パックdeメンテ現在加入
                pakkudementekanyu: $(
                    ".FrmOshiraseJokenToroku.pakkudementekanyu"
                ).val(),
                //（DZM）延長保証現在加入
                matsudaenchohoshokanyu: $(
                    ".FrmOshiraseJokenToroku.matsudaenchohoshokanyu"
                ).val(),
                //ボディーコーティング現在加入
                bodeikoteingukanyu: $(
                    ".FrmOshiraseJokenToroku.bodeikoteingukanyu"
                ).val(),
                //点検
                tenken: $(".FrmOshiraseJokenToroku.tenken").val(),
                //点検年月
                tenkenymd: $(".FrmOshiraseJokenToroku.tenkenymd").val(),
                //点検ステータス
                tenkensutetasu: $(
                    ".FrmOshiraseJokenToroku.tenkensutetasu"
                ).val(),
                //車検
                shaken: $(".FrmOshiraseJokenToroku.shaken").val(),
                //車検年月
                shakenymd: $(".FrmOshiraseJokenToroku.shakenymd").val(),
                //車検ステータス
                shakensutetasu: $(
                    ".FrmOshiraseJokenToroku.shakensutetasu"
                ).val(),
                //車点検ＤＭ発信結果日時
                dmhasshinkekkaDateFrom: $(
                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom"
                )
                    .val()
                    .replace(/\//g, ""),
                dmhasshinkekkaDateTo: $(
                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                )
                    .val()
                    .replace(/\//g, ""),
                //車点検ＤＭ発信結果タイプ名称
                dmhasshinkekkameisho: $(
                    ".FrmOshiraseJokenToroku.dmhasshinkekkameisho"
                ).val(),
            };

            //入力有無チェック[表示日]
            if ($(".FrmOshiraseJokenToroku.hyojiymd").val() == "") {
                $(".FrmOshiraseJokenToroku.hyojiymd").trigger("focus");
                me.subMsgOutput(-99, "表示日は必須入力です。");
                return;
            }
            //YYYY/MM/DDの型チェック[表示日]
            if (
                clsComFnc.CheckDate($(".FrmOshiraseJokenToroku.hyojiymd")) ==
                false
            ) {
                me.subMsgOutput(
                    -22,
                    "表示日",
                    $(".FrmOshiraseJokenToroku.hyojiymd"),
                    "「YYYY/MM/DD」"
                );
                return;
            }
            //表示日未来日チェック
            var date = new Date();
            var day = date.getDate() + 1;
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            day = day < 10 ? "0" + day : day;
            month = month < 10 ? "0" + month : month;
            var today = year + "/" + month + "/" + day;
            if ($(".FrmOshiraseJokenToroku.hyojiymd").val() < today) {
                $(".FrmOshiraseJokenToroku.hyojiymd").trigger("focus");
                me.subMsgOutput(-99, "表示日は明日以降を指定してください。");
                return;
            }
            //入力有無チェック[表示時間]
            if ($(".FrmOshiraseJokenToroku.hyojihm").val() == "") {
                $(".FrmOshiraseJokenToroku.hyojihm").trigger("focus");
                me.subMsgOutput(-99, "表示時間は必須入力です。");
                return;
            }
            //YYYY/MM/DDの型チェック[表示時間]
            if (
                !$(".FrmOshiraseJokenToroku.hyojihm")
                    .val()
                    .match("(([01]\\d)|(2[0-3])):[0-5]\\d(:[0-5]\\d)?")
            ) {
                me.subMsgOutput(
                    -22,
                    "表示時間",
                    $(".FrmOshiraseJokenToroku.hyojiymd"),
                    "「HH24:MI」"
                );
                return;
            }

            //全件送付チェックOnのときは入力有無チェックは行わない
            if ($(".FrmOshiraseJokenToroku.zenkensofu").is(":checked")) {
                var zenkensofu = "01";
            } else {
                var zenkensofu = "00";

                var str = "";
                for (key in osdata) {
                    if (key == "hyojiymd" || key == "hyojihm") {
                    } else {
                        str += osdata[key];
                    }
                }
                if (str == "") {
                    me.subMsgOutput(-99, "条件を指定してください。");
                    return;
                }
                //車検満了日チェック
                if (
                    $(".FrmOshiraseJokenToroku.shakenmanryoFrom").val() == "" ||
                    $(".FrmOshiraseJokenToroku.shakenmanryoFrom").val() == null
                ) {
                    if (
                        $(".FrmOshiraseJokenToroku.shakenmanryoTo").val() ==
                            "" ||
                        $(".FrmOshiraseJokenToroku.shakenmanryoTo").val() ==
                            null
                    ) {
                    } else {
                        if (
                            clsComFnc.CheckDate(
                                $(".FrmOshiraseJokenToroku.shakenmanryoTo")
                            ) == false
                        ) {
                            me.subMsgOutput(
                                -22,
                                "車検満了日(至)",
                                $(".FrmOshiraseJokenToroku.shakenmanryoTo"),
                                "「YYYY/MM/DD」"
                            );
                            return;
                        }
                    }
                } else {
                    //YYYY/MM/DDの型チェック
                    if (
                        clsComFnc.CheckDate(
                            $(".FrmOshiraseJokenToroku.shakenmanryoFrom")
                        ) == false
                    ) {
                        me.subMsgOutput(
                            -22,
                            "車検満了日(自)",
                            $(".FrmOshiraseJokenToroku.shakenmanryoFrom"),
                            "「YYYY/MM/DD」"
                        );
                        return;
                    }
                    if (
                        $(".FrmOshiraseJokenToroku.shakenmanryoTo").val() ==
                            "" ||
                        $(".FrmOshiraseJokenToroku.shakenmanryoTo").val() ==
                            null
                    ) {
                    } else {
                        if (
                            clsComFnc.CheckDate(
                                $(".FrmOshiraseJokenToroku.shakenmanryoTo")
                            ) == false
                        ) {
                            me.subMsgOutput(
                                -22,
                                "車検満了日(至)",
                                $(".FrmOshiraseJokenToroku.shakenmanryoTo"),
                                "「YYYY/MM/DD」"
                            );
                            return;
                        }
                        //日付期間のチェック
                        if (
                            osdata["shakenmanryoFrom"] >
                            osdata["shakenmanryoTo"]
                        ) {
                            me.subMsgOutput(
                                -99,
                                "車検満了日（至）は車検満了日（自）以降の日付を入力してください。"
                            );
                            return;
                        }
                    }
                }
                //車点検ＤＭ発信結果日時チェック
                if (
                    $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").val() ==
                        "" ||
                    $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom").val() ==
                        null
                ) {
                    if (
                        $(
                            ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                        ).val() == "" ||
                        $(
                            ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                        ).val() == null
                    ) {
                    } else {
                        if (
                            clsComFnc.CheckDate(
                                $(
                                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                                )
                            ) == false
                        ) {
                            me.subMsgOutput(
                                -22,
                                "車点検ＤＭ発信結果日時(至)",
                                $(
                                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                                ),
                                "「YYYY/MM/DD」"
                            );
                            return;
                        }
                    }
                } else {
                    //YYYY/MM/DDの型チェック
                    if (
                        clsComFnc.CheckDate(
                            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom")
                        ) == false
                    ) {
                        me.subMsgOutput(
                            -22,
                            "車点検ＤＭ発信結果日時(自)",
                            $(".FrmOshiraseJokenToroku.dmhasshinkekkaDateFrom"),
                            "「YYYY/MM/DD」"
                        );
                        return;
                    }
                    if (
                        $(
                            ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                        ).val() == "" ||
                        $(
                            ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                        ).val() == null
                    ) {
                    } else {
                        if (
                            clsComFnc.CheckDate(
                                $(
                                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                                )
                            ) == false
                        ) {
                            me.subMsgOutput(
                                -22,
                                "車点検ＤＭ発信結果日時(至)",
                                $(
                                    ".FrmOshiraseJokenToroku.dmhasshinkekkaDateTo"
                                ),
                                "「YYYY/MM/DD」"
                            );
                            return;
                        }
                        //日付期間のチェック
                        if (
                            osdata["dmhasshinkekkaDateFrom"] >
                            osdata["dmhasshinkekkaDateTo"]
                        ) {
                            me.subMsgOutput(
                                -99,
                                "車点検ＤＭ発信結果日時（至）は車点検ＤＭ発信結果日時（自）以降の日付を入力してください。"
                            );
                            return;
                        }
                    }
                }
            }
            var data = {
                oshiraseId: me.oshiraseId,
                messid: $(".FrmOshiraseJokenToroku.txtMesseJi")
                    .val()
                    .substring(0, 6),
                zenkensofu: zenkensofu,
                mode: me.Mode,
                upddt: me.upddt,
                data: osdata,
            };
        }
        if (me.Mode == 3) {
            var data = {
                oshiraseId: me.oshiraseId,
                messid: "",
                zenkensofu: "",
                mode: me.Mode,
                upddt: me.upddt,
                data: "",
            };
        }
        //ログイン開始
        var url = me.sys_id + "/" + me.id + "/" + "fncToroku";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.backflg = "1";
                $("#FrmOshiraseJokenIchiranSanshodialogs").dialog("close");
            } else {
                me.backflg = "0";
                if (
                    result["data"] ==
                        "入力されたメッセージIDは登録されていません。" ||
                    result["data"] == "対象件数が０件です" ||
                    result["data"] == "他のユーザによって変更されています" ||
                    result["data"] == "このお知らせ条件は既に連携済みです"
                ) {
                    me.subMsgOutput(-99, result["data"]);
                } else {
                    me.subMsgOutput(-9, result["data"]);
                }

                return;
            }
        };
        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：[キャンセル]ボタンクリック
    //'関 数 名：me.btnCancel
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnCancel = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.FncCancelConfirm;
        me.subMsgOutput(-999, "キャンセルします。よろしいですか？");
    };

    //'**********************************************************************
    //'処 理 名：[キャンセル(YES)]ボタンクリック
    //'関 数 名：me.FncCancelConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncCancelConfirm = function () {
        $("#FrmOshiraseJokenIchiranSanshodialogs").dialog("close");
    };

    me.subMsgOutput = function (intErrMsgNo, strErrMsg, formObj, strErrMsg2) {
        switch (intErrMsgNo) {
            case -1:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0001", strErrMsg);
                break;
            case -2:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0002", strErrMsg);
                break;
            case -3:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0003", strErrMsg);
                break;
            case -9:
                clsComFnc.FncMsgBox("E9999", strErrMsg);
                break;
            case -10:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("E0010", strErrMsg);
                break;
            case -22:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0022", strErrMsg, strErrMsg2);
                break;
            case -99:
                clsComFnc.FncMsgBox("W9999", strErrMsg);
                break;
            case -999:
                clsComFnc.FncMsgBox("QY999", strErrMsg);
                break;
        }
    };

    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_APPM_FrmOshiraseJokenToroku = new APPM.FrmOshiraseJokenToroku();
    o_APPM_FrmOshiraseJokenToroku.load();
    o_APPM_APPM.FrmOshiraseJokenToroku = o_APPM_FrmOshiraseJokenToroku;
});
