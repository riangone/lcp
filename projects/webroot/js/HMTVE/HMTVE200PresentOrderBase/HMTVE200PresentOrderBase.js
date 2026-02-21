/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE200PresentOrderBase");

HMTVE.HMTVE200PresentOrderBase = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE200PresentOrderBase";
    me.hmtve = new HMTVE.HMTVE();
    me.lastsel = "";

    // jqgrid
    me.grid_id = "#HMTVE200PresentOrderBase_tblMain";
    me.colModel = [
        {
            name: "HINMEI",
            label: "品名",
            index: "HINMEI",
            width: 302,
            align: "left",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "50",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            width: 75,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "6",
                class: "align_right",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CREATE_DATE",
            label: "",
            index: "CREATE_DATE",
            width: 40,
            align: "center",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE200PresentOrderBase.button",
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
    // = 宣言 end =objdrShopSya
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //登録ボタンクリック
    $(".HMTVE200PresentOrderBase.btnReg").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnReg_Click;
        me.clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
    });

    //削除ボタンクリック
    $(".HMTVE200PresentOrderBase.btnDel").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDel_Click;
        me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
    });

    //表示ボタンクリック
    $(".HMTVE200PresentOrderBase.btnPrintOut").click(function () {
        if (!me.checkNull()) {
            return;
        }
        me.btnPrintOut_Click();
    });

    //展示会検索ボタンクリック
    $(".HMTVE200PresentOrderBase.btnETSearch").click(function () {
        me.btnETSearch_Click();
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
        me.Page_Load();
    };
    $(me.grid_id).jqGrid({
        datatype: "local",
        rowNum: 0,
        caption: "",
        rownumbers: true,
        rownumWidth: 40,
        loadui: "disable",
        multiselect: false,
        colModel: me.colModel,
    });
    $(me.grid_id).jqGrid("bindKeys");
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 450);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 132);
        $("#HMTVE200PresentOrderBase_tblMain_rn").html("NO");
        me.fncJqgrid();
        //画面初期化
        me.Page_Clear();
    };
    me.fncJqgrid = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowId, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowId && rowId != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowId;
                        }
                        $(me.grid_id).jqGrid("editRow", rowId, {
                            keys: true,
                            focusField: cellIndex,
                        });
                    }
                } else {
                    if (rowId && rowId != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowId;
                    }
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                }
                gdmz.common.jqgrid.setKeybordEvents(me.grid_id, e, rowId);
                $(me.grid_id).find(".width").css("width", "91%");
                //靠右
                $(me.grid_id).find(".align_right").css("text-align", "right");
            },
            //ヘッダー選択を無効にする
            beforeSelectRow: function (_rowid, e) {
                var cellIndex = e.target.cellIndex;
                if (cellIndex == 0) {
                    setTimeout(() => {
                        var selNextId = "#" + me.lastsel + "_HINMEI";
                        $(selNextId).trigger("focus");
                        $(selNextId).select();
                    }, 0);
                    return false;
                }
                return true;
            },
        });
    };
    //'**********************************************************************
    //'処 理 名：当ページを初期化する
    //'関 数 名：Page_Clear
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：当ページを初期の状態にセットする
    //'**********************************************************************
    me.Page_Clear = function () {
        //画面初期化
        $(me.grid_id).jqGrid("clearGridData");
        $(".HMTVE200PresentOrderBase.pnlList").hide();
        //ボタンの設定
        $(".HMTVE200PresentOrderBase.HMS-button-pane").hide();
        //画面項目をクリアする
        $(".HMTVE200PresentOrderBase.lblExhibitTermStart").val("");
        $(".HMTVE200PresentOrderBase.lblExhibitTermEnd").val("");
        //フォーカス移動
        $(".HMTVE200PresentOrderBase.btnETSearch").trigger("focus");
    };
    //'**********************************************************************
    //'処 理 名：展覧会検索ボタンのイベント
    //'関 数 名：btnETSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索画面の戻り値を画面項目にセットする
    //'**********************************************************************
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE200PresentOrderBaseDialogDiv";
        var $rootDiv = $(".HMTVE200PresentOrderBase.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div style='display:none;'></div>")
            .attr("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "RtnCD")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETStart")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "lblETEnd")
            .insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    //選択した値をセットする
                    $(".HMTVE200PresentOrderBase.lblExhibitTermStart").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE200PresentOrderBase.lblExhibitTermEnd").val(
                        $lblETEnd.html()
                    );

                    $(".HMTVE200PresentOrderBase.pnlList").hide();
                    //ボタンの設定
                    $(".HMTVE200PresentOrderBase.HMS-button-pane").hide();
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
                $(".HMTVE200PresentOrderBase.lblExhibitTermStart").trigger(
                    "focus"
                );
            }
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE200PresentOrderBase.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    //'**********************************************************************
    //'処 理 名：表示ボタンのイベント
    //'関 数 名：btnPrintOut_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：取得データをグリッドビューにバインドする
    //'**********************************************************************
    me.btnPrintOut_Click = function () {
        $(".HMTVE200PresentOrderBase.pnlList").hide();
        //ボタンの設定
        $(".HMTVE200PresentOrderBase.HMS-button-pane").hide();

        var url = me.sys_id + "/" + me.id + "/" + "btnPrintOutClick";
        var data = {
            STARTDT: $(".HMTVE200PresentOrderBase.lblExhibitTermStart")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //指定されたデータソースを生成する
            me.CreateDataSource(result["data"]);

            $(".HMTVE200PresentOrderBase.pnlList").show();
            //ボタンの設定
            $(".HMTVE200PresentOrderBase.HMS-button-pane").show();
            $(me.grid_id).jqGrid("setSelection", 0);
            $(".HMTVE200PresentOrderBase #0_HINMEI").trigger("focus");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：データソースを生成する
    //'関 数 名：CreateDataSource
    //'引    数：objdr　データソース
    //'戻 り 値：無し
    //'処理説明：指定されたデータソースを生成する
    //'**********************************************************************
    me.CreateDataSource = function (objdr) {
        $(me.grid_id).jqGrid("clearGridData");
        for (var i = 0; i < 5; i++) {
            $(me.grid_id).jqGrid("addRowData", i, {});
        }
        if (objdr && objdr.length > 0) {
            for (var j = 0; j < objdr.length; j++) {
                var i = parseInt(objdr[j]["ORDER_NO"]) - 1;
                $(me.grid_id).jqGrid(
                    "setCell",
                    i,
                    "HINMEI",
                    objdr[j]["HINMEI"]
                );
                $(me.grid_id).jqGrid("setCell", i, "TANKA", objdr[j]["TANKA"]);
                $(me.grid_id).jqGrid(
                    "setCell",
                    i,
                    "CREATE_DATE",
                    objdr[j]["CREATE_DATE"]
                );
            }
        }
    };
    //'**********************************************************************
    //'処 理 名：入力チェック
    //'関 数 名：fncInputCheck
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：入力の内容をチェック
    //'**********************************************************************
    me.fncInputCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var emptyLine = 0;
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            var txtHinmei = $.trim(rowdata["HINMEI"]);
            var txtTanka = $.trim(rowdata["TANKA"]);
            //必須チェックを行う
            if (txtHinmei != "" && txtTanka == "") {
                //品名を入力した、単価を入力しなかった場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "品名を入力した場合は単価も入力してください。"
                );
                return false;
            }
            if (txtHinmei == "" && txtTanka != "") {
                //品名を入力しなかった、単価を入力した場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_HINMEI");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "単価を入力した場合は品名も入力してください。"
                );
                return false;
            }
            //桁数チェックを行う
            if (me.GetLen(txtHinmei) > 50) {
                //品名の桁数で指定されている桁数を超える場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_HINMEI");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "品名は指定されている桁数をオーバーしています。"
                );
                return false;
            }
            if (me.GetLen(txtTanka) > 6) {
                //品名の桁数で指定されている桁数を超える場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "単価は指定されている桁数をオーバーしています。"
                );
                return false;
            }
            if (!me.isHankaku(txtTanka)) {
                //半角じゃない場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                me.clsComFnc.FncMsgBox("W9999", "入力されている値が不正です。");
                return false;
            }
            if (txtTanka.indexOf(".") != -1) {
                //小数の場合
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                me.clsComFnc.FncMsgBox("W9999", "入力されている値が不正です。");
                return false;
            }
            //整合性チェックを行う
            if (txtTanka != "") {
                if (isNaN(txtTanka)) {
                    //名設定ﾃｰﾌﾞﾙ_単価に数値以外が入力されている場合
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                } else if (parseInt(txtTanka) < 0) {
                    //正数じゃない場合
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
            }
            if (txtHinmei == "" && txtTanka == "") {
                //品名も単価も入力しなかった場合
                emptyLine++;
            }
        }
        if (emptyLine == 5) {
            //品名設定ﾃｰﾌﾞﾙ_NO1～NO5がすべて空白の場合
            $(me.grid_id).jqGrid("setSelection", 0, true);
            me.clsComFnc.ObjFocus = $("#0_HINMEI");
            me.clsComFnc.FncMsgBox("W9999", "品名、単価を入力してください。");
            return false;
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnReg_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：データ追加する
    //'**********************************************************************
    me.btnReg_Click = function () {
        if (!me.fncInputCheck()) {
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnRegClick";
        var jqgridArr = new Array();
        var rowdata = "";
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            jqgridArr.push({
                ORDER_NO: parseInt(i) + 1,
                txtHinmei: $.trim(rowdata["HINMEI"]),
                txtTanka: $.trim(rowdata["TANKA"]),
                lblCREATE_DATE: $.trim(rowdata["CREATE_DATE"]),
            });
        }
        var data = {
            STARTDT: $(".HMTVE200PresentOrderBase.lblExhibitTermStart")
                .val()
                .replace(/\//g, ""),
            gvTenpo: jqgridArr,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
                    $(me.grid_id).jqGrid("setSelection", id, true);
                };
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                //画面項目をクリアする
                me.Page_Clear();
            };
            //登録が完了しました
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：削除ボタンのイベント
    //'関 数 名：btnDel_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：データを削除する
    //'**********************************************************************
    me.btnDel_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnDelClick";
        var data = {
            STARTDT: $(".HMTVE200PresentOrderBase.lblExhibitTermStart")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Clear();
            };
            //削除が完了しました
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：指定した引数の桁数を取得する
    //'関 数 名：GetLen
    //'引    数：str　桁数を統計される値
    //'戻 り 値：無し
    //'処理説明：指定した引数の桁数を取得する
    //'**********************************************************************
    me.GetLen = function (str) {
        if (str == "") {
            return 0;
        } else {
            return me.clsComFnc.GetByteCount(str);
        }
    };
    //'**********************************************************************
    //'処 理 名：指定した引数の桁数を取得する
    //'関 数 名：isHankaku
    //'引    数：str　文字序列
    //'戻 り 値：Trueの場合、半角
    //         Falseの場合、非半角
    //'処理説明：指定した引数は半角かとうか判断する
    //'**********************************************************************
    me.isHankaku = function (str) {
        if (str == "") {
            return true;
        } else {
            if (me.clsComFnc.GetByteCount(str) > str.length) {
                return false;
            }
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：空の値をチェックする
    //'関 数 名：checkNull
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：空の値をチェックする
    //'**********************************************************************
    me.checkNull = function () {
        var lblETStart = $.trim(
            $(".HMTVE200PresentOrderBase.lblExhibitTermStart").val()
        );
        var lblETEnd = $.trim(
            $(".HMTVE200PresentOrderBase.lblExhibitTermEnd").val()
        );

        if (lblETStart == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください"
            );
            return false;
        }
        if (lblETEnd == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください"
            );
            return false;
        }
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE200PresentOrderBase = new HMTVE.HMTVE200PresentOrderBase();
    o_HMTVE_HMTVE200PresentOrderBase.load();
    o_HMTVE_HMTVE.HMTVE200PresentOrderBase = o_HMTVE_HMTVE200PresentOrderBase;
});
