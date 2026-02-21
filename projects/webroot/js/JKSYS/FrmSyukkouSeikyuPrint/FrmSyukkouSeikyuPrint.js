/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 */

Namespace.register("JKSYS.FrmSyukkouSeikyuPrint");

JKSYS.FrmSyukkouSeikyuPrint = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmSyukkouSeikyuPrint";
    me.sys_id = "JKSYS";
    me.url = "";
    me.DateTimePicker1 = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyukkouSeikyuPrint.cmdPri",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyukkouSeikyuPrint.DateTimePicker1",
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

    //印刷ボタンクリック
    $(".FrmSyukkouSeikyuPrint.cmdPri").click(function () {
        me.cmdPri_Click();
    });

    //年月blur:空=>初期値
    $(".FrmSyukkouSeikyuPrint.DateTimePicker1").blur(function (e) {
        if (
            me.clsComFnc.CheckDate3(
                $(".FrmSyukkouSeikyuPrint.DateTimePicker1")
            ) == false
        ) {
            $(".FrmSyukkouSeikyuPrint.DateTimePicker1").val(me.DateTimePicker1);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmSyukkouSeikyuPrint.DateTimePicker1").trigger(
                        "focus"
                    );
                    $(".FrmSyukkouSeikyuPrint.DateTimePicker1").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmSyukkouSeikyuPrint.DateTimePicker1").trigger(
                            "focus"
                        );
                        $(".FrmSyukkouSeikyuPrint.DateTimePicker1").select();
                    }, 0);
                }
            }
            $(".FrmSyukkouSeikyuPrint.cmdPri").button("disable");
        } else {
            $(".FrmSyukkouSeikyuPrint.cmdPri").button("enable");
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
        //フォームロード
        me.frmSyukkouSeikyuPrint_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：frmSyukkouSeikyuPrint_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.frmSyukkouSeikyuPrint_Load = function () {
        $(".FrmSyukkouSeikyuPrint.cmdPri").button("enable");

        me.url = me.sys_id + "/" + me.id + "/fncLoad";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmSyukkouSeikyuPrint").ympicker("disable");
                $(".FrmSyukkouSeikyuPrint").attr("disabled", true);
                $(".FrmSyukkouSeikyuPrint button").button("disable");
                //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "コントロールマスタが存在しません。管理者にご連絡ください！"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //对象年月
                if (result["data"]["SYORI_YM"]) {
                    //'0件以外の場合
                    //'対象年月日をセット
                    me.DateTimePicker1 = result["data"]["SYORI_YM"];
                    $(".FrmSyukkouSeikyuPrint.DateTimePicker1").val(
                        me.DateTimePicker1
                    );
                }
                //对象年月选中
                $(".FrmSyukkouSeikyuPrint.DateTimePicker1").select();

                //'出向先コンボの設定
                $(".FrmSyukkouSeikyuPrint.cmbSyukko").empty();
                var tmpStr = '<option value="999999">全て</option> ';
                for (var i = 0; i < result["data"]["BUSYO"]["row"]; i++) {
                    tmpStr +=
                        '<option value="' +
                        result["data"]["BUSYO"]["data"][i]["KUBUN_CD"] +
                        '">' +
                        result["data"]["BUSYO"]["data"][i]["BUSYO_NM"] +
                        "</option> ";
                }
                $(".FrmSyukkouSeikyuPrint.cmbSyukko").append(tmpStr);
            }
        };
        //人事ｺﾝﾄﾛｰﾙﾏｽﾀの取得を行う
        me.ajax.send(me.url, "", 0);
    };

    /*
	 '**********************************************************************
	 '処 理 名：印刷ボタン
	 '関 数 名：cmdPri_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdPri_Click = function () {
        var strYM = $(".FrmSyukkouSeikyuPrint.DateTimePicker1").val();
        var busyoCD = $(".FrmSyukkouSeikyuPrint.cmbSyukko").val();
        var busyoNM = $(".FrmSyukkouSeikyuPrint.cmbSyukko")
            .find("option:selected")
            .text();

        me.url = me.sys_id + "/" + me.id + "/fncPriClick";
        me.data = {
            taisyoYM: strYM,
            busyoCD: busyoCD,
            busyoNM: busyoNM,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                //存在チェック
                if (result["error"] == "W9999") {
                    me.clsComFnc.ObjFocus = $(
                        ".FrmSyukkouSeikyuPrint.DateTimePicker1"
                    );
                    me.clsComFnc.FncMsgBox("W9999", result["msg"]);
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                if (result["printData"]["data"]) {
                    window.open(result["printData"]["data"]);
                }
            }
        };

        me.ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyukkouSeikyuPrint = new JKSYS.FrmSyukkouSeikyuPrint();
    o_JKSYS_FrmSyukkouSeikyuPrint.load();
});
