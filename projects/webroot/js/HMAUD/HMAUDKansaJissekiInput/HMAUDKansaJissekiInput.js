/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20240618           要望対応            20240618_HMAUD_受入検証                     YIN
 * 20250219           機能変更               20250219_内部統制_改修要望.xlsx          LHB
 * 20250414    「確定」ボタンを押して、その後「保存」ボタンを押すと、ご指摘のメッセージが表示され更新できない現象が出ています         LHB
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKansaJissekiInput");

HMAUD.HMAUDKansaJissekiInput = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.sys_id = "HMAUD";
    me.id = "HMAUDKansaJissekiInput";
    me.HMAUD = new HMAUD.HMAUD();
    me.lastsel = 0;
    me.prePage = "";
    //呼出元画面を戻る時用
    me.sessionCour = "";
    me.sessionKyotenCD = "";
    me.sessionTerritory = "";
    // 監査実績照会画面を戻る時用:ステータス
    me.sessionStatus = "";
    // 監査実績照会画面を戻る時用:領域
    me.sessionTerritoryArr = [];
    //監査実績照会画面を戻る時用:クール
    me.sessionCourShokai = "";
    //監査実績照会画面を戻る時用:拠点
    me.sessionKyotenCDShokai = "";
    //拠点
    me.kyotenList = "";
    //監査ID
    me.check_id = "";
    //現在のクール数
    me.gennzayiCour = "";
    me.allCourData = "";
    //原始データ
    //me.originalData = '';
    // 20250219 LHB INS S
    me.posSearchInput_data = [];
    // 20250219 LHB INS E

    // jqgrid
    me.grid_id = "#HMAUDKansaJissekiInput_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearch_Click";
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel = [
        {
            name: "ROW_NO",
            label: "ID",
            index: "ROW_NO",
            width: 50,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN1",
            label: "業務手順書NO.",
            index: "COLUMN1",
            width: 100,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN2",
            label: "業務手順書項目",
            index: "COLUMN2",
            width: 110,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN3",
            label: "担当",
            index: "COLUMN3",
            width: 85,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN4",
            label: "業務内容",
            index: "COLUMN4",
            width: 190,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN5",
            label: "留意点",
            index: "COLUMN5",
            width: 240,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN6",
            label: "監査方法",
            index: "COLUMN6",
            width: 135,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "COLUMN7",
            label: "監査項目",
            index: "COLUMN7",
            width: 220,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "RESULT",
            label: "実施度合評価<br>(〇か×で評価)",
            index: "RESULT",
            width: 100,
            align: "center",
            sortable: false,
            editable: true,
            edittype: "select",
            formatter: "select",
            editoptions: {
                value: {
                    0: "",
                    1: "〇",
                    2: "×",
                },
            },
        },
        {
            name: "REMARKS",
            label: "備考·特記事項",
            index: "REMARKS",
            width: 170,
            align: "left",
            sortable: false,
            editable: true,
            edittype: "textarea",
            formatter: function (cellValue) {
                return cellValue == null ? "" : cellValue;
            },
            editoptions: {
                rows: "3",
                cols: "6",
                maxlength: 1000,
            },
        },
        {
            name: "CHECK_LST_ID",
            label: "",
            index: "CHECK_LST_ID",
            width: 50,
            hidden: true,
        },
        {
            name: "CHECK_RESULT_ID",
            label: "",
            index: "CHECK_RESULT_ID",
            width: 50,
            hidden: true,
        },
        //20230420 caina ins s
        {
            name: "CHECK_ID",
            label: "",
            index: "CHECK_ID",
            width: 50,
            hidden: true,
        },
        {
            name: "RES",
            label: "",
            index: "RES",
            width: 50,
            hidden: true,
        },
        {
            name: "REM",
            label: "",
            index: "REM",
            width: 50,
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "",
            index: "UPD_DATE",
            width: 150,
            hidden: true,
        },
        //20230420 caina ins e
    ];
    //領域
    me.territorySelectList = [
        {
            val: "1",
            text: "営業",
        },
        {
            val: "2",
            text: "サービス",
        },
        {
            val: "3",
            text: "管理",
        },
        {
            val: "4",
            text: "業売",
        },
        {
            val: "5",
            text: "業売管理",
        },
        // 20250219 LHB INS S
        {
            val: "6",
            text: "カーセブン",
        },
        // 20250219 LHB INS E
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDKansaJissekiInput.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDKansaJissekiInput.dateInput",
        type: "datepicker",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =objdrShopSya
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //検索ボタンクリック
    $(".HMAUDKansaJissekiInput.btnSearch").click(function () {
        me.lastsel = 0;
        me.btnSearch_Click();
    });
    //保存ボタンクリック
    $(".HMAUDKansaJissekiInput.btnSave").click(function () {
        if (!me.fncInputCheck()) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnSave_Click;
        me.clsComFnc.FncMsgBox("QY010");
    });
    //実績照会へボタンクリック
    $(".HMAUDKansaJissekiInput.btnShokai").click(function () {
        me.btnShokai_Click();
    });
    //確定ボタンクリック
    $(".HMAUDKansaJissekiInput.btnConfirm").click(function () {
        if (!me.fncInputCheck() || !me.ConfirmClickCheck()) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnConfirm_Click;
        me.clsComFnc.FncMsgBox("QY999", "確定します。よろしいですか？");
    });
    //報告書へボタンクリック
    $(".HMAUDKansaJissekiInput.btnReport").click(function () {
        me.btnReport_Click();
    });
    //拠点
    $(".HMAUDKansaJissekiInput.posSearchInput").change(function () {
        me.posSearch_Change();
        me.fncPnlListHide();
    });
    //クール
    $(".HMAUDKansaJissekiInput.coursSearchInput").change(function () {
        me.fncPnlListHide();
        //クールchange
        me.fncCourChange();
    });
    //領域
    $(".HMAUDKansaJissekiInput.territorySelect").change(function () {
        me.fncPnlListHide();
    });
    //左メニューを閉じたときに明細の幅を広げて表示
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDKansaJissekiInput fieldset").width()
            );
        }, 500);
    });
    //実施日
    $(".HMAUDKansaJissekiInput.dateInput").on("blur", function () {
        if (
            me.clsComFnc.CheckDate($(".HMAUDKansaJissekiInput.dateInput")) ==
            false
        ) {
            $(".HMAUDKansaJissekiInput.dateInput").val(
                new Date().Format("yyyy/MM/dd")
            );
            $(".HMAUDKansaJissekiInput.dateInput").select();
        }
    });

    //指摘事項NO58:ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option
        );
        me.setTableSize();
        $(me.grid_id).jqGrid("bindKeys");
        //領域
        $(".HMAUDKansaJissekiInput.territorySelect").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMAUDKansaJissekiInput.territorySelect");
        for (var i = 0; i < me.territorySelectList.length; i++) {
            $("<option></option>")
                .val(me.territorySelectList[i].val)
                .text(me.territorySelectList[i].text)
                .appendTo(".HMAUDKansaJissekiInput.territorySelect");
        }
        //hide button and jqgrid
        me.fncPnlListHide();
        $(".HMAUDKansaJissekiInput.coursSearchInput").trigger("focus");
        //実施日
        $(".HMAUDKansaJissekiInput.dateInput").val(
            new Date().Format("yyyy/MM/dd")
        );

        //拠点マスタのデータを取得
        var url = me.sys_id + "/" + me.id + "/" + "fncGetKyoten";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //検索
                $(".HMAUDKansaJissekiInput.btnSearch").button("disable");
                //実績照会へ
                $(".HMAUDKansaJissekiInput.btnShokai").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            me.kyotenList = result["data"]["kyoten"];
            $(".HMAUDKansaJissekiInput.posSearchInput").find("option").remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDKansaJissekiInput.posSearchInput");
            // 20250219 LHB INS S
            $x = 0;
            // 20250219 LHB INS E
            for (var v = 0; v < me.kyotenList.length; v++) {
                //指摘事項NO64:拠点プルダウンの表記を拠点名＋領域名にしてほしい
                var foundNM_array = me.territorySelectList.filter(function (
                    element
                ) {
                    return element["val"] == me.kyotenList[v]["TERRITORY"];
                });
                $("<option></option>")
                    .val(
                        me.kyotenList[v]["KYOTEN_CD"] +
                            me.kyotenList[v]["TERRITORY"]
                    )
                    .text(
                        me.kyotenList[v]["KYOTEN_NAME"] +
                            "・" +
                            foundNM_array[0]["text"]
                    )
                    .appendTo(".HMAUDKansaJissekiInput.posSearchInput");
                // 20250219 LHB INS S
                if (me.kyotenList[v]["TERRITORY"] == "6") {
                    me.posSearchInput_data[$x] =
                        me.kyotenList[v]["KYOTEN_CD"] +
                        me.kyotenList[v]["TERRITORY"];
                    $x++;
                    $(
                        ".HMAUDKansaJissekiInput.posSearchInput option[value=" +
                            me.kyotenList[v]["KYOTEN_CD"] +
                            me.kyotenList[v]["TERRITORY"] +
                            "]"
                    ).hide();
                }
                // 20250219 LHB INS E
            }
            //指摘事項NO65:クール数の欄をプルダウンにする
            $(".HMAUDKansaJissekiInput.coursSearchInput")
                .find("option")
                .remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDKansaJissekiInput.coursSearchInput");
            if (result["data"]["cour"].length > 0) {
                var courAll = result["data"]["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDKansaJissekiInput.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
            }
            //呼出元画面からの値を画面に表示
            if (
                gdmz.SessionCour != undefined &&
                gdmz.SessionKyotenCD != undefined &&
                gdmz.territory != undefined
            ) {
                //監査実績照会画面を戻る時用:クール
                me.sessionCourShokai = gdmz.SessionCourShokai;
                delete gdmz.SessionCourShokai;
                //監査実績照会画面を戻る時用:拠点
                me.sessionKyotenCDShokai = gdmz.SessionKyotenCDShokai;
                delete gdmz.SessionKyotenCDShokai;
                //監査実績照会画面を戻る時用:ステータス
                me.sessionStatus = gdmz.SessionStatus;
                delete gdmz.SessionStatus;
                //監査実績照会画面を戻る時用:領域
                me.sessionTerritoryArr = gdmz.SessionTerritoryArr;
                delete gdmz.SessionTerritoryArr;

                //呼出元画面を戻る時用
                me.sessionCour = gdmz.SessionCour;
                me.sessionKyotenCD = gdmz.SessionKyotenCD;
                me.sessionTerritory = gdmz.territory;

                //クール
                $(".HMAUDKansaJissekiInput.coursSearchInput").val(
                    me.sessionCour
                );
                //クールchange
                me.fncCourChange();
                //拠点
                $(".HMAUDKansaJissekiInput.posSearchInput").val(
                    me.sessionKyotenCD + me.sessionTerritory
                );
                //領域
                $(".HMAUDKansaJissekiInput.territorySelect").val(
                    me.sessionTerritory
                );
                //拠点名をセット
                me.posSearch_Change();
                setTimeout(function () {
                    //データを検索
                    me.btnSearch_Click();
                }, 100);
                delete gdmz.SessionCour;
                delete gdmz.SessionKyotenCD;
                delete gdmz.territory;
                me.prePage = gdmz.SessionPrePG;
                delete gdmz.SessionPrePG;
            } else {
                //検索条件・クールには 現在のクール数を初期表示
                $(".HMAUDKansaJissekiInput.coursSearchInput").val(
                    me.gennzayiCour
                );
                //クールchange
                me.fncCourChange();
            }
        };
        me.ajax.send(url, "", 0);
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKansaJissekiInput fieldset").width()
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKansaJissekiInput.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKansaJissekiInput fieldset").height();
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 98;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    //'**********************************************************************
    //'処 理 名：行選択効果の設定
    //'関 数 名：jqgridEditSet
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：行選択効果の設定
    //'**********************************************************************
    me.jqgridEditSet = function () {
        //edit cell
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowId, _status, e) {
                if (
                    $(me.grid_id).getColProp("RESULT").editable ||
                    $(me.grid_id).getColProp("REMARKS").editable
                ) {
                    if (typeof e != "undefined") {
                        var focusIndex =
                            e.target.cellIndex !== undefined
                                ? e.target.cellIndex
                                : e.target.parentElement.cellIndex;
                        if (focusIndex < 8) {
                            //when click 'td' the first 'editble cell' focus
                            focusIndex = 8;
                        }
                        //編集可能なセルをクリック、上下キー
                        if (rowId && rowId != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowId;
                        }
                        $(me.grid_id).jqGrid("editRow", rowId, {
                            keys: true,
                            focusField: focusIndex,
                        });
                    } else {
                        //tab、enter、tab+shift
                        if (rowId && rowId != me.lastsel) {
                            $(me.grid_id).jqGrid(
                                "saveRow",
                                me.lastsel,
                                null,
                                "clientArray"
                            );
                            me.lastsel = rowId;
                        }
                        $(me.grid_id).jqGrid("editRow", rowId, {
                            keys: true,
                            focusField: false,
                        });
                    }
                    gdmz.common.jqgrid.setKeybordEvents(
                        me.grid_id,
                        e,
                        rowId
                    );
                }
            },
        });
    };
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索ボタンクリック
    //'**********************************************************************
    me.btnSearch_Click = function () {
        //hide button and jqgrid
        me.fncPnlListHide();
        $(me.grid_id).jqGrid("clearGridData");
        if (!me.fncInputCheck()) {
            return;
        }
        //クール
        var coursSearchInput = $(
            ".HMAUDKansaJissekiInput.coursSearchInput"
        ).val();
        //拠点
        var posSearchInput = $(".HMAUDKansaJissekiInput.posSearchInput").val();
        //領域
        var territorySelect = $(
            ".HMAUDKansaJissekiInput.territorySelect"
        ).val();
        var data = {
            COURS: $.trim(coursSearchInput),
            KYOTEN_CD: posSearchInput.substring(0, posSearchInput.length - 1),
            TERRITORY: territorySelect,
        };
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                if (result["error"] == "nouser") {
                    //指摘事項NO34:ログインIDが存在していなかったら抽出対象外
                    //該当するユーザーは登録されていません！
                    me.clsComFnc.FncMsgBox("W0008", "ユーザー");
                } else if (result["error"] == "nodata") {
                    //該当するデータは登録されていません！

                    me.clsComFnc.FncMsgBox("W0008", "データ");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            if (returnFLG == "nodata") {
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            //原始データ
            //me.originalData = result['rows'];
            //行選択効果の設定
            me.jqgridEditSet();
            //画面制御
            me.fncEditControl(result["audit"], $.trim(coursSearchInput));
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    //画面制御
    me.fncEditControl = function (resdata, coursSearch) {
        $(me.grid_id).setColProp("RESULT", {
            editable: false,
        });
        $(me.grid_id).setColProp("REMARKS", {
            editable: true,
        });
        //ボタンの設定
        $(".HMAUDKansaJissekiInput.btnSave").show();
        $(".HMAUDKansaJissekiInput.btnConfirm").hide();

        //監査ID
        me.check_id = resdata["CHECK_ID"];
        if (resdata["CHECK_DT"] !== null) {
            $(".HMAUDKansaJissekiInput.dateInput").val(resdata["CHECK_DT"]);
        }
        //指摘事項NO56:現クール以前のデータ登録、更新は管理者(HMAUD_MST_ADMIN)のみ実施可能にしてほしい
        //20230315 LIU UPD S
        //if (parseInt(coursSearch) < parseInt(me.gennzayiCour) && resdata['SUBAUDIT'] == false)
        //\99.提供資料\FromJP\20230310：ログインユーザーが ビューアマスタ上に存在している場合、監査スケジュールに登録されていなくてもデータ検索可能
        if (
            (parseInt(coursSearch) < parseInt(me.gennzayiCour) &&
                resdata["SUBAUDIT"] == false) ||
            resdata["VIEW"] == true
        ) {
            //20230315 LIU UPD E
            $(me.grid_id).setColProp("REMARKS", {
                editable: false,
            });
            //ボタンの設定
            $(".HMAUDKansaJissekiInput.btnSave").hide();
        } else {
            //報告書ヘッダ.ステータス＝00、01の場合
            // 20240618 YIN UPD S
            // if (resdata['STATUS'] == '00' || resdata['STATUS'] == '01') {
            if (
                resdata["STATUS"] == "00" ||
                resdata["STATUS"] == "01" ||
                resdata["STATUS"] == "91"
            ) {
                // 20240618 YIN UPD E
                if (resdata["ROLE"] == "1" || resdata["SUBAUDIT"] == true) {
                    //実施度合評価は 監査人、監査人補助だけ編集可
                    $(me.grid_id).setColProp("RESULT", {
                        editable: true,
                        editoptions: {
                            dataEvents: [
                                {
                                    type: "change",
                                    fn: function (e) {
                                        if (e.target.value != 0) {
                                            //実績度合評価の入力で○、×を選択したら次の項目へ遷移
                                            $(
                                                "#" + me.lastsel + "_REMARKS"
                                            ).select();
                                        }
                                    },
                                },
                            ],
                        },
                    });
                }
                //ボタンの設定
                if (resdata["ROLE"] == "1") {
                    //確定ボタン:ログインユーザが「監査人」である場合のみ使用可能
                    $(".HMAUDKansaJissekiInput.btnConfirm").show();
                }
            } else {
                $(me.grid_id).setColProp("REMARKS", {
                    editable: false,
                });
                //ボタンの設定
                $(".HMAUDKansaJissekiInput.btnSave").hide();
            }
        }
        $(".HMAUDKansaJissekiInput.pnlList").show();
        //x行目を選択状態にする
        //指摘事項NO82:保存、確定した後に 明細の選択行を 保持しておく
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
    };
    //'**********************************************************************
    //'処 理 名：検索条件check
    //'関 数 名：fncInputCheck
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索条件check
    //'**********************************************************************
    me.fncInputCheck = function () {
        //クール
        var coursSearchInput = $(
            ".HMAUDKansaJissekiInput.coursSearchInput"
        ).val();
        //拠点
        var posSearchInput = $(".HMAUDKansaJissekiInput.posSearchInput").val();
        //領域
        var territorySelect = $(
            ".HMAUDKansaJissekiInput.territorySelect"
        ).val();
        if ($.trim(coursSearchInput) == "") {
            me.clsComFnc.ObjFocus = $(
                ".HMAUDKansaJissekiInput.coursSearchInput"
            );
            //クールを入力して下さい！
            me.clsComFnc.FncMsgBox("W0017", "クール");
            return false;
        }
        if (posSearchInput == "" || posSearchInput == null) {
            me.clsComFnc.ObjFocus = $(".HMAUDKansaJissekiInput.posSearchInput");
            //拠点を選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "拠点を選択して下さい！");
            return false;
        }
        if (territorySelect == "" || territorySelect == null) {
            me.clsComFnc.ObjFocus = $(
                ".HMAUDKansaJissekiInput.territorySelect"
            );
            //領域を選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "領域を選択して下さい！");
            return false;
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：報告書へボタンクリック
    //'関 数 名：btnReport_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：報告書へボタンクリック
    //'**********************************************************************
    me.btnReport_Click = function () {
        if (!me.fncInputCheck()) {
            return;
        }
        //クール
        var coursSearchInput = $(
            ".HMAUDKansaJissekiInput.coursSearchInput"
        ).val();
        //拠点コード
        var posSearchInput = $(".HMAUDKansaJissekiInput.posSearchInput").val();
        //クール
        gdmz.SessionCour = coursSearchInput;
        //拠点コード
        gdmz.SessionKyotenCD = posSearchInput.substring(
            0,
            posSearchInput.length - 1
        );
        //領域
        gdmz.territory = $(".HMAUDKansaJissekiInput.territorySelect").val();
        gdmz.SessionPrePG = "HMAUDKansaJissekiInput";
        //報告書入力画面に遷移
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDKansaJissekiInput"
        );
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#HMAUDReportInput");
    };
    //'**********************************************************************
    //'処 理 名：クールchange
    //'関 数 名：fncCourChange
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：指摘事項NO76:クールを選択したら開始日～終了日を表示
    //'**********************************************************************
    me.fncCourChange = function () {
        var cour = $(".HMAUDKansaJissekiInput.coursSearchInput").val();
        var foundDT = undefined;
        // 20250219 LHB INS S
        if (parseInt(cour) >= 18) {
            $(
                '.HMAUDKansaJissekiInput.territorySelect option[value="6"]'
            ).show();
        } else {
            $(
                '.HMAUDKansaJissekiInput.territorySelect option[value="6"]'
            ).hide();
            var skyotenVal = $(".HMAUDKansaJissekiInput.posSearchInput").val();
            if (skyotenVal != "" && skyotenVal.substring(3) == "6") {
                $(".HMAUDKansaJissekiInput.posSearchInput").val("");
                $(".HMAUDKansaJissekiInput.territorySelect").val("");
            }
        }
        if (me.posSearchInput_data.length > 0) {
            for (
                let index = 0;
                index < me.posSearchInput_data.length;
                index++
            ) {
                if (parseInt(cour) >= 18) {
                    $(
                        ".HMAUDKansaJissekiInput.posSearchInput option[value=" +
                            me.posSearchInput_data[index] +
                            "]"
                    ).show();
                } else {
                    $(
                        ".HMAUDKansaJissekiInput.posSearchInput option[value=" +
                            me.posSearchInput_data[index] +
                            "]"
                    ).hide();
                }
            }
        }
        // 20250219 LHB INS E
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDKansaJissekiInput.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
    };
    //'**********************************************************************
    //'処 理 名：拠点change
    //'関 数 名：posSearch_Change
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：拠点change
    //'**********************************************************************
    me.posSearch_Change = function () {
        var skyotenVal = $(".HMAUDKansaJissekiInput.posSearchInput").val();
        var foundNM = undefined;
        if (me.kyotenList) {
            var foundNM_array = me.kyotenList.filter(function (element) {
                return (
                    element["KYOTEN_CD"] + element["TERRITORY"] == skyotenVal
                );
            });
            if (foundNM_array.length == 0 && skyotenVal !== "") {
                me.clsComFnc.ObjFocus = $(
                    ".HMAUDKansaJissekiInput.posSearchInput"
                );
                //該当する拠点コードは登録されていません！
                me.clsComFnc.FncMsgBox("W0007", "拠点");
            } else {
                foundNM = foundNM_array[0];
            }
        }
        //領域
        $(".HMAUDKansaJissekiInput.territorySelect").val(
            foundNM ? foundNM["TERRITORY"] : ""
        );
    };
    //'**********************************************************************
    //'処 理 名：保存ボタンクリック
    //'関 数 名：btnSave_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：保存ボタンクリック
    //'**********************************************************************
    me.btnSave_Click = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        //すべて○で登録した場合
        var changeSTATUS = 1;
        var ids = $(me.grid_id).getDataIDs();
        for (var i = 0; i < ids.length; i++) {
            var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            if ($.trim(rowdata["RESULT"]) != 1) {
                changeSTATUS = 0;
            }
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnSave_Click";
        var data = {
            //実施日
            sysDate: $(".HMAUDKansaJissekiInput.dateInput").val(),
            CHECK_ID: me.check_id,
            tableData: $(me.grid_id).jqGrid("getRowData"),
            changeSTATUS: changeSTATUS,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                //20230423 caina upd s
                if (
                    result["error"] ===
                    "他ユーザーによってデータが更新されています。再読込してください"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                //20230423 caina upd e
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                me.btnSearch_Click();
            };
            //登録が完了しました。
            me.clsComFnc.FncMsgBox("I0016");
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：実績照会へボタンクリック
    //'関 数 名：btnShokai_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：実績照会へボタンクリック
    //'**********************************************************************
    me.btnShokai_Click = function () {
        //監査実績照会へ戻る
        if (me.prePage == "HMAUDKansaJissekiShokai") {
            //ステータス
            gdmz.SessionStatus = me.sessionStatus;
            //領域
            gdmz.SessionTerritoryArr = me.sessionTerritoryArr;
            //クール
            gdmz.SessionCourShokai = me.sessionCourShokai;
            //拠点コード
            gdmz.SessionKyotenCDShokai = me.sessionKyotenCDShokai;
        } else {
            //領域
            gdmz.SessionTerritoryArr = $(
                ".HMAUDKansaJissekiInput.territorySelect"
            ).val();
            //クール
            gdmz.SessionCourShokai = $(
                ".HMAUDKansaJissekiInput.coursSearchInput"
            ).val();
            //拠点コード
            gdmz.SessionKyotenCDShokai = $(
                ".HMAUDKansaJissekiInput.posSearchInput"
            ).val();
        }
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDKansaJissekiInput"
        );
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#HMAUDKansaJissekiShokai"
        );
    };
    //'**********************************************************************
    //'処 理 名：入力欄check
    //'関 数 名：ConfirmClickCheck
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：入力欄check
    //'**********************************************************************
    me.ConfirmClickCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var ids = $(me.grid_id).getDataIDs();
        for (var i = 0; i < ids.length; i++) {
            var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            if ($.trim(rowdata["RESULT"]) == "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $(
                    me.grid_id + " #" + ids[i] + "_RESULT"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "実施度合評価を入力してください。"
                );
                return false;
            }
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：確定ボタンクリック
    //'関 数 名：btnConfirm_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：確定ボタンクリック
    //'**********************************************************************
    me.btnConfirm_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnConfirm_Click";
        var data = {
            CHECK_ID: me.check_id,
            //実施日
            sysDate: $(".HMAUDKansaJissekiInput.dateInput").val(),
            //指摘事項NO54:確定ボタンをクリックされたら、保存イベントの処理も実行する
            //データが変えた場合、データを保存する必要がある
            tableData: $(me.grid_id).jqGrid("getRowData"),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                //20230423 caina upd s
                if (
                    result["error"] ===
                    "他ユーザーによってデータが更新されています。再読込してください"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                //20230423 caina upd e
                return;
            }
            me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                //20250414 LHB INS S
                me.btnSearch_Click();
                //20250414 LHB INS E
                //指摘事項NO82:確定した後に 明細の選択行を 保持しておく
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            };
            //確定登録が完了しました。
            me.clsComFnc.FncMsgBox("I0020");
        };
        me.ajax.send(url, data, 0);
    };
    //hide button and jqgrid
    me.fncPnlListHide = function () {
        $(".HMAUDKansaJissekiInput.pnlList").hide();
        $(".HMAUDKansaJissekiInput.btnSave").hide();
        $(".HMAUDKansaJissekiInput.btnConfirm").hide();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMAUD_HMAUDKansaJissekiInput = new HMAUD.HMAUDKansaJissekiInput();
    o_HMAUD_HMAUDKansaJissekiInput.load();
    o_HMAUD_HMAUD.HMAUDKansaJissekiInput = o_HMAUD_HMAUDKansaJissekiInput;
});
