/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("JKSYS.FrmJinkenhiEnt");

JKSYS.FrmJinkenhiEnt = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.sys_id = "JKSYS";
    me.id = "FrmJinkenhiEnt";

    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.pager = "";
    me.sidx = "";
    me.upsel = "";
    me.nextsel = "";

    me.data = "";
    me.prvMstYM = "";
    me.prvUpdateDateTime = "";
    me.allBusyoName = "";
    me.totalName1 = [
        "KENKO_HKN_RYO",
        "KAIGO_HKN_RYO",
        "KOUSEINENKIN",
        "KOYOU_HKN_RYO",
        "ROUSAI_HKN_RYO",
        "JIDOUTEATE",
        "TAISYOKU_KYUFU",
    ];
    me.totalName2 = [
        "BNS_KENKO_HKN_RYO",
        "BNS_KAIGO_HKN_RYO",
        "BNS_KOUSEI_NENKIN",
        "BNS_JIDOU_TEATE",
    ];

    me.grid_id = "#JKSYS_FrmJinkenhiEnt_sprList";
    me.option = {
        rowNum: 0,
        rownumWidth: 30,
        rownumbers: true,
        shrinkToFit: false,
        autoScroll: true,
        multiselect: false,
        caption: "",
    };
    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "コード",
            index: "BUSYO_CD",
            width: 46,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    //フォーカスを失ったとき部署名をリセットする
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getBusyoName(e);
                        },
                    },
                    //マウス左セルイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getBusyoName(e);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "名称",
            index: "BUSYO_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NO",
            label: "番号",
            index: "SYAIN_NO",
            width: 44,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "5",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (key == 38 || key == 40) {
                                me.getSyainName(e);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NM",
            label: "名称",
            index: "SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "NEWROW",
            label: "",
            index: "NEWROW",
            width: 77,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "SYOKUSYU_CD",
            label: "職種コード",
            index: "SYOKUSYU_CD",
            width: 50,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyokusyu(e, "SYOKUSYU_CD", "SYOKUSYU_CODE");
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (key == 38 || key == 40) {
                                me.getSyokusyu(
                                    e,
                                    "SYOKUSYU_CD",
                                    "SYOKUSYU_CODE"
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYOKUSYU_CODE",
            label: "",
            index: "SYOKUSYU_CODE",
            width: 140,
            align: "left",
            sortable: false,
            editable: true,
            edittype: "select",
            formatter: "select",
            editoptions: {
                dataInit: function (elem) {
                    $(elem).width(135);
                },
                dataEvents: [
                    {
                        type: "change",
                        fn: function (e) {
                            me.getSyokusyu(e, "SYOKUSYU_CODE", "SYOKUSYU_CD");
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (key == 38 || key == 40) {
                                me.getSyokusyu(
                                    e,
                                    "SYOKUSYU_CODE",
                                    "SYOKUSYU_CD"
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KOYOU_KB",
            label: "雇用区分",
            index: "KOYOU_KB",
            width: 100,
            align: "left",
            sortable: false,
            editable: true,
            edittype: "select",
            formatter: "select",
            editoptions: {
                dataInit: function (elem) {
                    $(elem).width(95);
                },
            },
        },
        {
            name: "KIHONKYU",
            label: "基本給",
            index: "KIHONKYU",
            width: 75,
            align: "right",
            hidden: true,
            sortable: false,
        },
        {
            name: "TEIJIKAN_GESSYU",
            label: "定時間",
            index: "TEIJIKAN_GESSYU",
            width: 75,
            align: "right",
            sortable: false,
            editable: true,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "ZANGYOU_TEATE",
            label: "残業手当",
            index: "ZANGYOU_TEATE",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "GYOUSEKI_SYOUREI",
            label: "業績奨励金",
            index: "GYOUSEKI_SYOUREI",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "HOKA_GSK_SYOUREI",
            label: "他業績奨",
            index: "HOKA_GSK_SYOUREI",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "SONOTA_TEATE",
            label: "其他手当",
            index: "SONOTA_TEATE",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "KENKO_HKN_RYO",
            label: "健康保険",
            index: "KENKO_HKN_RYO",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "KENKO_HKN_RYO",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "KENKO_HKN_RYO",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KAIGO_HKN_RYO",
            label: "介護保険",
            index: "KAIGO_HKN_RYO",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "KAIGO_HKN_RYO",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "KAIGO_HKN_RYO",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KOUSEINENKIN",
            label: "厚生年金",
            index: "KOUSEINENKIN",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "KOUSEINENKIN",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "KOUSEINENKIN",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KOYOU_HKN_RYO",
            label: "雇用保険",
            index: "KOYOU_HKN_RYO",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "KOYOU_HKN_RYO",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "KOYOU_HKN_RYO",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ROUSAI_HKN_RYO",
            label: "労災保険",
            index: "ROUSAI_HKN_RYO",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "ROUSAI_HKN_RYO",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "ROUSAI_HKN_RYO",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "JIDOUTEATE",
            label: "児童手当",
            index: "JIDOUTEATE",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(e, "JIDOUTEATE", me.totalName1);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "JIDOUTEATE",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TAISYOKU_KYUFU",
            label: "退職給付",
            index: "TAISYOKU_KYUFU",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "TAISYOKU_KYUFU",
                                me.totalName1
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "TAISYOKU_KYUFU",
                                    me.totalName1
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYSKAIHOKENRTOKEI",
            label: "社会保険計",
            index: "SYSKAIHOKENRTOKEI",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
        },
        {
            name: "HIDDEN",
            label: " ",
            index: "HIDDEN",
            width: 77,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "BNS_MITUMORI",
            label: "賞与",
            index: "BNS_MITUMORI",
            width: 75,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
            },
        },
        {
            name: "BNS_KENKO_HKN_RYO",
            label: "健康保険料",
            index: "BNS_KENKO_HKN_RYO",
            width: 100,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "BNS_KENKO_HKN_RYO",
                                me.totalName2
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "BNS_KENKO_HKN_RYO",
                                    me.totalName2
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BNS_KAIGO_HKN_RYO",
            label: "介護保険料",
            index: "BNS_KAIGO_HKN_RYO",
            width: 100,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "BNS_KAIGO_HKN_RYO",
                                me.totalName2
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "BNS_KAIGO_HKN_RYO",
                                    me.totalName2
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BNS_KOUSEI_NENKIN",
            label: "厚生年金",
            index: "BNS_KOUSEI_NENKIN",
            width: 80,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "BNS_KOUSEI_NENKIN",
                                me.totalName2
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "BNS_KOUSEI_NENKIN",
                                    me.totalName2
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BNS_JIDOU_TEATE",
            label: "児童手当",
            index: "BNS_JIDOU_TEATE",
            width: 80,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataInit: function (element) {
                    //数字のみを入力する
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.numberCheckTotal(
                                e,
                                "BNS_JIDOU_TEATE",
                                me.totalName2
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //合計計算
                            if (key == 38 || key == 40) {
                                me.numberCheckTotal(
                                    e,
                                    "BNS_JIDOU_TEATE",
                                    me.totalName2
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYOUYOSYAKAIHOKENRYOKEI",
            label: "社保計",
            index: "SYOUYOSYAKAIHOKENRYOKEI",
            width: 70,
            align: "right",
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
        },
        {
            name: "JININ_CNT",
            label: "人員カウント",
            index: "JININ_CNT",
            width: 90,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^[0-1]{0,1}$/.test(value);
                    });
                },
            },
        },
        {
            name: "CREATE_DATE",
            label: "",
            index: "CREATE_DATE",
            width: 75,
            align: "right",
            hidden: true,
            sortable: false,
        },
        {
            name: "CRE_SYA_CD",
            label: "",
            index: "CRE_SYA_CD",
            width: 75,
            align: "right",
            hidden: true,
            sortable: false,
        },
        {
            name: "CRE_PRG_ID",
            label: "",
            index: "CRE_PRG_ID",
            width: 75,
            align: "right",
            hidden: true,
            sortable: false,
        },
    ];

    // ========== 変数 end ==========
    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJinkenhiEnt.dtpYM",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnSearchBusyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnSearchSyain",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnAddRow",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnDelRow",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnModify",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiEnt.btnUpdate",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========
    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //[検索条件]対象年月チェック
    $(".FrmJinkenhiEnt.dtpYM").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmJinkenhiEnt.dtpYM")) == false) {
            $(".FrmJinkenhiEnt.dtpYM").val(me.prvMstYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmJinkenhiEnt.dtpYM").focus();
                    $(".FrmJinkenhiEnt.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmJinkenhiEnt.dtpYM").focus();
                        $(".FrmJinkenhiEnt.dtpYM").select();
                    }, 0);
                }
            }
            $(".FrmJinkenhiEnt.btnSearch").button("disable");
        } else {
            $(".FrmJinkenhiEnt.btnSearch").button("enable");
        }
    });
    //[検索条件]部署コード変更時
    $(".FrmJinkenhiEnt.txtBusyoCd").change(function (e) {
        me.txtBusyoCd_Validating(e);
    });
    //[検索条件]社員コード変更時
    $(".FrmJinkenhiEnt.txtSyainNo").change(function (e) {
        me.txtSyainNo_Validating(e);
    });
    //[検索条件]部署検索ボタンクリック
    $(".FrmJinkenhiEnt.btnSearchBusyo").click(function () {
        me.btnSearchBusyo_Click();
    });
    //[検索条件]社員検索ボタンクリック
    $(".FrmJinkenhiEnt.btnSearchSyain").click(function () {
        me.btnSearchSyain_Click();
    });
    //[検索条件]検索ボタンクリック
    $(".FrmJinkenhiEnt.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //行追加ボタンクリック
    $(".FrmJinkenhiEnt.btnAddRow").click(function () {
        me.btnAddRow_Click();
    });
    //行削除ボタンクリック
    $(".FrmJinkenhiEnt.btnDelRow").click(function () {
        me.btnDelRow_Click();
    });
    //条件変更ボタンクリック
    $(".FrmJinkenhiEnt.btnModify").click(function () {
        me.btnModify_Click();
    });
    //登録ボタンクリック
    $(".FrmJinkenhiEnt.btnUpdate").click(function () {
        me.btnUpdate_Click();
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
        //フォームロード
        me.FrmJinkenhiEnt_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：FrmJinkenhiEnt_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FrmJinkenhiEnt_Load = function () {
        //フォーム初期化
        me.procInitFormCtrl(true, true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：検索ボタンクリック
	 '関 数 名：btnSearch_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnSearch_Click = function () {
        //フォーム初期化
        me.procInitFormCtrl(false, true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：[検索条件]部署コード変更時
	 '関 数 名：txtBusyoCd_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.txtBusyoCd_Validating = function (e) {
        var foundNM = undefined;
        var selCellVal = $(e.target).val();
        if (me.allBusyoName) {
            var foundNM_array = me.allBusyoName.filter(function (element) {
                return element["BUSYO_CD"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".FrmJinkenhiEnt.lblBusyoNm").val(foundNM ? foundNM["BUSYO_NM"] : "");
    };
    /*
	 '**********************************************************************
	 '処 理 名：[検索条件]社員コード変更時
	 '関 数 名：txtSyainNo_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.txtSyainNo_Validating = function (e) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allSyainName) {
            var foundNM_array = me.allSyainName.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".FrmJinkenhiEnt.lblSyainNm").val(foundNM ? foundNM["SYAIN_NM"] : "");
    };
    /*
	 '**********************************************************************
	 '処 理 名：条件変更ボタンクリック
	 '関 数 名：btnModify_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnModify_Click = function () {
        //フォーム初期化
        me.procInitFormCtrl(true, false);
    };
    /*
	 '**********************************************************************
	 '処 理 名：行追加ボタンクリック
	 '関 数 名：btnAddRow_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnAddRow_Click = function () {
        var selIRow = 0;
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        if (ids.length > 0) {
            var selIRow = parseInt(ids.pop()) + 1;
        }
        //行追加
        $(me.grid_id).jqGrid("addRowData", selIRow, {
            NEWROW: 1,
            TEIJIKAN_GESSYU: 0,
            ZANGYOU_TEATE: 0,
            GYOUSEKI_SYOUREI: 0,
            HOKA_GSK_SYOUREI: 0,
            SONOTA_TEATE: 0,
            KENKO_HKN_RYO: 0,
            KAIGO_HKN_RYO: 0,
            KOUSEINENKIN: 0,
            KOYOU_HKN_RYO: 0,
            ROUSAI_HKN_RYO: 0,
            JIDOUTEATE: 0,
            TAISYOKU_KYUFU: 0,
            SYSKAIHOKENRTOKEI: 0,
            BNS_MITUMORI: 0,
            BNS_KENKO_HKN_RYO: 0,
            BNS_KAIGO_HKN_RYO: 0,
            BNS_KOUSEI_NENKIN: 0,
            BNS_JIDOU_TEATE: 0,
            SYOUYOSYAKAIHOKENRYOKEI: 0,
            JININ_CNT: 1,
        });
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        $(me.grid_id).jqGrid("setSelection", selIRow, true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：行削除ボタンクリック
	 '関 数 名：btnDelRow_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnDelRow_Click = function () {
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length < 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0, len = allIds.length; i < len; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：部署検索ボタンクリック
	 '関 数 名：btnSearchBusyo_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnSearchBusyo_Click = function () {
        var $rootDiv = $(".FrmJinkenhiEnt.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYONM").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $BUSYONM = $rootDiv.parent().find("#BUSYONM");

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 550 : 650,
            width: me.ratio === 1.5 ? 541 : 580,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $BUSYONM.hide();
            },
            close: function () {
                if ($RtnCD.html() == 1) {
                    me.RtnCD = $RtnCD.html();
                    me.searchedBusyoCD = $("#BUSYOCD").html();
                    me.searchedBusyoNM = $("#BUSYONM").html();
                    if (me.searchedBusyoNM != "") {
                        $(".FrmJinkenhiEnt.lblBusyoNm").val(me.searchedBusyoNM);
                    }
                    if (me.searchedBusyoCD != "") {
                        $(".FrmJinkenhiEnt.txtBusyoCd").val(me.searchedBusyoCD);
                    }
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $BUSYONM.remove();
                $("#FrmBusyoSearchDialogDiv").remove();
                $(".FrmJinkenhiEnt.txtBusyoCd").select();
            },
        });

        var frmId = "FrmJKSYSBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);
            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    };
    /*
	 '**********************************************************************
	 '処 理 名：社員検索ボタンクリック
	 '関 数 名：btnSearchSyain_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnSearchSyain_Click = function () {
        var $rootDiv = $(".FrmJinkenhiEnt.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmSyainSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNO").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "KUJYUNBI").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $SYAINNO = $rootDiv.parent().find("#SYAINNO");
        var $SYAINNM = $rootDiv.parent().find("#SYAINNM");
        var $KUJYUNBI = $rootDiv.parent().find("#KUJYUNBI");

        var dtpYM = $(".FrmJinkenhiEnt.dtpYM").val();
        var year = dtpYM.substr(0, 4);
        var month = dtpYM.substr(4, 2);
        //构造一个日期对象：
        var day = new Date(year, month, 0);
        //获取当月天数：
        var daycount = day.getDate();
        $KUJYUNBI.val(dtpYM + daycount);
        $BUSYOCD.val($.trim($(".FrmJinkenhiEnt.txtBusyoCd").val()));

        $("#FrmSyainSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 550 : 650,
            width: me.ratio === 1.5 ? 752 : 790,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $SYAINNO.hide();
                $SYAINNM.hide();
                $KUJYUNBI.hide();
            },
            close: function () {
                me.SYAINNO = $SYAINNO.html();
                me.SYAINNM = $SYAINNM.html();

                if (me.SYAINNO == "" || me.SYAINNO == null) {
                    me.isHasNo = false;
                } else {
                    me.isHasNo = true;
                }

                if ($RtnCD.html() == 1) {
                    $(".FrmJinkenhiEnt.txtSyainNo").val(me.SYAINNO);
                    $(".FrmJinkenhiEnt.lblSyainNm").val(me.SYAINNM);
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $SYAINNO.remove();
                $SYAINNM.remove();
                $KUJYUNBI.remove();
                $("#FrmSyainSearchDialogDiv").remove();
                $(".FrmJinkenhiEnt.txtSyainNo").select();
            },
        });

        var url = me.sys_id + "/" + "FrmJKSYSSyainSearch";
        me.ajax.receive = function (result) {
            $("#FrmSyainSearchDialogDiv").html(result);
            $("#FrmSyainSearchDialogDiv").dialog(
                "option",
                "title",
                "社員番号検索"
            );
            $("#FrmSyainSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, "", 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：更新ボタンクリック
	 '関 数 名：btnUpdate_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnUpdate_Click = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        //入力チェック
        me.procInputCheck();
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：procInputCheck
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procInputCheck = function () {
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            if (me.clsComFnc.FncNv(rowdata["BUSYO_CD"]) == "") {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                };
                me.clsComFnc.FncMsgBox("W0001", "部署コード");
                return false;
            } else {
                if (rowdata["BUSYO_NM"] == "") {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    };
                    //該当データ無し
                    me.clsComFnc.FncMsgBox("W0008", "部署コード");
                    return false;
                }
            }

            if (me.clsComFnc.FncNv(rowdata["SYAIN_NO"]) == "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                $("#" + ids[i] + "_SYAIN_NO").focus();
                me.clsComFnc.FncMsgBox("W0001", "社員番号");
                return false;
            } else {
                if (rowdata["SYAIN_NM"] == "") {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    $("#" + ids[i] + "_SYAIN_NO").focus();
                    //該当データ無し
                    me.clsComFnc.FncMsgBox("W0008", "社員番号");
                    return false;
                }
            }
            //雇用区分が役員以外又は雇用区分が非常勤役員以外の場合
            if (
                me.clsComFnc.FncNv(rowdata["KOYOU_KB"]) != "07" &&
                me.clsComFnc.FncNv(rowdata["KOYOU_KB"]) != "97"
            ) {
                if (
                    me.clsComFnc.FncNv(rowdata["SYOKUSYU_CD"]) == "" ||
                    me.clsComFnc.FncNv(rowdata["SYOKUSYU_CODE"]) == ""
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    $("#" + ids[i] + "_SYOKUSYU_CODE").focus();
                    me.clsComFnc.FncMsgBox("W0001", "職種コード");
                    return false;
                }
            }

            if (me.clsComFnc.FncNv(rowdata["JININ_CNT"]) == "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                $("#" + ids[i] + "_JININ_CNT").focus();
                me.clsComFnc.FncMsgBox("W0001", "人員カウント");
                return false;
            } else if (
                me.clsComFnc.FncNv(rowdata["JININ_CNT"]) != "1" &&
                me.clsComFnc.FncNv(rowdata["JININ_CNT"]) != "0"
            ) {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                $("#" + ids[i] + "_JININ_CNT").focus();
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "人員カウントには0又は1を入力します。それ以外は入力不可です。"
                );
                return false;
            }
            if (me.clsComFnc.FncNv(rowdata["BUSYO_CD"]) == "175") {
                switch (me.clsComFnc.FncNv(rowdata["SYOKUSYU_CD"])) {
                    case "200":
                    case "290":
                    case "201":
                    case "202":
                    case "209":
                    case "320":
                    case "321":
                    case "322":
                    case "360":
                    case "530":
                        $(me.grid_id).jqGrid("setSelection", ids[i], true);
                        $("#" + ids[i] + "_SYOKUSYU_CODE").focus();
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "車両回送係(部署コード：175)に所属する社員に整備直接員に該当する職種コードを設定することは出来ません！"
                        );
                        return false;
                }
            }
        }

        me.url = me.sys_id + "/" + me.id + "/updCheck";
        me.data = {
            dtpYM:
                $(".FrmJinkenhiEnt.dtpYM").val().substr(0, 4) +
                "/" +
                $(".FrmJinkenhiEnt.dtpYM").val().substr(4, 2),
            ddlKoyouKbn: $(".FrmJinkenhiEnt.ddlKoyouKbn").val(),
            txtBusyoCd: $(".FrmJinkenhiEnt.txtBusyoCd").val(),
            txtSyainNo: $(".FrmJinkenhiEnt.txtSyainNo").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //更新日付の取得
            if (result["data"][0]["UPD_DATE"] != me.prvUpdateDateTime) {
                me.clsComFnc.FncMsgBox("W0018");
                return;
            }
            //重複チェック
            var Syainarr = $(me.grid_id).jqGrid("getCol", "SYAIN_NO");
            var allRowsId = $(me.grid_id).jqGrid("getDataIDs");
            for (i = 0, len = Syainarr.length; i < len; i++) {
                for (i2 = i + 1, len = Syainarr.length; i2 < len; i2++) {
                    if (
                        me.clsComFnc.FncNv(Syainarr[i]) ==
                        me.clsComFnc.FncNv(Syainarr[i2])
                    ) {
                        $(me.grid_id).jqGrid(
                            "setSelection",
                            allRowsId[i2],
                            true
                        );
                        $("#" + allRowsId[i2] + "_SYAIN_NO").focus();
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "社員№が重複しています"
                        );
                        return;
                    }
                }
            }
            //存在チェック
            me.procJinkenhiDataChk();
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：人件費データの存在チェック
	 '関 数 名：procJinkenhiDataChk
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procJinkenhiDataChk = function () {
        var rowDatas = $(me.grid_id).jqGrid("getRowData");
        var Syainarr = new Array();
        for (var i = 0, len = rowDatas.length; i < len; i++) {
            if (rowDatas[i]["CREATE_DATE"] == "") {
                Syainarr.push(rowDatas[i]["SYAIN_NO"]);
            }
        }
        if (Syainarr.length == 0) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.UpdateAction;
            me.clsComFnc.FncMsgBox("QY005");
        } else {
            me.url = me.sys_id + "/" + me.id + "/JinkenhiDataChk";
            me.data = {
                dtpYM:
                    $(".FrmJinkenhiEnt.dtpYM").val().substr(0, 4) +
                    "/" +
                    $(".FrmJinkenhiEnt.dtpYM").val().substr(4, 2),
                ddlKoyouKbn: $(".FrmJinkenhiEnt.ddlKoyouKbn").val(),
                txtBusyoCd: $(".FrmJinkenhiEnt.txtBusyoCd").val(),
                txtSyainNo: $(".FrmJinkenhiEnt.txtSyainNo").val(),
                Syainarr: Syainarr,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                if (result["row"] > 0) {
                    var Syainidarr = $(me.grid_id).jqGrid(
                        "getCol",
                        "SYAIN_NO",
                        true
                    );
                    for (var i = 0, len = Syainidarr.length; i < len; i++) {
                        if (
                            Syainidarr[i]["value"] ==
                            result["data"][0]["SYAIN_NO"]
                        ) {
                            $(me.grid_id).jqGrid(
                                "setSelection",
                                Syainidarr[i]["id"],
                                true
                            );
                            $("#" + Syainidarr[i]["id"] + "_SYAIN_NO").focus();
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "社員№：" +
                                    result["data"][0]["SYAIN_NO"] +
                                    "は既に登録されています。"
                            );
                            return;
                        }
                    }
                }

                me.clsComFnc.MsgBoxBtnFnc.Yes = me.UpdateAction;
                //実行確認メッセージを表示。
                me.clsComFnc.FncMsgBox("QY005");
            };
            me.ajax.send(me.url, me.data, 0);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：更新開始
	 '関 数 名：UpdateAction
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.UpdateAction = function () {
        var rowDatas = $(me.grid_id).jqGrid("getRowData");
        me.data = {
            dtpYM: $(".FrmJinkenhiEnt.dtpYM").val(),
            ddlKoyouKbn: $(".FrmJinkenhiEnt.ddlKoyouKbn").val(),
            txtBusyoCd: $(".FrmJinkenhiEnt.txtBusyoCd").val(),
            txtSyainNo: $(".FrmJinkenhiEnt.txtSyainNo").val(),
            rowDatas: JSON.stringify(rowDatas),
        };
        me.url = me.sys_id + "/" + me.id + "/UpdateAction";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //完了メッセージ表示
            me.clsComFnc.FncMsgBox("I0008");
            //フォーム初期化
            me.procInitFormCtrl(true, false);
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：部署名取得
	 '関 数 名：getBusyoName
	 '引    数：e
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getBusyoName = function (e) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allBusyoName) {
            var foundNM_array = me.allBusyoName.filter(function (element) {
                return element["BUSYO_CD"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(e.target)
            .parent()
            .next()
            .text(foundNM ? foundNM["BUSYO_NM"] : "");
    };

    /*
	 '**********************************************************************
	 '処 理 名：社員名取得
	 '関 数 名：getSyainName
	 '引    数：e
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getSyainName = function (e) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allSyainName) {
            var foundNM_array = me.allSyainName.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(e.target)
            .parent()
            .next()
            .text(foundNM ? foundNM["SYAIN_NM"] : "");
    };
    /*
	 '**********************************************************************
	 '処 理 名：職種名称取得
	 '関 数 名：getSyokusyu
	 '引    数：e, code, name
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getSyokusyu = function (e, code, name) {
        var row = $(e.target).closest("tr.jqgrow");
        var rowId = row.attr("id");
        var idGet = "#" + rowId + "_" + code;
        var vaGet = $.trim($(idGet).val());
        if (vaGet == "") {
            $("#" + rowId + "_" + name + "").val("");
        } else {
            $("#" + rowId + "_" + name + "").val(vaGet);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：合計計算
	 '関 数 名：numberCheckTotal
	 '引    数：e, check_name, cal_name
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.numberCheckTotal = function (e, _check_name, cal_name) {
        var row = $(e.target).closest("tr.jqgrow");
        var rowId = row.attr("id");
        if (cal_name) {
            var SyakaiHokenryoKei = 0;
            for (key in cal_name) {
                if ($.trim($("#" + rowId + "_" + cal_name[key]).val()) != "") {
                    SyakaiHokenryoKei += parseInt(
                        $.trim($("#" + rowId + "_" + cal_name[key]).val())
                    );
                }
            }
            if (cal_name == me.totalName1) {
                $(me.grid_id).jqGrid(
                    "setCell",
                    rowId,
                    "SYSKAIHOKENRTOKEI",
                    SyakaiHokenryoKei
                );
            } else {
                $(me.grid_id).jqGrid(
                    "setCell",
                    rowId,
                    "SYOUYOSYAKAIHOKENRYOKEI",
                    SyakaiHokenryoKei
                );
            }
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォーム初期化
	 '関 数 名：procInitFormCtrl
	 '引    数：isFormLoad, isFirst
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procInitFormCtrl = function (isFormLoad, isFirst) {
        if (isFormLoad && isFirst) {
            //jqgrid初期
            {
                if ($(window).width() <= 1366) {
                    gdmz.common.jqgrid.init(
                        me.grid_id,
                        me.g_url,
                        me.colModel,
                        me.pager,
                        me.sidx,
                        me.option
                    );
                    gdmz.common.jqgrid.set_grid_width(
                        me.grid_id,
                        me.ratio === 1.5 ? 1007 : 1068
                    );
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        me.ratio === 1.5 ? 210 : 260
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
                    //20201113 CI UPD S
                    //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1360);
                    gdmz.common.jqgrid.set_grid_width(me.grid_id, 1250);
                    //20201113 CI UPD E
                    gdmz.common.jqgrid.set_grid_height(me.grid_id, 450);
                }

                $(me.grid_id).jqGrid("setGridParam", {
                    beforeSelectRow: function (_rowid, e) {
                        var cellIndex = e.target.cellIndex;
                        //ヘッダークリック
                        if (cellIndex == 0) {
                            var selNextId = "#" + me.lastsel + "_BUSYO_CD";
                            $(selNextId).focus();
                            $(selNextId).select();
                            return false;
                        }
                        return true;
                    },
                    onSelectRow: function (rowid, _status, e) {
                        $(".numeric").numeric({
                            decimal: false,
                            negative: true,
                        });

                        if (typeof e != "undefined") {
                            var cellIndex =
                                e.target.cellIndex !== undefined
                                    ? e.target.cellIndex
                                    : e.target.parentElement.cellIndex;
                            //ヘッダークリック以外
                            if (cellIndex != 0) {
                                if (rowid && rowid != me.lastsel) {
                                    $(me.grid_id).jqGrid("saveRow", me.lastsel);
                                    me.lastsel = rowid;
                                }
                                var rowdata = $(me.grid_id).jqGrid(
                                    "getRowData",
                                    rowid
                                );
                                if (rowdata["NEWROW"] == 1) {
                                    $(me.grid_id).setColProp("SYAIN_NO", {
                                        editable: true,
                                    });
                                } else {
                                    $(me.grid_id).setColProp("SYAIN_NO", {
                                        editable: false,
                                    });
                                }
                                if (
                                    $(".FrmJinkenhiEnt.dtpYM").val() >=
                                    me.prvMstYM
                                ) {
                                    if (
                                        cellIndex !== 2 &&
                                        cellIndex !== 3 &&
                                        cellIndex !== 4 &&
                                        cellIndex !== 22 &&
                                        cellIndex !== 29
                                    ) {
                                        $(me.grid_id).jqGrid("editRow", rowid, {
                                            focusField: cellIndex,
                                        });
                                    } else {
                                        if (
                                            rowdata["NEWROW"] == 1 &&
                                            cellIndex === 3
                                        ) {
                                            $(me.grid_id).jqGrid(
                                                "editRow",
                                                rowid,
                                                {
                                                    focusField: cellIndex,
                                                }
                                            );
                                        } else {
                                            $(me.grid_id).jqGrid(
                                                "editRow",
                                                rowid,
                                                true
                                            );
                                            setTimeout(function () {
                                                var selNextId =
                                                    "#" + rowid + "_BUSYO_CD";
                                                $(selNextId).focus();
                                            }, 0);
                                        }
                                    }
                                }
                                //数字列居右编辑
                                $("#" + rowid + "_TEIJIKAN_GESSYU").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_ZANGYOU_TEATE").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_GYOUSEKI_SYOUREI").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_HOKA_GSK_SYOUREI").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_SONOTA_TEATE").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_KENKO_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_KAIGO_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_KOUSEINENKIN").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_KOYOU_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_ROUSAI_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_JIDOUTEATE").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_TAISYOKU_KYUFU").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_BNS_MITUMORI").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_BNS_KENKO_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_BNS_KAIGO_HKN_RYO").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_BNS_KOUSEI_NENKIN").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_BNS_JIDOU_TEATE").css(
                                    "text-align",
                                    "right"
                                );
                                $("#" + rowid + "_JININ_CNT").css(
                                    "text-align",
                                    "right"
                                );
                                $("input,select", e.target).focus();
                            }
                        } else {
                            if (rowid && rowid != me.lastsel) {
                                $(me.grid_id).jqGrid("saveRow", me.lastsel);
                                me.lastsel = rowid;
                            }
                            var rowdata = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowid
                            );
                            if (rowdata["NEWROW"] == 1) {
                                $(me.grid_id).setColProp("SYAIN_NO", {
                                    editable: true,
                                });
                            } else {
                                $(me.grid_id).setColProp("SYAIN_NO", {
                                    editable: false,
                                });
                            }
                            if (
                                $(".FrmJinkenhiEnt.dtpYM").val() >= me.prvMstYM
                            ) {
                                $(me.grid_id).jqGrid("editRow", rowid, {
                                    focusField: false,
                                });
                            }
                            $("#" + rowid + "_TEIJIKAN_GESSYU").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_ZANGYOU_TEATE").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_GYOUSEKI_SYOUREI").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_HOKA_GSK_SYOUREI").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_SONOTA_TEATE").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_KENKO_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_KAIGO_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_KOUSEINENKIN").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_KOYOU_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_ROUSAI_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_JIDOUTEATE").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_TAISYOKU_KYUFU").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_BNS_MITUMORI").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_BNS_KENKO_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_BNS_KAIGO_HKN_RYO").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_BNS_KOUSEI_NENKIN").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_BNS_JIDOU_TEATE").css(
                                "text-align",
                                "right"
                            );
                            $("#" + rowid + "_JININ_CNT").css(
                                "text-align",
                                "right"
                            );
                        }
                        //キーボードイベント
                        var up_next_sel =
                            gdmz.common.jqgrid.setKeybordEvents(
                                me.grid_id,
                                e,
                                me.lastsel
                            );
                        if (up_next_sel && up_next_sel.length == 2) {
                            me.upsel = up_next_sel[0];
                            me.nextsel = up_next_sel[1];
                        }
                    },
                });
                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            startColumnName: "BUSYO_CD",
                            numberOfColumns: 2,
                            titleText: "部署",
                        },
                        {
                            startColumnName: "SYAIN_NO",
                            numberOfColumns: 2,
                            titleText: "社員",
                        },
                        {
                            startColumnName: "BNS_MITUMORI",
                            numberOfColumns: 6,
                            titleText: "賞与",
                        },
                    ],
                });
                $(me.grid_id).jqGrid("bindKeys");

                $("#JKSYS_FrmJinkenhiEnt_sprList_SYOKUSYU_CODE").remove();
                $("#JKSYS_FrmJinkenhiEnt_sprList_SYOKUSYU_CD").attr(
                    "colspan",
                    2
                );
                $("#jqgh_JKSYS_FrmJinkenhiEnt_sprList_SYOKUSYU_CD").css(
                    "top",
                    "13px"
                );
            }
            me.url = me.sys_id + "/" + me.id + "/" + "fncFormload";
            me.data = {};
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    $(".FrmJinkenhiEnt").ympicker("disable");
                    $(".FrmJinkenhiEnt").attr("disabled", true);
                    $(".FrmJinkenhiEnt button").button("disable");

                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var data = result["data"];
                //対象年月
                me.prvMstYM = data["strRetYM"]["data"][0]["SYORI_YM"];
                $(".FrmJinkenhiEnt.dtpYM").val(me.prvMstYM);
                $(".FrmJinkenhiEnt.dtpYM").focus();
                $(".FrmJinkenhiEnt.dtpYM").select();
                //雇用区分ComboBox
                var ddlKoyouKbn = data["ddlKoyouKbn"];
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".FrmJinkenhiEnt.ddlKoyouKbn");
                for (var i = 0; i < ddlKoyouKbn.length; i++) {
                    $("<option></option>")
                        .val(ddlKoyouKbn[i]["KUBUN_CD"])
                        .text(ddlKoyouKbn[i]["KUBUN_NM"])
                        .appendTo(".FrmJinkenhiEnt.ddlKoyouKbn");
                }
                //部署コード
                me.allBusyoName = data["GetBusyoMstValue"];
                //社員番号
                me.allSyainName = data["GetSyainMstValue"];
            };
            me.ajax.send(me.url, me.data, 1);
        }
        if (isFormLoad) {
            $(me.grid_id).jqGrid("clearGridData");

            $(".FrmJinkenhiEnt.dtpYM").ympicker("enable");
            $(".FrmJinkenhiEnt.btnSearchBusyo").button("enable");
            $(".FrmJinkenhiEnt.btnSearchSyain").button("enable");
            $(".FrmJinkenhiEnt.btnSearch").button("enable");

            $(".FrmJinkenhiEnt.txtBusyoCd").attr("disabled", false);
            $(".FrmJinkenhiEnt.txtSyainNo").attr("disabled", false);
            $(".FrmJinkenhiEnt.ddlKoyouKbn").attr("disabled", false);

            $(".FrmJinkenhiEnt.btnAddRow").button("disable");
            $(".FrmJinkenhiEnt.btnDelRow").button("disable");

            $(".FrmJinkenhiEnt.btnModify").button("disable");
            $(".FrmJinkenhiEnt.btnUpdate").button("disable");
        } else {
            $(".FrmJinkenhiEnt.dtpYM").ympicker("disable");
            $(".FrmJinkenhiEnt.btnSearchBusyo").button("disable");
            $(".FrmJinkenhiEnt.btnSearchSyain").button("disable");
            $(".FrmJinkenhiEnt.btnSearch").button("disable");
            $(".FrmJinkenhiEnt.btnModify").button("enable");
            $(".FrmJinkenhiEnt.txtBusyoCd").attr("disabled", true);
            $(".FrmJinkenhiEnt.txtSyainNo").attr("disabled", true);
            $(".FrmJinkenhiEnt.ddlKoyouKbn").attr("disabled", true);
            if (
                $(".FrmJinkenhiEnt.dtpYM").val() < me.prvMstYM ||
                me.prvMstYM == ""
            ) {
                $(".FrmJinkenhiEnt.btnAddRow").button("disable");
                $(".FrmJinkenhiEnt.btnDelRow").button("disable");
                $(".FrmJinkenhiEnt.btnUpdate").button("disable");
            } else {
                $(".FrmJinkenhiEnt.btnAddRow").button("enable");
                $(".FrmJinkenhiEnt.btnDelRow").button("enable");
                $(".FrmJinkenhiEnt.btnUpdate").button("enable");
            }
            me.url = me.sys_id + "/" + me.id + "/fncSearchData";
            me.data = {
                dtpYM:
                    $(".FrmJinkenhiEnt.dtpYM").val().substr(0, 4) +
                    "/" +
                    $(".FrmJinkenhiEnt.dtpYM").val().substr(4, 2),
                ddlKoyouKbn: $(".FrmJinkenhiEnt.ddlKoyouKbn").val(),
                txtBusyoCd: $(".FrmJinkenhiEnt.txtBusyoCd").val(),
                txtSyainNo: $(".FrmJinkenhiEnt.txtSyainNo").val(),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    $(".FrmJinkenhiEnt.btnAddRow").button("disable");
                    $(".FrmJinkenhiEnt.btnDelRow").button("disable");
                    $(".FrmJinkenhiEnt.btnUpdate").button("disable");
                    return;
                }
                var data = result["data"];
                //職種Comboboxのデータ取得
                DT_S = result["data"]["DT_S"]["data"];
                //雇用comboboxのデータ取得
                DT_K = result["data"]["DT_K"]["data"];
                //SPREADの初期化
                me.procInitSpreadSheet(DT_S, DT_K);
                //更新日付の取得
                if (result["data"]["prvUpdateDateTime"]["row"] > 0) {
                    me.prvUpdateDateTime =
                        result["data"]["prvUpdateDateTime"]["data"][0][
                            "UPD_DATE"
                        ];
                } else {
                    me.prvUpdateDateTime = "";
                }
                //jqgrid reload
                var data = {
                    dtpYM:
                        $(".FrmJinkenhiEnt.dtpYM").val().substr(0, 4) +
                        "/" +
                        $(".FrmJinkenhiEnt.dtpYM").val().substr(4, 2),
                    ddlKoyouKbn: $(".FrmJinkenhiEnt.ddlKoyouKbn").val(),
                    txtBusyoCd: $(".FrmJinkenhiEnt.txtBusyoCd").val(),
                    txtSyainNo: $(".FrmJinkenhiEnt.txtSyainNo").val(),
                };
                var completeFnc = function (bErrorFlag, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        $(".FrmJinkenhiEnt.btnAddRow").button("disable");
                        $(".FrmJinkenhiEnt.btnDelRow").button("disable");
                        $(".FrmJinkenhiEnt.btnUpdate").button("disable");
                        return;
                    }
                    if (bErrorFlag == "nodata") {
                        //該当データは存在しません
                        me.clsComFnc.FncMsgBox("W0016");
                        //フォーム初期化
                        me.procInitFormCtrl(true, false);
                    }
                    //１行目を選択状態にする
                    $(me.grid_id).jqGrid("setSelection", "0");
                };
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    completeFnc
                );
            };
            me.ajax.send(me.url, me.data, 0);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：SPREADの初期化
	 '関 数 名：procInitSpreadSheet
	 '引    数：DT_S, DT_K
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procInitSpreadSheet = function (DT_S, DT_K) {
        //職種コード
        valuestr = "" + ":" + "";
        DT_S.map(function (value) {
            valuestr += ";" + value["CODE"] + ":" + value["MEISYOU"];
        });
        $(me.grid_id).setColProp("SYOKUSYU_CODE", {
            editoptions: {
                value: valuestr,
            },
        });
        //雇用区分
        valuestr = "" + ":" + "";
        DT_K.map(function (value) {
            valuestr += ";" + value["KUBUN_CD"] + ":" + value["KUBUN_NM"];
        });
        $(me.grid_id).setColProp("KOYOU_KB", {
            editoptions: {
                value: valuestr,
            },
        });
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_JKSYS_FrmJinkenhiEnt = new JKSYS.FrmJinkenhiEnt();
    o_JKSYS_FrmJinkenhiEnt.load();
});
