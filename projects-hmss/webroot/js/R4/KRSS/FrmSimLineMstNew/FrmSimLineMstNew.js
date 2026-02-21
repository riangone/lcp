/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmSimLineMstNew");

KRSS.FrmSimLineMstNew = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSimLineMstNew";
    me.sys_id = "KRSS";
    //（ライン） the last select line.
    me.lastsel = "";
    //（科目から集計）the last select line.
    me.lastsel1 = "";

    me.updData = new Object();

    me.isMakeErrFocus = false;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.colModel = [
        {
            name: "LINE_NO",
            label: "No",
            index: "LINE_NO",
            sortable: false,
            width: 50,
            align: "center",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                if (parseInt(me.lastsel) == 1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmSimLineMstNew_LINE").jqGrid(
                                        "saveRow",
                                        me.lastsel,
                                        null,
                                        "clientArray"
                                    );
                                    $("#FrmSimLineMstNew_LINE").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) - 1,
                                        true
                                    );
                                    setTimeout(function () {
                                        $(
                                            "#" +
                                                parseInt(me.lastsel) +
                                                "_DISP_KB"
                                        ).trigger("focus");
                                    }, 0);
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_ITEM_NM").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ITEM_NM",
            label: "ライン名",
            index: "ITEM_NM",
            sortable: false,
            width: me.ratio === 1.5 ? 280 : 340,
            align: "left",
            editable: true,
            editoptions: {
                maxlength: "40",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_LINE_NO").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_SRC_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SRC_KB",
            label: "集計元",
            index: "SRC_KB",
            sortable: false,
            width: me.ratio === 1.5 ? 30 : 35,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "1",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var newCodeValue = $(e.target).val();
                            if (newCodeValue == "1") {
                                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").prop(
                                    "disabled",
                                    true
                                );
                                $("#FrmSimLineMstNew_KAMOKU")
                                    .closest(".ui-jqgrid")
                                    .unblock();
                            } else if (newCodeValue == "2") {
                                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").prop(
                                    "disabled",
                                    false
                                );
                                $("#FrmSimLineMstNew_KAMOKU")
                                    .closest(".ui-jqgrid")
                                    .block();
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_ITEM_NM").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_RND_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "RND_KB",
            label: "丸め区分",
            index: "RND_KB",
            sortable: false,
            width: me.ratio === 1.5 ? 30 : 35,
            align: "right",
            editable: true,
            editoptions: {
                maxlength: "1",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_SRC_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_RND_POS").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "RND_POS",
            label: "丸め位置",
            index: "RND_POS",
            sortable: false,
            width: me.ratio === 1.5 ? 30 : 35,
            align: "right",
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_RND_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_CAL_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CAL_KB",
            label: "計算区分",
            index: "CAL_KB",
            sortable: false,
            width: me.ratio === 1.5 ? 30 : 35,
            align: "center",
            editable: true,
            editoptions: {
                //class : 'numeric',
                maxlength: "1",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_RND_POS").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel + "_DISP_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "DISP_KB",
            label: "表示区分",
            index: "DISP_KB",
            sortable: false,
            width: me.ratio === 1.5 ? 30 : 35,
            align: "right",
            editable: true,
            editoptions: {
                maxlength: "1",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_CAL_KB").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                var LastRow = me.lastsel;
                                var totalrow = $(
                                    "#FrmSimLineMstNew_LINE"
                                ).jqGrid("getGridParam", "records");
                                if (parseInt(me.lastsel) + 1 > totalrow) {
                                    //最后一格，ENTER，判断前面若有非空的，行+1.
                                    if (
                                        key == 13 &&
                                        $("#" + LastRow + "_LINE_NO").val() !=
                                            "" &&
                                        $("#" + LastRow + "_ITEM_NM").val() !=
                                            "" &&
                                        $("#" + LastRow + "_SRC_KB").val() !=
                                            "" &&
                                        $("#" + LastRow + "_CAL_KB").val() != ""
                                    ) {
                                        $("#FrmSimLineMstNew_LINE").jqGrid(
                                            "addRowData",
                                            parseInt(LastRow) + 1,
                                            me.LineRow
                                        );
                                        $("#FrmSimLineMstNew_LINE").jqGrid(
                                            "setSelection",
                                            parseInt(me.lastsel) + 1,
                                            true
                                        );
                                    } else {
                                        $("#FrmSimLineMstNew_LINE").jqGrid(
                                            "setSelection",
                                            me.lastsel,
                                            true
                                        );
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                } else {
                                    $("#FrmSimLineMstNew_LINE").jqGrid(
                                        "saveRow",
                                        me.lastsel,
                                        null,
                                        "clientArray"
                                    );
                                    $("#FrmSimLineMstNew_LINE").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) + 1,
                                        true
                                    );
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

    me.colModel1 = [
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            sortable: false,
            width: 100,
            align: "left",
            editable: true,
            editoptions: {
                maxlength: "5",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                if (parseInt(me.lastsel1) == 1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                                        "saveRow",
                                        me.lastsel1,
                                        null,
                                        "clientArray"
                                    );
                                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel1) - 1,
                                        true
                                    );
                                    setTimeout(function () {
                                        $(
                                            "#" + me.lastsel1 + "_CAL_KB1"
                                        ).trigger("focus");
                                    }, 0);
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel1 + "_HIMOK_CD").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "HIMOK_CD",
            label: "費目コード",
            index: "HIMOK_CD",
            sortable: false,
            width: 100,
            align: "left",
            editable: true,
            editoptions: {
                maxlength: "3",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel1 + "_KAMOK_CD").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                $("#" + me.lastsel1 + "_CAL_KB1").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CAL_KB1",
            label: "計算区分",
            index: "CAL_KB1",
            sortable: false,
            width: 100,
            align: "center",
            editable: true,
            editoptions: {
                //class : 'numeric',
                maxlength: "1",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab  (warning:this must be put in front of (key==13||key==9).)
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel1 + "_HIMOK_CD").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                var totalrow = $(
                                    "#FrmSimLineMstNew_KAMOKU"
                                ).jqGrid("getGridParam", "records");
                                if (parseInt(me.lastsel1) == totalrow) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                                        "saveRow",
                                        me.lastsel1,
                                        null,
                                        "clientArray"
                                    );
                                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel1) + 1,
                                        true
                                    );
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

    me.LineRow = {
        LINE_NO: "",
        ITEM_NM: "",
        SRC_KB: "",
        RND_KB: "",
        RND_POS: "",
        CAL_KB: "",
        DISP_KB: "",
    };

    me.KAMOKU_ROW = {
        KAMOK_CD: "",
        HIMOK_CD: "",
        CAL_KB1: "",
    };

    me.controls.push({
        id: ".KRSS.FrmSimLineMstNew.update",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmSimLineMstNew.cancel",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        // 初期処理
        me.FrmSimLineMstNew_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //（ライン）
    $("#FrmSimLineMstNew_LINE").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: me.ratio === 1.5 ? 290 : 338,
        rownumbers: false,
        colModel: me.colModel,

        onSelectRow: function (rowId, _status, e) {
            //ライン一覧　行選択時
            var rowdata = $("#FrmSimLineMstNew_LINE").jqGrid(
                "getRowData",
                rowId
            );
            console.log(rowdata);
            var line_no = rowdata["LINE_NO"];
            var src_kb = rowdata["SRC_KB"];

            if (line_no.toString().indexOf("<input") == 0) {
                return;
            }

            $("#FrmSimLineMstNew_KAMOKU").jqGrid("clearGridData");
            $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val("");
            //（選択行 集計元区分が1のとき） 科目から集計一覧を使用可,科目以外から集計を使用不可,選択されたラインNoと一致する科目を一覧表示する
            //（選択行 集計元区分が2のとき） 科目から集計一覧を使用不可,科目以外から集計を使用可,科目以外から集計に集計元ビュー名を表示
            if (src_kb == "1") {
                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").prop(
                    "disabled",
                    true
                );
                $("#FrmSimLineMstNew_KAMOKU").closest(".ui-jqgrid").unblock();
                me.fncshowkamoku(line_no, 1);
            } else if (src_kb == "2") {
                // for ( i = 0; i < 24; i++) {
                // $("#FrmSimLineMstNew_KAMOKU").jqGrid('addRowData', i + 1, me.KAMOKU_ROW);
                // };
                $("#FrmSimLineMstNew_KAMOKU").closest(".ui-jqgrid").block();

                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").prop(
                    "disabled",
                    false
                );
                me.fncshowkamoku(line_no, 2);
            } else {
                // for ( i = 0; i < 24; i++) {
                // $("#FrmSimLineMstNew_KAMOKU").jqGrid('addRowData', i + 1, me.KAMOKU_ROW);
                // };
                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").prop(
                    "disabled",
                    true
                );
                $("#FrmSimLineMstNew_KAMOKU").closest(".ui-jqgrid").block();
                me.fncshowkamoku(line_no, 3);
            }
            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (rowId && rowId !== me.lastsel) {
                    $("#FrmSimLineMstNew_LINE").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowId;
                }
                $("#FrmSimLineMstNew_LINE").jqGrid("editRow", rowId, {
                    keys: true,
                    focusField: cellIndex,
                });
                $("input, select", e.target).trigger("focus");
            } else {
                if (rowId && rowId !== me.lastsel) {
                    $("#FrmSimLineMstNew_LINE").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowId;
                }
                $("#FrmSimLineMstNew_LINE").jqGrid("editRow", rowId, true);
            }
            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });
        },
    });
    $("#FrmSimLineMstNew_LINE").jqGrid("bindKeys");

    $("#FrmSimLineMstNew_LINE").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });

    //（科目から集計）
    $("#FrmSimLineMstNew_KAMOKU").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: me.ratio === 1.5 ? 230 : 286,
        rownumbers: true,
        rownumWidth: 40,
        colModel: me.colModel1,
        onSelectRow: function (rowId, _status, e) {
            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (rowId && rowId !== me.lastsel1) {
                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                        "saveRow",
                        me.lastsel1,
                        null,
                        "clientArray"
                    );
                    me.lastsel1 = rowId;
                }
                $("#FrmSimLineMstNew_KAMOKU").jqGrid("editRow", rowId, {
                    keys: true,
                    focusField: cellIndex,
                });
                $("input, select", e.target).trigger("focus");
            } else {
                if (rowId && rowId !== me.lastsel1) {
                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                        "saveRow",
                        me.lastsel1,
                        null,
                        "clientArray"
                    );
                    me.lastsel1 = rowId;
                }
                $("#FrmSimLineMstNew_KAMOKU").jqGrid("editRow", rowId, true);
            }
            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });
        },
    });
    $("#FrmSimLineMstNew_KAMOKU").jqGrid("bindKeys");

    $("#FrmSimLineMstNew_KAMOKU").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });

    $("#jqgh_FrmSimLineMstNew_KAMOKU_rn").html("No");

    //**********************************************************************
    //処理説明：更新ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmSimLineMstNew.update").click(function () {
        me.fnccheck();
    });

    //**********************************************************************
    //処理説明：キャンセルボタン押下時
    //**********************************************************************
    $(".KRSS.FrmSimLineMstNew.cancel").click(function () {
        me.FrmSimLineMstNew_Load();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ＫＥＹ「↓」
    //関 数 名：fncDown
    //引    数1：lastselect
    //引    数2：tableid
    //引    数3：name
    //戻 り 値：無し
    //処理説明：ＫＥＹ「↓」
    //**********************************************************************
    // me.fncDown = function (lastselect, tableid, name) {
    //     var selIRow = parseInt(lastselect) + 1;
    //     var totalrow = $(tableid).jqGrid("getGridParam", "records");
    //     if (selIRow == totalrow + 1) {
    //         return false;
    //     }
    //     $(tableid).jqGrid("saveRow", lastselect, null, "clientArray");
    //     $(tableid).jqGrid("setSelection", selIRow, true);

    //     var selNextId = "#" + selIRow + "_" + name;
    //     $(selNextId).focus();
    // };

    //**********************************************************************
    //処 理 名：ＫＥＹ「↑」
    //関 数 名：funcUp
    //引    数1：lastselect
    //引    数2：tableid
    //引    数3：name
    //戻 り 値：無し
    //処理説明：ＫＥＹ「↑」
    //**********************************************************************
    // me.fncUp = function (lastselect, tableid, name) {
    //     var selIRow = parseInt(lastselect) - 1;
    //     if (selIRow == 0) {
    //         return false;
    //     }
    //     $(tableid).jqGrid("saveRow", lastselect, null, "clientArray");
    //     $(tableid).jqGrid("setSelection", selIRow, true);

    //     var selNextId = "#" + selIRow + "_" + name;
    //     $(selNextId).trigger("focus");
    // };

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：FrmSimLineMstNew_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.FrmSimLineMstNew_Load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FrmSimLineMstNew_Load";
        me.ajax.receive = function (result) {
            $("#FrmSimLineMstNew_LINE").jqGrid("clearGridData");
            result = JSON.parse(result);
            if (result["result"] == true) {
                //該当データなし
                if (result["data"].length == 0) {
                    $("#FrmSimLineMstNew_LINE").jqGrid(
                        "addRowData",
                        1,
                        me.LineRow
                    );
                } else {
                    //スプレッドにデータリーダーの内容をセット
                    for (key in result["data"]) {
                        //null->"＋" , -1 ->"－"
                        if (result["data"][key]["CAL_KB"] == 1) {
                            var tmp_cal_kb = "+";
                        } else if (result["data"][key]["CAL_KB"] == -1) {
                            var tmp_cal_kb = "-";
                        }
                        var columns = {
                            LINE_NO: result["data"][key]["LINE_NO"],
                            ITEM_NM: result["data"][key]["ITEM_NM"],
                            SRC_KB: result["data"][key]["SRC_KB"],
                            RND_KB: result["data"][key]["RND_KB"],
                            RND_POS: result["data"][key]["RND_POS"],
                            CAL_KB: tmp_cal_kb,
                            DISP_KB: result["data"][key]["DISP_KB"],
                        };
                        $("#FrmSimLineMstNew_LINE").jqGrid(
                            "addRowData",
                            parseInt(key) + 1,
                            columns
                        );
                    }
                    //添加一行
                    $("#FrmSimLineMstNew_LINE").jqGrid(
                        "addRowData",
                        parseInt(key) + 2,
                        me.LineRow
                    );
                }
                //１行目を選択状態にする
                $("#FrmSimLineMstNew_LINE").jqGrid("setSelection", 1, true);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：科目から集計一覧表示/（科目以外から集計）
    //関 数 名：fncshowkamoku
    //引    数1：line_no
    //引    数2：flag
    //戻 り 値：無し
    //処理説明：科目から集計一覧表示/（科目以外から集計）
    //**********************************************************************
    me.fncshowkamoku = function (line_no, flag) {
        console.log(line_no);
        var url = me.sys_id + "/" + me.id + "/" + "showkamoku";
        var data = line_no;
        me.ajax.receive = function (result) {
            $("#FrmSimLineMstNew_KAMOKU").jqGrid("clearGridData");
            $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val("");
            result = JSON.parse(result);
            if (result["result"] == true) {
                //科目から集計一覧表示
                for (key in result["data"]["kamoku"]) {
                    //null->"＋" , -1 ->"－"
                    if (result["data"]["kamoku"][key]["CAL_KB1"] == 1) {
                        var tmp_cal_kb = "+";
                    } else if (result["data"]["kamoku"][key]["CAL_KB1"] == -1) {
                        var tmp_cal_kb = "-";
                    }
                    var columns = {
                        KAMOK_CD: result["data"]["kamoku"][key]["KAMOK_CD"],
                        HIMOK_CD: $.trim(
                            result["data"]["kamoku"][key]["HIMOK_CD"]
                        ),
                        CAL_KB1: tmp_cal_kb,
                    };
                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                        "addRowData",
                        parseInt(key) + 1,
                        columns
                    );
                }
                if (
                    result["data"]["kamoku"] &&
                    result["data"]["kamoku"].length < 24
                ) {
                    for (i = result["data"]["kamoku"].length; i < 24; i++) {
                        $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                            "addRowData",
                            i + 1,
                            me.KAMOKU_ROW
                        );
                    }
                }
                if (
                    flag == 1 &&
                    result["data"]["kamoku"] &&
                    result["data"]["kamoku"].length == 0
                ) {
                    //データが存在しない場合 右側一覧の１行目にフォーカス
                    $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                }

                //科目以外から集計表示
                if (
                    result["data"]["viewname"] &&
                    result["data"]["viewname"].length > 0
                ) {
                    $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val(
                        result["data"]["viewname"][0]["SRC_VIEWNAME"]
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, data, 0);
    };

    // //**********************************************************************
    // //処 理 名：（科目以外から集計）
    // //関 数 名：fncshow_src_viewname
    // //引    数：line_no
    // //戻 り 値：無し
    // //処理説明：（科目以外から集計）
    // //**********************************************************************
    // me.fncshow_src_viewname = function(line_no) {
    // var url = me.sys_id + "/" + me.id + "/" + "show_src_viewname";
    // var data = line_no;
    // me.ajax.receive = function(result) {
    // $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val("");
    // result = eval('(' + result + ')');
    // if (result['result'] == true) {
    // if (result['data'].length > 0) {
    // $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val(result['data'][0]['SRC_VIEWNAME']);
    // }
    // } else {
    // me.clsComFnc.FncMsgBox("E9999", result['data']);
    // return;
    // }
    // };
    // me.ajax.send(url, data, 0);
    // };

    //**********************************************************************
    //処 理 名：get update data
    //関 数 名：fncgetUpdData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：get update data
    //**********************************************************************
    me.fncgetUpdData = function () {
        me.updData = {};
        $("#FrmSimLineMstNew_LINE").jqGrid(
            "saveRow",
            me.lastsel,
            null,
            "clientArray"
        );
        $("#FrmSimLineMstNew_KAMOKU").jqGrid(
            "saveRow",
            me.lastsel1,
            null,
            "clientArray"
        );

        var selrowdata = $("#FrmSimLineMstNew_LINE").jqGrid(
            "getRowData",
            me.lastsel
        );
        var selLineNo = selrowdata["LINE_NO"];

        var lineArr = new Array();
        var kamokuArr = new Array();

        //get the left table's data.
        var lineIdArr = $("#FrmSimLineMstNew_LINE").jqGrid("getDataIDs");
        for (key1 in lineIdArr) {
            var lineTableData = $("#FrmSimLineMstNew_LINE").jqGrid(
                "getRowData",
                lineIdArr[key1]
            );
            if (
                lineTableData["LINE_NO"] != "" ||
                lineTableData["ITEM_NM"] != "" ||
                lineTableData["SRC_KB"] != "" ||
                lineTableData["RND_KB"] != "" ||
                lineTableData["RND_POS"] != "" ||
                lineTableData["CAL_KB"] != "" ||
                lineTableData["DISP_KB"] != ""
            ) {
                lineTableData["rownum"] = lineIdArr[key1];
                lineArr.push(lineTableData);
            }
        }

        if (
            $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").attr("disabled") ==
            "disabled"
        ) {
            //科目から集計一覧を使用可  科目以外から集計を使用不可  get the right table's data.
            var kamokuIdArr = $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                "getDataIDs"
            );
            for (key2 in kamokuIdArr) {
                var kamokuTableData = $("#FrmSimLineMstNew_KAMOKU").jqGrid(
                    "getRowData",
                    kamokuIdArr[key2]
                );
                if (
                    kamokuTableData["KAMOK_CD"] != "" ||
                    kamokuTableData["HIMOK_CD"] != "" ||
                    kamokuTableData["CAL_KB1"] != ""
                ) {
                    kamokuTableData["rownum"] = kamokuIdArr[key2];
                    kamokuArr.push(kamokuTableData);
                }
            }
            me.updData = {
                selLineNo: selLineNo,
                lineArr: lineArr,
                kamokuArr: kamokuArr,
            };
        } else {
            //科目から集計一覧を使用不可 科目以外から集計を使用可  get the input's data.
            me.updData = {
                selLineNo: selLineNo,
                lineArr: lineArr,
                kamokuOutside: $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").val(),
            };
        }
    };

    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fnccheck
    //引    数：無し
    //戻 り 値：true/false
    //処理説明：入力チェック
    //**********************************************************************
    me.fnccheck = function () {
        me.fncgetUpdData();
        var lineTableData = me.updData["lineArr"];
        //左側一覧
        //必須チェック  No,ライン名,集計元,計算区分
        for (key in lineTableData) {
            for (key1 in lineTableData[key]) {
                lineTableData[key][key1] = $.trim(lineTableData[key][key1]);
                me.updData["lineArr"][key][key1] = $.trim(
                    me.updData["lineArr"][key][key1]
                );

                switch (key1) {
                    case "LINE_NO":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[0]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[0]["label"]
                            );
                            return false;
                        }
                    case "ITEM_NM":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[1]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[1]["label"]
                            );
                            return false;
                        }
                    case "SRC_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[2]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            //1、2、3　以外はエラー
                            if (
                                lineTableData[key][key1] != 1 &&
                                lineTableData[key][key1] != 2 &&
                                lineTableData[key][key1] != 3
                            ) {
                                //me.isMakeErrFocus = true;
                                //focus the warning position and show warning message.
                                me.fncFocusErrPos(
                                    "#FrmSimLineMstNew_LINE",
                                    lineTableData[key]["rownum"],
                                    key1,
                                    -3,
                                    me.colModel[2]["label"]
                                );
                                return false;
                            }
                            break;
                        } else {
                            //me.isMakeErrFocus = true;
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[2]["label"]
                            );
                            return false;
                        }
                    case "RND_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[3]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[3]["label"]
                            );
                            return false;
                        }
                    case "RND_POS":
                        //---20150522 fanzhengzhou upd s.
                        if (lineTableData[key][key1] > 9) {
                            intRtn = -3;
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[4]["label"]
                            );
                            return false;
                        }
                        //---20150522 fanzhengzhou upd e.
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NUMBER3,
                            me.colModel[4]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[4]["label"]
                            );
                            return false;
                        }
                    case "CAL_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[5]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            //+,- 以外はエラー
                            if (
                                lineTableData[key][key1] != "+" &&
                                lineTableData[key][key1] != "-"
                            ) {
                                //focus the warning position and show warning message.
                                me.fncFocusErrPos(
                                    "#FrmSimLineMstNew_LINE",
                                    lineTableData[key]["rownum"],
                                    key1,
                                    -3,
                                    me.colModel[5]["label"]
                                );
                                return false;
                            }
                            //"＋"->null, "－"->-1
                            if (me.updData["lineArr"][key][key1] == "+") {
                                me.updData["lineArr"][key][key1] = 1;
                            } else if (
                                me.updData["lineArr"][key][key1] == "-"
                            ) {
                                me.updData["lineArr"][key][key1] = -1;
                            }
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[5]["label"]
                            );
                            return false;
                        }
                    case "DISP_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key][key1],
                            0,
                            me.clsComFnc.INPUTTYPE.NONE,
                            me.colModel[6]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            break;
                        } else {
                            //focus the warning position and show warning message.
                            me.fncFocusErrPos(
                                "#FrmSimLineMstNew_LINE",
                                lineTableData[key]["rownum"],
                                key1,
                                intRtn,
                                me.colModel[6]["label"]
                            );
                            return false;
                        }
                }
            }
        }

        //（科目以外から集計）
        if (me.updData.kamokuOutside != undefined) {
            var tempKamokuOutside = $.trim(me.updData.kamokuOutside);
            var intReturn = me.clsComFnc.FncSprCheck(
                tempKamokuOutside,
                0,
                me.clsComFnc.INPUTTYPE.NONE,
                40
            );
            if (intReturn == -3 || intReturn == -2) {
                $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").trigger("focus");
                me.clsComFnc.MessageBox(
                    "集計元ビュー名が正しくありません。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                return;
            }
        }

        //右側一覧
        if (
            $(".KRSS.FrmSimLineMstNew.KAMOKU_OUTSIDE").attr("disabled") ==
            "disabled"
        ) {
            //マスタ存在チェック
            var url = me.sys_id + "/" + me.id + "/" + "mastercheck";
            me.ajax.receive = function (result) {
                result = JSON.parse(result);
                if (result["result"] == true) {
                    masterArr = result["data"];

                    //科目から集計一覧を使用可  科目以外から集計を使用不可  get the right table's data.
                    var kamokuTableData = me.updData["kamokuArr"];

                    var flag = false;

                    for (key in kamokuTableData) {
                        for (key1 in kamokuTableData[key]) {
                            kamokuTableData[key][key1] = $.trim(
                                kamokuTableData[key][key1]
                            );
                            me.updData["kamokuArr"][key][key1] = $.trim(
                                me.updData["kamokuArr"][key][key1]
                            );

                            switch (key1) {
                                case "KAMOK_CD":
                                    intRtn = me.clsComFnc.FncSprCheck(
                                        kamokuTableData[key][key1],
                                        1,
                                        me.clsComFnc.INPUTTYPE.NONE,
                                        me.colModel1[0]["editoptions"][
                                            "maxlength"
                                        ]
                                    );
                                    if (intRtn == 0) {
                                        flag = false;
                                        for (key2 in masterArr) {
                                            if (
                                                masterArr[key2]["KAMOK_CD"] ==
                                                kamokuTableData[key][key1]
                                            ) {
                                                flag = true;
                                            }
                                        }
                                        if (flag == true) {
                                            break;
                                        } else {
                                            //focus the warning position and show warning message.
                                            me.fncFocusErrPos(
                                                "#FrmSimLineMstNew_KAMOKU",
                                                kamokuTableData[key]["rownum"],
                                                key1,
                                                -4,
                                                me.colModel1[0]["label"]
                                            );
                                            return false;
                                        }
                                    } else {
                                        //focus the warning position and show warning message.
                                        me.fncFocusErrPos(
                                            "#FrmSimLineMstNew_KAMOKU",
                                            kamokuTableData[key]["rownum"],
                                            key1,
                                            intRtn,
                                            me.colModel1[0]["label"]
                                        );
                                        return false;
                                    }
                                case "HIMOK_CD":
                                    if (kamokuTableData[key][key1] == "") {
                                        me.updData["kamokuArr"][key][key1] =
                                            " ";
                                        break;
                                    } else {
                                        intRtn = me.clsComFnc.FncSprCheck(
                                            kamokuTableData[key][key1],
                                            1,
                                            me.clsComFnc.INPUTTYPE.NONE,
                                            me.colModel1[1]["editoptions"][
                                                "maxlength"
                                            ]
                                        );
                                        if (intRtn == 0) {
                                            if (
                                                kamokuTableData[key][key1] ==
                                                "00"
                                            ) {
                                                break;
                                            }
                                            flag = false;
                                            for (key2 in masterArr) {
                                                if (
                                                    masterArr[key2][
                                                        "KAMOK_CD"
                                                    ] ==
                                                        kamokuTableData[key][
                                                            "KAMOK_CD"
                                                        ] &&
                                                    masterArr[key2][
                                                        "KOMOK_CD"
                                                    ] ==
                                                        kamokuTableData[key][
                                                            key1
                                                        ]
                                                ) {
                                                    flag = true;
                                                }
                                            }
                                            if (flag == true) {
                                                break;
                                            } else {
                                                //focus the warning position and show warning message.
                                                me.fncFocusErrPos(
                                                    "#FrmSimLineMstNew_KAMOKU",
                                                    kamokuTableData[key][
                                                        "rownum"
                                                    ],
                                                    key1,
                                                    -4,
                                                    me.colModel1[1]["label"]
                                                );

                                                return false;
                                            }
                                        } else {
                                            //focus the warning position and show warning message.
                                            me.fncFocusErrPos(
                                                "#FrmSimLineMstNew_KAMOKU",
                                                kamokuTableData[key]["rownum"],
                                                key1,
                                                intRtn,
                                                me.colModel1[1]["label"]
                                            );
                                            return false;
                                        }
                                    }
                                case "CAL_KB1":
                                    intRtn = me.clsComFnc.FncSprCheck(
                                        kamokuTableData[key][key1],
                                        1,
                                        me.clsComFnc.INPUTTYPE.NONE,
                                        me.colModel1[2]["editoptions"][
                                            "maxlength"
                                        ]
                                    );

                                    if (intRtn == 0) {
                                        //+,- 以外はエラー
                                        if (
                                            kamokuTableData[key][key1] != "+" &&
                                            kamokuTableData[key][key1] != "-"
                                        ) {
                                            //focus the warning position and show warning message.
                                            me.fncFocusErrPos(
                                                "#FrmSimLineMstNew_KAMOKU",
                                                kamokuTableData[key]["rownum"],
                                                key1,
                                                -3,
                                                me.colModel1[2]["label"]
                                            );
                                            return false;
                                        }
                                        //"＋"->null, "－"->-1
                                        if (
                                            me.updData["kamokuArr"][key][
                                                key1
                                            ] == "+"
                                        ) {
                                            me.updData["kamokuArr"][key][
                                                key1
                                            ] = 1;
                                        } else if (
                                            me.updData["kamokuArr"][key][
                                                key1
                                            ] == "-"
                                        ) {
                                            me.updData["kamokuArr"][key][key1] =
                                                -1;
                                        }
                                        break;
                                    } else {
                                        //focus the warning position and show warning message.
                                        me.fncFocusErrPos(
                                            "#FrmSimLineMstNew_KAMOKU",
                                            kamokuTableData[key]["rownum"],
                                            key1,
                                            intRtn,
                                            me.colModel1[2]["label"]
                                        );
                                        return false;
                                    }
                            }
                        }
                    }
                    me.fncupdate();
                    return true;
                }
            };
            me.ajax.send(url, "", 0);
        } else {
            me.fncupdate();
            return true;
        }
    };

    //**********************************************************************
    //処 理 名：focus error info's position
    //関 数 名：fncFocusErrPos
    //引    数1：id
    //引    数2：rownum
    //引    数3：colnum
    //引    数4：intRtn
    //引    数5：name
    //戻 り 値：無し
    //処理説明：focus error info's position
    //**********************************************************************
    me.fncFocusErrPos = function (id, rownum, colnum, intRtn, name) {
        //focus the warning position.
        $(id).jqGrid("setSelection", rownum, true);
        $(id).jqGrid("editRow", rownum, true);
        $("#" + rownum + "_" + colnum).trigger("focus");
        //error message.
        switch (intRtn) {
            //必須異常
            case -1:
                me.clsComFnc.MessageBox(
                    name + "を入力してください。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //入力異常
            case -2:
                me.clsComFnc.MessageBox(
                    name + "が正しくありません。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //桁数異常
            case -3:
                me.clsComFnc.MessageBox(
                    name + "が正しくありません。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //科目/費目コードが不正です
            case -4:
                me.clsComFnc.MessageBox(
                    name + "が不正です。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
        }
    };

    //**********************************************************************
    //処 理 名：更新
    //関 数 名：fncupdate
    //引    数：無し
    //戻 り 値：無し
    //処理説明：更新
    //**********************************************************************
    me.fncupdate = function () {
        var url = me.sys_id + "/" + me.id + "/" + "update";
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Close = me.FrmSimLineMstNew_Load;
                me.clsComFnc.FncMsgBox("I0008");
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Close = me.FrmSimLineMstNew_Load;
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, me.updData, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmSimLineMstNew = new KRSS.FrmSimLineMstNew();
    o_KRSS_FrmSimLineMstNew.load();
});
