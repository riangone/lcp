/**
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付					Feature/Bug						            内容											                          担当
 * YYYYMMDD				#ID									          XXXXXX											                         GSDL
 * 20240426		   CSV再出力		   グリッドの高さ・幅が ウインドウのサイズに追従する		       lujunxia
 * 20240430      CSV再出力      一覧グリッドについて   件数のブルダウンを15、30、45に変更    lujunxia
 * 20240517      CSV再出力      出力済データを選択したところ一部しかデータが表示されない    　lujunxia
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS105CSVReOut");

HMDPS.HMDPS105CSVReOut = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HMDPS = new HMDPS.HMDPS();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.grid_grdGroupList_id = "#HMDPS105CSVReOut_grdGroupList";
    me.grid_pnlCsvOut_id = "#HMDPS105CSVReOut_pnlCsvOut";
    me.pager = "#HMDPS105CSVReOut_pager";
    me.sys_id = "HMDPS";

    me.id = "HMDPS105CSVReOut";
    //仕訳データの取得
    me.g_urlGroupAndSyohy =
        me.sys_id + "/" + me.id + "/" + "fncSelGroupAndSyohyShiwakeData";
    //検索
    me.g_urlKensaku = me.sys_id + "/" + me.id + "/" + "Kensaku_Click";
    me.ratio = window.devicePixelRatio || 1;
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
        //20240430 lujunxia upd s
        //rowNum: 10,
        rowNum: 15,
        //rowList: [10, 20, 30],
        rowList: [15, 30, 45],
        //20240430 lujunxia upd e
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
        //20240426 lujunxia ins s
        //列幅を自動幅に設定する
        shrinkToFit: true,
        //20240426 lujunxia ins e
    };

    me.option2 = {
        multiselect: true,
        caption: "",
        //20240517 lujunxia ins s
        rowNum: 9999,
        //20240517 lujunxia ins e
        rownumbers: true,
        scroll: false,
        //20240426 lujunxia ins s
        multiselectWidth: me.ratio === 1.5 ? 25 : 36,
        //列幅を自動幅に設定する
        shrinkToFit: true,
        //20240426 lujunxia ins e
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
            width: 223,
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
                    "<button onclick=\"grdGroupList_RowCommand('" +
                    rowObject["GROUP_NO"] +
                    "','" +
                    rowObject["KEIRI_DT"] +
                    "','" +
                    "2" +
                    "')\" id = '" +
                    rowObject.clid +
                    "_btnSelect' class=\"HMDPS105CSVReOut btnSelect Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
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
            width: 166,
            sortable: false,
        },
        {
            label: "借方科目",
            name: "KARIKATA",
            index: "KARIKATA",
            align: "left",
            search: false,
            width: 134,
            sortable: false,
        },
        {
            label: "貸方科目",
            name: "KASHIKATA",
            index: "KASHIKATA",
            align: "left",
            search: false,
            width: 134,
            sortable: false,
        },
        {
            label: "金額",
            name: "KINGAKU",
            index: "KINGAKU",
            align: "right",
            search: false,
            width: 96,
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
            width: 50,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openEditShiwake('" +
                    rowObject["SYOHYO_NO_VIEW"] +
                    "','" +
                    rowObject["SYOHYO_NO"].substring(0, 1) +
                    "','" +
                    rowObject["RENBAN"] +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HMDPS105CSVReOut UPD_DATE Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>編集</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMDPS105CSVReOut.btnBusyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.btnCsvOut",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.btnTantou",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.Kensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.CSVStart",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.CSVEnd",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS105CSVReOut.txtInputKeiriDt",
        type: "datepicker",
        handle: "",
    });
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
    //部署コードボタンクリック
    $(".HMDPS105CSVReOut.btnBusyo").click(function () {
        me.openSearchDialog("btnBusyo");
    });
    //担当者コードボタンクリック
    $(".HMDPS105CSVReOut.btnTantou").click(function () {
        me.openSearchDialog("btnTantou");
    });

    //検索ボタンクリック
    $(".HMDPS105CSVReOut.Kensaku").click(function () {
        me.Kensaku_click();
    });
    //担当者コード変更してフォーカスを失う
    $(".HMDPS105CSVReOut.ltxtTantouCD").on("blur", function () {
        $(".HMDPS105CSVReOut.TantouNM").val("");
        if ($(this).val() !== "") {
            me.ltxtTantouCD_CheckedChanged($(this).val());
        }
    });
    //部署コード変更してフォーカスを失う
    $(".HMDPS105CSVReOut.ltxtBusyoCD").on("blur", function () {
        $(".HMDPS105CSVReOut.BusyoNM").val("");
        if ($(this).val() !== "") {
            me.ltxtBusyoCD_CheckedChanged($(this).val());
        }
    });
    //担当者コード,部署コード,グループ名変更
    $(
        ".HMDPS105CSVReOut.ltxtTantouCD,.HMDPS105CSVReOut.ltxtBusyoCD,.HMDPS105CSVReOut.txtGroupName"
    ).change(function () {
        me.txtGroupName_CheckedChanged();
    });
    $(".HMDPS105CSVReOut.CSVStart").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "CSVStart");
        } else {
            $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
            $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        }
    });
    $(".HMDPS105CSVReOut.CSVEnd").on("blur", function () {
        if ($(this).val() !== "") {
            me.dateChanged(this, "CSVEnd");
        } else {
            $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
            $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        }
    });

    //CSV出力ボタンクリック
    $(".HMDPS105CSVReOut.btnCsvOut").click(function () {
        me.btnCsvOut_Click();
    });

    //経理処理日
    $(".HMDPS105CSVReOut.txtInputKeiriDt").on("blur", function () {
        me.dateChanged(this, "txtInputKeiriDt");
    });
    //20240426 lujunxia ins s
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    //左メニューサイズ変更時にグリッドの大きさも追従
    var index = 1;
    var ele = document.querySelector(".HMDPS105CSVReOut.HMDPS-content");
    var resizeObserver = new ResizeObserver(function () {
        if (index != 1) {
            me.setTableSize();
        }
        // 20241226 LHB UPD S
        // setTimeout(() => {
        setTimeout(function () {
            // 20241226 LHB UPD E
            index++;
        }, 500);
    });
    resizeObserver.observe(ele);
    //20240426 lujunxia ins e
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
            //20240426 lujunxia upd s
            // var widthTotal = $(".HMDPS105CSVReOut fieldset").width();
            // if (
            // 	navigator.userAgent.toUpperCase().indexOf("CHROME") > -1 ||
            // 	navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1
            // ) {
            // 	gdmz.common.jqgrid.set_grid_width(
            // 		me.grid_grdGroupList_id,
            // 		widthTotal * 0.45
            // 	);
            // 	gdmz.common.jqgrid.set_grid_width(
            // 		me.grid_pnlCsvOut_id,
            // 		widthTotal * 0.55
            // 	);
            // } else {
            // 	gdmz.common.jqgrid.set_grid_width(
            // 		me.grid_grdGroupList_id,
            // 		widthTotal * 0.4
            // 	);
            // 	gdmz.common.jqgrid.set_grid_width(
            // 		me.grid_pnlCsvOut_id,
            // 		widthTotal * 0.6
            // 	);
            // }
            // gdmz.common.jqgrid.set_grid_height(me.grid_grdGroupList_id, 260);
            // gdmz.common.jqgrid.set_grid_height(me.grid_pnlCsvOut_id, 270);
            me.setTableSize();
            //20240426 lujunxia upd e

            //No追加タイトル
            $("#HMDPS105CSVReOut_pnlCsvOut_rn").html("№");
            //出力対象のスタイルの設定
            $("#jqgh_HMDPS105CSVReOut_pnlCsvOut_cb").html("出力対象");
            //20240426 lujunxia del s
            //グリッドのサイズが変更する時、ボタンが表示不全の問題があるので
            // $("#HMDPS105CSVReOut_pnlCsvOut_cb").css("width", "36px");
            // $("#HMDPS105CSVReOut_pnlCsvOut tbody tr td").eq(1).css("width", "36px");
            //20240426 lujunxia del e

            //Gridのチェックボックス変更時の同期処理
            $(me.grid_pnlCsvOut_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, status, e) {
                    if (e != undefined) {
                        SubResetCSVStatus(rowid, status);
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
            $(".HMDPS105CSVReOut.CSVStart").val(
                new Date(dateFrom).Format("yyyy/MM/dd")
            );
            me.cboYMStart = new Date(dateFrom).Format("yyyy/MM/dd");
        };
        me.ajax.send(url, data, 0);

        $(".HMDPS105CSVReOut.txtGroupName").trigger("focus");
        $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        $(me.grid_grdGroupList_id).jqGrid("setGridParam", {
            //ページをめくる事件
            onPaging: function () {
                me.flg = "";
                $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
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
        var txtGroupName = $.trim($(".HMDPS105CSVReOut.txtGroupName").val());
        var ltxtBusyoCD = $.trim($(".HMDPS105CSVReOut.ltxtBusyoCD").val());
        var CSVStart = $.trim(
            $(".HMDPS105CSVReOut.CSVStart").val().replace(/\//g, "")
        );
        var CSVEnd = $.trim(
            $(".HMDPS105CSVReOut.CSVEnd").val().replace(/\//g, "")
        );
        var ltxtTantouCD = $.trim($(".HMDPS105CSVReOut.ltxtTantouCD").val());
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
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
                $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
                $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                $(me.grid_grdGroupList_id).jqGrid("setSelection", selId, true);
                if (me.flg == "csvout") {
                    $(me.grid_pnlCsvOut_id).trigger("reloadGrid");
                    $(".HMDPS105CSVReOut.PnlCsvOutTableRow").show();
                    $(".HMDPS105CSVReOut.Kensaku").trigger("focus");
                }
            }

            $(".HMDPS105CSVReOut.grdGroupListTableRow").show();
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_grdGroupList_id,
            me.data,
            complete_fun
        );
    };
    //**********************************************************************
    //処 理 名：グループ一覧の選択ボタン押下時
    //関 数 名：grdGroupList_RowCommand
    //引    数：無し
    //戻 り 値：なし
    //処理説明：グループ一覧の選択ボタンが押下された行のグループの仕訳を一覧に表示する
    //**********************************************************************
    grdGroupList_RowCommand = function (lblGroupNo, keiriDt, flg) {
        me.lblGroupNo = lblGroupNo;
        me.keiriDt = keiriDt;
        if (flg == 2) {
            //経理処理日
            $(".HMDPS105CSVReOut.txtInputKeiriDt").val(me.keiriDt);
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
                    $(".HMDPS105CSVReOut.txtInputGroupNM").val(
                        result["txtGroupName"]["0"]["CSV_GROUP_NM"]
                    );
                } else {
                    $(".HMDPS105CSVReOut.txtInputGroupNM").val("");
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
                $("#HMDPS105CSVReOut_pnlCsvOut #" + ids[i]).css(
                    "background",
                    "#FF8C00"
                );
            }
        }
        //書式
        intKensu = me.parseFormatNum(intKensu);
        sum = me.parseFormatNum(sum);
        $(".HMDPS105CSVReOut.lvTxtKingakuSum").val(sum);
        $(".HMDPS105CSVReOut.lvTxtCount").val(intKensu);
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").show();
    };

    //**********************************************************************
    //処 理 名：Gridのチェックボックス変更時の同期処理
    //関 数 名：SubResetCSVStatus
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    SubResetCSVStatus = function (element, status) {
        var decCount = parseInt(
            $(".HMDPS105CSVReOut.lvTxtCount").val().replace(/,/g, "")
        );
        var decKingaku = parseInt(
            $(".HMDPS105CSVReOut.lvTxtKingakuSum").val().replace(/,/g, "")
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
        $(".HMDPS105CSVReOut.lvTxtCount").val(me.parseFormatNum(decCount));
        // 出力対象金額合計
        $(".HMDPS105CSVReOut.lvTxtKingakuSum").val(
            me.parseFormatNum(decKingaku)
        );
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
            var curChk = $("#HMDPS105CSVReOut_pnlCsvOut " + "#" + ids[i]).find(
                ":checkbox"
            );
            rowData[i]["CHK_CSV_FLG"] = curChk[0].checked ? "1" : "0";
        }

        var data = {
            txtInputGroupNM: $(".HMDPS105CSVReOut.txtInputGroupNM").val(),
            txtInputKeiriDt: $(".HMDPS105CSVReOut.txtInputKeiriDt").val(),
            strGroup_no: me.lblGroupNo,
            data: rowData,
            CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
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
                            "#HMDPS105CSVReOut_pnlCsvOut #" +
                                result["data"]["rowNum"]
                        ).css("background", "#A06633");
                    }
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

        if ($(".HMDPS105CSVReOut.txtInputGroupNM").val().length == 0) {
            $(".HMDPS105CSVReOut.txtInputGroupNM").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "出力グループ名が未入力です！");
            return false;
        } else {
            // '/** 禁則 **/
            if (
                objRegEx_NG.test(
                    $(".HMDPS105CSVReOut.txtInputGroupNM").val().trimEnd()
                )
            ) {
                $(".HMDPS105CSVReOut.txtInputGroupNM").trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "出力グループ名に不正な文字が入力されています！"
                );
                return false;
            }

            //出力桁数のチェック
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS105CSVReOut.txtInputGroupNM")
                        .val()
                        .trimEnd()
                        .replace(/,/g, "")
                ) > 40
            ) {
                $(".HMDPS105CSVReOut.txtInputGroupNM").trigger("focus");
                //vb:E0013
                me.clsComFnc.FncMsgBox("E0027", "出力グループ名", 40);
                return false;
            }
        }

        if ($(".HMDPS105CSVReOut.lvTxtCount").val() == 0) {
            $(".HMDPS105CSVReOut.lvTxtCount").trigger("focus");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ＣＳＶ出力の対象が選択されていません！"
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

            $(".HMDPS105CSVReOut.Kensaku").trigger("focus");
        }
        $(".HMDPS105CSVReOut.TantouNM").val(foundNM ? foundNM["SYAIN_NM"] : "");
        $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
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
            $(".HMDPS105CSVReOut.CSVStart").trigger("focus");
        }
        $(".HMDPS105CSVReOut.BusyoNM").val(foundNM ? foundNM["BUSYO_NM"] : "");
        $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
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
                dialogId = "HMDPS702BusyoSearchDialogDiv";
                $txtSearchCD = $(".HMDPS105CSVReOut.ltxtBusyoCD");
                $txtSearchNM = $(".HMDPS105CSVReOut.BusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HMDPS702BusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnTantou":
                //社員
                dialogId = "HMDPS703SyainSearchDialogDiv";
                $txtSearchCD = $(".HMDPS105CSVReOut.ltxtTantouCD");
                $txtSearchNM = $(".HMDPS105CSVReOut.TantouNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HMDPS703SyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HMDPS105CSVReOut.HMDPS-content");
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
        $(".HMDPS105CSVReOut.txtGroupName").trigger("focus");
        var width = me.ratio === 1.5 ? 488 : 500;
        var height = me.ratio === 1.5 ? 558 : 630;
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: width,
            resizable: false,
            close: function () {
                var $RtnCD = $rootDiv.parent().find("#" + cd);
                var $SearchCD = $rootDiv.parent().find("#" + divCD);
                var $SearchNM = $rootDiv.parent().find("#" + divNM);
                if ($RtnCD.html() == 1) {
                    $txtSearchCD.val($SearchCD.html());
                    $txtSearchNM.val($SearchNM.html());
                    $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
                    $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
                }
                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();

                if (searchButton == "btnTantou") {
                    $syainSearch.remove();
                }

                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HMDPS105CSVReOut." + searchButton).trigger("focus");
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

    openEditShiwake = function (id, denpykbn, selno) {
        var frmId = "HMDPS102ShiharaiDenpyoInput";
        var dialogdiv = "HMDPS102ShiharaiDenpyoInputDialogDiv";
        if (denpykbn == "1") {
            var frmId = "HMDPS101ShiwakeDenpyoInput";
            var dialogdiv = "HMDPS101ShiwakeDenpyoInputDialogDiv";
        }
        var $rootDiv = $(".HMDPS105CSVReOut.HMDPS-content");

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
        $DISP_NO.html("105");
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
                grdGroupList_RowCommand(me.lblGroupNo, me.keiriDt, "1");
            }
            $("#" + dialogdiv).append(result);
            if (frmId == "HMDPS101ShiwakeDenpyoInput") {
                o_HMDPS_HMDPS.HMDPS105CSVReOut.HMDPS101ShiwakeDenpyoInput.before_close =
                    before_close;
            } else {
                o_HMDPS_HMDPS.HMDPS105CSVReOut.HMDPS102ShiharaiDenpyoInput.before_close =
                    before_close;
            }
        };
    };

    me.txtGroupName_CheckedChanged = function () {
        $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
        $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        $(".HMDPS105CSVReOut.ltxtBusyoCD").trigger("focus");
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
            $(".HMDPS105CSVReOut.grdGroupListTableRow").hide();
            $(".HMDPS105CSVReOut.PnlCsvOutTableRow").hide();
        }
    };
    //20240426 lujunxia ins s
    //グリッドの高さ・幅が ウインドウのサイズに追従する
    me.setTableSize = function () {
        var heightTotal = $(".HMDPS.HMDPS-layout-center").height();
        var fieldsetHeight = $(".HMDPS105CSVReOut fieldset").height();
        var gridHeight = heightTotal - fieldsetHeight - 90;
        gdmz.common.jqgrid.set_grid_height(
            me.grid_grdGroupList_id,
            gridHeight
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_pnlCsvOut_id,
            gridHeight - (me.ratio === 1.5 ? 67 : 80)
        );
        var widthTotal = $(".HMDPS105CSVReOut fieldset").width();
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
        //20240517 lujunxia del s
        //tableが「hide」になる時、この方法に入ることができるので、reloadを削除する
        //$(me.grid_pnlCsvOut_id).trigger("reloadGrid");
        //20240517 lujunxia del e
    };
    //20240426 lujunxia ins e
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HMDPS105CSVReOut_HMDPS105CSVReOut = new HMDPS.HMDPS105CSVReOut();
    o_HMDPS105CSVReOut_HMDPS105CSVReOut.load();
    o_HMDPS_HMDPS.HMDPS105CSVReOut = o_HMDPS105CSVReOut_HMDPS105CSVReOut;
});
