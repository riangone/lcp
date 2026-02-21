/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20220915           bug               登録用の行が表示されないと画面表示不正          LUJUNXIA
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("JKSYS.FrmKyotenFurikaeEdit");

JKSYS.FrmKyotenFurikaeEdit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmKyotenFurikaeEdit";
    me.sys_id = "JKSYS";
    me.g_url = "JKSYS/FrmKyotenFurikaeEdit/subSpreadReShow";
    me.data = "";
    me.FrmKyotenFurikae = null;
    me.lastsel = 0;
    me.strTougetu = "";
    me.grid_id = "#FrmKyotenFurikaeEdit_sprList";
    me.col = {
        SYAIN_CD: "",
        SYAIN_NM: "",
        FURIKAE_KIN: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKyotenFurikaeEdit.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKyotenFurikaeEdit.cmdBack",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyotenFurikaeEdit.cboKeiriBi",
        type: "datepicker3",
        handle: "",
    });
    me.option = {
        rowNum: 0,
        rownumWidth: 30,
        rownumbers: true,
        multiselect: false,
        //20220915 LUJUNXIA INS S
        //登録用の行が表示されないと画面表示不正の問題修正
        scroll: 50,
        caption: "",
        //20220915 LUJUNXIA INS E
    };
    me.colModel = [
        {
            name: "SYAIN_CD",
            label: "社員番号",
            index: "SYAIN_CD",
            width: 95,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "5",
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e);
                        },
                    },
                    //鼠标离开单元格的事件
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //上下键根据code找name事件
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getSyainName(e);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 175,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "FURIKAE_KIN",
            label: "金額",
            index: "FURIKAE_KIN",
            width: 210,
            align: "right",
            sortable: false,
            editable: true,
            //设置格式化
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
            editoptions: {
                maxlength: "13",
                //加入正则判断 只能输入正数负数
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^-?\d*$/.test(value);
                    });
                },
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            //振替先金額合計
                            var num = 0;
                            if (e && e.target) {
                                if ($(e.target).val() == "-0") {
                                    $(e.target).val("0");
                                }
                                var vaGet = $.trim($(e.target).val());
                                if (vaGet == "") {
                                    vaGet = 0;
                                }
                                var DataIDs = $(me.grid_id).jqGrid(
                                    "getDataIDs"
                                );
                                var rowcount = DataIDs.length;
                                num = parseInt(num) + parseInt(vaGet);

                                for (var i = 0; i < rowcount; i++) {
                                    var rowData = $(me.grid_id).jqGrid(
                                        "getRowData",
                                        i
                                    );
                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            me.clsComFnc.FncNz(
                                                rowData["FURIKAE_KIN"]
                                            )
                                        );
                                }
                                $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                            }
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Enter,Tab
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                //enter
                                var selIRow = parseInt(me.lastsel) + 1;
                                var DataIDs = $(
                                    "#FrmKyotenFurikaeEdit_sprList"
                                ).jqGrid("getDataIDs");
                                var rowcount = DataIDs.length;
                                if (selIRow >= rowcount) {
                                    var idSyain =
                                        "#" + me.lastsel + "_" + "SYAIN_CD";
                                    var vaSyain = $.trim($(idSyain).val());
                                    var idKin =
                                        "#" + me.lastsel + "_" + "FURIKAE_KIN";
                                    var vaKin = $.trim($(idKin).val());
                                    //添加了回车和Tab的判断区别操作
                                    if (key == 13) {
                                        if (vaSyain != "" || vaKin != "") {
                                            $(me.grid_id).jqGrid(
                                                "saveRow",
                                                me.lastsel
                                            );

                                            var num = 0;
                                            for (var i = 0; i < rowcount; i++) {
                                                var rowData = $(
                                                    me.grid_id
                                                ).jqGrid("getRowData", i);

                                                num =
                                                    parseInt(num) +
                                                    parseInt(
                                                        me.clsComFnc.FncNz(
                                                            rowData[
                                                                "FURIKAE_KIN"
                                                            ]
                                                        )
                                                    );
                                            }
                                            $(
                                                ".FrmKyotenFurikaeEdit.lblInputTotal"
                                            ).val(num.toString().numFormat());
                                            me.colomn = {
                                                SYAIN_CD: "",
                                                SYAIN_NM: "",
                                                FURIKAE_KIN: "",
                                            };

                                            $(me.grid_id).jqGrid(
                                                "addRowData",
                                                selIRow,
                                                me.colomn
                                            );
                                            //保持新追加的行为编辑状态
                                            $(me.grid_id).jqGrid(
                                                "setSelection",
                                                selIRow,
                                                true
                                            );
                                        }
                                    }
                                } else {
                                    $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );

                                    var num = 0;
                                    for (var i = 0; i < rowcount; i++) {
                                        var rowData = $(
                                            "#FrmKyotenFurikaeEdit_sprList"
                                        ).jqGrid("getRowData", i);

                                        num =
                                            parseInt(num) +
                                            parseInt(
                                                me.clsComFnc.FncNz(
                                                    rowData["FURIKAE_KIN"]
                                                )
                                            );
                                    }
                                    $(
                                        ".FrmKyotenFurikaeEdit.lblInputTotal"
                                    ).val(num.toString().numFormat());
                                }
                            }
                            if (key == 40) {
                                //DOWN
                                //最后一行处于编辑状态
                                var DataIDs = $(
                                    "#FrmKyotenFurikaeEdit_sprList"
                                ).jqGrid("getDataIDs");
                                var rowcount = DataIDs.length;
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow >= rowcount) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }

                                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                    "saveRow",
                                    selIRow
                                );

                                var num = 0;
                                for (var i = 0; i < rowcount; i++) {
                                    var rowData = $(
                                        "#FrmKyotenFurikaeEdit_sprList"
                                    ).jqGrid("getRowData", i);

                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            me.clsComFnc.FncNz(
                                                rowData["FURIKAE_KIN"]
                                            )
                                        );
                                }
                                $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                            }
                            //输“-”时操作:-_,-
                            if (key == 109 || key == 189) {
                                if (me.add_0(e)) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            if (key == 38) {
                                //UP
                                //第一行处于编辑状态
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == -1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }

                                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                    "saveRow",
                                    selIRow
                                );
                                var DataIDs = $(
                                    "#FrmKyotenFurikaeEdit_sprList"
                                ).jqGrid("getDataIDs");
                                var rowcount = DataIDs.length;

                                var num = 0;
                                for (var i = 0; i < rowcount; i++) {
                                    var rowData = $(
                                        "#FrmKyotenFurikaeEdit_sprList"
                                    ).jqGrid("getRowData", i);

                                    num =
                                        parseInt(num) +
                                        parseInt(
                                            me.clsComFnc.FncNz(
                                                rowData["FURIKAE_KIN"]
                                            )
                                        );
                                }
                                $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
                                    num.toString().numFormat()
                                );
                            }
                        },
                    },
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            me.keyups(e);
                        },
                    },
                ],
            },
        },
        {
            name: "INPUTED",
            label: "入力済み",
            index: "INPUTED",
            width: 190,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];

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

    $(".FrmKyotenFurikaeEdit.txtFurikaeKin").on("focus", function () {
        var num = $(".FrmKyotenFurikaeEdit.txtFurikaeKin")
            .val()
            .toString()
            .replace(/\,/g, "");
        $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(num);
    });

    $(".FrmKyotenFurikaeEdit.txtFurikaeKin").on("blur", function () {
        var num = $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val();
        if (num.trimEnd() != "") {
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(
                $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val().numFormat()
            );

            $("#FrmKyotenFurikaeEdit_sprList").jqGrid("saveRow", 0);
            var rowData = $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                "getRowData",
                0
            );
            if (rowData["FURIKAE_KIN"] == "") {
                var Kin =
                    parseInt(
                        $(".FrmKyotenFurikaeEdit.txtFurikaeKin")
                            .val()
                            .replace(/\,/g, "")
                    ) * -1;
                rowData["FURIKAE_KIN"] = Kin;
                var num =
                    parseInt($(".FrmKyotenFurikaeEdit.lblInputTotal").val()) +
                    Kin;
                $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
                    num.toString().numFormat()
                );
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                    "setRowData",
                    0,
                    rowData
                );
            }
        }
    });

    $(".FrmKyotenFurikaeEdit.txtFurikaeKin").keyup(function () {
        if (event.keyCode == 39 || event.keyCode == 37) {
            return;
        }

        var num = $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val();
        var num_count = num.length;

        if (num.indexOf("-") == -1 && num_count <= 13) {
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(num);
            return;
        }
        if (num.indexOf("-") == -1 && num_count == 14) {
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(num.substring(0, 13));
            return;
        }
        if (num.indexOf("-") == 0 && num_count <= 14) {
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(num);
            return;
        }
        if (num.indexOf("-") > 0) {
            var num = $(".FrmKyotenFurikaeEdit.txtFurikaeKin")
                .val()
                .toString()
                .replace(/-/, "");
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(num);
            return;
        }
    });

    $(".FrmKyotenFurikaeEdit.txtSyainCD").on("blur", function () {
        var tmp = $(".FrmKyotenFurikaeEdit.txtSyainCD").val().trimEnd();

        for (key in me.getAllSyainJqGrid) {
            if (me.getAllSyainJqGrid[key]["SYAIN_NO"] == tmp) {
                $(".FrmKyotenFurikaeEdit.lblSyainNM").val(
                    me.getAllSyainJqGrid[key]["SYAIN_NM"]
                );
                break;
            } else {
                $(".FrmKyotenFurikaeEdit.lblSyainNM").val("");
            }
        }
        $(".FrmKyotenFurikaeEdit.txtSyainCD").css(me.clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmKyotenFurikaeEdit.txtCMNNO").on("blur", function () {
        var tmp = $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd();
        $(".FrmKyotenFurikaeEdit.lblUCNO").val("");
        $(".FrmKyotenFurikaeEdit.txtCMNNO").css(me.clsComFnc.GC_COLOR_NORMAL);

        if (tmp == "") {
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/fncGetUCNO";

        me.data = {
            strCMNNO: tmp,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (result["row"] > 0) {
                $(".FrmKyotenFurikaeEdit.lblUCNO").val(
                    result["data"][0]["UC_NO"]
                );
            } else {
                $(".FrmKyotenFurikaeEdit.lblUCNO").val("");
            }
        };
        me.ajax.send(me.url, me.data, 0);
    });

    $(".FrmKyotenFurikaeEdit.txtDispMoji").on("blur", function () {
        $(".FrmKyotenFurikaeEdit.txtDispMoji").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
    });

    $(".FrmKyotenFurikaeEdit.cmdBack").click(function () {
        $(".FrmKyotenFurikaeEdit.body").dialog("close");
    });

    $(".FrmKyotenFurikaeEdit.cmdAction").click(function () {
        me.cmdAction_Click();
    });

    shortcut.add("F9", function () {
        var situ = $(".HMS_F9").dialog("isOpen");

        if (situ == true) {
            return;
        }

        $(".FrmKyotenFurikaeEdit.cmdAction").trigger("click");
    });

    // ==========
    // = イベント end =
    // ==========
    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));
    me.before_close = function () {};
    if (requestdata) {
        me.strNengetu = requestdata["strNengetu"];
        me.strEdaNO = requestdata["strEdaNO"];
        me.strCMN_NO = requestdata["strCMN_NO"];
        me.strMenteFlg = requestdata["strMenteFlg"];
    }

    localStorage.removeItem("requestdata");
    var width = me.ratio === 1.5 ? 602 : 620;
    var height = me.ratio === 1.5 ? 558 : 680;
    $(".FrmKyotenFurikaeEdit.body").dialog({
        autoOpen: false,
        width: width,
        height: height,
        modal: true,
        title: "奨励金用データ入力",
        open: function () {},
        close: function () {
            me.before_close();
            $(".FrmKyotenFurikaeEdit.body").remove();
        },
    });
    $(".FrmKyotenFurikaeEdit.body").dialog("open");
    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.init_control = function () {
        base_init_control();
        me.frmFurikaeInputLoad();
    };
    // **********************************************************************
    // 処 理 名：フォームロード
    // 関 数 名：frmFurikaeInputLoad
    // 引    数：無し
    // 戻 り 値：無し
    // 処理説明：各種初期値設定
    // **********************************************************************
    me.frmFurikaeInputLoad = function () {
        $(".FrmKyotenFurikaeEdit.txtFurikaeKin").numeric({
            decimal: false,
            negative: true,
        });

        //画面項目ｸﾘｱ
        me.subClearForm();
        $("#FrmKyotenFurikaeEdit_sprList").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncFurikaeInputLoad";
        if (me.strMenteFlg == "INS") {
            me.data = {
                flg: "INS",
            };
        } else {
            me.data = {
                flg: "UPDDEL",
                strMSKb: "M",
                txtEdaNO: me.strEdaNO,
                txtCMNNO: me.strCMN_NO,
                GetTougetuNew: me.strNengetu,
            };
        }

        me.ajax.receive = function (result) {
            var blnflg = true;
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".FrmKyotenFurikaeEdit.body").dialog("close");
                return;
            }
            if (result["row"] <= 0) {
                //コントロールマスタが存在していない場合
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                return;
            }
            //コンボボックスに当月年月を設定
            me.strTougetu = me.clsComFnc.FncNv(
                result["data"]["GetTougetu"][0]["TOUGETU"].substring(0, 4) +
                    result["data"]["GetTougetu"][0]["TOUGETU"].substring(5, 7)
            );
            $(".FrmKyotenFurikaeEdit.cboKeiriBi").val(me.strTougetu);
            me.getAllSyainJqGrid = result["data"]["AllSyainJqGrid"];

            if (me.strMenteFlg == "UPD" || me.strMenteFlg == "DEL") {
                //年月
                $(".FrmKyotenFurikaeEdit.cboKeiriBi").val(me.strNengetu);
                //SEQ番号
                $(".FrmKyotenFurikaeEdit.txtEdaNO").val(me.strEdaNO);
                //注文書番号
                $(".FrmKyotenFurikaeEdit.txtCMNNO").val(me.strCMN_NO);
                if (result["data"]["FurikaeExist"].length == 0) {
                    blnflg = false;
                } else {
                    var objDrFri = result["data"]["FurikaeExist"][0];
                    $(".FrmKyotenFurikaeEdit.lblUCNO").val(objDrFri["UC_NO"]);
                    $(".FrmKyotenFurikaeEdit.txtSyainCD").val(
                        objDrFri["SYAIN_CD"]
                    );
                    $(".FrmKyotenFurikaeEdit.lblSyainNM").val(
                        objDrFri["SYAIN_NM"]
                    );
                    $(".FrmKyotenFurikaeEdit.txtDispMoji").val(
                        objDrFri["DISP_MOJI"]
                    );
                    $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val(
                        objDrFri["FURIKAE_KIN"].numFormat()
                    );
                }

                var arr = {
                    GetTougetuNew: me.strNengetu,
                    strMSKb: "S",
                    txtEdaNO: me.strEdaNO,
                    txtCMNNO: me.strCMN_NO,
                };

                var complete_fun = function (bErrorFlag, result) {
                    if (result["error"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        $(".FrmKyotenFurikaeEdit.body").dialog("close");
                        return;
                    }
                    if (bErrorFlag == "nodata" && blnflg == false) {
                        //該当するデータは存在しません。
                        me.clsComFnc.FncMsgBox("I0001");
                        return;
                    }
                    var objDrSaki = result["rows"];
                    me.lngFurikaeKin = 0;
                    for (i = 1; i <= objDrSaki.length; i++) {
                        me.lngFurikaeKin =
                            me.lngFurikaeKin +
                            parseInt(
                                me.clsComFnc.FncNz(
                                    objDrSaki[i - 1]["cell"]["FURIKAE_KIN"]
                                )
                            );
                    }
                    $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
                        me.lngFurikaeKin.toString().numFormat()
                    );
                    if (me.strMenteFlg == "UPD") {
                        $(".FrmKyotenFurikaeEdit.txtSyainCD").trigger("focus");
                        $(".FrmKyotenFurikaeEdit.txtCMNNO").attr(
                            "disabled",
                            "disabled"
                        );
                        if ($(".FrmKyotenFurikaeEdit.txtCMNNO").val() != "") {
                            $(".FrmKyotenFurikaeEdit.txtDispMoji").attr(
                                "disabled",
                                "disabled"
                            );
                        }
                    } else {
                        $(".FrmKyotenFurikaeEdit.txtSyainCD").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".FrmKyotenFurikaeEdit.txtCMNNO").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".FrmKyotenFurikaeEdit.txtDispMoji").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".FrmKyotenFurikaeEdit.txtFurikaeKin").attr(
                            "disabled",
                            "disabled"
                        );
                        $("#FrmKyotenFurikaeEdit_sprList")
                            .closest(".ui-jqgrid")
                            .block();
                        $(".FrmKyotenFurikaeEdit.cmdAction").text("削除(F9)");
                        $(".FrmKyotenFurikaeEdit.cmdAction").trigger("focus");
                    }
                };

                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    "",
                    "",
                    me.option,
                    arr,
                    complete_fun
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 550);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 270 : 280
                );
            } else {
                gdmz.common.jqgrid.init(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    "",
                    "",
                    me.option
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 550);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 270 : 280
                );
                me.colomn = {
                    SYAIN_CD: "",
                    SYAIN_NM: "",
                    FURIKAE_KIN: "",
                };
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                    "addRowData",
                    0,
                    me.colomn
                );

                $(".FrmKyotenFurikaeEdit.txtCMNNO").trigger("focus");
            }
            $(".FrmKyotenFurikaeEdit.cboKeiriBi").ympicker("disable");
            $(me.grid_id).jqGrid("setGridParam", {
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
                                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                    "saveRow",
                                    me.lastsel
                                );
                                me.lastsel = rowid;
                            }
                            $("#" + rowid + "_FURIKAE_KIN").css(
                                "text-align",
                                "right"
                            );
                            $(me.grid_id).jqGrid("editRow", rowid, {
                                keys: true,
                                focusField: cellIndex,
                            });
                        }
                    } else {
                        if (rowid && rowid != me.lastsel) {
                            $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                "saveRow",
                                me.lastsel
                            );
                            me.lastsel = rowid;
                        }
                        $(me.grid_id).jqGrid("editRow", rowid, {
                            keys: true,
                            focusField: false,
                        });
                        $("#" + rowid + "_FURIKAE_KIN").css(
                            "text-align",
                            "right"
                        );
                    }
                    //键盘事件
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
        };

        me.ajax.send(me.url, me.data, 0);
    };
    // '**********************************************************************
    // '処 理 名：DBに内容を登録、修正、削除
    // '関 数 名：cmdAction_Click
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：パラメータがINS(追加)の場合は入力チェック、存在チェックの後、DBに登録
    // '   　　　            UPD(修正)の場合は名称の入力チェック後、DBを修正
    // '　　　　             DEL(削除)の場合はDBから削除
    // '**********************************************************************
    me.cmdAction_Click = function () {
        $(".FrmKyotenFurikaeEdit.cmdAction").trigger("focus");
        //確認ﾒｯｾｰｼﾞ表示
        if (me.strMenteFlg == "DEL") {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteFurikae;
            me.clsComFnc.FncMsgBox("QY004");
        }
        //新規・修正
        else {
            $("#FrmKyotenFurikaeEdit_sprList").jqGrid("saveRow", me.lastsel);
            // 入力ﾁｪｯｸ
            me.fncCheck();
        }
    };
    // '**********************************************************************
    // '処 理 名：削除処理
    // '関 数 名：fncDeleteFurikae
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：注文書番号と枝番で削除する
    // '**********************************************************************
    me.fncDeleteFurikae = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteFurikae";

        me.data = {
            cboKeiriBi: $(".FrmKyotenFurikaeEdit.cboKeiriBi").val().trimEnd(),
            txtEdaNO: $(".FrmKyotenFurikaeEdit.txtEdaNO").val().trimEnd(),
            txtCMNNO: $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] == "E0004") {
                    me.clsComFnc.FncMsgBox("E0004");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            o_JKSYS_JKSYS.FrmKyotenFurikae.refreshFlg = true;
            $(".FrmKyotenFurikaeEdit.body").dialog("close");
        };

        me.ajax.send(me.url, me.data, 0);
    };

    me.fncCheck = function () {
        //入力ﾁｪｯｸ
        if (!me.fncInputCheck()) {
            return;
        }
        //存在ﾁｪｯｸ
        me.fncExistsCheck();
    };

    // '**********************************************************************
    // '処 理 名：入力チェック
    // '関 数 名：fncInputChk
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：入力チェック
    // '**********************************************************************
    me.fncInputCheck = function () {
        var intRtn = "";
        //社員番号
        intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmKyotenFurikaeEdit.txtSyainCD"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "社員番号", "txtSyainCD");
            return false;
        }

        if (me.strMenteFlg == "INS") {
            //注文書番号
            intRtn = me.clsComFnc.FncTextCheck(
                $(".FrmKyotenFurikaeEdit.txtCMNNO"),
                0,
                me.clsComFnc.INPUTTYPE.CHAR2
            );
            if (intRtn < 0) {
                me.subMsgOutput(intRtn, "注文書番号", "txtCMNNO");
                return false;
            }
        }

        //表示文字
        intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmKyotenFurikaeEdit.txtDispMoji"),
            0,
            me.clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "表示文字", "txtDispMoji");
            return false;
        }

        //金額
        intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin"),
            0,
            me.clsComFnc.INPUTTYPE.NUMBER2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "金額", "txtFurikaeKin");
            return false;
        }

        return true;
    };
    // '**********************************************************************
    // '処 理 名：存在チェック
    // '関 数 名：fncExistsCheck
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：存在チェック
    // '**********************************************************************
    me.fncExistsCheck = function () {
        //社員ﾏｽﾀ存在チェック

        me.url = me.sys_id + "/" + me.id + "/fncExistsCheck";
        me.data = {
            strSyainNO: $(".FrmKyotenFurikaeEdit.txtSyainCD").val().trimEnd(),
            strCMNNO: $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //社員ﾏｽﾀ存在チェック
            if (result["data"]["SyainMstCheck"] == false) {
                $(".FrmKyotenFurikaeEdit.txtSyainCD").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.subMsgOutput(-8, "社員番号", "txtSyainCD");
                return;
            }
            //UCNOチェック
            if (result["data"]["M41E10Check"] == false) {
                if (me.strMenteFlg == "INS") {
                    $(".FrmKyotenFurikaeEdit.txtCMNNO").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                }
                me.subMsgOutput(-8, "注文書番号", "txtCMNNO");
                return;
            }
            if (me.strMenteFlg == "INS") {
                //必須入力チェック
                if (
                    $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd() == "" &&
                    $(".FrmKyotenFurikaeEdit.txtDispMoji").val().trimEnd() == ""
                ) {
                    me.clsComFnc.ObjFocus = $(".FrmKyotenFurikaeEdit.txtCMNNO");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文書番号又は表示文字のどちらか一方は必ず入力して下さい！"
                    );
                    $(".FrmKyotenFurikaeEdit.txtCMNNO").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    $(".FrmKyotenFurikaeEdit.txtDispMoji").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    return;
                }
                //整合性チェック
                if (
                    $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd() != "" &&
                    $(".FrmKyotenFurikaeEdit.txtDispMoji").val().trimEnd() != ""
                ) {
                    me.clsComFnc.ObjFocus = $(".FrmKyotenFurikaeEdit.txtCMNNO");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "注文書番号と表示文字を同時に登録することは出来ません！"
                    );
                    $(".FrmKyotenFurikaeEdit.txtCMNNO").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    $(".FrmKyotenFurikaeEdit.txtDispMoji").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    return;
                }
                //存在チェック
                me.url = me.sys_id + "/" + me.id + "/fncFurikaeExistadd";
                me.data = {
                    GetTougetuNew: me.strNengetu,
                    strMSKb: "",
                    cboKeiriBi: $(".FrmKyotenFurikaeEdit.cboKeiriBi")
                        .val()
                        .trimEnd(),
                    txtEdaNO: $(".FrmKyotenFurikaeEdit.txtEdaNO")
                        .val()
                        .trimEnd(),
                    txtCMNNO: $(".FrmKyotenFurikaeEdit.txtCMNNO")
                        .val()
                        .trimEnd(),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    if (result["row"] > 0) {
                        me.clsComFnc.FncMsgBox("W0004");
                        return;
                    }

                    //整合性ﾁｪｯｸ
                    me.fncConsistencyCheck();
                };
                me.ajax.send(me.url, me.data, 0);
            } else {
                //整合性ﾁｪｯｸ
                me.fncConsistencyCheck();
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    // '**********************************************************************
    // '処 理 名：整合性チェック
    // '関 数 名：fncConsistencyCheck
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：整合性チェック
    // '**********************************************************************
    me.fncConsistencyCheck = function () {
        if (me.strMenteFlg == "UPD") {
            if (
                $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd() == "" &&
                $(".FrmKyotenFurikaeEdit.txtDispMoji").val().trimEnd() == ""
            ) {
                me.clsComFnc.ObjFocus = $(".FrmKyotenFurikaeEdit.txtDispMoji");
                me.clsComFnc.FncMsgBox("W9999", "表示文字は必須入力です！");
                $(".FrmKyotenFurikaeEdit.txtDispMoji").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                return false;
            }
        }

        //ｽﾌﾟﾚｯﾄﾞ入力ﾁｪｯｸ
        var DataIDs = $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getDataIDs");
        var rowcount = DataIDs.length;
        for (var i = 0; i < rowcount; i++) {
            if (
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getCell", i, 1) ==
                    "" &&
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getCell", i, 3) != ""
            ) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                        "setSelection",
                        i,
                        true
                    );
                };
                me.clsComFnc.FncMsgBox("W9999", "社員番号を入力してください！");
                //添加全选和焦点
                var selNextId = "#" + i + "_SYAIN_CD";
                $(selNextId).trigger("focus");
                $(selNextId).select();
                return false;
            }
            if (
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getCell", i, 3) == ""
            ) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                        "setSelection",
                        i,
                        true
                    );
                };
                //添加全选和焦点
                var selNextId = "#" + i + "_FURIKAE_KIN";
                $(selNextId).trigger("focus");
                $(selNextId).select();
                me.clsComFnc.ObjFocus = $(selNextId);
                me.clsComFnc.FncMsgBox("W9999", "金額を入力してください！");
                return false;
            }
            if (
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getCell", i, 1) !==
                ""
            ) {
                if (
                    me.fncSyainChk(
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "getCell",
                            i,
                            1
                        )
                    ) == ""
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "setSelection",
                            i,
                            true
                        );
                    };
                    me.clsComFnc.FncMsgBox("W0008", "社員番号");
                    //添加全选和焦点
                    var selNextId = "#" + i + "_SYAIN_CD";
                    $(selNextId).trigger("focus");
                    $(selNextId).select();
                    //20191218 WY INS E
                    return;
                }
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("setCell", i, 4, "1");
            } else {
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("setCell", i, 4, "");
            }
        }

        //社員番号ﾁｪｯｸ
        for (var i = -1; i <= rowcount - 2; i++) {
            for (var k = i + 1; k <= rowcount - 1; k++) {
                if (i == -1) {
                    if (
                        $(".FrmKyotenFurikaeEdit.txtSyainCD").val().trimEnd() ==
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "getCell",
                            k,
                            1
                        )
                    ) {
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                "setSelection",
                                k,
                                true
                            );
                        };
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "振替元社員番号と重複しています！"
                        );
                        //20191218 WY INS S
                        //添加全选和焦点
                        var selNextId = "#" + k + "_SYAIN_CD";
                        $(selNextId).trigger("focus");
                        $(selNextId).select();
                        //20191218 WY INS E
                        return;
                    }
                } else {
                    if (
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "getCell",
                            i,
                            1
                        ) != "" &&
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "getCell",
                            k,
                            1
                        ) != ""
                    ) {
                        if (
                            $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                "getCell",
                                i,
                                1
                            ) ==
                            $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                "getCell",
                                k,
                                1
                            )
                        ) {
                            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                                    "setSelection",
                                    i,
                                    true
                                );
                            };
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "社員番号が重複しています！"
                            );
                            //20191218 WY INS S
                            //添加全选和焦点
                            var selNextId = "#" + i + "_SYAIN_CD";
                            $(selNextId).trigger("focus");
                            $(selNextId).select();
                            //20191218 WY INS E
                            return;
                        }
                    }
                }
            }
        }
        var lngInputTotal = 0;
        for (var i = 0; i < rowcount; i++) {
            if (
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getCell", i, 3) != ""
            ) {
                lngInputTotal =
                    lngInputTotal +
                    parseInt(
                        $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                            "getCell",
                            i,
                            3
                        )
                    );
            }
        }
        $(".FrmKyotenFurikaeEdit.lblInputTotal").val(
            lngInputTotal.toString().numFormat()
        );
        //金額合計ﾁｪｯｸ
        var txtFurikaeKin = parseInt(
            $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val().replace(/\,/g, "")
        );
        var lblInputTotal = parseInt(
            $(".FrmKyotenFurikaeEdit.lblInputTotal").val().replace(/\,/g, "")
        );
        if (txtFurikaeKin != lblInputTotal * -1) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAction;
            me.clsComFnc.MsgBoxBtnFnc.No = function () {
                return;
            };
            $("#FrmKyotenFurikaeEdit_sprList").jqGrid("setSelection", 0);
            $("#0_FURIKAE_KIN").trigger("focus");
            //20191218 WY INS S
            //添加全选
            $("#0_FURIKAE_KIN").select();
            //20191218 WY INS E
            me.clsComFnc.FncMsgBox(
                "QY999",
                "振替元金額と振替先金額合計が一致しません。登録しますか？"
            );
            return;
        } else {
            me.cmdAction();
        }
    };
    // '**********************************************************************
    // '処 理 名：金額合計チェックdialog Yes押下
    // '関 数 名：cmdAction
    // '引    数：無し
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：金額合計チェックdialog Yes押下
    // '**********************************************************************
    me.cmdAction = function () {
        $("#FrmKyotenFurikaeEdit_sprList").jqGrid("saveRow", me.lastsel);
        me.url = me.sys_id + "/" + me.id + "/fncInsertFurikae";
        me.data = {
            strMenteFlg: me.strMenteFlg,
            cboKeiriBi: $(".FrmKyotenFurikaeEdit.cboKeiriBi").val(),
            txtEdaNO: $(".FrmKyotenFurikaeEdit.txtEdaNO").val().trimEnd(),
            txtCMNNO: $(".FrmKyotenFurikaeEdit.txtCMNNO").val().trimEnd(),
            lblUCNO: $(".FrmKyotenFurikaeEdit.lblUCNO").val().trimEnd(),
            txtDispMoji: $(".FrmKyotenFurikaeEdit.txtDispMoji").val().trimEnd(),
            txtSyainCD: $(".FrmKyotenFurikaeEdit.txtSyainCD").val().trimEnd(),
            txtFurikaeKin: $(".FrmKyotenFurikaeEdit.txtFurikaeKin")
                .val()
                .replace(/\,/g, ""),
            rowData: $("#FrmKyotenFurikaeEdit_sprList").jqGrid("getRowData"),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (me.strMenteFlg == "INS") {
                $(".FrmKyotenFurikaeEdit.txtEdaNO").val(result["data"]);
                me.subClearForm();
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid("clearGridData");
                $("#FrmKyotenFurikaeEdit_sprList").jqGrid(
                    "addRowData",
                    0,
                    me.colomn
                );
                o_JKSYS_JKSYS.FrmKyotenFurikae.refreshFlg = true;
            }
            if (me.strMenteFlg == "UPD") {
                o_JKSYS_JKSYS.FrmKyotenFurikae.refreshFlg = true;
                $(".FrmKyotenFurikaeEdit.body").dialog("close");
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };
    // '**********************************************************************
    // '処 理 名：フォームクリア
    // '関 数 名：subClearForm
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：フォームをクリアする
    // '**********************************************************************
    me.subClearForm = function () {
        $(".FrmKyotenFurikaeEdit.txtSyainCD").val("");
        $(".FrmKyotenFurikaeEdit.lblSyainNM").val("");
        $(".FrmKyotenFurikaeEdit.txtCMNNO").val("");
        $(".FrmKyotenFurikaeEdit.lblUCNO").val("");
        $(".FrmKyotenFurikaeEdit.txtDispMoji").val("");
        $(".FrmKyotenFurikaeEdit.txtFurikaeKin").val("");
        $(".FrmKyotenFurikaeEdit.lblInputTotal").val("0");
    };

    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID) + 1;
        $("#FrmKyotenFurikaeEdit_sprList").jqGrid("setSelection", rowNum);

        var ceil = rowNum + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };
    // '**********************************************************************
    // '処 理 名：ｴﾗｰﾒｯｾｰｼﾞの表示
    // '関 数 名：subMsgOutput
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：ｴﾗｰﾒｯｾｰｼﾞの表示
    // '**********************************************************************

    me.subMsgOutput = function (intErrMsgno, strerrmsg, id) {
        switch (intErrMsgno) {
            //必須ｴﾗｰ
            case -1:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0001", strerrmsg);

                break;
            //入力値ｴﾗｰ
            case -2:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0002", strerrmsg);
                break;
            //桁数ｴﾗｰ
            case -3:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0003", strerrmsg);
                break;
            //範囲ｴﾗｰ
            case -6:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0006", strerrmsg);
                break;
            //存在ｴﾗｰ
            case -7:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0007", strerrmsg);
                break;
            //存在ｴﾗｰ
            case -8:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0008", strerrmsg);
                break;
            //その他ｴﾗｰ
            case -9:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W9999", strerrmsg);
                break;
            // フォルダ存在ｴﾗｰ
            case -15:
                me.clsComFnc.ObjSelect = $(".FrmKyotenFurikaeEdit." + id);
                me.clsComFnc.FncMsgBox("W0015", strerrmsg);
                break;
        }
    };

    //20191219 WY INS S
    me.keyups = function (e) {
        var key = e.charCode || e.keyCode;
        //backspaceキー
        if (key == 8) {
            var inputVal = $.trim($(e.target).val());
            if (inputVal == "-") {
                $(e.target).val("0");

                return false;
            }
        }
    };

    //输入-，则输入-0
    me.add_0 = function (e) {
        var keydownVal = e.char || e.key;
        var inputVal = $.trim($(e.target).val());

        if (inputVal) {
            if (e && e.target) {
                $(e.target).val(
                    inputVal.indexOf(keydownVal) >= 0
                        ? inputVal.replace(keydownVal, "")
                        : keydownVal + inputVal
                );
            }
        } else {
            $(e.target).val("-0");
        }
        return true;
    };

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substring(0, 1) + (keycode - 48).toString();
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
                $(targetVal).val(inputValue.substring(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substring(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substring(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    //提取共同方法
    me.getSyainName = function (e) {
        var foundNM = "";
        var selCellVal = $.trim($(e.target).val());
        if (me.getAllSyainJqGrid) {
            //根据code获取name
            var found = me.getAllSyainJqGrid.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (found.length > 0) {
                foundNM = found[0]["SYAIN_NM"];
            }
        }
        $(e.target).parent().next().text(foundNM);
    };

    me.fncSyainChk = function (syainno) {
        var foundNM = "";
        if (me.getAllSyainJqGrid) {
            //根据code获取name
            var found = me.getAllSyainJqGrid.filter(function (element) {
                return element["SYAIN_NO"] == me.clsComFnc.FncNv(syainno);
            });
            if (found.length > 0) {
                foundNM = found[0]["SYAIN_NM"];
            } else {
                foundNM = "";
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
    var o_JKSYS_FrmKyotenFurikaeEdit = new JKSYS.FrmKyotenFurikaeEdit();

    o_JKSYS_JKSYS.FrmKyotenFurikae.FrmKyotenFurikaeEdit =
        o_JKSYS_FrmKyotenFurikaeEdit;
    o_JKSYS_FrmKyotenFurikaeEdit.FrmKyotenFurikae =
        o_JKSYS_JKSYS.FrmKyotenFurikae;
    o_JKSYS_FrmKyotenFurikaeEdit.load();
});
