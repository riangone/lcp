/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20151013                  #2211                   BUG                         yin
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmKaikeiEdit");

R4.FrmKaikeiEdit = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmKaikeiEdit";
    me.sys_id = "R4K";
    me.url = "";
    me.data = new Array();
    me.FrmKaikei = null;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdBack",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdSearchKmk",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdSearchBs",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdSearchKmk2",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cmdSearchBs2",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaikeiEdit.cboKeiriBi",
        type: "datepicker",
        handle: "",
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

    me.init_control = function () {
        base_init_control();
        $(".FrmKaikeiEdit.txtKriHomoku").numeric({
            decimal: false,
            negative: false,
        });
        $(".FrmKaikeiEdit.txtKriHimoku").numeric({
            decimal: false,
            negative: false,
        });
        $(".FrmKaikeiEdit.txtKasHomoku").numeric({
            decimal: false,
            negative: false,
        });
        $(".FrmKaikeiEdit.txtKasHimoku").numeric({
            decimal: false,
            negative: false,
        });
        $(".FrmKaikeiEdit.txtKingaku").numeric({
            decimal: false,
            negative: false,
        });
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        var tmpDate = myDate.getDate().toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        if (tmpDate.length < 2) {
            tmpDate = "0" + tmpDate.toString();
        }
        var tmpNowDate =
            myDate.getFullYear().toString() +
            "/" +
            tmpMonth.toString() +
            "/" +
            tmpDate.toString();

        $(".FrmKaikeiEdit.cboKeiriBi").val(tmpNowDate);
        me.url = me.sys_id + "/" + me.id + "/fncDataSet";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                me.getAllBusyo = result["data"];
            }

            me.url = me.sys_id + "/" + me.id + "/fncDataSetKamoku";
            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["result"] == true) {
                    me.getAllKamoku = result["data"];
                }
                me.subClearForm();
            };
            ajax.send(me.url, "", 0);
        };

        ajax.send(me.url, "", 0);
    };

    $(".FrmKaikeiEdit.cmdSearchBs").click(function () {
        me.cmdSearchBs("txtKriBusyoCD");
    });

    $(".FrmKaikeiEdit.cmdSearchBs2").click(function () {
        me.cmdSearchBs("txtKasBusyoCD");
    });

    $(".FrmKaikeiEdit.cmdSearchKmk").click(function () {
        me.cmdSearchKamoku("txtKriKamokuCD");
    });

    $(".FrmKaikeiEdit.cmdSearchKmk2").click(function () {
        me.cmdSearchKamoku("txtKasKamokuCD");
    });

    $(".FrmKaikeiEdit.txtKriBusyoCD").on("blur", function () {
        me.setBusyoValBlur("txtKriBusyoCD");
    });

    $(".FrmKaikeiEdit.txtKasBusyoCD").on("blur", function () {
        me.setBusyoValBlur("txtKasBusyoCD");
    });

    $(".FrmKaikeiEdit.txtKriKamokuCD").on("blur", function () {
        me.setKamoKuValBlur("txtKriKamokuCD");
    });
    $(".FrmKaikeiEdit.txtKasKamokuCD").on("blur", function () {
        me.setKamoKuValBlur("txtKasKamokuCD");
    });
    $(".FrmKaikeiEdit.txtKasHomoku").on("blur", function () {
        me.Homoku_Validating("txtKasHomoku");
    });
    $(".FrmKaikeiEdit.txtKriHomoku").on("blur", function () {
        me.Homoku_Validating("txtKriHomoku");
    });

    $(".FrmKaikeiEdit.txtKasHimoku").on("blur", function () {
        me.Himoku_Validating("txtKasHimoku");
    });
    $(".FrmKaikeiEdit.txtKriHimoku").on("blur", function () {
        me.Himoku_Validating("txtKriHimoku");
    });

    $(".FrmKaikeiEdit.txtKriBK").on("blur", function () {
        $(".FrmKaikeiEdit.txtKriBK").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmKaikeiEdit.txtKasBK").on("blur", function () {
        $(".FrmKaikeiEdit.txtKriBK").css(clsComFnc.GC_COLOR_NORMAL);
    });
    $(".FrmKaikeiEdit.txtKriUCNO").on("blur", function () {
        $(".FrmKaikeiEdit.txtKriUCNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmKaikeiEdit.txtKasUCNO").on("blur", function () {
        $(".FrmKaikeiEdit.txtKasUCNO").css(clsComFnc.GC_COLOR_NORMAL);
    });
    $(".FrmKaikeiEdit.txtKriSyainNO").on("blur", function () {
        $(".FrmKaikeiEdit.txtKriSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmKaikeiEdit.txtKasSyainNO").on("blur", function () {
        $(".FrmKaikeiEdit.txtKasSyainNO").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmKaikeiEdit.txtKingaku").keypress(function (e) {
        if (e.keyCode == 229) {
            return false;
        }
        if (!(e.keyCode == 39 || e.keyCode == 37)) {
            var num = $(".FrmKaikeiEdit.txtKingaku").val();
            var num_count = num.length;

            if (num.indexOf("-") == -1 && num_count == 13) {
                if (e.keyCode != 45) {
                    return false;
                }
            }
            if (num.indexOf("-") == 0 && num_count <= 14) {
                $(".FrmKaikeiEdit.txtKingaku").val(num);
                return;
            }
            if (num.indexOf("-") > 0) {
                var num = $(".FrmKaikeiEdit.txtKingaku")
                    .val()
                    .toString()
                    .replace(/-/, "");
                $(".FrmKaikeiEdit.txtKingaku").val(num);
                return;
            }
        }
    });

    $(".FrmKaikeiEdit.txtKingaku").on("blur", function () {
        var num = $(".FrmKaikeiEdit.txtKingaku").val();

        if (num.toString().trimEnd() == "") {
            return;
        }

        // $(".FrmKaikeiEdit.txtKingaku").val(me.priceFormatter($(".FrmKaikeiEdit.txtKingaku").val()));
        $(".FrmKaikeiEdit.txtKingaku").val(
            $(".FrmKaikeiEdit.txtKingaku").val().toString().numFormat()
        );
        if (num.indexOf("-") == -1) {
            $(".FrmKaikeiEdit.txtKingaku").css("color", "black");
        } else {
            $(".FrmKaikeiEdit.txtKingaku").css("color", "red");
        }
    });

    $(".FrmKaikeiEdit.txtKingaku").on("focus", function () {
        // $(".FrmKaikeiEdit.txtKingaku").css('color', 'black');
        var num = $(".FrmKaikeiEdit.txtKingaku")
            .val()
            .toString()
            .replace(/\,/g, "");
        $(".FrmKaikeiEdit.txtKingaku").val(num);
    });

    $(".FrmKaikeiEdit.cmdAction").click(function () {
        //経理日ﾁｪｯｸ
        if (me.FrmKaikei.PrpMenteFlg == "INS") {
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
                var cboKeiriBiVal = $(".FrmKaikeiEdit.cboKeiriBi")
                    .val()
                    .toString()
                    .replace(/\//g, "")
                    .substr(0, 6);

                if (cboKeiriBiVal < result["data"][0]["SYR_YMD"]) {
                    me.subMsgOutput(-2, "経理日", "cboKeiriBi");
                    return;
                }

                //入力ﾁｪｯｸ

                if (!me.fncInputCheck()) {
                    return;
                }
                //存在ﾁｪｯｸ
                me.fncExistsCheck("txtKriBusyoCD");
            };

            ajax.send(me.url, "", 0);
        } else {
            //入力ﾁｪｯｸ

            if (!me.fncInputCheck()) {
                return;
            }
            //存在ﾁｪｯｸ
            me.fncExistsCheck("txtKriBusyoCD");
        }
    });

    shortcut.add("F9", function () {
        var situ = $(".HMS_F9").dialog("isOpen");

        if (situ == true) {
            return;
        }

        $(".FrmKaikeiEdit.cmdAction").trigger("click");
    });

    $(".FrmKaikeiEdit.cmdBack").click(function () {
        $("#DialogDiv").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.fncExistsCheck = function (id) {
        if (
            $(".FrmKaikeiEdit." + id)
                .val()
                .trimEnd() != ""
        ) {
            me.url = me.sys_id + "/" + me.id + "/FncGetBusyoMstValue";
            var BusyoCDVal = $(".FrmKaikeiEdit." + id)
                .val()
                .trimEnd();

            var arr = {
                CD: BusyoCDVal,
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
                    $(".FrmKaikeiEdit." + id).css(clsComFnc.GC_COLOR_ERROR);
                    if (id == "txtKriBusyoCD") {
                        me.subMsgOutput(-8, "借方部署コード", id);
                        return;
                    } else {
                        me.subMsgOutput(-8, "貸方部署コード", id);
                        return;
                    }
                }

                if (id == "txtKriBusyoCD") {
                    me.fncExistsCheckKriKamoku();
                } else {
                    me.fncExistsCheckKasKamoku();
                }
            };
            ajax.send(me.url, me.data, 0);
        } else {
            if (id == "txtKriBusyoCD") {
                me.fncExistsCheckKriKamoku();
            } else {
                me.fncExistsCheckKasKamoku();
            }
        }
    };

    me.fncExistsCheckKasKamoku = function () {
        //貸方科目コード
        if ($(".FrmKaikeiEdit.txtKasKamokuCD").val().trimEnd() != "") {
            if (
                $(".FrmKaikeiEdit.txtKasHimoku").val().trimEnd() == "" &&
                $(".FrmKaikeiEdit.txtKasHomoku").val().trimEnd() == ""
            ) {
                me.blnFlag = false;
                me.fncExistsCheckKamoku("txtKasKamokuCD", "");
            } else {
                if (
                    $(".FrmKaikeiEdit.txtKasHomoku").val().trimEnd() != "" &&
                    $(".FrmKaikeiEdit.txtKasHimoku").val().trimEnd() == ""
                ) {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKasKamokuCD",
                        $(".FrmKaikeiEdit.txtKasHomoku").val().trimEnd()
                    );
                } else if (
                    $(".FrmKaikeiEdit.txtKasHimoku").val().trimEnd() != "" &&
                    $(".FrmKaikeiEdit.txtKasHomoku").val().trimEnd() == ""
                ) {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKasKamokuCD",
                        $(".FrmKaikeiEdit.txtKasHimoku").val().trimEnd()
                    );
                } else {
                    me.blnFlag = true;
                    me.fncExistsCheckKamoku(
                        "txtKasKamokuCD",
                        $(".FrmKaikeiEdit.txtKasHomoku").val().trimEnd()
                    );
                }
            }
        }
    };

    me.fncExistsCheckKriKamoku = function () {
        //借方科目コード
        if ($(".FrmKaikeiEdit.txtKriKamokuCD").val().trimEnd() != "") {
            if (
                $(".FrmKaikeiEdit.txtKriHimoku").val().trimEnd() == "" &&
                $(".FrmKaikeiEdit.txtKriHomoku").val().trimEnd() == ""
            ) {
                me.blnFlag = false;
                me.fncExistsCheckKamoku("txtKriKamokuCD", "");
            } else {
                if (
                    $(".FrmKaikeiEdit.txtKriHomoku").val().trimEnd() != "" &&
                    $(".FrmKaikeiEdit.txtKriHimoku").val().trimEnd() == ""
                ) {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKriKamokuCD",
                        $(".FrmKaikeiEdit.txtKriHomoku").val().trimEnd()
                    );
                } else if (
                    $(".FrmKaikeiEdit.txtKriHimoku").val().trimEnd() != "" &&
                    $(".FrmKaikeiEdit.txtKriHomoku").val().trimEnd() == ""
                ) {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKriKamokuCD",
                        $(".FrmKaikeiEdit.txtKriHimoku").val().trimEnd()
                    );
                } else {
                    me.blnFlag = true;
                    me.fncExistsCheckKamoku(
                        "txtKriKamokuCD",
                        $(".FrmKaikeiEdit.txtKriHomoku").val().trimEnd()
                    );
                }
            }
        } else {
            //貸方部署コード
            me.fncExistsCheck("txtKasBusyoCD");
        }
    };

    me.fncExistsCheckKamoku = function (id, val) {
        me.url = me.sys_id + "/" + me.id + "/FncGetKamokuMstValue";
        var KamokuVal = $(".FrmKaikeiEdit." + id)
            .val()
            .trimEnd();

        var arr = {
            CD: KamokuVal,
            strKomoku: val,
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
                $(".FrmKaikeiEdit." + id).css(clsComFnc.GC_COLOR_ERROR);

                if (id == "txtKriKamokuCD") {
                    me.subMsgOutput(-8, "借方科目コード", id);
                    return;
                } else {
                    me.subMsgOutput(-8, "貸方科目コード", id);
                    return;
                }
            }
            if (me.blnFlag) {
                if (id == "txtKriKamokuCD") {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKriKamokuCD",
                        $(".FrmKaikeiEdit.txtKriHimoku").val().trimEnd()
                    );
                    return;
                } else {
                    me.blnFlag = false;
                    me.fncExistsCheckKamoku(
                        "txtKasKamokuCD",
                        $(".FrmKaikeiEdit.txtKasHimoku").val().trimEnd()
                    );
                    return;
                }
            } else {
                //貸方部署コード
                if (id == "txtKriKamokuCD") {
                    me.fncExistsCheck("txtKasBusyoCD");
                } else {
                    var arr = {
                        KEIJO_DT: $(".FrmKaikeiEdit.cboKeiriBi")
                            .val()
                            .toString()
                            .replace(/\//g, ""),
                        SYOHY_NO: $(".FrmKaikeiEdit.txtSyouhyo")
                            .val()
                            .toString()
                            .trimEnd(),
                        DENPY_NO: $(".FrmKaikeiEdit.txtDenpyoNO")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_BUSYO_CD: $(".FrmKaikeiEdit.txtKriBusyoCD")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_KAMOK_CD: $(".FrmKaikeiEdit.txtKriKamokuCD")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_KOMOK_CD: $(".FrmKaikeiEdit.txtKriHomoku")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_HIMOK_CD: $(".FrmKaikeiEdit.txtKriHimoku")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_BK: $(".FrmKaikeiEdit.txtKriBK")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_UC_NO: $(".FrmKaikeiEdit.txtKriUCNO")
                            .val()
                            .toString()
                            .trimEnd(),
                        L_SYAIN_NO: $(".FrmKaikeiEdit.txtKriSyainNO")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_BUSYO_CD: $(".FrmKaikeiEdit.txtKasBusyoCD")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_KAMOK_CD: $(".FrmKaikeiEdit.txtKasKamokuCD")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_KOMOK_CD: $(".FrmKaikeiEdit.txtKasHomoku")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_HIMOK_CD: $(".FrmKaikeiEdit.txtKasHimoku")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_BK: $(".FrmKaikeiEdit.txtKasBK")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_UC_NO: $(".FrmKaikeiEdit.txtKasUCNO")
                            .val()
                            .toString()
                            .trimEnd(),
                        R_SYAIN_NO: $(".FrmKaikeiEdit.txtKasSyainNO")
                            .val()
                            .toString()
                            .trimEnd(),
                        KEIJO_GK: $(".FrmKaikeiEdit.txtKingaku")
                            .val()
                            .toString()
                            .replace(/\,/g, ""),
                        TEKIYO1: $(".FrmKaikeiEdit.txtTekiyo1")
                            .val()
                            .toString()
                            .trimEnd(),
                        TEKIYO2: $(".FrmKaikeiEdit.txtTekiyo2")
                            .val()
                            .toString()
                            .trimEnd(),
                        TEKIYO3: $(".FrmKaikeiEdit.txtTekiyo3")
                            .val()
                            .toString()
                            .trimEnd(),
                        GYO_NO: me.FrmKaikei.prpGyoNO,
                    };

                    me.data = {
                        request: arr,
                    };

                    if (me.FrmKaikei.PrpMenteFlg == "INS") {
                        me.url = me.sys_id + "/" + me.id + "/fncInsertKaikei";

                        ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (result["result"] == false) {
                                clsComFnc.FncMsgBox("E9999", result["data"]);
                                clsComFnc.FncMsgBox("E0007");
                                return;
                            }

                            me.subClearForm();
                        };

                        ajax.send(me.url, me.data, 0);
                    } else {
                        me.url = me.sys_id + "/" + me.id + "/fncUpdateKaikei";
                        ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (result["result"] == false) {
                                clsComFnc.FncMsgBox("E9999", result["data"]);
                                clsComFnc.FncMsgBox("E0007");
                                return;
                            }

                            $("#DialogDiv").dialog("close");
                        };

                        ajax.send(me.url, me.data, 0);
                    }
                }
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncInputCheck = function () {
        var intRtn = "";
        if (me.FrmKaikei.PrpMenteFlg == "INS") {
            //伝票№
            intRtn = clsComFnc.FncTextCheck(
                $(".FrmKaikeiEdit.txtDenpyoNO"),
                1,
                clsComFnc.INPUTTYPE.CHAR2
            );
            if (intRtn < 0) {
                me.subMsgOutput(intRtn, "伝票番号", "txtDenpyoNO");
                return false;
            }
        }
        //借方部署コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriBusyoCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方部署コード", "txtKriBusyoCD");
            return false;
        }

        //借方科目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriKamokuCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方科目コード", "txtKriKamokuCD");
            return false;
        }

        //借方補目コード
        //20151013 yin upd S
        if (me.FrmKaikei.PrpMenteFlg == "INS") {
            intRtn = clsComFnc.FncTextCheck(
                $(".FrmKaikeiEdit.txtKriHomoku"),
                0,
                clsComFnc.INPUTTYPE.NUMBER1
            );
            if (intRtn < 0) {
                me.subMsgOutput(intRtn, "借方補目コード", "txtKriHomoku");
                return false;
            }
        } else {
            intRtn = $(".FrmKaikeiEdit.txtKriHomoku").val().length;
            if (intRtn > 5) {
                me.subMsgOutput(-2, "借方補目コード", "txtKriHomoku");
                return false;
            }
        }
        //20151013 yin upd E

        //借方費目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriHimoku"),
            0,
            clsComFnc.INPUTTYPE.NUMBER2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方費目コード", "txtKriHimoku");
            return false;
        }

        //借方B/K
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriBK"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方B/K", "txtKriBK");
            return false;
        }

        //借方UCNO
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriUCNO"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方UCNO", "txtKriUCNO");
            return false;
        }

        //借方社員№
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKriSyainNO"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "借方摘要(社員№)", "txtKriSyainNO");
            return false;
        }

        //貸方部署コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasBusyoCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方部署コード", "txtKasBusyoCD");
            return false;
        }

        //貸方科目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasKamokuCD"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方科目コード", "txtKasKamokuCD");
            return false;
        }

        //貸方補目コード
        //20151013 yin upd S
        if (me.FrmKaikei.PrpMenteFlg == "INS") {
            intRtn = clsComFnc.FncTextCheck(
                $(".FrmKaikeiEdit.txtKasHomoku"),
                0,
                clsComFnc.INPUTTYPE.NUMBER1
            );
            if (intRtn < 0) {
                me.subMsgOutput(intRtn, "貸方補目コード", "txtKasHomoku");
                return false;
            }
        } else {
            intRtn = $(".FrmKaikeiEdit.txtKasHomoku").val().length;
            if (intRtn > 5) {
                me.subMsgOutput(-2, "貸方補目コード", "txtKasHomoku");
                return false;
            }
        }
        //20151013 yin upd E

        //貸方費目コード
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasHimoku"),
            0,
            clsComFnc.INPUTTYPE.NUMBER2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方費目コード", "txtKasHimoku");
            return false;
        }

        //貸方B/K
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasBK"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方B/K", "txtKasBK");
            return false;
        }

        //貸方UCNO
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasUCNO"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方UCNO", "txtKasUCNO");
            return false;
        }

        //貸方社員№
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKasSyainNO"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "貸方摘要(社員№)", "txtKasSyainNO");
            return false;
        }

        //金額
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtKingaku"),
            1,
            clsComFnc.INPUTTYPE.NUMBER2
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "金額", "txtKingaku");
            return false;
        }

        //摘要１
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtTekiyo1"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "摘要", "txtTekiyo1");
            return false;
        }
        //摘要2
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtTekiyo2"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "摘要", "txtTekiyo2");
            return false;
        }
        //摘要3
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtTekiyo3"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "摘要", "txtTekiyo3");
            return false;
        }

        //証憑№
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmKaikeiEdit.txtSyouhyo"),
            0,
            clsComFnc.INPUTTYPE.NONE
        );
        if (intRtn < 0) {
            me.subMsgOutput(intRtn, "証憑№", "txtSyouhyo");
            return false;
        }
        return true;
    };

    me.subMsgOutput = function (intErrMsgno, strerrmsg, id) {
        switch (intErrMsgno) {
            case -1:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0001", strerrmsg);

                break;
            case -2:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0002", strerrmsg);
                break;
            case -3:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0003", strerrmsg);
                break;
            case -6:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0006", strerrmsg);
                break;
            case -7:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0007", strerrmsg);
                break;
            case -8:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0008", strerrmsg);
                break;
            case -9:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W9999", strerrmsg);
                break;
            case -15:
                clsComFnc.ObjSelect = $(".FrmKaikeiEdit." + id);
                clsComFnc.FncMsgBox("W0015", strerrmsg);
                break;
        }
    };
    me.Himoku_Validating = function (id) {
        var leng = $(".FrmKaikeiEdit." + id)
            .val()
            .trimEnd().length;

        if (leng == 1) {
            $(".FrmKaikeiEdit." + id).val(
                "0" +
                    $(".FrmKaikeiEdit." + id)
                        .val()
                        .trimEnd()
            );
        }
    };
    me.Homoku_Validating = function (id) {
        var leng = $(".FrmKaikeiEdit." + id)
            .val()
            .trimEnd().length;
        if (id == "txtKriHomoku") {
            if (leng < 3) {
                if (leng == 1) {
                    $(".FrmKaikeiEdit.txtKriHimoku").val(
                        "0" +
                            $(".FrmKaikeiEdit." + id)
                                .val()
                                .trimEnd()
                    );
                } else {
                    $(".FrmKaikeiEdit.txtKriHimoku").val(
                        $(".FrmKaikeiEdit." + id)
                            .val()
                            .trimEnd()
                    );
                }
            } else {
                $(".FrmKaikeiEdit.txtKriHimoku").val(
                    $(".FrmKaikeiEdit." + id)
                        .val()
                        .trimEnd()
                        .substr(1, 2)
                );
            }
            // $(".FrmKaikeiEdit.txtKriHimoku").select();
        } else {
            if (leng < 3) {
                if (leng == 1) {
                    $(".FrmKaikeiEdit.txtKasHimoku").val(
                        "0" +
                            $(".FrmKaikeiEdit." + id)
                                .val()
                                .trimEnd()
                    );
                } else {
                    $(".FrmKaikeiEdit.txtKasHimoku").val(
                        $(".FrmKaikeiEdit." + id)
                            .val()
                            .trimEnd()
                    );
                }
            } else {
                $(".FrmKaikeiEdit.txtKasHimoku").val(
                    $(".FrmKaikeiEdit." + id)
                        .val()
                        .trimEnd()
                        .substr(1, 2)
                );
            }
            // $(".FrmKaikeiEdit.txtKasHimoku").select();
        }
    };

    me.setBusyoValBlur = function (id) {
        var tmp = $(".FrmKaikeiEdit." + id)
            .val()
            .trimEnd();
        $(".FrmKaikeiEdit." + id).css(clsComFnc.GC_COLOR_NORMAL);
        if (id == "txtKriBusyoCD") {
            me.lblname = "lblKriBusyoNM";
        } else {
            me.lblname = "lblKasBusyoNM";
        }

        $(".FrmKaikeiEdit." + me.lblname).val("");
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYOCD"] == tmp) {
                $(".FrmKaikeiEdit." + me.lblname).val(
                    me.getAllBusyo[key]["BUSYONM"]
                );
            }
        }
    };

    me.setKamoKuValBlur = function (id) {
        var tmp = $(".FrmKaikeiEdit." + id)
            .val()
            .trimEnd();
        $(".FrmKaikeiEdit." + id).css(clsComFnc.GC_COLOR_NORMAL);
        if (id == "txtKriKamokuCD") {
            me.lblname = "lblKriKamokuNM";
        } else {
            me.lblname = "lblKasKamokuNM";
        }

        $(".FrmKaikeiEdit." + me.lblname).val("");
        for (key in me.getAllKamoku) {
            if (me.getAllKamoku[key]["KAMOKUCD"] == tmp) {
                $(".FrmKaikeiEdit." + me.lblname).val(
                    me.getAllKamoku[key]["KAMOKUNM"]
                );
            }
        }
    };

    me.cmdSearchKamoku = function (id) {
        $(".FrmKaikeiEdit." + id).trigger("focus");
        $("<div></div>")
            .attr("id", "FrmKamokuSearchDialogDiv")
            .insertAfter($("#FrmKaikeiEdit"));

        $("<div></div>")
            .attr("id", "KAMOKUCD")
            .insertAfter($("#FrmKaikeiEdit"));
        $("<div></div>")
            .attr("id", "KAMOKUNM")
            .insertAfter($("#FrmKaikeiEdit"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmKaikeiEdit"));

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
        ajax.send(url, me.data, 0);
        ajax.receive = function (result) {
            $("#FrmKamokuSearchDialogDiv").html(result);

            $("#FrmKamokuSearchDialogDiv").dialog(
                "option",
                "title",
                "科目コード検索"
            );
            $("#FrmKamokuSearchDialogDiv").dialog("open");
        };
    };

    me.cmdSearchBs = function (id) {
        $(".FrmKaikeiEdit." + id).trigger("focus");
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmKaikeiEdit"));

        $("<div></div>").attr("id", "BUSYOCD").insertAfter($("#FrmKaikeiEdit"));
        $("<div></div>").attr("id", "BUSYONM").insertAfter($("#FrmKaikeiEdit"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmKaikeiEdit"));

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
                me.GetFncSetRtnData(
                    $("#BUSYOCD").html(),
                    $("#BUSYONM").html(),
                    id
                );

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

    me.GetFncSetRtnData = function (BUSYOCD, BUSYONM, id) {
        if (me.RtnCD == 1) {
            $(".FrmKaikeiEdit." + id).val(BUSYOCD);
            if (id == "txtKriBusyoCD") {
                $(".FrmKaikeiEdit.lblKriBusyoNM").val(BUSYONM);
                $(".FrmKaikeiEdit.txtKriKamokuCD").select();
            } else {
                $(".FrmKaikeiEdit.lblKasBusyoNM").val(BUSYONM);
                $(".FrmKaikeiEdit.txtKasKamokuCD").select();
            }
        } else {
            $(".FrmKaikeiEdit." + id).select();
        }
    };

    me.GetFncSetRtnDataKamoku = function (KAMOKUCD, KAMOKUNM, id) {
        if (me.RtnCD == 1) {
            $(".FrmKaikeiEdit." + id).val(KAMOKUCD);
            if (id == "txtKriKamokuCD") {
                $(".FrmKaikeiEdit.lblKriKamokuNM").val(KAMOKUNM);
                $(".FrmKaikeiEdit.txtKriHomoku").select();
            } else {
                $(".FrmKaikeiEdit.lblKasKamokuNM").val(KAMOKUNM);
                $(".FrmKaikeiEdit.txtKasHomoku").select();
            }
        } else {
            $(".FrmKaikeiEdit." + id).select();
        }
    };

    me.subClearForm = function () {
        me.url = me.sys_id + "/" + me.id + "/GetSysDate";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmKaikeiEdit.lblToday").val(result.substring(0, 7));
            $(".FrmKaikeiEdit.txtDenpyoNO").val("");
            $(".FrmKaikeiEdit.txtKriBusyoCD").val("");
            $(".FrmKaikeiEdit.lblKriBusyoNM").val("");
            $(".FrmKaikeiEdit.txtKriKamokuCD").val("");
            $(".FrmKaikeiEdit.lblKriKamokuNM").val("");
            $(".FrmKaikeiEdit.txtKriHomoku").val("");
            $(".FrmKaikeiEdit.txtKriHimoku").val("");
            $(".FrmKaikeiEdit.txtKriBK").val("");
            $(".FrmKaikeiEdit.txtKriUCNO").val("");
            $(".FrmKaikeiEdit.txtKriSyainNO").val("");
            $(".FrmKaikeiEdit.txtKasBusyoCD").val("");
            $(".FrmKaikeiEdit.lblKasBusyoNM").val("");
            $(".FrmKaikeiEdit.txtKasKamokuCD").val("");
            $(".FrmKaikeiEdit.lblKasKamokuNM").val("");
            $(".FrmKaikeiEdit.txtKasHomoku").val("");
            $(".FrmKaikeiEdit.txtKasHimoku").val("");
            $(".FrmKaikeiEdit.txtKasBK").val("");
            $(".FrmKaikeiEdit.txtKasUCNO").val("");
            $(".FrmKaikeiEdit.txtKasSyainNO").val("");
            $(".FrmKaikeiEdit.txtKingaku").val("");
            $(".FrmKaikeiEdit.txtTekiyo1").val("");
            $(".FrmKaikeiEdit.txtTekiyo2").val("");
            $(".FrmKaikeiEdit.txtTekiyo3").val("");
            $(".FrmKaikeiEdit.txtSyouhyo").val("");

            $(".FrmKaikeiEdit.cboKeiriBi").trigger("focus");

            if (me.FrmKaikei.PrpMenteFlg == "UPD") {
                $(".FrmKaikeiEdit.cboKeiriBi").val(me.FrmKaikei.prpKeijyoBi);
                $(".FrmKaikeiEdit.txtDenpyoNO").val(me.FrmKaikei.prpDenpy_NO);

                me.url = me.sys_id + "/" + me.id + "/fncKaikeiSet";

                var arrayVal = {
                    KEIJYO: me.FrmKaikei.prpKeijyoBi,
                    DENPYO: me.FrmKaikei.prpDenpy_NO,
                    GYO_NO: me.FrmKaikei.prpGyoNO,
                };

                me.data = {
                    request: arrayVal,
                };

                ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["row"] == 0) {
                        clsComFnc.FncMsgBox("I0001");
                        return;
                    }
                    $(".FrmKaikeiEdit.txtKriBusyoCD").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_BUSYO_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.lblKriBusyoNM").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_BUSYO_NM"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriKamokuCD").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_KAMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.lblKriKamokuNM").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_KAMOK_NM"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriHomoku").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_KOMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriHimoku").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_HIMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriBK").val(
                        clsComFnc.FncNv(result["data"][0]["L_BK"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriUCNO").val(
                        clsComFnc.FncNv(result["data"][0]["L_UC_NO"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKriSyainNO").val(
                        clsComFnc
                            .FncNv(result["data"][0]["L_SYAIN_NO"])
                            .trimEnd()
                    );

                    $(".FrmKaikeiEdit.txtKasBusyoCD").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_BUSYO_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.lblKasBusyoNM").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_BUSYO_NM"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasKamokuCD").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_KAMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.lblKasKamokuNM").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_KAMOK_NM"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasHomoku").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_KOMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasHimoku").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_HIMOK_CD"])
                            .trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasBK").val(
                        clsComFnc.FncNv(result["data"][0]["R_BK"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasUCNO").val(
                        clsComFnc.FncNv(result["data"][0]["R_UC_NO"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtKasSyainNO").val(
                        clsComFnc
                            .FncNv(result["data"][0]["R_SYAIN_NO"])
                            .trimEnd()
                    );
                    // $(".FrmKaikeiEdit.txtKingaku").val(me.priceFormatter(clsComFnc.FncNv(result["data"][0]["KEIJO_GK"]).trimEnd()));
                    $(".FrmKaikeiEdit.txtKingaku").val(
                        clsComFnc
                            .FncNv(result["data"][0]["KEIJO_GK"])
                            .toString()
                            .trimEnd()
                            .numFormat()
                    );
                    $(".FrmKaikeiEdit.txtTekiyo1").val(
                        clsComFnc.FncNv(result["data"][0]["TEKIYO1"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtTekiyo2").val(
                        clsComFnc.FncNv(result["data"][0]["TEKIYO2"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtTekiyo3").val(
                        clsComFnc.FncNv(result["data"][0]["TEKIYO3"]).trimEnd()
                    );
                    $(".FrmKaikeiEdit.txtSyouhyo").val(
                        clsComFnc.FncNv(result["data"][0]["SYOHY_NO"]).trimEnd()
                    );

                    $(".FrmKaikeiEdit.cboKeiriBi").attr("disabled", "disabled");
                    $(".FrmKaikeiEdit.txtDenpyoNO").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".FrmKaikeiEdit.txtKriBusyoCD").trigger("focus");
                };

                ajax.send(me.url, me.data, 0);
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    // me.priceFormatter = function(num)
    // {
    // var num_moto = num;
    // var num = num_moto.replace(/-/, "");
    // sign = (num == ( num = Math.abs(num)));
    // num = Math.floor(num * 10 + 0.50000000001);
    // num = Math.floor(num / 10).toString();
    // for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
    // {
    // num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
    // }
    //
    // var val = ((num == 0) ? '0' : num);
    //
    // if (num_moto.match("-") && num != 0)
    // {
    // val = '-' + val;
    // }
    // else
    // {
    // val = val;
    // }
    // return val;
    // };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKaikeiEdit = new R4.FrmKaikeiEdit();
    o_R4_FrmKaikeiEdit.load();

    o_R4K_R4K.FrmKaikei.FrmKaikeiEdit = o_R4_FrmKaikeiEdit;
    o_R4_FrmKaikeiEdit.FrmKaikei = o_R4K_R4K.FrmKaikei;
});
