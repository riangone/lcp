/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150804           #2016 2019 2020 2021 2022    BUG                              li
 * 20150819           #2078						   BUG                              Yuanjh
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmTotalBusyo");

R4.FrmTotalBusyo = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmTotalBusyo";
    me.sys_id = "R4K";
    me.grid_Line = "#FrmTotalBusyo_sprLine";
    me.grid_Meisai = "#FrmTotalBusyo_sprMeisai";
    me.grid_MeisaiPlus = "#FrmTotalBusyo_sprMeisaiPlus";
    me.g_url = "R4K/FrmTotalBusyo/fncBusyoMstSelect";
    me.sidx = "";
    me.lastsel = 0;
    me.lastselPlus = 0;
    me.arrCheckData = new Array();
    me.arrCheckDataPlus = new Array();
    me.lastRowBusyoCD = "";
    me.flagBlock = false;
    me.strBusyoCD = "";
    me.findFlagPlus = false;
    me.firstMeisaiData = new Array();
    me.firstMeisaiPlusData = new Array();

    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };

    me.addDataMeisai = {
        BUSYO_CD: "",
        BUSYO_NM: "",
        CREATE_DATE: "",
    };

    me.colModelLine = [
        {
            name: "BUSYO_CD",
            label: "集計部署ｺｰﾄﾞ",
            index: "BUSYO_CD",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 240,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    me.colModelMeisai = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 80,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "3",
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 240,
            sortable: false,
            editable: false,
            align: "left",
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    me.colModelMeisaiPlus = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 80,
            sortable: false,
            editable: true,
            align: "left",
            editoptions: {
                class: "numeric",
                maxlength: "3",
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 240,
            sortable: false,
            editable: false,
            align: "left",
            editoptions: {
                maxlength: "40",
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmTotalBusyo.cmdCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmTotalBusyo.cmdAction",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    // '**********************************************************************
    // '処理概要：ｷｬﾝｾﾙﾎﾞﾀﾝｸﾘｯｸ
    // '**********************************************************************
    $(".FrmTotalBusyo.cmdCancel").click(function () {
        me.flagBlock = false;
        me.strBusyoCD = $(me.grid_Line).jqGrid(
            "getCell",
            me.lastRowBusyoCD,
            "BUSYO_CD"
        );

        $(me.grid_Meisai).closest(".ui-jqgrid").block();
        $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();
        $(".FrmTotalBusyo.cmdCancel").button("disable");
        $(".FrmTotalBusyo.cmdAction").button("disable");

        me.fncDispTTLBUSYO(false);
    });

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmTotalBusyo.cmdAction").click(function () {
        $(me.grid_Meisai).jqGrid("saveRow", me.lastsel, null, "clientArray");
        $(me.grid_MeisaiPlus).jqGrid(
            "saveRow",
            me.lastselPlus,
            null,
            "clientArray"
        );

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        $(".FrmTotalBusyo.cmdCancel").button("disable");
        $(".FrmTotalBusyo.cmdAction").button("disable");

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                gdmz.common.jqgrid.init(
                    me.grid_Meisai,
                    "",
                    me.colModelMeisai,
                    "",
                    me.sidx,
                    me.option
                );
                gdmz.common.jqgrid.init(
                    me.grid_MeisaiPlus,
                    "",
                    me.colModelMeisaiPlus,
                    "",
                    me.sidx,
                    me.option
                );

                gdmz.common.jqgrid.set_grid_width(me.grid_Meisai, 400);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_Meisai,
                    me.ratio === 1.5 ? 168 : 210
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_MeisaiPlus, 400);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_MeisaiPlus,
                    me.ratio === 1.5 ? 90 : 110
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();
                $(me.grid_Meisai).closest(".ui-jqgrid").block();
                $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();

                //部署マスタにデータが存在しない場合
                if (bErrorFlag == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                me.strBusyoCD = $(me.grid_Line).jqGrid(
                    "getCell",
                    0,
                    "BUSYO_CD"
                );

                $(me.grid_Line).jqGrid("setSelection", 0, true);

                me.fncDispTTLBUSYO(true);
                me.fncCompleteDealLine();
            }
        };
        //スプレッドに取得データをセットする
        //部署マスタからのデータ
        gdmz.common.jqgrid.showWithMesg(
            me.grid_Line,
            me.g_url,
            me.colModelLine,
            "",
            me.sidx,
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_Line, 420);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_Line,
            me.ratio === 1.5 ? 318 : 390
        );
    };

    // '**********************************************************************
    // '処 理 名：集計部署ﾏｽﾀ表示
    // '関 数 名：fncDispTTLBUSYO
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：集計部署ﾏｽﾀ表示
    // '**********************************************************************
    me.fncDispTTLBUSYO = function (bLoadFlag) {
        var url = me.id + "/fncTTLBusyoMstSelect";
        var data = {
            Busyo_CD: me.strBusyoCD,
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                if (bLoadFlag) {
                    gdmz.common.jqgrid.init(
                        me.grid_MeisaiPlus,
                        "",
                        me.colModelMeisaiPlus,
                        "",
                        me.sidx,
                        me.option
                    );

                    gdmz.common.jqgrid.set_grid_width(
                        me.grid_MeisaiPlus,
                        400
                    );
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_MeisaiPlus,
                        me.ratio === 1.5 ? 90 : 110
                    );

                    $(me.grid_Line).jqGrid("clearGridData");
                    $(me.grid_Line).closest(".ui-jqgrid").block();
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();
                } else if (me.flagBlock) {
                    me.flagBlock = false;
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                } else {
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();

                    $(".FrmTotalBusyo.cmdCancel").button("disable");
                    $(".FrmTotalBusyo.cmdCancel").trigger("focus");
                    $(".FrmTotalBusyo.cmdAction").button("disable");
                }
            } else {
                me.firstMeisaiData = $(me.grid_Meisai).jqGrid("getRowData");
                var rowArray = $(me.grid_Meisai).jqGrid(
                    "getGridParam",
                    "records"
                );

                for (var i = rowArray; i < 100; i++) {
                    $(me.grid_Meisai).jqGrid("addRowData", i, me.addDataMeisai);
                }

                me.fncDispPLUSTTLBUSYO(bLoadFlag);
                me.fncCompleteDealBUSYO();
            }

            if (me.flagBlock == false) {
                me.findFlagPlus = false;
            }
        };

        if (bLoadFlag) {
            //スプレッドに取得データをセットする
            //集計部署マスタからのデータ
            gdmz.common.jqgrid.showWithMesg(
                me.grid_Meisai,
                url,
                me.colModelMeisai,
                "",
                me.sidx,
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_Meisai, 400);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_Meisai,
                me.ratio === 1.5 ? 168 : 210
            );
            //20150820	Yuanjh ADD S.
            $(me.grid_Meisai).jqGrid("bindKeys");
            //20150820	Yuanjh ADD E.
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_Meisai,
                data,
                me.complete_fun
            );
        }
    };

    // '**********************************************************************
    // '処 理 名：中古車部門加算部署マスタ表示
    // '関 数 名：fncDispPLUSTTLBUSYO
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：中古車部門加算部署マスタ表示
    // '**********************************************************************
    me.fncDispPLUSTTLBUSYO = function (bLoadFlag) {
        var url = me.id + "/fncPlusTTLBusyoMstSelect";
        var data = {
            Busyo_CD: me.strBusyoCD,
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                if (bLoadFlag) {
                    $(me.grid_Line).jqGrid("clearGridData");
                    $(me.grid_Meisai).jqGrid("clearGridData");
                    $(me.grid_Line).closest(".ui-jqgrid").block();
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();
                } else if (me.flagBlock) {
                    me.flagBlock = false;
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                } else {
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();

                    $(".FrmTotalBusyo.cmdCancel").button("disable");
                    $(".FrmTotalBusyo.cmdCancel").trigger("focus");
                    $(".FrmTotalBusyo.cmdAction").button("disable");
                }
            } else {
                me.firstMeisaiPlusData = $(me.grid_MeisaiPlus).jqGrid(
                    "getRowData"
                );
                var rowArray = $(me.grid_MeisaiPlus).jqGrid(
                    "getGridParam",
                    "records"
                );

                for (var i = rowArray; i < 100; i++) {
                    $(me.grid_MeisaiPlus).jqGrid(
                        "addRowData",
                        i,
                        me.addDataMeisai
                    );
                }

                if (bLoadFlag) {
                    $(me.grid_Meisai).closest(".ui-jqgrid").block();
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();
                } else if (me.flagBlock) {
                    $(me.grid_Meisai).closest(".ui-jqgrid").unblock();

                    $(".FrmTotalBusyo.cmdCancel").button("enable");
                    $(".FrmTotalBusyo.cmdCancel").trigger("focus");
                    $(".FrmTotalBusyo.cmdAction").button("enable");
                } else {
                    $(me.grid_Line).closest(".ui-jqgrid").unblock();
                }

                if (me.flagBlock) {
                    //HPLUSKMKLINEMSTに登録されているTOTAL_BUSYO_CDの場合メンテナンス可能
                    me.selectKmkLineMst();
                }

                me.fncCompleteDealBUSYOPlus();
            }

            if (me.flagBlock == false) {
                me.findFlagPlus = false;
            }
        };

        if (bLoadFlag) {
            //スプレッドに取得データをセットする
            //中古車部門加算部署マスタからのデータ
            gdmz.common.jqgrid.showWithMesg(
                me.grid_MeisaiPlus,
                url,
                me.colModelMeisaiPlus,
                "",
                me.sidx,
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_MeisaiPlus, 400);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_MeisaiPlus,
                me.ratio === 1.5 ? 85 : 105
            );
            $(me.grid_MeisaiPlus).jqGrid("bindKeys");
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_MeisaiPlus,
                data,
                me.complete_fun
            );
        }
    };

    // '**********************************************************************
    // '処理概要：スプレッドセルクリック
    // '**********************************************************************
    me.fncCompleteDealLine = function () {
        $(me.grid_Line).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, _e) {
                me.flagBlock = true;
                me.lastRowBusyoCD = rowid;
                me.strBusyoCD = $(me.grid_Line).jqGrid(
                    "getCell",
                    rowid,
                    "BUSYO_CD"
                );

                $(me.grid_Line).closest(".ui-jqgrid").block();

                me.fncDispTTLBUSYO(false);
            },
        });
    };

    // '**********************************************************************
    // '処理概要：HPLUSKMKLINEMSTに登録されているTOTAL_BUSYO_CDの場合メンテナンス可能
    // '**********************************************************************
    me.selectKmkLineMst = function () {
        var url = me.id + "/fncPlusKMKLineMstSelect";
        var data = {
            Busyo_CD: me.strBusyoCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    me.findFlagPlus = true;
                    $(me.grid_MeisaiPlus).closest(".ui-jqgrid").unblock();
                }
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処理概要：スプレッドセルクリック
    // '**********************************************************************
    me.fncCompleteDealBUSYO = function () {
        $(me.grid_Meisai).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex = e.target.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_Meisai).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            $(me.grid_MeisaiPlus).jqGrid(
                                "saveRow",
                                me.lastselPlus,
                                null,
                                "clientArray"
                            );

                            if (rowid != me.lastsel) {
                                me.selectBusyoNM(me.lastsel, me.grid_Meisai);
                            }

                            me.lastsel = rowid;
                        }

                        $(me.grid_Meisai).jqGrid("editRow", rowid, true);
                    } else {
                        //ヘッダークリック
                        $(me.grid_Meisai).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_MeisaiPlus).jqGrid(
                            "saveRow",
                            me.lastselPlus,
                            null,
                            "clientArray"
                        );

                        //削除確認メッセージを表示する
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowDataMeisai;
                        me.clsComFnc.MessageBox(
                            "削除します、よろしいですか？",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        $(me.grid_Meisai).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_MeisaiPlus).jqGrid(
                            "saveRow",
                            me.lastselPlus,
                            null,
                            "clientArray"
                        );

                        if (rowid != me.lastsel) {
                            me.selectBusyoNM(me.lastsel, me.grid_Meisai);
                        }

                        me.lastsel = rowid;
                    }

                    $(me.grid_Meisai).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: true,
                });
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_Meisai,
                    e,
                    me.lastsel
                );
            },
        });
    };

    me.delRowDataMeisai = function () {
        var rowID = $(me.grid_Meisai).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_Meisai).jqGrid("getRowData", rowID);

        var url = me.id + "/frmMeisaiDeleteRow";
        var data = {
            BUSYO_CD: rowData["BUSYO_CD"],
            TOTAL_BUSYO_CD: me.strBusyoCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.delRowDataContent(rowID, me.grid_Meisai);
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.delRowDataContent = function (rowID, gridNM) {
        var getDataID = $(gridNM).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(gridNM).jqGrid("getRowData", i + 1);
            $(gridNM).jqGrid("setRowData", i, rowData);
        }

        $(gridNM).jqGrid("delRowData", getDataID.length - 1);
        $(gridNM).jqGrid("setSelection", rowID, true);
        if (gridNM === me.grid_Meisai) {
            me.firstMeisaiData.splice(parseInt(rowID), 1);
        } else if (gridNM === me.grid_MeisaiPlus) {
            me.firstMeisaiPlusData.splice(parseInt(rowID), 1);
        }
    };

    me.selectBusyoNM = function (rowID, gridNM) {
        var rowData = $(gridNM).jqGrid("getRowData", rowID);
        var strBusyoSelect = rowData["BUSYO_CD"];

        if (me.clsComFnc.FncNv(strBusyoSelect) == "") {
            $(gridNM).jqGrid("setRowData", rowID, me.addDataMeisai);
        } else {
            //入力されている場合
            var url = me.id + "/fncBusyoNmSelect";
            var data = {
                BUSYO_CD: strBusyoSelect,
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["data"].length > 0) {
                    $(gridNM).jqGrid(
                        "setCell",
                        rowID,
                        "BUSYO_NM",
                        result["data"][0]["BUSYO_NM"]
                    );
                } else if (result["data"].length <= 0) {
                    $(gridNM).jqGrid("setRowData", rowID, me.addDataMeisai);
                    $(gridNM).jqGrid(
                        "setCell",
                        rowID,
                        "BUSYO_CD",
                        strBusyoSelect
                    );
                }

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        }
    };

    me.fncCompleteDealBUSYOPlus = function () {
        $(me.grid_MeisaiPlus).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex = e.target.cellIndex;

                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        if (rowid && rowid != me.lastselPlus) {
                            $(me.grid_Meisai).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            $(me.grid_MeisaiPlus).jqGrid(
                                "saveRow",
                                me.lastselPlus,
                                null,
                                "clientArray"
                            );

                            if (rowid != me.lastselPlus) {
                                me.selectBusyoNM(
                                    me.lastselPlus,
                                    me.grid_MeisaiPlus
                                );
                            }

                            me.lastselPlus = rowid;
                        }

                        $(me.grid_MeisaiPlus).jqGrid("editRow", rowid, true);
                    } else {
                        //ヘッダークリック
                        $(me.grid_Meisai).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_MeisaiPlus).jqGrid(
                            "saveRow",
                            me.lastselPlus,
                            null,
                            "clientArray"
                        );

                        //削除確認メッセージを表示する
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowDataMeisaiPlus;
                        me.clsComFnc.MessageBox(
                            "削除します、よろしいですか？",
                            me.clsComFnc.GSYSTEM_NAME,
                            "YesNo",
                            "Question",
                            me.clsComFnc.MessageBoxDefaultButton.Button2
                        );
                    }
                } else {
                    if (rowid && rowid != me.lastselPlus) {
                        $(me.grid_Meisai).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        $(me.grid_MeisaiPlus).jqGrid(
                            "saveRow",
                            me.lastselPlus,
                            null,
                            "clientArray"
                        );

                        if (rowid != me.lastselPlus) {
                            me.selectBusyoNM(
                                me.lastselPlus,
                                me.grid_MeisaiPlus
                            );
                        }

                        me.lastselPlus = rowid;
                    }

                    $(me.grid_MeisaiPlus).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: true,
                });
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_MeisaiPlus,
                    e,
                    me.lastselPlus
                );
            },
        });
    };

    me.delRowDataMeisaiPlus = function () {
        var rowID = $(me.grid_MeisaiPlus).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_MeisaiPlus).jqGrid("getRowData", rowID);

        var url = me.id + "/frmMeisaiPlusDeleteRow";
        var data = {
            BUSYO_CD: rowData["BUSYO_CD"],
            TOTAL_BUSYO_CD: me.strBusyoCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                //行削除を行う
                me.delRowDataContent(rowID, me.grid_MeisaiPlus);
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：lntTeika  (I)定価合計
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        var intRtn = 0;
        var blnInputFlg = false;
        var blnInputFlgPlus = false;
        me.arrCheckData = new Array();
        me.arrCheckDataPlus = new Array();
        var data = $(me.grid_Meisai).jqGrid("getDataIDs");
        var dataPlus = $(me.grid_MeisaiPlus).jqGrid("getDataIDs");

        for (rowID in data) {
            var rowData = $(me.grid_Meisai).jqGrid("getRowData", data[rowID]);

            //どれか一列でも入力されていた場合
            if (rowData["BUSYO_CD"].trimEnd() != "") {
                intRtn = me.clsComFnc.FncSprCheck(
                    rowData["BUSYO_CD"],
                    0,
                    me.clsComFnc.INPUTTYPE.CHAR2,
                    me.colModelMeisai[0]["editoptions"]["maxlength"]
                );

                if (intRtn != 0) {
                    me.setFocus(me.grid_Meisai, rowID, "BUSYO_CD");
                    me.clsComFnc.FncMsgBox(
                        "W000" + intRtn * -1,
                        me.colModelMeisai[0]["label"]
                    );
                    return false;
                }

                //キー項目の必須ﾁｪｯｸ
                if (rowData["BUSYO_CD"].trimEnd() == "") {
                    me.setFocus(me.grid_Meisai, rowID, "BUSYO_CD");
                    me.clsComFnc.FncMsgBox("W0001", "部署コード");
                    return false;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                BUSYO_CD: "",
                BUSYO_NM: "",
                rowNO: "",
            };

            tmpAttr["BUSYO_CD"] = rowData["BUSYO_CD"];
            tmpAttr["BUSYO_NM"] = rowData["BUSYO_NM"];
            tmpAttr["CREATE_DATE"] = rowData["CREATE_DATE"];
            tmpAttr["rowNO"] = rowID;

            me.arrCheckData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(me.grid_Meisai, 0, "BUSYO_CD");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < me.arrCheckData.length - 1; i++) {
            for (var j = i + 1; j < me.arrCheckData.length; j++) {
                if (me.arrCheckData[i]["BUSYO_CD"].trimEnd() != "") {
                    if (
                        me.arrCheckData[i]["BUSYO_CD"] ==
                        me.arrCheckData[j]["BUSYO_CD"]
                    ) {
                        var row = j;
                        if (me.firstMeisaiData.length - 1 >= i) {
                            if (
                                me.firstMeisaiData[i]["BUSYO_CD"] !==
                                me.arrCheckData[i]["BUSYO_CD"]
                            ) {
                                var row = i;
                            }
                        }
                        me.setFocus(me.grid_Meisai, row, "BUSYO_CD");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }

        if (me.findFlagPlus) {
            for (rowID in dataPlus) {
                var rowData = $(me.grid_MeisaiPlus).jqGrid(
                    "getRowData",
                    dataPlus[rowID]
                );

                //どれか一列でも入力されていた場合
                if (rowData["BUSYO_CD"].trimEnd() != "") {
                    intRtn = me.clsComFnc.FncSprCheck(
                        rowData["BUSYO_CD"],
                        0,
                        me.clsComFnc.INPUTTYPE.CHAR2,
                        me.colModelMeisaiPlus[0]["editoptions"]["maxlength"]
                    );

                    if (intRtn != 0) {
                        me.setFocus(me.grid_MeisaiPlus, rowID, "BUSYO_CD");
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModelMeisaiPlus[0]["label"]
                        );
                        return false;
                    }

                    //キー項目の必須ﾁｪｯｸ
                    if (rowData["BUSYO_CD"].trimEnd() == "") {
                        me.setFocus(me.grid_MeisaiPlus, rowID, "BUSYO_CD");
                        me.clsComFnc.FncMsgBox("W0001", "部署コード");
                        return false;
                    }

                    blnInputFlgPlus = true;
                }

                var tmpAttr = {
                    BUSYO_CD: "",
                    BUSYO_NM: "",
                    rowNO: "",
                };

                tmpAttr["BUSYO_CD"] = rowData["BUSYO_CD"];
                tmpAttr["BUSYO_NM"] = rowData["BUSYO_NM"];
                tmpAttr["CREATE_DATE"] = rowData["CREATE_DATE"];
                tmpAttr["rowNO"] = rowID;

                me.arrCheckDataPlus.push(tmpAttr);
            }

            if (!blnInputFlgPlus) {
                me.setFocus(me.grid_MeisaiPlus, 0, "BUSYO_CD");
                me.clsComFnc.FncMsgBox("W0017", "データ");
                return false;
            }

            //重複ﾁｪｯｸ(加算集計元部署)
            for (var i = 0; i < me.arrCheckDataPlus.length - 1; i++) {
                for (var j = i + 1; j < me.arrCheckDataPlus.length; j++) {
                    if (me.arrCheckDataPlus[i]["BUSYO_CD"].trimEnd() != "") {
                        if (
                            me.arrCheckDataPlus[i]["BUSYO_CD"] ==
                            me.arrCheckDataPlus[j]["BUSYO_CD"]
                        ) {
                            var row = j;
                            if (me.firstMeisaiPlusData.length - 1 >= i) {
                                if (
                                    me.firstMeisaiPlusData[i]["BUSYO_CD"] !==
                                    me.arrCheckDataPlus[i]["BUSYO_CD"]
                                ) {
                                    var row = i;
                                }
                            }
                            me.setFocus(me.grid_MeisaiPlus, row, "BUSYO_CD");
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "キー項目が重複しています"
                            );
                            return false;
                        }
                    }
                }
            }

            //重複ﾁｪｯｸ(集計元部署 ＋ 加算集計元部署)
            for (var i = 0; i < me.arrCheckData.length; i++) {
                for (var j = 0; j < me.arrCheckDataPlus.length; j++) {
                    if (me.arrCheckData[i]["BUSYO_CD"].trimEnd() != "") {
                        if (
                            me.arrCheckData[i]["BUSYO_CD"] ==
                            me.arrCheckDataPlus[j]["BUSYO_CD"]
                        ) {
                            var row = j;
                            var gridId = me.grid_MeisaiPlus;
                            if (me.firstMeisaiData.length - 1 >= i) {
                                if (
                                    me.firstMeisaiData[i]["BUSYO_CD"] !==
                                    me.arrCheckData[i]["BUSYO_CD"]
                                ) {
                                    var row = i;
                                    gridId = me.grid_Meisai;
                                }
                            } else if (
                                me.firstMeisaiPlusData.length - 1 >= j &&
                                me.firstMeisaiPlusData[j]["BUSYO_CD"] ===
                                    me.arrCheckDataPlus[j]["BUSYO_CD"]
                            ) {
                                var row = i;
                                gridId = me.grid_Meisai;
                            }
                            me.setFocus(gridId, row, "BUSYO_CD");
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                "集計元部署と加算集計元部署でキー項目が重複しています"
                            );
                            return false;
                        }
                    }
                }
            }
        }

        //部署ﾏｽﾀの存在チェック
        me.checkExist();

        return true;
    };

    me.setFocus = function (gridNM, rowID, colID) {
        var rowNum = parseInt(rowID);
        $(gridNM).jqGrid("setSelection", rowNum);

        var ceil = rowNum + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.checkExist = function () {
        var url = me.id + "/frmCheckExit";
        var data = {
            MeisaiData: me.arrCheckData,
            MeisaiDataPlus: me.arrCheckDataPlus,
            checkPlus: me.findFlagPlus,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["rowNO"] != "none") {
                    if (result["gridNM"] == "grid_Meisai") {
                        me.setFocus(
                            me.grid_Meisai,
                            result["rowNO"],
                            "BUSYO_CD"
                        );
                    } else if (result["gridNM"] == "grid_MeisaiPlus") {
                        me.setFocus(
                            me.grid_MeisaiPlus,
                            result["rowNO"],
                            "BUSYO_CD"
                        );
                    }
                    me.clsComFnc.FncMsgBox("W0007", "部署");
                    return false;
                } else {
                    //確認メッセージ
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataTTLBusyo;
                    me.clsComFnc.FncMsgBox("QY010");
                }
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return false;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.fncDeleteUpdataTTLBusyo = function () {
        var url = me.id + "/fncDelUpdTTLBusyo";
        var sendData = {
            MeisaiData: me.arrCheckData,
            MeisaiDataPlus: me.arrCheckDataPlus,
            checkPlus: me.findFlagPlus,
            TOTAL_BUSYO_CD: me.strBusyoCD,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                me.flagBlock = false;
                me.findFlagPlus = false;

                $(".FrmTotalBusyo.cmdCancel").button("disable");
                $(".FrmTotalBusyo.cmdAction").button("disable");

                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");

                $(me.grid_Line).closest(".ui-jqgrid").unblock();
                $(me.grid_Meisai).closest(".ui-jqgrid").block();
                $(me.grid_MeisaiPlus).closest(".ui-jqgrid").block();
            }
        };
        me.ajax.send(url, sendData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmTotalBusyo = new R4.FrmTotalBusyo();
    o_R4_FrmTotalBusyo.load();
});
