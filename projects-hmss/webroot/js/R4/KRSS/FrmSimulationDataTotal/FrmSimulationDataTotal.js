/**
 * 説明：
 *
 *
 * @author fuxiaolin
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmSimulationDataTotal");

KRSS.FrmSimulationDataTotal = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.sys_id = "KRSS";
    me.id = "FrmSimulationDataTotal";
    me.data = new Array();
    clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmSimulationDataTotal.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmSimulationDataTotal.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =

    // ==========
    // = イベント start =
    // ==========
    $(".KRSS.FrmSimulationDataTotal.cboYM").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".KRSS.FrmSimulationDataTotal.cboYM")) ==
            false
        ) {
            $(".KRSS.FrmSimulationDataTotal.cboYM").val(me.cboYMInit);
            $(".KRSS.FrmSimulationDataTotal.cboYM").trigger("focus");
        }
    });

    $(".KRSS.FrmSimulationDataTotal.cmdAction").click(function () {
        me.cmdSearch_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;

    me.load = function () {
        base_load();
        me.frmSimulationDataTotal_Load();
    };

    me.frmSimulationDataTotal_Load = function () {
        //年月コントロールマスタ存在ﾁｪｯｸ
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".KRSS.FrmSimulationDataTotal.cboYM").val(tmpNowDate);

        var urlKrss = me.sys_id + "/" + me.id + "/frmSimulationDataTotalLoad";

        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                $(".KRSS.FrmSimulationDataTotal.cmdAction").button("disable");
            } else {
                var strTougetu = result["data"][0]["TOUGETU"];
                strTougetu = strTougetu.split("/");
                $(".KRSS.FrmSimulationDataTotal.cboYM").val(
                    strTougetu[0] + strTougetu[1]
                );
                $(".KRSS.FrmSimulationDataTotal.cboYM").trigger("focus");
                me.cboYMInit = strTougetu[0] + strTougetu[1];
            }
        };
        ajax.send(urlKrss, me.data, 0);
    };

    me.cmdSearch_Click = function () {
        var urlKrss = me.sys_id + "/" + me.id + "/cmdSearchClick";

        var arr = {
            cboYM: $(".KRSS.FrmSimulationDataTotal.cboYM").val(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "部署別実績集計が行われていません！先に部署別実績集計を行って下さい。"
                );
            } else {
                clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
                clsComFnc.FncMsgBox("QY005");
            }
        };
        ajax.send(urlKrss, me.data, 0);
    };

    me.cmdAct_Click = function () {
        var urlKrss = me.sys_id + "/" + me.id + "/cmdActClick";

        var arr = {
            cboYM: $(".KRSS.FrmSimulationDataTotal.cboYM").val(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                clsComFnc.FncMsgBox("I0005");
            }
        };
        ajax.send(urlKrss, me.data, 0);
    };

    return me;

    // ==========
    // = メソッド end =
    // ==========
};


$(function () {
    var o_KRSS_FrmSimulationDataTotal = new KRSS.FrmSimulationDataTotal();
    o_KRSS_FrmSimulationDataTotal.load();
});
