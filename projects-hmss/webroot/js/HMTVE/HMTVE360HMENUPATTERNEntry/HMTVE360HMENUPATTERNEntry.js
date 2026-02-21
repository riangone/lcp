/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE360HMENUPATTERNEntry");

HMTVE.HMTVE360HMENUPATTERNEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE360HMENUPATTERNEntry";
    me.grid_gvProgramInfo_id = "#HMTVE360HMENUPATTERNEntry_gvProgramInfo";
    me.grid_gvRights_id = "#HMTVE360HMENUPATTERNEntry_gvRights";

    me.url_gvRights = me.sys_id + "/" + me.id + "/Page_Load";
    me.url_gvProgramInfo =
        me.sys_id + "/" + me.id + "/gvRights_SelectedIndexChanged";
    me.lastselid = "";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
        shrinkToFit: true,
        multiselectWidth: 60,
    };
    me.colModel1 = [
        {
            label: "権限ID",
            name: "PATTERN_ID",
            index: "PATTERN_ID",
            align: "left",
            search: false,
            width: 110,
            sortable: false,
        },
        {
            label: "権限名",
            name: "PATTERN_NM",
            index: "PATTERN_NM",
            align: "left",
            search: false,
            width: 220,
            sortable: false,
        },
        {
            name: "",
            index: "lblSum",
            width: 47,
            align: "left",
            formatter: function (_cellvalue, options, rowObject) {
                var fontSize = me.ratio === 1.5 ? "10px" : "13px"; // Dynamic font size
                var detail =
                    "<button onclick=\"gvRights_SelectedIndexChanged('" +
                    rowObject.PATTERN_ID +
                    "','" +
                    rowObject.PATTERN_NM +
                    "','" +
                    options.rowId +
                    '\')" id="' +
                    rowObject.PATTERN_ID +
                    '_btnSelect" class="HMTVE360HMENUPATTERNEntry btnSelect Tab Enter" ' +
                    'style="border: 1px solid #77d5f7; background: #16b1e9; width: 100%; font-size: ' +
                    fontSize +
                    ';">' +
                    "選択</button>";
                return detail;
            },
        },
    ];
    me.colModel2 = [
        {
            name: "KBN",
            label: "追加",
            index: "KBN",
            width: 34,
            align: "center",
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
        },
        {
            label: "プログラム№",
            name: "PRO_NO",
            index: "PRO_NO",
            align: "left",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "プログラム名",
            name: "PRO_NM",
            index: "PRO_NM",
            align: "left",
            search: false,
            width: 155,
            sortable: false,
        },
        {
            label: "作成日",
            name: "CREATE_DATE",
            index: "CREATE_DATE",
            align: "left",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE360HMENUPATTERNEntry.btnAdd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE360HMENUPATTERNEntry.btnLogin",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE360HMENUPATTERNEntry.btnDelete",
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
    //追加検索ボタンクリック
    $(".HMTVE360HMENUPATTERNEntry.btnAdd").click(function () {
        me.btnAdd_click();
    });
    //消除ボタンクリック
    $(".HMTVE360HMENUPATTERNEntry.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "メニュー権限名称マスタとメニュー権限管理マスタを削除します。よろしいですか？"
        );
    });
    //登録ボタンクリック
    $(".HMTVE360HMENUPATTERNEntry.btnLogin").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_inputCheck;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "メニュー権限管理マスタに登録します。よろしいですか？"
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
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            gdmz.common.jqgrid.init(
                me.grid_gvProgramInfo_id,
                me.url_gvProgramInfo,
                me.colModel2,
                "",
                "",
                me.option
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_gvProgramInfo_id, 350);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_gvProgramInfo_id,
                me.ratio === 1.5 ? 317 : 337
            );
            $(me.grid_gvProgramInfo_id).jqGrid("bindKeys");
            var sumid = $(me.grid_gvRights_id).jqGrid(
                "getGridParam",
                "records"
            );
            if (sumid > 0) {
                $(me.grid_gvRights_id).jqGrid("setSelection", 0, true);
                var selvalue = $(me.grid_gvRights_id).jqGrid("getRowData", 0);
                $("#" + selvalue["PATTERN_ID"] + "_btnSelect").trigger("focus");
            } else {
                $(".HMTVE360HMENUPATTERNEntry.btnAdd").trigger("focus");
            }
        };
        gdmz.common.jqgrid.showWithMesg(
            me.grid_gvRights_id,
            me.url_gvRights,
            me.colModel1,
            "",
            "",
            me.option,
            {},
            complete_fun
        );
        gdmz.common.jqgrid.set_grid_height(me.grid_gvRights_id, 286);
        gdmz.common.jqgrid.set_grid_width(me.grid_gvRights_id, 479);

        $(me.grid_gvRights_id).jqGrid("bindKeys");
    };
    //**********************************************************************
    //処 理 名：選択ボタンのイベント
    //関 数 名：gvRights_SelectedIndexChanged
    //引    数：無し
    //戻 り 値：なし
    //処理説明：取得データを権限管理ﾃｰﾌﾞﾙの生成
    //**********************************************************************
    gvRights_SelectedIndexChanged = function (PATTERN_ID, PATTERN_NM) {
        me.lastselid = PATTERN_ID;
        var data = {
            selectedRow: PATTERN_ID,
            type: "insert",
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else if (result["records"] > 0) {
                $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").show();
                $(".HMTVE360HMENUPATTERNEntry.txtRightsID").prop(
                    "disabled",
                    true
                );
                //正常是从后台查询结果现在就做个前台效果
                $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val(PATTERN_ID);
                $(".HMTVE360HMENUPATTERNEntry.txtRightsName").val(PATTERN_NM);
                $(".HMTVE360HMENUPATTERNEntry.btnDelete").button("enable");
                $(me.grid_gvProgramInfo_id).jqGrid("setSelection", 0, true);
            } else {
                $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
                me.clsComFnc.FncMsgBox("W0024");
            }
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_gvProgramInfo_id,
            data,
            complete_fun
        );
    };
    //**********************************************************************
    //処 理 名：追加ボタンのイベント
    //関 数 名：btnAdd_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ユーザーの権限を追加する
    //**********************************************************************
    me.btnAdd_click = function () {
        var data = {
            type: "update",
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else if (result["records"] == 0) {
                $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }

            $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").show();
            $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val("");
            $(".HMTVE360HMENUPATTERNEntry.txtRightsName").val("");
            $(".HMTVE360HMENUPATTERNEntry.txtRightsID").prop("disabled", false);
            $(".HMTVE360HMENUPATTERNEntry.btnDelete").button("disable");
            $(me.grid_gvProgramInfo_id).jqGrid("setSelection", 0, true);
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_gvProgramInfo_id,
            data,
            complete_fun
        );
    };
    me.btnLogin_inputCheck = function () {
        //権限ID未入力の場合、エラー
        if (!$(".HMTVE360HMENUPATTERNEntry.txtRightsID").val()) {
            me.clsComFnc.ObjFocus = $(".HMTVE360HMENUPATTERNEntry.txtRightsID");
            me.clsComFnc.FncMsgBox("W9999", "権限IDを入力してください");
            return;
        }
        if (!$(".HMTVE360HMENUPATTERNEntry.txtRightsName").val()) {
            //権限名未入力の場合、エラー
            me.clsComFnc.ObjFocus = $(
                ".HMTVE360HMENUPATTERNEntry.txtRightsName"
            );
            me.clsComFnc.FncMsgBox("W9999", "権限名を入力してください");
            return;
        }
        if (
            me.clsComFnc.GetByteCount(
                $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val()
            ) > 3
        ) {
            //権限IDの桁数を超える場合は、エラー
            me.clsComFnc.ObjFocus = $(".HMTVE360HMENUPATTERNEntry.txtRightsID");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "権限IDの桁数は指定されている桁数をオーバーしています。"
            );
            return;
        }
        if (
            me.clsComFnc.GetByteCount(
                $(".HMTVE360HMENUPATTERNEntry.txtRightsName").val()
            ) > 50
        ) {
            //権限名の桁数を超える場合は、エラー
            me.clsComFnc.ObjFocus = $(
                ".HMTVE360HMENUPATTERNEntry.txtRightsName"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "権限名の桁数は指定されている桁数をオーバーしています。"
            );
            return;
        }
        me.btnLogin_Click();
    };
    //**********************************************************************
    //処 理 名：登録ボタンのイベント
    //関 数 名：btnLogin_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ユーザーが権限で登録する
    //**********************************************************************
    me.btnLogin_Click = function () {
        var inputType = "insert";
        if ($(".HMTVE360HMENUPATTERNEntry.txtRightsID").prop("disabled")) {
            inputType = "update";
        }
        var objDR = $(me.grid_gvProgramInfo_id).jqGrid("getRowData");
        var url = "HMTVE/HMTVE360HMENUPATTERNEntry/btnLogin_Click";
        var data = {
            txtRightsID: $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val(),
            txtRightsName: $(".HMTVE360HMENUPATTERNEntry.txtRightsName").val(),
            rowData: objDR,
            type: inputType,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                if (result["key"] == "E0016") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE360HMENUPATTERNEntry.txtRightsID"
                    );
                    me.clsComFnc.FncMsgBox("E0016");
                } else if (result["key"] == "W0004") {
                    me.clsComFnc.FncMsgBox("W0025");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                var complete_fun = function (_bErrorFlag, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }

                    me.Page_Clear(inputType);
                };
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_gvRights_id,
                    "",
                    complete_fun
                );
            };
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：削除ボタンクリックのイベント
    //関 数 名：btnDelete_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：削除ボタンの処理
    //**********************************************************************
    me.btnDelete_click = function () {
        var url = "HMTVE/HMTVE360HMENUPATTERNEntry/btnDelete_click";
        var data = {
            txtRightsID: $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                var complete_fun = function (_bErrorFlag, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    me.Page_Clear("delete");
                };
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_gvRights_id,
                    "",
                    complete_fun
                );
            };
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：当ページを初期化する
    //関 数 名：Page_Clear
    //引    数：無し
    //戻 り 値：なし
    //処理説明：当ページを初期の状態にセットする
    //**********************************************************************
    me.Page_Clear = function (action) {
        $(".HMTVE360HMENUPATTERNEntry.txtRightsID").val("");
        $(".HMTVE360HMENUPATTERNEntry.txtRightsName").val("");
        $(".HMTVE360HMENUPATTERNEntry.PnlCsvOutTableRow").hide();
        var sumid = $(me.grid_gvRights_id).jqGrid("getGridParam", "records");
        if (sumid > 0) {
            if (action == "update") {
                var rowdatas = $(me.grid_gvRights_id).jqGrid("getRowData");
                for (var i = 0; i < rowdatas.length; i++) {
                    if (rowdatas[i]["PATTERN_ID"] == me.lastselid) {
                        $(me.grid_gvRights_id).jqGrid("setSelection", i);
                        var selvalue = $(me.grid_gvRights_id).jqGrid(
                            "getRowData",
                            i
                        );
                        $("#" + selvalue["PATTERN_ID"] + "_btnSelect").trigger(
                            "focus"
                        );
                        $(".ui-jqgrid-bdiv").scrollTop(0);
                        break;
                    }
                }
            } else {
                $(me.grid_gvRights_id).jqGrid("setSelection", 0, true);
                var selvalue = $(me.grid_gvRights_id).jqGrid("getRowData", 0);
                $("#" + selvalue["PATTERN_ID"] + "_btnSelect").trigger("focus");
            }
        } else {
            $(".HMTVE360HMENUPATTERNEntry.btnAdd").trigger("focus");
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE360HMENUPATTERNEntry =
        new HMTVE.HMTVE360HMENUPATTERNEntry();
    o_HMTVE_HMTVE360HMENUPATTERNEntry.load();
    o_HMTVE_HMTVE.HMTVE360HMENUPATTERNEntry = o_HMTVE_HMTVE360HMENUPATTERNEntry;
});
