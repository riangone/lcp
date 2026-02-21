Namespace.register("HMAUD.HMAUDReportInputHistory");

HMAUD.HMAUDReportInputHistory = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.HMAUD = new HMAUD.HMAUD();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMAUDReportInputHistory";
    me.sys_id = "HMAUD";

    // ========== 変数 start ==========
    me.grid_id = "#HMAUDReportInputHistoryTb";
    me.g_url = me.sys_id + "/" + me.id + "/pageLoad";
    me.pager = "";
    me.sidx = "";
    // ========== 変数 end ==========
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "CHECK_DT",
            label: "日付",
            index: "CHECK_DT",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "処理担当",
            index: "SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "REMARKS",
            label: "コメント",
            index: "REMARKS",
            width: 300,
            align: "left",
            sortable: false,
        },
    ];
    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDReportInputHistory.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".HMAUDReportInputHistory.btnClose").click(function () {
        $(".HMAUDReportInputHistory.HMAUDReportInputHistoryDialog").dialog(
            "close"
        );
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
        me.Page_Load();
    };
    /*
	 '************************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：なし
	 '戻 り 値 ：なし
	 '処理説明 ：ページ初期化
	 '************************************************************************
	 */
    me.Page_Load = function () {
        me.before_close = function () {};
        $(".HMAUDReportInputHistory.HMAUDReportInputHistoryDialog").dialog({
            autoOpen: false,
            width: 1200,
            height: 692,
            modal: true,
            title: "コメント履歴",
            open: function () {},
            close: function () {
                me.before_close();
                $(
                    ".HMAUDReportInputHistory.HMAUDReportInputHistoryDialog"
                ).remove();
            },
            resizeStop: function () {
                me.setTableSize();
            },
        });
        $(".HMAUDReportInputHistory.HMAUDReportInputHistoryDialog").dialog(
            "open"
        );
        me.check_id = $("#checkid").html();
        // me.role = $("#role").html();
        var data = {
            CHECK_ID: me.check_id,
            // 'KBN' : me.role,
        };
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            me.complete_fun
        );
        $(me.grid_id).jqGrid("bindKeys");
        me.setTableSize();
    };

    me.complete_fun = function (_returnFLG, data) {
        if (data["error"]) {
            me.clsComFnc.FncMsgBox("E9999", data["error"]);
            return;
        }
        me.mainData = data["mainData"];
        $(".HMAUDReportInputHistory .COURS").val(me.mainData[0]["COURS"]);
        $(".HMAUDReportInputHistory .KYOTEN_NAME").val(
            me.mainData[0]["KYOTEN_NAME"]
        );
        $(".HMAUDReportInputHistory .TERRITORY").val(
            me.mainData[0]["TERRITORY"]
        );
    };

    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDReportInputHistoryList").width() - 20
        );
        $("#HMAUDReportInputHistoryTb_REMARKS").width(
            $(
                ".HMAUDReportInputHistory .ui-state-default.ui-jqgrid-hdiv"
            ).width() - 254
        );
        $(".HMAUDReportInputHistory .jqgfirstrow td:nth-child(3)").width(
            $(
                ".HMAUDReportInputHistory .ui-state-default.ui-jqgrid-hdiv"
            ).width() - 254
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            $(".HMAUDReportInputHistoryDialog").height() -
                (me.ratio === 1.5 ? 290 : 194)
        );
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDReportInputHistory = new HMAUD.HMAUDReportInputHistory();
    o_HMAUD_HMAUD.HMAUDReportInput.HMAUDReportInputHistory =
        o_HMAUD_HMAUDReportInputHistory;
    o_HMAUD_HMAUDReportInputHistory.load();
});
