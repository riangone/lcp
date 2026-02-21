/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("gdmz.base.panel_dialog");

gdmz.base.panel_dialog = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.dialog_title = "";

    me.handle = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    me.receive = function (data) {
        $(me.parent).html(data);
        me.do_resize();
    };

    me.load_complete = function () {
        me.init_control();
        me.open();
    };

    me.open = function () {
        $(me.html_id).dialog("open");
    };

    var base_init_control = me.init_control;

    me.init_control = function () {
        console.log("me.init_control panel_dialog");

        base_init_control();

        $(me.html_id).dialog({
            autoOpen: false,
            height: me.height,
            width: me.width,
            modal: true,
            title: me.dialog_title,
            resizable: false,
            buttons: {
                OK: function () {
                    me.ok_click();
                },
                Cancel: function () {
                    me.cancel_click();
                },
            },
            close: function () {},
        });
    };

    me.do_ok_handle = function () {};

    me.do_cancel_handle = function () {};

    me.ok_click = function () {
        if (me.do_ok_handle) {
            me.do_ok_handle();
        }

        $(me.html_id).dialog("close");
    };

    me.cancel_click = function () {
        if (me.do_cancel_handle) {
            me.do_cancel_handle();
        }

        $(me.html_id).dialog("close");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};
