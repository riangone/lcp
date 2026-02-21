/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150923 		  #2162						   BUG								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmShikakariPrint");

R4.FrmShikakariPrint = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmShikakariPrint";
    me.sys_id = "R4K";
    me.cboYMStart = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmShikakariPrint.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmShikakariPrint.cboYMStart",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
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
    $(".FrmShikakariPrint.cboYMStart").on("blur", function () {
        //20150923 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmShikakariPrint.cboYMStart")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmShikakariPrint.cboYMStart")) == false
        ) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmShikakariPrint.cboYMStart").val(me.cboYMStart);
                $(".FrmShikakariPrint.cboYMStart").trigger("focus");
                $(".FrmShikakariPrint.cboYMStart").select();
                $(".FrmShikakariPrint.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmShikakariPrint.cmdAction").button("enable");
        }
    });

    $(".FrmShikakariPrint.cboYMStart").on("keydown", function (e) {
        var key = e.which;
        // var oEvent = window.event;
        if (key == 9 || key == 13) {
            $(".FrmShikakariPrint.cmdAction").button("enable");
            $(".FrmShikakariPrint.cmdAction").trigger("focus");
        }
    });
    //To make the date format.End

    //実行Button
    $(".FrmShikakariPrint.cmdAction").click(function () {
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
            //新車選択
            $(".FrmShikakariPrint.radNew").prop("checked", "checked");
            $(".FrmShikakariPrint.radNew").trigger("focus");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //コンボボックスに当月年月を設定
                    //20150923 yin upd S
                    // me.cboYMStart = result['data'][0]['TOUGETU'].substr(0, 7);
                    // $(".FrmShikakariPrint.cboYMStart").val(me.cboYMStart);
                    me.cboYMStart = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    $(".FrmShikakariPrint.cboYMStart").val(me.cboYMStart);
                    //20150923 yin upd E
                } else {
                    //コントロールマスタが存在していない場合
                    $(".FrmShikakariPrint.cboYMStart").ympicker(
                        "setDate",
                        new Date()
                    );
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
        var tyohan1 = "";
        var cboYMStart = $(".FrmShikakariPrint.cboYMStart").val();
        if ($(".FrmShikakariPrint.radNew").prop("checked") == true) {
            tyohan1 = "新車";
        }
        if ($(".FrmShikakariPrint.radOld").prop("checked") == true) {
            tyohan1 = "中古車";
        }
        if ($(".FrmShikakariPrint.radDouble").prop("checked") == true) {
            tyohan1 = "両方";
        }
        var url = me.sys_id + "/" + me.id + "/" + "fncPrint";
        var data = {
            tyohan1: tyohan1,
            cboYMStart: cboYMStart,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    window.open(result["pdfpath"]);
                    $(".FrmShikakariPrint.radNew").prop("checked", true);
                    $(".FrmShikakariPrint.radNew").trigger("focus");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
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
    var o_R4_FrmShikakariPrint = new R4.FrmShikakariPrint();
    o_R4_FrmShikakariPrint.load();
});
