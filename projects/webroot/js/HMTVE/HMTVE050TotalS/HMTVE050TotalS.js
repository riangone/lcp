/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE050TotalS");

HMTVE.HMTVE050TotalS = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE050TotalS";
    me.HMTVE = new HMTVE.HMTVE();

    // jqgrid
    me.grid_id = "#HMTVE050TotalS_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.option = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "BUSYO_RYKNM",
            //タイトルのclass
            labelClasses: "HMTVE050TotalS_tblMain_BUSYO50_CELL_TITLE_BLUE_C",
            classes: "BUSYO50_CELL_TITLE_BLUE_C",
            label: "店舗名",
            index: "BUSYO_RYKNM",
            // width : 180,
            width: 100,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "CHK_FLG",
            label: "入力<br/>状況",
            //タイトルのclass
            labelClasses: "HMTVE050TotalS_tblMain_CELL_TITLE_BLUE_C",
            classes: "CELL_TITLE_BLUE_C",
            index: "CHK_FLG",
            width: 80,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "DAISU_GK",
            label: "成<br/>約<br/>台<br/>数<br/>合<br/>計",
            //タイトルのclass
            labelClasses: "HMTVE050TotalS_tblMain_CELL_TITLE_BLUE_C",
            classes: "CELL_TITLE_BLUE_C",
            index: "DAISU_GK",
            width: 50,
            align: "right",
            sortable: false,
            frozen: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE050TotalS.button",
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

    //展示会検索ボタンクリック
    $(".HMTVE050TotalS.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    //表示ボタンクリック
    $(".HMTVE050TotalS.btnView").click(function () {
        me.btnView_Click();
    });
    $(".HMTVE050TotalS.ddlExhibitDay").change(function () {
        $(".HMTVE050TotalS.pnlList").hide();
    });
    //HITNET用Excel出力ボタンクリック
    $(".HMTVE050TotalS.btnOutputHITNET").click(function () {
        if (me.checkNull() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnOutputHITNET_Click;
            //印刷ボタンの確認メッセージの表示
            var ckval1 = $.trim($(".HMTVE050TotalS.lblExhibitTerm").val());
            var ckval2 = $.trim($(".HMTVE050TotalS.ddlExhibitDay").val());
            me.clsComFnc.FncMsgBox(
                "QY999",
                ckval1 +
                    "～" +
                    ckval2 +
                    "のHITNET用のEXCELデータを出力します。よろしいですか？"
            );
        }
    });
    //ロック解除ボタンクリック
    $(".HMTVE050TotalS.btnUnLock").click(function () {
        if (me.checkNull2() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUnLock_Click;
            //印刷ボタンの確認メッセージの表示
            var ckval1 = $.trim($(".HMTVE050TotalS.lblExhibitTerm").val());
            var ckval2 = $.trim($(".HMTVE050TotalS.ddlExhibitDay").val());
            var ckval3 = $.trim($(".HMTVE050TotalS.lblExhibitTerm2").val());
            if (ckval2 != "") {
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "展示会" +
                        ckval1 +
                        "～" +
                        ckval2 +
                        "の速報データのロックを解除します。よろしいですか？"
                );
            } else {
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "展示会" +
                        ckval1 +
                        "～" +
                        ckval3 +
                        "の速報データのロックを解除します。よろしいですか？"
                );
            }
        }
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
        me.Page_Load();
    };
    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        me.Page_Clear();
        $(".HMTVE050TotalS.ddlExhibitDay").trigger("focus");
    };
    me.gridComplete = function (total_count, sum) {
        total_count["CHK_FLG"] = "総　合　計";
        total_count["DAISU_GK"] = sum;
        $(me.grid_id).jqGrid("footerData", "set", total_count);
        $(".HMTVE050TotalS .ui-jqgrid-bdiv .BUSYO50_CELL_TITLE_BLUE_C")
            .css("background", "#99CCFF")
            .css("color", "#000000")
            .css("border-color", "#000099");
        $(".HMTVE050TotalS .ui-jqgrid-sdiv tr").css("background", "#FFFF99");
        $(".HMTVE050TotalS .ui-jqgrid-sdiv tr").css("background", "#FFFF99");
        $(".HMTVE050TotalS_tblMain_CELL_TITLE_BLUE_C")
            .css("background", "#000099")
            .css("color", "#FFFFFF");
        $(".HMTVE050TotalS_tblMain_BUSYO50_CELL_TITLE_BLUE_C")
            .css("background", "#000099")
            .css("color", "#FFFFFF");
        $(".HMTVE050TotalS_tblMain_CELL_GREEN_C").css("background", "#CCFF99");
        $(".HMTVE050TotalS_tblMain_CELL_GREEN_C .ui-jqgrid-sortable").css(
            "top",
            "0px"
        );
        $(".HMTVE050TotalS_tblMain_CELL_TITLE_BLUEGREEN_C")
            .css("background", "#006400")
            .css("color", "#FFFFFF");
        $(".HMTVE050TotalS .frozen-div.ui-state-default.ui-jqgrid-hdiv").css(
            "overflow-y",
            "hidden"
        );
        $(".HMTVE050TotalS .ui-jqgrid .jqgrow td").css("height", "21px");
        $(
            ".HMTVE050TotalS .ui-jqgrid .frozen-div .ui-jqgrid-htable th#HMTVE050TotalS_tblMain_BUSYO_RYKNM"
        ).css("padding-bottom", "4px");
        if (me.ratio === 1.5) {
            $(
                ".frozen-div .ui-first-th-ltr.HMTVE050TotalS_tblMain_BUSYO50_CELL_TITLE_BLUE_C"
            ).css("width", "101px");
            $(
                ".frozen-bdiv.ui-jqgrid-bdiv .jqgfirstrow .HMTVE050TotalS_tblMain_BUSYO50_CELL_TITLE_BLUE_C"
            ).css("width", "101px");
            setTimeout(() => {
                $(".frozen-sdiv.ui-jqgrid-sdiv .BUSYO50_CELL_TITLE_BLUE_C").css(
                    "width",
                    "101px"
                );
            }, 0);
        }
    };
    //表示ボタンクリック
    me.btnView_Click = function () {
        if (me.checkNull() == false) {
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/" + "btnView_Click";
        var data = {
            lblExhibitTerm: $(".HMTVE050TotalS.lblExhibitTerm").val(),
            ddlExhibitDay: $(".HMTVE050TotalS.ddlExhibitDay").val(),
        };
        me.ajax.receive = function (result) {
            $.jgrid.gridUnload(me.grid_id);
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
            var objReader = result["data"]["detail"];
            var CarTypeData = result["data"]["CarTypeData"];
            var sumData = result["data"]["getSumData"];
            var sum = result["data"]["setAllSum"][0]["DAISU_GK"];
            var colModels = JSON.parse(JSON.stringify(me.colModel));
            for (var i = 0; i < CarTypeData.length; i++) {
                var colmodel = {
                    name: CarTypeData[i]["SYASYU_CD"],
                    label:
                        CarTypeData[i]["SYASYU_RYKNM"] == null
                            ? " "
                            : me.HMTVE.halfToFull(
                                  CarTypeData[i]["SYASYU_RYKNM"]
                              ).replace(/-|ｰ/g, "｜"),
                    index: CarTypeData[i]["SYASYU_CD"],
                    //タイトルのclass
                    labelClasses: "HMTVE050TotalS_tblMain_CELL_GREEN_C",
                    classes: "CELL_GREEN_C",
                    width: 40,
                    align: "right",
                    sortable: false,
                };
                colModels.push(colmodel);
            }
            $(".HMTVE050TotalS.pnlList").show();
            $(me.grid_id).jqGrid({
                datatype: "local",
                caption: "",
                rownumbers: false,
                loadui: "disable",
                footerrow: true,
                shrinkToFit: false,
                autoScroll: true,
                shrinkToFit: false,
                colModel: colModels,
                // jqgridにデータがなし場合、文字表示しない
                emptyRecordRow: false,
                rowNum: 9999,
            });
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMTVE050TotalS fieldset").width()
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 189 : 253
            );

            $(me.grid_id).jqGrid("bindKeys");
            if (CarTypeData.length != 0) {
                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            className:
                                "HMTVE050TotalS_tblMain_CELL_TITLE_BLUEGREEN_C",
                            startColumnName: CarTypeData[0]["SYASYU_CD"],
                            numberOfColumns: CarTypeData.length,
                            titleText: "成約車種内訳",
                        },
                    ],
                });
            }

            $(me.grid_id).jqGrid("setFrozenColumns");

            $(me.grid_id)
                .setGridParam({
                    data: objReader,
                })
                .trigger("reloadGrid");
            me.gridComplete(sumData, sum);
            me.getSumFrozen();
            $(me.grid_id).jqGrid("setSelection", 1);
        };
        me.ajax.send(me.url, data, 0);
    };
    // 空の値をチェックする
    me.checkNull = function () {
        var $lblETStart = $(".HMTVE050TotalS.lblExhibitTerm");
        var $lblETEnd = $(".HMTVE050TotalS.lblExhibitTerm2");
        var $ddlED = $(".HMTVE050TotalS.ddlExhibitDay");
        if ($.trim($lblETStart.val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.btnETSearch");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください"
            );
            return false;
        }
        if ($.trim($lblETEnd.val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.btnETSearch");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください"
            );
            return false;
        }
        if ($.trim($ddlED.val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.btnETSearch");
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください");
            return false;
        }
        return true;
    };
    // 空の値をチェックする
    me.checkNull2 = function () {
        var $lblETStart = $(".HMTVE050TotalS.lblExhibitTerm");
        var $lblETEnd = $(".HMTVE050TotalS.lblExhibitTerm2");
        if ($.trim($lblETStart.val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.btnETSearch");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください"
            );
            return false;
        }
        if ($.trim($lblETEnd.val()).length == 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.btnETSearch");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください"
            );
            return false;
        }
        return true;
    };
    me.getSumFrozen = function () {
        $(".HMTVE050TotalS .frozen-sdiv.ui-jqgrid-sdiv").remove();
        var $sumdiv = $(".HMTVE050TotalS .ui-jqgrid-sdiv").clone();
        var $sumdiv1 = document
            .getElementsByClassName("ui-jqgrid-sdiv")[0]
            .cloneNode(true);
        $sumdiv.width("");
        $sumdiv.find("table").width("");
        $sumdiv.find("tr").html("");
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        // 20250411 lujunxia upd s
        // var hth =
        //     $(
        //         ".HMTVE050TotalS .frozen-div.ui-state-default.ui-jqgrid-hdiv"
        //     ).height() +
        //     $(".HMTVE050TotalS .frozen-bdiv.ui-jqgrid-bdiv").height();
        var hth = $("#gbox_HMTVE050TotalS_tblMain").height() - 25;
        // $sumFrozenDiv = $(
        //     '<div style="position:absolute;left:0px;top:' +
        //         (parseInt(hth, 10) + 17) +
        //         'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>'
        // );
        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;top:' +
                parseInt(hth, 10) +
                'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>'
        );
        // 20250411 lujunxia upd e
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter($(".frozen-bdiv"));
    };
    //展示会検索ボタン
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE050TotalSDialogDiv";
        //var title = "展示会検索";
        var $rootDiv = $(".HMTVE050TotalS.HMTVE-content");
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
                    $(".HMTVE050TotalS.lblExhibitTerm").val($lblETStart.html());
                    $(".HMTVE050TotalS.lblExhibitTerm2").val($lblETEnd.html());
                    $(".HMTVE050TotalS.pnlList").hide();
                    var From = $(".HMTVE050TotalS.lblExhibitTerm").val();
                    var To = $(".HMTVE050TotalS.lblExhibitTerm2").val();
                    //取得展示会開催日の日付をセットする
                    $(".HMTVE050TotalS.ddlExhibitDay").html("");
                    var days = me.DateDiff(From, To);
                    for (var i = 0; i <= days; i++) {
                        var Fromdate = new Date(From);
                        Fromdate.setDate(Fromdate.getDate() + i);
                        var strdate = Fromdate.Format("yyyy/MM/dd");
                        $("<option></option>")
                            .val(strdate)
                            .text(strdate)
                            .appendTo(".HMTVE050TotalS.ddlExhibitDay");
                    }
                    $(".HMTVE050TotalS.btnETSearch").trigger("blur");
                    setTimeout(function () {
                        //需要focus的控件
                        $(".HMTVE050TotalS.ddlExhibitDay").trigger("focus");
                    }, 100);
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
            o_HMTVE_HMTVE.HMTVE050TotalS.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    // '**********************************************************************
    // '処 理 名：'展示会開催期間
    // '関 数 名：setExhibitTermDate
    // '引 数 　：strSql
    // '戻 り 値：なし
    // '処理説明：展示会開催期間に初期値をセットする
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    me.setExhibitTermDate = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "setExhibitTermDate";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //デフォルト日付をセットする
                if (result["data"].length != 0) {
                    if (
                        result["data"][0]["END_DATE"] != "" &&
                        result["data"][0]["END_DATE"] != null
                    ) {
                        var end_date = result["data"][0]["END_DATE"];
                        if (
                            result["data"][0]["START_DATE"] != "" &&
                            result["data"][0]["START_DATE"] != null
                        ) {
                            var start_date = result["data"][0]["START_DATE"];
                            $(".HMTVE050TotalS.lblExhibitTerm").val(
                                start_date.substring(0, 4) +
                                    "/" +
                                    start_date.substring(4, 6) +
                                    "/" +
                                    start_date.substring(6, 8)
                            );
                            me.From =
                                start_date.substring(0, 4) +
                                "/" +
                                start_date.substring(4, 6) +
                                "/" +
                                start_date.substring(6, 8);
                        }
                        $(".HMTVE050TotalS.lblExhibitTerm2").val(
                            end_date.substring(0, 4) +
                                "/" +
                                end_date.substring(4, 6) +
                                "/" +
                                end_date.substring(6, 8)
                        );
                        me.To =
                            end_date.substring(0, 4) +
                            "/" +
                            end_date.substring(4, 6) +
                            "/" +
                            end_date.substring(6, 8);
                        if (
                            me.clsComFnc.CheckDate(
                                $(".HMTVE050TotalS.lblExhibitTerm2")
                            ) == false
                        ) {
                            $(".HMTVE050TotalS.lblExhibitTerm2").val("");
                        }
                        if (
                            me.clsComFnc.CheckDate(
                                $(".HMTVE050TotalS.lblExhibitTerm")
                            ) == false
                        ) {
                            $(".HMTVE050TotalS.lblExhibitTerm").val("");
                        }
                        //取得展示会開催日の日付をセットする
                        var days = me.DateDiff(me.From, me.To);
                        for (var i = 0; i <= days; i++) {
                            var Fromdate = new Date(me.From);
                            Fromdate.setDate(Fromdate.getDate() + i);
                            var strdate = Fromdate.Format("yyyy/MM/dd");
                            $("<option></option>")
                                .val(strdate)
                                .text(strdate)
                                .appendTo(".HMTVE050TotalS.ddlExhibitDay");
                        }
                        me.setDateddlExhibitDay();
                    }
                    //TO日付が存在しない場合
                    else {
                        $(".HMTVE050TotalS.lblExhibitTerm").val("");
                        $(".HMTVE050TotalS.lblExhibitTerm2").val("");
                        $(".HMTVE050TotalS.ddlExhibitDay").html("");
                        $(".HMTVE050TotalS.btnETSearch").trigger("focus");
                    }
                }
                //データが存在しない場合
                else {
                    $(".HMTVE050TotalS.lblExhibitTerm").val("");
                    $(".HMTVE050TotalS.lblExhibitTerm2").val("");
                    $(".HMTVE050TotalS.ddlExhibitDay").html("");
                    $(".HMTVE050TotalS.btnETSearch").trigger("focus");
                }
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    me.DateDiff = function (start, end) {
        var sdate = new Date(start);
        var now = new Date(end);
        var days = now.getTime() - sdate.getTime();
        var day = parseInt(days / (1000 * 60 * 60 * 24));
        return day;
    };
    // **********************************************************************
    // 処 理 名：HITNET用Excel出力ボタンクリック
    // 関 数 名：btnOutputHITNET_Click
    // 戻 り 値：なし
    // 処理説明：HITNET用Excel出力を行う
    // **********************************************************************
    me.btnOutputHITNET_Click = function () {
        var data = {
            ddlExhibitDay: $(".HMTVE050TotalS.ddlExhibitDay").val(),
            lblExhibitTerm: $(".HMTVE050TotalS.lblExhibitTerm").val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnOutputHITNET_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "W0024") {
                    me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.ddlExhibitDay");
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.ddlExhibitDay");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                } else {
                    me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.ddlExhibitDay");
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                me.Page_Clear();
                // me.setDateddlExhibitDay();
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：ロック解除クリックのイベント
    // '関 数 名：btnUnLock_Click
    // '戻 り 値：なし
    // '処理説明：ロック解除を行う
    // '**********************************************************************
    me.btnUnLock_Click = function () {
        var data = {
            lblExhibitTerm: $(".HMTVE050TotalS.lblExhibitTerm").val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnUnLock_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["number_of_rows"] <= 0) {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.ObjFocus = $(".HMTVE050TotalS.ddlExhibitDay");
                    me.clsComFnc.FncMsgBox(
                        "I9999",
                        "ロックの解除を行いました。"
                    );
                }
                me.Page_Clear();
                // me.setDateddlExhibitDay();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：当ページを初期化する
    // '関 数 名：Page_Clear
    // '引 数 １：なし
    // '戻 り 値：なし
    // '処理説明：当ページを初期の状態にセットする
    // '2009/04/02 UPD clsdb追加
    // '**********************************************************************
    me.Page_Clear = function () {
        if (
            gdmz.SessionPatternID !== me.HMTVE.CONST_ADMIN_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_HONBU_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_TESTER_PTN_NO
        ) {
            $(".HMTVE050TotalS.btnOutputHITNET").hide();
            $(".HMTVE050TotalS.btnUnLock").hide();
        }
        //表示の設定
        $(".HMTVE050TotalS.pnlList").hide();
        //展示会開催期間に初期値をセットする
        me.setExhibitTermDate();
        //フォーカス移動
        // $(".HMTVE050TotalS.ddlExhibitDay").trigger("focus");
    };
    me.setDateddlExhibitDay = function () {
        var From = me.From;
        var To = me.To;
        //取得展示会開催日の日付をセットする
        $(".HMTVE050TotalS.ddlExhibitDay").html("");
        var days = me.DateDiff(From, To);
        for (var i = 0; i <= days; i++) {
            var Fromdate = new Date(From);
            Fromdate.setDate(Fromdate.getDate() + i);
            var strdate = Fromdate.Format("yyyy/MM/dd");
            $("<option></option>")
                .val(strdate)
                .text(strdate)
                .appendTo(".HMTVE050TotalS.ddlExhibitDay");
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE050TotalS = new HMTVE.HMTVE050TotalS();
    o_HMTVE_HMTVE050TotalS.load();
    o_HMTVE_HMTVE.HMTVE050TotalS = o_HMTVE_HMTVE050TotalS;
});
