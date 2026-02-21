/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                       Feature/Bug                    内容                          担当
 * YYYYMMDD           #ID                               XXXXXX                   FCSDL
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH07");

gdmz.SDH.SDH07 = function () {
    var me = new gdmz.base.panel_dialog();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH07";
    me.sys_id = "SDH";

    me.width = 740;
    me.height = 400;

    me.dialog_title = "入庫履歴";

    me.html_id = ".sdh.sdh07.dialog";
    me.parent = ".sdh.sdh01.dialog_area";

    me.data = null;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    me.init_control = function () {
        $(me.html_id).dialog({
            autoOpen: false,
            height: me.height,
            width: me.width,
            modal: true,
            title: me.dialog_title,
            resizable: false,
        });
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
    var o_SDH_SDH07 = new gdmz.SDH.SDH07();
    o_SDH_SDH07.load();

    o_HMSS_Master.SDH.SDH07 = o_SDH_SDH07;
    o_SDH_SDH07.SDH = o_HMSS_Master.SDH;
});
