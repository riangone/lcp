/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                                      担当
 * YYYYMMDD            #ID                          XXXXXX                                                   GSDL
 * 20201119            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。  LQS
 * * ----------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmListPrint");

R4.FrmListPrint = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmListPrint";
    me.sys_id = "R4G";

    me.data = "";
    me.methodFlag = false;
    me.CMNNO = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmListPrint.cmdPreview",
        type: "button",
        handle: "",
    });
    //20180601 YIN INS S
    me.controls.push({
        id: ".FrmListPrint.cmdPreviewagain",
        type: "button",
        handle: "",
    });
    //20180601 YIN INS E

    me.controls.push({
        id: ".FrmListPrint.cboStartDate",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmListPrint.cboEndDate",
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

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        var currentDay = new Date();
        $(".FrmListPrint.torokuDate").datepicker("setDate", currentDay);
    };

    $(".FrmListPrint.torokuDate").on("blur", function () {
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
        if ($(".FrmListPrint.radUriage").prop("checked") == true) {
            $(".FrmListPrint.radUriage").trigger("focus");
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
        $(".FrmListPrint.radUriage").click(function () {
            $(".FrmListPrint.cboStartDate").datepicker("option", {
                disabled: false,
            });
            $(".FrmListPrint.cboEndDate").datepicker("option", {
                disabled: false,
            });
            $(".FrmListPrint.txtCMN_NO").prop("disabled", "disabled");
            $(".FrmListPrint.txtCMN_NO").css("background-color", "");
        });
        $(".FrmListPrint.radCmnNO").click(function () {
            $(".FrmListPrint.txtCMN_NO").removeAttr("disabled");
            $(".FrmListPrint.cboStartDate").datepicker("option", {
                disabled: true,
            });
            $(".FrmListPrint.cboEndDate").datepicker("option", {
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
        if ($(".FrmListPrint.radUriage").prop("checked") == true) {
            if (
                $(".FrmListPrint.cboStartDate").val() >
                $(".FrmListPrint.cboEndDate").val()
            ) {
                clsComFnc.ObjFocus = $(".FrmListPrint.cboStartDate");
                clsComFnc.FncMsgBox(
                    "E9999",
                    "売上日の大小関係が不正です。",
                    "OK"
                );
                return false;
            }
        }
        //注文書番号のﾁｪｯｸ
        if ($(".FrmListPrint.radCmnNO").prop("checked") == true) {
            intRtnCD = clsComFnc.FncTextCheck(
                $(".FrmListPrint.txtCMN_NO"),
                1,
                clsComFnc.INPUTTYPE.CHAR2
            );
            switch (intRtnCD) {
                case -1:
                case -2:
                case -3:
                    clsComFnc.ObjFocus = $(".FrmListPrint.txtCMN_NO");
                    clsComFnc.FncMsgBox(
                        "W000" + (intRtnCD * -1).toString(),
                        "注文書番号"
                    );
                    return false;
            }
        }
    }

    clsComFnc.MsgBoxBtnFnc.Yes = function () {
        $(".FrmListPrint.cboStartDate").trigger("focus");
    };

    $(".FrmListPrint.cmdPreview").click(function () {
        //20180601 YIN UPD S
        // me.cmdPreview_Click(true);
        me.cmdPreview_Click(false);
        //20180601 YIN UPD E
    });
    //20180601 YIN INS S
    $(".FrmListPrint.cmdPreviewagain").click(function () {
        me.cmdPreview_Click(true);
    });

    /**********************************************************************
     処理概要：架装明細プレビュー画面表示
     **********************************************************************/
    //20180601 YIN UPD S
    // me.cmdPreview_Click = function()
    me.cmdPreview_Click = function (
        againflag //20180601 YIN UPD E
    ) {
        var strcheck;
        var flag;
        if ($(".FrmListPrint.radUriage").prop("checked") == true) {
            strcheck = "登録日";
            flag = 1;
        } else {
            strcheck = "注文書NO";
            flag = 2;
        }

        var startDate = $(".FrmListPrint.cboStartDate").val();
        var endDate = $(".FrmListPrint.cboEndDate").val();
        startDate = startDate.replace(/\//g, "");
        endDate = endDate.replace(/\//g, "");
        var txtCMNNOVal = $(".FrmListPrint.txtCMN_NO").val().trimEnd();

        //入力check
        if (fncinputCheck() == false) {
            return;
        }

        var funcName = "cmdPreviewClick";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var insertArray = {
            strcheck: strcheck,
            flag: flag,
            startDate: startDate,
            endDate: endDate,
            CMN_NO: txtCMNNOVal,
            //20180601 YIN INS S
            againflag: againflag,
            //20180601 YIN INS E
        };
        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (typeof result["resultLog1"] != "undefined") {
                clsComFnc.MsgBoxBtnFnc.Close = function () {
                    clsComFnc.MsgBoxBtnFnc.Close = function () {
                        if (typeof result["resultLog2"] != "undefined") {
                            clsComFnc.MsgBoxBtnFnc.Close = function () {
                                clsComFnc.FncMsgBox(
                                    result["resultLog2"]["MsgID"],
                                    result["resultLog2"]["Msg"]
                                );
                            };
                            clsComFnc.FncMsgBox(
                                result["resultLog2"]["MsgID"],
                                result["resultLog2"]["data"]
                            );
                        }
                    };
                    clsComFnc.FncMsgBox(
                        result["resultLog1"]["MsgID"],
                        result["resultLog1"]["Msg"]
                    );
                };
                clsComFnc.FncMsgBox(
                    result["resultLog1"]["MsgID"],
                    result["resultLog1"]["data"]
                );
            }
            switch (result["result"]) {
                case "warning":
                case false:
                    $(".FrmListPrint.txtCMN_NO").trigger("focus");
                    clsComFnc.FncMsgBox(result["MsgID"], result["data"]);
                    break;
                case true:
                    var objrpt = result["report"];
                    window.open(objrpt);
                    $(".FrmListPrint.txtCMN_NO").trigger("focus");
                    break;

                default:
                    break;
            }
        };
        ajax.send(url, me.data, 0);

        $(".FrmListPrint.txtCMN_NO").val("");
    };

    return me;
};

$(function () {
    var o_R4_FrmListPrint = new R4.FrmListPrint();
    o_R4_FrmListPrint.load();

    o_R4_R4.FrmListPrint = o_R4_FrmListPrint;
});
