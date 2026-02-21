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
 * 20240805         20240805_HMTVE（PHP）_画面調整依頼.xlsx                        caina
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE070TotalKHonbu");

HMTVE.HMTVE070TotalKHonbu = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE070TotalKHonbu";
    me.HMTVE = new HMTVE.HMTVE();

    // jqgrid
    me.grid_id = "#HMTVE070TotalKHonbu_tblMain";

    me.colModel = [
        {
            name: "BUSYO_RYKNM",
            labelClasses:
                "HMTVE070TotalKHonbu_tblMain_BUSYO70_CELL_TITLE_BLUE_C",
            classes: "BUSYO70_CELL_TITLE_BLUE_C",
            label: "店舗名",
            index: "BUSYO_RYKNM",
            width: 100,
            align: "center",
            sortable: false,
            frozen: true,
            formatter: function (_rowId, _options, row) {
                if (
                    gdmz.SessionPatternID == me.HMTVE.CONST_ADMIN_PTN_NO ||
                    gdmz.SessionPatternID == me.HMTVE.CONST_HONBU_PTN_NO ||
                    gdmz.SessionPatternID == me.HMTVE.CONST_TESTER_PTN_NO
                ) {
                    var btn = "";
                    btn +=
                        '&nbsp;<a href="javascript:gvBusyoRowCommand(' +
                        row["BUSYO_CD"] +
                        ')"class="mesq">' +
                        row["BUSYO_RYKNM"] +
                        "</a>&nbsp;";
                    return btn;
                } else {
                    return row["BUSYO_RYKNM"];
                }
            },
        },
        {
            name: "JYOKYO",
            label: "確定<br/>状況",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            index: "JYOKYO",
            width: 40,
            align: "center",
            sortable: false,
            frozen: true,
        },
        {
            name: "RAIJYO_KUMI_KEI",
            label: "計",
            index: "RAIJYO_KUMI_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUM_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_AB_KOKYAKU_KEI",
            label: "顧<br/>客",
            index: "RAIJYO_KUMI_AB_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_AB_SINTA_KEI",
            label: "新<br/>他",
            index: "RAIJYO_KUMI_AB_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_KOKYAKU_KEI",
            label: "顧<br/>客",
            index: "RAIJYO_KUMI_NONAB_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_SINTA_KEI",
            label: "新<br/>他",
            index: "RAIJYO_KUMI_NONAB_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_KUMI_NONAB_FREE_KEI",
            label: "内<br/>フ<br/>リ<br/>｜",
            index: "RAIJYO_KUMI_NONAB_FREE_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_DM_KEI",
            label: "Ｄ<br/>Ｍ<br/>配<br/>信<br/>数",
            index: "JIZEN_JYUNBI_DM_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_DH_KEI",
            label: "Ｄ<br/>Ｈ<br/>配<br/>布<br/>数",
            index: "JIZEN_JYUNBI_DH_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_POSTING_KEI",
            label: "ポ<br/>ス<br/>テ<br/>ィ<br/>ン<br/>グ",
            index: "JIZEN_JYUNBI_POSTING_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_TEL_KEI",
            label: "Ｔ<br/>Ｅ<br/>Ｌ<br/>コ<br/>｜<br/>ル",
            index: "JIZEN_JYUNBI_TEL_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "JIZEN_JYUNBI_KAKUYAKU_KEI",
            label: "来<br/>店<br/>確<br/>約<br/>数",
            index: "JIZEN_JYUNBI_KAKUYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_YOBIKOMI_KEI",
            label: "DM<br/>DH<br/>TEL<br/>ｺｰﾙ",
            index: "RAIJYO_BUNSEKI_YOBIKOMI_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_KAKUYAKU_KEI",
            label: "(内)<br/>確<br/>約<br/>来<br/>店",
            index: "RAIJYO_BUNSEKI_KAKUYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_KOUKOKU_KEI",
            label: "新<br/>聞",
            index: "RAIJYO_BUNSEKI_KOUKOKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_MEDIA_KEI",
            label: "ラ<br/>ジ<br/>オ<br/>・<br/>テ<br/>レ<br/>ビ",
            index: "RAIJYO_BUNSEKI_MEDIA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_CHIRASHI_KEI",
            label: "折<br/>込<br/>チ<br/>ラ<br/>シ",
            index: "RAIJYO_BUNSEKI_CHIRASHI_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_TORIGAKARI_KEI",
            label: "通<br/>り<br/>が<br/>か<br/>り",
            index: "RAIJYO_BUNSEKI_TORIGAKARI_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_SYOKAI_KEI",
            label: "紹<br/>介",
            index: "RAIJYO_BUNSEKI_SYOKAI_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_WEB_KEI",
            label: "Ｗ<br/>Ｅ<br/>Ｂ",
            index: "RAIJYO_BUNSEKI_WEB_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RAIJYO_BUNSEKI_SONOTA_KEI",
            label: "そ<br/>の<br/>他",
            index: "RAIJYO_BUNSEKI_SONOTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ENQUETE_KAISYU_KEI",
            label: "回<br/>収<br/>数",
            index: "ENQUETE_KAISYU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ENQUETE_RITU",
            label: "回<br/>収<br/>率<br/>（％）",
            index: "ENQUETE_RITU",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_KOKYAKU_KEI",
            label: "顧<br/>客",
            index: "ABHOT_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_SINTA_KEI",
            label: "新<br/>他",
            index: "ABHOT_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_RITU",
            label: "発<br/>生<br/>率<br/>（％）",
            index: "ABHOT_RITU",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "ABHOT_ZAN_KEI",
            label: "Ａ<br/>Ｂ<br/>ホ<br/>ッ<br/>ト<br/>残",
            index: "ABHOT_ZAN_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_KOKYAKU_KEI",
            label: "自<br />　<br />銘<br />　<br />柄",
            index: "SATEI_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_KOKYAKU_TA_KEI",
            label: "他<br />　<br />銘<br />　<br />柄",
            index: "SATEI_KOKYAKU_TA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_SINTA_KEI",
            label: "自<br />　<br />銘<br />　<br />柄",
            index: "SATEI_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SATEI_SINTA_TA_KEI",
            label: "他<br />　<br />銘<br />　<br />柄",
            index: "SATEI_SINTA_TA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "DEMO_KENSU_KEI",
            label: "デ<br/>モ<br/>件<br/>数",
            index: "DEMO_KENSU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "DEMO_RITU",
            label: "デ<br/>モ<br/>率<br/>（％）",
            index: "DEMO_RITU",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RUNCOST_KENSU_KEI",
            label: "ラ<br />ン<br />コ<br />ス<br />提<br />案",
            index: "RUNCOST_KENSU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SKYPLAN_KENSU_KEI",
            label: "Ｓ<br />Ｋ<br />Ｙ<br />プ<br />ラ<br />ン<br />提<br />案",
            index: "SKYPLAN_KENSU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "RUNCOST_SEIYAKU_KENSU_KEI",
            label: "ラ<br />ン<br />コ<br />ス<br />提<br />案<br />成<br />約",
            index: "RUNCOST_SEIYAKU_KENSU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SKYPLAN_KEIYAKU_KENSU_KEI",
            label: "Ｓ<br />Ｋ<br />Ｙ<br />プ<br />ラ<br />ン<br />提<br />案<br />契<br />約",
            index: "SKYPLAN_KEIYAKU_KENSU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_KEI",
            label: "成<br/>約<br/>台<br/>数",
            index: "SEIYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUEGREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_AB_KOKYAKU_KEI",
            label: "顧<br/>客",
            index: "SEIYAKU_AB_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_AB_SINTA_KEI",
            label: "新<br/>他",
            index: "SEIYAKU_AB_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_KOKYAKU_KEI",
            label: "顧<br/>客",
            index: "SEIYAKU_NONAB_KOKYAKU_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_SINTA_KEI",
            label: "新<br/>他",
            index: "SEIYAKU_NONAB_SINTA_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SEIYAKU_NONAB_FREE_KEI",
            label: "内<br/>フ<br/>リ<br/>｜",
            index: "SEIYAKU_NONAB_FREE_KEI",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
            width: 40,
            align: "right",
            sortable: false,
        },
        {
            name: "SOKU_RITU",
            label: "即<br/>決<br/>率<br/>（％）",
            index: "SOKU_RITU",
            labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
            width: 40,
            align: "right",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE070TotalKHonbu.button",
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
    $(".HMTVE070TotalKHonbu.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    //表示ボタンクリック
    $(".HMTVE070TotalKHonbu.btnView").click(function () {
        me.btnView_Click();
    });
    //Excel出力ボタンクリック
    $(".HMTVE070TotalKHonbu.btnExcelOut").click(function () {
        if (me.checkNull() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnExcelOut_Click;
            //印刷ボタンの確認メッセージの表示
            me.clsComFnc.FncMsgBox(
                "QY999",
                "確報集計(本部用)のEXCELを出力します。よろしいですか？"
            );
        }
    });
    //CSV出力ボタンクリック
    $(".HMTVE070TotalKHonbu.btnCSVOut").click(function () {
        if (me.checkNull() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnCSVOut_Click;
            //印刷ボタンの確認メッセージの表示
            me.clsComFnc.FncMsgBox(
                "QY999",
                "CSVを出力します。よろしいですか？"
            );
        }
    });
    //HITNET用Excel出力ボタンクリック
    $(".HMTVE070TotalKHonbu.btnOutputHITNET").click(function () {
        if (me.checkNull() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnOutputHITNET_Click;
            //印刷ボタンの確認メッセージの表示
            var ckval1 = $.trim(
                $(".HMTVE070TotalKHonbu.lblExhibitTermStart").val()
            );
            var ckval2 = $.trim(
                $(".HMTVE070TotalKHonbu.lblExhibitTermEnd").val()
            );
            me.clsComFnc.FncMsgBox(
                "QY999",
                ckval1 +
                    "～" +
                    ckval2 +
                    "のHITNET用のEXCELデータを出力します。よろしいですか？"
            );
        }
    });
    //HITNET用Excel出力ボタンクリック
    $(".HMTVE070TotalKHonbu.btnLock").click(function () {
        if (me.checkNull() == false) {
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLock_Click;
            //印刷ボタンの確認メッセージの表示
            var ckval1 = $.trim(
                $(".HMTVE070TotalKHonbu.lblExhibitTermStart").val()
            );
            var ckval2 = $.trim(
                $(".HMTVE070TotalKHonbu.lblExhibitTermEnd").val()
            );
            me.clsComFnc.FncMsgBox(
                "QY999",
                "展示会:" +
                    ckval1 +
                    "～" +
                    ckval2 +
                    "の確報データのロックを解除します。よろしいですか？"
            );
        }
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
        if (
            gdmz.SessionPatternID !== me.HMTVE.CONST_ADMIN_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_HONBU_PTN_NO &&
            gdmz.SessionPatternID !== me.HMTVE.CONST_TESTER_PTN_NO
        ) {
            $(".HMTVE070TotalKHonbu.btnOutputHITNET").hide();
            $(".HMTVE070TotalKHonbu.btnLock").hide();
        }
        $(".HMTVE070TotalKHonbu.btnETSearch").trigger("focus");

        if (gdmz.SessionPrePG == "HMTVE060TotalKShop") {
            $(".HMTVE070TotalKHonbu.lblExhibitTermStart").val(
                gdmz.SessionStartDT
            );
            var data = {
                lblExhibitTermStart: gdmz.SessionStartDT,
            };
            var url = me.sys_id + "/" + me.id + "/pageload";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (result["result"] == true) {
                    var endDate = result["data"][0]["END_DATE"];
                    $(".HMTVE070TotalKHonbu.lblExhibitTermEnd").val(
                        endDate.substring(0, 4) +
                            "/" +
                            endDate.substring(4, 6) +
                            "/" +
                            endDate.substring(6, 8)
                    );
                    delete gdmz.SessionPrePG;
                    delete gdmz.SessionTenpoCD_S;
                    delete gdmz.SessionStartDT;
                    me.btnView_Click();
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            };
            me.ajax.send(url, data, 0);
        }
    };
    // **********************************************************************
    // 処 理 名：確報集計テーブル合計
    // 関 数 名：gridComplete
    // 戻 り 値：なし
    // 処理説明：確報集計テーブル合計
    // **********************************************************************
    me.gridComplete = function (sumData) {
        sumData["JYOKYO"] = "合　計";
        $(me.grid_id).jqGrid("footerData", "set", sumData);

        $(".HMTVE070TotalKHonbu .ui-jqgrid-sdiv tr").css(
            "background",
            "#FFFF99"
        );
        $(".HMTVE070TotalKHonbu .ui-jqgrid tr.footrow td").css(
            "font-weight",
            "normal"
        );
        $(".HMTVE070TotalKHonbu .ui-jqgrid-bdiv .BUSYO70_CELL_TITLE_BLUE_C")
            .css("background", "#99CCFF")
            .css("border-color", "#000099");
    };
    // **********************************************************************
    // 処 理 名：表示ボタンクリック
    // 関 数 名：btnView_Click
    // 戻 り 値：なし
    // 処理説明：確報集計テーブル表示
    // **********************************************************************
    me.btnView_Click = function () {
        if (me.checkNull() == false) {
            return;
        }

        var data = {
            lblExhibitTermStart: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermStart"
            ).val(),
            lblExhibitTermEnd: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermEnd"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnViewClick";
        me.ajax.receive = function (result) {
            $.jgrid.gridUnload(me.grid_id);
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            var syasyuData = result["data"]["syasyu"];
            var detailData = result["data"]["detail"];
            var sumData = result["data"]["sum"];

            var colModels = JSON.parse(JSON.stringify(me.colModel));
            colModels[0] = {
                name: "BUSYO_RYKNM",
                labelClasses:
                    "HMTVE070TotalKHonbu_tblMain_BUSYO70_CELL_TITLE_BLUE_C",
                classes: "BUSYO70_CELL_TITLE_BLUE_C",
                label: "店舗名",
                index: "BUSYO_RYKNM",
                width: 100,
                align: "center",
                sortable: false,
                frozen: true,
                formatter: function (_rowId, _options, row) {
                    if (
                        gdmz.SessionPatternID ==
                            me.HMTVE.CONST_ADMIN_PTN_NO ||
                        gdmz.SessionPatternID ==
                            me.HMTVE.CONST_HONBU_PTN_NO ||
                        gdmz.SessionPatternID == me.HMTVE.CONST_TESTER_PTN_NO
                    ) {
                        var btn = "";
                        btn +=
                            '&nbsp;<a href="javascript:gvBusyoRowCommand(' +
                            row["BUSYO_CD"] +
                            ')"class="mesq">' +
                            row["BUSYO_RYKNM"] +
                            "</a>&nbsp;";
                        return btn;
                    } else {
                        return row["BUSYO_RYKNM"];
                    }
                },
            };

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
                    labelClasses: "HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C",
                    width: 40,
                    align: "right",
                    sortable: false,
                };
                colModels.push(colmodel);
            }

            $(".HMTVE070TotalKHonbu.pnlList").show();
            $(me.grid_id).jqGrid({
                datatype: "local",
                caption: "",
                rownumbers: false,
                loadui: "disable",
                footerrow: true,
                shrinkToFit: false,
                autoScroll: true,
                shrinkToFit: false,
                colModel: colModels,
                rowNum: 9999,
            });
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMTVE070TotalKHonbu fieldset").width()
            );
            if (detailData.length > 0) {
                // 20240326 LHB UPD S
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, 222);
                // 20240711 YIN UPD S
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, 326);
                var ch = $(
                    ".ui-widget-content.HMTVE.HMTVE-layout-center"
                ).height();
                //20240805 caina upd s
                // gdmz.common.jqgrid.set_grid_height(me.grid_id, ch - 250);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, ch - 277);
                //20240805 caina upd e
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
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_KUMI_KEI",
                            numberOfColumns: 6,
                            titleText: "来場組数",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "JIZEN_JYUNBI_DM_KEI",
                            numberOfColumns: 5,
                            titleText: "展示会事前活動",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI_KEI",
                            numberOfColumns: 9,
                            titleText: "来場分析",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "ENQUETE_KAISYU_KEI",
                            numberOfColumns: 2,
                            titleText: "アンケート",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "ABHOT_KOKYAKU_KEI",
                            numberOfColumns: 3,
                            titleText: "ＡＢホット発生",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SATEI_KOKYAKU_KEI",
                            numberOfColumns: 4,
                            titleText: "査定",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "DEMO_KENSU_KEI",
                            numberOfColumns: 2,
                            titleText: "デモ",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SEIYAKU_AB_KOKYAKU_KEI",
                            numberOfColumns: 6,
                            titleText: "成約内訳",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUEGREEN_C",
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
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_KUMI_KEI",
                            numberOfColumns: 6,
                            titleText: "来場組数",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "JIZEN_JYUNBI_DM_KEI",
                            numberOfColumns: 5,
                            titleText: "展示会事前活動",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI_KEI",
                            numberOfColumns: 9,
                            titleText: "来場分析",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "ENQUETE_KAISYU_KEI",
                            numberOfColumns: 2,
                            titleText: "アンケート",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "ABHOT_KOKYAKU_KEI",
                            numberOfColumns: 3,
                            titleText: "ＡＢホット発生",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SATEI_KOKYAKU_KEI",
                            numberOfColumns: 4,
                            titleText: "査定",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C",
                            startColumnName: "DEMO_KENSU_KEI",
                            numberOfColumns: 2,
                            titleText: "デモ",
                        },
                        {
                            className:
                                "HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C",
                            startColumnName: "SEIYAKU_AB_KOKYAKU_KEI",
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
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_KUMI_AB_KOKYAKU_KEI",
                        numberOfColumns: 2,
                        titleText: "Ａ　Ｂ",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_KUMI_NONAB_KOKYAKU_KEI",
                        numberOfColumns: 3,
                        titleText: "ＮＯＮ－ＡＢ",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "RAIJYO_BUNSEKI_YOBIKOMI_KEI",
                        numberOfColumns: 2,
                        titleText: "事前活動結果",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SATEI_KOKYAKU_KEI",
                        numberOfColumns: 2,
                        titleText: "顧客",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SATEI_SINTA_KEI",
                        numberOfColumns: 2,
                        titleText: "新他",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SEIYAKU_AB_KOKYAKU_KEI",
                        numberOfColumns: 2,
                        titleText: "Ａ　Ｂ",
                    },
                    {
                        className:
                            "HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C",
                        startColumnName: "SEIYAKU_NONAB_KOKYAKU_KEI",
                        numberOfColumns: 3,
                        titleText: "ＮＯＮ－ＡＢ",
                    },
                ],
            });
            $(me.grid_id).jqGrid("setFrozenColumns");
            $(".HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C").css(
                "background",
                "#FFFF99"
            );
            $(".HMTVE070TotalKHonbu_tblMain_CELL_SUM_C").css(
                "background",
                "#FF99CC"
            );
            $(".HMTVE070TotalKHonbu_tblMain_CELL_GREEN_C").css(
                "background",
                "#CCFF99"
            );
            $(".HMTVE070TotalKHonbu_tblMain_CELL_BLUE_C")
                .css("background", "#99CCFF")
                .css("border-color", "#000099");
            $(".HMTVE070TotalKHonbu_tblMain_CELL_SUBTITLE_C").css(
                "background",
                "#FFFF99"
            );
            $(
                ".HMTVE070TotalKHonbu .frozen-div.ui-state-default.ui-jqgrid-hdiv"
            ).css("overflow-y", "hidden");
            $(".HMTVE070TotalKHonbu .ui-jqgrid-hdiv .ui-jqgrid-hbox").css(
                "background",
                "#CCFF99"
            );
            $(".HMTVE070TotalKHonbu_tblMain_CELL_TITLE_GREEN_C")
                .css("background", "#006600")
                .css("color", "#FFFFFF");
            $(".HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUE_C")
                .css("background", "#000099")
                .css("color", "#FFFFFF");
            $(".HMTVE070TotalKHonbu_tblMain_BUSYO70_CELL_TITLE_BLUE_C")
                .css("background", "#000099")
                .css("color", "#FFFFFF");
            $(".HMTVE070TotalKHonbu_tblMain_CELL_TITLE_BLUEGREEN_C").css(
                "background",
                "#F6C30A"
            );

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
    // 処 理 名：店舗名クリック
    // 関 数 名：gvBusyoRowCommand
    // 戻 り 値：なし
    // 処理説明：店舗名クリック画面遷移
    // **********************************************************************
    gvBusyoRowCommand = function (contactName) {
        gdmz.SessionPrePG = "HMTVE070TotalKHonbu";
        gdmz.SessionTenpoCD_S = contactName;
        gdmz.SessionStartDT = $(
            ".HMTVE070TotalKHonbu.lblExhibitTermStart"
        ).val();
        o_HMTVE_HMTVE.FrmHMTVEMainMenu.blnFlag = false;
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "deselect_node",
            "#HMTVE070TotalKHonbu"
        );
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "select_node",
            "#HMTVE060TotalKShop"
        );
    };
    //列を凍結する
    me.getSumFrozen = function () {
        $(".HMTVE070TotalKHonbu .frozen-sdiv.ui-jqgrid-sdiv").remove();
        var $sumdiv = $(".HMTVE070TotalKHonbu .ui-jqgrid-sdiv").clone();
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
                ".HMTVE070TotalKHonbu .frozen-div.ui-state-default.ui-jqgrid-hdiv"
            ).height() +
            $(".HMTVE070TotalKHonbu .frozen-bdiv.ui-jqgrid-bdiv").height();
        $sumFrozenDiv = $(
            '<div style="position:absolute;left:0px;top:' +
                (parseInt(hth, 10) + 17) +
                'px;" class="frozen-sdiv ui-jqgrid-sdiv"></div>'
        );
        $sumFrozenDiv.append($sumdiv);
        $sumFrozenDiv.insertAfter($(".HMTVE070TotalKHonbu .frozen-bdiv"));
    };
    // **********************************************************************
    // 処 理 名：展示会検索ボタンのイベント
    // 関 数 名：btnETSearch_Click
    // 戻 り 値：なし
    // 処理説明：検索画面の表示
    // **********************************************************************
    me.btnETSearch_Click = function () {
        var frmId = "HMTVE080ExhibitionSearch";
        var dialogdiv = "HMTVE070TotalKHonbuDialogDiv";
        var $rootDiv = $(".HMTVE070TotalKHonbu.HMTVE-content");
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
                    $(".HMTVE070TotalKHonbu.lblExhibitTermStart").val(
                        $lblETStart.html()
                    );
                    $(".HMTVE070TotalKHonbu.lblExhibitTermEnd").val(
                        $lblETEnd.html()
                    );
                    $(".HMTVE070TotalKHonbu.pnlList").hide();
                }
                $RtnCD.remove();
                $lblETStart.remove();
                $lblETEnd.remove();
                $("#" + dialogdiv).remove();
                $(".HMTVE070TotalKHonbu.btnETSearch").trigger("blur");
            }

            $RtnCD.hide();
            $lblETStart.hide();
            $lblETEnd.hide();
            $("#" + dialogdiv).hide();
            $("#" + dialogdiv).append(result);
            o_HMTVE_HMTVE.HMTVE070TotalKHonbu.HMTVE080ExhibitionSearch.before_close =
                before_close;
        };
    };
    // **********************************************************************
    // 処 理 名：Excel出力ボタン
    // 関 数 名：btnExcelOut_Click
    // 戻 り 値：なし
    // 処理説明：確報集計(本部用)Excel出力
    // **********************************************************************
    me.btnExcelOut_Click = function () {
        var data = {
            lblExhibitTermStart: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermStart"
            ).val(),
            lblExhibitTermEnd: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermEnd"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnExcelOutClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (
                    result["error"] == "テンプレートファイルが存在しません。"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                $(".HMTVE070TotalKHonbu.pnlList").hide();
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：CSV出力ボタンクリック
    // 関 数 名：btnCSVOut_Click
    // 戻 り 値：なし
    // 処理説明：CSV出力を行う
    // **********************************************************************
    me.btnCSVOut_Click = function () {
        var data = {
            lblExhibitTermStart: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermStart"
            ).val(),
            lblExhibitTermEnd: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermEnd"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnCSVOutClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：HITNET用Excel出力ボタンクリック
    // 関 数 名：btnOutputHITNET_Click
    // 戻 り 値：なし
    // 処理説明：HITNET用Excel出力を行う
    // **********************************************************************
    me.btnOutputHITNET_Click = function () {
        var data = {
            lblExhibitTermStart: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermStart"
            ).val(),
            lblExhibitTermEnd: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermEnd"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnOutputHITNETClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.location.href = result["data"];
            } else {
                if (result["error"] == "MSG_W0003") {
                    me.clsComFnc.FncMsgBox("W0024");
                } else if (result["error"] == "MSG_W0006") {
                    me.clsComFnc.FncMsgBox("W0030");
                } else if (
                    result["error"] == "テンプレートファイルが存在しません。"
                ) {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：ロック解除クリックのイベント
    // '関 数 名：btnLock_Click
    // '戻 り 値：なし
    // '処理説明：ロック解除を行う
    // '**********************************************************************
    me.btnLock_Click = function () {
        var data = {
            lblExhibitTermStart: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermStart"
            ).val(),
            lblExhibitTermEnd: $(
                ".HMTVE070TotalKHonbu.lblExhibitTermEnd"
            ).val(),
        };
        var url = me.sys_id + "/" + me.id + "/btnLockClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["number_of_rows"] <= 0) {
                    me.clsComFnc.FncMsgBox("W0024");
                } else {
                    me.clsComFnc.FncMsgBox(
                        "I9999",
                        "ロックの解除を行いました。"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);

        $(".HMTVE070TotalKHonbu.pnlList").hide();
    };
    // 空の値をチェックする
    me.checkNull = function () {
        var $lblETStart = $(".HMTVE070TotalKHonbu.lblExhibitTermStart");
        var $lblETEnd = $(".HMTVE070TotalKHonbu.lblExhibitTermEnd");
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
        return true;
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE070TotalKHonbu = new HMTVE.HMTVE070TotalKHonbu();
    o_HMTVE_HMTVE070TotalKHonbu.load();
    o_HMTVE_HMTVE.HMTVE070TotalKHonbu = o_HMTVE_HMTVE070TotalKHonbu;
});
