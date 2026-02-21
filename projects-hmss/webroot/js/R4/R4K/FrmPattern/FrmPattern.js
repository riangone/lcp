/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078    					   BUG                              yin
 * 20150930           #2028   					   BUG                              LI
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmPattern");

R4.FrmPattern = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmPattern";
    me.grid_Patarn = "#FrmPattern_sprPatarn";
    me.grid_sprList = "#FrmPattern_sprProgramList";
    me.comboSysID = ".FrmPattern.UcComboBox1";
    me.lastsel = 0;
    me.lastselList = 0;
    me.bFlagSearch = true;
    // me.rowCountLoad = 0;
    //scroll count
    // me.widthSum = 0;
    me.divWidth = parseInt(
        $(".FrmPattern.tabScroll").css("width").replace("px", "")
    );
    var tabsList = $(".FrmPattern.tabsList").tabs();
    me.copyFlag = false;
    me.bFlagDelete = false;
    // me.newData = new Array();
    me.arrInputData = new Array();
    me.firstData = new Array();
    me.addPattern = {
        PATTERN_ID: "",
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
            name: "PATTERN_ID",
            label: "ＩＤ",
            index: "PATTERN_ID",
            width: 70,
            align: "left",
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
                            if (key == 229) {
                                return false;
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "PATTERN_NM",
            label: "パターン名",
            index: "PATTERN_NM",
            //---20150930 LI UPD S.
            //width : 280,
            width: 275,
            //---20150930 LI UPD E.
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                maxlength: "50",
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
            width: 45,
            align: "center",
            formatter: "checkbox",
            sortable: false,
            editable: false,
            formatoptions: {
                disabled: false,
            },
        },
        {
            name: "PRO_NM",
            label: "プログラム名",
            index: "PRO_NM",
            width: 320,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "PRO_NO",
            label: "ﾌﾟﾛｸﾞﾗﾑ№",
            index: "PRO_NO",
            hidden: true,
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
        id: ".FrmPattern.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmPattern.cmdCopy",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmPattern.cmdDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmPattern.cmdInput",
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
    $(".FrmPattern.UcComboBox1").change(function () {
        me.initSelect();

        var selectIndex = $(me.comboSysID + " option:selected").val();

        if (selectIndex == "") {
            $(".FrmPattern.cmdInsert").button("disable");
            $(".FrmPattern.cmdCopy").button("disable");
            $(".FrmPattern.cmdDelete").button("disable");
            $(".FrmPattern.cmdInput").button("disable");
        } else {
            //明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)にHPATTERNMSTから取得したﾃﾞｰﾀを表示する（【表示編集】を参照）
            var data = {
                STYLE_ID: selectIndex,
            };

            me.complete_fun = function (bErrorFlag) {
                if (bErrorFlag == "error") {
                    //エラー場合、コントロール状態変更
                    me.subErrorShow();
                    return;
                }

                //明細情報(ﾚｲｱｳﾄ情報)をコピーする
                me.complete_funList = function (bErrorFlag) {
                    if (bErrorFlag != "normal") {
                        //エラー場合、コントロール状態変更
                        me.subErrorShow();

                        //ﾊﾟﾀｰﾝﾘｽﾄマスタにデータが存在しない場合
                        if (bErrorFlag == "nodata") {
                            MessageBox.MessageBox(
                                "メニュー階層マスタが登録されていません。",
                                "HMReports",
                                "OK",
                                MessageBox.MessageBoxIcon.Warning
                            );
                        }

                        return;
                    }

                    //ﾃﾞｰﾀが存在している場合
                    var getDataID = $(me.grid_Patarn).jqGrid("getDataIDs");
                    me.firstData = $(me.grid_Patarn).jqGrid("getRowData");
                    if (getDataID.length == 0) {
                        me.addPattern["PATTERN_NM"] = "Sheet1";
                        $(me.grid_Patarn).jqGrid(
                            "addRowData",
                            0,
                            me.addPattern
                        );
                        // $(me.grid_Patarn).jqGrid('setRowData', 0, me.addPattern);

                        //削除ﾎﾞﾀﾝを非活性にする
                        $(".FrmPattern.cmdDelete").button("disable");
                    } else {
                        //削除ﾎﾞﾀﾝを活性にする
                        $(".FrmPattern.cmdDelete").button("enable");
                    }

                    //明細情報(ﾒﾆｭｰ管理ﾊﾟﾀｰﾝﾃｰﾌﾞﾙ)にHMENUKANRIPATTERNに登録されているプログラムにﾁｪｯｸを入れる
                    me.SetPatterCheck();

                    //追加ﾎﾞﾀﾝ、ｺﾋﾟｰﾎﾞﾀﾝ、登録ﾎﾞﾀﾝを活性にする
                    $(".FrmPattern.cmdInsert").button("enable");
                    $(".FrmPattern.cmdCopy").button("enable");
                    $(".FrmPattern.cmdInput").button("enable");

                    me.fncCompleteDeal();

                    tabsList.tabs({
                        activate: function (_event, ui) {
                            // me.ShowLoading();
                            var mytabindex = ui.newTab.index();

                            if (!me.bFlagSearch) {
                                $(me.grid_Patarn).jqGrid(
                                    "saveRow",
                                    me.lastsel,
                                    null,
                                    "clientArray"
                                );

                                if (me.bFlagDelete == false) {
                                    me.saveLastTabData();
                                }

                                if (me.copyFlag) {
                                    me.setTabData(me.lastsel);
                                    me.copyFlag = false;
                                } else {
                                    me.setTabData(mytabindex);
                                }
                            }
                            $(me.grid_sprList).jqGrid("setSelection", 0, true);
                            $(me.grid_Patarn).jqGrid(
                                "setSelection",
                                mytabindex,
                                true
                            );

                            me.bFlagSearch = false;
                            // me.CloseLoading();
                        },
                    });
                };

                gdmz.common.jqgrid.reloadMessage(
                    me.grid_sprList,
                    data,
                    me.complete_funList
                );
            };

            //スプレッドに取得データをセットする
            gdmz.common.jqgrid.reloadMessage(
                me.grid_Patarn,
                data,
                me.complete_fun
            );
        }
    });

    $(".FrmPattern.cmdInsert").click(function () {
        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");
        //明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)に1行追加する
        me.addPattern["PATTERN_NM"] = "";
        var rowID = $(me.grid_Patarn).jqGrid("getDataIDs");
        $(me.grid_Patarn).jqGrid("addRowData", rowID.length, me.addPattern);

        //***新しいシートを作成***
        //ﾚｲｱｳﾄ情報のｼｰﾄをｺﾋﾟｰし、明細情報(ﾒﾆｭｰ管理ﾊﾟﾀｰﾝﾃｰﾌﾞﾙ)の末尾に追加する
        me.addOneTab(rowID.length);

        //ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞのアクティブシートを今回追加したシートに設定
        tabsList.tabs("option", "active", rowID.length);
        // me.scrollLocation(rowID);
        // me.rowCountLoad += 1;

        $(".FrmPattern.cmdDelete").button("enable");
    });

    $(".FrmPattern.cmdCopy").click(function () {
        me.copyFlag = true;

        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");

        me.addPattern["PATTERN_NM"] = "";
        var rowID = $(me.grid_Patarn).jqGrid("getDataIDs");
        $(me.grid_Patarn).jqGrid("addRowData", rowID.length, me.addPattern);

        //***新しいシートを作成***
        me.addOneTab(rowID.length);

        tabsList.tabs("option", "active", rowID.length);
        // me.scrollLocation(me.rowCountLoad);
        // me.rowCountLoad += 1;

        $(".FrmPattern.cmdDelete").button("enable");
    });

    $(".FrmPattern.cmdDelete").click(function () {
        me.bFlagDelete = true;

        //ﾊﾟﾀｰﾝｽﾌﾟﾚｯﾄﾞが1行になった場合は1行を削除するのではなく残して値をｸﾘｱする
        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var datas = $(me.grid_Patarn).jqGrid("getDataIDs");
        var dataslist = $(me.grid_sprList).jqGrid("getDataIDs");

        //明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)が1行の場合
        if (datas.length == 1) {
            me.arrInputData = new Array();

            //明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)をｸﾘｱする
            var newName = "Sheet1";
            var tabID = me.getTabID();
            $("#Sheet_" + tabID).html(newName);

            me.addPattern["PATTERN_NM"] = newName;
            $(me.grid_Patarn).jqGrid(
                "setRowData",
                parseInt(me.lastsel),
                me.addPattern
            );

            // 明細情報(ﾒﾆｭｰ管理ﾊﾟﾀｰﾝﾃｰﾌﾞﾙ)をｸﾘｱする
            for (var i = 0; i < dataslist.length; i++) {
                var rowData = $(me.grid_sprList).jqGrid("getRowData", i);

                rowData["ADD_FLAG"] = "NO";
                rowData["CREATE_DATE"] = "";

                $(me.grid_sprList).jqGrid("setRowData", i, rowData);
            }

            //削除ﾎﾞﾀﾝを不活性にする
            $(".FrmPattern.cmdDelete").button("disable");
        } else {
            var selTabIndex = parseInt(me.lastsel);
            //明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)が2行以上存在する場合
            //①明細情報(ﾊﾟﾀｰﾝﾏｽﾀ)のｱｸﾃｨﾌﾞになっている行を削除する
            me.deleteTabData();

            //アクティブシートを設定する
            // if (selTabIndex == 0)
            // {
            // selTabIndex = 0;
            // }
            // else
            // {
            // selTabIndex = selTabIndex + 1;
            // }

            //ﾊﾟﾀｰﾝﾘｽﾄｽﾌﾟﾚｯﾄﾞのアクティブシートを今回追加したシートに設定
            tabsList.tabs("option", "active", selTabIndex);
        }

        me.bFlagDelete = false;
    });

    $(".FrmPattern.cmdInput").click(function () {
        //セル編集を停止します。
        $(me.grid_Patarn).jqGrid("saveRow", me.lastsel, null, "clientArray");
        me.saveLastTabData();

        //入力チェックを行う。
        if (!me.fncInputChk()) {
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
        me.fncGetPatarnData();
    };

    me.fncGetPatarnData = function () {
        var url = me.id + "/fncPatternSelect";
        var data = {
            STYLE_ID: "load",
        };

        me.complete_fun = function () {
            var urlList = me.id + "/fncPatternListSelect";
            var dataList = {
                STYLE_ID: "load",
            };

            me.complete_funList = function () {
                me.getMenuNM();
            };

            //スプレッドに取得データをセットする
            gdmz.common.jqgrid.showWithMesg(
                me.grid_sprList,
                urlList,
                me.colModelList,
                "",
                "",
                me.option_List,
                dataList,
                me.complete_funList
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_sprList, 440);
            //---20150930 LI UPD S.
            //gdmz.common.jqgrid.set_grid_height(me.grid_sprList, 390);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_sprList,
                me.ratio === 1.5 ? 301 : 370
            );
            //---20150930 LI UPD E.
            $(me.grid_sprList).jqGrid("bindKeys");
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_Patarn,
            url,
            me.colModelPatarn,
            "",
            "",
            me.option_List,
            data,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_Patarn, 420);
        //---20150930 LI UPD S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_Patarn, 350);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_Patarn,
            me.ratio === 1.5 ? 270 : 330
        );
        $(me.grid_Patarn).jqGrid("bindKeys");
        //---20150930 LI UPD E.
    };

    me.getMenuNM = function () {
        var url = me.id + "/fncHMENUSTYLESelect";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.setSelectValues(result["data"]);
            } else {
                me.clsComFnc.ObjFocus = $(me.comboSysID);
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }

            $(".FrmPattern.cmdInsert").button("disable");
            $(".FrmPattern.cmdCopy").button("disable");
            $(".FrmPattern.cmdDelete").button("disable");
            $(".FrmPattern.cmdInput").button("disable");
        };
        me.ajax.send(url, "", 1);
    };

    me.setSelectValues = function (arrResult) {
        $(me.comboSysID).empty();
        $("<option></option>").val("").text("").appendTo(me.comboSysID);

        for (key in arrResult) {
            if (arrResult[key]["STYLE_NM"] != "") {
                arrResult[key]["STYLE_NM"] = me.clsComFnc.fncGetFixVal(
                    arrResult[key]["STYLE_NM"],
                    18
                );
                $("<option></option>")
                    .val(arrResult[key]["STYLE_ID"])
                    .text(arrResult[key]["STYLE_NM"])
                    .appendTo(me.comboSysID);
            }
        }

        var tmpId = me.comboSysID + " option[value='" + "" + "']";
        $(tmpId).prop("selected", true);
        $(me.comboSysID).trigger("focus");
    };

    me.subErrorShow = function () {
        $(".FrmPattern.tabsUI").empty();
        $(me.grid_Patarn).jqGrid("clearGridData");
        $(me.grid_sprList).jqGrid("clearGridData");

        $(me.grid_Patarn).closest(".ui-jqgrid").block();
        $(me.grid_sprList).closest(".ui-jqgrid").block();
        $(".FrmPattern.tabsList").closest(".ui-tabs").block();

        $(".FrmPattern.cmdInsert").button("disable");
        $(".FrmPattern.cmdCopy").button("disable");
        $(".FrmPattern.cmdDelete").button("disable");
        $(".FrmPattern.cmdInput").button("disable");
    };

    me.addTabs = function () {
        var tabs_panels = "";
        var tabs_buttons = "";
        var tabs_style =
            "padding-top: 1px;padding-left: 10px;padding-bottom: 1px;padding-right: 10px";
        var getDataID = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in getDataID) {
            var rowID = parseInt(getDataID[key]);
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowID);

            rowData["PATTERN_NM"] =
                me.clsComFnc.FncNv(rowData["PATTERN_NM"]) == ""
                    ? "Sheet" + parseInt(rowID + 1)
                    : rowData["PATTERN_NM"];

            tabs_buttons +=
                '<li class="FrmPattern tabsLI" style="margin-right:0px" id=newLI_' +
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
                'class="FrmPattern tabsPanel"';
            tabs_panels += "</div>";
        }

        var ul = tabsList.find("ul");
        $(tabs_buttons).appendTo(ul);
        $(tabs_panels).appendTo(tabsList);

        tabsList.tabs("refresh");

        $(".FrmPattern.tabsPanel").css("padding", "0px");
        $(".FrmPattern.tabsUI").removeClass("ui-corner-all");
        $(".FrmPattern.tabsLI").removeClass("ui-corner-top");
        $(".FrmPattern.tabsList").removeClass("ui-corner-all");

        //****scroll move****
        ul = tabsList.find("ul");
        var widthSum = 0;

        for (var i = 0; i < ul[0].childNodes.length; i++) {
            widthSum += parseInt(ul[0].childNodes[i].clientWidth);
        }

        if (widthSum + 50 < me.divWidth) {
            widthSum = me.divWidth;
        }

        $(".FrmPattern.tabsUI").css("width", widthSum + 60);
        $(".FrmPattern.tabsList").css("width", widthSum + 80);
    };

    me.SetPatterCheck = function () {
        var url = me.id + "/fncPatternListSel";
        var patarnListArray = new Array();
        var CntRow = $(me.grid_sprList).jqGrid("getDataIDs");

        for (key in CntRow) {
            var rowID = parseInt(CntRow[key]);
            var rowData = $(me.grid_sprList).jqGrid("getRowData", rowID);

            patarnListArray.push(rowData);
        }

        var patarnArray = new Array();
        var getDataID = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in getDataID) {
            var rowID = parseInt(getDataID[key]);
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowID);

            patarnArray.push(rowData);
        }

        var data = {
            STYLE_ID: $(me.comboSysID + " option:selected").val(),
            patarn_data: patarnArray,
            patarn_list: patarnListArray,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                me.arrInputData = new Array();
                me.arrInputData = result["data"];

                // var patternFirst = result['data'][patarnArray[0]['PATTERN_ID']];

                // if (me.arrInputData[0].length != 0)
                // {
                //Ⅰで取得したﾃﾞｰﾀ件数分繰り返す
                for (var i = 0; i < me.arrInputData[0].length; i++) {
                    //Ⅰで取得したPRO_NOと明細情報(ﾒﾆｭｰ管理ﾊﾟﾀｰﾝﾃｰﾌﾞﾙ)のXｼｰﾄ目のプログラム№とが
                    //一致する行の追加のﾁｪｯｸﾎﾞｯｸｽに"True"を設定し
                    for (var j = 0; j < patarnListArray.length; j++) {
                        var rowData = $(me.grid_sprList).jqGrid(
                            "getRowData",
                            j
                        );

                        if (
                            rowData["PRO_NO"] == me.arrInputData[0][i]["PRO_NO"]
                        ) {
                            rowData["ADD_FLAG"] = "YES";
                            rowData["CREATE_DATE"] =
                                me.arrInputData[0][i]["CREATE_DATE"];

                            $(me.grid_sprList).jqGrid("setRowData", j, rowData);
                        }
                    }
                }
                // }

                //１行目を選択状態にする
                me.bFlagSearch = true;
                me.addTabs(getDataID);
                tabsList.tabs("option", "active", 0);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                me.subErrorShow();
                return;
            }
        };

        me.ajax.send(url, data, 0);
    };

    me.fncCompleteDeal = function () {
        $(me.grid_Patarn).jqGrid("setGridParam", {
            onSelectRow: function (rowId, _status, e) {
                if (typeof e != "undefined") {
                    //編集可能なセルをクリック、上下キー
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    var focusIndex = cellIndex == 0 ? 1 : cellIndex;
                    if (rowId && rowId !== me.lastsel) {
                        tabsList.tabs("option", "active", rowId);
                        $(me.grid_Patarn).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        // me.changeTabNM();
                        // me.lastsel = rowId;
                    }

                    $(me.grid_Patarn).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: focusIndex,
                    });
                } else {
                    if (rowId && rowId != me.lastsel) {
                        $(me.grid_Patarn).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.changeTabNM();
                        me.scrollLocation(rowId);
                        me.lastsel = rowId;
                    }
                    $(me.grid_Patarn).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                }

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });
                //键盘事件
                gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_Patarn,
                    e,
                    me.lastsel
                );
            },
        });
    };

    me.scrollLocation = function (iTabIndex) {
        var scrollWidth = 0;
        var ul = tabsList.find("ul");

        for (var i = 1; i <= parseInt(iTabIndex); i++) {
            scrollWidth += parseInt(ul[0].childNodes[i].clientWidth);
        }

        $(".FrmPattern.tabScroll").scrollLeft(scrollWidth - 100);
    };

    me.saveLastTabData = function () {
        var tmpArray = new Array();
        var rowIDs = $(me.grid_sprList).jqGrid("getDataIDs");

        for (key in rowIDs) {
            var rowID = rowIDs[key];
            var tmpNewData = {};
            var rowData = $(me.grid_sprList).jqGrid("getRowData", rowID);

            if (
                rowData["ADD_FLAG"].toUpperCase() == "YES" ||
                rowData["CREATE_DATE"] != ""
            ) {
                tmpNewData["ADD_FLAG"] = rowData["ADD_FLAG"];
                tmpNewData["PRO_NO"] = rowData["PRO_NO"];
                tmpNewData["CREATE_DATE"] = rowData["CREATE_DATE"];

                tmpArray.push(tmpNewData);
            }
        }

        var rowData = $(me.grid_Patarn).jqGrid("getRowData", me.lastsel);
        // var tabIndex = rowData['PATTERN_ID'];

        if (tmpArray.length != 0) {
            me.arrInputData[me.lastsel] = new Array();
            me.arrInputData[me.lastsel] = tmpArray;
        } else if (me.arrInputData[me.lastsel] != undefined) {
            if (me.arrInputData[me.lastsel].length != 0) {
                me.delInputData();
            }
        }
    };

    me.delInputData = function () {
        var newInputArray = new Array();

        for (var i = 0; i < me.arrInputData.length; i++) {
            if (parseInt(me.lastsel) != i && me.arrInputData[i] != undefined) {
                newInputArray[i] = me.arrInputData[i];
            }
        }

        me.arrInputData = new Array();
        me.arrInputData = newInputArray;
    };

    me.setTabData = function (mytabindex) {
        // var rowData = $(me.grid_Patarn).jqGrid('getRowData', mytabindex);
        // var tabIndex = rowData['PATTERN_ID'];
        var getDataID = $(me.grid_sprList).jqGrid("getDataIDs");

        if (me.arrInputData[mytabindex] == undefined) {
            for (var i = 0; i < getDataID.length; i++) {
                var rowDataList = $(me.grid_sprList).jqGrid("getRowData", i);

                rowDataList["ADD_FLAG"] = "NO";
                rowDataList["CREATE_DATE"] = "";

                $(me.grid_sprList).jqGrid("setRowData", i, rowDataList);
            }

            return;
        }

        var iNo = 0;

        for (var i = 0; i < getDataID.length; i++) {
            var rowDataList = $(me.grid_sprList).jqGrid("getRowData", i);

            if (iNo < me.arrInputData[mytabindex].length) {
                if (
                    me.arrInputData[mytabindex][iNo]["PRO_NO"] ==
                    rowDataList["PRO_NO"]
                ) {
                    rowDataList["ADD_FLAG"] =
                        me.arrInputData[mytabindex][iNo]["ADD_FLAG"];
                    rowDataList["PRO_NO"] =
                        me.arrInputData[mytabindex][iNo]["PRO_NO"];

                    if (!me.copyFlag) {
                        rowDataList["CREATE_DATE"] =
                            me.arrInputData[mytabindex][iNo]["CREATE_DATE"];
                    } else {
                        rowDataList["CREATE_DATE"] = "";
                    }

                    iNo += 1;
                } else {
                    rowDataList["ADD_FLAG"] = "NO";
                    rowDataList["CREATE_DATE"] = "";
                }
            } else {
                rowDataList["ADD_FLAG"] = "NO";
                rowDataList["CREATE_DATE"] = "";
            }

            $(me.grid_sprList).jqGrid("setRowData", i, rowDataList);
        }
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
        var widthSum = 0;
        var ul = tabsList.find("ul");

        for (var i = 0; i < ul[0].childNodes.length; i++) {
            widthSum += parseInt(ul[0].childNodes[i].clientWidth);
        }

        if (widthSum + 50 < me.divWidth) {
            widthSum = me.divWidth;
        }

        $(".FrmPattern.tabsUI").css("width", widthSum + 60);
        $(".FrmPattern.tabsList").css("width", widthSum + 80);
    };

    me.getTabID = function () {
        var tabID = -1;
        var li = $(".FrmPattern.tabsUI").find("li");

        for (var i = 0; i < li.length; i++) {
            if (i == me.lastsel) {
                tabID = li[i].id.substring(li[i].id.indexOf("_") + 1);
                break;
            }
        }

        return tabID;
    };

    me.addOneTab = function (patternCount) {
        var li = $(".FrmPattern.tabsUI").find("li");
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

        tabs_buttons +=
            '<li class="FrmPattern tabsLI" style="margin-right:0px" id=newLI_' +
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
            'class="FrmPattern tabsPanel"';
        tabs_panels += "</div>";

        var ul = tabsList.find("ul");
        $(tabs_buttons).appendTo(ul);
        $(tabs_panels).appendTo(tabsList);

        tabsList.tabs("refresh");

        $(".FrmPattern.tabsPanel").css("padding", "0px");
        $(".FrmPattern.tabsUI").removeClass("ui-corner-all");
        $(".FrmPattern.tabsLI").removeClass("ui-corner-top");
        $(".FrmPattern.tabsList").removeClass("ui-corner-all");

        var newName = "Sheet" + (parseInt(rowCount) + 1);
        $("#Sheet_" + rowCount).html(newName);

        me.addPattern["PATTERN_NM"] = newName;
        $(me.grid_Patarn).jqGrid("setRowData", patternCount, me.addPattern);
    };

    me.deleteTabData = function () {
        me.delRowDataContent();

        var tabID = me.getTabID();

        $("#Sheet_" + parseInt(tabID)).remove();
        $("#newLI_" + parseInt(tabID)).remove();
        tabsList.tabs("refresh");
    };

    me.delRowDataContent = function () {
        var rowID = parseInt(me.lastsel);
        var getDataID = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (var i = rowID; i < getDataID.length - 1; i++) {
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
    };

    me.fncInputChk = function () {
        var selectVal = $(".FrmPattern.UcComboBox1 option:selected").text();

        if (selectVal == "") {
            me.clsComFnc.ObjFocus = $(".FrmPattern.UcComboBox1");
            me.clsComFnc.FncMsgBox("W0003", "所属");
            return false;
        }

        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (var i = 0; i < rowIDs.length; i++) {
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", rowIDs[i]);

            //ﾊﾟﾀｰﾝ名
            //必須チェック
            if (rowData["PATTERN_ID"] == "") {
                me.setFocus("W0001", "パターンID", rowIDs[i], 0);
                return false;
            }

            //桁数チェック　５０桁以上の場合はエラー
            var intRtn = me.clsComFnc.FncSprCheck(
                rowData["PATTERN_NM"].trimEnd(),
                1,
                me.clsComFnc.INPUTTYPE.NONE,
                50
            );

            if (intRtn != 0) {
                me.setFocus("W000" + intRtn * -1, "パターン名", rowIDs[i], 1);
                return false;
            }

            //ﾊﾟﾀｰﾝ名の重複ﾁｪｯｸ
            for (var j = i + 1; j < rowIDs.length; j++) {
                var rowDataCompare = $(me.grid_Patarn).jqGrid(
                    "getRowData",
                    rowIDs[j]
                );

                if (
                    rowData["PATTERN_ID"] == rowDataCompare["PATTERN_ID"] ||
                    rowData["PATTERN_NM"] == rowDataCompare["PATTERN_NM"]
                ) {
                    var row =
                        me.firstData[rowIDs[i]]["PATTERN_ID"] !==
                            rowData["PATTERN_ID"] ||
                        me.firstData[rowIDs[i]]["PATTERN_NM"] !==
                            rowData["PATTERN_NM"]
                            ? rowIDs[i]
                            : rowIDs[j];

                    me.setFocus(
                        "W9999",
                        i +
                            1 +
                            "行目と" +
                            (j + 1) +
                            "行目のデータが重複しています。",
                        row,
                        0
                    );
                    return false;
                }
            }
        }

        return true;
    };

    me.setFocus = function (strMSGType, strMSG, rowID) {
        tabsList.tabs("option", "active", rowID);
        // me.scrollLocation(rowID);
        $(me.grid_Patarn).jqGrid("setSelection", rowID);
        if (strMSG == "パターン名") {
            me.clsComFnc.ObjFocus = $("#" + rowID + "_PATTERN_NM");
            me.clsComFnc.ObjSelect = $("#" + rowID + "_PATTERN_NM");
        } else {
            me.clsComFnc.ObjFocus = $("#" + rowID + "_PATTERN_ID");
            me.clsComFnc.ObjSelect = $("#" + rowID + "_PATTERN_ID");
        }
        me.clsComFnc.FncMsgBox(strMSGType, strMSG);
    };

    me.fncDeleteUpdataMst = function () {
        var patternData = new Array();
        var rowIDs = $(me.grid_Patarn).jqGrid("getDataIDs");

        for (key in rowIDs) {
            var rowData = $(me.grid_Patarn).jqGrid("getRowData", key);
            patternData.push(rowData);
        }

        var url = me.id + "/fncDeleteUpdataMst";
        var sendData = {
            inputDatas: me.arrInputData,
            patternData: patternData,
            selectIndex: $(me.comboSysID + " option:selected").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                //正常終了ﾒｯｾｰｼﾞ
                var tmpId = me.comboSysID + " option[value='" + "" + "']";
                $(tmpId).prop("selected", true);
                me.clsComFnc.ObjFocus = $(me.comboSysID);
                me.clsComFnc.FncMsgBox("I0005");

                $(me.comboSysID).get(0).selectedIndex = 0;
                me.initSelect();
                $(".FrmPattern.cmdInsert").button("disable");
                $(".FrmPattern.cmdCopy").button("disable");
                $(".FrmPattern.cmdDelete").button("disable");
                $(".FrmPattern.cmdInput").button("disable");
            }
        };
        me.ajax.send(url, sendData, 0);
    };

    me.initSelect = function () {
        me.addPattern = {
            PATTERN_ID: "",
            PATTERN_NM: "",
            CREATE_DATE: "",
        };
        me.arrInputData = new Array();
        me.lastsel = 0;

        //画面項目をｸﾘｱする
        tabsList.tabs("option", "active", 0);
        $(".FrmPattern.tabsUI").empty();
        $(me.grid_Patarn).jqGrid("clearGridData");
        $(me.grid_sprList).jqGrid("clearGridData");
        $(me.grid_Patarn).closest(".ui-jqgrid").unblock();
        $(me.grid_sprList).closest(".ui-jqgrid").unblock();
        $(".FrmPattern.tabsList").closest(".ui-tabs").unblock();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmPattern = new R4.FrmPattern();
    o_R4_FrmPattern.load();
});
