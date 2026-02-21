/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE040InputDataS");

HMTVE.HMTVE040InputDataS = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.g_url = "HMTVE/HMTVE040InputDataS/checkUserWork";
    me.id = "HMTVE040InputDataS";
    //展示会開催期間 初期值
    me.hidTermStart = "";
    me.hidTermEnd = "";
    //合计行数组
    me.total_count = {};
    me.grid_id = "#HMTVE040InputDataS_tblMain";

    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        footerrow: true,
        caption: "",
        multiselectWidth: 60,
    };
    me.colModel = [
        {
            label: "",
            name: "SYASYU_CD",
            index: "SYASYU_CD",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "CREATE_DATE",
            index: "CREATE_DATE",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "FLG",
            index: "FLG",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "車 種",
            name: "SYASYU_RYKNM",
            labelClasses: "HMTVE040InputDataS_tblMain_CELL_SUM_R",
            classes: "CELL_SUM_R",
            index: "SYASYU_RYKNM",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            label: "成約<br/>台数",
            name: "SEIYAKU_DAISU",
            labelClasses: "HMTVE040InputDataS_tblMain_CELL_SUM_C CELL_BORDER",
            classes: "CELL_SUM_C CELL_BORDER",
            index: "SEIYAKU_DAISU",
            width: 40,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                class: "align_right",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.Calculation(e.target);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9)
                            ) {
                                me.Calculation(e.target);
                            }
                        },
                    },
                ],
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE040InputDataS.btnETSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE040InputDataS.btnView",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE040InputDataS.btnUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE040InputDataS.btnDelete",
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
    $(".HMTVE040InputDataS.btnView").click(function () {
        me.btnView_click();
    });
    //消除ボタンクリック
    $(".HMTVE040InputDataS.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE040InputDataS.ddlExhibitDay").val() +
                "の速報データを削除します。よろしいですか？"
        );
    });
    //更新ボタンクリック
    $(".HMTVE040InputDataS.btnUpdate").click(function () {
        me.btnUpdate_click();
    });
    //展示会検索ボタンクリック
    $(".HMTVE040InputDataS.btnETSearch").click(function () {
        me.btnETSearch_click();
    });
    //展示会開催日変更
    $(".HMTVE040InputDataS.ddlExhibitDay").change(function () {
        me.ddlExhibitDay_SelectedIndexChanged();
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
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 250);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 349 : 435
        );
        $(me.grid_id).jqGrid("bindKeys");
        $(".HMTVE040InputDataS .ui-widget-content.footrow.footrow-ltr")
            .css("background", "#FF69B4 ")
            .css("color", "#000000");
        $(".HMTVE040InputDataS .ui-widget-content.footrow.footrow-ltr td").css(
            "text-align",
            "right"
        );
        me.PageInit();
        $(".HMTVE040InputDataS.buttonTd").width(
            $(".ui-widget-content.HMTVE.HMTVE-layout-center").width() -
                $(".HMTVE040InputDataS fieldset").width() -
                $("#gbox_HMTVE040InputDataS_tblMain").width() +
                "px"
        );
        var url = me.sys_id + "/" + me.id + "/" + "setExhibitTermDate";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".HMTVE040InputDataS.btnView").button("disable");
                me.clsComFnc.FncMsgBox("E9999", "データ読込に失敗しました。");
                return;
            }
            if (result["data"]["START_DATE"]) {
                me.hidTermStart = result["data"]["START_DATE"];
                $(".HMTVE040InputDataS.lblExhibitTermFrom").val(
                    me.hidTermStart
                );
            }
            if (result["data"]["END_DATE"]) {
                me.hidTermEnd = result["data"]["END_DATE"];
                $(".HMTVE040InputDataS.lblExhibitTermTo").val(me.hidTermEnd);
            }
            me.setExhibitTermDate(me.hidTermStart, me.hidTermEnd);
        };
        me.ajax.send(url, "", 0);
    };
    me.fncJqgrid = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex = e.target.cellIndex;
                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid != me.last_selected_id) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.last_selected_id,
                                null,
                                "clientArray"
                            );
                            me.last_selected_id = rowid;
                        }
                        $(me.grid_id).jqGrid("editRow", rowid, true);
                    }
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
                    $(me.grid_id).jqGrid("editRow", rowid, true);
                }

                //靠右
                $(me.grid_id).find(".align_right").css("text-align", "right");
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.last_selected_id
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
            },
        });
    };
    //**********************************************************************
    //処 理 名：表示ボタンクリックのイベント
    //関 数 名：btnView_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：表示ボタンの処理
    //**********************************************************************
    me.btnView_click = function () {
        //入力チェック
        if (!me.inputCheck()) {
            return;
        }
        var data = {
            ddlExhibitDay: $(".HMTVE040InputDataS.ddlExhibitDay").val(),
        };
        var complete_fun = function (_returnFLG, result) {
            me.PageInit();
            if (result["error"]) {
                if (result["error"] == "入力対象外です。") {
                    me.clsComFnc.FncMsgBox("W9999", "入力対象外です。");
                } else if (result["error"] == "は休みの設定がされています。") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        $(".HMTVE040InputDataS.ddlExhibitDay").val() +
                            result["error"]
                    );
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "データ読込に失敗しました。"
                    );
                }
                return;
            }
            me.buttonEnable();
            var objDR = $(me.grid_id).jqGrid("getRowData");
            me.total_count["SYASYU_RYKNM"] = "合　計";
            me.total_count["SEIYAKU_DAISU"] = "";
            if (objDR.length == 0) {
                $(me.grid_id).jqGrid("footerData", "set", me.total_count);
            } else {
                if (objDR[0]["FLG"]) {
                    if (objDR[0]["FLG"] == 0) {
                        $(".HMTVE040InputDataS.btnDelete").button("disable");
                    } else {
                        $(".HMTVE040InputDataS.btnDelete").button("enable");
                    }
                }
                me.Calculation($(".HMTVE040InputDataS #0_SEIYAKU_DAISU"));
            }
            if (result["kakuteiFLG"] != "") {
                if (
                    result["kakuteiFLG"].length > 0 &&
                    result["kakuteiFLG"][0]["KAKUTEI_FLG"] == 1
                ) {
                    $(".HMTVE040InputDataS.btnDelete").button("disable");
                    $(".HMTVE040InputDataS.btnUpdate").button("disable");
                    me.buttonEnable();
                    //edit cell
                    $(me.grid_id).jqGrid("setGridParam", {
                        //選択行の修正画面を呼び出す
                        onSelectRow: function () {},
                    });
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "既に速報データの出力が行われていますので、変更は出来ません"
                    );
                } else if (
                    result["kakuteiFLG"].length > 0 &&
                    result["kakuteiFLG"][0]["KAKUTEI_FLG"] >= 0
                ) {
                    $(".HMTVE040InputDataS.btnUpdate").button("enable");
                    me.buttonEnable();
                    me.fncJqgrid();
                }
            } else {
                $(".HMTVE040InputDataS.btnUpdate").button("enable");
                me.buttonEnable();
                me.fncJqgrid();
            }
            $(me.grid_id).jqGrid("setSelection", 0);
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //**********************************************************************
    //処 理 名：削除ボタンクリックのイベント
    //関 数 名：btnDelete_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：削除ボタンの処理
    //**********************************************************************
    me.btnDelete_click = function () {
        //入力チェック
        if (!me.inputCheck()) {
            return;
        }
        var url = "HMTVE/HMTVE040InputDataS/btnDelete_Click";
        var data = {
            ddlExhibitDay: $(".HMTVE040InputDataS.ddlExhibitDay").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Load();
            };
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：更新ボタンクリック
    //関 数 名：btnUpdate_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：画面の内容を更新する
    //**********************************************************************
    me.btnUpdate_click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpdate_execute;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE040InputDataS.ddlExhibitDay").val() +
                "の速報データを更新します。よろしいですか？"
        );
    };
    me.btnUpdate_execute = function () {
        var num = 0;
        var allArr = $(me.grid_id).jqGrid("getRowData");
        //データグリッドの行が存在する場合
        if (allArr.length > 0) {
            me.total_count["SEIYAKU_DAISU"] = 0;
            var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
            for (var i = 0; i < allArr.length; i++) {
                if (i == rowid) {
                    num = $(
                        ".HMTVE040InputDataS #" + rowid + "_SEIYAKU_DAISU"
                    ).val();
                } else {
                    num = allArr[i]["SEIYAKU_DAISU"];
                }
                if (me.clsComFnc.GetByteCount(num) > 3) {
                    //指定されている桁数をオーバーした場合
                    if (i != rowid) {
                        //to solve the problem:Uncaught RangeError: Maximum call stack size exceeded
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(me.grid_id).jqGrid("setSelection", i, true);
                        };
                    }
                    $(".HMTVE040InputDataS #" + i + "_SEIYAKU_DAISU").val("");
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE040InputDataS #" + i + "_SEIYAKU_DAISU"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        allArr[i]["SYASYU_RYKNM"] +
                            "は指定されている桁数をオーバーしています。"
                    );
                    return;
                }
                if (num != "") {
                    if (!$.isNumeric($.trim(num))) {
                        if (i != rowid) {
                            //to solve the problem:Uncaught RangeError: Maximum call stack size exceeded
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $(me.grid_id).jqGrid("setSelection", i, true);
                            };
                        }
                        me.clsComFnc.ObjSelect = $(
                            ".HMTVE040InputDataS #" + i + "_SEIYAKU_DAISU"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E0013",
                            allArr[i]["SYASYU_RYKNM"]
                        );
                        return;
                    }
                    if (parseInt(num) < 0) {
                        //正数じゃない場合
                        if (i != rowid) {
                            //to solve the problem:Uncaught RangeError: Maximum call stack size exceeded
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $(me.grid_id).jqGrid("setSelection", i, true);
                            };
                        }
                        me.clsComFnc.ObjSelect = $(
                            ".HMTVE040InputDataS #" + i + "_SEIYAKU_DAISU"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E0013",
                            allArr[i]["SYASYU_RYKNM"]
                        );
                        return;
                    }
                    if (num.indexOf(".") != -1) {
                        //整数じゃない場合
                        if (i != rowid) {
                            //to solve the problem:Uncaught RangeError: Maximum call stack size exceeded
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $(me.grid_id).jqGrid("setSelection", i, true);
                            };
                        }
                        me.clsComFnc.ObjSelect = $(
                            ".HMTVE040InputDataS #" + i + "_SEIYAKU_DAISU"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E0013",
                            allArr[i]["SYASYU_RYKNM"]
                        );
                        return;
                    }
                }
            }
            //入力チェック
            if (!me.inputCheck()) {
                return;
            }
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Load();
            };
            //登録が完了しました。
            me.clsComFnc.FncMsgBox("I0016");
            return;
        }
        var url = "HMTVE/HMTVE040InputDataS/btnUpdate_execute";

        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var tableDate = $(me.grid_id).jqGrid("getRowData");
        tableDate[rowid]["SEIYAKU_DAISU"] = $(
            ".HMTVE040InputDataS #" + rowid + "_SEIYAKU_DAISU"
        ).val();
        var data = {
            ddlExhibitDay: $(".HMTVE040InputDataS.ddlExhibitDay").val(),
            tableDate: tableDate,
            lblExhibitTermFrom: $(
                ".HMTVE040InputDataS.lblExhibitTermFrom"
            ).val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                if (result["key"] == "noBusyo") {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        me.PageInit();
                        //展示会開催期間に初期値をセットする
                        me.setExhibitTermDate(me.hidTermStart, me.hidTermEnd);
                    };
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else if (result["key"]) {
                    me.clsComFnc.FncMsgBox(result["key"], result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Load();
            };
            //登録が完了しました。
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    me.Calculation = function (e) {
        var num = 0;
        var allArr = $(me.grid_id).jqGrid("getRowData");
        me.total_count["SEIYAKU_DAISU"] = 0;
        for (var i = 0; i < allArr.length; i++) {
            num = $.trim(allArr[i]["SEIYAKU_DAISU"]);

            if ($.isNumeric(num)) {
                me.total_count["SEIYAKU_DAISU"] += parseInt(num);
            }
        }
        num = $.trim($(e).val());
        if ($.isNumeric(num)) {
            me.total_count["SEIYAKU_DAISU"] += parseInt(num);
        }
        $(me.grid_id).jqGrid("footerData", "set", me.total_count);
    };
    //**********************************************************************
    //処 理 名：展示会検索ボタンクリックのイベント
    //関 数 名：btnETSearch_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：展示会検索ボタンの処理
    //**********************************************************************
    me.btnETSearch_click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE040InputDataSDialogDiv";
        //var title = "展示会検索";
        var $rootDiv = $(".HMTVE040InputDataS.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETStart").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETEnd").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMTVE040InputDataS.lblExhibitTermFrom").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE040InputDataS.lblExhibitTermTo").val(
                        $lblETEnd.html()
                    );
                    me.PageInit();
                    me.setExhibitTermDate(
                        $(".HMTVE040InputDataS.lblExhibitTermFrom").val(),
                        $(".HMTVE040InputDataS.lblExhibitTermTo").val()
                    );
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
            }

            $RtnCD.hide();
            $lblETStart.hide();
            $lblETEnd.hide();
            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE040InputDataS.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    me.PageInit = function () {
        $(".HMTVE040InputDataS.btnDelete").button("disable");
        $(".HMTVE040InputDataS.btnUpdate").button("disable");
        $(".HMTVE040InputDataS.tableTd").hide();
        $(".HMTVE040InputDataS.buttonTd").hide();
    };
    me.buttonEnable = function () {
        $(".HMTVE040InputDataS.tableTd").show();
        $(".HMTVE040InputDataS.buttonTd").show();
    };
    //**********************************************************************
    //処 理 名：展開開催日を変えることのイベント
    //関 数 名：ddlExhibitDay_SelectedIndexChanged
    //引    数：無し
    //戻 り 値：なし
    //処理説明：展開開催日を変えることの処理
    //**********************************************************************
    me.ddlExhibitDay_SelectedIndexChanged = function () {
        if (!$(".HMTVE040InputDataS.tableTd").is(":hidden")) {
            me.btnView_click();
        } else {
            me.PageInit();
            me.total_count["SEIYAKU_DAISU"] = "";
            $(me.grid_id).jqGrid("footerData", "set", me.total_count);
        }
    };
    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：inputCheck
    //引    数：無し
    //戻 り 値：なし
    //処理説明：入力の内容をチェック
    //**********************************************************************
    me.inputCheck = function () {
        //展示会開催期間(From)が未入力の場合、エラー
        if ($(".HMTVE040InputDataS.lblExhibitTermFrom").val() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください。"
            );
            return false;
        }
        //展示会開催期間(To)が未入力の場合、エラー
        if ($(".HMTVE040InputDataS.lblExhibitTermTo").val() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください。"
            );
            return false;
        }
        //展示会開催日が未入力の場合、エラー
        if (
            $(".HMTVE040InputDataS.ddlExhibitDay").val() == "" ||
            $(".HMTVE040InputDataS.ddlExhibitDay").val() == null
        ) {
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください。");
            return false;
        }
        return true;
    };
    me.setExhibitTermDate = function (From, To) {
        $(".HMTVE040InputDataS.ddlExhibitDay").html("");
        $(".HMTVE040InputDataS.lblExhibitTermFrom").val(From);
        $(".HMTVE040InputDataS.lblExhibitTermTo").val(To);
        var days = me.DateDiff(From, To);
        for (var i = 0; i <= days; i++) {
            var Fromdate = new Date(From);
            Fromdate.setDate(Fromdate.getDate() + i);
            var strdate = Fromdate.Format("yyyy/MM/dd");
            $("<option></option>")
                .val(strdate)
                .text(strdate)
                .appendTo(".HMTVE040InputDataS.ddlExhibitDay");
        }
        //フォーカス移動
        $(".HMTVE040InputDataS.ddlExhibitDay").trigger("focus");
    };
    me.DateDiff = function (start, end) {
        var sdate = new Date(start);
        var now = new Date(end);
        var days = now.getTime() - sdate.getTime();
        var day = parseInt(days / (1000 * 60 * 60 * 24));
        return day;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE040InputDataS = new HMTVE.HMTVE040InputDataS();
    o_HMTVE_HMTVE040InputDataS.load();
    o_HMTVE_HMTVE.HMTVE040InputDataS = o_HMTVE_HMTVE040InputDataS;
});
