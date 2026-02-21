/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230801           機能変更　　　データを更新する後、選択行を保持しておく            caina
 * 20250219           機能変更         20250219_内部統制_改修要望.xlsx                 YIN
 * 20250225           bug         拠点コード・領域の内容を変更したら、更新した後で、
 *                                「対象・対象外」のデータが「null」として登録           YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKyotenMente");

HMAUD.HMAUDKyotenMente = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.sys_id = "HMAUD";
    me.id = "HMAUDKyotenMente";
    me.HMAUD = new HMAUD.HMAUD();
    me.refreshFlg = false;
    //20230801 caina ins s
    me.firstload = true;
    //20230801 caina ins e

    // jqgrid
    me.grid_id = "#HMAUDKyotenMente_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.lastsel = 0;
    me.option = {
        rownumbers: false,
        rowNum: 0,
        caption: "",
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "btnup",
            label: "  ",
            index: "btnup",
            width: 40,
            align: "center",
            sortable: false,
            formatter: function (_cellValue, options, _rowObject) {
                return (
                    "<button class='HMAUDKyotenMente btnup' style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;' onclick='moveRow(\"up\"," +
                    options.rowId +
                    ")'>↑</button>"
                );
            },
        },
        {
            name: "btndown",
            label: "  ",
            index: "btndown",
            width: 40,
            align: "center",
            sortable: false,
            formatter: function (_cellValue, options, _rowObject) {
                return (
                    "<button class='HMAUDKyotenMente btndown' style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;' onclick='moveRow(\"down\"," +
                    options.rowId +
                    ")'>↓</button>"
                );
            },
        },
        {
            name: "KYOTEN_CD",
            label: "拠点コード",
            index: "KYOTEN_CD",
            width: 67,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width numeric",
                //20230307 CAI INS S
                maxlength: "5",
                //20230307 CAI INS E
            },
        },
        {
            name: "KYOTEN_NAME",
            label: "拠点名",
            index: "KYOTEN_NAME",
            width: 110,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                // 20230307 LIU INS S
                maxlength: "60",
                // 20230307 LIU INS E
            },
        },
        {
            name: "TERRITORY",
            label: "領域",
            index: "TERRITORY",
            width: 120,
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
                        type: "change",
                        fn: function (e) {
                            if (e.target.value != 0) {
                                $("#" + me.lastsel + "_START_DT").select();
                            }
                        },
                    },
                ],
                value: {
                    1: "営業",
                    2: "サービス",
                    3: "管理",
                    4: "業売",
                    5: "業売管理",
                    // 20250219 YIN INS S
                    6: "カーセブン",
                    // 20250219 YIN INS E
                },
            },
        },
        {
            name: "START_DT",
            label: "開始日",
            index: "START_DT",
            width: 110,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                //20230308 LIU INS S
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //現在id
                            var nowId = this.parentElement.parentElement.id;
                            var date = this.value;
                            if (!me.dateCheck(date)) {
                                this.value = me.data[nowId]
                                    ? me.data[nowId]["cell"]["START_DT"]
                                    : "";
                            }
                        },
                    },
                ],
                //20230308 LIU INS E
                dataInit: function (elem) {
                    $(elem).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        onSelect: function () {
                            $(this).trigger("change");
                        },
                    });
                },
            },
        },
        {
            name: "END_DT",
            label: "終了日",
            index: "END_DT",
            width: 110,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width",
                //20230308 CAI INS S
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //現在id
                            var nowId = this.parentElement.parentElement.id;
                            var date = this.value;
                            if (date != "") {
                                if (!me.dateCheck(date)) {
                                    this.value = me.data[nowId]
                                        ? me.data[nowId]["cell"]["END_DT"]
                                        : "";
                                }
                            }
                        },
                    },
                ],
                //20230308 CAI INS E
                dataInit: function (elem) {
                    $(elem).datepicker({
                        changeYear: true,
                        changeMonth: true,
                        showButtonPanel: true,
                        onSelect: function () {
                            $(this).trigger("change");
                        },
                    });
                },
            },
        },
        {
            name: "RESPONSIBLE_EIGYO",
            label: "RESPONSIBLE_EIGYO",
            index: "RESPONSIBLE_EIGYO",
            sortable: false,
            align: "center",
            width: 135,
            hidden: true,
        },
        {
            name: "RESPONSIBLE_TERRITORY",
            label: "RESPONSIBLE_TERRITORY",
            index: "RESPONSIBLE_TERRITORY",
            sortable: false,
            align: "center",
            width: 135,
            hidden: true,
        },
        {
            name: "KEY_PERSON",
            label: "KEY_PERSON",
            index: "KEY_PERSON",
            sortable: false,
            align: "center",
            width: 135,
            hidden: true,
        },
        {
            name: "TARGET",
            label: "TARGET",
            index: "TARGET",
            sortable: false,
            align: "center",
            width: 135,
            hidden: true,
        },
        {
            name: "TARGET1",
            label: "  ",
            index: "TARGET1",
            sortable: false,
            align: "center",
            width: 135,
            formatter: function (_cellvalue, options, rowObject) {
                var radio1StringOnclick = '<input onclick="selection(';
                var radio1StringFirst = ")\" class='HMAUDKyotenMente ";
                var radio1StringSecond = "_rdoTenjikai1' type='radio' name='";
                // 20250225 YIN UPD S
                // var radio1StringName = "_rdoTenjikai' value='1' checked='true'/>対象  ";
                var radio1StringName =
                    "_rdoTenjikai' value='1' " +
                    (rowObject["TARGET"] == 1
                        ? "checked='true'/>対象  "
                        : "/>対象  ");
                // 20250225 YIN UPD E

                var radio2StringOnclick = '<input onclick="selection(';
                var radio2StringFirst = ")\" class='HMAUDKyotenMente ";
                var radio2StringSecond = "_rdoTenjikai2' type='radio' name='";
                // 20250225 YIN UPD S
                // var radio2StringName = "_rdoTenjikai' value='0'/>対象外";
                var radio2StringName =
                    "_rdoTenjikai' value='0' " +
                    (rowObject["TARGET"] == 0
                        ? "checked='true'/>対象外"
                        : "/>対象外");
                // 20250225 YIN UPD E
                var detail =
                    radio1StringOnclick +
                    // 20250225 YIN UPD S
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    // radio1StringFirst +
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    // radio1StringSecond +
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    // radio1StringName +
                    // radio2StringOnclick +
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    // radio2StringFirst +
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    // radio2StringSecond +
                    // rowObject.KYOTEN_CD +
                    // rowObject.TERRITORY +
                    options.rowId +
                    ",1" +
                    radio1StringFirst +
                    options.rowId +
                    radio1StringSecond +
                    options.rowId +
                    radio1StringName +
                    radio2StringOnclick +
                    options.rowId +
                    ",0" +
                    radio2StringFirst +
                    options.rowId +
                    radio2StringSecond +
                    options.rowId +
                    // 20250225 YIN UPD E
                    radio2StringName;
                return detail;
            },
        },
        {
            name: "DISP_SEQ",
            label: "  ",
            index: "DISP_SEQ",
            hidden: true,
        },
        {
            name: "btnEdit",
            label: " ",
            index: "btnEdit",
            width: 90,
            align: "right",
            sortable: false,
            formatter: function (_cellvalue, options, _rowObject) {
                var detail =
                    "<button onclick=\"btnSetting_Click('" +
                    options.rowId +
                    "')\" id = 'btnEdit' class=\"HMAUDKyotenMente btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>担当者設定</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMAUDKyotenMente.button",
        type: "button",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //更新ボタンクリック
    $(".HMAUDKyotenMente.btnUpdata").click(function () {
        if (!me.InputCheck()) {
            return;
        }
        if (!me.repeatCheck()) {
            return false;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpdate_Click;
        // 20230309 CI INS S
        me.clsComFnc.MsgBoxBtnFnc.No = me.setSelect;
        // 20230309 CI INS E

        me.clsComFnc.FncMsgBox("QY012");
    });
    $(".HMAUDKyotenMente.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    $(".HMAUDKyotenMente.btnRowDel").click(function () {
        me.btnRowDel_Click();
    });
    $(".HMAUDKyotenMente.btnRetrun").click(function () {
        me.btnRetrun_Click();
    });

    //左メニューを閉じたときに明細の幅を広げて表示
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDKyotenMente fieldset").width()
            );
        }, 500);
    });

    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    //20230801 caina upd s
    me.Page_Load = function (flg, scrollPosition) {
        //20230801 caina upd e
        $.jgrid.gridUnload(me.grid_id);
        me.complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                $(".HMAUDKyotenMente.btnUpdata").button("disable");
                // 20230307 LIU INS S
                $(".HMAUDKyotenMente.btnRowAdd").button("disable");
                $(".HMAUDKyotenMente.btnRowDel").button("disable");
                // 20230307 LIU INS E
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if ($(".HMAUDKyotenMenteSetting.body").dialog("isOpen")) {
                $(".HMAUDKyotenMenteSetting.body").dialog("close");
            }
            // 20230308 LIU INS S
            $(".HMAUDKyotenMente.btnUpdata").button("enable");
            $(".HMAUDKyotenMente.btnRowAdd").button("enable");
            $(".HMAUDKyotenMente.btnRowDel").button("enable");
            // 20230308 LIU INS E
            // 20250225 YIN DEL S
            // for (var i = 0; i < result["rows"].length; i++) {
            //     if (
            //         result["rows"][i]["cell"]["TARGET"] == 1 ||
            //         result["rows"][i]["cell"]["TARGET"] == 0
            //     ) {
            //         $(
            //             "input[name='" +
            //                 result["rows"][i]["cell"]["KYOTEN_CD"] +
            //                 result["rows"][i]["cell"]["TERRITORY"] +
            //                 "_rdoTenjikai'][value='" +
            //                 result["rows"][i]["cell"]["TARGET"] +
            //                 "']"
            //         ).prop("checked", true);
            //     }
            // }
            // 20250225 YIN DEL E
            me.data = result["rows"];
            //20230801 caina upd s
            if (flg == 1) {
                $(me.grid_id)
                    .closest(".ui-jqgrid-bdiv")
                    .scrollTop(scrollPosition);
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                me.firstload == false;
            }
            if (me.firstload == true) {
                //20230307 CAI INS S
                $(me.grid_id).jqGrid("setSelection", 0, true);
                //20230307 CAI INS E
            }
            me.firstload = false;
            //20230801 caina upd e
        };
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 890);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 350 : 470
        );
        $(me.grid_id).jqGrid("bindKeys");

        me.jqgridEditSet();
    };
    // 20250225 YIN UPD S
    // selection = function (cl) {
    selection = function (cl, target) {
        // 20250225 YIN UPD E
        $(me.grid_id).jqGrid("setSelection", cl, true);
        // 20250225 YIN INS S
        $(me.grid_id).jqGrid("setCell", cl, "TARGET", target);
        // 20250225 YIN INS E
    };

    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKyotenMente fieldset").width()
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKyotenMente.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKyotenMente fieldset").height();
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 98;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };

    moveRow = function (moveMethod, rowid) {
        $(me.grid_id).jqGrid("saveRow", rowid);
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var targetId = me.getTargetId(rowid, moveMethod);

        if (targetId == -1) {
            return false;
        }
        var temp1 = $(me.grid_id).jqGrid("getRowData", rowid);
        var temp2 = $(me.grid_id).jqGrid("getRowData", targetId);
        //对调行号
        var tempRn = temp1.DISP_SEQ;
        temp1.DISP_SEQ = temp2.DISP_SEQ;
        temp2.DISP_SEQ = tempRn;

        //对调数据
        $(me.grid_id).jqGrid("setRowData", rowid, temp2);
        $(me.grid_id).jqGrid("setRowData", targetId, temp1);
        // 20250225 YIN DEL S
        // $(
        //     "input[name='" +
        //         temp2["KYOTEN_CD"] +
        //         temp2["TERRITORY"] +
        //         "_rdoTenjikai'][value='" +
        //         temp2["TARGET"] +
        //         "']"
        // ).prop("checked", true);
        // $(
        //     "input[name='" +
        //         temp1["KYOTEN_CD"] +
        //         temp1["TERRITORY"] +
        //         "_rdoTenjikai'][value='" +
        //         temp1["TARGET"] +
        //         "']"
        // ).prop("checked", true);
        // 20250225 YIN DEL E
        $(me.grid_id).jqGrid("setSelection", targetId, true);
    };

    me.repeatCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i <= rows.length - 1; i++) {
            var rowData_i = $(me.grid_id).jqGrid("getRowData", rows[i]);
            for (var j = 0; j <= rows.length - 1; j++) {
                var rowData_j = $(me.grid_id).jqGrid("getRowData", rows[j]);
                if (i !== j) {
                    if (
                        rowData_i["KYOTEN_CD"] + rowData_i["TERRITORY"] !==
                            "" &&
                        rowData_i["KYOTEN_CD"] + rowData_i["TERRITORY"] ==
                            rowData_j["KYOTEN_CD"] + rowData_j["TERRITORY"]
                    ) {
                        $(me.grid_id).jqGrid("setSelection", rows[j], true);
                        me.clsComFnc.ObjFocus = $("#" + rows[j] + "_KYOTEN_CD");
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "拠点コード+領域が重複しています。"
                        );
                        return false;
                    }
                }
            }
        }
        return true;
    };
    me.getTargetId = function (selId, method) {
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            if (selId == ids[i] && method == "up") {
                if (i == 0) {
                    return -1;
                } else {
                    return ids[i - 1];
                }
            }
            if (selId == ids[i] && method == "down") {
                if (i == ids.length - 1) {
                    return -1;
                } else {
                    return ids[i + 1];
                }
            }
        }
    };
    //'**********************************************************************
    //'処 理 名：行選択効果の設定
    //'関 数 名：jqgridEditSet
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：行選択効果の設定
    //'**********************************************************************
    me.jqgridEditSet = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowid, _status, e) {
                var focusIndex =
                    typeof e != "undefined"
                        ? e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex
                        : false;
                //when click other [td],the first [cell] focus
                if (focusIndex && focusIndex > 6 && focusIndex != 13) {
                    focusIndex = true;
                }
                $("#ui-datepicker-div").css("display", "none");
                $(me.grid_id).jqGrid("saveRow", me.lastsel);
                if (typeof e != "undefined") {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                }

                $(me.grid_id).jqGrid("editRow", rowid, {
                    keys: false,
                    focusField: focusIndex,
                });

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    rowid
                );

                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                $(me.grid_id).find(".width").css("width", "91%");
            },
        });
    };
    // 20230309 CI INS S
    me.setSelect = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
        setTimeout(function () {
            $("#" + me.lastsel + "_KYOTEN_CD").trigger("focus");
        }, 0);
    };
    // 20230309 CI INS E
    me.btnUpdate_Click = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        //20230801 caina ins s
        var scrollPosition = $(me.grid_id)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop();
        //20230801 caina ins e
        var objDR = $(me.grid_id).jqGrid("getRowData");
        var url = "HMAUD/HMAUDKyotenMente/btnUpdateClick";
        for (var i = 0; i < objDR.length; i++) {
            // 20250225 YIN UPD S
            // objDR[i]["rdoTenjikai"] = $(
            //     'input[name="' +
            //         objDR[i]["KYOTEN_CD"] +
            //         objDR[i]["TERRITORY"] +
            //         '_rdoTenjikai"]:checked'
            // ).val();
            objDR[i]["rdoTenjikai"] = objDR[i]["TARGET"];
            // 20250225 YIN UPD E
            objDR[i]["DISP_SEQ"] = i + 1;
        }
        var data = {
            tableData: JSON.stringify(objDR),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.Page_Load();
                //更新が完了しました。
                //20230801 caina ins s
                me.clsComFnc.MsgBoxBtnFnc.OK = function () {
                    $(me.grid_id)
                        .closest(".ui-jqgrid-bdiv")
                        .scrollTop(scrollPosition);
                    me.setSelect();
                };
                //20230801 caina ins e
                me.clsComFnc.FncMsgBox("I0015");
            }
        };
        me.ajax.send(url, data, 0);
    };
    //20230308 CAI INS S
    me.dateCheck = function (date) {
        var patrn =
            /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
        if (!patrn.test($.trim(date))) {
            return false;
        }
        return true;
    };
    //20230308 CAI INS E
    me.InputCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (rowData["KYOTEN_CD"] == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_KYOTEN_CD");
                me.clsComFnc.FncMsgBox("W0017", "拠点コード");
                return false;
            }
            if (rowData["KYOTEN_NAME"] == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_KYOTEN_NAME");
                me.clsComFnc.FncMsgBox("W0017", "拠点名");
                return false;
            }
            if (rowData["START_DT"] == "") {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_START_DT");
                me.clsComFnc.FncMsgBox("W0017", "開始日");
                return false;
            }
            var patrn =
                /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
            if (!patrn.test($.trim(rowData["START_DT"]))) {
                $(me.grid_id).jqGrid("setSelection", rows[index], true);
                me.clsComFnc.ObjFocus = $("#" + rows[index] + "_START_DT");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "開始日「YYYY/MM/DD」書式のようにご入力ください。"
                );
                return false;
            }
            if ($.trim(rowData["END_DT"]) != "") {
                var patrn =
                    /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
                if (!patrn.test($.trim(rowData["END_DT"]))) {
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $("#" + rows[index] + "_END_DT");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "終了日「YYYY/MM/DD」書式のようにご入力ください。"
                    );
                    return false;
                }

                //開始日～終了日大小チェック。
                if (
                    new Date(rowData["START_DT"]) > new Date(rowData["END_DT"])
                ) {
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $("#" + rows[index] + "_START_DT");
                    me.clsComFnc.FncMsgBox("W0006", "開始日と終了日");
                    return false;
                }
            }
        }
        return true;
    };
    btnSetting_Click = function (rowID) {
        // var allIds = $(me.grid_id).jqGrid('getDataIDs');
        // var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        // if (allIds.length == 0 || rowid == null)
        // {
        // me.clsComFnc.FncMsgBox('W9999', '担当者設定の行を選択してください。');
        // return;
        // }
        $(me.grid_id).jqGrid("saveRow", rowID);
        // var rowID = $(me.grid_id).jqGrid('getGridParam', 'selrow');
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        if (rowData["KYOTEN_CD"] == "") {
            setTimeout(function () {
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_CD");
                me.clsComFnc.FncMsgBox("W0017", "拠点コード");
            }, 100);
            return;
        }
        if (rowData["KYOTEN_NAME"] == "") {
            setTimeout(function () {
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_NAME");
                me.clsComFnc.FncMsgBox("W0017", "拠点名");
            }, 100);
            return;
        }
        //20230308 CAI INS S
        if (!me.codecheck(rowData["KYOTEN_CD"])) {
            setTimeout(function () {
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_CD");
                me.clsComFnc.FncMsgBox("W9999", "拠点コードが存在しません！");
            }, 100);
            return;
        }
        //20230308 CAI INS E
        if (
            rowData["DISP_SEQ"] != me.data[rowID]["cell"]["DISP_SEQ"] ||
            rowData["KYOTEN_CD"] != me.data[rowID]["cell"]["KYOTEN_CD"] ||
            rowData["KYOTEN_NAME"] != me.data[rowID]["cell"]["KYOTEN_NAME"] ||
            rowData["TARGET"] != me.data[rowID]["cell"]["TARGET"] ||
            rowData["TERRITORY"] != me.data[rowID]["cell"]["TERRITORY"]
        ) {
            //20230308 LIU UPD S
            //$(me.grid_id).jqGrid('setSelection', rowID, true);
            //me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_CD");
            //me.clsComFnc.FncMsgBox("W9999", "拠点メンテナンスが変更されたため、更新ボタンをクリックしてください。");
            //return false;
            setTimeout(function () {
                $(me.grid_id).jqGrid("setSelection", rowID, true);
                me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_CD");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "拠点メンテナンスが変更されたため、更新ボタンをクリックしてください。"
                );
            }, 100);
            return;
            //20230308 LIU UPD E
        }
        //20230308 CAI DEL S
        // if (!me.codecheck(rowData['KYOTEN_CD']))
        // {
        // setTimeout(function()
        // {
        // $(me.grid_id).jqGrid('setSelection', rowID, true);
        // me.clsComFnc.ObjFocus = $("#" + rowID + "_KYOTEN_CD");
        // me.clsComFnc.FncMsgBox("W9999", "拠点コードが存在しません！");
        // }, 100);
        // return;
        // }
        //20230308 CAI DEL 	E
        // var getDataCount = $(me.grid_id).jqGrid('getGridParam', 'records');
        // if (getDataCount == 0)
        // {
        // me.clsComFnc.FncMsgBox("I0010");
        // return;
        // }

        if (rowData && rowData["KYOTEN_CD"]) {
            me.kyoten_cd = rowData["KYOTEN_CD"];
            me.kyoten_name = rowData["KYOTEN_NAME"];
            me.territory = rowData["TERRITORY"];
            me.ShowDialog();
        }
    };
    me.ShowDialog = function () {
        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                kyoten_cd: me.kyoten_cd,
                kyoten_name: me.kyoten_name,
                territory: me.territory,
            })
        );

        me.url = "HMAUD/HMAUDKyotenMenteSetting";
        me.ajax.receive = function (result) {
            function before_close() {
                if (me.refreshFlg) {
                    me.Page_Load();
                    me.refreshFlg = false;
                }
            }
            $(".HMAUDKyotenMente." + "dialogsHMAUDKyotenMenteSetting").hide();
            $(".HMAUDKyotenMente." + "dialogsHMAUDKyotenMenteSetting").append(
                result
            );
            o_HMAUD_HMAUD.HMAUDKyotenMente.HMAUDKyotenMenteSetting.before_close =
                before_close;
        };

        me.ajax.send(me.url, "", 0);
    };
    me.btnRowAdd_Click = function () {
        //获得所有行的ID数组
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //获得当前最大行号（数据编号）
            rowid = parseInt(ids.pop()) + 1;
        }
        var data = {
            btnup: "",
            btndown: "",
            KYOTEN_CD: "",
            KYOTEN_NAME: "",
            TERRITORY: "",
            START_DT: "",
            END_DT: "",
            TARGET: "0",
            DISP_SEQ: rowid + 1,
        };
        //插入一行
        $(me.grid_id).jqGrid("addRowData", rowid, data);
        $(me.grid_id).jqGrid("saveRow", me.lastsel);

        $(me.grid_id).jqGrid("setSelection", rowid, true);
    };
    me.btnRowDel_Click = function () {
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

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
    me.btnRetrun_Click = function () {
        //20230801 caina ins s
        me.firstload = true;
        //20230801 caina ins e
        me.Page_Load();
    };
    me.codecheck = function (code) {
        for (var j = 0; j <= me.data.length - 1; j++) {
            if (me.data[j]["cell"]["KYOTEN_CD"] == code) {
                return true;
            }
        }
        return false;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDKyotenMente = new HMAUD.HMAUDKyotenMente();
    o_HMAUD_HMAUDKyotenMente.load();
    o_HMAUD_HMAUD.HMAUDKyotenMente = o_HMAUD_HMAUDKyotenMente;
});
