/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20240710               BUG         内容が完全に表示されるようにサイズ変更            YIN
 * 20240711     20240711_HMTVE.xlsx   Chrome、Edgeが微調整が必要な箇所がありました   YIN
 * 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
 * 20250825            BUG          登録ボタンをクリックすると エラーが発生          YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE030InputDataK");

HMTVE.HMTVE030InputDataK = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE030InputDataK";
    me.hmtve = new HMTVE.HMTVE();
    me.hidTermStart = "";
    me.hidTermEnd = "";
    // jqgrid
    me.grid_id = "#HMTVE030InputDataK_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/getCarItem";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        footerrow: true,
        caption: "",
        multiselectWidth: 60,
    };
    me.upsel = "";
    me.nextsel = "";
    me.lastsel = 0;
    me.hidsum = "";
    me.colModel = [
        {
            label: "",
            name: "SYASYU_CD",
            index: "SYASYU_CD",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            label: "",
            name: "CREATE_DATE",
            index: "CREATE_DATE",
            width: 20,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "SYASYU_NM",
            label: "車種内訳",
            index: "SYASYU_NM",
            // 20240711 YIN UPD S
            // width: 190,
            width: 160,
            // 20240711 YIN UPD E
            align: "left",
            sortable: false,
        },
        {
            name: "SEIYAKU_DAISU_P",
            label: "計画",
            index: "SEIYAKU_DAISU_P",
            width: me.ratio === 1.5 ? 40 : 65,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "3",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, "SEIYAKU_DAISU_P");
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
                                me.totalCal(e, "SEIYAKU_DAISU_P");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SEIYAKU_DAISU_D",
            label: "実績",
            index: "SEIYAKU_DAISU_D",
            width: me.ratio === 1.5 ? 40 : 65,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "3",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, "SEIYAKU_DAISU_D");
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
                                me.totalCal(e, "SEIYAKU_DAISU_D");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "SIJYO_DAISU",
            label: "試乗",
            index: "SIJYO_DAISU",
            width: me.ratio === 1.5 ? 40 : 50,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "3",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, "SIJYO_DAISU");
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
                                me.totalCal(e, "SIJYO_DAISU");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "RAIJYO_DAISU",
            label: "来場<br/>目的",
            index: "RAIJYO_DAISU",
            width: me.ratio === 1.5 ? 40 : 50,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "3",
                dataInit: function (element) {
                    $(element).inputFilter(function (value) {
                        return /^\d*$/.test(value);
                    });
                },
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function (e) {
                            me.totalCal(e, "RAIJYO_DAISU");
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //コードで名前を見つける
                            if (
                                key == 9 ||
                                key == 13 ||
                                key == 38 ||
                                key == 40 ||
                                (key == 9 && e.shiftKey == true)
                            ) {
                                me.totalCal(e, "RAIJYO_DAISU");
                            }
                        },
                    },
                ],
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE030InputDataK.button",
        type: "button",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //表示ボタンクリック
    $(".HMTVE030InputDataK.btnView").click(function () {
        me.btnView_Click();
    });
    //展示会検索ボタンクリック
    $(".HMTVE030InputDataK.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    $(".HMTVE030InputDataK.DropDownList").change(function () {
        me.getFocusItem();
    });
    //展示会開催日
    $(".HMTVE030InputDataK.ddlExhibitDay").change(function () {
        me.ddlExhibitDay_SelectedIndexChanged();
    });
    $(".HMTVE030InputDataK.DropDownList").numeric({
        decimal: false,
    });
    //確定ボタンクリック
    $(".HMTVE030InputDataK.btnDecide").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDecide_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE030InputDataK.ddlExhibitDay").val() +
                "の確報データを更新します。よろしいですか"
        );
    });
    //削除ボタンクリック
    $(".HMTVE030InputDataK.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "展示会:" +
                $(".HMTVE030InputDataK.ddlExhibitDay").val() +
                "の確報データを削除します。よろしいですか？"
        );
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：init_control
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        //プロシージャ:画面初期化
        me.Page_Load();
    };
    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：Page_Load
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        // 20240711 YIN UPD S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 470);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 367 : 435
        );
        // 20240711 YIN UPD E
        // 20240710 YIN UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 395);
        //20240806 caina upd s
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 370);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 241 : 352
        );
        //20240806 caina upd e
        // 20240710 YIN UPD E
        $(me.grid_id).jqGrid("bindKeys");
        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SEIYAKU_DAISU_P",
                    numberOfColumns: 2,
                    titleText: "成約",
                },
            ],
        });
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") <= -1) {
            $(".HMTVE030InputDataK.lbl-sky-m-shi").css("width", "40px");
            $(".HMTVE030InputDataK.lbl-sky-m-ji").css("width", "40px");
        }
        $(".HMTVE030InputDataK.tblLeft").hide();
        $(".HMTVE030InputDataK.tblCenter").hide();
        $(".HMTVE030InputDataK.tblRight").hide();
        //ボタンの設定
        $(".HMTVE030InputDataK.btnDecide").button("disable");
        $(".HMTVE030InputDataK.btnDelete").button("disable");
        var url = me.sys_id + "/" + me.id + "/" + "setExhibitTermDate";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", "データ読込に失敗しました。");
                return;
            }
            //デフォルト日付をセットする
            if (result["data"].length != 0) {
                if (
                    result["data"][0]["END_DATE"] != "" &&
                    result["data"][0]["END_DATE"] != null
                ) {
                    var end_date = result["data"][0]["END_DATE"];
                    if (
                        result["data"][0]["START_DATE"] != "" &&
                        result["data"][0]["START_DATE"] != null
                    ) {
                        var start_date = result["data"][0]["START_DATE"];
                        $(".HMTVE030InputDataK.lblExhibitTermFrom").val(
                            start_date.substring(0, 4) +
                                "/" +
                                start_date.substring(4, 6) +
                                "/" +
                                start_date.substring(6, 8)
                        );
                        me.hidTermStart =
                            start_date.substring(0, 4) +
                            "/" +
                            start_date.substring(4, 6) +
                            "/" +
                            start_date.substring(6, 8);
                    }
                    $(".HMTVE030InputDataK.lblExhibitTermTo").val(
                        end_date.substring(0, 4) +
                            "/" +
                            end_date.substring(4, 6) +
                            "/" +
                            end_date.substring(6, 8)
                    );
                    me.hidTermEnd =
                        end_date.substring(0, 4) +
                        "/" +
                        end_date.substring(4, 6) +
                        "/" +
                        end_date.substring(6, 8);
                    if (
                        me.clsComFnc.CheckDate(
                            $(".HMTVE030InputDataK.lblExhibitTermFrom")
                        ) == false
                    ) {
                        $(".HMTVE030InputDataK.lblExhibitTermFrom").val("");
                    }
                    if (
                        me.clsComFnc.CheckDate(
                            $(".HMTVE030InputDataK.lblExhibitTermTo")
                        ) == false
                    ) {
                        $(".HMTVE030InputDataK.lblExhibitTermTo").val("");
                    }
                    if (
                        $(".HMTVE030InputDataK.lblExhibitTermFrom").val() !== ""
                    ) {
                        me.setExhibitTermDate(
                            $(".HMTVE030InputDataK.lblExhibitTermFrom").val(),
                            $(".HMTVE030InputDataK.lblExhibitTermTo").val()
                        );
                    }
                }
                //TO日付が存在しない場合
                else {
                    $(".HMTVE030InputDataK.lblExhibitTermFrom").val("");
                    $(".HMTVE030InputDataK.lblExhibitTermTo").val("");
                    $(".HMTVE030InputDataK.ddlExhibitDay").html("");
                }
            }
            //データが存在しない場合
            else {
                $(".HMTVE030InputDataK.lblExhibitTermFrom").val("");
                $(".HMTVE030InputDataK.lblExhibitTermTo").val("");
                $(".HMTVE030InputDataK.ddlExhibitDay").html("");
            }
            me.getFocusItem();
            //表示設定
            me.getClear();
            //フォーカス移動
            $(".HMTVE030InputDataK.ddlExhibitDay").trigger("focus");
        };
        me.ajax.send(url, "", 0);
    };
    me.getFocusItem = function () {
        var txtFCL1 = $(".HMTVE030InputDataK.txtForecast_L1").val();
        var txtFCL2 = $(".HMTVE030InputDataK.txtForeCast_L2").val();
        var txtFCS1 = Number(txtFCL1) + Number(txtFCL2);
        $(".HMTVE030InputDataK.txtForeCastSum_L1").text(
            Number(txtFCL1) + Number(txtFCL2)
        );
        var txtR1L = $(".HMTVE030InputDataK.txtResults1_L").val();
        var txtRL2 = $(".HMTVE030InputDataK.txtResults_L2").val();
        var txtRSL1 = Number(txtR1L) + Number(txtRL2);
        $(".HMTVE030InputDataK.txtResultsSum_L1").text(
            Number(txtR1L) + Number(txtRL2)
        );
        var txtFCL3 = $(".HMTVE030InputDataK.txtForecast_L3").val();
        var txtFCL4 = $(".HMTVE030InputDataK.txtForecast_L4").val();
        var txtFCS2 = Number(txtFCL3) + Number(txtFCL4);
        $(".HMTVE030InputDataK.txtForeCastSum_L2").text(
            Number(txtFCL3) + Number(txtFCL4)
        );
        var txtRL3 = $(".HMTVE030InputDataK.txtResults_L3").val();
        var txtRL4 = $(".HMTVE030InputDataK.txtResults_L4").val();
        var txtRL5 = $(".HMTVE030InputDataK.txtResults_L5").val();
        var txtRSL2 =
            Number(txtRL3) + Number(txtRL4) + Number(txtRL5) - Number(txtRL5);
        $(".HMTVE030InputDataK.txtResultsSum_L2").text(
            Number(txtRL3) + Number(txtRL4) + Number(txtRL5) - Number(txtRL5)
        );
        var txtFDM = $(".HMTVE030InputDataK.txtForecast_L11").val();
        var txtFMedia = $(".HMTVE030InputDataK.txtForecast_L13").val();
        var txtFRadio = $(".HMTVE030InputDataK.txtForecast_L14").val();
        var txtFKoukoku = $(".HMTVE030InputDataK.txtForecast_L15").val();
        var txtFTorigakari = $(".HMTVE030InputDataK.txtForecast_L16").val();
        var txtFSyokai = $(".HMTVE030InputDataK.txtForecast_L17").val();
        var txtWeb = $(".HMTVE030InputDataK.txtForecast_L18").val();
        $(".HMTVE030InputDataK.txtForecast_L19").text(
            Number(txtFCS1) +
                Number(txtFCS2) -
                (Number(txtFDM) +
                    Number(txtFMedia) +
                    Number(txtFRadio) +
                    Number(txtFKoukoku) +
                    Number(txtFTorigakari) +
                    Number(txtFSyokai) +
                    Number(txtWeb))
        );
        var txtRDM = $(".HMTVE030InputDataK.txtResults_L11").val();
        var txtRMedia = $(".HMTVE030InputDataK.txtResults_L13").val();
        var txtRRadio = $(".HMTVE030InputDataK.txtResults_L14").val();
        var txtRKoukoku = $(".HMTVE030InputDataK.txtResults_L15").val();
        var txtRTorigakari = $(".HMTVE030InputDataK.txtResults_L16").val();
        var txtRSyokai = $(".HMTVE030InputDataK.txtResults_L17").val();
        var txtRWeb = $(".HMTVE030InputDataK.txtResults_L18").val();
        $(".HMTVE030InputDataK.txtResults_L19").text(
            Number(txtRSL1) +
                Number(txtRSL2) -
                (Number(txtRDM) +
                    Number(txtRMedia) +
                    Number(txtRRadio) +
                    Number(txtRKoukoku) +
                    Number(txtRTorigakari) +
                    Number(txtRSyokai) +
                    Number(txtRWeb))
        );
    };
    me.gridComplete = function () {
        var rowDatas = $(me.grid_id).jqGrid("getRowData"),
            total_count = {};
        for (var i = 0; i < rowDatas.length; i++) {
            for (var j = 0; j < me.colModel.length; j++) {
                if (me.colModel[j]["name"] != "SYASYU_NM") {
                    if (i == 0) {
                        total_count[me.colModel[j]["name"]] = 0;
                    }
                    total_count[me.colModel[j]["name"]] +=
                        rowDatas[i][me.colModel[j]["name"]] - 0;
                }
            }
        }
        total_count["SYASYU_NM"] = "合　計";
        me.hidsum = total_count;
        $(me.grid_id).jqGrid("footerData", "set", total_count);
        //$(me.grid_id).jqGrid('setSelection', rowid, true);
        $(".HMTVE030InputDataK .ui-jqgrid-sdiv tr").css(
            "background",
            "#FF73B3"
        );
        $(".HMTVE030InputDataK .ui-jqgrid-sdiv tr")
            .find('[aria-describedby="HMTVE030InputDataK_tblMain_SYASYU_NM"]')
            .css("text-align", "right");
    };

    //合计的计算
    me.totalCal = function (e, str) {
        var row = $(e.target).closest("tr.jqgrow");
        var rowId = row.attr("id");
        var num = 0;
        var allArr = $(me.grid_id).jqGrid("getRowData");
        me.hidsum[str] = 0;
        for (var i = 0; i < allArr.length; i++) {
            num = allArr[i][str];

            num = parseInt(num);

            if (!isNaN(num)) {
                me.hidsum[str] += num;
            }
        }
        num = $.trim($("#" + rowId + "_" + str).val());
        num = parseInt(num);
        if (!isNaN(num)) {
            me.hidsum[str] += num;
        }
        $(me.grid_id).jqGrid("footerData", "set", me.hidsum);
    };
    // '**********************************************************************
    // '処 理 名：登録可能な展示会開催期間のイベント
    // '関 数 名：checkExhibitTermDate
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：登録可能な展示会開催期間を取得する
    // '**********************************************************************
    me.checkExhibitTermDate = function (objdr) {
        if (objdr != "") {
            //抽出データ("KAKUTEI_FLG")＝"1"の場合
            if (objdr) {
                //画面項目の13～75までのTextbox、画面項目76を読取専用(ReadOnly = True)にする
                $(".HMTVE030InputDataK.txtForecast_L1").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults1_L").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForeCast_L2").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L2").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L3").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L3").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L4").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L4").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L5").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L11").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L11").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L12").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L13").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L13").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L14").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L14").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L15").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L15").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L16").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L16").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L17").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L17").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L18").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L18").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L19").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L19").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L6").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L6").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L7").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L7").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L8").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L8").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L9").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L9").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_L10").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_L10").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C1").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C1").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C2").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C2").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C3").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C3").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C4").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C4").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C5").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C5").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C6").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C6").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C5T").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C5T").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C6T").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C6T").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C7").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C7").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C13").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C13").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C14").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C14").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C8").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C8").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C9").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C9").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C10").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C10").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForecast_C11").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C11").attr("readonly", true);
                $(".HMTVE030InputDataK.txtResults_C12").attr("readonly", true);
                $(".HMTVE030InputDataK.txtForeCastSum_L1").attr(
                    "readonly",
                    true
                );
                $(".HMTVE030InputDataK.txtResultsSum_L1").attr(
                    "readonly",
                    true
                );
                $(".HMTVE030InputDataK.txtForeCastSum_L2").attr(
                    "readonly",
                    true
                );
                $(".HMTVE030InputDataK.txtResultsSum_L2").attr(
                    "readonly",
                    true
                );
                $(me.grid_id).setColProp("SEIYAKU_DAISU_P", {
                    editable: false,
                });
                $(me.grid_id).setColProp("SEIYAKU_DAISU_D", {
                    editable: false,
                });
                $(me.grid_id).setColProp("SIJYO_DAISU", {
                    editable: false,
                });
                $(me.grid_id).setColProp("RAIJYO_DAISU", {
                    editable: false,
                });
                $(".HMTVE030InputDataK.tblLeft").show();
                $(".HMTVE030InputDataK.tblCenter").show();
                $(".HMTVE030InputDataK.tblRight").show();

                $(".HMTVE030InputDataK.btnDecide").button("disable");
                $(".HMTVE030InputDataK.btnDelete").button("disable");
                me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L1");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "既に確報データの出力が行われていますので、変更は出来ません"
                );
                return;
            } else {
                $(".HMTVE030InputDataK.txtForecast_L1").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults1_L").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForeCast_L2").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L2").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L3").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L3").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L4").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L4").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L5").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L11").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L11").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L12").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L13").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L13").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L14").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L14").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L15").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L15").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L16").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L16").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L17").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L17").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L18").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L18").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L19").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L19").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L6").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L6").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L7").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L7").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L8").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L8").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L9").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_L9").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_L10").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_L10").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C1").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C1").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C2").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C2").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C3").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C3").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C4").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C4").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C5").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C5").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C6").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C6").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C5T").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C5T").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C6T").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C6T").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C7").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C7").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C13").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C13").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C14").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C14").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C8").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C8").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C9").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C9").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C10").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C10").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForecast_C11").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResults_C11").attr("readonly", false);
                $(".HMTVE030InputDataK.txtResults_C12").attr("readonly", false);
                $(".HMTVE030InputDataK.txtForeCastSum_L1").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResultsSum_L1").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtForeCastSum_L2").attr(
                    "readonly",
                    false
                );
                $(".HMTVE030InputDataK.txtResultsSum_L2").attr(
                    "readonly",
                    false
                );

                $(me.grid_id).setColProp("SEIYAKU_DAISU_P", {
                    editable: true,
                });
                $(me.grid_id).setColProp("SEIYAKU_DAISU_D", {
                    editable: true,
                });
                $(me.grid_id).setColProp("SIJYO_DAISU", {
                    editable: true,
                });
                $(me.grid_id).setColProp("RAIJYO_DAISU", {
                    editable: true,
                });
                //画面項目No13～No87までを表示する
                $(".HMTVE030InputDataK.tblLeft").show();
                $(".HMTVE030InputDataK.tblCenter").show();
                $(".HMTVE030InputDataK.tblRight").show();
                //確定ボタンを使用可にする
                $(".HMTVE030InputDataK.btnDecide").button("enable");
            }
        } else {
            $(".HMTVE030InputDataK.txtForecast_L1").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults1_L").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForeCast_L2").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L2").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L3").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L3").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L4").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L4").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L5").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L11").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L11").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L12").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L13").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L13").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L14").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L14").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L15").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L15").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L16").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L16").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L17").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L17").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L18").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L18").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L19").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L19").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L6").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L6").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L7").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L7").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L8").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L8").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L9").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L9").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_L10").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_L10").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C1").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C1").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C2").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C2").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C3").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C3").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C4").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C4").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C5").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C5").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C6").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C6").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C5T").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C5T").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C6T").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C6T").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C7").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C7").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C13").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C13").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C14").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C14").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C8").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C8").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C9").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C9").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C10").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C10").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForecast_C11").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C11").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResults_C12").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForeCastSum_L1").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResultsSum_L1").attr("readonly", false);
            $(".HMTVE030InputDataK.txtForeCastSum_L2").attr("readonly", false);
            $(".HMTVE030InputDataK.txtResultsSum_L2").attr("readonly", false);
            $(me.grid_id).setColProp("SEIYAKU_DAISU_P", {
                editable: true,
            });
            $(me.grid_id).setColProp("SEIYAKU_DAISU_D", {
                editable: true,
            });
            $(me.grid_id).setColProp("SIJYO_DAISU", {
                editable: true,
            });
            $(me.grid_id).setColProp("RAIJYO_DAISU", {
                editable: true,
            });
            //確定ボタンを使用可にする
            $(".HMTVE030InputDataK.btnDecide").button("enable");
            //画面項目No13～No87までを表示する
            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
        }
        $(me.grid_id).jqGrid("setSelection", 0);
        setTimeout(function () {
            $(".HMTVE030InputDataK.txtForecast_L1").trigger("focus");
        }, 0);
    };
    // '**********************************************************************
    // '処 理 名：表示ボタンのイベント
    // '関 数 名：btnView_Click
    // '戻 り 値：なし
    // '処理説明：取得データを出勤管理グリッドにバインドする
    // '**********************************************************************
    me.btnView_Click = function () {
        var userAgent = navigator.userAgent;
        var isIE =
            userAgent.indexOf("compatible") > -1 &&
            userAgent.indexOf("MSIE") > -1;
        var isIE11 =
            userAgent.indexOf("Trident") > -1 &&
            userAgent.indexOf("rv:11.0") > -1;
        if (isIE || isIE11) {
            if ($(window).width() < 1536) {
                $(".HMTVE030InputDataK.HMTVE-content").css("width", "1290px");
                $(".HMTVE.HMTVE-layout-center").css("overflow-x", "scroll");
            }
        }
        //入力チェック
        if (!me.inputCheck()) {
            return;
        }
        var data = {
            ddlExhibitDay: $(".HMTVE030InputDataK.ddlExhibitDay").val(),
            lblExhibitTermFrom: $(
                ".HMTVE030InputDataK.lblExhibitTermFrom"
            ).val(),
        };
        var complete_fun = function (_returnFLG, result) {
            if (result["error"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE030InputDataK.ddlExhibitDay"
                    );
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "データ読込に失敗しました。"
                    );
                }
                me.Page_Clear();
                return;
            }
            me.getCarItem();
            if (result["objReader1"] && result["objReader2"]) {
                //4 確報入力データを取得する
                me.getKakuhouItem(result["objReader1"], result["objReader2"]);
            }

            //登録可能な展示会開催期間であるかのチェックを行い、画面制御を行う
            me.checkExhibitTermDate(result["KAKUTEIFLG"]);

            me.getFocusItem();

            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    // '**********************************************************************
    // '処 理 名：確報入力データを取得のイベント
    // '関 数 名：getKakuhouItem
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：確報入力データを取得する
    // '**********************************************************************
    me.getKakuhouItem = function (objReader1, objReader2) {
        if (objReader1.length == 0) {
            me.getClear();
            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
            $(".HMTVE030InputDataK.btnDecide").button("enable");
            $(".HMTVE030InputDataK.btnDelete").button("disable");
        } else if (objReader1.length > 0) {
            $(".HMTVE030InputDataK.txtForecast_L1").val(
                objReader1[0]["RAIJYO_KUMI_AB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForeCast_L2").val(
                objReader1[0]["RAIJYO_KUMI_AB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtForeCastSum_L1").text(
                Number(objReader1[0]["RAIJYO_KUMI_AB_KOKYAKU"]) +
                    Number(objReader1[0]["RAIJYO_KUMI_AB_SINTA"])
            );
            $(".HMTVE030InputDataK.txtForecast_L3").val(
                objReader1[0]["RAIJYO_KUMI_NONAB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_L4").val(
                objReader1[0]["RAIJYO_KUMI_NONAB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtForeCastSum_L2").text(
                Number(objReader1[0]["RAIJYO_KUMI_NONAB_KOKYAKU"]) +
                    Number(objReader1[0]["RAIJYO_KUMI_NONAB_SINTA"])
            );
            $(".HMTVE030InputDataK.txtForecast_L11").val(
                objReader1[0]["RAIJYO_BUNSEKI_YOBIKOMI"]
            );
            $(".HMTVE030InputDataK.txtForecast_L13").val(
                objReader1[0]["RAIJYO_BUNSEKI_KOUKOKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_L14").val(
                objReader1[0]["RAIJYO_BUNSEKI_MEDIA"]
            );
            $(".HMTVE030InputDataK.txtForecast_L15").val(
                objReader1[0]["RAIJYO_BUNSEKI_CHIRASHI"]
            );
            $(".HMTVE030InputDataK.txtForecast_L16").val(
                objReader1[0]["RAIJYO_BUNSEKI_TORIGAKARI"]
            );
            $(".HMTVE030InputDataK.txtForecast_L17").val(
                objReader1[0]["RAIJYO_BUNSEKI_SYOKAI"]
            );
            $(".HMTVE030InputDataK.txtForecast_L18").val(
                objReader1[0]["RAIJYO_BUNSEKI_WEB"]
            );
            $(".HMTVE030InputDataK.txtForecast_L19").text(
                objReader1[0]["RAIJYO_BUNSEKI_SONOTA"]
            );
            $(".HMTVE030InputDataK.txtForecast_L6").val(
                objReader1[0]["JIZEN_JYUNBI_DM"]
            );
            $(".HMTVE030InputDataK.txtForecast_L7").val(
                objReader1[0]["JIZEN_JYUNBI_DH"]
            );
            $(".HMTVE030InputDataK.txtForecast_L8").val(
                objReader1[0]["JIZEN_JYUNBI_POSTING"]
            );
            $(".HMTVE030InputDataK.txtForecast_L9").val(
                objReader1[0]["JIZEN_JYUNBI_TEL"]
            );
            $(".HMTVE030InputDataK.txtForecast_L10").val(
                objReader1[0]["JIZEN_JYUNBI_KAKUYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C1").val(
                objReader1[0]["ENQUETE_KAISYU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C2").val(
                objReader1[0]["ABHOT_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C3").val(
                objReader1[0]["ABHOT_SINTA"]
            );
            $(".HMTVE030InputDataK.txtForecast_C4").val(
                objReader1[0]["ABHOT_ZAN"]
            );
            $(".HMTVE030InputDataK.txtForecast_C5").val(
                objReader1[0]["SATEI_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C6").val(
                objReader1[0]["SATEI_SINTA"]
            );
            $(".HMTVE030InputDataK.txtForecast_C5T").val(
                objReader1[0]["SATEI_KOKYAKU_TA"]
            );
            $(".HMTVE030InputDataK.txtForecast_C6T").val(
                objReader1[0]["SATEI_SINTA_TA"]
            );
            $(".HMTVE030InputDataK.txtForecast_C7").val(
                objReader1[0]["DEMO_KENSU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C13").val(
                objReader1[0]["RUNCOST_KENSU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C14").val(
                objReader1[0]["SKYPLAN_KENSU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C8").val(
                objReader1[0]["SEIYAKU_AB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C9").val(
                objReader1[0]["SEIYAKU_AB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtForecast_C10").val(
                objReader1[0]["SEIYAKU_NONAB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtForecast_C11").val(
                objReader1[0]["SEIYAKU_NONAB_SINTA"]
            );
            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
            $(".HMTVE030InputDataK.btnDelete").button("enable");
        }
        if (objReader2.length == 0) {
            me.getClear();
            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
            $(".HMTVE030InputDataK.btnDecide").button("enable");
            $(".HMTVE030InputDataK.btnDelete").button("disable");
        } else if (objReader2.length > 0) {
            $(".HMTVE030InputDataK.txtResults1_L").val(
                objReader2[0]["RAIJYO_KUMI_AB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_L2").val(
                objReader2[0]["RAIJYO_KUMI_AB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResultsSum_L1").text(
                Number(objReader2[0]["RAIJYO_KUMI_AB_KOKYAKU"]) +
                    Number(objReader2[0]["RAIJYO_KUMI_AB_SINTA"])
            );
            $(".HMTVE030InputDataK.txtResults_L3").val(
                objReader2[0]["RAIJYO_KUMI_NONAB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_L4").val(
                objReader2[0]["RAIJYO_KUMI_NONAB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResults_L5").val(
                objReader2[0]["RAIJYO_KUMI_NONAB_FREE"]
            );
            $(".HMTVE030InputDataK.txtResultsSum_L2").text(
                Number(objReader2[0]["RAIJYO_KUMI_NONAB_KOKYAKU"]) +
                    Number(objReader2[0]["RAIJYO_KUMI_NONAB_SINTA"])
            );
            $(".HMTVE030InputDataK.txtResults_L11").val(
                objReader2[0]["RAIJYO_BUNSEKI_YOBIKOMI"]
            );
            $(".HMTVE030InputDataK.txtResults_L12").val(
                objReader2[0]["RAIJYO_BUNSEKI_KAKUYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_L13").val(
                objReader2[0]["RAIJYO_BUNSEKI_KOUKOKU"]
            );
            $(".HMTVE030InputDataK.txtResults_L14").val(
                objReader2[0]["RAIJYO_BUNSEKI_MEDIA"]
            );
            $(".HMTVE030InputDataK.txtResults_L15").val(
                objReader2[0]["RAIJYO_BUNSEKI_CHIRASHI"]
            );
            $(".HMTVE030InputDataK.txtResults_L16").val(
                objReader2[0]["RAIJYO_BUNSEKI_TORIGAKARI"]
            );
            $(".HMTVE030InputDataK.txtResults_L17").val(
                objReader2[0]["RAIJYO_BUNSEKI_SYOKAI"]
            );
            $(".HMTVE030InputDataK.txtResults_L18").val(
                objReader2[0]["RAIJYO_BUNSEKI_WEB"]
            );
            $(".HMTVE030InputDataK.txtResults_L19").text(
                objReader2[0]["RAIJYO_BUNSEKI_SONOTA"]
            );
            $(".HMTVE030InputDataK.txtResults_L6").val(
                objReader2[0]["JIZEN_JYUNBI_DM"]
            );
            $(".HMTVE030InputDataK.txtResults_L7").val(
                objReader2[0]["JIZEN_JYUNBI_DH"]
            );
            $(".HMTVE030InputDataK.txtResults_L8").val(
                objReader2[0]["JIZEN_JYUNBI_POSTING"]
            );
            $(".HMTVE030InputDataK.txtResults_L9").val(
                objReader2[0]["JIZEN_JYUNBI_TEL"]
            );
            $(".HMTVE030InputDataK.txtResults_L10").val(
                objReader2[0]["JIZEN_JYUNBI_KAKUYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_C1").val(
                objReader2[0]["ENQUETE_KAISYU"]
            );
            $(".HMTVE030InputDataK.txtResults_C2").val(
                objReader2[0]["ABHOT_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_C3").val(
                objReader2[0]["ABHOT_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResults_C4").val(
                objReader2[0]["ABHOT_ZAN"]
            );
            $(".HMTVE030InputDataK.txtResults_C5").val(
                objReader2[0]["SATEI_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_C6").val(
                objReader2[0]["SATEI_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResults_C5T").val(
                objReader2[0]["SATEI_KOKYAKU_TA"]
            );
            $(".HMTVE030InputDataK.txtResults_C6T").val(
                objReader2[0]["SATEI_SINTA_TA"]
            );
            $(".HMTVE030InputDataK.txtResults_C7").val(
                objReader2[0]["DEMO_KENSU"]
            );
            $(".HMTVE030InputDataK.txtResults_C13").val(
                objReader2[0]["RUNCOST_SEIYAKU_KENSU"]
            );
            $(".HMTVE030InputDataK.txtResults_C14").val(
                objReader2[0]["SKYPLAN_KEIYAKU_KENSU"]
            );
            $(".HMTVE030InputDataK.txtResults_C8").val(
                objReader2[0]["SEIYAKU_AB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_C9").val(
                objReader2[0]["SEIYAKU_AB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResults_C10").val(
                objReader2[0]["SEIYAKU_NONAB_KOKYAKU"]
            );
            $(".HMTVE030InputDataK.txtResults_C11").val(
                objReader2[0]["SEIYAKU_NONAB_SINTA"]
            );
            $(".HMTVE030InputDataK.txtResults_C12").val(
                objReader2[0]["SEIYAKU_NONAB_FREE"]
            );
            $(".HMTVE030InputDataK.tblLeft").show();
            $(".HMTVE030InputDataK.tblCenter").show();
            $(".HMTVE030InputDataK.tblRight").show();
            $(".HMTVE030InputDataK.btnDelete").button("enable");
        }
    };
    // '**********************************************************************
    // '処 理 名：車種データを取得のイベント
    // '関 数 名：getCarItem
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：車種データを取得する
    // '**********************************************************************
    me.getCarItem = function () {
        me.gridComplete();
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowId, _status, e) {
                if (typeof e != "undefined") {
                    // 20250825 YIN UPD S
                    // var cellIndex = e.target.cellIndex;
                    var cellIndex =
                        e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex;
                    //ヘッダークリック以外
                    if (cellIndex != 0) {
                        // if (rowId && rowId != me.lastsel1) {
                        if (rowId && rowId != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                // me.lastsel1,
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            // me.lastsel1 = rowId;
                            me.lastsel = rowId;
                        }
                        // $(me.grid_id).jqGrid("editRow", rowId, true);
                        if (cellIndex < 3) {
                            cellIndex = 3;
                        }
                        $(me.grid_id).jqGrid("editRow", rowId, {
                            keys: true,
                            focusField: cellIndex,
                        });
                    }
                } else {
                    if (rowId && rowId != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            // me.lastsel1,
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        // me.lastsel1 = rowId;
                        me.lastsel = rowId;
                        // 20250825 YIN UPD E
                    }
                    $(me.grid_id).jqGrid("editRow", rowId, {
                        keys: true,
                        focusField: false,
                    });
                }
                gdmz.common.jqgrid.setKeybordEvents(me.grid_id, e, rowId);
            },
        });

        me.getSumFrozen();
    };
    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：inputCheck
    //引    数：無し
    //戻 り 値：なし
    //処理説明：入力の内容をチェック
    //**********************************************************************
    me.inputCheck = function () {
        //展示会開催期間(From)が未入力の場合、エラー
        if ($(".HMTVE030InputDataK.lblExhibitTermFrom").val().length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください。"
            );
            return false;
        }
        //展示会開催期間(To)が未入力の場合、エラー
        if ($(".HMTVE030InputDataK.lblExhibitTermTo").val().length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください。"
            );
            return false;
        }
        //展示会開催日が未入力の場合、エラー
        var exhibitDayValue = $(".HMTVE030InputDataK.ddlExhibitDay").val();
        if (!exhibitDayValue) {
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください。");
            return false;
        }
        return true;
    };
    // '**********************************************************************
    // '処 理 名：展覧会検索ボタンのイベント
    // '関 数 名：btnETSearch_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：検索画面の戻り値を画面項目にセットする
    // '**********************************************************************
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE030InputDataKDialogDiv";
        var $rootDiv = $(".HMTVE030InputDataK.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        if ($("#HMTVE030InputDataKDialogDiv").length <= 0) {
            $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
            $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
            $("<div></div>").attr("id", "lblETStart").insertAfter($rootDiv);
            $("<div></div>").attr("id", "lblETEnd").insertAfter($rootDiv);
        }

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMTVE030InputDataK.lblExhibitTermFrom").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE030InputDataK.lblExhibitTermTo").val(
                        $lblETEnd.html()
                    );
                    $(".HMTVE030InputDataK.tblRight").hide();
                    $(".HMTVE030InputDataK.tblCenter").hide();
                    $(".HMTVE030InputDataK.tblLeft").hide();
                    me.setExhibitTermDate(
                        $(".HMTVE030InputDataK.lblExhibitTermFrom").val(),
                        $(".HMTVE030InputDataK.lblExhibitTermTo").val()
                    );
                    $(".HMTVE030InputDataK.btnETSearch").trigger("blur");
                    setTimeout(function () {
                        //需要focus的控件
                        $(".HMTVE030InputDataK.ddlExhibitDay").trigger("focus");
                    }, 100);
                }
                if ($("#HMTVE030InputDataKDialogDiv").length > 0) {
                    $RtnCD.remove();
                    $lblETStart.remove();
                    $lblETEnd.remove();
                    $("#" + dialogdiv).remove();
                }
            }

            $RtnCD.hide();
            $lblETStart.hide();
            $lblETEnd.hide();
            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE030InputDataK.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    me.getSumFrozen = function () {
        $(".frozen-sdiv.ui-jqgrid-sdiv").remove();
        var $sumdiv = $(".ui-jqgrid-sdiv").clone();
        var $sumdiv1 = document
            .getElementsByClassName("ui-jqgrid-sdiv")[0]
            .cloneNode(true);
        $sumdiv.width("");
        $sumdiv.find("table").width("");
        $sumdiv.find("tr").html("");
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        $sumdiv
            .find("tr")
            .append(
                $sumdiv1.firstChild.firstChild.firstChild.firstChild.firstChild
            );
        var hth =
            $(
                ".HMTVE030InputDataK .frozen-div.ui-state-default.ui-jqgrid-hdiv"
            ).height() +
            $(".HMTVE030InputDataK .frozen-bdiv.ui-jqgrid-bdiv").height();
        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;top:' +
                (parseInt(hth, 10) + 17) +
                'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>'
        );
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter($(".frozen-bdiv"));
    };
    // '**********************************************************************
    // '処 理 名：setExhibitTermDateのイベント
    // '関 数 名：setExhibitTermDate
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.setExhibitTermDate = function (From, To) {
        $(".HMTVE030InputDataK.ddlExhibitDay").html("");
        $(".HMTVE030InputDataK.lblExhibitTermFrom").val(From);
        $(".HMTVE030InputDataK.lblExhibitTermTo").val(To);
        var days = me.DateDiff(From, To);
        for (var i = 0; i <= days; i++) {
            var Fromdate = new Date(From);
            Fromdate.setDate(Fromdate.getDate() + i);
            var strdate = Fromdate.Format("yyyy/MM/dd");
            $("<option></option>")
                .val(strdate)
                .text(strdate)
                .appendTo(".HMTVE030InputDataK.ddlExhibitDay");
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：isPosNumber
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.isPosNumber = function (text) {
        try {
            if (text == null) {
                return Number(-1);
            } else if ($.trim(text) == "") {
                return Number(0);
            } else if ($.trim(text).indexOf("-", 0) != -1) {
                return Number(-1);
            } else if ($.trim(text).indexOf(".") != -1) {
                return Number(-1);
            } else {
                if ($.isNumeric($.trim(text))) {
                    return Number($.trim(text));
                } else {
                    return Number(-1);
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    // '*************************
    // '処 理 名：確定ボタンの入力チェック
    // '関 数 名：btnDecideCheck
    // '引    数：無し
    // '戻 り 値：なし
    // '処理説明：登録ボタンの入力チェック
    // '**********************************************************************
    me.btnDecideCheck = function () {
        //画面項目No13～75までのTextbox、画面項目No79～82に対して、正の数値以外が入力されていた場合エラー
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L1").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_顧客_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults1_L").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults1_L");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_顧客_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForeCast_L2").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForeCast_L2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_新他ストック_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L2").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_新他ストック_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForeCastSum_L1").text()) ==
            -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForeCastSum_L1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_合計_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResultsSum_L1").text()) ==
            -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResultsSum_L1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_AB_合計_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L3").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L3");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_顧客_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L3").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L3");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_顧客_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L4").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L4");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_新他ストック_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L4").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L4");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_新他ストック_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L5").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L5");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_内フリー_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForeCastSum_L2").text()) ==
            -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForeCastSum_L2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_合計_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResultsSum_L2").text()) ==
            -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForeCastSum_L2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_合計_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L11").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_呼込活動来店_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L11").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_呼込活動来店_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L12").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L12");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_(内)確約来店実績_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L13").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L13");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_新聞広告_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L13").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L13");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_新聞広告_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L14").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L14");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_ラジオ・テレビ_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L14").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L14");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_ラジオ・テレビ_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L15").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L15");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_折込チラシ_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L15").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L15");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_折込チラシ_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L16").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L16");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_通りがかり_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L16").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L16");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_通りがかり_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L17").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L17");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_紹介_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L17").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L17");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_紹介_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L18").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L18");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_ＷＥＢ_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L18").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L18");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_ＷＥＢ_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L6").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L6");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_DM配信枚数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L6").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L6");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_DM配信枚数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L7").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L7");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_DH配布枚数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L7").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L7");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_DH配布枚数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L8").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_ポスティング配布枚数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L8").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_ポスティング配布枚数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L9").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L9");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_TELコール数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L9").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L9");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_TELコール数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L10").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L10");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_来店確約数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L10").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L10");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_来店確約数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C1").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "アンケート回収_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C1").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "アンケート回収_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C2").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット発生_顧客_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C2").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C2");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット発生_顧客_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C3").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C3");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット発生_新他ストック_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C3").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C3");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット発生_新他ストック_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C4").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C4");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット残_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C4").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C4");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ABホット残_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C5").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C5");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_顧客_自銘柄_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C5").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C5");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_顧客_自銘柄_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C6").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C6");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_新他ストック_自銘柄_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C6").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C6");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_新他ストック_自銘柄_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C5T").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C5T");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_顧客_他銘柄_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C5T").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C5T");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_顧客_他銘柄_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C6T").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C6T");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_新他ストック_他銘柄_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C6T").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C6T");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "査定_新他ストック_他銘柄_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C7").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C7");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "デモ件数_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C7").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C7");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "デモ件数_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C13").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C13");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ランコス提案_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C13").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C13");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ランコス提案_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C14").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C14");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ＳＫＹプラン提案_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C14").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C14");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ＳＫＹプラン提案_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C8").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_AB_顧客_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C8").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_AB_顧客_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C9").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C9");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_AB_新他_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C9").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C9");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_AB_新他_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C10").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C10");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_顧客_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C10").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C10");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_顧客_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C11").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_新他_計画に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C11").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_新他_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C12").val()) == -1
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C12");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_内フリー_実績に不正な値が入力されています."
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L10").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L12").val())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L12");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "事前準備_来店確約数_実績と来場分析(内)確約来店実績_実績の大小関係が不正です"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L4").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L5").val())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L5");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_NONAB_新他ストック_実績と来場組数_NONAB_内フリー_実績の大小関係が不正です"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L11").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L12").val())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "呼込み活動来店_DM/DH/ポスティング/TELコールと呼込み活動来店_(内)確約来店実数の大小関係が不正です"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L19").text()) < 0
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_計画_その他に負数が入力されています。"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L19").text()) < 0
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場分析_実績_その他に負数が入力されています。"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L1").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForeCast_L2").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L3").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L4").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L11").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L13").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L14").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L15").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L16").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L17").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L18").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_L19").text())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_L19");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_計画_合計と来場分析_計画_合計の大小関係が不正です"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults1_L").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L2").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L3").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L4").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_L11").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L13").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L14").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L15").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L16").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L17").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L18").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_L19").text())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_L19");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "来場組数_実績_合計と来場分析_実績_合計の大小関係が不正です"
            );
            return false;
        }
        var hidsum1 = "";
        if (me.hidsum["SEIYAKU_DAISU_P"]) {
            hidsum1 = me.hidsum["SEIYAKU_DAISU_P"].toString();
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C8").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C9").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtForecast_C10").val()) +
                me.isPosNumber(
                    $(".HMTVE030InputDataK.txtForecast_C11").val()
                ) !=
            me.isPosNumber(hidsum1)
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtForecast_C8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_計画_合計と成約車種内訳_計画_合計が一致しません"
            );
            return false;
        }
        var hidsum2 = "";
        if (me.hidsum["SEIYAKU_DAISU_D"]) {
            hidsum2 = me.hidsum["SEIYAKU_DAISU_D"].toString();
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C8").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_C9").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_C10").val()) +
                me.isPosNumber($(".HMTVE030InputDataK.txtResults_C11").val()) !=
            me.isPosNumber(hidsum2)
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C8");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_実績_合計と成約車種内訳_実績_合計が一致しません"
            );
            return false;
        }
        var hidsum3 = "";
        if (me.hidsum["SIJYO_DAISU"]) {
            hidsum3 = me.hidsum["SIJYO_DAISU"].toString();
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C7").val()) !=
            me.isPosNumber(hidsum3)
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C7");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_デモ件数_実績と成約車種内訳_試乗_実績が一致しません"
            );
            return false;
        }
        if (
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C11").val()) <
            me.isPosNumber($(".HMTVE030InputDataK.txtResults_C12").val())
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE030InputDataK.txtResults_C11");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "成約内訳_NONAB_新他_実績と成約内訳_NONAB_内フリー_実績の大小関係が不正です"
            );
            return false;
        }
        return true;
    };
    // '**********************************************************************
    // '処 理 名：確定ボタンのイベント
    // '関 数 名：btnDecide_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：確報入力データに追加する
    // '**********************************************************************
    me.btnDecide_Click = function () {
        if (me.btnDecideCheck() == false) {
            return;
        }
        var url = "HMTVE/HMTVE030InputDataK/btnDecideClick";
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var tableDate = $(me.grid_id).jqGrid("getRowData");
        var data = {
            tableDate: tableDate,
            lblExhibitTermFrom: $(
                ".HMTVE030InputDataK.lblExhibitTermFrom"
            ).val(),
            ddlExhibitDay: $(".HMTVE030InputDataK.ddlExhibitDay").val(),
            txtForecast: {
                RAIJYO_KUMI_AB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtForecast_L1"
                ).val(),
                RAIJYO_KUMI_AB_SINTA: $(
                    ".HMTVE030InputDataK.txtForeCast_L2"
                ).val(),
                RAIJYO_KUMI_NONAB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtForecast_L3"
                ).val(),
                RAIJYO_KUMI_NONAB_SINTA: $(
                    ".HMTVE030InputDataK.txtForecast_L4"
                ).val(),
                RAIJYO_BUNSEKI_YOBIKOMI: $(
                    ".HMTVE030InputDataK.txtForecast_L11"
                ).val(),
                RAIJYO_BUNSEKI_KOUKOKU: $(
                    ".HMTVE030InputDataK.txtForecast_L13"
                ).val(),
                RAIJYO_BUNSEKI_MEDIA: $(
                    ".HMTVE030InputDataK.txtForecast_L14"
                ).val(),
                RAIJYO_BUNSEKI_CHIRASHI: $(
                    ".HMTVE030InputDataK.txtForecast_L15"
                ).val(),
                RAIJYO_BUNSEKI_TORIGAKARI: $(
                    ".HMTVE030InputDataK.txtForecast_L16"
                ).val(),
                RAIJYO_BUNSEKI_SYOKAI: $(
                    ".HMTVE030InputDataK.txtForecast_L17"
                ).val(),
                RAIJYO_BUNSEKI_WEB: $(
                    ".HMTVE030InputDataK.txtForecast_L18"
                ).val(),
                RAIJYO_BUNSEKI_SONOTA: $(
                    ".HMTVE030InputDataK.txtForecast_L19"
                ).text(),
                JIZEN_JYUNBI_DM: $(".HMTVE030InputDataK.txtForecast_L6").val(),
                JIZEN_JYUNBI_DH: $(".HMTVE030InputDataK.txtForecast_L7").val(),
                JIZEN_JYUNBI_POSTING: $(
                    ".HMTVE030InputDataK.txtForecast_L8"
                ).val(),
                JIZEN_JYUNBI_TEL: $(".HMTVE030InputDataK.txtForecast_L9").val(),
                JIZEN_JYUNBI_KAKUYAKU: $(
                    ".HMTVE030InputDataK.txtForecast_L10"
                ).val(),
                ENQUETE_KAISYU: $(".HMTVE030InputDataK.txtForecast_C1").val(),
                ABHOT_KOKYAKU: $(".HMTVE030InputDataK.txtForecast_C2").val(),
                ABHOT_SINTA: $(".HMTVE030InputDataK.txtForecast_C3").val(),
                ABHOT_ZAN: $(".HMTVE030InputDataK.txtForecast_C4").val(),
                SATEI_KOKYAKU: $(".HMTVE030InputDataK.txtForecast_C5").val(),
                SATEI_SINTA: $(".HMTVE030InputDataK.txtForecast_C6").val(),
                SATEI_KOKYAKU_TA: $(
                    ".HMTVE030InputDataK.txtForecast_C5T"
                ).val(),
                SATEI_SINTA_TA: $(".HMTVE030InputDataK.txtForecast_C6T").val(),
                DEMO_KENSU: $(".HMTVE030InputDataK.txtForecast_C7").val(),
                RUNCOST_KENSU: $(".HMTVE030InputDataK.txtForecast_C13").val(),
                SKYPLAN_KENSU: $(".HMTVE030InputDataK.txtForecast_C14").val(),
                RUNCOST_SEIYAKU_KENSU: $(
                    ".HMTVE030InputDataK.txtResults_C13"
                ).val(),
                SKYPLAN_KEIYAKU_KENSU: $(
                    ".HMTVE030InputDataK.txtResults_C14"
                ).val(),
                SEIYAKU_AB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtForecast_C8"
                ).val(),
                SEIYAKU_AB_SINTA: $(".HMTVE030InputDataK.txtForecast_C9").val(),
                SEIYAKU_NONAB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtForecast_C10"
                ).val(),
                SEIYAKU_NONAB_SINTA: $(
                    ".HMTVE030InputDataK.txtForecast_C11"
                ).val(),
            },
            txtResults: {
                RAIJYO_KUMI_AB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtResults1_L"
                ).val(),
                RAIJYO_KUMI_AB_SINTA: $(
                    ".HMTVE030InputDataK.txtResults_L2"
                ).val(),
                RAIJYO_KUMI_NONAB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtResults_L3"
                ).val(),
                RAIJYO_KUMI_NONAB_SINTA: $(
                    ".HMTVE030InputDataK.txtResults_L4"
                ).val(),
                RAIJYO_KUMI_NONAB_FREE: $(
                    ".HMTVE030InputDataK.txtResults_L5"
                ).val(),
                RAIJYO_BUNSEKI_YOBIKOMI: $(
                    ".HMTVE030InputDataK.txtResults_L11"
                ).val(),
                RAIJYO_BUNSEKI_KAKUYAKU: $(
                    ".HMTVE030InputDataK.txtResults_L12"
                ).val(),
                RAIJYO_BUNSEKI_KOUKOKU: $(
                    ".HMTVE030InputDataK.txtResults_L13"
                ).val(),
                RAIJYO_BUNSEKI_MEDIA: $(
                    ".HMTVE030InputDataK.txtResults_L14"
                ).val(),
                RAIJYO_BUNSEKI_CHIRASHI: $(
                    ".HMTVE030InputDataK.txtResults_L15"
                ).val(),
                RAIJYO_BUNSEKI_TORIGAKARI: $(
                    ".HMTVE030InputDataK.txtResults_L16"
                ).val(),
                RAIJYO_BUNSEKI_SYOKAI: $(
                    ".HMTVE030InputDataK.txtResults_L17"
                ).val(),
                RAIJYO_BUNSEKI_WEB: $(
                    ".HMTVE030InputDataK.txtResults_L18"
                ).val(),
                RAIJYO_BUNSEKI_SONOTA: $(
                    ".HMTVE030InputDataK.txtResults_L19"
                ).text(),
                JIZEN_JYUNBI_DM: $(".HMTVE030InputDataK.txtResults_L6").val(),
                JIZEN_JYUNBI_DH: $(".HMTVE030InputDataK.txtResults_L7").val(),
                JIZEN_JYUNBI_POSTING: $(
                    ".HMTVE030InputDataK.txtResults_L8"
                ).val(),
                JIZEN_JYUNBI_TEL: $(".HMTVE030InputDataK.txtResults_L9").val(),
                JIZEN_JYUNBI_KAKUYAKU: $(
                    ".HMTVE030InputDataK.txtResults_L10"
                ).val(),
                ENQUETE_KAISYU: $(".HMTVE030InputDataK.txtResults_C1").val(),
                ABHOT_KOKYAKU: $(".HMTVE030InputDataK.txtResults_C2").val(),
                ABHOT_SINTA: $(".HMTVE030InputDataK.txtResults_C3").val(),
                ABHOT_ZAN: $(".HMTVE030InputDataK.txtResults_C4").val(),
                SATEI_KOKYAKU: $(".HMTVE030InputDataK.txtResults_C5").val(),
                SATEI_SINTA: $(".HMTVE030InputDataK.txtResults_C6").val(),
                SATEI_KOKYAKU_TA: $(".HMTVE030InputDataK.txtResults_C5T").val(),
                SATEI_SINTA_TA: $(".HMTVE030InputDataK.txtResults_C6T").val(),
                DEMO_KENSU: $(".HMTVE030InputDataK.txtResults_C7").val(),
                RUNCOST_KENSU: $(".HMTVE030InputDataK.txtForecast_C13").val(),
                SKYPLAN_KENSU: $(".HMTVE030InputDataK.txtForecast_C14").val(),
                RUNCOST_SEIYAKU_KENSU: $(
                    ".HMTVE030InputDataK.txtResults_C13"
                ).val(),
                SKYPLAN_KEIYAKU_KENSU: $(
                    ".HMTVE030InputDataK.txtResults_C14"
                ).val(),
                SEIYAKU_AB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtResults_C8"
                ).val(),
                SEIYAKU_AB_SINTA: $(".HMTVE030InputDataK.txtResults_C9").val(),
                SEIYAKU_NONAB_KOKYAKU: $(
                    ".HMTVE030InputDataK.txtResults_C10"
                ).val(),
                SEIYAKU_NONAB_SINTA: $(
                    ".HMTVE030InputDataK.txtResults_C11"
                ).val(),
                SEIYAKU_NONAB_FREE: $(
                    ".HMTVE030InputDataK.txtResults_C12"
                ).val(),
            },
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                if (result["key"] == "W9999") {
                    $(".HMTVE030InputDataK.txtForecast_L1").trigger("focus");
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Clear();
            };

            me.clsComFnc.FncMsgBox("I0020");
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：削除ボタンのイベント
    // '関 数 名：btnDelete_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：確報入力データを削除する
    // '**********************************************************************
    me.btnDelete_Click = function () {
        var url = "HMTVE/HMTVE030InputDataK/btnDeleteClick";
        var data = {
            ddlExhibitDay: $(".HMTVE030InputDataK.ddlExhibitDay").val(),
            lblExhibitTermFrom: $(
                ".HMTVE030InputDataK.lblExhibitTermFrom"
            ).val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            //表示行数の設定
            if (!result["result"]) {
                if (result["key"] == "W9999") {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.Page_Clear();
            };
            me.clsComFnc.FncMsgBox("I0017");
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：当ページを初期化する
    // '関 数 名：Page_Clear
    // '引 数 １：なし
    // '戻 り 値：なし
    // '処理説明：当ページを初期の状態にセットする
    // '**********************************************************************
    me.Page_Clear = function () {
        me.getClear();
        $(".HMTVE030InputDataK.tblLeft").hide();
        $(".HMTVE030InputDataK.tblCenter").hide();
        $(".HMTVE030InputDataK.tblRight").hide();
        $(".HMTVE030InputDataK.btnDecide").button("disable");
        $(".HMTVE030InputDataK.btnDelete").button("disable");
        var footer = "";
        footer["SEIYAKU_DAISU_P"] = "";
        footer["SEIYAKU_DAISU_D"] = "";
        footer["SIJYO_DAISU"] = "";
        footer["RAIJYO_DAISU"] = "";
        $(me.grid_id).jqGrid("footerData", "set", footer);
        me.setExhibitTermDate(me.hidTermStart, me.hidTermEnd);
    };
    // '**********************************************************************
    // '処 理 名：TEXTBOXをクリアのイベント
    // '関 数 名：getClear
    // '引    数：なし
    // '戻 り 値：なし
    // '処理説明：画面項目No13～画面項目No75のTEXTBOXをクリアする
    // '**********************************************************************
    me.getClear = function () {
        $(".HMTVE030InputDataK.txtForecast_L1").val("");
        $(".HMTVE030InputDataK.txtResults1_L").val("");
        $(".HMTVE030InputDataK.txtForeCast_L2").val("");
        $(".HMTVE030InputDataK.txtResults_L2").val("");
        $(".HMTVE030InputDataK.txtForeCastSum_L1").text("");
        $(".HMTVE030InputDataK.txtResultsSum_L1").text("");
        $(".HMTVE030InputDataK.txtForecast_L3").val("");
        $(".HMTVE030InputDataK.txtResults_L3").val("");
        $(".HMTVE030InputDataK.txtForecast_L4").val("");
        $(".HMTVE030InputDataK.txtResults_L4").val("");
        $(".HMTVE030InputDataK.txtResults_L5").val("");
        $(".HMTVE030InputDataK.txtForeCastSum_L2").text("");
        $(".HMTVE030InputDataK.txtResultsSum_L2").text("");
        $(".HMTVE030InputDataK.txtForecast_L11").val("");
        $(".HMTVE030InputDataK.txtResults_L11").val("");
        $(".HMTVE030InputDataK.txtResults_L12").val("");
        $(".HMTVE030InputDataK.txtForecast_L13").val("");
        $(".HMTVE030InputDataK.txtResults_L13").val("");
        $(".HMTVE030InputDataK.txtForecast_L14").val("");
        $(".HMTVE030InputDataK.txtResults_L14").val("");
        $(".HMTVE030InputDataK.txtForecast_L15").val("");
        $(".HMTVE030InputDataK.txtResults_L15").val("");
        $(".HMTVE030InputDataK.txtForecast_L16").val("");
        $(".HMTVE030InputDataK.txtResults_L16").val("");
        $(".HMTVE030InputDataK.txtForecast_L17").val("");
        $(".HMTVE030InputDataK.txtResults_L17").val("");
        $(".HMTVE030InputDataK.txtForecast_L18").val("");
        $(".HMTVE030InputDataK.txtResults_L18").val("");
        $(".HMTVE030InputDataK.txtForecast_L19").text("");
        $(".HMTVE030InputDataK.txtResults_L19").text("");
        $(".HMTVE030InputDataK.txtForecast_L6").val("");
        $(".HMTVE030InputDataK.txtResults_L6").val("");
        $(".HMTVE030InputDataK.txtForecast_L7").val("");
        $(".HMTVE030InputDataK.txtResults_L7").val("");
        $(".HMTVE030InputDataK.txtForecast_L8").val("");
        $(".HMTVE030InputDataK.txtResults_L8").val("");
        $(".HMTVE030InputDataK.txtForecast_L9").val("");
        $(".HMTVE030InputDataK.txtResults_L9").val("");
        $(".HMTVE030InputDataK.txtForecast_L10").val("");
        $(".HMTVE030InputDataK.txtResults_L10").val("");
        $(".HMTVE030InputDataK.txtResults_L10").val("");
        $(".HMTVE030InputDataK.txtForecast_C1").val("");
        $(".HMTVE030InputDataK.txtResults_C1").val("");
        $(".HMTVE030InputDataK.txtForecast_C2").val("");
        $(".HMTVE030InputDataK.txtResults_C2").val("");
        $(".HMTVE030InputDataK.txtForecast_C3").val("");
        $(".HMTVE030InputDataK.txtResults_C3").val("");
        $(".HMTVE030InputDataK.txtForecast_C4").val("");
        $(".HMTVE030InputDataK.txtResults_C4").val("");
        $(".HMTVE030InputDataK.txtForecast_C5").val("");
        $(".HMTVE030InputDataK.txtResults_C5").val("");
        $(".HMTVE030InputDataK.txtForecast_C6").val("");
        $(".HMTVE030InputDataK.txtResults_C6").val("");
        $(".HMTVE030InputDataK.txtForecast_C5T").val("");
        $(".HMTVE030InputDataK.txtResults_C5T").val("");
        $(".HMTVE030InputDataK.txtForecast_C6T").val("");
        $(".HMTVE030InputDataK.txtResults_C6T").val("");
        $(".HMTVE030InputDataK.txtForecast_C7").val("");
        $(".HMTVE030InputDataK.txtResults_C7").val("");
        $(".HMTVE030InputDataK.txtForecast_C8").val("");
        $(".HMTVE030InputDataK.txtResults_C8").val("");
        $(".HMTVE030InputDataK.txtForecast_C9").val("");
        $(".HMTVE030InputDataK.txtResults_C9").val("");
        $(".HMTVE030InputDataK.txtForecast_C10").val("");
        $(".HMTVE030InputDataK.txtResults_C10").val("");
        $(".HMTVE030InputDataK.txtForecast_C11").val("");
        $(".HMTVE030InputDataK.txtResults_C11").val("");
        $(".HMTVE030InputDataK.txtResults_C12").val("");
        $(".HMTVE030InputDataK.txtForecast_C13").val("");
        $(".HMTVE030InputDataK.txtResults_C13").val("");
        $(".HMTVE030InputDataK.txtForecast_C14").val("");
        $(".HMTVE030InputDataK.txtResults_C14").val("");
    };
    me.ddlExhibitDay_SelectedIndexChanged = function () {
        if ($(".HMTVE030InputDataK.tblLeft").css("display") == "block") {
            me.btnView_Click();
        } else {
            $(".HMTVE030InputDataK.tblLeft").hide();
            $(".HMTVE030InputDataK.tblCenter").hide();
            $(".HMTVE030InputDataK.tblRight").hide();
        }
    };
    //時間間隔数を取得する
    me.DateDiff = function (start, end) {
        var sdate = new Date(start);
        var now = new Date(end);
        var days = now.getTime() - sdate.getTime();
        var day = parseInt(days / (1000 * 60 * 60 * 24));
        return day;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE030InputDataK = new HMTVE.HMTVE030InputDataK();
    o_HMTVE_HMTVE030InputDataK.load();
    o_HMTVE_HMTVE.HMTVE030InputDataK = o_HMTVE_HMTVE030InputDataK;
});
