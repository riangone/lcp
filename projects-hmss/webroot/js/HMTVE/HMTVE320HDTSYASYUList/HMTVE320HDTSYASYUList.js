Namespace.register("HMTVE.HMTVE320HDTSYASYUList");

HMTVE.HMTVE320HDTSYASYUList = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE320HDTSYASYUList";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========
    me.grid_id = "#HMTVE320HDTSYASYUListMain";
    me.pager = "#HMTVE320HDTSYASYUList_pager";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearch_Click";
    //現在選択されているデータの行数
    me.nowSelId = "";

    me.option = {
        rowNum: 10,
        caption: "",
        rownumbers: false,
        multiselect: false,
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
            name: "SYASYU_CD",
            label: "車種コード",
            index: "SYASYU_CD",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SYASYU_NM",
            label: "車種名",
            index: "SYASYU_NM",
            width: 240,
            align: "left",
            sortable: false,
        },
        {
            name: "SYASYU_RYKNM",
            label: "車種略称名",
            index: "SYASYU_RYKNM",
            width: 210,
            align: "left",
            sortable: false,
        },
        {
            name: "SYASYU_KB",
            label: "車種区分",
            index: "SYASYU_KB",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "btnEdit",
            label: " ",
            index: "btnEdit",
            width: 50,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openEditWindow('" +
                    rowObject["SYASYU_CD"] +
                    "')\" id = '" +
                    i +
                    "_btnEdit' class=\"HMTVE320HDTSYASYUList btnEdit\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
                    "修正" +
                    "</button>";
                return detail;
            },
        },
        {
            name: "btnDel",
            label: " ",
            index: "btnDel",
            width: 50,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"OnClientClick('" +
                    rowObject["SYASYU_CD"] +
                    "')\" id = '" +
                    i +
                    "_btnDel' class=\"HMTVE320HDTSYASYUList btnDel\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>" +
                    "削除" +
                    "</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE320HDTSYASYUList.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMTVE320HDTSYASYUList.btnAdd",
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

    //処理説明：検索ボタン押下時
    $(".HMTVE320HDTSYASYUList.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //処理説明：追加ボタン押下時
    $(".HMTVE320HDTSYASYUList.btnAdd").click(function () {
        me.openAddWindow();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：init_control
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        try {
            //ﾛｸﾞｲﾝ情報ﾃｰﾌﾞﾙを非表示にする
            $(".HMTVE320HDTSYASYUList.pnlList").hide();

            //項目をクリアする
            //車種コード
            $(".HMTVE320HDTSYASYUList.txtNumber").val("");
            //車種名
            $(".HMTVE320HDTSYASYUList.txtName").val("");

            gdmz.common.jqgrid.init2(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                "",
                me.option
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 800);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 266);

            $(me.grid_id).jqGrid("bindKeys");

            $(".HMTVE320HDTSYASYUList.txtNumber").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    me.btnSearch_Click = function () {
        try {
            //グリッドビューにデータをバインドする
            $(".HMTVE320HDTSYASYUList.pnlList").hide();

            me.BindGridViewData(1);
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：データバインドのイベント
    //関 数 名：BindGridViewData
    //引 数 　：showPageNum:どのページ
    //戻 り 値：なし
    //処理説明：指定した社員のデータを削除する
    //**********************************************************************
    me.BindGridViewData = function (showPageNum) {
        try {
            var txtNumber = $(".HMTVE320HDTSYASYUList.txtNumber").val();
            var txtName = $(".HMTVE320HDTSYASYUList.txtName").val();
            var data = {
                txtNumber: txtNumber,
                txtName: txtName,
            };
            var complete_fun = function (_returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var objDR = $(me.grid_id).jqGrid("getRowData");
                if (objDR.length == 0) {
                    //該当データはありません。
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    $(".HMTVE320HDTSYASYUList.pnlList").show();
                    //修正
                    if (me.nowSelId) {
                        $(me.grid_id).jqGrid("setSelection", me.nowSelId);
                        me.nowSelId = "";
                    } else {
                        if (result["page"] == "1") {
                            //１行目を選択状態にする
                            $(me.grid_id).jqGrid("setSelection", "0");
                        } else {
                            //ページをめくる後,１行目を選択状態にする
                            var selRow =
                                $(".ui-pg-selbox").val() * (result["page"] - 1);
                            $(me.grid_id).jqGrid("setSelection", selRow);
                        }
                    }
                    // フォーカスの設定
                    $(me.grid_id).trigger("focus");
                }
            };
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                complete_fun,
                showPageNum
            );
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：追加ボタンのイベント
    //関 数 名：openAddWindow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    me.openAddWindow = function () {
        try {
            var frmId = "HMTVE330HDTSYASYUEntry";
            var dialogdiv = "HMTVE330HDTSYASYUEntryDialogDiv";
            // var title = "車種マスタメンテナンス_入力";
            var $rootDiv = $(".HMTVE320HDTSYASYUList.HMTVE-content");

            //画面に文字が出たら消えます。
            $("<div style='display:none;'></div>")
                .attr("id", dialogdiv)
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .attr("id", "MODE")
                .insertAfter($rootDiv);

            var $MODE = $rootDiv.parent().find("#MODE");
            $MODE.html("1");

            var url = me.sys_id + "/" + frmId;
            me.ajax.send(url, "", 0);
            me.ajax.receive = function (result) {
                function before_close() {
                    $MODE.remove();
                    $("#" + dialogdiv).remove();
                    me.btnSearch_Click();
                }

                $("#" + dialogdiv).append(result);

                o_HMTVE_HMTVE.HMTVE320HDTSYASYUList.HMTVE330HDTSYASYUEntry.before_close =
                    before_close;
            };
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：修正ボタンのイベント
    //関 数 名：openEditWindow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    openEditWindow = function (id) {
        try {
            var frmId = "HMTVE330HDTSYASYUEntry";
            var dialogdiv = "HMTVE330HDTSYASYUEntryDialogDiv";
            // var title = "車種マスタメンテナンス_入力";
            var $rootDiv = $(".HMTVE320HDTSYASYUList.HMTVE-content");

            //画面に文字が出たら消えます。
            $("<div style='display:none;'></div>")
                .attr("id", dialogdiv)
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .attr("id", "MODE")
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .attr("id", "SYASYU_CD")
                .insertAfter($rootDiv);

            var $MODE = $rootDiv.parent().find("#MODE");
            var $SYASYU_CD = $rootDiv.parent().find("#SYASYU_CD");

            $MODE.html("2");
            $SYASYU_CD.html(id);

            var url = me.sys_id + "/" + frmId;
            me.ajax.send(url, "", 0);
            me.ajax.receive = function (result) {
                function before_close() {
                    $MODE.remove();
                    $SYASYU_CD.remove();
                    $("#" + dialogdiv).remove();
                    me.nowSelId = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "selrow"
                    );
                    //グリッドビューにデータをバインドする
                    $(".HMTVE320HDTSYASYUList.pnlList").hide();
                    me.BindGridViewData("");
                }
                $("#" + dialogdiv).append(result);

                o_HMTVE_HMTVE.HMTVE320HDTSYASYUList.HMTVE330HDTSYASYUEntry.before_close =
                    before_close;
            };
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：削除ボタンのイベント
    //関 数 名：OnClientClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    OnClientClick = function (SYASYU_CD) {
        try {
            me.DeleteDataByCD(SYASYU_CD);
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：データ削除のイベント
    //関 数 名：DeleteDataByCD
    //引   数：無し
    //戻 り 値：なし
    //処理説明：指定した車種のデータを削除する
    //**********************************************************************
    me.DeleteDataByCD = function (SYASYU_CD) {
        try {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                if (
                    SYASYU_CD == null ||
                    SYASYU_CD == undefined ||
                    SYASYU_CD == ""
                ) {
                    //該当データはありません。
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                }
                me.delurl = me.sys_id + "/" + me.id + "/deleteDataByCD";
                me.deldata = {
                    syasyuCD: SYASYU_CD,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"]) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            me.btnSearch_Click();
                        };
                        if (result["number_of_rows"] == 0) {
                            //削除失敗!!
                            me.clsComFnc.FncMsgBox("E9999", "削除失敗!!");
                        } else {
                            //削除が完了しました。
                            me.clsComFnc.FncMsgBox("I0017");
                        }
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                };
                me.ajax.send(me.delurl, me.deldata, 0);
            };
            me.clsComFnc.FncMsgBox(
                "QY999",
                "車種マスタデータを削除します。よろしいですか？"
            );
        } catch (ex) {
            console.log(ex);
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};
$(function () {
    var o_HMTVE_HMTVE320HDTSYASYUList = new HMTVE.HMTVE320HDTSYASYUList();
    o_HMTVE_HMTVE320HDTSYASYUList.load();
    o_HMTVE_HMTVE.HMTVE320HDTSYASYUList = o_HMTVE_HMTVE320HDTSYASYUList;
});
