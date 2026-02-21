/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容																　　　　　　　　　　　担当
 * YYYYMMDD						#ID							　　　　XXXXXX																　　　　　　　　　　　GSDL
 * 20240318        本番障害.xlsx NO5     編集ボタン追加、編集ボタンクリックで伝票入力画面に遷移             lujunxia
 * 20240322        本番障害.xlsx NO8            科目名、補助科目名を両方表示してほしい              					YIN
 * 20241125        【HD用伝票集計システム（HDKAIKEI）】仕様変更要望           伝票検索入力、パターン检索：新规/编辑dialog 既存bug             lhb
 * 20250124                   パターン選択から行追加するとフリーズする現象が出ました                                                     yin
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HDKAIKEI.HDKShiwakeInput");

HDKAIKEI.HDKShiwakeInput = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.id = "HDKShiwakeInput";
    me.sys_id = "HDKAIKEI";
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.grid_id = "#HDKShiwakeInput_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";

    //u00A0:不间断空格，结尾处不会换行显示
    //u0020:半角空格
    //u3000:全角空格
    // 20240124 YIN UPD S
    // me.blankReplace = /((\s|\u00A0|\u0020|\u3000)+$)/;
    me.blankReplace = /[\s\u00A0\u0020\u3000]+$/;
    // 20240124 YIN UPD E

    me.hidUpdDate = "";
    me.hidMode = "";
    me.hidDispNO = "";
    me.PatternID = "";
    me.BusyoCD = "";
    me.selectedData = {
        SYOHY_NO: "",
        EDA_NO: "",
        GYO_NO: "",
    };

    me.KamokuMstBlank = [];
    me.BusyoMst = [];
    me.Torihiki = [];
    me.AllData = [];

    me.option = {
        caption: "",
        rowNum: 0,
        rownumbers: true,
        multiselect: false,
        shrinkToFit: true,
    };
    me.colModel = [
        {
            label: "№",
            name: "SEQNO",
            index: "SEQNO",
            hidden: true,
        },
        {
            label: "証憑№",
            name: "SYOHY_NO",
            index: "SYOHY_NO",
            hidden: true,
        },
        {
            label: "枝№",
            name: "EDA_NO",
            index: "EDA_NO",
            hidden: true,
        },
        {
            label: "行№",
            name: "GYO_NO",
            index: "GYO_NO",
            hidden: true,
        },
        {
            label: "借方科目",
            name: "L_KAMOKU",
            index: "L_KAMOKU",
            width: 155,
            align: "left",
            sortable: false,
            // 20240322 YIN INS S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["L_KAMOKU"] !== null &&
                    rowObject["L_KAMOKU"] !== "" &&
                    rowObject["L_KOUMKU"] !== null &&
                    rowObject["L_KOUMKU"] !== ""
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["L_KAMOKU"] +
                        "<br>" +
                        rowObject["L_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["L_KAMOKU"] !== null &&
                        rowObject["L_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["L_KAMOKU"];
                    }
                    if (
                        rowObject["L_KOUMKU"] !== null &&
                        rowObject["L_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["L_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            // 20240322 YIN INS E
        },
        {
            label: "貸方科目",
            name: "R_KAMOKU",
            index: "R_KAMOKU",
            width: 155,
            align: "left",
            sortable: false,
            // 20240322 YIN INS S
            formatter: function (_cellvalue, _options, rowObject) {
                if (
                    rowObject["R_KAMOKU"] !== null &&
                    rowObject["R_KAMOKU"] !== "" &&
                    rowObject["R_KOUMKU"] !== null &&
                    rowObject["R_KOUMKU"] !== ""
                ) {
                    var detail =
                        '<div class="HDKAIKEI-jqgrid-td-margin">' +
                        rowObject["R_KAMOKU"] +
                        "<br>" +
                        rowObject["R_KOUMKU"] +
                        "</div>";
                } else {
                    var detail = "<div>";
                    if (
                        rowObject["R_KAMOKU"] !== null &&
                        rowObject["R_KAMOKU"] !== ""
                    ) {
                        detail += rowObject["R_KAMOKU"];
                    }
                    if (
                        rowObject["R_KOUMKU"] !== null &&
                        rowObject["R_KOUMKU"] !== ""
                    ) {
                        detail += rowObject["R_KOUMKU"];
                    }
                    detail += "</div>";
                }
                return detail;
            },
            // 20240322 YIN INS E
        },
        {
            label: "税込金額",
            name: "ZEIKM_GK",
            index: "ZEIKM_GK",
            width: 97,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "消費税額",
            name: "SHZEI_GK",
            index: "SHZEI_GK",
            width: 97,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "摘要",
            name: "TEKYO",
            index: "TEKYO",
            width: 360,
            align: "left",
            sortable: false,
        },
        {
            label: "選択",
            name: "SENTAKU",
            index: "SENTAKU",
            width: 90,
            align: "left",
            sortable: false,
            formatter: function (_cellvalue, options) {
                var detail =
                    "<button onclick=\"grdIchiran_SelectedIndexChanged('" +
                    options.rowId +
                    "')\" id = '" +
                    options.rowId +
                    "_btnEdit' class=\"HDKShiwakeInput btnEdit Tab Enter\" tabindex='47' style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>選択</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKShiwakeInput.HDKShiwakeInputBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKShiwakeInput.fileSelect",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKShiwakeInput.txtKeiriSyoriDT",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

    $(".HDKShiwakeInput.txtZeikm_GK").on("keydown", function (e) {
        var key = e.which;
        if (key == 13 || key == 9) {
            e.preventDefault();

            $(".ui-dialog-buttons").find(".ui-button").trigger("focus");
        }
    });
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //パターン対象部署クリック
    $(".HDKShiwakeInput.radPatternKyotu").click(function () {
        me.radPattern_CheckedChanged(1);
    });
    $(".HDKShiwakeInput.radPatternBusyo").click(function () {
        me.radPattern_CheckedChanged(2);
    });
    //修正前表示ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnSyuseiMaeDisp").click(function () {
        me.btnSyuseiMaeDisp_Click();
    });
    //最新表示ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnSaishinDisp").click(function () {
        me.btnSaishinDisp_Click();
    });
    //行追加ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnAdd").click(function () {
        me.btnAdd_Click();
    });
    //行変更ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnUpdate").click(function () {
        me.btnUpdate_Click();
    });
    //行削除ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox("QY017");
    });
    //クリアﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnClear").click(function () {
        me.btnClear_Click();
    });
    //表示されている仕訳をパターンとして登録ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnPatternTrk").click(function () {
        me.btnPatternTrk_Click();
    });
    //全確定ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnKakutei").click(function () {
        me.btnKakutei_Click();
    });
    //登録ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnPtnInsert").click(function () {
        me.btnPtnInsert_Click("btnPtnInsert");
    });
    //更新ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnPtnUpdate").click(function () {
        me.btnPtnInsert_Click("btnPtnUpdate");
    });
    //全削除ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnAllDelete").click(function () {
        me.btnAllDelete_Click();
    });
    //削除ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnPtnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnPtnDelete_Click;
        me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
    });
    //閉じるﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnClose").click(function () {
        $(".HDKShiwakeInput.body").dialog("close");
    });
    // //社員検索ﾎﾞﾀﾝクリック
    // $(".HDKShiwakeInput.btnSyainSearch").click(function () {
    // 	me.openSearchDialog("btnSyainSearch");
    // });
    //[借方]検索①ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnLKamokuSearch").click(function () {
        me.openSearchDialog("btnLKamokuSearch");
    });
    //[借方]検索②ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnLBusyoSearch").click(function () {
        me.openSearchDialog("btnLBusyoSearch");
    });
    //[貸方]検索①ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnRKamokuSearch").click(function () {
        me.openSearchDialog("btnRKamokuSearch");
    });
    //[貸方]検索②ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnRBusyoSearch").click(function () {
        me.openSearchDialog("btnRBusyoSearch");
    });
    //取引先検索ﾎﾞﾀﾝクリック
    $(".HDKShiwakeInput.btnTorihikiSearch").click(function () {
        me.openSearchDialog("btnTorihikiSearch");
    });
    //添付ファイルクリック
    $(".HDKShiwakeInput.fileSelect").click(function () {
        if (me.selectedData.SYOHY_NO == "") {
            me.clsComFnc.FncMsgBox("W9999", "行を選択して下さい。");
            return;
        }
        me.openSearchDialog("HDKAttachment");
    });
    //パターン選択change
    $(".HDKShiwakeInput.ddlPatternSel").change(function () {
        me.ddlPatternSel_SelectedIndexChanged();
    });
    //消費税区分[借方]change
    $(".HDKShiwakeInput.ddlLSyohizeiKbn").change(function () {
        me.ddlLSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税区分[貸方]change
    $(".HDKShiwakeInput.ddlRSyohizeiKbn").change(function () {
        me.ddlRSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税率[借方]change
    $(".HDKShiwakeInput.ddlLSyouhiKbn").change(function () {
        me.ddlLSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税率[貸方]change
    $(".HDKShiwakeInput.ddlRSyouhiKbn").change(function () {
        me.ddlRSyohizeiKbn_SelectedIndexChanged();
    });
    //摘要に英数字記号は半角に変換
    $(".HDKShiwakeInput.txtTekyo").change(function () {
        me.Tekiyo_TextChanged(this);
    });
    //科目[借方]change
    $(".HDKShiwakeInput.txtLKamokuCD").change(function () {
        me.txtLKamokuCD_TextChanged();
    });
    $(".HDKShiwakeInput.txtLKomokuCD").change(function () {
        me.txtLKamokuCD_TextChanged();
    });
    //科目[貸方]change
    $(".HDKShiwakeInput.txtRKamokuCD").change(function () {
        me.txtRKamokuCD_TextChanged();
    });
    $(".HDKShiwakeInput.txtRKomokuCD").change(function () {
        me.txtRKamokuCD_TextChanged();
    });
    //部署change
    $(".HDKShiwakeInput.txtLBusyoCD").change(function () {
        me.txtLBusyoCD_TextChanged("txtLBusyoCD");
    });
    $(".HDKShiwakeInput.txtRbusyoCD").change(function () {
        me.txtRBusyoCD_TextChanged("txtRbusyoCD");
    });
    // 取引先
    $(".HDKShiwakeInput.lblKensakuCD").change(function () {
        me.txtTorihiki_TextChanged();
    });
    //税込金額change
    $(".HDKShiwakeInput.txtZeikm_GK").change(function () {
        $(".HDKShiwakeInput.txtZeikm_GK").val(
            me.toMoney($(this), $(".HDKShiwakeInput.txtZeikm_GK").val())
        );
        me.txtZeikm_GK_TextChanged();
    });
    //税抜金額change
    $(".HDKShiwakeInput.lblZeink_GK").change(function () {
        $(".HDKShiwakeInput.lblZeink_GK").text(
            me.toMoney($(this), $(".HDKShiwakeInput.lblZeink_GK").text())
        );
    });
    //消費税金額change
    $(".HDKShiwakeInput.lblSyohizei").change(function () {
        $(".HDKShiwakeInput.lblSyohizei").text(
            me.toMoney($(this), $(".HDKShiwakeInput.lblSyohizei").text())
        );
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        try {
            base_init_control();

            if (window.ActiveXObject || "ActiveXObject" in window) {
                if ($(window).height() <= 950) {
                    // 画面内容较多，IE显示不全，追加纵向滚动条
                    $(".HDKAIKEI.HDKAIKEI-layout-center").css(
                        "overflow-y",
                        "scroll"
                    );
                }
            }

            //ページロード
            me.Page_Load();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：ページロード
     '関 数 名：Page_Load
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：ページ初期化
     '**********************************************************************
     */
    me.Page_Load = function () {
        try {
            var strMode = "",
                strDispNO = "",
                strAllSyohy_No = "",
                strPattern_NO = "",
                strSyohy_NO = "",
                strEda_No = "";
            strDispNO = $("#DISP_NO").text();
            me.hidDispNO = strDispNO;
            //前画面情報を取得
            strMode = $("#MODE").html();
            strAllSyohy_No = $("#SYOHY_NO").html();
            strPattern_NO = $("#PATTERN_NO").html();
            if (me.clsComFnc.FncNv(strAllSyohy_No) != "") {
                strSyohy_NO = strAllSyohy_No.substring(0, 15);
                strEda_No = strAllSyohy_No.substring(15, 17);
            }
            me.PatternID = gdmz.SessionPatternID;

            var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
            var data = {
                strDispNO: strDispNO,
                strSyohy_NO: strSyohy_NO,
                strMode: strMode,
                strEda_No: strEda_No,
                strPattern_NO: strPattern_NO,
                memo:
                    me.PatternID === me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                    me.PatternID === me.HDKAIKEI.CONST_HONBU_PTN_NO
                        ? true
                        : false,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    $(".HDKShiwakeInput.btnPtnDelete").button("disable");
                    $(".HDKShiwakeInput.btnPtnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("disable");
                    $(".HDKShiwakeInput.btnPtnInsert").button("disable");
                    $(".HDKShiwakeInput.btnKakutei").button("disable");
                    $(".HDKShiwakeInput.btnPatternTrk").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnAdd").button("disable");
                    $(".HDKShiwakeInput.fileSelect").button("disable");

                    $(".HDKShiwakeInput.btnClose").hide();

                    if (
                        result["error"] != "W0025" &&
                        result["error"] != "W0026"
                    ) {
                        if (result["data"]["message"]) {
                            me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                        return;
                    }
                }
                // 該当データが１件以上の場合、添付ファイル：あり と表示
                $(".HDKShiwakeInput.hasFileFlg").text(
                    result["data"]["HDKAttachment"]
                );
                me.BusyoCD = result["BusyoCD"];
                me.BusyoMst = result["data"]["BusyoMst"];
                me.KamokuMstBlank = result["data"]["KamokuMstBlank"];
                me.Torihiki = result["data"]["Torihiki"];
                //モードの設定
                if (strMode == "" || strMode == undefined) {
                    $(".HDKShiwakeInput.btnClose").hide();
                    //メニューから開かれた場合は新規モードに設定する
                    me.hidMode = "1";
                } else {
                    $(".HDKShiwakeInput.btnClose").show();
                    var userAgent = navigator.userAgent;
                    var isIE =
                        userAgent.indexOf("compatible") > -1 &&
                        userAgent.indexOf("MSIE") > -1;
                    var isIE11 =
                        userAgent.indexOf("Trident") > -1 &&
                        userAgent.indexOf("rv:11.0") > -1;
                    $(".HDKShiwakeInput.body").dialog({
                        autoOpen: false,
                        width: me.ratio === 1.5 ? 1085 : 1190,
                        height:
                            strDispNO == "103"
                                ? me.ratio === 1.5
                                    ? 290
                                    : 320
                                : isIE || isIE11
                                ? me.ratio === 1.5
                                    ? 500
                                    : 670
                                : me.ratio === 1.5
                                ? 540
                                : 680,
                        modal: true,
                        title: "仕訳伝票入力",
                        open: function () {},
                        close: function () {
                            me.before_close();
                            $(".HDKShiwakeInput.body").remove();
                        },
                    });
                    $(".HDKShiwakeInput.body").dialog("open");
                    //メニュー以外から開かれた場合は指定されたモードをセットする
                    me.hidMode = strMode;
                }
                $(".HDKShiwakeInput.clearLabel").height(
                    $(".HDKShiwakeInput.lblZeikm_GK_NM").height()
                );
                //画面項目をクリアする
                me.subFormClear();
                //ボタンを使用不可にする
                me.DpyInpNewButtonEnabled("99");
                //ドロップダウンリストを設定する,パターンのドロップダウンリストを設定する
                me.DropDownListSet(result);

                //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                switch (me.PatternID) {
                    case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                    case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                        $(".HDKShiwakeInput.pnlTenpo").css("display", "none");
                        $(".HDKShiwakeInput.pnlHonbu").css(
                            "display",
                            "table-row"
                        );
                        break;
                    default:
                        //メモ欄を設定する
                        me.MemoSet(result["data"]["MemoTbl"]);

                        $(".HDKShiwakeInput.pnlTenpo").css("display", "block");
                        $(".HDKShiwakeInput.pnlHonbu").css("display", "none");
                        break;
                }
                switch (strDispNO) {
                    //伝票検索画面又は全銀協・OBC再出力画面から開かれた場合
                    case "ReOut4OBC":
                    case "ReOut4ZenGin":
                    case "100":
                        $(".HDKShiwakeInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(true);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(false);
                        //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                        $(".HDKShiwakeInput.txtKeiriSyoriDT").datepicker(
                            "disable"
                        );
                        switch (me.PatternID) {
                            case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                            case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                                $(".HDKShiwakeInput.btnPatternTrk").show();
                                $(".HDKShiwakeInput.btnPatternTrk").button(
                                    "enable"
                                );
                                break;
                            default:
                                $(".HDKShiwakeInput.btnPatternTrk").hide();
                                break;
                        }
                        switch (strMode) {
                            //新規作成の場合
                            case "1":
                                //ボタンの活性・不活性を決める(新規の場合)
                                me.DpyInpNewButtonEnabled("1");
                                //修正前表示ボタンを不活性にする
                                $(".HDKShiwakeInput.btnPatternTrk").button(
                                    "enable"
                                );

                                $(me.grid_id).jqGrid("clearGridData");

                                gdmz.common.jqgrid.init(
                                    me.grid_id,
                                    me.g_url,
                                    me.colModel,
                                    "",
                                    "",
                                    me.option
                                );
                                me.setTableSize();
                                $(me.grid_id + "_rn").html("№");
                                $(me.grid_id).jqGrid("bindKeys");

                                if (
                                    !$(".HDKShiwakeInput.txtZeikm_GK").is(
                                        ":disabled"
                                    )
                                ) {
                                    $(".HDKShiwakeInput.txtZeikm_GK").trigger(
                                        "focus"
                                    );
                                }
                                break;
                            //修正・削除の場合
                            case "2":
                                var data = {
                                    lblSyohy_no: strAllSyohy_No,
                                };
                                var complete_fun = function () {
                                    //該当データが削除された可能性があります。最新の情報を確認して下さい
                                    //他のユーザーにより更新されています。最新の情報を確認してください。
                                    if (
                                        result["error"] == "W0026" ||
                                        result["error"] == "W0025"
                                    ) {
                                        $(me.grid_id).jqGrid("clearGridData");
                                        me.clsComFnc.FncMsgBox(result["error"]);
                                        return;
                                    }
                                    //証憑№のチェックを行う
                                    //証憑№を表示する
                                    $(".HDKShiwakeInput.lblSyohy_no").val(
                                        strAllSyohy_No
                                    );
                                    //一覧に表示する
                                    var IchiranTbl = $(me.grid_id).jqGrid(
                                        "getRowData"
                                    );
                                    //合計件数、合計金額、合計消費税額を計算する
                                    var lngKingaku = 0,
                                        lngSyohizei = 0;
                                    for (
                                        var i = 0;
                                        i < IchiranTbl.length;
                                        i++
                                    ) {
                                        lngKingaku += me.clsComFnc.FncNz(
                                            Number(IchiranTbl[i]["ZEIKM_GK"])
                                        );
                                        lngSyohizei += me.clsComFnc.FncNz(
                                            Number(IchiranTbl[i]["SHZEI_GK"])
                                        );
                                    }
                                    //合計件数、合計金額、合計消費税額を表示する
                                    $(".HDKShiwakeInput.lblKensu").val(
                                        me.toMoney(
                                            $(".HDKShiwakeInput.lblKensu"),
                                            IchiranTbl.length
                                        )
                                    );
                                    $(".HDKShiwakeInput.lblZeikomiGoukei").val(
                                        me.toMoney(
                                            $(
                                                ".HDKShiwakeInput.lblZeikomiGoukei"
                                            ),
                                            lngKingaku
                                        )
                                    );
                                    $(".HDKShiwakeInput.lblSyohizeiGoukei").val(
                                        me.toMoney(
                                            $(
                                                ".HDKShiwakeInput.lblSyohizeiGoukei"
                                            ),
                                            lngSyohizei
                                        )
                                    );
                                    //修正前データを取得する
                                    var SyuseiMaeTbl =
                                        result["data"]["SyuseiMaeTbl"];
                                    if (SyuseiMaeTbl.length > 0) {
                                        if (
                                            me.clsComFnc.FncNv(
                                                SyuseiMaeTbl[0]["SYOHY_NO"]
                                            ) == ""
                                        ) {
                                            //修正前データが存在しない場合
                                            //修正前表示ボタンを不活性にする
                                            $(
                                                ".HDKShiwakeInput.btnSyuseiMaeDisp"
                                            ).button("disable");
                                        } else {
                                            //修正前データが存在する場合
                                            //修正前表示ボタンを活性にする
                                            $(
                                                ".HDKShiwakeInput.btnSyuseiMaeDisp"
                                            ).button("enable");
                                        }
                                    }
                                    //該当枝№チェック
                                    if (
                                        result["data"]["EdaNoChkTbl"].length ==
                                        0
                                    ) {
                                        //該当データが削除された可能性があります。最新の情報を確認して下さい
                                        me.clsComFnc.FncMsgBox("W0026");
                                        return;
                                    }
                                    me.hidUpdDate = me.clsComFnc.FncNv(
                                        result["data"]["EdaNoChkTbl"][0][
                                            "UPD_DATE"
                                        ]
                                    );
                                    //伝票検索画面からの遷移の場合、モードの設定を行う
                                    if (strDispNO == "100") {
                                        //既に全銀協・OBC出力されている場合
                                        //表示モードを指定する
                                        var DispModeTbl =
                                            result["data"]["DispModeTbl"];
                                        if (
                                            me.clsComFnc.FncNv(
                                                DispModeTbl[0]["CSV_OUT_FLG"] ==
                                                    "1"
                                            ) ||
                                            me.clsComFnc.FncNv(
                                                DispModeTbl[0][
                                                    "XLSX_OUT_FLG"
                                                ] == "1"
                                            ) ||
                                            (me.clsComFnc.FncNv(
                                                DispModeTbl[0][
                                                    "HONBU_SYORIZUMI_FLG"
                                                ] == "1"
                                            ) &&
                                                me.PatternID !=
                                                    me.HDKAIKEI
                                                        .CONST_ADMIN_PTN_NO &&
                                                me.PatternID !=
                                                    me.HDKAIKEI
                                                        .CONST_HONBU_PTN_NO)
                                        ) {
                                            //*****参照モードで表示する*****
                                            //'ボタンを使用不可にする
                                            me.DpyInpNewButtonEnabled("9");
                                            //画面項目を不活性にする
                                            me.FormEnabled(false);
                                            //参照モードの設定
                                            me.hidMode = "9";
                                            return;
                                        } else if (
                                            me.clsComFnc.FncNv(
                                                DispModeTbl[0][
                                                    "PRINT_OUT_FLG"
                                                ] == "1"
                                            ) &&
                                            me.PatternID !=
                                                me.HDKAIKEI
                                                    .CONST_ADMIN_PTN_NO &&
                                            me.PatternID !=
                                                me.HDKAIKEI.CONST_HONBU_PTN_NO
                                        ) {
                                            //一部参照モードで表示する
                                            //ボタンを使用不可にする
                                            me.DpyInpNewButtonEnabled("8");
                                            //画面項目を不活性にする
                                            me.FormEnabled(false);
                                            //参照モードの設定
                                            me.hidMode = "8";
                                            return;
                                        }
                                    }
                                    //ボタンの活性・不活性を決める(修正の場合)
                                    me.DpyInpNewButtonEnabled("2");
                                    //99行を超える場合は行追加ボタンを不活性に設定する
                                    // 20241125 lhb upd s
                                    // if (IchiranTbl.length >= 10) {
                                    if (IchiranTbl.length >= 99) {
                                        // 20241125 lhb upd e
                                        $(".HDKShiwakeInput.btnAdd").button(
                                            "disable"
                                        );
                                    }

                                    if (
                                        !$(".HDKShiwakeInput.txtZeikm_GK").is(
                                            ":disabled"
                                        )
                                    ) {
                                        $(
                                            ".HDKShiwakeInput.txtZeikm_GK"
                                        ).trigger("focus");
                                    }
                                };

                                $(me.grid_id).jqGrid("clearGridData");

                                gdmz.common.jqgrid.showWithMesg(
                                    me.grid_id,
                                    me.g_url,
                                    me.colModel,
                                    "",
                                    "",
                                    me.option,
                                    data,
                                    complete_fun
                                );
                                me.setTableSize();
                                $(me.grid_id + "_rn").html("№");
                                $(me.grid_id).jqGrid("bindKeys");
                                break;
                            default:
                                break;
                        }
                        break;
                    //パターン検索画面から表示された場合
                    case "103":
                        $(".HDKShiwakeInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(false);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(true);
                        //経理処理日を非表示にする
                        $(".HDKShiwakeInput.txtKeiriSyoriDT").css(
                            "visibility",
                            "hidden"
                        );
                        $(".HDKShiwakeInput.lblKeiriSyoriDT_NM").css(
                            "visibility",
                            "hidden"
                        );
                        //仕訳伝票入力用項目を非表示にする
                        me.ForPatternVisible();
                        $(".HDKShiwakeInput.btnPtnDelete").button("disable");
                        $(".HDKShiwakeInput.btnPtnInsert").button("disable");
                        $(".HDKShiwakeInput.btnPtnUpdate").button("disable");
                        switch (me.clsComFnc.FncNv(strMode)) {
                            //新規の場合
                            case "1":
                                $(".HDKShiwakeInput.btnPtnDelete").button(
                                    "disable"
                                );
                                $(".HDKShiwakeInput.btnPtnInsert").button(
                                    "enable"
                                );
                                $(".HDKShiwakeInput.btnPtnInsert").text("登録");
                                $(".HDKShiwakeInput.btnPtnUpdate").hide();
                                break;
                            //編集の場合
                            case "2":
                                var PatternTbl =
                                    result["data"]["PatternTbl103"];
                                if (PatternTbl.length == 0) {
                                    //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                                    me.clsComFnc.FncMsgBox("W0026");
                                    return;
                                }
                                me.hidPatternNO = strPattern_NO;
                                //パターンデータを画面項目にセットする
                                me.DataFormSet("103", PatternTbl);
                                //ボタンを活性にする
                                $(".HDKShiwakeInput.btnPtnDelete").button(
                                    "enable"
                                );
                                $(".HDKShiwakeInput.btnPtnInsert").button(
                                    "enable"
                                );
                                $(".HDKShiwakeInput.btnPtnInsert").text(
                                    "新規登録"
                                );
                                $(".HDKShiwakeInput.btnPtnUpdate").show();
                                $(".HDKShiwakeInput.btnPtnUpdate").button(
                                    "enable"
                                );
                        }
                        me.radPatternBusyo_CheckedChanged();
                        $(".HDKShiwakeInput.txtTekyo").trigger("focus");
                        break;
                    //それ以外から表示された場合
                    default:
                        $(me.grid_id).jqGrid("clearGridData");
                        gdmz.common.jqgrid.init(
                            me.grid_id,
                            me.g_url,
                            me.colModel,
                            "",
                            "",
                            me.option
                        );
                        me.setTableSize();
                        $(me.grid_id + "_rn").html("№");
                        $(me.grid_id).jqGrid("bindKeys");
                        //マスターページ設定  メニューから表示された場合はこれが必要
                        $(".HDKShiwakeInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(true);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(false);
                        //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                        $(".HDKShiwakeInput.txtKeiriSyoriDT").datepicker(
                            "disable"
                        );
                        switch (me.PatternID) {
                            case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                            case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                                $(".HDKShiwakeInput.btnPatternTrk").show();
                                $(".HDKShiwakeInput.btnPatternTrk").button(
                                    "enable"
                                );
                                break;
                            default:
                                $(".HDKShiwakeInput.btnPatternTrk").hide();
                                break;
                        }
                        //ボタンの活性・不活性を決める(新規の場合)
                        me.DpyInpNewButtonEnabled("1");

                        if (
                            !$(".HDKShiwakeInput.txtZeikm_GK").is(":disabled")
                        ) {
                            $(".HDKShiwakeInput.txtZeikm_GK").trigger("focus");
                        }

                        break;
                }

                //[件]レイアウト設定
                var width =
                    $("#HDKShiwakeInput_sprList_R_KAMOKU").width() +
                    $("#HDKShiwakeInput_sprList_SEQNO").width() -
                    82;
                $(".HDKShiwakeInput#GOUKEITBL").css(
                    "margin-left",
                    width + "px"
                );
                $(".HDKShiwakeInput.lblKensu").width(
                    $("#HDKShiwakeInput_sprList_R_KAMOKU").width() / 2
                );
                $(".HDKShiwakeInput.lblZeikomiGoukei").width(
                    $("#HDKShiwakeInput_sprList_ZEIKM_GK").width() - 3
                );
                $(".HDKShiwakeInput.lblSyohizeiGoukei").width(
                    $("#HDKShiwakeInput_sprList_SHZEI_GK").width() - 3
                );
                $(".HDKShiwakeInput.lblZeikomiGoukei").css(
                    "margin-left",
                    $("#HDKShiwakeInput_sprList_R_KAMOKU").width() / 2 -
                        30 +
                        "px"
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
            //ボタンを使用不可にする
            me.DpyInpNewButtonEnabled("99");
        }
    };
    /*
     '**********************************************************************
     '処 理 名：修正前データの表示を行う
     '関 数 名：btnSyuseiMaeDisp_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：修正前データの表示を行う
     '**********************************************************************
     */
    me.btnSyuseiMaeDisp_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/" + "btnSyuseiMaeDisp_Click";
            var data = {
                lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    var SYUSEIMAETBL = result["data"]["SYUSEIMAETBL"][0];

                    $(".HDKShiwakeInput.lblSyohy_no").val(
                        me.clsComFnc.FncNv(SYUSEIMAETBL["SYOHY_NO"]) +
                            me.clsComFnc.FncNv(SYUSEIMAETBL["EDA_NO"])
                    );

                    $(".HDKShiwakeInput.btnSaishinDisp").show();
                    //画面項目をクリアする
                    me.subFormClear(true);
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").get(
                        0
                    ).selectedIndex = 0;
                    //*****参照モードで表示する*****
                    //ボタンを使用不可にする
                    me.DpyInpNewButtonEnabled("9");
                    $(".HDKShiwakeInput.btnKakutei").button("disable");

                    //画面項目を不活性にする
                    me.FormEnabled(false);
                    //一覧に表示する
                    var data = {
                        lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                            .val()
                            .replace(me.blankReplace, ""),
                    };
                    var complete_fun = function () {
                        var IchiranTbl = $(me.grid_id).jqGrid("getRowData");
                        //合計件数、合計金額、合計消費税額を計算する
                        var lngKingaku = 0,
                            lngSyohizei = 0;
                        for (var i = 0; i < IchiranTbl.length; i++) {
                            lngKingaku += me.clsComFnc.FncNz(
                                Number(IchiranTbl[i]["ZEIKM_GK"])
                            );
                            lngSyohizei += me.clsComFnc.FncNz(
                                Number(IchiranTbl[i]["SHZEI_GK"])
                            );
                        }
                        //合計件数、合計金額、合計消費税額を表示する
                        $(".HDKShiwakeInput.lblKensu").val(
                            me.toMoney(
                                $(".HDKShiwakeInput.lblKensu"),
                                IchiranTbl.length
                            )
                        );
                        $(".HDKShiwakeInput.lblZeikomiGoukei").val(
                            me.toMoney(
                                $(".HDKShiwakeInput.lblZeikomiGoukei"),
                                lngKingaku
                            )
                        );
                        $(".HDKShiwakeInput.lblSyohizeiGoukei").val(
                            me.toMoney(
                                $(".HDKShiwakeInput.lblSyohizeiGoukei"),
                                lngSyohizei
                            )
                        );
                    };
                    gdmz.common.jqgrid.reloadMessage(
                        me.grid_id,
                        data,
                        complete_fun
                    );
                    //修正前データを取得する
                    var SyuseiMaeTbl = result["data"]["SyuseiMaeTbl"];
                    if (SyuseiMaeTbl.length > 0) {
                        if (
                            me.clsComFnc.FncNz(SyuseiMaeTbl[0]["SYOHY_NO"]) ==
                            ""
                        ) {
                            //修正前データが存在しない場合
                            //修正前表示ボタンを不活性にする
                            $(".HDKShiwakeInput.btnSyuseiMaeDisp").button(
                                "disable"
                            );
                        } else {
                            //修正前データが存在する場合
                            //修正前表示ボタンを活性にする
                            $(".HDKShiwakeInput.btnSyuseiMaeDisp").button(
                                "enable"
                            );
                        }
                    }
                } else {
                    //修正前データ件数
                    if (result["error"] == "W0026") {
                        //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                        me.clsComFnc.FncMsgBox("W0026");
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：最新データの表示を行う
     '関 数 名：btnSaishinDisp_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：最新データの表示を行う
     '**********************************************************************
     */
    me.btnSaishinDisp_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/" + "btnSaishinDisp_Click";
            var data = {
                lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "W0026") {
                        //修正前データ件数
                        me.clsComFnc.FncMsgBox("W0026");
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return;
                }
                var NEWTBL = result["data"]["NEWTBL"];
                $(".HDKShiwakeInput.lblSyohy_no").val(
                    $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "")
                        .substring(0, 15) +
                        me.clsComFnc.FncNv(NEWTBL[0]["EDA_NO"])
                );

                //画面項目をクリアする
                me.subFormClear(true);
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
                $(".HDKShiwakeInput.ddlLSyouhiKbn").get(0).selectedIndex = 0;
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
                $(".HDKShiwakeInput.ddlRSyouhiKbn").get(0).selectedIndex = 0;

                //参照モードと一部参照モード(削除は可能)の場合は画面項目は不活性
                if (me.hidMode == "9" || me.hidMode == "8") {
                } else {
                    me.FormEnabled(true);
                }

                //ボタンを使用不可にする
                me.DpyInpNewButtonEnabled("99");

                //一覧に表示する
                var data = {
                    lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                };
                var complete_fun = function () {
                    var IchiranTbl = $(me.grid_id).jqGrid("getRowData");
                    //合計件数、合計金額、合計消費税額を計算する
                    var lngKingaku = 0,
                        lngSyohizei = 0;
                    for (var i = 0; i < IchiranTbl.length; i++) {
                        lngKingaku += me.clsComFnc.FncNz(
                            Number(IchiranTbl[i]["ZEIKM_GK"])
                        );
                        lngSyohizei += me.clsComFnc.FncNz(
                            Number(IchiranTbl[i]["SHZEI_GK"])
                        );
                    }
                    //合計件数、合計金額、合計消費税額を表示する
                    $(".HDKShiwakeInput.lblKensu").val(
                        me.toMoney(
                            $(".HDKShiwakeInput.lblKensu"),
                            IchiranTbl.length
                        )
                    );
                    $(".HDKShiwakeInput.lblZeikomiGoukei").val(
                        me.toMoney(
                            $(".HDKShiwakeInput.lblZeikomiGoukei"),
                            lngKingaku
                        )
                    );
                    $(".HDKShiwakeInput.lblSyohizeiGoukei").val(
                        me.toMoney(
                            $(".HDKShiwakeInput.lblSyohizeiGoukei"),
                            lngSyohizei
                        )
                    );

                    //修正前データを取得する
                    var SyuseiMaeTbl = result["data"]["SyuseiMaeTbl"];
                    if (SyuseiMaeTbl.length > 0) {
                        if (
                            me.clsComFnc.FncNz(SyuseiMaeTbl[0]["SYOHY_NO"]) ==
                            ""
                        ) {
                            //修正前データが存在しない場合
                            //修正前表示ボタンを不活性にする
                            $(".HDKShiwakeInput.btnSyuseiMaeDisp").button(
                                "disable"
                            );
                        } else {
                            //修正前データが存在する場合
                            //修正前表示ボタンを活性にする
                            $(".HDKShiwakeInput.btnSyuseiMaeDisp").button(
                                "enable"
                            );
                        }
                    }

                    //ボタンを使用可にする
                    me.DpyInpNewButtonEnabled(me.hidMode);

                    //明細が99行以上ある場合は、追加ボタンを不活性にする
                    // 20241125 lhb upd s
                    // if (IchiranTbl.length >= 10) {
                    if (IchiranTbl.length >= 99) {
                        // 20241125 lhb upd e
                        $(".HDKShiwakeInput.btnAdd").button("disable");
                    }
                    $(".HDKShiwakeInput.btnSaishinDisp").hide();
                };
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id,
                    data,
                    complete_fun
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターン選択
     '関 数 名：ddlPatternSel_SelectedIndexChanged
     '処理説明：選択されたパターンによって仕訳を展開する
     '**********************************************************************
     */
    me.ddlPatternSel_SelectedIndexChanged = function () {
        try {
            //パターン選択されていない場合
            if ($(".HDKShiwakeInput.ddlPatternSel").get(0).selectedIndex == 0) {
                return;
            }
            var url =
                me.sys_id +
                "/" +
                me.id +
                "/" +
                "ddlPatternSel_SelectedIndexChanged";
            var data = {
                ddlPatternSel: $(".HDKShiwakeInput.ddlPatternSel")
                    .val()
                    .replace(me.blankReplace, ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //画面項目をクリアする
                me.subFormClear(true);
                //選択された仕訳データを画面項目にセットする
                me.DataFormSet("101", result["data"]["PATTERNTBL"]);
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：行追加を行う
     '関 数 名：btnAdd_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：行追加処理(入力チェック・確認メッセージの表示を行う)
     '**********************************************************************
     */
    me.btnAdd_Click = function () {
        try {
            $(".HDKShiwakeInput.txtLKamokuCD").val(
                $.trim($(".HDKShiwakeInput.txtLKamokuCD").val())
            );
            $(".HDKShiwakeInput.txtLKomokuCD").val(
                $.trim($(".HDKShiwakeInput.txtLKomokuCD").val())
            );
            $(".HDKShiwakeInput.txtRKamokuCD").val(
                $.trim($(".HDKShiwakeInput.txtRKamokuCD").val())
            );
            $(".HDKShiwakeInput.txtRKomokuCD").val(
                $.trim($(".HDKShiwakeInput.txtRKomokuCD").val())
            );

            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.txtZeikm_GK_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();

            //入力チェックを行う
            if (me.fncInputCheck(true, "CMDEVENTINSERT") == true) {
                var url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
                var data = {
                    lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLBusyoCD: $(".HDKShiwakeInput.txtLBusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HDKShiwakeInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    // 20241125 lhb upd s
                    // if (
                    //     result["data"]["CheckTbl"] &&
                    //     result["data"]["CheckTbl"].length > 10
                    // ) {
                    if (
                        result["data"]["CheckTbl"] &&
                        result["data"]["CheckTbl"].length > 99
                    ) {
                        // me.clsComFnc.FncMsgBox(
                        //     "W9999",
                        //     "10行を超える仕訳を登録することは出来ません！"
                        // );
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "99行を超える仕訳を登録することは出来ません！"
                        );
                        // 20241125 lhb upd e
                        return;
                    }
                    FncGetBusyoMstValueCheck(result["data"], "cmdEventInsert");
                };
                me.ajax.send(url, data, 0);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    function FncGetBusyoMstValueCheck(data, flag) {
        try {
            var strSyozokuTenpo = "",
                strKariTenpo = "",
                strKashiTenpo = "";
            //** 名称取得
            for (var index in data["strSyozokuTenpo"]) {
                if (data["strSyozokuTenpo"][index]["BUSYO_CD"] == me.BusyoCD) {
                    strSyozokuTenpo =
                        data["strSyozokuTenpo"][index]["BUSYO_NM"];
                    break;
                }
            }
            for (var index in data["strKariTenpo"]) {
                if (
                    data["strKariTenpo"][index]["BUSYO_CD"] ==
                    $(".HDKShiwakeInput.txtLBusyoCD")
                        .val()
                        .replace(me.blankReplace, "")
                ) {
                    strKariTenpo = data["strKariTenpo"][index]["BUSYO_NM"];
                    break;
                }
            }
            for (var index in data["strKashiTenpo"]) {
                if (
                    data["strKashiTenpo"][index]["BUSYO_CD"] ==
                    $(".HDKShiwakeInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, "")
                ) {
                    strKashiTenpo = data["strKashiTenpo"][index]["BUSYO_NM"];
                    break;
                }
            }
            //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
            ) {
                if (flag == "cmdEventUpdate") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        me.cmdEvent_Click(flag);
                    };
                    me.clsComFnc.FncMsgBox("QY016");
                } else {
                    me.cmdEvent_Click(flag);
                }
            } else {
                if (
                    strKariTenpo == strSyozokuTenpo ||
                    strKashiTenpo == strSyozokuTenpo
                ) {
                    if (flag == "cmdEventUpdate") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdEvent_Click(flag);
                        };
                        me.clsComFnc.FncMsgBox("QY016");
                    } else {
                        me.cmdEvent_Click(flag);
                    }
                } else {
                    if (flag == "cmdEventUpdate") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdEvent_Click(flag);
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY999",
                            "借方にも貸方にも所属店舗が含まれておりませんが、このまま行変更を行いますか？"
                        );
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdEvent_Click(flag);
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY999",
                            "借方にも貸方にも所属店舗が含まれておりませんが、このまま行追加を行いますか？"
                        );
                    }
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    }

    /*
     '**********************************************************************
     '処 理 名：行修正を行う
     '関 数 名：btnUpdate_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：行修正処理(入力チェック・確認メッセージの表示を行う)
     '**********************************************************************
     */
    me.btnUpdate_Click = function () {
        try {
            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();

            //入力チェックを行う
            if (me.fncInputCheck(true, "CMDEVENTUPDATE") == true) {
                //** 名称取得
                var url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
                var data = {
                    txtLBusyoCD: $(".HDKShiwakeInput.txtLBusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HDKShiwakeInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    FncGetBusyoMstValueCheck(result["data"], "cmdEventUpdate");
                };
                me.ajax.send(url, data, 0);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：行削除を行う
     '関 数 名：btnDelete_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：行修正処理(入力チェック・確認メッセージの表示を行う)
     '**********************************************************************
     */
    me.btnDelete_Click = function () {
        try {
            me.cmdEvent_Click("cmdEventDelete");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：行追加・行修正・行削除を行う
     '関 数 名：cmdEvent_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：ＤＢへの追加・修正・削除処理を行う
     '**********************************************************************
     */
    me.cmdEvent_Click = function (sender) {
        try {
            var strSEQNO = "";
            var url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click";
            if (sender.toUpperCase() == "CMDEVENTALLDELETE") {
                var data = {
                    strSEQNO: strSEQNO,
                    CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
                    CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
                    lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                };
            } else {
                var data = {
                    strSEQNO: strSEQNO,
                    CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
                    CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
                    lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtZeikm_GK: $(".HDKShiwakeInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    lblZeink_GK: $(".HDKShiwakeInput.lblZeink_GK")
                        .text()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    lblSyohizei: $(".HDKShiwakeInput.lblSyohizei")
                        .text()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    txtTekyo: $(".HDKShiwakeInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKamokuCD: $(".HDKShiwakeInput.txtLKamokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKomokuCD: $(".HDKShiwakeInput.txtLKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLBusyoCD: $(".HDKShiwakeInput.txtLBusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    ddlLSyohizeiKbn: $(
                        ".HDKShiwakeInput.ddlLSyohizeiKbn"
                    ).val(),
                    ddlLSyouhiKbn: $(".HDKShiwakeInput.ddlLSyouhiKbn").val(),
                    txtRKamokuCD: $(".HDKShiwakeInput.txtRKamokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKomokuCD: $(".HDKShiwakeInput.txtRKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HDKShiwakeInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    ddlRSyohizeiKbn: $(
                        ".HDKShiwakeInput.ddlRSyohizeiKbn"
                    ).val(),
                    ddlRSyouhiKbn: $(".HDKShiwakeInput.ddlRSyouhiKbn").val(),
                    txtKeiriSyoriDT: $(".HDKShiwakeInput.txtKeiriSyoriDT")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/\//g, ""),
                    lblKensakuCD: $(".HDKShiwakeInput.lblKensakuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    lblKensakuNM: $(".HDKShiwakeInput.lblKensakuNM")
                        .val()
                        .replace(me.blankReplace, ""),
                };
            }
            //新規の証憑登録の場合
            data.fncFukanzenCheck = 0;
            if (
                $(".HDKShiwakeInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, "") == "" ||
                me.hidUpdDate == ""
            ) {
                //証憑№の取得を行う
                if (
                    $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "") != ""
                ) {
                    strSEQNO = $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "");
                }
                //登録処理を行う
                data.strSEQNO = strSEQNO;
                data.flag = "1";
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        if (result["error"] == "W0034") {
                            $(".HDKShiwakeInput." + result["html"]).trigger(
                                "focus"
                            );
                            me.clsComFnc.FncMsgBox("W0034", result["data"]);
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                        return;
                    }
                    //証憑№を表示する
                    $(".HDKShiwakeInput.lblSyohy_no").val(
                        result["data"]["strSEQNO"]
                    );
                    //更新日付を隠し項目にセット
                    me.hidUpdDate = result["data"]["dtSysdate"];

                    jqgridDataShow(result);
                };
                me.ajax.send(url, data, 0);
            }
            //追加の証憑登録の場合
            else {
                data.flag = "2";
                var url2 =
                    me.sys_id + "/" + me.id + "/" + "fncCheckJikkoSeigyo";
                var data2 = {
                    lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return false;
                    }
                    var objCDt = result["data"]["CheckTbl"];
                    var objNDt = result["data"]["NewNoTbl"];
                    //チェック用ＳＱＬを取得する
                    //同時実行のチェックを行う
                    if (me.fncCheckJikkoSeigyo(objCDt, objNDt) == false) {
                        return;
                    }

                    var strCreBusyoCD = me.clsComFnc.FncNv(
                        objCDt[0]["CRE_BUSYO_CD"]
                    );
                    var strCreSyainCD = me.clsComFnc.FncNv(
                        objCDt[0]["CRE_SYA_CD"]
                    );
                    var strCrePrgID = me.clsComFnc.FncNv(
                        objCDt[0]["CRE_PRG_ID"]
                    );
                    var strCreCltNM = me.clsComFnc.FncNv(
                        objCDt[0]["CRE_CLT_NM"]
                    );
                    //印刷済みの証憑の場合
                    data.PRINT_OUT_FLG = objCDt[0]["PRINT_OUT_FLG"];
                    data.intEdaNo = intEdaNo;
                    data.sender = sender.toUpperCase();
                    data.strCreBusyoCD = strCreBusyoCD;
                    data.strCreSyainCD = strCreSyainCD;
                    data.strCrePrgID = strCrePrgID;
                    data.strCreCltNM = strCreCltNM;
                    if (sender.toUpperCase() != "CMDEVENTALLDELETE") {
                        data.hidGyoNO = me.hidGyoNO
                            ? me.hidGyoNO.replace(me.blankReplace, "")
                            : "";
                    }
                    if (objCDt[0]["PRINT_OUT_FLG"] == 1) {
                        var intEdaNo = 0;
                        //枝№を取得する
                        intEdaNo = Number(objNDt[0]["EDA_NO"]) + 1;
                        if (intEdaNo < 10) {
                            intEdaNo = "0" + intEdaNo;
                        }
                        data.intEdaNo = intEdaNo;
                        //コピー処理を行う
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (!result["result"]) {
                                if (result["error"] == "W0034") {
                                    $(
                                        ".HDKShiwakeInput." + result["html"]
                                    ).trigger("focus");
                                    me.clsComFnc.FncMsgBox(
                                        "W0034",
                                        result["data"]
                                    );
                                } else {
                                    me.clsComFnc.FncMsgBox(
                                        "E9999",
                                        result["error"]
                                    );
                                }
                                return false;
                            }
                            //証憑№を表示する
                            $(".HDKShiwakeInput.lblSyohy_no").val(
                                $(".HDKShiwakeInput.lblSyohy_no")
                                    .val()
                                    .replace(me.blankReplace, "")
                                    .substring(0, 15) +
                                    result["data"]["intEdaNo"]
                            );
                            //更新日付を隠し項目にセット
                            me.hidUpdDate = result["data"]["dtSysdate"];
                            //修正前データを取得する
                            if (result["data"]["SyuseiMaeTbl"].length > 0) {
                                if (
                                    me.clsComFnc.FncNv(
                                        result["data"]["SyuseiMaeTbl"][0][
                                            "SYOHY_NO"
                                        ]
                                    ) == ""
                                ) {
                                    //修正前データが存在しない場合
                                    //修正前表示ボタンを不活性にする
                                    $(
                                        ".HDKShiwakeInput.btnSyuseiMaeDisp"
                                    ).button("disable");
                                } else {
                                    //修正前データが存在する場合
                                    //修正前表示ボタンを活性にする
                                    $(
                                        ".HDKShiwakeInput.btnSyuseiMaeDisp"
                                    ).button("enable");
                                }
                            }
                            jqgridDataShow(result);
                            afterDel(sender);
                        };
                        me.ajax.send(url, data, 0);
                    } else {
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (!result["result"]) {
                                if (result["error"] == "W0034") {
                                    $(
                                        ".HDKShiwakeInput." + result["html"]
                                    ).trigger("focus");
                                    me.clsComFnc.FncMsgBox(
                                        "W0034",
                                        result["data"]
                                    );
                                } else {
                                    me.clsComFnc.FncMsgBox(
                                        "E9999",
                                        result["error"]
                                    );
                                }
                                return false;
                            }
                            //登録処理
                            switch (sender.toUpperCase()) {
                                case "CMDEVENTDELETE":
                                case "CMDEVENTALLDELETE":
                                    if (
                                        result["data"]["DispModeTbl"].length ==
                                        0
                                    ) {
                                        me.hidUpdDate = "";
                                    } else {
                                        me.hidUpdDate = me.clsComFnc.FncNv(
                                            result["data"]["DispModeTbl"][0][
                                                "UPD_DATE"
                                            ]
                                        );
                                    }
                                    break;
                                case "CMDEVENTINSERT":
                                case "CMDEVENTUPDATE":
                                    //証憑№はそのままなので何もしない
                                    //更新日付を隠し項目にセット
                                    me.hidUpdDate = result["data"]["dtSysdate"];
                                    break;
                            }
                            jqgridDataShow(result);
                            afterDel(sender);
                        };
                        me.ajax.send(url, data, 0);
                    }
                };
                me.ajax.send(url2, data2, 0);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    function jqgridDataShow(result) {
        //後処理
        //一覧に表示する
        //合計件数、合計金額、合計消費税額を計算する
        var lngKingaku = 0,
            lngSyohizei = 0;
        $(me.grid_id).jqGrid("clearGridData");

        var data = {
            lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                .val()
                .replace(me.blankReplace, ""),
        };
        var complete_fun = function () {
            var objDs = $(me.grid_id).jqGrid("getRowData");
            for (var index = 0; index < objDs.length; index++) {
                lngKingaku += parseInt(
                    me.clsComFnc.FncNz(objDs[index]["ZEIKM_GK"])
                );
                lngSyohizei += parseInt(
                    me.clsComFnc.FncNz(objDs[index]["SHZEI_GK"])
                );
            }
            //画面項目をクリアし、ボタンの制御を行う
            me.btnClear_Click();
            //合計件数、合計金額、合計消費税額を表示する
            $(".HDKShiwakeInput.lblKensu").val(
                me.toMoney($(".HDKShiwakeInput.lblKensu"), objDs.length)
            );
            $(".HDKShiwakeInput.lblZeikomiGoukei").val(
                me.toMoney($(".HDKShiwakeInput.lblZeikomiGoukei"), lngKingaku)
            );
            $(".HDKShiwakeInput.lblSyohizeiGoukei").val(
                me.toMoney($(".HDKShiwakeInput.lblSyohizeiGoukei"), lngSyohizei)
            );
            //10行の場合は追加ボタンを不活性にする
            var rowNum = $(me.grid_id).jqGrid("getGridParam", "records");
            // 20241125 lhb upd s
            // if (rowNum >= 10) {
            if (rowNum >= 99) {
                $(".HDKShiwakeInput.btnAdd").button("disable");
                // 20241125 lhb upd e
            } else if (rowNum == 0) {
                $(".HDKShiwakeInput.btnAllDelete").button("disable");
                $(".HDKShiwakeInput.btnKakutei").button("disable");
            } else {
                $(".HDKShiwakeInput.btnKakutei").button("enable");
                $(".HDKShiwakeInput.btnAllDelete").button("enable");
            }
            $(".HDKShiwakeInput.txtZeikm_GK").trigger("focus");
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    }

    function afterDel(sender) {
        switch (sender.toUpperCase()) {
            case "CMDEVENTALLDELETE":
                //新規モードで表示していた場合
                if (me.hidMode == "1") {
                    //***画面をクリアし、連続入力できるようにする***
                    //完了メッセージを表示する
                    me.clsComFnc.FncMsgBox("I0022");
                    //画面項目をクリアする
                    me.subFormClear();
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").attr("disabled", false);
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", false);

                    $(me.grid_id).jqGrid("clearGridData");
                    //ボタンを使用不可にする
                    me.DpyInpNewButtonEnabled("99");

                    $(".HDKShiwakeInput.btnSaishinDisp").hide();
                    //伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    //パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HDKShiwakeInput.txtKeiriSyoriDT").datepicker("disable");
                    //ボタンの活性・不活性を決める(新規の場合)
                    me.DpyInpNewButtonEnabled("1");
                    switch (me.PatternID) {
                        case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                        case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                            $(".HDKShiwakeInput.btnPatternTrk").show();
                            $(".HDKShiwakeInput.btnPatternTrk").button(
                                "enable"
                            );
                            break;
                        default:
                            $(".HDKShiwakeInput.btnPatternTrk").hide();
                            break;
                    }
                    //修正前表示ボタンを不活性にする
                    $(".HDKShiwakeInput.btnSyuseiMaeDisp").button("disable");
                    $(".HDKShiwakeInput.txtZeikm_GK").trigger("focus");
                } else {
                    //完了メッセージを表示し、画面を閉じる
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        $(".HDKShiwakeInput.body").dialog("close");
                    };
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(".HDKShiwakeInput.body").dialog("close");
                    };
                    me.clsComFnc.FncMsgBox("I0022");
                }
                break;
        }
    }

    /*
     '**********************************************************************
     '処 理 名：全削除を行う
     '関 数 名：btnAllDelete_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：ＤＢへの削除処理を行う
     '**********************************************************************
     */
    me.btnAllDelete_Click = function () {
        try {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                me.cmdEvent_Click("cmdEventAllDelete");
            };
            if (me.hidDispNO == "ReOut4OBC" || me.hidDispNO == "ReOut4ZenGin") {
                //確認メッセージを表示する
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "該当証憑№のデータを全て削除します。よろしいですか？<br/>※全銀協・OBC出力対象から外したいだけの場合は出力画面の対象欄からチェックを外して下さい。<br/>削除した場合は該当証憑№のデータは全て失われます。"
                );
            } else {
                //確認メッセージを表示する
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "該当証憑№のデータを全て削除します。よろしいですか？"
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：全確定を行う
     '関 数 名：btnKakutei_Click
     '引 数 １：(I)sender イベントソース
     '引 数 ２：(I)e      イベントパラメータ
     '戻 り 値：なし
     '処理説明：印刷処理を実行
     '**********************************************************************
     */
    me.btnKakutei_Click = function () {
        try {
            var strMessage = "";
            if (me.hidMode == "9" || me.hidMode == "8") {
                strMessage = "印刷します。よろしいですか？";
            } else {
                if (me.fncInputNothingCheck() == false) {
                    strMessage =
                        "印刷処理を行います。登録処理(行追加・行修正)を行っていないデータは印刷されません。よろしいですか？";
                } else {
                    strMessage = "印刷します。よろしいですか？";
                }
                //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                if (
                    me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                ) {
                } else {
                    strMessage = strMessage + "※印刷後の修正は不可能です！";
                }
            }
            //確認メッセージを表示する
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEventPrint_Click;
            me.clsComFnc.FncMsgBox("QY999", strMessage);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：cmdEventPrint_Click
     '処理説明：
     '**********************************************************************
     */
    me.cmdEventPrint_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/" + "cmdEventPrint_Click";
            var data = {
                lblSyohy_no: $(".HDKShiwakeInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, ""),
                CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
                CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (
                        result["error"] == "W0026" ||
                        result["error"] == "W0025" ||
                        result["error"] == "W0024"
                    ) {
                        me.clsComFnc.FncMsgBox(result["error"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return false;
                } else {
                    if (me.hidMode != "1") {
                        $(".HDKShiwakeInput.body").dialog("close");
                    } else {
                        me.Page_Load();
                    }
                    //印刷プレビュー画面の表示
                    var href = result["data"]["report"];
                    window.open(href);
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：科目項目名取得
     '関 数 名：txtLKamokuCD_TextChanged
     '処理説明 ：フォーカス移動時に科目項目名を取得する
     '**********************************************************************
     */
    me.txtLKamokuCD_TextChanged = function () {
        try {
            var KAMOK_CD = $(".HDKShiwakeInput.txtLKamokuCD")
                .val()
                .replace(me.blankReplace, "");
            var SUB_KAMOK_CD = $(".HDKShiwakeInput.txtLKomokuCD")
                .val()
                .replace(me.blankReplace, "");
            if (KAMOK_CD != "" && SUB_KAMOK_CD != "") {
                var LdataExist = false;
                for (var index = 0; index < me.KamokuMstBlank.length; index++) {
                    if (
                        me.KamokuMstBlank[index]["KAMOK_CD"] == KAMOK_CD &&
                        me.KamokuMstBlank[index]["SUB_KAMOK_CD"] == SUB_KAMOK_CD
                    ) {
                        $(".HDKShiwakeInput.lblLKamokuNM").val(
                            me.KamokuMstBlank[index]["KAMOK_NAME"]
                        );
                        $(".HDKShiwakeInput.lblLKoumkNM").val(
                            me.KamokuMstBlank[index]["SUB_KAMOK_NAME"]
                        );
                        LdataExist = true;
                        break;
                    }
                }
                if (!LdataExist) {
                    $(".HDKShiwakeInput.lblLKamokuNM").val("");
                    $(".HDKShiwakeInput.lblLKoumkNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "入力された科目は存在しません"
                    );
                    return;
                }
                // 消費税区分（借方）:取得した借方消費税区分 を画面表示
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").val(
                    me.KamokuMstBlank[index]["KARI_TAX_KBN"]
                );
                if (
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn")
                        .find("option:selected")
                        .text() == "対象外"
                ) {
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").val("90");
                } else if (
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn")
                        .find("option:selected")
                        .text() == "非課税売上"
                ) {
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").val("10");
                } else if (
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn")
                        .find("option:selected")
                        .text() != ""
                ) {
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").val("07");
                }
                me.ddlLSyohizeiKbn_SelectedIndexChanged();
            } else {
                $(".HDKShiwakeInput.lblLKamokuNM").val("");
                $(".HDKShiwakeInput.lblLKoumkNM").val("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtRKamokuCD_TextChanged = function () {
        try {
            var KAMOK_CD = $(".HDKShiwakeInput.txtRKamokuCD")
                .val()
                .replace(me.blankReplace, "");
            var SUB_KAMOK_CD = $(".HDKShiwakeInput.txtRKomokuCD")
                .val()
                .replace(me.blankReplace, "");
            if (KAMOK_CD != "" && SUB_KAMOK_CD != "") {
                var RdataExist = false;
                for (var index = 0; index < me.KamokuMstBlank.length; index++) {
                    if (
                        me.KamokuMstBlank[index]["KAMOK_CD"] == KAMOK_CD &&
                        me.KamokuMstBlank[index]["SUB_KAMOK_CD"] == SUB_KAMOK_CD
                    ) {
                        $(".HDKShiwakeInput.lblRKamokuNM").val(
                            me.KamokuMstBlank[index]["KAMOK_NAME"]
                        );
                        $(".HDKShiwakeInput.lblRKoumkNM").val(
                            me.KamokuMstBlank[index]["SUB_KAMOK_NAME"]
                        );
                        RdataExist = true;
                        break;
                    }
                }
                if (!RdataExist) {
                    $(".HDKShiwakeInput.lblRKamokuNM").val("");
                    $(".HDKShiwakeInput.lblRKoumkNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "入力された科目は存在しません"
                    );
                    return;
                }
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").val(
                    me.KamokuMstBlank[index]["KARI_TAX_KBN"]
                );
                if (
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn")
                        .find("option:selected")
                        .text() == "対象外"
                ) {
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").val("90");
                } else if (
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn")
                        .find("option:selected")
                        .text() == "非課税売上"
                ) {
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").val("10");
                } else if (
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn")
                        .find("option:selected")
                        .text() != ""
                ) {
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").val("07");
                }
                me.ddlRSyohizeiKbn_SelectedIndexChanged();
            } else {
                $(".HDKShiwakeInput.lblRKamokuNM").val("");
                $(".HDKShiwakeInput.lblRKoumkNM").val("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：部署名取得
     '関 数 名：txtBusyoCD_TextChanged
     '処理説明：フォーカス移動時に部署名を取得する
     '**********************************************************************
     */
    me.txtBusyoCD_TextChanged = function (sender) {
        try {
            if (
                $(".HDKShiwakeInput." + sender)
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //** 名称取得
                var lblLbusyoNM = "";
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        me.BusyoMst[index]["BUSYO_CD"] ==
                        $(".HDKShiwakeInput." + sender)
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        lblLbusyoNM = me.BusyoMst[index]["BUSYO_NM"];
                        break;
                    }
                }
                if (sender.toUpperCase() == "TXTLBUSYOCD") {
                    $(".HDKShiwakeInput.lblLbusyoNM").val(lblLbusyoNM);
                } else {
                    $(".HDKShiwakeInput.lblRbusyoNM").val(lblLbusyoNM);
                }
            } else {
                if (sender.toUpperCase() == "TXTLBUSYOCD") {
                    $(".HDKShiwakeInput.lblLbusyoNM").val("");
                } else {
                    $(".HDKShiwakeInput.lblRbusyoNM").val("");
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtLBusyoCD_TextChanged = function (sender) {
        try {
            me.txtBusyoCD_TextChanged(sender);
            $(".HDKShiwakeInput.btnLBusyoSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtRBusyoCD_TextChanged = function (sender) {
        try {
            me.txtBusyoCD_TextChanged(sender);
            $(".HDKShiwakeInput.btnRBusyoSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtTorihiki_TextChanged = function () {
        try {
            if (
                $(".HDKShiwakeInput.lblKensakuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //** 名称取得
                var lblTorihikiNM = "";
                for (var index = 0; index < me.Torihiki.length; index++) {
                    if (
                        me.Torihiki[index]["TORIHIKISAKI_CD"] ==
                        $(".HDKShiwakeInput.lblKensakuCD")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        lblTorihikiNM = me.Torihiki[index]["TORIHIKISAKI_NAME"];
                        break;
                    }
                }
                $(".HDKShiwakeInput.lblKensakuNM").val(lblTorihikiNM);
            } else {
                $(".HDKShiwakeInput.lblKensakuNM").val("");
            }
            $(".HDKShiwakeInput.btnTorihikiSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターン対象部署
     '関 数 名：radPatternBusyo_CheckedChanged
     '処理説明：選択されたパターン対象部署によって部署コードの活性・不活性を
     '        ：切り替える
     '**********************************************************************
     */
    me.radPatternBusyo_CheckedChanged = function () {
        try {
            if ($(".HDKShiwakeInput.radPatternKyotu").is(":checked")) {
                $(".HDKShiwakeInput.txtPatternBusyo").attr(
                    "disabled",
                    "disabled"
                );
            } else if ($(".HDKShiwakeInput.radPatternBusyo").is(":checked")) {
                $(".HDKShiwakeInput.txtPatternBusyo").attr("disabled", false);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：一覧の選択ボタン押下時
     '関 数 名：grdIchiran_SelectedIndexChanged
     '処理説明：一覧の選択ボタンが押下された行の仕訳データを画面項目にセットする
     '**********************************************************************
     */
    grdIchiran_SelectedIndexChanged = function (rowId) {
        try {
            //仕訳データの取得
            var rowdata = $(me.grid_id).jqGrid("getRowData", rowId);
            var url = me.sys_id + "/" + me.id + "/" + "fncSelShiwakeData";
            me.selectedData = {
                SYOHY_NO: rowdata["SYOHY_NO"].substring(0, 15),
                EDA_NO: rowdata["EDA_NO"],
                GYO_NO: rowdata["GYO_NO"],
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //添付ファイル
                if (result["data"]["fileExist"]) {
                    $(".HDKShiwakeInput.hasFileFlg").text("ある");
                } else {
                    $(".HDKShiwakeInput.hasFileFlg").text("なし");
                }
                $(".HDKShiwakeInput.fileSelect").button("enable");
                //該当データが存在しない場合
                if (result["data"]["NewNoTbl"].length == 0) {
                    //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                    me.clsComFnc.FncMsgBox("W0026");
                    return;
                }
                //選択された仕訳データを画面項目にセットする
                me.DataFormSet("100", result["data"]["NewNoTbl"]);
                // 消費税率
                $(".HDKShiwakeInput.ddlLSyouhiKbn").attr("disabled", false);
                if (
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn")
                        .find("option:selected")
                        .text() == "対象外"
                ) {
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").val("90");
                }
                $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", false);
                if (
                    $(".HDKShiwakeInput.ddlRSyouhiKbn")
                        .find("option:selected")
                        .text() == "対象外"
                ) {
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").val("90");
                }
                //ボタンの活性・不活性を設定する
                switch (me.hidMode) {
                    case "1":
                    case "2":
                        //選択ボタン押下時のボタン設定
                        me.DpyInpNewButtonEnabled("3");
                        me.subMeisaiGyoEnabledSet();
                        break;
                    case "8":
                        //一部参照モードのボタン設定
                        me.DpyInpNewButtonEnabled("8");
                        //画面項目は不活性
                        me.FormEnabled(false);
                        break;
                    case "9":
                        //参照モードのボタン設定
                        me.DpyInpNewButtonEnabled("9");
                        //画面項目は不活性
                        me.FormEnabled(false);
                        break;
                }
                //最新表示ボタンが表示されている場合は、履歴参照中なので、ボタンは表示しない。
                if (
                    $(".HDKShiwakeInput.btnSaishinDisp").css("display") ==
                    "block"
                ) {
                    me.FormEnabled(false);
                    //参照モードのボタン設定
                    me.DpyInpNewButtonEnabled("9");
                    $(".HDKShiwakeInput.btnKakutei").button("disable");
                    $(".HDKShiwakeInput.btnPatternTrk").button("enable");
                    return;
                }
            };
            me.ajax.send(url, me.selectedData, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：subMeisaiGyoEnabledSet
     '処理説明：
     '**********************************************************************
     */
    me.subMeisaiGyoEnabledSet = function () {
        try {
            var rowdata = $(me.grid_id).jqGrid("getRowData");

            // 99行の場合は追加ボタンを不活性にする
            // 20241125 lhb upd s
            // if (rowdata.length >= 10) {
            if (rowdata.length >= 99) {
                // 20241125 lhb upd e
                $(".HDKShiwakeInput.btnAdd").button("disable");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：税込金額入力で税抜き金額と消費税額を計算する
     '関 数 名：txtZeikm_GK_TextChanged
     '処理説明：税込金額に入力された値で消費税区分より税抜金額と消費税額を
     '　　　　：計算し、表示する
     '**********************************************************************
     */
    me.txtZeikm_GK_TextChanged = function () {
        try {
            if (
                $(".HDKShiwakeInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                $(".HDKShiwakeInput.lblZeink_GK").text("");
                $(".HDKShiwakeInput.lblSyohizei").text("");
                return;
            }
            //,,,
            if (
                $(".HDKShiwakeInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") != "" &&
                $(".HDKShiwakeInput.txtZeikm_GK").val().replace(/,/g, "") == ""
            ) {
                $(".HDKShiwakeInput.txtZeikm_GK").val("");

                $(".HDKShiwakeInput.lblZeink_GK").text("");
                $(".HDKShiwakeInput.lblSyohizei").text("");

                me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtZeikm_GK");
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                return;
            }
            if (
                me.isPosNumber(
                    $(".HDKShiwakeInput.txtZeikm_GK").val().replace(/,/g, "")
                ) == -1
            ) {
                $(".HDKShiwakeInput.lblZeink_GK").text("");
                $(".HDKShiwakeInput.lblSyohizei").text("");
                return;
            }
            if (
                me.isPosNumber(
                    $(".HDKShiwakeInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, "")
                ) == 0
            ) {
                $(".HDKShiwakeInput.lblZeink_GK").text("0");
                $(".HDKShiwakeInput.lblSyohizei").text("0");
                return;
            }
            if (
                $.trim($(".HDKShiwakeInput.txtZeikm_GK").val()).replace(
                    /,/g,
                    ""
                ) != ""
            ) {
                if (
                    $(".HDKShiwakeInput.ddlLSyouhiKbn").prop("selectedIndex") ==
                    0
                ) {
                    $(".HDKShiwakeInput.lblZeink_GK").text("");
                    $(".HDKShiwakeInput.lblSyohizei").text("");
                } else {
                    if (
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "04" ||
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "04" ||
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "05" ||
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "05" ||
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "06" ||
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "06" ||
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "07" ||
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "07"
                    ) {
                        var dblZeink_gk = "";
                        var dblZeiRt = "";
                        if (
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "04" ||
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "04"
                        ) {
                            dblZeiRt = 1.05;
                        } else if (
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "05" ||
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "05"
                        ) {
                            dblZeiRt = 1.08;
                        } else if (
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "06" ||
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "06"
                        ) {
                            dblZeiRt = 1.08;
                        } else if (
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").val() == "07" ||
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").val() == "07"
                        ) {
                            dblZeiRt = 1.1;
                        }
                        dblZeink_gk = Math.floor(
                            $(".HDKShiwakeInput.txtZeikm_GK")
                                .val()
                                .replace(me.blankReplace, "")
                                .replace(/,/g, "") / dblZeiRt
                        );
                        while (1 == 1) {
                            if (
                                $(".HDKShiwakeInput.txtZeikm_GK")
                                    .val()
                                    .replace(me.blankReplace, "")
                                    .replace(/,/g, "") <=
                                Math.floor(dblZeink_gk * dblZeiRt)
                            ) {
                                break;
                            }
                            dblZeink_gk++;
                        }
                        $(".HDKShiwakeInput.lblZeink_GK").text(
                            me.toMoney(
                                $(".HDKShiwakeInput.lblZeink_GK"),
                                dblZeink_gk,
                                "label"
                            )
                        );
                        if (
                            $(".HDKShiwakeInput.txtZeikm_GK")
                                .val()
                                .replace(/,/g, "") == ""
                        ) {
                            $(".HDKShiwakeInput.lblSyohizei").text("");
                        } else {
                            var lblZeink_GK_val = 0;
                            if (
                                $(".HDKShiwakeInput.lblZeink_GK")
                                    .text()
                                    .replace(/,/g, "") != ""
                            ) {
                                lblZeink_GK_val = Number(
                                    $(".HDKShiwakeInput.lblZeink_GK")
                                        .text()
                                        .replace(/,/g, "")
                                );
                            }
                            $(".HDKShiwakeInput.lblSyohizei").text(
                                me.toMoney(
                                    $(".HDKShiwakeInput.lblSyohizei"),
                                    Number(
                                        $(".HDKShiwakeInput.txtZeikm_GK")
                                            .val()
                                            .replace(/,/g, "")
                                    ) - lblZeink_GK_val,
                                    "label"
                                )
                            );
                        }
                    } else {
                        $(".HDKShiwakeInput.lblZeink_GK").text(
                            me.toMoney(
                                $(".HDKShiwakeInput.lblZeink_GK"),
                                $(".HDKShiwakeInput.txtZeikm_GK")
                                    .val()
                                    .replace(me.blankReplace, ""),
                                "label"
                            )
                        );
                        $(".HDKShiwakeInput.lblSyohizei").text(0);
                    }
                }
            }
            $(".HDKShiwakeInput.txtTekyo").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：消費税区分選択時(借方)
     '関 数 名：ddlLSyohizeiKbn_SelectedIndexChanged
     '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
     '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
     '　　　　：計算し、表示する
     '**********************************************************************
     */
    me.ddlLSyohizeiKbn_SelectedIndexChanged = function () {
        try {
            $(".HDKShiwakeInput.ddlLSyouhiKbn").attr("disabled", false);
            if (
                $(".HDKShiwakeInput.ddlLSyohizeiKbn")
                    .find("option:selected")
                    .text() == "対象外"
            ) {
                $(".HDKShiwakeInput.ddlLSyouhiKbn").attr(
                    "disabled",
                    "disabled"
                );
                $(".HDKShiwakeInput.ddlLSyouhiKbn").val("90");
            }
            if (!$(".HDKShiwakeInput.txtZeikm_GK").is(":hidden")) {
                me.txtZeikm_GK_TextChanged();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：消費税区分選択時(貸方)
     '関 数 名：ddlRSyohizeiKbn_SelectedIndexChanged
     '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
     '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
     '　　　　：計算し、表示する
     '**********************************************************************
     */
    me.ddlRSyohizeiKbn_SelectedIndexChanged = function () {
        try {
            $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", false);
            // 消費税区分（借方）:取得した借方消費税区分 を画面表示
            if (
                $(".HDKShiwakeInput.ddlRSyohizeiKbn")
                    .find("option:selected")
                    .text() == "対象外"
            ) {
                $(".HDKShiwakeInput.ddlRSyouhiKbn").attr(
                    "disabled",
                    "disabled"
                );
                $(".HDKShiwakeInput.ddlRSyouhiKbn").val("90");
            }
            if (!$(".HDKShiwakeInput.txtZeikm_GK").is(":hidden")) {
                me.txtZeikm_GK_TextChanged();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：クリア処理
     '関 数 名：btnClear_Click
     '処理説明：画面項目をクリアする
     '**********************************************************************
     */
    me.btnClear_Click = function () {
        try {
            //画面項目をクリアする
            me.subFormClear(true);
            $(".HDKShiwakeInput.ddlPatternSel").get(0).selectedIndex = 0;
            //ドロップダウンをクリアする
            $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiwakeInput.ddlLSyouhiKbn").get(0).selectedIndex = 0;
            $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiwakeInput.ddlRSyouhiKbn").get(0).selectedIndex = 0;

            $(".HDKShiwakeInput.ddlLSyouhiKbn").attr("disabled", false);
            $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", false);
            //ボタンの活性・不活性を設定する
            me.DpyInpNewButtonEnabled("4");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：表示されている仕訳をパターンとして登録する
     '関 数 名：btnPatternTrk_Click
     '処理説明：表示されている仕訳をパターンとして登録する
     '**********************************************************************
     */
    me.btnPatternTrk_Click = function () {
        try {
            $(".HDKShiwakeInput.txtLKamokuCD").val(
                $.trim($(".HDKShiwakeInput.txtLKamokuCD").val())
            );
            $(".HDKShiwakeInput.txtLKomokuCD").val(
                $.trim($(".HDKShiwakeInput.txtLKomokuCD").val())
            );
            $(".HDKShiwakeInput.txtRKamokuCD").val(
                $.trim($(".HDKShiwakeInput.txtRKamokuCD").val())
            );
            $(".HDKShiwakeInput.txtRKomokuCD").val(
                $.trim($(".HDKShiwakeInput.txtRKomokuCD").val())
            );
            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.txtZeikm_GK_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            //入力チェックを行う
            if (
                $(".HDKShiwakeInput.radPatternBusyo").is(":checked") &&
                $(".HDKShiwakeInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternBusyo");
                me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
                return;
            } else if (
                $(".HDKShiwakeInput.radPatternBusyo").is(":checked") &&
                $(".HDKShiwakeInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        me.BusyoMst[index]["BUSYO_CD"] ==
                        $(".HDKShiwakeInput.txtPatternBusyo")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        BusyoMstFlag = true;
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    me.clsComFnc.ObjFocus = $(
                        ".HDKShiwakeInput.txtPatternBusyo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "対象部署コードが部署マスタに存在しません！"
                    );
                    return;
                }
            }
            if (
                $(".HDKShiwakeInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternNM");
                me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
                return;
            } else {
                if (
                    me.FncCheckByteLength(
                        $(".HDKShiwakeInput.txtPatternNM")
                            .val()
                            .replace(me.blankReplace, ""),
                        40
                    ) == false
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternNM");
                    me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                    return;
                }
            }
            //入力チェックを行う
            if (me.fncInputCheck(false) == true) {
                //確認メッセージを表示する
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEventPatternTrk_Click;
                me.clsComFnc.FncMsgBox("QY018");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：表示されている仕訳をパターンとして登録する
     '関 数 名：cmdEventPatternTrk_Click
     '処理説明：表示されている仕訳をパターンとして登録する
     '**********************************************************************
     */
    me.cmdEventPatternTrk_Click = function () {
        try {
            var fncFukanzenCheck = 0;
            var url =
                me.sys_id + "/" + me.id + "/" + "cmdEventPatternTrk_Click";
            var data = {
                hidPatternNO: me.clsComFnc.FncNv(me.hidPatternNO),
                txtZeikm_GK: $(".HDKShiwakeInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                lblZeink_GK: $(".HDKShiwakeInput.lblZeink_GK")
                    .text()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                lblSyohizei: $(".HDKShiwakeInput.lblSyohizei")
                    .text()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                txtTekyo: $(".HDKShiwakeInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKamokuCD: $(".HDKShiwakeInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKomokuCD: $(".HDKShiwakeInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HDKShiwakeInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                ddlLSyohizeiKbn: $(".HDKShiwakeInput.ddlLSyohizeiKbn").val(),
                ddlLSyouhiKbn: $(".HDKShiwakeInput.ddlLSyouhiKbn").val(),
                txtRKamokuCD: $(".HDKShiwakeInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKomokuCD: $(".HDKShiwakeInput.txtRKomokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRbusyoCD: $(".HDKShiwakeInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                lblKensakuCD: $(".HDKShiwakeInput.lblKensakuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                lblKensakuNM: $(".HDKShiwakeInput.lblKensakuNM")
                    .val()
                    .replace(me.blankReplace, ""),
                ddlRSyohizeiKbn: $(".HDKShiwakeInput.ddlRSyohizeiKbn").val(),
                ddlRSyouhiKbn: $(".HDKShiwakeInput.ddlRSyouhiKbn").val(),
                fncFukanzenCheck: fncFukanzenCheck,
                txtPatternNM: $(".HDKShiwakeInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, ""),
                radPatternKyotu: $(".HDKShiwakeInput.radPatternKyotu").is(
                    ":checked"
                )
                    ? "1"
                    : "2",
                txtPatternBusyo: $(".HDKShiwakeInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, ""),
                lblSyohy_no: !$(".HDKShiwakeInput.KeyTableRow").is(":hidden"),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "W0034") {
                        $(".HDKShiwakeInput." + result["html"]).trigger(
                            "focus"
                        );
                        me.clsComFnc.FncMsgBox("W0034", result["data"]);
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    return false;
                }
                if (me.clsComFnc.FncNv(me.hidPatternNO) == "") {
                    if (me.hidDispNO == "103" && me.hidMode == "2") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            $(".HDKShiwakeInput.body").dialog("close");
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HDKShiwakeInput.body").dialog("close");
                        };
                        //登録完了のメッセージを表示し、画面を閉じる
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        //画面項目をクリアする
                        if (me.hidDispNO == "103") {
                            me.subFormClear(true);
                            $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(
                                0
                            ).selectedIndex = 0;
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").get(
                                0
                            ).selectedIndex = 0;
                            $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(
                                0
                            ).selectedIndex = 0;
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").get(
                                0
                            ).selectedIndex = 0;
                            $(".HDKShiwakeInput.ddlLSyouhiKbn").attr(
                                "disabled",
                                "disabled"
                            );
                            $(".HDKShiwakeInput.ddlRSyouhiKbn").attr(
                                "disabled",
                                "disabled"
                            );
                        }
                        $(".HDKShiwakeInput.txtPatternNM").val("");
                        $(".HDKShiwakeInput.txtPatternBusyo").val("");
                        $(".HDKShiwakeInput.radPatternKyotu").prop(
                            "checked",
                            true
                        );
                        $(".HDKShiwakeInput.radPatternBusyo").prop(
                            "checked",
                            false
                        );
                        me.radPatternBusyo_CheckedChanged();
                        //登録完了のメッセージを表示する
                        me.clsComFnc.FncMsgBox("I0016");
                    }
                } else {
                    if (me.hidMode == "1") {
                        //画面項目をクリアする
                        me.subFormClear(true);
                        $(".HDKShiwakeInput.ddlLSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiwakeInput.ddlRSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiwakeInput.ddlLSyouhiKbn").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".HDKShiwakeInput.ddlRSyouhiKbn").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".HDKShiwakeInput.txtPatternNM").val("");
                        $(".HDKShiwakeInput.txtPatternBusyo").val("");
                        $(".HDKShiwakeInput.radPatternKyotu").prop(
                            "checked",
                            true
                        );
                        $(".HDKShiwakeInput.radPatternBusyo").prop(
                            "checked",
                            false
                        );
                        me.radPatternBusyo_CheckedChanged();
                        //登録完了のメッセージを表示し、画面を閉じる
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            $(".HDKShiwakeInput.body").dialog("close");
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HDKShiwakeInput.body").dialog("close");
                        };
                        //登録完了のメッセージを表示し、画面を閉じる
                        me.clsComFnc.FncMsgBox("I0016");
                    }
                }
                //パターンのドロップダウンリストを設定する
                me.PatternDDLSet(result["data"]["PatternTbl"]);
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターンを登録する(パターン検索画面より遷移)
     '関 数 名：btnPtnInsert_Click
     '処理説明：パターンを登録する
     '**********************************************************************
     */
    me.btnPtnInsert_Click = function (ID) {
        try {
            //入力チェックを行う
            if (
                $(".HDKShiwakeInput.radPatternBusyo").is(":checked") &&
                $(".HDKShiwakeInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternBusyo");
                me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
                return;
            } else if (
                $(".HDKShiwakeInput.radPatternBusyo").is(":checked") &&
                $(".HDKShiwakeInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //対象部署がマスタに存在しない場合
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        $(".HDKShiwakeInput.txtPatternBusyo")
                            .val()
                            .replace(me.blankReplace, "") ==
                        me.BusyoMst[index]["BUSYO_CD"]
                    ) {
                        BusyoMstFlag = true;
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    me.clsComFnc.ObjFocus = $(
                        ".HDKShiwakeInput.txtPatternBusyo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "対象部署コードが部署マスタに存在しません！"
                    );
                    return;
                }
            }
            if (
                $(".HDKShiwakeInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternNM");
                me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
                return;
            } else {
                if (
                    me.FncCheckByteLength(
                        $(".HDKShiwakeInput.txtPatternNM")
                            .val()
                            .replace(me.blankReplace, ""),
                        40
                    ) == false
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtPatternNM");
                    me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                    return;
                }
            }
            //入力チェックを行う
            if (me.fncInputCheck(false) == true) {
                //確認メッセージを表示する
                if (ID == "btnPtnInsert") {
                    //登録の場合
                    me.hidPatternNO = "";
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEventPatternTrk_Click;
                    me.clsComFnc.FncMsgBox("QY010");
                } else {
                    //更新の場合
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEventPatternTrk_Click;
                    me.clsComFnc.FncMsgBox("QY012");
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターンを削除する(パターン検索画面より遷移)
     '関 数 名：btnPtnDelete_Click
     '処理説明：パターンを削除する
     '**********************************************************************
     */
    me.btnPtnDelete_Click = function () {
        try {
            //パターン削除
            var url = me.sys_id + "/" + me.id + "/" + "btnPtnDelete_Click";
            var data = {
                hidPatternNO: me.hidPatternNO,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //削除完了のメッセージを表示し、画面を閉じる
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    $(".HDKShiwakeInput.body").dialog("close");
                };
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HDKShiwakeInput.body").dialog("close");
                };
                me.clsComFnc.FncMsgBox("I0017");
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：fncCheckJikkoSeigyo
     '処理説明：
     '**********************************************************************
     */
    me.fncCheckJikkoSeigyo = function (objCDt, objNDt) {
        try {
            //該当データが存在しない場合
            if (objCDt.length == 0) {
                //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return false;
            }
            //削除フラグが立っている場合
            if (objCDt[0]["DEL_FLG"] == 1) {
                //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return false;
            }
            //既に印刷されている場合
            if (
                me.clsComFnc.FncNv(objCDt[0]["PRINT_OUT_FLG"]) == 1 &&
                me.PatternID != me.HDKAIKEI.CONST_ADMIN_PTN_NO &&
                me.PatternID != me.HDKAIKEI.CONST_HONBU_PTN_NO
            ) {
                //hidmode="8"は削除は可能なため、省く
                if (me.hidMode != "8") {
                    //他のユーザによって印刷が行われましたので、登録を行うことは出来ません。"
                    me.clsComFnc.FncMsgBox("W0027");
                    return false;
                }
            }
            //既に全銀協・OBC出力されている場合
            if (
                me.clsComFnc.FncNv(objCDt[0]["CSV_OUT_FLG"]) == 1 ||
                me.clsComFnc.FncNv(objCDt[0]["XLSX_OUT_FLG"]) == 1
            ) {
                //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                if (
                    me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                ) {
                    //CSV再出力画面から表示した場合は飛ばす。
                    if (
                        me.hidDispNO != "ReOut4OBC" &&
                        me.hidDispNO != "ReOut4ZenGin"
                    ) {
                        //"他のユーザによって全銀協・OBC出力が行われましたので、全銀協・OBC再出力画面より開き直してください！"
                        me.clsComFnc.FncMsgBox("W0032");
                        return false;
                    }
                } else {
                    //他のユーザによって全銀協・OBC出力が行われましたので登録することは出来ません。
                    me.clsComFnc.FncMsgBox("W0033");
                    return false;
                }
            }
            //更新日付が表示時と違う場合
            if (
                me.clsComFnc.FncNv(objCDt[0]["UPD_DATE"]) !=
                me.clsComFnc.FncNv(me.hidUpdDate)
            ) {
                //他のユーザによって更新されています！最新データを取得して下さい。
                me.clsComFnc.FncMsgBox("W0025");
                return false;
            }
            //証憑№のチェックを行う
            if (
                objNDt[0]["EDA_NO"] !=
                $(".HDKShiwakeInput.lblSyohy_no").val().substring(15, 17)
            ) {
                //他のユーザーにより更新されています。最新の情報を確認してください。
                me.clsComFnc.FncMsgBox("W0025");
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目をクリアする
     '関 数 名：subFormClear
     '引 数 １：なし
     '戻 り 値：なし
     '処理説明：画面項目をクリアする
     '**********************************************************************
     */
    me.subFormClear = function (blnClear) {
        try {
            if (blnClear == undefined) {
                blnClear = false;
            }
            if (!blnClear) {
                $(".HDKShiwakeInput.lblSyohy_no").val("");
            }
            $(".HDKShiwakeInput.ddlLSyohizeiKbn").val("");
            $(".HDKShiwakeInput.ddlRSyohizeiKbn").val("");
            $(".HDKShiwakeInput.ddlLSyouhiKbn").val("");
            $(".HDKShiwakeInput.ddlRSyouhiKbn").val("");
            $(".HDKShiwakeInput.txtKeiriSyoriDT").val("");
            $(".HDKShiwakeInput.txtZeikm_GK").val("");
            $(".HDKShiwakeInput.lblZeink_GK").text("");
            $(".HDKShiwakeInput.lblSyohizei").text("");
            $(".HDKShiwakeInput.txtTekyo").val("");
            $(".HDKShiwakeInput.txtLKamokuCD").val("");
            $(".HDKShiwakeInput.txtLKomokuCD").val("");
            $(".HDKShiwakeInput.lblLKamokuNM").val("");
            $(".HDKShiwakeInput.lblLKoumkNM").val("");
            $(".HDKShiwakeInput.txtLBusyoCD").val("");
            $(".HDKShiwakeInput.lblLbusyoNM").val("");
            $(".HDKShiwakeInput.txtRKamokuCD").val("");
            $(".HDKShiwakeInput.txtRKomokuCD").val("");
            $(".HDKShiwakeInput.lblRKamokuNM").val("");
            $(".HDKShiwakeInput.lblRKoumkNM").val("");
            $(".HDKShiwakeInput.txtRbusyoCD").val("");
            $(".HDKShiwakeInput.lblRbusyoNM").val("");
            $(".HDKShiwakeInput.lblKensakuCD").val("");
            $(".HDKShiwakeInput.lblKensakuCD").val("");
            $(".HDKShiwakeInput.lblKensakuNM").val("");
            if (!blnClear) {
                $(".HDKShiwakeInput.lblKensu").val("");
                $(".HDKShiwakeInput.lblZeikomiGoukei").val("");
                $(".HDKShiwakeInput.lblSyohizeiGoukei").val("");
            }
            $(".HDKShiwakeInput.radPatternKyotu").prop("checked", true);
            $(".HDKShiwakeInput.radPatternBusyo").prop("checked", false);
            $(".HDKShiwakeInput.txtPatternBusyo").attr("disabled", "disabled");
            $(".HDKShiwakeInput.txtPatternBusyo").val("");
            $(".HDKShiwakeInput.txtPatternNM").val("");
            if (!blnClear) {
                $(".HDKShiwakeInput.lblMemo").text("");
            }
            // 添付ファイル
            $(".HDKShiwakeInput.hasFileFlg").text("なし");
            $(".HDKShiwakeInput.fileSelect").button("disable");
            me.selectedData = {
                SYOHY_NO: "",
                EDA_NO: "",
                GYO_NO: "",
            };
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：伝票入力画面用ボタンを表示する
     '関 数 名：DenpyoInputButtonVisible
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.DenpyoInputButtonVisible = function (blnVisible) {
        try {
            if (blnVisible) {
                $(".HDKShiwakeInput.btnSyuseiMaeDisp").show();
                $(".HDKShiwakeInput.btnAdd").show();
                $(".HDKShiwakeInput.btnUpdate").show();
                $(".HDKShiwakeInput.btnDelete").show();
                $(".HDKShiwakeInput.btnClear").show();
                $(".HDKShiwakeInput.btnAllDelete").show();
                $(".HDKShiwakeInput.btnKakutei").show();

                $(".HDKShiwakeInput.btnPatternTrk").show();
            } else {
                $(".HDKShiwakeInput.btnSyuseiMaeDisp").hide();
                $(".HDKShiwakeInput.btnAdd").hide();
                $(".HDKShiwakeInput.btnUpdate").hide();
                $(".HDKShiwakeInput.btnDelete").hide();
                $(".HDKShiwakeInput.btnClear").hide();
                $(".HDKShiwakeInput.btnAllDelete").hide();
                $(".HDKShiwakeInput.btnKakutei").hide();

                $(".HDKShiwakeInput.btnPatternTrk").hide();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターン登録用ボタンを表示する
     '関 数 名：PatternInputButtonVisible
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.PatternInputButtonVisible = function (blnVisible) {
        try {
            if (blnVisible) {
                $(".HDKShiwakeInput.btnPtnDelete").show();
                $(".HDKShiwakeInput.btnPtnInsert").show();
                $(".HDKShiwakeInput.btnPtnUpdate").show();
            } else {
                $(".HDKShiwakeInput.btnPtnDelete").hide();
                $(".HDKShiwakeInput.btnPtnInsert").hide();
                $(".HDKShiwakeInput.btnPtnUpdate").hide();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：ボタンを使用不可にする
     '関 数 名：DpyInpNewButtonEnabled
     '引 数 １：
     '戻 り 値：なし
     '処理説明：画面項目をクリアする
     '**********************************************************************
     */
    me.DpyInpNewButtonEnabled = function (intMode) {
        try {
            switch (intMode) {
                //新規画面表示時
                case "1":
                    $(".HDKShiwakeInput.btnAdd").button("enable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("disable");
                    $(".HDKShiwakeInput.btnSyuseiMaeDisp").button("disable");
                    $(".HDKShiwakeInput.btnClear").button("enable");
                    $(".HDKShiwakeInput.btnPatternTrk").button("enable");
                    $(".HDKShiwakeInput.fileSelect").button("disable");
                    break;
                //修正画面表示時
                case "2":
                    $(".HDKShiwakeInput.btnAdd").button("enable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("enable");
                    $(".HDKShiwakeInput.btnKakutei").button("enable");
                    $(".HDKShiwakeInput.btnClear").button("enable");
                    $(".HDKShiwakeInput.btnPatternTrk").button("enable");
                    $(".HDKShiwakeInput.fileSelect").button("disable");
                    break;
                //一覧選択時
                case "3":
                    $(".HDKShiwakeInput.btnAdd").button("enable");
                    $(".HDKShiwakeInput.btnUpdate").button("enable");
                    $(".HDKShiwakeInput.btnDelete").button("enable");
                    $(".HDKShiwakeInput.btnAllDelete").button("enable");
                    $(".HDKShiwakeInput.btnKakutei").button("enable");
                    $(".HDKShiwakeInput.btnClear").button("enable");
                    $(".HDKShiwakeInput.fileSelect").button("enable");
                    break;
                //クリア処理
                case "4":
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    var rowcount = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "reccount"
                    );
                    // 20241125 lhb upd s
                    // if (rowcount < 10) {
                    if (rowcount < 99) {
                        // 20241125 lhb upd e
                        $(".HDKShiwakeInput.btnAdd").button("enable");
                    }
                    $(".HDKShiwakeInput.fileSelect").button("disable");
                    break;
                //一部参照モードの場合
                case "8":
                    $(".HDKShiwakeInput.btnAdd").button("disable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("enable");
                    $(".HDKShiwakeInput.btnClear").button("disable");
                    if (
                        me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                    ) {
                        $(".HDKShiwakeInput.btnPatternTrk").button("enable");
                    } else {
                        $(".HDKShiwakeInput.btnPatternTrk").button("disable");
                    }
                    $(".HDKShiwakeInput.btnKakutei").button("enable");
                    break;
                //参照モードの場合
                case "9":
                    $(".HDKShiwakeInput.btnAdd").button("disable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("disable");
                    $(".HDKShiwakeInput.btnClear").button("disable");
                    //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                    if (
                        me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                    ) {
                        $(".HDKShiwakeInput.btnPatternTrk").button("enable");
                    } else {
                        $(".HDKShiwakeInput.btnPatternTrk").button("disable");
                    }
                    $(".HDKShiwakeInput.btnKakutei").button("enable");
                    break;
                //エラーの場合
                case "99":
                    $(".HDKShiwakeInput.btnAdd").button("disable");
                    $(".HDKShiwakeInput.btnUpdate").button("disable");
                    $(".HDKShiwakeInput.btnDelete").button("disable");
                    $(".HDKShiwakeInput.btnAllDelete").button("disable");
                    $(".HDKShiwakeInput.btnSyuseiMaeDisp").button("disable");
                    $(".HDKShiwakeInput.btnClear").button("disable");
                    $(".HDKShiwakeInput.btnPatternTrk").button("disable");
                    $(".HDKShiwakeInput.btnKakutei").button("disable");
                    $(".HDKShiwakeInput.fileSelect").button("disable");
                    break;
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目を不活性にする
     '関 数 名：FormEnabled
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FormEnabled = function (blnEnabled) {
        try {
            $(".HDKShiwakeInput.txtKeiriSyoriDT").datepicker("disable");
            if (
                blnEnabled &&
                $(".HDKShiwakeInput.txtKeiriSyoriDT").val() != ""
            ) {
                $(".HDKShiwakeInput.txtKeiriSyoriDT").datepicker("enable");
            }
            if (blnEnabled) {
                blnEnabled_button = "enable";
            } else {
                blnEnabled_button = "disable";
            }
            $(".HDKShiwakeInput.ddlPatternSel").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.txtZeikm_GK").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.txtTekyo").attr("disabled", !blnEnabled);

            $(".HDKShiwakeInput.txtLBusyoCD").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.txtRbusyoCD").attr("disabled", !blnEnabled);

            $(".HDKShiwakeInput.KamokuCD").attr("disabled", !blnEnabled);

            $(".HDKShiwakeInput.KouzaHiTekkiEnabledSet").attr(
                "disabled",
                !blnEnabled
            );
            $(".HDKShiwakeInput.KouzaHiTekkiEnabledSet2").attr(
                "disabled",
                !blnEnabled
            );

            $(".HDKShiwakeInput.lblKensakuCD").attr("disabled", !blnEnabled);

            $(".HDKShiwakeInput.nowrap").button(blnEnabled_button);

            $(".HDKShiwakeInput.ddlLSyohizeiKbn").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.ddlLSyouhiKbn").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.ddlRSyohizeiKbn").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", !blnEnabled);
            $(".HDKShiwakeInput.btnTorihikiSearch").button(blnEnabled_button);
            // $(".HDKShiwakeInput.btnSyainSearch").button(blnEnabled_button);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：ドロップダウンリストを設定する
     '関 数 名：DropDownListSet
     '引 数 １：なし
     '戻 り 値：なし
     '処理説明：画面項目をクリアする
     '**********************************************************************
     */
    me.DropDownListSet = function (result) {
        try {
            //借方消費税区分にセット
            var MeisyouTbl = result["data"]["MeisyouTbl"];
            for (var index = 0; index < MeisyouTbl.length; index++) {
                MeisyouTbl[index]["NICKNAME"] =
                    MeisyouTbl[index]["NICKNAME"] == null
                        ? ""
                        : MeisyouTbl[index]["NICKNAME"];
                $("<option></option>")
                    .val(MeisyouTbl[index]["TAX_KBN_CD"])
                    .text(MeisyouTbl[index]["NICKNAME"])
                    .appendTo(".HDKShiwakeInput.ddlLSyohizeiKbn");
            }
            //貸方消費税区分にセット
            for (var index = 0; index < MeisyouTbl.length; index++) {
                MeisyouTbl[index]["NICKNAME"] =
                    MeisyouTbl[index]["NICKNAME"] == null
                        ? ""
                        : MeisyouTbl[index]["NICKNAME"];
                $("<option></option>")
                    .val(MeisyouTbl[index]["TAX_KBN_CD"])
                    .text(MeisyouTbl[index]["NICKNAME"])
                    .appendTo(".HDKShiwakeInput.ddlRSyohizeiKbn");
            }
            //借方消費税率にセット
            var TorihikiTbl = result["data"]["TorihikiTbl"];
            for (var index = 0; index < TorihikiTbl.length; index++) {
                TorihikiTbl[index]["MEISYOU"] =
                    TorihikiTbl[index]["MEISYOU"] == null
                        ? ""
                        : TorihikiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(TorihikiTbl[index]["MEISYOU_CD"])
                    .text(TorihikiTbl[index]["MEISYOU"])
                    .appendTo(".HDKShiwakeInput.ddlLSyouhiKbn");
            }
            //貸方消費税率にセット
            for (var index = 0; index < TorihikiTbl.length; index++) {
                TorihikiTbl[index]["MEISYOU"] =
                    TorihikiTbl[index]["MEISYOU"] == null
                        ? ""
                        : TorihikiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(TorihikiTbl[index]["MEISYOU_CD"])
                    .text(TorihikiTbl[index]["MEISYOU"])
                    .appendTo(".HDKShiwakeInput.ddlRSyouhiKbn");
            }
            //パターン選択にセット
            var PatternTbl = result["data"]["PatternTbl"];
            for (var index = 0; index < PatternTbl.length; index++) {
                PatternTbl[index]["PATTERN_NM"] =
                    PatternTbl[index]["PATTERN_NM"] == null
                        ? ""
                        : PatternTbl[index]["PATTERN_NM"];
                $("<option></option>")
                    .val(PatternTbl[index]["PATTERN_NO"])
                    .text(PatternTbl[index]["PATTERN_NM"])
                    .appendTo(".HDKShiwakeInput.ddlPatternSel");
            }
            $(".HDKShiwakeInput.ddlLSyohizeiKbn").val("");
            $(".HDKShiwakeInput.ddlRSyohizeiKbn").val("");
            $(".HDKShiwakeInput.ddlLSyouhiKbn").val("");
            $(".HDKShiwakeInput.ddlRSyouhiKbn").val("");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：PatternDDLSet
     '処理説明：
     '**********************************************************************
     */
    me.PatternDDLSet = function (PatternTbl) {
        try {
            //パターン選択にセット
            $(".HDKShiwakeInput.ddlPatternSel").empty();
            for (var index = 0; index < PatternTbl.length; index++) {
                if (PatternTbl[index]["PATTERN_NM"] == null) {
                    PatternTbl[index]["PATTERN_NM"] = "";
                }
                $("<option></option>")
                    .val(PatternTbl[index]["PATTERN_NO"])
                    .text(PatternTbl[index]["PATTERN_NM"])
                    .appendTo(".HDKShiwakeInput.ddlPatternSel");
            }
            if (PatternTbl.length > 0) {
                $(".HDKShiwakeInput.ddlPatternSel").val(
                    PatternTbl[0]["PATTERN_NO"]
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：メモ欄を設定する
     '関 数 名：MemoSet
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.MemoSet = function (MemoTbl) {
        try {
            for (var index = 0; index < MemoTbl.length; index++) {
                $(".HDKShiwakeInput.lblMemo").text(
                    $(".HDKShiwakeInput.lblMemo").text() +
                        MemoTbl[index]["MEISYOU"]
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：仕訳伝票入力用項目を非表示にする
     '関 数 名：ForPatternVisible
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.ForPatternVisible = function () {
        try {
            $(".HDKShiwakeInput.fileSelect").hide();
            $(".HDKShiwakeInput.KeyTableRow").hide();
            $(".HDKShiwakeInput.KingakuRow").hide();
            $(".HDKShiwakeInput.HDKShiwakeInput_sprList").hide();
            $(".HDKShiwakeInput.GOUKEITBL").hide();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターンデータを画面項目にセットする
     '関 数 名：DataFormSet
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.DataFormSet = function (strNo, data) {
        try {
            if (data.length == 0) {
                return;
            }
            if (strNo == "100") {
                $(".HDKShiwakeInput.lblSyohy_no").val(
                    me.clsComFnc.FncNv(data[0]["SYOHY_NO"]) +
                        me.clsComFnc.FncNv(data[0]["EDA_NO"])
                );
                //隠し項目(行№)にセットする
                me.hidGyoNO = me.clsComFnc.FncNv(data[0]["GYO_NO"]);

                $(".HDKShiwakeInput.txtZeikm_GK").val(
                    me.toMoney(
                        $(".HDKShiwakeInput.txtZeikm_GK"),
                        me.clsComFnc
                            .FncNv(data[0]["ZEIKM_GK"])
                            .replace(/,/g, "")
                    )
                );
                $(".HDKShiwakeInput.lblZeink_GK").text(
                    me.toMoney(
                        $(".HDKShiwakeInput.lblZeink_GK"),
                        me.clsComFnc
                            .FncNv(data[0]["ZEINK_GK"])
                            .replace(/,/g, ""),
                        "label"
                    )
                );
                $(".HDKShiwakeInput.lblSyohizei").text(
                    me.toMoney(
                        $(".HDKShiwakeInput.lblSyohizei"),
                        me.clsComFnc
                            .FncNv(data[0]["SHZEI_GK"])
                            .replace(/,/g, ""),
                        "label"
                    )
                );
                $(".HDKShiwakeInput.txtKeiriSyoriDT").val(
                    me.clsComFnc.FncNv(data[0]["KEIRI_DT"])
                );
            }
            $(".HDKShiwakeInput.txtTekyo").val(
                me.clsComFnc.FncNv(data[0]["TEKYO"]).replace(/〜/g, "～")
            );
            $(".HDKShiwakeInput.txtLKamokuCD").val(
                me.clsComFnc.FncNv(data[0]["L_KAMOK_CD"])
            );
            $(".HDKShiwakeInput.txtLKomokuCD").val(
                me.clsComFnc.FncNv(data[0]["L_KOUMK_CD"])
            );
            $(".HDKShiwakeInput.lblLKamokuNM").val(
                me.clsComFnc.FncNv(data[0]["L_KAMOK_NM"])
            );
            $(".HDKShiwakeInput.lblLKoumkNM").val(
                me.clsComFnc.FncNv(data[0]["L_KOUMK_NM"])
            );
            $(".HDKShiwakeInput.txtLBusyoCD").val(
                me.clsComFnc.FncNv(data[0]["L_HASEI_KYOTN_CD"])
            );
            $(".HDKShiwakeInput.lblLbusyoNM").val(
                me.clsComFnc.FncNv(data[0]["L_BUSYO_NM"])
            );
            $(".HDKShiwakeInput.txtRKamokuCD").val(
                me.clsComFnc.FncNv(data[0]["R_KAMOK_CD"])
            );
            $(".HDKShiwakeInput.txtRKomokuCD").val(
                me.clsComFnc.FncNv(data[0]["R_KOUMK_CD"])
            );
            $(".HDKShiwakeInput.lblRKamokuNM").val(
                me.clsComFnc.FncNv(data[0]["R_KAMOK_NM"])
            );
            $(".HDKShiwakeInput.lblRKoumkNM").val(
                me.clsComFnc.FncNv(data[0]["R_KOUMK_NM"])
            );
            $(".HDKShiwakeInput.txtRbusyoCD").val(
                me.clsComFnc.FncNv(data[0]["R_HASEI_KYOTN_CD"])
            );
            $(".HDKShiwakeInput.lblRbusyoNM").val(
                me.clsComFnc.FncNv(data[0]["R_BUSYO_NM"])
            );
            $(".HDKShiwakeInput.lblKensakuCD").val(
                me.clsComFnc.FncNv(data[0]["TORIHIKISAKI_CD"])
            );
            $(".HDKShiwakeInput.lblKensakuNM").val(
                me.clsComFnc.FncNv(data[0]["TORIHIKISAKI_NAME"])
            );
            if (me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) != "") {
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").val(
                    me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"])
                );
            } else {
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").val("");
            }
            // 20241125 lhb ins s
            if (me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) == "0000") {
                $(".HDKShiwakeInput.ddlLSyouhiKbn ").attr("disabled", true);
            } else {
                if (
                    $(".HDKShiwakeInput.ddlLSyohizeiKbn").prop("disabled") ==
                    false
                ) {
                    $(".HDKShiwakeInput.ddlLSyouhiKbn ").attr(
                        "disabled",
                        false
                    );
                }
            }
            // 20241125 lhb ins e
            if (me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) != "") {
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").val(
                    me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"])
                );
            } else {
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").val("");
            }
            // 20241125 lhb ins s
            if (me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) == "0000") {
                $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", true);
            } else {
                if (
                    $(".HDKShiwakeInput.ddlRSyohizeiKbn").prop("disabled") ==
                    false
                ) {
                    $(".HDKShiwakeInput.ddlRSyouhiKbn").attr("disabled", false);
                }
            }
            // 20241125 lhb ins e
            $(".HDKShiwakeInput.ddlLSyouhiKbn").val(
                me.clsComFnc.FncNv(data[0]["L_ZEI_RT_KB"])
            );
            $(".HDKShiwakeInput.ddlRSyouhiKbn").val(
                me.clsComFnc.FncNv(data[0]["R_ZEI_RT_KB"])
            );
            //パターン検索画面から遷移、初期表示時
            if (strNo == "103") {
                if (data[0]["TAISYO_BUSYO_KB"] == "1") {
                    $(".HDKShiwakeInput.radPatternKyotu").prop("checked", true);
                    $(".HDKShiwakeInput.radPatternBusyo").prop(
                        "checked",
                        false
                    );
                } else {
                    $(".HDKShiwakeInput.radPatternKyotu").prop(
                        "checked",
                        false
                    );
                    $(".HDKShiwakeInput.radPatternBusyo").prop("checked", true);

                    $(".HDKShiwakeInput.txtPatternBusyo").val(
                        me.clsComFnc.FncNv(data[0]["TAISYO_BUSYO_CD"])
                    );
                }
                $(".HDKShiwakeInput.txtPatternNM").val(
                    me.clsComFnc.FncNv(data[0]["PATTERN_NM"])
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：入力チェックを行う
     '関 数 名：fncInputCheck
     '処理説明：入力チェックを行う
     '**********************************************************************
     */
    me.fncInputCheck = function (blnHissuChk, eventFlag) {
        try {
            if (blnHissuChk == undefined) {
                blnHissuChk = true;
            }
            //税込金額が未入力の場合、エラー
            if (
                $(".HDKShiwakeInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtZeikm_GK");
                    me.clsComFnc.FncMsgBox("E9999", "税込金額が未入力です！");
                    return false;
                }
            } else {
                //税込金額に不正な値が入力されている場合、エラー
                if (
                    me.isPosNumber(
                        $(".HDKShiwakeInput.txtZeikm_GK")
                            .val()
                            .replace(/,/g, "")
                    ) == -1
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtZeikm_GK");
                    me.clsComFnc.FncMsgBox("E0013", "税込金額");
                    return false;
                }
                //税込金額の桁数チェック
                if (
                    $(".HDKShiwakeInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, "").length > 13
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtZeikm_GK");
                    me.clsComFnc.FncMsgBox("E0027", "税込金額", 13);
                    return false;
                }
                //税込金額に負数が入力されている場合、エラー
                if (
                    parseInt(
                        $(".HDKShiwakeInput.txtZeikm_GK")
                            .val()
                            .replace(me.blankReplace, "")
                            .replace(/,/g, "")
                    ) < 0
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtZeikm_GK");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "税込金額に負数が入力されています！"
                    );
                    return false;
                }
                if (blnHissuChk) {
                    var rowId = $(me.grid_id).jqGrid("getGridParam", "selrow");
                    var rowdata = $(me.grid_id).jqGrid("getRowData", rowId);
                    var total = parseInt(
                        $(".HDKShiwakeInput.lblZeikomiGoukei")
                            .val()
                            .replace(/,/g, "")
                    );
                    if (eventFlag == "CMDEVENTINSERT") {
                        total =
                            total +
                            parseInt(
                                $(".HDKShiwakeInput.txtZeikm_GK")
                                    .val()
                                    .replace(/,/g, "")
                            );
                    } else if (eventFlag == "CMDEVENTUPDATE") {
                        total =
                            total -
                            parseInt(rowdata["ZEIKM_GK"]) +
                            parseInt(
                                $(".HDKShiwakeInput.txtZeikm_GK")
                                    .val()
                                    .replace(/,/g, "")
                            );
                    }
                    if (me.clsComFnc.GetByteCount(total.toString()) > 13) {
                        me.clsComFnc.ObjFocus =
                            $(".HDKShiwakeInput.txtZeikm_GK").prop(
                                "disabled"
                            ) == false
                                ? $(".HDKShiwakeInput.txtZeikm_GK")
                                : "";
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "税込金額合計が最大可能桁数を超えています。"
                        );
                        $(".HDKShiwakeInput.txtZeikm_GK").trigger("focus");
                        return;
                    }
                }
            }
            //借方科目コードが未入力の場合、エラー
            if (
                $(".HDKShiwakeInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方科目コードが未入力です！"
                    );
                    return false;
                }
            } else if ($.trim($(".HDKShiwakeInput.txtLKomokuCD").val()) == "") {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLKomokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方補助科目コードが未入力です!"
                    );
                    return false;
                }
            } else {
                // 画面．科目（借方）＋補助科目（借方） 入力時
                var KAMOK_CD = $(".HDKShiwakeInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "");
                var SUB_KAMOK_CD = $(".HDKShiwakeInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, "");
                var LdataExist = false;
                for (var index = 0; index < me.KamokuMstBlank.length; index++) {
                    if (
                        me.KamokuMstBlank[index]["KAMOK_CD"] == KAMOK_CD &&
                        me.KamokuMstBlank[index]["SUB_KAMOK_CD"] == SUB_KAMOK_CD
                    ) {
                        $(".HDKShiwakeInput.lblLKamokuNM").val(
                            me.KamokuMstBlank[index]["KAMOK_NAME"]
                        );
                        $(".HDKShiwakeInput.lblLKoumkNM").val(
                            me.KamokuMstBlank[index]["SUB_KAMOK_NAME"]
                        );
                        LdataExist = true;
                        break;
                    }
                }
                if (!LdataExist) {
                    $(".HDKShiwakeInput.lblLKamokuNM").val("");
                    $(".HDKShiwakeInput.lblLKoumkNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方科目コード・補助科目コードが科目マスタに存在しません！"
                    );
                    return;
                }
            }
            //部署コードが未入力の場合
            if (
                $(".HDKShiwakeInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLBusyoCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方発生部署が未入力です！"
                    );
                    return false;
                }
            } else {
                //対象部署がマスタに存在しない場合
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        me.BusyoMst[index]["BUSYO_CD"] ==
                        $(".HDKShiwakeInput.txtLBusyoCD")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        BusyoMstFlag = true;
                        $(".HDKShiwakeInput.lblLbusyoNM").val(
                            me.BusyoMst[index]["BUSYO_NM"]
                        );
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    $(".HDKShiwakeInput.lblLbusyoNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtLBusyoCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方発生部署が部署マスタに存在しません！"
                    );
                    return false;
                }
            }
            //借方消費税区分が選択されていない場合
            if (
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").prop("selectedIndex") == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HDKShiwakeInput.ddlLSyohizeiKbn"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方消費税区分が選択されていません！"
                    );
                    return false;
                }
            }
            //借方消費税率が選択されていない場合
            if (
                $(".HDKShiwakeInput.ddlLSyouhiKbn").prop("selectedIndex") == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.ddlLSyouhiKbn");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方消費税率が選択されていません！"
                    );
                    return false;
                }
            }
            //貸方科目コードが未入力の場合、エラー
            if (
                $(".HDKShiwakeInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方科目コードが未入力です！"
                    );
                    return false;
                }
            } else if ($.trim($(".HDKShiwakeInput.txtRKomokuCD").val()) == "") {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRKomokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方補助科目コードが未入力です!"
                    );
                    return false;
                }
            } else {
                // 画面．科目（借方）＋補助科目（借方） 入力時
                var KAMOK_CD = $(".HDKShiwakeInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "");
                var SUB_KAMOK_CD = $(".HDKShiwakeInput.txtRKomokuCD")
                    .val()
                    .replace(me.blankReplace, "");
                var RdataExist = false;
                for (var index = 0; index < me.KamokuMstBlank.length; index++) {
                    if (
                        me.KamokuMstBlank[index]["KAMOK_CD"] == KAMOK_CD &&
                        me.KamokuMstBlank[index]["SUB_KAMOK_CD"] == SUB_KAMOK_CD
                    ) {
                        $(".HDKShiwakeInput.lblRKamokuNM").val(
                            me.KamokuMstBlank[index]["KAMOK_NAME"]
                        );
                        $(".HDKShiwakeInput.lblRKoumkNM").val(
                            me.KamokuMstBlank[index]["SUB_KAMOK_NAME"]
                        );
                        RdataExist = true;
                        break;
                    }
                }
                if (!RdataExist) {
                    $(".HDKShiwakeInput.lblRKamokuNM").val("");
                    $(".HDKShiwakeInput.lblRKoumkNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRKamokuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方科目コード・補助科目コードが科目マスタに存在しません！"
                    );
                    return;
                }
            }
            //貸方部署コードが未入力の場合
            if (
                $(".HDKShiwakeInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRbusyoCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方発生部署が未入力です！"
                    );
                    return false;
                }
            } else {
                //貸方部署がマスタに存在しない場合
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        $(".HDKShiwakeInput.txtRbusyoCD")
                            .val()
                            .replace(me.blankReplace, "") ==
                        me.BusyoMst[index]["BUSYO_CD"]
                    ) {
                        BusyoMstFlag = true;
                        $(".HDKShiwakeInput.lblRbusyoNM").val(
                            me.BusyoMst[index]["BUSYO_NM"]
                        );
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    $(".HDKShiwakeInput.lblRbusyoNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtRbusyoCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方発生部署が部署マスタに存在しません！"
                    );
                    return false;
                }
            }
            //貸方消費税区分が選択されていない場合
            if (
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").prop("selectedIndex") == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HDKShiwakeInput.ddlRSyohizeiKbn"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方消費税区分が選択されていません！"
                    );
                    return false;
                }
            }
            //貸方消費税率が選択されていない場合
            if (
                $(".HDKShiwakeInput.ddlRSyouhiKbn").prop("selectedIndex") == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.ddlRSyouhiKbn");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方消費税率が選択されていません！"
                    );
                    return false;
                }
            }
            //摘要に半角文字以外が入力されている場合、エラー
            if (
                $(".HDKShiwakeInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    me.clsComFnc.GetByteCount(
                        $(".HDKShiwakeInput.txtTekyo")
                            .val()
                            .replace(me.blankReplace, "")
                    ) > 240
                ) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.txtTekyo");
                    me.clsComFnc.FncMsgBox("E0027", "摘要", "240");
                    return false;
                }
            }
            if (
                $(".HDKShiwakeInput.lblKensakuCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.lblKensakuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "取引先コードが未入力です！"
                    );
                    return false;
                }
            } else {
                //対象取引先がマスタに存在しない場合
                var TorihikiFlag = false;
                for (var index = 0; index < me.Torihiki.length; index++) {
                    if (
                        me.Torihiki[index]["TORIHIKISAKI_CD"] ==
                        $(".HDKShiwakeInput.lblKensakuCD")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        TorihikiFlag = true;
                        $(".HDKShiwakeInput.lblKensakuNM").val(
                            me.Torihiki[index]["TORIHIKISAKI_NAME"]
                        );
                        break;
                    }
                }
                if (!TorihikiFlag) {
                    $(".HDKShiwakeInput.lblKensakuNM").val("");
                    me.clsComFnc.ObjFocus = $(".HDKShiwakeInput.lblKensakuCD");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "取引先が取引先マスタに存在しません！"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：摘要変換
     '関 数 Tekiyo_TextChanged
     '処理説明：全文字強制全角変換を廃止し、英数字記号は半角に変換
     '**********************************************************************
     */
    me.Tekiyo_TextChanged = function (sender) {
        try {
            var patt = /[0-9a-zA-Z０-９ａ-ｚＡ-Ｚ]*$/g;
            if (
                $(sender)
                    .val()
                    .replace(me.blankReplace, "")
                    .toString()
                    .match(patt)
            ) {
                $(sender).val($(sender).val().toString().toHankaku());
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：fncInputNothingCheck
     '処理説明：
     '**********************************************************************
     */
    me.fncInputNothingCheck = function () {
        try {
            if (
                $(".HDKShiwakeInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.ddlLSyohizeiKbn").prop("selectedIndex") > 0
            ) {
                return false;
            }
            if ($(".HDKShiwakeInput.ddlLSyouhiKbn").prop("selectedIndex") > 0) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtRKomokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.lblKensakuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiwakeInput.ddlRSyohizeiKbn").prop("selectedIndex") > 0
            ) {
                return false;
            }
            if ($(".HDKShiwakeInput.ddlRSyouhiKbn").prop("selectedIndex") > 0) {
                return false;
            }
            return true;
        } catch (ex) {
            console.log(ex);
            return false;
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
            if (!text) {
                return -1;
            } else if ($.trim(text) == "") {
                return 0;
            } else if ($.trim(text).indexOf("-", 0) != -1) {
                return -1;
            } else if ($.trim(text).indexOf(".") != -1) {
                return -1;
            } else {
                return $.trim(text);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：
     '関 数 名：toMoney
     '処理説明：
     '**********************************************************************
     */
    me.toMoney = function (sender, number) {
        try {
            if (!me.HDKAIKEI.KinsokuMojiCheck(sender, me.clsComFnc)) {
                return "";
            }
            if ($.trim(number) == "") {
                return;
            }
            var txtValue = number.toString().replace(/,/g, "");
            //0.11,00.11
            if (/\b(0+\.)/gi.test(txtValue)) {
                txtValue = $.trim(txtValue).replace(/\b(0+\.)/gi, "0.");
            } else {
                txtValue = $.trim(txtValue).replace(/\b(0+)/gi, "");
            }
            var strNewval = txtValue.split(".");
            if (strNewval.length > 2) {
                me.clsComFnc.ObjFocus = sender;
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                return "";
            }
            if (isNaN(txtValue * 1)) {
                me.clsComFnc.ObjFocus = sender;
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                return "";
            }
            if (strNewval.length == 2) {
                //1111111.2222
                return txtValue == ""
                    ? 0
                    : strNewval[0]
                          .toString()
                          .replace(/(\d{1,3})(?=(\d{3})+(?:$))/g, "$1,") +
                          "." +
                          strNewval[1];
            } else {
                return txtValue == ""
                    ? 0
                    : txtValue
                          .toString()
                          .replace(/(\d{1,3})(?=(\d{3})+(?:$|\.))/g, "$1,");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：項目の桁数チェク
     '関 数 名：FncCheckByteLength
     '引 数 １：str　文字列
     '引 数 ２：maxlen　最大桁数
     '戻 り 値：True:最大桁数以内,False:最大桁数以外
     '処理説明：項目の桁数をチェクする
     '**********************************************************************
     */
    me.FncCheckByteLength = function (str, maxlen) {
        try {
            var len = str.length;
            var reLen = 0;
            for (var i = 0; i < len; i++) {
                // 全角
                if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) {
                    reLen += 2;
                } else {
                    reLen++;
                }
                if (reLen > maxlen) {
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
            return false;
        }
    };
    /*
     '**********************************************************************
     '処 理 名：パターン対象部署クリック
     '関 数 名：radPattern_CheckedChanged
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.radPattern_CheckedChanged = function (pNo) {
        try {
            if (pNo == 1) {
                $(".HDKShiwakeInput.txtPatternBusyo").val("");
                $(".HDKShiwakeInput.txtPatternBusyo").attr(
                    "disabled",
                    "disabled"
                );
            } else {
                $(".HDKShiwakeInput.txtPatternBusyo").attr("disabled", false);
                $(".HDKShiwakeInput.txtPatternBusyo").trigger("focus");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：添付ファイルクリック
     '関 数 名：fileOpenDialog
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fileOpenDialog = function () {
        try {
            $("#HDKKamokuSearchDialogDiv").dialog({
                autoOpen: false,
                modal: true,
                height: me.ratio === 1.5 ? 530 : 630,
                width: 500,
                resizable: false,
                close: function () {},
            });

            var url = me.sys_id + "/" + frmId;

            me.ajax.send(url, "", 0);
            me.ajax.receive = function (result) {
                $("#" + dialogId).html(result);
                $("#" + dialogId).dialog("option", "title", title);
                $("#" + dialogId).dialog("open");
            };
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：検索ﾎﾞﾀﾝクリック
     '関 数 名：openSearchDialog
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divkuCD = "";
        var divNM = "";
        var frmId = "";
        var title = "";
        var $txtSearchkuCD = undefined;
        var cd = "RtnCD";
        //取引先 + 社員
        var $txtSearchCD = $(".HDKShiwakeInput.lblKensakuCD");
        var $txtSearchNM = $(".HDKShiwakeInput.lblKensakuNM");

        var divSYOHY_NO = "";
        var divEDA_NO = "";
        var divGYO_NO = "";
        var divFromView = "";
        var divEditFlag = "";

        switch (searchButton) {
            // 添付ファイル
            case "HDKAttachment":
                frmId = "HDKAttachment";
                dialogId = "HDKAttachmentDialogDiv";
                title = "添付ファイル";
                divSYOHY_NO = "SYOHY_NO15";
                divEDA_NO = "EDA_NO";
                divGYO_NO = "GYO_NO";
                divFromView = "From_View";
                divEditFlag = "MAX_SYORI_FLG";
                break;
            case "btnLKamokuSearch":
            case "btnRKamokuSearch":
                //科目検索
                dialogId = "HDKKamokuSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HDKShiwakeInput.txtRKamokuCD")
                        : $(".HDKShiwakeInput.txtLKamokuCD");
                $txtSearchkuCD =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HDKShiwakeInput.txtRKomokuCD")
                        : $(".HDKShiwakeInput.txtLKomokuCD");
                $txtSearchNM =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HDKShiwakeInput.lblRKamokuNM")
                        : $(".HDKShiwakeInput.lblLKamokuNM");
                divCD = "KamokuCD";
                divkuCD = "KoumkuCD";
                divNM = "KamokuNM";
                frmId = "HDKKamokuSearch";
                title = "科目マスタ検索";
                break;
            case "btnLBusyoSearch":
            case "btnRBusyoSearch":
                //部署検索
                dialogId = "HDKBusyoSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HDKShiwakeInput.txtRbusyoCD")
                        : $(".HDKShiwakeInput.txtLBusyoCD");
                $txtSearchNM =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HDKShiwakeInput.lblRbusyoNM")
                        : $(".HDKShiwakeInput.lblLbusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HDKBusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnTorihikiSearch":
                //取引先
                dialogId = "HDKTorihikisakiSearchDialogDiv";
                divCD = "KensakuCD";
                divNM = "KensakuNM";
                frmId = "HDKTorihikisakiSearch";
                title = "取引先マスタ検索";
                break;
            // case "btnSyainSearch":
            // 	//社員
            // 	dialogId = "HDKSyainSearchDialogDiv";
            // 	divCD = "SyainCD";
            // 	divNM = "SyainNM";
            // 	frmId = "HDKSyainSearch";
            // 	title = "社員マスタ検索";
            // 	break;
            default:
        }

        var $rootDiv = $(".HDKShiwakeInput.HDKAIKEI-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        if (searchButton != "HDKAttachment") {
            $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
            $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        } else {
            $("<div></div>")
                .attr("id", divSYOHY_NO)
                .insertAfter($rootDiv)
                .hide();
            $("<div></div>").attr("id", divEDA_NO).insertAfter($rootDiv).hide();
            $("<div></div>").attr("id", divGYO_NO).insertAfter($rootDiv).hide();
            $("<div></div>")
                .attr("id", divFromView)
                .insertAfter($rootDiv)
                .hide();
            $("<div></div>")
                .attr("id", divEditFlag)
                .insertAfter($rootDiv)
                .hide();
        }
        if (
            searchButton == "btnLKamokuSearch" ||
            searchButton == "btnRKamokuSearch"
        ) {
            $("<div></div>").attr("id", divkuCD).insertAfter($rootDiv).hide();
        }
        // if (searchButton == "btnSyainSearch") {
        // 	$("<div></div>").attr("id", "syain").insertAfter($rootDiv).hide();
        // 	var $syainSearch = $rootDiv.parent().find("#" + "syain");
        // 	$syainSearch.val("syain");
        // }
        var $RtnCD = $rootDiv.parent().find("#" + cd);
        if (searchButton != "HDKAttachment") {
            var $SearchCD = $rootDiv.parent().find("#" + divCD);
            var $SearchNM = $rootDiv.parent().find("#" + divNM);
        } else {
            var $SYOHY_NO = $rootDiv.parent().find("#" + divSYOHY_NO);
            var $EDA_NO = $rootDiv.parent().find("#" + divEDA_NO);
            var $GYO_NO = $rootDiv.parent().find("#" + divGYO_NO);
            var $FromView = $rootDiv.parent().find("#" + divFromView);
            var $EditFlag = $rootDiv.parent().find("#" + divEditFlag);
        }
        var $SearchkuCD = undefined;
        if (
            searchButton == "btnLKamokuSearch" ||
            searchButton == "btnRKamokuSearch"
        ) {
            $SearchkuCD = $rootDiv.parent().find("#" + divkuCD);
        }
        var width = me.ratio === 1.5 ? 700 : 720;
        var height = me.ratio === 1.5 ? 530 : 630;
        if (searchButton == "HDKAttachment") {
            $SYOHY_NO.html(me.selectedData.SYOHY_NO);
            $EDA_NO.html(me.selectedData.EDA_NO);
            $GYO_NO.html(me.selectedData.GYO_NO);
            $FromView.html("HDKShiwakeInput");
            $EditFlag.html(
                me.hidMode == "8" || me.hidMode == "9" ? true : false
            );
            var userAgent = navigator.userAgent;
            var isIE =
                userAgent.indexOf("compatible") > -1 &&
                userAgent.indexOf("MSIE") > -1;
            var isIE11 =
                userAgent.indexOf("Trident") > -1 &&
                userAgent.indexOf("rv:11.0") > -1;
            width = me.ratio === 1.5 ? 1085 : 1190;
            height =
                me.hidDispNO == "103"
                    ? me.ratio === 1.5
                        ? 295
                        : 325
                    : isIE || isIE11
                    ? me.ratio === 1.5
                        ? 535
                        : 675
                    : me.ratio === 1.5
                    ? 535
                    : 690;
        } else {
            $SearchCD.val($.trim($txtSearchCD.val()));
        }
        $(".HDKShiwakeInput.txtTekyo").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: width,
            resizable: searchButton == "HDKAttachment" ? true : false,
            close: function () {
                //20211208 WANGYING INS S
                //change
                if (searchButton != "HDKAttachment") {
                    // var changeFlag = true;
                    // if (
                    //     (searchButton == "btnLKamokuSearch" ||
                    //         searchButton == "btnRKamokuSearch") &&
                    //     $SearchkuCD.html() != "" &&
                    //     $SearchkuCD.html() == $txtSearchkuCD.val()
                    // ) {
                    //     changeFlag = false;
                    // }
                    //20211208 WANGYING INS E

                    if ($RtnCD.html() == 1) {
                        $txtSearchCD.val($SearchCD.html());
                        $txtSearchNM.val($SearchNM.html());

                        if (
                            searchButton == "btnLKamokuSearch" ||
                            searchButton == "btnRKamokuSearch"
                        ) {
                            $txtSearchkuCD.val($SearchkuCD.html());
                        }
                        if (searchButton == "btnLKamokuSearch") {
                            me.txtLKamokuCD_TextChanged();
                        } else if (searchButton == "btnRKamokuSearch") {
                            me.txtRKamokuCD_TextChanged();
                        }
                    }
                    $RtnCD.remove();
                    $SearchCD.remove();
                    $SearchNM.remove();

                    if (
                        searchButton == "btnLKamokuSearch" ||
                        searchButton == "btnRKamokuSearch"
                    ) {
                        $SearchkuCD.remove();
                    } else {
                        $(".HDKShiwakeInput." + searchButton).trigger("focus");
                    }
                    // if (searchButton == "btnSyainSearch") {
                    // 	$syainSearch.remove();
                    // }
                } else {
                    $RtnCD.remove();
                    $SYOHY_NO.remove();
                    $EDA_NO.remove();
                    $GYO_NO.remove();
                    $FromView.remove();
                    $EditFlag.remove();
                    var url =
                        me.sys_id + "/" + me.id + "/" + "fncSelShiwakeData";
                    var data = {
                        SYOHY_NO: me.selectedData.SYOHY_NO,
                        EDA_NO: me.selectedData.EDA_NO,
                        GYO_NO: me.selectedData.GYO_NO,
                        fileExist: true,
                    };
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (!result["result"]) {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            return;
                        }
                        if (result["data"]["fileExist"]) {
                            $(".HDKShiwakeInput.hasFileFlg").text("ある");
                        } else {
                            $(".HDKShiwakeInput.hasFileFlg").text("なし");
                        }
                    };
                    me.ajax.send(url, data, 0);
                }
                $("#" + dialogId).remove();
            },
        });

        var url = me.sys_id + "/" + frmId;

        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#" + dialogId).html(result);
            $("#" + dialogId).dialog("option", "title", title);
            $("#" + dialogId).dialog("open");
        };
    };
    me.before_close = function () {};
    var ele = document.querySelector(".HDKShiwakeInput.HDKAIKEI-content");
    var resizeObserver = new ResizeObserver(function () {
        if (me.hidDispNO == "") {
            // 20241226 YIN UPS S
            // setTimeout(() => {
            setTimeout(function () {
                // 20241226 YIN UPS E
                me.setTableSize();
            }, 500);
        }
    });
    resizeObserver.observe(ele);
    window.onresize = function () {
        me.setTableSize();
    };
    me.setTableSize = function () {
        var pageWidth = 0;
        var pageHeight = 0;
        if (me.hidDispNO == "") {
            pageWidth = $(".HDKShiwakeInput.HDKAIKEI-content").width();
            $(me.grid_id).setGridWidth(
                pageWidth * 0.98 > 1100
                    ? me.ratio === 1.5
                        ? 1022
                        : 1100
                    : pageWidth * 0.98
            );
            pageHeight = $(".HDKAIKEI.HDKAIKEI-layout-center").height() - 380;
            $(me.grid_id).setGridHeight(pageHeight < 100 ? 100 : pageHeight);
        } else if (
            me.hidDispNO == "100" ||
            me.hidDispNO == "ReOut4OBC" ||
            me.hidDispNO == "ReOut4ZenGin"
        ) {
            pageWidth = $(".HDKShiwakeInput.HDKAIKEI-content").width();
            $(me.grid_id).setGridWidth(pageWidth * 0.98);
            pageHeight =
                $(
                    ".HDKShiwakeInput.body.ui-dialog-content.ui-widget-content"
                ).height() - (me.ratio === 1.5 ? 320 : 380);
            $(me.grid_id).setGridHeight(pageHeight < 100 ? 100 : pageHeight);
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HDKAIKEI_HDKShiwakeInput = new HDKAIKEI.HDKShiwakeInput();
    o_HDKAIKEI_HDKAIKEI.o_HDKAIKEI_HDKShiwakeInput = o_HDKAIKEI_HDKShiwakeInput;
    if (o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch) {
        o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiwakeInput =
            o_HDKAIKEI_HDKShiwakeInput;
        o_HDKAIKEI_HDKShiwakeInput.HDKDenpyoSearch =
            o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch;
    }
    if (o_HDKAIKEI_HDKAIKEI.HDKPatternSearch) {
        o_HDKAIKEI_HDKAIKEI.HDKPatternSearch.HDKShiwakeInput =
            o_HDKAIKEI_HDKShiwakeInput;
        o_HDKAIKEI_HDKShiwakeInput.HDKPatternSearch =
            o_HDKAIKEI_HDKAIKEI.HDKPatternSearch;
    }
    if (o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin) {
        o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin.HDKShiwakeInput =
            o_HDKAIKEI_HDKShiwakeInput;
        o_HDKAIKEI_HDKShiwakeInput.HDKReOut4ZenGin =
            o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin;
    }
    if (o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC) {
        o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC.HDKShiwakeInput =
            o_HDKAIKEI_HDKShiwakeInput;
        o_HDKAIKEI_HDKShiwakeInput.HDKReOut4OBC =
            o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC;
    }
    //20240318 lujunxia ins s
    if (o_HDKAIKEI_HDKAIKEI.HDKOut4OBC) {
        o_HDKAIKEI_HDKAIKEI.HDKOut4OBC.HDKShiwakeInput =
            o_HDKAIKEI_HDKShiwakeInput;
    }
    //20240318 lujunxia ins e
    o_HDKAIKEI_HDKShiwakeInput.load();
});
