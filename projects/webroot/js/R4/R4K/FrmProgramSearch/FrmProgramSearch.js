Namespace.register("R4.FrmProgramSearch");

R4.FrmProgramSearch = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmProgramSearch";
    me.grid_id = "#FrmProgramSearch_sprItyp";
    me.lastsel = 0;
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
            name: "PRO_NO",
            label: "No.",
            index: "PRO_NO",
            width: 80,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "PRO_NM",
            label: "名称",
            index: "PRO_NM",
            width: 300,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmProgramSearch.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmProgramSearch.cmdChoice",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmProgramSearch.cmdCancel",
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
    // **********************************************************************
    // 「検索」ボタン
    // **********************************************************************
    $(".FrmProgramSearch.cmdSearch").click(function () {
        //---検索処理---
        var data = {
            PRO_NM: $(".FrmProgramSearch.txtProgramNM").val(),
        };
        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                if (bErrorFlag == "nodata") {
                    //メッセージの表示
                    //該当するデータは存在しません。
                    me.clsComFnc.ObjFocus = $(".FrmProgramSearch.txtProgramNM");
                    me.clsComFnc.FncMsgBox("I0001");
                }

                //初期設定処理
                me.SubFirstSet(true);
            } else {
                //選択ボタンを活性
                $(".FrmProgramSearch.cmdChoice").button("enable");

                me.doubleClick();

                //１行目を選択状態にする
                $(me.grid_id).trigger("focus");
                $(me.grid_id).jqGrid("setSelection", 0, true);
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    });

    //'**********************************************************************
    // '　「明細」
    // '**********************************************************************
    me.doubleClick = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                $("#isOk").html(true);
                //閉じる
                $("#FrmProgramSearchDialogDiv").dialog("close");
            },
        });

        //スプレッド上でエンター押下時に修正処理
        $(me.grid_id).jqGrid("bindKeys", {
            scrollingRows: true,
            onEnter: function () {
                //選択値の設定
                if (me.FncSetRtnData() != true) {
                    return;
                }

                $("#isOk").html(true);
                //閉じる
                $("#FrmProgramSearchDialogDiv").dialog("close");
            },
        });
    };

    $(".FrmProgramSearch.txtProgramNM").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 222) {
            return false;
        }
    });

    //'**********************************************************************
    // '　「キャンセル」ボタン
    // '**********************************************************************
    $(".FrmProgramSearch.cmdCancel").click(function () {
        $("#isOk").html(false);
        $("#FrmProgramSearchDialogDiv").dialog("close");
    });

    // '**********************************************************************
    // '　「選択」ボタン
    // '**********************************************************************
    $(".FrmProgramSearch.cmdChoice").click(function () {
        //選択値の設定
        if (me.FncSetRtnData() != true) {
            return;
        }

        $("#isOk").html(true);
        //閉じる
        $("#FrmProgramSearchDialogDiv").dialog("close");
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

        //プログラム名称
        $(".FrmProgramSearch.txtProgramNM").val("");

        //初期設定処理
        me.SubFirstSet(false);

        var url = me.id + "/fncHPROGRAMMSTSel";
        gdmz.common.jqgrid.init(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 460);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 309 : 380
        );
    };

    // '**********************************************************************
    // '処 理 名：初期設定処理
    // '関 数 名：SubFirstSet
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：初期設定処理を行う。
    // '**********************************************************************
    me.SubFirstSet = function (bFlgFocus) {
        //表示行数の設定
        $(me.grid_id).jqGrid("clearGridData");

        //選択ボタンを不活性
        $(".FrmProgramSearch.cmdChoice").button("disable");

        //フォーカスの設定
        if (!bFlgFocus) {
            $(".FrmProgramSearch.txtProgramNM").trigger("focus");
        }
    };

    // '**********************************************************************
    // '処 理 名：選択データの設定
    // '関 数 名：FncSetRtnData
    // '引    数：無し
    // '戻 り 値：True ：正常
    // '        　False：異常
    // '処理説明：選択したデータを構造体に設定する。
    // '**********************************************************************
    me.FncSetRtnData = function () {
        var rowNo = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowNo);

        //'---科目コード---
        var strSyuCD = rowData["PRO_NO"];
        //'---科目名---
        var strNM = rowData["PRO_NM"];

        //'選択値設定
        if ($.trim(strSyuCD) != "") {
            //'リターン値
            // $("#intRtnCD").html("1");
            //'プログラム№
            $("#ProgNO").html(strSyuCD.trimEnd());
            //'プログラム名称
            $("#ProgNM").html(strNM.trimEnd());
        } else {
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
    o_R4_FrmProgramSearch = new R4.FrmProgramSearch();
    o_R4_FrmProgramSearch.load();
});
