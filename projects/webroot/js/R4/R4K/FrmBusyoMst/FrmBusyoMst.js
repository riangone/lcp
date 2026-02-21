/**
 * 説明：
 *
 *
 * @author ciyuanchen
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                   FCSDL
 * 20171226            #2807                        横スクロールバーがある                                                             ciyuanchen
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmBusyoMst");

R4.FrmBusyoMst = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmBusyoMst";
    me.sys_id = "R4K";
    me.url = "";
    me.grid_id = "#FrmBusyoMst_sprList";
    me.subDialogId = "#FrmBusyoMst_subFormDialog";
    me.g_url = me.sys_id + "/" + me.id + "/" + "subSpreadReShow";
    me.pager = "#FrmBusyoMst_sprList_pager";
    me.sidx = "";
    me.actionFlg = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    me.controls.push({
        id: ".FrmBusyoMst.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmBusyoMst.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmBusyoMst.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmBusyoMst.cmdSearch",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmBusyoMst.cmdSearch").click(function () {
        me.fncBusyoSearchButtonClick();
    });

    $(".FrmBusyoMst.cmdInsert").click(function () {
        me.fncBusyoInsertButtonClick();
    });

    $(".FrmBusyoMst.cmdUpdate").click(function () {
        me.fncBusyoUpdateButtonClick();
    });

    $(".FrmBusyoMst.cmdDelete").click(function () {
        me.fncBusyoDeleteButtonClick();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmBusyoMst_load();
    };
    me.initGrid = function () {
        me.option = {
            pagerpos: "left",
            multiselect: false,
            caption: "",
            rowNum: 5000000,
            multiselectWidth: 30,
            rownumWidth: 40,
        };
        me.colModel = [
            {
                name: "BUSYO_CD",
                label: "部  &nbsp;署<br>コード",
                index: "BUSYO_CD",
                width: 45,
                sortable: false,
                align: "left",
            },
            {
                name: "BUSYO_NM",
                label: "部署名",
                index: "BUSYO_NM",
                width: 170,
                sortable: false,
                align: "left",
            },
            {
                name: "BUSYO_KANANM",
                label: "--",
                index: "BUSYO_KANANM",
                width: 100,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "BUSYO_RYKNM",
                label: "--",
                index: "BUSYO_RYKNM",
                width: 100,
                sortable: false,
                align: "left",
                hidden: true,
            },
            {
                name: "KKR_BUSYO_CD",
                label: "括り<br>部署",
                index: "KKR_BUSYO_CD",
                width: 33,
                sortable: false,
                align: "left",
            },
            {
                name: "CNV_BUSYO_CD",
                label: "変換<br>部署",
                index: "CNV_BUSYO_CD",
                width: 33,
                sortable: false,
                align: "left",
            },
            {
                name: "SYUKEI_KB",
                label: "集計部<br>署区分",
                index: "SYUKEI_KB",
                width: 58,
                sortable: false,
                align: "left",
            },
            {
                name: "MANEGER_CD",
                label: "管理者",
                index: "MANEGER_CD",
                width: 45,
                sortable: false,
                align: "left",
            },
            {
                name: "START_DATE",
                label: "設立日",
                index: "START_DATE",
                width: 60,
                sortable: false,
                align: "left",
            },
            {
                name: "END_DATE",
                label: "閉鎖日",
                index: "END_DATE",
                width: 60,
                sortable: false,
                align: "left",
            },
            {
                name: "DSP_SEQNO",
                label: "表示順位",
                index: "DSP_SEQNO",
                width: 60,
                sortable: false,
                align: "left",
            },
            {
                name: "PRN_KB1",
                label: "新車",
                index: "PRN_KB1",
                width: 32,
                sortable: false,
                align: "right",
            },
            {
                name: "PRN_KB2",
                label: "中古",
                index: "PRN_KB2",
                width: 32,
                sortable: false,
                align: "right",
            },
            {
                name: "PRN_KB3",
                label: "整備",
                index: "PRN_KB3",
                width: 32,
                sortable: false,
                align: "right",
            },
            {
                name: "PRN_KB4",
                label: "損益",
                index: '"PRN_KB4',
                width: 32,
                sortable: false,
                align: "left",
            },
            {
                name: "PRN_KB5",
                label: "経成",
                index: "PRN_KB5",
                width: 32,
                sortable: false,
                align: "left",
            },
            {
                name: "PRN_KB6",
                label: "本社",
                index: "PRN_KB6",
                width: 32,
                sortable: false,
                align: "left",
            },
            {
                name: "HKNSYT_DSP_KB",
                label: "ｶﾊﾞｰ率",
                index: "HKNSYT_DSP_KB",
                width: 50,
                sortable: false,
                align: "left",
            },
            {
                name: "TORIKOMI_BUSYO_KB",
                label: "取込部署区分",
                index: "TORIKOMI_BUSYO_KB",
                width: 70,
                sortable: false,
                align: "left",
            },
        ];

        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        // 20171226 CIYUANCHEN UPD S
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1025);
        // 20171226 CIYUANCHEN UPD E
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 280
        );
        me.t = document.getElementById("FrmBusyoMst_sprList_pager_center");
        me.t.childNodes[1].innerHTML = "";
        //---20150818 fanzhengzhou add s.You must write Enter key event like this.If not,when you press up or down,the selected row of the Grid will not change in order.
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //dialogを開く
                me.fncBusyoUpdateButtonClick();
            },
        });
        //---20150818 fanzhengzhou add e.
    };

    me.FrmBusyoMst_load = function () {
        me.initGrid();
        //'ボタンを非表示にする
        $(".FrmBusyoMst.cmdUpdate").button("disable");
        $(".FrmBusyoMst.cmdDelete").button("disable");
        $(me.subDialogId).dialog({
            autoOpen: false,
            modal: true,
            height: 490,
            width: 910,
            title: " 部署マスタメンテナンス",
            resizable: false,
        });
        //---20150818 fanzhengzhou del s.
        //me.fnckeyDown13();
        //---20150818 fanzhengzhou del e.
    };
    //--click event functions--

    /***********************************************************************
	 '処 理 名：データグリッドの再表示
	 '関 数 名：fncBusyoSearchButtonClick
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：データグリッドを再表示する
	 ***********************************************************************
	 */
    me.fncBusyoSearchButtonClick = function () {
        me.data = {
            busyoCD: $(".FrmBusyoMst.txtBusyoCD").val().toString().trimEnd(),
            busyoKN: $(".FrmBusyoMst.txtBusyoKN").val().toString().trimEnd(),
        };
        gdmz.common.jqgrid.reload(
            me.grid_id,
            me.data,
            me.searchComplete_fun
        );
        // 20171226 CIYUANCHEN UPD S
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1010);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 1025);
        // 20171226 CIYUANCHEN UPD E
        //1090
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 228 : 280
        );
    };
    /***********************************************************************
	 '処 理 名：削除
	 '関 数 名：fncBusyoDeleteButtonClick
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：選択された行のデータを削除する
	 '**********************************************************************
	 */
    me.fncBusyoDeleteButtonClick = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (id >= 0) {
            clsComFnc.MsgBoxBtnFnc.Yes = me.YesDeleteFnc;
            clsComFnc.MsgBoxBtnFnc.No = me.NoDeleteFnc;
            clsComFnc.FncMsgBox("QY004");
        } else {
            //削除する行未選択
            clsComFnc.FncMsgBox("I0010");
        }
    };
    me.fncBusyoInsertButtonClick = function () {
        me.actionFlg = "INS";
        me.openSubFormDialog_fnc();
    };
    me.fncBusyoUpdateButtonClick = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        if (id == null) {
            $(".FrmBusyoMst.FrmBusyoMst_sprList").trigger("focus");
            return;
        } else {
            me.actionFlg = "UPD";
            me.busyoCd = rowData["BUSYO_CD"];
            me.openSubFormDialog_fnc();
            $(".FrmBusyoMst.FrmBusyoMst_sprList").trigger("focus");
        }
    };

    //---20150818 fanzhengzhou del s.
    //--keydown event functions--
    //me.fnckeyDown13 = function() {
    //me.inp = $(me.grid_id);
    // me.inp.bind('keydown', function(e)
    // {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 13 && oEvent.shiftKey == false)
    // {
    // me.fncBusyoUpdateButtonClick();
    // };
    // });
    //};
    //---20150818 fanzhengzhou del e.

    //--functions --
    me.searchComplete_fun = function () {
        if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
            $(".FrmBusyoMst.cmdUpdate").button("enable");
            $(".FrmBusyoMst.cmdDelete").button("enable");
            $(me.grid_id).jqGrid("setSelection", 0);
        } else {
            $(".FrmBusyoMst.cmdUpdate").button("disable");
            $(".FrmBusyoMst.cmdDelete").button("disable");
            return;
        }
        me.t = document.getElementById("FrmBusyoMst_sprList_pager_center");
        me.t.childNodes[1].innerHTML = "";
        me.doubleClickRow_fun();
    };
    me.YesDeleteFnc = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        var tmpdata1 = {
            busyoCd: rowData["BUSYO_CD"],
        };
        var deleteUrl = me.sys_id + "/" + me.id + "/" + "fncDeleteBusyo";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                if (result["data"].length > 0) {
                    me.fncBusyoSearchButtonClick();
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                clsComFnc.FncMsgBox("E0004");
                return;
            }
        };

        me.ajax.send(deleteUrl, tmpdata1, 1);
    };
    me.NoDeleteFnc = function () {
        return;
    };

    me.doubleClickRow_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.fncBusyoUpdateButtonClick();
            },
        });
    };

    me.openSubFormDialog_fnc = function () {
        $(me.subDialogId).html("");
        //dialog
        me.subForm_url = me.sys_id + "/" + "FrmBusyoMstEdit/index";

        data1 = "";
        me.ajax.receive = function (result) {
            $(me.subDialogId).html(result);
            $(me.subDialogId).dialog("open");
        };

        me.ajax.send(me.subForm_url, data1, 1);
    };
    me.closeSubFormDialog_fnc = function () {
        $(me.subDialogId).html("");
        $(me.subDialogId).dialog("close");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmBusyoMst = new R4.FrmBusyoMst();
    o_R4_FrmBusyoMst.load();
    o_R4K_R4K.FrmBusyoMst = o_R4_FrmBusyoMst;

    /*
	 o_R4K_R4K.FrmHendoKobetu.FrmBusyoSearch = o_R4_FrmBusyoSearch;
	 o_R4_FrmBusyoSearch.FrmHendoKobetu = o_R4K_R4K.FrmHendoKobetu;
	 */
});
