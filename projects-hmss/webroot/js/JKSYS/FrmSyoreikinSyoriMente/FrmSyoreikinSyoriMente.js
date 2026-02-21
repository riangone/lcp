/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("JKSYS.FrmSyoreikinSyoriMente");

JKSYS.FrmSyoreikinSyoriMente = function () {
    var me = new gdmz.base.panel();
    $(".FrmSyoreikinSyoriMente.tabsList").tabs();
    // ==========
    // = 宣言 start =
    // ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmSyoreikinSyoriMente";
    me.sys_id = "JKSYS";

    me.allSyokusyuName = "";
    me.allBusyoName = "";
    me.allRouteName = "";
    me.allKoyouName = "";

    me.tab1_1_jqgrid = "#FrmSyoreikinSyoriMente_sprGyoKeisuSyurui";
    me.tab1_2_jqgrid = "#FrmSyoreikinSyoriMente_sprGyokeisuKomoku";
    me.tab1_3_jqgrid = "#FrmSyoreikinSyoriMente_sprGyoTaisyoRoute";
    me.tab2_1_jqgrid = "#FrmSyoreikinSyoriMente_sprGyoTaisyo";
    me.tab2_2_jqgrid = "#FrmSyoreikinSyoriMente_sprGyoJogen";
    me.tab3_1_jqgrid = "#FrmSyoreikinSyoriMente_sprTenKeisuSyurui";
    me.tab3_2_jqgrid = "#FrmSyoreikinSyoriMente_sprTenkeisuKomoku";
    me.tab3_3_jqgrid = "#FrmSyoreikinSyoriMente_sprTenTaisyoRoute";
    me.tab4_1_jqgrid = "#FrmSyoreikinSyoriMente_sprTenTaisyo";
    me.tab4_2_jqgrid = "#FrmSyoreikinSyoriMente_sprTenSyutoku";
    //---------------------------------------------------
    me.lastsel = 0;
    me.upsel = "";
    me.nextsel = "";
    //---------------------------------------------------
    //   スプレッド
    //---------------------------------------------------
    //対象販売ルート
    me.SprTaisyoRoute = {
        CHECK: "",
        CODE: "",
        MEISYO: "",
    };
    //業績奨励_係数種類/項目
    me.SprGyokeisuCol = {
        CODE: "",
        MEISYO: "",
        ATAI_1: "",
        ATAI_1_NM: "",
        ATAI_2: "",
        HYOJI_JUN: "",
    };
    //業績奨励_支給対象
    me.SprGyoTaisyoCol = {
        SYOKUSYU: "",
        SYOKUSYU_NM: "",
        BUSYO: "",
        BUSYO_NM: "",
        ROUTE: "",
        ROUTE_NM: "",
    };
    //業績奨励_支給上限
    me.SprGyoJogenCol = {
        KOYOU: "",
        KOYOU_NM: "",
        SYOKUSYU: "",
        SYOKUSYU_NM: "",
        JOGEN: "",
    };
    //店長奨励_支給対象
    me.SprTenTaisyoCol = {
        BUSYO: "",
        BUSYO_NM: "",
        SYOKUSYU: "",
        SYOKUSYU_NM: "",
        ROUTE: "",
        ROUTE_NM: "",
    };
    //店長奨励_限界/経常利益取得部署
    me.SprTenSyutokuCol = {
        BUSYO: "",
        BUSYO_NM: "",
        RIEKI: "",
        RIEKI_NM: "",
        GENKAI: "",
        GENKAI_NM: "",
    };
    //業績奨励_対象販売ルート
    me.sprGyoTaisyoRouteColM = [
        {
            name: "CHECK",
            label: "対象",
            index: "CHECK",
            width: 35,
            align: "center",
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
        },
        {
            name: "CODE",
            label: "販売ルート",
            index: "CODE",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "販売ルート名",
            index: "MEISYO",
            width: 200,
            align: "left",
            sortable: false,
        },
    ];
    //業績奨励_係数項目
    me.sprGyokeisuKomokuColM = [
        {
            name: "CODE",
            label: "コード",
            index: "CODE",
            width: 65,
            align: "center",
            hidden: true,
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "名称",
            index: "MEISYO",
            width: 190,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "50",
            },
        },
        {
            name: "ATAI_1",
            label: "掛け率",
            index: "ATAI_1",
            width: 100,
            align: "right",
            //書式設定
            formatter: "number",
            formatoptions: {
                decimalSeparator: ".",
                decimalPlaces: 1,
                thousandsSeparator: "",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "9",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d{0,7}$|^-?\d{0,7}\.\d{0,1}$/.test(value);
                    });
                },
                dataEvents: [
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
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ATAI_1_NM",
            label: "値1名称",
            index: "ATAI_1_NM",
            width: 65,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "ATAI_2",
            label: "表示販売ルート名",
            index: "ATAI_2",
            width: 150,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
            },
        },
        {
            name: "HYOJI_JUN",
            label: "表示順",
            index: "HYOJI_JUN",
            width: 50,
            align: "right",
            sortable: false,
            editable: true,
            formatter: "number",
            formatoptions: {
                decimalPlaces: 0,
                thousandsSeparator: "",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "4",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^[0-9]*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var idName = "HYOJI_JUN";
                            me.BlurHyoujijyunn(idName);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー ↑，↓，tab，enter
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 9 ||
                                key == 13
                            ) {
                                var idName = "HYOJI_JUN";
                                me.BlurHyoujijyunn(idName);
                            }
                        },
                    },
                ],
            },
        },
    ];
    //業績奨励_係数種類
    me.sprGyoKeisuSyuruiColM = [
        {
            name: "CODE",
            label: "係数種類コード",
            index: "CODE",
            width: 65,
            align: "center",
            hidden: true,
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "係数種類",
            index: "MEISYO",
            width: 200,
            align: "left",
            sortable: false,
        },
        {
            name: "ATAI_1",
            label: "値1",
            index: "ATAI_1",
            width: 65,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "ATAI_1_NM",
            label: "範囲指定有無",
            index: "ATAI_1_NM",
            width: 100,
            align: "center",
            sortable: false,
        },
        {
            name: "ATAI_2",
            label: "支給計算書表示単位",
            index: "ATAI_2",
            width: 150,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
            },
        },
        {
            name: "HYOJI_JUN",
            label: "表示順",
            index: "HYOJI_JUN",
            width: 65,
            align: "right",
            sortable: false,
            editable: true,
            formatter: "number",
            formatoptions: {
                decimalPlaces: 0,
                thousandsSeparator: "",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "4",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^[0-9]*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var idName = "HYOJI_JUN";
                            me.BlurHyoujijyunn(idName);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー  ↑，↓，tab，enter
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 9 ||
                                key == 13
                            ) {
                                var idName = "HYOJI_JUN";
                                me.BlurHyoujijyunn(idName);
                            }
                        },
                    },
                ],
            },
        },
    ];
    //業績奨励_支給上限
    me.sprGyoJogenColM = [
        {
            name: "KOYOU",
            label: "雇用区分コード",
            index: "KOYOU",
            width: 110,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "2",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            var Id = $(me.tab2_2_jqgrid);
                            //ショートカットキー
                            me.BlurKyoutsuu(
                                Id,
                                "KOYOU",
                                "KOYOU_NM",
                                "KUBUN_CD",
                                "KUBUN_NM",
                                me.allKoyouName
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "KOYOU").val()
                                );
                                $(me.tab2_2_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "KOYOU_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        "KUBUN_CD",
                                        "KUBUN_NM",
                                        me.allKoyouName
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KOYOU_NM",
            label: "雇用区分",
            index: "KOYOU_NM",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "SYOKUSYU",
            label: "職種コード",
            index: "SYOKUSYU",
            width: 90,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab2_2_jqgrid);
                            var idName = "SYOKUSYU";
                            var vaGetName = "SYOKUSYU_NM";
                            var itemCode = "CODE";
                            var name = "MEISYOU";
                            var allParameter = me.allSyokusyuName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "SYOKUSYU").val()
                                );
                                var itemCode = "CODE";
                                var name = "MEISYOU";
                                var allParameter = me.allSyokusyuName;
                                $(me.tab2_2_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "SYOKUSYU_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYOKUSYU_NM",
            label: "職種名",
            index: "SYOKUSYU_NM",
            width: 165,
            align: "left",
            sortable: false,
        },
        {
            name: "JOGEN",
            label: "上限額",
            index: "JOGEN",
            width: 105,
            align: "right",
            formatter: "integer",
            formatoptions: {
                decimalSeparator: ",",
                defaultValue: "",
            },
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "7",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
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
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        },
                    },
                ],
            },
        },
    ];
    //業績奨励_支給対象
    me.sprGyoTaisyoColM = [
        {
            name: "SYOKUSYU",
            label: "職種コード",
            index: "SYOKUSYU",
            width: 90,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",

                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab2_1_jqgrid);
                            var idName = "SYOKUSYU";
                            var vaGetName = "SYOKUSYU_NM";
                            var itemCode = "CODE";
                            var name = "MEISYOU";
                            var allParameter = me.allSyokusyuName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (_e) {
                            //ショートカットキー
                            var vaGet = $.trim(
                                $("#" + me.lastsel + "_" + "SYOKUSYU").val()
                            );
                            var itemCode = "CODE";
                            var name = "MEISYOU";
                            var allParameter = me.allSyokusyuName;
                            $(me.tab2_1_jqgrid).jqGrid(
                                "setCell",
                                me.lastsel,
                                "SYOKUSYU_NM",
                                me.getName(
                                    me.clsComFnc.FncNv(vaGet),
                                    itemCode,
                                    name,
                                    allParameter
                                )
                            );
                        },
                    },
                ],
            },
        },
        {
            name: "SYOKUSYU_NM",
            label: "職種名",
            index: "SYOKUSYU_NM",
            width: 205,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO",
            label: "部署コード",
            index: "BUSYO",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab2_1_jqgrid);
                            var idName = "BUSYO";
                            var vaGetName = "BUSYO_NM";
                            var itemCode = "BUSYO_CD";
                            var name = "BUSYO_NM";
                            var allParameter = me.allBusyoName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "BUSYO").val()
                                );
                                var itemCode = "BUSYO_CD";
                                var name = "BUSYO_NM";
                                var allParameter = me.allBusyoName;
                                $(me.tab2_1_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "BUSYO_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "ROUTE",
            label: "販売ルート",
            index: "ROUTE",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "90",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab2_1_jqgrid);
                            var idName = "ROUTE";
                            var vaGetName = "ROUTE_NM";
                            var itemCode = "CODE";
                            var name = "MEISYO";
                            var allParameter = me.allRouteName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "ROUTE").val()
                                );
                                var itemCode = "CODE";
                                var name = "MEISYO";
                                var allParameter = me.allRouteName;
                                // すべてのパラメータ
                                $(me.tab2_1_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "ROUTE_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ROUTE_NM",
            label: "販売ルート名",
            index: "ROUTE_NM",
            width: 170,
            align: "left",
            sortable: false,
        },
    ];

    //店長奨励_対象販売ルート
    me.sprTenTaisyoRouteColM = [
        {
            name: "CHECK",
            label: "対象",
            index: "CHECK",
            width: 50,
            align: "center",
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
        },
        {
            name: "CODE",
            label: "販売ルート",
            index: "CODE",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "販売ルート名",
            index: "MEISYO",
            width: 200,
            align: "left",
            sortable: false,
        },
    ];

    //店長奨励_係数項目
    me.sprTenkeisuKomokuColM = [
        {
            name: "CODE",
            label: "コード",
            index: "CODE",
            width: 65,
            align: "center",
            hidden: true,
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "名称",
            index: "MEISYO",
            width: 150,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "50",
            },
        },
        {
            name: "ATAI_1",
            label: "値1",
            index: "ATAI_1",
            width: 65,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "ATAI_1_NM",
            label: "値1名称",
            index: "ATAI_1_NM",
            width: 150,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "ATAI_2",
            label: "値2",
            index: "ATAI_2",
            width: 65,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "HYOJI_JUN",
            label: "表示順",
            index: "HYOJI_JUN",
            width: 150,
            align: "right",
            sortable: false,
            editable: true,
            formatoptions: {
                decimalPlaces: 0,
                thousandsSeparator: "",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "4",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^[0-9]*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var idName = "HYOJI_JUN";
                            me.BlurHyoujijyunn(idName);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー  ↑，↓，tab，enter
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 9 ||
                                key == 13
                            ) {
                                var idName = "HYOJI_JUN";
                                me.BlurHyoujijyunn(idName);
                            }
                        },
                    },
                ],
            },
        },
    ];

    //店長奨励_係数種類
    me.sprTenKeisuSyuruiColM = [
        {
            name: "CODE",
            label: "係数コード",
            index: "CODE",
            width: 65,
            align: "center",
            hidden: true,
            sortable: false,
        },
        {
            name: "MEISYO",
            label: "係数種類",
            index: "MEISYO",
            width: 200,
            align: "left",
            sortable: false,
        },
        {
            name: "ATAI_1",
            label: "値1",
            index: "ATAI_1",
            width: 65,
            align: "left",
            hidden: true,
            sortable: false,
        },
        {
            name: "ATAI_1_NM",
            label: "人員割有無",
            index: "ATAI_1_NM",
            width: 100,
            align: "center",
            sortable: false,
        },
        {
            name: "ATAI_2",
            label: "支給計算書表示単位",
            index: "ATAI_2",
            width: 150,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
            },
        },
        {
            name: "HYOJI_JUN",
            label: "表示順",
            index: "HYOJI_JUN",
            width: 65,
            align: "right",
            sortable: false,
            editable: true,
            formatoptions: {
                decimalPlaces: 0,
                thousandsSeparator: "",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "4",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^[0-9]*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var idName = "HYOJI_JUN";
                            me.BlurHyoujijyunn(idName);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー  ↑，↓，tab，enter
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                key == 9 ||
                                key == 13
                            ) {
                                var idName = "HYOJI_JUN";
                                me.BlurHyoujijyunn(idName);
                            }
                        },
                    },
                ],
            },
        },
    ];

    //店長奨励_限界/経常利益取得部署
    me.sprTenSyutokuColM = [
        {
            name: "BUSYO",
            label: "部署コード",
            index: "BUSYO",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_2_jqgrid);
                            var idName = "BUSYO";
                            var vaGetName = "BUSYO_NM";
                            var itemCode = "BUSYO_CD";
                            var name = "BUSYO_NM";
                            var allParameter = me.allBusyoName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "BUSYO").val()
                                );
                                var itemCode = "BUSYO_CD";
                                var name = "BUSYO_NM";
                                var allParameter = me.allBusyoName;
                                $(me.tab4_2_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "BUSYO_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "RIEKI",
            label: "経常利益取得コード",
            index: "RIEKI",
            width: 145,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_2_jqgrid);
                            var idName = "RIEKI";
                            var vaGetName = "RIEKI_NM";
                            var itemCode = "BUSYO_CD";
                            var name = "BUSYO_NM";
                            var allParameter = me.allBusyoName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "RIEKI").val()
                                );
                                var itemCode = "BUSYO_CD";
                                var name = "BUSYO_NM";
                                var allParameter = me.allBusyoName;
                                $(me.tab4_2_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "RIEKI_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "RIEKI_NM",
            label: "名称",
            index: "RIEKI_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "GENKAI",
            label: "総限界取得コード",
            index: "GENKAI",
            width: 130,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_2_jqgrid);
                            var idName = "GENKAI";
                            var vaGetName = "GENKAI_NM";
                            var itemCode = "BUSYO_CD";
                            var name = "BUSYO_NM";
                            var allParameter = me.allBusyoName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "GENKAI").val()
                                );
                                var itemCode = "BUSYO_CD";
                                var name = "BUSYO_NM";
                                var allParameter = me.allBusyoName;
                                // 全部参数
                                $(me.tab4_2_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "GENKAI_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GENKAI_NM",
            label: "名称",
            index: "GENKAI_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
    ];
    //店長奨励_支給対象
    me.sprTenTaisyoColM = [
        {
            name: "BUSYO",
            label: "部署コード",
            index: "BUSYO",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_1_jqgrid);
                            var idName = "BUSYO";
                            var vaGetName = "BUSYO_NM";
                            var itemCode = "BUSYO_CD";
                            var name = "BUSYO_NM";
                            var allParameter = me.allBusyoName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "BUSYO").val()
                                );
                                var itemCode = "BUSYO_CD";
                                var name = "BUSYO_NM";
                                var allParameter = me.allBusyoName;
                                $(me.tab4_1_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "BUSYO_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "SYOKUSYU",
            label: "職種コード",
            index: "SYOKUSYU",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_1_jqgrid);
                            var idName = "SYOKUSYU";
                            var vaGetName = "SYOKUSYU_NM";
                            var itemCode = "CODE";
                            var name = "MEISYOU";
                            var allParameter = me.allSyokusyuName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "SYOKUSYU").val()
                                );
                                var itemCode = "CODE";
                                var name = "MEISYOU";
                                var allParameter = me.allSyokusyuName;
                                $(me.tab4_1_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "SYOKUSYU_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYOKUSYU_NM",
            label: "職種名",
            index: "SYOKUSYU_NM",
            width: 145,
            align: "left",
            sortable: false,
        },
        {
            name: "ROUTE",
            label: "販売ルート",
            index: "ROUTE",
            width: 80,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "90",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (_e) {
                            //ショートカットキー
                            var Id = $(me.tab4_1_jqgrid);
                            var idName = "ROUTE";
                            var vaGetName = "ROUTE_NM";
                            var itemCode = "CODE";
                            var name = "MEISYO";
                            var allParameter = me.allRouteName;
                            me.BlurKyoutsuu(
                                Id,
                                idName,
                                vaGetName,
                                itemCode,
                                name,
                                allParameter
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            //ショートカットキー
                            var key = e.charCode || e.keyCode;
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40
                            ) {
                                var vaGet = $.trim(
                                    $("#" + me.lastsel + "_" + "ROUTE").val()
                                );
                                var itemCode = "CODE";
                                var name = "MEISYO";
                                var allParameter = me.allRouteName;
                                // 全部参数
                                $(me.tab4_1_jqgrid).jqGrid(
                                    "setCell",
                                    me.lastsel,
                                    "ROUTE_NM",
                                    me.getName(
                                        me.clsComFnc.FncNv(vaGet),
                                        itemCode,
                                        name,
                                        allParameter
                                    )
                                );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ROUTE_NM",
            label: "販売ルート名",
            index: "ROUTE_NM",
            width: 140,
            align: "left",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddGyoKomoku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelGyoKomoku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddGyoTaisyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelGyoTaisyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddGyoJogen",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelGyoJogen",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddTenKomoku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelTenKomoku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddTenTaisyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelTenTaisyo",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnAddTenSyutoku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoreikinSyoriMente.btnDelTenSyutoku",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //更新ボタン
    $(".FrmSyoreikinSyoriMente.btnUpdate").click(function () {
        me.btnUpdate_Click();
    });
    //キャンセルボタン
    $(".FrmSyoreikinSyoriMente.btnCancel").click(function () {
        me.btnCancel_Click();
    });
    //行追加ボタン
    $(
        ".FrmSyoreikinSyoriMente.btnAddGyoKomoku,.FrmSyoreikinSyoriMente.btnAddGyoTaisyo,.FrmSyoreikinSyoriMente.btnAddGyoJogen,.FrmSyoreikinSyoriMente.btnAddTenKomoku,.FrmSyoreikinSyoriMente.btnAddTenTaisyo,.FrmSyoreikinSyoriMente.btnAddTenSyutoku"
    ).click(function () {
        me.btnRowAdd_Click();
    });
    //行削除ボタン
    $(
        ".FrmSyoreikinSyoriMente.btnDelGyoKomoku,.FrmSyoreikinSyoriMente.btnDelGyoTaisyo,.FrmSyoreikinSyoriMente.btnDelGyoJogen,.FrmSyoreikinSyoriMente.btnDelTenKomoku,.FrmSyoreikinSyoriMente.btnDelTenTaisyo,.FrmSyoreikinSyoriMente.btnDelTenSyutoku"
    ).click(function () {
        me.btnRowDel_Click();
    });
    //1.業績奨励係数管理---2.業績奨励支給管理---3.店長奨励係数管理---4.店長奨励支給管理
    $(
        ".FrmSyoreikinSyoriMente.tabsLI_Eigyoukeisu,.FrmSyoreikinSyoriMente.tabsLI_EigyouTen,.FrmSyoreikinSyoriMente.tabsLI_Tencyoukeisu,.FrmSyoreikinSyoriMente.tabsLI_TencyouTen"
    ).click(function () {
        me.TabControl_Click();
    });
    //1.業績奨励係数管理:係数種類---係数項目---係数種類別対象販売ルート
    $(
        ".FrmSyoreikinSyoriMente.rdoGyoKeisuSyurui,.FrmSyoreikinSyoriMente.rdoGyokeisuKomoku,.FrmSyoreikinSyoriMente.rdoGyoTaisyoRoute"
    ).click(function () {
        me.tab_rdo_Click();
    });
    //2.業績奨励支給管理:支給対象---係数項目---算出奨励金掛け率
    $(
        ".FrmSyoreikinSyoriMente.rdoGyoTaisyo,.FrmSyoreikinSyoriMente.rdoGyoJogen,.FrmSyoreikinSyoriMente.rdoGyoKakeritu"
    ).click(function () {
        me.tab_rdo_Click();
    });
    //3.店長奨励係数管理:係数種類---係数項目---係数種類別対象販売ルート
    $(
        ".FrmSyoreikinSyoriMente.rdoTenKeisuSyurui,.FrmSyoreikinSyoriMente.rdoTenkeisuKomoku,.FrmSyoreikinSyoriMente.rdoTenTaisyoRoute"
    ).click(function () {
        me.tab_rdo_Click();
    });
    //4.店長奨励支給管理:支給対象---支給上限---総限界利益掛け率---限界/経常取得部署
    $(
        ".FrmSyoreikinSyoriMente.rdoTenTaisyo,.FrmSyoreikinSyoriMente.rdoTenJogen,.FrmSyoreikinSyoriMente.rdoTenKakeritu,.FrmSyoreikinSyoriMente.rdoTenSyutoku"
    ).click(function () {
        me.tab_rdo_Click();
    });
    //1.業績奨励係数管理:[係数項目]係数種類
    $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").change(function () {
        me.cmbKeisuSyurui1_SelectionChangeCommitted("cmbGyoKeisuSyurui1");
    });
    //1.業績奨励係数管理:[係数種類別対象販売ルート]係数種類
    $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").change(function () {
        me.cmbKeisuSyurui2_SelectionChangeCommitted("cmbGyoKeisuSyurui2");
    });
    //3.店長奨励係数管理:[係数項目]係数種類
    $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").change(function () {
        me.cmbKeisuSyurui1_SelectionChangeCommitted("cmbTenKeisuSyurui1");
    });
    //3.店長奨励係数管理:[係数種類別対象販売ルート]係数種類
    $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").change(function () {
        me.cmbKeisuSyurui2_SelectionChangeCommitted("cmbTenKeisuSyurui2");
    });
    //2.業績奨励支給管理:正社員-------4.店長奨励支給管理:支給上限
    $(
        ".FrmSyoreikinSyoriMente.txtGyoJogen,.FrmSyoreikinSyoriMente.txtTenJogen"
    ).on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.txtJogen_Leave(e);
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                me.txtJogen_Leave(e);
            }
        }
    });
    //2.算出奨励金掛け率 4.総限界利益掛け率
    $(
        ".FrmSyoreikinSyoriMente.txtGyoKakeritu,.FrmSyoreikinSyoriMente.txtTenKakeritu"
    ).on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.txtKakeritu_Leave(e);
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                me.txtKakeritu_Leave(e);
            }
        }
    });

    //業績奨励_対象販売ルート
    $(me.tab1_3_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 104,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprGyoTaisyoRouteColM,
    });
    //業績奨励_係数種類
    $(me.tab1_1_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 130,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprGyoKeisuSyuruiColM,
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
                        $(me.tab1_1_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $("#" + rowid + "_ATAI_1").css("text-align", "right");
                    $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
                    $(me.tab1_1_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab1_1_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab1_1_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_ATAI_1").css("text-align", "right");
                $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab1_1_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab1_1_jqgrid).jqGrid("bindKeys");
    //業績奨励_係数項目
    $(me.tab1_2_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 130,
        width: 565,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprGyokeisuKomokuColM,
        shrinkToFit: false,

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
                        $(me.tab1_2_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab1_2_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab1_2_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab1_2_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab1_2_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab1_2_jqgrid).jqGrid("bindKeys");
    //業績奨励_支給上限
    $(me.tab2_2_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 156,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprGyoJogenColM,
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
                        $(me.tab2_2_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab2_2_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("#" + rowid + "_JOGEN").css("text-align", "right");
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab2_2_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab2_2_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_JOGEN").css("text-align", "right");
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab2_2_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab2_2_jqgrid).jqGrid("bindKeys");
    //業績奨励_支給対象
    $(me.tab2_1_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 208,
        rownumWidth: 30,
        multiselect: false,
        rownumbers: true,
        colModel: me.sprGyoTaisyoColM,
        onSelectRow: function (rowid, _status, e) {
            $(".numeric").numeric({
                decimal: false,
                negative: false,
            });

            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                //ヘッダークリック以外
                if (cellIndex != 0) {
                    if (rowid && rowid != me.lastsel) {
                        $(me.tab2_1_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab2_1_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab2_1_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab2_1_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab2_1_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });

    $(me.tab2_1_jqgrid).jqGrid("bindKeys");
    //店長奨励_対象販売ルート
    $(me.tab3_3_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 104,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprTenTaisyoRouteColM,
    });
    //店長奨励_係数項目
    $(me.tab3_2_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 130,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprTenkeisuKomokuColM,
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
                        $(me.tab3_2_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab3_2_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab3_2_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab3_2_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab3_2_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab3_2_jqgrid).jqGrid("bindKeys");
    //店長奨励_係数種類
    $(me.tab3_1_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 130,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprTenKeisuSyuruiColM,
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
                        $(me.tab3_1_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab3_1_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab3_1_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab3_1_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_HYOJI_JUN").css("text-align", "right");
            }
            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab3_1_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab3_1_jqgrid).jqGrid("bindKeys");
    //店長奨励_限界/経常利益取得部署
    $(me.tab4_2_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 156,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprTenSyutokuColM,
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
                        $(me.tab4_2_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab4_2_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab4_2_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab4_2_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab4_2_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab4_2_jqgrid).jqGrid("bindKeys");
    //店長奨励_支給対象
    $(me.tab4_1_jqgrid).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: 208,
        rownumWidth: 30,
        rownumbers: true,
        colModel: me.sprTenTaisyoColM,
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
                        $(me.tab4_1_jqgrid).jqGrid("saveRow", me.lastsel);
                        me.lastsel = rowid;
                    }
                    $(me.tab4_1_jqgrid).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("input,select", e.target).trigger("focus");
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.tab4_1_jqgrid).jqGrid("saveRow", me.lastsel);
                    me.lastsel = rowid;
                }
                $(me.tab4_1_jqgrid).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
            }

            var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                me.tab4_1_jqgrid,
                e,
                me.lastsel
            );
            if (up_next_sel && up_next_sel.length == 2) {
                me.upsel = up_next_sel[0];
                me.nextsel = up_next_sel[1];
            }
        },
    });
    $(me.tab4_1_jqgrid).jqGrid("bindKeys");
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        if ($(window).height() <= 950) {
            // 画面内容较多，IE显示不全，追加纵向滚动条
            $(".JKSYS.JKSYS-layout-center").css("overflow-y", "scroll");
            // 追加滚动条后调小宽度
            $(".JKSYS-content-fixed-width").css(
                "width",
                me.ratio === 1.5 ? "1032px" : "1100px"
            );
        }
        //フォームロード
        me.frmKeisuMstMente_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：frmKeisuMstMente_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.frmKeisuMstMente_Load = function () {
        //係数種類_ｺﾝﾎﾞﾎﾞｯｸｽｾｯﾄ
        me.subSetCmbKeisuSyurui();
    };
    /*
     '**********************************************************************
     '処 理 名：係数種類_ｺﾝﾎﾞｾｯﾄ
     '関 数 名：subSetCmbKeisuSyurui
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subSetCmbKeisuSyurui = function () {
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").empty();
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").empty();
        $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").empty();
        $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").empty();
        $(".FrmSyoreikinSyoriMente.btnAddGyoKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelGyoKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").prop("disabled", true);

        me.url = me.sys_id + "/" + me.id + "/subSetCmbKeisuSyurui";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                //ﾗｼﾞｵﾎﾞﾀﾝ初期化
                me.subRdoInit();
                //ボタン初期化
                $(".FrmSyoreikinSyoriMente button").button("disable");
                $(".FrmSyoreikinSyoriMente").prop("disabled", true);

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var dt = result["data"];
            for (var i = 0; i < dt["cmbGyoKeisuSyurui1"].length; i++) {
                $("<option></option>")
                    .val(dt["cmbGyoKeisuSyurui1"][i]["CODE"])
                    .text(dt["cmbGyoKeisuSyurui1"][i]["MEISYO"])
                    .appendTo(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1");
            }
            for (var i = 0; i < dt["cmbGyoKeisuSyurui2"].length; i++) {
                $("<option></option>")
                    .val(dt["cmbGyoKeisuSyurui2"][i]["CODE"])
                    .text(dt["cmbGyoKeisuSyurui2"][i]["MEISYO"])
                    .appendTo(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2");
            }
            for (var i = 0; i < dt["cmbTenKeisuSyurui1"].length; i++) {
                $("<option></option>")
                    .val(dt["cmbTenKeisuSyurui1"][i]["CODE"])
                    .text(dt["cmbTenKeisuSyurui1"][i]["MEISYO"])
                    .appendTo(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1");
            }
            for (var i = 0; i < dt["cmbTenKeisuSyurui2"].length; i++) {
                $("<option></option>")
                    .val(dt["cmbTenKeisuSyurui2"][i]["CODE"])
                    .text(dt["cmbTenKeisuSyurui2"][i]["MEISYO"])
                    .appendTo(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2");
            }
            //画面初期化
            me.subInit();
        };
        me.ajax.send(me.url, "", 0);
    };
    //**********************************************************************
    //処 理 名：キャンセルボタン
    //関 数 名：btnCancel_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.btnCancel_Click = function () {
        var rdoName = $("input[name='rdo']:checked").val();
        switch (rdoName) {
            case "rdoGyoKeisuSyurui":
                //業績奨励_係数種類
                me.getKeisuSyurui("1");
                break;
            case "rdoGyokeisuKomoku":
                //業績奨励_係数項目
                //係数種類选择空时，还原到初始化状态
                if (
                    $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").val() ==
                    "00"
                ) {
                    $(me.tab1_2_jqgrid).jqGrid("showCol", ["ATAI_1", "ATAI_2"]);
                    $(me.tab1_2_jqgrid).jqGrid("clearGridData");
                } else {
                    me.getKeisuKomoku("1");
                }
                break;
            case "rdoGyoTaisyoRoute":
                //業績奨励_対象販売ルート
                //係数種類选择空时，还原到初始化状态
                if (
                    $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").val() ==
                    "00"
                ) {
                    $(me.tab1_3_jqgrid).jqGrid("clearGridData");
                } else {
                    me.getTaisyoRoute("1");
                }
                break;
            case "rdoGyoTaisyo":
                //業績奨励_支給対象
                me.getSikyuTaisyo("1");
                break;
            case "rdoGyoJogen":
                //業績奨励_支給上限
                me.getJogen("1");
                break;
            case "rdoGyoKakeritu":
                //業績奨励_掛け率
                me.getKakeritu("1");
                break;
            case "rdoTenKeisuSyurui":
                //店長奨励_係数種類
                me.getKeisuSyurui("2");
                break;
            case "rdoTenkeisuKomoku":
                //店長奨励_係数項目
                //係数種類选择空时，还原到初始化状态
                if (
                    $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").val() ==
                    "00"
                ) {
                    $(me.tab3_2_jqgrid).jqGrid("clearGridData");
                } else {
                    me.getKeisuKomoku("2");
                }
                break;
            case "rdoTenTaisyoRoute":
                //店長奨励_対象販売ルート
                //係数種類选择空时，还原到初始化状态
                if (
                    $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").val() ==
                    "00"
                ) {
                    $(me.tab3_3_jqgrid).jqGrid("clearGridData");
                } else {
                    me.getTaisyoRoute("2");
                }
                break;
            case "rdoTenTaisyo":
                //店長奨励_支給対象
                me.getSikyuTaisyo("2");
                break;
            case "rdoTenJogen":
                //店長奨励_支給上限
                me.getJogen("2");
                break;
            case "rdoTenKakeritu":
                //店長奨励_掛け率
                me.getKakeritu("2");
                break;
            case "rdoTenSyutoku":
                //店長奨励_限界/経常利益取得部署
                me.getTenSyutoku();
                break;
        }
    };
    //**********************************************************************
    //処 理 名：更新ボタン
    //関 数 名：btnUpdate_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.btnUpdate_Click = function () {
        //入力チェック
        if (!me.fncCheckInput()) {
            return;
        }
        me.fncUpdate();
    };
    //**********************************************************************
    //処 理 名：行追加ボタン
    //関 数 名：btnRowAdd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.btnRowAdd_Click = function () {
        var rdoName = $("input[name='rdo']:checked").val();
        var cmb = "";
        var spr = "";
        var col = "";

        switch (rdoName) {
            case "rdoGyokeisuKomoku":
                //業績奨励_係数項目
                spr = $(me.tab1_2_jqgrid);
                col = me.SprGyokeisuCol;
                cmb = $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1");
                break;
            case "rdoGyoTaisyo":
                //業績奨励_支給対象
                spr = $(me.tab2_1_jqgrid);
                col = me.SprGyoTaisyoCol;
                break;
            case "rdoGyoJogen":
                //業績奨励_支給上限
                spr = $(me.tab2_2_jqgrid);
                col = me.SprGyoJogenCol;
                break;
            case "rdoTenkeisuKomoku":
                //店長奨励_係数項目
                spr = $(me.tab3_2_jqgrid);
                col = me.SprGyokeisuCol;
                cmb = $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1");
                break;
            case "rdoTenTaisyo":
                //店長奨励_支給対象
                spr = $(me.tab4_1_jqgrid);
                col = me.SprTenTaisyoCol;
                break;
            case "rdoTenSyutoku":
                //店長奨励_限界/経常利益取得部署
                spr = $(me.tab4_2_jqgrid);
                col = me.SprTenSyutokuCol;
                break;
        }
        //対象データが表示されていない場合
        if (cmb != "" && cmb.val() == "00") {
            me.clsComFnc.ObjFocus = cmb;
            me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
            return;
        }
        var ids = spr.jqGrid("getDataIDs");
        var selIRow = 0;
        if (ids.length > 0) {
            var selIRow = parseInt(ids.pop()) + 1;
        }
        spr.jqGrid("addRowData", selIRow, col);
        spr.jqGrid("saveRow", me.lastsel);
        spr.jqGrid("setSelection", selIRow, true);
    };
    //**********************************************************************
    //処 理 名：行削除ボタン
    //関 数 名：btnRowDel_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.btnRowDel_Click = function () {
        var rdoName = $("input[name='rdo']:checked").val();
        var cmb = "";
        var spr = "";

        switch (rdoName) {
            case "rdoGyokeisuKomoku":
                //業績奨励_係数項目
                spr = $(me.tab1_2_jqgrid);
                cmb = $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1");
                break;
            case "rdoGyoTaisyo":
                //業績奨励_支給対象
                spr = $(me.tab2_1_jqgrid);
                break;
            case "rdoGyoJogen":
                //業績奨励_支給上限
                spr = $(me.tab2_2_jqgrid);
                break;
            case "rdoTenkeisuKomoku":
                //店長奨励_係数項目
                spr = $(me.tab3_2_jqgrid);
                cmb = $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1");
                break;
            case "rdoTenTaisyo":
                //店長奨励_支給対象
                spr = $(me.tab4_1_jqgrid);
                break;
            case "rdoTenSyutoku":
                //店長奨励_限界/経常利益取得部署
                spr = $(me.tab4_2_jqgrid);
                break;
        }
        //対象データが表示されていない場合
        if (cmb != "" && cmb.val() == "00") {
            me.clsComFnc.ObjFocus = cmb;
            me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
            return;
        }

        var allIds = spr.jqGrid("getDataIDs");
        var selrow = spr.jqGrid("getGridParam", "selrow");
        if (allIds.length < 0 || selrow == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == selrow) {
                if (allIds[i] != allIds.pop()) {
                    spr.jqGrid("delRowData", selrow);

                    spr.jqGrid("setSelection", me.nextsel, true);
                } else {
                    spr.jqGrid("delRowData", selrow);

                    spr.jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };
    //**********************************************************************
    //処 理 名：係数項目コンボボックス変更
    //関 数 名：cmbKeisuSyurui1_change
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.cmbKeisuSyurui1_SelectionChangeCommitted = function (sender) {
        cmb = ".FrmSyoreikinSyoriMente." + sender;
        if ($(cmb).val() == "00") {
            // リストボックスがブランクならグリッドもブランク
            var spr = "";
            switch (sender) {
                case "cmbGyoKeisuSyurui1":
                    spr = me.tab1_2_jqgrid;
                    $(spr).jqGrid("showCol", ["ATAI_1", "ATAI_2"]);
                    break;
                case "cmbTenKeisuSyurui1":
                    spr = me.tab3_2_jqgrid;
                    break;
            }
            $(spr).jqGrid("clearGridData");
            return;
        }
        if (sender == "cmbGyoKeisuSyurui1") {
            if ($(cmb).val() == "01") {
                $(me.tab1_2_jqgrid).jqGrid("showCol", ["ATAI_1", "ATAI_2"]);
            } else {
                $(me.tab1_2_jqgrid).jqGrid("hideCol", ["ATAI_1", "ATAI_2"]);
            }
        }

        switch (sender) {
            case "cmbGyoKeisuSyurui1":
                //業績奨励_係数項目
                me.getKeisuKomoku("1", true);
                break;
            case "cmbTenKeisuSyurui1":
                //店長奨励_係数項目
                me.getKeisuKomoku("2", true);
                break;
        }
    };
    //**********************************************************************
    //処 理 名：対象販売ルートコンボボックス変更
    //関 数 名：cmbKeisuSyurui2_SelectionChangeCommitted
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.cmbKeisuSyurui2_SelectionChangeCommitted = function (sender) {
        cmb = ".FrmSyoreikinSyoriMente." + sender;
        if ($(cmb).val() == "00") {
            // リストボックスがブランクならグリッドもブランク
            var spr = "";
            switch (sender) {
                case "cmbGyoKeisuSyurui2":
                    spr = me.tab1_3_jqgrid;
                    break;
                case "cmbTenKeisuSyurui2":
                    spr = me.tab3_3_jqgrid;
                    break;
            }
            $(spr).jqGrid("clearGridData");
            return;
        }

        switch (sender) {
            case "cmbGyoKeisuSyurui2":
                //業績奨励_対象販売ルート
                me.getTaisyoRoute("1");
                break;
            case "cmbTenKeisuSyurui2":
                //店長奨励_対象販売ルート
                me.getTaisyoRoute("2");
                break;
        }
    };
    //**********************************************************************
    //処 理 名：支給上限値変更
    //関 数 名：txtJogen_Leave
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.txtJogen_Leave = function (event) {
        if ($(event.target).val() == "") {
            return;
        }
        //数値チェック
        if (
            me.clsComFnc.FncTextCheck($(event.target), 0, 1) == -2 ||
            $(event.target).val() == "-" ||
            $(event.target).val() == "."
        ) {
            me.clsComFnc.ObjFocus = $(event.target);
            me.clsComFnc.FncMsgBox("W0002", "支給上限");
            return;
        }
        //カンマ編集
        $(event.target).css(me.clsComFnc.GC_COLOR_NORMAL);
        var value = $(event.target).val().replace(/,/g, "");
        // 0 -> 空
        if (isNaN(value)) {
            value = "";
        }
        value = me.removeZero(value);
        if (value != "") {
            value = new BigNumber(value).toFixed(0);
        }
        $(event.target).val(value == 0 ? "" : me.numFormat(value));
    };
    me.txtKakeritu_Leave = function (event) {
        if ($(event.target).val() == "") {
            return;
        }
        //数値チェック
        if (
            me.clsComFnc.FncTextCheck($(event.target), 0, 1) == -2 ||
            $(event.target).val() == "-" ||
            $(event.target).val() == "."
        ) {
            me.clsComFnc.ObjFocus = $(event.target);
            me.clsComFnc.FncMsgBox("W0002", "掛け率");
            return;
        }
        //カンマ編集
        $(event.target).css(me.clsComFnc.GC_COLOR_NORMAL);
        var value = $(event.target).val();
        // 0 -> 空
        if (isNaN(value)) {
            value = "";
        }
        value = me.removeZero(value);
        $(event.target).val(value);
    };
    /*
     '**********************************************************************
     '処 理 名：画面初期化
     '関 数 名：subInit
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subInit = function () {
        //ﾗｼﾞｵﾎﾞﾀﾝﾁｪｯｸ初期化
        me.subRdoChkInit();

        //ﾗｼﾞｵﾎﾞﾀﾝ初期化
        me.subRdoInit();

        me.url = me.sys_id + "/" + me.id + "/subInit";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmSyoreikinSyoriMente button").button("disable");
                $(".FrmSyoreikinSyoriMente").prop("disabled", true);

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var data = result["data"];
            //業績奨励_係数種類
            if (!me.setKeisuSyurui("1", data["getKeisuSyurui1"])) {
                me.clsComFnc.FncMsgBox("W0008", "業績奨励_係数種類");
            }
            me.resetSelection(me.tab1_1_jqgrid);
            //業績奨励_係数項目
            $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").val("00");
            $(me.tab1_2_jqgrid).jqGrid("clearGridData");
            //業績奨励_対象販売ルート
            $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").val("00");
            $(me.tab1_3_jqgrid).jqGrid("clearGridData");
            //業績奨励_支給対象
            if (!me.setSikyuTaisyo("1", data["getSikyuTaisyo1"])) {
                me.clsComFnc.FncMsgBox("W0008", "業績奨励_支給対象");
            }
            me.resetSelection(me.tab2_1_jqgrid);
            //業績奨励_支給上限
            if (!me.setJogen("1", data["getJogen1"], data["txtGyoJogen"])) {
                me.clsComFnc.FncMsgBox("W0008", "業績奨励_支給上限");
            }
            me.resetSelection(me.tab2_2_jqgrid);
            //業績奨励_掛け率
            if (!me.setKakeritu("1", data["getKakeritu1"])) {
                me.clsComFnc.FncMsgBox("W0008", "業績奨励_算出奨励金掛け率");
            }
            //店長奨励_係数種類
            if (!me.setKeisuSyurui("2", data["getKeisuSyurui2"])) {
                me.clsComFnc.FncMsgBox("W0008", "店長奨励_係数種類");
            }
            me.resetSelection(me.tab3_1_jqgrid);
            //店長奨励_係数項目
            $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").val("00");
            $(me.tab3_2_jqgrid).jqGrid("clearGridData");
            //店長奨励_対象販売ルート
            $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").val("00");
            $(me.tab3_3_jqgrid).jqGrid("clearGridData");
            //店長奨励_支給対象
            if (!me.setSikyuTaisyo("2", data["getSikyuTaisyo2"])) {
                me.clsComFnc.FncMsgBox("W0008", "店長奨励_支給対象");
            }
            //店長奨励_支給上限
            if (!me.setJogen("2", "", data["txtTenJogen"])) {
                me.clsComFnc.FncMsgBox("W0008", "店長奨励_支給上限");
            }

            me.resetSelection(me.tab4_1_jqgrid);
            //店長奨励_掛け率
            if (!me.setKakeritu("2", data["getKakeritu2"])) {
                me.clsComFnc.FncMsgBox("W0008", "店長奨励_総限界利益掛け率");
            }
            //店長奨励_限界/経常利益取得部署
            if (!me.setTenSyutoku(data["getTenSyutoku"])) {
                me.clsComFnc.FncMsgBox(
                    "W0008",
                    "店長奨励_限界/経常利益取得部署"
                );
            }

            me.resetSelection(me.tab4_2_jqgrid);
            me.allBusyoName = data["allBusyoName"]["data"];
            me.allRouteName = data["allRouteName"]["data"];
            me.allKoyouName = data["allKoyouName"]["data"];
            me.allSyokusyuName = data["allSyokusyuName"]["data"];

            $(
                ".FrmSyoreikinSyoriMente.sprTenKeisuSyurui input[type='checkbox']"
            ).prop("disabled", true);
            //ボタン初期化
            $(".FrmSyoreikinSyoriMente.btnCancel").button("disable");
            $(".FrmSyoreikinSyoriMente.btnUpdate").button("disable");
        };

        me.ajax.send(me.url, "", 0);
    };
    // jqgrid reset selection
    me.resetSelection = function (gridId) {
        $(gridId).jqGrid("setSelection", 0, true);
        $(gridId).jqGrid("saveRow", me.lastsel);
        $(gridId).jqGrid("resetSelection");
    };
    /*
     '**********************************************************************
     '処 理 名：入力チェック
     '関 数 名：fncCheckInput
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncCheckInput = function () {
        var spr = "";
        var cmb = "";
        var chk = 0;

        var rdoName = $("input[name='rdo']:checked").val();
        if (rdoName == "rdoGyoKeisuSyurui") {
            //-------------------
            // 業績奨励_係数種類
            //-------------------
            $(me.tab1_1_jqgrid).jqGrid("saveRow", me.lastsel);
            spr = $(me.tab1_1_jqgrid);
            for (var i = 0; i < spr.jqGrid("getGridParam", "records"); i++) {
                //表示順 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", i, "HYOJI_JUN")
                        ),
                        1,
                        me.clsComFnc.INPUTTYPE.NUMBER1,
                        4
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "表示順");
                    return false;
                }
            }
        } else if (rdoName == "rdoGyokeisuKomoku") {
            //-------------------
            //業績奨励_係数項目
            //-------------------
            $(me.tab1_2_jqgrid).jqGrid("saveRow", me.lastsel);
            spr = $(me.tab1_2_jqgrid);
            cmb = $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1");
            //係数選択チェック
            if (!cmb.val() || cmb.val() == "00") {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
                return false;
            }
            for (var i = 0; i < spr.jqGrid("getGridParam", "records"); i++) {
                //名称 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(spr.jqGrid("getCell", i, "MEISYO")),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        50
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "名称");
                    return false;
                }

                // 係数種類 = 販売ルート
                if (
                    $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").val() ==
                    "01"
                ) {
                    //掛け率 必須チェック
                    if (
                        me.clsComFnc.FncSprCheck(
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", i, "ATAI_1")
                            ),
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER2,
                            100
                        ) == -1
                    ) {
                        me.clsComFnc.FncMsgBox("W0001", "掛け率");
                        return false;
                    }
                    //表示販売ルート名 必須チェック
                    if (
                        me.clsComFnc.FncSprCheck(
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", i, "ATAI_2")
                            ),
                            1,
                            me.clsComFnc.INPUTTYPE.NONE,
                            20
                        ) == -1
                    ) {
                        me.clsComFnc.FncMsgBox("W0001", "表示販売ルート名");
                        return false;
                    }
                }

                //表示順 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", i, "HYOJI_JUN")
                        ),
                        1,
                        me.clsComFnc.INPUTTYPE.NUMBER1,
                        4
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "表示順");
                    return false;
                }
            }
        } else if (rdoName == "rdoGyoTaisyoRoute") {
            //-------------------
            //業績奨励_対象販売ルート
            //-------------------
            $(me.tab1_3_jqgrid).jqGrid("saveRow", me.lastsel);
            cmb = $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2");
            //係数選択チェック
            if (!cmb.val() || cmb.val() == "00") {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
                return false;
            }
        } else if (rdoName == "rdoGyoTaisyo") {
            //-------------------
            //業績奨励_支給対象
            //-------------------
            $(me.tab2_1_jqgrid).jqGrid("saveRow", me.lastsel);
            spr = $(me.tab2_1_jqgrid);
            var ids = spr.jqGrid("getDataIDs");
            //支給対象 登録データチェック
            if (spr.jqGrid("getGridParam", "records") == 0) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支給対象には１件以上登録してください。"
                );
                return false;
            }
            for (var i = 0; i < ids.length; i++) {
                rowdata = spr.jqGrid("getRowData", ids[i]);
                //職種コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "職種コード");
                    return false;
                }
                //職種コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        "CODE",
                        "MEISYOU",
                        me.allSyokusyuName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "職種コード");
                    return false;
                }
                //部署コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "部署コード");
                    return false;
                }
                //部署コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        "BUSYO_CD",
                        "BUSYO_NM",
                        me.allBusyoName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "部署コード");
                    return false;
                }
                //販売ルート 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["ROUTE"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        100
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "販売ルート");
                    return false;
                }
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["ROUTE"]),
                        "CODE",
                        "MEISYO",
                        me.allRouteName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "販売ルート");
                    return false;
                }
                //重複チェック
                for (
                    var j = i + 1;
                    j < spr.jqGrid("getGridParam", "records");
                    j++
                ) {
                    if (
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "BUSYO")
                            ) !==
                            "" &&
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "BUSYO")
                            ) !==
                            ""
                    ) {
                        if (
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[i], "BUSYO")
                                ) ==
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[j], "BUSYO")
                                )
                        ) {
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "職種&部署コードが重複しています。(職種コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid(
                                            "getCell",
                                            ids[i],
                                            "SYOKUSYU"
                                        )
                                    ) +
                                    "、部署コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid("getCell", ids[i], "BUSYO")
                                    ) +
                                    ")"
                            );
                            return false;
                        }
                    }
                }
            }
        } else if (rdoName == "rdoGyoJogen") {
            $(me.tab2_2_jqgrid).jqGrid("saveRow", me.lastsel);
            cmb = $(".FrmSyoreikinSyoriMente.txtGyoJogen");
            //-------------------
            //業績奨励_支給上限
            //-------------------
            //** 正社員 **
            chk = me.clsComFnc.FncTextCheck(
                cmb,
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2,
                100
            );
            //必須チェック
            if (chk == -1) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0001", "上限額");
                return false;
            }
            //数値チェック
            if (chk == -2) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0002", "上限額");
                return false;
            }
            //桁数チェック
            if (chk == -3) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0003", "上限額");
                return false;
            }
            //** 正社員以外 **
            spr = $(me.tab2_2_jqgrid);
            var ids = spr.jqGrid("getDataIDs");
            for (var i = 0; i < ids.length; i++) {
                rowdata = spr.jqGrid("getRowData", ids[i]);
                //雇用区分コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["KOYOU"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        2
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "雇用区分コード");
                    return false;
                }
                //雇用区分コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["KOYOU"]),
                        "KUBUN_CD",
                        "KUBUN_NM",
                        me.allKoyouName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "雇用区分コード");
                    return false;
                }
                //職種コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "職種コード");
                    return false;
                }
                //職種コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        "CODE",
                        "MEISYOU",
                        me.allSyokusyuName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "職種コード");
                    return false;
                }
                //上限額　必須チェック
                if (rowdata["JOGEN"] == "" || rowdata["JOGEN"] == "0") {
                    me.clsComFnc.FncMsgBox("W0001", "上限額");
                    return false;
                }
                //重複チェック
                for (
                    var j = i + 1;
                    j < spr.jqGrid("getGridParam", "records");
                    j++
                ) {
                    if (
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[i], "KOYOU")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                            ) !==
                            "" &&
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[j], "KOYOU")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                            ) !==
                            ""
                    ) {
                        if (
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "KOYOU")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                                ) ==
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "KOYOU")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                                )
                        ) {
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "雇用区分&職種コードが重複しています。(雇用区分コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid("getCell", ids[i], "KOYOU")
                                    ) +
                                    "、職種コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid(
                                            "getCell",
                                            ids[i],
                                            "SYOKUSYU"
                                        )
                                    ) +
                                    ")"
                            );
                            return false;
                        }
                    }
                }
            }
        } else if (rdoName == "rdoGyoKakeritu") {
            cmb = $(".FrmSyoreikinSyoriMente.txtGyoKakeritu");
            //-------------------
            //業績奨励_掛け率
            //-------------------
            chk = me.clsComFnc.FncTextCheck(
                cmb,
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2,
                100
            );
            //必須チェック
            if (chk == -1) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0001", "算出奨励金掛け率");
                return false;
            }
            //数値チェック
            if (chk == -2) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0002", "算出奨励金掛け率");
                return false;
            }
            //桁数チェック
            if (chk == -3) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0003", "算出奨励金掛け率");
                return false;
            }
        } else if (rdoName == "rdoTenKeisuSyurui") {
            //-------------------
            //店長奨励_係数種類
            //-------------------
            $(me.tab3_1_jqgrid).jqGrid("saveRow", me.lastsel);
            spr = $(me.tab3_1_jqgrid);
            for (var i = 0; i < spr.jqGrid("getGridParam", "records"); i++) {
                //支給計算書表示単位 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(spr.jqGrid("getCell", i, "ATAI_2")),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        20
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "支給計算書表示単位");
                    return false;
                }
                //表示順 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", i, "HYOJI_JUN")
                        ),
                        1,
                        me.clsComFnc.INPUTTYPE.NUMBER1,
                        4
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "表示順");
                    return false;
                }
            }
        } else if (rdoName == "rdoTenkeisuKomoku") {
            //-------------------
            //店長奨励_係数項目
            //-------------------
            spr = $(me.tab3_2_jqgrid);
            $(me.tab3_2_jqgrid).jqGrid("saveRow", me.lastsel);
            cmb = $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1");
            //係数選択チェック
            if (!cmb.val() || cmb.val() == "00") {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
                return false;
            }
            for (var i = 0; i < spr.jqGrid("getGridParam", "records"); i++) {
                //名称 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(spr.jqGrid("getCell", i, "MEISYO")),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        50
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "名称");
                    return false;
                }
                //表示順 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", i, "HYOJI_JUN")
                        ),
                        1,
                        me.clsComFnc.INPUTTYPE.NUMBER1,
                        4
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "表示順");
                    return false;
                }
            }
        } else if (rdoName == "rdoTenTaisyoRoute") {
            //-------------------
            //店長奨励_対象販売ルート
            //-------------------
            $(me.tab3_3_jqgrid).jqGrid("saveRow", me.lastsel);
            cmb = $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2");
            //係数選択チェック
            if (!cmb.val() || cmb.val() == "00") {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W9999", "係数種類を選択してください。");
                return false;
            }
        } else if (rdoName == "rdoTenTaisyo") {
            //-------------------
            //店長奨励_支給対象
            //-------------------
            spr = $(me.tab4_1_jqgrid);
            $(me.tab4_1_jqgrid).jqGrid("saveRow", me.lastsel);
            var ids = spr.jqGrid("getDataIDs");

            //支給対象 登録データチェック
            if (spr.jqGrid("getGridParam", "records") == 0) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支給対象には１件以上登録してください。"
                );
                return false;
            }
            for (var i = 0; i < ids.length; i++) {
                rowdata = spr.jqGrid("getRowData", ids[i]);
                //部署コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "部署コード");
                    return false;
                }
                //部署コード 存在チェック

                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        "BUSYO_CD",
                        "BUSYO_NM",
                        me.allBusyoName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "部署コード");
                    return false;
                }
                //職種コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "職種コード");
                    return false;
                }
                //職種コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["SYOKUSYU"]),
                        "CODE",
                        "MEISYOU",
                        me.allSyokusyuName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "職種コード");
                    return false;
                }
                //販売ルート 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["ROUTE"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        100
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "販売ルート");
                    return false;
                }
                //販売ルート 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["ROUTE"]),
                        "CODE",
                        "MEISYO",
                        me.allRouteName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "販売ルート");
                    return false;
                }
                //重複チェック
                for (
                    var j = i + 1;
                    j < spr.jqGrid("getGridParam", "records");
                    j++
                ) {
                    if (
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[i], "BUSYO")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                            ) !==
                            "" &&
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[j], "BUSYO")
                        ) +
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                            ) !==
                            ""
                    ) {
                        if (
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "BUSYO")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[i], "SYOKUSYU")
                                ) ==
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "BUSYO")
                            ) +
                                me.clsComFnc.FncNv(
                                    spr.jqGrid("getCell", ids[j], "SYOKUSYU")
                                )
                        ) {
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "部署&職種コードが重複しています。(部署コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid("getCell", ids[i], "BUSYO")
                                    ) +
                                    "、職種コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid(
                                            "getCell",
                                            ids[i],
                                            "SYOKUSYU"
                                        )
                                    ) +
                                    ")"
                            );
                            return false;
                        }
                    }
                }
            }
        } else if (rdoName == "rdoTenJogen") {
            cmb = $(".FrmSyoreikinSyoriMente.txtTenJogen");
            //-------------------
            //店長奨励_支給上限
            //-------------------
            chk = me.clsComFnc.FncTextCheck(
                cmb,
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2,
                100
            );
            //必須チェック
            if (chk == -1) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0001", "上限額");
                return false;
            }
            //数値チェック
            if (chk == -2) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0002", "上限額");
                return false;
            }
            //桁数チェック
            if (chk == -3) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0003", "上限額");
                return false;
            }
        } else if (rdoName == "rdoTenKakeritu") {
            cmb = $(".FrmSyoreikinSyoriMente.txtTenKakeritu");
            //-------------------
            //店長奨励_掛け率
            //-------------------
            chk = me.clsComFnc.FncTextCheck(
                cmb,
                1,
                me.clsComFnc.INPUTTYPE.NUMBER2,
                100
            );
            //必須チェック
            if (chk == -1) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0001", "総限界利益掛け率");
                return false;
            }
            //数値チェック
            if (chk == -2) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0002", "総限界利益掛け率");
                return false;
            }
            //桁数チェック
            if (chk == -3) {
                me.clsComFnc.ObjFocus = cmb;
                me.clsComFnc.FncMsgBox("W0003", "総限界利益掛け率");
                return false;
            }
        } else if (rdoName == "rdoTenSyutoku") {
            //-------------------
            //店長奨励_限界/経常利益取得部署
            //-------------------
            spr = $(me.tab4_2_jqgrid);
            $(me.tab4_2_jqgrid).jqGrid("saveRow", me.lastsel);
            var ids = spr.jqGrid("getDataIDs");
            for (var i = 0; i < ids.length; i++) {
                rowdata = spr.jqGrid("getRowData", ids[i]);
                //部署コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "部署コード");
                    return false;
                }
                //部署コード 存在チェック
                if (
                    me.getName(
                        me.clsComFnc.FncNv(rowdata["BUSYO"]),
                        "BUSYO_CD",
                        "BUSYO_NM",
                        me.allBusyoName
                    ) == null
                ) {
                    me.clsComFnc.FncMsgBox("W0008", "部署コード");
                    return false;
                }
                //経常利益取得コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["RIEKI"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "経常利益取得コード");
                    return false;
                }
                //総限界取得コード 必須チェック
                if (
                    me.clsComFnc.FncSprCheck(
                        me.clsComFnc.FncNv(rowdata["GENKAI"]),
                        1,
                        me.clsComFnc.INPUTTYPE.NONE,
                        3
                    ) == -1
                ) {
                    me.clsComFnc.FncMsgBox("W0001", "総限界取得コード");
                    return false;
                }
                //重複チェック
                for (
                    var j = i + 1;
                    j < spr.jqGrid("getGridParam", "records");
                    j++
                ) {
                    if (
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[i], "BUSYO")
                        ) !== "" &&
                        me.clsComFnc.FncNv(
                            spr.jqGrid("getCell", ids[j], "BUSYO")
                        ) !== ""
                    ) {
                        if (
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[i], "BUSYO")
                            ) ==
                            me.clsComFnc.FncNv(
                                spr.jqGrid("getCell", ids[j], "BUSYO")
                            )
                        ) {
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "部署コードが重複しています。(部署コード：" +
                                    me.clsComFnc.FncNv(
                                        spr.jqGrid("getCell", ids[i], "BUSYO")
                                    ) +
                                    ")"
                            );

                            return false;
                        }
                    }
                }
            }
        }
        return true;
    };

    //**********************************************************************
    //処 理 名：更新処理
    //関 数 名：fncUpdate
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.fncUpdate = function () {
        var rdoName = $("input[name='rdo']:checked").val();

        if (rdoName == "rdoGyoKeisuSyurui") {
            //-------------------
            // 業績奨励_係数種類
            //-------------------
            me.data = {
                rdoName: rdoName,
                datas: $(me.tab1_1_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoGyokeisuKomoku") {
            //-------------------
            // 業績奨励_係数項目
            //-------------------
            me.data = {
                rdoName: rdoName,
                SelectedValue: $(
                    ".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1"
                ).val(),
                datas: $(me.tab1_2_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoGyoTaisyoRoute") {
            //-------------------
            // 業績奨励_対象販売ルート
            //-------------------
            me.data = {
                rdoName: rdoName,
                SelectedValue: $(
                    ".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2"
                ).val(),
                datas: $(me.tab1_3_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoGyoTaisyo") {
            //-------------------
            // 業績奨励_支給対象
            //-------------------
            me.data = {
                rdoName: rdoName,
                datas: $(me.tab2_1_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoGyoJogen") {
            //-------------------
            // 業績奨励_支給上限
            //-------------------
            me.data = {
                rdoName: rdoName,
                txtGyoJogen: $(".FrmSyoreikinSyoriMente.txtGyoJogen").val(),
                datas: $(me.tab2_2_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoGyoKakeritu") {
            //-------------------
            // 業績奨励_掛け率
            //-------------------
            me.data = {
                rdoName: rdoName,
                txtGyoKakeritu: $(
                    ".FrmSyoreikinSyoriMente.txtGyoKakeritu"
                ).val(),
            };
        } else if (rdoName == "rdoTenKeisuSyurui") {
            //-------------------
            // 店長奨励_係数種類
            //-------------------
            me.data = {
                rdoName: rdoName,
                datas: $(me.tab3_1_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoTenkeisuKomoku") {
            //-------------------
            // 店長奨励_係数項目
            //-------------------
            me.data = {
                rdoName: rdoName,
                SelectedValue: $(
                    ".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1"
                ).val(),
                datas: $(me.tab3_2_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoTenTaisyoRoute") {
            //-------------------
            // 店長奨励_対象販売ルート
            //-------------------
            me.data = {
                rdoName: rdoName,
                SelectedValue: $(
                    ".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2"
                ).val(),
                datas: $(me.tab3_3_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoTenTaisyo") {
            //-------------------
            // 店長奨励_支給対象
            //-------------------
            me.data = {
                rdoName: rdoName,
                datas: $(me.tab4_1_jqgrid).jqGrid("getRowData"),
            };
        } else if (rdoName == "rdoTenJogen") {
            //-------------------
            // 店長奨励_支給上限
            //-------------------
            me.data = {
                rdoName: rdoName,
                txtTenJogen: $(".FrmSyoreikinSyoriMente.txtTenJogen").val(),
            };
        } else if (rdoName == "rdoTenKakeritu") {
            //-------------------
            // 店長奨励_掛け率
            //-------------------
            me.data = {
                rdoName: rdoName,
                txtTenKakeritu: $(
                    ".FrmSyoreikinSyoriMente.txtTenKakeritu"
                ).val(),
            };
        } else if (rdoName == "rdoTenSyutoku") {
            //-------------------
            // 店長奨励_限界/経常利益取得部署
            //-------------------
            me.data = {
                rdoName: rdoName,
                datas: $(me.tab4_2_jqgrid).jqGrid("getRowData"),
            };
        }
        me.url = me.sys_id + "/" + me.id + "/fncUpdate";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //更新完了メッセージ
            me.clsComFnc.FncMsgBox("I0003");

            //画面初期化
            me.subInit();
        };

        me.ajax.send(me.url, me.data, 0);
    };
    //**********************************************************************
    //処 理 名：業績奨励_係数種類
    //関 数 名：getKeisuSyurui
    //引    数：無し
    //戻 り 値：無し
    //処理説明：業績=1、店長=2
    //**********************************************************************
    me.getKeisuSyurui = function (strSyoreiKbn) {
        me.url = me.sys_id + "/" + me.id + "/getKeisuSyurui";
        me.data = {
            strSyoreiKbn: strSyoreiKbn,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.setKeisuSyurui(strSyoreiKbn, result);
            var spr = "";
            if (strSyoreiKbn == 1) {
                spr = me.tab1_1_jqgrid;
            } else {
                spr = me.tab3_1_jqgrid;
            }
            me.resetSelection(spr);
        };

        me.ajax.send(me.url, me.data, 0);
    };
    //**********************************************************************
    //処 理 名：係数項目セット
    //関 数 名：getKeisuKomoku
    //引    数：無し
    //戻 り 値：無し
    //処理説明：業績=1、店長=2
    //**********************************************************************
    me.getKeisuKomoku = function (strSyoreiKbn, nodataHasMsg) {
        var spr = "";
        if (strSyoreiKbn == "1") {
            spr = me.tab1_2_jqgrid;
            var SelectedValue = $(
                ".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1"
            ).val();
        } else {
            spr = me.tab3_2_jqgrid;
            var SelectedValue = $(
                ".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1"
            ).val();
        }
        $(spr).jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/getKeisuKomoku";
        me.data = {
            strSyoreiKbn: strSyoreiKbn,
            SelectedValue: SelectedValue,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["row"] == 0 && nodataHasMsg) {
                if (strSyoreiKbn == "1") {
                    me.clsComFnc.FncMsgBox("W0008", "業績奨励_係数項目");
                } else {
                    me.clsComFnc.FncMsgBox("W0008", "店長奨励_係数項目");
                }
                return;
            }
            for (var i = 0; i < result["data"].length; i++) {
                $(spr).jqGrid("addRowData", i, result["data"][i]);
            }
            if (strSyoreiKbn == "1") {
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").trigger(
                    "focus"
                );
            } else {
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").trigger(
                    "focus"
                );
            }
            $(spr).jqGrid("setSelection", 0, true);
            $(spr).jqGrid("saveRow", me.lastsel);
        };

        me.ajax.send(me.url, me.data, 0);
    };
    //**********************************************************************
    //処 理 名：対象販売ルートセット
    //関 数 名：getTaisyoRoute
    //引    数：無し
    //戻 り 値：無し
    //処理説明：業績=1、店長=2
    //**********************************************************************
    me.getTaisyoRoute = function (strSyoreiKbn) {
        var spr = "";
        if (strSyoreiKbn == "1") {
            spr = me.tab1_3_jqgrid;
            var SelectedValue = $(
                ".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2"
            ).val();
        } else {
            spr = me.tab3_3_jqgrid;
            var SelectedValue = $(
                ".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2"
            ).val();
        }
        $(spr).jqGrid("clearGridData");

        me.url = me.sys_id + "/" + me.id + "/getTaisyoRoute";
        me.data = {
            strSyoreiKbn: strSyoreiKbn,
            SelectedValue: SelectedValue,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["data"]["SprTaisyoRoute"]["row"] > 0) {
                for (
                    var i = 0;
                    i < result["data"]["SprTaisyoRoute"]["data"].length;
                    i++
                ) {
                    $(spr).jqGrid(
                        "addRowData",
                        i,
                        result["data"]["SprTaisyoRoute"]["data"][i]
                    );
                }
                for (var i = 0; i < result["data"]["dt"]["data"].length; i++) {
                    for (
                        var j = 0;
                        j < result["data"]["SprTaisyoRoute"]["row"];
                        j++
                    ) {
                        var CODE = $(spr).jqGrid("getCell", j, "CODE");
                        if (result["data"]["dt"]["data"][i]["CODE"] == CODE) {
                            $(spr).jqGrid("setCell", j, "CHECK", true);
                        }
                    }
                }
                $(spr).jqGrid("setSelection", 0, true);
                $(spr).jqGrid("saveRow", me.lastsel);
            }
        };

        me.ajax.send(me.url, me.data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：係数種類セット
     '関 数 名：setKeisuSyurui
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：業績=1、店長=2
     '**********************************************************************
     */
    me.setKeisuSyurui = function (strSyoreiKbn, dataSource) {
        var spr = "";
        if (strSyoreiKbn == "1") {
            spr = me.tab1_1_jqgrid;
        } else {
            spr = me.tab3_1_jqgrid;
        }

        $(spr).jqGrid("clearGridData");

        if (dataSource["row"] == 0) {
            return false;
        }
        for (var i = 0; i < dataSource["data"].length; i++) {
            $(spr).jqGrid("addRowData", i, dataSource["data"][i]);
        }
        //チェックボックス設定
        if (strSyoreiKbn != "1") {
            var checkstr = "";
            for (var i = 0; i < dataSource["data"].length; i++) {
                if (dataSource["data"][i]["ATAI_1"] == "1") {
                    checkstr =
                        "<input type='checkbox' checked='checked' value='true' offval='no' onclick=\"if(this.checked == true){$('.FrmSyoreikinSyoriMente.KeisuSyuruiCheck_" +
                        i +
                        "').html('  有');}else{$('.FrmSyoreikinSyoriMente.KeisuSyuruiCheck_" +
                        i +
                        "').html('  無');}  \" /><span class='FrmSyoreikinSyoriMente KeisuSyuruiCheck_" +
                        i +
                        "'>  有</span>";
                } else {
                    checkstr =
                        "<input type='checkbox' value='false' offval='no' onclick=\"if(this.checked == true){$('.FrmSyoreikinSyoriMente.KeisuSyuruiCheck_" +
                        i +
                        "').html('  有');}else{$('.FrmSyoreikinSyoriMente.KeisuSyuruiCheck_" +
                        i +
                        "').html('  無');}  \" /><span class='FrmSyoreikinSyoriMente KeisuSyuruiCheck_" +
                        i +
                        "'>  無</span>";
                }
                $(spr).jqGrid("setCell", i, "ATAI_1_NM", checkstr);
            }
        }
        return true;
    };
    //**********************************************************************
    //処 理 名：支給対象
    //関 数 名：getSikyuTaisyo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：業績=1、店長=2
    //**********************************************************************
    me.getSikyuTaisyo = function (strSyoreiKbn) {
        me.url = me.sys_id + "/" + me.id + "/getSikyuTaisyo";
        me.data = {
            strSyoreiKbn: strSyoreiKbn,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.setSikyuTaisyo(strSyoreiKbn, result);
            var spr = "";
            if (strSyoreiKbn == 1) {
                spr = me.tab2_1_jqgrid;
            } else {
                spr = me.tab4_1_jqgrid;
            }
            me.resetSelection(spr);
        };

        me.ajax.send(me.url, me.data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：支給対象セット
     '関 数 名：setSikyuTaisyo
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：業績=1、店長=2
     '**********************************************************************
     */
    me.setSikyuTaisyo = function (strSyoreiKbn, dataSource) {
        var spr = "";
        if (strSyoreiKbn == "1") {
            spr = me.tab2_1_jqgrid;
        } else {
            spr = me.tab4_1_jqgrid;
        }
        $(spr).jqGrid("clearGridData");
        if (dataSource["row"] == 0) {
            return false;
        }
        for (var i = 0; i < dataSource["data"].length; i++) {
            $(spr).jqGrid("addRowData", i, dataSource["data"][i]);
        }

        return true;
    };
    //**********************************************************************
    //処 理 名：支給上限
    //関 数 名：getJogen
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.getJogen = function (strSyoreiKbn) {
        me.url = me.sys_id + "/" + me.id + "/getJogen";
        me.data = {
            strSyoreiKbn: strSyoreiKbn,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var data = result["data"];
            me.setJogen(strSyoreiKbn, data["getJogen"], data["txtJogen"]);
            me.resetSelection(me.tab2_2_jqgrid);
        };

        me.ajax.send(me.url, me.data, 0);
    };
    //**********************************************************************
    //処 理 名：支給上限セット
    //関 数 名：setJogen
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.setJogen = function (strSyoreiKbn, dataSource, txt) {
        // 正社員_支払上限
        if (txt["row"] == 0) {
            // 設定されていない場合、初期化
            if (strSyoreiKbn == "1") {
                $(".FrmSyoreikinSyoriMente.txtGyoJogen").val("");
                $(me.tab2_2_jqgrid).jqGrid("clearGridData");
            } else {
                $(".FrmSyoreikinSyoriMente.txtTenJogen").val("");
            }
            return false;
        }
        if (strSyoreiKbn == "1") {
            $(".FrmSyoreikinSyoriMente.txtGyoJogen").val(
                me.numFormat(
                    me.clsComFnc.FncNz(txt["data"][0]["JOGEN"].toString())
                )
            );

            // 正社員以外_支払上限
            if (dataSource["row"] == 0) {
                return false;
            }
            $(me.tab2_2_jqgrid).jqGrid("clearGridData");

            for (var i = 0; i < dataSource["data"].length; i++) {
                $(me.tab2_2_jqgrid).jqGrid(
                    "addRowData",
                    i,
                    dataSource["data"][i]
                );
            }
        } else {
            $(".FrmSyoreikinSyoriMente.txtTenJogen").val(
                me.numFormat(
                    me.clsComFnc.FncNz(txt["data"][0]["JOGEN"].toString())
                )
            );
        }

        return true;
    };
    //**********************************************************************
    //処 理 名：掛け率
    //関 数 名：getKakeritu
    //引    数：無し
    //戻 り 値：無し
    //処理説明：業績=1、店長=2
    //**********************************************************************
    me.getKakeritu = function (strSyoreiKbn) {
        me.url = me.sys_id + "/" + me.id + "/getKakeritu";

        me.data = {
            strSyoreiKbn: strSyoreiKbn,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.setKakeritu(strSyoreiKbn, result);
        };

        me.ajax.send(me.url, me.data, 0);
    };
    //**********************************************************************
    //処 理 名：掛け率セット
    //関 数 名：setKakeritu
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.setKakeritu = function (strSyoreiKbn, txt) {
        if (txt["row"] == 0) {
            return false;
        }

        if (strSyoreiKbn == "1") {
            var KAKERITU = me.clsComFnc.FncNz(
                txt["data"][0]["KAKERITU"].toString()
            );
            KAKERITU = new BigNumber(KAKERITU).toFixed(1);
            $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").val(KAKERITU);
        } else {
            var KAKERITU = me.clsComFnc.FncNz(
                txt["data"][0]["KAKERITU"].toString()
            );
            KAKERITU = new BigNumber(KAKERITU).toFixed(1);
            $(".FrmSyoreikinSyoriMente.txtTenKakeritu").val(KAKERITU);
        }

        return true;
    };
    //**********************************************************************
    //処 理 名：店長奨励_係数種類セット
    //関 数 名：setTenKeisuSyurui
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.setTenKeisuSyurui = function (dataSource) {
        $(me.tab3_1_jqgrid).jqGrid("clearGridData");
        if (dataSource["row"] <= 0) {
            return false;
        }
        for (var i = 0; i < dataSource["data"].length; i++) {
            $(me.tab3_1_jqgrid).jqGrid("addRowData", i, dataSource["data"][i]);
        }

        return true;
    };
    //**********************************************************************
    //処 理 名：店長奨励_限界/経常利益取得部署
    //関 数 名：getTenSyutoku
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.getTenSyutoku = function () {
        me.url = me.sys_id + "/" + me.id + "/getTenSyutoku";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.setTenSyutoku(result);

            me.resetSelection(me.tab4_2_jqgrid);
        };

        me.ajax.send(me.url, "", 0);
    };
    //**********************************************************************
    //処 理 名：店長奨励_限界/経常利益取得部署セット
    //関 数 名：setTenSyutoku
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.setTenSyutoku = function (dataSource) {
        $(me.tab4_2_jqgrid).jqGrid("clearGridData");
        if (dataSource["row"] == 0) {
            return false;
        }
        for (var i = 0; i < dataSource["data"].length; i++) {
            $(me.tab4_2_jqgrid).jqGrid("addRowData", i, dataSource["data"][i]);
        }

        return true;
    };

    /*
     '**********************************************************************
     '処 理 名：ﾗｼﾞｵﾎﾞﾀﾝ初期化
     '関 数 名：subRdoInit
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subRdoInit = function () {
        $(".FrmSyoreikinSyoriMente.grpGyoTaisyo").block();
        $(".FrmSyoreikinSyoriMente.btnAddGyoTaisyo").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelGyoTaisyo").button("disable");
        $(".FrmSyoreikinSyoriMente.grpGyoJogen").block();
        $(".FrmSyoreikinSyoriMente.btnAddGyoJogen").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelGyoJogen").button("disable");
        $(".FrmSyoreikinSyoriMente.txtGyoJogen").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpGyoKakeritu").block();
        $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpGyoKeisuSyurui").block();
        $(".FrmSyoreikinSyoriMente.grpGyokeisuKomoku").block();
        $(".FrmSyoreikinSyoriMente.btnAddGyoKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelGyoKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpGyoTaisyoRoute").block();
        $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpTenTaisyo").block();
        $(".FrmSyoreikinSyoriMente.btnAddTenTaisyo").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelTenTaisyo").button("disable");
        $(".FrmSyoreikinSyoriMente.grpTenJogen").block();
        $(".FrmSyoreikinSyoriMente.txtTenJogen").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.txtTenKakeritu").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpTenKakeritu").block();
        $(".FrmSyoreikinSyoriMente.grpTenKeisuSyurui").block();
        $(".FrmSyoreikinSyoriMente.grpTenKeisuKomoku").block();
        $(".FrmSyoreikinSyoriMente.btnAddTenKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelTenKomoku").button("disable");
        $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpTenTaisyoRoute").block();
        $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").prop("disabled", true);
        $(".FrmSyoreikinSyoriMente.grpTenSyutoku").block();
        $(".FrmSyoreikinSyoriMente.btnAddTenSyutoku").button("disable");
        $(".FrmSyoreikinSyoriMente.btnDelTenSyutoku").button("disable");
    };
    /*
     '**********************************************************************
     '処 理 名：ﾗｼﾞｵﾎﾞﾀﾝﾁｪｯｸ初期化
     '関 数 名：subRdoChkInit
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subRdoChkInit = function () {
        $(".FrmSyoreikinSyoriMente.rdoGyoKeisuSyurui").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoGyokeisuKomoku").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoGyoTaisyoRoute").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoGyoTaisyo").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoGyoJogen").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoGyoKakeritu").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenKeisuSyurui").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenkeisuKomoku").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenTaisyoRoute").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenTaisyo").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenJogen").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenKakeritu").prop("checked", false);
        $(".FrmSyoreikinSyoriMente.rdoTenSyutoku").prop("checked", false);
    };
    //**********************************************************************
    //処 理 名：処理選択Radioボタンクリック
    //関 数 名：tab_rdo_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.tab_rdo_Click = function () {
        me.subRdoInit();

        $(me.tab1_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab1_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab2_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab2_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab3_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab3_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab4_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab4_2_jqgrid).jqGrid("saveRow", me.lastsel);

        var rdoName = $("input[name='rdo']:checked").val();
        switch (rdoName) {
            case "rdoGyoKeisuSyurui":
                //業績奨励_係数種類
                $(".FrmSyoreikinSyoriMente.grpGyoKeisuSyurui").unblock();
                $(me.tab1_1_jqgrid).jqGrid("setSelection", 0, true);
                $(".FrmSyoreikinSyoriMente.btnAddGyoKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").prop(
                    "disabled",
                    true
                );
                break;
            case "rdoGyokeisuKomoku":
                //業績奨励_係数項目
                $(".FrmSyoreikinSyoriMente.grpGyokeisuKomoku").unblock();
                $(".FrmSyoreikinSyoriMente.btnAddGyoKomoku").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoKomoku").button("enable");
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").trigger(
                    "focus"
                );
                break;
            case "rdoGyoTaisyoRoute":
                //業績奨励_対象販売ルート
                $(".FrmSyoreikinSyoriMente.grpGyoTaisyoRoute").unblock();
                $(".FrmSyoreikinSyoriMente.btnAddGyoKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui1").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.cmbGyoKeisuSyurui2").trigger(
                    "focus"
                );
                break;
            case "rdoGyoTaisyo":
                //業績奨励_支給対象
                $(".FrmSyoreikinSyoriMente.grpGyoTaisyo").unblock();
                $(me.tab2_1_jqgrid).jqGrid("setSelection", 0, true);
                $(".FrmSyoreikinSyoriMente.btnAddGyoTaisyo").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoTaisyo").button("enable");
                $(".FrmSyoreikinSyoriMente.btnAddGyoJogen").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoJogen").button("disable");
                $(".FrmSyoreikinSyoriMente.txtGyoJogen").prop("disabled", true);
                $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").prop(
                    "disabled",
                    true
                );
                break;
            case "rdoGyoJogen":
                //業績奨励_支給上限
                $(".FrmSyoreikinSyoriMente.grpGyoJogen").unblock();
                $(me.tab2_2_jqgrid).jqGrid("setSelection", 0, true);
                $(".FrmSyoreikinSyoriMente.btnAddGyoTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnAddGyoJogen").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoJogen").button("enable");
                $(".FrmSyoreikinSyoriMente.txtGyoJogen").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.txtGyoJogen").select();
                break;
            case "rdoGyoKakeritu":
                //業績奨励_掛け率
                $(".FrmSyoreikinSyoriMente.grpGyoKakeritu").unblock();
                $(".FrmSyoreikinSyoriMente.btnAddGyoTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnAddGyoJogen").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelGyoJogen").button("disable");
                $(".FrmSyoreikinSyoriMente.txtGyoJogen").prop("disabled", true);
                $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").trigger("focus");
                $(".FrmSyoreikinSyoriMente.txtGyoKakeritu").select();
                break;
            case "rdoTenKeisuSyurui":
                //店長奨励_係数種類
                $(".FrmSyoreikinSyoriMente.grpTenKeisuSyurui").unblock();
                $(me.tab3_1_jqgrid).jqGrid("setSelection", 0, true);
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").prop(
                    "disabled",
                    true
                );
                $(
                    ".FrmSyoreikinSyoriMente.sprTenKeisuSyurui input[type='checkbox']"
                ).prop("disabled", false);
                break;
            case "rdoTenkeisuKomoku":
                //店長奨励_係数項目
                $(".FrmSyoreikinSyoriMente.grpTenKeisuKomoku").unblock();
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenKomoku").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelTenKomoku").button("enable");
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").trigger(
                    "focus"
                );
                $(
                    ".FrmSyoreikinSyoriMente.sprTenKeisuSyurui input[type='checkbox']"
                ).prop("disabled", true);
                break;
            case "rdoTenTaisyoRoute":
                //店長奨励_対象販売ルート
                $(".FrmSyoreikinSyoriMente.grpTenTaisyoRoute").unblock();
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui1").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenKomoku").button("disable");
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.cmbTenKeisuSyurui2").trigger(
                    "focus"
                );
                $(
                    ".FrmSyoreikinSyoriMente.sprTenKeisuSyurui input[type='checkbox']"
                ).prop("disabled", true);
                break;
            case "rdoTenTaisyo":
                //店長奨励_支給対象
                $(".FrmSyoreikinSyoriMente.grpTenTaisyo").unblock();
                $(me.tab4_1_jqgrid).jqGrid("setSelection", 0, true);

                $(".FrmSyoreikinSyoriMente.btnAddTenTaisyo").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelTenTaisyo").button("enable");
                $(".FrmSyoreikinSyoriMente.txtTenJogen").prop("disabled", true);
                $(".FrmSyoreikinSyoriMente.txtTenKakeritu").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenSyutoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenSyutoku").button("disable");
                break;
            case "rdoTenJogen":
                //店長奨励_支給上限
                $(".FrmSyoreikinSyoriMente.grpTenJogen").unblock();
                $(".FrmSyoreikinSyoriMente.btnAddTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.txtTenJogen").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.txtTenKakeritu").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenSyutoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenSyutoku").button("disable");
                $(".FrmSyoreikinSyoriMente.txtTenJogen").select();
                break;
            case "rdoTenKakeritu":
                //店長奨励_掛け率
                $(".FrmSyoreikinSyoriMente.grpTenKakeritu").unblock();
                $(".FrmSyoreikinSyoriMente.btnAddTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.txtTenJogen").prop("disabled", true);
                $(".FrmSyoreikinSyoriMente.txtTenKakeritu").prop(
                    "disabled",
                    false
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenSyutoku").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenSyutoku").button("disable");
                $(".FrmSyoreikinSyoriMente.txtTenKakeritu").select();
                break;
            case "rdoTenSyutoku":
                //店長奨励_限界/経常利益取得部署
                $(".FrmSyoreikinSyoriMente.grpTenSyutoku").unblock();
                $(me.tab4_2_jqgrid).jqGrid("setSelection", 0, true);
                $(".FrmSyoreikinSyoriMente.btnAddTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.btnDelTenTaisyo").button("disable");
                $(".FrmSyoreikinSyoriMente.txtTenJogen").prop("disabled", true);
                $(".FrmSyoreikinSyoriMente.txtTenKakeritu").prop(
                    "disabled",
                    true
                );
                $(".FrmSyoreikinSyoriMente.btnAddTenSyutoku").button("enable");
                $(".FrmSyoreikinSyoriMente.btnDelTenSyutoku").button("enable");
                break;
        }

        $(".FrmSyoreikinSyoriMente.btnCancel").button("enable");
        $(".FrmSyoreikinSyoriMente.btnUpdate").button("enable");
    };
    //**********************************************************************
    //処 理 名：abControlクリック
    //関 数 名：TabControl_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
    me.TabControl_Click = function () {
        me.subRdoChkInit();
        me.subRdoInit();
        $(me.tab1_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab1_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab2_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab2_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab3_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab3_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab4_1_jqgrid).jqGrid("saveRow", me.lastsel);
        $(me.tab4_2_jqgrid).jqGrid("saveRow", me.lastsel);
        $(".FrmSyoreikinSyoriMente.btnCancel").button("disable");
        $(".FrmSyoreikinSyoriMente.btnUpdate").button("disable");
    };

    //**********************************************************************
    //処 理 名：Blurショートカットキー
    //関 数 名：BlurKyoutsuu
    //引    数：Id,idName, vaGetName,itemCode,name,allParameter
    //引数説明：Id : htmlのid ,idName:inputのid ,vaGetName:name , itemCode:getName(),例如：item["CODE"] == 100
    //	       		name:お問い合わせのname,  allParameter:すべてのパラメータ
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.BlurKyoutsuu = function (
        Id,
        idName,
        vaGetName,
        itemCode,
        name,
        allParameter
    ) {
        var vaGet = $.trim($("#" + me.lastsel + "_" + idName).val());
        if (vaGet == "") {
            Id.jqGrid("setCell", me.lastsel, vaGetName, null);
        } else {
            Id.jqGrid(
                "setCell",
                me.lastsel,
                vaGetName,
                me.getName(
                    me.clsComFnc.FncNv(vaGet),
                    itemCode,
                    name,
                    allParameter
                )
            );
        }
    };

    //**********************************************************************
    //処 理 名：Blur
    //関 数 名：BlurHyoujijyunn
    //引    数：Id,idName
    //引数説明：Id : htmlのid ,idName:inputのid
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.BlurHyoujijyunn = function (idName) {
        var vaGet = $.trim($("#" + me.lastsel + "_" + idName).val());
        var str = me.removeZero(vaGet);
        $("#" + me.lastsel + "_" + idName).val(str == "0" ? "" : str);
    };

    me.removeZero = function (value) {
        var pn = value.indexOf("-") == 0 ? "-" : "";
        value = value.indexOf("-") == 0 ? value.substring(1) : value;
        var str = value;
        for (var index = 0; index < value.length; index++) {
            str = value.substring(index);
            if (str.indexOf("0") != 0) {
                break;
            }
        }
        if (str != "0") {
            str = pn + (str.indexOf(".") == 0 ? "0" : "") + str;
        }
        return str;
    };
    //**********************************************************************
    //処 理 名：番号から名称取得
    //関 数 名：getName
    //引    数：code: コード name :名称（值） ,allParameter ：所有参数
    //引数説明：
    //戻 り 値：無し
    //処理説明：職種名,部署名,販売ルート名,雇用区分名
    //**********************************************************************
    me.getName = function (code, itemCode, name, allParameter) {
        if (!allParameter) {
            return null;
        }
        var allName = allParameter.filter(function (item) {
            if (item[itemCode] == code) {
                return item;
            }
        });
        if (allName.length !== 0) {
            return allName[0][name];
        } else {
            return null;
        }
    };

    //**********************************************************************
    //処 理 名：共通
    //関 数 名：inputReplace
    //引    数：targetVal, inputLength, keycode
    //引数説明：無し
    //戻 り 値：無し
    //処理説明：無し
    //**********************************************************************
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
    //keyup共同
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
    //0追加
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
    //转换金额用
    me.numFormat = function (num) {
        var number =
            num.toString().indexOf(".") !== -1
                ? num.toLocaleString()
                : num.toString().replace(/(\d)(?=(?:\d{3})+$)/g, "$1,");
        return number;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_JKSYS_FrmSyoreikinSyoriMente = new JKSYS.FrmSyoreikinSyoriMente();
    o_JKSYS_JKSYS.FrmSyoreikinSyoriMente = o_JKSYS_FrmSyoreikinSyoriMente;
    o_JKSYS_FrmSyoreikinSyoriMente.load();
});
