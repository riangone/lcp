/**
 * 説明：
 *
 *
 * @author jinmingai
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                       Feature/Bug                    内容                          担当
 * YYYYMMDD           #ID                               XXXXXX                   FCSDL
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH03");

gdmz.SDH.SDH03 = function () {
    var me = new gdmz.base.panel_dialog();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH03";
    me.sys_id = "SDH";

    me.width = 800;
    me.height = me.ratio === 1.5 ? 550 : 600;

    me.dialog_title = "注文書情報";

    me.html_id = ".sdh.sdh03.dialog";
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
            width: 900, //me.width,
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
    var o_SDH_SDH03 = new gdmz.SDH.SDH03();
    o_SDH_SDH03.load();

    o_HMSS_Master.SDH.SDH03 = o_SDH_SDH03;
    o_SDH_SDH03.SDH = o_HMSS_Master.SDH;
});
