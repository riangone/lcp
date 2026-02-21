/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                                                   担当
 * 20201117           bug                     社員権限管理マスタのダイアログのjqGridの第一列には、          ZhangBoWen
 *                                            tab+shiftを押して、blurイベントを行わないです。
 * * ------------------------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmAuthCtlEdit");

KRSS.FrmAuthCtlEdit = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.sys_id = "KRSS";
    me.id = "FrmAuthCtlEdit";

    me.BusyoArrFalse = new Array();
    me.BusyoArrTrue = new Array();
    me.lastsel = "";

    me.is_cmdUpdate_click = false;
    me.Last_BUSYO_CD = "";

    me.delFlag = false;
    //プロパティ社員NO
    me.strSYAINNO = "";
    //プロパティ社員名
    me.strSYAINNM = "";
    //プロパティ配属開始日
    me.strSTARTDATE = "";
    //プロパティ配属終了日
    me.strENDDATEE = "";
    //プロパティ部署コード
    me.strBUSYOCD = "";
    //プロパティ部署名
    me.strBUSYONM = "";
    //FrmAuthCtl画面を戻る
    me.bolResult = false;
    // ========== 変数 end ==========

    me.colModel1 = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 90,
            sortable: false,
            editable: true,
            editoptions: {
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var totalrow = $(
                                "#FrmAuthCtlEdit_sprCostList"
                            ).jqGrid("getGridParam", "records");
                            var key = e.charCode || e.keyCode;
                            //when Japanese input method,e.keyCode==229.
                            if (key == 229) {
                                return false;
                            }
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                if (parseInt(me.lastsel) == 1) {
                                    //20201117 zhangbowen add S
                                    me.check_busyonm();
                                    //20201117 zhangbowen add E
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    //20201117 zhangbowen del S
                                    //$('#FrmAuthCtlEdit_sprCostList').jqGrid('saveRow', me.lastsel);
                                    //20201117 zhangbowen del E
                                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) - 1,
                                        true
                                    );
                                    $(
                                        "#" + me.lastsel + "_BUSYO_SELECT"
                                    ).trigger("focus");
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter&Tab
                                $("#" + me.lastsel + "_BUSYO_SELECT").trigger(
                                    "focus"
                                );
                                //添加行
                                var selrow = $(
                                    "#FrmAuthCtlEdit_sprCostList"
                                ).jqGrid("getGridParam", "selrow");
                                if (
                                    selrow == totalrow &&
                                    $("#" + selrow + "_BUSYO_CD").val() != ""
                                ) {
                                    var columns = {
                                        BUSYO_CD: "",
                                        BUSYO_NM: "",
                                    };
                                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                                        "addRowData",
                                        parseInt(totalrow) + 1,
                                        columns
                                    );
                                }
                                //check_busyonm
                                me.check_busyonm();

                                me.sprMeisei_reshow();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_SELECT",
            label: "検索",
            index: "BUSYO_SELECT",
            width: 62,
            align: "center",
            sortable: true,
            editable: true,
            edittype: "button",
            editoptions: {
                value: "検索",
                class: "width",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //shift+tab
                            if (e.shiftKey && key == 9) {
                                $("#" + me.lastsel + "_BUSYO_CD").trigger(
                                    "focus"
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            var totalrow = $(
                                "#FrmAuthCtlEdit_sprCostList"
                            ).jqGrid("getGridParam", "records");
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter and tab
                                if (me.lastsel != totalrow) {
                                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                                        "setSelection",
                                        parseInt(me.lastsel) + 1,
                                        true
                                    );
                                    $("#" + me.lastsel + "_BUSYO_CD").trigger(
                                        "focus"
                                    );
                                }
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "click",
                        fn: function () {
                            $("<div></div>")
                                .attr("id", "FrmBusyoSearchDialogDiv")
                                .insertAfter($("#KRSS_FrmAuthCtlEdit"));
                            $("<div></div>")
                                .attr("id", "BUSYOCD")
                                .insertAfter($("#KRSS_FrmAuthCtlEdit"));
                            $("<div></div>")
                                .attr("id", "BUSYONM")
                                .insertAfter($("#KRSS_FrmAuthCtlEdit"));
                            $("<div></div>")
                                .attr("id", "RtnCD")
                                .insertAfter($("#KRSS_FrmAuthCtlEdit"));

                            $("#FrmBusyoSearchDialogDiv").dialog({
                                autoOpen: false,
                                modal: true,
                                height: 680,
                                width: 550,
                                resizable: false,
                                open: function () {
                                    $("#RtnCD").hide();
                                    $("#BUSYONM").hide();
                                    $("#BUSYOCD").hide();
                                },
                                close: function () {
                                    var searchedBusyoCD = $("#BUSYOCD").html();
                                    var searchedBusyoNM = $("#BUSYONM").html();
                                    if (searchedBusyoCD != "") {
                                        $("#" + me.lastsel + "_BUSYO_CD").val(
                                            searchedBusyoCD
                                        );
                                    }
                                    if (searchedBusyoNM != "") {
                                        $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                                            "setCell",
                                            me.lastsel,
                                            "BUSYO_NM",
                                            searchedBusyoNM
                                        );
                                    }
                                    $("#RtnCD").remove();
                                    $("#BUSYONM").remove();
                                    $("#BUSYOCD").remove();
                                    $("#FrmBusyoSearchDialogDiv").remove();
                                    me.sprMeisei_reshow();
                                },
                            });

                            var frmId = "FrmBusyoSearch";
                            var url = "R4K" + "/" + frmId;
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
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 210,
            sortable: false,
        },
    ];

    me.colModel2 = [
        {
            name: "CHECK",
            label: "チェック",
            index: "CHECK",
            width: 70,
            sortable: false,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
            align: "center",
        },
        {
            name: "HAUTH_ID",
            label: "権限ID",
            index: "HAUTH_ID",
            width: 55,
            sortable: false,
        },
        {
            name: "HAUTH_NM",
            label: "権限名",
            index: "HAUTH_NM",
            width: 140,
            sortable: false,
        },
        {
            name: "MEMO",
            label: "備考",
            index: "MEMO",
            width: 152,
            sortable: false,
        },
        {
            name: "CHECK1",
            label: "隠しチェック",
            index: "CHECK1",
            hidden: true,
        },
        {
            name: "CREATE_DATE",
            label: "作成日付",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    me.columns1 = {
        BUSYO_CD: "",
        BUSYO_NM: "",
    };
    me.columns2 = {
        CHECK: "",
        HAUTH_ID: "",
        HAUTH_NM: "",
        MEMO: "",
        CHECK1: "",
        CREATE_DATE: "",
    };

    me.controls.push({
        id: ".KRSS.FrmAuthCtlEdit.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmAuthCtlEdit.cmdBack",
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

    $("#FrmAuthCtlEdit_sprCostList").jqGrid({
        datatype: "local",
        height: 260,
        colModel: me.colModel1,
        rownumbers: true,
        onSelectRow: function (rowId, _status, e) {
            var focusIndex =
                typeof e != "undefined"
                    ? e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex
                    : false;
            if (typeof e != "undefined") {
                //編集可能なセルをクリック、上下キー
                var cellIndex = e.target.cellIndex;
                if (cellIndex != 0) {
                    if (rowId && rowId !== me.lastsel) {
                        var last = me.lastsel;
                        //check_busyonm
                        me.check_busyonm();
                        $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        //隐藏jqGrid的检索button
                        $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                            "setCell",
                            me.lastsel,
                            "BUSYO_SELECT",
                            " "
                        );
                        me.lastsel = rowId;
                    }
                    $("#FrmAuthCtlEdit_sprCostList").jqGrid("editRow", rowId, {
                        /* keys:
                         * 説明:旧システムの「editRow」方法の2番目のパラメータと同じに設定してください
                         * 値＝true:Enter/Escキー:行を保存(saveRow)にする
                         * 値＝false(default):Enter/Escキー:何もしない
                         */
                        keys: false,
                        /* focusField:
                         * 説明:セル内のinputをfocus
                         * 値＝true(default):編集可能な最初の列のセルをフォーカス
                         *   注意:第0列をフォーカスしたいなら、「0」ではなく、「true」を設定してください
                         * 値＝false:何もしない
                         * 値＝数値:この数値の列のセルをフォーカス
                         */
                        focusField: focusIndex,
                    });

                    $("input, select", e.target).trigger("focus");
                    //添加行
                    var totalrow = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                        "getGridParam",
                        "records"
                    );
                    if (
                        rowId == totalrow &&
                        $("#" + rowId + "_BUSYO_CD").val() != ""
                    ) {
                        var columns = {
                            BUSYO_CD: "",
                            BUSYO_NM: "",
                        };
                        $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                            "addRowData",
                            parseInt(totalrow) + 1,
                            columns
                        );
                    }
                    me.changerow(last);
                } else {
                    //削除確認メッセージを表示する
                    //$('#FrmAuthCtlEdit_sprCostList').jqGrid('saveRow', me.lastsel);
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.FncMsgBox("QY007", "権限管理マスタ");
                }
            } else {
                if (rowId && rowId !== me.lastsel) {
                    var last = me.lastsel;

                    //check_busyonm
                    me.check_busyonm();

                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    //隐藏jqGrid的检索button
                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                        "setCell",
                        me.lastsel,
                        "BUSYO_SELECT",
                        " "
                    );
                    me.lastsel = rowId;
                }
                $("#FrmAuthCtlEdit_sprCostList").jqGrid("editRow", rowId, {
                    keys: true,
                    focusField: false,
                });

                if (last != "" && me.delFlag == false) {
                    me.changerow(last);
                }
            }
            var selNextId = "#" + me.lastsel + "_BUSYO_SELECT";
            $(selNextId).button();
        },
    });
    $("#FrmAuthCtlEdit_sprCostList").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });
    $("#FrmAuthCtlEdit_sprMeisei").jqGrid({
        datatype: "local",
        height: 260,
        colModel: me.colModel2,
        rownumbers: true,
    });
    $("#FrmAuthCtlEdit_sprMeisei").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });
    $("#FrmAuthCtlEdit_sprCostList").jqGrid("bindKeys");
    //**********************************************************************
    //処理説明：全て　クリック時
    //**********************************************************************
    $(".KRSS.FrmAuthCtlEdit.cbxAll").click(function () {
        me.sprCostList_Leave();
        me.cbxAll_CheckedChanged();
    });

    //**********************************************************************
    //処理説明：登録ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtlEdit.cmdUpdate").click(function () {
        me.sprCostList_Leave();
        me.cmdUpdate_Click();
    });

    //**********************************************************************
    //処理説明：戻るボタン押下時
    //**********************************************************************
    $(".KRSS.FrmAuthCtlEdit.cmdBack").click(function () {
        me.sprCostList_Leave();
        me.cmdBack();
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
        //プロパティ社員NO
        me.strSYAINNO = me.FrmAuthCtl.selRowData["SYAIN_NO"];
        //プロパティ社員名
        me.strSYAINNM = me.FrmAuthCtl.selRowData["SYAIN_NM"];
        //プロパティ配属開始日
        me.strSTARTDATE = me.FrmAuthCtl.selRowData["START_DATE"];
        //プロパティ配属終了日
        me.strENDDATEE = me.FrmAuthCtl.selRowData["END_DATE"];
        //プロパティ部署コード
        me.strBUSYOCD = me.FrmAuthCtl.selRowData["BUSYO_CD"];
        //プロパティ部署名
        me.strBUSYONM = me.FrmAuthCtl.selRowData["BUSYO_NM"];

        me.fncGetBusyo();
    };

    //**********************************************************************
    //処 理 名：getBusyo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.fncGetBusyo = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyo";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.BusyoArrFalse = result["data"]["false"];
                me.BusyoArrTrue = result["data"]["true"];
                me.frmAuthCtlEdit_Load();
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmAuthCtlEdit_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmAuthCtlEdit_Load = function () {
        //初期処理
        //画面項目ｸﾘｱ
        me.subClearForm();
        //データリストの値を設定
        me.DataReShow(true);
    };

    //**********************************************************************
    //処 理 名：データリストの値を設定
    //関 数 名：DataReShow
    //引    数：true/false
    //戻 り 値：無し
    //処理説明：データリストの値を設定
    //**********************************************************************
    me.DataReShow = function (blnFlag) {
        var url = me.sys_id + "/" + me.id + "/" + "fncSQL1";
        var data = {
            SYAIN_NO: me.strSYAINNO,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //データリストの値を設定
                if (result["data"].length != 0) {
                    me.subSpreadReShow(result["data"]);
                } else {
                    me.columns1 = {
                        BUSYO_CD: me.strBUSYOCD,
                        BUSYO_NM: me.strBUSYONM,
                    };
                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                        "addRowData",
                        1,
                        me.columns1
                    );
                    //１行目を選択状態にする
                    $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                }
                if (me.strBUSYOCD != "") {
                    var url = me.sys_id + "/" + me.id + "/" + "fncSQL2";
                    var BUSYO_CD = $("#1_BUSYO_CD").val().trimEnd();
                    var data = {
                        BUSYO_CD: BUSYO_CD,
                        SYAIN_NO: me.strSYAINNO,
                    };
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] == true) {
                            if (result["data"].length != 0) {
                                for (key in result["data"]) {
                                    me.columns2 = {
                                        CHECK: result["data"][key][
                                            "NVL2(ACTL.HAUTH_ID,1,0)"
                                        ],
                                        HAUTH_ID:
                                            result["data"][key]["HAUTH_ID"],
                                        HAUTH_NM:
                                            result["data"][key]["HAUTH_NM"],
                                        MEMO: result["data"][key]["MEMO"],
                                        CHECK1: result["data"][key][
                                            "NVL2(ACTL.HAUTH_ID,1,0)"
                                        ],
                                        CREATE_DATE:
                                            result["data"][key]["CREATE_DATE"],
                                    };
                                    $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                                        "addRowData",
                                        parseInt(key) + 1,
                                        me.columns2
                                    );
                                }
                            }
                            //ﾌｫｰﾑﾛｰﾄﾞ
                            if (blnFlag == true) {
                                //ﾊﾟﾗﾒｰﾀを使用して初期表示
                                me.fncFromDataShow();
                                $("#FrmAuthCtlEdit").dialog("open");
                            }
                            //1行 の部署コード　focus.
                            $("#1_BUSYO_CD").trigger("focus");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                    };
                    me.ajax.send(url, data, 0);
                } else {
                    $("#FrmAuthCtlEdit").dialog("open");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：画面項目をｸﾘｱする
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目をｸﾘｱする
    //**********************************************************************
    me.subClearForm = function () {
        $(".KRSS.FrmAuthCtlEdit.lblBUSYOCD").val("");
        $(".KRSS.FrmAuthCtlEdit.lblBUSYONM").val("");
        $(".KRSS.FrmAuthCtlEdit.lblSYAINNO").val("");
        $(".KRSS.FrmAuthCtlEdit.lblSYAINNM").val("");
        $(".KRSS.FrmAuthCtlEdit.cboSTARTDATE").val("");
        $(".KRSS.FrmAuthCtlEdit.cboENDDATE").val("");
        //$('.KRSS.FrmAuthCtlEdit.cbxAll.Checked = False
        $("#FrmAuthCtlEdit_sprCostList").jqGrid("clearGridData");
        $("#FrmAuthCtlEdit_sprMeisei").jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：データグリッドの再表示
    //関 数 名：subSpreadReShow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データグリッドを再表示する
    //**********************************************************************
    me.subSpreadReShow = function (tblData) {
        for (key in tblData) {
            me.columns1 = {
                BUSYO_CD: tblData[key]["BUSYO_CD"],
                BUSYO_NM: tblData[key]["BUSYO_NM"],
            };
            $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                "addRowData",
                parseInt(key) + 1,
                me.columns1
            );
            intRow = key;
        }
        //添加行
        me.columns11 = {
            BUSYO_CD: "",
            BUSYO_NM: "",
        };
        var totalrow = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "records"
        );
        $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "addRowData",
            parseInt(totalrow) + 1,
            me.columns11
        );

        //１行目を選択状態にする
        $("#FrmAuthCtlEdit_sprCostList").jqGrid("setSelection", 1, true);
    };

    //**********************************************************************
    //処 理 名：画面データの再表示
    //関 数 名：fromDataShow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面データを表示する
    //**********************************************************************
    me.fncFromDataShow = function () {
        //社員番号
        $(".KRSS.FrmAuthCtlEdit.lblSYAINNO").val(me.strSYAINNO);
        $(".KRSS.FrmAuthCtlEdit.lblSYAINNM").val(me.strSYAINNM);
        //配属期間
        if (me.strSTARTDATE != "") {
            $(".KRSS.FrmAuthCtlEdit.cboSTARTDATE").val(
                me.strSTARTDATE.substring(0, 4) +
                    "/" +
                    me.strSTARTDATE.substring(4, 6) +
                    "/" +
                    me.strSTARTDATE.substring(6, 8)
            );
        } else {
            $(".KRSS.FrmAuthCtlEdit.cboSTARTDATE").val("");
        }
        if (me.strENDDATEE != "") {
            $(".KRSS.FrmAuthCtlEdit.cboENDDATE").css("visibility", "visible");
            $(".KRSS.FrmAuthCtlEdit.cboENDDATE").val(
                me.strENDDATEE.substring(0, 4) +
                    "/" +
                    me.strENDDATEE.substring(4, 6) +
                    "/" +
                    me.strENDDATEE.substring(6, 8)
            );
        } else {
            $(".KRSS.FrmAuthCtlEdit.cboENDDATE").css("visibility", "hidden");
        }
        //部署
        $(".KRSS.FrmAuthCtlEdit.lblBUSYOCD").val(me.strBUSYOCD);
        $(".KRSS.FrmAuthCtlEdit.lblBUSYONM").val(me.strBUSYONM);
    };

    //**********************************************************************
    //処 理 名：全て
    //関 数 名：cbxAll_CheckedChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：全て　クリック時
    //**********************************************************************
    me.cbxAll_CheckedChanged = function () {
        var rowNum = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
            "getGridParam",
            "records"
        );
        //ONになった場合
        if ($(".KRSS.FrmAuthCtlEdit.cbxAll").prop("checked")) {
            for (i = 1; i <= rowNum; i++) {
                $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                    "setCell",
                    i,
                    "CHECK",
                    "1"
                );
            }
        }
        //OFFになった場合
        else {
            for (i = 1; i <= rowNum; i++) {
                $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                    "setCell",
                    i,
                    "CHECK",
                    "0"
                );
            }
        }
    };

    //**********************************************************************
    //処 理 名：削除を行う
    //関 数 名：delRowData
    //引    数：無し
    //戻 り 値：無し
    //処理説明：削除を行う
    //**********************************************************************
    me.delRowData = function () {
        var rowId = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var Rowdata = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getRowData",
            rowId
        );
        var BUSYO_CD = Rowdata["BUSYO_CD"];
        if (BUSYO_CD.match("<input")) {
            BUSYO_CD = $("#" + rowId + "_BUSYO_CD").val();
        }
        var url = me.sys_id + "/" + me.id + "/" + "delRowData";
        var data = {
            SYAIN_NO: me.strSYAINNO,
            BUSYO_CD: BUSYO_CD,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //データリストの値を設定
                $("#FrmAuthCtlEdit_sprCostList").jqGrid("clearGridData");
                $("#FrmAuthCtlEdit_sprMeisei").jqGrid("clearGridData");
                me.delFlag = true;
                me.DataReShow(false);
                $(".KRSS.FrmAuthCtlEdit.cbxAll").prop("checked", "false");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：登録
    //関 数 名：cmdUpdate_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：登録ボタン押下時
    //**********************************************************************
    me.cmdUpdate_Click = function () {
        var blnTarget = false;
        var rowId = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "selrow"
        );

        //var rowData = $('#FrmAuthCtlEdit_sprCostList').jqGrid('getRowData', rowId);
        var BUSYO_CD = $("#" + rowId + "_BUSYO_CD").val();
        //入力チェック
        var tempFlag = false;
        for (key in me.BusyoArrTrue) {
            if (me.BusyoArrTrue[key]["BUSYO_CD"] == BUSYO_CD) {
                tempFlag = true;
                $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                    "setCell",
                    rowId,
                    "BUSYO_NM",
                    me.BusyoArrTrue[key]["BUSYO_NM"]
                );
            }
        }
        if (tempFlag == false) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "入力された部署コードは存在していません。"
            );
            $("#" + rowId + "BUSYO_CD").css(
                "backgroundColor",
                me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
            );
            $("#" + rowId + "BUSYO_CD").trigger("focus");
            $("#" + rowId + "BUSYO_CD").select();
            return;
        }
        var totalrow = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
            "getGridParam",
            "records"
        );
        for (var i = 1; i <= totalrow; i++) {
            if (
                $("#FrmAuthCtlEdit_sprMeisei").jqGrid("getCell", i, "CHECK") ==
                "Yes"
            ) {
                blnTarget = true;
            }
        }
        if (blnTarget == false) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "更新対象の権限IDが選択されていません。"
            );
            $("#" + rowId + "BUSYO_CD").select();
            return;
        }
        //確認メッセージを表示する。
        me.is_cmdUpdate_click = true;
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delInsert;
        me.clsComFnc.FncMsgBox("QY010");
    };

    //**********************************************************************
    //処 理 名：データベースに登録する
    //関 数 名：delInsert
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データベースに登録する
    //**********************************************************************
    me.delInsert = function () {
        var rowId = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var BUSYO_CD = $("#" + rowId + "_BUSYO_CD")
            .val()
            .trimEnd();

        var CREATE_DATE_ARR = [];
        var HAUTH_ID_ARR = [];
        var totalrow = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
            "getGridParam",
            "records"
        );
        for (var i = 1; i <= totalrow; i++) {
            if (
                $("#FrmAuthCtlEdit_sprMeisei").jqGrid("getCell", i, "CHECK") ==
                "Yes"
            ) {
                CREATE_DATE_ARR.push(
                    $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                        "getCell",
                        i,
                        "CREATE_DATE"
                    )
                );
                HAUTH_ID_ARR.push(
                    $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                        "getCell",
                        i,
                        "HAUTH_ID"
                    )
                );
            }
        }

        var url = me.sys_id + "/" + me.id + "/" + "cmdUpdate_Click";
        if (me.is_cmdUpdate_click == true) {
            var data = {
                SYAIN_NO: me.strSYAINNO,
                BUSYO_CD: BUSYO_CD,
                CREATE_DATE: CREATE_DATE_ARR,
                HAUTH_ID: HAUTH_ID_ARR,
            };
        } else {
            var data = {
                SYAIN_NO: me.strSYAINNO,
                BUSYO_CD: me.Last_BUSYO_CD,
                CREATE_DATE: CREATE_DATE_ARR,
                HAUTH_ID: HAUTH_ID_ARR,
            };
        }

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //再表示を行う
                $(".KRSS.FrmAuthCtlEdit.cbxAll").prop("checked", false);

                var url = me.sys_id + "/" + me.id + "/" + "fncSQL2";
                var data = {
                    BUSYO_CD: BUSYO_CD,
                    SYAIN_NO: me.strSYAINNO,
                };
                me.ajax.receive = function (result) {
                    $("#FrmAuthCtlEdit_sprMeisei").jqGrid("clearGridData");
                    result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        if (result["data"].length != 0) {
                            for (key in result["data"]) {
                                me.columns2 = {
                                    CHECK: result["data"][key][
                                        "NVL2(ACTL.HAUTH_ID,1,0)"
                                    ],
                                    HAUTH_ID: result["data"][key]["HAUTH_ID"],
                                    HAUTH_NM: result["data"][key]["HAUTH_NM"],
                                    MEMO: result["data"][key]["MEMO"],
                                    CHECK1: result["data"][key][
                                        "NVL2(ACTL.HAUTH_ID,1,0)"
                                    ],
                                    CREATE_DATE:
                                        result["data"][key]["CREATE_DATE"],
                                };
                                $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                                    "addRowData",
                                    parseInt(key) + 1,
                                    me.columns2
                                );
                            }
                        }
                        me.bolResult = true;
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                };
                me.ajax.send(url, data, 0);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.check_busyonm = function () {
        var tempflag = false;
        for (key in me.BusyoArrTrue) {
            if (
                me.BusyoArrTrue[key]["BUSYO_CD"] ==
                $.trim($("#" + me.lastsel + "_BUSYO_CD").val())
            ) {
                $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                    "setCell",
                    me.lastsel,
                    "BUSYO_NM",
                    me.BusyoArrTrue[key]["BUSYO_NM"]
                );
                tempflag = true;
            }
        }
        if (tempflag == false) {
            $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                "setCell",
                me.lastsel,
                "BUSYO_NM",
                " "
            );
        }
    };

    me.changerow = function (row) {
        me.delFlag = false;
        var blnflag = false;
        me.Last_BUSYO_CD = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getCell",
            row,
            "BUSYO_CD"
        );
        //変更チェック
        var totalrow = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
            "getGridParam",
            "records"
        );
        for (var i = 1; i <= totalrow; i++) {
            var CHECK = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                "getCell",
                i,
                "CHECK"
            );
            CHECK == "Yes" ? (CHECK = 1) : (CHECK = 0);

            var CHECK1 = $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                "getCell",
                i,
                "CHECK1"
            );
            if (CHECK != CHECK1) {
                blnflag = true;
                me.is_cmdUpdate_click = false;
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.delInsert;
                me.clsComFnc.MsgBoxBtnFnc.No = me.sprMeisei_reshow;
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "右表のチェック内容が変更されていますが、登録されておりません。登録しますか？"
                );
                break;
            }
        }
        if (blnflag == false) {
            me.sprMeisei_reshow();
        }
    };

    //**********************************************************************
    //処 理 名：重新显示右表内容
    //関 数 名：sprMeisei_reshow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：重新显示右表内容
    //**********************************************************************
    me.sprMeisei_reshow = function () {
        var rowId = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var BUSYO_CD = $("#" + rowId + "_BUSYO_CD")
            .val()
            .trimEnd();
        $(".KRSS.FrmAuthCtlEdit.cbxAll").prop("checked", false);

        var url = me.sys_id + "/" + me.id + "/" + "fncSQL2";
        var data = {
            BUSYO_CD: BUSYO_CD,
            SYAIN_NO: me.strSYAINNO,
        };
        me.ajax.receive = function (result) {
            $("#FrmAuthCtlEdit_sprMeisei").jqGrid("clearGridData");
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    for (key in result["data"]) {
                        me.columns2 = {
                            CHECK: result["data"][key][
                                "NVL2(ACTL.HAUTH_ID,1,0)"
                            ],
                            HAUTH_ID: result["data"][key]["HAUTH_ID"],
                            HAUTH_NM: result["data"][key]["HAUTH_NM"],
                            MEMO: result["data"][key]["MEMO"],
                            CHECK1: result["data"][key][
                                "NVL2(ACTL.HAUTH_ID,1,0)"
                            ],
                            CREATE_DATE: result["data"][key]["CREATE_DATE"],
                        };
                        $("#FrmAuthCtlEdit_sprMeisei").jqGrid(
                            "addRowData",
                            parseInt(key) + 1,
                            me.columns2
                        );
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //光标离开左表
    me.sprCostList_Leave = function () {
        var selrow = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var BUSYO_CD = $("#" + selrow + "_BUSYO_CD").val();
        for (key in me.BusyoArrFalse) {
            if (me.BusyoArrFalse[key]["BUSYO_CD"] == BUSYO_CD) {
                $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                    "setCell",
                    selrow,
                    "BUSYO_NM",
                    me.BusyoArrFalse[key]["BUSYO_NM"]
                );
                flag = true;
            }
        }
        if ((flag = false)) {
            $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                "setCell",
                selrow,
                "BUSYO_NM",
                ""
            );
        }
    };

    //**********************************************************************
    //処 理 名：終了
    //関 数 名：cmdEnd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面を閉じる
    //**********************************************************************
    me.cmdBack = function () {
        var iKensu = 0;
        var totalrow = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
            "getGridParam",
            "records"
        );
        for (var i = 1; i <= totalrow; i++) {
            var BUSYO_CD = $("#FrmAuthCtlEdit_sprCostList").jqGrid(
                "getCell",
                i,
                "BUSYO_CD"
            );
            if (BUSYO_CD.match("<input")) {
                BUSYO_CD = $("#" + i + "_BUSYO_CD").val();
            }
            if (BUSYO_CD != "") {
                iKensu++;
            }
        }
        var url = me.sys_id + "/" + me.id + "/" + "fncSQL1";
        var data = {
            SYAIN_NO: me.strSYAINNO,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["data"].length == iKensu) {
                    $("#FrmAuthCtlEdit").dialog("close");
                } else {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.closedialog;
                    me.clsComFnc.FncMsgBox(
                        "QY999",
                        "権限にチェックが入っていない部署が存在します。このまま閉じますとその部署のデータは登録されません。よろしいですか？"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.closedialog = function () {
        $("#FrmAuthCtlEdit").dialog("close");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmAuthCtlEdit = new KRSS.FrmAuthCtlEdit();
    o_KRSS_FrmAuthCtlEdit.FrmAuthCtl = o_KRSS_KRSS_FrmAuthCtl;
    o_KRSS_KRSS_FrmAuthCtl.FrmAuthCtlEdit = o_KRSS_FrmAuthCtlEdit;
    o_KRSS_FrmAuthCtlEdit.load();
});
