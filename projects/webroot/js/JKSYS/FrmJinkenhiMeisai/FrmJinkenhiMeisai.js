/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmJinkenhiMeisai");

JKSYS.FrmJinkenhiMeisai = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmJinkenhiMeisai";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    me.cboYM = "";
    me.allBusyoName = "";

    // ========== 変数 end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJinkenhiMeisai.btnExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiMeisai.btnFromSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiMeisai.btnToSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJinkenhiMeisai.dtpTaisyouYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========

    //Excelボタンクリック
    $(".FrmJinkenhiMeisai.btnExcel").click(function () {
        me.btnExcel_Click();
    });
    //部署検索ボタンクリック(From)
    $(".FrmJinkenhiMeisai.btnFromSearch").click(function () {
        me.btnKensaku_Click("F");
    });
    //部署検索ボタンクリック(To)
    $(".FrmJinkenhiMeisai.btnToSearch").click(function () {
        me.btnKensaku_Click("T");
    });
    //部署コード(From)フォーカス移動時
    $(".FrmJinkenhiMeisai.txtFromBusyoCD").blur(function (e) {
        me.txtBusyoCD_LostFocus(e, "F");
    });
    //部署コード(To)フォーカス移動時
    $(".FrmJinkenhiMeisai.txtToBusyoCD").blur(function (e) {
        me.txtBusyoCD_LostFocus(e, "T");
    });
    $(".FrmJinkenhiMeisai.dtpTaisyouYM").blur(function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmJinkenhiMeisai.dtpTaisyouYM")) ==
            false
        ) {
            $(".FrmJinkenhiMeisai.dtpTaisyouYM").val(me.cboYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmJinkenhiMeisai.dtpTaisyouYM").trigger("focus");
                    $(".FrmJinkenhiMeisai.dtpTaisyouYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmJinkenhiMeisai.dtpTaisyouYM").trigger("focus");
                        $(".FrmJinkenhiMeisai.dtpTaisyouYM").select();
                    }, 0);
                }
            }

            $(".FrmJinkenhiMeisai.btnExcel").button("disable");
        } else {
            $(".FrmJinkenhiMeisai.btnExcel").button("enable");
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.init_control = function () {
        base_init_control();
        me.FrmJinkenhiMeisai_Load();
    };
    //初期値設定
    me.FrmJinkenhiMeisai_Load = function () {
        //画面初期化
        me.Formit();
    };
    //画面初期化(画面起動時)
    me.Formit = function () {
        url = me.sys_id + "/" + me.id + "/" + "FrmJinkenhiMeisai_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmJinkenhiMeisai").ympicker("disable");
                $(".FrmJinkenhiMeisai").attr("disabled", true);
                $(".FrmJinkenhiMeisai button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //テキストボックス
            //部署コードFrom
            $(".FrmJinkenhiMeisai.txtFromBusyoCD").val("");
            //部署コードTo
            $(".FrmJinkenhiMeisai.txtToTenpoCD").val("");
            //ボタン
            $("button.FrmSyokusyubetuKamokuMente").button("enable");
            //ラベル
            //部署名From
            $(".FrmJinkenhiMeisai.lblFromBusyoNM").val("");
            //部署名To
            $(".FrmJinkenhiMeisai.lblToTenpo").val("");

            me.allBusyoName = result["data"]["GetBusyoMstValue"];
            //対象年月セット
            if (result["data"] && result["data"]["SYORI_YM"]) {
                me.cboYM = result["data"]["SYORI_YM"];
                $(".FrmJinkenhiMeisai.dtpTaisyouYM").val(me.cboYM);
                $(".FrmJinkenhiMeisai.dtpTaisyouYM").trigger("focus");
                $(".FrmJinkenhiMeisai.dtpTaisyouYM").select();
            }
        };
        me.ajax.send(url, "", 0);
    };
    //Excelボタンクリック
    me.btnExcel_Click = function () {
        var TaisyouYM = $(".FrmJinkenhiMeisai.dtpTaisyouYM")
            .val()
            .toString()
            .trimEnd();
        var url = me.sys_id + "/" + me.id + "/" + "btnExcel_Click";
        var data = {
            BusyoCDFrom: $(".FrmJinkenhiMeisai.txtFromBusyoCD")
                .val()
                .toString()
                .trimEnd(),
            BusyoCDTo: $(".FrmJinkenhiMeisai.txtToBusyoCD")
                .val()
                .toString()
                .trimEnd(),
            TaisyouYM: TaisyouYM,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0011");
                return;
            } else {
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        TaisyouYM.substring(0, 4) +
                            "/" +
                            TaisyouYM.substring(4, 6) +
                            "分 部署別人件費明細データが存在しません。"
                    );
                    return;
                } else if (result["error"] == "E9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "passworderror") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "パスワードが設定されていません。パスワードマスタメンテナンス画面より登録してください。"
                    );
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //部署コードフォーカス移動時
    me.txtBusyoCD_LostFocus = function (e, kbn) {
        var foundNM = undefined;
        var selCellVal = $.trim($(e.target).val());
        if (me.allBusyoName) {
            var foundNM_array = me.allBusyoName.filter(function (element) {
                return element["BUSYO_CD"] == me.clsComFnc.FncNv(selCellVal);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        if (kbn == "F") {
            $(".FrmJinkenhiMeisai.lblFromBusyoNM").val(
                foundNM ? foundNM["BUSYO_NM"] : ""
            );
            if ($.trim($(".FrmJinkenhiMeisai.txtToBusyoCD").val()) == "") {
                $(".FrmJinkenhiMeisai.lblToBusyoNM").val(
                    $.trim($(".FrmJinkenhiMeisai.lblFromBusyoNM").val())
                );
                $(".FrmJinkenhiMeisai.txtToBusyoCD").val(
                    $.trim($(".FrmJinkenhiMeisai.txtFromBusyoCD").val())
                );
            }
        }
        if (kbn == "T") {
            $(".FrmJinkenhiMeisai.lblToBusyoNM").val(
                foundNM ? foundNM["BUSYO_NM"] : ""
            );
        }
        $(".FrmJinkenhiEnt.lblBusyoNm").val(foundNM ? foundNM["BUSYO_NM"] : "");
    };
    //部署検索ボタンクリック
    me.btnKensaku_Click = function (kbn) {
        var $rootDiv = $(".FrmJinkenhiMeisai.JKSYS-content");
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYONM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $BUSYONM = $rootDiv.parent().find("#BUSYONM");

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 550 : 650,
            width: me.ratio === 1.5 ? 541 : 580,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $BUSYONM.hide();
            },
            close: function () {
                me.RtnCD = $RtnCD.html();
                me.searchedBusyoCD = $BUSYOCD.html();
                me.searchedBusyoNM = $BUSYONM.html();

                if (me.RtnCD == "1") {
                    if (kbn == "F") {
                        $(".FrmJinkenhiMeisai.txtFromBusyoCD").val(
                            me.searchedBusyoCD
                        );
                        $(".FrmJinkenhiMeisai.lblFromBusyoNM").val(
                            me.searchedBusyoNM
                        );
                    }
                    if (kbn == "T") {
                        $(".FrmJinkenhiMeisai.txtToBusyoCD").val(
                            me.searchedBusyoCD
                        );
                        $(".FrmJinkenhiMeisai.lblToBusyoNM").val(
                            me.searchedBusyoNM
                        );
                    }
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $BUSYONM.remove();
                $("#FrmBusyoSearchDialogDiv").remove();

                if (kbn == "F") {
                    $(".FrmJinkenhiMeisai.txtFromBusyoCD").trigger("focus");
                }
                if (kbn == "T") {
                    $(".FrmJinkenhiMeisai.txtToBusyoCD").trigger("focus");
                }
            },
        });

        var frmId = "FrmJKSYSBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);
            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_FrmJinkenhiMeisai_FrmJinkenhiMeisai = new JKSYS.FrmJinkenhiMeisai();
    o_FrmJinkenhiMeisai_FrmJinkenhiMeisai.load();
});
