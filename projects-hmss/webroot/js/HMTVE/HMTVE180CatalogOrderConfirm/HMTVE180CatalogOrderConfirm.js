/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE180CatalogOrderConfirm");

HMTVE.HMTVE180CatalogOrderConfirm = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();

    me.sys_id = "HMTVE";
    me.id = "HMTVE180CatalogOrderConfirm";
    me.grid_id1 = "#HMTVE180CatalogOrderConfirm_sprList1";
    me.grid_id2 = "#HMTVE180CatalogOrderConfirm_sprList2";
    me.grid_id3 = "#HMTVE180CatalogOrderConfirm_sprList3";

    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel1 = [
        {
            label: "発行年月",
            width: 77,
            align: "left",
            name: "HAKKO_YM",
            index: "HAKKO_YM",
            sortable: false,
        },
        {
            label: "コード",
            width: 58,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
        },
        {
            label: "本カタログ",
            width: 145,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
        },
        {
            label: "単価",
            width: 77,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 77,
            align: "right",
            name: "ORDER_NUM",
            index: "ORDER_NUM",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "合計",
            width: 78,
            align: "right",
            name: "GOUKEI",
            index: "GOUKEI",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
    ];
    me.colModel2 = [
        {
            label: "発行年月",
            width: 77,
            align: "left",
            name: "HAKKO_YM",
            index: "HAKKO_YM",
            sortable: false,
        },
        {
            label: "コード",
            width: 58,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
        },
        {
            label: "用品カタログ",
            width: 145,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
        },
        {
            label: "単価",
            width: 77,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 77,
            align: "right",
            name: "ORDER_NUM",
            index: "ORDER_NUM",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "合計",
            width: 78,
            align: "right",
            name: "GOUKEI",
            index: "GOUKEI",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
    ];
    me.colModel3 = [
        {
            label: "コード",
            width: 58,
            align: "left",
            name: "CATALOG_CD",
            index: "CATALOG_CD",
            sortable: false,
        },
        {
            label: "用品カタログ",
            width: 144,
            align: "left",
            name: "CATALOG_NM",
            index: "CATALOG_NM",
            sortable: false,
        },
        {
            label: "単価",
            width: 77,
            align: "right",
            name: "TANKA",
            index: "TANKA",
            sortable: false,
        },
        {
            label: "注文数",
            width: 77,
            align: "right",
            name: "ORDER_NUM",
            index: "ORDER_NUM",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "合計",
            width: 88,
            align: "right",
            name: "GOUKEI",
            index: "GOUKEI",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE180CatalogOrderConfirm.btnToInput",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE180CatalogOrderConfirm.btnConfirm",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HMTVE.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //入力画面に戻るボタンクリック
    $(".HMTVE180CatalogOrderConfirm.btnToInput").click(function () {
        me.btnToInput_Click();
    });
    //注文を確定ボタンクリック
    $(".HMTVE180CatalogOrderConfirm.btnConfirm").click(function () {
        me.btnConfirm_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        try {
            base_init_control();
            //ページロード
            me.Page_Load();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：ページロード

	 '関 数 名：Page_Load
	 '戻 り 値：なし

	 '処理説明：ページ初期化
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        try {
            $(".HMTVE180CatalogOrderConfirm.body").dialog({
                autoOpen: false,
                modal: true,
                height: 690,
                width: 1300,
                resizable: false,
                title: "カタログ注文_注文内容確認",
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".HMTVE180CatalogOrderConfirm.body").remove();
                },
            });
            $(".HMTVE180CatalogOrderConfirm.body").dialog("open");

            //画面初期化
            //表示設定
            $(".HMTVE180CatalogOrderConfirm.tbMain").hide();
            //注文日をセット
            $(".HMTVE180CatalogOrderConfirm.lblOrderDayShow").text(
                $("#OrderDate").val()
            );
            $(".HMTVE180CatalogOrderConfirm.lblOrderTimeShow").text(
                $("#OrderTime").val()
            );

            $(".HMTVE180CatalogOrderConfirm.lblShopCD").text(
                $("#txtIntroPeople").val()
            );
            //対象データを取得し、表示する
            me.setGridViewDate();
            //画面の制御
            $(".HMTVE180CatalogOrderConfirm.tbMain").show();
            //配送希望チェックボックス　確認画面では変更不可
            $(".HMTVE180CatalogOrderConfirm.chkHaisouKibou").attr(
                "disabled",
                true
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力画面に戻るボタンのイベント

	 '関 数 名：btnToInput_Click
	 '戻 り 値：なし

	 '処理説明：入力画面に戻る
	 '**********************************************************************
	 */
    me.btnToInput_Click = function () {
        try {
            $(".HMTVE180CatalogOrderConfirm.body").dialog("close");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：注文を確定ボタンのイベント

	 '関 数 名：btnConfirm_Click
	 '引 数 １：(I)sender イベントソース
	 '引 数 ２：(I)e      イベントパラメータ
	 '戻 り 値：なし

	 '処理説明：注文を確定
	 '**********************************************************************
	 */
    me.btnConfirm_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/" + "btnConfirm_Click";
            var data = {
                BUSYOCD: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblShopCD").text()
                ),
                lblOrderDayShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblOrderDayShow").text()
                ),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    me.cmdEvent_Click();
                };
                //取得データ件数＞0の場合
                if (result["row"] > 0) {
                    //既に%1の本日のご注文を受け付けておりますが、ご注文されますか？
                    var lblShopNameShow = $.trim(
                        $(".HMTVE180CatalogOrderConfirm.lblShopNameShow").text()
                    );
                    me.clsComFnc.FncMsgBox("QY024", lblShopNameShow);
                } else {
                    //発注確定します。よろしいですか？
                    me.clsComFnc.FncMsgBox("QY023");
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    me.cmdEvent_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click";
            var data = {
                BUSYOCD: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblShopCD").text()
                ),
                lblShopNameShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblShopNameShow").text()
                ),
                lblOrderDayShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblOrderDayShow").text()
                ),
                lblOrderTimeShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblOrderTimeShow").text()
                ),
                chkHaisouKibou: $(
                    ".HMTVE180CatalogOrderConfirm.chkHaisouKibou"
                ).is(":checked")
                    ? "1"
                    : "",
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["data"]["errorMsg"]) {
                        if (result["data"]["errorMsg"] == "W9999") {
                            me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        } else {
                            me.clsComFnc.FncMsgBox(result["data"]["errorMsg"]);
                        }
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return;
                }
                $strOrderNo = result["data"]["strOrderNo"]
                    ? result["data"]["strOrderNo"]
                    : "99999999";
                var $root_div = $(".HMTVE180CatalogOrderConfirm.HMTVE-content");
                $("<div></div>")
                    .attr("id", "HMTVE191CatalogOrderFinishDialogDiv")
                    .insertAfter($root_div);
                $("<div></div>").attr("id", "OrderNO").insertAfter($root_div);

                var $input = $root_div.parent().find("#OrderNO");
                $input.val($strOrderNo);

                var urlDialog = "HMTVE/HMTVE191CatalogOrderFinish";
                me.ajax.receive = function (result) {
                    $("#HMTVE191CatalogOrderFinishDialogDiv").html(result);
                    $(".HMTVE180CatalogOrderConfirm.body").dialog("close");
                };
                me.ajax.send(urlDialog, "", 0);
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：	対象データを取得し、表示する
	 '関 数 名：setGridViewDate
	 '戻 り 値：本カタログﾃﾞｰﾀを取得するSQL
	 '処理説明：対象データを取得し、表示する
	 '**********************************************************************
	 */
    me.setGridViewDate = function () {
        try {
            //本カタログ
            var gvBookDirUrl = me.sys_id + "/" + me.id + "/" + "setGvBookDir";
            me.data = {
                BUSYOCD: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblShopCD").text()
                ),
                lblOrderDayShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblOrderDayShow").text()
                ),
                lblOrderTimeShow: $.trim(
                    $(".HMTVE180CatalogOrderConfirm.lblOrderTimeShow").text()
                ),
            };
            var complete_fun1 = function (_returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    //入力画面に戻る
                    $(".HMTVE180CatalogOrderConfirm.body").dialog("close");
                    return;
                }
                //店舗名を表示する
                $shopName = result["shopName"];
                if ($shopName && $shopName.length > 0) {
                    $(".HMTVE180CatalogOrderConfirm.lblShopNameShow").text(
                        $shopName[0]["BUSYO_RYKNM"]
                    );
                }
                //カタログ配送希望
                $chkHaisouKibou = result["chkHaisouKibou"];
                if ($chkHaisouKibou && $chkHaisouKibou.length > 0) {
                    //カタログ配送希望データ取得件数>0件の場合
                    $(".HMTVE180CatalogOrderConfirm.chkHaisouKibou").prop(
                        "checked",
                        "true"
                    );
                } else {
                    //カタログ配送希望データ取得件数=0件の場合
                    $(".HMTVE180CatalogOrderConfirm.chkHaisouKibou").removeProp(
                        "checked"
                    );
                }

                var complete_fun2 = function (_returnFLG, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        //入力画面に戻る
                        $(".HMTVE180CatalogOrderConfirm.body").dialog("close");
                        return;
                    }
                    //用品
                    var gvProdectUrl =
                        me.sys_id + "/" + me.id + "/" + "setGvProdect";
                    var complete_fun3 = function (_returnFLG, result) {
                        if (result["error"]) {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            //入力画面に戻る
                            $(".HMTVE180CatalogOrderConfirm.body").dialog(
                                "close"
                            );
                            return;
                        }
                        //本カタログ、用品カタログ、用品について取得データが一件も存在しない場合は、エラーメッセージを表示
                        var $gvBookDir = $(me.grid_id1).jqGrid(
                            "getGridParam",
                            "records"
                        );
                        var $gvProductDir = $(me.grid_id2).jqGrid(
                            "getGridParam",
                            "records"
                        );
                        var $gvProdect = $(me.grid_id3).jqGrid(
                            "getGridParam",
                            "records"
                        );
                        if (!$gvBookDir && !$gvProductDir && !$gvProdect) {
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                //入力画面に戻る
                                $(".HMTVE180CatalogOrderConfirm.body").dialog(
                                    "close"
                                );
                            };
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "注文内容が存在しません。カタログ注文入力を行ってください。"
                            );
                        } else {
                            $(
                                ".HMTVE180CatalogOrderConfirm.btnConfirm"
                            ).trigger("focus");
                        }
                    };
                    //用品
                    gdmz.common.jqgrid.showWithMesg(
                        me.grid_id3,
                        gvProdectUrl,
                        me.colModel3,
                        "",
                        "",
                        me.option,
                        me.data,
                        complete_fun3
                    );
                    gdmz.common.jqgrid.set_grid_width(me.grid_id3, 486);
                    gdmz.common.jqgrid.set_grid_height(me.grid_id3, 108);
                    $(me.grid_id3).jqGrid("bindKeys");
                };
                //用品カタログ
                var gvProductDirUrl =
                    me.sys_id + "/" + me.id + "/" + "setGvProductDir";
                //用品カタログ
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id2,
                    gvProductDirUrl,
                    me.colModel2,
                    "",
                    "",
                    me.option,
                    me.data,
                    complete_fun2
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id2, 560);
                gdmz.common.jqgrid.set_grid_height(me.grid_id2, 236);
                $(me.grid_id2).jqGrid("bindKeys");
            };
            //本カタログ
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id1,
                gvBookDirUrl,
                me.colModel1,
                "",
                "",
                me.option,
                me.data,
                complete_fun1
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id1, 560);
            gdmz.common.jqgrid.set_grid_height(me.grid_id1, 417);
            $(me.grid_id1).jqGrid("bindKeys");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.before_close = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE180CatalogOrderConfirm =
        new HMTVE.HMTVE180CatalogOrderConfirm();
    o_HMTVE_HMTVE180CatalogOrderConfirm.load();

    o_HMTVE_HMTVE.o_HMTVE_HMTVE180CatalogOrderConfirm =
        o_HMTVE_HMTVE180CatalogOrderConfirm;

    o_HMTVE_HMTVE.HMTVE170CatalogOrderEntry.HMTVE180CatalogOrderConfirm =
        o_HMTVE_HMTVE180CatalogOrderConfirm;
    o_HMTVE_HMTVE180CatalogOrderConfirm.HMTVE170CatalogOrderEntry =
        o_HMTVE_HMTVE.HMTVE170CatalogOrderEntry;
});
