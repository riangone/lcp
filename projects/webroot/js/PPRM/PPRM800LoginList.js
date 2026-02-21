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
 * 20201120           bug                          ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM800LoginList");

PPRM.PPRM800LoginList = function () {
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

    me.id = "PPRM800LoginList";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.reloadFlg = "";
    me.rowData = new Array();

    me.grid_id = "#PPRM800LoginList_gvLoginList";
    me.g_url = "PPRM/PPRM800LoginList/btnViewClick";
    me.pager = "";
    me.sidx = "";

    me.BusyoArr = new Array();

    me.option = {
        rowNum: 9999,
        recordpos: "left",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.colModel = [
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 205,
            sortable: false,
            align: "left",
        },
        {
            name: "SYAIN_NO",
            label: "ユーザID",
            index: "SYAIN_NO",
            width: 105,
            sortable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "ユーザ名",
            index: "SYAIN_NM",
            width: 155,
            sortable: false,
            align: "left",
        },
        {
            name: "FLG",
            label: "登録状態",
            index: "FLG",
            width: 70,
            sortable: false,
            align: "center",
        },
        {
            name: "PATTERN_NM",
            label: "パターン名",
            index: "PATTERN_NM",
            width: 150,
            sortable: false,
            align: "left",
        },
        {
            name: "KBN",
            label: "KBN",
            index: "KBN",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM800LoginList.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM800LoginList.btnView",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM800LoginList.btnEdit",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM800LoginList.btnDelete",
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

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.getAllBusyoNM();
    };

    $(".PPRM800LoginList.LvTextBusyoCD").on("blur", function () {
        me.setCallBackInit();
    });

    $(".PPRM800LoginList.btnSearch").click(function () {
        me.openBusyoSearch();
    });

    $(".PPRM800LoginList.LvTextUserID").change(function () {
        me.s();
    });

    $(".PPRM800LoginList.LvTextBusyoCD").change(function () {
        me.s();
    });

    $(".PPRM800LoginList.LvTextUserNM").change(function () {
        me.s();
    });

    $(".PPRM800LoginList.lvTaisyoku").click(function () {
        me.s();
    });

    //検索ボタンクリック
    $(".PPRM800LoginList.btnView").click(function () {
        me.btnView_Click();
    });

    //修正ボタンクリック
    $(".PPRM800LoginList.btnEdit").click(function () {
        me.openEditEntry();
    });

    //削除ボタン押下
    $(".PPRM800LoginList.btnDelete").click(function () {
        me.btnDelete_click();
    });

    // 2017/09/08 CI INS S
    $(".PPRM800LoginList.LvTextBusyoCD").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM800LoginList.LvTextUserID").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    $(".PPRM800LoginList.LvTextUserNM").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    // 2017/09/08 CI INS E

    //ページ初期化
    me.PPRM800LoginList_load = function () {
        //画面項目初期値セット
        $(".PPRM800LoginList.LvTextUserID").val("");
        $(".PPRM800LoginList.LvTextUserNM").val("");
        $(".PPRM800LoginList.LvTextBusyoCD").val("");
        $(".PPRM800LoginList.LvTextBusyoNM").val("");
        //20201120 WL UPD S
        //$(".PPRM800LoginList.btnSearch").prop("disabled", false);
        //$(".PPRM800LoginList.btnView").prop("disabled", false);
        $(".PPRM800LoginList.btnSearch").button("enable");
        $(".PPRM800LoginList.btnView").button("enable");
        //20201120 WL UPD E
        $(".PPRM800LoginList.pnlLoginList").css("display", "none");

        //フォーカス設定
        $(".PPRM800LoginList.LvTextUserID").trigger("focus");
    };

    //'**********************************************************************
    //'処 理 名：全部の部署コードと名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の部署コードと名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetALLBusyoNM";
        var selectObj = {
            request: {},
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                me.BusyoArr = result["data"];
            }
            me.PPRM800LoginList_load();
        };
        ajax.send(url, selectObj, 0);
    };

    //'**********************************************************************
    //'処 理 名：部署コードの名を取得
    //'関 数 名：me.FncGetBusyoNM
    //'引 数 　：部署コード
    //'戻 り 値：なし
    //'処理説明：部署コードの名を取得
    //'**********************************************************************
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
    //'***********************************************************************
    //'処 理 名：コールバック初期処理
    //'関 数 名：me.setCallBackInit
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：ClientCallBackの初期処理を行う
    //'***********************************************************************
    me.setCallBackInit = function () {
        $(".PPRM800LoginList.LvTextBusyoNM").val(
            me.FncGetBusyoNM($(".PPRM800LoginList.LvTextBusyoCD").val())
        );
        //20170922 lqs INS S
        $(".PPRM800LoginList.lvTaisyoku").trigger("focus");
        //20170922 lqs INS S
    };

    //'***********************************************************************
    //'処 理 名：レシーブデータ処理（コールバック用）
    //'関 数 名：me.ReceiveData
    //'引 数   ：result
    //'戻 り 値：なし
    //'処理説明：レシーブデータ処理（コールバック用）
    //'***********************************************************************
    me.ReceiveData = function (result) {
        result = result.split(unescape("%0D%0A"));

        if (result[0] == "0") {
            $(".PPRM800LoginList.LvTextBusyoNM").val(result[1]);

            if (result != null && result != "") {
                $(".PPRM800LoginList.lvTaisyoku").trigger("focus");
            } else {
                $(".PPRM800LoginList.btnSearch").trigger("focus");
            }
        }
    };

    //'***********************************************************************
    //'処 理 名：部署検索
    //'関 数 名：me.openBusyoSearch
    //'引 数 1 ：なし
    //'戻 り 値：BusyoCD(部署コード)
    //'戻 り 値：BusyoNM(部署名)
    //'処理説明：部署検索
    //'***********************************************************************
    me.openBusyoSearch = function () {
        me.url = "PPRM/PPRM702BusyoSearch";

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM702BusyoSearch.flg == 1) {
                    var BusyoCD = o_PPRM_PPRM.PPRM702BusyoSearch.busyocd;
                    var BusyoNM = o_PPRM_PPRM.PPRM702BusyoSearch.busyonm;

                    if (BusyoCD != "") {
                        $(".PPRM800LoginList.LvTextBusyoCD").val(BusyoCD);
                    }
                    if (BusyoNM != "") {
                        $(".PPRM800LoginList.LvTextBusyoNM").val(BusyoNM);
                    }

                    $(".PPRM800LoginList.pnlLoginList").css("display", "none");
                }
            }
            $("." + me.id + "." + "dialogs702").append(result);
            o_PPRM_PPRM.PPRM702BusyoSearch.before_close = before_close;
        };

        ajax.send(me.url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：me.btnView_Click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報を表示する
    //'**********************************************************************
    me.btnView_Click = function () {
        var LvTextUserID = $(".PPRM800LoginList.LvTextUserID").val();
        var LvTextUserNM = $(".PPRM800LoginList.LvTextUserNM").val();
        var LvTextBusyoCD = $(".PPRM800LoginList.LvTextBusyoCD").val();
        var lvTaisyoku = $(".PPRM800LoginList.lvTaisyoku").prop("checked");

        var data = {
            LvTextUserID: LvTextUserID,
            LvTextUserNM: LvTextUserNM,
            LvTextBusyoCD: LvTextBusyoCD,
            lvTaisyoku: lvTaisyoku,
        };

        if (me.reloadFlg == "") {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 772);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 220 : 260);
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 772);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 220 : 260);
        }
    };

    me.complete_fun = function (bErrorFlag) {
        me.reloadFlg = "1";

        //20201120 WL UPD S
        //$(".PPRM800LoginList.btnView").prop("disabled", false);
        $(".PPRM800LoginList.btnView").button("enable");
        //20201120 WL UPD E

        if (bErrorFlag != "nodata") {
            $(".PPRM800LoginList.pnlLoginList").css("display", "block");
        } else {
            $(".PPRM800LoginList.pnlLoginList").css("display", "none");
            $(".PPRM800LoginList.LvTextUserID").trigger("focus");
            clsComFnc.FncMsgBox("W0003_PPRM");
            return;
        }

        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function () {
                var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
                me.rowData = $(me.grid_id).jqGrid("getRowData", id);
                var KBN = me.rowData["KBN"];

                if (KBN == "1") {
                    //20201120 WL UPD S
                    //$(".PPRM800LoginList.btnDelete").prop("disabled", false);
                    $(".PPRM800LoginList.btnDelete").button("enable");
                    //20201120 WL UPD E
                } else {
                    //20201120 WL UPD S
                    //$(".PPRM800LoginList.btnDelete").prop("disabled", true);
                    $(".PPRM800LoginList.btnDelete").button("disable");
                    //20201120 WL UPD E
                }
            },
        });
    };

    //'***********************************************************************
    //'処 理 名：ログイン情報登録
    //'関 数 名：me.openEditEntry
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：ログイン情報登録
    //'***********************************************************************
    me.openEditEntry = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.rowData = $(me.grid_id).jqGrid("getRowData", id);

        if (id == null || id == undefined) {
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            return;
        } else {
            var lblUserID = me.rowData["SYAIN_NO"];
        }

        me.USER_ID = lblUserID;
        me.url = "PPRM/PPRM801LoginEntry";

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                USER_ID: me.USER_ID,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                me.btnView_Click();
            }
            $("." + me.id + "." + "dialogs801").append(result);
            o_PPRM_PPRM.PPRM801LoginEntry.before_close = before_close;
        };

        ajax.send(me.url, me.data, 0);
    };

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
            // clsComFnc.FncMsgBox("QY014_PPRM", "データ");
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
                //20171009 YIN INS S
                if (result["row"] > 0) {
                    me.FncDelete_LOGIN();
                } else {
                    clsComFnc.FncMsgBox("W0005_PPRM");
                    me.btnView_Click();
                    return;
                }
                //20171009 YIN INS E
                // me.FncDelete_LOGIN();
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                // me.btnView_Click();
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：ログインテーブル削除処理
    //'関 数 名：me.FncDelete_LOGIN
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ログインテーブルを削除する
    //'**********************************************************************
    me.FncDelete_LOGIN = function () {
        var lblUserID = me.rowData["SYAIN_NO"];

        var url = me.sys_id + "/" + me.id + "/fncDeleteLogin";
        var data = {
            lblUserID: lblUserID,
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

    me.s = function () {
        var t = $(".PPRM800LoginList.pnlLoginList");
        if (t != null) {
            $(".PPRM800LoginList.pnlLoginList").css("display", "none");
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM800LoginList = new PPRM.PPRM800LoginList();
    o_PPRM_PPRM800LoginList.load();
    o_PPRM_PPRM.PPRM800LoginList = o_PPRM_PPRM800LoginList;
});
