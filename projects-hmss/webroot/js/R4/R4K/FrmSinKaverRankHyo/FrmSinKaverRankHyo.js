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

Namespace.register("R4.FrmSinKaverRankHyo");

R4.FrmSinKaverRankHyo = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmSinKaverRankHyo";
    me.sys_id = "R4K";
    me.url = "";
    me.data = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSinKaverRankHyo.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinKaverRankHyo.cmdExcelOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinKaverRankHyo.cboYMStart",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    me.controls.push({
        id: ".FrmSinKaverRankHyo.cboYMEnd",
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

    $(".FrmSinKaverRankHyo.cboYMEnd").change(function () {
        //20150923 yin upd S
        //if (clsComFnc.CheckDate2($(".FrmSinKaverRankHyo.cboYMEnd")) == false)
        if (clsComFnc.CheckDate3($(".FrmSinKaverRankHyo.cboYMEnd")) == false) {
            //20150923 yin upd E
            $(".FrmSinKaverRankHyo.cboYMEnd").val(me.cboYMEnd1);
        } else {
            me.cboYMEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val();

            var YEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val().substring(0, 4);
            var MEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val().substring(4, 6);
            var DEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val().substring(6, 8);
            // console.log(DEnd);
            if (DEnd == "") {
                me.cboYMEnd = me.cboYMEnd + "/01";
                DEnd = "01";
            }
            $(".FrmSinKaverRankHyo.cboYMEnd").val(YEnd + MEnd);
            // console.log(me.cboYMEnd);
        }
    });

    $(".FrmSinKaverRankHyo.cboYMStart").on("blur", function () {
        //20150923 yin upd S
        // if (clsComFnc.CheckDate2($(".FrmKaverRankSyukei.cboYM")) == false)
        if (
            clsComFnc.CheckDate3($(".FrmSinKaverRankHyo.cboYMStart")) == false
        ) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSinKaverRankHyo.cboYMStart").val(me.cboYMStart);
                $(".FrmSinKaverRankHyo.cboYMStart").trigger("focus");
                $(".FrmSinKaverRankHyo.cboYMStart").select();
                $(".FrmSinKaverRankHyo.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSinKaverRankHyo.cmdAction").button("enable");
            $(".FrmSinKaverRankHyo.cmdExcelOut").button("enable");
        }
    });

    $(".FrmSinKaverRankHyo.cmdAction").click(function () {
        me.rad = "";
        if ($(".FrmSinKaverRankHyo.radRanking").prop("checked") == true) {
            me.rad = $(".FrmSinKaverRankHyo.radRanking").val();
        } else if ($(".FrmSinKaverRankHyo.radYachin").prop("checked") == true) {
            me.rad = $(".FrmSinKaverRankHyo.radYachin").val();
        } else {
            me.rad = $(".FrmSinKaverRankHyo.radBusyo").val();
        }

        var YMStart = $(".FrmSinKaverRankHyo.cboYMStart")
            .val()
            .replace("/", "");
        var YMEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val().replace("/", "");

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmSinKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/printSinsya";

        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: me.cboYMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmSinKaverRankHyo.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmSinKaverRankHyo.radYachin").prop("checked"),
            radBusyoCheck: $(".FrmSinKaverRankHyo.radBusyo").prop("checked"),
            fileName: me.Filename,
            Rank: $(".FrmSinKaverRankHyo.txtRank").val(),
            rad1: me.rad,
        };
        me.data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["errMsg"] == "I0001") {
                    $(".FrmSinKaverRankHyo.txtRank").val("");
                    $(".FrmSinKaverRankHyo.radRanking").prop(
                        "checked",
                        "checked"
                    );
                    clsComFnc.ObjFocus = $(".FrmSinKaverRankHyo.cboYMStart");
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            $(".FrmSinKaverRankHyo.cboYMStart").trigger("focus");
            $(".FrmSinKaverRankHyo.txtRank").val("");
            $(".FrmSinKaverRankHyo.radRanking").prop("checked", "checked");
            window.open(result["path"]);
        };

        ajax.send(me.url, me.data, 0);
    });

    $(".FrmSinKaverRankHyo.cmdExcelOut").click(function () {
        me.rad = "";
        if ($(".FrmSinKaverRankHyo.radRanking").prop("checked") == true) {
            me.rad = $(".FrmSinKaverRankHyo.radRanking").val();
            me.Filename = "KoteihiKaverRtRank_New.xls";
        } else if ($(".FrmSinKaverRankHyo.radYachin").prop("checked") == true) {
            me.rad = $(".FrmSinKaverRankHyo.radYachin").val();
            me.Filename = "KoteihiKaverRtRank_New_Yachin.xls";
        } else {
            me.rad = $(".FrmSinKaverRankHyo.radBusyo").val();
            me.Filename = "KoteihiKaverRtRank_New_Busyo.xls";
        }

        var YMStart = $(".FrmSinKaverRankHyo.cboYMStart")
            .val()
            .replace("/", "");
        var YMEnd = $(".FrmSinKaverRankHyo.cboYMEnd").val().replace("/", "");

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmSinKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/fileReadDialog";

        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: me.cboYMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmSinKaverRankHyo.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmSinKaverRankHyo.radYachin").prop("checked"),
            radBusyoCheck: $(".FrmSinKaverRankHyo.radBusyo").prop("checked"),
            fileName: me.Filename,
            Rank: $(".FrmSinKaverRankHyo.txtRank").val(),
            rad1: me.rad,
        };
        me.data = {
            request: arr,
        };
        // console.log(me.data);
        ajax.receive = function (result) {
            // console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["MsgID"] == "I0001") {
                    $(".FrmSinKaverRankHyo.txtRank").val("");
                    $(".FrmSinKaverRankHyo.radRanking").prop(
                        "checked",
                        "checked"
                    );
                    clsComFnc.ObjFocus = $(".FrmSinKaverRankHyo.cboYMStart");
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //clsComFnc.MsgBoxBtnFnc.Yes = me.fncYes;
            me.herf = result["data"];

            clsComFnc.ObjFocus = $(".FrmSinKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("I0011");
            $(".FrmSinKaverRankHyo.txtRank").val("");
            $(".FrmSinKaverRankHyo.radRanking").prop("checked", "checked");

            //20181026 YIN INS S
            downloadExcel = 0;
            //20181026 YIN INS E
            window.location.href = me.herf;
        };

        ajax.send(me.url, me.data, 0);
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.fncYes = function () { };

    me.frmKanrSyukei_Load = function () {
        //年月コントロールマスタ存在ﾁｪｯｸ

        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".FrmSinKaverRankHyo.cboYMStart").val(tmpNowDate);
        $(".FrmSinKaverRankHyo.cboYMEnd").val(tmpNowDate);

        // $(".FrmSinKaverRankHyo.cboYMStart").datepicker("setDate", tmpNowDate);
        // $(".FrmSinKaverRankHyo.cboYMEnd").datepicker("setDate", tmpNowDate);
        me.url = me.sys_id + "/" + me.id + "/frmKanrSyukei_Load";

        ajax.receive = function (result) {
            // console.log(result);
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
                $(".FrmSinKaverRankHyo.cboYMEnd").val(
                    strTougetu[0] + strTougetu[1]
                );
                me.cboYMEnd = strTougetu[0] + "/" + strTougetu[1];
                me.cboYMEnd1 = strTougetu[0] + strTougetu[1];
                //期首年月を変数に格納
                me.strKisyuYM = clsComFnc
                    .FncNv(result["data"][0]["KISYU"])
                    .toString()
                    .padRight(8)
                    .substring(0, 6);
                me.strKisyuYMD =
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(0, 4) +
                    "/" +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(4, 6) +
                    "/" +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(6, 8);
                $(".FrmSinKaverRankHyo.cboYMStart").val(
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(0, 4) +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substring(4, 6)
                );
                me.cboYMStart = $(".FrmSinKaverRankHyo.cboYMStart").val();
                //期を変数に格納
                me.strKI = clsComFnc.FncNv(result["data"][0]["KI"]);
                $(".FrmSinKaverRankHyo.cboYMStart").trigger("focus");
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
    var o_R4_FrmSinKaverRankHyo = new R4.FrmSinKaverRankHyo();
    o_R4_FrmSinKaverRankHyo.load();
});
