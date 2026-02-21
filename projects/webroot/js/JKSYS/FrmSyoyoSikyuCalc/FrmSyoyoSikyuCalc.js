Namespace.register("JKSYS.FrmSyoyoSikyuCalc");

JKSYS.FrmSyoyoSikyuCalc = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    // ========== 変数 start ==========
    me.id = "JKSYS/FrmSyoyoSikyuCalc";
    //評価対象期間開始日
    me._hyuokaTaisyouKikanSD = "";
    //評価対象期間終了日
    me._hyuokaTaisyouKikanED = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSyoyoSikyuCalc.cmdCsv",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyoyoSikyuCalc.cmbYM",
        type: "datepicker2",
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
    // 'CSV出力ボタン
    // '**********************************************************************
    $(".FrmSyoyoSikyuCalc.cmdCsv").click(function () {
        me.cmdCsv_Click();
    });
    // '**********************************************************************
    // '実施年月選択値変更時
    // '**********************************************************************
    $(".FrmSyoyoSikyuCalc.cmbYM").change(function () {
        me.cmbYM_SelectedIndexChanged();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.init_control = function () {
        base_init_control();
        me.frmSyoyoSikyuCalc_Load();
    };

    me.frmSyoyoSikyuCalc_Load = function () {
        me.subClearForm();

        var url = me.id + "/" + "frmSyoyoSikyuCalc_Load";
        me.ajax.receive = function (res) {
            var res = eval("(" + res + ")");
            if (res["result"] == false) {
                $(".FrmSyoyoSikyuCalc").attr("disabled", true);
                $(".FrmSyoyoSikyuCalc button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", res["error"]);
                return;
            } else {
                for (key in res["data"]["TrkRireki"]) {
                    $("<option></option>")
                        .val(res["data"]["TrkRireki"][key]["JISSHI_YM"])
                        .text(res["data"]["TrkRireki"][key]["JISSHI_YM"])
                        .appendTo(".FrmSyoyoSikyuCalc.cmbYM");
                }
                if (res["data"]["rekiKikan"].length > 0) {
                    $(".FrmSyoyoSikyuCalc.lblKikan").text(
                        res["data"]["rekiKikan"][0]["KIKAN"]
                    );
                    me._hyuokaTaisyouKikanSD =
                        res["data"]["rekiKikan"][0]["HYOUKA_KIKAN_START"];
                    me._hyuokaTaisyouKikanED =
                        res["data"]["rekiKikan"][0]["HYOUKA_KIKAN_END"];
                }

                $(".FrmSyoyoSikyuCalc.cmbYM").trigger("focus");
            }
        };
        me.ajax.send(url, "", 0);
    };

    me.cmdCsv_Click = function () {
        var cmbYM = $(".FrmSyoyoSikyuCalc.cmbYM").val();

        var url = me.id + "/" + "fncCsvOutput";
        var data = {
            cmbYM: cmbYM,
            _hyuokaTaisyouKikanSD: me._hyuokaTaisyouKikanSD,
            _hyuokaTaisyouKikanED: me._hyuokaTaisyouKikanED,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //出力処理が正常に終了しました
                me.clsComFnc.ObjFocus = $(".FrmSyoyoSikyuCalc.cmbYM");
                me.clsComFnc.FncMsgBox("I0011");
            } else {
                if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        cmbYM +
                            "実績分の評価データが存在しません。評価情報取込を行ってください"
                    );
                    return;
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.cmbYM_SelectedIndexChanged = function () {
        var cmbYM = $(".FrmSyoyoSikyuCalc.cmbYM").val();
        var url = me.id + "/" + "fncHyoukaTrkRirekiKikan";
        var data = {
            cmbYM: cmbYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["row"] == 0) {
                    //0件の場合
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "評価取込履歴データが存在しません"
                    );
                    return;
                } else {
                    //0件以外の場合
                    $(".FrmSyoyoSikyuCalc.lblKikan").text(
                        result["data"][0]["KIKAN"]
                    );
                    me._hyuokaTaisyouKikanSD =
                        result["data"][0]["HYOUKA_KIKAN_START"];
                    me._hyuokaTaisyouKikanED =
                        result["data"][0]["HYOUKA_KIKAN_END"];
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };

        me.ajax.send(url, data, 0);
    };

    me.subClearForm = function () {
        $(".FrmSyoyoSikyuCalc.cmbYM").selectedIndex = -1;
        $(".FrmSyoyoSikyuCalc.cmbYM").val("");
        $(".FrmSyoyoSikyuCalc.lblKikan").val("");
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyoyoSikyuCalc = new JKSYS.FrmSyoyoSikyuCalc();
    o_JKSYS_FrmSyoyoSikyuCalc.load();
    o_JKSYS_JKSYS.FrmSyoyoSikyuCalc = o_JKSYS_FrmSyoyoSikyuCalc;
});
