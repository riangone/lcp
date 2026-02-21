/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20240322       本番障害.xlsx NO8   		科目名、補助科目名は両方表示してほしい   LHB
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKDenpyoSearch");
HDKAIKEI.HDKDenpyoSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.id = "HDKDenpyoSearch";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearch_Click";
    me.grid_id = "#HDKDenpyoSearch_grdList";
    me.pager = "#HDKDenpyoSearch_pager";
    me.sidx = "";
    me.PatternID = gdmz.SessionPatternID;
    me.cboYM = "";
    me.cboYM_From = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HDKDenpyoSearch.HDKDenpyoSearchButton",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKDenpyoSearch.Datepicker",
        type: "datepicker",
        handle: "",
    });
    me.option = {
        rowNum: 10,
        caption: "",
        rownumbers: false,
        rowList: [10, 20, 30],
        multiselect: true,
        multiselectWidth: 30,
        autoScroll: true,
        //shrinkToFit : false,
        colModel: me.colModel,
        pager: me.pager, //分页容器
        //pagerpos : "center",
        recordpos: "right",
        datatype: "json",
    };
    me.colModel = [
        {
            name: "SYOHY_NO",
            label: "証憑№",
            index: "SYOHY_NO",
            width: 140,
            align: "left",
            sortable: false,
        },
        {
            name: "FILE",
            label: "添付",
            index: "FILE",
            width: 35,
            align: "center",
            sortable: false,
        },
        {
            name: "L_KAMOKU",
            label: "借方科目",
            index: "L_KAMOKU",
            width: 170,
            align: "left",
            sortable: false,
            // 20240322 LHB INS S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["L_KOUMKU"] !== null &&
                    rowObject["L_KOUMKU"] !== "" &&
                    rowObject["L_KAMOKU"] !== null &&
                    rowObject["L_KAMOKU"] !== ""
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
            // 20240322 LHB INS E
        },
        {
            name: "R_KAMOKU",
            label: "貸方科目",
            index: "R_KAMOKU",
            width: 170,
            align: "left",
            sortable: false,
            // 20240322 LHB INS S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["R_KOUMKU"] !== null &&
                    rowObject["R_KOUMKU"] !== "" &&
                    rowObject["R_KAMOKU"] !== null &&
                    rowObject["R_KAMOKU"] !== ""
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
                        rowObject["R_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["R_KAMOKU"];
                    }
                    if (
                        rowObject["R_KOUMKU"] !== null &&
                        rowObject["R_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["R_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            // 20240322 LHB INS E
        },
        {
            name: "SHIHARAI_DT",
            label: "支払予定日",
            index: "SHIHARAI_DT",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "TORIHIKISAKI_NAME",
            label: "取引先",
            index: "TORIHIKISAKI_NAME",
            width: 130,
            align: "left",
            sortable: false,
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            width: 80,
            align: "left",
            sortable: false,
        },
        {
            name: "CRE_BUSYO_CD",
            label: "作成<br />部署",
            index: "CRE_BUSYO_CD",
            width: 110,
            align: "left",
            sortable: false,
        },
        {
            name: "CRE_SYA_NM",
            label: "作成者",
            index: "CRE_SYA_NM",
            width: 110,
            align: "left",
            sortable: false,
        },
        {
            name: "KENSU",
            label: "件数",
            index: "KENSU",
            width: 65,
            align: "right",
            sortable: false,
        },
        {
            name: "KINGAKU",
            label: "合計金額",
            index: "KINGAKU",
            width: 122,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "PRINT_OUT_FLG",
            label: "印刷<br />状況",
            index: "PRINT_OUT_FLG",
            width: 50,
            align: "center",
            sortable: false,
            formatter: "checkbox",
        },
        {
            name: "CSV_OUT_FLG",
            label: "全銀協<br />出力状況",
            index: "CSV_OUT_FLG",
            width: 70,
            align: "center",
            sortable: false,
            formatter: "checkbox",
        },
        {
            name: "XLSX_OUT_FLG",
            label: "OBC出<br />力状況",
            index: "XLSX_OUT_FLG",
            width: 60,
            align: "center",
            sortable: false,
            formatter: "checkbox",
        },
        {
            name: "btnEdit",
            label: " ",
            index: "btnEdit",
            width: 73,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openEditShiwakeHDK('" +
                    rowObject["SYOHY_NO"] +
                    "','" +
                    rowObject["SYOHY_NO"].substring(0, 1) +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HDKDenpyoSearch btnEdit\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
                    (rowObject["MAX_SYORI_FLG"] == "1" ? "参照" : "編集") +
                    "</button>";
                return detail;
            },
        },
        {
            name: "MAX_SYORI_FLG",
            label: "出力履歴又は経理課更新履歴有り",
            index: "MAX_SYORI_FLG",
            width: 82,
            align: "center",
            sortable: false,
            hidden: true,
        },
    ];

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
    //検索
    $(".HDKDenpyoSearch.btnSearch").click(function () {
        me.cmdSearch_Click();
    });
    //借方科目コード検索ボタン
    $(".HDKDenpyoSearch.btnLKSearch").click(function () {
        me.openSearchDialog("btnLKSearch");
    });
    //貸方科目コード検索ボタン
    $(".HDKDenpyoSearch.btnRKSearch").click(function () {
        me.openSearchDialog("btnRKSearch");
    });
    //作成部署検索ボタン
    $(".HDKDenpyoSearch.btnBusyoSearch").click(function () {
        me.openSearchDialog("btnBusyoSearch");
    });
    //作成者検索ボタン
    $(".HDKDenpyoSearch.btnSyainSearch").click(function () {
        me.openSearchDialog("btnSyainSearch");
    });
    //全て選択ボタン
    $(".HDKDenpyoSearch.btnAllSelect").click(function () {
        me.btnAllSelect_Click();
    });
    //選択解除ボタン
    $(".HDKDenpyoSearch.btnAllKaijyo").click(function () {
        me.btnAllKaijyo_Click();
    });
    //新規作成ボタン
    $(".HDKDenpyoSearch.btnNew").click(function () {
        me.openAddShiwake();
    });
    //伝票印刷ボタン
    $(".HDKDenpyoSearch.btnDenpyPrint").click(function () {
        me.btnDenpyPrint_Click();
    });
    //未チェック一覧印刷ボタン
    $(".HDKDenpyoSearch.btnMicheckPrint").click(function () {
        me.btnMicheckPrint_Click();
    });
    //借方科目コード/貸方科目コード/作成部署/作成者change
    $(
        ".HDKDenpyoSearch.txtLKamokuCD,.HDKDenpyoSearch.txtRKamokuCD,.HDKDenpyoSearch.txtBusyoCD,.HDKDenpyoSearch.txtSyainNO"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    //伝票種類change
    $(
        "input[name='HDKDENPYOKIND'],input[name='HDKPRINTKIND'],input[name='HDKCSVKIND'],input[name='HDKXLSXKIND']"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HDKDenpyoSearch.txtSyohyNO").change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HDKDenpyoSearch.txtKeyWord").change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HDKDenpyoSearch.txtDateFrom").on("blur", function () {
        $(".HDKDenpyoSearch.btnSearch").button("enable");
        $(".HDKDenpyoSearch.btnMicheckPrint").button("enable");
        if ($(this).val() !== "") {
            me.dateChanged(this, me.cboYM_From);
        }
    });
    $(".HDKDenpyoSearch.txtDateTo").on("blur", function () {
        $(".HDKDenpyoSearch.btnSearch").button("enable");
        $(".HDKDenpyoSearch.btnMicheckPrint").button("enable");
        if ($(this).val() !== "") {
            me.dateChanged(this, me.cboYM);
        }
    });
    $(".HDKDenpyoSearch.txtShiharaiDTFrom").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "");
        }
    });
    $(".HDKDenpyoSearch.txtShiharaiDTEnd").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "");
        }
    });
    $(
        ".HDKDenpyoSearch.txtDateFrom,.HDKDenpyoSearch.txtDateTo,.HDKDenpyoSearch.txtShiharaiDTFrom,.HDKDenpyoSearch.txtShiharaiDTEnd"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    //借方科目コード変更してフォーカスを失う
    $(".HDKDenpyoSearch.txtLKamokuCD").on("change", function () {
        me.txtLKamokuCD_TextChanged($(this).val(), "K");
    });
    //貸方科目コード変更してフォーカスを失う
    $(".HDKDenpyoSearch.txtRKamokuCD").on("change", function () {
        me.txtLKamokuCD_TextChanged($(this).val(), "R");
    });
    //作成部署変更してフォーカスを失う
    $(".HDKDenpyoSearch.txtBusyoCD").on("change", function () {
        me.txtBusyoCD_TextChanged($(this).val());
    });
    //作成者変更してフォーカスを失う
    $(".HDKDenpyoSearch.txtSyainNO").on("change", function () {
        me.txtSyainNO_TextChanged($(this).val());
    });
    var ele = document.querySelector(".HDKDenpyoSearch.HDKAIKEI-content");
    var resizeObserver = new ResizeObserver(function () {
        $(me.grid_id).setGridWidth(
            $(".HDKDenpyoSearch.HDKAIKEI-content").width() * 0.98
        );
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
        if (window.ActiveXObject || "ActiveXObject" in window) {
            if ($(window).height() <= 950) {
                // 画面内容较多，IE显示不全，追加纵向滚动条
                $(".HDKAIKEI.HDKAIKEI-layout-center").css(
                    "overflow-y",
                    "scroll"
                );
            }
        }
        me.HDKDenpyoSearch_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：HDKDenpyoSearch_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.HDKDenpyoSearch_Load = function () {
        //初期処理
        //画面項目クリア
        // me.subClearForm();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        // me.initSpread();

        $(".HDKDenpyoSearch.pnlList").hide();
        me.subSearchButtonEnable(false);
        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 147 : 280
        );
        var fieldsetWidth = $(".HDKDenpyoSearch fieldset").width();
        gdmz.common.jqgrid.set_grid_width(me.grid_id, fieldsetWidth);
        //行をダブルクリックして編集画面を開きます。
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowId) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
                openEditShiwakeHDK(
                    rowData["SYOHY_NO"],
                    rowData["SYOHY_NO"].substring(0, 1)
                );
            },
        });
        $("#HDKDenpyoSearch_grdList_cb").html("印刷");
        //部署 ,担当者
        me.url = me.sys_id + "/" + me.id + "/" + "fncpageload";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "表示できる部署が存在しません。管理者にお問い合わせください。"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                $(".HDKDenpyoSearch.btnMicheckPrint").button("disable");
                $(".HDKDenpyoSearch.btnSearch").button("disable");
            }
            //部署コード
            me.allBusyo = result["data"]["GetBusyoMstValue"];
            //担当者コード
            me.allSyain = result["data"]["GetSyainMstValue"];
            //科目コード
            me.allKamoku = result["data"]["GetKamokuMstValue"];
            var txtDateTo = new Date(result["data"]["sysdate"]).Format(
                "yyyy/MM/dd"
            );
            var txtDateFrom = txtDateTo.substring(0, 8) + "01";
            $(".HDKDenpyoSearch.txtDateFrom").val(txtDateFrom);
            $(".HDKDenpyoSearch.txtDateTo").val(txtDateTo);
            me.cboYM = txtDateTo;
            me.cboYM_From = txtDateFrom;
            if (
                me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
            ) {
                $(".HDKDenpyoSearch.btnMicheckPrint").show();
                $(".HDKDenpyoSearch.lblFukanzen").show();
                $(".HDKDenpyoSearch.xlsxDiv").show();
                $(".HDKDenpyoSearch.lblCsvName").html("全銀協出力状態");
                $(".HDKDenpyoSearch.txtBusyoCD").val("");
                $(".HDKDenpyoSearch.txtBusyoCD").attr("enabled", true);
                $(".HDKDenpyoSearch.btnBusyoSearch").button("enable");
                $(".HDKDenpyoSearch.lblBusyoNM").val("");
            } else {
                $(".HDKDenpyoSearch.btnMicheckPrint").hide();
                $(".HDKDenpyoSearch.lblFukanzen").hide();
                $(".HDKDenpyoSearch.xlsxDiv").hide();
                $(".HDKDenpyoSearch.lblCsvName").html("経理課処理済");
                $(".HDKDenpyoSearch.txtBusyoCD").val(result["data"]["BusyoCD"]);
                me.txtBusyoCD_TextChanged(result["data"]["BusyoCD"]);
                $(".HDKDenpyoSearch.txtBusyoCD").attr("disabled", "disabled");
                $(".HDKDenpyoSearch.btnBusyoSearch").button("disable");
            }
            $(".HDKDenpyoSearch.radAll").trigger("focus");
        };
        me.ajax.send(me.url, data, 1);
    };
    me.cmdSearch_Click = function () {
        me.subSearchButtonEnable(false);
        if (
            me.PatternID !== me.HDKAIKEI.CONST_ADMIN_PTN_NO &&
            me.PatternID !== me.HDKAIKEI.CONST_HONBU_PTN_NO
        ) {
            $(me.grid_id).hideCol("FUKANZEN_FLG");
            $(me.grid_id).hideCol("XLSX_OUT_FLG");
            $(me.grid_id).setLabel("CSV_OUT_FLG", "経理課処理済");
        } else {
            $(me.grid_id).showCol("FUKANZEN_FLG");
            $(me.grid_id).showCol("XLSX_OUT_FLG");
            $(me.grid_id).setLabel("CSV_OUT_FLG", "全銀協<br />出力状況");
        }

        var txtDateFrom = $(".HDKDenpyoSearch.txtDateFrom").val();
        var txtDateTo = $(".HDKDenpyoSearch.txtDateTo").val();
        var txtShiharaiDTFrom = $(".HDKDenpyoSearch.txtShiharaiDTFrom").val();
        var txtShiharaiDTEnd = $(".HDKDenpyoSearch.txtShiharaiDTEnd").val();
        var txtLKamokuCD = $(".HDKDenpyoSearch.txtLKamokuCD").val();
        var txtRKamokuCD = $(".HDKDenpyoSearch.txtRKamokuCD").val();
        var txtBusyoCD = $(".HDKDenpyoSearch.txtBusyoCD").val();
        var txtSyainNO = $(".HDKDenpyoSearch.txtSyainNO").val();
        var radAll = $(".HDKDenpyoSearch.radAll").prop("checked");
        var radShiharai = $(".HDKDenpyoSearch.radShiharai").prop("checked");
        var radShiwake = $(".HDKDenpyoSearch.radShiwake").prop("checked");
        var radPrintNoSel = $(".HDKDenpyoSearch.radPrintNoSel").prop("checked");
        var radPrintMi = $(".HDKDenpyoSearch.radPrintMi").prop("checked");
        var radPrintSumi = $(".HDKDenpyoSearch.radPrintSumi").prop("checked");
        var radCsvNoSel = $(".HDKDenpyoSearch.radCsvNoSel").prop("checked");
        var radCsvMi = $(".HDKDenpyoSearch.radCsvMi").prop("checked");
        var radCsvSumi = $(".HDKDenpyoSearch.radCsvSumi").prop("checked");
        var radXlsxNoSel = $(".HDKDenpyoSearch.radXlsxNoSel").prop("checked");
        var radXlsxMi = $(".HDKDenpyoSearch.radXlsxMi").prop("checked");
        var radXlsxSumi = $(".HDKDenpyoSearch.radXlsxSumi").prop("checked");
        var txtKeyWord = $(".HDKDenpyoSearch.txtKeyWord").val();
        var txtSyohyNO = $(".HDKDenpyoSearch.txtSyohyNO").val();

        var data = {
            radAll: radAll,
            radShiharai: radShiharai,
            radShiwake: radShiwake,
            radPrintNoSel: radPrintNoSel,
            radPrintMi: radPrintMi,
            radPrintSumi: radPrintSumi,
            radCsvNoSel: radCsvNoSel,
            radCsvMi: radCsvMi,
            radCsvSumi: radCsvSumi,
            radXlsxNoSel: radXlsxNoSel,
            radXlsxMi: radXlsxMi,
            radXlsxSumi: radXlsxSumi,
            txtDateFrom: txtDateFrom,
            txtDateTo: txtDateTo,
            txtShiharaiDTFrom: txtShiharaiDTFrom,
            txtShiharaiDTEnd: txtShiharaiDTEnd,
            txtLKamokuCD: txtLKamokuCD,
            txtRKamokuCD: txtRKamokuCD,
            txtBusyoCD: txtBusyoCD,
            txtSyainNO: txtSyainNO,
            strPrgID: "DENPYO_SEARCH",
            strPrgNM: "DENPYO_SEARCH",
            txtKeyWord: txtKeyWord,
            txtSyohyNO: txtSyohyNO,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var objDR = $(me.grid_id).jqGrid("getRowData");
            if (objDR.length == 0) {
                //該当するデータは存在しません。
                me.clsComFnc.FncMsgBox("W0024");
                return;
            } else {
                var ids = $(me.grid_id).jqGrid("getDataIDs");
                for (var i = 0; i < ids.length; i++) {
                    var rowData = $(me.grid_id).jqGrid("getRowData", ids[i]);
                    if (rowData["PRINT_OUT_FLG"] == "No") {
                        $("#HDKDenpyoSearch_grdList " + "#" + ids[i])
                            .find("td")
                            .css("background-color", "#FF8C00");
                    }
                }
                me.subSearchButtonEnable(true);
                //１行目選択
                // フォーカスの設定
                $(me.grid_id).trigger("focus");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    me.btnDenpyPrint_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/btnDenpyPrint_Click";
        var arr = new Array();
        var jqGridRowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        if (jqGridRowIds.length <= 0) {
            me.clsComFnc.FncMsgBox("W9999", "印刷対象を指定して下さい！");
            return;
        }

        for (key in jqGridRowIds) {
            arr[key] = $(me.grid_id).jqGrid("getRowData", jqGridRowIds[key]);
        }
        var data = {
            arr: arr,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };

        me.ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["report"]) {
                    window.open(result["report"]);
                    me.cmdSearch_Click();
                }
            } else {
                if (result["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                } else if (result["error"].indexOf("W9999") > -1) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        result["error"].replace("W9999", "")
                    );
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    me.btnMicheckPrint_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/btnMicheckPrint_Click";
        var txtDateFrom = $(".HDKDenpyoSearch.txtDateFrom").val();
        var txtDateTo = $(".HDKDenpyoSearch.txtDateTo").val();
        var txtShiharaiDTFrom = $(".HDKDenpyoSearch.txtShiharaiDTFrom").val();
        var txtShiharaiDTEnd = $(".HDKDenpyoSearch.txtShiharaiDTEnd").val();
        var txtLKamokuCD = $(".HDKDenpyoSearch.txtLKamokuCD").val();
        var txtRKamokuCD = $(".HDKDenpyoSearch.txtRKamokuCD").val();
        var txtBusyoCD = $(".HDKDenpyoSearch.txtBusyoCD").val();
        var txtSyainNO = $(".HDKDenpyoSearch.txtSyainNO").val();
        var radAll = $(".HDKDenpyoSearch.radAll").prop("checked");
        var radShiharai = $(".HDKDenpyoSearch.radShiharai").prop("checked");
        var radShiwake = $(".HDKDenpyoSearch.radShiwake").prop("checked");
        var radPrintNoSel = $(".HDKDenpyoSearch.radPrintNoSel").prop("checked");
        var radPrintMi = $(".HDKDenpyoSearch.radPrintMi").prop("checked");
        var radPrintSumi = $(".HDKDenpyoSearch.radPrintSumi").prop("checked");
        var radCsvNoSel = $(".HDKDenpyoSearch.radCsvNoSel").prop("checked");
        var radCsvMi = $(".HDKDenpyoSearch.radCsvMi").prop("checked");
        var radCsvSumi = $(".HDKDenpyoSearch.radCsvSumi").prop("checked");
        var txtKeyWord = $(".HDKDenpyoSearch.txtKeyWord").val();
        var txtSyohyNO = $(".HDKDenpyoSearch.txtSyohyNO").val();

        me.data = {
            radAll: radAll,
            radShiharai: radShiharai,
            radShiwake: radShiwake,
            radPrintNoSel: radPrintNoSel,
            radPrintMi: radPrintMi,
            radPrintSumi: radPrintSumi,
            radCsvNoSel: radCsvNoSel,
            radCsvMi: radCsvMi,
            radCsvSumi: radCsvSumi,
            txtDateFrom: txtDateFrom,
            txtDateTo: txtDateTo,
            txtShiharaiDTFrom: txtShiharaiDTFrom,
            txtShiharaiDTEnd: txtShiharaiDTEnd,
            txtLKamokuCD: txtLKamokuCD,
            txtRKamokuCD: txtRKamokuCD,
            txtBusyoCD: txtBusyoCD,
            txtSyainNO: txtSyainNO,
            strPrgID: "DENPYO_SEARCH_CHECK",
            strPrgNM: "DENPYO_SEARCH_CHECK",
            txtKeyWord: txtKeyWord,
            txtSyohyNO: txtSyohyNO,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };

        me.ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["report"]) {
                    window.open(result["report"]);
                    me.subSearchButtonEnable(false);
                }
            } else {
                if (result["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    // '**********************************************************************
    // '処 理 名：社員名取得
    // '関 数 名：txtSyainNO_LostFocus
    // '処理説明：フォーカス移動時に社員名を取得する
    // '**********************************************************************
    me.txtSyainNO_TextChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allSyain) {
            var foundNM_array = me.allSyain.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKDenpyoSearch.lblSyainNM").val(
            foundNM ? foundNM["SYAIN_NM"] : ""
        );
    };
    // '**********************************************************************
    // '処 理 名：部署名取得
    // '関 数 名：txtBusyoCD_LostFocus
    // '処理説明：フォーカス移動時に部署名を取得する
    // '**********************************************************************
    me.txtBusyoCD_TextChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allBusyo) {
            var foundNM_array = me.allBusyo.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKDenpyoSearch.lblBusyoNM").val(
            foundNM ? foundNM["BUSYO_NM"] : ""
        );
    };
    // '**********************************************************************
    // '処 理 名：科目名取得
    // '関 数 名：txtLKamokuCD_LostFocus
    // '処理説明：フォーカス移動時に科目名を取得する
    // '**********************************************************************
    me.txtLKamokuCD_TextChanged = function (thisValue, Flag) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allKamoku) {
            var foundNM_array = me.allKamoku.filter(function (element) {
                return element["KAMOK_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        if (Flag == "K") {
            $(".HDKDenpyoSearch.lblLkamokuNM").val(
                foundNM ? foundNM["KAMOK_NAME"] : ""
            );
        } else {
            $(".HDKDenpyoSearch.lblRkamokuNM").val(
                foundNM ? foundNM["KAMOK_NAME"] : ""
            );
        }
    };
    me.openAddShiwake = function () {
        var HDKDENPYOKIND = $("input[name='HDKDENPYOKIND']:checked").val();
        if (HDKDENPYOKIND == "radShiwake") {
            var frmId = "HDKShiwakeInput";
            var dialogdiv = "HDKShiwakeInputDialogDiv";
            // var title = "仕訳伝票入力";
        } else {
            var frmId = "HDKShiharaiInput";
            var dialogdiv = "HDKShiharaiInputDialogDiv";
            // var title = "支払伝票入力";
        }
        var $rootDiv = $(".HDKDenpyoSearch.HDKAIKEI-content");

        $("<div style='display:none;'></div>")
            .attr("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "MODE")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "DISP_NO")
            .insertAfter($rootDiv);

        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        $MODE.html("1");
        $DISP_NO.html("100");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                //データバインドする
                if ($(".HDKDenpyoSearch.pnlList").css("display") == "block") {
                    me.cmdSearch_Click();
                }
                $MODE.remove();
                $DISP_NO.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            if (HDKDENPYOKIND == "radShiwake") {
                o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiwakeInput.before_close =
                    before_close;
            } else {
                o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiharaiInput.before_close =
                    before_close;
            }
        };
    };
    openEditShiwakeHDK = function (id, denpykbn) {
        if (denpykbn == "1") {
            var frmId = "HDKShiwakeInput";
            var dialogdiv = "HDKShiwakeInputDialogDiv";
            // var title = "仕訳伝票入力";
        } else {
            var frmId = "HDKShiharaiInput";
            var dialogdiv = "HDKShiharaiInputDialogDiv";
            // var title = "支払伝票入力";
        }
        var $rootDiv = $(".HDKDenpyoSearch.HDKAIKEI-content");

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

        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        var $SYOHY_NO = $rootDiv.parent().find("#SYOHY_NO");

        $MODE.html("2");
        $DISP_NO.html("100");
        $SYOHY_NO.html(id);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                //データバインドする
                me.cmdSearch_Click();
                $MODE.remove();
                $DISP_NO.remove();
                $SYOHY_NO.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            if (frmId == "HDKShiwakeInput") {
                o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiwakeInput.before_close =
                    before_close;
            } else {
                o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiharaiInput.before_close =
                    before_close;
            }
        };
    };
    me.dateChanged = function (textDate, cboYM) {
        me.subSearchButtonEnable(false);

        if (me.clsComFnc.CheckDate($(textDate)) == false) {
            $(textDate).val(cboYM);
            $(textDate).trigger("focus");
            $(textDate).select();
            // Firefox
            window.setTimeout(function () {
                $(textDate).trigger("focus");
                $(textDate).select();
            }, 0);
            if (cboYM != "") {
                $(".HDKDenpyoSearch.btnSearch").button("disable");
                $(".HDKDenpyoSearch.btnMicheckPrint").button("disable");
            }
        } else {
            $(".HDKDenpyoSearch.btnSearch").button("enable");
            $(".HDKDenpyoSearch.btnMicheckPrint").button("enable");
        }
    };
    me.subSearchButtonEnable = function (blnHantei) {
        if (blnHantei == true) {
            $(".HDKDenpyoSearch.pnlList").show();
            $(".HDKDenpyoSearch.pnlallbutton").show();
            $(".HDKDenpyoSearch.btnAllSelect").attr("disabled", false);
            $(".HDKDenpyoSearch.btnAllKaijyo").attr("disabled", false);
            $(".HDKDenpyoSearch.btnDenpyPrint").attr("disabled", false);
        } else {
            $(".HDKDenpyoSearch.pnlList").hide();
            $(".HDKDenpyoSearch.pnlallbutton").hide();
            $(".HDKDenpyoSearch.btnAllSelect").attr("disabled", "disabled");
            $(".HDKDenpyoSearch.btnAllKaijyo").attr("disabled", "disabled");
            $(".HDKDenpyoSearch.btnDenpyPrint").attr("disabled", "disabled");
        }
    };
    me.btnAllSelect_Click = function () {
        $(me.grid_id).jqGrid("resetSelection");
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        ids.forEach(function (element) {
            $(me.grid_id).jqGrid("setSelection", element, true);
        });
    };
    me.btnAllKaijyo_Click = function () {
        $(me.grid_id).jqGrid("resetSelection");
    };
    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divNM = "";
        var frmId = "";
        var title = "";
        var cd = "RtnCD";
        var $txtSearchCD = undefined;
        var $txtSearchNM = undefined;
        var koumkuCd = "koumkuCd";
        switch (searchButton) {
            case "btnLKSearch":
            case "btnRKSearch":
                //科目検索
                dialogId = "HDKKamokuSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRKSearch"
                        ? $(".HDKDenpyoSearch.txtRKamokuCD")
                        : $(".HDKDenpyoSearch.txtLKamokuCD");
                $txtSearchNM =
                    searchButton == "btnRKSearch"
                        ? $(".HDKDenpyoSearch.lblRkamokuNM")
                        : $(".HDKDenpyoSearch.lblLkamokuNM");
                divCD = "KamokuCD";
                divNM = "KamokuNM";
                frmId = "HDKKamokuSearch";
                title = "科目マスタ検索";
                break;
            case "btnBusyoSearch":
                dialogId = "HDKCreatBusyoSearchDialogDiv";
                $txtSearchCD = $(".HDKDenpyoSearch.txtBusyoCD");
                $txtSearchNM = $(".HDKDenpyoSearch.lblBusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HDKCreatBusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnSyainSearch":
                dialogId = "HDKSyainSearchDialogDiv";
                $txtSearchCD = $(".HDKDenpyoSearch.txtSyainNO");
                $txtSearchNM = $(".HDKDenpyoSearch.lblSyainNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HDKSyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HDKDenpyoSearch.HDKAIKEI-content");

        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }

        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", koumkuCd).insertAfter($rootDiv).hide();

        if (searchButton == "btnSyainSearch") {
            $("<div></div>").attr("id", "syain").insertAfter($rootDiv).hide();
            var $syainSearch = $rootDiv.parent().find("#" + "syain");
            $syainSearch.val("syain");
        }
        var $RtnCD = $rootDiv.parent().find("#" + cd);
        var $SearchCD = $rootDiv.parent().find("#" + divCD);
        var $SearchNM = $rootDiv.parent().find("#" + divNM);
        var $koumkuCd = $rootDiv.parent().find("#" + koumkuCd);

        $SearchCD.val($.trim($txtSearchCD.val()));
        $koumkuCd.val("10");
        $(".HDKDenpyoSearch.txtSyohyNO").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 530 : 630,
            width:
                searchButton == "btnBusyoSearch"
                    ? 500
                    : me.ratio === 1.5
                    ? 700
                    : 720,
            resizable: false,
            close: function () {
                if ($RtnCD.html() == 1) {
                    if ($SearchCD.html() != "") {
                        $txtSearchCD.val($SearchCD.html());
                    }
                    if ($SearchNM.html() != "") {
                        $txtSearchNM.val($SearchNM.html());
                    }
                    me.subSearchButtonEnable(false);
                }

                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();
                $koumkuCd.remove();
                if (searchButton == "btnSyainSearch") {
                    $syainSearch.remove();
                }
                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HDKDenpyoSearch." + searchButton).trigger("focus");
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

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HDKAIKEI_HDKDenpyoSearch = new HDKAIKEI.HDKDenpyoSearch();
    o_HDKAIKEI_HDKDenpyoSearch.load();
    o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch = o_HDKAIKEI_HDKDenpyoSearch;
});
