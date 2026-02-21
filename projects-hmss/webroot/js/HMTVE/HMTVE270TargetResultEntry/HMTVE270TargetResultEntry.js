/**
 * 履歴：
 * -------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                            内容                                 担当
 * YYYYMMDD           #ID                                    XXXXXX                               FCSDL
 * 20240326    		受入検証.xlsx NO2     					車種を追加してください             		  LHB
 * 20240611    		202406_データ集計システム_CX-80追加        CX-80追加            		 		  LHB
 * 20240710               BUG                    内容が完全に表示されるようにサイズ変更                YIN
 * 20240712    		CX-80追加判断                           CX-80追加判断            		 	     LHB
 * 20251118         202511_データ集計システム_機能追加要望   目標と実績＿改修イメージ                  caina
 * -------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE270TargetResultEntry");

HMTVE.HMTVE270TargetResultEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.hmtve = new HMTVE.HMTVE();
    // 20240712 LHB INS S
    me.hidden = false;
    // 20240712 LHB INS E

    // ========== 変数 start ==========

    me.id = "HMTVE270TargetResultEntry";
    me.sys_id = "HMTVE";
    //insert or update
    me.hidCreateTime = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //閉じるボタン
    me.controls.push({
        id: ".HMTVE270TargetResultEntry.btnClose",
        type: "button",
        handle: "",
    });

    //登録ボタン
    me.controls.push({
        id: ".HMTVE270TargetResultEntry.btnLogin",
        type: "button",
        handle: "",
    });

    //削除ボタン
    me.controls.push({
        id: ".HMTVE270TargetResultEntry.btnDel",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmtve.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmtve.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    // lost focus
    $(".HMTVE270TargetResultEntry.lostFocusInput").on("blur", function () {
        me.lostFocus1();
    });
    // input 数値しか入力できない
    $(".HMTVE270TargetResultEntry input").numeric({
        point: true,
    });
    //閉じるボタン
    $(".HMTVE270TargetResultEntry.btnClose").click(function () {
        $(".HMTVE270TargetResultEntry.HMTVE-content").dialog("close");
    });
    //登録ボタンのイベント
    $(".HMTVE270TargetResultEntry.btnLogin").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "目標と実績データを更新します。よろしいですか？"
        );
    });
    //削除ボタンのイベント
    $(".HMTVE270TargetResultEntry.btnDel").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDel_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "目標と実績データを削除します。よろしいですか？"
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
        base_init_control();
        if ($(window).height() <= 739) {
            //垂直スクロールバー
            $(".HMTVE270TargetResultEntry.HMTVE-content").css(
                "overflow-y",
                "scroll"
            );
        }
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
        if (gdmz.SessionPatternID) {
            me.before_close = function () {};
            $(".HMTVE270TargetResultEntry.HMTVE-content").dialog({
                autoOpen: false,
                width: $(window).height() <= 739 ? 1190 : 1175,
                // 20240710 YIN UPD S
                // height: $(window).height() <= 739 ? 720 : 759,
                height:
                    $(window).height() <= 739
                        ? me.ratio === 1.5
                            ? 555
                            : 700
                        : 759,
                // 20240710 YIN UPD E
                modal: true,
                title: "目標と実績_登録",
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".HMTVE270TargetResultEntry.HMTVE-content").remove();
                },
            });
            $(".HMTVE270TargetResultEntry.HMTVE-content").dialog("open");

            //システム日付を取得する
            var sysDate = new Date();
            //処理年月を設定する
            var sysYear = sysDate.getFullYear();
            var sysMonth = parseInt(sysDate.getMonth()) + 1;
            // 20240712 LHB INS S
            if ($("#isexit").html() == "true") {
                $(".HMTVE270TargetResultEntry .CX80_TRK_DAISU").hide();
                me.hidden = true;
            }
            // 20240712 LHB INS E
            //期をセットする
            $(".HMTVE270TargetResultEntry.txtbDuring").val(sysYear);
            //コンボリストの月をセットする
            //対象年月(月)のコンボリストに1～12をセットする
            for (var i = 1; i <= 12; i++) {
                $("<option></option>")
                    .val(i)
                    .text(i.toString().padLeft(2, "0"))
                    .appendTo(".HMTVE270TargetResultEntry.ddlMonth");
            }
            $(".HMTVE270TargetResultEntry.ddlMonth").val(sysMonth);
            //前画面の内容を引き継ぐ
            if ($("#txtbDuring").html() != "") {
                $(".HMTVE270TargetResultEntry.txtbDuring").val(
                    $("#txtbDuring").html()
                );
            }
            if ($("#ddlMonth").html() != "") {
                $(".HMTVE270TargetResultEntry.ddlMonth").val(
                    $("#ddlMonth").html()
                );
            }

            var url = me.sys_id + "/" + me.id + "/" + "Page_Load";
            var month = $.trim($(".HMTVE270TargetResultEntry.ddlMonth").val());
            var monthData = month.toString().padLeft(2, "0");
            var data = {
                CONST_ADMIN_PTN_NO: me.hmtve.CONST_ADMIN_PTN_NO,
                CONST_HONBU_PTN_NO: me.hmtve.CONST_HONBU_PTN_NO,
                CONST_TESTER_PTN_NO: me.hmtve.CONST_TESTER_PTN_NO,
                TAISYOU_YM:
                    $.trim($(".HMTVE270TargetResultEntry.txtbDuring").val()) +
                    monthData,
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["data"] && result["data"]["msg"]) {
                        me.clsComFnc.FncMsgBox(
                            result["data"]["msg"],
                            result["error"]
                        );
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                    $(".HMTVE270TargetResultEntry.btnLogin").button("disable");
                    $(".HMTVE270TargetResultEntry.btnDel").button("disable");
                    return;
                }
                //店舗名をセットする
                if (
                    gdmz.SessionPatternID == me.hmtve.CONST_ADMIN_PTN_NO ||
                    gdmz.SessionPatternID == me.hmtve.CONST_HONBU_PTN_NO ||
                    gdmz.SessionPatternID == me.hmtve.CONST_TESTER_PTN_NO
                ) {
                    $(".HMTVE270TargetResultEntry.lblShopName").val("その他");
                } else {
                    //店舗名を抽出する
                    var BUSYO_RYKNM = result["data"]["BUSYO_RYKNM"];
                    if (BUSYO_RYKNM) {
                        $(".HMTVE270TargetResultEntry.lblShopName").val(
                            BUSYO_RYKNM
                        );
                    } else {
                        $(".HMTVE270TargetResultEntry.lblShopName").val("");
                    }
                }
                //表示設定
                //登録データを取得する
                if (
                    result["data"]["objdr2"] &&
                    result["data"]["objdr2"].length > 0
                ) {
                    var objdr2 = result["data"]["objdr2"][0];
                    //総限界利益_中間会議
                    $(".HMTVE270TargetResultEntry.txtGoal").val(
                        objdr2["GENRI_MOKUHYO"]
                    );
                    //総限界利益_月末予想_中間会議
                    $(".HMTVE270TargetResultEntry.txtYosou").val(
                        objdr2["GENRI_YOSOU"]
                    );
                    //総限界利益_月末予想_増減
                    $(".HMTVE270TargetResultEntry.txtSabun").val(
                        objdr2["GENRI_SABUN"]
                    );
                    $(".HMTVE270TargetResultEntry.txtJisski").val(
                        objdr2["GENRI_JISSEKI"]
                    );
                    //利益売上台数予想_当月目標_ﾒｲﾝ権
                    $(".HMTVE270TargetResultEntry.txtMain").val(
                        objdr2["URIMOKU_MAIN"]
                    );
                    //利益売上台数予想_当月目標_他ﾁｬﾈﾙ
                    $(".HMTVE270TargetResultEntry.txtTaChanel").val(
                        objdr2["URIMOKU_TACHANEL"]
                    );
                    //利益売上台数予想_月末予想_ﾒｲﾝ権_中間会議
                    $(".HMTVE270TargetResultEntry.txtMainY").val(
                        objdr2["URIYOSOU_MAIN_Y"]
                    );
                    //利益売上台数予想_月末予想_ﾒｲﾝ権_増減
                    $(".HMTVE270TargetResultEntry.txtMainS").val(
                        objdr2["URIYOSOU_MAIN_S"]
                    );
                    //利益売上台数予想_月末予想_軽自動車_中間会議
                    $(".HMTVE270TargetResultEntry.txtKeiY").val(
                        objdr2["URIYOSOU_KEI_Y"]
                    );
                    //利益売上台数予想_月末予想_軽自動車_増減
                    $(".HMTVE270TargetResultEntry.txtKeiS").val(
                        objdr2["URIYOSOU_KEI_S"]
                    );
                    //利益売上台数予想_月末予想_ボルボ_中間会議
                    $(".HMTVE270TargetResultEntry.txtVolvoY").val(
                        objdr2["URIYOSOU_VOLVO_Y"]
                    );
                    //利益売上台数予想_月末予想_ボルボ_増減
                    $(".HMTVE270TargetResultEntry.txtVolvoS").val(
                        objdr2["URIYOSOU_VOLVO_S"]
                    );
                    //利益売上台数予想_月末予想_その他_中間会議
                    $(".HMTVE270TargetResultEntry.txtSonotaY").val(
                        objdr2["URIYOSOU_SONOTA_Y"]
                    );
                    //利益売上台数予想_月末予想_その他_増減
                    $(".HMTVE270TargetResultEntry.txtSonotaS").val(
                        objdr2["URIYOSOU_SONOTA_S"]
                    );
                    //利益売上台数予想_売上台数計_中間会議
                    $(".HMTVE270TargetResultEntry.txtYGk").val(
                        objdr2["URI_Y_GK"]
                    );
                    //利益売上台数予想_売上台数計_増減
                    $(".HMTVE270TargetResultEntry.txtSGk").val(
                        objdr2["URI_S_GK"]
                    );
                    //登録台数予想_自自_中間会議
                    $(".HMTVE270TargetResultEntry.txtJijiY").val(
                        objdr2["TRKDAISU_JIJI_Y"]
                    );
                    //登録台数予想_自自_増減
                    $(".HMTVE270TargetResultEntry.txtJijiS").val(
                        objdr2["TRKDAISU_JIJI_S"]
                    );
                    //登録台数予想_福祉_中間会議
                    $(".HMTVE270TargetResultEntry.txtFukushiY").val(
                        objdr2["TRKDAISU_FUKUSHI_Y"]
                    );
                    //登録台数予想_福祉_中間会議
                    $(".HMTVE270TargetResultEntry.txtFukushiS").val(
                        objdr2["TRKDAISU_FUKUSHI_S"]
                    );
                    //登録台数予想_他自_中間会議
                    $(".HMTVE270TargetResultEntry.txtTajiY").val(
                        objdr2["TRKDAISU_TAJI_Y"]
                    );
                    //登録台数予想_他自_増減
                    $(".HMTVE270TargetResultEntry.txtTajiS").val(
                        objdr2["TRKDAISU_TAJI_S"]
                    );
                    //登録台数予想_自他_中間会議
                    $(".HMTVE270TargetResultEntry.txtJitaY").val(
                        objdr2["TRKDAISU_JITA_Y"]
                    );
                    //登録台数予想_自他_増減
                    $(".HMTVE270TargetResultEntry.txtJitaS").val(
                        objdr2["TRKDAISU_JITA_S"]
                    );
                    //登録台数予想_登録台数計_中間会議
                    $(".HMTVE270TargetResultEntry.txtTYGk").val(
                        objdr2["TRK_Y_GK"]
                    );
                    //登録台数予想_登録台数計_増減
                    $(".HMTVE270TargetResultEntry.txtTSGk").val(
                        objdr2["TRK_S_GK"]
                    );
                    //登録台数予想_軽自自_中間会議
                    $(".HMTVE270TargetResultEntry.txtKJijiY").val(
                        objdr2["TRKDAISU_KEI_JIJI_Y"]
                    );
                    //登録台数予想_軽自自_増減
                    $(".HMTVE270TargetResultEntry.txtKJijiS").val(
                        objdr2["TRKDAISU_KEI_JIJI_S"]
                    );
                    //登録台数予想_軽他自_中間会議
                    $(".HMTVE270TargetResultEntry.txtKTajiY").val(
                        objdr2["TRKDAISU_KEI_TAJI_Y"]
                    );
                    //登録台数予想_軽他自_増減
                    $(".HMTVE270TargetResultEntry.txtKTajiS").val(
                        objdr2["TRKDAISU_KEI_TAJI_S"]
                    );
                    //登録台数予想_軽自他_中間会議
                    $(".HMTVE270TargetResultEntry.txtKJitaY").val(
                        objdr2["TRKDAISU_KEI_JITA_Y"]
                    );
                    //登録台数予想_軽自他_増減
                    $(".HMTVE270TargetResultEntry.txtKJitaS").val(
                        objdr2["TRKDAISU_KEI_JITA_S"]
                    );

                    $(".HMTVE270TargetResultEntry.txtKFukushiY").val(
                        objdr2["TRKDAISU_KEI_FUKUSHI_Y"]
                    );

                    $(".HMTVE270TargetResultEntry.txtKFukushiS").val(
                        objdr2["TRKDAISU_KEI_FUKUSHI_S"]
                    );
                    //登録台数予想_軽自動車登録台数計_中間会議
                    $(".HMTVE270TargetResultEntry.txtKTDaisuY").val(
                        objdr2["KEI_TRK_DAISU_Y"]
                    );
                    //登録台数予想_軽自動車登録台数計_増減
                    $(".HMTVE270TargetResultEntry.txtKTDaisuS").val(
                        objdr2["KEI_TRK_DAISU_S"]
                    );
                    //登録台数予想_内レンタカー_中間会議
                    $(".HMTVE270TargetResultEntry.txtRentaY").val(
                        objdr2["TRKDAISU_RENTA_Y"]
                    );
                    //登録台数予想_内レンタカー_増減
                    $(".HMTVE270TargetResultEntry.txtRentaS").val(
                        objdr2["TRKDAISU_RENTA_S"]
                    );
                    //デミオ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtTDaisuY").val(
                        objdr2["DEMIO_TRK_DAISU_Y"]
                    );
                    //デミオ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtTDaisuS").val(
                        objdr2["DEMIO_TRK_DAISU_S"]
                    );
                    //(ZM)2 登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtM2GDaisuY").val(
                        objdr2["M2G_TRK_DAISU_Y"]
                    );
                    //(ZM)2 登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtM2GDaisuS").val(
                        objdr2["M2G_TRK_DAISU_S"]
                    );
                    //CX-3登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtCX3DaisuY").val(
                        objdr2["CX3_TRK_DAISU_Y"]
                    );
                    //CX-3登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtCX3DaisuS").val(
                        objdr2["CX3_TRK_DAISU_S"]
                    );
                    //Mazda3 SDN登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtM3SDaisuY").val(
                        objdr2["M3S_TRK_DAISU_Y"]
                    );
                    //Mazda3 SDN登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtM3SDaisuS").val(
                        objdr2["M3S_TRK_DAISU_S"]
                    );
                    //Mazda3 SDN登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtM3HDaisuY").val(
                        objdr2["M3H_TRK_DAISU_Y"]
                    );
                    //Mazda3 SDN登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtM3HDaisuS").val(
                        objdr2["M3H_TRK_DAISU_S"]
                    );
                    //Mazda6 SDN登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtM6SDaisuY").val(
                        objdr2["M6S_TRK_DAISU_Y"]
                    );
                    //Mazda6 SDN登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtM6SDaisuS").val(
                        objdr2["M6S_TRK_DAISU_S"]
                    );
                    //Mazda6 WGN登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtM6WDaisuY").val(
                        objdr2["M6W_TRK_DAISU_Y"]
                    );
                    //Mazda6 WGN登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtM6WDaisuS").val(
                        objdr2["M6W_TRK_DAISU_S"]
                    );
                    //アテンザ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtATDaiSuY").val(
                        objdr2["ATENZA_TRK_DAISU_Y"]
                    );
                    //アテンザ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtATDaiSuS").val(
                        objdr2["ATENZA_TRK_DAISU_S"]
                    );
                    //アクセラ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtAXTDaisuY").val(
                        objdr2["AXS_TRK_DAISU_Y"]
                    );
                    //アクセラ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtAXTDaisuS").val(
                        objdr2["AXS_TRK_DAISU_S"]
                    );
                    //プレマシー登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtPTDaisuY").val(
                        objdr2["PREMACY_TRK_DAISU_Y"]
                    );
                    //プレマシー登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtPTDaisuS").val(
                        objdr2["PREMACY_TRK_DAISU_S"]
                    );
                    //ビアンテ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtBianteY").val(
                        objdr2["BIANTE_TRK_DAISU_Y"]
                    );
                    //ビアンテ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtBianteS").val(
                        objdr2["BIANTE_TRK_DAISU_S"]
                    );
                    //ＭＰＶ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtMTDaisuY").val(
                        objdr2["MPV_TRK_DAISU_Y"]
                    );
                    //ＭＰＶ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtMTDaisuS").val(
                        objdr2["MPV_TRK_DAISU_S"]
                    );
                    //ＣＸ－５登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtCX5Y").val(
                        objdr2["CX5_TRK_DAISU_Y"]
                    );
                    //ＣＸ－５登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtCX5S").val(
                        objdr2["CX5_TRK_DAISU_S"]
                    );
                    //ＣＸ－８登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtCX8Y").val(
                        objdr2["CX8_TRK_DAISU_Y"]
                    );
                    //ＣＸ－８登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtCX8S").val(
                        objdr2["CX8_TRK_DAISU_S"]
                    );
                    //ＣＸ－３０登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtCX30Y").val(
                        objdr2["CX30_TRK_DAISU_Y"]
                    );
                    //ＣＸ－３０登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtCX30S").val(
                        objdr2["CX30_TRK_DAISU_S"]
                    );
                    //ＭＸ－３０登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtMX30Y").val(
                        objdr2["MX30_TRK_DAISU_Y"]
                    );
                    //ＭＸ－３０登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtMX30S").val(
                        objdr2["MX30_TRK_DAISU_S"]
                    );
                    // 20240326 LHB INS S
                    //ＣＸ－６０登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtCX60Y").val(
                        objdr2["CX60_TRK_DAISU_Y"]
                    );
                    //ＣＸ－６０登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtCX60S").val(
                        objdr2["CX60_TRK_DAISU_S"]
                    );
                    // 20240326 LHB INS E
                    // 20240611 LHB INS S
                    //ＣＸ－８０登録台数_中間会議
                    // 20240712 LHB INS S
                    // $(".HMTVE270TargetResultEntry.txtCX80Y").val(objdr2['CX80_TRK_DAISU_Y']);
                    // //ＣＸ－８０登録台数_増減
                    // $(".HMTVE270TargetResultEntry.txtCX80S").val(objdr2['CX80_TRK_DAISU_S']);
                    if (!me.hidden) {
                        $(".HMTVE270TargetResultEntry.txtCX80Y").val(
                            objdr2["CX80_TRK_DAISU_Y"]
                        );
                        //ＣＸ－８０登録台数_増減
                        $(".HMTVE270TargetResultEntry.txtCX80S").val(
                            objdr2["CX80_TRK_DAISU_S"]
                        );
                    }
                    // 20240712 LHB INS E
                    // 20240611 LHB INS E
                    //ロードスター登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtLTDaisuY").val(
                        objdr2["LDSTAR_TRK_DAISU_Y"]
                    );
                    //ロードスター登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtLTDaisuS").val(
                        objdr2["LDSTAR_TRK_DAISU_S"]
                    );
                    //ファミリアバン登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtSTDaisuY").val(
                        objdr2["FMV_TRK_DAISU_Y"]
                    );
                    //ファミリアバン登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtSTDaisuS").val(
                        objdr2["FMV_TRK_DAISU_S"]
                    );
                    //ボンゴ／ブローニィ登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtBTaisuY").val(
                        objdr2["BONGO_TRK_DAISU_Y"]
                    );
                    //ボンゴ／ブローニィ登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtBTaisuS").val(
                        objdr2["BONGO_TRK_DAISU_S"]
                    );
                    //タイタン登録台数_中間会議
                    $(".HMTVE270TargetResultEntry.txtTTTDaisuY").val(
                        objdr2["TT_TRK_DAISU_Y"]
                    );
                    //タイタン登録台数_増減
                    $(".HMTVE270TargetResultEntry.txtTTTDaisuS").val(
                        objdr2["TT_TRK_DAISU_S"]
                    );
                    // 中古売上台数_当月目標_直売
                    $(".HMTVE270TargetResultEntry.txtChoku").val(
                        objdr2["URIMOKU_CHUKO_CHOKU"]
                    );
                    // 中古売上台数_当月目標_業売
                    $(".HMTVE270TargetResultEntry.txtGyobai").val(
                        objdr2["URIMOKU_CHUKO_GYOBAI"]
                    );
                    // 中古売上台数_月末予想_直売_中間会議
                    $(".HMTVE270TargetResultEntry.txtChokuY").val(
                        objdr2["URIYOSOU_CHUKO_CHOKU_Y"]
                    );
                    // 中古売上台数_月末予想_直売_増減
                    $(".HMTVE270TargetResultEntry.txtChokuS").val(
                        objdr2["URIYOSOU_CHUKO_CHOKU_S"]
                    );
                    // 中古売上台数_月末予想_業売_中間会議
                    $(".HMTVE270TargetResultEntry.txtGyobaiY").val(
                        objdr2["URIYOSOU_CHUKO_GYOBAI_Y"]
                    );
                    // 中古売上台数_月末予想_業売_増減
                    $(".HMTVE270TargetResultEntry.txtGyobaiS").val(
                        objdr2["URIYOSOU_CHUKO_GYOBAI_S"]
                    );

                    // 中古売上台数_売上台数計_中間会議
                    $(".HMTVE270TargetResultEntry.txtYCk").val(
                        objdr2["URI_Y_CK"]
                    );
                    // 中古売上台数_売上台数計_増減
                    $(".HMTVE270TargetResultEntry.txtSCf").val(
                        objdr2["URI_S_CF"]
                    );
                    //周辺利益_自動車保険
                    $(".HMTVE270TargetResultEntry.txtHoken").val(
                        objdr2["SHURI_HOKEN"]
                    );
                    //周辺利益_再リース
                    $(".HMTVE270TargetResultEntry.txtLease").val(
                        objdr2["SHURI_LEASE"]
                    );
                    //周辺利益_ローンＫＢ
                    $(".HMTVE270TargetResultEntry.txtLoan").val(
                        objdr2["SHURI_LOAN"]
                    );
                    //周辺利益_希望Ｎｏ
                    $(".HMTVE270TargetResultEntry.txtKibou").val(
                        objdr2["SHURI_KIBOU"]
                    );
                    //周辺利益_延長保証
                    $(".HMTVE270TargetResultEntry.txtP753").val(
                        objdr2["SHURI_P753"]
                    );
                    //周辺利益_Ｐメンテ
                    $(".HMTVE270TargetResultEntry.txtPMente").val(
                        objdr2["SHURI_PMENTE"]
                    );
                    //周辺利益_ボディコ－ト
                    $(".HMTVE270TargetResultEntry.txtBodycoat").val(
                        objdr2["SHURI_BODYCOAT"]
                    );
                    //周辺利益_ＪＡＦ加入
                    $(".HMTVE270TargetResultEntry.txtJaf").val(
                        objdr2["SHURI_JAF"]
                    );
                    //周辺利益_ＯＳＳ
                    $(".HMTVE270TargetResultEntry.txtOss").val(
                        objdr2["SHURI_OSS"]
                    );
                    me.hidCreateTime = objdr2["CREATE_DATE"];
                    me.lostFocus1();
                    $(".HMTVE270TargetResultEntry.btnDel").button("enable");
                } else {
                    $(".HMTVE270TargetResultEntry.btnDel").button("disable");
                }
                //総限界利益_目標にフォーカス移動
                $(".HMTVE270TargetResultEntry.txtGoal").trigger("focus");
            };
            me.ajax.send(url, data, 0);
        }
    };
    //'**********************************************************************
    //'処 理 名：フォーカス移動時
    //'関 数 名：lostFocus1
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：フォーカス移動時
    //'**********************************************************************
    me.lostFocus1 = function () {
        //登録台数予想-メイン権-中間会議
        var $txtMainY = $(".HMTVE270TargetResultEntry.txtMainY");
        var $txtJijiY = $(".HMTVE270TargetResultEntry.txtJijiY");
        $txtJijiY.val(me.ChangeTo($txtMainY.val()));
        //登録台数予想-メイン権-増減
        var $txtMainS = $(".HMTVE270TargetResultEntry.txtMainS");
        var $txtJijiS = $(".HMTVE270TargetResultEntry.txtJijiS");
        $txtJijiS.val(me.ChangeTo($txtMainS.val()));
        //登録台数予想-メイン権-最終予想
        var $txtJijiSY = $(".HMTVE270TargetResultEntry.txtJijiSY");
        var txtJijiYNum = me.ChangeTo($txtJijiY.val());
        var txtJijiSNum = me.ChangeTo($txtJijiS.val());
        $txtJijiSY.val(parseFloat(txtJijiYNum) + parseFloat(txtJijiSNum));
        //登録台数予想-軽自動車-中間会議
        var $txtKeiY = $(".HMTVE270TargetResultEntry.txtKeiY");
        var $txtKJijiY = $(".HMTVE270TargetResultEntry.txtKJijiY");
        $txtKJijiY.val(me.ChangeTo($txtKeiY.val()));
        //登録台数予想-軽自動車-増減
        var $txtKeiS = $(".HMTVE270TargetResultEntry.txtKeiS");
        var $txtKJijiS = $(".HMTVE270TargetResultEntry.txtKJijiS");
        $txtKJijiS.val(me.ChangeTo($txtKeiS.val()));
        //登録台数予想-軽自動車-最終予想
        var $txtKJijiSY = $(".HMTVE270TargetResultEntry.txtKJijiSY");
        var txtKJijiYNum = me.ChangeTo($txtKJijiY.val());
        var txtKJijiSNum = me.ChangeTo($txtKJijiS.val());
        $txtKJijiSY.val(parseFloat(txtKJijiYNum) + parseFloat(txtKJijiSNum));

        //総限界利益(単位：千円)
        //利益売上台数予想-月末予想
        //登録台数予想-福祉,他自,自他,軽：他自,軽：自他,軽：福祉,内ﾚﾝﾀｶｰ登録
        //登録台数車種内訳
        var $carType = $(
            ".HMTVE270TargetResultEntry.LOGIN_NUM_CALTYPE td input"
        );
        for (var i = 2; i < $carType.length; i++) {
            if ($carType[i].readOnly == true) {
                var num1 = me.ChangeTo($carType[i - 1].value);
                var num2 = me.ChangeTo($carType[i - 2].value);
                $carType[i].value = parseFloat(num1) + parseFloat(num2);
            }
        }
        //利益売上台数予想-売上台数計
        for (var i = 1; i <= 3; i++) {
            var $sellNum = $(".HMTVE270TargetResultEntry.sellNum" + i);
            var $selfSellNum = $(".HMTVE270TargetResultEntry.selfSellNum" + i);
            $total = 0;
            for (var j = 0; j < $selfSellNum.length; j++) {
                $total += parseFloat(me.ChangeTo($selfSellNum[j].value));
            }
            $sellNum.val($total);
        }
        //中古売上台数-売上台数計
        for (var i = 1; i <= 3; i++) {
            var $sellNum = $(".HMTVE270TargetResultEntry.sellCNum" + i);
            var $selfSellCNum = $(
                ".HMTVE270TargetResultEntry.selfSellCNum" + i
            );
            $total = 0;
            for (var j = 0; j < $selfSellCNum.length; j++) {
                $total += parseFloat(me.ChangeTo($selfSellCNum[j].value));
            }
            $sellNum.val($total);
        }
        //登録台数予想-登録台数計
        //登録台数予想-軽自動車登録台数計
        for (var i = 1; i <= 6; i++) {
            var $loginNum = $(".HMTVE270TargetResultEntry.loginNum" + i);
            var $selfLoginNum = $(
                ".HMTVE270TargetResultEntry.selfLoginNum" + i
            );
            $total = 0;
            for (var j = 0; j < $selfLoginNum.length; j++) {
                $total += parseFloat(me.ChangeTo($selfLoginNum[j].value));
            }
            var $subSelfLoginNum = $(
                ".HMTVE270TargetResultEntry.selfLoginNum_" + i
            );
            $total -= me.ChangeTo($subSelfLoginNum.val());
            $loginNum.val($total);
        }
    };
    //'**********************************************************************
    //'処 理 名：Nullは転換に値します
    //'関 数 名：ChangeTo
    //'引    数：text
    //'戻 り 値：無し
    //'処理説明：フォーカス移動時
    //'**********************************************************************
    me.ChangeTo = function (text) {
        if (text == "") {
            text = 0;
        }
        return text;
    };
    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnLogin_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：データ更新処理を行う
    //'**********************************************************************
    me.btnLogin_Click = function () {
        if (!me.fncInputCheck()) {
            return;
        }
        var strMode = "";
        if ($(".HMTVE270TargetResultEntry.btnDel").prop("disabled") == true) {
            strMode = "INSERT";
        } else {
            strMode = "UPDATE";
        }
        //存在チェックを行う
        var url = me.sys_id + "/" + me.id + "/" + "btnLogin_Click";
        var insData = {
            txtGoal: $(".HMTVE270TargetResultEntry.txtGoal").val(),
            txtYosou: $(".HMTVE270TargetResultEntry.txtYosou").val(),
            txtJisski: $(".HMTVE270TargetResultEntry.txtJisski").val(),
            txtMain: $(".HMTVE270TargetResultEntry.txtMain").val(),
            txtTaChanel: $(".HMTVE270TargetResultEntry.txtTaChanel").val(),
            txtMainY: $(".HMTVE270TargetResultEntry.txtMainY").val(),

            txtMainS: $(".HMTVE270TargetResultEntry.txtMainS").val(),
            txtKeiY: $(".HMTVE270TargetResultEntry.txtKeiY").val(),
            txtKeiS: $(".HMTVE270TargetResultEntry.txtKeiS").val(),

            txtVolvoY: $(".HMTVE270TargetResultEntry.txtVolvoY").val(),
            txtVolvoS: $(".HMTVE270TargetResultEntry.txtVolvoS").val(),
            txtSonotaY: $(".HMTVE270TargetResultEntry.txtSonotaY").val(),
            txtSonotaS: $(".HMTVE270TargetResultEntry.txtSonotaS").val(),
            txtJijiY: $(".HMTVE270TargetResultEntry.txtJijiY").val(),
            txtJijiS: $(".HMTVE270TargetResultEntry.txtJijiS").val(),
            txtFukushiY: $(".HMTVE270TargetResultEntry.txtFukushiY").val(),
            txtFukushiS: $(".HMTVE270TargetResultEntry.txtFukushiS").val(),

            txtTajiY: $(".HMTVE270TargetResultEntry.txtTajiY").val(),
            txtTajiS: $(".HMTVE270TargetResultEntry.txtTajiS").val(),
            txtJitaY: $(".HMTVE270TargetResultEntry.txtJitaY").val(),
            txtJitaS: $(".HMTVE270TargetResultEntry.txtJitaS").val(),

            txtKJijiY: $(".HMTVE270TargetResultEntry.txtKJijiY").val(),
            txtKJijiS: $(".HMTVE270TargetResultEntry.txtKJijiS").val(),
            txtKTajiY: $(".HMTVE270TargetResultEntry.txtKTajiY").val(),
            txtKTajiS: $(".HMTVE270TargetResultEntry.txtKTajiS").val(),
            txtKJitaY: $(".HMTVE270TargetResultEntry.txtKJitaY").val(),
            txtKJitaS: $(".HMTVE270TargetResultEntry.txtKJitaS").val(),
            txtKFukushiY: $(".HMTVE270TargetResultEntry.txtKFukushiY").val(),
            txtKFukushiS: $(".HMTVE270TargetResultEntry.txtKFukushiS").val(),

            txtRentaY: $(".HMTVE270TargetResultEntry.txtRentaY").val(),
            txtRentaS: $(".HMTVE270TargetResultEntry.txtRentaS").val(),

            txtTDaisuY: $(".HMTVE270TargetResultEntry.txtTDaisuY").val(),
            txtTDaisuS: $(".HMTVE270TargetResultEntry.txtTDaisuS").val(),

            txtM2GDaisuY: $(".HMTVE270TargetResultEntry.txtM2GDaisuY").val(),
            txtM2GDaisuS: $(".HMTVE270TargetResultEntry.txtM2GDaisuS").val(),

            txtCX3DaisuY: $(".HMTVE270TargetResultEntry.txtCX3DaisuY").val(),
            txtCX3DaisuS: $(".HMTVE270TargetResultEntry.txtCX3DaisuS").val(),

            txtM3SDaisuY: $(".HMTVE270TargetResultEntry.txtM3SDaisuY").val(),
            txtM3SDaisuS: $(".HMTVE270TargetResultEntry.txtM3SDaisuS").val(),
            txtM3HDaisuY: $(".HMTVE270TargetResultEntry.txtM3HDaisuY").val(),
            txtM3HDaisuS: $(".HMTVE270TargetResultEntry.txtM3HDaisuS").val(),

            txtM6SDaisuY: $(".HMTVE270TargetResultEntry.txtM6SDaisuY").val(),
            txtM6SDaisuS: $(".HMTVE270TargetResultEntry.txtM6SDaisuS").val(),
            txtM6WDaisuY: $(".HMTVE270TargetResultEntry.txtM6WDaisuY").val(),
            txtM6WDaisuS: $(".HMTVE270TargetResultEntry.txtM6WDaisuS").val(),

            txtATDaiSuY: $(".HMTVE270TargetResultEntry.txtATDaiSuY").val(),
            txtATDaiSuS: $(".HMTVE270TargetResultEntry.txtATDaiSuS").val(),
            txtAXTDaisuY: $(".HMTVE270TargetResultEntry.txtAXTDaisuY").val(),
            txtAXTDaisuS: $(".HMTVE270TargetResultEntry.txtAXTDaisuS").val(),
            txtBianteY: $(".HMTVE270TargetResultEntry.txtBianteY").val(),
            txtBianteS: $(".HMTVE270TargetResultEntry.txtBianteS").val(),

            txtPTDaisuY: $(".HMTVE270TargetResultEntry.txtPTDaisuY").val(),
            txtPTDaisuS: $(".HMTVE270TargetResultEntry.txtPTDaisuS").val(),
            txtMTDaisuY: $(".HMTVE270TargetResultEntry.txtMTDaisuY").val(),
            txtMTDaisuS: $(".HMTVE270TargetResultEntry.txtMTDaisuS").val(),
            txtCX5Y: $(".HMTVE270TargetResultEntry.txtCX5Y").val(),
            txtCX5S: $(".HMTVE270TargetResultEntry.txtCX5S").val(),
            txtCX8Y: $(".HMTVE270TargetResultEntry.txtCX8Y").val(),
            txtCX8S: $(".HMTVE270TargetResultEntry.txtCX8S").val(),
            txtCX30Y: $(".HMTVE270TargetResultEntry.txtCX30Y").val(),
            txtCX30S: $(".HMTVE270TargetResultEntry.txtCX30S").val(),
            txtMX30Y: $(".HMTVE270TargetResultEntry.txtMX30Y").val(),
            txtMX30S: $(".HMTVE270TargetResultEntry.txtMX30S").val(),
            // 20240326 LHB INS S
            txtCX60Y: $(".HMTVE270TargetResultEntry.txtCX60Y").val(),
            txtCX60S: $(".HMTVE270TargetResultEntry.txtCX60S").val(),
            // 20240326 LHB INS E
            // 20240611 LHB INS S
            txtCX80Y: $(".HMTVE270TargetResultEntry.txtCX80Y").val(),
            txtCX80S: $(".HMTVE270TargetResultEntry.txtCX80S").val(),
            // 20240611 LHB INS E
            txtLTDaisuY: $(".HMTVE270TargetResultEntry.txtLTDaisuY").val(),
            txtLTDaisuS: $(".HMTVE270TargetResultEntry.txtLTDaisuS").val(),
            txtSTDaisuY: $(".HMTVE270TargetResultEntry.txtSTDaisuY").val(),
            txtSTDaisuS: $(".HMTVE270TargetResultEntry.txtSTDaisuS").val(),
            txtBTaisuY: $(".HMTVE270TargetResultEntry.txtBTaisuY").val(),
            txtBTaisuS: $(".HMTVE270TargetResultEntry.txtBTaisuS").val(),

            txtTTTDaisuY: $(".HMTVE270TargetResultEntry.txtTTTDaisuY").val(),
            txtTTTDaisuS: $(".HMTVE270TargetResultEntry.txtTTTDaisuS").val(),
            txtKTDaisuY: $(".HMTVE270TargetResultEntry.txtKTDaisuY").val(),
            txtKTDaisuS: $(".HMTVE270TargetResultEntry.txtKTDaisuS").val(),

            // 中古売上台数
            txtChoku: $(".HMTVE270TargetResultEntry.txtChoku").val(),
            txtGyobai: $(".HMTVE270TargetResultEntry.txtGyobai").val(),
            txtChokuY: $(".HMTVE270TargetResultEntry.txtChokuY").val(),
            txtChokuS: $(".HMTVE270TargetResultEntry.txtChokuS").val(),
            txtGyobaiY: $(".HMTVE270TargetResultEntry.txtGyobaiY").val(),
            txtGyobaiS: $(".HMTVE270TargetResultEntry.txtGyobaiS").val(),

            txtHoken: $(".HMTVE270TargetResultEntry.txtHoken").val(),
            txtLease: $(".HMTVE270TargetResultEntry.txtLease").val(),
            txtLoan: $(".HMTVE270TargetResultEntry.txtLoan").val(),
            txtKibou: $(".HMTVE270TargetResultEntry.txtKibou").val(),
            txtP753: $(".HMTVE270TargetResultEntry.txtP753").val(),
            txtPMente: $(".HMTVE270TargetResultEntry.txtPMente").val(),
            txtBodycoat: $(".HMTVE270TargetResultEntry.txtBodycoat").val(),
            txtJaf: $(".HMTVE270TargetResultEntry.txtJaf").val(),
            txtOss: $(".HMTVE270TargetResultEntry.txtOss").val(),
        };
        var month = $.trim($(".HMTVE270TargetResultEntry.ddlMonth").val());
        var monthData = month.toString().padLeft(2, "0");
        var data = {
            strMode: strMode,
            CONST_ADMIN_PTN_NO: me.hmtve.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.hmtve.CONST_HONBU_PTN_NO,
            CONST_TESTER_PTN_NO: me.hmtve.CONST_TESTER_PTN_NO,
            TAISYOU_YM:
                $.trim($(".HMTVE270TargetResultEntry.txtbDuring").val()) +
                monthData,
            hidCreateTime: me.hidCreateTime,
            insData: insData,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "INSERT") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE270TargetResultEntry.btnClose"
                    );
                    //既に登録されています。
                    me.clsComFnc.FncMsgBox("E0016");
                } else if (result["error"] == "UPDATE") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE270TargetResultEntry.btnClose"
                    );
                    //他のユーザーにより更新されています。最新の情報を確認してください。
                    me.clsComFnc.FncMsgBox("W0025");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
            $("#hidStart").html(
                $(".HMTVE270TargetResultEntry.txtbDuring").val()
            );
            $("#hidEnd").html(month);
            //画面を閉じる
            $(".HMTVE270TargetResultEntry.HMTVE-content").dialog("close");
            //dialog ok focus
            setTimeout(function () {
                //登録が完了しました
                me.clsComFnc.FncMsgBox("I0016");
            }, 100);
        };
        me.ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：入力チェック
    //'関 数 名：fncInputCheck
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：入力チェック
    //'**********************************************************************
    me.fncInputCheck = function () {
        //入力チェック
        //画面項目NO2、画面項目NO3が未入力の場合、エラー
        $txtbDuring = $(".HMTVE270TargetResultEntry.txtbDuring");
        if ($txtbDuring.val() == "") {
            me.clsComFnc.ObjFocus = $txtbDuring;
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "対象年月(年)を入力してください。"
                );
            }, 0);
            return false;
        }
        $ddlMonth = $(".HMTVE270TargetResultEntry.ddlMonth");
        if ($ddlMonth.val() == "") {
            me.clsComFnc.ObjFocus = $txtbDuring;
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "対象年月(月)を入力してください。"
                );
            }, 0);
            return false;
        }
        //画面項目NO5～画面項目NO56までの項目で
        //・桁数チェックを行う()メッセージ内容：エラー項目.TEXT & "は指定されている桁数をオーバーしています。"
        var $inputChe = $(".HMTVE270TargetResultEntry input");
        for (var i = 0; i < $inputChe.length; i++) {
            if ($inputChe[i].attributes.hasOwnProperty("lblname")) {
                var maxlength = $inputChe[i].maxLength;
                if (me.ChangeTo($inputChe[i].value).length > maxlength) {
                    var attr = $inputChe[i].attributes;
                    for (var j = 0; j < attr.length; j++) {
                        if (attr[j].name == "lblname") {
                            me.clsComFnc.ObjFocus = $inputChe[i];
                            setTimeout(function () {
                                me.clsComFnc.FncMsgBox(
                                    "W9999",
                                    attr[j].value +
                                        " は指定されている桁数をオーバーしています。"
                                );
                            }, 0);
                            return false;
                        }
                    }
                }
                if (isNaN(me.ChangeTo($inputChe[i].value))) {
                    me.clsComFnc.ObjFocus = $inputChe[i];
                    setTimeout(function () {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "入力されている値が不正です。"
                        );
                    }, 0);
                    return false;
                }
            }
        }

        //・整合性チェックを行う
        //登録台数車種内訳(軽自動車以外)の合計と登録台数計が同一出ない場合はエラー
        var no27 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTYGk").val())
        );
        var no29 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTDaisuY").val())
        );
        var no31 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM3SDaisuY").val())
        );
        var no82 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM3HDaisuY").val())
        );

        var no84 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM6SDaisuY").val())
        );
        var no85 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM6WDaisuY").val())
        );

        var no33 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtATDaiSuY").val())
        );
        var no35 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtAXTDaisuY").val())
        );
        var no37 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtPTDaisuY").val())
        );
        var no39 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMTDaisuY").val())
        );
        var no45 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtLTDaisuY").val())
        );
        var no47 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtSTDaisuY").val())
        );
        var no49 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtBTaisuY").val())
        );
        var no53 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTTTDaisuY").val())
        );

        var no55 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtBianteY").val())
        );
        var no57 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX5Y").val())
        );
        var no59 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX3DaisuY").val())
        );
        var no80 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX8Y").val())
        );

        var no88 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM2GDaisuY").val())
        );
        var no89 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX30Y").val())
        );

        var no92 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMX30Y").val())
        );
        // 20240326 LHB INS S
        var no94 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX60Y").val())
        );
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // var no96 = parseFloat(me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX80Y").val()));
        if (!me.hidden) {
            var no96 = parseFloat(
                me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX80Y").val())
            );
        }
        // 20240712 LHB UPD E
        // 20240611 LHB INS E
        // 20240326 LHB UPD S
        // if (no27 != no29 + no31 + no33 + no35 + no37 + no39 + no45 + no47 + no49 + no53 + no55 + no57 + no59 + no80 + no82 + no84 + no85 + no88 + no89 + no92)
        // {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuY");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)中間会議と登録台数計の中間会議が一致しません。");
        // 	return false;
        // }
        // 20240611 LHB UPD S
        // if (no27 != no29 + no31 + no33 + no35 + no37 + no39 + no45 + no47 + no49 + no53 + no55 + no57 + no59 + no80 + no82 + no84 + no85 + no88 + no89 + no92 + no94) {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuY");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)中間会議と登録台数計の中間会議が一致しません。");
        // 	return false;
        // }
        // 20240712 LHB UPD S
        // if (no27 != no29 + no31 + no33 + no35 + no37 + no39 + no45 + no47 + no49 + no53 + no55 + no57 + no59 + no80 + no82 + no84 + no85 + no88 + no89 + no92 + no94 + no96) {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuY");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)中間会議と登録台数計の中間会議が一致しません。");
        // 	return false;
        // }
        var total =
            no29 +
            no31 +
            no33 +
            no35 +
            no37 +
            no39 +
            no45 +
            no47 +
            no49 +
            no53 +
            no55 +
            no57 +
            no59 +
            no80 +
            no82 +
            no84 +
            no85 +
            no88 +
            no89 +
            no92 +
            no94;
        if (!me.hidden) {
            total = total + no96;
        }
        if (no27 != total) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuY");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数車種内訳(軽自動車以外)中間会議と登録台数計の中間会議が一致しません。"
                );
            }, 0);
            return false;
        }
        // 20240712 LHB UPD E
        // 20240611 LHB UPD E
        // 20240326 LHB UPD E
        var no28 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTSGk").val())
        );
        var no30 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTDaisuS").val())
        );
        var no32 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM3SDaisuS").val())
        );
        var no83 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM3HDaisuS").val())
        );

        var no86 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM6SDaisuS").val())
        );
        var no87 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM6WDaisuS").val())
        );

        var no34 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtATDaiSuS").val())
        );
        var no36 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtAXTDaisuS").val())
        );
        var no38 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtPTDaisuS").val())
        );
        var no40 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMTDaisuS").val())
        );
        var no46 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtLTDaisuS").val())
        );
        var no48 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtSTDaisuS").val())
        );
        var no50 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtBTaisuS").val())
        );
        var no54 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtTTTDaisuS").val())
        );

        var no56 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtBianteS").val())
        );
        var no58 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX5S").val())
        );
        var no64 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX3DaisuS").val())
        );
        var no81 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX8S").val())
        );

        var no90 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtM2GDaisuS").val())
        );
        var no91 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX30S").val())
        );

        var no93 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMX30S").val())
        );
        // 20240326 LHB INS S
        var no95 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX60S").val())
        );
        // 20240326 LHB INS E
        // 20240611 LHB INS S
        // 20240712 LHB UPD S
        // var no97 = parseFloat(me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX80S").val()));
        if (!me.hidden) {
            var no97 = parseFloat(
                me.ChangeTo($(".HMTVE270TargetResultEntry.txtCX80S").val())
            );
        }
        // 20240712 LHB UPD E
        // 20240611 LHB INS E
        // 20240326 LHB UPD S
        // if (no28 !== no30 + no32 + no34 + no36 + no38 + no40 + no46 + no48 + no50 + no54 + no56 + no58 + no64 + no81 + no83 + no86 + no87 + no90 + no91 + no93)
        // {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuS");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)増減と登録台数計の増減が一致しません。");
        // 	return false;
        // }
        // 20240611 LHB UPD S
        // if (no28 !== no30 + no32 + no34 + no36 + no38 + no40 + no46 + no48 + no50 + no54 + no56 + no58 + no64 + no81 + no83 + no86 + no87 + no90 + no91 + no93 + no95) {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuS");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)増減と登録台数計の増減が一致しません。");
        // 	return false;
        // }
        // 20240712 LHB UPD S
        // if (no28 !== no30 + no32 + no34 + no36 + no38 + no40 + no46 + no48 + no50 + no54 + no56 + no58 + no64 + no81 + no83 + no86 + no87 + no90 + no91 + no93 + no95 + no97) {
        // 	me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuS");
        // 	me.clsComFnc.FncMsgBox("W9999", "登録台数車種内訳(軽自動車以外)増減と登録台数計の増減が一致しません。");
        // 	return false;
        // }
        var total =
            no30 +
            no32 +
            no34 +
            no36 +
            no38 +
            no40 +
            no46 +
            no48 +
            no50 +
            no54 +
            no56 +
            no58 +
            no64 +
            no81 +
            no83 +
            no86 +
            no87 +
            no90 +
            no91 +
            no93 +
            no95;
        if (!me.hidden) {
            total = total + no97;
        }
        if (no28 !== total) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtTDaisuS");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数車種内訳(軽自動車以外)増減と登録台数計の増減が一致しません。"
                );
            }, 0);
            return false;
        }
        // 20240712 LHB UPD E
        // 20240611 LHB UPD E
        // 20240326 LHB UPD E
        //登録台数の自契自登＋自契他登≠売上台数のﾒｲﾝ権の場合はエラー
        var no19 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtJijiY").val())
        );
        var no11 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMainY").val())
        );
        if (no11 != no19) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtJijiY");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数のメイン権の中間会議と売上台数のメイン権の中間会議が一致しません。"
                );
            }, 0);
            return false;
        }

        var no12 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtMainS").val())
        );
        var no20 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtJijiS").val())
        );
        if (no12 != no20) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtJijiS");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数のメイン権の増減と売上台数のメイン権の増減が一致しません。"
                );
            }, 0);
            return false;
        }

        var no60 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtKJijiY").val())
        );
        var no61 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtKeiY").val())
        );
        if (no60 != no61) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtKJijiY");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数の軽自動車の中間会議と売上台数の軽自動車の中間会議が一致しません。"
                );
            }, 0);
            return false;
        }

        var no62 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtKJijiS").val())
        );
        var no63 = parseFloat(
            me.ChangeTo($(".HMTVE270TargetResultEntry.txtKeiS").val())
        );
        if (no62 != no63) {
            me.clsComFnc.ObjFocus = $(".HMTVE270TargetResultEntry.txtKJijiS");
            setTimeout(function () {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "登録台数の軽自動車の増減と売上台数の軽自動車の増減が一致しません。"
                );
            }, 0);
            return false;
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：削除ボタンのイベント
    //'関 数 名：btnDel_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：目標と実績データを削除する
    //'**********************************************************************
    me.btnDel_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnDel_Click";
        var month = $.trim($(".HMTVE270TargetResultEntry.ddlMonth").val());
        var monthData = month.length == 1 ? "0" + month : month;
        var data = {
            CONST_ADMIN_PTN_NO: me.hmtve.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.hmtve.CONST_HONBU_PTN_NO,
            CONST_TESTER_PTN_NO: me.hmtve.CONST_TESTER_PTN_NO,
            TAISYOU_YM:
                $.trim($(".HMTVE270TargetResultEntry.txtbDuring").val()) +
                monthData,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $("#hidStart").html(
                $(".HMTVE270TargetResultEntry.txtbDuring").val()
            );
            $("#hidEnd").html(month);
            //画面を閉じる
            $(".HMTVE270TargetResultEntry.HMTVE-content").dialog("close");
            //dialog ok focus
            setTimeout(function () {
                //削除が完了しました
                me.clsComFnc.FncMsgBox("I0017");
            }, 100);
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE270TargetResultEntry =
        new HMTVE.HMTVE270TargetResultEntry();
    o_HMTVE_HMTVE.HMTVE260TargetResultList.HMTVE270TargetResultEntry =
        o_HMTVE_HMTVE270TargetResultEntry;
    o_HMTVE_HMTVE270TargetResultEntry.load();
});
