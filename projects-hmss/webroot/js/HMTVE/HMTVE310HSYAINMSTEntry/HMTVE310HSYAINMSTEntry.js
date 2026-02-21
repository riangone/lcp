Namespace.register("HMTVE.HMTVE310HSYAINMSTEntry");

HMTVE.HMTVE310HSYAINMSTEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.hmtve = new HMTVE.HMTVE();
    me.id = "HMTVE310HSYAINMSTEntry";
    me.sys_id = "HMTVE";
    me.grid_id = "#HMTVE310HSYAINMSTEntry_gvBusyo";
    me.url = me.sys_id + "/" + me.id + "/UpdateData";
    //选择行id
    me.lastsel = 1;
    //前一行id
    me.upsel = "";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        footerrow: true,
        caption: "",
        shrinkToFit: true,
        multiselectWidth: 60,
    };
    //后一行id
    me.nextsel = "";
    me.colModel = [
        {
            label: "所属<br/>部署",
            name: "BUSYO_CD",
            classes: "HMTVE310HSYAINMSTEntry txtDispose",
            index: "BUSYO_CD",
            width: 10,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
            },
        },
        {
            label: "集計処理用部署",
            name: "SYUKEI_BUSYO_CD",
            classes: "HMTVE310HSYAINMSTEntry txtSDispose",
            index: "SYUKEI_BUSYO_CD",
            width: 16,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "3",
            },
        },
        {
            label: "配属開始日",
            name: "START_DATE",
            classes: "HMTVE310HSYAINMSTEntry txtStart",
            index: "START_DATE",
            width: 25,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            label: "配属終了日",
            name: "END_DATE",
            classes: "HMTVE310HSYAINMSTEntry txtDispose",
            index: "END_DATE",
            width: 25,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            label: "職種区分",
            name: "SYOKUSYU_KB",
            classes: "HMTVE310HSYAINMSTEntry txtSyokusyu",
            index: "SYOKUSYU_KB",
            width: 18,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "10",
            },
        },
        {
            label: "表示区分",
            name: "DISP_KB",
            classes: "HMTVE310HSYAINMSTEntry txtDISP",
            index: "DISP_KB",
            width: 22,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            label: "台数表示区分",
            name: "DAI_HYOUJI",
            classes: "HMTVE310HSYAINMSTEntry txtDaihyouji",
            index: "DAI_HYOUJI",
            width: 25,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
                dataEvents: [
                    //blurイベント
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //enter
                            if (key == 13) {
                                var ids = $(me.grid_id).jqGrid("getDataIDs");
                                var sumid = $(me.grid_id).jqGrid(
                                    "getGridParam",
                                    "records"
                                );
                                if (ids[sumid - 1] == me.lastsel) {
                                    me.btnAddRow_Click();
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            label: "展示会対象",
            name: "Row_delete",
            index: "Row_delete",
            width: 25,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var radio1StringOnclick = '<input onclick="radio_Click(';
                var radio1StringFirst = ")\" class='HMTVE310HSYAINMSTEntry ";
                var radio1StringSecond = "_rdoTenjikai1' type='radio' name='";
                var radio1StringName =
                    "_rdoTenjikai' value='1' checked='true'/>対象";

                var radio2StringOnclick = '<input onclick="radio_Click(';
                var radio2StringFirst = ")\" class='HMTVE310HSYAINMSTEntry ";
                var radio2StringSecond = "_rdoTenjikai2' type='radio' name='";
                var radio2StringName = "_rdoTenjikai' value='0'/>対象外";
                var detail =
                    radio1StringOnclick +
                    rowObject.id +
                    radio1StringFirst +
                    rowObject.id +
                    radio1StringSecond +
                    rowObject.id +
                    radio1StringName +
                    radio2StringOnclick +
                    rowObject.id +
                    radio2StringFirst +
                    rowObject.id +
                    radio2StringSecond +
                    rowObject.id +
                    radio2StringName;
                return detail;
            },
        },
        {
            name: "",
            index: "ROW_DELETE",
            width: 15,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var fontSize = me.ratio === 1.5 ? "10px" : "13px";
                var detail =
                    '<button onclick="ROW_DELETE_Click(' +
                    rowObject.id +
                    ')" id="' +
                    rowObject.id +
                    '_ROW_DELETE" class="HMTVE310HSYAINMSTEntry ROW_DELETE Tab Enter" ' +
                    'style="border: 1px solid #77d5f7; background: #16b1e9; width: 100%; font-size: ' +
                    fontSize +
                    ';">' +
                    "行削除</button>";
                return detail;
            },
        },
        {
            label: "id",
            name: "id",
            index: "id",
            align: "left",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "UPD_PRG_ID",
            name: "UPD_PRG_ID",
            index: "UPD_PRG_ID",
            align: "left",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "IVENT_TARGET_FLG",
            name: "IVENT_TARGET_FLG",
            index: "IVENT_TARGET_FLG",
            align: "left",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];

    me.dataList = [
        {
            id: 1,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE310HSYAINMSTEntry.btnAddRow",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE310HSYAINMSTEntry.btnAdd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE310HSYAINMSTEntry.btnClose",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE310HSYAINMSTEntry.txtResignation",
        type: "datepicker",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmtve.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmtve.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //行追加ボタンクリック
    $(".HMTVE310HSYAINMSTEntry.btnAddRow").click(function () {
        me.btnAddRow_Click();
    });
    //登録/削除ボタンクリック
    $(".HMTVE310HSYAINMSTEntry.btnAdd").click(function () {
        if ($("#MODE").html() == 3) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnAdd_Click;
            me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
        } else {
            me.btnAdd_Click();
        }
    });
    //一覧へボタンクリック
    $(".HMTVE310HSYAINMSTEntry.btnClose").click(function () {
        $(".HMTVE310HSYAINMSTEntry.HMTVE-content").dialog("close");
    });
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
        //プロシージャ:画面初期化
        me.Page_Load();
    };

    me.before_close = function () {};

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************

    me.Page_Load = function () {
        $(".HMTVE310HSYAINMSTEntry.HMTVE-content").dialog({
            autoOpen: false,
            width: 840,
            height: me.ratio === 1.5 ? 555 : 700,
            modal: true,
            title: " 社員・配属先マスタメンテナンス_入力",
            open: function () {},
            close: function () {
                me.before_close();
                $(".HMTVE310HSYAINMSTEntry.HMTVE-content").remove();
            },
        });
        $(".HMTVE310HSYAINMSTEntry.HMTVE-content").dialog("open");
        if ($("#MODE").html() == 1 || $("#MODE").html() == "") {
            var data = {
                SYAIN_NO: "",
            };
        } else {
            var data = {
                SYAIN_NO: $("#SYAIN_NO").html(),
            };
        }
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            function (bErrorFlag, result_jqgrid) {
                //button:登録/削除
                $(".HMTVE310HSYAINMSTEntry.btnAdd").text(
                    $("#MODE").html() == 3 ? "削除" : "登録"
                );

                if (
                    result_jqgrid["error"] ||
                    result_jqgrid.inputDate.length == 0
                ) {
                    me.clsComFnc.FncMsgBox("E9999", result_jqgrid["error"]);
                    $(".HMTVE310HSYAINMSTEntry.btnAdd").button("disable");
                    return;
                }
                if ($("#MODE").html() == 2) {
                    //修正画面起動時の引継ぎパラメータ(モード)＝"2"の場合(修正モード)
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").prop(
                        "disabled",
                        true
                    );
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE310HSYAINMSTEntry.btnClose"
                    ).trigger("focus");
                    if (bErrorFlag == "nodata") {
                        //add a null row
                        me.addNullRow();
                        $(me.grid_id).jqGrid("editRow", 0, true);
                        me.clsComFnc.FncMsgBox("W0024");
                        return;
                    }
                    me.UpdateData(result_jqgrid.inputDate);
                } else if ($("#MODE").html() == 3) {
                    //削除画面起動時の引継ぎパラメータ(モード)＝"3"の場合(削除モード)
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtCapacity").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtBusiness").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtResignation").prop(
                        "disabled",
                        true
                    );
                    $(".HMTVE310HSYAINMSTEntry.txtResignation").datepicker(
                        "disable"
                    );
                    $(".HMTVE310HSYAINMSTEntry.btnAddRow").button("disable");
                    $(".HMTVE310HSYAINMSTEntry.btnAdd").trigger("focus");

                    if (
                        bErrorFlag == "nodata" ||
                        result_jqgrid.inputDate.length == 0
                    ) {
                        //add a null row
                        me.addNullRow();
                        $("#gview_HMTVE310HSYAINMSTEntry_gvBusyo button").prop(
                            "disabled",
                            true
                        );
                        $(
                            "#gview_HMTVE310HSYAINMSTEntry_gvBusyo input[type='radio']"
                        ).prop("disabled", true);
                        me.clsComFnc.FncMsgBox("W0024");
                        return;
                    }
                    me.UpdateData(result_jqgrid.inputDate);
                }
                me.fncJqgrid(result_jqgrid.inputDate);
                $(me.grid_id).jqGrid("setSelection", 0, true);

                if ($("#MODE").html() == 1 || $("#MODE").html() == "") {
                    me.btnAddRow_Click();
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").trigger("focus");
                }
                if (
                    $("#MODE").html() == 2 &&
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").prop(
                        "disabled"
                    ) != true
                ) {
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").trigger(
                        "focus"
                    );
                }
                var allArr = $(me.grid_id).jqGrid("getRowData");
                for (var i = 0; i < allArr.length; i++) {
                    if ($("#MODE").html() == 2) {
                        if (
                            me.clsComFnc.FncNv(
                                result_jqgrid.inputDate["UPD_PRG_ID"]
                            ) != "HSYAINMSTENTRY"
                        ) {
                            $("#" + allArr[i]["id"] + "_ROW_DELETE").prop(
                                "disabled",
                                true
                            );
                        }
                    }
                    if (allArr[i]["IVENT_TARGET_FLG"] != "") {
                        if (
                            allArr[i]["IVENT_TARGET_FLG"] == 1 ||
                            allArr[i]["IVENT_TARGET_FLG"] == 0
                        ) {
                            $(
                                "input[name='" +
                                    allArr[i]["id"] +
                                    "_rdoTenjikai'][value='" +
                                    allArr[i]["IVENT_TARGET_FLG"] +
                                    "']"
                            ).prop("checked", true);
                        }
                    }
                }
                if ($("#MODE").html() == 3) {
                    $("#gview_HMTVE310HSYAINMSTEntry_gvBusyo button").prop(
                        "disabled",
                        true
                    );
                    $(
                        "#gview_HMTVE310HSYAINMSTEntry_gvBusyo input[type='radio']"
                    ).prop("disabled", true);
                }
            }
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 780);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 110 : 160
        );
        $(me.grid_id).jqGrid("bindKeys");
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    addclass: "HMTVE310HSYAINMSTEntry CELL_BORDER",
                    startColumnName: "DISP_KB",
                    numberOfColumns: 2,
                    titleText: "固定費カバー率用",
                },
            ],
        });
    };
    //add a null row
    me.addNullRow = function () {
        $(me.grid_id).jqGrid("addRowData", 0, {
            id: 0,
            BUSYO_CD: "",
            SYUKEI_BUSYO_CD: "",
            START_DATE: "",
            END_DATE: "",
            SYOKUSYU_KB: "",
            DISP_KB: "",
            DAI_HYOUJI: "",
        });

        $(me.grid_id).jqGrid("setSelection", 0, true);
    };
    //**********************************************************************
    //処 理 名：jqgrid イベント
    //関 数 名：fncJqgrid
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    me.fncJqgrid = function (tableDate) {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowId, _status, e) {
                //編集可能なセルをクリック、上下キー
                var focusIndex =
                    typeof e != "undefined"
                        ? e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex
                        : false;
                me.lastsel = rowId;

                //获得所有行的ID数组
                var ids = $(me.grid_id).jqGrid("getDataIDs");
                ids.forEach(function (element) {
                    $(me.grid_id).jqGrid("saveRow", element);
                });
                if (
                    tableDate["UPD_PRG_ID"] == "HSYAINMSTENTRY" &&
                    $("#MODE").html() != 3
                ) {
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: focusIndex,
                    });
                }
                if ($("#MODE").html() == 1 || $("#MODE").html() == "") {
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: focusIndex,
                    });
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
    };
    //**********************************************************************
    //処 理 名：登録ボタンのイベント
    //関 数 名：btnAdd_Click
    //引    数：(I)sender イベントソース
    //戻 り 値：なし
    //処理説明：ﾛｸﾞｲﾝ情報の登録処理
    //**********************************************************************
    me.btnAdd_Click = function () {
        if (
            $("#MODE").html() == 2 ||
            $("#MODE").html() == 1 ||
            $("#MODE").html() == ""
        ) {
            var inputResult = me.fncInputCheck();
            if (!inputResult["result"]) {
                me.clsComFnc.ObjFocus = inputResult["key"];
                me.clsComFnc.FncMsgBox("W9999", inputResult["data"]);
                return;
            }
        }
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var url = "HMTVE/HMTVE310HSYAINMSTEntry/btnAdd_Click";
        for (var i = 0; i < objDR.length; i++) {
            objDR[i]["rdoTenjikai"] = $(
                'input[name="' + objDR[i]["id"] + '_rdoTenjikai"]:checked'
            ).val();
        }
        var data = {
            MODE: $("#MODE").html(),
            SYAIN_NO: $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").val(),
            SYAINNM: $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").val(),
            SYAINKN: $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val(),
            SIKAKU: $(".HMTVE310HSYAINMSTEntry.txtCapacity").val(),
            SUTAFF: $(".HMTVE310HSYAINMSTEntry.txtBusiness").val(),
            TAISYOKU: $(".HMTVE310HSYAINMSTEntry.txtResignation")
                .val()
                .replace(/-/g, "/"),
            tableData: objDR,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    var nowSelId = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "selrow"
                    );
                    $(me.grid_id).jqGrid("editRow", nowSelId, true);
                };
                if (result["key"] == "E0016" || result["key"] == "W0024") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE310HSYAINMSTEntry.btnClose"
                    );
                    me.clsComFnc.FncMsgBox(result["key"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            if ($("#MODE").html() == 1 || $("#MODE").html() == "") {
                me.PageClear();
                me.clsComFnc.FncMsgBox("I0016");
            } else {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HMTVE310HSYAINMSTEntry.HMTVE-content").dialog("close");
                };
                //QA22
                if ($("#MODE").html() == 2) {
                    me.clsComFnc.FncMsgBox("I0015");
                } else if ($("#MODE").html() == 3) {
                    me.clsComFnc.FncMsgBox("I0017");
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fncInputCheck
    //引    数：なし
    //戻 り 値：True:正常　false:異常
    //処理説明：入力チェックを行う
    //**********************************************************************
    me.fncInputCheck = function () {
        var result = [];
        result["key"] = "";
        result["data"] = "";
        result["result"] = false;
        //エラーメッセージを表示して、処理を中止する
        if (
            $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").val()).length == 0
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO");
            result["data"] = "社員NOを入力してください。";
            return result;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").val())
            ) > 5
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO");
            result["data"] = "社員NOは指定されている桁数をオーバーしています。";
            return result;
        }
        //社員名のチェック
        if (
            $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeName").val()).length ==
            0
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeName");
            result["data"] = "社員名を入力してください。";
            return result;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeName").val())
            ) > 20
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeName");
            result["data"] = "社員名は指定されている桁数をオーバーしています。";
            return result;
        }
        //社員名カナのチェック
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val())
            ) > 40
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell");
            result["data"] =
                "社員名カナは指定されている桁数をオーバーしています。";
            return result;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val())
            ) > 0
        ) {
            if (
                $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val().length !=
                me.clsComFnc.GetByteCount(
                    $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val()
                )
            ) {
                result["key"] = $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell");
                result["data"] = "社員名カナの入力値が不正です。";
                return result;
            }
        }
        //資格コードのチェック
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtCapacity").val())
            ) > 2
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtCapacity");
            result["data"] =
                "資格コードは指定されている桁数をオーバーしています。";
            return result;
        }
        //営業スタッフ区分のチェック
        if ($(".HMTVE310HSYAINMSTEntry.txtBusiness").val().trimEnd()) {
            if (
                $.trim($(".HMTVE310HSYAINMSTEntry.txtBusiness").val()) != "1" &&
                $.trim($(".HMTVE310HSYAINMSTEntry.txtBusiness").val()) != "3" &&
                $.trim($(".HMTVE310HSYAINMSTEntry.txtBusiness").val()) != "9"
            ) {
                result["key"] = $(".HMTVE310HSYAINMSTEntry.txtBusiness");
                result["data"] =
                    "営業スタッフ区分に不正な値が入力されています。";
                return result;
            } else if (
                me.clsComFnc.GetByteCount(
                    $.trim($(".HMTVE310HSYAINMSTEntry.txtBusiness").val())
                ) > 1
            ) {
                result["key"] = $(".HMTVE310HSYAINMSTEntry.txtBusiness");
                result["data"] =
                    "営業スタッフ区分は指定されている桁数をオーバーしています。";
                return result;
            }
        }
        //退職日のチェック
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtResignation").val())
            ) > 10
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtResignation");
            result["data"] = "退職日は指定されている桁数をオーバーしています。";
            return result;
        } else if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE310HSYAINMSTEntry.txtResignation").val())
            ) == 10
        ) {
            if (
                me.CheckDate(
                    $.trim($(".HMTVE310HSYAINMSTEntry.txtResignation").val())
                ) != true
            ) {
                result["key"] = $(".HMTVE310HSYAINMSTEntry.txtResignation");
                result["data"] = "退職日は不正な値が入力されています。";
                return result;
            }
        } else if (
            me.clsComFnc.GetByteCount(
                $(".HMTVE310HSYAINMSTEntry.txtResignation").val().trimEnd()
            ) != 0
        ) {
            result["key"] = $(".HMTVE310HSYAINMSTEntry.txtResignation");
            result["data"] = "YYYY/MM/DD'書式のようにご入力ください";
            return result;
        }

        var strMaxStDate = "";
        var strMaxStEndDate = "";
        var intMaxEdCnt = 0;
        var intErrN = 0;
        var intTableId = 0;
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var allArr = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < allArr.length; i++) {
            //所属部署のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["BUSYO_CD"]) > 3) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_BUSYO_CD");
                result["data"] =
                    "所属部署は指定されている桁数をオーバーしています。";
                return result;
            } else if ($.trim(allArr[i]["BUSYO_CD"]).length == 0) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_BUSYO_CD");
                result["data"] = "所属部署を入力してください。";
                return result;
            }
            //集計処理用部署のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["SYUKEI_BUSYO_CD"]) > 3) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_SYUKEI_BUSYO_CD");
                result["data"] =
                    "集計処理用部署は指定されている桁数をオーバーしています。";
                return result;
            }
            //配属開始日のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["START_DATE"]) == 0) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                result["data"] = "配属開始日を入力してください。";
                return result;
            } else if (
                me.clsComFnc.GetByteCount(allArr[i]["START_DATE"]) > 10
            ) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                result["data"] =
                    "配属開始日は指定されている桁数をオーバーしています。";
                return result;
            } else if (
                me.clsComFnc.GetByteCount(allArr[i]["START_DATE"]) == 10
            ) {
                if (me.CheckDate($.trim(allArr[i]["START_DATE"])) != true) {
                    $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                    result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                    result["data"] = "配属開始日は不正な値が入力されています。";
                    return result;
                }
            } else {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                result["data"] = "YYYY/MM/DD'書式のようにご入力ください";
                return result;
            }
            //配属終了日のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["END_DATE"]) > 10) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_END_DATE");
                result["data"] =
                    "配属終了日は指定されている桁数をオーバーしています。";
                return result;
            } else if (me.clsComFnc.GetByteCount(allArr[i]["END_DATE"]) == 10) {
                if (me.CheckDate($.trim(allArr[i]["END_DATE"])) != true) {
                    $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                    result["key"] = $("#" + allArr[i]["id"] + "_END_DATE");
                    result["data"] = "配属終了日は不正な値が入力されています。";
                    return result;
                }
            } else if (me.clsComFnc.GetByteCount(allArr[i]["END_DATE"]) != 0) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_END_DATE");
                result["data"] = "YYYY/MM/DD'書式のようにご入力ください";
                return result;
            }
            //職種区分のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["SYOKUSYU_KB"]) > 10) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_SYOKUSYU_KB");
                result["data"] =
                    "職種区分は指定されている桁数をオーバーしています。";
                return result;
            }
            //固定費カバー率用表示区分のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["DISP_KB"]) > 1) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_DISP_KB");
                result["data"] =
                    "固定費カバー率表示区分は指定されている桁数をオーバーしています。";
                return result;
            } else if (
                allArr[i]["DISP_KB"] != 1 &&
                allArr[i]["DISP_KB"] != 2 &&
                allArr[i]["DISP_KB"] != 3 &&
                allArr[i]["DISP_KB"] != 9 &&
                allArr[i]["DISP_KB"] != ""
            ) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_DISP_KB");
                result["data"] =
                    "固定費カバー率用表示区分に不正な値が入力されています。";
                return result;
            }
            //固定費カバー率用台数表示区分のチェック
            if (me.clsComFnc.GetByteCount(allArr[i]["DAI_HYOUJI"]) > 1) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_DAI_HYOUJI");
                result["data"] =
                    "固定費カバー率台数表示区分は指定されている桁数をオーバーしています。";
                return result;
            } else if (
                allArr[i]["DAI_HYOUJI"] != 1 &&
                allArr[i]["DAI_HYOUJI"] != ""
            ) {
                $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                result["key"] = $("#" + allArr[i]["id"] + "_DAI_HYOUJI");
                result["data"] =
                    "固定費カバー率台数表示区分に不正な値が入力されています。";
                return result;
            }
            //配属開始日＝配属終了日の場合、エラー
            if (allArr[i]["END_DATE"]) {
                if (allArr[i]["END_DATE"] == allArr[i]["START_DATE"]) {
                    $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                    result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                    result["data"] = "配属開始日と配属終了日が重複しています。";
                    return result;
                }
                if (
                    new Date(allArr[i]["END_DATE"]) <
                    new Date(allArr[i]["START_DATE"])
                ) {
                    $(me.grid_id).jqGrid("setSelection", allArr[i]["id"], true);
                    result["key"] = $("#" + allArr[i]["id"] + "_START_DATE");
                    result["data"] =
                        "配属開始日と配属終了日の大小関係が不正です。";
                    return result;
                }
            }

            for (var j = 0; j < allArr.length; j++) {
                if (i != j) {
                    //配属開始日の重複チェック
                    if (allArr[i]["START_DATE"] && allArr[j]["START_DATE"]) {
                        if (
                            allArr[i]["START_DATE"] === allArr[j]["START_DATE"]
                        ) {
                            $(me.grid_id).jqGrid(
                                "setSelection",
                                allArr[i]["id"],
                                true
                            );
                            result["key"] = $(
                                "#" + allArr[i]["id"] + "_START_DATE"
                            );
                            result["data"] = "配属開始日が重複しています。";
                            return result;
                        }
                    }
                    //配属終了日の重複チェック
                    if (allArr[i]["START_DATE"] && allArr[j]["END_DATE"]) {
                        if (allArr[i]["END_DATE"] === allArr[j]["END_DATE"]) {
                            $(me.grid_id).jqGrid(
                                "setSelection",
                                allArr[i]["id"],
                                true
                            );
                            result["key"] = $(
                                "#" + allArr[i]["id"] + "_END_DATE"
                            );
                            result["data"] = "配属終了日が重複しています。";
                            return result;
                        }
                    }
                    if (allArr[i]["END_DATE"] && allArr[j]["START_DATE"]) {
                        if (allArr[i]["END_DATE"] === allArr[j]["START_DATE"]) {
                            $(me.grid_id).jqGrid(
                                "setSelection",
                                allArr[i]["id"],
                                true
                            );
                            result["key"] = $(
                                "#" + allArr[i]["id"] + "_END_DATE"
                            );
                            result["data"] =
                                "配属終了日と配属開始日が重複しています。";
                            return result;
                        }
                    }
                    if (allArr[j]["START_DATE"]) {
                        if (
                            new Date(allArr[i]["START_DATE"]) <
                                new Date(allArr[j]["START_DATE"]) &&
                            new Date(allArr[j]["START_DATE"]) <
                                new Date(allArr[i]["END_DATE"])
                        ) {
                            $(me.grid_id).jqGrid(
                                "setSelection",
                                allArr[j]["id"],
                                true
                            );
                            result["key"] = $(
                                "#" + allArr[j]["id"] + "_START_DATE"
                            );
                            result["data"] = "配属開始日の範囲が不正です。";
                            return result;
                        }
                    }
                }
            }
            if (strMaxStDate == "") {
                strMaxStDate = allArr[i]["START_DATE"];
                strMaxStEndDate = allArr[i]["END_DATE"];
            } else if (
                new Date(strMaxStDate) < new Date(allArr[i]["START_DATE"])
            ) {
                strMaxStDate = allArr[i]["START_DATE"];
                strMaxStEndDate = allArr[i]["END_DATE"];
            }
            if (allArr[i]["END_DATE"].trimEnd() == "") {
                intMaxEdCnt += 1;
                intErrN = i;
                intTableId = allArr[i]["id"];
            }
        }
        if (intMaxEdCnt > 1) {
            $(me.grid_id).jqGrid("setSelection", intTableId, true);
            result["key"] = $("#" + intTableId + "_END_DATE");
            result["data"] =
                "配属終了日を指定しなくてもよいのは最新の配属先のみです。";
            return result;
        }
        if ($(".HMTVE310HSYAINMSTEntry.txtResignation").val().trimEnd()) {
            if (strMaxStEndDate != "") {
                var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
                $(me.grid_id).jqGrid("setSelection", id, true);
                result["key"] = $(".HMTVE310HSYAINMSTEntry.txtResignation");
                result["data"] =
                    "退職日を入力した場合は、最終の配属終了日は入力できません！。";
                return result;
            }
        }

        if (allArr.length > 0) {
            if (
                new Date(strMaxStDate) >
                new Date(allArr[intErrN]["START_DATE"].trimEnd())
            ) {
                $(me.grid_id).jqGrid("setSelection", intTableId, true);
                result["key"] = $("#" + intTableId + "_START_DATE");
                result["data"] = "配属開始日の大小関係が不正です。";
                return result;
            }
        }
        result["result"] = true;
        return result;
    };

    //行消除ボタンクリック
    ROW_DELETE_Click = function (rowid) {
        $(me.grid_id).jqGrid("setSelection", rowid, true);
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };

    //**********************************************************************
    //処 理 名：行追加ボタンのイベント
    //関 数 名：btnAddRow_Click
    //引    数：なし
    //戻 り 値：なし
    //処理説明：
    //**********************************************************************
    me.btnAddRow_Click = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var allArr = $(me.grid_id).jqGrid("getRowData");
        var maxId = 1;

        for (var i = 0; i < allArr.length; i++) {
            if (
                allArr[i]["BUSYO_CD"] == "" &&
                allArr[i]["SYUKEI_BUSYO_CD"] == "" &&
                allArr[i]["START_DATE"] == "" &&
                allArr[i]["END_DATE"] == "" &&
                allArr[i]["SYOKUSYU_KB"] == "" &&
                allArr[i]["DISP_KB"] == "" &&
                allArr[i]["DAI_HYOUJI"] == ""
            ) {
                $(me.grid_id).jqGrid("delRowData", allArr[i]["id"]);
            }
        }
        if (allArr.length > 0) {
            maxId = parseInt(allArr[allArr.length - 1]["id"]) + 1;
        }
        var selectrow = {
            id: maxId,
            BUSYO_CD: "",
            SYUKEI_BUSYO_CD: "",
            START_DATE: "",
            END_DATE: "",
            SYOKUSYU_KB: "",
            DISP_KB: "",
            DAI_HYOUJI: "",
        };
        $(me.grid_id).jqGrid("addRowData", maxId, selectrow);
        $(me.grid_id).jqGrid("setSelection", me.lastsel, false);
        $(me.grid_id).jqGrid("setSelection", maxId, true);
    };
    me.UpdateData = function (tableData) {
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").val(
            me.clsComFnc.FncNv(tableData["SYAIN_NO"])
        );
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").val(
            me.clsComFnc.FncNv(tableData["SYAIN_NM"])
        );
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val(
            me.clsComFnc.FncNv(tableData["SYAIN_KN"])
        );
        $(".HMTVE310HSYAINMSTEntry.txtCapacity").val(
            me.clsComFnc.FncNv(tableData["SIKAKU_CD"])
        );
        $(".HMTVE310HSYAINMSTEntry.txtBusiness").val(
            me.clsComFnc.FncNv(tableData["SLSSUTAFF_KB"])
        );
        $(".HMTVE310HSYAINMSTEntry.txtResignation").val(
            me.clsComFnc.FncNv(tableData["TAISYOKU_DATE"])
        );
        if (me.clsComFnc.FncNv(tableData["UPD_PRG_ID"]) != "HSYAINMSTENTRY") {
            $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").prop("disabled", true);
            $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").prop("disabled", true);
            $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").prop(
                "disabled",
                true
            );
            $(".HMTVE310HSYAINMSTEntry.txtCapacity").prop("disabled", true);
            $(".HMTVE310HSYAINMSTEntry.txtBusiness").prop("disabled", true);
            $(".HMTVE310HSYAINMSTEntry.txtResignation").prop("disabled", true);
            $(".HMTVE310HSYAINMSTEntry.txtResignation").datepicker("disable");
            $(".HMTVE310HSYAINMSTEntry.btnAddRow").button("disable");
        }
    };

    //**********************************************************************
    //処 理 名：日付チェク
    //関 数 名：CheckDate
    //引    数：なし
    //戻 り 値：なし
    //処理説明：
    //**********************************************************************
    me.CheckDate = function (Object) {
        var ObjectValue = Object;
        var patrn = /^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            var d = new Date(r[1], r[3] - 1, r[4]);
            var RigDate =
                d.getFullYear() +
                r[2] +
                (d.getMonth() + 1) +
                r[2] +
                d.getDate();
            var s = ObjectValue.substring(4, 5);
            var newdateArr = ObjectValue.split(s);
            newdateArr[1] = newdateArr[1].trimStart("0");
            newdateArr[2] = newdateArr[2].trimStart("0");
            var newdate = newdateArr[0] + s + newdateArr[1] + s + newdateArr[2];
            if (RigDate == newdate) {
                return true;
            } else {
                return false;
            }
        }
    };
    //**********************************************************************
    //処 理 名：ページクリア
    //関 数 名：PageClear
    //引    数：なし
    //戻 り 値：なし
    //処理説明：ページクリア
    //**********************************************************************
    me.PageClear = function () {
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").val("");
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeName").val("");
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeSpell").val("");
        $(".HMTVE310HSYAINMSTEntry.txtCapacity").val("");
        $(".HMTVE310HSYAINMSTEntry.txtBusiness").val("");
        $(".HMTVE310HSYAINMSTEntry.txtResignation").val("");

        $(me.grid_id).jqGrid("clearGridData");
        me.btnAddRow_Click();
        $(".HMTVE310HSYAINMSTEntry.txtEmployeeNO").trigger("focus");
    };
    radio_Click = function (rowid) {
        $(me.grid_id).jqGrid("setSelection", rowid, true);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE310HSYAINMSTEntry = new HMTVE.HMTVE310HSYAINMSTEntry();
    o_HMTVE_HMTVE.HMTVE300HSYAINMSTList.HMTVE310HSYAINMSTEntry =
        o_HMTVE_HMTVE310HSYAINMSTEntry;
    o_HMTVE_HMTVE310HSYAINMSTEntry.load();
});
