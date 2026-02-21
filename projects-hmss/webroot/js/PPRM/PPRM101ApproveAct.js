/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20171208            #2807                       遮挡问题                           lqs
 * 20180410            #2843                       他のユーザによって更新された可能性があります。最新情報を取得して下さい！                            li
 * 20201120            bug                         ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM101ApproveAct");

PPRM.PPRM101ApproveAct = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";

    // ========== 変数 start ==========

    me.ajax = new gdmz.common.ajax();
    me.id = "PPRM101ApproveAct";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.hidFLG = "";
    me.hidTCD = "";
    me.hidHJMNo = "";
    me.hidHJMDate = "";
    me.hidBNM = "";
    me.hidTNM = "";
    me.hidOpenDate = "";
    me.hidUpdDate = "";
    me.title = "承認画面";
    me.strProgramID = "ApproveAct";
    //20180301 lqs INS S
    me.keiri = false;
    me.tencho = false;
    me.kacho = false;
    me.tantou = false;
    //20180301 lqs INS E

    // ========== 変数 end ==========

    me.before_close = function () {};

    //dialog
    $(".PPRM101ApproveAct.body").dialog({
        autoOpen: false,
        width: 1120,
        height: me.ratio === 1.5 ? 540 : 660,
        modal: true,
        title: me.title,
        open: function () {},
        close: function () {
            me.before_close();
            $(".PPRM101ApproveAct.body").remove();
        },
    });
    $(".PPRM101ApproveAct.body").dialog("open");

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.hidFLG = requestdata["TAISYO"];
        me.hidHJMNo = requestdata["HNO"];
        me.hidTCD = requestdata["TCD"];
        me.hidHJMDate = requestdata["HDATE"];
    }

    localStorage.removeItem("requestdata");

    // ========== コントロール start ==========
    me.controls.push({
        id: ".PPRM101ApproveAct.btnKeiri",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM101ApproveAct.btnTencho",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM101ApproveAct.btnKacho",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM101ApproveAct.btnTantou",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM101ApproveAct.btnKanren",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM101ApproveAct.btnClose",
        type: "button",
        handle: "",
    });

    // ========== コントロール end ==========

    // ========== イベント start ==========

    //[経理担当承認]のclick
    $(".PPRM101ApproveAct.btnKeiri").click(function () {
        //20180301 lqs INS S
        me.keiri = true;
        //20180301 lqs INS E
        me.btnKeiri_Click();
    });
    //[店長承認]のclick
    $(".PPRM101ApproveAct.btnTencho").click(function () {
        //20180301 lqs INS S
        me.tencho = true;
        //20180301 lqs INS E
        me.btnTencho_Click();
    });
    //[課長承認]のclick
    $(".PPRM101ApproveAct.btnKacho").click(function () {
        //20180301 lqs INS S
        me.kacho = true;
        //20180301 lqs INS E
        me.btnKacho_Click();
    });
    //[担当承認]のclick
    $(".PPRM101ApproveAct.btnTantou").click(function () {
        //20180301 lqs INS S
        me.tantou = true;
        //20180301 lqs INS E
        me.btnTantou_Click();
    });
    //[閉じる]のclick
    $(".PPRM101ApproveAct.btnClose").click(function () {
        me.windowClose();
    });

    // ========== イベント end ==========

    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();

        me.getUpdDate();
    };
    // '**********************************************************************
    // '処 理 名：画面初期化
    // '関 数 名：subFormInt
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：画面初期化
    // '**********************************************************************
    me.subFormInt = function () {
        me.getTenpo();
    };

    //'**********************************************************************
    //'処 理 名：更新日付取得
    //'関 数 名：getUpdDate
    //'引    数：なし
    //'戻 り 値：更新日付
    //'処理説明：店舗日締承認データの更新日付を取得する
    //'**********************************************************************
    me.getUpdDate = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    me.hidUpdDate = result["data"][0]["UPD_DATE"];
                } else {
                    me.hidUpdDate = "";
                }

                me.chkKeiri();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：経理承認済みチェック
    // '関 数 名：chkKeiri
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：経理承認済みかチェックする
    // '**********************************************************************
    me.chkKeiri = function () {
        var chkKeiri = false;
        var url = me.sys_id + "/" + me.id + "/" + "fncchkKeiri";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    if (result["data"][0]["KEIRI_SNN_FLG"] == "1") {
                        chkKeiri = true;
                    }
                }
                if (
                    gdmz.SessionBusyoCD != "122" &&
                    gdmz.SessionBusyoCD != "125" &&
                    chkKeiri
                ) {
                    var getUpdDate = "";
                    if (result["data1"].length > 0) {
                        getUpdDate = result["data1"][0]["UPD_DATE"];
                    }
                    //20180410 YIN UPD S
                    // if (clsComFnc.FncNv(me.hidUpdDate) != getUpdDate)
                    if (
                        clsComFnc.FncNv(me.hidUpdDate) !=
                        clsComFnc.FncNv(getUpdDate)
                    ) {
                        //20180410 YIN UPD E
                        me.flg = 2;
                        $(".PPRM101ApproveAct.body").dialog("close");
                        clsComFnc.FncMsgBox(
                            "E0011_PPRM",
                            "経理承認済みですので、承認画面を開くことはできません。"
                        );
                        return;
                    }
                }
                me.subFormInt();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：イメージファイルの有無確認
    // '関 数 名：jpgKakunin
    // '戻 り 値：なし
    // '処理説明：イメージファイルの有無確認
    // '**********************************************************************
    me.jpgKakunin = function () {
        var jpgKakunin = false;
        var url = me.sys_id + "/" + me.id + "/" + "fncjpgKakunin";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    jpgKakunin = true;
                }
                if (jpgKakunin == true) {
                    $(".PPRM101ApproveAct.btnKanren").button("enable");
                } else {
                    $(".PPRM101ApproveAct.btnKanren").button("disable");
                }

                if (me.hidFLG == "1") {
                    $(".PPRM101ApproveAct.btnKanren").click(function () {
                        me.ImgOpenFile(me.hidTCD, me.hidHJMNo);
                    });
                }
                //[閉じる]のclick
                $(".PPRM101ApproveAct.btnClose").click(function () {
                    me.windowClose();
                });
                //登録処理
                me.DataInsert();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：店舗名取得
    // '関 数 名：getTenpo
    // '処理説明：店舗名を取得する
    // '**********************************************************************
    me.getTenpo = function () {
        var getTenpo = "";
        var url = me.sys_id + "/" + me.id + "/" + "fncgetTenpo";
        var arr = {
            TCD: me.hidTCD,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    getTenpo = result["data"][0]["BUSYO_NM"];
                }
                //対象
                if (me.hidFLG == "1") {
                    //'ラベル
                    $(".PPRM101ApproveAct.lblTenpo").val(getTenpo);
                    $(".PPRM101ApproveAct.lblHJMDate").val(me.hidHJMDate);
                    $(".PPRM101ApproveAct.lblHJMNo").val(me.hidHJMNo);

                    //表示
                    $(".PPRM101ApproveAct.tdlblTitle3").show();
                    $(".PPRM101ApproveAct.tdlblHJMNo").show();
                    //20171208 lqs DEL S
                    // $(".PPRM101ApproveAct.btnKanren").show();
                    //20171208 lqs DEL E
                } else {
                    //'ラベル
                    $(".PPRM101ApproveAct.lblTenpo").val(getTenpo);
                    $(".PPRM101ApproveAct.lblTitle2").text("売上日");
                    $(".PPRM101ApproveAct.lblHJMDate").val(me.hidHJMDate);

                    //表示
                    $(".PPRM101ApproveAct.tdlblTitle3").hide();
                    $(".PPRM101ApproveAct.tdlblHJMNo").hide();
                    //20171208 lqs DEL S
                    //$(".PPRM101ApproveAct.btnKanren").hide();
                    //20171208 lqs DEL E
                }
                $(".PPRM101ApproveAct.btnTencho").button("enable");
                $(".PPRM101ApproveAct.btnKacho").button("enable");
                $(".PPRM101ApproveAct.btnTantou").button("enable");

                me.jpgKakunin();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：データ登録
    // '関 数 名：DataInsert
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：データを登録する
    // '**********************************************************************
    me.DataInsert = function () {
        var SerchData = false;
        var url = me.sys_id + "/" + me.id + "/" + "fncSerchData";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
            strProgramID: me.strProgramID,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    var strKeiriFLG = result["data"][0]["KEIRI_SNN_FLG"];
                    var strTenchoFLG = result["data"][0]["TENCHO_SNN_FLG"];
                    var strKachoFLG = result["data"][0]["KACHO_SNN_FLG"];
                    var strTantouFLG = result["data"][0]["TAN_SNN_FLG"];

                    //ボタン設定
                    me.BtnSettei(1, strKeiriFLG);
                    me.BtnSettei(2, strTenchoFLG);
                    me.BtnSettei(3, strKachoFLG);
                    me.BtnSettei(4, strTantouFLG);

                    //'存在する
                    SerchData = true;

                    me.hidBNM = result["data"][0]["BUSYO_RYKNM"];
                } else {
                    //ボタン設定
                    me.BtnSettei(1, 0);
                    me.BtnSettei(2, 0);
                    me.BtnSettei(3, 0);
                    me.BtnSettei(4, 0);

                    SerchData = false;

                    me.hidBNM = "";
                }

                if (SerchData == false) {
                    url = me.sys_id + "/" + me.id + "/" + "fncDataInsert";
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] == true) {
                            if (result["data"].length > 0) {
                                me.hidBNM = result["data"][0]["BUSYO_RYKNM"];
                            } else {
                                me.hidBNM = "";
                            }
                            //20180411 YIN INS S
                            me.hidUpdDate = result["data2"][0]["UPD_DATE"];
                            //20180411 YIN INS E

                            me.hidTNM = gdmz.SessionSyainNM.substring(
                                0,
                                gdmz.SessionSyainNM.indexOf("　") + 1
                            );

                            me.SubSetEnabled_OnPageLoad(me.hidTCD);
                        } else {
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                    };
                    me.ajax.send(url, data, 0);
                } else {
                    url = me.sys_id + "/" + me.id + "/" + "fncgetBusyoRNM";
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (result["result"] == true) {
                            if (result["data"].length > 0) {
                                me.hidBNM = result["data"][0]["BUSYO_RYKNM"];
                            } else {
                                me.hidBNM = "";
                            }

                            me.hidTNM = gdmz.SessionSyainNM.substring(
                                0,
                                gdmz.SessionSyainNM.indexOf("　") + 1
                            );

                            me.SubSetEnabled_OnPageLoad(me.hidTCD);
                        } else {
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                    };
                    me.ajax.send(url, data, 0);
                }

                if (
                    gdmz.SessionBusyoCD != "122" &&
                    gdmz.SessionBusyoCD != "125"
                ) {
                    $(".PPRM101ApproveAct.btnKeiri").button("disable");
                } else {
                    $(".PPRM101ApproveAct.btnKeiri").button("enable");
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：権限設定（初期値）
    //'関 数 名：me.SubSetEnabled_OnPageLoad
    //'引 数   ：txtTenpoCD(店舗コード)
    //'戻 り 値：なし
    //'処理説明：権限設定（初期値）
    //'***********************************************************************
    me.SubSetEnabled_OnPageLoad = function (txtTenpoCD) {
        var url = me.sys_id + "/" + me.id + "/subSetEnabledOnPageLoad";
        var data = {
            txtTenpoCD: txtTenpoCD,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            for (key in result["data"]) {
                //20201120 WL UPD S
                //$(".PPRM101ApproveAct." + key).prop("disabled", result['data'][key]);
                $(".PPRM101ApproveAct." + key).button(
                    result["data"][key] == true ? "disable" : "enable"
                );
                //20201120 WL UPD E
            }
            me.pdfView(0, "");
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：ボタン設定
    // '関 数 名：BtnSettei
    // '引 数   ：種類（1:経理担当,2:店長,3:課長,4:担当）、状態（0:未承認、1:承認）
    // '戻 り 値：なし
    // '処理説明：担当欄に承認印を押す＆外す
    // '**********************************************************************
    me.BtnSettei = function (strSyurui, strFLG) {
        switch (strSyurui) {
            case 1:
                if (strFLG == "0") {
                    $(".PPRM101ApproveAct.btnKeiri").text("経理担当承認");
                    $(".PPRM101ApproveAct.btnKeiri").css("color", "black");
                } else {
                    $(".PPRM101ApproveAct.btnKeiri").text("経理担当承認ｷｬﾝｾﾙ");
                    $(".PPRM101ApproveAct.btnKeiri").css("color", "red");
                }
                break;
            case 2:
                if (strFLG == "0") {
                    $(".PPRM101ApproveAct.btnTencho").text("店長承認");
                    $(".PPRM101ApproveAct.btnTencho").css("color", "black");
                } else {
                    $(".PPRM101ApproveAct.btnTencho").text("店長承認ｷｬﾝｾﾙ");
                    $(".PPRM101ApproveAct.btnTencho").css("color", "red");
                }
                break;
            case 3:
                if (strFLG == "0") {
                    $(".PPRM101ApproveAct.btnKacho").text("課長承認");
                    $(".PPRM101ApproveAct.btnKacho").css("color", "black");
                } else {
                    $(".PPRM101ApproveAct.btnKacho").text("課長承認ｷｬﾝｾﾙ");
                    $(".PPRM101ApproveAct.btnKacho").css("color", "red");
                }
                break;
            case 4:
                if (strFLG == "0") {
                    $(".PPRM101ApproveAct.btnTantou").text("担当承認");
                    $(".PPRM101ApproveAct.btnTantou").css("color", "black");
                } else {
                    $(".PPRM101ApproveAct.btnTantou").text("担当承認ｷｬﾝｾﾙ");
                    $(".PPRM101ApproveAct.btnTantou").css("color", "red");
                }
                break;
        }
    };
    // '**********************************************************************
    // '処 理 名：経理担当承認ボタンクリック
    // '関 数 名：btnKeiri_Click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：経理担当欄に承認印を押す＆外す
    // '**********************************************************************
    me.btnKeiri_Click = function () {
        var getUpdDate = "";
        var url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    getUpdDate = result["data"][0]["UPD_DATE"];
                } else {
                    getUpdDate = "";
                }
                //---20180410 li UPD S.
                // if (clsComFnc.FncNv(me.hidUpdDate) != getUpdDate)
                if (
                    clsComFnc.FncNv(me.hidUpdDate) !=
                    clsComFnc.FncNv(getUpdDate)
                ) {
                    //---20180410 li UPD E.
                    clsComFnc.FncMsgBox(
                        "E0011_PPRM",
                        "他のユーザによって更新された可能性があります。最新情報を取得して下さい！"
                    );
                    return;
                }
                if ($(".PPRM101ApproveAct.btnKeiri").text() == "経理担当承認") {
                    me.UpdateSyounin(1, 1);
                } else {
                    me.UpdateSyounin(1, 0);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：店長承認ボタンクリック
    // '関 数 名：btnTencho_Click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：店長欄に承認印を押す＆外す
    // '**********************************************************************
    me.btnTencho_Click = function () {
        var getUpdDate = "";
        var url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    getUpdDate = result["data"][0]["UPD_DATE"];
                } else {
                    getUpdDate = "";
                }
                //---20180410 li UPD S.
                // if (clsComFnc.FncNv(me.hidUpdDate) != getUpdDate)
                if (
                    clsComFnc.FncNv(me.hidUpdDate) !=
                    clsComFnc.FncNv(getUpdDate)
                ) {
                    //---20180410 li UPD E.
                    clsComFnc.FncMsgBox(
                        "E0011_PPRM",
                        "他のユーザによって更新された可能性があります。最新情報を取得して下さい！"
                    );
                    return;
                }
                if ($(".PPRM101ApproveAct.btnTencho").text() == "店長承認") {
                    me.UpdateSyounin(2, 1);
                } else {
                    me.UpdateSyounin(2, 0);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：課長承認ボタンクリック
    // '関 数 名：btnKacho_Click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：課長欄に承認印を押す＆外す
    // '**********************************************************************
    me.btnKacho_Click = function () {
        var getUpdDate = "";
        var url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    getUpdDate = result["data"][0]["UPD_DATE"];
                } else {
                    getUpdDate = "";
                }
                //---20180410 li UPD S.
                // if (clsComFnc.FncNv(me.hidUpdDate) != getUpdDate)
                if (
                    clsComFnc.FncNv(me.hidUpdDate) !=
                    clsComFnc.FncNv(getUpdDate)
                ) {
                    //---20180410 li UPD E.
                    clsComFnc.FncMsgBox(
                        "E0011_PPRM",
                        "他のユーザによって更新された可能性があります。最新情報を取得して下さい！"
                    );
                    return;
                }
                if ($(".PPRM101ApproveAct.btnKacho").text() == "課長承認") {
                    me.UpdateSyounin(3, 1);
                } else {
                    me.UpdateSyounin(3, 0);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：担当承認ボタンクリック
    // '関 数 名：btnTantou_Click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：担当欄に承認印を押す＆外す
    // '**********************************************************************
    me.btnTantou_Click = function () {
        var getUpdDate = "";
        var url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length > 0) {
                    getUpdDate = result["data"][0]["UPD_DATE"];
                } else {
                    getUpdDate = "";
                }
                //---20180410 li UPD S.
                // if (clsComFnc.FncNv(me.hidUpdDate) != getUpdDate)
                if (
                    clsComFnc.FncNv(me.hidUpdDate) !=
                    clsComFnc.FncNv(getUpdDate)
                ) {
                    //---20180410 li UPD E.
                    clsComFnc.FncMsgBox(
                        "E0011_PPRM",
                        "他のユーザによって更新された可能性があります。最新情報を取得して下さい！"
                    );
                    return;
                }
                if ($(".PPRM101ApproveAct.btnTantou").text() == "担当承認") {
                    me.UpdateSyounin(4, 1);
                } else {
                    me.UpdateSyounin(4, 0);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：帳票をPDFで表示する
    // '関 数 名：pdfView
    // '引 数   ：strFLG1,strFLG2
    // '戻 り 値：なし
    // '処理説明：帳票をPDFで表示する
    // '**********************************************************************
    me.pdfView = function (strFLG1, strFLG2) {
        var lngCount = 0;
        me.hidOpenDate = new Date().Format("yyyyMMddHHmmss");

        intState = 9;

        if (me.hidFLG == "1") {
            //日締出力帳票
            me.subPrintPDFJimu(strFLG1, strFLG2, "1", lngCount);
        } else {
            //整備日報
            me.subPrintPDFSeibi(strFLG1, strFLG2, "2", lngCount);
        }
        intState = 1;
    };
    // '**********************************************************************
    // '処 理 名：整備日報のPDF生成
    // '関 数 名：subPrintPDFSeibi
    // '引 数   ：strFLG1,strFLG2, strKIND, lngCount
    // '戻 り 値：なし
    // '処理説明：日締帳票のPDF生成
    // '**********************************************************************
    me.subPrintPDFSeibi = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncsubPrintPDFSeibi";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    var href = result["reports"];
                    $(".PPRM101ApproveAct.temp").prop("src", href);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：日締帳票のPDF生成
    // '関 数 名：subPrintPDFJimu
    // '引 数   ：strFLG1,strFLG2, strKIND, lngCount
    // '戻 り 値：なし
    // '処理説明：日締帳票のPDF生成
    // '**********************************************************************
    me.subPrintPDFJimu = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncsubPrintPDFJimu";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            hidOpenDate: me.hidOpenDate,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    var href = result["reports"];
                    $(".PPRM101ApproveAct.temp").prop("src", href);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    // '**********************************************************************
    // '処 理 名：店舗日締承認データを更新する
    // '関 数 名：UpdateSyounin
    // '引 数   ：種類（1:経理担当,2:店長,3:課長,4:担当）、状態（0:未承認、1:承認）
    // '戻 り 値：なし
    // '処理説明：承認する（店舗日締承認データの各承認フラグを1にする）
    // '        ：未承認にする（店舗日締承認データの各承認フラグを0にする）
    // '**********************************************************************
    me.UpdateSyounin = function (strFLG1, strFLG2) {
        var url = me.sys_id + "/" + me.id + "/" + "fncUpdateSyounin";
        var arr = {
            TCD: me.hidTCD,
            HJMNo: me.hidHJMNo,
            HJMDT: me.hidHJMDate,
            FLG: me.hidFLG,
            strProgramID: me.strProgramID,
            strSyurui: strFLG1,
            strFLG: strFLG2,
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.BtnSettei(strFLG1, strFLG2);

                url = me.sys_id + "/" + me.id + "/" + "fncgetUpdDate";
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        if (result["data"].length > 0) {
                            me.hidUpdDate = result["data"][0]["UPD_DATE"];
                        } else {
                            me.hidUpdDate = "";
                        }

                        if (me.hidFLG == "1") {
                            me.pdfView(strFLG1, strFLG2);
                        }
                    } else {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                };
                me.ajax.send(url, data, 0);
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：イメージファイルを行う
    //'関 数 名：me.ImgOpenFile
    //'引 数 １：no1
    //'引 数 ２：no2
    //'戻 り 値：なし
    //'処理説明：イメージファイル開く
    //'**********************************************************************
    me.ImgOpenFile = function (no1, no2) {
        var url = me.sys_id + "/" + "PPRMjpgView";

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: 1,
                TCD: no1,
                HNO: no2,
            })
        );

        me.data = {};
        me.ajax.receive = function (result) {
            $(".PPRM101ApproveAct_dialog").html(result);
        };
        me.ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：戻るを行う
    //'関 数 名：me.windowClose
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面閉じる
    //'**********************************************************************
    me.windowClose = function () {
        me.flg = 2;
        $(".PPRM101ApproveAct.body").dialog("close");
    };
    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM101ApproveAct = new PPRM.PPRM101ApproveAct();
    o_PPRM_PPRM101ApproveAct.load();
    o_PPRM_PPRM.PPRM101ApproveAct = o_PPRM_PPRM101ApproveAct;
});
