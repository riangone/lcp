/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                   FCSDL
 * 20231128      			出力対象件数とＳＱＬ実行結果が一致しない                                   yin
 * 20240322        本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい           lujunxia
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKReOut4OBC");

HDKAIKEI.HDKReOut4OBC = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.grid_grdGroupList_id = "#HDKReOut4OBC_grdGroupList";
    me.grid_pnlCsvOut_id = "#HDKReOut4OBC_pnlCsvOut";
    me.pager = "#HDKReOut4OBC_pager";
    me.sys_id = "HDKAIKEI";

    me.id = "HDKReOut4OBC";
    //仕訳データの取得
    me.g_urlGroupAndSyohy =
        me.sys_id + "/" + me.id + "/" + "fncSelGroupAndSyohyShiwakeData";
    //検索
    me.g_urlKensaku = me.sys_id + "/" + me.id + "/" + "Kensaku_Click";
    //グループ名 CSV出力処理は必要です
    me.lblGroupNo = "";
    //部署コード
    me.allBusyo = "";
    //担当者コード
    me.allSyain = "";
    //CSV出力日Start
    me.cboYMStart = "";
    me.data = "";
    //jqgrid reload-me.flg='':検索 me.flg=csvout:CSV出力後
    me.flg = "";
    me.option1 = {
        pagerpos: "center",
        viewrecords: false,
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
        shrinkToFit: true,
    };

    me.option2 = {
        multiselect: true,
        caption: "",
        // 20231128 YIN INS S
        rowNum: 9999,
        // 20231128 YIN INS E
        rownumbers: true,
        scroll: false,
    };

    me.colModel1 = [
        {
            label: "",
            name: "GROUP_NO",
            index: "GROUP_NO",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "グループ名",
            name: "CSV_GROUP_NM",
            index: "CSV_GROUP_NM",
            align: "left",
            search: false,
            width: 148,
            sortable: false,
        },
        {
            label: "出力日時",
            name: "CSV_OUT_DT",
            index: "CSV_OUT_DT",
            align: "left",
            search: false,
            width: 148,
            sortable: false,
        },
        {
            label: "合計金額",
            name: "SUMMONEY",
            index: "SUMMONEY",
            align: "right",
            search: false,
            width: 94,
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            label: "",
            name: "KEIRI_DT",
            index: "KEIRI_DT",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "",
            index: "lblSum",
            width: 64,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"grdGroupObcList_RowCommand('" +
                    rowObject["GROUP_NO"] +
                    "','" +
                    rowObject["KEIRI_DT"] +
                    "','" +
                    "2" +
                    "')\" id = '" +
                    rowObject.clid +
                    "_btnSelect' class=\"HDKReOut4OBC btnSelect Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>選択</button>";
                return detail;
            },
        },
    ];
    me.colModel2 = [
        {
            label: "",
            name: "SYOHYO_NO",
            index: "SYOHYO_NO",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "UPD_FLG",
            index: "UPD_FLG",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "UPD_DATE",
            index: "UPD_DATE",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "EDA_NO",
            index: "EDA_NO",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "CHK_CSV_STATUS",
            index: "CHK_CSV_STATUS",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "証憑№",
            name: "SYOHYO_NO_VIEW",
            index: "SYOHYO_NO_VIEW",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 120 : 166,
            sortable: false,
        },
        {
            label: "借方科目",
            name: "KARIKATA",
            index: "KARIKATA",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 100 : 134,
            sortable: false,
            //20240322 lujunxia ins s
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["L_KAMOKU"] !== null &&
                    rowObject["L_KAMOKU"] !== "" &&
                    rowObject["L_KOUMKU"] !== null &&
                    rowObject["L_KOUMKU"] !== ""
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["L_KAMOKU"] +
                        "<br>" +
                        rowObject["L_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["L_KAMOKU"] !== null &&
                        rowObject["L_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["L_KAMOKU"];
                    }
                    if (
                        rowObject["L_KOUMKU"] !== null &&
                        rowObject["L_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["L_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            //20240322 lujunxia ins e
        },
        {
            label: "貸方科目",
            name: "KASHIKATA",
            index: "KASHIKATA",
            align: "left",
            search: false,
            width: me.ratio === 1.5 ? 100 : 134,
            sortable: false,
            //20240322 lujunxia ins s
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["R_KAMOKU"] !== null &&
                    rowObject["R_KAMOKU"] !== "" &&
                    rowObject["R_KAMOKU"] !== undefined &&
                    rowObject["R_KOUMKU"] !== null &&
                    rowObject["R_KOUMKU"] !== "" &&
                    rowObject["R_KOUMKU"] !== undefined
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["R_KAMOKU"] +
                        "<br>" +
                        rowObject["R_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["R_KAMOKU"] !== null &&
                        rowObject["R_KAMOKU"] !== "" &&
                        rowObject["R_KAMOKU"] !== undefined
                    ) {
                        detail += rowObject["R_KAMOKU"];
                    }
                    if (
                        rowObject["R_KOUMKU"] !== null &&
                        rowObject["R_KOUMKU"] !== "" &&
                        rowObject["R_KOUMKU"] !== undefined
                    ) {
                        detail += rowObject["R_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            //20240322 lujunxia ins e
        },
        {
            label: "金額",
            name: "KINGAKU",
            index: "KINGAKU",
            align: "right",
            search: false,
            width: me.ratio === 1.5 ? 85 : 96,
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "",
            index: "btnEdit",
            width: me.ratio === 1.5 ? 35 : 50,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openEditShiwakeOBC('" +
                    rowObject["SYOHYO_NO_VIEW"] +
                    "','" +
                    rowObject["SYOHYO_NO"].substring(0, 1) +
                    "','" +
                    rowObject["RENBAN"] +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HDKReOut4OBC UPD_DATE Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>編集</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKReOut4OBC.btnBusyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.btnCsvOut",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.btnTantou",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.Kensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.CSVStart",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.CSVEnd",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".HDKReOut4OBC.txtInputKeiriDt",
        type: "datepicker",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //部署コードボタンクリック
    $(".HDKReOut4OBC.btnBusyo").click(function () {
        me.openSearchDialog("btnBusyo");
    });
    //担当者コードボタンクリック
    $(".HDKReOut4OBC.btnTantou").click(function () {
        me.openSearchDialog("btnTantou");
    });

    //検索ボタンクリック
    $(".HDKReOut4OBC.Kensaku").click(function () {
        me.Kensaku_click();
    });
    //担当者コード変更してフォーカスを失う
    $(".HDKReOut4OBC.ltxtTantouCD").on("blur", function () {
        $(".HDKReOut4OBC.TantouNM").val("");
        if ($(this).val() !== "") {
            me.ltxtTantouCD_CheckedChanged($(this).val());
        }
    });
    //部署コード変更してフォーカスを失う
    $(".HDKReOut4OBC.ltxtBusyoCD").on("blur", function () {
        $(".HDKReOut4OBC.BusyoNM").val("");
        if ($(this).val() !== "") {
            me.ltxtBusyoCD_CheckedChanged($(this).val());
        }
    });
    //担当者コード,部署コード,グループ名変更
    $(
        ".HDKReOut4OBC.ltxtTantouCD,.HDKReOut4OBC.ltxtBusyoCD,.HDKReOut4OBC.txtGroupName"
    ).change(function () {
        me.txtGroupName_CheckedChanged();
    });
    $(".HDKReOut4OBC.CSVStart").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "CSVStart");
        } else {
            $(".HDKReOut4OBC.grdGroupListTableRow").hide();
            $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        }
    });
    $(".HDKReOut4OBC.CSVEnd").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "CSVEnd");
        } else {
            $(".HDKReOut4OBC.grdGroupListTableRow").hide();
            $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        }
    });

    //CSV出力ボタンクリック
    $(".HDKReOut4OBC.btnCsvOut").click(function () {
        me.btnCsvOut_Click();
    });

    //経理処理日
    $(".HDKReOut4OBC.txtInputKeiriDt").on("blur", function () {
        me.dateChanged(this, "txtInputKeiriDt");
    });
    var ele = document.querySelector(".HDKReOut4OBC.HDKAIKEI-content");
    var resizeObserver = new ResizeObserver(function () {
        if ($(".HDKReOut4OBC.PnlCsvOutTableRow").css("display") == "block") {
            var widthTotal = $(".HDKReOut4OBC fieldset").width();
            if (
                navigator.userAgent.toUpperCase().indexOf("CHROME") > -1 ||
                navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1
            ) {
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_grdGroupList_id,
                    widthTotal * 0.45
                );
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_pnlCsvOut_id,
                    widthTotal * 0.55
                );
            } else {
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_grdGroupList_id,
                    widthTotal * 0.4
                );
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_pnlCsvOut_id,
                    widthTotal * 0.6
                );
            }
        }
    });
    resizeObserver.observe(ele);
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
    //**********************************************************************
    //処 理 名：LOAD
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：LOAD
    //**********************************************************************
    me.Page_Load = function () {
        //jqgrid初期
        {
            //グループ一覧
            gdmz.common.jqgrid.init2(
                me.grid_grdGroupList_id,
                me.g_urlKensaku,
                me.colModel1,
                me.pager,
                "",
                me.option1
            );
            //出力対象一覧
            gdmz.common.jqgrid.init2(
                me.grid_pnlCsvOut_id,
                me.g_urlGroupAndSyohy,
                me.colModel2,
                "",
                "",
                me.option2
            );
            var widthTotal = $(".HDKReOut4OBC fieldset").width();
            if (
                navigator.userAgent.toUpperCase().indexOf("CHROME") > -1 ||
                navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1
            ) {
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_grdGroupList_id,
                    widthTotal * 0.45
                );
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_pnlCsvOut_id,
                    widthTotal * 0.55
                );
            } else {
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_grdGroupList_id,
                    widthTotal * 0.4
                );
                gdmz.common.jqgrid.set_grid_width(
                    me.grid_pnlCsvOut_id,
                    widthTotal * 0.6
                );
            }
            gdmz.common.jqgrid.set_grid_height(
                me.grid_grdGroupList_id,
                me.ratio === 1.5 ? 212 : 260
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_pnlCsvOut_id,
                me.ratio === 1.5 ? 230 : 270
            );

            //No追加タイトル
            $("#HDKReOut4OBC_pnlCsvOut_rn").html("№");
            //出力対象のスタイルの設定
            $("#jqgh_HDKReOut4OBC_pnlCsvOut_cb").html("出力対象");
            $("#HDKReOut4OBC_pnlCsvOut_cb").css("width", "36px");
            $("#HDKReOut4OBC_pnlCsvOut tbody tr td").eq(1).css("width", "36px");

            //Gridのチェックボックス変更時の同期処理
            $(me.grid_pnlCsvOut_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, status, e) {
                    if (e != undefined) {
                        me.SubResetCSVStatus(rowid, status);
                    }
                },
            });
        }

        //部署 ,担当者
        var url = me.sys_id + "/" + me.id + "/" + "fncFormload";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //部署コード
            me.allBusyo = result["data"]["GetBusyoMstValue"];
            //担当者コード
            me.allSyain = result["data"]["GetSyainMstValue"];

            var dateFrom = new Date(result["data"]["strStartDate"]);
            dateFrom.setDate(dateFrom.getDate() - 7);
            $(".HDKReOut4OBC.CSVStart").val(
                new Date(dateFrom).Format("yyyy/MM/dd")
            );
            me.cboYMStart = new Date(dateFrom).Format("yyyy/MM/dd");
        };
        me.ajax.send(url, data, 0);

        $(".HDKReOut4OBC.txtGroupName").trigger("focus");
        $(".HDKReOut4OBC.grdGroupListTableRow").hide();
        $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        $(me.grid_grdGroupList_id).jqGrid("setGridParam", {
            //ページをめくる事件
            onPaging: function () {
                me.flg = "";
                $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
            },
        });
        $(me.grid_grdGroupList_id).jqGrid("bindKeys");
    };
    //**********************************************************************
    //処 理 名：検索ボタンクリックのイベント
    //関 数 名：Kensaku_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.Kensaku_click = function () {
        var txtGroupName = $.trim($(".HDKReOut4OBC.txtGroupName").val());
        var ltxtBusyoCD = $.trim($(".HDKReOut4OBC.ltxtBusyoCD").val());
        var CSVStart = $.trim(
            $(".HDKReOut4OBC.CSVStart").val().replace(/\//g, "")
        );
        var CSVEnd = $.trim($(".HDKReOut4OBC.CSVEnd").val().replace(/\//g, ""));
        var ltxtTantouCD = $.trim($(".HDKReOut4OBC.ltxtTantouCD").val());
        me.data = {
            txtGroupName: txtGroupName,
            ltxtBusyoCD: ltxtBusyoCD,
            CSVStart: CSVStart,
            CSVEnd: CSVEnd,
            ltxtTantouCD: ltxtTantouCD,
        };
        me.flg = "";
        me.fncJqgridReload();
    };
    //**********************************************************************
    //処 理 名：検索/CSV出力後、jqgrid reload
    //関 数 名 fncJqgridReload
    //引    数：なし
    //戻 り 値：なし
    //処理説明：検索/CSV出力後、jqgrid reload
    //**********************************************************************
    me.fncJqgridReload = function () {
        //選択した行のidを取得
        var selId = 0;
        if (me.flg == "csvout") {
            selId = $(me.grid_grdGroupList_id).jqGrid("getGridParam", "selrow");
        }
        $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
                $(".HDKReOut4OBC.grdGroupListTableRow").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
                $(".HDKReOut4OBC.grdGroupListTableRow").hide();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                $(me.grid_grdGroupList_id).jqGrid("setSelection", selId, true);
                if (me.flg == "csvout") {
                    $(me.grid_pnlCsvOut_id).trigger("reloadGrid");
                    $(".HDKReOut4OBC.PnlCsvOutTableRow").show();
                    $(".HDKReOut4OBC.Kensaku").trigger("focus");
                }
            }

            $(".HDKReOut4OBC.grdGroupListTableRow").show();
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_grdGroupList_id,
            me.data,
            complete_fun
        );
    };
    //**********************************************************************
    //処 理 名：グループ一覧の選択ボタン押下時
    //関 数 名：grdGroupObcList_RowCommand
    //引    数：無し
    //戻 り 値：なし
    //処理説明：グループ一覧の選択ボタンが押下された行のグループの仕訳を一覧に表示する
    //**********************************************************************
    grdGroupObcList_RowCommand = function (lblGroupNo, keiriDt, flg) {
        me.lblGroupNo = lblGroupNo;
        me.keiriDt = keiriDt;
        if (flg == 2) {
            //経理処理日
            $(".HDKReOut4OBC.txtInputKeiriDt").val(me.keiriDt);
        }
        var data = {
            strGroup_no: me.lblGroupNo,
            flg: flg,
        };

        //右側のテーブルのデータを検索します
        //仕訳データの取得
        var completeFnc = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (flg == 2) {
                //グループ名
                if (result["txtGroupName"].length > 0) {
                    $(".HDKReOut4OBC.txtInputGroupNM").val(
                        result["txtGroupName"]["0"]["CSV_GROUP_NM"]
                    );
                } else {
                    $(".HDKReOut4OBC.txtInputGroupNM").val("");
                }
            }
            //取得したデータをグリッドビューにセットする
            me.subGridDataSet();

            //jqgridデータはすべて選択状態です
            me.btnAllSelect_Click();
        };

        gdmz.common.jqgrid.reloadMessage(
            me.grid_pnlCsvOut_id,
            data,
            completeFnc
        );
    };

    me.btnAllSelect_Click = function () {
        var ids = $(me.grid_pnlCsvOut_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            $(me.grid_pnlCsvOut_id).jqGrid("setSelection", ids[i], true);
        }
    };

    //**********************************************************************
    //処 理 名： 抽出結果をグリッドにバインドする
    //関 数 名：subGridDataSet
    //引    数：無し
    //戻 り 値：なし
    //処理説明： 抽出結果をグリッドにバインドする
    //**********************************************************************
    me.subGridDataSet = function () {
        //背景色
        var sum = 0;
        var ids = $(me.grid_pnlCsvOut_id).jqGrid("getDataIDs");
        var rowData = $(me.grid_pnlCsvOut_id).jqGrid("getRowData");
        var intKensu = 0;
        for (var i = 0; i < rowData.length; i++) {
            if (rowData[i]["CHK_CSV_STATUS"] == "1") {
                if (
                    rowData[i]["KINGAKU"] != "" &&
                    rowData[i]["KINGAKU"] != null
                ) {
                    sum += parseFloat(rowData[i]["KINGAKU"]);
                }
                intKensu = parseInt(intKensu) + 1;
            }
            if (rowData[i]["UPD_FLG"] == "1") {
                $("#HDKReOut4OBC_pnlCsvOut tr.jqgrow#" + ids[i] + " td").css(
                    "background",
                    "#FF8C00"
                );
            }
        }
        //書式
        intKensu = me.parseFormatNum(intKensu);
        sum = me.parseFormatNum(sum);
        $(".HDKReOut4OBC.lvTxtKingakuSum").val(sum);
        $(".HDKReOut4OBC.lvTxtCount").val(intKensu);
        $(".HDKReOut4OBC.PnlCsvOutTableRow").show();
    };

    //**********************************************************************
    //処 理 名：Gridのチェックボックス変更時の同期処理
    //関 数 名：SubResetCSVStatus
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    me.SubResetCSVStatus = function (element, status) {
        var decCount = parseInt(
            $(".HDKReOut4OBC.lvTxtCount").val().replace(/,/g, "")
        );
        var decKingaku = parseInt(
            $(".HDKReOut4OBC.lvTxtKingakuSum").val().replace(/,/g, "")
        );
        var rowdata = "";

        rowdata = $(me.grid_pnlCsvOut_id).jqGrid("getRowData", element);
        if (status == true) {
            decCount = parseInt(decCount) + 1;
            if (rowdata["KINGAKU"] != "" && rowdata["KINGAKU"] != null) {
                decKingaku += parseInt(rowdata["KINGAKU"]);
            }
        } else {
            decCount = parseInt(decCount) - 1;
            if (rowdata["KINGAKU"] != "" && rowdata["KINGAKU"] != null) {
                decKingaku -= parseInt(rowdata["KINGAKU"]);
            }
        }

        // 出力対象件数
        $(".HDKReOut4OBC.lvTxtCount").val(me.parseFormatNum(decCount));
        // 出力対象金額合計
        $(".HDKReOut4OBC.lvTxtKingakuSum").val(me.parseFormatNum(decKingaku));
    };
    //**********************************************************************
    //処 理 名：CSV出力ボタンクリック
    //関 数 名：btnCsvOut_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明： CSV出力ボタンクの処理
    //**********************************************************************
    me.btnCsvOut_Click = function () {
        //出力対象のチェック
        if (me.FncChkInput_CSVOUT() == false) {
            return;
        }
        //CSV出力 出力グループの登録 仕訳データの更新
        var url = me.sys_id + "/" + me.id + "/" + "btnCsvOut_Click";

        var ids = $(me.grid_pnlCsvOut_id).jqGrid("getDataIDs");
        var rowData = $(me.grid_pnlCsvOut_id).jqGrid("getRowData");
        for (var i = 0; i < ids.length; i++) {
            var curChk = $("#HDKReOut4OBC_pnlCsvOut " + "#" + ids[i]).find(
                ":checkbox"
            );
            rowData[i]["CHK_CSV_FLG"] = curChk[0].checked ? "1" : "0";
        }

        var data = {
            txtInputGroupNM: $(".HDKReOut4OBC.txtInputGroupNM").val(),
            txtInputKeiriDt: $(".HDKReOut4OBC.txtInputKeiriDt").val(),
            strGroup_no: me.lblGroupNo,
            data: rowData,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"]) {
                //jqgrid
                me.flg = "csvout";
                me.fncJqgridReload();
                //file
                var link = document.createElement("a");
                link.style.display = "none";
                link.href = result["data"];
                link.setAttribute("download", "");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                if (result["error"] == "repeatErr") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に同一のグループ名が登録されています！"
                    );
                } else if (result["error"] == "W9999" && result["msg"]) {
                    me.clsComFnc.FncMsgBox(result["error"], result["msg"]);
                    if (
                        result["data"]["chgColor"] &&
                        result["data"]["chgColor"] == "1"
                    ) {
                        $(
                            "#HDKReOut4OBC_pnlCsvOut tr.jqgrow#" +
                                result["data"]["rowNum"] +
                                " td"
                        ).css("background", "#A06633");
                    }
                } else if (result["error"] == "W0034") {
                    $(".HDKReOut4OBC." + result["html"]).trigger("focus");
                    me.clsComFnc.FncMsgBox("W0034", result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：出力対象のチェック
    //関 数 名：FncChkInput_CSVOUT
    //引    数：無し
    //戻 り 値：なし
    //処理説明： CSV出力ボタンクの処理
    //**********************************************************************
    me.FncChkInput_CSVOUT = function () {
        var objRegEx_NG = /[\'\""]/;

        if ($(".HDKReOut4OBC.txtInputGroupNM").val().length == 0) {
            $(".HDKReOut4OBC.txtInputGroupNM").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "出力グループ名が未入力です！");
            return false;
        } else {
            // '/** 禁則 **/
            if (
                objRegEx_NG.test(
                    $(".HDKReOut4OBC.txtInputGroupNM").val().trimEnd()
                )
            ) {
                $(".HDKReOut4OBC.txtInputGroupNM").trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "出力グループ名に不正な文字が入力されています！"
                );
                return false;
            }

            //出力桁数のチェック
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKReOut4OBC.txtInputGroupNM")
                        .val()
                        .trimEnd()
                        .replace(/,/g, "")
                ) > 40
            ) {
                $(".HDKReOut4OBC.txtInputGroupNM").trigger("focus");
                //vb:E0013
                me.clsComFnc.FncMsgBox("E0027", "出力グループ名", 40);
                return false;
            }
        }

        if ($(".HDKReOut4OBC.lvTxtCount").val() == 0) {
            $(".HDKReOut4OBC.lvTxtCount").trigger("focus");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ＯＢＣ出力の対象が選択されていません！"
            );
            return false;
        }

        return true;
    };

    //**********************************************************************
    //処 理 名：担当者コードがフォーカスを失う
    //関 数 名：ltxtTantouCD_CheckedChanged
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.ltxtTantouCD_CheckedChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allSyain) {
            var foundNM_array = me.allSyain.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }

            $(".HDKReOut4OBC.Kensaku").trigger("focus");
        }
        $(".HDKReOut4OBC.TantouNM").val(foundNM ? foundNM["SYAIN_NM"] : "");
        $(".HDKReOut4OBC.grdGroupListTableRow").hide();
        $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
    };

    //**********************************************************************
    //処 理 名：部署コードがフォーカスを失う
    //関 数 名：ltxtBusyoCD_CheckedChanged
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.ltxtBusyoCD_CheckedChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allBusyo) {
            var foundNM_array = me.allBusyo.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
            $(".HDKReOut4OBC.CSVStart").trigger("focus");
        }
        $(".HDKReOut4OBC.BusyoNM").val(foundNM ? foundNM["BUSYO_NM"] : "");
        $(".HDKReOut4OBC.grdGroupListTableRow").hide();
        $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
    };

    //**********************************************************************
    //処 理 名：検索ﾎﾞﾀﾝクリック
    //関 数 名：openSearchDialog
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divNM = "";
        var frmId = "";
        var title = "";
        var $txtSearchCD = undefined;
        var $txtSearchNM = undefined;
        var cd = "RtnCD";

        switch (searchButton) {
            case "btnBusyo":
                //部署検索
                dialogId = "HDKCreatBusyoSearchDialogDiv";
                $txtSearchCD = $(".HDKReOut4OBC.ltxtBusyoCD");
                $txtSearchNM = $(".HDKReOut4OBC.BusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HDKCreatBusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnTantou":
                //社員
                dialogId = "HDKSyainSearchDialogDiv";
                $txtSearchCD = $(".HDKReOut4OBC.ltxtTantouCD");
                $txtSearchNM = $(".HDKReOut4OBC.TantouNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HDKSyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HDKReOut4OBC.HDKAIKEI-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        if (searchButton == "btnTantou") {
            $("<div></div>").attr("id", "syain").insertAfter($rootDiv).hide();
            var $syainSearch = $rootDiv.parent().find("#" + "syain");
            $syainSearch.val("syain");
        }
        $(".HDKReOut4OBC.txtGroupName").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 530 : 630,
            width:
                searchButton == "btnBusyo" ? 500 : me.ratio === 1.5 ? 696 : 720,
            resizable: false,
            close: function () {
                var $RtnCD = $rootDiv.parent().find("#" + cd);
                var $SearchCD = $rootDiv.parent().find("#" + divCD);
                var $SearchNM = $rootDiv.parent().find("#" + divNM);
                if ($RtnCD.html() == 1) {
                    $txtSearchCD.val($SearchCD.html());
                    $txtSearchNM.val($SearchNM.html());
                    $(".HDKReOut4OBC.grdGroupListTableRow").hide();
                    $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
                }
                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();

                if (searchButton == "btnTantou") {
                    $syainSearch.remove();
                }

                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HDKReOut4OBC." + searchButton).trigger("focus");
                }, 100);
            },
        });

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#" + dialogId).html(result);
            $("#" + dialogId).dialog("option", "title", title);
            $("#" + dialogId).dialog("open");
        };
    };

    me.parseFormatNum = function (number) {
        number += "";
        number = number.replace(/\b(0+)/gi, "");
        number = number.replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,");
        if (number == "" || number == "-") {
            number = 0;
        }
        return number;
    };

    openEditShiwakeOBC = function (id, denpykbn, selno) {
        var frmId = "HDKShiharaiInput";
        var dialogdiv = "HDKShiharaiInputDialogDiv";
        if (denpykbn == "1") {
            var frmId = "HDKShiwakeInput";
            var dialogdiv = "HDKShiwakeInputDialogDiv";
        }
        var $rootDiv = $(".HDKReOut4OBC.HDKAIKEI-content");

        $("<div style='display:none;'></div>")
            .attr("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "MODE")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "DISP_NO")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "SYOHY_NO")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "RENBAN")
            .insertAfter($rootDiv);

        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        var $SYOHY_NO = $rootDiv.parent().find("#SYOHY_NO");
        var $RENBAN = $rootDiv.parent().find("#RENBAN");
        $MODE.html("2");
        $DISP_NO.html("ReOut4OBC");
        $SYOHY_NO.html(id);
        $RENBAN.html(selno);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                $MODE.remove();
                $DISP_NO.remove();
                $SYOHY_NO.remove();
                $RENBAN.remove();
                $("#" + dialogdiv).remove();
                grdGroupObcList_RowCommand(me.lblGroupNo, me.keiriDt, "1");
            }
            $("#" + dialogdiv).append(result);
            if (frmId == "HDKShiwakeInput") {
                o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC.HDKShiwakeInput.before_close =
                    before_close;
            } else {
                o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC.HDKShiharaiInput.before_close =
                    before_close;
            }
        };
    };

    me.txtGroupName_CheckedChanged = function () {
        $(".HDKReOut4OBC.grdGroupListTableRow").hide();
        $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        $(".HDKReOut4OBC.ltxtBusyoCD").trigger("focus");
    };

    me.dateChanged = function (textDate, CSVdate) {
        if (me.clsComFnc.CheckDate($(textDate)) == false) {
            if (CSVdate == "CSVStart") {
                $(textDate).val(me.cboYMStart);
            } else if (CSVdate == "CSVEnd") {
                $(textDate).val("");
            } else if (CSVdate == "txtInputKeiriDt") {
                $(textDate).val(me.keiriDt);
            }

            $(textDate).trigger("focus");
            $(textDate).select();
            //Firefox
            window.setTimeout(function () {
                $(textDate).trigger("focus");
                $(textDate).select();
            }, 0);
        }

        if (CSVdate != "txtInputKeiriDt") {
            $(".HDKReOut4OBC.grdGroupListTableRow").hide();
            $(".HDKReOut4OBC.PnlCsvOutTableRow").hide();
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HDKReOut4OBC_HDKReOut4OBC = new HDKAIKEI.HDKReOut4OBC();
    o_HDKReOut4OBC_HDKReOut4OBC.load();
    o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC = o_HDKReOut4OBC_HDKReOut4OBC;
});
