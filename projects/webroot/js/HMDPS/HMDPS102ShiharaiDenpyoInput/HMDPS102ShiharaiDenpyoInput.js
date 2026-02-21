/**
 * 説明：
 *
 *
 * @author GSDL
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付				   Feature/Bug				 内容						 			担当
 * YYYYMMDD				  #ID					 XXXXXX						 			GSDL
 * 20240418				svn-ver.38694			VBソース変更							lqs
 * 20240426			[確定登録ボタンを押下したら口座NO、必須摘要がクリアされてしまいます]修正		lqs
 * 20240426			すべての項目・ボタン群が一度に表示されるように微調整						lqs
 * 20250124           パターン選択から行追加するとフリーズする現象が出ました                  yin
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMDPS.HMDPS102ShiharaiDenpyoInput");
HMDPS.HMDPS102ShiharaiDenpyoInput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "伝票集計システム";
    me.hmdps = new HMDPS.HMDPS();
    me.id = "HMDPS102ShiharaiDenpyoInput";
    me.dialog_id = "";
    me.sys_id = "HMDPS";
    me.hidDispNO = "";
    me.hidMode = "";
    me.hidToDay = "";
    me.hidCreateDate = "";
    me.hidUpdDate = "";
    me.hidShiharaiDate = "";
    me.hidGyoNO = "";
    me.PatternID = gdmz.SessionPatternID;
    // 取引先マスタ構造体
    me.TorihikiMst = {
        strTorihikiNM: null, // 取引先名
    };
    me.ratio = window.devicePixelRatio || 1;

    me.allBusyo = [];
    me.RKamoku = [];
    me.RKomoku = [];
    me.KamokuMstBlank = [];
    me.KamokuMstNotBlank = [];
    me.Meisyou = [];
    me.Torihiki = [];
    me.allTorihikisaki = [];
    me.PATTERN_Data = [];

    //u00A0:不间断空格，结尾处不会换行显示
    //u0020:半角空格
    //u3000:全角空格
    // 20240124 YIN UPD S
    // me.blankReplace = /((\s|\u00A0|\u0020|\u3000)+$)/;
    me.blankReplace = /[\s\u00A0\u0020\u3000]+$/;
    // 20240124 YIN UPD E

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMDPS102ShiharaiDenpyoInput.HMDPS102ShiharaiDenpyoInputButton",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMDPS102ShiharaiDenpyoInput.Datepicker",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmdps.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmdps.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmdps.EnterKeyDown(me.id);

    String.prototype.trimEnd = function (trimStr) {
        trimStr = arguments[0] != undefined ? arguments[0] : " ";
        if (!trimStr) {
            return this;
        }
        var temp = this;
        while (true) {
            if (
                temp.substring(temp.length - trimStr.length, temp.length) !=
                trimStr
            ) {
                if (trimStr == " " || trimStr == "　") {
                    if (
                        temp.substring(
                            temp.length - trimStr.length,
                            temp.length
                        ) != " " &&
                        temp.substring(
                            temp.length - trimStr.length,
                            temp.length
                        ) != "　"
                    ) {
                        break;
                    }
                } else {
                    break;
                }
            }
            temp = temp.substring(0, temp.length - trimStr.length);
        }
        return String(temp);
    };

    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").on("keydown", function (e) {
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
    $(".HMDPS102ShiharaiDenpyoInput.btnSyainSearch").click(function () {
        me.openSearchDialog("btnSyainSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnLKamokuSearch").click(function () {
        me.openSearchDialog("btnLKamokuSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnLBusyoSearch").click(function () {
        me.openSearchDialog("btnLBusyoSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnRBusyoSearch").click(function () {
        me.openSearchDialog("btnRBusyoSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnTorihikiSearch").click(function () {
        me.openSearchDialog("btnTorihikiSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnShiharaisakiSearch").click(function () {
        me.openSearchDialog("btnShiharaisakiSearch");
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnCopySyohy").click(function () {
        me.btnCopySyohy_Click();
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").click(function () {
        me.btnSaishinDisp_Click();
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").click(function () {
        me.btnSyuseiMaeDisp_Click();
    });
    $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").change(function () {
        me.radPatternBusyo_CheckedChanged();
    });
    $(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").change(function () {
        me.radPatternBusyo_CheckedChanged();
    });
    // 印刷ボタン
    $(".HMDPS102ShiharaiDenpyoInput.btnPrint").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdPrint_Click();
        };
        me.clsComFnc.FncMsgBox("QY999", "印刷します。よろしいですか？");
    });
    // 削除
    $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").click(function () {
        me.btnAllDelete_Click();
    });
    $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").click(function () {
        me.btnPtnDelete_Click();
    });
    // 確定登録
    $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").click(function () {
        me.btnAdd_Click();
    });
    // クリア
    $(".HMDPS102ShiharaiDenpyoInput.btnClear").click(function () {
        me.btnClear_Click();
    });
    //登録ﾎﾞﾀﾝクリック
    $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").click(function () {
        me.btnPtnInsert_Click("btnPtnInsert");
    });
    //更新ﾎﾞﾀﾝクリック
    $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").click(function () {
        me.btnPtnInsert_Click("btnPtnUpdate");
    });
    // 表示されている仕訳をパターンとして登録
    $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").click(function () {
        me.btnPatternTrk_Click();
    });
    // 閉じる
    $(".HMDPS102ShiharaiDenpyoInput.btnClose").click(function () {
        me.close1();
    });
    // 発生部署 左
    $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtBusyoCD_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val(),
            "L"
        );
    });
    // 発生部署 右
    $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtBusyoCD_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").val(),
            "R"
        );
    });
    //税込金額
    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").change(function () {
        me.hidShiharaiDate = "";
        me.toMoney($(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK"));
        me.txtZeikm_GK_TextChanged();
    });
    // 支払先 codeZ
    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtShiharaisakiCD_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
        );
    });
    // 摘要
    $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").change(function () {
        me.txtTekyo_TextChanged();
    });
    // 支払先
    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").change(function () {
        me.txtShiharaisaki_TextChanged();
    });
    // パターン選択
    $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").change(function () {
        me.hidShiharaiDate = "";
        me.ddlPatternSel_SelectedIndexChanged();
    });
    // 借方科目コード
    $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtLKamokuCD_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
        );
    });
    // 借方項目コード
    $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtLKomokuCD_TextChanged();
    });
    // 貸方科目コード
    $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.ddlRKamokuCD_SelectedIndexChanged();
    });
    // 貸方項目コード
    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").change(function () {
        me.hidShiharaiDate = "";
    });
    //口座キー変換
    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10").on("blur", function () {
        me.txtLKouzaKey_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10")
        );
    });
    // 	時期
    $(
        ".HMDPS102ShiharaiDenpyoInput.grpJiki input[type=radio][name=grpJiki]"
    ).change(function () {
        me.hidShiharaiDate = "";
        me.radJikiHiduke_CheckedChanged();
    });
    // 	振込先銀行
    $(
        ".HMDPS102ShiharaiDenpyoInput.grpGinko input[type=radio][name=grpGinko]"
    ).change(function () {
        me.hidShiharaiDate = "";
        me.radHiroGinko_CheckedChanged();
    });
    // 振込先銀行 その他
    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").change(function () {
        me.txtSonotaGinko_LostFocus();
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").on("blur", function () {
        me.txtDateFrom_TextChanged(
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
        );
    });
    $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").on(
        "blur",
        function () {
            me.txtDateFrom_TextChanged(
                $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
            );
        }
    );
    //消費税区分[借方]change
    $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").change(function () {
        me.hidShiharaiDate = "";
        me.ddlLSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税区分[貸方]change
    $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").change(function () {
        me.hidShiharaiDate = "";
        me.ddlRSyohizeiKbn_SelectedIndexChanged();
    });
    $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").change(function () {
        me.hidShiharaiDate = "";
    });
    $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").change(function () {
        me.hidShiharaiDate = "";
    });
    // 20240418 lqs INS S
    //相手先区分変更
    $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").change(function () {
        me.ddlAitesakiKBN_SelectedIndexChanged();
    });
    //お客様名／取引先名取得
    $(".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm").change(
        function () {
            me.txtOkyakusamaNOTorihikisakiNm_TextChanged();
        }
    );
    //特例区分変更
    $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").change(function () {
        me.ddlTokureiKBN_SelectedIndexChanged();
    });
    // 20240418 lqs INS E
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        // 20240418 lqs INS S
        var cnt = $(".HMDPS.HMDPS-layout-center").children().length;
        if (
            $(".HMDPS.HMDPS-layout-center")
                .children()
                [cnt - 1].className.indexOf("HMDPS102ShiharaiDenpyoInput") > -1
        ) {
            // 20240426 lqs UPD S
            // $(".HMDPS.HMDPS-layout-center").css("overflow-y", "scroll");
            $(
                ".HMDPS.HMDPS-layout-center .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
            ).css("transform-origin", "top left");
            $(
                ".HMDPS.HMDPS-layout-center .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
            ).css(
                "transform",
                me.ratio === 1.5 ? "scale(0.83)" : "scale(0.84)"
            );
            $(
                ".HMDPS.HMDPS-layout-center .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
            ).css("margin-top", "-3px");
            // 20240426 lqs UPD E
        }
        // 20240418 lqs INS E

        me.HMDPS102ShiharaiDenpyoInput_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：HMDPS102ShiharaiDenpyoInput_Load
	 '引	数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.HMDPS102ShiharaiDenpyoInput_Load = function () {
        // 画面初期化
        var strMode = "";
        var strDispNO = "";
        var strAllSyohy_No = "";
        var strPattern_NO = "";
        var strSyohy_NO = "";
        var strEda_No = "";
        strDispNO = $("#DISP_NO").html();
        strDispNO = strDispNO == undefined ? "" : strDispNO;
        me.hidDispNO = strDispNO;
        // 前画面情報を取得
        strMode = $("#MODE").html();
        strAllSyohy_No = $("#SYOHY_NO").html();
        strPattern_NO = $("#PATTERN_NO").html();
        if (me.clsComFnc.FncNv(strAllSyohy_No) != "") {
            strSyohy_NO = strAllSyohy_No.substring(0, 15);
            strEda_No = strAllSyohy_No.substring(15, 17);
        }

        // 'モードの設定
        if (strDispNO == "") {
            // メニューから開かれた場合は新規モードに設定する
            me.hidMode = "1";
        } else {
            me.before_close = function () {};
            var userAgent = navigator.userAgent;
            var isIE =
                userAgent.indexOf("compatible") > -1 &&
                userAgent.indexOf("MSIE") > -1;
            var isIE11 =
                userAgent.indexOf("Trident") > -1 &&
                userAgent.indexOf("rv:11.0") > -1;
            $(".HMDPS102ShiharaiDenpyoInput.body").dialog({
                autoOpen: false,
                //20240426 lqs UPD S
                // width: 1150,
                width:
                    strDispNO !== "103" && !isIE && !isIE11
                        ? me.ratio === 1.5
                            ? 890
                            : 1030
                        : me.ratio === 1.5
                        ? 955
                        : 1130,
                //20240426 lqs UPD E
                height:
                    strDispNO == "103"
                        ? me.ratio === 1.5
                            ? 555
                            : 715
                        : isIE || isIE11
                        ? 670
                        : me.ratio === 1.5
                        ? 555
                        : 680,
                modal: true,
                title: "支払伝票入力",
                open: function () {
                    //20240426 lqs INS S
                    var width = me.ratio === 1.5 ? "963px" : "1110px";
                    var scale =
                        me.ratio === 1.5 ? "scale(0.95)" : "scale(0.98)";
                    if (strDispNO == "103") {
                        $(
                            ".ui-dialog .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
                        ).css("transform-origin", "top left");
                        $(
                            ".ui-dialog .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
                        ).css("transform", scale);
                        $(".ui-dialog .HMDPS102ShiharaiDenpyoInput.body").css(
                            "overflow-y",
                            "hidden"
                        );
                        $(".ui-dialog .HMDPS102ShiharaiDenpyoInput.body").css(
                            "width",
                            width
                        );
                    } else if (!isIE && !isIE11) {
                        $(
                            ".ui-dialog .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
                        ).css("transform-origin", "top left");
                        $(
                            ".ui-dialog .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
                        ).css("transform", "scale(0.91)");
                        $(
                            ".ui-dialog .HMDPS102ShiharaiDenpyoInput.HMDPS-content"
                        ).css("margin-top", "-3px");
                        $(".ui-dialog .HMDPS102ShiharaiDenpyoInput.body").css(
                            "overflow-y",
                            "hidden"
                        );
                        $(".ui-dialog .HMDPS102ShiharaiDenpyoInput.body").css(
                            "width",
                            width
                        );
                    }
                    //20240426 lqs INS E
                },
                close: function () {
                    me.before_close();
                    $(".HMDPS102ShiharaiDenpyoInput.body").remove();
                },
            });
            $(".HMDPS102ShiharaiDenpyoInput.body").dialog("open");
            // メニュー以外から開かれた場合は指定されたモードをセットする
            me.hidMode = strMode == undefined ? "" : strMode;
        }

        $(
            ".HMDPS102ShiharaiDenpyoInput.clearLabelL,.HMDPS102ShiharaiDenpyoInput.clearLabelR"
        ).css(
            "height",
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no_NM").css("height")
        );

        // 画面項目をクリアする
        me.subFormClear();

        // 貸方部署コードを不活性にする
        $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").attr("disabled", true);

        // 貸方部署検索ボタンを不活性にする
        $(".HMDPS102ShiharaiDenpyoInput.btnRBusyoSearch").attr(
            "disabled",
            true
        );

        // 支払先名の入力項目を選択する
        $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiNM").show();
        $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD").hide();

        //  ボタンを使用不可にする
        me.DpyInpNewButtonEnabled(99);

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false);

        var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
        var data = {
            strDispNO: strDispNO,
            strSyohy_NO: strSyohy_NO,
            strMode: strMode,
            strEda_No: strEda_No,
            strPattern_NO: strPattern_NO,
            getMemo:
                me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                    ? "0"
                    : "1",
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                //  ボタンを使用不可にする
                me.allBtnDisable(true);
                if (result["data"]["msg"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }

                return;
            }
            var res = result["data"];

            // 経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            switch (me.PatternID) {
                case me.hmdps.CONST_ADMIN_PTN_NO:
                case me.hmdps.CONST_HONBU_PTN_NO:
                    $(".HMDPS102ShiharaiDenpyoInput.pnlTenpo").hide();
                    $(".HMDPS102ShiharaiDenpyoInput.pnlHonbu").show();
                    break;
                default:
                    me.MemoSet(res["MemoTbl"]);
                    $(".HMDPS102ShiharaiDenpyoInput.pnlTenpo").show();
                    $(".HMDPS102ShiharaiDenpyoInput.pnlHonbu").hide();
            }

            // 支払方法の欄を不活性にする
            me.ShiharaiHouhouEnabled(false);

            // 未払費用の項目を不活性にする
            me.MibaraiNaiyoEnabled(false);

            // 支払予定日を不活性にする
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                "disabled",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").datepicker("disable");
            me.hidToDay = res["Today"];

            me.allBusyo = res["Busyo"];
            // 貸方部署に部署コード122をセットする
            $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").val("122");
            me.txtBusyoCD_TextChanged(
                $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").val(),
                "R"
            );

            //ドロップダウンリストを設定する
            me.RKamoku = res["KamokuTbl"];
            me.RKomoku = res["KomokuTbl"];

            me.KamokuMstBlank = res["KamokuMstBlank"];
            me.KamokuMstNotBlank = res["KamokuMstNotBlank"];

            me.Meisyou = res["MeisyouTbl"];
            me.Torihiki = res["TorihikiTbl"];

            me.allTorihikisaki = res["Torihiki"];
            // 20240418 lqs UPD S
            // me.DropDownListSet();
            me.DropDownListSet(res);
            // 20240418 lqs UPD E

            //パターンのドロップダウンリストを設定する
            me.PATTERN_Data = res["PatternTbl"];
            me.PatternDDLSet();
            switch (strDispNO) {
                case "100":
                case "105":
                    // 伝票検索画面又はＣＳＶ再出力画面から開かれた場合
                    $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    // 経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").attr(
                        "disabled",
                        true
                    );
                    $(
                        ".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT"
                    ).datepicker("disable");

                    switch (me.PatternID) {
                        case me.hmdps.CONST_ADMIN_PTN_NO:
                        case me.hmdps.CONST_HONBU_PTN_NO:
                            $(
                                ".HMDPS102ShiharaiDenpyoInput.btnPatternTrk"
                            ).show();
                            $(
                                ".HMDPS102ShiharaiDenpyoInput.btnPatternTrk"
                            ).attr("disabled", false);
                            break;
                        default:
                            $(
                                ".HMDPS102ShiharaiDenpyoInput.btnPatternTrk"
                            ).hide();
                    }
                    switch (strMode) {
                        case "1":
                            // 新規作成の場合
                            // ボタンの活性・不活性を決める(新規の場合)
                            me.DpyInpNewButtonEnabled(1);
                            me.hidCreateDate = "";
                            // 隠し項目・支払予定日初期化
                            me.hidShiharaiDate = "";
                            // 支払予定日をセットする
                            me.subradJikiProc();
                            // ****貸方科目は初期値に振込を選択する****
                            me.subSyokiDataSet(res["RKOUBANTBL"]);
                            break;
                        case "2":
                            // 修正・削除の場合
                            if (
                                res["NewNoTbl"].length > 0 &&
                                res["NewNoTbl"][0]["EDA_NO"] != strEda_No
                            ) {
                                //他のユーザーにより更新されています。最新の情報を確認してください。
                                me.clsComFnc.FncMsgBox("W0025");
                                return;
                            }

                            // 証憑№を表示する
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                                strAllSyohy_No
                            );

                            // 仕訳データの取得
                            var objds = res["DataTbl"];
                            // 該当データが存在しない場合
                            if (objds.length == 0) {
                                // 該当データが削除された可能性があります。最新の情報を確認して下さい。
                                me.clsComFnc.FncMsgBox("W0026");
                                return;
                            }

                            // 選択された仕訳データを画面項目にセットする
                            me.DataFormSet(objds, "100");

                            // 作成日を隠し項目にセット
                            me.hidCreateDate = me.clsComFnc.FncNv(
                                objds[0]["CREATE_DATE"]
                            );

                            // 隠し項目・支払予定日にDBの値をセット
                            me.hidShiharaiDate = me.clsComFnc.FncNv(
                                objds[0]["SHIHARAI_DT"]
                            );

                            // 支払予定日をセットする
                            me.subradJikiProc();

                            // 口座キー・必須摘要に入力されている値があれば活性にする
                            me.KouzaHittekiEnabledCheck();

                            // 口座キー・必須摘要の名称を取得する(借方)
                            objds = res["LKOUBANTBL"];
                            me.LKoubanNMSet(objds, false);

                            // 口座キー・必須摘要の名称を取得する(貸方)
                            objds = res["RKOUBANTBL"];
                            me.RKoubanNMSet(objds, false);
                            // 修正前データを取得する
                            objds = res["SyuseiMaeTbl"];
                            if (
                                me.clsComFnc.FncNv(objds[0]["SYOHY_NO"]) == ""
                            ) {
                                // 修正前データが存在しない場合
                                // 修正前表示ボタンを不活性にする
                                $(
                                    ".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp"
                                ).attr("disabled", true);
                            } else {
                                // 修正前データが存在する場合
                                // 修正前表示ボタンを活性にする
                                $(
                                    ".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp"
                                ).attr("disabled", false);
                            }
                            // 該当枝№チェック
                            objds = res["EdaNoChkTbl"];
                            if (objds.length == 0) {
                                // 該当データが削除された可能性があります。最新の情報を確認して下さい。
                                me.clsComFnc.FncMsgBox("W0026");
                                return;
                            }
                            me.hidUpdDate = me.clsComFnc.FncNv(
                                objds[0]["UPD_DATE"]
                            );

                            if (strDispNO == "100") {
                                // 伝票検索画面からの遷移の場合、モードの設定を行う
                                // 表示モードを指定する
                                objds = res["DispModeTbl"];
                                // 既にＣＳＶ出力されている場合
                                if (
                                    me.clsComFnc.FncNv(
                                        objds[0]["CSV_OUT_FLG"]
                                    ) == "1"
                                ) {
                                    // '*****参照モードで表示する*****
                                    // ボタンを使用不可にする
                                    me.DpyInpNewButtonEnabled(9);

                                    // 画面項目を不活性にする
                                    me.FormEnabled(false);

                                    // 参照モードの設定
                                    me.hidMode = "9";

                                    return;
                                    // '******************************
                                } else if (
                                    me.clsComFnc.FncNv(
                                        objds[0]["HONBU_SYORIZUMI_FLG"]
                                    ) == "1" &&
                                    me.PatternID !=
                                        me.hmdps.CONST_ADMIN_PTN_NO &&
                                    me.PatternID != me.hmdps.CONST_HONBU_PTN_NO
                                ) {
                                    // *****参照モードで表示する*****
                                    // ボタンを使用不可にする
                                    me.DpyInpNewButtonEnabled(9);

                                    // 画面項目を不活性にする
                                    me.FormEnabled(false);

                                    // 参照モードの設定
                                    me.hidMode = "9";

                                    return;
                                    // ******************************
                                } else if (
                                    me.clsComFnc.FncNv(
                                        objds[0]["PRINT_OUT_FLG"]
                                    ) == "1" &&
                                    me.PatternID !=
                                        me.hmdps.CONST_ADMIN_PTN_NO &&
                                    me.PatternID != me.hmdps.CONST_HONBU_PTN_NO
                                ) {
                                    // *****一部参照モードで表示する*****
                                    // ボタンを使用不可にする
                                    me.DpyInpNewButtonEnabled(8);

                                    // 画面項目を不活性にする
                                    me.FormEnabled(false);

                                    // 参照モードの設定
                                    me.hidMode = "8";

                                    return;
                                    // ******************************
                                }
                            }
                            if (objds != undefined) {
                                objds = undefined;
                            }

                            // ボタンの活性・不活性を決める(修正の場合)
                            me.DpyInpNewButtonEnabled(2);

                            break;
                    }
                    break;
                case "103":
                    // パターン検索画面から表示された場合
                    $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(false);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(true);
                    // 経理処理日を非表示にする
                    $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").hide();
                    // 支払伝票入力用項目を非表示にする
                    me.ForPatternVisible();
                    $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").attr(
                        "disabled",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").attr(
                        "disabled",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").attr(
                        "disabled",
                        true
                    );
                    switch (strMode) {
                        case "1":
                            // 新規の場合
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").attr(
                                "disabled",
                                true
                            );
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").attr(
                                "disabled",
                                false
                            );
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").text(
                                "登録"
                            );
                            $(
                                ".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate"
                            ).hide();
                            // 隠し項目を初期化
                            me.hidCreateDate = "";
                            me.hidShiharaiDate = "";
                            // 支払予定日をセットする
                            me.subradJikiProc();
                            break;
                        case "2":
                            // 編集の場合
                            // 選択したパターンデータを取得する
                            var objDs = res["PatternTbl"];
                            if (objDs.length == 0) {
                                // 該当データが削除された可能性があります。最新の情報を確認して下さい。
                                me.clsComFnc.FncMsgBox("W0026");
                                return;
                            }

                            me.hidPatternNO = strPattern_NO;

                            // パターンデータを画面項目にセットする
                            me.DataFormSet(objDs, "103");

                            me.hidCreateDate = "";

                            me.hidShiharaiDate = "";

                            // 支払予定日をセットする
                            me.subradJikiProc();

                            // 口座キー・必須摘要に入力されている値があれば活性にする
                            me.KouzaHittekiEnabledCheck();

                            // 口座キー・必須摘要の名称を取得する(借方)
                            objDs = res["LKOUBANTBL"];
                            me.LKoubanNMSet(objDs, false);

                            // 口座キー・必須摘要の名称を取得する(貸方)
                            objDs = res["RKOUBANTBL"];
                            me.RKoubanNMSet(objDs, false);

                            // ボタンを活性にする
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").attr(
                                "disabled",
                                false
                            );
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").attr(
                                "disabled",
                                false
                            );
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").text(
                                "新規登録"
                            );
                            $(
                                ".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate"
                            ).show();
                            $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").attr(
                                "disabled",
                                false
                            );

                            break;
                    }

                    me.radPatternBusyo_CheckedChanged();
                    // 20240418 lqs UPD S
                    // $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").trigger("focus");
                    $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").trigger(
                        "focus"
                    );
                    // 20240418 lqs UPD E
                    break;
                default:
                    // それ以外から表示された場合
                    $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    // ボタンの活性・不活性を決める(新規の場合)
                    me.DpyInpNewButtonEnabled(1);

                    // ****貸方科目は初期値に振込を選択する
                    me.subSyokiDataSet(res["RKOUBANTBL"]);

                    if (
                        me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                    ) {
                        $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").show();
                        $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").attr(
                            "disabled",
                            false
                        );
                    } else {
                        $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").hide();
                    }

                    // 経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").attr(
                        "disabled",
                        true
                    );
                    $(
                        ".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT"
                    ).datepicker("disable");

                    // 閉じるボタンを非表示にする
                    $(".HMDPS102ShiharaiDenpyoInput.btnClose").hide();

                    me.hidCreateDate = "";

                    me.hidShiharaiDate = "";

                    // 支払予定日をセットする
                    me.subradJikiProc();
            }

            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").prop(
                    "disabled"
                ) == false
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").trigger("focus");
            }

            // 隠し項目・支払予定日を初期化
            me.hidShiharaiDate = "";

            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").attr(
                "disabled",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").datepicker(
                "disable"
            );
        };
        me.ajax.send(url, data, 0);
    };

    //20240418 lqs INS S
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
        me.txtOkyakusamaNOTorihikisakiNm_TextChanged(flg);
        try {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .toString() == "3"
            ) {
                $(
                    ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                ).attr("disabled", "disabled");
            } else {
                $(
                    ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
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
        flg = typeof flg === "undefined" ? true : flg;
        try {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm")
                    .val()
                    .trim() == ""
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.lblOkyakuNOTorihikisakiNm").val(
                    ""
                );
                $(
                    ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
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
                ddlAitesakiKBN: $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .replace(me.blankReplace, "")
                    .toString(),
                txtOkyakusamaNOTorihikisakiNm: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
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
                        ".HMDPS102ShiharaiDenpyoInput.lblOkyakuNOTorihikisakiNm"
                    ).val(strRet);
                    if (flg) {
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                        ).val(strRet);
                    }
                    $(".HMDPS102ShiharaiDenpyoInput.lblKensakuCD").val(
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                        )
                            .val()
                            .trim()
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.lblKensakuNM").val(strRet);
                } else {
                    $(
                        ".HMDPS102ShiharaiDenpyoInput.lblOkyakuNOTorihikisakiNm"
                    ).val("");
                    if (flg) {
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                        ).val("");
                    }
                    $(".HMDPS102ShiharaiDenpyoInput.lblKensakuCD").val("");
                    $(".HMDPS102ShiharaiDenpyoInput.lblKensakuNM").val("");
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
            $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").val().toString() ==
            "0"
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo").val(
                "T0000000000000"
            );
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo").val("");
        }
    };
    //20240418 lqs INS E

    // '**********************************************************************
    // '処 理 名：貸方科目コードが変更された時
    // '関 数 名：ddlRKamokuCD_SelectedIndexChanged
    // '処理説明：貸方項目コードを活性にする
    // '**********************************************************************
    me.ddlRKamokuCD_SelectedIndexChanged = function () {
        //'貸方科目セット時の処理を行う
        me.fncRKamokuCDSetProc();

        me.url =
            me.sys_id + "/" + me.id + "/" + "ddlRKamokuCD_SelectedIndexChanged";
        var data = {
            ddlRKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val(),
            ddlRKomokuCD: $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];

            me.RKoubanNMSet(res["RKOUBANTBL"], true);
            me.RKouzaHittekiNmNothingClear();
        };
        me.ajax.send(me.url, data, 0);
    };

    me.ForPatternVisible = function () {
        $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").hide();
        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").hide();
        $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").hide();
        $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").hide();
        $(".HMDPS102ShiharaiDenpyoInput.CopyMotoRow").hide();
        $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").hide();
        $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").hide();
        $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT-dateDiv").hide();
        $(".HMDPS102ShiharaiDenpyoInput.KeyTableRow").hide();
        $(".HMDPS102ShiharaiDenpyoInput.KingakuRow").hide();
    };
    // '**********************************************************************
    // '処 理 名：修正前データの表示を行う
    // '関 数 名：btnSyuseiMaeDisp_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：修正前データの表示を行う
    // '**********************************************************************
    me.btnSyuseiMaeDisp_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "btnSyuseiMaeDisp_Click";
        var data = {
            lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                .val()
                .trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];

            var objDs = res["SYUSEIMAETBL"];
            //修正前データ件数
            if (me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) == "") {
                //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return;
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) +
                    me.clsComFnc.FncNv(objDs[0]["EDA_NO"])
            );
            $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").show();
            //画面項目をクリアする
            me.subFormClear(true);
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                0
            ).selectedIndex = 0;

            // 登録内容を画面項目に表示する
            // データの取得
            objDs = res["DataTbl"];
            // 該当データが存在しない場合
            if (objDs.length == 0) {
                // '該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return;
            }

            // 選択された仕訳データを画面項目にセットする
            me.DataFormSet(objDs, "100");

            // 口座キー・必須摘要に入力されている値があれば活性にする
            me.KouzaHittekiEnabledCheck();

            // '口座キー・必須摘要の名称を取得する(借方)
            objDs = res["LKOUBANTBL"];
            me.LKoubanNMSet(objDs, false);

            // 口座キー・必須摘要の名称を取得する(貸方)
            objDs = res["RKOUBANTBL"];
            me.RKoubanNMSet(objDs, false);

            // 口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false);
            //*****参照モードで表示する*****
            //ボタンを使用不可にする
            me.DpyInpNewButtonEnabled(9);

            //画面項目を不活性にする
            me.FormEnabled(false);

            if (me.hidMode != "9") {
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").hide();
            } else if (me.hidMode != "8") {
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").hide();
            }

            $(".HMDPS102ShiharaiDenpyoInput.btnPrint").attr("disabled", true);

            //修正前データを取得する
            objDs = res["SyuseiMaeTbl"];
            if (me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) == "") {
                // 修正前データが存在しない場合
                // 修正前表示ボタンを不活性にする
                $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").attr(
                    "disabled",
                    true
                );
            } else {
                // 修正前データが存在する場合
                // 修正前表示ボタンを活性にする
                $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").attr(
                    "disabled",
                    false
                );
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：最新データの表示を行う
    // '関 数 名：btnSaishinDisp_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：最新データの表示を行う
    // '**********************************************************************
    me.btnSaishinDisp_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "btnSaishinDisp_Click";
        var data = {
            lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                .val()
                .trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];
            if (res["NEWTBL"].length == 0) {
                //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return;
            }

            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                    .val()
                    .trimEnd()
                    .toString()
                    .substring(0, 15) + res["NEWTBL"][0]["EDA_NO"]
            );

            // 画面項目をクリアする
            me.subFormClear(true);
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                0
            ).selectedIndex = 0;
            me.FormEnabled(true);

            me.hidCreateDate = "";

            // 口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false);

            //登録内容を画面項目に表示する
            var objDs = res["DataTbl"];
            // 該当データが存在しない場合
            if (objDs.length == 0) {
                // '該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return;
            }
            // 選択された仕訳データを画面項目にセットする
            me.DataFormSet(objDs, "100");

            me.hidCreateDate = me.clsComFnc.FncNv(objDs[0]["CREATE_DATE"]);

            // 隠し項目・支払予定日にDBの値セット
            me.hidShiharaiDate = me.clsComFnc.FncNv(objDs[0]["SHIHARAI_DT"]);

            // 支払予定日をセットする
            me.subradJikiProc();

            // 口座キー・必須摘要に入力されている値があれば活性にする
            me.KouzaHittekiEnabledCheck();

            // '口座キー・必須摘要の名称を取得する(借方)
            objDs = res["LKOUBANTBL"];
            me.LKoubanNMSet(objDs, false);

            // 口座キー・必須摘要の名称を取得する(貸方)
            objDs = res["RKOUBANTBL"];
            me.RKoubanNMSet(objDs, false);

            if (me.hidMode == "9" || me.hidMode == "8") {
                me.FormEnabled(false);
            } else {
                // 対象外が選択されている場合
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                        "selectedIndex"
                    ) > 0
                ) {
                    if (
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn"
                        ).val() == "90"
                    ) {
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                            "disabled",
                            true
                        );
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                    }
                }
                // 対象外が選択されている場合
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                        "selectedIndex"
                    ) > 0
                ) {
                    if (
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn"
                        ).val() == "90"
                    ) {
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                            "disabled",
                            true
                        );
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                    }
                }
            }
            $(".HMDPS102ShiharaiDenpyoInput.btnPrint").attr("disabled", false);

            //修正前データを取得する
            objDs = res["SyuseiMaeTbl"];
            if (me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) == "") {
                // 修正前データが存在しない場合
                // 修正前表示ボタンを不活性にする
                $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").attr(
                    "disabled",
                    true
                );
            } else {
                // 修正前データが存在する場合
                // 修正前表示ボタンを活性にする
                $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").attr(
                    "disabled",
                    false
                );
            }

            // ボタンを使用可にする
            me.DpyInpNewButtonEnabled(me.hidMode);

            $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").hide();
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：コピー元証憑№表示ボタン押下時
    // '関 数 名：btnCopySyohy_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：コピー元証憑№に入力された証憑№の仕訳データを取得し、表示する
    // '**********************************************************************
    me.btnCopySyohy_Click = function () {
        // 入力チェックを行う
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo").val().trimEnd() ==
            ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo"
            );
            me.clsComFnc.FncMsgBox("E0012", "コピー元証憑№");
            return;
        }
        if (
            me.clsComFnc.FncSprCheck(
                $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo").val(),
                0,
                0,
                17
            ) < 0
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo"
            );
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo").val().trimEnd()
                .length != 17
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo"
            );
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo")
                    .val()
                    .substring(0, 1) == "1"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo"
                );
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "仕訳伝票の仕訳をコピーすることは出来ません。支払伝票の仕訳のみコピー可能です！"
                );
                return;
            }
        }
        me.url = me.sys_id + "/" + me.id + "/" + "btnCopySyohy_Click";
        var data = {
            txtCopySyohyNo: $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo")
                .val()
                .trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                //  ボタンを使用不可にする
                me.allBtnDisable(true);
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];
            // 登録内容を画面項目に表示する
            // 仕訳データの取得
            var objDs = res["DataTbl"];
            // 該当データが存在しない場合
            if (objDs.length == 0) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo"
                );
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            // 画面項目をクリアする
            me.subFormClear(false, false);
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
                0
            ).selectedIndex = 0;
            $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                0
            ).selectedIndex = 0;

            me.hidCreateDate = "";
            // 口座キー、必須摘要を不活性にする
            me.KouzaHiTekkiEnabledSet(false);

            // 選択された仕訳データを画面項目にセットする
            me.DataFormSet(res["DataTbl"], "100", false);

            me.hidCreateDate = "";

            me.hidShiharaiDate = "";

            // 支払予定日をセットする
            me.subradJikiProc();

            // 口座キー・必須摘要に入力されている値があれば活性にする
            me.KouzaHittekiEnabledCheck();
            //口座キー・必須摘要の名称を取得する(借方)
            me.LKoubanNMSet(res["LKOUBANTBL"], false);
            //口座キー・必須摘要の名称を取得する(貸方)
            me.RKoubanNMSet(res["RKOUBANTBL"], false);

            //ボタンを使用可にする
            me.DpyInpNewButtonEnabled(me.hidMode);
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：削除を行う
    // '関 数 名：btnAllDelete_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ＤＢへの削除処理を行う
    // '**********************************************************************
    me.btnAllDelete_Click = function () {
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
            me.clsComFnc.FncMsgBox("QY004");
        }
    };
    // '**********************************************************************
    // '処 理 名：パターンを削除する(パターン検索画面より遷移)
    // '関 数 名：btnPtnDelete_Click
    // '処理説明：パターンを削除する
    // '**********************************************************************
    me.btnPtnDelete_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.url = me.sys_id + "/" + me.id + "/" + "btnPtnDelete_Click";
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
                    me.close1();
                };
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    me.close1();
                };
                me.clsComFnc.FncMsgBox("I0017");
            };

            me.ajax.send(me.url, data, 0);
        };

        //確認メッセージを表示する
        me.clsComFnc.FncMsgBox("QY004");
    };
    // '**********************************************************************
    // '処 理 名：パターンを登録する(パターン検索画面より遷移)
    // '関 数 名：btnPtnInsert_Click
    // '処理説明：パターンを登録する
    // '**********************************************************************
    me.btnPtnInsert_Click = function (ID) {
        //入力チェックを行う
        if (
            $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").is(":checked") &&
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val().trimEnd() ==
                ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo"
            );
            me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
            return;
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").is(":checked") &&
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val().trimEnd() !=
                ""
        ) {
            //対象部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo")
                        .val()
                        .trimEnd()
                );
            });

            if (index == -1) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "対象部署コードが部署マスタに存在しません！"
                );
                return;
            }
        }

        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, "") == ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtPatternNM"
            );
            me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
            return;
        } else {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 40
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtPatternNM"
                );
                me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                return;
            }
        }
        //入力チェックを行う(必須チェックは行わない)
        if (!me.fncInputCheck(false)) {
            return;
        }

        // 未払費用チェック(必須チェックは行わない)
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val() != null &&
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD")
                .val()
                .padRight(6)
                .substring(1) == "21152"
        ) {
            var strTorihikiNM = null;
            var find = me.allTorihikisaki.filter(function (one) {
                return (
                    one["ATO_DTRPITCD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                        .val()
                        .trimEnd()
                );
            });
            if (find.length > 0) {
                strTorihikiNM = find[0]["ATO_DTRPTBNM"];
            }
            me.TorihikiMst.strTorihikiNM = strTorihikiNM;
            if (!me.fncMibaraiKoumokuCheck(false)) {
                return;
            }
        }

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
    };
    // '**********************************************************************
    // '処 理 名：パターン選択
    // '関 数 名：ddlPatternSel_SelectedIndexChanged
    // '処理説明：選択されたパターンによって仕訳を展開する
    // '**********************************************************************
    me.ddlPatternSel_SelectedIndexChanged = function () {
        // パターン選択されていない場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").prop(
                "selectedIndex"
            ) <= 0
        ) {
            return;
        }

        // 画面項目をクリアする
        me.subFormClear();

        // 時期に関する設定を行う
        me.subradJikiProc();

        me.hidCreateDate = "";

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false);

        me.url =
            me.sys_id +
            "/" +
            me.id +
            "/" +
            "ddlPatternSel_SelectedIndexChanged";
        var data = {
            ddlPatternSel: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlPatternSel"
            ).val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var res = result["data"];

            var objDs = res["PATTERNTBL"];
            if (objDs.length == 0) {
                return;
            }

            // 選択された仕訳データを画面項目にセットする
            me.DataFormSet(objDs, "102");

            if (
                $(".HMDPS102ShiharaiDenpyoInput.radJikiHiduke").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                    "disabled",
                    false
                );
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                    "disabled",
                    true
                );
            }

            // 隠し項目・支払予定日を初期化
            me.hidShiharaiDate = "";

            // 口座キー・必須摘要に入力されている値があれば活性にする
            me.KouzaHittekiEnabledCheck();

            //口座キー・必須摘要の名称を取得する(借方)
            objDs = res["LKOUBANTBL"];
            me.LKoubanNMSet(objDs, false);

            //口座キー・必須摘要の名称を取得する(貸方)
            objDs = res["RKOUBANTBL"];
            me.RKoubanNMSet(objDs, false);

            // 時期に関する設定を行う
            me.subradJikiProc();
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：表示されている仕訳をパターンとして登録する
    // '関 数 名：btnPatternTrk_Click
    // '処理説明：表示されている仕訳をパターンとして登録する
    // '**********************************************************************
    me.btnPatternTrk_Click = function () {
        // 前処理
        if (
            $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").css("display") ==
            "none"
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val(
                $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val())
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val(
                $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val())
            );

            me.txtLkamokuCDKoumokuSet(
                $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD"),
                false
            );

            me.txtBusyoCD_TextChanged(
                $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val(),
                "L"
            );

            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                    .val()
                    .trimEnd() != ""
            ) {
                me.txtShiharaisakiCD_TextChanged(
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                );
            }
            me.txtZeikm_GK_TextChanged();
            me.txtTekyo_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            me.radJikiHiduke_CheckedChanged();
        }

        // 一部参照モード
        if (me.hidMode == "9" || me.hidMode == "8") {
            me.FormEnabled(false);
        }

        // 入力チェックを行う
        if (
            $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").is(":checked") &&
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val().trimEnd() ==
                ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo"
            );
            me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
            return;
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").is(":checked") &&
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val().trimEnd() !=
                ""
        ) {
            //借方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo")
                        .val()
                        .trimEnd()
                );
            });

            if (index == -1) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "対象部署コードが部署マスタに存在しません！"
                );
                return;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, "") == ""
        ) {
            me.clsComFnc.ObjFocus = $(
                ".HMDPS102ShiharaiDenpyoInput.txtPatternNM"
            );
            me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
            return;
        } else {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 40
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtPatternNM"
                );
                me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                return;
            }
        }

        //入力チェックを行う(必須チェックは行わない)
        if (me.fncInputCheck(false) == false) {
            return;
        }

        //未払費用チェック(必須チェックは行わない)
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val() != null &&
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD")
                .val()
                .padRight(6)
                .substring(1) == "21152"
        ) {
            var strTorihikiNM = null;
            var find = me.allTorihikisaki.filter(function (one) {
                return (
                    one["ATO_DTRPITCD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                        .val()
                        .trimEnd()
                );
            });
            if (find.length > 0) {
                strTorihikiNM = find[0]["ATO_DTRPTBNM"];
            }
            me.TorihikiMst.strTorihikiNM = strTorihikiNM;
            if (!me.fncMibaraiKoumokuCheck(false)) {
                return;
            }
        }

        //確認メッセージを表示する
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdEventPatternTrk_Click;
        me.clsComFnc.FncMsgBox("QY018");
    };
    // '**********************************************************************
    // '処 理 名：表示されている仕訳をパターンとして登録する
    // '関 数 名：cmdEventPatternTrk_Click
    // '処理説明：表示されている仕訳をパターンとして登録する
    // '**********************************************************************
    me.cmdEventPatternTrk_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "cmdEventPatternTrk_Click";
        var fncFukanzenCheck = me.fncFukanzenCheck();
        var data = {
            hidPatternNO: me.clsComFnc.FncNv(me.hidPatternNO),
            txtZeikm_GK: $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                .val()
                .trimEnd()
                .replace(/,/g, ""),
            lblZeink_GK: $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK")
                .text()
                .trimEnd()
                .replace(/,/g, ""),
            lblSyohizei: $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei")
                .text()
                .trimEnd()
                .replace(/,/g, ""),
            txtTekyo: $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                .val()
                .replace(me.blankReplace, ""),
            txtLKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                .val()
                .trimEnd(),
            txtLKomokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                .val()
                .trimEnd(),
            txtLBusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                .val()
                .trimEnd(),
            ddlLSyohizeiKbn: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn"
            ).val(),
            ddlLTorihikiKbn: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn"
            ).val(),
            txtLKouzaKey1: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1")
                .val()
                .trimEnd(),
            txtLKouzaKey2: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2")
                .val()
                .trimEnd(),
            txtLKouzaKey3: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3")
                .val()
                .trimEnd(),
            txtLKouzaKey4: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4")
                .val()
                .trimEnd(),
            txtLKouzaKey5: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5")
                .val()
                .trimEnd(),
            txtLHissuTekyo1: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1")
                .val()
                .trimEnd(),
            txtLHissuTekyo2: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2")
                .val()
                .trimEnd(),
            txtLHissuTekyo3: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3")
                .val()
                .trimEnd(),
            txtLHissuTekyo4: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4")
                .val()
                .trimEnd(),
            txtLHissuTekyo5: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5")
                .val()
                .trimEnd(),
            txtLHissuTekyo6: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6")
                .val()
                .trimEnd(),
            txtLHissuTekyo7: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7")
                .val()
                .trimEnd(),
            txtLHissuTekyo8: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8")
                .val()
                .trimEnd(),
            txtLHissuTekyo9: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9")
                .val()
                .trimEnd(),
            txtLHissuTekyo10: $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10")
                .val()
                .trimEnd(),
            ddlRKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val(),
            ddlRKomokuCD: $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val(),
            txtRbusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                .val()
                .trimEnd(),
            ddlRSyohizeiKbn: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn"
            ).val(),
            ddlRTorihikiKbn: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn"
            ).val(),
            txtRKouzaKey1: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1")
                .val()
                .trimEnd(),
            txtRKouzaKey2: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2")
                .val()
                .trimEnd(),
            txtRKouzaKey3: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3")
                .val()
                .trimEnd(),
            txtRKouzaKey4: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4")
                .val()
                .trimEnd(),
            txtRKouzaKey5: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5")
                .val()
                .trimEnd(),
            txtRHissuTekyo1: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1")
                .val()
                .trimEnd(),
            txtRHissuTekyo2: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2")
                .val()
                .trimEnd(),
            txtRHissuTekyo3: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3")
                .val()
                .trimEnd(),
            txtRHissuTekyo4: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4")
                .val()
                .trimEnd(),
            txtRHissuTekyo5: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5")
                .val()
                .trimEnd(),
            txtRHissuTekyo6: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6")
                .val()
                .trimEnd(),
            txtRHissuTekyo7: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7")
                .val()
                .trimEnd(),
            txtRHissuTekyo8: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8")
                .val()
                .trimEnd(),
            txtRHissuTekyo9: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9")
                .val()
                .trimEnd(),
            txtRHissuTekyo10: $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10")
                .val()
                .trimEnd(),
            txtSeikyusyoNO: $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO")
                .val()
                .trimEnd(),
            txtTorihikiHasseibi: $(
                ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
            )
                .val()
                .trimEnd()
                .replace(/\//g, ""),
            txtShiharaisakiCD: $(
                ".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD"
            )
                .val()
                .trimEnd(),
            lblShiharaisakiNM: $(
                ".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM"
            )
                .val()
                .trimEnd(),
            txtShiharaisaki: $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                .val()
                .trimEnd(),
            grpGinko: $(
                '.HMDPS102ShiharaiDenpyoInput.grpGinko input[name="grpGinko"]:checked'
            ).val(),
            txtSonotaShiten: $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten")
                .val()
                .trimEnd(),
            txtSonotaGinko: $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko")
                .val()
                .trimEnd(),
            grpSyubetu: $(
                '.HMDPS102ShiharaiDenpyoInput.grpSyubetu input[name="grpSyubetu"]:checked'
            ).val(),
            txtKouzaNO: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO")
                .val()
                .trimEnd(),
            txtKouzaNM: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                .val()
                .trimEnd(),
            grpJiki: $(
                '.HMDPS102ShiharaiDenpyoInput.grpJiki input[name="grpJiki"]:checked'
            ).val(),
            pnlShiharaiCDVis: $(
                ".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD"
            ).is(":hidden")
                ? "0"
                : "1",
            txtJikiDate: $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                .val()
                .replace(/\//g, ""),
            txtSeikyusyoNOEna: $(
                ".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO"
            ).is(":disabled")
                ? "0"
                : "1",
            txtTorihikiHasseibiEna: $(
                ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
            ).is(":disabled")
                ? "0"
                : "1",
            radHiroGinkoEna: $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").is(
                ":disabled"
            )
                ? "0"
                : "1",
            txtSonotaGinkoEna: $(
                ".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko"
            ).is(":disabled")
                ? "0"
                : "1",
            radSyubetuTouzaEna: $(
                ".HMDPS102ShiharaiDenpyoInput.radSyubetuTouza"
            ).is(":disabled")
                ? "0"
                : "1",
            txtKouzaNOEna: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").is(
                ":disabled"
            )
                ? "0"
                : "1",
            txtKouzaNMEna: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").is(
                ":disabled"
            )
                ? "0"
                : "1",
            fncFukanzenCheck: fncFukanzenCheck,
            lblSyohyNoVis: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").is(
                ":disabled"
            )
                ? "0"
                : "1",
            txtPatternNM: $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, ""),
            grpPattern: $(
                '.HMDPS102ShiharaiDenpyoInput.grpPattern input[name="grpPattern"]:checked'
            ).val(),
            txtPatternBusyo: $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo")
                .val()
                .trimEnd(),
            txtSonotaShitenEna: $(
                ".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten"
            ).is(":disabled")
                ? "0"
                : "1",
            //20240418 lqs INS S
            ddlAitesakiKBN: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN"
            ).val(),
            txtOkyakusamaNOTorihikisakiNm: $(
                ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
            )
                .val()
                .trimEnd(),
            txtTorokuNoKazeiMenzeiGyosya: $(
                ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
            )
                .val()
                .trimEnd(),
            txtJigyosyoMeiTorokuNo: $(
                ".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo"
            )
                .val()
                .trimEnd(),
            ddlTokureiKBN: $(
                ".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN"
            ).val(),
            //20240418 lqs INS E
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var res = result["data"];

            if (me.clsComFnc.FncNv(me.hidPatternNO) == "") {
                if (me.hidDispNO == "103" && me.hidMode == "2") {
                    //登録完了のメッセージを表示し、画面を閉じる
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        me.close1();
                    };
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        me.close1();
                    };
                    me.clsComFnc.FncMsgBox("I0016");
                } else {
                    //画面項目をクリアする
                    if (me.hidDispNO == "103") {
                        me.subFormClear(true);
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                            "disabled",
                            true
                        );
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                            "disabled",
                            false
                        );
                        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                            "disabled",
                            false
                        );
                    }

                    // 支払予定日をセットする
                    me.subradJikiProc();
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM").val("");
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val("");
                    $(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").prop(
                        "checked",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").prop(
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
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                        "disabled",
                        false
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                        "disabled",
                        false
                    );
                    // 支払予定日をセットする
                    me.subradJikiProc();
                    me.hidCreateDate = "";
                    me.hidShiharaiDate = "";
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM").val("");
                    $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val("");
                    $(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").prop(
                        "checked",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").prop(
                        "checked",
                        false
                    );
                    me.radPatternBusyo_CheckedChanged();
                    //登録完了のメッセージを表示し、画面を閉じる
                    me.clsComFnc.FncMsgBox("I0016");
                } else {
                    //登録完了のメッセージを表示し、画面を閉じる
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        me.close1();
                    };
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        me.close1();
                    };
                    me.clsComFnc.FncMsgBox("I0016");
                }
            }

            //パターンのドロップダウンリストを設定する
            me.PATTERN_Data = res["PatternTbl"];
            me.PatternDDLSet();
        };

        me.ajax.send(me.url, data, 0);
    };

    // 20240418 lqs UPD S
    // me.DropDownListSet = function () {
    me.DropDownListSet = function (result) {
        // 20240418 lqs UPD E
        //貸方科目コードにセット
        for (var index = 0; index < me.RKamoku.length; index++) {
            var opt = me.RKamoku[index];
            $("<option></option>")
                .val(opt["SUCHI1"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD");
        }
        //貸方項目コードを不活性にする
        $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr("disabled", true);
        //借方消費税区分にセット
        for (var index = 0; index < me.Meisyou.length; index++) {
            var opt = me.Meisyou[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn");
        }
        //貸方消費税区分にセット
        for (var index = 0; index < me.Meisyou.length; index++) {
            var opt = me.Meisyou[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn");
        }
        //借方取引区分にセット
        for (var index = 0; index < me.Torihiki.length; index++) {
            var opt = me.Torihiki[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn");
        }
        //貸方取引区分にセット
        for (var index = 0; index < me.Torihiki.length; index++) {
            var opt = me.Torihiki[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn");
        }
        //20240418 lqs INS S
        // '相手先区分の値を取得
        var AitesakiTbl = result["AitesakiKBN"];
        for (var index = 0; index < AitesakiTbl.length; index++) {
            AitesakiTbl[index]["MEISYOU"] =
                AitesakiTbl[index]["MEISYOU"] == null
                    ? ""
                    : AitesakiTbl[index]["MEISYOU"];
            $("<option></option>")
                .val(AitesakiTbl[index]["MEISYOU_CD"])
                .text(AitesakiTbl[index]["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN");
        }

        // '特例区分の値を取得
        var TokureiTbl = result["TokureiKBN"];
        for (var index = 0; index < TokureiTbl.length; index++) {
            TokureiTbl[index]["MEISYOU"] =
                TokureiTbl[index]["MEISYOU"] == null
                    ? ""
                    : TokureiTbl[index]["MEISYOU"];
            $("<option></option>")
                .val(TokureiTbl[index]["MEISYOU_CD"])
                .text(TokureiTbl[index]["MEISYOU"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN");
        }
        //20240418 lqs INS E
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：PatternDDLSet
	 '処理説明：
	 '**********************************************************************
	 */
    me.PatternDDLSet = function () {
        //パターン選択にセット
        $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").empty();
        for (var index = 0; index < me.PATTERN_Data.length; index++) {
            var opt = me.PATTERN_Data[index];
            $("<option></option>")
                .val(opt["PATTERN_NO"])
                .text(opt["PATTERN_NM"] == null ? "" : opt["PATTERN_NM"])
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel");
        }
    };
    // '**********************************************************************
    // '処 理 名：クリア処理
    // '関 数 名：btnClear_Click
    // '処理説明：画面項目をクリアする
    // '**********************************************************************
    me.btnClear_Click = function (ifback) {
        ifback = ifback == undefined ? true : ifback;
        // 画面項目をクリアする
        me.subFormClear(true);

        // 時期に関する設定を行う
        me.subradJikiProc();

        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").prop(
                "selectedIndex"
            ) > -1
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").get(
                0
            ).selectedIndex = 0;
        }

        // ドロップダウンをクリアする
        $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").get(
            0
        ).selectedIndex = 0;
        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
            0
        ).selectedIndex = 0;
        $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").get(
            0
        ).selectedIndex = 0;
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
            0
        ).selectedIndex = 0;

        //20240418 lqs INS S
        $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").get(
            0
        ).selectedIndex = 0;
        $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").get(
            0
        ).selectedIndex = 0;
        //20240418 lqs INS E

        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
            "disabled",
            false
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
            "disabled",
            false
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr("disabled", true);

        // ボタンの活性・不活性を設定する
        me.DpyInpNewButtonEnabled(4);

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false);

        if (ifback) {
            me.url = me.sys_id + "/" + me.id + "/" + "btnClear_Click";
            var data = {
                ddlRKamokuCD: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
                ).val(),
                ddlRKomokuCD: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD"
                ).val(),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var res = result["data"];

                // 貸方科目は初期値に振込を選択する
                me.subSyokiDataSet(res["RKOUBANTBL"]);
            };
            me.ajax.send(me.url, data, 0);
        }
    };
    // '**********************************************************************
    // '処 理 名：借方科目項目名取得
    // '関 数 名：txtLKamokuCD_LostFocus
    // '処理説明：フォーカス移動時に科目項目名を取得する
    // '**********************************************************************
    me.txtLKamokuCD_TextChanged = function (sender, changeFlag) {
        me.txtLkamokuCDKoumokuSet(sender, true, changeFlag);
    };
    // txtLKamokuCD_TextChangedの内容を関数化
    me.txtLkamokuCDKoumokuSet = function (sender, DefalutValue, changeFlag) {
        DefalutValue = DefalutValue == undefined ? true : DefalutValue;
        changeFlag = changeFlag == undefined ? false : changeFlag;

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false, 1);

        // 項目名をクリアする
        me.LKouzaHittekiClear();

        if ($.trim(sender.val()) != "") {
            me.url = me.sys_id + "/" + me.id + "/" + "txtLkamokuCDKoumokuSet";
            var data = {
                strCode: $.trim(sender.val()),
                strKomoku:
                    $.trim(
                        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()
                    ) == ""
                        ? "999999"
                        : $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                              .val()
                              .trimEnd(),
                txtLKomokuCD: $.trim(
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()
                ),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var res = result["data"];

                //** 名称取得
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val(
                    res["lblLKamokuNM"]
                );

                //口座キー・必須摘要の名称を取得する(借方)
                me.LKoubanNMSet(res["LKOUBANTBL"], DefalutValue);
                me.LKouzaHittekiNmNothingClear();
                if (changeFlag == false) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").trigger(
                        "focus"
                    );
                } else if (changeFlag == true) {
                    $(".HMDPS102ShiharaiDenpyoInput.btnLKamokuSearch").trigger(
                        "focus"
                    );
                } else if (changeFlag == "2") {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").trigger(
                        "focus"
                    );
                }
                if ($("div[id*='MsgBox_']").length > 0) {
                    $("div[id*='MsgBox_']")
                        .next()
                        .find(".ui-button")
                        .first()
                        .trigger("focus");
                }
            };
            me.ajax.send(me.url, data, 0);
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
        }
    };
    // '**********************************************************************
    // '処 理 名：科目項目名取得
    // '関 数 名：txtLKamokuCD_LostFocus
    // '処理説明：フォーカス移動時に科目項目名を取得する
    // '**********************************************************************
    me.txtLKomokuCD_TextChanged = function () {
        me.txtLkoumkCDKoumokuSet(true);
        $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").trigger("focus");
    };
    // txtLKomokuCD_TextChangedの内容を関数化
    me.txtLkoumkCDKoumokuSet = function (DefalutValue) {
        DefalutValue = DefalutValue == undefined ? true : DefalutValue;

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false, 1);

        // 項目名をクリアする
        me.LKouzaHittekiClear();

        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val(
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val())
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnLKamokuSearch").trigger("focus");

        if (
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val()) == ""
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
            return;
        } else {
            me.url = me.sys_id + "/" + me.id + "/" + "txtLkoumkCDKoumokuSet";
            var data = {
                strCode: $.trim(
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val()
                ),
                strKomoku:
                    $.trim(
                        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()
                    ) == ""
                        ? "999999"
                        : $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                              .val()
                              .trimEnd(),
                txtLKomokuCD: $.trim(
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()
                ),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var res = result["data"];

                //** 名称取得
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val(
                    res["lblLKamokuNM"]
                );

                //口座キー・必須摘要の名称を取得する(借方)
                me.LKoubanNMSet(res["LKOUBANTBL"], DefalutValue);
                me.LKouzaHittekiNmNothingClear();
            };
            me.ajax.send(me.url, data, 0);
        }
    };
    // '**********************************************************************
    // '処 理 名：登録を行う
    // '関 数 名：btnAdd_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：行追加処理(入力チェック・確認メッセージの表示を行う)
    // '**********************************************************************
    me.btnAdd_Click = function () {
        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val(
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val())
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val(
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val())
        );

        // 前処理
        // txtLkamokuCDKoumokuSet、名称取得
        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false, 1);
        // 項目名をクリアする
        me.LKouzaHittekiClear();
        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val(
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val())
        );

        me.url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
        var data = {
            txtLKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                .val()
                .trimEnd(),
            //20240426 lqs UPD S
            // txtLKomokuCD:
            // 	$.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()) == ""
            // 		? "999999"
            // 		: $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val().trimEnd(),
            strLKomokuCD:
                $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()) ==
                ""
                    ? "999999"
                    : $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                          .val()
                          .trimEnd(),
            txtLKomokuCD: $.trim(
                $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val()
            ),
            //20240426 lqs UPD E
            txtLBusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                .val()
                .trimEnd(),
            txtRbusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                .val()
                .trimEnd(),
        };
        //
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];
            // me.txtLkamokuCDKoumokuSet($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD"), false);
            if (
                $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val()) ==
                ""
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
            } else {
                //** 名称取得
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val(
                    res["lblLKamokuNM"]
                );

                //口座キー・必須摘要の名称を取得する(借方)
                me.LKoubanNMSet(res["LKOUBANTBL"], false);
                me.LKouzaHittekiNmNothingClear();
            }

            me.txtBusyoCD_TextChanged(
                $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val(),
                "L"
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                    .val()
                    .trimEnd() != ""
            ) {
                me.txtShiharaisakiCD_TextChanged(
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                );
            }
            me.txtZeikm_GK_TextChanged();
            me.txtTekyo_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();

            // 入力チェックを行う
            if (!me.fncInputCheck()) {
                return;
            }

            // 未払費用チェック
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val() != null &&
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD")
                    .val()
                    .padRight(6)
                    .substring(1) == "21152"
            ) {
                var strTorihikiNM = null;
                var find = me.allTorihikisaki.filter(function (one) {
                    return (
                        one["ATO_DTRPITCD"] ==
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                            .val()
                            .trimEnd()
                    );
                });
                if (find.length > 0) {
                    strTorihikiNM = find[0]["ATO_DTRPTBNM"];
                }
                me.TorihikiMst.strTorihikiNM = strTorihikiNM;
                if (!me.fncMibaraiKoumokuCheck()) {
                    return;
                }
            }

            var strMessage = "";

            //名称取得
            var strSyozokuTenpo = res["strSyozokuTenpo"];
            var strKariTenpo = res["strKariTenpo"];
            var strKashiTenpo = res["strKashiTenpo"];

            // 経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
            ) {
                strMessage = "QY010";
            } else {
                if (
                    strKariTenpo == strSyozokuTenpo ||
                    strKashiTenpo == strSyozokuTenpo
                ) {
                    strMessage = "QY010";
                } else {
                    strMessage =
                        "借方にも貸方にも所属部署が含まれておりませんが、このまま登録を行いますか？";
                }
            }

            // 確認メッセージを表示する
            if (me.hidMode == "1") {
                //新規の場合
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    me.cmdEvent_Click("CMDEVENTINSERT");
                };
            } //修正の場合
            else {
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    me.cmdEvent_Click("CMDEVENTUPDATE");
                };
            }

            if (strMessage == "QY010") {
                me.clsComFnc.FncMsgBox(strMessage);
            } else {
                me.clsComFnc.FncMsgBox("QY999", strMessage);
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：登録・削除処理
    // '関 数 名：cmdEvent_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：ＤＢへの追加・修正・削除処理を行う
    // '**********************************************************************
    me.cmdEvent_Click = function (sender) {
        var data = "";

        var fncFukanzenCheck = me.fncFukanzenCheck();

        //新規の証憑登録の場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val().trimEnd() ==
                "" ||
            me.hidUpdDate == ""
        ) {
            me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click1";
            data = {
                lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                    .val()
                    .trimEnd(),
                HONBUFLG:
                    me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                        ? "1"
                        : "0",
                txtZeikm_GK: $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                    .val()
                    .trimEnd()
                    .replace(/,/g, ""),
                lblZeink_GK: $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK")
                    .text()
                    .trimEnd()
                    .replace(/,/g, ""),
                lblSyohizei: $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei")
                    .text()
                    .trimEnd()
                    .replace(/,/g, ""),
                txtTekyo: $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                    .val()
                    .trimEnd(),
                txtLKomokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                    .val()
                    .trimEnd(),
                txtLBusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                    .val()
                    .trimEnd(),
                ddlLSyohizeiKbn: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn"
                ).val(),
                ddlLTorihikiKbn: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn"
                ).val(),
                txtLKouzaKey1: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1")
                    .val()
                    .trimEnd(),
                txtLKouzaKey2: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2")
                    .val()
                    .trimEnd(),
                txtLKouzaKey3: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3")
                    .val()
                    .trimEnd(),
                txtLKouzaKey4: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4")
                    .val()
                    .trimEnd(),
                txtLKouzaKey5: $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5")
                    .val()
                    .trimEnd(),
                txtLHissuTekyo1: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo2: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo3: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo4: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo5: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo6: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo7: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo8: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo9: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9"
                )
                    .val()
                    .trimEnd(),
                txtLHissuTekyo10: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10"
                )
                    .val()
                    .trimEnd(),
                ddlRKamokuCD: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
                ).val(),
                ddlRKomokuCD: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD"
                ).val(),
                txtRbusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                    .val()
                    .trimEnd(),
                ddlRSyohizeiKbn: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn"
                ).val(),
                ddlRTorihikiKbn: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn"
                ).val(),
                txtRKouzaKey1: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1")
                    .val()
                    .trimEnd(),
                txtRKouzaKey2: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2")
                    .val()
                    .trimEnd(),
                txtRKouzaKey3: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3")
                    .val()
                    .trimEnd(),
                txtRKouzaKey4: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4")
                    .val()
                    .trimEnd(),
                txtRKouzaKey5: $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5")
                    .val()
                    .trimEnd(),
                txtRHissuTekyo1: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo2: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo3: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo4: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo5: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo6: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo7: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo8: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo9: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9"
                )
                    .val()
                    .trimEnd(),
                txtRHissuTekyo10: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10"
                )
                    .val()
                    .trimEnd(),
                txtSeikyusyoNO: $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO")
                    .val()
                    .trimEnd(),
                txtTorihikiHasseibi: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
                )
                    .val()
                    .trimEnd()
                    .replace(/\//g, ""),
                txtShiharaisakiCD: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD"
                )
                    .val()
                    .trimEnd(),
                lblShiharaisakiNM: $(
                    ".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM"
                )
                    .val()
                    .trimEnd(),
                txtShiharaisaki: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki"
                )
                    .val()
                    .trimEnd(),
                grpGinko: $(
                    '.HMDPS102ShiharaiDenpyoInput.grpGinko input[name="grpGinko"]:checked'
                ).val(),
                txtSonotaShiten: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten"
                )
                    .val()
                    .trimEnd(),
                txtSonotaGinko: $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko")
                    .val()
                    .trimEnd(),
                grpSyubetu: $(
                    '.HMDPS102ShiharaiDenpyoInput.grpSyubetu input[name="grpSyubetu"]:checked'
                ).val(),
                txtKouzaNO: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO")
                    .val()
                    .trimEnd(),
                txtKouzaNM: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                    .val()
                    .trimEnd(),
                grpJiki: $(
                    '.HMDPS102ShiharaiDenpyoInput.grpJiki input[name="grpJiki"]:checked'
                ).val(),
                pnlShiharaiCDVis: $(
                    ".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD"
                ).is(":hidden")
                    ? "0"
                    : "1",
                txtJikiDate: $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                    .val()
                    .replace(/\//g, ""),
                txtSeikyusyoNOEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO"
                ).is(":disabled")
                    ? "0"
                    : "1",
                txtTorihikiHasseibiEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
                ).is(":disabled")
                    ? "0"
                    : "1",
                radHiroGinkoEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.radHiroGinko"
                ).is(":disabled")
                    ? "0"
                    : "1",
                txtSonotaGinkoEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko"
                ).is(":disabled")
                    ? "0"
                    : "1",
                radSyubetuTouzaEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.radSyubetuTouza"
                ).is(":disabled")
                    ? "0"
                    : "1",
                txtKouzaNOEna: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                txtKouzaNMEna: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                fncFukanzenCheck: fncFukanzenCheck,
                txtSonotaShitenEna: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten"
                ).is(":disabled")
                    ? "0"
                    : "1",
                txtKeiriSyoriDT: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT"
                )
                    .val()
                    .trimEnd()
                    .replace(/\//g, ""),
                //20240418 lqs INS S
                ddlAitesakiKBN: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN"
                ).val(),
                txtOkyakusamaNOTorihikisakiNm: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                )
                    .val()
                    .trimEnd(),
                txtTorokuNoKazeiMenzeiGyosya: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                )
                    .val()
                    .trimEnd(),
                txtJigyosyoMeiTorokuNo: $(
                    ".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo"
                )
                    .val()
                    .trimEnd(),
                ddlTokureiKBN: $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN"
                ).val(),
                //20240418 lqs INS E
            };
        }
        //追加の証憑登録の場合
        else {
            me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click2";
            data = {
                lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                    .val()
                    .trimEnd(),
            };
        }

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];

            //新規の証憑登録の場合
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val().trimEnd() ==
                    "" ||
                me.hidUpdDate == ""
            ) {
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                        .val()
                        .trimEnd() == ""
                ) {
                    //証憑№の取得を行う
                    var strSEQNO = res["strSEQNO"];
                } else {
                    var strSEQNO = $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                        .val()
                        .trimEnd();
                }

                //証憑№を表示する
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(strSEQNO);

                //更新日付を隠し項目にセット
                me.hidUpdDate = "";

                // 貸方科目は初期値に振込を選択する
                // me.subSyokiDataSet(res['RKOUBANTBL']);
                me.btnClear_Click();
                me.afterDeal(sender);
            } else {
                //追加の証憑登録の場合
                var objCDT = res["CheckTbl"];

                var objNDT = res["NewNoTbl"];
                //同時実行のチェックを行う
                if (
                    !me.fncCheckJikkoSeigyo(
                        objCDT,
                        objNDT,
                        $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                            .val()
                            .trimEnd()
                            .substring(15, 17)
                    )
                ) {
                    return;
                }

                me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click3";
                var data1 = {
                    FLG:
                        objCDT[0]["PRINT_OUT_FLG"] == "1" ||
                        objCDT[0]["CSV_OUT_FLG"] == "1"
                            ? "1"
                            : "0",
                    intEdaNo: parseInt(objNDT[0]["EDA_NO"]) + 1, //枝№を取得する
                    PatternIDFLG:
                        me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                            ? "1"
                            : "0",
                    sender: sender.toUpperCase(),
                    hidGyoNO: me.hidGyoNO.trimEnd(),
                    lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                        .val()
                        .trimEnd(),
                    txtZeikm_GK: $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                        .val()
                        .trimEnd()
                        .replace(/,/g, ""),
                    lblZeink_GK: $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK")
                        .text()
                        .trimEnd()
                        .replace(/,/g, ""),
                    lblSyohizei: $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei")
                        .text()
                        .trimEnd()
                        .replace(/,/g, ""),
                    txtTekyo: $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKamokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                        .val()
                        .trimEnd(),
                    txtLKomokuCD: $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                        .val()
                        .trimEnd(),
                    txtLBusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                        .val()
                        .trimEnd(),
                    ddlLSyohizeiKbn: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn"
                    ).val(),
                    ddlLTorihikiKbn: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn"
                    ).val(),
                    txtLKouzaKey1: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1"
                    )
                        .val()
                        .trimEnd(),
                    txtLKouzaKey2: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2"
                    )
                        .val()
                        .trimEnd(),
                    txtLKouzaKey3: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3"
                    )
                        .val()
                        .trimEnd(),
                    txtLKouzaKey4: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4"
                    )
                        .val()
                        .trimEnd(),
                    txtLKouzaKey5: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo1: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo2: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo3: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo4: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo5: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo6: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo7: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo8: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo9: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9"
                    )
                        .val()
                        .trimEnd(),
                    txtLHissuTekyo10: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10"
                    )
                        .val()
                        .trimEnd(),
                    ddlRKamokuCD: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
                    ).val(),
                    ddlRKomokuCD: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD"
                    ).val(),
                    txtRbusyoCD: $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                        .val()
                        .trimEnd(),
                    ddlRSyohizeiKbn: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn"
                    ).val(),
                    ddlRTorihikiKbn: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn"
                    ).val(),
                    txtRKouzaKey1: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1"
                    )
                        .val()
                        .trimEnd(),
                    txtRKouzaKey2: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2"
                    )
                        .val()
                        .trimEnd(),
                    txtRKouzaKey3: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3"
                    )
                        .val()
                        .trimEnd(),
                    txtRKouzaKey4: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4"
                    )
                        .val()
                        .trimEnd(),
                    txtRKouzaKey5: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo1: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo2: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo3: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo4: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo5: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo6: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo7: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo8: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo9: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9"
                    )
                        .val()
                        .trimEnd(),
                    txtRHissuTekyo10: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10"
                    )
                        .val()
                        .trimEnd(),
                    txtSeikyusyoNO: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO"
                    )
                        .val()
                        .trimEnd(),
                    txtTorihikiHasseibi: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
                    )
                        .val()
                        .trimEnd()
                        .replace(/\//g, ""),
                    txtShiharaisakiCD: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD"
                    )
                        .val()
                        .trimEnd(),
                    lblShiharaisakiNM: $(
                        ".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM"
                    )
                        .val()
                        .trimEnd(),
                    txtShiharaisaki: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki"
                    )
                        .val()
                        .trimEnd(),
                    grpGinko: $(
                        '.HMDPS102ShiharaiDenpyoInput.grpGinko input[name="grpGinko"]:checked'
                    ).val(),
                    txtSonotaShiten: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten"
                    )
                        .val()
                        .trimEnd(),
                    txtSonotaGinko: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko"
                    )
                        .val()
                        .trimEnd(),
                    grpSyubetu: $(
                        '.HMDPS102ShiharaiDenpyoInput.grpSyubetu input[name="grpSyubetu"]:checked'
                    ).val(),
                    txtKouzaNO: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO")
                        .val()
                        .trimEnd(),
                    txtKouzaNM: $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                        .val()
                        .trimEnd(),
                    grpJiki: $(
                        '.HMDPS102ShiharaiDenpyoInput.grpJiki input[name="grpJiki"]:checked'
                    ).val(),
                    pnlShiharaiCDVis: $(
                        ".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD"
                    ).is(":hidden")
                        ? "0"
                        : "1",
                    txtJikiDate: $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                        .val()
                        .replace(/\//g, ""),
                    txtSeikyusyoNOEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtTorihikiHasseibiEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    radHiroGinkoEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.radHiroGinko"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtSonotaGinkoEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    radSyubetuTouzaEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.radSyubetuTouza"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtKouzaNOEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtKouzaNO"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtKouzaNMEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtKouzaNM"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    fncFukanzenCheck: fncFukanzenCheck,
                    txtSonotaShitenEna: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtKeiriSyoriDT: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT"
                    )
                        .val()
                        .trimEnd()
                        .replace(/\//g, ""),
                    //20240418 lqs INS S
                    ddlAitesakiKBN: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN"
                    ).val(),
                    txtOkyakusamaNOTorihikisakiNm: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                    )
                        .val()
                        .trimEnd(),
                    txtTorokuNoKazeiMenzeiGyosya: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                    )
                        .val()
                        .trimEnd(),
                    txtJigyosyoMeiTorokuNo: $(
                        ".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo"
                    )
                        .val()
                        .trimEnd(),
                    ddlTokureiKBN: $(
                        ".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN"
                    ).val(),
                    //20240418 lqs INS E
                };

                me.ajax.receive = function (result1) {
                    result1 = eval("(" + result1 + ")");

                    if (!result1["result"]) {
                        me.clsComFnc.FncMsgBox("E9999", result1["error"]);
                        return;
                    }

                    //印刷済みの証憑の場合
                    if (
                        objCDT[0]["PRINT_OUT_FLG"] == "1" ||
                        objCDT[0]["CSV_OUT_FLG"] == "1"
                    ) {
                        //証憑№を表示する
                        $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                                .val()
                                .trimEnd()
                                .substring(0, 15) + result1["data"]["intEdaNo"]
                        );
                        //更新日付を隠し項目にセット
                        me.hidUpdDate = result1["data"]["dtSysdate"];
                    } else {
                        // 更新日付(隠し)クリア
                        me.hidUpdDate = "";
                    }

                    // 貸方科目は初期値に振込を選択する
                    // me.subSyokiDataSet(result1['data']['RKOUBANTBL']);
                    me.btnClear_Click();
                    me.afterDeal(sender);
                };

                me.ajax.send(me.url, data1, 0);
            }
        };

        me.ajax.send(me.url, data, 0);
    };

    me.afterDeal = function (sender) {
        // 後処理
        // 画面項目をクリアし、ボタンの制御を行う
        // me.btnClear_Click();
        me.hidCreateDate = "";
        me.hidShiharaiDate = "";

        switch (sender.toUpperCase()) {
            case "CMDEVENTINSERT":
            case "CMDEVENTUPDATE":
                if (
                    me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                ) {
                    if (me.hidMode == "1") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdPrint_Click();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.No = function () {
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                                ""
                            );
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                                ""
                            );
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY021",
                            "登録処理が完了しました。引き続き印刷処理"
                        );
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdPrint_Click();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.No = function () {
                            me.close1();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            me.close1();
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY021",
                            "登録処理が完了しました。引き続き印刷処理"
                        );
                    }
                } else {
                    if (me.hidMode == "1") {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdPrint_Click();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.No = function () {
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                                ""
                            );
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                                ""
                            );
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY999",
                            "登録処理が完了しました。引き続き印刷処理を行いますか？※印刷後の修正は不可能です。"
                        );
                    } else {
                        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                            me.cmdPrint_Click();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.No = function () {
                            me.close1();
                        };
                        me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                            me.close1();
                        };
                        me.clsComFnc.FncMsgBox(
                            "QY999",
                            "登録処理が完了しました。引き続き印刷処理を行いますか？※印刷後の修正は不可能です。"
                        );
                    }
                }
                break;
            case "CMDEVENTALLDELETE":
                // 全削除ボタンが押下された場合
                me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                    me.close1();
                };
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    me.close1();
                };
                me.clsComFnc.FncMsgBox("I0022");
                break;
        }
    };
    // '**********************************************************************
    // '処 理 名：印刷処理
    // '関 数 名：cmdPrint_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：印刷処理を実行
    // '**********************************************************************
    me.cmdPrint_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "cmdPrint_Click";
        data = {
            lblSyohy_no: $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no")
                .val()
                .trimEnd(),
            CONST_ADMIN_PTN_NO: me.hmdps.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.hmdps.CONST_HONBU_PTN_NO,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];

            if (res["DispModeTbl"].length == 0) {
                //該当データが削除された可能性があります。最新の情報を確認して下さい。"
                me.clsComFnc.FncMsgBox("W0026");
                return;
            }

            if (res["changed"] == 1) {
                //他のユーザーにより更新されています。最新の情報を確認してください。
                me.clsComFnc.FncMsgBox("W0025");
                return;
            }

            if (res["intTaisyo"] < 1) {
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }

            //証憑№をクリアする
            //印刷プレビュー画面の表示
            if (me.hidMode != "1") {
                me.close1();
            }
            var href = res["report"];
            window.open(href);
        };
        me.ajax.send(me.url, data, 0);
    };
    me.fncCheckJikkoSeigyo = function (objCDt, objNDt, strEda_No) {
        //該当データが存在しない場合
        if (objCDt.length == 0) {
            //該当データが削除された可能性があります。最新の情報を確認して下さい。"
            me.clsComFnc.FncMsgBox("W0026");
            return false;
        }
        //削除フラグが立っている場合
        if (objCDt[0]["DEL_FLG"] == "1") {
            //該当データが削除された可能性があります。最新の情報を確認して下さい。"
            me.clsComFnc.FncMsgBox("W0026");
            return false;
        }
        //既に印刷されている場合
        if (
            me.clsComFnc.FncNv(objCDt[0]["PRINT_OUT_FLG"]) == "1" &&
            me.PatternID != me.hmdps.CONST_ADMIN_PTN_NO &&
            me.PatternID != me.hmdps.CONST_HONBU_PTN_NO
        ) {
            //hidmode="8"は削除は可能なため、省く
            if (me.hidMode != "8") {
                //他のユーザによって印刷が行われましたので、登録を行うことは出来ません。"
                me.clsComFnc.FncMsgBox("W0027");
                return false;
            }
        }
        //既にＣＳＶ出力されている場合
        if (me.clsComFnc.FncNv(objCDt[0]["CSV_OUT_FLG"]) == "1") {
            //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID != me.hmdps.CONST_ADMIN_PTN_NO ||
                me.PatternID != me.hmdps.CONST_HONBU_PTN_NO
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
        if (objNDt[0]["EDA_NO"] != strEda_No) {
            //他のユーザーにより更新されています。最新の情報を確認してください。
            me.clsComFnc.FncMsgBox("W0025");
            return false;
        }
        return true;
    };
    me.fncMibaraiKoumokuCheck = function (blnHissuChk) {
        blnHissuChk = blnHissuChk === undefined ? true : blnHissuChk;

        // 支払先コードが未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                .val()
                .trimEnd() == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "支払先コードが未入力です！");
                return false;
            }
        } else {
            // 支払先コードが取引先マスタに存在しない場合、エラー
            if (me.TorihikiMst.strTorihikiNM == null) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支払先コードが取引先マスタに存在しません！"
                );
                return false;
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val(
                    me.TorihikiMst.strTorihikiNM
                );
            }
        }

        // 請求書№が未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").val().trimEnd() ==
            ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "請求書№が未入力です！");
                return false;
            }
        } else {
            // 請求書№の桁数チェック
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO")
                        .val()
                        .trimEnd()
                ) > 20
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "請求書№", "20");
                return false;
            }
        }

        // 取引発生日が未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
                .val()
                .trimEnd() == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "取引発生日が未入力です！");
                return false;
            }
        }

        return true;
    };
    me.fncInputCheck = function (blnHissuChk) {
        blnHissuChk = blnHissuChk === undefined ? true : blnHissuChk;

        // 税込金額が未入力の場合、エラー
        if ($.trim($(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()) == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "税込金額が未入力です！");
                return false;
            }
        } else {
            // 税込金額の桁数チェック
            if (
                me.clsComFnc.GetByteCount(
                    $.trim(
                        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()
                    ).replace(/,/g, "")
                ) > 13
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "税込金額", "13");
                return false;
            }

            // 税込金額に不正な値が入力されている場合、エラー
            if (
                me.isPosNumber(
                    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                        .val()
                        .replace(/,/g, "")
                ) == -1
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E0013", "税込金額");
                return false;
            }

            // 未払費用以外で税込み金額に負数が入力されている場合、エラー
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val() != null &&
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD")
                    .val()
                    .padRight(6)
                    .substring(1) != "21152"
            ) {
                if (
                    $.trim(
                        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()
                    ).replace(/,/g, "") < 0
                ) {
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "税込金額に負数が入力されています！"
                    );
                    return false;
                }
            }
        }

        // 借方科目コードが未入力の場合、エラー
        if (
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val()) == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "借方科目コードが未入力です！");
                return false;
            }
        } else {
            // 借方科目コードがマスタに存在しない場合、エラー
            var KamokuMst = me.KamokuMstBlank;
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                    .val()
                    .trimEnd() != ""
            ) {
                KamokuMst = me.KamokuMstNotBlank;
            }

            var index = KamokuMst.findIndex(function (ele) {
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                        .val()
                        .trimEnd() != ""
                ) {
                    return (
                        ele["KAMOK_CD"] ==
                            $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                                .val()
                                .trimEnd() &&
                        ele["KOUMK_CD"] ==
                            $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                                .val()
                                .trimEnd()
                    );
                } else {
                    return (
                        ele["KAMOK_CD"] ==
                        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                            .val()
                            .trimEnd()
                    );
                }
            });

            if (index == -1) {
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方科目コード・項目コードが科目マスタに存在しません!"
                );
                return false;
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val(
                    KamokuMst[index]["KAMOK_NM"]
                );
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                        .val()
                        .trimEnd() == "43189" &&
                    ($(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                        .val()
                        .trimEnd() == "" ||
                        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD")
                            .val()
                            .trimEnd() == 0)
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方項目コードが未入力です!"
                    );
                    return false;
                }
            }
        }

        // 部署コードが未入力の場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val().trimEnd() == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "借方発生部署が未入力です！");
                return false;
            }
        } else {
            // 借方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                        .val()
                        .trimEnd()
                );
            });
            if (index == -1) {
                $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方発生部署が部署マスタに存在しません！"
                );
                return false;
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM").val(
                    me.allBusyo[index]["BUSYO_NM"]
                );
            }
        }

        // 借方消費税区分が選択されていない場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                "selectedIndex"
            ) <= 0
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方消費税区分が選択されていません！"
                );
                return false;
            }
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").val() != "90"
            ) {
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").prop(
                        "selectedIndex"
                    ) == 0
                ) {
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "借方取引区分が選択されていません！"
                    );
                    return false;
                }
            }
        }

        // 貸方科目コードが未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").prop(
                "selectedIndex"
            ) <= 0
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "貸方科目コードが未入力です！");
                return false;
            }
        }

        // 貸方部署コードが未入力の場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").val().trimEnd() == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "貸方発生部署が未入力です！");
                return false;
            }
        } else {
            // 貸方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                        .val()
                        .trimEnd()
                );
            });
            if (index == -1) {
                $(".HMDPS102ShiharaiDenpyoInput.lblRbusyoNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方発生部署が部署マスタに存在しません！"
                );
                return false;
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.lblRbusyoNM").val(
                    me.allBusyo[index]["BUSYO_NM"]
                );
            }
        }
        // 貸方消費税区分が選択されていない場合
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                "selectedIndex"
            ) <= 0
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方消費税区分が選択されていません！"
                );
                return false;
            }
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val() != "90"
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方消費税区分は対象外でなければなりません。"
                );
                return false;
            }
        }

        // 借方口座キー・必須摘要、貸方口座キー・必須摘要のチェック
        if (!me.fncInputCheckforHitteki()) {
            return false;
        }

        // 支払先が未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD").css("display") ==
            "inline-block"
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                    .val()
                    .trimEnd() == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus =
                        $(
                            ".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD"
                        ).prop("disabled") == false
                            ? $(
                                  ".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD"
                              )
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "支払先コードが未入力です！"
                    );
                    return false;
                }
            }
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                    .val()
                    .trimEnd() == ""
            ) {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                            : "";
                    me.clsComFnc.FncMsgBox("E9999", "支払先が未入力です！");
                    return false;
                }
            } else {
                // 摘要に全角文字以外が入力されている場合、エラー
                if (
                    me.clsComFnc.GetByteCount(
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                            .val()
                            .trimEnd()
                    ) !=
                    $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                        .val()
                        .trimEnd().length *
                        2
                ) {
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "支払先には全角以外の文字を入力することは出来ません！"
                    );
                    return false;
                }
                if (
                    me.clsComFnc.GetByteCount(
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                            .val()
                            .trimEnd()
                    ) > 60
                ) {
                    me.clsComFnc.ObjFocus =
                        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").prop(
                            "disabled"
                        ) == false
                            ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                            : "";
                    me.clsComFnc.FncMsgBox("E0027", "支払先", "60");
                    return false;
                }
            }
        }

        // 取引発生日に日付以外が入力された場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
                .val()
                .trimEnd() != ""
        ) {
            if (
                me.clsComFnc.CheckDate(
                    $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
                ) == false
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "取引発生日に日付以外の値が入力されています。"
                );
                return false;
            }
        }

        // 支払予定日が未入力の場合、エラー
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val().trimEnd() == ""
        ) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "支払予定日が未入力です！");
                return false;
            }
        } else {
            // 時期に日付以外が入力された場合、エラー
            if (
                me.clsComFnc.CheckDate(
                    $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                ) == false
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支払時期に日付以外の値が入力されています。"
                );
                return false;
            }
        }

        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val().trimEnd() != ""
        ) {
            // 摘要に全角文字以外が入力されている場合、エラー
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, "")
                        .replace(/[\r\n]/g, "")
                ) !=
                $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "")
                    .replace(/[\r\n]/g, "").length *
                    2
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "摘要には全角以外の文字を入力することは出来ません！"
                );
                return false;
            }

            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 240
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "摘要", "240");
                return false;
            }
        }

        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").val().trimEnd() != ""
        ) {
            var patt = /^[0-9a-zA-Z!-`ｧ-ｰｱ-ﾟ０-９ァ-ーＡ-Ｚａ-ｚ！-｀]*$/g;
            if (
                !$(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                    .val()
                    .replace(/　/g, "")
                    .replace(/ /g, "")
                    .match(patt)
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "振込先口座名にはカナ・英数字・記号以外の文字を入力することは出来ません！"
                );
                return false;
            }
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").val().trimEnd()
                ) > 60
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "振込先口座名", "60");
                return false;
            }
        }

        // その他銀行名
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val().trimEnd() !=
            ""
        ) {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko")
                        .val()
                        .trimEnd()
                ) > 19
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "その他銀行名", "19");
                return false;
            }
        }
        // その他支店名
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val().trimEnd() !=
            ""
        ) {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten")
                        .val()
                        .trimEnd()
                ) > 15
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "その他支店名", "15");
                return false;
            }
        }
        // 振込先口座№
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val().trimEnd() != ""
        ) {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val().trimEnd()
                ) > 7
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").prop(
                        "disabled"
                    ) == false
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "口座№", "7");
                return false;
            }
        }

        // 20240418 lqs INS S
        if (me.fncInputInvoicesCheck() == false) {
            return false;
        }
        // 20240418 lqs INS E
        return true;
    };
    me.fncInputCheckforHitteki = function () {
        // 借方
        var labelLArr = $(".HMDPS102ShiharaiDenpyoInput.clearLabelL");
        var textLArr = $(".HMDPS102ShiharaiDenpyoInput.clearTextL");
        for (var i = 0; i < labelLArr.length; i++) {
            var label = labelLArr[i];
            // 口座キーの桁数チェック  必須摘要の桁数チェック
            if (label.innerText.trimEnd() != "") {
                if (
                    me.clsComFnc.GetByteCount(textLArr[i].value.trimEnd()) > 20
                ) {
                    var selectClass = textLArr[i].className.replace(/ /g, ".");
                    me.clsComFnc.ObjFocus = $("." + selectClass);
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "借方" + label.innerText,
                        "20"
                    );
                    return false;
                }
            }
        }

        // 貸方
        var labelRArr = $(".HMDPS102ShiharaiDenpyoInput.clearLabelR");
        var textRArr = $(".HMDPS102ShiharaiDenpyoInput.clearTextR");
        for (var i = 0; i < labelRArr.length; i++) {
            var label = labelRArr[i];
            // 口座キーの桁数チェック  必須摘要の桁数チェック
            if (label.innerText.trimEnd() != "") {
                if (
                    me.clsComFnc.GetByteCount(textRArr[i].value.trimEnd()) > 20
                ) {
                    var selectClass = textRArr[i].className.replace(/ /g, ".");
                    me.clsComFnc.ObjFocus = $("." + selectClass);
                    me.clsComFnc.FncMsgBox(
                        "E0027",
                        "貸方" + label.innerText,
                        "20"
                    );
                    return false;
                }
            }
        }
        return true;
    };

    //20240418 lqs INS S
    me.fncInputInvoicesCheck = function () {
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").val().toString() ==
                "" ||
            $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").val().toString() ==
                ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN")
                    .val()
                    .toString() == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN"
                );
            } else {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN"
                );
            }

            me.clsComFnc.FncMsgBox(
                "E9999",
                "相手先区分、特例区分が選択されていません。"
            );
            return false;
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "1"
        ) {
            // '相手先区分＝1：顧客で、選択した顧客のマスターにインボイス登録番号が登録されている
            // 場合、特例区分＝ 1(免税経措あり) を入力している場合にはエラーとなります。
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN")
                    .val()
                    .toString() == "1"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "相手先区分＝1：顧客で、選択した顧客のマスターにインボイス登録番号が登録されている場合、特例区分＝ 1(免税経措あり) を入力している場合にはエラーとなります。"
                );
                return false;
            }
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "2"
        ) {
            // '相手先区分＝2：取引先 ＆ その取引先マスターにインボイス登録番号が入力されて
            // 'いる時に、消費税取引区分＝2：売上としていた場合、エラーとなります。
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo")
                    .val()
                    .trimEnd() != "" &&
                $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn")
                    .val()
                    .toString() == "2"
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn"
                );
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "相手先区分＝2：取引先 ＆ その取引先マスターにインボイス登録番号が入力されている時に、消費税取引区分＝2：売上としていた場合、エラーとなります。"
                );
                return false;
            }
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").val().toString() ==
            "3"
        ) {
            // 事業者名は必須
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya")
                    .val()
                    .trim() == ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                );
                me.clsComFnc.FncMsgBox("E0012", "事業者名");
                return false;
            }
        }
        return true;
    };
    //20240418 lqs INS E

    me.isPosNumber = function (text) {
        if (text == "") {
            return -1;
        } else if ($.trim(text) == "") {
            return 0;
        } else if (text.indexOf(".") >= 0) {
            return -1;
        } else {
            return text;
        }
    };
    // '**********************************************************************
    // '処 理 名：日付変換
    // '関 数 名：txtDateFrom_TextChanged
    // '処理説明："yyyy/MM/dd"形式で表示する
    // '**********************************************************************
    me.txtDateFrom_TextChanged = function (sender) {
        if (sender.val() == "") {
            me.allBtnDisable(false);
            return;
        }
        if (me.clsComFnc.CheckDate(sender) == false) {
            if (
                sender.selector.toString() ==
                ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
            ) {
                sender.val("");
            } else {
                sender.val(me.hidToDay);
            }
            sender.trigger("focus");
            sender.select();
            //Firefox
            window.setTimeout(function () {
                sender.trigger("focus");
                sender.select();
            }, 0);
            if (
                sender.selector.toString() !=
                ".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi"
            ) {
                me.allBtnDisable(true);
            }
        } else {
            me.allBtnDisable(false);
        }
    };
    me.allBtnDisable = function (flg) {
        var status = flg ? "disable" : "enable";
        $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnClear").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnPrint").button(status);
        $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").button(status);
        if (!flg) {
            me.DpyInpNewButtonEnabled(me.hidMode);
        }
    };
    // '**********************************************************************
    // '処 理 名：消費税区分選択時(貸方)
    // '関 数 名：ddlRSyohizeiKbn_SelectedIndexChanged
    // '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
    // '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
    // '　　　　：計算し、表示する
    // '**********************************************************************
    me.ddlRSyohizeiKbn_SelectedIndexChanged = function () {
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
            "disabled",
            false
        );
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                "selectedIndex"
            ) > 0
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val() == "90"
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").get(
                    0
                ).selectedIndex = 0;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").css("display") !=
            "none"
        ) {
            me.txtZeikm_GK_TextChanged();
        }
    };
    // '**********************************************************************
    // '処 理 名：消費税区分選択時(借方)
    // '関 数 名：ddlLSyohizeiKbn_SelectedIndexChanged
    // '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
    // '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
    // '　　　　：計算し、表示する
    // '**********************************************************************
    me.ddlLSyohizeiKbn_SelectedIndexChanged = function () {
        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
            "disabled",
            false
        );
        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                "selectedIndex"
            ) > 0
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").val() == "90"
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").get(
                    0
                ).selectedIndex = 0;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").css("display") !=
            "none"
        ) {
            me.txtZeikm_GK_TextChanged();
        }
    };
    // '**********************************************************************
    // '処 理 名：摘要に半角文字を入力された場合、全角に変換する
    // '関 数 名：txtTekyo_TextChanged
    // '処理説明：摘要に半角文字を入力された場合、全角に変換する
    // '**********************************************************************
    me.txtTekyo_TextChanged = function () {
        $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").val(
            me.hmdps.halfToFull(
                $(".HMDPS102ShiharaiDenpyoInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "")
                    .toZenkaku()
            )
        );
    };
    // '**********************************************************************
    // '処 理 名：支払先に半角文字を入力された場合、全角に変換する
    // '関 数 名：txtShiharaisaki_TextChanged
    // '処理説明：支払先に半角文字を入力された場合、全角に変換する
    // '**********************************************************************
    me.txtShiharaisaki_TextChanged = function () {
        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").val(
            me.hmdps.halfToFull(
                $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki")
                    .val()
                    .trimEnd()
                    .toZenkaku()
            )
        );
    };
    // '**********************************************************************
    // '処 理 名：口座キー変換
    // '関 数 名：txtLKouzaKey_TextChanged
    // '処理説明：英数字が入力された場合は半角の大文字に変換する
    // '**********************************************************************
    me.txtLKouzaKey_TextChanged = function (sender) {
        var patt = /^[0-9a-zA-Z０-９ａ-ｚＡ-Ｚ]*$/g;
        if (sender.val().toString().match(patt)) {
            sender.val(sender.val().toString().toUpperCase().toHankaku());
        }
    };
    // '**********************************************************************
    // '処 理 名：支払先名取得
    // '関 数 名：txtShiharaisakiCD_TextChanged
    // '処理説明：フォーカス移動時に取引先名を取得する
    // '**********************************************************************
    me.txtShiharaisakiCD_TextChanged = function (sender) {
        if ($.trim(sender.val()) != "") {
            var foundNM = "";
            var foundNM_array = me.allTorihikisaki.filter(function (element) {
                return (
                    element["ATO_DTRPITCD"] ==
                    me.clsComFnc.FncNv($.trim(sender.val()))
                );
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0]["ATO_DTRPTBNM"];
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val(foundNM);
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val("");
        }
    };
    // '**********************************************************************
    // '処 理 名：税込金額入力で税抜き金額と消費税額を計算する
    // '関 数 名：txtZeikm_GK_TextChanged
    // '処理説明：税込金額に入力された値で消費税区分より税抜金額と消費税額を計算し、表示する
    // '**********************************************************************
    me.txtZeikm_GK_TextChanged = function () {
        var dealedVal = $.trim(
            $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()
        ).replace(/,/g, "");
        if (dealedVal == "") {
            $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").text("");
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("");
            if (
                $.trim($(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()) !=
                ""
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK"
                );
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val("");
            }
            return;
        }
        if (
            me.isPosNumber(
                $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK")
                    .val()
                    .replace(/,/g, "")
            ) == -1
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").text("");
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("");
            return;
        }
        if (dealedVal == "0") {
            $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").text("0");
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("0");
            return;
        }
        if (dealedVal != "") {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                    "selectedIndex"
                ) == 0
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").text("");
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("");
            } else {
                var ddlLVal = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn"
                ).val();
                var ddlRVal = $(
                    ".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn"
                ).val();
                if (
                    ddlLVal == "04" ||
                    ddlRVal == "04" ||
                    ddlLVal == "05" ||
                    ddlRVal == "05" ||
                    ddlLVal == "06" ||
                    ddlRVal == "06" ||
                    ddlLVal == "07" ||
                    ddlRVal == "07"
                ) {
                    var dblZeink_gk = 0;
                    var dblZeiRt = 0;
                    if (ddlLVal == "04" || ddlRVal == "04") {
                        dblZeiRt = 1.05;
                    } else if (ddlLVal == "05" || ddlRVal == "05") {
                        dblZeiRt = 1.08;
                    } else if (ddlLVal == "06" || ddlRVal == "06") {
                        dblZeiRt = 1.08;
                    } else if (ddlLVal == "07" || ddlRVal == "07") {
                        dblZeiRt = 1.1;
                    }

                    // dblZeink_gk = me.clsComFnc.fncRoundDown(dealedVal/dblZeiRt, 0);
                    dblZeink_gk =
                        dealedVal / dblZeiRt > 0
                            ? Math.floor(dealedVal / dblZeiRt)
                            : Math.ceil(dealedVal / dblZeiRt);

                    while (1 == 1) {
                        // if(dealedVal <= me.clsComFnc.fncRoundDown(dblZeink_gk * dblZeiRt, 0))
                        var tmp =
                            dblZeink_gk * dblZeiRt > 0
                                ? Math.floor(dblZeink_gk * dblZeiRt)
                                : Math.ceil(dblZeink_gk * dblZeiRt);
                        if (dealedVal <= tmp) {
                            break;
                        }
                        dblZeink_gk += 1;
                    }
                    me.toMoney(
                        $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK"),
                        "label",
                        dblZeink_gk
                    );
                    var lblSyohizeiVal = dealedVal - dblZeink_gk;
                    me.toMoney(
                        $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei"),
                        "label",
                        lblSyohizeiVal
                    );
                } else {
                    me.toMoney(
                        $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK"),
                        "label",
                        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val()
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("0");
                }
            }
        }
        // 20240418 lqs UPD S
        // $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").trigger("focus");
        $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").trigger("focus");
        // 20240418 lqs UPD E
    };
    // '**********************************************************************
    // '処 理 名：銀行区分が変更されたとき
    // '関 数 名：radHiroGinko_CheckedChanged
    // '処理説明：銀行区分が変更されたとき
    // '**********************************************************************
    me.radHiroGinko_CheckedChanged = function () {
        if ($(".HMDPS102ShiharaiDenpyoInput.radGinkoSonota").prop("checked")) {
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                "disabled",
                false
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                "disabled",
                false
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("");
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                "disabled",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                "disabled",
                false
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("（GD）");
            } else if (
                $(".HMDPS102ShiharaiDenpyoInput.radMomijiGinko").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("もみじ");
            } else if (
                $(".HMDPS102ShiharaiDenpyoInput.radShinyoKinko").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val(
                    "（GD）信用金庫"
                );
            }
        }
        //振込(又は普通預金)が選択されたときは自動で項目を変更する
        var ddlRKamokuCDVal = $(
            ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
        ).val();
        if (ddlRKamokuCDVal == "211121") {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("31210");
            } else if (
                $(".HMDPS102ShiharaiDenpyoInput.radMomijiGinko").prop("checked")
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("41210");
            }
        }
    };
    // '**********************************************************************
    // '2011/04/27 追加
    // '処 理 名：その他銀行名が変更されたとき
    // '関 数 名：txtSonotaGinko_TextChanged
    // '処理説明：その他銀行名が変更されたとき
    // '**********************************************************************
    me.txtSonotaGinko_LostFocus = function () {
        //振込が選択されたときは自動で項目を変更する
        var ddlRKamokuCDVal = $(
            ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
        ).val();
        if (ddlRKamokuCDVal == "211121") {
            if (
                $.trim(
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val()
                ) == "三井住友"
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("11210");
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("41210");
            }
        }
    };

    me.toMoney = function (obj, objType, val) {
        if (!me.hmdps.KinsokuMojiCheck(obj, me.clsComFnc)) {
            obj.val("");
            return;
        }
        objType = objType === undefined ? "text" : objType;

        val = val === undefined ? obj.val() : val;
        if ($.trim(val) == "") {
            return;
        }
        var txtValue = val.toString().replace(/,/g, "");
        if (txtValue == "" && objType == "text") {
            me.clsComFnc.ObjFocus = obj;
            me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
            obj.val("");
            return;
        }
        //0.11,00.11
        if (/\b(0+\.)/gi.test(txtValue)) {
            txtValue = $.trim(txtValue).replace(/\b(0+\.)/gi, "0.");
        } else {
            txtValue = $.trim(txtValue).replace(/\b(0+)/gi, "");
        }
        var strNewval = txtValue.split(".");

        if (objType == "text") {
            if (strNewval.length > 2) {
                me.clsComFnc.ObjFocus = obj;
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                obj.val("");
                return;
            }
            if (isNaN(txtValue * 1)) {
                me.clsComFnc.ObjFocus = obj;
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                obj.val("");
                return;
            }
            obj.val(
                txtValue == ""
                    ? 0
                    : txtValue
                          .toString()
                          .replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,")
            );
        } else if (objType == "label") {
            obj.text(val.toString().replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,"));
        }
    };
    me.close1 = function () {
        if (me.hidDispNO != "") {
            $(".HMDPS102ShiharaiDenpyoInput.body").dialog("close");
        }
    };
    // '**********************************************************************
    // '処 理 名：パターン対象部署
    // '関 数 名：radPatternBusyo_CheckedChanged
    // '処理説明：選択されたパターン対象部署によって部署コードの活性・不活性を
    // '		：切り替える
    // '**********************************************************************
    me.radPatternBusyo_CheckedChanged = function () {
        if ($(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").prop("checked")) {
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val("");
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").attr(
                "disabled",
                true
            );
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").prop("checked")
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").attr(
                "disabled",
                false
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").trigger("focus");
        }
    };
    me.FormEnabled = function (blnEnabled) {
        $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").attr(
            "disabled",
            true
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").datepicker("disable");
        if (
            blnEnabled &&
            $.trim($(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").val()) !=
                ""
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").attr(
                "disabled",
                false
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").datepicker(
                "enable"
            );
        }
        $(".HMDPS102ShiharaiDenpyoInput.ddlPatternSel").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnCopySyohy").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnLKamokuSearch").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnLBusyoSearch").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.clearTextL").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").attr(
            "disabled",
            !blnEnabled
        );
        if (!blnEnabled) {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                "disabled",
                !blnEnabled
            );
        } else if (
            me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
            me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                "disabled",
                !blnEnabled
            );
        }
        if (!blnEnabled) {
            $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").attr(
                "disabled",
                !blnEnabled
            );
            $(".HMDPS102ShiharaiDenpyoInput.btnRBusyoSearch").attr(
                "disabled",
                !blnEnabled
            );
        }
        $(".HMDPS102ShiharaiDenpyoInput.clearTextR").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").datepicker(
            blnEnabled ? "enable" : "disable"
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnShiharaisakiSearch").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpGinko']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpJiki']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").datepicker(
            blnEnabled ? "enable" : "disable"
        );
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpSyubetu']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnTorihikiSearch").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.btnSyainSearch").attr(
            "disabled",
            !blnEnabled
        );
        //20240418 lqs INS S
        $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").attr(
            "disabled",
            !blnEnabled
        );
        //20240418 lqs INS E
    };
    me.LKoubanNMSet = function (objDt, ValueSet) {
        if (objDt.length > 0) {
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey1NM").text(
                me.clsComFnc.FncNv(objDt[0]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey1NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1").val(
                        me.clsComFnc.FncNv(objDt[0]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey2NM").text(
                me.clsComFnc.FncNv(objDt[1]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey2NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2").val(
                        me.clsComFnc.FncNv(objDt[1]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey3NM").text(
                me.clsComFnc.FncNv(objDt[2]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey3NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3").val(
                        me.clsComFnc.FncNv(objDt[2]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey4NM").text(
                me.clsComFnc.FncNv(objDt[3]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey4NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4").val(
                        me.clsComFnc.FncNv(objDt[3]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey5NM").text(
                me.clsComFnc.FncNv(objDt[4]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey5NM").text() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5").val(
                        me.clsComFnc.FncNv(objDt[4]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo1").text(
                me.clsComFnc.FncNv(objDt[5]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo1")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1").val(
                        me.clsComFnc.FncNv(objDt[5]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo2").text(
                me.clsComFnc.FncNv(objDt[6]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo2")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2").val(
                        me.clsComFnc.FncNv(objDt[6]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo3").text(
                me.clsComFnc.FncNv(objDt[7]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo3")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3").val(
                        me.clsComFnc.FncNv(objDt[7]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo4").text(
                me.clsComFnc.FncNv(objDt[8]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo4")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4").val(
                        me.clsComFnc.FncNv(objDt[8]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo5").text(
                me.clsComFnc.FncNv(objDt[9]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo5").text() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5").val(
                        me.clsComFnc.FncNv(objDt[9]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo6").text(
                me.clsComFnc.FncNv(objDt[10]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo6")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6").val(
                        me.clsComFnc.FncNv(objDt[10]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo7").text(
                me.clsComFnc.FncNv(objDt[11]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo7")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7").val(
                        me.clsComFnc.FncNv(objDt[11]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo8").text(
                me.clsComFnc.FncNv(objDt[12]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo8")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8").val(
                        me.clsComFnc.FncNv(objDt[12]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo9").text(
                me.clsComFnc.FncNv(objDt[13]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo9").text() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9").val(
                        me.clsComFnc.FncNv(objDt[13]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo10").text(
                me.clsComFnc.FncNv(objDt[14]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo10")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10").val(
                        me.clsComFnc.FncNv(objDt[14]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10").attr(
                    "disabled",
                    false
                );
            }
        }
    };
    me.KouzaHittekiEnabledCheck = function () {
        var textHtmlArrL = $(".HMDPS102ShiharaiDenpyoInput.clearTextL");
        for (var i = 0; i < textHtmlArrL.length; i++) {
            var text = textHtmlArrL[i];
            if (text.value != "") {
                text.disabled = false;
            }
        }
        var textHtmlArrR = $(".HMDPS102ShiharaiDenpyoInput.clearTextR");
        for (var i = 0; i < textHtmlArrR.length; i++) {
            var text = textHtmlArrR[i];
            if (text.value != "") {
                text.disabled = false;
            }
        }
    };
    me.DataFormSet = function (objdt, strNo, blnSyohyNo) {
        blnSyohyNo = blnSyohyNo == undefined ? true : blnSyohyNo;
        if (strNo == "100") {
            if (blnSyohyNo) {
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val(
                    me.clsComFnc.FncNv(objdt[0]["SYOHY_NO"]) +
                        me.clsComFnc.FncNv(objdt[0]["EDA_NO"])
                );
            }
            // 隠し項目(行№)にセットする
            me.hidGyoNO = me.clsComFnc.FncNv(objdt[0]["GYO_NO"]);

            me.toMoney(
                $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK"),
                "text",
                me.clsComFnc.FncNv(objdt[0]["ZEIKM_GK"])
            );
            me.toMoney(
                $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK"),
                "label",
                me.clsComFnc.FncNv(objdt[0]["ZEINK_GK"])
            );
            me.toMoney(
                $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei"),
                "label",
                me.clsComFnc.FncNv(objdt[0]["SHZEI_GK"])
            );

            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").val(
                me.clsComFnc.FncNv(objdt[0]["KEIRI_DT"])
            );
        }
        $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").val(
            me.clsComFnc
                .FncNv(objdt[0]["TEKYO"])
                .toString()
                .replace(/〜/g, "～")
        );
        //20240418 lqs INS S
        $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").val(
            me.clsComFnc.FncNv(objdt[0]["AITESAKI_KB"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm").val(
            me.clsComFnc
                .FncNv(objdt[0]["OKYAKU_TORIHIKI_NO"])
                .replace(/〜/g, "～")
        );
        me.ddlAitesakiKBN_SelectedIndexChanged(false);
        $(".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").val(
            me.clsComFnc.FncNv(objdt[0]["JIGYOSYA_NM"]).replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo").val(
            me.clsComFnc.FncNv(objdt[0]["INVOICE_ENTRYNO"]).replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").val(
            me.clsComFnc.FncNv(objdt[0]["TOKUREI_KB"])
        );
        //20240418 lqs INS E
        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_KAMOK_CD"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_KOUMK_CD"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val(
            me.clsComFnc.FncNv(objdt[0]["L_KAMOK_NM"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_HASEI_KYOTN_CD"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM").val(
            me.clsComFnc.FncNv(objdt[0]["L_BUSYO_NM"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_KOUZA_KEY1"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_KOUZA_KEY2"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_KOUZA_KEY3"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_KOUZA_KEY4"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_KOUZA_KEY5"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO1"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO2"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO3"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO4"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO5"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO6"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO7"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO8"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO9"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10").val(
            me.clsComFnc
                .FncNv(objdt[0]["L_HISSU_TEKYO10"])
                .toString()
                .replace(/〜/g, "～")
        );
        if (me.clsComFnc.FncNv(objdt[0]["R_KAMOK_CD"]) != "") {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHR_KAMOK_KB"])
                    .toString()
                    .padRight(3)
                    .substring(0, 1) +
                    me.clsComFnc.FncNv(objdt[0]["R_KAMOK_CD"])
            );
            // Me.ddlRKamokuCD.SelectedIndex = Me.ddlRKamokuCD.SelectedIndex
        }

        // 科目コードセット時の処理
        me.fncRKamokuCDSetProc(true);

        if (me.clsComFnc.FncNv(objdt[0]["R_KOUMK_CD"]) != "") {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val(
                me.clsComFnc.FncNv(objdt[0]["R_KOUMK_CD"])
            );
        }
        $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD").val(
            me.clsComFnc.FncNv(objdt[0]["R_HASEI_KYOTN_CD"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.lblRbusyoNM").val(
            me.clsComFnc.FncNv(objdt[0]["R_BUSYO_NM"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_KOUZA_KEY1"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_KOUZA_KEY2"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_KOUZA_KEY3"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_KOUZA_KEY4"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_KOUZA_KEY5"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO1"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO2"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO3"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO4"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO5"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO6"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO7"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO8"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO9"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10").val(
            me.clsComFnc
                .FncNv(objdt[0]["R_HISSU_TEKYO10"])
                .toString()
                .replace(/〜/g, "～")
        );
        if (me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) != "") {
            if (me.clsComFnc.FncNv(objdt[0]["L_ZEI_RT_KB"]) != "") {
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").val(
                    me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) +
                        me.clsComFnc.FncNv(objdt[0]["L_ZEI_RT_KB"])
                );
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").val(
                    me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) + "0"
                );
            }
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").val("");
        }

        if (me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) == "9") {
            $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                "disabled",
                true
            );
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlLSyohizeiKbn").prop(
                    "disabled"
                ) == false
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").attr(
                    "disabled",
                    false
                );
            }
        }

        if (me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) != "") {
            if (me.clsComFnc.FncNv(objdt[0]["R_ZEI_RT_KB"]) != "") {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val(
                    me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) +
                        me.clsComFnc.FncNv(objdt[0]["R_ZEI_RT_KB"])
                );
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val(
                    me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) + "0"
                );
            }
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val("");
        }

        if (me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) == "9") {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                "disabled",
                true
            );
        } else {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").prop(
                    "disabled"
                ) == false
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
                    "disabled",
                    false
                );
            }
        }

        $(".HMDPS102ShiharaiDenpyoInput.ddlLTorihikiKbn").val(
            me.clsComFnc.FncNv(objdt[0]["L_TORHK_KB"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").val(
            me.clsComFnc.FncNv(objdt[0]["R_TORHK_KB"])
        );

        $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").val(
            me.clsComFnc.FncNv(objdt[0]["SEIKYUSYO_NO"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").val(
            me.clsComFnc.FncNv(objdt[0]["TORIHIKI_DT"])
        );

        if (me.clsComFnc.FncNv(objdt[0]["R_KAMOK_CD"]) == "21152") {
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").val(
                me.clsComFnc.FncNv(objdt[0]["SHIHARAISAKI_CD"])
            );
            $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHIHARAISAKI_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHIHARAISAKI_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        }

        $(".HMDPS102ShiharaiDenpyoInput input[name='grpGinko']").prop(
            "checked",
            false
        );

        if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "1") {
            $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop(
                "checked",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("（GD）");
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "2") {
            $(".HMDPS102ShiharaiDenpyoInput.radMomijiGinko").prop(
                "checked",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("もみじ");
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "3") {
            $(".HMDPS102ShiharaiDenpyoInput.radShinyoKinko").prop(
                "checked",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val(
                "（GD）信用金庫"
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "9") {
            $(".HMDPS102ShiharaiDenpyoInput.radGinkoSonota").prop(
                "checked",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val(
                me.clsComFnc
                    .FncNv(objdt[0]["GINKO_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop(
                "checked",
                true
            );
        }

        switch (me.clsComFnc.FncNv(objdt[0]["JIKI"])) {
            case "1":
                $(".HMDPS102ShiharaiDenpyoInput.radJikiSokujitu").prop(
                    "checked",
                    true
                );
                break;
            case "2":
                $(".HMDPS102ShiharaiDenpyoInput.radJikiHiduke").prop(
                    "checked",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val(
                    me.clsComFnc.FncNv(objdt[0]["SHIHARAI_DT"])
                );
                break;
            case "3":
                $(".HMDPS102ShiharaiDenpyoInput.radJikiYokugetu").prop(
                    "checked",
                    true
                );
                break;
        }
        switch (me.clsComFnc.FncNv(objdt[0]["YOKIN_SYUBETU"])) {
            case "1":
                $(".HMDPS102ShiharaiDenpyoInput.radSyubetuFutu").prop(
                    "checked",
                    true
                );
                break;
            case "2":
                $(".HMDPS102ShiharaiDenpyoInput.radSyubetuTouza").prop(
                    "checked",
                    true
                );
                break;
            case "9":
                $(".HMDPS102ShiharaiDenpyoInput.radSyubetuSonota").prop(
                    "checked",
                    true
                );
                break;
        }
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val(
            me.clsComFnc.FncNv(objdt[0]["KOUZA_NO"])
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").val(
            me.clsComFnc.FncNv(objdt[0]["KOUZA_KN"])
        );

        // 貸方項目で振込(又は普通預金)以外が選択されている場合は、振込先等を不活性に、振込(又は普通預金)が選択されている場合は活性にする
        var ddlRKamokuCDVal = $(
            ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
        ).val();
        if (ddlRKamokuCDVal != "0" && ddlRKamokuCDVal != null) {
            if (ddlRKamokuCDVal == "211121" || ddlRKamokuCDVal == "711122") {
                me.ShiharaiHouhouEnabled(true);

                // 経理課ではなくパターンＩＤが管理者又は本部かで分ける
                // If Session("PatternID") = clsConst.CONST_ADMIN_PTN_NO Or Session("PatternID") = clsConst.CONST_HONBU_PTN_NO Then
                if (
                    me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                        "disabled",
                        false
                    );
                }
                // 銀行名・支店名の活性・不活性の設定
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.radGinkoSonota").prop(
                        "checked"
                    )
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                        "disabled",
                        false
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                        "disabled",
                        false
                    );
                } else {
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                        "disabled",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                        "disabled",
                        false
                    );
                }
            } else if (ddlRKamokuCDVal == "611121") {
                me.ShiharaiHouhou2Enabled(false);
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                    "disabled",
                    false
                );
            } else if (ddlRKamokuCDVal.padRight(6).substring(1) == "21152") {
                me.ShiharaiHouhouEnabled(true);
                // 銀行区分の選択値が変更された場合
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.radGinkoSonota").prop(
                        "checked"
                    )
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                        "disabled",
                        false
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                        "disabled",
                        false
                    );
                } else {
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                        "disabled",
                        true
                    );
                    $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                        "disabled",
                        false
                    );
                }
            } else {
                me.ShiharaiHouhouEnabled(false);
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                    "disabled",
                    true
                );
            }
        }

        if (strNo == "103") {
            if (objdt[0]["TAISYO_BUSYO_KB"] == "1") {
                $(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").prop(
                    "checked",
                    true
                );
            } else {
                $(".HMDPS102ShiharaiDenpyoInput.radPatternBusyo").prop(
                    "checked",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val(
                    me.clsComFnc.FncNv(objdt[0]["TAISYO_BUSYO_CD"])
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM").val(
                me.clsComFnc.FncNv(objdt[0]["PATTERN_NM"])
            );
        }
    };
    me.ShiharaiHouhou2Enabled = function (blnEnabled) {
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpGinko']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpSyubetu']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").attr(
            "disabled",
            !blnEnabled
        );
    };
    me.subSyokiDataSet = function (objds) {
        $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").get(0).selectedIndex = 2;
        // 貸方科目セット時の処理を行う
        me.fncRKamokuCDSetProc();

        me.RKoubanNMSet(objds, true);

        me.RKouzaHittekiNmNothingClear();
        // 貸方消費税区分は対象外を選択
        $(".HMDPS102ShiharaiDenpyoInput.ddlRSyohizeiKbn").val("90");
        $(".HMDPS102ShiharaiDenpyoInput.ddlRTorihikiKbn").attr(
            "disabled",
            true
        );
    };
    me.RKoubanNMSet = function (objDt, ValueSet) {
        if (objDt.length > 0) {
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey1NM").text(
                me.clsComFnc.FncNv(objDt[0]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey1NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1").val(
                        me.clsComFnc.FncNv(objDt[0]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey2NM").text(
                me.clsComFnc.FncNv(objDt[1]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey2NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2").val(
                        me.clsComFnc.FncNv(objDt[1]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey3NM").text(
                me.clsComFnc.FncNv(objDt[2]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey3NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3").val(
                        me.clsComFnc.FncNv(objDt[2]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey4NM").text(
                me.clsComFnc.FncNv(objDt[3]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey4NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4").val(
                        me.clsComFnc.FncNv(objDt[3]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey5NM").text(
                me.clsComFnc.FncNv(objDt[4]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey5NM")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5").val(
                        me.clsComFnc.FncNv(objDt[4]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo1").text(
                me.clsComFnc.FncNv(objDt[5]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo1")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1").val(
                        me.clsComFnc.FncNv(objDt[5]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo2").text(
                me.clsComFnc.FncNv(objDt[6]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo2")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2").val(
                        me.clsComFnc.FncNv(objDt[6]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo3").text(
                me.clsComFnc.FncNv(objDt[7]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo3")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3").val(
                        me.clsComFnc.FncNv(objDt[7]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo4").text(
                me.clsComFnc.FncNv(objDt[8]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo4")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4").val(
                        me.clsComFnc.FncNv(objDt[8]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo5").text(
                me.clsComFnc.FncNv(objDt[9]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo5")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5").val(
                        me.clsComFnc.FncNv(objDt[9]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo6").text(
                me.clsComFnc.FncNv(objDt[10]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo6")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6").val(
                        me.clsComFnc.FncNv(objDt[10]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo7").text(
                me.clsComFnc.FncNv(objDt[11]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo7")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7").val(
                        me.clsComFnc.FncNv(objDt[11]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo8").text(
                me.clsComFnc.FncNv(objDt[12]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo8")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8").val(
                        me.clsComFnc.FncNv(objDt[12]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo9").text(
                me.clsComFnc.FncNv(objDt[13]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo9")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9").val(
                        me.clsComFnc.FncNv(objDt[13]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9").attr(
                    "disabled",
                    false
                );
            }
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo10").text(
                me.clsComFnc.FncNv(objDt[14]["KOBAN_NM"])
            );
            if (
                $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo10")
                    .text()
                    .trimEnd() != ""
            ) {
                if (ValueSet) {
                    $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10").val(
                        me.clsComFnc.FncNv(objDt[14]["VALUE_DATA"])
                    );
                }
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10").attr(
                    "disabled",
                    false
                );
            }
        }
    };
    me.RKouzaHittekiNmNothingClear = function () {
        var labelHtmlArr = $(".HMDPS102ShiharaiDenpyoInput.clearLabelR");
        var textHtmlArr = $(".HMDPS102ShiharaiDenpyoInput.clearTextR");
        for (var i = 0; i < labelHtmlArr.length; i++) {
            var label = labelHtmlArr[i];
            if (label.innerText == "") {
                textHtmlArr[i].value = "";
            }
        }
    };
    me.LKouzaHittekiNmNothingClear = function () {
        var labelHtmlArr = $(".HMDPS102ShiharaiDenpyoInput.clearLabelL");
        var textHtmlArr = $(".HMDPS102ShiharaiDenpyoInput.clearTextL");
        for (var i = 0; i < labelHtmlArr.length; i++) {
            var label = labelHtmlArr[i];
            if (label.innerText == "") {
                textHtmlArr[i].value = "";
            }
        }
    };
    me.subradJikiProc = function (radiochange) {
        radiochange = radiochange == undefined ? false : radiochange;
        var dtToday = "";
        var dtCreateDate = "";
        if (me.hidToDay == "") {
            dtToday = new Date().Format("yyyy/MM/dd");
        } else {
            dtToday = me.hidToDay;
        }
        if (me.hidCreateDate == "") {
            dtCreateDate = dtToday;
        } else {
            dtCreateDate = me.hidCreateDate;
        }

        // 支払予定日がDB登録済なら登録された値を採用する
        if (me.hidShiharaiDate != "") {
            dtCreateDate = me.hidShiharaiDate;
        }
        // 項目の活性・不活性の設定と、日付を表示する
        // 未払の場合だけ設定する日付を変える
        if ($(".HMDPS102ShiharaiDenpyoInput.radJikiHiduke").prop("checked")) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.radJikiHiduke").prop(
                    "disabled"
                ) == false
            ) {
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").datepicker(
                    "enable"
                );
            }

            // 20220121 lqs UPD S
            if (radiochange) {
                // 支払予定日をセットする処理
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val(
                    new Date(dtCreateDate).Format("yyyy/MM/dd")
                );
            }
            // 20220121 lqs UPD E
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.radJikiSokujitu").prop("checked")
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                "disabled",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").datepicker("disable");
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val(
                new Date(dtCreateDate).Format("yyyy/MM/dd")
            );
        } else if (
            $(".HMDPS102ShiharaiDenpyoInput.radJikiYokugetu").prop("checked")
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").attr(
                "disabled",
                true
            );
            $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").datepicker("disable");
            // 支払予定日がDB登録済なら１か月加算しないよう
            if (me.hidShiharaiDate != "") {
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val(
                    new Date(dtCreateDate).Format("yyyy/MM/dd")
                );
            } else {
                var nextMonth = new Date(dtCreateDate).getMonth() + 1;
                var tmpDate = new Date(dtCreateDate).setMonth(nextMonth);
                $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val(
                    new Date(tmpDate).Format("yyyy/MM/20")
                );
            }
        }
    };
    // '**********************************************************************
    // '処 理 名：日付指定を選択された場合、日付を入力可能にする
    // '関 数 名：radJikiHiduke_CheckedChanged
    // '処理説明：日付指定を選択された場合、日付を入力可能にする
    // '**********************************************************************
    me.radJikiHiduke_CheckedChanged = function () {
        me.subradJikiProc(true);
    };
    me.DenpyoInputButtonVisible = function (blnVisible) {
        if (blnVisible) {
            $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnClear").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnPrint").show();

            $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").show();
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnClear").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnPrint").hide();

            $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").hide();
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").css("display") ==
                "none" &&
            $(".HMDPS102ShiharaiDenpyoInput.btnSaishinDisp").css("display") ==
                "none"
        ) {
            $(
                ".HMDPS102ShiharaiDenpyoInput.HMS-button-pane.first-row-div"
            ).hide();
        }
    };
    me.PatternInputButtonVisible = function (blnVisible) {
        if (blnVisible) {
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").show();
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").show();
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnInsert").hide();
            $(".HMDPS102ShiharaiDenpyoInput.btnPtnUpdate").hide();
        }
    };
    me.MemoSet = function (data) {
        var memoStr = "";
        for (var i = 0; i < data.length; i++) {
            var one = data[i];
            memoStr += one["MEISYOU"];
        }

        $(".HMDPS102ShiharaiDenpyoInput.lblMemo").text(memoStr);
    };
    me.fncFukanzenCheck = function () {
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey1NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey1")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey2NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey2")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey3NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey3")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey4NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey4")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLKouzaKey5NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLKouzaKey5")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo1")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo1")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo2")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo2")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo3")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo3")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo4")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo4")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo5")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo5")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo6")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo6")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo7")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo7")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo8")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo8")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo9")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo9")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblLHissuTekyo10")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtLHissuTekyo10")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey1NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey1")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey2NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey2")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey3NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey3")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey4NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey4")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRKouzaKey5NM")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRKouzaKey5")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo1")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo1")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo2")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo2")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo3")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo3")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo4")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo4")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo5")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo5")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo6")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo6")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo7")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo7")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo8")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo8")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo9")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo9")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        if (
            $(".HMDPS102ShiharaiDenpyoInput.lblRHissuTekyo10")
                .text()
                .trimEnd() != ""
        ) {
            if (
                $(".HMDPS102ShiharaiDenpyoInput.txtRHissuTekyo10")
                    .val()
                    .trimEnd() == ""
            ) {
                return 1;
            }
        }
        return 0;
    };
    me.DpyInpNewButtonEnabled = function (intMode) {
        intMode = parseInt(intMode);
        switch (intMode) {
            case 1:
                // 新規画面表示時
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").hide();
                $(".HMDPS102ShiharaiDenpyoInput.btnPtnDelete").attr(
                    "disabled",
                    true
                );
                break;
            case 2:
                // 修正画面表示時
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").hide();
                break;
            case 3:
                // 一覧選択時
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    false
                );
                break;
            case 4:
                // クリア処理
                break;
            case 8:
                // 一部参照モード
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    false
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").show();
                break;
            case 9:
                // 参照モードの場合
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPrint").show();
                break;
            case 99:
                // エラーの場合
                $(".HMDPS102ShiharaiDenpyoInput.btnAllDelete").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnKakutei").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnClear").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnPatternTrk").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.btnSyuseiMaeDisp").attr(
                    "disabled",
                    true
                );
                break;
        }
    };
    me.txtBusyoCD_TextChanged = function (txtBusyoCD, flg) {
        var foundNM = "";
        if ($.trim(txtBusyoCD) != "") {
            var foundNM_array = me.allBusyo.filter(function (element) {
                return element["BUSYO_CD"] == me.clsComFnc.FncNv(txtBusyoCD);
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0]["BUSYO_NM"];
            }
        }
        if (flg == "L") {
            $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM").val(foundNM);
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.lblRbusyoNM").val(foundNM);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面項目をクリアする
	 '関 数 名：subFormClear
	 '引 数 １：blnClear (I) false:初期　true:クリア処理
	 '       ：blnCopySyohyClear  (I) True:コピー元証憑№をクリアする false:クリアしない
	 '       ：blnPatternClear	(I) True:パターン用項目をクリアする   false：パターン用項目をクリアしない
	 '戻 り 値：なし
	 '処理説明：画面項目をクリアする
	 '**********************************************************************
	 */
    me.subFormClear = function (blnClear, blnCopyShohyClear, blnPatternClear) {
        blnClear = blnClear == undefined ? false : blnClear;

        blnCopyShohyClear =
            blnCopyShohyClear == undefined ? true : blnCopyShohyClear;

        blnPatternClear = blnPatternClear == undefined ? true : blnPatternClear;

        if (blnClear == false) {
            $(".HMDPS102ShiharaiDenpyoInput.lblSyohy_no").val("");
        }
        if (blnCopyShohyClear) {
            $(".HMDPS102ShiharaiDenpyoInput.txtCopySyohyNo").val("");
        }
        if (blnPatternClear) {
            $(".HMDPS102ShiharaiDenpyoInput.txtKeiriSyoriDT").val("");
        }
        $(".HMDPS102ShiharaiDenpyoInput.txtZeikm_GK").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblZeink_GK").text("");
        $(".HMDPS102ShiharaiDenpyoInput.lblSyohizei").text("");

        $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM").val("");

        $(".HMDPS102ShiharaiDenpyoInput.clearLabelL").text("");
        $(".HMDPS102ShiharaiDenpyoInput.clearTextL").val("");

        if (
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").prop(
                "selectedIndex"
            ) > -1
        ) {
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD").get(
                0
            ).selectedIndex = 0;
        }

        if (blnClear == true) {
            me.fncRKamokuCDSetProc();
        }

        $(".HMDPS102ShiharaiDenpyoInput.clearLabelR").text("");
        $(".HMDPS102ShiharaiDenpyoInput.clearTextR").val("");

        $(".HMDPS102ShiharaiDenpyoInput.lblKensakuCD").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblKensakuNM").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("（GD）");
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val("");
        $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop("checked", true);
        $(".HMDPS102ShiharaiDenpyoInput.radJikiSokujitu").prop("checked", true);
        $(".HMDPS102ShiharaiDenpyoInput.txtJikiDate").val("");
        $(".HMDPS102ShiharaiDenpyoInput.radSyubetuTouza").prop("checked", true);
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").val("");
        $(".HMDPS102ShiharaiDenpyoInput.radPatternKyotu").prop("checked", true);
        $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").attr(
            "disabled",
            true
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtPatternBusyo").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtPatternNM").val("");
        if (blnClear == false) {
            $(".HMDPS102ShiharaiDenpyoInput.lblMemo").text("");
        }
        // 20240418 lqs INS S
        $(".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtJigyosyoMeiTorokuNo").val("");
        $(".HMDPS102ShiharaiDenpyoInput.lblOkyakuNOTorihikisakiNm").val("");
        $(".HMDPS102ShiharaiDenpyoInput.ddlAitesakiKBN").get(
            0
        ).selectedIndex = 0;
        $(".HMDPS102ShiharaiDenpyoInput.ddlTokureiKBN").get(
            0
        ).selectedIndex = 0;
        // 20240418 lqs INS E
    };
    me.fncRKamokuCDSetProc = function (blnKoumokuSel) {
        blnKoumokuSel = blnKoumokuSel == undefined ? false : blnKoumokuSel;

        // 口座キー、必須摘要を不活性にする
        me.KouzaHiTekkiEnabledSet(false, 2);

        // 項目名をクリアする
        me.RKouzaHittekiClear();

        me.ShiharaiHouhouEnabled(false);

        me.MibaraiNaiyoEnabled(false);

        var ddlRKamokuCDVal = $(
            ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
        ).val();
        if (ddlRKamokuCDVal != null && ddlRKamokuCDVal != "0") {
            if (ddlRKamokuCDVal == "211121" || ddlRKamokuCDVal == "711122") {
                me.ShiharaiHouhouEnabled(true);

                // 経理課ではなくパターンＩＤが管理者又は本部かで分ける
                if (
                    me.PatternID == me.hmdps.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.hmdps.CONST_HONBU_PTN_NO
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                        "disabled",
                        false
                    );
                } else {
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                        "disabled",
                        true
                    );
                }
                // 銀行区分の選択値が変更された場合
                $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop(
                    "checked",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("（GD）");
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val("");
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                    "disabled",
                    false
                );
            } else if (ddlRKamokuCDVal == "611121") {
                me.ShiharaiHouhou2Clear();
                me.ShiharaiHouhou2Enabled(false);
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                    "disabled",
                    false
                );
            } else {
                me.ShiharaiHouhouClear();
                me.ShiharaiHouhouEnabled(false);
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").empty();
                $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").attr(
                    "disabled",
                    true
                );
            }

            // 未払費用
            if (ddlRKamokuCDVal.padRight(6).substring(1) == "21152") {
                me.MibaraiNaiyoClear(1);
                me.MibaraiNaiyoEnabled(true);
                me.ShiharaiHouhouEnabled(true);

                // 銀行区分の選択値が変更された場合
                $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop(
                    "checked",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("（GD）");
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val("");
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
                    "disabled",
                    true
                );
                $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
                    "disabled",
                    false
                );
            } else {
                me.MibaraiNaiyoClear(2);
                me.MibaraiNaiyoEnabled(false);
            }

            // 貸方項目コードにセット
            var strKamokuCD = $(
                ".HMDPS102ShiharaiDenpyoInput.ddlRKamokuCD"
            ).val();
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").empty();
            var selRKomoku = me.RKomoku.filter(function (one) {
                return (
                    one["SUCHI1"] == strKamokuCD.padRight(6).substring(1) &&
                    one["MEISYOUCD"] == strKamokuCD.padRight(6).substring(0, 1)
                );
            });
            for (var index = 0; index < selRKomoku.length; index++) {
                var opt = selRKomoku[index];
                $("<option></option>")
                    .val(opt["SUCHI2"])
                    .text(opt["MOJI1"] == null ? "" : opt["MOJI1"])
                    .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD");
            }

            if (ddlRKamokuCDVal == "211121") {
                if (
                    $(".HMDPS102ShiharaiDenpyoInput.radHiroGinko").prop(
                        "checked"
                    )
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("31210");
                } else if (
                    $(".HMDPS102ShiharaiDenpyoInput.radMomijiGinko").prop(
                        "checked"
                    )
                ) {
                    $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").val("41210");
                }
            }
        } else {
            // 貸方項目コードにセット
            $(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD").empty();
            $("<option></option>")
                .val("1")
                .text("")
                .appendTo(".HMDPS102ShiharaiDenpyoInput.ddlRKomokuCD");
        }
    };

    me.MibaraiNaiyoClear = function (intKbn) {
        if (intKbn == "1") {
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisaki").val("");
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").val("");
            $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").val("");
            $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD").val("");
            $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM").val("");
        }
    };
    me.ShiharaiHouhouClear = function () {
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").val("");
    };
    me.ShiharaiHouhou2Clear = function () {
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").val("");
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").val("");
    };
    me.KouzaHiTekkiEnabledSet = function (blnEnabled, TaisyakuKb) {
        TaisyakuKb = TaisyakuKb == undefined ? 9 : TaisyakuKb;

        if (TaisyakuKb == 1 || TaisyakuKb == 9) {
            $(".HMDPS102ShiharaiDenpyoInput.clearTextL").attr(
                "disabled",
                !blnEnabled
            );
        }
        if (TaisyakuKb == 2 || TaisyakuKb == 9) {
            $(".HMDPS102ShiharaiDenpyoInput.clearTextR").attr(
                "disabled",
                !blnEnabled
            );
        }
    };
    me.RKouzaHittekiClear = function () {
        $(".HMDPS102ShiharaiDenpyoInput.clearLabelR").text("");
    };
    me.LKouzaHittekiClear = function () {
        $(".HMDPS102ShiharaiDenpyoInput.clearLabelL").text("");
    };
    me.ShiharaiHouhouEnabled = function (blnEnabled) {
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpGinko']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaGinko").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtSonotaShiten").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput input[name='grpSyubetu']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNO").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtKouzaNM").attr(
            "disabled",
            !blnEnabled
        );
    };
    me.MibaraiNaiyoEnabled = function (blnEnabled) {
        $(".HMDPS102ShiharaiDenpyoInput.txtSeikyusyoNO").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").attr(
            "disabled",
            !blnEnabled
        );
        $(".HMDPS102ShiharaiDenpyoInput.txtTorihikiHasseibi").datepicker(
            blnEnabled ? "enable" : "disable"
        );
        if (blnEnabled) {
            $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD").show();
            $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiNM").hide();
        } else {
            $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiCD").hide();
            $(".HMDPS102ShiharaiDenpyoInput.pnlShiharaiNM").show();
        }
    };
    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divkuCD = "";
        var divNM = "";
        var frmId = "";
        var title = "";
        var $txtSearchCD = undefined;
        var $txtSearchkuCD = undefined;
        var $txtSearchNM = undefined;
        var cd = "RtnCD";
        // 20240418 lqs INS S
        var $txtOkyakusamaNOTorihikisakiNm = undefined;
        var $lblOkyakuNOTorihikisakiNm = undefined;
        var $txtTorokuNoKazeiMenzeiGyosya = undefined;
        // 20240418 lqs INS E

        switch (searchButton) {
            case "btnLKamokuSearch":
                //科目検索
                dialogId = "HMDPS701KamokuSearchDialogDiv";
                $txtSearchCD = $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD");
                $txtSearchkuCD = $(".HMDPS102ShiharaiDenpyoInput.txtLKomokuCD");
                $txtSearchNM = $(".HMDPS102ShiharaiDenpyoInput.lblLKamokuNM");
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
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtRbusyoCD")
                        : $(".HMDPS102ShiharaiDenpyoInput.txtLBusyoCD");
                $txtSearchNM =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HMDPS102ShiharaiDenpyoInput.lblRbusyoNM")
                        : $(".HMDPS102ShiharaiDenpyoInput.lblLbusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HMDPS702BusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            case "btnShiharaisakiSearch":
            case "btnTorihikiSearch":
                //取引先
                dialogId = "HMDPS700TorihikisakiSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnShiharaisakiSearch"
                        ? $(".HMDPS102ShiharaiDenpyoInput.txtShiharaisakiCD")
                        : $(".HMDPS102ShiharaiDenpyoInput.lblKensakuCD");
                $txtSearchNM =
                    searchButton == "btnShiharaisakiSearch"
                        ? $(".HMDPS102ShiharaiDenpyoInput.lblShiharaisakiNM")
                        : $(".HMDPS102ShiharaiDenpyoInput.lblKensakuNM");
                // 20240418 lqs INS S
                if (searchButton == "btnTorihikiSearch") {
                    $txtOkyakusamaNOTorihikisakiNm = $(
                        ".HMDPS102ShiharaiDenpyoInput.txtOkyakusamaNOTorihikisakiNm"
                    );
                    $lblOkyakuNOTorihikisakiNm = $(
                        ".HMDPS102ShiharaiDenpyoInput.lblOkyakuNOTorihikisakiNm"
                    );
                    $txtTorokuNoKazeiMenzeiGyosya = $(
                        ".HMDPS102ShiharaiDenpyoInput.txtTorokuNoKazeiMenzeiGyosya"
                    );
                }
                // 20240418 lqs INS E
                divCD = "KensakuCD";
                divNM = "KensakuNM";
                frmId = "HMDPS700TorihikisakiSearch";
                title = "取引先マスタ検索";
                break;
            case "btnSyainSearch":
                //社員
                dialogId = "HMDPS703SyainSearchDialogDiv";
                $txtSearchCD = $(".HMDPS102ShiharaiDenpyoInput.lblKensakuCD");
                $txtSearchNM = $(".HMDPS102ShiharaiDenpyoInput.lblKensakuNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HMDPS703SyainSearch";
                title = "社員マスタ検索";
                break;
            default:
        }

        var $rootDiv = $(".HMDPS102ShiharaiDenpyoInput.HMDPS-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        if (searchButton == "btnLKamokuSearch") {
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
        $SearchCD.val($.trim($txtSearchCD.val()));
        if (searchButton == "btnLKamokuSearch") {
            $SearchkuCD = $rootDiv.parent().find("#" + divkuCD);
        }
        $(".HMDPS102ShiharaiDenpyoInput.txtTekyo").trigger("focus");
        var width = me.ratio === 1.5 ? 488 : 500;
        var height = me.ratio === 1.5 ? 558 : 630;
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: width,
            resizable: false,
            close: function () {
                var changeFlag = true;
                if (searchButton == "btnLKamokuSearch") {
                    if (
                        $SearchkuCD.html() != "" &&
                        $SearchCD.html() == $txtSearchCD.val()
                    ) {
                        changeFlag = false;
                    } else {
                        changeFlag = "2";
                    }
                }

                if ($RtnCD.html() == 1) {
                    $txtSearchCD.val($SearchCD.html());
                    $txtSearchNM.val($SearchNM.html());
                    // 20240418 lqs INS S
                    if (searchButton == "btnTorihikiSearch") {
                        $txtOkyakusamaNOTorihikisakiNm.val($SearchCD.html());
                        $lblOkyakuNOTorihikisakiNm.val($SearchNM.html());
                        $txtTorokuNoKazeiMenzeiGyosya.val($SearchNM.html());
                    }
                    // 20240418 lqs INS E
                    if (searchButton == "btnLKamokuSearch") {
                        $txtSearchkuCD.val($SearchkuCD.html());

                        me.txtLKamokuCD_TextChanged(
                            $(".HMDPS102ShiharaiDenpyoInput.txtLKamokuCD"),
                            changeFlag
                        );
                    }
                }

                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();
                if (searchButton == "btnLKamokuSearch") {
                    $SearchkuCD.remove();
                } else {
                    $(".HMDPS102ShiharaiDenpyoInput." + searchButton).trigger(
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
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMDPS_HMDPS102ShiharaiDenpyoInput =
        new HMDPS.HMDPS102ShiharaiDenpyoInput();
    o_HMDPS_HMDPS.HMDPS102ShiharaiDenpyoInput =
        o_HMDPS_HMDPS102ShiharaiDenpyoInput;
    if (o_HMDPS_HMDPS.HMDPS100DenpyoSearch) {
        o_HMDPS_HMDPS.HMDPS100DenpyoSearch.HMDPS102ShiharaiDenpyoInput =
            o_HMDPS_HMDPS102ShiharaiDenpyoInput;
        o_HMDPS_HMDPS102ShiharaiDenpyoInput.HMDPS100DenpyoSearch =
            o_HMDPS_HMDPS.HMDPS100DenpyoSearch;
    }

    if (o_HMDPS_HMDPS.HMDPS103PatternSearch) {
        o_HMDPS_HMDPS.HMDPS103PatternSearch.HMDPS102ShiharaiDenpyoInput =
            o_HMDPS_HMDPS102ShiharaiDenpyoInput;
        o_HMDPS_HMDPS102ShiharaiDenpyoInput.HMDPS103PatternSearch =
            o_HMDPS_HMDPS.HMDPS103PatternSearch;
    }

    if (o_HMDPS_HMDPS.HMDPS105CSVReOut) {
        o_HMDPS_HMDPS.HMDPS105CSVReOut.HMDPS102ShiharaiDenpyoInput =
            o_HMDPS_HMDPS102ShiharaiDenpyoInput;
        o_HMDPS_HMDPS102ShiharaiDenpyoInput.HMDPS105CSVReOut =
            o_HMDPS_HMDPS.HMDPS105CSVReOut;
    }

    o_HMDPS_HMDPS102ShiharaiDenpyoInput.load();
});
