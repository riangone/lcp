/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE280IntroduceConfirmEntry");

HMTVE.HMTVE280IntroduceConfirmEntry = function () {
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
    me.id = "HMTVE280IntroduceConfirmEntry";
    me.grid_id = "#HMTVE280IntroduceConfirmEntry_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";

    me.HidNull = "";
    me.HidReshow = "";
    me.hidIntroPeople = "";
    me.HidYBefore = "";
    me.HidYAfter = "";
    me.post_data = [];

    me.option = {
        caption: "",
        rowNum: 0,
        rownumbers: true,
        multiselect: false,
        viewrecords: false,
    };

    me.colModel = [
        {
            label: "受理№",
            width: 90,
            align: "left",
            name: "JYURI_NO",
            index: "JYURI_NO",
            sortable: false,
        },
        {
            label: "提供日",
            width: 90,
            align: "left",
            name: "JYURI_DT",
            index: "JYURI_DT",
            sortable: false,
        },
        {
            label: "店舗",
            width: 90,
            align: "left",
            name: "BUSYO_RYKNM",
            index: "BUSYO_RYKNM",
            sortable: false,
        },
        {
            label: "担当者",
            name: "SYAIN_NM",
            index: "SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            label: "お客様",
            name: "OKYAKU_NM",
            index: "OKYAKU_NM",
            width: me.ratio === 1.5 ? 155 : 180,
            align: "left",
            sortable: false,
        },
        {
            label: "紹介者・窓口会社",
            name: "SYOUKAI_NM",
            index: "SYOUKAI_NM",
            width: me.ratio === 1.5 ? 155 : 180,
            align: "left",
            sortable: false,
        },
        {
            label: "不備理由",
            name: "FUBI_RIYU",
            index: "FUBI_RIYU",
            width: me.ratio === 1.5 ? 155 : 180,
            align: "left",
            sortable: false,
            editable: true,
        },
        {
            name: "BUSYO_CD",
            index: "BUSYO_CD",
            hidden: true,
        },
        {
            name: "SYAIN_NO",
            index: "SYAIN_NO",
            hidden: true,
        },
        {
            name: "SYOUDAN_FLG",
            index: "SYOUDAN_FLG",
            hidden: true,
        },
        {
            label: "",
            name: "",
            index: "EDIT",
            width: 80,
            align: "center",
            formatter: function (_cellvalue, options) {
                var detail =
                    '<button onclick="btnEdit_Click(' +
                    options.rowId +
                    ")\" id = 'btnEdit' class=\"HMTVE280IntroduceConfirmEntry btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>編集</button>";
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnETSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnClear",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnLand",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE280IntroduceConfirmEntry.txtAcceptDate",
        type: "datepicker",
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
    $(".HMTVE280IntroduceConfirmEntry.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    //コピーボタンクリック
    $(".HMTVE280IntroduceConfirmEntry.btnCopy").click(function () {
        me.btnCopy_Click();
    });
    //登録ボタンクリック
    $(".HMTVE280IntroduceConfirmEntry.btnLand").click(function () {
        me.btnLand_Click();
    });
    //クリアボタンクリック
    $(".HMTVE280IntroduceConfirmEntry.btnClear").click(function () {
        me.btnClear_Click();
    });
    //削除ボタンクリック
    $(".HMTVE280IntroduceConfirmEntry.btnDelete").click(function () {
        me.btnDelete_Click();
    });
    //検索ボタンクリック
    $(".HMTVE280IntroduceConfirmEntry.btnSearch").click(function () {
        me.openPageExbSearch();
    });
    //受理No.change
    $(".HMTVE280IntroduceConfirmEntry.txtJyuriNo").change(function () {
        me.Upper1();
    });
    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").change(function () {
        me.Upper();
    });
    //部署change
    $(".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1").change(function () {
        me.FoucsMove();
    });
    $(".HMTVE280IntroduceConfirmEntry.txtPost").change(function () {
        me.FoucsMove1();
    });
    //change
    $(".HMTVE280IntroduceConfirmEntry.ddlYear").change(function () {
        me.DLselectchange();
    });
    $(".HMTVE280IntroduceConfirmEntry.ddlMonth").change(function () {
        me.DLselectchange();
    });
    $(".HMTVE280IntroduceConfirmEntry.ddlYear2").change(function () {
        me.DLselectchange2();
    });
    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").change(function () {
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
            //画面初期化
            //紹介者ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();
            //画面項目をクリアする
            me.clearPage("all");
            try {
                var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
                var data = {};
                me.ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (!result["result"]) {
                        $(".HMTVE280IntroduceConfirmEntry.btnDelete").button(
                            "disable"
                        );
                        $(".HMTVE280IntroduceConfirmEntry.btnLand").button(
                            "disable"
                        );

                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    me.ddlYear = result["data"]["getTerm"];
                    me.post_data = result["data"]["MST"];

                    var sysdata = result["data"]["sysdata"];
                    if (
                        me.ddlYear.length == 0 ||
                        (me.ddlYear.length > 0 &&
                            me.ddlYear[0]["HI_MIN"] == null &&
                            me.ddlYear[0]["HI_MAX"] == null)
                    ) {
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(".HMTVE280IntroduceConfirmEntry.ddlYear");
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlMonth"
                            );
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                            );
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlMonth2"
                            );
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
                        //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
                        $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(
                            sysdata
                        );
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlDirector"
                            );

                        me.HidNull = "DataNull";
                    } else {
                        //コンボリストに日付を設定する
                        //年のコンボリストを設定する
                        me.setYear("ddlYear", sysdata);
                        me.setYear("ddlYear2", sysdata);
                        //月のコンボリストを設定する
                        me.setMonth("ddlMonth", sysdata);
                        me.setMonth("ddlMonth2", sysdata);
                        //日のコンボリストを選択する
                        me.setDay("ddlDay", "ddlYear", "ddlMonth", sysdata);
                        me.setDay("ddlDay2", "ddlYear2", "ddlMonth2", sysdata);
                        //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
                        $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(
                            sysdata
                        );
                        $("<option></option>")
                            .val("")
                            .text("")
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlDirector"
                            );
                    }
                    $(".HMTVE280IntroduceConfirmEntry.rdoMikaku").prop(
                        "checked",
                        "checked"
                    );

                    var complete_fun = function (_returnFLG, data) {
                        if (data["error"]) {
                            me.clsComFnc.FncMsgBox("E9999", data["error"]);
                            return;
                        }
                        $(me.grid_id).jqGrid("bindKeys");
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
                        me.ratio === 1.5 ? 1025 : 1090
                    );
                    //20240806 caina upd s
                    // gdmz.common.jqgrid.set_grid_height(me.grid_id, 300);
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        me.ratio === 1.5 ? 205 : 277
                    );
                    //20240806 caina upd e
                };
                me.ajax.send(url, data, 0);
            } catch (e) {
                console.log(e);
            } finally {
                //フォーカス移動
                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").trigger(
                    "focus"
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者検索ボタンのイベント
	 '関 数 名：btnETSearch_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：検索画面の表示
	 '**********************************************************************
	 */
    me.btnETSearch_Click = function () {
        try {
            //画面クリア処理
            $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();

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
                $(".HMTVE280IntroduceConfirmEntry.ddlYear").val() != "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() != ""
            ) {
                var f =
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear").val() +
                    "/" +
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() +
                    "/" +
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay").val();
                var t =
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() +
                    "/" +
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() +
                    "/" +
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay2").val();
                if (f > t) {
                    if (me.HidReshow != "RESHOWSEARCH") {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE280IntroduceConfirmEntry.ddlYear"
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "受理日の大小関係が不正です！"
                        );
                        return;
                    }
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear").trigger(
                        "focus"
                    );
                    return;
                }
            }
            //紹介者確認データを表示する
            me.showData();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者検索ボタンのイベント
	 '関 数 名：btnCopy_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：お客様に入力された値を紹介者・窓口会社にコピーする
	 '**********************************************************************
	 */
    me.btnCopy_Click = function () {
        try {
            if (
                $(".HMTVE280IntroduceConfirmEntry.txtClient").val() != "" &&
                $(".HMTVE280IntroduceConfirmEntry.txtClient").val() != null
            ) {
                $(".HMTVE280IntroduceConfirmEntry.txtIntroPeople").val(
                    $(".HMTVE280IntroduceConfirmEntry.txtClient").val()
                );
                $(".HMTVE280IntroduceConfirmEntry.chkBargain").trigger("focus");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者登録ボタンのイベント
	 '関 数 名：btnLand_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：紹介者情報登録
	 '**********************************************************************
	 */
    me.btnLand_Click = function () {
        try {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                //入力チェック
                if (me.checkInputs() == false) {
                    return;
                }
                //受理No書式のチェック
                if (me.checkOrderNo("txtAcceptNo") == false) {
                    return;
                }
                //受理日付書式のチェック
                if (me.checkDateFormat("txtAcceptDate") == false) {
                    return;
                }
                //コンボリスト
                me.HidYBefore = $(
                    ".HMTVE280IntroduceConfirmEntry.ddlYear"
                ).val();
                me.HidYAfter = $(
                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                ).val();
                //更新対象の紹介者確認ﾃﾞｰﾀの取得
                var url = me.sys_id + "/" + me.id + "/" + "btnLand_Click";
                var data = {
                    txtAcceptNo: $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo")
                        .val()
                        .trimEnd(),
                    txtAcceptDate: $(
                        ".HMTVE280IntroduceConfirmEntry.txtAcceptDate"
                    )
                        .val()
                        .trimEnd()
                        .replace(/\//g, ""),
                    txtPost: $(".HMTVE280IntroduceConfirmEntry.txtPost")
                        .val()
                        .trimEnd(),
                    ddlDirector: $(
                        ".HMTVE280IntroduceConfirmEntry.ddlDirector"
                    ).val(),
                    txtClient: $(".HMTVE280IntroduceConfirmEntry.txtClient")
                        .val()
                        .trimEnd(),
                    txtIntroPeople: $(
                        ".HMTVE280IntroduceConfirmEntry.txtIntroPeople"
                    )
                        .val()
                        .trimEnd(),
                    txtAcceptNoEnabled: $(
                        ".HMTVE280IntroduceConfirmEntry.txtAcceptNo"
                    ).is(":enabled"),
                    chkJudge: $(".HMTVE280IntroduceConfirmEntry.chkBargain").is(
                        ":checked"
                    )
                        ? "'1'"
                        : "NULL",
                };
                me.ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (!result["result"]) {
                        if (result["error"] == "E9999") {
                            me.clsComFnc.ObjFocus = $(
                                ".HMTVE280IntroduceConfirmEntry.txtAcceptNo"
                            );
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "既に登録されています。"
                            );
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                        return;
                    }
                    //再表示
                    me.ddlYear = result["data"]["getTerm"];
                    me.reShow("Land", result["data"]["sysdata"]);
                };
                me.ajax.send(url, data, 0);
            };
            me.clsComFnc.FncMsgBox(
                "QY999",
                "受理No." +
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").val() +
                    "の紹介者データを登録します。よろしいですか？"
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者クリアボタンのイベント
	 '関 数 名：btnClear_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：紹介者情報クリア
	 '**********************************************************************
	 */
    me.btnClear_Click = function () {
        try {
            me.clearPage();
            //システム日付を取得する
            var url = me.sys_id + "/" + me.id + "/" + "btnClear_Click";
            var data = {};
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var sysdata = result["data"]["sysdata"];

                $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(sysdata);
                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                    "disabled",
                    false
                );
                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").trigger(
                    "focus"
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：再表示のイベント
	 '関 数 名：reShow
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '引 数 ３：(I)flag
	 '戻 り 値：なし
	 '処理説明：ページを表示する
	 '**********************************************************************
	 */
    me.reShow = function (flag, sysdata) {
        try {
            //再表示処理を行う
            if (
                $(".HMTVE280IntroduceConfirmEntry.tblDetail").css("display") ==
                "block"
            ) {
                //画面制御
                me.clearPage();

                $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(sysdata);
                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").trigger(
                    "focus"
                );
                //対象期間を取得する
                if (
                    me.ddlYear.length == 0 ||
                    (me.ddlYear.length > 0 &&
                        me.ddlYear[0]["HI_MIN"] == null &&
                        me.ddlYear[0]["HI_MAX"] == null)
                ) {
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlMonth");
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlYear");
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlMonth2");
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlYear2");
                    //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(
                        sysdata
                    );
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDirector");

                    me.HidNull = "DataNull";

                    me.clsComFnc.FncMsgBox("W0024");
                    $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                        "disabled",
                        false
                    );
                } else {
                    //年のコンボリストを設定しなおす
                    me.setYear("ddlYear", sysdata, "RESHOW");
                    me.setYear("ddlYear2", sysdata, "RESHOW");
                    //登録ボタン押下時に選択されていた年を選択する
                    var isExist = false;
                    var count = $(
                        ".HMTVE280IntroduceConfirmEntry.ddlYear"
                    ).find("option").length;
                    for (var i = 0; i < count; i++) {
                        if (
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear").get(0)
                                .options[i].value == me.HidYBefore
                        ) {
                            isExist = true;
                            break;
                        }
                    }
                    if (isExist) {
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear").val(
                            me.HidYBefore
                        );
                    } else {
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear").val(
                            me.ddlYear[0]["HI_MIN"].substring(0, 4)
                        );
                    }

                    var isExist = false;
                    var count = $(
                        ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                    ).find("option").length;
                    for (var i = 0; i < count; i++) {
                        if (
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear2").get(0)
                                .options[i].value == me.HidYAfter
                        ) {
                            isExist = true;
                            break;
                        }
                    }
                    if (isExist) {
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val(
                            me.HidYAfter
                        );
                    } else {
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val(
                            me.ddlYear[0]["HI_MAX"].substring(0, 4)
                        );
                    }

                    if (me.HidNull == "DataNull") {
                        me.setMonth("ddlMonth", sysdata, "RESHOW");
                        me.setMonth("ddlMonth2", sysdata, "RESHOW");
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val("");
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val("");

                        me.HidNull = "-1";
                    }
                    //再表示処理を行う
                    //日付_FROM_年≠""　AND 日付_FROM_月≠"" AND 日付_FROM_日≠"" AND
                    //日付_TO_年 ≠ "" AND 日付_TO_月 ≠ "" AND 日付_TO_日≠""の場合(全て””の場合再表示されます。)
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        me.HidReshow = "RESHOWSEARCH";
                        me.btnETSearch_Click();
                        me.HidReshow = "-1";
                    };
                    //完了メッセージの表示
                    if (flag == "Land") {
                        //QA.22
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        me.clsComFnc.FncMsgBox("I0017");
                    }
                }
            } else {
                //画面制御
                me.clearPage();

                $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(sysdata);
                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").trigger(
                    "focus"
                );

                //対象期間を取得する
                if (
                    me.ddlYear.length == 0 ||
                    (me.ddlYear.length > 0 &&
                        me.ddlYear[0]["HI_MIN"] == null &&
                        me.ddlYear[0]["HI_MAX"] == null)
                ) {
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlMonth");
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlYear");
                    $(".HMTVE280IntroduceConfirmEntry.ddlDay2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlMonth2");
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear2")
                        .find("option")
                        .remove();
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlYear2");
                    //入力ﾃｰﾌﾞﾙ_受理日に初期値を表示する
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(
                        sysdata
                    );
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDirector");

                    me.HidNull = "DataNull";

                    me.clsComFnc.FncMsgBox("W0024");
                    $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                        "disabled",
                        false
                    );
                } else {
                    //年のコンボリストを設定しなおす
                    me.setYear("ddlYear", sysdata, "RESHOW");
                    me.setYear("ddlYear2", sysdata, "RESHOW");

                    if (me.HidNull == "DataNull") {
                        var isExist = false;
                        var count = $(
                            ".HMTVE280IntroduceConfirmEntry.ddlYear"
                        ).find("option").length;
                        for (var i = 0; i < count; i++) {
                            if (
                                $(".HMTVE280IntroduceConfirmEntry.ddlYear").get(
                                    0
                                ).options[i].value == sysdata.substring(0, 4)
                            ) {
                                isExist = true;
                                break;
                            }
                        }
                        if (isExist) {
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear").val(
                                sysdata.substring(0, 4)
                            );
                        } else {
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear"
                                );
                            $("<option></option>")
                                .val(sysdata.substring(0, 4))
                                .text(sysdata.substring(0, 4))
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear"
                                );
                            ".HMTVE280IntroduceConfirmEntry.ddlYear".val(
                                sysdata.substring(0, 4)
                            );
                        }
                        var isExist = false;
                        var count = $(
                            ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                        ).find("option").length;
                        for (var i = 0; i < count; i++) {
                            if (
                                $(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                                ).get(0).options[i].value ==
                                sysdata.substring(0, 4)
                            ) {
                                isExist = true;
                                break;
                            }
                        }
                        if (isExist) {
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val(
                                sysdata.substring(0, 4)
                            );
                        } else {
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear2")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                                );
                            $("<option></option>")
                                .val(sysdata.substring(0, 4))
                                .text(sysdata.substring(0, 4))
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                                );
                            ".HMTVE280IntroduceConfirmEntry.ddlYear2".val(
                                sysdata.substring(0, 4)
                            );
                        }

                        me.setMonth("ddlMonth", sysdata, "RESHOW");
                        me.setMonth("ddlMonth2", sysdata, "RESHOW");
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val(
                            sysdata.substring(5, 7)
                        );
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val(
                            sysdata.substring(5, 7)
                        );
                        $(".HMTVE280IntroduceConfirmEntry.ddlDay")
                            .find("option")
                            .remove();
                        $(".HMTVE280IntroduceConfirmEntry.ddlDay2")
                            .find("option")
                            .remove();
                        me.addDays("ddlDay", 31);
                        me.addDays("ddlDay2", 31);
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val(
                            sysdata.substring(8, 10)
                        );
                        $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val(
                            sysdata.substring(8, 10)
                        );
                        me.HidNull = "-1";
                    } else {
                        //登録ボタン押下時に選択されていた年を選択する
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear").val(
                            me.HidYBefore
                        );
                        $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val(
                            me.HidYAfter
                        );
                    }
                    //完了メッセージの表示
                    if (flag == "Land") {
                        //QA.22
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        me.clsComFnc.FncMsgBox("I0017");
                    }
                }
                $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();
            }
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                "disabled",
                false
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者削除ボタンのイベント
	 '関 数 名：btnDelete_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：紹介者情報削除
	 '**********************************************************************
	 */
    me.btnDelete_Click = function () {
        try {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                if (me.checkText("txtAcceptNo", "lblAcceptNo") == false) {
                    return;
                }
                //受理No書式のチェク
                if (me.checkOrderNo("txtAcceptNo") == false) {
                    return;
                }
                //コンボリスト
                me.HidYBefore = $(
                    ".HMTVE280IntroduceConfirmEntry.ddlYear"
                ).val();
                me.HidYAfter = $(
                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                ).val();

                //紹介者確認データを削除する
                var url = me.sys_id + "/" + me.id + "/" + "btnDelete_Click";
                var data = {
                    txtAcceptNo: $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo")
                        .val()
                        .trimEnd(),
                };
                me.ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (!result["result"]) {
                        if (result["error"] == "W0024") {
                            me.clsComFnc.FncMsgBox("W0024");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                        return;
                    }
                    me.ddlYear = result["data"]["getTerm"];
                    me.reShow("Delete", result["data"]["sysdata"]);
                };
                me.ajax.send(url, data, 0);
            };

            me.clsComFnc.FncMsgBox(
                "QY999",
                "受理No." +
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").val() +
                    "の紹介者データを削除します。よろしいですか？"
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：紹介者レコード変更ボタンのイベント
	 '関 数 名：grdView_RowEditing
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：紹介者レコード変更
	 '**********************************************************************
	 */
    btnEdit_Click = function (rowid) {
        try {
            me.clearPage();
            //選択行の値を入力ﾃｰﾌﾞﾙに表示する
            var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").val(
                rowData["JYURI_NO"]
            );
            // 20210110 &nbsp; 应该是html里用来表示空格的。这个先不考虑吧
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                "disabled",
                "disabled"
            );
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val(
                rowData["JYURI_DT"]
            );
            $(".HMTVE280IntroduceConfirmEntry.lblPost1").val(
                rowData["BUSYO_RYKNM"]
            );
            $(".HMTVE280IntroduceConfirmEntry.txtClient").val(
                rowData["OKYAKU_NM"]
            );
            $(".HMTVE280IntroduceConfirmEntry.txtIntroPeople").val(
                rowData["SYOUKAI_NM"]
            );
            $(".HMTVE280IntroduceConfirmEntry.txtPost").val(
                rowData["BUSYO_CD"]
            );
            if (rowData["SYOUDAN_FLG"] == "1") {
                $(".HMTVE280IntroduceConfirmEntry.chkBargain").prop(
                    "checked",
                    true
                );
            }
            //コンボリストを選択する
            //画面項目NO14.紹介者ﾃｰﾌﾞﾙ_担当者コード
            var str = rowData["SYAIN_NO"];
            //部署に所属する社員を取得する
            //コンボリストを設定する
            var url = me.sys_id + "/" + me.id + "/" + "FoucsMove";
            var data = {
                BUSYOCD: $(".HMTVE280IntroduceConfirmEntry.txtPost").val(),
                T_SYAIN: true,
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //入力ﾃｰﾌﾞﾙ_担当者のコンボリストを設定する()
                //部署に所属する社員を取得する
                var T_SYAIN = result["data"]["T_SYAIN"];
                var flag = 0;
                for (var index in T_SYAIN) {
                    $("<option></option>")
                        .val(T_SYAIN[index]["SYAIN_NO"])
                        .text(T_SYAIN[index]["SYAIN_NM"])
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDirector");
                    if (T_SYAIN[index]["SYAIN_NO"] == str) {
                        $(".HMTVE280IntroduceConfirmEntry.ddlDirector").val(
                            T_SYAIN[index]["SYAIN_NO"]
                        );
                        flag++;
                    }
                }
                //空白行をセットする
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDirector");

                if (flag == 0) {
                    $(".HMTVE280IntroduceConfirmEntry.ddlDirector").val("");
                }

                $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                    "disabled",
                    "disabled"
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：grdView_RowEditing
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：入力をチェックする
	 '**********************************************************************
	 */
    me.checkInputs = function () {
        try {
            //入力ﾃｰﾌﾞﾙ_商談ﾌﾗｸﾞにチェックが入っている場合
            if ($(".HMTVE280IntroduceConfirmEntry.chkBargain").is(":checked")) {
                //入力ﾃｰﾌﾞﾙ_受理のチェック
                if (me.checkText("txtAcceptNo", "lblAcceptNo") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_受理日のチェック
                if (me.checkText("txtAcceptDate", "lblAcceptDate") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_お客様のチェック
                if (me.checkText("txtClient", "lblClient") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_紹介者のチェック
                if (me.checkText("txtIntroPeople", "lblIntroPeople") == false) {
                    return false;
                }
            }
            //入っていない場合
            else {
                //入力ﾃｰﾌﾞﾙ_受理№のチェック
                if (me.checkText("txtAcceptNo", "lblAcceptNo") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_受理日のチェック
                if (me.checkText("txtAcceptDate", "lblAcceptDate") == false) {
                    return false;
                }
                //部署コードのチェック
                if (me.checkText("txtPost", "lblPost") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_お客様のチェック
                if (me.checkText("txtClient", "lblClient") == false) {
                    return false;
                }
                //入力ﾃｰﾌﾞﾙ_紹介者のチェック
                if (me.checkText("txtIntroPeople", "lblIntroPeople") == false) {
                    return false;
                }
            }
            //部署が存在するかチェックを行う
            if ($(".HMTVE280IntroduceConfirmEntry.txtPost").val() != "") {
                if ($(".HMTVE280IntroduceConfirmEntry.lblPost1").val() == "") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry.txtPost"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        $(".HMTVE280IntroduceConfirmEntry.txtPost").val() +
                            "が存在しません。"
                    );
                    return false;
                }
            }
            //桁数チェックを行う
            if (me.checkLength("txtAcceptNo", "lblAcceptNo", 10) == false) {
                return false;
            }
            if (me.checkLength("txtAcceptDate", "lblAcceptDate", 10) == false) {
                return false;
            }
            if (me.checkLength("txtPost", "lblPost", 3) == false) {
                return false;
            }
            if (me.checkLength("txtClient", "lblClient", 40) == false) {
                return false;
            }
            if (
                me.checkLength("txtIntroPeople", "lblIntroPeople", 40) == false
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
	 '処 理 名：データの表示
	 '関 数 名：showData
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし
	 '処理説明：GridViewのデータを表示する
	 '**********************************************************************
	 */
    me.showData = function () {
        try {
            //紹介者確認データを取得する
            var flg = "";
            if (
                $(".HMTVE280IntroduceConfirmEntry.ddlDay").val() == "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlDay2").val() == "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() == "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() == "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlYear").val() == "" &&
                $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() == ""
            ) {
                flg = "all";
            }
            $(me.grid_id).jqGrid("clearGridData");

            var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
            if (
                !$.trim(
                    $(".HMTVE280IntroduceConfirmEntry.txtJyuriNo").val()
                ).match(objRegEX_AN)
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE280IntroduceConfirmEntry.txtJyuriNo"
                );
                me.clsComFnc.FncMsgBox("E0013", "受理No.");
            } else {
                var complete_fun = function (_returnFLG, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    var rowDatas = $(me.grid_id).jqGrid("getRowData");
                    if (rowDatas.length > 0) {
                        //画面制御を行う
                        $(".HMTVE280IntroduceConfirmEntry.tblDetail").show();
                    } else {
                        //対象期間を取得する
                        if (
                            result["T_Term"].length == 0 ||
                            (result["T_Term"].length > 0 &&
                                result["T_Term"][0]["HI_MIN"] == null &&
                                result["T_Term"][0]["HI_MAX"] == null)
                        ) {
                            $(".HMTVE280IntroduceConfirmEntry.ddlDay")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlDay"
                                );
                            $(".HMTVE280IntroduceConfirmEntry.ddlMonth")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlMonth"
                                );
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear"
                                );
                            $(".HMTVE280IntroduceConfirmEntry.ddlDay2")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlDay2"
                                );
                            $(".HMTVE280IntroduceConfirmEntry.ddlMonth2")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlMonth2"
                                );
                            $(".HMTVE280IntroduceConfirmEntry.ddlYear2")
                                .find("option")
                                .remove();
                            $("<option></option>")
                                .val("")
                                .text("")
                                .appendTo(
                                    ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                                );
                        }
                        $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").attr(
                            "disabled",
                            false
                        );
                        if (me.HidReshow != "RESHOWSEARCH") {
                            me.clsComFnc.FncMsgBox("W0024");
                        }
                        $(".HMTVE280IntroduceConfirmEntry.tblDetail").hide();
                    }
                    $(me.grid_id).jqGrid("setSelection", "0");
                };
                var data = {
                    flg: flg,
                    txtJyuriNo: $.trim(
                        $(".HMTVE280IntroduceConfirmEntry.txtJyuriNo").val()
                    ),
                    rdoKaku: $(".HMTVE280IntroduceConfirmEntry.rdoKaku").is(
                        ":checked"
                    ),
                    rdoMikaku: $(".HMTVE280IntroduceConfirmEntry.rdoMikaku").is(
                        ":checked"
                    ),
                    txtExhibitTitle1: $(
                        ".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1"
                    )
                        .val()
                        .trimEnd(),
                    ddlYear: $(".HMTVE280IntroduceConfirmEntry.ddlYear").val(),
                    ddlMonth: $(
                        ".HMTVE280IntroduceConfirmEntry.ddlMonth"
                    ).val(),
                    ddlDay: $(".HMTVE280IntroduceConfirmEntry.ddlDay").val(),
                    ddlYear2: $(
                        ".HMTVE280IntroduceConfirmEntry.ddlYear2"
                    ).val(),
                    ddlMonth2: $(
                        ".HMTVE280IntroduceConfirmEntry.ddlMonth2"
                    ).val(),
                    ddlDay2: $(".HMTVE280IntroduceConfirmEntry.ddlDay2").val(),
                };
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    complete_fun
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年、月、日のチェック
	 '関 数 名：checkYMD
	 '引 数   ：なし
	 '戻 り 値：年月日チェック     boolean
	 '処理説明：年、月、日をチェックする
	 '**********************************************************************
	 */
    me.checkYMD = function (ddlY, ddlM, ddlD) {
        try {
            if (
                $(".HMTVE280IntroduceConfirmEntry." + ddlY + " option")
                    .length == 1 &&
                $(".HMTVE280IntroduceConfirmEntry." + ddlM + " option")
                    .length == 1 &&
                $(".HMTVE280IntroduceConfirmEntry." + ddlD + " option")
                    .length == 1
            ) {
                if (me.HidReshow != "RESHOWSEARCH") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry.txtAcceptNo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "登録されているデータが存在しません。登録を行ってください!"
                    );
                } else {
                    $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").trigger(
                        "focus"
                    );
                }
                return false;
            }
            if (
                $(".HMTVE280IntroduceConfirmEntry." + ddlY).val() != "" ||
                $(".HMTVE280IntroduceConfirmEntry." + ddlM).val() != "" ||
                $(".HMTVE280IntroduceConfirmEntry." + ddlD).val() != ""
            ) {
                //年＝"" OR 月＝"" OR 日＝""の場合
                if (
                    $(".HMTVE280IntroduceConfirmEntry." + ddlY).val() == "" ||
                    $(".HMTVE280IntroduceConfirmEntry." + ddlM).val() == "" ||
                    $(".HMTVE280IntroduceConfirmEntry." + ddlD).val() == ""
                ) {
                    if (
                        $(".HMTVE280IntroduceConfirmEntry." + ddlD).val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE280IntroduceConfirmEntry." + ddlD
                        );
                    }
                    if (
                        $(".HMTVE280IntroduceConfirmEntry." + ddlM).val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE280IntroduceConfirmEntry." + ddlM
                        );
                    }
                    if (
                        $(".HMTVE280IntroduceConfirmEntry." + ddlY).val() == ""
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE280IntroduceConfirmEntry." + ddlY
                        );
                    }
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
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力ﾃｰﾌﾞﾙの画面項目の桁数チェック　　
	 '関 数 名：checkLength
	 '引 数 １：(I)textBox 　　 テキストボックス
	 '引 数 ２：(I)lable      　レッボ
	 '引 数 ３：(I)length       Integer
	 '戻 り 値：入力チェック    Boolean
	 '処理説明：桁数チェックする
	 '**********************************************************************
	 */
    me.checkLength = function (textBox, lable, length) {
        try {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMTVE280IntroduceConfirmEntry." + textBox).val()
                ) > length
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE280IntroduceConfirmEntry." + textBox
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    $(".HMTVE280IntroduceConfirmEntry." + lable).text() +
                        "は指定されている桁数をオーバーしています。"
                );
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力ﾃｰﾌﾞﾙの画面項目のチェック　　
	 '関 数 名：checkText
	 '引 数 １：(I)textBox 　　 テキストボックス
	 '引 数 ２：(I)lable      　レッボ
	 '戻 り 値：入力チェック     Boolean
	 '処理説明：入力テキストをチェックする
	 '**********************************************************************
	 */
    me.checkText = function (textBox, lable) {
        try {
            if (
                $.trim(
                    $(".HMTVE280IntroduceConfirmEntry." + textBox)
                        .val()
                        .replace(
                            /(^(\s|\u0020|\u3000)+)|((\s|\u0020|\u3000)+$)/,
                            ""
                        )
                ) == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE280IntroduceConfirmEntry." + textBox
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    $(".HMTVE280IntroduceConfirmEntry." + lable).text() +
                        "を入力してください。"
                );
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面項目クリア　　
	 '関 数 名：pageClear
	 '引 数 　：なし
	 '戻 り 値：なし
	 '処理説明：画面項目をクリアする
	 '**********************************************************************
	 */
    me.clearPage = function (scrope) {
        try {
            if (scrope == "all") {
                $(".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1").val("");
                $(".HMTVE280IntroduceConfirmEntry.lblExhibitTitle1").val("");
            }
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").val("");
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptDate").val("");
            $(".HMTVE280IntroduceConfirmEntry.txtPost").val("");
            $(".HMTVE280IntroduceConfirmEntry.lblPost1").val("");
            $(".HMTVE280IntroduceConfirmEntry.ddlDirector")
                .find("option")
                .remove();
            $(".HMTVE280IntroduceConfirmEntry.txtClient").val("");
            $(".HMTVE280IntroduceConfirmEntry.txtIntroPeople").val("");
            $(".HMTVE280IntroduceConfirmEntry.chkBargain").prop(
                "checked",
                false
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：年のセット
	 '関 数 名：setYear
	 '引 数   ：なし
	 '戻 り 値：なし
	 '処理説明：年をセットする
	 '**********************************************************************
	 */
    me.setYear = function (ddlY, sysdata, flg) {
        try {
            if (flg == undefined) {
                flg = "";
            }
            $(".HMTVE280IntroduceConfirmEntry." + ddlY)
                .find("option")
                .remove();
            //空白行をセットする
            $("<option></option>")
                .val("")
                .text("")
                .prependTo(".HMTVE280IntroduceConfirmEntry." + ddlY);
            var strMin = me.ddlYear[0]["HI_MIN"],
                strMax = me.ddlYear[0]["HI_MAX"];
            var min = 0,
                max = 0;
            if (strMin == "0" || strMin == null || strMin == "") {
                //何もしない
            } else {
                min = strMin.substring(0, 4);
            }

            if (strMax == "0" || strMax == null || strMax == "") {
                //何もしない
            } else {
                max = strMax.substring(0, 4);
                for (var i = min; i <= max; i++) {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .prependTo(".HMTVE280IntroduceConfirmEntry." + ddlY);
                }
            }
            //デフォルト日付を指定する
            var str = sysdata.substring(0, 4);
            if (max < str) {
                $("<option></option>")
                    .val(str)
                    .text(str)
                    .prependTo(".HMTVE280IntroduceConfirmEntry." + ddlY);

                $(".HMTVE280IntroduceConfirmEntry." + ddlY).get(
                    0
                ).selectedIndex = 0;
            } else {
                if (flg == "RESHOW") {
                    //何もしない
                } else {
                    var isExist = false;
                    var count = $(
                        ".HMTVE280IntroduceConfirmEntry." + ddlY
                    ).find("option").length;
                    for (var i = 0; i < count; i++) {
                        if (
                            $(".HMTVE280IntroduceConfirmEntry." + ddlY).get(0)
                                .options[i].value == str
                        ) {
                            isExist = true;
                            break;
                        }
                    }
                    if (isExist) {
                        $(".HMTVE280IntroduceConfirmEntry." + ddlY).val(str);
                    } else {
                        $(".HMTVE280IntroduceConfirmEntry." + ddlY).val(max);
                    }
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：月のセット
	 '関 数 名：setMonth
	 '引 数   ：なし
	 '戻 り 値：なし
	 '処理説明：月をセットする
	 '**********************************************************************
	 */
    me.setMonth = function (ddlM, sysdata, flg) {
        try {
            if (flg == undefined) {
                flg = "";
            }
            $(".HMTVE280IntroduceConfirmEntry." + ddlM)
                .find("option")
                .remove();
            //日付_FROMと日付_TOにセットする
            for (var i = 1; i <= 12; i++) {
                if (i < 10) {
                    $("<option></option>")
                        .val("0" + i)
                        .text("0" + i)
                        .appendTo(".HMTVE280IntroduceConfirmEntry." + ddlM);
                } else {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .appendTo(".HMTVE280IntroduceConfirmEntry." + ddlM);
                }
            }
            //デフォルト日付を指定する
            if (flg == "RESHOW") {
            } else {
                $(".HMTVE280IntroduceConfirmEntry." + ddlM).val(
                    sysdata.substring(5, 7)
                );
            }
            //空白行をセットする
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE280IntroduceConfirmEntry." + ddlM);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：日のセット
	 '関 数 名：setDay
	 '引 数   ：なし
	 '戻 り 値：なし
	 '処理説明：日をセットする
	 '**********************************************************************
	 */
    me.setDay = function (ddlD, ddlY, ddlM, sysdata) {
        try {
            var year = 0,
                month = 0;
            if ($(".HMTVE280IntroduceConfirmEntry." + ddlY).val() != "") {
                year = $(".HMTVE280IntroduceConfirmEntry." + ddlY).val();
            } else {
                return;
            }
            if ($(".HMTVE280IntroduceConfirmEntry." + ddlM).val() != "") {
                month = $(".HMTVE280IntroduceConfirmEntry." + ddlM).val();
            } else {
                return;
            }

            $(".HMTVE280IntroduceConfirmEntry." + ddlD)
                .find("option")
                .remove();
            //日付_FROMと日付_TOにセットする
            if (
                month == "04" ||
                month == "06" ||
                month == "09" ||
                month == "11"
            ) {
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
                $(".HMTVE280IntroduceConfirmEntry." + ddlD).val(
                    sysdata.substring(8, 10)
                );
            }
            //空白行をセットする
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE280IntroduceConfirmEntry." + ddlD);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：日の増加
	 '関 数 名：setDay
	 '引 数   ：なし
	 '戻 り 値：なし
	 '処理説明：日を増加する
	 '**********************************************************************
	 */
    me.addDays = function (ddl, DayNum) {
        try {
            for (var i = 1; i <= DayNum; i++) {
                if (i < 10) {
                    $("<option></option>")
                        .val("0" + i)
                        .text("0" + i)
                        .appendTo(".HMTVE280IntroduceConfirmEntry." + ddl);
                } else {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .appendTo(".HMTVE280IntroduceConfirmEntry." + ddl);
                }
            }
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
            //画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
            if (
                $(".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1").val() != ""
            ) {
                var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
                if (
                    !objRegEX_AN.test(
                        $.trim(
                            $(
                                ".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1"
                            ).val()
                        )
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "部署");

                    $(".HMTVE280IntroduceConfirmEntry.lblExhibitTitle1").val(
                        ""
                    );
                    return;
                }
                var BUSYO_CD_FLAG = false;
                for (var index in me.post_data) {
                    if (
                        me.post_data[index]["BUSYO_CD"] ==
                        $(
                            ".HMTVE280IntroduceConfirmEntry.txtExhibitTitle1"
                        ).val()
                    ) {
                        BUSYO_CD_FLAG = true;
                        $(
                            ".HMTVE280IntroduceConfirmEntry.lblExhibitTitle1"
                        ).val(me.post_data[index]["BUSYO_RYKNM"]);
                        break;
                    }
                }
                if (!BUSYO_CD_FLAG) {
                    $(".HMTVE280IntroduceConfirmEntry.lblExhibitTitle1").val(
                        ""
                    );
                }
            } else {
                $(".HMTVE280IntroduceConfirmEntry.lblExhibitTitle1").val("");
            }

            $(".HMTVE280IntroduceConfirmEntry.ddlYear").trigger("focus");
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
    me.FoucsMove1 = function () {
        try {
            //画面項目NO18.入力ﾃｰﾌﾞﾙ_部署コードが見入力の場合、処理を抜ける
            if ($(".HMTVE280IntroduceConfirmEntry.txtPost").val() != "") {
                $(".HMTVE280IntroduceConfirmEntry.ddlDirector")
                    .find("option")
                    .remove();

                var objRegEX_AN = /^[a-zA-Z0-9\-]*$/g;
                if (
                    !objRegEX_AN.test(
                        $.trim(
                            $(".HMTVE280IntroduceConfirmEntry.txtPost").val()
                        )
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry.txtPost"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "部署");
                    if (
                        $(".HMTVE280IntroduceConfirmEntry.lblPost1").val() != ""
                    ) {
                        $(".HMTVE280IntroduceConfirmEntry.lblPost1").val("");
                    }
                    return;
                }
                var url = me.sys_id + "/" + me.id + "/" + "FoucsMove";
                var data = {
                    BUSYOCD: $(".HMTVE280IntroduceConfirmEntry.txtPost").val(),
                    T_SYAIN: true,
                };
                me.ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    var BUSYO_CD_FLAG = false;
                    for (var index in me.post_data) {
                        if (
                            me.post_data[index]["BUSYO_CD"] ==
                            $(".HMTVE280IntroduceConfirmEntry.txtPost").val()
                        ) {
                            BUSYO_CD_FLAG = true;

                            $(".HMTVE280IntroduceConfirmEntry.lblPost1").val(
                                me.post_data[index]["BUSYO_RYKNM"]
                            );
                            break;
                        }
                    }
                    if (!BUSYO_CD_FLAG) {
                        $(".HMTVE280IntroduceConfirmEntry.lblPost1").val("");
                    }

                    //入力ﾃｰﾌﾞﾙ_担当者のコンボリストを設定する()
                    //部署に所属する社員を取得する
                    var T_SYAIN = result["data"]["T_SYAIN"];
                    for (var index in T_SYAIN) {
                        $("<option></option>")
                            .val(T_SYAIN[index]["SYAIN_NO"])
                            .text(T_SYAIN[index]["SYAIN_NM"])
                            .appendTo(
                                ".HMTVE280IntroduceConfirmEntry.ddlDirector"
                            );
                    }
                    //空白行をセットする
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDirector");
                    //コンボリストを選択する
                    $(".HMTVE280IntroduceConfirmEntry.ddlDirector").val("");
                };
                me.ajax.send(url, data, 0);
            } else {
                $(".HMTVE280IntroduceConfirmEntry.lblPost1").val("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：受理Ｎoの書式チェク
	 '関 数 名：checkOrderNo
	 '引 数 １：なし
	 '戻 り 値：なし
	 '処理説明：受理Ｎoの書式をチェクする
	 '**********************************************************************
	 */
    me.checkOrderNo = function (textBox) {
        try {
            var str = $(".HMTVE280IntroduceConfirmEntry." + textBox).val();
            for (var i = 0; i < str.length; i++) {
                //全角
                if (me.clsComFnc.GetByteCount(str[i]) >= 2) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry." + textBox
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "受理№に不正な値が入力されています。"
                    );
                    return false;
                } else {
                    if (
                        (str.charCodeAt(i) >= 48 && str.charCodeAt(i) <= 57) ||
                        (str.charCodeAt(i) >= 65 && str.charCodeAt(i) <= 90) ||
                        (str.charCodeAt(i) >= 97 && str.charCodeAt(i) <= 122)
                    ) {
                    } else {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE280IntroduceConfirmEntry." + textBox
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "受理№に不正な値が入力されています。"
                        );
                        return false;
                    }
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：受理日の書式チェク
	 '関 数 名：checkDateFormat
	 '引 数 １：なし
	 '戻 り 値：なし
	 '処理説明：受理日の書式をチェクする
	 '**********************************************************************
	 */
    me.checkDateFormat = function (txtDate) {
        try {
            if (
                $.trim($(".HMTVE280IntroduceConfirmEntry." + txtDate).val())
                    .length == 0
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE280IntroduceConfirmEntry." + txtDate
                );
                me.clsComFnc.FncMsgBox("W9999", "受理日を入力してください。");
                return false;
            } else if (
                $.trim($(".HMTVE280IntroduceConfirmEntry." + txtDate).val())
                    .length > 10
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE280IntroduceConfirmEntry." + txtDate
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "受理日は指定されている桁数をオーバーしています。"
                );
                return false;
            } else {
                if (
                    /^(\d{4})\/(0\d{1}|1[0-2])\/(0\d{1}|[12]\d{1}|3[01])$/.test(
                        $.trim(
                            $(".HMTVE280IntroduceConfirmEntry." + txtDate).val()
                        )
                    )
                ) {
                    $(".HMTVE280IntroduceConfirmEntry." + txtDate).val(
                        $.trim(
                            $(".HMTVE280IntroduceConfirmEntry." + txtDate).val()
                        )
                    );
                } else {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE280IntroduceConfirmEntry." + txtDate
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "YYYY/MM/DD'書式のようにご入力ください"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    me.DLselectchange = function () {
        try {
            var i = 0,
                zdr = 0,
                day = $.trim($(".HMTVE280IntroduceConfirmEntry.ddlDay").val());
            if (
                $(".HMTVE280IntroduceConfirmEntry.ddlYear").val() % 400 == 0 ||
                ($(".HMTVE280IntroduceConfirmEntry.ddlYear").val() % 4 == 0 &&
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear").val() % 100 !=
                        0)
            ) {
                zdr =
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() <= "07"
                        ? $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() %
                              2 ==
                          0
                            ? $(
                                  ".HMTVE280IntroduceConfirmEntry.ddlMonth"
                              ).val() == 2
                                ? 29
                                : 30
                            : 31
                        : $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                zdr =
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() <= "07"
                        ? $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() %
                              2 ==
                          0
                            ? $(
                                  ".HMTVE280IntroduceConfirmEntry.ddlMonth"
                              ).val() == 2
                                ? 28
                                : 30
                            : 31
                        : $(".HMTVE280IntroduceConfirmEntry.ddlMonth").val() %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE280IntroduceConfirmEntry.ddlDay").children().remove();
            for (var i = 1; i <= 9; i++) {
                $("<option></option>")
                    .val("0" + i)
                    .text("0" + i)
                    .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
            }
            for (i = 10; i <= parseInt(zdr); i++) {
                $("<option></option>")
                    .val(i)
                    .text(i)
                    .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
            }
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay");
            if (day == "") {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay").get(
                    0
                ).selectedIndex = parseInt(zdr);
            } else if (parseInt(day) > parseInt(zdr)) {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay").get(
                    0
                ).selectedIndex = 0;
            } else {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay").val(day);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.DLselectchange2 = function () {
        try {
            var i = 0,
                zdr = 0,
                day = $.trim($(".HMTVE280IntroduceConfirmEntry.ddlDay2").val());
            if (
                $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() % 400 == 0 ||
                ($(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() % 4 == 0 &&
                    $(".HMTVE280IntroduceConfirmEntry.ddlYear2").val() % 100 !=
                        0)
            ) {
                zdr =
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() <= "07"
                        ? $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() %
                              2 ==
                          0
                            ? $(
                                  ".HMTVE280IntroduceConfirmEntry.ddlMonth2"
                              ).val() == 2
                                ? 29
                                : 30
                            : 31
                        : $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() %
                              2 ==
                          0
                        ? 31
                        : 30;
            } else {
                zdr =
                    $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() <= "07"
                        ? $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() %
                              2 ==
                          0
                            ? $(
                                  ".HMTVE280IntroduceConfirmEntry.ddlMonth2"
                              ).val() == 2
                                ? 28
                                : 30
                            : 31
                        : $(".HMTVE280IntroduceConfirmEntry.ddlMonth2").val() %
                              2 ==
                          0
                        ? 31
                        : 30;
            }
            $(".HMTVE280IntroduceConfirmEntry.ddlDay2").children().remove();
            for (var i = 1; i <= 9; i++) {
                $("<option></option>")
                    .val("0" + i)
                    .text("0" + i)
                    .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
            }
            for (i = 10; i <= parseInt(zdr); i++) {
                $("<option></option>")
                    .val(i)
                    .text(i)
                    .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
            }
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMTVE280IntroduceConfirmEntry.ddlDay2");
            if (day == "") {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay2").get(
                    0
                ).selectedIndex = parseInt(zdr);
            } else if (parseInt(day) > parseInt(zdr)) {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay2").get(
                    0
                ).selectedIndex = 0;
            } else {
                $(".HMTVE280IntroduceConfirmEntry.ddlDay2").val(day);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.Upper = function () {
        try {
            var txtAcceptNo = $(
                ".HMTVE280IntroduceConfirmEntry.txtAcceptNo"
            ).val();
            $(".HMTVE280IntroduceConfirmEntry.txtAcceptNo").val(
                txtAcceptNo.toUpperCase()
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    me.Upper1 = function () {
        try {
            var txtJyuriNo1 = $(
                ".HMTVE280IntroduceConfirmEntry.txtJyuriNo"
            ).val();
            $(".HMTVE280IntroduceConfirmEntry.txtJyuriNo").val(
                txtJyuriNo1.toUpperCase()
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    me.openPageExbSearch = function () {
        try {
            var $root_div = $(".HMTVE280IntroduceConfirmEntry.HMTVE-content");
            if ($("#HMTVE390HDTCOMPANYSEARCHDialogDiv").length > 0) {
                $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").remove();
            }
            $("<div></div>")
                .attr("id", "HMTVE390HDTCOMPANYSEARCHDialogDiv")
                .insertAfter($root_div);
            $("<div></div>")
                .attr("id", "hidIntroPeople")
                .insertAfter($root_div);
            $("<div></div>").attr("id", "FLAG").insertAfter($root_div);

            var $hidData = $root_div.parent().find("#hidIntroPeople");
            var $FLAG = $root_div.parent().find("#FLAG");

            me.url = "HMTVE/HMTVE390HDTCOMPANYSEARCH";
            me.ajax.receive = function (result) {
                function before_close() {
                    if ($FLAG.html() == "1") {
                        $(".HMTVE280IntroduceConfirmEntry.txtIntroPeople").val(
                            $hidData.html()
                        );
                    }

                    $hidData.remove();
                    $FLAG.remove();

                    $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").remove();

                    $(".HMTVE280IntroduceConfirmEntry.txtIntroPeople").trigger(
                        "focus"
                    );
                }

                $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").hide();
                $("#HMTVE390HDTCOMPANYSEARCHDialogDiv").append(result);

                o_HMTVE_HMTVE.HMTVE280IntroduceConfirmEntry.HMTVE390HDTCOMPANYSEARCH.before_close =
                    before_close;
            };
            me.ajax.send(me.url, "", 0);
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
    var o_HMTVE_HMTVE280IntroduceConfirmEntry =
        new HMTVE.HMTVE280IntroduceConfirmEntry();
    o_HMTVE_HMTVE280IntroduceConfirmEntry.load();
    o_HMTVE_HMTVE.HMTVE280IntroduceConfirmEntry =
        o_HMTVE_HMTVE280IntroduceConfirmEntry;
});
