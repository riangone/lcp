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
 * 20240426            レイアウトについて  すべての項目・ボタン群が一度に表示される     yin
 * 20240430            一覧グリッドについて   件数のブルダウンを15、30、45に変更       yin
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS100DenpyoSearch");
HMDPS.HMDPS100DenpyoSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.HMDPS = new HMDPS.HMDPS();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.id = "HMDPS100DenpyoSearch";
    me.sys_id = "HMDPS";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearch_Click";
    me.grid_id = "#HMDPS100DenpyoSearch_grdList";
    me.pager = "#HMDPS100DenpyoSearch_pager";
    me.sidx = "";
    me.PatternID = gdmz.SessionPatternID;
    me.cboYM = "";
    me.cboYM_From = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMDPS100DenpyoSearch.HMDPS100DenpyoSearchButton",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS100DenpyoSearch.Datepicker",
        type: "datepicker",
        handle: "",
    });
    me.option = {
        // 20240430 YIN UPD S
        // rowNum: 10,
        rowNum: 15,
        // 20240430 YIN UPD E
        caption: "",
        rownumbers: false,
        // 20240430 YIN UPD S
        // rowList: [10, 20, 30],
        rowList: [15, 30, 45],
        // 20240430 YIN UPD E
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
            name: "L_KAMOKU",
            label: "借方科目",
            index: "L_KAMOKU",
            width: 170,
            align: "left",
            sortable: false,
        },
        {
            name: "R_KAMOKU",
            label: "貸方科目",
            index: "R_KAMOKU",
            width: 170,
            align: "left",
            sortable: false,
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
            name: "SHIHARAISAKI_NM",
            label: "支払先",
            index: "SHIHARAISAKI_NM",
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
            width: 50,
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
            name: "FUKANZEN_FLG",
            label: "不完全",
            index: "FUKANZEN_FLG",
            width: 57,
            align: "center",
            sortable: false,
            formatter: "checkbox",
        },
        {
            name: "CSV_OUT_FLG",
            label: "出力<br />状況",
            index: "CSV_OUT_FLG",
            width: 50,
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
                    "<button onclick=\"openEditShiwake('" +
                    rowObject["SYOHY_NO"] +
                    "','" +
                    rowObject["SYOHY_NO"].substring(0, 1) +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HMDPS100DenpyoSearch btnEdit\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
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
    me.HMDPS.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMDPS.TabKeyDown();

    //Enterキーのバインド
    me.HMDPS.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //検索
    $(".HMDPS100DenpyoSearch.btnSearch").click(function () {
        me.cmdSearch_Click();
    });
    //借方科目コード検索ボタン
    $(".HMDPS100DenpyoSearch.btnLKSearch").click(function () {
        me.openSearchDialog("btnLKSearch");
    });
    //貸方科目コード検索ボタン
    $(".HMDPS100DenpyoSearch.btnRKSearch").click(function () {
        me.openSearchDialog("btnRKSearch");
    });
    //作成部署検索ボタン
    $(".HMDPS100DenpyoSearch.btnBusyoSearch").click(function () {
        me.openSearchDialog("btnBusyoSearch");
    });
    //作成者検索ボタン
    $(".HMDPS100DenpyoSearch.btnSyainSearch").click(function () {
        me.openSearchDialog("btnSyainSearch");
    });
    //全て選択ボタン
    $(".HMDPS100DenpyoSearch.btnAllSelect").click(function () {
        me.btnAllSelect_Click();
    });
    //選択解除ボタン
    $(".HMDPS100DenpyoSearch.btnAllKaijyo").click(function () {
        me.btnAllKaijyo_Click();
    });
    //新規作成ボタン
    $(".HMDPS100DenpyoSearch.btnNew").click(function () {
        me.openAddShiwake();
    });
    //伝票印刷ボタン
    $(".HMDPS100DenpyoSearch.btnDenpyPrint").click(function () {
        me.btnDenpyPrint_Click();
    });
    //伝票印刷ボタン
    $(".HMDPS100DenpyoSearch.btnMicheckPrint").click(function () {
        me.btnMicheckPrint_Click();
    });
    //借方科目コード/貸方科目コード/作成部署/作成者change
    $(
        ".HMDPS100DenpyoSearch.txtLKamokuCD,.HMDPS100DenpyoSearch.txtRKamokuCD,.HMDPS100DenpyoSearch.txtBusyoCD,.HMDPS100DenpyoSearch.txtSyainNO"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    //伝票種類change
    $(
        "input[name='DENPYOKIND'],input[name='PRINTKIND'],input[name='CSVKIND'],.HMDPS100DenpyoSearch.chkFukanzen"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HMDPS100DenpyoSearch.txtSyohyNO").change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HMDPS100DenpyoSearch.txtKeyWord").change(function () {
        me.subSearchButtonEnable(false);
    });
    $(".HMDPS100DenpyoSearch.txtDateFrom").blur(function () {
        $(".HMDPS100DenpyoSearch.btnSearch").button("enable");
        $(".HMDPS100DenpyoSearch.btnMicheckPrint").button("enable");
        if ($(this).val() !== "") {
            me.dateChanged(this, me.cboYM_From);
        }
    });
    $(".HMDPS100DenpyoSearch.txtDateTo").blur(function () {
        $(".HMDPS100DenpyoSearch.btnSearch").button("enable");
        $(".HMDPS100DenpyoSearch.btnMicheckPrint").button("enable");
        if ($(this).val() !== "") {
            me.dateChanged(this, me.cboYM);
        }
    });
    $(".HMDPS100DenpyoSearch.txtShiharaiDTFrom").blur(function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "");
        }
    });
    $(".HMDPS100DenpyoSearch.txtShiharaiDTEnd").blur(function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "");
        }
    });
    $(
        ".HMDPS100DenpyoSearch.txtDateFrom,.HMDPS100DenpyoSearch.txtDateTo,.HMDPS100DenpyoSearch.txtShiharaiDTFrom,.HMDPS100DenpyoSearch.txtShiharaiDTEnd"
    ).change(function () {
        me.subSearchButtonEnable(false);
    });
    //借方科目コード変更してフォーカスを失う
    $(".HMDPS100DenpyoSearch.txtLKamokuCD").on("change", function () {
        me.txtLKamokuCD_TextChanged($(this).val(), "K");
    });
    //貸方科目コード変更してフォーカスを失う
    $(".HMDPS100DenpyoSearch.txtRKamokuCD").on("change", function () {
        me.txtLKamokuCD_TextChanged($(this).val(), "R");
    });
    //作成部署変更してフォーカスを失う
    $(".HMDPS100DenpyoSearch.txtBusyoCD").on("change", function () {
        me.txtBusyoCD_TextChanged($(this).val());
    });
    //作成者変更してフォーカスを失う
    $(".HMDPS100DenpyoSearch.txtSyainNO").on("change", function () {
        me.txtSyainNO_TextChanged($(this).val());
    });
    // 20240426 YIN INS S
    var ele = document.querySelector(".HMDPS.HMDPS-layout-center");
    var resizeObserver = new ResizeObserver(function () {
        $(me.grid_id).setGridWidth(
            $(".HMDPS100DenpyoSearch.HMDPS-content").width() * 0.98
        );
        $(me.grid_id).setGridHeight(
            $(".HMDPS.HMDPS-layout-center").height() -
                $(".HMDPS100DenpyoSearch.fieldset").height() -
                170
        );
    });
    resizeObserver.observe(ele);
    // 20240426 YIN INS E
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
                $(".HMDPS.HMDPS-layout-center").css("overflow-y", "scroll");
            }
        }
        me.HMDPS100DenpyoSearch_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：HMDPS100DenpyoSearch_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.HMDPS100DenpyoSearch_Load = function () {
        //初期処理
        //画面項目クリア
        // me.subClearForm();
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        // me.initSpread();

        $(".HMDPS100DenpyoSearch.pnlList").hide();
        me.subSearchButtonEnable(false);
        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        // 20240426 YIN INS S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 250);
        var userAgent = navigator.userAgent;
        if (userAgent.indexOf("Edg") > -1) {
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 270);
        } else {
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 250);
        }
        // 20240426 YIN INS E
        var fieldsetWidth = $(".HMDPS100DenpyoSearch fieldset").width();
        gdmz.common.jqgrid.set_grid_width(me.grid_id, fieldsetWidth);
        //行をダブルクリックして編集画面を開きます。
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowId, _iRow, _iCol, _e) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
                openEditShiwake(
                    rowData["SYOHY_NO"],
                    rowData["SYOHY_NO"].substring(0, 1)
                );
            },
        });
        $("#HMDPS100DenpyoSearch_grdList_cb").html("印刷");
        //部署 ,担当者
        me.url = me.sys_id + "/" + me.id + "/" + "fncPageLoad";
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
                $(".HMDPS100DenpyoSearch.btnMicheckPrint").button("disable");
                $(".HMDPS100DenpyoSearch.btnSearch").button("disable");
                return;
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
            $(".HMDPS100DenpyoSearch.txtDateFrom").val(txtDateFrom);
            $(".HMDPS100DenpyoSearch.txtDateTo").val(txtDateTo);
            me.cboYM = txtDateTo;
            me.cboYM_From = txtDateFrom;
            if (
                me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
            ) {
                $(".HMDPS100DenpyoSearch.btnMicheckPrint").show();
                $(".HMDPS100DenpyoSearch.lblFukanzen").show();
                $(".HMDPS100DenpyoSearch.chkFukanzen").show();
                $(".HMDPS100DenpyoSearch.lblCsvName").html("CSV出力状態");
                $(".HMDPS100DenpyoSearch.txtBusyoCD").val("");
                $(".HMDPS100DenpyoSearch.txtBusyoCD").prop("enabled", true);
                $(".HMDPS100DenpyoSearch.btnBusyoSearch").button("enable");
                $(".HMDPS100DenpyoSearch.lblBusyoNM").val("");
            } else {
                $(".HMDPS100DenpyoSearch.btnMicheckPrint").hide();
                $(".HMDPS100DenpyoSearch.lblFukanzen").hide();
                $(".HMDPS100DenpyoSearch.chkFukanzen").hide();
                $(".HMDPS100DenpyoSearch.lblCsvName").html("経理課処理済");
                $(".HMDPS100DenpyoSearch.txtBusyoCD").val(
                    result["data"]["BusyoCD"]
                );
                me.txtBusyoCD_TextChanged(result["data"]["BusyoCD"]);
                $(".HMDPS100DenpyoSearch.txtBusyoCD").prop(
                    "disabled",
                    "disabled"
                );
                $(".HMDPS100DenpyoSearch.btnBusyoSearch").button("disable");
            }
            $(".HMDPS100DenpyoSearch.radAll").trigger("focus");
        };
        me.ajax.send(me.url, data, 1);
    };
    me.cmdSearch_Click = function () {
        me.subSearchButtonEnable(false);
        // $(me.grid_id).jqGrid("clearGridData");
        if (
            me.PatternID !== me.HMDPS.CONST_ADMIN_PTN_NO &&
            me.PatternID !== me.HMDPS.CONST_HONBU_PTN_NO
        ) {
            $(me.grid_id).hideCol("FUKANZEN_FLG");
            $(me.grid_id).setLabel("CSV_OUT_FLG", "経理課処理済");
        } else {
            $(me.grid_id).showCol("FUKANZEN_FLG");
            $(me.grid_id).setLabel("CSV_OUT_FLG", "出力<br />状況");
        }

        var txtDateFrom = $(".HMDPS100DenpyoSearch.txtDateFrom").val();
        var txtDateTo = $(".HMDPS100DenpyoSearch.txtDateTo").val();
        var txtShiharaiDTFrom = $(
            ".HMDPS100DenpyoSearch.txtShiharaiDTFrom"
        ).val();
        var txtShiharaiDTEnd = $(
            ".HMDPS100DenpyoSearch.txtShiharaiDTEnd"
        ).val();
        var txtLKamokuCD = $(".HMDPS100DenpyoSearch.txtLKamokuCD").val();
        var txtRKamokuCD = $(".HMDPS100DenpyoSearch.txtRKamokuCD").val();
        var txtBusyoCD = $(".HMDPS100DenpyoSearch.txtBusyoCD").val();
        var txtSyainNO = $(".HMDPS100DenpyoSearch.txtSyainNO").val();
        var radAll = $(".HMDPS100DenpyoSearch.radAll").prop("checked");
        var radShiharai = $(".HMDPS100DenpyoSearch.radShiharai").prop(
            "checked"
        );
        var radShiwake = $(".HMDPS100DenpyoSearch.radShiwake").prop("checked");
        var radPrintNoSel = $(".HMDPS100DenpyoSearch.radPrintNoSel").prop(
            "checked"
        );
        var radPrintMi = $(".HMDPS100DenpyoSearch.radPrintMi").prop("checked");
        var radPrintSumi = $(".HMDPS100DenpyoSearch.radPrintSumi").prop(
            "checked"
        );
        var radCsvNoSel = $(".HMDPS100DenpyoSearch.radCsvNoSel").prop(
            "checked"
        );
        var radCsvMi = $(".HMDPS100DenpyoSearch.radCsvMi").prop("checked");
        var radCsvSumi = $(".HMDPS100DenpyoSearch.radCsvSumi").prop("checked");
        var chkFukanzen = $(".HMDPS100DenpyoSearch.chkFukanzen").prop(
            "checked"
        );
        var txtKeyWord = $(".HMDPS100DenpyoSearch.txtKeyWord").val();
        var txtSyohyNO = $(".HMDPS100DenpyoSearch.txtSyohyNO").val();

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
            chkFukanzen: chkFukanzen,
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
            CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
        };
        var complete_fun = function (returnFLG, result) {
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
                var dataNum = $(me.grid_id).jqGrid("getGridParam", "records");

                var ids = $(me.grid_id).jqGrid("getDataIDs");
                for (var i = 0; i < ids.length; i++) {
                    var rowData = $(me.grid_id).jqGrid("getRowData", ids[i]);
                    if (rowData["PRINT_OUT_FLG"] == "No") {
                        $("#HMDPS100DenpyoSearch_grdList " + "#" + ids[i])
                            .find("td")
                            .css("background-color", "#FF8C00");
                    }
                }
                // $(".HMDPS100DenpyoSearch.pnlList").show();
                me.subSearchButtonEnable(true);
                //１行目選択
                // $(me.grid_id).jqGrid('setSelection', 0, true);
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
            CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
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
        var txtDateFrom = $(".HMDPS100DenpyoSearch.txtDateFrom").val();
        var txtDateTo = $(".HMDPS100DenpyoSearch.txtDateTo").val();
        var txtShiharaiDTFrom = $(
            ".HMDPS100DenpyoSearch.txtShiharaiDTFrom"
        ).val();
        var txtShiharaiDTEnd = $(
            ".HMDPS100DenpyoSearch.txtShiharaiDTEnd"
        ).val();
        var txtLKamokuCD = $(".HMDPS100DenpyoSearch.txtLKamokuCD").val();
        var txtRKamokuCD = $(".HMDPS100DenpyoSearch.txtRKamokuCD").val();
        var txtBusyoCD = $(".HMDPS100DenpyoSearch.txtBusyoCD").val();
        var txtSyainNO = $(".HMDPS100DenpyoSearch.txtSyainNO").val();
        var radAll = $(".HMDPS100DenpyoSearch.radAll").prop("checked");
        var radShiharai = $(".HMDPS100DenpyoSearch.radShiharai").prop(
            "checked"
        );
        var radShiwake = $(".HMDPS100DenpyoSearch.radShiwake").prop("checked");
        var radPrintNoSel = $(".HMDPS100DenpyoSearch.radPrintNoSel").prop(
            "checked"
        );
        var radPrintMi = $(".HMDPS100DenpyoSearch.radPrintMi").prop("checked");
        var radPrintSumi = $(".HMDPS100DenpyoSearch.radPrintSumi").prop(
            "checked"
        );
        var radCsvNoSel = $(".HMDPS100DenpyoSearch.radCsvNoSel").prop(
            "checked"
        );
        var radCsvMi = $(".HMDPS100DenpyoSearch.radCsvMi").prop("checked");
        var radCsvSumi = $(".HMDPS100DenpyoSearch.radCsvSumi").prop("checked");
        var chkFukanzen = $(".HMDPS100DenpyoSearch.chkFukanzen").prop(
            "checked"
        );
        var txtKeyWord = $(".HMDPS100DenpyoSearch.txtKeyWord").val();
        var txtSyohyNO = $(".HMDPS100DenpyoSearch.txtSyohyNO").val();

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
            chkFukanzen: chkFukanzen,
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
            CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
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
        $(".HMDPS100DenpyoSearch.lblSyainNM").val(
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
        $(".HMDPS100DenpyoSearch.lblBusyoNM").val(
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
            $(".HMDPS100DenpyoSearch.lblLkamokuNM").val(
                foundNM ? foundNM["KAMOK_NM"] : ""
            );
        } else {
            $(".HMDPS100DenpyoSearch.lblRkamokuNM").val(
                foundNM ? foundNM["KAMOK_NM"] : ""
            );
        }
    };
    me.openAddShiwake = function () {
        var DENPYOKIND = $("input[name='DENPYOKIND']:checked").val();
        if (DENPYOKIND == "radShiwake") {
            var frmId = "HMDPS101ShiwakeDenpyoInput";
            var dialogdiv = "HMDPS101ShiwakeDenpyoInputDialogDiv";
            var title = "仕訳伝票入力";
        } else {
            var frmId = "HMDPS102ShiharaiDenpyoInput";
            var dialogdiv = "HMDPS102ShiharaiDenpyoInputDialogDiv";
            var title = "支払伝票入力";
        }
        var $rootDiv = $(".HMDPS100DenpyoSearch.HMDPS-content");
        //画面出现文字后再消失
        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        //($("<div style='display:none;'></div>").attr("id", "RtnCD")).insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "MODE")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "DISP_NO")
            .insertAfter($rootDiv);

        //var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        $MODE.html("1");
        $DISP_NO.html("100");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                //データバインドする
                if (
                    $(".HMDPS100DenpyoSearch.pnlList").css("display") == "block"
                ) {
                    me.cmdSearch_Click();
                }
                //$RtnCD.remove();
                $MODE.remove();
                $DISP_NO.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            if (DENPYOKIND == "radShiwake") {
                o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS101ShiwakeDenpyoInput.before_close =
                    before_close;
            } else {
                o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS102ShiharaiDenpyoInput.before_close =
                    before_close;
            }
        };
    };
    openEditShiwake = function (id, denpykbn) {
        if (denpykbn == "1") {
            var frmId = "HMDPS101ShiwakeDenpyoInput";
            var dialogdiv = "HMDPS101ShiwakeDenpyoInputDialogDiv";
            var title = "仕訳伝票入力";
        } else {
            var frmId = "HMDPS102ShiharaiDenpyoInput";
            var dialogdiv = "HMDPS102ShiharaiDenpyoInputDialogDiv";
            var title = "支払伝票入力";
        }
        var $rootDiv = $(".HMDPS100DenpyoSearch.HMDPS-content");

        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        // ($("<div style='display:none;'></div>").attr("id", "RtnCD")).insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "MODE")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "DISP_NO")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "SYOHY_NO")
            .insertAfter($rootDiv);

        // var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        var $SYOHY_NO = $rootDiv.parent().find("#SYOHY_NO");

        // $RtnCD.hide();
        // $MODE.hide();
        // $DISP_NO.hide();
        // $SYOHY_NO.hide();
        // $("#" + dialogdiv).hide();

        $MODE.html("2");
        $DISP_NO.html("100");
        $SYOHY_NO.html(id);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                //データバインドする
                me.cmdSearch_Click();
                // $RtnCD.remove();
                $MODE.remove();
                $DISP_NO.remove();
                $SYOHY_NO.remove();
                $("#" + dialogdiv).remove();
            }

            $("#" + dialogdiv).append(result);
            if (frmId == "HMDPS101ShiwakeDenpyoInput") {
                o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS101ShiwakeDenpyoInput.before_close =
                    before_close;
            } else {
                o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS102ShiharaiDenpyoInput.before_close =
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
                $(".HMDPS100DenpyoSearch.btnSearch").button("disable");
                $(".HMDPS100DenpyoSearch.btnMicheckPrint").button("disable");
            }
        } else {
            $(".HMDPS100DenpyoSearch.btnSearch").button("enable");
            $(".HMDPS100DenpyoSearch.btnMicheckPrint").button("enable");
        }
    };
    me.subSearchButtonEnable = function (blnHantei) {
        if (blnHantei == true) {
            $(".HMDPS100DenpyoSearch.pnlList").show();
            $(".HMDPS100DenpyoSearch.pnlallbutton").show();
            $(".HMDPS100DenpyoSearch.btnAllSelect").prop("disabled", false);
            $(".HMDPS100DenpyoSearch.btnAllKaijyo").prop("disabled", false);
            $(".HMDPS100DenpyoSearch.btnDenpyPrint").prop("disabled", false);
        } else {
            $(".HMDPS100DenpyoSearch.pnlList").hide();
            $(".HMDPS100DenpyoSearch.pnlallbutton").hide();
            $(".HMDPS100DenpyoSearch.btnAllSelect").prop(
                "disabled",
                "disabled"
            );
            $(".HMDPS100DenpyoSearch.btnAllKaijyo").prop(
                "disabled",
                "disabled"
            );
            $(".HMDPS100DenpyoSearch.btnDenpyPrint").prop(
                "disabled",
                "disabled"
            );
        }
    };
    me.btnAllSelect_Click = function (_blnHantei) {
        $(me.grid_id).jqGrid("resetSelection");
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        ids.forEach(function (element) {
            $(me.grid_id).jqGrid("setSelection", element, true);
        });
    };
    me.btnAllKaijyo_Click = function (_blnHantei) {
        $(me.grid_id).jqGrid("resetSelection");
        // $(me.grid_id).trigger('reloadGrid');
        // var ids = $(me.grid_id).jqGrid('getDataIDs');
        // $(".cbox").prop("checked", false);
        // for (var i = 0; i < ids.length; i++)
        // {
        // $(me.grid_id).jqGrid('setSelection', i, false);
        // // $("#jqg_HMDPS100DenpyoSearch_grdList_" + i).prop("checked", false);
        // }
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
                dialogId = "HMDPS701KamokuSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRKSearch"
                        ? $(".HMDPS100DenpyoSearch.txtRKamokuCD")
                        : $(".HMDPS100DenpyoSearch.txtLKamokuCD");
                $txtSearchNM =
                    searchButton == "btnRKSearch"
                        ? $(".HMDPS100DenpyoSearch.lblRkamokuNM")
                        : $(".HMDPS100DenpyoSearch.lblLkamokuNM");
                divCD = "KamokuCD";
                divNM = "KamokuNM";
                frmId = "HMDPS701KamokuSearch";
                title = "科目マスタ検索";
                break;
            case "btnBusyoSearch":
                dialogId = "HMDPS702BusyoSearchDialogDiv";
                $txtSearchCD = $(".HMDPS100DenpyoSearch.txtBusyoCD");
                $txtSearchNM = $(".HMDPS100DenpyoSearch.lblBusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HMDPS702BusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnSyainSearch":
                dialogId = "HMDPS703SyainSearchDialogDiv";
                $txtSearchCD = $(".HMDPS100DenpyoSearch.txtSyainNO");
                $txtSearchNM = $(".HMDPS100DenpyoSearch.lblSyainNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HMDPS703SyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HMDPS100DenpyoSearch.HMDPS-content");

        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }

        $("<div></div>").prop("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").prop("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").prop("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").prop("id", divNM).insertAfter($rootDiv).hide();
        $("<div></div>").prop("id", koumkuCd).insertAfter($rootDiv).hide();

        if (searchButton == "btnSyainSearch") {
            $("<div></div>").prop("id", "syain").insertAfter($rootDiv).hide();
            var $syainSearch = $rootDiv.parent().find("#" + "syain");
            $syainSearch.val("syain");
        }
        var $RtnCD = $rootDiv.parent().find("#" + cd);
        var $SearchCD = $rootDiv.parent().find("#" + divCD);
        var $SearchNM = $rootDiv.parent().find("#" + divNM);
        var $koumkuCd = $rootDiv.parent().find("#" + koumkuCd);

        $SearchCD.val($.trim($txtSearchCD.val()));
        $koumkuCd.val("10");
        $(".HMDPS100DenpyoSearch.txtSyohyNO").trigger("focus");
        var ratio = window.devicePixelRatio || 1;
        var height = ratio === 1.5 ? 558 : 630;

        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: 500,
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
                    $(".HMDPS100DenpyoSearch." + searchButton).trigger("focus");
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
    var o_HMDPS_HMDPS100DenpyoSearch = new HMDPS.HMDPS100DenpyoSearch();
    o_HMDPS_HMDPS100DenpyoSearch.load();
    o_HMDPS_HMDPS.HMDPS100DenpyoSearch = o_HMDPS_HMDPS100DenpyoSearch;
});
