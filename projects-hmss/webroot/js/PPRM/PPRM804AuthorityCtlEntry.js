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
 * 20170220           #ID                          XXXXXX                           GSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM804AuthorityCtlEntry");

PPRM.PPRM804AuthorityCtlEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    me.id = "PPRM804AuthorityCtlEntry";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.txtDispBusyoCD = "";
    me.txtDispBusyoNM = "";
    me.txtDispStartDate = "";
    me.txtDispEndDate = "";
    me.txtDispSyainNO = "";
    me.txtDispSyainNM = "";
    me.hidCreateDate = "";

    me.BusyoArr = new Array();

    //jqgrid
    {
        me.grid_id = "#PPRM804AuthorityCtlEntry_gvRights";
        me.grid_id1 = "#PPRM804AuthorityCtlEntry_gvProgramInfo";
        me.pager = "";
        me.sidx = "";

        me.option = {
            pagerpos: "center",
            recordpos: "right",
            multiselect: false,
            caption: "",
            rowNum: 30,
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
                label: "部署コード",
                index: "BUSYO_CD",
                width: 120,
                sortable: false,
                align: "left",
            },
            {
                name: "BUSYO_RYKNM",
                label: "部署名",
                index: "BUSYO_RYKNM",
                width: 150,
                sortable: false,
                align: "left",
            },
        ];

        me.colModel1 = [
            {
                name: "CTL_CHK",
                label: "選択",
                index: "CTL_CHK",
                width: 35,
                align: "center",
                formatter: "checkbox",
                sortable: false,
                editable: false,
                formatoptions: {
                    disabled: false,
                },
            },
            {
                name: "PRO_NO",
                label: "プログラム",
                index: "PRO_NO",
                width: 120,
                align: "left",
                sortable: false,
                hidden: true,
            },
            {
                name: "PRO_NM",
                label: "メニュー名",
                index: "PRO_NM",
                width: 160,
                sortable: false,
                align: "left",
            },
            {
                name: "HAUTH_ID",
                label: "操作",
                index: "HAUTH_ID",
                width: 120,
                align: "left",
                hidden: true,
            },
            {
                name: "HAUTH_NM",
                label: "操作",
                index: "HAUTH_NM",
                width: 140,
                sortable: false,
                align: "left",
            },
            {
                name: "CREATE_DATE",
                label: "作成日",
                index: "CREATE_DATE",
                width: 120,
                align: "left",
                hidden: true,
            },
        ];

        $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid({
            datatype: "local",
            height: me.ratio === 1.5 ? 145 : 250,
            width: 410,
            colModel: me.colModel1,
            rownumbers: true,
            viewrecords: true,
            sortorder: "desc",
            emptyRecordRow: false,
        });
    }

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //【新規追加】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnAdd",
        type: "button",
        handle: "",
    });
    //【登録】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnTouroku",
        type: "button",
        handle: "",
    });
    //【削除】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnDelete",
        type: "button",
        handle: "",
    });
    //【戻る】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnBack",
        type: "button",
        handle: "",
    });
    //【選択】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnSelect",
        type: "button",
        handle: "",
    });
    //【検索】ボタン
    me.controls.push({
        id: ".PPRM804AuthorityCtlEntry.btnSearch",
        type: "button",
        handle: "",
    });
    // ========== コントロール end ==========

    // ========== イベント start ==========
    //【新規追加】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnAdd").click(function () {
        me.btnAdd_click();
    });
    //【登録】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnTouroku").click(function () {
        me.btnTouroku_click();
    });
    //【削除】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnDelete").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.btnDelete_click();
        };
        clsComFnc.MsgBoxBtnFnc.No = function () {
            return;
        };
        clsComFnc.MessageBox(
            "" +
                $(".PPRM804AuthorityCtlEntry.txtDispSyainNM").val() +
                "の" +
                $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val() +
                "に対しての権限情報を削除します。よろしいですか？",
            clsComFnc.GSYSTEM_NAME,
            "YesNo",
            "Question"
        );
    });
    //【戻る】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnBack").click(function () {
        me.windowClose();
    });
    //【選択】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnSelect").click(function () {
        me.gvRights_SelectedIndexChanged();
    });
    //【検索】ボタン押下
    $(".PPRM804AuthorityCtlEntry.btnSearch").click(function () {
        me.openBusyoSearch();
    });
    //【部署コード】のblur
    $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").on("blur", function () {
        me.txtInpBusyoCDBlur();
    });
    //【全て】のchange
    $(".PPRM804AuthorityCtlEntry.chkAll").change(function () {
        me.chkAll_CheckedChanged();
    });
    // ========== イベント end ==========

    //引数を画面に表示する
    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.txtDispBusyoCD = requestdata["BCD"];
        me.txtDispBusyoNM = requestdata["BNM"];
        me.txtDispStartDate = requestdata["SDT"];
        me.txtDispEndDate = requestdata["EDT"];
        me.txtDispSyainNO = requestdata["SNO"];
        me.txtDispSyainNM = requestdata["SNM"];
    }

    localStorage.removeItem("requestdata");

    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM804AuthorityCtlEntry_load();
    };

    me.before_close = function () {};

    me.PPRM804AuthorityCtlEntry_load = function () {
        //弹窗功能
        $(".PPRM804AuthorityCtlEntry.body").dialog({
            autoOpen: false,
            width: 970,
            height: me.ratio === 1.5 ? 535 : 680,
            modal: true,
            title: "社員別権限修正",
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM804AuthorityCtlEntry.body").remove();
            },
        });
        $(".PPRM804AuthorityCtlEntry.body").dialog("open");
    };
    var base_load = me.load;
    //'**********************************************************************
    //'処 理 名：全部の店舗コードと店舗名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の店舗コードと店舗名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyoNM";
        var selectObj = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                me.BusyoArr = result["data"];
                Page_Clear();
            }
        };
        ajax.send(url, selectObj, 0);
    };

    me.load = function () {
        base_load();
        $(".PPRM804AuthorityCtlEntry.txtDispBusyoCD").val(me.txtDispBusyoCD);
        $(".PPRM804AuthorityCtlEntry.txtDispBusyoNM").val(me.txtDispBusyoNM);
        $(".PPRM804AuthorityCtlEntry.txtDispStartDate").val(
            me.txtDispStartDate
        );
        $(".PPRM804AuthorityCtlEntry.txtDispEndDate").val(me.txtDispEndDate);
        $(".PPRM804AuthorityCtlEntry.txtDispSyainNO").val(me.txtDispSyainNO);
        $(".PPRM804AuthorityCtlEntry.txtDispSyainNM").val(me.txtDispSyainNM);

        if ($(".PPRM804AuthorityCtlEntry.txtDispStartDate").val() != "") {
            $(".PPRM804AuthorityCtlEntry.txtDispStartDate").val(
                $(".PPRM804AuthorityCtlEntry.txtDispStartDate")
                    .val()
                    .substring(0, 4) +
                    "/" +
                    $(".PPRM804AuthorityCtlEntry.txtDispStartDate")
                        .val()
                        .substring(4, 6) +
                    "/" +
                    $(".PPRM804AuthorityCtlEntry.txtDispStartDate")
                        .val()
                        .substring(6, 8)
            );
        }
        if ($(".PPRM804AuthorityCtlEntry.txtDispEndDate").val() != "") {
            $(".PPRM804AuthorityCtlEntry.txtDispEndDate").val(
                $(".PPRM804AuthorityCtlEntry.txtDispEndDate")
                    .val()
                    .substring(0, 4) +
                    "/" +
                    $(".PPRM804AuthorityCtlEntry.txtDispEndDate")
                        .val()
                        .substring(4, 6) +
                    "/" +
                    $(".PPRM804AuthorityCtlEntry.txtDispEndDate")
                        .val()
                        .substring(6, 8)
            );
        }
        me.getAllBusyoNM();
    };

    me.FncGetBusyoNM = function (strCD) {
        try {
            if (strCD == "ZZZ") {
                return "全て";
            }
            if (strCD == "") {
                return "";
            }
            for (key in me.BusyoArr) {
                if (strCD == me.BusyoArr[key]["BUSYO_CD"]) {
                    return me.BusyoArr[key]["BUSYO_NM"];
                }
            }
            return "";
        } catch (e) {
            return "";
        }
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
        $(".PPRM804AuthorityCtlEntry.body").dialog("close");
        localStorage.removeItem("requestdata");
    };

    //'**********************************************************************
    //'処 理 名：新規追加を行う
    //'関 数 名：me.btnAdd_click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnAdd_click = function () {
        $(".PPRM804AuthorityCtlEntry.checkAll").css("display", "block");
        //20170919 lqs DEL S
        //$(".PPRM804AuthorityCtlEntry.btnSearch").css("background", "#16b1e9");
        //20170919 lqs DEL E
        $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid("clearGridData");

        var url = me.sys_id + "/" + me.id + "/" + "btnAddClick";
        var data = {};

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] > 0) {
                for (var i = 0; i < result["row"]; i++) {
                    $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                        "addRowData",
                        i + 1,
                        result["data"][i]
                    );
                    //１行目を選択状態にする
                    $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                }
            } else {
                clsComFnc.FncMsgBox(
                    "E0011_PPRM",
                    "部署やボタン別に権限管理するに設定されているプログラムが存在しません！"
                );
            }

            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val("");
            $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val("");
            $(".PPRM804AuthorityCtlEntry.chkAll").prop("checked", false);

            subAuthInfoVisible("block");

            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").prop(
                "disabled",
                false
            );
            $(".PPRM804AuthorityCtlEntry.btnSearch").removeClass(
                "ui-button-disabled"
            );
            $(".PPRM804AuthorityCtlEntry.btnSearch").removeClass(
                "ui-state-disabled"
            );
            $(".PPRM804AuthorityCtlEntry.btnSearch").removeAttr("disabled");
            $(".PPRM804AuthorityCtlEntry.btnDelete").css("display", "none");
            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").trigger("focus");
        };

        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：部署情報選択ボタンクリック
    //'関 数 名：me.gvRights_SelectedIndexChanged
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.gvRights_SelectedIndexChanged = function () {
        $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid("clearGridData");

        var id = $("#PPRM804AuthorityCtlEntry_gvRights").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM804AuthorityCtlEntry_gvRights").jqGrid(
            "getRowData",
            id
        );

        if (id == null || id == undefined || id == "") {
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            return;
        }

        var url =
            me.sys_id + "/" + me.id + "/" + "gvRightsSelectedIndexChanged";

        var arr = {
            txtDispSyainNO: me.txtDispSyainNO,
            BUSYO_CD: rowData["BUSYO_CD"],
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] > 0) {
                for (var i = 0; i < result["row"]; i++) {
                    $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                        "addRowData",
                        i + 1,
                        result["data"][i]
                    );
                    //１行目を選択状態にする
                    $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                }
            }

            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val(
                rowData["BUSYO_CD"]
            );
            $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val(
                rowData["BUSYO_RYKNM"]
            );
            $(".PPRM804AuthorityCtlEntry.chkAll").prop("checked", false);

            var url = me.sys_id + "/" + me.id + "/" + "fncMaxCheck";

            var arr = {
                txtDispSyainNO: me.txtDispSyainNO,
                txtInpBusyoCD: $(
                    ".PPRM804AuthorityCtlEntry.txtInpBusyoCD"
                ).val(),
            };
            var data = {
                request: arr,
            };

            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                } else {
                    me.hidCreateDate = clsComFnc.FncNz(
                        result["data"][0]["MAXUPDDT"]
                    );
                }
            };
            ajax.send(url, data, 0);
            subAuthInfoVisible("block");

            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").prop("disabled", true);
            $(".PPRM804AuthorityCtlEntry.btnSearch").prop(
                "disabled",
                "disabled"
            );
            $(".PPRM804AuthorityCtlEntry.checkAll").css("display", "block");
        };

        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：登録処理
    //'関 数 名：me.btnTouroku_click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：データを新規追加
    //'**********************************************************************
    me.btnTouroku_click = function () {
        if (fncInputChk() == false) {
            return;
        }
    };

    //'**********************************************************************
    //'処 理 名：検索を行う
    //'関 数 名：me.openBusyoSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：部署検索画面移動します
    //'**********************************************************************
    me.openBusyoSearch = function () {
        var url = me.sys_id + "/" + "PPRM702BusyoSearch" + "/" + "index";

        var arr = {};
        me.data = {
            request: arr,
        };
        ajax.receive = function (result) {
            $(".PPRM702_BusyoSearch_dialog").append(result);
            function before_close() {
                if (o_PPRM_PPRM.PPRM702BusyoSearch.flg == 1) {
                    var busyocd = o_PPRM_PPRM.PPRM702BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM702BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val(
                            busyocd
                        );
                    } else {
                    }
                    if (busyonm != "") {
                        $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val(
                            busyonm
                        );
                    } else {
                    }
                }
            }
            o_PPRM_PPRM.PPRM702BusyoSearch.before_close = before_close;
        };
        ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：削除処理
    //'関 数 名：me.btnDelete_click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：データを削除
    //'**********************************************************************
    me.btnDelete_click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncDeleteSQL";

        var arr = {
            txtDispSyainNO: me.txtDispSyainNO,
            txtInpBusyoCD: $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val(),
        };
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                clsComFnc.FncMsgBox("I0003_PPRM");
                Page_Clear();
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：【部署コード】のblur
    //'関 数 名：me.txtInpBusyoCDBlur
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.txtInpBusyoCDBlur = function () {
        $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val(
            me.FncGetBusyoNM($(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val())
        );
    };

    //'**********************************************************************
    //'処 理 名：全てにチェックを入れる・外す
    //'関 数 名：me.chkAll_CheckedChanged
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全てにチェックを入れる・外す
    //'**********************************************************************
    me.chkAll_CheckedChanged = function () {
        if ($(".PPRM804AuthorityCtlEntry.chkAll").prop("checked") == true) {
            var ids = $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                "getDataIDs"
            );
            for (var i = 0; i < ids.length; i++) {
                $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                    "setRowData",
                    ids[i],
                    {
                        CTL_CHK: true,
                    }
                );
            }
        } else {
            var ids = $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                "getDataIDs"
            );
            for (var i = 0; i < ids.length; i++) {
                $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                    "setRowData",
                    ids[i],
                    {
                        CTL_CHK: false,
                    }
                );
            }
        }
    };

    //'**********************************************************************
    //'処 理 名：画面項目クリア処理
    //'関 数 名：Page_Clear
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function Page_Clear() {
        //新規追加ボタンを表示する
        $(".PPRM804AuthorityCtlEntry.btnAdd").css("display", "block");
        $(".PPRM804AuthorityCtlEntry.chkAll").prop("checked", false);
        //権限情報テーブルを非表示にする
        subAuthInfoVisible("none");
        //登録されている部署情報を表示する
        var url = me.sys_id + "/" + me.id + "/" + "fncBusyoInfoSel";
        var arr = {
            txtDispSyainNO: me.txtDispSyainNO,
        };

        var data = {
            request: arr,
        };
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "nodata") {
                $(".PPRM804AuthorityCtlEntry.btnSelect").css(
                    "visibility",
                    "hidden"
                );
                return;
            } else {
                $(".PPRM804AuthorityCtlEntry.btnSelect").css(
                    "display",
                    "block"
                );
            }
            $(me.grid_id).jqGrid("setGridParam", {
                onSelectRow: function (_rowid, status) {
                    if (status) {
                        $(".PPRM804AuthorityCtlEntry.tblThirdMain").css(
                            "display",
                            "none"
                        );
                        $(".PPRM804AuthorityCtlEntry.btnTouroku").css(
                            "display",
                            "none"
                        );
                        $(".PPRM804AuthorityCtlEntry.btnDelete").css(
                            "display",
                            "none"
                        );
                        $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val("");
                        $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val("");
                        $(".PPRM804AuthorityCtlEntry.chkAll").prop(
                            "checked",
                            false
                        );
                    }
                },
            });
        };

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
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 350);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 200 : 310);
    }

    //'**********************************************************************
    //'処 理 名：権限情報表示設定
    //'関 数 名：subAuthInfoVisible
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function subAuthInfoVisible(blnVisible) {
        $(".PPRM804AuthorityCtlEntry.tblThirdMain").css("display", blnVisible);
        $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").css("display", blnVisible);
        $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").css(
            "display",
            blnVisible
        );
        $(".PPRM804AuthorityCtlEntry.btnSearch").css("display", blnVisible);
        $(".PPRM804AuthorityCtlEntry.btnTouroku").css("display", blnVisible);
        $(".PPRM804AuthorityCtlEntry.btnDelete").css("display", blnVisible);
    }

    //'**********************************************************************
    //'処 理 名：入力チェック
    //'関 数 名：fncInputChk
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function fncInputChk() {
        if ($(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val() == "") {
            if (
                $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").prop("disabled") ==
                false
            ) {
                clsComFnc.FncMsgBox("E0001_PPRM", "部署コード");
                return false;
            } else {
                clsComFnc.FncMsgBox(
                    "E0011_PPRM",
                    "部署情報が失われています。もう一度部署情報より対象の部署を選択して下さい。"
                );
                return false;
            }
        }
        if ($(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val() == "ZZZ") {
            $(".PPRM804AuthorityCtlEntry.textInpBusyoNM").val("全て");
            //20171011 lqs INS S
            fncMaxCheck(0);
            //20171011 lqs INS E
        } else {
            if (
                me.FncGetBusyoNM(
                    $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val()
                ) == ""
            ) {
                clsComFnc.FncMsgBox("E0011_PPRM", "該当の部署は存在しません");
                return false;
            } else {
                fncMaxCheck(0);
            }
        }
        return true;
    }

    //'**********************************************************************
    //'処 理 名：
    //'関 数 名：fncMaxCheck
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    function fncMaxCheck(iType) {
        var blnIns = false;
        var deployDataArr = new Array();
        var url = me.sys_id + "/" + me.id + "/" + "fncMaxCheck";

        var arr = {
            txtDispSyainNO: me.txtDispSyainNO,
            txtInpBusyoCD: $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").val(),
        };
        var data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            switch (iType) {
                case 0:
                    if (
                        $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").prop(
                            "disabled"
                        ) == false
                    ) {
                        if (clsComFnc.FncNz(result["data"][0]["CNT"]) > 0) {
                            $(
                                ".PPRM804AuthorityCtlEntry.txtInpBusyoCD"
                            ).trigger("focus");
                            clsComFnc.FncMsgBox("E0005_PPRM");
                            return;
                        }
                    } else {
                        if (
                            $(".PPRM804AuthorityCtlEntry.txtInpBusyoCD").prop(
                                "disabled"
                            ) == true
                        ) {
                            if (
                                clsComFnc.FncNz(result["data"][0]["CNT"]) == 0
                            ) {
                                clsComFnc.FncMsgBox("W0004_PPRM");
                                return;
                            }

                            if (
                                clsComFnc.FncNz(
                                    result["data"][0]["MAXUPDDT"]
                                ) != clsComFnc.FncNz(me.hidCreateDate)
                            ) {
                                clsComFnc.FncMsgBox("W0004_PPRM");
                                return;
                            }
                        }
                    }
                    break;
            }

            var ids = $("#PPRM804AuthorityCtlEntry_gvProgramInfo").jqGrid(
                "getDataIDs"
            );

            for (var i = 0; i < ids.length; i++) {
                var deployData = $(
                    "#PPRM804AuthorityCtlEntry_gvProgramInfo"
                ).jqGrid("getRowData", ids[i]);

                if (deployData["CTL_CHK"] == "Yes") {
                    blnIns = true;
                    deployDataArr.push(deployData);
                }
            }
            if (blnIns == false) {
                clsComFnc.FncMsgBox(
                    "E0011_PPRM",
                    "登録する対象が選択されていません。"
                );
                return;
            }
            blnIns = false;

            var url = me.sys_id + "/" + me.id + "/" + "btnTourokuClick";
            var arr = {
                txtDispSyainNO: me.txtDispSyainNO,
                txtInpBusyoCD: $(
                    ".PPRM804AuthorityCtlEntry.txtInpBusyoCD"
                ).val(),
                deployDataArr: deployDataArr,
            };

            var data = {
                request: arr,
            };

            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                } else {
                    clsComFnc.FncMsgBox("I0002_PPRM");
                    Page_Clear();
                }
            };

            ajax.send(url, data, 0);
        };

        ajax.send(url, data, 0);
    }

    //========== 関数 end ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM804AuthorityCtlEntry = new PPRM.PPRM804AuthorityCtlEntry();
    o_PPRM_PPRM804AuthorityCtlEntry.load();
    o_PPRM_PPRM.PPRM804AuthorityCtlEntry = o_PPRM_PPRM804AuthorityCtlEntry;
});
