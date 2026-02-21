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
Namespace.register("R4.FrmEverydayDLFS");

R4.FrmEverydayDLFS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========
    //-- 20151117 li INS S.
    me.id = "FrmEverydayDLFS";
    me.sys_id = "R4K";
    //-- 20151117 li INS E.
    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmEverydayDLFS.Button1",
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
    $(".FrmEverydayDLFS.Button1").click(function () {
        var url = "R4K/FrmEverydayDLFS/buttonclick";

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
            $(".FrmEverydayDLFS.R4-content").append(
                '<div class="FrmEverydayDLFS Dialog"></div>'
            );
            $(".FrmEverydayDLFS.Dialog").dialog({
                autoOpen: false,
                modal: true,
                resizable: false,
                width: 1105,
                height: 600,
                open: function () {},
                close: function () {
                    $(".FrmEverydayDLFS.Dialog").remove();
                },
            });
            $(".FrmEverydayDLFS.Dialog").html(result);
            $(".FrmEverydayDLFS.Dialog").dialog(
                "option",
                "title",
                "ダウンロード実行状況確認"
            );
            $(".FrmEverydayDLFS.Dialog").dialog("open");
        };
        var url = me.sys_id + "/" + "frmDLStateCheck";
        me.ajax.send(url, "data", 0);
    };
    //-- 20151117 li INS E.

    me.buttonable = function () {
        $(".FrmEverydayDLFS.Button1").button("enable");
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

        $(".FrmEverydayDLFS.Button1").trigger("focus");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmEverydayDLFS = new R4.FrmEverydayDLFS();
    o_R4_FrmEverydayDLFS.load();
});
