/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmHanteChkList");

R4.FrmHanteChkList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHanteChkList";
    me.sys_id = "R4K";
    me.cboYMStart = "";
    me.cboYMEnd = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHanteChkList.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHanteChkList.cboYMStart",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHanteChkList.cboYMEnd",
        type: "datepicker",
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
        me.frmKanrSyukei_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmHanteChkList.cboYMStart").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmHanteChkList.cboYMStart")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmHanteChkList.cboYMStart").val(me.cboYMStart);
                $(".FrmHanteChkList.cboYMStart").trigger("focus");
                $(".FrmHanteChkList.cboYMStart").select();
                $(".FrmHanteChkList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmHanteChkList.cmdAction").button("enable");
        }
    });
    $(".FrmHanteChkList.cboYMEnd").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmHanteChkList.cboYMEnd")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmHanteChkList.cboYMEnd").val(me.cboYMEnd);
                $(".FrmHanteChkList.cboYMEnd").trigger("focus");
                $(".FrmHanteChkList.cboYMEnd").select();
                $(".FrmHanteChkList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmHanteChkList.cmdAction").button("enable");
        }
    });

    //実行Button
    $(".FrmHanteChkList.cmdAction").click(function () {
        me.cmdAction_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmKanrSyukei_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmKanrSyukei_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "frmKanrSyukei_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    $(".FrmHanteChkList.cboYMStart").val(
                        result["data"][0]["TOUGETU"]
                    );
                    $(".FrmHanteChkList.cboYMEnd").val(
                        result["data"][0]["TOUGETU"]
                    );
                    me.cboYMStart = result["data"][0]["TOUGETU"];
                    me.cboYMEnd = result["data"][0]["TOUGETU"];
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    $(".FrmHanteChkList.cboYMStart").datepicker(
                        "setDate",
                        new Date()
                    );
                    $(".FrmHanteChkList.cboYMEnd").datepicker(
                        "setDate",
                        new Date()
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmHanteChkList.cboYMStart").trigger("focus");
        };
        me.ajax.send(url, "", 1);
    };

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：印刷する
    //**********************************************************************
    me.cmdAction_Click = function () {
        //入力ﾁｪｯｸ
        if (
            $(".FrmHanteChkList.cboYMStart").val().replace(/\//g, "") >
            $(".FrmHanteChkList.cboYMEnd").val().replace(/\//g, "")
        ) {
            $(".FrmHanteChkList.cboYMStart").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "日付の範囲が不正です");
            return;
        }
        //印刷処理
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var data = {
            cboYMStart: $(".FrmHanteChkList.cboYMStart").val(),
            cboYMEnd: $(".FrmHanteChkList.cboYMEnd").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    window.open(result["pdfpath"]);
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            $(".FrmHanteChkList.cboYMStart").trigger("focus");
        };
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHanteChkList = new R4.FrmHanteChkList();
    o_R4_FrmHanteChkList.load();
});
