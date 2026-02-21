/**
 * 説明：
 *
 *
 * @author yushuangji
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                                     担当
 * YYYYMMDD           #ID                       XXXXXX                                   FCSDL
 * 20160612           依赖#2530                 EXCEL出力機能の速度改善                      Yinhuaiyu
 * 20201119           BUG                      jqGrid他のページから影響を受けたIDが不正です。   ZHANGBOWEN
 * 20250314			202503_KRSS修正.xlsx		行選択を単一選択にできる					LHB
 * -------------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmBusyoKanriVBA");
KRSS.FrmBusyoKanriVBA = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.sys_id = "KRSS";
    me.id = "FrmBusyoKanriVBA";
    me.grid_id = "#KRSS_FrmBusyoKanriVBA_sprList";

    me.BusyoArr = new Array();
    me.MaxMinBusyoArr = new Array();
    me.jqGrid_busyo_checked = false;
    me.onlyBusyo = false;
    //期首年月日
    me.strKisyuYMD = "";
    //期
    me.strKI = "";
    me.cboYMInit = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========

    me.controls.push({
        id: ".KRSS.FrmBusyoKanriVBA.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmBusyoKanriVBA.cmdCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmBusyoKanriVBA.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: true,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        //20201119 zhangbowen add S
        scroll: 0,
        //20201119 zhangbowen add E
    };
    me.colModel = [
        {
            name: "PATTERN_NM",
            label: "パターン名",
            index: "PATTERN_NM",
            width: 300,
            align: "left",
        },
        {
            name: "PATTERN_NO",
            label: "no",
            index: "PATTERN_NO",
            width: 80,
            hidden: true,
        },
    ];

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".KRSS.FrmBusyoKanriVBA.cboYM").on("blur", function () {
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmBusyoKanriVBA.cboYM")) == false
        ) {
            $(".KRSS.FrmBusyoKanriVBA.cboYM").val(me.cboYMInit);
            $(".KRSS.FrmBusyoKanriVBA.cboYM").trigger("focus");
        }
    });

    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").on("blur", function () {
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val(
            $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val()
        );
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        if ($(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val().trimEnd() != "") {
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom")
                        .val()
                        .trimEnd() == me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val(
                        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val()
                    );
                    break;
                } else {
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom").val("");
                }
            }
        } else {
            $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom").val("");
        }
    });
    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").on("blur", function () {
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        if ($(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val().trimEnd() != "") {
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val().trimEnd() ==
                    me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                    break;
                } else {
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo").val("");
                }
            }
        } else {
            $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo").val("");
        }
    });
    $(".KRSS.FrmBusyoKanriVBA.cmdAction").click(function () {
        var jqGrid_selected_rowDatas = $(me.grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmBusyoKanriVBA.cboYM")) == false
        ) {
            $(".KRSS.FrmBusyoKanriVBA.cboYM").val(me.cboYMInit);
            $(".KRSS.FrmBusyoKanriVBA.cboYM").trigger("focus");
            return;
        }
        if (me.onlyBusyo == true) {
        } else {
            if (jqGrid_selected_rowDatas.length <= 0) {
                $("#jqg_KRSS_FrmBusyoKanriVBA_sprList_1").trigger("focus");
                me.clsComFnc.FncMsgBox("W9999", "パターンを選択してください！");
                return;
            }
        }

        if (me.jqGrid_busyo_checked == true) {
            if (
                $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val().trimEnd() !=
                    "" &&
                $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val().trimEnd() != ""
            ) {
                if (
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val().trimEnd() >
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val().trimEnd()
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "部署コードの範囲が不正です"
                    );
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").css(
                        "backgroundColor",
                        me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                    );
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").css(
                        "backgroundColor",
                        me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                    );
                    return;
                }
            }
        }

        //期首を経理コントロールマスタの値から入力した処理年月の値から期首を自動計算するように変更
        if ($(".KRSS.FrmBusyoKanriVBA.cboYM").val().substring(5, 13) >= 10) {
            me.strKisyuYMD =
                $(".KRSS.FrmBusyoKanriVBA.cboYM").val().substring(0, 4) +
                "/10/01";
            me.strKI =
                parseInt(
                    $(".KRSS.FrmBusyoKanriVBA.cboYM").val().substring(0, 4)
                ) - 1917;
        } else {
            var tmpVal =
                parseInt(
                    $(".KRSS.FrmBusyoKanriVBA.cboYM").val().substring(0, 4)
                ) - 1;
            me.strKisyuYMD = tmpVal + "/10/01";
            me.strKI = tmpVal - 1917;
        }

        var busyoCD_From = "";
        var busyoCD_To = "";
        var jqGridRowData = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var grid_data = {};
        if (me.onlyBusyo == false) {
            for (key in jqGridRowData) {
                grid_data[key] = $(me.grid_id).jqGrid(
                    "getRowData",
                    jqGridRowData[key]
                );
            }
        }
        if (me.jqGrid_busyo_checked == true) {
            busyoCD_From = $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val();
            busyoCD_To = $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val();
        } else {
            busyoCD_From = "";
            busyoCD_To = "";
        }
        var url = me.sys_id + "/" + me.id + "/fncSelect";
        var data = {
            cboYM: $(".KRSS.FrmBusyoKanriVBA.cboYM").val(),
            KI: me.strKI,
            busyoCD_From: busyoCD_From,
            busyoCD_To: busyoCD_To,
            busyoCD_Checked: me.jqGrid_busyo_checked,
            jqGridRowData: grid_data,
            onlyBusyo: me.onlyBusyo,
            chkMikakudei: $(".KRSS.FrmBusyoKanriVBA.chkMikakudei").prop(
                "checked"
            ),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["MsgID"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                }
            } else {
                //20181026 YIN INS S
                downloadExcel = 0;
                //20181026 YIN INS E
                window.location.href = result["data"];
            }
        };
        me.ajax.send(url, data, 0);
    });
    $(".KRSS.FrmBusyoKanriVBA.cmdCancel").click(function () {
        me.formLoad();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ---イベント メソッド s---
    me.formLoad_2 = function () {
        var url = me.sys_id + "/" + me.id + "/fncGetAuthor";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            var isManager = false;
            if (result["result"] == true || result["result" == "true"]) {
                if (result["data"].length >= 1) {
                    for (key in result["data"]) {
                        if (result["data"][key]["BUSYO_CD"] == "000") {
                            isManager = true;
                            break;
                        }
                    }
                    if (isManager == true) {
                        me.jqGrid_busyo_checked = false;
                        me.onlyBusyo = false;
                        //--jqgrid--
                        me.fncGetJqGrid();
                        //focus cboYM
                        $(".KRSS.FrmBusyoKanriVBA.cboYM").trigger("focus");
                    } else {
                        if (me.MaxMinBusyoArr.length == 1) {
                            $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val(
                                me.MaxMinBusyoArr[0]["BUSYO_CD"]
                            );
                            $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val(
                                me.MaxMinBusyoArr[0]["BUSYO_CD"]
                            );

                            for (key in me.BusyoArr) {
                                if (
                                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo")
                                        .val()
                                        .trimEnd() ==
                                    me.BusyoArr[key]["BUSYO_CD"]
                                ) {
                                    $(
                                        ".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo"
                                    ).val(me.BusyoArr[key]["BUSYO_NM"]);
                                    $(
                                        ".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom"
                                    ).val(me.BusyoArr[key]["BUSYO_NM"]);
                                    break;
                                }
                                /*if ($('.KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom').val().trimEnd() == me.BusyoArr[key]["BUSYO_CD"]) {
								 $('.KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom').val(me.BusyoArr[key]["BUSYO_NM"]);
								 break;
								 } */
                            }
                        } else {
                            $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val(
                                me.MaxMinBusyoArr[0]["BUSYO_CD"]
                            );
                            $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val(
                                me.MaxMinBusyoArr[me.MaxMinBusyoArr.length - 1][
                                    "BUSYO_CD"
                                ]
                            );
                            for (key in me.BusyoArr) {
                                if (
                                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo")
                                        .val()
                                        .trimEnd() ==
                                    me.BusyoArr[key]["BUSYO_CD"]
                                ) {
                                    $(
                                        ".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo"
                                    ).val(me.BusyoArr[key]["BUSYO_NM"]);
                                }
                                if (
                                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom")
                                        .val()
                                        .trimEnd() ==
                                    me.BusyoArr[key]["BUSYO_CD"]
                                ) {
                                    $(
                                        ".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom"
                                    ).val(me.BusyoArr[key]["BUSYO_NM"]);
                                }
                            }
                        }
                        me.jqGrid_busyo_checked = true;
                        me.onlyBusyo = true;
                        $(me.grid_id).jqGrid("clearGridData");

                        //--jqgrid--
                        me.fncGetJqGrid();

                        $("#gview_KRSS_FrmBusyoKanriVBA_sprList").block();
                        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").attr(
                            "disabled",
                            false
                        );
                        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").attr(
                            "disabled",
                            false
                        );
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmBusyoKanriVBA").block();
            }
        };
        me.ajax.send(url, "", 1);
    };

    me.formLoad = function () {
        $(".KRSS.FrmBusyoKanriVBA.table1").css("width", "100%");
        me.formClear();
        var url1 = me.sys_id + "/" + me.id + "/fncHKEIRICTL";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmBusyoKanriVBA").block();
                return;
            } else {
                var strTougetu = result["data"][0]["TOUGETU"].substring(0, 7);
                strTougetu = strTougetu.replace("/", "");
                me.cboYMInit = strTougetu;
                $(".KRSS.FrmBusyoKanriVBA.cboYM").val(strTougetu);
                me.fncGetBusyo();

                //focus cboYM
                $(".KRSS.FrmBusyoKanriVBA.cboYM").trigger("focus");
            }
        };
        me.ajax.send(url1, "", 1);
    };
    // ---イベント メソッド e---

    me.complete_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowId, status, _e) {
                if (rowId == 1 && status == true) {
                    me.jqGrid_busyo_checked = true;
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").prop(
                        "disabled",
                        false
                    );
                    $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").prop(
                        "disabled",
                        false
                    );
                } else {
                    // 20250314 LHB UPD S
                    // if (rowId == 1 && status == false) {
                    if ((rowId == 1 && status == false) || rowId !== 1) {
                        // 20250314 LHB UPD E
                        me.jqGrid_busyo_checked = false;
                        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").prop(
                            "disabled",
                            true
                        );
                        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").prop(
                            "disabled",
                            true
                        );
                    }
                }
                // 20250314 LHB INS S
                if (status) {
                    var selectedRowIds = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "selarrrow"
                    );
                    $.each(selectedRowIds, function (_index, id) {
                        if (id !== rowId) {
                            $(me.grid_id).jqGrid("setSelection", id, false);
                        }
                    });
                }
                // 20250314 LHB INS E
            },
        });
        $("#jqgh_KRSS_FrmBusyoKanriVBA_sprList_cb").html("印刷");
    };

    me.formClear = function () {
        $(".KRSS.FrmBusyoKanriVBA.cboYM").val("");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").attr("disabled", "disabled");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").attr("disabled", "disabled");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDFrom").val("");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoCDTo").val("");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMFrom").val("");
        $(".KRSS.FrmBusyoKanriVBA.txtBusyoNMTo").val("");
    };
    me.fncGetBusyo = function () {
        var url = me.sys_id + "/" + me.id + "/fncGetBusyo";
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                //return;
            } else {
                me.MaxMinBusyoArr = result["MaxMinBusyoArr"];
                me.BusyoArr = result["AllBusyoArr"];
            }
            me.formLoad_2();
        };
        me.ajax.send(url, "", 1);
    };
    me.fncGetJqGrid = function () {
        //----get jqgrid data ----
        var data = {};
        var url = me.sys_id + "/" + me.id + "/fncPatternNMSel";
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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 420);
        // 20250314 LHB UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 200);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 270 : 310);
        // 20250314 LHB UPD E
    };

    // ==========
    // = メソッド end =
    // ==========
    var base_load = me.load;

    me.load = function () {
        base_load();
        me.formLoad();
    };
    return me;
};
$(function () {
    var o_KRSS_FrmBusyoKanriVBA = new KRSS.FrmBusyoKanriVBA();
    o_KRSS_FrmBusyoKanriVBA.load();
});
