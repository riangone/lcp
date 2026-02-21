/**
 * PPRM
 * @alias  PPRM
 * @author FCSDL
 */

Namespace.register("PPRM.PPRM");

PPRM.PPRM = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "PPRM";
    me.sys_id = "PPRM";

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

        //$(".PPRM.PPRM-loading-icon").show();
        var frmId = "FrmPPRMMainMenu";
        var url = frmId;
        // + "/index";
        url = "PPRM" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".PPRM.PPRM-layout-west").html(result);
                $(".PPRM.PPRM-loading-icon").hide();
                $(".ui-widget-content.PPRM.PPRM-layout-west").css(
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
    o_PPRM_PPRM = new PPRM.PPRM();
    o_PPRM_PPRM.load();
});
