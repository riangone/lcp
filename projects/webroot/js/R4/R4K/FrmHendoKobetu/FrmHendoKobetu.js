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
 * 20150922                  #2164                   BUG                         LI
 * 20150917                  #2153                   BUG                         yinhuaiyu
 * 20150922                  #2162                   BUG                         Yuanjh
 * 20151014					#2152					 BUG							yinhuaiyu
 * 20201117                 bug                      年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmHendoKobetu");

R4.FrmHendoKobetu = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    me.R4K = new R4K.R4K();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmHendoKobetu";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.FrmBusyoSearch = null;
    me.FrmHendoKobetuCopy = null;
    me.sprData = "";
    me.RtnCD = "";
    me.strSaveBusyoM = "";
    me.blnAther = false;
    me.strBusyoCD = "";
    me.fncInputChkBln = "";
    me.getAllBusyo = "";
    me.syainArray = "";
    me.col = {
        BusyoCD: "",
        BusyoNM: "",
        SyainNO: "",
        SyainNM: "",
        Kingaku: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHendoKobetu.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cmdSearchBs",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cmdTeisyuTrk",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cmdSearch1",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetu.cboYM",
        //-- 20150922 Yuanjh UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150922 Yuanjh UPD E.
        handle: "",
    });

    me.colModel = [
        {
            name: "BusyoCD",
            label: "部署コード",
            index: "BusyoCD",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "BusyoNM",
            label: "部署名",
            index: "BusyoNM",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "SyainNO",
            label: "社員番号",
            index: "SyainNO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SyainNM",
            label: "社員名",
            index: "SyainNM",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "Kingaku",
            label: "金額",
            index: "Kingaku",
            width: 160,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
    ];

    $("#FrmHendoKobetu_sprMeisai").jqGrid({
        datatype: "local",
        //-- 20150922 li UPD S.
        //height : 270,
        height: me.ratio === 1.5 ? 170 : 210,
        emptyRecordRow: false,
        //-- 20150922 li UPD E.
        colModel: me.colModel,
        ondblClickRow: function (rowId) {
            me.sprMeisai_CellClick(rowId);
        },
    });

    //ShifキーとTabキーのバインド
    me.R4K.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.R4K.TabKeyDown(me.id);

    //Enterキーのバインド
    me.R4K.EnterKeyDown(me.id);

    $(".FrmHendoKobetu.cmdTeisyuTrk").hide();

    var base_init_control = me.init_control;

    me.DayNumOfMonth = function (Year, Month) {
        var d = new Date(Year, Month, 0);
        return d.getDate();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmHendoKobetu.txtBusyoCD").on("blur", function () {
        $(".FrmHendoKobetu.cboSyainNO").empty();
        var tmp = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();
        var tmpYM = $(".FrmHendoKobetu.cboYM")
            .val()
            .trimEnd()
            .replace(/\//, "");
        var tmpY = tmpYM.substr(0, 4);
        var tmpM = tmpYM.substr(4, 2);
        var tmpD = me.DayNumOfMonth(tmpY, tmpM);
        var tmpYMD = tmpY + tmpM + tmpD;
        $(".FrmHendoKobetu.txtBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmHendoKobetu.lblBusyoNM").val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmHendoKobetu.lblBusyoNM").val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmHendoKobetu.cboSyainNO");
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
                        .appendTo(".FrmHendoKobetu.cboSyainNO");
                }
            }
        }
        $(".FrmHendoKobetu.txtSyainNO").val(
            $(".FrmHendoKobetu.cboSyainNO").val()
        );
    });

    $(".FrmHendoKobetu.cmdAction").click(function () {
        me.fncInputChk();
    });

    shortcut.add("F9", function () {
        me.fncInputChk();
    });

    $(".FrmHendoKobetu.cmdDelete").click(function () {
        var txtSyainNO = $(".FrmHendoKobetu.txtSyainNO").val().trimEnd();
        if (txtSyainNO == "") {
            $(".FrmHendoKobetu.txtSyainNO").css(clsComFnc.GC_COLOR_ERROR);
            clsComFnc.ObjSelect = $(".FrmHendoKobetu.txtSyainNO");
            clsComFnc.FncMsgBox("W9999", "社員番号が指定されていません");
            return;
        }
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteHSTAFFKomoku;
        clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmHendoKobetu.txtSyainNO").on("blur", function () {
        var cboVal = $(".FrmHendoKobetu.cboSyainNO").val();

        var txtVal = $(".FrmHendoKobetu.txtSyainNO").val();

        if ($(".FrmHendoKobetu.txtSyainNO").val().trimEnd() == "") {
            var tmpId = ".FrmHendoKobetu.cboSyainNO option[value='" + "" + "']";
            $(tmpId).prop("selected", true);
        } else {
            var flg = true;
            var i = 0;
            $(".FrmHendoKobetu.cboSyainNO").each(function () {
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
                $(".FrmHendoKobetu.txtSyainNO").val(cboVal);
            } else {
                var tmpId =
                    ".FrmHendoKobetu.cboSyainNO option[value='" + txtVal + "']";
                $(tmpId).prop("selected", true);
            }
        }

        $(".FrmHendoKobetu.txtSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmHendoKobetu.cboYM").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmHendoKobetu.cboYM")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (clsComFnc.CheckDate3($(".FrmHendoKobetu.cboYM")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmHendoKobetu.cboYM").val(me.cboYM);
                $(".FrmHendoKobetu.cboYM").trigger("focus");
                $(".FrmHendoKobetu.cboYM").select();
                $(".FrmHendoKobetu.cmdCopy").button("disable");
                $(".FrmHendoKobetu.cmdAction").button("disable");
                $(".FrmHendoKobetu.cmdDelete").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmHendoKobetu.cmdCopy").button("enable");
            $(".FrmHendoKobetu.cmdAction").button("enable");
            $(".FrmHendoKobetu.cmdDelete").button("enable");
        }
    });

    $(".FrmHendoKobetu.cmdSearch1").click(function () {
        var strNameID = clsComFnc.FncNv($(".FrmHendoKobetu.txtBusyoCD").val());
        if ($(".FrmHendoKobetu.chkSyainNo").prop("checked") == true) {
            //判断是否已经打勾
            $(".FrmHendoKobetu.cboSyainNO").empty();
            me.url = me.sys_id + "/" + me.id + "/subComboSet";
            var KJNBIVal = $(".FrmHendoKobetu.cboYM").val();

            var arr = {
                KJNBI: KJNBIVal,
                strNameID: strNameID,
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
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".FrmHendoKobetu.cboSyainNO");
                if (result["row"] > 0) {
                    for (key in result["data"]) {
                        $("<option></option>")
                            .val(result["data"][key]["SYAIN_NO"])
                            .text(result["data"][key]["SYAIN_NM"])
                            .appendTo(".FrmHendoKobetu.cboSyainNO");
                    }
                    $(".FrmHendoKobetu.txtSyainNO").val(
                        $(".FrmHendoKobetu.cboSyainNO").val()
                    );
                }
                $(".FrmHendoKobetu.txtGoukei").val("0");
                $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
                me.fncFromHSTAFFSelect(false);
            };
            ajax.send(me.url, me.data, 0);
        } else {
            if (strNameID == "") {
                $(".FrmHendoKobetu.txtGoukei").val("0");
                $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
                me.fncFromHSTAFFSelect(false);
            } else {
                $(".FrmHendoKobetu.cboSyainNO").empty();
                me.url = me.sys_id + "/" + me.id + "/subComboSet";
                var KJNBIVal = $(".FrmHendoKobetu.cboYM").val();

                var arr = {
                    KJNBI: KJNBIVal,
                    strNameID: strNameID,
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
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".FrmHendoKobetu.cboSyainNO");
                    if (result["row"] > 0) {
                        for (key in result["data"]) {
                            $("<option></option>")
                                .val(result["data"][key]["SYAIN_NO"])
                                .text(result["data"][key]["SYAIN_NM"])
                                .appendTo(".FrmHendoKobetu.cboSyainNO");
                        }
                        $(".FrmHendoKobetu.txtSyainNO").val(
                            $(".FrmHendoKobetu.cboSyainNO").val()
                        );
                    }
                    $(".FrmHendoKobetu.txtGoukei").val("0");
                    $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
                    me.fncFromHSTAFFSelect(false);
                };
                ajax.send(me.url, me.data, 0);
            }
        }
    });

    $(".FrmHendoKobetu.txtKingaku").keyup(function (event) {
        if (event.keyCode == 39 || event.keyCode == 37) {
            return;
        }
        if (event.keyCode == 13) {
            $(".FrmHendoKobetu.cmdAction").trigger("focus");
            me.fncInputChk();
        } else {
            var num = $(".FrmHendoKobetu.txtKingaku").val();
            var num_count = num.length;

            if (num.indexOf("-") == -1 && num_count <= 13) {
                $(".FrmHendoKobetu.txtKingaku").val(num);
                return;
            }
            if (num.indexOf("-") == -1 && num_count == 14) {
                $(".FrmHendoKobetu.txtKingaku").val(num.substring(0, 13));
                return;
            }
            if (num.indexOf("-") == 0 && num_count <= 14) {
                $(".FrmHendoKobetu.txtKingaku").val(num);
                return;
            }
            if (num.indexOf("-") > 0) {
                var num = $(".FrmHendoKobetu.txtKingaku")
                    .val()
                    .toString()
                    .replace(/-/, "");
                $(".FrmHendoKobetu.txtKingaku").val(num);
                return;
            }
        }
    });

    $(".FrmHendoKobetu.txtKingaku").on("blur", function () {
        var num = $(".FrmHendoKobetu.txtKingaku").val();
        $(".FrmHendoKobetu.txtKingaku").val(
            me.priceFormatter($(".FrmHendoKobetu.txtKingaku").val())
        );
        if (num.indexOf("-") == -1) {
            $(".FrmHendoKobetu.txtKingaku").css("color", "black");
        } else {
            $(".FrmHendoKobetu.txtKingaku").css("color", "red");
        }
    });

    $(".FrmHendoKobetu.txtKingaku").on("focus", function () {
        $(".FrmHendoKobetu.txtKingaku").css("color", "black");
        var num = $(".FrmHendoKobetu.txtKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");
        $(".FrmHendoKobetu.txtKingaku").val(num);
    });

    $(".FrmHendoKobetu.cboItemNO").change(function () {
        $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
        $(".FrmHendoKobetu.txtItemNO").val(
            $(".FrmHendoKobetu.cboItemNO").val()
        );
        var tmp = $(".FrmHendoKobetu.txtItemNO").val();

        if (tmp.substr(0, 3).trimEnd() == "5") {
            $(".FrmHendoKobetu.cmdTeisyuTrk").show();
            $(".FrmHendoKobetu.cmdTeisyuTrk").button("enable");
        } else {
            $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
        }
    });

    $(".FrmHendoKobetu.cboSyainNO").change(function () {
        $(".FrmHendoKobetu.txtSyainNO").val(
            $(".FrmHendoKobetu.cboSyainNO").val()
        );
    });

    $(".FrmHendoKobetu.chkSyainNo").click(function () {
        if ($(".FrmHendoKobetu.chkSyainNo").prop("checked") == true) {
            //判断是否已经打勾
            //20150917 yinhuaiyu mod S
            var tmpYM = $(".FrmHendoKobetu.cboYM")
                .val()
                .trimEnd()
                .replace(/\//, "");
            var tmpY = tmpYM.substr(0, 4);
            var tmpM = tmpYM.substr(4, 2);
            var tmpD = me.DayNumOfMonth(tmpY, tmpM);
            var tmpYMD = tmpY + tmpM + tmpD;
            $(".FrmHendoKobetu.cmdSearchBs").button("disable");
            $(".FrmHendoKobetu.txtBusyoCD").val("");
            $(".FrmHendoKobetu.lblBusyoNM").val("");
            $(".FrmHendoKobetu.txtKingaku").val("");
            $(".FrmHendoKobetu.cboSyainNO").empty();
            $(".FrmHendoKobetu.txtBusyoCD").prop("disabled", "disabled");
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".FrmHendoKobetu.cboSyainNO");
            for (key in me.syainArray) {
                if (
                    me.syainArray[key]["START_DATE"] <= tmpYMD &&
                    me.syainArray[key]["TAISYOKU_DATE"] == null
                ) {
                    $("<option></option>")
                        .val(me.syainArray[key]["SYAINNO"])
                        .text(me.syainArray[key]["SYAIN_NM"])
                        .appendTo(".FrmHendoKobetu.cboSyainNO");
                }
            }
            $(".FrmHendoKobetu.txtSyainNO").trigger("focus");
            //20150917 yinhuaiyu mod E
        } else {
            $(".FrmHendoKobetu.cmdSearchBs").button("enable");
            $(".FrmHendoKobetu.txtBusyoCD").removeAttr("disabled");
            $(".FrmHendoKobetu.txtBusyoCD").trigger("focus");
            $(".FrmHendoKobetu.txtKingaku").val("");
            $(".FrmHendoKobetu.cboSyainNO").empty();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".FrmHendoKobetu.cboSyainNO");
            $(".FrmHendoKobetu.txtSyainNO").val(
                $(".FrmHendoKobetu.cboSyainNO").val()
            );
        }
    });

    $(".FrmHendoKobetu.cmdSearchBs").click(function () {
        $(".FrmHendoKobetu.txtBusyoCD").trigger("focus");
        me.showBusyoDialog();
    });

    $(".FrmHendoKobetu.cmdCopy").click(function () {
        $("<div></div>")
            .prop("id", "cmdCopyDialogDiv")
            .insertAfter($("#FrmHendoKobetu"));

        $("#cmdCopyDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 300,
            width: 450,
            resizable: false,
            close: function () {
                if (me.FrmHendoKobetuCopy.PrpFlg) {
                    me.subFormClear();
                    $(".FrmHendoKobetu.cboYM").val(
                        me.FrmHendoKobetuCopy.prpKeijyoYM
                    );
                    $(".FrmHendoKobetu.txtItemNO").val(
                        me.FrmHendoKobetuCopy.prpItemNO
                    );

                    var tmpId =
                        ".FrmHendoKobetu.cboItemNO option[value='" +
                        me.FrmHendoKobetuCopy.prpItemNO +
                        "']";
                    $(tmpId).prop("selected", true);

                    var tmp = $(".FrmHendoKobetu.txtItemNO").val();

                    if (tmp.substr(0, 3).trimEnd() == "5") {
                        $(".FrmHendoKobetu.cmdTeisyuTrk").show();
                        $(".FrmHendoKobetu.cmdTeisyuTrk").button("enable");
                    } else {
                        $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
                    }
                    me.cboItemNO_Validating();

                    if (
                        $(".FrmHendoKobetu.chkSyainNo").prop("checked") == true
                    ) {
                        //判断是否已经打勾
                        $(".FrmHendoKobetu.txtSyainNO").trigger("focus");
                    } else {
                        $(".FrmHendoKobetu.txtBusyoCD").trigger("focus");
                    }
                }
                $("#cmdCopyDialogDiv").remove();
            },
        });

        var frmId = "FrmHendoKobetuCopy";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#cmdCopyDialogDiv").html(result);

            $("#cmdCopyDialogDiv").dialog(
                "option",
                "title",
                "営業スタッフ個別項目入力(コピー)"
            );
            $("#cmdCopyDialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
    });

    $(".FrmHendoKobetu.cmdTeisyuTrk").click(function () {
        me.url = me.sys_id + "/" + me.id + "/fncExistCheckSel";
        var TOUGETUVal = $(".FrmHendoKobetu.cboYM").val();
        var ITEMCDVal = $(".FrmHendoKobetu.txtItemNO").val();

        var arr = {
            TOUGETU: TOUGETUVal,
            ITEMCD: ITEMCDVal,
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
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncFromTeisyuDelIns;
                clsComFnc.FncMsgBox(
                    "QY999",
                    "既に対象年月の管理台数が登録されていますが、更新しますか？"
                );
            } else {
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncFromTeisyuDelIns;
                clsComFnc.FncMsgBox("QY010");
            }
        };
        ajax.send(me.url, me.data, 0);
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
    me.showBusyoDialog = function () {
        $("<div></div>")
            .prop("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmHendoKobetu"));

        $("<div></div>")
            .prop("id", "BUSYOCD")
            .insertAfter($("#FrmHendoKobetu"));
        $("<div></div>")
            .prop("id", "BUSYONM")
            .insertAfter($("#FrmHendoKobetu"));
        $("<div></div>").prop("id", "RtnCD").insertAfter($("#FrmHendoKobetu"));

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 552 : 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#BUSYONM").hide();
                $("#BUSYOCD").hide();
            },
            close: function () {
                me.RtnCD = $("#RtnCD").html();
                me.GetFncSetRtnData($("#BUSYOCD").html(), $("#BUSYONM").html());

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

    me.priceFormatter = function (num) {
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
    me.fncDeleteHSTAFFKomoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteHSTAFFKomoku";
        var KEIJOBIVal = $(".FrmHendoKobetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoKobetu.txtItemNO").val();
        var txtSyainNOVal = $(".FrmHendoKobetu.txtSyainNO").val().trimEnd();
        var arr = {
            strBusyoCD: "",
            KEIJOBI: KEIJOBIVal,
            ITEMCD: txtItemNOVal,
            SyainNO: txtSyainNOVal,
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
            clsComFnc.ObjFocus = $(".FrmHendoKobetu.cboYM");
            clsComFnc.FncMsgBox("I0004");
            me.subFormClear();
            var txtItemNO = $(".FrmHendoKobetu.txtItemNO").val();
            if (txtItemNO.substr(0, 3).trimEnd() == "5") {
                $(".FrmHendoKobetu.cmdTeisyuTrk").show();
                $(".FrmHendoKobetu.cmdTeisyuTrk").button("enable");
            } else {
                $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
            }

            $(".FrmHendoKobetu.txtGoukei").val("0");
            $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
            me.fncFromHSTAFFSelect(false);
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteInsertHSTAFFKomoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteInsertHSTAFFKomoku";

        var KEIJOBIVal = $(".FrmHendoKobetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoKobetu.txtItemNO").val();
        var txtSyainNOVal = $(".FrmHendoKobetu.txtSyainNO").val().trimEnd();
        var txtKingakuVal = $(".FrmHendoKobetu.txtKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");

        var arr = {
            strBusyoCD: me.strBusyoCD,
            KEIJOBI: KEIJOBIVal,
            ITEMCD: txtItemNOVal,
            SyainNO: txtSyainNOVal,
            KEIJO_GK: txtKingakuVal,
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
            me.subFormClear();
            var txtItemNO = $(".FrmHendoKobetu.txtItemNO").val();
            if (txtItemNO.substr(0, 3).trimEnd() == "5") {
                $(".FrmHendoKobetu.cmdTeisyuTrk").show();
                $(".FrmHendoKobetu.cmdTeisyuTrk").button("enable");
            } else {
                $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
            }

            $(".FrmHendoKobetu.txtGoukei").val("0");
            $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
            me.fncFromHSTAFFSelect(false);

            if ($(".FrmHendoKobetu.chkSyainNo").prop("checked") == true) {
                //判断是否已经打勾
                $(".FrmHendoKobetu.txtSyainNO").trigger("focus");
            } else {
                $(".FrmHendoKobetu.txtBusyoCD").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncFromHSTAFFSelectAction = function (param) {
        me.url = me.sys_id + "/" + me.id + "/fncFromHSTAFFSelect";

        var KEIJOBIVal = $(".FrmHendoKobetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoKobetu.txtItemNO").val();
        var txtSyainNOVal = $(".FrmHendoKobetu.txtSyainNO").val();

        var arr = {
            strBusyoCD: param,
            KEIJOBI: KEIJOBIVal,
            ItemNO: txtItemNOVal,
            SyainNO: txtSyainNOVal,
            blnCheck: true,
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
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteInsertHSTAFFKomoku;
                clsComFnc.FncMsgBox(
                    "QY999",
                    "該当データは既に存在します。修正しますか？"
                );
            } else {
                me.fncDeleteInsertHSTAFFKomoku();
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncInputChkSyainNO = function () {
        var txtSyainNO = $(".FrmHendoKobetu.txtSyainNO").val();
        if (txtSyainNO == "") {
            $(".FrmHendoKobetu.txtSyainNO").css(clsComFnc.GC_COLOR_ERROR);
            clsComFnc.ObjSelect = $(".FrmHendoKobetu.txtSyainNO");
            clsComFnc.FncMsgBox("W0001", "社員番号");
            // me.fncInputChkBln = false;
            return false;
        } else {
            me.url = me.sys_id + "/" + me.id + "/fncSyainmstExist";

            var arr = {
                SyainNO: txtSyainNO.trimEnd(),
                KJNBI: $(".FrmHendoKobetu.cboYM").val(),
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
                    $(".FrmHendoKobetu.txtSyainNO").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    clsComFnc.ObjSelect = $(".FrmHendoKobetu.txtSyainNO");
                    clsComFnc.FncMsgBox("W0008", "社員番号");
                    // me.fncInputChkBln = false;
                    return false;
                } else {
                    if (me.strBusyoCD == "") {
                        me.strBusyoCD = result["data"][0]["BUSYO_CD"];
                    }

                    me.fncFromHSTAFFSelectAction(me.strBusyoCD);

                    return true;
                }
            };
            ajax.send(me.url, me.data, 0);
        }
    };

    me.fncInputChk = function () {
        //部署コード存在ﾁｪｯｸ
        me.fncInputChkBln = false;
        me.strBusyoCD = "";
        var txtBusyoCD = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();
        if (txtBusyoCD != "999" && txtBusyoCD != "") {
            me.url = me.sys_id + "/" + me.id + "/FncGetBusyoMstValue";
            var BusyoCDVal = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();

            var arr = {
                BusyoCD: BusyoCDVal,
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
                    $(".FrmHendoKobetu.txtBusyoCD").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    clsComFnc.ObjSelect = $(".FrmHendoKobetu.txtBusyoCD");
                    clsComFnc.FncMsgBox("W0008", "部署コード");
                    // me.fncInputChkBln = false;
                    return false;
                }

                me.strBusyoCD = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();
                // me.fncInputChkBln = me.fncInputChkSyainNO();
                me.fncInputChkSyainNO();
            };
            ajax.send(me.url, me.data, 0);
        } else {
            // me.fncInputChkBln = me.fncInputChkSyainNO();
            me.fncInputChkSyainNO();
        }
    };

    me.fncFromTeisyuDelIns = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "fncFromTeisyuDelIns";
        var KEIJO_DTVal = $(".FrmHendoKobetu.cboYM").val();
        var DATA_KBVal = $(".FrmHendoKobetu.txtItemNO").val();

        var arr = {
            KEIJO_DT: KEIJO_DTVal,
            DATA_KB: DATA_KBVal,
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

            me.subFormClear();

            var tmp = $(".FrmHendoKobetu.txtItemNO").val();

            if (tmp.substr(0, 3).trimEnd() == "5") {
                $(".FrmHendoKobetu.cmdTeisyuTrk").show();
                $(".FrmHendoKobetu.cmdTeisyuTrk").button("enable");
            } else {
                $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
            }

            $(".FrmHendoKobetu.txtGoukei").val("0");
            $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
            me.fncFromHSTAFFSelect(true);
        };

        ajax.send(me.url, me.data, 0);
    };

    me.cboItemNO_Validating = function () {
        $(".FrmHendoKobetu.txtGoukei").val("0");
        $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
        me.fncFromHSTAFFSelect(false);
    };

    me.GetFncSetRtnData = function (BUSYOCD, BUSYONM) {
        if (me.RtnCD == 1) {
            $(".FrmHendoKobetu.txtBusyoCD").val(BUSYOCD);
            $(".FrmHendoKobetu.lblBusyoNM").val(BUSYONM);
            $(".FrmHendoKobetu.cboSyainNO").trigger("focus");
            var tmp = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();
            if (me.strSaveBusyoM != tmp) {
                me.subComboSet(tmp);
            }
        } else {
            $(".FrmHendoKobetu.txtBusyoCD").trigger("focus");
        }
    };

    me.sprMeisai_CellClick = function (rowId) {
        $(".FrmHendoKobetu.chkSyainNo").prop("checked", false);
        $(".FrmHendoKobetu.cmdSearchBs").button("enable");
        //20151014 yin upd S
        $(".FrmHendoKobetu.cboYM").val(
            me.sprData[rowId]["KEIJO_DT"].substr(0, 7).replace("/", "")
        );
        //20151014 yin upd E
        $(".FrmHendoKobetu.txtItemNO").val(me.sprData[rowId]["ITEMNO"]);
        if (me.sprData[rowId]["ITEMNO"].substr(0, 3).trimEnd() == "5") {
            $(".FrmHendoKobetu.cmdTeisyuTrk").show();
        } else {
            $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
        }
        $(".FrmHendoKobetu.txtBusyoCD").val(me.sprData[rowId]["BUSYO_CD"]);
        $(".FrmHendoKobetu.lblBusyoNM").val(me.sprData[rowId]["BUSYO_NM"]);

        var num = me.sprData[rowId]["KEIJYO_GK"];
        if (num.indexOf("-") == -1) {
            $(".FrmHendoKobetu.txtKingaku").css("color", "black");
        } else {
            $(".FrmHendoKobetu.txtKingaku").css("color", "red");
        }
        $(".FrmHendoKobetu.txtKingaku").val(me.sprData[rowId]["KEIJYO_GK"]);
        if ($(".FrmHendoKobetu.txtBusyoCD").val() != "") {
            me.subComboSet(
                $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd(),
                true,
                rowId
            );
        }
    };

    me.subComboSet = function (txtBusyoCDVal, situFlg, rowId) {
        $(".FrmHendoKobetu.cboSyainNO").empty();
        me.url = me.sys_id + "/" + me.id + "/subComboSet";
        var KJNBIVal = $(".FrmHendoKobetu.cboYM").val();
        var strNameID = clsComFnc.FncNv(txtBusyoCDVal);
        var arr = {
            KJNBI: KJNBIVal,
            strNameID: strNameID,
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
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".FrmHendoKobetu.cboSyainNO");
            if (result["row"] > 0) {
                for (key in result["data"]) {
                    $("<option></option>")
                        .val(result["data"][key]["SYAIN_NO"])
                        .text(result["data"][key]["SYAIN_NM"])
                        .appendTo(".FrmHendoKobetu.cboSyainNO");
                }
                $(".FrmHendoKobetu.txtSyainNO").val(
                    $(".FrmHendoKobetu.cboSyainNO").val()
                );
            }

            if (situFlg) {
                $(".FrmHendoKobetu.txtSyainNO").val(
                    me.sprData[rowId]["SYAIN_NO"]
                );
                // $(".FrmHendoKobetu.cboSyainNO").val(me.sprData[rowId]['SYAIN_NM']);
                // $(".FrmHendoKobetu.cboSyainNO").attr("value", me.sprData[rowId]['SYAIN_NO']);
                var tmpId =
                    ".FrmHendoKobetu.cboSyainNO option[value='" +
                    me.sprData[rowId]["SYAIN_NO"] +
                    "']";
                $(tmpId).prop("selected", true);
                // console.log(me.sprData[rowId]['SYAIN_NO']);
                me.strSaveBusyoM = $(".FrmHendoKobetu.txtBusyoCD")
                    .val()
                    .trimEnd();

                $(".FrmHendoKobetu.txtKingaku").trigger("focus");
                $(".FrmHendoKobetu.txtKingaku").select();
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncFromHSTAFFSelect = function (situFlag) {
        me.url = me.sys_id + "/" + me.id + "/fncFromHSTAFFSelect";

        var txtBusyoCDVal = $(".FrmHendoKobetu.txtBusyoCD").val().trimEnd();
        var KEIJOBIVal = $(".FrmHendoKobetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoKobetu.txtItemNO").val();
        var txtSyainNOVal = $(".FrmHendoKobetu.txtSyainNO").val();

        var arr = {
            strBusyoCD: txtBusyoCDVal,
            KEIJOBI: KEIJOBIVal,
            ItemNO: txtItemNOVal,
            SyainNO: txtSyainNOVal,
            blnCheck: false,
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
            $(".FrmHendoKobetu.txtGoukei").val("0");
            if (result["row"] > 0) {
                me.sprData = result["data"];
                var lngGoukei = 0;
                for (key in result["data"]) {
                    me.col["BusyoCD"] = result["data"][key]["BUSYO_CD"];
                    me.col["BusyoNM"] = result["data"][key]["BUSYO_NM"];
                    me.col["SyainNO"] = result["data"][key]["SYAIN_NO"];
                    me.col["SyainNM"] = result["data"][key]["SYAIN_NM"];
                    me.col["Kingaku"] = result["data"][key]["KEIJYO_GK"];
                    $("#FrmHendoKobetu_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );

                    lngGoukei =
                        lngGoukei + parseInt(result["data"][key]["KEIJYO_GK"]);
                }
                $(".FrmHendoKobetu.txtGoukei").val(
                    me.priceFormatter(lngGoukei.toString())
                );
            }
            if (situFlag) {
                $(".FrmHendoKobetu.cboYM").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.subFormClear = function (blnSitu) {
        if (blnSitu) {
            $(".FrmHendoKobetu.cboSyainNO").empty();
        }
        $(".FrmHendoKobetu.txtBusyoCD").val("");
        $(".FrmHendoKobetu.lblBusyoNM").val("");
        // $(".FrmHendoKobetu.cboSyainNO").empty();
        $(".FrmHendoKobetu.txtSyainNO").val("");
        var tmpId = ".FrmHendoKobetu.cboSyainNO option[value='']";
        $(tmpId).prop("selected", true);
        $(".FrmHendoKobetu.txtKingaku").val("");
        $(".FrmHendoKobetu.txtGoukei").val("");
        $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
        $(".FrmHendoKobetu.cmdTeisyuTrk").hide();
    };

    me.subComboSet2 = function (id) {
        me.url = me.sys_id + "/" + me.id + "/subComboSet2";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] > 0) {
                for (key in result["data"]) {
                    $("<option></option>")
                        .val(result["data"][key]["MEISYOU_CD"])
                        .text(result["data"][key]["MEISYOU"])
                        .appendTo(id + ".cboItemNO");
                }
                $(id + ".txtItemNO").val($(id + ".cboItemNO").val());
            } else {
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(id + ".cboItemNO");
                clsComFnc.FncMsgBox("E9999", "項目番号データが未登録です。");
                return;
            }
            me.fncFromHSTAFFSelect(true);
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
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["result"] == true) {
                        me.syainArray = result["data"];
                        //コントロールマスタ存在ﾁｪｯｸ
                        me.url =
                            me.sys_id + "/" + me.id + "/FrmOptionInput_Load";

                        ajax.receive = function (result) {
                            result = eval("(" + result + ")");

                            var myDate = new Date();
                            var tmpMonth = (myDate.getMonth() + 1).toString();
                            if (tmpMonth.length < 2) {
                                tmpMonth = "0" + tmpMonth.toString();
                            }
                            var tmpNowDate =
                                myDate.getFullYear().toString() +
                                tmpMonth.toString();
                            if (result["result"] == false) {
                                clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }
                            if (result["row"] == 0) {
                                clsComFnc.FncMsgBox(
                                    "E9999",
                                    "コントロールマスタが存在しません！"
                                );
                                $(".FrmHendoKobetu.cboYM").val(tmpNowDate);
                                return;
                            }
                            var strTougetu = clsComFnc
                                .FncNv(result["data"][0]["TOUGETU"])
                                .toString();
                            strTougetu = strTougetu.split("/");
                            //-- 20150922 Yuanjh UPD S.
                            //$(".FrmHendoKobetu.cboYM").val(strTougetu[0] + '/' + strTougetu[1]);
                            $(".FrmHendoKobetu.cboYM").val(
                                strTougetu[0] + strTougetu[1]
                            );
                            //me.cboYM = $(".FrmHendoKobetu.cboYM").val();
                            //me.cboYM = strTougetu[0] + '/' + strTougetu[1];
                            me.cboYM = strTougetu[0] + strTougetu[1];
                            //-- 20150922 Yuanjh UPD E.
                            me.subFormClear(true);

                            me.subComboSet2(".FrmHendoKobetu");
                        };
                        ajax.send(me.url, me.data, 0);
                    }
                };
                ajax.send(me.url, "", 0);
            }
        };

        ajax.send(me.url, "", 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHendoKobetu = new R4.FrmHendoKobetu();
    o_R4_FrmHendoKobetu.load();

    o_R4K_R4K.FrmHendoKobetu = o_R4_FrmHendoKobetu;
});
