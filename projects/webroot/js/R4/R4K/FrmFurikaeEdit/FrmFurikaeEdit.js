/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151008           #2203                        BUG                              li
 * 20151010           #2202                        BUG                              li
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmFurikaeEdit");

R4.FrmFurikaeEdit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmFurikaeEdit";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.getAllBusyo = "";
    me.FrmFurikae = null;
    me.lastsel = 0;
    me.col = {
        BUSYO_CD: "",
        BUSYO_NM: "",
        KEIJO_GK: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmFurikaeEdit.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeEdit.cmdBack",
        type: "button",
        handle: "",
    });

    // me.controls.push(
    // {
    // id : ".FrmFurikaeEdit.cmdTorikomi",
    // type : "button",
    // handle : ""
    // });

    me.controls.push({
        id: ".FrmFurikaeEdit.cmdSearchKmk",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeEdit.cmdMotKmk_S",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeEdit.cmdMotBs_S",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmFurikaeEdit.cboKeiriBi",
        type: "datepicker",
        handle: "",
    });

    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "部署",
            index: "BUSYO_CD",
            width: 105,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                // class : 'numeric',
                maxlength: "3",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            var row = $(e.target).closest("tr.jqgrow");
                            var rowId = row.attr("id");
                            var idGet = "#" + rowId + "_" + "BUSYO_CD";
                            var vaGet = $.trim($(idGet).val());

                            // $('#FrmFurikaeEdit_sprMeisai').jqGrid("setCell", rowId, "BUSYO_NM", "");
                            for (key in me.getAllBusyoJqGrid) {
                                if (
                                    me.getAllBusyoJqGrid[key]["BUSYO_CD"] ==
                                    vaGet
                                ) {
                                    $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                        "setCell",
                                        rowId,
                                        "BUSYO_NM",
                                        me.getAllBusyoJqGrid[key]["BUSYO_NM"]
                                    );
                                }
                            }
                            // $('#FrmFurikaeEdit_sprMeisai').jqGrid('saveRow', me.lastsel);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }

                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter
                                var idGet = "#" + me.lastsel + "_" + "BUSYO_CD";
                                var vaGet = $.trim($(idGet).val());
                                for (key in me.getAllBusyoJqGrid) {
                                    if (
                                        me.getAllBusyoJqGrid[key]["BUSYO_CD"] ==
                                        vaGet
                                    ) {
                                        $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                            "setCell",
                                            me.lastsel,
                                            "BUSYO_NM",
                                            me.getAllBusyoJqGrid[key][
                                                "BUSYO_NM"
                                            ]
                                        );
                                    }
                                }
                                var selIRow = parseInt(me.lastsel);
                                var selNextId = "#" + selIRow + "_KEIJO_GK";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 40) {
                                //DOWN
                                var idGet = "#" + me.lastsel + "_" + "BUSYO_CD";
                                var vaGet = $.trim($(idGet).val());
                                for (key in me.getAllBusyoJqGrid) {
                                    if (
                                        me.getAllBusyoJqGrid[key]["BUSYO_CD"] ==
                                        vaGet
                                    ) {
                                        $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                            "setCell",
                                            me.lastsel,
                                            "BUSYO_NM",
                                            me.getAllBusyoJqGrid[key][
                                                "BUSYO_NM"
                                            ]
                                        );
                                    }
                                }
                            }
                            if (key == 38) {
                                //UP
                                var idGet = "#" + me.lastsel + "_" + "BUSYO_CD";
                                var vaGet = $.trim($(idGet).val());
                                for (key in me.getAllBusyoJqGrid) {
                                    if (
                                        me.getAllBusyoJqGrid[key]["BUSYO_CD"] ==
                                        vaGet
                                    ) {
                                        $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                            "setCell",
                                            me.lastsel,
                                            "BUSYO_NM",
                                            me.getAllBusyoJqGrid[key][
                                                "BUSYO_NM"
                                            ]
                                        );
                                    }
                                }
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 190,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "KEIJO_GK",
            label: "金額",
            index: "KEIJO_GK",
            width: 215,
            align: "right",
            sortable: false,
            editable: true,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            editoptions: {
                class: "numeric",
                maxlength: "13",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function () {
                            var num = 0;
                            $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            for (var i = 1; i <= 100; i++) {
                                var rowData = $(
                                    "#FrmFurikaeEdit_sprMeisai"
                                ).jqGrid("getRowData", i);

                                num =
                                    parseInt(num) +
                                    parseInt(
                                        clsComFnc.FncNz(rowData["KEIJO_GK"])
                                    );
                            }
                            $(".FrmFurikaeEdit.lblInputTotal").val(
                                num.toString().numFormat()
                            );
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            }

                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter
                                var selIRow = parseInt(me.lastsel) + 1;
                                $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );

                                var num = 0;
                                for (var i = 1; i <= 100; i++) {
                                    var rowData = $(
                                        "#FrmFurikaeEdit_sprMeisai"
                                    ).jqGrid("getRowData", i);

                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            clsComFnc.FncNz(rowData["KEIJO_GK"])
                                        );
                                }
                                $(".FrmFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                                $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );
                                var selNextId = "#" + selIRow + "_BUSYO_CD";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 40) {
                                //DOWN
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == 101) {
                                    return false;
                                }

                                var num = 0;
                                for (var i = 1; i <= 100; i++) {
                                    if (me.lastsel == i) {
                                        continue;
                                    }
                                    var rowData = $(
                                        "#FrmFurikaeEdit_sprMeisai"
                                    ).jqGrid("getLocalRow", i);

                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            clsComFnc.FncNz(rowData["KEIJO_GK"])
                                        );
                                }
                                if (
                                    $("#" + me.lastsel + "_KEIJO_GK").val() !==
                                    ""
                                ) {
                                    num =
                                        num +
                                        parseInt(
                                            $(
                                                "#" + me.lastsel + "_KEIJO_GK"
                                            ).val()
                                        );
                                }
                                $(".FrmFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                            }
                            if (key == 38) {
                                //UP
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    return false;
                                }

                                var num = 0;
                                for (var i = 1; i <= 100; i++) {
                                    if (me.lastsel == i) {
                                        continue;
                                    }
                                    var rowData = $(
                                        "#FrmFurikaeEdit_sprMeisai"
                                    ).jqGrid("getLocalRow", i);
                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            clsComFnc.FncNz(rowData["KEIJO_GK"])
                                        );
                                }
                                if (
                                    $("#" + me.lastsel + "_KEIJO_GK").val() !==
                                    ""
                                ) {
                                    num =
                                        num +
                                        parseInt(
                                            $(
                                                "#" + me.lastsel + "_KEIJO_GK"
                                            ).val()
                                        );
                                }
                                $(".FrmFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                            }
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 12, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];

    $("#FrmFurikaeEdit_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: me.ratio === 1.5 ? 240 : 300,
        rownumWidth: 60,
        rownumbers: true,
        colModel: me.colModel,
        onSelectRow: function (rowid, _status, e) {
            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });

            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;
                //ヘッダークリック以外
                if (cellIndex != 0) {
                    if (rowid && rowid != me.lastsel) {
                        $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowid;
                    }
                    $("#FrmFurikaeEdit_sprMeisai").jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });

                    $("#" + rowid + "_KEIJO_GK").css("text-align", "right");
                } else {
                    $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    clsComFnc.MsgBoxBtnFnc.Yes = me.del;
                    clsComFnc.MessageBox(
                        "削除します。よろしいですか？",
                        clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowid;
                }
                $("#FrmFurikaeEdit_sprMeisai").jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
                $("#" + rowid + "_KEIJO_GK").css("text-align", "right");
            }
        },
    });
    $("#FrmFurikaeEdit_sprMeisai").jqGrid("bindKeys");
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
    $(".FrmFurikaeEdit.cboKeiriBi").on("blur", function () {
        if (clsComFnc.CheckDate($(".FrmFurikaeEdit.cboKeiriBi")) == false) {
            $(".FrmFurikaeEdit.cboKeiriBi").val(me.cboYM);
            $(".FrmFurikaeEdit.cboKeiriBi").trigger("focus");
            $(".FrmFurikaeEdit.cboKeiriBi").select();
        } else {
        }
    });

    $(".FrmFurikaeEdit.txtMotKingaku").on("focus", function () {
        // $(".FrmFurikaeEdit.txtMotKingaku").css('color', 'black');
        var num = $(".FrmFurikaeEdit.txtMotKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");
        $(".FrmFurikaeEdit.txtMotKingaku").val(num);
    });

    $(".FrmFurikaeEdit.txtMotKingaku").on("blur", function () {
        var num = $(".FrmFurikaeEdit.txtMotKingaku").val();
        if (num.trimEnd() != "") {
            $(".FrmFurikaeEdit.txtMotKingaku").val(
                $(".FrmFurikaeEdit.txtMotKingaku").val().numFormat()
            );
        } else {
            $(".FrmFurikaeEdit.txtMotKingaku").val("");
        }

        if (num.indexOf("-") == -1) {
            $(".FrmFurikaeEdit.txtMotKingaku").css("color", "black");
        } else {
            $(".FrmFurikaeEdit.txtMotKingaku").css("color", "red");
        }
    });

    $(".FrmFurikaeEdit.txtMotKingaku").keyup(function (event) {
        if (event.keyCode == 39 || event.keyCode == 37) {
            return;
        }

        var num = $(".FrmFurikaeEdit.txtMotKingaku").val();
        var num_count = num.length;

        if (num.indexOf("-") == -1 && num_count <= 13) {
            $(".FrmFurikaeEdit.txtMotKingaku").val(num);
            return;
        }
        if (num.indexOf("-") == -1 && num_count == 14) {
            $(".FrmFurikaeEdit.txtMotKingaku").val(num.substring(0, 13));
            return;
        }
        if (num.indexOf("-") == 0 && num_count <= 14) {
            $(".FrmFurikaeEdit.txtMotKingaku").val(num);
            return;
        }
        if (num.indexOf("-") > 0) {
            var num = $(".FrmFurikaeEdit.txtMotKingaku")
                .val()
                .toString()
                .replace(/-/, "");
            $(".FrmFurikaeEdit.txtMotKingaku").val(num);
            return;
        }
    });

    $(".FrmFurikaeEdit.txtMotBusyoCD").on("blur", function () {
        var tmp = $(".FrmFurikaeEdit.txtMotBusyoCD").val().trimEnd();
        $(".FrmFurikaeEdit.txtMotBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFurikaeEdit.lblMotBusyoNM").val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmFurikaeEdit.lblMotBusyoNM").val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
    });

    $(".FrmFurikaeEdit.txtKamokuCD").on("blur", function () {
        var tmp = $(".FrmFurikaeEdit.txtKamokuCD").val().trimEnd();
        $(".FrmFurikaeEdit.txtMotKmkCD").val(tmp);
        $(".FrmFurikaeEdit.txtKamokuCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFurikaeEdit.txtMotKmkCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFurikaeEdit.txtHimokuCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFurikaeEdit.txtMotHmkCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmFurikaeEdit.lblKamokuNM").val("");
        $(".FrmFurikaeEdit.lblMotKamokuNM").val("");

        if (tmp == "") {
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";

        var arr = {
            Kamoku: tmp,
            Himoku: $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["data"]["intRtnCD"] == 1) {
                $(".FrmFurikaeEdit.lblKamokuNM").val(
                    result["data"]["strKamokuNM"]
                );
            }
            $(".FrmFurikaeEdit.lblMotKamokuNM").val(
                $(".FrmFurikaeEdit.lblKamokuNM").val()
            );
            $(".FrmFurikaeEdit.txtMotHmkCD").val(
                $(".FrmFurikaeEdit.txtHimokuCD").val()
            );
        };
        ajax.send(me.url, me.data, 0);
    });

    $(".FrmFurikaeEdit.txtMotKmkCD").on("blur", function () {
        var tmp = $(".FrmFurikaeEdit.txtMotKmkCD").val().trimEnd();
        $(".FrmFurikaeEdit.txtMotKmkCD").css(clsComFnc.GC_COLOR_NORMAL);

        $(".FrmFurikaeEdit.txtMotHmkCD").css(clsComFnc.GC_COLOR_NORMAL);

        $(".FrmFurikaeEdit.lblMotKamokuNM").val("");
        if (tmp == "") {
            return;
        }

        me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";

        var arr = {
            Kamoku: $(".FrmFurikaeEdit.txtMotKmkCD").val().trimEnd(),
            Himoku: $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["data"]["intRtnCD"] == 1) {
                $(".FrmFurikaeEdit.lblMotKamokuNM").val(
                    result["data"]["strKamokuNM"]
                );
            }
        };
        ajax.send(me.url, me.data, 0);
    });

    $(".FrmFurikaeEdit.cmdBack").click(function () {
        $("#DialogDivFurikae").dialog("close");
    });

    $(".FrmFurikaeEdit.cmdAction").click(function () {
        // $(".FrmFurikaeEdit.txtMotKmkCD").focus();
        $(".FrmFurikaeEdit.cmdAction").trigger("focus");

        if (me.FrmFurikae.PrpMenteFlg == "DEL") {
            clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteFurikae;
            clsComFnc.FncMsgBox("QY004");
        } else {
            $("#FrmFurikaeEdit_sprMeisai").jqGrid("saveRow", me.lastsel);
            if (me.FrmFurikae.PrpMenteFlg == "INS") {
                me.url = me.sys_id + "/" + me.id + "/fncControlNenChk";

                ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["row"] == 0) {
                        clsComFnc.FncMsgBox(
                            "E9999",
                            "コントロールマスタが存在しません！"
                        );
                        return;
                    }
                    var cboKeiriBiVal = $(".FrmFurikaeEdit.cboKeiriBi")
                        .val()
                        .toString()
                        .replace(/\//g, "")
                        .substr(0, 6);

                    if (cboKeiriBiVal < result["data"][0]["SYR_YMD"]) {
                        me.subMsgOutput(-2, "経理日", "cboKeiriBi");
                        return;
                    }

                    //入力ﾁｪｯｸ
                    me.fncCheck();
                };

                ajax.send(me.url, "", 0);
            } else {
                //入力ﾁｪｯｸ
                me.fncCheck();
            }
        }
    });

    shortcut.add("F9", function () {
        var situ = $(".HMS_F9").dialog("isOpen");

        if (situ == true) {
            return;
        }

        $(".FrmFurikaeEdit.cmdAction").trigger("click");
    });

    $(".FrmFurikaeEdit.cmdMotBs_S").click(function () {
        $(".FrmFurikaeEdit.txtMotBusyoCD").trigger("focus");
        me.showBusyoDialog();
    });

    $(".FrmFurikaeEdit.cmdSearchKmk").click(function () {
        // $(".FrmFurikaeEdit.txtKamokuCD").focus();
        me.showKamokuDialog("txtKamokuCD");
    });
    $(".FrmFurikaeEdit.cmdMotKmk_S").click(function () {
        // $(".FrmFurikaeEdit.txtMotKmkCD").focus();
        me.showKamokuDialog("txtMotKmkCD");
    });

    $(".FrmFurikaeEdit.txtHimokuCD").on("blur", function () {
        $(".FrmFurikaeEdit.lblKamokuNM").val("");
        $(".FrmFurikaeEdit.txtMotHmkCD").val("");
        $(".FrmFurikaeEdit.txtMotKmkCD").val("");
        $(".FrmFurikaeEdit.lblMotKamokuNM").val("");

        var tmp = $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd();
        var leng = tmp.length;

        if (leng == 1) {
            if (tmp == "0") {
                $(".FrmFurikaeEdit.txtHimokuCD").val("0" + tmp);
                $(".FrmFurikaeEdit.txtMotHmkCD").val("");
            } else {
                $(".FrmFurikaeEdit.txtHimokuCD").val("0" + tmp);
                $(".FrmFurikaeEdit.txtMotHmkCD").val("0" + tmp);
            }
        }

        if (leng == 2) {
            if (tmp == "00") {
                $(".FrmFurikaeEdit.txtMotHmkCD").val("");
            } else {
                $(".FrmFurikaeEdit.txtMotHmkCD").val(tmp);
            }
        }

        me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";

        var arr = {
            Kamoku: $(".FrmFurikaeEdit.txtKamokuCD").val().trimEnd(),
            Himoku: $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["data"]["intRtnCD"] == 1) {
                $(".FrmFurikaeEdit.lblKamokuNM").val(
                    result["data"]["strKamokuNM"]
                );
            }
            $(".FrmFurikaeEdit.lblMotKamokuNM").val(
                $(".FrmFurikaeEdit.lblKamokuNM").val()
            );

            $(".FrmFurikaeEdit.txtMotKmkCD").val(
                $(".FrmFurikaeEdit.txtKamokuCD").val()
            );
        };
        ajax.send(me.url, me.data, 0);
    });

    $(".FrmFurikaeEdit.txtMotHmkCD").on("blur", function () {
        $(".FrmFurikaeEdit.lblMotKamokuNM").val("");

        var tmp = $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd();
        var leng = tmp.length;

        if (leng == 1) {
            $(".FrmFurikaeEdit.txtMotHmkCD").val("0" + tmp);
        }

        me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";

        var arr = {
            Kamoku: $(".FrmFurikaeEdit.txtMotKmkCD").val().trimEnd(),
            Himoku: $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["data"]["intRtnCD"] == 1) {
                $(".FrmFurikaeEdit.lblMotKamokuNM").val(
                    result["data"]["strKamokuNM"]
                );
            }
        };
        ajax.send(me.url, me.data, 0);
    });

    $(".FrmFurikaeEdit.txtDenpyoNO").on("blur", function () {
        $(".FrmFurikaeEdit.txtDenpyoNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    // $(".FrmFurikaeEdit.cmdTorikomi").click(function()
    // {
    // me.subClearForm();
    // //取込伝票№が未入力の場合、ｴﾗｰ
    // if ($(".FrmFurikaeEdit.txtTorikomiDenpy").val().trimEnd() == "")
    // {
    // $(".FrmFurikaeEdit.txtTorikomiDenpy").css(clsComFnc.GC_COLOR_ERROR);
    // clsComFnc.ObjSelect = $(".FrmFurikaeEdit.txtTorikomiDenpy");
    // clsComFnc.FncMsgBox("W9999", '取込伝票№を入力してください！');
    // return;
    // }
    //
    // //同一伝票№が既に振替データに存在する場合、ｴﾗｰ
    //
    // me.url = me.sys_id + '/' + me.id + '/fncFurikaeExist';
    //
    // var arr =
    // {
    // 'DENPY_NO' : $(".FrmFurikaeEdit.txtTorikomiDenpy").val().trimEnd(),
    // 'KEIJOBI' : $(".FrmFurikaeEdit.cboKeiriBi").val()
    // }
    //
    // me.data =
    // {
    // request : arr,
    // };
    //
    // console.log(arr);
    //
    // ajax.receive = function(result)
    // {
    // console.log(result);
    // result = eval('(' + result + ')');
    //
    // if (result['result'] == false)
    // {
    // clsComFnc.FncMsgBox("E9999", result['data']);
    // return;
    // }
    //
    // if (result['row'] > 0)
    // {
    // clsComFnc.ObjSelect = $(".FrmFurikaeEdit.txtTorikomiDenpy");
    // $(".FrmFurikaeEdit.txtTorikomiDenpy").css(clsComFnc.GC_COLOR_ERROR);
    // clsComFnc.FncMsgBox("W0013", "取込伝票№");
    // return;
    // }
    //
    // }
    // ajax.send(me.url, me.data, 0);
    //
    // });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.init_control = function () {
        base_init_control();
        $(".FrmFurikaeEdit.txtMotKingaku").numeric({
            decimal: false,
            negative: true,
        });
        me.frmFurikaeInputLoad();
    };

    me.del = function () {
        var rowID = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
            "getRowData",
            rowID
        );

        for (var i = parseInt(rowID); i < 100; i++) {
            var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "getRowData",
                i + 1
            );
            $("#FrmFurikaeEdit_sprMeisai").jqGrid("setRowData", i, rowData);
        }
        $("#FrmFurikaeEdit_sprMeisai").jqGrid("setRowData", 100, me.col);

        var num = 0;
        for (var i = 1; i <= 100; i++) {
            var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "getRowData",
                i
            );

            num =
                parseInt(num) + parseInt(clsComFnc.FncNz(rowData["KEIJO_GK"]));
        }
        $(".FrmFurikaeEdit.lblInputTotal").val(num.toString().numFormat());
    };

    me.GetFncSetRtnDataKamoku = function (KAMOKUCD, KAMOKUNM, id) {
        if (me.RtnCD == 1) {
            $(".FrmFurikaeEdit." + id).val(KAMOKUCD);
            if (id == "txtKamokuCD") {
                $(".FrmFurikaeEdit.lblKamokuNM").val(KAMOKUNM);
                $(".FrmFurikaeEdit.lblMotKamokuNM").val(KAMOKUNM);
                $(".FrmFurikaeEdit.txtMotKmkCD").val(KAMOKUCD);
                // $('#FrmFurikaeEdit_sprMeisai').focus();
                // $('#FrmFurikaeEdit_sprMeisai').jqGrid('setSelection', 1, true);
            } else {
                $(".FrmFurikaeEdit.lblMotKamokuNM").val(KAMOKUNM);
                // $('#FrmFurikaeEdit_sprMeisai').focus();
                // $('#FrmFurikaeEdit_sprMeisai').jqGrid('setSelection', 1, true);
            }
            $(".FrmFurikaeEdit.txtMotBusyoCD").trigger("focus");
        } else {
            $(".FrmFurikaeEdit." + id).select();
        }
    };

    me.showKamokuDialog = function (id) {
        // $(".FrmFurikaeEdit." + id).focus();
        $("<div></div>")
            .attr("id", "FrmKamokuSearchDialogDiv")
            .insertAfter($("#FrmFurikae"));

        $("<div></div>").attr("id", "KAMOKUCD").insertAfter($("#FrmFurikae"));
        $("<div></div>").attr("id", "KAMOKUNM").insertAfter($("#FrmFurikae"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmFurikae"));

        $("#FrmKamokuSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 558 : 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#KAMOKUNM").hide();
                $("#KAMOKUCD").hide();
            },
            close: function () {
                me.RtnCD = $("#RtnCD").html();

                me.GetFncSetRtnDataKamoku(
                    $("#KAMOKUCD").html(),
                    $("#KAMOKUNM").html(),
                    id
                );

                $("#RtnCD").remove();
                $("#KAMOKUNM").remove();
                $("#KAMOKUCD").remove();
                $("#FrmKamokuSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmKamokuSearch";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#FrmKamokuSearchDialogDiv").html(result);

            $("#FrmKamokuSearchDialogDiv").dialog(
                "option",
                "title",
                "科目コード検索"
            );
            $("#FrmKamokuSearchDialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
    };
    me.showBusyoDialog = function () {
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmFurikae"));

        $("<div></div>").attr("id", "BUSYOCD").insertAfter($("#FrmFurikae"));
        $("<div></div>").attr("id", "BUSYONM").insertAfter($("#FrmFurikae"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmFurikae"));

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 558 : 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#BUSYONM").hide();
                $("#BUSYOCD").hide();
            },
            close: function () {
                me.RtnCD = $("#RtnCD").html();

                if (me.RtnCD == 1) {
                    $(".FrmFurikaeEdit.txtMotBusyoCD").val(
                        $("#BUSYOCD").html()
                    );
                    $(".FrmFurikaeEdit.lblMotBusyoNM").val(
                        $("#BUSYONM").html()
                    );
                    $(".FrmFurikaeEdit.txtMotKingaku").trigger("focus");
                    // $('#FrmFurikaeEdit_sprMeisai').focus();
                    // $('#FrmFurikaeEdit_sprMeisai').jqGrid('setSelection', 1, true);
                } else {
                    $(".FrmFurikaeEdit.txtMotBusyoCD").trigger("focus");
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);

            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
    };

    me.fncDeleteFurikae = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteFurikae";

        var arrayVal = {
            DENPYO: $(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd(),
            KEIJYO: $(".FrmFurikaeEdit.cboKeiriBi").val(),
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
            $("#DialogDivFurikae").dialog("close");
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncCheck = function () {
        //入力ﾁｪｯｸ
        if (!me.fncInputCheck()) {
            return;
        }

        //ｽﾌﾟﾚｯﾄﾞ入力ﾁｪｯｸ
        if (!me.fncInputSprChk()) {
            return;
        }

        //存在ﾁｪｯｸ
        me.fncExistsCheckKamoku("txtKamokuCD");
    };

    me.fncExistsCheckKamoku = function (id) {
        if (id == "txtKamokuCD") {
            var arr = {
                Kamoku: $(".FrmFurikaeEdit.txtKamokuCD").val().trimEnd(),
                Himoku: $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd(),
            };
        } else {
            var arr = {
                Kamoku: $(".FrmFurikaeEdit.txtMotKmkCD").val().trimEnd(),
                Himoku: $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd(),
            };
        }

        if (
            $(".FrmFurikaeEdit." + id)
                .val()
                .trimEnd() != ""
        ) {
            me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";

            me.data = {
                request: arr,
            };

            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["data"]["intRtnCD"] == -1) {
                    if (id == "txtKamokuCD") {
                        $(".FrmFurikaeEdit.txtKamokuCD").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        if (
                            $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd() !=
                            ""
                        ) {
                            $(".FrmFurikaeEdit.txtHimokuCD").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            me.subMsgOutput(-8, "科目コード", "txtKamokuCD");
                            return false;
                        }
                    } else {
                        $(".FrmFurikaeEdit.txtMotKmkCD").css(
                            clsComFnc.GC_COLOR_ERROR
                        );
                        if (
                            $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd() !=
                            ""
                        ) {
                            $(".FrmFurikaeEdit.txtMotHmkCD").css(
                                clsComFnc.GC_COLOR_ERROR
                            );
                            me.subMsgOutput(-8, "科目コード", "txtMotKmkCD");
                            return false;
                        }
                    }
                    return false;
                }

                if (id == "txtKamokuCD") {
                    me.fncExistsCheckKamoku("txtMotKmkCD");
                } else {
                    me.fncExistsCheck();
                }
            };
            ajax.send(me.url, me.data, 0);
        }
    };

    me.fncInputSprChk = function () {
        var data = $("#FrmFurikaeEdit_sprMeisai").jqGrid("getDataIDs");
        var blnInputFlg = false;
        for (rowID in data) {
            var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "getRowData",
                data[rowID]
            );

            //どれか一列でも入力されていた場合
            if (
                rowData["BUSYO_CD"].trimEnd() != "" ||
                rowData["KEIJO_GK"].trimEnd() != ""
            ) {
                var iColNo = 0;
                for (colID in rowData) {
                    switch (colID) {
                        case "BUSYO_CD":
                            intRtn = clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                clsComFnc.INPUTTYPE.NONE,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );

                            break;
                        case "KEIJO_GK":
                            intRtn = clsComFnc.FncSprCheck(
                                rowData[colID].toString().replace(/\,/g, ""),
                                0,
                                clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                        default:
                            break;
                    }

                    if (intRtn != 0) {
                        me.setFocus(rowID, colID);
                        clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
                        );
                        return false;
                    }

                    iColNo += 1;
                }
                //キー項目の必須ﾁｪｯｸ
                if (rowData["BUSYO_CD"].trimEnd() == "") {
                    me.setFocus(rowID, "BUSYO_CD");
                    clsComFnc.FncMsgBox("W0001", "部署ｺｰﾄﾞ");
                    return false;
                }

                //必須ﾁｪｯｸ
                if (rowData["KEIJO_GK"].trimEnd() == "") {
                    me.setFocus(rowID, "KEIJO_GK");
                    clsComFnc.FncMsgBox("W0001", "金額");
                    return false;
                }

                var blnInputFlg = true;
            }
        }

        if (!blnInputFlg) {
            me.setFocus(0, "BUSYO_CD");
            clsComFnc.FncMsgBox("W0017", "振替先データ");
            return false;
        }
        return true;
    };

    me.fncInputCheck = function () {
        var intRtn = "";
        if (me.FrmFurikae.PrpMenteFlg == "INS") {
            //伝票№
            intRtn = clsComFnc.FncTextCheck(
                $(".FrmFurikaeEdit.txtDenpyoNO"),
                1,
                clsComFnc.INPUTTYPE.NUMBER1
            );
            if (intRtn < 0) {
                me.subMsgOutput(intRtn, "伝票番号", "txtDenpyoNO");
                return false;
            }
        }

        //貸借区分
        var tmpVal = $(".FrmFurikaeEdit.txtTaisyakuKbn").val();
        if (tmpVal != "1" && tmpVal != "2") {
            me.subMsgOutput(-2, "貸借区分", "txtTaisyakuKbn");
            return false;
        }

        //科目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtKamokuCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "科目コード", "txtKamokuCD");
            return false;
        }

        //費目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtHimokuCD"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "費目コード", "txtHimokuCD");
            return false;
        }

        //元科目ｺｰﾄﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtMotKmkCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "振替元科目コード", "txtMotKmkCD");
            return false;
        }

        //元費目ｺｰﾄﾞ
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtMotHmkCD"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "振替元費目コード", "txtMotHmkCD");
            return false;
        }

        //元部署コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtMotBusyoCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "振替元部署コード", "txtMotBusyoCD");
            return false;
        }

        //元金額
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmFurikaeEdit.txtMotKingaku"),
            1,
            clsComFnc.INPUTTYPE.NUMBER2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "振替元金額", "txtMotKingaku");
            return false;
        }

        return true;
    };

    me.fncExistsCheck = function () {
        //部署コード存在ﾁｪｯｸ
        me.url = me.sys_id + "/" + me.id + "/FncGetBusyoMstValue";
        var arr = {
            BusyoCD: $(".FrmFurikaeEdit.txtMotBusyoCD").val().trimEnd(),
        };
        me.data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["data"]["intRtnCD"] == -1) {
                $(".FrmFurikaeEdit.txtMotBusyoCD").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                me.subMsgOutput(-8, "振替元部署コード", "txtMotBusyoCD");
                return;
            }

            //ｽﾌﾟﾚｯﾄﾞ存在ﾁｪｯｸ
            me.fncExistsSprChk();
        };
        ajax.send(me.url, me.data, 0);
    };
    //
    me.fncExistsSprChk = function () {
        me.url = me.sys_id + "/" + me.id + "/fncExistsSprChk";
        var arr = new Array();
        var data = $("#FrmFurikaeEdit_sprMeisai").jqGrid("getDataIDs");

        for (rowID in data) {
            var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "getRowData",
                data[rowID]
            );

            if (clsComFnc.FncNv(rowData["BUSYO_CD"]) != "") {
                arr.push(rowData["BUSYO_CD"]);
            }
        }

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["data"]["intRtnCD"] == -1) {
                me.setFocus(result["data"]["errMsg"], "BUSYO_CD");
                clsComFnc.FncMsgBox("W0008", "部署");
                return;
            }
            //金額合計ﾁｪｯｸ
            var num1 = parseInt(
                $(".FrmFurikaeEdit.txtMotKingaku")
                    .val()
                    .toString()
                    .replace(/\,/g, "")
            );
            var num2 = parseInt(
                $(".FrmFurikaeEdit.lblInputTotal")
                    .val()
                    .toString()
                    .replace(/\,/g, "")
            );
            if (num1 != num2) {
                clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAction;
                clsComFnc.FncMsgBox(
                    "QY999",
                    "振替元金額と振替先金額合計が一致しません。登録しますか？"
                );
            } else {
                me.cmdAction();
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.cmdAction = function () {
        if (me.FrmFurikae.PrpMenteFlg == "INS") {
            //同一伝票番号のデータが存在している場合はｴﾗｰ(新規)
            me.url = me.sys_id + "/" + me.id + "/fncFurikaeExistChk";

            var arr = {
                DENPY: $(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd(),
                KEIJYO: $(".FrmFurikaeEdit.cboKeiriBi").val(),
            };

            me.data = {
                request: arr,
            };

            ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }

                if (result["row"] > 0) {
                    clsComFnc.ObjSelect = $(".FrmFurikaeEdit.txtDenpyoNO");
                    clsComFnc.FncMsgBox("W0013", "取込伝票№");
                    return;
                }

                me.fncInsertFurikae();
            };
            ajax.send(me.url, me.data, 0);
        } else {
            me.url = me.sys_id + "/" + me.id + "/fncDeleteFurikae";

            var arrayVal = {
                DENPYO: $(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd(),
                KEIJYO: $(".FrmFurikaeEdit.cboKeiriBi").val(),
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

                me.fncInsertFurikae();
            };

            ajax.send(me.url, me.data, 0);
        }
    };

    me.fncInsertFurikae = function () {
        me.url = me.sys_id + "/" + me.id + "/fncInsertFurikae";

        var arr = new Array();
        arr[0] = {
            DENPYO: $(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd(),
            KEIJYO: $(".FrmFurikaeEdit.cboKeiriBi").val(),
            TAISK: $(".FrmFurikaeEdit.txtTaisyakuKbn").val().trimEnd(),
            BUSYO: $(".FrmFurikaeEdit.txtMotBusyoCD").val().trimEnd(),
            KAMOK: $(".FrmFurikaeEdit.txtMotKmkCD").val().trimEnd(),
            HIMOK: $(".FrmFurikaeEdit.txtMotHmkCD").val().trimEnd(),
            GOKEI: $(".FrmFurikaeEdit.txtMotKingaku")
                .val()
                .toString()
                .replace(/\,/g, ""),
        };
        var data = $("#FrmFurikaeEdit_sprMeisai").jqGrid("getDataIDs");
        var i = 1;
        for (rowID in data) {
            var rowData = $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "getRowData",
                data[rowID]
            );

            if (clsComFnc.FncNv(rowData["BUSYO_CD"]) != "") {
                arr[i] = {
                    DENPYO: $(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd(),
                    KEIJYO: $(".FrmFurikaeEdit.cboKeiriBi").val(),
                    TAISK: $(".FrmFurikaeEdit.txtTaisyakuKbn").val().trimEnd(),
                    BUSYO: clsComFnc.FncNv(rowData["BUSYO_CD"]),
                    KAMOK: $(".FrmFurikaeEdit.txtKamokuCD").val().trimEnd(),
                    HIMOK: $(".FrmFurikaeEdit.txtHimokuCD").val().trimEnd(),
                    GOKEI: clsComFnc
                        .FncNv(rowData["KEIJO_GK"])
                        .replace(/\,/g, ""),
                };
                // arr.push(rowData);
                //---20151008 li INS S.
                i++;
                //---20151008 li INS E.
            }
            //---20151008 li DEL S.
            //i++;
            //---20151008 li DEL E.
        }

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (me.FrmFurikae.PrpMenteFlg == "INS") {
                me.subClearForm();
                $(".FrmFurikaeEdit.txtKamokuCD").trigger("focus");
            } else {
                $("#DialogDivFurikae").dialog("close");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.frmFurikaeInputLoad = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDataSet";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.getAllBusyo = result["data1"];
                me.KamokuArray = result["data2"];
                me.getAllBusyoJqGrid = result["data3"];

                var myDate = new Date();
                var tmpMonth = (myDate.getMonth() + 1).toString();
                if (tmpMonth.length < 2) {
                    tmpMonth = "0" + tmpMonth.toString();
                }

                var md = me.DayNumOfMonth(
                    myDate.getFullYear().toString(),
                    tmpMonth
                );
                var tmpNowDate =
                    myDate.getFullYear().toString() +
                    "/" +
                    tmpMonth.toString() +
                    "/" +
                    md;
                $(".FrmFurikaeEdit.cboKeiriBi").val(tmpNowDate);
                me.cboYM = tmpNowDate;
                me.subClearForm();

                if (
                    me.FrmFurikae.PrpMenteFlg == "UPD" ||
                    me.FrmFurikae.PrpMenteFlg == "DEL"
                ) {
                    $(".FrmFurikaeEdit.cboKeiriBi").val(
                        me.FrmFurikae.prpKeijyoBi
                    );
                    $(".FrmFurikaeEdit.txtDenpyoNO").val(
                        me.FrmFurikae.prpDenpy_NO
                    );
                    $(".FrmFurikaeEdit.cboKeiriBi").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".FrmFurikaeEdit.txtDenpyoNO").attr(
                        "disabled",
                        "disabled"
                    );
                    me.url = me.sys_id + "/" + me.id + "/fncFurikaeMotSet";

                    var arr = {
                        KEIJYO: me.FrmFurikae.prpKeijyoBi,
                        DENPY: me.FrmFurikae.prpDenpy_NO,
                        blnTorikomi: false,
                    };

                    me.data = {
                        request: arr,
                    };

                    ajax.receive = function (result) {
                        result = eval("(" + result + ")");

                        if (result["result"] == false) {
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }

                        if (result["row"] == 0) {
                            clsComFnc.FncMsgBox("I0001");
                            // me.blnflg = false;
                            return;
                        } else {
                            me.subMotoDataSet(result["data"][0]);
                            if (result["row"] > 1) {
                                clsComFnc.FncMsgBox(
                                    "E9999",
                                    "振替元データが複数件存在しています！"
                                );
                                return;
                            }
                        }

                        me.url = me.sys_id + "/" + me.id + "/fncFurikaeSet";

                        var arr = {
                            KEIJOBI: me.FrmFurikae.prpKeijyoBi,
                            DENPYNO: me.FrmFurikae.prpDenpy_NO,
                            blnTorikomi: false,
                        };

                        me.data = {
                            request: arr,
                        };

                        ajax.receive = function (result) {
                            result = eval("(" + result + ")");

                            if (result["result"] == false) {
                                clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }

                            // if (result['row'] == 0 && me.blnflg == false)
                            if (result["row"] == 0) {
                                clsComFnc.FncMsgBox("I0001");
                                return;
                            }

                            me.subSakiDataSet(result["data"]);

                            // $(".FrmFurikaeEdit.cboKeiriBi").attr("disabled", "disabled");
                            // $(".FrmFurikaeEdit.txtDenpyoNO").attr("disabled", "disabled");
                            // // $(".FrmFurikaeEdit.txtTorikomiDenpy").attr("disabled", "disabled");
                            // $(".FrmFurikaeEdit.cmdTorikomi").button("disable");
                            $(".FrmFurikaeEdit.txtTaisyakuKbn").trigger(
                                "focus"
                            );

                            if (me.FrmFurikae.PrpMenteFlg == "DEL") {
                                me.subDelControl();
                            }
                        };

                        ajax.send(me.url, me.data, 0);
                    };

                    ajax.send(me.url, me.data, 0);
                }
            }
        };

        ajax.send(me.url, "", 0);
    };

    me.subDelControl = function () {
        $(".FrmFurikaeEdit.txtTaisyakuKbn").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtKamokuCD").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtHimokuCD").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtMotBusyoCD").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtMotKmkCD").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtMotHmkCD").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.txtMotKingaku").attr("disabled", "disabled");
        $(".FrmFurikaeEdit.lblInputTotal").attr("disabled", "disabled");
        $("#FrmFurikaeEdit_sprMeisai").closest(".ui-jqgrid").block();
        $(".FrmFurikaeEdit.cmdSearchKmk").button("disable");
        $(".FrmFurikaeEdit.cmdMotBs_S").button("disable");
        $(".FrmFurikaeEdit.cmdMotKmk_S").button("disable");
        $(".FrmFurikaeEdit.cmdAction").text("削除");

        $(".FrmFurikaeEdit.cmdAction").trigger("focus");
    };

    me.subSakiDataSet = function (objDr) {
        $(".FrmFurikaeEdit.txtTaisyakuKbn").val(
            clsComFnc.FncNv(objDr[0]["TAISK_KB"]).trimEnd()
        );
        $(".FrmFurikaeEdit.txtKamokuCD").val(
            clsComFnc.FncNv(objDr[0]["KAMOK_CD"]).trimEnd()
        );
        $(".FrmFurikaeEdit.lblKamokuNM").val(
            clsComFnc.FncNv(objDr[0]["KAMOKNM"]).trimEnd()
        );
        //---20151010 li UPD S.
        //$(".FrmFurikaeEdit.txtHimokuCD").val(clsComFnc.FncNv(objDr[0]['HIMOK_CD']).trimEnd());
        if (clsComFnc.FncNv(objDr[0]["HIMOK_CD"]).trimEnd() == "00") {
            $(".FrmFurikaeEdit.txtHimokuCD").val("");
        } else {
            $(".FrmFurikaeEdit.txtHimokuCD").val(
                clsComFnc.FncNv(objDr[0]["HIMOK_CD"]).trimEnd()
            );
        }
        //---20151010 li UPD E.
        $("#FrmFurikaeEdit_sprMeisai").jqGrid("clearGridData");
        var lblInputTotal = 0;
        for (i = 1; i <= objDr.length; i++) {
            me.colomn = {
                BUSYO_CD: clsComFnc.FncNv(objDr[parseInt(i) - 1]["BUSYO_CD"]),
                BUSYO_NM: clsComFnc.FncNv(objDr[parseInt(i) - 1]["BUSYO_NM"]),
                KEIJO_GK: clsComFnc.FncNz(objDr[parseInt(i) - 1]["KEIJO_GK"]),
            };

            $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "addRowData",
                parseInt(i),
                me.colomn
            );
            lblInputTotal =
                parseInt(lblInputTotal) + parseInt(me.colomn["KEIJO_GK"]);
        }

        for (i = parseInt(objDr.length) + 1; i <= 100; i++) {
            $("#FrmFurikaeEdit_sprMeisai").jqGrid(
                "addRowData",
                parseInt(i),
                me.col
            );
        }

        $(".FrmFurikaeEdit.lblInputTotal").val(
            lblInputTotal.toString().numFormat()
        );
    };

    me.subMotoDataSet = function (objMotDr) {
        $(".FrmFurikaeEdit.txtMotKmkCD").val(
            clsComFnc.FncNv(objMotDr["KAMOK_CD"])
        );
        $(".FrmFurikaeEdit.lblMotKamokuNM").val(
            clsComFnc.FncNv(objMotDr["KAMOKNM"])
        );
        //---20151010 li UPD S.
        //$(".FrmFurikaeEdit.txtMotHmkCD").val(clsComFnc.FncNv(objMotDr['HIMOK_CD']));
        if (clsComFnc.FncNv(objMotDr["HIMOK_CD"]).trimEnd() == "00") {
            $(".FrmFurikaeEdit.txtMotHmkCD").val("");
        } else {
            $(".FrmFurikaeEdit.txtMotHmkCD").val(
                clsComFnc.FncNv(objMotDr["HIMOK_CD"]).trimEnd()
            );
        }
        //---20151010 li UPD E.
        $(".FrmFurikaeEdit.txtMotBusyoCD").val(
            clsComFnc.FncNv(objMotDr["BUSYO_CD"])
        );
        $(".FrmFurikaeEdit.lblMotBusyoNM").val(
            clsComFnc.FncNv(objMotDr["BUSYO_NM"])
        );
        $(".FrmFurikaeEdit.txtMotKingaku").val(
            clsComFnc.FncNz(objMotDr["KEIJO_GK"]).numFormat()
        );
    };

    me.subClearForm = function () {
        var intKeta = 0;
        if ($(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd() != "") {
            intKeta = $(".FrmFurikaeEdit.txtDenpyoNO").val().length;
            $(".FrmFurikaeEdit.txtDenpyoNO")
                .val(
                    parseInt($(".FrmFurikaeEdit.txtDenpyoNO").val().trimEnd()) +
                        1
                )
                .toString()
                .padLeft(intKeta, "0");
        }
        $(".FrmFurikaeEdit.txtHimokuCD").val("");
        $(".FrmFurikaeEdit.txtMotBusyoCD").val("");
        $(".FrmFurikaeEdit.txtMotHmkCD").val("");
        $(".FrmFurikaeEdit.txtMotKingaku").val("");
        $(".FrmFurikaeEdit.lblMotBusyoNM").val("");
        $(".FrmFurikaeEdit.lblInputTotal").val("");
        $("#FrmFurikaeEdit_sprMeisai").jqGrid("clearGridData");
        for (i = 1; i <= 100; i++) {
            me.col = {
                BUSYO_CD: "",
                BUSYO_NM: "",
                KEIJO_GK: "",
            };
            $("#FrmFurikaeEdit_sprMeisai").jqGrid("addRowData", i, me.col);
        }
    };

    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID) + 1;
        $("#FrmFurikaeEdit_sprMeisai").jqGrid("setSelection", rowNum);

        var ceil = rowNum + "_" + colID;
        clsComFnc.ObjFocus = $("#" + ceil);
        clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.subMsgOutput = function (intErrMsgno, strerrmsg, id) {
        switch (intErrMsgno) {
            case -1:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0001", strerrmsg);

                break;
            case -2:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0002", strerrmsg);
                break;
            case -3:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0003", strerrmsg);
                break;
            case -6:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0006", strerrmsg);
                break;
            case -7:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0007", strerrmsg);
                break;
            case -8:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0008", strerrmsg);
                break;
            case -9:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W9999", strerrmsg);
                break;
            case -15:
                clsComFnc.ObjSelect = $(".FrmFurikaeEdit." + id);
                clsComFnc.FncMsgBox("W0015", strerrmsg);
                break;
        }
    };

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substr(0, 1) + (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                } else if (
                    inputValue == "0" &&
                    keycode >= 49 &&
                    keycode <= 57
                ) {
                    inputValue = (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                }

                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substr(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substr(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substr(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    me.DayNumOfMonth = function (Year, Month) {
        var d = new Date(Year, Month, 0);
        return d.getDate();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFurikaeEdit = new R4.FrmFurikaeEdit();

    o_R4K_R4K.FrmFurikae.FrmFurikaeEdit = o_R4_FrmFurikaeEdit;
    o_R4_FrmFurikaeEdit.FrmFurikae = o_R4K_R4K.FrmFurikae;
    o_R4_FrmFurikaeEdit.load();
});
