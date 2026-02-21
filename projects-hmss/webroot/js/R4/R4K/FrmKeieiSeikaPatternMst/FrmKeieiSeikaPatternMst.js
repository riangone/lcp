/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD         #ID                         XXXXXX                        FCSDL
 * 20150804           #2016 2019 2020 2021 2022    BUG              li
 * 20150817           #2079						   BUG                          Yuanjh
 * 20150820           #2078						   BUG                          Yuanjh
 * 20150827           #2085						   BUG                           li
 * 20151120           #2273						   BUG                          Yuanjh
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmKeieiSeikaPatternMst");

R4.FrmKeieiSeikaPatternMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmKeieiSeikaPatternMst";
    me.grid_Patarn = "#FrmKeieiSeikaPatternMst_sprPatarn";
    me.grid_sprList = "#FrmKeieiSeikaPatternMst_sprList";
    me.g_url = "R4K/FrmKeieiSeikaPatternMst/FrmKeieiSeikaPatternMst";
    me.lastsel = 0;
    me.lastselList = 0;
    me.rowCountLoad = 0;
    //scroll count
    me.widthSum = 0;
    me.divWidth = parseInt(
        $(".FrmKeieiSeikaPatternMst.tabScroll").css("width").replace("px", "")
    );
    var tabsList = $(".FrmKeieiSeikaPatternMst.tabsList").tabs();
    me.copyFlag = false;
    me.bFlagDelete = false;
    me.newData = new Array();
    me.arrInputData = new Array();
    //-----20151120  Yuanjh  ADD S.
    me.arrInputFlg = new Array();
    //-----20151120  Yuanjh  ADD E.
    me.firstPatternData = new Array();
    me.firstSprListData = new Array();
    me.addPattern = {
        PATTERN_NO: "",
        PATTERN_NM: "",
        CREATE_DATE: "",
    };
    me.option_List = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModelPatarn = [
        {
            name: "PATTERN_NM",
            label: "パターン名",
            index: "PATTERN_NM",
            width: 320,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "30",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            //enter
                            if (key == 13) {
                                //シート名をﾊﾟﾀｰﾝ名に入力された値に変更
                                $(me.grid_Patarn).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                me.changeTabNM();
                                $(me.grid_Patarn).jqGrid(
                                    "setSelection",
                                    me.lastsel,
                                    true
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            //--20150819	Yuanjh ADD S.
                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                                return;
                            }
                            //DOWN && TAB

                            if (key == 9 && !e.shiftKey) {
                                var selIRow = parseInt(me.lastsel) + 1;
                                var getDataCount = $(me.grid_Patarn).jqGrid(
                                    "getGridParam",
                                    "records"
                                );
                                if (selIRow == getDataCount) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }
                                $(me.grid_Patarn).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                $(me.grid_Patarn).jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                tabsList.tabs("option", "active", selIRow);
                                me.scrollLocation(selIRow);

                                var selNextId = "#" + selIRow + "_PATTERN_NM";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            //UP && TAB+SHIFT
                            if (key == 9 && e.shiftKey) {
                                var selIRow = parseInt(me.lastsel) - 1;
                                var getDataCount = $(me.grid_Patarn).jqGrid(
                                    "getGridParam",
                                    "records"
                                );
                                if (selIRow == -1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }
                                if (selIRow == getDataCount) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }
                                $(me.grid_Patarn).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );
                                $(me.grid_Patarn).jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                tabsList.tabs("option", "active", selIRow);
                                me.scrollLocation(selIRow);

                                var selNextId = "#" + selIRow + "_PATTERN_NM";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            if (key == 222) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                            //----20050819 Yuanjh ADD E.
                        },
                    },
                ],
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    me.colModelList = [
        {
            name: "ADD_FLAG",
            label: "追加",
            index: "ADD_FLAG",
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
            name: "PRINT_NO",
            label: "印刷順",
            index: "PRINT_NO",
            width: 52,
            align: "right",
            sortable: false,
            editable: true,
            editoptions: {
                class: "numeric",
                maxlength: "3",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;

                            //enter and tab
                            if (key == 229) {
                                e.preventDefault();
                                e.stopPropagation();
                            } else if (key == 48 || key == 96) {
                                var inputValue = $(e.target).val();

                                if (inputValue == "0") {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            } else if (key == 9 && !e.shiftKey) {
                                //Tab
                                var selIRow = parseInt(me.lastselList) + 1;
                                var getDataCount = $(me.grid_sprList).jqGrid(
                                    "getGridParam",
                                    "records"
                                );

                                if (selIRow == getDataCount) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }

                                $(me.grid_sprList).jqGrid(
                                    "saveRow",
                                    me.lastselList,
                                    null,
                                    "clientArray"
                                );
                                $(me.grid_sprList).jqGrid(
                                    "setSelection",
                                    parseInt(me.lastselList) + 1,
                                    true
                                );
                                e.preventDefault();
                                e.stopPropagation();
                            } else if (key == 9 && e.shiftKey) {
                                //up
                                var selIRow = parseInt(me.lastselList) - 1;

                                if (selIRow == -1) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    return;
                                }
                                $(me.grid_sprList).jqGrid(
                                    "saveRow",
                                    me.lastselList,
                                    null,
                                    "clientArray"
                                );
                                $(me.grid_sprList).jqGrid(
                                    "setSelection",
                                    selIRow,
                                    true
                                );

                                var selNextId = "#" + selIRow + "_PRINT_NO";
                                $(selNextId).trigger("focus");
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 70,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            width: 250,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
    ];

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKeieiSeikaPatternMst.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKeieiSeikaPatternMst.cmdCopy",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKeieiSeikaPatternMst.cmdDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKeieiSeikaPatternMst.cmdAction",
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

    $(".FrmKeieiSeikaPatternMst.txtPrintOrder").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 229) {
            return false;
        }
    });

    $(".FrmKeieiSeikaPatternMst.txtBusyoCD").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 222) {
            return false;
        }
    });

    $(".FrmKeieiSeikaPatternMst.txtBusyoCD").on("blur", function () {
        $(me.grid_sprList).jqGrid(
            "saveRow",
            me.lastselList,
            null,
            "clientArray"
        );

        $(".FrmKeieiSeikaPatternMst.lblBusyoNM").val("");
        var busyoCD = $(".FrmKeieiSeikaPatternMst.txtBusyoCD").val().trimEnd();

        if (busyoCD != "") {
            var url = me.id + "/fncGetBusyoMstValue";
            var data = {
                Busyo_CD: busyoCD,
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    if (result["data"]["intRtnCD"] == "1") {
                        $(".FrmKeieiSeikaPatternMst.lblBusyoNM").val(
                            result["data"]["strBusyoNM"]
                        );

                        // 入力された部署ｺｰﾄﾞと同一のﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞの行にﾁｪｯｸを入れる
                        var rowID = $(me.grid_sprList).jqGrid("getDataIDs");

                        for (key in rowID) {
                            var rowData = $(me.grid_sprList).jqGrid(
                                "getRowData",
                                rowID[key]
                            );

                            if (busyoCD == rowData["BUSYO_CD"].trimEnd()) {
                                rowData["ADD_FLAG"] = "YES";
                                $(me.grid_sprList).jqGrid(
                                    "setRowData",
                                    rowID[key],
                                    rowData
                                );
                                $(me.grid_sprList).jqGrid(
                                    "setSelection",
                                    rowID[key]
                                );

                                setTimeout(() => {
                                    $(
                                        ".FrmKeieiSeikaPatternMst.txtPrintOrder"
                                    ).removeAttr("disabled");

                                    // 印刷順にﾌｫｰｶｽ移動
                                    $(
                                        ".FrmKeieiSeikaPatternMst.txtPrintOrder"
                                    ).trigger("select");
                                }, 0);
                                return;
                            }
                        }
                    }

                    // 部署ｺｰﾄﾞにﾌｫｰｶｽ移動
                    $(".FrmKeieiSeikaPatternMst.txtPrintOrder").attr(
                        "disabled",
                        "disabled"
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);

                    // 部署ｺｰﾄﾞにﾌｫｰｶｽ移動
                    $(".FrmKeieiSeikaPatternMst.txtPrintOrder").attr(
                        "disabled",
                        "disabled"
                    );

                    return;
                }
            };
            me.ajax.send(url, data, 0);
        }
    });

    $(".FrmKeieiSeikaPatternMst.txtPrintOrder").on("blur", function () {
        $(me.grid_sprList).jqGrid(
            "saveRow",
            me.lastselList,
            null,
            "clientArray"
        );

        //入力された部署ｺｰﾄﾞと同一のﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞの行にﾁｪｯｸを入れる
        var rowID = $(me.grid_sprList).jqGrid("getDataIDs");
        var busyoCD = $(".FrmKeieiSeikaPatternMst.txtBusyoCD").val().trimEnd();

        for (key in rowID) {
            var rowData = $(me.grid_sprList).jqGrid("getRowData", rowID[key]);

            if (busyoCD == rowData["BUSYO_CD"].trimEnd()) {
                if ($(".FrmKeieiSeikaPatternMst.txtPrintOrder").val() == "") {
                    rowData["PRINT_NO"] = "0";
                } else {
                    rowData["PRINT_NO"] = $(
                        ".FrmKeieiSeikaPatternMst.txtPrintOrder"
                    ).val();
                }

                $(me.grid_sprList).jqGrid("setRowData", rowID[key], rowData);
                $(me.grid_sprList).jqGrid("setSelection", rowID[key]);
                var selNextId = "#" + rowID[key] + "_PRINT_NO";
                $(selNextId).trigger("focus");
                return;
            }
        }
    });

    $(".FrmKeieiSeikaPatternMst.cmdInsert").click(function () {
        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //ﾊﾟﾀｰﾝ名ｽﾌﾟﾚｯﾄﾞに1行追加
        //追加した行をアクティブにする
        me.addPattern["PATTERN_NM"] = "";
        $(me.grid_Patarn).jqGrid("addRowData", me.rowCountLoad, me.addPattern);

        //***新しいシートを作成***
        me.addOneTab();

        //ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞのアクティブシートを今回追加したシートに設定
        tabsList.tabs("option", "active", me.rowCountLoad);
        me.scrollLocation(me.rowCountLoad);
        me.rowCountLoad += 1;

        $(".FrmKeieiSeikaPatternMst.cmdDelete").button("enable");
    });

    $(".FrmKeieiSeikaPatternMst.cmdCopy").click(function () {
        me.copyFlag = true;

        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");

        me.addPattern["PATTERN_NM"] = "";
        $(me.grid_Patarn).jqGrid("addRowData", me.rowCountLoad, me.addPattern);

        //***新しいシートを作成***
        me.addOneTab();
        me.firstSprListData[me.rowCountLoad] = me.firstSprListData[me.lastsel];
        tabsList.tabs("option", "active", me.rowCountLoad);
        me.scrollLocation(me.rowCountLoad);
        me.rowCountLoad += 1;

        $(".FrmKeieiSeikaPatternMst.cmdDelete").button("enable");
    });

    $(".FrmKeieiSeikaPatternMst.cmdDelete").click(function () {
        me.bFlagDelete = true;

        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");
        $(me.grid_sprList).jqGrid(
            "saveRow",
            me.lastselList,
            null,
            "clientArray"
        );

        //ﾊﾟﾀｰﾝｽﾌﾟﾚｯﾄﾞが1行になった場合は1行を削除するのではなく残して値をｸﾘｱする
        var datas = $(me.grid_Patarn).jqGrid("getDataIDs");

        if (datas.length == 1) {
            //新しいシート固有の設定
            var newName = "Sheet1";
            var tabID = me.getTabID();
            $("#Sheet_" + tabID).html(newName);

            me.addPattern["PATTERN_NM"] = newName;
            $(me.grid_Patarn).jqGrid(
                "setRowData",
                parseInt(me.lastsel),
                me.addPattern
            );

            // 新しいシートを作成
            // ﾚｲｱｳﾄｽﾌﾟﾚｯﾄﾞをコピー
            for (var i = 0; i < me.newData.length; i++) {
                //部署ｺｰﾄﾞを選択する
                me.newData[i]["ADD_FLAG"] = "NO";
                //印刷順をセット
                me.newData[i]["PRINT_NO"] = "";
                //作成日をセット
                me.newData[i]["CREATE_DATE"] = "";

                $(me.grid_sprList).jqGrid("setRowData", i, me.newData[i]);
            }

            $(".FrmKeieiSeikaPatternMst.cmdDelete").button("disable");
            $(".FrmKeieiSeikaPatternMst.txtBusyoCD").trigger("focus");
            me.rowCountLoad = 1;
        } else {
            //ﾊﾟﾀｰﾝｽﾌﾟﾚｯﾄﾞからアクティブになっている行を削除する
            //リストｽﾌﾟﾚｯﾄﾞからアクティブになっているシートを削除する
            me.deleteTabData();

            //アクティブシートを設定する
            var selTabIndex = parseInt(me.lastsel) + 1;

            if (me.lastsel == 0) {
                selTabIndex = 0;
            }

            //ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞのアクティブシートを今回追加したシートに設定
            tabsList.tabs("option", "active", selTabIndex);

            var datas = $(me.grid_Patarn).jqGrid("getDataIDs");

            if (datas.length == selTabIndex) {
                selTabIndex -= 1;
            }
            me.scrollLocation(selTabIndex);
            me.rowCountLoad -= 1;
        }

        me.bFlagDelete = false;
    });

    $(".FrmKeieiSeikaPatternMst.cmdAction").click(function () {
        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");
        $(me.grid_sprList).jqGrid(
            "saveRow",
            me.lastselList,
            null,
            "clientArray"
        );

        me.saveLastTabData(false);

        //ﾊﾟﾀｰﾝ名入力ﾁｪｯｸ
        if (!me.fncPatternNameChk()) {
            return;
        }
        //登録確認ﾒｯｾｰｼﾞを表示する
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteUpdataMst;
        me.clsComFnc.FncMsgBox("QY010");
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

        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });

        me.fncGetPatarnData();
    };

    me.fncGetPatarnData = function () {
        //部署ﾃﾞｰﾀﾘｰﾀﾞの作成
        //ﾚｲｱｳﾄｽﾌﾟﾚｯﾄﾞに元になる部署を設定
        //ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞに元になる部署を1シート目のみ初期設定(2シート目からはﾚｲｱｳﾄｽﾌﾟﾚｯﾄﾞからコピーして使用)
        var url = me.id + "/fncBusyoListSel";

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                gdmz.common.jqgrid.init(
                    me.grid_Patarn,
                    "",
                    me.colModelPatarn,
                    "",
                    "",
                    me.option_List
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_Patarn, 360);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_Patarn,
                    me.ratio === 1.5 ? 200 : 250
                );

                //エラー場合、コントロール状態変更
                me.subErrorShow();

                //ﾊﾟﾀｰﾝﾘｽﾄマスタにデータが存在しない場合
                if (bErrorFlag == "nodata") {
                    MessageBox.MessageBox(
                        "部署マスタが登録されていません。",
                        "HMReports",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                }

                return;
            } else {
                //ﾊﾟﾀｰﾝ名ﾃﾞｰﾀﾘｰﾀﾞの作成
                //ﾊﾟﾀｰﾝ名ｽﾌﾟﾚｯﾄﾞにﾊﾟﾀｰﾝ名を表示
                url = me.id + "/fncPatternNMSel";

                me.complete_Patarn = function (bErrorFlag) {
                    if (bErrorFlag == "error") {
                        //エラー場合、コントロール状態変更
                        me.subErrorShow();
                        return;
                    }

                    var rowArray = $(me.grid_Patarn).jqGrid(
                        "getGridParam",
                        "records"
                    );
                    me.firstPatternData = $(me.grid_Patarn).jqGrid(
                        "getRowData"
                    );
                    me.rowCountLoad = rowArray;
                    //----20151120   Yuanjh   ADD  S.
                    for (var i = 0; i < rowArray; i++) {
                        me.arrInputFlg[i] = "E999";
                    }
                    //----20151120   Yuanjh   ADD  E.

                    if (rowArray == 0) {
                        me.rowCountLoad += 1;
                        me.addPattern["PATTERN_NM"] = "Sheet1";
                        $(me.grid_Patarn).jqGrid(
                            "addRowData",
                            0,
                            me.addPattern
                        );
                    }

                    //ﾊﾟﾀｰﾝ名分、ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞにシートを追加する資源
                    me.addTabs();

                    if (rowArray == 0) {
                        $(".FrmKeieiSeikaPatternMst.tabsUI").css(
                            "width",
                            me.divWidth + 50
                        );
                        $(".FrmKeieiSeikaPatternMst.tabsList").css(
                            "width",
                            me.divWidth + 60
                        );
                    }

                    var rowID = $(me.grid_sprList).jqGrid("getDataIDs");

                    for (key in rowID) {
                        var rowData = $(me.grid_sprList).jqGrid(
                            "getRowData",
                            rowID[key]
                        );
                        me.newData.push(rowData);
                    }

                    //***ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞにﾊﾟﾀｰﾝﾘｽﾄの値を設定していく***
                    //ﾊﾟﾀｰﾝﾘｽﾄ用ﾃﾞｰﾀﾘｰﾀﾞの作成
                    if (rowArray != 0) {
                        me.setPatternList();
                    }

                    me.fncCompleteDeal();
                    me.fncCompleteDealList();

                    var bFlagLoad = true;

                    tabsList.tabs({
                        activate: function (_event, ui) {
                            me.ShowLoading();

                            // tabsList.tabs('paging',
                            // {
                            // cycle : false,
                            // follow : true,
                            // tabsPerPage : 3,
                            // nextButton : 'next▶',
                            // prevButton : '◀prev',
                            // activeOnAdd : true,
                            // followOnActive : true
                            // });

                            var mytabindex = ui.newTab.index();

                            $(me.grid_Patarn).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            $(me.grid_sprList).jqGrid(
                                "saveRow",
                                me.lastselList,
                                null,
                                "clientArray"
                            );

                            if (!me.bFlagDelete) {
                                me.saveLastTabData(bFlagLoad);
                            }

                            if (me.copyFlag) {
                                me.setTabData(me.lastsel);
                                me.copyFlag = false;
                            } else {
                                me.setTabData(mytabindex);
                                //----20151120   Yuanjh   ADD  S.
                                me.arrInputFlg[mytabindex] = mytabindex;
                                //----20151120   Yuanjh   ADD  E.
                            }

                            $(me.grid_sprList).jqGrid("setSelection", 0, true);
                            $(me.grid_Patarn).jqGrid(
                                "setSelection",
                                mytabindex,
                                true
                            );

                            bFlagLoad = false;

                            me.CloseLoading();
                        },
                    });

                    if (rowArray == 0) {
                        //１行目を選択状態にする
                        tabsList.tabs("option", "active", 0);
                    }
                };

                gdmz.common.jqgrid.showWithMesg(
                    me.grid_Patarn,
                    url,
                    me.colModelPatarn,
                    "",
                    "",
                    me.option_List,
                    "",
                    me.complete_Patarn
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_Patarn, 400);
                //---20150817 Yuanjh modify S.
                //gdmz.common.jqgrid.set_grid_height(me.grid_Patarn, 265);
                //---20150827 li UPD S.
                //gdmz.common.jqgrid.set_grid_height(me.grid_Patarn, 455);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_Patarn,
                    me.ratio === 1.5 ? 250 : 300
                );
                //---20150827 li UPD E.
                //---20150817 Yuanjh modify E.
                //20150820	Yuanjh ADD S.
                $(me.grid_Patarn).jqGrid("bindKeys");
                //20150820	Yuanjh ADD E.
            }
        };

        gdmz.common.jqgrid.showWithMesg(
            me.grid_sprList,
            url,
            me.colModelList,
            "",
            "",
            me.option_List,
            "",
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_sprList, 500);
        //---20150817 Yuanjh modify S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_sprList, 340);
        //---20150827 li UPD S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_sprList, 515);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_sprList,
            me.ratio === 1.5 ? 311 : 365
        );
        //---20150827 li UPD E.
        //---20150817 Yuanjh modify E.
        //20150820	Yuanjh ADD S.
        $(me.grid_sprList).jqGrid("bindKeys");
        //20150820	Yuanjh ADD E.
    };

    me.ShowLoading = function () {
        $.blockUI({
            css: {
                border: "none",
                padding: "10px",
                backgroundColor: "#fff",
                "-webkit-border-radius": "8px",
                "-moz-border-radius": "8px",
                top: "45%",
                left: "40%",
                color: "#000",
                width: "200px",
            },
            message:
                '<img src="img/1.gif" width="64" height="64" /><br /><B>読み込み中...</B>',
        });
    };

    me.CloseLoading = function () {
        $.unblockUI();
    };

    me.subErrorShow = function () {
        $(me.grid_Patarn).closest(".ui-jqgrid").block();
        $(me.grid_sprList).closest(".ui-jqgrid").block();

        $(".FrmKeieiSeikaPatternMst.txtBusyoCD").attr("disabled", "disabled");
        $(".FrmKeieiSeikaPatternMst.txtPrintOrder").attr(
            "disabled",
            "disabled"
        );

        $(".FrmKeieiSeikaPatternMst.cmdInsert").button("disable");
        $(".FrmKeieiSeikaPatternMst.cmdCopy").button("disable");
        $(".FrmKeieiSeikaPatternMst.cmdDelete").button("disable");
        $(".FrmKeieiSeikaPatternMst.cmdAction").button("disable");
    };

    me.addTabs = function () {
        var tabs_panels = "";
        var tabs_buttons = "";
        var tabs_style =
            "padding-top: 1px;padding-left: 10px;padding-bottom: 1px;padding-right: 10px";
        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in rowIDs) {
            var rowID = parseInt(rowIDs[key]);
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowID);

            if (rowID == 0) {
                rowData["PATTERN_NM"] =
                    me.clsComFnc.FncNv(rowData["PATTERN_NM"]) == ""
                        ? "Sheet1"
                        : rowData["PATTERN_NM"];
            }

            tabs_buttons +=
                '<li class="FrmKeieiSeikaPatternMst tabsLI" style="margin-right:0px" id=newLI_' +
                rowID +
                ">";
            tabs_buttons +=
                '<a href="#newtab_' +
                rowID +
                '" id="Sheet_' +
                rowID +
                '" style="' +
                tabs_style +
                '">' +
                rowData["PATTERN_NM"] +
                "</a>";
            tabs_buttons += "</li>";
            tabs_panels +=
                '<div id="newtab_' +
                rowID +
                '"' +
                'class="FrmKeieiSeikaPatternMst tabsPanel"';
            tabs_panels += "</div>";
        }

        var ul = tabsList.find("ul");
        $(tabs_buttons).appendTo(ul);
        $(tabs_panels).appendTo(tabsList);

        tabsList.tabs("refresh");

        $(".FrmKeieiSeikaPatternMst.tabsPanel").css("padding", "0px");
        $(".FrmKeieiSeikaPatternMst.tabsUI").removeClass("ui-corner-all");
        $(".FrmKeieiSeikaPatternMst.tabsLI").removeClass("ui-corner-top");
        $(".FrmKeieiSeikaPatternMst.tabsList").removeClass("ui-corner-all");

        //****scroll move****
        ul = tabsList.find("ul");
        me.widthSum = 0;

        for (var i = 1; i < ul[0].childNodes.length; i++) {
            me.widthSum += parseInt(ul[0].childNodes[i].clientWidth);
        }

        if (me.widthSum + 50 < me.divWidth) {
            me.widthSum = me.divWidth;
        }

        $(".FrmKeieiSeikaPatternMst.tabsUI").css("width", me.widthSum + 60);
        $(".FrmKeieiSeikaPatternMst.tabsList").css("width", me.widthSum + 80);
    };

    me.setPatternList = function () {
        var url = me.id + "/fncPatternListSel";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    //１行目を選択状態にする
                    tabsList.tabs("option", "active", 0);
                    return;
                }

                var j = 0;
                var intPatturnNo = "";
                var tmpArray = new Array();
                var jqGridCount = $(me.grid_Patarn).jqGrid("getDataIDs");
                var intSavePatturnNO =
                    parseInt(result["data"][0]["PATTERN_NO"]) - 1;
                for (var i = 0; i < result["data"].length; i++) {
                    var saveDataInfo = {};

                    //ﾊﾟﾀｰﾝ番号からﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞのシート番号を取得する
                    intPatturnNo =
                        parseInt(result["data"][i]["PATTERN_NO"]) - 1;
                    //ﾊﾟﾀｰﾝﾏｽﾀに登録されていない№のものが、ﾊﾟﾀｰﾝﾘｽﾄにあった場合はエラー
                    if (intPatturnNo > jqGridCount.length) {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "ﾊﾟﾀｰﾝﾘｽﾄにﾊﾟﾀｰﾝ名が存在しないデータが含まれています！"
                        );
                        $(".FrmKeieiSeikaPatternMst.tabsList")
                            .closest(".ui-tabs")
                            .block();
                        me.subErrorShow();
                        return;
                    } else if (intSavePatturnNO != intPatturnNo) {
                        me.arrInputData[intSavePatturnNO] = tmpArray;
                        me.firstSprListData[intSavePatturnNO] = tmpArray;
                        tmpArray = new Array();
                        //シートが変わった場合
                        intSavePatturnNO = intPatturnNo;
                    } else if (intPatturnNo == 0) {
                        for (; j < me.newData.length; j++) {
                            if (
                                result["data"][i]["BUSYO_CD"] ==
                                me.newData[j]["BUSYO_CD"]
                            ) {
                                //部署ｺｰﾄﾞを選択する
                                me.newData[j]["ADD_FLAG"] = "YES";
                                //印刷順をセット
                                me.newData[j]["PRINT_NO"] =
                                    result["data"][i]["PRINT_ORDER"] == "999"
                                        ? ""
                                        : result["data"][i]["PRINT_ORDER"];
                                //作成日をセット
                                me.newData[j]["CREATE_DATE"] =
                                    result["data"][i]["CREATE_DATE"].trimEnd();

                                $(me.grid_sprList).jqGrid(
                                    "setRowData",
                                    j,
                                    me.newData[j]
                                );

                                j += 1;
                                break;
                            }
                        }
                    }

                    saveDataInfo["ADD_FLAG"] = "YES";
                    saveDataInfo["BUSYO_CD"] = result["data"][i]["BUSYO_CD"];
                    saveDataInfo["PRINT_ORDER"] =
                        result["data"][i]["PRINT_ORDER"] == "999"
                            ? ""
                            : result["data"][i]["PRINT_ORDER"];
                    saveDataInfo["CREATE_DATE"] =
                        result["data"][i]["CREATE_DATE"];

                    tmpArray.push(saveDataInfo);
                }

                me.arrInputData[intPatturnNo] = tmpArray;
                me.firstSprListData[intPatturnNo] = tmpArray;
                //１行目を選択状態にする
                tabsList.tabs("option", "active", 0);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $(".FrmKeieiSeikaPatternMst.tabsList")
                    .closest(".ui-tabs")
                    .block();
                me.subErrorShow();
                return;
            }
        };

        me.ajax.send(url, "", 1);
    };

    me.fncCompleteDeal = function () {
        $(me.grid_Patarn).jqGrid("setGridParam", {
            onSelectRow: function (rowId) {
                if (rowId && rowId !== me.lastsel) {
                    tabsList.tabs("option", "active", rowId);
                    me.scrollLocation(rowId);

                    $(me.grid_Patarn).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );

                    me.changeTabNM();
                    me.lastsel = rowId;
                    $(me.grid_Patarn).jqGrid("editRow", rowId, true);
                } else {
                    $(me.grid_Patarn).jqGrid("editRow", rowId, {
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
            },
        });
    };

    me.fncCompleteDealList = function () {
        $(me.grid_sprList).jqGrid("setGridParam", {
            onSelectRow: function (rowId) {
                if (rowId && rowId !== me.lastselList) {
                    $(me.grid_sprList).jqGrid(
                        "saveRow",
                        me.lastselList,
                        null,
                        "clientArray"
                    );
                    me.lastselList = rowId;
                    $(me.grid_sprList).jqGrid("editRow", rowId, true);
                } else {
                    $(me.grid_sprList).jqGrid("editRow", rowId, {
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
            },
        });
    };

    me.saveLastTabData = function (bFlagLoad) {
        if (bFlagLoad) {
            return false;
        }

        var tmpArray = new Array();
        var rowIDs = $(me.grid_sprList).jqGrid("getDataIDs");

        for (key in rowIDs) {
            var rowID = rowIDs[key];
            var tmpNewData = {};
            var rowData = $(me.grid_sprList).jqGrid("getRowData", rowID);

            if (
                rowData["ADD_FLAG"].toUpperCase() == "YES" ||
                rowData["PRINT_NO"] != ""
            ) {
                //Flag
                tmpNewData["ADD_FLAG"] = rowData["ADD_FLAG"];
                //部署ｺｰﾄﾞを選択する
                tmpNewData["BUSYO_CD"] = rowData["BUSYO_CD"];
                //印刷順をセット
                tmpNewData["PRINT_ORDER"] = rowData["PRINT_NO"];
                //作成日をセット
                tmpNewData["CREATE_DATE"] = rowData["CREATE_DATE"];

                tmpArray.push(tmpNewData);
            } else if (rowData["CREATE_DATE"] != "") {
                //Flag
                tmpNewData["ADD_FLAG"] = "NO";
                //部署ｺｰﾄﾞを選択する
                tmpNewData["BUSYO_CD"] = rowData["BUSYO_CD"];
                //印刷順をセット
                tmpNewData["PRINT_ORDER"] = "";
                //作成日をセット
                tmpNewData["CREATE_DATE"] = rowData["CREATE_DATE"];

                tmpArray.push(tmpNewData);
            }
        }

        if (tmpArray.length != 0) {
            me.arrInputData[me.lastsel] = new Array();
            me.arrInputData[me.lastsel] = tmpArray;
        } else if (me.arrInputData[me.lastsel] != undefined) {
            if (me.arrInputData[me.lastsel].length != 0) {
                me.delInputData();
            }
        }

        return true;
    };

    me.delInputData = function () {
        var rowNum = parseInt(me.lastsel);
        var newInputArray = new Array();

        for (var i = 0; i < me.arrInputData.length; i++) {
            if (rowNum != i && me.arrInputData[i] != undefined) {
                newInputArray[i] = me.arrInputData[i];
            }
        }

        me.arrInputData = new Array();
        me.arrInputData = newInputArray;
    };

    me.setTabData = function (tabIndex) {
        if (me.arrInputData[tabIndex] == undefined) {
            for (var i = 0; i < me.newData.length; i++) {
                //部署ｺｰﾄﾞを選択する
                me.newData[i]["ADD_FLAG"] = "NO";
                //印刷順をセット
                me.newData[i]["PRINT_NO"] = "";
                //作成日をセット
                me.newData[i]["CREATE_DATE"] = "";

                $(me.grid_sprList).jqGrid("setRowData", i, me.newData[i]);
            }

            return false;
        }

        var iNo = 0;

        for (var i = 0; i < me.newData.length; i++) {
            if (iNo < me.arrInputData[tabIndex].length) {
                if (
                    me.arrInputData[tabIndex][iNo]["BUSYO_CD"] ==
                    me.newData[i]["BUSYO_CD"]
                ) {
                    //部署ｺｰﾄﾞを選択する
                    me.newData[i]["ADD_FLAG"] =
                        me.arrInputData[tabIndex][iNo]["ADD_FLAG"];
                    //印刷順をセット
                    me.newData[i]["PRINT_NO"] =
                        me.arrInputData[tabIndex][iNo]["PRINT_ORDER"];
                    //作成日をセット
                    if (!me.copyFlag) {
                        me.newData[i]["CREATE_DATE"] =
                            me.arrInputData[tabIndex][iNo]["CREATE_DATE"];
                    } else {
                        me.newData[i]["CREATE_DATE"] = "";
                    }

                    iNo += 1;
                } else {
                    //部署ｺｰﾄﾞを選択する
                    me.newData[i]["ADD_FLAG"] = "NO";
                    //印刷順をセット
                    me.newData[i]["PRINT_NO"] = "";
                    //作成日をセット
                    me.newData[i]["CREATE_DATE"] = "";
                }
            } else {
                //部署ｺｰﾄﾞを選択する
                me.newData[i]["ADD_FLAG"] = "NO";
                //印刷順をセット
                me.newData[i]["PRINT_NO"] = "";
                //作成日をセット
                me.newData[i]["CREATE_DATE"] = "";
            }

            $(me.grid_sprList).jqGrid("setRowData", i, me.newData[i]);
        }

        return true;
    };

    me.changeTabNM = function () {
        var changeRowID = me.lastsel;
        var rowData = $(me.grid_Patarn).jqGrid("getRowData", changeRowID);
        var newName =
            me.clsComFnc.FncNv(rowData["PATTERN_NM"]) == ""
                ? "Sheet" + (parseInt(changeRowID) + 1)
                : rowData["PATTERN_NM"];
        var tabID = me.getTabID();
        $("#Sheet_" + tabID).html(newName);

        rowData["PATTERN_NM"] = newName;
        $(me.grid_Patarn).jqGrid("setRowData", changeRowID, rowData);

        //****scroll move****
        me.widthSum = 0;
        var ul = tabsList.find("ul");

        for (var i = 1; i < ul[0].childNodes.length; i++) {
            me.widthSum += parseInt(ul[0].childNodes[i].clientWidth);
        }

        if (me.widthSum + 50 < me.divWidth) {
            me.widthSum = me.divWidth;
        }

        $(".FrmKeieiSeikaPatternMst.tabsUI").css("width", me.widthSum + 60);
        $(".FrmKeieiSeikaPatternMst.tabsList").css("width", me.widthSum + 80);
    };

    me.addOneTab = function () {
        var li = $(".FrmKeieiSeikaPatternMst.tabsUI").find("li");
        var rowCount =
            parseInt(
                li[li.length - 1].id.substring(
                    li[li.length - 1].id.indexOf("_") + 1
                )
            ) + 1;
        var tabs_panels = "";
        var tabs_buttons = "";
        var tabs_style =
            "padding-top: 1px;padding-left: 10px;padding-bottom: 1px;padding-right: 10px";
        // var rowCount = parseInt(me.rowCountLoad);

        tabs_buttons +=
            '<li class="FrmKeieiSeikaPatternMst tabsLI" style="margin-right:0px" id=newLI_' +
            rowCount +
            ">";
        tabs_buttons +=
            '<a href="#newtab_' +
            rowCount +
            '" id="Sheet_' +
            rowCount +
            '" style="' +
            tabs_style +
            '"></a>';
        tabs_buttons += "</li>";
        tabs_panels +=
            '<div id="newtab_' +
            rowCount +
            '"' +
            'class="FrmKeieiSeikaPatternMst tabsPanel"';
        tabs_panels += "</div>";

        var ul = tabsList.find("ul");
        $(tabs_buttons).appendTo(ul);
        $(tabs_panels).appendTo(tabsList);

        tabsList.tabs("refresh");

        $(".FrmKeieiSeikaPatternMst.tabsPanel").css("padding", "0px");
        $(".FrmKeieiSeikaPatternMst.tabsUI").removeClass("ui-corner-all");
        $(".FrmKeieiSeikaPatternMst.tabsLI").removeClass("ui-corner-top");
        $(".FrmKeieiSeikaPatternMst.tabsList").removeClass("ui-corner-all");

        var newName = "Sheet" + (parseInt(rowCount) + 1);
        $("#Sheet_" + rowCount).html(newName);

        me.addPattern["PATTERN_NM"] = newName;
        $(me.grid_Patarn).jqGrid("setRowData", me.rowCountLoad, me.addPattern);
    };

    me.deleteTabData = function () {
        // me.delInputData();
        me.delRowDataContent(parseInt(me.lastsel));

        var tabID = me.getTabID();

        $("#Sheet_" + parseInt(tabID)).remove();
        $("#newLI_" + parseInt(tabID)).remove();
        tabsList.tabs("refresh");
    };

    me.getTabID = function () {
        var tabID = -1;
        var li = $(".FrmKeieiSeikaPatternMst.tabsUI").find("li");

        for (var i = 0; i < li.length; i++) {
            if (i == me.lastsel) {
                tabID = li[i].id.substring(li[i].id.indexOf("_") + 1);
                break;
            }
        }

        return tabID;
    };

    me.delRowDataContent = function (rowID) {
        var getDataID = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (var i = parseInt(rowID); i < getDataID.length - 1; i++) {
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", i + 1);
            $(me.grid_Patarn).jqGrid("setRowData", i, rowData);
        }

        $(me.grid_Patarn).jqGrid("delRowData", getDataID.length - 1);

        var newInputArray = new Array();

        for (var i = 0; i < me.arrInputData.length; i++) {
            if (i < rowID && me.arrInputData[i] != undefined) {
                newInputArray[i] = me.arrInputData[i];
            } else if (i > rowID && me.arrInputData[i] != undefined) {
                newInputArray[i - 1] = me.arrInputData[i];
            }
        }

        me.arrInputData = new Array();
        me.arrInputData = newInputArray;
        if (me.firstPatternData.length - 1 >= parseInt(rowID)) {
            me.firstPatternData.splice(parseInt(rowID), 1);
        }
    };

    me.scrollLocation = function (iTabIndex) {
        var scrollWidth = 0;
        var ul = tabsList.find("ul");

        for (var i = 1; i <= parseInt(iTabIndex); i++) {
            scrollWidth += parseInt(ul[0].childNodes[i].clientWidth);
        }

        $(".FrmKeieiSeikaPatternMst.tabScroll").scrollLeft(scrollWidth - 100);
    };

    me.fncPatternNameChk = function () {
        var rowNum = 0;
        var rowComNum = 0;
        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in rowIDs) {
            rowNum += 1;
            var rowID = rowIDs[key];
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowID);
            var intRtn = me.clsComFnc.FncSprCheck(
                rowData["PATTERN_NM"],
                1,
                me.clsComFnc.INPUTTYPE.NONE,
                40
            );

            if (intRtn != 0) {
                me.setFocus(
                    "W000" + intRtn * -1,
                    me.colModelPatarn[0]["label"],
                    rowID,
                    false,
                    0
                );
                return false;
            }

            //ﾊﾟﾀｰﾝ名の重複ﾁｪｯｸ
            if (parseInt(rowID) <= parseInt(rowIDs[rowIDs.length - 2])) {
                rowComNum = rowNum;

                for (
                    var j = parseInt(rowIDs[parseInt(key) + 1]);
                    j <= parseInt(rowIDs[rowIDs.length - 1]);
                    j++
                ) {
                    rowComNum += 1;
                    var rowDataCompare = $(me.grid_Patarn).jqGrid(
                        "getRowData",
                        rowIDs[j]
                    );

                    if (rowData["PATTERN_NM"] == rowDataCompare["PATTERN_NM"]) {
                        var row = j;
                        if (me.firstPatternData.length - 1 >= rowNum - 1) {
                            if (
                                me.firstPatternData[rowNum - 1][
                                    "PATTERN_NM"
                                ] !== rowData["PATTERN_NM"]
                            ) {
                                var row = rowNum - 1;
                            }
                        }
                        me.setFocus(
                            "W9999",
                            rowNum +
                                "行目と" +
                                rowComNum +
                                "行目のデータが重複しています。",
                            rowIDs[row],
                            false,
                            0
                        );
                        return false;
                    }
                }
            }

            //ﾊﾟﾀｰﾝﾘｽﾄ・印刷順の重複ﾁｪｯｸ
            if (me.arrInputData[parseInt(rowID)] != undefined) {
                var rowListIDs = $(me.grid_sprList).jqGrid("getDataIDs");

                for (
                    var i = 0;
                    i < me.arrInputData[parseInt(rowID)].length;
                    i++
                ) {
                    var addFlag =
                        me.arrInputData[parseInt(rowID)][i]["ADD_FLAG"];
                    var printNO =
                        me.arrInputData[parseInt(rowID)][i]["PRINT_ORDER"];

                    if (addFlag.toUpperCase() == "NO" && printNO != "") {
                        var iNo = 0;

                        for (var k = 0; k < rowListIDs.length; k++) {
                            var rowListData = $(me.grid_sprList).jqGrid(
                                "getRowData",
                                k
                            );

                            if (
                                rowListData["BUSYO_CD"] ==
                                me.arrInputData[parseInt(rowID)][i]["BUSYO_CD"]
                            ) {
                                iNo = k;
                                break;
                            }
                        }

                        me.setFocus(
                            "W9999",
                            "印刷順を入力した場合、追加にチェックをしてください。",
                            rowID,
                            true,
                            parseInt(iNo)
                        );
                        return false;
                    }
                }

                for (
                    var i = 0;
                    i < me.arrInputData[parseInt(rowID)].length - 1;
                    i++
                ) {
                    for (
                        var j = i + 1;
                        j < me.arrInputData[parseInt(rowID)].length;
                        j++
                    ) {
                        if (
                            me.arrInputData[parseInt(rowID)][i][
                                "PRINT_ORDER"
                            ] != "" ||
                            me.arrInputData[parseInt(rowID)][j][
                                "PRINT_ORDER"
                            ] != ""
                        ) {
                            if (
                                me.arrInputData[parseInt(rowID)][i][
                                    "PRINT_ORDER"
                                ] ==
                                me.arrInputData[parseInt(rowID)][j][
                                    "PRINT_ORDER"
                                ]
                            ) {
                                var iNo = 0;
                                var iComNo = 0;

                                for (var k = 0; k < rowListIDs.length; k++) {
                                    var rowListData = $(me.grid_sprList).jqGrid(
                                        "getRowData",
                                        k
                                    );

                                    if (
                                        rowListData["BUSYO_CD"] ==
                                        me.arrInputData[parseInt(rowID)][i][
                                            "BUSYO_CD"
                                        ]
                                    ) {
                                        iNo = k;
                                    } else if (
                                        rowListData["BUSYO_CD"] ==
                                        me.arrInputData[parseInt(rowID)][j][
                                            "BUSYO_CD"
                                        ]
                                    ) {
                                        iComNo = k;
                                        break;
                                    }
                                }
                                var row = -1;

                                var arrayData =
                                    me.firstSprListData[parseInt(rowID)];
                                if (
                                    me.arrInputData[parseInt(rowID)][i][
                                        "CREATE_DATE"
                                    ] !== "" &&
                                    me.arrInputData[parseInt(rowID)][j][
                                        "CREATE_DATE"
                                    ] !== ""
                                ) {
                                    for (
                                        var index = 0;
                                        index < arrayData.length;
                                        index++
                                    ) {
                                        if (
                                            arrayData[index]["PRINT_ORDER"] !==
                                                me.arrInputData[
                                                    parseInt(rowID)
                                                ][i]["PRINT_ORDER"] &&
                                            arrayData[index]["BUSYO_CD"] ===
                                                me.arrInputData[
                                                    parseInt(rowID)
                                                ][i]["BUSYO_CD"]
                                        ) {
                                            row = iNo;
                                        } else if (
                                            arrayData[index]["PRINT_ORDER"] !==
                                                me.arrInputData[
                                                    parseInt(rowID)
                                                ][j]["PRINT_ORDER"] &&
                                            arrayData[index]["BUSYO_CD"] ===
                                                me.arrInputData[
                                                    parseInt(rowID)
                                                ][j]["BUSYO_CD"]
                                        ) {
                                            row = iComNo;
                                        }
                                    }
                                } else if (
                                    me.arrInputData[parseInt(rowID)][i][
                                        "CREATE_DATE"
                                    ] == "" &&
                                    me.arrInputData[parseInt(rowID)][j][
                                        "CREATE_DATE"
                                    ] !== ""
                                ) {
                                    row = iNo;
                                } else if (
                                    me.arrInputData[parseInt(rowID)][j][
                                        "CREATE_DATE"
                                    ] == "" &&
                                    me.arrInputData[parseInt(rowID)][i][
                                        "CREATE_DATE"
                                    ] !== ""
                                ) {
                                    row = iComNo;
                                } else {
                                    row = iNo;
                                }
                                me.setFocus(
                                    "W9999",
                                    parseInt(iNo) +
                                        1 +
                                        "行目と" +
                                        (parseInt(iComNo) + 1) +
                                        "行目のデータが重複しています。",
                                    rowID,
                                    true,
                                    parseInt(row)
                                );
                                return false;
                            }
                        }
                    }
                }
            }
        }

        return true;
    };

    me.setFocus = function (strMSGType, strMSG, rowID, bFlagList, iNo) {
        if (!bFlagList) {
            $(me.grid_sprList).jqGrid("setSelection", 0);
        }

        tabsList.tabs("option", "active", rowID);
        me.scrollLocation(rowID);
        $(me.grid_Patarn).jqGrid("setSelection", rowID);

        me.clsComFnc.ObjFocus = $("#" + rowID + "_PATTERN_NM");
        me.clsComFnc.ObjSelect = $("#" + rowID + "_PATTERN_NM");

        if (bFlagList) {
            $(me.grid_sprList).jqGrid("setSelection", iNo);

            me.clsComFnc.ObjFocus = $("#" + iNo + "_PRINT_NO");
            me.clsComFnc.ObjSelect = $("#" + iNo + "_PRINT_NO");
        }

        me.clsComFnc.FncMsgBox(strMSGType, strMSG);
    };

    me.fncDeleteUpdataMst = function () {
        var patternData = new Array();
        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in rowIDs) {
            var rowID = rowIDs[key];
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowID);
            patternData.push(rowData);
        }

        var url = me.id + "/fncDeleteUpdataMst";

        var sendData = {
            inputDatas: me.arrInputData,
            //---20151120   Yuanjh  ADD S.
            inputFlgs: me.arrInputFlg,
            //---20151120   Yuanjh  ADD E.
            patternData: patternData,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                //reload
                // me.delAllTabs();
                // me.fncGetPatarnData(false);

                $(".FrmKeieiSeikaPatternMst.txtBusyoCD").val("");
                $(".FrmKeieiSeikaPatternMst.lblBusyoNM").val("");
                $(".FrmKeieiSeikaPatternMst.txtPrintOrder").val("");
                $(".FrmKeieiSeikaPatternMst.txtPrintOrder").removeAttr(
                    "disabled"
                );

                tabsList.tabs("option", "active", 0);
                me.scrollLocation(0);
                me.firstPatternData = $(me.grid_Patarn).jqGrid("getRowData");
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.FncMsgBox("I0005");
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.delAllTabs = function () {
        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (var i = 0; i <= parseInt(rowIDs[rowIDs.length - 1]); i++) {
            $("#newLI_" + i).remove();
        }

        tabsList.tabs("refresh");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKeieiSeikaPatternMst = new R4.FrmKeieiSeikaPatternMst();
    o_R4_FrmKeieiSeikaPatternMst.load();
});
