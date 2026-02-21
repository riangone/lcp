/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150810           #2003                        BUG                              li
 * 20201118           bug              Dialogを閉じる後、jqgridの幅を変更したエラー                    WANGYING
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyainMstList");

R4.FrmSyainMstList = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmSyainMstList";
    me.operationMode = "";
    me.subId = "FrmSyainMstEdit";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmSyainMstList_sprMeisai";
    me.subDialogId = "#FrmSyainMstList_subFormDialog";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncFromSyainSelect";
    me.pager = "#FrmSyainMstList_pager";
    me.sidx = "";
    me.searchedBusyoCD = "";
    me.searchedBusyoNM = "";
    me.RtnCD = 0;
    me.validatingArr = {
        current: "",
        before: "",
    };
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyainMstList.cmd_SearchBs",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstList.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstList.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstList.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyainMstList.cmdDelete",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //---click---
    $(".FrmSyainMstList.cmdSearch").click(function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.cmd_SearchBs").click(function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.cmdInsert").click(function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.cmdUpdate").click(function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.cmdDelete").click(function () {
        me.fnc_validating();
    });
    //---focus---
    $(".FrmSyainMstList.txtSyainNO").on("focus", function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.txtBusyoCD").on("focus", function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.txtSyainNM").on("focus", function () {
        me.fnc_validating();
    });
    $(".FrmSyainMstList.chkTaisyoku").on("focus", function () {
        me.fnc_validating();
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
        me.FrmSyainMstList_load();
    };

    me.FrmSyainMstList_load = function () {
        me.formInit();
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option1
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 840);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 280
        );

        me.t = document.getElementById("FrmSyainMstList_pager_center");
        me.t.childNodes[1].innerHTML = "";
        //---20150818 li ADD S.
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //dialogを開く
                me.clickFnc_cmdUpdate();
            },
        });
        //---20150818 li ADD E.
    };
    me.formInit = function () {
        $("#FrmSyainMstList_sub_dialog").dialog({
            autoOpen: false,
            modal: true,
            //---20150810 li UPD S.
            //height : 775,
            height: me.ratio === 1.5 ? 554 : 640,
            //---20150810 li UPD E.
            width: 650,
            //---20150819 li UPD S.
            //title : "社員マスタメンテナンス1231321",
            title: "社員マスタメンテナンス",
            //---20150819 li UPD E.
            resizable: false,
        });
        me.initGrid();
        $(".FrmSyainMstList.txtBusyoCD").val("");
        $(".FrmSyainMstList.lblBusyoNM").val("");
        $(".FrmSyainMstList.txtSyainNO").val("");
        $(".FrmSyainMstList.txtSyainNM").val("");
        $(".FrmSyainMstList.chkTaisyoku").prop("checked", false);
        $(".FrmSyainMstList.cmdUpdate").button("disable");
        $(".FrmSyainMstList.cmdDelete").button("disable");
        //---20150818 li DEL S.
        //me.fnckeyDown13();
        //---20150818 li DEL E.
    };

    //------event functions------
    //--click event functions
    me.clickFnc_cmdSearch = function () {
        me.data = {
            txtSyainNO: $(".FrmSyainMstList.txtSyainNO")
                .val()
                .toString()
                .trimEnd(),
            txtSyainNM: $(".FrmSyainMstList.txtSyainNM")
                .val()
                .toString()
                .trimEnd(),
            txtBusyoCD: $(".FrmSyainMstList.txtBusyoCD")
                .val()
                .toString()
                .trimEnd(),
            chkTaisyoku: $(".FrmSyainMstList.chkTaisyoku").prop("checked"),
        };
        gdmz.common.jqgrid.reload(
            me.grid_id,
            me.data,
            me.fnc_searchComplete
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 840);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 280
        );
    };
    me.clickFnc_cmdUpdate = function () {
        me.operationMode = "UPD";
        me.txtSyainNo = "";
        var url = me.sys_id + "/" + me.subId + "/index";
        //get txtSyainNO
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        me.txtSyainNo = rowData["txtSyainNO"];
        var tmpData = {
            //"txtSyainNO" : rowData['txtSyainNO']
        };

        me.ajax.receive = function (result) {
            $("#FrmSyainMstList_sub_dialog").html(result);
            $("#FrmSyainMstList_sub_dialog").dialog("open");
        };
        me.ajax.send(url, tmpData, 1);
    };
    me.clickFnc_cmdInsert = function () {
        me.operationMode = "INS";
        me.txtSyainNo = "";
        var url = me.sys_id + "/" + me.subId + "/index";
        //get txtSyainNO
        var tmpData = {
            //"txtSyainNO" : rowData['txtSyainNO']
        };

        me.ajax.receive = function (result) {
            $("#FrmSyainMstList_sub_dialog").html(result);
            $("#FrmSyainMstList_sub_dialog").dialog("open");
        };
        me.ajax.send(url, tmpData, 1);
    };
    me.clickFnc_cmdDelete = function () {
        me.operationMode = "DEL";
        me.txtSyainNo = "";
        var url = me.sys_id + "/" + me.subId + "/index";
        //get txtSyainNO
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        me.txtSyainNo = rowData["txtSyainNO"];
        var tmpData = {
            //"txtSyainNO" : rowData['txtSyainNO']
        };

        me.ajax.receive = function (result) {
            $("#FrmSyainMstList_sub_dialog").html(result);
            $("#FrmSyainMstList_sub_dialog").dialog("open");
        };
        me.ajax.send(url, tmpData, 1);
    };
    //--keydown event functions
    //---20150818 li DEL S.
    // me.fnckeyDown13 = function()
    // {
    // me.inp = $(me.grid_id);
    // me.inp.bind('keydown', function(e)
    // {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 13 && oEvent.shiftKey == false)
    // {
    // me.clickFnc_cmdUpdate();
    // };
    // });
    //};
    //---20150818 li DEL E.

    //------normal functions------
    me.initGrid = function () {
        me.option1 = {
            pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            multiselectWidth: 30,
            rownumWidth: 40,
        };
        me.colModel = [
            {
                name: "txtSyainNO",
                label: "社員No.",
                index: "txtSyainNO",
                width: 55,
                sortable: false,
                align: "left",
            },
            {
                name: "txtSyainNM",
                label: "社員名",
                index: "txtSyainNM",
                width: 200,
                sortable: false,
                align: "left",
            },
            {
                name: "txtSyainNM",
                label: "社員名カナ",
                index: "BUSYO_KANANM",
                width: 500,
                sortable: false,
                align: "left",
            },
        ];
    };
    me.fnc_searchComplete = function () {
        if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
            $(".FrmSyainMstList.cmdUpdate").button("enable");
            $(".FrmSyainMstList.cmdDelete").button("enable");
            $(".FrmSyainMstList.cmdInsert").button("enable");
            $(me.grid_id).jqGrid("setSelection", 0);
            me.fnc_doubleClickRow();
        } else {
            $(".FrmSyainMstList.cmdInsert").button("enable");
            $(".FrmSyainMstList.cmdUpdate").button("disable");
            $(".FrmSyainMstList.cmdDelete").button("disable");
            return;
        }
    };
    me.fnc_validating = function () {
        //me.Control_LostFocus();
        var tmpA = document.activeElement;
        me.validatingArr["before"] = me.validatingArr["current"];
        me.validatingArr["current"] = tmpA;

        if (me.validatingArr["before"] && me.validatingArr["current"]) {
            if (
                me.validatingArr["before"].className !=
                me.validatingArr["current"].className
            ) {
                if (
                    me.validatingArr["before"].className.indexOf("txtBusyoCD") >
                    0
                ) {
                    var url =
                        me.sys_id +
                        "/" +
                        me.id +
                        "/" +
                        "fncTxtBusyoCDValidating";
                    //set validating values
                    var tmpData = {
                        busyoCD: $(".FrmSyainMstList.txtBusyoCD")
                            .val()
                            .toString()
                            .trimEnd(),
                    };
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] == true) {
                            $(".FrmSyainMstList.lblBusyoNM").html(
                                result["data"]
                            );
                        } else {
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                        me.fnc_button_Click_validating();
                    };
                    me.ajax.send(url, tmpData, 1);
                } else {
                    me.fnc_button_Click_validating();
                }
            } else {
                me.fnc_button_Click_validating();
            }
        } else {
            me.fnc_button_Click_validating();
        }
    };
    me.fnc_button_Click_validating = function () {
        if (me.validatingArr["current"].className.indexOf("cmdInsert") > 0) {
            me.clickFnc_cmdInsert();
        }
        if (me.validatingArr["current"].className.indexOf("cmdDelete") > 0) {
            me.clickFnc_cmdDelete();
        }
        if (me.validatingArr["current"].className.indexOf("cmdUpdate") > 0) {
            me.clickFnc_cmdUpdate();
        }
        if (me.validatingArr["current"].className.indexOf("cmdSearch") > 0) {
            me.clickFnc_cmdSearch();
        }
        if (me.validatingArr["current"].className.indexOf("cmd_SearchBs") > 0) {
            me.fnc_searchBusyo();
        }
    };
    me.fnc_searchBusyo = function () {
        $(".FrmSyainMstList.txtBusyoCD").trigger("focus");

        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmSyainMstList"));

        $("<div></div>")
            .attr("id", "BUSYOCD")
            .insertAfter($("#FrmSyainMstList"));
        $("<div></div>")
            .attr("id", "BUSYONM")
            .insertAfter($("#FrmSyainMstList"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmSyainMstList"));

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 554 : 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#BUSYONM").hide();
                $("#BUSYOCD").hide();
            },
            close: function () {
                me.RtnCD = $("#RtnCD").html();
                me.searchedBusyoCD = $("#BUSYOCD").html();
                me.searchedBusyoNM = $("#BUSYONM").html();
                if (me.searchedBusyoNM != "") {
                    $(".FrmSyainMstList.lblBusyoNM").html(me.searchedBusyoNM);
                }
                if (me.searchedBusyoCD != "") {
                    $(".FrmSyainMstList.txtBusyoCD").val(me.searchedBusyoCD);
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);
            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    };
    me.fnc_closeSubDialog = function () {
        $("#FrmSyainMstList_sub_dialog").dialog("close");
        gdmz.common.jqgrid.reload(
            me.grid_id,
            me.data,
            me.fnc_searchComplete
        );
        // 20201118 wangying upd S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 840);
        // 20201118 wangying upd E
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 280
        );
    };
    me.fnc_doubleClickRow = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.clickFnc_cmdUpdate();
            },
        });
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyainMstList = new R4.FrmSyainMstList();
    o_R4K_R4K.FrmSyainMstList = o_R4_FrmSyainMstList;
    //o_R4_FrmSyainMstList.FrmBusyoSearch = o_R4K_R4K.FrmBusyoSearch();
    o_R4_FrmSyainMstList.load();
});
