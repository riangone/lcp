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
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmGENRILIST");

R4.FrmGENRILIST = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmGENRILIST";
    me.sys_id = "R4K";
    me.cboYM = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmGENRILIST.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmGENRILIST.cboYM",
        //20150922 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd S
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
        me.frmGenkaiMake_Load();
        $(".FrmGENRILIST.cboYM").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmGENRILIST.cboYM").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmGENRILIST.cboYMFrom")) == false)
        if (me.clsComFnc.CheckDate3($(".FrmGENRILIST.cboYM")) == false) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmGENRILIST.cboYM").val(me.cboYM);
                $(".FrmGENRILIST.cboYM").trigger("focus");
                $(".FrmGENRILIST.cboYM").select();
                $(".FrmGENRILIST.cmdAct").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmGENRILIST.cmdAct").button("enable");
        }
    });

    $(".FrmGENRILIST.cboYM").on("keydown", function (e) {
        var key = e.which;
        if (key == 9 || key == 13) {
            $(".FrmGENRILIST.cmdAct").button("enable");
            $(".FrmGENRILIST.cmdAct").trigger("focus");
        }
    });

    //実行Button
    $(".FrmGENRILIST.cmdAct").click(function () {
        me.cmdAct_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmGenkaiMake_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmGenkaiMake_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "frmGenkaiMake_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150922 yin upd S
                    // $(".FrmGENRILIST.cboYM").val(result['data'][0]['TOUGETU'].substr(0, 7));
                    // me.cboYM = result['data'][0]['TOUGETU'].substr(0, 7);
                    $(".FrmGENRILIST.cboYM").val(
                        result["data"][0]["TOUGETU"]
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin upd E
                } else {
                    //20240530 lujunxia upd s
                    $(".FrmGENRILIST.cboYM").ympicker("setDate", new Date());
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    //$('.FrmGENRILIST.cboYM').datepicker('setDate', new Date());
                    //20240530 lujunxia upd e
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
    //関 数 名：cmdAct_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：印刷する
    //**********************************************************************
    me.cmdAct_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var data = $(".FrmGENRILIST.cboYM").val();
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
    var o_R4_FrmGENRILIST = new R4.FrmGENRILIST();
    o_R4_FrmGENRILIST.load();
});
