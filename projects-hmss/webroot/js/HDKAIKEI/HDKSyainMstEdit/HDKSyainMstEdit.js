Namespace.register("HDKAIKEI.HDKSyainMstEdit");

HDKAIKEI.HDKSyainMstEdit = function () {
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
    me.id = "HDKSyainMstEdit";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKSyainMstEdit.LoginBtn",
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
    //登録ボタンクリック
    $(".HDKSyainMstEdit.LoginBtn").click(function () {
        me.fncLoginBtnClick();
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
        $(".HDKSyainMstEdit.BusyoCdVal").focus();
    };
    //**********************************************************************
    //処 理 名：登録ボタンクリック
    //関    数：fncLoginBtnClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：登録ボタンクリック
    //**********************************************************************
    me.fncLoginBtnClick = function () {
        var syainNo = $(".HDKSyainMstEdit.SyainNoVal").val();
        if ($.trim(syainNo) == "") {
            me.clsComFnc.ObjFocus = $(".HDKSyainMstEdit.SyainNoVal");
            me.clsComFnc.FncMsgBox("W0017", "社員番号");
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDataLogin;
        me.clsComFnc.FncMsgBox("QY010");
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
            BUSYO_CD: $(".HDKSyainMstEdit.BusyoCdVal").val(),
            SYAIN_NO: $(".HDKSyainMstEdit.SyainNoVal").val(),
            SYAIN_NM: $(".HDKSyainMstEdit.SyainNmVal").val(),
            SYAIN_KN: $(".HDKSyainMstEdit.SyainKnVal").val(),
            PASSWORD: $(".HDKSyainMstEdit.PassWordVal").val(),
            PATTERN_ID: $(".HDKSyainMstEdit.PatternIdSel").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.clsComFnc.FncMsgBox("I0012");
            $(".HDKSyainMstEdit.BusyoCdVal").val("");
            $(".HDKSyainMstEdit.SyainNoVal").val("");
            $(".HDKSyainMstEdit.SyainNmVal").val("");
            $(".HDKSyainMstEdit.SyainKnVal").val("");
            $(".HDKSyainMstEdit.PassWordVal").val("");
            $(".HDKSyainMstEdit.PatternIdSel").val("000");
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HDKSyainMstEdit_HDKSyainMstEdit = new HDKAIKEI.HDKSyainMstEdit();
    o_HDKSyainMstEdit_HDKSyainMstEdit.load();
});
