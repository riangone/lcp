/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * *履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                                  担当
 * YYYYMMDD               #ID                     XXXXXX                                    GSDL
 * 20201117               bug                     DIVのHeightが間違っています。              LQS
 * 20201119            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。   LQS
 * 20201120                bug                    データが多い時、jqgridに横向スクロールがあります。   LQS
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmFDHokanSelect");

/*
 '************************************************************
 '
 'システム名　　：
 '客先名　　　　：（GD）（DZM）殿向け
 'プロセス名　　：データ入力
 'プログラム名　：データ抽出(登録予定)
 '
 '************************************************************
 */
R4.FrmFDHokanSelect = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "FrmFDHokanSelect";
    me.sys_id = "R4G";
    me.class_id = ".FrmFDHokanSelect.body";
    me.parent_class_id = ".R4.R4-layout-center";

    me.grid_id = "#" + me.id + "_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fnc" + me.id;
    me.pager = "#div" + me.id + "_pager";
    me.FrmFDHokanInput = null;
    me.FrmFDHokanSelect = null;
    me.objArr = null;
    me.sidx = "INP_FLG";
    me.option = {
        rowNum: 500000,
        pagerpos: "left",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 25,
        shrinkToFit: me.ratio === 1.5,
    };

    me.colModel = [
        {
            name: "FD_CRE",
            label: "F D<br>作成",
            index: "FD_CRE",
            width: "35",
            formatter: "checkbox",
            sortable: false,
            align: "center",
        },
        {
            name: "INP_FLG",
            label: "補完<br>入力",
            index: "INP_FLG",
            width: "35",
            formatter: "checkbox",
            sortable: false,
            align: "center",
        },
        {
            name: "KATASIKI",
            label: "型式類別",
            index: "KATASIKI",
            width: "90",
            sortable: false,
        },
        {
            name: "CARNO",
            label: "車台番号",
            index: "CARNO",
            width: "120",
            sortable: false,
        },
        {
            name: "SHI_USER_NM",
            label: "氏名",
            index: "SHI_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            name: "SHI_ADDRESS",
            label: "住所",
            index: "SHI_ADDRESS",
            width: "185",
            sortable: false,
        },
        {
            name: "SYO_USER_NM",
            label: "氏名",
            index: "SYO_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            name: "SYO_ADDRESS",
            label: "住所",
            index: "SYO_ADDRESS",
            width: "185",
            sortable: false,
        },
        {
            name: "CHUMN_NO",
            label: "注文書番号",
            index: "CHUMN_NO",
            width: "100",
            sortable: false,
        },
        {
            name: " TOU_Y_DT",
            label: "登録予定日",
            index: " TOU_Y_DT",
            hidden: true,
            width: "100",
            sortable: false,
        },
    ];

    me.objfrm = new Array();
    me.nowDate1 = new Date();
    me.nowDate2 =
        me.nowDate1.getFullYear +
        "/" +
        me.nowDate1.getMonth +
        "/" +
        me.nowDate1.getDate;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".frmFDHokanSelect.button_action",
        type: "button",
        enable: "false",
        handle: "",
    });
    me.controls.push({
        id: ".frmFDHokanSelect.searchButton",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".frmFDHokanSelect.TourokuFrom_input",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".frmFDHokanSelect.TourokuTo_input",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".frmFDHokanSelect.button_action").click(function () {
        me.frmInputShow();
    });

    $(".frmFDHokanSelect.searchButton").click(function () {
        //登録予定日のﾁｪｯｸ
        if (
            $(".frmFDHokanSelect.TourokuFrom_input").val() >
            $(".frmFDHokanSelect.TourokuTo_input").val()
        ) {
            clsComFnc.ObjFocus = $(".frmFDHokanSelect.TourokuFrom_input");
            clsComFnc.FncMsgBox("W0017", "登録予定日の範囲");
            return;
        }
        $(".frmFDHokanSelect.button_action").button("disable");
        me.subSpreadRe_reload();
    });

    $(".frmFDHokanSelect.TourokuFrom_input").on("blur", function () {
        if (
            clsComFnc.CheckDate($(".frmFDHokanSelect.TourokuFrom_input")) ==
            false
        ) {
            var myDate = new Date();

            $(".frmFDHokanSelect.TourokuFrom_input").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            $(".frmFDHokanSelect.TourokuTo_input").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201119 lqs upd S
            // $(".frmFDHokanSelect.TourokuFrom_input").focus();
            // $(".frmFDHokanSelect.TourokuFrom_input").select();
            window.setTimeout(function () {
                $(".frmFDHokanSelect.TourokuFrom_input").trigger("focus");
                $(".frmFDHokanSelect.TourokuFrom_input").select();
            }, 0);
            // 20201119 lqs upd E
            $(".frmFDHokanSelect.searchButton").button("disable");
            return false;
        } else {
            $(".frmFDHokanSelect.searchButton").button("enable");
        }
    });

    $(".frmFDHokanSelect.TourokuTo_input").on("blur", function () {
        if (
            clsComFnc.CheckDate($(".frmFDHokanSelect.TourokuTo_input")) == false
        ) {
            var myDate = new Date();

            $(".frmFDHokanSelect.TourokuTo_input").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201119 lqs upd S
            // $(".frmFDHokanSelect.TourokuTo_input").focus();
            // $(".frmFDHokanSelect.TourokuTo_input").select();
            window.setTimeout(function () {
                $(".frmFDHokanSelect.TourokuTo_input").trigger("focus");
                $(".frmFDHokanSelect.TourokuTo_input").select();
            }, 0);
            // 20201119 lqs upd E
            $(".frmFDHokanSelect.searchButton").button("disable");
            return false;
        } else {
            $(".frmFDHokanSelect.searchButton").button("enable");
        }
    });

    //******************************
    //処 理 名：ファンクションキー 押下時
    //関 数 名：FrmFDHokanInput_KeyUp
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ファンクションキー 押下時
    //******************************
    $("#FrmFDHokanSelect_subDialog").keyup(function (event) {
        if (event.keyCode == 120) {
            var isOpen = $("#FrmFDHokanSelect_subDialog").dialog("isOpen");
            if (isOpen) {
                me.FrmFDHokanInput.fnccmdActionClick();
            }
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;
    me.load = function () {
        base_load();
        me.frmFDHokanSelect_Load();
    };
    me.frmFDHokanSelect_Load = function () {
        //画面項目ｸﾘｱ
        me.subFormClear();
        me.subSpreadRe_show();
        me.frmFDHokanSelect_keyDown13();
    };

    /*
	 ********************************
	 '処 理 名：画面項目初期化
	 '関 数 名：subFormClear
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：画面項目を初期化する
	 ********************************
	 */
    $(".frmFDHokanSelect.TourokuFrom_input").change(function () {
        if (clsComFnc.CheckDate($(".frmFDHokanSelect.TourokuFrom_input"))) {
            $(".frmFDHokanSelect.TourokuTo_input").val(
                $(".frmFDHokanSelect.TourokuFrom_input").val()
            );
        }
    });
    me.subFormClear = function () {
        var currentDay = new Date();
        var date1 =
            currentDay.getFullYear +
            currentDay.getMonth +
            (currentDay.getDate + 1);
        $(".frmFDHokanSelect.TourokuFrom_input").datepicker("setDate", date1);
        $(".frmFDHokanSelect.TourokuTo_input").datepicker("setDate", date1);
        $(".frmFDHokanSelect.Misakusei_inputCheck").prop("checked", false);
    };

    /*
	 ********************************
	 '処 理 名：データグリッドの再表示
	 '関 数 名：subSpreadRe_show
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：データグリッドを再表示する
	 '*******************************
	 */
    me.subSpreadRe_show = function () {
        tmpdata = {
            Misakusei: $(".frmFDHokanSelect.Misakusei_inputCheck").prop(
                "checked"
            ),
            KAISHI: $(".frmFDHokanSelect.TourokuFrom_input").val(),
            SYURYO: $(".frmFDHokanSelect.TourokuTo_input").val(),
        };

        me.complete_fun = function () {
            if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
                $(".frmFDHokanSelect.button_action").button("enable");
                me.compare_replace();
            } else {
                clsComFnc.ObjFoucs = $(".frmFDHokanSelect.TourokuFrom_input");
                $(".frmFDHokanSelect.button_action").button("disable");
                $(".frmFDHokanSelect.TourokuFrom_input").trigger("focus");
            }

            $(me.grid_id).jqGrid("setSelection", "0");
            me.doubleClickRow_fun();
        };

        gdmz.common.jqgrid.show(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );

        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SHI_USER_NM",
                    numberOfColumns: 2,
                    titleText: "使用者",
                },
                {
                    startColumnName: "SYO_USER_NM",
                    numberOfColumns: 2,
                    titleText: "所有者",
                },
            ],
        });
        //20201120 lqs upd S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 1050);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1030 : 1060
        );
        //20201120 lqs upd E
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 220 : 270
        );
        $("#jqgh_FrmFDHokanSelect_sprList_FD_CRE").css("top", "4px");
        $("#jqgh_FrmFDHokanSelect_sprList_INP_FLG").css("top", "4px");
        $("#FrmFDHokanSelect_subDialog").dialog({
            autoOpen: false,
            modal: true,
            // 20201117 lqs upd S
            // height : 655,
            height: me.ratio === 1.5 ? 558 : 715,
            // 20201117 lqs upd E
            width: 910,
            title: "補完入力　軽第1号様式",
            resizable: false,
        });
    };

    /*
	 ******************************
	 '処 理 名：データグリッドの再表示
	 '関 数 名：subSpreadRe_reload
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：データグリッドを再表示する
	 '******************************
	 */
    me.subSpreadRe_reload = function () {
        tmpdata = {
            Misakusei: $(".frmFDHokanSelect.Misakusei_inputCheck").prop(
                "checked"
            ),
            KAISHI: $(".frmFDHokanSelect.TourokuFrom_input").val(),
            SYURYO: $(".frmFDHokanSelect.TourokuTo_input").val(),
        };
        me.complete_fun_1 = function () {
            if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
                $(".frmFDHokanSelect.button_action").button("enable");
                me.compare_replace();
            } else {
                clsComFnc.ObjFocus = $(".frmFDHokanSelect.TourokuFrom_input");
                clsComFnc.FncMsgBox("I0001");
                $(".frmFDHokanSelect.button_action").button("disable");
            }
            $(me.grid_id).jqGrid("setSelection", "0");
            me.doubleClickRow_fun();
        };

        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun_1);
    };

    /*
	 ******************************
	 '処 理 名： 置き換え "株式会社"，"有限会社"
	 '関 数 名：compare_replace
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：置き換え "株式会社"，"有限会社"
	 'vb line： 1117
	 '******************************
	 */
    me.compare_replace = function () {
        var data = $(me.grid_id).jqGrid("getDataIDs");
        for (key in data) {
            var objDr = $(me.grid_id).jqGrid("getRowData", data[key]);
            var strShiyouNm;

            strShiyouNm = clsComFnc.FncNv(objDr["SHI_USER_NM"]);
            strShiyouNm = String(strShiyouNm).replace("㈱", "株式会社");
            strShiyouNm = String(strShiyouNm).replace("㈲", "有限会社");

            if (strShiyouNm.indexOf("株式会社") == 0) {
                if (strShiyouNm.indexOf("株式会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("株式会社") > 0) {
                if (strShiyouNm.indexOf("　株式会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("株式会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("株式会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("株式会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("株式会社"));
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") == 0) {
                if (strShiyouNm.indexOf("有限会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") > 0) {
                if (strShiyouNm.indexOf("　有限会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("有限会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("有限会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("有限会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("有限会社"));
                    //20160601 Upd End
                }
            }
            strSyoyouNm = clsComFnc.FncNv(objDr["SYO_USER_NM"]);
            strSyoyouNm = String(strShiyouNm).replace("㈱", "株式会社");
            strSyoyouNm = String(strShiyouNm).replace("㈲", "有限会社");

            if (strShiyouNm.indexOf("株式会社") == 0) {
                if (strShiyouNm.indexOf("株式会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("株式会社") > 0) {
                if (strShiyouNm.indexOf("　株式会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("株式会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("株式会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("株式会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("株式会社"));
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") == 0) {
                if (strShiyouNm.indexOf("有限会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") > 0) {
                if (strShiyouNm.indexOf("　有限会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("有限会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("有限会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("有限会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("有限会社"));
                    //20160601 Upd End
                }
            }
            //vb line 1166
            if (
                clsComFnc.FncNv(objDr["SHI_USER_NM"]) ==
                clsComFnc.FncNv(objDr["SYO_USER_NM"])
            ) {
                $(me.grid_id).setCell(key, "SYO_USER_NM", null);
            }

            if (
                clsComFnc.FncNv(objDr["SHI_ADDRESS"]) ==
                clsComFnc.FncNv(objDr["SYO_ADDRESS"])
            ) {
                $(me.grid_id).setCell(key, "SYO_ADDRESS", null);
            }
        }
    };

    /*
	 *******************************
	 '処 理 名： スプレッド上でエンター押下時に修正処理
	 '関 数 名：frmFDHokanSelect_keyDown13
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：スプレッド上でエンター押下時に修正処理
	 '******************************
	 */
    me.frmFDHokanSelect_keyDown13 = function () {
        var $inp = $("#gview_FrmFDHokanSelect_sprList");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13 && e.shiftKey == false) {
                var selectedIds = 0;
                selectedIds = $(me.grid_id).jqGrid("getGridParam", "selrow");
                if (selectedIds == null) {
                    selectedIds = -1;
                }

                if (selectedIds == -1) {
                    return;
                } else {
                    if (selectedIds.length <= 0) {
                        return;
                    } else {
                        me.openFDHokanInputDialog(selectedIds);
                    }
                }
            }
        });
    };

    /*
	 **********************************************************************
	 '処 理 名：選択行の修正画面を呼び出す
	 '関 数 名：doubleClickRow_fun
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：修正ボタン押下のイベントを呼び出す
	 'vb line： 1021
	 **********************************************************************
	 */
    me.doubleClickRow_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function (rowId) {
                me.openFDHokanInputDialog(rowId);
            },
        });
    };

    /*
	 ******************************
	 '処 理 名： 窓を開けて子
	 '関 数 名：openFDHokanInputDialog
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：窓を開けて子
	 '******************************
	 */
    me.openFDHokanInputDialog = function (rowId) {
        $("#FrmFDHokanSelect_subDialog").html("");
        var tmpRowData = $(me.grid_id).jqGrid("getRowData", rowId);
        var objfrm_PrpMenteFlg = "UPD";
        var objfrm_PrpChumn_NO = tmpRowData["CHUMN_NO"];
        var objfrmDataArr = new Array();
        objfrmDataArr["objfrm_PrpMenteFlg"] = objfrm_PrpMenteFlg;
        objfrmDataArr["objfrm_PrpChumn_NO"] = objfrm_PrpChumn_NO;
        me.objArr = objfrmDataArr;
        var FrmFDHokanInput_controller_id =
            me.sys_id + "/FrmFDHokanInput/index";
        var data = "";
        var ajax1 = new gdmz.common.ajax();
        ajax1.receive = function (response) {
            $("#FrmFDHokanSelect_subDialog").html(response);
            $("#FrmFDHokanSelect_subDialog").dialog("open");
        };
        ajax1.send(FrmFDHokanInput_controller_id, data, 0);
    };

    /*
	 ******************************
	 '処 理 名： 子の方法を遂行して
	 '関 数 名：subExeFnc
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：子の方法を遂行して
	 '******************************
	 */
    me.subExeFnc = function (objfrm_flag) {
        if (objfrm_flag == true) {
            me.subSpreadRe_reload();
            $("#FrmFDHokanSelect_subDialog").dialog("close");
        } else {
            $("#FrmFDHokanSelect_subDialog").dialog("close");
            return;
        }
    };

    /*
	 **********************************************************************
	 '処 理 名：編集画面の表示
	 '関 数 名：frmInputShow
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：編集画面の初期値設定、表示後
	 'データグリッドの再表示
	 'vb line： 972
	 **********************************************************************
	 */
    me.frmInputShow = function () {
        $("#FrmFDHokanSelect_subDialog").html("");
        //修正の場合、画面表示初期値を設定する
        //'処理対象行未存在の場合
        var selectedIds = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (selectedIds.length <= 0) {
            clsComFnc.FncMsgBox("10010");
            return;
        } else {
            me.openFDHokanInputDialog(selectedIds);
        }
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFDHokanSelect = new R4.FrmFDHokanSelect();
    o_R4_FrmFDHokanSelect.load();

    o_R4_R4.FrmFDHokanSelect = o_R4_FrmFDHokanSelect;
});
