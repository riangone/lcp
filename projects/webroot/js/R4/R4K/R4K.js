/**
 * R4K
 * @alias  R4K
 * @author FCSDL
 */

Namespace.register("R4K.R4K");

R4K.R4K = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "R4K";
    me.sys_id = "R4K";

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

        //$(".R4K.R4K-loading-icon").show();
        var frmId = "FrmR4KMainMenu";
        var url = frmId;
        // + "/index";
        url = "R4K" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".R4K.R4K-layout-west").html(result);
                $(".R4K.R4K-loading-icon").hide();
                $(".ui-widget-content.R4K.R4K-layout-west").css(
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
    me.Shift_TabKeyDown = function (form_id) {
        var $inp = $(".Tab[tabindex]");
        if (form_id) {
            $inp = $("." + form_id).find(".Tab[tabindex]");
        }

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == true) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                $inp_enabled = $inp_enabled.filter(":visible");
                var nxtIdx = Number($inp_enabled.index(this));
                if (nxtIdx == 0) {
                    //first one : init
                    nxtIdx = $inp_enabled.length;
                }
                $inp_enabled.eq(nxtIdx - 1).select();
                $inp_enabled.eq(nxtIdx - 1).focus();
            }
        });
    };
    //Tabキーのバインド
    me.TabKeyDown = function (form_id) {
        var $inp = $(".Tab[tabindex]");
        if (form_id) {
            $inp = $("." + form_id).find(".Tab[tabindex]");
        }

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });

        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == false) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                $inp_enabled = $inp_enabled.filter(":visible");
                var nxtIdx = Number($inp_enabled.index(this)) + 1;
                if (nxtIdx == $inp_enabled.length) {
                    //last one : init
                    nxtIdx = 0;
                }
                $inp_enabled.eq(nxtIdx).select();
                $inp_enabled.eq(nxtIdx).focus();

                e.stopPropagation();
            }
        });
    };
    //Enterキーのバインド
    me.EnterKeyDown = function (form_id) {
        var $inp = $(".Enter[tabindex]");
        if (form_id) {
            $inp = $("." + form_id).find(".Enter[tabindex]");
        }

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();

                    var $inp_enabled = $inp.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
                    var nxtIdx = Number($inp_enabled.index(this)) + 1;
                    if (nxtIdx == $inp_enabled.length) {
                        //last one : init
                        nxtIdx = 0;
                    }
                    $inp_enabled.eq(nxtIdx).select();
                    $inp_enabled.eq(nxtIdx).focus();
                }
            }
        });
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_R4K_R4K = new R4K.R4K();
    o_R4K_R4K.load();
});
