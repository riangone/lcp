/**
 * 説明：
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150915           #2133                                                         Yuanjh
 * 20150915           #2134                                                         Yuanjh
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * * ------------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSyasyuArariChousei");
R4.FrmSyasyuArariChousei = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyasyuArariChousei";
    me.sys_id = "R4K";
    //---customer start---
    me.strSaveYM = "";
    me.strSaveItem = "";
    me.validatingArr = {
        current: "",
        before: "",
    };
    //---customer end---

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyasyuArariChousei.button_update",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuArariChousei.button_delete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuArariChousei.cboYM",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    // ========== コントロール end ==========
    // ==========
    // = 宣言 end =
    // ==========
    // ==========
    // = イベント start =
    // ==========
    $(".FrmSyasyuArariChousei.cboYM").on("blur", function () {
        //20150923 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSyasyuArariChousei.cboYM")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuArariChousei.cboYM")) == false
        ) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuArariChousei.cboYM").val(me.cboYMState);
                $(".FrmSyasyuArariChousei.cboYM").trigger("focus");
                $(".FrmSyasyuArariChousei.cboYM").select();
                $(".FrmSyasyuArariChousei.button_update").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyasyuArariChousei.button_update").button("enable");
        }
    });
    $(".FrmSyasyuArariChousei.txtUriage").on("focus", function () {
        me.cboItemNO_Validating();
    });
    $(".FrmSyasyuArariChousei.txtArari").on("focus", function () {
        me.cboItemNO_Validating();
    });
    $(".FrmSyasyuArariChousei.cboSyasyu").on("focus", function () {
        me.cboItemNO_Validating();
    });
    $(".FrmSyasyuArariChousei.cboYM").on("focus", function () {
        me.cboItemNO_Validating();
    });
    $(".FrmSyasyuArariChousei.button_update").click(function () {
        //20150915  Yuanjh UPD S.
        //me.cboItemNO_Validating();
        //20150915  Yuanjh UPD E.
        me.fnc_button_update();
    });
    $(".FrmSyasyuArariChousei.button_delete").click(function () {
        me.fnc_button_delete();
    });

    //20150915 Yuanjh UPD S.
    /*
	 $(".FrmSyasyuArariChousei.txtUriage").numeric(
	 {
	 decimal : false,
	 negative : false
	 });
	 $(".FrmSyasyuArariChousei.txtArari").numeric(
	 {
	 decimal : false,
	 negative : false
	 });
	 */
    $(".FrmSyasyuArariChousei.txtUriage").numeric({
        decimal: false,
    });
    $(".FrmSyasyuArariChousei.txtArari").numeric({
        decimal: false,
    });
    //20150915 Yuanjh UPD E.

    /*
	 '**********************************************************************
	 '　「車種｣
	 '**********************************************************************
	 '******************************
	 '- SELECTEDVALUECHANGED
	 '******************************
	 */
    $(".FrmSyasyuArariChousei.cboSyasyu").change(function () {
        $(".FrmSyasyuArariChousei.txtItemNO").val(
            $(".FrmSyasyuArariChousei.cboSyasyu").val()
        );
    });
    $(".FrmSyasyuArariChousei.cboSyasyu").on("blur", function () {
        $(".FrmSyasyuArariChousei.cboSyasyu").css(me.clsComFnc.GC_COLOR_NORMAL);
    });

    //F9キー＝登録ボタン
    shortcut.add("F9", function () {
        if (currentTabId == "#tabs_R4K") {
            me.shortCut(currentTabId, "F9");
        }
    });

    // ==========
    // = イベント end =
    // ==========
    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmSyasyuArariChousei_load();
    };

    /*
	 '**********************************************************************
	 '処理概要：フォームロード
	 '**********************************************************************
	 */
    me.FrmSyasyuArariChousei_load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "formLoad";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    //コンボボックスに当月年月を設定
                    //20150923 yin upd S
                    // $tmpDate = result['data'][0]['TOUGETU'].substr(0, 7);
                    $tmpDate = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150923 yin upd E
                    me.cboYMState = $tmpDate;
                    $(".FrmSyasyuArariChousei.cboYM").val($tmpDate);
                    //画面項目ｸﾘｱ
                    me.subFormClear();
                    //コンボボックスに値を設定
                    me.subComboSet2();
                    $(".FrmSyasyuArariChousei.cboYM").trigger("focus");
                    me.strSaveYM = $(".FrmSyasyuArariChousei.cboYM")
                        .val()
                        .replace("/", "");
                    me.strSaveItem = "";
                } else {
                    var myDate = new Date();
                    var tmpMonth = (myDate.getMonth() + 1).toString();
                    if (tmpMonth.length < 2) {
                        tmpMonth = "0" + tmpMonth.toString();
                    }
                    var tmpNowDate =
                        myDate.getFullYear().toString() + tmpMonth.toString();
                    $(".FrmSyasyuArariChousei.cboYM").val(tmpNowDate);
                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 1);
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面項目ｸﾘｱ
	 '関 数 名：subFormClear
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：スプレッドでEnterキーで次のセルに進むようにする
	 '**********************************************************************
	 */
    me.subFormClear = function () {
        $(".FrmSyasyuArariChousei.txtUriage").val("");
        $(".FrmSyasyuArariChousei.txtArari").val("");
    };

    /*
	 '**********************************************************************
	 '処 理 名：コンボボックス値を設定
	 '関 数 名：subComboSet2
	 '引    数：しない
	 '戻 り 値：String
	 '処理説明：名称マスタから保険区分を取得し、コンボボックスに設定する
	 '**********************************************************************
	 */
    me.subComboSet2 = function () {
        var url = me.sys_id + "/" + me.id + "/" + "subComboSet2";
        me.ajax.receive = function (result1) {
            result1 = eval("(" + result1 + ")");
            if (result1["result"]) {
                if (result1["data"].length > 0) {
                    for (key in result1["data"]) {
                        var cboSyasyu_option1 =
                            "<option value='" +
                            result1["data"][key]["OYA_CD"] +
                            "'> " +
                            result1["data"][key]["SS_NAME"] +
                            "</option>";
                        $(".FrmSyasyuArariChousei.cboSyasyu").append(
                            cboSyasyu_option1
                        );
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result1["data"]);
            }
            var cboSyasyu_option = "<option value='999'>調整金</option>";
            $(".FrmSyasyuArariChousei.cboSyasyu").append(cboSyasyu_option);
        };
        me.ajax.send(url, "", 1);
    };

    /*
	 '**********************************************************************
	 '処理概要：更新ボタン押下時
	 '**********************************************************************
	 */
    me.fnc_button_update = function () {
        //入力チェック
        var tmpVal = $(".FrmSyasyuArariChousei.cboSyasyu")[0].selectedIndex;
        if (tmpVal <= 0) {
            $(".FrmSyasyuArariChousei.cboSyasyu").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "車種は必須項目です！");
            $(".FrmSyasyuArariChousei.cboSyasyu").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
        } else {
            //重複ﾁｪｯｸ
            var url = me.sys_id + "/" + me.id + "/" + "fncArariSelect";
            var tmpdata1 = {
                cboYM: $(".FrmSyasyuArariChousei.cboYM")
                    .val()
                    .toString()
                    .trimEnd(),
                txtItemNo: $(".FrmSyasyuArariChousei.txtItemNO")
                    .val()
                    .toString()
                    .trimEnd(),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    if (result["data"].length > 0) {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesUpdateFnc;
                        me.clsComFnc.MsgBoxBtnFnc.No = me.NoUpdateFnc;
                        me.clsComFnc.FncMsgBox(
                            "QY999",
                            "該当データは既に存在します。修正しますか？"
                        );
                    } else {
                        //****登録開始****
                        me.YesUpdateFnc();
                    }
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                }
            };
            me.ajax.send(url, tmpdata1, 1);
        }
    };

    /*
	 '**********************************************************************
	 '処理概要：削除ボタン押下時
	 '**********************************************************************
	 */
    me.fnc_button_delete = function () {
        //入力チェック
        var tmpVal = $(".FrmSyasyuArariChousei.cboSyasyu")[0].selectedIndex;
        if (tmpVal <= 0) {
            $(".FrmSyasyuArariChousei.cboSyasyu").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "車種は必須項目です！");
            $(".FrmSyasyuArariChousei.cboSyasyu").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesDeleteFnc;
            me.clsComFnc.MsgBoxBtnFnc.No = me.NoDeleteFnc;
            me.clsComFnc.FncMsgBox("QY004");
        }
    };

    /*
	 '**********************************************************************
	 '処理概要：項目番号ﾌｫｰｶｽ移動
	 '**********************************************************************
	 */
    me.cboItemNO_Validating = function () {
        var tmpA = document.activeElement;
        me.validatingArr["before"] = me.validatingArr["current"];
        me.validatingArr["current"] = tmpA;

        if (me.validatingArr["before"] && me.validatingArr["current"]) {
            if (
                me.validatingArr["before"].className !=
                me.validatingArr["current"].className
            ) {
                if (
                    me.validatingArr["before"].className.indexOf("cboSyasyu") >
                        0 ||
                    me.validatingArr["before"].className.indexOf("cboYM") > 0
                ) {
                    //年月、車種が前回と変わらない場合は処理を抜ける
                    var tmpCboYM = $(".FrmSyasyuArariChousei.cboYM")
                        .val()
                        .replace("/", "");
                    if (
                        tmpCboYM == me.strSaveYM &&
                        $(".FrmSyasyuArariChousei.txtItemNO").val() ==
                            me.strSaveItem
                    ) {
                        return;
                    }
                    me.subFormClear();

                    var url = me.sys_id + "/" + me.id + "/" + "fncArariSelect";
                    var tmpdata1 = {
                        cboYM: $(".FrmSyasyuArariChousei.cboYM").val(),
                        txtItemNo: $(".FrmSyasyuArariChousei.txtItemNO").val(),
                    };
                    //営業ｽﾀｯﾌ項目ﾃﾞｰﾀを抽出
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"]) {
                            if (result["data"].length > 0) {
                                //取得した値を設定
                                $(".FrmSyasyuArariChousei.txtUriage").val(
                                    result["data"][0]["HONTAIGAKU"]
                                );
                                $(".FrmSyasyuArariChousei.txtArari").val(
                                    result["data"][0]["SYARYOARARI"]
                                );
                                me.strSaveYM = $(".FrmSyasyuArariChousei.cboYM")
                                    .val()
                                    .replace("/", "");
                                me.strSaveItem = $(
                                    ".FrmSyasyuArariChousei.cboSyasyu"
                                ).val();
                            } else {
                                //営業ｽﾀｯﾌ項目ﾃﾞｰﾀが存在しない場合
                                me.strSaveYM = $(".FrmSyasyuArariChousei.cboYM")
                                    .val()
                                    .replace("/", "");
                                me.strSaveItem = "";
                                return;
                            }
                            me.fnc_button_Click_validating();
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["data"]);
                        }
                    };
                    me.ajax.send(url, tmpdata1, 1);
                } else {
                    me.fnc_button_Click_validating();
                }
            } else {
                me.fnc_button_Click_validating();
            }
        } else {
            me.fnc_button_Click_validating();
        }
    };

    /*
	 '**********************************************************************
	 'ファンクションキー 押下時
	 '**********************************************************************
	 */
    me.shortCut = function (selTabIdStr, shortCut) {
        if (selTabIdStr == "#tabs_R4K") {
            selTabIdStr = "R4K";
        }

        switch (shortCut) {
            case "F9": {
                switch (selTabIdStr) {
                    case "R4K":
                        me.fnc_button_update();
                        break;
                }
                break;
            }
        }
    };

    me.fnc_button_Click_validating = function () {
        if (
            me.validatingArr["current"].className.indexOf("button_update") > 0
        ) {
            me.fnc_button_update();
        }
    };
    me.NoUpdateFnc = function () {
        return;
    };
    me.YesUpdateFnc = function () {
        //****登録開始****
        var url = me.sys_id + "/" + me.id + "/" + "fncDeleteInsertArari";
        var tmpdata1 = {
            cboYM: $(".FrmSyasyuArariChousei.cboYM").val(),
            txtItemNo: $(".FrmSyasyuArariChousei.txtItemNO").val(),
            txtUriage: $(".FrmSyasyuArariChousei.txtUriage").val(),
            txtArari: $(".FrmSyasyuArariChousei.txtArari").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                $(".FrmSyasyuArariChousei.cboSyasyu")[0].selectedIndex = 0;
                me.subFormClear();
                //現在のキー値を保存する
                me.strSaveYM = $(".FrmSyasyuArariChousei.cboYM")
                    .val()
                    .replace("/", "");
                me.strSaveItem = "";
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, tmpdata1, 1);
    };
    me.YesDeleteFnc = function () {
        //削除処理
        var url = me.sys_id + "/" + me.id + "/" + "fncDeleteArari";
        var tmpdata1 = {
            cboYM: $(".FrmSyasyuArariChousei.cboYM").val(),
            txtItemNo: $(".FrmSyasyuArariChousei.txtItemNO").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                $(".FrmSyasyuArariChousei.cboSyasyu")[0].selectedIndex = 0;
                me.subFormClear();
                //現在のキー値を保存する
                me.strSaveYM = $(".FrmSyasyuArariChousei.cboYM")
                    .val()
                    .replace("/", "");
                me.strSaveItem = "";
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, tmpdata1, 1);
    };
    me.NoDeleteFnc = function () {
        return;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyasyuArariChousei = new R4.FrmSyasyuArariChousei();
    o_R4_FrmSyasyuArariChousei.load();
});
