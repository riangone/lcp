Namespace.register("JKSYS.FrmSyukkouSeikyuInfoEnt");

JKSYS.FrmSyukkouSeikyuInfoEnt = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "JKSYS";
    me.id = "FrmSyukkouSeikyuInfoEnt";
    me.g_url = me.sys_id + "/" + me.id + "/selSyukkouSeikyuSQL";
    me.grid_id = "#FrmSyukkouSeikyuInfoEnt_sprList";

    me.lastsel = 0;
    me.upsel = "";
    me.nextsel = "";
    me._taishoYM = "";
    //排他日付
    me._updDate = "";
    //固定賃金計
    me.total_sumtotal = [
        "KIHONKYU",
        "CHOUSEIKYU",
        "SYOKUMU_TEATE",
        "KAZOKU_TEATE",
        "TUKIN_TEATE",
        "SYARYOU_TEATE",
        "SYOUREIKIN",
        "ZANGYOU_TEATE",
        "SYUKKOU_TEATE",
        "JIKANSA_TEATE",
    ];
    //会社負担計
    me.total_sump1v1 = [
        "KENKO_HKN_RYO",
        "KAIGO_HKN_RYO",
        "KOUSEINENKIN",
        "JIDOU_TEATE",
        "KOYOU_HKN_RYO",
        "TAISYOKU_NENKIN",
        "ROUSAI_UWA_HKN_RYO",
    ];
    //賞与計
    me.total_sumx1ac1 = [
        "BNS_GK",
        "BNS_KENKO_HKN_RYO",
        "BNS_KAIGO_HKN_RYO",
        "BNS_KOUSEI_NENKIN",
        "BNS_JIDOU_TEATE",
        "BNS_KOYOU_HOKEN",
    ];
    //負担金額計
    me.total_d1o1w1 = ["sumtotal", "SUM(P1:V1)", "SUM(X1:AC1)"];

    me.option = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "BUSYO_NM",
            label: "出向先",
            index: "BUSYO_NM",
            width: 130,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NO",
            label: "番号",
            index: "SYAIN_NO",
            width: 60,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "名称",
            index: "SYAIN_NM",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "HIDDEN",
            label: "HIDDEN",
            index: "HIDDEN",
            width: 100,
            sortable: false,
            hidden: true,
            align: "left",
        },
        {
            name: "sumtotal",
            label: "固定賃金計",
            index: "sumtotal",
            width: 105,
            formatter: "integer",
            sortable: false,
            editable: false,
            //设置字体靠右
            align: "right",
        },
        {
            name: "KIHONKYU",
            label: "基本給",
            index: "KIHONKYU",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            //提取共通方法
                            var key = e.charCode || e.keyCode;
                            if (key == 9 || key == 38 || key == 40) {
                                me.totalCal(e, me.total_sumtotal, "KIHONKYU");
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "KIHONKYU");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "CHOUSEIKYU",
            label: "調整給",
            index: "CHOUSEIKYU",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(e, me.total_sumtotal, "CHOUSEIKYU");
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "CHOUSEIKYU");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SYOKUMU_TEATE",
            label: "職務",
            index: "SYOKUMU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "SYOKUMU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "SYOKUMU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "KAZOKU_TEATE",
            label: "家族",
            index: "KAZOKU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "KAZOKU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "KAZOKU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "TUKIN_TEATE",
            label: "通勤",
            index: "TUKIN_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "TUKIN_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "TUKIN_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SYARYOU_TEATE",
            label: "車両",
            index: "SYARYOU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "SYARYOU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "SYARYOU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SYOUREIKIN",
            label: "奨励",
            index: "SYOUREIKIN",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(e, me.total_sumtotal, "SYOUREIKIN");
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "SYOUREIKIN");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "ZANGYOU_TEATE",
            label: "残業",
            index: "ZANGYOU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "ZANGYOU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "ZANGYOU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SYUKKOU_TEATE",
            label: "出向",
            index: "SYUKKOU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "SYUKKOU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "SYUKKOU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "JIKANSA_TEATE",
            label: "時間差",
            index: "JIKANSA_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumtotal,
                                    "JIKANSA_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumtotal, "JIKANSA_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SUM(P1:V1)",
            label: "会社負担計",
            index: "SUM(P1:V1)",
            width: 105,
            formatter: "integer",
            sortable: false,
            editable: false,
            //设置靠右
            align: "right",
        },
        {
            name: "KENKO_HKN_RYO",
            label: "健康保険",
            index: "KENKO_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "KENKO_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "KENKO_HKN_RYO");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "KAIGO_HKN_RYO",
            label: "介護保険",
            index: "KAIGO_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "KAIGO_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "KAIGO_HKN_RYO");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "KOUSEINENKIN",
            label: "厚生年金",
            index: "KOUSEINENKIN",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "KOUSEINENKIN"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "KOUSEINENKIN");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "JIDOU_TEATE",
            label: "児童手当",
            index: "JIDOU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(e, me.total_sump1v1, "JIDOU_TEATE");
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "JIDOU_TEATE");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "KOYOU_HKN_RYO",
            label: "雇用保険",
            index: "KOYOU_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "KOYOU_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "KOYOU_HKN_RYO");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "TAISYOKU_NENKIN",
            label: "退職年金",
            index: "TAISYOKU_NENKIN",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "TAISYOKU_NENKIN"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sump1v1, "TAISYOKU_NENKIN");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "ROUSAI_UWA_HKN_RYO",
            label: "労災上乗",
            index: "ROUSAI_UWA_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sump1v1,
                                    "ROUSAI_UWA_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sump1v1,
                                "ROUSAI_UWA_HKN_RYO"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "SUM(X1:AC1)",
            label: "賞与計",
            index: "SUM(X1:AC1)",
            width: 105,
            formatter: "integer",
            sortable: false,
            editable: false,
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_GK",
            label: "賞与",
            index: "BNS_GK",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(e, me.total_sumx1ac1, "BNS_GK");
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, me.total_sumx1ac1, "BNS_GK");
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_KENKO_HKN_RYO",
            label: "健康保険料",
            index: "BNS_KENKO_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumx1ac1,
                                    "BNS_KENKO_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sumx1ac1,
                                "BNS_KENKO_HKN_RYO"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_KAIGO_HKN_RYO",
            label: "介護保険料",
            index: "BNS_KAIGO_HKN_RYO",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumx1ac1,
                                    "BNS_KAIGO_HKN_RYO"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sumx1ac1,
                                "BNS_KAIGO_HKN_RYO"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_KOUSEI_NENKIN",
            label: "厚生年金",
            index: "BNS_KOUSEI_NENKIN",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumx1ac1,
                                    "BNS_KOUSEI_NENKIN"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sumx1ac1,
                                "BNS_KOUSEI_NENKIN"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_JIDOU_TEATE",
            label: "児童手当",
            index: "BNS_JIDOU_TEATE",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.totalCal(
                                    e,
                                    me.total_sumx1ac1,
                                    "BNS_JIDOU_TEATE"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sumx1ac1,
                                "BNS_JIDOU_TEATE"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "BNS_KOYOU_HOKEN",
            label: "雇用保険料",
            index: "BNS_KOYOU_HOKEN",
            width: 90,
            //初始化颜色设置
            cellattr: addCellAttr,
            //更改可输入字符设置
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                class: "align_right",
                //添加正则表达式
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40
                            ) {
                                me.totalCal(
                                    e,
                                    me.total_sumx1ac1,
                                    "BNS_KOYOU_HOKEN"
                                );
                            }
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                    //调用共通方法
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(
                                e,
                                me.total_sumx1ac1,
                                "BNS_KOYOU_HOKEN"
                            );
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
            //设置字体靠右
            align: "right",
        },
        {
            name: "D1 + O1 + W1",
            label: "負担金額計",
            index: "D1 + O1 + W1",
            width: 112,
            formatter: "integer",
            sortable: false,
            editable: false,
            //设置字体靠右
            align: "right",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyukkouSeikyuInfoEnt.dtpYM",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyukkouSeikyuInfoEnt.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyukkouSeikyuInfoEnt.cmdChange",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyukkouSeikyuInfoEnt.cmdEntry",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //年月blur:空=>初期値
    $(".FrmSyukkouSeikyuInfoEnt.dtpYM").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmSyukkouSeikyuInfoEnt.dtpYM")) ==
            false
        ) {
            $(".FrmSyukkouSeikyuInfoEnt.dtpYM").val(me._taishoYM);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmSyukkouSeikyuInfoEnt.dtpYM").trigger("focus");
                    $(".FrmSyukkouSeikyuInfoEnt.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").trigger("focus");
                        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").select();
                    }, 0);
                }
            }
            $(".FrmSyukkouSeikyuInfoEnt.cmdSearch").button("disable");
            return;
        } else {
            $(".FrmSyukkouSeikyuInfoEnt.cmdSearch").button("enable");
        }
    });
    //検索ﾎﾞﾀﾝクリック
    $(".FrmSyukkouSeikyuInfoEnt.cmdSearch").click(function () {
        me.cmdSearch_Click();
    });
    //条件変更ﾎﾞﾀﾝクリック
    $(".FrmSyukkouSeikyuInfoEnt.cmdChange").click(function () {
        me.cmdChange_Click();
    });
    //登録ﾎﾞﾀﾝクリック
    $(".FrmSyukkouSeikyuInfoEnt.cmdEntry").click(function () {
        me.cmdEntry_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //20201113 CI INS S
        if ($(window).height() <= 950) {
            // 画面内容较多，IE显示不全，追加纵向滚动条
            $(".JKSYS.JKSYS-layout-center").css("overflow-y", "scroll");
        }
        //20201113 CI INS E
        //出向先値、対象年月選択
        me.Page_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        //画面初期化
        $(".FrmSyukkouSeikyuInfoEnt.cmdChange").button("disable");
        $(".FrmSyukkouSeikyuInfoEnt.cmdEntry").button("disable");

        //スプレッド初期化
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        if ($(window).width() <= 1366) {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                me.ratio === 1.5 ? 1007 : 1068
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 255 : 355
            );
        } else {
            gdmz.common.jqgrid.init(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 1250);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 535);
        }
        me.initSpread();

        var url = me.sys_id + "/" + me.id + "/" + "selShoriYMSQL";
        me.ajax.receive = function (res) {
            var res = eval("(" + res + ")");
            if (!res["result"]) {
                $(".FrmSyukkouSeikyuInfoEnt").ympicker("disable");
                $(".FrmSyukkouSeikyuInfoEnt").attr("disabled", true);
                $(".FrmSyukkouSeikyuInfoEnt button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", res["error"]);
                return;
            }
            //対象年月
            if (res["data"]["ShoriYM"]) {
                me._taishoYM = res["data"]["ShoriYM"];
                $(".FrmSyukkouSeikyuInfoEnt.dtpYM").val(me._taishoYM);
            }
            $(".FrmSyukkouSeikyuInfoEnt.dtpYM").trigger("focus");
            $(".FrmSyukkouSeikyuInfoEnt.dtpYM").select();

            //出向先コンボの設定
            $("<option></option>").appendTo(
                ".FrmSyukkouSeikyuInfoEnt.comSyukkou"
            );

            if (res["data"]["flag"]) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
            } else {
                for (key in res["data"]["ComSyu"]) {
                    $("<option></option>")
                        .val(res["data"]["ComSyu"][key]["KUBUN_CD"])
                        .text(res["data"]["ComSyu"][key]["BUSYO_NM"])
                        .appendTo(".FrmSyukkouSeikyuInfoEnt.comSyukkou");
                }
            }
        };
        me.ajax.send(url, "", 1);
    };
    //スプレッド初期化
    me.initSpread = function () {
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SYAIN_NO",
                    numberOfColumns: 2,
                    titleText: "社員",
                },
                {
                    startColumnName: "sumtotal",
                    numberOfColumns: 11,
                    titleText: "固定賃金",
                },
                {
                    startColumnName: "SUM(P1:V1)",
                    numberOfColumns: 8,
                    titleText: "会社負担",
                },
                {
                    startColumnName: "SUM(X1:AC1)",
                    numberOfColumns: 7,
                    titleText: "賞与",
                },
            ],
        });

        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //添加点击行头不选中
            beforeSelectRow: function (_rowid, e) {
                if ($(me.grid_id).getColProp("KIHONKYU").editable) {
                    var cellIndex = e.target.cellIndex;
                    if (cellIndex == 0) {
                        var selNextId = "#" + me.lastsel + "_KIHONKYU";
                        setTimeout(() => {
                            $(selNextId).trigger("focus");
                            $(selNextId).select();
                        }, 0);

                        return false;
                    }
                }
                return true;
            },
            onSelectRow: function (rowid, _status, e) {
                if ($(me.grid_id).getColProp("KIHONKYU").editable) {
                    if (typeof e != "undefined") {
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_id).jqGrid("saveRow", me.lastsel);
                            me.lastsel = rowid;
                        }
                        var colModel = $(this).jqGrid(
                                "getGridParam",
                                "colModel"
                            ),
                            targetCell = colModel[cellIndex];
                        if (!targetCell.editable) {
                            $(me.grid_id).jqGrid("editRow", rowid, true);
                            setTimeout(() => {
                                $("#" + me.lastsel + "_KIHONKYU").trigger(
                                    "focus"
                                );
                            }, 0);
                        } else {
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                focusField: cellIndex,
                            });
                        }
                    } else {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_id).jqGrid("saveRow", me.lastsel);
                            me.lastsel = rowid;
                        }
                        $(me.grid_id).jqGrid("editRow", rowid, {
                            focusField: false,
                        });
                    }
                    //负数变红
                    me.selRed();
                    //靠右
                    $(me.grid_id)
                        .find(".align_right")
                        .css("text-align", "right");

                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    //键盘事件
                    var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                    if (up_next_sel && up_next_sel.length == 2) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    }
                }
            },
        });
        $(me.grid_id).jqGrid("bindKeys");
        //右クリックを禁止する
        $(me.grid_id).unbind("contextmenu");
    };
    /*
	 '**********************************************************************
	 '処 理 名：検索ボタン
	 '関 数 名：cmdSearch_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdSearch_Click = function () {
        var taishoYM = $(".FrmSyukkouSeikyuInfoEnt.dtpYM").val();
        var comSyukkou = $(".FrmSyukkouSeikyuInfoEnt.comSyukkou").val();
        var data = {
            taishoYM: taishoYM,
            comSyukkou: comSyukkou,
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };
    //
    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"]) {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }

        //対象年月
        me._taishoYM = $(".FrmSyukkouSeikyuInfoEnt.dtpYM").val();

        //更新日取得
        me._updDate = result["updDate"] ? result["updDate"] : "";

        if (result["records"] == 0) {
            //該当データなし
            me.clsComFnc.ObjFocus = $(".FrmSyukkouSeikyuInfoEnt.dtpYM");
            me.clsComFnc.FncMsgBox("I0001");
            return;
        }

        //処理年月取得
        var shoriYM = result["ShoriYM"] ? result["ShoriYM"] : "";

        //画面制御
        if (me._taishoYM < shoriYM) {
            //入力不可（読取専用）
            me.changeSpreadMode(false);
            $(".FrmSyukkouSeikyuInfoEnt.cmdEntry").button("disable");
        } else {
            //入力可
            me.changeSpreadMode(true);
            $(".FrmSyukkouSeikyuInfoEnt.cmdEntry").button("enable");
        }

        //入力制御
        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").ympicker("disable");
        $(".FrmSyukkouSeikyuInfoEnt.comSyukkou").prop("disabled", true);
        $(".FrmSyukkouSeikyuInfoEnt.cmdSearch").button("disable");
        $(".FrmSyukkouSeikyuInfoEnt.cmdChange").button("enable");

        //SPREADにフォーカスを移動させます
        $(me.grid_id).jqGrid("setSelection", 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：スプレッドモード変更
	 '関 数 名：changeSpreadMode
	 '引    数：true：編集モード ; false：読取専用モード
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.changeSpreadMode = function (mode) {
        var allEditCol = me.total_sumtotal
            .concat(me.total_sump1v1)
            .concat(me.total_sumx1ac1);
        for (var i = 0; i < allEditCol.length; i++) {
            $(me.grid_id).setColProp(allEditCol[i], {
                editable: mode,
            });
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録ボタン
	 '関 数 名：cmdEntry_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdEntry_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
        me.clsComFnc.FncMsgBox("QY010");
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録_はいボタン
	 '関 数 名：YesUpdateFnc
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.YesUpdateFnc = function () {
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "updSyukkouSeikyu";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W0018") {
                    //排他エラー
                    me.clsComFnc.ObjFocus = $(
                        ".FrmSyukkouSeikyuInfoEnt.cmdChange"
                    );
                    me.clsComFnc.FncMsgBox(result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //画面初期化
                me.cmdChange_Click();

                //更新完了のメッセージを表示する
                me.clsComFnc.FncMsgBox("I0008");
            }
        };

        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var allDatas = $(me.grid_id).jqGrid("getRowData");
        var taishoYM = $(".FrmSyukkouSeikyuInfoEnt.dtpYM").val();
        var comSyukkou = $(".FrmSyukkouSeikyuInfoEnt.comSyukkou").val();
        var data = {
            new: JSON.stringify(allDatas),
            taisyouym: taishoYM,
            comSyukkou: comSyukkou,
            updDate: me._updDate,
        };
        me.ajax.send(me.updateUrl, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：条件変更ボタン
	 '関 数 名：cmdChange_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdChange_Click = function () {
        //入力制御
        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").ympicker("enable");
        $(".FrmSyukkouSeikyuInfoEnt.comSyukkou").prop("disabled", false);
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmSyukkouSeikyuInfoEnt.cmdSearch").button("enable");
        $(".FrmSyukkouSeikyuInfoEnt.cmdChange").button("disable");
        $(".FrmSyukkouSeikyuInfoEnt.cmdEntry").button("disable");

        //更新日
        me._updDate = "";

        //フォーカス
        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").trigger("focus");
        $(".FrmSyukkouSeikyuInfoEnt.dtpYM").select();
    };

    //添加变色功能提取方法
    me.colorNum = function (_e, str) {
        var strVal = "#" + parseInt(me.lastsel) + "_" + str;

        if ($(strVal).val() != "") {
            var numVal = parseInt($(strVal).val());
        }
        if (numVal < 0) {
            $(strVal).parent().css("color", "red");
            $(strVal).css("color", "red");
        }
        if (numVal >= 0) {
            $(strVal).parent().css("color", "black");
            $(strVal).css("color", "black");
        }
    };
    //keypress共通
    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();
        if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substring(0, 1) + (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                } else if (
                    inputValue == "0" &&
                    keycode >= 49 &&
                    keycode <= 57
                ) {
                    inputValue = (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                }

                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substring(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substring(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substring(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };
    //keyup共通
    me.keyups = function (e) {
        var key = e.charCode || e.keyCode;
        //backspace
        if (key == 8) {
            var inputVal = $.trim($(e.target).val());
            if (inputVal == "-") {
                $(e.target).val("0");
                return false;
            }
        }
    };
    //负数为红色
    me.selRed = function () {
        $(me.grid_id)
            .find(".align_right")
            .each(function () {
                if ($(this).val() < 0) {
                    $(this).css("color", "red");
                }
            });
    };
    //初始化时负数为红色
    function addCellAttr(_rowId, val) {
        var reg = new RegExp(",", "g");
        val = val.replace(reg, "");
        if (val < 0) {
            return "style='color:red'";
        }
    }

    //合计的计算
    me.totalCal = function (e, cal_name, str) {
        var row = $(e.target).closest("tr.jqgrow");
        var rowId = row.attr("id");
        var SyakaiHokenryoKei = 0;
        for (key in cal_name) {
            if ($.trim($("#" + rowId + "_" + cal_name[key]).val()) != "") {
                SyakaiHokenryoKei += parseInt(
                    $.trim($("#" + rowId + "_" + cal_name[key]).val())
                );
            }
        }
        //固定賃金計
        if (cal_name == me.total_sumtotal) {
            $(me.grid_id).jqGrid(
                "setCell",
                rowId,
                "sumtotal",
                SyakaiHokenryoKei
            );
        }
        //会社負担計
        else if (cal_name == me.total_sump1v1) {
            $(me.grid_id).jqGrid(
                "setCell",
                rowId,
                "SUM(P1:V1)",
                SyakaiHokenryoKei
            );
        }
        //賞与計
        else {
            $(me.grid_id).jqGrid(
                "setCell",
                rowId,
                "SUM(X1:AC1)",
                SyakaiHokenryoKei
            );
        }

        //固定賃金計
        var sumtotal = $(e.target)
            .parent()
            .parent()
            .children(
                '[aria-describedby="FrmSyukkouSeikyuInfoEnt_sprList_sumtotal"]'
            )
            .text();
        //会社負担計
        var sump1v1 = $(e.target)
            .parent()
            .parent()
            .children(
                '[aria-describedby="FrmSyukkouSeikyuInfoEnt_sprList_SUM(P1:V1)"]'
            )
            .text();
        //賞与計
        var sumx1ac1 = $(e.target)
            .parent()
            .parent()
            .children(
                '[aria-describedby="FrmSyukkouSeikyuInfoEnt_sprList_SUM(X1:AC1)"]'
            )
            .text();

        //負担金額計
        var d1o1w1 =
            parseInt(sumtotal.replace(/\,/g, "")) +
            parseInt(sump1v1.replace(/\,/g, "")) +
            parseInt(sumx1ac1.replace(/\,/g, ""));
        $(me.grid_id).jqGrid("setCell", rowId, "D1 + O1 + W1", d1o1w1);

        me.colorNum(e, str);

        return true;
    };
    //-の追加/削除
    me.add_0 = function (e) {
        var keydownVal = e.char || e.key;
        var inputVal = $.trim($(e.target).val());

        if (inputVal) {
            if (e && e.target) {
                $(e.target).val(
                    inputVal.indexOf(keydownVal) >= 0
                        ? inputVal.replace(keydownVal, "")
                        : keydownVal + inputVal
                );
            }
        } else {
            $(e.target).val("-0");
        }
        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyukkouSeikyuInfoEnt = new JKSYS.FrmSyukkouSeikyuInfoEnt();
    o_JKSYS_FrmSyukkouSeikyuInfoEnt.load();
    o_JKSYS_JKSYS.FrmSyukkouSeikyuInfoEnt = o_JKSYS_FrmSyukkouSeikyuInfoEnt;
});
