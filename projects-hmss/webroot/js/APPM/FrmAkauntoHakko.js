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
Namespace.register("APPM.FrmAkauntoHakko");

APPM.FrmAkauntoHakko = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ヒロアプ管理";
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmAkauntoHakko";
    me.sys_id = "APPM";
    me.mydata = new Array();
    me.mode = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmAkauntoHakko.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmAkauntoHakko.btnIssue",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmAkauntoHakko.btnCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmAkauntoHakko.btnPDFoutput",
        type: "button",
        handle: "",
    });

    //jqgrid
    {
        me.colModel = [
            {
                name: "UPDNO",
                label: "登録番号",
                index: "UPDNO",
                sortable: false,
                height: 45,
                width: 135,
            },
            {
                name: "CARNO",
                label: "車台番号",
                index: "CARNO",
                width: 135,
            },
            {
                name: "CARNM",
                label: "通称車名",
                index: "CARNM",
                width: 177,
            },
        ];
    }

    //ShiftキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

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

        me.FrmAkauntoHakko_load();
    };

    $(".FrmAkauntoHakko.btnSearch").click(function () {
        me.btnSearch_Click();
    });

    $(".FrmAkauntoHakko.btnCancel").click(function () {
        me.btnCancel_Click();
    });

    $(".FrmAkauntoHakko.btnIssue").click(function () {
        me.btnIssue_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    me.FrmAkauntoHakko_load = function () {
        $(".FrmAkauntoHakko.confirmBox").css("display", "none");
        $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
    };

    //'**********************************************************************
    //'処 理 名：お客様No条件検索
    //'関 数 名：me.btnSearch_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnSearch_Click = function () {
        var txtCusNo = $(".FrmAkauntoHakko.txtCusNo").val();

        if (txtCusNo == "" || txtCusNo == null) {
            $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
            clsComFnc.FncMsgBox("W9999", "お客様Noを指定してください。");

            return;
        }

        if (txtCusNo.length > 10) {
            $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
            clsComFnc.FncMsgBox("W0022", "お客様No", "10");
            return;
        }

        var intRtn = 0;
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmAkauntoHakko.txtCusNo"),
            0,
            clsComFnc.INPUTTYPE.CHAR2
        );
        if (intRtn < 0) {
            $(".FrmAkauntoHakko.txtCusNo").css(clsComFnc.GC_COLOR_NORMAL);
            $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
            clsComFnc.FncMsgBox("W0021", "お客様No");
            return;
        }

        $(".FrmAkauntoHakko.confirmBox").css("display", "none");

        try {
            var url = me.sys_id + "/" + me.id + "/" + "FncGetSelect_Keiyakusya";

            var data = {
                txtCusNo: txtCusNo,
            };

            ajax.send(url, data, 0);

            ajax.receive = function (result) {
                result = JSON.parse(result);
                if (result["result"] == true) {
                    if (result["row"] > 0) {
                        me.mydata = result["data"];

                        $(".FrmAkauntoHakko.CSRNM1").val(me.mydata["CSRNM"]);
                        $(".FrmAkauntoHakko.HOM_TEL").val(me.mydata["HOM_TEL"]);
                        $(".FrmAkauntoHakko.MOB_TEL").val(me.mydata["MOB_TEL"]);
                        $(".FrmAkauntoHakko.CSRAD").val(me.mydata["CSRAD"]);

                        me.FrmAkauntoHakko_jqgrid();

                        $(".FrmAkauntoHakko.confirmBox").css(
                            "display",
                            "block"
                        );
                    } else {
                        $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
                        clsComFnc.FncMsgBox(
                            "W9999",
                            "対象のお客様データは存在しません。"
                        );
                        return;
                    }
                } else {
                    $(".FrmAkauntoHakko.txtCusNo").trigger("focus");
                    if (
                        result["data"] ==
                        "対象のお客様は既にアカウント情報を発行しています。"
                    ) {
                        clsComFnc.FncMsgBox("W9999", result["data"]);
                    } else {
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                    }
                    return;
                }
            };
        } catch (e) {
            console.log(e);
            clsComFnc.FncMsgBox("E9999", e.message);
        }
    };
    //'**********************************************************************
    //'処 理 名：[登録]ボタンクリック
    //'関 数 名：me.btnIssue_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnIssue_Click = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.FncIssueConfirm;
        clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
    };
    //'**********************************************************************
    //'処 理 名：ID/PWの発行
    //'関 数 名：me.FncIssueConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncIssueConfirm = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncIssue";

        var data = {
            txtCusNo: me.mydata["DLRCSRNO"],
            txtCusNm: me.mydata["CSRNM"],
        };

        ajax.send(url, data, 0);

        ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                window.open(result["reports"]);
                me.mode = "0";
                $("#FrmAkauntoIchiranSanshodialog").dialog("close");
            } else {
                me.mode = "1";
                $("#FrmAkauntoIchiranSanshodialog").dialog("close");
                if (
                    result["data"] == "他のユーザーが更新中です" ||
                    result["data"] ==
                        "対象のお客様は既にアカウント情報を発行しています。"
                ) {
                    clsComFnc.FncMsgBox("W9999", result["data"]);
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                }

                return;
            }
        };
    };
    //'**********************************************************************
    //'処 理 名：[キャンセル]ボタンクリック
    //'関 数 名：me.btnCancel_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnCancel_Click = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.FncCancelConfirm;
        clsComFnc.FncMsgBox("QY999", "キャンセルします。よろしいですか？");
    };
    //'**********************************************************************
    //'処 理 名：キャンセル
    //'関 数 名：me.FncCancelConfirm
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FncCancelConfirm = function () {
        me.mode = "1";
        $("#FrmAkauntoIchiranSanshodialog").dialog("close");
    };
    //'**********************************************************************
    //'処 理 名：jqgrid生成
    //'関 数 名：me.FrmAkauntoHakko_jqgrid
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.FrmAkauntoHakko_jqgrid = function () {
        me.subClearForm();
        $("#FrmAkauntoHakko_jqgrid").jqGrid({
            datatype: "local",
            caption:
                "<span style='color:black;font-size:14px;display:inline-block;padding-top:2px;'>&nbsp;&nbsp;車両情報</span>",
            height: 50,
            colModel: me.colModel,
        });
        me.subSpreadReShow();
    };
    me.subClearForm = function () {
        $("#FrmAkauntoHakko_jqgrid").jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：jqgrid初期処理
    //関 数 名：subSpreadReShow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：jqgrid初期処理
    //**********************************************************************
    me.subSpreadReShow = function () {
        var data = me.mydata["JQDATA"];

        for (var i = 0; i < data.length; i++) {
            $("#FrmAkauntoHakko_jqgrid").jqGrid("addRowData", i + 1, data[i]);
        }
    };

    return me;
};

$(function () {
    var o_APPM_FrmAkauntoHakko = new APPM.FrmAkauntoHakko();
    o_APPM_FrmAkauntoHakko.FrmAkauntoIchiranSansho =
        o_APPM_APPM_FrmAkauntoIchiranSansho;
    o_APPM_APPM_FrmAkauntoIchiranSansho.FrmAkauntoHakko =
        o_APPM_FrmAkauntoHakko;
    o_APPM_FrmAkauntoHakko.load();
});
