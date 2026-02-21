/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150805　　　　　   #2072         Error: ReferenceError: padRight is not defined  FANZHENGZHOU
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmHknSytKaverRt");

KRSS.FrmHknSytKaverRt = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHknSytKaverRt";
    me.sys_id = "KRSS";
    me.cboYM = "";
    me.cboYMTo = "";
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".KRSS.FrmHknSytKaverRt.cmdExcel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmHknSytKaverRt.cboYM",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmHknSytKaverRt.cboYMTo",
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
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".KRSS.FrmHknSytKaverRt.cboYM").on("blur", function () {
        if (me.clsComFnc.CheckDate3($(this)) == false) {
            $(this).val(me.cboYM);
        }
    });

    $(".KRSS.FrmHknSytKaverRt.cboYMTo").on("blur", function () {
        if (me.clsComFnc.CheckDate3($(this)) == false) {
            $(this).val(me.cboYMTo);
        }
    });

    $(".KRSS.FrmHknSytKaverRt.cmdExcel").click(function () {
        me.cmdExcel();
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
        me.FrmLoad();
    };

    /*'**********************************************************************
	 '処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
	 '関 数 名：FrmLoad
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：初期設定
	 '**********************************************************************
	 */
    me.FrmLoad = function () {
        //コントロールマスタ存在ﾁｪｯｸ
        var tmpurl = me.sys_id + "/" + me.id + "/fncHKEIRICTL";
        data = {};
        me.ajax.receive = function (result) {
            var result = $.parseJSON(result);
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmHknSytKaverRt").block();
                return;
            } else {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    $("#KRSS_FrmHknSytKaverRt").block();
                    return;
                } else {
                    //コンボボックスに当月年月を設定
                    var strTougetu = me.clsComFnc.FncNv(
                        result["data"][0]["TOUGETU"]
                    );
                    $(".KRSS.FrmHknSytKaverRt.cboYMTo").val(
                        strTougetu.toString().substring(0, 7).replace(/\//, "")
                    );
                    me.cboYMTo = strTougetu
                        .toString()
                        .substring(0, 7)
                        .replace(/\//, "");
                    //期首年月を変数に格納
                    //---20150805 #2072 fanzhengzhou upd s.
                    //var strKisyuYM = me.clsComFnc.FncNv(me.padRight(result['data'][0]['KISYU'], 8).toString().substr(0, 6));
                    // var strKisyuYM = me.clsComFnc.FncNv(
                    //     result["data"][0]["KISYU"]
                    //         .toString()
                    //         .padRight(8)
                    //         .substr(0, 6)
                    // );
                    //---20150805 #2072 fanzhengzhou upd e.
                    var strKisyuYMD = result["data"][0]["KISYU"]
                        .toString()
                        .substring(0, 6);
                    $(".KRSS.FrmHknSytKaverRt.cboYM").val(strKisyuYMD);
                    me.cboYM = strKisyuYMD;
                    $(".KRSS.FrmHknSytKaverRt.cboYM").trigger("focus");
                }
            }
        };
        me.ajax.send(tmpurl, data, 0);
    };

    /*
	 '**********************************************************************
	 '処 理 名：エクセル出力
	 '関 数 名：Button1_Click
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：エクセルを出力する
	 '**********************************************************************
	 */
    me.cmdExcel = function () {
        //処理年月が期首以前の場合エラー
        if (
            $(".KRSS.FrmHknSytKaverRt.cboYM").val() >
            $(".KRSS.FrmHknSytKaverRt.cboYMTo").val()
        ) {
            me.clsComFnc.ObjFocus = $(".KRSS.FrmHknSytKaverRt.cboYM");
            me.clsComFnc.ObjSelect = $(".KRSS.FrmHknSytKaverRt.cboYM");
            me.clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
            return;
        }
        //-----ワークテーブルに保険収手・固定費を部署別・ライン別に集計する-----
        var url = me.sys_id + "/" + me.id + "/cmbExcel_Click";
        var data = {
            cboYM:
                $(".KRSS.FrmHknSytKaverRt.cboYM").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmHknSytKaverRt.cboYM").val().substring(4, 6),
            cboYMTo:
                $(".KRSS.FrmHknSytKaverRt.cboYMTo").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmHknSytKaverRt.cboYMTo").val().substring(4, 6),
        };
        me.ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.ObjFocus = $(".KRSS.FrmSonekiMeisai.cboYM");
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.reshow;
                    me.clsComFnc.FncMsgBox("I0011");
                    //印刷出力
                    window.location.href = result["data"];
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //ｺﾝﾎﾞﾎﾞｯｸｽに当月年月を設定する
    me.reshow = function () {
        var cboYM_Dom = $(".KRSS.FrmHknSytKaverRt.cboYM");
        var cboYMTo_Dom = $(".KRSS.FrmHknSytKaverRt.cboYMTo");
        cboYM_Dom.val(me.cboYM);
        cboYMTo_Dom.val(me.cboYMTo);
        cboYM_Dom.trigger("focus");
        cboYM_Dom.select();
    };

    // ==========
    // = メソッド end =
    // ==========

    // ==========
    // フォームロード
    // ==========

    return me;
};

$(function () {
    var o_KRSS_FrmHknSytKaverRt = new KRSS.FrmHknSytKaverRt();
    o_KRSS_FrmHknSytKaverRt.load();
});
