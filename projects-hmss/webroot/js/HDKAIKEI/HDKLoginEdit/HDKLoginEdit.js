Namespace.register("HDKAIKEI.HDKLoginEdit");

HDKAIKEI.HDKLoginEdit = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.sys_id = "HDKAIKEI";
    me.id = "HDKLoginEdit";
    me.Busyo_Array = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKLoginEdit.btnPtnUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKLoginEdit.btnPtnClear",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".HDKLoginEdit.SyainNoVal").on("blur", function () {
        if ($.trim($(".HDKLoginEdit.SyainNoVal").val()) != "") {
            me.SyainNoChanged();
        }
    });
    $(".HDKLoginEdit.BusyoCdVal").on("blur", function () {
        if ($.trim($(".HDKLoginEdit.BusyoCdVal").val()) != "") {
            var busyoCd = $(".HDKLoginEdit.BusyoCdVal").val();
            var busyoData = me.BusyoCdChanged(busyoCd);
            if (busyoData != "") {
                $(".HDKLoginEdit.BusyoNmVal").val(busyoData["BUSYO_NM"]);
            }
        } else {
            $(".HDKLoginEdit.BusyoNmVal").val("");
        }
    });
    //更新ボタンクリック
    $(".HDKLoginEdit.btnPtnUpdate").click(function () {
        me.fncUpdateClick();
    });
    //クリアボタンクリック
    $(".HDKLoginEdit.btnPtnClear").click(function () {
        me.fncClearClick();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 Page_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_load = function () {
        $(".HDKLoginEdit.SyainNoVal").trigger("focus");
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyoMstValue";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            me.Busyo_Array = result["data"];
        };
        me.ajax.send(url, "", 0);
    };
    //**********************************************************************
    //処 理 名：更新ボタンクリック
    //関    数：fncUpdateClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：更新ボタンクリック
    //**********************************************************************
    me.fncUpdateClick = function () {
        var syainNo = $(".HDKLoginEdit.SyainNoVal").val();
        if ($.trim(syainNo) == "") {
            me.clsComFnc.ObjFocus = $(".HDKLoginEdit.SyainNoVal");
            me.clsComFnc.FncMsgBox("W0017", "すべての項目");
            return;
        }
        if ($.trim($(".HDKLoginEdit.BusyoCdVal").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HDKLoginEdit.BusyoCdVal");
            me.clsComFnc.FncMsgBox("W0017", "すべての項目");
            return;
        }
        if ($.trim($(".HDKLoginEdit.SyainNmVal").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HDKLoginEdit.SyainNmVal");
            me.clsComFnc.FncMsgBox("W0017", "すべての項目");
            return;
        }
        if ($.trim($(".HDKLoginEdit.PassWordVal").val()) == "") {
            me.clsComFnc.ObjFocus = $(".HDKLoginEdit.PassWordVal");
            me.clsComFnc.FncMsgBox("W0017", "すべての項目");
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDataLogin;
        me.clsComFnc.FncMsgBox("QY010");
    };
    //**********************************************************************
    //処 理 名：クリアボタンクリック
    //関    数：fncClearClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：クリアボタンクリック
    //**********************************************************************
    me.fncClearClick = function () {
        $(".HDKLoginEdit.SyainNoVal").val("");
        $(".HDKLoginEdit.BusyoCdVal").val("");
        $(".HDKLoginEdit.BusyoNmVal").val("");
        $(".HDKLoginEdit.SyainNmVal").val("");
        $(".HDKLoginEdit.PassWordVal").val("");
        $(".HDKLoginEdit.SyainNoVal").trigger("focus");
    };
    //**********************************************************************
    //処 理 名：データを登録
    //関    数：fncDataLogin
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データを登録
    //**********************************************************************
    me.fncDataLogin = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncLoginBtnClick";
        var data = {
            SYAIN_NO: $(".HDKLoginEdit.SyainNoVal").val(),
            BUSYO_CD: $(".HDKLoginEdit.BusyoCdVal").val(),
            SYAIN_NM: $(".HDKLoginEdit.SyainNmVal").val(),
            PASSWORD: $(".HDKLoginEdit.PassWordVal").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.clsComFnc.FncMsgBox("I0012");
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：ユーザーID変更
    //関    数：SyainNoChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ユーザーID変更
    //**********************************************************************
    me.SyainNoChanged = function () {
        var syainNo = $(".HDKLoginEdit.SyainNoVal").val();
        var url = me.sys_id + "/" + me.id + "/" + "fncSyainNoChanged";
        var data = {
            SYAIN_NO: syainNo,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (result["row"] == 0) {
                $(".HDKLoginEdit.BusyoCdVal").val("");
                $(".HDKLoginEdit.BusyoNmVal").val("");
                $(".HDKLoginEdit.SyainNmVal").val("");
                $(".HDKLoginEdit.PassWordVal").val("");
            } else {
                $(".HDKLoginEdit.BusyoCdVal").val(
                    result["data"][0]["BUSYO_CD"]
                );
                $(".HDKLoginEdit.SyainNmVal").val(
                    result["data"][0]["SYAIN_NM"]
                );
                $(".HDKLoginEdit.PassWordVal").val(
                    result["data"][0]["PASSWORD"]
                );
                if (
                    result["data"][0]["BUSYO_CD"] != "" &&
                    result["data"][0]["BUSYO_CD"] != null
                ) {
                    var busyoData = me.BusyoCdChanged(
                        result["data"][0]["BUSYO_CD"]
                    );
                    if (busyoData != "") {
                        $(".HDKLoginEdit.BusyoNmVal").val(
                            busyoData["BUSYO_NM"]
                        );
                    }
                } else {
                    $(".HDKLoginEdit.BusyoNmVal").val("");
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：所属変更
    //関    数：BusyoCdChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：所属変更
    //**********************************************************************
    me.BusyoCdChanged = function (busyoCd) {
        for (var key in me.Busyo_Array) {
            if (me.Busyo_Array[key]["BUSYO_CD"] === busyoCd) {
                return me.Busyo_Array[key];
            }
        }
        me.clsComFnc.ObjFocus = $(".HDKLoginEdit.BusyoCdVal");
        me.clsComFnc.FncMsgBox("W9999", "部署コードが不正です");
        return "";
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HDKLoginEdit_HDKLoginEdit = new HDKAIKEI.HDKLoginEdit();
    o_HDKLoginEdit_HDKLoginEdit.load();
});
