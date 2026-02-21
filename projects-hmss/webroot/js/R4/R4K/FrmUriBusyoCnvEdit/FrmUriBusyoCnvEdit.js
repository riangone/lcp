/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmUriBusyoCnvEdit");

R4.FrmUriBusyoCnvEdit = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    me.id = "FrmUriBusyoCnvEdit";
    me.sys_id = "R4K";
    me.iMode = "";
    me.strCMNNO = "";
    me.strINPUTCMN = "";
    me.validatingArr = {
        current: "",
        before: "",
    };
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmUriBusyoCnvEdit.cmdUpdate.Enter",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUriBusyoCnvEdit.cmdBack.Enter",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.iMode = me.FrmUriBusyoCnv.Mode;
        me.strCMNNO = me.FrmUriBusyoCnv.CMNNO;
        me.frmUriBusyoCnvEdit_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").on("focus", function () {
        me.fncValidating();
    });
    $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").on("focus", function () {
        me.fncValidating();
    });
    $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").keydown(function (event) {
        switch (event.keyCode) {
            case 13:
                me.txtCMNNO2validting();
                break;
            case 9:
                if (event.keyCode == 9 && event.shiftKey) {
                    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger("focus");
                    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").select();
                    break;
                } else {
                    me.txtCMNNO2validting();
                    $(".FrmUriBusyoCnvEdit.cmdUpdate.Enter").trigger("focus");
                }
                break;
        }
    });
    //更新ボタン押下時
    $(".FrmUriBusyoCnvEdit.cmdUpdate.Enter").click(function () {
        me.fncValidating();
    });

    //戻るボタン押下時
    $(".FrmUriBusyoCnvEdit.cmdBack.Enter").click(function () {
        $("#FrmUriBusyoCnvEdit").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmUriBusyoCnvEdit_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmUriBusyoCnvEdit_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();
        //修正の場合
        if (me.iMode == 2) {
            //引き渡された注文書番号
            me.strINPUTCMN = me.strCMNNO;
            //注文書番号
            $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val(me.strCMNNO);
            $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").attr("disabled", true);
            //画面のデータの値を設定
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["result"] == true) {
                    if (result["row"] != 0) {
                        me.fncFromDataShow(result["data"][0]);
                        $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").trigger(
                            "focus"
                        );
                    } else {
                        return;
                    }
                }
            };
            var url = me.sys_id + "/" + me.id + "/" + "fncDataSel";
            var data = me.strINPUTCMN;
            me.ajax.send(url, data, 0);
        } else {
            $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val("");
            $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger("focus");
        }
    };
    //**********************************************************************
    //処 理 名：画面項目をｸﾘｱする
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目をｸﾘｱする
    //**********************************************************************
    me.subClearForm = function () {
        $(".FrmUriBusyoCnvEdit.lblSYAINNO").html("");
        $(".FrmUriBusyoCnvEdit.lblSYAINNM").html("");
        $(".FrmUriBusyoCnvEdit.lblKEIYAKUNM").html("");
        $(".FrmUriBusyoCnvEdit.lblBUSYONO").html("");
        $(".FrmUriBusyoCnvEdit.lblBUSYONM").html("");
        $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").val("");
        $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html("");
        $(".FrmUriBusyoCnvEdit.lblCreateDate").html("");
    };
    //**********************************************************************
    //処 理 名：画面データの再表示
    //関 数 名：fromDataShow
    //処理説明：画面データを表示する
    //**********************************************************************
    me.fncFromDataShow = function (objDr) {
        //社員番号
        $(".FrmUriBusyoCnvEdit.lblSYAINNO").html(
            me.clsComFnc.FncNv(objDr["SYAIN_NO"])
        );
        $(".FrmUriBusyoCnvEdit.lblSYAINNM").html(
            me.clsComFnc.FncNv(objDr["SYAIN_NM"])
        );
        //契約者名称
        $(".FrmUriBusyoCnvEdit.lblKEIYAKUNM").html(
            me.clsComFnc.FncNv(objDr["MGN_MEI_KNJ1"])
        );
        //部署
        $(".FrmUriBusyoCnvEdit.lblBUSYONO").html(
            me.clsComFnc.FncNv(objDr["BU_BUSYO_CD"])
        );
        $(".FrmUriBusyoCnvEdit.lblBUSYONM").html(
            me.clsComFnc.FncNv(objDr["BU_BUSYO_NM"])
        );
        //変更後部署
        $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").val(
            me.clsComFnc.FncNv(objDr["HEN_BUSYO_CD"])
        );
        $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html(
            me.clsComFnc.FncNv(objDr["HEN_BUSYO_NM"])
        );
        //作成日付
        $(".FrmUriBusyoCnvEdit.lblCreateDate").html(
            me.clsComFnc.FncNv(objDr["CREATE_DATE"])
        );
    };

    me.cmdUpdate = function () {
        //変更後部署
        var intRtn = me.clsComFnc.FncTextCheck(
            $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter"),
            1,
            me.clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn < 0) {
            $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").trigger("focus");
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fnc_txtCMNNO2_select;
            me.clsComFnc.FncMsgBox("W000" + intRtn * -1, "変更後部署");
            $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").css(
                "background-color",
                me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
            );
            return;
        }
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                if (result["data"]["intRtnCD"] == -1) {
                    $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").trigger("focus");
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.fnc_txtCMNNO2_select;
                    me.clsComFnc.FncMsgBox("W0008", "変更後部署");
                    $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").css(
                        "background-color",
                        me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                    );
                    return;
                }
                //名称取得
                $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html(
                    result["data"]["strBusyoNM"]
                );
                $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                //確認ﾒｯｾｰｼﾞ表示
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteInsertHuri;
                //make sure not do cmdUpdate_Click again,when click yes
                $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger("focus");
                me.clsComFnc.FncMsgBox("QY010");
                return;
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoMstValue";
        var data = $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").val().trimEnd();
        me.ajax.send(url, data, 0);
    };

    me.fncDeleteInsertHuri = function () {
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
            if (result["result"] == true) {
                if (me.iMode == 1) {
                    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val("");
                    me.subClearForm();
                    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger("focus");
                }
                if (me.iMode == 2) {
                    $("#FrmUriBusyoCnvEdit").dialog("close");
                }
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncDeleteInsertHuri";
        var data = {
            txtCMNNO: $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val().trimEnd(),
            txtCMNNO2: $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").val().trimEnd(),
            lblCreateDate: $(".FrmUriBusyoCnvEdit.lblCreateDate")
                .html()
                .trimEnd(),
        };
        me.ajax.send(url, data, 0);
    };

    //*****************make error info selected start***********
    me.fnc_txtCMNNO1_select = function () {
        $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").select();
    };

    me.fnc_txtCMNNO2_select = function () {
        $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").select();
    };
    //*****************make error info selected end**************

    me.fncValidating = function () {
        var tmpA = document.activeElement;
        me.validatingArr["before"] = me.validatingArr["current"];
        me.validatingArr["current"] = tmpA;

        if (me.validatingArr["before"] && me.validatingArr["current"]) {
            if (
                me.validatingArr["before"].className !=
                me.validatingArr["current"].className
            ) {
                if (
                    me.validatingArr["before"].className.indexOf("txtCMNNO1") >
                    0
                ) {
                    //引き渡された注文書番号
                    me.strINPUTCMN = $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter")
                        .val()
                        .trimEnd()
                        .toUpperCase();
                    $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val(
                        me.strINPUTCMN
                    );
                    //画面項目ｸﾘｱ
                    me.subClearForm();
                    //画面のデータの値を設定
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] == false) {
                            me.clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                        if (result["result"] == true) {
                            if (result["row"] != 0) {
                                me.fncFromDataShow(result["data"][0]);
                            }
                        }
                        $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").css(
                            "background-color",
                            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                        );
                        //click button
                        me.fnc_button_Click_validating();
                    };
                    var url = me.sys_id + "/" + me.id + "/" + "fncDataSel";
                    var data = me.strINPUTCMN;
                    me.ajax.send(url, data, 0);
                } else {
                    if (
                        me.validatingArr["before"].className.indexOf(
                            "txtCMNNO2"
                        ) > 0
                    ) {
                        $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html("");
                        //画面のデータの値を設定
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (result["result"] == false) {
                                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                                return;
                            }
                            if (result["result"] == true) {
                                //名称取得
                                $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html(
                                    result["data"]["strBusyoNM"]
                                );
                            }
                            $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").css(
                                "background-color",
                                me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                            );
                            //click button
                            me.fnc_button_Click_validating();
                        };
                        var url =
                            me.sys_id +
                            "/" +
                            me.id +
                            "/" +
                            "FncGetBusyoMstValue";
                        var data = $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter")
                            .val()
                            .trimEnd();
                        me.ajax.send(url, data, 0);
                    } else {
                        //click button
                        me.fnc_button_Click_validating();
                    }
                }
            } else {
                //click button
                me.fnc_button_Click_validating();
            }
        } else {
            //click button
            me.fnc_button_Click_validating();
        }
    };

    me.fnc_button_Click_validating = function () {
        if (me.validatingArr["current"].className.indexOf("cmdUpdate") > 0) {
            me.cmdUpdate_Click();
        }
    };

    me.cmdUpdate_Click = function () {
        if (me.iMode == 1) {
            //注文書番号
            var intRtn = me.clsComFnc.FncTextCheck(
                $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter"),
                1,
                me.clsComFnc.INPUTTYPE.CHAR2
            );

            if (intRtn < 0) {
                $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger("focus");
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fnc_txtCMNNO1_select;
                me.clsComFnc.FncMsgBox("W000" + intRtn * -1, "注文書番号");
                $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                return;
            }
            //注文書番号存在チェック
            var url = me.sys_id + "/" + me.id + "/" + "fncCheckCMNNO";
            var data = $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").val().trimEnd();
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == true) {
                    intRtn = result["data"];
                    if (intRtn > 0) {
                        $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").trigger(
                            "focus"
                        );

                        if (intRtn == 1) {
                            me.clsComFnc.MsgBoxBtnFnc.Yes =
                                me.fnc_txtCMNNO1_select;
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "入力された注文書番号は売上データに存在しません。"
                            );
                        }
                        if (intRtn == 2) {
                            me.clsComFnc.MsgBoxBtnFnc.Yes =
                                me.fnc_txtCMNNO1_select;
                            me.clsComFnc.FncMsgBox(
                                "W9999",
                                "入力された注文書番号は既に変換テーブルに存在しています。"
                            );
                        }

                        $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").css(
                            "background-color",
                            me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                        );

                        return;
                    }
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                $(".FrmUriBusyoCnvEdit.txtCMNNO1.Enter").css(
                    "background-color",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                me.cmdUpdate();
            };
            me.ajax.send(url, data, 0);
        }
        if (me.iMode == 2) {
            me.cmdUpdate();
        }
    };

    me.txtCMNNO2validting = function () {
        $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html("");
        //画面のデータの値を設定
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                //名称取得
                $(".FrmUriBusyoCnvEdit.lblCMNO2NM").html(
                    result["data"]["strBusyoNM"]
                );
            }
            $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").css(
                "background-color",
                me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
            );
        };
        var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoMstValue";
        var data = $(".FrmUriBusyoCnvEdit.txtCMNNO2.Enter").val().trimEnd();
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmUriBusyoCnvEdit = new R4.FrmUriBusyoCnvEdit();
    o_R4_FrmUriBusyoCnvEdit.FrmUriBusyoCnv = o_R4K_R4K_FrmUriBusyoCnv;
    o_R4K_R4K_FrmUriBusyoCnv.FrmUriBusyoCnvEdit = o_R4_FrmUriBusyoCnvEdit;
    o_R4_FrmUriBusyoCnvEdit.load();
});
