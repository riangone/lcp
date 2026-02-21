Namespace.register("R4.FrmLoginEdit");

R4.FrmLoginEdit = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmLoginEdit";
    me.FrmLoginSel = null;
    me.REC_CRE_DT = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmLoginEdit.Button3",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmLoginEdit.UcComboBox1").change(function () {
        var url = me.id + "/SetPatternCombox";
        var selIndex = $(".FrmLoginEdit.UcComboBox1 option:selected").val();

        if (selIndex == "") {
            $(".FrmLoginEdit.UcComboBox2").empty();

            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".FrmLoginEdit.UcComboBox2");
            var tmpId = ".FrmLoginEdit.UcComboBox2 option[value='']";
            $(tmpId).prop("selected", true);
            return;
        }

        var data = {
            UserID: selIndex,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.setPatternValues(result["data"], "");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, data, 0);
    });

    $(".FrmLoginEdit.Button3").click(function () {
        //入力チェックを行う。
        if (!me.fncInputChk()) {
            return;
        }
        //登録確認ﾒｯｾｰｼﾞを表示する
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMst;
        me.clsComFnc.FncMsgBox("QY010");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        var url = me.id + "/fncLoadDeal";
        var data = {
            UserID: me.FrmLoginSel.UserID,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["arrUserInfo"].length > 0) {
                    $(".FrmLoginEdit.Label8").val(
                        result["arrUserInfo"][0]["SYAIN_NO"]
                    );
                    $(".FrmLoginEdit.Label7").val(
                        result["arrUserInfo"][0]["SYAIN_NM"]
                    );
                    $(".FrmLoginEdit.UcTextBox1").val(
                        result["arrUserInfo"][0]["PASSWORD"]
                    );
                    //$(".FrmLoginEdit.UcTextBox2").val(result['arrUserInfo'][0]['PASSWORD']);
                }
                me.setSelectValues(
                    result["arrSTYLEID"],
                    result["arrUserInfo"][0]["STYLE_ID"]
                );
                me.setPatternValues(
                    result["data"],
                    result["arrUserInfo"][0]["PATTERN_ID"]
                );

                me.REC_CRE_DT = me.clsComFnc.FncNz(
                    result["arrUserInfo"][0]["REC_CRE_DT"]
                );
                $(".FrmLoginEdit.UcTextBox1").select();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, data, 1);
    };

    // '**********************************************************************
    // 'フォームロード時、COMBOXコントロールの初期化
    // '**********************************************************************
    me.setSelectValues = function (arrResult, styleID) {
        $(".FrmLoginEdit.UcComboBox1").empty();
        var strStyleID = "";
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmLoginEdit.UcComboBox1");

        for (key in arrResult) {
            if (arrResult[key]["STYLE_NM"] != "") {
                arrResult[key]["STYLE_NM"] = me.clsComFnc.fncGetFixVal(
                    arrResult[key]["STYLE_NM"],
                    18
                );
                $("<option></option>")
                    .val(arrResult[key]["STYLE_ID"])
                    .text(arrResult[key]["STYLE_NM"])
                    .appendTo(".FrmLoginEdit.UcComboBox1");

                if (arrResult[key]["STYLE_ID"] == styleID) {
                    strStyleID = styleID;
                }
            }
        }

        var tmpId =
            ".FrmLoginEdit.UcComboBox1 option[value='" + strStyleID + "']";
        $(tmpId).prop("selected", true);
    };

    // '**********************************************************************
    // 'パターンＩＤコンボボックスの項目に設定する
    // '**********************************************************************
    me.setPatternValues = function (arrResult, patternID) {
        $(".FrmLoginEdit.UcComboBox2").empty();
        var strPatternID = "";
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmLoginEdit.UcComboBox2");

        for (key in arrResult) {
            if (arrResult[key]["PATTERN_NM"] != "") {
                $("<option></option>")
                    .val(arrResult[key]["PATTERN_ID"])
                    .text(arrResult[key]["PATTERN_NM"])
                    .appendTo(".FrmLoginEdit.UcComboBox2");

                if (arrResult[key]["PATTERN_ID"] == patternID) {
                    strPatternID = patternID;
                }
            }
        }

        var tmpId =
            ".FrmLoginEdit.UcComboBox2 option[value='" + strPatternID + "']";
        $(tmpId).prop("selected", true);
    };

    me.fncInputChk = function () {
        /*	BUG_NO 2026
    //パスワードの必須ﾁｪｯｸ
    var intRetNo = me.clsComFnc.FncTextCheck($(".FrmLoginEdit.UcTextBox1"), 1, me.clsComFnc.INPUTTYPE.CHAR2, 10);

    if (intRetNo == -1)
    {
      me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox1");
      me.clsComFnc.FncMsgBox("E9999", "パスワードを入力して下さい。");
      return false;
    }
    else
    if (intRetNo == -3)
    {
      me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox1");
      me.clsComFnc.FncMsgBox("W0003", "パスワード");
      return false;
    }

    var intRetNo = me.clsComFnc.FncTextCheck($(".FrmLoginEdit.UcTextBox2"), 1, me.clsComFnc.INPUTTYPE.CHAR2, 10);

    if (intRetNo == -1)
    {
      me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
      me.clsComFnc.FncMsgBox("E9999", "パスワード確認を入力して下さい。");
      return false;
    }
    else
    if (intRetNo == -3)
    {
      me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
      me.clsComFnc.FncMsgBox("W0003", "パスワード確認");
      return false;
    }

    if ($(".FrmLoginEdit.UcTextBox1").val() != $(".FrmLoginEdit.UcTextBox2").val())
    {
      me.clsComFnc.ObjSelect = $(".FrmLoginEdit.UcTextBox2");
      me.clsComFnc.FncMsgBox("E9999", "パスワードとパスワード確認の内容が異なっております。");
      return false;
    }
    */
        if (
            $(".FrmLoginEdit.UcComboBox1 option:selected").val() == "" ||
            $(".FrmLoginEdit.UcComboBox2 option:selected").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcComboBox2");
            me.clsComFnc.FncMsgBox("E9999", "パターンＩＤを入力して下さい。");
            return false;
        }

        return true;
    };

    me.fncDeleteUpdataMst = function () {
        var url = me.id + "/fncDeleteUpdataMst";
        var sendData = {
            USER_ID: $(".FrmLoginEdit.Label8").val(),
            PASSWORD: $(".FrmLoginEdit.UcTextBox1").val(),
            REC_CRE_DT: me.REC_CRE_DT,
            STYLE_ID: $(".FrmLoginEdit.UcComboBox1 option:selected").val(),
            PATTERN_ID: $(".FrmLoginEdit.UcComboBox2 option:selected").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9998", result["data"]);
                return;
            } else {
                //閉じる
                $("#FrmLoginEditDialogDiv").dialog("close");
            }
        };
        me.ajax.send(url, sendData, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmLoginEdit = new R4.FrmLoginEdit();

    o_R4K_R4K.FrmLoginSel.FrmLoginEdit = o_R4_FrmLoginEdit;
    o_R4_FrmLoginEdit.FrmLoginSel = o_R4K_R4K.FrmLoginSel;

    o_R4_FrmLoginEdit.load();
});
