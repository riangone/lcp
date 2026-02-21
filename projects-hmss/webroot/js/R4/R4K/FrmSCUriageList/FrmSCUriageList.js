/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150810           #1953     参照画面が大きすぎて１画面に表示しきれない                     FANZHENGZHOU
 * 20180124           #2807						   bug								YIN
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSCUriageList");

R4.FrmSCUriageList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    me.id = "FrmSCUriageList";
    me.sys_id = "R4K";
    me.grid_id = "#FrmSCUriageList_sprList";
    me.g_url = "R4K/FrmSCUriageList/subSpreadReShow";
    me.pager = "#FrmSCUriageList_pager";
    me.sidx = "";
    me.t = "";
    //注文書番号
    me.strCmnNO = "";

    me.option = {
        rowNum: 50,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
        loadui: "enable",
    };

    me.colModel = [
        {
            name: "CMN_NO",
            label: "注文番号",
            index: "CMN_NO",
            sortable: false,
            width: 90,
        },
        {
            name: "UC_NO",
            label: "ＵＣＮＯ",
            index: "UC_NO",
            sortable: false,
            width: 120,
        },
        {
            name: "BUSYO_NM",
            label: "部署",
            index: "BUSYO_NM",
            sortable: false,
            width: 130,
        },
        {
            name: "SYAINMEI",
            label: "社員",
            index: "SYAINMEI",
            sortable: false,
            width: 130,
        },
        {
            name: "KEIYAUMEI",
            label: "契約者名",
            index: "KEIYAUMEI",
            sortable: false,
            width: 190,
        },
        {
            name: "URG_DATE",
            label: "売上日",
            index: "URG_DATE",
            sortable: false,
            width: 90,
        },
        {
            name: "JKN_HKD",
            label: "条件変更日",
            index: "JKN_HKD",
            sortable: false,
            width: 90,
        },
        {
            name: "NAU_KB",
            label: "新中区分",
            index: "NAU_KB",
            sortable: false,
            hidden: true,
        },
        {
            name: "CEL_DATE",
            label: "解約日",
            index: "CEL_DATE",
            sortable: false,
            width: 90,
        },
        {
            name: "NAU_KB_NM",
            label: "新中",
            index: "NAU_KB_NM",
            sortable: false,
            width: 50,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSCUriageList.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSCUriageList.cmdAction",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //フォームロード
        me.FrmSCUriageList_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    // 修正画面のdialog
    $("#FrmSCUriageMeisai").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 1088,
        //---20150810 #1953 fanzhengzhou upd s.
        //height : 768,
        height: me.ratio === 1.5 ? 558 : 656,
        //---20150810 #1953 fanzhengzhou upd e.
        open: function () {},
        close: function () {},
    });

    //入力された検索条件で検索
    $(".FrmSCUriageList.cmdSearch.Enter.Tab").click(function () {
        //スプレッドを表示
        me.subSpreadReShow();
    });

    //表示ボタンクリック
    $(".FrmSCUriageList.cmdAction.Tab.Enter").click(function () {
        var selRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (selRow == null) {
            me.clsComFnc.FncMsgBox("I0010");
        } else {
            me.dgdShoukaiBack_Click();
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：FrmSCUriageList_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：各種初期設定
    //**********************************************************************
    me.FrmSCUriageList_Load = function () {
        $(".FrmSCUriageList.txtCMNNO.Enter.Tab").val();
        $(".FrmSCUriageList.txtUCNO.Enter.Tab").val();
        $(".FrmSCUriageList.txtKana.Enter.Tab").val();
        $(".FrmSCUriageList.txtBusyoCD.Enter.Tab").val();
        $(".FrmSCUriageList.txtTourokuNO.Enter.Tab").val();
        $(".FrmSCUriageList.txtEmpNO.Enter.Tab").val();
        $(".FrmSCUriageList.txtCarNO.Enter.Tab").val();
        $(".FrmSCUriageList.cmdAction.Tab.Enter").button("disable");
        $("#FrmSCUriageList_sprList").jqGrid("clearGridData");
        $(".FrmSCUriageList.txtCMNNO.Enter.Tab").trigger("focus");
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1040 : 1090
        );
        //20180124 YIN UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 202 : 233
        );
        //20180124 YIN UPD E
        $(".ui-paging-info").html("");
        me.sprList_CellDoubleClick();
        me.sprList_KeyDown();
    };
    //**********************************************************************
    //処 理 名：データグリッドの再表示
    //関 数 名：subSpreadReShow
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データグリッドを再表示する
    //**********************************************************************
    me.subSpreadReShow = function () {
        //スプレッドの表示を初期化
        $("#FrmSCUriageList_sprList").jqGrid("clearGridData");
        var data = {
            //注文書番号
            txtCMNNO: $(".FrmSCUriageList.txtCMNNO.Enter.Tab").val().trimEnd(),
            //UCNO
            txtUCNO: $(".FrmSCUriageList.txtUCNO.Enter.Tab").val().trimEnd(),
            //カナ
            txtKana: $(".FrmSCUriageList.txtKana.Enter.Tab").val().trimEnd(),
            //登録NO下4桁
            txtTourokuNO: $(".FrmSCUriageList.txtTourokuNO.Enter.Tab")
                .val()
                .trimEnd(),
            //部署コード
            txtBusyoCD: $(".FrmSCUriageList.txtBusyoCD.Enter.Tab")
                .val()
                .trimEnd(),
            //社員番号
            txtEmpNO: $(".FrmSCUriageList.txtEmpNO.Enter.Tab").val().trimEnd(),
            //CAR_NO
            txtCarNO: $(".FrmSCUriageList.txtCarNO.Enter.Tab").val().trimEnd(),
        };
        me.complete_fun = function (bErrorFlag) {
            //DB Error
            if (bErrorFlag == "error") {
                return;
            }
            //該当するデータは存在しません。
            if (bErrorFlag == "nodata") {
                //該当データなし
                $(".FrmSCUriageList.txtCMNNO.Enter.Tab").trigger("focus");
                me.clsComFnc.FncMsgBox("I0001");
                //表示ボタンを使用不可に変更
                $(".FrmSCUriageList.cmdAction.Tab.Enter").button("disable");
                return;
            } else {
                //if not do this,when tabkey down,there is something wrong.
                me.t = document.getElementById("FrmSCUriageList_pager_center");
                me.t.childNodes[1].innerHTML = "";

                //１行目を選択状態にする
                //$(me.grid_id).jqGrid('setSelection', 0);
                //$('.ui-paging-info').html('');
                //表示ボタンを使用可に変更
                $(".FrmSCUriageList.cmdAction.Tab.Enter").button("enable");
            }
            $("#FrmSCUriageList_sprList").trigger("focus");
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1040 : 1090
        );
        //20180124 YIN UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 202 : 233
        );
        //20180124 YIN UPD E
    };

    //**********************************************************************
    //処 理 名：選択行の修正画面を呼び出す
    //関 数 名：sprList_CellDoubleClick
    //引    数：無し
    //戻 り 値：無し
    //処理説明：DoubleClickのイベントを呼び出す
    //**********************************************************************
    me.sprList_CellDoubleClick = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                me.dgdShoukaiBack_Click();
            },
        });
    };
    //**********************************************************************
    //処 理 名：選択行の修正画面を呼び出す
    //関 数 名：sprList_KeyDown
    //引    数：無し
    //戻 り 値：無し
    //処理説明：Enter押下,修正画面を呼び出す
    //**********************************************************************
    me.sprList_KeyDown = function () {
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                me.dgdShoukaiBack_Click();
            },
        });
    };

    //**********************************************************************
    //処 理 名：編集画面の表示
    //関 数 名：dgdShoukaiBack_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：編集画面の初期値設定、表示後
    //　　　　　データグリッドの再表示
    //**********************************************************************
    me.dgdShoukaiBack_Click = function () {
        //テーブルの選択行を第一列の値
        var selRow = $("#FrmSCUriageList_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowdata = $("#FrmSCUriageList_sprList").jqGrid(
            "getRowData",
            selRow
        );
        me.strCmnNO = rowdata["CMN_NO"];
        var url = me.sys_id + "/" + "FrmSCUriageMeisai" + "/index";
        me.ajax.receive = function (result) {
            $("#FrmSCUriageMeisai").dialog(
                "option",
                "title",
                "新車・中古車売上データ参照"
            );
            $("#FrmSCUriageMeisai").dialog("open");
            $("#FrmSCUriageMeisai").html(result);
        };
        me.ajax.send(url, "", 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSCUriageList = new R4.FrmSCUriageList();
    o_R4_FrmSCUriageList.load();
    o_R4K_R4K_FrmSCUriageList = o_R4_FrmSCUriageList;
});
