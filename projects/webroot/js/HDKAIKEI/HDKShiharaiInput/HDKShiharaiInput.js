/**
 * 説明：
 *
 *
 * @author GSDL
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug						内容																担当
 * YYYYMMDD						#ID																								XXXXXX																GSDL
 * 20240129						#支払伝票入力				 全確定を実行して 枝番が＋１、元データの作成者をセットする						YIN
 * 20240312						#支払伝票入力				/06.障害一覧/本番障害.xlsxのNO2、NO3										LQS
 * 20240315						#支払伝票入力				/06.障害一覧/本番障害.xlsxのNO4												LQS
 * 20240318						本番障害.xlsx NO5			編集ボタン追加、編集ボタンクリックで伝票入力画面に遷移						lujunxia
 * 20240322						本番障害.xlsx NO8			科目名、補助科目名を両方表示してほしい										YIN
 * 20240408						本番保守.xlsx NO11			貸方科目ブルダウンに 「未払金給与（社員立替）」を追加							LQS
 * 20240507						99.提供資料\FromJP\20240507			20240423_金融機関マスタ追加対応.xlsx								LQS
 * 20240520						修正依頼			受取人名欄を 現在60byte->30byteで編集するよう修正お願いします								YIN
 * 20240606						修正依頼			copy后，选择数据，カレンダーを起動しなくても日付を変更した時点でボタンが押せなくなるようです	LQS
 * 20241125                     【HD用伝票集計システム（HDKAIKEI）】仕様変更要望           伝票検索入力                                  lhb
 * 20250124                   パターン選択から行追加するとフリーズする現象が出ました                                                     yin
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("HDKAIKEI.HDKShiharaiInput");
HDKAIKEI.HDKShiharaiInput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    // ========== 変数 start ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.id = "HDKShiharaiInput";
    me.dialog_id = "";
    me.sys_id = "HDKAIKEI";
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

    me.grid_id = "#HDKShiharaiInput_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/fncSearchSpread";

    me.allBusyo = [];
    me.RKamoku = [];
    me.RKomoku = [];
    me.KamokuMst = [];
    me.Meisyou = [];
    me.Syohizeiritu = [];
    me.allTorihikisaki = [];
    // 20240507 LQS INS S
    me.allBank = [];
    // 20240507 LQS INS E
    // 20240408 LQS INS S
    me.allSyain = [];
    // 20240408 LQS INS E
    me.PATTERN_Data = [];
    me.selectedRow = {};

    //u00A0:不间断空格，结尾处不会换行显示
    //u0020:半角空格
    //u3000:全角空格
    // 20240124 YIN UPD S
    // me.blankReplace = /((\s|\u00A0|\u0020|\u3000)+$)/;
    me.blankReplace = /[\s\u00A0\u0020\u3000]+$/;
    // 20240124 YIN UPD E

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
                    "_btnEdit' class=\"HDKShiharaiInput btnEdit Tab Enter\" tabindex='72' style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>選択</button>";
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HDKShiharaiInput.HDKShiharaiInputButton",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HDKShiharaiInput.Datepicker",
        type: "datepicker",
        handle: "",
    });
    // 20240315 LQS INS S
    me.controls.push({
        id: ".HDKShiharaiInput.Datepicker5",
        type: "datepicker5",
        handle: "",
    });
    // 20240315 LQS INS E

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

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

    $(".HDKShiharaiInput.txtZeikm_GK").on("keydown", function (e) {
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
    $(".HDKShiharaiInput.btnLKamokuSearch").click(function () {
        me.openSearchDialog("btnLKamokuSearch");
    });
    $(".HDKShiharaiInput.btnLBusyoSearch").click(function () {
        me.openSearchDialog("btnLBusyoSearch");
    });
    $(".HDKShiharaiInput.btnRBusyoSearch").click(function () {
        me.openSearchDialog("btnRBusyoSearch");
    });
    $(".HDKShiharaiInput.btnTorihikiSearch").click(function () {
        me.openSearchDialog("btnTorihikiSearch");
    });
    // 20240408 LQS INS S
    $(".HDKShiharaiInput.btnTatekaeSyaSearch").click(function () {
        me.openSearchDialog("btnTatekaeSyaSearch");
    });
    // 20240408 LQS INS E
    // 20240507 LQS INS S
    $(".HDKShiharaiInput.btnBankSearch").click(function () {
        me.openSearchDialog("btnBankSearch");
    });
    // 20240507 LQS INS E
    //添付ファイルクリック
    $(".HDKShiharaiInput.fileDialog").click(function () {
        me.openSearchDialog("HDKAttachment");
    });
    $(".HDKShiharaiInput.btnCopySyohy").click(function () {
        me.btnCopySyohy_Click();
    });
    $(".HDKShiharaiInput.btnSaishinDisp").click(function () {
        me.btnSaishinDisp_Click();
    });
    $(".HDKShiharaiInput.btnSyuseiMaeDisp").click(function () {
        me.btnSyuseiMaeDisp_Click();
    });
    $(".HDKShiharaiInput.radPatternBusyo").change(function () {
        me.radPatternBusyo_CheckedChanged();
    });
    $(".HDKShiharaiInput.radPatternKyotu").change(function () {
        me.radPatternBusyo_CheckedChanged();
    });
    // 全確定ボタン
    $(".HDKShiharaiInput.btnKakutei").click(function () {
        var strMessage = "";
        if (me.hidMode == "9" || me.hidMode == "8") {
            strMessage = "印刷します。よろしいですか？";
        } else {
            if (!me.fncInputNothingCheck()) {
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
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdPrint_Click();
        };
        me.clsComFnc.FncMsgBox("QY999", strMessage);
    });
    // 全削除
    $(".HDKShiharaiInput.btnAllDelete").click(function () {
        me.btnAllDelete_Click();
    });
    $(".HDKShiharaiInput.btnPtnDelete").click(function () {
        me.btnPtnDelete_Click();
    });
    // 行追加
    $(".HDKShiharaiInput.btnAdd").click(function () {
        me.btnAdd_Click("add");
    });
    //行変更
    $(".HDKShiharaiInput.btnUpdate").click(function () {
        me.btnAdd_Click("update");
    });
    //行削除ﾎﾞﾀﾝクリック
    $(".HDKShiharaiInput.btnDelete").click(function () {
        me.btnDelete_Click();
    });
    // クリア
    $(".HDKShiharaiInput.btnClear").click(function () {
        me.btnClear_Click();
    });
    //登録ﾎﾞﾀﾝクリック
    $(".HDKShiharaiInput.btnPtnInsert").click(function () {
        me.btnPtnInsert_Click("btnPtnInsert");
    });
    //更新ﾎﾞﾀﾝクリック
    $(".HDKShiharaiInput.btnPtnUpdate").click(function () {
        me.btnPtnInsert_Click("btnPtnUpdate");
    });
    // 表示されている仕訳をパターンとして登録
    $(".HDKShiharaiInput.btnPatternTrk").click(function () {
        me.btnPatternTrk_Click();
    });
    // 閉じる
    $(".HDKShiharaiInput.btnClose").click(function () {
        me.close1();
    });
    // 発生部署 左
    $(".HDKShiharaiInput.txtLBusyoCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtBusyoCD_TextChanged(
            $(".HDKShiharaiInput.txtLBusyoCD").val(),
            "L"
        );
    });
    // 発生部署 右
    $(".HDKShiharaiInput.txtRBusyoCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtBusyoCD_TextChanged(
            $(".HDKShiharaiInput.txtRBusyoCD").val(),
            "R"
        );
    });
    //税込金額
    $(".HDKShiharaiInput.txtZeikm_GK").change(function () {
        me.hidShiharaiDate = "";
        me.toMoney($(".HDKShiharaiInput.txtZeikm_GK"));
        me.txtZeikm_GK_TextChanged();
    });
    // 摘要
    $(".HDKShiharaiInput.txtTekyo").change(function () {
        me.txtTekyo_TextChanged();
    });
    // パターン選択
    $(".HDKShiharaiInput.ddlPatternSel").change(function () {
        me.hidShiharaiDate = "";
        me.ddlPatternSel_SelectedIndexChanged();
    });
    // 借方科目コード
    $(".HDKShiharaiInput.txtLKamokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtLKamokuCD_TextChanged($(".HDKShiharaiInput.txtLKamokuCD"));
    });
    // 借方補助科目コード
    $(".HDKShiharaiInput.txtLKomokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.txtLKomokuCD_TextChanged($(".HDKShiharaiInput.txtLKomokuCD"));
    });
    // 貸方科目コード
    $(".HDKShiharaiInput.ddlRKamokuCD").change(function () {
        me.hidShiharaiDate = "";
        me.ddlRKamokuCD_SelectedIndexChanged();
    });
    // 貸方補助科目コード
    $(".HDKShiharaiInput.ddlRKomokuCD").change(function () {
        me.hidShiharaiDate = "";
    });
    // 	時期
    $(
        ".HDKShiharaiInput.grpJiki input[type=radio][name=HDKShiharaiInput_grpJiki]"
    ).change(function () {
        me.hidShiharaiDate = "";
        me.radJikiHiduke_CheckedChanged();
    });
    // 	振込先銀行
    $(
        ".HDKShiharaiInput.grpGinko input[type=radio][name=HDKShiharaiInput_grpGinko]"
    ).change(function () {
        me.hidShiharaiDate = "";
        me.radHiroGinko_CheckedChanged();
    });
    $(".HDKShiharaiInput.txtJikiDate").on("blur", function () {
        me.txtDateFrom_TextChanged($(".HDKShiharaiInput.txtJikiDate"));
    });
    $(".HDKShiharaiInput.txtTorihikiHasseibi").on("blur", function () {
        me.txtDateFrom_TextChanged($(".HDKShiharaiInput.txtTorihikiHasseibi"));
    });
    //消費税区分[借方]change
    $(".HDKShiharaiInput.ddlLSyohizeiKbn").change(function () {
        me.hidShiharaiDate = "";
        me.ddlLSyohizeiKbn_SelectedIndexChanged();
    });
    //消費税区分[貸方]change
    $(".HDKShiharaiInput.ddlRSyohizeiKbn").change(function () {
        me.hidShiharaiDate = "";
        me.ddlRSyohizeiKbn_SelectedIndexChanged();
    });
    $(".HDKShiharaiInput.ddlLSyohizeiritu").change(function () {
        me.hidShiharaiDate = "";
        if ($(".HDKShiharaiInput.txtZeikm_GK").css("display") != "none") {
            me.txtZeikm_GK_TextChanged();
        }
    });
    $(".HDKShiharaiInput.ddlRSyohizeiritu").change(function () {
        me.hidShiharaiDate = "";
        if ($(".HDKShiharaiInput.txtZeikm_GK").css("display") != "none") {
            me.txtZeikm_GK_TextChanged();
        }
    });
    // 取引先コード
    $(".HDKShiharaiInput.txtKensakuCD").change(function () {
        me.txtKensakuCD_TextChanged($(".HDKShiharaiInput.txtKensakuCD"));
    });
    // 20240408 LQS INS S
    // 社員
    $(".HDKShiharaiInput.txtTatekaeSyaCD").change(function () {
        me.txtTatekaeSyaCD_TextChanged(
            $(".HDKShiharaiInput.txtTatekaeSyaCD").val()
        );
    });
    // 20240408 LQS INS E

    //ウインドウサイズ変更時にグリッドの大きさも追従
    var ele = document.querySelector(".HDKShiharaiInput.HDKAIKEI-content");
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
        if (
            me.hidDispNO == "100" ||
            me.hidDispNO == "ReOut4OBC" ||
            me.hidDispNO == "ReOut4ZenGin"
        ) {
            me.setTableSize();
        }
        if (me.hidDispNO == "") {
            setTimeout(function () {
                me.setTableSize();
            }, 500);
        }
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

        me.HDKShiharaiInput_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：HDKShiharaiInput_Load
     '引	数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.HDKShiharaiInput_Load = function () {
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
        strPattern_NO = $("#PATTERN_NO").html();
        if (me.clsComFnc.FncNv(strAllSyohy_No) != "") {
            strSyohy_NO = strAllSyohy_No.substring(0, 15);
            strEda_No = strAllSyohy_No.substring(15, 17);
        }
        me.selectedRow = {};

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
            $(".HDKShiharaiInput.body").dialog({
                autoOpen: false,
                width: me.ratio === 1.5 ? 980 : 1150,
                height:
                    strDispNO == "103"
                        ? me.ratio === 1.5
                            ? 380
                            : 450
                        : isIE || isIE11
                        ? me.ratio === 1.5
                            ? 520
                            : 690
                        : me.ratio === 1.5
                        ? 525
                        : 700,
                modal: true,
                title: "支払伝票入力",
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".HDKShiharaiInput.body").remove();
                },
            });
            $(".HDKShiharaiInput.body").dialog("open");
            // メニュー以外から開かれた場合は指定されたモードをセットする
            me.hidMode = strMode == undefined ? "" : strMode;
        }

        // 画面項目をクリアする
        me.subFormClear();
        $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlRSyohizeiritu").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.lblKensu").val("");
        $(".HDKShiharaiInput.lblZeikomiGoukei").val("");
        $(".HDKShiharaiInput.lblSyohizeiGoukei").val("");

        // 支払先名の入力項目を選択する
        $(".HDKShiharaiInput.pnlShiharaiNM").show();
        $(".HDKShiharaiInput.pnlShiharaiCD").hide();
        // 20240408 LQS INS S
        // 社員
        $(".HDKShiharaiInput.tatekae-syain").hide();
        // 20240408 LQS INS E

        //  ボタンを使用不可にする
        me.DpyInpNewButtonEnabled(99);

        var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
        var data = {
            strDispNO: strDispNO,
            strSyohy_NO: strSyohy_NO,
            strMode: strMode,
            strEda_No: strEda_No,
            strPattern_NO: strPattern_NO,
            getMemo:
                me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
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
                case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                    $(".HDKShiharaiInput.pnlTenpo").hide();
                    $(".HDKShiharaiInput.pnlHonbu").show();
                    break;
                default:
                    me.MemoSet(res["MemoTbl"]);
                    $(".HDKShiharaiInput.pnlTenpo").show();
                    $(".HDKShiharaiInput.pnlHonbu").hide();
            }

            // 支払予定日を不活性にする
            $(".HDKShiharaiInput.txtJikiDate").attr("disabled", true);
            $(".HDKShiharaiInput.txtJikiDate").datepicker("disable");
            me.hidToDay = res["Today"];

            me.allBusyo = res["Busyo"];

            //ドロップダウンリストを設定する
            me.RKamoku = res["KamokuTbl"];
            me.RKomoku = res["KomokuTbl"];

            me.KamokuMst = res["KamokuMst"];

            me.Meisyou = res["MeisyouTbl"];
            me.Syohizeiritu = res["syohizeiritu"];

            me.allTorihikisaki = res["Torihiki"];
            // 20240507 LQS INS S
            me.allBank = res["Bank"];
            // 20240507 LQS INS E
            // 20240408 LQS INS S
            me.allSyain = res["Syain"];
            // 20240408 LQS INS E
            me.DropDownListSet();

            //パターンのドロップダウンリストを設定する
            me.PATTERN_Data = res["PatternTbl"];
            me.PatternDDLSet();
            // 隠し項目を初期化
            me.hidCreateDate = "";
            me.hidShiharaiDate = "";
            switch (strDispNO) {
                case "ReOut4OBC":
                case "ReOut4ZenGin":
                case "100":
                    // 伝票検索画面又は全銀協・OBC再出力画面から開かれた場合
                    $(".HDKShiharaiInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    // 経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HDKShiharaiInput.txtKeiriSyoriDT").attr(
                        "disabled",
                        true
                    );
                    $(".HDKShiharaiInput.txtKeiriSyoriDT").datepicker(
                        "disable"
                    );

                    switch (me.PatternID) {
                        case me.HDKAIKEI.CONST_ADMIN_PTN_NO:
                        case me.HDKAIKEI.CONST_HONBU_PTN_NO:
                            $(".HDKShiharaiInput.btnPatternTrk").show();
                            $(".HDKShiharaiInput.btnPatternTrk").attr(
                                "disabled",
                                false
                            );
                            break;
                        default:
                            $(".HDKShiharaiInput.btnPatternTrk").hide();
                    }
                    switch (strMode) {
                        case "1":
                            // 新規作成の場合
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

                            // ボタンの活性・不活性を決める(新規の場合)
                            me.DpyInpNewButtonEnabled(1);
                            // 支払予定日をセットする
                            me.subradJikiProc();
                            // ****貸方科目は初期値に振込を選択する****
                            me.subSyokiDataSet();
                            break;
                        case "2":
                            var data = {
                                lblSyohy_no: strAllSyohy_No,
                            };
                            var complete_fun = function () {
                                // 修正・削除の場合
                                // 該当データが存在しない場合
                                if (res["NewNoTbl"].length == 0) {
                                    // 該当データが削除された可能性があります。最新の情報を確認して下さい。
                                    $(me.grid_id).jqGrid("clearGridData");
                                    me.clsComFnc.FncMsgBox("W0026");
                                    return;
                                }
                                if (
                                    res["NewNoTbl"].length > 0 &&
                                    res["NewNoTbl"][0]["EDA_NO"] != strEda_No
                                ) {
                                    //他のユーザーにより更新されています。最新の情報を確認してください。
                                    $(me.grid_id).jqGrid("clearGridData");
                                    me.clsComFnc.FncMsgBox("W0025");
                                    return;
                                }

                                // 証憑№を表示する
                                $(".HDKShiharaiInput.lblSyohy_no").val(
                                    strAllSyohy_No
                                );

                                //一覧に表示する
                                var IchiranTbl = $(me.grid_id).jqGrid(
                                    "getRowData"
                                );
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
                                me.toMoney(
                                    $(".HDKShiharaiInput.lblKensu"),
                                    "text",
                                    IchiranTbl.length
                                );
                                me.toMoney(
                                    $(".HDKShiharaiInput.lblZeikomiGoukei"),
                                    "text",
                                    lngKingaku
                                );
                                me.toMoney(
                                    $(".HDKShiharaiInput.lblSyohizeiGoukei"),
                                    "text",
                                    lngSyohizei
                                );

                                // 支払予定日をセットする
                                me.subradJikiProc();

                                // 修正前データを取得する
                                objds = res["SyuseiMaeTbl"];
                                if (
                                    me.clsComFnc.FncNv(objds[0]["SYOHY_NO"]) ==
                                    ""
                                ) {
                                    // 修正前データが存在しない場合
                                    // 修正前表示ボタンを不活性にする
                                    $(
                                        ".HDKShiharaiInput.btnSyuseiMaeDisp"
                                    ).attr("disabled", true);
                                } else {
                                    // 修正前データが存在する場合
                                    // 修正前表示ボタンを活性にする
                                    $(
                                        ".HDKShiharaiInput.btnSyuseiMaeDisp"
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
                                    // 既に全銀協・OBC出力されている場合
                                    if (
                                        me.clsComFnc.FncNv(
                                            objds[0]["CSV_OUT_FLG"]
                                        ) == "1" ||
                                        me.clsComFnc.FncNv(
                                            objds[0]["XLSX_OUT_FLG"]
                                        ) == "1" ||
                                        (me.clsComFnc.FncNv(
                                            objds[0]["HONBU_SYORIZUMI_FLG"] ==
                                                "1"
                                        ) &&
                                            me.PatternID !=
                                                me.HDKAIKEI
                                                    .CONST_ADMIN_PTN_NO &&
                                            me.PatternID !=
                                                me.HDKAIKEI.CONST_HONBU_PTN_NO)
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
                                            me.HDKAIKEI.CONST_ADMIN_PTN_NO &&
                                        me.PatternID !=
                                            me.HDKAIKEI.CONST_HONBU_PTN_NO
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
                                            me.HDKAIKEI.CONST_ADMIN_PTN_NO &&
                                        me.PatternID !=
                                            me.HDKAIKEI.CONST_HONBU_PTN_NO
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
                                me.subSyokiDataSet();
                                //99行を超える場合は行追加ボタンを不活性に設定する
                                // 20241125 lhb upd s
                                // if (IchiranTbl.length >= 10) {
                                if (IchiranTbl.length >= 99) {
                                    // 20241125 lhb upd e
                                    $(".HDKShiharaiInput.btnAdd").button(
                                        "disable"
                                    );
                                }

                                if (
                                    !$(".HDKShiharaiInput.txtZeikm_GK").is(
                                        ":disabled"
                                    )
                                ) {
                                    $(".HDKShiharaiInput.txtZeikm_GK").trigger(
                                        "focus"
                                    );
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
                    }
                    break;
                case "103":
                    // パターン検索画面から表示された場合
                    $(".HDKShiharaiInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(false);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(true);
                    // 経理処理日を非表示にする
                    $(".HDKShiharaiInput.txtKeiriSyoriDT").hide();
                    // 支払伝票入力用項目を非表示にする
                    me.ForPatternVisible();
                    $(".HDKShiharaiInput.btnPtnDelete").attr("disabled", true);
                    $(".HDKShiharaiInput.btnPtnInsert").attr("disabled", true);
                    $(".HDKShiharaiInput.btnPtnUpdate").attr("disabled", true);
                    switch (strMode) {
                        case "1":
                            // 新規の場合
                            $(".HDKShiharaiInput.btnPtnDelete").attr(
                                "disabled",
                                true
                            );
                            $(".HDKShiharaiInput.btnPtnInsert").attr(
                                "disabled",
                                false
                            );
                            $(".HDKShiharaiInput.btnPtnInsert").text("登録");
                            $(".HDKShiharaiInput.btnPtnUpdate").hide();

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

                            // 支払予定日をセットする
                            me.subradJikiProc();

                            // ボタンを活性にする
                            $(".HDKShiharaiInput.btnPtnDelete").attr(
                                "disabled",
                                false
                            );
                            $(".HDKShiharaiInput.btnPtnInsert").attr(
                                "disabled",
                                false
                            );
                            $(".HDKShiharaiInput.btnPtnInsert").text(
                                "新規登録"
                            );
                            $(".HDKShiharaiInput.btnPtnUpdate").show();
                            $(".HDKShiharaiInput.btnPtnUpdate").attr(
                                "disabled",
                                false
                            );

                            break;
                    }

                    me.radPatternBusyo_CheckedChanged();
                    $(".HDKShiharaiInput.txtTekyo").trigger("focus");
                    break;
                default:
                    // それ以外から表示された場合
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
                    $(".HDKShiharaiInput.btnSaishinDisp").hide();
                    // 伝票入力画面用ボタンを表示する
                    me.DenpyoInputButtonVisible(true);
                    // パターン登録用ボタンを表示する
                    me.PatternInputButtonVisible(false);
                    // ボタンの活性・不活性を決める(新規の場合)
                    me.DpyInpNewButtonEnabled(1);

                    // ****貸方科目は初期値に振込を選択する
                    me.subSyokiDataSet();

                    if (
                        me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                    ) {
                        $(".HDKShiharaiInput.btnPatternTrk").show();
                        $(".HDKShiharaiInput.btnPatternTrk").attr(
                            "disabled",
                            false
                        );
                    } else {
                        $(".HDKShiharaiInput.btnPatternTrk").hide();
                    }

                    // 経理処理日を不活性にする(バーコード読取された時点で登録されるため)
                    $(".HDKShiharaiInput.txtKeiriSyoriDT").attr(
                        "disabled",
                        true
                    );
                    $(".HDKShiharaiInput.txtKeiriSyoriDT").datepicker(
                        "disable"
                    );

                    // 閉じるボタンを非表示にする
                    $(".HDKShiharaiInput.btnClose").hide();

                    // 支払予定日をセットする
                    me.subradJikiProc();
            }

            // 20240507 LQS INS S
            me.setBankSearchBtn();
            // 20240507 LQS INS E

            if ($(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false) {
                $(".HDKShiharaiInput.txtZeikm_GK").trigger("focus");
            }

            $(".HDKShiharaiInput.txtKeiriSyoriDT").attr("disabled", true);
            $(".HDKShiharaiInput.txtKeiriSyoriDT").datepicker("disable");
            //[件]レイアウト設定
            var width =
                $("#HDKShiharaiInput_sprList_R_KAMOKU").width() +
                $("#HDKShiharaiInput_sprList_SEQNO").width() -
                82;
            $(".HDKShiharaiInput#GOUKEITBL").css("margin-left", width + "px");
            $(".HDKShiharaiInput.lblKensu").width(
                $("#HDKShiharaiInput_sprList_R_KAMOKU").width() / 2
            );
            $(".HDKShiharaiInput.lblZeikomiGoukei").width(
                $("#HDKShiharaiInput_sprList_ZEIKM_GK").width() - 3
            );
            $(".HDKShiharaiInput.lblSyohizeiGoukei").width(
                $("#HDKShiharaiInput_sprList_SHZEI_GK").width() - 3
            );
            $(".HDKShiharaiInput.lblZeikomiGoukei").css(
                "margin-left",
                $("#HDKShiharaiInput_sprList_R_KAMOKU").width() / 2 - 30 + "px"
            );
        };
        me.ajax.send(url, data, 0);
    };

    //ウインドウサイズ変更時にグリッドの大きさも追従
    me.setTableSize = function () {
        // jqgrid minheight:3行 78px
        var pageWidth = 0;
        var pageHeight = 0;
        var gridHeight = 0;
        if (me.hidDispNO == "") {
            pageWidth = $(".HDKShiharaiInput.HDKAIKEI-content").width();
            pageHeight = $(".HDKAIKEI.HDKAIKEI-layout-center").height();
            gridHeight = pageHeight - 468;
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                pageWidth - 30 > (me.ratio === 1.5 ? 903 : 1060)
                    ? me.ratio === 1.5
                        ? 903
                        : 1060
                    : pageWidth - 30
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                gridHeight > 265
                    ? 265
                    : gridHeight < 78
                    ? me.ratio === 1.5
                        ? 44
                        : 78
                    : gridHeight
            );
        } else if (
            me.hidDispNO == "100" ||
            me.hidDispNO == "ReOut4OBC" ||
            me.hidDispNO == "ReOut4ZenGin"
        ) {
            pageWidth = $(".HDKShiharaiInput .HDKAIKEI-content").width();
            pageHeight = $(
                ".HDKShiharaiInput.body.ui-dialog-content.ui-widget-content"
            ).height();
            gridHeight = pageHeight - 493;
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id,
                gridHeight < 78 ? 78 : gridHeight
            );
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                pageWidth - (me.ratio === 1.5 ? 33 : 45)
            );
        }
    };

    me.btnDelete_Click = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdEvent_Click("cmdEventDelete");
        };
        me.clsComFnc.FncMsgBox("QY017");
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
                $(".HDKShiharaiInput.txtZeikm_GK")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtLKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtLKomokuCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtLBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.ddlLSyohizeiKbn").prop("selectedIndex") > 0
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.ddlLSyohizeiritu").prop("selectedIndex") >
                0
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.ddlRKamokuCD")
                    .val()
                    .replace(me.blankReplace, "") != "0"
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtRBusyoCD")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                return false;
            }
            if (
                $(".HDKShiharaiInput.txtKensakuCD")
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
            me.selectedRow = rowdata;
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
                // 画面項目をクリアする
                me.subFormClear(false, false);
                $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
                $(".HDKShiharaiInput.ddlLSyohizeiritu").get(
                    0
                ).selectedIndex = 0;
                $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
                $(".HDKShiharaiInput.ddlRSyohizeiritu").get(
                    0
                ).selectedIndex = 0;

                // 選択された仕訳データを画面項目にセットする
                me.DataFormSet(result["data"]["NewNoTbl"], "100");

                // 作成日を隠し項目にセット
                me.hidCreateDate = me.clsComFnc.FncNv(
                    result["data"]["NewNoTbl"][0]["CREATE_DATE"]
                );

                // 隠し項目・支払予定日にDBの値をセット
                me.hidShiharaiDate = me.clsComFnc.FncNv(
                    result["data"]["NewNoTbl"][0]["SHIHARAI_DT"]
                );

                // 支払予定日をセットする
                me.subradJikiProc();

                //ボタンの活性・不活性を設定する
                switch (me.hidMode) {
                    case "1":
                    case "2":
                        //選択ボタン押下時のボタン設定
                        me.DpyInpNewButtonEnabled("3");
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
                    $(".HDKShiharaiInput.btnSaishinDisp").css("display") ==
                    "block"
                ) {
                    me.FormEnabled(false);
                    //参照モードのボタン設定
                    me.DpyInpNewButtonEnabled("9");
                    $(".HDKShiharaiInput.btnKakutei").button("disable");
                    $(".HDKShiharaiInput.btnPatternTrk").button("enable");
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };

    // '**********************************************************************
    // '処 理 名：貸方科目コードが変更された時
    // '関 数 名：ddlRKamokuCD_SelectedIndexChanged
    // '処理説明：貸方項目コードを活性にする
    // '**********************************************************************
    me.ddlRKamokuCD_SelectedIndexChanged = function () {
        //'貸方科目セット時の処理を行う
        me.fncRKamokuCDSetProc();
    };

    me.ForPatternVisible = function () {
        $(".HDKShiharaiInput.ddlPatternSel").hide();
        $(".HDKShiharaiInput.txtZeikm_GK").hide();
        $(".HDKShiharaiInput.lblZeink_GK").hide();
        $(".HDKShiharaiInput.lblSyohizei").hide();
        $(".HDKShiharaiInput.CopyMotoRow").hide();
        $(".HDKShiharaiInput.lblSyohy_no").hide();
        $(".HDKShiharaiInput.txtKeiriSyoriDT").hide();
        $(".HDKShiharaiInput.txtKeiriSyoriDT-dateDiv").hide();
        $(".HDKShiharaiInput.KeyTableRow").hide();
        $(".HDKShiharaiInput.KingakuRow").hide();
        $(".HDKShiharaiInput.HDKShiharaiInput_sprList").hide();
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
            lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd(),
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
            $(".HDKShiharaiInput.lblSyohy_no").val(
                me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) +
                    me.clsComFnc.FncNv(objDs[0]["EDA_NO"])
            );
            $(".HDKShiharaiInput.btnSaishinDisp").show();
            //画面項目をクリアする
            me.subFormClear(true);
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlRSyohizeiritu").get(0).selectedIndex = 0;

            //*****参照モードで表示する*****
            //ボタンを使用不可にする
            me.DpyInpNewButtonEnabled(9);
            $(".HDKShiharaiInput.btnKakutei").button("disable");

            //画面項目を不活性にする
            me.FormEnabled(false);

            //一覧に表示する
            var data = {
                lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no")
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
                me.toMoney(
                    $(".HDKShiharaiInput.lblKensu"),
                    "text",
                    IchiranTbl.length
                );
                me.toMoney(
                    $(".HDKShiharaiInput.lblZeikomiGoukei"),
                    "text",
                    lngKingaku
                );
                me.toMoney(
                    $(".HDKShiharaiInput.lblSyohizeiGoukei"),
                    "text",
                    lngSyohizei
                );
            };
            gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);

            //修正前データを取得する
            objDs = res["SyuseiMaeTbl"];
            if (me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) == "") {
                // 修正前データが存在しない場合
                // 修正前表示ボタンを不活性にする
                $(".HDKShiharaiInput.btnSyuseiMaeDisp").attr("disabled", true);
            } else {
                // 修正前データが存在する場合
                // 修正前表示ボタンを活性にする
                $(".HDKShiharaiInput.btnSyuseiMaeDisp").attr("disabled", false);
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
            lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd(),
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

            $(".HDKShiharaiInput.lblSyohy_no").val(
                $(".HDKShiharaiInput.lblSyohy_no")
                    .val()
                    .trimEnd()
                    .toString()
                    .substring(0, 15) + res["NEWTBL"][0]["EDA_NO"]
            );

            // 画面項目をクリアする
            me.subFormClear(true);
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlRSyohizeiritu").get(0).selectedIndex = 0;
            me.FormEnabled(true);

            // 貸方科目は初期値に振込を選択する
            me.subSyokiDataSet();

            // 支払予定日をセットする
            me.subradJikiProc();

            if (me.hidMode == "9" || me.hidMode == "8") {
                me.FormEnabled(false);
            }

            //一覧に表示する
            var data = {
                lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no")
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
                me.toMoney(
                    $(".HDKShiharaiInput.lblKensu"),
                    "text",
                    IchiranTbl.length
                );
                me.toMoney(
                    $(".HDKShiharaiInput.lblZeikomiGoukei"),
                    "text",
                    lngKingaku
                );
                me.toMoney(
                    $(".HDKShiharaiInput.lblSyohizeiGoukei"),
                    "text",
                    lngSyohizei
                );

                //修正前データを取得する
                var objDs = res["SyuseiMaeTbl"];
                if (me.clsComFnc.FncNv(objDs[0]["SYOHY_NO"]) == "") {
                    // 修正前データが存在しない場合
                    // 修正前表示ボタンを不活性にする
                    $(".HDKShiharaiInput.btnSyuseiMaeDisp").attr(
                        "disabled",
                        true
                    );
                } else {
                    // 修正前データが存在する場合
                    // 修正前表示ボタンを活性にする
                    $(".HDKShiharaiInput.btnSyuseiMaeDisp").attr(
                        "disabled",
                        false
                    );
                }

                // ボタンを使用可にする
                me.DpyInpNewButtonEnabled(me.hidMode);

                //明細が99行以上ある場合は、追加ボタンを不活性にする
                // 20241125 lhb upd s
                // if (IchiranTbl.length >= 10) {
                if (IchiranTbl.length >= 99) {
                    // 20241125 lhb upd e
                    $(".HDKShiharaiInput.btnAdd").button("disable");
                }
                if (IchiranTbl.length == 0) {
                    $(".HDKShiharaiInput.btnAllDelete").button("disable");
                    $(".HDKShiharaiInput.btnKakutei").button("disable");
                } else {
                    $(".HDKShiharaiInput.btnKakutei").button("enable");
                    $(".HDKShiharaiInput.btnAllDelete").button("enable");
                }

                $(".HDKShiharaiInput.btnSaishinDisp").hide();
            };
            gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
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
        if ($(".HDKShiharaiInput.txtCopySyohyNo").val().trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtCopySyohyNo");
            me.clsComFnc.FncMsgBox("E0012", "コピー元証憑№");
            return;
        }
        if (
            me.clsComFnc.FncSprCheck(
                $(".HDKShiharaiInput.txtCopySyohyNo").val(),
                0,
                0,
                17
            ) < 0
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtCopySyohyNo");
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else if (
            $(".HDKShiharaiInput.txtCopySyohyNo").val().trimEnd().length != 17
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtCopySyohyNo");
            me.clsComFnc.FncMsgBox("W0024");
            return;
        } else {
            if (
                $(".HDKShiharaiInput.txtCopySyohyNo").val().substring(0, 1) ==
                "1"
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtCopySyohyNo");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "仕訳伝票の仕訳をコピーすることは出来ません。支払伝票の仕訳のみコピー可能です！"
                );
                return;
            }
        }
        me.url = me.sys_id + "/" + me.id + "/" + "btnCopySyohy_Click";
        var data = {
            txtCopySyohyNo: $(".HDKShiharaiInput.txtCopySyohyNo")
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
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtCopySyohyNo");
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            $(".HDKShiharaiInput.lblSyohy_no").val("");
            jqgridDataShow(false);
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
        if (me.hidDispNO == "ReOut4OBC" || me.hidDispNO == "ReOut4ZenGin") {
            //確認メッセージを表示する
            me.clsComFnc.FncMsgBox(
                "QY999",
                "該当証憑№のデータを全て削除します。よろしいですか？<br/>※全銀協・OBC出力対象から外したいだけの場合は出力画面の対象欄からチェックを外して下さい。<br/>削除した場合は該当証憑№のデータは全て失われます。"
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
            $(".HDKShiharaiInput.radPatternBusyo").is(":checked") &&
            $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternBusyo");
            me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
            return;
        } else if (
            $(".HDKShiharaiInput.radPatternBusyo").is(":checked") &&
            $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd() != ""
        ) {
            //対象部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd()
                );
            });

            if (index == -1) {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternBusyo");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "対象部署コードが部署マスタに存在しません！"
                );
                return;
            }
        }

        if (
            $(".HDKShiharaiInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, "") == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternNM");
            me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
            return;
        } else {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKShiharaiInput.txtPatternNM")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 40
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternNM");
                me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                return;
            }
        }
        //入力チェックを行う(必須チェックは行わない)
        if (!me.fncInputCheck(false)) {
            return;
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
        if ($(".HDKShiharaiInput.ddlPatternSel").prop("selectedIndex") == 0) {
            return;
        }

        // 画面項目をクリアする
        //20240312 LQS UPD S
        // me.subFormClear();
        me.subFormClear(false, true, true, true);
        //20240312 LQS UPD E

        // 時期に関する設定を行う
        me.subradJikiProc();

        me.hidCreateDate = "";

        me.url =
            me.sys_id +
            "/" +
            me.id +
            "/" +
            "ddlPatternSel_SelectedIndexChanged";
        var data = {
            ddlPatternSel: $(".HDKShiharaiInput.ddlPatternSel").val(),
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

            if ($(".HDKShiharaiInput.radJikiHiduke").prop("checked")) {
                $(".HDKShiharaiInput.txtJikiDate").attr("disabled", false);
            } else {
                $(".HDKShiharaiInput.txtJikiDate").attr("disabled", true);
            }

            // 隠し項目・支払予定日を初期化
            me.hidShiharaiDate = "";

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
        if ($(".HDKShiharaiInput.btnSaishinDisp").css("display") == "none") {
            $(".HDKShiharaiInput.txtLKamokuCD").val(
                $.trim($(".HDKShiharaiInput.txtLKamokuCD").val())
            );
            $(".HDKShiharaiInput.txtLKomokuCD").val(
                $.trim($(".HDKShiharaiInput.txtLKomokuCD").val())
            );

            // me.txtLkamokuCDKoumokuSet(
            //     $(".HDKShiharaiInput.txtLKamokuCD"),
            //     false
            // );

            me.txtBusyoCD_TextChanged(
                $(".HDKShiharaiInput.txtLBusyoCD").val(),
                "L"
            );

            me.txtZeikm_GK_TextChanged();
            me.txtTekyo_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();
            me.ddlLSyohizeiKbn_SelectedIndexChanged();
            //20240312 LQS UPD S
            // me.radJikiHiduke_CheckedChanged();
            me.radJikiHiduke_CheckedChanged(false);
            //20240312 LQS UPD E
        }

        // 一部参照モード
        if (me.hidMode == "9" || me.hidMode == "8") {
            me.FormEnabled(false);
        }

        // 入力チェックを行う
        if (
            $(".HDKShiharaiInput.radPatternBusyo").is(":checked") &&
            $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternBusyo");
            me.clsComFnc.FncMsgBox("E9999", "対象部署コードが未入力です！");
            return;
        } else if (
            $(".HDKShiharaiInput.radPatternBusyo").is(":checked") &&
            $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd() != ""
        ) {
            //借方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HDKShiharaiInput.txtPatternBusyo").val().trimEnd()
                );
            });

            if (index == -1) {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternBusyo");
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "対象部署コードが部署マスタに存在しません！"
                );
                return;
            }
        }
        if (
            $(".HDKShiharaiInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, "") == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternNM");
            me.clsComFnc.FncMsgBox("E9999", "パターン名が未入力です！");
            return;
        } else {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKShiharaiInput.txtPatternNM")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 40
            ) {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtPatternNM");
                me.clsComFnc.FncMsgBox("E0027", "パターン名", 40);
                return;
            }
        }

        //入力チェックを行う(必須チェックは行わない)
        if (me.fncInputCheck(false) == false) {
            return;
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
        var data = {
            hidPatternNO: me.clsComFnc.FncNv(me.hidPatternNO),
            txtZeikm_GK: $(".HDKShiharaiInput.txtZeikm_GK")
                .val()
                .trimEnd()
                .replace(/,/g, ""),
            lblZeink_GK: $(".HDKShiharaiInput.lblZeink_GK")
                .text()
                .trimEnd()
                .replace(/,/g, ""),
            lblSyohizei: $(".HDKShiharaiInput.lblSyohizei")
                .text()
                .trimEnd()
                .replace(/,/g, ""),
            txtTekyo: $(".HDKShiharaiInput.txtTekyo")
                .val()
                .replace(me.blankReplace, ""),
            txtLKamokuCD: $(".HDKShiharaiInput.txtLKamokuCD").val().trimEnd(),
            txtLKomokuCD: $(".HDKShiharaiInput.txtLKomokuCD").val().trimEnd(),
            txtLBusyoCD: $(".HDKShiharaiInput.txtLBusyoCD").val().trimEnd(),
            ddlLSyohizeiKbn: $(".HDKShiharaiInput.ddlLSyohizeiKbn").val(),
            ddlLSyohizeiritu: $(".HDKShiharaiInput.ddlLSyohizeiritu").val(),
            ddlRKamokuCD: $(".HDKShiharaiInput.ddlRKamokuCD").val(),
            // 20240408 LQS INS S
            syainCD:
                $(".HDKShiharaiInput.ddlRKamokuCD").val() ==
                me.HDKAIKEI.TATEKAE_KAMOKU_CD
                    ? $(".HDKShiharaiInput.txtTatekaeSyaCD").val()
                    : "",
            // 20240408 LQS INS E
            ddlRKomokuCD: $(".HDKShiharaiInput.ddlRKomokuCD").val(),
            txtRBusyoCD: $(".HDKShiharaiInput.txtRBusyoCD").val().trimEnd(),
            ddlRSyohizeiKbn: $(".HDKShiharaiInput.ddlRSyohizeiKbn").val(),
            ddlRSyohizeiritu: $(".HDKShiharaiInput.ddlRSyohizeiritu").val(),
            txtKensakuCD: $(".HDKShiharaiInput.txtKensakuCD").val().trimEnd(),
            lblKensakuNM: $(".HDKShiharaiInput.lblKensakuNM").val().trimEnd(),
            txtTorihikiHasseibi: $(".HDKShiharaiInput.txtTorihikiHasseibi")
                .val()
                .trimEnd()
                .replace(/\//g, ""),
            grpGinko: $(
                '.HDKShiharaiInput.grpGinko input[name="HDKShiharaiInput_grpGinko"]:checked'
            ).val(),
            txtSonotaShiten: $(".HDKShiharaiInput.txtSonotaShiten")
                .val()
                .trimEnd(),
            txtSonotaGinko: $(".HDKShiharaiInput.txtSonotaGinko")
                .val()
                .trimEnd(),
            grpSyubetu: $(
                '.HDKShiharaiInput.grpSyubetu input[name="HDKShiharaiInput_grpSyubetu"]:checked'
            ).val(),
            txtKouzaNO: $(".HDKShiharaiInput.txtKouzaNO").val().trimEnd(),
            txtKouzaNM: $(".HDKShiharaiInput.txtKouzaNM").val().trimEnd(),
            grpJiki: $(
                '.HDKShiharaiInput.grpJiki input[name="HDKShiharaiInput_grpJiki"]:checked'
            ).val(),
            txtJikiDate: $(".HDKShiharaiInput.txtJikiDate")
                .val()
                .replace(/\//g, ""),
            txtTorihikiHasseibiEna: $(
                ".HDKShiharaiInput.txtTorihikiHasseibi"
            ).is(":disabled")
                ? "0"
                : "1",
            radHiroGinkoEna: $(".HDKShiharaiInput.radHiroGinko").is(":disabled")
                ? "0"
                : "1",
            txtSonotaGinkoEna: $(".HDKShiharaiInput.txtSonotaGinko").is(
                ":disabled"
            )
                ? "0"
                : "1",
            radSyubetuTouzaEna: $(".HDKShiharaiInput.radSyubetuTouza").is(
                ":disabled"
            )
                ? "0"
                : "1",
            txtKouzaNOEna: $(".HDKShiharaiInput.txtKouzaNO").is(":disabled")
                ? "0"
                : "1",
            txtKouzaNMEna: $(".HDKShiharaiInput.txtKouzaNM").is(":disabled")
                ? "0"
                : "1",
            lblSyohyNoVis: $(".HDKShiharaiInput.lblSyohy_no").is(":disabled")
                ? "0"
                : "1",
            txtPatternNM: $(".HDKShiharaiInput.txtPatternNM")
                .val()
                .replace(me.blankReplace, ""),
            grpPattern: $(
                '.HDKShiharaiInput.grpPattern input[name="HDKShiharaiInput_grpPattern"]:checked'
            ).val(),
            txtPatternBusyo: $(".HDKShiharaiInput.txtPatternBusyo")
                .val()
                .trimEnd(),
            txtSonotaShitenEna: $(".HDKShiharaiInput.txtSonotaShiten").is(
                ":disabled"
            )
                ? "0"
                : "1",
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                if (result["error"] == "W0034") {
                    $(".HDKShiharaiInput." + result["html"]).trigger("focus");
                    me.clsComFnc.FncMsgBox("W0034", result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
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
                        $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiharaiInput.ddlLSyohizeiritu").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiharaiInput.ddlRSyohizeiritu").get(
                            0
                        ).selectedIndex = 0;
                        $(".HDKShiharaiInput.ddlRKomokuCD").attr(
                            "disabled",
                            true
                        );
                        $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                            "disabled",
                            false
                        );
                        $(".HDKShiharaiInput.ddlRSyohizeiritu").attr(
                            "disabled",
                            false
                        );
                    }

                    // 支払予定日をセットする
                    me.subradJikiProc();
                    $(".HDKShiharaiInput.txtPatternNM").val("");
                    $(".HDKShiharaiInput.txtPatternBusyo").val("");
                    $(".HDKShiharaiInput.radPatternKyotu").prop(
                        "checked",
                        true
                    );
                    $(".HDKShiharaiInput.radPatternBusyo").prop(
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
                    $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlRSyohizeiritu").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                        "disabled",
                        false
                    );
                    $(".HDKShiharaiInput.ddlRSyohizeiritu").attr(
                        "disabled",
                        false
                    );
                    // 支払予定日をセットする
                    me.subradJikiProc();
                    me.hidCreateDate = "";
                    me.hidShiharaiDate = "";
                    $(".HDKShiharaiInput.txtPatternNM").val("");
                    $(".HDKShiharaiInput.txtPatternBusyo").val("");
                    $(".HDKShiharaiInput.radPatternKyotu").prop(
                        "checked",
                        true
                    );
                    $(".HDKShiharaiInput.radPatternBusyo").prop(
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

    me.DropDownListSet = function () {
        //貸方科目コードにセット
        for (var index = 0; index < me.RKamoku.length; index++) {
            var opt = me.RKamoku[index];
            $("<option></option>")
                .val(opt["SUCHI1"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HDKShiharaiInput.ddlRKamokuCD");
        }
        //貸方補助科目コードを不活性にする
        $(".HDKShiharaiInput.ddlRKomokuCD").attr("disabled", true);
        //借方消費税区分にセット
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HDKShiharaiInput.ddlLSyohizeiKbn");
        for (var index = 0; index < me.Meisyou.length; index++) {
            var opt = me.Meisyou[index];
            $("<option></option>")
                .val(opt["TAX_KBN_CD"])
                .text(opt["TAX_KBN_NAME"] == null ? "" : opt["TAX_KBN_NAME"])
                .appendTo(".HDKShiharaiInput.ddlLSyohizeiKbn");
        }
        //貸方消費税区分にセット
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HDKShiharaiInput.ddlRSyohizeiKbn");
        for (var index = 0; index < me.Meisyou.length; index++) {
            var opt = me.Meisyou[index];
            $("<option></option>")
                .val(opt["TAX_KBN_CD"])
                .text(opt["TAX_KBN_NAME"] == null ? "" : opt["TAX_KBN_NAME"])
                .appendTo(".HDKShiharaiInput.ddlRSyohizeiKbn");
        }
        //借方消費税率にセット
        for (var index = 0; index < me.Syohizeiritu.length; index++) {
            var opt = me.Syohizeiritu[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HDKShiharaiInput.ddlLSyohizeiritu");
        }
        //貸方消費税率にセット
        for (var index = 0; index < me.Syohizeiritu.length; index++) {
            var opt = me.Syohizeiritu[index];
            $("<option></option>")
                .val(opt["MEISYOU_CD"])
                .text(opt["MEISYOU"] == null ? "" : opt["MEISYOU"])
                .appendTo(".HDKShiharaiInput.ddlRSyohizeiritu");
        }
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
        $(".HDKShiharaiInput.ddlPatternSel").empty();
        for (var index = 0; index < me.PATTERN_Data.length; index++) {
            var opt = me.PATTERN_Data[index];
            $("<option></option>")
                .val(opt["PATTERN_NO"])
                .text(opt["PATTERN_NM"] == null ? "" : opt["PATTERN_NM"])
                .appendTo(".HDKShiharaiInput.ddlPatternSel");
        }
    };
    // '**********************************************************************
    // '処 理 名：クリア処理
    // '関 数 名：btnClear_Click
    // '処理説明：画面項目をクリアする
    // '**********************************************************************
    me.btnClear_Click = function (ifback, copyClear) {
        ifback = ifback == undefined ? true : ifback;
        copyClear = copyClear == undefined ? true : copyClear;
        me.hidGyoNO = "";
        // 画面項目をクリアする
        me.subFormClear(true, copyClear);

        // 時期に関する設定を行う
        me.subradJikiProc();

        if ($(".HDKShiharaiInput.ddlPatternSel").prop("selectedIndex") > -1) {
            $(".HDKShiharaiInput.ddlPatternSel").get(0).selectedIndex = 0;
        }

        // ドロップダウンをクリアする
        $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlRSyohizeiKbn").get(0).selectedIndex = 0;
        $(".HDKShiharaiInput.ddlRSyohizeiritu").get(0).selectedIndex = 0;

        $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", false);
        $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", false);

        // ボタンの活性・不活性を設定する
        me.DpyInpNewButtonEnabled(4);

        if (ifback) {
            // 貸方科目は初期値に振込を選択する
            me.subSyokiDataSet();
        }
    };
    // '**********************************************************************
    // '処 理 名：借方科目補助科目名取得
    // '関 数 名：txtLKamokuCD_LostFocus
    // '処理説明：フォーカス移動時に科目補助科目名を取得する
    // '**********************************************************************
    me.txtLKamokuCD_TextChanged = function (sender, changeFlag) {
        me.txtLkamokuCDKoumokuSet(sender, true, changeFlag);
    };
    // txtLKamokuCD_TextChangedの内容を関数化
    me.txtLkamokuCDKoumokuSet = function (sender, DefalutValue, changeFlag) {
        DefalutValue = DefalutValue == undefined ? true : DefalutValue;
        changeFlag = changeFlag == undefined ? false : changeFlag;

        if (
            $.trim(sender.val()) != "" &&
            $.trim($(".HDKShiharaiInput.txtLKomokuCD").val()) != ""
        ) {
            me.url = me.sys_id + "/" + me.id + "/" + "txtLkamokuCDKoumokuSet";
            var data = {
                strCode: $.trim(sender.val()),
                strKomoku: $.trim($(".HDKShiharaiInput.txtLKomokuCD").val()),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var res = result["data"];

                if (res["LKamoku"].length == 0) {
                    $(".HDKShiharaiInput.lblLKamokuNM").val("");
                    $(".HDKShiharaiInput.lblLKomokuNM").val("");
                    $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").get(
                        0
                    ).selectedIndex = 0;
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力された科目は存在しません"
                    );
                    return;
                }

                //** 名称取得
                $(".HDKShiharaiInput.lblLKamokuNM").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KAMOK_NAME"]) == ""
                        ? ""
                        : me.clsComFnc.FncNv(res["LKamoku"][0]["KAMOK_NAME"])
                );
                $(".HDKShiharaiInput.lblLKomokuNM").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["SUB_KAMOK_NAME"]) ==
                        ""
                        ? ""
                        : me.clsComFnc.FncNv(
                              res["LKamoku"][0]["SUB_KAMOK_NAME"]
                          )
                );

                $(".HDKShiharaiInput.ddlLSyohizeiKbn").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"])
                );
                if (
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"]) ==
                        "0000" ||
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"]) ==
                        "0080"
                ) {
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").val("90");
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                        "disabled",
                        true
                    );
                } else {
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                        "disabled",
                        false
                    );
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").val("70");
                }

                if (changeFlag == false) {
                    $(".HDKShiharaiInput.txtLKomokuCD").trigger("focus");
                } else if (changeFlag == true) {
                    $(".HDKShiharaiInput.btnLKamokuSearch").trigger("focus");
                } else if (changeFlag == "2") {
                    $(".HDKShiharaiInput.txtLBusyoCD").trigger("focus");
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
            $(".HDKShiharaiInput.lblLKamokuNM").val("");
            $(".HDKShiharaiInput.lblLKomokuNM").val("");
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
        }
    };
    // '**********************************************************************
    // '処 理 名：科目補助科目名取得
    // '関 数 名：txtLKamokuCD_LostFocus
    // '処理説明：フォーカス移動時に科目補助科目名を取得する
    // '**********************************************************************
    me.txtLKomokuCD_TextChanged = function (sender) {
        me.txtLkoumkCDKoumokuSet(sender, true);
        $(".HDKShiharaiInput.txtLBusyoCD").trigger("focus");
    };
    // txtLKomokuCD_TextChangedの内容を関数化
    me.txtLkoumkCDKoumokuSet = function (_sender, DefalutValue) {
        DefalutValue = DefalutValue == undefined ? true : DefalutValue;

        $(".HDKShiharaiInput.txtLKomokuCD").val(
            $.trim($(".HDKShiharaiInput.txtLKomokuCD").val())
        );
        $(".HDKShiharaiInput.btnLKamokuSearch").trigger("focus");

        if (
            $.trim($(".HDKShiharaiInput.txtLKamokuCD").val()) == "" ||
            $.trim($(".HDKShiharaiInput.txtLKomokuCD").val()) == ""
        ) {
            $(".HDKShiharaiInput.lblLKamokuNM").val("");
            $(".HDKShiharaiInput.lblLKomokuNM").val("");
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(0).selectedIndex = 0;
            $(".HDKShiharaiInput.ddlLSyohizeiritu").get(0).selectedIndex = 0;
            return;
        } else {
            me.url = me.sys_id + "/" + me.id + "/" + "txtLkoumkCDKoumokuSet";
            var data = {
                strCode: $.trim($(".HDKShiharaiInput.txtLKamokuCD").val()),
                strKomoku: $.trim($(".HDKShiharaiInput.txtLKomokuCD").val()),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var res = result["data"];

                if (res["LKamoku"].length == 0) {
                    $(".HDKShiharaiInput.lblLKamokuNM").val("");
                    $(".HDKShiharaiInput.lblLKomokuNM").val("");
                    $(".HDKShiharaiInput.ddlLSyohizeiKbn").get(
                        0
                    ).selectedIndex = 0;
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").get(
                        0
                    ).selectedIndex = 0;
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力された科目は存在しません"
                    );
                    return;
                }

                //** 名称取得
                $(".HDKShiharaiInput.lblLKamokuNM").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KAMOK_NAME"]) == ""
                        ? ""
                        : me.clsComFnc.FncNv(res["LKamoku"][0]["KAMOK_NAME"])
                );
                $(".HDKShiharaiInput.lblLKomokuNM").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["SUB_KAMOK_NAME"]) ==
                        ""
                        ? ""
                        : me.clsComFnc.FncNv(
                              res["LKamoku"][0]["SUB_KAMOK_NAME"]
                          )
                );

                $(".HDKShiharaiInput.ddlLSyohizeiKbn").val(
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"])
                );
                if (
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"]) ==
                        "0000" ||
                    me.clsComFnc.FncNv(res["LKamoku"][0]["KARI_TAX_KBN"]) ==
                        "0080"
                ) {
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").val("90");
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                        "disabled",
                        true
                    );
                } else {
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").val("07");
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").attr(
                        "disabled",
                        false
                    );
                }
            };
            me.ajax.send(me.url, data, 0);
        }
    };
    // '**********************************************************************
    // '処 理 名：行追加を行う
    // '関 数 名：btnAdd_Click
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e	  イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：行追加処理(入力チェック・確認メッセージの表示を行う)
    // '**********************************************************************
    me.btnAdd_Click = function (flg) {
        $(".HDKShiharaiInput.txtLKamokuCD").val(
            $.trim($(".HDKShiharaiInput.txtLKamokuCD").val())
        );
        $(".HDKShiharaiInput.txtLKomokuCD").val(
            $.trim($(".HDKShiharaiInput.txtLKomokuCD").val())
        );

        // 前処理
        // txtLkamokuCDKoumokuSet、名称取得
        me.url = me.sys_id + "/" + me.id + "/" + "btnAdd_Click";
        var data = {
            txtLKamokuCD: $(".HDKShiharaiInput.txtLKamokuCD").val().trimEnd(),
            txtLKomokuCD: $(".HDKShiharaiInput.txtLKomokuCD").val().trimEnd(),
            txtLBusyoCD: $(".HDKShiharaiInput.txtLBusyoCD").val().trimEnd(),
            txtRBusyoCD: $(".HDKShiharaiInput.txtRBusyoCD").val().trimEnd(),
        };
        //
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var res = result["data"];
            if ($.trim($(".HDKShiharaiInput.txtLKamokuCD").val()) == "") {
                $(".HDKShiharaiInput.lblLKamokuNM").val("");
            } else {
                //** 名称取得
                $(".HDKShiharaiInput.lblLKamokuNM").val(res["lblLKamokuNM"]);
            }
            if ($.trim($(".HDKShiharaiInput.txtLKomokuCD").val()) == "") {
                $(".HDKShiharaiInput.lblLKomokuNM").val("");
            } else {
                //** 名称取得
                $(".HDKShiharaiInput.lblLKomokuNM").val(res["lblLKomokuNM"]);
            }

            me.txtBusyoCD_TextChanged(
                $(".HDKShiharaiInput.txtLBusyoCD").val(),
                "L"
            );

            me.txtZeikm_GK_TextChanged();
            me.txtTekyo_TextChanged();
            me.ddlRSyohizeiKbn_SelectedIndexChanged();

            // 入力チェックを行う
            if (
                !me.fncInputCheck(
                    true,
                    flg == "add" ? "CMDEVENTINSERT" : "CMDEVENTUPDATE"
                )
            ) {
                return;
            }

            var strMessage = "";

            //名称取得
            var strSyozokuTenpo = res["strSyozokuTenpo"];
            var strKariTenpo = res["strKariTenpo"];
            var strKashiTenpo = res["strKashiTenpo"];

            // 経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
            ) {
                strMessage = flg == "add" ? "QY010" : "QY016";
            } else {
                if (
                    strKariTenpo == strSyozokuTenpo ||
                    strKashiTenpo == strSyozokuTenpo
                ) {
                    strMessage = flg == "add" ? "QY010" : "QY016";
                } else {
                    strMessage =
                        "借方にも貸方にも所属部署が含まれておりませんが、このまま登録を行いますか？";
                }
            }

            // 確認メッセージを表示する
            if (flg == "add") {
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

            if (strMessage == "QY010" || strMessage == "QY016") {
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
        var fncFukanzenCheck = 0;

        //新規の証憑登録の場合/コピー元証憑№の場合
        if (
            $(".HDKShiharaiInput.lblSyohy_no")
                .val()
                .trimEnd()
                .replace(me.blankReplace, "") == "" ||
            me.hidUpdDate == ""
        ) {
            me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click1";
            var objDs = $(me.grid_id).jqGrid("getRowData");
            data = {
                lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd(),
                HONBUFLG:
                    me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                    me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                        ? "1"
                        : "0",
                txtZeikm_GK: $(".HDKShiharaiInput.txtZeikm_GK")
                    .val()
                    .trimEnd()
                    .replace(/,/g, ""),
                lblZeink_GK: $(".HDKShiharaiInput.lblZeink_GK")
                    .text()
                    .trimEnd()
                    .replace(/,/g, ""),
                lblSyohizei: $(".HDKShiharaiInput.lblSyohizei")
                    .text()
                    .trimEnd()
                    .replace(/,/g, ""),
                txtTekyo: $(".HDKShiharaiInput.txtTekyo")
                    .val()
                    .replace(me.blankReplace, ""),
                txtLKamokuCD: $(".HDKShiharaiInput.txtLKamokuCD")
                    .val()
                    .trimEnd(),
                txtLKomokuCD: $(".HDKShiharaiInput.txtLKomokuCD")
                    .val()
                    .trimEnd(),
                txtLBusyoCD: $(".HDKShiharaiInput.txtLBusyoCD").val().trimEnd(),
                ddlLSyohizeiKbn: $(".HDKShiharaiInput.ddlLSyohizeiKbn").val(),
                ddlLSyohizeiritu: $(".HDKShiharaiInput.ddlLSyohizeiritu").val(),
                ddlRKamokuCD: $(".HDKShiharaiInput.ddlRKamokuCD").val(),
                // 20240408 LQS INS S
                syainCD:
                    $(".HDKShiharaiInput.ddlRKamokuCD").val() ==
                    me.HDKAIKEI.TATEKAE_KAMOKU_CD
                        ? $(".HDKShiharaiInput.txtTatekaeSyaCD").val()
                        : "",
                // 20240408 LQS INS E
                ddlRKomokuCD: $(".HDKShiharaiInput.ddlRKomokuCD").val(),
                txtRBusyoCD: $(".HDKShiharaiInput.txtRBusyoCD").val().trimEnd(),
                ddlRSyohizeiKbn: $(".HDKShiharaiInput.ddlRSyohizeiKbn").val(),
                ddlRSyohizeiritu: $(".HDKShiharaiInput.ddlRSyohizeiritu").val(),
                txtTorihikiHasseibi: $(".HDKShiharaiInput.txtTorihikiHasseibi")
                    .val()
                    .trimEnd()
                    .replace(/\//g, ""),
                txtKensakuCD: $(".HDKShiharaiInput.txtKensakuCD")
                    .val()
                    .trimEnd(),
                lblKensakuNM: $(".HDKShiharaiInput.lblKensakuNM")
                    .val()
                    .trimEnd(),
                grpGinko: $(
                    '.HDKShiharaiInput.grpGinko input[name="HDKShiharaiInput_grpGinko"]:checked'
                ).val(),
                txtSonotaShiten: $(".HDKShiharaiInput.txtSonotaShiten")
                    .val()
                    .trimEnd(),
                txtSonotaGinko: $(".HDKShiharaiInput.txtSonotaGinko")
                    .val()
                    .trimEnd(),
                grpSyubetu: $(
                    '.HDKShiharaiInput.grpSyubetu input[name="HDKShiharaiInput_grpSyubetu"]:checked'
                ).val(),
                txtKouzaNO: $(".HDKShiharaiInput.txtKouzaNO").val().trimEnd(),
                txtKouzaNM: $(".HDKShiharaiInput.txtKouzaNM").val().trimEnd(),
                grpJiki: $(
                    '.HDKShiharaiInput.grpJiki input[name="HDKShiharaiInput_grpJiki"]:checked'
                ).val(),
                txtJikiDate: $(".HDKShiharaiInput.txtJikiDate")
                    .val()
                    .replace(/\//g, ""),
                txtTorihikiHasseibiEna: $(
                    ".HDKShiharaiInput.txtTorihikiHasseibi"
                ).is(":disabled")
                    ? "0"
                    : "1",
                radHiroGinkoEna: $(".HDKShiharaiInput.radHiroGinko").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                txtSonotaGinkoEna: $(".HDKShiharaiInput.txtSonotaGinko").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                radSyubetuTouzaEna: $(".HDKShiharaiInput.radSyubetuTouza").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                txtKouzaNOEna: $(".HDKShiharaiInput.txtKouzaNO").is(":disabled")
                    ? "0"
                    : "1",
                txtKouzaNMEna: $(".HDKShiharaiInput.txtKouzaNM").is(":disabled")
                    ? "0"
                    : "1",
                txtSonotaShitenEna: $(".HDKShiharaiInput.txtSonotaShiten").is(
                    ":disabled"
                )
                    ? "0"
                    : "1",
                txtKeiriSyoriDT: $(".HDKShiharaiInput.txtKeiriSyoriDT")
                    .val()
                    .trimEnd()
                    .replace(/\//g, ""),
                fncFukanzenCheck: fncFukanzenCheck,
                // コピー元証憑№の場合
                sender: sender.toUpperCase(),
                hidGyoNO: me.selectedRow["GYO_NO"],
                copySyohyNo:
                    objDs.length > 0
                        ? $(".HDKShiharaiInput.txtCopySyohyNo")
                              .val()
                              .trimEnd()
                              .replace(/\//g, "")
                        : "",
            };
        }
        //既存な証憑登録の場合
        else {
            me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click2";
            data = {
                lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd(),
            };
        }

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (!result["result"]) {
                if (result["error"] == "W0034") {
                    $(".HDKShiharaiInput." + result["html"]).trigger("focus");
                    me.clsComFnc.FncMsgBox("W0034", result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            var res = result["data"];

            //新規の証憑登録の場合
            // コピー元証憑№
            if (
                $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd() == "" ||
                me.hidUpdDate == ""
            ) {
                if ($(".HDKShiharaiInput.lblSyohy_no").val().trimEnd() == "") {
                    //証憑№の取得を行う
                    var strSEQNO = res["strSEQNO"];
                } else {
                    var strSEQNO = $(".HDKShiharaiInput.lblSyohy_no")
                        .val()
                        .trimEnd();
                }

                //証憑№を表示する
                $(".HDKShiharaiInput.lblSyohy_no").val(strSEQNO);

                //更新日付を隠し項目にセット
                me.hidUpdDate = result["data"]["dtSysdate"];

                // 貸方科目は初期値に振込を選択する
                // me.subSyokiDataSet();
                me.btnClear_Click();
                me.afterDeal(sender);
            } else {
                //追加の証憑登録の場合
                var objCDT = res["CheckTbl"];

                var objNDT = res["NewNoTbl"];
                // 20241125 lhb upd s
                // if (objCDT && objCDT.length > 10) {
                // 	me.clsComFnc.FncMsgBox(
                // 		"W9999",
                // 		"10行を超える仕訳を登録することは出来ません！"
                // 	);
                if (objCDT && objCDT.length > 99) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "99行を超える仕訳を登録することは出来ません！"
                    );
                    // 20241125 lhb upd e
                    return;
                }
                //同時実行のチェックを行う
                if (
                    !me.fncCheckJikkoSeigyo(
                        objCDT,
                        objNDT,
                        $(".HDKShiharaiInput.lblSyohy_no")
                            .val()
                            .trimEnd()
                            .substring(15, 17)
                    )
                ) {
                    return;
                }

                me.url = me.sys_id + "/" + me.id + "/" + "cmdEvent_Click3";
                var edaNo = parseInt(objNDT[0]["EDA_NO"]) + 1;
                var data1 = {
                    FLG:
                        objCDT[0]["PRINT_OUT_FLG"] == "1" ||
                        objCDT[0]["CSV_OUT_FLG"] == "1" ||
                        objCDT[0]["XLSX_OUT_FLG"] == "1"
                            ? "1"
                            : "0",
                    intEdaNo: edaNo < 10 ? "0" + edaNo : edaNo, //枝№を取得する
                    // 20240129 YIN INS S
                    addData: true,
                    strCreBusyoCD: me.clsComFnc.FncNv(
                        objCDT[0]["CRE_BUSYO_CD"]
                    ),
                    strCreSyainCD: me.clsComFnc.FncNv(objCDT[0]["CRE_SYA_CD"]),
                    strCreCltNM: me.clsComFnc.FncNv(objCDT[0]["CRE_CLT_NM"]),
                    // 20240129 YIN INS E
                    PatternIDFLG:
                        me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                        me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
                            ? "1"
                            : "0",
                    sender: sender.toUpperCase(),
                    hidGyoNO: me.hidGyoNO.trimEnd(),
                    lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no")
                        .val()
                        .trimEnd(),
                    txtZeikm_GK: $(".HDKShiharaiInput.txtZeikm_GK")
                        .val()
                        .trimEnd()
                        .replace(/,/g, ""),
                    lblZeink_GK: $(".HDKShiharaiInput.lblZeink_GK")
                        .text()
                        .trimEnd()
                        .replace(/,/g, ""),
                    lblSyohizei: $(".HDKShiharaiInput.lblSyohizei")
                        .text()
                        .trimEnd()
                        .replace(/,/g, ""),
                    txtTekyo: $(".HDKShiharaiInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, ""),
                    txtLKamokuCD: $(".HDKShiharaiInput.txtLKamokuCD")
                        .val()
                        .trimEnd(),
                    txtLKomokuCD: $(".HDKShiharaiInput.txtLKomokuCD")
                        .val()
                        .trimEnd(),
                    txtLBusyoCD: $(".HDKShiharaiInput.txtLBusyoCD")
                        .val()
                        .trimEnd(),
                    ddlLSyohizeiKbn: $(
                        ".HDKShiharaiInput.ddlLSyohizeiKbn"
                    ).val(),
                    ddlLSyohizeiritu: $(
                        ".HDKShiharaiInput.ddlLSyohizeiritu"
                    ).val(),
                    ddlRKamokuCD: $(".HDKShiharaiInput.ddlRKamokuCD").val(),
                    // 20240408 LQS INS S
                    syainCD:
                        $(".HDKShiharaiInput.ddlRKamokuCD").val() ==
                        me.HDKAIKEI.TATEKAE_KAMOKU_CD
                            ? $(".HDKShiharaiInput.txtTatekaeSyaCD").val()
                            : "",
                    // 20240408 LQS INS E
                    ddlRKomokuCD: $(".HDKShiharaiInput.ddlRKomokuCD").val(),
                    txtRBusyoCD: $(".HDKShiharaiInput.txtRBusyoCD")
                        .val()
                        .trimEnd(),
                    ddlRSyohizeiKbn: $(
                        ".HDKShiharaiInput.ddlRSyohizeiKbn"
                    ).val(),
                    ddlRSyohizeiritu: $(
                        ".HDKShiharaiInput.ddlRSyohizeiritu"
                    ).val(),
                    txtTorihikiHasseibi: $(
                        ".HDKShiharaiInput.txtTorihikiHasseibi"
                    )
                        .val()
                        .trimEnd()
                        .replace(/\//g, ""),
                    txtKensakuCD: $(".HDKShiharaiInput.txtKensakuCD")
                        .val()
                        .trimEnd(),
                    lblKensakuNM: $(".HDKShiharaiInput.lblKensakuNM")
                        .val()
                        .trimEnd(),
                    grpGinko: $(
                        '.HDKShiharaiInput.grpGinko input[name="HDKShiharaiInput_grpGinko"]:checked'
                    ).val(),
                    txtSonotaShiten: $(".HDKShiharaiInput.txtSonotaShiten")
                        .val()
                        .trimEnd(),
                    txtSonotaGinko: $(".HDKShiharaiInput.txtSonotaGinko")
                        .val()
                        .trimEnd(),
                    grpSyubetu: $(
                        '.HDKShiharaiInput.grpSyubetu input[name="HDKShiharaiInput_grpSyubetu"]:checked'
                    ).val(),
                    txtKouzaNO: $(".HDKShiharaiInput.txtKouzaNO")
                        .val()
                        .trimEnd(),
                    txtKouzaNM: $(".HDKShiharaiInput.txtKouzaNM")
                        .val()
                        .trimEnd(),
                    grpJiki: $(
                        '.HDKShiharaiInput.grpJiki input[name="HDKShiharaiInput_grpJiki"]:checked'
                    ).val(),
                    txtJikiDate: $(".HDKShiharaiInput.txtJikiDate")
                        .val()
                        .replace(/\//g, ""),
                    txtTorihikiHasseibiEna: $(
                        ".HDKShiharaiInput.txtTorihikiHasseibi"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    radHiroGinkoEna: $(".HDKShiharaiInput.radHiroGinko").is(
                        ":disabled"
                    )
                        ? "0"
                        : "1",
                    txtSonotaGinkoEna: $(".HDKShiharaiInput.txtSonotaGinko").is(
                        ":disabled"
                    )
                        ? "0"
                        : "1",
                    radSyubetuTouzaEna: $(
                        ".HDKShiharaiInput.radSyubetuTouza"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtKouzaNOEna: $(".HDKShiharaiInput.txtKouzaNO").is(
                        ":disabled"
                    )
                        ? "0"
                        : "1",
                    txtKouzaNMEna: $(".HDKShiharaiInput.txtKouzaNM").is(
                        ":disabled"
                    )
                        ? "0"
                        : "1",
                    txtSonotaShitenEna: $(
                        ".HDKShiharaiInput.txtSonotaShiten"
                    ).is(":disabled")
                        ? "0"
                        : "1",
                    txtKeiriSyoriDT: $(".HDKShiharaiInput.txtKeiriSyoriDT")
                        .val()
                        .trimEnd()
                        .replace(/\//g, ""),
                    fncFukanzenCheck: fncFukanzenCheck,
                };

                me.ajax.receive = function (result1) {
                    result1 = eval("(" + result1 + ")");

                    if (!result1["result"]) {
                        if (result1["error"] == "W0034") {
                            $(".HDKShiharaiInput." + result1["html"]).trigger(
                                "focus"
                            );
                            me.clsComFnc.FncMsgBox("W0034", result1["data"]);
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result1["error"]);
                        }
                        return;
                    }
                    //印刷済みの証憑の場合
                    if (
                        objCDT[0]["PRINT_OUT_FLG"] == "1" ||
                        objCDT[0]["CSV_OUT_FLG"] == "1" ||
                        objCDT[0]["XLSX_OUT_FLG"] == "1"
                    ) {
                        //証憑№を表示する
                        $(".HDKShiharaiInput.lblSyohy_no").val(
                            $(".HDKShiharaiInput.lblSyohy_no")
                                .val()
                                .replace(me.blankReplace, "")
                                .substring(0, 15) + result1["data"]["intEdaNo"]
                        );
                        //更新日付を隠し項目にセット
                        me.hidUpdDate = result1["data"]["dtSysdate"];
                        //修正前データを取得する
                        if (result1["data"]["SyuseiMaeTbl"].length > 0) {
                            if (
                                me.clsComFnc.FncNv(
                                    result1["data"]["SyuseiMaeTbl"][0][
                                        "SYOHY_NO"
                                    ]
                                ) == ""
                            ) {
                                //修正前データが存在しない場合
                                //修正前表示ボタンを不活性にする
                                $(".HDKShiharaiInput.btnSyuseiMaeDisp").button(
                                    "disable"
                                );
                            } else {
                                //修正前データが存在する場合
                                //修正前表示ボタンを活性にする
                                $(".HDKShiharaiInput.btnSyuseiMaeDisp").button(
                                    "enable"
                                );
                            }
                        }
                    } else {
                        //登録処理
                        switch (sender.toUpperCase()) {
                            case "CMDEVENTDELETE":
                            case "CMDEVENTALLDELETE":
                                if (
                                    result1["data"]["DispModeTbl"].length == 0
                                ) {
                                    me.hidUpdDate = "";
                                } else {
                                    me.hidUpdDate = me.clsComFnc.FncNv(
                                        result1["data"]["DispModeTbl"][0][
                                            "UPD_DATE"
                                        ]
                                    );
                                }
                                break;
                            case "CMDEVENTINSERT":
                            case "CMDEVENTUPDATE":
                                //証憑№はそのままなので何もしない
                                //更新日付を隠し項目にセット
                                me.hidUpdDate = result1["data"]["dtSysdate"];
                                break;
                        }
                    }

                    // 貸方科目は初期値に振込を選択する
                    // me.subSyokiDataSet();
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
        me.hidCreateDate = "";
        me.hidShiharaiDate = "";

        switch (sender.toUpperCase()) {
            case "CMDEVENTINSERT":
            case "CMDEVENTUPDATE":
            case "CMDEVENTDELETE":
                jqgridDataShow();
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
            lblSyohy_no: $(".HDKShiharaiInput.lblSyohy_no").val().trimEnd(),
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
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
            } else {
                me.HDKShiharaiInput_Load();
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
            me.clsComFnc.FncNv(objCDt[0]["CSV_OUT_FLG"]) == "1" ||
            me.clsComFnc.FncNv(objCDt[0]["XLSX_OUT_FLG"]) == "1"
        ) {
            //経理課ではなくパターンＩＤが管理者又は本部かで分けるように変更
            if (
                me.PatternID != me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
                me.PatternID != me.HDKAIKEI.CONST_HONBU_PTN_NO
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
        if (objNDt[0]["EDA_NO"] != strEda_No) {
            //他のユーザーにより更新されています。最新の情報を確認してください。
            me.clsComFnc.FncMsgBox("W0025");
            return false;
        }
        return true;
    };

    me.fncInputCheck = function (blnHissuChk, action) {
        blnHissuChk = blnHissuChk === undefined ? true : blnHissuChk;

        $(".HDKShiharaiInput.txtKouzaNM").val(
            $(".HDKShiharaiInput.txtKouzaNM")
                .val()
                .toHankaku()
                .toHankanaCase()
                .replace(/　/g, " ")
        );

        // 税込金額が未入力の場合、エラー
        if ($.trim($(".HDKShiharaiInput.txtZeikm_GK").val()) == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "税込金額が未入力です！");
                return false;
            }
        } else {
            // 税込金額の桁数チェック
            if (
                me.clsComFnc.GetByteCount(
                    $.trim($(".HDKShiharaiInput.txtZeikm_GK").val()).replace(
                        /,/g,
                        ""
                    )
                ) > 13
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "税込金額", "13");
                return false;
            }

            // 税込金額に不正な値が入力されている場合、エラー
            if (
                me.isPosNumber(
                    $(".HDKShiharaiInput.txtZeikm_GK").val().replace(/,/g, "")
                ) == -1
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox("E0013", "税込金額");
                return false;
            }

            // 未払費用以外で税込み金額に負数が入力されている場合、エラー
            // if ($(".HDKShiharaiInput.ddlRKamokuCD").val() != null && $(".HDKShiharaiInput.ddlRKamokuCD").val().padRight(6).substring(1) != "21152") {
            if (
                $.trim($(".HDKShiharaiInput.txtZeikm_GK").val()).replace(
                    /,/g,
                    ""
                ) < 0
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "税込金額に負数が入力されています！"
                );
                return false;
            }
            // }

            if (
                $.trim($(".HDKShiharaiInput.txtZeikm_GK").val()).replace(
                    /,/g,
                    ""
                ) < 0
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtZeikm_GK")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "税込金額に負数が入力されています！"
                );
                return false;
            }
            if (blnHissuChk) {
                var total = parseInt(
                    $(".HDKShiharaiInput.lblZeikomiGoukei")
                        .val()
                        .replace(/,/g, "")
                );
                if (action == "CMDEVENTINSERT") {
                    total =
                        total +
                        parseInt(
                            $(".HDKShiharaiInput.txtZeikm_GK")
                                .val()
                                .replace(/,/g, "")
                        );
                } else if (action == "CMDEVENTUPDATE") {
                    total =
                        total -
                        parseInt(me.selectedRow["ZEIKM_GK"]) +
                        parseInt(
                            $(".HDKShiharaiInput.txtZeikm_GK")
                                .val()
                                .replace(/,/g, "")
                        );
                }

                if (me.clsComFnc.GetByteCount(total.toString()) > 13) {
                    me.clsComFnc.ObjFocus =
                        $(".HDKShiharaiInput.txtZeikm_GK").prop("disabled") ==
                        false
                            ? $(".HDKShiharaiInput.txtZeikm_GK")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "税込金額合計が最大可能桁数を超えています。"
                    );
                    $(".HDKShiharaiInput.txtZeikm_GK").trigger("focus");
                    return;
                }
            }
        }

        // 借方科目コードが未入力の場合、エラー
        if ($.trim($(".HDKShiharaiInput.txtLKamokuCD").val()) == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtLKamokuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtLKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "借方科目コードが未入力です！");
                return false;
            }
        } else if ($.trim($(".HDKShiharaiInput.txtLKomokuCD").val()) == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtLKomokuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtLKomokuCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方補助科目コードが未入力です!"
                );
                return false;
            }
        } else {
            // 借方科目コードがマスタに存在しない場合、エラー
            var KamokuMst = me.KamokuMst;

            var index = KamokuMst.findIndex(function (ele) {
                return (
                    ele["KAMOK_CD"] ==
                        $(".HDKShiharaiInput.txtLKamokuCD").val().trimEnd() &&
                    ele["SUB_KAMOK_CD"] ==
                        $(".HDKShiharaiInput.txtLKomokuCD").val().trimEnd()
                );
            });

            if (index == -1) {
                $(".HDKShiharaiInput.lblLKamokuNM").val("");
                $(".HDKShiharaiInput.lblLKomokuNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtLKamokuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtLKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "入力された科目は存在しません");
                return false;
            }
        }

        // 部署コードが未入力の場合
        if ($(".HDKShiharaiInput.txtLBusyoCD").val().trimEnd() == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtLBusyoCD").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtLBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "借方発生部署が未入力です！");
                return false;
            }
        } else {
            // 借方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HDKShiharaiInput.txtLBusyoCD").val().trimEnd()
                );
            });
            if (index == -1) {
                $(".HDKShiharaiInput.lblLbusyoNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtLBusyoCD").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtLBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方発生部署が部署マスタに存在しません！"
                );
                return false;
            } else {
                $(".HDKShiharaiInput.lblLbusyoNM").val(
                    me.allBusyo[index]["BUSYO_NM"]
                );
            }
        }

        // 借方消費税区分が選択されていない場合
        if ($(".HDKShiharaiInput.ddlLSyohizeiKbn").prop("selectedIndex") == 0) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.ddlLSyohizeiKbn").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.ddlLSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方消費税区分が選択されていません！"
                );
                return false;
            }
        } else {
            if (
                $(".HDKShiharaiInput.ddlLSyohizeiritu").prop("selectedIndex") ==
                0
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.ddlLSyohizeiritu").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.ddlLSyohizeiritu")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "借方消費税率が選択されていません！"
                );
                return false;
            }
        }

        // 貸方科目コードが未入力の場合、エラー
        if ($(".HDKShiharaiInput.ddlRKamokuCD").prop("selectedIndex") == 0) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.ddlRKamokuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.ddlRKamokuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "貸方科目コードが未入力です！");
                return false;
            }
        }

        // 貸方部署コードが未入力の場合
        if ($(".HDKShiharaiInput.txtRBusyoCD").val().trimEnd() == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtRBusyoCD").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtRBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "貸方発生部署が未入力です！");
                return false;
            }
        } else {
            // 貸方部署がマスタに存在しない場合
            var index = me.allBusyo.findIndex(function (ele) {
                return (
                    ele["BUSYO_CD"] ==
                    $(".HDKShiharaiInput.txtRBusyoCD").val().trimEnd()
                );
            });
            if (index == -1) {
                $(".HDKShiharaiInput.lblRbusyoNM").val("");
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtRBusyoCD").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtRBusyoCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方発生部署が部署マスタに存在しません！"
                );
                return false;
            } else {
                $(".HDKShiharaiInput.lblRbusyoNM").val(
                    me.allBusyo[index]["BUSYO_NM"]
                );
            }
        }
        // 貸方消費税区分が選択されていない場合
        if ($(".HDKShiharaiInput.ddlRSyohizeiKbn").prop("selectedIndex") == 0) {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.ddlRSyohizeiKbn").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.ddlRSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方消費税区分が選択されていません！"
                );
                return false;
            }
        } else {
            if ($(".HDKShiharaiInput.ddlRSyohizeiKbn").val() != "0000") {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.ddlRSyohizeiKbn").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.ddlRSyohizeiKbn")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "貸方消費税区分は対象外でなければなりません。"
                );
                return false;
            }
        }

        // 20240408 LQS INS S
        // 貸方科目ブルダウンに 「未払金給与（社員立替）」を選択されたら、社員コードが未入力の場合
        if (
            $(".HDKShiharaiInput.ddlRKamokuCD").val() ==
            me.HDKAIKEI.TATEKAE_KAMOKU_CD
        ) {
            if ($(".HDKShiharaiInput.txtTatekaeSyaCD").val().trimEnd() == "") {
                if (blnHissuChk) {
                    me.clsComFnc.ObjFocus =
                        $(".HDKShiharaiInput.txtTatekaeSyaCD").prop(
                            "disabled"
                        ) == false
                            ? $(".HDKShiharaiInput.txtTatekaeSyaCD")
                            : "";
                    me.clsComFnc.FncMsgBox("E9999", "社員が未入力です！");
                    return false;
                }
            } else {
                // 社員がマスタに存在しない場合
                var index = me.allSyain.findIndex(function (ele) {
                    return (
                        ele["SYAIN_NO"] ==
                        $(".HDKShiharaiInput.txtTatekaeSyaCD").val().trimEnd()
                    );
                });
                if (index == -1) {
                    $(".HDKShiharaiInput.lblTatekaeSyaNM").val("");
                    me.clsComFnc.ObjFocus =
                        $(".HDKShiharaiInput.txtTatekaeSyaCD").prop(
                            "disabled"
                        ) == false
                            ? $(".HDKShiharaiInput.txtTatekaeSyaCD")
                            : "";
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "社員が社員マスタに存在しません！"
                    );
                    return false;
                } else {
                    $(".HDKShiharaiInput.lblTatekaeSyaNM").val(
                        me.allSyain[index]["SYAIN_NM"]
                    );
                }
            }
        }
        // 20240408 LQS INS E

        // 取引先が未入力の場合、エラー
        if ($(".HDKShiharaiInput.txtKensakuCD").val().trimEnd() == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKensakuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtKensakuCD")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "取引先コードが未入力です！");
                return false;
            }
        } else {
            // 取引先コードが取引先マスタに存在しない場合、エラー
            var index = me.allTorihikisaki.findIndex(function (one) {
                return (
                    one["TORIHIKISAKI_CD"] ==
                    $(".HDKShiharaiInput.txtKensakuCD").val().trimEnd()
                );
            });
            if (index == -1) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKensakuCD").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtKensakuCD")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "取引先コードが取引先マスタに存在しません！"
                );
                return false;
            } else {
                $(".HDKShiharaiInput.lblKensakuNM").val(
                    me.allTorihikisaki[index]["TORIHIKISAKI_NAME"]
                );
            }
        }

        // 取引発生日に日付以外が入力された場合、エラー
        if ($(".HDKShiharaiInput.txtTorihikiHasseibi").val().trimEnd() != "") {
            if (
                me.clsComFnc.CheckDate(
                    $(".HDKShiharaiInput.txtTorihikiHasseibi")
                ) == false
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtTorihikiHasseibi").prop(
                        "disabled"
                    ) == false
                        ? $(".HDKShiharaiInput.txtTorihikiHasseibi")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "取引発生日に日付以外の値が入力されています。"
                );
                return false;
            }
        }

        // 支払予定日が未入力の場合、エラー
        if ($(".HDKShiharaiInput.txtJikiDate").val().trimEnd() == "") {
            if (blnHissuChk) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtJikiDate").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtJikiDate")
                        : "";
                me.clsComFnc.FncMsgBox("E9999", "支払予定日が未入力です！");
                return false;
            }
        } else {
            // 時期に日付以外が入力された場合、エラー
            if (
                me.clsComFnc.CheckDate($(".HDKShiharaiInput.txtJikiDate")) ==
                false
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtJikiDate").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtJikiDate")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支払時期に日付以外の値が入力されています。"
                );
                return false;
            }
            // 20240315 LQS INS S
            var d = new Date($(".HDKShiharaiInput.txtJikiDate").val()).getDay();
            if (d === 0 || d === 6) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtJikiDate").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtJikiDate")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支払予定日に土曜日、日曜日は指定できません。"
                );
                return false;
            }
            // 20240315 LQS INS E
        }

        if ($(".HDKShiharaiInput.txtJikiDate").val().trimEnd() != "") {
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKShiharaiInput.txtTekyo")
                        .val()
                        .replace(me.blankReplace, "")
                ) > 240
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtTekyo").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtTekyo")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "摘要", "240");
                return false;
            }
        }

        if ($(".HDKShiharaiInput.txtKouzaNM").val().trimEnd() != "") {
            var patt = /^[0-9a-zA-Z!-`ｧ-ｰｱ-ﾟ]*$/g;
            if (
                !$(".HDKShiharaiInput.txtKouzaNM")
                    .val()
                    .replace(/ /g, "")
                    .match(patt)
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKouzaNM").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtKouzaNM")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "振込先口座名には半角カナ・英数字・記号以外の文字を入力することは出来ません！"
                );
                return false;
            }
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKShiharaiInput.txtKouzaNM").val().trimEnd()
                    // 20240520 YIN UPD S
                    // ) > 60
                    // 20240522 YIN UPD S
                    // ) > 30
                ) > 60
                // 20240522 YIN UPD E
                // 20240520 YIN UPD E
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKouzaNM").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtKouzaNM")
                        : "";
                // 20240520 YIN UPD S
                // me.clsComFnc.FncMsgBox("E0027", "振込先口座名", "60");
                // 20240522 YIN UPD S
                // me.clsComFnc.FncMsgBox("E0027", "振込先口座名", "30");
                me.clsComFnc.FncMsgBox("E0027", "振込先口座名", "60");
                // 20240522 YIN UPD E
                // 20240520 YIN UPD E
                return false;
            }
        }

        // 20240507 LQS INS S
        // 銀行名 // 支店名
        if (
            $(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd() != "" &&
            $(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd() != ""
        ) {
            // マスタに存在しない場合、エラー
            var index = me.allBank.findIndex(function (one) {
                return (
                    one["BANK_NM"] ==
                        $(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd() &&
                    one["BRANCH_NM"] ==
                        $(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd()
                );
            });
            if (index == -1) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtSonotaShiten").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtSonotaShiten")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "銀行名・支店名が金融機関マスタに存在しません！"
                );
                return false;
            }
        }
        if (
            $(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd() != "" &&
            $(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd() == ""
        ) {
            // マスタに存在しない場合、エラー
            var index = me.allBank.findIndex(function (one) {
                return (
                    one["BANK_NM"] ==
                    $(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd()
                );
            });
            if (index == -1) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtSonotaGinko").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtSonotaGinko")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "銀行名が金融機関マスタに存在しません！"
                );
                return false;
            }
        }
        if (
            $(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd() == "" &&
            $(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd() != ""
        ) {
            // マスタに存在しない場合、エラー
            var index = me.allBank.findIndex(function (one) {
                return (
                    one["BRANCH_NM"] ==
                    $(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd()
                );
            });
            if (index == -1) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtSonotaShiten").prop("disabled") ==
                    false
                        ? $(".HDKShiharaiInput.txtSonotaShiten")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "支店名が金融機関マスタに存在しません！"
                );
                return false;
            }
        }
        // 20240507 LQS INS E

        // 20240507 LQS DEL S
        // // その他銀行名
        // if ($(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd() != "") {
        // 	if (
        // 		me.clsComFnc.GetByteCount(
        // 			$(".HDKShiharaiInput.txtSonotaGinko").val().trimEnd()
        // 		) > 19
        // 	) {
        // 		me.clsComFnc.ObjFocus =
        // 			$(".HDKShiharaiInput.txtSonotaGinko").prop("disabled") == false
        // 				? $(".HDKShiharaiInput.txtSonotaGinko")
        // 				: "";
        // 		me.clsComFnc.FncMsgBox("E0027", "その他銀行名", "19");
        // 		return false;
        // 	}
        // }
        // // その他支店名
        // if ($(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd() != "") {
        // 	if (
        // 		me.clsComFnc.GetByteCount(
        // 			$(".HDKShiharaiInput.txtSonotaShiten").val().trimEnd()
        // 		) > 15
        // 	) {
        // 		me.clsComFnc.ObjFocus =
        // 			$(".HDKShiharaiInput.txtSonotaShiten").prop("disabled") == false
        // 				? $(".HDKShiharaiInput.txtSonotaShiten")
        // 				: "";
        // 		me.clsComFnc.FncMsgBox("E0027", "その他支店名", "15");
        // 		return false;
        // 	}
        // }
        // 20240507 LQS DEL E
        // 振込先口座№
        if ($(".HDKShiharaiInput.txtKouzaNO").val().trimEnd() != "") {
            var patt = /^[0-9]*$/g;
            if (!$(".HDKShiharaiInput.txtKouzaNO").val().match(patt)) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKouzaNO").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtKouzaNO")
                        : "";
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "口座№には半角数値以外を入力することは出来ません！"
                );
                return false;
            }
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKShiharaiInput.txtKouzaNO").val().trimEnd()
                ) > 7
            ) {
                me.clsComFnc.ObjFocus =
                    $(".HDKShiharaiInput.txtKouzaNO").prop("disabled") == false
                        ? $(".HDKShiharaiInput.txtKouzaNO")
                        : "";
                me.clsComFnc.FncMsgBox("E0027", "口座№", "7");
                return false;
            }
        }
        return true;
    };

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
            // 20240606 LQS UPD S
            // me.DpyInpNewButtonEnabled(me.hidMode);
            // if (me.hidGyoNO != "") {
            // 	$(".HDKShiharaiInput.btnUpdate").button("enable");
            // 	$(".HDKShiharaiInput.btnDelete").button("enable");
            // }
            if (me.hidGyoNO != "") {
                me.DpyInpNewButtonEnabled(
                    me.hidMode == "1" || me.hidMode == "2" ? "3" : "1"
                );
            }
            // 20240606 LQS UPD E
            return;
        }
        if (me.clsComFnc.CheckDate(sender) == false) {
            if (
                sender.selector.toString() ==
                ".HDKShiharaiInput.txtTorihikiHasseibi"
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
                ".HDKShiharaiInput.txtTorihikiHasseibi"
            ) {
                me.allBtnDisable(true);
            }
        } else {
            me.allBtnDisable(false);
            // 20240606 LQS INS S
            me.DpyInpNewButtonEnabled(
                me.hidMode == "1" || me.hidMode == "2" ? "3" : "1"
            );
            // 20240606 LQS INS E
            if (me.hidGyoNO == "") {
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
            }
        }
    };
    me.allBtnDisable = function (flg) {
        var status = flg ? "disable" : "enable";
        $(".HDKShiharaiInput.btnAdd").button(status);
        $(".HDKShiharaiInput.btnUpdate").button(status);
        $(".HDKShiharaiInput.btnDelete").button(status);
        $(".HDKShiharaiInput.btnKakutei").button(status);
        $(".HDKShiharaiInput.btnPtnInsert").button(status);
        $(".HDKShiharaiInput.btnAllDelete").button(status);
        $(".HDKShiharaiInput.btnPtnUpdate").button(status);
        $(".HDKShiharaiInput.btnClear").button(status);
        $(".HDKShiharaiInput.btnPtnDelete").button(status);
        $(".HDKShiharaiInput.btnPatternTrk").button(status);
    };
    // '**********************************************************************
    // '処 理 名：消費税区分選択時(貸方)
    // '関 数 名：ddlRSyohizeiKbn_SelectedIndexChanged
    // '処理説明：消費税区分で対象外が選択された場合取引区分は不活性にする
    // '　　　　：消費税区分で選択された値と税込金額から税抜金額と消費税額を
    // '　　　　：計算し、表示する
    // '**********************************************************************
    me.ddlRSyohizeiKbn_SelectedIndexChanged = function () {
        $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", false);
        if ($(".HDKShiharaiInput.ddlRSyohizeiKbn").prop("selectedIndex") > 0) {
            if ($(".HDKShiharaiInput.ddlRSyohizeiKbn").val() == "0000") {
                $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", true);
                $(".HDKShiharaiInput.ddlRSyohizeiritu").val("90");
            }
        }
        if ($(".HDKShiharaiInput.txtZeikm_GK").css("display") != "none") {
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
        $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", false);
        if ($(".HDKShiharaiInput.ddlLSyohizeiKbn").prop("selectedIndex") > 0) {
            if ($(".HDKShiharaiInput.ddlLSyohizeiKbn").val() == "0000") {
                $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", true);
                $(".HDKShiharaiInput.ddlLSyohizeiritu").val("90");
            }
        }
        if ($(".HDKShiharaiInput.txtZeikm_GK").css("display") != "none") {
            me.txtZeikm_GK_TextChanged();
        }
    };

    // '**********************************************************************
    // '処 理 名：摘要欄の 全文字強制全角変換を廃止し、英数字記号は半角に変換
    // '関 数 名：txtTekyo_TextChanged
    // '処理説明：摘要欄の 全文字強制全角変換を廃止し、英数字記号は半角に変換
    // '**********************************************************************
    me.txtTekyo_TextChanged = function () {
        $(".HDKShiharaiInput.txtTekyo").val(
            $(".HDKShiharaiInput.txtTekyo")
                .val()
                .replace(me.blankReplace, "")
                .toHankaku()
        );
    };
    // '**********************************************************************
    // '処 理 名：取引先名取得
    // '関 数 txtKensakuCD_TextChanged
    // '処理説明：フォーカス移動時に取引先名を取得する
    // '**********************************************************************
    me.txtKensakuCD_TextChanged = function (sender) {
        if ($.trim(sender.val()) != "") {
            var foundNM = "";
            var foundNM_array = me.allTorihikisaki.filter(function (element) {
                return (
                    element["TORIHIKISAKI_CD"] ==
                    me.clsComFnc.FncNv($.trim(sender.val()))
                );
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0]["TORIHIKISAKI_NAME"];
            }
            $(".HDKShiharaiInput.lblKensakuNM").val(foundNM);
        } else {
            $(".HDKShiharaiInput.lblKensakuNM").val("");
        }
    };
    // 20240408 LQS INS S
    // '**********************************************************************
    // '処 理 名：社員名取得
    // '関 数 txtTatekaeSyaCD_TextChanged
    // '処理説明：フォーカス移動時に取引先名を取得する
    // '**********************************************************************
    me.txtTatekaeSyaCD_TextChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allSyain) {
            var foundNM_array = me.allSyain.filter(function (element) {
                return element["SYAIN_NO"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
        }
        $(".HDKShiharaiInput.lblTatekaeSyaNM").val(
            foundNM ? foundNM["SYAIN_NM"] : ""
        );
    };
    // 20240408 LQS INS E
    // '**********************************************************************
    // '処 理 名：税込金額入力で税抜き金額と消費税額を計算する
    // '関 数 名：txtZeikm_GK_TextChanged
    // '処理説明：税込金額に入力された値で消費税区分より税抜金額と消費税額を計算し、表示する
    // '**********************************************************************
    me.txtZeikm_GK_TextChanged = function () {
        var dealedVal = $.trim(
            $(".HDKShiharaiInput.txtZeikm_GK").val()
        ).replace(/,/g, "");
        if (dealedVal == "") {
            $(".HDKShiharaiInput.lblZeink_GK").text("");
            $(".HDKShiharaiInput.lblSyohizei").text("");
            if ($.trim($(".HDKShiharaiInput.txtZeikm_GK").val()) != "") {
                me.clsComFnc.ObjFocus = $(".HDKShiharaiInput.txtZeikm_GK");
                me.clsComFnc.FncMsgBox("W9999", "数字以外が入力されています。");
                $(".HDKShiharaiInput.txtZeikm_GK").val("");
            }
            return;
        }
        if (
            me.isPosNumber(
                $(".HDKShiharaiInput.txtZeikm_GK").val().replace(/,/g, "")
            ) == -1
        ) {
            $(".HDKShiharaiInput.lblZeink_GK").text("");
            $(".HDKShiharaiInput.lblSyohizei").text("");
            return;
        }
        if (dealedVal == "0") {
            $(".HDKShiharaiInput.lblZeink_GK").text("0");
            $(".HDKShiharaiInput.lblSyohizei").text("0");
            return;
        }
        if (dealedVal != "") {
            if (
                $(".HDKShiharaiInput.ddlLSyohizeiritu").prop("selectedIndex") ==
                0
            ) {
                $(".HDKShiharaiInput.lblZeink_GK").text("");
                $(".HDKShiharaiInput.lblSyohizei").text("");
            } else {
                var ddlLVal = $(".HDKShiharaiInput.ddlLSyohizeiritu").val();
                var ddlRVal = $(".HDKShiharaiInput.ddlRSyohizeiritu").val();
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
                        $(".HDKShiharaiInput.lblZeink_GK"),
                        "label",
                        dblZeink_gk
                    );
                    var lblSyohizeiVal = dealedVal - dblZeink_gk;
                    me.toMoney(
                        $(".HDKShiharaiInput.lblSyohizei"),
                        "label",
                        lblSyohizeiVal
                    );
                } else {
                    me.toMoney(
                        $(".HDKShiharaiInput.lblZeink_GK"),
                        "label",
                        $(".HDKShiharaiInput.txtZeikm_GK").val()
                    );
                    $(".HDKShiharaiInput.lblSyohizei").text("0");
                }
            }
        }
        $(".HDKShiharaiInput.txtTekyo").trigger("focus");
    };
    // '**********************************************************************
    // '処 理 名：銀行区分が変更されたとき
    // '関 数 名：radHiroGinko_CheckedChanged
    // '処理説明：銀行区分が変更されたとき
    // '**********************************************************************
    me.radHiroGinko_CheckedChanged = function () {
        if ($(".HDKShiharaiInput.radGinkoSonota").prop("checked")) {
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", false);
            $(".HDKShiharaiInput.txtSonotaShiten").attr("disabled", false);
            $(".HDKShiharaiInput.txtSonotaGinko").val("");
        } else {
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", true);
            $(".HDKShiharaiInput.txtSonotaShiten").attr("disabled", false);
            if ($(".HDKShiharaiInput.radHiroGinko").prop("checked")) {
                $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）");
            } else if ($(".HDKShiharaiInput.radMomijiGinko").prop("checked")) {
                $(".HDKShiharaiInput.txtSonotaGinko").val("もみじ");
            } else if ($(".HDKShiharaiInput.radShinyoKinko").prop("checked")) {
                //20240507 LQS UPD S
                // $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）信用金庫");
                $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）信金");
                //20240507 LQS UPD E
            }
        }
        // 20240507 LQS INS S
        me.setBankSearchBtn();
        // 20240507 LQS INS E
    };

    // 20240507 LQS INS S
    me.setBankSearchBtn = function () {
        if ($(".HDKShiharaiInput.radGinkoSonota").prop("checked")) {
            $(".HDKShiharaiInput.btnBankSearch").button("enable");
        } else {
            $(".HDKShiharaiInput.btnBankSearch").button("disable");
        }
    };
    // 20240507 LQS INS E
    me.toMoney = function (obj, objType, val) {
        if (!me.HDKAIKEI.KinsokuMojiCheck(obj, me.clsComFnc)) {
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
            $(".HDKShiharaiInput.body").dialog("close");
        }
    };
    // '**********************************************************************
    // '処 理 名：パターン対象部署
    // '関 数 名：radPatternBusyo_CheckedChanged
    // '処理説明：選択されたパターン対象部署によって部署コードの活性・不活性を
    // '		：切り替える
    // '**********************************************************************
    me.radPatternBusyo_CheckedChanged = function () {
        if ($(".HDKShiharaiInput.radPatternKyotu").prop("checked")) {
            $(".HDKShiharaiInput.txtPatternBusyo").val("");
            $(".HDKShiharaiInput.txtPatternBusyo").attr("disabled", true);
        } else if ($(".HDKShiharaiInput.radPatternBusyo").prop("checked")) {
            $(".HDKShiharaiInput.txtPatternBusyo").attr("disabled", false);
            $(".HDKShiharaiInput.txtPatternBusyo").trigger("focus");
        }
    };
    me.FormEnabled = function (blnEnabled) {
        $(".HDKShiharaiInput.txtKeiriSyoriDT").attr("disabled", true);
        $(".HDKShiharaiInput.txtKeiriSyoriDT").datepicker("disable");
        if (
            blnEnabled &&
            $.trim($(".HDKShiharaiInput.txtKeiriSyoriDT").val()) != ""
        ) {
            $(".HDKShiharaiInput.txtKeiriSyoriDT").attr("disabled", false);
            $(".HDKShiharaiInput.txtKeiriSyoriDT").datepicker("enable");
        }
        $(".HDKShiharaiInput.ddlPatternSel").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtCopySyohyNo").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.btnCopySyohy").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtZeikm_GK").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtTekyo").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtLKamokuCD").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtLKomokuCD").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.btnLKamokuSearch").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtLBusyoCD").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.btnLBusyoSearch").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.ddlRKamokuCD").attr("disabled", !blnEnabled);
        if (!blnEnabled) {
            $(".HDKShiharaiInput.ddlRKomokuCD").attr("disabled", !blnEnabled);
        } else if (
            me.PatternID == me.HDKAIKEI.CONST_ADMIN_PTN_NO ||
            me.PatternID == me.HDKAIKEI.CONST_HONBU_PTN_NO
        ) {
            $(".HDKShiharaiInput.ddlRKomokuCD").attr("disabled", !blnEnabled);
        }
        // if (!blnEnabled) {
        $(".HDKShiharaiInput.txtRBusyoCD").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.btnRBusyoSearch").attr("disabled", !blnEnabled);
        // }
        $(".HDKShiharaiInput.txtKensakuCD").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.ddlLSyohizeiKbn").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.ddlRSyohizeiKbn").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtTorihikiHasseibi").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtTorihikiHasseibi").datepicker(
            blnEnabled ? "enable" : "disable"
        );
        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpGinko']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtSonotaShiten").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpJiki']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtJikiDate").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtJikiDate").datepicker(
            blnEnabled ? "enable" : "disable"
        );
        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpSyubetu']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtKouzaNO").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtKouzaNM").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.btnTorihikiSearch").attr("disabled", !blnEnabled);
    };

    function jqgridDataShow(copyClear) {
        //後処理
        var copyNOClear = copyClear == undefined ? true : copyClear;
        //一覧に表示する
        // var objDs = result["data"]["IchiranTbl"];
        //合計件数、合計金額、合計消費税額を計算する
        var lngKingaku = 0,
            lngSyohizei = 0;
        $(me.grid_id).jqGrid("clearGridData");
        me.selectedRow = {};

        var data = {
            lblSyohy_no: copyNOClear
                ? $(".HDKShiharaiInput.lblSyohy_no")
                      .val()
                      .replace(me.blankReplace, "")
                : $(".HDKShiharaiInput.txtCopySyohyNo")
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
            me.btnClear_Click(true, copyNOClear);
            //合計件数、合計金額、合計消費税額を表示する
            me.toMoney($(".HDKShiharaiInput.lblKensu"), "text", objDs.length);
            me.toMoney(
                $(".HDKShiharaiInput.lblZeikomiGoukei"),
                "text",
                lngKingaku
            );
            me.toMoney(
                $(".HDKShiharaiInput.lblSyohizeiGoukei"),
                "text",
                lngSyohizei
            );
            //10行の場合は追加ボタンを不活性にする
            var rowNum = $(me.grid_id).jqGrid("getGridParam", "records");
            // 20241125 lhb upd s
            // if (rowNum >= 10) {
            if (rowNum >= 99) {
                // 20241125 lhb upd e
                $(".HDKShiharaiInput.btnAdd").button("disable");
            } else if (rowNum == 0) {
                $(".HDKShiharaiInput.btnAllDelete").button("disable");
                $(".HDKShiharaiInput.btnKakutei").button("disable");
            } else {
                $(".HDKShiharaiInput.btnKakutei").button("enable");
                $(".HDKShiharaiInput.btnAllDelete").button("enable");
            }
            // コピー元証憑№h表示の場合
            if (!copyNOClear) {
                $(".HDKShiharaiInput.btnAllDelete").button("disable");
                $(".HDKShiharaiInput.btnKakutei").button("disable");
            }
            $(".HDKShiharaiInput.txtZeikm_GK").trigger("focus");
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    }

    me.DataFormSet = function (objdt, strNo) {
        if (strNo == "100") {
            if (
                $(".HDKShiharaiInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                $(".HDKShiharaiInput.fileDialog").button("enable");
                $(".HDKShiharaiInput.hasFileFlg").empty();
                if (me.clsComFnc.FncNv(objdt[0]["FILEFLG"]) == "") {
                    $(".HDKShiharaiInput.hasFileFlg").text("なし");
                } else {
                    $(".HDKShiharaiInput.hasFileFlg").text("あり");
                }
                $(".HDKShiharaiInput.lblSyohy_no").val(
                    me.clsComFnc.FncNv(objdt[0]["SYOHY_NO"]) +
                        me.clsComFnc.FncNv(objdt[0]["EDA_NO"])
                );
                // 隠し項目(行№)にセットする
                me.hidGyoNO = me.clsComFnc.FncNv(objdt[0]["GYO_NO"]);
            }
            // 20240606 LQS INS S
            if (
                $(".HDKShiharaiInput.txtCopySyohyNo")
                    .val()
                    .replace(me.blankReplace, "") != ""
            ) {
                // 隠し項目(行№)にセットする
                me.hidGyoNO = me.clsComFnc.FncNv(objdt[0]["GYO_NO"]);
            }
            // 20240606 LQS INS E

            me.toMoney(
                $(".HDKShiharaiInput.txtZeikm_GK"),
                "text",
                me.clsComFnc.FncNv(objdt[0]["ZEIKM_GK"])
            );
            me.toMoney(
                $(".HDKShiharaiInput.lblZeink_GK"),
                "label",
                me.clsComFnc.FncNv(objdt[0]["ZEINK_GK"])
            );
            me.toMoney(
                $(".HDKShiharaiInput.lblSyohizei"),
                "label",
                me.clsComFnc.FncNv(objdt[0]["SHZEI_GK"])
            );

            $(".HDKShiharaiInput.txtKeiriSyoriDT").val(
                me.clsComFnc.FncNv(objdt[0]["KEIRI_DT"])
            );
        }
        $(".HDKShiharaiInput.txtTekyo").val(
            me.clsComFnc
                .FncNv(objdt[0]["TEKYO"])
                .toString()
                .replace(/〜/g, "～")
        );
        $(".HDKShiharaiInput.txtLKamokuCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_KAMOK_CD"])
        );
        $(".HDKShiharaiInput.txtLKomokuCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_KOUMK_CD"])
        );
        $(".HDKShiharaiInput.lblLKamokuNM").val(
            me.clsComFnc.FncNv(objdt[0]["L_KAMOK_NM"])
        );
        $(".HDKShiharaiInput.lblLKomokuNM").val(
            me.clsComFnc.FncNv(objdt[0]["L_KOMOK_NM"])
        );
        $(".HDKShiharaiInput.txtLBusyoCD").val(
            me.clsComFnc.FncNv(objdt[0]["L_HASEI_KYOTN_CD"])
        );
        $(".HDKShiharaiInput.lblLbusyoNM").val(
            me.clsComFnc.FncNv(objdt[0]["L_BUSYO_NM"])
        );

        if (me.clsComFnc.FncNv(objdt[0]["R_KAMOK_CD"]) != "") {
            $(".HDKShiharaiInput.ddlRKamokuCD").val(
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
        me.fncRKamokuCDSetProc();

        // 20240408 LQS INS S
        // 社員
        if (
            $(".HDKShiharaiInput.ddlRKamokuCD").val() ==
            me.HDKAIKEI.TATEKAE_KAMOKU_CD
        ) {
            $(".HDKShiharaiInput.txtTatekaeSyaCD").val(
                me.clsComFnc.FncNv(objdt[0]["TATEKAE_SYA_CD"])
            );
            // 20241226 YIN UPS S
            // var syain = me.allSyain.find((el) => {
            //     return (
            //         el["SYAIN_NO"] == $(".HDKShiharaiInput.txtTatekaeSyaCD").val().trimEnd()
            //     );
            // });
            var syain = false;
            for (var i = 0; i < me.allSyain.length; i++) {
                if (
                    me.allSyain[i]["SYAIN_NO"] ==
                    $(".HDKShiharaiInput.txtTatekaeSyaCD").val().trimEnd()
                ) {
                    syain = me.allSyain[i];
                }
            }
            // 20241226 YIN UPS E
            if (syain) {
                $(".HDKShiharaiInput.lblTatekaeSyaNM").val(syain["SYAIN_NM"]);
            }
        } else {
            $(".HDKShiharaiInput.txtTatekaeSyaCD").val("");
            $(".HDKShiharaiInput.lblTatekaeSyaNM").val("");
        }
        // 20240408 LQS INS E

        if (me.clsComFnc.FncNv(objdt[0]["R_KOUMK_CD"]) != "") {
            $(".HDKShiharaiInput.ddlRKomokuCD").val(
                me.clsComFnc.FncNv(objdt[0]["R_KOUMK_CD"])
            );
        }
        $(".HDKShiharaiInput.txtRBusyoCD").val(
            me.clsComFnc.FncNv(objdt[0]["R_HASEI_KYOTN_CD"])
        );
        $(".HDKShiharaiInput.lblRbusyoNM").val(
            me.clsComFnc.FncNv(objdt[0]["R_BUSYO_NM"])
        );

        if (me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) != "") {
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").val(
                me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"])
            );
        } else {
            $(".HDKShiharaiInput.ddlLSyohizeiKbn").val("");
        }

        if (me.clsComFnc.FncNv(objdt[0]["L_KAZEI_KB"]) == "0000") {
            $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", true);
        } else {
            if (
                $(".HDKShiharaiInput.ddlLSyohizeiKbn").prop("disabled") == false
            ) {
                $(".HDKShiharaiInput.ddlLSyohizeiritu").attr("disabled", false);
            }
        }

        if (me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) != "") {
            $(".HDKShiharaiInput.ddlRSyohizeiKbn").val(
                me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"])
            );
        } else {
            $(".HDKShiharaiInput.ddlRSyohizeiKbn").val("");
        }

        if (me.clsComFnc.FncNv(objdt[0]["R_KAZEI_KB"]) == "0000") {
            $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", true);
        } else {
            if (
                $(".HDKShiharaiInput.ddlRSyohizeiKbn").prop("disabled") == false
            ) {
                $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", false);
            }
        }

        $(".HDKShiharaiInput.ddlLSyohizeiritu").val(
            me.clsComFnc.FncNv(objdt[0]["L_ZEI_RT_KB"])
        );
        $(".HDKShiharaiInput.ddlRSyohizeiritu").val(
            me.clsComFnc.FncNv(objdt[0]["R_ZEI_RT_KB"])
        );

        $(".HDKShiharaiInput.txtTorihikiHasseibi").val(
            me.clsComFnc.FncNv(objdt[0]["TORIHIKI_DT"])
        );

        $(".HDKShiharaiInput.txtKensakuCD").val(
            me.clsComFnc.FncNv(objdt[0]["TORIHIKISAKI_CD"])
        );
        $(".HDKShiharaiInput.lblKensakuNM").val(
            me.clsComFnc.FncNv(objdt[0]["TORIHIKISAKI_NAME"])
        );

        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpGinko']").prop(
            "checked",
            false
        );

        if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "1") {
            $(".HDKShiharaiInput.radHiroGinko").prop("checked", true);
            $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）");
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", true);
            $(".HDKShiharaiInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "2") {
            $(".HDKShiharaiInput.radMomijiGinko").prop("checked", true);
            $(".HDKShiharaiInput.txtSonotaGinko").val("もみじ");
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", true);
            $(".HDKShiharaiInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "3") {
            $(".HDKShiharaiInput.radShinyoKinko").prop("checked", true);
            //20240507 LQS UPD S
            // $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）信用金庫");
            $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）信金");
            //20240507 LQS UPD E
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", true);
            $(".HDKShiharaiInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else if (me.clsComFnc.FncNv(objdt[0]["GINKO_KB"]) == "9") {
            $(".HDKShiharaiInput.radGinkoSonota").prop("checked", true);
            $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", false);
            $(".HDKShiharaiInput.txtSonotaGinko").val(
                me.clsComFnc
                    .FncNv(objdt[0]["GINKO_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
            $(".HDKShiharaiInput.txtSonotaShiten").val(
                me.clsComFnc
                    .FncNv(objdt[0]["SHITEN_NM"])
                    .toString()
                    .replace(/〜/g, "～")
            );
        } else {
            $(".HDKShiharaiInput.radHiroGinko").prop("checked", true);
        }

        switch (me.clsComFnc.FncNv(objdt[0]["JIKI"])) {
            case "1":
                $(".HDKShiharaiInput.radJikiSokujitu").prop("checked", true);
                break;
            case "2":
                $(".HDKShiharaiInput.radJikiHiduke").prop("checked", true);
                //20240312 LQS INS S
                if (strNo !== "102") {
                    //20240312 LQS INS E
                    $(".HDKShiharaiInput.txtJikiDate").val(
                        me.clsComFnc.FncNv(objdt[0]["SHIHARAI_DT"])
                    );
                }
                break;
            case "3":
                $(".HDKShiharaiInput.radJikiYokugetu").prop("checked", true);
                break;
        }
        switch (me.clsComFnc.FncNv(objdt[0]["YOKIN_SYUBETU"])) {
            case "1":
                $(".HDKShiharaiInput.radSyubetuFutu").prop("checked", true);
                break;
            case "2":
                $(".HDKShiharaiInput.radSyubetuTouza").prop("checked", true);
                break;
            case "9":
                $(".HDKShiharaiInput.radSyubetuSonota").prop("checked", true);
                break;
        }
        $(".HDKShiharaiInput.txtKouzaNO").val(
            me.clsComFnc.FncNv(objdt[0]["KOUZA_NO"])
        );
        $(".HDKShiharaiInput.txtKouzaNM").val(
            me.clsComFnc.FncNv(objdt[0]["KOUZA_KN"])
        );

        if (strNo == "103") {
            if (objdt[0]["TAISYO_BUSYO_KB"] == "1") {
                $(".HDKShiharaiInput.radPatternKyotu").prop("checked", true);
            } else {
                $(".HDKShiharaiInput.radPatternBusyo").prop("checked", true);
                $(".HDKShiharaiInput.txtPatternBusyo").val(
                    me.clsComFnc.FncNv(objdt[0]["TAISYO_BUSYO_CD"])
                );
            }
            $(".HDKShiharaiInput.txtPatternNM").val(
                me.clsComFnc.FncNv(objdt[0]["PATTERN_NM"])
            );
        }
        // 20240507 LQS INS S
        me.setBankSearchBtn();
        // 20240507 LQS INS E
    };
    me.ShiharaiHouhou2Enabled = function (blnEnabled) {
        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpGinko']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtSonotaGinko").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtSonotaShiten").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput input[name='HDKShiharaiInput_grpSyubetu']").attr(
            "disabled",
            !blnEnabled
        );
        $(".HDKShiharaiInput.txtKouzaNO").attr("disabled", !blnEnabled);
        $(".HDKShiharaiInput.txtKouzaNM").attr("disabled", !blnEnabled);
    };
    me.subSyokiDataSet = function () {
        // $(".HDKShiharaiInput.ddlRKamokuCD").get(0).selectedIndex = 2;
        $(".HDKShiharaiInput.ddlRKamokuCD").get(0).selectedIndex = 0;
        // 貸方科目セット時の処理を行う
        me.fncRKamokuCDSetProc();

        // 貸方消費税区分は対象外を選択
        $(".HDKShiharaiInput.ddlRSyohizeiKbn").val("0000");
        $(".HDKShiharaiInput.ddlRSyohizeiritu").val("90");
        $(".HDKShiharaiInput.ddlRSyohizeiritu").attr("disabled", true);
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
        if ($(".HDKShiharaiInput.radJikiHiduke").prop("checked")) {
            if (
                $(".HDKShiharaiInput.radJikiHiduke").prop("disabled") == false
            ) {
                $(".HDKShiharaiInput.txtJikiDate").attr("disabled", false);
                $(".HDKShiharaiInput.txtJikiDate").datepicker("enable");
            }

            // 20220121 lqs UPD S
            if (radiochange) {
                // 支払予定日をセットする処理
                $(".HDKShiharaiInput.txtJikiDate").val(
                    new Date(dtCreateDate).Format("yyyy/MM/dd")
                );
            }
            // 20220121 lqs UPD E
        } else if ($(".HDKShiharaiInput.radJikiSokujitu").prop("checked")) {
            $(".HDKShiharaiInput.txtJikiDate").attr("disabled", true);
            $(".HDKShiharaiInput.txtJikiDate").datepicker("disable");
            $(".HDKShiharaiInput.txtJikiDate").val(
                new Date(dtCreateDate).Format("yyyy/MM/dd")
            );
        } else if ($(".HDKShiharaiInput.radJikiYokugetu").prop("checked")) {
            $(".HDKShiharaiInput.txtJikiDate").attr("disabled", true);
            $(".HDKShiharaiInput.txtJikiDate").datepicker("disable");
            // 支払予定日がDB登録済なら１か月加算しないよう
            if (me.hidShiharaiDate != "") {
                $(".HDKShiharaiInput.txtJikiDate").val(
                    new Date(dtCreateDate).Format("yyyy/MM/dd")
                );
            } else {
                var nextMonth = new Date(dtCreateDate).getMonth() + 1;
                var tmpDate = new Date(dtCreateDate).setMonth(nextMonth);
                $(".HDKShiharaiInput.txtJikiDate").val(
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
    //20240312 LQS UPD S
    // me.radJikiHiduke_CheckedChanged = function () {
    // 	me.subradJikiProc(true);
    // };
    // 20241226 YIN UPS S
    // me.radJikiHiduke_CheckedChanged = function (val = true) {
    me.radJikiHiduke_CheckedChanged = function (val) {
        val = typeof val === "undefined" ? true : val;
        // 20241226 YIN UPS E
        me.subradJikiProc(val);
    };
    //20240312 LQS UPD E
    me.DenpyoInputButtonVisible = function (blnVisible) {
        if (blnVisible) {
            $(".HDKShiharaiInput.fileDialog").show();
            $(".HDKShiharaiInput.btnAdd").show();
            $(".HDKShiharaiInput.btnUpdate").show();
            $(".HDKShiharaiInput.btnDelete").show();
            $(".HDKShiharaiInput.btnSyuseiMaeDisp").show();
            $(".HDKShiharaiInput.btnClear").show();
            $(".HDKShiharaiInput.btnAllDelete").show();
            $(".HDKShiharaiInput.btnKakutei").show();

            $(".HDKShiharaiInput.btnPatternTrk").show();
        } else {
            $(".HDKShiharaiInput.fileDialog").hide();
            $(".HDKShiharaiInput.btnAdd").hide();
            $(".HDKShiharaiInput.btnUpdate").hide();
            $(".HDKShiharaiInput.btnDelete").hide();
            $(".HDKShiharaiInput.btnSyuseiMaeDisp").hide();
            $(".HDKShiharaiInput.btnClear").hide();
            $(".HDKShiharaiInput.btnAllDelete").hide();
            $(".HDKShiharaiInput.btnKakutei").hide();

            $(".HDKShiharaiInput.btnPatternTrk").hide();
        }
        if (
            $(".HDKShiharaiInput.btnSyuseiMaeDisp").css("display") == "none" &&
            $(".HDKShiharaiInput.btnSaishinDisp").css("display") == "none"
        ) {
            $(".HDKShiharaiInput.HMS-button-pane.first-row-div").hide();
        }
    };
    me.PatternInputButtonVisible = function (blnVisible) {
        if (blnVisible) {
            $(".HDKShiharaiInput.btnPtnDelete").show();
            $(".HDKShiharaiInput.btnPtnInsert").show();
            $(".HDKShiharaiInput.btnPtnUpdate").show();
        } else {
            $(".HDKShiharaiInput.btnPtnDelete").hide();
            $(".HDKShiharaiInput.btnPtnInsert").hide();
            $(".HDKShiharaiInput.btnPtnUpdate").hide();
        }
    };
    me.MemoSet = function (data) {
        var memoStr = "";
        for (var i = 0; i < data.length; i++) {
            var one = data[i];
            memoStr += one["MEISYOU"];
        }

        $(".HDKShiharaiInput.lblMemo").text(memoStr);
    };

    me.DpyInpNewButtonEnabled = function (intMode) {
        intMode = parseInt(intMode);
        switch (intMode) {
            case 1:
                // 新規画面表示時
                $(".HDKShiharaiInput.btnAdd").button("enable");
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                $(".HDKShiharaiInput.btnAllDelete").attr("disabled", true);
                $(".HDKShiharaiInput.btnClear").attr("disabled", false);
                $(".HDKShiharaiInput.btnPatternTrk").attr("disabled", false);
                $(".HDKShiharaiInput.btnPtnDelete").attr("disabled", true);
                break;
            case 2:
                // 修正画面表示時
                $(".HDKShiharaiInput.btnAdd").button("enable");
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                $(".HDKShiharaiInput.btnAllDelete").attr("disabled", false);
                $(".HDKShiharaiInput.btnKakutei").attr("disabled", false);
                $(".HDKShiharaiInput.btnClear").attr("disabled", false);
                $(".HDKShiharaiInput.btnPatternTrk").attr("disabled", false);
                break;
            case 3:
                // 一覧選択時
                // コピー元証憑№表示
                if (
                    $(".HDKShiharaiInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    $(".HDKShiharaiInput.btnAllDelete").button("disable");
                    $(".HDKShiharaiInput.btnKakutei").button("disable");
                } else {
                    $(".HDKShiharaiInput.btnAllDelete").button("enable");
                    $(".HDKShiharaiInput.btnKakutei").button("enable");
                }
                $(".HDKShiharaiInput.btnUpdate").button("enable");
                $(".HDKShiharaiInput.btnDelete").button("enable");
                $(".HDKShiharaiInput.btnAdd").button("enable");
                var rowcount = $(me.grid_id).jqGrid("getGridParam", "reccount");
                // 20241125 lhb upd s
                // if (rowcount >= 10) {
                if (rowcount >= 99) {
                    // 20241125 lhb upd e
                    $(".HDKShiharaiInput.btnAdd").button("disable");
                } else if (
                    rowcount == 1 &&
                    $(".HDKShiharaiInput.lblSyohy_no")
                        .val()
                        .replace(me.blankReplace, "") == ""
                ) {
                    $(".HDKShiharaiInput.btnDelete").button("disable");
                }

                $(".HDKShiharaiInput.btnClear").attr("disabled", false);
                break;
            case 4:
                // クリア処理
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                var rowcount = $(me.grid_id).jqGrid("getGridParam", "reccount");
                // 20241125 lhb upd s
                // if (rowcount < 10) {
                if (rowcount < 99) {
                    // 20241125 lhb upd e
                    $(".HDKShiharaiInput.btnAdd").button("enable");
                }
                break;
            case 8:
                // 一部参照モード
                $(".HDKShiharaiInput.btnAdd").button("disable");
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                $(".HDKShiharaiInput.btnAllDelete").attr("disabled", false);
                $(".HDKShiharaiInput.btnKakutei").attr("disabled", false);
                $(".HDKShiharaiInput.btnClear").attr("disabled", true);
                break;
            case 9:
                // 参照モードの場合
                $(".HDKShiharaiInput.btnAdd").button("disable");
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                $(".HDKShiharaiInput.btnAllDelete").attr("disabled", true);
                $(".HDKShiharaiInput.btnKakutei").attr("disabled", false);
                $(".HDKShiharaiInput.btnClear").attr("disabled", true);
                break;
            case 99:
                // エラーの場合
                $(".HDKShiharaiInput.btnAdd").button("disable");
                $(".HDKShiharaiInput.btnUpdate").button("disable");
                $(".HDKShiharaiInput.btnDelete").button("disable");
                $(".HDKShiharaiInput.btnAllDelete").attr("disabled", true);
                $(".HDKShiharaiInput.btnKakutei").attr("disabled", true);
                $(".HDKShiharaiInput.btnClear").attr("disabled", true);
                $(".HDKShiharaiInput.btnPatternTrk").attr("disabled", true);
                $(".HDKShiharaiInput.btnSyuseiMaeDisp").attr("disabled", true);
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
            $(".HDKShiharaiInput.lblLbusyoNM").val(foundNM);
        } else {
            $(".HDKShiharaiInput.lblRbusyoNM").val(foundNM);
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
    // 20240312 LQS UPD S
    // me.subFormClear = function (blnClear, blnCopyShohyClear, blnPatternClear) {
    me.subFormClear = function (
        blnClear,
        blnCopyShohyClear,
        blnPatternClear,
        isPatternSel
    ) {
        // 20240312 LQS UPD E
        $(".HDKShiharaiInput.fileDialog").button("disable");
        $(".HDKShiharaiInput.hasFileFlg").empty();
        $(".HDKShiharaiInput.hasFileFlg").text("なし");
        blnClear = blnClear == undefined ? false : blnClear;

        blnCopyShohyClear =
            blnCopyShohyClear == undefined ? true : blnCopyShohyClear;

        blnPatternClear = blnPatternClear == undefined ? true : blnPatternClear;
        // 20240312 LQS UPD S
        isPatternSel = isPatternSel == undefined ? false : isPatternSel;
        // if (blnClear == false && blnCopyShohyClear) {
        if (blnClear == false && blnCopyShohyClear && !isPatternSel) {
            // 20240312 LQS UPD E
            $(".HDKShiharaiInput.lblSyohy_no").val("");
        }
        if (blnCopyShohyClear) {
            $(".HDKShiharaiInput.txtCopySyohyNo").val("");
        }
        if (blnPatternClear) {
            $(".HDKShiharaiInput.txtKeiriSyoriDT").val("");
        }
        $(".HDKShiharaiInput.txtZeikm_GK").val("");
        $(".HDKShiharaiInput.lblZeink_GK").text("");
        $(".HDKShiharaiInput.lblSyohizei").text("");

        $(".HDKShiharaiInput.txtTekyo").val("");
        $(".HDKShiharaiInput.txtLKamokuCD").val("");
        $(".HDKShiharaiInput.txtLKomokuCD").val("");
        $(".HDKShiharaiInput.lblLKamokuNM").val("");
        $(".HDKShiharaiInput.lblLKomokuNM").val("");
        $(".HDKShiharaiInput.txtLBusyoCD").val("");
        $(".HDKShiharaiInput.lblLbusyoNM").val("");
        $(".HDKShiharaiInput.txtRBusyoCD").val("");
        $(".HDKShiharaiInput.lblRbusyoNM").val("");

        if ($(".HDKShiharaiInput.ddlRKamokuCD").prop("selectedIndex") > -1) {
            $(".HDKShiharaiInput.ddlRKamokuCD").get(0).selectedIndex = 0;
        }

        if (blnClear == true) {
            me.fncRKamokuCDSetProc();
        }

        $(".HDKShiharaiInput.txtKensakuCD").val("");
        $(".HDKShiharaiInput.lblKensakuNM").val("");
        $(".HDKShiharaiInput.txtTorihikiHasseibi").val("");
        $(".HDKShiharaiInput.txtSonotaGinko").val("（GD）");
        $(".HDKShiharaiInput.txtSonotaShiten").val("");
        $(".HDKShiharaiInput.radHiroGinko").prop("checked", true);
        $(".HDKShiharaiInput.radJikiSokujitu").prop("checked", true);
        $(".HDKShiharaiInput.txtJikiDate").val("");
        $(".HDKShiharaiInput.radSyubetuTouza").prop("checked", true);
        $(".HDKShiharaiInput.txtKouzaNO").val("");
        $(".HDKShiharaiInput.txtKouzaNM").val("");
        $(".HDKShiharaiInput.radPatternKyotu").prop("checked", true);
        $(".HDKShiharaiInput.txtPatternBusyo").attr("disabled", true);
        $(".HDKShiharaiInput.txtPatternBusyo").val("");
        $(".HDKShiharaiInput.txtPatternNM").val("");
        if (blnClear == false) {
            $(".HDKShiharaiInput.lblMemo").text("");
        }
        // 20240507 LQS INS S
        me.setBankSearchBtn();
        // 20240507 LQS INS E
    };
    me.fncRKamokuCDSetProc = function () {
        var ddlRKamokuCDVal = $(".HDKShiharaiInput.ddlRKamokuCD").val();
        if (ddlRKamokuCDVal != null && ddlRKamokuCDVal != "0") {
            // 貸方科目コードにセット
            var strKamokuCD = $(".HDKShiharaiInput.ddlRKamokuCD").val();
            $(".HDKShiharaiInput.ddlRKomokuCD").empty();
            var selRKomoku = me.RKomoku.filter(function (one) {
                return (
                    one["SUCHI1"] == strKamokuCD.substring(1) &&
                    one["MEISYOUCD"] == strKamokuCD.substring(0, 1)
                );
            });
            for (var index = 0; index < selRKomoku.length; index++) {
                var opt = selRKomoku[index];
                $("<option></option>")
                    .val(opt["SUCHI2"])
                    .text(opt["MOJI1"] == null ? "" : opt["MOJI1"])
                    .appendTo(".HDKShiharaiInput.ddlRKomokuCD");
            }
        } else {
            // 貸方補助科目コードにセット
            $(".HDKShiharaiInput.ddlRKomokuCD").empty();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HDKShiharaiInput.ddlRKomokuCD");
        }

        if ($(".HDKShiharaiInput.ddlRKomokuCD")[0].options.length == 1) {
            $(".HDKShiharaiInput.ddlRKomokuCD").attr("disabled", true);
        } else {
            $(".HDKShiharaiInput.ddlRKomokuCD").attr("disabled", false);
        }
        // 20240408 LQS INS S
        if (ddlRKamokuCDVal === me.HDKAIKEI.TATEKAE_KAMOKU_CD) {
            $(".HDKShiharaiInput.txtTatekaeSyaCD").val("");
            $(".HDKShiharaiInput.lblTatekaeSyaNM").val("");
            $(".HDKShiharaiInput.tatekae-syain").show();
        } else {
            $(".HDKShiharaiInput.txtTatekaeSyaCD").val("");
            $(".HDKShiharaiInput.lblTatekaeSyaNM").val("");
            $(".HDKShiharaiInput.tatekae-syain").hide();
        }
        // 20240408 LQS INS E
    };

    me.ShiharaiHouhouClear = function () {
        $(".HDKShiharaiInput.txtSonotaGinko").val("");
        $(".HDKShiharaiInput.txtSonotaShiten").val("");
        $(".HDKShiharaiInput.txtKouzaNO").val("");
        $(".HDKShiharaiInput.txtKouzaNM").val("");
    };
    me.ShiharaiHouhou2Clear = function () {
        $(".HDKShiharaiInput.txtSonotaGinko").val("");
        $(".HDKShiharaiInput.txtSonotaShiten").val("");
        $(".HDKShiharaiInput.txtKouzaNO").val("");
    };

    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divkuCD = "";
        var divNM = "";
        var divSubNM = "";
        var frmId = "";
        var title = "";
        var $txtSearchCD = undefined;
        var $txtSearchkuCD = undefined;
        var $txtSearchNM = undefined;
        var cd = "RtnCD";

        var divSYOHY_NO15 = "";
        var divEDA_NO = "";
        var divGYO_NO = "";
        var divEditFlag = "";

        switch (searchButton) {
            case "HDKAttachment":
                //添付ファイル
                dialogId = "HDKAttachmentDialogDiv";
                divSYOHY_NO15 = "SYOHY_NO15";
                divEDA_NO = "EDA_NO";
                divGYO_NO = "GYO_NO";
                divFrom_View = "From_View";
                divEditFlag = "MAX_SYORI_FLG";
                frmId = "HDKAttachment";
                title = "添付ファイル";
                break;
            case "btnLKamokuSearch":
                //科目検索
                dialogId = "HDKKamokuSearchDialogDiv";
                $txtSearchCD = $(".HDKShiharaiInput.txtLKamokuCD");
                $txtSearchkuCD = $(".HDKShiharaiInput.txtLKomokuCD");
                $txtSearchNM = $(".HDKShiharaiInput.lblLKamokuNM");
                $txtSearchSubNM = $(".HDKShiharaiInput.lblLKomokuNM");
                divCD = "KamokuCD";
                divkuCD = "KoumkuCD";
                divNM = "KamokuNM";
                divSubNM = "KamokuSubNM";
                frmId = "HDKKamokuSearch";
                title = "科目マスタ検索";
                break;
            // 20240507 LQS INS S
            case "btnBankSearch":
                //金融機関マスタ
                dialogId = "HDKBankSearchDialogDiv";
                $txtSearchNM = $(".HDKShiharaiInput.txtSonotaGinko");
                $txtSearchSubNM = $(".HDKShiharaiInput.txtSonotaShiten");
                divNM = "BankNM";
                divSubNM = "BranchNM";
                frmId = "HDKBankSearch";
                title = "金融機関マスタ検索";
                break;
            // 20240507 LQS INS E
            case "btnLBusyoSearch":
            case "btnRBusyoSearch":
                //部署検索
                dialogId = "HDKBusyoSearchDialogDiv";
                $txtSearchCD =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HDKShiharaiInput.txtRBusyoCD")
                        : $(".HDKShiharaiInput.txtLBusyoCD");
                $txtSearchNM =
                    searchButton == "btnRBusyoSearch"
                        ? $(".HDKShiharaiInput.lblRbusyoNM")
                        : $(".HDKShiharaiInput.lblLbusyoNM");
                divCD = "BusyoCD";
                divNM = "BusyoNM";
                frmId = "HDKBusyoSearch";
                title = "部署マスタ検索";
                cd = "RtnBusyoCD";
                break;
            // 20240408 LQS INS S
            case "btnTatekaeSyaSearch":
                dialogId = "HDKSyainSearchDialogDiv";
                $txtSearchCD = $(".HDKShiharaiInput.txtTatekaeSyaCD");
                $txtSearchNM = $(".HDKShiharaiInput.lblTatekaeSyaNM");
                divCD = "SyainCD";
                divNM = "SyainNM";
                frmId = "HDKSyainSearch";
                title = "社員マスタ検索";
                break;
            // 20240408 LQS INS E
            case "btnTorihikiSearch":
                //取引先
                dialogId = "HDKTorihikisakiSearchDialogDiv";
                $txtSearchCD = $(".HDKShiharaiInput.txtKensakuCD");
                $txtSearchNM = $(".HDKShiharaiInput.lblKensakuNM");
                divCD = "KensakuCD";
                divNM = "KensakuNM";
                frmId = "HDKTorihikisakiSearch";
                title = "取引先マスタ検索";
                break;
            default:
        }

        var width = me.ratio === 1.5 ? 700 : 720;
        var height = me.ratio === 1.5 ? 530 : 630;
        var $rootDiv = $(".HDKShiharaiInput.HDKAIKEI-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        // 20240408 LQS INS S
        if (searchButton == "btnTatekaeSyaSearch") {
            $("<div></div>").attr("id", "syain").insertAfter($rootDiv).hide();
            var $syainSearch = $rootDiv.parent().find("#" + "syain");
            $syainSearch.val("syain");
        }
        // 20240408 LQS INS E
        var $RtnCD = $rootDiv.parent().find("#" + cd);
        if (searchButton == "HDKAttachment") {
            width = me.ratio === 1.5 ? 1085 : 1150;
            height = me.ratio === 1.5 ? 535 : 695;
            $("<div></div>")
                .attr("id", divSYOHY_NO15)
                .insertAfter($rootDiv)
                .hide();
            $("<div></div>").attr("id", divEDA_NO).insertAfter($rootDiv).hide();
            $("<div></div>").attr("id", divGYO_NO).insertAfter($rootDiv).hide();
            $("<div></div>")
                .attr("id", divFrom_View)
                .insertAfter($rootDiv)
                .hide();
            $("<div></div>")
                .attr("id", divEditFlag)
                .insertAfter($rootDiv)
                .hide();
            var $SYOHY_NO = $rootDiv.parent().find("#" + divSYOHY_NO15);
            var $EDA_NO = $rootDiv.parent().find("#" + divEDA_NO);
            var $GYO_NO = $rootDiv.parent().find("#" + divGYO_NO);
            var $From_View = $rootDiv.parent().find("#" + divFrom_View);
            var $EditFlag = $rootDiv.parent().find("#" + divEditFlag);
            $SYOHY_NO.html(
                $(".HDKShiharaiInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, "")
                    .substring(0, 15)
            );
            $EDA_NO.html(
                $(".HDKShiharaiInput.lblSyohy_no")
                    .val()
                    .replace(me.blankReplace, "")
                    .substring(15, 17)
            );
            $GYO_NO.html(me.hidGyoNO);
            $From_View.html("HDKShiharaiInput");
            $EditFlag.html(
                me.hidMode == "8" || me.hidMode == "9" ? true : false
            );
            // 20240507 LQS INS S
        } else if (searchButton == "btnBankSearch") {
            $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
            $("<div></div>").attr("id", divSubNM).insertAfter($rootDiv).hide();
            var $SearchNM = $rootDiv.parent().find("#" + divNM);
            var $SearchSubNM = $rootDiv.parent().find("#" + divSubNM);
            $SearchNM.val($.trim($txtSearchNM.val()));
            $SearchSubNM.val($.trim($txtSearchSubNM.val()));
            // 20240507 LQS INS E
        } else {
            $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
            if (searchButton == "btnLKamokuSearch") {
                $("<div></div>")
                    .attr("id", divkuCD)
                    .insertAfter($rootDiv)
                    .hide();
            }
            $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
            var $SearchCD = $rootDiv.parent().find("#" + divCD);
            var $SearchNM = $rootDiv.parent().find("#" + divNM);
            var $SearchSubNM = $rootDiv.parent().find("#" + divNM);
            var $SearchkuCD = undefined;
            $SearchCD.val($.trim($txtSearchCD.val()));
            if (searchButton == "btnLKamokuSearch") {
                $SearchkuCD = $rootDiv.parent().find("#" + divkuCD);
            }
        }

        $(".HDKShiharaiInput.txtTekyo").trigger("focus");

        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: height,
            width: width,
            resizable: searchButton == "HDKAttachment" ? true : false,
            close: function () {
                if (searchButton == "HDKAttachment") {
                    $RtnCD.remove();
                    $SYOHY_NO.remove();
                    $EDA_NO.remove();
                    $GYO_NO.remove();
                    $From_View.remove();
                    $EditFlag.remove();
                    var url =
                        me.sys_id + "/" + me.id + "/" + "fncSelShiwakeData";
                    var data = {
                        SYOHY_NO: $(".HDKShiharaiInput.lblSyohy_no")
                            .val()
                            .replace(me.blankReplace, "")
                            .substring(0, 15),
                        EDA_NO: $(".HDKShiharaiInput.lblSyohy_no")
                            .val()
                            .replace(me.blankReplace, "")
                            .substring(15, 17),
                        GYO_NO: me.hidGyoNO,
                    };
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        if (!result["result"]) {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            return;
                        }

                        if (
                            result["data"]["NewNoTbl"].length > 0 &&
                            me.clsComFnc.FncNv(
                                result["data"]["NewNoTbl"][0]["FILEFLG"]
                            ) == ""
                        ) {
                            $(".HDKShiharaiInput.hasFileFlg").text("なし");
                        } else {
                            $(".HDKShiharaiInput.hasFileFlg").text("あり");
                        }
                    };
                    me.ajax.send(url, data, 0);
                    // 20240507 LQS INS S
                } else if (searchButton == "btnBankSearch") {
                    if ($RtnCD.html() == 1) {
                        $txtSearchNM.val($SearchNM.html());
                        $txtSearchSubNM.val($SearchSubNM.html());
                    }

                    $RtnCD.remove();
                    $SearchNM.remove();
                    $SearchSubNM.remove();

                    $(".HDKShiharaiInput." + searchButton).trigger("focus");
                    // 20240507 LQS INS E
                } else {
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
                        if (searchButton == "btnLKamokuSearch") {
                            $txtSearchkuCD.val($SearchkuCD.html());
                            $txtSearchSubNM.val($SearchSubNM.html());

                            me.txtLKamokuCD_TextChanged(
                                $(".HDKShiharaiInput.txtLKamokuCD"),
                                changeFlag
                            );
                        }
                    }

                    $RtnCD.remove();
                    $SearchCD.remove();
                    $SearchNM.remove();
                    if (searchButton == "btnLKamokuSearch") {
                        $SearchkuCD.remove();
                        $SearchSubNM.remove();
                    } else {
                        $(".HDKShiharaiInput." + searchButton).trigger("focus");
                    }
                    // 20240408 LQS INS S
                    if (searchButton == "btnTatekaeSyaSearch") {
                        $syainSearch.remove();
                    }
                    // 20240408 LQS INS E
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
    var o_HDKAIKEI_HDKShiharaiInput = new HDKAIKEI.HDKShiharaiInput();
    o_HDKAIKEI_HDKAIKEI.HDKShiharaiInput = o_HDKAIKEI_HDKShiharaiInput;
    if (o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch) {
        o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
        o_HDKAIKEI_HDKShiharaiInput.HDKDenpyoSearch =
            o_HDKAIKEI_HDKAIKEI.HDKDenpyoSearch;
    }

    if (o_HDKAIKEI_HDKAIKEI.HDKPatternSearch) {
        o_HDKAIKEI_HDKAIKEI.HDKPatternSearch.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
        o_HDKAIKEI_HDKShiharaiInput.HDKPatternSearch =
            o_HDKAIKEI_HDKAIKEI.HDKPatternSearch;
    }

    if (o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin) {
        o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
        o_HDKAIKEI_HDKShiharaiInput.HDKReOut4ZenGin =
            o_HDKAIKEI_HDKAIKEI.HDKReOut4ZenGin;
    }
    if (o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC) {
        o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
        o_HDKAIKEI_HDKShiharaiInput.HDKReOut4OBC =
            o_HDKAIKEI_HDKAIKEI.HDKReOut4OBC;
    }
    //20240318 lujunxia ins s
    if (o_HDKAIKEI_HDKAIKEI.HDKOut4ZenGin) {
        o_HDKAIKEI_HDKAIKEI.HDKOut4ZenGin.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
    }
    if (o_HDKAIKEI_HDKAIKEI.HDKOut4OBC) {
        o_HDKAIKEI_HDKAIKEI.HDKOut4OBC.HDKShiharaiInput =
            o_HDKAIKEI_HDKShiharaiInput;
    }
    //20240318 lujunxia ins e
    o_HDKAIKEI_HDKShiharaiInput.load();
});
