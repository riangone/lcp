/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922	 		  #2162						   BUG								YIN
 * 20151112           20151112以降の修正差異点                                         YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyasyuTourokuList");

R4.FrmSyasyuTourokuList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyasyuTourokuList";
    me.sys_id = "R4K";
    me.strTougetu = "";
    me.strKisyu = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyasyuTourokuList.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyasyuTourokuList.cboYMFrom",
        //20150922 yin upd S
        // type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd E
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyasyuTourokuList.cboYMTo",
        //20150922 yin upd S
        // type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd E
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
    //To make the date format.Start
    $(".FrmSyasyuTourokuList.cboYMFrom").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSyasyuTourokuList.cboYMFrom")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuTourokuList.cboYMFrom")) ==
            false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuTourokuList.cboYMFrom").val(me.strKisyu);
                $(".FrmSyasyuTourokuList.cboYMFrom").trigger("focus");
                $(".FrmSyasyuTourokuList.cboYMFrom").select();
                $(".FrmSyasyuTourokuList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyasyuTourokuList.cmdAction").button("enable");
        }
    });

    $(".FrmSyasyuTourokuList.cboYMTo").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSyasyuTourokuList.cboYMTo")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuTourokuList.cboYMTo")) == false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuTourokuList.cboYMTo").val(me.strTougetu);
                $(".FrmSyasyuTourokuList.cboYMTo").trigger("focus");
                $(".FrmSyasyuTourokuList.cboYMTo").select();
                $(".FrmSyasyuTourokuList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyasyuTourokuList.cmdAction").button("enable");
        }
    });
    //To make the date format.End

    //radio changed events.Start
    $(".FrmSyasyuTourokuList.radTougetu").click(function () {
        var cboYMFrom = $(".FrmSyasyuTourokuList.cboYMFrom").val();
        // $('.FrmSyasyuTourokuList.cboYMFrom').datepicker("option",
        // {
        // disabled : true
        // });

        //---20151112 Yin DEL S
        // $('.FrmSyasyuTourokuList.cboYMFromdiv').block(
        // {
        // "overlayCSS" :
        // {
        // opacity : 0,
        // }
        // });
        // $('.FrmSyasyuTourokuList.cboYMFrom').attr('disabled', true);
        //---20151112 Yin DEL E

        $(".FrmSyasyuTourokuList.cboYMFrom").val(cboYMFrom);
    });
    $(".FrmSyasyuTourokuList.radTouki").click(function () {
        var cboYMFrom = $(".FrmSyasyuTourokuList.cboYMFrom").val();
        // $('.FrmSyasyuTourokuList.cboYMFrom').datepicker("option",
        // {
        // disabled : false
        // });
        $(".FrmSyasyuTourokuList.cboYMFromdiv").unblock();
        $(".FrmSyasyuTourokuList.cboYMFrom").attr("disabled", false);
        $(".FrmSyasyuTourokuList.cboYMFrom").val(cboYMFrom);
    });
    $(".FrmSyasyuTourokuList.radDouble").click(function () {
        var cboYMFrom = $(".FrmSyasyuTourokuList.cboYMFrom").val();
        // $('.FrmSyasyuTourokuList.cboYMFrom').datepicker("option",
        // {
        // disabled : false
        // });
        $(".FrmSyasyuTourokuList.cboYMFromdiv").unblock();
        $(".FrmSyasyuTourokuList.cboYMFrom").attr("disabled", false);
        $(".FrmSyasyuTourokuList.cboYMFrom").val(cboYMFrom);
    });
    //radio changed events.End

    //実行Button
    $(".FrmSyasyuTourokuList.cmdAction").click(function () {
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
        //コントロールマスタ存在ﾁｪｯｸ
        var url = me.sys_id + "/" + me.id + "/" + "frmKanrSyukei_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //当月選択
            $(".FrmSyasyuTourokuList.radTougetu").prop("checked", "checked");
            // $('.FrmSyasyuTourokuList.cboYMFrom').datepicker("option",
            // {
            // disabled : true
            // });

            //---20151112 Yin DEL S
            // $('.FrmSyasyuTourokuList.cboYMFromdiv').block(
            // {
            // "overlayCSS" :
            // {
            // opacity : 0,
            // }
            // });
            // $('.FrmSyasyuTourokuList.cboYMFrom').attr("disabled", true);
            //---20151112 Yin DEL E

            $(".FrmSyasyuTourokuList.radTougetu").trigger("focus");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //コンボボックスに当月年月を設定
                    //20150922 yin upd S
                    // me.strKisyu = result['data'][0]['KISYU_YMD'].substr(0, 7);
                    // me.strTougetu = result['data'][0]['TOUGETU'].substr(0, 7);
                    me.strKisyu = result["data"][0]["KISYU_YMD"]
                        .substr(0, 7)
                        .replace("/", "");
                    me.strTougetu = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin upd E
                    $(".FrmSyasyuTourokuList.cboYMFrom").val(me.strKisyu);
                    $(".FrmSyasyuTourokuList.cboYMTo").val(me.strTougetu);
                } else {
                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };
    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdEnd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：印刷する
    //**********************************************************************
    me.cmdAction_Click = function () {
        //入力チェック
        if (
            $(".FrmSyasyuTourokuList.radTouki").prop("checked") == true ||
            $(".FrmSyasyuTourokuList.radDouble").prop("checked") == true
        ) {
            if (
                $(".FrmSyasyuTourokuList.cboYMFrom").val().replace(/\//g, "") >
                $(".FrmSyasyuTourokuList.cboYMTo").val().replace(/\//g, "")
            ) {
                $(".FrmSyasyuTourokuList.cboYMFrom").trigger("focus");
                me.clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です!");
                return;
            }
        }

        if ($(".FrmSyasyuTourokuList.radTougetu").prop("checked") == true) {
            var url = me.sys_id + "/" + me.id + "/" + "fncPrintTougetut";
            var data = $(".FrmSyasyuTourokuList.cboYMTo")
                .val()
                .replace(/\//g, "");
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
            };
            me.ajax.send(url, data, 0);
        } else {
            if ($(".FrmSyasyuTourokuList.radTouki").prop("checked") == true) {
                var url = me.sys_id + "/" + me.id + "/" + "fncPrintTouki";
                var data = {
                    cboYMFrom: $(".FrmSyasyuTourokuList.cboYMFrom")
                        .val()
                        .replace(/\//g, ""),
                    cboYMTo: $(".FrmSyasyuTourokuList.cboYMTo")
                        .val()
                        .replace(/\//g, ""),
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
                };
                me.ajax.send(url, data, 0);
            } else {
                var url = me.sys_id + "/" + me.id + "/" + "fncPrintDouble";
                var data = {
                    cboYMFrom: $(".FrmSyasyuTourokuList.cboYMFrom")
                        .val()
                        .replace(/\//g, ""),
                    cboYMTo: $(".FrmSyasyuTourokuList.cboYMTo")
                        .val()
                        .replace(/\//g, ""),
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
                };
                me.ajax.send(url, data, 0);
            }
        }
        $(".FrmSyasyuTourokuList.cboYMFrom").trigger("focus");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyasyuTourokuList = new R4.FrmSyasyuTourokuList();
    o_R4_FrmSyasyuTourokuList.load();
});
