/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM204DCOutput");

PPRM.PPRM204DCOutput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ODR_Jscript = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    me.ajax = new gdmz.common.ajax();
    me.id = "PPRM204DCOutput";
    me.sys_id = "PPRM";
    me.url = "";
    me.title = "日締出力";
    me.data = new Array();

    me.strMODE = "";
    me.strTAISYO = "";
    me.strHNO = "";
    me.strTCD = "";
    me.strURI = "";
    me.hidTenpoCD = "";
    me.strProgramID = "DC_Output";
    me.hidReturnId = "";
    me.hidOpenDate = "";
    me.hidJTenpoNM = "";
    me.hidJHjmDT = "";
    me.hidSTenpoNM = "";

    // ========== 変数 end ==========

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.strMODE = requestdata["MODE"];
        me.strTAISYO = requestdata["TAISYO"];
        me.strHNO = requestdata["HNO"];
        me.strTCD = requestdata["TCD"];
        me.strURI = requestdata["URI"];
        me.hidTenpoCD = me.strTCD;
    }

    localStorage.removeItem("requestdata");

    me.before_close = function () {};

    if (me.strMODE != "" && me.strMODE != undefined && me.strMODE != null) {
        $(".PPRM204DCOutput.body").dialog({
            autoOpen: false,
            width: 1120,
            height: me.ratio === 1.5 ? 540 : 660,
            modal: true,
            title: me.title,
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM204DCOutput.body").remove();
            },
        });
        $(".PPRM204DCOutput.body").dialog("open");
    }

    // ========== コントロール start ==========
    me.controls.push({
        id: ".PPRM204DCOutput.btnHjmSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM204DCOutput.btnPdf",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM204DCOutput.btnClose",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM204DCOutput.btnTenpoSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".PPRM204DCOutput.txtSUriageDate",
        type: "datepicker",
        handle: "",
    });
    // ========== コントロール end ==========

    // ========== イベント start ==========

    //[プレビュー]のclick
    $(".PPRM204DCOutput.btnPdf").click(function () {
        me.btnPdf_Click();
    });
    //[検索(日締№)]のclick
    $(".PPRM204DCOutput.btnHjmSearch").click(function () {
        me.openHJMSearch();
    });
    //[検索(店舗コード)]のclick
    $(".PPRM204DCOutput.btnTenpoSearch").click(function () {
        me.openTenpoSearch();
    });
    //[閉じる]のclick
    $(".PPRM204DCOutput.btnClose").click(function () {
        me.windowClose();
    });
    //[整備]のchange
    $(".PPRM204DCOutput.radSeibi").change(function () {
        me.radSeibi_CheckedChanged();
    });
    //[事務]のchange
    $(".PPRM204DCOutput.radJimu").change(function () {
        me.radJimu_CheckedChanged();
    });
    //[店舗コード]のchange
    $(".PPRM204DCOutput.txtSTenpoCD").change(function () {
        me.txtSTenpoCD_TextChanged();
    });
    //[売上日]のchange
    $(".PPRM204DCOutput.txtSUriageDate").change(function () {
        me.txtSUriageDate_TextChanged();
    });
    //[売上日]のblur
    $(".PPRM204DCOutput.txtSUriageDate").on("blur", function () {
        ODR_Jscript.DateFOut($(this));
    });

    // ========== イベント end ==========

    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM204_load();
    };

    me.PPRM204_load = function () {
        subFormInt();

        if (me.strMODE == "") {
            $(".PPRM204DCOutput.btnClose").css("display", "none");
            $(".PPRM204DCOutput.txtJHjmNO").prop("readonly", false);
            //20171013 lqs INS S
            $(".PPRM204DCOutput.txtJHjmNO").prop("disabled", false);
            //20171013 lqs INS E
            if (gdmz.SessionBusyoKB == "F") {
                $(".PPRM204DCOutput.radSeibi").prop("checked", "checked");
                $(".PPRM204DCOutput.pnlSeibi").css("display", "block");
                $(".PPRM204DCOutput.txtSTenpoCD").trigger("focus");
            } else {
                $(".PPRM204DCOutput.radJimu").prop("checked", "checked");
                $(".PPRM204DCOutput.pnlJimu").css("display", "block");
                $(".PPRM204DCOutput.txtJHjmNO").trigger("focus");
            }
        }
        if (me.strMODE == "REF") {
            $(".PPRM204DCOutput.btnPdf").css("display", "none");
            if (me.strTAISYO == "1") {
                //事務
                $(".PPRM204DCOutput.txtJHjmNO").val(me.strHNO);
                $(".PPRM204DCOutput.radJimu").prop("checked", "checked");
                $(".PPRM204DCOutput.radJPrintAll").prop("checked", "checked");

                $(".PPRM204DCOutput.txtJHjmNO").prop("readonly", "readonly");
                //20171013 lqs INS S
                $(".PPRM204DCOutput.txtJHjmNO").prop("disabled", "disabled");
                //20171013 lqs INS E
                $(".PPRM204DCOutput.radJimu").prop("disabled", true);
                $(".PPRM204DCOutput.radSeibi").prop("disabled", true);
                $(".PPRM204DCOutput.btnHjmSearch").hide();
                $(".PPRM204DCOutput.btnPdf").show();
                $(".PPRM204DCOutput.pnlJimu").show();
                var url = me.sys_id + "/" + me.id + "/" + "getTenpoCDHjmDT";
                var arr = {
                    txtJHjmNO: $(".PPRM204DCOutput.txtJHjmNO").val(),
                };
                var data = {
                    request: arr,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        $(".PPRM204DCOutput.lblJHjmDT").val(
                            result["data"][0]["HJM_SYR_DTM"]
                        );

                        var url = me.sys_id + "/" + me.id + "/" + "getTenpoNM";
                        var arr = {
                            txtSTenpoCD: result["data"][0]["TENPO_CD"],
                        };
                        var data = {
                            request: arr,
                        };
                        me.ajax.receive = function (result1) {
                            result1 = eval("(" + result1 + ")");
                            if (result["result"] == true) {
                                if (
                                    result1["data"] &&
                                    result1["data"]["BUSYO_NM"]
                                ) {
                                    $(".PPRM204DCOutput.lblJTenpoNM").val(
                                        result1["data"]["BUSYO_NM"]
                                    );
                                } else {
                                    $(".PPRM204DCOutput.lblJTenpoNM").val("");
                                }
                                me.creatPDF();
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
            } else {
                //整備
                $(".PPRM204DCOutput.txtSTenpoCD").val(me.strTCD);
                $(".PPRM204DCOutput.txtSUriageDate").val(me.strURI);
                $(".PPRM204DCOutput.radSeibi").prop("checked", "checked");
                $(".PPRM204DCOutput.radSPrintAll").prop("checked", "checked");

                $(".PPRM204DCOutput.txtSTenpoCD").prop("readonly", "readonly");
                $(".PPRM204DCOutput.txtSUriageDate").prop(
                    "readonly",
                    "readonly"
                );
                //20171013 lqs INS S
                $(".PPRM204DCOutput.txtSTenpoCD").prop("disabled", "disabled");
                $(".PPRM204DCOutput.txtSUriageDate").prop(
                    "disabled",
                    "disabled"
                );
                //20171013 lqs INS E
                $(".PPRM204DCOutput.blockDate").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
                $(".PPRM204DCOutput.radJimu").prop("disabled", true);
                $(".PPRM204DCOutput.radSeibi").prop("disabled", true);
                $(".PPRM204DCOutput.btnHjmSearch").hide();
                $(".PPRM204DCOutput.btnTenpoSearch").hide();
                $(".PPRM204DCOutput.btnPdf").show();
                $(".PPRM204DCOutput.pnlSeibi").show();
                var url = me.sys_id + "/" + me.id + "/" + "getTenpoNM";
                var arr = {
                    txtSTenpoCD: me.strTCD,
                };
                var data = {
                    request: arr,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        $(".PPRM204DCOutput.lblSTenpoNM").val(
                            result["data"]["BUSYO_NM"]
                        );
                        me.creatPDF();
                    } else {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                };
                me.ajax.send(url, data, 0);
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：プレビューボタン押下
    //'関 数 名：btnPdf_Click
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：帳票の出力を行う
    //'**********************************************************************
    me.btnPdf_Click = function () {
        if (checkInputData() == false) {
            return;
        }
        if ($(".PPRM204DCOutput.radSeibi").is(":checked")) {
            me.hidTenpoCD = $(".PPRM204DCOutput.txtSTenpoCD").val();
        }
        me.creatPDF();
    };

    //'**********************************************************************
    //'処 理 名：帳票生成
    //'関 数 名：creatPDF
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：帳票を生成する
    //'**********************************************************************
    me.creatPDF = function () {
        try {
            //me.hidOpenDate= nowDate.toLocaleString();
            me.hidReturnId = "creatPDF";
            var intState = 0;
            var lngCount = 0;
            intState = 9;

            try {
                if ($(".PPRM204DCOutput.radJimu").is(":checked")) {
                    creatPDFJimu();
                } else {
                    creatPDFSeibi();
                }

                intState = 1;
            } catch (ex) {
                clsComFnc.FncMsgBox("E9999", ex.message);
            } finally {
                if (intState != 0) {
                    if (intState == 9) {
                        lngCount = 0;
                    }
                    // if ($(".PPRM204DCOutput.radJimu").is(':checked'))
                    // {
                    // var url = me.sys_id + "/" + me.id + "/" + "fncLogEntry";
                    // var arr =
                    // {
                    // 'common' : $(".PPRM204DCOutput.txtJHjmNO").val(),
                    // 'hidTenpoCD' : me.hidTenpoCD,
                    // };
                    // var data =
                    // {
                    // request : arr
                    // };
                    // me.ajax.send(url, data, 0);
                    // }
                    // else
                    // {
                    // var url = me.sys_id + "/" + me.id + "/" + "fncLogEntry";
                    // var arr =
                    // {
                    // 'common' : $(".PPRM204DCOutput.txtSUriageDate").val(),
                    // 'hidTenpoCD' : me.hidTenpoCD,
                    // };
                    // var data =
                    // {
                    // request : arr
                    // };
                    // me.ajax.send(url, data, 0);
                    // }
                }
            }
        } catch (ex) {
            clsComFnc.FncMsgBox("E9999", ex.message);
        }
    };

    //'**********************************************************************
    //'処 理 名：日締№検索
    //'関 数 名：openHJMSearch
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：日締№検索
    //'**********************************************************************
    me.openHJMSearch = function () {
        var url = me.sys_id + "/" + "PPRM202DCSearch";

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: "PPRM204DCOutput",
                FTCD: "",
                TTCD: "",
                FDATE: "",
                TDATE: "",
            })
        );

        var arr = {};

        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            function before_close() {
                var HJMNo = o_PPRM_PPRM.PPRM202DCSearch.HJMNo;
                if (HJMNo != "") {
                    $(".PPRM204DCOutput.txtJHjmNO").val(HJMNo);
                    $(".PPRM204DCOutput.radJimu").prop("checked", "checked");
                    formJimu_all();
                } else {
                    return;
                }

                var url = me.sys_id + "/" + me.id + "/" + "getTenpoCD_HjmDT";
                var arr = {
                    txtJHjmNO: $(".PPRM204DCOutput.txtJHjmNO").val(),
                };
                var data = {
                    request: arr,
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        $(".PPRM204DCOutput.lblJHjmDT").val(
                            result["data"][0]["HJM_SYR_DTM"]
                        );

                        var url = me.sys_id + "/" + me.id + "/" + "getTenpoNM";
                        var arr = {
                            txtSTenpoCD: result["data"][0]["TENPO_CD"],
                        };
                        var data = {
                            request: arr,
                        };
                        me.ajax.receive = function (result1) {
                            result1 = eval("(" + result1 + ")");
                            $(".PPRM204DCOutput.lblJTenpoNM").val(
                                result1["data"]["BUSYO_NM"]
                            );
                        };
                        me.ajax.send(url, data, 0);
                    } else {
                        $(".PPRM204DCOutput.lblJTenpoNM").val("");
                        $(".PPRM204DCOutput.lblJHjmDT").val("");
                    }
                    $(".PPRM204DCOutput.radJPrintAll").trigger("focus");
                };
                me.ajax.send(url, data, 0);
            }

            $(".PPRM204DCOutput.PPRM202").append(result);

            o_PPRM_PPRM.PPRM202DCSearch.before_close = before_close;
        };

        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：店舗コード検索
    //'関 数 名：openTenpoSearch
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索
    //'**********************************************************************
    me.openTenpoSearch = function () {
        var url = me.sys_id + "/" + "PPRM705R4BusyoSearch";

        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: 1,
        //     })
        // );

        var arr = {};

        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM204DCOutput.txtSTenpoCD").val(busyocd);
                        $(".PPRM204DCOutput.radSeibi").prop(
                            "checked",
                            "checked"
                        );
                        formSeibi_all();
                    }

                    if (busyonm != "") {
                        $(".PPRM204DCOutput.lblSTenpoNM").val(busyonm);
                    }
                }
                $(".PPRM204DCOutput.txtSUriageDate").trigger("focus");
            }

            $(".PPRM204DCOutput.PPRM705").append(result);

            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };

        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：画面切り替え（対象_整備）
    //'関 数 名：radSeibi_CheckedChanged
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に画面の切り替えを行う
    //'**********************************************************************
    me.radSeibi_CheckedChanged = function () {
        if ($(".PPRM204DCOutput.radSeibi").is(":checked")) {
            subFormSeibi();
            $(".PPRM204DCOutput.radSeibi").prop("checked", true);
            $(".PPRM204DCOutput.pnlSeibi").css("display", "block");
            $(".PPRM204DCOutput.pnlJimu").css("display", "none");
            $(".PPRM204DCOutput.txtSTenpoCD").trigger("focus");
        }
    };

    //'**********************************************************************
    //'処 理 名：画面切り替え（対象_事務）
    //'関 数 名：radJimu_CheckedChanged
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に画面の切り替えを行う
    //'**********************************************************************
    me.radJimu_CheckedChanged = function () {
        if ($(".PPRM204DCOutput.radJimu").is(":checked")) {
            subFormJimu();
            $(".PPRM204DCOutput.radJimu").prop("checked", true);
            $(".PPRM204DCOutput.pnlJimu").css("display", "block");
            $(".PPRM204DCOutput.pnlSeibi").css("display", "none");
            $(".PPRM204DCOutput.txtJHjmNO").trigger("focus");
        }
    };

    //'**********************************************************************
    //'処 理 名：画面初期化（店舗コード）
    //'関 数 名：txtSTenpoCD_TextChanged
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に画面の初期化を行う
    //'**********************************************************************
    me.txtSTenpoCD_TextChanged = function () {
        var url = me.sys_id + "/" + me.id + "/" + "getTenpoNM";
        var arr = {
            txtSTenpoCD: $(".PPRM204DCOutput.txtSTenpoCD").val(),
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".PPRM204DCOutput.lblSTenpoNM").val(
                    result["data"]["BUSYO_NM"]
                );
                $(".PPRM204DCOutput.txtSUriageDate").trigger("focus");
            } else {
                $(".PPRM204DCOutput.lblSTenpoNM").val("");
                $(".PPRM204DCOutput.txtSUriageDate").trigger("focus");
            }
            $(".PPRM204DCOutput.radSPrintAll").prop("checked", true);
            $(".PPRM204DCOutput.radSPrintSelect").prop("checked", true);
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：画面初期化（売上日）
    //'関 数 名：txtSUriageDate_TextChanged
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に画面の初期化を行う
    //'**********************************************************************
    me.txtSUriageDate_TextChanged = function () {
        $(".PPRM204DCOutput.radSPrintAll").prop("checked", true);
        $(".PPRM204DCOutput.radSPrintSelect").prop("checked", false);
        $(".PPRM204DCOutput.radSPrintAll").trigger("focus");
    };

    //'**********************************************************************
    //'処 理 名：戻るを行う
    //'関 数 名：me.windowClose2
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面閉じる
    //'**********************************************************************
    me.windowClose = function () {
        me.flg = 2;
        $(".PPRM204DCOutput.body").dialog("close");
    };

    //'**********************************************************************
    //'処 理 名：入力チェック（必須入力）
    //'関 数 名：checkInputData
    //'引    数：なし
    //'戻 り 値：True : 正常、False : 異常
    //'処理説明：入力値にチェックを行う
    //'**********************************************************************
    function checkInputData() {
        var checkInputData = true;

        //事務の場合
        if ($(".PPRM204DCOutput.radJimu").is(":checked")) {
            if ($(".PPRM204DCOutput.txtJHjmNO").val() == "") {
                clsComFnc.FncMsgBox("E0001_PPRM", "日締№");
                checkInputData = false;
            }
            if ($(".PPRM204DCOutput.radJPrintSelect").is(":checked")) {
                if (
                    $(".PPRM204DCOutput.chkSuitoEigKsy").is(":checked") ==
                        false &&
                    $(".PPRM204DCOutput.chkSuitoEig").is(":checked") == false &&
                    $(".PPRM204DCOutput.chkCardMei").is(":checked") == false &&
                    $(".PPRM204DCOutput.chkShiireMei").is(":checked") ==
                        false &&
                    $(".PPRM204DCOutput.chkFurikaeMei").is(":checked") ==
                        false &&
                    $(".PPRM204DCOutput.chkSonotaMei").is(":checked") == false
                ) {
                    clsComFnc.FncMsgBox("E0008_PPRM", "出力する帳票");
                    checkInputData = false;
                }
            }
        }
        //整備の場合
        else {
            if ($(".PPRM204DCOutput.txtSTenpoCD").val() == "") {
                clsComFnc.FncMsgBox("E0001_PPRM", "店舗コード");
                checkInputData = false;
            }
            if ($(".PPRM204DCOutput.txtSUriageDate").val() == "") {
                clsComFnc.FncMsgBox("E0001_PPRM", "売上日");
                checkInputData = false;
            }
            if ($(".PPRM204DCOutput.radSPrintSelect").is(":checked")) {
                if (
                    $(".PPRM204DCOutput.chkSeibiNik").is(":checked") == false &&
                    $(".PPRM204DCOutput.chkSeibiGek").is(":checked") == false &&
                    $(".PPRM204DCOutput.chkUriMei").is(":checked") == false &&
                    $(".PPRM204DCOutput.chkGaichu").is(":checked") == false
                ) {
                    clsComFnc.FncMsgBox("E0008_PPRM", "出力する帳票");
                    checkInputData = false;
                }
            }
        }
        return checkInputData;
    }

    //'**********************************************************************
    //'処 理 名：日締帳票生成
    //'関 数 名：creatPDFSeibi
    //'引    数：rpt    ：出力帳票，lngCount
    //'戻 り 値：なし
    //'処理説明：日締帳票を生成する
    //'**********************************************************************
    function creatPDFSeibi() {
        var url = me.sys_id + "/" + me.id + "/" + "pdfPrintSeibi";
        var allCheck = false;
        var chkSeibiNik = false;
        var chkSeibiGek = false;
        var chkUriMei = false;
        var chkGaichu = false;
        if ($(".PPRM204DCOutput.radSPrintAll").is(":checked")) {
            allCheck = true;
        }
        if ($(".PPRM204DCOutput.chkSeibiNik").is(":checked")) {
            chkSeibiNik = true;
        }
        if ($(".PPRM204DCOutput.chkSeibiGek").is(":checked")) {
            chkSeibiGek = true;
        }
        if ($(".PPRM204DCOutput.chkUriMei").is(":checked")) {
            chkUriMei = true;
        }
        if ($(".PPRM204DCOutput.chkGaichu").is(":checked")) {
            chkGaichu = true;
        }

        var arr = {
            allCheck: allCheck,
            chkSeibiNik: chkSeibiNik,
            chkSeibiGek: chkSeibiGek,
            chkUriMei: chkUriMei,
            chkGaichu: chkGaichu,
            tenpoCD: $(".PPRM204DCOutput.txtSTenpoCD").val(),
            sUriageDate: $(".PPRM204DCOutput.txtSUriageDate").val(),
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["data"] == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    var href = result["reports"];
                    $(".PPRM204DCOutput.temp").prop("src", href);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    }

    //'**********************************************************************
    //'処 理 名：日締帳票生成
    //'関 数 名：creatPDFJimu
    //'引    数：rpt    ：出力帳票，lngCount
    //'戻 り 値：なし
    //'処理説明：日締帳票を生成する
    //'**********************************************************************
    function creatPDFJimu() {
        var url = me.sys_id + "/" + me.id + "/" + "pdfPrintJimu";
        var checked = "";
        var suitoEigKsy = false;
        var suitoEig = false;
        var cardMei = false;
        var shiireMei = false;
        var furikaeMei = false;
        var sonotaMei = false;

        if ($(".PPRM204DCOutput.radJPrintAll").is(":checked")) {
            checked = "all";
        } else {
            //20171011 YIN DEL S
            // if($(".PPRM204DCOutput.chkSuitoEig").is(':checked') || $(".PPRM204DCOutput.chkCardMei").is(':checked') ||$(".PPRM204DCOutput.chkSonotaMei").is(':checked'))
            // {
            // checked="01";
            // }
            // if($(".PPRM204DCOutput.chkSuitoEig").is(':checked') || $(".PPRM204DCOutput.chkCardMei").is(':checked'))
            // {
            // checked="03";
            // }
            // if($(".PPRM204DCOutput.chkShiireMei").is(':checked'))
            // {
            // checked="05";
            // }
            // if($(".PPRM204DCOutput.chkFurikaeMei").is(':checked'))
            // {
            // checked="07";
            // }
            //20171011 YIN DEL E
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkSuitoEigKsy").is(":checked")
        ) {
            suitoEigKsy = true;
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkSuitoEig").is(":checked")
        ) {
            suitoEig = true;
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkCardMei").is(":checked")
        ) {
            cardMei = true;
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkShiireMei").is(":checked")
        ) {
            shiireMei = true;
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkFurikaeMei").is(":checked")
        ) {
            furikaeMei = true;
        }
        if (
            $(".PPRM204DCOutput.radJPrintAll").is(":checked") ||
            $(".PPRM204DCOutput.chkSonotaMei").is(":checked")
        ) {
            sonotaMei = true;
        }
        var arr = {
            check: checked,
            suitoEigKsy: suitoEigKsy,
            suitoEig: suitoEig,
            cardMei: cardMei,
            shiireMei: shiireMei,
            furikaeMei: furikaeMei,
            sonotaMei: sonotaMei,
            hidOpenDate: me.hidOpenDate,
            txtJHjmNO: $(".PPRM204DCOutput.txtJHjmNO").val(),
        };
        var data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["data"] == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    var href = result["reports"];
                    $(".PPRM204DCOutput.temp").prop("src", href);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    }

    //事務画面（全て）
    function formJimu_all() {
        // チェック
        $(".PPRM204DCOutput.radJPrintAll").prop("checked", "checked");
        $(".PPRM204DCOutput.radJPrintSelect").prop("checked", false);

        $(".PPRM204DCOutput.chkSuitoEigKsy").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSuitoEig").prop("checked", "checked");
        $(".PPRM204DCOutput.chkCardMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkShiireMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkFurikaeMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSonotaMei").prop("checked", "checked");

        //表示
        $(".PPRM204DCOutput.chkSuitoEigKsy").prop("disabled", true);
        $(".PPRM204DCOutput.chkSuitoEig").prop("disabled", true);
        $(".PPRM204DCOutput.chkCardMei").prop("disabled", true);
        $(".PPRM204DCOutput.chkShiireMei").prop("disabled", true);
        $(".PPRM204DCOutput.chkFurikaeMei").prop("disabled", true);
        $(".PPRM204DCOutput.chkSonotaMei").prop("disabled", true);
    }

    //整備画面（全て）
    function formSeibi_all() {
        // チェック
        $(".PPRM204DCOutput.radSPrintAll").prop("checked", true);
        $(".PPRM204DCOutput.radSPrintSelect").prop("checked", false);

        $(".PPRM204DCOutput.chkSeibiNik").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSeibiGek").prop("checked", "checked");
        $(".PPRM204DCOutput.chkUriMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkGaichu").prop("checked", "checked");

        //表示
        $(".PPRM204DCOutput.chkSeibiNik").prop("disabled", true);
        $(".PPRM204DCOutput.chkSeibiGek").prop("disabled", true);
        $(".PPRM204DCOutput.chkUriMei").prop("disabled", true);
        $(".PPRM204DCOutput.chkGaichu").prop("disabled", true);
    }

    //'**********************************************************************
    //'処 理 名：画面初期化
    //'関 数 名：subFormInt
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'**********************************************************************
    function subFormInt() {
        me.hidReturnId = "";
        me.hidOpenDate = "";
        me.hidJTenpoNM = "";
        me.hidJHjmDT = "";
        me.hidSTenpoNM = "";
        //事務
        subFormJimu();
        //整備
        subFormSeibi();
        //ボタン
        $(".PPRM204DCOutput.btnPdf").show();
        $(".PPRM204DCOutput.btnClose").show();
    }

    //'**********************************************************************
    //'処 理 名：事務画面初期化
    //'関 数 名：subFormJimu
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：事務画面初期化
    //'**********************************************************************
    function subFormJimu() {
        //表示
        $(".PPRM204DCOutput.radJimu").prop("disabled", false);
        $(".PPRM204DCOutput.radJPrintAll").prop("disabled", false);
        $(".PPRM204DCOutput.radJPrintSelect").prop("disabled", false);
        $(".PPRM204DCOutput.pnlJimu").hide();
        //テキストボックス
        $(".PPRM204DCOutput.txtJHjmNO").val("");
        //ラベル
        $(".PPRM204DCOutput.lblJTenpoNM").val("");
        $(".PPRM204DCOutput.lblJHjmDT").val("");
        //ラジオボタン
        $(".PPRM204DCOutput.radJimu").prop("checked", false);
        $(".PPRM204DCOutput.radJPrintAll").prop("checked", "checked");
        $(".PPRM204DCOutput.radJPrintSelect").prop("checked", false);
        //チェックボックス
        $(".PPRM204DCOutput.chkSuitoEigKsy").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSuitoEig").prop("checked", "checked");
        $(".PPRM204DCOutput.chkCardMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkShiireMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkFurikaeMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSonotaMei").prop("checked", "checked");
    }

    //'**********************************************************************
    //'処 理 名：整備画面初期化
    //'関 数 名：subFormSeibi
    //'引    数：なし
    //'戻 り 値：なし
    //'処理説明：整備画面初期化
    //'**********************************************************************
    function subFormSeibi() {
        //表示
        $(".PPRM204DCOutput.radSeibi").prop("disabled", false);
        $(".PPRM204DCOutput.radSPrintAll").prop("disabled", false);
        $(".PPRM204DCOutput.radSPrintSelect").prop("disabled", false);
        $(".PPRM204DCOutput.pnlSeibi").css("display", "none");
        //テキストボックス
        $(".PPRM204DCOutput.txtSTenpoCD").val("");
        $(".PPRM204DCOutput.txtSUriageDate").val("");
        //ラベル
        $(".PPRM204DCOutput.lblSTenpoNM").val("");
        //ラジオボタン
        $(".PPRM204DCOutput.radSeibi").prop("checked", false);
        $(".PPRM204DCOutput.radSPrintAll").prop("checked", "checked");
        $(".PPRM204DCOutput.radSPrintSelect").prop("checked", false);
        //チェックボックス
        $(".PPRM204DCOutput.chkSeibiNik").prop("checked", "checked");
        $(".PPRM204DCOutput.chkSeibiGek").prop("checked", "checked");
        $(".PPRM204DCOutput.chkUriMei").prop("checked", "checked");
        $(".PPRM204DCOutput.chkGaichu").prop("checked", "checked");
    }

    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM204DCOutput = new PPRM.PPRM204DCOutput();
    o_PPRM_PPRM204DCOutput.load();
    o_PPRM_PPRM.PPRM204DCOutput = o_PPRM_PPRM204DCOutput;
});
