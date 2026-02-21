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
 * 20141202          NO.22                           スタイルシートの調整                           fanzhengzhou
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH04");

gdmz.SDH.SDH04 = function () {
    var me = new gdmz.base.panel_dialog();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH04";
    me.sys_id = "SDH";

    //-----20141202 NO.22 fanzhengzhou  upd  s
    //me.width = 800;
    me.width = 830;
    //-----20141202 NO.22 fanzhengzhou  upd  e
    //20180201 lqs UPD S
    // me.height = 430;
    me.height = 450;
    //20180201 lqs UPD E

    me.dialog_title = "任意保険＆クレジット情報";

    me.html_id = ".sdh.sdh04.dialog";
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
    var o_SDH_SDH04 = new gdmz.SDH.SDH04();
    o_SDH_SDH04.load();

    o_HMSS_Master.SDH.SDH04 = o_SDH_SDH04;
    o_SDH_SDH04.SDH = o_HMSS_Master.SDH;
});
