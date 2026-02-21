/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmBillSitoInput");

R4.FrmBillSitoInput = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmBillSitoInput";
    me.sys_id = "R4G";
    me.click = false;
    me.validate = false;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmBillSitoInput.btnAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmBillSitoInput.btnDelete",
        type: "button",
        handle: "",
        enable: "false",
    });
    me.controls.push({
        id: ".FrmBillSitoInput.btnBack",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmBillSitoInput.txtCMN_NO").trigger("focus");

    //画面をクリア
    subFormClear();

    $(".FrmBillSitoInput.txtCMN_NO").on("blur", function () {
        if (0 < $('div[id^="MsgBox_"]').length) {
            return false;
        }
        if ($(".FrmBillSitoInput.txtCMN_NO").val() != "") {
            me.validate = true;

            if (fncCMNNOChk() == false) {
                me.validate = false;
                return;
            } else {
                txtCMNNo_Validating();
            }
        }
    });

    $("#btnAction").on("click", function () {
        me.click = true;

        if (me.validate == false) {
            if (fncInputChk() == false) {
                me.click = false;
                return;
            }

            //確認メッセージ
            clsComFnc.MsgBoxBtnFnc.Yes = function () {
                btnAction_click();
            };
            clsComFnc.MsgBoxBtnFnc.No = function () {
                me.click = false;
            };
            clsComFnc.FncMsgBox("QY010");
        }
    });

    $(".FrmBillSitoInput.btnDelete").click(function () {
        /* 2013/10/27 YuanQuan 既存バグ修正 Start */
        clsComFnc.MsgBoxBtnFnc.Yes = function () {
            btnDelete_Click();
        };
        /* 2013/10/27 YuanQuan 既存バグ修正 End */
        clsComFnc.MessageBox(
            "削除してもよろしいですか？",
            "確認",
            "YesNo",
            "Question"
        );
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /**********************************************************************
     処 理 名：			更新ボタンクリック
     関 数 名：			cmdAction_Click
     引    数：			無し
     戻 り 値：			無し
     処理説明：	更新ボタン押下時処理
     **********************************************************************/

    function btnAction_click() {
        var ajaxUrl = me.sys_id + "/" + me.id + "/fnc" + me.id + "Action";

        var data_array = {
            txtCMN_NO: $(".FrmBillSitoInput.txtCMN_NO").val(),
            txtBillSito: $(".FrmBillSitoInput.txtBillSito").val(),
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["flag"] == "true" && result["msg"] == "true") {
                clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    //画面をクリア
                    subFormClear();
                    $(".FrmBillSitoInput.btnDelete").button("disable");
                };
                clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
                //正常終了ﾒｯｾｰｼﾞ
                clsComFnc.FncMsgBox("I0008");
            } else {
                var err_code = result["msg"]["error_code"];
                var err_msg = result["msg"]["message"];

                clsComFnc.FncMsgBox(err_code, err_msg);
            }
            me.click = false;
        };

        ajax.send(ajaxUrl, data_array, 0);

        // $.ajax({
        // url : ajaxUrl,
        // type : "post",
        // data : {
        // "request" : data_array
        // },
        // success : function(result) {
        // result = $.parseJSON(result);
        // if (result['flag'] == 'true' && result['msg'] == 'true') {
        // clsComFnc.MsgBoxBtnFnc.Yes = function() {
        // //画面をクリア
        // subFormClear();
        // $(".FrmBillSitoInput.btnDelete").button("disable");
        // };
        // clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
        // //正常終了ﾒｯｾｰｼﾞ
        // clsComFnc.FncMsgBox('I0008');
        // } else {
        // var err_code = result['msg']['error_code'];
        // var err_msg = result['msg']['message'];
        //
        // clsComFnc.FncMsgBox(err_code, err_msg);
        // }
        // me.click = false;
        // }
        // });
    }

    $(".FrmBillSitoInput.txtBillSito").numeric(
        {
            decimal: false,
            negative: false,
        },
        function () {}
    );

    /**********************************************************************
     処 理 名：削除ボタンクリック
     関 数 名：btnDelete_Click
     引    数：無し
     戻 り 値：無し
     処理説明：削除ボタン押下時処理
     **********************************************************************/

    function btnDelete_Click() {
        var ajaxUrl = me.sys_id + "/" + me.id + "/fnc" + me.id + "Delete";

        var data_array = {
            txtCMN_NO: $(".FrmBillSitoInput.txtCMN_NO").val(),
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["flag"] == "true") {
                if (result["msg"] == "true") {
                    //画面をクリア
                    subFormClear();
                    $(".FrmBillSitoInput.btnDelete").button("disable");
                    $(".FrmBillSitoInput.txtCMN_NO").trigger("focus");
                }
            } else {
                var err_msg = result["msg"];
                clsComFnc.MessageBox(err_msg, "システム", "OK");
            }
        };

        ajax.send(ajaxUrl, data_array, 0);

        // $.ajax({
        // url : ajaxUrl,
        // type : "post",
        // data : {
        // "request" : data_array
        // },
        // success : function(result) {
        // result = $.parseJSON(result);
        // if (result['flag'] == 'true') {
        // if (result['msg'] == 'true') {
        // //画面をクリア
        // subFormClear();
        // $(".FrmBillSitoInput.btnDelete").button("disable");
        // $(".FrmBillSitoInput.txtCMN_NO").trigger("focus");
        // }
        // } else {
        // var err_msg = result['msg'];
        // clsComFnc.MessageBox(err_msg, 'システム', 'OK');
        // }
        // }
        // });
    }

    /**********************************************************************
     処 理 名：	ﾌｫｰｶｽ移動
     関 数 名：txtCMNNo_Validating
     引    数：無し
     戻 り 値：	無し
     処理説明：ﾌｫｰｶｽ移動
     **********************************************************************/

    function txtCMNNo_Validating() {
        //入力されている場合
        if (clsComFnc.FncNv($(".FrmBillSitoInput.txtCMN_NO").val()) != "") {
            var ajaxUrl =
                me.sys_id + "/" + me.id + "/fnc" + me.id + "Validating";

            var data_array = {
                txtCMN_NO: $(".FrmBillSitoInput.txtCMN_NO").val(),
            };

            ajax.receive = function (result) {
                result = $.parseJSON(result);

                if (result["flag"] == "true") {
                    if (result["msg"] == "true") {
                        for (key in result["data"]) {
                            if (key.match("Kaptes")) {
                                $(".FrmBillSitoInput." + key).val(
                                    clsComFnc.FncNz(result["data"][key])
                                );
                            } else {
                                $(".FrmBillSitoInput." + key).val(
                                    clsComFnc.FncNv(result["data"][key])
                                );
                            }
                        }

                        if (
                            clsComFnc.FncNv(result["data"]["txtBillSito"]) == ""
                        ) {
                            $(".FrmBillSitoInput.txtBillSito").trigger("focus");
                            $(".FrmBillSitoInput.btnDelete").button("disable");
                            $(".FrmBillSitoInput.btnDelete").prop(
                                "tabindex",
                                "0"
                            );
                        } else {
                            $(".FrmBillSitoInput.txtBillSito").trigger("focus");
                            $(".FrmBillSitoInput.btnDelete").button("enable");
                            $(".FrmBillSitoInput.btnDelete").prop(
                                "tabindex",
                                "4"
                            );
                        }
                    } else {
                        var err_code = result["msg"]["error_code"];
                        var err_msg = result["msg"]["message"];
                        clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
                        clsComFnc.FncMsgBox(err_code, err_msg);
                    }
                } else {
                    var err_code = result["msg"]["error_code"];
                    var err_msg = result["msg"]["message"];
                    clsComFnc.FncMsgBox(err_code, err_msg);
                }
                me.validate = false;

                if (me.click == true) {
                    $("#btnAction").trigger("click");
                }
            };

            ajax.send(ajaxUrl, data_array, 0);

            // $.ajax({
            // url : ajaxUrl,
            // type : "post",
            // data : {
            // "request" : data_array
            // },
            // async : false,
            // success : function(result) {
            // result = $.parseJSON(result);
            //
            // if (result['flag'] == 'true') {
            // if (result['msg'] == 'true') {
            //
            // for (key in result['data']) {
            // if (key.match('Kaptes')) {
            // $('.FrmBillSitoInput.' + key).val(clsComFnc.FncNz(result['data'][key]));
            // } else {
            // $('.FrmBillSitoInput.' + key).val(clsComFnc.FncNv(result['data'][key]));
            // }
            // }
            //
            // if (clsComFnc.FncNv(result['data']['txtBillSito']) == "") {
            // $(".FrmBillSitoInput.txtBillSito").trigger("focus");
            // $(".FrmBillSitoInput.btnDelete").button("disable");
            // $(".FrmBillSitoInput.btnDelete").prop("tabindex", "0");
            // } else {
            // $(".FrmBillSitoInput.txtBillSito").trigger("focus");
            // $(".FrmBillSitoInput.btnDelete").button("enable");
            // $(".FrmBillSitoInput.btnDelete").prop("tabindex", "4");
            // }
            // } else {
            // var err_code = result['msg']['error_code'];
            // var err_msg = result['msg']['message'];
            // clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
            // clsComFnc.FncMsgBox(err_code, err_msg);
            // }
            // } else {
            // var err_code = result['msg']['error_code'];
            // var err_msg = result['msg']['message'];
            // clsComFnc.FncMsgBox(err_code, err_msg);
            // }
            // me.validate = false;
            // if (me.click == true) {
            // $("#btnAction").trigger('click');
            // }
            // }
            // });
        }
    }

    function subFormClear() {
        $(".FrmBillSitoInput.txtCMN_NO").val("");
        $(".FrmBillSitoInput.txtUCNO").val("");
        $(".FrmBillSitoInput.txtKeiyakusya").val("");
        $(".FrmBillSitoInput.txtSiyosya").val("");
        $(".FrmBillSitoInput.txtSiyosyaKN").val("");
        $(".FrmBillSitoInput.txtKaptes").val("");
        $(".FrmBillSitoInput.txtBillSito").val("");
    }

    function fncInputChk() {
        intRtnCD = clsComFnc.FncTextCheck(
            $(".FrmBillSitoInput.txtCMN_NO"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        //alert(intRtnCD);
        switch (intRtnCD) {
            case -1:
                clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
                clsComFnc.FncMsgBox(
                    "W000" + (intRtnCD * -1).toString(),
                    "注文書番号"
                );
                return false;
        }

        if ($(".FrmBillSitoInput.txtKaptes").val() == "0") {
            clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
            clsComFnc.FncMsgBox("I9999", "割賦元金が０です");
            return false;
        } else {
            if (
                clsComFnc.FncNz($(".FrmBillSitoInput.txtBillSito").val()) == "0"
            ) {
                clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtBillSito");
                clsComFnc.FncMsgBox("I9999", "据置日数が入力されていません");
                return false;
            }
        }
        //正常終了
        return true;
    }

    function fncCMNNOChk() {
        intRtnCD = clsComFnc.FncTextCheck(
            $(".FrmBillSitoInput.txtCMN_NO"),
            1,
            clsComFnc.INPUTTYPE.CHAR2
        );
        switch (intRtnCD) {
            // case -1:
            case -2:
            case -3:
                clsComFnc.ObjFocus = $(".FrmBillSitoInput.txtCMN_NO");
                clsComFnc.FncMsgBox(
                    "W000" + (intRtnCD * -1).toString(),
                    "注文書番号"
                );
                return false;
        }
    }

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmBillSitoInput = new R4.FrmBillSitoInput();
    o_R4_FrmBillSitoInput.load();
});
