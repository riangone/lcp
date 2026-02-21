/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                            　　　担当
 * YYYYMMDD           #ID                          XXXXXX                         　　　 FCSDL
 * 20230802           Bug  　2ページ目にデータを更新してから、クールまたはページを変更する、　lujunxia
 *　　　　　　　　　　　　　　　　　　選択位置がトップに戻らない問題
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDSKDList");

HMAUD.HMAUDSKDList = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDSKDList";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDSKDList_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/getListData";
    me.pager = "#HMAUDSKDList_pager";
    me.sidx = "";
    me.firstload = true;
    me.gennzayiCour = "0";
    me.page = 0;
    me.rowId = -1;
    me.tbl1width = me.ratio === 1.5 ? 605 : 830;
    me.grid_id2 = "#HMAUDSKDList_tblRiyou";
    me.g_url2 = me.sys_id + "/" + me.id + "/getRiyouListData";
    me.pager2 = "#HMAUDSKDList_tblRiyou_pager";
    me.riyouListReloadTimeout = true;
    me.maingridpart = me.ratio === 1.5 ? 0.6 : 0.65;

    me.option = {
        caption: "",
        rownumbers: false,
        rowNum: 60,
        multiselect: false,
        emptyRecordRow: false,
        colModel: me.colModel,
        rowList: [20, 40, 60],
        pager: me.pager,
        loadui: "disable",
        autoScroll: true,
        recordpos: "right",
    };

    me.colModel = [
        {
            name: "KYOTEN_CD",
            label: "監査拠点コード",
            index: "KYOTEN_CD",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "COURS1",
            label: "クール",
            index: "COURS1",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "TERRITORY_KTN",
            label: "領域",
            index: "TERRITORY_KTN",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "CHECK_MEMBER1",
            label: "監査人",
            index: "CHECK_MEMBER1",
            classes: "CHECK_MEMBER_COLUMN",
            width: me.ratio === 1.5 ? 130 : 200,
            align: "left",
            sortable: false,
        },
        {
            name: "KYOTEN_NAME1",
            label: "監査拠点",
            index: "KYOTEN_NAME1",
            width: me.ratio === 1.5 ? 80 : 100,
            align: "center",
            sortable: false,
            formatter: function (_rowId, options, row) {
                var btn = "";
                if (
                    parseInt($(".HMAUDSKDList.coursSearchInput").val()) <
                    parseInt(me.gennzayiCour)
                ) {
                    btn += row["KYOTEN_NAME"] + "<br />" + row["TERRITORY_NM"];
                } else {
                    btn +=
                        '&nbsp;<a href="javascript:gvkyotenColClick(&#34;' +
                        row["KYOTEN_CD"] +
                        "&#34;,&#34;" +
                        row["KYOTEN_NAME"] +
                        "&#34;,&#34;" +
                        row["TERRITORY_KTN"] +
                        "&#34;,&#34;cour1&#34;," +
                        options["rowId"] +
                        ')">' +
                        row["KYOTEN_NAME"] +
                        "<br />" +
                        row["TERRITORY_NM"] +
                        "</a>&nbsp;";
                }
                return btn;
            },
        },
        {
            name: "CHECK_DATETIME1",
            label: "監査日時",
            index: "CHECK_DATETIME1",
            width: me.ratio === 1.5 ? 80 : 100,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                if (
                    row["CHECK_DATETIME1"] !== "" ||
                    row["CHECK_TIME1"] !== ""
                ) {
                    mambers +=
                        '&nbsp;<a href="javascript:gvcheckDateColClick(&#34;' +
                        row["KYOTEN_CD"] +
                        "&#34;,&#34;cour1&#34;,&#34;" +
                        row["TERRITORY_KTN"] +
                        "&#34;" +
                        ')">' +
                        row["CHECK_DATETIME1"] +
                        "</a>&nbsp;";
                } else {
                    mambers +=
                        '<div style="height:100%;cursor:pointer" onclick="javascript:gvcheckDateColClick(&#34;' +
                        row["KYOTEN_CD"] +
                        "&#34;,&#34;cour1&#34;,&#34;" +
                        row["TERRITORY_KTN"] +
                        "&#34;" +
                        ')">';
                }

                return mambers;
            },
        },
        {
            name: "COUR1_MONTH1",
            label: "",
            index: "COUR1_MONTH1",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH1"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
        {
            name: "COUR1_MONTH2",
            label: "",
            index: "COUR1_MONTH2",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH2"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
        {
            name: "COUR1_MONTH3",
            label: "",
            index: "COUR1_MONTH3",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH3"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
        {
            name: "COUR1_MONTH4",
            label: "",
            index: "COUR1_MONTH4",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH4"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
        {
            name: "COUR1_MONTH5",
            label: "",
            index: "COUR1_MONTH5",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH5"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
        {
            name: "COUR1_MONTH6",
            label: "",
            index: "COUR1_MONTH6",
            width: me.ratio === 1.5 ? 42 : 60,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var mambers = "";
                mambers +=
                    '<a href="javascript:gvReportColClick(&#34;' +
                    row["KYOTEN_CD"] +
                    "&#34;,&#34;cour1&#34;,&#34;" +
                    row["TERRITORY_KTN"] +
                    "&#34;" +
                    ')"class="';
                switch (row["COUR1_MONTH6"]) {
                    case "PLAN_DT":
                        mambers += 'HMAUDList_CELL_GREED">監査</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善＆<br />結果報告</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン<br />確認</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人<br />Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
    ];

    me.option2 = {
        rownumbers: false,
        rowNum: 0,
        caption: "",
        multiselect: false,
        shrinkToFit: false,
        pager: me.pager2,
    };

    me.colModel2 = [
        {
            name: "AUDITOR_NAME",
            label: "監査人",
            index: "AUDITOR_NAME",
            width: me.ratio === 1.5 ? 60 : 80,
            align: "left",
            sortable: false,
            frozen: true,
        },
        {
            name: "AMPM",
            label: " ",
            index: "AMPM",
            width: me.ratio === 1.5 ? 20 : 25,
            align: "left",
            sortable: false,
            frozen: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDSKDList.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDSKDList.pnlList").width() * me.maingridpart >
                    me.tbl1width
                    ? me.tbl1width
                    : $(".HMAUDSKDList.pnlList").width() * me.maingridpart
            );
            if ($(me.grid_id2)[0].grid) {
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_id2,
                    $(".HMAUDSKDList.pnlList").width() -
                        $("#gbox_HMAUDSKDList_tblMain").width() -
                        40
                );
            }
        }, 500);
    });
    $(".HMAUDSKDList.coursSearchInput").change(function () {
        //20230802 lujunxia ins s
        me.page = 1;
        $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
        //20230802 lujunxia ins e
        me.fncCourChange();
        me.jqgrid_reload();
    });
    //指摘事項NO58:ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
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
    // **********************************************************************
    // 処 理 名：ページロード
    // 関 数 名：Page_Load
    // 戻 り 値：なし
    // 処理説明：ページ初期化
    // **********************************************************************
    me.Page_Load = function () {
        $.jgrid.gridUnload(me.grid_id);
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            {
                cour: $(".HMAUDSKDList.coursSearchInput").val(),
            },
            me.complete_fun
        );
        if ($(".HMAUDSKDToroku.body").dialog("isOpen")) {
            $(".HMAUDSKDToroku.body").dialog("close");
        }
        //20230802 lujunxia ins s
        $(me.grid_id).jqGrid("setGridParam", {
            //ページをめくる事件
            onPaging: function () {
                setTimeout(() => {
                    $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
                }, 50);
            },
        });
        //20230802 lujunxia ins e
        $(me.grid_id).jqGrid("bindKeys");
    };

    me.complete_fun = function (_returnFLG, data) {
        if (data["error"]) {
            $("#gbox_HMAUDSKDList_tblMain").hide();
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else if (data["error"] == "W0024NOTNOW") {
                $(".HMAUDSKDList.coursSearchInput").find("option").remove();
                if (data["cour"].length > 0) {
                    var courAll = data["cour"];
                    me.allCourData = courAll;
                    for (var i = 0; i < courAll.length; i++) {
                        //クールselect
                        $("<option></option>")
                            .val(courAll[i]["COURS"])
                            .text(courAll[i]["COURS"])
                            .appendTo(".HMAUDSKDList.coursSearchInput");
                        if (courAll[i]["COURS_NOW"] == "1") {
                            //現在のクール数
                            me.gennzayiCour = courAll[i]["COURS"];
                        }
                    }
                }
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDSKDList.coursSearchInput").val(me.gennzayiCour);
                //クールchange
                me.fncCourChange();
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }
            return;
        } else {
            $("#gbox_HMAUDSKDList_tblMain").show();
        }
        me.listData = data["rows"];
        me.headerData = data["headerData"];
        me.courData = data["courData"];
        if (me.firstload == true) {
            if (data["cour"] && data["cour"].length > 0) {
                $(".HMAUDSKDList.coursSearchInput").find("option").remove();
                var courAll = data["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDSKDList.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDSKDList.coursSearchInput").val(me.gennzayiCour);
            }
            //クールchange
            me.fncCourChange();

            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDSKDList.pnlList").width() * me.maingridpart >
                    me.tbl1width
                    ? me.tbl1width
                    : $(".HMAUDSKDList.pnlList").width() * me.maingridpart
            );
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        addclass: "HMAUDSKDList_tblMain_CELL_TITLE",
                        startColumnName: "CHECK_MEMBER1",
                        numberOfColumns: 9,
                        titleText: "第" + me.courData["cour1"] + "クール",
                    },
                ],
            });
            me.firstload = false;
        } else {
            $(".HMAUDSKDList_tblMain_CELL_TITLE").html(
                "第" + me.courData["cour1"] + "クール"
            );
        }
        for (key in me.headerData) {
            $("#jqgh_HMAUDSKDList_tblMain_" + key).html(me.headerData[key]);
            $("#jqgh_HMAUDSKDList_tblMain_" + key).on("click", function (e) {
                me.monthClick($(e.target).html());
            });
        }
        $(".HMAUDSKDList .frozen-div.ui-state-default.ui-jqgrid-hdiv").css(
            "overflow-y",
            "hidden"
        );
        //１行目選択
        if (data["page"] == "1") {
            //１行目を選択状態にする
            $(me.grid_id).jqGrid("setSelection", "0");
        } else {
            //ページをめくる後,１行目を選択状態にする
            var selRow = $(".ui-pg-selbox").val() * (data["page"] - 1);
            $(me.grid_id).jqGrid("setSelection", selRow);
        }

        // $(me.grid_id).jqGrid('setSelection', 0, true);
        $(".HMAUDSKDList .HMAUDList_CELL_GREED")
            .parent()
            .css("background", "#b0d99b");
        $(".HMAUDSKDList .HMAUDList_CELL_ORANGE")
            .parent()
            .css("background", "#ffc899");
        $(".HMAUDSKDList .HMAUDList_CELL_PURPLE")
            .parent()
            .css("background", "#e0d0e9");
        $(".HMAUDSKDList .HMAUDList_CELL_BLUE")
            .parent()
            .css("background", "#a8cfff");
        // $('.HMAUDSKDList .HMAUDSKDList_tblMain_CELL_TITLE_YELLOW').css('background', '#ffff73');
        // $('.HMAUDSKDList .HMAUDSKDList_tblMain_CELL_TITLE_GREED').css('background', '#9bf396');
        // $('.HMAUDSKDList .ui-th-column').css('background', '#bdbdbd');
        $(".HMAUDSKDList .CHECK_MEMBER_COLUMN").css("padding-top", "5px");
        $(".HMAUDSKDList .CHECK_MEMBER_COLUMN").css("padding-bottom", "5px");
        // $('.HMAUDSKDList .frozen-div .ui-jqgrid-labels.jqg-third-row-header').html('').css('height', '45px');

        if (me.listData.length > 0) {
            if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    $("#HMAUDSKDList_tblMain").height() > 490
                        ? 490
                        : $("#HMAUDSKDList_tblMain").height() + 20
                );
            } else {
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    $("#HMAUDSKDList_tblMain").height() > 510
                        ? 510
                        : $("#HMAUDSKDList_tblMain").height() + 20
                );
            }
        } else {
            $(me.grid_id).css("height", "1px");
        }
        me.setTableSize();
        me.page = 0;
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDSKDList.pnlList").width() * me.maingridpart > me.tbl1width
                ? me.tbl1width
                : $(".HMAUDSKDList.pnlList").width() * me.maingridpart
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var tableHeight = mainHeight - 170;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - 175;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
        if ($(me.grid_id2)[0].grid) {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id2,
                $(".HMAUDSKDList.pnlList").width() -
                    $("#gbox_HMAUDSKDList_tblMain").width() -
                    40
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id2,
                tableHeight + (me.ratio === 1.5 ? 0 : 4)
            );
        }

        if (me.rowId > -1) {
            $(me.grid_id).jqGrid("setSelection", me.rowId, true);
            me.rowId = -1;
        }
    };
    // **********************************************************************
    // 処 理 名：監査拠点クリック
    // 関 数 名：gvkyotenColClick
    // 戻 り 値：なし
    // 処理説明：監査スケジュール登録画面の表示
    // **********************************************************************
    gvkyotenColClick = function (kyoten_cd, kyoten_nm, territory, cour, rowId) {
        me.riyouListReloadTimeout = false;
        me.page = $(me.grid_id).getGridParam("page");
        me.rowId = rowId;
        $(me.grid_id).jqGrid("setSelection", rowId, true);
        var frmId = "HMAUDSKDToroku";
        var dialogdiv = "HMAUDSKDListDialogDiv";
        // var title = "監査スケジュール登録";
        var $rootDiv = $(".HMAUDSKDList.HMAUD-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "cour").insertAfter($rootDiv);
        $("<div></div>").attr("id", "kyotenCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "kyotenNM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "territory").insertAfter($rootDiv);
        $("<div></div>").attr("id", "auditMeetDt").insertAfter($rootDiv);
        $("<div></div>").attr("id", "courDate").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $cour = $rootDiv.parent().find("#cour");
        var $kyotenCD = $rootDiv.parent().find("#kyotenCD");
        var $kyotenNM = $rootDiv.parent().find("#kyotenNM");
        var $territory = $rootDiv.parent().find("#territory");
        var $auditMeetDt = $rootDiv.parent().find("#auditMeetDt");
        var $courDate = $rootDiv.parent().find("#courDate");
        if (cour == "cour1") {
            $cour.html(me.courData["cour1"]);
            $auditMeetDt.html(me.courData["cour1_end_dt"]);
            $courDate.html(
                me.courData["cour1_start_dt"] +
                    " ～ " +
                    me.courData["cour1_end_dt"]
            );
        } else {
            $cour.html(me.courData["cour2"]);
            $auditMeetDt.html(me.courData["cour2_end_dt"]);
            $courDate.html(
                me.courData["cour2_start_dt"] +
                    " ～ " +
                    me.courData["cour2_end_dt"]
            );
        }
        $kyotenCD.html(kyoten_cd);
        $kyotenNM.html(kyoten_nm);
        $territory.html(territory);
        $cour.hide();
        $kyotenCD.hide();
        $kyotenNM.hide();
        $territory.hide();
        $auditMeetDt.hide();
        $courDate.hide();

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    me.jqgrid_reload();
                }
                $RtnCD.remove();
                $cour.remove();
                $kyotenCD.remove();
                $kyotenNM.remove();
                $territory.remove();
                $auditMeetDt.remove();
                $courDate.remove();
                $("#" + dialogdiv).remove();
            }

            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMAUD_HMAUD.HMAUDSKDList.HMAUDSKDToroku.before_close =
                before_close;
        };
    };
    me.monthClick = function (monthText) {
        $.jgrid.gridUnload(me.grid_id2);
        me.riyouListReloadTimeout = true;
        clearTimeout(me.setTimeoutWork);
        var year = monthText.substring(0, 4);
        var month = monthText.substring(5, 7);
        var days = new Date(year, month, 0).getDate();
        var cols2 = JSON.parse(JSON.stringify(me.colModel2));
        for (var i = 1; i <= days; i++) {
            var day = ("0" + i).slice(-2);
            var dateStr = year + "-" + month + "-" + day;
            cols2.push({
                name: dateStr,
                label: i,
                index: dateStr,
                width: me.ratio === 1.5 ? 15 : 25,
                align: "center",
                sortable: false,
            });
        }

        me.complete_fun2 = function (_returnFLG, data) {
            if (data["error"]) {
                $("#gbox_HMAUDSKDList_tblRiyou").hide();
                if (data["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", data["error"]);
                }
                return;
            } else {
                var num = Math.floor(Math.random() * 100000);
                num = num.toString().padStart(5, "0");
                var className = "HMAUDSKDList_tblRiyou_CELL_TITLE" + num;
                $(me.grid_id2).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            className: className,
                            startColumnName: "AUDITOR_NAME",
                            numberOfColumns: 2,
                            titleText: month + "月",
                        },
                        {
                            startColumnName: year + "-" + month + "-" + "01",
                            numberOfColumns: days,
                            titleText: "",
                        },
                    ],
                });
                setTimeout(function () {
                    $(me.grid_id2).jqGrid("setFrozenColumns");
                }, 0);
                $("#gbox_HMAUDSKDList_tblRiyou").show();

                me.setTableSize();
                me.setTimeoutWork = setTimeout(function () {
                    if (
                        me.riyouListReloadTimeout &&
                        document.getElementsByClassName(className).length > 0 &&
                        document.getElementById("tabs_HMAUD").style.display ===
                            "block"
                    ) {
                        me.monthClick(monthText);
                    }
                }, 30000);
            }
        };

        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id2,
            me.g_url2,
            cols2,
            me.pager2,
            "",
            me.option,
            {
                y: year,
                m: month,
            },
            me.complete_fun2
        );

        var footer = document.getElementById("pg_HMAUDSKDList_tblRiyou_pager");
        footer.innerHTML = "";
        $("#gbox_HMAUDSKDList_tblRiyou").hide();
    };

    me.riyouListReload = function () {
        if (
            me.riyouListReloadTimeout &&
            document.getElementsByClassName("HMAUDSKDList_tblRiyou_CELL_TITLE")
                .length > 0
        ) {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id2,
                {
                    cour: $(".HMAUDSKDList.coursSearchInput").val(),
                },
                me.complete_fun2,
                me.page,
                true
            );
        } else {
            clearTimeout(me.setTimeoutWork);
        }
    };
    //'**********************************************************************
    //'処 理 名：クールchange
    //'関 数 名：fncCourChange
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：指摘事項NO76:クールを選択したら開始日～終了日を表示
    //'**********************************************************************
    me.fncCourChange = function () {
        var cour = $(".HMAUDSKDList.coursSearchInput").val();
        var foundDT = undefined;
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDSKDList.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
    };
    me.jqgrid_reload = function () {
        // $(me.grid_id).jqGrid("clearGridData");
        $("#gbox_HMAUDSKDList_tblRiyou").hide();
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            {
                cour: $(".HMAUDSKDList.coursSearchInput").val(),
            },
            me.complete_fun,
            me.page,
            true
        );
    };

    // **********************************************************************
    // 処 理 名：[監査日時]列クリック
    // 関 数 名：gvcheckDateColClick
    // 戻 り 値：なし
    // 処理説明：監査実績入力画面遷移
    // **********************************************************************
    gvcheckDateColClick = function (kyoten_cd, cour, territory) {
        gdmz.SessionPrePG = "HMAUDSKDList";
        gdmz.SessionCour = me.courData[cour];
        gdmz.SessionKyotenCD = kyoten_cd;
        gdmz.territory = territory;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree("deselect_node", "#HMAUDSKDList");
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#HMAUDKansaJissekiInput"
        );
    };
    // **********************************************************************
    // 処 理 名：YYYY年MM月（６ヶ月分）(監査人Mtg以外)　クリック
    // 関 数 名：gvReportColClick
    // 戻 り 値：なし
    // 処理説明：報告書入力画面遷移
    // **********************************************************************
    gvReportColClick = function (kyoten_cd, cour, territory) {
        gdmz.SessionPrePG = "HMAUDSKDList";
        gdmz.SessionCour = me.courData[cour];
        gdmz.SessionKyotenCD = kyoten_cd;
        gdmz.territory = territory;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree("deselect_node", "#HMAUDSKDList");
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#HMAUDReportInput");
    };
    // **********************************************************************
    // 処 理 名：YYYY年MM月（６ヶ月分）(監査人Mtg)　クリック
    // 関 数 名：gvMomColClick
    // 戻 り 値：なし
    // 処理説明：議事録入力画面遷移
    // **********************************************************************
    gvMomColClick = function (_kyoten_cd, cour) {
        // gdmz.SessionPrePG = 'HMAUDSKDList';
        gdmz.SessionCour = me.courData[cour];
        // gdmz.SessionKyotenCD = kyoten_cd;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree("deselect_node", "#HMAUDSKDList");
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#HMAUDGijirokuULDL");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDSKDList = new HMAUD.HMAUDSKDList();
    o_HMAUD_HMAUDSKDList.load();
    o_HMAUD_HMAUD.HMAUDSKDList = o_HMAUD_HMAUDSKDList;
});
