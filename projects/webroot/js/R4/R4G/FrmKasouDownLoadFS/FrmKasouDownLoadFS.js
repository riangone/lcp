/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmKasouDownLoadFS");

R4.FrmKasouDownLoadFS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmKasouDownLoadFS";
    me.sys_id = "R4G";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKasouDownLoadFS.Button1",
        type: "button",
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

    //実行ボタンクリック
    $(".FrmKasouDownLoadFS.Button1").click(function () {
        //注文番号の入力チェック
        var Chumon_NO2 = $(".FrmKasouDownLoadFS.txtCmnNo").val();
        var Chumon_NO = me.clsComFnc.FncTextCheck(
            $(".FrmKasouDownLoadFS.txtCmnNo"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR1
        );
        //必須ｴﾗｰ
        if (Chumon_NO == -1) {
            me.clsComFnc.ObjFocus = $(".FrmKasouDownLoadFS.txtCmnNo");
            me.clsComFnc.FncMsgBox("W0001", "注文書番号");
            return;
        }
        //入力値ｴﾗｰ
        if (Chumon_NO == -2) {
            me.clsComFnc.ObjFocus = $(".FrmKasouDownLoadFS.txtCmnNo");
            me.clsComFnc.FncMsgBox("W0002", "注文書番号");
            return;
        }
        //桁数ｴﾗｰ
        if (Chumon_NO == -3) {
            me.clsComFnc.ObjFocus = $(".FrmKasouDownLoadFS.txtCmnNo");
            me.clsComFnc.FncMsgBox("W0003", "注文書番号");
            return;
        }
        $(".FrmKasouDownLoadFS.Button1").button("disable");
        data_array = {
            status: 8,
            Chumon_NO1: Chumon_NO2,
        };
        url = me.sys_id + "/FrmKasouDownLoadFS/buttonclick";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["flag"] == true) {
                if (result["msg"] == true) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.buttonable;
                    me.clsComFnc.MessageBox(
                        "ロック解除画面から該当処理の状況を確認してください。",
                        "R4",
                        me.clsComFnc.MessageBoxButtons.OK,
                        me.clsComFnc.MessageBoxIcon.Information
                    );
                } else {
                    errorcode = result["msg"]["error_code"];
                    errorinfo = result["msg"]["message"];
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.buttonable;
                    me.clsComFnc.FncMsgBox("E9999", errorinfo);
                }
            } else {
                errorcode = result["msg"]["error_code"];
                errorinfo = result["msg"]["message"];
                me.clsComFnc.MsgBoxBtnFnc.Close = me.buttonable;
                me.clsComFnc.FncMsgBox("E9999", errorinfo);
            }
        };
        me.ajax.send(url, data_array, 0);
        //20131213 fan
        //if session dateout,make the button enable before dialog poping.
        me.ajax.beforeLogin = me.buttonable;
    });

    me.buttonable = function () {
        $(".FrmKasouDownLoadFS.Button1").button("enable");
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //初期処理
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        $(".FrmKasouDownLoadFS.txtCmnNo").trigger("focus");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKasouDownLoadFS = new R4.FrmKasouDownLoadFS();
    o_R4_FrmKasouDownLoadFS.load();
});
