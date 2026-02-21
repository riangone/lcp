/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240322       本番障害.xlsx NO8         				科目名、補助科目名は両方表示してほしい  	LHB
 * -------------------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKPatternSearch");

HDKAIKEI.HDKPatternSearch = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.grid_id = "#HDKPatternSearch_sprList";
    me.pager = "#HDKPatternSearch_pager";
    me.id = "HDKPatternSearch";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "Kensaku_Click";
    //部署
    me.name_busyoSaki = "";

    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: false,
        caption: "",
        rowNum: 14,
        rowList: [14, 25, 35],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
        shrinkToFit: true,
    };

    me.colModel = [
        {
            label: "伝票区分",
            name: "DENPY_KB",
            index: "DENPY_KB",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "パターンNo",
            name: "PATNO",
            index: "PATNO",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "パターン名",
            name: "PATTERN_NM",
            index: "PATTERN_NM",
            width: 203,
            align: "left",
            sortable: false,
        },
        {
            label: "対象部署",
            name: "BUSYO_NM",
            index: "BUSYO_NM",
            width: 96,
            align: "left",
            sortable: false,
        },
        {
            label: "借方科目",
            name: "KMK_KUM_NM1",
            index: "KMK_KUM_NM1",
            width: 146,
            align: "left",
            sortable: false,
            // 20240322 LHB UPD S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["L_KOUMKU"] !== null &&
                    rowObject["L_KOUMKU"] !== "" &&
                    rowObject["L_KAMOKU"] !== null &&
                    rowObject["L_KAMOKU"] !== ""
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["L_KAMOKU"] +
                        "<br>" +
                        rowObject["L_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["L_KAMOKU"] !== null &&
                        rowObject["L_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["L_KAMOKU"];
                    }
                    if (
                        rowObject["L_KOUMKU"] !== null &&
                        rowObject["L_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["L_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            // 20240322 LHB UPD E
        },
        {
            label: "借方部署",
            name: "BUSYO_NM2",
            index: "BUSYO_NM2",
            align: "left",
            width: 113,
            sortable: false,
        },
        {
            label: "貸方科目",
            name: "KMK_KUM_NM2",
            index: "KMK_KUM_NM2",
            width: 146,
            align: "left",
            sortable: false,
            // 20240322 LHB INS S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["R_KOUMKU"] !== null &&
                    rowObject["R_KOUMKU"] !== "" &&
                    rowObject["R_KAMOKU"] !== null &&
                    rowObject["R_KAMOKU"] !== ""
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["R_KAMOKU"] +
                        "<br>" +
                        rowObject["R_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["R_KAMOKU"] !== null &&
                        rowObject["R_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["R_KAMOKU"];
                    }
                    if (
                        rowObject["R_KOUMKU"] !== null &&
                        rowObject["R_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["R_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            // 20240322 LHB INS E
        },
        {
            label: "貸方部署",
            name: "BUSYO_NM3",
            index: "BUSYO_NM3",
            align: "left",
            width: 113,
            sortable: false,
        },
        {
            label: "摘要",
            name: "TEKYO",
            index: "TEKYO",
            width: 169,
            align: "left",
            sortable: false,
        },
        {
            label: "取引先",
            name: "TORIHIKISAKI_NAME",
            index: "TORIHIKISAKI_NAME",
            align: "left",
            search: false,
            width: 135,
            sortable: false,
        },
        {
            name: "",
            index: "operate",
            width: 72,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"openEditShiwakeHDK('" +
                    rowObject.DENPY_KB +
                    "','" +
                    rowObject.PATNO +
                    "','2')\" id = '" +
                    rowObject.clid +
                    "_btnEdit' class=\"HDKPatternSearch btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>編集</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKPatternSearch.Kensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKPatternSearch.Kensaku2",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKPatternSearch.Shinki",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //部署検索ボタンクリック
    $(".HDKPatternSearch.Kensaku").click(function () {
        me.Kensaku_click();
    });

    //検索ボタンクリック
    $(".HDKPatternSearch.Kensaku2").click(function () {
        me.Kensaku2_click();
    });

    //新規追加タンクリック
    $(".HDKPatternSearch.Shinki").click(function () {
        me.Shinki_click();
    });
    //対象部署redioクリック
    $('input:radio[name="HDKPatternSearch_radio_SYURUI"]').click(function () {
        var checkValue = $(
            'input:radio[name="HDKPatternSearch_radio_SYURUI"]:checked'
        ).val();
        me.radBusyo_CheckedChanged(checkValue);
    });
    //伝票種類redioクリック
    $('input:radio[name="HDKPatternSearch_radio_DENPYO"]').click(function () {
        me.radAll_CheckedChanged();
    });
    //部署指定変更してフォーカスを失う
    $(".HDKPatternSearch.BusyoCD").on("change", function () {
        me.txtBusyo_TextChanged();
    });

    //パターン名変更してフォーカスを失う
    $(".HDKPatternSearch.txtPatternName").on("change", function () {
        me.radAll_CheckedChanged();
    });
    var ele = document.querySelector(".HDKPatternSearch.HDKAIKEI-content");
    var resizeObserver = new ResizeObserver(function () {
        $(me.grid_id).setGridWidth(
            $(".HDKPatternSearch.HDKAIKEI-content").width() * 0.98
        );
    });
    resizeObserver.observe(ele);
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.pattern_Search_Load();
    };

    //**********************************************************************
    //処 理 名：LOAD
    //関 数 名：pattern_Search_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：LOAD
    //**********************************************************************
    me.pattern_Search_Load = function () {
        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1256);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 250 : 373
        );

        //部署
        var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoMstValue";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //部署コード
            me.name_busyoSaki = result["data"];
        };
        me.ajax.send(url, data, 0);

        $(".HDKPatternSearch.BusyoCD").attr("disabled", true);
        $(".HDKPatternSearch.Kensaku").button("disable");
        $(".HDKPatternSearch.jqgridHidden").hide();
        $(".HDKPatternSearch.Subete").trigger("focus");

        //行をダブルクリックして編集画面を開きます。
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowId) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", rowId);
                openEditShiwakeHDK(rowdata["DENPY_KB"], rowdata["PATNO"], "2");
            },
        });

        //上下キー
        $(me.grid_id).jqGrid("bindKeys");
    };

    //**********************************************************************
    //処 理 名：検索ボタンクリックのイベント
    //関 数 名：Kensaku2_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.Kensaku2_click = function () {
        if ($(".HDKPatternSearch.BusyoCD").val() === "") {
            $(".HDKPatternSearch.BusyoNM").val("");
        }
        if (
            $(':input[name="HDKPatternSearch_radio_SYURUI"]:checked').val() !=
            "1"
        ) {
            $(".HDKPatternSearch.Kensaku").button("enable");
            $(".HDKPatternSearch.BusyoCD").attr("disabled", false);
        } else {
            $(".HDKPatternSearch.Kensaku").button("disable");
            $(".HDKPatternSearch.BusyoCD").attr("disabled", true);
        }

        var BusyoCD = $.trim($(".HDKPatternSearch.BusyoCD").val());
        var txtPatternName = $.trim(
            $(".HDKPatternSearch.txtPatternName").val()
        );
        var rdoDENPYO = $.trim(
            $("input[name='HDKPatternSearch_radio_DENPYO']:checked").val()
        );
        var rdoSYURUI = $.trim(
            $("input[name='HDKPatternSearch_radio_SYURUI']:checked").val()
        );

        var data = {
            BusyoCD: BusyoCD,
            txtPatternName: txtPatternName,
            rdoDENPYO: rdoDENPYO,
            rdoSYURUI: rdoSYURUI,
        };
        var completeFnc = function (bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (bErrorFlag == "nodata") {
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                $(".HDKPatternSearch.jqgridHidden").hide();
            } else {
                $(".HDKPatternSearch.jqgridHidden").show();
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, completeFnc);
    };

    //**********************************************************************
    //処 理 名：部署検索ﾎﾞﾀﾝクリック
    //関 数 名：Kensaku_click
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：検索ボタンの処理
    //**********************************************************************
    me.Kensaku_click = function () {
        var dialogId = "HDKBusyoSearchDialogDiv";
        var $rootDiv = $(".HDKPatternSearch.HDKAIKEI-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnBusyoCD").insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", "BusyoCD").insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", "BusyoNM").insertAfter($rootDiv).hide();

        var $RtnCD = $rootDiv.parent().find("#RtnBusyoCD");
        var $BusyoCD = $rootDiv.parent().find("#BusyoCD");
        var $BusyoNM = $rootDiv.parent().find("#BusyoNM");
        $(".HDKPatternSearch.Subete").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 530 : 630,
            width: me.ratio === 1.5 ? 696 : 720,
            resizable: false,
            close: function () {
                if ($RtnCD.html() == 1) {
                    $(".HDKPatternSearch.BusyoCD").val($BusyoCD.html());
                    $(".HDKPatternSearch.BusyoNM").val($BusyoNM.html());
                    $(".HDKPatternSearch.jqgridHidden").hide();
                }
                $RtnCD.remove();
                $BusyoCD.remove();
                $BusyoNM.remove();
                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HDKPatternSearch.Kensaku").trigger("focus");
                }, 100);
            },
        });

        var frmId = "HDKBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#" + dialogId).html(result);
            $("#" + dialogId).dialog("option", "title", "部署マスタ検索");
            $("#" + dialogId).dialog("open");
        };
    };

    //**********************************************************************
    //処 理 名：新規追加ボタンクリックのイベント
    //関 数 名：Shinki_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：新規追加ボタンの処理
    //**********************************************************************
    me.Shinki_click = function () {
        openEditShiwakeHDK(
            $(':input[name="HDKPatternSearch_radio_DENPYO"]:checked').val(),
            "",
            "1"
        );
    };

    me.radBusyo_CheckedChanged = function (pNo) {
        if (pNo == "1") {
            $(".HDKPatternSearch.BusyoNM").val("");
            $(".HDKPatternSearch.BusyoCD").val("");
            $(".HDKPatternSearch.Kensaku").button("disable");
            $(".HDKPatternSearch.BusyoCD").attr("disabled", true);
            $(".HDKPatternSearch.jqgridHidden").hide();
        } else {
            $(".HDKPatternSearch.BusyoCD").attr("disabled", false);
            $(".HDKPatternSearch.BusyoCD").trigger("focus");
            $(".HDKPatternSearch.Kensaku").button("enable");
            $(".HDKPatternSearch.jqgridHidden").hide();
        }
    };

    me.radAll_CheckedChanged = function () {
        $(".HDKPatternSearch.jqgridHidden").hide();
    };

    openEditShiwakeHDK = function (denpykbn, patno, mode) {
        var frmId = "HDKShiharaiInput";
        var dialogdiv = "HDKShiharaiInputDialogDiv";
        //var title = "支払伝票入力";
        // denpykbn = 1:仕訳伝票入力表示
        if (denpykbn == "1") {
            var frmId = "HDKShiwakeInput";
            var dialogdiv = "HDKShiwakeInputDialogDiv";
            //var title = "仕訳伝票入力";
        }
        var $rootDiv = $(".HDKPatternSearch.HDKAIKEI-content");

        $("<div style='display:none;'></div>")
            .attr("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "MODE")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "DISP_NO")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .attr("id", "PATTERN_NO")
            .insertAfter($rootDiv);

        var $MODE = $rootDiv.parent().find("#MODE");
        var $DISP_NO = $rootDiv.parent().find("#DISP_NO");
        var $PATTERN_NO = $rootDiv.parent().find("#PATTERN_NO");

        //mode:1 新しいしきたり    mode:2 編集
        $MODE.html(mode);
        $DISP_NO.html("103");
        $PATTERN_NO.html(patno);

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                $MODE.remove();
                $DISP_NO.remove();
                $PATTERN_NO.remove();
                $("#" + dialogdiv).remove();
                me.Kensaku2_click();
            }

            $("#" + dialogdiv).append(result);
            if (frmId == "HDKShiwakeInput") {
                o_HDKAIKEI_HDKAIKEI.HDKPatternSearch.HDKShiwakeInput.before_close =
                    before_close;
            } else {
                o_HDKAIKEI_HDKAIKEI.HDKPatternSearch.HDKShiharaiInput.before_close =
                    before_close;
            }
        };
    };

    //フォーカス移動時に部署名を取得する
    me.txtBusyo_TextChanged = function () {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(
            $.trim($(".HDKPatternSearch.BusyoCD").val())
        );
        if (me.name_busyoSaki) {
            var foundNM_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKPatternSearch.BusyoNM").val(foundNM ? foundNM["BUSYO_NM"] : "");
        $(".HDKPatternSearch.jqgridHidden").hide();
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HDKPatternSearch_HDKPatternSearch = new HDKAIKEI.HDKPatternSearch();
    o_HDKPatternSearch_HDKPatternSearch.load();
    o_HDKAIKEI_HDKAIKEI.HDKPatternSearch = o_HDKPatternSearch_HDKPatternSearch;
});
