/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20201119                  bug                     行の折り返しがあります                           WANGYING
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmKaikei");

R4.FrmKaikei = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmKaikei";
    me.sys_id = "R4K";
    me.url = "";
    me.data = new Array();
    me.blnStart = false;

    // me.FrmHendoKobetu = null;
    // me.FrmTeisyu = null;
    // me.col =
    // {
    // "BusyoCD" : "",
    // "BusyoNM" : "",
    // "KKRCD" : ""
    // };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKaikei.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikei.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikei.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikei.cmdDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikei.cboKeiriBi",
        type: "datepicker",
        handle: "",
    });

    me.colModel = [
        {
            name: "KEIJO_DT",
            label: "経理日",
            index: "KEIJO_DT",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "DENPY_NO",
            label: "伝票№",
            index: "DENPY_NO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SYOHY_NO",
            label: "証憑№",
            index: "SYOHY_NO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "L_BUSYO_CD",
            label: "部署",
            index: "L_BUSYO_CD",
            width: 35,
            align: "left",
            sortable: false,
        },
        {
            name: "L_KAMOK_CD",
            label: "科目",
            index: "L_KAMOK_CD",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "L_KOMOK_CD",
            label: "補目",
            index: "L_KOMOK_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "L_HIMOK_CD",
            label: "費目",
            index: "L_HIMOK_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "R_BUSYO_CD",
            label: "部署",
            index: "R_BUSYO_CD",
            width: 35,
            align: "left",
            sortable: false,
        },
        {
            name: "R_KAMOK_CD",
            label: "科目",
            index: "R_KAMOK_CD",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "R_KOMOK_CD",
            label: "補目",
            index: "R_KOMOK_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "R_HIMOK_CD",
            label: "費目",
            index: "R_HIMOK_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "KEIJO_GK",
            label: "金額",
            index: "KEIJO_GK",
            width: 100,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
        {
            name: "TEKIYO1",
            label: "摘要",
            index: "TEKIYO1",
            width: 150,
            align: "left",
            sortable: false,
        },
        {
            name: "HASEI_MOTO_KB",
            label: "括り部署コード",
            index: "HASEI_MOTO_KB",
            width: 30,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "GYO_NO",
            label: "括り部署コード",
            index: "GYO_NO",
            width: 30,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];
    me.g_url = "R4K/FrmKaikei/fncSearchKaikei";
    me.grid_id = "#FrmKaikei_sprMeisai";
    me.pager = "#FrmKaikei_pager";
    me.sidx = "";
    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: false,
        rownumbers: true,
        rowNum: 50,
        // rowList : [50, 100, 300, 400, 500],
        multiselectWidth: 50,
        // pager : me.pager,
        gridview: true,
        scroll: 1,
        caption: "",
        loadui: "enable",
    };

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    me.init_control = function () {
        base_init_control();

        me.SubFormClear(true);
    };

    $(".FrmKaikei.cmdSearch").click(function () {
        me.PrpMenteFlg = "SEA";
        me.cmdSearch_Click();
    });

    $(".FrmKaikei.cmdDelete").click(function () {
        me.PrpMenteFlg = "DEL";
        me.cmdDelete_Click();
    });

    $(".FrmKaikei.cmdUpdate").click(function () {
        me.frmInputShow("cmdUpdate");
    });

    $(".FrmKaikei.cmdInsert").click(function () {
        me.frmInputShow("cmdInsert");
    });

    $(".FrmKaikei.txtDenpyoNOFrom").on("blur", function () {
        var txtDenpyoNOFrom = $(".FrmKaikei.txtDenpyoNOFrom").val().trimEnd();
        $(".FrmKaikei.txtDenpyoNOTo").val(txtDenpyoNOFrom);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.cmdDelete_Click = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        //処理対象行未存在の場合
        if (rowID == null || rowID == "") {
            clsComFnc.FncMsgBox("I0010");
            return;
        }

        if (
            rowData["HASEI_MOTO_KB"] != "KA" &&
            rowData["HASEI_MOTO_KB"] != "SW"
        ) {
            return;
        }
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteFurikae;
        clsComFnc.FncMsgBox("QY004");
    };

    me.fncDeleteFurikae = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteFurikae";

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

        var arrayVal = {
            KEIJYO: rowData["KEIJO_DT"],
            DENPYO: rowData["DENPY_NO"],
            GYO_NO: rowData["GYO_NO"],
        };

        me.data = {
            request: arrayVal,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E0004");
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            me.blnStart = false;
            me.subSpreadReShow();
        };

        ajax.send(me.url, me.data, 0);
    };

    me.cmdSearch_Click = function () {
        //伝票番号のﾁｪｯｸ
        var txtDenpyoNOFrom = $(".FrmKaikei.txtDenpyoNOFrom").val().trimEnd();
        var txtDenpyoNOTo = $(".FrmKaikei.txtDenpyoNOTo").val().trimEnd();

        if (txtDenpyoNOFrom != "" || txtDenpyoNOTo != "") {
            if (txtDenpyoNOFrom == "" || txtDenpyoNOTo == "") {
                clsComFnc.ObjFocus = $(".FrmKaikei.txtDenpyoNOFrom");
                clsComFnc.FncMsgBox("W0017", "伝票番号の範囲");
                return;
            }
        }
        if (txtDenpyoNOFrom > txtDenpyoNOTo) {
            clsComFnc.ObjSelect = $(".FrmKaikei.txtDenpyoNOFrom");
            clsComFnc.FncMsgBox("W0006", "伝票番号");
            return;
        }

        $("#FrmKaikei_sprMeisai").jqGrid("clearGridData");
        $(".FrmKaikei.cmdUpdate").button("disable");
        $(".FrmKaikei.cmdDelete").button("disable");

        me.blnStart = false;
        me.subSpreadReShow();
    };

    me.SubFormClear = function (blnFlag) {
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        var tmpDate = myDate.getDate().toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        if (tmpDate.length < 2) {
            tmpDate = "0" + tmpDate.toString();
        }
        var tmpNowDate =
            myDate.getFullYear().toString() +
            "/" +
            tmpMonth.toString() +
            "/" +
            tmpDate.toString();

        $(".FrmKaikei.cboKeiriBi").val(tmpNowDate);
        $(".FrmKaikei.txtDenpyoNOFrom").val("");
        $(".FrmKaikei.txtDenpyoNOTo").val("");
        $("#FrmKaikei_sprMeisai").jqGrid("clearGridData");

        me.url = me.sys_id + "/" + me.id + "/GetSysDate";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmKaikei.lblToday").val(result.substring(0, 7));
            //コントロールマスタ存在ﾁｪｯｸ
            me.url = me.sys_id + "/" + me.id + "/ControlCheck";

            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);

                    gdmz.common.jqgrid.init(
                        me.grid_id,
                        me.g_url,
                        me.colModel,
                        me.pager,
                        me.sidx,
                        me.option
                    );
                    gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        me.ratio === 1.5 ? 228 : 285
                    );
                    $("#FrmKaikei_sprMeisai").jqGrid("setGroupHeaders", {
                        useColSpanStyle: true,
                        groupHeaders: [
                            {
                                startColumnName: "L_BUSYO_CD",
                                numberOfColumns: 4,
                                titleText: "借方科目",
                            },
                            {
                                startColumnName: "R_BUSYO_CD",
                                numberOfColumns: 4,
                                titleText: "貸方科目",
                            },
                        ],
                    });
                    $(".FrmKaikei.cmdUpdate").button("disable");
                    $(".FrmKaikei.cmdDelete").button("disable");
                    $(".FrmKaikei.cmdInsert").button("disable");
                    $(".FrmKaikei.cmdSearch").button("disable");
                    return;
                }
                if (result["row"] == 0) {
                    clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );

                    gdmz.common.jqgrid.init(
                        me.grid_id,
                        me.g_url,
                        me.colModel,
                        me.pager,
                        me.sidx,
                        me.option
                    );
                    gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        me.ratio === 1.5 ? 228 : 285
                    );
                    $("#FrmKaikei_sprMeisai").jqGrid("setGroupHeaders", {
                        useColSpanStyle: true,
                        groupHeaders: [
                            {
                                startColumnName: "L_BUSYO_CD",
                                numberOfColumns: 4,
                                titleText: "借方科目",
                            },
                            {
                                startColumnName: "R_BUSYO_CD",
                                numberOfColumns: 4,
                                titleText: "貸方科目",
                            },
                        ],
                    });
                    $(".FrmKaikei.cmdUpdate").button("disable");
                    $(".FrmKaikei.cmdDelete").button("disable");
                    $(".FrmKaikei.cmdInsert").button("disable");
                    $(".FrmKaikei.cmdSearch").button("disable");

                    return;
                }

                if (blnFlag) {
                    $(".FrmKaikei.cmdUpdate").button("disable");
                    $(".FrmKaikei.cmdDelete").button("disable");
                    me.blnStart = true;
                    me.subSpreadReShow();
                }
            };
            ajax.send(me.url, me.data, 0);
        };

        ajax.send(me.url, me.data, 0);
    };

    me.subSpreadReShow = function () {
        $("#FrmKaikei_sprMeisai").jqGrid("clearGridData");
        var KEIJYOBI = $(".FrmKaikei.cboKeiriBi").val().trimEnd();
        var DENPYOF = $(".FrmKaikei.txtDenpyoNOFrom").val().trimEnd();
        var DENPYOT = $(".FrmKaikei.txtDenpyoNOTo").val().trimEnd();

        var arr = {
            KEIJYOBI: KEIJYOBI,
            DENPYOF: DENPYOF,
            DENPYOT: DENPYOT,
        };

        if (me.blnStart) {
            gdmz.common.jqgrid.showWithMesgScroll(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                arr,
                me.complete_fun
            );
            $("#FrmKaikei_sprMeisai").jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "L_BUSYO_CD",
                        numberOfColumns: 4,
                        titleText: "借方科目",
                    },
                    {
                        startColumnName: "R_BUSYO_CD",
                        numberOfColumns: 4,
                        titleText: "貸方科目",
                    },
                ],
            });

            me.sprList_fnc();
        } else {
            //スプレッドに取得データをセットする
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                arr,
                me.complete_fun
            );
        }

        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 285
        );
    };

    me.complete_fun = function (bErrorFlag) {
        if (bErrorFlag == "error") {
            // $(me.grid_id).closest('.ui-jqgrid').block();
            // $(".FrmMKamokuMnt.cmdAction").button("disable");
            // $(".FrmMKamokuMnt.cmdAdd").button("disable");
            // $(".FrmMKamokuMnt.cmdSearch").button("disable");
            return;
        } else if (bErrorFlag == "nodata") {
            if (!me.blnStart) {
                clsComFnc.ObjFocus = $(".FrmKaikei.txtDenpyoNOFrom");
                if (
                    me.PrpMenteFlg == "UPD" ||
                    me.PrpMenteFlg == "DEL" ||
                    me.PrpMenteFlg == "SEA"
                ) {
                    clsComFnc.FncMsgBox("I0001");
                }
            }

            $(".FrmKaikei.txtDenpyoNOFrom").trigger("focus");
            $(".FrmKaikei.cmdUpdate").button("disable");
            $(".FrmKaikei.cmdDelete").button("disable");
        } else {
            var rowIds = $(me.grid_id).jqGrid("getDataIDs");
            for (key in rowIds) {
                var tmpRowData = $(me.grid_id).jqGrid(
                    "getRowData",
                    rowIds[key]
                );
                // var csspropWhiteSmoke = {
                //     background: "#F5F5F5",
                // };
                var csspropLavender = {
                    background: "#E6E6FA",
                };

                if (tmpRowData["HASEI_MOTO_KB"] != "KA") {
                    //$(me.grid_id).jqGrid('setRowData', rowIds[key], false, csspropWhiteSmoke);
                } else {
                    $(me.grid_id).jqGrid(
                        "setRowData",
                        rowIds[key],
                        false,
                        csspropLavender
                    );
                }
            }
            // $(me.grid_id).jqGrid('setSelection', "0");
            $(".FrmKaikei.cmdUpdate").button("enable");
            $(".FrmKaikei.cmdDelete").button("enable");
        }
    };

    me.sprList_fnc = function () {
        //選択行の修正画面を呼び出す
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.frmInputShow("sprList");
            },
        });

        //スプレッド上でエンター押下時に修正処理
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                me.frmInputShow("sprList");
            },
        });
    };

    me.frmInputShow = function (sender) {
        if (sender == "cmdInsert") {
            me.PrpMenteFlg = "INS";
        } else {
            //発生元が"KA"OR"SW"以外の場合は処理終了
            var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
            var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
            if (rowID == null || rowID == "") {
                clsComFnc.FncMsgBox("I0010");
                return;
            }

            if (
                rowData["HASEI_MOTO_KB"].trimEnd() != "KA" &&
                rowData["HASEI_MOTO_KB"].trimEnd() != "SW"
            ) {
                return;
            }
            //プロパティーに値を設定
            me.PrpMenteFlg = "UPD";
            me.prpKeijyoBi = rowData["KEIJO_DT"];
            me.prpDenpy_NO = rowData["DENPY_NO"];
            me.prpGyoNO = rowData["GYO_NO"];
        }
        me.ShowDialog();
    };
    me.ShowDialog = function () {
        $("<div></div>").attr("id", "DialogDiv").insertAfter($("#FrmKaikei"));

        $("#DialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 550,
            //20201119 wangying upd S
            // width : 900,
            width: 910,
            //20201119 wangying upd E
            resizable: false,
            close: function () {
                shortcut.remove("F9");
                $("#DialogDiv").remove();

                me.blnStart = false;
                me.subSpreadReShow();
            },
        });

        var frmId = "FrmKaikeiEdit";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#DialogDiv").html(result);

            $("#DialogDiv").dialog("option", "title", "会計データ入力");
            $("#DialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKaikei = new R4.FrmKaikei();
    o_R4_FrmKaikei.load();

    o_R4K_R4K.FrmKaikei = o_R4_FrmKaikei;
});
