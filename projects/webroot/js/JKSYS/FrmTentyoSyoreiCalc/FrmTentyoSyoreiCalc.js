/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmTentyoSyoreiCalc");

JKSYS.FrmTentyoSyoreiCalc = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmTentyoSyoreiCalc";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.dtpYM = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmTentyoSyoreiCalc.btnAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmTentyoSyoreiCalc.dtpYM",
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

    //年月blur
    $(".FrmTentyoSyoreiCalc.dtpYM").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmTentyoSyoreiCalc.dtpYM")) == false) {
            $(".FrmTentyoSyoreiCalc.dtpYM").val(me.dtpYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmTentyoSyoreiCalc.dtpYM").trigger("focus");
                    $(".FrmTentyoSyoreiCalc.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmTentyoSyoreiCalc.dtpYM").trigger("focus");
                        $(".FrmTentyoSyoreiCalc.dtpYM").select();
                    }, 0);
                }
            }
            if (me.dtpYM == "") {
                $(".FrmTentyoSyoreiCalc.btnAction").button("disable");
            }
        } else {
            $(".FrmTentyoSyoreiCalc.btnAction").button("enable");
        }
    });
    //実行ﾎﾞﾀﾝクリック
    $(".FrmTentyoSyoreiCalc.btnAction").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnAction_Click;
        me.clsComFnc.FncMsgBox("QY005");
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
        //フォームロード
        me.FrmTentyoSyoreiCalc_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォーム初期化
     '関 数 名：FrmTentyoSyoreiCalc_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FrmTentyoSyoreiCalc_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "FrmTentyoSyoreiCalc_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmTentyoSyoreiCalc").ympicker("disable");
                $(".FrmTentyoSyoreiCalc").attr("disabled", true);
                $(".FrmTentyoSyoreiCalc button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["data"]["SYORI_YM"]) {
                me.dtpYM = me.clsComFnc.FncNv(result["data"]["SYORI_YM"]);
                $(".FrmTentyoSyoreiCalc.dtpYM").val(me.dtpYM);
                $(".FrmTentyoSyoreiCalc.dtpYM").trigger("focus");
                $(".FrmTentyoSyoreiCalc.dtpYM").select();
            }
        };
        me.ajax.send(url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：実行ボタンクリック
     '関 数 名：fncbtnAction
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnAction_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var data = {
            dtpYM: $(".FrmTentyoSyoreiCalc.dtpYM").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //完了メッセージ表示
                me.clsComFnc.FncMsgBox("I0014");
            } else {
                if (result["error"] == "W0008") {
                    me.clsComFnc.FncMsgBox("W0008", result["message"]);
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["message"]);
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
    o_FrmTentyoSyoreiCalc_FrmTentyoSyoreiCalc = new JKSYS.FrmTentyoSyoreiCalc();
    o_FrmTentyoSyoreiCalc_FrmTentyoSyoreiCalc.load();
});
