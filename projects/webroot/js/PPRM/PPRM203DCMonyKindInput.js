/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM203DCMonyKindInput");

PPRM.PPRM203DCMonyKindInput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();
    var ODR_Jscript = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    me.id = "PPRM203DCMonyKindInput";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.strREF_PRG = "";
    me.strMODE = "";
    me.strTCD = "";
    me.strHDATE = "";
    me.strHNO = "";
    me.hidUpdDate = "";
    //画面フラグ設定
    me.hidGamenFLG = "";
    me.strReadOnlyFlg = "";
    me.btnType = false;
    me.FncMsgType = false;

    if (
        gdmz.SessionBusyoCD != "" &&
        gdmz.SessionBusyoCD != null &&
        gdmz.SessionBusyoCD != undefined
    ) {
        me.SessionBusyoCD = gdmz.SessionBusyoCD;
    } else {
        me.SessionBusyoCD = "";
    }

    if (
        gdmz.SessionTenpoCD != "" &&
        gdmz.SessionTenpoCD != null &&
        gdmz.SessionTenpoCD != undefined
    ) {
        me.SessionTenpoCD = gdmz.SessionTenpoCD;
    } else {
        me.SessionTenpoCD = "";
    }

    // me.SessionBusyoCD = 154;
    // me.SessionTenpoCD = "224";

    //20170907 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170907 ZHANGXIAOLEI INS E

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnTenpoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnHJMSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnDisp",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.txtHJMDate",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnRowAdd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnRowDel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnTouroku",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM203DCMonyKindInput.btnClose",
        type: "button",
        handle: "",
    });

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.strREF_PRG = requestdata["REFPRG"];
        me.strMODE = requestdata["MODE"];
        me.strTCD = requestdata["TCD"];
        me.strHDATE = requestdata["HDATE"];
        me.strHNO = requestdata["HNO"];
    }
    localStorage.removeItem("requestdata");

    me.before_close = function () {};

    if (
        me.strREF_PRG != "" &&
        me.strREF_PRG != undefined &&
        me.strREF_PRG != null
    ) {
        $(".PPRM203DCMonyKindInput.body").dialog({
            autoOpen: false,
            width: 1050,
            //20170907 lqs UPD S
            // height : 750,
            height: me.ratio === 1.5 ? 540 : 650,
            //20170907 lqs UPD E
            modal: true,
            title: "金種表入力",
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM203DCMonyKindInput.body").remove();
            },
        });
        $(".PPRM203DCMonyKindInput.body").dialog("open");
    }

    //店舗コード検索ボタン押下
    $(".PPRM203DCMonyKindInput.btnTenpoSearch").click(function () {
        me.openTenpoSearch();
    });

    //日締№検索ボタン押下
    $(".PPRM203DCMonyKindInput.btnHJMSearch").click(function () {
        me.openHJMSearch();
    });

    //表示ボタン押下
    $(".PPRM203DCMonyKindInput.btnDisp").mousedown(
        function () // $(".PPRM203DCMonyKindInput.btnDisp").click(function()
        {
            me.btnType = false;
            me.FncMsgType = false;
            var txtbtnDisp = $(".PPRM203DCMonyKindInput.btnDisp").text();
            switch (txtbtnDisp) {
                case "表示":
                    me.btnType = true;
                    break;
                // case "日締№変更":
                // me.btnDisp_Click();
                // me.btnType = false;
                // break;
                default:
                    break;
            }
            // me.btnDisp_Click();
        }
    );

    //表示ボタン押下
    $(".PPRM203DCMonyKindInput.btnDisp").click(function () {
        if (me.FncMsgType == false) {
            me.btnDisp_Click();
        }
    });

    //行追加ボタン押下
    $(".PPRM203DCMonyKindInput.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });

    //行削除ボタン押下
    $(".PPRM203DCMonyKindInput.btnRowDel").click(function () {
        me.btnRowDel_Click();
    });

    //登録ボタン押下
    $(".PPRM203DCMonyKindInput.btnTouroku").click(function () {
        me.btnType = false;
        me.FncMsgType = false;
        me.btnTouroku_Click();
    });

    //削除ボタン押下
    $(".PPRM203DCMonyKindInput.btnDelete").click(function () {
        me.btnType = false;
        me.FncMsgType = false;
        me.btnDelete_Click();
    });

    //閉じるボタン押下
    $(".PPRM203DCMonyKindInput.btnClose").click(function () {
        me.windowClose();
    });

    $(".PPRM203DCMonyKindInput.txtTenpoCD").change(function () {
        me.setCallBackInit("txtTenpoCD");
    });

    $(".PPRM203DCMonyKindInput.txtHJMNo").change(function () {
        me.setCallBackInit("txtHJMNo");
    });

    $(".PPRM203DCMonyKindInput.txtHJMDate").change(function () {
        me.setCallBackInit("txtHJMDate");
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_10000").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_10000").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_5000").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_10000").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_5000").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_5000").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_5000").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_2000").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_2000").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_2000").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_2000").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_1000").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_1000").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_1000").keypress(function (event) {
        me.CheckNum(event);
    });
    $(".PPRM203DCMonyKindInput.txtMaisu_1000").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_500").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_500").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_500").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_500").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_100").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_100").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_100").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_100").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_50").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_50").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_50").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_50").on("keydown", function (event) {
        if (event.keyCode == "13") {
            $(".PPRM203DCMonyKindInput.txtMaisu_10").trigger("focus");
        }
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_10").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_10").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_5").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_5").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_1").on("blur", function () {
        me.lostFocus();
    });

    $(".PPRM203DCMonyKindInput.txtMaisu_1").keypress(function (event) {
        me.CheckNum(event);
    });

    $(".PPRM203DCMonyKindInput.txtHJMDate").on("blur", function () {
        if (!ODR_Jscript.DateFOut($(this))) {
            me.FncMsgType = true;
        }
    });
    // 2017/09/15 CI INS S

    $(".PPRM203DCMonyKindInput.txtRiyu").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    // 2017/09/15 CI INS E

    // 2017/09/11 CI INS S
    $(".PPRM203DCMonyKindInput.txtTenpoCD").on("blur", function () {
        //店舗名取得
        $(".PPRM203DCMonyKindInput.lblTenpo").val(
            me.FncGetBusyoNM1($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
        );
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    // 2017/09/11 CI INS E

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();

        //20170907 ZHANGXIAOLEI UPD S
        // me.PPRM203DCMonyKindInput_load();
        me.getAllBusyoNM();
        //20170907 ZHANGXIAOLEI UPD E
    };

    //20170907 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部の店舗コードと店舗名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の店舗コードと店舗名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetALLBusyoNM";
        var selectObj = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                me.BusyoArr = result["data"];
            }
            me.PPRM203DCMonyKindInput_load();
        };
        ajax.send(url, selectObj, 0);
    };

    me.FncGetBusyoNM1 = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }
            for (key in me.BusyoArr) {
                if (strCD == me.BusyoArr[key]["TENPO_CD"]) {
                    return me.BusyoArr[key]["BUSYO_NM"];
                }
            }
        } catch (e) {
            return "";
        }
    };
    //20170907 ZHANGXIAOLEI INS E

    //ページ初期化
    me.PPRM203DCMonyKindInput_load = function () {
        me.subFormInt(true);

        document
            .querySelectorAll(".numberInput")
            .forEach(function (inputElement) {
                inputElement.addEventListener("keydown", function (e) {
                    const allowedKeys = [
                        "Backspace",
                        "ArrowLeft",
                        "ArrowRight",
                        "Tab",
                        "Delete",
                        "Enter",
                        "Escape",
                    ];
                    if (
                        allowedKeys.includes(e.key) ||
                        (e.key >= "0" && e.key <= "9")
                    ) {
                        return;
                    }
                    e.preventDefault();
                });
                inputElement.addEventListener("input", function () {
                    this.value = this.value.replace(/[^0-9]/g, "");
                });
            });
        //画面により初期表示を設定
        switch (me.strREF_PRG) {
            case "":
                //メニュー用

                //画面フラグ設定
                me.hidGamenFLG = "1";

                //画面初期設定
                me.subFormInt1();

                break;

            case "PPRM100ApproveStateSearch":
                //承認状況検索用

                if (me.strMODE == "NEW") {
                    //新規登録

                    //画面フラグ設定
                    me.hidGamenFLG = "2";

                    //画面初期設定
                    me.subFormInt2();
                } else {
                    //編集・削除

                    //画面フラグ設定
                    me.hidGamenFLG = "3";

                    //画面初期設定
                    me.subFormInt3();
                }
                break;

            case "PPRM202DCSearch":
                //日締履歴データ検索用

                //画面フラグ設定
                me.hidGamenFLG = "4";

                //画面初期設定
                me.subFormInt4();

                break;

            default:
                break;
        }
    };

    //'***********************************************************************
    //'処 理 名：画面初期化（共通）
    //'関 数 名：me.subFormInt
    //'引 数   ：blnInit(Boolean)
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'***********************************************************************
    me.subFormInt = function (blnInit) {
        if (blnInit == true) {
            $(".PPRM203DCMonyKindInput.txtTenpoCD").val("");
            $(".PPRM203DCMonyKindInput.txtHJMDate").val("");
            $(".PPRM203DCMonyKindInput.txtHJMNo").val("");
            $(".PPRM203DCMonyKindInput.lblTenpo").val("");
        }
        $(".PPRM203DCMonyKindInput.txtMaisu_10000").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_5000").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_2000").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_1000").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_500").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_100").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_50").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_10").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_5").val("");
        $(".PPRM203DCMonyKindInput.txtMaisu_1").val("");

        $(".PPRM203DCMonyKindInput.lblKin_10000").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_5000").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_2000").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_1000").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_500").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_100").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_50").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_10").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_5").text("0");
        $(".PPRM203DCMonyKindInput.lblKin_1").text("0");
        $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text("0");
        $(".PPRM203DCMonyKindInput.lblKoukaGoukei").text("0");
        $(".PPRM203DCMonyKindInput.lblKogiteGoukei").text("0");
        $(".PPRM203DCMonyKindInput.lblTyouboGoukei").text("0");
        $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text("0");

        if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125) {
            $(".PPRM203DCMonyKindInput.pnlLabel_Riyu").css("display", "none");
            $(".PPRM203DCMonyKindInput.pnlText_Riyu").css("display", "none");
            $(".PPRM203DCMonyKindInput.pnlDummyLabel").css("display", "block");
            $(".PPRM203DCMonyKindInput.pnlDummyText").css("display", "block");
            $(".PPRM203DCMonyKindInput.pnlDummyLabel").css(
                "visibility",
                "hidden"
            );
            $(".PPRM203DCMonyKindInput.pnlDummyText").css(
                "visibility",
                "hidden"
            );
        } else {
            $(".PPRM203DCMonyKindInput.pnlLabel_Riyu").css("display", "block");
            $(".PPRM203DCMonyKindInput.pnlText_Riyu").css("display", "block");
            $(".PPRM203DCMonyKindInput.pnlDummyLabel").css("display", "none");
            $(".PPRM203DCMonyKindInput.pnlDummyText").css("display", "none");
            $(".PPRM203DCMonyKindInput.pnlDummyLabel").css(
                "visibility",
                "hidden"
            );
            $(".PPRM203DCMonyKindInput.pnlDummyText").css(
                "visibility",
                "hidden"
            );
            $(".PPRM203DCMonyKindInput.txtRiyu").prop("readonly", false);
            //20171013 lqs INS S
            $(".PPRM203DCMonyKindInput.txtRiyu").prop("disabled", false);
            //20171013 lqs INS E
        }
    };

    //'***********************************************************************
    //'処 理 名：画面初期化（メニューから）
    //'関 数 名：me.subFormInt1
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'***********************************************************************
    me.subFormInt1 = function () {
        //ボタン
        $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("enable");
        $(".PPRM203DCMonyKindInput.btnHJMSearch").button("enable");
        $(".PPRM203DCMonyKindInput.btnRowAdd").button("enable");
        $(".PPRM203DCMonyKindInput.btnRowDel").button("enable");
        $(".PPRM203DCMonyKindInput.btnTouroku").button("enable");
        $(".PPRM203DCMonyKindInput.btnDelete").button("disable");
        $(".PPRM203DCMonyKindInput.btnClose").button("disable");
        $(".PPRM203DCMonyKindInput.btnClose").css("display", "none");

        //テキストボックス
        var currentDay = new Date();
        $(".PPRM203DCMonyKindInput.txtHJMDate").datepicker(
            "setDate",
            currentDay.getFullYear + currentDay.getMonth + currentDay.getDate
        );
        if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125) {
            //店舗コード
            $(".PPRM203DCMonyKindInput.txtTenpoCD").val(me.SessionTenpoCD);

            //日締№&店舗名
            me.getHJMNO(true);
        } else {
            me.setButton();

            //権限設定（初期値）
            if (me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) != "") {
                me.SubSetEnabled_OnPageLoad(
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                );
            } else {
                me.getDataAll();
            }
        }
    };

    //'***********************************************************************
    //'処 理 名：画面初期化（ダイアログ：新規）
    //'関 数 名：me.subFormInt2
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'***********************************************************************
    me.subFormInt2 = function () {
        //ボタン
        $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("enable");
        $(".PPRM203DCMonyKindInput.btnHJMSearch").button("enable");
        $(".PPRM203DCMonyKindInput.btnRowAdd").button("enable");
        $(".PPRM203DCMonyKindInput.btnRowDel").button("enable");
        $(".PPRM203DCMonyKindInput.btnTouroku").button("enable");
        $(".PPRM203DCMonyKindInput.btnDelete").button("disable");
        $(".PPRM203DCMonyKindInput.btnClose").button("enable");
        $(".PPRM203DCMonyKindInput.btnClose").css("display", "inline-block");

        //テキストボックス
        var currentDay = new Date();
        $(".PPRM203DCMonyKindInput.txtHJMDate").datepicker(
            "setDate",
            currentDay.getFullYear + currentDay.getMonth + currentDay.getDate
        );
        if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125) {
            //店舗コード
            $(".PPRM203DCMonyKindInput.txtTenpoCD").val(me.SessionTenpoCD);

            //日締№&店舗名
            me.getHJMNO(true);
        } else {
            me.setButton();

            //権限設定（初期値）
            if (me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) != "") {
                me.SubSetEnabled_OnPageLoad(
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                );
            } else {
                me.getDataAll();
            }
        }
    };

    //'***********************************************************************
    //'処 理 名：画面初期化（ダイアログ：編集・削除）
    //'関 数 名：me.subFormInt3
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'***********************************************************************
    me.subFormInt3 = function () {
        //ボタン
        $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("disable");
        $(".PPRM203DCMonyKindInput.btnHJMSearch").button("disable");
        $(".PPRM203DCMonyKindInput.btnTouroku").button("enable");
        $(".PPRM203DCMonyKindInput.btnDelete").button("enable");
        $(".PPRM203DCMonyKindInput.btnClose").button("enable");
        $(".PPRM203DCMonyKindInput.btnClose").css("display", "inline-block");
        $(".PPRM203DCMonyKindInput.btnDisp").css("display", "none");

        //テキストボックス
        $(".PPRM203DCMonyKindInput.txtTenpoCD").val(me.strTCD);
        $(".PPRM203DCMonyKindInput.txtHJMNo").val(me.strHNO);
        var HDATE = me.strHDATE.substr(0, 10);
        $(".PPRM203DCMonyKindInput.txtHJMDate").val(HDATE);

        $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtHJMNo").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtHJMDate").prop("readonly", true);
        //20171013 lqs INS S
        $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("disabled", "disabled");
        $(".PPRM203DCMonyKindInput.txtHJMNo").prop("disabled", "disabled");
        $(".PPRM203DCMonyKindInput.txtHJMDate").prop("disabled", "disabled");
        $(".PPRM203DCMonyKindInput.rdbSyurui").prop("disabled", true);
        //20171013 lqs INS E
        //20170907 lqs UPD S
        $(".PPRM203DCMonyKindInput.block").block({
            overlayCSS: {
                opacity: 0,
            },
        });
        //20170907 lqs UPD E

        //ラベル
        me.FncGetBusyoNM(true);
    };

    //'***********************************************************************
    //'処 理 名：画面初期化（ダイアログ：参照用）
    //'関 数 名：me.subFormInt4
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'***********************************************************************
    me.subFormInt4 = function () {
        //ボタン
        $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("disable");
        $(".PPRM203DCMonyKindInput.btnHJMSearch").button("disable");
        $(".PPRM203DCMonyKindInput.btnRowAdd").button("disable");
        $(".PPRM203DCMonyKindInput.btnRowDel").button("disable");
        $(".PPRM203DCMonyKindInput.btnTouroku").button("disable");
        $(".PPRM203DCMonyKindInput.btnDelete").button("disable");
        $(".PPRM203DCMonyKindInput.btnClose").button("enable");
        $(".PPRM203DCMonyKindInput.btnClose").css("display", "inline-block");
        $(".PPRM203DCMonyKindInput.btnDisp").css("display", "none");

        //テキストボックス
        $(".PPRM203DCMonyKindInput.txtTenpoCD").val(me.strTCD);
        $(".PPRM203DCMonyKindInput.txtHJMNo").val(me.strHNO);
        var HDATE = me.strHDATE.substr(0, 10);
        $(".PPRM203DCMonyKindInput.txtHJMDate").val(HDATE);

        $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtHJMNo").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtHJMDate").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_10000").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_5000").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_2000").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_1000").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_500").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_100").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_50").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_10").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_5").prop("readonly", true);
        $(".PPRM203DCMonyKindInput.txtMaisu_1").prop("readonly", true);
        //20171013 lqs INS S
        $(".PPRM203DCMonyKindInput").find("input").prop("disabled", "disabled");
        //20171013 lqs INS E
        //20170907 lqs UPD S
        $(".PPRM203DCMonyKindInput.block").block({
            overlayCSS: {
                opacity: 0,
            },
        });
        //20170907 lqs UPD E

        me.strReadOnlyFlg = "1";

        //if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125)
        if (me.SessionBusyoCD == 122 || me.SessionBusyoCD == 125) {
            //帳簿上の残高と実際の残高の不一致の理由
            $(".PPRM203DCMonyKindInput.txtRiyu").prop("readonly", true);
            //20171013 lqs INS S
            $(".PPRM203DCMonyKindInput.txtRiyu").prop("disabled", "disabled");
            //20171013 lqs INS E
        }

        //ラベル
        me.FncGetBusyoNM(true);
        //me.FncGetBusyoNM(txtTenpoCD);
    };

    //'***********************************************************************
    //'処 理 名：日締№取得
    //'関 数 名：me.getHJMNO
    //'引 数   ：flag(Boolean)
    //'戻 り 値：なし
    //'処理説明：getHJMNO
    //'***********************************************************************
    me.getHJMNO = function (flag) {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var strNO = $(".PPRM203DCMonyKindInput.txtHJMDate")
            .val()
            .replaceAll("/", "")
            .substr(2, 6);
        var HJMNo = txtTenpoCD + strNO;

        var url = me.sys_id + "/" + me.id + "/getHJMNO";
        var data = {
            txtTenpoCD: txtTenpoCD,
            HJMNo: HJMNo,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                data = result["data"];
                //日締№セット
                $(".PPRM203DCMonyKindInput.txtHJMNo").val(data);
            }

            if (flag == false) {
                return;
            }

            //店舗名取得
            me.FncGetBusyoNM(true);
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：店舗名取得（関数）
    //'関 数 名：me.FncGetBusyoNM
    //'引 数 2 ：flag(Boolean)
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'***********************************************************************
    me.FncGetBusyoNM = function (flag) {
        //20170907 ZHANGXIAOLEI UPD S
        // var url = me.sys_id + "/" + me.id + "/getBusyoNM";
        // var data =
        // {
        // strCD : strCD
        // };
        //
        // ajax.receive = function(result)
        // {
        // result = $.parseJSON(result);
        //
        // if (result['result'] == true)
        // {
        // lblTenpo = result['data'];
        //
        // //店舗名セット
        // $(".PPRM203DCMonyKindInput.lblTenpo").val(lblTenpo);
        //alert("000");
        $(".PPRM203DCMonyKindInput.lblTenpo").val(
            me.FncGetBusyoNM1($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
        );
        //20170907 ZHANGXIAOLEI UPD E

        if (flag == false) {
            return;
        }

        switch (me.strREF_PRG) {
            case "":
                me.setButton();

                //権限設定（初期値）
                if (
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) !=
                    ""
                ) {
                    me.SubSetEnabled_OnPageLoad(
                        me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                    );
                }

                break;

            case "PPRM100ApproveStateSearch":
                if (me.strMODE == "NEW") {
                    me.setButton();

                    //権限設定（初期値）
                    if (
                        me.rtrim(
                            $(".PPRM203DCMonyKindInput.txtTenpoCD").val()
                        ) != ""
                    ) {
                        me.SubSetEnabled_OnPageLoad(
                            me.rtrim(
                                $(".PPRM203DCMonyKindInput.txtTenpoCD").val()
                            )
                        );
                    }
                } else {
                    //経理承認確認
                    me.managerConfirm();
                }

                break;

            case "PPRM202DCSearch":
                me.getDataAll();

                break;
        }

        //20170907 ZHANGXIAOLEI DEL S
        // }
        // else
        // {
        // clsComFnc.FncMsgBox("E9999", result['data']);
        // return;
        // }
        //
        // };
        // ajax.send(url, data, 0);
        //20170907 ZHANGXIAOLEI DEL E
    };

    //'***********************************************************************
    //'処 理 名：経理承認確認
    //'関 数 名：me.managerConfirm
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：経理承認確認
    //'***********************************************************************
    me.managerConfirm = function () {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();

        var url = me.sys_id + "/" + me.id + "/managerConfirm";
        var data = {
            txtTenpoCD: txtTenpoCD,
            txtHJMNo: txtHJMNo,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            strFLG = result["data"];

            //経理承認後の場合変更不可にする
            if (strFLG == "1") {
                $(".PPRM203DCMonyKindInput.txtMaisu_10000").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_5000").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_2000").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_1000").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_500").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_100").prop(
                    "readonly",
                    true
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_50").prop("readonly", true);
                $(".PPRM203DCMonyKindInput.txtMaisu_10").prop("readonly", true);
                $(".PPRM203DCMonyKindInput.txtMaisu_5").prop("readonly", true);
                $(".PPRM203DCMonyKindInput.txtMaisu_1").prop("readonly", true);
                //20171013 lqs INS S
                $(".PPRM203DCMonyKindInput.txtMaisu_10000").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_5000").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_2000").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_1000").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_500").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_100").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_50").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_10").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_5").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtMaisu_1").prop(
                    "disabled",
                    "disabled"
                );
                //20171013 lqs INS E

                me.strReadOnlyFlg = "1";

                $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("disable");
                $(".PPRM203DCMonyKindInput.btnHJMSearch").button("disable");
                $(".PPRM203DCMonyKindInput.btnRowAdd").button("disable");
                $(".PPRM203DCMonyKindInput.btnRowDel").button("disable");
                $(".PPRM203DCMonyKindInput.btnTouroku").button("disable");
                $(".PPRM203DCMonyKindInput.btnDelete").button("disable");
                $(".PPRM203DCMonyKindInput.btnClose").button("enable");

                if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125) {
                    //帳簿上の残高と実際の残高の不一致の理由セット
                    $(".PPRM203DCMonyKindInput.txtRiyu").prop("readonly", true);
                    //20171013 lqs INS S
                    $(".PPRM203DCMonyKindInput.txtRiyu").prop(
                        "disabled",
                        "disabled"
                    );
                    //20171013 lqs INS E
                }

                //権限設定（初期値）
                if (
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) !=
                    ""
                ) {
                    me.SubSetEnabled_OnPageLoad(
                        me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                    );
                }
            } else {
                //権限設定（初期値）
                if (
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) !=
                    ""
                ) {
                    me.SubSetEnabled_OnPageLoad(
                        me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                    );
                }
            }
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：すべてのボタンの設定
    //'関 数 名：me.setButton
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：すべてのボタンの設定
    //'***********************************************************************
    me.setButton = function () {
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();
        if (txtHJMNo.length == 12) {
            $(".PPRM203DCMonyKindInput.btnDisp").css("display", "block");
            $(".PPRM203DCMonyKindInput.btnDisp").text("日締№変更");

            //入力領域可见
            $(".PPRM203DCMonyKindInput.pnlInput").css("display", "block");
            //入力領域項目を不活性にする
            me.InputKoumokuEnable(false);

            $(".PPRM203DCMonyKindInput.btnTouroku").css(
                "display",
                "inline-block"
            );
            $(".PPRM203DCMonyKindInput.btnDelete").css(
                "display",
                "inline-block"
            );
            //20170907 lqs UPD S
            $(".PPRM203DCMonyKindInput.block").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            //20170907 lqs UPD E
        } else {
            $(".PPRM203DCMonyKindInput.btnDisp").css("display", "block");
            $(".PPRM203DCMonyKindInput.btnDisp").text("表示");

            //入力領域不可见
            $(".PPRM203DCMonyKindInput.pnlInput").css("display", "none");
            //入力領域項目を不活性にする
            me.InputKoumokuEnable(true);

            $(".PPRM203DCMonyKindInput.btnTouroku").css("display", "none");
            $(".PPRM203DCMonyKindInput.btnDelete").css("display", "none");
        }
    };

    //'***********************************************************************
    //'処 理 名：権限設定（初期値）
    //'関 数 名：me.SubSetEnabled_OnPageLoad
    //'引 数   ：txtTenpoCD(店舗コード)
    //'戻 り 値：なし
    //'処理説明：権限設定（初期値）
    //'***********************************************************************
    me.SubSetEnabled_OnPageLoad = function (txtTenpoCD) {
        var url = me.sys_id + "/" + me.id + "/pPRM203DCMonyKindInputLoad";
        var data = {
            txtTenpoCD: txtTenpoCD,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            for (key in result["data"]) {
                $(".PPRM203DCMonyKindInput." + key).prop(
                    "disabled",
                    result["data"][key]
                );
            }

            me.getDataAll();
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：金種別残高データ取得/小切手データ取得/帳簿上の残高取得/実際の残高取得
    //'関 数 名：me.getDataAll
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：金種別残高データ取得/小切手データ取得/帳簿上の残高取得/実際の残高取得
    //'***********************************************************************
    me.getDataAll = function () {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();

        var url = me.sys_id + "/" + me.id + "/getDataAll";
        var data = {
            txtTenpoCD: txtTenpoCD,
            txtHJMNo: txtHJMNo,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            me.SetDataAll(result);
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：金種別残高表示/小切手表示/帳簿上の残高表示/実際の残高表示
    //'関 数 名：me.SetDataAll
    //'引 数   ：data
    //'戻 り 値：なし
    //'処理説明：金種別残高表示/小切手表示/帳簿上の残高表示/実際の残高表示
    //'***********************************************************************
    me.SetDataAll = function (data) {
        if (data["result"] == true) {
            //金種別残高表示
            var setKinsyuData = data["setKinsyuData"];
            var lngShihei = 0;
            var lngKouka = 0;

            for (i = 0; i < setKinsyuData.length; i++) {
                switch (setKinsyuData[i]["MNY_KIND"]) {
                    case "0":
                        //紙幣（10000～1000）
                        switch (setKinsyuData[i]["MEISAI_NO"]) {
                            case "1":
                                $(".PPRM203DCMonyKindInput.txtMaisu_10000").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_10000").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "2":
                                $(".PPRM203DCMonyKindInput.txtMaisu_5000").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_5000").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "3":
                                $(".PPRM203DCMonyKindInput.txtMaisu_2000").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_2000").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "4":
                                $(".PPRM203DCMonyKindInput.txtMaisu_1000").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_1000").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            default:
                                break;
                        }
                        //紙幣合計
                        lngShihei =
                            lngShihei + parseInt(setKinsyuData[i]["ZANDAKA"]);
                        break;

                    case "1":
                        //硬貨（500～1）
                        switch (setKinsyuData[i]["MEISAI_NO"]) {
                            case "1":
                                $(".PPRM203DCMonyKindInput.txtMaisu_500").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_500").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "2":
                                $(".PPRM203DCMonyKindInput.txtMaisu_100").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_100").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "3":
                                $(".PPRM203DCMonyKindInput.txtMaisu_50").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_50").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "4":
                                $(".PPRM203DCMonyKindInput.txtMaisu_10").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_10").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "5":
                                $(".PPRM203DCMonyKindInput.txtMaisu_5").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_5").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            case "6":
                                $(".PPRM203DCMonyKindInput.txtMaisu_1").val(
                                    setKinsyuData[i]["MAISU"]
                                );
                                $(".PPRM203DCMonyKindInput.lblKin_1").text(
                                    me.EditKanma(setKinsyuData[i]["ZANDAKA"])
                                );
                                break;

                            default:
                                break;
                        }
                        //硬貨合計
                        lngKouka =
                            lngKouka + parseInt(setKinsyuData[i]["ZANDAKA"]);
                        break;

                    default:
                        break;
                }
            }

            //小計①②セット
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text(
                me.EditKanma(lngShihei)
            );
            $(".PPRM203DCMonyKindInput.lblKoukaGoukei").text(
                me.EditKanma(lngKouka)
            );

            //小切手表示
            var setKogiteData = data["setKogiteData"];
            var lngZandaka = 0;
            var lngKogiteGoukei = 0;

            $(".PPRM203DCMonyKindInput.Kogite tbody").html("");
            for (j = 0; j < setKogiteData.length; j++) {
                strHtml = "";
                strHtml += "<tr>" + "\r\n";
                strHtml += " <td style='width:180px;'>" + "\r\n";
                //20171009 lqs UPS S
                // strHtml += "  <input class='PPRM203DCMonyKindInput Enter Tab txtKINSYU' style='width:170px;text-align: left;' maxLength =\'20\' value=\'" + setKogiteData[j]['KINSYU'] + "\'/>" + "\r\n";
                strHtml +=
                    "  <input class='PPRM203DCMonyKindInput EnterKey Tab txtKINSYU' style='width:165px;text-align: left;' maxLength ='20' value='" +
                    setKogiteData[j]["KINSYU"] +
                    "'/>" +
                    "\r\n";
                //20171009 lqs UPS E
                strHtml += " </td>" + "\r\n";
                strHtml += " <td style='width:151px;'>" + "\r\n";
                //20171009 lqs UPS S
                // strHtml += "  <input class='PPRM203DCMonyKindInput Enter Tab txtZANDAKA' style='width:95px;text-align: right;' maxLength =\'13\' value=\'" + me.EditKanma(setKogiteData[j]['ZANDAKA']) + "\'/>" + "\r\n";
                //20171009 lqs UPS E
                strHtml +=
                    "  <input class='PPRM203DCMonyKindInput EnterKey Tab txtZANDAKA' style='width:92px;text-align: right;' maxLength ='13' value='" +
                    me.EditKanma(setKogiteData[j]["ZANDAKA"]) +
                    "'/>" +
                    "\r\n";
                strHtml += "  <span>円</span>" + "\r\n";
                strHtml += " </td>" + "\r\n";
                strHtml += "</tr>";

                $(".PPRM203DCMonyKindInput.Kogite tbody").append(strHtml);
                lngZandaka = parseInt(setKogiteData[j]["ZANDAKA"]);
                lngKogiteGoukei = lngKogiteGoukei + lngZandaka;

                $(".PPRM203DCMonyKindInput.txtZANDAKA").on("blur", function () {
                    me.gridZandakaSum();
                });
                $(".PPRM203DCMonyKindInput.txtZANDAKA").keypress(function (
                    event
                ) {
                    me.CheckNum(event);
                });
            }
            //20171009 lqs INS S
            me.EnterKeyDown();
            //20171009 lqs INS

            if (me.strReadOnlyFlg == "1") {
                $(".PPRM203DCMonyKindInput.txtKINSYU").prop("readonly", true);
                $(".PPRM203DCMonyKindInput.txtZANDAKA").prop("readonly", true);
                //20171013 lqs INS S
                $(".PPRM203DCMonyKindInput.txtKINSYU").prop(
                    "disabled",
                    "disabled"
                );
                $(".PPRM203DCMonyKindInput.txtZANDAKA").prop(
                    "disabled",
                    "disabled"
                );
                //20171013 lqs INS E
            }

            //小計③にセット
            $(".PPRM203DCMonyKindInput.lblKogiteGoukei").text(
                me.EditKanma(lngKogiteGoukei)
            );

            //帳簿上の残高表示
            var getTyouboZandaka = data["getTyouboZandaka"];
            var lngGoukei = 0;

            if (getTyouboZandaka.length > 0) {
                lngGoukei = parseInt(
                    clsComFnc.FncNz(getTyouboZandaka[0]["KON_HJM_EGK_KKS_GK"])
                );
                $(".PPRM203DCMonyKindInput.lblTyouboGoukei").text(
                    me.EditKanma(lngGoukei)
                );
            }

            //実際の残高表示
            var getJissaiZandaka = data["getJissaiZandaka"];
            var lngGoukei2 = 0;

            if (getJissaiZandaka.length > 0) {
                lngGoukei2 = parseInt(
                    clsComFnc.FncNv(getJissaiZandaka[0]["ZAN_GK"])
                );
                $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text(
                    me.EditKanma(lngGoukei2)
                );
                $(".PPRM203DCMonyKindInput.txtRiyu").val(
                    clsComFnc.FncNv(getJissaiZandaka[0]["FUICHI_RIYU"])
                );
                me.hidUpdDate = clsComFnc.FncNv(
                    getJissaiZandaka[0]["UPD_DATE"]
                );
            } else {
                $(".PPRM203DCMonyKindInput.btnDelete").button("disable");
            }

            //フォーカス設定
            switch (me.strREF_PRG) {
                case "":
                    if (
                        $(".PPRM203DCMonyKindInput.txtTenpoCD").prop(
                            "readonly"
                        ) == true
                    ) {
                        $(".PPRM203DCMonyKindInput.txtMaisu_10000").trigger(
                            "focus"
                        );
                    } else {
                        $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger(
                            "focus"
                        );
                    }

                    break;

                case "PPRM100ApproveStateSearch":
                    if (me.strMODE == "NEW") {
                        //新規登録

                        if (
                            $(".PPRM203DCMonyKindInput.txtTenpoCD").prop(
                                "readonly"
                            ) == true
                        ) {
                            $(".PPRM203DCMonyKindInput.txtMaisu_10000").trigger(
                                "focus"
                            );
                        } else {
                            $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger(
                                "focus"
                            );
                        }
                    } else {
                        //編集・削除

                        $(".PPRM203DCMonyKindInput.txtMaisu_10000").trigger(
                            "focus"
                        );
                    }

                    break;

                case "PPRM202DCSearch":
                    //日締履歴データ検索用

                    $(".PPRM203DCMonyKindInput.btnClose").trigger("focus");

                    break;
            }
        } else {
            clsComFnc.FncMsgBox("E9999", data["data"]);
            return;
        }
    };

    me.EnterKeyDown = function () {
        var $inp = $(".EnterKey");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();
                    var nxtIdx = $inp.index(this);
                    for (var i = nxtIdx; i < $inp.length; i++) {
                        if (i != $inp.length - 1) {
                            if (
                                $(".EnterKey:eq(" + (i + 1) + ")").prop(
                                    "disabled"
                                ) != true
                            ) {
                                $(".EnterKey:eq(" + (i + 1) + ")").trigger(
                                    "focus"
                                );
                                $(".EnterKey:eq(" + (i + 1) + ")").select();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        } else {
                            for (var j = 0; j < $inp.length; j++) {
                                if (
                                    $(".EnterKey:eq(" + j + ")").prop(
                                        "disabled"
                                    ) != true
                                ) {
                                    $(".EnterKey:eq(" + j + ")").trigger(
                                        "focus"
                                    );
                                    $(".EnterKey:eq(" + j + ")").select();
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        }
                    }
                }
            }
        });
    };

    //'***********************************************************************
    //'処 理 名：入力領域項目の活性・不活性設定
    //'関 数 名：me.InputKoumokuEnable
    //'引 数   ：blnEnabled(Boolean)
    //'戻 り 値：なし
    //'処理説明：入力領域項目の活性・不活性設定
    //'***********************************************************************
    me.InputKoumokuEnable = function (blnEnabled) {
        if (blnEnabled == true) {
            //20170912 lqs INS S
            $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger("focus");
            //20170912 lqs INS E
            $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("readonly", false);
            $(".PPRM203DCMonyKindInput.txtHJMDate").prop("readonly", false);
            $(".PPRM203DCMonyKindInput.txtHJMNo").prop("readonly", false);
            //20170912 lqs INS S
            //20171013 lqs INS S
            $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("disabled", false);
            $(".PPRM203DCMonyKindInput.txtHJMDate").prop("disabled", false);
            $(".PPRM203DCMonyKindInput.txtHJMNo").prop("disabled", false);
            $(".PPRM203DCMonyKindInput.rdbSyurui").prop("disabled", false);
            //20171013 lqs INS E
            $(".PPRM203DCMonyKindInput.block").unblock();
            //20170912 lqs INS E
            $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("enable");
            $(".PPRM203DCMonyKindInput.btnHJMSearch").button("enable");
        } else {
            $(".PPRM203DCMonyKindInput.txtTenpoCD").prop("readonly", true);
            $(".PPRM203DCMonyKindInput.txtHJMDate").prop("readonly", true);
            $(".PPRM203DCMonyKindInput.txtHJMNo").prop("readonly", true);
            //20171013 lqs INS S
            $(".PPRM203DCMonyKindInput.txtTenpoCD").prop(
                "disabled",
                "disabled"
            );
            $(".PPRM203DCMonyKindInput.txtHJMDate").prop(
                "disabled",
                "disabled"
            );
            $(".PPRM203DCMonyKindInput.txtHJMNo").prop("disabled", "disabled");
            $(".PPRM203DCMonyKindInput.rdbSyurui").prop("disabled", true);
            //20171013 lqs INS E
            //20170912 lqs INS S
            $(".PPRM203DCMonyKindInput.block").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            //20170912 lqs INS E
            $(".PPRM203DCMonyKindInput.btnTenpoSearch").button("disable");
            $(".PPRM203DCMonyKindInput.btnHJMSearch").button("disable");
        }
    };

    //'***********************************************************************
    //'処 理 名：店舗コード検索
    //'関 数 名：me.openTenpoSearch
    //'引 数   ：me.TKB
    //'戻 り 値：TenpoCD(店舗コード)
    //'戻 り 値：TenpoNM(店舗名)
    //'処理説明：店舗コード検索
    //'***********************************************************************
    me.openTenpoSearch = function () {
        // me.TKB = 1;
        me.url = "PPRM/PPRM705R4BusyoSearch";

        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: me.TKB,
        //     })
        // );

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    var TenpoCD = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var TenpoNM = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;

                    if (TenpoCD != "") {
                        $(".PPRM203DCMonyKindInput.txtTenpoCD").val(TenpoCD);
                    }
                    if (TenpoNM != "") {
                        $(".PPRM203DCMonyKindInput.lblTenpo").val(TenpoNM);
                    }

                    me.getHJMNO(false);
                }
            }
            $("." + me.id + "." + "dialogs705").append(result);
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };

        ajax.send(me.url, me.data, 0);
    };

    //'***********************************************************************
    //'処 理 名：日締№検索
    //'関 数 名：me.openHJMSearch
    //'引 数 1 ：me.REFPRG
    //'引 数 2 ：me.FTCD
    //'引 数 3 ：me.TTCD
    //'引 数 4 ：me.FDATE
    //'引 数 5 ：me.TDATE
    //'戻 り 値：HJMNo(日締№)
    //'処理説明：日締№検索
    //'***********************************************************************
    me.openHJMSearch = function () {
        me.REFPRG = "PPRM203DCMonyKindInput";
        me.FTCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        me.TTCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        me.FDATE = $(".PPRM203DCMonyKindInput.txtHJMDate").val();
        me.TDATE = $(".PPRM203DCMonyKindInput.txtHJMDate").val();

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: me.REFPRG,
                FTCD: me.FTCD,
                TTCD: me.TTCD,
                FDATE: me.FDATE,
                TDATE: me.TDATE,
            })
        );

        me.url = "PPRM/PPRM202DCSearch";

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM202DCSearch.flg == 1) {
                    var txtHJMNo = o_PPRM_PPRM.PPRM202DCSearch.HJMNo;
                    $(".PPRM203DCMonyKindInput.txtHJMNo").val(txtHJMNo);

                    var txtTenpoCD = txtHJMNo.substr(0, 3);
                    $(".PPRM203DCMonyKindInput.txtTenpoCD").val(txtTenpoCD);

                    var txtHJMDate =
                        "20" +
                        txtHJMNo.substr(3, 2) +
                        "/" +
                        txtHJMNo.substr(5, 2) +
                        "/" +
                        txtHJMNo.substr(7, 2);
                    $(".PPRM203DCMonyKindInput.txtHJMDate").val(txtHJMDate);

                    me.FncGetBusyoNM(false);
                }
            }
            $("." + me.id + "." + "dialogs202").append(result);
            o_PPRM_PPRM.PPRM202DCSearch.before_close = before_close;
        };

        ajax.send(me.url, me.data, 0);
    };

    //'***********************************************************************
    //'処 理 名：表示又は日締め№変更処理
    //'関 数 名：me.btnDisp_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：表示又は日締め№変更処理
    //'***********************************************************************
    me.btnDisp_Click = function () {
        if (me.FncMsgType) {
            me.FncMsgType = false;
            return;
        }

        var txtbtnDisp = $(".PPRM203DCMonyKindInput.btnDisp").text();
        switch (txtbtnDisp) {
            case "表示":
                //日締№ チェック
                var txtHJMNo = me.rtrim(
                    $(".PPRM203DCMonyKindInput.txtHJMNo").val()
                );
                if (txtHJMNo == "") {
                    $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
                    me.btnType = false;
                    me.FncMsgType = true;
                    clsComFnc.FncMsgBox("E0001_PPRM", "日締№");
                    return;
                } else if (txtHJMNo.length < 12) {
                    $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
                    me.btnType = false;
                    me.FncMsgType = true;
                    clsComFnc.FncMsgBox("E0003_PPRM", "日締№");
                    return;
                }

                $(".PPRM203DCMonyKindInput.btnDisp").text("日締№変更");
                $(".PPRM203DCMonyKindInput.btnDelete").button("enable");

                //画面初期化（共通）
                me.subFormInt(false);

                //入力領域显示
                $(".PPRM203DCMonyKindInput.pnlInput").css("display", "block");

                //入力領域項目を不活性にする
                me.InputKoumokuEnable(false);

                $(".PPRM203DCMonyKindInput.btnTouroku").css(
                    "display",
                    "inline-block"
                );
                $(".PPRM203DCMonyKindInput.btnDelete").css(
                    "display",
                    "inline-block"
                );

                //フォーカス設定
                $(".PPRM203DCMonyKindInput.txtMaisu_10000").trigger("focus");

                //権限設定（初期値）
                if (
                    me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val()) !=
                    ""
                ) {
                    me.SubSetEnabled_OnPageLoad(
                        me.rtrim($(".PPRM203DCMonyKindInput.txtTenpoCD").val())
                    );
                } else {
                    me.getDataAll();
                }

                break;

            case "日締№変更":
                $(".PPRM203DCMonyKindInput.btnDisp").text("表示");

                //入力領域隐藏
                $(".PPRM203DCMonyKindInput.pnlInput").css("display", "none");

                //入力領域項目を不活性にする
                me.InputKoumokuEnable(true);

                $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger("focus");

                $(".PPRM203DCMonyKindInput.btnTouroku").css("display", "none");
                $(".PPRM203DCMonyKindInput.btnDelete").css("display", "none");

                me.FncGetBusyoNM(false);

                break;
            default:
                break;
        }
    };

    //'***********************************************************************
    //'処 理 名：行追加
    //'関 数 名：me.btnRowAdd_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：一番下に行を追加する
    //'***********************************************************************
    me.btnRowAdd_Click = function () {
        strHtml = "";
        strHtml += "<tr>" + "\r\n";
        strHtml += " <td style='width:180px;'>" + "\r\n";
        //20171009 lqs UPS S
        // strHtml += "  <input class='PPRM203DCMonyKindInput Enter Tab txtKINSYU' style='width:170px;text-align: left;' maxLength =\'20\' />" + "\r\n";
        strHtml +=
            "  <input class='PPRM203DCMonyKindInput EnterKey Tab txtKINSYU' style='width:165px;text-align: left;' maxLength ='20' />" +
            "\r\n";
        //20171009 lqs UPS E
        strHtml += " </td>" + "\r\n";
        strHtml += " <td style='width:151px;'>" + "\r\n";
        //20171009 lqs UPS S
        // strHtml += "  <input class='PPRM203DCMonyKindInput Enter Tab txtZANDAKA' style='width:95px;text-align: right;' maxLength =\'13\' />" + "\r\n";
        strHtml +=
            "  <input class='PPRM203DCMonyKindInput EnterKey Tab txtZANDAKA' style='width:92px;text-align: right;' maxLength ='13' />" +
            "\r\n";
        //20171009 lqs UPS E
        strHtml += "  <span>円</span>" + "\r\n";
        strHtml += " </td>" + "\r\n";
        strHtml += "</tr>";

        $(".PPRM203DCMonyKindInput.Kogite tbody").append(strHtml);
        $(".PPRM203DCMonyKindInput.txtZANDAKA").on("blur", function () {
            me.gridZandakaSum();
        });
        $(".PPRM203DCMonyKindInput.txtZANDAKA").keypress(function (event) {
            me.CheckNum(event);
        });

        var lblShiheiGoukei = parseInt(
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei")
                .text()
                .replaceAll(",", "")
        );
        var lblKoukaGoukei = parseInt(
            $(".PPRM203DCMonyKindInput.lblKoukaGoukei")
                .text()
                .replaceAll(",", "")
        );
        var lblKogiteGoukei = parseInt(
            $(".PPRM203DCMonyKindInput.lblKogiteGoukei")
                .text()
                .replaceAll(",", "")
        );

        //小計④を再計算 = 小計① + 小計② + 小計③
        var lblJissaiGoukei = me.EditKanma(
            lblShiheiGoukei + lblKoukaGoukei + lblKogiteGoukei
        );
        $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text(lblJissaiGoukei);

        //カーソル設定
        $(".PPRM203DCMonyKindInput.txtKINSYU").trigger("focus");
        //20171009 lqs INS S
        me.EnterKeyDown();
        //20171009 lqs INS E
    };

    //'***********************************************************************
    //'処 理 名：行削除
    //'関 数 名：me.btnRowDel_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：一番下の行を削除する
    //'***********************************************************************
    me.btnRowDel_Click = function () {
        var lngZandaka = 0;
        var lblKogiteGoukei = 0;

        var arr = $(".txtZANDAKA");
        for (i = 0; i < arr.length - 1; i++) {
            if (arr[i].value == "") {
                lngZandaka = 0;
            } else {
                lngZandaka = parseInt(me.removeComma(arr[i].value));
            }

            //小切手合計
            lblKogiteGoukei = lblKogiteGoukei + lngZandaka;
        }

        //小計③にセット
        $(".PPRM203DCMonyKindInput.lblKogiteGoukei").text(
            me.EditKanma(lblKogiteGoukei)
        );

        //小計④を再計算
        var lblShiheiGoukei = parseInt(
            me.removeComma($(".PPRM203DCMonyKindInput.lblShiheiGoukei").text())
        );
        var lblKoukaGoukei = parseInt(
            me.removeComma($(".PPRM203DCMonyKindInput.lblKoukaGoukei").text())
        );
        $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text(
            me.EditKanma(lblShiheiGoukei + lblKoukaGoukei + lblKogiteGoukei)
        );

        $(".PPRM203DCMonyKindInput.Kogite tbody")
            .children("tr:nth-last-of-type(1)")
            .remove();

        //カーソル設定
        $(".PPRM203DCMonyKindInput.txtKINSYU").trigger("focus");
    };

    //'***********************************************************************
    //'処 理 名：登録ボタン
    //'関 数 名：me.btnTouroku_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：金種データの登録・更新
    //'***********************************************************************
    me.btnTouroku_Click = function () {
        //2017/09/15 CI INS S
        var aa = true;
        $(".PPRM203DCMonyKindInput.txtKINSYU").each(function () {
            if (ODR_Jscript.KinsokuMojiCheck($(this)) == false) {
                aa = false;
                return false;
            }
        });
        if (aa == false) {
            return;
        }
        //2017/09/15 CI INS E

        //20170908 lqs INS S
        var realSum = me
            .removeComma($(".PPRM203DCMonyKindInput.lblJissaiGoukei").text())
            .toString();
        if (realSum.length > 13) {
            clsComFnc.FncMsgBox("W0010_PPRM", "実際の残高");
            return;
        }
        //20170908 lqs INS E
        //店舗コードチェック
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        if (txtTenpoCD == "") {
            $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger("focus");
            clsComFnc.FncMsgBox("E0001_PPRM", "店舗コード");
            return;
        }
        if (txtTenpoCD.length != 3) {
            $(".PPRM203DCMonyKindInput.txtTenpoCD").trigger("focus");
            clsComFnc.FncMsgBox("E0002_PPRM", "店舗コード");
            return;
        }

        //日締№チェック
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();
        if (txtHJMNo == "") {
            $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
            clsComFnc.FncMsgBox("E0001_PPRM", "日締№");
            return;
        }
        if (txtHJMNo.length != 12) {
            $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
            clsComFnc.FncMsgBox("E0002_PPRM", "日締№");
            return;
        }

        //日締日存在チェック
        me.FncUpdDate();
    };

    //'***********************************************************************
    //'処 理 名：日締№検索（関数）
    //'関 数 名：me.FncUpdDate
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：日締№の有無をチェック
    //'***********************************************************************
    me.FncUpdDate = function () {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();

        var url = me.sys_id + "/" + me.id + "/fncUpdDate";
        var data = {
            strTCD: txtTenpoCD,
            strHJMNo: txtHJMNo,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            var date = result["data"];

            //日締日存在チェック
            if (clsComFnc.FncNv(me.hidUpdDate) != clsComFnc.FncNv(date)) {
                $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
                clsComFnc.FncMsgBox(
                    "E0011_PPRM",
                    "他のユーザによって更新された可能性があります。最新情報を取得して下さい！"
                );
                return;
            }

            //小切手欄チェック
            arrKINSYU = $(".PPRM203DCMonyKindInput.txtKINSYU");
            arrZANDAKA = $(".PPRM203DCMonyKindInput.txtZANDAKA");

            for (i = 0; i < arrKINSYU.length; i++) {
                if (
                    (arrKINSYU[i].value == "" && arrZANDAKA[i].value != "") ||
                    (arrKINSYU[i].value != "" && arrZANDAKA[i].value == "")
                ) {
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "小切手№と金額は両方入力してください。"
                    );
                    return;
                }
            }

            //帳簿上の残高と実際の残高を比較する
            var lblTyouboGoukei = $(
                ".PPRM203DCMonyKindInput.lblTyouboGoukei"
            ).text();
            var lblJissaiGoukei = $(
                ".PPRM203DCMonyKindInput.lblJissaiGoukei"
            ).text();
            var txtRiyu = $(".PPRM203DCMonyKindInput.txtRiyu").val();

            if (lblTyouboGoukei != "0") {
                if (lblTyouboGoukei != lblJissaiGoukei) {
                    if (me.SessionBusyoCD != 122 && me.SessionBusyoCD != 125) {
                        $(".PPRM203DCMonyKindInput.txtMaisu_10000").trigger(
                            "focus"
                        );
                        clsComFnc.FncMsgBox(
                            "E0007_PPRM",
                            "帳簿上の残高",
                            "実際の残高"
                        );
                        return;
                    } else if (me.rtrim(txtRiyu) == "") {
                        $(".PPRM203DCMonyKindInput.txtRiyu").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "E0011_PPRM",
                            "帳簿上の残高と実際の残高が一致していません。不一致のまま登録する場合は不一致理由を入力して下さい！"
                        );
                        return;
                    }
                }
            }

            me.InsertAllData();
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：金種別残高/小切手/帳簿上の残高/実際の残高データの登録処理を実行
    //'関 数 名：me.InsertAllData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：金種別残高/小切手/帳簿上の残高/実際の残高データの登録処理を実行
    //'***********************************************************************
    me.InsertAllData = function () {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();

        //金種別残高用
        var txtMaisu_10000 = $(".PPRM203DCMonyKindInput.txtMaisu_10000").val();
        var lblKin_10000 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_10000").text()
        );
        var txtMaisu_5000 = $(".PPRM203DCMonyKindInput.txtMaisu_5000").val();
        var lblKin_5000 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_5000").text()
        );
        var txtMaisu_2000 = $(".PPRM203DCMonyKindInput.txtMaisu_2000").val();
        var lblKin_2000 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_2000").text()
        );
        var txtMaisu_1000 = $(".PPRM203DCMonyKindInput.txtMaisu_1000").val();
        var lblKin_1000 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_1000").text()
        );
        var txtMaisu_500 = $(".PPRM203DCMonyKindInput.txtMaisu_500").val();
        var lblKin_500 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_500").text()
        );
        var txtMaisu_100 = $(".PPRM203DCMonyKindInput.txtMaisu_100").val();
        var lblKin_100 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_100").text()
        );
        var txtMaisu_50 = $(".PPRM203DCMonyKindInput.txtMaisu_50").val();
        var lblKin_50 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_50").text()
        );
        var txtMaisu_10 = $(".PPRM203DCMonyKindInput.txtMaisu_10").val();
        var lblKin_10 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_10").text()
        );
        var txtMaisu_5 = $(".PPRM203DCMonyKindInput.txtMaisu_5").val();
        var lblKin_5 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_5").text()
        );
        var txtMaisu_1 = $(".PPRM203DCMonyKindInput.txtMaisu_1").val();
        var lblKin_1 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_1").text()
        );

        //小切手用
        var arrKINSYU = $(".PPRM203DCMonyKindInput.txtKINSYU");
        var arrZANDAKA = $(".PPRM203DCMonyKindInput.txtZANDAKA");
        var arrKinsyu = new Array();
        var arrZandaka = new Array();
        for (i = 0; i < arrKINSYU.length; i++) {
            if (arrKINSYU[i].value != "" && arrZANDAKA[i].value != "") {
                arrKinsyu.push(arrKINSYU[i].value);
                arrZandaka.push(me.removeComma(arrZANDAKA[i].value));
            }
        }

        //小計用
        var lblShiheiGoukei = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text()
        );
        var lblKoukaGoukei = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKoukaGoukei").text()
        );
        var lblKogiteGoukei = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKogiteGoukei").text()
        );
        var lblShiheiGoukei = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text()
        );
        var lblJissaiGoukei = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text()
        );
        var txtRiyu = me.rtrim($(".PPRM203DCMonyKindInput.txtRiyu").val());

        var url = me.sys_id + "/" + me.id + "/insertAllData";
        var data = {
            txtTenpoCD: txtTenpoCD,
            txtHJMNo: txtHJMNo,
            txtMaisu_10000: txtMaisu_10000,
            lblKin_10000: lblKin_10000,
            txtMaisu_5000: txtMaisu_5000,
            lblKin_5000: lblKin_5000,
            txtMaisu_2000: txtMaisu_2000,
            lblKin_2000: lblKin_2000,
            txtMaisu_1000: txtMaisu_1000,
            lblKin_1000: lblKin_1000,
            txtMaisu_500: txtMaisu_500,
            lblKin_500: lblKin_500,
            txtMaisu_100: txtMaisu_100,
            lblKin_100: lblKin_100,
            txtMaisu_50: txtMaisu_50,
            lblKin_50: lblKin_50,
            txtMaisu_10: txtMaisu_10,
            lblKin_10: lblKin_10,
            txtMaisu_5: txtMaisu_5,
            lblKin_5: lblKin_5,
            txtMaisu_1: txtMaisu_1,
            lblKin_1: lblKin_1,
            arrKinsyu: arrKinsyu,
            arrZandaka: arrZandaka,
            lblShiheiGoukei: lblShiheiGoukei,
            lblKoukaGoukei: lblKoukaGoukei,
            lblKogiteGoukei: lblKogiteGoukei,
            lblShiheiGoukei: lblShiheiGoukei,
            lblJissaiGoukei: lblJissaiGoukei,
            txtRiyu: txtRiyu,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                //完了メッセージ
                clsComFnc.FncMsgBox("I0002_PPRM");

                //登録後処理
                if (me.hidGamenFLG == "1") {
                    me.subFormInt(true);
                    me.subFormInt1();
                } else {
                    //画面を閉じる
                    $(".PPRM203DCMonyKindInput.body").dialog("close");
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：削除ボタン
    //'関 数 名：me.btnDelete_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：メッセージ表示。Yesの場合削除処理を実行
    //'***********************************************************************
    me.btnDelete_Click = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEvent_Click;
        clsComFnc.FncMsgBox("QY014_PPRM", "金種表データ");
    };

    //'***********************************************************************
    //'処 理 名：Yesの場合削除処理を実行
    //'関 数 名：me.cmdEvent_Click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：Yesの場合削除処理を実行
    //'***********************************************************************
    me.cmdEvent_Click = function () {
        var txtTenpoCD = $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        var txtHJMNo = $(".PPRM203DCMonyKindInput.txtHJMNo").val();

        var url = me.sys_id + "/" + me.id + "/cmdEventClick";
        var data = {
            txtTenpoCD: txtTenpoCD,
            txtHJMNo: txtHJMNo,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                //完了メッセージ
                clsComFnc.FncMsgBox("I0003_PPRM");

                //画面を閉じる
                if (me.hidGamenFLG == "1") {
                    //20170907 lqs UPD S
                    //$(".PPRM203DCMonyKindInput.body").html("");
                    me.subFormInt(true);
                    me.subFormInt1();
                    //20170907 lqs UPD E
                    // 20170912 lqs INS S
                    me.hidUpdDate = "";
                    // 20170912 lqs INS E
                } else {
                    $(".PPRM203DCMonyKindInput.body").dialog("close");
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：閉じる
    //'関 数 名：me.windowClose
    //'引 数  ：なし
    //'戻 り 値：なし
    //'処理説明：閉じる
    //'***********************************************************************
    me.windowClose = function () {
        me.no = "";
        $(".PPRM203DCMonyKindInput.body").dialog("close");
    };

    //'***********************************************************************
    //'処 理 名：コールバック初期処理
    //'関 数 名：me.setCallBackInit
    //'引 数   ：str
    //'戻 り 値：なし
    //'処理説明：ClientCallBackの初期処理を行う
    //'***********************************************************************
    me.setCallBackInit = function (str) {
        var eventArgument = "";

        if (str == "txtTenpoCD") {
            eventArgument = "0" + decodeURI("%0D%0A");
            eventArgument +=
                "0" +
                $(".PPRM203DCMonyKindInput.txtHJMDate").val() +
                decodeURI("%0D%0A");
            eventArgument +=
                "0" + $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        } else if (str == "txtHJMNo") {
            eventArgument = "1" + decodeURI("%0D%0A");
            eventArgument += $(".PPRM203DCMonyKindInput.txtHJMNo").val();
        } else {
            eventArgument = "2" + decodeURI("%0D%0A");
            eventArgument +=
                "0" +
                $(".PPRM203DCMonyKindInput.txtHJMDate").val() +
                decodeURI("%0D%0A");
            eventArgument +=
                "0" + $(".PPRM203DCMonyKindInput.txtTenpoCD").val();
        }

        var url = me.sys_id + "/" + me.id + "/raiseCallbackEvent";

        var data = {
            eventArgument: eventArgument,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                data = result["data"];
                me.strErr = me.ReceiveData(data);
                if (me.strErr != "W9999") {
                    if (me.btnType) {
                        me.btnDisp_Click();
                        me.btnType = false;
                    }
                } else {
                    me.FncMsgType = true;
                }
            } else {
                me.FncMsgType = true;
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：レシーブデータ処理（コールバック用）
    //'関 数 名：me.ReceiveData
    //'引 数   ：result
    //'戻 り 値：なし
    //'処理説明：レシーブデータ処理（コールバック用）
    //'***********************************************************************
    me.ReceiveData = function (data) {
        data = data.split(decodeURI("%0D%0A"));

        switch (data[0]) {
            case "0":
                //店舗コード

                //店舗名セット
                if (data[1] == "") {
                    $(".PPRM203DCMonyKindInput.lblTenpo").val("");
                } else {
                    $(".PPRM203DCMonyKindInput.lblTenpo").val(data[1]);
                }

                //日締№セット
                $(".PPRM203DCMonyKindInput.txtHJMNo").val(data[2]);
                $(".PPRM203DCMonyKindInput.txtHJMNo").trigger("focus");
                break;

            case "1":
                //日締№

                //店舗コードセット
                $(".PPRM203DCMonyKindInput.txtTenpoCD").val(data[2]);

                //店舗名セット
                if (data[1] == "") {
                    $(".PPRM203DCMonyKindInput.lblTenpo").val("");
                } else {
                    $(".PPRM203DCMonyKindInput.lblTenpo").val(data[1]);
                }

                //日締日セット
                $(".PPRM203DCMonyKindInput.txtHJMDate").val(data[3]);

                if (data[4] == "0") {
                    $(".PPRM203DCMonyKindInput.txtHJMDate").trigger("focus");
                    //20170908 lqs UPD S
                    //alert("入力された日締№は不正です");
                    clsComFnc.FncMsgBox("W9999", "入力された日締№は不正です。");
                    //20170908 lqs UPD E
                    return "W9999";
                }
                break;

            case "2":
                //日締日

                //日締№セット
                $(".PPRM203DCMonyKindInput.txtHJMNo").val("");
                $(".PPRM203DCMonyKindInput.txtHJMNo").val(data[2]);
                break;
            default:
                break;
        }
    };

    //'***********************************************************************
    //'処 理 名：フォーカスが離れた時に計算
    //'関 数 名：me.lostFocus
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：フォーカスが離れた時に計算
    //'***********************************************************************
    me.lostFocus = function () {
        //金種（10000）
        var txtMaisu_10000 = $(".PPRM203DCMonyKindInput.txtMaisu_10000").val();
        if (txtMaisu_10000 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_10000").text(
                me.addFigure(Number(txtMaisu_10000) * 10000)
            );
        }

        //金種（5000）
        var txtMaisu_5000 = $(".PPRM203DCMonyKindInput.txtMaisu_5000").val();
        if (txtMaisu_5000 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_5000").text(
                me.addFigure(Number(txtMaisu_5000) * 5000)
            );
        }

        //金種（2000）
        var txtMaisu_2000 = $(".PPRM203DCMonyKindInput.txtMaisu_2000").val();
        if (txtMaisu_2000 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_2000").text(
                me.addFigure(Number(txtMaisu_2000) * 2000)
            );
        }

        //金種（1000）
        var txtMaisu_1000 = $(".PPRM203DCMonyKindInput.txtMaisu_1000").val();
        if (txtMaisu_1000 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_1000").text(
                me.addFigure(Number(txtMaisu_1000) * 1000)
            );
        }

        //金種（500）
        var txtMaisu_500 = $(".PPRM203DCMonyKindInput.txtMaisu_500").val();
        if (txtMaisu_500 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_500").text(
                me.addFigure(Number(txtMaisu_500) * 500)
            );
        }

        //金種（100）
        var txtMaisu_100 = $(".PPRM203DCMonyKindInput.txtMaisu_100").val();
        if (txtMaisu_100 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_100").text(
                me.addFigure(Number(txtMaisu_100) * 100)
            );
        }

        //金種（50）
        var txtMaisu_50 = $(".PPRM203DCMonyKindInput.txtMaisu_50").val();
        if (txtMaisu_50 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_50").text(
                me.addFigure(Number(txtMaisu_50) * 50)
            );
        }

        //金種（10）
        var txtMaisu_10 = $(".PPRM203DCMonyKindInput.txtMaisu_10").val();
        if (txtMaisu_10 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_10").text(
                me.addFigure(Number(txtMaisu_10) * 10)
            );
        }

        //金種（5）
        var txtMaisu_5 = $(".PPRM203DCMonyKindInput.txtMaisu_5").val();
        if (txtMaisu_5 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_5").text(
                me.addFigure(Number(txtMaisu_5) * 5)
            );
        }

        //金種（1）
        var txtMaisu_1 = $(".PPRM203DCMonyKindInput.txtMaisu_1").val();
        if (txtMaisu_1 != null) {
            $(".PPRM203DCMonyKindInput.lblKin_1").text(
                me.addFigure(Number(txtMaisu_1) * 1)
            );
        }

        //合計（紙幣）
        var str1 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_10000").text()
        );
        var str2 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_5000").text()
        );
        var str3 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_2000").text()
        );
        var str4 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_1000").text()
        );

        $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text(
            me.addFigure(
                Number(str1) + Number(str2) + Number(str3) + Number(str4)
            )
        );

        //合計（硬貨）
        var str1 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_500").text()
        );
        var str2 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_100").text()
        );
        var str3 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_50").text()
        );
        var str4 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKin_10").text()
        );
        var str5 = me.removeComma($(".PPRM203DCMonyKindInput.lblKin_5").text());
        var str6 = me.removeComma($(".PPRM203DCMonyKindInput.lblKin_1").text());

        $(".PPRM203DCMonyKindInput.lblKoukaGoukei").text(
            me.addFigure(
                Number(str1) +
                    Number(str2) +
                    Number(str3) +
                    Number(str4) +
                    Number(str5) +
                    Number(str6)
            )
        );

        //合計（実際の残高）
        var str1 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei").text()
        );
        var str2 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKoukaGoukei").text()
        );
        var str3 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKogiteGoukei").text()
        );

        $(".PPRM203DCMonyKindInput.lblJissaiGoukei").text(
            me.addFigure(Number(str1) + Number(str2) + Number(str3))
        );
    };

    //'***********************************************************************
    //'処 理 名：入力チェック（数値のみ入力可能）
    //'関 数 名：me.CheckNum
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：入力チェック（数値のみ入力可能）
    //'***********************************************************************
    me.CheckNum = function (event) {
        if (event.keyCode < 48 || event.keyCode > 57) {
            event.preventDefault();
            return false;
        }
    };

    //'***********************************************************************
    //'処 理 名：グリッドビュー自動計算
    //'関 数 名：me.gridZandakaSum
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：グリッドビュー自動計算
    //'***********************************************************************
    me.gridZandakaSum = function () {
        //計算
        var sum = 0;
        var str;
        var str2;
        var arrTD = $("input");

        for (i = 0; i < arrTD.length - 1; i++) {
            str = arrTD[i].className;

            //ID検索
            rObj = new RegExp("txtZANDAKA");

            if (str.match(rObj)) {
                str2 = me.removeComma(arrTD[i].value);

                if (str2 != "" && !isNaN(Number(str2))) {
                    //カンマ編集
                    arrTD[i].value = me.addFigure(Number(str2));
                    //合計計算
                    sum += parseInt(Number(str2), 10);
                }
            }
        }

        $(".PPRM203DCMonyKindInput.lblKogiteGoukei")[0].innerText =
            me.addFigure(sum);

        //合計（実際の残高）
        var str1 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblShiheiGoukei")[0].innerText
        );
        var str2 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKoukaGoukei")[0].innerText
        );
        var str3 = me.removeComma(
            $(".PPRM203DCMonyKindInput.lblKogiteGoukei")[0].innerText
        );
        var num = $(".PPRM203DCMonyKindInput.lblJissaiGoukei")[0];
        num.innerText = me.addFigure(
            Number(str1) + Number(str2) + Number(str3)
        );
    };

    //'***********************************************************************
    //'処 理 名：カンマ編集
    //'関 数 名：me.EditKanma
    //'引 数   ：lngValue
    //'戻 り 値：編集値
    //'処理説明：カンマ編集
    //'***********************************************************************
    me.EditKanma = function (lngValue) {
        return String(lngValue).numFormat();
    };

    //'***********************************************************************
    //'処 理 名：この関数は空白文字の文字列の右側を削除します
    //'関 数 名：me.rtrim
    //'引 数   ：str
    //'戻 り 値：編集値
    //'処理説明：この関数は空白文字の文字列の右側を削除します
    //'***********************************************************************
    me.rtrim = function (str) {
        return str.replace(/(\s*$)/g, "");
    };

    //'***********************************************************************
    //'処 理 名：3桁カンマ区切り
    //'関 数 名：me.addFigure
    //'引 数   ：str
    //'戻 り 値：編集値
    //'処理説明：3桁カンマ区切り
    //'***********************************************************************
    me.addFigure = function (str) {
        var num = new String(str).replace(/,/g, "");
        while (num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
        return num;
    };

    //'***********************************************************************
    //'処 理 名：カンマ削除
    //'関 数 名：me.removeComma
    //'引 数   ：value
    //'戻 り 値：編集値
    //'処理説明：カンマ削除
    //'***********************************************************************
    me.removeComma = function (value) {
        return value.split(",").join("");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM203DCMonyKindInput = new PPRM.PPRM203DCMonyKindInput();
    o_PPRM_PPRM203DCMonyKindInput.load();
    o_PPRM_PPRM.PPRM203DCMonyKindInput = o_PPRM_PPRM203DCMonyKindInput;
});
