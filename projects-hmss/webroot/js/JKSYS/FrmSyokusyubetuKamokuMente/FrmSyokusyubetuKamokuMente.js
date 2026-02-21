Namespace.register("JKSYS.FrmSyokusyubetuKamokuMente");

JKSYS.FrmSyokusyubetuKamokuMente = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "JKSYS/FrmSyokusyubetuKamokuMente";
    me.grid_id = "#FrmSyokusyubetuKamokuMente_sprList";

    me.option = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "KOUMK_NO",
            label: "項目No",
            index: "KOUMK_NO",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
            hidden: true,
        },
        {
            name: "KUBUN_NM",
            label: "項目",
            index: "KUBUN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "HIMOK_CD",
            label: "費目コード",
            index: "HIMOK_CD",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "KAMOK_NM",
            label: "名称",
            index: "KAMOK_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYOKUSYU_CD",
            label: "コード",
            index: "SYOKUSYU_CD",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "MEISYOU",
            label: "名称",
            index: "MEISYOU",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "Col_CREATE_DATE",
            label: "Col_CREATE_DATE",
            index: "Col_CREATE_DATE",
            width: 200,
            sortable: false,
            editable: false,
            hidden: true,
            align: "left",
        },
        {
            name: "Col_CRE_SYA_CD",
            label: "Col_CRE_SYA_CD",
            index: "Col_CRE_SYA_CD",
            width: 200,
            sortable: false,
            editable: false,
            hidden: true,
            align: "left",
        },
        {
            name: "Col_CRE_PRG_ID",
            label: "Col_CRE_PRG_ID",
            index: "Col_CRE_PRG_ID",
            width: 200,
            sortable: false,
            editable: false,
            hidden: true,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyokusyubetuKamokuMente.cmdSel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSyokusyubetuKamokuMente.cmdCan",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokusyubetuKamokuMente.cmdReg",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokusyubetuKamokuMente.cmdDel",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========

    // '**********************************************************************
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmSyokusyubetuKamokuMente.cmdSel").click(function () {
        me.cmdSel_Click();
    });
    $(".FrmSyokusyubetuKamokuMente.cmdCan").click(function () {
        me.cmdCan_Click();
    });
    $(".FrmSyokusyubetuKamokuMente.cmdReg").click(function () {
        me.cmdReg_Click();
    });
    $(".FrmSyokusyubetuKamokuMente.cmdDel").click(function () {
        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }

        //確認メッセージ
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdDel_Click();
        };

        me.clsComFnc.FncMsgBox("QY004");
    });
    $(".FrmSyokusyubetuKamokuMente.txtKaCd").blur(function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is(".FrmSyokusyubetuKamokuMente") ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.txtKaCd_Validating();
            }
        } else if (
            !e.relatedTarget ||
            $(e.relatedTarget).is(".FrmSyokusyubetuKamokuMente")
        ) {
            me.txtKaCd_Validating();
        }
    });

    $(".FrmSyokusyubetuKamokuMente.txtHiCd").blur(function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is(".FrmSyokusyubetuKamokuMente") ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                me.txtKaCd_Validating();
            }
        } else if (
            !e.relatedTarget ||
            $(e.relatedTarget).is(".FrmSyokusyubetuKamokuMente")
        ) {
            me.txtKaCd_Validating();
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.frmSyokusyubetuKamokuMente_Load();
    };
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.frmSyokusyubetuKamokuMente_Load = function () {
        //初期処理
        //画面項目ｸﾘｱ
        me.subClearForm();

        //ｽﾌﾟﾚｯﾄﾞの初期設定
        var url = me.id + "/fncSelSyokusyuKamokCnvSQL";
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                $(".FrmSyokusyubetuKamokuMente").attr("disabled", true);
                $(".FrmSyokusyubetuKamokuMente button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            //入力領域のコンボボックスの設定を行う
            var url = me.id + "/" + "FncSelCodeMstSQL";
            me.ajax.receive = function (res) {
                res = eval("(" + res + ")");
                if (res["result"] == false) {
                    $(".FrmSyokusyubetuKamokuMente").attr("disabled", true);
                    $(".FrmSyokusyubetuKamokuMente button").button("disable");

                    me.clsComFnc.FncMsgBox("E9999", res["error"]);
                    return;
                } else {
                    //職種の取得
                    for (key in res["Code"]["data"]) {
                        $("<option></option>")
                            .val(res["Code"]["data"][key]["CODE"])
                            .text(res["Code"]["data"][key]["MEISYOU"])
                            .appendTo(".FrmSyokusyubetuKamokuMente.cmbSyCd");
                    }
                    $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val(
                        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val()
                    );

                    //区分の取得
                    for (key in res["Kubun"]["data"]) {
                        $("<option></option>")
                            .val(res["Kubun"]["data"][key]["KUBUN_CD"])
                            .text(res["Kubun"]["data"][key]["KUBUN_NM"])
                            .appendTo(".FrmSyokusyubetuKamokuMente.cmbItem");
                    }
                    $(".FrmSyokusyubetuKamokuMente.cmbItem").val(
                        $(".FrmSyokusyubetuKamokuMente.cmbItem").val()
                    );
                }

                //初期表示クリア
                $(".FrmSyokusyubetuKamokuMente.cmbItem").selectedIndex = -1;
                $(".FrmSyokusyubetuKamokuMente.cmbItem").val("");
                $(".FrmSyokusyubetuKamokuMente.cmbSyCd").selectedIndex = -1;
                $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val("");

                $(me.grid_id).jqGrid("setSelection", 0, true);
            };
            me.ajax.send(url, "", 0);
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 950);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 220 : 260
        );

        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "KAMOK_CD",
                    numberOfColumns: 3,
                    titleText: "科目",
                },
                {
                    startColumnName: "SYOKUSYU_CD",
                    numberOfColumns: 2,
                    titleText: "職種",
                },
            ],
        });
        $(me.grid_id).jqGrid("bindKeys");
    };

    // 選択ボタン
    me.cmdSel_Click = function () {
        var selrow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        //行が選択されていない場合ｴﾗｰ
        if (!selrow) {
            me.clsComFnc.FncMsgBox("I0010");
            return;
        }

        var rowData = $(me.grid_id).jqGrid("getRowData", selrow);
        //選択した行の内容を入力領域に表示する
        $(".FrmSyokusyubetuKamokuMente.txtKaCd").val(
            me.clsComFnc.FncNv(rowData["KAMOK_CD"])
        );
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").val(
            me.clsComFnc.FncNv(rowData["HIMOK_CD"])
        );
        $(".FrmSyokusyubetuKamokuMente.lblKaNm").val(
            me.clsComFnc.FncNv(rowData["KAMOK_NM"])
        );

        $(".FrmSyokusyubetuKamokuMente.cmbItem").val(
            me.clsComFnc.FncNv(rowData["KOUMK_NO"])
        );
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val(
            me.clsComFnc.FncNv(rowData["SYOKUSYU_CD"])
        );

        $(".FrmSyokusyubetuKamokuMente.lblCreD").val(
            me.clsComFnc.FncNv(rowData["Col_CREATE_DATE"])
        );
        $(".FrmSyokusyubetuKamokuMente.lblCreM").val(
            me.clsComFnc.FncNv(rowData["Col_CRE_SYA_CD"])
        );
        $(".FrmSyokusyubetuKamokuMente.lblCreA").val(
            me.clsComFnc.FncNv(rowData["Col_CRE_PRG_ID"])
        );

        //ｺﾝﾄﾛｰﾙの制御
        me.subCtlForm();
    };
    //キャンセルボタン
    me.cmdCan_Click = function () {
        // 画面項目ｸﾘｱ
        me.subClearForm();
    };
    //登録ボタン
    me.cmdReg_Click = function () {
        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        }

        var cmbItem = $(".FrmSyokusyubetuKamokuMente.cmbItem").val();
        var txtKaCd = $(".FrmSyokusyubetuKamokuMente.txtKaCd").val();
        var txtHiCd = $(".FrmSyokusyubetuKamokuMente.txtHiCd").val();
        var lblKaNm = $(".FrmSyokusyubetuKamokuMente.lblKaNm").val();
        var cmbSyCd = $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val();
        var lblCreD = $(".FrmSyokusyubetuKamokuMente.lblCreD").val();
        var lblCreM = $(".FrmSyokusyubetuKamokuMente.lblCreM").val();
        var lblCreA = $(".FrmSyokusyubetuKamokuMente.lblCreA").val();
        //データを登録する
        var url = me.id + "/fncRegSyokusyuKamokCnvSQL";

        var data = {
            cmbItem: cmbItem,
            txtKaCd: txtKaCd,
            txtHiCd: txtHiCd,
            lblKaNm: lblKaNm,
            cmbSyCd: cmbSyCd,
            lblCreD: lblCreD,
            lblCreM: lblCreM,
            lblCreA: lblCreA,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "W0007") {
                    //マスタ存在チェック
                    me.clsComFnc.ObjFocus = $(
                        ".FrmSyokusyubetuKamokuMente.txtKaCd"
                    );
                    $(".FrmSyokusyubetuKamokuMente.txtKaCd").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    $(".FrmSyokusyubetuKamokuMente.txtHiCd").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.FncMsgBox("W0007", "科目");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //登録完了メッセージ
                me.clsComFnc.FncMsgBox("I0012");

                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.initSpread();
            }
        };

        me.ajax.send(url, data, 0);
    };
    //削除ボタン
    me.cmdDel_Click = function () {
        //データを削除する
        var url = me.id + "/" + "fncDelSyokusyuKamokCnvSQL";
        var data = {
            KAMOK_CD: $(".FrmSyokusyubetuKamokuMente.txtKaCd").val(),
            HIMOK_CD: $(".FrmSyokusyubetuKamokuMente.txtHiCd").val(),
            KOUMK_NO: $(".FrmSyokusyubetuKamokuMente.cmbItem").val(),
            SYOKUSYU_CD: $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //完了メッセージ
                me.clsComFnc.FncMsgBox("I0004");

                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.initSpread();
            }
        };

        me.ajax.send(url, data, 0);
    };
    //画面項目クリア
    me.subClearForm = function () {
        $(".FrmSyokusyubetuKamokuMente.cmdSel").button("enable");
        $(".FrmSyokusyubetuKamokuMente.cmbItem").val("");
        $(".FrmSyokusyubetuKamokuMente.cmbItem").attr("disabled", false);
        $(".FrmSyokusyubetuKamokuMente.cmbItem").selectedIndex = -1;
        $(".FrmSyokusyubetuKamokuMente.txtKaCd").val("");
        $(".FrmSyokusyubetuKamokuMente.txtKaCd").attr("readonly", false);
        $(".FrmSyokusyubetuKamokuMente.lblKaNm").val("");
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").val("");
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").attr("readonly", false);
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val("");
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").attr("disabled", false);
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").selectedIndex = -1;
        $(".FrmSyokusyubetuKamokuMente.lblCreD").val("");
        $(".FrmSyokusyubetuKamokuMente.lblCreM").val("");
        $(".FrmSyokusyubetuKamokuMente.lblCreA").val("");

        $(".FrmSyokusyubetuKamokuMente.cmdCan").button("enable");
        $(".FrmSyokusyubetuKamokuMente.cmdReg").button("enable");
        $(".FrmSyokusyubetuKamokuMente.cmdDel").button("disable");
        $(".FrmSyokusyubetuKamokuMente.cmdEnd").button("enable");

        $(".FrmSyokusyubetuKamokuMente.txtKaCd").attr("disabled", false);
        $(".FrmSyokusyubetuKamokuMente.lblKaNm").attr("disabled", true);
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").attr("disabled", false);

        //初期色セット
        me.subResetColor();
    };
    //画面項目制御
    me.subCtlForm = function () {
        $(".FrmSyokusyubetuKamokuMente.cmbItem").attr("disabled", true);
        $(".FrmSyokusyubetuKamokuMente.txtKaCd").attr("disabled", true);
        $(".FrmSyokusyubetuKamokuMente.lblKaNm").attr("disabled", true);
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").attr("disabled", true);
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").attr("disabled", true);

        $(".FrmSyokusyubetuKamokuMente.cmdReg").button("disable");
        $(".FrmSyokusyubetuKamokuMente.cmdDel").button("enable");

        //初期色セット
        me.subResetColor();
    };
    //初期色
    me.subResetColor = function () {
        $(".FrmSyokusyubetuKamokuMente.cmbItem").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmSyokusyubetuKamokuMente.txtKaCd").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmSyokusyubetuKamokuMente.txtHiCd").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".FrmSyokusyubetuKamokuMente.cmbSyCd").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
    };
    //入力チェック
    me.fncInputChk = function () {
        //初期色セット
        me.subResetColor();

        var checkRet = 0;
        var cmbItem = $(".FrmSyokusyubetuKamokuMente.cmbItem").val();
        var cmbSyCd = $(".FrmSyokusyubetuKamokuMente.cmbSyCd").val();
        //項目 必須チェック
        if (!cmbItem) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyubetuKamokuMente.cmbItem");
            $(".FrmSyokusyubetuKamokuMente.cmbItem").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.FncMsgBox("W0008", "項目");
            return false;
        }

        //科目コード 必須チェック, 桁数チェック
        checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmSyokusyubetuKamokuMente.txtKaCd"),
            1,
            me.clsComFnc.INPUTTYPE.NONE,
            5
        );
        switch (checkRet) {
            case -1:
                //必須エラー
                $(".FrmSyokusyubetuKamokuMente.txtKaCd").trigger("focus");
                me.clsComFnc.FncMsgBox("W0001", "科目コード");
                return false;
            case -2:
                //必須エラー
                $(".FrmSyokusyubetuKamokuMente.txtKaCd").trigger("focus");
                me.clsComFnc.FncMsgBox("W0002", "科目コード");
                return false;
            case -3:
                //桁数エラー
                $(".FrmSyokusyubetuKamokuMente.txtKaCd").trigger("focus");
                me.clsComFnc.FncMsgBox("W0003", "科目コード");
                return false;
        }

        //費目コード 桁数チェック
        checkRet = me.clsComFnc.FncTextCheck(
            $(".FrmSyokusyubetuKamokuMente.txtHiCd"),
            0,
            me.clsComFnc.INPUTTYPE.NONE,
            5
        );
        switch (checkRet) {
            case -2:
                //必須エラー
                $(".FrmSyokusyubetuKamokuMente.txtHiCd").trigger("focus");
                me.clsComFnc.FncMsgBox("W0002", "費目コード");
                return false;
            case -3:
                //必須エラー
                $(".FrmSyokusyubetuKamokuMente.txtHiCd").trigger("focus");
                me.clsComFnc.FncMsgBox("W0003", "費目コード");
                return false;
        }

        //職種 必須チェック
        if (!cmbSyCd) {
            me.clsComFnc.ObjFocus = $(".FrmSyokusyubetuKamokuMente.cmbSyCd");
            $(".FrmSyokusyubetuKamokuMente.cmbSyCd").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.FncMsgBox("W0008", "職種");
            return false;
        }

        return true;
    };
    //科目名表示
    me.txtKaCd_Validating = function () {
        $(".FrmSyokusyubetuKamokuMente.lblKaNm").val("");
        var txtKaCd = $(".FrmSyokusyubetuKamokuMente.txtKaCd").val();
        var txtHiCd = $(".FrmSyokusyubetuKamokuMente.txtHiCd").val();

        var url = me.id + "/FncGetKamokuNm";
        var data = {
            txtKaCd: txtKaCd,
            txtHiCd: txtHiCd,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                //該当データ有
                if (result["row"] !== 0) {
                    $(".FrmSyokusyubetuKamokuMente.lblKaNm").val(
                        me.clsComFnc.FncNv(result["data"][0]["KAMOK_NM"])
                    );
                }
                //該当データ無
                else {
                    $(".FrmSyokusyubetuKamokuMente.lblKaNm").val("");
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //スプレッドの初期値設定
    me.initSpread = function () {
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            //画面項目ｸﾘｱ
            me.subClearForm();

            $(me.grid_id).jqGrid("setSelection", 0, true);
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, "", complete_fun);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmSyokusyubetuKamokuMente =
        new JKSYS.FrmSyokusyubetuKamokuMente();
    o_JKSYS_FrmSyokusyubetuKamokuMente.load();
});
