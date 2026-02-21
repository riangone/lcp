/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmJinkenhiCsv");

JKSYS.FrmJinkenhiCsv = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmJinkenhiCsv";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.dtpYM = "";
    me.updFlg = "";
    me.keydownEvent = undefined;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJinkenhiCsv.cmdCsv",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiCsv.dtpYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmJinkenhiCsv.dtpYM").keydown(function (e) {
        if (e) {
            me.keydownEvent = {
                which: e.which,
                shiftKey: e.shiftKey,
            };
        }
    });
    $(".FrmJinkenhiCsv.dtpYM").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmJinkenhiCsv.dtpYM")) == false) {
            //年月blur:空=>初期値
            $(".FrmJinkenhiCsv.dtpYM").val(me.dtpYM);
            if (me.dtpYM != "") {
                me.dtpYM_Validating(e, true);
            } else {
                $(".FrmJinkenhiCsv.cmdCsv").button("disable");
            }
        }
    });
    $(".FrmJinkenhiCsv.dtpYM").change(function (e) {
        me.dtpYM_Validating(e);
    });
    //本部負担金編集
    $(".FrmJinkenhiCsv.txtHombuFutankin").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.fncEditComma(
                    "本部負担金",
                    $(".FrmJinkenhiCsv.txtHombuFutankin")
                );
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                me.fncEditComma(
                    "本部負担金",
                    $(".FrmJinkenhiCsv.txtHombuFutankin")
                );
            }
        }
    });
    //整備負担金編集
    $(".FrmJinkenhiCsv.txtSeibiFutankin").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.fncEditComma(
                    "整備負担金",
                    $(".FrmJinkenhiCsv.txtSeibiFutankin")
                );
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                me.fncEditComma(
                    "整備負担金",
                    $(".FrmJinkenhiCsv.txtSeibiFutankin")
                );
            }
        }
    });

    $(".FrmJinkenhiCsv.txtHombuFutankin").numeric({
        decimal: false,
    });
    $(".FrmJinkenhiCsv.txtSeibiFutankin").numeric({
        decimal: false,
    });
    $(".FrmJinkenhiCsv.cmdCsv").click(function () {
        me.cmdCsv_click();
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
        //コンボボックスに初期値設定
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
        //--- 本部負担金、整備負担金設定 ---
        me.setFutankin(function () {
            $(".FrmJinkenhiCsv.dtpYM").val(me.dtpYM);
            $(".FrmJinkenhiCsv.dtpYM").trigger("focus");
            $(".FrmJinkenhiCsv.dtpYM").select();
        });
    };
    /*
	 '**********************************************************************
	 '処 理 名：本部・整備負担金設定
	 '関 数 名：setFutankin
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.setFutankin = function (funComplete) {
        var url = me.sys_id + "/" + me.id + "/" + "FrmJinkenhiCsv_Load";
        var data = {
            taishoYM: $(".FrmJinkenhiCsv.dtpYM").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                //本部負担金、整備負担金設定
                me.Futankin = result["data"]["Futankin"];
                $(".FrmJinkenhiCsv.txtHombuFutankin").val("");
                $(".FrmJinkenhiCsv.txtSeibiFutankin").val("");
                for (key in me.Futankin) {
                    $(".FrmJinkenhiCsv.txtHombuFutankin").val(
                        me.Futankin[key]["HONBU_FUTANKIN"]
                    );
                    $(".FrmJinkenhiCsv.txtSeibiFutankin").val(
                        me.Futankin[key]["SEIBI_FUTANKIN"]
                    );
                }

                //対象年月
                me.dtpYM = result["data"]["taishoYM"];
                if (funComplete) {
                    funComplete();
                }

                $(".FrmJinkenhiCsv.cmdCsv").button("enable");
            } else {
                $(".FrmJinkenhiCsv").ympicker("disable");
                $(".FrmJinkenhiCsv").prop("disabled", true);
                $(".FrmJinkenhiCsv button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：対象年月フォーカス移動時
	 '関 数 名：dtpYM_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.dtpYM_Validating = function (e, flgErrorDate) {
        //--- 本部負担金、整備負担金設定 ---
        me.setFutankin(function () {
            //対象年月
            var selCellVal = $.trim($(e.target).val());
            if (selCellVal < me.dtpYM) {
                //読取専用
                $(".FrmJinkenhiCsv.txtHombuFutankin").prop("disabled", true);
                $(".FrmJinkenhiCsv.txtSeibiFutankin").prop("disabled", true);
            } else {
                //入力可
                $(".FrmJinkenhiCsv.txtHombuFutankin").prop("disabled", false);
                $(".FrmJinkenhiCsv.txtSeibiFutankin").prop("disabled", false);
            }

            //対象年月チェック
            if (flgErrorDate) {
                if (document.documentMode) {
                    //IE11
                    if (
                        $(document.activeElement).is("." + me.id) ||
                        $(document.activeElement).is(".JKSYS-layout-center")
                    ) {
                        $(".FrmJinkenhiCsv.dtpYM").trigger("focus");
                        $(".FrmJinkenhiCsv.dtpYM").select();
                    }
                } else {
                    if (
                        !e.relatedTarget ||
                        $(e.relatedTarget).is("." + me.id)
                    ) {
                        $(".FrmJinkenhiCsv.dtpYM").trigger("focus");
                        $(".FrmJinkenhiCsv.dtpYM").select();
                    }
                }
            } else if (me.keydownEvent) {
                var event = jQuery.Event("keydown");
                event.which = me.keydownEvent.which;
                event.shiftKey = me.keydownEvent.shiftKey;
                $(".FrmJinkenhiCsv.dtpYM").trigger(event);
            }
        });
    };
    /*
	 '**********************************************************************
	 '処 理 名：カンマ編集
	 '関 数 名：fncEditComma
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fncEditComma = function (strLabel, objTxt) {
        var strText = $.trim($(objTxt).val());

        if (strText == "") {
            return;
        }

        //数値チェック
        var intRtnCD1 = me.clsComFnc.FncTextCheck(objTxt, 0, 1);
        if (intRtnCD1 == -2) {
            //数値エラー
            me.clsComFnc.ObjFocus = $(objTxt);
            me.clsComFnc.FncMsgBox("W0002", strLabel);
            return;
        }

        //桁数チェック
        var txtValue = strText.replace(/,/g, "");
        if (me.clsComFnc.GetByteCount(txtValue) > 6) {
            $(objTxt).css("background-color", "tomato");
            me.clsComFnc.ObjFocus = $(objTxt);
            me.clsComFnc.FncMsgBox("W0003", strLabel);
            return;
        }
        $(objTxt).css(me.clsComFnc.GC_COLOR_NORMAL);
        txtValue = $.trim(txtValue).replace(/\b(0+)/gi, "");
        //千位分隔符
        $(objTxt).val(txtValue.replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,"));
        if ($(objTxt).val() == "" || $(objTxt).val() == "-") {
            $(objTxt).val("");
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：ＣＳＶ出力ボタン
	 '関 数 名：cmdCsv_click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdCsv_click = function () {
        //対象年月
        me.dtpYM = $(".FrmJinkenhiCsv.dtpYM").val();

        var url = me.sys_id + "/" + me.id + "/" + "make_CSV";
        var data = {
            dtpYM: me.dtpYM,
            HombuFutankin: $(".FrmJinkenhiCsv.txtHombuFutankin").val(),
            SeibiFutankin: $(".FrmJinkenhiCsv.txtSeibiFutankin").val(),
            checkRetHombu: me.clsComFnc.FncTextCheck(
                $(".FrmJinkenhiCsv.txtHombuFutankin"),
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ), //本部負担金 必須チェック，数値チェック
            checkRetSeibi: me.clsComFnc.FncTextCheck(
                $(".FrmJinkenhiCsv.txtSeibiFutankin"),
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ), //整備負担金 必須チェック，数値チェック
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["row"] && result["row"] > 0) {
                    //出力完了のメッセージを表示する
                    me.clsComFnc.FncMsgBox("I0011");
                }
            } else {
                if (result["error"] == "W9999" && result["msg"]) {
                    me.clsComFnc.FncMsgBox(result["error"], result["msg"]);
                } else if (result["error"] == "W0001_Hombu") {
                    //本部負担金 必須チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtHombuFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0001", "本部負担金");
                } else if (result["error"] == "W0002_Hombu") {
                    //本部負担金 数値チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtHombuFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0002", "本部負担金");
                } else if (result["error"] == "W0001_Seibi") {
                    //整備負担金 必須チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtSeibiFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0001", "整備負担金");
                } else if (result["error"] == "W0002_Seibi") {
                    //整備負担金 数値チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtSeibiFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0002", "整備負担金");
                } else if (result["error"] == "W0001") {
                    //出力先 フォルダ存在チェック
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                } else if (result["error"] == "W0015") {
                    //出力先 フォルダ存在チェック
                    me.clsComFnc.FncMsgBox("W0015");
                } else if (result["error"] == "W0017_Hombu") {
                    //本部負担金 範囲チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtHombuFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0017", "本部負担金は1以上の数値");
                } else if (result["error"] == "W0017_Seibi") {
                    //整備負担金 範囲チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmJinkenhiCsv.txtSeibiFutankin"
                    );
                    me.clsComFnc.FncMsgBox("W0017", "整備負担金は1以上の数値");
                } else if (result["error"] == "W0016") {
                    me.clsComFnc.FncMsgBox("W0016");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_FrmJinkenhiCsv_FrmJinkenhiCsv = new JKSYS.FrmJinkenhiCsv();
    o_FrmJinkenhiCsv_FrmJinkenhiCsv.load();
});
