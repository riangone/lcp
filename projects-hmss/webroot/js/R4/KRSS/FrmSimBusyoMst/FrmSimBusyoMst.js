/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmSimBusyoMst");

KRSS.FrmSimBusyoMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";

    me.id = "FrmSimBusyoMst";

    me.sys_id = "KRSS";

    me.lastsel = 0;

    me.maxRow = 0;

    me.arrInputData = new Array();

    me.updData = new Object();

    // ========== 変数 end ==========

    //jqGriDの設定する
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 90,
            sortable: false,
            editable: false,
            align: "right",
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 180,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SALESNUMBER_KB",
            label: "売上台数ﾗﾝｷﾝｸﾞ区分",
            index: "SALESNUMBER_KB",
            width: 100,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (e.shiftKey && key == 9) {
                                var selIRow = parseInt(me.lastsel) - 1;
                                if (selIRow == 0) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmSimBusyoMst_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmSimBusyoMst_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_PROFIT_KB";
                                    $(selNextId).trigger("focus");
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }

                            if (key == 13) {
                                //enter
                                var selIRow = parseInt(me.lastsel);
                                var selNextId = "#" + selIRow + "_SALES_KB";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            return true;
                        },
                    },
                ],
            },
        },
        {
            name: "SALES_KB",
            label: "売上ﾗﾝｷﾝｸﾞ区分",
            index: "SALES_KB",
            width: 90,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (e.shiftKey && key == 9) {
                                var selIRow = parseInt(me.lastsel);
                                var selNextId =
                                    "#" + selIRow + "_SALESNUMBER_KB";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }

                            if (key == 13) {
                                //enter and tab
                                var selIRow = parseInt(me.lastsel);
                                var selNextId = "#" + selIRow + "_PROFIT_KB";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            return true;
                        },
                    },
                ],
            },
        },
        {
            name: "PROFIT_KB",
            label: "経常利益ﾗﾝｷﾝｸﾞ区分",
            index: "PROFIT_KB",
            width: 100,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            if (e.shiftKey && key == 9) {
                                var selIRow = parseInt(me.lastsel);
                                var selNextId = "#" + selIRow + "_SALES_KB";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 13 || (key == 9 && !e.shiftKey)) {
                                var selIRow = parseInt(me.lastsel) + 1;
                                if (selIRow == me.maxRow + 1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                } else {
                                    $("#FrmSimBusyoMst_sprList").jqGrid(
                                        "saveRow",
                                        me.lastsel
                                    );
                                    $("#FrmSimBusyoMst_sprList").jqGrid(
                                        "setSelection",
                                        selIRow,
                                        true
                                    );
                                    var selNextId =
                                        "#" + selIRow + "_SALESNUMBER_KB";
                                    $(selNextId).trigger("focus");
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                            return true;
                        },
                    },
                ],
            },
        },
    ];

    me.controls.push({
        id: ".KRSS.FrmSimBusyoMst.cmdUpdate",
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
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.FrmSimBusyoMst_load();
    };

    // データリストの値を設定
    me.FrmSimBusyoMst_load = function () {
        me.subClearForm();
        $("#FrmSimBusyoMst_sprList").jqGrid({
            datatype: "local",
            // jqgridにデータがなし場合、文字表示しない
            emptyRecordRow: false,
            height: 310,
            colModel: me.colModel,
            rownumbers: true,
            onSelectRow: function (rowId, _status, e) {
                if (typeof e != "undefined") {
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmSimBusyoMst_sprList").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                    if (cellIndex == 1 || cellIndex == 2) {
                        $("#FrmSimBusyoMst_sprList").jqGrid(
                            "editRow",
                            rowId,
                            true
                        );
                    }
                    $("#FrmSimBusyoMst_sprList").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: cellIndex,
                    });
                    $("input, select", e.target).trigger("focus");
                } else {
                    if (rowId && rowId !== me.lastsel) {
                        $("#FrmSimBusyoMst_sprList").jqGrid(
                            "saveRow",
                            me.lastsel
                        );
                        me.lastsel = rowId;
                    }
                    $("#FrmSimBusyoMst_sprList").jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                }
            },
        });
        $("#FrmSimBusyoMst_sprList").jqGrid("bindKeys");
        //一番行目からデータを表示する
        me.subSpreadReShow(0);
    };

    //画面がClearを処理する
    me.subClearForm = function () {
        $("#FrmSimBusyoMst_sprList").jqGrid("clearGridData");
    };

    //シミュレーションラインマスタﾃﾞｰﾀを取得する
    me.subSpreadReShow = function (intRow) {
        //sendのURLの設定する
        var tmpurl = me.sys_id + "/" + me.id + "/subSpreadReShow";
        //sendのデータの設定する
        var data = {};
        //sendを戻る場合の処理する
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //サーバ戻る場合、データを取得する
                me.arrInputData = result["data"];
                var mydata = me.arrInputData;
                //スプレッドにデータリーダーの内容をセット
                for (var i = intRow; i <= mydata.length; i++) {
                    $("#FrmSimBusyoMst_sprList").jqGrid(
                        "addRowData",
                        i + 1,
                        mydata[i]
                    );
                }
                me.maxRow = mydata.length;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }

            //スプレッドにデータリーダーの第一番行目に表示の設定する
            $("#FrmSimBusyoMst_sprList").jqGrid("setSelection", "1");
            $("#1_SALESNUMBER_KB").trigger("focus");
        };
        //send処理
        me.ajax.send(tmpurl, data, 0);
    };

    //**********************************************************************
    //処理説明：登録ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmSimBusyoMst.cmdUpdate").click(function () {
        //入力データをチェックする
        if (me.fnccheck() == true) {
            //msgBOxにYESを押下して、fncupdateを実行する
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncupdate;
            me.clsComFnc.FncMsgBox("QY010");
        }
    });

    //**********************************************************************
    //処 理 名：登録
    //関 数 名：fncupdate
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データ更新
    //**********************************************************************
    me.fncupdate = function () {
        //sendのURLの設定する
        var url = me.sys_id + "/" + me.id + "/" + "cmdUpdate_Click";

        //sendを戻る場合の処理する
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0008");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        //send処理
        me.ajax.send(url, me.updData, 0);
    };

    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：fnccheck
    //引    数：無し
    //戻 り 値：true/false
    //処理説明：入力チェック
    //**********************************************************************
    me.fnccheck = function () {
        $("#FrmSimBusyoMst_sprList").jqGrid("saveRow", me.lastsel);
        var lineIdArr = $("#FrmSimBusyoMst_sprList").jqGrid("getDataIDs");
        var lineArr = new Array();
        for (key in lineIdArr) {
            var rowId = lineIdArr[key];
            var lineTableData = $("#FrmSimBusyoMst_sprList").jqGrid(
                "getRowData",
                lineIdArr[key]
            );
            for (key1 in lineTableData) {
                switch (key1) {
                    case "SALESNUMBER_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[2]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            //1、2　以外はエラー
                            if (
                                lineTableData[key1] != 1 &&
                                lineTableData[key1] != 2 &&
                                lineTableData[key1] != 0
                            ) {
                                me.fncFocusErrPos(
                                    "#FrmSimBusyoMst_sprList",
                                    rowId,
                                    key1,
                                    -3,
                                    me.colModel[2]["label"]
                                );
                                return false;
                            }
                        }
                        //入力が必須場合のチェック
                        // else {
                        // me.fncFocusErrPos("#FrmSimBusyoMst_sprList", karowId, key1, intRtn, me.colModel1[2]['label']);
                        // return false;
                        // }
                        break;

                    case "SALES_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[3]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            //1、2　以外はエラー
                            if (
                                lineTableData[key1] != 1 &&
                                lineTableData[key1] != 2 &&
                                lineTableData[key1] != 0
                            ) {
                                me.fncFocusErrPos(
                                    "#FrmSimBusyoMst_sprList",
                                    rowId,
                                    key1,
                                    -3,
                                    me.colModel[3]["label"]
                                );
                                return false;
                            }
                        }
                        break;
                    case "PROFIT_KB":
                        intRtn = me.clsComFnc.FncSprCheck(
                            lineTableData[key1],
                            1,
                            me.clsComFnc.INPUTTYPE.NUMBER1,
                            me.colModel[4]["editoptions"]["maxlength"]
                        );
                        if (intRtn == 0) {
                            //1、2　以外はエラーfncgetUpdData
                            if (
                                lineTableData[key1] != 1 &&
                                lineTableData[key1] != 2 &&
                                lineTableData[key1] != 0
                            ) {
                                me.fncFocusErrPos(
                                    "#FrmSimBusyoMst_sprList",
                                    rowId,
                                    key1,
                                    -3,
                                    me.colModel[4]["label"]
                                );
                                return false;
                            }
                        }
                        break;
                }
            }
            lineArr.push(lineTableData);
            //更新データが設定する
            me.updData = {
                lineArr: lineArr,
            };
        }
        return true;
    };

    //**********************************************************************
    //処 理 名：focus error info's position
    //関 数 名：fncFocusErrPos
    //引    数1：id
    //引    数2：rownum
    //引    数3：colnum
    //引    数4：intRtn
    //引    数5：name
    //戻 り 値：無し
    //処理説明：focus error info's position
    //**********************************************************************
    me.fncFocusErrPos = function (id, rownum, colnum, intRtn, name) {
        //focus the warning position.
        $(id).jqGrid("setSelection", rownum, true);
        $(id).jqGrid("editRow", rownum, true);
        $("#" + rownum + "_" + colnum).trigger("focus");
        //error message.
        switch (intRtn) {
            //必須異常
            case -1:
                me.clsComFnc.MessageBox(
                    name + "を入力してください。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //入力異常
            case -2:
                me.clsComFnc.MessageBox(
                    name + "が正しくありません。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //桁数異常
            case -3:
                me.clsComFnc.MessageBox(
                    name + "が正しくありません。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
            //科目/費目コードが不正です
            case -4:
                me.clsComFnc.MessageBox(
                    name + "が不正です。",
                    "経常利益シミュレーション",
                    "OK",
                    "Warning"
                );
                break;
        }
    };
    return me;
};

$(function () {
    var o_KRSS_FrmSimBusyoMst = new KRSS.FrmSimBusyoMst();
    o_KRSS_FrmSimBusyoMst.load();
});
