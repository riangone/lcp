Namespace.register("KRSS.FrmSyasyuArariChkListKRSS_KRSS");
KRSS.FrmSyasyuArariChkListKRSS_KRSS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyasyuArariChkListKRSS";
    me.sys_id = "KRSS";
    me.cboYMStartState = "";
    me.cboYMEndState = "";
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cmdExportExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd",
        type: "datepicker3",
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

    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cmdExportExcel").click(function () {
        me.button_exportExcel_Click();
    });
    $(".KRSS.KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList").click(
        function () {
            if (
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList").prop(
                    "checked"
                )
            ) {
                var tmpVal = $(
                    ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart"
                ).val();
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.DIVcboYMStart").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
                    "disabled",
                    true
                );
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(
                    tmpVal
                );
            }
        }
    );
    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radMeisai").click(function () {
        if (
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radMeisai").prop("checked")
        ) {
            var tmpVal = $(
                ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart"
            ).val();

            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.DIVcboYMStart").unblock();
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
                "disabled",
                false
            );
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(tmpVal);
        }
    });
    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radBaseh").click(function () {
        if (
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radBaseh").prop("checked")
        ) {
            var tmpVal = $(
                ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart"
            ).val();

            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.DIVcboYMStart").unblock();
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
                "disabled",
                false
            );
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(tmpVal);
        }
    });
    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radDouble").click(function () {
        if (
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radDouble").prop("checked")
        ) {
            var tmpVal = $(
                ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart"
            ).val();

            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.DIVcboYMStart").unblock();
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
                "disabled",
                false
            );
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(tmpVal);
        }
    });

    //-----
    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").on(
        "blur",
        function () {
            if (
                me.clsComFnc.CheckDate3(
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart")
                ) == false
            ) {
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").trigger(
                    "focus"
                );
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(
                    me.cboYMStartState
                );
            }
        }
    );
    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").on("blur", function () {
        if (
            me.clsComFnc.CheckDate3(
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd")
            ) == false
        ) {
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").trigger(
                "focus"
            );
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").val(
                me.cboYMEndState
            );
        }
    });
    //-----
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmSyasyuArariChkListKRSS_KRSS_load();
    };
    me.FrmSyasyuArariChkListKRSS_KRSS_load = function () {
        me.formLoad();
        $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.DIVcboYMStart").block({
            overlayCSS: {
                opacity: 0,
            },
        });
        $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
            "disabled",
            true
        );
        $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList").prop(
            "checked",
            true
        );
        $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList").trigger("focus");
    };
    /*
	 '**********************************************************************
	 '処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
	 '関 数 名：frmKanrSyukei_Load
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：初期設定
	 '**********************************************************************
	 */
    me.formLoad = function () {
        var url = me.sys_id + "/" + me.id + "/" + "formLoad";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //コントロールマスタ存在ﾁｪｯｸ
                if (result["data"].length > 0) {
                    //コンボボックスに当月年月を設定
                    $tmpDateEnd = result["data"][0]["TOUGETU"].substring(0, 6);
                    $tmpDateStart = result["data"][0]["KISYU_YMD"].substring(
                        0,
                        6
                    );
                    me.cboYMStartState = $tmpDateStart;
                    me.cboYMEndState = $tmpDateEnd;
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").val(
                        $tmpDateEnd
                    );
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(
                        $tmpDateStart
                    );
                    //画面項目ｸﾘｱ
                    //me.subFormClear();
                    //コンボボックスに値を設定
                    $(
                        ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart"
                    ).trigger("focus");
                } else {
                    var myDate = new Date();
                    var tmpMonth = (myDate.getMonth() + 1).toString();
                    if (tmpMonth.length < 2) {
                        tmpMonth = "0" + tmpMonth.toString();
                    }
                    var tmpNowDate =
                        myDate.getFullYear().toString() + tmpMonth.toString();
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").val(
                        tmpNowDate
                    );
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val(
                        tmpNowDate
                    );

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
	 '処 理 名：excel
	 '関 数 名：button_exportExcel_Click
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：excel出力する
	 '**********************************************************************

	 */
    me.button_exportExcel_Click = function () {
        //入力チェック
        if (
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radMeisai").prop(
                "checked"
            ) ||
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radBaseh").prop(
                "checked"
            ) ||
            $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radDouble").prop("checked")
        ) {
            if (
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").val() <
                $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").val()
            ) {
                me.clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
                if (
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart").prop(
                        "disabled"
                    )
                ) {
                    $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd").trigger(
                        "focus"
                    );
                }
                return;
            }
        }

        /*if ($(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList").prop("checked"))
		 {*/
        var url = me.sys_id + "/" + me.id + "/" + "fncCmdExportExcel";
        $data = {
            cboYMEnd: $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMEnd")
                .val()
                .replace("/", ""),
            cboYMStart: $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.cboYMStart")
                .val()
                .replace("/", ""),
            radChkList: $(
                ".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radChkList"
            ).prop("checked"),
            radMeisai: $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radMeisai").prop(
                "checked"
            ),
            radBaseh: $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radBaseh").prop(
                "checked"
            ),
            radDouble: $(".KRSS.FrmSyasyuArariChkListKRSS_KRSS.radDouble").prop(
                "checked"
            ),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            console.log(result);
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["data"]["TFException"]) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        result["data"]["messageContent"]
                    );
                } else {
                    if (!result["data"]["messageContent"] == "") {
                        me.clsComFnc.FncMsgBox(
                            result["data"]["messageCode"],
                            result["data"]["messageContent"]
                        );
                    } else {
                        me.clsComFnc.FncMsgBox(result["data"]["messageCode"]);
                    }
                }
            }
        };
        me.ajax.send(url, $data, 1);
    };

    //};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    var o_KRSS_FrmSyasyuArariChkListKRSS_KRSS =
        new KRSS.FrmSyasyuArariChkListKRSS_KRSS();
    o_KRSS_FrmSyasyuArariChkListKRSS_KRSS.load();
});
