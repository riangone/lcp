/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078    						BUG                              yin
 * 20151113           #2272    						仕様変更                           yin
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmReOutReportEdit");

R4.FrmReOutReportEdit = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmReOutReportEdit";
    me.sys_id = "R4K";
    me.lastsel = "";
    me.GridData = new Array();
    me.cboInpDate = "";
    //処理結果フラグ
    me.blnFlg = false;
    //ID
    me.strID = "";
    //入力年月日
    me.strInpDate = "";
    //処理フラグ
    me.strMenteFlg = "";
    //エラーNO
    me.strErrMsgNo = "";
    //エラーメッセージ
    me.strErrMsg = "";
    //エラーフラグ
    me.blnErrFlg = "";

    me.F9Flag = false;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.colModel = [
        {
            name: "KBN",
            label: "区分",
            index: "KBN",
            sortable: false,
            width: 45,
            editable: true,
            editoptions: {
                maxlength: "1",
                dataEvents: [
                    //---20151113 Yin INS S
                    {
                        type: "keyup",
                        fn: function (e) {
                            if (e.keyCode >= 65 && e.keyCode <= 90) {
                                $(e.target).val(this.value.toUpperCase());
                            }
                        },
                    },
                    //---20151113 Yin INS E
                ],
            },
        },
        {
            name: "NENSIKI",
            label: "年式",
            index: "NENSIKI",
            sortable: false,
            width: 45,
            editable: true,

            editoptions: {
                class: "numeric",
                maxlength: "2",
            },
        },
        {
            name: "SYADAIKATA",
            label: "型式",
            index: "SYADAIKATA",
            sortable: false,
            width: 100,
            editable: true,
            editoptions: {
                maxlength: "8",
                dataEvents: [
                    //---20151113 Yin INS S
                    {
                        type: "keyup",
                        fn: function (e) {
                            if (e.keyCode >= 65 && e.keyCode <= 90) {
                                $(e.target).val(this.value.toUpperCase());
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CAR_NO",
            label: "車台№",
            index: "CAR_NO",
            sortable: false,
            width: 130,
            editable: true,
            editoptions: {
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                var totalrow = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "records");
                                if (selIRow == totalrow + 1) {
                                    return false;
                                }
                                //8位右对齐.S
                                var CurrentValue = $(e.target).val();
                                if (me.clsComFnc.FncNv(CurrentValue) == "") {
                                    $(e.target).val("");
                                } else {
                                    $(e.target).val(
                                        me.clsComFnc.FncNv(
                                            CurrentValue.toString().padLeft(8)
                                        )
                                    );
                                }
                                //8位右对齐.E
                            }
                            if (key == 38) {
                                //8位右对齐.S
                                var CurrentValue = $(e.target).val();
                                if (me.clsComFnc.FncNv(CurrentValue) == "") {
                                    $(e.target).val("");
                                } else {
                                    $(e.target).val(
                                        me.clsComFnc.FncNv(
                                            CurrentValue.toString().padLeft(8)
                                        )
                                    );
                                }
                                //8位右对齐.E
                                return false;
                            }
                        },
                    },
                    {
                        type: "blur",
                        fn: function (e) {
                            var CurrentValue = $(e.target).val();
                            if (me.clsComFnc.FncNv(CurrentValue) == "") {
                                $(e.target).val("");
                            } else {
                                $(e.target).val(
                                    me.clsComFnc.FncNv(
                                        CurrentValue.toString().padLeft(8)
                                    )
                                );
                            }
                        },
                    },
                    //英数字のみを許可します。 ("-",",",".")
                    {
                        type: "keyup",
                        fn: function (e) {
                            if (
                                e.keyCode != 16 &&
                                e.keyCode != 8 &&
                                e.keyCode != 9 &&
                                e.keyCode != 46 &&
                                e.keyCode != 110 &&
                                e.keyCode != 190 &&
                                (e.keyCode < 35 || e.keyCode > 40)
                            ) {
                                if (me.GetByteCount1(this.value)) {
                                    this.value = this.value.replace(
                                        /[^\d\-\a-\z\A-\Z\ \,\.]/g,
                                        ""
                                    );
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUHIN_DAI",
            label: "部品",
            index: "BUHIN_DAI",
            sortable: false,
            width: 120,
            align: "right",

            editable: true,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "9",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                var totalrow = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "records");
                                if (selIRow == totalrow + 1) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;

                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblBuhinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_BUHIN_DAI").val() != ""
                                ) {
                                    lblBuhinGk += parseInt(
                                        $("#" + selRowId + "_BUHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["BUHIN_DAI"] != "") {
                                        lblBuhinGk += parseInt(
                                            tableData["BUHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblBuhinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(lblBuhinGk, "lblBuhinGk");
                                    $(".FrmReOutReportEdit.lblBuhinGk").html(
                                        lblBuhinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;
                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblBuhinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_BUHIN_DAI").val() != ""
                                ) {
                                    lblBuhinGk += parseInt(
                                        $("#" + selRowId + "_BUHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["BUHIN_DAI"] != "") {
                                        lblBuhinGk += parseInt(
                                            tableData["BUHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblBuhinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(lblBuhinGk, "lblBuhinGk");
                                    $(".FrmReOutReportEdit.lblBuhinGk").html(
                                        lblBuhinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                        },
                    },
                    {
                        type: "blur",
                        fn: function (e) {
                            var CurrentValue = $(e.target).val();
                            var rowId = $(e.target)
                                .closest("tr.jqgrow")
                                .attr("id");
                            var id_GAICHU = "#" + rowId + "_" + "GAICHU_DAI";
                            var id_KOUCHIN = "#" + rowId + "_" + "KOUCHIN_DAI";
                            var GOUKEI = 0;
                            if (CurrentValue != "") {
                                GOUKEI = GOUKEI + parseInt(CurrentValue);
                            }
                            if ($(id_GAICHU).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_GAICHU).val());
                            }
                            if ($(id_KOUCHIN).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_KOUCHIN).val());
                            }
                            GOUKEI = me.fncSqlNull(GOUKEI);
                            $("#FrmReOutReportEdit_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "GOUKEI",
                                GOUKEI
                            );
                            var lblBuhinGk = 0;
                            var tempmark = false;
                            if (CurrentValue != "") {
                                lblBuhinGk += parseInt(CurrentValue);
                                tempmark = true;
                            }
                            var IDs = $("#FrmReOutReportEdit_sprList").jqGrid(
                                "getDataIDs"
                            );
                            for (key in IDs) {
                                var tableData = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getRowData", IDs[key]);
                                if (tableData["BUHIN_DAI"] != "") {
                                    lblBuhinGk += parseInt(
                                        tableData["BUHIN_DAI"]
                                    );
                                    tempmark = true;
                                }
                            }
                            //当日合計
                            if (tempmark == false) {
                                $(".FrmReOutReportEdit.lblBuhinGk").html("");
                            } else {
                                //change red,when<0.
                                me.changeColor(lblBuhinGk, "lblBuhinGk");
                                $(".FrmReOutReportEdit.lblBuhinGk").html(
                                    lblBuhinGk.toString().numFormat()
                                );
                            }
                            var lblSouGoukei = 0;
                            if (
                                $(".FrmReOutReportEdit.lblBuhinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblBuhinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblGaichuGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblGaichuGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblKouchinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblKouchinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            //change red,when<0.
                            me.changeColor(lblSouGoukei, "lblSouGoukei");
                            $(".FrmReOutReportEdit.lblSouGoukei").html(
                                lblSouGoukei.toString().numFormat()
                            );
                        },
                    },
                    //'keypress' and 'keyup' event for '-'.when set the Column numeric,input '-',show '-0'.In the same time,set .numeric()'s negative true.
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 8, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GAICHU_DAI",
            label: "外注",
            index: "GAICHU_DAI",
            sortable: false,
            width: 120,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "9",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                var totalrow = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "records");
                                if (selIRow == totalrow + 1) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;
                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblGaichuGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_GAICHU_DAI").val() !=
                                    ""
                                ) {
                                    lblGaichuGk += parseInt(
                                        $("#" + selRowId + "_GAICHU_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["GAICHU_DAI"] != "") {
                                        lblGaichuGk += parseInt(
                                            tableData["GAICHU_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblGaichuGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(lblGaichuGk, "lblGaichuGk");
                                    $(".FrmReOutReportEdit.lblGaichuGk").html(
                                        lblGaichuGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;
                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblGaichuGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_GAICHU_DAI").val() !=
                                    ""
                                ) {
                                    lblGaichuGk += parseInt(
                                        $("#" + selRowId + "_GAICHU_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["GAICHU_DAI"] != "") {
                                        lblGaichuGk += parseInt(
                                            tableData["GAICHU_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblGaichuGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(lblGaichuGk, "lblGaichuGk");
                                    $(".FrmReOutReportEdit.lblGaichuGk").html(
                                        lblGaichuGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                        },
                    },
                    {
                        type: "blur",
                        fn: function (e) {
                            var CurrentValue = $(e.target).val();
                            var rowId = $(e.target)
                                .closest("tr.jqgrow")
                                .attr("id");
                            var id_BUHIN = "#" + rowId + "_" + "BUHIN_DAI";
                            var id_KOUCHIN = "#" + rowId + "_" + "KOUCHIN_DAI";
                            var GOUKEI = 0;
                            if (CurrentValue != "") {
                                GOUKEI = GOUKEI + parseInt(CurrentValue);
                            }
                            if ($(id_BUHIN).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_BUHIN).val());
                            }
                            if ($(id_KOUCHIN).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_KOUCHIN).val());
                            }
                            GOUKEI = me.fncSqlNull(GOUKEI);
                            $("#FrmReOutReportEdit_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "GOUKEI",
                                GOUKEI
                            );

                            var lblGaichuGk = 0;
                            var tempmark = false;
                            if (CurrentValue != "") {
                                lblGaichuGk += parseInt(CurrentValue);
                                tempmark = true;
                            }
                            var IDs = $("#FrmReOutReportEdit_sprList").jqGrid(
                                "getDataIDs"
                            );
                            for (key in IDs) {
                                var tableData = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getRowData", IDs[key]);
                                if (tableData["GAICHU_DAI"] != "") {
                                    lblGaichuGk += parseInt(
                                        tableData["GAICHU_DAI"]
                                    );
                                    tempmark = true;
                                }
                            }
                            //当日合計
                            if (tempmark === false) {
                                $(".FrmReOutReportEdit.lblGaichuGk").html("");
                            } else {
                                //change red,when<0.
                                me.changeColor(lblGaichuGk, "lblGaichuGk");
                                $(".FrmReOutReportEdit.lblGaichuGk").html(
                                    lblGaichuGk.toString().numFormat()
                                );
                            }
                            var lblSouGoukei = 0;
                            if (
                                $(".FrmReOutReportEdit.lblBuhinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblBuhinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblGaichuGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblGaichuGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblKouchinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblKouchinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            //change red,when<0.
                            me.changeColor(lblSouGoukei, "lblSouGoukei");
                            $(".FrmReOutReportEdit.lblSouGoukei").html(
                                lblSouGoukei.toString().numFormat()
                            );
                        },
                    },
                    //'keypress' and 'keyup' event for '-'.when set the Column numeric,input '-',show '-0'.In the same time,set .numeric()'s negative true.
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 8, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "KOUCHIN_DAI",
            label: "工賃",
            index: "KOUCHIN_DAI",
            sortable: false,
            width: 120,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "9",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                return false;
                            }
                            //2015/08/19 yinhuaiyu add start
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                var LastSelRowData = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getRowData", LastRow);
                                var GOUKEI = 0;
                                if (LastSelRowData["BUHIN_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["BUHIN_DAI"]);
                                }
                                if (LastSelRowData["GAICHU_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["GAICHU_DAI"]);
                                }
                                if (LastSelRowData["KOUCHIN_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["KOUCHIN_DAI"]);
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblKouchinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    lblKouchinGk += parseInt(
                                        $("#" + selRowId + "_KOUCHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["KOUCHIN_DAI"] != "") {
                                        lblKouchinGk += parseInt(
                                            tableData["KOUCHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(
                                        lblKouchinGk,
                                        "lblKouchinGk"
                                    );
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        lblKouchinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                            //2015/08/19 yinhuaiyu add end
                            if (key == 13 || key == 9) {
                                //enter and tab
                                var LastRow = me.lastsel;
                                var totalrow = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "records");
                                if (parseInt(me.lastsel) + 1 > totalrow) {
                                    //最后一格，ENTER，判断前面若有非空的，行+1.
                                    if (
                                        key == 13 &&
                                        (me.fncSqlNull(
                                            $("#" + LastRow + "_KBN").val()
                                        ) != null ||
                                            me.fncSqlNull(
                                                $(
                                                    "#" + LastRow + "_NENSIKI"
                                                ).val()
                                            ) != null ||
                                            me.fncSqlNull(
                                                $(
                                                    "#" +
                                                        LastRow +
                                                        "_SYADAIKATA"
                                                ).val()
                                            ) != null ||
                                            me.fncSqlNull(
                                                $(
                                                    "#" + LastRow + "_CAR_NO"
                                                ).val()
                                            ) != null ||
                                            me.fncSqlNull(
                                                $(
                                                    "#" + LastRow + "_BUHIN_DAI"
                                                ).val()
                                            ) != null ||
                                            me.fncSqlNull(
                                                $(
                                                    "#" +
                                                        LastRow +
                                                        "_GAICHU_DAI"
                                                ).val()
                                            ) != null)
                                    ) {
                                        $("#FrmReOutReportEdit_sprList").jqGrid(
                                            "addRowData",
                                            parseInt(LastRow) + 1,
                                            me.columns
                                        );
                                    } else {
                                        return false;
                                    }
                                }
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setSelection",
                                    parseInt(me.lastsel) + 1,
                                    true
                                );
                                $("#" + me.lastsel + "_KBN").select();
                                var LastSelRowData = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getRowData", LastRow);
                                var GOUKEI = 0;
                                if (LastSelRowData["BUHIN_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["BUHIN_DAI"]);
                                }
                                if (LastSelRowData["GAICHU_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["GAICHU_DAI"]);
                                }
                                if (LastSelRowData["KOUCHIN_DAI"] != "") {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(LastSelRowData["KOUCHIN_DAI"]);
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblKouchinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    lblKouchinGk += parseInt(
                                        $("#" + selRowId + "_KOUCHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["KOUCHIN_DAI"] != "") {
                                        lblKouchinGk += parseInt(
                                            tableData["KOUCHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(
                                        lblKouchinGk,
                                        "lblKouchinGk"
                                    );
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        lblKouchinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                var totalrow = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "records");
                                if (selIRow == totalrow + 1) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;
                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblKouchinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    lblKouchinGk += parseInt(
                                        $("#" + selRowId + "_KOUCHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["KOUCHIN_DAI"] != "") {
                                        lblKouchinGk += parseInt(
                                            tableData["KOUCHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(
                                        lblKouchinGk,
                                        "lblKouchinGk"
                                    );
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        lblKouchinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }
                                var LastRow = me.lastsel;
                                var GOUKEI = 0;
                                if (
                                    $("#" + LastRow + "_BUHIN_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_BUHIN_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_GAICHU_DAI").val() != ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_GAICHU_DAI"
                                            ).val()
                                        );
                                }
                                if (
                                    $("#" + LastRow + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    GOUKEI =
                                        GOUKEI +
                                        parseInt(
                                            $(
                                                "#" + LastRow + "_KOUCHIN_DAI"
                                            ).val()
                                        );
                                }
                                GOUKEI = me.fncSqlNull(GOUKEI);
                                $("#FrmReOutReportEdit_sprList").jqGrid(
                                    "setCell",
                                    LastRow,
                                    "GOUKEI",
                                    GOUKEI
                                );

                                var lblKouchinGk = 0;
                                var tmpmark = false;
                                var selRowId = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    $("#" + selRowId + "_KOUCHIN_DAI").val() !=
                                    ""
                                ) {
                                    lblKouchinGk += parseInt(
                                        $("#" + selRowId + "_KOUCHIN_DAI").val()
                                    );
                                    tmpmark = true;
                                }
                                var IDs = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getDataIDs");
                                for (key in IDs) {
                                    var tableData = $(
                                        "#FrmReOutReportEdit_sprList"
                                    ).jqGrid("getRowData", IDs[key]);
                                    if (tableData["KOUCHIN_DAI"] != "") {
                                        lblKouchinGk += parseInt(
                                            tableData["KOUCHIN_DAI"]
                                        );
                                        tmpmark = true;
                                    }
                                }
                                //当日合計
                                if (tmpmark == false) {
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        ""
                                    );
                                } else {
                                    //change red,when<0.
                                    me.changeColor(
                                        lblKouchinGk,
                                        "lblKouchinGk"
                                    );
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        lblKouchinGk.toString().numFormat()
                                    );
                                }
                                var lblSouGoukei = 0;
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblBuhinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblBuhinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblGaichuGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblGaichuGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                if (
                                    $(
                                        ".FrmReOutReportEdit.lblKouchinGk"
                                    ).html() !== ""
                                ) {
                                    lblSouGoukei += parseInt(
                                        $(".FrmReOutReportEdit.lblKouchinGk")
                                            .html()
                                            .replace(/,/g, "")
                                    );
                                }
                                //change red,when<0.
                                me.changeColor(lblSouGoukei, "lblSouGoukei");
                                $(".FrmReOutReportEdit.lblSouGoukei").html(
                                    lblSouGoukei.toString().numFormat()
                                );
                                return false;
                            }
                        },
                    },
                    {
                        type: "blur",
                        fn: function (e) {
                            var CurrentValue = $(e.target).val();
                            var rowId = $(e.target)
                                .closest("tr.jqgrow")
                                .attr("id");
                            var id_GAICHU = "#" + rowId + "_" + "GAICHU_DAI";
                            var id_BUHIN = "#" + rowId + "_" + "BUHIN_DAI";
                            var GOUKEI = 0;
                            if (CurrentValue != "") {
                                GOUKEI = GOUKEI + parseInt(CurrentValue);
                            }
                            if ($(id_GAICHU).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_GAICHU).val());
                            }
                            if ($(id_BUHIN).val() != "") {
                                GOUKEI = GOUKEI + parseInt($(id_BUHIN).val());
                            }
                            GOUKEI = me.fncSqlNull(GOUKEI);
                            $("#FrmReOutReportEdit_sprList").jqGrid(
                                "setCell",
                                rowId,
                                "GOUKEI",
                                GOUKEI
                            );

                            var lblKouchinGk = 0;
                            var tempmark = false;
                            if (CurrentValue != "") {
                                lblKouchinGk += parseInt(CurrentValue);
                                tempmark = true;
                            }
                            var IDs = $("#FrmReOutReportEdit_sprList").jqGrid(
                                "getDataIDs"
                            );
                            for (key in IDs) {
                                var tableData = $(
                                    "#FrmReOutReportEdit_sprList"
                                ).jqGrid("getRowData", IDs[key]);
                                if (tableData["KOUCHIN_DAI"] != "") {
                                    lblKouchinGk += parseInt(
                                        tableData["KOUCHIN_DAI"]
                                    );
                                    tempmark = true;
                                }
                            }
                            //当日合計
                            if (tempmark == false) {
                                $(".FrmReOutReportEdit.lblKouchinGk").html("");
                            } else {
                                //change red,when<0.
                                me.changeColor(lblKouchinGk, "lblKouchinGk");
                                $(".FrmReOutReportEdit.lblKouchinGk").html(
                                    lblKouchinGk.toString().numFormat()
                                );
                            }
                            var lblSouGoukei = 0;
                            if (
                                $(".FrmReOutReportEdit.lblBuhinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblBuhinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblGaichuGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblGaichuGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            if (
                                $(".FrmReOutReportEdit.lblKouchinGk").html() !==
                                ""
                            ) {
                                lblSouGoukei += parseInt(
                                    $(".FrmReOutReportEdit.lblKouchinGk")
                                        .html()
                                        .replace(/,/g, "")
                                );
                            }
                            //change red,when<0.
                            me.changeColor(lblSouGoukei, "lblSouGoukei");

                            $(".FrmReOutReportEdit.lblSouGoukei").html(
                                lblSouGoukei.toString().numFormat()
                            );
                        },
                    },
                    //'keypress' and 'keyup' event for '-'.when set the Column numeric,input '-',show '-0'.In the same time,set .numeric()'s negative true.
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 8, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "GOUKEI",
            label: "合計",
            index: "GOUKEI",
            sortable: false,
            width: 120,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            sortable: false,
            hidden: true,
        },
        {
            name: "CHECK",
            label: "check",
            index: "CHECK",
            sortable: false,
            hidden: true,
        },
    ];
    me.columns = {
        KBN: "",
        NENSIKI: "",
        SYADAIKATA: "",
        CAR_NO: "",
        BUHIN_DAI: "",
        GAICHU_DAI: "",
        KOUCHIN_DAI: "",
        GOUKEI: "",
        CREATE_DATE: "",
    };
    me.controls.push({
        id: ".FrmReOutReportEdit.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmReOutReportEdit.cboInpDate",
        type: "datepicker",
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
        me.strMenteFlg = me.FrmReOutReport.PrpMenteFlg;
        me.strInpDate = me.FrmReOutReport.INP_DATE;
        me.frmMM_IGRP_E_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmReOutReportEdit.cboInpDate").on("blur", function () {
        if (
            me.clsComFnc.CheckDate($(".FrmReOutReportEdit.cboInpDate")) == false
        ) {
            $(".FrmReOutReportEdit.cboInpDate").val(me.cboInpDate);
            $(".FrmReOutReportEdit.cboInpDate").trigger("focus");
            $(".FrmReOutReportEdit.cboInpDate").select();
            $(".FrmReOutReportEdit.cmdSearch").button("disable");
            return;
        } else {
            $(".FrmReOutReportEdit.cmdUpdate").button("enable");
        }
    });

    $("#FrmReOutReportEdit_sprList").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: 260,
        colModel: me.colModel,
        rownumbers: true,
        onSelectRow: function (rowId, _status, e) {
            if (typeof e != "undefined") {
                //編集可能なセルをクリック、上下キー
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                if (cellIndex != 0) {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmReOutReportEdit_sprList").jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowId;
                    }
                    $("#FrmReOutReportEdit_sprList").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: cellIndex,
                    });
                } else {
                    //削除確認メッセージを表示する
                    $("#FrmReOutReportEdit_sprList").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.MessageBox(
                        "削除します。よろしいですか？",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowId && rowId !== me.lastsel) {
                    $("#FrmReOutReportEdit_sprList").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowId;
                }
                $("#FrmReOutReportEdit_sprList").jqGrid("editRow", rowId, {
                    keys: true,
                    focusField: false,
                });
            }
            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });
            //键盘事件
            gdmz.common.jqgrid.setKeybordEvents(
                "#FrmReOutReportEdit_sprList",
                e,
                me.lastsel
            );
        },
    });
    $("#FrmReOutReportEdit_sprList").jqGrid("setSelection", 0, true);
    $("#FrmReOutReportEdit_sprList").jqGrid("bindKeys");
    $("#FrmReOutReportEdit_sprList").jqGrid("setGroupHeaders", {
        useColSpanStyle: true,
        groupHeaders: [
            {
                startColumnName: "BUHIN_DAI",
                numberOfColumns: 4,
                titleText: "（出庫・見積）",
            },
        ],
    });
    $("#FrmReOutReportEdit_sprList").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });

    $(".FrmReOutReportEdit.cmdUpdate").click(function () {
        me.cmdAction_Click();
    });

    //F9キー＝登録ボタン
    shortcut.add("F9", function () {
        var situ = $(".HMS_F9").dialog("isOpen");
        if (situ == true) {
            return;
        }
        if (me.F9Flag == false) {
            me.cmdAction_Click();
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmMM_IGRP_E_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：各種初期値設定
    //**********************************************************************
    me.frmMM_IGRP_E_Load = function () {
        //初期処理
        var blnErrFlg2 = false;
        //画面項目ｸﾘｱ
        me.subClearForm();
        var url = me.sys_id + "/" + me.id + "/" + "select";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    $(".FrmReOutReportEdit.cboInpDate").val(
                        me.clsComFnc.FncNv(result["data"][0]["TOUGETU"])
                    );
                    me.cboInpDate = me.clsComFnc.FncNv(
                        result["data"][0]["TOUGETU"]
                    );
                    if (me.strMenteFlg == "INS") {
                        //添加一行
                        $("#FrmReOutReportEdit_sprList").jqGrid(
                            "addRowData",
                            1,
                            me.columns
                        );
                    }
                    if (me.strMenteFlg == "UPD") {
                        //'表示初期値設定
                        //完了日
                        $(".FrmReOutReportEdit.cboInpDate").val(me.strInpDate);
                        var url =
                            me.sys_id +
                            "/" +
                            me.id +
                            "/" +
                            "fncSaiseiSyukkoSet";
                        var data = me.strInpDate;
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (result["result"] == true) {
                                //該当なし
                                if (result["data"].length == 0) {
                                    //該当するデータは存在しません。
                                    me.clsComFnc.FncMsgBox("I0001");
                                    return;
                                } else {
                                    var lblBuhinGk = 0;
                                    var lblGaichuGk = 0;
                                    var lblKouchinGk = 0;
                                    //ｽﾌﾟﾚｯﾄﾞに該当データを表示
                                    for (key in result["data"]) {
                                        var columns = {
                                            KBN: result["data"][key]["KBN"],
                                            NENSIKI:
                                                result["data"][key]["NENSIKI"],
                                            SYADAIKATA:
                                                result["data"][key][
                                                    "SYADAIKATA"
                                                ],
                                            CAR_NO: result["data"][key][
                                                "CAR_NO"
                                            ],
                                            BUHIN_DAI:
                                                result["data"][key][
                                                    "BUHIN_DAI"
                                                ],
                                            GAICHU_DAI:
                                                result["data"][key][
                                                    "GAICHU_DAI"
                                                ],
                                            KOUCHIN_DAI:
                                                result["data"][key][
                                                    "KOUCHIN_DAI"
                                                ],
                                            GOUKEI: result["data"][key][
                                                "GOUKEI"
                                            ],
                                            CREATE_DATE:
                                                result["data"][key][
                                                    "CREATE_DATE"
                                                ],
                                        };
                                        $("#FrmReOutReportEdit_sprList").jqGrid(
                                            "addRowData",
                                            parseInt(key) + 1,
                                            columns
                                        );
                                        lblBuhinGk += parseInt(
                                            me.clsComFnc.FncNz(
                                                result["data"][key]["BUHIN_DAI"]
                                            )
                                        );
                                        lblGaichuGk += parseInt(
                                            me.clsComFnc.FncNz(
                                                result["data"][key][
                                                    "GAICHU_DAI"
                                                ]
                                            )
                                        );
                                        lblKouchinGk += parseInt(
                                            me.clsComFnc.FncNz(
                                                result["data"][key][
                                                    "KOUCHIN_DAI"
                                                ]
                                            )
                                        );
                                    }
                                    //添加一行
                                    $("#FrmReOutReportEdit_sprList").jqGrid(
                                        "addRowData",
                                        parseInt(key) + 2,
                                        me.columns
                                    );
                                    $("#FrmReOutReportEdit_sprList").jqGrid(
                                        "setSelection",
                                        1,
                                        true
                                    );

                                    //当日合計
                                    me.changeColor(lblBuhinGk, "lblBuhinGk");
                                    $(".FrmReOutReportEdit.lblBuhinGk").html(
                                        lblBuhinGk.toString().numFormat()
                                    );
                                    me.changeColor(lblGaichuGk, "lblGaichuGk");
                                    $(".FrmReOutReportEdit.lblGaichuGk").html(
                                        lblGaichuGk.toString().numFormat()
                                    );
                                    me.changeColor(
                                        lblKouchinGk,
                                        "lblKouchinGk"
                                    );
                                    $(".FrmReOutReportEdit.lblKouchinGk").html(
                                        lblKouchinGk.toString().numFormat()
                                    );
                                    me.changeColor(
                                        parseInt(lblKouchinGk) +
                                            parseInt(lblGaichuGk) +
                                            parseInt(lblBuhinGk),
                                        "lblSouGoukei"
                                    );
                                    $(".FrmReOutReportEdit.lblSouGoukei").html(
                                        (
                                            parseInt(lblKouchinGk) +
                                            parseInt(lblGaichuGk) +
                                            parseInt(lblBuhinGk)
                                        )
                                            .toString()
                                            .numFormat()
                                    );
                                }
                            } else {
                                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }
                        };
                        me.ajax.send(url, data, 0);
                    }
                } else {
                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //ﾌｫｰｶｽ設定
            window.onload = function () {
                document
                    .querySelector(".FrmReOutReportEdit.cboInpDate")
                    .trigger("focus");
            };
            //正常終了
            blnErrFlg2 = true;
            if (blnErrFlg2 == true) {
                $("#FrmReOutReportEdit").dialog(
                    "option",
                    "title",
                    "再生出庫報告書"
                );
                $("#FrmReOutReportEdit").dialog("open");
            }
        };
        me.ajax.send(url, "", 0);
    };

    //行削除を行う
    me.delRowData = function () {
        var rowId = $("#FrmReOutReportEdit_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        me.delRowDataContent(rowId);
        if (
            $("#FrmReOutReportEdit_sprList").jqGrid(
                "getGridParam",
                "records"
            ) == 0
        ) {
            $("#FrmReOutReportEdit_sprList").jqGrid(
                "addRowData",
                1,
                me.columns
            );
        }

        var lblBuhinGk = 0;
        var lblGaichuGk = 0;
        var lblKouchinGk = 0;
        var IDs = $("#FrmReOutReportEdit_sprList").jqGrid("getDataIDs");
        for (key in IDs) {
            var tableData = $("#FrmReOutReportEdit_sprList").jqGrid(
                "getRowData",
                IDs[key]
            );
            if (tableData["BUHIN_DAI"] != "") {
                lblBuhinGk += parseInt(tableData["BUHIN_DAI"]);
            }
            if (tableData["GAICHU_DAI"] != "") {
                lblGaichuGk += parseInt(tableData["GAICHU_DAI"]);
            }
            if (tableData["KOUCHIN_DAI"] != "") {
                lblKouchinGk += parseInt(tableData["KOUCHIN_DAI"]);
            }
        }
        //当日合計
        me.changeColor(lblBuhinGk, "lblBuhinGk");
        $(".FrmReOutReportEdit.lblBuhinGk").html(
            lblBuhinGk.toString().numFormat()
        );
        me.changeColor(lblGaichuGk, "lblGaichuGk");
        $(".FrmReOutReportEdit.lblGaichuGk").html(
            lblGaichuGk.toString().numFormat()
        );
        me.changeColor(lblKouchinGk, "lblKouchinGk");
        $(".FrmReOutReportEdit.lblKouchinGk").html(
            lblKouchinGk.toString().numFormat()
        );
        me.changeColor(
            parseInt(lblBuhinGk) +
                parseInt(lblGaichuGk) +
                parseInt(lblKouchinGk),
            "lblSouGoukei"
        );
        $(".FrmReOutReportEdit.lblSouGoukei").html(
            (
                parseInt(lblBuhinGk) +
                parseInt(lblGaichuGk) +
                parseInt(lblKouchinGk)
            )
                .toString()
                .numFormat()
        );
    };
    //when delete one row,the rows below'id will has mistakes.To make the ids right,do below.
    me.delRowDataContent = function (rowID) {
        var getDataID = $("#FrmReOutReportEdit_sprList").jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length; i++) {
            var rowData = $("#FrmReOutReportEdit_sprList").jqGrid(
                "getRowData",
                i + 1
            );
            $("#FrmReOutReportEdit_sprList").jqGrid("setRowData", i, rowData);
        }
        $("#FrmReOutReportEdit_sprList").jqGrid("delRowData", getDataID.length);
    };

    //get data from jqGrid.
    me.GetData = function () {
        var arr = new Array();
        var data = $("#FrmReOutReportEdit_sprList").jqGrid("getDataIDs");
        for (key in data) {
            var tableData = $("#FrmReOutReportEdit_sprList").jqGrid(
                "getRowData",
                data[key]
            );
            arr.push(tableData);
        }
        return arr;
    };

    //**********************************************************************
    //処 理 名：DBに内容を登録、修正、削除
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：パラメータがINS(追加)の場合は入力チェック、存在チェックの後、DBに登録
    //  　　　            UPD(修正)の場合は名称の入力チェック後、DBを修正
    //　　　　             DEL(削除)の場合はDBから削除
    //**********************************************************************
    me.cmdAction_Click = function () {
        $("#FrmReOutReportEdit_sprList").jqGrid(
            "saveRow",
            me.lastsel,
            null,
            "clientArray"
        );
        //入力ﾁｪｯｸ
        if (me.fncInputSprChk() == false) {
            return;
        }
        me.F9Flag = true;
        //存在チェック
        var url = me.sys_id + "/" + me.id + "/" + "fncExistsCheck";
        var data = $(".FrmReOutReportEdit.cboInpDate").val();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.DelInsert;
                    me.clsComFnc.FncMsgBox(
                        "QY999",
                        "既に該当データは登録されています。上書きしてもよろしいですか？"
                    );
                } else {
                    me.DelInsert();
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //delete data,then insert data.
    me.DelInsert = function () {
        var url = me.sys_id + "/" + me.id + "/" + "DelInsert";
        var data = {
            GridData: me.GridData,
            strInpDate: $(".FrmReOutReportEdit.cboInpDate").val(),
        };
        me.ajax.receive = function (result) {
            me.F9Flag = false;
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //後処理
                switch (me.strMenteFlg) {
                    case "INS":
                        me.blnFlg = true;
                        me.subClearForm();
                        $("#FrmReOutReportEdit_sprList").jqGrid(
                            "addRowData",
                            1,
                            me.columns
                        );
                        $(".FrmReOutReportEdit.cboInpDate").trigger("focus");
                        break;
                    case "UPD":
                        me.blnFlg = true;
                        $("#FrmReOutReportEdit").dialog("close");
                        break;
                }
            } else {
                if (result["data"] == "E0007") {
                    me.clsComFnc.FncMsgBox("E0007");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：画面のクリア
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面のクリア
    //**********************************************************************
    me.subClearForm = function () {
        $(".FrmReOutReportEdit.lblBuhinGk").html("");
        $(".FrmReOutReportEdit.lblGaichuGk").html("");
        $(".FrmReOutReportEdit.lblKouchinGk").html("");
        $(".FrmReOutReportEdit.lblSouGoukei").html("");
        $("#FrmReOutReportEdit_sprList").jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：スプレッドの入力チェック
    //関 数 名：fncInputSprChk
    //引    数：無し
    //戻 り 値：True:正常終了 False:異常終了
    //処理説明：スプレッドの入力チェック
    //**********************************************************************
    me.fncInputSprChk = function () {
        var intRtn = 0;
        var blnInputFlg = false;
        var txt = "";
        var num = "";
        var colindex = "";
        var colname = "";
        me.GridData = me.GetData();
        for (i = 0; i < me.GridData.length; i++) {
            me.GridData[i]["CHECK"] = "0";
            //どれか一列でも入力されていた場合
            if (
                me.GridData[i]["KBN"].trimEnd() !== "" ||
                me.GridData[i]["NENSIKI"].trimEnd() !== "" ||
                me.GridData[i]["SYADAIKATA"].trimEnd() !== "" ||
                me.GridData[i]["CAR_NO"].trimEnd() !== "" ||
                (me.clsComFnc.FncNz(me.GridData[i]["BUHIN_DAI"].trimEnd()) !==
                    0 &&
                    me.clsComFnc.FncNz(
                        me.GridData[i]["BUHIN_DAI"].trimEnd()
                    ) !== "0") ||
                (me.clsComFnc.FncNz(me.GridData[i]["GAICHU_DAI"].trimEnd()) !==
                    0 &&
                    me.clsComFnc.FncNz(
                        me.GridData[i]["GAICHU_DAI"].trimEnd()
                    ) !== "0") ||
                (me.clsComFnc.FncNz(me.GridData[i]["KOUCHIN_DAI"].trimEnd()) !==
                    0 &&
                    me.clsComFnc.FncNz(
                        me.GridData[i]["KOUCHIN_DAI"].trimEnd()
                    ) !== "0")
            ) {
                //入力チェック
                for (key in me.GridData[i]) {
                    switch (key) {
                        case "KBN":
                            colindex = 0;
                            colname = "KBN";
                            txt = me.colModel[0]["editoptions"]["maxlength"];
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["KBN"],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                txt
                            );
                            break;
                        case "NENSIKI":
                            colindex = 1;
                            colname = "NENSIKI";
                            txt = me.colModel[1]["editoptions"]["maxlength"];
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["NENSIKI"],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                txt
                            );
                            break;
                        case "SYADAIKATA":
                            colindex = 2;
                            colname = "SYADAIKATA";
                            txt = me.colModel[2]["editoptions"]["maxlength"];
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["SYADAIKATA"],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                txt
                            );
                            break;
                        case "CAR_NO":
                            colindex = 3;
                            colname = "CAR_NO";
                            txt = me.colModel[3]["editoptions"]["maxlength"];
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["CAR_NO"],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                txt
                            );
                            break;
                        case "BUHIN_DAI":
                            colindex = 4;
                            colname = "BUHIN_DAI";
                            num =
                                me.colModel[4]["editoptions"]["maxlength"] - 3;
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["BUHIN_DAI"],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                num
                            );
                            break;
                        case "GAICHU_DAI":
                            colindex = 5;
                            colname = "GAICHU_DAI";
                            num =
                                me.colModel[5]["editoptions"]["maxlength"] - 3;
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["GAICHU_DAI"],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                num
                            );
                            break;
                        case "KOUCHIN_DAI":
                            colindex = 6;
                            colname = "KOUCHIN_DAI";
                            num =
                                me.colModel[6]["editoptions"]["maxlength"] - 3;
                            intRtn = me.clsComFnc.FncSprCheck(
                                me.GridData[i]["KOUCHIN_DAI"],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                num
                            );
                            break;
                        default:
                            break;
                    }
                    switch (intRtn) {
                        case 0:
                            break;
                        default:
                            //focus the error Cell.
                            $("#FrmReOutReportEdit_sprList").jqGrid(
                                "setSelection",
                                i + 1,
                                true
                            );
                            $("#FrmReOutReportEdit_sprList").jqGrid(
                                "editRow",
                                i + 1,
                                true
                            );
                            me.clsComFnc.ObjFocus = $(
                                "#" + (i + 1) + "_" + colname
                            );
                            me.clsComFnc.ObjSelect = $(
                                "#" + (i + 1) + "_" + colname
                            );

                            me.clsComFnc.FncMsgBox(
                                "W000" + (intRtn * -1).toString(),
                                me.colModel[colindex]["label"]
                            );
                            return false;
                    }
                }
                //入力されている場合はフラグに"1"をセット
                me.GridData[i]["CHECK"] = "1";
                blnInputFlg = true;
            }
        }
        if (blnInputFlg == false) {
            //focus the error Cell.
            $("#FrmReOutReportEdit_sprList").jqGrid("setSelection", 1, true);
            $("#FrmReOutReportEdit_sprList").jqGrid("editRow", 1, true);
            me.clsComFnc.ObjFocus = $("#1_KBN");
            me.clsComFnc.ObjSelect = $("#1_KBN");
            me.clsComFnc.FncMsgBox("W0017", "再生出庫データ");
            return false;
        }

        //正常終了
        return true;
    };

    //**********************************************************************
    //処 理 名：fncSqlNull
    //関 数 名：fncSqlNull
    //引    数：vstrWk     (I)文字列
    //戻 り 値：String
    //処理説明：DB登録項目を編集する
    //**********************************************************************
    me.fncSqlNull = function (vstrWk) {
        if ($.trim(vstrWk) == "" || vstrWk == "0") {
            return null;
        } else {
            return vstrWk;
        }
    };

    //when numeric,and allow "-".Make"-"=>"-0"
    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substr(0, 1) + (keycode - 48).toString();
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
                $(targetVal).val(inputValue.substr(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substr(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substr(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    //英数字のみを許可します。
    me.GetByteCount1 = function (str) {
        var uFF61 = parseInt("FF61", 16);
        var uFF9F = parseInt("FF9F", 16);
        var uFFE8 = parseInt("FFE8", 16);
        var uFFEE = parseInt("FFEE", 16);
        var flagCheck = true;
        if (str != null) {
            for (var i = 0; i < str.length; i++) {
                var c = parseInt(str.charCodeAt(i));
                if (c < 256) {
                    flagCheck = true;
                } else {
                    if (uFF61 <= c && c <= uFF9F) {
                        flagCheck = true;
                    } else if (uFFE8 <= c && c <= uFFEE) {
                        flagCheck = true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return flagCheck;
    };
    //when <0,make the fontcolor red.S
    me.changeColor = function (lblVal, lblName) {
        if (lblVal < 0) {
            $(".FrmReOutReportEdit." + lblName).css("color", "red");
        } else {
            $(".FrmReOutReportEdit." + lblName).css("color", "");
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmReOutReportEdit = new R4.FrmReOutReportEdit();
    o_R4_FrmReOutReportEdit.FrmReOutReport = o_R4K_R4K_FrmReOutReport;
    o_R4K_R4K_FrmReOutReport.FrmReOutReportEdit = o_R4_FrmReOutReportEdit;
    o_R4_FrmReOutReportEdit.load();
});
