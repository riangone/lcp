/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmHyogoCheckList");

JKSYS.FrmHyogoCheckList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHyogoCheckList";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.resultJissi = new Array();
    me.dtFrom = "";
    me.dtTo = "";
    me._strSyokoukyuMonth = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmHyogoCheckList.btnExcel",
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
    $(".FrmHyogoCheckList.btnExcel").click(function () {
        me.btnExcel_Click();
    });
    $(".FrmHyogoCheckList.cmbJissi").change(function () {
        me.cmbJissi_SelectedIndexChanged($(this).val());
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
        //初期値設定
        me.frmHyogoCheckList_Load();
    };
    //**********************************************************************
    //処 理 名：初期値設定
    //関 数 名：frmHyogoCheckList_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.frmHyogoCheckList_Load = function () {
        //画面初期化
        me.Formit();
    };
    //**********************************************************************
    //処 理 名：画面初期化(画面起動時)
    //関 数 名：Formit
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.Formit = function () {
        url = me.sys_id + "/" + me.id + "/" + "Formit";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".FrmHyogoCheckList.btnExcel").button("enable");
                me._strSyokoukyuMonth = result["data"]["strSyokoukyuMonth"];
                me.resultJissi = result["data"]["YMSet"];
                //実施年月
                $(".FrmHyogoCheckList.cmbJissi").val(
                    result["data"]["YMSet"][0]["JISSHI_YM"]
                );
                if (me.resultJissi.length > 0) {
                    for (key in me.resultJissi) {
                        $("<option></option>")
                            .val(me.resultJissi[key]["JISSHI_YM"])
                            .text(me.resultJissi[key]["JISSHI_YM"])
                            .appendTo(".FrmHyogoCheckList.cmbJissi");
                    }
                    var resultTaisyou = result["data"]["DT"];
                    if (resultTaisyou.length > 0) {
                        if (resultTaisyou["0"]["KIKAN"] == "") {
                            $(".FrmHyogoCheckList.btnExcel").button("disable");
                        }
                        $(".FrmHyogoCheckList.lblTaisyou").text(
                            resultTaisyou["0"]["KIKAN"]
                        );
                        me.dtFrom = resultTaisyou["0"]["HYOUKA_KIKAN_START"];
                        me.dtTo = resultTaisyou["0"]["HYOUKA_KIKAN_END"];
                    } else {
                        $(".FrmHyogoCheckList.btnExcel").button("disable");
                        $(".FrmHyogoCheckList.lblTaisyou").text("");
                    }
                }
                $(".FrmHyogoCheckList.cmbJissi").trigger("focus");
            } else {
                $(".FrmHyogoCheckList button").button("disable");
                if (result["error"] == "W9999") {
                    $(".FrmHyogoCheckList.cmbJissi").val(result["currYM"]);
                    $("<option></option>")
                        .val(result["currYM"])
                        .text(result["currYM"])
                        .appendTo(".FrmHyogoCheckList.cmbJissi");
                    var resultTaisyou = result["data"]["DT"];
                    if (resultTaisyou.length > 0) {
                        $(".FrmHyogoCheckList.lblTaisyou").text(
                            resultTaisyou["0"]["KIKAN"]
                        );
                        me.dtFrom = resultTaisyou["0"]["HYOUKA_KIKAN_START"];
                        me.dtTo = resultTaisyou["0"]["HYOUKA_KIKAN_END"];
                    }
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "評価取込履歴データが存在しません。"
                    );
                } else {
                    $(".FrmHyogoCheckList").prop("disabled", true);
                    $(".FrmHyogoCheckList button").button("disable");

                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                $("<option></option>")
                    .val("2010/09")
                    .text("2010/09")
                    .appendTo(".FrmHyogoCheckList.cmbJissi");
                $("<option></option>")
                    .val("2010/03")
                    .text("2010/03")
                    .appendTo(".FrmHyogoCheckList.cmbJissi");
            }
        };
        me.ajax.send(url, "", 0);
    };
    //**********************************************************************
    //処 理 名：Excel
    //関 数 名：btnExcel_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：Excel
    //**********************************************************************
    me.btnExcel_Click = function () {
        url = me.sys_id + "/" + me.id + "/" + "cmdExcel_Click";
        data = {
            JISSI: $(".FrmHyogoCheckList.cmbJissi").val(),
            dtFrom: me.dtFrom,
            dtTo: me.dtTo,
            strSyokoukyuMonth: me._strSyokoukyuMonth,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox(result["data"]);
            } else {
                //フォルダーが存在するかどうかのﾁｪｯｸ
                if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                } else if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：
    //関 数 名：cmbJissi_SelectedIndexChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：評価実施年月フォーカス移動時
    //**********************************************************************
    me.cmbJissi_SelectedIndexChanged = function (cmbJissiText) {
        url = me.sys_id + "/" + me.id + "/" + "cmbJissiSelectedIndexChanged";
        data = {
            strJisshi: cmbJissiText,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            $(".FrmHyogoCheckList.btnExcel").button("disable");
            if (result["result"] == true) {
                if (result["row"] == 0) {
                    $(".FrmHyogoCheckList.lblTaisyou").text("");
                } else {
                    $(".FrmHyogoCheckList.btnExcel").button("enable");
                    $(".FrmHyogoCheckList.lblTaisyou").text(
                        result["data"]["0"]["KIKAN"]
                    );
                    me.dtFrom = result["data"]["0"]["HYOUKA_KIKAN_START"];
                    me.dtTo = result["data"]["0"]["HYOUKA_KIKAN_END"];
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
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
    o_FrmHyogoCheckList_FrmHyogoCheckList = new JKSYS.FrmHyogoCheckList();
    o_FrmHyogoCheckList_FrmHyogoCheckList.load();
});
