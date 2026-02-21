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
 * 20170503                                         变更                             WANGYING
 * 20170511                                         jqgrid变更                       LQS
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmMessejiIchiranSansho");

APPM.FrmMessejiIchiranSansho = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    //20170503 LQS UPD S
    //clsComFnc.GSYSTEM_NAME = "メッセージ管理";
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    //20170503 LQS UPD E
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmMessejiIchiranSansho";
    me.sys_id = "APPM";
    me.url = "";

    me.grid_id = "#FrmMessejiIchiranSansho_spdList";
    me.pager = "#FrmMessejiIchiranSansho_pager";
    me.g_url = me.sys_id + "/" + me.id + "/" + "msgSearch";
    me.sidx = "";

    me.first = true;

    me.option = {
        //20170602 YIN UPD S
        // rowNum : 30,
        // recordpos : "center",
        // //20170503 WANG DEL S
        // //multiselect : false,
        // //20170503 WANG DEL E
        // caption : "&nbsp;&nbsp;メッセージ一覧",
        // multiselectWidth : 30,
        // multiselect : true,
        // rownumbers : false,
        // scroll : 1,
        // pager : me.pager,
        pagerpos: "center",
        recordpos: "right",
        multiselect: true,
        caption:
            "<span style='color:black;font-size:14px;display:inline-block;padding-top:2px;'>&nbsp;&nbsp;メッセージ一覧</span>",
        rowNum: 30,
        rowList: [30, 40, 50],
        multiselectWidth: 30,
        rownumbers: false,
        autowidth: true,
        height: 270,
        datatype: "json",
        pager: me.pager,
        scroll: false,
    };

    me.colModel = [
        {
            name: "MESSEJI_ID",
            label: "メッセージID",
            index: "MESSEJI_ID",
            align: "center",
            width: 100,
        }, //
        {
            name: "MESSEJI_NAIYO",
            label: "メッセージ",
            index: "MESSEJI_NAIYO",
            align: "center",
            width: 150,
        }, //
        {
            name: "MESSEJI_RIYO_KIKAN_FROM",
            label: "利用期間from",
            index: "MESSEJI_RIYO_KIKAN_FROM",
            align: "center",
            width: 100,
        }, //
        {
            name: "MESSEJI_RIYO_KIKAN_TO",
            label: "利用期間to",
            index: "MESSEJI_RIYO_KIKAN_TO",
            align: "center",
            width: 100,
        }, //
        {
            name: "NAIYO_KBN",
            label: "内容区分",
            index: "NAIYO_KBN",
            align: "center",
            hidden: true,
        }, //
        {
            name: "NAIYO_NAME",
            label: "内容区分",
            index: "NAIYO_KBN_NAME",
            align: "center",
            width: 100,
        }, //
        {
            name: "KONTAKUTO_BOTAN_FLG",
            label: "コンタクト",
            index: "KONTAKUTO_BOTAN_FLG",
            align: "center",
            width: 80,
        }, //
        {
            name: "SHIJO_YOYAKU_BOTAN_FLG",
            label: "試乗",
            index: "SHIJO_YOYAKU_BOTAN_FLG",
            align: "center",
            width: 50,
        }, //
        {
            name: "NYUKO_YOYAKU_BOTAN_FLG",
            label: "入庫予約",
            index: "NYUKO_YOYAKU_BOTAN_FLG",
            align: "center",
            width: 70,
        }, //
        {
            name: "KIDOKU_KAKUNIN_FLG",
            label: "既読有無",
            index: "KIDOKU_KAKUNIN_FLG",
            align: "center",
            hidden: true,
        }, //
        {
            name: "KIDOKU_KAKUNIN_NAME",
            label: "既読有無",
            index: "KIDOKU_KAKUNIN_NAME",
            align: "center",
            width: 70,
        }, //
        {
            name: "RENKEI_KBN",
            label: "連携区分",
            index: "RENKEI_KBN",
            align: "center",
            hidden: true,
        }, //
        {
            name: "RENKEI_NAME",
            label: "連携区分",
            index: "RENKEI_NAME",
            align: "center",
            width: 70,
        }, //
        {
            name: "DEL_FLG",
            label: "削除フラグ",
            index: "RENKEI_KBN",
            align: "DEL_FLG",
            hidden: true,
        }, //
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.msgSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.btnCan",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.btnNew",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.btnEdit",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmMessejiIchiranSansho.txtDate",
        type: "datepicker",
        handle: "",
    });
    // ========== コントロール end ==========

    // ========== イベント start ==========
    //[検索]のclick
    $(".FrmMessejiIchiranSansho.msgSearch").click(function () {
        me.msgSearch();
    });
    //[参照]のclick
    $(".FrmMessejiIchiranSansho.btnCan").click(function () {
        me.btnCan();
    });
    //[新規登録]のclick
    $(".FrmMessejiIchiranSansho.btnNew").click(function () {
        me.btnNew();
    });
    //[変更]のclick
    $(".FrmMessejiIchiranSansho.btnEdit").click(function () {
        me.btnEdit();
    });
    //[削除]のclick
    $(".FrmMessejiIchiranSansho.btnDelete").click(function () {
        me.btnDelete();
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

        //20170503 WANG DEL S
        // gdmz.common.jqgrid.init(me.grid_id, me.g_url, me.colModel, me.pager, me.sidx, me.option);
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 980);
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
        //20170503 WANG DEL E
        $(".FrmMessejiIchiranSansho.txtDate").trigger("focus");

        // $("#cb_FrmMessejiIchiranSansho_spdList").css("display", "none");

        var o_url = me.sys_id + "/" + me.id + "/" + "fncSearchData";
        var o_data = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //20170504 WANG UPD S
            if (result["result"]) {
                for (var i = 0; i < result["content"]["row"]; i++) {
                    $(".FrmMessejiIchiranSansho.selContent").append(
                        "<option value='" +
                            result["content"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["content"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>"
                    );
                }
                for (var i = 0; i < result["kidoku"]["row"]; i++) {
                    $(".FrmMessejiIchiranSansho.sellianxie").append(
                        "<option value='" +
                            result["kidoku"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["kidoku"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>"
                    );
                }
                for (var i = 0; i < result["delete"]["row"]; i++) {
                    $(".FrmMessejiIchiranSansho.txtDelete").append(
                        "<option value='" +
                            result["delete"]["data"][i]["NAIBU_CD"] +
                            "'>" +
                            result["delete"]["data"][i]["NAIBU_CD_MEISHO"] +
                            "</option>"
                    );
                }
                $(".FrmMessejiIchiranSansho.txtDelete").val("00");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            // if (result['content']['result'])
            // {
            // for (var i = 0; i < result['content']['row']; i++)
            // {
            // $(".FrmMessejiIchiranSansho.selContent").append("<option>" + result['content']['data'][i]['NAIBU_CD_MEISHO'] + "</option>");
            // }
            // }
            // else
            // {
            // clsComFnc.FncMsgBox("E9999", result['content']['data']);
            // return;
            // }
            // if (result['kidoku']['result'])
            // {
            // for (var i = 0; i < result['kidoku']['row']; i++)
            // {
            // $(".FrmMessejiIchiranSansho.sellianxie").append("<option>" + result['kidoku']['data'][i]['NAIBU_CD_MEISHO'] + "</option>");
            // }
            // }
            // else
            // {
            // clsComFnc.FncMsgBox("E9999", result['kidoku']['data']);
            // return;
            // }
            //20170504 WANG UPD E

            var availableTags = [];
            var url = me.sys_id + "/" + me.id + "/" + "Search";
            var data = {};
            ajax.receive = function (result) {
                result = eval("(" + result + ")");
                //20170524 LQS INS S
                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                //20170524 LQS INS E
                //20170503 WANG UPD S

                //for (var i = 0; i < result["rows"].length; i++)
                for (var i = 0; i < result["row"]; i++) {
                    availableTags.push({
                        // label : '' + result["rows"][i]["cell"][0] + ":" + result["rows"][i]["cell"][1] + '',
                        label:
                            "" +
                            result["data"][i]["MESSEJI_ID"] +
                            ":" +
                            result["data"][i]["MESSEJI_NAIYO"] +
                            "",
                        // value : result["rows"][i]["cell"][0]
                        //20170505 WANG UPD S
                        //value : result["data"][i]["MESSEJI_ID"]
                        value:
                            result["data"][i]["MESSEJI_ID"] +
                            ":" +
                            result["data"][i]["MESSEJI_NAIYO"],
                        //20170505 WANG UPD E
                    });
                }
                //20170503 WANG UPD E

                //20170503 WANG INS S
                gdmz.common.jqgrid.init2(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 1000);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
                //20170503 WANG INS E
            };
            ajax.send(url, data, 0);

            $(".tags").autocomplete({
                source: availableTags,
            });
        };
        ajax.send(o_url, o_data, 0);
    };
    //'**********************************************************************
    //'処 理 名：[検索]ボタンクリック
    //'関 数 名：me.msgSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.msgSearch = function () {
        if ($(".FrmMessejiIchiranSansho.txtDate").val() == "") {
            clsComFnc.FncMsgBox("W9999", "利用日を指定してください");
            return;
        }
        if (
            clsComFnc.CheckDate($(".FrmMessejiIchiranSansho.txtDate")) == false
        ) {
            clsComFnc.FncMsgBox("W0022", "利用日", "「YYYY/MM/DD」");
            return;
        }

        var txtDate = $(".FrmMessejiIchiranSansho.txtDate").val();
        var message = $(".FrmMessejiIchiranSansho.tags").val().substring(0, 6);
        var txtcontent = $(".FrmMessejiIchiranSansho.selContent").val();
        var txtlianxie = $(".FrmMessejiIchiranSansho.sellianxie").val();
        var txtdelete = $(".FrmMessejiIchiranSansho.txtDelete").val();

        var arr = {
            txtDate: txtDate,
            MESSAGE: message,
            CONTENT: txtcontent,
            RENKEI: txtlianxie,
            DEL_FLG: txtdelete,
        };

        var data = {
            request: arr,
        };
        //20171225 lqs INS S
        var flg = true;
        //20171225 lqs INS E

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "nodata") {
                //20171225 lqs INS S
                if (flg != true) {
                    return;
                }
                flg = false;
                //20171225 lqs INS E
                $(".ui-jqgrid-labels").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });
                //20170515 LQS UPD S
                //clsComFnc.FncMsgBox("W0016");
                clsComFnc.FncMsgBox("W9999", "該当データがありません。");
                return;
                //20170515 LQS UPD E
            } else {
                $(".ui-jqgrid-labels").unblock();
                //20170511 LQS INS S
                $("#FrmMessejiIchiranSansho_spdList").jqGrid("setGridParam", {
                    onSelectRow: function () {
                        setButtonEnable();
                    },
                    onSelectAll: function () {
                        setButtonEnable();
                    },
                });
                //20170511 LQS INS E
                var ids = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
                    "getGridParam",
                    "records"
                );

                for (var i = 0; i < ids; i++) {
                    var rowData = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
                        "getRowData",
                        i
                    );
                    if (rowData["DEL_FLG"] == "01") {
                        $("#FrmMessejiIchiranSansho_spdList " + "#" + i)
                            .find("td")
                            .css("background-color", "gray");
                    }
                }
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };
    //20170511 LQS INS S
    function setButtonEnable() {
        var rowids = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (rowids.length > 0) {
            for (i = 0; i < rowids.length; i++) {
                var rowData = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
                    "getRowData",
                    rowids[i]
                );
                if (
                    rowData["DEL_FLG"] == "01" ||
                    rowData["RENKEI_KBN"] == "01"
                ) {
                    $(".FrmMessejiIchiranSansho.btnEdit").button("disable");
                    $(".FrmMessejiIchiranSansho.btnDelete").button("disable");
                    break;
                } else {
                    $(".FrmMessejiIchiranSansho.btnEdit").button("enable");
                    $(".FrmMessejiIchiranSansho.btnDelete").button("enable");
                }
            }
        } else if (rowids.length == 0) {
            $(".FrmMessejiIchiranSansho.btnEdit").button("enable");
            $(".FrmMessejiIchiranSansho.btnDelete").button("enable");
        }
    }

    //20170511 LQS INS E

    //'**********************************************************************
    //'処 理 名：[参照]ボタンクリック
    //'関 数 名：me.btnCan
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnCan = function () {
        var rows = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selarrrow"
        );

        var id = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selrow"
        );
        rowData = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getRowData",
            id
        );
        var tableDataNum = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "records"
        );
        if (rows == undefined || tableDataNum === 0) {
            clsComFnc.FncMsgBox("W9999", "行を選択してください。");
            return;
        } else {
            if (rows.length <= 0) {
                clsComFnc.FncMsgBox("W9999", "行を選択してください");
                return;
            }
            if (rows.length > 1) {
                clsComFnc.FncMsgBox("W9999", "複数行は選択できません");
                return;
            }
        }

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "0",
                MESSEJI_ID: rowData["MESSEJI_ID"],
            })
        );

        var url = me.sys_id + "/" + "FrmMessejiToroku";

        var arr = {};
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            $(".FrmMToroku_dialog").append(result);
            if (o_APPM_APPM.FrmMessejiToroku.flag == false) {
                clsComFnc.FncMsgBox("W9999", "パラメータが不正です");
            }
            function before_close() {}
            o_APPM_APPM.FrmMessejiToroku.before_close = before_close;
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[新規作成]ボタンクリック
    //'関 数 名：me.btnNew
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnNew = function () {
        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "1",
                MESSEJI_ID: "",
            })
        );

        var url = me.sys_id + "/" + "FrmMessejiToroku";

        var arr = {};
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            $(".FrmMToroku_dialog").append(result);

            if (o_APPM_APPM.FrmMessejiToroku.flag == false) {
                clsComFnc.FncMsgBox("W9999", "パラメータが不正です");
            }

            function before_close() {
                if (
                    o_APPM_APPM.FrmMessejiToroku.search == true &&
                    $(".FrmMessejiIchiranSansho.txtDate").val() != ""
                ) {
                    me.msgSearch();
                }
            }
            o_APPM_APPM.FrmMessejiToroku.before_close = before_close;
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[変更]ボタンクリック
    //'関 数 名：me.btnEdit
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnEdit = function () {
        var rows = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selarrrow"
        );

        var id = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selrow"
        );
        rowData = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getRowData",
            id
        );
        var tableDataNum = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "records"
        );
        if (rows == undefined || tableDataNum === 0) {
            clsComFnc.FncMsgBox("W9999", "行を選択してください");
            return;
        } else {
            if (rows.length <= 0) {
                clsComFnc.FncMsgBox("W9999", "行を選択してください");
                return;
            }
            if (rows.length > 1) {
                clsComFnc.FncMsgBox("W9999", "複数行は選択できません");
                return;
            }
        }

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "2",
                MESSEJI_ID: rowData["MESSEJI_ID"],
            })
        );

        var url = me.sys_id + "/" + "FrmMessejiToroku";

        var arr = {};
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            $(".FrmMToroku_dialog").append(result);
            if (o_APPM_APPM.FrmMessejiToroku.flag == false) {
                clsComFnc.FncMsgBox("W9999", "パラメータが不正です");
            }
            function before_close() {
                if (
                    o_APPM_APPM.FrmMessejiToroku.search == true &&
                    $(".FrmMessejiIchiranSansho.txtDate").val() != ""
                ) {
                    me.msgSearch();
                }
            }
            o_APPM_APPM.FrmMessejiToroku.before_close = before_close;
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：[削除]ボタンクリック
    //'関 数 名：me.btnDelete
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnDelete = function () {
        var rows = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selarrrow"
        );

        var id = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "selrow"
        );
        rowData = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getRowData",
            id
        );
        var tableDataNum = $("#FrmMessejiIchiranSansho_spdList").jqGrid(
            "getGridParam",
            "records"
        );
        if (rows == undefined || tableDataNum === 0) {
            clsComFnc.FncMsgBox("W9999", "行を選択してください。");
            return;
        } else {
            if (rows.length <= 0) {
                clsComFnc.FncMsgBox("W9999", "行を選択してください");
                return;
            }
            if (rows.length > 1) {
                clsComFnc.FncMsgBox("W9999", "複数行は選択できません");
                return;
            }
        }

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "3",
                MESSEJI_ID: rowData["MESSEJI_ID"],
            })
        );

        var url = me.sys_id + "/" + "FrmMessejiToroku";

        var arr = {};
        var data = {
            request: arr,
        };
        ajax.receive = function (result) {
            $(".FrmMToroku_dialog").append(result);
            if (o_APPM_APPM.FrmMessejiToroku.flag == false) {
                clsComFnc.FncMsgBox("W9999", "パラメータが不正です");
            }
            function before_close() {
                if (
                    o_APPM_APPM.FrmMessejiToroku.search == true &&
                    $(".FrmMessejiIchiranSansho.txtDate").val() != ""
                ) {
                    me.msgSearch();
                }
            }
            o_APPM_APPM.FrmMessejiToroku.before_close = before_close;
        };
        ajax.send(url, data, 0);
    };
    // ========== 関数 end ==========

    return me;
};

$(function () {
    o_APPM_FrmMessejiIchiranSansho = new APPM.FrmMessejiIchiranSansho();
    o_APPM_FrmMessejiIchiranSansho.load();
    o_APPM_APPM.FrmMessejiIchiranSansho = o_APPM_FrmMessejiIchiranSansho;
});
