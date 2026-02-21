Namespace.register("JKSYS.FrmPassMente");

JKSYS.FrmPassMente = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    me.id = "JKSYS/FrmPassMente";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmPassMente.cmdCan",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPassMente.cmdReg",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPassMente.cmdDel",
        type: "button",
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
    //      'キャンセルボタン
    // '**********************************************************************
    $(".FrmPassMente.cmdCan").click(function () {
        me.cmdCan_Click();
    });

    // '**********************************************************************
    // '登録ボタン
    // '**********************************************************************
    $(".FrmPassMente.cmdReg").click(function () {
        //入力チェック
        if (me.fncInputChk(1) == false) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdReg_Click();
        };
        me.clsComFnc.FncMsgBox("QY010");
    });

    // '**********************************************************************
    // '削除ボタン
    // '**********************************************************************
    $(".FrmPassMente.cmdDel").click(function () {
        if (me.fncInputChk(2) == false) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdDel_Click();
        };
        me.clsComFnc.FncMsgBox("QY004");
    });

    // '**********************************************************************
    //      'プログラム名変更時
    // '**********************************************************************
    $(".FrmPassMente.cmbPGNM").change(function () {
        me.cmbPGNM_Leave();
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
        me.FrmPassMente_Load();
    };

    //フォームロード
    me.FrmPassMente_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();

        var url = me.id + "/" + "fncGetPGMSTSQL";
        var selectObj = {};
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmPassMente").attr("disabled", true);
                $(".FrmPassMente button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //コンボボックス設定
                for (key in result["data"]) {
                    $("<option></option>")
                        .val(result["data"][key]["PRO_NO"])
                        .text(result["data"][key]["PRO_NM"])
                        .appendTo(".FrmPassMente.cmbPGNM");
                }
                $(".FrmPassMente.cmbPGNM").val(
                    $(".FrmPassMente.cmbPGNM").val()
                );
            }

            //フォーカス設定
            $(".FrmPassMente.cmbPGNM").trigger("focus");

            //初期表示クリア
            $(".FrmPassMente.cmbPGNM").selectedIndex = -1;
            $(".FrmPassMente.cmbPGNM").val("");
        };

        me.ajax.send(url, selectObj, 0);
    };

    //キャンセルボタン
    me.cmdCan_Click = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();
        $(".FrmPassMente.cmbPGNM").trigger("focus");
    };

    //登録ボタン
    me.cmdReg_Click = function () {
        var url = me.id + "/cmdReg_Click";

        var cmbPGNM = $(".FrmPassMente.cmbPGNM").val();
        var txtPass1 = $(".FrmPassMente.txtPass1").val();
        var data = {
            cmbPGNM: cmbPGNM,
            txtPass1: txtPass1,
        };
        me.ajax.receive = function (res) {
            res = eval("(" + res + ")");

            if (res["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", res["error"]);
            } else {
                $(".FrmPassMente.cmbPGNM").trigger("focus");
                me.clsComFnc.FncMsgBox("I0012");
                me.subClearForm();
            }
        };

        me.ajax.send(url, data, 0);
    };

    //削除ボタン
    me.cmdDel_Click = function () {
        var url = me.id + "/" + "cmdDel_Click";

        var data = {
            cmbPGNM: $(".FrmPassMente.cmbPGNM").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] == "I0001") {
                    $(".FrmPassMente.cmbPGNM").trigger("focus");
                    me.clsComFnc.FncMsgBox("I0001");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                $(".FrmPassMente.cmbPGNM").trigger("focus");
                me.clsComFnc.FncMsgBox("I0004");
                me.subClearForm();
            }
        };
        me.ajax.send(url, data, 0);
    };

    //プログラム名変更時
    me.cmbPGNM_Leave = function () {
        var cmbPGNM = $(".FrmPassMente.cmbPGNM").val();
        if (!cmbPGNM) {
            return;
        }

        var url = me.id + "/fncGetPass";
        var data = {
            cmbPGNM: cmbPGNM,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                if (result["row"] == 0) {
                    $(".FrmPassMente.txtPass1").val("");
                } else {
                    //パスワード設定
                    $(".FrmPassMente.txtPass1").val(
                        me.clsComFnc.FncNv(result["data"][0]["PASS"])
                    );
                }
                $(".FrmPassMente.txtPass1").trigger("focus");
            }
        };
        me.ajax.send(url, data, 0);
    };

    //関数/プロシージャ
    //画面項目クリア
    me.subClearForm = function () {
        //コンボ
        $(".FrmPassMente.cmbPGNM").val("");
        $(".FrmPassMente.cmbPGNM").attr("disabled", false);
        $(".FrmPassMente.cmbPGNM").selectedIndex = -1;

        //テキスト
        $(".FrmPassMente.txtPass1").val("");
        $(".FrmPassMente.txtPass2").val("");
        $(".FrmPassMente.txtPass1").attr("disabled", false);
        $(".FrmPassMente.txtPass2").attr("disabled", false);

        //ボタン
        $(".FrmPassMente.cmdCan").button("enable");
        $(".FrmPassMente.cmdReg").button("enable");
        $(".FrmPassMente.cmdDel").button("enable");
    };

    //入力チェック
    //パラメータ：intFLG(1和非1的情况)
    me.fncInputChk = function (intFLG) {
        var cmbPGNM = $.trim($(".FrmPassMente.cmbPGNM").val());
        var txtPass1 = $.trim($(".FrmPassMente.txtPass1").val());
        var txtPass2 = $.trim($(".FrmPassMente.txtPass2").val());

        //プログラム名 必須チェック
        if (cmbPGNM == "" || cmbPGNM == null) {
            me.clsComFnc.ObjFocus = $(".FrmPassMente.cmbPGNM");
            me.clsComFnc.FncMsgBox("W0001", "プログラム名");
            return false;
        }

        //パスワード
        if (intFLG == 1) {
            //パスワード必須チェック
            if (txtPass1 == "") {
                me.clsComFnc.ObjFocus = $(".FrmPassMente.txtPass1");
                me.clsComFnc.FncMsgBox("W0001", "パスワード");
                return false;
            }
            //比較
            if (txtPass1 != txtPass2) {
                me.clsComFnc.ObjFocus = $(".FrmPassMente.txtPass1");
                me.clsComFnc.FncMsgBox("W9999", "パスワードが一致していません");
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
    var o_JKSYS_FrmPassMente = new JKSYS.FrmPassMente();
    o_JKSYS_FrmPassMente.load();
    o_JKSYS_JKSYS.FrmPassMente = o_JKSYS_FrmPassMente;
});
