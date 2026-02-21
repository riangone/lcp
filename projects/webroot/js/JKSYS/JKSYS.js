/**
 * JKSYS
 * @alias  JKSYS
 * @author FCSDL
 */
Namespace.register("JKSYS.JKSYS");

JKSYS.JKSYS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "JKSYS";
    me.sys_id = "JKSYS";

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
        var frmId = "FrmJKSYSMainMenu";
        var url = frmId;
        url = "JKSYS" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".JKSYS.JKSYS-layout-west").html(result);
                $(".JKSYS.JKSYS-loading-icon").hide();
                $(".ui-widget-content.JKSYS.JKSYS-layout-west").css(
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
    //ShiftキーとTabキーのバインド
    me.Shift_TabKeyDown = function () {
        var $inp = $(".Tab[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == true) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                // 20250424 lujunxia ins s
                $inp_enabled = $inp_enabled.filter(":visible");
                // 20250424 lujunxia ins e
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
    me.TabKeyDown = function () {
        var $inp = $(".Tab[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
            );
        });

        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == false) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                // 20250424 lujunxia ins s
                $inp_enabled = $inp_enabled.filter(":visible");
                // 20250424 lujunxia ins e
                var nxtIdx = Number($inp_enabled.index(this)) + 1;
                if (nxtIdx == $inp_enabled.length) {
                    //last one : init
                    nxtIdx = 0;
                }
                $inp_enabled.eq(nxtIdx).select();
                $inp_enabled.eq(nxtIdx).focus();
            }
        });
    };
    //Enterキーのバインド
    me.EnterKeyDown = function () {
        var $inp = $(".Enter[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
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
                    // 20250424 lujunxia ins s
                    $inp_enabled = $inp_enabled.filter(":visible");
                    // 20250424 lujunxia ins e
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
    o_JKSYS_JKSYS = new JKSYS.JKSYS();
    o_JKSYS_JKSYS.load();
});
