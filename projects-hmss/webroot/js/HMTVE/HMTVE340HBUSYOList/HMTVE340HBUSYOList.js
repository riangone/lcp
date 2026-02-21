Namespace.register("HMTVE.HMTVE340HBUSYOList");

HMTVE.HMTVE340HBUSYOList = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE340HBUSYOList";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========

    me.grid_id = "#HMTVE340HBUSYOListMain";
    me.pager = "#HMTVE340HBUSYOList_pager";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnSearch_Click";
    //現在選択されているデータの行数
    me.nowSelId = "";
    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
    };

    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 127,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 305,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_KANANM",
            label: "部署名カナ",
            index: "BUSYO_KANANM",
            width: 266,
            align: "left",
            sortable: false,
        },
        {
            name: "btnEdit",
            label: " ",
            index: "btnEdit",
            width: 64,
            align: "right",
            sortable: false,
            formatter: function (cellvalue, options, rowObject) {
                var detail =
                    '<button onclick="openPageExbSearch(' +
                    options.rowId +
                    ")\" id = 'btnEdit' class=\"HMTVE340HBUSYOList btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>修正</button>";
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE340HBUSYOList.btnSearch",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：検索ボタン押下時
    $(".HMTVE340HBUSYOList.btnSearch").click(function () {
        me.btnSearch_Click(1);
    });

    $(".HMTVE340HBUSYOList.txtID,.HMTVE340HBUSYOList.txtName").change(
        function () {
            $(".HMTVE340HBUSYOList.pnlList").hide();
        }
    );

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：init_control
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        try {
            //画面初期化
            $(".HMTVE340HBUSYOList.pnlList").hide();

            //部署コード
            $(".HMTVE340HBUSYOList.txtID").val("");
            //部署名カナ
            $(".HMTVE340HBUSYOList.txtName").val("");

            gdmz.common.jqgrid.init2(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                "",
                me.option
            );

            gdmz.common.jqgrid.set_grid_width(me.grid_id, 800);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 266);

            $(me.grid_id).jqGrid("bindKeys");

            //focus設定
            $(".HMTVE340HBUSYOList.txtID").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：btnSearch_Click
    //引    数：showPageNum:どのページ
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    me.btnSearch_Click = function (showPageNum) {
        try {
            var txtID = $.trim($(".HMTVE340HBUSYOList.txtID").val());
            var txtName = $.trim($(".HMTVE340HBUSYOList.txtName").val());

            var data = {
                txtID: txtID,
                txtName: txtName,
            };

            var complete_fun = function (returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    $(".HMTVE340HBUSYOList.pnlList").hide();
                    return;
                }
                if (returnFLG == "nodata") {
                    $(".HMTVE340HBUSYOList.pnlList").hide();
                    return;
                } else {
                    $(".HMTVE340HBUSYOList.pnlList").show();
                    //修正
                    if (me.nowSelId) {
                        $(me.grid_id).jqGrid("setSelection", me.nowSelId);
                        me.nowSelId = "";
                    } else {
                        if (result["page"] == "1") {
                            //１行目を選択状態にする
                            $(me.grid_id).jqGrid("setSelection", "0");
                        } else {
                            //ページをめくる後,１行目を選択状態にする
                            var selRow =
                                $(".ui-pg-selbox").val() * (result["page"] - 1);
                            $(me.grid_id).jqGrid("setSelection", selRow);
                        }
                    }
                }
            };
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                complete_fun,
                showPageNum
            );
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：修正ボタンのイベント
    //関 数 名：openPageExbSearch
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ﾛｸﾞｲﾝ情報の検索処理
    //**********************************************************************
    openPageExbSearch = function (rowId) {
        try {
            var frmId = "HMTVE350HBUSYOEntry";
            var dialogdiv = "HMTVE350HBUSYOEntryDialogDiv";
            var $rootDiv = $(".HMTVE340HBUSYOList.HMTVE-content");
            if ($("#" + dialogdiv).length > 0) {
                $("#" + dialogdiv).remove();
            }
            //画面に文字が出たら消えます。
            $("<div style='display:none;'></div>")
                .attr("id", dialogdiv)
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .attr("id", "param")
                .insertAfter($rootDiv);
            $("<div style='display:none;'></div>")
                .attr("id", "PartmentID")
                .insertAfter($rootDiv);

            var $param = $rootDiv.parent().find("#param");
            var $PartmentID = $rootDiv.parent().find("#PartmentID");

            $param.html("2");
            var rowData = $(me.grid_id).jqGrid("getRowData", rowId);
            $PartmentID.html(rowData["BUSYO_CD"]);

            var url = me.sys_id + "/" + frmId;
            me.ajax.send(url, "", 0);
            me.ajax.receive = function (result) {
                function before_close() {
                    //データバインドする
                    if (
                        $(".HMTVE340HBUSYOList.pnlList").css("display") ==
                        "block"
                    ) {
                        me.nowSelId = $(me.grid_id).jqGrid(
                            "getGridParam",
                            "selrow"
                        );
                        me.btnSearch_Click("");
                    }
                    $param.remove();
                    $PartmentID.remove();
                    $("#" + dialogdiv).remove();
                }
                $("#" + dialogdiv).append(result);

                o_HMTVE_HMTVE.HMTVE340HBUSYOList.HMTVE350HBUSYOEntry.before_close =
                    before_close;
            };
        } catch (ex) {
            console.log(ex);
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};
$(function () {
    var o_HMTVE_HMTVE340HBUSYOList = new HMTVE.HMTVE340HBUSYOList();
    o_HMTVE_HMTVE340HBUSYOList.load();
    o_HMTVE_HMTVE.HMTVE340HBUSYOList = o_HMTVE_HMTVE340HBUSYOList;
});
