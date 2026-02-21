/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                                      担当
 * YYYYMMDD            #ID                          XXXXXX                                                   GSDL
 * 20201117            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。   lqs
 * * ----------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmTRKDownLoadFS");

R4.FrmTRKDownLoadFS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmTRKDownLoadFS";
    me.sys_id = "R4G";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmTRKDownLoadFS.Button1",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmTRKDownLoadFS.cboT_YoteiBi",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //予定日
        var currentDay = new Date();
        $(".FrmTRKDownLoadFS.cboT_YoteiBi").datepicker(
            "setDate",
            currentDay.getFullYear +
                currentDay.getMonth +
                (currentDay.getDate + 1)
        );
        $(".FrmTRKDownLoadFS.Button1").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmTRKDownLoadFS.cboT_YoteiBi").on("blur", function () {
        if (
            me.clsComFnc.CheckDate($(".FrmTRKDownLoadFS.cboT_YoteiBi")) == false
        ) {
            var myDate = new Date();
            $(".FrmTRKDownLoadFS.cboT_YoteiBi").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201117 lqs upd S
            // $(".FrmTRKDownLoadFS.cboT_YoteiBi").trigger("focus");
            // $(".FrmTRKDownLoadFS.cboT_YoteiBi").select();
            window.setTimeout(function () {
                $(".FrmTRKDownLoadFS.cboT_YoteiBi").trigger("focus");
                $(".FrmTRKDownLoadFS.cboT_YoteiBi").select();
            }, 0);
            // 20201117 lqs upd E
            $(".FrmTRKDownLoadFS.Button1").button("disable");
            return;
        } else {
            $(".FrmTRKDownLoadFS.Button1").button("enable");
        }
    });

    $(".FrmTRKDownLoadFS.cboT_YoteiBi").on("keydown", function (e) {
        var key = e.which;
        if (key == 9 || key == 13) {
            $(".FrmTRKDownLoadFS.Button1").button("enable");
            $(".FrmTRKDownLoadFS.Button1").trigger("focus");
        }
    });

    $(".FrmTRKDownLoadFS.Button1").click(function () {
        $(".FrmTRKDownLoadFS.Button1").button("disable");
        data_array = {
            status: 9,
            cboT_YoteiBi: $(".FrmTRKDownLoadFS.cboT_YoteiBi").val(),
        };
        url = me.sys_id + "/" + "FrmTRKDownLoadFS/buttonclick";
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
        me.ajax.beforeLogin = me.buttonable;
    });

    me.buttonable = function () {
        $(".FrmTRKDownLoadFS.Button1").button("enable");
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmTRKDownLoadFS = new R4.FrmTRKDownLoadFS();
    o_R4_FrmTRKDownLoadFS.load();
});
