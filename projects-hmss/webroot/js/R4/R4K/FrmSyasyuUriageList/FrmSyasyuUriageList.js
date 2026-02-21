/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922	 		  #2162						   BUG								YIN
 * 20151112           20151112以降の修正差異点                                         YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyasyuUriageList");

R4.FrmSyasyuUriageList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyasyuUriageList";
    me.sys_id = "R4K";
    me.strTougetu = "";
    me.strKisyu = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyasyuUriageList.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyasyuUriageList.cboYMFrom",
        //20150922 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd E
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyasyuUriageList.cboYMTo",
        //20150922 yin upd S
        //type : "datepicker2",
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
    $(".FrmSyasyuUriageList.cboYMFrom").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSyasyuUriageList.cboYMFrom")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuUriageList.cboYMFrom")) ==
            false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuUriageList.cboYMFrom").val(me.strKisyu);
                $(".FrmSyasyuUriageList.cboYMFrom").trigger("focus");
                $(".FrmSyasyuUriageList.cboYMFrom").select();
                $(".FrmSyasyuUriageList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyasyuUriageList.cmdAction").button("enable");
        }
    });

    $(".FrmSyasyuUriageList.cboYMTo").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSyasyuUriageList.cboYMTo")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuUriageList.cboYMTo")) == false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuUriageList.cboYMTo").val(me.strTougetu);
                $(".FrmSyasyuUriageList.cboYMTo").trigger("focus");
                $(".FrmSyasyuUriageList.cboYMTo").select();
                $(".FrmSyasyuUriageList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyasyuUriageList.cmdAction").button("enable");
        }
    });
    //To make the date format.End

    //radio changed events.Start
    $(".FrmSyasyuUriageList.radTougetu").click(function () {
        var cboYMFrom = $(".FrmSyasyuUriageList.cboYMFrom").val();
        //20150922 yin upd S
        // $('.FrmSyasyuUriageList.cboYMFrom').datepicker("option",
        // {
        // disabled : true
        // });

        //---20151112 Yin DEL S
        // $('.FrmSyasyuUriageList.cboYMFromdiv').block(
        // {
        // "overlayCSS" :
        // {
        // opacity : 0,
        // }
        // });
        // $('.FrmSyasyuUriageList.cboYMFrom').attr('disabled', true);
        //---20151112 Yin DEL E

        //20150922 yin upd E
        $(".FrmSyasyuUriageList.cboYMFrom").val(cboYMFrom);
    });
    $(".FrmSyasyuUriageList.radTouki").click(function () {
        var cboYMFrom = $(".FrmSyasyuUriageList.cboYMFrom").val();
        //20150922 yin upd S
        // $('.FrmSyasyuUriageList.cboYMFrom').datepicker("option",
        // {
        // disabled : false
        // });

        //---20151112 Yin DEL S
        //$('.FrmSyasyuUriageList.cboYMFromdiv').unblock();
        //$('.FrmSyasyuUriageList.cboYMFrom').attr('disabled', false);
        //---20151112 Yin DEL E

        //20150922 yin upd E
        $(".FrmSyasyuUriageList.cboYMFrom").val(cboYMFrom);
    });
    $(".FrmSyasyuUriageList.radDouble").click(function () {
        var cboYMFrom = $(".FrmSyasyuUriageList.cboYMFrom").val();
        //20150922 yin upd S
        // $('.FrmSyasyuUriageList.cboYMFrom').datepicker("option",
        // {
        // disabled : false
        // });
        $(".FrmSyasyuUriageList.cboYMFromdiv").unblock();
        $(".FrmSyasyuUriageList.cboYMFrom").attr("disabled", false);
        //20150922 yin upd E
        $(".FrmSyasyuUriageList.cboYMFrom").val(cboYMFrom);
    });
    //radio changed events.End

    //実行Button
    $(".FrmSyasyuUriageList.cmdAction").click(function () {
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
            $(".FrmSyasyuUriageList.radTougetu").prop("checked", "checked");
            //20150922 yin upd S
            // $('.FrmSyasyuUriageList.cboYMFrom').datepicker("option",
            // {
            // disabled : true
            // });

            //---20151112 Yin DEL S
            // $('.FrmSyasyuUriageList.cboYMFromdiv').block(
            // {
            // "overlayCSS" :
            // {
            // opacity : 0,
            // }
            // });
            // $('.FrmSyasyuUriageList.cboYMFrom').attr('disabled', true);
            //---20151112 Yin DEL E

            //20150922 yin upd E
            $(".FrmSyasyuUriageList.radTougetu").trigger("focus");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //コンボボックスに当月年月を設定
                    //20150922 yin  upd S
                    me.strKisyu = result["data"][0]["KISYU_YMD"]
                        .substr(0, 7)
                        .replace("/", "");
                    me.strTougetu = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin  upd E
                    $(".FrmSyasyuUriageList.cboYMFrom").val(me.strKisyu);
                    $(".FrmSyasyuUriageList.cboYMTo").val(me.strTougetu);
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
            $(".FrmSyasyuUriageList.radTouki").prop("checked") == true ||
            $(".FrmSyasyuUriageList.radDouble").prop("checked") == true
        ) {
            if (
                $(".FrmSyasyuUriageList.cboYMFrom").val().replace(/\//g, "") >
                $(".FrmSyasyuUriageList.cboYMTo").val().replace(/\//g, "")
            ) {
                $(".FrmSyasyuUriageList.cboYMFrom").trigger("focus");
                me.clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です!");
                return;
            }
        }

        if ($(".FrmSyasyuUriageList.radTougetu").prop("checked") == true) {
            var url = me.sys_id + "/" + me.id + "/" + "fncPrintTougetu";
            var data = $(".FrmSyasyuUriageList.cboYMTo")
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
            if ($(".FrmSyasyuUriageList.radTouki").prop("checked") == true) {
                var url = me.sys_id + "/" + me.id + "/" + "fncPrintTouki";
                var data = {
                    cboYMFrom: $(".FrmSyasyuUriageList.cboYMFrom")
                        .val()
                        .replace(/\//g, ""),
                    cboYMTo: $(".FrmSyasyuUriageList.cboYMTo")
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
                    cboYMFrom: $(".FrmSyasyuUriageList.cboYMFrom")
                        .val()
                        .replace(/\//g, ""),
                    cboYMTo: $(".FrmSyasyuUriageList.cboYMTo")
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
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyasyuUriageList = new R4.FrmSyasyuUriageList();
    o_R4_FrmSyasyuUriageList.load();
});
