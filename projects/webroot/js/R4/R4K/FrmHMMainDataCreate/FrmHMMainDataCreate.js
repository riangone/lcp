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
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150928                  #2179                   BUG                         LI
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmHMMainDataCreate");

R4.FrmHMMainDataCreate = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmHMMainDataCreate";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.action = "";
    me.strPath = "";
    me.cboYM = "";
    me.fileMark = 0;
    me.myDate = new Date();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHMMainDataCreate.cboStartDate",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHMMainDataCreate.cboEndDate",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHMMainDataCreate.cboSyoriYM",
        //-- 20150928 LI UPD S.
        // type : "datepicker2",
        type: "datepicker3",
        //-- 20150928 LI UPD E.
        handle: "",
    });
    me.controls.push({
        id: ".FrmHMMainDataCreate.cmdAct",
        type: "button",
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

    me.init_control = function () {
        base_init_control();
    };

    $(".FrmHMMainDataCreate.radAll").change(function () {
        me.fnc_change_event_radAll();
    });
    $(".FrmHMMainDataCreate.radKobetu").change(function () {
        me.fnc_change_event_radKobetu();
    });
    $(":checkbox").change(function (e) {
        me.fnc_change_event_checkbox(e);
    });
    $(".FrmHMMainDataCreate.cmdAct").click(function () {
        me.fnc_click_event_cmdAct();
    });
    $(".FrmHMMainDataCreate.cboSyoriYM").on("blur", function () {
        if (!clsComFnc.CheckDate3($(this))) {
            window.setTimeout(function () {
                $(".FrmHMMainDataCreate.cboSyoriYM").val(me.cboYMState);
                $(".FrmHMMainDataCreate.cboSyoriYM").focus();
                $(".FrmHMMainDataCreate.cboSyoriYM").select();
                $(".FrmHMMainDataCreate.cmdAct").button("disable");
            }, 0);
        } else {
            $(".FrmHMMainDataCreate.cmdAct").button("enable");
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //---event functions---
    me.fnc_change_event_checkbox = function (e) {
        var t = e.target.className.split(" ");
        if (t[1] == "chkUriSoku") {
            if (e.target.checked == true) {
                $(".FrmHMMainDataCreate.chkGenriSoku").prop("checked", true);
                $(".FrmHMMainDataCreate.chkKijyunSoku").prop("checked", true);
                $(".FrmHMMainDataCreate.chkGenriSoku").prop(
                    "disabled",
                    "disabled"
                );
                $(".FrmHMMainDataCreate.chkKijyunSoku").prop(
                    "disabled",
                    "disabled"
                );
            } else {
                $(".FrmHMMainDataCreate.chkGenriSoku").prop("checked", false);
                $(".FrmHMMainDataCreate.chkKijyunSoku").prop("checked", false);
                $(".FrmHMMainDataCreate.chkGenriSoku").prop("disabled", "");
                $(".FrmHMMainDataCreate.chkKijyunSoku").prop("disabled", "");
            }
        }
        $(".FrmHMMainDataCreate.cboSyoriYM").ympicker("disable");
        $(".FrmHMMainDataCreate.cboStartDate").datepicker("disable");
        $(".FrmHMMainDataCreate.cboEndDate").datepicker("disable");

        if (
            $(".FrmHMMainDataCreate.chkUriSoku").prop("checked") == true ||
            $(".FrmHMMainDataCreate.chkKaikeiSoku").prop("checked") == true
        ) {
            $(".FrmHMMainDataCreate.cboStartDate").datepicker("enable");
            $(".FrmHMMainDataCreate.cboEndDate").datepicker("enable");
        }

        if (
            $(".FrmHMMainDataCreate.chkGenriSoku").prop("checked") == true ||
            $(".FrmHMMainDataCreate.chkKijyunSoku").prop("checked") == true
        ) {
            if (
                $(".FrmHMMainDataCreate.chkGenriSoku").prop("disabled") !=
                    true ||
                $(".FrmHMMainDataCreate.chkKijyunSoku").prop("disabled") != true
            ) {
                $(".FrmHMMainDataCreate.cboSyoriYM").ympicker("enable");
            }
        }
    };
    me.fnc_change_event_radKobetu = function () {
        if ($(".FrmHMMainDataCreate.radKobetu").prop("checked") == true) {
            me.setGroupBoxStatus("");
            $(".FrmHMMainDataCreate.div.cboSyoriYM").css(
                "visibility",
                "visible"
            );
            $(
                ".ui-ympicker-inline.ui-datepicker.ui-widget.ui-widget-content.ui-helper-clearfix.ui-corner-all"
            ).css("display", "none");

            $(".FrmHMMainDataCreate.cboSyoriYM").ympicker("disable");
            $(".FrmHMMainDataCreate.cboStartDate").datepicker("disable");
            $(".FrmHMMainDataCreate.cboEndDate").datepicker("disable");

            if (
                $(".FrmHMMainDataCreate.chkUriSoku").prop("checked") == true ||
                $(".FrmHMMainDataCreate.chkKaikeiSoku").prop("checked") == true
            ) {
                $(".FrmHMMainDataCreate.cboStartDate").datepicker("enable");
                $(".FrmHMMainDataCreate.cboEndDate").datepicker("enable");
            } else {
                if (
                    $(".FrmHMMainDataCreate.chkGenriSoku").prop("checked") ==
                        true ||
                    $(".FrmHMMainDataCreate.chkKijyunSoku").prop("checked")
                ) {
                    $(".FrmHMMainDataCreate.cboSyoriYM").ympicker("enable");
                }
            }
        }
    };
    me.fnc_change_event_radAll = function () {
        if ($(".FrmHMMainDataCreate.radAll").prop("checked") == true) {
            me.setGroupBoxStatus("disabled");
            //$(".FrmHMMainDataCreate.cboSyoriYM").datepicker("disable");
            $(".FrmHMMainDataCreate.div.cboSyoriYM").css(
                "visibility",
                "hidden"
            );

            $(".FrmHMMainDataCreate.cboStartDate").datepicker("enable");
            $(".FrmHMMainDataCreate.cboEndDate").datepicker("enable");
        }
    };
    me.fnc_click_event_cmdAct = function () {
        var tflg = false;
        var startDate = $(".FrmHMMainDataCreate.cboStartDate").val();
        var endDate = $(".FrmHMMainDataCreate.cboEndDate").val();
        startDate = startDate.replace("/", "");
        startDate = startDate.replace("/", "");
        endDate = endDate.replace("/", "");
        endDate = endDate.replace("/", "");
        //売上速報データ作成処理が実行される場合

        //-----
        me.url = me.sys_id + "/" + me.id + "/fncUriMakeJknSel";
        me.data = {
            startDate: startDate,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (
                    $(".FrmHMMainDataCreate.radAll").prop("checked") == true ||
                    ($(".FrmHMMainDataCreate.radKobetu").prop("checked") ==
                        true &&
                        $(".FrmHMMainDataCreate.chkUriSoku").prop("checked") ==
                            true)
                ) {
                    //売上データ作成範囲ｺﾝﾄﾛｰﾙﾏｽﾀに更新対象日付に設定した日付が設定されていること
                    if (result["data"].length == 0) {
                        //ｴﾗｰメッセージ表示
                        clsComFnc.FncMsgBox(
                            "E9999",
                            "画面で指定された対象更新年月日の売上データ作成範囲データが設定されていません。<br/>売上データ作成範囲コントロールマスタメンテナンス画面より登録して下さい"
                        );
                        return;
                    }
                    if (result["data"][0]["END_UPD_DATE"] < endDate) {
                        //指定した画面の対象更新年月日が月をまたぐ処理になる場合はエラー
                        clsComFnc.FncMsgBox(
                            "E9999",
                            "画面で指定された対象更新年月日(期間)は売上速報データ作成で複数の処理年月を対象とします。<br/>処理年月をまたぐ場合はまたがないように複数回に分けて実行してください。<br/>開始対象更新年月日：" +
                                startDate +
                                "で指定可能な終了対象更新年月日は" +
                                clsComFnc.FncNv(
                                    result["data"][0]["END_UPD_DATE"]
                                )
                        );
                        return;
                    }
                }
                //メッセージを出力する
                if (
                    $(".FrmHMMainDataCreate.radKobetu").prop("checked") == true
                ) {
                    if (
                        $(".FrmHMMainDataCreate.chkUriSoku").prop("checked") ==
                            false &&
                        $(".FrmHMMainDataCreate.chkGenriSoku").prop(
                            "checked"
                        ) == false &&
                        $(".FrmHMMainDataCreate.chkKijyunSoku").prop(
                            "checked"
                        ) == false &&
                        $(".FrmHMMainDataCreate.chkKaikeiSoku").prop(
                            "checked"
                        ) == false
                    ) {
                        tflg = true;
                        //実行します、よろしいですか？
                        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_ClickYes;
                        clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
                        clsComFnc.FncMsgBox(
                            "QY999",
                            "メインデータの作成のみを行います。よろしいですか？"
                        );
                    }
                }
                if (!tflg) {
                    me.cmdAct_ClickYes();
                }
            } else {
                clsComFnc.FncMsgBox(result["data"]);
            }
        };
        ajax.send(me.url, me.data, 0);
        //-----
    };
    //---functions---
    me.FrmHMMainDataCreate_load = function () {
        $(".FrmHMMainDataCreate.div.cboSyoriYM").css("visibility", "hidden");
        $(
            ".ui-ympicker-inline.ui-datepicker.ui-widget.ui-widget-content.ui-helper-clearfix.ui-corner-all"
        ).css("display", "none");

        $(".FrmHMMainDataCreate.chkUriMain").prop("checked", true);
        $(".FrmHMMainDataCreate.chkKaikeiMain").prop("checked", true);
        $(".FrmHMMainDataCreate.chkUriMain").prop("disabled", "disabled");
        $(".FrmHMMainDataCreate.chkKaikeiMain").prop("disabled", "disabled");
        $(".FrmHMMainDataCreate.radAll").prop("checked", true);
        if ($(".FrmHMMainDataCreate.radAll").prop("checked") == true) {
            me.setGroupBoxStatus("disabled");
            $(".FrmHMMainDataCreate.cboSyoriYM").ympicker("disable");
        }
        var nowdate =
            me.myDate.getFullYear().toString() +
            "/" +
            (parseInt(me.myDate.getMonth().toString()) + 1) +
            "/" +
            (parseInt(me.myDate.getDate().toString()) - 1);
        $(".FrmHMMainDataCreate.cboEndDate").val(nowdate);
        $(".FrmHMMainDataCreate.cboStartDate").val(nowdate);
        //-- 20150928 LI UPD S.
        //$(".FrmHMMainDataCreate.cboSyoriYM").val(nowdate);
        $(".FrmHMMainDataCreate.cboSyoriYM").val(
            me.myDate.getFullYear().toString() +
                (parseInt(me.myDate.getMonth().toString()) + 1)
        );
        //-- 20150928 LI UPD E.
        //処理年月を取得する
        me.url = me.sys_id + "/" + me.id + "/fncUriMakeTargetDt";
        me.data = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length <= 0) {
                    $(".FrmHMMainDataCreate.cmdAct").button("disable");
                    MessageBox.MessageBox(
                        "This is no row! ",
                        "HMReports",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                }
                //処理年月を取得する
                if (
                    clsComFnc.FncNv(result["data"][0]["URI_SOKU_START_DT"]) !=
                    ""
                ) {
                    $(".FrmHMMainDataCreate.cboStartDate").val(
                        result["data"][0]["URI_SOKU_START_DT"]
                    );
                    if (
                        clsComFnc.FncNv(result["data"][0]["URI_SOKU_END_DT"]) !=
                        ""
                    ) {
                        $(".FrmHMMainDataCreate.cboEndDate").val(
                            result["data"][0]["URI_SOKU_END_DT"]
                        );
                    }
                } else {
                    if (
                        clsComFnc.FncNv(
                            result["data"][0]["KAIKEI_SOKU_START_DT"]
                        ) != ""
                    ) {
                        $(".FrmHMMainDataCreate.cboStartDate").val(
                            result["data"][0]["KAIKEI_SOKU_START_DT"]
                        );
                        if (
                            clsComFnc.FncNv(
                                result["data"][0]["KAIKEI_SOKU_END_DT"]
                            ) != ""
                        ) {
                            $(".FrmHMMainDataCreate.cboEndDate").val(
                                result["data"][0]["KAIKEI_SOKU_END_DT"]
                            );
                        }
                    }
                }

                if (
                    clsComFnc.FncNv(result["data"][0]["GENRI_SOKU_START_MT"]) !=
                    ""
                ) {
                    //-- 20150928 LI UPD S.
                    $(".FrmHMMainDataCreate.cboSyoriYM").val(
                        result["data"][0]["GENRI_SOKU_START_MT"].replace(
                            "/",
                            ""
                        )
                    );
                    //-- 20150928 LI UPD E.
                } else {
                    if (
                        clsComFnc.FncNv(
                            result["data"][0]["KIJYUN_SOKU_START_MT"]
                        ) != ""
                    ) {
                        //-- 20150928 LI UPD S.
                        $(".FrmHMMainDataCreate.cboSyoriYM").val(
                            result["data"][0]["KIJYUN_SOKU_START_MT"].replace(
                                "/",
                                ""
                            )
                        );
                        //-- 20150928 LI UPD E.
                    }
                }
                me.cboYMState = $(".FrmHMMainDataCreate.cboSyoriYM").val();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        ajax.send(me.url, me.data, 0);
    };
    me.setGroupBoxStatus = function (status) {
        $(".FrmHMMainDataCreate.chkUriSoku").prop("disabled", status);
        $(".FrmHMMainDataCreate.chkGenriSoku").prop("disabled", status);
        $(".FrmHMMainDataCreate.chkKijyunSoku").prop("disabled", status);
        $(".FrmHMMainDataCreate.chkKaikeiSoku").prop("disabled", status);
    };
    me.cmdAct_ClickYes = function () {
        //dialog
        $("#tmpDealMsgDialog").dialog({
            hide: true, //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen: true,
            height: 470,
            width: 570,
            modal: false, //蒙层（弹出会影响页面大小）
            title: "データ作成",
            resizable: false,
            buttons: {
                close: function () {
                    $("#tmpDealMsgDialog").dialog("close");
                },
            },
        });
        var startDate = $(".FrmHMMainDataCreate.cboStartDate").val();
        var endDate = $(".FrmHMMainDataCreate.cboEndDate").val();
        var syoriYM = $(".FrmHMMainDataCreate.cboSyoriYM").val();
        startDate = startDate.replace("/", "");
        startDate = startDate.replace("/", "");
        endDate = endDate.replace("/", "");
        endDate = endDate.replace("/", "");
        syoriYM = syoriYM.replace("/", "");
        syoriYM = syoriYM.replace("/", "");
        me.url = me.sys_id + "/" + me.id + "/fncCmdAct_ClickYes";
        me.data = {
            startDate: startDate,
            endDate: endDate,
            syoriYM: syoriYM,
            radAll: $(".FrmHMMainDataCreate.radAll").prop("checked"),
            radKobetu: $(".FrmHMMainDataCreate.radKobetu").prop("checked"),
            chkUriSoku: $(".FrmHMMainDataCreate.chkUriSoku").prop("checked"),
            chkGenriSoku: $(".FrmHMMainDataCreate.chkGenriSoku").prop(
                "checked"
            ),
            chkKijyunSoku: $(".FrmHMMainDataCreate.chkKijyunSoku").prop(
                "checked"
            ),
            chkKaikeiSoku: $(".FrmHMMainDataCreate.chkKaikeiSoku").prop(
                "checked"
            ),
            chkUriMain: $(".FrmHMMainDataCreate.chkUriMain").prop("checked"),
            chkKaikeiMain: $(".FrmHMMainDataCreate.chkKaikeiMain").prop(
                "checked"
            ),
        };
        $("#tmpDealMsgDialog").html("");
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            var str = "";
            if (
                $(".FrmHMMainDataCreate.radAll").prop("checked") == true ||
                ($(".FrmHMMainDataCreate.radKobetu").prop("checked") == true &&
                    $(".FrmHMMainDataCreate.chkUriSoku").prop("checked") ==
                        true)
            ) {
                if (
                    result["iProjectCode"] == 1 ||
                    result["iProjectCode"] == 2 ||
                    result["iProjectCode"] == 3 ||
                    result["iProjectCode"] == 4
                ) {
                    str +=
                        " ******************************************************** <br/>";
                    str +=
                        " ************     売上速報データ作成　実行中          ************* <br/>";
                    str +=
                        "********************************************************* <br/>";
                    str += "<br/>";
                    str += "<br/>";
                }
            }
            if (
                $(".FrmHMMainDataCreate.radAll").prop("checked") == true ||
                ($(".FrmHMMainDataCreate.radKobetu").prop("checked") == true &&
                    $(".FrmHMMainDataCreate.chkGenriSoku").prop("checked") ==
                        true)
            ) {
                if (
                    result["iProjectCode"] == 2 ||
                    result["iProjectCode"] == 3 ||
                    result["iProjectCode"] == 4
                ) {
                    str +=
                        " ******************************************************** <br/>";
                    str +=
                        " **********     限界利益速報データ作成　実行中        ************* <br/>";
                    str +=
                        "********************************************************* <br/>";
                    str += "<br/>";
                    str += "<br/>";
                }
            }
            if (
                $(".FrmHMMainDataCreate.radAll").prop("checked") == true ||
                ($(".FrmHMMainDataCreate.radKobetu").prop("checked") == true &&
                    $(".FrmHMMainDataCreate.chkKijyunSoku").prop("checked") ==
                        true)
            ) {
                if (
                    result["iProjectCode"] == 3 ||
                    result["iProjectCode"] == 4
                ) {
                    str +=
                        " ******************************************************** <br/>";
                    str +=
                        " **********    基準会計速報データ作成　　実行中      ************* <br/>";
                    str +=
                        "********************************************************* <br/>";
                    str += "<br/>";
                    str += "<br/>";
                }
            }
            if (
                $(".FrmHMMainDataCreate.radAll").prop("checked") == true ||
                ($(".FrmHMMainDataCreate.radKobetu").prop("checked") == true &&
                    $(".FrmHMMainDataCreate.chkKaikeiSoku").prop("checked") ==
                        true)
            ) {
                if (result["iProjectCode"] == 4) {
                    str +=
                        " ******************************************************** <br/>";
                    str +=
                        " **********      会計速報データ作成　　実行中         ************* <br/>";
                    str +=
                        "********************************************************* <br/>";
                    str += "<br/>";
                    str += "<br/>";
                }
            }
            $("#tmpDealMsgDialog").html(str);
            $("#tmpDealMsgDialog").dialog("open");
            if (result["result"] == true) {
                clsComFnc.FncMsgBox("I0005");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        ajax.send(me.url, me.data, 0);
    };
    me.cmdAct_ClickNo = function () {};
    //---load---
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmHMMainDataCreate_load();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHMMainDataCreate = new R4.FrmHMMainDataCreate();
    o_R4_FrmHMMainDataCreate.load();
});
