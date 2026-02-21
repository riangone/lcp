/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE160CatalogOrderBase");

HMTVE.HMTVE160CatalogOrderBase = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE160CatalogOrderBase";
    me.HMTVE = new HMTVE.HMTVE();

    // jqgrid
    me.grid_id = "#HMTVE160CatalogOrderBase_tblMain_grdHonCatalog";
    me.grid_gm_id = "#HMTVE160CatalogOrderBase_tblMain_grdMail";
    me.grid_gy_id = "#HMTVE160CatalogOrderBase_tblMain_grdYou";
    me.grid_gc_id = "#HMTVE160CatalogOrderBase_tblMain_grdCata";

    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.g_urlMail = me.sys_id + "/" + me.id + "/" + "fncSearchSpreadMail";
    me.g_urlYouCata = me.sys_id + "/" + me.id + "/" + "fncSearchSpreadYouCata";
    me.g_urlCata = me.sys_id + "/" + me.id + "/" + "fncSearchSpreadCata";

    me.upsel = "";
    me.nextsel = "";
    me.upsel_gm = "";
    me.nextsel_gm = "";
    me.upsel_gy = "";
    me.nextsel_gy = "";
    me.upsel_gc = "";
    me.nextsel_gc = "";
    me.last_selected_id = 0;
    me.gm_last_selected_id = 0;
    me.gy_last_selected_id = 0;
    me.gc_last_selected_id = 0;

    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
    };
    me.colModel = [
        {
            name: "HAKKO_YM",
            label: "発行年月",
            index: "HAKKO_YM",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 80 : 100,
            editoptions: {
                maxlength: "7",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "HAKKO_YM" + "_" + "grdHonCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CATALOG_CD",
            label: "コード",
            index: "CATALOG_CD",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 65 : 80,
            editoptions: {
                maxlength: "4",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid +
                                    "_" +
                                    "CATALOG_CD" +
                                    "_" +
                                    "grdHonCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CATALOG_NM",
            label: "本カタログ",
            index: "CATALOG_NM",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 200 : 240,
            editoptions: {
                class: "width",
                maxlength: "50",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid +
                                    "_" +
                                    "CATALOG_NM" +
                                    "_" +
                                    "grdHonCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TANKA",
            label: "単価",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            index: "TANKA",
            align: "right",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 82 : 102,
            editoptions: {
                //20241028 UPD START
                //maxlength : '4',
                maxlength: "5",
                //20241028 UPD END
                class: "align_right",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "TANKA" + "_" + "grdHonCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    me.option_GM = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };
    me.colModel_GM = [
        {
            label: "",
            name: "SEQ_NO",
            index: "SEQ_NO",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "MAIL_ADDRESS",
            label: "メールアドレス",
            index: "MAIL_ADDRESS",
            sortable: false,
            editable: true,
            align: "left",
            width: me.ratio === 1.5 ? 430 : 502,
            editoptions: {
                maxlength: "100",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    me.option_GY = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
    };
    me.colModel_GY = [
        {
            name: "HAKKO_YM",
            label: "発行年月",
            index: "HAKKO_YM",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            sortable: false,
            editable: true,
            width: 66,
            editoptions: {
                maxlength: "7",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "HAKKO_YM" + "_" + "grdYouCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CATALOG_CD",
            label: "コード",
            index: "CATALOG_CD",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 60 : 80,
            editoptions: {
                maxlength: "4",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid +
                                    "_" +
                                    "CATALOG_CD" +
                                    "_" +
                                    "grdYouCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CATALOG_NM",
            label: "用品カタログ",
            index: "CATALOG_NM",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 150 : 210,
            editoptions: {
                class: "width",
                maxlength: "50",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid +
                                    "_" +
                                    "CATALOG_NM" +
                                    "_" +
                                    "grdYouCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            align: "right",
            sortable: false,
            editable: true,
            width: 65,
            editoptions: {
                //20241028 UPD START
                //maxlength : '4',
                maxlength: "5",
                //20241028 UPD END
                class: "align_right",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "TANKA" + "_" + "grdYouCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];

    me.option_GC = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
    };
    me.colModel_GC = [
        {
            name: "CATALOG_CD",
            label: "コード",
            index: "CATALOG_CD",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 40 : 44,
            editoptions: {
                maxlength: "4",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "CATALOG_CD" + "_" + "grdCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CATALOG_NM",
            label: "用品",
            index: "CATALOG_NM",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 154 : 204,
            editoptions: {
                class: "width",
                maxlength: "50",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "CATALOG_NM" + "_" + "grdCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TANKA",
            label: "単価",
            index: "TANKA",
            //タイトルのclass
            labelClasses:
                "HMTVE160CatalogOrderBase_tblMain_grdHonCatalog_CELL_SUM_C",
            classes: "CELL_SUM_C",
            align: "right",
            sortable: false,
            editable: true,
            width: me.ratio === 1.5 ? 53 : 68,
            editoptions: {
                //20241028 UPD START
                //maxlength : '4',
                maxlength: "5",
                //20241028 UPD END
                class: "align_right",
                dataInit: function (element, row) {
                    if (row.id) {
                        if (row.id.length > 0) {
                            var index = row.id.indexOf("_");
                            var rowid = row.id.substring(0, index);
                            $(element).attr(
                                "id",
                                rowid + "_" + "TANKA" + "_" + "grdCatalog"
                            );
                        }
                    }
                },
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE160CatalogOrderBase.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMTVE.TabKeyDown();

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //登録ボタンクリック
    $(".HMTVE160CatalogOrderBase.btnLogin").click(function () {
        me.rowSelection("");
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        me.clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
    });
    $(".HMTVE160CatalogOrderBase.btnRowAdd_grdHon").click(function () {
        me.btnRowAddgrdHon_Click(me.grid_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowDel_grdHon").click(function () {
        me.grdHonCatalog_RowDeleting(me.grid_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowAdd_grdMail").click(function () {
        me.btnRowAddgrdHon_Click(me.grid_gm_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowDel_grdMail").click(function () {
        me.grdMail_RowDeleting(me.grid_gm_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowAdd_grdYou").click(function () {
        me.btnRowAddgrdHon_Click(me.grid_gy_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowDel_grdYou").click(function () {
        me.grdYouCatalog_RowDeleting(me.grid_gy_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowAdd_grdCata").click(function () {
        me.btnRowAddgrdHon_Click(me.grid_gc_id);
    });
    $(".HMTVE160CatalogOrderBase.btnRowDel_grdCata").click(function () {
        me.grdCatalog_RowDeleting(me.grid_gc_id);
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：init_control
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：Page_Load
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        if (
            gdmz.SessionUserId.toString() != null ||
            gdmz.SessionUserId.toString() != ""
        ) {
            //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの生成
            var completeFunGetHonCatalog = function (returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    me.error_fun();
                    return;
                }
                if (returnFLG != "nodata") {
                    //１行目を選択状態にする
                    $(me.grid_id).jqGrid("setSelection", "0");
                }
                //メールアドレスﾃｰﾌﾞﾙの生成
                var completeFunMail = function (returnFLG, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        me.error_fun();
                        return;
                    }
                    if (returnFLG != "nodata") {
                        //１行目を選択状態にする
                        $(me.grid_gm_id).jqGrid("setSelection", "0");
                    }
                    //用品ﾃｰﾌﾞﾙの生成
                    var completeFunGetCatalogLogin = function (
                        returnFLG,
                        result
                    ) {
                        if (result["error"]) {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            me.error_fun();
                            return;
                        }
                        if (returnFLG != "nodata") {
                            //１行目を選択状態にする
                            $(me.grid_gc_id).jqGrid("setSelection", "0");
                        }
                        //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの生成
                        var completeFunGetYouCatalog = function (
                            returnFLG,
                            result
                        ) {
                            if (result["error"]) {
                                me.clsComFnc.FncMsgBox(
                                    "E9999",
                                    result["error"]
                                );
                                me.error_fun();
                                return;
                            }
                            if (returnFLG != "nodata") {
                                //１行目を選択状態にする
                                $(me.grid_gy_id).jqGrid("setSelection", "0");
                            }

                            var rowDataNum = $(me.grid_id).jqGrid("getRowData");
                            if (rowDataNum.length > 0) {
                                $(me.grid_id).jqGrid("setSelection", "0");
                            }
                        };
                        gdmz.common.jqgrid.showWithMesg(
                            me.grid_gy_id,
                            me.g_urlYouCata,
                            me.colModel_GY,
                            "",
                            "",
                            me.option_GY,
                            "",
                            completeFunGetYouCatalog
                        );
                        me.getcomplete_all(
                            me.grid_gy_id,
                            me.gy_last_selected_id,
                            "grdYouCatalog"
                        );
                        gdmz.common.jqgrid.set_grid_width(
                            me.grid_gy_id,
                            me.ratio === 1.5 ? 380 : 465
                        );
                        gdmz.common.jqgrid.set_grid_height(
                            me.grid_gy_id,
                            124
                        );
                        $(me.grid_gy_id).jqGrid("bindKeys");
                    };
                    gdmz.common.jqgrid.showWithMesg(
                        me.grid_gc_id,
                        me.g_urlCata,
                        me.colModel_GC,
                        "",
                        "",
                        me.option_GC,
                        "",
                        completeFunGetCatalogLogin
                    );
                    me.getcomplete_all(
                        me.grid_gc_id,
                        me.gc_last_selected_id,
                        "grdCatalog"
                    );
                    gdmz.common.jqgrid.set_grid_width(me.grid_gc_id, me.ratio === 1.5 ? 280 : 350);
                    gdmz.common.jqgrid.set_grid_height(me.grid_gc_id, 78);
                    $(me.grid_gc_id).jqGrid("bindKeys");
                };
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_gm_id,
                    me.g_urlMail,
                    me.colModel_GM,
                    "",
                    "",
                    me.option_GM,
                    "",
                    completeFunMail
                );
                me.getcomplete_all(me.grid_gm_id, me.gm_last_selected_id);
                gdmz.common.jqgrid.set_grid_width(me.grid_gm_id, me.ratio === 1.5 ? 480 : 570);
                gdmz.common.jqgrid.set_grid_height(me.grid_gm_id, me.ratio === 1.5 ? 58 : 68);
                $("#HMTVE160CatalogOrderBase_tblMain_grdMail_rn").html("No.");
                $(me.grid_gm_id).jqGrid("bindKeys");
            };
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                "",
                "",
                me.option,
                "",
                completeFunGetHonCatalog
            );
            me.getcomplete_all(
                me.grid_id,
                me.last_selected_id,
                "grdHonCatalog"
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, me.ratio === 1.5 ? 470 : 560);
            gdmz.common.jqgrid.set_grid_height(me.grid_id,  me.ratio === 1.5 ? 180 : 244);
            $(me.grid_id).jqGrid("bindKeys");
        }
    };

    me.btnRowAddgrdHon_Click = function (grid_id) {
        //获得所有行的ID数组
        var ids = $(grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //获得当前最大行号（数据编号）
            rowid = parseInt(ids.pop()) + 1;
        }
        var data = {
            HAKKO_YM: "",
            CATALOG_CD: "",
            CATALOG_NM: "",
            TANKA: "",
        };
        //插入一行
        $(grid_id).jqGrid("addRowData", rowid, data);
        $(grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        $(grid_id).jqGrid("setSelection", rowid, true);
    };

    // '**********************************************************************
    // '処 理 名：本カｶﾀﾛｸﾞテープルの行削除ボタンのイベント
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：行削除ボタンを押下された行に表示されているﾃﾞｰﾀをクリアする
    // '**********************************************************************
    me.grdHonCatalog_RowDeleting = function (grid_id) {
        var allIds = $(grid_id).jqGrid("getDataIDs");
        var rowid = $(grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };

    // '**********************************************************************
    // '処 理 名：用品ｶﾀﾛｸﾞテープルの行削除ボタンのイベント
    // '関 数 名：Page_Load
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：行削除ボタンを押下された行に表示されているﾃﾞｰﾀをクリアする
    // '**********************************************************************
    me.grdYouCatalog_RowDeleting = function (grid_id) {
        var allIds = $(grid_id).jqGrid("getDataIDs");
        var rowid = $(grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.nextsel_gy, true);
                } else {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.upsel_gy, true);
                }
                break;
            }
        }
    };

    // '**********************************************************************
    // '処 理 名：用品ｶﾀﾛｸﾞテープルの行削除ボタンのイベント
    // '関 数 名：Page_Load
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：行削除ボタンを押下された行に表示されているﾃﾞｰﾀをクリアする
    // '**********************************************************************
    me.grdCatalog_RowDeleting = function (grid_id) {
        var allIds = $(grid_id).jqGrid("getDataIDs");
        var rowid = $(grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.nextsel_gc, true);
                } else {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.upsel_gc, true);
                }
                break;
            }
        }
    };

    // '**********************************************************************
    // '処 理 名：メールアドレス設定テープルの行削除ボタンのイベント
    // '関 数 名：Page_Load
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：行削除ボタンを押下された行に表示されているﾃﾞｰﾀをクリアする
    // '**********************************************************************
    me.grdMail_RowDeleting = function (grid_id) {
        var allIds = $(grid_id).jqGrid("getDataIDs");
        var rowid = $(grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.nextsel_gm, true);
                } else {
                    $(grid_id).jqGrid("delRowData", rowid);

                    $(grid_id).jqGrid("setSelection", me.upsel_gm, true);
                }
                break;
            }
        }
    };

    // '**********************************************************************
    // '処 理 名：登録ボタンのイベント
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：入力チェックして、登録処理
    // '**********************************************************************
    me.btnLogin_Click = function () {
        //入力チェック
        if (me.btnLoginCheck() == false) {
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnLogin_Click";

        $(me.grid_gm_id).jqGrid(
            "saveRow",
            me.gm_last_selected_id,
            null,
            "clientArray"
        );
        $(me.grid_gy_id).jqGrid(
            "saveRow",
            me.gy_last_selected_id,
            null,
            "clientArray"
        );
        $(me.grid_gc_id).jqGrid(
            "saveRow",
            me.gc_last_selected_id,
            null,
            "clientArray"
        );
        $(me.grid_id).jqGrid(
            "saveRow",
            me.last_selected_id,
            null,
            "clientArray"
        );
        var rows = $(me.grid_id).jqGrid("getRowData");
        var rows_gy = $(me.grid_gy_id).jqGrid("getRowData");
        var rows_gc = $(me.grid_gc_id).jqGrid("getRowData");
        var rows_gm = $(me.grid_gm_id).jqGrid("getRowData");

        var data = {
            grdHonCatalog: rows,
            grdYouCatalog: rows_gy,
            grdCatalog: rows_gc,
            grdMail: rows_gm,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
                $(me.grid_gc_id).jqGrid(
                    "setSelection",
                    me.gc_last_selected_id,
                    true
                );
                $(me.grid_gm_id).jqGrid(
                    "setSelection",
                    me.gm_last_selected_id,
                    true
                );
                $(me.grid_gy_id).jqGrid(
                    "setSelection",
                    me.gy_last_selected_id,
                    true
                );
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.OK = me.setSelection;
            me.clsComFnc.FncMsgBox("I0016");
            // $(me.grid_gm_id).jqGrid('setSelection', "0");
            // $(me.grid_gc_id).jqGrid('setSelection', "0");
            // $(me.grid_gy_id).jqGrid('setSelection', "0");
            // $(me.grid_id).jqGrid('setSelection', "0");
        };
        me.ajax.send(url, data, 0);
    };
    me.setSelection = function () {
        $(me.grid_gm_id).jqGrid("setSelection", "0");
        $(me.grid_gc_id).jqGrid("setSelection", "0");
        $(me.grid_gy_id).jqGrid("setSelection", "0");
        $(me.grid_id).jqGrid("setSelection", "0");
    };
    // '*************************
    // '処 理 名：登録ボタンの入力チェック
    // '関 数 名：btnLoginCheck
    // '引    数：無し
    // '戻 り 値：なし
    // '処理説明：登録ボタンの入力チェック
    // '**********************************************************************
    me.btnLoginCheck = function () {
        //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙ
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        $(me.grid_id).jqGrid("saveRow", rowid, null, "clientArray");
        var count1 = 0;
        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (
                $.trim(rowData["HAKKO_YM"]) !== "" ||
                $.trim(rowData["CATALOG_CD"]) !== "" ||
                $.trim(rowData["CATALOG_NM"]) !== "" ||
                $.trim(rowData["TANKA"]) !== ""
            ) {
                if ($.trim(rowData["HAKKO_YM"]) == "") {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月を入力してください。"
                    );
                    return false;
                }
                //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第一列
                //必須チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["HAKKO_YM"])) > 7
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["HAKKO_YM"])) < 7
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                //整合性チェック
                var patrn = /^(\d{4})(-|\/)(\d{1,2})$/;
                if (me.CheckDate(rowData["HAKKO_YM"]) == false) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                if ($.trim(rowData["HAKKO_YM"]).substring(4, 5) !== "/") {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第二列
                //必須チェック
                if ($.trim(rowData["CATALOG_CD"]) == "") {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードを入力してください。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) > 4
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェック
                var value = Number($.trim(rowData["CATALOG_CD"]));
                var patt = /^-?[０-９]*$/g;
                if (
                    isNaN(value) &&
                    $.trim(rowData["CATALOG_CD"]) !== "" &&
                    !$.trim(rowData["CATALOG_CD"]).match(patt)
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードに数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) !=
                    $.trim(rowData["CATALOG_CD"]).length
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (
                    $.trim(rowData["CATALOG_CD"]).indexOf(".") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("-") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }

                //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第三列
                //必須チェック
                if ($.trim(rowData["CATALOG_NM"]) == "") {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "本カタログを入力してください。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_NM"])) >
                    50
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "本カタログは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第四列
                //必須チェック
                if ($.trim(rowData["TANKA"]) == "") {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox("W9999", "単価を入力してください。");
                    return false;
                }
                //桁数チェック
                if (me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) > 5) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }

                //整合性チェック
                var patt = /^-?[０-９]*$/g;
                value = Number($.trim(rowData["TANKA"]));
                if (
                    isNaN(value) &&
                    $.trim(rowData["TANKA"]) !== "" &&
                    !$.trim(rowData["TANKA"]).match(patt)
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価に数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) !=
                        $.trim(rowData["TANKA"]).length ||
                    $.trim(rowData["TANKA"]).indexOf(".") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("-") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdHonCatalog");
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdHonCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (rowData["CATALOG_CD"] !== "") {
                    count1++;
                }
            } else {
                $(me.grid_id).jqGrid("delRowData", rows[index]);
            }
        }
        $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
        //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙ
        var rows = $(me.grid_gy_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_gy_id).jqGrid("getGridParam", "selrow");
        var count2 = 0;
        $(me.grid_gy_id).jqGrid("saveRow", rowid, null, "clientArray");
        for (index in rows) {
            var rowData = $(me.grid_gy_id).jqGrid("getRowData", rows[index]);
            if (
                $.trim(rowData["HAKKO_YM"]) !== "" ||
                $.trim(rowData["CATALOG_CD"]) !== "" ||
                $.trim(rowData["CATALOG_NM"]) !== "" ||
                $.trim(rowData["TANKA"]) !== ""
            ) {
                //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第一列
                //必須チェック
                if ($.trim(rowData["HAKKO_YM"]) == "") {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月を入力してください。"
                    );
                    return false;
                }
                //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第一列
                //必須チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["HAKKO_YM"])) > 7
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["HAKKO_YM"])) < 7
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                //整合性チェック
                var patrn = /^(\d{4})(-|\/)(\d{1,2})$/;
                if (!patrn.test($.trim(rowData["HAKKO_YM"]))) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                if ($.trim(rowData["HAKKO_YM"]).substring(4, 5) !== "/") {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_HAKKO_YM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "発行年月「YYYY/MM」書式のようにご入力ください。"
                    );
                    return false;
                }
                //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第二列
                //必須チェック
                if ($.trim(rowData["CATALOG_CD"]) == "") {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードを入力してください。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) > 4
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェック
                value = Number($.trim(rowData["CATALOG_CD"]));
                var patt = /^-?[０-９]*$/g;
                if (
                    isNaN(value) &&
                    $.trim(rowData["CATALOG_CD"]) !== "" &&
                    !$.trim(rowData["CATALOG_CD"]).match(patt)
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードに数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) !=
                    $.trim(rowData["CATALOG_CD"]).length
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (
                    $.trim(rowData["CATALOG_CD"]).indexOf(".") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("-") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第三列
                //必須チェック
                if ($.trim(rowData["CATALOG_NM"]) == "") {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "用品カタログを入力してください。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_NM"])) >
                    50
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "用品カタログは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙの第四列
                //必須チェック
                if ($.trim(rowData["TANKA"]) == "") {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox("W9999", "単価を入力してください。");
                    return false;
                }
                //桁数チェック
                if (me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) > 5) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェック
                value = Number($.trim(rowData["TANKA"]));
                var patt = /^-?[０-９]*$/g;
                if (
                    isNaN(value) &&
                    $.trim(rowData["TANKA"]) !== "" &&
                    !$.trim(rowData["TANKA"]).match(patt)
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価に数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) !=
                        $.trim(rowData["TANKA"]).length ||
                    $.trim(rowData["TANKA"]).indexOf(".") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("-") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdYouCatalog");
                    $(me.grid_gy_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdYouCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (rowData["CATALOG_CD"] !== "") {
                    count2++;
                }
            } else {
                $(me.grid_gy_id).jqGrid("delRowData", rows[index]);
            }
        }
        $(me.grid_gy_id).jqGrid("setSelection", me.gy_last_selected_id, true);
        //用品ﾃｰﾌﾞﾙ
        var rows = $(me.grid_gc_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_gc_id).jqGrid("getGridParam", "selrow");
        $(me.grid_gc_id).jqGrid("saveRow", rowid, null, "clientArray");
        var count3 = 0;
        for (index in rows) {
            var rowData = $(me.grid_gc_id).jqGrid("getRowData", rows[index]);
            if (
                $.trim(rowData["CATALOG_CD"]) !== "" ||
                $.trim(rowData["CATALOG_NM"]) !== "" ||
                $.trim(rowData["TANKA"]) !== ""
            ) {
                //用品ﾃｰﾌﾞﾙの第一列
                //必須チェック
                if ($.trim(rowData["CATALOG_CD"]) == "") {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードを入力してください。"
                    );
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) > 4
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェック
                value = Number($.trim(rowData["CATALOG_CD"]));
                var patt = /^-?[０-９]*$/g;
                if (
                    isNaN(value) &&
                    $.trim(rowData["CATALOG_CD"]) !== "" &&
                    !$.trim(rowData["CATALOG_CD"]).match(patt)
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コードに数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_CD"])) !=
                    $.trim(rowData["CATALOG_CD"]).length
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (
                    $.trim(rowData["CATALOG_CD"]).indexOf(".") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("-") >= 0 ||
                    $.trim(rowData["CATALOG_CD"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_CD" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                //用品ﾃｰﾌﾞﾙの第二列
                //必須チェック
                if ($.trim(rowData["CATALOG_NM"]) == "") {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox("W9999", "用品を入力してください。");
                    return false;
                }
                //桁数チェック
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["CATALOG_NM"])) >
                    50
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_CATALOG_NM" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "用品は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //用品ﾃｰﾌﾞﾙの第三列
                //必須チェック
                if ($.trim(rowData["TANKA"]) == "") {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox("W9999", "単価を入力してください。");
                    return false;
                }
                //桁数チェック
                if (me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) > 5) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価は指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
                //整合性チェック
                value = Number($.trim(rowData["TANKA"]));
                var patt = /^-?[０-９]*$/g;
                if (
                    isNaN(value) &&
                    $.trim(rowData["TANKA"]) !== "" &&
                    !$.trim(rowData["TANKA"]).match(patt)
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "単価に数値以外の値が入力されています。"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount($.trim(rowData["TANKA"])) !=
                    $.trim(rowData["TANKA"]).length
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (
                    $.trim(rowData["TANKA"]).indexOf(".") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("-") >= 0 ||
                    $.trim(rowData["TANKA"]).indexOf("+") >= 0
                ) {
                    me.rowSelection("grdCatalog");
                    $(me.grid_gc_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_TANKA" + "_grdCatalog"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力されている値が不正です。"
                    );
                    return false;
                }
                if (rowData["CATALOG_CD"] !== "") {
                    count3++;
                }
            } else {
                $(me.grid_gc_id).jqGrid("delRowData", rows[index]);
            }
        }
        $(me.grid_gc_id).jqGrid("setSelection", me.gc_last_selected_id, true);
        //メールアドレスチェック
        var rows = $(me.grid_gm_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_gm_id).jqGrid("getGridParam", "selrow");
        $(me.grid_gm_id).jqGrid("saveRow", rowid, null, "clientArray");
        for (index in rows) {
            var rowData = $(me.grid_gm_id).jqGrid("getRowData", rows[index]);
            if ($.trim(rowData["MAIL_ADDRESS"]) !== "") {
                if (me.clsComFnc.GetByteCount(rowData["MAIL_ADDRESS"]) > 100) {
                    me.rowSelection("grid_gm_id");
                    $(me.grid_gm_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_MAIL_ADDRESS"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "メールアドレスは指定されている桁数をオーバーしています。"
                    );
                    return false;
                }
            } else {
                $(me.grid_gm_id).jqGrid("delRowData", rows[index]);
            }
        }
        $(me.grid_gm_id).jqGrid("setSelection", me.gm_last_selected_id, true);
        //本ｶﾀﾛｸﾞﾃｰﾌﾞﾙ
        if (count1 == 0) {
            me.clsComFnc.FncMsgBox("W9999", "登録ﾃﾞｰﾀが存在しません。");
            return false;
        }

        //用品ｶﾀﾛｸﾞﾃｰﾌﾞﾙ
        if (count2 == 0) {
            me.clsComFnc.FncMsgBox("W9999", "登録ﾃﾞｰﾀが存在しません。");
            return false;
        }
        //用品ﾃｰﾌﾞﾙ
        if (count3 == 0) {
            me.clsComFnc.FncMsgBox("W9999", "登録ﾃﾞｰﾀが存在しません。");
            return false;
        }
        //重複のチェック
        //本カタログ
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        $(me.grid_id).jqGrid("saveRow", rowid, null, "clientArray");
        //用品カタログ
        var rows_gy = $(me.grid_gy_id).jqGrid("getDataIDs");
        var rowid_gy = $(me.grid_gy_id).jqGrid("getGridParam", "selrow");
        $(me.grid_gy_id).jqGrid("saveRow", rowid_gy, null, "clientArray");
        //用品
        var rows_gc = $(me.grid_gc_id).jqGrid("getDataIDs");
        var rowid_gc = $(me.grid_gc_id).jqGrid("getGridParam", "selrow");
        $(me.grid_gc_id).jqGrid("saveRow", rowid_gc, null, "clientArray");
        //重複のチェック
        if (!me.repeatCheck(rows, rows, me.grid_id, me.grid_id)) {
            $(me.grid_gc_id).jqGrid(
                "setSelection",
                me.gc_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            $(me.grid_gy_id).jqGrid(
                "setSelection",
                me.gy_last_selected_id,
                true
            );
            return false;
        }
        if (!me.repeatCheck(rows_gy, rows_gy, me.grid_gy_id, me.grid_gy_id)) {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
            $(me.grid_gc_id).jqGrid(
                "setSelection",
                me.gc_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            return false;
        }
        if (!me.repeatCheck(rows_gc, rows_gc, me.grid_gc_id, me.grid_gc_id)) {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
            $(me.grid_gy_id).jqGrid(
                "setSelection",
                me.gy_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            return false;
        }
        if (!me.repeatCheck(rows, rows_gy, me.grid_id, me.grid_gy_id)) {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
            $(me.grid_gc_id).jqGrid(
                "setSelection",
                me.gc_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            return false;
        }
        if (!me.repeatCheck(rows_gy, rows_gc, me.grid_gy_id, me.grid_gc_id)) {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
            $(me.grid_gy_id).jqGrid(
                "setSelection",
                me.gy_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            return false;
        }
        if (!me.repeatCheck(rows, rows_gc, me.grid_id, me.grid_gc_id)) {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
            $(me.grid_gy_id).jqGrid(
                "setSelection",
                me.gy_last_selected_id,
                true
            );
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
            return false;
        }
    };

    me.repeatCheck = function (tableData, anotherTable, grid_id1, grid_id2) {
        for (var i = 0; i <= tableData.length - 1; i++) {
            var j = 0;
            if (grid_id1 == grid_id2) {
                j = i + 1;
            }
            for (j; j <= anotherTable.length - 1; j++) {
                var rowData_i = $(grid_id1).jqGrid("getRowData", tableData[i]);
                var rowData_j = $(grid_id2).jqGrid(
                    "getRowData",
                    anotherTable[j]
                );
                if (
                    rowData_i["CATALOG_CD"] !== "" &&
                    rowData_i["CATALOG_CD"] == rowData_j["CATALOG_CD"]
                ) {
                    if (me.grid_id == grid_id2) {
                        me.rowSelection("grdHonCatalog");
                        $(grid_id2).jqGrid(
                            "setSelection",
                            anotherTable[j],
                            true
                        );
                        me.clsComFnc.ObjFocus = $(
                            "#" +
                                anotherTable[j] +
                                "_CATALOG_CD" +
                                "_grdHonCatalog"
                        );
                    } else if (me.grid_gy_id == grid_id2) {
                        me.rowSelection("grdYouCatalog");
                        $(grid_id2).jqGrid(
                            "setSelection",
                            anotherTable[j],
                            true
                        );
                        me.clsComFnc.ObjFocus = $(
                            "#" +
                                anotherTable[j] +
                                "_CATALOG_CD" +
                                "_grdYouCatalog"
                        );
                    } else if (me.grid_gc_id == grid_id2) {
                        me.rowSelection("grdCatalog");
                        $(grid_id2).jqGrid(
                            "setSelection",
                            anotherTable[j],
                            true
                        );
                        me.clsComFnc.ObjFocus = $(
                            "#" +
                                anotherTable[j] +
                                "_CATALOG_CD" +
                                "_grdCatalog"
                        );
                    }
                    me.clsComFnc.FncMsgBox("W9999", "コードが重複しています。");
                    return false;
                }
            }
        }
        return true;
    };
    //check select
    me.rowSelection = function (jqgridtype) {
        if (jqgridtype == "grdHonCatalog") {
            $(me.grid_id).jqGrid("setSelection", me.last_selected_id, true);
        }
        if (jqgridtype == "grdCatalog") {
            $(me.grid_gc_id).jqGrid(
                "setSelection",
                me.gc_last_selected_id,
                true
            );
        }
        if (jqgridtype == "grid_gm_id") {
            $(me.grid_gm_id).jqGrid(
                "setSelection",
                me.gm_last_selected_id,
                true
            );
        }
        if (jqgridtype == "grdYouCatalog") {
            $(me.grid_gy_id).jqGrid(
                "setSelection",
                me.gy_last_selected_id,
                true
            );
        }
    };
    me.CheckDate = function (ObjectValue) {
        var patrn = /^(\d{4})(-|\/)(\d{1,2})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            if (r[1] < 1753 || r[1] > 9998) {
                return false;
            }
            if (r[3] > 12 || r[3] <= 0) {
                return false;
            } else if (r[3].length < 2) {
                Object.val(r[1] + r[2] + "0" + r[3]);
            }
        }
        return true;
    };
    me.error_fun = function () {
        $(".HMTVE160CatalogOrderBase.pnlList_grdHonCatalog").hide();
        $(".HMTVE160CatalogOrderBase.pnlList_grdMail").hide();
        $(".HMTVE160CatalogOrderBase.pnlList_grdYou").hide();
        $(".HMTVE160CatalogOrderBase.pnlList_grdCata").hide();
        $(".HMTVE160CatalogOrderBase.btnLogin").hide();
    };

    me.getcomplete_all = function (grid_id, last_selected_id, grid_id_name) {
        $(grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowId, _status, e) {
                var cellIndex =
                    typeof e === "undefined"
                        ? false
                        : e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (last_selected_id != "") {
                    $(grid_id).jqGrid(
                        "saveRow",
                        last_selected_id,
                        null,
                        "clientArray"
                    );
                }

                $(grid_id).jqGrid("editRow", rowId, {
                    keys: true,
                    focusField: cellIndex === 0 ? true : cellIndex,
                });
                last_selected_id = rowId;

                if (me.grid_id == grid_id) {
                    me.last_selected_id = rowId;
                } else if (me.grid_gy_id == grid_id) {
                    me.gy_last_selected_id = rowId;
                } else if (me.grid_gc_id == grid_id) {
                    me.gc_last_selected_id = rowId;
                } else if (me.grid_gm_id == grid_id) {
                    me.gm_last_selected_id = rowId;
                }
                if (e) {
                    if (e.target && e.target.name) {
                        me.lastCol = e.target.name;
                    } else {
                        $td = $(e.target).closest("tr.jqgrow>td");
                        if ($td && $td.length > 0) {
                            var iCol = $.jgrid.getCellIndex($td[0]),
                                colModel = $(this).jqGrid(
                                    "getGridParam",
                                    "colModel"
                                ),
                                targetCell = colModel[iCol];
                            me.lastCol = targetCell.name;
                        }
                    }
                    $(
                        "#" +
                            last_selected_id +
                            "_" +
                            me.lastCol +
                            "_" +
                            grid_id_name
                    ).trigger("focus");
                    $(
                        "#" +
                            last_selected_id +
                            "_" +
                            me.lastCol +
                            "_" +
                            grid_id_name
                    ).select();
                }
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    grid_id,
                    e,
                    rowId,
                    null,
                    grid_id_name
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    if (me.grid_id == grid_id) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    } else if (me.grid_gy_id == grid_id) {
                        me.upsel_gy = up_next_sel[0];
                        me.nextsel_gy = up_next_sel[1];
                    } else if (me.grid_gc_id == grid_id) {
                        me.upsel_gc = up_next_sel[0];
                        me.nextsel_gc = up_next_sel[1];
                    } else if (me.grid_gm_id == grid_id) {
                        me.upsel_gm = up_next_sel[0];
                        me.nextsel_gm = up_next_sel[1];
                    }
                }
                //靠右
                $(grid_id).find(".align_right").css("text-align", "right");
                $(grid_id).find(".width").css("width", "97%");
            },
        });
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE160CatalogOrderBase = new HMTVE.HMTVE160CatalogOrderBase();
    o_HMTVE_HMTVE160CatalogOrderBase.load();
});
