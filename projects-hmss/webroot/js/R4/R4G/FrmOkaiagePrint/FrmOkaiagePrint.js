/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20161008           #2575                   納品請求書印刷処理改善                  yangyang
 * 20200805                                   納品請求書印刷処理改善                  ciyuanchen
 * 20201119           年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。  LQS
 * 20221017           対応		R4との総額不一致チェックを行っているが不一致となった場合     yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmOkaiagePrint");

R4.FrmOkaiagePrint = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmOkaiagePrint";
    me.sys_id = "R4G";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmOkaiagePrint.cmdPreview",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmOkaiagePrint.Button3",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmOkaiagePrint.cboStartDate",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOkaiagePrint.cboEndDate",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmOkaiagePrint.cmdPreview").click(function () {
        Button2_Click();
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        // 20221017 YIN INS S
        $(".FrmOkaiagePrint.gross-error-tr").hide();
        $(".FrmOkaiagePrint.recycle-error-tr").hide();
        // 20221017 YIN INS E

        var url = "R4G/FrmOkaiagePrint/styleidload";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                if (result["data"]["STYLE_ID"] == "005") {
                    $(".FrmOkaiagePrint.chk").hide();
                    $("#mainTtl_frmOkaiagePrint").text("納品請求書印刷(店舗)");
                }
            }
        };
        ajax.send(url, "", 0);
        var currentDay = new Date();
        $(".FrmOkaiagePrint.torokuDate").datepicker("setDate", currentDay);

        //$('.FrmOkaiagePrint.radUriage').attr("checked","checked");
    };

    $(".FrmOkaiagePrint.torokuDate").on("blur", function () {
        if (clsComFnc.CheckDate($(this)) == false) {
            $(this).datepicker("setDate", new Date());
            // 20201119 lqs upd S
            // $(this).trigger("focus");
            var thisElement = this;
            window.setTimeout(function () {
                $(thisElement).trigger("focus");
            }, 0);
            // 20201119 lqs upd E
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //ラジオボタン変更

    tabIndex();

    function tabIndex() {
        if ($(".FrmOkaiagePrint.radUriage").prop("checked") == true) {
            $(".FrmOkaiagePrint.radUriage").trigger("focus");
        }
    }

    radCmnNO_CheckedChanged();

    /***********************************************************************
     処 理 名：ラジオボタン変更
     関 数 名：radCmnNO_CheckedChanged
     引    数：無し
     戻 り 値：無し
     処理説明：ラジオボタンチェック変更イベント
     ***********************************************************************/

    function radCmnNO_CheckedChanged() {
        $(".FrmOkaiagePrint.radUriage").click(function () {
            $(".FrmOkaiagePrint.cboStartDate").datepicker("option", {
                disabled: false,
            });
            $(".FrmOkaiagePrint.cboEndDate").datepicker("option", {
                disabled: false,
            });
            $(".FrmOkaiagePrint.txtCMN_NO").prop("disabled", "disabled");
            $(".FrmOkaiagePrint.txtCMN_NO").css("background-color", "");
        });
        $(".FrmOkaiagePrint.radCmnNO").click(function () {
            $(".FrmOkaiagePrint.txtCMN_NO").removeAttr("disabled");
            $(".FrmOkaiagePrint.cboStartDate").datepicker("option", {
                disabled: true,
            });
            $(".FrmOkaiagePrint.cboEndDate").datepicker("option", {
                disabled: true,
            });
        });
    }

    /***********************************************************************
     処 理 名：入力チェック
     関 数 名：fncinputCheck
     引    数：無し
     戻 り 値：True：正常終了 False:異常終了
     処理説明：入力チェック
     ***********************************************************************/

    function fncinputCheck() {
        var intRtnCD = 0;
        //売上日のﾁｪｯｸ
        if ($(".FrmOkaiagePrint.radUriage").prop("checked") == true) {
            if (
                $(".FrmOkaiagePrint.cboStartDate").val() >
                $(".FrmOkaiagePrint.cboEndDate").val()
            ) {
                clsComFnc.ObjFocus = $(".FrmOkaiagePrint.cboStartDate");
                clsComFnc.FncMsgBox(
                    "E9999",
                    "売上日の大小関係が不正です。",
                    "OK"
                );
                return false;
            }
        }
        //注文書番号のﾁｪｯｸ
        if ($(".FrmOkaiagePrint.radCmnNO").prop("checked") == true) {
            /* 20161008 yangyang upd s */
            // intRtnCD = clsComFnc.FncTextCheck($('.FrmOkaiagePrint.txtCMN_NO'), 1, clsComFnc.INPUTTYPE.CHAR2);
            // switch(intRtnCD) {
            // case -1:
            // case -2:
            // case -3:
            // clsComFnc.ObjFocus = $('.FrmOkaiagePrint.txtCMN_NO');
            // clsComFnc.FncMsgBox("W000" + (intRtnCD * (-1)).toString(), "注文書番号");
            // return false;
            // }

            for (i = 1; i < 21; i++) {
                var txtCMN_NOI = "FrmOkaiagePrint.txtCMN_NO" + i;
                intRtnCD = clsComFnc.FncTextCheck(
                    $("." + txtCMN_NOI),
                    0,
                    clsComFnc.INPUTTYPE.CHAR2
                );
                switch (intRtnCD) {
                    case -2:
                    case -3:
                        clsComFnc.ObjFocus = $("." + txtCMN_NOI);
                        clsComFnc.FncMsgBox(
                            "W000" + (intRtnCD * -1).toString(),
                            "注文書番号"
                        );
                        return false;
                }
            }
            /* 20161008 yangyang upd e */
        }
    }

    clsComFnc.MsgBoxBtnFnc.Yes = function () {
        $(".FrmOkaiagePrint.cboStartDate").trigger("focus");
    };

    /**********************************************************************
     処 理 名：			プレビューボタンクリック
     関 数 名：			Button2_Click
     引    数：無し
     戻 り 値：無し
     処理説明：ラジオボタンチェック変更イベント
     **********************************************************************/

    function Button2_Click() {
        // 20221017 YIN INS S
        $(".FrmOkaiagePrint.gross-error-tr").hide();
        $(".FrmOkaiagePrint.recycle-error-tr").hide();
        // 20221017 YIN INS E
        var strcheck;
        var flag;
        var ajaxUrl = me.sys_id + "/" + me.id + "/fnc" + me.id + "Preview";

        if ($(".FrmOkaiagePrint.radUriage").prop("checked") == true) {
            strcheck = "登録日";
            flag = 1;
        } else {
            strcheck = "注文書NO";
            flag = 2;
        }

        //--------20141203 fuxiaolin add  s
        var printMark = "none";
        if (
            $(".FrmOkaiagePrint.chk1").prop("checked") == true &&
            $(".FrmOkaiagePrint.chk2").prop("checked") == true
        ) {
            printMark = "all";
        } else {
            if ($(".FrmOkaiagePrint.chk1").prop("checked") == true) {
                printMark = "1";
            } else if ($(".FrmOkaiagePrint.chk2").prop("checked") == true) {
                printMark = "2";
            } else {
                printMark = "none";
            }
        }

        if (printMark == "none") {
            MessageBox.MessageBox(
                "出力する帳票を選択してください。",
                "HMReports",
                "OK",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }

        //--------20141203 fuxiaolin add  e

        //入力check
        if (fncinputCheck() == false) {
            return;
        }

        var startDate = $(".FrmOkaiagePrint.cboStartDate").val();
        var endDate = $(".FrmOkaiagePrint.cboEndDate").val();

        startDate = startDate.replace(/\//g, "");
        endDate = endDate.replace(/\//g, "");

        /* 20161008 yangyang add s */
        var txtCMN_NO1 = $(".FrmOkaiagePrint.txtCMN_NO1").val();
        var txtCMN_NO2 = $(".FrmOkaiagePrint.txtCMN_NO2").val();
        var txtCMN_NO3 = $(".FrmOkaiagePrint.txtCMN_NO3").val();
        var txtCMN_NO4 = $(".FrmOkaiagePrint.txtCMN_NO4").val();
        var txtCMN_NO5 = $(".FrmOkaiagePrint.txtCMN_NO5").val();
        var txtCMN_NO6 = $(".FrmOkaiagePrint.txtCMN_NO6").val();
        var txtCMN_NO7 = $(".FrmOkaiagePrint.txtCMN_NO7").val();
        var txtCMN_NO8 = $(".FrmOkaiagePrint.txtCMN_NO8").val();
        var txtCMN_NO9 = $(".FrmOkaiagePrint.txtCMN_NO9").val();
        var txtCMN_NO10 = $(".FrmOkaiagePrint.txtCMN_NO10").val();
        var txtCMN_NO11 = $(".FrmOkaiagePrint.txtCMN_NO11").val();
        var txtCMN_NO12 = $(".FrmOkaiagePrint.txtCMN_NO12").val();
        var txtCMN_NO13 = $(".FrmOkaiagePrint.txtCMN_NO13").val();
        var txtCMN_NO14 = $(".FrmOkaiagePrint.txtCMN_NO14").val();
        var txtCMN_NO15 = $(".FrmOkaiagePrint.txtCMN_NO15").val();
        var txtCMN_NO16 = $(".FrmOkaiagePrint.txtCMN_NO16").val();
        var txtCMN_NO17 = $(".FrmOkaiagePrint.txtCMN_NO17").val();
        var txtCMN_NO18 = $(".FrmOkaiagePrint.txtCMN_NO18").val();
        var txtCMN_NO19 = $(".FrmOkaiagePrint.txtCMN_NO19").val();
        var txtCMN_NO20 = $(".FrmOkaiagePrint.txtCMN_NO20").val();

        var arrCMN_NO = new Array();
        if (!txtCMN_NO1 == "" || txtCMN_NO1 == null) {
            arrCMN_NO.push(txtCMN_NO1);
        }
        if (!txtCMN_NO2 == "" || txtCMN_NO2 == null) {
            arrCMN_NO.push(txtCMN_NO2);
        }
        if (!txtCMN_NO3 == "" || txtCMN_NO3 == null) {
            arrCMN_NO.push(txtCMN_NO3);
        }
        if (!txtCMN_NO4 == "" || txtCMN_NO4 == null) {
            arrCMN_NO.push(txtCMN_NO4);
        }
        if (!txtCMN_NO5 == "" || txtCMN_NO5 == null) {
            arrCMN_NO.push(txtCMN_NO5);
        }
        if (!txtCMN_NO6 == "" || txtCMN_NO6 == null) {
            arrCMN_NO.push(txtCMN_NO6);
        }
        if (!txtCMN_NO7 == "" || txtCMN_NO7 == null) {
            arrCMN_NO.push(txtCMN_NO7);
        }
        if (!txtCMN_NO8 == "" || txtCMN_NO8 == null) {
            arrCMN_NO.push(txtCMN_NO8);
        }
        if (!txtCMN_NO9 == "" || txtCMN_NO9 == null) {
            arrCMN_NO.push(txtCMN_NO9);
        }
        if (!txtCMN_NO10 == "" || txtCMN_NO10 == null) {
            arrCMN_NO.push(txtCMN_NO10);
        }
        if (!txtCMN_NO11 == "" || txtCMN_NO11 == null) {
            arrCMN_NO.push(txtCMN_NO11);
        }
        if (!txtCMN_NO12 == "" || txtCMN_NO12 == null) {
            arrCMN_NO.push(txtCMN_NO12);
        }
        if (!txtCMN_NO13 == "" || txtCMN_NO13 == null) {
            arrCMN_NO.push(txtCMN_NO13);
        }
        if (!txtCMN_NO14 == "" || txtCMN_NO14 == null) {
            arrCMN_NO.push(txtCMN_NO14);
        }
        if (!txtCMN_NO15 == "" || txtCMN_NO15 == null) {
            arrCMN_NO.push(txtCMN_NO15);
        }
        if (!txtCMN_NO16 == "" || txtCMN_NO16 == null) {
            arrCMN_NO.push(txtCMN_NO16);
        }
        if (!txtCMN_NO17 == "" || txtCMN_NO17 == null) {
            arrCMN_NO.push(txtCMN_NO17);
        }
        if (!txtCMN_NO18 == "" || txtCMN_NO18 == null) {
            arrCMN_NO.push(txtCMN_NO18);
        }
        if (!txtCMN_NO19 == "" || txtCMN_NO19 == null) {
            arrCMN_NO.push(txtCMN_NO19);
        }
        if (!txtCMN_NO20 == "" || txtCMN_NO20 == null) {
            arrCMN_NO.push(txtCMN_NO20);
        }
        if (flag == 2) {
            if (arrCMN_NO.length < 1) {
                clsComFnc.ObjFocus = $(".FrmOkaiagePrint.txtCMN_NO1");
                clsComFnc.FncMsgBox("W0001", "注文書番号");
                return;
            }
        }
        /* 20161008 yangyang add e */

        data_array = {
            strcheck: strcheck,
            flag: flag,
            /* 20161008 yangyang upd s */
            // 'txtCMN_NO' : $('.FrmOkaiagePrint.txtCMN_NO').val(),
            txtCMN_NO: arrCMN_NO,
            /* 20161008 yangyang upd e */
            cboStartDate: startDate,
            cboEndDate: endDate,
            printMark: printMark,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["flag"] == "true") {
                if (result["msg"] == "true") {
                    //プレビュー表示
                    if (clsComFnc.FncNv(result["reports"]) != "") {
                        window.open(result["reports"]);
                    }
                    //画面項目クリア
                    $(".FrmOkaiagePrint.txtCMN_NO").val("");
                    $(".FrmOkaiagePrint.torokuDate").datepicker(
                        "setDate",
                        new Date()
                    );
                    $(".FrmOkaiagePrint.radUriage").trigger("focus");
                } else if (typeof result["msg"] == "object") {
                    var msg_flag = result["msg"]["MsgFlag"];
                    if (
                        msg_flag == "OKaiageCnt" ||
                        msg_flag == "OkaiagePrint"
                    ) {
                        var err_code = result["msg"]["error_code"];
                        var err_msg = result["msg"]["message"];
                        clsComFnc.FncMsgBox(err_code, err_msg);
                    }

                    //--------------------- 2014-01-09 仕様変更 Delete start ----------------------

                    /*
                        else if (msg_flag == "SitadoriCount") {
                        var msg_text = "注文書番号(" + clsComFnc.FncNv(result['msg']['CMN_NO']) + ")の下取データが" + clsComFnc.FncNv(result['msg']['KENSU']) + "件存在しています";
                        clsComFnc.MessageBox(msg_text, clsComFnc.GSYSTEM_NAME, clsComFnc.MessageBoxButtons.OK, clsComFnc.MessageBoxIcon.Warning, clsComFnc.MessageBoxDefaultButton.Button1);
                        }
                        */

                    //--------------------- 2014-01-09 仕様変更 Delete end ----------------------
                }

                //20221017 YIN INS S
                if (result && result["gross_error_message"] && result["gross_error_message"].length > 0) {
                    $(".FrmOkaiagePrint.gross-error-tr").show();
                    $(".FrmOkaiagePrint.gross.error-message").html(
                        result["gross_error_message"]
                    );
                }
                if (result && result["recycle_error_message"] && result["recycle_error_message"].length > 0) {
                    $(".FrmOkaiagePrint.recycle-error-tr").show();
                    $(".FrmOkaiagePrint.recycle.error-message").html(
                        result["recycle_error_message"]
                    );
                }
                //20221017 YIN INS E
            } else {
                var err_code = result["msg"]["error_code"];
                var err_msg = result["msg"]["message"];
                clsComFnc.FncMsgBox(err_code, err_msg);
            }
        };

        ajax.send(ajaxUrl, data_array, 0);

        // $.ajax({
        // url : ajaxUrl,
        // type : "post",
        // data : {
        // "request" : data_array
        // },
        // success : function(result) {
        // result = $.parseJSON(result);
        //
        // if (result['flag'] == "true") {
        // if (result['msg'] == 'true') {
        // //プレビュー表示
        // if (clsComFnc.FncNv(result['reports']) != "") {
        // window.open(result['reports']);
        // }
        // //画面項目クリア
        // $(".FrmOkaiagePrint.txtCMN_NO").val("");
        // $(".FrmOkaiagePrint.torokuDate").datepicker("setDate", new Date());
        // $('.FrmOkaiagePrint.radUriage').trigger("focus");
        // } else if ( typeof (result['msg']) == 'object') {
        // var msg_flag = result['msg']['MsgFlag'];
        // if (msg_flag == "OKaiageCnt" || msg_flag == 'OkaiagePrint') {
        //
        // var err_code = result['msg']['error_code'];
        // var err_msg = result['msg']['message'];
        // clsComFnc.FncMsgBox(err_code, err_msg);
        // } else if (msg_flag == "SitadoriCount") {
        // var msg_text = "注文書番号(" + clsComFnc.FncNv(result['msg']['CMN_NO']) + ")の下取データが" + clsComFnc.FncNv(result['msg']['KENSU']) + "件存在しています";
        // clsComFnc.MessageBox(msg_text, clsComFnc.GSYSTEM_NAME, clsComFnc.MessageBoxButtons.OK, clsComFnc.MessageBoxIcon.Warning, clsComFnc.MessageBoxDefaultButton.Button1);
        // }
        // }
        // } else {
        // var err_code = result['msg']['error_code'];
        // var err_msg = result['msg']['message'];
        // clsComFnc.FncMsgBox(err_code, err_msg);
        // }
        // }
        // });
    }

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmOkaiagePrint = new R4.FrmOkaiagePrint();
    o_R4_FrmOkaiagePrint.load();
});
