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

Namespace.register("KRSS.FrmKanrRank");

KRSS.FrmKanrRank = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmKanrRank";
    me.sys_id = "KRSS";
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    //期
    me.strKI = "";
    //当月年月
    me.cboYM = "";

    me.dblNinzu = 0;
    me.dblDaisu = 0;

    //DOM当月年月
    me.DomCboYM = $(".KRSS.FrmKanrRank.cboYM");
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".KRSS.FrmKanrRank.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmKanrRank.cancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmKanrRank.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_load = me.load;
    me.load = function () {
        base_load();
        me.frmKanrSyukei_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理年月 blur. when not YYYYmm,changeto initial the value.
    me.DomCboYM.on("blur", function () {
        if (me.clsComFnc.CheckDate3($(this)) == false) {
            $(this).val(me.cboYM);
            $(this).trigger("focus");
            return;
        }
    });

    //**********************************************************************
    //処理説明：実行ボタン押下時       excel出力処理を実行する
    //**********************************************************************
    $(".KRSS.FrmKanrRank.cmdAction").click(function () {
        me.dblNinzu = 0;
        me.dblDaisu = 0;
        //入力ﾁｪｯｸ
        me.fncInputCheck();
    });

    //**********************************************************************
    //処理説明：キャンセルボタン押下時    画面を初期表示状態に戻す
    //**********************************************************************
    $(".KRSS.FrmKanrRank.cancel").click(function () {
        //既定では、新車の選択
        $(".KRSS.FrmKanrRank.NEW").prop("checked", true);
        me.frmKanrSyukei_Load();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmKanrSyukei_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmKanrSyukei_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "frmKanrSyukei_Load";
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //コンボボックスに当月年月を設定
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substring(0, 7)
                        .replace(/\//, "");
                    me.DomCboYM.val(me.cboYM);
                    //期を変数に格納
                    me.strKI = me.clsComFnc.FncNv(result["data"][0]["KI"]);
                    me.DomCboYM.trigger("focus");
                }
                //コントロールマスタが存在していない場合
                else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    me.DomCboYM.ympicker("setDate", new Date());
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ランキングリストを印刷する
    //**********************************************************************
    me.cmdAction_Click = function () {
        var cboYM = me.DomCboYM.val();
        //期首を経理コントロールマスタの値から入力した処理年月の値から期首を自動計算するように変更
        if (cboYM.substring(4, 6) >= 10) {
            var strKisyuYMD = cboYM.substring(0, 4) + "/10/01";
            me.strKI = (cboYM.substring(0, 4) - 1917).toString();
        } else {
            var strKisyuYMD = cboYM.substring(0, 4) - 1 + "/10/01";
            me.strKI = (cboYM.substring(0, 4) - 1 - 1917).toString();
        }

        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        //flag=1  種類＝新車の場合
        //flag=2  種類＝中古車の場合
        //flag=3  種類＝整備の場合
        var data = {
            flag: $("input:radio:checked").val(),
            cboYM:
                me.DomCboYM.val().substring(0, 4) +
                "/" +
                me.DomCboYM.val().substring(4, 6),
            strKisyuYMD: strKisyuYMD,
            strKI: me.strKI,
            dblNinzu: me.dblNinzu,
            dblDaisu: me.dblDaisu,
        };
        //console.log(data);
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    window.location.href = result["data"];
                } else {
                    me.clsComFnc.ObjFocus = me.DomCboYM;
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：入力ﾁｪｯｸ
    //関 数 名：fncInputCheck
    //引    数：無し
    //戻 り 値：
    //処理説明：入力ﾁｪｯｸ
    //**********************************************************************
    me.fncInputCheck = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncRankingDataSel";
        var data = me.DomCboYM.val();
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox(
                        "ランキング用データ入力が行われておりません。管理者にご連絡ください！"
                    );
                    return;
                }
                me.dblNinzu = me.clsComFnc.FncNz(
                    result["data"][0]["ATHER_JININ"]
                );
                var flag = $("input:radio:checked").val();
                switch (flag) {
                    //新車
                    case "1":
                        me.dblDaisu = me.clsComFnc.FncNz(
                            result["data"][0]["SINSYA_DAISU"]
                        );
                        break;
                    //中古車
                    case "2":
                        me.dblDaisu = me.clsComFnc.FncNz(
                            result["data"][0]["CHUKO_DAISU"]
                        );
                        break;
                    //整備
                    case "3":
                        me.dblDaisu = me.clsComFnc.FncNz(
                            result["data"][0]["SEIBI_JININ"]
                        );
                        break;
                }
                me.cmdAction_Click();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmKanrRank = new KRSS.FrmKanrRank();
    o_KRSS_FrmKanrRank.load();
});
