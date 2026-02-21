Namespace.register("R4.FrmSnapShotBat");

R4.FrmSnapShotBat = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    me.id = "R4K/FrmSnapShotBat";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSnapShotBat.cmdAction",
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

    //実行ボタンクリック
    $(".FrmSnapShotBat.cmdAction").click(function () {
        var url = me.id + "/fncActionClick";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0005");
            }
        };
        me.ajax.send(url, "", 0);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //初期処理
    var base_load = me.load;

    me.load = function () {
        base_load();

        $(".FrmSnapShotBat.cmdAction").focus();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSnapShotBat = new R4.FrmSnapShotBat();
    o_R4_FrmSnapShotBat.load();
});
