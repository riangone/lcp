/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMAUD.HMAUDSKDListSearch");

HMAUD.HMAUDSKDListSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDSKDListSearch";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDSKDListSearch_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/getListData";
    me.pager = "#HMAUDSKDListSearch_pager";
    me.sidx = "";
    me.firstload = true;
    me.gennzayiCour = "0";

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
            width: me.ratio === 1.5 ? 130 : 150,
            align: "center",
            sortable: false,
            formatter: function (_rowId, _options, row) {
                var btn = "";
                btn += row["KYOTEN_NAME"] + "・" + row["TERRITORY_NM"];
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
                        "<br />" +
                        row["CHECK_TIME1"] +
                        (row["CHECK_DATETIME1"] !== "" ||
                        row["CHECK_TIME1"] !== ""
                            ? "〜"
                            : "") +
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
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
            width: me.ratio === 1.5 ? 100 : 125,
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
                        mambers += 'HMAUDList_CELL_GREED">監査実施</a>';
                        break;
                    case "REPORT_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_ORANGE">改善期間＆<br />改善結果報告書提出</a>';
                        break;
                    case "KEY_PERSON_LIMIT":
                        mambers +=
                            'HMAUDList_CELL_PURPLE">キーマン確認実施</a>';
                        break;
                    case "AUDIT_MEET_DT":
                        mambers =
                            '<a href="javascript:gvMomColClick(&#34;' +
                            row["KYOTEN_CD"] +
                            "&#34;,&#34;cour1&#34;,&#34;" +
                            row["TERRITORY_KTN"] +
                            "&#34;" +
                            ')"class="HMAUDList_CELL_BLUE">監査人Mtg</a>';
                        break;
                    default:
                        mambers = "";
                        break;
                }
                return mambers;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDSKDListSearch.button",
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
                $(".HMAUDSKDListSearch.pnlList").width() - 14
            );
        }, 500);
    });
    $(".HMAUDSKDListSearch.coursSearchInput").change(function () {
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
                cour: $(".HMAUDSKDListSearch.coursSearchInput").val(),
            },
            me.complete_fun
        );
        $(me.grid_id).jqGrid("bindKeys");
    };

    me.complete_fun = function (_returnFLG, data) {
        if (data["error"]) {
            $("#gbox_HMAUDSKDListSearch_tblMain").hide();
            if (data["error"] == "W0024") {
                me.clsComFnc.FncMsgBox("W0024");
            } else if (data["error"] == "W0024NOTNOW") {
                $(".HMAUDSKDListSearch.coursSearchInput")
                    .find("option")
                    .remove();
                if (data["cour"].length > 0) {
                    var courAll = data["cour"];
                    me.allCourData = courAll;
                    for (var i = 0; i < courAll.length; i++) {
                        //クールselect
                        $("<option></option>")
                            .val(courAll[i]["COURS"])
                            .text(courAll[i]["COURS"])
                            .appendTo(".HMAUDSKDListSearch.coursSearchInput");
                        if (courAll[i]["COURS_NOW"] == "1") {
                            //現在のクール数
                            me.gennzayiCour = courAll[i]["COURS"];
                        }
                    }
                }
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDSKDListSearch.coursSearchInput").val(me.gennzayiCour);
                //クールchange
                me.fncCourChange();
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }
            return;
        } else {
            $("#gbox_HMAUDSKDListSearch_tblMain").show();
        }
        me.listData = data["rows"];
        me.headerData = data["headerData"];
        me.courData = data["courData"];
        if (me.firstload == true) {
            if (data["cour"] && data["cour"].length > 0) {
                $(".HMAUDSKDListSearch.coursSearchInput")
                    .find("option")
                    .remove();
                var courAll = data["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDSKDListSearch.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
            }
            //検索条件・クールには 現在のクール数を初期表示
            $(".HMAUDSKDListSearch.coursSearchInput").val(me.gennzayiCour);
            //クールchange
            me.fncCourChange();

            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDSKDListSearch.pnlList").width() - 15
            );
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        addclass: "HMAUDSKDListSearch_tblMain_CELL_TITLE",
                        startColumnName: "CHECK_MEMBER1",
                        numberOfColumns: 9,
                        titleText: "第" + me.courData["cour1"] + "クール",
                    },
                ],
            });
            me.firstload = false;
        } else {
            $(".HMAUDSKDListSearch_tblMain_CELL_TITLE").html(
                "第" + me.courData["cour1"] + "クール"
            );
        }
        for (key in me.headerData) {
            $("#jqgh_HMAUDSKDListSearch_tblMain_" + key).html(
                me.headerData[key]
            );
        }
        $(
            ".HMAUDSKDListSearch .frozen-div.ui-state-default.ui-jqgrid-hdiv"
        ).css("overflow-y", "hidden");
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
        $(".HMAUDSKDListSearch .HMAUDList_CELL_GREED")
            .parent()
            .css("background", "#b0d99b");
        $(".HMAUDSKDListSearch .HMAUDList_CELL_ORANGE")
            .parent()
            .css("background", "#ffc899");
        $(".HMAUDSKDListSearch .HMAUDList_CELL_PURPLE")
            .parent()
            .css("background", "#e0d0e9");
        $(".HMAUDSKDListSearch .HMAUDList_CELL_BLUE")
            .parent()
            .css("background", "#a8cfff");
        $(".HMAUDSKDListSearch .CHECK_MEMBER_COLUMN").css("padding-top", "5px");
        $(".HMAUDSKDListSearch .CHECK_MEMBER_COLUMN").css(
            "padding-bottom",
            "5px"
        );

        if (me.listData.length > 0) {
            if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    $("#HMAUDSKDListSearch_tblMain").height() > 490
                        ? 490
                        : $("#HMAUDSKDListSearch_tblMain").height() + 20
                );
            } else {
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    $("#HMAUDSKDListSearch_tblMain").height() > 510
                        ? 510
                        : $("#HMAUDSKDListSearch_tblMain").height() + 20
                );
            }
        } else {
            $(me.grid_id).css("height", "1px");
        }
        me.setTableSize();
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDSKDListSearch.pnlList").width() - 15
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var tableHeight = mainHeight - 170;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - 175;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };

    //'**********************************************************************
    //'処 理 名：クールchange
    //'関 数 名：fncCourChange
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：指摘事項NO76:クールを選択したら開始日～終了日を表示
    //'**********************************************************************
    me.fncCourChange = function () {
        var cour = $(".HMAUDSKDListSearch.coursSearchInput").val();
        var foundDT = undefined;
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDSKDListSearch.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
    };
    me.jqgrid_reload = function () {
        $(me.grid_id).jqGrid("clearGridData");
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            {
                cour: $(".HMAUDSKDListSearch.coursSearchInput").val(),
            },
            me.complete_fun
        );
    };

    // **********************************************************************
    // 処 理 名：[監査日時]列クリック
    // 関 数 名：gvcheckDateColClick
    // 戻 り 値：なし
    // 処理説明：監査実績入力画面遷移
    // **********************************************************************
    gvcheckDateColClick = function (kyoten_cd, cour, territory) {
        gdmz.SessionPrePG = "HMAUDSKDListSearch";
        gdmz.SessionCour = me.courData[cour];
        gdmz.SessionKyotenCD = kyoten_cd;
        gdmz.territory = territory;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDSKDListSearch"
        );
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
        gdmz.SessionPrePG = "HMAUDSKDListSearch";
        gdmz.SessionCour = me.courData[cour];
        gdmz.SessionKyotenCD = kyoten_cd;
        gdmz.territory = territory;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDSKDListSearch"
        );
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#HMAUDReportInput");
    };
    // **********************************************************************
    // 処 理 名：YYYY年MM月（６ヶ月分）(監査人Mtg)　クリック
    // 関 数 名：gvMomColClick
    // 戻 り 値：なし
    // 処理説明：議事録入力画面遷移
    // **********************************************************************
    gvMomColClick = function (_kyoten_cd, cour) {
        // gdmz.SessionPrePG = 'HMAUDSKDListSearch';
        gdmz.SessionCour = me.courData[cour];
        // gdmz.SessionKyotenCD = kyoten_cd;
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDSKDListSearch"
        );
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#HMAUDGijirokuULDL");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDSKDListSearch = new HMAUD.HMAUDSKDListSearch();
    o_HMAUD_HMAUDSKDListSearch.load();
    o_HMAUD_HMAUD.HMAUDSKDListSearch = o_HMAUD_HMAUDSKDListSearch;
});
