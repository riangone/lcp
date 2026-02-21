Namespace.register("JKSYS.FrmKyotenFurikae");

JKSYS.FrmKyotenFurikae = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmKyotenFurikae";
    me.sys_id = "JKSYS";
    me.url = "";
    me.data = new Array();
    me.blnErrFlg = false;
    me.strTougetu = "";
    me.prpNengetu = "";
    me.prpEdaNO = "";
    me.prpCMN_NO = "";
    me.cboYM = "";
    me.g_url = "JKSYS/FrmKyotenFurikae/fncSearchFurikae";
    me.grid_id = "#JKSYS_FrmKyotenFurikae_sprList";
    me.pager = "";
    me.sidx = "";
    me.refreshFlg = false;
    me.ratio = window.devicePixelRatio || 1;

    me.option = {
        rowNum: 0,
        recordpos: "left",
        multiselect: false,
        rownumbers: true,
        rownumWidth: me.ratio === 1.5 ? 25 : 40,
        caption: "",
        multiselectWidth: 30,
        scroll: 50,
    };
    me.colModel = [
        {
            name: "NENGETU",
            label: "年月",
            index: "NENGETU",
            width: 90,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "EDA_NO",
            label: "SEQ_NO",
            index: "EDA_NO",
            width: 90,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "MOTO_SYAIN_CD",
            label: "社員番号",
            index: "MOTO_SYAIN_CD",
            width: 85,
            align: "left",
            sortable: false,
        },
        {
            name: "MOTO_SYAIN_NM",
            label: "社員名",
            index: "MOTO_SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "MOTO_KIN",
            label: "金額",
            index: "MOTO_KIN",
            width: me.ratio === 1.5 ? 97 : 120,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "SAKI_SYAIN_CD",
            label: "社員番号",
            index: "SAKI_SYAIN_CD",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "SAKI_SYAIN_NM",
            label: "社員名",
            index: "SAKI_SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "SAKI_KIN",
            label: "金額",
            index: "SAKI_KIN",
            width: me.ratio === 1.5 ? 97 : 120,
            align: "right",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
                defaultValue: "",
            },
        },
        {
            name: "CMN_NO",
            label: "注文書番号",
            index: "CMN_NO",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "UC_NO",
            label: "UC_NO",
            index: "UC_NO",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "DISP_MOJI",
            label: "表示文字",
            index: "DISP_MOJI",
            width: 135,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKyotenFurikae.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyotenFurikae.cmdInsert",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyotenFurikae.cmdUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyotenFurikae.cmdDelete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyotenFurikae.cboKeiriBi",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmKyotenFurikae.cmdSearch").click(function () {
        me.PrpMenteFlg = "SEA";
        me.cmdSearch_Click();
    });

    $(".FrmKyotenFurikae.cmdDelete").click(function () {
        me.frmInputShow("cmdDelete");
    });

    $(".FrmKyotenFurikae.cmdUpdate").click(function () {
        me.frmInputShow("cmdUpdate");
    });

    $(".FrmKyotenFurikae.cmdInsert").click(function () {
        me.frmInputShow("cmdInsert");
    });

    $(".FrmKyotenFurikae.txtDenpyoNOFrom").on("blur", function () {
        var txtDenpyoNOFrom = $(".FrmKyotenFurikae.txtDenpyoNOFrom")
            .val()
            .trimEnd();
        $(".FrmKyotenFurikae.txtDenpyoNOTo").val(txtDenpyoNOFrom);
    });
    //20191212 WY INS S
    //年月blur:空=>初期値 没变 保留日期为空时的效果
    $(".FrmKyotenFurikae.cboKeiriBi").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmKyotenFurikae.cboKeiriBi")) == false
        ) {
            $(".FrmKyotenFurikae.cboKeiriBi").val(me.cboYM);
            //ページ以外の領域またはページ内の領域をクリックした場合
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmKyotenFurikae.cboKeiriBi").trigger("focus");
                    $(".FrmKyotenFurikae.cboKeiriBi").select();
                }
                $(".FrmKyotenFurikae.cmdSearch").button("disable");
                $(".FrmKyotenFurikae.cmdInsert").button("disable");
                $(".FrmKyotenFurikae.cmdUpdate").button("disable");
                $(".FrmKyotenFurikae.cmdDelete").button("disable");
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmKyotenFurikae.cboKeiriBi").trigger("focus");
                        $(".FrmKyotenFurikae.cboKeiriBi").select();
                    }, 0);
                }
                $(".FrmKyotenFurikae.cmdSearch").button("disable");
                $(".FrmKyotenFurikae.cmdInsert").button("disable");
                $(".FrmKyotenFurikae.cmdUpdate").button("disable");
                $(".FrmKyotenFurikae.cmdDelete").button("disable");
            }
        } else {
            $(".FrmKyotenFurikae.cmdSearch").button("enable");
            $(".FrmKyotenFurikae.cmdInsert").button("enable");
            var data = $(me.grid_id).jqGrid("getRowData");
            if (data.length > 0) {
                $(".FrmKyotenFurikae.cmdUpdate").button("enable");
                $(".FrmKyotenFurikae.cmdDelete").button("enable");
            }
        }
    });
    //20191212 WY INS E

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.frmFurikae_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォーム初期化
     '関 数 名：frmFurikae_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */

    me.frmFurikae_Load = function () {
        me.blnErrFlg = false;
        //画面項目ｸﾘｱ
        me.subFormClear();

        me.url = me.sys_id + "/" + me.id + "/fncFurikae_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmKyotenFurikae").ympicker("disable");
                $(".FrmKyotenFurikae").attr("disabled", true);
                $(".FrmKyotenFurikae button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //当月年月
            me.strTougetu = me.clsComFnc.FncNv(result["data"]["TOUGETU"]);
            var arrTougetu = me.strTougetu.split("/");
            me.cboYM = arrTougetu[0] + arrTougetu[1];
            $(".FrmKyotenFurikae.cboKeiriBi").val(me.cboYM);

            //ボタンを非表示にする
            $(".FrmKyotenFurikae.cmdUpdate").button("disable");
            $(".FrmKyotenFurikae.cmdDelete").button("disable");

            //スプレッドを表示
            me.blnErrFlg = true;
            me.subSpreadReShow();
        };
        me.ajax.send(me.url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：検索ボタンクリック
     '関 数 名：cmdSearch_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.cmdSearch_Click = function () {
        //初期化（スプレッド、ボタン）
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmKyotenFurikae.cmdUpdate").button("disable");
        $(".FrmKyotenFurikae.cmdDelete").button("disable");
        //スプレッドを表示
        me.subSpreadReShow();
    };

    /*
     '**********************************************************************
     '処 理 名：編集画面の表示
     '関 数 名：frmInputShow
     '引    数：無し
     '戻 り 値：無し
     '処理説明：編集画面の初期値設定、表示後
     '　　　　　   データグリッドの再表示
     ''**********************************************************************
     */
    me.frmInputShow = function (sender) {
        if (sender == "cmdInsert") {
            //日付が同じかどうかを判断する
            if (
                me.strTougetu !=
                $(".FrmKyotenFurikae.cboKeiriBi").val().substring(0, 4) +
                    "/" +
                    $(".FrmKyotenFurikae.cboKeiriBi").val().substring(4, 6) +
                    "/" +
                    "01"
            ) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "処理年月以外は入力できません！"
                );
                return;
            }
            me.PrpMenteFlg = "INS";
        }
        //修正、削除の場合、画面表示初期値を設定する
        if (
            sender == "cmdUpdate" ||
            sender == "sprList" ||
            sender == "cmdDelete"
        ) {
            var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
            var rowData = $(me.grid_id).jqGrid("getRowData", rowID);
            //処理対象行未存在の場合
            if (rowID == null || rowID == "") {
                me.clsComFnc.FncMsgBox("I0010");
                return;
            } else {
                if (
                    me.strTougetu !=
                    $(".FrmKyotenFurikae.cboKeiriBi").val().substring(0, 4) +
                        "/" +
                        $(".FrmKyotenFurikae.cboKeiriBi")
                            .val()
                            .substring(4, 6) +
                        "/" +
                        "01"
                ) {
                    if (sender == "cmdUpdate" || sender == "sprList") {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "処理年月以外は修正できません！"
                        );
                        return;
                    } else {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "処理年月以外は削除できません！"
                        );
                        return;
                    }
                }
                //プロパティーに値を設定
                if (sender == "cmdDelete") {
                    me.PrpMenteFlg = "DEL";
                } else {
                    me.PrpMenteFlg = "UPD";
                }
                me.prpNengetu = rowData["NENGETU"];
                me.prpEdaNO = rowData["EDA_NO"];
                me.prpCMN_NO = rowData["CMN_NO"];
            }
        }
        me.ShowDialog();
    };
    me.ShowDialog = function () {
        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                strNengetu: me.prpNengetu,
                strEdaNO: me.prpEdaNO,
                strCMN_NO: me.prpCMN_NO,
                strMenteFlg: me.PrpMenteFlg,
            })
        );
        var frmId = "FrmKyotenFurikaeEdit";
        me.url = me.sys_id + "/" + frmId;

        me.ajax.receive = function (result) {
            function before_close() {
                shortcut.remove("F9");
                me.blnErrFlg = false;
                if (me.refreshFlg) {
                    me.subSpreadReShow();
                    me.refreshFlg = false;
                }
            }
            $("." + me.id + "." + "dialogsFrmKyotenFurikaeEdit").hide();
            $("." + me.id + "." + "dialogsFrmKyotenFurikaeEdit").append(result);
            o_JKSYS_JKSYS.FrmKyotenFurikae.FrmKyotenFurikaeEdit.before_close =
                before_close;
        };

        me.ajax.send(me.url, "", 0);
    };

    /*
     '**********************************************************************
     '処 理 名：データグリッドの再表示
     '関 数 名：subSpreadReShow
     '引    数：無し
     '戻 り 値：無し
     '処理説明：データグリッドを再表示する
     '**********************************************************************
     */
    me.subSpreadReShow = function () {
        //スプレッドの表示を初期化
        $(me.grid_id).jqGrid("clearGridData");
        var cboKeiriBi =
            $(".FrmKyotenFurikae.cboKeiriBi").val().substring(0, 4) +
            "/" +
            $(".FrmKyotenFurikae.cboKeiriBi").val().substring(4, 6);
        var txtSyainNO = $(".FrmKyotenFurikae.txtSyainNO").val().trimEnd();
        var txtCmnNO = $(".FrmKyotenFurikae.txtCmnNO").val().trimEnd();
        var arr = {
            NENGETU: cboKeiriBi,
            SYAIN: txtSyainNO,
            CMNNO: txtCmnNO,
        };
        var complete_fun = function (_bErrorFlag, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var arrIds = $(me.grid_id).jqGrid("getDataIDs");
            if (arrIds.length == 0) {
                //該当データなし
                if (!me.blnErrFlg) {
                    me.clsComFnc.ObjFocus = $(".FrmKyotenFurikae.cboKeiriBi");
                    if (
                        me.PrpMenteFlg == "UPD" ||
                        me.PrpMenteFlg == "DEL" ||
                        me.PrpMenteFlg == "SEA"
                    ) {
                        //該当するデータは存在しません。
                        me.clsComFnc.FncMsgBox("I0001");
                        return;
                    }
                }
                //フォーカスを明細に移動する
                $(".FrmKyotenFurikae.cboKeiriBi").trigger("focus");
                $(".FrmKyotenFurikae.cboKeiriBi").select();
                //修正、削除ボタンを使用不可に変更
                $(".FrmKyotenFurikae.cmdUpdate").button("disable");
                $(".FrmKyotenFurikae.cmdDelete").button("disable");
            } else {
                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", "0");
                $(".FrmKyotenFurikae.cmdUpdate").button("enable");
                $(".FrmKyotenFurikae.cmdDelete").button("enable");
            }
        };
        if (me.blnErrFlg) {
            //jqgridが存在しない場合
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                arr,
                complete_fun
            );
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "MOTO_SYAIN_CD",
                        numberOfColumns: 3,
                        titleText: "振替元",
                    },
                    {
                        startColumnName: "SAKI_SYAIN_CD",
                        numberOfColumns: 3,
                        titleText: "振替先",
                    },
                ],
            });

            me.sprList_CellDoubleClick();
            me.sprList_KeyDown();
        } else {
            //jqgridが存在場合
            if ($("#gview_JKSYS_FrmKyotenFurikae_sprList").length > 0) {
                //スプレッドに取得データをセットする
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    arr,
                    complete_fun
                );
            } else {
                //スプレッドに取得データをセットする
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option,
                    arr,
                    complete_fun
                );
            }
        }

        $(".FrmKyotenFurikae.cboKeiriBi").trigger("focus");
        $(".FrmKyotenFurikae.cboKeiriBi").select();
        $(me.grid_id).jqGrid("setSelection", "0");
        var width = me.ratio === 1.5 ? 1020 : 1080;
        var height = me.ratio === 1.5 ? 222 : 338;
        gdmz.common.jqgrid.set_grid_width(me.grid_id, width);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, height);
        me.blnErrFlg = false;
    };

    /*
     '**********************************************************************
     '処 理 名：選択行の修正画面を呼び出す
     '関 数 名：sprList_CellDoubleClick
     '引    数：無し
     '戻 り 値：無し
     '処理説明：修正ボタン押下のイベントを呼び出す
     '**********************************************************************
     */
    me.sprList_CellDoubleClick = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (_rowId, _iRow, _iCol, _e) {
                me.frmInputShow("sprList");
            },
        });
    };
    /*
     '**********************************************************************
     '処 理 名：押下時に修正処理
     '関 数 名：sprList_KeyDown
     '引    数：無し
     '戻 り 値：無し
     '処理説明：スプレッド上でエンター押下時に修正処理
     '**********************************************************************
     */
    me.sprList_KeyDown = function () {
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function (_rowid) {
                me.frmInputShow("sprList");
            },
        });
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目ｸﾘｱ
     '関 数 名：subFormClear
     '引    数：無し
     '戻 り 値：無し
     '処理説明：画面項目ｸﾘｱ
     '**********************************************************************
     */
    me.subFormClear = function () {
        $(".FrmKyotenFurikae.txtCmnNO").val("");
        $(".FrmKyotenFurikae.txtSyainNO").val("");
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_JKSYS_FrmKyotenFurikae = new JKSYS.FrmKyotenFurikae();
    o_JKSYS_FrmKyotenFurikae.load();
    o_JKSYS_JKSYS.FrmKyotenFurikae = o_JKSYS_FrmKyotenFurikae;
});
