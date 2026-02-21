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
 * 20150922                  #2162                   BUG                         Yuanjh
 * 20201117                  bug                     年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmHendoBubetu");

R4.FrmHendoBubetu = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmHendoBubetu";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.FrmBusyoSearch = null;
    me.FrmHendoBubetuCopy = null;
    me.sprData = "";
    me.RtnCD = "";
    me.strSaveBusyoM = "";
    me.blnAther = false;
    me.strBusyoCD = "";
    me.fncInputChkBln = "";
    me.cboItemNo = "";
    me.getAllBusyo = "";
    me.col = {
        BusyoCD: "",
        BusyoNM: "",
        Kingaku: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHendoBubetu.cmdCopy",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetu.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetu.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetu.cmdSearchBs",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHendoBubetu.cmdSearch1",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetu.cboYM",
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
            width: 130,
            align: "left",
            sortable: false,
        },
        {
            name: "BusyoNM",
            label: "部署名",
            index: "BusyoNM",
            width: 210,
            align: "left",
            sortable: false,
        },
        {
            name: "Kingaku",
            label: "金額",
            index: "Kingaku",
            width: 180,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
    ];

    $("#FrmHendoBubetu_sprMeisai").jqGrid({
        datatype: "local",
        //20180206 YIN UPD S
        // height : 250,
        height: me.ratio === 1.5 ? 186 : 240,
        //20180206 YIN UPD E
        emptyRecordRow: false,
        colModel: me.colModel,
        ondblClickRow: function (rowId) {
            me.sprMeisai_CellClick(rowId);
        },
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

    $(".FrmHendoBubetu.txtBusyoCD").on("blur", function () {
        $(".FrmHendoBubetu.txtBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmHendoBubetu.cmdAction").click(function () {
        //入力チェック
        me.fncInputChk();
    });

    shortcut.add("F9", function () {
        me.fncInputChk();
    });

    $(".FrmHendoBubetu.txtBusyoCD").on("blur", function () {
        var tmp = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        $(".FrmHendoBubetu.txtBusyoCD").css(clsComFnc.GC_COLOR_NORMAL);
        $(".FrmHendoBubetu.lblBusyoNM").val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmHendoBubetu.lblBusyoNM").val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
    });

    $(".FrmHendoBubetu.cmdDelete").click(function () {
        var txtBusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        if (txtBusyoCDVal == "") {
            $(".FrmHendoBubetu.txtBusyoCD").css(clsComFnc.GC_COLOR_ERROR);
            clsComFnc.ObjSelect = $(".FrmHendoBubetu.txtBusyoCD");
            clsComFnc.FncMsgBox("W9999", "部署コードが指定されていません");
            return;
        }

        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteHSTAFFKomoku;
        clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmHendoBubetu.cboYM").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmHendoBubetu.cboYM")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (clsComFnc.CheckDate3($(".FrmHendoBubetu.cboYM")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmHendoBubetu.cboYM").val(me.cboYM);
                $(".FrmHendoBubetu.cboYM").trigger("focus");
                $(".FrmHendoBubetu.cboYM").select();
                $(".FrmHendoBubetu.cmdCopy").button("disable");
                $(".FrmHendoBubetu.cmdAction").button("disable");
                $(".FrmHendoBubetu.cmdDelete").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmHendoBubetu.cmdCopy").button("enable");
            $(".FrmHendoBubetu.cmdAction").button("enable");
            $(".FrmHendoBubetu.cmdDelete").button("enable");
        }
    });

    $(".FrmHendoBubetu.cmdSearch1").click(function () {
        $(".FrmHendoBubetu.txtGoukei").val("0");
        $("#FrmHendoBubetu_sprMeisai").jqGrid("clearGridData");
        me.fncFromHSTAFFSelect(false);
    });

    $(".FrmHendoBubetu.txtKingaku").keyup(function (event) {
        if (event.keyCode == 13) {
            $(".FrmHendoBubetu.cmdAction").trigger("focus");
            me.fncInputChk();
        } else {
            var num = $(".FrmHendoBubetu.txtKingaku").val();
            var num_count = num.length;

            if (num.indexOf("-") == -1 && num_count <= 13) {
                $(".FrmHendoBubetu.txtKingaku").val(num);
                return;
            }
            if (num.indexOf("-") == -1 && num_count == 14) {
                $(".FrmHendoBubetu.txtKingaku").val(num.substring(0, 13));
                return;
            }
            if (num.indexOf("-") == 0 && num_count <= 14) {
                $(".FrmHendoBubetu.txtKingaku").val(num);
                return;
            }
            if (num.indexOf("-") > 0) {
                var num = $(".FrmHendoBubetu.txtKingaku")
                    .val()
                    .toString()
                    .replace(/-/, "");
                $(".FrmHendoBubetu.txtKingaku").val(num);
                return;
            }
        }
    });

    $(".FrmHendoBubetu.txtKingaku").on("blur", function () {
        var num = $(".FrmHendoBubetu.txtKingaku").val();
        $(".FrmHendoBubetu.txtKingaku").val(
            me.priceFormatter($(".FrmHendoBubetu.txtKingaku").val())
        );
        if (num.indexOf("-") == -1) {
            $(".FrmHendoBubetu.txtKingaku").css("color", "black");
        } else {
            $(".FrmHendoBubetu.txtKingaku").css("color", "red");
        }
    });

    $(".FrmHendoBubetu.txtKingaku").on("focus", function () {
        var num = $(".FrmHendoBubetu.txtKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");

        $(".FrmHendoBubetu.txtKingaku").val(num);
    });

    $(".FrmHendoBubetu.cboItemNO").change(function () {
        $(".FrmHendoBubetu.cmdTeisyuTrk").hide();
        $(".FrmHendoBubetu.txtItemNO").val(
            $(".FrmHendoBubetu.cboItemNO").val()
        );
        // var tmp = $(".FrmHendoBubetu.txtItemNO").val();
    });

    $(".FrmHendoBubetu.cmdSearchBs").click(function () {
        $(".FrmHendoBubetu.txtBusyoCD").trigger("focus");
        $("<div></div>")
            .prop("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmHendoBubetu"));
        // ($("<div></div>").attr("id", "setDate")).insertAfter($("#FrmHendoBubetu"));

        $("<div></div>")
            .prop("id", "BUSYOCD")
            .insertAfter($("#FrmHendoBubetu"));
        $("<div></div>")
            .prop("id", "BUSYONM")
            .insertAfter($("#FrmHendoBubetu"));
        $("<div></div>").prop("id", "RtnCD").insertAfter($("#FrmHendoBubetu"));

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
    });

    $(".FrmHendoBubetu.cmdCopy").click(function () {
        $("<div></div>")
            .prop("id", "cmdCopyDialogDiv")
            .insertAfter($("#FrmHendoBubetu"));

        $("#cmdCopyDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 165,
            width: 400,
            resizable: false,
            close: function () {
                if (me.FrmHendoBubetuCopy.PrpFlg) {
                    me.subFormClear();
                    // $(".FrmHendoBubetu.cboYM").val(me.FrmHendoBubetuCopy.prpKeijyoYM);
                    $(".FrmHendoBubetu.txtItemNO").val(me.cboItemNo);
                    var tmpId =
                        ".FrmHendoBubetu.cboItemNO option[value='" +
                        me.cboItemNo +
                        "']";
                    $(tmpId).prop("selected", true);

                    me.cboItemNO_Validating();
                }
                $("#cmdCopyDialogDiv").remove();
            },
        });

        var frmId = "FrmHendoBubetuCopy";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#cmdCopyDialogDiv").html(result);

            $("#cmdCopyDialogDiv").dialog(
                "option",
                "title",
                "営業スタッフ部別項目入力(コピー)"
            );
            $("#cmdCopyDialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
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

    me.cboItemNO_Validating = function () {
        $(".FrmHendoKobetu.txtGoukei").val("0");
        $("#FrmHendoKobetu_sprMeisai").jqGrid("clearGridData");
        me.fncFromHSTAFFSelect(false);
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
        var KEIJOBIVal = $(".FrmHendoBubetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoBubetu.txtItemNO").val();
        var txtBusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        var arr = {
            strBusyoCD: txtBusyoCDVal,
            KEIJOBI: KEIJOBIVal,
            ITEMCD: txtItemNOVal,
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
            clsComFnc.ObjFocus = $(".FrmHendoBubetu.cboYM");
            clsComFnc.FncMsgBox("I0004");
            me.subFormClear();
            $(".FrmHendoBubetu.txtGoukei").val("0");
            $("#FrmHendoBubetu_sprMeisai").jqGrid("clearGridData");
            me.fncFromHSTAFFSelect(false);
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteInsertHSTAFFKomoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteInsertHSTAFFKomoku";

        var KEIJOBIVal = $(".FrmHendoBubetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoBubetu.txtItemNO").val();
        var txtKingakuVal = $(".FrmHendoBubetu.txtKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");
        var txtBusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val();
        var arr = {
            strBusyoCD: txtBusyoCDVal,
            KEIJOBI: KEIJOBIVal,
            ITEMCD: txtItemNOVal,
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

            $(".FrmHendoBubetu.txtGoukei").val("0");
            $("#FrmHendoBubetu_sprMeisai").jqGrid("clearGridData");
            me.fncFromHSTAFFSelect(false);

            $(".FrmHendoBubetu.txtBusyoCD").trigger("focus");
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncFromHSTAFFSelectAction = function (param) {
        me.url = me.sys_id + "/" + me.id + "/fncFromHSTAFFSelect";

        // var txtBusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        var KEIJOBIVal = $(".FrmHendoBubetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoBubetu.txtItemNO").val();

        var arr = {
            strBusyoCD: param,
            KEIJOBI: KEIJOBIVal,
            ItemNO: txtItemNOVal,
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

    me.fncInputChk = function () {
        var txtBusyoCD = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        if (txtBusyoCD == "") {
            $(".FrmHendoBubetu.txtBusyoCD").css(clsComFnc.GC_COLOR_ERROR);
            clsComFnc.ObjSelect = $(".FrmHendoBubetu.txtBusyoCD");
            clsComFnc.FncMsgBox("W0001", "部署コード");
            return;
        }

        // if (txtBusyoCD != '999')
        else {
            //部署コード存在ﾁｪｯｸ
            me.url = me.sys_id + "/" + me.id + "/FncGetBusyoMstValue";
            var BusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();

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
                    $(".FrmHendoBubetu.txtBusyoCD").css(
                        clsComFnc.GC_COLOR_ERROR
                    );
                    clsComFnc.ObjSelect = $(".FrmHendoBubetu.txtBusyoCD");
                    clsComFnc.FncMsgBox("W0008", "部署コード");
                    return;
                }

                me.fncFromHSTAFFSelectAction(txtBusyoCD);
            };
            ajax.send(me.url, me.data, 0);
        }
    };

    me.GetFncSetRtnData = function (BUSYOCD, BUSYONM) {
        if (me.RtnCD == 1) {
            $(".FrmHendoBubetu.txtBusyoCD").val(BUSYOCD);
            $(".FrmHendoBubetu.lblBusyoNM").val(BUSYONM);
            // var tmp = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
            // if (me.strSaveBusyoM != tmp)
            // {
            // me.subComboSet(tmp);
            // }
        } else {
            $(".FrmHendoBubetu.txtBusyoCD").trigger("focus");
        }
    };

    me.sprMeisai_CellClick = function (rowId) {
        $(".FrmHendoBubetu.txtItemNO").val(me.sprData[rowId]["ITEMNO"]);
        $(".FrmHendoBubetu.txtBusyoCD").val(me.sprData[rowId]["BUSYO_CD"]);
        $(".FrmHendoBubetu.lblBusyoNM").val(me.sprData[rowId]["BUSYO_NM"]);
        var num = me.sprData[rowId]["KEIJYO_GK"];
        if (num.indexOf("-") == -1) {
            $(".FrmHendoBubetu.txtKingaku").css("color", "black");
        } else {
            $(".FrmHendoBubetu.txtKingaku").css("color", "red");
        }
        $(".FrmHendoBubetu.txtKingaku").val(me.sprData[rowId]["KEIJYO_GK"]);
        $(".FrmHendoBubetu.txtKingaku").select();
    };

    me.fncFromHSTAFFSelect = function (situFlag) {
        me.url = me.sys_id + "/" + me.id + "/fncFromHSTAFFSelect";

        var txtBusyoCDVal = $(".FrmHendoBubetu.txtBusyoCD").val().trimEnd();
        var KEIJOBIVal = $(".FrmHendoBubetu.cboYM").val();
        var txtItemNOVal = $(".FrmHendoBubetu.txtItemNO").val();

        var arr = {
            strBusyoCD: txtBusyoCDVal,
            KEIJOBI: KEIJOBIVal,
            ItemNO: txtItemNOVal,
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

            if (result["row"] > 0) {
                me.sprData = result["data"];
                var lngGoukei = 0;
                for (key in result["data"]) {
                    me.col["BusyoCD"] = result["data"][key]["BUSYO_CD"];
                    me.col["BusyoNM"] = result["data"][key]["BUSYO_NM"];
                    me.col["Kingaku"] = result["data"][key]["KEIJYO_GK"];
                    $("#FrmHendoBubetu_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );

                    lngGoukei =
                        lngGoukei + parseInt(result["data"][key]["KEIJYO_GK"]);
                }
                $(".FrmHendoBubetu.txtGoukei").val(
                    me.priceFormatter(lngGoukei.toString())
                );
            }
            if (situFlag) {
                $(".FrmHendoBubetu.cboYM").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.subFormClear = function () {
        $(".FrmHendoBubetu.txtBusyoCD").val("");
        $(".FrmHendoBubetu.lblBusyoNM").val("");
        $(".FrmHendoBubetu.txtKingaku").val("");
        $(".FrmHendoBubetu.txtGoukei").val("");
        $("#FrmHendoBubetu_sprMeisai").jqGrid("clearGridData");
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
                clsComFnc.FncMsgBox("E9999", "項目番号データが未登録です。");
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(id + ".cboItemNO");
                return;
            }
            me.cboItemNo = $(".FrmHendoBubetu.cboItemNO").val();
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
                //コントロールマスタ存在ﾁｪｯｸ
                me.url = me.sys_id + "/" + me.id + "/FrmOptionInput_Load";

                ajax.receive = function (result) {
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
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["row"] == 0) {
                        clsComFnc.FncMsgBox(
                            "E9999",
                            "コントロールマスタが存在しません！"
                        );
                        //-- 20150922 Yuanjh UPD S.
                        $(".FrmHendoBubetu.cboYM").val(
                            myDate.getFullYear().toString() +
                                tmpMonth.toString()
                        );
                        //$(".FrmHendoBubetu.cboYM").val(tmpNowDate);
                        //-- 20150922 Yuanjh UPD E.
                        return;
                    }
                    var strTougetu = clsComFnc
                        .FncNv(result["data"][0]["TOUGETU"])
                        .toString();
                    strTougetu = strTougetu.split("/");
                    //-- 20150922 Yuanjh UPD S.
                    //$(".FrmHendoBubetu.cboYM").val(strTougetu[0] + '/' + strTougetu[1]);
                    $(".FrmHendoBubetu.cboYM").val(
                        strTougetu[0] + strTougetu[1]
                    );
                    //-- 20150922 Yuanjh UPD E.
                    me.cboYM = $(".FrmHendoBubetu.cboYM").val();
                    me.subFormClear();

                    me.subComboSet2(".FrmHendoBubetu");
                };
                ajax.send(me.url, me.data, 0);
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
    var o_R4_FrmHendoBubetu = new R4.FrmHendoBubetu();
    o_R4_FrmHendoBubetu.load();

    o_R4K_R4K.FrmHendoBubetu = o_R4_FrmHendoBubetu;
});
