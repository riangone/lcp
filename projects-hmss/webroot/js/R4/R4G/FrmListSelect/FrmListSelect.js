/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmListSelect");

R4.FrmListSelect = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmListSelect";
    me.sys_id = "R4G";
    me.FrmList = null;
    me.intBtnKind = "";
    me.data = new Array();
    me.SelectRow = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmListSelect.cmdCopy",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmListSelect.cmdSelect",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmListSelect.cmdBack",
        type: "button",
        handle: "",
    });

    me.colModel = [
        {
            name: "CMN_NO",
            label: "注文書番号",
            index: "CMN_NO",
            align: "left",
            width: 170,
            sortable: false,
        },
        {
            name: "KASOU_NO",
            label: "架装番号",
            index: "KASOU_NO",
            align: "left",
            width: 300,
            sortable: false,
        },
    ];

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    me.grid_id = "#FrmListSelect_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fnc" + me.id;
    me.pager = "#FrmListSelect_pager";
    me.sidx = "CMN_NO";
    me.option = {
        pagerpos: "left",
        caption: "",
        shrinkToFit: true,
        multiselect: false,
        rowNum: 100000000,
        rownumWidth: 50,
    };

    var txtCMNNOVal = $.trim($(".FrmList.txtCMNNO").val());
    var txtSiyFgnVal = $.trim($(".FrmList.txtSiyFgn").val());
    var txtEmpNOVal = $.trim($(".FrmList.txtEmpNO").val());

    me.data = {
        CMN_NO: txtCMNNOVal,
        SIY_FGN: txtSiyFgnVal,
        HNB_TAN_EMP_NO: txtEmpNOVal,
    };

    var base_load = me.load;

    me.load = function () {
        base_load();

        gdmz.common.jqgrid.show(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            me.data,
            me.loadCom
        );

        gdmz.common.jqgrid.set_grid_width(me.grid_id, 570);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 300);
        $("#FrmListDialogDiv").dialog("option", "title", "架装明細表示");
        $("#FrmListDialogDiv").dialog("open");
    };

    me.loadCom = function () {
        $(me.grid_id).jqGrid("setSelection", "0");
    };
    // ========== コントロース end ==========
    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmListSelect.cmdCopy").click(function () {
        me.SelectRow = $("#FrmListSelect_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );

        if (me.SelectRow == "" || me.SelectRow == null) {
            clsComFnc.MessageBox(
                "I9999</br>行を選択してください。",
                clsComFnc.GSYSTEM_NAME,
                clsComFnc.MessageBoxButtons.OK,
                clsComFnc.MessageBoxIcon.Information
            );
        } else {
            me.intBtnKind = 1;
            $("#FrmListDialogDiv").dialog("close");
        }
    });

    $(".FrmListSelect.cmdSelect").click(function () {
        me.SelectRow = $("#FrmListSelect_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        if (me.SelectRow == "" || me.SelectRow == null) {
            clsComFnc.MessageBox(
                "I9999</br>行を選択してください。",
                clsComFnc.GSYSTEM_NAME,
                clsComFnc.MessageBoxButtons.OK,
                clsComFnc.MessageBoxIcon.Information
            );
        } else {
            me.intBtnKind = 2;
            $("#FrmListDialogDiv").dialog("close");
        }
    });

    $(".FrmListSelect.cmdBack").click(function () {
        $("#FrmListDialogDiv").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmListSelect = new R4.FrmListSelect();
    o_R4_FrmListSelect.load();

    o_R4_R4.FrmList.FrmListSelect = o_R4_FrmListSelect;
    o_R4_FrmListSelect.FrmList = o_R4_R4.FrmList;
});
