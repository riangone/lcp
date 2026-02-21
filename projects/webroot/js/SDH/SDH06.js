/**
 * 説明：
 *
 *管理担当履歴ダイアログ
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

Namespace.register("gdmz.SDH.SDH06");

gdmz.SDH.SDH06 = function () {
    var me = new gdmz.base.panel_dialog();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH06";
    me.sys_id = "SDH";

    //me.width = 280;
    //me.height = 150;
    //	me.width = 330;
    me.width = 550;
    me.height = 180;

    me.dialog_title = "担当変更履歴";

    me.html_id = ".sdh.sdh06.dialog";
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

    var base_load = me.load;
    me.load = function () {
        base_load();
    };

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

    me.open = function () {
        if (!me.data) {
            return;
        }

        var B1_KATACHGDAY = "";
        var B2_KATACHGDAY = "";
        var B3_KATACHGDAY = "";
        if (me.data[0]["B1_KATACHGDAY"].length == 8) {
            B1_KATACHGDAY =
                me.data[0]["B1_KATACHGDAY"].substr(0, 4) +
                "/" +
                me.data[0]["B1_KATACHGDAY"].substr(4, 2) +
                "/" +
                me.data[0]["B1_KATACHGDAY"].substr(6, 2);
        }
        if (me.data[0]["B2_KATACHGDAY"].length == 8) {
            B2_KATACHGDAY =
                me.data[0]["B2_KATACHGDAY"].substr(0, 4) +
                "/" +
                me.data[0]["B2_KATACHGDAY"].substr(4, 2) +
                "/" +
                me.data[0]["B2_KATACHGDAY"].substr(6, 2);
        }
        if (me.data[0]["B3_KATACHGDAY"].length == 8) {
            B3_KATACHGDAY =
                me.data[0]["B3_KATACHGDAY"].substr(0, 4) +
                "/" +
                me.data[0]["B3_KATACHGDAY"].substr(4, 2) +
                "/" +
                me.data[0]["B3_KATACHGDAY"].substr(6, 2);
        }

        //20171121 Add Start
        if (B1_KATACHGDAY == "" && B2_KATACHGDAY == "" && B3_KATACHGDAY == "") {
            return;
        }
        //20171121 Add End

        $(".sdh.sdh06.lbl_B1_KATANNM.value").html(me.data[0]["B1_KATANNM"]);
        $(".sdh.sdh06.lbl_B2_KATANNM.value").html(me.data[0]["B2_KATANNM"]);
        $(".sdh.sdh06.lbl_B3_KATANNM.value").html(me.data[0]["B3_KATANNM"]);
        $(".sdh.sdh06.lbl_HAN_BUSMANCD.value").html(me.data[0]["TANTOSYA_NM"]);
        $(".sdh.sdh06.lbl_B1_KATACHGDAY.value").html(B1_KATACHGDAY);
        $(".sdh.sdh06.lbl_B2_KATACHGDAY.value").html(B2_KATACHGDAY);
        $(".sdh.sdh06.lbl_B3_KATACHGDAY.value").html(B3_KATACHGDAY);
        $(me.html_id).dialog("option", "title", me.dialog_title);
        $(me.html_id).dialog("open");
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
    var o_SDH_SDH06 = new gdmz.SDH.SDH06();
    o_SDH_SDH06.load();

    o_HMSS_Master.SDH.SDH06 = o_SDH_SDH06;
    o_SDH_SDH06.SDH = o_HMSS_Master.SDH;
});
