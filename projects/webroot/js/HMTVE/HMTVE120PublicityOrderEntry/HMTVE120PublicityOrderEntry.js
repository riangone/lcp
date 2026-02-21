/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															   担当
 * YYYYMMDD						#ID							　　　　XXXXXX															  GSDL
 * 20240329        受入検証.xlsx NO5     見出し部分の表示内容が不正             	  		YIN
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE120PublicityOrderEntry");

HMTVE.HMTVE120PublicityOrderEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE120PublicityOrderEntry";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE120PublicityOrderEntry_tblSubMain";
    me.g_url = me.sys_id + "/" + me.id + "/getCreatDateEx";
    me.grid_load = true;
    me.lastsel = 0;
    me.option = {
        caption: "",
        rownumbers: false,
        rowNum: 0,
        multiselect: false,
        colModel: me.colModel,
    };
    me.colModel = [
        {
            name: "START_DATE",
            label: "",
            index: "START_DATE",
            sortable: false,
            hidden: true,
        },
        {
            name: "HIDUKE_IVENT_NM",
            label: "日時/展示会名",
            index: "HIDUKE_IVENT_NM",
            width: me.ratio === 1.5 ? 200 : 245,
            align: "center",
            sortable: false,
            //タイトルのclass
            labelClasses: "HMTVE120PublicityOrderEntry_tblSubMain_hasBack",
            classes: "hasBack",
        },
        {
            name: "ORDER_VAL1",
            // 20240329 YIN UPD S
            // label: '',
            label: "ＤＢセット<br /> @150",
            // 20240329 YIN UPD E
            index: "ORDER_VAL1",
            width: me.ratio === 1.5 ? 120 : 130,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "align_right",
                maxlength: 5,
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
        },
        {
            name: "ORDER_VAL2",
            // 20240329 YIN UPD S
            // label: '',
            label: "DH<br /> @100",
            // 20240329 YIN UPD E
            index: "ORDER_VAL2",
            width: me.ratio === 1.5 ? 120 : 130,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "align_right",
                maxlength: 5,
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
        },
        {
            name: "ORDER_VAL3",
            // 20240329 YIN UPD S
            // label: '',
            label: "来場プレゼント<br /> @300",
            // 20240329 YIN UPD E
            index: "ORDER_VAL3",
            width: me.ratio === 1.5 ? 120 : 130,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "align_right",
                maxlength: 5,
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
        },
        {
            name: "TARGET",
            label: "広告媒体ガイド<br />(ターゲット)",
            index: "TARGET",
            width: 130,
            align: "center",
            sortable: false,
        },
        {
            name: "BIKOU",
            label: "備考",
            index: "BIKOU",
            width: 265,
            align: "left",
            sortable: false,
        },
        {
            name: "CREATE_DATE",
            label: "",
            index: "CREATE_DATE",
            sortable: false,
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "",
            index: "UPD_DATE",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE120PublicityOrderEntry.Button",
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
    $(".HMTVE120PublicityOrderEntry.btnView").click(function () {
        me.btnView_Click();
    });

    // 注文内容確認画面へ
    $(".HMTVE120PublicityOrderEntry.btnCheck.Button").click(function () {
        me.btnCheck_Click();
    });

    $(".HMTVE120PublicityOrderEntry.DdlYear").change(function () {
        me.selectedIndexChanged();
    });

    $(".HMTVE120PublicityOrderEntry.DdlMonth").change(function () {
        me.selectedIndexChanged();
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
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        // 表示設定
        me.pageSet();
        $(".HMTVE120PublicityOrderEntry.lblStore").val("");
        //コンボリストを設定する
        var url = me.sys_id + "/" + me.id + "/pageLoad";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                // 展示会が設定する
                if (result["data"].length > 0) {
                    me.YMSet(result["data"]);
                }
                // 店舗名を表示する
                // 画面項目No.7(店舗名)に⑦－１．抽出データ("BUSYO_RYKNM")を表示する
                me.ShopNMSet(result["shopdata"]);
                // $('.HMTVE120PublicityOrderEntry.DdlYear').trigger("focus");
            } else {
                me.clsComFnc.FncMsgBox("E9999", "データ読込に失敗しました。");
            }
        };
        me.ajax.send(url, "", 0);
    };
    // '**********************************************************************
    // '処 理 名：表示ボタンのイベント
    // '関 数 名：btnView_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：データの取得して、表示します
    // '**********************************************************************
    me.btnView_Click = function () {
        me.pageSet();

        if ($(".HMTVE120PublicityOrderEntry.lblStore").val() == "") {
            me.clsComFnc.ObjFocus = $(".HMTVE120PublicityOrderEntry.DdlYear");
            me.clsComFnc.FncMsgBox("W9999", "登録店舗が確定できません。");
            $(".HMTVE120PublicityOrderEntry.btnView").button("disable");
            return;
        }

        if (
            !$(".HMTVE120PublicityOrderEntry.DdlYear").val() ||
            !$(".HMTVE120PublicityOrderEntry.DdlMonth").val()
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE120PublicityOrderEntry.DdlYear");
            me.clsComFnc.FncMsgBox(
                "E9999",
                "出力可能な展示会宣材データは存在しません！"
            );
            return;
        }

        // 画面項目のクリア処理
        me.pageSet();

        // 登録可能年月かをチェックする
        //コンボリストを設定する
        var url = me.sys_id + "/" + me.id + "/getExCheck";
        var data = {
            NENGETU:
                $(".HMTVE120PublicityOrderEntry.DdlYear").val().toString() +
                $(".HMTVE120PublicityOrderEntry.DdlMonth").val().toString(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["data"].length > 0) {
                    if (
                        result["data"][0].hasOwnProperty("KAKUTEI_FLG") &&
                        result["data"][0]["KAKUTEI_FLG"] == "1"
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "既に出力が行われていますので、登録は出来ません"
                        );
                        return;
                    }
                }
                //データの取得
                me.creatData("HDTPUBLICITYDATA");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '********************************************************************************
    // '処 理 名：登録ボタンのイベント
    // '関 数 名：btnCheck_Click
    // '戻 り 値：なし
    // '処理説明：入力チェックを行って、登録処理を行って、注文内容確認画面へ遷移する
    // '********************************************************************************
    me.btnCheck_Click = function () {
        //入力チェック
        if (me.btnInputCheck() == false) {
            return;
        }

        $(me.grid_id).jqGrid("resetSelection");
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var gridData = $(me.grid_id).jqGrid("getRowData");

        // 登録可能年月かをチェック
        // 宣材確定データを取得
        //コンボリストを設定する
        var url = me.sys_id + "/" + me.id + "/btnCheckClick";
        var data = {
            NENGETU:
                $(".HMTVE120PublicityOrderEntry.DdlYear").val().toString() +
                $(".HMTVE120PublicityOrderEntry.DdlMonth").val().toString(),
            ROWS: gridData,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //エラーがない場合
                //注文内容確認画面へ遷移する
                var frmId = "HMTVE130PublicityOrderConfirm";
                var dialogdiv = "HMTVE120PublicityOrderEntryDialogDiv";
                var $rootDiv = $(".HMTVE120PublicityOrderEntry.HMTVE-content");

                if ($("#" + dialogdiv).length > 0) {
                    $("#" + dialogdiv).remove();
                }
                $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
                $("<div style='display:none;'></div>")
                    .attr("id", "RtnCD")
                    .insertAfter($rootDiv);

                $("<div style='display:none;'></div>")
                    .attr("id", "IVENTYM")
                    .insertAfter($rootDiv);
                var $IVENTYM = $rootDiv.parent().find("#IVENTYM");
                $IVENTYM.html(
                    $(".HMTVE120PublicityOrderEntry.DdlYear").val().toString() +
                        "/" +
                        $(".HMTVE120PublicityOrderEntry.DdlMonth")
                            .val()
                            .toString()
                );

                var RtnCD = $rootDiv.parent().find("#RtnCD");

                var url = me.sys_id + "/" + frmId;
                me.ajax.send(url, "", 0);
                me.ajax.receive = function (result) {
                    function before_close() {
                        //returnの場合
                        if (RtnCD.html() == 0) {
                            //データの取得
                            me.creatData("WK_HDTPUBLICITYDATA");
                        } else {
                            me.Page_Load();
                        }
                        RtnCD.remove();
                        $("#" + dialogdiv).remove();
                    }

                    $("#" + dialogdiv).hide();
                    $("#" + dialogdiv).append(result);
                    o_HMTVE_HMTVE.HMTVE120PublicityOrderEntry.HMTVE130PublicityOrderConfirm.before_close =
                        before_close;
                };
            } else {
                if (result["error"] == "E9999") {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel);
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "既に出力が行われていますので、登録は出来ません"
                    );
                    return;
                } else {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel);
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：jqgridの初期化
	 '関 数 名：fncJqgrid
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"] == "W9999") {
            me.clsComFnc.FncMsgBox("W9999", "データがありません。");
            me.pageSet();
            return;
        } else if (result["error"] != "") {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            me.pageSet();
            return;
        }

        //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得
        me.ExHeaderSet(result["dataHd"]);
        //回収期限ﾃﾞｰﾀを取得する
        if (result["dataDt"].length > 0) {
            // 画面項目NO9.回収期限に③－１の取得データをセットする
            $(".HMTVE120PublicityOrderEntry.lblDate").text(
                result["dataDt"][0]["KIGEN_YM"].substring(0, 4) +
                    "年" +
                    result["dataDt"][0]["KIGEN_YM"].substring(4, 6) +
                    "月" +
                    result["dataDt"][0]["KIGEN_YM"].substring(6, 8) +
                    "日 までに"
            );
        }
        //画面制御
        me.btnViewPageSet(result["dataHd"], result["rows"]);
        // me.gridFocus();
    };

    // '**********************************************************************
    // '処 理 名：データの新規作成
    // '関 数 名：creatData
    // '戻 り 値：無し
    // '処理説明：データの取得して、表示します
    // '**********************************************************************
    me.creatData = function (TABLENM) {
        //展示会テーブルの生成
        //展示会データ取得
        var data = {
            NENGETU:
                $(".HMTVE120PublicityOrderEntry.DdlYear").val().toString() +
                $(".HMTVE120PublicityOrderEntry.DdlMonth").val().toString(),
            TABLENM: TABLENM,
        };
        if (me.grid_load) {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                "",
                "",
                me.option,
                data,
                me.complete_fun
            );
            me.initGrid();

            me.grid_load = false;
        } else {
            $(me.grid_id).jqGrid("clearGridData");
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
            // $(me.grid_id).jqGrid('bindKeys');
        }
    };
    // '**************************************************************************
    // '処 理 名：入力チェック
    // '関 数 名：btnInputCheck
    // '引 数 １：無し
    // '戻 り 値：なし
    // '処理説明：入力チェック
    // '**************************************************************************
    me.btnInputCheck = function () {
        var allData = $(me.grid_id).jqGrid("getRowData");

        for (var i = 0; i < allData.length; i++) {
            var row = allData[i];

            if (i == me.lastsel) {
                //桁数チェック
                for (var j = 1; j < 4; j++) {
                    if (
                        row["ORDER_VAL" + j].indexOf('<input type="text"') >=
                            0 &&
                        me.clsComFnc.GetByteCount(
                            $.trim(
                                $(
                                    ".HMTVE120PublicityOrderEntry #" +
                                        me.lastsel +
                                        "_" +
                                        "ORDER_VAL" +
                                        j
                                ).val()
                            )
                        ) > 5
                    ) {
                        //if (me.colModel[j]['editable'] && me.clsComFnc.GetByteCount($.trim($(".HMTVE120PublicityOrderEntry #" + me.lastsel + "_" + me.colModel[j]['index']).val())) > 5)
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE120PublicityOrderEntry #" +
                                me.lastsel +
                                "_" +
                                "ORDER_VAL" +
                                j
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            me.colModel[j]["label"].replace("<br />", "") +
                                "は指定されている桁数をオーバーしています。"
                        );
                        return false;
                    }
                }

                //整合性チェック
                for (var j = 1; j < 4; j++) {
                    var val = $(
                        ".HMTVE120PublicityOrderEntry #" +
                            me.lastsel +
                            "_" +
                            "ORDER_VAL" +
                            j
                    ).val();
                    if (
                        (row["ORDER_VAL" + j].indexOf('<input type="text"') >=
                            0 &&
                            $.trim(val) != "" &&
                            /^\d+$/.test($.trim(val)) == false) ||
                        $.trim(val).indexOf(".") > -1 ||
                        $.trim(val).indexOf("-") > -1 ||
                        $.trim(val).indexOf("+") > -1
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE120PublicityOrderEntry #" +
                                me.lastsel +
                                "_" +
                                "ORDER_VAL" +
                                j
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                        return false;
                    }
                }
            } else {
                //桁数チェック
                for (var j = 1; j < 4; j++) {
                    if (
                        me.clsComFnc.GetByteCount(
                            $.trim(row["ORDER_VAL" + j])
                        ) > 5
                    ) {
                        $(me.grid_id).jqGrid("setSelection", i);
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE120PublicityOrderEntry #" +
                                i +
                                "_" +
                                "ORDER_VAL" +
                                j
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            me.colModel[j]["label"].replace("<br />", "") +
                                "は指定されている桁数をオーバーしています。"
                        );
                        return false;
                    }
                }

                //整合性チェック
                for (var j = 1; j < 4; j++) {
                    var val = row["ORDER_VAL" + j];
                    if (
                        ($.trim(val) != "" &&
                            /^\d+$/.test($.trim(val)) == false) ||
                        $.trim(val).indexOf(".") > -1 ||
                        $.trim(val).indexOf("-") > -1 ||
                        $.trim(val).indexOf("+") > -1
                    ) {
                        $(me.grid_id).jqGrid("setSelection", i);
                        me.clsComFnc.ObjFocus = $(
                            ".HMTVE120PublicityOrderEntry #" +
                                me.lastsel +
                                "_" +
                                "ORDER_VAL" +
                                j
                        );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    // '**********************************************************************
    // '処 理 名：年月コンボリストの設定
    // '関 数 名：YMSet
    // '引 数 １：(I)objReader
    // '戻 り 値：なし
    // '処理説明：コンボリストに日付を設定する
    // '**********************************************************************
    me.YMSet = function (objReader) {
        if (!objReader[0]["IVENTMAX"]) {
            me.clsComFnc.ObjFocus = $(".HMTVE120PublicityOrderEntry.DdlYear");
            me.clsComFnc.FncMsgBox(
                "E9999",
                "展示会が設定されていません。先に展示会データ登録を行ってください！"
            );
            return;
        }
        // コンボリストに日付を設定する
        var max =
            objReader[0]["IVENTMAX"].substring(0, 4) +
            "/" +
            objReader[0]["IVENTMAX"].substring(4, 6);

        var min =
            objReader[0]["IVENTMIN"].substring(0, 4) +
            "/" +
            objReader[0]["IVENTMIN"].substring(4, 6);

        var td = objReader[0]["TD"];

        $(".HMTVE120PublicityOrderEntry.DdlYear").empty();
        for (
            var k = parseInt(min.substring(0, 4));
            k <= parseInt(max.substring(0, 4));
            k++
        ) {
            $("<option></option>")
                .val(k)
                .text(k)
                .prependTo(".HMTVE120PublicityOrderEntry.DdlYear");
        }

        $(".HMTVE120PublicityOrderEntry.DdlMonth").empty();
        //月のコンボリストを設定する
        for (var m = 1; m <= 12; m++) {
            if (m < 10) {
                $("<option></option>")
                    .val("0" + m)
                    .text("0" + m)
                    .appendTo(".HMTVE120PublicityOrderEntry.DdlMonth");
            } else {
                $("<option></option>")
                    .val(m)
                    .text(m)
                    .appendTo(".HMTVE120PublicityOrderEntry.DdlMonth");
            }
        }

        if (min <= td && td <= max) {
            $(".HMTVE120PublicityOrderEntry.DdlYear").val(td.substring(0, 4));
            $(".HMTVE120PublicityOrderEntry.DdlMonth").val(td.substring(5, 7));
        }

        if (max < td) {
            $(".HMTVE120PublicityOrderEntry.DdlYear").val(max.substring(0, 4));
            $(".HMTVE120PublicityOrderEntry.DdlMonth").val(max.substring(5, 7));
        }

        if (min > td) {
            $(".HMTVE120PublicityOrderEntry.DdlYear").val(min.substring(0, 4));
            $(".HMTVE120PublicityOrderEntry.DdlMonth").val(min.substring(5, 7));
        }
        $(".HMTVE120PublicityOrderEntry.DdlYear").trigger("focus");
    };
    // '**************************************************************************
    // '処 理 名：店舗名の設定
    // '関 数 名：ShopNMSet
    // '引 数 １：無し
    // '戻 り 値：なし
    // '処理説明：店舗名の設定
    // '**************************************************************************
    me.ShopNMSet = function (objReader) {
        if (objReader.length > 0) {
            $(".HMTVE120PublicityOrderEntry.lblStore").val(
                objReader[0]["BUSYO_RYKNM"]
            );
        } else {
            $(".HMTVE120PublicityOrderEntry.lblStore").val("");
        }
    };
    // '**************************************************************************
    // '処 理 名：展示会ﾍｯﾀﾞｰﾃﾞｰﾀの設定
    // '関 数 名：ExHeaderSet
    // '引 数 １：無し
    // '戻 り 値：なし
    // '処理説明：展示会ﾍｯﾀﾞｰﾃﾞｰﾀの設定
    // '**************************************************************************
    me.ExHeaderSet = function (dataSet) {
        if (dataSet.length > 0) {
            //展示会ﾍｯﾀﾞｰﾃｰﾌﾞﾙに①－１の取得データをセットする
            me.colModel[0]["label"] = "日時/展示会名";
            me.colModel[1]["label"] = dataSet[0]["COL_HED_1"];
            me.colModel[2]["label"] = dataSet[0]["COL_HED_2"];
            me.colModel[3]["label"] = dataSet[0]["COL_HED_3"];
            me.colModel[4]["label"] = "広告媒体ガイド<br />(ターゲット)";
            me.colModel[5]["label"] = "備考";
            try {
                //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_1")＝0の場合
                if (dataSet[0]["COL_HED_1"].toString() == "<br /> @") {
                    $(me.grid_id).setLabel("ORDER_VAL1", "&nbsp;");
                    for (
                        var i = 0;
                        i < $(me.grid_id).getRowData().length;
                        i++
                    ) {
                        $(me.grid_id).setCell(i, "ORDER_VAL1", " ");
                    }
                } else {
                    $(me.grid_id).setLabel(
                        "ORDER_VAL1",
                        dataSet[0]["COL_HED_1"]
                    );
                }

                //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_2")＝0の場合
                if (dataSet[0]["COL_HED_2"].toString() == "<br /> @") {
                    $(me.grid_id).setLabel("ORDER_VAL2", "&nbsp;");
                    for (
                        var i = 0;
                        i < $(me.grid_id).getRowData().length;
                        i++
                    ) {
                        $(me.grid_id).setCell(i, "ORDER_VAL2", " ");
                    }
                } else {
                    $(me.grid_id).setLabel(
                        "ORDER_VAL2",
                        dataSet[0]["COL_HED_2"]
                    );
                }

                //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_3")＝0の場合
                if (dataSet[0]["COL_HED_3"].toString() == "<br /> @") {
                    $(me.grid_id).setLabel("ORDER_VAL3", "&nbsp;");
                    for (
                        var i = 0;
                        i < $(me.grid_id).getRowData().length;
                        i++
                    ) {
                        $(me.grid_id).setCell(i, "ORDER_VAL3", " ");
                    }
                } else {
                    $(me.grid_id).setLabel(
                        "ORDER_VAL3",
                        dataSet[0]["COL_HED_3"]
                    );
                }
            } catch (ex) {
                console.log(ex);
            }
        } else {
            // 20240329 YIN INS S
            $(me.grid_id).setLabel("ORDER_VAL1", "ＤＢセット<br /> @150");
            $(me.grid_id).setLabel("ORDER_VAL2", "DH<br /> @100");
            $(me.grid_id).setLabel("ORDER_VAL3", "来場プレゼント<br /> @300");
            // 20240329 YIN INS E
        }
    };
    // '**************************************************************************
    // '処 理 名：ページロードの設定
    // '関 数 名：pageSet
    // '引 数 １：無し
    // '戻 り 値：なし
    // '処理説明：ページロードの設定
    // '**************************************************************************
    me.pageSet = function () {
        $(".HMTVE120PublicityOrderEntry.tblMain1").hide();
        $(".HMTVE120PublicityOrderEntry.TabCellDate").hide();
        $(".HMTVE120PublicityOrderEntry.btnCheck.HMS-button-pane").hide();
    };
    // '**************************************************************************
    // '処 理 名：表示ボタンの画面制御
    // '関 数 名：btnViewPageSet
    // '引 数 １：無し
    // '戻 り 値：なし
    // '処理説明：表示ボタンの画面制御
    // '**************************************************************************
    me.btnViewPageSet = function (dataSet) {
        if (dataSet.length > 0) {
            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_1")＝0の場合
            if (dataSet[0]["HANDAN_1"] == 0) {
                // 展示会ﾃｰﾌﾞﾙ_品名・単価1　の列を読取専用にする(ReadOnly=True)
                $(me.grid_id).setColProp("ORDER_VAL1", {
                    editable: false,
                });
            } else {
                $(me.grid_id).setColProp("ORDER_VAL1", {
                    editable: true,
                });
            }

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_2")＝0の場合
            if (dataSet[0]["HANDAN_2"] == 0) {
                // 展示会ﾃｰﾌﾞﾙ_品名・単価2　の列を読取専用にする(ReadOnly=True)
                $(me.grid_id).setColProp("ORDER_VAL2", {
                    editable: false,
                });
            } else {
                $(me.grid_id).setColProp("ORDER_VAL2", {
                    editable: true,
                });
            }

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_3")＝0の場合
            if (dataSet[0]["HANDAN_3"] == 0) {
                $(me.grid_id).setColProp("ORDER_VAL3", {
                    editable: false,
                });
            } else {
                $(me.grid_id).setColProp("ORDER_VAL3", {
                    editable: true,
                });
            }
        } else {
            // 20240329 YIN INS S
            $(me.grid_id).setColProp("ORDER_VAL1", {
                editable: true,
            });
            $(me.grid_id).setColProp("ORDER_VAL2", {
                editable: true,
            });
            $(me.grid_id).setColProp("ORDER_VAL3", {
                editable: true,
            });
            // 20240329 YIN INS E
        }

        $(".HMTVE120PublicityOrderEntry.tblMain1").show();
        $(".HMTVE120PublicityOrderEntry.TabCellDate").show();
        $(".HMTVE120PublicityOrderEntry.btnCheck.HMS-button-pane").show();
        $(".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td").css(
            "padding",
            "8px 2px"
        );
        $(".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td").css(
            "height",
            "40px"
        );
        $(
            ".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td.hasBack"
        ).css(
            "background",
            "#16b1e9 url(css/jquery/images/ui-bg_gloss-wave_75_16b1e9_500x100.png) 50% 50% repeat-x"
        );
        $(
            ".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td.hasBack"
        ).css("color", "#222222");
        $(".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid-htable th div").css(
            "padding",
            "5px 0px"
        );
        $(me.grid_id).jqGrid("setSelection", 0);
        // me.gridFocus();
    };
    me.gridFocus = function () {
        var idx = $(me.grid_id)
            .jqGrid("getGridParam", "colModel")
            .findIndex(function (col) {
                return col["editable"] == true;
            });
        $(
            ".HMTVE120PublicityOrderEntry #" +
                me.lastsel +
                "_" +
                me.colModel[idx]["name"]
        ).trigger("focus");
    };
    me.initGrid = function () {
        $(me.grid_id).jqGrid("bindKeys");

        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行
            onSelectRow: function (rowId, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    //ヘッダークリック以外
                    if (rowId && rowId != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowId;
                    }
                    if (cellIndex < 2 || cellIndex > 4) {
                        //when click 'td' the first 'editble cell' focus
                        cellIndex = 2;
                    }
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: cellIndex,
                    });
                } else {
                    if (rowId && rowId != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowId;
                    }
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                }
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                $(
                    ".HMTVE120PublicityOrderEntry.tblMain1 .ui-jqgrid tr.jqgrow td input"
                ).css("width", "90%");
                //靠右
                $(me.grid_id).find(".align_right").css("text-align", "right");
            },
        });
        // $(me.grid_id).jqGrid('setSelection', 0);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1018 : 1078
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 265 : 289
        );
    };
    // '**********************************************************************
    // '処 理 名：コンボリストのインデックスの変換処理
    // '関 数 名：selectedIndexChanged
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：コンボリストのインデックスの変換処理
    // '**********************************************************************
    me.selectedIndexChanged = function () {
        me.pageSet();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE120PublicityOrderEntry =
        new HMTVE.HMTVE120PublicityOrderEntry();
    o_HMTVE_HMTVE.HMTVE120PublicityOrderEntry =
        o_HMTVE_HMTVE120PublicityOrderEntry;
    o_HMTVE_HMTVE120PublicityOrderEntry.load();
});
