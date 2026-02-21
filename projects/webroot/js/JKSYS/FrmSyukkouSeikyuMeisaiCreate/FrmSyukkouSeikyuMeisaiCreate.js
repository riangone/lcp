/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmSyukkouSeikyuMeisaiCreate");

JKSYS.FrmSyukkouSeikyuMeisaiCreate = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "JKSYS";
    me.id = "FrmSyukkouSeikyuMeisaiCreate";

    // 共通変数/定数
    me.prvMstYM = "";
    me.prvMonth_Summer = "";
    me.prvMonth_Winter = "";
    me.prvTermFrom_Summer = "";
    me.prvTermTo_Summer = "";
    me.prvTermFrom_Winter = "";
    me.prvTermTo_Winter = "";
    me.strJudgeTermFrom = "";
    me.strJudgeTermTo = "";

    //番号,名称データ
    me.syainName = [];
    me.intLockRowIdx = 0;
    // jqgrid
    me.grid_id = "#FrmSyukkouSeikyuMeisaiCreate_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/procGetSeikyuMeisaiData";
    // 最后点击的上一行的id
    me.upsel = "";
    // 最后点击的下一行的id
    me.nextsel = "";
    // 最后点击的行id
    me.lastsel = 0;
    me.lastCol = "";
    me.option = {
        rownumbers: true,
        rownumWidth: me.ratio === 1.5 ? 30 : 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };
    me.colModel = [
        {
            name: "chkUpdate",
            label: "更新対象",
            index: "chkUpdate",
            width: me.ratio === 1.5 ? 30 : 35,
            align: "center",
            sortable: false,
            formatter: function (_cellValue, options) {
                return (
                    "<input type='checkbox' class='" +
                    options.rowId +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate' onclick='chkDeleteCellClick(\"chkUpdate\"," +
                    options.rowId +
                    ")'/>"
                );
            },
        },
        {
            name: "chkDelete",
            label: "削除対象",
            index: "chkDelete",
            width: me.ratio === 1.5 ? 30 : 35,
            align: "center",
            sortable: false,
            formatter: function (_cellValue, options) {
                return (
                    "<input type='checkbox' class='" +
                    options.rowId +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete' onclick='chkDeleteCellClick(\"chkDelete\"," +
                    options.rowId +
                    ")'/>"
                );
            },
        },
        {
            name: "SYAIN_NO",
            label: "番号",
            index: "SYAIN_NO",
            width: 50,
            align: "center",
            sortable: false,
            editoptions: {
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            var foundNM = me.getName($.trim($(e.target).val()));
                            $(e.target)
                                .parent()
                                .next()
                                .text(
                                    foundNM
                                        ? me.clsComFnc.FncNv(
                                              foundNM["SYAIN_NM"]
                                          )
                                        : ""
                                );
                        },
                    },
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            var rowdata = $(me.grid_id).jqGrid(
                                "getRowData",
                                me.upsel
                            );
                            if (!rowdata["btnSyainSearch"] && key == 38) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                var foundNM = me.getName(
                                    $.trim($(e.target).val())
                                );
                                $(e.target)
                                    .parent()
                                    .next()
                                    .text(
                                        foundNM
                                            ? me.clsComFnc.FncNv(
                                                  foundNM["SYAIN_NM"]
                                              )
                                            : ""
                                    );
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NM",
            label: "名称",
            index: "SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "btnSyainSearch",
            label: "検索",
            index: "btnSyainSearch",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_CD",
            label: "出向先",
            index: "BUSYO_CD",
            width: 200,
            align: "left",
            sortable: false,
            editable: true,
            edittype: "select",
            formatter: "select",
            editoptions: {
                dataInit: function (elem) {
                    $(elem).css("width", "100%");
                },
                dataEvents: [
                    //enterイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //right
                            if (key == 39) {
                                $("#" + me.lastsel + "_SYUKKIN_NISSU").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            //left
                            if (key == 37) {
                                $("#" + me.lastsel + "_SYAIN_NO").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYUKKIN_NISSU",
            label: "(出勤)",
            index: "SYUKKIN_NISSU",
            width: 80,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            var str = $.trim($(e.target).val());
                            $(e.target).val(
                                Number(str)
                                    ? Number(str).toString()
                                    : $(e.target).val()
                            );
                            if (
                                str.replace(/0/g, "").replace(/-/g, "") == "" &&
                                str != "-" &&
                                str != ""
                            ) {
                                $(e.target).val("0");
                            }
                        },
                    },
                    //マウス左セルイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (key == 38 || key == 40) {
                                var str = $.trim($(e.target).val());
                                $(e.target).val(
                                    Number(str)
                                        ? Number(str).toString()
                                        : $(e.target).val()
                                );
                                if (
                                    str.replace(/0/g, "").replace(/-/g, "") ==
                                        "" &&
                                    str != "-" &&
                                    str != ""
                                ) {
                                    $(e.target).val("0");
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYUGYOU_NISSU",
            label: "(月)",
            index: "SYUGYOU_NISSU",
            width: 50,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            var str = $.trim($(e.target).val());
                            $(e.target).val(
                                Number(str)
                                    ? Number(str).toString()
                                    : $(e.target).val()
                            );
                            if (
                                str.replace(/0/g, "").replace(/-/g, "") == "" &&
                                str != "-" &&
                                str != ""
                            ) {
                                $(e.target).val("0");
                            }
                        },
                    },
                    //マウス左セルイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードに従って名前イベントを見つける
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40
                            ) {
                                var str = $.trim($(e.target).val());
                                $(e.target).val(
                                    Number(str)
                                        ? Number(str).toString()
                                        : $(e.target).val()
                                );
                                if (
                                    str.replace(/0/g, "").replace(/-/g, "") ==
                                        "" &&
                                    str != "-" &&
                                    str != ""
                                ) {
                                    $(e.target).val("0");
                                }
                            }
                        },
                    },
                ],
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.dtpYM",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.rowSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.btnAddRow",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.btnDelRow",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.btnAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyukkouSeikyuMeisaiCreate.btnModify",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //検索ボタンクリック
    $(".FrmSyukkouSeikyuMeisaiCreate.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //行追加ボタンクリック
    $(".FrmSyukkouSeikyuMeisaiCreate.btnAddRow").click(function () {
        me.btnAddRow_Click();
    });
    //行削除ボタンクリック
    $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").click(function () {
        me.btnDelRow_Click();
    });
    //条件変更ボタンクリック
    $(".FrmSyukkouSeikyuMeisaiCreate.btnModify").click(function () {
        me.btnModify_Click();
    });
    //実行ボタンクリック
    $(".FrmSyukkouSeikyuMeisaiCreate.btnAction").click(function () {
        me.btnAction_Click();
    });
    //全て更新Checkbox変更時
    $(".FrmSyukkouSeikyuMeisaiCreate.chkAllUpdate").change(function (e) {
        me.chkAllUpdate_CheckedChanged(e.target);
    });
    //全て削除Checkbox変更時
    $(".FrmSyukkouSeikyuMeisaiCreate.chkAllDelete").change(function (e) {
        me.chkAllDelete_CheckedChanged(e.target);
    });
    //年月blur:空=>初期値
    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmSyukkouSeikyuMeisaiCreate.dtpYM")) ==
            false
        ) {
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val(me.prvMstYM);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").trigger("focus");
                    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").trigger(
                            "focus"
                        );
                        $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").select();
                    }, 0);
                }
            }

            $(".FrmSyukkouSeikyuMeisaiCreate.btnSearch").button("disable");
            $(".FrmSyukkouSeikyuMeisaiCreate.btnModify").button("disable");
        } else {
            $(".FrmSyukkouSeikyuMeisaiCreate.btnSearch").button("enable");
            $(".FrmSyukkouSeikyuMeisaiCreate.btnModify").button("enable");
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.procInitFormCtrl(true);
    };
    /*
	 '**********************************************************************
	 '処 理 名：jqgridの初期化
	 '関 数 名：fncJqgrid
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"]) {
            $(".FrmSyukkouSeikyuMeisaiCreate button").button("disable");

            me.clsComFnc.FncMsgBox("E9999", result["error"]);
            return;
        }

        me.intLockRowIdx = result["records"] - 1;

        if (!result["isExistMeisaiData"]) {
            $(".FrmSyukkouSeikyuMeisaiCreate.chkAllUpdate").prop(
                "checked",
                !result["isExistMeisaiData"]
            );
            $(".FrmSyukkouSeikyuMeisaiCreate.chkAllDelete").prop(
                "checked",
                false
            );

            $(".FrmSyukkouSeikyuMeisaiCreate.lblState").hide();
        } else {
            $(".FrmSyukkouSeikyuMeisaiCreate.lblState").show();
        }

        //SPREADの初期化
        me.procInitSpreadSheet(result["isExistMeisaiData"]);

        var enableFlg =
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val() < me.prvMstYM
                ? "disable"
                : "enable";
        $(".FrmSyukkouSeikyuMeisaiCreate.btnAddRow").button(enableFlg);
        $(".FrmSyukkouSeikyuMeisaiCreate.btnAction").button(enableFlg);

        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllUpdate").attr(
            "disabled",
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val() < me.prvMstYM
        );
        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllDelete").attr(
            "disabled",
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val() < me.prvMstYM
        );

        $(".FrmSyukkouSeikyuMeisaiCreate.btnModify").button("enable");
    };
    me.procInitSpreadSheet = function (isExistMeisaiData) {
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var editableFlg =
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val() < me.prvMstYM;
        ids.forEach(function (element) {
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).prop("checked", !isExistMeisaiData);
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).attr("disabled", editableFlg);
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
            ).attr("disabled", editableFlg);
        });

        // 初期第一行選択
        $(me.grid_id).jqGrid("setSelection", 0);
    };
    me.fncJqgrid = function (comboboxData, isFormLoad) {
        var data = {
            MstYM: $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val(),
        };

        if (isFormLoad) {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                "",
                "",
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                me.ratio === 1.5 ? 683 : 710
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                me.ratio === 1.5 ? 220 : 338
            );

            //出向者Comboboxのデータ取得
            var selectVal = ":;";
            if (comboboxData) {
                comboboxData.forEach(function (element, index) {
                    selectVal +=
                        "" + element.KUBUN_CD + ":" + element.BUSYO_NM + "";
                    if (index != comboboxData.length - 1) {
                        selectVal += ";";
                    }
                });
            }
            $(me.grid_id).setColProp("BUSYO_CD", {
                editoptions: {
                    value: selectVal,
                },
            });

            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "SYAIN_NO",
                        numberOfColumns: 3,
                        titleText: "社員",
                    },
                    {
                        startColumnName: "SYUKKIN_NISSU",
                        numberOfColumns: 2,
                        titleText: "日割日数",
                    },
                ],
            });
            //edit cell
            $(me.grid_id).jqGrid("setGridParam", {
                beforeSelectRow: function (rowId, e) {
                    var $td = $(e.target).closest("tr.jqgrow>td");
                    if ($td && $td.length > 0) {
                        var iCol = $.jgrid.getCellIndex($td[0]),
                            colModel = $(this).jqGrid(
                                "getGridParam",
                                "colModel"
                            ),
                            targetCell = colModel[iCol];
                        if (
                            targetCell.name == "SYAIN_NO" ||
                            targetCell.name == "btnSyainSearch"
                        ) {
                            if (rowId > me.intLockRowIdx) {
                                return true;
                            } else {
                                if (me.lastCol) {
                                    var selNextId =
                                        "#" + me.lastsel + "_" + me.lastCol;
                                    setTimeout(() => {
                                        $(selNextId).trigger("focus");
                                        $(selNextId).select();
                                    }, 0);
                                }
                                return false;
                            }
                        } else if (!targetCell.editable) {
                            if (me.lastCol) {
                                var selNextId =
                                    "#" + me.lastsel + "_" + me.lastCol;
                                setTimeout(() => {
                                    $(selNextId).trigger("focus");
                                    $(selNextId).select();
                                }, 0);
                            }
                            return false;
                        }
                    }

                    return true;
                },
                //選択行の修正画面を呼び出す
                onSelectRow: function (rowId, _status, e) {
                    if (
                        $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val() <
                        me.prvMstYM
                    ) {
                        return;
                    }

                    me.lastsel = rowId;

                    //获得所有行的ID数组
                    var ids = $(me.grid_id).jqGrid("getDataIDs");
                    ids.forEach(function (element) {
                        $(me.grid_id).jqGrid("saveRow", element);
                    });

                    //行削除ボタン表示/非表示
                    if (rowId <= me.intLockRowIdx) {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: false,
                        });
                        me.lastCol = "BUSYO_CD";
                        $("#" + me.lastsel + "_" + me.lastCol).trigger("focus");
                        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").hide();
                        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button(
                            "disable"
                        );
                    } else {
                        $(me.grid_id).setColProp("SYAIN_NO", {
                            editable: true,
                        });
                        me.lastCol = "SYAIN_NO";
                        $("#" + me.lastsel + "_" + me.lastCol).trigger("focus");
                        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").show();
                        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button(
                            "enable"
                        );
                    }

                    $(me.grid_id).jqGrid("editRow", rowId, {
                        focusField: false,
                    });

                    if (e) {
                        if (e.target && e.target.name) {
                            me.lastCol = e.target.name;
                        } else {
                            var $td = $(e.target).closest("tr.jqgrow>td");
                            if ($td && $td.length > 0) {
                                var iCol = $.jgrid.getCellIndex($td[0]),
                                    colModel = $(this).jqGrid(
                                        "getGridParam",
                                        "colModel"
                                    ),
                                    targetCell = colModel[iCol];
                                me.lastCol = targetCell.name;
                            }
                        }
                        setTimeout(() => {
                            $("#" + me.lastsel + "_" + me.lastCol).trigger(
                                "focus"
                            );
                            $("#" + me.lastsel + "_" + me.lastCol).select();
                        }, 0);
                    }

                    var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        me.lastsel
                    );
                    if (up_next_sel && up_next_sel.length == 2) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    }
                },
            });
            $(me.grid_id).jqGrid("bindKeys");
            $(me.grid_id).unbind("contextmenu");
        } else {
            $(me.grid_id).jqGrid("clearGridData");
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォーム初期化
	 '関 数 名：procInitFormCtrl
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procInitFormCtrl = function (isFormLoad) {
        me.data = {
            isFormLoad: isFormLoad,
        };
        me.url = me.sys_id + "/" + me.id + "/procGetJinjiCtrlMstYM";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmSyukkouSeikyuMeisaiCreate").ympicker("disable");
                $(".FrmSyukkouSeikyuMeisaiCreate").attr("disabled", true);
                $(".FrmSyukkouSeikyuMeisaiCreate button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (isFormLoad) {
                me.prvMonth_Summer = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["KAKI_BONUS_MONTH"]
                );
                me.prvMonth_Winter = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["TOUKI_BONUS_MONTH"]
                );
                me.prvTermFrom_Summer = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["KAKI_BONUS_START_MT"]
                );
                me.prvTermTo_Summer = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["KAKI_BONUS_END_MT"]
                );
                me.prvTermFrom_Winter = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["TOUKI_BONUS_START_MT"]
                );
                me.prvTermTo_Winter = me.clsComFnc.FncNv(
                    result["data"]["MstYM"]["TOUKI_BONUS_END_MT"]
                );

                if (result["data"]["SYORI_YM"]) {
                    //対象年月値設定
                    me.prvMstYM = me.clsComFnc.FncNv(
                        result["data"]["SYORI_YM"]
                    );
                    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val(me.prvMstYM);
                }
            }

            me.syainName = result["data"]["syainName"]
                ? result["data"]["syainName"]
                : [];

            //ｺﾝﾄﾛｰﾙの制御
            $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").ympicker("disable");
            $(".FrmSyukkouSeikyuMeisaiCreate.btnSearch").button("disable");

            //jqgridの初期化
            me.fncJqgrid(result["data"]["comboboxData"], isFormLoad);
        };
        me.ajax.send(me.url, me.data, 0);

        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").hide();
        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("disable");
    };
    /*
	 '**********************************************************************
	 '処 理 名：検索ボタンクリック
	 '関 数 名：btnSearch_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnSearch_Click = function () {
        me.procInitFormCtrl(false);
    };
    /*
	 '**********************************************************************
	 '処 理 名：削除チェックボックス変更時
	 '関 数 名：chkDeleteCellClick
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    chkDeleteCellClick = function (flag, element) {
        $(me.grid_id).jqGrid("setSelection", element);

        if (flag == "chkDelete") {
            var chkDeleteFlag = $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
            ).is(":checked");
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).prop("checked", chkDeleteFlag);
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).attr("disabled", chkDeleteFlag);
        }
        if (element <= me.intLockRowIdx) {
            $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").hide();
            $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("disable");
        } else {
            $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").show();
            $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("enable");
        }
    };
    //セルボタンクリック
    rowSearch_Click = function (rowId) {
        var $rootDiv = $(".FrmSyukkouSeikyuMeisaiCreate.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmSyainSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNO").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "KUJYUNBI").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $SYAINNO = $rootDiv.parent().find("#SYAINNO");
        var $SYAINNM = $rootDiv.parent().find("#SYAINNM");
        var $KUJYUNBI = $rootDiv.parent().find("#KUJYUNBI");

        var dtpYM = $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val();
        var year = dtpYM.substring(0, 4);
        var month = dtpYM.substring(4, 6);
        //构造一个日期对象：
        var day = new Date(year, month, 0);
        //获取当月天数：
        var daycount = day.getDate();

        $KUJYUNBI.val(dtpYM + daycount);

        $("#FrmSyainSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 650,
            width: 790,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $SYAINNO.hide();
                $SYAINNM.hide();
                $KUJYUNBI.hide();
            },
            close: function () {
                me.SYAINNO = $SYAINNO.html();
                me.SYAINNM = $SYAINNM.html();

                if ($RtnCD.html() == 1) {
                    $("#" + rowId + "_SYAIN_NO").val(me.SYAINNO);
                    $(me.grid_id).jqGrid(
                        "setCell",
                        rowId,
                        "SYAIN_NM",
                        me.SYAINNM
                    );
                }

                $RtnCD.remove();
                $SYAINNO.remove();
                $SYAINNM.remove();
                $KUJYUNBI.remove();
                $("#FrmSyainSearchDialogDiv").remove();
                $("#" + rowId + "_SYAIN_NO").select();
            },
        });

        var url = me.sys_id + "/" + "FrmJKSYSSyainSearch";
        me.ajax.receive = function (result) {
            $("#FrmSyainSearchDialogDiv").html(result);
            $("#FrmSyainSearchDialogDiv").dialog(
                "option",
                "title",
                "社員番号検索"
            );
            $("#FrmSyainSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, "", 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：行追加ボタンクリック
	 '関 数 名：btnAddRow_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnAddRow_Click = function () {
        //获得所有行的ID数组
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //获得当前最大行号（数据编号）
            rowid = Math.max.apply(Math, ids) + 1;
        }

        //获得新添加行的行号（数据编号）
        me.lastsel = rowid;
        ids.forEach(function (element) {
            $(me.grid_id).jqGrid("saveRow", element);
        });
        var selectVal =
            "<button onclick=\"rowSearch_Click('" +
            me.lastsel +
            "')\" class=\"FrmSyukkouSeikyuMeisaiCreate rowSearch Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>検索</button>";
        var data = {
            btnSyainSearch: selectVal,
            chkUpdate:
                "<input type='checkbox' checked='checked' onclick='chkDeleteCellClick(\"chkUpdate\"," +
                me.lastsel +
                ")'/>",
            chkDelete:
                "<input type='checkbox' onclick='chkDeleteCellClick(\"chkDelete\"," +
                me.lastsel +
                ")'/>",
        };
        // 插入一行
        $(me.grid_id).jqGrid("addRowData", me.lastsel, data);
        $(me.grid_id).jqGrid("editRow", me.lastsel, true);

        $(
            "." + me.lastsel + "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
        ).prop("checked", true);

        $(me.grid_id).jqGrid("setSelection", me.lastsel);

        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").show();
        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("enable");
    };
    /*
	 '**********************************************************************
	 '処 理 名：行削除ボタンクリック
	 '関 数 名：btnDelRow_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnDelRow_Click = function () {
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }
        if (me.lastsel <= me.intLockRowIdx) {
            me.clsComFnc.FncMsgBox(
                "E9999",
                "追加した行以外を削除することは出来ません！初期表示されている社員を削除する場合は削除対象にチェックを入れ、実行ボタンをクリックして下さい。"
            );
            return;
        }

        //選択行の削除
        $(me.grid_id).jqGrid("delRowData", me.lastsel);
        if (me.nextsel == undefined) {
            $(me.grid_id).jqGrid("setSelection", me.upsel);
        }
        $(me.grid_id).jqGrid("setSelection", me.nextsel);

        if (me.lastsel || me.lastsel == 0) {
            if (me.lastsel <= me.intLockRowIdx) {
                $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").hide();
                $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("disable");
            } else {
                $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").show();
                $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("enable");
            }
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：条件変更ボタンクリック
	 '関 数 名：btnModify_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnModify_Click = function () {
        $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").ympicker("enable");
        $(".FrmSyukkouSeikyuMeisaiCreate.btnSearch").button("enable");

        $(me.grid_id).jqGrid("clearGridData");

        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllUpdate").prop("checked", false);
        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllDelete").prop("checked", false);
        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllUpdate").attr(
            "disabled",
            "disabled"
        );
        $(".FrmSyukkouSeikyuMeisaiCreate.chkAllDelete").attr(
            "disabled",
            "disabled"
        );
        $(".FrmSyukkouSeikyuMeisaiCreate.btnAddRow").button("disable");
        $(".FrmSyukkouSeikyuMeisaiCreate.btnAction").button("disable");

        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").hide();
        $(".FrmSyukkouSeikyuMeisaiCreate.btnDelRow").button("disable");
        $(".FrmSyukkouSeikyuMeisaiCreate.lblState").hide();
    };
    /*
	 '**********************************************************************
	 '処 理 名：実行ボタンクリック
	 '関 数 名：btnAction_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.btnAction_Click = function () {
        var dtpYM = $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val();
        //１．ボーナス評価期間を変数にセット
        me.procSetTerm(dtpYM);

        me.url = me.sys_id + "/" + me.id + "/fncCheckData";
        me.data = {
            dtpYM: dtpYM,
            summer: me.prvMonth_Summer,
            winter: me.prvMonth_Winter,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //２．存在チェックを行います
            if (!result["result"]) {
                if (result["error"] != "W9999") {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var msg = "";
                var ymd =
                    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM")
                        .val()
                        .substring(0, 4) +
                    "/" +
                    $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM")
                        .val()
                        .substring(4, 6);
                switch (result["row"]) {
                    case "1":
                        msg =
                            ymd +
                            "月分の支給データが存在しません。給与データの取込を行ってください。";
                        break;
                    case "2":
                        msg =
                            ymd +
                            "月分の事業主データが存在しません。給与データの取込を行ってください。";
                        break;
                    case "3":
                        msg =
                            "夏季賞与データが存在しません。" +
                            ymd +
                            "月は賞与支給月ですので、賞与データの取込を行ってください。";
                        break;
                    case "4":
                        msg =
                            "冬季賞与データが存在しません。" +
                            ymd +
                            "月は賞与支給月ですので、賞与データの取込を行ってください。";
                        break;
                }
                me.clsComFnc.FncMsgBox("W9999", msg);
                return;
            }
            //３．入力チェックを行います
            me.procInputCheck();
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：ボーナス評価期間を変数にセット
	 '関 数 名：procSetTerm
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procSetTerm = function (dtpYM) {
        var month = dtpYM.substring(4, 6);
        if (month == me.prvMonth_Summer) {
            //Ⅰ．画面．対象年月(月）＝変数．夏季ボーナス月の場合
            //	   ボーナス評価期間開始年月を求める
            if (me.prvTermFrom_Summer >= me.prvMonth_Summer) {
                //・変数．夏季ボーナス開始計算期間＞=変数．夏季ボーナス月の場合
                //      変数．ボーナス評価期間開始＝画面．対象年月(年)－1年 & 夏季ボーナス開始計算期間
                me.strJudgeTermFrom =
                    dtpYM.substring(0, 4) - 1 + "/" + me.prvTermFrom_Summer;
            } else {
                //・変数．夏季ボーナス開始計算期間<変数．夏季ボーナス月の場合
                //      変数．ボーナス評価期間開始＝画面．対象年月（年) & 夏季ボーナス開始計算期間
                me.strJudgeTermFrom =
                    dtpYM.substring(0, 4) + "/" + me.prvTermFrom_Summer;
            }

            //ボーナス評価期間終了年月を求める
            if (me.prvTermTo_Summer >= me.prvMonth_Summer) {
                //・変数．夏季ボーナス終了計算期間＞=変数．夏季ボーナス月の場合
                //      変数．ボーナス評価期間終了＝画面．対象年月(年)－1年 & 夏季ボーナス終了計算期間
                me.strJudgeTermTo =
                    dtpYM.substring(0, 4) - 1 + "/" + me.prvTermTo_Summer;
            } else {
                //・変数．夏季ボーナス終了計算期間<変数．夏季ボーナス月の場合
                //      変数．ボーナス評価期間終了＝画面．対象年月（年) & 夏季ボーナス終了計算期間
                me.strJudgeTermTo =
                    dtpYM.substring(0, 4) + "/" + me.prvTermTo_Summer;
            }
        } else if (month == me.prvMonth_Winter) {
            //Ⅱ．画面．対象年月(月）＝変数．冬季ボーナス月の場合
            //          ボーナス評価期間開始年月を求める
            if (me.prvTermFrom_Winter >= me.prvMonth_Winter) {
                //・変数．冬季ボーナス開始計算期間＞=変数．冬季ボーナス月の場合
                //      変数．ボーナス評価期間開始＝画面．対象年月(年)－1年 & 冬季ボーナス開始計算期間
                me.strJudgeTermFrom =
                    dtpYM.substring(0, 4) - 1 + "/" + me.prvTermFrom_Winter;
            } else {
                //・変数．冬季ボーナス開始計算期間<変数．冬季ボーナス月の場合
                //      変数．ボーナス評価期間開始＝画面．対象年月（年) & 冬季ボーナス開始計算期間
                me.strJudgeTermFrom =
                    dtpYM.substring(0, 4) + "/" + me.prvTermFrom_Winter;
            }
            //ボーナス評価期間終了年月を求める
            if (me.prvTermTo_Summer >= me.prvMonth_Summer) {
                //・変数．冬季ボーナス終了計算期間＞=変数．冬季ボーナス月の場合
                //      変数．ボーナス評価期間終了＝画面．対象年月(年)－1年 & 冬季ボーナス終了計算期間
                me.strJudgeTermTo =
                    dtpYM.substring(0, 4) - 1 + "/" + me.prvTermTo_Winter;
            } else {
                //・変数．冬季ボーナス終了計算期間<変数．冬季ボーナス月の場合
                //      変数．ボーナス評価期間終了＝画面．対象年月（年) & 冬季ボーナス終了計算期間
                me.strJudgeTermTo =
                    dtpYM.substring(0, 4) + "/" + me.prvTermTo_Winter;
            }
        } else {
            if (me.prvMonth_Summer < me.prvMonth_Winter) {
                if (dtpYM >= dtpYM.substring(0, 4) + me.prvMonth_Winter) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) + "/" + me.prvMonth_Winter;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) + me.prvMonth_Summer
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) + "/" + me.prvMonth_Summer;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) - 1 + me.prvMonth_Winter
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) - 1 + me.prvMonth_Winter;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) - 1 + me.prvMonth_Summer
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) - 1 + me.prvMonth_Summer;
                }
            } else {
                if (dtpYM >= dtpYM.substring(0, 4) + me.prvMonth_Summer) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) + "/" + me.prvMonth_Summer;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) + me.prvMonth_Winter
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) + "/" + me.prvMonth_Winter;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) - 1 + me.prvMonth_Summer
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) - 1 + me.prvMonth_Summer;
                } else if (
                    dtpYM >=
                    dtpYM.substring(0, 4) - 1 + me.prvMonth_Winter
                ) {
                    me.strJudgeTermFrom =
                        dtpYM.substring(0, 4) - 1 + me.prvMonth_Winter;
                }
            }
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：５．入力チェック
	 '関 数 名：procInputCheck
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procInputCheck = function () {
        //①	更新対象にチェックが1箇所も入っていない場合、エラー
        var isChecked = false;
        var isDeleteChecked = false;

        //获取当前显示的数据
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowDatas = $(me.grid_id).jqGrid("getRowData");

        var reg = /^-?[0-9]\.?[0-9]*$/;
        var pattern = new RegExp(reg);

        for (var intIdx = 0; intIdx < rowDatas.length; intIdx++) {
            var rowID = ids[intIdx];
            var flag = $(
                "." + rowID + "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).is(":checked");
            if (flag) {
                isChecked = true;
                isDeleteChecked = $(
                    "." +
                        rowID +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
                ).is(":checked");

                //②	（入力領域)．更新対象欄にチェックが入っている場合
                //    Ⅰ．（入力領域)．削除対象欄にチェックが入っている場合
                //        ※「入力チェック仕様１」の№1と№3のチェックを行う
                //    Ⅱ．（入力領域)．削除対象欄にチェックが入っていない場合
                //        ※「入力チェック仕様１」の全てのチェックを行う	（入力領域)．更新対象欄にチェックが入っている場合
                if (me.clsComFnc.FncNv(rowDatas[intIdx]["SYAIN_NO"]) == "") {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid("setSelection", rowID);
                    };
                    me.clsComFnc.FncMsgBox("W0001", "社員番号");
                    return;
                }

                for (
                    var intIdx2 = intIdx + 1;
                    intIdx2 < rowDatas.length;
                    intIdx2++
                ) {
                    if (
                        $(
                            "." +
                                ids[intIdx2] +
                                "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
                        ).is(":checked")
                    ) {
                        if (
                            me.clsComFnc.FncNv(rowDatas[intIdx]["SYAIN_NO"]) ==
                            me.clsComFnc.FncNv(rowDatas[intIdx2]["SYAIN_NO"])
                        ) {
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $(me.grid_id).jqGrid("setSelection", rowID);
                            };
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "社員番号が重複しています。(" +
                                    me.clsComFnc.FncNv(
                                        rowDatas[intIdx]["SYAIN_NO"]
                                    ) +
                                    ")"
                            );
                            return;
                        }
                    }
                }

                if (
                    !isDeleteChecked &&
                    me.clsComFnc.FncNv(rowDatas[intIdx]["BUSYO_CD"]) == ""
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid("setSelection", rowID);
                    };
                    me.clsComFnc.FncMsgBox("W9999", "出向先を選択して下さい。");
                    return;
                }

                if (!me.getName($.trim(rowDatas[intIdx]["SYAIN_NO"]))) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        //該当データ無し
                        $(me.grid_id).jqGrid("setSelection", rowID);
                    };
                    me.clsComFnc.FncMsgBox("W0008", "社員番号");
                    return;
                }

                if (
                    !isDeleteChecked &&
                    me.clsComFnc.FncNv(rowDatas[intIdx]["SYUKKIN_NISSU"]) !=
                        "" &&
                    !pattern.test(rowDatas[intIdx]["SYUKKIN_NISSU"])
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid("setSelection", rowID);
                    };
                    me.clsComFnc.FncMsgBox("W0017", "日割日数(出勤)には数値");
                    return;
                }

                if (
                    !isDeleteChecked &&
                    me.clsComFnc.FncNv(rowDatas[intIdx]["SYUGYOU_NISSU"]) !=
                        "" &&
                    !pattern.test(rowDatas[intIdx]["SYUGYOU_NISSU"])
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(me.grid_id).jqGrid("setSelection", rowID);
                    };
                    me.clsComFnc.FncMsgBox("W0017", "日割日数(月)には数値");
                    return;
                }

                if (
                    !isDeleteChecked &&
                    (me.clsComFnc.FncNv(rowDatas[intIdx]["SYUKKIN_NISSU"]) !=
                        "" ||
                        me.clsComFnc.FncNv(rowDatas[intIdx]["SYUGYOU_NISSU"]) !=
                            "")
                ) {
                    if (
                        me.clsComFnc.FncNv(rowDatas[intIdx]["SYUKKIN_NISSU"]) ==
                        ""
                    ) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(me.grid_id).jqGrid("setSelection", rowID);
                        };
                        me.clsComFnc.FncMsgBox("W0001", "日割日数(出勤)");
                        return;
                    }
                    if (
                        me.clsComFnc.FncNv(rowDatas[intIdx]["SYUGYOU_NISSU"]) ==
                        ""
                    ) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(me.grid_id).jqGrid("setSelection", rowID);
                        };
                        me.clsComFnc.FncMsgBox("W0001", "日割日数(月)");
                        return;
                    } else if (
                        me.clsComFnc.FncNv(rowDatas[intIdx]["SYUGYOU_NISSU"]) ==
                        "0"
                    ) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(me.grid_id).jqGrid("setSelection", rowID);
                        };
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "日割日数（月)に0を入力することは出来ません"
                        );
                        return;
                    }
                }
            }
        }

        if (!isChecked) {
            //メッセージコード：W9999 %1="更新対象が存在しません。請求明細情報を生成する対象者の更新対象欄にチェックを入れてください"
            me.clsComFnc.FncMsgBox(
                "W9999",
                "更新対象が存在しません。請求明細情報を生成する対象者の更新対象欄にチェックを入れてください。"
            );
            return;
        }
        //５．出向社員請求明細情報を生成します
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.procCreateSeikyuMeisai;
        //４．実行確認メッセージを表示。
        me.clsComFnc.FncMsgBox("QY005");
    };
    /*
	 '**********************************************************************
	 '処 理 名：５．出向社員請求明細情報を生成します
	 '関 数 名：procCreateSeikyuMeisai
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.procCreateSeikyuMeisai = function () {
        me.url = me.sys_id + "/" + me.id + "/procCreateSeikyuMeisai";
        var dtpYM = $(".FrmSyukkouSeikyuMeisaiCreate.dtpYM").val();
        var all_jqgrid_data = [];

        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowDatas = $(me.grid_id).jqGrid("getRowData");

        $.each(rowDatas, function (index, value) {
            var chkDeleteFlag = $(
                "." +
                    ids[index] +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
            ).is(":checked");
            var chkUpdateFlag = $(
                "." +
                    ids[index] +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).is(":checked");

            if (chkUpdateFlag) {
                value["chkUpdate"] = chkUpdateFlag;
                value["chkDelete"] = chkDeleteFlag;
                all_jqgrid_data.push(value);
            }
        });

        me.data = {
            dtpYM: dtpYM,
            prvMonth_Summer: me.prvMonth_Summer,
            prvMonth_Winter: me.prvMonth_Winter,
            strJudgeTermFrom: me.strJudgeTermFrom.replace("/", ""),
            strJudgeTermTo: me.strJudgeTermTo.replace("/", ""),
            data: all_jqgrid_data,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"]) {
                me.btnModify_Click();
                //完了メッセージ表示
                me.clsComFnc.FncMsgBox("I0008");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：全て更新Checkbox変更時
	 '関 数 名：chkAllUpdate_CheckedChanged
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.chkAllUpdate_CheckedChanged = function (targetCtrl) {
        //获取当前显示的数据
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var isChecked = $(targetCtrl).is(":checked");
        allIds.forEach(function (element) {
            var chkDeleteFlag = $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
            ).is(":checked");
            $(
                "." +
                    element +
                    "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
            ).prop("checked", isChecked || chkDeleteFlag);
        });
    };
    /*
	 '**********************************************************************
	 '処 理 名：全て削除Checkbox変更時
	 '関 数 名：chkAllDelete_CheckedChanged
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.chkAllDelete_CheckedChanged = function (targetCtrl) {
        //获取当前显示的数据
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var isChecked = $(targetCtrl).is(":checked");

        allIds.forEach(function (element) {
            if (isChecked) {
                $(
                    "." +
                        element +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
                ).prop("checked", isChecked);
                $(
                    "." +
                        element +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
                ).prop("checked", isChecked);

                $(
                    "." +
                        element +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
                ).attr("disabled", true);
            } else {
                var chkDeleteFlag = $(
                    "." +
                        element +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
                ).is(":checked");
                if (chkDeleteFlag) {
                    $(
                        "." +
                            element +
                            "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
                    ).prop("checked", !chkDeleteFlag);
                    $(
                        "." +
                            element +
                            "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkUpdate"
                    ).attr("disabled", !chkDeleteFlag);
                }
                $(
                    "." +
                        element +
                        "_FrmSyukkouSeikyuMeisaiCreate_sprList_chkDelete"
                ).prop("checked", isChecked);
            }
        });
    };
    /*
	 '**********************************************************************
	 '処 理 名：番号から名称取得
	 '関 数 名：getName
	 '引    数：e(当前选中的单元格对象)
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getName = function (val) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(val);
        if (me.syainName && me.syainName.length > 0) {
            var foundNM_array = me.syainName.filter(function (element) {
                return me.clsComFnc.FncNv(element["SYAIN_NO"]) == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        return foundNM;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyukkouSeikyuMeisaiCreate =
        new JKSYS.FrmSyukkouSeikyuMeisaiCreate();
    o_JKSYS_FrmSyukkouSeikyuMeisaiCreate.load();
});
