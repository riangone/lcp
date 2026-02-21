/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20160119           #2290                        BUG                              LI
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmSalesJskList");

KRSS.FrmSalesJskList = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.MessageBox = new gdmz.common.MessageBox();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.cboYM = "";

    // ========== 変数 end ==========
    me.sys_id = "KRSS";
    me.id = "FrmSalesJskList";
    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmSalesJskList.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmSalesJskList.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========
    //**********************************************************************
    //処理説明：印刷ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmSalesJskList.cmdAction").click(function () {
        me.cmdAction_Click();
    });
    $(".KRSS.FrmSalesJskList.cboYM").on("blur", function () {
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmSalesJskList.cboYM")) == false
        ) {
            $(".KRSS.FrmSalesJskList.cboYM").trigger("focus");
            $(".KRSS.FrmSalesJskList.cboYM").val(me.cboYM);
        }
    });
    //**********************************************************************
    //処 理 名：Excel印刷
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：Excel印刷
    //**********************************************************************
    me.cmdAction_Click = function () {
        var tmpurl = me.sys_id + "/" + me.id + "/cmdAction_Click";
        var ym =
            $(".KRSS.FrmSalesJskList.cboYM").val().substring(0, 4) +
            "/" +
            $(".KRSS.FrmSalesJskList.cboYM").val().substring(4, 6);
        var data = {
            cboYM: ym,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                window.location.href = result["data"];
            }
        };
        me.ajax.send(tmpurl, data, 0);
    };
    // ==========
    // = 宣言 end =
    // ==========

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    var base_load = me.load;
    //**********************************************************************
    //処 理 名：画面初始化
    //関 数 名：load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面初始化
    //**********************************************************************
    me.load = function () {
        base_load();
        me.FrmLoad();
    };
    //**********************************************************************
    //処 理 名：画面初始化
    //関 数 名：FrmLoad
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面初始化
    //**********************************************************************
    me.FrmLoad = function () {
        var tmpurl = me.sys_id + "/" + me.id + "/frmKanrSyukei_Load";
        data = {};
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                $("#FrmSalesJskList").block();
            } else {
                var strTougetu = result["data"][0]["TOUGETU"].substring(0, 6);
                me.cboYM = strTougetu;
                $(".KRSS.FrmSalesJskList.cboYM").val(strTougetu);
                //期を変数に格納
                me.strKI = me.clsComFnc.FncNv(result["data"][0]["KI"]);
                //-- 20160119 li UPD S.
                // $(".KRSS.FrmSinKaverRankHyo.cboYM").focus();
                $(".KRSS.FrmSinKaverRankHyoKRSS.cboYM").trigger("focus");
                //-- 20160119 li UPD E.
            }
        };
        me.ajax.send(tmpurl, data, 0);
    };

    return me;
};
$(function () {
    var o_KRSS_FrmSalesJskList = new KRSS.FrmSalesJskList();
    o_KRSS_FrmSalesJskList.load();
});
