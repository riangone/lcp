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
 * 20151027           #2241                        BUG                              LI
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmKanrSyukei");

R4.FrmKanrSyukei = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmKanrSyukei";
    me.sys_id = "R4K";
    me.url = "";

    me.data = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKanrSyukei.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKanrSyukei.cboYM",
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
    $(".FrmKanrSyukei.cboYM").on("blur", function () {
        //20150923 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmKanrSyukei.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmKanrSyukei.cboYM")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmKanrSyukei.cboYM").val(me.cboYM);
                $(".FrmKanrSyukei.cboYM").trigger("focus");
                $(".FrmKanrSyukei.cboYM").select();
                $(".FrmKanrSyukei.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmKanrSyukei.cmdAction").button("enable");
        }
    });

    me.init_control = function () {
        base_init_control();
        me.frmKanrSyukei_Load();
    };

    $(".FrmKanrSyukei.cmdAction").click(function () {
        me.subClearForm();
        me.cmdAction();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.frmKanrSyukei_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();

        //コントロールマスタ存在ﾁｪｯｸ
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        $(".FrmKanrSyukei.cboYM").val(tmpNowDate);
        me.cboYM = tmpNowDate;
        me.url = me.sys_id + "/" + me.id + "/frmKanrSyukei_Load";

        ajax.receive = function (result) {
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
                $(".FrmKanrSyukei.cboYM").val(strTougetu[0] + strTougetu[1]);
                me.cboYM = strTougetu[0] + strTougetu[1];
                $(".FrmKanrSyukei.cboYM").trigger("focus");
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.subClearForm = function () {
        $(".FrmKanrSyukei.lblFurikaeReadCnt").val("");
        $(".FrmKanrSyukei.lblKaikeiReadCnt").val("");
        $(".FrmKanrSyukei.lblKariGKSum").val("");
        $(".FrmKanrSyukei.lblKasiGKSum").val("");
    };

    me.cmdAction = function () {
        $(".FrmKanrSyukei.lblMSG").html("エラーデータチェック処理中です。");

        me.url = me.sys_id + "/" + me.id + "/fncSiwakeErrPrintSelect";

        var val = $(".FrmKanrSyukei.cboYM").val();

        var arrayVal = {
            YM: val,
            checked: $(".FrmKanrSyukei.chkPrint").prop("checked"),
        };

        me.data = {
            request: arrayVal,
        };

        // me.data =
        // {
        // request : arrayVal
        // };
        //
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["result"] == true) {
                if (result["MsgId"] == "01") {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncAction;
                    clsComFnc.MessageBox(
                        "会計データ/振替データにエラーデータが存在します。集計処理を続行しますか？",
                        clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Warning",
                        clsComFnc.MessageBoxDefaultButton.Button1
                    );
                    $(".FrmKanrSyukei.lblMSG").html("処理を中断しました。");
                    window.open(result["path"]);
                    return;
                } else {
                    me.fncAction();
                }
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncAction = function () {
        $(".FrmKanrSyukei.lblMSG").html(
            "当月売上基準会計データ作成処理中です。"
        );

        me.url = me.sys_id + "/" + me.id + "/fncAction";

        var val = $(".FrmKanrSyukei.cboYM").val();

        var arrayVal = {
            YM: val,
            checked: $(".FrmKanrSyukei.chkPrint").prop("checked"),
        };

        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["MsgId"] == "E9999") {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["MsgId"] == "I0001") {
                    clsComFnc.FncMsgBox("I0001");
                    return;
                }
            }
            if (result["result"] == true) {
                if (result["path4"] != "") {
                    window.open(result["path4"]);
                }
                //-- 20151027 li UPD S.
                // $(".FrmKanrSyukei.lblFurikaeReadCnt").val(result['num1']);
                // $(".FrmKanrSyukei.lblKaikeiReadCnt").val(result['num2']);
                $(".FrmKanrSyukei.lblKaikeiReadCnt").val(result["num1"]);
                $(".FrmKanrSyukei.lblFurikaeReadCnt").val(result["num2"]);
                //-- 20151027 li UPD E.
                $(".FrmKanrSyukei.lblKariGKSum").val(result["strKariGKCnt"]);
                $(".FrmKanrSyukei.lblKasiGKSum").val(result["strKasiGKCnt"]);
                $(".FrmKanrSyukei.lblMSG").html(
                    "部署別集計処理が終了しました。"
                );
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
    var o_R4_FrmKanrSyukei = new R4.FrmKanrSyukei();
    o_R4_FrmKanrSyukei.load();
});
