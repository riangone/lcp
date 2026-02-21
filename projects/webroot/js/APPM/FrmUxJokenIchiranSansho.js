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
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmUxJokenIchiranSansho");

APPM.FrmUxJokenIchiranSansho = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmUxJokenIchiranSansho";
    me.sys_id = "APPM";
    me.mydata = new Array();

    me.grid_id = "#FrmUxJokenIchiranSansho_jqGrid";
    me.g_url = me.sys_id + "/" + me.id + "/" + "FncSearch";
    me.pager = "#FrmUxJokenIchiranSansho_pager";
    me.sidx = "";

    me.option = {
        rowNum: 30,
        rowList: [30, 40, 50],
        pagerpos: "center",
        recordpos: "right",
        multiselect: false,
        caption:
            "<span style='color:black;font-size:14px;display:inline-block;padding-top:2px;'>&nbsp;&nbsp;UX条件一覧</span>",
        multiselectWidth: 30,
        multiselect: true,
        rownumbers: false,
        scroll: false,
        pager: me.pager,
    };
    me.colModel = [
        {
            name: "UX_JOKEN_ID",
            label: "UX条件ID",
            index: "UX_JOKEN_ID",
            width: 100,
            align: "center",
        },
        {
            name: "MESSEJI_ID",
            label: "メッセージID",
            index: "MESSEJI_ID",
            width: 100,
            align: "center",
        },
        {
            name: "TAITORU",
            label: "メッセージタイトル",
            index: "TAITORU",
            width: 260,
            align: "center",
        },
        {
            name: "HYOJI_ST_YMD",
            label: "表示日from",
            index: "HYOJI_ST_YMD",
            width: 100,
            sortorder: "desc",
            align: "center",
        },
        {
            name: "HYOJI_ED_YMD",
            label: "表示日to",
            index: "HYOJI_ED_YMD",
            width: 100,
            align: "center",
        },
        {
            name: "TAISHO_KENSU",
            label: "対象件数",
            index: "TAISHO_KENSU",
            width: 80,
            align: "center",
        },
        {
            name: "RENKEI_NAME",
            label: "連携区分",
            index: "RENKEI_NAME",
            width: 80,
            align: "center",
        },
        {
            name: "RENKEI_KBN",
            label: "連携区分",
            index: "RENKEI_KBN",
            width: 80,
            hidden: true,
        },
        {
            name: "ZENKENSOFU_FLG",
            label: "全件送付",
            index: "ZENKENSOFU_FLG",
            width: 80,
            align: "center",
        },
        {
            name: "DEL_FLG",
            label: "削除フラグ",
            index: "DEL_FLG",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.txtHyoJI",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.btnReference",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.btnSign",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.btnUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmUxJokenIchiranSansho.btnDel",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //検索ボタンクリック
    $(".FrmUxJokenIchiranSansho.btnSearch").click(function () {
        btnSearch_Click();
    });

    //参照ボタンクリック
    $(".FrmUxJokenIchiranSansho.btnReference").click(function () {
        btnReference_Click();
    });

    //新規作成ボタンクリック
    $(".FrmUxJokenIchiranSansho.btnSign").click(function () {
        btnSign_Click();
    });

    //変更ボタンクリック
    $(".FrmUxJokenIchiranSansho.btnUpdate").click(function () {
        btnUpdate_Click();
    });

    //削除ボタンクリック
    $(".FrmUxJokenIchiranSansho.btnDel").click(function () {
        btnDel_Click();
    });

    // dialog
    $("#dialogsToroku").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 700,
        height: 680,
        open: function (event, ui) {},
        close: function () {
            $("#dialogsToroku").dialog("close");
            $("#dialogsToroku").html("");
            if (o_APPM_APPM.FrmUxJokenToroku.result == 1) {
                btnSearch_Click();
            }
        },
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();

        $(".FrmUxJokenIchiranSansho.txtHyoJI").trigger("focus");
        $("#cb_FrmUxJokenIchiranSansho_jqGrid").css("display", "none");

        var url = me.sys_id + "/" + me.id + "/" + "FncGetNaiBu";

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                for (i = 0; i < result["row"]; i++) {
                    html =
                        "<option value=" +
                        result["data"][i]["NAIBU_CD"] +
                        ">" +
                        result["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                    $(".FrmUxJokenIchiranSansho.ddlRenKeiKbn").append(html);
                }
                for (i = 0; i < result["del"]["row"]; i++) {
                    html =
                        "<option value=" +
                        result["del"]["data"][i]["NAIBU_CD"] +
                        ">" +
                        result["del"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                    $(".FrmUxJokenIchiranSansho.ddlDelFlg").append(html);
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            var url = me.sys_id + "/" + me.id + "/" + "FncAutoComplete";

            ajax.receive = function (result) {
                result = $.parseJSON(result);

                if (result["result"] == true) {
                    availableTags = new Array();
                    for (i = 0; i < result["row"]; i++) {
                        availableTags.push({
                            label:
                                "" +
                                result["data"][i]["MESSEJI_ID"] +
                                ":" +
                                result["data"][i]["TAITORU"] +
                                "",
                            value:
                                result["data"][i]["MESSEJI_ID"] +
                                ":" +
                                result["data"][i]["TAITORU"],
                        });
                    }

                    //メッセージのオートコンプリート
                    $(".FrmUxJokenIchiranSansho.txtMesseJi").autocomplete({
                        source: availableTags,
                    });
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }

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
            };
            ajax.send(url, "", 0);
        };

        ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：検索ボタンクリック
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：検索ボタンクリック
    //**********************************************************************
    function btnSearch_Click() {
        if (
            $(".FrmUxJokenIchiranSansho.txtHyoJI").val() != "" &&
            clsComFnc.CheckDate($(".FrmUxJokenIchiranSansho.txtHyoJI")) == false
        ) {
            clsComFnc.ObjFocus = $(".FrmUxJokenIchiranSansho.txtHyoJI");
            clsComFnc.FncMsgBox("W0022", "表示日", "「YYYY/MM/DD」");
            return;
        }

        //表示日
        var txtHyoJI = $(".FrmUxJokenIchiranSansho.txtHyoJI").val();
        //連携区分
        var ddlRenKeiKbn = $(".FrmUxJokenIchiranSansho.ddlRenKeiKbn").val();
        //全件送付
        var chkZenkensofuFlg = "";
        //削除表示
        var ddlDelFlg = $(".FrmUxJokenIchiranSansho.ddlDelFlg").val();
        //メッセージ
        var txtMesseJi = $(".FrmUxJokenIchiranSansho.txtMesseJi").val();

        if (
            $(".FrmUxJokenIchiranSansho.chkZenkensofuFlg").prop("checked") ==
            true
        ) {
            chkZenkensofuFlg = "01";
        } else {
            chkZenkensofuFlg = "";
        }

        var data = {
            txtHyoJI: txtHyoJI,
            chkZenkensofuFlg: chkZenkensofuFlg,
            txtMesseJi: txtMesseJi,
            ddlRenKeiKbn: ddlRenKeiKbn,
            ddlDelFlg: ddlDelFlg,
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
                clsComFnc.FncMsgBox("W9999", "該当データがありません。");
                return;
            } else {
                $(".ui-jqgrid-labels").unblock();
                $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid("setGridParam", {
                    onSelectRow: function () {
                        setButtonEnable();
                    },
                    onSelectAll: function () {
                        setButtonEnable();
                    },
                });
                //削除済の場合・・　灰色
                var rows = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
                    "getDataIDs"
                );

                for (i in rows) {
                    rowData = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
                        "getRowData",
                        rows[i]
                    );
                    if (clsComFnc.FncNz(rowData["DEL_FLG"]) == "01") {
                        $(me.grid_id + " tr#" + rows[i] + " td").css(
                            "background-color",
                            "gray"
                        );
                    }
                }
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    }

    function setButtonEnable() {
        var rowids = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (rowids.length > 0) {
            for (i = 0; i < rowids.length; i++) {
                var rowData = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
                    "getRowData",
                    rowids[i]
                );
                if (
                    rowData["DEL_FLG"] == "01" ||
                    rowData["RENKEI_KBN"] == "01"
                ) {
                    $(".FrmUxJokenIchiranSansho.btnUpdate").button("disable");
                    $(".FrmUxJokenIchiranSansho.btnDel").button("disable");
                    break;
                } else {
                    $(".FrmUxJokenIchiranSansho.btnUpdate").button("enable");
                    $(".FrmUxJokenIchiranSansho.btnDel").button("enable");
                }
            }
        } else if (rowids.length == 0) {
            $(".FrmUxJokenIchiranSansho.btnUpdate").button("enable");
            $(".FrmUxJokenIchiranSansho.btnDel").button("enable");
        }
    }

    //**********************************************************************
    //処 理 名：参照ボタンクリック
    //関 数 名：btnReference_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：参照ボタンクリック
    //**********************************************************************
    function btnReference_Click() {
        me.Mode = 0;
        openUxJokenToroku();
    }

    //**********************************************************************
    //処 理 名：新規作成ボタンクリック
    //関 数 名：btnSign_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：新規作成ボタンクリック
    //**********************************************************************
    function btnSign_Click() {
        me.Mode = 1;
        me.UxId = "";

        ajax.receive = function (result) {
            $("#dialogsToroku").dialog("option", "title", "UX条件登録");
            $("#dialogsToroku").dialog("open");
            $("#dialogsToroku").html(result);
        };
        var url = me.sys_id + "/" + "FrmUxJokenToroku" + "/" + "index";
        ajax.send(url, "", 0);
    }

    //**********************************************************************
    //処 理 名：変更ボタンクリック
    //関 数 名：btnUpdate_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：変更ボタンクリック
    //**********************************************************************
    function btnUpdate_Click() {
        me.Mode = 2;
        openUxJokenToroku();
    }

    //**********************************************************************
    //処 理 名：削除ボタンクリック
    //関 数 名：btnDel_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：削除ボタンクリック
    //**********************************************************************
    function btnDel_Click() {
        me.Mode = 3;
        openUxJokenToroku();
    }

    //**********************************************************************
    //処 理 名：UX条件登録画面を表示する
    //関 数 名：openUxJokenToroku
    //引    数：無し
    //戻 り 値：無し
    //処理説明：UX条件登録画面を表示する
    //**********************************************************************
    function openUxJokenToroku() {
        var selrows = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "selarrrow"
        );

        var id = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "selrow"
        );
        rowData = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid("getRowData", id);

        me.UxId = rowData["UX_JOKEN_ID"];
        var tableDataNum = $(me.grid_id).jqGrid("getGridParam", "records");
        if (selrows == undefined || selrows.length <= 0 || tableDataNum === 0) {
            //一覧リストで行選択されていない場合
            clsComFnc.FncMsgBox("W9999", "行を選択してください。");
            return;
        } else if (selrows.length > 1) {
            //一覧リストで複数行選択されている場合
            clsComFnc.FncMsgBox("W9999", "複数行は選択できません。");
            return;
        } else if (selrows.length == 1) {
            //一覧リストで1行選択されている場合
            var id = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#FrmUxJokenIchiranSansho_jqGrid").jqGrid(
                "getRowData",
                id
            );
            me.UxId = rowData["UX_JOKEN_ID"];

            ajax.receive = function (result) {
                $("#dialogsToroku").dialog("option", "title", "UX条件登録");
                $("#dialogsToroku").dialog("open");
                $("#dialogsToroku").html(result);
            };
            var url = me.sys_id + "/" + "FrmUxJokenToroku" + "/" + "index";
            ajax.send(url, "", 0);
        }
    }

    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    return me;
};

$(function () {
    var o_APPM_FrmUxJokenIchiranSansho = new APPM.FrmUxJokenIchiranSansho();
    o_APPM_FrmUxJokenIchiranSansho.load();
    o_APPM_APPM.FrmUxJokenIchiranSansho = o_APPM_FrmUxJokenIchiranSansho;
});
