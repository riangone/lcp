Namespace.register("JKSYS.FrmFurikaeHiritsuEnt");

JKSYS.FrmFurikaeHiritsuEnt = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.jksys = new JKSYS.JKSYS();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    // ========== 変数 start ==========
    me.id = "FrmFurikaeHiritsuEnt";
    me.sys_id = "JKSYS";
    me.id_url = me.sys_id + "/" + me.id;
    me.syorinm = "";
    me._strHiddUpdDate = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnKensaku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnChange",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnImport",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnEnt",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnExcel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.btnClose",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikaeHiritsuEnt.dtpTaisyouYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.jksys.Shift_TabKeyDown();
    //Tabキーのバインド
    me.jksys.TabKeyDown();
    //Enterキーのバインド
    me.jksys.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".FrmFurikaeHiritsuEnt.btnImport").click(function () {
        me.btnImport_Click();
    });
    //条件変更ボタンクリック
    $(".FrmFurikaeHiritsuEnt.btnChange").click(function () {
        me.btnChange_Click();
        me._strHiddUpdDate = "";
    });
    //検索ボタンクリック
    $(".FrmFurikaeHiritsuEnt.btnKensaku").click(function () {
        me.btnKensaku_Click();
    });
    //削除ボタンクリック
    $(".FrmFurikaeHiritsuEnt.btnDelete").click(function () {
        if ($(".FrmFurikaeHiritsuEnt.lblState").text() == "修正") {
            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                me.btnDelete_Click();
            };
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    //登録ボタンクリック
    $(".FrmFurikaeHiritsuEnt.btnEnt").click(function () {
        me.btnEnt_Click();
    });
    //Excelボタンクリック
    $(".FrmFurikaeHiritsuEnt.btnExcel").click(function () {
        me.btnExcel_Click();
    });
    $(".FrmFurikaeHiritsuEnt.txtSyouyo").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKenkou").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKaigo").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtKoyou").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtRousai").numeric({
        decimal: false,
    });
    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").numeric({
        decimal: false,
    });
    //賞与見積
    $(".FrmFurikaeHiritsuEnt.txtSyouyo").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtSyouyo",
                        "賞与見積"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtSyouyo",
                        "賞与見積"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //健康保険料
    $(".FrmFurikaeHiritsuEnt.txtKenkou").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKenkou",
                        "健康保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKenkou",
                        "健康保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //介護保険料
    $(".FrmFurikaeHiritsuEnt.txtKaigo").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKaigo",
                        "介護保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKaigo",
                        "介護保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //厚生年金保険料
    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKouseiNenkin",
                        "厚生年金保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKouseiNenkin",
                        "厚生年金保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //児童手当
    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtJidouTeate",
                        "児童手当"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtJidouTeate",
                        "児童手当"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //契約社員_賞与見積
    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKSyouyo",
                        "契約社員_賞与見積"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKSyouyo",
                        "契約社員_賞与見積"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //契約社員_健康保険料
    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKenkou",
                        "契約社員_健康保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKenkou",
                        "契約社員_健康保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //契約社員_介護保険料
    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKaigo",
                        "契約社員_介護保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKaigo",
                        "契約社員_介護保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //契約社員_厚生年金保険料
    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin",
                        "契約社員_厚生年金保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin",
                        "契約社員_厚生年金保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //契約社員_児童手当
    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKJidouTeate",
                        "契約社員_児童手当"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKYKJidouTeate",
                        "契約社員_児童手当"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //雇用保険料
    $(".FrmFurikaeHiritsuEnt.txtKoyou").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKoyou",
                        "雇用保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtKoyou",
                        "雇用保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //労災保険料
    $(".FrmFurikaeHiritsuEnt.txtRousai").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtRousai",
                        "労災保険料"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtRousai",
                        "労災保険料"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //退職手当
    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").on("blur", function (e) {
        if (document.documentMode) {
            //IE11
            if (
                $(document.activeElement).is("." + me.id) ||
                $(document.activeElement).is(".JKSYS-layout-center")
            ) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtTaisyoku",
                        "退職手当"
                    ) == false
                ) {
                    return;
                }
            }
        } else {
            if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                if (
                    me.fncEditComma(
                        ".FrmFurikaeHiritsuEnt.txtTaisyoku",
                        "退職手当"
                    ) == false
                ) {
                    return;
                }
            }
        }
    });
    //年月blur:空=>初期値
    $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmFurikaeHiritsuEnt.dtpTaisyouYM")) ==
            false
        ) {
            $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val(me.syorinm);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").trigger("focus");
                    $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").trigger(
                            "focus"
                        );
                        $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").select();
                    }, 0);
                }
            }
            $(".FrmFurikaeHiritsuEnt.btnKensaku").button("disable");
            $(".FrmFurikaeHiritsuEnt.btnChange").button("disable");
        } else {
            $(".FrmFurikaeHiritsuEnt.btnKensaku").button("enable");
            $(".FrmFurikaeHiritsuEnt.btnChange").button("enable");
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.init_control = function () {
        base_init_control();
        //画面初期化
        me.Formit();
    };
    /*
     '**********************************************************************
     '処 理 名：画面初期化(画面初期表示時)
     '関 数 名：Formit
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.Formit = function () {
        var url = me.id_url + "/" + "FncSelalldataSQL";
        var data = {};
        me.ajax.receive = function (res) {
            res = eval("(" + res + ")");
            if (!res["result"]) {
                $(".FrmFurikaeHiritsuEnt").ympicker("disable");
                $(".FrmFurikaeHiritsuEnt").attr("disabled", true);
                $(".FrmFurikaeHiritsuEnt button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", res["error"]);
                return;
            }
            if (res["data"]["SYORI_YM"]) {
                DT = res["data"]["SYORI_YM"];
                me.syorinm = DT;
                $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val(DT);
                if (res["data"]["DT2"]["row"] > 0) {
                    $(".FrmFurikaeHiritsuEnt.lblState").text("修正");
                    DT3 = res["data"]["DT2"]["data"];
                    $(".FrmFurikaeHiritsuEnt.txtSyouyo").val(
                        DT3[0]["BNS_MITUMORI"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKenkou").val(
                        DT3[0]["KENKO_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKaigo").val(
                        DT3[0]["KAIGO_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").val(
                        DT3[0]["KOUSEINENKIN"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val(
                        DT3[0]["JIDOUTEATE"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKoyou").val(
                        DT3[0]["KOYOU_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtRousai").val(
                        DT3[0]["ROUSAI_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val(
                        DT3[0]["TAISYOKUTEATE"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val(
                        DT3[0]["KYK_BNS_MITUMORI"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val(
                        DT3[0]["KYK_KENKO_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val(
                        DT3[0]["KYK_KAIGO_HKN_RYO"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").val(
                        DT3[0]["KYK_KOUSEINENKIN"]
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").val(
                        DT3[0]["KYK_JIDOUTEATE"]
                    );
                    me._strHiddUpdDate = DT3[0]["UPD_DATE"];

                    $(".FrmFurikaeHiritsuEnt.btnDelete").button("enable");
                    $(".FrmFurikaeHiritsuEnt.btnExcel").button("enable");
                } else {
                    $(".FrmFurikaeHiritsuEnt.lblState").text("新規");
                    if (res["data"]["DT3"]["row"] > 0) {
                        DT3 = res["data"]["DT3"]["data"];
                        $(".FrmFurikaeHiritsuEnt.txtSyouyo").val(
                            DT3[0]["BNS_MITUMORI"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKenkou").val(
                            DT3[0]["KENKO_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKaigo").val(
                            DT3[0]["KAIGO_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").val(
                            DT3[0]["KOUSEINENKIN"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val(
                            DT3[0]["JIDOUTEATE"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKoyou").val(
                            DT3[0]["KOYOU_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtRousai").val(
                            DT3[0]["ROUSAI_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val(
                            DT3[0]["TAISYOKUTEATE"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val(
                            DT3[0]["KYK_BNS_MITUMORI"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val(
                            DT3[0]["KYK_KENKO_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val(
                            DT3[0]["KYK_KAIGO_HKN_RYO"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").val(
                            DT3[0]["KYK_KOUSEINENKIN"]
                        );
                        $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").val(
                            DT3[0]["KYK_JIDOUTEATE"]
                        );
                    }
                    me._strHiddUpdDate = "";

                    $(".FrmFurikaeHiritsuEnt.btnDelete").button("disable");
                    $(".FrmFurikaeHiritsuEnt.btnExcel").button("disable");
                }
            }
            //ボタン活性・非活性
            $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").ympicker("disable");
            $(".FrmFurikaeHiritsuEnt.btnKensaku").button("disable");
            $(".FrmFurikaeHiritsuEnt.txtSyouyo").select();
            if (res["data"]["ERROR_FLG"]) {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：画面初期化(検索ボタン押下時)
     '関 数 名：formit2
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.formit2 = function () {
        $(".FrmFurikaeHiritsuEnt.txtSyouyo").val("");
        $(".FrmFurikaeHiritsuEnt.txtKenkou").val("");
        $(".FrmFurikaeHiritsuEnt.txtKaigo").val("");
        $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").val("");
        $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val("");
        $(".FrmFurikaeHiritsuEnt.txtKoyou").val("");
        $(".FrmFurikaeHiritsuEnt.txtRousai").val("");
        $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val("");
        $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val("");
        $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val("");
        $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val("");
        $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").val("");
        $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").val("");
    };
    /*
     '**********************************************************************
     '処 理 名：条件変更ボタンクリック
     '関 数 名：btnChange_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnChange_Click = function () {
        //ボタン活性・非活性
        if ($(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").prop("disabled") == true) {
            $(".FrmFurikaeHiritsuEnt.lblState").text("");
            //対象年月
            $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").ympicker("enable");
            //検索ボタン
            $(".FrmFurikaeHiritsuEnt.btnKensaku").button("enable");

            //賞与見積
            $(".FrmFurikaeHiritsuEnt.txtSyouyo").attr("disabled", true);
            //健康保険料
            $(".FrmFurikaeHiritsuEnt.txtKenkou").attr("disabled", true);
            //介護保険料
            $(".FrmFurikaeHiritsuEnt.txtKaigo").attr("disabled", true);
            //厚生年金
            $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").attr("disabled", true);
            //児童手当
            $(".FrmFurikaeHiritsuEnt.txtJidouTeate").attr("disabled", true);
            //雇用保険料
            $(".FrmFurikaeHiritsuEnt.txtKoyou").attr("disabled", true);
            //労災保険料
            $(".FrmFurikaeHiritsuEnt.txtRousai").attr("disabled", true);
            //退職手当
            $(".FrmFurikaeHiritsuEnt.txtTaisyoku").attr("disabled", true);

            //契約社員_賞与見積
            $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").attr("disabled", true);
            //契約社員_健康保険料
            $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").attr("disabled", true);
            //契約社員_介護保険料
            $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").attr("disabled", true);
            //契約社員_厚生年金
            $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").attr(
                "disabled",
                true
            );
            //契約社員_児童手当
            $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").attr("disabled", true);

            //登録ボタン
            $(".FrmFurikaeHiritsuEnt.btnEnt").button("disable");
            //削除ボタン
            $(".FrmFurikaeHiritsuEnt.btnDelete").button("disable");
            //Excelボタン
            $(".FrmFurikaeHiritsuEnt.btnExcel").button("disable");

            //画面初期化(一覧)
            me.formit2();
        }
    };
    /*
     '**********************************************************************
     '処 理 名：検索ボタンクリック
     '関 数 名：btnKensaku_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnKensaku_Click = function () {
        //画面初期化
        me.formit2();

        var url = me.id_url + "/" + "FncSelnowdataSQL";
        var dtpTaisyouYM = $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val();
        var data = {
            dtpTaisyouYM: dtpTaisyouYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                var jinjiYM = result["data"]["jinjiYM"];
                //データが存在する場合
                if (result["Count"] > 0) {
                    //初期値設定
                    DT2 = result["data"]["DT2"]["data"];
                    //賞与見積
                    $(".FrmFurikaeHiritsuEnt.txtSyouyo").val(
                        DT2[0]["BNS_MITUMORI"]
                    );
                    //健康保険料
                    $(".FrmFurikaeHiritsuEnt.txtKenkou").val(
                        DT2[0]["KENKO_HKN_RYO"]
                    );
                    //介護保険料
                    $(".FrmFurikaeHiritsuEnt.txtKaigo").val(
                        DT2[0]["KAIGO_HKN_RYO"]
                    );
                    //厚生年金
                    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").val(
                        DT2[0]["KOUSEINENKIN"]
                    );
                    //児童手当
                    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val(
                        DT2[0]["JIDOUTEATE"]
                    );
                    //雇用保険料
                    $(".FrmFurikaeHiritsuEnt.txtKoyou").val(
                        DT2[0]["KOYOU_HKN_RYO"]
                    );
                    //労災保険料
                    $(".FrmFurikaeHiritsuEnt.txtRousai").val(
                        DT2[0]["ROUSAI_HKN_RYO"]
                    );
                    //退職手当
                    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val(
                        DT2[0]["TAISYOKUTEATE"]
                    );

                    //契約社員_賞与見積
                    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val(
                        DT2[0]["KYK_BNS_MITUMORI"]
                    );
                    //契約社員_健康保険料
                    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val(
                        DT2[0]["KYK_KENKO_HKN_RYO"]
                    );
                    //契約社員_介護保険料
                    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val(
                        DT2[0]["KYK_KAIGO_HKN_RYO"]
                    );
                    //契約社員_厚生年金
                    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").val(
                        DT2[0]["KYK_KOUSEINENKIN"]
                    );
                    //契約社員_児童手当
                    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").val(
                        DT2[0]["KYK_JIDOUTEATE"]
                    );

                    //更新日(非表示項目)
                    me._strHiddUpdDate = DT2[0]["UPD_DATE"];

                    //ボタン活性・非活性
                    $(".FrmFurikaeHiritsuEnt.btnExcel").button("enable");
                    $(".FrmFurikaeHiritsuEnt.btnDelete").button("enable");
                } else {
                    if (result["data"]["DT2"]["row"] > 0) {
                        DT2 = result["data"]["DT2"]["data"];
                        //賞与見積
                        $(".FrmFurikaeHiritsuEnt.txtSyouyo").val(
                            DT2[0]["BNS_MITUMORI"]
                        );
                        //健康保険料
                        $(".FrmFurikaeHiritsuEnt.txtKenkou").val(
                            DT2[0]["KENKO_HKN_RYO"]
                        );
                        //介護保険料
                        $(".FrmFurikaeHiritsuEnt.txtKaigo").val(
                            DT2[0]["KAIGO_HKN_RYO"]
                        );
                        //厚生年金
                        $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").val(
                            DT2[0]["KOUSEINENKIN"]
                        );
                        //児童手当
                        $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val(
                            DT2[0]["JIDOUTEATE"]
                        );
                        //雇用保険料
                        $(".FrmFurikaeHiritsuEnt.txtKoyou").val(
                            DT2[0]["KOYOU_HKN_RYO"]
                        );
                        //労災保険料
                        $(".FrmFurikaeHiritsuEnt.txtRousai").val(
                            DT2[0]["ROUSAI_HKN_RYO"]
                        );
                        //退職手当
                        $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val(
                            DT2[0]["TAISYOKUTEATE"]
                        );

                        //契約社員_賞与見積
                        $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val(
                            DT2[0]["KYK_BNS_MITUMORI"]
                        );
                        //契約社員_健康保険料
                        $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val(
                            DT2[0]["KYK_KENKO_HKN_RYO"]
                        );
                        //契約社員_介護保険料
                        $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val(
                            DT2[0]["KYK_KAIGO_HKN_RYO"]
                        );
                        //契約社員_厚生年金
                        $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").val(
                            DT2[0]["KYK_KOUSEINENKIN"]
                        );
                        //契約社員_児童手当
                        $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").val(
                            DT2[0]["KYK_JIDOUTEATE"]
                        );
                    }
                    me._strHiddUpdDate = "";
                    //ボタン活性・非活性
                    $(".FrmFurikaeHiritsuEnt.btnExcel").button("disable");
                    $(".FrmFurikaeHiritsuEnt.btnDelete").button("disable");
                }
                //人事コントロール.処理年月より以前の場合
                if (dtpTaisyouYM < jinjiYM) {
                    //テキストボックス活性・非活性
                    $(".FrmFurikaeHiritsuEnt.txtSyouyo").prop("readonly", true);
                    $(".FrmFurikaeHiritsuEnt.txtKenkou").prop("readonly", true);
                    $(".FrmFurikaeHiritsuEnt.txtKaigo").prop("readonly", true);
                    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKoyou").prop("readonly", true);
                    $(".FrmFurikaeHiritsuEnt.txtRousai").prop("readonly", true);
                    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").prop(
                        "readonly",
                        true
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").prop(
                        "readonly",
                        true
                    );

                    $(".FrmFurikaeHiritsuEnt.btnEnt").button("disable");
                    $(".FrmFurikaeHiritsuEnt.btnDelete").button("disable");
                } else {
                    if (result["Count"] > 0) {
                        $(".FrmFurikaeHiritsuEnt.lblState").text("修正");
                    } else {
                        $(".FrmFurikaeHiritsuEnt.lblState").text("新規");
                    }
                    //テキストボックス活性・非活性
                    $(".FrmFurikaeHiritsuEnt.txtSyouyo").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKenkou").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKaigo").prop("readonly", false);
                    $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtJidouTeate").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKoyou").prop("readonly", false);
                    $(".FrmFurikaeHiritsuEnt.txtRousai").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtTaisyoku").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").prop(
                        "readonly",
                        false
                    );
                    $(".FrmFurikaeHiritsuEnt.btnEnt").button("enable");
                }
                $(".FrmFurikaeHiritsuEnt.txtSyouyo").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKenkou").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKaigo").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin").attr(
                    "disabled",
                    false
                );
                $(".FrmFurikaeHiritsuEnt.txtJidouTeate").attr(
                    "disabled",
                    false
                );
                $(".FrmFurikaeHiritsuEnt.txtKoyou").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtRousai").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtTaisyoku").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").attr("disabled", false);
                $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin").attr(
                    "disabled",
                    false
                );
                $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate").attr(
                    "disabled",
                    false
                );
                $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").ympicker("disable");
                $(".FrmFurikaeHiritsuEnt.btnKensaku").button("disable");
                $(".FrmFurikaeHiritsuEnt.btnChange").button("enable");
                $(".FrmFurikaeHiritsuEnt.txtSyouyo").select();
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：登録ボタンクリック
     '関 数 名：btnEnt_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnEnt_Click = function () {
        //入力チェック
        if (me.InPutCheck() == 0) {
            var dtpTaisyouYM = $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val();
            var txtSyouyo = $(".FrmFurikaeHiritsuEnt.txtSyouyo").val();
            var txtKenkou = $(".FrmFurikaeHiritsuEnt.txtKenkou").val();
            var txtKaigo = $(".FrmFurikaeHiritsuEnt.txtKaigo").val();
            var txtKouseiNenkin = $(
                ".FrmFurikaeHiritsuEnt.txtKouseiNenkin"
            ).val();
            var txtJidouTeate = $(".FrmFurikaeHiritsuEnt.txtJidouTeate").val();
            var txtKoyou = $(".FrmFurikaeHiritsuEnt.txtKoyou").val();
            var txtRousai = $(".FrmFurikaeHiritsuEnt.txtRousai").val();
            var txtTaisyoku = $(".FrmFurikaeHiritsuEnt.txtTaisyoku").val();
            var txtKYKSyouyo = $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo").val();
            var txtKYKKenkou = $(".FrmFurikaeHiritsuEnt.txtKYKKenkou").val();
            var txtKYKKaigo = $(".FrmFurikaeHiritsuEnt.txtKYKKaigo").val();
            var txtKYKKouseiNenkin = $(
                ".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin"
            ).val();
            var txtKYKJidouTeate = $(
                ".FrmFurikaeHiritsuEnt.txtKYKJidouTeate"
            ).val();
            if ($(".FrmFurikaeHiritsuEnt.lblState").text() == "新規") {
                var lblState = "新規";
                var strHiddUpdDate = "";
            } else if ($(".FrmFurikaeHiritsuEnt.lblState").text() == "修正") {
                var lblState = "修正";
                var strHiddUpdDate = me._strHiddUpdDate;
            }
            var data = {
                lblState: lblState,
                strHiddUpdDate: strHiddUpdDate,
                dtpTaisyouYM: dtpTaisyouYM,
                txtSyouyo: txtSyouyo.replace(/,/g, ""),
                txtKenkou: txtKenkou.replace(/,/g, ""),
                txtKaigo: txtKaigo.replace(/,/g, ""),
                txtKouseiNenkin: txtKouseiNenkin.replace(/,/g, ""),
                txtJidouTeate: txtJidouTeate.replace(/,/g, ""),
                txtKoyou: txtKoyou.replace(/,/g, ""),
                txtRousai: txtRousai.replace(/,/g, ""),
                txtTaisyoku: txtTaisyoku.replace(/,/g, ""),
                txtKYKSyouyo: txtKYKSyouyo.replace(/,/g, ""),
                txtKYKKenkou: txtKYKKenkou.replace(/,/g, ""),
                txtKYKKaigo: txtKYKKaigo.replace(/,/g, ""),
                txtKYKKouseiNenkin: txtKYKKouseiNenkin.replace(/,/g, ""),
                txtKYKJidouTeate: txtKYKJidouTeate.replace(/,/g, ""),
            };

            var url = me.id_url + "/btnEnt_Click";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "W0018") {
                        me.clsComFnc.FncMsgBox("W0018");
                        return;
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                } else {
                    me.clsComFnc.FncMsgBox("I9999", "登録完了しました。");
                    //登録内容を再表示する
                    me.btnKensaku_Click();
                }
            };
            me.ajax.send(url, data, 0);
        }
    };
    /*
     '**********************************************************************
     '処 理 名：削除ボタンクリック
     '関 数 名：btnDelete_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnDelete_Click = function () {
        var url = me.id_url + "/" + "FncDelJKFHDAT";
        var data = {
            dtpTaisyouYM: $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val(),
            strHiddUpdDate: me._strHiddUpdDate,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W0018") {
                    me.clsComFnc.FncMsgBox("W0018");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            } else {
                //完了メッセージ
                me.clsComFnc.FncMsgBox("I0004");
                //登録内容を再表示する
                me.btnKensaku_Click();
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：Excelボタンクリック
     '関 数 名：btnExcel_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnExcel_Click = function () {
        var url = me.id_url + "/" + "btnExcel_Click";
        var dtpTaisyouYM = $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val();
        var data = {
            dtpTaisyouYM: dtpTaisyouYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //完了メッセージ
                if (result["data"] == "I0011") {
                    me.clsComFnc.FncMsgBox("I0011");
                    return;
                }
            } else {
                //フォルダーが存在するかどうかのﾁｪｯｸ
                if (result["error"] == "W0001") {
                    me.clsComFnc.FncMsgBox("W0001", "出力先");
                    return;
                } else if (result["error"] == "W0015") {
                    me.clsComFnc.FncMsgBox("W0015");
                    return;
                } else if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                } else if (result["error"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：画面項目入力チェック
     '関 数 名：InPutCheck
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.InPutCheck = function () {
        //賞与見積
        var txtSyouyo = $(".FrmFurikaeHiritsuEnt.txtSyouyo")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtSyouyo"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtSyouyo");
            me.clsComFnc.FncMsgBox("W0002", "賞与見積");
            return 1;
        } else if (txtSyouyo <= 0) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtSyouyo");
            me.clsComFnc.FncMsgBox("W0017", "賞与見積は1以上の数値");
            return 1;
        }
        //健康保険料
        var txtKenkou = $(".FrmFurikaeHiritsuEnt.txtKenkou")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKenkou"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKenkou");
            me.clsComFnc.FncMsgBox("W0002", "健康保険料");
            return 1;
        } else if (txtKenkou <= 0) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKenkou");
            me.clsComFnc.FncMsgBox("W0017", "健康保険料は1以上の数値");
            return 1;
        }
        //介護保険料
        var txtKaigo = $(".FrmFurikaeHiritsuEnt.txtKaigo")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKaigo"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKaigo");
            me.clsComFnc.FncMsgBox("W0002", "介護保険料");
            return 1;
        } else if (txtKaigo <= 0) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKaigo");
            me.clsComFnc.FncMsgBox("W0017", "介護保険料は1以上の数値");
            return 1;
        }
        //厚生年金
        var txtKouseiNenkin = $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin");
            me.clsComFnc.FncMsgBox("W0002", "厚生年金保険料");
            return 1;
        } else if (txtKouseiNenkin <= 0) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKouseiNenkin");
            me.clsComFnc.FncMsgBox("W0017", "厚生年金保険料は1以上の数値");
            return 1;
        }
        //児童手当
        var txtJidouTeate = $(".FrmFurikaeHiritsuEnt.txtJidouTeate")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtJidouTeate"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtJidouTeate");
            me.clsComFnc.FncMsgBox("W0002", "児童手当");
            return 1;
        } else if (txtJidouTeate <= 0) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtJidouTeate");
            me.clsComFnc.FncMsgBox("W0017", "児童手当は1以上の数値");
            return 1;
        }
        //賞与見積
        var txtKYKSyouyo = $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo");
            me.clsComFnc.FncMsgBox("W0002", "契約社員_賞与見積");
            return 1;
        } else if (txtKYKSyouyo < 0 || txtKYKSyouyo == "") {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKSyouyo");
            me.clsComFnc.FncMsgBox("W0017", "契約社員_賞与見積は0以上の数値");
            return 1;
        }
        //健康保険料
        var txtKYKKenkou = $(".FrmFurikaeHiritsuEnt.txtKYKKenkou")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKYKKenkou"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKKenkou");
            me.clsComFnc.FncMsgBox("W0002", "契約社員_健康保険料");
            return 1;
        } else if (txtKYKKenkou < 0 || txtKYKKenkou == "") {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKKenkou");
            me.clsComFnc.FncMsgBox("W0017", "契約社員_健康保険料は0以上の数値");
            return 1;
        }
        //介護保険料
        var txtKYKKaigo = $(".FrmFurikaeHiritsuEnt.txtKYKKaigo")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKYKKaigo"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKKaigo");
            me.clsComFnc.FncMsgBox("W0002", "契約社員_介護保険料");
            return 1;
        } else if (txtKYKKaigo < 0 || txtKYKKaigo == "") {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKKaigo");
            me.clsComFnc.FncMsgBox("W0017", "契約社員_介護保険料は0以上の数値");
            return 1;
        }
        //厚生年金
        var txtKYKKouseiNenkin = $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(
                ".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin"
            );
            me.clsComFnc.FncMsgBox("W0002", "契約社員_厚生年金保険料");
            return 1;
        } else if (txtKYKKouseiNenkin < 0 || txtKYKKouseiNenkin == "") {
            me.clsComFnc.ObjFocus = $(
                ".FrmFurikaeHiritsuEnt.txtKYKKouseiNenkin"
            );
            me.clsComFnc.FncMsgBox(
                "W0017",
                "契約社員_厚生年金保険料は0以上の数値"
            );
            return 1;
        }
        //児童手当
        var txtKYKJidouTeate = $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate")
            .val()
            .replace(/,/g, "");
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate");
            me.clsComFnc.FncMsgBox("W0002", "契約社員_児童手当");
            return 1;
        } else if (txtKYKJidouTeate < 0 || txtKYKJidouTeate == "") {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKYKJidouTeate");
            me.clsComFnc.FncMsgBox("W0017", "契約社員_児童手当は0以上の数値");
            return 1;
        }
        //雇用保険料
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtKoyou"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtKoyou");
            me.clsComFnc.FncMsgBox("W0002", "雇用保険料");
            return 1;
        }
        //労災保険料
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtRousai"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtRousai");
            me.clsComFnc.FncMsgBox("W0002", "労災保険料");
            return 1;
        }
        //退職手当
        if (
            me.clsComFnc.FncTextCheck(
                $(".FrmFurikaeHiritsuEnt.txtTaisyoku"),
                0,
                me.clsComFnc.INPUTTYPE.NUMBER2
            ) == -2
        ) {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.txtTaisyoku");
            me.clsComFnc.FncMsgBox("W0002", "退職手当");
            return 1;
        }
        return 0;
    };
    /*
     '**********************************************************************
     '処 理 名：カンマ編集
     '関 数 名：fncEditComma
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.fncEditComma = function (textBox, label) {
        if ($(textBox).val() == "") {
            return true;
        }
        intRtnCD1 = me.clsComFnc.FncTextCheck(
            $(textBox),
            0,
            me.clsComFnc.INPUTTYPE.NUMBER2
        );
        switch (intRtnCD1) {
            case -2:
                me.clsComFnc.ObjFocus = $(textBox);
                me.clsComFnc.FncMsgBox("W0002", label);
                return false;
        }

        var txtValue = $(textBox).val().replace(/,/g, "");
        if (txtValue.length > 10) {
            me.clsComFnc.ObjFocus = $(textBox);
            $(textBox).css({
                backgroundColor: "tomato",
            });
            me.clsComFnc.FncMsgBox("W0003", label);
            return false;
        }
        txtValue = $.trim(txtValue).replace(/\b(0+)/gi, "");

        $(textBox).val(txtValue.replace(/(\d{1,3})(?=(\d{3})+$)/g, "$1,"));
        if ($(textBox).val() == "" || $(textBox).val() == "-") {
            $(textBox).val(0);
        }
        return true;
    };
    /*
     '**********************************************************************
     '処 理 名：退職金EXCEL取込ボタンクリック
     '関 数 名：btnImport_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnImport_Click = function () {
        var dtpTaisyouYM = $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val();
        if (dtpTaisyouYM == "") {
            me.clsComFnc.ObjFocus = $(".FrmFurikaeHiritsuEnt.dtpTaisyouYM");
            me.clsComFnc.FncMsgBox("W0001", "対象年月");
            return;
        }
        var $rootDiv = $(".FrmFurikaeHiritsuEnt.JKSYS-content");

        $("<div></div>")
            .attr("id", "FrmExcelTorikomiKyufuDialogDiv")
            .insertAfter($rootDiv);
        $("<div></div>").attr("id", "CboYM").insertAfter($rootDiv).hide();
        var $SearchCD = $rootDiv.parent().find("#" + "CboYM");

        $SearchCD.val($.trim($(".FrmFurikaeHiritsuEnt.dtpTaisyouYM").val()));
        $("#FrmExcelTorikomiKyufuDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 180,
            width: 750,
            resizable: false,
        });

        var frmId = "FrmExcelTorikomiKyufu";
        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, me.data, 0);
        me.ajax.receive = function (result) {
            $("#FrmExcelTorikomiKyufuDialogDiv").html(result);
            $("#FrmExcelTorikomiKyufuDialogDiv").dialog(
                "option",
                "title",
                "退職金EXCEL取込"
            );
            $("#FrmExcelTorikomiKyufuDialogDiv").dialog("open");
        };
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmFurikaeHiritsuEnt = new JKSYS.FrmFurikaeHiritsuEnt();
    o_JKSYS_FrmFurikaeHiritsuEnt.load();
    o_JKSYS_JKSYS.FrmFurikaeHiritsuEnt = o_JKSYS_FrmFurikaeHiritsuEnt;
});
