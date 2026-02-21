/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE210PresentOrderEntry");

HMTVE.HMTVE210PresentOrderEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE210PresentOrderEntry";
    me.hmtve = new HMTVE.HMTVE();
    me.last_selected_id = "";
    me.reload = false;

    // jqgrid
    me.grid_id = "#HMTVE210PresentOrderEntry_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/getData";
    me.option = {
        rownumbers: false,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        loadui: "disable",
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "ORDER_NO",
            label: "lblOrderNO",
            index: "ORDER_NO",
            width: 100,
            align: "center",
            sortable: false,
            hidden: true,
        },
        {
            name: "HINMEI",
            label: "品名",
            index: "HINMEI",
            width: 312,
            align: "left",
            sortable: false,
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            width: 117,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            name: "ORDER_NUM",
            label: "注文数",
            index: "ORDER_NUM",
            width: 117,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "6",
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //当前id
                            var nowId = this.parentElement.parentElement.id;
                            var lblTotal = this.parentElement.nextSibling;
                            var txtOrderNum = this.value;
                            me.totalSum(nowId, lblTotal, txtOrderNum);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9) ||
                                key == 38 ||
                                key == 40
                            ) {
                                //当前id
                                var nowId = this.parentElement.parentElement.id;
                                var lblTotal = this.parentElement.nextSibling;
                                var txtOrderNum = this.value;
                                me.totalSum(nowId, lblTotal, txtOrderNum);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KINGAKU",
            label: "合計",
            index: "KINGAKU",
            width: 116,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE210PresentOrderEntry.button",
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
    //展示会検索ボタンクリック
    $(".HMTVE210PresentOrderEntry.btnExhibitSearch").click(function () {
        me.btnExhibitSearch_Click();
    });
    //表示ボタンクリック
    $(".HMTVE210PresentOrderEntry.btnShow").click(function () {
        me.btnShow_Click();
    });
    //注文ボタンクリック
    $(".HMTVE210PresentOrderEntry.btnOrder").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.DeleteDataByCD;
        me.clsComFnc.FncMsgBox("QY999", "注文します。よろしいですか？");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };
    // **********************************************************************
    // 処 理 名：ページロード
    // 関 数 名：Page_Load
    // 戻 り 値：なし
    // 処理説明：ページ初期化
    // **********************************************************************
    me.Page_Load = function () {
        $(".HMTVE210PresentOrderEntry.lblExhibitTime1").val("");
        $(".HMTVE210PresentOrderEntry.lblExhibitTime2").val("");
        $(".HMTVE210PresentOrderEntry.lblShopName2").val("");
        //当ページを初期の状態にセットする
        $(".HMTVE210PresentOrderEntry.pnlList").hide();
        var url = me.sys_id + "/" + me.id + "/" + "pageclear";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HMTVE210PresentOrderEntry.btnShow").button("disable");
                    //フォーカス移動
                    $(".HMTVE210PresentOrderEntry.btnExhibitSearch").trigger(
                        "focus"
                    );
                };
                if (result["data"] && result["data"]["msg"]) {
                    me.clsComFnc.FncMsgBox(
                        result["data"]["msg"],
                        result["error"]
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            //店舗名を表示する
            if (result["data"]["shopName"]) {
                me.Page_ShopNameSave(result["data"]["shopName"][0]);
            }
            //展示会開催期間に初期値をセット
            if (result["data"]["START_DATE"]) {
                $(".HMTVE210PresentOrderEntry.lblExhibitTime1").val(
                    result["data"]["START_DATE"]
                );
            }
            if (result["data"]["END_DATE"]) {
                $(".HMTVE210PresentOrderEntry.lblExhibitTime2").val(
                    result["data"]["END_DATE"]
                );
            }
            //フォーカス移動
            $(".HMTVE210PresentOrderEntry.btnExhibitSearch").trigger("focus");
        };
        me.ajax.send(url, "", 0);
    };
    // '**********************************************************************
    // '処 理 名：当ページを初期化する
    // '関 数 名：Page_Clear
    // '引 数 １：なし
    // '戻 り 値：なし
    // '処理説明：当ページを初期の状態にセットする
    // '**********************************************************************
    me.Page_Clear = function (objdr2) {
        $(".HMTVE210PresentOrderEntry.pnlList").hide();

        // 店舗名と店舗コードを設定する
        me.Page_ShopNameSave(objdr2);

        $(".HMTVE210PresentOrderEntry.btnExhibitSearch").trigger("focus");
    };
    // **********************************************************************
    // 処 理 名：表示ボタンクリック
    // 関 数 名：btnView_Click
    // 戻 り 値：なし
    // 処理説明：画面の内容を表示する
    // **********************************************************************
    me.btnShow_Click = function () {
        if (me.checkNull() == false) {
            return;
        }
        if ($.trim($(".HMTVE210PresentOrderEntry.lblShopName2").val()) == "") {
            $(".HMTVE210PresentOrderEntry.btnExhibitSearch").trigger("focus");
            $(".HMTVE210PresentOrderEntry.btnShow").button("disable");
            me.clsComFnc.FncMsgBox("W9999", "登録店舗が確定できません。");
            return false;
        }
        $(".HMTVE210PresentOrderEntry.pnlList").hide();
        //データを取得する
        var url = me.sys_id + "/" + me.id + "/" + "getFlag";
        me.data = {
            STARTDT: $(".HMTVE210PresentOrderEntry.lblExhibitTime1")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //データを取得する
            var complete_fun = function (returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                if (returnFLG == "nodata") {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "成約プレゼント注文の設定が行われておりません。管理者にお問い合わせください。"
                    );
                    return;
                }
                me.fncJqgrid();
                $(".HMTVE210PresentOrderEntry.pnlList").show();
            };
            if (me.reload == false) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    "",
                    "",
                    me.option,
                    me.data,
                    complete_fun
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 700);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 150);
                $(me.grid_id).jqGrid("bindKeys");
                me.reload = true;
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    me.data,
                    complete_fun
                );
            }
        };
        me.ajax.send(url, me.data, 0);
    };
    me.fncJqgrid = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                $(me.grid_id).jqGrid(
                    "saveRow",
                    me.last_selected_id,
                    null,
                    "clientArray"
                );
                if (typeof e != "undefined") {
                    if (rowid && rowid != me.last_selected_id) {
                        me.last_selected_id = rowid;
                    }

                    $("input,select", e.target).trigger("focus");
                } else {
                    if (rowid && rowid != me.last_selected_id) {
                        me.last_selected_id = rowid;
                    }
                }
                $(me.grid_id).jqGrid("editRow", rowid, false);
                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    rowid
                );

                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                $(me.grid_id).find(".width").css("width", "95%");
            },
        });
        $(me.grid_id).jqGrid("setSelection", 0, true);
    };
    // '**********************************************************************
    // '処 理 名：データ削除のイベント
    // '関 数 名：DeleteDataByCD
    // '戻 り 値：なし
    // '処理説明：成約プレゼント注文データを削除する
    // '**********************************************************************
    me.DeleteDataByCD = function () {
        if (!me.btnOrder_Click()) {
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "deleteDataByCD";
        var jqgridArr = new Array();
        var rowdata = "";
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            $ORDER_NUM = $.trim(rowdata["ORDER_NUM"]);
            jqgridArr.push({
                ORDER_NO: rowdata["ORDER_NO"],
                ORDER_NUM: $ORDER_NUM ? $ORDER_NUM : 0,
            });
        }
        var data = {
            gvSyouhin: jqgridArr,
            STARTDT: $(".HMTVE210PresentOrderEntry.lblExhibitTime1")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            var shopName =
                result["data"]["shopName"] != undefined
                    ? result["data"]["shopName"][0]
                    : "";

            if (
                !result["result"] &&
                result["error"] ==
                    "既に出力が行われていますので、登録は出来ません"
            ) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
                    $(me.grid_id).jqGrid("setSelection", id, true);
                };
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    //画面をクリアする
                    me.Page_Clear(shopName);
                };
                if (!result["result"]) {
                    if (result["data"] && result["data"]["msg"]) {
                        me.clsComFnc.FncMsgBox(result["data"]["msg"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                } else {
                    me.clsComFnc.FncMsgBox("I9999", "注文を受け付けました。");
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：注文ボタンのイベント
    // '関 数 名：btnOrder_Click
    // '戻 り 値：なし
    // '処理説明：注文ボタンをクリックする
    // '**********************************************************************
    me.btnOrder_Click = function () {
        $(me.grid_id).jqGrid("saveRow", me.last_selected_id);
        var datas = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < datas.length; i++) {
            if (me.clsComFnc.GetByteCount($.trim(datas[i]["ORDER_NUM"])) > 6) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjFocus = $("#" + i + "_ORDER_NUM");
                setTimeout(() => {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文数は指定されている桁数をオーバーしています。"
                    );
                }, 0);

                $(
                    me.grid_id +
                        " tr[id='" +
                        i +
                        "']" +
                        " td[aria-describedby='HMTVE210PresentOrderEntry_tblMain_KINGAKU']"
                ).html("");
                return false;
            }
            if (
                !$.isNumeric(datas[i]["ORDER_NUM"]) &&
                datas[i]["ORDER_NUM"] !== "" &&
                datas[i]["ORDER_NUM"] !== null
            ) {
                $(me.grid_id).jqGrid("setSelection", i, true);
                me.clsComFnc.ObjFocus = $("#" + i + "_ORDER_NUM");
                setTimeout(() => {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                }, 0);

                $(
                    me.grid_id +
                        " tr[id='" +
                        i +
                        "']" +
                        " td[aria-describedby='HMTVE210PresentOrderEntry_tblMain_KINGAKU']"
                ).html("");
                return false;
            }
        }
        return true;
    };
    // **********************************************************************
    // 処 理 名：空値チェック
    // 関 数 名：checkNull
    // 引 数 １：なし
    // 戻 り 値：なし
    // 処理説明：空の値をチェックする
    // **********************************************************************
    me.checkNull = function () {
        //展示会開催期間(From)が未入力の場合、エラー
        if ($(".HMTVE210PresentOrderEntry.lblExhibitTime1").val().length == 0) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE210PresentOrderEntry.btnExhibitSearch"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください。"
            );
            return false;
        }
        //展示会開催期間(To)が未入力の場合、エラー
        if ($(".HMTVE210PresentOrderEntry.lblExhibitTime2").val().length == 0) {
            me.clsComFnc.ObjFocus = $(
                ".HMTVE210PresentOrderEntry.btnExhibitSearch"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください。"
            );
            return false;
        }
        return true;
    };
    // **********************************************************************
    // 処 理 名：展示会検索ページを開く
    // 関 数 名：btnETSearch_Click
    // 戻 り 値：なし
    // 処理説明：展示会検索ページを開く
    // **********************************************************************
    me.btnExhibitSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE210PresentOrderEntryDialogDiv";
        var $rootDiv = $(".HMTVE210PresentOrderEntry.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETStart").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETEnd").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMTVE210PresentOrderEntry.lblExhibitTime1").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE210PresentOrderEntry.lblExhibitTime2").val(
                        $lblETEnd.html()
                    );
                    $(".HMTVE210PresentOrderEntry.pnlList").hide();
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
            }
            $(".HMTVE210PresentOrderEntry.btnExhibitSearch").trigger("blur");

            $RtnCD.hide();
            $lblETStart.hide();
            $lblETEnd.hide();
            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE210PresentOrderEntry.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    // '**********************************************************************
    // '処 理 名：当ページを初期化する
    // '関 数 名：Page_ShopNameSave
    // '引 数 １：flg:load/delete
    // '戻 り 値：なし
    // '処理説明：店舗コード、店舗名を抽出する
    // '**********************************************************************
    me.Page_ShopNameSave = function (objdr2) {
        if (objdr2) {
            $(".HMTVE210PresentOrderEntry.lblShopName2").val(
                objdr2["BUSYO_RYKNM"]
            );
        } else {
            $(".HMTVE210PresentOrderEntry.lblShopName2").val("");
        }
    };
    //和を計算する
    me.totalSum = function (nowId, lblTotal, txtOrderNum) {
        //获取数据
        var data = $(me.grid_id).jqGrid("getRowData", nowId);
        var lblTanka = data.TANKA;
        var reg = /^\d*$/;
        var reg2 = /[\uFF00-\uFFFF]/;

        if (txtOrderNum == null || txtOrderNum == "") {
            lblTotal.innerText = "";
            return;
        } else if (!reg.test(txtOrderNum.toString())) {
            lblTotal.innerText = "";
            return;
        } else if (reg2.test(txtOrderNum.toString())) {
            lblTotal.innerText = "";
            return;
        } else if (txtOrderNum.toString().length > 6) {
            return;
        }
        result = parseInt(me.decomma(lblTanka)) * parseInt(txtOrderNum);

        lblTotal.innerText = me.comma(result);
    };
    //3桁カンマ区切りを外す
    me.decomma = function (num) {
        var x = num.split(",");
        return parseInt(x.join(""));
    };
    //3桁カンマ区切りを表示する
    me.comma = function (num) {
        num = num + "";
        var re = /(-?\d+)(\d{3})/;
        while (re.test(num)) {
            num = num.replace(re, "$1,$2");
        }
        return num;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE210PresentOrderEntry =
        new HMTVE.HMTVE210PresentOrderEntry();
    o_HMTVE_HMTVE210PresentOrderEntry.load();
    o_HMTVE_HMTVE.HMTVE210PresentOrderEntry = o_HMTVE_HMTVE210PresentOrderEntry;
});
