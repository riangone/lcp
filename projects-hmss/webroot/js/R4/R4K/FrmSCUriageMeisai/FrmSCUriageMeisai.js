/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmSCUriageMeisai");

R4.FrmSCUriageMeisai = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    me.id = "FrmSCUriageMeisai";
    me.sys_id = "R4K";
    //注文書番号
    me.strCmnNO = "";
    me.intCnt = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSCUriageMeisai.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSCUriageMeisai.cmdBack",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {

        base_init_control();
        me.strCmnNO = me.FrmSCUriageList.strCmnNO;
        me.frmSCUriageMeisai_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmSCUriageMeisai.cmdAction").click(function () {
        me.cmdAction_Click();
    });

    $(".FrmSCUriageMeisai.cmdBack").click(function () {
        me.cmdBack_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmSCUriageMeisai_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：各種初期値設定
    //**********************************************************************
    me.frmSCUriageMeisai_Load = function () {
        //初期処理
        var blnErrFlg2 = false;
        me.intCnt = 0;
        //クエリ実行
        var url = me.sys_id + "/" + me.id + "/" + "FncSelectMeisai";
        var data = me.strCmnNO;
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                var objDr = result["data"][0];
                //画面項目をセットする
                if (me.fncMeisaiSet(objDr, 1) == false) {
                    return;
                }
                //正常終了
                blnErrFlg2 = true;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
            if (blnErrFlg2 == false) {
                $("#FrmSCUriageMeisai").dialog("close");
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：
    //**********************************************************************
    me.cmdAction_Click = function () {
        me.intCnt = me.intCnt + 1;
        var url = me.sys_id + "/" + me.id + "/" + "fncSelectJyohen";
        var data = {
            strCmnNO: me.strCmnNO,
            intCnt: me.intCnt,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                var objDR = result["data"][0];
                //画面項目をセットする
                if (me.fncMeisaiSet(objDR, 2) == false) {
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：戻る
    //関 数 名：cmdBack_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：確認メッセージを表示しよければフォームを閉じて親画面に戻る
    //**********************************************************************
    me.cmdBack_Click = function () {
        if (me.intCnt == 0) {
            $("#FrmSCUriageMeisai").dialog("close");
        } else {
            me.frmSCUriageMeisai_Load();
        }
    };

    me.fncMeisaiSet = function (objDr, intUJFlg) {
        var strJHNNO = "";

        $(".FrmSCUriageMeisai.TabSinsya").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSinsya").css("display", "none");
        $("#TabSinsya").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabChuko").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabChuko").css("display", "none");
        $("#TabChuko").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabKeiyakusya").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabKeiyakusya").css("display", "none");
        $("#TabKeiyakusya").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori1").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori1").css("display", "none");
        $("#TabSitadori1").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori2").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori2").css("display", "none");
        $("#TabSitadori2").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori3").css("visibility", "hidden");
        $(".FrmSCUriageMeisai.TabSitadori3").css("display", "none");
        $("#TabSitadori3").css("visibility", "hidden");
        //画面項目ｸﾘｱ
        me.subHedderClearForm();
        me.subSinsyaFormClear();
        me.subChukosyaFormClear();
        me.subKeiyakusyaFormClear();
        me.subSitadori1FormClear();
        me.subSitadori2FormClear();
        me.subSitadori3FormClear();
        //該当なし
        if (objDr.length == 0) {
            //該当するデータは存在しません。
            me.clsComFnc.FncMsgBox("I0001");
            return false;
        }
        if (intUJFlg == 1) {
            //売上ﾃｰﾌﾞﾙ
            if (me.clsComFnc.FncNz(objDr["RIR_COUNT"]) == 0) {
                //履歴データが存在しない場合は、ボタンを不可にする
                $(".FrmSCUriageMeisai.cmdAction").button("disable");
            } else {
                $(".FrmSCUriageMeisai.cmdAction").button("enable");
            }
        } // 条変連番が条変の存在件数と一致した時、次の条変はないので、ボタンを非表示にする
        else {
            if (
                me.clsComFnc.FncNz(objDr["RIR_COUNT"]) ==
                me.clsComFnc.FncNz(objDr["CT"])
            ) {
                //次の履歴データが存在しない場合は、ボタンを不可にする
                $(".FrmSCUriageMeisai.cmdAction").button("disable");
            } else {
                $(".FrmSCUriageMeisai.cmdAction").button("enable");
            }
            strJHNNO = objDr["JKN_HKO_RIRNO"];
        }
        //データをセットする
        me.subFormHedderSet(objDr);

        if (me.clsComFnc.FncNv(objDr["NAU_KB"]) == "1") {
            $("#tabs").tabs({
                active: 0,
            });
            me.subFormSinsyaSet(objDr);
            $(".FrmSCUriageMeisai.TabSinsya").css("visibility", "visible");
            $(".FrmSCUriageMeisai.TabSinsya").css("display", "block");
            $("#TabSinsya").css("visibility", "visible");
        } else {
            //set 中古車Tab selected
            $("#tabs").tabs({
                active: 1,
            });
            me.subFormChukoSet(objDr);
            $(".FrmSCUriageMeisai.TabChuko").css("visibility", "visible");
            $(".FrmSCUriageMeisai.TabChuko").css("display", "block");
            $("#TabChuko").css("visibility", "visible");
        }
        //契約者TAB
        me.subKeiyakusyaSet(objDr);
        $(".FrmSCUriageMeisai.TabKeiyakusya").css("visibility", "visible");
        $(".FrmSCUriageMeisai.TabKeiyakusya").css("display", "block");
        $("#TabKeiyakusya").css("visibility", "visible");

        var objDrSit = "";
        var url = me.sys_id + "/" + me.id + "/" + "FncSelectSitadori";
        if (intUJFlg == "1") {
            var data = {
                strCMN_NO: me.strCmnNO,
                intUJFlg: intUJFlg,
                strTblName: "HSCSIT",
                strRIR_NO: "",
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == true) {
                    objDrSit = result["data"];
                    me.tmpTabSitadori(objDrSit);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return false;
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            var data = {
                strCMN_NO: me.strCmnNO,
                intUJFlg: intUJFlg,
                strTblName: "HJYOUHENSIT",
                strRIR_NO: strJHNNO,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == true) {
                    objDrSit = result["data"];
                    me.tmpTabSitadori(objDrSit);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return false;
                }
            };
            me.ajax.send(url, data, 0);
        }
        return true;
    };

    me.tmpTabSitadori = function (objDrSit) {
        var intCnt1 = 0;
        //該当データが存在している場合
        if (objDrSit.length != 0) {
            var i = 0;
            while (objDrSit[i] != undefined) {
                //下取りデータをセットする
                switch (intCnt1) {
                    case 0:
                        me.subSitadori1FormSet(objDrSit[i]);
                        $(".FrmSCUriageMeisai.TabSitadori1").css(
                            "visibility",
                            "visible"
                        );
                        $(".FrmSCUriageMeisai.TabSitadori1").css(
                            "display",
                            "block"
                        );
                        $("#TabSitadori1").css("visibility", "visible");
                        intCnt1 += 1;
                        break;
                    case 1:
                        me.subSitadori2FormSet(objDrSit[i]);
                        $(".FrmSCUriageMeisai.TabSitadori2").css(
                            "visibility",
                            "visible"
                        );
                        $(".FrmSCUriageMeisai.TabSitadori2").css(
                            "display",
                            "block"
                        );
                        $("#TabSitadori2").css("visibility", "visible");
                        intCnt1 += 1;
                        break;
                    case 2:
                        me.subSitadori3FormSet(objDrSit[i]);
                        $(".FrmSCUriageMeisai.TabSitadori3").css(
                            "visibility",
                            "visible"
                        );
                        $(".FrmSCUriageMeisai.TabSitadori3").css(
                            "display",
                            "block"
                        );
                        $("#TabSitadori3").css("visibility", "visible");
                        intCnt1 += 1;
                        break;
                }
                i++;
            }
        }
    };
    //**********************************************************************
    //処 理 名：フォームクリア
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：フォームをクリアする
    //**********************************************************************
    me.subHedderClearForm = function () {
        $(".FrmSCUriageMeisai.lblCMNNO").html("");
        $(".FrmSCUriageMeisai.lblUCNO").html("");
        $(".FrmSCUriageMeisai.lblBusyoCD").html("");
        $(".FrmSCUriageMeisai.lblBusyoNM").html("");
        $(".FrmSCUriageMeisai.lblSyainCD").html("");
        $(".FrmSCUriageMeisai.lblSyainNM").html("");
        $(".FrmSCUriageMeisai.lblGyousyaCD").html("");
        $(".FrmSCUriageMeisai.lblGyousyaNM").html("");
        $(".FrmSCUriageMeisai.lblKei_Jyusyo").html("");
        $(".FrmSCUriageMeisai.lblKei_Shimei").html("");
        $(".FrmSCUriageMeisai.lblUriageBi").html("");
        $(".FrmSCUriageMeisai.lblKeiriBi").html("");
        $(".FrmSCUriageMeisai.lblSinsyaUCNO").html("");
        $(".FrmSCUriageMeisai.lblSeiriNO").html("");
        $(".FrmSCUriageMeisai.lblChukoUCNO").html("");
        $(".FrmSCUriageMeisai.lblMeigara").html("");
        $(".FrmSCUriageMeisai.lblNensei").html("");
        $(".FrmSCUriageMeisai.lblSyamei").html("");
        $(".FrmSCUriageMeisai.lblKatasiki").html("");
        $(".FrmSCUriageMeisai.lblKosyo").html("");
        $(".FrmSCUriageMeisai.lblSyasyuCD").html("");
        $(".FrmSCUriageMeisai.lblCarNO").html("");
        $(".FrmSCUriageMeisai.lblRuibetu").html("");
        $(".FrmSCUriageMeisai.lblTourokuNO").html("");
        $(".FrmSCUriageMeisai.lblSyonendo").html("");
        $(".FrmSCUriageMeisai.lblTourokuBi").html("");
        $(".FrmSCUriageMeisai.lblRikuji").html("");
    };
    //新車
    me.subSinsyaFormClear = function () {
        $(".FrmSCUriageMeisai.lblSin_Hontai_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Hontai_Shz").html("");
        $(".FrmSCUriageMeisai.lblSin_Hontai_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_Nebiki_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Shz").html("");
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Shz").html("");
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_Kappu_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Kappu_Shz").html("");
        $(".FrmSCUriageMeisai.lblSin_Kappu_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Shz").html("");
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_Azukari_Kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Azukari_Gen").html("");
        $(".FrmSCUriageMeisai.lblSin_ZeiHo_kyk").html("");
        $(".FrmSCUriageMeisai.lblSin_Syouhizei").html("");
        $(".FrmSCUriageMeisai.lblSin_Zansai").html("");
        $(".FrmSCUriageMeisai.lblSin_ShiharaiKei").html("");
        $(".FrmSCUriageMeisai.lblSin_SitadoriKin").html("");
        $(".FrmSCUriageMeisai.lblSin_SitadoriShz").html("");
        $(".FrmSCUriageMeisai.lblSin_Atamakin").html("");
        $(".FrmSCUriageMeisai.lblSin_SyoZeiHo").html("");
        $(".FrmSCUriageMeisai.lblSin_Tegata").html("");
        $(".FrmSCUriageMeisai.lblSin_Curegit").html("");
        $(".FrmSCUriageMeisai.lblSin_KappuGankin").html("");
        $(".FrmSCUriageMeisai.lblSin_SyunyuTes").html("");
        $(".FrmSCUriageMeisai.lblSin_Syoureikin").html("");
        $(".FrmSCUriageMeisai.lblSin_HanbaiTes").html("");
        $(".FrmSCUriageMeisai.lblSin_SonotaSyoukai").html("");
        $(".FrmSCUriageMeisai.lblSin_Penalty").html("");
        $(".FrmSCUriageMeisai.lblSin_EigyoGai").html("");
        $(".FrmSCUriageMeisai.lblSin_FgouGenri").html("");
        $(".FrmSCUriageMeisai.lblSin_HonbuFtan").html("");
        $(".FrmSCUriageMeisai.lblSin_KyotenSoneki").html("");
        $(".FrmSCUriageMeisai.lblSin_TegataSue").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Kensa").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Mochikomi").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Syako").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Nousya").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Sitadori").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Satei").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Unchin").html("");
        $(".FrmSCUriageMeisai.lblSin_Tou_Sonota").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_Kensa").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_Mochikomi").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_Syako").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_Haisya").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_Syousyo").html("");
        $(".FrmSCUriageMeisai.lblSin_Kzi_JAF").html("");
        $(".FrmSCUriageMeisai.lblSin_JidosyaZei").html("");
        $(".FrmSCUriageMeisai.lblSin_SyutokuZei").html("");
        $(".FrmSCUriageMeisai.lblSin_JyuryoZei").html("");
        $(".FrmSCUriageMeisai.lblSin_Jibaiseki_Tuki").html("");
        $(".FrmSCUriageMeisai.lblSin_Jibaiseki").html("");
        $(".FrmSCUriageMeisai.lblSin_NiniHoken").html("");
        $(".FrmSCUriageMeisai.lblSin_ZeiHokenKei").html("");
        $(".FrmSCUriageMeisai.lblSin_KaisyaKN").html("");
        $(".FrmSCUriageMeisai.lblSin_CarSyurui").html("");
        $(".FrmSCUriageMeisai.lblSin_CarColor").html("");
        $(".FrmSCUriageMeisai.lblSin_KihonMargin").html("");
        $(".FrmSCUriageMeisai.lblSin_RuisinMargin").html("");
        $(".FrmSCUriageMeisai.lblSin_Syoureikin").html("");
        $(".FrmSCUriageMeisai.lblSin_GoukeiMargin").html("");
        $(".FrmSCUriageMeisai.lblSin_SyoureiMargin").html("");
        $(".FrmSCUriageMeisai.lblSin_HnbShiharai_CD").html("");
        $(".FrmSCUriageMeisai.lblSin_HnbShiharaiNM").html("");
        $(".FrmSCUriageMeisai.lblSin_HnbHanbaiTes").html("");
        $(".FrmSCUriageMeisai.lblSin_HnbSyouhizei").html("");
        $(".FrmSCUriageMeisai.lblSin_CuregitKaisya").html("");
        $(".FrmSCUriageMeisai.lblSin_SyouninNO").html("");
        $(".FrmSCUriageMeisai.lblSin_KBKin").html("");
        $(".FrmSCUriageMeisai.lblSinYotakKB").html("");
        $(".FrmSCUriageMeisai.lblSinRcyYptKin").html("");
        $(".FrmSCUriageMeisai.lblsinRcySknKanHi").html("");
    };
    //中古車
    me.subChukosyaFormClear = function () {
        $(".FrmSCUriageMeisai.lblCko_Syaryo_kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_Syaryo_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Syaryo_Kjn").html("");
        $(".FrmSCUriageMeisai.lblCko_Syaryo_SE").html("");
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Kjn").html("");
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_SE").html("");
        $(".FrmSCUriageMeisai.lblCko_Kappu_Kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_Kappu_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Kappu_Kjn").html("");
        $(".FrmSCUriageMeisai.lblCko_Kappu_SE").html("");
        $(".FrmSCUriageMeisai.lblCko_Touroku_Kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_Touroku_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Touroku_Kjn").html("");
        $(".FrmSCUriageMeisai.lblCko_Touroku_SE").html("");
        $(".FrmSCUriageMeisai.lblCko_Azukari_Kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_Azukari_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho").html("");
        $(".FrmSCUriageMeisai.lblCko_Zansai").html("");
        $(".FrmSCUriageMeisai.lblCko_GK_Kyk").html("");
        $(".FrmSCUriageMeisai.lblCko_GK_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_GK_SE").html("");
        $(".FrmSCUriageMeisai.lblCko_SitKakaku").html("");
        $(".FrmSCUriageMeisai.lblCko_SitShz").html("");
        $(".FrmSCUriageMeisai.lblCko_Atamakin").html("");
        $(".FrmSCUriageMeisai.lblCko_TouShoHi").html("");
        $(".FrmSCUriageMeisai.lblCko_Tegata_Kai").html("");
        $(".FrmSCUriageMeisai.lblCko_Tegata_Kin").html("");
        $(".FrmSCUriageMeisai.lblCko_Curegit_Kai").html("");
        $(".FrmSCUriageMeisai.lblCko_Curegit_Kin").html("");
        $(".FrmSCUriageMeisai.lblCko_SitadoriSatei").html("");
        $(".FrmSCUriageMeisai.lblCko_SitadoriSoneki").html("");
        $(".FrmSCUriageMeisai.lblCko_HanbaiTes").html("");
        $(".FrmSCUriageMeisai.lblCko_SyoukaiRyo").html("");
        $(".FrmSCUriageMeisai.lblCko_KjnSoneki").html("");
        $(".FrmSCUriageMeisai.lblCko_Uchikomi").html("");
        $(".FrmSCUriageMeisai.lblCko_Genri").html("");
        $(".FrmSCUriageMeisai.lblCko_MikeikaJibai").html("");
        $(".FrmSCUriageMeisai.lblCko_SasRie").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Kensa").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Mochikomi").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Syako").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Nousya").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Sit").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Satei").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Niji").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_Sonota").html("");
        $(".FrmSCUriageMeisai.lblCko_Tou_GK").html("");
        $(".FrmSCUriageMeisai.lblCko_Azk_Kensa").html("");
        $(".FrmSCUriageMeisai.lblCko_Azk_Mochikomi").html("");
        $(".FrmSCUriageMeisai.lblCko_Azk_Syako").html("");
        $(".FrmSCUriageMeisai.lblCko_Azk_Haisya").html("");
        $(".FrmSCUriageMeisai.lblCko_Azk_GK").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jidosya").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_MiJidosya").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Syutoku").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jyuryo").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jibai").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_MiJibai").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_NiniHoken").html("");
        $(".FrmSCUriageMeisai.lblCko_Zeiho_GK").html("");
        $(".FrmSCUriageMeisai.lblCko_Han_ShiharaiCD").html("");
        $(".FrmSCUriageMeisai.lblCko_Han_ShiharaiNM").html("");
        $(".FrmSCUriageMeisai.lblCko_Han_KBN").html("");
        $(".FrmSCUriageMeisai.lblCko_Han_HanbaiTes").html("");
        $(".FrmSCUriageMeisai.lblCko_Han_Shz").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Kin").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Satei").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Mitumori").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Syogakari").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Uchikomi").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_SeibiKbn").html("");
        $(".FrmSCUriageMeisai.lblCko_Sit_Meigi").html("");
        $(".FrmSCUriageMeisai.lblCko_Jyohen1").html("");
        $(".FrmSCUriageMeisai.lblCko_Jyohen2").html("");
        $(".FrmSCUriageMeisai.lblCko_Jyohen3").html("");
        $(".FrmSCUriageMeisai.lblCko_HnbKB").html("");
        $(".FrmSCUriageMeisai.lblCko_HnbMei").html("");
        $(".FrmSCUriageMeisai.lblCko_NyukaKB").html("");
        $(".FrmSCUriageMeisai.lblCko_NyukaMei").html("");
        $(".FrmSCUriageMeisai.lblChuYotakKB").html("");
        $(".FrmSCUriageMeisai.lblChuRcyYptKin").html("");
        $(".FrmSCUriageMeisai.lblChuRcySknKanHi").html("");
    };
    //契約者
    me.subKeiyakusyaFormClear = function () {
        $(".FrmSCUriageMeisai.lblKeiYubinNO").html("");
        $(".FrmSCUriageMeisai.lblKeiJyusyoNM1").html("");
        $(".FrmSCUriageMeisai.lblKeiJyusyoNM2").html("");
        $(".FrmSCUriageMeisai.lblKeiTel").html("");
        $(".FrmSCUriageMeisai.lblKeiShimeiKN").html("");
        $(".FrmSCUriageMeisai.lblKeiShimeiNM1").html("");
        $(".FrmSCUriageMeisai.lblKeiShimeiNM2").html("");
        $(".FrmSCUriageMeisai.lblKeiKinmuTel").html("");
        $(".FrmSCUriageMeisai.lblKeiKziKB").html("");
        $(".FrmSCUriageMeisai.lblKeiKziNM").html("");
        $(".FrmSCUriageMeisai.lblMeiYubinNO").html("");
        $(".FrmSCUriageMeisai.lblMeiJyusyoNM1").html("");
        $(".FrmSCUriageMeisai.lblMeiJyusyoNM2").html("");
        $(".FrmSCUriageMeisai.lblMeiTel").html("");
        $(".FrmSCUriageMeisai.lblMeiShimeiKN").html("");
        $(".FrmSCUriageMeisai.lblMeiShimeiNM1").html("");
        $(".FrmSCUriageMeisai.lblMeiShimeiNM2").html("");
        $(".FrmSCUriageMeisai.lblMeiKinmuTel").html("");
        $(".FrmSCUriageMeisai.lblKeiyakutenCD").html("");
        $(".FrmSCUriageMeisai.lblKeiyakutenNM").html("");
        $(".FrmSCUriageMeisai.lblTourokuTenCD").html("");
        $(".FrmSCUriageMeisai.lblTourokuTenNM").html("");
        $(".FrmSCUriageMeisai.lblNinteiCD").html("");
        $(".FrmSCUriageMeisai.lblHanbaiKeitai").html("");
        $(".FrmSCUriageMeisai.lblSyoyukenKB").html("");
        $(".FrmSCUriageMeisai.lblSyoyuken").html("");
        $(".FrmSCUriageMeisai.lblNyukoYakuKB").html("");
        $(".FrmSCUriageMeisai.lblNyukoYakusoku").html("");
        $(".FrmSCUriageMeisai.lblDMKB").html("");
        $(".FrmSCUriageMeisai.lblDM").html("");
        $(".FrmSCUriageMeisai.lblAfterKB").html("");
        $(".FrmSCUriageMeisai.lblAfter").html("");
        $(".FrmSCUriageMeisai.lblYoutoKB").html("");
        $(".FrmSCUriageMeisai.lblYouto").html("");
        $(".FrmSCUriageMeisai.lblKyosinkaiKyk").html("");
        $(".FrmSCUriageMeisai.lblKyosinkaiSki").html("");
        $(".FrmSCUriageMeisai.lblKyousinkaiKou").html("");
    };
    //下取１
    me.subSitadori1FormClear = function () {
        $(".FrmSCUriageMeisai.lblSit1SeiriNO").html("");
        $(".FrmSCUriageMeisai.lblSit1GenSiki").html("");
        $(".FrmSCUriageMeisai.lblSit1SitadoriSW").html("");
        $(".FrmSCUriageMeisai.lblSit1Meigara").html("");
        $(".FrmSCUriageMeisai.lblSit1Syamei").html("");
        $(".FrmSCUriageMeisai.lblSit1Syonendo").html("");
        $(".FrmSCUriageMeisai.lblSit1Katasiki").html("");
        $(".FrmSCUriageMeisai.lblSit1Syadai").html("");
        $(".FrmSCUriageMeisai.lblSit1KataSitei").html("");
        $(".FrmSCUriageMeisai.lblSit1Ruibetu").html("");
        $(".FrmSCUriageMeisai.lblSit1TourokuBi").html("");
        $(".FrmSCUriageMeisai.lblSit1TourokuNO").html("");
        $(".FrmSCUriageMeisai.lblSit1Rikuji").html("");
        $(".FrmSCUriageMeisai.lblSit1SitadoriKin").html("");
        $(".FrmSCUriageMeisai.lblSit1SateiKin").html("");
        $(".FrmSCUriageMeisai.lblSit1ShzRt").html("");
        $(".FrmSCUriageMeisai.lblSit1ShzGaku").html("");
        $(".FrmSCUriageMeisai.lblSit1YotakGK").html("");
        $(".FrmSCUriageMeisai.lblSit1ShikinKnrRyokin").html("");
        $(".FrmSCUriageMeisai.lblSit1YotakKB").html("");
        $(".FrmSCUriageMeisai.lblSit1TebanashiKB").html("");
    };
    //下取2
    me.subSitadori2FormClear = function () {
        $(".FrmSCUriageMeisai.lblSit2SeiriNO").html("");
        $(".FrmSCUriageMeisai.lblSit2GenSiki").html("");
        $(".FrmSCUriageMeisai.lblSit2SitadoriSW").html("");
        $(".FrmSCUriageMeisai.lblSit2Meigara").html("");
        $(".FrmSCUriageMeisai.lblSit2Syamei").html("");
        $(".FrmSCUriageMeisai.lblSit2Syonendo").html("");
        $(".FrmSCUriageMeisai.lblSit2Katasiki").html("");
        $(".FrmSCUriageMeisai.lblSit2Syadai").html("");
        $(".FrmSCUriageMeisai.lblSit2KataSitei").html("");
        $(".FrmSCUriageMeisai.lblSit2Ruibetu").html("");
        $(".FrmSCUriageMeisai.lblSit2TourokuBi").html("");
        $(".FrmSCUriageMeisai.lblSit2TourokuNO").html("");
        $(".FrmSCUriageMeisai.lblSit2Rikuji").html("");
        $(".FrmSCUriageMeisai.lblSit2SitadoriKin").html("");
        $(".FrmSCUriageMeisai.lblSit2SateiKin").html("");
        $(".FrmSCUriageMeisai.lblSit2ShzRt").html("");
        $(".FrmSCUriageMeisai.lblSit2ShzGaku").html("");
        $(".FrmSCUriageMeisai.lblSit2YotakGK").html("");
        $(".FrmSCUriageMeisai.lblSit2ShikinKnrRyokin").html("");
        $(".FrmSCUriageMeisai.lblSit2YotakKB").html("");
        $(".FrmSCUriageMeisai.lblSit2TebanashiKB").html("");
    };
    //下取3
    me.subSitadori3FormClear = function () {
        $(".FrmSCUriageMeisai.lblSit3SeiriNO").html("");
        $(".FrmSCUriageMeisai.lblSit3GenSiki").html("");
        $(".FrmSCUriageMeisai.lblSit3SitadoriSW").html("");
        $(".FrmSCUriageMeisai.lblSit3Meigara").html("");
        $(".FrmSCUriageMeisai.lblSit3Syamei").html("");
        $(".FrmSCUriageMeisai.lblSit3Syonendo").html("");
        $(".FrmSCUriageMeisai.lblSit3Katasiki").html("");
        $(".FrmSCUriageMeisai.lblSit3Syadai").html("");
        $(".FrmSCUriageMeisai.lblSit3KataSitei").html("");
        $(".FrmSCUriageMeisai.lblSit3Ruibetu").html("");
        $(".FrmSCUriageMeisai.lblSit3TourokuBi").html("");
        $(".FrmSCUriageMeisai.lblSit3TourokuNO").html("");
        $(".FrmSCUriageMeisai.lblSit3Rikuji").html("");
        $(".FrmSCUriageMeisai.lblSit3SitadoriKin").html("");
        $(".FrmSCUriageMeisai.lblSit3SateiKin").html("");
        $(".FrmSCUriageMeisai.lblSit3ShzRt").html("");
        $(".FrmSCUriageMeisai.lblSit3ShzGaku").html("");
        $(".FrmSCUriageMeisai.lblSit3YotakGK").html("");
        $(".FrmSCUriageMeisai.lblSit3ShikinKnrRyokin").html("");
        $(".FrmSCUriageMeisai.lblSit3YotakKB").html("");
        $(".FrmSCUriageMeisai.lblSit3TebanashiKB").html("");
    };

    me.subFormHedderSet = function (objDr) {
        $(".FrmSCUriageMeisai.lblCMNNO").html(
            me.clsComFnc.FncNv(objDr["CMN_NO"])
        );
        $(".FrmSCUriageMeisai.lblUCNO").html(
            me.clsComFnc.FncNv(objDr["UC_NO"])
        );
        $(".FrmSCUriageMeisai.lblBusyoCD").html(
            me.clsComFnc.FncNv(objDr["URI_BUSYO_CD"])
        );
        $(".FrmSCUriageMeisai.lblBusyoNM").html(
            me.clsComFnc.FncNv(objDr["BUSYO_MEI"])
        );
        $(".FrmSCUriageMeisai.lblSyainCD").html(
            me.clsComFnc.FncNv(objDr["URI_TANNO"])
        );
        $(".FrmSCUriageMeisai.lblSyainNM").html(
            me.clsComFnc.FncNv(objDr["SYAIN_MEI"])
        );
        $(".FrmSCUriageMeisai.lblGyousyaCD").html(
            me.clsComFnc.FncNv(objDr["URI_GYOSYA"])
        );
        $(".FrmSCUriageMeisai.lblGyousyaNM").html(
            me.clsComFnc.FncNv(objDr["GYOUSYA_MEI"])
        );

        $(".FrmSCUriageMeisai.lblKei_Jyusyo").html(
            me.clsComFnc.FncNv(objDr["KYK_ADR_NOKI_KNJ"]) +
            me.clsComFnc.FncNv(objDr["KYK_ADR_TUSYO_KNJ"]) +
            me.clsComFnc.FncNv(objDr["KYK_ADR_MEI"])
        );

        $(".FrmSCUriageMeisai.lblKei_Shimei").html(
            me.clsComFnc.FncNv(objDr["KYK_MEI_KNJ1"]) +
            " " +
            me.clsComFnc.FncNv(objDr["KYK_MEI_KNJ2"])
        );
        $(".FrmSCUriageMeisai.lblUriageBi").html(
            me.clsComFnc.FncNv(objDr["URG_DATE"])
        );
        $(".FrmSCUriageMeisai.lblKeiriBi").html(
            me.clsComFnc.FncNv(objDr["KRI_DATE"])
        );
        $(".FrmSCUriageMeisai.lblJhnBi").html(
            me.clsComFnc.FncNv(objDr["JKN_HKD"])
        );
        $(".FrmSCUriageMeisai.lblCelBi").html(
            me.clsComFnc.FncNv(objDr["CEL_DATE"])
        );
        // 新車ＵＣと中古車ＵＣの表示場所を修正
        if (me.clsComFnc.FncNv(objDr["NAU_KB"]) == "1") {
            $(".FrmSCUriageMeisai.lblSinsyaUCNO").html(
                me.clsComFnc.FncNv(objDr["UC_NO"])
            );
        } else {
            $(".FrmSCUriageMeisai.lblSinsyaUCNO").html("");
        }

        $(".FrmSCUriageMeisai.lblSeiriNO").html(
            me.clsComFnc.FncNv(objDr["CKO_CAR_SER_NO"])
        );
        // 新車ＵＣと中古車ＵＣの表示場所を修正
        if (me.clsComFnc.FncNv(objDr["NAU_KB"]) == "2") {
            $(".FrmSCUriageMeisai.lblChukoUCNO").html(
                me.clsComFnc.FncNv(objDr["UC_NO"])
            );
        } else {
            $(".FrmSCUriageMeisai.lblChukoUCNO").html("");
        }

        $(".FrmSCUriageMeisai.lblMeigara").html(
            me.clsComFnc.FncNv(objDr["MAKER_CD"])
        );
        $(".FrmSCUriageMeisai.lblNensei").html(
            me.clsComFnc.FncNv(objDr["NENSIKI"])
        );
        $(".FrmSCUriageMeisai.lblSyamei").html(
            me.clsComFnc.FncNv(objDr["SYADAI"])
        );
        $(".FrmSCUriageMeisai.lblKatasiki").html(
            me.clsComFnc.FncNv(objDr["NINKATA_CD"])
        );
        $(".FrmSCUriageMeisai.lblKosyo").html(
            me.clsComFnc.FncNv(objDr["TOA_NAME"])
        );
        $(".FrmSCUriageMeisai.lblSyasyuCD").html(
            me.clsComFnc.FncNv(objDr["SS_CD"])
        );
        $(".FrmSCUriageMeisai.lblCarNO").html(
            me.clsComFnc.FncNv(objDr["CARNO"])
        );
        $(".FrmSCUriageMeisai.lblRuibetu").html(
            me.clsComFnc.FncNv(objDr["SITEI_NO"])
        );

        $(".FrmSCUriageMeisai.lblTourokuNO").html(
            me.clsComFnc.FncNv(objDr["TOURK_NO1"]) +
            me.clsComFnc.FncNv(objDr["TOURK_NO2"]) +
            me.clsComFnc.FncNv(objDr["TOURK_NO3"])
        );

        $(".FrmSCUriageMeisai.lblSyonendo").html(
            me.clsComFnc
                .FncNv(objDr["TOU_DATE"])
                .toString()
                .padRight(8)
                .substr(0, 4) +
            "年" +
            me.clsComFnc
                .FncNv(objDr["TOU_DATE"])
                .toString()
                .padRight(8)
                .substr(4, 2) +
            "月"
        );

        $(".FrmSCUriageMeisai.lblTourokuBi").html(
            me.clsComFnc
                .FncNv(objDr["TOU_DATE"])
                .toString()
                .padRight(8)
                .substr(0, 4) +
            "年" +
            me.clsComFnc
                .FncNv(objDr["TOU_DATE"])
                .toString()
                .padRight(8)
                .substr(4, 2) +
            "月" +
            me.clsComFnc
                .FncNv(objDr["TOU_DATE"])
                .toString()
                .padRight(8)
                .substr(6, 2) +
            "日"
        );

        $(".FrmSCUriageMeisai.lblRikuji").html(
            me.clsComFnc.FncNv(objDr["RIKUJI_CD"])
        );
    };

    me.subFormSinsyaSet = function (objDr) {
        $(".FrmSCUriageMeisai.lblSin_Hontai_Kyk").html(
            me.clsComFnc.FncNz(objDr["SRY_PRC"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Hontai_Shz").html(
            me.clsComFnc.FncNz(objDr["SRY_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Hontai_Gen").html(
            me.clsComFnc.FncNz(objDr["SRY_KTN_PCS"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Nebiki_Kyk").html(
            me.clsComFnc.FncNz(objDr["SRY_NBK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Kyk").html(
            me.clsComFnc.FncNz(objDr["FHZ_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Shz").html(
            me.clsComFnc.FncNz(objDr["FHZ_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Fuzoku_Gen").html(
            me.clsComFnc.FncNz(objDr["FHZ_PCS"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Kyk").html(
            me.clsComFnc.FncNz(objDr["TKB_KSH_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Shz").html(
            me.clsComFnc.FncNz(objDr["TKB_KSH_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tokubetu_Gen").html(
            me.clsComFnc.FncNz(objDr["TKB_KSH_PCS"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kappu_Kyk").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kappu_Shz").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kappu_Gen").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_KJN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Kyk").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Shz").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tousyohi_Gen").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KJN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Azukari_Kyk").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Azukari_Gen").html(
            me.clsComFnc.FncNz(objDr["NEBIKI_RT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_ZeiHo_kyk").html(
            me.clsComFnc.FncNz(objDr["HKN_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Syouhizei").html(
            me.clsComFnc.FncNz(objDr["SHZ_KEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Zansai").html(
            me.clsComFnc.FncNz(objDr["TRA_CAR_ZSI_SUM"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_ShiharaiKei").html(
            me.clsComFnc.FncNz(objDr["SHR_GK_SUM"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SitadoriKin").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SitadoriShz").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Atamakin").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_ATM_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SyoZeiHo").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_TRK_SYH"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tegata").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_TGT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Curegit").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_KRJ_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_KappuGankin").html(
            me.clsComFnc.FncNz(objDr["KAP_GKN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SyunyuTes").html(
            me.clsComFnc.FncNz(objDr["UKM_SNY_TES"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Syoureikin").html(
            me.clsComFnc.FncNz(objDr["UKM_SINSEI_SYR"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_HanbaiTes").html(
            me.clsComFnc.FncNz(objDr["HNB_TES_GKU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SonotaSyoukai").html(
            me.clsComFnc.FncNz(objDr["ETC_SKI_RYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Penalty").html(
            me.clsComFnc.FncNz(objDr["PENALTY"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_EigyoGai").html(
            me.clsComFnc.FncNz(objDr["EGO_GAI_SYUEKI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_FgouGenri").html(
            me.clsComFnc.FncNz(objDr["SRY_GENKAI_RIE"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_HonbuFtan").html(
            me.clsComFnc.FncNz(objDr["HONBU_FTK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_KyotenSoneki").html(
            me.clsComFnc.FncNz(objDr["SAI_SONEKI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_TegataSue").html(
            me.clsComFnc.FncNz(objDr["TGT_SIT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Kensa").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Mochikomi").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SYAKEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Syako").html(
            me.clsComFnc
                .FncNz(objDr["TOU_SYH_SYAKO_SYO"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Nousya").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_NOUSYA"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Sitadori").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SIT_TTK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Satei").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SATEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Unchin").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_JIKOU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Tou_Sonota").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_ETC"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_Kensa").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_KEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_Mochikomi").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_SYAKEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_Syako").html(
            me.clsComFnc
                .FncNz(objDr["HOUTEIH_SYAKO_SYO"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_Haisya").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_SIT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_Syousyo").html(
            me.clsComFnc.FncNz(objDr["KOUSEI_SYOSYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Kzi_JAF").html(
            me.clsComFnc.FncNz(objDr["JAF"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_JidosyaZei").html(
            me.clsComFnc.FncNz(objDr["JIDOUSYA_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SyutokuZei").html(
            me.clsComFnc.FncNz(objDr["SYARYOU_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_JyuryoZei").html(
            me.clsComFnc.FncNz(objDr["JYURYO_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_Jibaiseki_Tuki").html(
            me.clsComFnc.FncNv(objDr["JIBAI_TUKI_SU"])
        );
        $(".FrmSCUriageMeisai.lblSin_Jibaiseki").html(
            me.clsComFnc.FncNz(objDr["JIBAI_HOK_RYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_NiniHoken").html(
            me.clsComFnc.FncNz(objDr["OPTHOK_RYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_ZeiHokenKei").html(
            me.clsComFnc.FncNz(objDr["HKN_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_KaisyaKN").html(
            me.clsComFnc.FncNv(objDr["JIBAI_KAISYA"])
        );
        $(".FrmSCUriageMeisai.lblSin_CarSyurui").html(
            me.clsComFnc.FncNv(objDr["JIBAI_CAR_KND"])
        );
        $(".FrmSCUriageMeisai.lblSin_CarColor").html(
            me.clsComFnc.FncNv(objDr["JIBAI_ICOL_CD"]) +
            " " +
            me.clsComFnc.FncNv(objDr["CLR_NM"])
        );
        $(".FrmSCUriageMeisai.lblSin_KihonMargin").html(
            me.clsComFnc.FncNz(objDr["TOK_KEI_KHN_MGN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_RuisinMargin").html(
            me.clsComFnc.FncNz(objDr["TOK_KEI_RUI_MGN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_SyoureiMargin").html(
            me.clsComFnc.FncNz(objDr["TOK_KEI_KHN_SYR"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_GoukeiMargin").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TOK_KEI_KHN_MGN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TOK_KEI_RUI_MGN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TOK_KEI_KHN_SYR"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblSin_HnbShiharai_CD").html(
            me.clsComFnc.FncNv(objDr["HNB_TES_RYO_KZI_KBN"])
        );
        $(".FrmSCUriageMeisai.lblSin_HnbShiharaiNM").html(
            me.clsComFnc.FncNv(objDr["SHIHARAI_MEI"])
        );
        $(".FrmSCUriageMeisai.lblSin_HnbHanbaiTes").html(
            me.clsComFnc.FncNz(objDr["HNB_TES_GKU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSin_HnbSyouhizei").html(
            me.clsComFnc.FncNv(objDr["HNB_SHZ"])
        );
        $(".FrmSCUriageMeisai.lblSin_CuregitKaisya").html(
            me.clsComFnc.FncNv(objDr["KUREJITGAISYA"])
        );
        $(".FrmSCUriageMeisai.lblSin_SyouninNO").html(
            me.clsComFnc.FncNv(objDr["KUREJIT_NO"])
        );
        $(".FrmSCUriageMeisai.lblSin_KBKin").html(
            me.clsComFnc.FncNz(objDr["KICK_BACK"]).toString().numFormat()
        );

        $(".FrmSCUriageMeisai.lblSin_CuregitNm").html(
            me.clsComFnc.FncNv(objDr["CRE_NM"])
        );
        $(".FrmSCUriageMeisai.lblSinYotakKB").html(
            me.clsComFnc.FncNv(objDr["YOTAK_KB"])
        );
        $(".FrmSCUriageMeisai.lblsinRcySknKanHi").html(
            me.clsComFnc.FncNz(objDr["RCY_SKN_KAN_HI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSinRcyYptKin").html(
            me.clsComFnc.FncNz(objDr["RCY_YOT_KIN"]).toString().numFormat()
        );
    };

    me.subFormChukoSet = function (objDr) {
        $(".FrmSCUriageMeisai.lblCko_Syaryo_kyk").html(
            me.clsComFnc.FncNz(objDr["SRY_PRC"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Syaryo_Shz").html(
            me.clsComFnc.FncNz(objDr["SRY_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Syaryo_Kjn").html(
            me.clsComFnc.FncNz(objDr["SRY_KTN_PCS"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Syaryo_SE").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["SRY_PRC"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SRY_KTN_PCS"]))
            )
                .toString()
                .numFormat()
        );
        //特別架装品に添付品金額を加算
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Kyk").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_KYK"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_KYK"]))
            )
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Shz").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_SHZ"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_SHZ"]))
            )
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_Kjn").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_PCS"]))
            )
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tokubetu_SE").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_PCS"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblCko_Kappu_Kyk").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Kappu_Shz").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Kappu_Kjn").html(
            me.clsComFnc.FncNz(objDr["KAP_TES_KJN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Kappu_SE").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KJN"]))
            )
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Touroku_Kyk").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KYK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Touroku_Shz").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Touroku_Kjn").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KJN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Touroku_SE").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KJN"]))
            )
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Azukari_Kyk").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_GK"]).toString().numFormat()
        );
        //添付品金額を加算
        $(".FrmSCUriageMeisai.lblCko_Azukari_Shz").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["SRY_SHZ"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_SHZ"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_SHZ"])) +
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_SHZ"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_SHZ"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblCko_Zeiho").html(
            me.clsComFnc.FncNz(objDr["HKN_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zansai").html(
            me.clsComFnc.FncNz(objDr["TRA_CAR_ZSI_SUM"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_GK_Kyk").html(
            me.clsComFnc.FncNz(objDr["SHR_GK_SUM"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_GK_Shz").html(
            me.clsComFnc.FncNz(objDr["SHZ_KEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_GK_SE").html(
            me.clsComFnc.FncNz(objDr["SAI_SONEKI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_SitKakaku").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_SitShz").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Atamakin").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_ATM_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_TouShoHi").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_TRK_SYH"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tegata_Kai").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_TGT_KAI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tegata_Kin").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_TGT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Curegit_Kai").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_KRJ_KAI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Curegit_Kin").html(
            me.clsComFnc.FncNz(objDr["SHR_JKN_KRJ_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_SitadoriSatei").html(
            me.clsComFnc.FncNz(objDr["TRA_CAR_STI_SUM"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_SitadoriSoneki").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_STI_SUM"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_PRC_SUM"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_KIN"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblCko_HanbaiTes").html(
            me.clsComFnc.FncNz(objDr["HNB_TES_GKU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_SyoukaiRyo").html(
            me.clsComFnc.FncNz(objDr["ETC_SKI_RYO"]).toString().numFormat()
        );
        //添付品金額を加算
        $(".FrmSCUriageMeisai.lblCko_KjnSoneki").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["SRY_PRC"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SRY_KTN_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KJN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KJN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_STI_SUM"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_PRC_SUM"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_KIN"])) -
                parseInt(me.clsComFnc.FncNz(objDr["HNB_TES_GKU"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblCko_Uchikomi").html(
            me.clsComFnc.FncNz(objDr["UKM_SNY_TES"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Genri").html(
            me.clsComFnc.FncNz(objDr["SAI_SONEKI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_MikeikaJibai").html(
            me.clsComFnc
                .FncNz(objDr["CKO_MIKEI_JIBAI_KIN"])
                .toString()
                .numFormat()
        );
        //添付品金額を加算
        $(".FrmSCUriageMeisai.lblCko_SasRie").html(
            (
                parseInt(me.clsComFnc.FncNz(objDr["SRY_PRC"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SRY_KTN_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TKB_KSH_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["FHZ_PCS"])) +
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["KAP_TES_KJN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KYK"])) -
                parseInt(me.clsComFnc.FncNz(objDr["TOU_SYH_KJN"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_STI_SUM"])) +
                parseInt(me.clsComFnc.FncNz(objDr["TRA_CAR_PRC_SUM"])) -
                parseInt(me.clsComFnc.FncNz(objDr["SHR_JKN_SIT_KIN"])) -
                parseInt(me.clsComFnc.FncNz(objDr["HNB_TES_GKU"])) +
                parseInt(me.clsComFnc.FncNz(objDr["UKM_SNY_TES"])) +
                parseInt(me.clsComFnc.FncNz(objDr["CKO_MIKEI_JDO_KIN"]))
            )
                .toString()
                .numFormat()
        );

        $(".FrmSCUriageMeisai.lblCko_Tou_Kensa").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_KEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Mochikomi").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SYAKEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Syako").html(
            me.clsComFnc
                .FncNz(objDr["TOU_SYH_SYAKO_SYO"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Nousya").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_NOUSYA"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Sit").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SIT_TTK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Satei").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_SATEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Niji").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_JIKOU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_Sonota").html(
            me.clsComFnc.FncNz(objDr["TOU_SYH_ETC"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Tou_GK").html(0);
        $(".FrmSCUriageMeisai.lblCko_Azk_Kensa").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_KEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Azk_Mochikomi").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_SYAKEN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Azk_Syako").html(
            me.clsComFnc
                .FncNz(objDr["HOUTEIH_SYAKO_SYO"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Azk_Haisya").html(
            me.clsComFnc.FncNz(objDr["HOUTEIH_SIT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Azk_GK").html(0);
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jidosya").html(
            me.clsComFnc.FncNz(objDr["JIDOUSYA_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_MiJidosya").html(
            me.clsComFnc
                .FncNz(objDr["CKO_MIKEI_JDO_KIN"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Syutoku").html(
            me.clsComFnc.FncNz(objDr["SYARYOU_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jyuryo").html(
            me.clsComFnc.FncNz(objDr["JYURYO_ZEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Shz").html(
            me.clsComFnc.FncNz(objDr["SHZ_KEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_Jibai").html(
            me.clsComFnc.FncNz(objDr["JIBAI_HOK_RYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_MiJibai").html(
            me.clsComFnc
                .FncNz(objDr["CKO_MIKEI_JIBAI_KIN"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_NiniHoken").html(
            me.clsComFnc.FncNz(objDr["OPTHOK_RYO"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Zeiho_GK").html(0);
        $(".FrmSCUriageMeisai.lblCko_Han_ShiharaiCD").html(
            me.clsComFnc.FncNz(objDr["HNB_TES_RYO_SHR_CD"])
        );
        $(".FrmSCUriageMeisai.lblCko_Han_ShiharaiNM").html(
            me.clsComFnc.FncNv(objDr["SHIHARAI_MEI"])
        );
        $(".FrmSCUriageMeisai.lblCko_Han_KBN").html(
            me.clsComFnc.FncNv(objDr["HNB_TES_RYO_KZI_KBN"])
        );
        $(".FrmSCUriageMeisai.lblCko_Han_HanbaiTes").html(
            me.clsComFnc.FncNz(objDr["HNB_TES_GKU"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Han_Shz").html(
            me.clsComFnc.FncNz(objDr["HNB_SHZ"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Kin").html(
            me.clsComFnc.FncNz(objDr["CKO_BAI_SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Satei").html(
            me.clsComFnc.FncNz(objDr["CKO_BAI_SATEI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Mitumori").html(
            me.clsComFnc.FncNz(objDr["CKO_SAI_MITUMORI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Syogakari").html(
            me.clsComFnc.FncNz(objDr["CKO_SYOGAKARI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Uchikomi").html(
            me.clsComFnc.FncNz(objDr["UKM_SNY_TES"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_SeibiKbn").html(
            me.clsComFnc.FncNv(objDr["CKO_SEB_KB"])
        );
        $(".FrmSCUriageMeisai.lblCko_Sit_Meigi").html(
            me.clsComFnc.FncNv(objDr["CKO_MEG_KB"])
        );
        $(".FrmSCUriageMeisai.lblCko_Jyohen1").html(
            me.clsComFnc.FncNv(objDr["JKN_HKD"])
        );
        $(".FrmSCUriageMeisai.lblCko_Jyohen2").html(
            me.clsComFnc.FncNv(objDr["JKN_NO"])
        );
        $(".FrmSCUriageMeisai.lblCko_Jyohen3").html(
            me.clsComFnc.FncNv(objDr["KAIYAKU"])
        );
        $(".FrmSCUriageMeisai.lblCko_HnbKB").html(
            me.clsComFnc.FncNv(objDr["CKO_HNB_KB"])
        );
        $(".FrmSCUriageMeisai.lblCko_HnbMei").html(
            me.clsComFnc.FncNv(objDr["HNB_NM"])
        );
        $(".FrmSCUriageMeisai.lblCko_NyukaKB").html(
            me.clsComFnc.FncNv(objDr["CKO_SIR_KB"])
        );
        $(".FrmSCUriageMeisai.lblCko_NyukaMei").html(
            me.clsComFnc.FncNv(objDr["SIR_NM"])
        );
        $(".FrmSCUriageMeisai.lblChuYotakKB").html(
            me.clsComFnc.FncNv(objDr["YOTAK_KB"])
        );
        $(".FrmSCUriageMeisai.lblChuRcySknKanHi").html(
            me.clsComFnc.FncNz(objDr["RCY_SKN_KAN_HI"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblChuRcyYptKin").html(
            me.clsComFnc.FncNz(objDr["RCY_YOT_KIN"]).toString().numFormat()
        );
    };

    me.subKeiyakusyaSet = function (objDr) {
        $(".FrmSCUriageMeisai.lblKeiYubinNO").html(
            me.clsComFnc.FncNv(objDr["KYK_YUBIN_NO"]) != ""
                ? me.clsComFnc
                    .FncNv(objDr["KYK_YUBIN_NO"])
                    .toString()
                    .padRight(7)
                    .substr(0, 3) +
                "-" +
                me.clsComFnc
                    .FncNv(objDr["KYK_YUBIN_NO"])
                    .toString()
                    .padRight(7)
                    .substr(3, 4)
                : ""
        );

        $(".FrmSCUriageMeisai.lblKeiJyusyoNM1").html(
            me.clsComFnc.FncNv(objDr["KYK_ADR_NOKI_KNJ"]) +
            me.clsComFnc.FncNv(objDr["KYK_ADR_TUSYO_KNJ"])
        );
        $(".FrmSCUriageMeisai.lblKeiJyusyoNM2").html(
            me.clsComFnc.FncNv(objDr["KYK_ADR_MEI"])
        );

        $(".FrmSCUriageMeisai.lblKeiTel").html(
            me.clsComFnc.FncNv(objDr["KYK_KEY_TEL"])
        );

        $(".FrmSCUriageMeisai.lblKeiShimeiKN").html(
            me.clsComFnc.FncNv(objDr["KYK_MEI_KN"])
        );
        $(".FrmSCUriageMeisai.lblKeiShimeiNM1").html(
            me.clsComFnc.FncNv(objDr["KYK_MEI_KNJ1"])
        );
        $(".FrmSCUriageMeisai.lblKeiShimeiNM2").html(
            me.clsComFnc.FncNv(objDr["KYK_MEI_KNJ2"])
        );

        $(".FrmSCUriageMeisai.lblKeiKziKB").html(
            me.clsComFnc.FncNv(objDr["KAZEI_KB"])
        );
        $(".FrmSCUriageMeisai.lblKeiKziNM").html(
            me.clsComFnc.FncNv(objDr["ZKB_NM"])
        );
        $(".FrmSCUriageMeisai.lblMeiYubinNO").html(
            me.clsComFnc.FncNv(objDr["MGN_YUBIN_NO"])
        );

        $(".FrmSCUriageMeisai.lblMeiJyusyoNM1").html(
            me.clsComFnc.FncNv(objDr["MGN_ADR_NOKI_KNJ"]) +
            me.clsComFnc.FncNv(objDr["MGN_ADR_TUSYO_KNJ"])
        );
        $(".FrmSCUriageMeisai.lblMeiJyusyoNM2").html(
            me.clsComFnc.FncNv(objDr["MGN_ADR_MEI"])
        );

        $(".FrmSCUriageMeisai.lblMeiTel").html(
            me.clsComFnc.FncNv(objDr["MGN_KEY_TEL"])
        );

        $(".FrmSCUriageMeisai.lblMeiShimeiKN").html(
            me.clsComFnc.FncNv(objDr["MGN_MEI_KN"])
        );
        $(".FrmSCUriageMeisai.lblMeiShimeiNM1").html(
            me.clsComFnc.FncNv(objDr["MGN_MEI_KNJ1"])
        );
        $(".FrmSCUriageMeisai.lblMeiShimeiNM2").html(
            me.clsComFnc.FncNv(objDr["MGN_MEI_KNJ2"])
        );

        $(".FrmSCUriageMeisai.lblKeiyakutenCD").html(
            me.clsComFnc.FncNv(objDr["KYK_HNS"])
        );
        $(".FrmSCUriageMeisai.lblKeiyakutenNM").html(
            me.clsComFnc.FncNv(objDr["KYK_TEN_MEI"])
        );
        $(".FrmSCUriageMeisai.lblTourokuTenCD").html(
            me.clsComFnc.FncNv(objDr["TOU_HNS"])
        );
        $(".FrmSCUriageMeisai.lblTourokuTenNM").html(
            me.clsComFnc.FncNv(objDr["TOU_TEN_MEI"])
        );
        $(".FrmSCUriageMeisai.lblNinteiCD").html(
            me.clsComFnc.FncNv(objDr["KUREJIT_NO"])
        );
        $(".FrmSCUriageMeisai.lblHanbaiKeitai").html(
            me.clsComFnc.FncNv(objDr["HNB_KB"])
        );
        $(".FrmSCUriageMeisai.lblSyoyukenKB").html(
            me.clsComFnc.FncNv(objDr["KYK_KB"])
        );
        $(".FrmSCUriageMeisai.lblSyoyuken").html(
            me.clsComFnc.FncNv(objDr["SYK_NM"])
        );
        $(".FrmSCUriageMeisai.lblNyukoYakuKB").html(
            me.clsComFnc.FncNv(objDr["NYK_KB"])
        );
        $(".FrmSCUriageMeisai.lblNyukoYakusoku").html(
            me.clsComFnc.FncNv(objDr["NYK_NM"])
        );
        $(".FrmSCUriageMeisai.lblDMKB").html(
            me.clsComFnc.FncNv(objDr["DM_KB"])
        );
        $(".FrmSCUriageMeisai.lblDM").html(me.clsComFnc.FncNv(objDr["DM_NM"]));
        $(".FrmSCUriageMeisai.lblAfterKB").html("");
        $(".FrmSCUriageMeisai.lblAfter").html("");
        $(".FrmSCUriageMeisai.lblYoutoKB").html(
            me.clsComFnc.FncNv(objDr["YOUTO_KB"])
        );
        $(".FrmSCUriageMeisai.lblYouto").html(
            me.clsComFnc.FncNv(objDr["YOT_NM"])
        );
        $(".FrmSCUriageMeisai.lblKyosinkaiKyk").html(
            me.clsComFnc.FncNv(objDr["KYOUSINKAI_KYK"])
        );
        $(".FrmSCUriageMeisai.lblKyosinkaiSki").html(
            me.clsComFnc.FncNv(objDr["KYOUSINKAI_SKI"])
        );
        $(".FrmSCUriageMeisai.lblKyousinkaiKou").html(
            me.clsComFnc.FncNv(objDr["KYOUSINKAI_KOKEN"])
        );
    };

    me.subSitadori1FormSet = function (objDrSit) {
        $(".FrmSCUriageMeisai.lblSit1SeiriNO").html(
            me.clsComFnc.FncNv(objDrSit["SEIRI_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit1GenSiki").html(
            me.clsComFnc.FncNv(objDrSit["TRA_CARSEQ_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit1SitadoriSW").html(
            me.clsComFnc.FncNv(objDrSit["SIT_SW"])
        );
        $(".FrmSCUriageMeisai.lblSit1Meigara").html(
            me.clsComFnc.FncNv(objDrSit["MEIGARA"])
        );
        $(".FrmSCUriageMeisai.lblSit1Syamei").html(
            me.clsComFnc.FncNv(objDrSit["SYAMEI"])
        );
        $(".FrmSCUriageMeisai.lblSit1Syonendo").html(
            me.clsComFnc.FncNv(objDrSit["SEIREKI_NEN"])
        );
        $(".FrmSCUriageMeisai.lblSit1Katasiki").html(
            me.clsComFnc.FncNv(objDrSit["SYAKEN_KAT"])
        );
        $(".FrmSCUriageMeisai.lblSit1Syadai").html(
            me.clsComFnc.FncNv(objDrSit["CARNO"])
        );
        $(".FrmSCUriageMeisai.lblSit1KataSitei").html(
            me.clsComFnc.FncNv(objDrSit["KATASIKI"])
        );
        $(".FrmSCUriageMeisai.lblSit1Ruibetu").html(
            me.clsComFnc.FncNv(objDrSit["RUIIBETU"])
        );
        $(".FrmSCUriageMeisai.lblSit1TourokuBi").html(
            me.clsComFnc.FncNv(objDrSit["TOU_NEN"]) +
            "年" +
            me.clsComFnc.FncNv(objDrSit["TOU_TUKI"]) +
            "月" +
            me.clsComFnc.FncNv(objDrSit["TOU_HI"])
        );
        $(".FrmSCUriageMeisai.lblSit1TourokuNO").html(
            me.clsComFnc.FncNv(objDrSit["TOUROKU_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit1Rikuji").html(
            me.clsComFnc.FncNv(objDrSit["RIKUJI"])
        );
        $(".FrmSCUriageMeisai.lblSit1SitadoriKin").html(
            me.clsComFnc.FncNz(objDrSit["SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1SateiKin").html(
            me.clsComFnc.FncNz(objDrSit["SAT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1ShzRt").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_RT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1ShzGaku").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1YotakGK").html(
            me.clsComFnc.FncNz(objDrSit["YOTAK_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1ShikinKnrRyokin").html(
            me.clsComFnc
                .FncNz(objDrSit["SHIKIN_KNR_RYOKIN"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit1YotakKB").html(
            me.clsComFnc.FncNv(objDrSit["YOTAK_KB"])
        );
        $(".FrmSCUriageMeisai.lblSit1TebanashiKB").html(
            me.clsComFnc.FncNv(objDrSit["TEBANASHI_KB"])
        );
    };
    me.subSitadori2FormSet = function (objDrSit) {
        $(".FrmSCUriageMeisai.lblSit2SeiriNO").html(
            me.clsComFnc.FncNv(objDrSit["SEIRI_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit2GenSiki").html(
            me.clsComFnc.FncNv(objDrSit["TRA_CARSEQ_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit2SitadoriSW").html(
            me.clsComFnc.FncNv(objDrSit["SIT_SW"])
        );
        $(".FrmSCUriageMeisai.lblSit2Meigara").html(
            me.clsComFnc.FncNv(objDrSit["MEIGARA"])
        );
        $(".FrmSCUriageMeisai.lblSit2Syamei").html(
            me.clsComFnc.FncNv(objDrSit["SYAMEI"])
        );
        $(".FrmSCUriageMeisai.lblSit2Syonendo").html(
            me.clsComFnc.FncNv(objDrSit["SEIREKI_NEN"])
        );
        $(".FrmSCUriageMeisai.lblSit2Katasiki").html(
            me.clsComFnc.FncNv(objDrSit["SYAKEN_KAT"])
        );
        $(".FrmSCUriageMeisai.lblSit2Syadai").html(
            me.clsComFnc.FncNv(objDrSit["CARNO"])
        );
        $(".FrmSCUriageMeisai.lblSit2KataSitei").html(
            me.clsComFnc.FncNv(objDrSit["KATASIKI"])
        );
        $(".FrmSCUriageMeisai.lblSit2Ruibetu").html(
            me.clsComFnc.FncNv(objDrSit["RUIIBETU"])
        );
        $(".FrmSCUriageMeisai.lblSit2TourokuBi").html(
            me.clsComFnc.FncNv(objDrSit["TOU_NEN"]) +
            "年" +
            me.clsComFnc.FncNv(objDrSit["TOU_TUKI"]) +
            "月" +
            me.clsComFnc.FncNv(objDrSit["TOU_HI"])
        );
        $(".FrmSCUriageMeisai.lblSit2TourokuNO").html(
            me.clsComFnc.FncNv(objDrSit["TOUROKU_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit2Rikuji").html(
            me.clsComFnc.FncNv(objDrSit["RIKUJI"])
        );
        $(".FrmSCUriageMeisai.lblSit2SitadoriKin").html(
            me.clsComFnc.FncNz(objDrSit["SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2SateiKin").html(
            me.clsComFnc.FncNz(objDrSit["SAT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2ShzRt").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_RT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2ShzGaku").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2YotakGK").html(
            me.clsComFnc.FncNz(objDrSit["YOTAK_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2ShikinKnrRyokin").html(
            me.clsComFnc
                .FncNz(objDrSit["SHIKIN_KNR_RYOKIN"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit2YotakKB").html(
            me.clsComFnc.FncNv(objDrSit["YOTAK_KB"])
        );
        $(".FrmSCUriageMeisai.lblSit2TebanashiKB").html(
            me.clsComFnc.FncNv(objDrSit["TEBANASHI_KB"])
        );
    };
    me.subSitadori3FormSet = function (objDrSit) {
        $(".FrmSCUriageMeisai.lblSit3SeiriNO").html(
            me.clsComFnc.FncNv(objDrSit["SEIRI_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit3GenSiki").html(
            me.clsComFnc.FncNv(objDrSit["TRA_CARSEQ_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit3SitadoriSW").html(
            me.clsComFnc.FncNv(objDrSit["SIT_SW"])
        );
        $(".FrmSCUriageMeisai.lblSit3Meigara").html(
            me.clsComFnc.FncNv(objDrSit["MEIGARA"])
        );
        $(".FrmSCUriageMeisai.lblSit3Syamei").html(
            me.clsComFnc.FncNv(objDrSit["SYAMEI"])
        );
        $(".FrmSCUriageMeisai.lblSit3Syonendo").html(
            me.clsComFnc.FncNv(objDrSit["SEIREKI_NEN"])
        );
        $(".FrmSCUriageMeisai.lblSit3Katasiki").html(
            me.clsComFnc.FncNv(objDrSit["SYAKEN_KAT"])
        );
        $(".FrmSCUriageMeisai.lblSit3Syadai").html(
            me.clsComFnc.FncNv(objDrSit["CARNO"])
        );
        $(".FrmSCUriageMeisai.lblSit3KataSitei").html(
            me.clsComFnc.FncNv(objDrSit["KATASIKI"])
        );
        $(".FrmSCUriageMeisai.lblSit3Ruibetu").html(
            me.clsComFnc.FncNv(objDrSit["RUIIBETU"])
        );
        $(".FrmSCUriageMeisai.lblSit3TourokuBi").html(
            me.clsComFnc.FncNv(objDrSit["TOU_NEN"]) +
            "年" +
            me.clsComFnc.FncNv(objDrSit["TOU_TUKI"]) +
            "月" +
            me.clsComFnc.FncNv(objDrSit["TOU_HI"])
        );
        $(".FrmSCUriageMeisai.lblSit3TourokuNO").html(
            me.clsComFnc.FncNv(objDrSit["TOUROKU_NO"])
        );
        $(".FrmSCUriageMeisai.lblSit3Rikuji").html(
            me.clsComFnc.FncNv(objDrSit["RIKUJI"])
        );
        $(".FrmSCUriageMeisai.lblSit3SitadoriKin").html(
            me.clsComFnc.FncNz(objDrSit["SIT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3SateiKin").html(
            me.clsComFnc.FncNz(objDrSit["SAT_KIN"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3ShzRt").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_RT"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3ShzGaku").html(
            me.clsComFnc.FncNz(objDrSit["SHZ_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3YotakGK").html(
            me.clsComFnc.FncNz(objDrSit["YOTAK_GK"]).toString().numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3ShikinKnrRyokin").html(
            me.clsComFnc
                .FncNz(objDrSit["SHIKIN_KNR_RYOKIN"])
                .toString()
                .numFormat()
        );
        $(".FrmSCUriageMeisai.lblSit3YotakKB").html(
            me.clsComFnc.FncNv(objDrSit["YOTAK_KB"])
        );
        $(".FrmSCUriageMeisai.lblSit3TebanashiKB").html(
            me.clsComFnc.FncNv(objDrSit["TEBANASHI_KB"])
        );
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSCUriageMeisai = new R4.FrmSCUriageMeisai();
    o_R4_FrmSCUriageMeisai.FrmSCUriageList = o_R4K_R4K_FrmSCUriageList;
    o_R4K_R4K_FrmSCUriageList.FrmSCUriageMeisai = o_R4_FrmSCUriageMeisai;
    o_R4_FrmSCUriageMeisai.load();
});
