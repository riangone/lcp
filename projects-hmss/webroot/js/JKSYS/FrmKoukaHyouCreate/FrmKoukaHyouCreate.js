/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20201117           bug                         【评价期间】值不正时焦点问题修正        YIN
 * 20201120           bug                         IE下考課表タイプ 无法选择问题修正       YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("JKSYS.FrmKoukaHyouCreate");

JKSYS.FrmKoukaHyouCreate = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.ajax = new gdmz.common.ajax();
    me.jksys = new JKSYS.JKSYS();
    me.id = "FrmKoukaHyouCreate";
    me.sys_id = "JKSYS";

    //初期化処理済みチェック
    me.IsInitialized = false;
    //夏季評価期間終了
    me.prvKakiBonusEndM = "";
    //冬季評価期間終了
    me.prvToukiBonusEndMt = "";
    //作成者
    me.GetSyainNm = "";
    //社員別考課表タイプデータよりMAX(評価対象期間終了)
    me.dtpKikanEnd = "";
    //現在のキーボードイベント
    me.keydownEvent = undefined;
    //「tab+shift」キーボードイベント true:はい　false:いいえ
    me.tab_shift = false;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKoukaHyouCreate.dtpKikanEnd",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKoukaHyouCreate.cmdExcel",
        type: "button",
        handle: "",
    });
    //評価期間
    $(".FrmKoukaHyouCreate.dtpKikanEnd").on("keydown", function (e) {
        if (e) {
            var key = e.which;
            if (
                (key == 9 && e.shiftKey == true) ||
                key == 13 ||
                (key == 9 && e.shiftKey == false)
            ) {
                me.keydownEvent = {
                    which: e.which,
                    shiftKey: e.shiftKey,
                };
            }
        }
    });
    //ShiftキーとTabキーのバインド
    me.jksys.Shift_TabKeyDown();

    //Tabキーのバインド
    me.jksys.EnterKeyDown();

    //Enterキーのバインド
    me.jksys.TabKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //作成者
    $(".FrmKoukaHyouCreate.txtSyainNo").on("input", function () {
        //コントロールの設定
        me.pnlJoken(false);
    });
    //作成者
    $(".FrmKoukaHyouCreate.txtSyainNo").on("keydown", function (e) {
        var key = e.which;
        if (key == 13 || (key == 9 && e.shiftKey == false)) {
            e.preventDefault();
            me.tab_shift = false;
            if ($(".ui-dialog-content.ui-widget-content.HMS_F9").length == 0) {
                if (
                    me.txtSyainNo_LostFocus(
                        $(".FrmKoukaHyouCreate.txtSyainNo").val()
                    ) != false
                ) {
                    $(".FrmKoukaHyouCreate.cmdExcel").trigger("focus");
                }
            }
        } else if (key == 9 && e.shiftKey == true) {
            e.preventDefault();
            //IE
            if (
                navigator.userAgent.toUpperCase().indexOf("TRIDENT") > -1 &&
                navigator.userAgent.toUpperCase().indexOf("RV") > -1
            ) {
                me.tab_shift = true;
            } else {
                //考課表タイプ使用不可場合
                if (
                    $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled") ==
                    "disabled"
                ) {
                    me.tab_shift = true;
                }
            }

            if ($(".ui-dialog-content.ui-widget-content.HMS_F9").length == 0) {
                if (
                    me.txtSyainNo_LostFocus(
                        $(".FrmKoukaHyouCreate.txtSyainNo").val()
                    ) != false
                ) {
                    $(".FrmKoukaHyouCreate.cboKoukaType").trigger("focus");
                }
            }
        }
    });
    //作成者
    $(".FrmKoukaHyouCreate.txtSyainNo").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.txtSyainNo_LostFocus(
                        $(".FrmKoukaHyouCreate.txtSyainNo").val()
                    )
                ) {
                    if (me.tab_shift) {
                        //tab+shift：考課表タイプフォカス
                        $(".FrmKoukaHyouCreate.cboKoukaType").trigger("focus");
                    } else {
                        //blur：Excel出力ボタンフォカス
                        $(".FrmKoukaHyouCreate.cmdExcel").trigger("focus");
                    }
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.txtSyainNo_LostFocus(
                        $(".FrmKoukaHyouCreate.txtSyainNo").val()
                    )
                ) {
                    if (me.tab_shift) {
                        //tab+shift：考課表タイプフォカス
                        $(".FrmKoukaHyouCreate.cboKoukaType").trigger("focus");
                    } else {
                        //blur：Excel出力ボタンフォカス
                        $(".FrmKoukaHyouCreate.cmdExcel").trigger("focus");
                    }
                }
            }
        }
        me.tab_shift = false;
    });

    //評価期間
    $(".FrmKoukaHyouCreate.dtpKikanEnd").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmKoukaHyouCreate.dtpKikanEnd")) ==
            false
        ) {
            $(".FrmKoukaHyouCreate.dtpKikanEnd").val(me.dtpKikanEnd);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmKoukaHyouCreate.dtpKikanEnd").trigger("focus");
                    $(".FrmKoukaHyouCreate.dtpKikanEnd").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    window.setTimeout(function () {
                        $(".FrmKoukaHyouCreate.dtpKikanEnd").trigger("focus");
                        $(".FrmKoukaHyouCreate.dtpKikanEnd").select();
                    }, 0);
                }
            }
            $(".FrmKoukaHyouCreate.cmdExcel").button("disable");
            //20201116 YIN INS S
            $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", false);
            me.keydownEvent = undefined;
            //20201116 YIN INS E
        } else {
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    if (me.dtpKikanEnd_LostFocus(this)) {
                        $(".FrmKoukaHyouCreate.cmdExcel").button("enable");
                    }
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    if (me.dtpKikanEnd_LostFocus(e.target)) {
                        $(".FrmKoukaHyouCreate.cmdExcel").button("enable");
                    }
                }
            }
        }
    });
    //Excel出力ボタンクリック
    $(".FrmKoukaHyouCreate.cmdExcel").click(function () {
        me.cmdExcel_Click();
    });

    //20201116 YIN INS S
    $(".FrmKoukaHyouCreate.cboKoukaType").mousedown(function () {
        if (
            navigator.userAgent.toUpperCase().indexOf("CHROME") > -1 ||
            navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1
        ) {
            $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", true);
            //評価期間
            var dtpKikanEnd = $(".FrmKoukaHyouCreate.dtpKikanEnd").val();
            var month = dtpKikanEnd.substring(
                dtpKikanEnd.length - 2,
                dtpKikanEnd.length
            );
            if (
                !(
                    month != me.prvKakiBonusEndMt &&
                    month != me.prvToukiBonusEndMt
                )
            ) {
                $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", false);
            }
        }
    });
    //20201116 YIN INS E
    //20201120 YIN INS S
    $(".FrmKoukaHyouCreate.divcboKoukaType").mouseenter(function () {
        //判断是否为IE浏览器
        if (
            navigator.userAgent.toUpperCase().indexOf("TRIDENT") > -1 &&
            navigator.userAgent.toUpperCase().indexOf("RV") > -1
        ) {
            //評価期間
            var dtpKikanEnd = $(".FrmKoukaHyouCreate.dtpKikanEnd").val();
            var month = dtpKikanEnd.substring(
                dtpKikanEnd.length - 2,
                dtpKikanEnd.length
            );
            if (
                month != me.prvKakiBonusEndMt &&
                month != me.prvToukiBonusEndMt
            ) {
                $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", true);
            } else {
                if (
                    $(".FrmKoukaHyouCreate.dtpKikanEnd").prop("disabled") ==
                    false
                ) {
                    $(".FrmKoukaHyouCreate.cboKoukaType").attr(
                        "disabled",
                        false
                    );
                }
            }
        }
    });
    $(".FrmKoukaHyouCreate.divcboKoukaType").mouseleave(function () {
        //判断是否为IE浏览器
        if (
            navigator.userAgent.toUpperCase().indexOf("TRIDENT") > -1 &&
            navigator.userAgent.toUpperCase().indexOf("RV") > -1
        ) {
            if (
                $(".FrmKoukaHyouCreate.dtpKikanEnd").prop("disabled") == false
            ) {
                $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", false);
            }
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
        //フォームロード
        me.FrmKoukaHyouCreate_Load();
    };
    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：FrmKoukaHyouCreate_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.FrmKoukaHyouCreate_Load = function () {
        me.IsInitialized = false;

        var url = me.sys_id + "/" + me.id + "/" + "FrmKoukaHyouCreate_Load";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmKoukaHyouCreate").ympicker("disable");
                $(".FrmKoukaHyouCreate").attr("disabled", true);
                $(".FrmKoukaHyouCreate button").button("disable");
                if (result["error"] == "W0008") {
                    me.clsComFnc.FncMsgBox("W0008", "人事コントロールマスタ");
                    return false;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return false;
                }
            } else {
                $(".FrmKoukaHyouCreate.txtSyainNo").trigger("focus");

                //人事コントロールマスタより評価期間終了月を取得する
                if (result["data"]) {
                    //夏季評価期間終了
                    me.prvKakiBonusEndMt = result["data"]["prvKakiBonusEndMt"];
                    //冬季評価期間終了
                    me.prvToukiBonusEndMt =
                        result["data"]["prvToukiBonusEndMt"];

                    //社員別考課表タイプデータよりMAX(評価対象期間終了)を取得する
                    me.dtpKikanEnd = result["data"]["dtpKikanEnd"];
                    $(".FrmKoukaHyouCreate.dtpKikanEnd").val(me.dtpKikanEnd);

                    //評価期間チェックを制御する
                    me.setKikan();

                    //考課表ﾀｲﾌﾟｺﾝﾎﾞﾎﾞｯｸｽに値を設定する
                    for (key in result["data"]["cboKoukaType"]) {
                        $("<option></option>")
                            .val(
                                result["data"]["cboKoukaType"][key][
                                    "KOUKATYPE_CD"
                                ]
                                    ? result["data"]["cboKoukaType"][key][
                                          "KOUKATYPE_CD"
                                      ]
                                    : ""
                            )
                            .text(
                                result["data"]["cboKoukaType"][key][
                                    "KOUKATYPE_NM"
                                ]
                                    ? result["data"]["cboKoukaType"][key][
                                          "KOUKATYPE_NM"
                                      ]
                                    : ""
                            )
                            .appendTo(".FrmKoukaHyouCreate.cboKoukaType");
                    }

                    //コントロールの設定
                    me.pnlJoken(false);

                    //作成者
                    me.GetSyainNm = result["data"]["SYAIN_NO_NAME"];
                }
                me.IsInitialized = true;
            }
        };
        me.ajax.send(url, "", 0);
    };
    //**********************************************************************
    //処 理 名：社員番号
    //関 数 名：txtSyainNo_LostFocus
    //引    数：e
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.txtSyainNo_LostFocus = function (targetVal) {
        //コントロールの設定
        me.pnlJoken(false);

        var foundNM = undefined;
        var selCellVal = $.trim(targetVal);
        $(".FrmKoukaHyouCreate.lbl_SyainName").text("");
        if (
            me.IsInitialized &&
            selCellVal.length ==
                $(".FrmKoukaHyouCreate.txtSyainNo").attr("maxlength")
        ) {
            if (me.GetSyainNm) {
                var foundNM_array = me.GetSyainNm.filter(function (element) {
                    return (
                        element["SYAIN_NO"] == me.clsComFnc.FncNv(selCellVal)
                    );
                });
                if (
                    foundNM_array.length == 0 ||
                    foundNM_array[0]["SYAIN_NM"] == ""
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(".FrmKoukaHyouCreate.txtSyainNo").select();
                    };
                    me.clsComFnc.FncMsgBox("W0007", "ログイン");
                    //コントロールの設定
                    me.pnlJoken(false);
                    return false;
                } else {
                    foundNM = foundNM_array[0];
                    //コントロールの設定
                    me.pnlJoken(true);
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
        $(".FrmKoukaHyouCreate.lbl_SyainName").text(
            foundNM ? foundNM["SYAIN_NM"] : ""
        );

        return true;
    };
    //**********************************************************************
    //処 理 名：評価期間
    //関 数 名：dtpKikanEnd_LostFocus
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.dtpKikanEnd_LostFocus = function (e) {
        if (me.IsInitialized) {
            return me.setKikan(e);
        }
    };
    //**********************************************************************
    //処 理 名：Excel出力ボタンクリック
    //関 数 名：cmdExcel_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.cmdExcel_Click = function () {
        if (!me.setKikan()) {
            return;
        }
        //評価期間
        var dtpYM = $(".FrmKoukaHyouCreate.dtpKikanEnd").val();
        var url = me.sys_id + "/" + me.id + "/" + "fncOutputExcel";
        var data = {
            dtpYM: dtpYM,
            SelectedValue: $(".FrmKoukaHyouCreate.cboKoukaType").val(),
            rdoBoth: $(".FrmKoukaHyouCreate.rdo6kagetu").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["message"]) {
                    me.clsComFnc.FncMsgBox(result["error"], result["message"]);
                    return false;
                } else if (result["error"] == "W0002") {
                    me.clsComFnc.FncMsgBox("W0002", "評価期間");
                    return false;
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                    return false;
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return false;
                } else if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return false;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return false;
                }
            } else {
                me.clsComFnc.FncMsgBox("I0011");
                return false;
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：評価期間チェックを制御する
    //関 数 名：setKikan
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.setKikan = function (e) {
        //考課表タイプ
        $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", false);
        //評価期間
        var dtpKikanEnd = $(".FrmKoukaHyouCreate.dtpKikanEnd").val();
        var month = dtpKikanEnd.substring(
            dtpKikanEnd.length - 2,
            dtpKikanEnd.length
        );
        if (month != me.prvKakiBonusEndMt && month != me.prvToukiBonusEndMt) {
            if (me.IsInitialized) {
                //考課表タイプ
                $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", true);
                //20201116 UPD S
                // $(".FrmKoukaHyouCreate.txtSyainNo").attr("readonly", "readonly");
                $(".FrmKoukaHyouCreate.txtSyainNo").attr("disabled", true);
                //20201116 UPD E

                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    //20201116 UPD S
                    // $(".FrmKoukaHyouCreate.txtSyainNo").removeAttr("readonly");
                    $(".FrmKoukaHyouCreate.txtSyainNo").attr("disabled", false);
                    $(".FrmKoukaHyouCreate.cboKoukaType").attr(
                        "disabled",
                        false
                    );
                    //20201116 UPD E
                    $(".FrmKoukaHyouCreate.dtpKikanEnd").select();
                };
                me.clsComFnc.FncMsgBox("W0002", "評価期間");
            }
            //20201116 YIN INS S
            me.keydownEvent = undefined;
            //20201116 YIN INS E
            return false;
        }

        if (e && me.keydownEvent) {
            var event = $.Event("keydown");
            event.which = me.keydownEvent.which;
            event.shiftKey = me.keydownEvent.shiftKey;
            $(e).trigger(event);
            me.keydownEvent = undefined;
        }
        return true;
    };
    //**********************************************************************
    //処 理 名：コントロールの設定
    //関 数 名：pnlJoken
    //引    数：flg
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.pnlJoken = function (flg) {
        if (flg) {
            //評価期間
            $(".FrmKoukaHyouCreate.dtpKikanEnd").ympicker("enable");
            $(".FrmKoukaHyouCreate.dtpKikanEnd").prop("disabled", false);
            //考課表タイプ
            $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", false);
            //Excel出力
            $(".FrmKoukaHyouCreate.cmdExcel").button("enable");
        } else {
            //評価期間
            $(".FrmKoukaHyouCreate.dtpKikanEnd").ympicker("disable");
            $(".FrmKoukaHyouCreate.dtpKikanEnd").prop("disabled", true);
            //考課表タイプ
            $(".FrmKoukaHyouCreate.cboKoukaType").attr("disabled", true);
            //Excel出力
            $(".FrmKoukaHyouCreate.cmdExcel").button("disable");
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    o_JKSYS_FrmKoukaHyouCreate = new JKSYS.FrmKoukaHyouCreate();
    o_JKSYS_FrmKoukaHyouCreate.load();
});
