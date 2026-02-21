/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * 20201120           bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる              WL
 * 20201120           bug                          ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM804AuthorityCtlList");

PPRM.PPRM804AuthorityCtlList = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();
    var ODR = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    me.id = "PPRM804AuthorityCtlList";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.reloadFlg = "";
    me.rowData = new Array();

    me.strProgramID = "";
    me.strTenpoKB = "";

    //20170908 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170908 ZHANGXIAOLEI INS E

    //jqgrid
    {
        me.grid_id = "#PPRM804AuthorityCtlList_gvInfo";
        me.g_url = "PPRM/PPRM804AuthorityCtlList/btnViewClick";
        me.pager = "";
        me.sidx = "";

        me.option = {
            pagerpos: "center",
            recordpos: "right",
            multiselect: false,
            caption: "",
            rowNum: 9999,
            multiselectWidth: 30,
            rownumbers: true,
            rowList: [10, 20, 30, 40, 50],
            loadui: "disable",
            scroll: false,
            pager: me.pager,
        };

        me.colModel = [
            {
                name: "BUSYO_CD",
                label: "部署ID",
                index: "BUSYO_CD",
                width: 120,
                align: "left",
                hidden: true,
            },
            {
                name: "BUSYO_NM",
                label: "部署名",
                index: "BUSYO_NM",
                width: 185,
                sortable: false,
                align: "left",
            },
            {
                name: "SYAIN_NO",
                label: "ユーザID",
                index: "SYAIN_NO",
                width: 140,
                sortable: false,
                align: "left",
            },
            {
                name: "SYAIN_NM",
                label: "ユーザ名",
                index: "SYAIN_NM",
                width: 180,
                sortable: false,
                align: "left",
            },

            {
                name: "FLG",
                label: "登録状態",
                index: "FLG",
                width: 80,
                sortable: false,
                align: "center",
            },
            {
                name: "KBN",
                label: "",
                index: "KBN",
                width: 160,
                align: "left",
                hidden: true,
            },
            {
                name: "Operation",
                label: " ",
                index: "Operation",
                width: 130,
                align: "center",
                hidden: true,
            },
            {
                name: "START_DATE",
                label: "配属期間START_DATE",
                index: "START_DATE",
                width: 130,
                align: "left",
                hidden: true,
            },
            {
                name: "END_DATE",
                label: "配属期間END_DATE",
                index: "END_DATE",
                width: 120,
                align: "left",
                hidden: true,
            },
        ];
    }

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //選択ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlList.btnSearch",
        type: "button",
        handle: "",
    });
    //戻るボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlList.btnView",
        type: "button",
        handle: "",
    });
    //削除ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlList.btnDelete",
        type: "button",
        handle: "",
    });
    //修正ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlList.btnEdit",
        type: "button",
        handle: "",
    });
    //'処理説明：ページ初期化
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //20170908 ZHANGXIAOLEI UPD S
        //me.PPRM804AuthorityCtlList_load();
        me.getAllBusyoNM();
        //20170908 ZHANGXIAOLEI UPD E
    };

    //20170908 ZHANGXIAOLEI INS S
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
            me.PPRM804AuthorityCtlList_load();
        };
        ajax.send(url, selectObj, 0);
    };

    me.FncGetBusyoNM = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }
            for (key in me.BusyoArr) {
                if (strCD == me.BusyoArr[key]["BUSYO_CD"]) {
                    return me.BusyoArr[key]["BUSYO_NM"];
                }
            }
        } catch (e) {
            return "";
        }
    };
    //20170908 ZHANGXIAOLEI INS E

    me.PPRM804AuthorityCtlList_load = function () {
        $(".PPRM804AuthorityCtlList.pnlLoginList").css("display", "none");
        $(".PPRM804AuthorityCtlList.btnEdit").css("display", "none");
        $(".PPRM804AuthorityCtlList.btnDelete").css("display", "none");
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 670);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 220 : 260);
        $(".PPRM804AuthorityCtlList.LvTextUserID").trigger("focus");
    };

    //【検索】ボタン
    $(".PPRM804AuthorityCtlList.btnSearch").click(function () {
        openFromTenpoSearch();
    });
    //【検索】(右)ボタン
    $(".PPRM804AuthorityCtlList.btnView").click(function () {
        me.btnView_Click();
    });
    //【修正】ボタン
    $(".PPRM804AuthorityCtlList.btnEdit").click(function () {
        openEditEntry();
    });
    //【削除】ボタン
    $(".PPRM804AuthorityCtlList.btnDelete").click(function () {
        me.btnDelete_click();
    });

    $(".PPRM804AuthorityCtlList.LvTextUserID").change(function () {
        spdClear();
    });

    $(".PPRM804AuthorityCtlList.LvTextUserNM").change(function () {
        spdClear();
    });

    $(".PPRM804AuthorityCtlList.LvTextBusyoCD").change(function () {
        spdClear();
    });

    $(".PPRM804AuthorityCtlList.rdo").change(function () {
        spdClear();
    });

    $(".PPRM804AuthorityCtlList.chkTaisyoku").change(function () {
        spdClear();
    });

    $(".PPRM804AuthorityCtlList.LvTextBusyoCD").on("blur", function () {
        me.LvTextBusyoCDBlur();
    });

    // 2017/09/11 CI INS S
    $(".PPRM804AuthorityCtlList.LvTextUserID").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM804AuthorityCtlList.LvTextUserNM").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    $(".PPRM804AuthorityCtlList.LvTextBusyoCD").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    // 2017/09/11 CI INS E

    //'***********************************************************************
    //'処 理 名：削除ボタン
    //'関 数 名：me.btnDelete_click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：メッセージ表示。Yesの場合削除処理を実行
    //'***********************************************************************
    me.btnDelete_click = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.rowData = $(me.grid_id).jqGrid("getRowData", id);

        if (id == null || id == undefined) {
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            return;
        } else {
            clsComFnc.MsgBoxBtnFnc.Yes = me.FncDeleteConfirm;
            //20171009 YIN UPD S
            // clsComFnc.FncMsgBox("QY014_PPRM", "");
            clsComFnc.FncMsgBox("QY003_PPRM");
            //20171009 YIN UPD E
        }
    };

    //'***********************************************************************
    //'処 理 名：Yesの場合削除処理を実行
    //'関 数 名：me.FncDeleteConfirm
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報を削除する
    //'***********************************************************************
    me.FncDeleteConfirm = function () {
        me.FncCheckSQL();
    };

    //'**********************************************************************
    //'処 理 名：ログインテーブル存在チェック
    //'関 数 名：me.FncCheckSQL
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ユーザID取得
    //'**********************************************************************
    me.FncCheckSQL = function () {
        var lblUserID = me.rowData["SYAIN_NO"];

        var url = me.sys_id + "/" + me.id + "/fncCheckSQL";
        var data = {
            lblUserID: lblUserID,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                if (result["row"] > 0) {
                    me.buttomClick_Confirm();
                } else {
                    clsComFnc.FncMsgBox("W0005_PPRM");
                    me.btnView_Click();
                    return;
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：選択行の削除処理
    //関 数 名：buttomClick_Confirm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：click押下,削除処理
    //**********************************************************************
    me.buttomClick_Confirm = function () {
        var id = $("#PPRM804AuthorityCtlList_gvInfo").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM804AuthorityCtlList_gvInfo").jqGrid(
            "getRowData",
            id
        );
        var url = me.sys_id + "/" + me.id + "/" + "btnDeleteClick";

        var data = {
            SYAIN_NO: rowData["SYAIN_NO"],
            busyonm: rowData["BUSYO_NM"],
            SYAIN_NM: rowData["SYAIN_NM"],
            FLG: rowData["FLG"],
        };
        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                clsComFnc.FncMsgBox("I0003_PPRM");
                me.btnView_Click();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnView_Click
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報を表示する
    //'**********************************************************************

    me.btnView_Click = function () {
        var LvTextUserID = $(".PPRM804AuthorityCtlList.LvTextUserID").val();
        var LvTextUserNM = $(".PPRM804AuthorityCtlList.LvTextUserNM").val();
        var LvTextBusyoCD = $(".PPRM804AuthorityCtlList.LvTextBusyoCD").val();
        var chkTaisyoku = "";
        var rdo = "";

        $(".rdo input").each(function () {
            if ($(this).prop("checked") == true) {
                rdo = $(this).val();
            }
        });

        if ($(".chkTaisyoku").prop("checked") == true) {
            chkTaisyoku = "true";
        }

        var data = {
            LvTextUserID: LvTextUserID,
            LvTextUserNM: LvTextUserNM,
            LvTextBusyoCD: LvTextBusyoCD,
            chkTaisyoku: chkTaisyoku,
            rdo: rdo,
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 670);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 220 : 260);
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
        if (bErrorFlag == "nodata") {
            $(".PPRM804AuthorityCtlList.LvTextUserID").trigger("focus");
            clsComFnc.FncMsgBox("W0003_PPRM");
            $(".PPRM804AuthorityCtlList.pnlLoginList").css("display", "none");
            $(".PPRM804AuthorityCtlList.btnEdit").css("display", "none");
            $(".PPRM804AuthorityCtlList.btnDelete").css("display", "none");

            return;
        } else {
            $(".PPRM804AuthorityCtlList.pnlLoginList").css("display", "block");
            $(".PPRM804AuthorityCtlList.btnEdit").css("display", "block");
            $(".PPRM804AuthorityCtlList.btnDelete").css("display", "block");
        }

        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
                if (rowData["KBN"] == "0") {
                    //20201120 WL UPD S
                    //$(".PPRM804AuthorityCtlList.btnDelete").prop("disabled", true);
                    $(".PPRM804AuthorityCtlList.btnDelete").button("disable");
                    //20201120 WL UPD E
                } else {
                    //20201120 WL UPD S
                    //$(".PPRM804AuthorityCtlList.btnDelete").prop("disabled", false);
                    $(".PPRM804AuthorityCtlList.btnDelete").button("enable");
                    //20201120 WL UPD E
                }
            },
        });
    };
    //**********************************************************************
    //処 理 名：社員別権限管理マスタ修正画面を表示する
    //関 数 名：openEditEntry
    //引    数：無し
    //戻 り 値：無し
    //処理説明：Enter押下,修正画面を呼び出す
    //**********************************************************************

    function openEditEntry() {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);

        if (id == null || id == undefined || id == "") {
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
        } else {
            var url =
                me.sys_id + "/" + "PPRM804AuthorityCtlEntry" + "/" + "index";
            localStorage.setItem(
                "requestdata",
                JSON.stringify({
                    BCD: rowData["BUSYO_CD"],
                    BNM: rowData["BUSYO_NM"],
                    SDT: rowData["START_DATE"],
                    EDT: rowData["END_DATE"],
                    SNO: rowData["SYAIN_NO"],
                    SNM: rowData["SYAIN_NM"],
                })
            );

            me.data = {};
            ajax.receive = function (result) {
                $("#PPRM804AuthorityCtlList_dialogs").html(result);
                function before_close() {
                    me.btnView_Click();
                }
                o_PPRM_PPRM.PPRM804AuthorityCtlEntry.before_close =
                    before_close;
            };
            ajax.send(url, me.data, 0);
        }
    }
    //'**********************************************************************
    //'処 理 名：変更時スプレッドクリア
    //'関 数 名：spdClear
    //'引 数 　：なし
    //'戻 り 値：なし
    //'**********************************************************************
    function spdClear() {
        $(".PPRM804AuthorityCtlList.pnlLoginList").css("display", "none");
        $(".PPRM804AuthorityCtlList.btnEdit").css("display", "none");
        $(".PPRM804AuthorityCtlList.btnDelete").css("display", "none");
    }

    //'**********************************************************************
    //'処 理 名：【部署コード】のblur
    //'関 数 名：me.LvTextBusyoCDBlur
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.LvTextBusyoCDBlur = function () {
        //20170908 ZHANGXIAOLEI UPD S
        // var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";
        //
        // var arr =
        // {
        // "LvTextBusyoCD" : $(".PPRM804AuthorityCtlList.LvTextBusyoCD").val(),
        // };
        //
        // var data =
        // {
        // request : arr
        // };
        //
        // ajax.receive = function(result)
        // {
        //
        // result = eval("(" + result + ")");
        //
        // if (result["records"] <= 0)
        // {
        // $(".PPRM804AuthorityCtlList.LvTextBusyoNM").val("");
        // }
        // else
        // {
        // $(".PPRM804AuthorityCtlList.LvTextBusyoNM").val(result["rows"][0]["cell"]["BUSYO_NM"]);
        // }
        // };
        //
        // ajax.send(url, data, 0);

        $(".PPRM804AuthorityCtlList.LvTextBusyoNM").val(
            me.FncGetBusyoNM($(".PPRM804AuthorityCtlList.LvTextBusyoCD").val())
        );
        $(".PPRM804AuthorityCtlList.chkTaisyoku").trigger("focus");
        //20170908 ZHANGXIAOLEI UPD E
    };
    //'**********************************************************************
    //'処 理 名：部署名取得
    //'関 数 名：openFromTenpoSearch
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：部署コードにて部署名を取得する
    //'**********************************************************************
    function openFromTenpoSearch() {
        // me.TKB = "1";
        me.url = "PPRM/PPRM702BusyoSearch";

        //20171010 lqs DEL S
        // localStorage.setItem('requestdata', JSON.stringify(
        // {
        // 'TKB' : me.TKB
        // }));
        //20171010 lqs DEL E

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM702BusyoSearch.flg == 1) {
                    //Else
                    var busyocd = o_PPRM_PPRM.PPRM702BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM702BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM804AuthorityCtlList.LvTextBusyoCD").val(
                            busyocd
                        );
                    } else {
                        $(".PPRM804AuthorityCtlList.LvTextBusyoCD").val("");
                    }
                    if (busyonm != "") {
                        $(".PPRM804AuthorityCtlList.LvTextBusyoNM").val(
                            busyonm
                        );
                    } else {
                        $(".PPRM804AuthorityCtlList.LvTextBusyoNM").val("");
                    }
                }
            }
            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM702BusyoSearch.before_close = before_close;
        };
        ajax.send(me.url, me.data, 0);
    }

    return me;
};

$(function () {
    var o_PPRM_PPRM804AuthorityCtlList = new PPRM.PPRM804AuthorityCtlList();
    o_PPRM_PPRM804AuthorityCtlList.load();
    o_PPRM_PPRM.PPRM804AuthorityCtlList = o_PPRM_PPRM804AuthorityCtlList;
});
