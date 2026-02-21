/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("gdmz.base.panel");

gdmz.base.panel = function () {
    var me = new Object();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "";
    me.sys_id = "";

    me.width = 800;
    me.height = 600;

    me.controls = new Array();

    me.ratio = window.devicePixelRatio || 1;

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

    me.init_control = function () {
        for (var i = 0; i < me.controls.length; i++) {
            $(me.controls[i].id).data(me.controls[i]);
            switch (me.controls[i].type) {
                case "button":
                    //20140916 zhenghuiyun update s
                    // $(me.controls[i].id).button();
                    $(me.controls[i].id).button({
                        icon: me.controls[i].icons,
                    });
                    //20140916 zhenghuiyun update e

                    if (me.controls[i].enable == "false") {
                        $(me.controls[i].id).button("disable");
                    }
                    break;
                case "datepicker":
                    $(me.controls[i].id).datepicker({
                        showOn: "button",
                        buttonImage: "css/jquery/images/calendar.gif",
                        buttonImageOnly: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "yy/mm/dd",
                        yearRange: "1900:2035",
                        // gotoCurrent: true
                        showButtonPanel: true,
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        onChangeMonthYear: function (_year, _month, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                case "datepicker4":
                    $(me.controls[i].id).ympicker({
                        dateFormat: "yy",
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        beforeShowDay: function (input, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                case "datepicker3":
                    $(me.controls[i].id).ympicker({
                        dateFormat: "yymm",
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        onChangeMonthYear: function (_year, _month, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                case "datepicker1":
                    $(me.controls[i].id).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showWeek: true,
                        dateFormat: "yy-mm-dd",
                        yearRange: "1900:2035",
                        // gotoCurrent: true
                        showButtonPanel: true,
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        onChangeMonthYear: function (_year, _month, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                case "datepicker2":
                    $(me.controls[i].id).datepicker({
                        showOn: "button",
                        buttonImage: "css/jquery/images/calendar.gif",
                        buttonImageOnly: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "yy/mm",
                        yearRange: "1900:2035",
                        // gotoCurrent: true
                        showButtonPanel: true,
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        onChangeMonthYear: function (_year, _month, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                // 20240315 LQS INS S
                case "datepicker5":
                    $(me.controls[i].id).datepicker({
                        showOn: "button",
                        buttonImage: "css/jquery/images/calendar.gif",
                        buttonImageOnly: true,
                        changeYear: true,
                        changeMonth: true,
                        dateFormat: "yy/mm/dd",
                        yearRange: "1900:2035",
                        // gotoCurrent: true,
                        showButtonPanel: true,
                        beforeShowDay: function (date) {
                            var d = date.getDay();
                            if (d === 0 || d === 6) {
                                return [false];
                            }
                            return [true];
                        },
                        beforeShow: function (input, inst) {
                            me.setDatepickerSelectIds(input, inst);
                        },
                        onChangeMonthYear: function (_year, _month, inst) {
                            me.setDatepickerSelectIds(null, inst);
                        },
                    });
                    break;
                // 20240315 LQS INS S
                //$(me.controls[i].id).datepicker("option", "dateFormat", "yy/mm/dd");
            }

            //2014/08/22 zhenghuiyun insert start

            if (me.controls[i].style) {
                for (key in me.controls[i].style) {
                    $(me.controls[i].id).css(key, me.controls[i].style[key]);
                }
            }

            //2014/08/22 zhenghuiyun insert end
        }
    };
    me.setDatepickerSelectIds = function (_input, inst) {
        setTimeout(function () {
            const inputId =
                Math.random().toString(36).substring(2, 9) + "-" + Date.now();
            var $dpDiv = $(inst.dpDiv);
            $dpDiv
                .find(".ui-datepicker-month")
                .attr("id", "datepicker-month-" + inputId);
            $dpDiv
                .find(".ui-datepicker-year")
                .attr("id", "datepicker-year-" + inputId);
        }, 10);
    };
    me.load = function () {
        me.init_control();

        //2013/11/21 zhenghuiyun update start
        $(".numeric").numeric();
        //2013/11/21 zhenghuiyun update end
        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                $(mutation.addedNodes)
                    .find(
                        "select.ui-datepicker-month:not([name]), select.ui-datepicker-year:not([name])"
                    )
                    .each(function () {
                        var $select = $(this);
                        var name = $select.is(".ui-datepicker-month")
                            ? "datepicker-month"
                            : "datepicker-year";
                        $select.attr("name", name);
                    });
            });
        }).observe(document.body, {
            childList: true,
            subtree: true,
            attributes: false,
            characterData: false,
        });
        //20240612 zhangxiaolei add s
        if (me.id && me.id != me.sys_id) {
            var pageNM = me.id.split("/");
            $(
                "[class*=" + pageNM[pageNM.length - 1] + " i] input:not(.Login)"
            ).prop("autocomplete", "off");
            if (
                $("." + pageNM[pageNM.length - 1] + ' input[type="password"]')
                    .length == 0
            ) {
                $("." + pageNM[pageNM.length - 1])
                    .eq(0)
                    .prepend('<input type="text" style="display:none" />')
                    .prepend(
                        '<form><input type="password" style="display:none" autocomplete="on" /></form>'
                    );
            }
        }
        //20240612 zhangxiaolei add e
    };

    //2014/08/21 zhenghuiyun insert start

    me.open_dialog = function (
        dialog_id,
        ok_handle,
        cancel_handle,
        selected_data
    ) {
        if (me[dialog_id] == null) {
            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = function (data) {
                // console.log(selected_data);
                $(me.dialog_area).html(data);
                setTimeout(() => {
                    if (ok_handle) {
                        me[dialog_id].do_ok_handle = ok_handle;
                    }
                    if (cancel_handle) {
                        me[dialog_id].do_cancel_handle = cancel_handle;
                    }
                    if (selected_data) {
                        me[dialog_id].data = selected_data;
                    }
                    me[dialog_id].open();
                }, 0);
            };
            // o_ajax.send(me.sys_id + "/" + me.id + "/" + dialog_id, null, "0");
            o_ajax.send(me.sys_id + "/" + dialog_id, selected_data, "0");
        } else {
            if (ok_handle) {
                me[dialog_id].do_ok_handle = ok_handle;
            }
            if (cancel_handle) {
                me[dialog_id].do_cancel_handle = cancel_handle;
            }
            if (selected_data) {
                me[dialog_id].data = selected_data;
            }
            me[dialog_id].open();
        }
    };

    //2014/08/21 zhenghuiyun insert end

    // ==========
    // = メソッド end =
    // ==========
    return me;
};
