/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("HMTVE.HMTVE240ReportPlaceCntEntry");

HMTVE.HMTVE240ReportPlaceCntEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE240ReportPlaceCntEntry";
    me.hmtve = new HMTVE.HMTVE();
    me.total_count = [];
    me.jqgridReadOnly = true;
    me.last_selected_id = 1;
    me.g_url = "HMTVE/HMTVE240ReportPlaceCntEntry/getReporter";
    me.grid_id = "#HMTVE240ReportPlaceCntEntry_tblSubMain";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        footerrow: true,
        caption: "",
        shrinkToFit: true,
        multiselectWidth: 60,
    };
    me.colModel = [
        {
            name: "Classification",
            classes: "HMTVE240_CELL_TITLE_BLUE_C",
            label: "申請区分",
            index: "Classification",
            width: 100,
            align: "center",
            sortable: false,
        },
        {
            label: "①保管場所届出義務を<br />伴う検査等申請件数",
            name: "SINSEI_CNT",
            index: "SINSEI_CNT",
            width: 100,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            me.ToDivision();
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9)
                            ) {
                                me.ToDivision();
                            }
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            label: "②警察への保管<br />場所届出件数",
            name: "TODOKE_CNT",
            index: "TODOKE_CNT",
            width: 100,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            me.ToDivision();
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9)
                            ) {
                                me.ToDivision();
                            }
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            label: "③ユーザ自身が<br />届出し確認した件数",
            name: "KAKUNIN_CNT",
            index: "KAKUNIN_CNT",
            width: 100,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            me.ToDivision();
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9)
                            ) {
                                me.ToDivision();
                            }
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            label: "届出率(％)<br />（②＋③）÷①",
            name: "SYASYU_RYKNM",
            classes: "CELL_SUM_R",
            index: "SYASYU_RYKNM",
            width: 100,
            align: "right",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE240ReportPlaceCntEntry.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE240ReportPlaceCntEntry.btnLogin",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE240ReportPlaceCntEntry.btnSearch",
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
    //表示ボタンクリック
    $(".HMTVE240ReportPlaceCntEntry.btnSearch").click(function () {
        me.btnSearch_click();
    });
    //登録ボタンクリック
    $(".HMTVE240ReportPlaceCntEntry.btnLogin").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.last_selected_id);
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = function () {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id);
        };
        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id);
        };
        me.clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
    });
    //消除ボタンクリック
    $(".HMTVE240ReportPlaceCntEntry.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
    });
    //選択行変更
    $(".HMTVE240ReportPlaceCntEntry.ddlMonth").change(function () {
        me.ddlMonth_SelectedIndexChanged();
    });
    //テキスト変更
    $(".HMTVE240ReportPlaceCntEntry.txtTitle").change(function () {
        me.txtTitle_TextChanged();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 770);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 82);
        $(me.grid_id).jqGrid("bindKeys");
        me.total_count["Classification"] = "5.合　　　計";
        $(me.grid_id).closest(".ui-jqgrid-bdiv").css({
            "overflow-y": "hidden",
        });
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").hide();
        var url = me.sys_id + "/" + me.id + "/" + "pageLoad";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }

                $(".HMTVE240ReportPlaceCntEntry.btnSearch").button("disable");
                return;
            }
            $(".HMTVE240ReportPlaceCntEntry.strKbn").html(
                result["data"]["date"].substring(0, 2)
            );
            for (var i = 1; i <= 12; i++) {
                if (i < 10) {
                    $("<option></option>")
                        .val("0" + i)
                        .text("0" + i)
                        .appendTo(".HMTVE240ReportPlaceCntEntry.ddlMonth");
                } else {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .appendTo(".HMTVE240ReportPlaceCntEntry.ddlMonth");
                }
            }

            //タイトルを表示する
            $(".HMTVE240ReportPlaceCntEntry.ddlMonth").val(
                result["data"]["date"].substring(4, 6)
            );
            $(".HMTVE240ReportPlaceCntEntry.txtTitle").val(
                result["data"]["date"].substring(2, 4)
            );
            if (result["data"]["BUSYO_RYKNM"]) {
                $(".HMTVE240ReportPlaceCntEntry.lblShopName").val(
                    result["data"]["BUSYO_RYKNM"]
                );
            }
            if (result["data"]["SyainNM"]) {
                $(".HMTVE240ReportPlaceCntEntry.lblReporterName").val(
                    result["data"]["SyainNM"]
                );
            }
            me.fncJqgrid();
        };
        me.ajax.send(url, "", 0);
    };

    //jqgrid 初期话
    me.fncJqgrid = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    //ヘッダークリック以外

                    if (rowid && rowid != me.last_selected_id) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.last_selected_id,
                            null,
                            "clientArray"
                        );
                        me.last_selected_id = rowid;
                    }
                    if (cellIndex < 1||cellIndex == 4) {
                        //when click 'td' the first 'editble cell' focus
                        cellIndex = 1;
                    }
                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                } else {
                    if (rowid && rowid != me.last_selected_id) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.last_selected_id,
                            null,
                            "clientArray"
                        );
                        me.last_selected_id = rowid;
                    }
                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }
                gdmz.common.jqgrid.setKeybordEvents(me.grid_id, e, rowid);

                //靠右
                $(me.grid_id).find(".align_right").css("text-align", "right");
            },
        });
    };
    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    me.btnSearch_click = function () {
        //店舗名を抽出する
        if ($.trim($(".HMTVE240ReportPlaceCntEntry.lblShopName").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HMTVE240ReportPlaceCntEntry.ddlMonth");
            $(".HMTVE240ReportPlaceCntEntry.btnSearch").button("disable");
            me.clsComFnc.FncMsgBox("W9999", "登録店舗が確定できません。");
            return;
        }
        var data = {
            nenfetu:
                $(".HMTVE240ReportPlaceCntEntry.strKbn").html() +
                $(".HMTVE240ReportPlaceCntEntry.txtTitle").val() +
                $(".HMTVE240ReportPlaceCntEntry.ddlMonth").val(),
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE240ReportPlaceCntEntry.btnSearch"
                );
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").show();

            var objDR = $(me.grid_id).jqGrid("getRowData");
            if (result["allnum"]) {
                me.ExpressManageLocale(objDR);
            } else {
                me.total_count["SINSEI_CNT"] = "";
                me.total_count["TODOKE_CNT"] = "";
                me.total_count["KAKUNIN_CNT"] = "";
                me.total_count["SYASYU_RYKNM"] = "";
            }
            $(me.grid_id).jqGrid("footerData", "set", me.total_count);
            $(".HMTVE240_CELL_TITLE_BLUE_C")
                .css(
                    "background",
                    "#16b1e9  url(css/jquery/images/ui-bg_gloss-wave_75_16b1e9_500x100.png) 50% 50% repeat-x"
                )
                .css("border-color", "#77d5f7")
                .css("font-weight", "bold")
                .css("color", "#222222");
            if (result["kakuteiFLG"]) {
                //Ⅰ.①の取得データ("KAKUTEI_FLG")＝"1"の場合
                if (result["kakuteiFLG"] == "1") {
                    me.jqgridReadOnly = false;
                    //登録ボタン、削除ボタンを不活性(Enabled=False)にする
                    $(".HMTVE240ReportPlaceCntEntry.btnDelete").button(
                        "disable"
                    );
                    $(".HMTVE240ReportPlaceCntEntry.btnLogin").button(
                        "disable"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に出力が行われていますので、登録は出来ません"
                    );
                    return;
                }
                //Ⅱ.①の取得データ件数＝０件　又は　①の取得データ("KAKUTEI_FLG")="0"の場合
                else if (result["kakuteiFLG"] == "0") {
                    me.jqgridReadOnly = true;
                    //登録ボタン、削除ボタンを不活性(Enabled=True)にする
                    $(".HMTVE240ReportPlaceCntEntry.btnDelete").button(
                        "enable"
                    );
                    $(".HMTVE240ReportPlaceCntEntry.btnLogin").button("enable");
                    $(me.grid_id).jqGrid("setSelection", 0);
                } else {
                    me.jqgridReadOnly = true;
                    //登録ボタン、削除ボタンを不活性(Enabled=True)にする
                    $(".HMTVE240ReportPlaceCntEntry.btnDelete").button(
                        "enable"
                    );
                    $(".HMTVE240ReportPlaceCntEntry.btnLogin").button("enable");
                    $(me.grid_id).jqGrid("setSelection", 0);
                }
            } else {
                me.jqgridReadOnly = true;
                $(".HMTVE240ReportPlaceCntEntry.btnDelete").button("enable");
                $(".HMTVE240ReportPlaceCntEntry.btnLogin").button("enable");
                $(me.grid_id).jqGrid("setSelection", 0);
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    //**********************************************************************
    //処 理 名：登録ボタンのイベント
    //関 数 名：btnLogin_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：データを改修します
    //**********************************************************************
    me.btnLogin_Click = function () {
        if (!me.checkYear()) {
            return;
        }
        checkResult = me.Check_btnLogin();
        if (!checkResult["result"]) {
            me.clsComFnc.ObjFocus = checkResult["key"];
            me.clsComFnc.FncMsgBox("W9999", checkResult["data"]);
            return;
        }
        me.btnLogin_execute();
    };
    me.checkYear = function () {
        if ($(".HMTVE240ReportPlaceCntEntry.txtTitle").val().length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE240ReportPlaceCntEntry.txtTitle");
            me.clsComFnc.FncMsgBox("W9999", "登録年を入力してください。");
            return false;
        } else if (
            $(".HMTVE240ReportPlaceCntEntry.txtTitle").val().length !== 2
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE240ReportPlaceCntEntry.txtTitle");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "登録年は不正な値が入力されています。"
            );
            return false;
        }
        return true;
    };
    me.btnLogin_execute = function () {
        var url = "HMTVE/HMTVE240ReportPlaceCntEntry/btnLoginClick";
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var data = {
            nenfetu:
                $(".HMTVE240ReportPlaceCntEntry.strKbn").html() +
                $(".HMTVE240ReportPlaceCntEntry.txtTitle").val() +
                $(".HMTVE240ReportPlaceCntEntry.ddlMonth").val(),
        };
        data["tableDate"] = objDR;
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").hide();
                if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").hide();
                $(me.grid_id).jqGrid("clearGridData");
            };
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：登録ボタンの入力
    //関 数 名：Check_btnLogin
    //引    数：無し
    //戻 り 値：なし
    //処理説明：桁数チェックを行う、整合性チェックを行う
    //**********************************************************************
    me.Check_btnLogin = function () {
        var result = [];
        result["result"] = true;
        var lengthResult = me.CheckLength();
        if (!lengthResult["result"]) {
            return lengthResult;
        }
        var numberResult = me.LogicNumber();
        if (!numberResult["result"]) {
            return numberResult;
        }
        return result;
    };
    //**********************************************************************
    //処 理 名：桁数チェック
    //関 数 名：CheckLength
    //引    数：なし
    //戻 り 値：なし
    //処理説明：桁数チェックを行う
    //**********************************************************************
    me.CheckLength = function () {
        var result = [];
        result["key"] = "";
        result["data"] = "";
        result["result"] = false;
        $(me.grid_id).jqGrid("saveRow", me.last_selected_id);
        var allArr = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < allArr.length; i++) {
            if (
                me.clsComFnc.GetByteCount($.trim(allArr[i]["SINSEI_CNT"])) > 6
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_SINSEI_CNT");
                result["data"] =
                    "①保管場所届出義務を伴う検査等申請件数は指定されている桁数をオーバーしています。";
                return result;
            }
            if (
                me.clsComFnc.GetByteCount($.trim(allArr[i]["TODOKE_CNT"])) > 6
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_TODOKE_CNT");
                result["data"] =
                    "②警察への保管場所届出件数は指定されている桁数をオーバーしています。";
                return result;
            }
            if (
                me.clsComFnc.GetByteCount($.trim(allArr[i]["KAKUNIN_CNT"])) > 6
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_KAKUNIN_CNT");
                result["data"] =
                    "③ユーザ自身が届出し確認した件数は指定されている桁数をオーバーしています。";
                return result;
            }
        }
        result["result"] = true;
        return result;
    };
    //**********************************************************************
    //処 理 名：整合性チェック
    //関 数 名：LogicNumber
    //引    数：なし
    //戻 り 値：array
    //処理説明：整合性チェックを行う
    //**********************************************************************
    me.LogicNumber = function () {
        var result = [];
        result["key"] = "";
        result["data"] = "";
        result["result"] = false;
        var allArr = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < allArr.length; i++) {
            if (
                (!me.isNumeric(allArr[i]["SINSEI_CNT"]) &&
                    allArr[i]["SINSEI_CNT"] != "") ||
                me.clsComFnc.GetByteCount($.trim(allArr[i]["SINSEI_CNT"])) !=
                    $.trim(allArr[i]["SINSEI_CNT"]).length ||
                allArr[i]["SINSEI_CNT"].split(".").length > 1 ||
                allArr[i]["SINSEI_CNT"].split("+").length > 1 ||
                allArr[i]["SINSEI_CNT"].split("-").length > 1
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_SINSEI_CNT");
                result["data"] = "入力されている値が不正です。";
                return result;
            }
            if (
                (!me.isNumeric(allArr[i]["TODOKE_CNT"]) &&
                    allArr[i]["TODOKE_CNT"] != "") ||
                me.clsComFnc.GetByteCount($.trim(allArr[i]["TODOKE_CNT"])) !=
                    $.trim(allArr[i]["TODOKE_CNT"]).length ||
                allArr[i]["TODOKE_CNT"].split(".").length > 1 ||
                allArr[i]["TODOKE_CNT"].split("+").length > 1 ||
                allArr[i]["TODOKE_CNT"].split("-").length > 1
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_TODOKE_CNT");
                result["data"] = "入力されている値が不正です。";
                return result;
            }
            if (
                (!me.isNumeric(allArr[i]["KAKUNIN_CNT"]) &&
                    allArr[i]["KAKUNIN_CNT"] != "") ||
                me.clsComFnc.GetByteCount($.trim(allArr[i]["KAKUNIN_CNT"])) !=
                    $.trim(allArr[i]["KAKUNIN_CNT"]).length ||
                allArr[i]["KAKUNIN_CNT"].split(".").length > 1 ||
                allArr[i]["KAKUNIN_CNT"].split("+").length > 1 ||
                allArr[i]["KAKUNIN_CNT"].split("-").length > 1
            ) {
                $(me.grid_id).jqGrid("setSelection", i + 1, true);
                result["key"] = $("#" + (i + 1) + "_KAKUNIN_CNT");
                result["data"] = "入力されている値が不正です。";
                return result;
            }
        }
        result["result"] = true;
        return result;
    };
    me.isNumeric = function (obj) {
        return !isNaN(parseFloat(obj)) && isFinite(obj);
    };
    //**********************************************************************
    //処 理 名：保管場所届出件数データ
    //関 数 名：ExpressManageLocale
    //引    数：なし
    //戻 り 値：なし
    //処理説明：保管場所届出件数データを表示する
    //**********************************************************************
    me.ExpressManageLocale = function (objDR) {
        for (var i = 0; i < objDR.length; i++) {
            objDR[i]["SYASYU_RYKNM"] =
                me.getResult(
                    objDR[i]["TODOKE_CNT"],
                    objDR[i]["KAKUNIN_CNT"],
                    objDR[i]["SINSEI_CNT"]
                ) + "%";
            if (objDR["SYASYU_RYKNM"] == "%") {
                objDR["SYASYU_RYKNM"] = "";
            }
            $(me.grid_id).jqGrid(
                "setCell",
                i,
                "SYASYU_RYKNM",
                objDR[i]["SYASYU_RYKNM"]
            );
        }
        me.getSum(objDR, "load");
    };
    //**********************************************************************
    //処 理 名：届出率
    //関 数 名：getResult
    //引 数 1 ：str1
    //引 数 2 ：str2
    //引 数 3 ：str3
    //戻 り 値：なし
    //処理説明：届出率を取ります
    //**********************************************************************
    me.getResult = function (str1, str2, str3) {
        var result = "";
        if (!str1) {
            str1 = "0";
        }
        if (!str2) {
            str2 = "0";
        }
        if (str3 === "") {
            return "";
        }
        if (me.ckeckint(str1) && me.ckeckint(str2) && me.ckeckint(str3)) {
            str1 = parseInt(str1);
            str2 = parseInt(str2);
            str3 = parseInt(str3);
            if (str3 == 0) {
                return "0";
            } else {
                var intResult = me.formatNum(((str1 + str2) / str3) * 100, 1);
                result = intResult + "";
            }
        }
        return result;
    };
    //**********************************************************************
    //処 理 名：四捨五入
    //関 数 名：FncRoundA
    //引 数  ：Decimal  - 元の数値 Integer  - 四捨五入位数
    //戻 り 値：Decimal
    //**********************************************************************
    // me.FncRoundA = function(dValue, iDigits)
    // {
    // var dCoef = Math.pow(10, iDigits - 1);
    // if (dValue > 0)
    // {
    // console.log(Math.floor(dValue + 0.5 / dCoef, iDigits));
    // return Math.floor(dValue + 0.5 / dCoef, iDigits);
    // }
    // else
    // {
    // return Math.ceil((dValue * dCoef) - 0.5) / dCoef;
    // }
    // };
    me.formatNum = function (Num1, Num2) {
        if (isNaN(Num1) || isNaN(Num2)) {
            return 0;
        } else {
            Num1 = Num1.toString();
            Num2 = parseInt(Num2);
            if (Num1.indexOf(".") == -1) {
                return Num1;
            } else {
                var b = Num1.substring(0, Num1.indexOf(".") + Num2 + 1);
                var c = Num1.substring(
                    Num1.indexOf(".") + Num2 + 1,
                    Num1.indexOf(".") + Num2 + 2
                );
                if (c == "") {
                    return b;
                } else {
                    if (parseInt(c) < 5) {
                        return b;
                    } else {
                        return (
                            (Math.round(parseFloat(b) * Math.pow(10, Num2)) +
                                Math.round(
                                    parseFloat(
                                        Math.pow(0.1, Num2)
                                            .toString()
                                            .substring(
                                                0,
                                                Math.pow(0.1, Num2)
                                                    .toString()
                                                    .indexOf(".") +
                                                    Num2 +
                                                    1
                                            )
                                    ) * Math.pow(10, Num2)
                                )) /
                            Math.pow(10, Num2)
                        );
                    }
                }
            }
        }
    };
    //**********************************************************************
    //処 理 名：合計
    //関 数 名：getSum
    //引 数  ：Array,type
    //戻 り 値：なし
    //処理説明：合計を取ります
    //**********************************************************************
    me.getSum = function (allArr) {
        var SINSEI_CNT = 0;
        var TODOKE_CNT = 0;
        var KAKUNIN_CNT = 0;
        me.total_count["SINSEI_CNT"] = 0;
        me.total_count["TODOKE_CNT"] = 0;
        me.total_count["KAKUNIN_CNT"] = 0;
        var countNull = [];
        countNull["SINSEI_CNT"] = "";
        countNull["TODOKE_CNT"] = "";
        countNull["KAKUNIN_CNT"] = "";
        for (var i = 0; i < allArr.length; i++) {
            // if (type == "editRow")
            // {
            // if (me.last_selected_id == i)
            // {
            // continue;
            // }
            // }

            if (
                allArr[i]["SINSEI_CNT"] != "" &&
                me.checkNumber(allArr[i]["SINSEI_CNT"])
            ) {
                countNull["SINSEI_CNT"] = allArr[i]["SINSEI_CNT"];
            }
            if (
                allArr[i]["TODOKE_CNT"] != "" &&
                me.checkNumber(allArr[i]["TODOKE_CNT"])
            ) {
                countNull["TODOKE_CNT"] = allArr[i]["TODOKE_CNT"];
            }
            if (
                allArr[i]["KAKUNIN_CNT"] != "" &&
                me.checkNumber(allArr[i]["KAKUNIN_CNT"])
            ) {
                countNull["KAKUNIN_CNT"] = allArr[i]["KAKUNIN_CNT"];
            }
            SINSEI_CNT = allArr[i]["SINSEI_CNT"];

            SINSEI_CNT = parseInt(SINSEI_CNT);

            if (!isNaN(SINSEI_CNT)) {
                me.total_count["SINSEI_CNT"] += SINSEI_CNT;
            }

            TODOKE_CNT = allArr[i]["TODOKE_CNT"];

            TODOKE_CNT = parseInt(TODOKE_CNT);

            if (!isNaN(TODOKE_CNT)) {
                me.total_count["TODOKE_CNT"] += TODOKE_CNT;
            }

            KAKUNIN_CNT = allArr[i]["KAKUNIN_CNT"];

            KAKUNIN_CNT = parseInt(KAKUNIN_CNT);

            if (!isNaN(KAKUNIN_CNT)) {
                me.total_count["KAKUNIN_CNT"] += KAKUNIN_CNT;
            }
        }
        // if (type == "editRow")
        // {
        // TODOKE_CNT = parseInt($("#" + me.last_selected_id + "_TODOKE_CNT").val());
        // KAKUNIN_CNT = parseInt($("#" + me.last_selected_id + "_KAKUNIN_CNT").val());
        // SINSEI_CNT = parseInt($("#" + me.last_selected_id + "_SINSEI_CNT").val());
        // if (!isNaN(TODOKE_CNT))
        // {
        // me.total_count['TODOKE_CNT'] += TODOKE_CNT;
        // }
        // if (!isNaN(SINSEI_CNT))
        // {
        // me.total_count['SINSEI_CNT'] += SINSEI_CNT;
        // }
        // if (!isNaN(KAKUNIN_CNT))
        // {
        // me.total_count['KAKUNIN_CNT'] += KAKUNIN_CNT;
        // }
        // if (TODOKE_CNT != "" && me.checkNumber(TODOKE_CNT))
        // {
        // countNull['TODOKE_CNT'] = TODOKE_CNT;
        // }
        //
        // if (SINSEI_CNT != "" && me.checkNumber(SINSEI_CNT))
        // {
        // countNull['SINSEI_CNT'] = SINSEI_CNT;
        // }
        // if (KAKUNIN_CNT != "" && me.checkNumber(KAKUNIN_CNT))
        // {
        // countNull['KAKUNIN_CNT'] = KAKUNIN_CNT;
        // }
        // }
        if (countNull["KAKUNIN_CNT"] == "") {
            me.total_count["KAKUNIN_CNT"] = "";
        }
        if (countNull["TODOKE_CNT"] == "") {
            me.total_count["TODOKE_CNT"] = "";
        }
        if (countNull["SINSEI_CNT"] == "") {
            me.total_count["SINSEI_CNT"] = "";
        }
        me.total_count["SYASYU_RYKNM"] =
            me.getResult(
                me.total_count["TODOKE_CNT"],
                me.total_count["KAKUNIN_CNT"],
                me.total_count["SINSEI_CNT"]
            ) + "%";
        if (me.total_count["SYASYU_RYKNM"] == "%") {
            me.total_count["SYASYU_RYKNM"] = "";
        }
        $(me.grid_id).jqGrid("footerData", "set", me.total_count);
    };
    //**********************************************************************
    //処 理 名：合計，届出率
    //関 数 名：ToDivision
    //引 数  ：なし
    //戻 り 値：なし
    //処理説明：合計を取ります
    //**********************************************************************
    me.ToDivision = function () {
        var TODOKE_CNT = $("#" + me.last_selected_id + "_TODOKE_CNT").val();
        var KAKUNIN_CNT = $("#" + me.last_selected_id + "_KAKUNIN_CNT").val();
        var SINSEI_CNT = $("#" + me.last_selected_id + "_SINSEI_CNT").val();
        var SYASYU_RYKNM =
            me.getResult(TODOKE_CNT, KAKUNIN_CNT, SINSEI_CNT) + "%";
        if (SYASYU_RYKNM == "%") {
            SYASYU_RYKNM = "&nbsp";
        }
        $(me.grid_id).jqGrid(
            "setCell",
            me.last_selected_id,
            "SYASYU_RYKNM",
            SYASYU_RYKNM
        );
        var allArr = $(me.grid_id).jqGrid("getRowData");
        allArr[me.last_selected_id]["TODOKE_CNT"] = TODOKE_CNT;
        allArr[me.last_selected_id]["KAKUNIN_CNT"] = KAKUNIN_CNT;
        allArr[me.last_selected_id]["SINSEI_CNT"] = SINSEI_CNT;
        me.getSum(allArr, "editRow");
    };
    //**********************************************************************
    //処 理 名：削除ボタンのイベント
    //関 数 名：btnDelete_Click
    //引 数  ：なし
    //戻 り 値：なし
    //処理説明：データを削除します
    //**********************************************************************
    me.btnDelete_Click = function () {
        var url = "HMTVE/HMTVE240ReportPlaceCntEntry/btnDeleteClick";
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var data = {
            nenfetu:
                $(".HMTVE240ReportPlaceCntEntry.strKbn").html() +
                $(".HMTVE240ReportPlaceCntEntry.txtTitle").val() +
                $(".HMTVE240ReportPlaceCntEntry.ddlMonth").val(),
        };
        data["tableDate"] = objDR;
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                if (result["key"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.btnSearch_click();
            };
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：コンボリスト選択行変更
    //関 数 名：ddlMonth_SelectedIndexChanged
    //引 数  ：なし
    //戻 り 値：なし
    //処理説明：月の選択されている値が変更になった場合は検索結果を非表示にする
    //**********************************************************************
    me.ddlMonth_SelectedIndexChanged = function () {
        $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").hide();
        $(me.grid_id).jqGrid("clearGridData");
    };
    //**********************************************************************
    //処 理 名：テキスト変更
    //関 数 名：txtTitle_TextChanged
    //引 数  ：なし
    //戻 り 値：なし
    //処理説明：月の選択されている値が変更になった場合は検索結果を非表示にする
    //**********************************************************************
    me.txtTitle_TextChanged = function () {
        $(".HMTVE240ReportPlaceCntEntry.PnlCsvOutTableRow").hide();
        $(me.grid_id).jqGrid("clearGridData");
    };

    me.ckeckint = function (str) {
        var patrn = /^[0-9]*?$/;
        if (patrn.exec(str)) {
            return true;
        }
        return false;
    };
    //Numberチェック
    me.checkNumber = function (theObj) {
        theObj = Number(theObj);
        var reg = /^[0-9]+.?[0-9]*$/;
        if (reg.test(theObj)) {
            return true;
        }
        return false;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE240ReportPlaceCntEntry =
        new HMTVE.HMTVE240ReportPlaceCntEntry();
    o_HMTVE_HMTVE240ReportPlaceCntEntry.load();
    o_HMTVE_HMTVE.HMTVE240ReportPlaceCntEntry =
        o_HMTVE_HMTVE240ReportPlaceCntEntry;
});
