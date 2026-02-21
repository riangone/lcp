/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmR2KAIKEI");

R4.FrmR2KAIKEI = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    me.id = "R4K/FrmR2KAIKEI";
    me.grid_error = "#FrmR2KAIKEI_sprErrList";
    me.grid_id = "#FrmR2KAIKEI_sprList";
    me.strCsvPath = "";
    me.strLogPath = "";
    me.strBackUpPath = "";
    me.blnLockFlg = false;
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModelErr = [
        {
            name: "",
            label: "ｴﾗｰ科目ｺｰﾄﾞ",
            index: "",
            width: 150,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];
    me.colModel = [
        {
            name: "CSV_OUT_DT",
            label: "作成日時",
            index: "CSV_OUT_DT",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "CNT",
            label: "件数",
            index: "CNT",
            width: 100,
            sortable: false,
            editable: false,
            align: "right",
        },
    ];

    // ========== 変数 end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmR2KAIKEI.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmR2KAIKEI.cboDateFrom",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmR2KAIKEI.cboDateTo",
        type: "datepicker",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmR2KAIKEI.cboDateFrom").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmR2KAIKEI.cboDateFrom"))) {
            $(".FrmR2KAIKEI.cboDateFrom").val(
                $(".FrmR2KAIKEI.cboDateFrom").val()
            );
            $(".FrmR2KAIKEI.cmdAction").button("enable");
        } else {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                var currentDate = new Date();
                $(".FrmR2KAIKEI.cboDateFrom").datepicker(
                    "setDate",
                    currentDate
                );
                $(".FrmR2KAIKEI.cboDateFrom").trigger("focus");
                $(".FrmR2KAIKEI.cboDateFrom").trigger("select");
                $(".FrmR2KAIKEI.cmdAction").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        }
    });

    $(".FrmR2KAIKEI.cboDateTo").on("blur", function () {
        //20201117 wangying upd S
        // if (me.clsComFnc.CheckDate($(".FrmR2KAIKEI.cboDateFrom")))
        if (me.clsComFnc.CheckDate($(".FrmR2KAIKEI.cboDateTo"))) {
            //20201117 wangying upd E
            $(".FrmR2KAIKEI.cboDateTo").val($(".FrmR2KAIKEI.cboDateTo").val());
            $(".FrmR2KAIKEI.cmdAction").button("enable");
        } else {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                var currentDate = new Date();
                $(".FrmR2KAIKEI.cboDateTo").datepicker("setDate", currentDate);
                $(".FrmR2KAIKEI.cboDateTo").trigger("focus");
                $(".FrmR2KAIKEI.cboDateTo").trigger("select");
                $(".FrmR2KAIKEI.cmdAction").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        }
    });

    $(".FrmR2KAIKEI.radOutFlg").click(function () {
        $(".FrmR2KAIKEI.listArea").css("visibility", "hidden");
        $(".FrmR2KAIKEI.cboDateFrom").prop("disabled", "disabled");
        $(".FrmR2KAIKEI.cboDateTo").prop("disabled", "disabled");
    });

    $(".FrmR2KAIKEI.radHanibi").click(function () {
        $(".FrmR2KAIKEI.listArea").css("visibility", "hidden");
        $(".FrmR2KAIKEI.cboDateFrom").removeAttr("disabled");
        $(".FrmR2KAIKEI.cboDateTo").removeAttr("disabled");
        $(".FrmR2KAIKEI.cboDateFrom").trigger("focus");
        $(".FrmR2KAIKEI.cboDateFrom").trigger("select");
    });

    $(".FrmR2KAIKEI.radDate").click(function () {
        $(".FrmR2KAIKEI.cboDateFrom").prop("disabled", "disabled");
        $(".FrmR2KAIKEI.cboDateTo").prop("disabled", "disabled");

        me.subSpreadReShow();
    });

    $(".FrmR2KAIKEI.cmdAction").click(function () {
        // var strRdoValue = "";
        var strCboDateFrom = "";
        var strCboDateTo = "";
        // var strSelDate = "";

        if ($(".FrmR2KAIKEI.radOutFlg").prop("checked") == true) {
            strRdoValue = $(".FrmR2KAIKEI.radOutFlg").val();
        } else if ($(".FrmR2KAIKEI.radHanibi").prop("checked") == true) {
            strRdoValue = $(".FrmR2KAIKEI.radHanibi").val();
            strCboDateFrom = $(".FrmR2KAIKEI.cboDateFrom").val();
            strCboDateTo = $(".FrmR2KAIKEI.cboDateTo").val();
        } else {
            strRdoValue = $(".FrmR2KAIKEI.radDate").val();

            var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
            var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
            strSelDate = rowData["CSV_OUT_DT"];
        }

        me.subClearForm2();

        //出力条件の日付From>日付Toの場合、エラー [delete]
        //出力条件の日付From>日付Toの場合、エラー
        if ($(".FrmR2KAIKEI.radHanibi").prop("checked") == true) {
            if (
                parseInt(strCboDateFrom.replace(/\//g, "")) >
                parseInt(strCboDateTo.replace(/\//g, ""))
            ) {
                me.clsComFnc.ObjFocus = $(".FrmR2KAIKEI.cboDateFrom");
                me.clsComFnc.ObjSelect = $(".FrmR2KAIKEI.cboDateFrom");
                me.clsComFnc.FncMsgBox("E9999", "計上日範囲が不正です");
                return;
            }
        }

        var url = me.id + "/fncCheckState";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            me.blnLockFlg = result["blnLockFlg"];

            //出力確認ﾒｯｾｰｼﾞ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdR2KAIKEI;
            me.clsComFnc.MsgBoxBtnFnc.No = me.cancel;
            me.clsComFnc.FncMsgBox("QY009");
        };
        me.ajax.send(url, "", 0);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    me.load = function () {
        base_load();

        me.subClearForm();

        var url = me.id + "/fncGetPath";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.strCsvPath = result["strCsvPath"];
                me.strLogPath = result["strLogPath"];
                me.strBackUpPath = result["strBackUpPath"];

                $(".FrmR2KAIKEI.txtOutput").val(me.strCsvPath);
                $(".FrmR2KAIKEI.listArea").css("visibility", "hidden");
                $(".FrmR2KAIKEI.cboDateFrom").attr("disabled", "disabled");
                $(".FrmR2KAIKEI.cboDateTo").attr("disabled", "disabled");
                var todayDate = new Date();
                $(".FrmR2KAIKEI.cboDateFrom").datepicker("setDate", todayDate);
                $(".FrmR2KAIKEI.cboDateTo").datepicker("setDate", todayDate);

                var urlGrid = me.id + "/fncRirekiDateSelect";
                gdmz.common.jqgrid.init(
                    me.grid_id,
                    urlGrid,
                    me.colModel,
                    "",
                    "",
                    me.option
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 380);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 169 : 220
                );

                //CSV未出力のデータを初期値として選択
                $(".FrmR2KAIKEI.radOutFlg").prop("checked", "checked");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };

    $(me.grid_error).jqGrid({
        datatype: "local",
        height: me.ratio === 1.5 ? 140 : 200,
        colModel: me.colModelErr,
        rownumbers: true,
    });

    me.subClearForm = function () {
        $(".FrmR2KAIKEI.txtOutput").val("");
        $(".FrmR2KAIKEI.radOutFlg").prop("checked", "checked");
        $(".FrmR2KAIKEI.listArea").css("visibility", "hidden");

        me.subClearForm2();
    };

    me.subClearForm2 = function () {
        $(".FrmR2KAIKEI.lblMSG").val("");
        $(".FrmR2KAIKEI.lblMSG2").val("");
        $(".FrmR2KAIKEI.lblCnt").val("");
        $(".FrmR2KAIKEI.lblAllCnt").val("");
        $(".FrmR2KAIKEI.listArea").css("visibility", "hidden");
        $(".FrmR2KAIKEI.errorArea").css("visibility", "hidden");

        $(me.grid_error).jqGrid("clearGridData");
    };

    // '**********************************************************************
    // '処 理 名：データグリッドの再表示
    // '関 数 名：subSpreadReShow
    // '引    数：objDr (I) オブジェクト
    // '戻 り 値：無し
    // '処理説明：データグリッドを再表示する
    // '**********************************************************************
    me.subSpreadReShow = function () {
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                $(me.grid_id).jqGrid("clearGridData");

                //社員マスタにデータが存在
                if (bErrorFlag == "nodata") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncControlSet;
                    me.clsComFnc.ObjFocus = $(".FrmR2KAIKEI.radOutFlg");
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
            } else {
                $(".FrmR2KAIKEI.listArea").css("visibility", "visible");
                $(me.grid_id).jqGrid("setSelection", 0, true);
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, "", me.complete_fun);
    };

    me.fncControlSet = function () {
        $(".FrmR2KAIKEI.radOutFlg").prop("checked", "true");
    };

    me.leaveFrmR2KAIKEIPage = function (bFlagLeave) {
        //-----排他制御-----
        if (me.blnLockFlg) {
            //ロックを外す
            var url = me.id + "/fncLogUpdate";

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }

                me.blnLockFlg = false;

                if (bFlagLeave) {
                    me.FrmMainMenu.getHtml(
                        me.FrmMainMenu.setNodeID,
                        me.FrmMainMenu.setfrmNM,
                        me.FrmMainMenu.setUrl
                    );
                }
            };
            me.ajax.send(url, "", 0);
        } else if (bFlagLeave) {
            me.FrmMainMenu.getHtml(
                me.FrmMainMenu.setNodeID,
                me.FrmMainMenu.setfrmNM,
                me.FrmMainMenu.setUrl
            );
        }
    };

    me.fncUpdR2KAIKEI = function () {
        var arrInputData = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (rowData["TEISYU"] != "" || rowData["HOYU"] != "") {
                arrInputData.push(rowData);
            }
        }

        var url = me.id + "/fncDeleteUpdataTeisyuMst";
        var sendData = {
            strLogPath: me.strLogPath,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                $(".FrmTeisyu.txtBusyoCD").val("");
                $(".FrmTeisyu.lblBusyoNM").val("");
                $(".FrmTeisyu.cmdAction").button("disable");
                $(".FrmTeisyu.txtBusyoCD").trigger("focus");
                $(me.grid_id).jqGrid("clearGridData");

                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.cancel = function () {
        me.leaveFrmR2KAIKEIPage(false);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmR2KAIKEI = new R4.FrmR2KAIKEI();
    o_R4_FrmR2KAIKEI.load();
    o_R4K_R4K.FrmR2KAIKEI = o_R4_FrmR2KAIKEI;
});
