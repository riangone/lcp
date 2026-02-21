/**
 * APPM
 * @alias  APPM
 * @author FCSDL
 */

Namespace.register("APPM.APPM");

APPM.APPM = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "APPM";
    me.sys_id = "APPM";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

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

        //$(".APPM.APPM-loading-icon").show();
        var frmId = "FrmAPPMMainMenu";
        var url = frmId;
        // + "/index";
        url = "APPM" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".APPM.APPM-layout-west").html(result);
                $(".APPM.APPM-loading-icon").hide();
                $(".ui-widget-content.APPM.APPM-layout-west").css(
                    "overflow",
                    "scroll"
                );
            },
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
    o_APPM_APPM = new APPM.APPM();
    o_APPM_APPM.load();
});
