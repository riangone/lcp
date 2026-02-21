Namespace.register("R4.FrmLoginSel");

R4.FrmLoginSel = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmLoginSel";
    me.sys_id = "R4";
    me.grid_id = "#FrmLoginSel_sprList";
    // me.lastsel = 0;
    me.strTougetu = "";
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
            name: "SYAIN_NO",
            label: "ユーザＩＤ",
            index: "SYAIN_NO",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "STYLE_NM",
            label: "所属",
            index: "STYLE_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "PATTERN_NM",
            label: "パターン",
            index: "PATTERN_NM",
            width: 200,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "USER_ID",
            label: "済/未",
            index: "USER_ID",
            width: 100,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmLoginSel.Button1",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmLoginSel.Button3",
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

    // '**********************************************************************
    // '検索ﾎﾞﾀﾝクリック時
    // '**********************************************************************
    $(".FrmLoginSel.Button1").click(function () {
        me.Button1_Click();
    });

    $(".FrmLoginSel.Button3").click(function () {
        var getDataCount = $(me.grid_id).jqGrid("getGridParam", "records");

        if (getDataCount <= 0) {
            me.clsComFnc.FncMsgBox("I0010");
            return;
        }

        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
        me.UserID = rowData["SYAIN_NO"];

        me.openDialog();
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

        var url = me.id + "/fncGetLoginInfo";
        var data = {
            KJNBI: "load",
        };

        me.complete_fun = function () {
            var url = me.id + "/fncLoadDeal";

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    me.strTougetu = result["strTougetu"];
                    me.setSelectValues(result["data"]);
                    $(".FrmLoginSel.UcUserID").trigger("focus");
                } else {
                    me.clsComFnc.ObjFocus = $(".FrmLoginSel.UcComboBox1");
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                }

                // $('.FrmLoginSel.Button3').button('disable');
            };
            me.ajax.send(url, "", 1);
        };

        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 900);
        //----20150818 Yuanjh Modify S.
        //gdmz.common.jqgrid.set_grid_height(me.grid_id, 390);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 270 : 338
        );
        //----20150818 Yuanjh Modify E.

        //--200150818  Yuanjh ADD S.
        $(me.grid_id).jqGrid("bindKeys", {
            //scrollingRows : true,
            onEnter: function (rowid) {
                var selIRow = parseInt(rowid) + 1;
                var getDataCount = $(me.grid_id).jqGrid(
                    "getGridParam",
                    "records"
                );
                if (selIRow == getDataCount) {
                    return false;
                }
                $(me.grid_id).jqGrid("setSelection", selIRow, true);
            },
        });
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowid) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowid);
                me.UserID = rowData["SYAIN_NO"];
                me.openDialog();
            },
        });
        //--200150818  Yuanjh ADD E.
    };

    // '**********************************************************************
    // 'フォームロード時、COMBOXコントロールの初期化
    // '**********************************************************************
    me.setSelectValues = function (arrResult) {
        $(".FrmLoginSel.UcComboBox1").empty();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".FrmLoginSel.UcComboBox1");

        for (key in arrResult) {
            if (arrResult[key]["STYLE_NM"] != "") {
                arrResult[key]["STYLE_NM"] = me.clsComFnc.fncGetFixVal(
                    arrResult[key]["STYLE_NM"],
                    18
                );
                $("<option></option>")
                    .val(arrResult[key]["STYLE_ID"])
                    .text(arrResult[key]["STYLE_NM"])
                    .appendTo(".FrmLoginSel.UcComboBox1");
            }
        }

        var tmpId = ".FrmLoginSel.UcComboBox1 option[value='" + "" + "']";
        $(tmpId).prop("selected", true);
    };

    //--200150818  Yuanjh DEL S.
    // me.fncCompleteDeal = function() {
    // $(me.grid_id).jqGrid('bindKeys', {
    //scrollingRows : true,
    // onEnter : function(rowid) {
    // var selIRow = parseInt(rowid) + 1;
    // console.log(rowid + "---"+selIRow);
    // var getDataCount = $(me.grid_id).jqGrid('getGridParam', 'records');
    // if (selIRow == getDataCount) {
    // return false;
    // }
    // $(me.grid_id).jqGrid('setSelection', selIRow, true);
    // }
    // });
    // $(me.grid_id).jqGrid('setGridParam', {
    // ondblClickRow : function(rowid) {
    // var rowData = $(me.grid_id).jqGrid('getRowData', rowid);
    // me.UserID = rowData['SYAIN_NO'];
    // me.openDialog();
    // }
    // });
    // };
    //--200150818  Yuanjh DEL E.

    me.Button1_Click = function () {
        var data = {
            KJNBI: me.strTougetu,
            SYAIN_NO: $(".FrmLoginSel.UcUserID").val(),
            PATTERN_ID: $(".FrmLoginSel.UcComboBox1 option:selected").val(),
        };

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag != "normal") {
                $(me.grid_id).jqGrid("clearGridData");
                // $('.FrmLoginSel.Button3').button('disable');
                // $(".FrmLoginSel.UcUserID").trigger("focus");
                return;
            }

            //スプレッドに取得データをセットする
            //---20150818  Yuanjh Del S.
            //me.fncCompleteDeal();
            //---20150818  Yuanjh Del E.

            $(me.grid_id).trigger("focus");
            $(me.grid_id).jqGrid("setSelection", 0);
            // $('.FrmLoginSel.Button3').button('enable');
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
    };

    me.openDialog = function () {
        $("<div></div>")
            .attr("id", "FrmLoginEditDialogDiv")
            .insertAfter($("#FrmLoginSel"));
        $("#FrmLoginEditDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 200,
            width: 500,
            resizable: false,
            close: function () {
                me.Button1_Click();
            },
        });
        var frmId = "FrmLoginEdit";
        var url = "R4K/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#FrmLoginEditDialogDiv").html(result);
            $("#FrmLoginEditDialogDiv").dialog(
                "option",
                "title",
                "ログイン情報登録"
            );
            $("#FrmLoginEditDialogDiv").dialog("open");
        };
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmLoginSel = new R4.FrmLoginSel();
    o_R4_FrmLoginSel.load();
    o_R4K_R4K.FrmLoginSel = o_R4_FrmLoginSel;
});
