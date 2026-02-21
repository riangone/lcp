/**
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付					   Feature/Bug						         内容											                         担当
 * YYYYMMDD				    #ID									    XXXXXX											                          GSDL
 * 20240426		    バーコード読取・CSV出力		   グリッドの高さ・幅が ウインドウのサイズに追従する		   lujunxia
 * 20240507		    バーコード読取・CSV出力		   検索されたデータをgridに追加されるの変更		   　　　　　lujunxia
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS104BarCodeReadOut");

HMDPS.HMDPS104BarCodeReadOut = function () {
    var me = new gdmz.base.panel();
    me.ajax = new gdmz.common.ajax();
    me.MessageBox = new gdmz.common.MessageBox();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.hmdps = new HMDPS.HMDPS();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "HMDPS104BarCodeReadOut";
    me.sys_id = "HMDPS";
    me.grid_id = "#HMDPS104BarCodeReadOut_table";
    me.g_url = me.sys_id + "/" + me.id + "/FncSetData";
    me.retCSVFLG = "";
    me.HMDPS104_CSV_TYPE = "";
    //20240507 lujunxia ins s
    me.tableData = [];
    //20240507 lujunxia ins e
    me.ratio = window.devicePixelRatio || 1;
    //20240507 lujunxia del s
    // me.option = {
    // 	rowNum: 0,
    // 	caption: "",
    // 	rownumbers: false,
    // 	loadui: "disable",
    // 	multiselect: false,
    // 	//20240426 lujunxia ins s
    // 	//列幅を自動幅に設定する
    // 	shrinkToFit: true,
    // 	//20240426 lujunxia ins e
    // };
    //20240507 lujunxia del e
    me.colModel = [
        {
            name: "CHK_CSV_STATUS",
            label: "Ｃ<br />Ｓ<br />Ｖ",
            index: "CHK_CSV_STATUS",
            align: "center",
            sortable: false,
            width: 28,
            formatter: function (_cellValue, options, _rowObject) {
                return (
                    "<input type='checkbox' class='" +
                    options.rowId +
                    "_HMDPS104BarCodeReadOut_CHK_CSV_STATUS CHK_CSV_STATUS_CHECK' checked='checked' onclick='SubResetCSVStatus(" +
                    options.rowId +
                    ")'/>"
                );
            },
        },
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
            width: 160,
        },
        {
            name: "KARIKATA",
            label: "借方科目",
            index: "KARIKATA",
            sortable: false,
            align: "left",
            width: 200,
        },
        {
            name: "KASHIKATA",
            label: "貸方科目",
            index: "KASHIKATA",
            sortable: false,
            align: "left",
            width: 200,
        },
        {
            name: "KINGAKU",
            label: "金額",
            index: "KINGAKU",
            align: "right",
            sortable: false,
            width: 132,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "CHK_HUKANZEN_STATUS",
            label: "不完全",
            index: "CHK_HUKANZEN_STATUS",
            align: "center",
            width: 28,
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: true,
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
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMDPS104BarCodeReadOut.btnCsvOut",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmdps.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmdps.TabKeyDown();

    //Enterキーのバインド
    me.hmdps.EnterKeyDown();
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // CSV出力ボタンクリック
    $(".HMDPS104BarCodeReadOut.btnCsvOut").click(function () {
        //出力対象のチェック
        me.FncChkInput_CSVOUT();
    });
    // Enterキーを押す
    $(".HMDPS104BarCodeReadOut.txtSyohyoNo").on("keydown", function (e) {
        var key = e.which;
        if (key == 13) {
            me.cmdEventEnter_Click();
        } else {
            //読取書類
            $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val("");
        }
    });
    // 出力対象金額合計blur
    $(".HMDPS104BarCodeReadOut.lvTxtKingakuSum").blur(function (e) {
        var txtValue = $.trim($(e.target).val()).replace(/\b(0+)/gi, "");
        $(e.target).val(txtValue.replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,"));
        if ($(e.target).val() == "" || $(e.target).val() == "-") {
            $(e.target).val(0);
        }
    });
    //20240426 lujunxia ins s
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    //左メニューサイズ変更時にグリッドの大きさも追従
    var index = 1;
    var ele = document.querySelector(".HMDPS104BarCodeReadOut.HMDPS-content");
    var resizeObserver = new ResizeObserver(function () {
        if (index != 1) {
            me.setTableSize();
        }
        // 20241226 caina upd s
        // setTimeout(() => {
        setTimeout(function () {
            // 20241226 caina upd e
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
        //20240507 lujunxia upd s
        // gdmz.common.jqgrid.init(
        // 	me.grid_id,
        // 	me.g_url,
        // 	me.colModel,
        // 	"",
        // 	"",
        // 	me.option
        // );
        $(me.grid_id).jqGrid({
            datatype: "local",
            emptyRecordRow: false,
            rowNum: 0,
            caption: "",
            rownumbers: false,
            loadui: "disable",
            multiselect: false,
            //列幅を自動幅に設定する
            shrinkToFit: true,
            colModel: me.colModel,
        });
        //20240507 lujunxia upd e
        //20240426 lujunxia upd s
        // var widthTotal = $(".ui-widget-content.HMDPS.HMDPS-layout-center").width();
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, (widthTotal * 3) / 5);
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 360);
        me.setTableSize();
        //20240426 lujunxia upd e
        me.SubCtrlInit();
        $(me.grid_id).jqGrid("bindKeys");
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
        //証憑№
        $(".HMDPS104BarCodeReadOut.txtSyohyoNo").val("");
        //読取書類
        $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val("");
        //経理処理日
        $(".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi").val("");
        //出力グループ名
        $(".HMDPS104BarCodeReadOut.lvTxtGroupName").val(""),
            // 出力対象件数
            $(".HMDPS104BarCodeReadOut.lvTxtCount").val(0);
        // 出力対象金額合計
        $(".HMDPS104BarCodeReadOut.lvTxtKingakuSum").val(0);
        $(".HMDPS104BarCodeReadOut.txtSyohyoNo").trigger("focus");
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
        var lvTxtKeiriSyoribi = $(".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi");
        var valDate = lvTxtKeiriSyoribi.val();
        if ($.trim(valDate) == "") {
            me.clsComFnc.ObjFocus = lvTxtKeiriSyoribi;
            me.clsComFnc.FncMsgBox("W9999", "経理処理日が未入力です！");
            return;
        }
        if (valDate.length == 8 && valDate.indexOf("/") == -1) {
            $(".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi").val(
                valDate.substring(0, 4) +
                    "/" +
                    valDate.substring(4, 6) +
                    "/" +
                    valDate.substring(6, 8)
            );
        }
        if (me.clsComFnc.CheckDate(lvTxtKeiriSyoribi) == false) {
            $(".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi").val(valDate);
            me.clsComFnc.ObjFocus = lvTxtKeiriSyoribi;
            me.clsComFnc.FncMsgBox("W9999", "経理処理日の入力形式が不正です！");
            return;
        }
        // 出力グループ名
        var lvTxtGroupName = $(".HMDPS104BarCodeReadOut.lvTxtGroupName");
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
        var check_flag = $(".CHK_CSV_STATUS_CHECK").is(":checked");
        if ($(".HMDPS104BarCodeReadOut.lvTxtCount").val() == 0 || !check_flag) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ＣＳＶ出力の対象が選択されていません！"
            );
            return;
        }
        //出力グループ名の重複チェック
        var url = me.sys_id + "/" + me.id + "/" + "FncChkExistGroupNM";
        var data = {
            //出力グループ名
            lvTxtGroupName: $.trim(
                $(".HMDPS104BarCodeReadOut.lvTxtGroupName").val()
            ),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                if (result["error"] == "repeatErr") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に同一のグループ名が登録されています！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnCsvOut_Click;
                //CSVを出力します。よろしいですか？
                me.clsComFnc.FncMsgBox("QY014");
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
        //20240507 lujunxia del s
        //$(me.grid_id).jqGrid("clearGridData");
        // 出力対象件数
        //$(".HMDPS104BarCodeReadOut.lvTxtCount").val(0);
        // 出力対象金額合計
        //$(".HMDPS104BarCodeReadOut.lvTxtKingakuSum").val(0);
        //20240507 lujunxia del e
        // 読取書類
        $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val("");
        var txtSyohyoNo = $(".HMDPS104BarCodeReadOut.txtSyohyoNo");

        if ($.trim(txtSyohyoNo.val()) == "") {
            me.clsComFnc.ObjFocus = txtSyohyoNo;
            me.clsComFnc.FncMsgBox("W9999", "証憑№が未入力です！");
            return false;
        }
        if (txtSyohyoNo.val().length != 17) {
            me.clsComFnc.ObjSelect = txtSyohyoNo;
            me.clsComFnc.FncMsgBox("W9999", "証憑№が不正です！");
            return false;
        }
        //証憑№のチェック,Gridへのデータセット
        me.FncChkAndSetShiwakeInfo($.trim(txtSyohyoNo.val()), 1, "");
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
        var txtSyohyoNo = $(".HMDPS104BarCodeReadOut.txtSyohyoNo").val();
        var data = {
            strSyohyoNo: $.trim(txtSyohyoNo),
        };
        //20240507 lujunxia upd s
        //var complete_fun = function (returnFLG, result) {
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //if (result["error"]) {
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var returnData = result["data"];
            //if (returnFLG != "nodata") {
            if (returnData.length > 0) {
                me.HMDPS104_CSV_TYPE = me.retCSVFLG;
                //var records = $(me.grid_id).jqGrid("getGridParam", "records");
                // 20241226 caina upd s
                // var noArr = me.tableData.map((item) => item.SYOHYO_NO);
                var noArr = [];
                for (var i = 0; i < me.tableData.length; i++) {
                    noArr.push(me.tableData[i].SYOHYO_NO);
                }
                // 20241226 caina upd e
                var dataPosition = noArr.indexOf(returnData[0]["SYOHYO_NO"]);
                if (dataPosition > -1) {
                    me.tableData.splice(dataPosition, 1);
                }
                //データがテーブルに最上を追加する
                me.tableData.unshift(returnData[0]);
                $(me.grid_id).jqGrid("clearGridData");
                for (var i = 0; i < me.tableData.length; i++) {
                    $(me.grid_id).jqGrid("addRowData", i, me.tableData[i]);
                }
                //出力対象件数,出力対象金額合計のセット
                //if (records) {
                var decCount = 0;
                var decKingaku = 0;
                var rowdata = "";
                var ids = $(me.grid_id).jqGrid("getDataIDs");
                for (var i = 0; i < ids.length; i++) {
                    rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                    if (
                        $(
                            "." +
                                ids[i] +
                                "_HMDPS104BarCodeReadOut_CHK_CSV_STATUS"
                        ).is(":checked")
                    ) {
                        decCount += 1;
                        decKingaku += parseInt(rowdata["KINGAKU"]);
                    }
                }
                // 出力対象件数
                $(".HMDPS104BarCodeReadOut.lvTxtCount").val(
                    decCount
                        .toString()
                        .replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
                );
                // 出力対象金額合計
                $(".HMDPS104BarCodeReadOut.lvTxtKingakuSum").val(
                    decKingaku
                        .toString()
                        .replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
                );
                //}
            }
            $(".HMDPS104BarCodeReadOut.txtSyohyoNo").trigger("focus");
            $(".HMDPS104BarCodeReadOut.txtSyohyoNo").select();
        };
        // gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        me.ajax.send(me.g_url, data, 0);
        //20240507 lujunxia upd e
    };
    /*
     '**********************************************************************
     '処 理 名：読み取りデータのチェックと読取書類ラベルへのセット
     '関 数 名：FncChkAndSetShiwakeInfo
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FncChkAndSetShiwakeInfo = function (
        strSyohyoNo,
        Mode,
        retCSVFLG,
        chgColor
    ) {
        var url = me.sys_id + "/" + me.id + "/" + "FncChkAndSetShiwakeInfo";
        var data = {
            strSyohyoNo: strSyohyoNo,
            Mode: Mode ? Mode : 0,
            retCSVFLG: retCSVFLG ? retCSVFLG : "",
            chgColor: chgColor ? chgColor : "0",
            HMDPS104_CSV_TYPE: me.HMDPS104_CSV_TYPE,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                if (result["data"]["errorMsg"]) {
                    me.clsComFnc.ObjSelect = $(
                        ".HMDPS104BarCodeReadOut.txtSyohyoNo"
                    );
                    me.clsComFnc.FncMsgBox("W9999", result["data"]["errorMsg"]);
                    //読取書類
                    $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val(
                        result["data"]["lvTxtYomitoriSyorui"]
                            ? result["data"]["lvTxtYomitoriSyorui"]
                            : ""
                    );
                } else {
                    me.clsComFnc.ObjSelect = $(
                        ".HMDPS104BarCodeReadOut.txtSyohyoNo"
                    );
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            } else {
                me.retCSVFLG = result["data"]["retCSVFLG"]
                    ? result["data"]["retCSVFLG"]
                    : "";
                me.FncSetData();
                //not show
                $(".HMDPS104BarCodeReadOut.lvTxtSyohyoNo").val("");

                //読取書類
                $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val(
                    result["data"]["lvTxtYomitoriSyorui"]
                        ? result["data"]["lvTxtYomitoriSyorui"]
                        : ""
                );
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：Gridのチェックボックス変更時の同期処理
     '関 数 名：SubResetCSVStatus
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    SubResetCSVStatus = function (id) {
        var lvTxtCount = $(".HMDPS104BarCodeReadOut.lvTxtCount")
            .val()
            .replace(/,/g, "");
        var lvTxtKingakuSum = $(".HMDPS104BarCodeReadOut.lvTxtKingakuSum")
            .val()
            .replace(/,/g, "");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        var decCount = 0;
        var decKingaku = 0;
        if (
            $("." + id + "_HMDPS104BarCodeReadOut_CHK_CSV_STATUS").is(
                ":checked"
            )
        ) {
            decCount = parseInt(lvTxtCount) + 1;
            decKingaku =
                parseInt(lvTxtKingakuSum) + parseInt(rowData["KINGAKU"]);
        } else {
            decCount = lvTxtCount - 1;
            decKingaku = lvTxtKingakuSum - rowData["KINGAKU"];
        }
        // 出力対象件数
        $(".HMDPS104BarCodeReadOut.lvTxtCount").val(
            decCount.toString().replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
        );
        // 出力対象金額合計
        $(".HMDPS104BarCodeReadOut.lvTxtKingakuSum").val(
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
            $(".HMDPS104BarCodeReadOut.lvTxtKeiriSyoribi").val()
        );
        var url = me.sys_id + "/" + me.id + "/" + "btnCsvOut_Click";
        var jqgridArr = new Array();
        var rowdata = "";
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            jqgridArr.push({
                strSyohyoNo: rowdata["SYOHYO_NO"],
                strEdaNo: rowdata["EDA_NO"],
                strIsCSVOut: $(
                    "." + ids[i] + "_HMDPS104BarCodeReadOut_CHK_CSV_STATUS"
                ).is(":checked")
                    ? "1"
                    : "",
                SYOHYO_NO_VIEW: rowdata["SYOHYO_NO_VIEW"],
            });
        }
        var data = {
            //出力グループ名
            lvTxtGroupName: $.trim(
                $(".HMDPS104BarCodeReadOut.lvTxtGroupName").val()
            ),
            // 経理処理日
            lvTxtKeiriSyoribi: lvTxtKeiriSyoribi,
            CONST_ADMIN_PTN_NO: me.hmdps.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.hmdps.CONST_HONBU_PTN_NO,
            lvGvList: jqgridArr,
        };
        me.ajax.receive = function (result) {
            //20240507 lujunxia ins s
            me.tableData = [];
            //20240507 lujunxia ins e
            var result = eval("(" + result + ")");
            if (result["data"]["tranStartFlg"] == false) {
                me.HMDPS104_CSV_TYPE = "";
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
                        if (
                            result["data"]["chgColor"] &&
                            result["data"]["chgColor"] == "1"
                        ) {
                            $(
                                "#HMDPS104BarCodeReadOut_table #" +
                                    result["data"]["rowNum"]
                            ).css("background", "rgb(160,102,51)");
                        }
                        me.clsComFnc.ObjSelect = $(
                            ".HMDPS104BarCodeReadOut.txtSyohyoNo"
                        );
                        me.clsComFnc.FncMsgBox(
                            result["error"],
                            result["data"]["errorMsg"]
                        );
                        //読取書類
                        $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val(
                            result["data"]["lvTxtYomitoriSyorui"]
                                ? result["data"]["lvTxtYomitoriSyorui"]
                                : ""
                        );
                    } else {
                        me.clsComFnc.ObjSelect = $(
                            ".HMDPS104BarCodeReadOut.txtSyohyoNo"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                } else {
                    //読取書類
                    $(".HMDPS104BarCodeReadOut.lvTxtYomitoriSyorui").val(
                        result["data"]["lvTxtYomitoriSyorui"]
                            ? result["data"]["lvTxtYomitoriSyorui"]
                            : ""
                    );
                    if (result["error"] == "W0001") {
                        me.clsComFnc.FncMsgBox("W0001", "出力先");
                    } else if (result["error"] == "W0015") {
                        me.clsComFnc.FncMsgBox(result["error"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                }
            } else {
                //画面の初期化
                me.SubCtrlInit();

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
    //20240426 lujunxia ins s
    //グリッドの高さ・幅が ウインドウのサイズに追従する
    me.setTableSize = function () {
        var pageHeight = $(".HMDPS.HMDPS-layout-center").height();
        gdmz.common.jqgrid.set_grid_height(me.grid_id, pageHeight - 125);
        var pageWidth = $(".HMDPS.HMDPS-layout-center").width();
        gdmz.common.jqgrid.set_grid_width(me.grid_id, (pageWidth * 3) / 5);
    };
    //20240426 lujunxia ins e
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_HMDPS_HMDPS104BarCodeReadOut = new HMDPS.HMDPS104BarCodeReadOut();
    o_HMDPS_HMDPS104BarCodeReadOut.load();
});
