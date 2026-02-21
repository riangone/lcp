/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmShimeProc");

JKSYS.FrmShimeProc = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmShimeProc";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmShimeProc.btnUpdate",
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

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //コンボボックスに初期値設定
        me.FrmShimeProc_Load();
    };

    //**********************************************************************
    //処 理 名：LOAD
    //関 数 名：FrmShimeProc_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：LOAD
    //**********************************************************************
    me.FrmShimeProc_Load = function () {
        me.subDispSyoriYM();
    };

    //**********************************************************************
    //処 理 名：人事コントロールマスタの処理年月を更新する
    //関 数 名：btnUpdate_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：人事コントロールマスタの処理年月を更新する
    //**********************************************************************
    $(".FrmShimeProc.btnUpdate").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpdate_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            $(".FrmShimeProc.lblSyoriYM").val().replace("/", "年") +
                "月の締め処理を行います。よろしいですか？"
        );
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：subDispSyoriYM
    //関 数 名：subDispSyoriYM
    //引    数：無し
    //戻 り 値：無し
    //処理説明：処理年月を表示する
    //**********************************************************************
    me.subDispSyoriYM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "subDispSyoriYM";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["row"] && result["row"] > 0) {
                    $(".FrmShimeProc.lblSyoriYM").val(
                        result["data"][0]["SYORI_YM"]
                    );
                } else {
                    $(".FrmShimeProc.lblSyoriYM").val("2010/11");

                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            } else {
                $(".FrmShimeProc.btnUpdate").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：人事コントロールマスタの処理年月を更新する
    //関 数 名：btnUpdate_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：人事コントロールマスタの処理年月を更新する
    //**********************************************************************
    me.btnUpdate_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnUpdate_Click";
        var data = "";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    me.FrmShimeProc_Load();
                };
                me.clsComFnc.FncMsgBox("I0005");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_FrmShimeProc_FrmShimeProc = new JKSYS.FrmShimeProc();
    o_FrmShimeProc_FrmShimeProc.load();
});
