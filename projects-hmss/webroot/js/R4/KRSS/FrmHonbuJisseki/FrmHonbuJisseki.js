/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmHonbuJisseki");

KRSS.FrmHonbuJisseki = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    me.id = "KRSS/FrmHonbuJisseki";
    me.cboYearMonth = ".KRSS.FrmHonbuJisseki.cboYM";
    //当月年月
    me.strTougetu = "";
    //期
    me.strKI = "";
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmHonbuJisseki.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmHonbuJisseki.cboYM",
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

    // ==========
    // = イベント start =
    // ==========
    $(me.cboYearMonth).on("blur", function () {
        if (!me.clsComFnc.CheckDate3($(this))) {
            $(this).val(me.strTougetu);
            $(this).trigger("focus");
            $(this).select();
            return;
        }
    });

    $(".KRSS.FrmHonbuJisseki.cmdAction").click(function () {
        me.cmdAction_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.load = function () {
        base_load();

        var url = me.id + "/frmGetYearMonth";
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            $(me.cboYearMonth).ympicker("setDate", new Date());

            if (result["result"] == true) {
                //コントロールマスタが存在してる場合は年度に期首年月を設定
                if (result["data"].length > 0) {
                    me.strTougetu = me.clsComFnc
                        .FncNv(result["data"][0]["TOUGETU"])
                        .substring(0, 7)
                        .replace(/\//, "");
                    $(me.cboYearMonth).val(me.strTougetu);
                    $(me.cboYearMonth).trigger("focus");
                    //期を変数に格納
                    me.strKI = me.clsComFnc.FncNv(result["data"][0]["KI"]);
                } else {
                    //コントロールマスタが存在していない場合
                    $("#KRSS_FrmHonbuJisseki").block();
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            } else {
                $("#KRSS_FrmHonbuJisseki").block();
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 1);
    };

    me.cmdAction_Click = function () {
        //部署別実績ﾜｰｸ集計処理
        var url = me.id + "/cmdAction";
        var data = $(me.cboYearMonth).val();
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    window.location.href = result["data"];
                } else {
                    me.clsComFnc.ObjFocus = $(me.cboYearMonth);
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // ==========
    // = 宣言 end =
    // ==========

    return me;
};

$(function () {
    var o_KRSS_FrmHonbuJisseki = new KRSS.FrmHonbuJisseki();
    o_KRSS_FrmHonbuJisseki.load();
});
