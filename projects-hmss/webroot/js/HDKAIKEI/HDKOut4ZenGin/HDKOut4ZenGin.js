/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240318        本番障害.xlsx NO5     編集ボタン追加、編集ボタンクリックで伝票入力画面に遷移        lujunxia
 * 20240322       本番障害.xlsx NO8         科目名、補助科目名のいずれかしか表示していない箇所があるが  caina
 * 20240328      全銀協連携データ出力       画面構成を OBC取込データ出力 と同じ構成                  lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKOut4ZenGin");

HDKAIKEI.HDKOut4ZenGin = function () {
    var me = new gdmz.base.panel();
    me.ajax = new gdmz.common.ajax();
    me.MessageBox = new gdmz.common.MessageBox();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "HDKOut4ZenGin";
    me.sys_id = "HDKAIKEI";
    me.grid_id = "#HDKOut4ZenGin_table";
    //20240328 lujunxia ins s
    me.grid_id_selected = "#HDKOut4ZenGin_table_selected";
    //20240328 lujunxia ins e
    me.g_url = me.sys_id + "/" + me.id + "/FncSetData";
    me.retCSVFLG = "";
    me.HDKOut4ZenGin_CSV_TYPE = "";
    //科目
    me.kamokuData = "";
    //部署
    me.busyoData = "";
    //作成担当者
    me.tanntousyaData = "";
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: true,
        //列幅を自動幅に設定する
        shrinkToFit: true,
    };
    me.colModel = [
        {
            name: "CSV_STATUS",
            label: "CSV",
            index: "CSV_STATUS",
            align: "left",
            hidden: true,
        },
        {
            name: "SYOHYO_KBN",
            label: "読取書類",
            index: "SYOHYO_KBN",
            sortable: false,
            align: "left",
            width: 80,
        },
        {
            name: "SYOHYO_NO",
            label: "",
            index: "SYOHYO_NO",
            align: "left",
            hidden: true,
        },
        {
            name: "EDA_NO",
            label: "",
            index: "EDA_NO",
            align: "left",
            hidden: true,
        },
        {
            name: "SYOHYO_NO_VIEW",
            label: "証憑№",
            index: "SYOHYO_NO_VIEW",
            sortable: false,
            align: "left",
            width: me.ratio === 1.5 ? 150 : 160,
        },
        {
            name: "KARIKATA",
            label: "借方科目",
            index: "KARIKATA",
            sortable: false,
            align: "left",
            width: 200,
            //20240322 caina ins s
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
            //20240322 caina ins e
        },
        {
            name: "KASHIKATA",
            label: "貸方科目",
            index: "KASHIKATA",
            sortable: false,
            align: "left",
            width: 200,
            //20240322 caina ins s
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["R_KAMOKU"] !== null &&
                    rowObject["R_KAMOKU"] !== "" &&
                    rowObject["R_KOUMKU"] !== null &&
                    rowObject["R_KOUMKU"] !== ""
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
            //20240322 caina ins e
        },
        {
            name: "KINGAKU",
            label: "金額",
            index: "KINGAKU",
            align: "right",
            sortable: false,
            width: 112,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "HUKANZEN_STATUS",
            label: "",
            index: "HUKANZEN_STATUS",
            align: "left",
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "",
            index: "UPD_DATE",
            align: "left",
            hidden: true,
        },
        //20240328 lujunxia upd s
        {
            name: "L_KAMOKU",
            label: "",
            index: "L_KAMOKU",
            align: "left",
            hidden: true,
        },
        {
            name: "R_KAMOKU",
            label: "",
            index: "R_KAMOKU",
            align: "left",
            hidden: true,
        },
        {
            name: "L_KOUMKU",
            label: "",
            index: "L_KOUMKU",
            align: "left",
            hidden: true,
        },
        {
            name: "R_KOUMKU",
            label: "",
            index: "R_KOUMKU",
            align: "left",
            hidden: true,
        },
        //20240318 lujunxia ins s
        // {
        // 	name: "",
        // 	index: "btnEdit",
        // 	width: 50,
        // 	align: "left",
        // 	formatter: function (cellvalue, options, rowObject) {
        // 		var detail =
        // 			"<button onclick=\"openEditShiwakeZenGin(event,'" +
        // 			rowObject["SYOHYO_NO_VIEW"] +
        // 			"')\" id = '" +
        // 			i +
        // 			"_btnEdit' class=\"HDKOut4ZenGin Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:13px;'>編集</button>";
        // 		return detail;
        // 	},
        // },
        //20240318 lujunxia ins e
    ];
    me.colModel1 = JSON.parse(JSON.stringify(me.colModel));
    me.colModel.push({
        name: "",
        index: "btnEdit",
        width: 50,
        align: "left",
        formatter: function (_cellvalue, _options, rowObject) {
            var detail =
                "<button onclick=\"openEditShiwakeZenGin(event,'" +
                rowObject["SYOHYO_NO_VIEW"] +
                "')\" id = '" +
                i +
                "_btnEdit' class=\"HDKOut4ZenGin Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
                (me.ratio === 1.5 ? "10" : "13") +
                "px;'>編集</button>";
            return detail;
        },
    });
    $(me.grid_id_selected).jqGrid({
        datatype: "local",
        colModel: me.colModel1,
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: true,
        //列幅を自動幅に設定する
        shrinkToFit: true,
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
    });
    $(".HDKOut4ZenGin.rightList").hide();
    //20240328 lujunxia upd e
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKOut4ZenGin.buttonStyle",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKOut4ZenGin.datepickerStyle",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // CSV出力ボタンクリック
    $(".HDKOut4ZenGin.btnCsvOut").click(function () {
        //出力対象のチェック
        me.FncChkInput_CSVOUT();
    });
    // Enterキーを押す
    $(".HDKOut4ZenGin.keywordInput").on("keydown", function (e) {
        var key = e.which;
        if (key == 13) {
            me.cmdEventEnter_Click();
        }
    });
    //検索ボタンクリック
    $(".HDKOut4ZenGin.btnSearch").click(function () {
        //出力対象のチェック
        me.cmdEventEnter_Click();
    });
    // 出力対象金額合計blur
    $(".HDKOut4ZenGin.lvTxtKingakuSum").on("blur", function (e) {
        var txtValue = $.trim($(e.target).val()).replace(/\b(0+)/gi, "");
        $(e.target).val(txtValue.replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,"));
        if ($(e.target).val() == "" || $(e.target).val() == "-") {
            $(e.target).val(0);
        }
    });
    //キャンセルボタンクリック
    $(".HDKOut4ZenGin.btnCancle").click(function () {
        me.SubCtrlInit();
    });
    //部署検索
    $(".HDKOut4ZenGin.btnBusyoSearch").click(function () {
        me.openSearchDialog("btnBusyoSearch");
    });
    //作成担当者検索
    $(".HDKOut4ZenGin.btnTanntousyaSearch").click(function () {
        me.openSearchDialog("btnTanntousyaSearch");
    });
    //借方科目検索
    $(".HDKOut4ZenGin.btnLKamokuSearch").click(function () {
        me.openSearchDialog("btnLKamokuSearch");
    });
    //貸方科目検索
    $(".HDKOut4ZenGin.btnRKamokuSearch").click(function () {
        me.openSearchDialog("btnRKamokuSearch");
    });
    //借方科目コード変更してフォーカスを失う
    $(".HDKOut4ZenGin.lKamokuInput").on("change", function () {
        me.fncBlur($(this).val(), "lKamokuLabel");
    });
    //貸方科目コード変更してフォーカスを失う
    $(".HDKOut4ZenGin.rKamokuInput").on("change", function () {
        me.fncBlur($(this).val(), "rKamokuLabel");
    });
    //部署変更してフォーカスを失う
    $(".HDKOut4ZenGin.busyoInput").on("change", function () {
        me.fncBlur($(this).val(), "busyoLabel");
    });
    //作成担当者変更してフォーカスを失う
    $(".HDKOut4ZenGin.tanntousyaInput").on("change", function () {
        me.fncBlur($(this).val(), "tanntousyaLabel");
    });
    //20240328 lujunxia ins s
    //「↑」ボタン
    $(".HDKOut4ZenGin.noselectBtn").click(function () {
        me.noselectBtnClick();
    });
    //「↓」ボタン
    $(".HDKOut4ZenGin.selectBtn").click(function () {
        me.selectBtnClick();
    });
    //クリアボタン
    $(".HDKOut4ZenGin.clearBtn").click(function () {
        //「選択データ一覧」テーブルはクリアする
        $(me.grid_id_selected).jqGrid("clearGridData");
        // 出力対象件数
        $(".HDKOut4ZenGin.lvTxtCount").val(0);
        // 出力対象金額合計
        $(".HDKOut4ZenGin.lvTxtKingakuSum").val(0);
        me.cmdEventEnter_Click();
    });
    //20240328 lujunxia ins e
    //ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    //左メニューサイズ変更時にグリッドの大きさも追従
    var index = 1;
    var ele = document.querySelector(".HDKOut4ZenGin.HDKAIKEI-content");
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
    /*
	 '**********************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FncGetMaster";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                $(".HDKOut4ZenGin.rightList").hide();
                $(".HDKOut4ZenGin.btnSearch").button("disable");
                setTimeout(function () {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }, 100);
            } else {
                //科目
                me.kamokuData = result["data"]["kamoku"];
                //部署
                me.busyoData = result["data"]["busyo"];
                //作成担当者
                me.tanntousyaData = result["data"]["tanntousya"];

                me.FncSetData();
                me.SubCtrlInit();
                //20240328 lujunxia ins s
                // 出力対象件数
                $(".HDKOut4ZenGin.lvTxtCount").val(0);
                // 出力対象金額合計
                $(".HDKOut4ZenGin.lvTxtKingakuSum").val(0);
                //20240328 lujunxia ins e
            }
        };
        me.ajax.send(url, "", 0);
    };
    //ウインドウサイズ変更時にグリッドの大きさも追従
    me.setTableSize = function () {
        var pageHeight = $(".HDKAIKEI.HDKAIKEI-layout-center").height();
        //20240328 lujunxia upd s
        //gdmz.common.jqgrid.set_grid_height(me.grid_id, pageHeight - 145);
        var pageWidth = $(".HDKAIKEI.HDKAIKEI-layout-center").width();
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, (pageWidth * 3) / 5);
        var tableHeight = (pageHeight - 165) / 2;
        var tableWidth = (pageWidth * 3) / 5;
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, tableWidth);
        gdmz.common.jqgrid.set_grid_height(me.grid_id_selected, tableHeight);
        gdmz.common.jqgrid.set_grid_width(me.grid_id_selected, tableWidth);
        //20240328 lujunxia upd e
    };
    /*
	 '**********************************************************************
	 '処 理 名：コントロールの初期化
	 '関 数 名：SubCtrlInit
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.SubCtrlInit = function () {
        $(me.grid_id).jqGrid("clearGridData");
        //経理処理日
        keiriSyoribiFrom = $(".HDKOut4ZenGin.keiriSyoribiFrom").val("");
        keiriSyoribiTo = $(".HDKOut4ZenGin.keiriSyoribiTo").val("");
        //部署
        $(".HDKOut4ZenGin.busyoInput").val("");
        $(".HDKOut4ZenGin.busyoLabel").text("");
        //作成担当者
        $(".HDKOut4ZenGin.tanntousyaInput").val("");
        $(".HDKOut4ZenGin.tanntousyaLabel").text("");
        //科目
        $(".HDKOut4ZenGin.lKamokuInput").val("");
        $(".HDKOut4ZenGin.lKamokuLabel").text("");
        $(".HDKOut4ZenGin.rKamokuInput").val("");
        $(".HDKOut4ZenGin.rKamokuLabel").text("");
        //キーワード
        $(".HDKOut4ZenGin.keywordInput").val("");
        //経理処理日
        $(".HDKOut4ZenGin.lvTxtKeiriSyoribi").val("");
        //出力グループ名
        $(".HDKOut4ZenGin.lvTxtGroupName").val("");
        //20240328 lujunxia del s
        // 出力対象件数
        //$(".HDKOut4ZenGin.lvTxtCount").val(0);
        // 出力対象金額合計
        //$(".HDKOut4ZenGin.lvTxtKingakuSum").val(0);
        //20240328 lujunxia del e
    };
    /*
	 '**********************************************************************
	 '処 理 名：CSV出力時のチェック処理
	 '関 数 名：FncChkInput_CSVOUT
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FncChkInput_CSVOUT = function () {
        // 経理処理日
        var lvTxtKeiriSyoribi = $(".HDKOut4ZenGin.lvTxtKeiriSyoribi");
        var valDate = lvTxtKeiriSyoribi.val();
        if ($.trim(valDate) == "") {
            me.clsComFnc.ObjFocus = lvTxtKeiriSyoribi;
            me.clsComFnc.FncMsgBox("W9999", "経理処理日が未入力です！");
            return;
        }
        if (valDate.length == 8 && valDate.indexOf("/") == -1) {
            $(".HDKOut4ZenGin.lvTxtKeiriSyoribi").val(
                valDate.substring(0, 4) +
                    "/" +
                    valDate.substring(4, 6) +
                    "/" +
                    valDate.substring(6, 8)
            );
        }
        if (me.clsComFnc.CheckDate(lvTxtKeiriSyoribi) == false) {
            $(".HDKOut4ZenGin.lvTxtKeiriSyoribi").val(valDate);
            me.clsComFnc.ObjFocus = lvTxtKeiriSyoribi;
            me.clsComFnc.FncMsgBox("W9999", "経理処理日の入力形式が不正です！");
            return;
        }
        // 出力グループ名
        var lvTxtGroupName = $(".HDKOut4ZenGin.lvTxtGroupName");
        if ($.trim(lvTxtGroupName.val()) == "") {
            me.clsComFnc.ObjFocus = lvTxtGroupName;
            me.clsComFnc.FncMsgBox("W9999", "出力グループ名が未入力です！");
            return;
        }
        if (
            lvTxtGroupName.val().indexOf("'") != -1 ||
            lvTxtGroupName.val().indexOf('"') != -1
        ) {
            me.clsComFnc.ObjFocus = lvTxtGroupName;
            me.clsComFnc.FncMsgBox(
                "W9999",
                "出力グループ名に不正な文字が入力されています！"
            );
            return;
        }
        // バイト数取得
        var len = me.clsComFnc.GetByteCount(lvTxtGroupName.val());
        if (len > 40) {
            me.clsComFnc.ObjFocus = lvTxtGroupName;
            me.clsComFnc.FncMsgBox("E0027", "出力グループ名", "40");
            return;
        }
        // 出力対象件数が0の場合
        //20240328 lujunxia upd s
        //var jqGridRowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var jqGridRowIds = $(me.grid_id_selected).jqGrid("getRowData");
        //20240328 lujunxia upd e
        if (jqGridRowIds.length <= 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "全銀協出力の対象が選択されていません！"
            );
            return;
        }
        // if (jqGridRowIds.length > 1) {
        // 	for (var j = 0; j < jqGridRowIds.length; j++) {
        // 		if (j != 0) {
        // 			$data = $(me.grid_id).getCell(jqGridRowIds[j], "SYOHYO_KBN");
        // 			$data_pre = $(me.grid_id).getCell(jqGridRowIds[j - 1], "SYOHYO_KBN");
        // 			if ($data != $data_pre) {
        // 				me.clsComFnc.FncMsgBox(
        // 					"W9999",
        // 					"全銀協出力の対象の読取書類が違います！"
        // 				);
        // 				return;
        // 			}
        // 		}
        // 	}
        // }
        //出力グループ名の重複チェック
        var url = me.sys_id + "/" + me.id + "/" + "FncChkExistGroupNM";
        var data = {
            //出力グループ名
            lvTxtGroupName: $.trim($(".HDKOut4ZenGin.lvTxtGroupName").val()),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                if (result["error"] == "repeatErr") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に同一のグループ名が登録されています！"
                    );
                } else if (result["error"] == "W0034") {
                    $(".HDKOut4ZenGin." + result["html"]).trigger("focus");
                    me.clsComFnc.FncMsgBox("W0034", result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnCsvOut_Click;
                //CSVを出力します。よろしいですか？
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "全銀協を出力します。よろしいですか？"
                );
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：Enterキーを押す
	 '関 数 名：cmdEventEnter_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdEventEnter_Click = function () {
        $(me.grid_id).jqGrid("clearGridData");
        //20240328 lujunxia del s
        // 出力対象件数
        //$(".HDKOut4ZenGin.lvTxtCount").val(0);
        // 出力対象金額合計
        //$(".HDKOut4ZenGin.lvTxtKingakuSum").val(0);
        //20240328 lujunxia del s
        //経理処理日
        var keiriSyoribiFrom = $(".HDKOut4ZenGin.keiriSyoribiFrom").val();
        if (
            keiriSyoribiFrom.length == 8 &&
            keiriSyoribiFrom.indexOf("/") == -1
        ) {
            $(".HDKOut4ZenGin.keiriSyoribiFrom").val(
                keiriSyoribiFrom.substring(0, 4) +
                    "/" +
                    keiriSyoribiFrom.substring(4, 6) +
                    "/" +
                    keiriSyoribiFrom.substring(6, 8)
            );
        }
        if (
            $.trim($(".HDKOut4ZenGin.keiriSyoribiFrom").val()) != "" &&
            me.clsComFnc.CheckDate($(".HDKOut4ZenGin.keiriSyoribiFrom")) ==
                false
        ) {
            me.clsComFnc.ObjFocus = $(".HDKOut4ZenGin.keiriSyoribiFrom");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "経理処理日(from)の入力形式が不正です！"
            );
            return;
        }
        var keiriSyoribiTo = $(".HDKOut4ZenGin.keiriSyoribiTo").val();
        if (keiriSyoribiTo.length == 8 && keiriSyoribiTo.indexOf("/") == -1) {
            $(".HDKOut4ZenGin.keiriSyoribiTo").val(
                keiriSyoribiTo.substring(0, 4) +
                    "/" +
                    keiriSyoribiTo.substring(4, 6) +
                    "/" +
                    keiriSyoribiTo.substring(6, 8)
            );
        }
        if (
            $.trim($(".HDKOut4ZenGin.keiriSyoribiTo").val()) != "" &&
            me.clsComFnc.CheckDate($(".HDKOut4ZenGin.keiriSyoribiTo")) == false
        ) {
            me.clsComFnc.ObjFocus = $(".HDKOut4ZenGin.keiriSyoribiTo");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "経理処理日(to)の入力形式が不正です！"
            );
            return;
        }
        var startDt = new Date(
            $.trim($(".HDKOut4ZenGin.keiriSyoribiFrom").val())
        );
        var endDt = new Date($.trim($(".HDKOut4ZenGin.keiriSyoribiTo").val()));
        //開始日～終了日大小チェック。
        if (startDt && endDt && startDt > endDt) {
            me.clsComFnc.ObjFocus = $(".HDKOut4ZenGin.keiriSyoribiFrom");
            me.clsComFnc.FncMsgBox("W0006", "経理処理日");
            return;
        }
        //Gridへのデータセット
        me.FncSetData();
    };
    /*
	 '**********************************************************************
	 '処 理 名：Gridへのデータセット
	 '関 数 名：FncSetData
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FncSetData = function () {
        $.jgrid.gridUnload(me.grid_id);
        //経理処理日
        var keiriSyoribiFrom = $(".HDKOut4ZenGin.keiriSyoribiFrom").val();
        var keiriSyoribiTo = $(".HDKOut4ZenGin.keiriSyoribiTo").val();
        //部署
        var busyo = $(".HDKOut4ZenGin.busyoInput").val();
        //作成担当者
        var tanntousya = $(".HDKOut4ZenGin.tanntousyaInput").val();
        //科目
        var kamoku1 = $(".HDKOut4ZenGin.lKamokuInput").val();
        var kamoku2 = $(".HDKOut4ZenGin.rKamokuInput").val();
        //キーワード
        var keyword = $(".HDKOut4ZenGin.keywordInput").val();
        //20240328 lujunxia ins s
        //「選択データ一覧」にデータが表示しない
        var selectedNo = "";
        if ($(me.grid_id_selected)) {
            var ids = $(me.grid_id_selected).jqGrid("getDataIDs");
            if (ids.length > 0) {
                var sel_no_ar = $(me.grid_id_selected).jqGrid("getCol", 3);
                selectedNo = sel_no_ar.join("','");
            }
        }
        //20240328 lujunxia ins e
        var data = {
            keiriSyoribiFrom: keiriSyoribiFrom.replace(/\//g, ""),
            keiriSyoribiTo: keiriSyoribiTo.replace(/\//g, ""),
            busyo: $.trim(busyo),
            tanntousya: $.trim(tanntousya),
            kamoku1: $.trim(kamoku1),
            kamoku2: $.trim(kamoku2),
            keyword: $.trim(keyword),
            //20240328 lujunxia ins s
            selectedNo: selectedNo,
            //20240328 lujunxia ins e
        };
        me.complete_fun = function (returnFLG, result) {
            //20240328 lujunxia ins s
            $(".HDKOut4ZenGin.rightList").show();
            //20240328 lujunxia ins e
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG != "nodata") {
                me.HDKOut4ZenGin_CSV_TYPE = me.retCSVFLG;
                //20240328 lujunxia del s
                //出力対象件数,出力対象金額合計のセット
                // $(me.grid_id).jqGrid("setGridParam", {
                // 	onSelectRow: function (rowid, status, e) {
                // 		SubResetCSVStatus(rowid);
                // 	},
                // });
                //20240328 lujunxia del e
            } else {
                setTimeout(function () {
                    //該当するデータは登録されていません！
                    me.clsComFnc.FncMsgBox("W0008", "データ");
                }, 100);
            }
            $(".HDKOut4ZenGin.keiriSyoribiFrom").trigger("focus");
        };
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
        //20240328 lujunxia ins s
        //選択チェックボックスは廃止
        $(me.grid_id).jqGrid("hideCol", "cb");
        $(me.grid_id_selected).jqGrid("hideCol", "cb");
        //20240328 lujunxia ins e
        me.setTableSize();
        //20240328 lujunxia del s
        //$("#jqgh_HDKOut4ZenGin_table_cb").html("全<br />銀<br />協");
        //20240328 lujunxia del e
    };
    /*
	 '**********************************************************************
	 '処 理 名：「選択データ一覧」Grid変更時の同期処理
	 '関 数 名：SubResetCSVStatus
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    //20240328 lujunxia upd s
    ///SubResetCSVStatus = function (id) {
    me.SubResetCSVStatus = function () {
        // var lvTxtCount = $(".HDKOut4ZenGin.lvTxtCount").val().replace(/,/g, "");
        // var lvTxtKingakuSum = $(".HDKOut4ZenGin.lvTxtKingakuSum")
        // 	.val()
        // 	.replace(/,/g, "");
        // var rowData = $(me.grid_id).jqGrid("getRowData", id);
        // var decCount = 0;
        var decKingaku = 0;
        // if ($("#jqg_HDKOut4ZenGin_table_" + id).is(":checked")) {
        // 	decCount = parseInt(lvTxtCount) + 1;
        // 	decKingaku = parseInt(lvTxtKingakuSum) + parseInt(rowData["KINGAKU"]);
        // } else {
        // 	decCount = lvTxtCount - 1;
        // 	decKingaku = lvTxtKingakuSum - rowData["KINGAKU"];
        // }
        var allData = $(me.grid_id_selected).jqGrid("getRowData");
        var decCount = allData.length;
        for (var i = 0; i < allData.length; i++) {
            decKingaku += parseInt(allData[i]["KINGAKU"]);
        }
        //20240328 lujunxia upd e
        // 出力対象件数
        $(".HDKOut4ZenGin.lvTxtCount").val(
            decCount.toString().replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
        );
        // 出力対象金額合計
        $(".HDKOut4ZenGin.lvTxtKingakuSum").val(
            decKingaku.toString().replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
        );
    };
    /*
	 '**********************************************************************
	 '処 理 名：CSV出力ボタンクリック
	 '関 数 名：btnCsvOut_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnCsvOut_Click = function () {
        var lvTxtKeiriSyoribi = $.trim(
            $(".HDKOut4ZenGin.lvTxtKeiriSyoribi").val()
        );
        var url = me.sys_id + "/" + me.id + "/" + "btnCsvOut_Click";
        var jqgridArr = new Array();
        var rowdata = "";
        //20240328 lujunxia upd s
        //var ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var ids = $(me.grid_id_selected).jqGrid("getDataIDs");
        //20240328 lujunxia upd e
        for (var i = 0; i < ids.length; i++) {
            //20240328 lujunxia upd s
            //rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            rowdata = $(me.grid_id_selected).jqGrid("getRowData", ids[i]);
            //20240328 lujunxia upd e
            jqgridArr.push({
                strSyohyoNo: rowdata["SYOHYO_NO"],
                strEdaNo: rowdata["EDA_NO"],
                SYOHYO_NO_VIEW: rowdata["SYOHYO_NO_VIEW"],
                rowId: ids[i],
                //読取書類
                SYOHYO_KBN: rowdata["SYOHYO_KBN"],
            });
        }
        var data = {
            //出力グループ名
            lvTxtGroupName: $.trim($(".HDKOut4ZenGin.lvTxtGroupName").val()),
            // 経理処理日
            lvTxtKeiriSyoribi: lvTxtKeiriSyoribi,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
            lvGvList: jqgridArr,
            //出力対象金額合計
            lvTxtKingakuSum: $(".HDKOut4ZenGin.lvTxtKingakuSum")
                .val()
                .replace(/,/g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["data"]["tranStartFlg"] == false) {
                me.HDKOut4ZenGin_CSV_TYPE = "";
            }
            if (!result["result"]) {
                //表示できる部署が存在しません。管理者にお問い合わせください。
                if (result["data"]["msg"]) {
                    me.clsComFnc.FncMsgBox(
                        result["data"]["msg"],
                        result["error"]
                    );
                    return;
                }
                if (result["data"]["type"] == "FncChkAndSetShiwakeInfo") {
                    //FncChkAndSetShiwakeInfo:mode =2
                    if (result["data"]["errorMsg"]) {
                        if (result["data"]["chooseData"]) {
                            result["data"]["chooseData"].forEach(function (
                                item
                            ) {
                                if (
                                    item["chgColor"] &&
                                    item["chgColor"] == "1"
                                ) {
                                    //20240328 lujunxia upd s
                                    //$("#HDKOut4ZenGin_table #" + item["rowNum"]).css(
                                    $(
                                        "#HDKOut4ZenGin_table_selected tr.jqgrow#" +
                                            item["rowNum"] +
                                            " td"
                                    ).css("background", "rgb(160,102,51)");
                                    //20240328 lujunxia upd e
                                }
                            });
                        }
                        me.clsComFnc.ObjSelect = $(
                            ".HDKOut4ZenGin.keiriSyoribiFrom"
                        );
                        me.clsComFnc.FncMsgBox(
                            result["error"],
                            result["data"]["errorMsg"]
                        );
                    } else {
                        me.clsComFnc.ObjSelect = $(
                            ".HDKOut4ZenGin.keiriSyoribiFrom"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                } else {
                    if (result["error"] == "W0001") {
                        me.clsComFnc.FncMsgBox("W0001", "出力先");
                    } else if (result["error"] == "W0015") {
                        me.clsComFnc.FncMsgBox(result["error"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                }
            } else {
                //「検索結果一覧」テーブルはリロード
                me.cmdEventEnter_Click();
                //20240328 lujunxia ins s
                //「選択データ一覧」テーブルはクリアする
                $(me.grid_id_selected).jqGrid("clearGridData");
                // 出力対象件数
                $(".HDKOut4ZenGin.lvTxtCount").val(0);
                // 出力対象金額合計
                $(".HDKOut4ZenGin.lvTxtKingakuSum").val(0);
                //20240328 lujunxia ins e

                var link = document.createElement("a");
                link.style.display = "none";
                link.href = result["data"]["url"];
                link.setAttribute("download", "");
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        me.ajax.send(url, data, 0);
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
            case "btnLKamokuSearch":
            case "btnRKamokuSearch":
                //科目検索
                dialogId = "HDKKamokuSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnLKamokuSearch"
                        ? $(".HDKOut4ZenGin.lKamokuInput")
                        : $(".HDKOut4ZenGin.rKamokuInput");
                $txtSearchNM =
                    searchButton == "btnLKamokuSearch"
                        ? $(".HDKOut4ZenGin.lKamokuLabel")
                        : $(".HDKOut4ZenGin.rKamokuLabel");
                divCD = "KamokuCD";
                divNM = "KamokuNM";
                frmId = "HDKKamokuSearch";
                title = "科目マスタ検索";
                break;
            case "btnBusyoSearch":
                dialogId = "HDKCreatBusyoSearchDialogDiv";
                $txtSearchCD = $(".HDKOut4ZenGin.busyoInput");
                $txtSearchNM = $(".HDKOut4ZenGin.busyoLabel");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HDKCreatBusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnTanntousyaSearch":
                //作成担当者
                dialogId = "HDKSyainSearchDialogDiv";
                $txtSearchCD = $(".HDKOut4ZenGin.tanntousyaInput");
                $txtSearchNM = $(".HDKOut4ZenGin.tanntousyaLabel");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HDKSyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HDKOut4ZenGin.HDKAIKEI-content");

        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }

        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", koumkuCd).insertAfter($rootDiv).hide();

        if (searchButton == "btnTanntousyaSearch") {
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
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: 630,
            width: searchButton == "btnBusyoSearch" ? 500 : 720,
            resizable: false,
            close: function () {
                if ($RtnCD.html() == 1) {
                    if ($SearchCD.html() != "") {
                        $txtSearchCD.val($SearchCD.html());
                    }
                    if ($SearchNM.html() != "") {
                        $txtSearchNM.text($SearchNM.html());
                    }
                }

                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();
                $koumkuCd.remove();
                $("#" + dialogId).remove();
                if (searchButton == "btnTanntousyaSearch") {
                    $syainSearch.remove();
                }
                setTimeout(function () {
                    $(".HDKOut4ZenGin." + searchButton).trigger("focus");
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

    me.fncBlur = function (thisValue, labelName) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        var alldata;
        var id;
        var name;
        if (labelName == "busyoLabel") {
            //部署
            alldata = me.busyoData;
            id = "BUSYO_CD";
            name = "BUSYO_NM";
        } else if (labelName == "tanntousyaLabel") {
            //作成担当者
            alldata = me.tanntousyaData;
            id = "SYAIN_NO";
            name = "SYAIN_NM";
        } else {
            //科目
            alldata = me.kamokuData;
            id = "KAMOK_CD";
            name = "KAMOK_NAME";
        }
        if (alldata) {
            var foundNM_array = alldata.filter(function (element) {
                return element[id] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKOut4ZenGin." + labelName).text(foundNM ? foundNM[name] : "");
    };
    //20240318 lujunxia ins s
    openEditShiwakeZenGin = function (event, id) {
        //ボタンをクリックする時、「行選択」を実行しない
        event.stopPropagation();
        //支払伝票のみ
        var frmId = "HDKShiharaiInput";
        var dialogdiv = "HDKShiharaiInputDialogDiv";

        var $rootDiv = $(".HDKOut4ZenGin.HDKAIKEI-content");

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
        $DISP_NO.html("ReOut4ZenGin");
        $SYOHY_NO.html(id);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                $MODE.remove();
                $DISP_NO.remove();
                $SYOHY_NO.remove();
                $("#" + dialogdiv).remove();
                //データをreload
                me.cmdEventEnter_Click();
            }
            $("#" + dialogdiv).append(result);
            o_HDKAIKEI_HDKAIKEI.HDKOut4ZenGin.HDKShiharaiInput.before_close =
                before_close;
        };
    };
    //20240318 lujunxia ins e
    //20240328 lujunxia ins s
    /*
	 '**********************************************************************
	 '処 理 名：「↑」ボタンクリック
	 '関 数 名：noselectBtnClick
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：「↑」ボタンクリック
	 '**********************************************************************
	 */
    me.noselectBtnClick = function () {
        var selIds = $(me.grid_id_selected).jqGrid("getGridParam", "selarrrow");
        var selDatalength = selIds.length;
        if (selDatalength == 0) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return;
        }
        //前に選択したデータは【選択しない】に設定する
        var preSelIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var preSelIdsLength = preSelIds.length;
        if (preSelIdsLength > 0) {
            for (var i = 0; i < preSelIdsLength; i++) {
                $(me.grid_id).jqGrid("setSelection", preSelIds[0], false);
            }
        }
        //すべての行のID配列を取得
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //現在の最大行番号（データ番号）を取得する
            rowid = parseInt(ids.pop()) + 1;
        }
        for (var i = 0; i < selDatalength; i++) {
            var rowdata = $(me.grid_id_selected).jqGrid(
                "getRowData",
                selIds[i]
            );
            $(me.grid_id).jqGrid("addRowData", rowid, rowdata);
            $(me.grid_id).jqGrid("setSelection", rowid, true);
            rowid++;
        }
        for (var i = 0; i < selDatalength; i++) {
            $(me.grid_id_selected).jqGrid("delRowData", selIds[0]);
        }
        //「選択データ一覧」テーブルに縦スクロールバーが出てきたときに幅も変わるために
        me.setTableSize();
        //スクロールバーの位置を一番下に変更する
        var scrollHeight = $(me.grid_id).prop("scrollHeight");
        $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(scrollHeight);
        me.SubResetCSVStatus();
    };
    /*
	 '**********************************************************************
	 '処 理 名：「↓」ボタンクリック
	 '関 数 名：selectBtnClick
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：「↓」ボタンクリック
	 '**********************************************************************
	 */
    me.selectBtnClick = function () {
        var selIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var selDatalength = selIds.length;
        if (selDatalength == 0) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return;
        }
        //前に選択したデータは【選択しない】に設定する
        var preSelIds = $(me.grid_id_selected).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        var preSelIdsLength = preSelIds.length;
        if (preSelIdsLength > 0) {
            for (var i = 0; i < preSelIdsLength; i++) {
                $(me.grid_id_selected).jqGrid(
                    "setSelection",
                    preSelIds[0],
                    false
                );
            }
        }
        //すべての行のID配列を取得
        var ids = $(me.grid_id_selected).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //現在の最大行番号（データ番号）を取得する
            rowid = parseInt(ids.pop()) + 1;
        }
        for (var i = 0; i < selDatalength; i++) {
            var rowdata = $(me.grid_id).jqGrid("getRowData", selIds[i]);
            $(me.grid_id_selected).jqGrid("addRowData", rowid, rowdata);
            $(me.grid_id_selected).jqGrid("setSelection", rowid, true);
            rowid++;
        }
        for (var i = 0; i < selDatalength; i++) {
            $(me.grid_id).jqGrid("delRowData", selIds[0]);
        }
        //「選択データ一覧」テーブルに縦スクロールバーが出てきたときに幅も変わるために
        me.setTableSize();
        //スクロールバーの位置を一番下に変更する
        var scrollHeight = $(me.grid_id_selected).prop("scrollHeight");
        $(me.grid_id_selected)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop(scrollHeight);
        me.SubResetCSVStatus();
    };
    //20240328 lujunxia ins e
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_HDKAIKEI_HDKOut4ZenGin = new HDKAIKEI.HDKOut4ZenGin();
    o_HDKAIKEI_HDKOut4ZenGin.load();
    //20240318 lujunxia ins s
    o_HDKAIKEI_HDKAIKEI.HDKOut4ZenGin = o_HDKAIKEI_HDKOut4ZenGin;
    //20240318 lujunxia ins e
});
