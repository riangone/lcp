/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("HMTVE.HMTVE140PublicityOrderTotal");

HMTVE.HMTVE140PublicityOrderTotal = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE140PublicityOrderTotal";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE140PublicityOrderTotal_tblSubMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnETSearchClick";
    me.grid_load = true;

    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "店舗コード",
            index: "BUSYO_CD",
            width: 96,
            align: "center",
            sortable: false,
        },
        {
            name: "BUSYO_RYKNM",
            label: "店舗名",
            index: "BUSYO_RYKNM",
            width: 275,
            align: "left",
            sortable: false,
        },
    ];
    me.option = {
        caption: "",
        rowNum: 0,
        rownumbers: false,
        multiselect: false,
        colModel: me.colModel,
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE140PublicityOrderTotal.Button",
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

    // Excel出力ボタン
    $(".HMTVE140PublicityOrderTotal.btnExcelOut").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            // 入力チェックを行う
            // 画面項目NO9.件数＝0の場合、エラー
            if ($(".HMTVE140PublicityOrderTotal.lblItemnum").text() == "0") {
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            me.btnExcelOut_Click();
        };
        var yearMonth =
            $(".HMTVE140PublicityOrderTotal.ddlYear").val() +
            "/" +
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val();
        me.clsComFnc.FncMsgBox(
            "QY999",
            yearMonth + "のEXCELデータを出力します。よろしいですか？"
        );
    });

    // 表示ボタン
    $(".HMTVE140PublicityOrderTotal.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });

    // ロック解除
    $(".HMTVE140PublicityOrderTotal.btnLock").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.btnLock_Click();
        };
        var yearMonth =
            $(".HMTVE140PublicityOrderTotal.ddlYear").val() +
            "/" +
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val();
        me.clsComFnc.FncMsgBox(
            "QY999",
            yearMonth +
                "の展示会宣材注文データのロックを解除します。よろしいですか？"
        );
    });

    $(".HMTVE140PublicityOrderTotal.ddlYear").change(function () {
        me.selectedIndexChanged();
    });

    $(".HMTVE140PublicityOrderTotal.ddlMonth").change(function () {
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
        // 表示設定
        $(".HMTVE140PublicityOrderTotal.tblSubMain").hide();

        $(".HMTVE140PublicityOrderTotal.lblItem").hide();
        $(".HMTVE140PublicityOrderTotal.lblItemnum").hide();
        $(".HMTVE140PublicityOrderTotal.btnExcelOut").hide();
        $(".HMTVE140PublicityOrderTotal.btnLock").hide();
        $(".HMTVE140PublicityOrderTotal.HMS-button-pane").hide();

        // 画面項目をクリアする
        $(".HMTVE140PublicityOrderTotal.ddlYear").empty();
        $(".HMTVE140PublicityOrderTotal.ddlMonth").empty();

        //コンボリストを設定する
        me.setDropDownList();

        // 'フォーカス移動
        // '展示会開催年月(年)にフォーカス移動
        $(".HMTVE140PublicityOrderTotal.ddlYear").trigger("focus");
    };

    // '**********************************************************************
    // '処 理 名：展示会宣材注文_集計表示ボタンのイベント
    // '関 数 名：btnETSearch_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：展示会宣材注文_集計画面を表示する
    // '**********************************************************************
    me.btnETSearch_Click = function () {
        // 入力チェック
        if (
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val() == null ||
            $(".HMTVE140PublicityOrderTotal.ddlYear").val() == null ||
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val().length == 0 ||
            $(".HMTVE140PublicityOrderTotal.ddlYear").val().length == 0
        ) {
            me.clsComFnc.FncMsgBox(
                "E9999",
                "出力可能な展示会宣材データは存在しません！"
            );
            return;
        }

        //画面項目のクリア処理
        $(".HMTVE140PublicityOrderTotal.tblSubMain").hide();
        $(".HMTVE140PublicityOrderTotal.lblItem").hide();
        $(".HMTVE140PublicityOrderTotal.lblItemnum").hide();
        $(".HMTVE140PublicityOrderTotal.btnExcelOut").hide();
        $(".HMTVE140PublicityOrderTotal.btnLock").hide();
        $(".HMTVE140PublicityOrderTotal.HMS-button-pane").hide();

        var data = {
            IVENTYM:
                $(".HMTVE140PublicityOrderTotal.ddlYear").val().toString() +
                $(".HMTVE140PublicityOrderTotal.ddlMonth").val().toString(),
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
            $(me.grid_id).jqGrid("bindKeys");
            me.grid_load = false;
        } else {
            $(me.grid_id).jqGrid("clearGridData");
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
        }
    };

    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 400);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 270 : 312
        );

        //件数を表示する
        $(".HMTVE140PublicityOrderTotal.lblItemnum").text(result["records"]);

        $(me.grid_id).jqGrid("setSelection", 0);
        //画面制御を行う
        $(".HMTVE140PublicityOrderTotal.tblSubMain").show();
        $(".HMTVE140PublicityOrderTotal.lblItem").show();
        $(".HMTVE140PublicityOrderTotal.lblItemnum").show();
        $(".HMTVE140PublicityOrderTotal.btnExcelOut").show();
        $(".HMTVE140PublicityOrderTotal.btnLock").show();
        $(".HMTVE140PublicityOrderTotal.HMS-button-pane").show();
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
        //表示設定
        $(".HMTVE140PublicityOrderTotal.tblSubMain").hide();
        $(".HMTVE140PublicityOrderTotal.lblItem").hide();
        $(".HMTVE140PublicityOrderTotal.lblItemnum").hide();
        $(".HMTVE140PublicityOrderTotal.btnExcelOut").hide();
        $(".HMTVE140PublicityOrderTotal.btnLock").hide();
        $(".HMTVE140PublicityOrderTotal.HMS-button-pane").hide();
    };
    // '**********************************************************************
    // '処 理 名：Excel出力ボタン
    // '関 数 名：btnExcelOut_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：展示会宣材注文_集計Excel出力
    // '**********************************************************************
    me.btnExcelOut_Click = function () {
        var time =
            $(".HMTVE140PublicityOrderTotal.ddlYear").val() +
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val();
        var data = {
            IVENTYM: time,
        };
        //画面項目NO9.件数＝0の場合、エラー
        if ($(".HMTVE140PublicityOrderTotal.lblItemnum").val() == "0") {
            me.clsComFnc.FncMsgBox("W0024");
            return;
        }
        //宣材注文確定データに確定ﾌﾗｸﾞ１で更新する
        var url = me.sys_id + "/" + me.id + "/btnExcelOutClick";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                window.location.href = result["data"];
                //未出力データが存在しないかチェックする
                me.checkInput(result);
            } else {
                if (result["error"] == "W0006") {
                    me.clsComFnc.FncMsgBox("W0030");
                } else if (result["error"] == "W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (
                    result["error"] == "テンプレートファイルが存在しません。"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：ロック解除クリックのイベント
    // '関 数 名：btnLock_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ロック解除を行う
    // '**********************************************************************
    me.btnLock_Click = function () {
        var time =
            $(".HMTVE140PublicityOrderTotal.ddlYear").val() +
            $(".HMTVE140PublicityOrderTotal.ddlMonth").val();
        var data = {
            IVENTYM: time,
        };
        //ロック解除を行う
        //宣材注文確定データに確定ﾌﾗｸﾞ１で更新する
        var url = me.sys_id + "/" + me.id + "/btnLockClick";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["number_of_rows"] > 0) {
                    me.clsComFnc.FncMsgBox(
                        "I9999",
                        "ロックの解除を行いました。"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("W0024");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：展示会宣材注文_集計未出力データが存在しないかチェックする
    // '関 数 名：checkInput
    // '引 数 １：(I)objdr1 OracleDataReader
    // '引 数 ２：(I)time      String
    // '戻 り 値：なし
    // '処理説明：未出力データが存在しないかチェックする
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    me.checkInput = function (result) {
        //未出力データを抽出する
        var objdr1 = result["dataCheck"];
        //メッセージを表示する
        //抽出データ("CNT")=0の場合
        if (objdr1[0]["CNT"] == 0) {
            me.clsComFnc.FncMsgBox("I0018");
        } else if (objdr1[0]["CNT"] > 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "未出力データが" +
                    objdr1[0]["CNT"] +
                    "件存在します。再度EXCEL出力を行ってください！"
            );
        }
    };
    // '**********************************************************************
    // '処 理 名：展示会宣材注文_集計コンボリストを設定する
    // '関 数 名：setDropDownList
    // '戻 り 値：なし
    // '処理説明：コンボリストを設定する
    // '**********************************************************************
    me.setDropDownList = function () {
        // 対象期間を取得する
        var url = me.sys_id + "/" + me.id + "/setDropDownList";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                var objdr1 = result["data"];
                // 展示会データが存在しない場合は、メッセージ表示後、処理を中断する
                if (objdr1.length > 0) {
                    if (objdr1[0]["IVENTMAX"] == null) {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "展示会が設定されていません。先に展示会データ登録を行ってください！"
                        );
                        return false;
                    }
                } else if (objdr1.length == 0) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "展示会が設定されていません。先に展示会データ登録を行ってください！"
                    );
                    return false;
                }

                //コンボリストに日付を設定する

                var strEnd =
                    objdr1[0]["IVENTMAX"].substring(0, 4) +
                    "/" +
                    objdr1[0]["IVENTMAX"].substring(4, 6);
                var strStart =
                    objdr1[0]["IVENTMIN"].substring(0, 4) +
                    "/" +
                    objdr1[0]["IVENTMIN"].substring(4, 6);

                var strSysTime = new Date(objdr1[0]["TD"]).Format(
                    "yyyy/MM/dd HH:mm:ss"
                );

                var sysTimeCompareMin = me.DateDiff(
                    "m",
                    strEnd + "/01",
                    strSysTime
                );
                var sysTimeCompareMax = me.DateDiff(
                    "m",
                    strStart + "/01",
                    strSysTime
                );

                //年のコンボリストを設定する
                var strStartYear = new Date(strStart + "/01").getFullYear();
                var strEndYear = new Date(strEnd + "/01").getFullYear();

                for (var i = strEndYear; i > strStartYear - 1; i--) {
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .appendTo(".HMTVE140PublicityOrderTotal.ddlYear");
                }

                //'年のデフォルトを設定する
                //'取得データ("IVENTMIN")<=⑤-1.取得データ("TD")<=⑤-1.取得データ("IVENTMAX")の場合
                if (sysTimeCompareMin >= 0 && sysTimeCompareMax <= 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlYear").val(
                        new Date(strSysTime).getFullYear()
                    );
                }

                //取得データ("IVENTMAX")<⑤-1．取得データ("TD")の場合
                if (sysTimeCompareMax > 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlYear").val(
                        new Date(strStart + "/01").getFullYear()
                    );
                }

                //取得データ("IVENTMIN")>⑤-1．取得データ("TD")の場合
                if (sysTimeCompareMin < 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlYear").val(
                        new Date(strEnd + "/01").getFullYear()
                    );
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
                        .appendTo(".HMTVE140PublicityOrderTotal.ddlMonth");
                }

                //月のデフォルトを設定する
                //取得データ("IVENTMIN")<=⑤-1.取得データ("TD")<=⑤-1.取得データ("IVENTMAX")の場合
                if (sysTimeCompareMin >= 0 && sysTimeCompareMax <= 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlMonth").get(
                        0
                    ).selectedIndex = new Date(strSysTime).getMonth();
                }

                //取得データ("IVENTMAX")<⑤-1．取得データ("TD")の場合
                if (sysTimeCompareMax > 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlMonth").get(
                        0
                    ).selectedIndex = new Date(strStart + "/01").getMonth();
                }

                //取得データ("IVENTMIN")>⑤-1．取得データ("TD")の場合
                if (sysTimeCompareMin < 0) {
                    $(".HMTVE140PublicityOrderTotal.ddlMonth").get(
                        0
                    ).selectedIndex = new Date(strEnd + "/01").getMonth();
                }

                return true;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, "", 0);
    };
    me.DateDiff = function (interval, date1, date2) {
        var TimeCom1 = new Date(date1);
        var TimeCom2 = new Date(date2);
        var result;
        switch (String(interval).toLowerCase()) {
            case "yyyy":
            case "year":
                result = TimeCom1.getFullYear() - TimeCom2.getFullYear();
                break;
            case "m":
            case "month":
                result =
                    (TimeCom1.getFullYear() - TimeCom2.getFullYear()) * 12 +
                    (TimeCom1.getMonth() - TimeCom2.getMonth());
                break;
            case "d":
            case "day":
                result = Math.round(
                    (Date.UTC(
                        TimeCom1.getFullYear(),
                        TimeCom1.getMonth(),
                        TimeCom1.getDate()
                    ) -
                        Date.UTC(
                            TimeCom2.getFullYear(),
                            TimeCom2.getMonth(),
                            TimeCom2.getDate()
                        )) /
                        (1000 * 60 * 60 * 24)
                );
                break;
            case "h":
            case "hour":
                result = Math.round(
                    (Date.UTC(
                        TimeCom1.getFullYear(),
                        TimeCom1.getMonth(),
                        TimeCom1.getDate(),
                        TimeCom1.getHours()
                    ) -
                        Date.UTC(
                            TimeCom2.getFullYear(),
                            TimeCom2.getMonth(),
                            TimeCom2.getDate(),
                            TimeCom2.getHours()
                        )) /
                        (1000 * 60 * 60)
                );
                break;
            case "min":
            case "minute":
                result = Math.round(
                    (Date.UTC(
                        TimeCom1.getFullYear(),
                        TimeCom1.getMonth(),
                        TimeCom1.getDate(),
                        TimeCom1.getHours(),
                        TimeCom1.getMinutes()
                    ) -
                        Date.UTC(
                            TimeCom2.getFullYear(),
                            TimeCom2.getMonth(),
                            TimeCom2.getDate(),
                            TimeCom2.getHours(),
                            TimeCom2.getMinutes()
                        )) /
                        (1000 * 60)
                );
                break;
            case "s":
            case "second":
                result = Math.round(
                    (Date.UTC(
                        TimeCom1.getFullYear(),
                        TimeCom1.getMonth(),
                        TimeCom1.getDate(),
                        TimeCom1.getHours(),
                        TimeCom1.getMinutes(),
                        TimeCom1.getSeconds()
                    ) -
                        Date.UTC(
                            TimeCom2.getFullYear(),
                            TimeCom2.getMonth(),
                            TimeCom2.getDate(),
                            TimeCom2.getHours(),
                            TimeCom2.getMinutes(),
                            TimeCom2.getSeconds()
                        )) /
                        1000
                );
                break;
            case "ms":
            case "msecond":
                result =
                    Date.UTC(
                        TimeCom1.getFullYear(),
                        TimeCom1.getMonth(),
                        TimeCom1.getDate(),
                        TimeCom1.getHours(),
                        TimeCom1.getMinutes(),
                        TimeCom1.getSeconds(),
                        TimeCom1.getMilliseconds()
                    ) -
                    Date.UTC(
                        TimeCom2.getFullYear(),
                        TimeCom2.getMonth(),
                        TimeCom2.getDate(),
                        TimeCom2.getHours(),
                        TimeCom2.getMinutes(),
                        TimeCom2.getSeconds(),
                        TimeCom1.getMilliseconds()
                    );
                break;
            case "w":
            case "week":
                result =
                    Math.round(
                        (Date.UTC(
                            TimeCom1.getFullYear(),
                            TimeCom1.getMonth(),
                            TimeCom1.getDate()
                        ) -
                            Date.UTC(
                                TimeCom2.getFullYear(),
                                TimeCom2.getMonth(),
                                TimeCom2.getDate()
                            )) /
                            (1000 * 60 * 60 * 24)
                    ) % 7;
                break;
            default:
                result = "invalid";
        }
        return result;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE140PublicityOrderTotal =
        new HMTVE.HMTVE140PublicityOrderTotal();
    o_HMTVE_HMTVE140PublicityOrderTotal.load();
});
