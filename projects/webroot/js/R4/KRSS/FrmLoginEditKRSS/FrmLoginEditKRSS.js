/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20160527			  #2529							依頼								Yinhuaiyu
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmLoginEditKRSS");

KRSS.FrmLoginEditKRSS = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.id = "FrmLoginEditKRSS";
    me.sys_id = "KRSS";
    me.ajax = gdmz.common.ajax();
    me.strTougetu = "";

    //FrmALoginSel画面を戻る
    me.bolResult = false;

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmLoginEditKRSS.Button3",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginEditKRSS.Button1",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginEditKRSS.Button2",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_load = me.load;

    me.load = function () {
        base_load();
        me.FrmLoginEditKRSS_load();
    };

    // // ========== コントロース end ==========
    // // ==========
    // // = 宣言 end =
    // // ==========
    //
    // ==========
    // = イベント start =
    // ==========
    $(".FrmLoginEditKRSS.cboSysKB").change(function () {
        me.fncSyozokuComboSet();
    });
    $(".FrmLoginEditKRSS.Button1").click(function () {
        me.fncButton1Click();
    });
    $(".FrmLoginEditKRSS.Button3").click(function () {
        me.fncButton3Click();
    });
    $(".FrmLoginEditKRSS.Button2").click(function () {
        $("#FrmLoginSelKRSS_SubDialog").dialog("close");
    });

    $(".FrmLoginEditKRSS.UcComboBox1").change(function () {
        me.fncUcComboBox1Change();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.FrmLoginEditKRSS_load = function () {
        var SysData = me.o_KRSS_FrmLoginSelKRSS.SysData;
        for (var i = 0; i < SysData.length; i++) {
            $(".FrmLoginEditKRSS.cboSysKB").append(
                "<option value='" +
                    SysData[i]["SYS_KB"] +
                    "'>" +
                    SysData[i]["STYLE_NM"] +
                    "</option>"
            );
        }

        $(".FrmLoginEditKRSS.cboSysKB").prop("disabled", "disabled");
        $(".FrmLoginEditKRSS.cboSysKB")
            .find("option[value='" + me.o_KRSS_FrmLoginSelKRSS.SysKB + "']")
            .prop("selected", true);
        $(".FrmLoginEditKRSS.UcTextBox1").trigger("focus");
        $(".FrmLoginEditKRSS.UcUserID").prop("disabled", "disabled");
        $(".FrmLoginEditKRSS.UcUserNM").prop("disabled", "disabled");

        var data = {
            UserID: me.o_KRSS_FrmLoginSelKRSS.userId_SYAIN_NO,
            cboSysKB: $(".FrmLoginEditKRSS.cboSysKB").val(),
        };
        var tmpurl1 = me.sys_id + "/" + me.id + "/fncLoadDeal";

        me.ajax.receive = function (result1) {
            var result1 = eval("(" + result1 + ")");
            if (result1["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result1["data"]);
                return;
            }
            //me.fncSyozokuComboSet();
            me.REC_CRE_DT = clsComFnc.FncNz(
                result1["arrUserInfo"][0]["REC_CRE_DT"]
            );
            for (var t = 0; t < result1["arrSTYLEID"].length; t++) {
                $(".FrmLoginEditKRSS.UcComboBox1").append(
                    "<option value='" +
                        result1["arrSTYLEID"][t]["STYLE_ID"] +
                        "'>" +
                        result1["arrSTYLEID"][t]["STYLE_NM"] +
                        "</option>"
                );
            }
            for (var t = 0; t < result1["arrPattern"].length; t++) {
                $(".FrmLoginEditKRSS.UcComboBox2").append(
                    "<option value='" +
                        result1["arrPattern"][t]["PATTERN_ID"] +
                        "'>" +
                        result1["arrPattern"][t]["PATTERN_NM"] +
                        "</option>"
                );
            }
            if (result1["arrUserInfo"].length >= 1) {
                for (var t = 0; t < result1["arrUserInfo"].length; t++) {
                    $(".FrmLoginEditKRSS.UcUserID").val(
                        result1["arrUserInfo"][0]["SYAIN_NO"]
                    );
                    $(".FrmLoginEditKRSS.UcUserNM").val(
                        result1["arrUserInfo"][0]["SYAIN_NM"]
                    );
                    $(".FrmLoginEditKRSS.password").val(
                        result1["arrUserInfo"][0]["PASSWORD"]
                    );
                    $(".FrmLoginEditKRSS.rePassword").val(
                        result1["arrUserInfo"][0]["PASSWORD"]
                    );
                    for (
                        var n = 0;
                        n <
                        $(".FrmLoginEditKRSS.UcComboBox1").find("option")
                            .length;
                        n++
                    ) {
                        $(".FrmLoginEditKRSS.UcComboBox1")
                            .find(
                                "option[value='" +
                                    result1["arrUserInfo"][0]["STYLE_ID"] +
                                    "']"
                            )
                            .prop("selected", true);
                        break;
                    }
                    for (
                        var n = 0;
                        n <
                        $(".FrmLoginEditKRSS.UcComboBox2").find("option")
                            .length;
                        n++
                    ) {
                        $(".FrmLoginEditKRSS.UcComboBox2")
                            .find(
                                "option[value='" +
                                    result1["arrUserInfo"][0]["PATTERN_ID"] +
                                    "']"
                            )
                            .prop("selected", true);
                        break;
                    }
                }
            }

            if ($(".FrmLoginEditKRSS.UcComboBox1").val() != "") {
                $(".FrmLoginEditKRSS.UcComboBox1")
                    .find(
                        "option[value='" +
                            $(".FrmLoginEditKRSS.UcComboBox1").val() +
                            "']"
                    )
                    .prop("selected", true);
            }
        };
        me.ajax.send(tmpurl1, data, 0);
    };
    me.fncSyozokuComboSet = function () {
        $(".FrmLoginEditKRSS.UcComboBox1").empty();
        $(".FrmLoginEditKRSS.UcComboBox1").append("<option></option>");
        var data = $(".FrmLoginEditKRSS.cboSysKB").val();
        var tmpurl1 = me.sys_id + "/" + me.id + "/fncSyozokuComboSet";

        me.ajax.receive = function (result1) {
            var result1 = eval("(" + result1 + ")");
            if (result1["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result1["data"]);
                return;
            }

            for (var i = 0; i < result1["data"].length; i++) {
                $(".FrmLoginEditKRSS.UcComboBox1").append(
                    "<option value='" +
                        result1["data"][i]["STYLE_ID"] +
                        "'>" +
                        result1["data"][i]["STYLE_NM"] +
                        "</option>"
                );
            }
        };
        me.ajax.send(tmpurl1, data, 0);
    };
    me.fncUcComboBox1Change = function () {
        var url = me.sys_id + "/" + me.id + "/SetPatternCombox";
        var selIndex = $(".FrmLoginEditKRSS.UcComboBox1 option:selected").val();

        if (selIndex == "") {
            $(".FrmLoginEditKRSS.UcComboBox2").empty();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".FrmLoginEditKRSS.UcComboBox2");
            var tmpId = ".FrmLoginEditKRSS.UcComboBox2 option[value='']";
            $(tmpId).prop("selected", true);
            return;
        }

        var data = {
            UserID: selIndex,
            cboSysKB: $(".FrmLoginEditKRSS.cboSysKB").val(),
            UcComboBox1: $(".FrmLoginEditKRSS.UcComboBox1").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.setPatternValues(result["data"], "");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };

        me.ajax.send(url, data, 0);
    };
    me.setPatternValues = function (arrResult, patternID) {
        $(".FrmLoginEditKRSS.UcComboBox2").empty();
        var strPatternID = "";
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmLoginEditKRSS.UcComboBox2");

        for (key in arrResult) {
            if (arrResult[key]["PATTERN_NM"] != "") {
                $(".FrmLoginEditKRSS.UcComboBox2").append(
                    "<option value='" +
                        arrResult[key]["PATTERN_ID"] +
                        "'>" +
                        arrResult[key]["PATTERN_NM"] +
                        "</option>"
                );
                if (arrResult[key]["PATTERN_ID"] == patternID) {
                    strPatternID = patternID;
                }
            }
        }
        console.log(strPatternID);
        var tmpId =
            ".FrmLoginEditKRSS.UcComboBox2 option[value='" +
            strPatternID +
            "']";
        $(tmpId).prop("selected", true);
    };
    me.fncInputChk = function () {
        //パスワードの必須ﾁｪｯｸ
        var intRetNo = clsComFnc.FncTextCheck(
            $(".FrmLoginEditKRSS.password"),
            1,
            clsComFnc.INPUTTYPE.CHAR2,
            10
        );

        if (intRetNo == -1) {
            clsComFnc.ObjFocus = $(".FrmLoginEditKRSS.password");
            clsComFnc.FncMsgBox("E9999", "パスワードを入力して下さい。");
            return false;
        } else {
            if (intRetNo == -3) {
                clsComFnc.ObjFocus = $(".FrmLoginEditKRSS.password");
                clsComFnc.FncMsgBox("W0003", "パスワード");
                return false;
            }
        }
        var intRetNo = clsComFnc.FncTextCheck(
            $(".FrmLoginEditKRSS.rePassword"),
            1,
            clsComFnc.INPUTTYPE.CHAR2,
            10
        );

        if (intRetNo == -1) {
            clsComFnc.ObjFocus = $(".FrmLoginEditKRSS.rePassword");
            clsComFnc.FncMsgBox("E9999", "パスワード確認を入力して下さい。");
            return false;
        } else {
            if (intRetNo == -3) {
                clsComFnc.ObjFocus = $(".FrmLoginEditKRSS.rePassword");
                clsComFnc.FncMsgBox("W0003", "パスワード確認");
                return false;
            }
        }

        if (
            $(".FrmLoginEditKRSS.password").val() !=
            $(".FrmLoginEditKRSS.rePassword").val()
        ) {
            clsComFnc.ObjSelect = $(".FrmLoginEditKRSS.rePassword");
            clsComFnc.FncMsgBox(
                "E9999",
                "パスワードとパスワード確認の内容が異なっております。"
            );
            return false;
        }

        if ($(".FrmLoginEditKRSS.UcComboBox2 option:selected").val() == "") {
            clsComFnc.ObjFocus = $(".FrmLoginEditKRSS.UcComboBox2");
            clsComFnc.FncMsgBox("E9999", "パターンＩＤを入力して下さい。");
            return false;
        }

        return true;
    };
    me.fncButton3Click = function () {
        //入力チェックを行う。
        if (!me.fncInputChk()) {
            return false;
        }

        //登録確認ﾒｯｾｰｼﾞを表示する
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMst;
        clsComFnc.FncMsgBox("QY010");
    };

    me.fncDeleteUpdataMst = function () {
        var url = me.sys_id + "/" + me.id + "/fncDeleteUpdataMst";
        var sendData = {
            SYS_KB: $(".FrmLoginEditKRSS.cboSysKB").val(),
            USER_ID: $(".FrmLoginEditKRSS.UcUserID").val(),
            PASSWORD: $(".FrmLoginEditKRSS.password").val(),
            REC_CRE_DT: me.REC_CRE_DT,
            STYLE_ID: $(".FrmLoginEditKRSS.UcComboBox1 option:selected").val(),
            PATTERN_ID: $(
                ".FrmLoginEditKRSS.UcComboBox2 option:selected"
            ).val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9998", result["data"]);
                return;
            } else {
                console.log("close1");
                //閉じる
                $("#FrmLoginSelKRSS_SubDialog").dialog("close");

                console.log("close2");
                me.bolResult = true;
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.fncButton2Click = function () {
        $("#FrmLoginEditKRSSDialogDiv").dialog("close");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmLoginEditKRSS = new KRSS.FrmLoginEditKRSS();
    o_KRSS_KRSS.o_KRSS_FrmLoginSelKRSS.o_KRSS_FrmLoginEditKRSS =
        o_KRSS_FrmLoginEditKRSS;
    o_KRSS_FrmLoginEditKRSS.o_KRSS_FrmLoginSelKRSS =
        o_KRSS_KRSS.o_KRSS_FrmLoginSelKRSS;
    o_KRSS_FrmLoginEditKRSS.load();
});
