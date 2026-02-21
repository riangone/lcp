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
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmOshiraseJokenIchiranSansho");

APPM.FrmOshiraseJokenIchiranSansho = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmOshiraseJokenIchiranSansho";
    me.sys_id = "APPM";
    me.mydata = new Array();

    me.grid_id = "#FrmOshiraseJokenIchiranSansho_jqGrid";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fncGetOshiraseData";
    me.pager = "#FrmOshiraseJokenIchiranSansho_pager";
    me.sidx = "";

    me.option = {
        rowNum: 30,
        rowList: [30, 40, 50],
        recordpos: "right",
        caption:
            "<span style='color:black;font-size:14px;display:inline-block;padding-top:2px;'>&nbsp;&nbsp;お知らせ条件一覧</span>",
        multiselectWidth: 25,
        multiselect: true,
        rownumbers: false,
        scroll: false,
        pager: me.pager,
    };
    me.colModel = [
        {
            name: "OSHIRASEJOKEN_ID",
            label: "お知らせ条件ID",
            index: "OSHIRASEJOKEN_ID",
            width: 120,
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
            align: "left",
        },
        {
            name: "HYOJI_YMD",
            label: "表示日",
            index: "HYOJI_YMD",
            width: 100,
            sortorder: "desc",
            align: "center",
        },
        {
            name: "HYOJI_HM",
            label: "表示時間",
            index: "HYOJI_HM",
            width: 100,
            align: "center",
        },
        {
            name: "TAISHO_KENSU",
            label: "対象件数",
            index: "TAISHO_KENSU",
            width: 80,
            align: "right",
        },
        {
            name: "NAIBU_CD_MEISHO",
            label: "連携区分",
            index: "NAIBU_CD_MEISHO",
            width: 80,
            align: "center",
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
            width: 80,
            hidden: true,
            align: "center",
        },
        {
            name: "RENKEI_KBN",
            label: "RENKEI_KBN",
            index: "RENKEI_KBN",
            width: 80,
            hidden: true,
            align: "center",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.txtHyoJiTo",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.btnReference",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.btnSign",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.btnUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmOshiraseJokenIchiranSansho.btnDel",
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
    $(".FrmOshiraseJokenIchiranSansho.btnSearch").click(function () {
        btnSearch_Click();
    });

    //参照ボタンクリック
    $(".FrmOshiraseJokenIchiranSansho.btnReference").click(function () {
        btnReference_Click();
    });

    //新規作成ボタンクリック
    $(".FrmOshiraseJokenIchiranSansho.btnSign").click(function () {
        btnSign_Click();
    });

    //変更ボタンクリック
    $(".FrmOshiraseJokenIchiranSansho.btnUpdate").click(function () {
        btnUpdate_Click();
    });

    //削除ボタンクリック
    $(".FrmOshiraseJokenIchiranSansho.btnDel").click(function () {
        btnDel_Click();
    });

    // dialog
    $("#FrmOshiraseJokenIchiranSanshodialogs").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 700,
        height: 680,
        open: function () {},
        close: function () {
            $("#FrmOshiraseJokenIchiranSanshodialogs").html("");
            if (o_APPM_APPM.FrmOshiraseJokenToroku.backflg == "1") {
                if (
                    $.trim(
                        $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom").val()
                    ) != ""
                ) {
                    btnSearch_Click();
                }
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

        $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom").trigger("focus");

        var url = me.sys_id + "/" + me.id + "/" + "FncGetNaiBu";

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                for (i = 0; i < result["RenKeiKbn"]["row"]; i++) {
                    html =
                        "<option value=" +
                        result["RenKeiKbn"]["data"][i]["NAIBU_CD"] +
                        ">" +
                        result["RenKeiKbn"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                    $(".FrmOshiraseJokenIchiranSansho.ddlRenKeiKbn").append(
                        html
                    );
                }
                for (i = 0; i < result["DelFlg"]["row"]; i++) {
                    html =
                        "<option value=" +
                        result["DelFlg"]["data"][i]["NAIBU_CD"] +
                        ">" +
                        result["DelFlg"]["data"][i]["NAIBU_CD_MEISHO"] +
                        "</option>";
                    $(".FrmOshiraseJokenIchiranSansho.ddlDelFlg").append(html);
                }

                var url = me.sys_id + "/" + me.id + "/" + "fncGetMesseji";
                var data = {};
                ajax.receive = function (result) {
                    result = eval("(" + result + ")");

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
                        $(".txtMesseJi").autocomplete({
                            source: availableTags,
                        });
                        gdmz.common.jqgrid.init2(
                            me.grid_id,
                            me.g_url,
                            me.colModel,
                            me.pager,
                            me.sidx,
                            me.option
                        );
                        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
                        gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
                    } else {
                        me.subMsgOutput(-9, result["data"]);
                        return;
                    }
                };
                ajax.send(url, data, 0);
            } else {
                me.subMsgOutput(-9, result["data"]);
                return;
            }
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
        //表示日from
        var txtHyoJiFrom = $(
            ".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom"
        ).val();
        //表示日to
        var txtHyoJiTo = $(".FrmOshiraseJokenIchiranSansho.txtHyoJiTo").val();
        //連携区分
        var ddlRenKeiKbn = $(
            ".FrmOshiraseJokenIchiranSansho.ddlRenKeiKbn"
        ).val();
        //全件送付
        var chkZenkensofuFlg = "";
        //削除表示
        var ddlDelFlg = $(".FrmOshiraseJokenIchiranSansho.ddlDelFlg").val();
        //メッセージ
        var txtMesseJi = $(".FrmOshiraseJokenIchiranSansho.txtMesseJi").val();

        txtHyoJiFrom = txtHyoJiFrom.replace(/\//g, "");
        txtHyoJiTo = txtHyoJiTo.replace(/\//g, "");

        if (
            $(".FrmOshiraseJokenIchiranSansho.chkZenkensofuFlg").prop(
                "checked"
            ) == true
        ) {
            chkZenkensofuFlg = "01";
        } else {
            chkZenkensofuFlg = "";
        }
        // 表示日チェック
        // 表示日from
        if (txtHyoJiFrom != "") {
            if (
                clsComFnc.CheckDate(
                    $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom")
                ) == false
            ) {
                me.subMsgOutput(
                    -22,
                    "表示日（自）",
                    $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom"),
                    "「YYYY/MM/DD」"
                );
                return;
            }
        } else {
            $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom").trigger("focus");
            me.subMsgOutput(-99, "表示日(自)は必須入力です。");
            return;
        }
        // 表示日to
        if (txtHyoJiTo != "") {
            if (
                clsComFnc.CheckDate(
                    $(".FrmOshiraseJokenIchiranSansho.txtHyoJiTo")
                ) == false
            ) {
                me.subMsgOutput(
                    -22,
                    "表示日（至）",
                    $(".FrmOshiraseJokenIchiranSansho.txtHyoJiTo"),
                    "「YYYY/MM/DD」"
                );
                return;
            }
        }
        //日付期間のチェック
        if (txtHyoJiFrom != "" && txtHyoJiTo != "") {
            if (txtHyoJiFrom > txtHyoJiTo) {
                $(".FrmOshiraseJokenIchiranSansho.txtHyoJiFrom").trigger(
                    "focus"
                );
                me.subMsgOutput(
                    -99,
                    "表示日（至）は表示日（自）以降の日付を入力してください。"
                );
                return;
            }
        }

        var data = {
            txtHyoJiFrom: txtHyoJiFrom,
            txtHyoJiTo: txtHyoJiTo,
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
                me.subMsgOutput(-99, "該当データがありません");
                return;
            } else {
                $(".ui-jqgrid-labels").unblock();

                $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
                    "setGridParam",
                    {
                        onSelectRow: function () {
                            var selrows = $(
                                "#FrmOshiraseJokenIchiranSansho_jqGrid"
                            ).jqGrid("getGridParam", "selarrrow");

                            var delflag = false;

                            for (key in selrows) {
                                rowData = $(
                                    "#FrmOshiraseJokenIchiranSansho_jqGrid"
                                ).jqGrid("getRowData", selrows[key]);
                                if (
                                    clsComFnc.FncNz(rowData["DEL_FLG"]) ==
                                        "01" ||
                                    clsComFnc.FncNz(rowData["RENKEI_KBN"]) ==
                                        "01"
                                ) {
                                    $(
                                        ".FrmOshiraseJokenIchiranSansho.btnUpdate"
                                    ).button("disable");
                                    $(
                                        ".FrmOshiraseJokenIchiranSansho.btnDel"
                                    ).button("disable");
                                    delflag = true;
                                }
                            }
                            if (delflag == false) {
                                $(
                                    ".FrmOshiraseJokenIchiranSansho.btnUpdate"
                                ).button("enable");
                                $(
                                    ".FrmOshiraseJokenIchiranSansho.btnDel"
                                ).button("enable");
                            }
                        },
                        onSelectAll: function () {
                            var selrows = $(
                                "#FrmOshiraseJokenIchiranSansho_jqGrid"
                            ).jqGrid("getGridParam", "selarrrow");

                            var delflag = false;

                            for (key in selrows) {
                                rowData = $(
                                    "#FrmOshiraseJokenIchiranSansho_jqGrid"
                                ).jqGrid("getRowData", selrows[key]);
                                if (
                                    clsComFnc.FncNz(rowData["DEL_FLG"]) ==
                                        "01" ||
                                    clsComFnc.FncNz(rowData["RENKEI_KBN"]) ==
                                        "01"
                                ) {
                                    $(
                                        ".FrmOshiraseJokenIchiranSansho.btnUpdate"
                                    ).button("disable");
                                    $(
                                        ".FrmOshiraseJokenIchiranSansho.btnDel"
                                    ).button("disable");
                                    delflag = true;
                                }
                            }
                            if (delflag == false) {
                                $(
                                    ".FrmOshiraseJokenIchiranSansho.btnUpdate"
                                ).button("enable");
                                $(
                                    ".FrmOshiraseJokenIchiranSansho.btnDel"
                                ).button("enable");
                            }
                        },
                    }
                );
                //削除済の場合・・　灰色
                var selrows = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
                    "getDataIDs"
                );

                for (key in selrows) {
                    rowData = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
                        "getRowData",
                        selrows[key]
                    );
                    if (clsComFnc.FncNz(rowData["DEL_FLG"]) == "01") {
                        $(me.grid_id + " tr#" + selrows[key] + " td").css(
                            "background-color",
                            "gray"
                        );
                    }
                }
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
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
        openOshiraseJokenToroku();
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
        me.oshiraseId = "";

        ajax.receive = function (result) {
            $("#FrmOshiraseJokenIchiranSanshodialogs").dialog(
                "option",
                "title",
                "お知らせ条件登録"
            );
            $("#FrmOshiraseJokenIchiranSanshodialogs").dialog("open");
            $("#FrmOshiraseJokenIchiranSanshodialogs").html(result);
        };
        var url = me.sys_id + "/" + "FrmOshiraseJokenToroku" + "/" + "index";
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
        openOshiraseJokenToroku();
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
        openOshiraseJokenToroku();
    }

    //**********************************************************************
    //処 理 名：お知らせ条件登録画面を表示する
    //関 数 名：openOshiraseJokenToroku
    //引    数：無し
    //戻 り 値：無し
    //処理説明：お知らせ条件登録画面を表示する
    //**********************************************************************
    function openOshiraseJokenToroku() {
        var selrows = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "selarrrow"
        );

        var id = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "selrow"
        );
        rowData = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
            "getRowData",
            id
        );

        me.oshiraseId = rowData["OSHIRASEJOKEN_ID"];
        var tableDataNum = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
            "getGridParam",
            "records"
        );
        if (selrows == undefined || selrows.length <= 0 || tableDataNum === 0) {
            //一覧リストで行選択されていない場合
            me.subMsgOutput(-99, "行を選択してください");
            return;
        } else if (selrows.length > 1) {
            //一覧リストで複数行選択されている場合
            me.subMsgOutput(-99, "複数行は選択できません");
            return;
        } else if (selrows.length == 1) {
            //一覧リストで1行選択されている場合
            var id = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#FrmOshiraseJokenIchiranSansho_jqGrid").jqGrid(
                "getRowData",
                id
            );
            me.oshiraseId = rowData["OSHIRASEJOKEN_ID"];

            ajax.receive = function (result) {
                $("#FrmOshiraseJokenIchiranSanshodialogs").dialog(
                    "option",
                    "title",
                    "お知らせ条件登録"
                );
                $("#FrmOshiraseJokenIchiranSanshodialogs").dialog("open");
                $("#FrmOshiraseJokenIchiranSanshodialogs").html(result);
            };
            var url =
                me.sys_id + "/" + "FrmOshiraseJokenToroku" + "/" + "index";
            ajax.send(url, "", 0);
        }
    }

    me.subMsgOutput = function (intErrMsgNo, strErrMsg, formObj, strErrMsg2) {
        switch (intErrMsgNo) {
            case -1:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0001", strErrMsg);
                break;
            case -2:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0002", strErrMsg);
                break;
            case -3:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0003", strErrMsg);
                break;
            case -9:
                clsComFnc.FncMsgBox("E9999", strErrMsg);
                break;
            case -22:
                formObj.trigger("focus");
                clsComFnc.FncMsgBox("W0022", strErrMsg, strErrMsg2);
                break;
            case -99:
                clsComFnc.FncMsgBox("W9999", strErrMsg);
                break;
        }
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    return me;
};

$(function () {
    var o_APPM_FrmOshiraseJokenIchiranSansho =
        new APPM.FrmOshiraseJokenIchiranSansho();
    o_APPM_FrmOshiraseJokenIchiranSansho.load();
    o_APPM_APPM.FrmOshiraseJokenIchiranSansho =
        o_APPM_FrmOshiraseJokenIchiranSansho;
});
