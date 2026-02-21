/**
 * 説明：
 *
 *
 * @author YINHUAIYU
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmAkauntoIchiranSansho");

APPM.FrmAkauntoIchiranSansho = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmAkauntoIchiranSansho";
    me.sys_id = "APPM";
    me.meTenpo = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmAkauntoIchiranSansho.btnSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmAkauntoIchiranSansho.btnIssue",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmAkauntoIchiranSansho.btnPDFoutput",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmAkauntoIchiranSansho.txtDTFrom",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmAkauntoIchiranSansho.txtDTTo",
        type: "datepicker",
        handle: "",
    });

    me.grid_id = "#FrmAkauntoIchiranSansho_jqgrid";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fnc" + me.id;
    me.pager = "#divFrmAkauntoIchiranSansho_pager";
    me.sidx = "";

    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: true,
        caption:
            "<span style='color:black;font-size:14px;display:inline-block;padding-top:2px;'>&nbsp;&nbsp;アカウント一覧</span>",
        rowNum: 30,
        rowList: [30, 40, 50],
        multiselectWidth: 30,
        rownumbers: false,
        scroll: false,
        autowidth: true,
        height: 270,
        pager: me.pager,
        datatype: "json",
    };
    me.colModel = [
        {
            name: "BUSYO_NM",
            label: "店舗",
            index: "BUSYO_NM",
            width: 85,
            align: "center",
        },
        {
            name: "DLRCSRNO",
            label: "お客様No",
            index: "DLRCSRNO",
            width: 90,
            align: "left",
        },
        {
            name: "ROGUIN_ID",
            label: "ID",
            index: "ROGUIN_ID",
            width: 90,
            align: "left",
        },
        {
            name: "CSRNM",
            label: "名前",
            index: "CSRNM",
            width: 130,
            align: "left",
        },
        {
            name: "CSRAD",
            label: "住所",
            index: "CSRAD",
            width: 180,
            align: "left",
        },
        {
            name: "CUS_HOM_TEL",
            label: "自宅TEL",
            index: "CUS_HOM_TEL",
            width: 120,
            align: "left",
        },
        {
            name: "MOB_TEL",
            label: "携帯TEL",
            index: "MOB_TEL",
            width: 120,
            align: "left",
        },
        {
            name: "HAKKO_YMD",
            label: "発行日",
            index: "HAKKO_YMD",
            width: 100,
            align: "center",
        },
        {
            name: "KARI_PASUWADO",
            label: "仮パスワード",
            index: "KARI_PASUWADO",
            width: 146,
            align: "center",
            hidden: true,
        },
    ];

    //ShiftキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    $(".FrmAkauntoIchiranSansho.btnSearch").click(function () {
        me.btnSearchClick();
    });

    $(".FrmAkauntoIchiranSansho.btnIssue").click(function () {
        me.btnIssueClick();
    });

    $(".FrmAkauntoIchiranSansho.btnPDFoutput").click(function () {
        me.btnPDFoutputClick();
    });

    // dialog
    $("#FrmAkauntoIchiranSanshodialog").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 520,
        height: 600,
        dialogClass: "RemoveCloseMark",
        open: function () {},
        close: function () {
            //clear the dialog
            $("#FrmAkauntoIchiranSanshodialog").html("");
            if (me.FrmAkauntoHakko.mode == "0") {
                $(".FrmAkauntoIchiranSansho.txtTenpo").val(me.meTenpo);
                var date = new Date();
                var seperator1 = "/";
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var strDate = date.getDate();
                if (month >= 1 && month <= 9) {
                    month = "0" + month;
                }
                if (strDate >= 0 && strDate <= 9) {
                    strDate = "0" + strDate;
                }
                var currentdate =
                    year + seperator1 + month + seperator1 + strDate;
                $(".FrmAkauntoIchiranSansho.txtDTFrom").val(currentdate);
                me.btnSearchClick();
            }
        },
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        var url = me.sys_id + "/" + me.id + "/fncGetTenpo";

        var data = {};

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (result["result"] == true) {
                var strData = result["data"];
                var strSelect = "";
                strSelect += '<option value=""></option>';

                for (var i = 0; i < strData.length; i++) {
                    strSelect +=
                        '<option value="' +
                        strData[i]["BUSYO_CD"] +
                        '">' +
                        strData[i]["BUSYO_RYKNM"] +
                        "</option>";
                }
                $(".FrmAkauntoIchiranSansho.txtTenpo").html(strSelect);

                me.meTenpo = result["meTenpo"][0]["KYOTN_CD"];
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
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
        };
        ajax.send(url, data, 0);
    };

    //20171225 lqs Del S
    // me.complete_fun = function(bErrorFlag)
    // {
    // if (bErrorFlag == 'nodata')
    // {
    // $('.ui-jqgrid-labels').block(
    // {
    // "overlayCSS" :
    // {
    // opacity : 0,
    // }
    // });
    //
    // clsComFnc.FncMsgBox("W9999", '該当データがありません。');
    // return;
    // }
    // else
    // {
    // $('.ui-jqgrid-labels').unblock();
    //
    // }
    // };
    //20171225 lqs Del E

    /*
	 **********************************************************************
	 '処 理 名：検索ボタンリック
	 '関 数 名：btnSearch_Click
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：検索ボタンリック
	 **********************************************************************
	 */
    me.btnSearchClick = function () {
        // 店舗
        var txtTenpo = $(".FrmAkauntoIchiranSansho.txtTenpo").val();
        // お客様名
        var txtCusNM = $(".FrmAkauntoIchiranSansho.txtCusNM").val();
        // お客様No
        var txtCusNo = $(".FrmAkauntoIchiranSansho.txtCusNo").val();
        // 発行日始
        var txtDTFrom = $(".FrmAkauntoIchiranSansho.txtDTFrom").val();
        // 発行日終
        var txtDTTo = $(".FrmAkauntoIchiranSansho.txtDTTo").val();

        var intRtn = 0;
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmAkauntoIchiranSansho.txtCusNo"),
            0,
            clsComFnc.INPUTTYPE.CHAR7
        );
        if (intRtn < 0) {
            $(".FrmAkauntoIchiranSansho.txtCusNo").css(
                clsComFnc.GC_COLOR_NORMAL
            );
            $(".FrmAkauntoIchiranSansho.txtCusNo").trigger("focus");
            clsComFnc.FncMsgBox("W0021", "お客様No");
            return;
        }

        //店舗チェック
        if (txtTenpo == "") {
            clsComFnc.FncMsgBox("W9999", "店舗を指定してください。 ");
            return;
        }
        // 発行日チェック
        // 発行日始
        if (txtDTFrom != "") {
            if (
                clsComFnc.CheckDate($(".FrmAkauntoIchiranSansho.txtDTFrom")) ==
                false
            ) {
                $(".FrmAkauntoIchiranSansho.txtDTFrom").trigger("focus");
                $(".FrmAkauntoIchiranSansho.txtDTFrom").select();
                clsComFnc.FncMsgBox("W0022", "発行日（自）", "「YYYY/MM/DD」");
                return;
            }
        } else {
            clsComFnc.FncMsgBox("W9999", "発行日（自）を指定してください。");
            return;
        }

        // 発行日終
        if (txtDTTo != "") {
            if (
                clsComFnc.CheckDate($(".FrmAkauntoIchiranSansho.txtDTTo")) ==
                false
            ) {
                $(".FrmAkauntoIchiranSansho.txtDTTo").trigger("focus");
                $(".FrmAkauntoIchiranSansho.txtDTTo").select();
                clsComFnc.FncMsgBox("W0022", "発行日（至）", "「YYYY/MM/DD」");
                return;
            }
        }

        txtDTFrom = txtDTFrom.replace(/\//g, "");
        txtDTTo = txtDTTo.replace(/\//g, "");

        if (txtDTFrom != "" && txtDTTo != "") {
            if (txtDTFrom > txtDTTo) {
                clsComFnc.FncMsgBox(
                    "W9999",
                    "発行日（至）は発行日（自）以降の日付を入力してください。"
                );
                return;
            }
        }

        var data = {
            txtTenpo: txtTenpo,
            txtDTFrom: txtDTFrom,
            txtDTTo: txtDTTo,
            txtCusNM: txtCusNM,
            txtCusNo: txtCusNo,
        };
        //20171225 lqs INS S
        var flg = true;

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "nodata") {
                if (flg != true) {
                    return;
                }
                flg = false;

                $(".ui-jqgrid-labels").block({
                    overlayCSS: {
                        opacity: 0,
                    },
                });

                clsComFnc.FncMsgBox("W9999", "該当データがありません。");
                return;
            } else {
                $(".ui-jqgrid-labels").unblock();
            }
        };
        //20171225 lqs INS E

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };
    /*
	 **********************************************************************
	 '処 理 名：新規発行ボタンリック
	 '関 数 名：btnIssueClick
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：アカウント発行画面表示
	 **********************************************************************
	 */
    me.btnIssueClick = function () {
        //新規画面
        ajax.receive = function (result) {
            $("#FrmAkauntoIchiranSanshodialog").dialog(
                "option",
                "title",
                "アカウント発行"
            );
            $("#FrmAkauntoIchiranSanshodialog").dialog("open");
            $("#FrmAkauntoIchiranSanshodialog").html(result);
        };
        var url = me.sys_id + "/" + "FrmAkauntoHakko" + "/" + "index";
        ajax.send(url, "", 0);
    };

    /*
	 **********************************************************************
	 '処 理 名：出力ボタンリック
	 '関 数 名：btnPDFoutputClick
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：PDF出力
	 **********************************************************************
	 */

    me.btnPDFoutputClick = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/btnPdf_Click";

            var arr = new Array();
            var jqGridRowIds = $(me.grid_id).jqGrid(
                "getGridParam",
                "selarrrow"
            );
            var tableDataNum = $(me.grid_id).jqGrid("getGridParam", "records");
            if (tableDataNum === 0 || jqGridRowIds.length <= 0) {
                clsComFnc.FncMsgBox("W9999", "行を選択してください");
                return;
            }

            for (key in jqGridRowIds) {
                arr[key] = $(me.grid_id).jqGrid(
                    "getRowData",
                    jqGridRowIds[key]
                );
            }

            var data = arr;

            ajax.receive = function (result) {
                result = $.parseJSON(result);
                if (result["result"] == true) {
                    window.open(result["reports"]);
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            ajax.send(url, data, 0);
        } catch (e) {
            console.log(e);
            clsComFnc.FncMsgBox("E9999", e.message);
        }
    };

    return me;
};

$(function () {
    var o_APPM_FrmAkauntoIchiranSansho = new APPM.FrmAkauntoIchiranSansho();
    o_APPM_FrmAkauntoIchiranSansho.load();
    o_APPM_APPM_FrmAkauntoIchiranSansho = o_APPM_FrmAkauntoIchiranSansho;
});
