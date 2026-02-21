/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("HMTVE.HMTVE250ReportPlaceCntTotal");

HMTVE.HMTVE250ReportPlaceCntTotal = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE250ReportPlaceCntTotal";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE250ReportPlaceCntTotal_tblSubMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnExpressClick";
    me.reload = false;
    me.option = {
        rownumbers: false,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        loadui: "disable",
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "店舗コード",
            index: "BUSYO_CD",
            width: 112,
            align: "center",
            sortable: false,
        },
        {
            name: "BUSYO_RYKNM",
            label: "店舗名",
            index: "BUSYO_RYKNM",
            width: 260,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE250ReportPlaceCntTotal.Button",
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

    // 明細出力ボタン
    $(".HMTVE250ReportPlaceCntTotal.btnView").click(function () {
        // 入力チェックを行う
        if (!$(".HMTVE250ReportPlaceCntTotal.ddlYear").val()) {
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                if (
                    $(".HMTVE250ReportPlaceCntTotal.lblItemnum").text() == "0"
                ) {
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                }
                me.ExcelOutBtn_Click("detail");
            };
            var yearMonth =
                $(".HMTVE250ReportPlaceCntTotal.ddlYear").val() +
                "年" +
                $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val();
            me.clsComFnc.FncMsgBox(
                "QY999",
                yearMonth + "月分のEXCELデータを出力します。よろしいですか？"
            );
        }
    });
    // 合計出力ボタン
    $(".HMTVE250ReportPlaceCntTotal.btnAll").click(function () {
        // 入力チェックを行う
        if (!$(".HMTVE250ReportPlaceCntTotal.ddlYear").val()) {
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                if (
                    $(".HMTVE250ReportPlaceCntTotal.lblItemnum").text() == "0"
                ) {
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                }
                me.ExcelOutBtn_Click("sum");
            };
            var yearMonth =
                $(".HMTVE250ReportPlaceCntTotal.ddlYear").val() +
                "年" +
                $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val();
            me.clsComFnc.FncMsgBox(
                "QY999",
                yearMonth + "月分のEXCELデータを出力します。よろしいですか？"
            );
        }
    });

    // 表示ボタン
    $(".HMTVE250ReportPlaceCntTotal.btnExpress").click(function () {
        me.btnExpress_Click();
    });

    // ロック解除
    $(".HMTVE250ReportPlaceCntTotal.btnRemove").click(function () {
        // 入力チェックを行う
        if (!$(".HMTVE250ReportPlaceCntTotal.ddlYear").val()) {
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                me.btnRemove_Click();
            };
            var yearMonth =
                $(".HMTVE250ReportPlaceCntTotal.ddlYear").val() +
                "年" +
                $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val();
            me.clsComFnc.FncMsgBox(
                "QY999",
                yearMonth +
                    "月分の軽自動車保管場所届出件数データのロックを解除します。よろしいですか？"
            );
        }
    });

    // 対象年月-年
    $(".HMTVE250ReportPlaceCntTotal.ddlYear").change(function () {
        me.selectedIndexChanged();
    });

    // 対象年月-月分
    $(".HMTVE250ReportPlaceCntTotal.ddlMonth").change(function () {
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
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        // 画面項目をクリアする
        me.PageClear();

        //コンボリストを設定する
        me.ExpressDdlYmd();
    };
    // '**********************************************************************
    // '処 理 名：画面項目
    // '関 数 名：PageClear
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：画面項目をクリアする
    // '**********************************************************************
    me.PageClear = function () {
        // '②表示設定
        // '画面項目No8.部署ﾃｰﾌﾞﾙを非表示にする(Visible=false)
        $(".HMTVE250ReportPlaceCntTotal.tblSubMain").hide();

        //画面項目NO9.件数を非表示にする(Visible=false)
        $(".HMTVE250ReportPlaceCntTotal.tblItem").hide();

        //EXCEL出力ボタン、ロック解除ボタンを非表示にする(Visible=False)
        $(".HMTVE250ReportPlaceCntTotal.btnView").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnAll").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnRemove").hide();
        $(".HMTVE250ReportPlaceCntTotal.HMS-button-pane").hide();

        //画面項目NO5、画面項目NO6をクリアする
        $(".HMTVE250ReportPlaceCntTotal.ddlYear").empty();
        $(".HMTVE250ReportPlaceCntTotal.ddlMonth").empty();

        $(".HMTVE250ReportPlaceCntTotal.btnExpress").trigger("focus");
    };
    // '**********************************************************************
    // '処 理 名：表示ボタンのイベント
    // '関 数 名：btnExpress_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：データを取得して、表示します
    // '**********************************************************************
    me.btnExpress_Click = function () {
        //画面項目のクリア処理
        $(".HMTVE250ReportPlaceCntTotal.tblSubMain").hide();
        //画面項目NO9.件数を非表示にする
        $(".HMTVE250ReportPlaceCntTotal.tblItem").hide();

        //EXCEL出力ボタン、ロック解除ボタンを非表示にする
        $(".HMTVE250ReportPlaceCntTotal.btnView").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnAll").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnRemove").hide();
        $(".HMTVE250ReportPlaceCntTotal.HMS-button-pane").hide();

        //部署データ取得
        $time =
            $.trim($(".HMTVE250ReportPlaceCntTotal.ddlYear").val()) +
            $.trim($(".HMTVE250ReportPlaceCntTotal.ddlMonth").val());
        var data = {
            NENGETU: $time,
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //１行目を選択状態にする
            $(me.grid_id).jqGrid("setSelection", "0");
            $num = $(me.grid_id).jqGrid("getGridParam", "records");
            //件数を表示する
            $(".HMTVE250ReportPlaceCntTotal.lblItemnum").text($num);

            //画面制御を行う
            //画面項目No8.部署ﾃｰﾌﾞﾙを表示する
            $(".HMTVE250ReportPlaceCntTotal.tblSubMain").show();

            //画面項目NO9.件数を表示する
            $(".HMTVE250ReportPlaceCntTotal.tblItem").show();

            //EXCEL出力ボタン、ロック解除ボタンを非表示にする
            $(".HMTVE250ReportPlaceCntTotal.btnView").show();
            $(".HMTVE250ReportPlaceCntTotal.btnAll").show();
            $(".HMTVE250ReportPlaceCntTotal.btnRemove").show();
            $(".HMTVE250ReportPlaceCntTotal.HMS-button-pane").show();
        };
        if (me.reload == false) {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                "",
                "",
                me.option,
                data,
                complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 400);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 270 : 312);
            $(me.grid_id).jqGrid("bindKeys");
            me.reload = true;
        } else {
            gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        }
    };
    // '**********************************************************************
    // '処 理 名：ページ更新
    // '関 数 名：selectedIndexChanged
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ページ更新
    // '**********************************************************************
    me.selectedIndexChanged = function () {
        //②表示設定
        //画面項目No8.部署ﾃｰﾌﾞﾙを非表示にする(Visible=false)
        $(".HMTVE250ReportPlaceCntTotal.tblSubMain").hide();

        //画面項目NO9.件数を非表示にする(Visible=false)
        $(".HMTVE250ReportPlaceCntTotal.tblItem").hide();

        //EXCEL出力ボタン、ロック解除ボタンを非表示にする(Visible=False)
        $(".HMTVE250ReportPlaceCntTotal.btnView").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnAll").hide();
        $(".HMTVE250ReportPlaceCntTotal.btnRemove").hide();
        $(".HMTVE250ReportPlaceCntTotal.HMS-button-pane").hide();
    };
    // '**********************************************************************
    // '処 理 名：出力ボタン
    // '関 数 名：me.ExcelOutBtn_Click(btnAll_Click+btnView_Click)
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：データを出力します
    // '**********************************************************************
    me.ExcelOutBtn_Click = function (type) {
        var url = me.sys_id + "/" + me.id + "/" + "excelOutBtnClick";
        var data = {
            ddlYear: $.trim($(".HMTVE250ReportPlaceCntTotal.ddlYear").val()),
            ddlMonth: $.trim($(".HMTVE250ReportPlaceCntTotal.ddlMonth").val()),
            type: type,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["data"] && result["data"]["msg"]) {
                    me.clsComFnc.FncMsgBox(
                        result["data"]["msg"],
                        result["error"]
                    );
                } else {
                    //エラーが発生した場合
                    if (type == "sum") {
                        //出力に失敗しました。
                        me.clsComFnc.FncMsgBox("W0030");
                    } else {
                        //出力に失敗しました。+ error
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "出力に失敗しました。" + result["error"]
                        );
                    }
                }
                return;
            }
            window.location.href = result["data"]["url"];
            //未出力データが存在しないかチェックする
            if (result["data"]["CNT"]) {
                var CNT = result["data"]["CNT"];
                if (CNT == -1) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]["msg"]);
                } else {
                    if (CNT == 0) {
                        //出力が完了しました。
                        me.clsComFnc.FncMsgBox("I0018");
                    }
                    if (CNT > 0) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "未出力データが " +
                                CNT +
                                " 件存在します。再度EXCEL出力を行ってください！"
                        );
                    }
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：ロック解除のイベント
    // '関 数 名：btnRemove_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：データを削除します
    // '**********************************************************************
    me.btnRemove_Click = function () {
        //ロック解除を行う
        var time =
            $.trim($(".HMTVE250ReportPlaceCntTotal.ddlYear").val()) +
            $.trim($(".HMTVE250ReportPlaceCntTotal.ddlMonth").val());
        var url = me.sys_id + "/" + me.id + "/" + "btnRemoveClick";
        var data = {
            NENGETU: time,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["data"] && result["data"]["msg"]) {
                //メッセージを表示し、処理を終了
                me.clsComFnc.FncMsgBox(result["data"]["msg"]);
                return;
            }
            //メッセージを表示する
            me.clsComFnc.FncMsgBox("I9999", "ロックの解除を行いました。");
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：コンボリストに日付
    // '関 数 名：ExpressDdlYmd
    // '引 数 　：objReader
    // '戻 り 値：true or false
    // '処理説明：コンボリストに日付を設定する
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    me.ExpressDdlYmd = function () {
        // 対象期間を取得する
        var url = me.sys_id + "/" + me.id + "/" + "expressDdlYmd";

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["row"] > 0) {
                var objReader = result["data"];
                if (objReader[0]["IVENTMAX"] == null) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "出力可能な軽自動車保管場所届出件数データは存在しません！"
                    );
                    return;
                }

                //年のコンボリストを設定する
                var iventMIN = objReader[0]["IVENTMIN"]
                    .toString()
                    .substring(0, 4);
                var iventMAX = objReader[0]["IVENTMAX"]
                    .toString()
                    .substring(0, 4);

                for (
                    var i = parseInt(iventMIN);
                    i < parseInt(iventMAX) + 1;
                    i++
                ) {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .prependTo(".HMTVE250ReportPlaceCntTotal.ddlYear");
                }

                var strTD = objReader[0]["TD"]
                    .toString()
                    .replace(/\//g, "")
                    .substring(0, 6);
                var strMIN = objReader[0]["IVENTMIN"].toString();
                var strMAX = objReader[0]["IVENTMAX"].toString();

                //'年のデフォルトを設定する
                //'取得データ("IVENTMIN")<=⑤-1.取得データ("TD")<=⑤-1.取得データ("IVENTMAX")の場合
                if (strMIN <= strTD && strTD <= strMAX) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlYear").val(
                        strTD.substring(0, 4)
                    );
                }

                //取得データ("IVENTMAX")<⑤-1．取得データ("TD")の場合
                if (strMAX < strTD) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlYear").val(iventMAX);
                }

                //取得データ("IVENTMIN")>⑤-1．取得データ("TD")の場合
                if (strMIN > strTD) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlYear").val(iventMIN);
                }

                //月のコンボリストを設定する
                for (var i = 1; i < 13; i++) {
                    var str = i.toString();
                    if (i < 10) {
                        str = "0" + str;
                    }
                    $("<option></option>")
                        .val(str)
                        .text(str)
                        .appendTo(".HMTVE250ReportPlaceCntTotal.ddlMonth");
                }

                //月のデフォルトを設定する
                if (strMIN <= strTD && strTD <= strMAX) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val(
                        strTD.substring(4, 6)
                    );
                }

                //取得データ("IVENTMAX")<⑤-1．取得データ("TD")の場合
                if (strMAX < strTD) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val(
                        strMAX.substring(4, 6)
                    );
                }

                //取得データ("IVENTMIN")>⑤-1．取得データ("TD")の場合
                if (strMIN > strTD) {
                    $(".HMTVE250ReportPlaceCntTotal.ddlMonth").val(
                        strMIN.substring(4, 6)
                    );
                }
            }
            //フォーカス移動
            $(".HMTVE250ReportPlaceCntTotal.btnExpress").trigger("focus");
        };
        me.ajax.send(url, "", 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE250ReportPlaceCntTotal =
        new HMTVE.HMTVE250ReportPlaceCntTotal();
    o_HMTVE_HMTVE250ReportPlaceCntTotal.load();
});
