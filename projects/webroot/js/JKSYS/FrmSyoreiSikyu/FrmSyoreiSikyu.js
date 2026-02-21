/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmSyoreiSikyu");

JKSYS.FrmSyoreiSikyu = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyoreiSikyu";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.DateTimePicker1 = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSyoreiSikyu.cmdExcel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyoreiSikyu.DateTimePicker1",
        type: "datepicker3",
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

    //年月blur
    $(".FrmSyoreiSikyu.DateTimePicker1").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmSyoreiSikyu.DateTimePicker1")) ==
            false
        ) {
            $(".FrmSyoreiSikyu.DateTimePicker1").val(me.DateTimePicker1);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmSyoreiSikyu.DateTimePicker1").trigger("focus");
                    $(".FrmSyoreiSikyu.DateTimePicker1").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmSyoreiSikyu.DateTimePicker1").trigger("focus");
                        $(".FrmSyoreiSikyu.DateTimePicker1").select();
                    }, 0);
                }
            }
            if (me.DateTimePicker1 == "") {
                $(".FrmSyoreiSikyu.cmdExcel").button("disable");
            }
        } else {
            $(".FrmSyoreiSikyu.cmdExcel").button("enable");
        }
    });
    //EXCEL出力ボタンクリック
    $(".FrmSyoreiSikyu.cmdExcel").click(function () {
        me.fncInputChk();
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
        //フォームロード
        me.FrmSyoreiSikyu_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：FrmSyoreiSikyu_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FrmSyoreiSikyu_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "FrmSyoreiSikyu_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmSyoreiSikyu").ympicker("disable");
                $(".FrmSyoreiSikyu").attr("disabled", true);
                $(".FrmSyoreiSikyu button").button("disable");
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コントロールマスタが存在しません。管理者にご連絡ください！"
                    );
                    return;
                }

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //0件以外の場合
            //対象年月日をセット
            if (result["data"]["SYORI_YM"]) {
                me.DateTimePicker1 = result["data"]["SYORI_YM"];
                $(".FrmSyoreiSikyu.DateTimePicker1").val(me.DateTimePicker1);
                $(".FrmSyoreiSikyu.DateTimePicker1").trigger("focus");
                $(".FrmSyoreiSikyu.DateTimePicker1").select();
            }
        };
        //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
        me.ajax.send(url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：初期色
     '関 数 名：subResetColor
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.subResetColor = function () {
        $(".FrmSyoreiSikyu.rdbGyousekiDiv").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmSyoreiSikyu.rdbGyousekiTenpobetuDiv").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmSyoreiSikyu.rdbTencyouDiv").css(me.clsComFnc.GC_COLOR_NORMAL);
    };
    /*
     '**********************************************************************
     '処 理 名：実行ボタンクリック
     '関 数 名：fncInputChk
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncInputChk = function () {
        //初期色セット
        me.subResetColor();
        //両方ともにチェックが入っていない場合ｴﾗｰ
        if (
            $(".FrmSyoreiSikyu.rdbGyouseki").prop("checked") != true &&
            $(".FrmSyoreiSikyu.rdbGyousekiTenpobetu").prop("checked") != true &&
            $(".FrmSyoreiSikyu.rdbTencyou").prop("checked") != true
        ) {
            $(".FrmSyoreiSikyu.rdbGyousekiDiv").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            $(".FrmSyoreiSikyu.rdbGyousekiTenpobetuDiv").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            $(".FrmSyoreiSikyu.rdbTencyouDiv").css(me.clsComFnc.GC_COLOR_ERROR);

            me.clsComFnc.ObjFocus = $(".FrmSyoreiSikyu.rdbGyouseki");
            me.clsComFnc.FncMsgBox("W9999", "出力対象を選択して下さい");
            return;
        }

        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdExcel_Click;
        me.clsComFnc.FncMsgBox("QY005");
    };
    /*
     '**********************************************************************
     '処 理 名：実行_はい(Y)ボタンクリック
     '関 数 名：cmdExcel_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdExcel_Click = function () {
        var data = {
            dateTimePicker1: $(".FrmSyoreiSikyu.DateTimePicker1").val(),
            kbn: $(
                ".FrmSyoreiSikyu[name='FrmSyoreiSikyu_radio']:checked"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/" + "cmdExcel_Click";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.ObjFocus = $(".FrmSyoreiSikyu.DateTimePicker1");
                me.clsComFnc.FncMsgBox("I0011");
                me.FrmSyoreiSikyu_Load();
            } else {
                if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "I9999") {
                    me.clsComFnc.FncMsgBox("I9999", result["data"]["msg"]);
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
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
    o_FrmSyoreiSikyu_FrmSyoreiSikyu = new JKSYS.FrmSyoreiSikyu();
    o_FrmSyoreiSikyu_FrmSyoreiSikyu.load();
});
