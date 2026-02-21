/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150819           #2078    						BUG                              yin
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmReOutReport");

R4.FrmReOutReport = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmReOutReport";
    me.sys_id = "R4K";
    me.cboYM = "";
    //20240701 caina del s
    // me.botType = false;
    //20240701 caina del s
    //INS:新規登録   UPD:修正
    me.PrpMenteFlg = "";
    //selected 入力日
    me.INP_DATE = "";
    me.flag = 1;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.option = {
        emptyRecordRow: false,
    };
    me.colModel = [
        {
            name: "INP_DATE",
            label: "完了日",
            index: "INP_DATE",
            sortable: false,
            width: 200,
        },
        {
            name: "GOUKEI",
            label: "合計",
            index: "GOUKEI",
            sortable: false,
            width: 200,
            align: "right",
            formatter: "integer",
        },
    ];

    me.controls.push({
        id: ".FrmReOutReport.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmReOutReport.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmReOutReport.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmReOutReport.cmdDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmReOutReport.cboDateFrom",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmReOutReport.cboDateTo",
        type: "datepicker",
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
        me.frmBusyoMst_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmReOutReport.cboDateFrom").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmReOutReport.cboDateFrom")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmReOutReport.cboDateFrom").val(me.cboYM);
                $(".FrmReOutReport.cboDateFrom").trigger("focus");
                $(".FrmReOutReport.cboDateFrom").select();
                $(".FrmReOutReport.cmdSearch").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmReOutReport.cmdSearch").button("enable");
        }
    });
    $(".FrmReOutReport.cboDateTo").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmReOutReport.cboDateTo")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmReOutReport.cboDateTo").val(me.cboYM);
                $(".FrmReOutReport.cboDateTo").trigger("focus");
                $(".FrmReOutReport.cboDateTo").select();
                $(".FrmReOutReport.cmdSearch").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmReOutReport.cmdSearch").button("enable");
        }
    });

    //検索
    $(".FrmReOutReport.cmdSearch").click(function () {
        //** 初期化（スプレッド、ボタン）
        $("#FrmReOutReport_sprList").jqGrid("clearGridData");
        $(".FrmReOutReport.cmdUpdate").button("disable");
        $(".FrmReOutReport.cmdDelete").button("disable");
        me.subSpreadReShow(false);
    });
    //新規登録
    $(".FrmReOutReport.cmdInsert").click(function () {
        //2015/08/19 yinhuaiyu modify start
        //20240701 caina upd s
        // if (me.botType == false) {
        //     me.botType = true;
        if ($(".FrmReOutReport.botType").html() == false) {
            $(".FrmReOutReport.botType").html(true);
            //20240701 caina upd e
            me.frmInputShow(1);
        }
        //2015/08/19 yinhuaiyu modify end
    });
    //修正
    $(".FrmReOutReport.cmdUpdate").click(function () {
        //2015/08/19 yinhuaiyu modify start
        //20240701 caina upd s
        // if (me.botType == false) {
        //     me.botType = true;
        if ($(".FrmReOutReport.botType").html() == false) {
            $(".FrmReOutReport.botType").html(true);
            //20240701 caina upd e
            me.frmInputShow(2);
        }
        //2015/08/19 yinhuaiyu modify end
    });

    //**********************************************************************
    //処 理 名：削除
    //関 数 名：cmdDelete_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：選択された行のデータを削除する
    //**********************************************************************
    $(".FrmReOutReport.cmdDelete").click(function () {
        //処理対象行未存在の場合
        var selRow = $("#FrmReOutReport_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        if (selRow == null) {
            //削除する行未選択
            me.clsComFnc.FncMsgBox("I0010");
            return;
        }
        //ﾒｯｾｰｼﾞﾎﾞｯｸｽ表示(削除してもよろしいですか？)
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "選択された完了日のデータを全て削除します。よろしいですか？"
        );
    });

    $("#FrmReOutReport_sprList").jqGrid({
        datatype: "local",
        emptyRecordRow: me.option.emptyRecordRow,
        height: "210",
        rownumbers: true,
        colModel: me.colModel,

        //選択行の修正画面を呼び出す   DoubleClick
        ondblClickRow: function () {
            //2015/08/19 yinhuaiyu modify start
            //20240701 caina upd s
            // if (me.botType == false) {
            //     me.botType = true;
            if ($(".FrmReOutReport.botType").html() == false) {
                $(".FrmReOutReport.botType").html(true);
                //20240701 caina upd e
                me.frmInputShow(2);
            }
            //2015/08/19 yinhuaiyu modify end
        },
    });
    //スプレッド上でエンター押下時に修正処理
    $("#FrmReOutReport_sprList").jqGrid("bindKeys", {
        onEnter: function () {
            var selRow = $("#FrmReOutReport_sprList").jqGrid(
                "getGridParam",
                "selrow"
            );
            //スプレッドが未選択の場合は無効
            if (selRow == null) {
                return;
            }
            //dialogを開く
            //2015/08/19 yinhuaiyu modify start
            //20240701 caina upd s
            // if (me.botType == false) {
            //     me.botType = true;
            if ($(".FrmReOutReport.botType").html() == false) {
                $(".FrmReOutReport.botType").html(true);
                //20240701 caina upd e
                me.frmInputShow(2);
            }
            //2015/08/19 yinhuaiyu modify end
        },
    });
    $("#FrmReOutReport_sprList").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });
    // dialog
    $("#FrmReOutReportEdit").dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 932,
        height: 520,
        close: function () {
            shortcut.remove("F9");
            me.subSpreadReShow(false);
            //2015/08/19 yinhuaiyu modify start
            //20240701 caina upd s
            // me.botType = false;
            $(".FrmReOutReport.botType").html(false);
            //20240701 caina upd e
            //2015/08/19 yinhuaiyu modify end
        },
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    me.frmBusyoMst_Load = function () {
        //20240701 caina ins s
        $(".FrmReOutReport.botType").html(false);
        //20240701 caina ins e
        //画面項目ｸﾘｱ
        $("#FrmReOutReport_sprList").jqGrid("clearGridData");
        //ボタンを非表示にする
        $(".FrmReOutReport.cmdUpdate").button("disable");
        $(".FrmReOutReport.cmdDelete").button("disable");

        var url = me.sys_id + "/" + me.id + "/" + "frmBusyoMst_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            $(".FrmReOutReport.cboDateFrom").trigger("focus");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    $(".FrmReOutReport.cboDateFrom").val(
                        me.clsComFnc.FncNv(result["data"][0]["TOUGETU"])
                    );
                    $(".FrmReOutReport.cboDateTo").val(
                        me.clsComFnc.FncNv(result["data"][0]["TOUGETU"])
                    );
                    me.cboYM = me.clsComFnc.FncNv(result["data"][0]["TOUGETU"]);
                    //スプレッドを表示
                    me.subSpreadReShow(true);
                } else {
                    $(".FrmReOutReport.cboDateFrom").datepicker(
                        "setDate",
                        new Date()
                    );
                    $(".FrmReOutReport.cboDateTo").datepicker(
                        "setDate",
                        new Date()
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };

    //**********************************************************************
    //処 理 名：データグリッドの再表示
    //関 数 名：subSpreadReShow
    //引    数：Boolean
    //戻 り 値：無し
    //処理説明：データグリッドを再表示する
    //**********************************************************************
    me.subSpreadReShow = function (blnStart) {
        //再生出庫データ取得
        var url = me.sys_id + "/" + me.id + "/" + "subSpreadReShow";
        var data = {
            cboDateFrom: $(".FrmReOutReport.cboDateFrom").val(),
            cboDateTo: $(".FrmReOutReport.cboDateTo").val(),
        };
        me.ajax.receive = function (result) {
            $("#FrmReOutReport_sprList").jqGrid("clearGridData");
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //該当データなし
                if (result["data"].length == 0) {
                    if (blnStart == false) {
                        //'該当するデータは存在しません。
                        $(".FrmReOutReport.cboDateFrom").trigger("focus");
                        me.clsComFnc.FncMsgBox("I0001");
                        //修正ボタンを使用不可に変更
                        $(".FrmReOutReport.cmdUpdate").button("disable");
                        //削除ボタンを使用不可に変更
                        $(".FrmReOutReport.cmdDelete").button("disable");
                        return;
                    }
                } else {
                    //スプレッドにデータリーダーの内容をセット
                    for (key in result["data"]) {
                        var columns = {
                            INP_DATE: result["data"][key]["INP_DATE"],
                            GOUKEI: result["data"][key]["GOUKEI"],
                        };
                        $("#FrmReOutReport_sprList").jqGrid(
                            "addRowData",
                            parseInt(key) + 1,
                            columns
                        );
                    }
                    //１行目を選択状態にする
                    $("#FrmReOutReport_sprList").jqGrid(
                        "setSelection",
                        1,
                        true
                    );
                    //修正ボタンを使用可に変更
                    $(".FrmReOutReport.cmdUpdate").button("enable");
                    //削除ボタンを使用可に変更
                    $(".FrmReOutReport.cmdDelete").button("enable");

                    //フォーカスを明細に移動する
                    if (blnStart == false) {
                        $("#FrmReOutReport_sprList").trigger("focus");
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //削除rowdata
    me.delRowData = function () {
        //テーブルの選択の行を第一列の値
        var selRow = $("#FrmReOutReport_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowdata = $("#FrmReOutReport_sprList").jqGrid("getRowData", selRow);
        var INP_DATE = rowdata["INP_DATE"];
        var url = me.sys_id + "/" + me.id + "/" + "fncDeleteSaiseiSyukko";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.subSpreadReShow(false);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, INP_DATE, 0);
    };

    //**********************************************************************
    //処 理 名：編集画面の表示
    //関 数 名：frmInputShow
    //引    数：
    //戻 り 値：無し
    //処理説明：編集画面の初期値設定、表示後
    //　　　　　データグリッドの再表示
    //**********************************************************************
    me.frmInputShow = function (mark) {
        //20240701 caina upd s
        // me.botType = true;
        $(".FrmReOutReport.botType").html(true);
        //20240701 caina upd e
        //新規登録の場合
        if (mark == 1) {
            me.PrpMenteFlg = "INS";
        }
        //修正の場合、画面表示初期値を設定する
        if (mark == 2) {
            //処理対象行未存在の場合
            var selRow = $("#FrmReOutReport_sprList").jqGrid(
                "getGridParam",
                "selrow"
            );
            if (selRow == null) {
                me.clsComFnc.FncMsgBox("I0010");
                //修正する行未選択
                return;
            } else {
                //プロパティーに値を設定
                me.PrpMenteFlg = "UPD";
                //テーブルの選択の行を第一列の値
                var rowdata = $("#FrmReOutReport_sprList").jqGrid(
                    "getRowData",
                    selRow
                );
                me.INP_DATE = rowdata["INP_DATE"];
            }
        }
        //入力画面を表示する
        var url = me.sys_id + "/" + "FrmReOutReportEdit" + "/" + "index";
        me.ajax.receive = function (result) {
            $("#FrmReOutReportEdit").html("");
            $("#FrmReOutReportEdit").html(result);
        };
        me.ajax.send(url, "", 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmReOutReport = new R4.FrmReOutReport();
    o_R4_FrmReOutReport.load();
    o_R4K_R4K_FrmReOutReport = o_R4_FrmReOutReport;
});
