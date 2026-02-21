/**
 * R4G
 * @alias  R4G
 * @author FCSDL
 */

Namespace.register("R4G.R4G");

R4G.R4G = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "R4G";
    me.sys_id = "R4G";

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

        //$(".R4G.R4G-loading-icon").show();
        var frmId = "FrmR4GMainMenu";
        var url = frmId;
        // + "/index";
        url = "R4G" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".R4G.R4G-layout-west").html(result);
                $(".R4G.R4G-loading-icon").hide();
                // $(".ui-widget-content.R4G.R4G-layout-west").css("overflow","scroll");
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
    o_R4_R4 = new R4G.R4G();
    o_R4_R4.load();
});
