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
 * 20240426            レイアウトについて  すべての項目・ボタン群が一度に表示される     yin
 * 20240430            一覧グリッドについて   件数のブルダウンを15、30、45に変更       yin
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS103PatternSearch");

HMDPS.HMDPS103PatternSearch = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HMDPS = new HMDPS.HMDPS();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.grid_id = "#HMDPS103PatternSearch_sprList";
    me.pager = "#HMDPS103PatternSearch_pager";
    me.id = "HMDPS103PatternSearch";
    me.sys_id = "HMDPS";
    me.g_url = me.sys_id + "/" + me.id + "/" + "Kensaku_Click";
    //部署
    me.name_busyoSaki = "";

    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: false,
        caption: "",
        // 20240430 YIN UPD S
        // rowNum: 14,
        // rowList: [14, 25, 35],
        rowNum: 15,
        rowList: [15, 30, 45],
        // 20240430 YIN UPD E
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
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
            label: "支払先",
            name: "SHIHARAISAKI_NM",
            index: "SHIHARAISAKI_NM",
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
                    "<button onclick=\"openEditShiwake('" +
                    rowObject.DENPY_KB +
                    "','" +
                    rowObject.PATNO +
                    "','2')\" id = '" +
                    rowObject.clid +
                    "_btnEdit' class=\"HMDPS103PatternSearch btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>編集</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMDPS103PatternSearch.Kensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS103PatternSearch.Kensaku2",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS103PatternSearch.Shinki",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMDPS.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMDPS.TabKeyDown();

    //Enterキーのバインド
    me.HMDPS.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //部署検索ボタンクリック
    $(".HMDPS103PatternSearch.Kensaku").click(function () {
        me.Kensaku_click();
    });

    //検索ボタンクリック
    $(".HMDPS103PatternSearch.Kensaku2").click(function () {
        me.Kensaku2_click();
    });

    //新規追加タンクリック
    $(".HMDPS103PatternSearch.Shinki").click(function () {
        me.Shinki_click();
    });
    //対象部署redioクリック
    $('input:radio[name="PatternSearch_radio_SYURUI"]').click(function () {
        var checkValue = $(
            'input:radio[name="PatternSearch_radio_SYURUI"]:checked'
        ).val();
        me.radBusyo_CheckedChanged(checkValue);
    });
    //伝票種類redioクリック
    $('input:radio[name="PatternSearch_radio_DENPYO"]').click(function () {
        me.radAll_CheckedChanged();
    });
    //部署指定変更してフォーカスを失う
    $(".HMDPS103PatternSearch.BusyoCD").on("change", function () {
        me.txtBusyo_TextChanged();
    });

    //パターン名変更してフォーカスを失う
    $(".HMDPS103PatternSearch.txtPatternName").on("change", function () {
        me.radAll_CheckedChanged();
    });
    // 20240426 YIN INS S
    var ele = document.querySelector(".HMDPS.HMDPS-layout-center");
    var resizeObserver = new ResizeObserver(function () {
        $(me.grid_id).setGridWidth(
            $(".HMDPS103PatternSearch.HMDPS-content").width() * 0.98
        );
        $(me.grid_id).setGridHeight(
            $(".HMDPS.HMDPS-layout-center").height() -
                $(".HMDPS103PatternSearch.fieldset").height() -
                115
        );
    });
    resizeObserver.observe(ele);
    // 20240426 YIN INS E
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
        // 20240426 YIN UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 373);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 380);
        // 20240426 YIN UPD E

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

        $(".HMDPS103PatternSearch.BusyoCD").attr("disabled", true);
        $(".HMDPS103PatternSearch.Kensaku").button("disable");
        $(".HMDPS103PatternSearch.jqgridHidden").hide();
        $(".HMDPS103PatternSearch.Subete").trigger("focus");

        //行をダブルクリックして編集画面を開きます。
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowId) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", rowId);
                openEditShiwake(rowdata["DENPY_KB"], rowdata["PATNO"], "2");
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
        if ($(".HMDPS103PatternSearch.BusyoCD").val() === "") {
            $(".HMDPS103PatternSearch.BusyoNM").val("");
        }
        if (
            $(':input[name="PatternSearch_radio_SYURUI"]:checked').val() != "1"
        ) {
            $(".HMDPS103PatternSearch.Kensaku").button("enable");
            $(".HMDPS103PatternSearch.BusyoCD").attr("disabled", false);
        } else {
            $(".HMDPS103PatternSearch.Kensaku").button("disable");
            $(".HMDPS103PatternSearch.BusyoCD").attr("disabled", true);
        }

        var BusyoCD = $.trim($(".HMDPS103PatternSearch.BusyoCD").val());
        var txtPatternName = $.trim(
            $(".HMDPS103PatternSearch.txtPatternName").val()
        );
        var rdoDENPYO = $.trim(
            $("input[name='PatternSearch_radio_DENPYO']:checked").val()
        );
        var rdoSYURUI = $.trim(
            $("input[name='PatternSearch_radio_SYURUI']:checked").val()
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
                $(".HMDPS103PatternSearch.jqgridHidden").hide();
            } else {
                $(".HMDPS103PatternSearch.jqgridHidden").show();
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
        var dialogId = "HMDPS702BusyoSearchDialogDiv";
        var $rootDiv = $(".HMDPS103PatternSearch.HMDPS-content");
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
        $(".HMDPS103PatternSearch.Subete").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: 630,
            width: 500,
            resizable: false,
            close: function () {
                if ($RtnCD.html() == 1) {
                    $(".HMDPS103PatternSearch.BusyoCD").val($BusyoCD.html());
                    $(".HMDPS103PatternSearch.BusyoNM").val($BusyoNM.html());
                    $(".HMDPS103PatternSearch.jqgridHidden").hide();
                }
                $RtnCD.remove();
                $BusyoCD.remove();
                $BusyoNM.remove();
                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HMDPS103PatternSearch.Kensaku").trigger("focus");
                }, 100);
            },
        });

        var frmId = "HMDPS702BusyoSearch";
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
        openEditShiwake(
            $(':input[name="PatternSearch_radio_DENPYO"]:checked').val(),
            "",
            "1"
        );
    };

    me.radBusyo_CheckedChanged = function (pNo) {
        if (pNo == "1") {
            $(".HMDPS103PatternSearch.BusyoNM").val("");
            $(".HMDPS103PatternSearch.BusyoCD").val("");
            $(".HMDPS103PatternSearch.Kensaku").button("disable");
            $(".HMDPS103PatternSearch.BusyoCD").attr("disabled", true);
            $(".HMDPS103PatternSearch.jqgridHidden").hide();
        } else {
            $(".HMDPS103PatternSearch.BusyoCD").attr("disabled", false);
            $(".HMDPS103PatternSearch.BusyoCD").trigger("focus");
            $(".HMDPS103PatternSearch.Kensaku").button("enable");
            $(".HMDPS103PatternSearch.jqgridHidden").hide();
        }
    };

    me.radAll_CheckedChanged = function () {
        $(".HMDPS103PatternSearch.jqgridHidden").hide();
    };

    openEditShiwake = function (denpykbn, patno, mode) {
        var frmId = "HMDPS102ShiharaiDenpyoInput";
        var dialogdiv = "HMDPS102ShiharaiDenpyoInputDialogDiv";
        // denpykbn = 1:仕訳伝票入力表示
        if (denpykbn == "1") {
            var frmId = "HMDPS101ShiwakeDenpyoInput";
            var dialogdiv = "HMDPS101ShiwakeDenpyoInputDialogDiv";
        }
        var $rootDiv = $(".HMDPS103PatternSearch.HMDPS-content");

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
            if (frmId == "HMDPS101ShiwakeDenpyoInput") {
                o_HMDPS_HMDPS.HMDPS103PatternSearch.HMDPS101ShiwakeDenpyoInput.before_close =
                    before_close;
            } else {
                o_HMDPS_HMDPS.HMDPS103PatternSearch.HMDPS102ShiharaiDenpyoInput.before_close =
                    before_close;
            }
        };
    };

    //フォーカス移動時に部署名を取得する
    me.txtBusyo_TextChanged = function () {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(
            $.trim($(".HMDPS103PatternSearch.BusyoCD").val())
        );
        if (me.name_busyoSaki) {
            var foundNM_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HMDPS103PatternSearch.BusyoNM").val(
            foundNM ? foundNM["BUSYO_NM"] : ""
        );
        $(".HMDPS103PatternSearch.jqgridHidden").hide();
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HMDPS103PatternSearch_HMDPS103PatternSearch =
        new HMDPS.HMDPS103PatternSearch();
    o_HMDPS103PatternSearch_HMDPS103PatternSearch.load();
    o_HMDPS_HMDPS.HMDPS103PatternSearch =
        o_HMDPS103PatternSearch_HMDPS103PatternSearch;
});
