/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE100AttendanceControl");

HMTVE.HMTVE100AttendanceControl = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE100AttendanceControl";
    me.hmtve = new HMTVE.HMTVE();
    // jqgrid
    me.grid_id = "#HMTVE100AttendanceControl_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnPrintOut_Click";
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "",
            index: "BUSYO_CD",
            width: 100,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "SYAIN_NO",
            label: "",
            index: "SYAIN_NO",
            width: 100,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 400,
            align: "left",
            sortable: false,
        },
        {
            name: "FLG",
            label: "休み",
            index: "FLG",
            width: 70,
            align: "center",
            sortable: false,
            formatter: function (cellValue, options) {
                if (cellValue == "2") {
                    return (
                        "<input type='checkbox' class='" +
                        options.rowId +
                        "_HMTVE100AttendanceControl_FLG' checked='checked'/>"
                    );
                } else {
                    return (
                        "<input type='checkbox' class='" +
                        options.rowId +
                        "_HMTVE100AttendanceControl_FLG'/>"
                    );
                }
            },
        },
        {
            name: "IVENT_TARGET_FLG",
            label: "",
            index: "IVENT_TARGET_FLG",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "CREATE_DATE",
            label: "",
            index: "CREATE_DATE",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE100AttendanceControl.button",
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
    // = 宣言 end =objdrShopSya
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //登録ボタンクリック
    $(".HMTVE100AttendanceControl.btnReg").click(function () {
        if (!me.checkNull()) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnReg_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE100AttendanceControl.ddlExhibitDay").val() +
                "の出勤管理データを更新します。よろしいですか？"
        );
    });

    //削除ボタンクリック
    $(".HMTVE100AttendanceControl.btnDel").click(function () {
        if (!me.checkNull()) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDel_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE100AttendanceControl.ddlExhibitDay").val() +
                "の出勤管理データを削除します。よろしいですか？"
        );
    });

    //表示ボタンクリック
    $(".HMTVE100AttendanceControl.btnPrintOut").click(function () {
        if (!me.checkNull()) {
            return;
        }
        me.btnPrintOut_Click();
    });

    //展示会検索ボタンクリック
    $(".HMTVE100AttendanceControl.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });

    //開催日change
    $(".HMTVE100AttendanceControl.ddlExhibitDay").change(function () {
        me.ddlExhibitDay_SelectedIndexChanged();
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
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 500);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 270 : 339
        );
        $(me.grid_id).jqGrid("bindKeys");
        //jqgridとbutton表示しない
        me.ddlExhibitDay_SelectedIndexChanged();
        //店舗名と店舗コードを設定する
        me.Page_ShopNameSave();
    };
    //'**********************************************************************
    //'処 理 名：当ページを初期化する
    //'関 数 名：Page_Clear
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：当ページを初期の状態にセットする
    //'**********************************************************************
    me.Page_Clear = function () {
        //hideの設定
        me.ddlExhibitDay_SelectedIndexChanged();
        //展示会開催期間に初期値をセットする
        //デフォルト日付を取得する
        var url = me.sys_id + "/" + me.id + "/" + "pageclear";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var objdr2 = result["data"];
            if (result["row"] > 0 && objdr2) {
                if (
                    objdr2[0]["END_DATE"] &&
                    objdr2[0]["END_DATE"] != undefined
                ) {
                    //TO日付が存在する場合
                    var endDate =
                        objdr2[0]["END_DATE"].substring(0, 4) +
                        "/" +
                        objdr2[0]["END_DATE"].substring(4, 6) +
                        "/" +
                        objdr2[0]["END_DATE"].substring(6, 8);
                    $(".HMTVE100AttendanceControl.lblExhibitTermEnd").val(
                        endDate
                    );
                    if (
                        objdr2[0]["START_DATE"] &&
                        objdr2[0]["START_DATE"] != undefined
                    ) {
                        var startDate =
                            objdr2[0]["START_DATE"].substring(0, 4) +
                            "/" +
                            objdr2[0]["START_DATE"].substring(4, 6) +
                            "/" +
                            objdr2[0]["START_DATE"].substring(6, 8);
                        $(".HMTVE100AttendanceControl.lblExhibitTermStart").val(
                            startDate
                        );
                    }
                    //展示会開催日のコンボリストを設定する
                    if (
                        $(
                            ".HMTVE100AttendanceControl.lblExhibitTermStart"
                        ).val() != "" &&
                        $(
                            ".HMTVE100AttendanceControl.lblExhibitTermEnd"
                        ).val() != ""
                    ) {
                        //コンボリストを設定する
                        me.setExhibitTermDate(
                            $(
                                ".HMTVE100AttendanceControl.lblExhibitTermStart"
                            ).val(),
                            $(
                                ".HMTVE100AttendanceControl.lblExhibitTermEnd"
                            ).val()
                        );
                        $(".HMTVE100AttendanceControl.ddlExhibitDay").trigger(
                            "focus"
                        );
                    } else {
                        $(".HMTVE100AttendanceControl.btnETSearch").trigger(
                            "focus"
                        );
                    }
                } else {
                    //TO日付が存在しない場合
                    $(".HMTVE100AttendanceControl.lblExhibitTermStart").val("");
                    $(".HMTVE100AttendanceControl.lblExhibitTermEnd").val("");
                    $(".HMTVE100AttendanceControl.ddlExhibitDay")
                        .find("option")
                        .remove();
                    $(".HMTVE100AttendanceControl.btnETSearch").trigger(
                        "focus"
                    );
                }
            } else {
                //データが存在しない場合
                $(".HMTVE100AttendanceControl.lblExhibitTermStart").val("");
                $(".HMTVE100AttendanceControl.lblExhibitTermEnd").val("");
                $(".HMTVE100AttendanceControl.ddlExhibitDay")
                    .find("option")
                    .remove();
                $(".HMTVE100AttendanceControl.btnETSearch").trigger("focus");
            }
        };
        me.ajax.send(url, "", 0);
    };
    // **********************************************************************
    // 処 理 名：展示会開催期間初期値セット
    // 関 数 名：setExhibitTermDate
    // 引 数   ：From, To
    // 戻 り 値：なし
    // 処理説明：展示会開催期間に初期値をセット
    // **********************************************************************
    me.setExhibitTermDate = function (From, To) {
        $(".HMTVE100AttendanceControl.ddlExhibitDay").find("option").remove();
        var dayDiff = me.DateDiff(From, To);
        for (var i = 0; i <= dayDiff; i++) {
            var Fromdate = new Date(From);
            Fromdate.setDate(Fromdate.getDate() + i);
            var strdate = Fromdate.Format("yyyy/MM/dd");
            $("<option></option>")
                .val(strdate)
                .text(strdate)
                .appendTo(".HMTVE100AttendanceControl.ddlExhibitDay");
        }
    };
    // **********************************************************************
    // 処 理 名：
    // 関 数 名：DateDiff
    // 引 数   ：start, end
    // 戻 り 値：なし
    // 処理説明：時間間隔数を取得する
    // **********************************************************************
    me.DateDiff = function (start, end) {
        var sdate = new Date(start);
        var now = new Date(end);
        var days = now.getTime() - sdate.getTime();
        var day = parseInt(days / (1000 * 60 * 60 * 24));
        return day;
    };
    // **********************************************************************
    // 処 理 名：当ページを初期化する
    // 関 数 名：Page_ShopNameSave
    // 引 数   ：なし
    // 戻 り 値：なし
    // 処理説明：店舗コード、店舗名を抽出する
    // **********************************************************************
    me.Page_ShopNameSave = function () {
        //店舗名を取得する
        var url = me.sys_id + "/" + me.id + "/" + "pageshopnamesave";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (
                result["data"]["BUSYO_RYKNM"] != undefined &&
                result["data"]["BUSYO_CD"] != undefined
            ) {
                $(".HMTVE100AttendanceControl.lblTenpoNM").val(
                    result["data"]["BUSYO_RYKNM"]
                );
                $(".HMTVE100AttendanceControl.lblTenpoCd").val(
                    result["data"]["BUSYO_CD"]
                );
            }
            //画面初期化
            me.Page_Clear();
        };
        me.ajax.send(url, "", 0);
    };
    //'**********************************************************************
    //'処 理 名：削除ボタンのイベント
    //'関 数 名：btnDel_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：出勤管理データを削除する
    //'**********************************************************************
    me.btnDel_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnDelClick";
        var data = {
            IVENTDT: $(".HMTVE100AttendanceControl.ddlExhibitDay")
                .val()
                .replace(/\//g, ""),
            BUSYOCD: $(".HMTVE100AttendanceControl.lblTenpoCd").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                //画面をクリアする
                me.Page_ShopNameSave();
            };
            //削除が完了しました。
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：展覧会検索ボタンのイベント
    //'関 数 名：btnETSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索画面の戻り値を画面項目にセットする
    //'**********************************************************************
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE100AttendanceControlDialogDiv";
        var $rootDiv = $(".HMTVE100AttendanceControl.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div style='display:none;'></div>")
            .attr("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "RtnCD")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETStart")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETEnd")
            .insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMTVE100AttendanceControl.lblExhibitTermStart").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE100AttendanceControl.lblExhibitTermEnd").val(
                        $lblETEnd.html()
                    );
                    $(".HMTVE100AttendanceControl.pnlList").hide();
                    //ボタンの設定
                    $(".HMTVE100AttendanceControl.HMS-button-pane").hide();
                    //コンボリストを設定する
                    me.setExhibitTermDate(
                        $(
                            ".HMTVE100AttendanceControl.lblExhibitTermStart"
                        ).val(),
                        $(".HMTVE100AttendanceControl.lblExhibitTermEnd").val()
                    );
                    //Enterキーを押した後のオプション拡張の問題
                    $(".HMTVE100AttendanceControl.btnETSearch").trigger("blur");
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE100AttendanceControl.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    //'**********************************************************************
    //'処 理 名：表示ボタンのイベント
    //'関 数 名：btnPrintOut_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：取得データを出勤管理グリッドにバインドする
    //'**********************************************************************
    me.btnPrintOut_Click = function () {
        //jqgridとbutton表示しない
        me.ddlExhibitDay_SelectedIndexChanged();
        var data = {
            BUSYOCD: $(".HMTVE100AttendanceControl.lblTenpoCd").val(),
            IVENTDT: $(".HMTVE100AttendanceControl.ddlExhibitDay")
                .val()
                .replace(/\//g, ""),
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
            //１行目を選択状態にする
            $(me.grid_id).jqGrid("setSelection", "0");
            $(".HMTVE100AttendanceControl.pnlList").show();
            //ボタンの設定
            $(".HMTVE100AttendanceControl.HMS-button-pane").show();
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnReg_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：出勤管理データに追加する
    //'**********************************************************************
    me.btnReg_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnRegClick";
        var jqgridArr = new Array();
        var rowdata = "";
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            jqgridArr.push({
                chkbox: $("." + ids[i] + "_HMTVE100AttendanceControl_FLG").is(
                    ":checked"
                )
                    ? "1"
                    : "0",
                lblSyainCD: rowdata["SYAIN_NO"],
                IVENT_TARGET_FLG: rowdata["IVENT_TARGET_FLG"],
                CREATE_DATE: $.trim(rowdata["CREATE_DATE"]),
            });
        }
        var data = {
            gvTenpo: jqgridArr,
            IDATE: $(".HMTVE100AttendanceControl.ddlExhibitDay")
                .val()
                .replace(/\//g, ""),
            BUSYOCD: $(".HMTVE100AttendanceControl.lblTenpoCd").val(),
            START_DATE: $(".HMTVE100AttendanceControl.lblExhibitTermStart")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["data"]["msg"]) {
                    me.clsComFnc.FncMsgBox(
                        result["error"],
                        result["data"]["msg"]
                    );
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        result["data"]["rowNum"],
                        true
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }

            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                //画面をクリアする
                me.Page_ShopNameSave();
            };
            //登録が完了しました。
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：
    //'関 数 名：checkNull
    //'引    数：無し
    //'戻 り 値：Boolean
    //'処理説明：空の値をチェックする
    //'**********************************************************************
    me.checkNull = function () {
        if (
            $.trim($(".HMTVE100AttendanceControl.lblExhibitTermStart").val()) ==
            ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE100AttendanceControl.lblExhibitTermStart"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください"
            );
            return false;
        }
        if (
            $.trim($(".HMTVE100AttendanceControl.lblExhibitTermEnd").val()) ==
            ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE100AttendanceControl.lblExhibitTermEnd"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください"
            );
            return false;
        }
        if ($.trim($(".HMTVE100AttendanceControl.ddlExhibitDay").val()) == "") {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE100AttendanceControl.ddlExhibitDay"
            );
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください");
            return false;
        }
        if ($.trim($(".HMTVE100AttendanceControl.lblTenpoNM").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HMTVE100AttendanceControl.lblTenpoNM");
            me.clsComFnc.FncMsgBox("W9999", "部署が存在しません！");
            return false;
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：開催日を変えることのイベント
    //'関 数 名：ddlExhibitDay_SelectedIndexChanged
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：開催日を変えることの処理
    //'**********************************************************************
    me.ddlExhibitDay_SelectedIndexChanged = function () {
        $(me.grid_id).jqGrid("clearGridData");
        $(".HMTVE100AttendanceControl.pnlList").hide();
        //ボタンの設定
        $(".HMTVE100AttendanceControl.HMS-button-pane").hide();
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE100AttendanceControl =
        new HMTVE.HMTVE100AttendanceControl();
    o_HMTVE_HMTVE100AttendanceControl.load();
    o_HMTVE_HMTVE.HMTVE100AttendanceControl = o_HMTVE_HMTVE100AttendanceControl;
});
