/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240329       本番障害.xlsx NO9       ダイアログ上で検索実行後に 検索条件を復元する必要なし      lujunxia
 * -------------------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKKamokuSearch");

HDKAIKEI.HDKKamokuSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.id = "HDKKamokuSearch";
    me.ajax = new gdmz.common.ajax();
    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKKamokuSearch_sprItyp";
    me.tree_grid_id = "#HDKAIKEI_HDKKamokuSearch_treeprItyp";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "btnHyouji_Click";
    me.tree_g_url = me.sys_id + "/" + me.id + "/" + "btnTreeHyouji_Click";

    me.option = {
        rowNum: 0,
        rownumbers: true,
        multiselect: false,
        caption: "",
        multiselectWidth: 40,
        datatype: "json",
        treeGrid: true, // 启用treeGrid树形表格
        treeGridModel: "adjacency", // treeGrid所使用的数据结构方法,nested:嵌套集模型，adjacency: 邻接模型
        ExpandColumn: "KAMOK_CD", // 指定那列来展开tree grid，默认为第一列
        ExpandColClick: true, //点击文本展开
        loadui: "disable",
        treeReader: {
            // 扩展表格的colModel
            level_field: "level", //  treeGrid等级字段，integer类型，从0开始
            parent_id_field: "PARENT_ID", // treeGrid关联父级id字段
            leaf_field: "isLeaf", // 是否叶子节点字段，boolean类型
            expanded_field: "expanded", //treeGrid是否展开字段 ，boolean类型
        },
    };

    me.colModelT = [
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            width: 93,
            align: "left",
            sortable: false,
        },
        {
            name: "KAMOK_NAME",
            label: "科目名",
            index: "KAMOK_NAME",
            width: 142,
            align: "left",
            sortable: false,
        },
        {
            name: "SUB_KAMOK_CD",
            label: "補助科目コード",
            index: "SUB_KAMOK_CD",
            width: 103,
            align: "left",
            sortable: false,
        },
        {
            name: "SUB_KAMOK_NAME",
            label: "補助科目名",
            index: "SUB_KAMOK_NAME",
            width: me.ratio === 1.5 ? 265 : 280,
            align: "left",
            sortable: false,
        },
    ];
    me.colModel = [
        {
            name: "KAMOK_CD",
            label: "科目コード",
            index: "KAMOK_CD",
            width: 73,
            align: "left",
            sortable: false,
        },
        {
            name: "KAMOK_NAME",
            label: "科目名",
            index: "KAMOK_NAME",
            width: 122,
            align: "left",
            sortable: false,
        },
        {
            name: "SUB_KAMOK_CD",
            label: "補助科目コード",
            index: "SUB_KAMOK_CD",
            width: 103,
            align: "left",
            sortable: false,
        },
        {
            name: "SUB_KAMOK_NAME",
            label: "補助科目名",
            index: "SUB_KAMOK_NAME",
            width: me.ratio === 1.5 ? 260 : 270,
            align: "left",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HDKKamokuSearch.btnView",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HDKKamokuSearch.btnSelect",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKKamokuSearch.btnClose",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $(".HDKKamokuSearch.btnView").click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HDKKamokuSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //処理説明：戻るボタン押下時
    $(".HDKKamokuSearch.btnClose").click(function () {
        $("#HDKKamokuSearchDialogDiv").dialog("close");
    });

    $(".HDKKamokuSearch.txtKamoku").on("focus", function () {
        //テキストエリアを全選択する
        $(this).select();
    });

    $(".HDKKamokuSearch.txtKamoku").on("blur", function () {
        me.HDKAIKEI.KinsokuMojiCheck($(this), me.clsComFnc);
    });

    $(".HDKKamokuSearch.txtKamoku").on("keydown", function (e) {
        var key = e.which;
        if (key == 13 || key == 9) {
            e.preventDefault();

            $(".ui-dialog-buttons").find(".ui-button").trigger("focus");
        }
    });
    $("input[type=radio][name=HDKKamokuSearch_radio]").change(function () {
        rdo = $.trim($("input[name='HDKKamokuSearch_radio']:checked").val());
        if (rdo == "rdonotree") {
            $(".HDKKamokuSearch .kam").show();
            $(".HDKKamokuSearch .treekam").hide();
        } else {
            $(".HDKKamokuSearch .treekam").show();
            $(".HDKKamokuSearch .kam").hide();
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
        me.HDKKamokuSearch_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKKamokuSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKKamokuSearch_load = function () {
        //初期設定処理
        me.SubFirstSet();

        gdmz.common.jqgrid.init_tree(
            me.tree_grid_id,
            me.tree_g_url,
            me.colModelT,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 643 : 653
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 260 : 312
        );
        gdmz.common.jqgrid.set_grid_width(
            me.tree_grid_id,
            me.ratio === 1.5 ? 643 : 653
        );
        gdmz.common.jqgrid.set_grid_height(
            me.tree_grid_id,
            me.ratio === 1.5 ? 260 : 312
        );

        $("#HDKAIKEI_HDKKamokuSearch_sprItyp_rn").html("№");
        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKKamokuSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId) {
                $(me.grid_id + " tr#" + rowId).on("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKKamokuSearch.btnSelect").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKKamokuSearch.btnView").trigger("focus");
                    }
                });
            },
        });
        $(me.tree_grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKKamokuSearchDialogDiv").dialog("close");
            },
            onSelectRow: function (rowId) {
                $(me.tree_grid_id + " tr#" + rowId).on("keydown", function (e) {
                    var key = e.which;
                    e.preventDefault();
                    if (key == 9 && e.shiftKey == false) {
                        $(".HDKKamokuSearch.btnSelect").trigger("focus");
                    } else if (key == 9 && e.shiftKey == true) {
                        $(".HDKKamokuSearch.btnView").trigger("focus");
                    }
                });
            },
        });
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKKamokuSearchDialogDiv").dialog("close");
            },
        });
        $(me.tree_grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                //閉じる
                $("#HDKKamokuSearchDialogDiv").dialog("close");
            },
        });
        $(".treekam").hide();
        $("#RtnCD").html("-1");
    };

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {
        var txtKamokuCode = $.trim($(".HDKKamokuSearch.txtKamokuCode").val()); //科目CD
        var txtSubkoumokuCode = $.trim(
            $(".HDKKamokuSearch.txtSubkoumokuCode").val()
        ); //辅助CD
        var txtKamokuName = $.trim($(".HDKKamokuSearch.txtKamokuName").val()); //科目名
        var txtSubkoumokuName = $.trim(
            $(".HDKKamokuSearch.txtSubkoumokuName").val()
        ); //辅助名
        var str = $.trim($("#koumkuCd").val());

        var data = {
            txtKamokuCode: txtKamokuCode,
            txtSubkoumokuCode: txtSubkoumokuCode,
            txtKamokuName: txtKamokuName,
            txtSubkoumokuName: txtSubkoumokuName,
            str: str,
        };

        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                //20240329 lujunxia upd s
                //me.SubFirstSet();
                me.SubFirstSet(true);
                //20240329 lujunxia upd e
                //該当データはありません。
            } else {
                //選択ボタンが表示されます。
                $(".HDKKamokuSearch.btnSelect").show();
                $(".HDKKamokuSearch.txtKamoku.txtKamokuCode").trigger("focus");
            }
        };

        var complete_funT = function (returnFLGT, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (returnFLGT == "nodata") {
                //20240329 lujunxia upd s
                //me.SubFirstSet();
                me.SubFirstSet(true);
                //20240329 lujunxia upd e
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                //選択ボタンが表示されます。
                $(".HDKKamokuSearch.btnSelect").show();
                $(".HDKKamokuSearch.txtKamoku.txtKamokuCode").trigger("focus");
                $(me.tree_grid_id).find(".tree-minus").trigger("click");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        gdmz.common.jqgrid.reloadMessage(
            me.tree_grid_id,
            data,
            complete_funT
        );
    };

    //'**********************************************************************
    //'処 理 名：科目グリッド行選択のイベント
    //'関 数 名：windowClose
    //'戻 り 値：なし
    //'処理説明：科目グリッド行選択の処理
    //'**********************************************************************
    me.windowClose = function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        //閉じる
        $("#HDKKamokuSearchDialogDiv").dialog("close");
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var rdo = $.trim(
            $("input[name='HDKKamokuSearch_radio']:checked").val()
        );
        if (rdo == "rdonotree") {
            var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        } else {
            var SelectRow = $(me.tree_grid_id).jqGrid("getGridParam", "selrow");
        }
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return false;
        } else {
            if (rdo == "rdonotree") {
                var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
            } else {
                var rowData = $(me.tree_grid_id).jqGrid(
                    "getRowData",
                    SelectRow
                );
            }

            if (
                rowData &&
                $.trim(rowData["KAMOK_CD"]) != "" &&
                $.trim(rowData["SUB_KAMOK_CD"]) != ""
            ) {
                //リターン値
                $("#RtnCD").html("1");
                //---科目コード---
                $("#KamokuCD").html($.trim(rowData["KAMOK_CD"]));
                //---項目コード---
                $("#KoumkuCD").html($.trim(rowData["SUB_KAMOK_CD"]));
                //---科目名---
                $("#KamokuNM").html($.trim(rowData["KAMOK_NAME"]));
            } else {
                return false;
            }
        }

        return true;
    };

    //**********************************************************************
    //処 理 名：初期設定処理
    //関 数 名：SubFirstSet
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定処理を行う。
    //**********************************************************************
    //20240329 lujunxia upd s
    // me.SubFirstSet = function () {
    // 	if ($("#KamokuCD").length > 0) {
    me.SubFirstSet = function (flg) {
        if ($("#KamokuCD").length > 0 && !flg) {
            //20240329 lujunxia upd e
            var strKamokuCD = $("#KamokuCD").val();
            if (strKamokuCD) {
                $(".HDKKamokuSearch.txtKamoku.txtKamokuCode").val(strKamokuCD);
            }
        }
        //フォーカスの設定
        $(".HDKKamokuSearch.txtKamoku.txtKamokuCode").trigger("focus");
        //選択ボタンは表示されません。-
        $(".HDKKamokuSearch.btnSelect").hide();
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKKamokuSearch = new HDKAIKEI.HDKKamokuSearch();
    o_HDKAIKEI_HDKKamokuSearch.load();
});
