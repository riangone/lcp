/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20201120           bug                         IE下考課表タイプ 无法选择问题修正       YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("JKSYS.FrmEvaluationtotal");

JKSYS.FrmEvaluationtotal = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.jksys = new JKSYS.JKSYS();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmEvaluationtotal";
    me.sys_id = "JKSYS";
    me.prvKakiBonusEndMt = "";
    me.prvToukiBonusEndMt = "";
    me.prvKisyuYmd = "";
    me.prvKimatuYmd = "";
    me.dtpTaisyouKE = "";
    me.prvKisyuYM = "";
    me.kensu = "";
    me.IsInitialized = false;
    // ========== 変数 end ==========
    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmEvaluationtotal.cmdApply",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmEvaluationtotal.Button1",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmEvaluationtotal.cmdReApply",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmEvaluationtotal.dtpTaisyouKE",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.jksys.Shift_TabKeyDown();
    //Tabキーのバインド
    me.jksys.TabKeyDown();
    //Enterキーのバインド
    me.jksys.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //集計ボタン
    $(".FrmEvaluationtotal.cmdApply").click(function () {
        me.cmdApply_Click();
    });
    //順位再設定ボタン押下
    $(".FrmEvaluationtotal.cmdReApply").click(function () {
        me.cmdReApply_Click();
    });
    //達成率のみ更新
    $(".FrmEvaluationtotal.Button1").click(function () {
        me.Button1_Click();
    });

    $(".FrmEvaluationtotal.dtpTaisyouKE").blur(function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmEvaluationtotal.dtpTaisyouKE")) ==
            false
        ) {
            $(".FrmEvaluationtotal.dtpTaisyouKE").val(me.dtpTaisyouKE);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmEvaluationtotal.dtpTaisyouKE").trigger("focus");
                    $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmEvaluationtotal.dtpTaisyouKE").trigger("focus");
                        $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                    }, 0);
                }
            }
            $(".FrmEvaluationtotal.cmdApply").button("disable");
            $(".FrmEvaluationtotal.Button1").button("disable");
            $(".FrmEvaluationtotal.cmdReApply").button("disable");
            //20201120 YIN INS S
            $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", false);
            //20201120 YIN INS E
        } else {
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    if (me.IsInitialized) {
                        me.setKikan();
                    }
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    if (me.IsInitialized) {
                        me.setKikan();
                    }
                }
            }

            $(".FrmEvaluationtotal.cmdApply").button("enable");
            $(".FrmEvaluationtotal.Button1").button("enable");
            $(".FrmEvaluationtotal.cmdReApply").button("enable");
        }
    });

    //20201120 YIN INS S
    $(".FrmEvaluationtotal.cboKoukaType").mousedown(function () {
        if (
            navigator.userAgent.toUpperCase().indexOf("CHROME") > -1 ||
            navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1
        ) {
            $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", true);
            var month = $(".FrmEvaluationtotal.dtpTaisyouKE")
                .val()
                .substring(4, 6);
            if (
                !(
                    month != me.prvKakiBonusEndMt &&
                    month != me.prvToukiBonusEndMt
                )
            ) {
                $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", false);
            }
        }
    });
    $(".FrmEvaluationtotal.divcboKoukaType").mouseenter(function () {
        //判断是否为IE浏览器
        if (
            navigator.userAgent.toUpperCase().indexOf("TRIDENT") > -1 &&
            navigator.userAgent.toUpperCase().indexOf("RV") > -1
        ) {
            //評価期間
            $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", true);
            var month = $(".FrmEvaluationtotal.dtpTaisyouKE")
                .val()
                .substring(4, 6);
            if (
                !(
                    month != me.prvKakiBonusEndMt &&
                    month != me.prvToukiBonusEndMt
                )
            ) {
                $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", false);
            }
        }
    });
    $(".FrmEvaluationtotal.divcboKoukaType").mouseleave(function () {
        //判断是否为IE浏览器
        if (
            navigator.userAgent.toUpperCase().indexOf("TRIDENT") > -1 &&
            navigator.userAgent.toUpperCase().indexOf("RV") > -1
        ) {
            $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", false);
        }
    });
    //20201120 YIN INS E
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.FrmEvaluationtotal_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：FrmEvaluationtotal_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明：フォームロード
	 '**********************************************************************
	 */
    me.FrmEvaluationtotal_Load = function () {
        me.IsInitialized = false;
        //人事コントロールマスタより評価期間終了月/決算期間を取得する   考課表ﾀｲﾌﾟｺﾝﾎﾞﾎﾞｯｸｽに値を設定する  社員別考課表タイプデータよりMAX(評価対象期間終了)を取得する
        me.getJKCONTROLMST();
    };
    /*
	 '**********************************************************************
	 '処 理 名：達成率のみ更新
	 '関 数 名：Button1_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：基準・実績を修正した場合に、達成率を再計算する
	 '**********************************************************************
	 */
    me.Button1_Click = function () {
        //入力チェック
        if (!me.fncInputChk()) {
            return;
        }

        $(".FrmEvaluationtotal.cmdApply").button("disable");
        $(".FrmEvaluationtotal.cmdReApply").button("disable");

        var data = {
            dtpTaisyouKE: $(".FrmEvaluationtotal.dtpTaisyouKE").val(),
        };
        var url = me.sys_id + "/" + me.id + "/" + "Button1_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                };
                if (result["error"] == "W0002") {
                    me.clsComFnc.FncMsgBox("W0002", "評価期間");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                me.clsComFnc.FncMsgBox("I0005");
            }
            $(".FrmEvaluationtotal.cmdApply").button("enable");
            $(".FrmEvaluationtotal.cmdReApply").button("enable");
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fncInputChk
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：入力チェック
	 '**********************************************************************
	 */
    me.fncInputChk = function () {
        if (!me.setKikan()) {
            return false;
        }
        //間接管理職・間接スタッフは実績集計をしない
        if (
            $(".FrmEvaluationtotal.cboKoukaType").val() == "07" ||
            $(".FrmEvaluationtotal.cboKoukaType").val() == "14"
        ) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "実績集計対象考課表タイプを選択して下さい。"
            );
            return false;
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：順位再設定ボタン押下
	 '関 数 名：cmdReApply_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：順位再設定ボタン押下
	 '**********************************************************************
	 */
    me.cmdReApply_Click = function () {
        //入力チェック
        if (!me.fncInputChk()) {
            return;
        }
        var data = {
            dtpTaisyouKE: $(".FrmEvaluationtotal.dtpTaisyouKE").val(),
            cboKoukaType: $(".FrmEvaluationtotal.cboKoukaType").val(),
            rdoBoth: $(".FrmEvaluationtotal.rdoBoth").prop("checked"),
            rdo6Months: $(".FrmEvaluationtotal.rdo6Months").prop("checked"),
            rdo1year: $(".FrmEvaluationtotal.rdo1year").prop("checked"),
            rdoExct_Grop: $(".FrmEvaluationtotal.rdoExct_Grop").prop("checked"),
            rdoExct_Type: $(".FrmEvaluationtotal.rdoExct_Type").prop("checked"),
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncChkJISSEKI_SYUKEI";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                };
                //評価最終年月
                if (result["error"] == "W0002") {
                    me.clsComFnc.FncMsgBox("W0002", "評価期間");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                if (result["data"]["KENSU"] == 0) {
                    me.clsComFnc.FncMsgBox(
                        "I9999",
                        "該当期間の実績集計データは存在しません。"
                    );
                    return;
                }
                //-- ボタン制御 --
                $(".FrmEvaluationtotal.cmdApply").button("disable");
                $(".FrmEvaluationtotal.cmdReApply").button("disable");
                var url = me.sys_id + "/" + me.id + "/" + "cmdReApply_Click";
                me.ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (result["result"] == false) {
                        if (result["error"] == "I0001") {
                            me.clsComFnc.FncMsgBox("I0001");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                    } else {
                        //終了メッセージ
                        me.clsComFnc.FncMsgBox("I0013");
                    }
                    //-- ボタン制御 --
                    $(".FrmEvaluationtotal.cmdApply").button("enable");
                    $(".FrmEvaluationtotal.cmdReApply").button("enable");
                };
                me.ajax.send(url, data, 0);
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：集計ボタン
	 '関 数 名：cmdApply_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：集計ボタン
	 '**********************************************************************
	 */
    me.cmdApply_Click = function () {
        //入力チェック
        if (!me.fncInputChk()) {
            return;
        }
        var data = {
            dtpTaisyouKE: $(".FrmEvaluationtotal.dtpTaisyouKE").val(),
            cboKoukaType: $(".FrmEvaluationtotal.cboKoukaType").val(),
            rdoBoth: $(".FrmEvaluationtotal.rdoBoth").prop("checked"),
            rdo6Months: $(".FrmEvaluationtotal.rdo6Months").prop("checked"),
            rdo1year: $(".FrmEvaluationtotal.rdo1year").prop("checked"),
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncChkJISSEKI_SYUKEI";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                };
                //評価最終年月
                if (result["error"] == "W0002") {
                    me.clsComFnc.FncMsgBox("W0002", "評価期間");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                me.kensu = result["data"]["KENSU"];
                if (result["data"]["KENSU"] !== "0") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdApplyQY;
                    me.clsComFnc.FncMsgBox("QY011", "該当期間の実績集計データ");
                } else {
                    me.cmdApplyQY();
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：集計ボタンの操作
	 '関 数 名：cmdApplyQY
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明：集計ボタンの操作
	 '**********************************************************************
	 */
    me.cmdApplyQY = function () {
        //-- ボタン制御 --
        $(".FrmEvaluationtotal.cmdApply").button("disable");
        $(".FrmEvaluationtotal.cmdReApply").button("disable");
        //期首年月の設定
        //集計対象期首年月を求める
        if (
            me.prvKisyuYmd.substring(0, 6) >
                $(".FrmEvaluationtotal.dtpTaisyouKE").val() ||
            $(".FrmEvaluationtotal.dtpTaisyouKE").val() >
                me.prvKimatuYmd.substring(0, 6)
        ) {
            //20211102 WANGYING UPD S
            //me.prvKisyuYM = (me.prvKisyuYmd.substring(0, 4) - me.prvKimatuYmd.substring(0, 4) - $(".FrmEvaluationtotal.dtpTaisyouKE").val().substring(0, 4)) + '' + me.prvKisyuYmd.substring(4, 6);
            me.prvKisyuYM =
                me.prvKisyuYmd.substring(0, 4) -
                (me.prvKimatuYmd.substring(0, 4) -
                    $(".FrmEvaluationtotal.dtpTaisyouKE")
                        .val()
                        .substring(0, 4)) +
                "" +
                me.prvKisyuYmd.substring(4, 6);
            //20211102 WANGYING UPD E
        } else {
            me.prvKisyuYM = me.prvKisyuYmd.substring(0, 6);
        }
        var data = {
            rdoBoth: $(".FrmEvaluationtotal.rdoBoth").prop("checked"),
            rdo6Months: $(".FrmEvaluationtotal.rdo6Months").prop("checked"),
            rdo1year: $(".FrmEvaluationtotal.rdo1year").prop("checked"),
            dtpTaisyouKE: $(".FrmEvaluationtotal.dtpTaisyouKE").val(),
            cboKoukaType: $(".FrmEvaluationtotal.cboKoukaType").val(),
            rdoExct_Grop: $(".FrmEvaluationtotal.rdoExct_Grop").prop("checked"),
            rdoExct_Type: $(".FrmEvaluationtotal.rdoExct_Type").prop("checked"),
            prvKisyuYM: me.prvKisyuYM,
            kensu: me.kensu,
        };
        var url = me.sys_id + "/" + me.id + "/" + "cmdApply_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //終了メッセージ
                me.clsComFnc.FncMsgBox("I0013");
            }
            //-- ボタン制御 --
            $(".FrmEvaluationtotal.cmdApply").button("enable");
            $(".FrmEvaluationtotal.cmdReApply").button("enable");
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：処理説明 ：評価期間チェックを制御する
	 '関 数 名：setKikan
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明：処理説明 ：評価期間チェックを制御する
	 '**********************************************************************
	 */
    me.setKikan = function () {
        var month = $(".FrmEvaluationtotal.dtpTaisyouKE").val().substring(4, 6);
        if (month != me.prvKakiBonusEndMt && month != me.prvToukiBonusEndMt) {
            if (me.IsInitialized) {
                //20201120 INS YIN S
                $(".FrmEvaluationtotal.rdoExct_Type").prop("disabled", true);
                //20201120 INS YIN E
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    //20201120 YIN INS S
                    $(".FrmEvaluationtotal.rdoExct_Type").prop(
                        "disabled",
                        false
                    );
                    //20201120 YIN INS E
                    $(".FrmEvaluationtotal.dtpTaisyouKE").select();
                };
                me.clsComFnc.FncMsgBox("W0002", "評価期間");
                return false;
            }
        }
        //20201120 YIN INS S
        $(".FrmEvaluationtotal.cboKoukaType").prop("disabled", false);
        //20201120 YIN INS E
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：人事コントロールマスタより評価期間End月/決算期間を取得
	 '関 数 名：getJKCONTROLMST
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明：人事コントロールマスタより評価期間End月/決算期間を取得
	 '**********************************************************************
	 */
    me.getJKCONTROLMST = function () {
        var url = me.sys_id + "/" + me.id + "/" + "getJKCONTROLMST";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"]["ymd"]) {
                    //夏季評価期間終了
                    me.prvKakiBonusEndMt =
                        result["data"]["ymd"]["0"]["KAKI_HYOUKA_END_MT"];
                    //冬季評価期間終了
                    me.prvToukiBonusEndMt =
                        result["data"]["ymd"]["0"]["TOUKI_HYOUKA_END_MT"];
                    //期首年月日
                    me.prvKisyuYmd = result["data"]["ymd"]["0"]["KISYU_YMD"];
                    //期末年月日
                    me.prvKimatuYmd = result["data"]["ymd"]["0"]["KIMATU_YMD"];
                }
                if (result["data"]["select"]) {
                    for (key in result["data"]["select"]) {
                        $("<option></option>")
                            .val(
                                result["data"]["select"][key]["KOUKATYPE_CD"]
                                    ? result["data"]["select"][key][
                                          "KOUKATYPE_CD"
                                      ]
                                    : ""
                            )
                            .text(
                                result["data"]["select"][key]["KOUKATYPE_NM"]
                                    ? result["data"]["select"][key][
                                          "KOUKATYPE_NM"
                                      ]
                                    : ""
                            )
                            .appendTo(".FrmEvaluationtotal.cboKoukaType");
                    }
                }
                //評価期間にチェック
                if (result["data"]["dtpTaisyouKE"]) {
                    //評価期間
                    $(".FrmEvaluationtotal.dtpTaisyouKE").val(
                        result["data"]["dtpTaisyouKE"]
                    );
                    me.dtpTaisyouKE = result["data"]["dtpTaisyouKE"];
                }
                //評価期間チェックを制御する
                me.setKikan();

                //順位設定単位を設定する
                $(".FrmEvaluationtotal.rdoExct_Type").prop(
                    "checked",
                    "checked"
                );
                //コントロールの設定
                $(".FrmEvaluationtotal.cmdApply").button("enable");
                $(".FrmEvaluationtotal.cmdReApply").button("enable");
                me.IsInitialized = true;
                $(".FrmEvaluationtotal.dtpTaisyouKE").select();
            } else {
                $(".FrmEvaluationtotal").ympicker("disable");
                $(".FrmEvaluationtotal").prop("disabled", true);
                $(".FrmEvaluationtotal button").button("disable");
                if (result["error"] == "W0008") {
                    me.clsComFnc.FncMsgBox("W0008", "人事コントロールマスタ");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, "", 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    o_JKSYS_FrmEvaluationtotal = new JKSYS.FrmEvaluationtotal();
    o_JKSYS_FrmEvaluationtotal.load();
});
