Namespace.register("KRSS.FrmHiyouMeisai");

KRSS.FrmHiyouMeisai = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "FrmHiyouMeisai";
    me.sys_id = "KRSS";
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.Validating_Busyo_Array = [];
    me.Validating_Kamoku_Array = [];
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmHiyouMeisai.cmd004",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmHiyouMeisai.cboYM",
        type: "datepicker3",
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

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    base_load = me.load;
    me.load = function () {
        base_load();
        me.fncGetBusyoKamokuMstValue_and_load();
    };

    // ==========
    // = イベント start =
    // ==========

    $(".KRSS.FrmHiyouMeisai.cboYM").on("blur", function () {
        if (me.clsComFnc.CheckDate3($(".KRSS.FrmHiyouMeisai.cboYM")) == false) {
            $(".KRSS.FrmHiyouMeisai.cboYM").val(me.tmpNowDate);
            $(".KRSS.FrmHiyouMeisai.cboYM").trigger("focus");
        }
    });

    $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").on("blur", function (e) {
        if (e.target.value.trimEnd() == "") {
            $(".KRSS.FrmHiyouMeisai.lblBusyoCDFrom").val("");
            return;
        }
        var tmpVal = me.searchBusyoValue(e.target.value);
        $(".KRSS.FrmHiyouMeisai.lblBusyoCDFrom").val(tmpVal);
        $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val(e.target.value);
    });

    $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").on("blur", function (e) {
        if (e.target.value.trimEnd() == "") {
            $(".KRSS.FrmHiyouMeisai.lblBusyoCDTo").val("");
            return;
        }
        var tmpVal = me.searchBusyoValue(e.target.value);
        $(".KRSS.FrmHiyouMeisai.lblBusyoCDTo").val(tmpVal);
    });

    $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").on("blur", function (e) {
        if (e.target.value.trimEnd() == "") {
            $(".KRSS.FrmHiyouMeisai.lblKamokuCDFrom").val("");
            return;
        }
        var tmpVal = me.searchKamokuValue(e.target.value);
        $(".KRSS.FrmHiyouMeisai.lblKamokuCDFrom").val(tmpVal);
        $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").val(e.target.value);
    });

    $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").on("blur", function (e) {
        if (e.target.value.trimEnd() == "") {
            $(".KRSS.FrmHiyouMeisai.lblKamokuCDTo").val("");
            return;
        }
        var tmpVal = me.searchKamokuValue(e.target.value);
        $(".KRSS.FrmHiyouMeisai.lblKamokuCDTo").val(tmpVal);
    });

    $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").keydown(function (_e) {
        $(this).css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
    });

    $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").keydown(function (_e) {
        $(this).css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
    });

    $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").keydown(function (_e) {
        $(this).css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
    });

    $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").keydown(function (_e) {
        $(this).css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
        $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );
    });

    $(".KRSS.FrmHiyouMeisai.cmd004").click(function (e) {
        me.excelExport(e);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
	 '関 数 名：frmHiyouMeisai_Load
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：初期設定
	 '**********************************************************************
	 */
    me.FrmHiyouMeisai_Load = function () {
        //初期処理
        me.subClearForm();
        //コントロールマスタ存在ﾁｪｯｸ
        var tmpUrl = me.sys_id + "/" + me.id + "/fncHKEIRICTL";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            var myDate = new Date();
            var tmpMonth = (myDate.getMonth() + 1).toString();
            if (tmpMonth.length < 2) {
                tmpMonth = "0" + tmpMonth.toString();
            }
            me.tmpNowDate =
                myDate.getFullYear().toString() + tmpMonth.toString();
            $(".KRSS.FrmHiyouMeisai.cboYM").val(me.tmpNowDate);
            if (result["result"] != true) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //コントロールマスタが存在していない場合
            if (result["row"] == 0) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                return;
            }
            //コンボボックスに当月年月を設定
            $(".KRSS.FrmHiyouMeisai.cboYM").val(
                me.clsComFnc.FncNv(result["data"][0]["TOUGETU"])
            );

            //権限のﾁｪｯｸを行う
            var tmpUrl1 = me.sys_id + "/" + me.id + "/fncAuthCheck";
            me.ajax.receive = function (result1) {
                var result1 = eval("(" + result1 + ")");
                if (result1["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result1["data"]);
                    return;
                }
                if (result1["row"] == 0) {
                    //0件の場合
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "権限の設定がされていません。管理者にご連絡ください！"
                    );
                    return;
                } else if (result1["row"] == 1) {
                    //1件の場合
                    //部署コード
                    $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").val(
                        me.fncDataNullStr(result1["data"][0]["BUSYO_CD"])
                    );
                    $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val(
                        me.fncDataNullStr(result1["data"][0]["BUSYO_CD"])
                    );

                    for (key in me.Validating_Busyo_Array) {
                        if (
                            me.Validating_Busyo_Array[key]["BUSYO_CD"] ==
                            me.fncDataNullStr(result1["data"][0]["BUSYO_CD"])
                        ) {
                            $(".KRSS.FrmHiyouMeisai.lblBusyoCDFrom").val(
                                me.Validating_Busyo_Array[key]["BUSYO_NM"]
                            );
                            $(".KRSS.FrmHiyouMeisai.lblBusyoCDTo").val(
                                me.Validating_Busyo_Array[key]["BUSYO_NM"]
                            );
                        }
                    }

                    $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").prop(
                        "disabled",
                        "true"
                    );
                    $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").prop(
                        "disabled",
                        "true"
                    );
                    me.fncAuthorityInvest(
                        me.fncDataNullStr(result1["data"][0]["BUSYO_CD"])
                    );
                } else {
                    //'>1件の場合
                    //'何も処理しない
                    $(".KRSS.FrmHiyouMeisai.cboYM").trigger("focus");
                }
            };
            me.ajax.send(tmpUrl1, "", 0);
        };
        me.ajax.send(tmpUrl, "", 0);
    };

    me.fncAuthorityInvest = function (busyocd) {
        var tempArr = [];
        var i = 0;
        //获取本画面所有的input,button
        for (i = 0; i < $(".KRSS.FrmHiyouMeisai:input").length; i++) {
            //过滤掉lbl.因为有些lbl是<input> disabled.
            if (
                $($(".KRSS.FrmHiyouMeisai:input")[i])
                    .attr("class")
                    .split(" ")[2]
                    .match("lbl") == null
            ) {
                tempArr.push(
                    $($(".KRSS.FrmHiyouMeisai:input")[i])
                        .attr("class")
                        .split(" ")[2]
                );
            }
        }
        console.log(tempArr);

        me.url = me.sys_id + "/" + me.id + "/" + "fncAuthorityInvest";
        var data = {
            controls: tempArr,
            BusyoCd: busyocd,
        };

        me.ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    for (key in result["data"]) {
                        //権限あり
                        if (result["data"][key] == 1) {
                            if (key.match("txt")) {
                                $(".KRSS.FrmHiyouMeisai." + key).prop(
                                    "disabled",
                                    "false"
                                );
                            } else {
                                $(".KRSS.FrmHiyouMeisai." + key).button(
                                    "enable"
                                );
                            }
                        }
                        //権限なし
                        else {
                            if (key.match("txt")) {
                                $(".KRSS.FrmHiyouMeisai." + key).prop(
                                    "disabled",
                                    "true"
                                );
                            } else {
                                $(".KRSS.FrmHiyouMeisai." + key).button(
                                    "disable"
                                );
                            }
                        }
                    }
                }
                $(".KRSS.FrmHiyouMeisai.cboYM").trigger("focus");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#FrmHiyouMeisai").block();
                return;
            }
        };
        me.ajax.send(me.url, data, 0);
    };

    me.subClearForm = function () {
        $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").val("");
        $(".KRSS.FrmHiyouMeisai.txtBusyoNMFrom").html("");
        $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val("");
        $(".KRSS.FrmHiyouMeisai.txtBusyoNMTo").html("");
        $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").val("");
        $(".KRSS.FrmHiyouMeisai.txtKamokuNMMFrom").html("");
        $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").val("");
        $(".KRSS.FrmHiyouMeisai.txtKamokuNMTo").html("");
        $(".KRSS.FrmHiyouMeisai.frameTime").css("visibility", "hidden");
    };

    me.searchBusyoValue = function (busyoCD) {
        var tcnt = 0;
        for (key in me.Validating_Busyo_Array) {
            if (me.Validating_Busyo_Array[key]["BUSYO_CD"] == busyoCD) {
                return me.Validating_Busyo_Array[key]["BUSYO_NM"];
            }
            tcnt++;
        }

        if (tcnt == me.Validating_Busyo_Array.length) {
            return "";
        }
    };

    me.searchKamokuValue = function (kamokuCD) {
        var tcnt = 0;
        for (key in me.Validating_Kamoku_Array) {
            if (me.Validating_Kamoku_Array[key]["KAMOK_CD"] == kamokuCD) {
                return me.Validating_Kamoku_Array[key]["KAMOK_NM"];
            }
            tcnt++;
        }

        if (tcnt == me.Validating_Kamoku_Array.length) {
            return "";
        }
    };
    me.fncGetBusyoKamokuMstValue_and_load = function () {
        var tmpUrl4 = me.sys_id + "/" + me.id + "/fncGetBusyoMstValue";
        me.ajax.receive = function (result4) {
            result4 = eval("(" + result4 + ")");
            if (result4["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result4["data"]);
                return;
            }
            me.Validating_Busyo_Array = result4["data"];
            console.log(me.Validating_Busyo_Array);
            var tmpUrl5 = me.sys_id + "/" + me.id + "/fncGetKamokuMstValue";
            me.ajax.receive = function (result5) {
                result5 = eval("(" + result5 + ")");
                if (result5["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result5["data"]);
                    return;
                }
                me.Validating_Kamoku_Array = result5["data"];
                console.log(me.Validating_Kamoku_Array);
                me.FrmHiyouMeisai_Load();
            };
            me.ajax.send(tmpUrl5, "", 0);
        };
        me.ajax.send(tmpUrl4, "", 0);
    };
    me.excelExport = function (_e) {
        //入力ﾁｪｯｸ
        if (me.fncInputCheck() == false) {
            return;
        }
        var objDateTime = new Date();
        var h = objDateTime.getHours();
        var m = objDateTime.getMinutes();
        var m1 = m + 10;
        var h1 = 0;
        var s = objDateTime.getSeconds();
        if (h < 10) {
            h = "0" + h;
        }
        if (m < 10) {
            m = "0" + m;
        }

        if (m1 == 60) {
            m1 = "00";
            h1 = h + 1;
            if (h1 == 24) {
                h1 = "00";
            }
        } else if (m1 > 60) {
            m1 = "0" + (m1 % 10);
            h1 = h + 1;
            if (h1 == 24) {
                h1 = "00";
            }
        } else {
            h1 = h;
        }

        if (s < 10) {
            s = "0" + s;
        }

        var nowTime = h + ":" + m + ":" + s;
        var nowTime1 = h1 + ":" + m1 + ":" + s;
        // console.log(nowTime);
        // console.log(nowTime1);
        //console.log(objDateTime.getMinutes+10);
        // return;
        $(".KRSS.FrmHiyouMeisai.frameTime").css("visibility", "visible");
        $(".KRSS.FrmHiyouMeisai.lblMSG").css("visibility", "visible");
        $(".KRSS.FrmHiyouMeisai.finishTime").html("終了预定時刻");

        $(".KRSS.FrmHiyouMeisai.txtStartTime").val(nowTime);
        $(".KRSS.FrmHiyouMeisai.txtEndTime").val(nowTime1);
        $(".KRSS.FrmHiyouMeisai.txtStartTime").prop("disabled", "disabled");
        $(".KRSS.FrmHiyouMeisai.txtEndTime").prop("disabled", "disabled");
        $(".KRSS.FrmHiyouMeisai.lblMSG").html("  処理中です");

        //印刷処理
        var tmpCmdButtonName = "cmd002";
        var tmpUrl6 = me.sys_id + "/" + me.id + "/fncHiyoumeisaiSelExcel";
        var data = {
            cmdButton: tmpCmdButtonName,
            cboYM: $(".KRSS.FrmHiyouMeisai.cboYM").val(),
            txtBusyoCDFrom: $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").val(),
            txtBusyoCDTo: $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val(),
            txtKamokuCDFrom: $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").val(),
            txtKamokuCDTo: $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").val(),
        };
        me.ajax.receive = function (result6) {
            result6 = eval("(" + result6 + ")");
            console.log(result6);
            if (result6["result"] == true) {
                var objDateTime = new Date();
                var h = objDateTime.getHours();
                var m = objDateTime.getMinutes();
                var s = objDateTime.getSeconds();
                if (h < 10) {
                    h = "0" + h;
                }
                if (m < 10) {
                    m = "0" + m;
                }

                if (s < 10) {
                    s = "0" + s;
                }

                var nowTime = h + ":" + m + ":" + s;

                $(".KRSS.FrmHiyouMeisai.txtEndTime").val(nowTime);
                $(".KRSS.FrmHiyouMeisai.finishTime").html("終了時刻");
                $(".KRSS.FrmHiyouMeisai.lblMSG").css("visibility", "hidden");

                window.location.href = result6["data"];
            } else {
                if (result6["data"] == "nodata") {
                    me.clsComFnc.FncMsgBox("I0001");
                    $(".KRSS.FrmHiyouMeisai.lblMSG").css(
                        "visibility",
                        "hidden"
                    );
                    return;
                }

                me.clsComFnc.FncMsgBox("E9999", result6["data"]);
                $(".KRSS.FrmHiyouMeisai.lblMSG").css("visibility", "hidden");
            }
        };
        me.ajax.send(tmpUrl6, data, 0);
    };
    //----common functions----
    me.fncDataNullStr = function (obj) {
        if (obj == null) {
            return "";
        } else {
            return obj.toString();
        }
    };
    me.fncInputCheck = function () {
        //部署コード大小ﾁｪｯｸ
        if (
            $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom")
                .val()
                .toString()
                .trimEnd() != "" &&
            $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val().toString() != ""
        ) {
            if (
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").val() >
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").val()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".KRSS.FrmHiyouMeisai.txtBusyoCDFrom"
                );
                me.clsComFnc.FncMsgBox("W9999", "部署コードの範囲が不正です");
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );

                // $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").focus();
                return false;
            } else {
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                $(".KRSS.FrmHiyouMeisai.txtBusyoCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
            }
        }

        //科目コード大小ﾁｪｯｸ
        if (
            $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom")
                .val()
                .toString()
                .trimEnd() != "" &&
            $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo")
                .val()
                .toString()
                .trimEnd() != ""
        ) {
            if (
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom")
                    .val()
                    .toString()
                    .trimEnd() >
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo")
                    .val()
                    .toString()
                    .trimEnd()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".KRSS.FrmHiyouMeisai.txtKamokuCDFrom"
                );
                me.clsComFnc.FncMsgBox("W9999", "科目コードの範囲が不正です");
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                return false;
            } else {
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDTo").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                $(".KRSS.FrmHiyouMeisai.txtKamokuCDFrom").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
            }
        }

        return true;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmHiyouMeisai = new KRSS.FrmHiyouMeisai();
    o_R4_FrmHiyouMeisai.load();
});
