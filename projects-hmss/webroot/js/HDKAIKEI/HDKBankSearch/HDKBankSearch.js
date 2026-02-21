/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               GSDL
 * -------------------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKBankSearch");

HDKAIKEI.HDKBankSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.id = "HDKBankSearch";
    me.ajax = new gdmz.common.ajax();
    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKBankSearch_sprItyp";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnHyouji_Click";

    me.option = {
        rowNum: 100,
        rownumbers: true,
        multiselect: false,
        caption: "",
        scroll: 30,
    };

    me.colModel = [
        {
            name: "BANK_CD",
            label: "金融機関コード",
            index: "BANK_CD",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "BANK_NM",
            label: "金融機関名",
            index: "BANK_NM",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "BRANCH_CD",
            label: "支店コード",
            index: "BRANCH_CD",
            width: 110,
            align: "left",
            sortable: false,
        },
        {
            name: "BRANCH_NM",
            label: "支店名",
            index: "BRANCH_NM",
            width: 180,
            align: "left",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HDKBankSearch.btnView",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HDKBankSearch.btnSelect",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKBankSearch.btnClose",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $(".HDKBankSearch.btnView").click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HDKBankSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $(".HDKBankSearch.btnClose").click(function () {
        $("#HDKBankSearchDialogDiv").dialog("close");
    });

    $(".HDKBankSearch.txtBankCode").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HDKBankSearch.txtBank").on("blur", function () {
        me.HDKAIKEI.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HDKBankSearch.txtBank").bind("keydown", function (e) {
        var key = e.which;
        if (key == 13 || key == 9) {
            e.preventDefault();

            $(".ui-dialog-buttons").find(".ui-button").trigger("focus");
        }
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
        me.HDKBankSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKBankSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKBankSearch_load = function () {
        if ($("#BankNM").length > 0) {
            var str = $("#BankNM").val();
            if (str) {
                $(".HDKBankSearch.txtBank.txtBankName").val(str);
            }
        }
        if ($("#BranchNM").length > 0) {
            var str = $("#BranchNM").val();
            if (str) {
                $(".HDKBankSearch.txtBank.txtBranchName").val(str);
            }
        }
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 653);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 300 : 312
        );
        $(".HDKBankSearch.btnSelect").hide();

        $("#HDKAIKEI_HDKBankSearch_sprItyp_rn").html("№");
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKBankSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId) {
                $(me.grid_id + " tr#" + rowId).bind("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKBankSearch.btnSelect").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKBankSearch.btnView").trigger("focus");
                    }
                });
            },
        });

        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKBankSearchDialogDiv").dialog("close");
            },
        });
        $("#RtnCD").html("-1");
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {
        //金融機関コード
        var txtBankCode = $.trim($(".HDKBankSearch.txtBankCode").val());
        //支店コード
        var txtBranchCode = $.trim($(".HDKBankSearch.txtBranchCode").val());
        //金融機関名
        var txtBankName = $.trim($(".HDKBankSearch.txtBankName").val());
        //辅助名
        var txtBranchName = $.trim($(".HDKBankSearch.txtBranchName").val());

        var data = {
            txtBankCode: txtBankCode,
            txtBranchCode: txtBranchCode,
            txtBankName: txtBankName,
            txtBranchName: txtBranchName,
        };

        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(".HDKBankSearch.txtBank.txtBankCode").trigger("focus");
            if (result["records"] > 0) {
                //選択ボタンが表示されます。
                $(".HDKBankSearch.btnSelect").show();
            } else {
                $(".HDKBankSearch.btnSelect").hide();
                me.clsComFnc.FncMsgBox("W0024");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：科目グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：科目グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#HDKBankSearchDialogDiv").dialog("close");
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return false;
        } else {
            var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);

            if (
                rowData &&
                $.trim(rowData["BANK_CD"]) != "" &&
                $.trim(rowData["BRANCH_CD"]) != ""
            ) {
                //リターン値
                $("#RtnCD").html("1");
                //---項目コード---
                $("#BankNM").html($.trim(rowData["BANK_NM"]));
                //---科目名---
                $("#BranchNM").html($.trim(rowData["BRANCH_NM"]));
            } else {
                return false;
            }
        }

        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKBankSearch = new HDKAIKEI.HDKBankSearch();
    o_HDKAIKEI_HDKBankSearch.load();
});
