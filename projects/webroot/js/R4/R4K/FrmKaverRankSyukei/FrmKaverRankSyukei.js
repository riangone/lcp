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

Namespace.register("R4.FrmKaverRankSyukei");

R4.FrmKaverRankSyukei = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();
    me.id = "FrmKaverRankSyukei";
    me.sys_id = "R4K";
    me.cboYM = "";
    me.url = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKaverRankSyukei.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKaverRankSyukei.cboYM",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //コンボボックスに初期値設定
        var currentYM = new Date();
        $(".FrmKaverRankSyukei.cboYM").ympicker("setDate", currentYM);
        me.frmGenkaiMake_Load();
        $(".FrmKaverRankSyukei.cmdAct").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmKaverRankSyukei.cboYM").on("blur", function () {
        //20150923 yin upd S
        // if (clsComFnc.CheckDate2($(".FrmKaverRankSyukei.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmKaverRankSyukei.cboYM")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmKaverRankSyukei.cboYM").val(me.cboYM);
                $(".FrmKaverRankSyukei.cboYM").trigger("focus");
                $(".FrmKaverRankSyukei.cboYM").select();
                $(".FrmKaverRankSyukei.cmdAct").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmKaverRankSyukei.cmdAct").button("enable");
        }
    });

    $(".FrmKaverRankSyukei.cboYM").on("keydown", function (e) {
        var key = e.which;
        if (key == 9 || key == 13) {
            $(".FrmKaverRankSyukei.cmdAct").button("enable");
            $(".FrmKaverRankSyukei.cmdAct").trigger("focus");
        }
    });

    me.frmGenkaiMake_Load = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "frmGenkaiMake_Load";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150923 yin upd S
                    // $(".FrmKaverRankSyukei.cboYM").val(result["data"][0]["TOUGETU"].substr(0, 7));
                    // me.cboYM = result["data"][0]["TOUGETU"].substr(0, 7);
                    $(".FrmKaverRankSyukei.cboYM").val(
                        result["data"][0]["TOUGETU"]
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150923 yin upd E
                } else {
                    clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(me.url, "", 0);
    };

    $(".FrmKaverRankSyukei.cmdAct").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        clsComFnc.FncMsgBox("QY005");
    });

    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "fncExistsCheck";

        me.data = $(".FrmKaverRankSyukei.cboYM").val().replace("/", "");

        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                switch (result["data"]) {
                    case "E0001":
                        clsComFnc.FncMsgBox("W0008", "人件費エクセルデータ");
                        break;
                    case "E0002":
                        clsComFnc.FncMsgBox(
                            "W0008",
                            "自賠責件数エクセルデータ"
                        );
                        break;
                    case "E0003":
                        clsComFnc.FncMsgBox("W0008", "任意保険エクセルデータ");
                        break;
                    case "E0004":
                        clsComFnc.FncMsgBox("W0008", "経験年数エクセルデータ");
                        break;
                    default:
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        break;
                }

                return;
            } else {
                me.url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";

                ajax.receive = function (result) {
                    // console.log(result);
                    result = eval("(" + result + ")");

                    if (result["result"] == false) {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    clsComFnc.FncMsgBox("I0005");
                };

                ajax.send(me.url, me.data, 0);
            }
        };
        ajax.send(me.url, me.data, 0);
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
    var o_R4_FrmKaverRankSyukei = new R4.FrmKaverRankSyukei();
    o_R4_FrmKaverRankSyukei.load();
});
