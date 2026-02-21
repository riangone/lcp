/**
 *
 * 原価マスタ
 *
 * @alias FrmGenka
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150717           #1965                   原価マスタを表示するときに時間がかかる        ZHENGHUIYUN
 * 20150819           #2078                                                     FANZHENGZHOU
 * 20150831           #2100                   BUG                               LI
 * 20180112           #2822                   BUG                               LI
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmGenka");

R4.FrmGenka = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmGenka";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmGenka_sprMeisai";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmGenkaSelect";
    //20150717 #1965 zhenghuiyun upd s
    // me.pager = '';
    // // '#FrmGenka_pager';
    me.pager = "#FrmGenka_pager";
    //20150717 #1965 zhenghuiyun upd e
    me.sidx = "";
    me.actionFlg = "";
    me.lastsel = 0;
    me.lastcol = "";
    me.cursel = 0;
    me.tmpSaveRowData = new Array();
    me.frontLineNo = -1;
    me.frontLineNo1 = -1;
    me.saveSelectedRowData = new Array();
    me.commonTempVar = "";
    me.showMsgTF = false;
    me.loadGridRowCnt = 0;
    me.focusSaveRowDataArr = new Array();
    me.copyButtonTF = "";
    me.buttonActionFlg = "";
    me.editDataFlg = false;
    me.firstData = new Array();
    me.frontLineNo2 = -1;

    //20150717 #1965 zhenghuiyun add s
    me.old_data;
    //20150717 #1965 zhenghuiyun add e

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    me.controls.push({
        id: ".FrmGenka.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmGenka.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmGenka.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmGenka.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmGenka.cmdSearch",
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
    $(".FrmGenka.cmdInsert").click(function () {
        me.fnc_click_cmdInsert();
    });
    $(".FrmGenka.cmdUpdate").click(function () {
        me.fnc_click_cmdUpdate();
    });
    $(".FrmGenka.cmdCancel").click(function () {
        me.fnc_click_cmdCancel();
    });
    $(".FrmGenka.cmdSearch").click(function () {
        me.fnc_click_cmdSearch();
    });
    $(".FrmGenka.cmdCopy").click(function () {
        me.fnc_click_cmdCopy();
    });
    $(".FrmGenka.txtTOA_NAME").keydown(function (e) {
        var key = e.charCode || e.keyCode;
        if (key == 222) {
            return false;
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmGenka_load();
    };
    me.initGrid = function () {
        //20150717 #1965 zhenghuiyun upd s
        // me.option =
        // {
        // pagerpos : "left",
        // multiselect : false,
        // caption : "",
        // rowNum : 5000000,
        // multiselectWidth : 30,
        // rownumWidth : 40,
        // };
        me.option = {
            pagerpos: "center",
            recordpos: "right",
            multiselect: false,
            rownumbers: true,
            rowNum: 30,
            rowList: [10, 20, 30, 40, 50],
            caption: "",
            multiselectWidth: 30,
            pager: me.pager,
            //---20150821 fanzhengzhou add s.
            loadui: "disable",
            scroll: false,
            //---20150821 fanzhengzhou add e.
        };
        //20150717 #1965 zhenghuiyun upd e
        me.colModel = [
            {
                name: "ID",
                label: "ID",
                index: "ID",
                width: 30,
                sortable: true,
                align: "left",

                editable: true,
                editoptions: {
                    maxlength: 3,
                    dataEvents: [
                        {
                            type: "keydown",
                            //type : 'keyup',
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "ID", "TOA_NAME", "ID")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TOA_NAME",
                                        "F_HABA",
                                        true,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                //me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "TOA_NAME",
                label: "問合呼称",
                index: "TOA_NAME",
                width: 75,
                sortable: true,
                align: "left",
                editable: true,

                editoptions: {
                    maxlength: 6,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "TOA_NAME", "HTA_PRC", "ID")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "HTA_PRC",
                                        "ID",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {},
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "HTA_PRC",
                label: "本体価格",
                index: "HTA_PRC",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "HTA_PRC", "TNP_PRC", "TOA_NAME")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TNP_PRC",
                                        "TOA_NAME",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                                //me.MathRound(13, this);
                            },
                        },
                    ],
                },
            },
            {
                name: "TNP_PRC",
                label: "店頭価格",
                index: "TNP_PRC",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "TNP_PRC", "FZK_PRC", "HTA_PRC")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "FZK_PRC",
                                        "HTA_PRC",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.editDataFlg = true;
                                me.commonTempVar = $(this).val();
                            },
                        },
                    ],
                },
            },
            {
                name: "FZK_PRC",
                label: "添付価格",
                index: "FZK_PRC",
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd e.
                                // if (!me.setColSelection(key, "FZK_PRC", "SOU_HABA", "TNP_PRC")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SOU_HABA",
                                        "TNP_PRC",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "SOU_HABA",
                label: "利巾",
                index: "SOU_HABA",
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "SOU_HABA", "SYA_PCS", "FZK_PRC")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SYA_PCS",
                                        "FZK_PRC",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "SYA_PCS",
                label: "社内原価",
                index: "SYA_PCS",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "SYA_PCS", "SIK_PCS", "SOU_HABA")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "SIK_PCS",
                                        "SOU_HABA",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "SIK_PCS",
                label: "仕切",
                index: "SIK_PCS",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "SIK_PCS", "FZK_PCS", "SYA_PCS")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "FZK_PCS",
                                        "SYA_PCS",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "FZK_PCS",
                label: "添付社内",
                index: "FZK_PCS",
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd e.
                                // if (!me.setColSelection(key, "FZK_PCS", "FZK_RIE", "SIK_PCS")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "FZK_RIE",
                                        "SIK_PCS",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "FZK_RIE",
                label: "添付利益",
                index: "FZK_RIE",
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "FZK_RIE", "KTN_PCS", "FZK_PCS")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "KTN_PCS",
                                        "FZK_PCS",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "KTN_PCS",
                label: "拠点原価",
                index: "KTN_PCS",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "KTN_PCS", "KTN_HABA", "FZK_RIE")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "KTN_HABA",
                                        "FZK_RIE",
                                        false,
                                        false
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "KTN_HABA",
                label: "拠点巾",
                index: "KTN_HABA",
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "KTN_HABA", "TYK_HABA", "KTN_PCS")) {
                                // return false;
                                // }
                                //---20180112 li UPD S.
                                // if (!me.setColSelection(e, key, "KTN_HABA", "TYK_HABA", "KTN_PCS", false, false)) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TYK_PCS",
                                        "KTN_PCS",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20180112 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                //---20180112 li UPD S.
                // name : 'TYK_HABA',
                name: "TYK_PCS",
                //---20180112 li UPD E.
                label: "特約店原価",
                //---20180112 li UPD S.
                // index : 'TYK_HABA',
                index: "TYK_PCS",
                //---20180112 li UPD E.
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "TYK_HABA", "TYK_PCS", "KTN_HABA")) {
                                // return false;
                                // }
                                //---20180112 li UPD S.
                                // if (!me.setColSelection(e, key, "TYK_HABA", "TYK_PCS", "KTN_HABA", false, false)) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "TYK_HABA",
                                        "KTN_HABA",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20180112 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                //---20180112 li UPD S.
                // name : 'TYK_PCS',
                name: "TYK_HABA",
                //---20180112 li UPD E.
                label: "特約店巾",
                //---20180112 li UPD S.
                // index : 'TYK_PCS',
                index: "TYK_HABA",
                //---20180112 li UPD E.
                width: 70,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd e.
                                // if (!me.setColSelection(key, "TYK_PCS", "F_PCS", "TYK_HABA")) {
                                // return false;
                                // }
                                //---20180112 li UPD S.
                                // if (!me.setColSelection(e, key, "TYK_PCS", "F_PCS", "TYK_HABA", false, false)) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "F_PCS",
                                        "TYK_PCS",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20180112 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "F_PCS",
                label: "Ｆ号原価",
                index: "F_PCS",
                width: 85,
                sortable: true,
                align: "right",
                hidden: false,
                editable: true,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 9,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "F_PCS", "F_HABA", "TYK_PCS")) {
                                // return false;
                                // }
                                //---20180112 li UPD S.
                                // if (!me.setColSelection(e, key, "F_PCS", "F_HABA", "TYK_PCS", false, false)) {
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "F_HABA",
                                        "TYK_HABA",
                                        false,
                                        false
                                    )
                                ) {
                                    //---20180112 li UPD E.
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "F_HABA",
                label: "号巾",
                index: "F_HABA",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: false,
                //---20150821 fanzhengzhou add s.
                formatter: "integer",
                formatoptions: {
                    defaultValue: "",
                },
                //---20150821 fanzhengzhou add e.
                editoptions: {
                    maxlength: 7,
                    dataEvents: [
                        {
                            type: "keydown",
                            fn: function (e) {
                                var key = e.charCode || e.keyCode;
                                me.MathRound(key, this);
                                //---20150819 #2078 fanzhengzhou upd s.
                                // if (!me.setColSelection(key, "F_HABA", "F_HABA", "F_PCS")) {
                                // return false;
                                // }
                                if (
                                    !me.setColSelection(
                                        e,
                                        key,
                                        "ID",
                                        "F_PCS",
                                        false,
                                        true
                                    )
                                ) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                //---20150819 #2078 fanzhengzhou upd e.
                            },
                        },
                        {
                            type: "blur",
                            fn: function () {
                                me.MathRound(13, this);
                            },
                        },
                        {
                            type: "focus",
                            fn: function () {
                                me.commonTempVar = $(this).val();
                                me.editDataFlg = true;
                            },
                        },
                    ],
                },
            },
            {
                name: "UPD_DATE",
                label: "UPD_DATE",
                index: "UPD_DATE",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "CREATE_DATE",
                label: "CREATE_DATE",
                index: "CREATE_DATE",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_SYA_CD",
                label: "UPD_SYA_CD",
                index: "UPD_SYA_CD",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_PRG_ID",
                label: "UPD_PRG_ID",
                index: "UPD_PRG_ID",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
            {
                name: "UPD_CLT_NM",
                label: "UPD_CLT_NM",
                index: "UPD_CLT_NM",
                width: 70,
                sortable: true,
                editable: true,
                align: "right",
                hidden: true,
            },
        ];
        me.complete_fun = function () {
            //20150717 #1965 zhenghuiyun add s
            me.old_data = $(me.grid_id).jqGrid("getRowData");
            //20150717 #1965 zhenghuiyun add e

            me.focusSaveRowData();
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            me.loadGridRowCnt = arrIds.length;
            //---add saved row
            if (me.tmpSaveRowData.length > 0) {
                var tmpcnt1 = $(me.grid_id).jqGrid("getDataIDs");
                var tt = 0;
                for (var tmpI = 0; tmpI < me.tmpSaveRowData.length; tmpI++) {
                    $(me.grid_id).jqGrid(
                        "addRowData",
                        parseInt(tmpcnt1.length) + tt,
                        me.tmpSaveRowData[tmpI]
                    );
                    tt++;
                }
            }
            //---
            rowdata = {
                ID: "",
                TOA_NAME: "",
                HTA_PRC: "",
                TNP_PRC: "",
                FZK_PRC: "",
                SOU_HABA: "",
                SYA_PCS: "",
                SIK_PCS: "",
                FZK_PCS: "",
                FZK_RIE: "",
                KTN_PCS: "",
                KTN_HABA: "",
                //---20180112 li UPD S.
                // TYK_HABA : "",
                // TYK_PCS : "",
                TYK_PCS: "",
                TYK_HABA: "",
                //---20180112 li UPD E.
                F_PCS: "",
                F_HABA: "",
            };
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            $(me.grid_id).jqGrid("addRowData", arrIds.length, rowdata);
            if (arrIds.length >= 1) {
                $(me.grid_id).jqGrid("setSelection", 0);
            }
            me.firstData = $(me.grid_id).jqGrid("getRowData");
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (rowid, _status, e) {
                    //---20150917 li ADD S.
                    me.delDataFlg = false;
                    //---20150917 li ADD E.
                    if (typeof e != "undefined") {
                        //---20150917 li UPD S.
                        // if (rowid && rowid != me.lastsel) {
                        // me.focusSaveRowData1(rowid);
                        // if (me.copyButtonTF == "copy") {
                        // var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
                        // for (var t = 0; t < tmpcnt.length; t++) {
                        // $(me.grid_id).jqGrid('saveRow', t);
                        // };
                        // var rowdata = {
                        // ID : "",
                        // TOA_NAME : "",
                        // HTA_PRC : "",
                        // TNP_PRC : "",
                        // FZK_PRC : "",
                        // SOU_HABA : "",
                        // SYA_PCS : "",
                        // SIK_PCS : "",
                        // FZK_PCS : "",
                        // FZK_RIE : "",
                        // KTN_PCS : "",
                        // KTN_HABA : "",
                        // TYK_HABA : "",
                        // TYK_PCS : "",
                        // F_PCS : "",
                        // F_HABA : ""
                        // };
                        // $(me.grid_id).jqGrid('setRowData', parseInt(tmpcnt.length) - 1, rowdata);
                        // me.copyButtonTF = "";
                        // }
                        //
                        // $(me.grid_id).jqGrid('saveRow', me.lastsel);
                        // me.lastsel = rowid;
                        // }
                        // $(me.grid_id).jqGrid('editRow', rowid, true);
                        // $('input,select', e.target).focus();
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        if (cellIndex != 0) {
                            if (rowid && rowid != me.lastsel) {
                                me.focusSaveRowData1(rowid);
                                $(me.grid_id).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.lastsel = rowid;
                            }
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: cellIndex,
                            });
                        } else {
                            me.delDataFlg = true;
                            //ヘッダークリック
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            var rowID = $(me.grid_id).jqGrid(
                                "getGridParam",
                                "selrow"
                            );
                            var rowData = $(me.grid_id).jqGrid(
                                "getRowData",
                                rowID
                            );
                            if (rowData["ID"].toString().trimEnd() == "") {
                                return;
                            }
                            me.jqgridCurrentRowID = rowID;
                            me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                            me.clsComFnc.MessageBox(
                                "削除します。よろしいですか？",
                                me.clsComFnc.GSYSTEM_NAME,
                                "YesNo",
                                "Question",
                                me.clsComFnc.MessageBoxDefaultButton.Button2
                            );
                        }
                        //---20150917 li UPD E.
                    } else {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowid;
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: false,
                            });
                        }
                    }
                    if (me.frontLineNo == -1) {
                        me.frontLineNo = rowid;
                    }
                    //---20150917 li ADD S.
                    if (!me.delDataFlg) {
                        //---20150917 li ADD E.
                        if (me.frontLineNo != rowid) {
                            me.frontLineNo1 = me.frontLineNo;
                            if (me.showMsgTF != true) {
                                if (me.copyButtonTF == "copy") {
                                } else {
                                    if (me.checkNull()) {
                                        me.checkUnique(e);
                                    }
                                }
                            }
                        }
                        //---20150917 li ADD S.
                    }
                    //---20150917 li ADD E.

                    $(".numeric").numeric({
                        decimal: false,
                        negative: false,
                    });
                    me.frontLineNo = rowid;
                },
            });
            //me.set_pager_row_count();
        };
        var tmpdata = {};
        //20150717 #1965 zhenghuiyun upd s
        // gdmz.common.jqgrid.show(me.grid_id, me.g_url, me.colModel, me.pager, me.sidx, me.option, tmpdata, me.complete_fun);
        gdmz.common.jqgrid.show_2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        //20150717 #1965 zhenghuiyun upd e
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1000);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 280);
        //---20150819 #2078 fanzhengzhou add s.
        $(me.grid_id).jqGrid("bindKeys");
        //---20150819 #2078 fanzhengzhou add e.
    };

    me.FrmGenka_load = function () {
        me.initGrid();
        //---20150831 li DEL S.
        //me.fnckeyDown46();
        //---20150831 li DEL E.
    };
    //--click event functions--
    me.fnc_click_cmdInsert = function () {
        me.buttonActionFlg = "insert";
        //---20180209 CIYUANCHEN INS S.
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < arrIds.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        $(me.grid_id).jqGrid("setSelection", parseInt(arrIds.length) - 1);
        $(me.grid_id).jqGrid("editRow", parseInt(arrIds.length) - 1, true);
        //20240624 lujunxia ins s
        //IDをfocusのために
        me.lastsel = parseInt(arrIds.length) - 1;
        me.lastcol = "ID";
        //20240624 lujunxia ins e
        $("#" + (parseInt(arrIds.length) - 1) + "_UCOYA_CD").trigger("focus");
        //---20180209 CIYUANCHEN INS E.
    };
    me.fnc_click_cmdUpdate = function () {
        me.buttonActionFlg = "update";
        if (me.editDataFlg == false) {
            return;
        }
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        if (arrIds.length == 1) {
            return;
        }
        if (!me.checkNull_Update()) {
            return;
        }
        if (!me.checkUnique_Update()) {
            return;
        }

        //確認メッセージ
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.NoUpdateFnc;
        me.clsComFnc.FncMsgBox("QY010");
    };
    me.fnc_click_cmdCancel = function () {
        me.buttonActionFlg = "cancel";
        var tmpdata = {};
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    me.fnc_click_cmdSearch = function () {
        me.buttonActionFlg = "search";
        var tmpdata = {
            txtTOA_NAME: $(".FrmGenka.txtTOA_NAME").val().toString().trimEnd(),
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };
    me.fnc_click_cmdCopy = function () {
        me.buttonActionFlg = "copy";
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");

        if (rowID == arrIds.length - 1) {
            return;
        }
        if (me.checkNull()) {
            if (!me.checkUnique()) {
                return;
            }
        } else {
            return;
        }
        me.copyButtonTF = "copy";
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        var lastRowId = parseInt(tmpcnt.length) - 1;
        if (rowID < lastRowId) {
            for (var t = 0; t < tmpcnt.length; t++) {
                $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
            }
            var tmpdata = $(me.grid_id).jqGrid("getRowData", rowID);
            $(me.grid_id).jqGrid("setRowData", lastRowId, tmpdata);
            $(me.grid_id).jqGrid("setSelection", lastRowId);
            $(me.grid_id).jqGrid("editRow", lastRowId);
            $(me.grid_id).jqGrid("saveRow", lastRowId, null, "clientArray");
            me.focusSaveRowData();
            $(me.grid_id).jqGrid("editRow", lastRowId);
        }
    };
    //--functions--
    me.keyupAddrow = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (tmpcnt.length - 1 == rowID) {
            rowdata = {};
            $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
        }
    };

    //---20150819 #2078 fanzhengzhou upd s.
    me.setColSelection = function (
        e,
        key,
        colNextName,
        colPreviousName,
        firstCol,
        lastCol
    ) {
        var GridRecords = $(me.grid_id).jqGrid("getGridParam", "reccount");
        if (key == 13) {
            return false;
        }
        if ((e.shiftKey && key == 37) || (e.shiftKey && key == 39)) {
            return true;
        } else {
            //Shift+Tab && Left
            if ((e.shiftKey && key == 9) || key == 37) {
                if (firstCol == true && parseInt(me.lastsel) == 0) {
                    return false;
                } else if (firstCol == true && parseInt(me.lastsel) > 0) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) - 1,
                        true
                    );
                }
                $("#" + me.lastsel + "_" + colPreviousName).trigger("focus");
                $("#" + me.lastsel + "_" + colPreviousName).trigger("select");
                return false;
            }

            //Tab && Rhght
            if (key == 9 || key == 39) {
                if (lastCol == true && me.lastsel == GridRecords - 1) {
                    return false;
                } else if (lastCol == true && me.lastsel < GridRecords - 1) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        parseInt(me.lastsel) + 1
                    );
                }
                $("#" + me.lastsel + "_" + colNextName).trigger("focus");
                $("#" + me.lastsel + "_" + colNextName).trigger("select");
                return false;
            }
            //---20150831 li INS S.
            //---20150917 li DEL S.
            // if (key == 46  && colNowName == "ID") {
            // me.delRowData();
            // }
            //---20150917 li DEL E.
            //---20150831 li INS E.
        }
        if (
            (key >= 65 && key <= 90) ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105) ||
            (key >= 186 && key <= 222) ||
            (key >= 109 && key <= 111) ||
            key == 106 ||
            key == 107
        ) {
            me.keyupAddrow();
        }
        if (key == 222) {
            return false;
        }
        return true;
    };
    //---20150819 #2078 fanzhengzhou upd e.

    me.YesActionFnc = function () {
        //業者ﾏｽﾀに登録開始
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        var data = $(me.grid_id).jqGrid("getRowData");
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoActionFnc = function () {
        return;
    };
    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var allRowData = $(me.grid_id).jqGrid("getRowData");
        for (var tmpI = rowID; tmpI < allRowData.length - 1; tmpI++) {
            $(me.grid_id).jqGrid(
                "setRowData",
                parseInt(tmpI),
                allRowData[parseInt(tmpI) + 1]
            );
            $(me.grid_id).jqGrid("delRowData", allRowData.length - 1);
        }
        //---20150917 li ADD S.
        $(me.grid_id).jqGrid("setSelection", rowID);
        me.setButtonEnableState();
        //---20150917 li ADD E.
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

    //---20150917 li ADD S.
    me.setButtonEnableState = function () {
        var arrIds = $(me.grid_id).jqGrid("getDataIDs");
        if (arrIds.length >= 1) {
            $(".FrmSyasyu.cmdCopy").button("enable");
            $(".FrmSyasyu.cmdInsert").button("enable");
            $(".FrmSyasyu.cmdUpdate").button("enable");
            $(".FrmSyasyu.cmdCancel").button("enable");
        } else {
            $(".FrmSyasyu.cmdCopy").button("disable");
            $(".FrmSyasyu.cmdInsert").button("disable");
            $(".FrmSyasyu.cmdUpdate").button("disable");
            $(".FrmSyasyu.cmdCancel").button("disable");
        }
    };
    //---20150917 li ADD E.
    me.cancelsel = function () {};

    me.YesUpdateFnc = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        me.updateUrl = me.sys_id + "/" + me.id + "/" + "fncUpdate";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.clsComFnc.MessageBox(
                    "更新完了しました。",
                    "DB登録",
                    "OK",
                    "Information"
                );
            }
        };
        //20150717 #1965 zhenghuiyun upd s
        // var data = $(me.grid_id).jqGrid('getRowData');
        var new_data = $(me.grid_id).jqGrid("getRowData");
        var data = {
            old: JSON.stringify(me.old_data),
            new: JSON.stringify(new_data),
        };
        //20150717 #1965 zhenghuiyun upd e
        me.ajax.send(me.updateUrl, data, 0);
    };
    me.NoUpdateFnc = function () {};
    //---20150831 li DEL S.
    // me.fnckeyDown46 = function() {
    // me.inp = $(me.grid_id);
    // me.inp.bind('keydown', function(e) {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 46) {
    // me.delRowData();
    // };
    // });
    // };
    //---20150831 li DEL E.

    me.MathRound = function (key, obj) {
        if (key == 13 || (key >= 37 && key <= 40) || key == 9) {
            if ($(obj).val() != "") {
                $(obj).val(Math.round($(obj).val()));
            }
            if ($(obj).val() === "NaN") {
                $(obj).val(me.commonTempVar);
            }
        }
    };
    me.yesCorrectRowData = function () {
        $(me.grid_id).jqGrid("editRow", me.frontLineNo1, true);
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo1);
    };
    me.yesCorrectRowData_Update = function () {
        $(me.grid_id).jqGrid("editRow", me.frontLineNo2, true);
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo2);
    };
    me.noCorrectRowData = function () {
        $(me.grid_id).jqGrid("setSelection", me.frontLineNo1);
        var currentRowId = parseInt(me.frontLineNo) + 1;
        $(me.grid_id).jqGrid("saveRow", me.frontLineNo, null, "clientArray");
        if (me.loadGridRowCnt < currentRowId) {
            if (
                me.focusSaveRowDataArr[me.frontLineNo] !== undefined &&
                me.focusSaveRowDataArr[me.frontLineNo]["TOA_NAME"] == "" &&
                me.focusSaveRowDataArr[me.frontLineNo]["HTA_PRC"] == ""
            ) {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo,
                    $(me.grid_id).jqGrid(
                        "getRowData",
                        parseInt(me.frontLineNo) + 1
                    )
                );
                $(me.grid_id).jqGrid(
                    "delRowData",
                    parseInt(me.frontLineNo) + 1
                );
                $(me.grid_id).jqGrid("editRow", parseInt(me.frontLineNo));
                $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
            } else {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    me.frontLineNo,
                    me.focusSaveRowDataArr[me.frontLineNo]
                );
                $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
            }
        } else {
            $(me.grid_id).jqGrid(
                "setRowData",
                me.frontLineNo,
                me.focusSaveRowDataArr[me.frontLineNo]
            );
            $(me.grid_id).jqGrid("setSelection", me.frontLineNo);
        }
    };
    me.noCorrectRowData_Update = function () {
        var rowID = parseInt(me.frontLineNo2);
        currentRowId = rowID;
        if (me.loadGridRowCnt < currentRowId) {
            if (
                me.focusSaveRowDataArr[currentRowId]["TOA_NAME"] == "" &&
                me.focusSaveRowDataArr[currentRowId]["HTA_PRC"] == ""
            ) {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    currentRowId,
                    $(me.grid_id).jqGrid(
                        "getRowData",
                        parseInt(currentRowId) + 1
                    )
                );
                $(me.grid_id).jqGrid("delRowData", parseInt(currentRowId) + 1);
                $(me.grid_id).jqGrid("editRow", parseInt(currentRowId));
                $(me.grid_id).jqGrid("setSelection", currentRowId);
            } else {
                $(me.grid_id).jqGrid(
                    "setRowData",
                    currentRowId,
                    me.focusSaveRowDataArr[currentRowId]
                );
                $(me.grid_id).jqGrid("setSelection", currentRowId);
            }
        } else {
            $(me.grid_id).jqGrid(
                "setRowData",
                currentRowId,
                me.focusSaveRowDataArr[currentRowId]
            );
            $(me.grid_id).jqGrid("setSelection", currentRowId);
        }
    };
    me.closeCorrectRowData = function () {
        me.showMsgTF = false;
    };
    me.checkUnique = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["TOA_NAME"] != "" &&
                    grid_data1[i]["HTA_PRC"] != ""
                ) {
                    if (
                        grid_data1[i]["TOA_NAME"].toString().toUpperCase() ==
                            grid_data1[j]["TOA_NAME"]
                                .toString()
                                .toUpperCase() &&
                        grid_data1[i]["HTA_PRC"].toString().toUpperCase() ==
                            grid_data1[j]["HTA_PRC"].toString().toUpperCase()
                    ) {
                        me.showMsgTF = true;
                        me.frontLineNo1 = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["TOA_NAME"]
                                    .toString()
                                    .toUpperCase() !==
                                    grid_data1[i]["TOA_NAME"]
                                        .toString()
                                        .toUpperCase() ||
                                me.firstData[i]["HTA_PRC"]
                                    .toString()
                                    .toUpperCase() !==
                                    grid_data1[j]["HTA_PRC"]
                                        .toString()
                                        .toUpperCase()
                            ) {
                                me.frontLineNo1 = i;
                            }
                        }
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                        me.clsComFnc.MsgBoxBtnFnc.Close =
                            me.closeCorrectRowData;
                        me.clsComFnc.MessageBox(
                            "列'問合呼称,本体価格'は一意であるように制約されています。値'" +
                                grid_data1[i]["TOA_NAME"] +
                                "," +
                                grid_data1[i]["HTA_PRC"] +
                                "' は既に存在します。値を修正しますか?",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                        return false;
                    }
                }
            }
        }
        $(me.grid_id).jqGrid("editRow", me.lastsel, {
            focusField: false,
        });
        $("#" + parseInt(me.lastsel) + "_" + me.lastcol).focus();

        return true;
    };
    me.checkUnique_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            for (var j = i + 1; j < grid_data1.length; j++) {
                if (
                    grid_data1[i]["TOA_NAME"] != "" &&
                    grid_data1[i]["HTA_PRC"] != ""
                ) {
                    if (
                        grid_data1[i]["TOA_NAME"].toString().toUpperCase() ==
                            grid_data1[j]["TOA_NAME"]
                                .toString()
                                .toUpperCase() &&
                        grid_data1[i]["HTA_PRC"].toString().toUpperCase() ==
                            grid_data1[j]["HTA_PRC"].toString().toUpperCase()
                    ) {
                        me.showMsgTF = true;
                        me.frontLineNo2 = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["TOA_NAME"]
                                    .toString()
                                    .toUpperCase() !==
                                    grid_data1[i]["TOA_NAME"]
                                        .toString()
                                        .toUpperCase() ||
                                me.firstData[i]["HTA_PRC"]
                                    .toString()
                                    .toUpperCase() !==
                                    grid_data1[j]["HTA_PRC"]
                                        .toString()
                                        .toUpperCase()
                            ) {
                                me.frontLineNo2 = i;
                            }
                        }
                        me.clsComFnc.MsgBoxBtnFnc.Yes =
                            me.yesCorrectRowData_Update;
                        me.clsComFnc.MsgBoxBtnFnc.No =
                            me.noCorrectRowData_Update;
                        me.clsComFnc.MsgBoxBtnFnc.Close =
                            me.closeCorrectRowData;
                        me.clsComFnc.MessageBox(
                            "列'問合呼称,本体価格'は一意であるように制約されています。値'" +
                                grid_data1[i]["TOA_NAME"] +
                                "," +
                                grid_data1[i]["HTA_PRC"] +
                                "' は既に存在します。値を修正しますか?",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    me.checkNull = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            if (
                grid_data1[i]["TOA_NAME"] == "" ||
                grid_data1[i]["HTA_PRC"] == ""
            ) {
                var tmpField = "";
                if (grid_data1[i]["TOA_NAME"] == "") {
                    tmpField = "問合呼称";
                    //tmp_rowData['TOA_NAME'];
                }
                if (grid_data1[i]["HTA_PRC"] == "") {
                    tmpField = "本体価格";
                    //tmp_rowData['HTA_PRC'];
                }
                if (me.frontLineNo1 == tmpcnt.length - 1) {
                    return true;
                }
                me.showMsgTF = true;
                me.frontLineNo1 = i;
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData;
                me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData;
                me.clsComFnc.MsgBoxBtnFnc.Close = me.closeCorrectRowData;
                me.clsComFnc.MessageBox(
                    "列'" +
                        tmpField +
                        "'にNullを使用することはできません。値を修正しますか?",
                    me.clsComFnc.GSYSTEM_NAME,
                    "YesNo",
                    "Question",
                    me.clsComFnc.MessageBoxDefaultButton.Button2
                );
                return false;
            }
        }
        $(me.grid_id).jqGrid("editRow", me.lastsel, {
            focusField: false,
        });
        $("#" + parseInt(me.lastsel) + "_" + me.lastcol).focus();
        return true;
    };
    me.checkNull_Update = function () {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        for (var t = 0; t < tmpcnt.length; t++) {
            $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
        }
        var grid_data1 = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < grid_data1.length - 1; i++) {
            if (
                grid_data1[i]["ID"] == "" &&
                grid_data1[i]["TOA_NAME"] == "" &&
                grid_data1[i]["HTA_PRC"] == "" &&
                grid_data1[i]["TNP_PRC"] == "" &&
                grid_data1[i]["FZK_PRC"] == "" &&
                grid_data1[i]["SOU_HABA"] == "" &&
                grid_data1[i]["SYA_PCS"] == "" &&
                grid_data1[i]["SIK_PCS"] == "" &&
                grid_data1[i]["FZK_PCS"] == "" &&
                grid_data1[i]["FZK_RIE"] == "" &&
                grid_data1[i]["KTN_PCS"] == "" &&
                grid_data1[i]["KTN_HABA"] == "" &&
                grid_data1[i]["TYK_HABA"] == "" &&
                grid_data1[i]["TYK_PCS"] == "" &&
                grid_data1[i]["F_PCS"] == "" &&
                grid_data1[i]["F_HABA"] == ""
            ) {
            } else {
                if (
                    grid_data1[i]["TOA_NAME"] == "" ||
                    grid_data1[i]["HTA_PRC"] == ""
                ) {
                    var tmpField = "";
                    if (grid_data1[i]["TOA_NAME"] == "") {
                        tmpField = "問合呼称";
                    }
                    if (grid_data1[i]["HTA_PRC"] == "") {
                        tmpField = "本体価格";
                    }
                    me.showMsgTF = true;
                    me.frontLineNo2 = i;
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.yesCorrectRowData_Update;
                    me.clsComFnc.MsgBoxBtnFnc.No = me.noCorrectRowData_Update;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.closeCorrectRowData;
                    me.clsComFnc.MessageBox(
                        "列'" +
                            tmpField +
                            "'にNullを使用することはできません。値を修正しますか?",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                    return false;
                }
            }
        }
        return true;
    };

    me.focusSaveRowData = function () {
        var tt = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.focusSaveRowDataArr[tt] = $(me.grid_id).jqGrid("getRowData", tt);
    };
    me.focusSaveRowData1 = function (rowid) {
        //var tt = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        if (rowid && rowid != me.lastsel) {
            $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
            me.focusSaveRowDataArr[rowid] = $(me.grid_id).jqGrid(
                "getRowData",
                rowid
            );
        }
    };
    me.copyRowData = function () {};

    me.tempPackage = function () {
        if (me.buttonActionFlg != "update") {
            me.focusSaveRowData();
        }

        if (me.copyButtonTF == "copy") {
            var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
            for (var t = 0; t < tmpcnt.length; t++) {
                $(me.grid_id).jqGrid("saveRow", t, null, "clientArray");
            }
            var rowdata = {
                ID: "",
                TOA_NAME: "",
                HTA_PRC: "",
                TNP_PRC: "",
                FZK_PRC: "",
                SOU_HABA: "",
                SYA_PCS: "",
                SIK_PCS: "",
                FZK_PCS: "",
                FZK_RIE: "",
                KTN_PCS: "",
                KTN_HABA: "",
                //---20180112 li UPD S.
                // TYK_HABA : "",
                // TYK_PCS : "",
                TYK_PCS: "",
                TYK_HABA: "",
                //---20180112 li UPD E.
                F_PCS: "",
                F_HABA: "",
            };
            $(me.grid_id).jqGrid(
                "setRowData",
                parseInt(tmpcnt.length) - 1,
                rowdata
            );
            me.copyButtonTF = "";
        }
    };
    me.set_pager_row_count = function () {
        var ttt = document.getElementById("FrmGenka_pager_center");

        var tmp_ttt = ttt.childNodes[0].innerHTML
            .toString()
            .replace("検索結果 ", "");
        tmp_ttt = tmp_ttt.replace("件を表示しました", "");
        ttt.childNodes[0].innerHTML =
            "検索結果 " +
            (parseInt(tmp_ttt) - 1).toString() +
            "件を表示しました";
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmGenka = new R4.FrmGenka();
    o_R4_FrmGenka.load();
    o_R4K_R4K.FrmGenka = o_R4_FrmGenka;
});
