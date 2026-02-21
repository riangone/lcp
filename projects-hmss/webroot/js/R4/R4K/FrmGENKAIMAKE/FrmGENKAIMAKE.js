/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */
/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922			 #2162							BUG								YIN
 * 20201117          bug                            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmGENKAIMAKE");

R4.FrmGENKAIMAKE = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmGENKAIMAKE";
    me.sys_id = "R4K";
    me.cboYM = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmGENKAIMAKE.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmGENKAIMAKE.cboYM",
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
        //コンボボックスに初期値設定
        var currentYM = new Date();
        //20240520 lujunxia upd s
        //$(".FrmGENKAIMAKE.cboYM").datepicker("setDate", currentYM);
        $(".FrmGENKAIMAKE.cboYM").ympicker("setDate", currentYM);
        //20240520 lujunxia upd e
        me.frmGenkaiMake_Load();
        $(".FrmGENKAIMAKE.cmdAct").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmGENKAIMAKE.cboYM").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmGENKAIMAKE.cboYM")) == false)
        if (me.clsComFnc.CheckDate3($(".FrmGENKAIMAKE.cboYM")) == false) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmGENKAIMAKE.cboYM").val(me.cboYM);
                $(".FrmGENKAIMAKE.cboYM").trigger("focus");
                $(".FrmGENKAIMAKE.cboYM").select();
                $(".FrmGENKAIMAKE.cmdAct").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmGENKAIMAKE.cmdAct").button("enable");
        }
    });

    $(".FrmGENKAIMAKE.cboYM").on("keydown", function (e) {
        var key = e.which;
        if (key == 9 || key == 13) {
            $(".FrmGENKAIMAKE.cmdAct").button("enable");
            $(".FrmGENKAIMAKE.cmdAct").trigger("focus");
        }
    });

    me.frmGenkaiMake_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "frmGenkaiMake_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150922 yin upd S
                    //$(".FrmGENKAIMAKE.cboYM").val(result['data'][0]['TOUGETU'].substr(0, 7));
                    //me.cboYM = result['data'][0]['TOUGETU'].substr(0, 7);
                    $(".FrmGENKAIMAKE.cboYM").val(
                        result["data"][0]["TOUGETU"]
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin upd E
                } else {
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

    $(".FrmGENKAIMAKE.cmdAct").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncCmdAct;
        me.clsComFnc.FncMsgBox("QY005");
    });
    me.fncCmdAct = function () {
        //var url = me.sys_id + '/' + 'FrmGENKAIMAKE/cmdAct_Click';
        var url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";
        var data = $(".FrmGENKAIMAKE.cboYM").val().replace("/", "");
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
                if (result["data"] == "I0005") {
                    me.clsComFnc.FncMsgBox("I0005");
                    return;
                }
            }
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmGENKAIMAKE = new R4.FrmGENKAIMAKE();
    o_R4_FrmGENKAIMAKE.load();
});
