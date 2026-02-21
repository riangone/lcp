/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150928                  #2179                   BUG                         LI
 * 20151124         		 BUG对应                  BUG                         Yin
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyokaiFurikae");

R4.FrmSyokaiFurikae = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmSyokaiFurikae";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.getAllBusyo = "";
    me.syainArray = "";
    me.FrmSyokaiFurikaeList = null;
    me.strSaveKeijyoYM = "";
    me.strSaveDenpyoNO = "";
    me.strKeijyoBi = "";
    me.strDenpyoNO = "";
    me.strSaveBusyoM = "";
    me.strSaveBusyoS = "";
    me.flg = 0;

    me.col = {
        DENPY_NO: "",
        MOT_BUSYO_CD: "",
        MOT_SYAIN_NM: "",
        MOT_SYAIN_NO: "",
        SAKI_BUSYO_CD: "",
        SAKI_SYAIN_NO: "",
        SAKI_SYAIN_NM: "",
        KEIJO_GK: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyokaiFurikae.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikae.cmdBack",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikae.cmdDelete",
        type: "button",
        handle: "",
    });
    // me.controls.push(
    // {
    // id : ".FrmSyokaiFurikae.cmdSearch",
    // type : "button",
    // handle : ""
    // });
    me.controls.push({
        id: ".FrmSyokaiFurikae.cmdSearchBs_S",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikae.cmdSearchBs_M",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikae.cboYM",
        //-- 20150928 LI UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150928 LI UPD E.
        handle: "",
    });

    me.colModel = [
        {
            name: "DENPY_NO",
            label: "伝票番号",
            index: "DENPY_NO",
            width: 130,
            align: "left",
            sortable: false,
        },
        {
            name: "MOT_BUSYO_CD",
            label: "部署",
            index: "MOT_BUSYO_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "MOT_SYAIN_NO",
            label: "社員番号",
            index: "MOT_SYAIN_NO",
            width: 70,
            align: "left",
            sortable: false,
        },
        {
            name: "MOT_SYAIN_NM",
            label: "社員名",
            index: "MOT_SYAIN_NM",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "SAKI_BUSYO_CD",
            label: "部署",
            index: "SAKI_BUSYO_CD",
            width: 40,
            align: "left",
            sortable: false,
        },
        {
            name: "SAKI_SYAIN_NO",
            label: "社員番号",
            index: "SAKI_SYAIN_NO",
            width: 70,
            align: "left",
            sortable: false,
        },
        {
            name: "SAKI_SYAIN_NM",
            label: "社員名",
            index: "SAKI_SYAIN_NM",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "KEIJO_GK",
            label: "振替金額",
            index: "KEIJO_GK",
            width: 120,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
    ];

    $("#FrmSyokaiFurikae_sprMeisai").jqGrid({
        datatype: "local",
        height: me.ratio === 1.5 ? 165 : 200,
        width: 830,
        rownumbers: true,
        emptyRecordRow: false,
        colModel: me.colModel,
        ondblClickRow: function (rowId) {
            me.sprMeisai_CellClick(rowId);
        },
    });

    $("#FrmSyokaiFurikae_sprMeisai").jqGrid("setGroupHeaders", {
        useColSpanStyle: true,
        groupHeaders: [
            {
                startColumnName: "MOT_BUSYO_CD",
                numberOfColumns: 3,
                titleText: "振替元",
            },
            {
                startColumnName: "SAKI_BUSYO_CD",
                numberOfColumns: 3,
                titleText: "振替先",
            },
        ],
    });

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
    $(".FrmSyokaiFurikae.txtSakiSyainNO").on("blur", function () {
        var cboVal = $(".FrmSyokaiFurikae.cboSakiSyain").val();

        var txtVal = $(".FrmSyokaiFurikae.txtSakiSyainNO").val();

        if ($(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd() == "") {
            var tmpId =
                ".FrmSyokaiFurikae.cboSakiSyain option[value='" + "" + "']";
            $(tmpId).prop("selected", true);
        } else {
            var flg = true;
            var i = 0;
            $(".FrmSyokaiFurikae.cboSakiSyain").each(function () {
                $(this)
                    .children("option")
                    .each(function () {
                        i++;
                        if (txtVal == $(this).val()) {
                            flg = false;
                        }
                    });
            });
            if (i == 0) {
                return;
            }
            if (flg) {
                $(".FrmSyokaiFurikae.txtSakiSyainNO").val(cboVal);
            } else {
                var tmpId =
                    ".FrmSyokaiFurikae.cboSakiSyain option[value='" +
                    txtVal +
                    "']";
                $(tmpId).prop("selected", true);
            }
        }

        $(".FrmSyokaiFurikae.txtSakiSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmSyokaiFurikae.txtMotoSyainNO").on("blur", function () {
        var cboVal = $(".FrmSyokaiFurikae.cboMotoSyain").val();

        var txtVal = $(".FrmSyokaiFurikae.txtMotoSyainNO").val();

        if ($(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd() == "") {
            var tmpId =
                ".FrmSyokaiFurikae.cboMotoSyain option[value='" + "" + "']";
            $(tmpId).prop("selected", true);
        } else {
            var flg = true;
            var i = 0;
            $(".FrmSyokaiFurikae.cboMotoSyain").each(function () {
                $(this)
                    .children("option")
                    .each(function () {
                        i++;
                        if (txtVal == $(this).val()) {
                            flg = false;
                        }
                    });
            });
            if (i == 0) {
                return;
            }
            if (flg) {
                $(".FrmSyokaiFurikae.txtMotoSyainNO").val(cboVal);
            } else {
                var tmpId =
                    ".FrmSyokaiFurikae.cboMotoSyain option[value='" +
                    txtVal +
                    "']";
                $(tmpId).prop("selected", true);
            }
        }

        $(".FrmSyokaiFurikae.txtMotoSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmSyokaiFurikae.txtMotoKingaku").on("focus", function () {
        // $(".FrmSyokaiFurikae.txtMotoKingaku").css('color', 'black');
        var num = $(".FrmSyokaiFurikae.txtMotoKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");
        $(".FrmSyokaiFurikae.txtMotoKingaku").val(num);
    });

    $(".FrmSyokaiFurikae.txtMotoKingaku").on("blur", function () {
        var num = $(".FrmSyokaiFurikae.txtMotoKingaku").val();
        $(".FrmSyokaiFurikae.txtMotoKingaku").val(
            me.priceFormatter($(".FrmSyokaiFurikae.txtMotoKingaku").val())
        );
        if (num.indexOf("-") == -1) {
            $(".FrmSyokaiFurikae.txtMotoKingaku").css("color", "black");
        } else {
            $(".FrmSyokaiFurikae.txtMotoKingaku").css("color", "red");
        }
    });

    $(".FrmSyokaiFurikae.txtMotoKingaku").keyup(function (event) {
        if (event.keyCode == 39 || event.keyCode == 37) {
            return;
        }

        var num = $(".FrmSyokaiFurikae.txtMotoKingaku").val();
        var num_count = num.length;

        if (num.indexOf("-") == -1 && num_count <= 13) {
            $(".FrmSyokaiFurikae.txtMotoKingaku").val(num);
            return;
        }
        if (num.indexOf("-") == -1 && num_count == 14) {
            $(".FrmSyokaiFurikae.txtMotoKingaku").val(num.substring(0, 13));
            return;
        }
        if (num.indexOf("-") == 0 && num_count <= 14) {
            $(".FrmSyokaiFurikae.txtMotoKingaku").val(num);
            return;
        }
        if (num.indexOf("-") > 0) {
            var num = $(".FrmSyokaiFurikae.txtMotoKingaku")
                .val()
                .toString()
                .replace(/-/, "");
            $(".FrmSyokaiFurikae.txtMotoKingaku").val(num);
            return;
        }
    });

    $(".FrmSyokaiFurikae.txtDenpyoNOFrom").on("blur", function () {
        $(".FrmSyokaiFurikae.txtDenpyoNOFrom").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmSyokaiFurikae.txtSakiSyainNO").on("blur", function () {
        $(".FrmSyokaiFurikae.txtSakiSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmSyokaiFurikae.txtMotoSyainNO").on("blur", function () {
        $(".FrmSyokaiFurikae.txtMotoSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmSyokaiFurikae.txtMotoBusyoCD").on("blur", function () {
        var tmp = $(".FrmSyokaiFurikae.txtMotoBusyoCD").val().trimEnd();
        $(".FrmSyokaiFurikae.txtMotoBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmSyokaiFurikae.lblMotoBusyoNM").val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmSyokaiFurikae.lblMotoBusyoNM").val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
        $(".FrmSyokaiFurikae.cboMotoSyain").empty();
        $(".FrmSyokaiFurikae.txtMotoSyainNO").val("");
        if (tmp != "") {
            me.subComboSet2(tmp, "cboMotoSyain");
        }
    });

    $(".FrmSyokaiFurikae.txtSakiBusyoCD").on("blur", function () {
        var tmp = $(".FrmSyokaiFurikae.txtSakiBusyoCD").val().trimEnd();
        $(".FrmSyokaiFurikae.txtSakiBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmSyokaiFurikae.lblSakiBusyoNM").val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmSyokaiFurikae.lblSakiBusyoNM").val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
        $(".FrmSyokaiFurikae.cboSakiSyain").empty();
        $(".FrmSyokaiFurikae.txtSakiSyainNO").val("");
        if (tmp != "") {
            me.subComboSet2(tmp, "cboSakiSyain");
        }
    });

    $(".FrmSyokaiFurikae.cboSakiSyain").change(function () {
        $(".FrmSyokaiFurikae.txtSakiSyainNO").val(
            $(".FrmSyokaiFurikae.cboSakiSyain").val().trimEnd()
        );
    });

    $(".FrmSyokaiFurikae.cboMotoSyain").change(function () {
        $(".FrmSyokaiFurikae.txtMotoSyainNO").val(
            $(".FrmSyokaiFurikae.cboMotoSyain").val().trimEnd()
        );
    });

    $(".FrmSyokaiFurikae.cboYM").on("blur", function () {
        //-- 20150928 LI UPD S.
        //if (clsComFnc.CheckDate2($(".FrmSyokaiFurikae.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmSyokaiFurikae.cboYM")) == false) {
            //-- 20150928 LI UPD E.
            $(".FrmSyokaiFurikae.cboYM").val(me.cboYM);
            $(".FrmSyokaiFurikae.cboYM").trigger("focus");
            $(".FrmSyokaiFurikae.cboYM").select();
            $(".FrmSyokaiFurikae.cmdAction").button("disable");
        } else {
            $(".FrmSyokaiFurikae.cmdAction").button("enable");
        }
    });
    $(".FrmSyokaiFurikae.cmdDelete").click(function () {
        if ($(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd() == "") {
            clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtMotoSyainNO");
            clsComFnc.FncMsgBox(
                "W9999",
                "削除の対象となる社員を入力してください！"
            );
            return;
        }

        if ($(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd() == "") {
            clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtSakiSyainNO");
            clsComFnc.FncMsgBox(
                "W9999",
                "削除の対象となる社員を入力してください！"
            );
            return;
        }

        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteChuSyokai;
        clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmSyokaiFurikae.cmdBack").click(function () {
        $("#DialogDiv").dialog("close");
    });

    $(".FrmSyokaiFurikae.cmdAction").click(function () {
        // me.flg = me.flg + 1;
        var txtDenpyoNOFrom = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
            .val()
            .trimEnd();
        var cboYMVal = $(".FrmSyokaiFurikae.cboYM")
            .val()
            .trimEnd()
            .replace(/\//, "");

        if (
            me.strSaveKeijyoYM == cboYMVal &&
            me.strSaveDenpyoNO == txtDenpyoNOFrom
        ) {
            me.fncInputChk();
            return;
        }

        // console.log('処理概要：更新ボタン押下時');
        var txtDenpyoNOFrom = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
            .val()
            .trimEnd();
        var cboYMVal = $(".FrmSyokaiFurikae.cboYM")
            .val()
            .trimEnd()
            .replace(/\//, "");
        if (me.FrmSyokaiFurikaeList.PrpMenteFlg == "INS") {
            me.url = me.sys_id + "/" + me.id + "/fncFromChuSyokaiSelect";

            var arr = {
                KEIJOBI: cboYMVal,
                DENPYNO: txtDenpyoNOFrom,
            };

            me.data = {
                request: arr,
            };

            ajax.receive = function (result) {
                // console.log(result);
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }

                if (result["row"] > 0) {
                    clsComFnc.ObjSelect = $(
                        ".FrmSyokaiFurikae.txtDenpyoNOFrom"
                    );
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "既に該当データは登録されています！"
                    );
                    me.subFormClear(false);
                    $(".FrmSyokaiFurikae.txtGoukei").val("0");
                    $("#FrmSyokaiFurikae_sprMeisai").jqGrid("clearGridData");
                    return;
                }

                if (result["row"] <= 0) {
                    me.fncInputChk();
                    return;
                }
            };

            ajax.send(me.url, me.data, 0);
        } else {
            me.fncInputChk();
        }
    });

    shortcut.add("F9", function () {
        $(".FrmSyokaiFurikae.cmdAction").trigger("focus");
        me.fncInputChk();
    });

    shortcut.add("F3", function () {
        $(".FrmSyokaiFurikae.txtDenpyoNOFrom").trigger("focus");
    });

    // $(".FrmSyokaiFurikae.cmdSearch").click(function()
    // {
    // var txtDenpyoNOFrom = $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd();
    // var cboYMVal = $(".FrmSyokaiFurikae.cboYM").val().trimEnd().replace(/\//, "");
    // if (txtDenpyoNOFrom == '')
    // {
    // return;
    // }
    //
    // // if (me.strSaveKeijyoYM == cboYMVal && me.strSaveDenpyoNO == txtDenpyoNOFrom)
    // // {
    // // return;
    // // }
    //
    // me.subFormClear(false);
    // $("#FrmSyokaiFurikae_sprMeisai").jqGrid('clearGridData');
    //
    // //中古紹介料振替ﾃﾞｰﾀを抽出
    // me.fncFromChuSyokaiSelectSearchButton(cboYMVal, txtDenpyoNOFrom, false);
    // });

    $(".FrmSyokaiFurikae.cmdSearchBs_S").click(function () {
        $(".FrmSyokaiFurikae.txtSakiBusyoCD").trigger("focus");
        me.showBusyoDialog("S");
    });

    $(".FrmSyokaiFurikae.cmdSearchBs_M").click(function () {
        $(".FrmSyokaiFurikae.txtMotoBusyoCD").trigger("focus");
        me.showBusyoDialog("M");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.init_control = function () {
        base_init_control();

        me.FrmOptionInput_Load();
    };

    me.showBusyoDialog = function (flg) {
        $("<div></div>")
            .prop("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmSyokaiFurikae"));

        $("<div></div>")
            .prop("id", "BUSYOCD")
            .insertAfter($("#FrmSyokaiFurikae"));
        $("<div></div>")
            .prop("id", "BUSYONM")
            .insertAfter($("#FrmSyokaiFurikae"));
        $("<div></div>")
            .prop("id", "RtnCD")
            .insertAfter($("#FrmSyokaiFurikae"));

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
                me.RtnCD = $("#RtnCD").html();
                if (flg == "M") {
                    if (me.RtnCD == 1) {
                        $(".FrmSyokaiFurikae.txtMotoBusyoCD").val(
                            $("#BUSYOCD").html()
                        );
                        $(".FrmSyokaiFurikae.lblMotoBusyoNM").val(
                            $("#BUSYONM").html()
                        );
                        $(".FrmSyokaiFurikae.cboMotoSyain").trigger("focus");
                    } else {
                        $(".FrmSyokaiFurikae.txtMotoBusyoCD").trigger("focus");
                    }
                } else {
                    if (me.RtnCD == 1) {
                        $(".FrmSyokaiFurikae.txtSakiBusyoCD").val(
                            $("#BUSYOCD").html()
                        );
                        $(".FrmSyokaiFurikae.lblSakiBusyoNM").val(
                            $("#BUSYONM").html()
                        );
                        $(".FrmSyokaiFurikae.cboSakiSyain").trigger("focus");
                    } else {
                        $(".FrmSyokaiFurikae.txtSakiBusyoCD").trigger("focus");
                    }
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        ajax.send(url, me.data, 0);
        ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);

            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    };

    me.fncInputChk = function () {
        //伝票番号ﾁｪｯｸ
        if ($(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd() == "") {
            $(".FrmSyokaiFurikae.txtDenpyoNOFrom").css(
                clsComFnc.GC_COLOR_ERROR
            );
            clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtDenpyoNOFrom");
            clsComFnc.FncMsgBox("W0001", "伝票番号");
            return;
        }

        //社員番号ﾁｪｯｸ
        if ($(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd() == "") {
            $(".FrmSyokaiFurikae.txtMotoSyainNO").css(clsComFnc.GC_COLOR_ERROR);
            clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtMotoSyainNO");
            clsComFnc.FncMsgBox("W0001", "振替元社員番号");
            return;
        }

        me.fncInputChkSyainNO();
    };

    me.fncInputChkSyainNO = function () {
        //社員番号存在ﾁｪｯｸ
        // console.log('社員番号存在ﾁｪｯｸ');
        me.url = me.sys_id + "/" + me.id + "/fncSyainmstExist";
        var arr = {
            SyainNO: $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd(),
            //-- 20150928 LI UPD S.
            //'KJNBI' : $(".FrmSyokaiFurikae.cboYM").val()
            KJNBI: $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, ""),
            //-- 20150928 LI UPD E.
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
                $(".FrmSyokaiFurikae.txtMotoSyainNO").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtMotoSyainNO");
                clsComFnc.FncMsgBox("W0008", "振替元社員番号");
                return;
            }

            if ($(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd() == "") {
                $(".FrmSyokaiFurikae.txtSakiSyainNO").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtSakiSyainNO");
                clsComFnc.FncMsgBox("W0001", "振替先社員番号");
                return;
            }

            me.fncInputChkSyainNOSaki();
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncInputChkSyainNOSaki = function () {
        //社員番号存在ﾁｪｯｸ
        // console.log('社員番号存在ﾁｪｯｸ');
        me.url = me.sys_id + "/" + me.id + "/fncSyainmstExist";
        var arr = {
            SyainNO: $(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd(),
            //-- 20150928 LI UPD S.
            //'KJNBI' : $(".FrmSyokaiFurikae.cboYM").val()
            KJNBI: $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, ""),
            //-- 20150928 LI UPD E.
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
                $(".FrmSyokaiFurikae.txtSakiSyainNO").css(
                    clsComFnc.GC_COLOR_ERROR
                );
                clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtSakiSyainNO");
                clsComFnc.FncMsgBox("W0008", "振替先社員番号");
                return;
            }

            me.fncExistChukoSyokai(1);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncExistChukoSyokai = function (intChk) {
        me.url = me.sys_id + "/" + me.id + "/fncExistChukoSyokai";
        var arr = {
            intChk: intChk,
            DENPYNO: $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd(),
            MOTNO: $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd(),
            SAKINO: $(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd(),
            //-- 20150928 LI UPD S.
            //'KEIJOBI' : $(".FrmSyokaiFurikae.cboYM").val()
            KEIJOBI: $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, ""),
            //-- 20150928 LI UPD E.
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
                var tmp = clsComFnc
                    .FncNv(result["data"][0]["MOT_SYAIN_NO"])
                    .toString();

                if (
                    tmp != $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd()
                ) {
                    clsComFnc.ObjSelect = $(".FrmSyokaiFurikae.txtMotoBusyoCD");
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "同一伝票№内で振替元を複数入力することは出来ません！"
                    );
                    return;
                }
            }

            $(".FrmSyokaiFurikae.lblCreateDt").val("");
            $(".FrmSyokaiFurikae.lblDispNo").val("");

            me.url = me.sys_id + "/" + me.id + "/fncExistChukoSyokai";
            var arr = {
                intChk: 2,
                DENPYNO: $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd(),
                MOTNO: $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd(),
                SAKINO: $(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd(),
                //-- 20150928 LI UPD S.
                //'KEIJOBI' : $(".FrmSyokaiFurikae.cboYM").val()
                KEIJOBI: $(".FrmSyokaiFurikae.cboYM")
                    .val()
                    .trimEnd()
                    .replace(/\//, ""),
                //-- 20150928 LI UPD E.
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
                    $(".FrmSyokaiFurikae.lblCreateDt").val(
                        clsComFnc.FncNv(result["data"][0]["CREATE_DATE"])
                    );
                    $(".FrmSyokaiFurikae.lblDispNo").val(
                        clsComFnc.FncNv(result["data"][0]["DISP_NO"])
                    );
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteInsertChuSyokai;
                    clsComFnc.FncMsgBox("QY003");
                    return;
                }
                me.fncDeleteInsertChuSyokai();
            };
            ajax.send(me.url, me.data, 0);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteInsertChuSyokai = function () {
        // console.log('中古紹介料振替入力に登録開始');

        me.url = me.sys_id + "/" + me.id + "/fncDeleteInsertChuSyokai";

        var arr = {
            KEIJOBI: $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, ""),
            DENPYNO: $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd(),
            MOTNO: $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd(),
            SAKINO: $(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd(),
            KEIJO_GK: $(".FrmSyokaiFurikae.txtMotoKingaku")
                .val()
                .trimEnd()
                .replace(/\,/g, ""),
            DISPNO: $(".FrmSyokaiFurikae.lblDispNo").val().trimEnd(),
            CRE_DT: $(".FrmSyokaiFurikae.lblCreateDt").val().trimEnd(),
        };

        // console.log(arr);

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmSyokaiFurikae.txtMotoKingaku").val("");
            $(".FrmSyokaiFurikae.txtSakiBusyoCD").val("");
            $(".FrmSyokaiFurikae.lblSakiBusyoNM").val("");
            $(".FrmSyokaiFurikae.txtSakiSyainNO").val("");
            $(".FrmSyokaiFurikae.cboSakiSyain").empty();
            $(".FrmSyokaiFurikae.lblDispNo").val("");
            $(".FrmSyokaiFurikae.lblCreateDt").val("");

            $("#FrmSyokaiFurikae_sprMeisai").jqGrid("clearGridData");

            me.fncFromChuSyokaiSelectSearchButton(
                arr["KEIJOBI"],
                arr["DENPYNO"],
                true
            );
        };
        ajax.send(me.url, me.data, 0);
    };

    me.FrmOptionInput_Load = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDataSet";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.getAllBusyo = result["data"];
                me.url = me.sys_id + "/" + me.id + "/fncDataSetSyain";
                ajax.receive = function (result) {
                    // console.log(result);
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["result"] == true) {
                        me.syainArray = result["data"];
                        // console.log(me.syainArray);
                        //コントロールマスタ存在ﾁｪｯｸ
                        me.url =
                            me.sys_id + "/" + me.id + "/FrmOptionInput_Load";

                        ajax.receive = function (result) {
                            // console.log(result);
                            result = eval("(" + result + ")");

                            var myDate = new Date();
                            var tmpMonth = (myDate.getMonth() + 1).toString();
                            if (tmpMonth.length < 2) {
                                tmpMonth = "0" + tmpMonth.toString();
                            }
                            // var tmpNowDate =
                            //     myDate.getFullYear().toString() +
                            //     "/" +
                            //     tmpMonth.toString();
                            if (result["result"] == false) {
                                $("#DialogDiv").dialog("close");
                                clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }
                            if (result["row"] == 0) {
                                $("#DialogDiv").dialog("close");
                                clsComFnc.FncMsgBox(
                                    "E9999",
                                    "コントロールマスタが存在しません！"
                                );
                                // $(".FrmSyokaiFurikae.cboYM").val(tmpNowDate);
                                return;
                            }
                            var strTougetu = clsComFnc
                                .FncNv(result["data"][0]["TOUGETU"])
                                .toString();
                            strTougetu = strTougetu.split("/");
                            //-- 20150928 LI UPD S.
                            //$(".FrmSyokaiFurikae.cboYM").val(strTougetu[0] + '/' + strTougetu[1]);
                            $(".FrmSyokaiFurikae.cboYM").val(
                                strTougetu[0] + strTougetu[1]
                            );
                            //-- 20150928 LI UPD E.
                            me.cboYM = $(".FrmSyokaiFurikae.cboYM").val();

                            $(".FrmSyokaiFurikae.cmdDelete").button("disable");

                            if (me.FrmSyokaiFurikaeList.PrpMenteFlg == "UPD") {
                                //-- 20150928 LI UPD S.
                                //$(".FrmSyokaiFurikae.cboYM").val(me.FrmSyokaiFurikaeList.prpKeijyoBi);
                                $(".FrmSyokaiFurikae.cboYM").val(
                                    me.FrmSyokaiFurikaeList.prpKeijyoBi
                                        .trimEnd()
                                        .replace(/\//, "")
                                );
                                //-- 20150928 LI UPD E.
                                $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val(
                                    me.FrmSyokaiFurikaeList.prpDenpy_NO
                                );
                                me.fncFromChuSyokaiSelect();
                            }
                        };
                        ajax.send(me.url, me.data, 0);
                    }
                };
                ajax.send(me.url, "", 0);
            }
        };

        ajax.send(me.url, "", 0);
    };

    me.fncFromChuSyokaiSelect = function () {
        //データグリッドの再表示
        $("#FrmSyokaiFurikae_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncFromChuSyokaiSelect";

        var arr = {
            KEIJOBI: me.FrmSyokaiFurikaeList.prpKeijyoBi,
            DENPYNO: me.FrmSyokaiFurikaeList.prpDenpy_NO,
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            me.sprData = result["data"];
            if (result["result"] == false) {
                $("#DialogDiv").dialog("close");
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmSyokaiFurikae.txtGoukei").val("0");
            if (result["row"] > 0) {
                var lngGoukei = 0;
                for (key in result["data"]) {
                    me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                    me.col["MOT_BUSYO_CD"] =
                        result["data"][key]["MOT_BUSYO_CD"];
                    me.col["MOT_SYAIN_NM"] =
                        result["data"][key]["MOT_SYAIN_NM"];
                    me.col["MOT_SYAIN_NO"] =
                        result["data"][key]["MOT_SYAIN_NO"];
                    me.col["SAKI_BUSYO_CD"] =
                        result["data"][key]["SAKI_BUSYO_CD"];
                    me.col["SAKI_SYAIN_NO"] =
                        result["data"][key]["SAKI_SYAIN_NO"];
                    me.col["SAKI_SYAIN_NM"] =
                        result["data"][key]["SAKI_SYAIN_NM"];
                    me.col["KEIJO_GK"] = result["data"][key]["KEIJO_GK"];

                    $("#FrmSyokaiFurikae_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );
                    lngGoukei =
                        lngGoukei + parseInt(result["data"][key]["KEIJO_GK"]);
                }
                //ｽﾌﾟﾚｯﾄﾞの合計を表示
                $(".FrmSyokaiFurikae.txtGoukei").val(
                    me.priceFormatter(lngGoukei.toString())
                );
                //$("#FrmSyokaiFurikae_sprMeisai").jqGrid('setSelection', 0, true);
            }
            //---20151116 Yin INS S
            $(".FrmSyokaiFurikae.cboYMFromdiv").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            //---20151116 Yin INS E
            $(".FrmSyokaiFurikae.cboYM").prop("disabled", "disabled");
            $(".FrmSyokaiFurikae.txtDenpyoNOFrom").prop("disabled", "disabled");
            $(".FrmSyokaiFurikae.cmdDelete").button("enable");
            // $(".FrmSyokaiFurikae.cmdSearch").button('disable');

            //振替元部署にﾌｫｰｶｽ移動
            $(".FrmSyokaiFurikae.txtMotoBusyoCD").trigger("focus");

            // 保存用変数クリア
            me.strSaveBusyoM = $(".FrmSyokaiFurikae.txtMotoBusyoCD")
                .val()
                .trimEnd();
            me.strSaveBusyoS = $(".FrmSyokaiFurikae.txtSakiBusyoCD")
                .val()
                .trimEnd();
            me.strSaveKeijyoYM = $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, "");
            me.strSaveDenpyoNO = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
                .val()
                .trimEnd();
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncFromChuSyokaiSelectSearchButton = function (val1, val2, methodFlg) {
        //データグリッドの再表示
        me.url = me.sys_id + "/" + me.id + "/fncFromChuSyokaiSelect";

        var arr = {
            KEIJOBI: val1,
            DENPYNO: val2,
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            me.sprData = result["data"];
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (!methodFlg) {
                if (me.FrmSyokaiFurikaeList.PrpMenteFlg == "INS") {
                    if (result["row"] > 0) {
                        // var tmpA = document.activeElement.className;
                        // if (tmpA.indexOf("cmdBack") == -1)
                        // {
                        clsComFnc.ObjSelect = $(
                            ".FrmSyokaiFurikae.txtDenpyoNOFrom"
                        );
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "既に該当データは登録されています！"
                        );
                    }
                    return;
                }
                //
                // }

                // 新規ﾓｰﾄﾞの場合は、同一伝票№が存在している場合は、エラー

                $(".FrmSyokaiFurikae.txtGoukei").val("0");
                if (result["row"] > 0) {
                    var lngGoukei = 0;
                    for (key in result["data"]) {
                        me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                        me.col["MOT_BUSYO_CD"] =
                            result["data"][key]["MOT_BUSYO_CD"];
                        me.col["MOT_SYAIN_NM"] =
                            result["data"][key]["MOT_SYAIN_NM"];
                        me.col["MOT_SYAIN_NO"] =
                            result["data"][key]["MOT_SYAIN_NO"];
                        me.col["SAKI_BUSYO_CD"] =
                            result["data"][key]["SAKI_BUSYO_CD"];
                        me.col["SAKI_SYAIN_NO"] =
                            result["data"][key]["SAKI_SYAIN_NO"];
                        me.col["SAKI_SYAIN_NM"] =
                            result["data"][key]["SAKI_SYAIN_NM"];
                        me.col["KEIJO_GK"] = result["data"][key]["KEIJO_GK"];

                        $("#FrmSyokaiFurikae_sprMeisai").jqGrid(
                            "addRowData",
                            parseInt(key),
                            me.col
                        );
                        lngGoukei =
                            lngGoukei +
                            parseInt(result["data"][key]["KEIJO_GK"]);
                    }
                    //ｽﾌﾟﾚｯﾄﾞの合計を表示
                    $(".FrmSyokaiFurikae.txtGoukei").val(
                        me.priceFormatter(lngGoukei.toString())
                    );
                }

                // 保存用変数クリア
                me.strSaveBusyoM = $(".FrmSyokaiFurikae.txtMotoBusyoCD")
                    .val()
                    .trimEnd();
                me.strSaveBusyoS = $(".FrmSyokaiFurikae.txtSakiBusyoCD")
                    .val()
                    .trimEnd();
                me.strSaveKeijyoYM = $(".FrmSyokaiFurikae.cboYM")
                    .val()
                    .trimEnd()
                    .replace(/\//, "");
                me.strSaveDenpyoNO = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
                    .val()
                    .trimEnd();
            } else {
                $(".FrmSyokaiFurikae.txtGoukei").val("0");
                if (result["row"] > 0) {
                    var lngGoukei = 0;
                    for (key in result["data"]) {
                        me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                        me.col["MOT_BUSYO_CD"] =
                            result["data"][key]["MOT_BUSYO_CD"];
                        me.col["MOT_SYAIN_NM"] =
                            result["data"][key]["MOT_SYAIN_NM"];
                        me.col["MOT_SYAIN_NO"] =
                            result["data"][key]["MOT_SYAIN_NO"];
                        me.col["SAKI_BUSYO_CD"] =
                            result["data"][key]["SAKI_BUSYO_CD"];
                        me.col["SAKI_SYAIN_NO"] =
                            result["data"][key]["SAKI_SYAIN_NO"];
                        me.col["SAKI_SYAIN_NM"] =
                            result["data"][key]["SAKI_SYAIN_NM"];
                        me.col["KEIJO_GK"] = result["data"][key]["KEIJO_GK"];

                        $("#FrmSyokaiFurikae_sprMeisai").jqGrid(
                            "addRowData",
                            parseInt(key),
                            me.col
                        );
                        lngGoukei =
                            lngGoukei +
                            parseInt(result["data"][key]["KEIJO_GK"]);
                    }
                    //ｽﾌﾟﾚｯﾄﾞの合計を表示
                    $(".FrmSyokaiFurikae.txtGoukei").val(
                        me.priceFormatter(lngGoukei.toString())
                    );
                }
                // 保存用変数クリア
                me.strSaveBusyoM = $(".FrmSyokaiFurikae.txtMotoBusyoCD")
                    .val()
                    .trimEnd();
                me.strSaveBusyoS = $(".FrmSyokaiFurikae.txtSakiBusyoCD")
                    .val()
                    .trimEnd();
                me.strSaveKeijyoYM = $(".FrmSyokaiFurikae.cboYM")
                    .val()
                    .trimEnd()
                    .replace(/\//, "");
                me.strSaveDenpyoNO = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
                    .val()
                    .trimEnd();

                $(".FrmSyokaiFurikae.cmdDelete").button("enable");
                $(".FrmSyokaiFurikae.txtMotoKingaku").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncFromChuSyokaiSelectDelete = function (val1, val2) {
        //データグリッドの再表示
        $("#FrmSyokaiFurikae_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncFromChuSyokaiSelect";

        var arr = {
            KEIJOBI: val1,
            DENPYNO: val2,
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log('893::' + result);
            result = eval("(" + result + ")");
            me.sprData = result["data"];
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] == 0) {
                $(".FrmSyokaiFurikae.cmdDelete").button("disable");
                me.strSaveKeijyoYM = $(".FrmSyokaiFurikae.cboYM")
                    .val()
                    .trimEnd()
                    .replace(/\//, "");
                me.strSaveDenpyoNO = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
                    .val()
                    .trimEnd();
                return;
            }

            if (result["row"] > 0) {
                var lngGoukei = 0;

                for (key in result["data"]) {
                    me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                    me.col["MOT_BUSYO_CD"] =
                        result["data"][key]["MOT_BUSYO_CD"];
                    me.col["MOT_SYAIN_NM"] =
                        result["data"][key]["MOT_SYAIN_NM"];
                    me.col["MOT_SYAIN_NO"] =
                        result["data"][key]["MOT_SYAIN_NO"];
                    me.col["SAKI_BUSYO_CD"] =
                        result["data"][key]["SAKI_BUSYO_CD"];
                    me.col["SAKI_SYAIN_NO"] =
                        result["data"][key]["SAKI_SYAIN_NO"];
                    me.col["SAKI_SYAIN_NM"] =
                        result["data"][key]["SAKI_SYAIN_NM"];
                    me.col["KEIJO_GK"] = result["data"][key]["KEIJO_GK"];

                    $("#FrmSyokaiFurikae_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );
                    lngGoukei =
                        lngGoukei + parseInt(result["data"][key]["KEIJO_GK"]);
                }

                //ｽﾌﾟﾚｯﾄﾞの合計を表示
                $(".FrmSyokaiFurikae.txtGoukei").val(
                    me.priceFormatter(lngGoukei.toString())
                );
                // $("#FrmSyokaiFurikae_sprMeisai").jqGrid('setSelection', 0, true);
            }

            // 保存用変数クリア
            me.strSaveKeijyoYM = $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, "");
            me.strSaveDenpyoNO = $(".FrmSyokaiFurikae.txtDenpyoNOFrom")
                .val()
                .trimEnd();
            me.strSaveBusyoM = "";
            me.strSaveBusyoS = "";

            $(".FrmSyokaiFurikae.cmdDelete").button("disable");

            $(".FrmSyokaiFurikae.txtMotoBusyoCD").trigger("focus");
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteChuSyokai = function () {
        // console.log('削除処理');
        me.url = me.sys_id + "/" + me.id + "/fncDeleteChuSyokai";

        var arr = {
            KEIJOBI: $(".FrmSyokaiFurikae.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, ""),
            DENPYNO: $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val().trimEnd(),
            MOTNO: $(".FrmSyokaiFurikae.txtMotoSyainNO").val().trimEnd(),
            SAKINO: $(".FrmSyokaiFurikae.txtSakiSyainNO").val().trimEnd(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["number_of_rows"] == 0) {
                clsComFnc.ObjFocus = $(".FrmSyokaiFurikae.txtSakiBusyoCD");
                clsComFnc.FncMsgBox(
                    "W9999",
                    "削除対象のデータが存在しません！"
                );
                return;
            }

            clsComFnc.FncMsgBox("I0004");

            me.subFormClear(false);

            $(".FrmSyokaiFurikae.txtGoukei").val("0");

            me.fncFromChuSyokaiSelectDelete(arr["KEIJOBI"], arr["DENPYNO"]);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.sprMeisai_CellClick = function (rowId) {
        $(".FrmSyokaiFurikae.cboYM").val(
            me.sprData[rowId]["KEIJO_DT"].substr(0, 7).replace("/", "")
        );
        $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val(
            me.sprData[rowId]["DENPY_NO"]
        );
        $(".FrmSyokaiFurikae.txtMotoBusyoCD").val(
            me.sprData[rowId]["MOT_BUSYO_CD"]
        );
        $(".FrmSyokaiFurikae.lblMotoBusyoNM").val(
            me.sprData[rowId]["MOT_BUSYO_NM"]
        );
        $(".FrmSyokaiFurikae.txtMotoSyainNO").val(
            me.sprData[rowId]["MOT_SYAIN_NO"]
        );

        $(".FrmSyokaiFurikae.txtSakiBusyoCD").val(
            me.sprData[rowId]["SAKI_BUSYO_CD"]
        );
        $(".FrmSyokaiFurikae.lblSakiBusyoNM").val(
            me.sprData[rowId]["SAKI_BUSYO_NM"]
        );
        $(".FrmSyokaiFurikae.txtSakiSyainNO").val(
            me.sprData[rowId]["SAKI_SYAIN_NO"]
        );
        $(".FrmSyokaiFurikae.lblCreateDt").val(
            me.sprData[rowId]["CREATE_DATE"]
        );
        $(".FrmSyokaiFurikae.lblDispNo").val(me.sprData[rowId]["DISP_NO"]);

        var num = me.sprData[rowId]["KEIJO_GK"];

        if (num.indexOf("-") == -1) {
            $(".FrmSyokaiFurikae.txtMotoKingaku").css("color", "black");
        } else {
            $(".FrmSyokaiFurikae.txtMotoKingaku").css("color", "red");
        }

        $(".FrmSyokaiFurikae.txtMotoKingaku").val(
            me.priceFormatter(me.sprData[rowId]["KEIJO_GK"])
        );

        if (me.sprData[rowId]["MOT_BUSYO_CD"] != "") {
            me.subComboSet2(
                me.sprData[rowId]["MOT_BUSYO_CD"].trimEnd(),
                "cboMotoSyain"
            );
            var tmpId =
                ".FrmSyokaiFurikae.cboMotoSyain option[value='" +
                me.sprData[rowId]["MOT_SYAIN_NO"] +
                "']";
            $(tmpId).prop("selected", true);
        }

        if (me.sprData[rowId]["SAKI_BUSYO_CD"] != "") {
            me.subComboSet2(
                me.sprData[rowId]["SAKI_BUSYO_CD"].trimEnd(),
                "cboSakiSyain"
            );
            var tmpId =
                ".FrmSyokaiFurikae.cboSakiSyain option[value='" +
                me.sprData[rowId]["SAKI_SYAIN_NO"] +
                "']";
            $(tmpId).prop("selected", true);
        }
    };

    me.subComboSet2 = function (busyoCD, id) {
        $(".FrmSyokaiFurikae." + id).empty();

        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmSyokaiFurikae." + id);
        var tmp = busyoCD;
        var tmpYM = $(".FrmSyokaiFurikae.cboYM")
            .val()
            .trimEnd()
            .replace(/\//, "");
        var tmpY = tmpYM.substr(0, 4);
        var tmpM = tmpYM.substr(4, 2);
        var tmpD = me.DayNumOfMonth(tmpY, tmpM);
        var tmpYMD = tmpY + tmpM + tmpD;
        for (key in me.syainArray) {
            if (me.syainArray[key]["BUSYO_CD"] == tmp) {
                if (
                    me.syainArray[key]["START_DATE"] <= tmpYMD &&
                    me.syainArray[key]["END_DATE"] == null &&
                    me.syainArray[key]["TAISYOKU_DATE"] == null
                ) {
                    $("<option></option>")
                        .val(me.syainArray[key]["SYAINNO"])
                        .text(me.syainArray[key]["SYAIN_NM"])
                        .appendTo(".FrmSyokaiFurikae." + id);
                }
            }
        }
    };

    me.subFormClear = function (blnKeyClear) {
        if (blnKeyClear) {
            $(".FrmSyokaiFurikae.txtDenpyoNOFrom").val("");
            $(".FrmSyokaiFurikae.txtGoukei").val("0");
        }

        $(".FrmSyokaiFurikae.txtMotoBusyoCD").val("");
        $(".FrmSyokaiFurikae.lblMotoBusyoNM").val("");
        $(".FrmSyokaiFurikae.txtMotoKingaku").val("");
        $(".FrmSyokaiFurikae.txtSakiBusyoCD").val("");
        $(".FrmSyokaiFurikae.lblSakiBusyoNM").val("");
        $(".FrmSyokaiFurikae.txtSakiSyainNO").val("");
        $(".FrmSyokaiFurikae.txtMotoSyainNO").val("");
        $(".FrmSyokaiFurikae.cboMotoSyain").empty();
        $(".FrmSyokaiFurikae.cboSakiSyain").empty();

        $(".FrmSyokaiFurikae.lblCreateDt").val("");
        $(".FrmSyokaiFurikae.lblDispNo").val("");
    };

    me.priceFormatter = function (num) {
        if (num.trimEnd() == "") {
            return num;
        }
        var num_moto = num;
        var num = num_moto.replace(/-/, "");
        sign = num == (num = Math.abs(num));
        num = Math.floor(num * 10 + 0.50000000001);
        num = Math.floor(num / 10).toString();
        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++) {
            num =
                num.substring(0, num.length - (4 * i + 3)) +
                "," +
                num.substring(num.length - (4 * i + 3));
        }

        var val = num == 0 ? "0" : num;

        if (num_moto.match("-") && num != 0) {
            val = "-" + val;
        } else {
            val = val;
        }
        return val;
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
    var o_R4_FrmSyokaiFurikae = new R4.FrmSyokaiFurikae();
    o_R4_FrmSyokaiFurikae.load();

    o_R4K_R4K.FrmSyokaiFurikaeList.FrmSyokaiFurikae = o_R4_FrmSyokaiFurikae;
    o_R4_FrmSyokaiFurikae.FrmSyokaiFurikaeList = o_R4K_R4K.FrmSyokaiFurikaeList;
});
