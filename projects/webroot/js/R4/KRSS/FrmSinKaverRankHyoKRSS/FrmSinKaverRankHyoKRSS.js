/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmSinKaverRankHyoKRSS");

KRSS.FrmSinKaverRankHyoKRSS = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.id = "FrmSinKaverRankHyoKRSS";
    me.sys_id = "KRSS";
    me.url = "";
    me.data = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSinKaverRankHyoKRSS.cmdExcelOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinKaverRankHyoKRSS.cboYMStart",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinKaverRankHyoKRSS.cboYMEnd",
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
    // = メソッド start =
    // = Load
    // ==========
    me.init_control = function () {
        base_init_control();

        me.frmKanrSyukei_Load();
    };

    me.frmKanrSyukei_Load = function () {
        //年月コントロールマスタ存在ﾁｪｯｸ
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".FrmSinKaverRankHyoKRSS.cboYMStart").val(tmpNowDate);
        $(".FrmSinKaverRankHyoKRSS.cboYMEnd").val(tmpNowDate);

        me.url = me.sys_id + "/" + me.id + "/frmKanrSyukei_Load";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //コントロールマスタが存在していない場合
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
            } else {
                //'コンボボックスに当月年月を設定
                var strTougetu = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();
                strTougetu = strTougetu.split("/");
                $(".FrmSinKaverRankHyoKRSS.cboYMEnd").val(
                    strTougetu[0] + strTougetu[1]
                );
                me.cboYMEnd = strTougetu[0] + strTougetu[1];

                //期首年月を変数に格納
                me.strKisyuYM = clsComFnc
                    .FncNv(result["data"][0]["KISYU"])
                    .toString()
                    .padRight(8)
                    .substring(0, 6);
                $(".FrmSinKaverRankHyoKRSS.cboYMStart").val(
                    clsComFnc
                        .FncNv(result["data"][0]["KISYU"])
                        .substring(0, 4) +
                        clsComFnc
                            .FncNv(result["data"][0]["KISYU"])
                            .substring(4, 6)
                );
                me.cboYMStart = $(".FrmSinKaverRankHyoKRSS.cboYMStart").val();
                //期を変数に格納
                me.strKI = clsComFnc.FncNv(result["data"][0]["KI"]);
                $(".FrmSinKaverRankHyoKRSS.cboYMStart").trigger("focus");
            }
        };

        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //処理年月の処理
    $(".FrmSinKaverRankHyoKRSS.cboYMEnd").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmSinKaverRankHyoKRSS.cboYMEnd")) == false
        ) {
            $(".FrmSinKaverRankHyoKRSS.cboYMEnd").val(me.cboYMEnd);
            $(".FrmSinKaverRankHyoKRSS.cboYMEnd").trigger("focus");
        } else {
            me.cboYMEnd = $(".FrmSinKaverRankHyoKRSS.cboYMEnd").val();

            var YEnd = $(".FrmSinKaverRankHyoKRSS.cboYMEnd")
                .val()
                .substring(0, 4);
            var MEnd = $(".FrmSinKaverRankHyoKRSS.cboYMEnd")
                .val()
                .substring(4, 6);
            $(".FrmSinKaverRankHyoKRSS.cboYMEnd").val(YEnd + MEnd);
            console.log(me.cboYMEnd);
        }
    });

    $(".FrmSinKaverRankHyoKRSS.cboYMStart").on("blur", function () {
        if (
            clsComFnc.CheckDate3($(".FrmSinKaverRankHyoKRSS.cboYMStart")) ==
            false
        ) {
            $(".FrmSinKaverRankHyoKRSS.cboYMStart").val(me.cboYMStart);
            $(".FrmSinKaverRankHyoKRSS.cboYMStart").trigger("focus");
        } else {
            me.cboYMStart = $(".FrmSinKaverRankHyoKRSS.cboYMStart").val();

            var YEnd = $(".FrmSinKaverRankHyoKRSS.cboYMStart")
                .val()
                .substring(0, 4);
            var MEnd = $(".FrmSinKaverRankHyoKRSS.cboYMStart")
                .val()
                .substring(4, 6);
            $(".FrmSinKaverRankHyoKRSS.cboYMStart").val(YEnd + MEnd);
            console.log(me.cboYMStart);
        }
    });

    //Excelファイルを出力
    $(".FrmSinKaverRankHyoKRSS.cmdExcelOut").click(function () {
        var YMStart = $(".FrmSinKaverRankHyoKRSS.cboYMStart")
            .val()
            .replace("/", "");
        var YMEnd = $(".FrmSinKaverRankHyoKRSS.cboYMEnd")
            .val()
            .replace("/", "");

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmSinKaverRankHyoKRSS.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }

        me.rad = "";
        if ($(".FrmSinKaverRankHyoKRSS.radRanking").prop("checked") == true) {
            me.rad = $(".FrmSinKaverRankHyoKRSS.radRanking").val();
            me.Filename = "KoteihiKaverRtRank_New";
        } else if (
            $(".FrmSinKaverRankHyoKRSS.radYachin").prop("checked") == true
        ) {
            me.rad = $(".FrmSinKaverRankHyoKRSS.radYachin").val();
            me.Filename = "KoteihiKaverRtRank_New_Yachin";
        } else {
            me.rad = $(".FrmSinKaverRankHyoKRSS.radBusyo").val();
            me.Filename = "KoteihiKaverRtRank_New_Busyo";
        }

        me.url = me.sys_id + "/" + me.id + "/fileReadDialog";

        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: YMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmSinKaverRankHyoKRSS.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmSinKaverRankHyoKRSS.radYachin").prop(
                "checked"
            ),
            radBusyoCheck: $(".FrmSinKaverRankHyoKRSS.radBusyo").prop(
                "checked"
            ),
            fileName: me.Filename,
            Rank: $(".FrmSinKaverRankHyoKRSS.txtRank").val(),
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
                        ".FrmSinKaverRankHyoKRSS.cboYMStart"
                    );
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //clsComFnc.MsgBoxBtnFnc.Yes = me.fncYes;

            me.herf = result["data"];

            clsComFnc.ObjFocus = $(".FrmSinKaverRankHyoKRSS.cboYMStart");
            clsComFnc.FncMsgBox("I0011");
            $(".FrmSinKaverRankHyoKRSS.txtRank").val("");

            window.location.href = me.herf;
        };

        ajax.send(me.url, me.data, 0);
    });
    // ==========
    // = イベント end =
    // ==========

    return me;
};

$(function () {
    var o_KRSS_FrmSinKaverRankHyoKRSS = new KRSS.FrmSinKaverRankHyoKRSS();
    o_KRSS_FrmSinKaverRankHyoKRSS.load();
});
