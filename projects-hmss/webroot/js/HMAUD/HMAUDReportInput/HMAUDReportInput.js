/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230103           機能追加　　　　　   20221226_内部統制_仕様変更                 YIN
 * 20230801           機能変更　　　  データを更新する後、選択行を保持しておく          lujunxia
 * 20240313                         画面上の表記「常務」を「取締役」に変更お願いします  caina
 * 20240412                         管理者のみ、過去クールのデータは使用可能になり、管理者以外の場合、操作不可能になりますが、この制限は除く  ciyuanchen
 * 20240530           機能追加      メール通知機能にて、クールと領域名も一緒に出力する   YIN
 * 20240612           機能追加      報告書入力で 差戻を実行する際、差戻先を ユーザーが選択可能にしてほしい    CI
 * 20241030           機能変更　　202410_内部統制システム_集計機能改善対応 指摘回数を細分化         LHB
 * 20250219           機能変更               20250219_内部統制_改修要望.xlsx          LHB
 * 20250403           機能追加       		     202504_内部統制_要望.xlsx        CI
 * 20250512           機能追加       		     202505_内部統制_要望.xlsx        CI
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
 * 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDReportInput");

HMAUD.HMAUDReportInput = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDReportInput";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDReportInput_tblMain";
    me.grid_id1 = "#HMAUDReportInput_tblMain2";

    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.g_url1 = me.sys_id + "/" + me.id + "/fncgetHeaddata";
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
    me.coursnow = "";
    me.allCourData = "";
    me.admin = "";
    me.lastsel = 0;
    me.SessionPrePG = "";
    me.SessionPrePG1 = "";
    me.report_id = "";
    me.check_id = "";
    me.status = "";
    me.originalData = "";
    me.allCourData = "";
    // 20250219 LHB INS S
    me.posSearch_data = [];
    // 20250219 LHB INS E
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
            label: "項目<br>ID",
            index: "ROW_NO",
            width: me.ratio === 1.5 ? 20 : 40,
            align: "left",
            sortable: false,
        },
        //20250508 CI INS S
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
        //20250508 CI INS E
        {
            name: "COLUMN7",
            label: "監査項目",
            index: "COLUMN7",
            width: me.ratio === 1.5 ? 180 : 240,
            align: "left",
            sortable: false,
        },
        {
            name: "REPORT_LIST_ID",
            label: "",
            index: "REPORT_LIST_ID",
            hidden: true,
        },
        {
            name: "CHECK_LIST_ID",
            label: "",
            index: "CHECK_LIST_ID",
            hidden: true,
        },
        {
            name: "POINTED",
            label: "具体的な<br>指摘事項",
            index: "POINTED",
            width: me.ratio === 1.5 ? 150 : 200,
            align: "left",
            editable: false,
            sortable: false,
        },
        {
            name: "IMPROVE_DETAIL",
            label: "改善取組",
            index: "IMPROVE_DETAIL",
            width: me.ratio === 1.5 ? 160 : 210,
            align: "left",
            editable: false,
            sortable: false,
        },
        {
            name: "IMPROVE_PLAN_DT",
            label: "改善予<br>定日",
            index: "IMPROVE_PLAN_DT",
            width: 48,
            align: "left",
            editable: false,
            sortable: false,
            // 20250509 CI INS S
            formatter: function (cellValue, _options, _rowData) {
                if (!cellValue) return "";
                const datePattern = /^\d{4}[/-]\d{2}[/-]\d{2}$/;
                if (!datePattern.test(cellValue)) {
                    return cellValue;
                }
                let date = new Date(cellValue);
                if (isNaN(date.getTime())) return cellValue;
                let mm = String(date.getMonth() + 1).padStart(2, "0");
                let dd = String(date.getDate()).padStart(2, "0");
                return `<span data-full="${cellValue}" style="pointer-events: none;">${mm}/${dd}</span>`;
            },
            // unformatセルからdata-fullを読み込んで元の完全な日付データを取得する
            unformat: function (cellContent, _options, cell) {
                let span = cell.querySelector("span");
                if (span) {
                    // data-fullに格納されている元の値を返します
                    return span.getAttribute("data-full");
                }
                return cellContent;
            },
            // 20250509 CI INS E
        },
        {
            name: "IMPROVE_DT",
            label: "改善日",
            index: "IMPROVE_DT",
            width: 48,
            align: "left",
            editable: false,
            sortable: false,
            // 20250509 CI INS S
            formatter: function (cellValue, _options, _rowData) {
                if (!cellValue) return "";
                const datePattern = /^\d{4}[/-]\d{2}[/-]\d{2}$/;
                if (!datePattern.test(cellValue)) {
                    return cellValue;
                }
                let date = new Date(cellValue);
                if (isNaN(date.getTime())) return cellValue;
                let mm = String(date.getMonth() + 1).padStart(2, "0");
                let dd = String(date.getDate()).padStart(2, "0");
                return `<span data-full="${cellValue}">${mm}/${dd}</span>`;
            },
            // unformatセルからdata-fullを読み込んで元の完全な日付データを取得する
            unformat: function (cellContent, _options, cell) {
                let span = cell.querySelector("span");
                if (span) {
                    // data-fullに格納されている元の値を返します
                    return span.getAttribute("data-full");
                }
                return cellContent;
            },
            // 20250509 CI INS E
        },
        // 20241030 LHB ins s
        {
            name: "ROW_NO2",
            label: "連続<br>指摘<br>回数",
            index: "ROW_NO2",
            width: me.ratio === 1.5 ? 40 : 50,
            align: "left",
            editable: false,
            sortable: false,
        },
        // 20241030 LHB ins e
        {
            name: "ROW_NO1",
            // 20241030 LHB upd s
            // label: "過去<br>指摘<br>回数",
            label: "累積<br>指摘<br>回数",
            // 20241030 LHB upd e
            index: "ROW_NO1",
            width: me.ratio === 1.5 ? 40 : 50,
            align: "left",
            editable: false,
            sortable: false,
        },
        {
            name: "KEYPERSON_CHECK",
            label: "キー<br>マン<br>確認",
            index: "KEYPERSON_CHECK",
            width: me.ratio === 1.5 ? 40 : 50,
            align: "left",
            sortable: false,
            editable: false,
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
            name: "KEYPERSON_COMMENT",
            label: "キーマン<br>コメント",
            index: "KEYPERSON_COMMENT",
            width: me.ratio === 1.5 ? 215 : 245,
            align: "left",
            sortable: false,
            editable: false,
        },
        {
            name: "UPD_DATE",
            label: "",
            index: "UPD_DATE",
            hidden: true,
        },
    ];
    me.option1 = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
    };
    me.colModel1 = [
        {
            name: "status",
            label: " ",
            index: "status",
            width: me.ratio === 1.5 ? 10 : 30,
            align: "center",
            sortable: false,
        },
        {
            name: "audit_master",
            label: " ",
            index: "audit_master",
            width: 117,
            align: "left",
            sortable: false,
        },
        {
            name: "btnEdit1",
            label: " ",
            index: "btnEdit1",
            width: 50,
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: " ",
            index: "SYAIN_NM",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "btnEdit2",
            label: " ",
            index: "btnEdit2",
            width: 50,
            align: "left",
            sortable: false,
        },
        {
            name: "responsible_check_dt",
            label: "確認日",
            index: "responsible_check_dt",
            width: 78,
            sortable: false,
        },
        {
            name: "responsible_comment",
            label: "コメント",
            index: "responsible_comment",
            width: me.ratio === 1.5 ? 200 : 320,
            sortable: false,
        },
    ];
    //ステータス
    me.statusSelectList = [
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
        id: ".HMAUDReportInput.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDReportInput.btnEdit1",
        type: "button",
        handle: "",
    });
    //20250508 CI INS S
    me.controls.push({
        id: ".HMAUDReportInput.add",
        type: "button",
        handle: "",
    });
    //20250508 CI INS E
    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //検索ボタンクリック
    $(".HMAUDReportInput.btnSearch").click(function () {
        //20230801 lujunxia ins s
        me.lastsel = 0;
        //20230801 lujunxia ins e
        me.btnSearch_Click();
    });
    //実績入力ボタンクリック
    $(".HMAUDReportInput.btnJisseki").click(function () {
        me.btnJisseki_Click();
    });
    //20250508 CI INS S
    $(".HMAUDReportInput.add").click(function () {
        me.add_Click();
    });
    //20250508 CI INS E
    //保存ボタンクリック
    $(".HMAUDReportInput.btnSave").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnSave;
        me.clsComFnc.FncMsgBox("QY010");
    });
    //拠点
    $(".HMAUDReportInput.posSearch").change(function () {
        me.posSearch_Change();
        me.fncPnlListHide();
    });
    //実績照会へボタンクリック
    $(".HMAUDReportInput.btnShokai").click(function () {
        me.btnShokai_Click();
    });
    //履歴ボタンクリック
    $(".HMAUDReportInput.btnHistory").click(function () {
        me.openHistory();
    });
    $(".HMAUDReportInput.posSearch").on("input", function () {
        me.fncPnlListHide();
    });
    //クール
    $(".HMAUDReportInput.coursSearchInput").change(function () {
        me.fncPnlListHide();
        me.fncCourChange();
    });
    //領域
    $(".HMAUDReportInput.statusSelect").change(function () {
        me.fncPnlListHide();
    });
    //左メニューを閉じたときに明細の幅を広げて表示
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDReportInput fieldset").width(),
            );
        }, 500);
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
        //20250508 CI INS S
        $(".HMAUDReportInput.add").text("+").css("width", "30px").hide();
        $(document).ready(function () {
            let isDragging = false;
            let startY, startFieldsetHeight, startPnlListHeight;

            // マウスで仕切りバーを押し下げた時
            $(".HMAUDReportInput-resizable-handle").on(
                "mousedown",
                function (e) {
                    isDragging = true;
                    startY = e.clientY;
                    startFieldsetHeight = $(
                        ".HMAUDReportInput fieldset",
                    ).outerHeight();
                    startPnlListHeight = $(
                        ".HMAUDReportInput.pnlList",
                    ).outerHeight();
                    e.preventDefault();

                    // ドラッグ時の視覚的フィードバックを追加
                    $("body").css("cursor", "row-resize");
                    $(this).css({
                        background: "#aaa",
                        "border-color": "#666",
                    });
                },
            );

            // マウス移動時
            $(document)
                .on("mousemove", function (e) {
                    if (!isDragging) return;

                    // マウス移動距離の計算
                    const dy = e.clientY - startY;

                    // 最小高さ制限の設定
                    const minHeight = 60;
                    const handleHeight = 25;
                    const maxHeight = $(window).height() - 200 - handleHeight;

                    // 新しいfieldset高さの計算
                    let newFieldsetHeight = startFieldsetHeight + dy;
                    newFieldsetHeight = Math.max(
                        minHeight,
                        Math.min(maxHeight, newFieldsetHeight),
                    );

                    // 新しいjqgrid高さの計算
                    let newPnlListHeight = startPnlListHeight - dy;
                    newPnlListHeight = Math.max(
                        minHeight,
                        Math.min(maxHeight, newPnlListHeight),
                    );

                    $(".HMAUDReportInput fieldset").css({
                        height: newFieldsetHeight + "px",
                        // max-height制限の除去
                        "max-height": "none",
                    });
                    $(".HMAUDReportInput.pnlList").css({
                        height: newPnlListHeight + "px",
                        "min-height": minHeight + "px",
                    });

                    $(".HMAUD.HMAUD-layout-center").css("overflow", "hidden");

                    var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
                    var buttonHeight = $(
                        ".HMAUDReportInput.buttonClass",
                    ).height();
                    var fieldsetHeight = $(
                        ".HMAUDReportInput fieldset",
                    ).height();
                    var tableHeight =
                        mainHeight - buttonHeight - fieldsetHeight - 120;
                    gdmz.common.jqgrid.set_grid_height(
                        me.grid_id,
                        tableHeight,
                    );
                })
                .on("mouseup", function () {
                    isDragging = false;
                    $("body").css("cursor", "");
                    $(".HMAUDReportInput-resizable-handle").css({
                        background: "#ddd",
                        "border-color": "#aaa",
                    });
                });
        });
        //20250508 CI INS E
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
        );
        me.setTableSize();

        $(me.grid_id).jqGrid("bindKeys");
        gdmz.common.jqgrid.init(
            me.grid_id1,
            "",
            me.colModel1,
            "",
            "",
            me.option1,
        );
        // 20250403 CI UPD S
        // 20250508 CI UPD S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id1, 830);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id1,
            me.ratio === 1.5 ? 640 : 800,
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id1,
            me.ratio === 1.5 ? 200 : 230,
        );
        // 20250508 CI UPD S
        // 20250403 CI UPD E
        $(".HMAUDReportInput.pnlList").hide();
        $(".HMAUDReportInput.pnlList1").hide();
        $(".HMAUDReportInput.btnHistory").hide();
        $(".HMAUDReportInput.btnSave").hide();
        $(".HMAUDReportInput.LBL_TITLE_STD10").hide();
        //領域
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMAUDReportInput.statusSelect");
        for (var i = 0; i < me.statusSelectList.length; i++) {
            $("<option></option>")
                .val(me.statusSelectList[i].val)
                .text(me.statusSelectList[i].text)
                .appendTo(".HMAUDReportInput.statusSelect");
        }
        //拠点マスタのデータを取得
        var url = me.sys_id + "/" + me.id + "/" + "getKyotenData";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //検索
                $(".HMAUDReportInput.btnSearch").button("disable");
                //実績照会へ
                $(".HMAUDReportInput.btnShokai").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.kyotenList = result["data"]["kyoten"];
            $(".HMAUDReportInput.posSearch").find("option").remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDReportInput.posSearch");
            // 20250219 LHB INS S
            $x = 0;
            // 20250219 LHB INS E
            for (var v = 0; v < me.kyotenList.length; v++) {
                var foundNM_array = me.statusSelectList.filter(
                    function (element) {
                        return element["val"] == me.kyotenList[v]["TERRITORY"];
                    },
                );
                $("<option></option>")
                    .val(
                        me.kyotenList[v]["KYOTEN_CD"] +
                            me.kyotenList[v]["TERRITORY"],
                    )
                    .text(
                        me.kyotenList[v]["KYOTEN_NAME"] +
                            "・" +
                            foundNM_array[0]["text"],
                    )
                    .appendTo(".HMAUDReportInput.posSearch");
                // 20250219 LHB INS S
                if (me.kyotenList[v]["TERRITORY"] == "6") {
                    me.posSearch_data[$x] =
                        me.kyotenList[v]["KYOTEN_CD"] +
                        me.kyotenList[v]["TERRITORY"];
                    $x++;
                    $(
                        ".HMAUDReportInput.posSearch option[value=" +
                            me.kyotenList[v]["KYOTEN_CD"] +
                            me.kyotenList[v]["TERRITORY"] +
                            "]",
                    ).hide();
                }
                // 20250219 LHB INS E
            }
            //指摘事項NO65:クール数の欄をプルダウンにする
            $(".HMAUDReportInput.coursSearchInput").find("option").remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDReportInput.coursSearchInput");
            if (result["data"]["cour"].length > 0) {
                var courAll = result["data"]["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDReportInput.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.coursnow = courAll[i]["COURS"];
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

                me.sessionCour = gdmz.SessionCour;
                delete gdmz.SessionCour;
                me.sessionKyotenCD = gdmz.SessionKyotenCD;
                delete gdmz.SessionKyotenCD;
                me.sessionTerritory = gdmz.territory;
                delete gdmz.territory;
                //クール
                $(".HMAUDReportInput.coursSearchInput").val(me.sessionCour);
                me.fncCourChange();
                //拠点
                $(".HMAUDReportInput.posSearch").val(
                    me.sessionKyotenCD + me.sessionTerritory,
                );
                //領域
                $(".HMAUDReportInput.statusSelect").val(me.sessionTerritory);
                me.posSearch_Change();
                setTimeout(function () {
                    //データを検索
                    me.btnSearch_Click();
                }, 100);

                me.SessionPrePG = gdmz.SessionPrePG;
                me.SessionPrePG1 = gdmz.SessionPrePG;
                // if (gdmz.SessionPrePG != undefined)
                // {
                // //戻る
                // $('.HMAUDReportInput.btnReturn').show();
                // }
                delete gdmz.SessionPrePG;
            } else {
                if (result["data"]["cour"].length > 0) {
                    //検索条件・クールには 現在のクール数を初期表示
                    $(".HMAUDReportInput.coursSearchInput").val(me.coursnow);
                    me.fncCourChange();
                }
            }
            if ($(".HMAUDReportInput.coursSearchInput").val() >= 20) {
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id1,
                    me.ratio === 1.5 ? 190 : 185,
                );
            }
        };
        me.ajax.send(url, "", 0);
    };
    //20250508 CI INS S
    me.add_Click = function () {
        var cols = [
            "COLUMN1",
            "COLUMN2",
            "COLUMN3",
            "COLUMN4",
            "COLUMN5",
            "COLUMN6",
        ];
        var allHidden = cols.every(function (colName) {
            return $(me.grid_id).jqGrid("getColProp", colName).hidden;
        });

        if (allHidden) {
            cols.forEach(function (colName) {
                $(me.grid_id).jqGrid("showCol", colName);
            });
            $(".HMAUDReportInput.add").text("-").css("width", "948px");
        } else {
            cols.forEach(function (colName) {
                $(me.grid_id).jqGrid("hideCol", colName);
            });
            $(".HMAUDReportInput.add").text("+").css("width", "30px");
        }
    };
    //20250508 CI INS E
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索ボタンクリックs
    //'**********************************************************************
    me.btnSearch_Click = function () {
        //データバインド
        //20230801 lujunxia del s
        //$(me.grid_id).jqGrid("clearGridData");
        //20230801 lujunxia del s
        // $(me.grid_id1).jqGrid("clearGridData");
        if (!me.InputCheck()) {
            return;
        }
        //クール
        me.coursSearchInput = $(".HMAUDReportInput.coursSearchInput").val();
        //拠点
        me.posSearch = $(".HMAUDReportInput.posSearch").val();
        //領域
        me.statusSelect = $(".HMAUDReportInput.statusSelect").val();
        var data = {
            COURS: $.trim(me.coursSearchInput),
            KYOTEN_CD: me.posSearch.substring(0, me.posSearch.length - 1),
            TERRITORY: me.statusSelect,
        };
        var complete_fun = function (returnFLG, result) {
            // 20250512 CI INS S
            if (result && result.rows && result.rows.length > 0) {
                $(".HMAUDReportInput.add").show();
                $(".HMAUDReportInput.add").text("+").css("width", "30px");
                [
                    "COLUMN1",
                    "COLUMN2",
                    "COLUMN3",
                    "COLUMN4",
                    "COLUMN5",
                    "COLUMN6",
                ].forEach(function (colName) {
                    $(me.grid_id).jqGrid("hideCol", colName);
                });
            } else {
                $(".HMAUDReportInput.add").hide();
            }
            // 20250512 CI INS E
            if (result["error"]) {
                if (result["error"] == "W0008") {
                    me.clsComFnc.FncMsgBox("W0008", "ユーザー");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
            if (result["headdata"]["row"] == 0) {
                if (me.SessionPrePG1 !== "") {
                    me.clsComFnc.MsgBoxBtnFnc.OK = me.backtopage;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.backtopage;
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "監査情報が取得できませんでした。一覧に戻ります。",
                    );
                    return;
                } else {
                    $(".HMAUDReportInput.LBL_TITLE_STD10").hide();
                    //該当データはありません。
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                }
            }

            $(".HMAUDReportInput.LBL_TITLE_STD10").show();

            me.report_id = result["headdata"]["data"][0]["REPORT_ID"];
            me.check_id = result["headdata"]["data"][0]["CHECK_ID"];
            me.status = result["headdata"]["data"][0]["STATUS"];
            me.admin = result["admin"];
            //原始データ
            me.originalData = result["rows"];
            me.fncjqgrid();
            me.getHeaddata(
                result["headdata"]["data"],
                result["persondata"]["data"],
            );
            $("#0_btnEdit").prop("disabled", true);
            $("#1_btnEdit").prop("disabled", true);
            $("#2_btnEdit").prop("disabled", true);
            $("#3_btnEdit").prop("disabled", true);
            $("#4_btnEdit").prop("disabled", true);
            $("#5_btnEdit").prop("disabled", true);
            $("#6_btnEdit").prop("disabled", true);
            $("#7_btnEdit").prop("disabled", true);
            $("#8_btnEdit").prop("disabled", true);
            $("#9_btnEdit").prop("disabled", true);
            $("#10_btnEdit").prop("disabled", true);
            $("#11_btnEdit").prop("disabled", true);
            // 20230103 YIN INS S
            $("#12_btnEdit").prop("disabled", true);
            $("#13_btnEdit").prop("disabled", true);
            // 20230103 YIN INS E
            // 20250403 CI INS S
            $("#14_btnEdit").prop("disabled", true);
            $("#15_btnEdit").prop("disabled", true);
            // 20250403 CI INS E
            if (returnFLG !== "nodata") {
                $(".HMAUDReportInput.LBL_TITLE_STD11").html(
                    "指摘件数：" + result["records"] + "件",
                );
                $(".HMAUDReportInput.pnlList").hide();
            } else {
                $(".HMAUDReportInput.LBL_TITLE_STD11").html("指摘件数：０件");
            }
            $(me.grid_id).setColProp("POINTED", {
                editable: false,
            });
            $(me.grid_id).setColProp("IMPROVE_DETAIL", {
                editable: false,
            });
            $(me.grid_id).setColProp("IMPROVE_PLAN_DT", {
                editable: false,
            });
            $(me.grid_id).setColProp("IMPROVE_DT", {
                editable: false,
            });
            $(me.grid_id).setColProp("KEYPERSON_CHECK", {
                editable: false,
            });
            $(me.grid_id).setColProp("KEYPERSON_COMMENT", {
                editable: false,
            });
            $(".HMAUDReportInput.btnSave").hide();
            //画面制御
            me.fncEditable(result["role"], returnFLG);
        };
        //20230801 lujunxia upd s
        //gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            data,
            complete_fun,
            "",
            true,
        );
        //20230801 lujunxia upd e
        //20250513 CI INS S
        [
            "COLUMN1",
            "COLUMN2",
            "COLUMN3",
            "COLUMN4",
            "COLUMN5",
            "COLUMN6",
        ].forEach(function (colName) {
            $(me.grid_id).jqGrid("showCol", colName);
            $(".HMAUDReportInput.add").text("-").css("width", "948px");
        });
        //20250513 CI INS S
    };
    me.backtopage = function () {
        //監査実績照会
        if (me.SessionPrePG1 == "HMAUDKansaJissekiShokai") {
            //ステータス
            gdmz.SessionStatus = me.sessionStatus;
            //領域
            gdmz.SessionTerritoryArr = me.sessionTerritoryArr;
            //クール
            gdmz.SessionCourShokai = me.sessionCourShokai;
            //拠点コード
            gdmz.SessionKyotenCDShokai = me.sessionKyotenCDShokai;
        } else {
            //クール
            gdmz.SessionCour = me.sessionCour;
            //拠点コード
            gdmz.SessionKyotenCD = me.sessionKyotenCD;
            //領域
            gdmz.territory = me.sessionTerritory;
        }
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDReportInput",
        );
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#" + me.SessionPrePG1,
        );
    };
    me.getHeaddata = function (headdata, persondata) {
        $(me.grid_id1).jqGrid("clearGridData");
        me.syainlist0 = [];
        if (persondata && persondata.length > 0) {
            for (var i = 0; i < persondata.length; i++) {
                if (persondata[i]["ROLE"] == "1") {
                    me.syainlist0.push(persondata[i]);
                }
            }
        }
        if (headdata && headdata.length > 0) {
            $(me.grid_id1).jqGrid("addRowData", 0, {});
            //20240612 CI UPD S
            // if (headdata[0]["STATUS"] == "01") {
            if (
                headdata[0]["STATUS"] == "01" ||
                headdata[0]["STATUS"] == "91"
            ) {
                $(me.grid_id1).jqGrid("setCell", 0, "status", "●");
            }
            //20240612 CI UPD E
            $(me.grid_id1).jqGrid("setCell", 0, "audit_master", "監査人");
            $(me.grid_id1).jqGrid(
                "setCell",
                0,
                "btnEdit1",
                "<button onclick=\"openPage('0','0','1')\" id = '0_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                0,
                "SYAIN_NM",
                "<select class=\"HMAUDReportInput syainnm1\"  style='min-width: 95px;'/>",
            );
            $(me.grid_id1).jqGrid("setCell", 0, "btnEdit2", "- -");
            $(me.grid_id1).jqGrid(
                "setCell",
                0,
                "responsible_check_dt",
                headdata[0]["COMP_CHECK_DT"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                0,
                "responsible_comment",
                headdata[0]["COMP_COMMENT"],
            );
            // $(me.grid_id1).jqGrid("setCell", 0, "btnHistory", "<button onclick=\"openHistory('1')\" id = '1_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDReportInput.syainnm1");
            me.user = "";
            if (me.syainlist0.length != 0) {
                for (var i = 0; i < me.syainlist0.length; i++) {
                    $("<option></option>")
                        .val(me.syainlist0[i]["SYAIN_NO"])
                        .text(me.syainlist0[i]["SYAIN_NM"])
                        .appendTo(".HMAUDReportInput.syainnm1");
                    if (me.syainlist0[i]["SYAIN_NO"] == gdmz.SessionUserId) {
                        //現在のクール数
                        me.user = me.syainlist0[i]["SYAIN_NO"];
                    }
                }
                if (me.user == "") {
                    $(".HMAUDReportInput.syainnm1")
                        .find("option")
                        .eq(1)
                        .prop("selected", true);
                } else {
                    $(".HMAUDReportInput.syainnm1").val(me.user);
                }
            }
            $(me.grid_id1).jqGrid("addRowData", 1, {});
            if (headdata[0]["STATUS"] == "02") {
                $(me.grid_id1).jqGrid("setCell", 1, "status", "●");
            }
            $(me.grid_id1).jqGrid(
                "setCell",
                1,
                "audit_master",
                "改善報告書担当",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                1,
                "btnEdit1",
                "<button onclick=\"openPage('1','1','1')\" id = '1_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                1,
                "btnEdit2",
                "<button onclick=\"openPage('1','2','2')\" id = '2_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                1,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT0"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                1,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT0"],
            );
            // $(me.grid_id1).jqGrid("setCell", 1, "btnHistory", "<button onclick=\"openHistory('2')\" id = '2_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");
            $(me.grid_id1).jqGrid("addRowData", 2, {});
            //20240612 CI UPD S
            // if (headdata[0]["STATUS"] == "03" || headdata[0]["STATUS"] == "99") {
            // 20250403 CI UPD S
            if (
                headdata[0]["STATUS"] == "03" ||
                headdata[0]["STATUS"] == "94"
            ) {
                // 20250403 CI UPD E
                $(me.grid_id1).jqGrid("setCell", 2, "status", "●");
            }
            //20240612 CI UPD E
            $(me.grid_id1).jqGrid(
                "setCell",
                2,
                "audit_master",
                "改善取組責任者",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                2,
                "btnEdit1",
                "<button onclick=\"openPage('2','3','1')\" id = '3_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>提出</button>",
            );
            $(me.grid_id1).jqGrid("setCell", 2, "btnEdit2", "- -");
            $(me.grid_id1).jqGrid(
                "setCell",
                2,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT1"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                2,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT1"],
            );
            // $(me.grid_id1).jqGrid("setCell", 2, "btnHistory", "<button onclick=\"openHistory('3')\" id = '3_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");

            $(me.grid_id1).jqGrid("addRowData", 3, {});
            //20240612 CI UPD S
            // 	if (headdata[0]["STATUS"] == "04") {
            if (
                headdata[0]["STATUS"] == "04" ||
                headdata[0]["STATUS"] == "95"
            ) {
                $(me.grid_id1).jqGrid("setCell", 3, "status", "●");
            }
            //20240612 CI UPD E
            $(me.grid_id1).jqGrid("setCell", 3, "audit_master", "各領域責任者");
            $(me.grid_id1).jqGrid(
                "setCell",
                3,
                "btnEdit1",
                "<button onclick=\"openPage('3','4','1')\" id = '4_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                3,
                "btnEdit2",
                "<button onclick=\"openPage('3','5','0')\" id = '5_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                3,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT2"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                3,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT2"],
            );
            // $(me.grid_id1).jqGrid("setCell", 3, "btnHistory", "<button onclick=\"openHistory('4')\" id = '4_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");

            $(me.grid_id1).jqGrid("addRowData", 4, {});
            //20240612 CI UPD S
            //if (headdata[0]["STATUS"] == "05") {
            if (
                headdata[0]["STATUS"] == "05" ||
                headdata[0]["STATUS"] == "96"
            ) {
                $(me.grid_id1).jqGrid("setCell", 4, "status", "●");
            }
            //20240612 CI UPD E
            $(me.grid_id1).jqGrid("setCell", 4, "audit_master", "キーマン");
            $(me.grid_id1).jqGrid(
                "setCell",
                4,
                "btnEdit1",
                "<button onclick=\"openPage('4','6','1')\" id = '6_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                4,
                "btnEdit2",
                "<button onclick=\"openPage('4','7','0')\" id = '7_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                4,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT3"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                4,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT3"],
            );
            // $(me.grid_id1).jqGrid("setCell", 4, "btnHistory", "<button onclick=\"openHistory('5')\" id = '5_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");
            $(me.grid_id1).jqGrid("addRowData", 5, {});
            //20240612 CI UPD S
            //if (headdata[0]["STATUS"] == "06") {
            if (
                headdata[0]["STATUS"] == "06" ||
                headdata[0]["STATUS"] == "97"
            ) {
                $(me.grid_id1).jqGrid("setCell", 5, "status", "●");
            }
            //20240612 CI UPD E
            $(me.grid_id1).jqGrid("setCell", 5, "audit_master", "総括責任者");
            $(me.grid_id1).jqGrid(
                "setCell",
                5,
                "btnEdit1",
                "<button onclick=\"openPage('5','8','1')\" id = '8_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;disabled='disabled''>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                5,
                "btnEdit2",
                "<button onclick=\"openPage('5','9','0')\" id = '9_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;disabled='disabled''>差戻</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                5,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT4"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                5,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT4"],
            );
            // $(me.grid_id1).jqGrid("setCell", 5, "btnHistory", "<button onclick=\"openHistory('6')\" id = '6_btnHistory' class=\"HMAUDReportInput btnHistory\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>履歴</button>");
            // 20230103 YIN INS S
            // 20251016 YIN UPD S
            if ($(".HMAUDReportInput.coursSearchInput").val() < 19) {
                $(me.grid_id1).jqGrid("addRowData", 6, {});
                //20240612 CI UPD S
                //if (headdata[0]["STATUS"] == "07") {
                if (
                    headdata[0]["STATUS"] == "07" ||
                    headdata[0]["STATUS"] == "98"
                ) {
                    $(me.grid_id1).jqGrid("setCell", 6, "status", "●");
                }
                //20240612 CI UPD E
                //20240313 caina upd s
                // $(me.grid_id1).jqGrid("setCell", 6, "audit_master", "常務");
                $(me.grid_id1).jqGrid("setCell", 6, "audit_master", "取締役");
                //20240313 caina upd e
                $(me.grid_id1).jqGrid(
                    "setCell",
                    6,
                    "btnEdit1",
                    "<button onclick=\"openPage('6','10','1')\" id = '10_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    6,
                    "btnEdit2",
                    "<button onclick=\"openPage('6','11','0')\" id = '11_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    6,
                    "responsible_check_dt",
                    headdata[0]["RESPONSIBLE_CHECK_DT5"],
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    6,
                    "responsible_comment",
                    headdata[0]["RESPONSIBLE_COMMENT5"],
                );
            }
            // 20251016 YIN UPD E
            // 20230103 YIN INS E
            // 20230103 YIN UPD S
            // $(me.grid_id1).jqGrid('addRowData', 6,
            // {
            // });
            // if (headdata[0]["STATUS"] == "07")
            // {
            // $(me.grid_id1).jqGrid("setCell", 6, "status", '●');
            // }
            // $(me.grid_id1).jqGrid("setCell", 6, "audit_master", '社長');
            // $(me.grid_id1).jqGrid("setCell", 6, "btnEdit1", "<button onclick=\"openPage('6','10')\" id = '10_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>");
            // $(me.grid_id1).jqGrid("setCell", 6, "btnEdit2", "<button onclick=\"openPage('6','11')\" id = '11_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>");
            // $(me.grid_id1).jqGrid("setCell", 6, "responsible_check_dt", headdata[0]["RESPONSIBLE_CHECK_DT5"]);
            // $(me.grid_id1).jqGrid("setCell", 6, "responsible_comment", headdata[0]["RESPONSIBLE_COMMENT5"]);
            // 20250403 CI UPD S
            $(me.grid_id1).jqGrid("addRowData", 7, {});
            // 20251016 YIN UPD S
            if (
                ($(".HMAUDReportInput.coursSearchInput").val() < 19 &&
                    headdata[0]["STATUS"] == "08") ||
                ($(".HMAUDReportInput.coursSearchInput").val() > 18 &&
                    headdata[0]["STATUS"] == "07") ||
                headdata[0]["STATUS"] == "99"
            ) {
                $(me.grid_id1).jqGrid("setCell", 7, "status", "●");
            }
            // 20251016 YIN UPD E
            $(me.grid_id1).jqGrid("setCell", 7, "audit_master", "社長");
            $(me.grid_id1).jqGrid(
                "setCell",
                7,
                "btnEdit1",
                "<button onclick=\"openPage('7','12','1')\" id = '12_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                7,
                "btnEdit2",
                "<button onclick=\"openPage('7','13','0')\" id = '13_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                7,
                "responsible_check_dt",
                headdata[0]["RESPONSIBLE_CHECK_DT6"],
            );
            $(me.grid_id1).jqGrid(
                "setCell",
                7,
                "responsible_comment",
                headdata[0]["RESPONSIBLE_COMMENT6"],
            );

            if ($(".HMAUDReportInput.coursSearchInput").val() < 20) {
                $(me.grid_id1).jqGrid("addRowData", 8, {});
                if (headdata[0]["STATUS"] == "09") {
                    $(me.grid_id1).jqGrid("setCell", 8, "status", "●");
                }
                $(me.grid_id1).jqGrid("setCell", 8, "audit_master", "社長");
                $(me.grid_id1).jqGrid(
                    "setCell",
                    8,
                    "btnEdit1",
                    "<button onclick=\"openPage('8','14','1')\" id = '14_btnEdit' class=\"HMAUDReportInput btnEdit1\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>確認</button>",
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    8,
                    "btnEdit2",
                    "<button onclick=\"openPage('8','15','0')\" id = '15_btnEdit' class=\"HMAUDReportInput btnEdit2\" style='border: 1px solid #77d5f7;background: #16b1e9;min-width: 50px;'>差戻</button>",
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    8,
                    "responsible_check_dt",
                    headdata[0]["RESPONSIBLE_CHECK_DT7"],
                );
                $(me.grid_id1).jqGrid(
                    "setCell",
                    8,
                    "responsible_comment",
                    headdata[0]["RESPONSIBLE_COMMENT7"],
                );
            }

            // 20250403 CI UPD E
            // 20230103 YIN UPD E

            if (persondata && persondata.length > 0) {
                for (var i = 0; i < persondata.length; i++) {
                    if (persondata[i]["ROLE"] != "1") {
                        $(me.grid_id1).jqGrid(
                            "setCell",
                            persondata[i]["ROLE"] - 1,
                            "SYAIN_NM",
                            persondata[i]["SYAIN_NM"],
                        );
                    }
                }
            }
        }
    };
    openPage = function (rowId, flag, pageflag) {
        //監査人確認をクリック
        if (rowId == "0" && flag == "0") {
            $(me.grid_id).jqGrid("saveRow", me.lastsel);
            var ids = $(me.grid_id).getDataIDs();
            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (
                    rowdata["POINTED"] !=
                        me.originalData[ids[i]]["cell"]["POINTED"] &&
                    !(
                        rowdata["POINTED"] == "" &&
                        me.originalData[ids[i]]["cell"]["POINTED"] == null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_POINTED",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "具体的な指摘事項が変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
            }
            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (rowdata["POINTED"] == "") {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_POINTED",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "具体的な指摘事項を入力してください。",
                    );
                    return false;
                }
            }
        }
        //改善取組責任者提出をクリック
        if (rowId == "2" && flag == "3") {
            $(me.grid_id).jqGrid("saveRow", me.lastsel);
            var ids = $(me.grid_id).getDataIDs();
            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (
                    rowdata["IMPROVE_DETAIL"] !=
                        me.originalData[ids[i]]["cell"]["IMPROVE_DETAIL"] &&
                    !(
                        rowdata["IMPROVE_DETAIL"] == "" &&
                        me.originalData[ids[i]]["cell"]["IMPROVE_DETAIL"] ==
                            null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_IMPROVE_DETAIL",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善取組が変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
                if (
                    rowdata["IMPROVE_PLAN_DT"] !=
                        me.originalData[ids[i]]["cell"]["IMPROVE_PLAN_DT"] &&
                    !(
                        rowdata["IMPROVE_PLAN_DT"] == "" &&
                        me.originalData[ids[i]]["cell"]["IMPROVE_PLAN_DT"] ==
                            null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_IMPROVE_PLAN_DT",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善予定日が変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
                if (
                    rowdata["IMPROVE_DT"] !=
                        me.originalData[ids[i]]["cell"]["IMPROVE_DT"] &&
                    !(
                        rowdata["IMPROVE_DT"] == "" &&
                        me.originalData[ids[i]]["cell"]["IMPROVE_DT"] == null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_IMPROVE_DT",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善日が変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
            }

            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (rowdata["IMPROVE_DETAIL"] == "") {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_IMPROVE_DETAIL",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善取組を入力してください。",
                    );
                    return false;
                }
                // if (rowdata['IMPROVE_PLAN_DT'] == "")
                // {
                // $(me.grid_id).jqGrid('setSelection', ids[i], true);
                // me.clsComFnc.ObjFocus = $(me.grid_id + " #" + ids[i] + "_IMPROVE_PLAN_DT");
                // me.clsComFnc.FncMsgBox("W9999", "改善予定日を入力してください。");
                // return false;
                // }
                // if (rowdata['IMPROVE_DT'] == "")
                // {
                // $(me.grid_id).jqGrid('setSelection', ids[i], true);
                // me.clsComFnc.ObjFocus = $(me.grid_id + " #" + ids[i] + "_IMPROVE_DT");
                // me.clsComFnc.FncMsgBox("W9999", "改善日を入力してください。");
                // return false;
                // }
            }
        }
        //キーマン確認をクリック
        if (rowId == "4" && flag == "6") {
            $(me.grid_id).jqGrid("saveRow", me.lastsel);
            var ids = $(me.grid_id).getDataIDs();
            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (
                    rowdata["KEYPERSON_CHECK"] !=
                        me.originalData[ids[i]]["cell"]["KEYPERSON_CHECK"] &&
                    !(
                        rowdata["KEYPERSON_CHECK"] == "" &&
                        me.originalData[ids[i]]["cell"]["KEYPERSON_CHECK"] ==
                            null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_KEYPERSON_CHECK",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "キーマン確認が変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
                if (
                    rowdata["KEYPERSON_COMMENT"] !=
                        me.originalData[ids[i]]["cell"]["KEYPERSON_COMMENT"] &&
                    !(
                        rowdata["KEYPERSON_COMMENT"] == "" &&
                        me.originalData[ids[i]]["cell"]["KEYPERSON_COMMENT"] ==
                            null
                    )
                ) {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_KEYPERSON_COMMENT",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "キーマンコメントが変更されたため、保存ボタンをクリックしてください。",
                    );
                    return false;
                }
            }

            for (var i = 0; i < ids.length; i++) {
                var rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
                if (rowdata["KEYPERSON_CHECK"] == "") {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_KEYPERSON_CHECK",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "キーマン確認を入力してください。",
                    );
                    return false;
                }
                if (rowdata["KEYPERSON_CHECK"] == "2") {
                    $(me.grid_id).jqGrid("setSelection", ids[i], true);
                    me.clsComFnc.ObjFocus = $(
                        me.grid_id + " #" + ids[i] + "_KEYPERSON_CHECK",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "キーマン確認”に「×」が存在しているため、承認できません。ご確認ください。",
                    );
                    return false;
                }
                // if (rowdata['KEYPERSON_COMMENT'] == "")
                // {
                // $(me.grid_id).jqGrid('setSelection', ids[i], true);
                // me.clsComFnc.ObjFocus = $(me.grid_id + " #" + ids[i] + "_KEYPERSON_COMMENT");
                // me.clsComFnc.FncMsgBox("W9999", "キーマンコメントを入力してください。");
                // return false;
                // }
            }
        }
        var frmId = "HMAUDReportInputedit";
        var dialogdiv = "HMAUDReportInputDialogDiv";

        var $rootDiv = $(".HMAUDReportInput.HMAUD-content");

        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "RtnCD")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "userid")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "flag")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "check_id")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "report_id")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "lblComment")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "territory")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "kyoten")
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "skip")
            .insertAfter($rootDiv);
        // 20240530 YIN INS S
        $("<div style='display:none;'></div>")
            .prop("id", "cour")
            .insertAfter($rootDiv);
        // 20240530 YIN INS E
        // 20240613 CI INS S
        $("<div style='display:none;'></div>")
            .prop("id", "pageflag")
            .insertAfter($rootDiv);
        // 20240613 CI INS E
        var gridDatas = $(me.grid_id).jqGrid("getRowData");
        var $skip = $rootDiv.parent().find("#skip");
        if (flag == "1" && gridDatas.length == 0) {
            $skip.html("1");
        } else {
            $skip.html("0");
        }
        var $userid = $rootDiv.parent().find("#userid");
        var rowData = $(me.grid_id1).jqGrid("getRowData", rowId);
        $userid.html(rowData["responsible_comment"]);
        var $flag = $rootDiv.parent().find("#flag");
        $flag.html(flag);
        var $check_id = $rootDiv.parent().find("#check_id");
        $check_id.html(me.check_id);
        var $report_id = $rootDiv.parent().find("#report_id");
        $report_id.html(me.report_id);
        var $territory = $rootDiv.parent().find("#territory");
        $territory.html(
            $(".HMAUDReportInput.statusSelect").find("option:selected").text(),
        );
        var $kyoten = $rootDiv.parent().find("#kyoten");
        $kyoten.html(
            $(".HMAUDReportInput.posSearch").find("option:selected").text(),
        );
        // 20240530 YIN INS S
        var $cour = $rootDiv.parent().find("#cour");
        $cour.html($(".HMAUDReportInput.coursSearchInput").val());
        // 20240530 YIN INS E
        // 20240613 CI INS S
        var $pageflag = $rootDiv.parent().find("#pageflag");
        $pageflag.html(pageflag);
        // 20240613 CI INS E
        // var $txtComment = $rootDiv.parent().find("#lblComment");
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            function before_close() {
                me.btnSearch_Click();
                // $(me.grid_id1).jqGrid("setCell", rowId, 'responsible_comment', $txtComment.html());
                var $RtnCD = $rootDiv.parent().find("#RtnCD");
                $RtnCD.remove();
                $userid.remove();
                $flag.remove();
                $check_id.remove();
                $report_id.remove();
                $territory.remove();
                $kyoten.remove();
                // 20240530 YIN INS S
                $cour.remove();
                // 20240530 YIN INS E
                // 20240613 CI INS S
                $pageflag.remove();
                // 20240613 CI INS E
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            o_HMAUD_HMAUD.HMAUDReportInput.HMAUDReportInputedit.before_close =
                before_close;
        };
    };
    me.openHistory = function () {
        var frmId = "HMAUDReportInputHistory";
        var dialogdiv = "HMAUDReportInputHistoryDialogDiv";
        var $rootDiv = $(".HMAUDReportInput.HMAUD-content");

        $("<div style='display:none;'></div>")
            .prop("id", dialogdiv)
            .insertAfter($rootDiv);
        $("<div style='display:none;'></div>")
            .prop("id", "checkid")
            .insertAfter($rootDiv);
        // ($("<div style='display:none;'></div>").prop("id", "role")).insertAfter($rootDiv);

        var $checkid = $rootDiv.parent().find("#checkid");
        // var $role = $rootDiv.parent().find("#role");
        $checkid.html(me.check_id);
        // $role.html(role);

        var url = me.sys_id + "/" + frmId;
        me.ajax.receive = function (result) {
            function before_close() {
                $checkid.remove();
                // $role.remove();
                $("#" + dialogdiv).remove();
            }
            $("#" + dialogdiv).append(result);
            o_HMAUD_HMAUD.HMAUDReportInput.HMAUDReportInputHistory.before_close =
                before_close;
        };
        me.ajax.send(url, "", 0);
    };
    me.fncjqgrid = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowid, _status, e) {
                var focusIndex =
                    typeof e != "undefined"
                        ? e.target.cellIndex !== undefined
                            ? e.target.cellIndex
                            : e.target.parentElement.cellIndex
                        : false;

                var editableArr = $(me.grid_id).find("td .editable");
                if (editableArr.length > 0) {
                    //get [cellIndex] of editable cell
                    var editableIndexArr = [];
                    for (var i = 0; i < editableArr.length; i++) {
                        editableIndexArr.push(
                            editableArr[i].parentElement.cellIndex,
                        );
                    }
                    if (
                        e.target.cellIndex !== undefined &&
                        editableIndexArr.indexOf(e.target.cellIndex) === -1
                    ) {
                        //when click other [td],the first [cell] focus
                        focusIndex = true;
                    }
                }
                $(me.grid_id).jqGrid("saveRow", me.lastsel);
                if (typeof e != "undefined") {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                } else {
                    if (rowid && rowid != me.lastsel) {
                        me.lastsel = rowid;
                    }
                }
                $(me.grid_id).jqGrid("editRow", rowid, {
                    keys: false,
                    focusField: focusIndex,
                });

                $(".numeric").numeric({
                    decimal: false,
                    negative: false,
                });

                //键盘事件
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    me.lastsel,
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                $("#" + rowid + "_POINTED").css("height", "auto");
                $("#" + rowid + "_POINTED").css(
                    "height",
                    $("#" + rowid + "_POINTED").prop("scrollHeight") + "px",
                );
                $("#" + rowid + "_IMPROVE_DETAIL").css("height", "auto");
                $("#" + rowid + "_IMPROVE_DETAIL").css(
                    "height",
                    $("#" + rowid + "_IMPROVE_DETAIL").prop("scrollHeight") +
                        "px",
                );
                $("#" + rowid + "_KEYPERSON_COMMENT").css("height", "auto");
                $("#" + rowid + "_KEYPERSON_COMMENT").css(
                    "height",
                    $("#" + rowid + "_KEYPERSON_COMMENT").prop("scrollHeight") +
                        "px",
                );

                $(me.grid_id).find(".width").css("width", "91%");
                $(me.grid_id).find(".overflow").css("overflow", "hidden");
            },
        });
    };
    me.fncEditable = function (role, returnFLG) {
        // 20240412 CI DEL S
        // if (
        // 	!(
        // 		parseInt($(".HMAUDReportInput.coursSearchInput").val()) <
        // 		parseInt(me.coursnow) && me.admin == "0"
        // 	)
        // ) {
        // 20240412 CI DEL E
        // 監査マスタ．監査員＝ログインユーザID　AND  ステータス＝00、01、99の場合インユーザIDの場合,具体的な指摘事項を編集可能にする
        //明細・具体的な指摘事項 を編集可能にする
        //20240613 CI UPD S
        // if (
        // 	role.indexOf("1") > -1 &&
        // 	(me.status == "00" || me.status == "01" || me.status == "99")
        // ) {
        if (
            role.indexOf("1") > -1 &&
            (me.status == "00" ||
                me.status == "01" ||
                //20240618 CI UPD S
                // me.status == "99" ||
                //20240618 CI UPD E
                me.status == "91")
        ) {
            $(me.grid_id).setColProp("POINTED", {
                editable: true,
                edittype: "textarea",

                editoptions: {
                    class: "overflow",
                    dataEvents: [
                        {
                            type: "keyup",
                            fn: function (e) {
                                me.autoHeight(e.target);
                            },
                        },
                    ],
                },
            });
            if (me.status !== "00") {
                $("#0_btnEdit").prop("disabled", false);
            }

            $(".HMAUDReportInput.btnSave").show();
        }
        //20240613 CI UPD E
        if (role.indexOf("2") > -1 && me.status == "02") {
            $("#1_btnEdit").prop("disabled", false);
            $("#2_btnEdit").prop("disabled", false);
        }

        //　監査マスタ．改善取組責任者＝ログインユーザID　　AND  ステータス＝03，99の場合
        //20240613 CI UPD S
        // if (role.indexOf("3") > -1 && (me.status == "03" || me.status == "99")) {
        // 20250403 CI UPD S
        if (
            role.indexOf("3") > -1 &&
            (me.status == "03" || me.status == "94")
        ) {
            // 20250403 CI UPD E
            $("#3_btnEdit").prop("disabled", false);
            $(me.grid_id).setColProp("IMPROVE_DETAIL", {
                editable: true,
                edittype: "textarea",

                editoptions: {
                    class: "overflow",
                    dataEvents: [
                        {
                            type: "keyup",
                            fn: function (e) {
                                me.autoHeight(e.target);
                            },
                        },
                    ],
                },
            });
            $(me.grid_id).setColProp("IMPROVE_PLAN_DT", {
                editable: true,
                editoptions: {
                    class: "width",
                    dataInit: function (elem) {
                        $(elem).datepicker({
                            changeYear: true,
                            changeMonth: true,
                            showButtonPanel: true,
                            onSelect: function () {
                                $(this).trigger("change");
                            },
                        });
                    },
                },
            });
            $(me.grid_id).setColProp("IMPROVE_DT", {
                editable: true,
                editoptions: {
                    class: "width",
                    dataInit: function (elem) {
                        $(elem).datepicker({
                            changeYear: true,
                            changeMonth: true,
                            showButtonPanel: true,
                            onSelect: function () {
                                $(this).trigger("change");
                            },
                        });
                    },
                },
            });
            $(".HMAUDReportInput.btnSave").show();
        }
        //20240613 CI UPD E
        // 監査マスタ．領域責任者＝ログインユーザID　AND  ステータス＝04の場合
        // 領域責任者・確認、差戻ボタンを使用可能
        //20240613 CI UPD S
        // if (role.indexOf("4") > -1 && me.status == "04") {
        if (
            role.indexOf("4") > -1 &&
            (me.status == "04" || me.status == "95")
        ) {
            $("#4_btnEdit").prop("disabled", false);
            $("#5_btnEdit").prop("disabled", false);
        }
        //20240613 CI UPD E
        // 監査マスタ．キーマン＝ログインユーザID　AND  ステータス＝05の場合
        // キーマン・確認・確認、差戻ボタンを使用可能
        //20240613 CI UPD S
        // if (role.indexOf("5") > -1 && me.status == "05") {
        if (
            role.indexOf("5") > -1 &&
            (me.status == "05" || me.status == "96")
        ) {
            $("#6_btnEdit").prop("disabled", false);
            $("#7_btnEdit").prop("disabled", false);
            $(me.grid_id).setColProp("KEYPERSON_CHECK", {
                formatter: "select",
                editable: true,
                edittype: "select",
                editoptions: {
                    dataInit: function (elem) {
                        $(elem).css("width", "100%");
                    },
                    dataEvents: [
                        //enterイベント
                        {
                            type: "change",
                            fn: function (e) {
                                if (e.target.value != 0) {
                                    $(
                                        "#" + me.lastsel + "_KEYPERSON_COMMENT",
                                    ).select();
                                }
                            },
                        },
                    ],
                    value: {
                        0: "",
                        1: "〇",
                        2: "×",
                    },
                },
            });
            $(me.grid_id).setColProp("KEYPERSON_COMMENT", {
                editable: true,
                edittype: "textarea",
                editoptions: {
                    class: "overflow",
                    dataEvents: [
                        {
                            type: "keyup",
                            fn: function (e) {
                                me.autoHeight(e.target);
                            },
                        },
                    ],
                },
            });
            $(".HMAUDReportInput.btnSave").show();
        }
        //20240613 CI UPD E
        //監査マスタ．総括責任者＝ログインユーザID　AND  ステータス＝06の場合
        //20240613 CI UPD S
        // if (role.indexOf("6") > -1 && me.status == "06") {
        if (
            role.indexOf("6") > -1 &&
            (me.status == "06" || me.status == "97")
        ) {
            $("#8_btnEdit").prop("disabled", false);
            $("#9_btnEdit").prop("disabled", false);
        }
        //20240613 CI UPD E
        // 20230103 YIN INS S
        //監査マスタ．常務責任者＝ログインユーザID　AND  ステータス＝07の場合
        //20240613 CI UPD S
        // if (role.indexOf("7") > -1 && me.status == "07") {
        if (
            role.indexOf("7") > -1 &&
            (me.status == "07" || me.status == "98")
        ) {
            $("#10_btnEdit").prop("disabled", false);
            $("#11_btnEdit").prop("disabled", false);
        }
        //20240613 CI UPD E
        // 20230103 YIN INS E
        //監査マスタ．社長責任者＝ログインユーザID　AND  ステータス＝08、09の場合
        // 20230103 YIN UPD S
        // if ((role.indexOf("7") > -1) && ((me.status == "07") || (me.status == "08")))
        // {
        // $("#10_btnEdit").prop('disabled', false);
        // $("#11_btnEdit").prop('disabled', false);
        // }
        // 20240412 CI UPD E
        //if (role.indexOf("8") > -1 && (me.status == "08" || me.status == "09")) {
        // 20240412 CI UPD S
        // 20250403 CI UPD S
        if (
            role.indexOf("8") > -1 &&
            (me.status == "08" || me.status == "99")
        ) {
            // 20250403 CI UPD E
            $("#12_btnEdit").prop("disabled", false);
            $("#13_btnEdit").prop("disabled", false);
        }
        // 20230103 YIN UPD E
        // 20251016 YIN INS S
        if (
            $(".HMAUDReportInput.coursSearchInput").val() > 18 &&
            role.indexOf("8") > -1 &&
            me.status == "07"
        ) {
            // 20250403 CI UPD E
            $("#12_btnEdit").prop("disabled", false);
            $("#13_btnEdit").prop("disabled", false);
        }
        // 20251016 YIN INS E
        // 20240412 CI DEL S
        // }
        // 20240412 CI DEL E
        // 20250403 CI INS S
        if (role.indexOf("9") > -1 && me.status == "09") {
            $("#14_btnEdit").prop("disabled", false);
            $("#15_btnEdit").prop("disabled", false);
        }
        // 20250403 CI INS E
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var index = 0; index < ids.length; index++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", ids[index]);
            // 20241030 LHB upd s
            // if (rowData["ROW_NO1"] == 2) {
            //     $(me.grid_id).setCell(ids[index], "ROW_NO1", "2回目", {
            //         background: "#FFFF00",
            //     });
            // } else if (rowData["ROW_NO1"] > 2) {
            //     $(me.grid_id).setCell(ids[index], "ROW_NO1", "3回目", {
            //         background: "#FF0000",
            //     });
            // }
            if (rowData["ROW_NO2"] == 2) {
                $(me.grid_id).setCell(ids[index], "ROW_NO2", "2回目", {
                    background: "#FFFF00",
                });
            } else if (rowData["ROW_NO2"] > 2) {
                $(me.grid_id).setCell(
                    ids[index],
                    "ROW_NO2",
                    rowData["ROW_NO2"] + "回目",
                    {
                        background: "#FF0000",
                    },
                );
            }
            // 20241030 LHB upd e
            // else
            // {
            // $(me.grid_id).setCell(ids[index], 'ROW_NO1', '');
            // }
        }
        if (returnFLG == "nodata") {
            $(".HMAUDReportInput.pnlList").hide();
            $(".HMAUDReportInput.btnSave").hide();
        } else {
            $(".HMAUDReportInput.pnlList").show();
        }

        $(".HMAUDReportInput.pnlList1").show();
        $(".HMAUDReportInput.btnHistory").show();
        //20230801 lujunxia upd s
        //$(me.grid_id).jqGrid("setSelection", 0);
        //選択行を保持しておく
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
        //20230801 lujunxia upd e
    };
    me.btnSave = function () {
        if (!me.Inputcheck1()) {
            return;
        }
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var url = me.sys_id + "/" + me.id + "/" + "btnSave";
        var data = {
            CHECK_ID: me.check_id,
            rowdata: $(me.grid_id).jqGrid("getRowData"),
            COURS: $.trim(me.coursSearchInput),
            KYOTEN_CD: me.posSearch.substring(0, me.posSearch.length - 1),
            TERRITORY: me.statusSelect,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                };
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "他ユーザーによってデータが更新されています。再読込してください",
                    );
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
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
    //'処 理 名：实际入力へボタンクリック
    //'関 数 名：btnReport_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：实际入力へボタンクリック
    //'**********************************************************************
    me.btnJisseki_Click = function () {
        //クール
        var coursSearchInput = $(".HMAUDReportInput.coursSearchInput").val();
        //拠点コード
        var posSearch = $(".HMAUDReportInput.posSearch").val();
        //領域
        var statusSelect = $(".HMAUDReportInput.statusSelect").val();
        if (coursSearchInput == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.coursSearchInput");
            //クールを入力して下さい！
            me.clsComFnc.FncMsgBox("W0017", "クール");
            return;
        }
        if (posSearch == "" || posSearch == null) {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.posSearch");
            //拠点を入力して下さい！
            me.clsComFnc.FncMsgBox("W9999", "拠点を選択して下さい！");
            return;
        }
        if (statusSelect == "" || statusSelect == null) {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.statusSelect");
            //領域を入力して下さい！
            me.clsComFnc.FncMsgBox("W9999", "領域を選択して下さい！");
            return;
        }
        //クール
        gdmz.SessionCour = $(".HMAUDReportInput.coursSearchInput").val();
        //拠点コード
        gdmz.SessionKyotenCD = posSearch.substring(0, posSearch.length - 1);
        //領域
        gdmz.territory = $(".HMAUDReportInput.statusSelect").val();
        gdmz.SessionPrePG = "HMAUDReportInput";
        //实际入力入力画面に遷移
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDReportInput",
        );
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#HMAUDKansaJissekiInput",
        );
    };
    me.InputCheck = function () {
        //クール
        var coursSearchInput = $(".HMAUDReportInput.coursSearchInput").val();
        //拠点
        var posSearchInput = $(".HMAUDReportInput.posSearch").val();
        //領域
        var territorySelect = $(".HMAUDReportInput.statusSelect").val();
        if ($.trim(coursSearchInput) == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.coursSearchInput");
            //クールを入力して下さい！
            me.clsComFnc.FncMsgBox("W0017", "クール");
            return false;
        }
        if ($.trim(posSearchInput) == "" || posSearchInput == null) {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.posSearch");
            //拠点を選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "拠点を選択して下さい！");
            return false;
        }
        if (territorySelect == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDReportInput.statusSelect");
            //領域を選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "領域を選択して下さい！");
            return false;
        }
        return true;
    };
    me.Inputcheck1 = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var rows = $(me.grid_id).jqGrid("getDataIDs");

        for (index in rows) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rows[index]);
            if (rowData["IMPROVE_PLAN_DT"] !== "") {
                var patrn =
                    /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
                if (!patrn.test($.trim(rowData["IMPROVE_PLAN_DT"]))) {
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_IMPROVE_PLAN_DT",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善予定日「YYYY/MM/DD」書式のようにご入力ください。",
                    );
                    return false;
                }
            }
            if (rowData["IMPROVE_DT"] !== "") {
                var patrn =
                    /^[1-9]\d{3}(-|\/)(0[1-9]|1[0-2])(-|\/)(0[1-9]|[1-2][0-9]|3[0-1])$/;
                if (!patrn.test($.trim(rowData["IMPROVE_DT"]))) {
                    $(me.grid_id).jqGrid("setSelection", rows[index], true);
                    me.clsComFnc.ObjFocus = $(
                        "#" + rows[index] + "_IMPROVE_DT",
                    );
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "改善日「YYYY/MM/DD」書式のようにご入力ください。",
                    );
                    return false;
                }
            }
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：拠点blur
    //'関 数 名：posSearch_Change
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：拠点blur
    //'**********************************************************************
    me.posSearch_Change = function () {
        var skyotenVal = $(".HMAUDReportInput.posSearch").val();

        var foundNM = undefined;
        if (me.kyotenList) {
            var foundNM_array = me.kyotenList.filter(function (element) {
                return (
                    element["KYOTEN_CD"] + element["TERRITORY"] == skyotenVal
                );
            });
            if (foundNM_array.length == 0 && skyotenVal !== "") {
                me.clsComFnc.ObjFocus = $(".HMAUDReportInput.posSearch");
                //該当する拠点コードは登録されていません！
                me.clsComFnc.FncMsgBox("W0007", "拠点");
            } else {
                foundNM = foundNM_array[0];
            }
        }
        //領域
        $(".HMAUDReportInput.statusSelect").val(
            foundNM ? foundNM["TERRITORY"] : "",
        );
    };
    // **********************************************************************
    // 処 理 名： 監査実績照会ボタンクリック
    // 関 数 名：btnShokai_Click
    // 戻 り 値：なし
    // 処理説明： 監査実績照会ペッジを遷移
    // **********************************************************************
    me.btnShokai_Click = function () {
        //監査実績照会へ戻る
        if (me.SessionPrePG == "HMAUDKansaJissekiShokai") {
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
                ".HMAUDReportInput.statusSelect",
            ).val();
            //クール
            gdmz.SessionCourShokai = $(
                ".HMAUDReportInput.coursSearchInput",
            ).val();
            //拠点コード
            gdmz.SessionKyotenCDShokai = $(
                ".HMAUDReportInput.posSearch",
            ).val();
        }
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDReportInput",
        );
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#HMAUDKansaJissekiShokai",
        );
    };
    me.fncCourChange = function () {
        var cour = $(".HMAUDReportInput.coursSearchInput").val();
        var foundDT = undefined;
        // 20250219 LHB INS S
        if (parseInt(cour) >= 18) {
            $('.HMAUDReportInput.statusSelect option[value="6"]').show();
        } else {
            $('.HMAUDReportInput.statusSelect option[value="6"]').hide();
            var posSearchVal = $(".HMAUDReportInput.posSearch").val();
            if (posSearchVal != "" && posSearchVal.substring(3) == "6") {
                $(".HMAUDReportInput.posSearch").val("");
                $(".HMAUDReportInput.statusSelect").val("");
            }
        }
        if (me.posSearch_data.length > 0) {
            for (let index = 0; index < me.posSearch_data.length; index++) {
                if (parseInt(cour) >= 18) {
                    $(
                        ".HMAUDReportInput.posSearch option[value=" +
                            me.posSearch_data[index] +
                            "]",
                    ).show();
                } else {
                    $(
                        ".HMAUDReportInput.posSearch option[value=" +
                            me.posSearch_data[index] +
                            "]",
                    ).hide();
                }
            }
        }
        // 20250219 LHB INS E
        if (cour) {
            if (me.allCourData) {
                var foundDT_array = me.allCourData.filter(function (element) {
                    return element["COURS"] == cour;
                });
                if (foundDT_array.length > 0 && cour !== "") {
                    foundDT = foundDT_array[0];
                    $(".HMAUDReportInput.courPeriod").text(
                        foundDT ? foundDT["PERIOD"] : "",
                    );
                }
            }
        } else {
            $(".HMAUDReportInput.courPeriod").text("");
        }
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDReportInput fieldset").width(),
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDReportInput.buttonClass").height();
        var fieldsetHeight = $(".HMAUDReportInput fieldset").height();
        // 20250512 CI UPD S
        // var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 145;
        var tableHeight =
            mainHeight -
            buttonHeight -
            fieldsetHeight -
            (me.ratio === 1.5 ? 170 : 195);
        // 20250512 CI UPD E
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 130;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    me.fncPnlListHide = function () {
        $(".HMAUDReportInput.add").hide();
        $(".HMAUDReportInput.pnlList").hide();
        $(".HMAUDReportInput.btnSave").hide();
        $(".HMAUDReportInput.pnlList1").hide();
        $(".HMAUDReportInput.btnHistory").hide();
        $(".HMAUDReportInput.LBL_TITLE_STD10").hide();
        me.SessionPrePG1 = "";
    };
    me.autoHeight = function (elem) {
        elem.style.height = "auto";
        elem.scrollTop = 0;
        //防抖动
        elem.style.height = elem.scrollHeight + "px";
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDReportInput = new HMAUD.HMAUDReportInput();
    o_HMAUD_HMAUDReportInput.load();
    o_HMAUD_HMAUD.HMAUDReportInput = o_HMAUD_HMAUDReportInput;
});
