/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															   担当
 * YYYYMMDD						#ID							　　　　XXXXXX															  GSDL
 * 20240326        受入検証.xlsx NO4     見出しの高さを全体的に小さくして、データ行ができるだけ多く表示されるようにしてほしい             	  LHB
 * 20240711                20240711_HMTVE.xlsx      Chrome、Edgeで表示状態を確認しましたが微調整が必要な箇所がありました               	  YIN
 * 20240806         20240806_HMTVE(PHP)グリッド高さ調整.xlsx                         caina
 * 20240807         スタッフ一覧と 合計行の間の余白部分が邪魔なのでなくしてほしいとのことです  caina
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE060TotalKShop");

HMTVE.HMTVE060TotalKShop = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE060TotalKShop";
    me.HMTVE = new HMTVE.HMTVE();

    // jqgrid
    me.grid_id = "#HMTVE060TotalKShop_tblMain";
    me.colModel = [
        {
            name: "SYAIN_NM",
            labelClasses:
                "HMTVE060TotalKShop_tblMain_SYAIN60_CELL_TITLE_BLUE_C",
            classes: "SYAIN60_CELL_TITLE_BLUE_C",
            label: "スタッフ名",
            index: "SYAIN_NM",
            width: 100,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "JOKYO",
            label: "確定<br/>状況",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            index: "JOKYO",
            width: 40,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "RAIJYO_KUMI_KEI",
            label: "計",
            index: "RAIJYO_KUMI_KEI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUM_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_AB_KOKYAKU",
            label: "顧<br/>客",
            index: "RAIJYO_KUMI_AB_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_AB_SINTA",
            label: "新<br/>他",
            index: "RAIJYO_KUMI_AB_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_KOKYAKU",
            label: "顧<br/>客",
            index: "RAIJYO_KUMI_NONAB_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_SINTA",
            label: "新<br/>他",
            index: "RAIJYO_KUMI_NONAB_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_FREE",
            label: "内<br/>フ<br/>リ<br/>｜",
            index: "RAIJYO_KUMI_NONAB_FREE",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_DM",
            label: "Ｄ<br/>Ｍ<br/>配<br/>信<br/>数",
            index: "JIZEN_JYUNBI_DM",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_DH",
            label: "Ｄ<br/>Ｈ<br/>配<br/>布<br/>数",
            index: "JIZEN_JYUNBI_DH",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_POSTING",
            label: "ポ<br/>ス<br/>テ<br/>ィ<br/>ン<br/>グ",
            index: "JIZEN_JYUNBI_POSTING",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_TEL",
            label: "Ｔ<br/>Ｅ<br/>Ｌ<br/>コ<br/>｜<br/>ル",
            index: "JIZEN_JYUNBI_TEL",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_KAKUYAKU",
            label: "来<br/>店<br/>確<br/>約<br/>数",
            index: "JIZEN_JYUNBI_KAKUYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_YOBIKOMI",
            label: "DM<br/>DH<br/>TEL<br/>ｺｰﾙ",
            index: "RAIJYO_BUNSEKI_YOBIKOMI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_KAKUYAKU",
            label: "(内)<br/>確<br/>約<br/>来<br/>店",
            index: "RAIJYO_BUNSEKI_KAKUYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_KOUKOKU",
            label: "新<br/>聞",
            index: "RAIJYO_BUNSEKI_KOUKOKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_MEDIA",
            label: "ラ<br/>ジ<br/>オ<br/>・<br/>テ<br/>レ<br/>ビ",
            index: "RAIJYO_BUNSEKI_MEDIA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_CHIRASHI",
            label: "折<br/>込<br/>チ<br/>ラ<br/>シ",
            index: "RAIJYO_BUNSEKI_CHIRASHI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_TORIGAKARI",
            label: "通<br/>り<br/>が<br/>か<br/>り",
            index: "RAIJYO_BUNSEKI_TORIGAKARI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_SYOKAI",
            label: "紹<br/>介",
            index: "RAIJYO_BUNSEKI_SYOKAI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_WEB",
            label: "Ｗ<br/>Ｅ<br/>Ｂ",
            index: "RAIJYO_BUNSEKI_WEB",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_SONOTA",
            label: "そ<br/>の<br/>他",
            index: "RAIJYO_BUNSEKI_SONOTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ENQUETE_KAISYU",
            label: "回<br/>収<br/>数",
            index: "ENQUETE_KAISYU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ENQUETE_RITU",
            label: "回<br/>収<br/>率<br/>（％）",
            index: "ENQUETE_RITU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_KOKYAKU",
            label: "顧<br/>客",
            index: "ABHOT_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_SINTA",
            label: "新<br/>他",
            index: "ABHOT_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_RITU",
            label: "発<br/>生<br/>率<br/>（％）",
            index: "ABHOT_RITU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_ZAN",
            label: "Ａ<br/>Ｂ<br/>ホ<br/>ッ<br/>ト<br/>残",
            index: "ABHOT_ZAN",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_KOKYAKU",
            label: "自<br />　<br />銘<br />　<br />柄",
            index: "SATEI_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_KOKYAKU_TA",
            label: "他<br />　<br />銘<br />　<br />柄",
            index: "SATEI_KOKYAKU_TA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_SINTA",
            label: "自<br />　<br />銘<br />　<br />柄",
            index: "SATEI_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_SINTA_TA",
            label: "他<br />　<br />銘<br />　<br />柄",
            index: "SATEI_SINTA_TA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "DEMO_KENSU",
            label: "デ<br/>モ<br/>件<br/>数",
            index: "DEMO_KENSU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "DEMO_RITU",
            label: "デ<br/>モ<br/>率<br/>（％）",
            index: "DEMO_RITU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RUNCOST_KENSU",
            label: "ラ<br />ン<br />コ<br />ス<br />提<br />案",
            index: "RUNCOST_KENSU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SKYPLAN_KENSU",
            label: "Ｓ<br />Ｋ<br />Ｙ<br />プ<br />ラ<br />ン<br />提<br />案",
            index: "SKYPLAN_KENSU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RUNCOST_SEIYAKU_KENSU",
            label: "ラ<br />ン<br />コ<br />ス<br />提<br />案<br />成<br />約",
            index: "RUNCOST_SEIYAKU_KENSU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SKYPLAN_KEIYAKU_KENSU",
            label: "Ｓ<br />Ｋ<br />Ｙ<br />プ<br />ラ<br />ン<br />提<br />案<br />契<br />約",
            index: "SKYPLAN_KEIYAKU_KENSU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_KEI",
            label: "成<br/>約<br/>台<br/>数",
            index: "SEIYAKU_KEI",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUEGREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_AB_KOKYAKU",
            label: "顧<br/>客",
            index: "SEIYAKU_AB_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_AB_SINTA",
            label: "新<br/>他",
            index: "SEIYAKU_AB_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_KOKYAKU",
            label: "顧<br/>客",
            index: "SEIYAKU_NONAB_KOKYAKU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_SINTA",
            label: "新<br/>他",
            index: "SEIYAKU_NONAB_SINTA",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_FREE",
            label: "内<br/>フ<br/>リ<br/>｜",
            index: "SEIYAKU_NONAB_FREE",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SOKU_RITU",
            label: "即<br/>決<br/>率<br/>（％）",
            index: "SOKU_RITU",
            labelClasses: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE060TotalKShop.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMTVE.TabKeyDown();

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //展示会検索ボタンクリック
    $(".HMTVE060TotalKShop.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    //表示ボタンクリック
    $(".HMTVE060TotalKShop.btnView").click(function () {
        me.btnView_Click();
    });
    //戻るボタンクリック
    $(".HMTVE060TotalKShop.btnReturn").click(function () {
        me.btnReturn_Click();
    });
    //印刷ボタンクリック
    $(".HMTVE060TotalKShop.btnPrintOut").click(function () {
        if (me.checkPDF() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnPrintOut_Click;
            //印刷ボタンの確認メッセージの表示
            me.clsComFnc.FncMsgBox("QY999", "印刷します。よろしいですか？");
        }
    });
    //展示会開催日
    $(".HMTVE060TotalKShop.ddlExhibitDay").change(function () {
        me.ddlExhibitDay_SelectedIndexChanged();
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

        me.Page_Load();
    };
    // **********************************************************************
    // 処 理 名：ページロード
    // 関 数 名：Page_Load
    // 戻 り 値：なし
    // 処理説明：ページ初期化
    // **********************************************************************
    me.Page_Load = function () {
        var PrePG = false;
        if (
            gdmz.SessionPrePG &&
            gdmz.SessionPrePG == "HMTVE070TotalKHonbu"
        ) {
            PrePG = true;
            $(".HMTVE060TotalKShop.btnReturn").button("enable");
        } else {
            $(".HMTVE060TotalKShop.btnReturn").button("disable");
        }
        var data = {
            PrePG: PrePG,
        };
        if (PrePG == true) {
            data.lblExhibitTermFrom = gdmz.SessionStartDT;
            data.TenpoCD_S = gdmz.SessionTenpoCD_S;
        }
        var url = me.sys_id + "/" + me.id + "/pageload";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (PrePG == true) {
                    $(".HMTVE060TotalKShop.lblExhibitTermFrom").val(
                        gdmz.SessionStartDT
                    );
                    if (result["data"]["date"].length > 0) {
                        if (
                            result["data"]["date"][0]["END_DATE"] !== "" &&
                            result["data"]["date"][0]["END_DATE"] !== null
                        ) {
                            var strDate = result["data"]["date"][0]["END_DATE"];
                            $(".HMTVE060TotalKShop.lblExhibitTermTo").val(
                                strDate.substring(0, 4) +
                                    "/" +
                                    strDate.substring(4, 6) +
                                    "/" +
                                    strDate.substring(6, 8)
                            );
                        }
                    }
                } else {
                    if (result["data"]["date"].length > 0) {
                        var FromDate = result["data"]["date"][0]["START_DATE"];
                        var ToDate = result["data"]["date"][0]["END_DATE"];
                        if (FromDate !== "" && FromDate !== null) {
                            $(".HMTVE060TotalKShop.lblExhibitTermFrom").val(
                                FromDate.substring(0, 4) +
                                    "/" +
                                    FromDate.substring(4, 6) +
                                    "/" +
                                    FromDate.substring(6, 8)
                            );
                        }
                        if (ToDate !== "" && ToDate !== null) {
                            $(".HMTVE060TotalKShop.lblExhibitTermTo").val(
                                ToDate.substring(0, 4) +
                                    "/" +
                                    ToDate.substring(4, 6) +
                                    "/" +
                                    ToDate.substring(6, 8)
                            );
                        }
                    }
                }
                if (
                    me.clsComFnc.CheckDate(
                        $(".HMTVE060TotalKShop.lblExhibitTermFrom")
                    ) == false
                ) {
                    $(".HMTVE060TotalKShop.lblExhibitTermFrom").val("");
                }
                if (
                    me.clsComFnc.CheckDate(
                        $(".HMTVE060TotalKShop.lblExhibitTermTo")
                    ) == false
                ) {
                    $(".HMTVE060TotalKShop.lblExhibitTermTo").val("");
                }
                me.setExhibitTermDate(
                    $(".HMTVE060TotalKShop.lblExhibitTermFrom").val(),
                    $(".HMTVE060TotalKShop.lblExhibitTermTo").val()
                );
                if (result["data"]["ShopName"].length > 0) {
                    $(".HMTVE060TotalKShop.lblTenpoNM").val(
                        result["data"]["ShopName"][0]["BUSYO_RYKNM"]
                    );
                    $(".HMTVE060TotalKShop.lblTenpoCD").val(
                        result["data"]["ShopName"][0]["BUSYO_CD"]
                    );
                }
                if (gdmz.SessionPrePG == "HMTVE070TotalKHonbu") {
                    me.btnView_Click();
                    delete gdmz.SessionPrePG;
                    delete gdmz.SessionTenpoCD_S;
                }
                //(展示会開催日)にフォーカス移動
                $(".HMTVE060TotalKShop.ddlExhibitDay").trigger("focus");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：確報集計合計に値をセット
    // 関 数 名：gridComplete
    // 戻 り 値：なし
    // 処理説明：確報集計合計に値をセット
    // **********************************************************************
    me.gridComplete = function (sumData) {
        sumData["JOKYO"] = "合　計";
        $(me.grid_id).jqGrid("footerData", "set", sumData);
        $(".HMTVE060TotalKShop .ui-jqgrid-bdiv .SYAIN60_CELL_TITLE_BLUE_C")
            .css("background", "#99CCFF")
            .css("border-color", "#000099");
        $(".HMTVE060TotalKShop .ui-jqgrid-sdiv tr").css(
            "background",
            "#FFFF99"
        );
        $(".HMTVE060TotalKShop .ui-jqgrid tr.footrow td").css(
            "font-weight",
            "normal"
        );
    };
    // **********************************************************************
    // 処 理 名：表示ボタンクリック
    // 関 数 名：btnView_Click
    // 戻 り 値：なし
    // 処理説明：画面の内容を表示する
    // **********************************************************************
    me.btnView_Click = function () {
        if (me.inputCheck() == false) {
            return;
        }
        var data = {
            lblTenpoCD: $(".HMTVE060TotalKShop.lblTenpoCD").val(),
            ddlExhibitDay: $(".HMTVE060TotalKShop.ddlExhibitDay").val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnViewClick";
        me.ajax.receive = function (result) {
            // $(me.grid_id).GridUnload();
            $.jgrid.gridUnload(me.grid_id);
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "データ読込に失敗しました。"
                    );
                }
                return;
            }
            var syasyuData = result["data"]["syasyu"];
            var detailData = result["data"]["detail"];
            var sumData = result["data"]["sum"];

            var colModels = JSON.parse(JSON.stringify(me.colModel));
            for (var i = 0; i < syasyuData.length; i++) {
                var colmodel = {
                    name: syasyuData[i]["SYASYU_CD"],
                    label:
                        syasyuData[i]["SYASYU_NM"] == null
                            ? " "
                            : me.HMTVE.halfToFull(
                                  syasyuData[i]["SYASYU_NM"]
                              ).replace(/-|ｰ/g, "｜"),
                    index: syasyuData[i]["SYASYU_CD"],
                    labelClasses: "HMTVE060TotalKShop_tblMain_CELL_GREEN_C",
                    width: 40,
                    align: "right",
                    sortable: false,
                };
                colModels.push(colmodel);
            }

            $(".HMTVE060TotalKShop.pnlList").show();
            $(me.grid_id).jqGrid({
                datatype: "local",
                // jqgridにデータがなし場合、文字表示しない
                emptyRecordRow: false,
                caption: "",
                rownumbers: false,
                loadui: "disable",
                footerrow: true,
                shrinkToFit: false,
                autoScroll: true,
                shrinkToFit: false,
                colModel: colModels,
                //20240807 caina ins s
                loadComplete: function () {
                    var height = $(me.grid_id).outerHeight() + 16;
                    if (
                        height <
                        $(
                            ".ui-widget-content.HMTVE.HMTVE-layout-center"
                        ).height() -
                            307
                    ) {
                        gdmz.common.jqgrid.set_grid_height(
                            me.grid_id,
                            height
                        );
                    }
                },
                //20240807 caina ins e
            });
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMTVE060TotalKShop fieldset").width()
            );
            if (detailData.length > 0) {
                // 20240326 LHB UPD S
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, 200);
                // 20240711 YIN UPD S
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, 299);
                var ch = $(
                    ".ui-widget-content.HMTVE.HMTVE-layout-center"
                ).height();
                //20240806 caina upd s
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, ch - 280);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    ch - (me.ratio === 1.5 ? 270 : 307)
                );
                //20240806 caina upd e
                // 20240711 YIN UPD E
                // 20240326 LHB UPD E
            } else {
                $(me.grid_id).css("height", "1px");
            }
            $(me.grid_id).jqGrid("bindKeys");
            if (syasyuData.length > 0) {
                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_KUMI_KEI",
                            numberOfColumns: 6,
                            titleText: "来場組数",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "JIZEN_JYUNBI_DM",
                            numberOfColumns: 5,
                            titleText: "展示会事前活動",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI",
                            numberOfColumns: 9,
                            titleText: "来場分析",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "ENQUETE_KAISYU",
                            numberOfColumns: 2,
                            titleText: "アンケート",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "ABHOT_KOKYAKU",
                            numberOfColumns: 3,
                            titleText: "ＡＢホット発生",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SATEI_KOKYAKU",
                            numberOfColumns: 4,
                            titleText: "査定",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "DEMO_KENSU",
                            numberOfColumns: 2,
                            titleText: "デモ",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SEIYAKU_AB_KOKYAKU",
                            numberOfColumns: 6,
                            titleText: "成約内訳",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUEGREEN_C",
                            startColumnName: syasyuData[0]["SYASYU_CD"],
                            numberOfColumns: syasyuData.length,
                            titleText: "成約車種内訳",
                        },
                    ],
                });
            } else {
                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_KUMI_KEI",
                            numberOfColumns: 6,
                            titleText: "来場組数",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "JIZEN_JYUNBI_DM",
                            numberOfColumns: 5,
                            titleText: "展示会事前活動",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI",
                            numberOfColumns: 9,
                            titleText: "来場分析",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "ENQUETE_KAISYU",
                            numberOfColumns: 2,
                            titleText: "アンケート",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "ABHOT_KOKYAKU",
                            numberOfColumns: 3,
                            titleText: "ＡＢホット発生",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SATEI_KOKYAKU",
                            numberOfColumns: 4,
                            titleText: "査定",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "DEMO_KENSU",
                            numberOfColumns: 2,
                            titleText: "デモ",
                        },
                        {
                            className:
                                "HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SEIYAKU_AB_KOKYAKU",
                            numberOfColumns: 6,
                            titleText: "成約内訳",
                        },
                    ],
                });
            }
            $(me.grid_id).jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                numberOfRowSpan: 3,
                groupHeaders: [
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_KUMI_AB_KOKYAKU",
                        numberOfColumns: 2,
                        titleText: "Ａ　Ｂ",
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_KUMI_NONAB_KOKYAKU",
                        numberOfColumns: 3,
                        titleText: "ＮＯＮ－ＡＢ",
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI",
                        numberOfColumns: 2,
                        // 20240327 LHB UPD S
                        // titleText : '事前活動<br/>結果'
                        titleText: "事前活動結果",
                        // 20240327 LHB UPD S
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SATEI_KOKYAKU",
                        numberOfColumns: 2,
                        titleText: "顧客",
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SATEI_SINTA",
                        numberOfColumns: 2,
                        titleText: "新他",
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SEIYAKU_AB_KOKYAKU",
                        numberOfColumns: 2,
                        titleText: "Ａ　Ｂ",
                    },
                    {
                        className: "HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SEIYAKU_NONAB_KOKYAKU",
                        numberOfColumns: 3,
                        titleText: "ＮＯＮ－ＡＢ",
                    },
                ],
            });
            $(me.grid_id).jqGrid("setFrozenColumns");
            $(".HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C").css(
                "background",
                "#FFFF99"
            );
            $(".HMTVE060TotalKShop_tblMain_CELL_SUM_C").css(
                "background",
                "#FF99CC"
            );
            $(".HMTVE060TotalKShop_tblMain_CELL_GREEN_C").css(
                "background",
                "#CCFF99"
            );
            $(".HMTVE060TotalKShop_tblMain_CELL_BLUE_C")
                .css("background", "#99CCFF")
                .css("border-color", "#000099");
            $(".HMTVE060TotalKShop_tblMain_CELL_SUBTITLE_C").css(
                "background",
                "#FFFF99"
            );
            $(
                ".HMTVE060TotalKShop .frozen-div.ui-state-default.ui-jqgrid-hdiv"
            ).css("overflow-y", "hidden");
            $(".HMTVE060TotalKShop .ui-jqgrid-hdiv .ui-jqgrid-hbox").css(
                "background",
                "#CCFF99"
            );
            $(".HMTVE060TotalKShop_tblMain_CELL_TITLE_GREEN_C")
                .css("background", "#006600")
                .css("color", "#FFFFFF");
            $(".HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUE_C")
                .css("background", "#000099")
                .css("color", "#FFFFFF");
            $(".HMTVE060TotalKShop_tblMain_CELL_TITLE_BLUEGREEN_C").css(
                "background",
                "#F6C30A"
            );
            $(".HMTVE060TotalKShop_tblMain_SYAIN60_CELL_TITLE_BLUE_C")
                .css("background", "#000099")
                .css("color", "#FFFFFF");
            //(展示会開催日)にフォーカス移動
            $(".HMTVE060TotalKShop.ddlExhibitDay").trigger("focus");

            $(me.grid_id)
                .setGridParam({
                    data: detailData,
                })
                .trigger("reloadGrid");
            //１行目選択
            $(me.grid_id).jqGrid("setSelection", 1);
            me.gridComplete(sumData);
            me.getSumFrozen();
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：印刷ボタンクリック
    // 関 数 名：btnPrintOut_Click
    // 戻 り 値：なし
    // '処理説明：ＰＤＦ生成処理
    // '**********************************************************************
    me.btnPrintOut_Click = function () {
        var data = {
            lblExhibitTermFrom: $.trim(
                $(".HMTVE060TotalKShop.lblExhibitTermFrom").val()
            ),
            lblExhibitTermTo: $.trim(
                $(".HMTVE060TotalKShop.lblExhibitTermTo").val()
            ),
            ddlExhibitDay: $(".HMTVE060TotalKShop.ddlExhibitDay").val(),
            lblTenpoNM: $(".HMTVE060TotalKShop.lblTenpoNM").val(),
            lblTenpoCD: $(".HMTVE060TotalKShop.lblTenpoCD").val().trimEnd(),
            ddlExhibitDay: $(".HMTVE060TotalKShop.ddlExhibitDay").val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnPrintOutClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (
                    result["error"] ==
                    "フォルダのパーミッションはエラーが発生しました。"
                ) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                } else if (
                    result["error"] == "テンプレートファイルが存在しません。"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "出力処理中にエラーが発生しました。"
                    );
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    //入力チェック
    me.checkPDF = function () {
        var $lblETStart = $(".HMTVE060TotalKShop.lblExhibitTermFrom");
        var $lblETEnd = $(".HMTVE060TotalKShop.lblExhibitTermTo");
        var $ddlED = $(".HMTVE060TotalKShop.ddlExhibitDay");
        var $lblTenpoNM = $(".HMTVE060TotalKShop.lblTenpoNM");
        if ($.trim($lblETStart.val()).length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください"
            );
            return false;
        }
        if ($.trim($lblETEnd.val()).length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください"
            );
            return false;
        }
        if ($.trim($ddlED.val()).length == 0) {
            me.clsComFnc.ObjFocus = $ddlED;
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください");
            return false;
        }
        if ($.trim($lblTenpoNM.val()).length == 0) {
            me.clsComFnc.FncMsgBox("W9999", "表示できる部署が存在しません！");
            return false;
        }
        return true;
    };
    // **********************************************************************
    // 処 理 名：開催日を変えることのイベント
    // 関 数 名：ddlExhibitDay_SelectedIndexChanged
    // 戻 り 値：なし
    // 処理説明：開催日を変えることの処理
    // '**********************************************************************
    me.ddlExhibitDay_SelectedIndexChanged = function () {
        $(me.grid_id).jqGrid("clearGridData");
        $(".HMTVE060TotalKShop.pnlList").hide();
    };
    // **********************************************************************
    // 処 理 名：入力チェック
    // 関 数 名：inputCheck
    // 引 数 １：なし
    // 戻 り 値：なし
    // 処理説明：入力の内容をチェック
    // **********************************************************************
    me.inputCheck = function () {
        //展示会開催期間(From)が未入力の場合、エラー
        if ($(".HMTVE060TotalKShop.lblExhibitTermFrom").val().length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲開始)を選択してください。"
            );
            return false;
        }
        //展示会開催期間(To)が未入力の場合、エラー
        if ($(".HMTVE060TotalKShop.lblExhibitTermTo").val().length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "展示会開催期間(範囲終了)を選択してください。"
            );
            return false;
        }
        //展示会開催日が未入力の場合、エラー
        if (
            $(".HMTVE060TotalKShop.ddlExhibitDay").val() == "" ||
            $(".HMTVE060TotalKShop.ddlExhibitDay").val() == null
        ) {
            me.clsComFnc.ObjFocus = $(".HMTVE060TotalKShop.ddlExhibitDay");
            me.clsComFnc.FncMsgBox("W9999", "展示会開催日を選択してください。");
            return false;
        }
        //部署コードが未入力の場合、エラー
        if ($(".HMTVE060TotalKShop.lblTenpoCD").val().length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "表示できる部署が存在しません。管理者にお問い合わせください。"
            );
            return false;
        }
        return true;
    };
    // **********************************************************************
    // 処 理 名：戻るボタンクリック
    // 関 数 名：btnReturn_Click
    // 戻 り 値：なし
    // 処理説明：前のペッジを遷移
    // **********************************************************************
    me.btnReturn_Click = function () {
        gdmz.SessionPrePG = "HMTVE060TotalKShop";
        o_HMTVE_HMTVE.FrmHMTVEMainMenu.blnFlag = false;
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "deselect_node",
            "#HMTVE060TotalKShop"
        );
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "select_node",
            "#HMTVE070TotalKHonbu"
        );
    };
    //列を凍結する
    me.getSumFrozen = function (contactName) {
        $(".HMTVE060TotalKShop .frozen-sdiv.ui-jqgrid-sdiv").remove();
        var $sumdiv = $(".HMTVE060TotalKShop .ui-jqgrid-sdiv").clone();
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
        var hth =
            $(
                ".HMTVE060TotalKShop .frozen-div.ui-state-default.ui-jqgrid-hdiv"
            ).height() +
            $(".HMTVE060TotalKShop .frozen-bdiv.ui-jqgrid-bdiv").height();
        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;top:' +
                (parseInt(hth, 10) + 17) +
                'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>'
        );
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter($(".HMTVE060TotalKShop .frozen-bdiv"));
    };
    // **********************************************************************
    // 処 理 名：検索ボタンクリック
    // 関 数 名：btnETSearch_Click
    // 戻 り 値：なし
    // 処理説明：検索画面の表示と検索結果のセット
    // **********************************************************************
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE060TotalKShopDialogDiv";
        var title = "展示会検索";
        var $rootDiv = $(".HMTVE060TotalKShop.HMTVE-content");
        if ($("#" + dialogdiv).length > 0) {
            $("#" + dialogdiv).remove();
        }
        $("<div></div>").attr("id", dialogdiv).insertAfter($rootDiv);
        $("<div></div>").attr("id", "RtnCD").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETStart").insertAfter($rootDiv);
        $("<div></div>").attr("id", "lblETEnd").insertAfter($rootDiv);

        var $RtnCD = $rootDiv.parent().find("#RtnCD");
        var $lblETStart = $rootDiv.parent().find("#lblETStart");
        var $lblETEnd = $rootDiv.parent().find("#lblETEnd");

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            function before_close() {
                if ($RtnCD.html() == 1) {
                    $(".HMTVE060TotalKShop.lblExhibitTermFrom").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE060TotalKShop.lblExhibitTermTo").val(
                        $lblETEnd.html()
                    );
                    $(".HMTVE060TotalKShop.pnlList").hide();
                    me.setExhibitTermDate(
                        $(".HMTVE060TotalKShop.lblExhibitTermFrom").val(),
                        $(".HMTVE060TotalKShop.lblExhibitTermTo").val()
                    );
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
                $(".HMTVE060TotalKShop.btnETSearch").blur();
            }

            $RtnCD.hide();
            $lblETStart.hide();
            $lblETEnd.hide();
            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE060TotalKShop.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    // **********************************************************************
    // 処 理 名：店舗名を取得
    // 関 数 名：getShopName
    // 引 数   ：なし
    // 戻 り 値：なし
    // 処理説明：店舗名を取得する
    // **********************************************************************
    me.getShopName = function () {
        $(".HMTVE060TotalKShop.lblTenpoNM").val("祇園店");
        $(".HMTVE060TotalKShop.lblTenpoCD").val(380);
    };
    // **********************************************************************
    // 処 理 名：展示会開催期間初期値セット
    // 関 数 名：setExhibitTermDate
    // 引 数   ：なし
    // 戻 り 値：なし
    // 処理説明：展示会開催期間に初期値をセット
    // **********************************************************************
    me.setExhibitTermDate = function (From, To) {
        $(".HMTVE060TotalKShop.ddlExhibitDay").html("");
        var days = me.DateDiff(From, To);
        for (var i = 0; i <= days; i++) {
            var Fromdate = new Date(From);
            Fromdate.setDate(Fromdate.getDate() + i);
            var strdate = Fromdate.Format("yyyy/MM/dd");
            $("<option></option>")
                .val(strdate)
                .text(strdate)
                .appendTo(".HMTVE060TotalKShop.ddlExhibitDay");
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
    var o_HMTVE_HMTVE060TotalKShop = new HMTVE.HMTVE060TotalKShop();
    o_HMTVE_HMTVE060TotalKShop.load();
    o_HMTVE_HMTVE.HMTVE060TotalKShop = o_HMTVE_HMTVE060TotalKShop;
});
