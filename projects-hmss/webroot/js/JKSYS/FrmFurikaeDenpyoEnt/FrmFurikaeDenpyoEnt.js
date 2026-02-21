/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmFurikaeDenpyoEnt");

JKSYS.FrmFurikaeDenpyoEnt = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.jksys = new JKSYS.JKSYS();
    me.id = "FrmFurikaeDenpyoEnt";

    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    // jqgrid
    //他部署振替者氏名および振替先
    me.grid_tf_id = "#JKSYS_FrmFurikaeDenpyoEnt_sprList1";
    //長期欠勤連絡
    me.grid_fk_id = "#JKSYS_FrmFurikaeDenpyoEnt_sprList2";

    //更新日(非表示項目)
    me._strHiddUpdDate1 = "";
    me._strHiddUpdDate2 = "";

    me.upsel = "";
    me.nextsel = "";
    me.sidx = "";
    me.pager = "";

    me.jinjiYM = "";
    //社員番号,名称データ
    me.name_syain = [];
    //振替先部署名番号,名称データ
    me.name_busyoMoto = [];
    //振替元部署名番号,名称データ
    me.name_busyoSaki = [];
    me.flg_reload = false;

    me.focus_flag = "";
    me.option_TF = {
        rownumbers: true,
        rownumWidth: me.ratio === 1.5 ? 25 : 40,
        caption: "",
        multiselect: false,
        rowNum: 0,
    };

    me.colModel_TF = [
        {
            name: "SYAIN_NO",
            label: "社員ID",
            index: "SYAIN_NO",
            sortable: false,
            editable: true,
            width: 46,
            editoptions: {
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getSyainName(e.target);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.getSyainName(e.target);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "btnSyainSearch",
            label: "検索",
            index: "btnSyainSearch",
            width: me.ratio === 1.5 ? 35 : 50,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            sortable: false,
            width: me.ratio === 1.5 ? 100 : 120,
        },
        {
            name: "FRI_MOTO_BUSYO_CD",
            label: "振替元部署コード",
            index: "FRI_MOTO_BUSYO_CD",
            sortable: false,
            width: me.ratio === 1.5 ? 70 : 90,
        },
        {
            name: "BUSYO_NM1",
            label: "振替元部署名",
            index: "BUSYO_NM1",
            sortable: false,
            width: 120,
        },
        {
            name: "FRI_SAKI_BUSYO_CD",
            label: "振替先部署コード",
            index: "FRI_SAKI_BUSYO_CD",
            sortable: true,
            editable: true,
            width: 90,
            editoptions: {
                dataEvents: [
                    {
                        type: "blur",
                        fn: function (e) {
                            me.getBusyoName(e.target);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            if (key == 38 || key == 40) {
                                me.getBusyoName(e.target);
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "btnBusyoSearch",
            label: "検索",
            index: "btnBusyoSearch",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_NM2",
            label: "振替先部署名",
            index: "BUSYO_NM2",
            sortable: false,
            width: 120,
        },
        {
            name: "BIKOU",
            label: "備考",
            index: "BIKOU",
            sortable: false,
            editable: true,
            width: 278,
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            sortable: false,
            width: 90,
            hidden: true,
        },
        {
            name: "CRE_SYA_CD",
            label: "作成者",
            index: "CRE_SYA_CD",
            sortable: false,
            width: 90,
            hidden: true,
        },
        {
            name: "CRE_PRG_ID",
            label: "作成APP",
            index: "CRE_PRG_ID",
            sortable: false,
            width: 90,
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "更新日",
            index: "UPD_DATE",
            sortable: false,
            width: 90,
            hidden: true,
        },
    ];

    me.option_FK = {
        rownumbers: true,
        rownumWidth: 40,
        caption: "",
        rowNum: 0,
        multiselect: false,
    };
    me.colModel_FK = [
        {
            name: "SYAIN_NO",
            label: "社員番号",
            index: "SYAIN_NO",
            sortable: false,
            align: "left",
            width: 100,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            sortable: false,
            align: "left",
            width: 120,
        },
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_NM",
            label: "部署名",
            index: "BUSYO_NM",
            sortable: false,
            width: 120,
        },
        {
            name: "SYUKKIN_RITU",
            label: "出勤率(%)",
            index: "SYUKKIN_RITU",
            sortable: false,
            width: 90,
            formatter: "number",
            align: "right",
            formatoptions: {
                decimalSeparator: ".",
                decimalPlaces: 1,
                defaultValue: "",
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            sortable: false,
            hidden: true,
        },
        {
            name: "CRE_SYA_CD",
            label: "作成者",
            index: "CRE_SYA_CD",
            sortable: false,
            hidden: true,
        },
        {
            name: "CRE_PRG_ID",
            label: "作成APP",
            index: "CRE_PRG_ID",
            sortable: false,
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "更新日",
            index: "UPD_DATE",
            sortable: false,
            hidden: true,
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnKensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnRowAdd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnRowDel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnChange",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.btnEnt",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeDenpyoEnt.dtpTaisyouYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.jksys.Shift_TabKeyDown();
    //Tabキーのバインド
    me.jksys.TabKeyDown();
    //Enterキーのバインド
    me.jksys.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //条件変更ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnChange").click(function () {
        me.btnChange_Click();
    });
    //検索ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnKensaku").click(function () {
        me.focus_flag = "btnKensaku";

        me.btnKensaku_Click();
    });
    //行追加ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnRowAdd").click(function () {
        me.btnRowAdd_Click();
    });
    //行削除ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnRowDel").click(function () {
        me.btnRowDel_Click();
    });
    //登録ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnEnt").click(function () {
        me.focus_flag = "btnEnt";

        me.btnEnt_Click();
    });
    //Excel出力ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnExcel").click(function () {
        me.btnExcel_Click();
    });
    //削除ボタンクリック
    $(".FrmFurikaeDenpyoEnt.btnDelete").click(function () {
        me.btnDelete_Click();
    });

    //年月blur
    $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmFurikaeDenpyoEnt.dtpTaisyouYM")) ==
            false
        ) {
            $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(me.jinjiYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").focus();
                    $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").focus();
                        $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").select();
                    }, 0);
                }
            }

            $(".FrmFurikaeDenpyoEnt.btnKensaku").button("disable");
            $(".FrmFurikaeDenpyoEnt.btnChange").button("disable");
        } else {
            $(".FrmFurikaeDenpyoEnt.btnKensaku").button("enable");
            $(".FrmFurikaeDenpyoEnt.btnChange").button("enable");
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

        if ($(window).height() < 885) {
            // 画面内容较多，IE显示不全，追加纵向滚动条
            $(".JKSYS.JKSYS-layout-center").css("overflow-y", "scroll");
            // 追加滚动条后调小宽度
            $(".JKSYS-content-fixed-width").css(
                "width",
                me.ratio === 1.5 ? "1030px" : "1100px"
            );
        }

        me.Formit();
    };
    //画面初期化(画面起動時)
    me.Formit = function (fncComplete) {
        //画面初期化(一覧)
        me.Formit2();
        //画面初期化データ取得
        var url = me.sys_id + "/" + me.id + "/" + "frmFurikaeDenpyoEnt_load";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                //データ取得(人事コントロールマスタ)
                me.jinjiYM = result["data"]["SYORI_YM"];

                if (!me.flg_reload) {
                    $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(me.jinjiYM);
                    //社員署名取得
                    if (result["data"]["SyainMst"].length > 0) {
                        me.name_syain = result["data"]["SyainMst"];
                    }
                    //振替元部署名取得
                    if (result["data"]["BusyoMoto"].length > 0) {
                        me.name_busyoMoto = result["data"]["BusyoMoto"];
                    }
                    //振替先部署取得
                    if (result["data"]["BusyoSaki"].length > 0) {
                        me.name_busyoSaki = result["data"]["BusyoSaki"];
                    }
                }
                //jqgrid読み込み
                me.jqgridInit(fncComplete);
                //対象年月
                $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").ympicker("disable");
                //検索ボタン
                $(".FrmFurikaeDenpyoEnt.btnKensaku").button("disable");
                //条件変更
                $(".FrmFurikaeDenpyoEnt.btnChange").button("enable");
            } else {
                $(".FrmFurikaeDenpyoEnt").ympicker("disable");
                $(".FrmFurikaeDenpyoEnt").attr("disabled", true);
                $(".FrmFurikaeDenpyoEnt button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, "", 0);
    };

    me.jqgridInit = function (fncComplete) {
        //データ取得(人件費他部署振替データ)
        var url = me.sys_id + "/" + me.id + "/" + "fncGetJKTFDAT_load";
        var data = {
            dtpTaisyouYM: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(),
            jinjiYM: me.jinjiYM,
        };
        var complete_fun_tf = function (obj) {
            if (obj && obj.responseJSON && obj.responseJSON["error"]) {
                me.clsComFnc.FncMsgBox("E9999", obj.responseJSON["error"]);
                return;
            }
            //データ取得(人件費振替長期欠勤者データ)
            var url = me.sys_id + "/" + me.id + "/" + "fncGetJKFKDAT_load";
            var data_DT3 = {
                date: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(),
                flgReload: "",
            };
            var complete_fun_fk_DT3 = function (obj) {
                if (obj && obj.responseJSON && obj.responseJSON["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", obj.responseJSON["error"]);
                    return;
                }
                //人件費他部署振替データ
                var DT2 = $(me.grid_tf_id).jqGrid("getRowData");
                //長期欠勤連絡
                var DT3 = $(me.grid_fk_id).jqGrid("getRowData");
                //登録セルフォーカス
                if (fncComplete) fncComplete();
                //データが存在する場合(人件費他部署振替 OR 人件費振替長期欠勤者)
                if (DT2.length > 0 || DT3.length > 0) {
                    if (me.focus_flag != "btnEnt") {
                        $(me.grid_tf_id).jqGrid("setSelection", 0, true);
                    }

                    //更新日の最大値を取得
                    //人件費他部署振替
                    me._strHiddUpdDate1 = me.maxUpdDate(DT2);
                    //人件費振替長期欠勤者
                    me._strHiddUpdDate2 = me.maxUpdDate(DT3);

                    if (!me.flg_reload) {
                        $(".FrmFurikaeDenpyoEnt.lblState").html("修正");
                    }

                    //Excel出力
                    $(".FrmFurikaeDenpyoEnt.btnExcel").button("enable");
                    //削除
                    $(".FrmFurikaeDenpyoEnt.btnDelete").button("enable");
                } else {
                    //新規であり、人件費他部署振替データが無い場合
                    if (!me.flg_reload) {
                        $(".FrmFurikaeDenpyoEnt.lblState").html("新規");
                    }

                    //データ取得(勤怠データ)
                    var data_DT4 = {
                        date: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(),
                        flgReload: 1,
                    };
                    var complete_fun_fk_DT4 = function (obj) {
                        if (
                            obj &&
                            obj.responseJSON &&
                            obj.responseJSON["error"]
                        ) {
                            me.clsComFnc.FncMsgBox(
                                "E9999",
                                obj.responseJSON["error"]
                            );
                            return;
                        }
                    };
                    if (me.flg_reload) {
                        if (
                            !(
                                $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val() <
                                me.jinjiYM
                            )
                        ) {
                            //長期欠勤連絡
                            gdmz.common.jqgrid.reloadGridOptions(
                                me.grid_fk_id,
                                data_DT4,
                                complete_fun_fk_DT4
                            );
                        }
                    } else {
                        //長期欠勤連絡
                        gdmz.common.jqgrid.reloadGridOptions(
                            me.grid_fk_id,
                            data_DT4,
                            complete_fun_fk_DT4
                        );
                        if (DT2.length == 0) {
                            //空行追加
                            me.btnRowAdd_Click();
                        }
                    }
                    //'ボタン活性・非活性
                    //Excel出力
                    $(".FrmFurikaeDenpyoEnt.btnExcel").button("disable");
                    //削除
                    $(".FrmFurikaeDenpyoEnt.btnDelete").button("disable");
                    //人件費他部署振替
                    me._strHiddUpdDate1 = "";
                    //人件費振替長期欠勤者
                    me._strHiddUpdDate2 = "";
                }
                if (me.flg_reload) {
                    //画面.対象年月 < 人事コントロールマスタ.処理年月の場合
                    if (
                        $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val() <
                        me.jinjiYM
                    ) {
                        //行追加
                        $(".FrmFurikaeDenpyoEnt.btnRowAdd").button("disable");
                        //行削除
                        $(".FrmFurikaeDenpyoEnt.btnRowDel").button("disable");
                        //登録
                        $(".FrmFurikaeDenpyoEnt.btnEnt").button("disable");
                        //削除
                        $(".FrmFurikaeDenpyoEnt.btnDelete").button("disable");
                    } else {
                        if (DT2.length > 0 || DT3.length > 0) {
                            $(".FrmFurikaeDenpyoEnt.lblState").html("修正");
                        } else {
                            $(".FrmFurikaeDenpyoEnt.lblState").html("新規");
                            //空行追加
                            me.btnRowAdd_Click();
                        }
                        //行追加
                        $(".FrmFurikaeDenpyoEnt.btnRowAdd").button("enable");
                        //行削除
                        $(".FrmFurikaeDenpyoEnt.btnRowDel").button("enable");
                        //登録
                        $(".FrmFurikaeDenpyoEnt.btnEnt").button("enable");
                    }
                    //削除ボタフォカス
                    if (me.focus_flag == "btnKensaku") {
                        $(".FrmFurikaeDenpyoEnt.btnDelete").trigger("focus");
                    }
                }
            };
            //長期欠勤連絡
            if (me.flg_reload) {
                gdmz.common.jqgrid.reloadGridOptions(
                    me.grid_fk_id,
                    data_DT3,
                    complete_fun_fk_DT3
                );
            } else {
                gdmz.common.jqgrid.showGridOptions(
                    me.grid_fk_id,
                    url,
                    me.colModel_FK,
                    me.pager,
                    me.sidx,
                    me.option_FK,
                    data_DT3,
                    complete_fun_fk_DT3
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_fk_id, 610);
                gdmz.common.jqgrid.set_grid_height(me.grid_fk_id, 130);
                //jqgrid_FK設定
                me.setJqgridFK();
            }

            //jqgrid_TF設定
            me.Formit3(me.jinjiYM);
        };
        if (me.flg_reload) {
            //他部署振替者氏名および振替先
            gdmz.common.jqgrid.reloadGridOptions(
                me.grid_tf_id,
                data,
                complete_fun_tf
            );
        } else {
            //他部署振替者氏名および振替先
            gdmz.common.jqgrid.showGridOptions(
                me.grid_tf_id,
                url,
                me.colModel_TF,
                me.pager,
                me.sidx,
                me.option_TF,
                data,
                complete_fun_tf
            );
            gdmz.common.jqgrid.set_grid_width(
                me.grid_tf_id,
                me.ratio === 1.5 ? 1009 : 1078
            );
            gdmz.common.jqgrid.set_grid_height(me.grid_tf_id, 208);
            //jqgrid_TF設定
            me.setJqgridTF();
        }
    };
    me.setJqgridTF = function () {
        $(me.grid_tf_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SYAIN_NO",
                    numberOfColumns: 3,
                    titleText: "社員",
                },
                {
                    startColumnName: "FRI_MOTO_BUSYO_CD",
                    numberOfColumns: 2,
                    titleText: "振替元",
                },
                {
                    startColumnName: "FRI_SAKI_BUSYO_CD",
                    numberOfColumns: 3,
                    titleText: "振替先",
                },
            ],
        });
        //タイトルを削除
        $(me.grid_tf_id + "_SYAIN_NO").remove();
        $(me.grid_tf_id + "_btnSyainSearch").remove();
        $(me.grid_tf_id + "_SYAIN_NM").remove();

        $(me.grid_tf_id + "_BUSYO_NM1").remove();
        $(me.grid_tf_id + "_FRI_MOTO_BUSYO_CD").remove();

        $(me.grid_tf_id + "_FRI_SAKI_BUSYO_CD").remove();
        $(me.grid_tf_id + "_btnBusyoSearch").remove();
        $(me.grid_tf_id + "_BUSYO_NM2").remove();
        $(me.grid_tf_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                if ($(me.grid_tf_id).getColProp("SYAIN_NO").editable) {
                    if (typeof e != "undefined") {
                        var cellIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        //ヘッダークリック以外
                        if (cellIndex != 0) {
                            if (rowid && rowid != me.lastsel) {
                                $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
                                me.lastsel = rowid;
                            }
                            if (cellIndex === 6 || cellIndex === 9) {
                                $(me.grid_tf_id).jqGrid("editRow", rowid, {
                                    focusField: cellIndex,
                                });
                            } else {
                                $(me.grid_tf_id).jqGrid("editRow", rowid, true);
                                setTimeout(function () {
                                    var selNextId = "#" + rowid + "_SYAIN_NO";
                                    $(selNextId).focus();
                                }, 0);
                            }
                            // $("input,select", e.target).focus();
                        } else {
                            $(me.grid_tf_id).jqGrid("editRow", rowid, true);
                        }
                    } else {
                        if (rowid && rowid != me.lastsel) {
                            $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
                            me.lastsel = rowid;
                        }
                        $(me.grid_tf_id).jqGrid("editRow", rowid, {
                            focusField: false,
                        });
                    }
                    var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_tf_id,
                        e,
                        me.lastsel
                    );
                    if (up_next_sel && up_next_sel.length == 2) {
                        me.upsel = up_next_sel[0];
                        me.nextsel = up_next_sel[1];
                    }
                }
            },
            //ヘッダー選択を無効にする
            beforeSelectRow: function (_rowid, e) {
                var cellIndex = e.target.cellIndex;
                if (cellIndex == 0) {
                    var selNextId = "#" + me.lastsel + "_SYAIN_NO";
                    $(selNextId).focus();
                    $(selNextId).select();
                    return false;
                }
                return true;
            },
        });
        $(me.grid_tf_id).jqGrid("bindKeys");
    };
    me.setJqgridFK = function () {
        $(me.grid_fk_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SYAIN_NO",
                    numberOfColumns: 2,
                    titleText: "社員",
                },
                {
                    startColumnName: "BUSYO_CD",
                    numberOfColumns: 2,
                    titleText: "部署",
                },
            ],
        });
        //タイトルを削除
        $(me.grid_fk_id + "_SYAIN_NO").remove();
        $(me.grid_fk_id + "_SYAIN_NM").remove();
        $(me.grid_fk_id + "_BUSYO_CD").remove();
        $(me.grid_fk_id + "_BUSYO_NM").remove();
        $(".ui-jqgrid-labels.jqg-third-row-header").remove();
        $(me.grid_fk_id).jqGrid("bindKeys");
    };
    //更新日の最大値を取得
    me.maxUpdDate = function (data) {
        if (data && data.length > 0) {
            data.sort(function (a, b) {
                var update1 = a.UPD_DATE;
                var update2 = b.UPD_DATE;
                return update1 < update2 ? 1 : -1;
            });
            return data[0]["UPD_DATE"];
        }
        return "";
    };
    //スプレッド設定
    me.Formit3 = function (jinjiYM) {
        var dtpTaisyouYM = $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val();
        if (dtpTaisyouYM < jinjiYM) {
            $(me.grid_tf_id).setColProp("SYAIN_NO", {
                editable: false,
            });
            $(me.grid_tf_id).setColProp("FRI_SAKI_BUSYO_CD", {
                editable: false,
            });
            $(me.grid_tf_id).setColProp("BIKOU", {
                editable: false,
            });
        } else {
            $(me.grid_tf_id).setColProp("SYAIN_NO", {
                editable: true,
            });
            $(me.grid_tf_id).setColProp("FRI_SAKI_BUSYO_CD", {
                editable: true,
            });
            $(me.grid_tf_id).setColProp("BIKOU", {
                editable: true,
            });
        }
    };
    //登録ボタンクリック
    me.btnEnt_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.Update;
        me.clsComFnc.FncMsgBox("QY010");
    };
    me.Update = function () {
        //入力チェック
        if (me.InPutCheck()) {
            if (me.InPutCheck2()) {
                me.UpdateAction();
            }
        }
    };
    me.UpdateAction = function () {
        $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
        $(me.grid_fk_id).jqGrid("saveRow", me.lastsel2);
        var rowDataTfs = $(me.grid_tf_id).jqGrid("getRowData");
        var rowDataFks = $(me.grid_fk_id).jqGrid("getRowData");
        var url = me.sys_id + "/" + me.id + "/" + "Ent_Click";
        var data = {
            dtpYM: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").val(),
            strHiddUpdDate1: me._strHiddUpdDate1,
            strHiddUpdDate2: me._strHiddUpdDate2,
            DataTF: rowDataTfs,
            DataFK: rowDataFks,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W0018") {
                    $(me.grid_tf_id).jqGrid("setSelection", 0, true);
                    me.clsComFnc.ObjFocus = $("#0_SYAIN_NO");
                    me.clsComFnc.FncMsgBox("W0018", "更新日");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            } else {
                //完了メッセージ
                me.btnKensaku_Click(function () {
                    $(me.grid_tf_id).jqGrid("setSelection", 0, true);
                    me.clsComFnc.ObjFocus = $("#0_SYAIN_NO");
                    me.clsComFnc.FncMsgBox("I9999", "登録完了しました。");
                });
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.InPutCheck = function () {
        //一覧(他部署振替)が表示されている
        $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
        var ids = $(me.grid_tf_id).jqGrid("getDataIDs");
        var rowdata = "";
        //社員番号
        var strSyainNo = "";
        //振替元部署コード
        var strMotoBusyoCD = "";
        //振替先部署コード
        var strSakiBusyoCD = "";
        //備考
        var strBikou = "";
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_tf_id).jqGrid("getRowData", ids[i]);
            strSyainNo = me.clsComFnc.FncNv(rowdata["SYAIN_NO"]);
            strMotoBusyoCD = me.clsComFnc.FncNv(rowdata["FRI_MOTO_BUSYO_CD"]);
            strSakiBusyoCD = me.clsComFnc.FncNv(rowdata["FRI_SAKI_BUSYO_CD"]);
            strBikou = me.clsComFnc.FncNv(rowdata["BIKOU"]);
            if (
                strSyainNo == "" &&
                strMotoBusyoCD == "" &&
                strSakiBusyoCD == "" &&
                strBikou == ""
            ) {
                continue;
            }
            //社員番号未入力チェック
            if (strSyainNo == "") {
                $(me.grid_tf_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0001", "社員番号");
                return false;
            }
            //社員番号
            var found_array = me.name_syain.filter(function (element) {
                return element["SYAIN_NO"] == strSyainNo;
            });
            if (found_array.length == 0) {
                $(me.grid_tf_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_SYAIN_NO");
                me.clsComFnc.FncMsgBox("W0008", "社員");
                return false;
            }
            //振替元部署コード
            if (strMotoBusyoCD != "") {
                found_array = me.name_busyoMoto.filter(function (element) {
                    return element["BUSYO_CD"] == strMotoBusyoCD;
                });
                if (found_array.length == 0) {
                    $(me.grid_tf_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $("#" + ids[i] + "_SYAIN_NO");
                    me.clsComFnc.FncMsgBox("W0008", "振替元部署コード");
                    return false;
                }
            }
            //振替先部署コード未入力チェック
            if (strSakiBusyoCD == "") {
                $(me.grid_tf_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_FRI_SAKI_BUSYO_CD");
                me.clsComFnc.FncMsgBox("W0001", "振替先部署コード");
                return false;
            }
            //振替先部署コード
            found_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == strSakiBusyoCD;
            });
            if (found_array.length == 0) {
                $(me.grid_tf_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_FRI_SAKI_BUSYO_CD");
                me.clsComFnc.FncMsgBox("W0008", "振替先部署コード");
                return false;
            }
            //重複チェック
            var Syainarr = $(me.grid_tf_id).jqGrid("getCol", "SYAIN_NO");
            var allRowsId = $(me.grid_tf_id).jqGrid("getDataIDs");
            for (var i2 = i + 1; i2 < Syainarr.length; i2++) {
                //最後の重複番号の行ID
                if (
                    me.clsComFnc.FncNv(Syainarr[i]) ==
                    me.clsComFnc.FncNv(Syainarr[i2])
                ) {
                    $(me.grid_tf_id).jqGrid(
                        "setSelection",
                        allRowsId[i2],
                        true
                    );
                    me.clsComFnc.ObjFocus = $(
                        "#" + allRowsId[i2] + "_SYAIN_NO"
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "社員番号が重複しています。(" + Syainarr[i2] + ")"
                    );
                    return false;
                }
            }
        }
        //一覧(長期欠勤)が表示されている
        var records_fk = $(me.grid_fk_id).jqGrid("getGridParam", "records");
        if (records_fk) {
            var ids_fk = $(me.grid_fk_id).jqGrid("getDataIDs");
            for (var i = 0; i < ids_fk.length; i++) {
                rowdata = $(me.grid_fk_id).jqGrid("getRowData", ids_fk[i]);
                strSyainNo = me.clsComFnc.FncNv(rowdata["SYAIN_NO"]);
                var found_array = me.name_syain.filter(function (element) {
                    return element["SYAIN_NO"] == strSyainNo;
                });
                if (found_array.length == 0) {
                    $(me.grid_fk_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.FncMsgBox("W0008", "社員");
                    return false;
                }
            }
        }
        return true;
    };
    me.InPutCheck2 = function () {
        //一覧(他部署振替)が表示されている
        $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
        var ids = $(me.grid_tf_id).jqGrid("getDataIDs");

        var strBiko = "";
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_tf_id).jqGrid("getRowData", ids[i]);
            strBiko = me.clsComFnc.FncNv(rowdata["BIKOU"]);
            if (me.clsComFnc.FncSprCheck(strBiko, 0, 13, 60) == -3) {
                me.clsComFnc.FncMsgBox("W0003", "備考");
                return false;
            }
        }
        //一覧(長期欠勤)が表示されている
        var records_fk = $(me.grid_fk_id).jqGrid("getGridParam", "records");
        if (records_fk) {
            var ids = $(me.grid_fk_id).jqGrid("getDataIDs");
            var strSyukkin = "";
            for (var i = 0; i < ids.length; i++) {
                rowdata = $(me.grid_fk_id).jqGrid("getRowData", ids[i]);
                strSyukkin = me.clsComFnc.FncNv(rowdata["SYUKKIN_RITU"]);
                if (me.clsComFnc.FncSprCheck(strSyukkin, 0, 0, 6) == -3) {
                    me.clsComFnc.FncMsgBox("W0003", "出勤率");
                    return false;
                }
            }
        }
        return true;
    };
    //削除ボタンクリック
    me.btnDelete_Click = function () {
        if ($.trim($(".FrmFurikaeDenpyoEnt.lblState").html()) == "修正") {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.Delete;
            me.clsComFnc.FncMsgBox("QY004");
        }
    };
    me.Delete = function () {
        var url = me.sys_id + "/" + me.id + "/" + "Delete_Click";
        var data = {
            dtpYM: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM")
                .val()
                .replace(/\//g, ""),
            strHiddUpdDate1: me._strHiddUpdDate1,
            strHiddUpdDate2: me._strHiddUpdDate2,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"]) {
                me.clsComFnc.FncMsgBox("I0004");
                //登録内容を再表示する
                me.btnKensaku_Click();
            } else {
                if (result["error"] == "W0018") {
                    $(me.grid_tf_id).jqGrid("setSelection", 0, true);
                    me.clsComFnc.ObjFocus = $("#0_SYAIN_NO");
                    me.clsComFnc.FncMsgBox("W0018", "更新日");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //検索ボタンクリック
    me.btnKensaku_Click = function (fncComplete) {
        me.flg_reload = true;
        //登録内容を再表示する
        me.Formit(fncComplete);
    };
    //条件変更ボタンクリック
    me.btnChange_Click = function () {
        //ボタン活性・非活性
        //上段が無効な場合
        if ($(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").prop("disabled")) {
            //対象年月
            $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").ympicker("enable");
            $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM").prop("disabled", false);
            //検索ボタン
            $(".FrmFurikaeDenpyoEnt.btnKensaku").button("enable");
            //ステータス
            $(".FrmFurikaeDenpyoEnt.lblState").html("");
            //行追加
            $(".FrmFurikaeDenpyoEnt.btnRowAdd").button("disable");
            //行削除
            $(".FrmFurikaeDenpyoEnt.btnRowDel").button("disable");
            //登録ボタン
            $(".FrmFurikaeDenpyoEnt.btnEnt").button("disable");
            //削除ボタ
            $(".FrmFurikaeDenpyoEnt.btnDelete").button("disable");
            //Excel出力ボタン
            $(".FrmFurikaeDenpyoEnt.btnExcel").button("disable");
            //画面初期化(一覧クリア)
            me.Formit2();
        }
    };
    //画面初期化(一覧クリア)
    me.Formit2 = function () {
        // jqgridデータクリア
        $(me.grid_tf_id).jqGrid("clearGridData");
        $(me.grid_fk_id).jqGrid("clearGridData");
    };
    //行追加ボタンクリック
    me.btnRowAdd_Click = function () {
        //jqgridロードされた
        if ($("#gview_JKSYS_FrmFurikaeDenpyoEnt_sprList1").length > 0) {
            //获得所有行的ID数组
            var ids = $(me.grid_tf_id).jqGrid("getDataIDs");
            var rowid = 0;
            if (ids.length > 0) {
                //获得当前最大行号（数据编号）
                rowid = parseInt(ids.pop()) + 1;
            }
            var strbtnSyainSearch =
                "<button onclick=\"rowSyainSearch_Click('" +
                rowid +
                "')\" id = '" +
                rowid +
                "_btnSyainSearch' class=\"FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>検索</button>";
            var strbtnBusyoSearch =
                "<button onclick=\"rowBusyoSearch_Click('" +
                rowid +
                "')\" id = '" +
                rowid +
                "_btnBusyoSearch' class=\"FrmFurikaeDenpyoEnt rowSyainSearch Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>検索</button>";
            var data = {
                btnSyainSearch: strbtnSyainSearch,
                btnBusyoSearch: strbtnBusyoSearch,
            };
            //插入一行
            $(me.grid_tf_id).jqGrid("addRowData", rowid, data);
            $(me.grid_tf_id).jqGrid("saveRow", me.lastsel);
            $(me.grid_tf_id).jqGrid("setSelection", rowid, true);
        }
    };
    //セルボタンクリック
    rowSyainSearch_Click = function (rowId) {
        var $rootDiv = $(".FrmFurikaeDenpyoEnt.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmSyainSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYONM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNO").insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNM").insertAfter($rootDiv);
        $("<div></div>").attr("id", "KUJYUNBI").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $BUSYONM = $rootDiv.parent().find("#BUSYONM");
        var $SYAINNO = $rootDiv.parent().find("#SYAINNO");
        var $SYAINNM = $rootDiv.parent().find("#SYAINNM");
        var $KUJYUNBI = $rootDiv.parent().find("#KUJYUNBI");

        var dtpYM = $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM")
            .val()
            .replace(/\//g, "");
        var year = dtpYM.substring(0, 4);
        var month = dtpYM.substring(4, 6);
        //构造一个日期对象：
        var day = new Date(year, month, 0);
        //获取当月天数：
        var daycount = day.getDate();
        $KUJYUNBI.val(dtpYM + daycount);

        $("#FrmSyainSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 550 : 650,
            width: me.ratio === 1.5 ? 752 : 790,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $BUSYONM.hide();
                $SYAINNO.hide();
                $SYAINNM.hide();
                $KUJYUNBI.hide();
            },
            close: function () {
                if ($RtnCD.html() == 1) {
                    me.SYAINNO = $SYAINNO.html();
                    me.SYAINNM = $SYAINNM.html();
                    me.BUSYOCD = $BUSYOCD.html();
                    me.BUSYONM = $BUSYONM.html();

                    $("#" + rowId + "_SYAIN_NO").val(me.SYAINNO);
                    $(me.grid_tf_id).jqGrid(
                        "setCell",
                        rowId,
                        "SYAIN_NM",
                        me.SYAINNM
                    );
                    $(me.grid_tf_id).jqGrid(
                        "setCell",
                        rowId,
                        "FRI_MOTO_BUSYO_CD",
                        me.BUSYOCD
                    );
                    $(me.grid_tf_id).jqGrid(
                        "setCell",
                        rowId,
                        "BUSYO_NM1",
                        me.BUSYONM
                    );
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $BUSYONM.remove();
                $SYAINNO.remove();
                $SYAINNM.remove();
                $KUJYUNBI.remove();
                $("#FrmSyainSearchDialogDiv").remove();
                $("#" + rowId + "_SYAIN_NO").select();
            },
        });

        var url = me.sys_id + "/" + "FrmJKSYSSyainSearch";
        me.ajax.receive = function (result) {
            $("#FrmSyainSearchDialogDiv").html(result);
            $("#FrmSyainSearchDialogDiv").dialog(
                "option",
                "title",
                "社員番号検索"
            );
            $("#FrmSyainSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, "", 0);
    };
    //セルボタンクリック
    rowBusyoSearch_Click = function (rowId) {
        var $rootDiv = $(".FrmFurikaeDenpyoEnt.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "BUSYONM").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $BUSYOCD = $rootDiv.parent().find("#BUSYOCD");
        var $BUSYONM = $rootDiv.parent().find("#BUSYONM");

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 550 : 650,
            width: me.ratio === 1.5 ? 541 : 580,
            resizable: false,
            open: function () {
                $RtnCD.hide();
                $BUSYOCD.hide();
                $BUSYONM.hide();
            },
            close: function () {
                if ($RtnCD.html() == 1) {
                    me.RtnCD = $RtnCD.html();
                    me.searchedBusyoCD = $BUSYOCD.html();
                    me.searchedBusyoNM = $BUSYONM.html();

                    $(me.grid_tf_id).jqGrid(
                        "setCell",
                        rowId,
                        "BUSYO_NM2",
                        me.searchedBusyoNM
                    );
                    $("#" + rowId + "_FRI_SAKI_BUSYO_CD").val(
                        me.searchedBusyoCD
                    );
                }

                $RtnCD.remove();
                $BUSYOCD.remove();
                $BUSYONM.remove();
                $("#FrmBusyoSearchDialogDiv").remove();
                $("#" + rowId + "_FRI_SAKI_BUSYO_CD").select();
            },
        });

        var frmId = "FrmJKSYSBusyoSearch";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);
            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    };
    //行削除ボタンクリック
    me.btnRowDel_Click = function () {
        var allIds = $(me.grid_tf_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_tf_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(me.grid_tf_id).jqGrid("delRowData", rowid);

                    $(me.grid_tf_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(me.grid_tf_id).jqGrid("delRowData", rowid);

                    $(me.grid_tf_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };
    //Excelボタンクリック
    me.btnExcel_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "Excel_Click";
        var data = {
            dtpTaisyouYM: $(".FrmFurikaeDenpyoEnt.dtpTaisyouYM")
                .val()
                .replace(/\//g, ""),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0011");
                return;
            } else {
                if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                    return;
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    //番号から名称取得
    /*
	 '**********************************************************************
	 '処 理 名：番号から名称取得
	 '関 数 名：me.getSyainName
	 '引    数：e(当前选中的单元格对象)
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getSyainName = function (e) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv($.trim($(e).val()));
        if (me.name_syain) {
            var foundNM_array = me.name_syain.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        var foundNM2 = undefined;
        var sel_parent = $(e).parent();
        sel_parent
            .nextAll('td[aria-describedby$="SYAIN_NM"]')
            .text(foundNM ? foundNM["SYAIN_NM"] : "");
        if (me.name_busyoMoto) {
            var foundNM_array = me.name_busyoMoto.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM2 = foundNM_array[0];
            }
        }
        sel_parent
            .nextAll('td[aria-describedby$="FRI_MOTO_BUSYO_CD"]')
            .text(foundNM2 ? foundNM2["BUSYO_CD"] : "");
        sel_parent
            .nextAll('td[aria-describedby$="BUSYO_NM1"]')
            .text(foundNM2 ? foundNM2["BUSYO_NM"] : "");
    };
    //部署から名称取得
    /*
	 '**********************************************************************
	 '処 理 名：部署から名称取得
	 '関 数 名：me.getBusyoName
	 '引    数：e(当前选中的单元格对象)
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.getBusyoName = function (e) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv($.trim($(e).val()));
        if (me.name_busyoSaki) {
            var foundNM_array = me.name_busyoSaki.filter(function (element) {
                return element["BUSYO_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(e)
            .parent()
            .nextAll('td[aria-describedby$="BUSYO_NM2"]')
            .text(foundNM ? foundNM["BUSYO_NM"] : "");
    };
    return me;
};
$(function () {
    o_FrmFurikaeDenpyoEnt_FrmFurikaeDenpyoEnt = new JKSYS.FrmFurikaeDenpyoEnt();
    o_FrmFurikaeDenpyoEnt_FrmFurikaeDenpyoEnt.load();
});
