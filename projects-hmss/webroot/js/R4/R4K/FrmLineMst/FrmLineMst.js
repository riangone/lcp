/**
 *
 * ラインマスタメンテナンス
 *
 * @alias FrmLineMst
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150724           #1984                   ime-mode設定                       ZHENGHUIYUN
 * 20150811           #1985                   BUG		                        Yuanjh
 * 20150820           #2078                   BUG		                        Yuanjh
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmLineMst");

R4.FrmLineMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmLineMst";
    me.sys_id = "R4K";
    me.grid_id = "#FrmLineMst_sprList";
    me.g_url = "R4K/FrmLineMst/fncLineMstSelect";
    me.pager = "#FrmLineMst_pager";
    me.sidx = "LINE_NO";
    me.lastsel = 0;
    me.firstData = new Array();
    me.addData = {
        LINE_NO: "",
        ITEM_NM: "",
        TANI: "",
        RND_KB: "",
        RND_POS: "",
        CAL_KB: "",
        DISP_KB: "",
        IDX_NM: "",
        IDX_LINE_NO: "",
        IDX_CAL_KB: "",
        IDX_TANI: "",
        IDX_RND_KB: "",
        IDX_RND_POS: "",
        SONEK_PRN_FLG: "",
        CREATE_DATE: "",
    };

    me.colNames = [
        "ラインNo.",
        "項目名称",
        "単位",
        "丸め区分",
        "丸め位置",
        "計算区分",
        "表示区分",
        "指標説明",
        "指標対象ライン",
        "指標計算区分",
        "指標単位",
        "指標丸め区分",
        "指標丸め位置",
        "損益ﾌﾗｸﾞ",
        "作成日",
    ];
    me.colModel = [
        {
            name: "LINE_NO",
            //lable : 'ライン№',
            index: "LINE_NO",
            width: 70,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric sbc_field",
                maxlength: "4",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 3, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "ITEM_NM",
            //lable : '項目名称',
            index: "ITEM_NM",
            width: 160,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
            },
        },
        {
            //lable : '単位',
            name: "TANI",
            index: "TANI",
            width: 25,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '丸め区分',
            name: "RND_KB",
            index: "RND_KB",
            width: 40,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '丸め位置',
            name: "RND_POS",
            index: "RND_POS",
            width: 40,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 1, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            //lable : '計算区分',
            name: "CAL_KB",
            index: "CAL_KB",
            width: 40,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 1, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            //lable : '表示区分',
            name: "DISP_KB",
            index: "DISP_KB",
            width: 40,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '指標説明',
            name: "IDX_NM",
            index: "IDX_NM",
            width: 160,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "20",
            },
        },
        {
            //lable : '指標対象ライン',
            name: "IDX_LINE_NO",
            index: "IDX_LINE_NO",
            width: 70,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "4",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 3, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            //lable : '指標計算区分',
            name: "IDX_CAL_KB",
            index: "IDX_CAL_KB",
            width: 70,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '指標単位',
            name: "IDX_TANI",
            index: "IDX_TANI",
            width: 40,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '指標丸め区分',
            name: "IDX_RND_KB",
            index: "IDX_RND_KB",
            width: 50,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '指標丸め位置',
            name: "IDX_RND_POS",
            index: "IDX_RND_POS",
            width: 50,
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "2",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 1, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            //lable : '損益ﾌﾗｸﾞ',
            name: "SONEK_PRN_FLG",
            index: "SONEK_PRN_FLG",
            width: 40,
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "1",
            },
        },
        {
            //lable : '作成日',
            name: "CREATE_DATE",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmLineMst.cmdAction",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmLineMst.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        if (me.fncInputChk() == false) {
            return;
        }
        //確認メッセージ
        else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataLineMst;
            me.clsComFnc.FncMsgBox("QY010");
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
        me.fncLineMstSelect();
    };

    $(me.grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        height: me.ratio ? 331 : 416,
        colModel: me.colModel,
        colNames: me.colNames,
        rownumbers: true,

        // '**********************************************************************
        // '処理概要：スプレッドセルクリック
        // '**********************************************************************
        onSelectRow: function (rowid, _status, e) {
            if (typeof e != "undefined") {
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;

                //ヘッダークリック以外
                if (cellIndex != 0) {
                    if (rowid && rowid != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowid;
                    }
                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                } else {
                    //ヘッダークリック
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );

                    //削除確認メッセージを表示する
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.MessageBox(
                        "削除します、よろしいですか？",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowid;
                }

                $(me.grid_id).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
            }

            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });

            gdmz.common.jqgrid.setKeybordEvents(me.grid_id, e, me.lastsel);
        },
    });
    //---20150820 Yuanjh add s.Bind enter key ,but do nothing.It seems like strange.:)
    //---But if not,when you press up or down,the selected row will jump to the center of Grid.
    $(me.grid_id).jqGrid("bindKeys", {
        onEnter: function () {},
    });
    //---20150820 Yuanjh add e.

    me.fncLineMstSelect = function () {
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                for (var i = 0; i < result["data"].length; i++) {
                    for (key in result["data"][i]) {
                        result["data"][i][key] = me.clsComFnc
                            .FncNv(result["data"][i][key])
                            .trimEnd();
                    }

                    $(me.grid_id).jqGrid("addRowData", i, result["data"][i]);
                }

                if (result["data"].length < 100) {
                    for (var i = result["data"].length; i < 100; i++) {
                        $(me.grid_id).jqGrid("addRowData", i, me.addData);
                    }
                }

                $(me.grid_id).jqGrid("setSelection", 0, true);

                {
                    var supportIMEMode = "ime-mode" in document.body.style;

                    // 1バイト文字専用フィールド
                    $(".sbc_field").keydown(function () {
                        // ime-modeが使えるブラウザならスキップ
                        if (supportIMEMode) return;

                        // マルチバイト文字が入力されたら削除
                        var target = $(this);
                        window.setTimeout(function () {
                            var v = target.val();
                            target.val(filterMBC(v));
                        }, 1);
                    });
                    // // 全ブラウザ：貼り付け
                    // .on('paste', function()
                    // {
                    // // マルチバイト文字が入力されたら削除
                    // var target = jQuery(this);
                    // window.setTimeout(function()
                    // {
                    // var v = target.val();
                    // target.val(filterMBC(v));
                    //
                    // }, 1);
                    // });

                    // 日本語(マルチバイト文字)を削除した値を返す
                    function filterMBC(src) {
                        var str = "";
                        src = encodeURIComponent(src);
                        // abあcd => ab%u3042cd
                        for (i = 0; i < src.length; i++) {
                            var chr = src.charAt(i);
                            if (chr == "%") {
                                var nchr = src.charAt(++i);
                                if (nchr == "u") {
                                    // 2バイト文字をスキップ
                                    i += 4;
                                } else {
                                    // 1バイト文字を追加
                                    str += chr;
                                    str += nchr;
                                    str += src.charAt(++i);
                                }
                                continue;
                            }

                            str += chr;
                        }
                        return decodeURIComponent(str);
                    }
                }
                me.firstData = $(me.grid_id).jqGrid("getRowData");
            } else if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(me.g_url, "", 1);
    };

    // **********************************************************************
    // 処 理 名：jqGridのヘッダークリック時、削除選択行
    // 関 数 名：delRowData
    // 引    数：	無し
    // 戻 り 値：	無し
    // 処理説明：jqGridのヘッダークリック時、削除選択行
    // **********************************************************************
    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

        if (rowData["LINE_NO"] != "") {
            var url = me.id + "/frmDeleteSelectRow";
            var data = {
                LINE_NO: rowData["LINE_NO"],
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    me.delRowDataContent(rowID);
                } else if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            me.delRowDataContent(rowID);
        }
    };

    me.delRowDataContent = function (rowID) {
        var getDataID = $(me.grid_id).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", i + 1);
            $(me.grid_id).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_id).jqGrid("delRowData", getDataID.length - 1);
        $(me.grid_id).jqGrid("setSelection", rowID, true);
        if (me.firstData.length - 1 >= parseInt(rowID)) {
            me.firstData.splice(parseInt(rowID), 1);
        }
    };

    // **********************************************************************
    // 処 理 名：jqGridのデータ入力チェック
    // 関 数 名：fncInputChk
    // 引    数：	無し
    // 戻 り 値：	True:正常終了 False:異常終了
    // 処理説明：jqGridのデータ入力チェック
    // **********************************************************************
    me.fncInputChk = function () {
        var intRtn = 0;
        var blnInputFlg = false;
        var data = $(me.grid_id).jqGrid("getDataIDs");
        var arrCheckData = new Array();

        for (rowID in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[rowID]);

            if (
                rowData["LINE_NO"].trimEnd() != "" ||
                rowData["ITEM_NM"].trimEnd() != "" ||
                rowData["TANI"].trimEnd() != "" ||
                rowData["RND_KB"].trimEnd() != "" ||
                rowData["RND_POS"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != "" ||
                rowData["DISP_KB"].trimEnd() != ""
            ) {
                var iColNo = 0;
                for (colID in rowData) {
                    switch (colID) {
                        case "RND_KB":
                        case "IDX_CAL_KB":
                        case "IDX_RND_KB":
                        case "SONEK_PRN_FLG":
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.CHAR2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                        case "LINE_NO":
                        case "RND_POS":
                        case "CAL_KB":
                        case "IDX_LINE_NO":
                        case "IDX_RND_POS":
                            if (
                                colID == "LINE_NO" ||
                                colID == "IDX_LINE_NO" ||
                                colID == "IDX_RND_POS"
                            ) {
                                var maxlength = rowData[colID].length - 1;
                                if (
                                    rowData[colID].length > maxlength &&
                                    rowData[colID] > 0
                                ) {
                                    intRtn = me.clsComFnc.FncSprCheck(
                                        rowData[colID],
                                        0,
                                        me.clsComFnc.INPUTTYPE.NUMBER2,
                                        me.colModel[iColNo]["editoptions"][
                                            "maxlength"
                                        ] - 1
                                    );
                                    break;
                                }
                            }
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                        case "CREATE_DATE":
                            break;
                        default:
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NONE,
                                me.colModel[iColNo]["editoptions"]["maxlength"]
                            );
                            break;
                    }

                    if (intRtn != 0) {
                        me.setFocus(rowID, colID);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colNames[iColNo]
                        );
                        return false;
                    }

                    //入力内容チェック
                    switch (colID) {
                        case "LINE_NO":
                            //ライン№の必須ﾁｪｯｸ
                            if (rowData[colID].trimEnd() == "") {
                                intRtn = 1;
                            }
                            break;
                        case "RND_KB":
                        case "IDX_RND_KB":
                            var content = me.clsComFnc.FncNz(rowData[colID]);
                            if (
                                content != "0" &&
                                content != "1" &&
                                content != "2"
                            ) {
                                intRtn = 2;
                            }
                            break;
                        case "CAL_KB":
                            var content = me.clsComFnc.FncNv(rowData[colID]);
                            if (content != "") {
                                if (
                                    rowData[colID] != 1 &&
                                    rowData[colID] != -1
                                ) {
                                    intRtn = 2;
                                }
                            }
                            break;
                        case "DISP_KB":
                        case "IDX_CAL_KB":
                            var content = me.clsComFnc.FncNv(rowData[colID]);
                            if (content != "") {
                                if (rowData[colID] != 1) {
                                    intRtn = 2;
                                }
                            }
                            break;
                        case "IDX_LINE_NO":
                            var content = me.clsComFnc.FncNv(rowData[colID]);
                            if (content != "") {
                                if (rowData[colID] < 0 || rowData[colID] > 85) {
                                    intRtn = 2;
                                }
                            }
                            break;
                        case "IDX_TANI":
                            var content = me.clsComFnc.FncNv(rowData[colID]);
                            if (content != "") {
                                if (rowData[colID] != "%") {
                                    intRtn = 2;
                                }
                            }
                            break;
                        case "SONEK_PRN_FLG":
                            var content = me.clsComFnc.FncNv(rowData[colID]);
                            if (content != "") {
                                if (content != "O") {
                                    intRtn = 2;
                                }
                            }
                            break;
                    }

                    if (intRtn != 0) {
                        me.setFocus(rowID, colID);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn,
                            me.colNames[iColNo]
                        );
                        return false;
                    }

                    iColNo += 1;
                }

                blnInputFlg = true;
            }

            var tmpAttr = {
                LINE_NO: "",
            };

            tmpAttr["LINE_NO"] = rowData["LINE_NO"];

            arrCheckData.push(tmpAttr);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "LINE_NO");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        //重複ﾁｪｯｸ
        for (var i = 0; i < arrCheckData.length - 1; i++) {
            for (var j = i + 1; j < arrCheckData.length; j++) {
                if (arrCheckData[i]["LINE_NO"].trimEnd() != "") {
                    if (
                        arrCheckData[i]["LINE_NO"] == arrCheckData[j]["LINE_NO"]
                    ) {
                        var row = j;
                        if (me.firstData.length - 1 >= i) {
                            if (
                                me.firstData[i]["LINE_NO"] !==
                                arrCheckData[i]["LINE_NO"]
                            ) {
                                var row = i;
                            }
                        }
                        me.setFocus(row, "LINE_NO");
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "キー項目が重複しています"
                        );
                        return false;
                    }
                }
            }
        }

        return true;
    };

    // **********************************************************************
    // 処 理 名：入力データ取得
    // 関 数 名：fncGetInputData
    // 引    数：	無し
    // 戻 り 値：	配列.入力値
    // 処理説明：jqGridの入力データ取得
    // **********************************************************************
    me.fncGetInputData = function () {
        var arr = new Array();
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (
                rowData["LINE_NO"].trimEnd() != "" ||
                rowData["ITEM_NM"].trimEnd() != "" ||
                rowData["TANI"].trimEnd() != "" ||
                rowData["RND_KB"].trimEnd() != "" ||
                rowData["RND_POS"].trimEnd() != "" ||
                rowData["CAL_KB"].trimEnd() != "" ||
                rowData["DISP_KB"].trimEnd() != "" ||
                rowData["IDX_NM"].trimEnd() != "" ||
                rowData["IDX_LINE_NO"].trimEnd() != "" ||
                rowData["IDX_CAL_KB"].trimEnd() != "" ||
                rowData["IDX_TANI"].trimEnd() != "" ||
                rowData["IDX_RND_KB"].trimEnd() != "" ||
                rowData["IDX_RND_POS"].trimEnd() != "" ||
                rowData["SONEK_PRN_FLG"].trimEnd() != ""
            ) {
                arr.push(rowData);
            }
        }

        return arr;
    };

    // **********************************************************************
    // 処 理 名：エラーセルフォーカス
    // 関 数 名：setFocus
    // 引    数：	無し
    // 戻 り 値：無し
    // 処理説明：エラーセルフォーカスする
    // **********************************************************************
    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID);
        $(me.grid_id).jqGrid("setSelection", rowNum);

        var ceil = rowID + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    // **********************************************************************
    // 処 理 名：jqGridのデータ入力チェック
    // 関 数 名：fncDeleteUpdataLineMst
    // 引    数：	無し
    // 戻 り 値：	無し
    // 処理説明：jqGridのデータ入力チェック
    // **********************************************************************
    me.fncDeleteUpdataLineMst = function () {
        var arrInputData = me.fncGetInputData();

        var url = me.id + "/fncDeleteUpdateLineMst";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                me.clsComFnc.FncMsgBox("I0008");
                $(me.grid_id).jqGrid("clearGridData");
                me.fncLineMstSelect();
            }
        };
        me.ajax.send(url, arrInputData, 0);
    };

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substr(0, 1) + (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                } else if (
                    inputValue == "0" &&
                    keycode >= 49 &&
                    keycode <= 57
                ) {
                    inputValue = (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                }

                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substr(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substr(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substr(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmLineMst = new R4.FrmLineMst();
    o_R4_FrmLineMst.load();
});
