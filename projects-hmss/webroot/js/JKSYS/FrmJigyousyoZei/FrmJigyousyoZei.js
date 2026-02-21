Namespace.register("JKSYS.FrmJigyousyoZei");

JKSYS.FrmJigyousyoZei = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ========== 変数 start ==========

    me.id = "FrmJigyousyoZei";
    me.sys_id = "JKSYS";
    me.grid_id = "#FrmJigyousyoZei_sprList";

    me.dtpTaisyouYM_F = "";
    me.dtpTaisyouYM_T = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJigyousyoZei.btnExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJigyousyoZei.dtpTaisyouYM_F",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJigyousyoZei.dtpTaisyouYM_T",
        type: "datepicker",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmJigyousyoZei.btnExcel").click(function () {
        me.btnExcel_Click();
    });

    me.foucus_back = undefined;
    me.eventType = "focusout";
    if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
        me.eventType = "blur";
    }
    //対象期間from
    $(".FrmJigyousyoZei.dtpTaisyouYM_F")
        .on(me.eventType, function (e) {
            if (
                me.clsComFnc.CheckDate($(".FrmJigyousyoZei.dtpTaisyouYM_F")) ==
                false
            ) {
                $(".FrmJigyousyoZei.dtpTaisyouYM_F").val(me.dtpTaisyouYM_F);
                if (
                    !e.relatedTarget ||
                    $(e.relatedTarget).is("." + me.id) ||
                    $(e.relatedTarget).prop("className").indexOf(me.sys_id) !=
                        -1
                ) {
                    me.foucus_back = setTimeout(function () {
                        $(".FrmJigyousyoZei.dtpTaisyouYM_F").trigger("focus");
                        $(".FrmJigyousyoZei.dtpTaisyouYM_F").select();
                    }, 0);
                }
                $(".FrmJigyousyoZei.btnExcel").button("disable");
            } else {
                $(".FrmJigyousyoZei.btnExcel").button("enable");
            }
        })
        .on("focus", function () {
            if (me.foucus_back) {
                clearTimeout(me.foucus_back);
            }
        });
    //対象期間to
    $(".FrmJigyousyoZei.dtpTaisyouYM_T")
        .on(me.eventType, function (e) {
            if (
                me.clsComFnc.CheckDate($(".FrmJigyousyoZei.dtpTaisyouYM_T")) ==
                false
            ) {
                $(".FrmJigyousyoZei.dtpTaisyouYM_T").val(me.dtpTaisyouYM_T);
                if (
                    !e.relatedTarget ||
                    $(e.relatedTarget).is("." + me.id) ||
                    $(e.relatedTarget).prop("className").indexOf(me.sys_id) !=
                        -1
                ) {
                    me.foucus_back = setTimeout(function () {
                        $(".FrmJigyousyoZei.dtpTaisyouYM_T").trigger("focus");
                        $(".FrmJigyousyoZei.dtpTaisyouYM_T").select();
                    }, 0);
                }
                $(".FrmJigyousyoZei.btnExcel").button("disable");
            } else {
                $(".FrmJigyousyoZei.btnExcel").button("enable");
            }
        })
        .on("focus", function () {
            if (me.foucus_back) {
                clearTimeout(me.foucus_back);
            }
        });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.frmJigyousyoZei_Load();
    };
    me.frmJigyousyoZei_Load = function () {
        //画面初期化
        me.Formit();
    };
    //画面初期化(画面起動時)
    me.Formit = function () {
        //データ取得(SQL)
        var url = me.sys_id + "/" + me.id + "/FncGetJKCMST";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmJigyousyoZei").datepicker("disable");
                $(".FrmJigyousyoZei").attr("disabled", true);
                $(".FrmJigyousyoZei button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                //対象年月(期首・期末)
                me.dtpTaisyouYM_F = result["data"]["KISYU_YMD"];
                me.dtpTaisyouYM_T = result["data"]["KIMATU_YMD"];
                $(".FrmJigyousyoZei.dtpTaisyouYM_F").val(me.dtpTaisyouYM_F);
                $(".FrmJigyousyoZei.dtpTaisyouYM_T").val(me.dtpTaisyouYM_T);
                $(".FrmJigyousyoZei.dtpTaisyouYM_F").select();

                // テキストボックス
                $(".FrmJigyousyoZei.txtOld").val("");

                //ボタン
                $(".FrmJigyousyoZei.btnExcel").attr("disabled", false);

                if (result["data"]["ERROR_FLG"]) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            }
        };
        me.ajax.send(url, "", 0);
    };
    //Excelボタンクリック
    me.btnExcel_Click = function () {
        //入力チェック
        if (me.InPutCheck() !== 0) {
            return;
        }

        var dtpTaisyouYM_F = $(".FrmJigyousyoZei.dtpTaisyouYM_F").val();
        var dtpTaisyouYM_T = $(".FrmJigyousyoZei.dtpTaisyouYM_T").val();
        var txtOld = $(".FrmJigyousyoZei.txtOld").val();
        var url = me.sys_id + "/" + me.id + "/btnExcel_Click";
        var data = {
            dtpTaisyouYM_F: dtpTaisyouYM_F,
            dtpTaisyouYM_T: dtpTaisyouYM_T,
            txtOld: txtOld,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"] && result["data"] == "I0011") {
                    me.clsComFnc.FncMsgBox(result["data"]);
                }
            } else {
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.InPutCheck = function () {
        //対象期間
        var strTaisyouYM_F = $(".FrmJigyousyoZei.dtpTaisyouYM_F").val();
        var strTaisyouYM_T = $(".FrmJigyousyoZei.dtpTaisyouYM_T").val();

        //From <= To では無い場合
        if (strTaisyouYM_F > strTaisyouYM_T) {
            me.clsComFnc.ObjFocus = $(".FrmJigyousyoZei.dtpTaisyouYM_F");
            me.clsComFnc.FncMsgBox("W0006", "対象期間");
            return 1;
        }

        //老人年齢
        if (
            me.clsComFnc.FncTextCheck($(".FrmJigyousyoZei.txtOld"), 1, 0, 2) ==
            -1
        ) {
            //必須
            me.clsComFnc.ObjFocus = $(".FrmJigyousyoZei.txtOld");
            me.clsComFnc.FncMsgBox("W0001", "老人年齢");
            return 1;
        }
        if (
            me.clsComFnc.FncTextCheck($(".FrmJigyousyoZei.txtOld"), 1, 0, 2) ==
            -2
        ) {
            //数値
            me.clsComFnc.ObjFocus = $(".FrmJigyousyoZei.txtOld");
            me.clsComFnc.FncMsgBox("W0002", "老人年齢");
            return 1;
        }
        if (
            me.clsComFnc.FncTextCheck($(".FrmJigyousyoZei.txtOld"), 1, 0, 2) ==
            -3
        ) {
            //桁数
            me.clsComFnc.ObjFocus = $(".FrmJigyousyoZei.txtOld");
            me.clsComFnc.FncMsgBox("W0003", "老人年齢");
            return 1;
        }

        return 0;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmJigyousyoZei = new JKSYS.FrmJigyousyoZei();
    o_JKSYS_FrmJigyousyoZei.load();
});
