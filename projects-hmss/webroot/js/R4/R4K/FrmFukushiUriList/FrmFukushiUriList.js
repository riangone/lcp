/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922			  #2162							BUG								yin
 * 20201117           bug                           年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。 WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmFukushiUriList");

R4.FrmFukushiUriList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmFukushiUriList";
    me.sys_id = "R4K";
    me.cboYM = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmFukushiUriList.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmFukushiUriList.cboYM",
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
        me.frmLeaseUriageMeisai_Load();
        $(".FrmFukushiUriList.cboYM").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmFukushiUriList.cboYM").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmFukushiUriList.cboYM")) == false)
        if (me.clsComFnc.CheckDate3($(".FrmFukushiUriList.cboYM")) == false) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmFukushiUriList.cboYM").val(me.cboYM);
                $(".FrmFukushiUriList.cboYM").trigger("focus");
                $(".FrmFukushiUriList.cboYM").select();
                $(".FrmFukushiUriList.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmFukushiUriList.cmdAction").button("enable");
        }
    });

    $(".FrmFukushiUriList.cboYM").on("keydown", function (e) {
        var key = e.which;
        if (key == 9 || key == 13) {
            $(".FrmFukushiUriList.cmdAction").button("enable");
            $(".FrmFukushiUriList.cmdAction").trigger("focus");
        }
    });

    //実行Button
    $(".FrmFukushiUriList.cmdAction").click(function () {
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
    me.frmLeaseUriageMeisai_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "frmLeaseUriageMeisai_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150922 yin upd S
                    // $(".FrmFukushiUriList.cboYM").val(result['data'][0]['TOUGETU'].substr(0, 7));
                    // me.cboYM = result['data'][0]['TOUGETU'].substr(0, 7);
                    $(".FrmFukushiUriList.cboYM").val(
                        result["data"][0]["TOUGETU"]
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin upd E
                } else {
                    //20240521 lujunxia upd s
                    $(".FrmFukushiUriList.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    //$('.FrmFukushiUriList.cboYM').datepicker('setDate', new Date());
                    //20240521 lujunxia upd e
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
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var data = {
            cboYM: $(".FrmFukushiUriList.cboYM").val().replace("/", ""),
            cboYM1: $(".FrmFukushiUriList.cboYM").val(),
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
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFukushiUriList = new R4.FrmFukushiUriList();
    o_R4_FrmFukushiUriList.load();
});
