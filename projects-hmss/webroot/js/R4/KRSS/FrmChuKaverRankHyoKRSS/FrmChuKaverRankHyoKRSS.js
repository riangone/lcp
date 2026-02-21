/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("KRSS.FrmChuKaverRankHyoKRSS");

KRSS.FrmChuKaverRankHyoKRSS = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmChuKaverRankHyoKRSS";
    me.sys_id = "KRSS";
    me.url = "";
    me.data = "";
    clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmChuKaverRankHyoKRSS.cmdExcelOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmChuKaverRankHyoKRSS.cboYMStart",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmChuKaverRankHyoKRSS.cboYMEnd",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    me.init_control = function () {
        base_init_control();

        me.frmKanrSyukei_Load();
    };

    $(".FrmChuKaverRankHyoKRSS.cboYMEnd").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmChuKaverRankHyoKRSS.cboYMEnd")) == false
        ) {
            $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val(me.cboYMEnd);
            $(".FrmChuKaverRankHyoKRSS.cboYMEnd").trigger("focus");
        } else {
            me.cboYMEnd = $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val();
            var YEnd = $(".FrmChuKaverRankHyoKRSS.cboYMEnd")
                .val()
                .substring(0, 4);
            var MEnd = $(".FrmChuKaverRankHyoKRSS.cboYMEnd")
                .val()
                .substring(4, 6);
            var DEnd = $(".FrmChuKaverRankHyoKRSS.cboYMEnd")
                .val()
                .substring(6, 8);
            console.log(DEnd);

            $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val(YEnd + MEnd);
            console.log(me.cboYMEnd);
        }
    });

    $(".FrmChuKaverRankHyoKRSS.cboYMStart").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmChuKaverRankHyoKRSS.cboYMStart")) ==
            false
        ) {
            $(".FrmChuKaverRankHyoKRSS.cboYMStart").val(me.cboYMStart);
            $(".FrmChuKaverRankHyoKRSS.cboYMStart").trigger("focus");
        } else {
            me.cboYMStart = $(".FrmChuKaverRankHyoKRSS.cboYMStart").val();
            var YEnd = $(".FrmChuKaverRankHyoKRSS.cboYMStart")
                .val()
                .substring(0, 4);
            var MEnd = $(".FrmChuKaverRankHyoKRSS.cboYMStart")
                .val()
                .substring(4, 6);
            // var DEnd = $(".FrmChuKaverRankHyoKRSS.cboYMStart")
            //     .val()
            //     .substr(6, 2);

            $(".FrmChuKaverRankHyoKRSS.cboYMStart").val(YEnd + MEnd);
            console.log(me.cboYMStart);
        }
    });

    $(".FrmChuKaverRankHyoKRSS.cmdExcelOut").click(function () {
        me.rad = "";
        if ($(".FrmChuKaverRankHyoKRSS.radRanking").prop("checked") == true) {
            me.rad = $(".FrmChuKaverRankHyoKRSS.radRanking").val();
            me.Filename = "KoteihiKaverRtRank_Used";
        } else if (
            $(".FrmChuKaverRankHyoKRSS.radYachin").prop("checked") == true
        ) {
            me.rad = $(".FrmChuKaverRankHyoKRSS.radYachin").val();
            me.Filename = "KoteihiKaverRtRank_Used_Yachin";
        } else {
            me.rad = $(".FrmChuKaverRankHyoKRSS.radBusyo").val();
            me.Filename = "KoteihiKaverRtRank_Used_Busyo";
        }

        var YMStart = $(".FrmChuKaverRankHyoKRSS.cboYMStart").val();
        var YMEnd = $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val();

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmChuKaverRankHyoKRSS.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/fileReadDialog";
        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: YMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmChuKaverRankHyoKRSS.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmChuKaverRankHyoKRSS.radYachin").prop(
                "checked"
            ),
            radBusyoCheck: $(".FrmChuKaverRankHyoKRSS.radBusyo").prop(
                "checked"
            ),
            fileName: me.Filename,
            Rank: $(".FrmChuKaverRankHyoKRSS.txtRank").val(),
            rad1: me.rad,
        };
        me.data = {
            request: arr,
        };
        console.log(me.data);
        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["MsgID"] == "I0001") {
                    clsComFnc.ObjFocus = $(
                        ".FrmChuKaverRankHyoKRSS.cboYMStart"
                    );
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            clsComFnc.ObjFocus = $(".FrmChuKaverRankHyoKRSS.cboYMStart");
            clsComFnc.FncMsgBox("I0011");
            $(".FrmChuKaverRankHyoKRSS.txtRank").val("");
            // $(".FrmChuKaverRankHyoKRSS.radRanking").prop("checked", "checked");
            window.location.href = result["data"];
        };

        ajax.send(me.url, me.data, 0);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.frmKanrSyukei_Load = function () {
        //年月コントロールマスタ存在ﾁｪｯｸ
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".FrmChuKaverRankHyoKRSS.cboYMStart").val(tmpNowDate);
        $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val(tmpNowDate);
        me.url = me.sys_id + "/" + me.id + "/frmKanrSyukei_Load";

        ajax.receive = function (result) {
            //console.log(result);
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
            } else {
                var strTougetu = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();
                strTougetu = strTougetu.split("/");
                $(".FrmChuKaverRankHyoKRSS.cboYMEnd").val(
                    strTougetu[0] + strTougetu[1]
                );
                me.cboYMEnd = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();

                //期首年月を変数に格納
                me.strKisyuYM = clsComFnc
                    .FncNv(result["data"][0]["KISYU"])
                    .toString()
                    .padRight(8)
                    .substring(0, 6);
                me.strKisyuYMD =
                    clsComFnc
                        .FncNv(result["data"][0]["KISYU"])
                        .substring(0, 4) +
                    clsComFnc
                        .FncNv(result["data"][0]["KISYU"])
                        .substring(4, 6) +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(6, 8);
                $(".FrmChuKaverRankHyoKRSS.cboYMStart").val(
                    clsComFnc
                        .FncNv(result["data"][0]["KISYU"])
                        .substring(0, 4) +
                        clsComFnc
                            .FncNv(result["data"][0]["KISYU"])
                            .substring(4, 6)
                );
                me.cboYMStart = $(".FrmChuKaverRankHyoKRSS.cboYMStart").val();
                //期を変数に格納
                me.strKI = clsComFnc.FncNv(result["data"][0]["KI"]);
                $(".FrmChuKaverRankHyoKRSS.cboYMStart").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmChuKaverRankHyoKRSS = new KRSS.FrmChuKaverRankHyoKRSS();
    o_KRSS_FrmChuKaverRankHyoKRSS.load();
});
