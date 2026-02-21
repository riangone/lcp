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
 * 20180115 		  #2807						   依頼								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSyanaiLeasePrint");

R4.FrmSyanaiLeasePrint = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyanaiLeasePrint";
    me.sys_id = "R4K";
    me.cboYM = "";
    me.allValidatingArr = new Array();
    me.validatingArr = {
        current: "",
        before: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSyanaiLeasePrint.button_CSV_print",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyanaiLeasePrint.button_cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyanaiLeasePrint.cboYM",
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

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //コンボボックスに初期値設定
        var currentYM = new Date();
        $(".FrmSyanaiLeasePrint.cboYM").ympicker("setDate", currentYM);
    };
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmSyanaiLeasePrint.cboYM").on("focus", function () {
        me.fncValidating();
    });
    $(".FrmSyanaiLeasePrint.radioAll").on("focus", function () {
        me.fncValidating();
    });
    $(".FrmSyanaiLeasePrint.radioOption1").on("focus", function () {
        me.fncValidating();
    });
    $(".FrmSyanaiLeasePrint.radioOption2").on("focus", function () {
        me.fncValidating();
    });

    $(".FrmSyanaiLeasePrint.cboYM").on("blur", function () {
        //20150923 yin upd S
        // if (me.clsComFnc.CheckDate2($(".FrmSyanaiLeasePrint.cboYM")) == false)
        if (me.clsComFnc.CheckDate3($(".FrmSyanaiLeasePrint.cboYM")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyanaiLeasePrint.cboYM").val(me.cboYMState);
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
        }
    });

    //**********************************************************************		`
    //処 理 名：名称取得
    //関 数 名：txtBusyoCDFROMValidating
    //引    数：無し
    //戻 り 値：無し
    //処理説明：部署名称を取得する
    //**********************************************************************
    $(".FrmSyanaiLeasePrint.busyoCDFrom").on("focus", function () {
        me.fncValidating();
    });

    //**********************************************************************		`
    //処 理 名：名称取得
    //関 数 名：txtBusyoCDTOValidating
    //引    数：無し
    //戻 り 値：無し
    //処理説明：部署名称を取得する
    //**********************************************************************
    $(".FrmSyanaiLeasePrint.busyoCDTo").on("focus", function () {
        me.fncValidating();
    });

    //**********************************************************************
    //処 理 名：ＣＳＶ出力
    //関 数 名：cmdOutput_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ＣＳＶ出力
    //**********************************************************************
    $(".FrmSyanaiLeasePrint.button_CSV_print").click(function () {
        me.fncValidating();
    });

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：印刷する
    //**********************************************************************
    $(".FrmSyanaiLeasePrint.button_cmdAction").click(function () {
        me.fncValidating();
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
        me.FrmSyanaiLeasePrint_load();
    };

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmsyanaiLeasePrint_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.FrmSyanaiLeasePrint_load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncFrmSyanaiLeasePrint_load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    //20150923 yin upd S
                    // $tmpDate = result['data'][0]['TOUGETU'].substr(0, 7);
                    $tmpDate = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150923 yin upd E
                    me.cboYMState = $tmpDate;
                    $(".FrmSyanaiLeasePrint.cboYM").val($tmpDate);
                    //me.getAllValidating();
                } else {
                    $(".FrmSyanaiLeasePrint.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
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

    me.fncValidating = function () {
        me.Control_LostFocus();
        var tmpA = document.activeElement;
        me.validatingArr["before"] = me.validatingArr["current"];
        me.validatingArr["current"] = tmpA;

        if (me.validatingArr["before"] && me.validatingArr["current"]) {
            if (
                me.validatingArr["before"].className !=
                me.validatingArr["current"].className
            ) {
                if (
                    me.validatingArr["before"].className.indexOf(
                        "busyoCDFrom"
                    ) > 0 ||
                    me.validatingArr["before"].className.indexOf("busyoCDTo") >
                        0
                ) {
                    var tmpBusyoCD = "";
                    var url =
                        me.sys_id +
                        "/" +
                        me.id +
                        "/" +
                        "fncTxtBusyoCDValidating";
                    //set validating values
                    tmpBusyoCD = me.fnc_set_values_validating();
                    var tmpData = {
                        busyoCD: tmpBusyoCD,
                    };

                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] != false) {
                            if (
                                me.validatingArr["before"].className.indexOf(
                                    "busyoCDFrom"
                                ) > 0
                            ) {
                                $(".FrmSyanaiLeasePrint.busyoNMTo").val(
                                    result["data"]
                                );
                                $(".FrmSyanaiLeasePrint.busyoNMFrom").val(
                                    result["data"]
                                );
                            } else {
                                //$(".FrmSyanaiLeasePrint.busyoNMFrom").val(result['data']);
                                $(".FrmSyanaiLeasePrint.busyoNMTo").val(
                                    result["data"]
                                );
                            }
                        } else {
                            if (
                                me.validatingArr["before"].className.indexOf(
                                    "busyoCDFrom"
                                ) > 0
                            ) {
                                $(".FrmSyanaiLeasePrint.busyoNMTo").val("");
                                $(".FrmSyanaiLeasePrint.busyoNMFrom").val("");
                            } else {
                                //20180115 YIN UPD S
                                // $(".FrmSyanaiLeasePrint.busyoNMFrom").val("");
                                $(".FrmSyanaiLeasePrint.busyoNMTo").val("");
                                //20180115 YIN UPD E
                            }
                        }
                        me.fnc_button_Click_validating();
                    };
                    me.ajax.send(url, tmpData, 1);
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

    me.fnc_button_CSV_print = function () {
        var tmpBusyoCD = $(".FrmSyanaiLeasePrint.busyoCDFrom").val();
        $(".FrmSyanaiLeasePrint.busyoCDTo").val(tmpBusyoCD);
        var tmpBusyoNM = $(".FrmSyanaiLeasePrint.busyoNMFrom").val();
        $(".FrmSyanaiLeasePrint.busyoNMTo").val(tmpBusyoNM);

        var tmpData = {
            cboYM: $(".FrmSyanaiLeasePrint.cboYM").val(),
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncOutput1";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    //20181026 YIN INS S
                    downloadExcel = 0;
                    //20181026 YIN INS E
                    window.location.href = result["data"];
                    me.clsComFnc.FncMsgBox("I0011");
                    // setTimeout(fnc_a, 1200);
                } else {
                    $(".FrmSyanaiLeasePrint.cboYM").trigger("focus");
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, tmpData, 1);
        me.focusBefore = null;
    };

    me.fnc_button_cmdAction = function () {
        //入力チェック
        var tt = $(".FrmSyanaiLeasePrint.busyoCDFrom");
        var tVal = me.clsComFnc.FncTextCheck(
            tt,
            0,
            me.clsComFnc.INPUTTYPE.CHAR2
        );
        var tt1 = $(".FrmSyanaiLeasePrint.busyoCDTo");
        var tVal1 = me.clsComFnc.FncTextCheck(
            tt1,
            0,
            me.clsComFnc.INPUTTYPE.CHAR2
        );
        if (tVal < 0 || tVal1 < 0) {
            if (tVal1 < 0) {
                $(".FrmSyanaiLeasePrint.busyoCDTo").select();
                $(".FrmSyanaiLeasePrint.busyoCDTo").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
            } else {
                $(".FrmSyanaiLeasePrint.busyoCDFrom").select();
                $(".FrmSyanaiLeasePrint.busyoCDFrom").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
            }

            me.clsComFnc.FncMsgBox("W9999", "入力値が不正です！");

            return;
        }

        if (tt.val().trimEnd() > tt1.val().trimEnd()) {
            me.clsComFnc.FncMsgBox("W9999", "部署コードの範囲が不正です！");
            return;
        }

        //印刷処理
        var tmpData = {
            cboYm: $(".FrmSyanaiLeasePrint.cboYM").val(),
            busyoCDFrom: $(".FrmSyanaiLeasePrint.busyoCDFrom").val(),
            busyoCDTo: $(".FrmSyanaiLeasePrint.busyoCDTo").val(),
            radAll: $(".FrmSyanaiLeasePrint.radioAll").prop("checked"),
            rad1: $(".FrmSyanaiLeasePrint.radioOption1").prop("checked"),
            rad2: $(".FrmSyanaiLeasePrint.radioOption2").prop("checked"),
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncCmdAction_Click";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    window.open(result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, tmpData, 1);
    };

    me.fnc_button_Click_validating = function () {
        if (
            me.validatingArr["current"].className.indexOf("button_CSV_print") >
            0
        ) {
            me.fnc_button_CSV_print();
        }
        if (
            me.validatingArr["current"].className.indexOf("button_cmdAction") >
            0
        ) {
            me.fnc_button_cmdAction();
        }
    };
    me.fnc_set_values_validating = function () {
        var tmpBusyoCD = "";
        if (me.validatingArr["before"].className.indexOf("busyoCDFrom") > 0) {
            tmpBusyoCD = $(".FrmSyanaiLeasePrint.busyoCDFrom").val();
            $(".FrmSyanaiLeasePrint.busyoCDTo").val(tmpBusyoCD);
        }
        if (me.validatingArr["before"].className.indexOf("busyoCDTo") > 0) {
            tmpBusyoCD = $(".FrmSyanaiLeasePrint.busyoCDTo").val();
            //$(".FrmSyanaiLeasePrint.busyoCDFrom").val(tmpBusyoCD);
        }
        return tmpBusyoCD;
    };
    /*
	 ***********************************************************************
	 処 理 名：背景色のリセット
	 関 数 名：Control_LostFocus
	 引    数：無し
	 戻 り 値：無し
	 処理説明：背景色をエラー色から正常色へと変更する
	 **********************************************************************
	 */
    me.Control_LostFocus = function () {
        if (
            document.activeElement.className.indexOf("busyoCDFrom") > 0 ||
            document.activeElement.className.indexOf("busyoCDTo") > 0
        ) {
        } else {
            $(".FrmSyanaiLeasePrint.busyoCDFrom").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmSyanaiLeasePrint.busyoCDTo").css(
                me.clsComFnc.GC_COLOR_NORMAL
            );
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    var o_R4_FrmSyanaiLeasePrint = new R4.FrmSyanaiLeasePrint();
    o_R4_FrmSyanaiLeasePrint.load();
});
