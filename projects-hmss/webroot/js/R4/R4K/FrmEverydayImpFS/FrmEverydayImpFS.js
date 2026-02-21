/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151117           #2277                        Q&A                              LI
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmEverydayImpFS");

R4.FrmEverydayImpFS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========
    //-- 20151117 li INS S.
    me.id = "FrmEverydayImpFS";
    me.sys_id = "R4K";
    //-- 20151117 li INS E.
    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // me.blnFlg = false;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmEverydayImpFS.Button1",
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
    $(".FrmEverydayImpFS.Button1").click(function () {
        var url = "R4K/FrmEverydayImpFS/buttonclick";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                //-- 20151117 li UPD S.
                //me.clsComFnc.MsgBoxBtnFnc.Close = me.buttonable;
                //me.clsComFnc.MessageBox('ダウンロード実行状況確認画面から該当処理の状況を確認してください。', "R4", me.clsComFnc.MessageBoxButtons.OK, me.clsComFnc.MessageBoxIcon.Information);
                me.loadFrmDLStateCheck();
                //-- 20151117 li UPD E.
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
        me.ajax.beforeLogin = me.buttonable;
    });

    //-- 20151117 li INS S.
    me.loadFrmDLStateCheck = function () {
        me.ajax.receive = function (result) {
            $(".FrmEverydayImpFS.R4-content").append(
                '<div class="FrmEverydayImpFS Dialog"></div>'
            );
            $(".FrmEverydayImpFS.Dialog").dialog({
                autoOpen: false,
                modal: true,
                resizable: false,
                width: 1105,
                height: 600,
                open: function () {},
                close: function () {
                    $(".FrmEverydayImpFS.Dialog").remove();
                },
            });
            $(".FrmEverydayImpFS.Dialog").html(result);
            $(".FrmEverydayImpFS.Dialog").dialog(
                "option",
                "title",
                "ダウンロード実行状況確認"
            );
            $(".FrmEverydayImpFS.Dialog").dialog("open");
        };
        var url = me.sys_id + "/" + "frmDLStateCheck";
        me.ajax.send(url, "data", 0);
    };
    //-- 20151117 li INS E.

    me.buttonable = function () {
        $(".FrmEverydayImpFS.Button1").button("enable");
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //初期処理
    var base_load = me.load;

    me.load = function () {
        base_load();

        $(".FrmEverydayImpFS.Button1").trigger("focus");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmEverydayImpFS = new R4.FrmEverydayImpFS();
    o_R4_FrmEverydayImpFS.load();
});
