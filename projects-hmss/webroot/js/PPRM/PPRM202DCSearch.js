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
 * 20201119            bug                          ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * 20201120            bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる      WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM202DCSearch");

PPRM.PPRM202DCSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ODR_Jscript = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    me.ajax = new gdmz.common.ajax();
    me.id = "PPRM202DCSearch";
    me.sys_id = "PPRM";
    me.title = "日締検索";
    me.url = "";
    me.data = new Array();

    me.strProgramID = "";
    me.strFromTCD = "";
    me.strToTCD = "";
    me.strFromDATE = "";
    me.strToDATE = "";
    me.hidGamenID = "";
    me.flag = false;
    me.firstrdbTaisyo1 = true;
    me.firstrdbTaisyo2 = true;

    //20170905 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170905 ZHANGXIAOLEI INS E

    //jqgrid
    {
        me.grid_id = "#PPRM202DCSearch_spdList";
        me.grid_id1 = "#PPRM202DCSearch_spdList1";
        me.g_url = "";
        me.pager = "";
        me.sidx = "";

        me.option = {
            rowNum: 9999,
            recordpos: "left",
            multiselect: false,
            rownumWidth: 30,
            rownumbers: true,
            caption: "",
            multiselectWidth: 30,
            scroll: 1,
        };

        me.colModel = [
            {
                name: "HJM_SYR_DTM",
                label: "日締日時",
                index: "HJM_SYR_DTM",
                width: 170,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                frozen: true,
            }, //
            {
                name: "BUSYO_RYKNM",
                label: "店舗",
                index: "BUSYO_RYKNM",
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 110,
            }, //
            {
                name: "TEN_HJM_NO",
                label: "日締№",
                index: "TEN_HJM_NO",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 130,
            }, //
            {
                name: "EGK_KEJ_KENSU",
                label: "計上件数",
                index: "EGK_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO1",
                label: "伝票№",
                index: "DENPYO1",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "KMY_KEJ_KENSU",
                label: "計上件数",
                index: "KMY_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO2",
                label: "伝票№",
                index: "DENPYO2",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "CRD_DEN_DTL_KEJ_KENSU",
                label: "計上件数",
                index: "CRD_DEN_DTL_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO3",
                label: "伝票№",
                index: "DENPYO3",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "SIR_DEN_DTL_KEJ_KENSU",
                label: "計上件数",
                index: "SIR_DEN_DTL_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO4",
                label: "伝票№",
                index: "DENPYO4",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "FRK_DEN_DTL_KEJ_KENSU",
                label: "計上件数",
                index: "FRK_DEN_DTL_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO5",
                label: "伝票№",
                index: "DENPYO5",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "ETC_DEN_DTL_KEJ_KENSU",
                label: "計上件数",
                index: "ETC_DEN_DTL_KEJ_KENSU",
                frozen: false,
                width: 65,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                align: "right",
            }, //
            {
                name: "DENPYO6",
                label: "伝票№",
                index: "DENPYO6",
                frozen: false,
                //20171115 lqs INS S
                sortable: false,
                //20171115 lqs INS E
                width: 250,
            }, //
            {
                name: "TENPO_CD",
                label: "",
                index: "TENPO_CD",
                hidden: true,
            }, //
            {
                name: "SONZAI",
                label: "",
                index: "SONZAI",
                hidden: true,
            }, //
            {
                name: "PRINT_DISP_FLG",
                label: "",
                index: "PRINT_DISP_FLG",
                hidden: true,
            }, //
            {
                name: "IMAGE_DISP_FLG",
                label: "",
                index: "IMAGE_DISP_FLG",
                hidden: true,
            }, //
            {
                name: "KINSYU_DISP_FLG",
                label: "",
                index: "KINSYU_DISP_FLG",
                hidden: true,
            }, //
        ];

        me.option1 = {
            rowNum: 9999,
            recordpos: "left",
            multiselect: false,
            rownumbers: true,
            caption: "",
            multiselectWidth: 30,
            scroll: 1,
        };

        me.colModel3 = [
            {
                name: "URIAGEDT",
                label: "売上日",
                sortable: false,
                index: "URIAGEDT",
            }, //
            {
                name: "BUSYO_RYKNM",
                label: "店舗",
                sortable: false,
                index: "BUSYO_RYKNM",
            }, //
            {
                name: "TENPO_CD",
                label: "",
                index: "TENPO_CD",
                hidden: true,
            }, //
            {
                name: "SONZAI",
                label: "",
                index: "SONZAI",
                hidden: true,
            }, //
            {
                name: "PRINT_DISP_FLG",
                label: "",
                index: "PRINT_DISP_FLG",
                hidden: true,
            }, //
            {
                name: "IMAGE_DISP_FLG",
                label: "",
                index: "IMAGE_DISP_FLG",
                hidden: true,
            }, //
            {
                name: "KINSYU_DISP_FLG",
                label: "",
                index: "KINSYU_DISP_FLG",
                hidden: true,
            }, //
        ];
    }

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM202DCSearch.btnFromTenpoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.btnToTenpoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.btnSelect",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.btnClose",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.openHijimeOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.ImgOpenFile",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.openKinsyuIn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM202DCSearch.txtHJMFromDate",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".PPRM202DCSearch.txtHJMToDate",
        type: "datepicker",
        handle: "",
    });

    // ========== コントロール end ==========

    // ========== イベント start ==========

    //【検索(店舗コードfrom)】ボタン押下
    $(".PPRM202DCSearch.btnFromTenpoSearch").click(function () {
        me.openFromTenpoSearch();
    });
    //【検索(店舗コードto)】ボタン押下
    $(".PPRM202DCSearch.btnToTenpoSearch").click(function () {
        me.openToTenpoSearch();
    });
    //【検索(jqgrid)】ボタン押下
    $(".PPRM202DCSearch.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //【日締ﾌﾟﾚﾋﾞｭｰ】ボタン押下
    $(".PPRM202DCSearch.openHijimeOut").click(function () {
        me.openHijimeOut();
    });
    //【イメージファイル】ボタン押下
    $(".PPRM202DCSearch.ImgOpenFile").click(function () {
        me.ImgOpenFile();
    });
    //【金種表参照】ボタン押下
    $(".PPRM202DCSearch.openKinsyuIn").click(function () {
        me.openKinsyuIn();
    });
    //【選択】ボタン押下
    $(".PPRM202DCSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //【戻る】ボタン押下
    $(".PPRM202DCSearch.btnClose").click(function () {
        me.windowClose2();
    });
    //【事務】のchange
    $(".PPRM202DCSearch.rdbTaisyo1").change(function () {
        me.rdbTaisyo1_CheckedChanged();
    });
    //【整備】のchange
    $(".PPRM202DCSearch.rdbTaisyo2").change(function () {
        me.rdbTaisyo2_CheckedChanged();
    });
    //【店舗コード(from)】のchange
    $(".PPRM202DCSearch.txtFromTenpoCD").change(function () {
        spdClear();
    });
    //【店舗コード(to)】のchange
    $(".PPRM202DCSearch.txtToTenpoCD").change(function () {
        spdClear();
    });
    //【日締日(from)】のchange
    $(".PPRM202DCSearch.txtHJMFromDate").change(function () {
        spdClear();
    });
    //【日締日(to)】のchange
    $(".PPRM202DCSearch.txtHJMToDate").change(function () {
        spdClear();
    });
    //【日締№】のchange
    $(".PPRM202DCSearch.txtHJMNo").change(function () {
        spdClear();
    });
    //【店舗コード(from)】のblur
    $(".PPRM202DCSearch.txtFromTenpoCD").on("blur", function () {
        // 2017/09/08 CI UPD S
        //me.txtFromTenpoCDBlur();
        me.txtFromTenpoCDBlur($(this), $(".PPRM202DCSearch.lblFromTenpo"), "F");
        // 2017/09/08 CI UPD E
    });
    //【店舗コード(to)】のblur
    $(".PPRM202DCSearch.txtToTenpoCD").on("blur", function () {
        // 2017/09/08 CI UPD S
        //me.txtToTenpoCDBlur();
        me.txtFromTenpoCDBlur($(this), $(".PPRM202DCSearch.lblTenpo"), "T");
        // 2017/09/08 CI UPD E
    });
    //【日締日(from)】のblur
    $(".PPRM202DCSearch.txtHJMFromDate").on("blur", function () {
        ODR_Jscript.DateFOut($(this));
    });
    //【日締日(to)】のblur
    $(".PPRM202DCSearch.txtHJMToDate").on("blur", function () {
        ODR_Jscript.DateFOut($(this));
    });

    // 2017/09/08 CI INS S
    $(".PPRM202DCSearch.txtFromTenpoCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    $(".PPRM202DCSearch.txtToTenpoCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });
    $(".PPRM202DCSearch.txtHJMNo").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });
    // 2017/09/08 CI INS E

    // ========== イベント end ==========

    //引数を画面に表示する
    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.hidGamenID = requestdata["REFPRG"];
        me.strFromTCD = requestdata["FTCD"];
        me.strToTCD = requestdata["TTCD"];
        me.strFromDATE = requestdata["FDATE"];
        me.strToDATE = requestdata["TDATE"];
    }

    localStorage.removeItem("requestdata");

    me.before_close = function () {};

    if (
        me.hidGamenID != "" &&
        me.hidGamenID != undefined &&
        me.hidGamenID != null
    ) {
        $(".PPRM202DCSearch.body").dialog({
            autoOpen: false,
            width: 970,
            height: me.ratio === 1.5 ? 530 : 660,
            modal: true,
            title: me.title,
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM202DCSearch.body").remove();
            },
        });
        $(".PPRM202DCSearch.body").dialog("open");
    }

    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();

        //20170907 ZHANGXIAOLEI UPD S
        //20170905 ZHANGXIAOLEI INS S
        me.getAllBusyoNM();
        //20170905 ZHANGXIAOLEI INS E
        //20170907 ZHANGXIAOLEI UPD E
        // 20170922 lqs INS S
        //Enterキーのバインド
        me.EnterKeyDown();
        clsComFnc.TabKeyDown();
        // 20170922 lqs INS E
    };
    me.EnterKeyDown = function () {
        var $inp = $(".Enter202");
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
                                $(".Enter202:eq(" + (i + 1) + ")").prop(
                                    "disabled"
                                ) != true
                            ) {
                                $(".Enter202:eq(" + (i + 1) + ")").trigger(
                                    "focus"
                                );
                                $(".Enter202:eq(" + (i + 1) + ")").select();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        } else {
                            for (var j = 0; j < $inp.length; j++) {
                                if (
                                    $(".Enter202:eq(" + j + ")").prop(
                                        "disabled"
                                    ) != true
                                ) {
                                    $(".Enter202:eq(" + j + ")").trigger(
                                        "focus"
                                    );
                                    $(".Enter202:eq(" + j + ")").select();
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

    //20170907 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：店舗コード検索のコールバック
    //'関 数 名：me.fncComplete
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索のコールバック
    //'**********************************************************************
    me.fncComplete = function () {
        if (
            me.hidGamenID == "" ||
            me.hidGamenID == null ||
            me.hidGamenID == undefined
        ) {
            subFormInt1();

            if (
                gdmz.SessionBusyoCD != 122 &&
                gdmz.SessionBusyoCD != 125
            ) {
                //20170905 ZHANGXIAOLEI UPD S
                // $(".txtFromTenpoCD").val(gdmz.SessionTenpoCD);
                // $(".txtToTenpoCD").val(gdmz.SessionTenpoCD);
                $(".PPRM202DCSearch.txtFromTenpoCD").val(
                    gdmz.SessionTenpoCD
                );
                $(".PPRM202DCSearch.txtToTenpoCD").val(gdmz.SessionTenpoCD);
                //20170905 ZHANGXIAOLEI UPD E
                var txtFromTenpoCD = $(".PPRM202DCSearch.txtFromTenpoCD").val();
                var txtToTenpoCD = $(".PPRM202DCSearch.txtToTenpoCD").val();

                //20170905 ZHANGXIAOLEI UPD S
                // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
                // var arrFrom =
                // {
                // 'txtTenpoCD' : txtFromTenpoCD
                // };
                // var dataFrom =
                // {
                // request : arrFrom
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblFromTenpo").val(result["data"]["strBusyoNM"]);
                // }
                //
                // var arrTo =
                // {
                // 'txtTenpoCD' : txtToTenpoCD
                // };
                // var dataTo =
                // {
                // request : arrTo
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblTenpo").val(result["data"]["strBusyoNM"]);
                // }
                // };
                // me.ajax.send(url, dataTo, 0);
                // };
                // me.ajax.send(url, dataFrom, 0);
                $(".PPRM202DCSearch.lblFromTenpo").val(
                    me.FncGetBusyoNM(txtFromTenpoCD)
                );
                $(".PPRM202DCSearch.lblTenpo").val(
                    me.FncGetBusyoNM(txtToTenpoCD)
                );
                //20170905 ZHANGXIAOLEI UPD E
            }
            if (gdmz.SessionBusyoKB == "F") {
                $(".rdbTaisyo2").prop("checked", "checked");
                subFormInt4();
            } else {
                $(".rdbTaisyo1").prop("checked", "checked");
                subFormInt3();
            }
        } else {
            //20170911 YIN UPD S
            // if ($(".rdbTaisyo1").is(':checked'))
            // {
            // subFormInt3();
            // }
            $(".rdbTaisyo1").prop("checked", "checked");
            subFormInt3();
            //20170911 YIN UPD E

            //対象非表示
            $(".PPRM202DCSearch.tr1").css("display", "none");
            //テキストボックス
            $(".PPRM202DCSearch.txtFromTenpoCD").val(me.strFromTCD);
            $(".PPRM202DCSearch.txtToTenpoCD").val(me.strToTCD);
            $(".PPRM202DCSearch.txtHJMFromDate").val(me.strFromDATE);
            $(".PPRM202DCSearch.txtHJMToDate").val(me.strToDATE);
            $(".PPRM202DCSearch.txtHJMNo").val("");
            //ボタン
            //20201119 WL UPD S
            //$(".PPRM202DCSearch.btnFromTenpoSearch").attr("disabled", false);
            //$(".PPRM202DCSearch.btnToTenpoSearch").disabled = false;
            //$(".PPRM202DCSearch.btnToTenpoSearch").attr("disabled", false);
            $(".PPRM202DCSearch.btnFromTenpoSearch").button("enable");
            $(".PPRM202DCSearch.btnToTenpoSearch").button("enable");
            //20201119 WL UPD E
            $(".PPRM202DCSearch.btnSearch").css("display", "block");
            $(".PPRM202DCSearch.btnClose").css("display", "block");
            $(".PPRM202DCSearch.btnSelect").css("display", "none");
            //スプレッド
            $("#gbox_PPRM202DCSearch_spdList").hide();
            $("#gbox_PPRM202DCSearch_spdList1").hide();

            if (
                $(".PPRM202DCSearch.txtFromTenpoCD").val() == "" &&
                $(".PPRM202DCSearch.txtToTenpoCD").val() == ""
            ) {
                if (
                    gdmz.SessionBusyoCD != 122 &&
                    gdmz.SessionBusyoCD != 125
                ) {
                    $(".PPRM202DCSearch.txtFromTenpoCD").val(
                        gdmz.SessionTenpoCD
                    );
                    $(".PPRM202DCSearch.txtToTenpoCD").val(
                        gdmz.SessionTenpoCD
                    );
                    var txtFromTenpoCD = $(
                        ".PPRM202DCSearch.txtFromTenpoCD"
                    ).val();
                    var txtToTenpoCD = $(".PPRM202DCSearch.txtToTenpoCD").val();

                    //20170905 ZHANGXIAOLEI UPD S
                    // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
                    // var arrFrom =
                    // {
                    // 'txtTenpoCD' : txtFromTenpoCD
                    // };
                    // var dataFrom =
                    // {
                    // request : arrFrom
                    // };
                    // me.ajax.receive = function(result1)
                    // {
                    // result1 = eval('(' + result1 + ')');
                    //
                    // if (result1['result'] == false)
                    // {
                    // return;
                    // }
                    // else
                    // {
                    // $(".PPRM202DCSearch.lblFromTenpo").val(result1["data"]["strBusyoNM"]);
                    // }
                    //
                    // var arrTo =
                    // {
                    // 'txtTenpoCD' : txtToTenpoCD
                    // };
                    // var dataTo =
                    // {
                    // request : arrTo
                    // };
                    // me.ajax.receive = function(result2)
                    // {
                    // result2 = eval('(' + result2 + ')');
                    //
                    // if (result2['result'] == false)
                    // {
                    // return;
                    // }
                    // else
                    // {
                    // $(".PPRM202DCSearch.lblTenpo").val(result2["data"]["strBusyoNM"]);
                    // }
                    // };
                    // me.ajax.send(url, dataTo, 0);
                    // };
                    // me.ajax.send(url, dataFrom, 0);
                    $(".PPRM202DCSearch.lblFromTenpo").val(
                        me.FncGetBusyoNM(txtFromTenpoCD)
                    );
                    $(".PPRM202DCSearch.lblTenpo").val(
                        me.FncGetBusyoNM(txtToTenpoCD)
                    );
                    //20170905 ZHANGXIAOLEI UPD E
                }
            } else {
                subFormInt2();
            }
        }
        var urlLoad = me.sys_id + "/" + me.id + "/" + "pprm202DCSearchLoad";
        var dataLoad = {
            txtTenpoCD: $(".PPRM202DCSearch.txtFromTenpoCD").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //20170907 ZHANGXIAOLEI INS S
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            //20170907 ZHANGXIAOLEI INS E

            for (key in result["data"]) {
                $(".PPRM202DCSearch." + key).prop(
                    "disabled",
                    result["data"][key]
                );
            }
        };
        me.ajax.send(urlLoad, dataLoad, 0);

        $(".PPRM202DCSearch.txtFromTenpoCD").trigger("focus");
    };
    //20170907 ZHANGXIAOLEI INS E

    //20170905 ZHANGXIAOLEI INS S
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
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                //20170907 ZHANGXIAOLEI INS S
                clsComFnc.FncMsgBox("E9999", result["data"]);
                //20170907 ZHANGXIAOLEI INS E
                return;
            } else {
                me.BusyoArr = result["data"];
                me.fncComplete();
            }
        };
        me.ajax.send(url, selectObj, 0);
    };

    me.FncGetBusyoNM = function (strCD) {
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
    //20170905 ZHANGXIAOLEI INS E

    //'**********************************************************************
    //'処 理 名：検索(店舗コードfrom)を行う
    //'関 数 名：me.openFromTenpoSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索画面遷移
    //'**********************************************************************
    me.openFromTenpoSearch = function () {
        var url = me.sys_id + "/" + "PPRM705R4BusyoSearch" + "/" + "index";

        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: 1,
        //     })
        // );

        var arr = {};
        me.data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            $(".PPRM705R4BusyoSearch_dialog").append(result);
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM202DCSearch.txtFromTenpoCD").val(busyocd);
                        spdClear();
                    } else {
                    }
                    if (busyonm != "") {
                        $(".PPRM202DCSearch.lblFromTenpo").val(busyonm);
                    } else {
                    }
                }
            }
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };
        me.ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：検索(店舗コードto)を行う
    //'関 数 名：me.openToTenpoSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索画面遷移
    //'**********************************************************************
    me.openToTenpoSearch = function () {
        var url = me.sys_id + "/" + "PPRM705R4BusyoSearch" + "/" + "index";

        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: 1,
        //     })
        // );

        var arr = {};
        me.data = {
            request: arr,
        };
        me.ajax.receive = function (result) {
            $(".PPRM705R4BusyoSearch_dialog").html(result);
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM202DCSearch.txtToTenpoCD").val(busyocd);
                        spdClear();
                    } else {
                    }
                    if (busyonm != "") {
                        $(".PPRM202DCSearch.lblTenpo").val(busyonm);
                    } else {
                    }
                }
            }
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };
        me.ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：検索を行う
    //'関 数 名：btnSearch_Click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：条件に一致する検索結果を一覧に表示する
    //'**********************************************************************
    me.btnSearch_Click = function () {
        var txtFromTenpoCD = $(".PPRM202DCSearch.txtFromTenpoCD").val();
        var txtToTenpoCD = $(".PPRM202DCSearch.txtToTenpoCD").val();
        var txtHJMFromDate = $(".PPRM202DCSearch.txtHJMFromDate").val();
        var txtHJMToDate = $(".PPRM202DCSearch.txtHJMToDate").val();
        var tdtxtHJM = $(".PPRM202DCSearch.txtHJMNo").val();
        $("#PPRM202DCSearch_spdList").jqGrid("clearGridData");
        $("#PPRM202DCSearch_spdList1").jqGrid("clearGridData");

        $(".PPRM202DCSearch.openHijimeOut").css("display", "none");
        $(".PPRM202DCSearch.ImgOpenFile").css("display", "none");
        $(".PPRM202DCSearch.openKinsyuIn").css("display", "none");

        //20201119 WL UPD S
        // $(".PPRM202DCSearch.openHijimeOut").attr("disabled", "disabled");
        // $(".PPRM202DCSearch.ImgOpenFile").attr("disabled", "disabled");
        // $(".PPRM202DCSearch.openKinsyuIn").attr("disabled", "disabled");
        $(".PPRM202DCSearch.openHijimeOut").button("disable");
        $(".PPRM202DCSearch.ImgOpenFile").button("disable");
        $(".PPRM202DCSearch.openKinsyuIn").button("disable");
        //20201119 WL UPD E

        if (
            me.hidGamenID == "" ||
            me.hidGamenID == undefined ||
            me.hidGamenID == null
        ) {
            $(".PPRM202DCSearch.btnSelect").css("display", "none");
        } else {
            $(".PPRM202DCSearch.btnSelect").css("display", "block");
        }

        if (txtFromTenpoCD != "" && txtToTenpoCD != "") {
            if (txtFromTenpoCD > txtToTenpoCD) {
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "店舗コード（前）",
                    "店舗コード（後）"
                );
                return;
            }
        }
        if (txtHJMFromDate != "" && txtHJMToDate != "") {
            if (txtHJMFromDate > txtHJMToDate) {
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "日締日（前）",
                    "日締日（後）"
                );
                return;
            }
            //2017/09/25 CI INS S
            var startTime = new Date(
                Date.parse(txtHJMFromDate.replace(/-/g, "/"))
            ).getTime();
            var endTime = new Date(
                Date.parse(txtHJMToDate.replace(/-/g, "/"))
            ).getTime();
            var dates = Math.abs(startTime - endTime) / (1000 * 60 * 60 * 24);
            if (dates > 30) {
                clsComFnc.FncMsgBox("W9999", "指定された期間が長すぎます。");
                return;
            }
            //2017/09/25 CI INS E
        }

        $(".PPRM202DCSearch.spdList").css("display", "none");
        $(".PPRM202DCSearch.spdList1").css("display", "none");

        if ($(".PPRM202DCSearch.rdbTaisyo1").is(":checked")) {
            var url = me.sys_id + "/" + me.id + "/" + "fncSelectHJM";
            var arr = {
                txtFromTenpoCD: txtFromTenpoCD,
                txtToTenpoCD: txtToTenpoCD,
                txtHJMFromDate: txtHJMFromDate,
                txtHJMToDate: txtHJMToDate,
                tdtxtHJM: tdtxtHJM,
            };
            var data = {
                request: arr,
            };

            me.complete_fun = function (bErrorFlag) {
                //20201120 WL DEL S
                // //20170913 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                // //20170913 YIN INS E
                //20201120 WL DEL E

                $(".PPRM202DCSearch_spdList").jqGrid("setGridParam", {
                    onSelectRow: function (rowid) {
                        var rowData = $("#PPRM202DCSearch_spdList").jqGrid(
                            "getRowData",
                            rowid
                        );

                        if (clsComFnc.FncNz(rowData["PRINT_DISP_FLG"]) == 0) {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openHijimeOut").attr("disabled", "disabled");
                            $(".PPRM202DCSearch.openHijimeOut").button(
                                "disable"
                            );
                            //20201119 WL UPD E
                        } else {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openHijimeOut").attr("disabled", false);
                            $(".PPRM202DCSearch.openHijimeOut").button(
                                "enable"
                            );
                            //20201119 WL UPD E
                        }

                        if (rowData["SONZAI"] == "") {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.ImgOpenFile").attr("disabled", "disabled");
                            $(".PPRM202DCSearch.ImgOpenFile").button("disable");
                            //20201119 WL UPD E
                        } else {
                            if (
                                clsComFnc.FncNz(rowData["IMAGE_DISP_FLG"]) == 0
                            ) {
                                //20201119 WL UPD S
                                //$(".PPRM202DCSearch.ImgOpenFile").attr("disabled", "disabled");
                                $(".PPRM202DCSearch.ImgOpenFile").button(
                                    "disable"
                                );
                                //20201119 WL UPD E
                            } else {
                                //20201119 WL UPD S
                                //$(".PPRM202DCSearch.ImgOpenFile").attr("disabled", false);
                                $(".PPRM202DCSearch.ImgOpenFile").button(
                                    "enable"
                                );
                                //20201119 WL UPD E
                            }
                        }

                        if (rowData["KINSYU_DISP_FLG"] == 0) {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openKinsyuIn").attr("disabled", "disabled");
                            $(".PPRM202DCSearch.openKinsyuIn").button(
                                "disable"
                            );
                            //20201119 WL UPD E
                        } else {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openKinsyuIn").attr("disabled", false);
                            $(".PPRM202DCSearch.openKinsyuIn").button("enable");
                            //20201119 WL UPD E
                        }
                    },
                });
                if (bErrorFlag == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                    $(".PPRM202DCSearch.btnSelect").css("display", "none");
                    return;
                } else {
                    if (
                        me.hidGamenID == "" ||
                        me.hidGamenID == undefined ||
                        me.hidGamenID == null
                    ) {
                        $(".PPRM202DCSearch.openHijimeOut").css(
                            "display",
                            "block"
                        );
                        $(".PPRM202DCSearch.ImgOpenFile").css(
                            "display",
                            "block"
                        );
                        $(".PPRM202DCSearch.openKinsyuIn").css(
                            "display",
                            "block"
                        );
                    } else {
                        $(".PPRM202DCSearch.openHijimeOut").css(
                            "display",
                            "none"
                        );
                        $(".PPRM202DCSearch.ImgOpenFile").css(
                            "display",
                            "none"
                        );
                        $(".PPRM202DCSearch.openKinsyuIn").css(
                            "display",
                            "none"
                        );
                    }
                    if (
                        me.hidGamenID != "" &&
                        me.hidGamenID != null &&
                        me.hidGamenID != undefined
                    ) {
                        $(".PPRM202DCSearch.btnSelect").css("display", "block");
                    }
                    $(".PPRM202DCSearch.spdList").css("display", "block");
                }
            };

            if (me.firstrdbTaisyo1 == true) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option,
                    data,
                    me.complete_fun
                );
                me.firstrdbTaisyo1 = false;
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    me.complete_fun
                );
            }

            gdmz.common.jqgrid.set_grid_width(me.grid_id, 790);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 202 : 275);

            $("#gbox_PPRM202DCSearch_spdList").show();

            if (me.flag == false) {
                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            startColumnName: "EGK_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "現金出納帳（営業）",
                        }, //
                        {
                            startColumnName: "KMY_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "現金出納帳（小口）",
                        }, //
                        {
                            startColumnName: "CRD_DEN_DTL_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "カード伝票明細一覧表",
                        }, //
                        {
                            startColumnName: "SIR_DEN_DTL_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "仕入伝票明細一覧表",
                        }, //
                        {
                            startColumnName: "FRK_DEN_DTL_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "振替伝票明細計上件数",
                        }, //
                        {
                            startColumnName: "ETC_DEN_DTL_KEJ_KENSU",
                            numberOfColumns: 2,
                            titleText: "その他伝票明細一覧表",
                        }, //
                    ],
                });
                me.flag = true;
            }
        } else {
            var url = me.sys_id + "/" + me.id + "/" + "fncSelectURI";
            var arr = {
                txtFromTenpoCD: txtFromTenpoCD,
                txtToTenpoCD: txtToTenpoCD,
                txtHJMFromDate: txtHJMFromDate,
                txtHJMToDate: txtHJMToDate,
            };
            var data = {
                request: arr,
            };

            me.complete_fun = function (bErrorFlag) {
                //20201120 WL DEL S
                // //20170913 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                // //20170913 YIN INS E
                //20201120 WL DEL E

                $(".PPRM202DCSearch_spdList1").jqGrid("setGridParam", {
                    onSelectRow: function (rowid) {
                        var rowData = $("#PPRM202DCSearch_spdList1").jqGrid(
                            "getRowData",
                            rowid
                        );

                        if (clsComFnc.FncNz(rowData["PRINT_DISP_FLG"]) == 0) {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openHijimeOut").attr("disabled", "disabled");
                            $(".PPRM202DCSearch.openHijimeOut").button(
                                "disable"
                            );
                            //20201119 WL UPD E
                        } else {
                            //20201119 WL UPD S
                            //$(".PPRM202DCSearch.openHijimeOut").attr("disabled", false);
                            $(".PPRM202DCSearch.openHijimeOut").button(
                                "enable"
                            );
                            //20201119 WL UPD E
                        }
                    },
                });
                if (bErrorFlag == "nodata") {
                    $(".PPRM202DCSearch.txtFromTenpoCD").trigger("focus");
                    clsComFnc.FncMsgBox("W0003_PPRM");
                    return;
                } else {
                    $(".PPRM202DCSearch.openHijimeOut").css("display", "block");
                    //20170926 lqs DEL S
                    //$(".PPRM202DCSearch.openHijimeOut").css("background", "#16b1e9");
                    //20170926 lqs DEL E
                    $(".PPRM202DCSearch.spdList1").css("display", "block");
                }
            };

            if (me.firstrdbTaisyo2 == true) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id1,
                    url,
                    me.colModel3,
                    me.pager,
                    me.sidx,
                    me.option1,
                    data,
                    me.complete_fun
                );
                me.firstrdbTaisyo2 = false;
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id1,
                    data,
                    me.complete_fun
                );
            }
            gdmz.common.jqgrid.set_grid_width(me.grid_id1, 360);
            gdmz.common.jqgrid.set_grid_height(me.grid_id1, me.ratio === 1.5 ? 220 : 260);

            $("#gbox_PPRM202DCSearch_spdList1").show();

            $(".PPRM202DCSearch_spdList1").closest(".ui-jqgrid-bdiv").css({
                "overflow-x": "hidden",
            });
        }
    };

    //'**********************************************************************
    //'処 理 名：日締ﾌﾟﾚﾋﾞｭｰを行う
    //'関 数 名：me.openHijimeOut
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：日締出力帳票画面遷移
    //'**********************************************************************
    me.openHijimeOut = function () {
        if ($(".PPRM202DCSearch.rdbTaisyo1").is(":checked")) {
            var id = $("#PPRM202DCSearch_spdList").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#PPRM202DCSearch_spdList").jqGrid(
                "getRowData",
                id
            );

            var url = me.sys_id + "/" + "PPRM204DCOutput";

            localStorage.setItem(
                "requestdata",
                JSON.stringify({
                    MODE: "REF",
                    TAISYO: 1,
                    HNO: rowData["TEN_HJM_NO"],
                    TCD: rowData["TENPO_CD"],
                    URI: "",
                    timestamp: new Date().getTime(),
                })
            );

            me.data = {};
            me.ajax.receive = function (result) {
                $(".PPRM204_DC_Output_dialog").html(result);

                function before_close() {
                    $(".PPRM202DCSearch.rdbTaisyo1").prop("checked", "checked");
                    $(".PPRM202DCSearch.btnClose").css("display", "none");
                }
                o_PPRM_PPRM.PPRM204DCOutput.before_close = before_close;
            };
            me.ajax.send(url, me.data, 0);
        } else {
            var id = $("#PPRM202DCSearch_spdList1").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#PPRM202DCSearch_spdList1").jqGrid(
                "getRowData",
                id
            );

            if (id == null || id == undefined || id == "") {
                clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            } else {
                var url = me.sys_id + "/" + "PPRM204DCOutput";

                localStorage.setItem(
                    "requestdata",
                    JSON.stringify({
                        MODE: "REF",
                        TAISYO: 2,
                        HNO: "",
                        TCD: rowData["TENPO_CD"],
                        URI: rowData["URIAGEDT"],
                        timestamp: new Date().getTime(),
                    })
                );

                me.data = {};
                me.ajax.receive = function (result) {
                    $(".PPRM204_DC_Output_dialog").html(result);
                    function before_close() {
                        $(".PPRM202DCSearch.rdbTaisyo2").prop(
                            "checked",
                            "checked"
                        );
                        $(".PPRM202DCSearch.btnClose").css("display", "none");
                    }
                    o_PPRM_PPRM.PPRM204DCOutput.before_close = before_close;
                };
                me.ajax.send(url, me.data, 0);
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：金種表参照を行う
    //'関 数 名：me.openKinsyuIn
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：金種表入力画面遷移
    //'**********************************************************************
    me.openKinsyuIn = function () {
        var id = $("#PPRM202DCSearch_spdList").jqGrid("getGridParam", "selrow");
        var rowData = $("#PPRM202DCSearch_spdList").jqGrid("getRowData", id);

        if (id == null || id == undefined || id == "") {
            me.clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
        } else {
            var url =
                me.sys_id + "/" + "PPRM203DCMonyKindInput" + "/" + "index";

            localStorage.setItem(
                "requestdata",
                JSON.stringify({
                    REFPRG: "PPRM202DCSearch",
                    TCD: rowData["TENPO_CD"],
                    HDATE: rowData["HJM_SYR_DTM"],
                    HNO: rowData["TEN_HJM_NO"],
                    timestamp: new Date().getTime(),
                })
            );

            me.data = {};
            me.ajax.receive = function (result) {
                $(".PPRM203_DC_MonyKindInput_dialog").html(result);
            };
            me.ajax.send(url, me.data, 0);
        }
    };

    //'**********************************************************************
    //'処 理 名：イメージファイルを行う
    //'関 数 名：me.ImgOpenFile
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイル開く
    //'**********************************************************************
    me.ImgOpenFile = function () {
        var url = me.sys_id + "/" + "PPRMjpgView";
        var id = $("#PPRM202DCSearch_spdList").jqGrid("getGridParam", "selrow");
        var rowData = $("#PPRM202DCSearch_spdList").jqGrid("getRowData", id);

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: 1,
                TCD: rowData["TENPO_CD"],
                HNO: rowData["TEN_HJM_NO"],
                timestamp: new Date().getTime(),
            })
        );

        me.data = {};
        me.ajax.receive = function (result) {
            $(".PPRM204_DC_Output_dialog").html(result);
        };
        me.ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：対象切替
    //'関 数 名：me.rdbTaisyo1_CheckedChanged
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：事務を選択した場合
    //'**********************************************************************
    me.rdbTaisyo1_CheckedChanged = function () {
        $(".PPRM202DCSearch.openHijimeOut").css("display", "none");
        $(".PPRM202DCSearch.ImgOpenFile").css("display", "none");
        $(".PPRM202DCSearch.openKinsyuIn").css("display", "none");

        me.flag = true;

        if ($(".PPRM202DCSearch.rdbTaisyo1").is(":checked")) {
            $(".PPRM202DCSearch.txtFromTenpoCD").val("");
            $(".PPRM202DCSearch.txtToTenpoCD").val("");
            $(".PPRM202DCSearch.txtHJMFromDate").val("");
            $(".PPRM202DCSearch.txtHJMToDate").val("");
            $(".PPRM202DCSearch.txtHJMNo").val("");
            $(".PPRM202DCSearch.lblFromTenpo").val("");
            $(".PPRM202DCSearch.lblTenpo").val("");

            if (
                gdmz.SessionBusyoCD != 122 &&
                gdmz.SessionBusyoCD != 125
            ) {
                $(".PPRM202DCSearch.txtFromTenpoCD").val(
                    gdmz.SessionTenpoCD
                );
                $(".PPRM202DCSearch.txtToTenpoCD").val(gdmz.SessionTenpoCD);

                //20170905 ZHANGXIAOLEI UPD S
                // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
                // var arr =
                // {
                // 'txtTenpoCD' : $(".PPRM202DCSearch.txtFromTenpoCD").val()
                // };
                // var data =
                // {
                // request : arr
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblFromTenpo").val(result["data"]["strBusyoNM"]);
                // }
                //
                // var arr_to =
                // {
                // 'txtTenpoCD' : $(".PPRM202DCSearch.txtToTenpoCD").val()
                // };
                // var data_to =
                // {
                // request : arr_to
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblTenpo").val(result["data"]["strBusyoNM"]);
                // }
                // };
                // me.ajax.send(url, data_to, 0);
                // };
                // me.ajax.send(url, data, 0);
                $(".PPRM202DCSearch.lblFromTenpo").val(
                    me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                );
                $(".PPRM202DCSearch.lblTenpo").val(
                    me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                );
                //20170905 ZHANGXIAOLEI UPD E
            }

            $("#gbox_PPRM202DCSearch_spdList").hide();
            $("#gbox_PPRM202DCSearch_spdList1").hide();
            subFormInt3();
            $(".PPRM202DCSearch.txtFromTenpoCD").trigger("focus");
        }
    };

    //'**********************************************************************
    //'処 理 名：対象切替
    //'関 数 名：me.rdbTaisyo2_CheckedChanged
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：整備を選択した場合
    //'**********************************************************************
    me.rdbTaisyo2_CheckedChanged = function () {
        $(".PPRM202DCSearch.openHijimeOut").css("display", "none");
        $(".PPRM202DCSearch.ImgOpenFile").css("display", "none");
        $(".PPRM202DCSearch.openKinsyuIn").css("display", "none");

        if ($(".PPRM202DCSearch.rdbTaisyo2").is(":checked")) {
            $(".PPRM202DCSearch.txtFromTenpoCD").val("");
            $(".PPRM202DCSearch.txtToTenpoCD").val("");
            $(".PPRM202DCSearch.txtHJMFromDate").val("");
            $(".PPRM202DCSearch.txtHJMToDate").val("");
            $(".PPRM202DCSearch.txtHJMNo").val("");
            $(".PPRM202DCSearch.lblFromTenpo").val("");
            $(".PPRM202DCSearch.lblTenpo").val("");

            if (
                gdmz.SessionBusyoCD != 122 &&
                gdmz.SessionBusyoCD != 125
            ) {
                $(".PPRM202DCSearch.txtFromTenpoCD").val(
                    gdmz.SessionTenpoCD
                );
                $(".PPRM202DCSearch.txtToTenpoCD").val(gdmz.SessionTenpoCD);

                //20170905 ZHANGXIAOLEI UPD S
                // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
                // var arr =
                // {
                // 'txtTenpoCD' : $(".PPRM202DCSearch.txtFromTenpoCD").val()
                // };
                // var data =
                // {
                // request : arr
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblFromTenpo").val(result["data"]["strBusyoNM"]);
                // }		//【売上日/日締日to】为空
                //
                // var arr_to =
                // {
                // 'txtTenpoCD' : $(".PPRM202DCSearch.txtToTenpoCD").val()
                // };
                // var data_to =
                // {
                // request : arr_to
                // };
                // me.ajax.receive = function(result)
                // {
                // result = eval('(' + result + ')');
                //
                // if (result['result'] == false)
                // {
                // return;
                // }
                // else
                // {
                // $(".PPRM202DCSearch.lblTenpo").val(result["data"]["strBusyoNM"]);
                // }
                // };
                // me.ajax.send(url, data_to, 0);
                // };
                // me.ajax.send(url, data, 0);
                $(".PPRM202DCSearch.lblFromTenpo").val(
                    me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                );
                $(".PPRM202DCSearch.lblTenpo").val(
                    me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                );
                //20170905 ZHANGXIAOLEI UPD E
            }

            $("#gbox_PPRM202DCSearch_spdList").hide();
            $("#gbox_PPRM202DCSearch_spdList1").hide();
            subFormInt4();
            $(".PPRM202DCSearch.txtFromTenpoCD").trigger("focus");
        }
    };

    //'**********************************************************************
    //'処 理 名：選択を行う
    //'関 数 名：me.windowClose
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：データを選択
    //'**********************************************************************
    me.windowClose = function () {
        if ($(".PPRM202DCSearch.rdbTaisyo2").is(":checked")) {
            var id = $("#PPRM202DCSearch_spdList1").jqGrid(
                "getGridParam",
                "selrow"
            );
            if (id == null) {
                clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            } else {
                var rowData = $("#PPRM202DCSearch_spdList1").jqGrid(
                    "getRowData",
                    id
                );

                if ($.trim(rowData["TENPO_CD"]) != "") {
                    me.HJMNo = rowData["TENPO_CD"];
                }
                me.flg = 1;
                $(".PPRM202DCSearch.body").dialog("close");
            }
        } else {
            var id = $("#PPRM202DCSearch_spdList").jqGrid(
                "getGridParam",
                "selrow"
            );

            if (id == null) {
                clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            } else {
                var rowData = $("#PPRM202DCSearch_spdList").jqGrid(
                    "getRowData",
                    id
                );

                if ($.trim(rowData["TEN_HJM_NO"]) != "") {
                    me.HJMNo = rowData["TEN_HJM_NO"];
                }
                me.flg = 1;
                $(".PPRM202DCSearch.body").dialog("close");
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：戻るを行う
    //'関 数 名：me.windowClose2
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面閉じる
    //'**********************************************************************
    me.windowClose2 = function () {
        me.flg = 2;
        $(".PPRM202DCSearch.body").dialog("close");
    };

    //'**********************************************************************
    //'処 理 名：店舗コード(from)のblur
    //'関 数 名：me.txtFromTenpoCDBlur
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.txtFromTenpoCDBlur = function (objTenpoCD, objTenpoNM, FoT) {
        //20170905 ZHANGXIAOLEI UPD S
        // me.url = me.sys_id + '/' + me.id + '/' + 'FncGetBusyoNM';
        //
        // var arr =
        // {
        // 'txtTenpoCD' : $(".PPRM202DCSearch.txtFromTenpoCD").val()
        // };
        // me.data =
        // {
        // request : arr,
        // };
        // me.ajax.receive = function(result)
        // {
        // result = eval('(' + result + ')');
        //
        // if (result['result'] == false)
        // {
        // return;
        // }
        // else
        // {
        // $(".PPRM202DCSearch.lblFromTenpo").val(result["data"]["strBusyoNM"]);
        // }
        // };
        // me.ajax.send(me.url, me.data, 0);

        // 2017/09/08 CI UPD S
        //$(".PPRM202DCSearch.lblFromTenpo").val(me.FncGetBusyoNM($(".PPRM202DCSearch.txtFromTenpoCD").val()));
        try {
            var strCD = objTenpoCD.val();
            var TenpoNM = objTenpoNM.val();

            objTenpoNM.val("");

            if (strCD != "") {
                for (key in me.BusyoArr) {
                    if (strCD == me.BusyoArr[key]["TENPO_CD"]) {
                        objTenpoNM.val(me.BusyoArr[key]["BUSYO_NM"]);
                        break;
                    }
                }
            }

            if (FoT == "F") {
                if (TenpoNM != objTenpoNM.val()) {
                    $(".PPRM202DCSearch.txtToTenpoCD").trigger("focus");
                }
            } else {
                if (TenpoNM != objTenpoNM.val()) {
                    $(".PPRM202DCSearch.txtHJMFromDate").trigger("focus");
                }
            }
        } catch (e) {}

        // 2017/09/08 CI UPD E
        //20170905 ZHANGXIAOLEI UPD E
    };
    // 2017/09/08 CI DEL S
    //'**********************************************************************
    //'処 理 名：店舗コード(to)のblur
    //'関 数 名：me.txtToTenpoCDBlur
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    // me.txtToTenpoCDBlur = function()
    // {
    // //20170905 ZHANGXIAOLEI UPD S
    // // me.url = me.sys_id + '/' + me.id + '/' + 'FncGetBusyoNM';
    // //
    // // var arr =
    // // {
    // // 'txtTenpoCD' : $(".PPRM202DCSearch.txtToTenpoCD").val()
    // // };
    // // me.data =
    // // {
    // // request : arr,
    // // };
    // // me.ajax.receive = function(result)
    // // {
    // // result = eval('(' + result + ')');
    // //
    // // if (result['result'] == false)
    // // {
    // // return;
    // // }
    // // else
    // // {
    // // $(".PPRM202DCSearch.lblTenpo").val(result["data"]["strBusyoNM"]);
    // // }
    // // };
    // // me.ajax.send(me.url, me.data, 0);
    // $(".PPRM202DCSearch.lblTenpo").val(me.FncGetBusyoNM($(".PPRM202DCSearch.txtToTenpoCD").val()));
    // //20170905 ZHANGXIAOLEI UPD E
    // };
    // 2017/09/08 CI DEL E
    //'**********************************************************************
    //'処 理 名：画面初期化（メニューから）
    //'関 数 名：subFormInt1
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'**********************************************************************
    function subFormInt1() {
        //【店舗コードfrom】为空
        $(".PPRM202DCSearch.txtFromTenpoCD").val("");
        //【店舗コードto】为空
        $(".PPRM202DCSearch.txtToTenpoCD").val("");
        //2017/09/25 CI UPD S
        $(".PPRM202DCSearch.txtHJMFromDate").datepicker("setDate", -6);
        $(".PPRM202DCSearch.txtHJMToDate").datepicker("setDate", "null");
        //【売上日/日締日from】为空
        //$(".PPRM202DCSearch.txtHJMFromDate").val("");
        //【売上日/日締日to】为空
        //$(".PPRM202DCSearch.txtHJMToDate").val("");
        // 2017/09/25 CI UPD E
        //【日締№】为空
        $(".PPRM202DCSearch.txtHJMNo").val("");
        //【店舗名from】为空
        $(".PPRM202DCSearch.lblFromTenpo").val("");
        //【店舗名to】为空
        $(".PPRM202DCSearch.lblTenpo").val("");
        //20201119 WL UPD S
        //【検索店舗コードfrom】ボタン可用
        //$(".PPRM202DCSearch.btnFromTenpoSearch").attr("disabled", false);
        //【検索店舗コードto】ボタン可用
        //$(".PPRM202DCSearch.btnToTenpoSearch").attr("disabled", false);
        //【検索】ボタン可用
        //$(".PPRM202DCSearch.btnSearch").attr("disabled", false);
        $(".PPRM202DCSearch.btnFromTenpoSearch").button("enable");
        $(".PPRM202DCSearch.btnToTenpoSearch").button("enable");
        $(".PPRM202DCSearch.btnSearch").button("enable");
        //20201119 WL UPD E
        //【選択】ボタン非表示
        $(".PPRM202DCSearch.btnSelect").css("display", "none");
        if (me.strREFPRG != undefined) {
            //【戻る】ボタン表示
            $(".PPRM202DCSearch.btnClose").css("display", "block");
        } else {
            //【戻る】ボタン非表示
            $(".PPRM202DCSearch.btnClose").css("display", "none");
        }
        //jqgrid非表示
        $("#gbox_PPRM202DCSearch_spdList").hide();
        $("#gbox_PPRM202DCSearch_spdList1").hide();
    }

    //'**********************************************************************
    //'処 理 名：画面初期化（ダイアログ）
    //'関 数 名：subFormInt2
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化
    //'**********************************************************************
    function subFormInt2() {
        //20170905 ZHANGXIAOLEI UPD S
        // //ラベル
        // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
        // var arr =
        // {
        // 'txtTenpoCD' : $(".PPRM202DCSearch.txtFromTenpoCD").val()
        // };
        // var data =
        // {
        // request : arr
        // };
        // me.ajax.receive = function(result)
        // {
        // result = eval('(' + result + ')');
        //
        // if (result['result'] == false)
        // {
        // return;
        // }
        // else
        // {
        // $(".PPRM202DCSearch.lblFromTenpo").val(result["data"]["strBusyoNM"]);
        // }
        //
        // var arr_to =
        // {
        // 'txtTenpoCD' : $(".PPRM202DCSearch.txtToTenpoCD").val()
        // };
        // var data_to =
        // {
        // request : arr_to
        // };
        // me.ajax.receive = function(result)
        // {
        // result = eval('(' + result + ')');
        //
        // if (result['result'] == false)
        // {
        // return;
        // }
        // else
        // {
        // $(".PPRM202DCSearch.lblTenpo").val(result["data"]["strBusyoNM"]);
        // }
        // };
        // me.ajax.send(url, data_to, 0);
        // };
        // me.ajax.send(url, data, 0);
        $(".PPRM202DCSearch.lblFromTenpo").val(
            me.FncGetBusyoNM($(".PPRM202DCSearch.txtFromTenpoCD").val())
        );
        $(".PPRM202DCSearch.lblTenpo").val(
            me.FncGetBusyoNM($(".PPRM202DCSearch.txtToTenpoCD").val())
        );
        //20170905 ZHANGXIAOLEI UPD E
    }

    //'**********************************************************************
    //'処 理 名：画面初期化（事務の場合）
    //'関 数 名：subFormInt3
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化（事務の場合）
    //'**********************************************************************
    function subFormInt3() {
        //表示項目設定
        $(".PPRM202DCSearch.lblTitle2").html("日締日");
        $(".PPRM202DCSearch.tdlblHJM").css("display", "block");
        $(".PPRM202DCSearch.txtHJMNo").css("display", "block");
        //2017/09/27 CI INS S
        $(".PPRM202DCSearch.txtHJMFromDate").datepicker("setDate", -6);
        $(".PPRM202DCSearch.txtHJMToDate").datepicker("setDate", "null");
        //2017/09/27 CI INS E
    }

    //'**********************************************************************
    //'処 理 名：画面初期化（整備の場合）
    //'関 数 名：subFormInt4
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：画面初期化（整備の場合）
    //'**********************************************************************
    function subFormInt4() {
        //表示項目設定
        $(".PPRM202DCSearch.lblTitle2").html("売上日");
        $(".PPRM202DCSearch.tdlblHJM").css("display", "none");
        $(".PPRM202DCSearch.txtHJMNo").css("display", "none");
        //2017/09/27 CI INS S
        $(".PPRM202DCSearch.txtHJMFromDate").datepicker("setDate", -6);
        $(".PPRM202DCSearch.txtHJMToDate").datepicker("setDate", "null");
        //2017/09/27 CI INS E
    }

    //'**********************************************************************
    //'処 理 名：変更時スプレッドクリア
    //'関 数 名：spdClear
    //'引 数 　：なし
    //'戻 り 値：なし
    //'**********************************************************************
    function spdClear() {
        $("#gbox_PPRM202DCSearch_spdList").hide();
        $("#gbox_PPRM202DCSearch_spdList1").hide();

        $(".PPRM202DCSearch.openHijimeOut").css("display", "none");
        $(".PPRM202DCSearch.ImgOpenFile").css("display", "none");
        $(".PPRM202DCSearch.openKinsyuIn").css("display", "none");
    }

    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM202DCSearch = new PPRM.PPRM202DCSearch();
    o_PPRM_PPRM202DCSearch.load();
    o_PPRM_PPRM.PPRM202DCSearch = o_PPRM_PPRM202DCSearch;
});
