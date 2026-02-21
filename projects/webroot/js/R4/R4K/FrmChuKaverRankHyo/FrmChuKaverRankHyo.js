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
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。 WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmChuKaverRankHyo");

R4.FrmChuKaverRankHyo = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmChuKaverRankHyo";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmChuKaverRankHyo.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmChuKaverRankHyo.cmdExcelOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmChuKaverRankHyo.cboYMStart",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    me.controls.push({
        id: ".FrmChuKaverRankHyo.cboYMEnd",
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

    $(".FrmChuKaverRankHyo.cboYMEnd").change(function () {
        if (clsComFnc.CheckDate3($(".FrmChuKaverRankHyo.cboYMEnd")) == false) {
            $(".FrmChuKaverRankHyo.cboYMEnd").val(me.cboYMEnd1);
        } else {
            me.cboYMEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val();
            var YEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val().substring(0, 4);
            var MEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val().substring(4, 6);
            var DEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val().substring(6, 8);
            // console.log(DEnd);
            if (DEnd == "") {
                me.cboYMEnd = me.cboYMEnd + "/01";
                DEnd = "01";
            }
            $(".FrmChuKaverRankHyo.cboYMEnd").val(YEnd + MEnd);
            // console.log(me.cboYMEnd);
        }
    });

    $(".FrmChuKaverRankHyo.cboYMStart").on("blur", function () {
        //20150923 yin upd S
        // if (clsComFnc.CheckDate2($(".FrmKaverRankSyukei.cboYM")) == false)
        if (
            clsComFnc.CheckDate3($(".FrmChuKaverRankHyo.cboYMStart")) == false
        ) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmChuKaverRankHyo.cboYMStart").val(me.cboYMStart);
                $(".FrmChuKaverRankHyo.cboYMStart").trigger("focus");
                $(".FrmChuKaverRankHyo.cboYMStart").select();
                $(".FrmChuKaverRankHyo.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmChuKaverRankHyo.cmdAction").button("enable");
            $(".FrmChuKaverRankHyo.cmdExcelOut").button("enable");
        }
    });

    $(".FrmChuKaverRankHyo.cmdAction").click(function () {
        me.rad = "";
        if ($(".FrmChuKaverRankHyo.radRanking").prop("checked") == true) {
            me.rad = $(".FrmChuKaverRankHyo.radRanking").val();
        } else if ($(".FrmChuKaverRankHyo.radYachin").prop("checked") == true) {
            me.rad = $(".FrmChuKaverRankHyo.radYachin").val();
        } else {
            me.rad = $(".FrmChuKaverRankHyo.radBusyo").val();
        }

        var YMStart = $(".FrmChuKaverRankHyo.cboYMStart")
            .val()
            .replace("/", "");
        var YMEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val().replace("/", "");

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmChuKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/printTyugo";

        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: me.cboYMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmChuKaverRankHyo.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmChuKaverRankHyo.radYachin").prop("checked"),
            radBusyoCheck: $(".FrmChuKaverRankHyo.radBusyo").prop("checked"),
            fileName: me.Filename,
            Rank: $(".FrmChuKaverRankHyo.txtRank").val(),
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
                if (result["errMsg"] == "I0001") {
                    $(".FrmChuKaverRankHyo.txtRank").val("");
                    $(".FrmChuKaverRankHyo.radRanking").prop(
                        "checked",
                        "checked"
                    );
                    clsComFnc.ObjFocus = $(".FrmChuKaverRankHyo.cboYMStart");
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            $(".FrmChuKaverRankHyo.cboYMStart").trigger("focus");
            $(".FrmChuKaverRankHyo.txtRank").val("");
            $(".FrmChuKaverRankHyo.radRanking").prop("checked", "checked");
            window.open(result["path"]);
        };

        ajax.send(me.url, me.data, 0);
    });

    $(".FrmChuKaverRankHyo.cmdExcelOut").click(function () {
        me.rad = "";
        if ($(".FrmChuKaverRankHyo.radRanking").prop("checked") == true) {
            me.rad = $(".FrmChuKaverRankHyo.radRanking").val();
            me.Filename = "KoteihiKaverRtRank_Used.xls";
        } else if ($(".FrmChuKaverRankHyo.radYachin").prop("checked") == true) {
            me.rad = $(".FrmChuKaverRankHyo.radYachin").val();
            me.Filename = "KoteihiKaverRtRank_Used_Yachin.xls";
        } else {
            me.rad = $(".FrmChuKaverRankHyo.radBusyo").val();
            me.Filename = "KoteihiKaverRtRank_Used_Busyo.xls";
        }

        var YMStart = $(".FrmChuKaverRankHyo.cboYMStart")
            .val()
            .replace("/", "");
        var YMEnd = $(".FrmChuKaverRankHyo.cboYMEnd").val().replace("/", "");

        if (YMStart > YMEnd) {
            clsComFnc.ObjFocus = $(".FrmChuKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/fileReadDialog";
        var arr = {
            cboYMStart: YMStart,
            cboYMEnd: me.cboYMEnd,
            YMEnd: YMEnd,
            radRankingCheck: $(".FrmChuKaverRankHyo.radRanking").prop(
                "checked"
            ),
            radYachinCheck: $(".FrmChuKaverRankHyo.radYachin").prop("checked"),
            radBusyoCheck: $(".FrmChuKaverRankHyo.radBusyo").prop("checked"),
            fileName: me.Filename,
            Rank: $(".FrmChuKaverRankHyo.txtRank").val(),
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
                    $(".FrmChuKaverRankHyo.txtRank").val("");
                    $(".FrmChuKaverRankHyo.radRanking").prop(
                        "checked",
                        "checked"
                    );
                    clsComFnc.ObjFocus = $(".FrmChuKaverRankHyo.cboYMStart");
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            clsComFnc.ObjFocus = $(".FrmChuKaverRankHyo.cboYMStart");
            clsComFnc.FncMsgBox("I0011");
            $(".FrmChuKaverRankHyo.txtRank").val("");
            $(".FrmChuKaverRankHyo.radRanking").prop("checked", "checked");
            //20181026 YIN INS S
            downloadExcel = 0;
            //20181026 YIN INS E
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
        // var currentYM = new Date();
        // $(".FrmChuKaverRankHyo.cboYMStart").datepicker("setDate", currentYM);
        // $(".FrmChuKaverRankHyo.cboYMEnd").datepicker("setDate", currentYM);

        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".FrmChuKaverRankHyo.cboYMStart").val(tmpNowDate);
        $(".FrmChuKaverRankHyo.cboYMEnd").val(tmpNowDate);
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
                $(".FrmChuKaverRankHyo.cboYMEnd").val(
                    strTougetu[0] + strTougetu[1]
                );
                me.cboYMEnd = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();
                me.cboYMEnd1 = strTougetu[0] + strTougetu[1];

                //期首年月を変数に格納
                me.strKisyuYM = clsComFnc
                    .FncNv(result["data"][0]["KISYU"])
                    .toString()
                    .padRight(8)
                    .substr(0, 6);
                me.strKisyuYMD =
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substr(0, 4) +
                    "/" +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substr(4, 2) +
                    "/" +
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substr(6, 2);
                $(".FrmChuKaverRankHyo.cboYMStart").val(
                    clsComFnc.FncNv(result["data"][0]["KISYU"]).substr(0, 4) +
                        clsComFnc.FncNv(result["data"][0]["KISYU"]).substr(4, 2)
                );
                me.cboYMStart = $(".FrmChuKaverRankHyo.cboYMStart").val();
                //期を変数に格納
                me.strKI = clsComFnc.FncNv(result["data"][0]["KI"]);
                $(".FrmChuKaverRankHyo.cboYMStart").trigger("focus");
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
    var o_R4_FrmChuKaverRankHyo = new R4.FrmChuKaverRankHyo();
    o_R4_FrmChuKaverRankHyo.load();
});
