Namespace.register("JKSYS.FrmSeikatutousou");

JKSYS.FrmSeikatutousou = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ========== 変数 start ==========
    me.sys_id = "JKSYS";
    me.id = "FrmSeikatutousou";
    me.id_url = me.sys_id + "/" + me.id;
    me.grid_id = "#FrmSeikatutousou_sprList";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSeikatutousou.cmdExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSeikatutousou.DateTimePicker1",
        type: "datepicker4",
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
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmSeikatutousou.cmdExcel").click(function () {
        me.cmdExcel_Click();
    });

    //年月blur:空=>初期値
    $(".FrmSeikatutousou.DateTimePicker1").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate4($(".FrmSeikatutousou.DateTimePicker1")) ==
            false
        ) {
            $(".FrmSeikatutousou.DateTimePicker1").val(me.tblCTL);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmSeikatutousou.DateTimePicker1").trigger("focus");
                    $(".FrmSeikatutousou.DateTimePicker1").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmSeikatutousou.DateTimePicker1").trigger("focus");
                        $(".FrmSeikatutousou.DateTimePicker1").select();
                    }, 0);
                }
            }
            $(".FrmSeikatutousou.cmdExcel").button("disable");
        } else {
            $(".FrmSeikatutousou.cmdExcel").button("enable");
        }
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
        me.frmSeikatutousou_Load();
    };
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.frmSeikatutousou_Load = function () {
        //初期処理
        var url = me.id_url + "/" + "fncJinjiCtlMstSQL";
        me.ajax.receive = function (result) {
            //画面項目ｸﾘｱ
            me.subClearForm();

            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmSeikatutousou").ympicker("disable");
                $(".FrmSeikatutousou").prop("disabled", true);
                $(".FrmSeikatutousou button").button("disable");

                if (result["error"] == "W9999") {
                    //0件の場合
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コントロールマスタが存在しません。管理者にご連絡ください！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }

            me.tblCTL = result["SYORI_YM"];
            $(".FrmSeikatutousou.DateTimePicker1").val(me.tblCTL);
            $(".FrmSeikatutousou.DateTimePicker1").select();
        };
        me.ajax.send(url, "", 0);
    };

    // '**********************************************************************
    // '処理概要：画面項目クリア
    // '**********************************************************************
    me.subClearForm = function () {
        $(".FrmSeikatutousou.chkNo1").prop("checked", true);
        $(".FrmSeikatutousou.chkNo2").prop("checked", true);
        me.subResetColor();
    };

    // '**********************************************************************
    // '処理概要：初期色
    // '**********************************************************************
    me.subResetColor = function () {
        $(".FrmSeikatutousou.chkNo div").css(me.clsComFnc.GC_COLOR_NORMAL);
    };

    // '**********************************************************************
    // '処理概要：初期色
    // '**********************************************************************
    me.cmdExcel_Click = function () {
        if (me.fncInputChk() == false) {
            return;
        }
        var DateTimePicker1 = $(".FrmSeikatutousou.DateTimePicker1").val();

        var url = me.id_url + "/" + "cmdExcel_Click";
        var data = {
            DateTimePicker1: DateTimePicker1,
            chkNo1checked: $(".FrmSeikatutousou.chkNo1").prop("checked"),
            chkNo2checked: $(".FrmSeikatutousou.chkNo2").prop("checked"),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.ObjFocus = $(".FrmSeikatutousou.DateTimePicker1");
                me.clsComFnc.FncMsgBox("I0011");
            } else {
                if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
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

    // '**********************************************************************
    // '処理概要：入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        //初期色セット
        me.subResetColor();

        //両方ともにチェックが入っていない場合エラー
        if (
            !$(".FrmSeikatutousou.chkNo1").prop("checked") &&
            !$(".FrmSeikatutousou.chkNo2").prop("checked")
        ) {
            $(".FrmSeikatutousou.chkNo div").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.ObjFocus = $(".FrmSeikatutousou.chkNo1");
            me.clsComFnc.FncMsgBox("W9999", "帳票種類を選択して下さい");
            return false;
        }

        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSeikatutousou = new JKSYS.FrmSeikatutousou();
    o_JKSYS_FrmSeikatutousou.load();
    o_JKSYS_JKSYS.FrmSeikatutousou = o_JKSYS_FrmSeikatutousou;
});
