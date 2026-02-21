/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("HMTVE.HMTVE370MLOGINList");

HMTVE.HMTVE370MLOGINList = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE370MLOGINList";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE370MLOGINList_tblMain";
    me.pager = "#HMTVE370MLOGINList_pager";
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
            name: "SYAIN_NO",
            label: "ユーザＩＤ",
            index: "SYAIN_NO",
            width: 68,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 250,
            align: "left",
            sortable: false,
        },
        {
            name: "PATTERN_NM",
            label: "パターン名",
            index: "PATTERN_NM",
            width: 200,
            align: "left",
            sortable: false,
        },
        {
            name: "FLG",
            label: "未／済",
            index: "FLG",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "",
            label: "",
            index: "btnEdit",
            width: 80,
            align: "center",
            formatter: function (_cellvalue, options) {
                var detail =
                    '<button id="btnEdit" class="HMTVE370MLOGINList btnEdit Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">修正</button>';
                detail = detail.replace(
                    "<button",
                    `<button onclick="openPage.call(this, '${options.rowId}')"`
                );
                return detail;
            },
        },
        {
            name: "",
            label: "",
            index: "btnDel",
            width: 80,
            align: "center",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    '<button id="btnDel" class="HMTVE370MLOGINList btnDel Tab Enter" style="border: 1px solid #77d5f7;background: #16b1e9;width: 100%;">削除</button>';
                detail = detail.replace(
                    "<button",
                    `<button onclick="DeleteDataByCD.call(this, '${rowObject["SYAIN_NO"]}')"`
                );
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE370MLOGINList.btnSearch",
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

    // 検索ボタン
    $(".HMTVE370MLOGINList.btnSearch").click(function () {
        me.btnSearch_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        //プロシージャ:画面初期化
        me.Page_Load();
    };

    /*
	 '************************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ページ初期化
	 '************************************************************************
	 */
    me.Page_Load = function () {
        //ﾛｸﾞｲﾝ情報ﾃｰﾌﾞﾙを非表示にする
        $(".HMTVE370MLOGINList.pnlList").hide();
        //項目をクリアする
        $(".HMTVE370MLOGINList.txtUserID").val("");
        $(".HMTVE370MLOGINList.txtSyaYin").val("");
        $(".HMTVE370MLOGINList.txtUserID").trigger("focus");

        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 770);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 268);
        $(me.grid_id).jqGrid("bindKeys");
    };
    /*
	 '************************************************************************
	 '処 理 名：検索ボタンのイベント
	 '関 数 名：btnSearch_Click
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ﾛｸﾞｲﾝ情報の検索処理
	 '************************************************************************
	 */
    me.btnSearch_Click = function () {
        //グリッドビューにデータをバインドする
        $(".HMTVE370MLOGINList.pnlList").hide();
        me.BindGridViewData(1);
    };
    /*
	 '************************************************************************
	 '処 理 名：データ削除のイベント
	 '関 数 名：DeleteDataByCD
	 '引    数：rowId 行番号
	 '戻 り 値 ：なし
	 '処理説明 ：指定した社員のデータを削除する
	 '************************************************************************
	 */
    DeleteDataByCD = function (rowId) {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            if (rowId == null || rowId == undefined || rowId == "") {
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            //データバインドする
            me.delurl = me.sys_id + "/" + me.id + "/deleteDataByCD";
            me.deldata = {
                SYAINCD: rowId,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    if (result["data"] && result["data"] != "") {
                        //該当データはありません。
                        me.clsComFnc.FncMsgBox(result["data"]);
                    } else {
                        //削除が完了しました。
                        me.clsComFnc.FncMsgBox("I0017");
                    }
                    me.nowSelId = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "selrow"
                    );
                    //グリッドビューにデータをバインドする
                    $(".HMTVE370MLOGINList.pnlList").hide();
                    me.BindGridViewData("");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            };
            me.ajax.send(me.delurl, me.deldata, 0);
        };
        me.clsComFnc.FncMsgBox(
            "QY999",
            "ﾛｸﾞｲﾝ情報を削除します。よろしいですか？"
        );
    };
    /*
	 '************************************************************************
	 '処 理 名：ログイン登録画面を弾む
	 '関 数 名：openPage
	 '引    数：rowId 行番号
	 '戻 り 値 ：なし
	 '処理説明 ：ログイン登録画面を弾む
	 '************************************************************************
	 */
    openPage = function (rowId) {
        var frmId = "HMTVE380MLOGINEntry";
        var dialogdiv = "HMTVE370MLOGINListDialogDiv";
        var $rootDiv = $(".HMTVE370MLOGINList.HMTVE-content");

        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "RtnCD")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "userid")
            .insertAfter($rootDiv);

        var $userid = $rootDiv.parent().find("#userid");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
        $userid.html(rowData["SYAIN_NO"]);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                var $RtnCD = $rootDiv.parent().find("#RtnCD");
                if ($RtnCD.html() == 1) {
                    me.nowSelId = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "selrow"
                    );
                    //データバインドする
                    me.BindGridViewData("");
                }
                $RtnCD.remove();
                $userid.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE370MLOGINList.HMTVE380MLOGINEntry.before_close =
                before_close;
        };
    };
    /*
	 '************************************************************************
	 '処 理 名：データバインドする
	 '関 数 名：BindGridViewData
	 '引    数：showPageNum:どのページ
	 '戻 り 値 ：なし
	 '処理説明 ：データバインドする
	 '************************************************************************
	 */
    me.BindGridViewData = function (showPageNum) {
        var txtUserID = $(".HMTVE370MLOGINList.txtUserID").val();
        var txtSyaYin = $(".HMTVE370MLOGINList.txtSyaYin").val();
        var data = {
            txtUserID: txtUserID,
            txtSyaYin: txtSyaYin,
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
                $(".HMTVE370MLOGINList.pnlList").show();
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
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE370MLOGINList = new HMTVE.HMTVE370MLOGINList();
    o_HMTVE_HMTVE370MLOGINList.load();
    o_HMTVE_HMTVE.HMTVE370MLOGINList = o_HMTVE_HMTVE370MLOGINList;
});
