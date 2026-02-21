Namespace.register("R4.FrmDLStateCheck");

R4.FrmDLStateCheck = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    me.id = "R4K/FrmDLStateCheck";
    me.grid_id = "#FrmDLStateCheck_sprList";
    me.lastsel = 0;
    me.arrInputDatas = new Array();
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
        shrinkToFit: me.ratio === 1.5,
    };
    me.colModel = [
        {
            name: "CHECK_FLAG",
            label: "確認",
            index: "CHECK_FLAG",
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
            name: "FILE_NM",
            label: "ファイル名",
            index: "FILE_NM",
            width: 280,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "DT",
            label: "実行開始日時",
            index: "DT",
            width: 176,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "STEP",
            label: "ステップ",
            index: "STEP",
            width: 60,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "STATE",
            label: "状態",
            index: "STATE",
            width: 45,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "MESSAGE",
            label: "メッセージ",
            index: "MESSAGE",
            width: 350,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmDLStateCheck.cmdDisp",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmDLStateCheck.cmdUpdate",
        type: "button",
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

    $(".FrmDLStateCheck.cmdDisp").click(function () {
        //ｽﾌﾟﾚｯﾄﾞを再表示する
        if (!me.fncReDisp(false)) {
            return;
        }
    });

    shortcut.add("F5", function () {
        //F 5キー＝更新ボタン
        //ｽﾌﾟﾚｯﾄﾞを再表示する
        if (!me.fncReDisp(false)) {
            return;
        }
    });

    $(".FrmDLStateCheck.cmdUpdate").click(function () {
        var url = me.id + "/fncStateDelUpd";
        var arrInputData = me.fncGetInputData();
        var sendData = {
            inputData: arrInputData,
        };

        if (arrInputData.length != 0) {
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                } else {
                    //ｽﾌﾟﾚｯﾄﾞを再表示する
                    if (!me.fncReDisp(true)) {
                        return;
                    }
                }
            };
            me.ajax.send(url, sendData, 0);
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        var url = me.id + "/fncHFTS_TRANSFER_LIST_Sel";

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                //エラー場合、コントロール状態変更
                $(".FrmDLStateCheck.cmdUpdate").button("disable");
                return;
            }

            $(me.grid_id).jqGrid("setSelection", 0, true);
        };

        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1024 : 1050
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 312 : 390
        );
    };

    me.fncReDisp = function (flagType) {
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "error") {
                //エラー場合、コントロール状態変更
                $(".FrmDLStateCheck.cmdUpdate").button("disable");
                return;
            }

            $(me.grid_id).jqGrid("setSelection", 0, true);
            $(".FrmDLStateCheck.cmdUpdate").button("enable");

            if (flagType) {
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0008");
            }
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, "", me.complete_fun);
    };

    me.fncGetInputData = function () {
        var arr = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            //ﾁｪｯｸが入っている行を更新する
            if (rowData["CHECK_FLAG"] != "No") {
                arr.push(rowData);
            }
        }

        return arr;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmDLStateCheck = new R4.FrmDLStateCheck();
    o_R4_FrmDLStateCheck.load();
});
