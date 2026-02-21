/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151021           #2229						   依頼                              li
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmGetujiSime");

R4.FrmGetujiSime = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmGetujiSime";
    me.sys_id = "R4K";
    me.cboYM = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmGetujiSime.cmdAction",
        type: "button",
        handle: "",
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };
    // ========== コントロール end ==========
    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmGetujiSime.cmdAction").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.continueFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.cancelsel;
        me.clsComFnc.MessageBox(
            "月次締処理を実行します。よろしいですか？",
            "確認",
            me.clsComFnc.MessageBoxButtons.OKCancel,
            me.clsComFnc.MessageBoxIcon.Question,
            me.clsComFnc.MessageBoxDefaultButton.Button1
        );
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmGetujiSime_load();
    };
    me.cancelsel = function () {
        return;
    };
    me.continueFnc = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncCmdAct_Click";
        var tmpData = {
            cboYM: $(".FrmGetujiSime.cboYM").val().toString().trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //---20151021 li INS S.
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.continueFncYes;
                //---20151021 li INS E.
                me.clsComFnc.MessageBox(
                    "処理が正常に終了しました。",
                    me.clsComFnc.GSYSTEM_NAME,
                    me.clsComFnc.MessageBoxButtons.OK,
                    me.clsComFnc.MessageBoxIcon.None,
                    me.clsComFnc.MessageBoxDefaultButton.Button1
                );
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, tmpData, 1);
    };

    //---20151021 li INS S.
    me.continueFncYes = function () {
        me.FrmGetujiSime_load();
    };
    //---20151021 li INS E.
    me.FrmGetujiSime_load = function () {
        $(".FrmGetujiSime.cboYM").prop("disabled", "disabled");

        var url = me.sys_id + "/" + me.id + "/" + "fncFrmGetujiSime_load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    $tmpDate = result["data"][0]["TOUGETU"].substr(0, 7);
                    me.cboYMState = $tmpDate;
                    $(".FrmGetujiSime.cboYM").val($tmpDate);
                } else {
                    var myDate = new Date();
                    var tmpMonth = (myDate.getMonth() + 1).toString();
                    if (tmpMonth.length < 2) {
                        tmpMonth = "0" + tmpMonth.toString();
                    }
                    var tmpNowDate =
                        myDate.getFullYear().toString() +
                        "/" +
                        tmpMonth.toString();
                    $(".FrmGetujiSime.cboYM").val(tmpNowDate);
                    $(".FrmGetujiSime.cmdAction").button("disable");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 1);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    var o_R4_FrmGetujiSime = new R4.FrmGetujiSime();
    o_R4_FrmGetujiSime.load();
});
