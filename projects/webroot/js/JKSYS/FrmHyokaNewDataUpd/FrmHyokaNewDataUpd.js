// 評価・賞与最新データ更新
Namespace.register("JKSYS.FrmHyokaNewDataUpd");

JKSYS.FrmHyokaNewDataUpd = function () {
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.sys_id = "JKSYS";
    me.id = "FrmHyokaNewDataUpd";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmHyokaNewDataUpd.cmdUpdate",
        type: "button",
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

    // ==========
    // = イベント start =
    // ==========

    //更新ボタンクリック
    $(".FrmHyokaNewDataUpd.cmdUpdate").click(function () {
        me.btnUpdate_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    /*
     '**********************************************************************
     '処 理 名：更新ボタンクリック
     '関 数 名：btnUpdate_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnUpdate_Click = function () {
        try {
            //確認メッセージ表示
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.UpdateAction;
            me.clsComFnc.FncMsgBox("QY005");
        } catch (ex) {
            me.clsComFnc.FncMsgBox("E9999", ex);
        }
    };

    me.UpdateAction = function () {
        me.url = me.sys_id + "/" + me.id + "/UpdateAction";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //メッセージ表示
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.clsComFnc.FncMsgBox("I0005");
            }
        };

        me.ajax.send(me.url, "", 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmHyokaNewDataUpd = new JKSYS.FrmHyokaNewDataUpd();
    o_JKSYS_FrmHyokaNewDataUpd.load();
});
