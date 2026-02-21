/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 ** 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															担当
 * YYYYMMDD						#ID							　　　　XXXXXX															GSDL
 * 20240417        				svn-ver.38694					VBソース変更											            lqs
 * 20240426						すべての項目・ボタン群が一度に表示されるように微調整														lqs
 * 20250124                   パターン選択から行追加するとフリーズする現象が出ました                                                     yin
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMDPS.HMDPS101ShiwakeDenpyoInput");

HMDPS.HMDPS101ShiwakeDenpyoInput = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HMDPS = new HMDPS.HMDPS();
    me.id = "HMDPS101ShiwakeDenpyoInput";
    me.sys_id = "HMDPS";
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.grid_id = "#HMDPS101ShiwakeDenpyoInput_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";
    me.ratio = window.devicePixelRatio || 1;

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

    me.KamokuMstBlank = [];
    me.KamokuMstNotBlank = [];

    me.BusyoMst = [];

    me.option = {
        caption: "",
        rowNum: 0,
        rownumbers: true,
        multiselect: false,
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
            width: me.ratio === 1.5 ? 130 : 155,
            align: "left",
            sortable: false,
        },
        {
            label: "貸方科目",
            name: "R_KAMOKU",
            index: "R_KAMOKU",
            width: me.ratio === 1.5 ? 130 : 155,
            align: "left",
            sortable: false,
        },
        {
            label: "税込金額",
            name: "ZEIKM_GK",
            index: "ZEIKM_GK",
            width: me.ratio === 1.5 ? 72 : 97,
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
            width: me.ratio === 1.5 ? 72 : 97,
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
            width: me.ratio === 1.5 ? 65 : 90,
            align: "left",
            sortable: false,
            formatter: function (_cellvalue, options) {
                var detail =
                    "<button onclick=\"grdIchiran_SelectedIndexChanged('" +
                    options.rowId +
                    "')\" id = '" +
                    options.rowId +
                    "_btnEdit' class=\"HMDPS101ShiwakeDenpyoInput btnEdit Tab Enter\" tabindex='47' style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>選択</button>";
                return detail;
            },
        },
    ];
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMDPS101ShiwakeDenpyoInput.HMDPS101ShiwakeDenpyoInputBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMDPS.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HMDPS.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HMDPS.EnterKeyDown(me.id);

    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").on("keydown", function (e) {
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
    $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").click(function () {
        me.radPattern_CheckedChanged(1);
    });
    $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").click(function () {
        me.radPattern_CheckedChanged(2);
    });
    //修正前表示ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").click(function () {
        me.btnSyuseiMaeDisp_Click();
    });
    //最新表示ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").click(function () {
        me.btnSaishinDisp_Click();
    });
    //行追加ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").click(function () {
        me.btnAdd_Click();
    });
    //行変更ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").click(function () {
        me.btnUpdate_Click();
    });
    //行削除ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox("QY017");
    });
    //クリアﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnClear").click(function () {
        me.btnClear_Click();
    });
    //表示されている仕訳をパターンとして登録ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").click(function () {
        me.btnPatternTrk_Click();
    });
    //全確定ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").click(function () {
        me.btnKakutei_Click();
    });
    //登録ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnPtnInsert").click(function () {
        me.btnPtnInsert_Click("btnPtnInsert");
    });
    //更新ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate").click(function () {
        me.btnPtnInsert_Click("btnPtnUpdate");
    });
    //全削除ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").click(function () {
        me.btnAllDelete_Click();
    });
    //削除ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnPtnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnPtnDelete_Click;
        me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
    });
    //閉じるﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnClose").click(function () {
        $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
    });
    //社員検索ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnSyainSearch").click(function () {
        me.openSearchDialog("btnSyainSearch");
    });
    //[借方]検索①ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnLKamokuSearch").click(function () {
        me.openSearchDialog("btnLKamokuSearch");
    });
    //[借方]検索②ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnLBusyoSearch").click(function () {
        me.openSearchDialog("btnLBusyoSearch");
    });
    //[貸方]検索①ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnRKamokuSearch").click(function () {
        me.openSearchDialog("btnRKamokuSearch");
    });
    //[貸方]検索②ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnRBusyoSearch").click(function () {
        me.openSearchDialog("btnRBusyoSearch");
    });
    //取引先検索ﾎﾞﾀﾝクリック
    $(".HMDPS101ShiwakeDenpyoInput.btnTorihikiSearch").click(function () {
        me.openSearchDialog("btnTorihikiSearch");
    });
    //パターン選択change
    $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").change(function () {
        me.ddlPatternSel_SelectedIndexChanged();
    });
    // 20240417 lqs INS S
    //相手先区分変更
    $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").change(function () {
        me.ddlAitesakiKBN_SelectedIndexChanged();
    });
    //お客様名／取引先名取得
    $(".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm").change(
        function () {
            me.txtOkyakusamaNOTorihikisakiNm_TextChanged();
        }
    );
    //特例区分変更
    $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").change(function () {
        me.ddlTokureiKBN_SelectedIndexChanged();
    });
    // 20240417 lqs INS E
    //消費税区分[借方]change
    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").change(function () {
        me.ddlLSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税区分[貸方]change
    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").change(function () {
        me.ddlRSyohizeiKbn_SelectedIndexChanged();
    });
    //摘要に半角文字を入力された場合、全角に変換する
    $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").change(function () {
        $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val(
            me.HMDPS.halfToFull(
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "")
                    .toString()
                    .toZenkaku()
            )
        );
    });
    //科目[借方]change
    $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").change(function () {
        me.txtLKamokuCD_TextChanged("txtLKamokuCD");
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").change(function () {
        me.txtLKomokuCD_TextChanged("txtLKomokuCD");
    });
    //科目[貸方]change
    $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").change(function () {
        me.txtRKamokuCD_TextChanged("txtRKamokuCD");
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").change(function () {
        me.txtRKomokuCD_TextChanged("txtRKomokuCD");
    });
    //部署change
    $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD").change(function () {
        me.txtLBusyoCD_TextChanged("txtLBusyoCD");
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD").change(function () {
        me.txtRBusyoCD_TextChanged("txtRbusyoCD");
    });
    //税込金額change
    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").change(function () {
        $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val(
            me.toMoney(
                $(this),
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val()
            )
        );

        me.txtZeikm_GK_TextChanged();
    });
    //税抜金額change
    $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").change(function () {
        $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text(
            me.toMoney(
                $(this),
                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text()
            )
        );
    });
    //消費税金額change
    $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").change(function () {
        $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text(
            me.toMoney(
                $(this),
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text()
            )
        );
    });
    //口座キー変換
    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
    });
    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").change(function () {
        me.txtLKouzaKey1_TextChanged(this);
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

            // 20240417 lqs UPD S
            // if (window.ActiveXObject || "ActiveXObject" in window) {
            // 	if ($(window).height() <= 950) {
            // 画面内容较多，IE显示不全，追加纵向滚动条
            // $(".HMDPS.HMDPS-layout-center").css("overflow-y", "scroll");
            // 	}
            // }
            var cnt = $(".HMDPS.HMDPS-layout-center").children().length;
            if (
                $(".HMDPS.HMDPS-layout-center")
                    .children()
                    [cnt - 1].className.indexOf("HMDPS101ShiwakeDenpyoInput") >
                -1
            ) {
                // 20240426 lqs UPD S
                // $(".HMDPS.HMDPS-layout-center").css("overflow-y", "scroll");
                $(
                    ".HMDPS.HMDPS-layout-center .HMDPS101ShiwakeDenpyoInput.HMDPS-content"
                ).css("transform-origin", "top left");
                $(
                    ".HMDPS.HMDPS-layout-center .HMDPS101ShiwakeDenpyoInput.HMDPS-content"
                ).css("transform", "scale(0.85)");
                // 20240426 lqs UPD E
            }
            // 20240417 lqs UPD E

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
    // 20250411 lujunxia upd s
    // me.Page_Load = function () {
    me.Page_Load = function (flg = "") {
        // 20250411 lujunxia upd e
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
                    me.PatternID === me.HMDPS.CONST_ADMIN_PTN_NO ||
                    me.PatternID === me.HMDPS.CONST_HONBU_PTN_NO
                        ? true
                        : false,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    $(".HMDPS101ShiwakeDenpyoInput.btnPtnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnPtnInsert").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");

                    $(".HMDPS101ShiwakeDenpyoInput.btnClose").hide();

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
                me.BusyoCD = result["BusyoCD"];
                me.BusyoMst = result["data"]["BusyoMst"];
                me.KamokuMstBlank = result["data"]["KamokuMstBlank"];
                me.KamokuMstNotBlank = result["data"]["KamokuMstNotBlank"];
                //モードの設定
                if (strMode == "" || strMode == undefined) {
                    $(".HMDPS101ShiwakeDenpyoInput.btnClose").hide();
                    //メニューから開かれた場合は新規モードに設定する
                    me.hidMode = "1";
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.btnClose").show();
                    var userAgent = navigator.userAgent;
                    var isIE =
                        userAgent.indexOf("compatible") > -1 &&
                        userAgent.indexOf("MSIE") > -1;
                    var isIE11 =
                        userAgent.indexOf("Trident") > -1 &&
                        userAgent.indexOf("rv:11.0") > -1;
                    // 20250411 lujunxia ins s
                    if (flg !== "opening") {
                        // 20250411 lujunxia ins e
                        $(".HMDPS101ShiwakeDenpyoInput.body").dialog({
                            autoOpen: false,
                            // 20240426 lqs UPD S
                            // width: 1190,
                            // height: strDispNO == "103" ? 570 : isIE || isIE11 ? 670 : 710,
                            width:
                                strDispNO !== "103" && !isIE && !isIE11
                                    ? me.ratio === 1.5
                                        ? 883
                                        : 1070
                                    : me.ratio === 1.5
                                    ? 969
                                    : 1190,
                            height:
                                strDispNO == "103"
                                    ? me.ratio === 1.5
                                        ? 470
                                        : 590
                                    : isIE || isIE11
                                    ? 670
                                    : me.ratio === 1.5
                                    ? 558
                                    : 710,
                            // 20240426 lqs UPD S
                            modal: true,
                            title: "仕訳伝票入力",
                            open: function () {
                                //20240426 lqs INS S
                                if (strDispNO !== "103" && !isIE && !isIE11) {
                                    $(
                                        ".ui-dialog .HMDPS101ShiwakeDenpyoInput.HMDPS-content"
                                    ).css("transform-origin", "top left");
                                    $(
                                        ".ui-dialog .HMDPS101ShiwakeDenpyoInput.HMDPS-content"
                                    ).css("transform", "scale(0.91)");
                                    $(
                                        ".ui-dialog .HMDPS101ShiwakeDenpyoInput.body"
                                    ).css("overflow-y", "hidden");
                                    var width =
                                        me.ratio === 1.5 ? "952px" : "1150px";
                                    $(
                                        ".ui-dialog .HMDPS101ShiwakeDenpyoInput.body"
                                    ).css("width", width);
                                }
                                //20240426 lqs INS E
                            },
                            close: function () {
                                me.before_close();
                                $(".HMDPS101ShiwakeDenpyoInput.body").remove();
                            },
                        });
                        $(".HMDPS101ShiwakeDenpyoInput.body").dialog("open");
                        // 20250411 lujunxia ins s
                    }
                    // 20250411 lujunxia ins e
                    //メニュー以外から開かれた場合は指定されたモードをセットする
                    me.hidMode = strMode;
                }
                $(".HMDPS101ShiwakeDenpyoInput.clearLabel").height(
                    $(".HMDPS101ShiwakeDenpyoInput.lblZeikm_GK_NM").height()
                );
                //画面項目をクリアする
                me.subFormClear();
                //ボタンを使用不可にする
                me.DpyInpNewButtonEnabled("99");
                //口座キー、必須摘要を不活性にする
                me.KouzaHiTekkiEnabledSet(false);
                //ドロップダウンリストを設定する,パターンのドロップダウンリストを設定する
                me.DropDownListSet(result);

                //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                switch (me.PatternID) {
                    case me.HMDPS.CONST_ADMIN_PTN_NO:
                    case me.HMDPS.CONST_HONBU_PTN_NO:
                        $(".HMDPS101ShiwakeDenpyoInput.pnlTenpo").css(
                            "display",
                            "none"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.pnlHonbu").css(
                            "display",
                            "table-row"
                        );
                        break;
                    default:
                        //メモ欄を設定する
                        me.MemoSet(result["data"]["MemoTbl"]);

                        $(".HMDPS101ShiwakeDenpyoInput.pnlTenpo").css(
                            "display",
                            "block"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.pnlHonbu").css(
                            "display",
                            "none"
                        );
                        break;
                }
                switch (strDispNO) {
                    //伝票検索画面又はＣＳＶ再出力画面から開かれた場合
                    case "100":
                    case "105":
                        $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(true);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(false);
                        //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT"
                        ).datepicker("disable");
                        switch (me.PatternID) {
                            case me.HMDPS.CONST_ADMIN_PTN_NO:
                            case me.HMDPS.CONST_HONBU_PTN_NO:
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).show();
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).button("enable");
                                break;
                            default:
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).hide();
                                break;
                        }
                        switch (strMode) {
                            //新規作成の場合
                            case "1":
                                //ボタンの活性・不活性を決める(新規の場合)
                                me.DpyInpNewButtonEnabled("1");
                                //修正前表示ボタンを不活性にする
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).button("enable");

                                $(me.grid_id).jqGrid("clearGridData");

                                gdmz.common.jqgrid.init(
                                    me.grid_id,
                                    me.g_url,
                                    me.colModel,
                                    "",
                                    "",
                                    me.option
                                );
                                var width = me.ratio === 1.5 ? 920 : 1065;
                                var height = me.ratio === 1.5 ? 72 : 107;
                                gdmz.common.jqgrid.set_grid_width(
                                    me.grid_id,
                                    width
                                );
                                gdmz.common.jqgrid.set_grid_height(
                                    me.grid_id,
                                    height
                                );
                                $(me.grid_id + "_rn").html("№");
                                $(me.grid_id).jqGrid("bindKeys");

                                if (
                                    !$(
                                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                                    ).is(":disabled")
                                ) {
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                                    ).trigger("focus");
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
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblSyohy_no"
                                    ).val(strAllSyohy_No);
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
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblKensu"
                                    ).val(
                                        me.toMoney(
                                            $(
                                                ".HMDPS101ShiwakeDenpyoInput.lblKensu"
                                            ),
                                            IchiranTbl.length
                                        )
                                    );
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei"
                                    ).val(
                                        me.toMoney(
                                            $(
                                                ".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei"
                                            ),
                                            lngKingaku
                                        )
                                    );
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei"
                                    ).val(
                                        me.toMoney(
                                            $(
                                                ".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei"
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
                                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                                            ).button("disable");
                                        } else {
                                            //修正前データが存在する場合
                                            //修正前表示ボタンを活性にする
                                            $(
                                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
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
                                        //既にＣＳＶ出力されている場合
                                        //表示モードを指定する
                                        var DispModeTbl =
                                            result["data"]["DispModeTbl"];
                                        if (
                                            me.clsComFnc.FncNv(
                                                DispModeTbl[0]["CSV_OUT_FLG"] ==
                                                    "1"
                                            ) ||
                                            (me.clsComFnc.FncNv(
                                                DispModeTbl[0][
                                                    "HONBU_SYORIZUMI_FLG"
                                                ] == "1"
                                            ) &&
                                                me.PatternID !=
                                                    me.HMDPS
                                                        .CONST_ADMIN_PTN_NO &&
                                                me.PatternID !=
                                                    me.HMDPS.CONST_HONBU_PTN_NO)
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
                                                me.HMDPS.CONST_ADMIN_PTN_NO &&
                                            me.PatternID !=
                                                me.HMDPS.CONST_HONBU_PTN_NO
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
                                    //10行を超える場合は行追加ボタンを不活性に設定する
                                    if (IchiranTbl.length >= 10) {
                                        $(
                                            ".HMDPS101ShiwakeDenpyoInput.btnAdd"
                                        ).button("disable");
                                    }

                                    if (
                                        !$(
                                            ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                                        ).is(":disabled")
                                    ) {
                                        $(
                                            ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
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
                                var width = me.ratio === 1.5 ? 920 : 1068;
                                var height = me.ratio === 1.5 ? 72 : 107;
                                gdmz.common.jqgrid.set_grid_width(
                                    me.grid_id,
                                    width
                                );
                                gdmz.common.jqgrid.set_grid_height(
                                    me.grid_id,
                                    height
                                );
                                $(me.grid_id + "_rn").html("№");
                                $(me.grid_id).jqGrid("bindKeys");
                                break;
                            default:
                                break;
                        }
                        break;
                    //パターン検索画面から表示された場合
                    case "103":
                        $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(false);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(true);
                        //経理処理日を非表示にする
                        $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").css(
                            "visibility",
                            "hidden"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.lblKeiriSyoriDT_NM").css(
                            "visibility",
                            "hidden"
                        );
                        //仕訳伝票入力用項目を非表示にする
                        me.ForPatternVisible();
                        $(".HMDPS101ShiwakeDenpyoInput.btnPtnDelete").button(
                            "disable"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.btnPtnInsert").button(
                            "disable"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate").button(
                            "disable"
                        );
                        switch (me.clsComFnc.FncNv(strMode)) {
                            //新規の場合
                            case "1":
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnDelete"
                                ).button("disable");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnInsert"
                                ).button("enable");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnInsert"
                                ).text("登録");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate"
                                ).hide();
                                break;
                            //編集の場合
                            case "2":
                                var PatternTbl =
                                    result["data"]["PatternTbl103"];
                                var LKOUBANTBL = result["data"]["LKOUBANTBL"];
                                var RKOUBANTBL = result["data"]["RKOUBANTBL"];
                                if (PatternTbl.length == 0) {
                                    //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                                    me.clsComFnc.FncMsgBox("W0026");
                                    return;
                                }
                                me.hidPatternNO = strPattern_NO;
                                //パターンデータを画面項目にセットする
                                me.DataFormSet("103", PatternTbl);
                                //口座キー・必須摘要に入力されている値があれば活性にする
                                me.KouzaHittekiEnabledCheck();

                                //口座キー・必須摘要の名称を取得する(借方)
                                me.LKoubanNMSet(LKOUBANTBL, false);
                                //口座キー・必須摘要の名称を取得する(貸方)
                                me.RKoubanNMSet(RKOUBANTBL, false);

                                //ボタンを活性にする
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnDelete"
                                ).button("enable");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnInsert"
                                ).button("enable");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnInsert"
                                ).text("新規登録");
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate"
                                ).show();
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate"
                                ).button("enable");
                        }
                        me.radPatternBusyo_CheckedChanged();
                        // 20240417 lqs UPD S
                        // $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").trigger("focus");
                        $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").trigger(
                            "focus"
                        );
                        // 20240417 lqs UPD E
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
                        var height = me.ratio === 1.5 ? 48 : 70;
                        var width = me.ratio === 1.5 ? 920 : 1065;
                        gdmz.common.jqgrid.set_grid_width(me.grid_id, width);
                        gdmz.common.jqgrid.set_grid_height(
                            me.grid_id,
                            height
                        );
                        $(me.grid_id + "_rn").html("№");
                        $(me.grid_id).jqGrid("bindKeys");
                        //マスターページ設定  メニューから表示された場合はこれが必要
                        $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").hide();
                        //伝票入力画面用ボタンを表示する
                        me.DenpyoInputButtonVisible(true);
                        //パターン登録用ボタンを表示する
                        me.PatternInputButtonVisible(false);
                        //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT"
                        ).datepicker("disable");
                        switch (me.PatternID) {
                            case me.HMDPS.CONST_ADMIN_PTN_NO:
                            case me.HMDPS.CONST_HONBU_PTN_NO:
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).show();
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).button("enable");
                                break;
                            default:
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                                ).hide();
                                break;
                        }
                        //ボタンの活性・不活性を決める(新規の場合)
                        me.DpyInpNewButtonEnabled("1");

                        if (
                            !$(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").is(
                                ":disabled"
                            )
                        ) {
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                            ).trigger("focus");
                        }

                        break;
                }

                //[件]レイアウト設定
                var width =
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_R_KAMOKU").width() +
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_SEQNO").width() -
                    82;
                $(".HMDPS101ShiwakeDenpyoInput#GOUKEITBL").css(
                    "margin-left",
                    width + "px"
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblKensu").width(
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_R_KAMOKU").width() /
                        2
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").width(
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_ZEIKM_GK").width() -
                        3
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei").width(
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_SHZEI_GK").width() -
                        3
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").css(
                    "margin-left",
                    $("#HMDPS101ShiwakeDenpyoInput_sprList_R_KAMOKU").width() /
                        2 -
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
                lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    var SYUSEIMAETBL = result["data"]["SYUSEIMAETBL"][0];

                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val(
                        me.clsComFnc.FncNv(SYUSEIMAETBL["SYOHY_NO"]) +
                            me.clsComFnc.FncNv(SYUSEIMAETBL["EDA_NO"])
                    );

                    $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").show();
                    //画面項目をクリアする
                    me.subFormClear(true);
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    //口座キー、必須摘要を不活性にする
                    me.KouzaHiTekkiEnabledSet(false);
                    //*****参照モードで表示する*****
                    //ボタンを使用不可にする
                    me.DpyInpNewButtonEnabled("9");
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "disable"
                    );

                    //画面項目を不活性にする
                    me.FormEnabled(false);
                    //一覧に表示する
                    var data = {
                        lblSyohy_no: $(
                            ".HMDPS101ShiwakeDenpyoInput.lblSyohy_no"
                        )
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
                        $(".HMDPS101ShiwakeDenpyoInput.lblKensu").val(
                            me.toMoney(
                                $(".HMDPS101ShiwakeDenpyoInput.lblKensu"),
                                IchiranTbl.length
                            )
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").val(
                            me.toMoney(
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei"
                                ),
                                lngKingaku
                            )
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei").val(
                            me.toMoney(
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei"
                                ),
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
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                            ).button("disable");
                        } else {
                            //修正前データが存在する場合
                            //修正前表示ボタンを活性にする
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                            ).button("enable");
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
                lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
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
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val(
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "")
                        .substring(0, 15) +
                        me.clsComFnc.FncNv(NEWTBL[0]["EDA_NO"])
                );

                //画面項目をクリアする
                me.subFormClear(true);
                $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").get(
                    0
                ).selectedIndex = 0;
                $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                    0
                ).selectedIndex = 0;
                $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").get(
                    0
                ).selectedIndex = 0;
                $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                    0
                ).selectedIndex = 0;

                //参照モードと一部参照モード(削除は可能)の場合は画面項目は不活性
                if (me.hidMode == "9" || me.hidMode == "8") {
                } else {
                    me.FormEnabled(true);
                }

                //ボタンを使用不可にする
                me.DpyInpNewButtonEnabled("99");
                //口座キー、必須摘要を不活性にする
                me.KouzaHiTekkiEnabledSet(false);

                //一覧に表示する
                var data = {
                    lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
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
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensu").val(
                        me.toMoney(
                            $(".HMDPS101ShiwakeDenpyoInput.lblKensu"),
                            IchiranTbl.length
                        )
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").val(
                        me.toMoney(
                            $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei"),
                            lngKingaku
                        )
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei").val(
                        me.toMoney(
                            $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei"),
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
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                            ).button("disable");
                        } else {
                            //修正前データが存在する場合
                            //修正前表示ボタンを活性にする
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                            ).button("enable");
                        }
                    }

                    //ボタンを使用可にする
                    me.DpyInpNewButtonEnabled(me.hidMode);

                    //明細が10行以上ある場合は、追加ボタンを不活性にする
                    if (IchiranTbl.length >= 10) {
                        $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button(
                            "disable"
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").hide();
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
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").get(0)
                    .selectedIndex == 0
            ) {
                return;
            }
            var url =
                me.sys_id +
                "/" +
                me.id +
                "/" +
                "ddlPatternSel_SelectedIndexChanged";
            var data = {
                ddlPatternSel: $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel")
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
                //口座キー、必須摘要を不活性にする
                me.KouzaHiTekkiEnabledSet(false);
                if (result["data"]["PATTERNTBL"].length == 0) {
                    return;
                }
                //選択された仕訳データを画面項目にセットする
                me.DataFormSet("101", result["data"]["PATTERNTBL"]);
                //口座キー・必須摘要に入力されている値があれば活性にする
                me.KouzaHittekiEnabledCheck();
                //口座キー・必須摘要の名称を取得する(借方)
                me.LKoubanNMSet(result["data"]["LKOUBANTBL"], false);
                //口座キー・必須摘要の名称を取得する(貸方)
                me.RKoubanNMSet(result["data"]["RKOUBANTBL"], false);
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    //20240417 lqs INS S
    // '**********************************************************************
    // '処 理 名：相手先区分変更
    // '関 数 名：ddlAitesakiKBN_SelectedIndexChanged
    // '処理説明：フォーカス移動時にお客様名／取引先名取得を取得する
    // '**********************************************************************
    // 20241226 YIN UPS S
    // me.ddlAitesakiKBN_SelectedIndexChanged = function (flg = true) {
    me.ddlAitesakiKBN_SelectedIndexChanged = function (flg) {
        flg = typeof flg === "undefined" ? true : flg;
        // 20241226 YIN UPS E
        try {
            me.txtOkyakusamaNOTorihikisakiNm_TextChanged(flg);
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .toString() == "3"
            ) {
                $(
                    ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                ).attr("disabled", "disabled");
            } else {
                $(
                    ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                ).attr("disabled", false);
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    // '**********************************************************************
    // '処 理 名：お客様名／取引先名取得
    // '関 数 名：txtOkyakusamaNOTorihikisakiNm_TextChanged
    // '処理説明：フォーカス移動時にお客様名／取引先名取得を取得する
    // '**********************************************************************
    me.txtOkyakusamaNOTorihikisakiNm_TextChanged = function (flg) {
        try {
            flg = typeof flg === "undefined" ? true : flg;
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm")
                    .val()
                    .trim() == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm").val(
                    ""
                );
                $(
                    ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                ).val("");
                return;
            }
            var url =
                me.sys_id +
                "/" +
                me.id +
                "/" +
                "txtOkyakusamaNOTorihikisakiNmSet";
            var data = {
                ddlAitesakiKBN: $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .replace(me.blankReplace, "")
                    .toString(),
                txtOkyakusamaNOTorihikisakiNm: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                )
                    .val()
                    .trim(),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var strRet = result["data"]["NM"];
                if (strRet !== "") {
                    $(
                        ".HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm"
                    ).val(strRet);
                    if (flg) {
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                        ).val(strRet);
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensakuCD").val(
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                        )
                            .val()
                            .trim()
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensakuNM").val(strRet);
                } else {
                    $(
                        ".HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm"
                    ).val("");
                    if (flg) {
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                        ).val("");
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensakuCD").val("");
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensakuNM").val("");
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };

    // '**********************************************************************
    //  '処 理 名：特例区分選択時
    //  '関 数 名：ddlTokureiKBN_SelectedIndexChanged
    //  '処理説明：特例区分が変更されたら、登録番変更
    //  '**********************************************************************
    me.ddlTokureiKBN_SelectedIndexChanged = function () {
        if (
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").val().toString() ==
            "0"
        ) {
            $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo").val(
                "T0000000000000"
            );
        } else {
            $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo").val("");
        }
    };
    //20240417 lqs INS E
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
            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val())
            );

            //前処理
            // me.txtLkamokuCDMeisyouSet('txtLKamokuCD', false);
            // me.txtLkamokuCDMeisyouSet('txtRKamokuCD', false);

            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.txtZeikm_GK_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val(
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .toString()
                    .toZenkaku()
            );
            //入力チェックを行う
            if (me.fncInputCheck() == true) {
                var url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
                var data = {
                    lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    if (
                        result["data"]["CheckTbl"] &&
                        result["data"]["CheckTbl"].length > 10
                    ) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "10行を超える仕訳を登録することは出来ません！"
                        );
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
                    $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
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
                    $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, "")
                ) {
                    strKashiTenpo = data["strKashiTenpo"][index]["BUSYO_NM"];
                    break;
                }
            }
            //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
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
            //前処理
            // me.txtLkamokuCDMeisyouSet('txtLKamokuCD', false);
            // me.txtLkamokuCDMeisyouSet('txtRKamokuCD', false);
            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val(
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .toString()
                    .toZenkaku()
            );

            //入力チェックを行う
            if (me.fncInputCheck() == true) {
                //** 名称取得
                var url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
                var data = {
                    txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
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
                    CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
                    CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
                    lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                };
            } else {
                var data = {
                    strSEQNO: strSEQNO,
                    CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
                    CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
                    lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtZeikm_GK: $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    lblZeink_GK: $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK")
                        .text()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    lblSyohizei: $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei")
                        .text()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, ""),
                    txtTekyo: $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKamokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    ddlLSyohizeiKbn: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                    ).val(),
                    ddlLTorihikiKbn: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn"
                    ).val(),
                    txtLKouzaKey1: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKouzaKey2: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKouzaKey3: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKouzaKey4: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKouzaKey5: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo1: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo2: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo3: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo4: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo5: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo6: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo7: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo8: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo9: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLHissuTekyo10: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKamokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRbusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                        .val()
                        .replace(me.blankReplace, ""),
                    ddlRSyohizeiKbn: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                    ).val(),
                    ddlRTorihikiKbn: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn"
                    ).val(),
                    txtRKouzaKey1: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKouzaKey2: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKouzaKey3: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKouzaKey4: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRKouzaKey5: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo1: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo2: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo3: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo4: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo5: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo6: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo7: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo8: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo9: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtRHissuTekyo10: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10"
                    )
                        .val()
                        .replace(me.blankReplace, ""),
                    txtKeiriSyoriDT: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT"
                    )
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/\//g, ""),
                    //20240417 lqs INS S
                    ddlAitesakiKBN: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN"
                    ).val(),
                    txtOkyakusamaNOTorihikisakiNm: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                    )
                        .val()
                        .trimEnd(),
                    txtTorokuNoKazeiMenzeiGyosya: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                    )
                        .val()
                        .trimEnd(),
                    txtJigyosyoMeiTorokuNo: $(
                        ".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo"
                    )
                        .val()
                        .trimEnd(),
                    ddlTokureiKBN: $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN"
                    ).val(),
                    //20240417 lqs INS E
                };
            }
            //新規の証憑登録の場合
            var fncFukanzenCheck = me.fncFukanzenCheck();
            data.fncFukanzenCheck = fncFukanzenCheck;
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, "") == "" ||
                me.hidUpdDate == ""
            ) {
                //証憑№の取得を行う
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "") != ""
                ) {
                    strSEQNO = $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "");
                }
                //登録処理を行う
                data.strSEQNO = strSEQNO;
                data.flag = "1";
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                    //証憑№を表示する
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val(
                        result["data"]["strSEQNO"]
                    );
                    //更新日付を隠し項目にセット
                    me.hidUpdDate = result["data"]["dtSysdate"];

                    jqgridDataShow();
                };
                me.ajax.send(url, data, 0);
            }
            //追加の証憑登録の場合
            else {
                data.flag = "2";
                var url2 =
                    me.sys_id + "/" + me.id + "/" + "fncCheckJikkoSeigyo";
                var data2 = {
                    lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
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
                                me.clsComFnc.FncMsgBox(
                                    "E9999",
                                    result["error"]
                                );
                                return false;
                            }
                            //証憑№を表示する
                            $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val(
                                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
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
                                        ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                                    ).button("disable");
                                } else {
                                    //修正前データが存在する場合
                                    //修正前表示ボタンを活性にする
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp"
                                    ).button("enable");
                                }
                            }
                            jqgridDataShow();
                            afterDel(sender);
                        };
                        me.ajax.send(url, data, 0);
                    } else {
                        me.ajax.receive = function (result) {
                            result = eval("(" + result + ")");
                            if (!result["result"]) {
                                me.clsComFnc.FncMsgBox(
                                    "E9999",
                                    result["error"]
                                );
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
                            jqgridDataShow();
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
    function jqgridDataShow() {
        //後処理
        //一覧に表示する
        //合計件数、合計金額、合計消費税額を計算する
        var lngKingaku = 0,
            lngSyohizei = 0;
        $(me.grid_id).jqGrid("clearGridData");

        var data = {
            lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
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
            $(".HMDPS101ShiwakeDenpyoInput.lblKensu").val(
                me.toMoney(
                    $(".HMDPS101ShiwakeDenpyoInput.lblKensu"),
                    objDs.length
                )
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").val(
                me.toMoney(
                    $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei"),
                    lngKingaku
                )
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei").val(
                me.toMoney(
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei"),
                    lngSyohizei
                )
            );
            //10行の場合は追加ボタンを不活性にする
            var rowNum = $(me.grid_id).jqGrid("getGridParam", "records");
            if (rowNum >= 10) {
                $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");
            } else if (rowNum == 0) {
                $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button("disable");
                $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button("disable");
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button("enable");
                $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button("enable");
            }
            $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").trigger("focus");
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
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                        "disabled",
                        false
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                        "disabled",
                        false
                    );

                    $(me.grid_id).jqGrid("clearGridData");
                    //ボタンを使用不可にする
                    me.DpyInpNewButtonEnabled("99");
                    //口座キー、必須摘要を不活性にする
                    me.KouzaHiTekkiEnabledSet(false);

                    $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").hide();
                    //伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    //パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    //経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").datepicker(
                        "disable"
                    );
                    //ボタンの活性・不活性を決める(新規の場合)
                    me.DpyInpNewButtonEnabled("1");
                    switch (me.PatternID) {
                        case me.HMDPS.CONST_ADMIN_PTN_NO:
                        case me.HMDPS.CONST_HONBU_PTN_NO:
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                            ).show();
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                            ).button("enable");
                            break;
                        default:
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnPatternTrk"
                            ).hide();
                            break;
                    }
                    //修正前表示ボタンを不活性にする
                    $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").trigger(
                        "focus"
                    );
                } else {
                    //完了メッセージを表示し、画面を閉じる
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
                    };
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
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
            if (me.hidDispNO == "105") {
                //確認メッセージを表示する
                me.clsComFnc.FncMsgBox(
                    "QY999",
                    "該当証憑№のデータを全て削除します。よろしいですか？<br/>※ＣＳＶ出力対象から外したいだけの場合は出力画面の対象欄からチェックを外して下さい。<br/>削除した場合は該当証憑№のデータは全て失われます。"
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
                    me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
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
                lblSyohy_no: $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, ""),
                CONST_ADMIN_PTN_NO: me.HMDPS.CONST_ADMIN_PTN_NO,
                CONST_HONBU_PTN_NO: me.HMDPS.CONST_HONBU_PTN_NO,
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
                        $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
                    } else {
                        // 20250411 lujunxia upd s
                        // 問題：新規データを追加、全確定を行う「width:auto」に変更されたので、幅が狭くなる
                        // me.Page_Load();
                        me.Page_Load("opening");
                        // 20250411 lujunxia upd e
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
    me.txtLKamokuCD_TextChanged = function (sender, changeFlag) {
        try {
            me.txtLkamokuCDMeisyouSet(sender, true, changeFlag);
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtRKamokuCD_TextChanged = function (sender, changeFlag) {
        try {
            me.txtLkamokuCDMeisyouSet(sender, true, changeFlag);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：科目項目名取得
	 '関 数 名：txtLkamokuCDMeisyouSet
	 '処理説明 ：フォーカス移動時に科目項目名を取得する
	 '**********************************************************************
	 */
    me.txtLkamokuCDMeisyouSet = function (sender, DefalueValue, changeFlag) {
        try {
            if (DefalueValue == undefined) {
                DefalueValue = true;
            }
            changeFlag = changeFlag == undefined ? false : changeFlag;
            //口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(
                false,
                sender.toUpperCase() == "TXTLKAMOKUCD" ? 1 : 2
            );
            //項目名をクリアする
            if (sender.toUpperCase() == "TXTLKAMOKUCD") {
                me.LKouzaHittekiClear();
                var data = {
                    KamokuCD: $(".HMDPS101ShiwakeDenpyoInput." + sender)
                        .val()
                        .replace(me.blankReplace, ""),
                    KomokuCD:
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                            .val()
                            .replace(me.blankReplace, "") == ""
                            ? "999999"
                            : $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                                  .val()
                                  .replace(me.blankReplace, ""),
                    txtKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                };
            } else {
                me.RKouzaHittekiClear();
                var data = {
                    KamokuCD: $(".HMDPS101ShiwakeDenpyoInput." + sender)
                        .val()
                        .replace(me.blankReplace, ""),
                    KomokuCD:
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                            .val()
                            .replace(me.blankReplace, "") == ""
                            ? "999999"
                            : $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                                  .val()
                                  .replace(me.blankReplace, ""),
                    txtKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                        .val()
                        .replace(me.blankReplace, ""),
                };
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput." + sender)
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                var url =
                    me.sys_id + "/" + me.id + "/" + "FncGetKamokuMstValue";
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox(result["error"]);
                        return;
                    }
                    //** 名称取得
                    if (sender.toUpperCase() == "TXTLKAMOKUCD") {
                        $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val(
                            result["data"]["strKamokuNM"]
                        );
                        //口座キー・必須摘要の名称を取得する(借方)
                        me.LKoubanNMSet(
                            result["data"]["KOUBANTBL"],
                            DefalueValue
                        );

                        me.LKouzaHittekiNmNothingClear();

                        if (!changeFlag) {
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD"
                            ).trigger("focus");
                        } else {
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnLKamokuSearch"
                            ).trigger("focus");
                        }
                    } else {
                        $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val(
                            result["data"]["strKamokuNM"]
                        );
                        //口座キー・必須摘要の名称を取得する(貸方)
                        me.RKoubanNMSet(
                            result["data"]["KOUBANTBL"],
                            DefalueValue
                        );

                        me.RKouzaHittekiNmNothingClear();

                        if (!changeFlag) {
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD"
                            ).trigger("focus");
                        } else {
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.btnRKamokuSearch"
                            ).trigger("focus");
                        }
                    }
                };
                me.ajax.send(url, data, 0);
            } else {
                if (sender.toUpperCase() == "TXTLKAMOKUCD") {
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val("");
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val("");
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：科目項目名取得
	 '関 数 名：txtLKamokuCD_LostFocus
	 '処理説明：フォーカス移動時に科目項目名を取得する
	 '**********************************************************************
	 */
    me.txtLKomokuCD_TextChanged = function (sender) {
        try {
            me.txtLKomokuCDMeisyouSet(sender, 1);
            $(".HMDPS101ShiwakeDenpyoInput.btnLKamokuSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtRKomokuCD_TextChanged = function (sender) {
        try {
            me.txtLKomokuCDMeisyouSet(sender, 2);
            $(".HMDPS101ShiwakeDenpyoInput.btnRKamokuSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtLKomokuCDMeisyouSet = function (sender, DefalueValue) {
        try {
            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val())
            );
            //口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false, DefalueValue);
            if (sender.toUpperCase() == "TXTLKOMOKUCD") {
                me.LKouzaHittekiClear();

                fncSetCommon("txtLKamokuCD", "txtLKomokuCD", "lblLKamokuNM");
            } else {
                me.RKouzaHittekiClear();

                fncSetCommon("txtRKamokuCD", "txtRKomokuCD", "lblRKamokuNM");
            }
        } catch (ex) {
            console.log(ex);
        }
    };

    function fncSetCommon(KamokuCD, KomokuCD, KamokuNM) {
        try {
            if (
                $(".HMDPS101ShiwakeDenpyoInput." + KamokuCD)
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput." + KamokuNM).text("");
            } else {
                var url =
                    me.sys_id + "/" + me.id + "/" + "FncGetKamokuMstValue";
                var data = {
                    KamokuCD: $.trim(
                        $(".HMDPS101ShiwakeDenpyoInput." + KamokuCD).val()
                    ),
                    KomokuCD:
                        $.trim(
                            $(".HMDPS101ShiwakeDenpyoInput." + KomokuCD).val()
                        ) == ""
                            ? "999999"
                            : $.trim(
                                  $(
                                      ".HMDPS101ShiwakeDenpyoInput." + KomokuCD
                                  ).val()
                              ),
                    txtKomokuCD: $.trim(
                        $(".HMDPS101ShiwakeDenpyoInput." + KomokuCD).val()
                    ),
                };
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (!result["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    } else {
                        $(".HMDPS101ShiwakeDenpyoInput." + KamokuNM).val(
                            result["data"]["strKamokuNM"]
                        );

                        if (KamokuCD == "txtLKamokuCD") {
                            me.LKoubanNMSet(result["data"]["KOUBANTBL"], true);
                            //口座キー・必須摘要の名称を取得する(借方)
                            me.LKouzaHittekiNmNothingClear();
                        } else {
                            me.RKoubanNMSet(result["data"]["KOUBANTBL"], true);
                            //口座キー・必須摘要の名称を取得する(貸方)
                            me.RKouzaHittekiNmNothingClear();
                        }
                    }
                };
                me.ajax.send(url, data, 0);
            }
        } catch (ex) {
            console.log(ex);
        }
    }

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
                $(".HMDPS101ShiwakeDenpyoInput." + sender)
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //** 名称取得
                var lblLbusyoNM = "";
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        me.BusyoMst[index]["BUSYO_CD"] ==
                        $(".HMDPS101ShiwakeDenpyoInput." + sender)
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        lblLbusyoNM = me.BusyoMst[index]["BUSYO_NM"];
                        break;
                    }
                }
                if (sender.toUpperCase() == "TXTLBUSYOCD") {
                    $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val(
                        lblLbusyoNM
                    );
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val(
                        lblLbusyoNM
                    );
                }
            } else {
                if (sender.toUpperCase() == "TXTLBUSYOCD") {
                    $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val("");
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val("");
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtLBusyoCD_TextChanged = function (sender) {
        try {
            me.txtBusyoCD_TextChanged(sender);
            $(".HMDPS101ShiwakeDenpyoInput.btnLBusyoSearch").trigger("focus");
        } catch (ex) {
            console.log(ex);
        }
    };
    me.txtRBusyoCD_TextChanged = function (sender) {
        try {
            me.txtBusyoCD_TextChanged(sender);
            $(".HMDPS101ShiwakeDenpyoInput.btnRBusyoSearch").trigger("focus");
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
            if (
                $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").is(":checked")
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").attr(
                    "disabled",
                    "disabled"
                );
            } else if (
                $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").is(":checked")
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").attr(
                    "disabled",
                    false
                );
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
            //口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false);
            //仕訳データの取得
            var rowdata = $(me.grid_id).jqGrid("getRowData", rowId);
            var url = me.sys_id + "/" + me.id + "/" + "fncSelShiwakeData";
            var data = {
                SYOHY_NO: rowdata["SYOHY_NO"],
                EDA_NO: rowdata["EDA_NO"],
                GYO_NO: rowdata["GYO_NO"],
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                //該当データが存在しない場合
                if (result["data"]["NewNoTbl"].length == 0) {
                    //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                    me.clsComFnc.FncMsgBox("W0026");
                    return;
                }
                //選択された仕訳データを画面項目にセットする
                me.DataFormSet("100", result["data"]["NewNoTbl"]);
                //口座キー・必須摘要に入力されている値があれば活性にする
                me.KouzaHittekiEnabledCheck();
                //口座キー・必須摘要の名称を取得する(借方)
                me.LKoubanNMSet(result["data"]["LKOUBANTBL"], false);
                //口座キー・必須摘要の名称を取得する(貸方)
                me.RKoubanNMSet(result["data"]["RKOUBANTBL"], false);
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
                    $(".HMDPS101ShiwakeDenpyoInput.btnSaishinDisp").css(
                        "display"
                    ) == "block"
                ) {
                    me.FormEnabled(false);
                    //参照モードのボタン設定
                    me.DpyInpNewButtonEnabled("9");
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                        "enable"
                    );
                    return;
                }
            };
            me.ajax.send(url, data, 0);
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

            //10行の場合は追加ボタンを不活性にする
            if (rowdata.length >= 10) {
                $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");
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
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("");
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("");
                return;
            }
            //,,,
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") != "" &&
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(/,/g, "") == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val("");

                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("");
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("");

                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                );
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                return;
            }
            if (
                me.isPosNumber(
                    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                        .val()
                        .replace(/,/g, "")
                ) == -1
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("");
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("");
                return;
            }
            if (
                me.isPosNumber(
                    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, "")
                ) == 0
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("0");
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("0");
                return;
            }
            if (
                $.trim(
                    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val()
                ).replace(/,/g, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").prop(
                        "selectedIndex"
                    ) == 0
                ) {
                    $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("");
                    $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("");
                } else {
                    if (
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                        ).val() == "04" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                        ).val() == "04" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                        ).val() == "05" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                        ).val() == "05" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                        ).val() == "06" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                        ).val() == "06" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                        ).val() == "07" ||
                        $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                        ).val() == "07"
                    ) {
                        var dblZeink_gk = "";
                        var dblZeiRt = "";
                        if (
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                            ).val() == "04" ||
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                            ).val() == "04"
                        ) {
                            dblZeiRt = 1.05;
                        } else if (
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                            ).val() == "05" ||
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                            ).val() == "05"
                        ) {
                            dblZeiRt = 1.08;
                        } else if (
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                            ).val() == "06" ||
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                            ).val() == "06"
                        ) {
                            dblZeiRt = 1.08;
                        } else if (
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                            ).val() == "07" ||
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                            ).val() == "07"
                        ) {
                            dblZeiRt = 1.1;
                        }
                        dblZeink_gk = Math.floor(
                            $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                                .val()
                                .replace(me.blankReplace, "")
                                .replace(/,/g, "") / dblZeiRt
                        );
                        while (1 == 1) {
                            if (
                                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                                    .val()
                                    .replace(me.blankReplace, "")
                                    .replace(/,/g, "") <=
                                Math.floor(dblZeink_gk * dblZeiRt)
                            ) {
                                break;
                            }
                            dblZeink_gk++;
                        }
                        $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text(
                            me.toMoney(
                                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK"),
                                dblZeink_gk,
                                "label"
                            )
                        );
                        if (
                            $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                                .val()
                                .replace(/,/g, "") == ""
                        ) {
                            $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text(
                                ""
                            );
                        } else {
                            var lblZeink_GK_val = 0;
                            if (
                                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK")
                                    .text()
                                    .replace(/,/g, "") != ""
                            ) {
                                lblZeink_GK_val = Number(
                                    $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK")
                                        .text()
                                        .replace(/,/g, "")
                                );
                            }
                            $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text(
                                me.toMoney(
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblSyohizei"
                                    ),
                                    Number(
                                        $(
                                            ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                                        )
                                            .val()
                                            .replace(/,/g, "")
                                    ) - lblZeink_GK_val,
                                    "label"
                                )
                            );
                        }
                    } else {
                        $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text(
                            me.toMoney(
                                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK"),
                                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                                    .val()
                                    .replace(me.blankReplace, ""),
                                "label"
                            )
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text(0);
                    }
                }
            }
            // 20240417 lqs UPD S
            // $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").trigger("focus");
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").trigger("focus");
            // 20240417 lqs UPD E
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
            $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                "disabled",
                false
            );
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").val() ==
                    "90"
                ) {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                }
            }
            if (!$(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").is(":hidden")) {
                me.txtZeikm_GK_TextChanged();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：消費税区分選択時(借方)
	 '関 数 名：ddlRSyohizeiKbn_SelectedIndexChanged
	 '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
	 '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
	 '　　　　：計算し、表示する
	 '**********************************************************************
	 */
    me.ddlLSyohizeiKbn_SelectedIndexChanged = function () {
        try {
            $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                "disabled",
                false
            );
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").val() ==
                    "90"
                ) {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                        "disabled",
                        "disabled"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                }
            }
            if (!$(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").is(":hidden")) {
                me.txtZeikm_GK_TextChanged();
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー変換
	 '関 数 名：txtLKouzaKey1_TextChanged
	 '処理説明：英数字が入力された場合は半角の大文字に変換する
	 '**********************************************************************
	 */
    me.txtLKouzaKey1_TextChanged = function (sender) {
        try {
            var patt = /^[0-9a-zA-Z０-９ａ-ｚＡ-Ｚ]*$/g;
            if (
                $(sender)
                    .val()
                    .replace(me.blankReplace, "")
                    .toString()
                    .match(patt)
            ) {
                $(sender).val(
                    $(sender).val().toString().toUpperCase().toHankaku()
                );
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
            $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").get(
                0
            ).selectedIndex = 0;
            //ドロップダウンをクリアする
            $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                0
            ).selectedIndex = 0;

            $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                "disabled",
                false
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                "disabled",
                false
            );

            //20240417 lqs INS S
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").get(
                0
            ).selectedIndex = 0;
            //20240417 lqs INS E
            //ボタンの活性・不活性を設定する
            me.DpyInpNewButtonEnabled("4");
            //口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false);
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
            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val())
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val(
                $.trim($(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val())
            );
            //前処理
            // me.txtLkamokuCDMeisyouSet('txtLKamokuCD', false);
            // me.txtLkamokuCDMeisyouSet('txtRKamokuCD', false);

            me.txtBusyoCD_TextChanged("txtLBusyoCD");
            me.txtBusyoCD_TextChanged("txtRbusyoCD");
            me.txtZeikm_GK_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val(
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "")
                    .toString()
                    .toZenkaku()
            );
            //入力チェックを行う
            if (
                $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").is(
                    ":checked"
                ) &&
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo"
                );
                me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
                return;
            } else if (
                $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").is(
                    ":checked"
                ) &&
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        me.BusyoMst[index]["BUSYO_CD"] ==
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        BusyoMstFlag = true;
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "対象部署コードが部署マスタに存在しません！"
                    );
                    return;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtPatternNM"
                );
                me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
                return;
            } else {
                if (
                    me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM")
                            .val()
                            .replace(me.blankReplace, ""),
                        40
                    ) == false
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtPatternNM"
                    );
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
            var fncFukanzenCheck = me.fncFukanzenCheck();
            var url =
                me.sys_id + "/" + me.id + "/" + "cmdEventPatternTrk_Click";
            var data = {
                hidPatternNO: me.clsComFnc.FncNv(me.hidPatternNO),
                txtZeikm_GK: $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                lblZeink_GK: $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK")
                    .text()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                lblSyohizei: $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei")
                    .text()
                    .replace(me.blankReplace, "")
                    .replace(/,/g, ""),
                txtTekyo: $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKamokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                ddlLSyohizeiKbn: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                ).val(),
                ddlLTorihikiKbn: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKouzaKey1: $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKouzaKey2: $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKouzaKey3: $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKouzaKey4: $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKouzaKey5: $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo1: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo2: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo3: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo4: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo5: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo6: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo7: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo8: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo9: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLHissuTekyo10: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKamokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKomokuCD: $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRbusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                ddlRSyohizeiKbn: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                ).val(),
                ddlRTorihikiKbn: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn"
                ).val(),
                txtRKouzaKey1: $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKouzaKey2: $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKouzaKey3: $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKouzaKey4: $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRKouzaKey5: $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5")
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo1: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo2: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo3: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo4: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo5: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo6: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo7: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo8: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo9: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtRHissuTekyo10: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLBusyoCD: $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, ""),
                fncFukanzenCheck: fncFukanzenCheck,
                txtPatternNM: $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, ""),
                radPatternKyotu: $(
                    ".HMDPS101ShiwakeDenpyoInput.radPatternKyotu"
                ).is(":checked")
                    ? "1"
                    : "2",
                txtPatternBusyo: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo"
                )
                    .val()
                    .replace(me.blankReplace, ""),
                lblSyohy_no: !$(".HMDPS101ShiwakeDenpyoInput.KeyTableRow").is(
                    ":hidden"
                ),
                //20240417 lqs INS S
                ddlAitesakiKBN: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN"
                ).val(),
                txtOkyakusamaNOTorihikisakiNm: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                )
                    .val()
                    .trimEnd(),
                txtTorokuNoKazeiMenzeiGyosya: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                )
                    .val()
                    .trimEnd(),
                txtJigyosyoMeiTorokuNo: $(
                    ".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo"
                )
                    .val()
                    .trimEnd(),
                ddlTokureiKBN: $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN"
                ).val(),
                //20240417 lqs INS E
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return false;
                }
                if (me.clsComFnc.FncNv(me.hidPatternNO) == "") {
                    if (me.hidDispNO == "103" && me.hidMode == "2") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            $(".HMDPS101ShiwakeDenpyoInput.body").dialog(
                                "close"
                            );
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HMDPS101ShiwakeDenpyoInput.body").dialog(
                                "close"
                            );
                        };
                        //登録完了のメッセージを表示し、画面を閉じる
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        //画面項目をクリアする
                        if (me.hidDispNO == "103") {
                            me.subFormClear(true);
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                            ).get(0).selectedIndex = 0;
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn"
                            ).get(0).selectedIndex = 0;
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                            ).get(0).selectedIndex = 0;
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn"
                            ).get(0).selectedIndex = 0;
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn"
                            ).attr("disabled", "disabled");
                            $(
                                ".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn"
                            ).attr("disabled", "disabled");
                        }
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM").val("");
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").val(
                            ""
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").prop(
                            "checked",
                            true
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").prop(
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
                        $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                            "disabled",
                            "disabled"
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM").val("");
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").val(
                            ""
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").prop(
                            "checked",
                            true
                        );
                        $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").prop(
                            "checked",
                            false
                        );
                        me.radPatternBusyo_CheckedChanged();
                        //登録完了のメッセージを表示し、画面を閉じる
                        me.clsComFnc.FncMsgBox("I0016");
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            $(".HMDPS101ShiwakeDenpyoInput.body").dialog(
                                "close"
                            );
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HMDPS101ShiwakeDenpyoInput.body").dialog(
                                "close"
                            );
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
                $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").is(
                    ":checked"
                ) &&
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo"
                );
                me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
                return;
            } else if (
                $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").is(
                    ":checked"
                ) &&
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //対象部署がマスタに存在しない場合
                var BusyoMstFlag = false;
                for (var index = 0; index < me.BusyoMst.length; index++) {
                    if (
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo")
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
                        ".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "対象部署コードが部署マスタに存在しません！"
                    );
                    return;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtPatternNM"
                );
                me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
                return;
            } else {
                if (
                    me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM")
                            .val()
                            .replace(me.blankReplace, ""),
                        40
                    ) == false
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtPatternNM"
                    );
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
                    $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
                };
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HMDPS101ShiwakeDenpyoInput.body").dialog("close");
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
                me.PatternID != me.HMDPS.CONST_ADMIN_PTN_NO &&
                me.PatternID != me.HMDPS.CONST_HONBU_PTN_NO
            ) {
                //hidmode="8"は削除は可能なため、省く
                if (me.hidMode != "8") {
                    //他のユーザによって印刷が行われましたので、登録を行うことは出来ません。"
                    me.clsComFnc.FncMsgBox("W0027");
                    return false;
                }
            }
            //既にＣＳＶ出力されている場合
            if (me.clsComFnc.FncNv(objCDt[0]["CSV_OUT_FLG"]) == 1) {
                //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                if (
                    me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
                ) {
                    //CSV再出力画面から表示した場合は飛ばす。
                    if (me.hidDispNO != "105") {
                        //"他のユーザによってＣＳＶ出力が行われましたので、ＣＳＶ再出力画面より開き直してください！"
                        me.clsComFnc.FncMsgBox("W0028");
                        return false;
                    }
                } else {
                    //他のユーザによってＣＳＶ出力が行われましたので登録することは出来ません。
                    me.clsComFnc.FncMsgBox("W0029");
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
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no")
                    .val()
                    .substring(15, 17)
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
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val("");
            }
            $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text("");
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val("");
            // 20240417 lqs INS S
            $(".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm").val(
                ""
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").val(
                ""
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm").val("");
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").get(
                0
            ).selectedIndex = 0;
            // 20240417 lqs INS E
            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10").text("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblKensakuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10").text("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblKensakuCD").val("");
            $(".HMDPS101ShiwakeDenpyoInput.lblKensakuNM").val("");
            if (!blnClear) {
                $(".HMDPS101ShiwakeDenpyoInput.lblKensu").val("");
                $(".HMDPS101ShiwakeDenpyoInput.lblZeikomiGoukei").val("");
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizeiGoukei").val("");
            }
            $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").prop(
                "checked",
                true
            );
            $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").prop(
                "checked",
                false
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").attr(
                "disabled",
                "disabled"
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").val("");
            $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM").val("");
            if (!blnClear) {
                $(".HMDPS101ShiwakeDenpyoInput.lblMemo").text("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー、必須摘要を不活性にする
	 '関 数 名：KouzaHiTekkiEnabledSet
	 '引 数 １：
	 '戻 り 値：なし
	 '処理説明：画面項目をクリアする
	 '**********************************************************************
	 */
    me.KouzaHiTekkiEnabledSet = function (blnEnabled, TaisyakuKb) {
        try {
            if (TaisyakuKb == undefined) {
                TaisyakuKb = 9;
            }
            if (TaisyakuKb == 1 || TaisyakuKb == 9) {
                $(".HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet").attr(
                    "disabled",
                    !blnEnabled
                );
            }
            if (TaisyakuKb == 2 || TaisyakuKb == 9) {
                $(".HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet2").attr(
                    "disabled",
                    !blnEnabled
                );
            }
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
                $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnAdd").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnDelete").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnClear").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").show();

                $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").show();
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnAdd").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnDelete").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnClear").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").hide();

                $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").hide();
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
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnDelete").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnInsert").show();
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate").show();
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnDelete").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnInsert").hide();
                $(".HMDPS101ShiwakeDenpyoInput.btnPtnUpdate").hide();
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
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                        "enable"
                    );
                    break;
                //修正画面表示時
                case "2":
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "enable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "enable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                        "enable"
                    );
                    break;
                //一覧選択時
                case "3":
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button("enable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "enable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "enable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("enable");
                    break;
                //クリア処理
                case "4":
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    var rowcount = $(me.grid_id).jqGrid(
                        "getGridParam",
                        "reccount"
                    );
                    if (rowcount < 10) {
                        $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button(
                            "enable"
                        );
                    }
                    break;
                //一部参照モードの場合
                case "8":
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "enable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("disable");
                    if (
                        me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
                    ) {
                        $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                            "enable"
                        );
                    } else {
                        $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                            "disable"
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "enable"
                    );
                    break;
                //参照モードの場合
                case "9":
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("disable");
                    //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
                    if (
                        me.PatternID == me.HMDPS.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HMDPS.CONST_HONBU_PTN_NO
                    ) {
                        $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                            "enable"
                        );
                    } else {
                        $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                            "disable"
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "enable"
                    );
                    break;
                //エラーの場合
                case "99":
                    $(".HMDPS101ShiwakeDenpyoInput.btnAdd").button("disable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnUpdate").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnAllDelete").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnSyuseiMaeDisp").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnClear").button("disable");
                    $(".HMDPS101ShiwakeDenpyoInput.btnPatternTrk").button(
                        "disable"
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.btnKakutei").button(
                        "disable"
                    );
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
            $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").datepicker(
                "disable"
            );
            if (
                blnEnabled &&
                $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").val() != ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").datepicker(
                    "enable"
                );
            }
            if (blnEnabled) {
                blnEnabled_button = "enable";
            } else {
                blnEnabled_button = "disable";
            }
            $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").attr(
                "disabled",
                !blnEnabled
            );

            $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD").attr(
                "disabled",
                !blnEnabled
            );

            $(".HMDPS101ShiwakeDenpyoInput.KamokuCD").attr(
                "disabled",
                !blnEnabled
            );

            $(".HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.KouzaHiTekkiEnabledSet2").attr(
                "disabled",
                !blnEnabled
            );

            $(".HMDPS101ShiwakeDenpyoInput.nowrap").button(blnEnabled_button);

            $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                "disabled",
                !blnEnabled
            );
            //20240417 lqs INS S
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").attr(
                "disabled",
                !blnEnabled
            );
            //20240417 lqs INS E

            $(".HMDPS101ShiwakeDenpyoInput.btnTorihikiSearch").button(
                blnEnabled_button
            );
            $(".HMDPS101ShiwakeDenpyoInput.btnSyainSearch").button(
                blnEnabled_button
            );
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
                MeisyouTbl[index]["MEISYOU"] =
                    MeisyouTbl[index]["MEISYOU"] == null
                        ? ""
                        : MeisyouTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(MeisyouTbl[index]["MEISYOU_CD"])
                    .text(MeisyouTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn");
            }
            //貸方消費税区分にセット
            for (var index = 0; index < MeisyouTbl.length; index++) {
                MeisyouTbl[index]["MEISYOU"] =
                    MeisyouTbl[index]["MEISYOU"] == null
                        ? ""
                        : MeisyouTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(MeisyouTbl[index]["MEISYOU_CD"])
                    .text(MeisyouTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn");
            }
            //借方取引区分にセット
            var TorihikiTbl = result["data"]["TorihikiTbl"];
            for (var index = 0; index < TorihikiTbl.length; index++) {
                TorihikiTbl[index]["MEISYOU"] =
                    TorihikiTbl[index]["MEISYOU"] == null
                        ? ""
                        : TorihikiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(TorihikiTbl[index]["MEISYOU_CD"])
                    .text(TorihikiTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn");
            }
            //貸方取引区分にセット
            for (var index = 0; index < TorihikiTbl.length; index++) {
                TorihikiTbl[index]["MEISYOU"] =
                    TorihikiTbl[index]["MEISYOU"] == null
                        ? ""
                        : TorihikiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(TorihikiTbl[index]["MEISYOU_CD"])
                    .text(TorihikiTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn");
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
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel");
            }
            //20240417 lqs INS S
            // '相手先区分の値を取得
            var AitesakiTbl = result["data"]["AitesakiKBN"];
            for (var index = 0; index < AitesakiTbl.length; index++) {
                AitesakiTbl[index]["MEISYOU"] =
                    AitesakiTbl[index]["MEISYOU"] == null
                        ? ""
                        : AitesakiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(AitesakiTbl[index]["MEISYOU_CD"])
                    .text(AitesakiTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN");
            }

            // '特例区分の値を取得
            var TokureiTbl = result["data"]["TokureiKBN"];
            for (var index = 0; index < TokureiTbl.length; index++) {
                TokureiTbl[index]["MEISYOU"] =
                    TokureiTbl[index]["MEISYOU"] == null
                        ? ""
                        : TokureiTbl[index]["MEISYOU"];
                $("<option></option>")
                    .val(TokureiTbl[index]["MEISYOU_CD"])
                    .text(TokureiTbl[index]["MEISYOU"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN");
            }
            //20240417 lqs INS E
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
            $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").empty();
            for (var index = 0; index < PatternTbl.length; index++) {
                if (PatternTbl[index]["PATTERN_NM"] == null) {
                    PatternTbl[index]["PATTERN_NM"] = "";
                }
                $("<option></option>")
                    .val(PatternTbl[index]["PATTERN_NO"])
                    .text(PatternTbl[index]["PATTERN_NM"])
                    .appendTo(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel");
            }
            if (PatternTbl.length > 0) {
                $(".HMDPS101ShiwakeDenpyoInput.ddlPatternSel").val(
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
                $(".HMDPS101ShiwakeDenpyoInput.lblMemo").text(
                    $(".HMDPS101ShiwakeDenpyoInput.lblMemo").text() +
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
            $(".HMDPS101ShiwakeDenpyoInput.KeyTableRow").hide();
            $(".HMDPS101ShiwakeDenpyoInput.KingakuRow").hide();
            $(
                ".HMDPS101ShiwakeDenpyoInput.HMDPS101ShiwakeDenpyoInput_sprList"
            ).hide();
            $(".HMDPS101ShiwakeDenpyoInput.GOUKEITBL").hide();
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
            if (strNo == "100") {
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohy_no").val(
                    me.clsComFnc.FncNv(data[0]["SYOHY_NO"]) +
                        me.clsComFnc.FncNv(data[0]["EDA_NO"])
                );
                //隠し項目(行№)にセットする
                me.hidGyoNO = me.clsComFnc.FncNv(data[0]["GYO_NO"]);

                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK").val(
                    me.toMoney(
                        $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"),
                        me.clsComFnc
                            .FncNv(data[0]["ZEIKM_GK"])
                            .replace(/,/g, "")
                    )
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK").text(
                    me.toMoney(
                        $(".HMDPS101ShiwakeDenpyoInput.lblZeink_GK"),
                        me.clsComFnc
                            .FncNv(data[0]["ZEINK_GK"])
                            .replace(/,/g, ""),
                        "label"
                    )
                );
                $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei").text(
                    me.toMoney(
                        $(".HMDPS101ShiwakeDenpyoInput.lblSyohizei"),
                        me.clsComFnc
                            .FncNv(data[0]["SHZEI_GK"])
                            .replace(/,/g, ""),
                        "label"
                    )
                );
                $(".HMDPS101ShiwakeDenpyoInput.txtKeiriSyoriDT").val(
                    me.clsComFnc.FncNv(data[0]["KEIRI_DT"])
                );
            }
            $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").val(
                me.clsComFnc.FncNv(data[0]["TEKYO"]).replace(/〜/g, "～")
            );
            //20240417 lqs INS S
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").val(
                me.clsComFnc.FncNv(data[0]["AITESAKI_KB"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm").val(
                me.clsComFnc
                    .FncNv(data[0]["OKYAKU_TORIHIKI_NO"])
                    .replace(/〜/g, "～")
            );
            me.ddlAitesakiKBN_SelectedIndexChanged(false);
            $(".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").val(
                me.clsComFnc.FncNv(data[0]["JIGYOSYA_NM"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo").val(
                me.clsComFnc
                    .FncNv(data[0]["INVOICE_ENTRYNO"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").val(
                me.clsComFnc.FncNv(data[0]["TOKUREI_KB"])
            );
            //20240417 lqs INS E
            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val(
                me.clsComFnc.FncNv(data[0]["L_KAMOK_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val(
                me.clsComFnc.FncNv(data[0]["L_KOUMK_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val(
                me.clsComFnc.FncNv(data[0]["L_KAMOK_NM"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD").val(
                me.clsComFnc.FncNv(data[0]["L_HASEI_KYOTN_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val(
                me.clsComFnc.FncNv(data[0]["L_BUSYO_NM"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").val(
                me.clsComFnc.FncNv(data[0]["L_KOUZA_KEY1"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").val(
                me.clsComFnc.FncNv(data[0]["L_KOUZA_KEY2"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").val(
                me.clsComFnc.FncNv(data[0]["L_KOUZA_KEY3"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").val(
                me.clsComFnc.FncNv(data[0]["L_KOUZA_KEY4"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").val(
                me.clsComFnc.FncNv(data[0]["L_KOUZA_KEY5"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO1"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO2"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO3"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO4"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO5"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO6"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO7"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO8"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO9"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").val(
                me.clsComFnc
                    .FncNv(data[0]["L_HISSU_TEKYO10"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val(
                me.clsComFnc.FncNv(data[0]["R_KAMOK_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val(
                me.clsComFnc.FncNv(data[0]["R_KOUMK_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val(
                me.clsComFnc.FncNv(data[0]["R_KAMOK_NM"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD").val(
                me.clsComFnc.FncNv(data[0]["R_HASEI_KYOTN_CD"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val(
                me.clsComFnc.FncNv(data[0]["R_BUSYO_NM"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").val(
                me.clsComFnc.FncNv(data[0]["R_KOUZA_KEY1"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").val(
                me.clsComFnc.FncNv(data[0]["R_KOUZA_KEY2"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").val(
                me.clsComFnc.FncNv(data[0]["R_KOUZA_KEY3"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").val(
                me.clsComFnc.FncNv(data[0]["R_KOUZA_KEY4"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").val(
                me.clsComFnc.FncNv(data[0]["R_KOUZA_KEY5"]).replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO1"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO2"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO3"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO4"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO5"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO6"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO7"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO8"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO9"])
                    .replace(/〜/g, "～")
            );
            $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").val(
                me.clsComFnc
                    .FncNv(data[0]["R_HISSU_TEKYO10"])
                    .replace(/〜/g, "～")
            );
            if (me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) != "") {
                if (me.clsComFnc.FncNv(data[0]["L_ZEI_RT_KB"]) != "") {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").val(
                        me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) +
                            me.clsComFnc.FncNv(data[0]["L_ZEI_RT_KB"])
                    );
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").val(
                        me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) + "0"
                    );
                }
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").val("");
            }
            if (me.clsComFnc.FncNv(data[0]["L_KAZEI_KB"]) == "9") {
                $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                    "disabled",
                    true
                );
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").attr(
                    "disabled",
                    false
                );
            }

            if (me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) != "") {
                if (me.clsComFnc.FncNv(data[0]["R_ZEI_RT_KB"]) != "") {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").val(
                        me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) +
                            me.clsComFnc.FncNv(data[0]["R_ZEI_RT_KB"])
                    );
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").val(
                        me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) + "0"
                    );
                }
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").val("");
            }
            if (me.clsComFnc.FncNv(data[0]["R_KAZEI_KB"]) == "9") {
                $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                    "disabled",
                    true
                );
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").val(
                me.clsComFnc.FncNv(data[0]["L_TORHK_KB"])
            );
            $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").val(
                me.clsComFnc.FncNv(data[0]["R_TORHK_KB"])
            );
            //パターン検索画面から遷移、初期表示時
            if (strNo == "103") {
                if (data[0]["TAISYO_BUSYO_KB"] == "1") {
                    $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").prop(
                        "checked",
                        true
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").prop(
                        "checked",
                        false
                    );
                } else {
                    $(".HMDPS101ShiwakeDenpyoInput.radPatternKyotu").prop(
                        "checked",
                        false
                    );
                    $(".HMDPS101ShiwakeDenpyoInput.radPatternBusyo").prop(
                        "checked",
                        true
                    );

                    $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").val(
                        me.clsComFnc.FncNv(data[0]["TAISYO_BUSYO_CD"])
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternNM").val(
                    me.clsComFnc.FncNv(data[0]["PATTERN_NM"])
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：LKouzaHittekiClear
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.LKouzaHittekiClear = function () {
        try {
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10").text("");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：LKouzaHittekiClear
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.RKouzaHittekiClear = function () {
        try {
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9").text("");
            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10").text("");
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー・必須摘要に入力されている値があれば活性にする
	 '関 数 名：LKouzaHittekiNmNothingClear
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.LKouzaHittekiNmNothingClear = function () {
        try {
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").val("");
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10").text() == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").val("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：RKouzaHittekiNmNothingClear
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.RKouzaHittekiNmNothingClear = function () {
        try {
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").val("");
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9").text() == "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").val("");
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10").text() == ""
            ) {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").val("");
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー・必須摘要に入力されている値があれば活性にする
	 '関 数 名：KouzaHittekiEnabledCheck
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.KouzaHittekiEnabledCheck = function () {
        try {
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").attr(
                    "disabled",
                    false
                );
            }
            if ($(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").val() != "") {
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").attr(
                    "disabled",
                    false
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー・必須摘要の名称を取得する(借方)
	 '関 数 名：LKoubanNMSet
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.LKoubanNMSet = function (objDt, ValueSet) {
        try {
            if (objDt.length > 0) {
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM").text(
                    me.clsComFnc.FncNv(objDt[0]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").val(
                            me.clsComFnc.FncNv(objDt[0]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM").text(
                    me.clsComFnc.FncNv(objDt[1]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").val(
                            me.clsComFnc.FncNv(objDt[1]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM").text(
                    me.clsComFnc.FncNv(objDt[2]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").val(
                            me.clsComFnc.FncNv(objDt[2]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM").text(
                    me.clsComFnc.FncNv(objDt[3]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").val(
                            me.clsComFnc.FncNv(objDt[3]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM").text(
                    me.clsComFnc.FncNv(objDt[4]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").val(
                            me.clsComFnc.FncNv(objDt[4]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1").text(
                    me.clsComFnc.FncNv(objDt[5]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").val(
                            me.clsComFnc.FncNv(objDt[5]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2").text(
                    me.clsComFnc.FncNv(objDt[6]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").val(
                            me.clsComFnc.FncNv(objDt[6]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3").text(
                    me.clsComFnc.FncNv(objDt[7]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").val(
                            me.clsComFnc.FncNv(objDt[7]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4").text(
                    me.clsComFnc.FncNv(objDt[8]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").val(
                            me.clsComFnc.FncNv(objDt[8]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5").text(
                    me.clsComFnc.FncNv(objDt[9]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").val(
                            me.clsComFnc.FncNv(objDt[9]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6").text(
                    me.clsComFnc.FncNv(objDt[10]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").val(
                            me.clsComFnc.FncNv(objDt[10]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7").text(
                    me.clsComFnc.FncNv(objDt[11]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").val(
                            me.clsComFnc.FncNv(objDt[11]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8").text(
                    me.clsComFnc.FncNv(objDt[12]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").val(
                            me.clsComFnc.FncNv(objDt[12]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9").text(
                    me.clsComFnc.FncNv(objDt[13]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").val(
                            me.clsComFnc.FncNv(objDt[13]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10").text(
                    me.clsComFnc.FncNv(objDt[14]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").val(
                            me.clsComFnc.FncNv(objDt[14]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10").attr(
                        "disabled",
                        false
                    );
                }
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：口座キー・必須摘要の名称を取得する(貸方)
	 '関 数 名：RKoubanNMSet
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.RKoubanNMSet = function (objDt, ValueSet) {
        try {
            if (objDt.length > 0) {
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM").text(
                    me.clsComFnc.FncNv(objDt[0]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").val(
                            me.clsComFnc.FncNv(objDt[0]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM").text(
                    me.clsComFnc.FncNv(objDt[1]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").val(
                            me.clsComFnc.FncNv(objDt[1]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM").text(
                    me.clsComFnc.FncNv(objDt[2]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").val(
                            me.clsComFnc.FncNv(objDt[2]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM").text(
                    me.clsComFnc.FncNv(objDt[3]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").val(
                            me.clsComFnc.FncNv(objDt[3]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM").text(
                    me.clsComFnc.FncNv(objDt[4]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").val(
                            me.clsComFnc.FncNv(objDt[4]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1").text(
                    me.clsComFnc.FncNv(objDt[5]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").val(
                            me.clsComFnc.FncNv(objDt[5]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2").text(
                    me.clsComFnc.FncNv(objDt[6]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").val(
                            me.clsComFnc.FncNv(objDt[6]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3").text(
                    me.clsComFnc.FncNv(objDt[7]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").val(
                            me.clsComFnc.FncNv(objDt[7]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4").text(
                    me.clsComFnc.FncNv(objDt[8]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").val(
                            me.clsComFnc.FncNv(objDt[8]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5").text(
                    me.clsComFnc.FncNv(objDt[9]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").val(
                            me.clsComFnc.FncNv(objDt[9]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6").text(
                    me.clsComFnc.FncNv(objDt[10]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").val(
                            me.clsComFnc.FncNv(objDt[10]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7").text(
                    me.clsComFnc.FncNv(objDt[11]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").val(
                            me.clsComFnc.FncNv(objDt[11]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8").text(
                    me.clsComFnc.FncNv(objDt[12]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").val(
                            me.clsComFnc.FncNv(objDt[12]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9").text(
                    me.clsComFnc.FncNv(objDt[13]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").val(
                            me.clsComFnc.FncNv(objDt[13]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9").attr(
                        "disabled",
                        false
                    );
                }
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10").text(
                    me.clsComFnc.FncNv(objDt[14]["KOBAN_NM"])
                );
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10")
                        .text()
                        .replace(me.blankReplace, "") != ""
                ) {
                    if (ValueSet) {
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").val(
                            me.clsComFnc.FncNv(objDt[14]["VALUE_DATA"])
                        );
                    }
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10").attr(
                        "disabled",
                        false
                    );
                }
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
    me.fncInputCheck = function (blnHissuChk) {
        try {
            if (blnHissuChk == undefined) {
                blnHissuChk = true;
            }
            //税込金額が未入力の場合、エラー
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                    );
                    me.clsComFnc.FncMsgBox("E9999", "税込金額が未入力です！");
                    return false;
                }
            } else {
                //税込金額に不正な値が入力されている場合、エラー
                if (
                    me.isPosNumber(
                        $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                            .val()
                            .replace(/,/g, "")
                    ) == -1
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                    );
                    me.clsComFnc.FncMsgBox("E0013", "税込金額");
                    return false;
                }
                //税込金額の桁数チェック
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/,/g, "").length > 13
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                    );
                    me.clsComFnc.FncMsgBox("E0027", "税込金額", 13);
                    return false;
                }
                //税込金額に負数が入力されている場合、エラー
                if (
                    parseInt(
                        $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                            .val()
                            .replace(me.blankReplace, "")
                            .replace(/,/g, "")
                    ) < 0
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "税込金額に負数が入力されています！"
                    );
                    return false;
                }
            }
            //借方科目コードが未入力の場合、エラー
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方科目コードが未入力です！"
                    );
                    return false;
                }
            } else {
                //借方科目コードがマスタに存在しない場合、エラー
                var KamokuMst = me.KamokuMstBlank;
                var KamokuMstFlag = false;
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                        .val()
                        .replace(me.blankReplace, "") != ""
                ) {
                    KamokuMstFlag = true;
                    KamokuMst = me.KamokuMstNotBlank;
                }
                if (
                    $.trim(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD").val()
                    ) == "43189" &&
                    ($.trim(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD").val()
                    ) == "" ||
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                            .val()
                            .replace(me.blankReplace, "") == 0)
                ) {
                    $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val("");
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方項目コードが未入力です!"
                    );
                    return false;
                } else {
                    var KamokuMstValue = false;
                    for (var index = 0; index < KamokuMst.length; index++) {
                        if (
                            $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD")
                                .val()
                                .replace(me.blankReplace, "") ==
                            KamokuMst[index]["KAMOK_CD"]
                        ) {
                            if (KamokuMstFlag) {
                                if (
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD"
                                    )
                                        .val()
                                        .replace(me.blankReplace, "") ==
                                    KamokuMst[index]["KOUMK_CD"]
                                ) {
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM"
                                    ).val(KamokuMst[index]["KAMOK_NM"]);
                                    KamokuMstValue = true;
                                    break;
                                }
                            } else {
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM"
                                ).val(KamokuMst[index]["KAMOK_NM"]);
                                KamokuMstValue = true;
                                break;
                            }
                        }
                    }
                    if (!KamokuMstValue) {
                        $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM").val("");
                        me.clsComFnc.ObjFocus = $(
                            ".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "借方科目コード・項目コードが科目マスタに存在しません!"
                        );
                        return false;
                    }
                }
            }
            //部署コードが未入力の場合
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD"
                    );
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
                        $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                            .val()
                            .replace(me.blankReplace, "")
                    ) {
                        BusyoMstFlag = true;
                        $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val(
                            me.BusyoMst[index]["BUSYO_NM"]
                        );
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM").val("");
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方発生部署が部署マスタに存在しません！"
                    );
                    return false;
                }
            }
            //借方消費税区分が選択されていない場合
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").prop(
                    "selectedIndex"
                ) == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方消費税区分が選択されていません！"
                    );
                    return false;
                }
            } else {
                //借方消費税区分で対象外以外を選択されている場合は、取引区分の選択は必須
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").val() !=
                    "90"
                ) {
                    if (
                        $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").prop(
                            "selectedIndex"
                        ) == 0
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "借方取引区分が選択されていません！"
                        );
                        return false;
                    }
                }
            }
            //貸方科目コードが未入力の場合、エラー
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方科目コードが未入力です！"
                    );
                    return false;
                }
            } else {
                //貸方科目コードがマスタに存在しない場合、エラー
                var KamokuMst = me.KamokuMstBlank;
                var KamokuMstFlag = false;
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                        .val()
                        .replace(me.blankReplace, "") != ""
                ) {
                    KamokuMstFlag = true;
                    KamokuMst = me.KamokuMstNotBlank;
                }
                if (
                    $.trim(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD").val()
                    ) == "43189" &&
                    ($.trim(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD").val()
                    ) == "" ||
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                            .val()
                            .replace(me.blankReplace, "") == 0)
                ) {
                    $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val("");
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方項目コードが未入力です!"
                    );
                    return false;
                } else {
                    var KamokuMstValue = false;
                    for (var index = 0; index < KamokuMst.length; index++) {
                        if (
                            $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                                .val()
                                .replace(me.blankReplace, "") ==
                            KamokuMst[index]["KAMOK_CD"]
                        ) {
                            if (KamokuMstFlag) {
                                if (
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD"
                                    )
                                        .val()
                                        .replace(me.blankReplace, "") ==
                                    KamokuMst[index]["KOUMK_CD"]
                                ) {
                                    $(
                                        ".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM"
                                    ).val(KamokuMst[index]["KAMOK_NM"]);
                                    KamokuMstValue = true;
                                    break;
                                }
                            } else {
                                $(
                                    ".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM"
                                ).val(KamokuMst[index]["KAMOK_NM"]);
                                KamokuMstValue = true;
                                break;
                            }
                        }
                    }
                    if (!KamokuMstValue) {
                        $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM").val("");
                        me.clsComFnc.ObjFocus = $(
                            ".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "貸方科目コード・項目コードが科目マスタに存在しません！"
                        );
                        return false;
                    }
                }
            }
            //貸方部署コードが未入力の場合
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, "") == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD"
                    );
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
                        $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                            .val()
                            .replace(me.blankReplace, "") ==
                        me.BusyoMst[index]["BUSYO_CD"]
                    ) {
                        BusyoMstFlag = true;
                        $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val(
                            me.BusyoMst[index]["BUSYO_NM"]
                        );
                        break;
                    }
                }
                if (!BusyoMstFlag) {
                    $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM").val("");
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方発生部署が部署マスタに存在しません！"
                    );
                    return false;
                }
            }
            //貸方消費税区分が選択されていない場合
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").prop(
                    "selectedIndex"
                ) == 0
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "貸方消費税区分が選択されていません！"
                    );
                    return false;
                }
            } else {
                //貸方消費税区分で対象外以外を選択されている場合は、取引区分の選択は必須
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").val() !=
                    "90"
                ) {
                    if (
                        $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").prop(
                            "selectedIndex"
                        ) == 0
                    ) {
                        me.clsComFnc.ObjFocus = $(
                            ".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn"
                        );
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "貸方取引区分が選択されていません！"
                        );
                        return false;
                    }
                }
            }
            //摘要に全角文字以外が入力されている場合、エラー
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                //摘要に全角文字以外が入力されている場合、エラー
                if (
                    me.clsComFnc.GetByteCount(
                        $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                            .val()
                            .replace(me.blankReplace, "")
                    ) !=
                    $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, "").length *
                        2
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtTekyo"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "摘要には全角以外の文字を入力することは出来ません！"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount(
                        $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                            .val()
                            .replace(me.blankReplace, "")
                    ) > 240
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtTekyo"
                    );
                    me.clsComFnc.FncMsgBox("E0027", "摘要", "240");
                    return false;
                }
            }
            //口座キー、必須摘要チェック
            if (me.fncInputCheckforHitteki() == false) {
                return false;
            }
            // 20240417 lqs INS S
            if (me.fncInputInvoicesCheck() == false) {
                return false;
            }
            // 20240417 lqs INS E
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：fncInputCheckforHitteki
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fncInputCheckforHitteki = function () {
        try {
            //口座キー1の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー2の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー3の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー4の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー5の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要1の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要2の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要3の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要4の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要5の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要6の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要7の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要8の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要9の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要10の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //*****貸方*****
            //口座キー1の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー2の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー3の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー4の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //口座キー5の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要1の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要2の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要3の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要4の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要5の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要6の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要7の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要8の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要9の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            //必須摘要10の桁数チェック
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    !me.FncCheckByteLength(
                        $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10")
                            .val()
                            .replace(me.blankReplace, ""),
                        20
                    )
                ) {
                    me.clsComFnc.ObjFocus = $(
                        ".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10"
                    );
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" +
                            $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10")
                                .text()
                                .replace(me.blankReplace, ""),
                        "20"
                    );
                    return false;
                }
            }
            return true;
        } catch (ex) {
            console.log(ex);
        }
    };
    //20240417 lqs INS S
    me.fncInputInvoicesCheck = function () {
        if (
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").val().toString() ==
                "" ||
            $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN").val().toString() ==
                ""
        ) {
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .toString() == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN"
                );
            } else {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN"
                );
            }

            me.clsComFnc.FncMsgBox(
                "E9999",
                "相手先区分、特例区分が選択されていません。"
            );
            return false;
        }
        if (
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "1"
        ) {
            // '相手先区分＝1：顧客で、選択した顧客のマスターにインボイス登録番号が登録されている
            // 場合、特例区分＝ 1(免税経措あり) を入力している場合にはエラーとなります。
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN")
                    .val()
                    .toString() == "1"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlTokureiKBN"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "相手先区分＝1：顧客で、選択した顧客のマスターにインボイス登録番号が登録されている場合、特例区分＝ 1(免税経措あり) を入力している場合にはエラーとなります。"
                );
                return false;
            }
        } else if (
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "2"
        ) {
            // '相手先区分＝2：取引先 ＆ その取引先マスターにインボイス登録番号が入力されて
            // 'いる時に、消費税取引区分＝2：売上としていた場合、エラーとなります。
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtJigyosyoMeiTorokuNo")
                    .val()
                    .trimEnd() != "" &&
                $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn")
                    .val()
                    .toString() == "2"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "相手先区分＝2：取引先 ＆ その取引先マスターにインボイス登録番号が入力されている時に、消費税取引区分＝2：売上としていた場合、エラーとなります。"
                );
                return false;
            }
        } else if (
            $(".HMDPS101ShiwakeDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "3"
        ) {
            // 事業者名は必須
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya")
                    .val()
                    .trim() == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                );
                me.clsComFnc.FncMsgBox("E0012", "事業者名");
                return false;
            }
        }
        return true;
    };
    //20240417 lqs INS E
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
                $(".HMDPS101ShiwakeDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlLSyohizeiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlLTorihikiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlRSyohizeiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.ddlRTorihikiKbn").prop(
                    "selectedIndex"
                ) > 0
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
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
	 '処 理 名：見つかったコントロールのTextプロパティに代入
	 '関 数 名：fncFukanzenCheck
	 '処理説明：見つかったコントロールのTextプロパティに代入
	 '**********************************************************************
	 */
    me.fncFukanzenCheck = function () {
        try {
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey1NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey1")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey2NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey2")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey3NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey3")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey4NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey4")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLKouzaKey5NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLKouzaKey5")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo1")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo1")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo2")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo2")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo3")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo3")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo4")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo4")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo5")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo5")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo6")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo6")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo7")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo7")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo8")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo8")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo9")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo9")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblLHissuTekyo10")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtLHissuTekyo10")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey1NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey1")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey2NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey2")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey3NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey3")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey4NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey4")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRKouzaKey5NM")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRKouzaKey5")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo2")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo2")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo1")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo1")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo3")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo3")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo4")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo4")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo5")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo5")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo6")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo6")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo7")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo7")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo8")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo8")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo9")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo9")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            if (
                $(".HMDPS101ShiwakeDenpyoInput.lblRHissuTekyo10")
                    .text()
                    .replace(me.blankReplace, "") != ""
            ) {
                if (
                    $(".HMDPS101ShiwakeDenpyoInput.txtRHissuTekyo10")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    return 1;
                }
            }
            return 0;
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
            if (!me.HMDPS.KinsokuMojiCheck(sender, me.clsComFnc)) {
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
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").val("");
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").attr(
                    "disabled",
                    "disabled"
                );
            } else {
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").attr(
                    "disabled",
                    false
                );
                $(".HMDPS101ShiwakeDenpyoInput.txtPatternBusyo").trigger(
                    "focus"
                );
            }
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
        var $txtSearchCD = $(".HMDPS101ShiwakeDenpyoInput.lblKensakuCD");
        var $txtSearchNM = $(".HMDPS101ShiwakeDenpyoInput.lblKensakuNM");
        //20240417 lqs INS S
        var $txtOkyakusamaNOTorihikisakiNm = undefined;
        var $lblOkyakuNOTorihikisakiNm = undefined;
        var $txtTorokuNoKazeiMenzeiGyosya = undefined;
        //20240417 lqs INS E

        switch (searchButton) {
            case "btnLKamokuSearch":
            case "btnRKamokuSearch":
                //科目検索
                dialogId = "HMDPS701KamokuSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HMDPS101ShiwakeDenpyoInput.txtRKamokuCD")
                        : $(".HMDPS101ShiwakeDenpyoInput.txtLKamokuCD");
                $txtSearchkuCD =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HMDPS101ShiwakeDenpyoInput.txtRKomokuCD")
                        : $(".HMDPS101ShiwakeDenpyoInput.txtLKomokuCD");
                $txtSearchNM =
                    searchButton == "btnRKamokuSearch"
                        ? $(".HMDPS101ShiwakeDenpyoInput.lblRKamokuNM")
                        : $(".HMDPS101ShiwakeDenpyoInput.lblLKamokuNM");
                divCD = "KamokuCD";
                divkuCD = "KoumkuCD";
                divNM = "KamokuNM";
                frmId = "HMDPS701KamokuSearch";
                title = "科目マスタ検索";
                break;
            case "btnLBusyoSearch":
            case "btnRBusyoSearch":
                //部署検索
                dialogId = "HMDPS702BusyoSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HMDPS101ShiwakeDenpyoInput.txtRbusyoCD")
                        : $(".HMDPS101ShiwakeDenpyoInput.txtLBusyoCD");
                $txtSearchNM =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HMDPS101ShiwakeDenpyoInput.lblRbusyoNM")
                        : $(".HMDPS101ShiwakeDenpyoInput.lblLbusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HMDPS702BusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnTorihikiSearch":
                //取引先
                //20240417 lqs INS S
                $txtOkyakusamaNOTorihikisakiNm = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                );
                $lblOkyakuNOTorihikisakiNm = $(
                    ".HMDPS101ShiwakeDenpyoInput.lblOkyakuNOTorihikisakiNm"
                );
                $txtTorokuNoKazeiMenzeiGyosya = $(
                    ".HMDPS101ShiwakeDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                );
                //20240417 lqs INS E
                dialogId = "HMDPS700TorihikisakiSearchDialogDiv";
                divCD = "KensakuCD";
                divNM = "KensakuNM";
                frmId = "HMDPS700TorihikisakiSearch";
                title = "取引先マスタ検索";
                break;
            case "btnSyainSearch":
                //社員
                dialogId = "HMDPS703SyainSearchDialogDiv";
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HMDPS703SyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HMDPS101ShiwakeDenpyoInput.HMDPS-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        if (
            searchButton == "btnLKamokuSearch" ||
            searchButton == "btnRKamokuSearch"
        ) {
            $("<div></div>").attr("id", divkuCD).insertAfter($rootDiv).hide();
        }
        $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        if (searchButton == "btnSyainSearch") {
            $("<div></div>").attr("id", "syain").insertAfter($rootDiv).hide();
            var $syainSearch = $rootDiv.parent().find("#" + "syain");
            $syainSearch.val("syain");
        }
        var $RtnCD = $rootDiv.parent().find("#" + cd);
        var $SearchCD = $rootDiv.parent().find("#" + divCD);
        var $SearchNM = $rootDiv.parent().find("#" + divNM);
        var $SearchkuCD = undefined;
        if (
            searchButton == "btnLKamokuSearch" ||
            searchButton == "btnRKamokuSearch"
        ) {
            $SearchkuCD = $rootDiv.parent().find("#" + divkuCD);
        }
        $SearchCD.val($.trim($txtSearchCD.val()));
        $(".HMDPS101ShiwakeDenpyoInput.txtTekyo").trigger("focus");
        var width = me.ratio === 1.5 ? 488 : 500;
        var height = me.ratio === 1.5 ? 558 : 630;
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: width,
            resizable: false,
            close: function () {
                //20211208 WANGYING INS S
                //change
                var changeFlag = true;
                if (
                    (searchButton == "btnLKamokuSearch" ||
                        searchButton == "btnRKamokuSearch") &&
                    $SearchkuCD.html() != "" &&
                    $SearchkuCD.html() == $txtSearchkuCD.val()
                ) {
                    changeFlag = false;
                }
                //20211208 WANGYING INS E

                if ($RtnCD.html() == 1) {
                    $txtSearchCD.val($SearchCD.html());
                    $txtSearchNM.val($SearchNM.html());
                    //20240417 lqs INS S
                    if (searchButton == "btnTorihikiSearch") {
                        $txtOkyakusamaNOTorihikisakiNm.val($SearchCD.html());
                        $lblOkyakuNOTorihikisakiNm.val($SearchNM.html());
                        $txtTorokuNoKazeiMenzeiGyosya.val($SearchNM.html());
                    }
                    //20240417 lqs INS E
                    if (
                        searchButton == "btnLKamokuSearch" ||
                        searchButton == "btnRKamokuSearch"
                    ) {
                        $txtSearchkuCD.val($SearchkuCD.html());
                    }
                }
                if (searchButton == "btnLKamokuSearch") {
                    me.txtLKamokuCD_TextChanged("txtLKamokuCD", changeFlag);
                } else if (searchButton == "btnRKamokuSearch") {
                    me.txtRKamokuCD_TextChanged("txtRKamokuCD", changeFlag);
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
                    $(".HMDPS101ShiwakeDenpyoInput." + searchButton).trigger(
                        "focus"
                    );
                }
                if (searchButton == "btnSyainSearch") {
                    $syainSearch.remove();
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
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMDPS_HMDPS101ShiwakeDenpyoInput =
        new HMDPS.HMDPS101ShiwakeDenpyoInput();
    o_HMDPS_HMDPS.o_HMDPS_HMDPS101ShiwakeDenpyoInput =
        o_HMDPS_HMDPS101ShiwakeDenpyoInput;

    if (o_HMDPS_HMDPS.HMDPS100DenpyoSearch) {
        o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS101ShiwakeDenpyoInput =
            o_HMDPS_HMDPS101ShiwakeDenpyoInput;
        o_HMDPS_HMDPS101ShiwakeDenpyoInput.HMDPS100DenpyoSearch =
            o_HMDPS_HMDPS.HMDPS100DenpyoSearch;
    }
    if (o_HMDPS_HMDPS.HMDPS103PatternSearch) {
        o_HMDPS_HMDPS.HMDPS103PatternSearch.HMDPS101ShiwakeDenpyoInput =
            o_HMDPS_HMDPS101ShiwakeDenpyoInput;
        o_HMDPS_HMDPS101ShiwakeDenpyoInput.HMDPS103PatternSearch =
            o_HMDPS_HMDPS.HMDPS103PatternSearch;
    }
    if (o_HMDPS_HMDPS.HMDPS105CSVReOut) {
        o_HMDPS_HMDPS.HMDPS105CSVReOut.HMDPS101ShiwakeDenpyoInput =
            o_HMDPS_HMDPS101ShiwakeDenpyoInput;
        o_HMDPS_HMDPS101ShiwakeDenpyoInput.HMDPS105CSVReOut =
            o_HMDPS_HMDPS.HMDPS105CSVReOut;
    }
    o_HMDPS_HMDPS101ShiwakeDenpyoInput.load();
});
