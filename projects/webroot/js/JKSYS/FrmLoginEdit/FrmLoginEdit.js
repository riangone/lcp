Namespace.register("JKSYS.FrmLoginEdit");

JKSYS.FrmLoginEdit = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "JKSYS/FrmJKSYSLoginEdit";
    me.strTougetu = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmLoginEdit.Button3",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginEdit.Button2",
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
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '登録イベント
    // '**********************************************************************
    $(".FrmLoginEdit.Button3").click(function () {
        //入力チェックを行う。
        if (!me.Button3_Click()) {
            return;
        }
        //登録確認ﾒｯｾｰｼﾞを表示する
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMst;
        me.clsComFnc.FncMsgBox("QY010");
    });

    //戻るボタン押下
    $(".FrmLoginEdit.Button2").click(function () {
        me.btnBack_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    var base_load = me.init_control;
    me.init_control = function () {
        base_load();

        $(".FrmLoginEdit.body").dialog({
            autoOpen: false,
            width: 500,
            height: me.ratio === 1.5 ? 205 : 275,
            modal: true,
            title: "ログイン情報登録",
            open: function () {
                var localStorage = window.localStorage;
                var requestdata = JSON.parse(
                    localStorage.getItem("requestdata")
                );
                me.before_close = function () {};
                if (requestdata) {
                    me.UserID = requestdata["UserID"];
                    me.cboSysKB = requestdata["cboSysKB"];

                    me.frmLoginEdit_Load();
                }
                localStorage.removeItem("requestdata");
            },
            close: function () {
                me.before_close();
                $(".FrmLoginEdit.body").remove();
            },
        });
        $(".FrmLoginEdit.body").dialog("open");
    };
    me.frmLoginEdit_Load = function () {
        //システム区分追加
        var SysKbText = ["人事給与システム"];
        var SysKbValue = ["6"];

        //コントロールマスタ存在ﾁｪｯｸ
        var url = me.id + "/fncLoadDeal";
        var data = {
            UserID: me.UserID,
            cboSysKB: SysKbValue[0],
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                //コントロールマスタが存在していない場合
                $("<option></option>").appendTo(".FrmLoginEdit.cboSysKB");
                $(".FrmLoginEdit.Button3").button("disable");
                if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox(result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }

            //コンボボックスに当月年月を設定
            me.strTougetu = me.clsComFnc.FncNv(result["data"]["strTougetu"]);

            //システム区分追加
            for (var i = 0; i < SysKbText.length; i++) {
                $("<option></option>")
                    .val(SysKbValue[i])
                    .text(SysKbText[i])
                    .appendTo(".FrmLoginEdit.cboSysKB");
            }
            $(".FrmLoginEdit.cboSysKB").attr("disabled", "disabled");

            var patternID = "";
            if (result["data"]["pattern"]) {
                $(".FrmLoginEdit.Label8").val(
                    me.chgToString(result["data"]["pattern"]["SYAIN_NO"])
                );
                $(".FrmLoginEdit.Label7").val(
                    me.chgToString(result["data"]["pattern"]["SYAIN_NM"])
                );
                $(".FrmLoginEdit.UcTextBox1").val(
                    me.chgToString(result["data"]["pattern"]["PASSWORD"])
                );
                $(".FrmLoginEdit.UcTextBox2").val(
                    me.chgToString(result["data"]["pattern"]["PASSWORD"])
                );
                $(".FrmLoginEdit.Label9").val(
                    me.clsComFnc.FncNz(result["data"]["pattern"]["REC_CRE_DT"])
                );

                //パターンＩＤコンボボックスの項目に設定する
                patternID = me.chgToString(
                    result["data"]["pattern"]["PATTERN_ID"]
                );
            }

            //パターンＩＤコンボボックスの項目に設定する
            if (result["data"]["arrCombox"]) {
                $(".FrmLoginEdit.UcComboBox2").append("<option></option>");
                for (var i = 0; i < result["data"]["arrCombox"].length; i++) {
                    $(".FrmLoginEdit.UcComboBox2").append(
                        "<option value='" +
                            result["data"]["arrCombox"][i]["PATTERN_ID"] +
                            "'>" +
                            result["data"]["arrCombox"][i]["PATTERN_NM"] +
                            "</option>"
                    );
                }

                if (patternID) {
                    $(".FrmLoginEdit.UcComboBox2").val(patternID);
                }
            }

            $(".FrmLoginEdit.UcTextBox1").select();
        };
        me.ajax.send(url, data, 0);
    };

    me.Button3_Click = function () {
        //パスワードの必須ﾁｪｯｸ
        var intRetNo = me.clsComFnc.FncTextCheck(
            $(".FrmLoginEdit.UcTextBox1"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2,
            10
        );
        if (intRetNo < 0) {
            switch (intRetNo) {
                case -1:
                    //必須エラー
                    me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox1");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "パスワードを入力して下さい"
                    );
                    return false;
                case -3:
                    //桁数エラー
                    me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox1");
                    me.clsComFnc.FncMsgBox("W0003", "パスワード");
                    return false;
            }

            $(".FrmLoginEdit.UcTextBox1").trigger("focus");
            return false;
        }

        //パスワード確認チェック
        intRetNo = me.clsComFnc.FncTextCheck(
            $(".FrmLoginEdit.UcTextBox2"),
            1,
            me.clsComFnc.INPUTTYPE.CHAR2,
            10
        );
        if (intRetNo < 0) {
            switch (intRetNo) {
                case -1:
                    //必須エラー
                    me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "パスワード確認を入力して下さい"
                    );
                    return false;
                case -3:
                    //桁数エラー
                    me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
                    me.clsComFnc.FncMsgBox("W0003", "パスワード確認");
                    return false;
            }

            $(".FrmLoginEdit.UcTextBox2").trigger("focus");
            return false;
        }

        var UcTextBox1 = $(".FrmLoginEdit.UcTextBox1").val();
        var UcTextBox2 = $(".FrmLoginEdit.UcTextBox2").val();
        //整合性チェック
        if ($.trim(UcTextBox1) != $.trim(UcTextBox2)) {
            me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
            me.clsComFnc.FncMsgBox(
                "E9999",
                "パスワードとパスワード確認の内容が異なっております。"
            );
            return false;
        }
        if ($.trim(UcTextBox1) == "" && $.trim(UcTextBox2) == "") {
            me.clsComFnc.ObjFocus = $(".FrmLoginEdit.UcTextBox2");
            me.clsComFnc.FncMsgBox(
                "E9999",
                "パスワードとパスワード確認の内容が異なっております。"
            );
            return false;
        }

        return true;
    };

    me.fncDeleteUpdataMst = function () {
        var url = me.id + "/fncDeleteUpdataMst";
        var sendData = {
            USER_ID: $(".FrmLoginEdit.Label8").val(),
            PASSWORD: $(".FrmLoginEdit.UcTextBox1").val(),
            REC_CRE_DT: $(".FrmLoginEdit.Label9").val(),
            PATTERN_ID: $(".FrmLoginEdit.UcComboBox2").val(),
            cboSysKB: $(".FrmLoginEdit.cboSysKB").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9998", result["error"]);
                return;
            } else {
                o_JKSYS_JKSYS.FrmLoginSel.refreshFlg = true;
                //閉じる
                $(".FrmLoginEdit.body").dialog("close");
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    // '**********************************************************************
    // '閉めます
    // '**********************************************************************
    me.btnBack_Click = function () {
        $(".FrmLoginEdit.body").dialog("close");
    };

    // '**********************************************************************
    // '関数、もしNOTHINGかDBNULLの時、空を返します
    // '**********************************************************************
    me.chgToString = function (obj) {
        var restr = "";
        if (!obj) {
            return restr;
        }
        return obj;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};
$(function () {
    var o_JKSYS_FrmLoginEdit = new JKSYS.FrmLoginEdit();

    o_JKSYS_JKSYS.FrmLoginSel.FrmLoginEdit = o_JKSYS_FrmLoginEdit;
    o_JKSYS_FrmLoginEdit.FrmLoginSel = o_JKSYS_JKSYS.FrmLoginSel;

    o_JKSYS_FrmLoginEdit.load();
});
