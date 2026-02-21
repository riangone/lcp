/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20160527			  #2529							依頼								Yinhuaiyu
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmLoginSelKRSS");

KRSS.FrmLoginSelKRSS = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.id = "FrmLoginSelKRSS";
    me.sys_id = "KRSS";
    me.grid_id = "#FrmLoginSelKRSS_sprList";
    me.ajax = gdmz.common.ajax();
    me.strTougetu = "";
    me.userId_SYAIN_NO = "";
    me.SysKB = "";
    me.SysData = "";
    $("#FrmLoginSelKRSS_SubDialog").dialog({
        title: "経常利益シミュレーションシステム",
        width: 800,
        height: me.ratio === 1.5 ? 250 : 300,
        autoOpen: false,
        modal: true,
        resize: false,
        close: function () {
            if (me.o_KRSS_FrmLoginEditKRSS.bolResult == true) {
                me.fncButton1Click();
            }
        },
    });
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "SYAIN_NO",
            label: "ユーザＩＤ",
            index: "SYAIN_NO",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "STYLE_NM",
            label: "所属",
            index: "STYLE_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "PATTERN_NM",
            label: "パターン",
            index: "PATTERN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "USER_ID",
            label: "済/未",
            index: "USER_ID",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmLoginSelKRSS.Button3",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginSelKRSS.Button1",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_load = me.load;

    me.load = function () {
        base_load();
        me.FrmLoginSelKRSS_load();
    };

    // // ========== コントロース end ==========
    // // ==========
    // // = 宣言 end =
    // // ==========
    //
    // ==========
    // = イベント start =
    // ==========
    $(".FrmLoginSelKRSS.cboSysKB").change(function () {
        me.fncSyozokuComboSet();
    });
    $(".FrmLoginSelKRSS.Button1").click(function () {
        me.fncButton1Click();
    });
    $(".FrmLoginSelKRSS.Button3").click(function () {
        me.fncButton3Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.FrmLoginSelKRSS_load = function () {
        var url = me.sys_id + "/" + me.id + "/fncButton1Click";
        var data = {
            cboSysKB: "",
            UcComboBox1: "",
            UcUserID: "",
            strTougetu: "",
        };

        me.complete_fun = function () {
            me.fncCompleteSetButtonStatus();
            me.fncDoubleClick();
            var tmpurl = me.sys_id + "/" + me.id + "/fncHKEIRICTL";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }

                me.SysData = result["data"];
                for (var i = 0; i < result["data"].length; i++) {
                    $(".FrmLoginSelKRSS.cboSysKB").append(
                        "<option value='" +
                            result["data"][i]["SYS_KB"] +
                            "'>" +
                            result["data"][i]["STYLE_NM"] +
                            "</option>"
                    );
                    me.strTougetu = result["data"][0]["TOUGETU"];
                }

                me.fncSyozokuComboSet();
            };
            me.ajax.send(tmpurl, "", 0);
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 240 : 310 );
    };

    me.fncSyozokuComboSet = function () {
        $(".FrmLoginSelKRSS.UcComboBox1").empty();
        $(".FrmLoginSelKRSS.UcComboBox1").append("<option></option>");
        var data = $(".FrmLoginSelKRSS.cboSysKB").val();

        var tmpurl1 = me.sys_id + "/" + me.id + "/fncSyozokuComboSet";
        me.ajax.receive = function (result1) {
            var result1 = eval("(" + result1 + ")");
            if (result1["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result1["data"]);
                return;
            }

            for (var i = 0; i < result1["data"].length; i++) {
                $(".FrmLoginSelKRSS.UcComboBox1").append(
                    "<option value='" +
                        result1["data"][i]["STYLE_ID"] +
                        "'>" +
                        result1["data"][i]["STYLE_NM"] +
                        "</option>"
                );
            }
        };
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmLoginSelKRSS.Button3").button("disable");
        me.ajax.send(tmpurl1, data, 0);
    };

    me.fncButton1Click = function () {
        me.complete_fun1 = function () {
            me.fncCompleteSetButtonStatus();
        };
        var data = {
            cboSysKB: $(".FrmLoginSelKRSS.cboSysKB").val(),
            UcComboBox1: $(".FrmLoginSelKRSS.UcComboBox1").val(),
            UcUserID: $(".FrmLoginSelKRSS.UcUserID").val(),
            strTougetu: me.strTougetu,
        };
        me.ajax.receive = function (result1) {
            var result1 = eval("(" + result1 + ")");
            if (result1["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result1["data"]);
                return;
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun1);
    };

    me.fncCompleteSetButtonStatus = function () {
        var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");
        if (getDataCount <= 0) {
            $(".FrmLoginSelKRSS.Button3").button("disable");
        } else {
            $(me.grid_id).jqGrid("setSelection", 1);
            $(".FrmLoginSelKRSS.Button3").button("enable");
        }
    };

    me.fncButton3Click = function () {
        var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");
        me.SysKB = $(".FrmLoginSelKRSS.cboSysKB").val();
        if (getDataCount > 0) {
            var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
            var rowData = $(me.grid_id).jqGrid("getRowData", id);
            me.userId_SYAIN_NO = rowData["SYAIN_NO"];
            if (rowData["SYAIN_NO"] != "") {
                me.openFrmLoginEditDialog();
            }
        } else {
            me.clsComFnc.FncMsgBox("I0001");
        }
    };

    me.openFrmLoginEditDialog = function () {
        var tmpurl1 = me.sys_id + "/FrmLoginEditKRSS/index";
        me.ajax.receive = function (result1) {
            $("#FrmLoginSelKRSS_SubDialog").html(result1);
            $("#FrmLoginSelKRSS_SubDialog").dialog("open");
        };
        me.ajax.send(tmpurl1, "", 0);
    };

    me.fncDoubleClick = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowid) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
                me.SysKB = $(".FrmLoginSelKRSS.cboSysKB").val();
                me.UserID = rowData["SYAIN_NO"];
                me.userId_SYAIN_NO = rowData["SYAIN_NO"];
                me.openFrmLoginEditDialog();
            },
        });
    };
    me.fncGetSyainNo = function () {
        return rowData["SYAIN_NO"];
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmLoginSelKRSS = new KRSS.FrmLoginSelKRSS();
    o_KRSS_FrmLoginSelKRSS.load();
    o_KRSS_KRSS.o_KRSS_FrmLoginSelKRSS = o_KRSS_FrmLoginSelKRSS;
});
