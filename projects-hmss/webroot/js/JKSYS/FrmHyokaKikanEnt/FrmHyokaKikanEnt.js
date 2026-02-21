Namespace.register("JKSYS.FrmHyokaKikanEnt");

JKSYS.FrmHyokaKikanEnt = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.sys_id = "JKSYS";
    me.id = "FrmHyokaKikanEnt";
    me.grid_id = "#FrmHyokaKikanEnt_sprList";

    me.dtpJisshiYM = "";
    me.dtpTaisyouKS = "";
    me.dtpTaisyouKE = "";
    me.dtpNowDate = "";

    me.pstrKakiBonusMonth = "";
    me.prvSyoriYM = "";
    me.prvKakiBonusMonth = "";
    me.prvKakiBonusStartMt = "";
    me.prvKakiBonusEndMt = "";
    me.prvToukiBonusMonth = "";
    me.prvToukiBonusStartMt = "";
    me.prvToukiBonusEndMt = "";

    me.option = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.colModel = [
        {
            name: "JISSHI_YM",
            label: "実施年月",
            index: "JISSHI_YM",
            width: 150,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "HYOUKA_KIKAN_START",
            label: "開始",
            index: "HYOUKA_KIKAN_START",
            width: 150,
            sortable: false,
            editable: false,
            align: "left",
        },
        {
            name: "HYOUKA_KIKAN_END",
            label: "終了",
            index: "HYOUKA_KIKAN_END",
            width: 150,
            sortable: false,
            editable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHyokaKikanEnt.btnSelect",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHyokaKikanEnt.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyokaKikanEnt.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyokaKikanEnt.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyokaKikanEnt.dtpJisshiYM",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyokaKikanEnt.dtpTaisyouKS",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyokaKikanEnt.dtpTaisyouKE",
        type: "datepicker",
        handle: "",
    });
    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //選択ﾎﾞﾀﾝクリック
    $(".FrmHyokaKikanEnt.btnSelect").click(function () {
        me.sprList_ButtonClicked();
    });
    //キャンセルボタンクリック
    $(".FrmHyokaKikanEnt.cmdCancel").click(function () {
        me.subReClearForm();
    });
    //登録／修正ボタンクリック
    $(".FrmHyokaKikanEnt.cmdUpdate").click(function () {
        me.cmdUpdate_Click();
    });
    //夏季・冬季クリック
    $(".FrmHyokaKikanEnt.rdbBonus").change(function () {
        me.rdbBonus_CheckedChanged();
    });
    //年間クリック
    $(".FrmHyokaKikanEnt.rdbSyokoukyu").change(function () {
        me.rdbSyokoukyu_CheckedChanged();
    });
    //削除ボタンクリック
    $(".FrmHyokaKikanEnt.cmdDelete").click(function () {
        var dtpJisshiYM = $(".FrmHyokaKikanEnt.dtpJisshiYM").val();
        //評価データ取込チェック
        var url = me.sys_id + "/" + me.id + "/" + "fncHyoukaTriRirekiDataSQL";
        var data = {
            dtpJisshiYM: dtpJisshiYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                if (result["row"] > 0) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に評価データが取り込まれているため削除出来ません。"
                    );
                } else {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                        me.cmdDelete_Click();
                    };
                    //確認メッセージ
                    me.clsComFnc.FncMsgBox("QY004");
                }
            }
        };
        me.ajax.send(url, data, 0);
    });

    me.foucus_back = undefined;
    me.eventType = "focusout";
    if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
        me.eventType = "blur";
    }
    $(".FrmHyokaKikanEnt.dtpJisshiYM")
        .on(me.eventType, function (e) {
            if (
                me.clsComFnc.CheckDate3($(".FrmHyokaKikanEnt.dtpJisshiYM")) ==
                false
            ) {
                $(".FrmHyokaKikanEnt.dtpJisshiYM").val(me.dtpJisshiYM);
                if (
                    !e.relatedTarget ||
                    $(e.relatedTarget).is("." + me.id) ||
                    $(e.relatedTarget).prop("className").indexOf(me.sys_id) !=
                        -1
                ) {
                    me.foucus_back = setTimeout(function () {
                        if (me.dtpJisshiYM !== "") {
                            if (me.dtpJisshiYM_Validating()) {
                                $(".FrmHyokaKikanEnt.dtpJisshiYM").trigger(
                                    "focus"
                                );
                                $(".FrmHyokaKikanEnt.dtpJisshiYM").select();
                            }
                        } else {
                            $(".FrmHyokaKikanEnt.dtpJisshiYM").trigger("focus");
                            $(".FrmHyokaKikanEnt.dtpJisshiYM").select();
                        }
                    }, 0);
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmHyokaKikanEnt.dtpJisshiYM").trigger("focus");
                        $(".FrmHyokaKikanEnt.dtpJisshiYM").select();
                    }, 0);
                }
            } else {
                if (me.dtpJisshiYM !== "") {
                    me.dtpJisshiYM_Validating();
                }
            }
        })
        .on("focus", function () {
            if (me.foucus_back) {
                clearTimeout(me.foucus_back);
            }
        });

    //評価対象期間from
    $(".FrmHyokaKikanEnt.dtpTaisyouKS")
        .on(me.eventType, function (e) {
            if (
                me.clsComFnc.CheckDate($(".FrmHyokaKikanEnt.dtpTaisyouKS")) ==
                false
            ) {
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(me.dtpTaisyouKS);
                if (
                    !e.relatedTarget ||
                    $(e.relatedTarget).is("." + me.id) ||
                    $(e.relatedTarget).prop("className").indexOf(me.sys_id) !=
                        -1
                ) {
                    me.foucus_back = setTimeout(function () {
                        $(".FrmHyokaKikanEnt.dtpTaisyouKS").trigger("focus");
                        $(".FrmHyokaKikanEnt.dtpTaisyouKS").select();
                    }, 0);
                }
            }
        })
        .on("focus", function () {
            if (me.foucus_back) {
                clearTimeout(me.foucus_back);
            }
        });
    //評価対象期間to
    $(".FrmHyokaKikanEnt.dtpTaisyouKE")
        .on(me.eventType, function (e) {
            if (
                me.clsComFnc.CheckDate($(".FrmHyokaKikanEnt.dtpTaisyouKE")) ==
                false
            ) {
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(me.dtpTaisyouKE);
                if (
                    !e.relatedTarget ||
                    $(e.relatedTarget).is("." + me.id) ||
                    $(e.relatedTarget).prop("className").indexOf(me.sys_id) !=
                        -1
                ) {
                    me.foucus_back = setTimeout(function () {
                        $(".FrmHyokaKikanEnt.dtpTaisyouKE").trigger("focus");
                        $(".FrmHyokaKikanEnt.dtpTaisyouKE").select();
                    }, 0);
                }
            }
        })
        .on("focus", function () {
            if (me.foucus_back) {
                clearTimeout(me.foucus_back);
            }
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
        //フォームロード
        me.FrmHyokaKikanEnt_Load();
    };

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：FrmKyuyoInfoTake_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FrmHyokaKikanEnt_Load = function () {
        //ｽﾌﾟﾚｯﾄﾞの初期設定
        me.initSpread();
    };

    //ｽﾌﾟﾚｯﾄﾞの初期設定
    me.initSpread = function () {
        //人事コントロールマスタの取得
        me.GetControlKakiMonth();
    };

    /*
	 '**********************************************************************
	 '処 理 名：評価実績年月フォーカス移動
	 '関 数 名：dtpJisshiYM_Validating
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.dtpJisshiYM_Validating = function () {
        var checkFlg = true;
        if ($(".FrmHyokaKikanEnt.rdbBonus").prop("checked")) {
            var dtpJisshiYM = $(".FrmHyokaKikanEnt.dtpJisshiYM").val();
            me.dtpTaisyouKS = "";
            me.dtpTaisyouKE = "";

            if (dtpJisshiYM.substring(4, 6) == me.prvKakiBonusMonth) {
                //評価期間開始
                if (me.prvKakiBonusStartMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        dtpJisshiYM.substring(0, 4),
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(dtpJisshiYM.substring(0, 4)) - 1,
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvKakiBonusEndMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        dtpJisshiYM.substring(0, 4),
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(dtpJisshiYM.substring(0, 4)) - 1,
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                }
            } else if (dtpJisshiYM.substring(4, 6) == me.prvToukiBonusMonth) {
                //評価期間開始
                if (me.prvToukiBonusStartMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        dtpJisshiYM.substring(0, 4),
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(dtpJisshiYM.substring(0, 4)) - 1,
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvToukiBonusEndMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        dtpJisshiYM.substring(0, 4),
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(dtpJisshiYM.substring(0, 4)) - 1,
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                }
            } else {
                //何もしない
            }

            if (me.dtpTaisyouKS == "") {
                checkFlg = false;
                me.dtpTaisyouKS = me.dtpNowDate;
            }
            $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(me.dtpTaisyouKS);

            if (me.dtpTaisyouKE == "") {
                checkFlg = false;
                me.dtpTaisyouKE = me.dtpNowDate;
            }
            $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(me.dtpTaisyouKE);
        } else {
            me.ChangeEnableTaisyoKS();
        }

        //初期色セット
        me.subResetColor();
        return checkFlg;
    };

    /*
	 '**********************************************************************
	 '処 理 名：人事コントロールマスタの取得
	 '関 数 名：GetControlKakiMonth
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.GetControlKakiMonth = function () {
        var url = me.sys_id + "/" + me.id + "/" + "GetControlKakiMonth";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmHyokaKikanEnt").ympicker("disable");
                $(".FrmHyokaKikanEnt").datepicker("disable");
                $(".FrmHyokaKikanEnt").attr("disabled", true);
                $(".FrmHyokaKikanEnt button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                var data = {
                    params: "",
                };
                var url = me.sys_id + "/" + me.id + "/FrmHyokaKikanEnt_Load";
                //スプレッドに取得データをセットする
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id,
                    url,
                    me.colModel,
                    "",
                    "",
                    me.option,
                    data,
                    function (_bErrorFlag, result_jqgrid) {
                        //初期処理
                        //画面項目ｸﾘｱ
                        me.subClearForm(result_jqgrid);

                        if (result_jqgrid["error"]) {
                            $(".FrmHyokaKikanEnt").attr("disabled", true);
                            $(".FrmHyokaKikanEnt button").button("disable");

                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            return;
                        }

                        //１行目を選択状態にする
                        $(me.grid_id).jqGrid("setSelection", "0");
                    }
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 550);
                gdmz.common.jqgrid.set_grid_height(
                    me.grid_id,
                    me.ratio === 1.5 ? 218 : 260
                );

                $(me.grid_id).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            startColumnName: "HYOUKA_KIKAN_START",
                            numberOfColumns: 2,
                            titleText: "評価対象期間",
                        },
                    ],
                });
                $(me.grid_id).jqGrid("bindKeys");

                var JinjiCtl = result["data"]["JinjiCtl"][0];
                me.prvSyoriYM = me.clsComFnc.FncNv(JinjiCtl["SYORI_YM"]);
                me.prvKakiBonusMonth = me.clsComFnc.FncNv(
                    JinjiCtl["KAKI_BONUS_MONTH"]
                );
                me.prvKakiBonusStartMt = me.clsComFnc.FncNv(
                    JinjiCtl["KAKI_HYOUKA_START_MT"]
                );
                me.prvKakiBonusEndMt = me.clsComFnc.FncNv(
                    JinjiCtl["KAKI_HYOUKA_END_MT"]
                );
                me.prvToukiBonusMonth = me.clsComFnc.FncNv(
                    JinjiCtl["TOUKI_BONUS_MONTH"]
                );
                me.prvToukiBonusStartMt = me.clsComFnc.FncNv(
                    JinjiCtl["TOUKI_HYOUKA_START_MT"]
                );
                me.prvToukiBonusEndMt = me.clsComFnc.FncNv(
                    JinjiCtl["TOUKI_HYOUKA_END_MT"]
                );

                $(".FrmHyokaKikanEnt.rdbBonus").trigger("focus");

                //評価実施年月、評価期間の設定
                me.setHyoukaYMD();

                if (result["data"]["GetKakiMonth"].length !== 0) {
                    me.pstrKakiBonusMonth = me.clsComFnc.FncNv(
                        result["data"]["GetKakiMonth"][0]["KAKI_BONUS_MONTH"]
                    );
                }
            }
        };
        me.ajax.send(url, "", 0);
    };

    /*
	 '**********************************************************************
	 '処 理 名：評価実施年月、評価期間の設定
	 '関 数 名：setHyoukaYMD
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.setHyoukaYMD = function () {
        me.dtpJisshiYM = "";
        me.dtpTaisyouKS = "";
        me.dtpTaisyouKE = "";

        //直近のボーナス月を求める
        if (me.prvKakiBonusMonth < me.prvToukiBonusMonth) {
            if (
                me.prvSyoriYM <=
                me.prvSyoriYM.substring(0, 4) + me.prvKakiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    me.prvSyoriYM.substring(0, 4),
                    me.prvKakiBonusMonth
                );

                //評価期間開始
                if (me.prvKakiBonusStartMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvKakiBonusEndMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                }
            } else if (
                me.prvSyoriYM <=
                me.prvSyoriYM.substring(0, 4) + me.prvToukiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    me.prvSyoriYM.substring(0, 4),
                    me.prvToukiBonusMonth
                );
                //評価期間開始
                if (me.prvToukiBonusStartMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvToukiBonusEndMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                }
            } else if (
                me.prvSyoriYM <=
                parseInt(me.prvSyoriYM.substring(0, 4)) +
                    1 +
                    me.prvKakiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                    me.prvKakiBonusMonth
                );
                //評価期間開始
                if (me.prvKakiBonusStartMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvKakiBonusEndMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                }
            }
        } else {
            if (
                me.prvSyoriYM <=
                me.prvSyoriYM.substring(0, 4) + me.prvToukiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    me.prvSyoriYM.substring(0, 4),
                    me.prvToukiBonusMonth
                );
                //評価期間開始
                if (me.prvToukiBonusStartMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvToukiBonusEndMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                }
            } else if (
                me.prvSyoriYM <=
                me.prvSyoriYM.substring(0, 4) + me.prvKakiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    me.prvSyoriYM.substring(0, 4),
                    me.prvKakiBonusMonth
                );
                //評価期間開始
                if (me.prvKakiBonusStartMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvKakiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvKakiBonusEndMt <= me.prvKakiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) - 1,
                        me.prvKakiBonusEndMt,
                        "31"
                    );
                }
            } else if (
                me.prvSyoriYM <=
                parseInt(me.prvSyoriYM.substring(0, 4)) +
                    1 +
                    me.prvToukiBonusMonth
            ) {
                //評価実施年月
                me.dtpJisshiYM = me.isDate(
                    parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                    me.prvToukiBonusMonth
                );
                //評価期間開始
                if (me.prvToukiBonusStartMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKS = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                } else {
                    me.dtpTaisyouKS = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusStartMt,
                        "01"
                    );
                }
                //評価期間終了
                if (me.prvToukiBonusEndMt <= me.prvToukiBonusMonth) {
                    me.dtpTaisyouKE = me.isDate(
                        parseInt(me.prvSyoriYM.substring(0, 4)) + 1,
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                } else {
                    me.dtpTaisyouKE = me.isDate(
                        me.prvSyoriYM.substring(0, 4),
                        me.prvToukiBonusEndMt,
                        "31"
                    );
                }
            }
        }

        if (me.dtpJisshiYM == "") {
            me.dtpJisshiYM = me.dtpNowDate;
        }
        $(".FrmHyokaKikanEnt.dtpJisshiYM").val(me.dtpJisshiYM);

        if (me.dtpTaisyouKS == "") {
            me.dtpTaisyouKS = me.dtpNowDate;
        }
        $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(me.dtpTaisyouKS);

        if (me.dtpTaisyouKE == "") {
            me.dtpTaisyouKE = me.dtpNowDate;
        }
        $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(me.dtpTaisyouKE);
    };

    me.isDate = function (y, m, d) {
        var day = "01";
        if (d && d !== "31") {
            day = d;
        }

        var newDate = new Date(y, m - 1, day);
        // subtract 1 from the month since .getMonth() is zero-indexed.
        if (
            newDate.getFullYear() == y &&
            newDate.getMonth() == m - 1 &&
            newDate.getDate() == day
        ) {
            if (!d) {
                return y + m;
            } else if (d == "31") {
                return me.GetEndDate(y + "/" + m);
            } else {
                return y + "/" + m + "/" + d;
            }
        } else {
            if (d == "31") {
                me.clsComFnc.FncMsgBox(
                    "E9999",
                    "年月が不正です。yyyy/MMを指定してください。"
                );
            }

            return "";
        }
    };

    /*
	 '**********************************************************************
	 '処 理 名：セルボタンクリック、チェックボックス変更時
	 '関 数 名：sprList_ButtonClicked
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.sprList_ButtonClicked = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (id == null || id == undefined) {
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return;
        }
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        var dtpJisshiYM =
            rowData["JISSHI_YM"].substring(0, 4) +
            rowData["JISSHI_YM"].substring(5, 7);
        if ($(".FrmHyokaKikanEnt.rdbBonus").prop("checked")) {
            if (
                rowData["JISSHI_YM"].substring(
                    rowData["JISSHI_YM"].length - 2,
                    rowData["JISSHI_YM"].length
                ) == me.pstrKakiBonusMonth
            ) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "夏季・冬季考課のデータではありません。"
                );
                return;
            }
            //評価実施年月
            $(".FrmHyokaKikanEnt.dtpJisshiYM").val(dtpJisshiYM);
            $(".FrmHyokaKikanEnt.dtpJisshiYM").ympicker("enable");
            if (rowData["HYOUKA_KIKAN_START"] == "") {
                var yearRange = $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker(
                    "option",
                    "yearRange"
                );
                var arr = yearRange.split(":");
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(arr[1] + "/12/31");
            } else {
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(
                    rowData["HYOUKA_KIKAN_START"]
                );
            }
            //評価対象期間
            $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("enable");
            if (rowData["HYOUKA_KIKAN_END"] == "") {
                var yearRange = $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker(
                    "option",
                    "yearRange"
                );
                var arr = yearRange.split(":");
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(arr[1] + "/12/31");
            } else {
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(
                    rowData["HYOUKA_KIKAN_END"]
                );
            }

            me.subSelClearForm(false);

            //評価履歴データが存在する場合はボタンを不活性に
            //評価データ取込チェック
            var url =
                me.sys_id + "/" + me.id + "/" + "fncHyoukaTriRirekiDataSQL";
            var data = {
                dtpJisshiYM: dtpJisshiYM,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                } else {
                    if (result["row"] > 0) {
                        $(".FrmHyokaKikanEnt.cmdUpdate").button("disable");
                        $(".FrmHyokaKikanEnt.cmdDelete").button("disable");
                    } else {
                        $(".FrmHyokaKikanEnt.cmdUpdate").button("enable");
                        $(".FrmHyokaKikanEnt.cmdDelete").button("enable");
                    }
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            if (
                rowData["JISSHI_YM"].substring(
                    rowData["JISSHI_YM"].length - 2,
                    rowData["JISSHI_YM"].length
                ) !== me.pstrKakiBonusMonth
            ) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "年間評語のデータではありません。"
                );
                return;
            }
            $(".FrmHyokaKikanEnt.dtpJisshiYM").val(dtpJisshiYM);
            $(".FrmHyokaKikanEnt.cmdCancel").button("enable");
            $(".FrmHyokaKikanEnt.cmdDelete").button("enable");
            me.subSelClearForm(true);
        }
    };

    /*
	 '**********************************************************************
	 '処 理 名：画面項目再クリア
	 '関 数 名：subReClearForm
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.subReClearForm = function () {
        //デートタイム
        $(".FrmHyokaKikanEnt.dtpJisshiYM").ympicker("enable");
        $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("enable");
        $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("enable");

        //ボタン
        $(".FrmHyokaKikanEnt.cmdCancel").hide();
        $(".FrmHyokaKikanEnt.cmdDelete").hide();
        $(".FrmHyokaKikanEnt.cmdCancel").button("disable");
        $(".FrmHyokaKikanEnt.cmdDelete").button("disable");

        $(".FrmHyokaKikanEnt.cmdUpdate").show();
        $(".FrmHyokaKikanEnt.cmdUpdate").text("登録");
        $(".FrmHyokaKikanEnt.cmdUpdate").button("enable");

        if ($(".FrmHyokaKikanEnt.rdbSyokoukyu").prop("checked")) {
            $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("disable");
            $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("disable");
        }

        //初期色セット
        me.subResetColor();
    };

    /*
	 '**********************************************************************
	 '処 理 名：画面項目選択クリア
	 '関 数 名：subSelClearForm
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.subSelClearForm = function (blnIsReclearKouka_) {
        //デートタイム
        if (blnIsReclearKouka_ == false) {
            $(".FrmHyokaKikanEnt.dtpJisshiYM").ympicker("disable");
            $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("enable");
            $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("enable");
            //ボタン
            $(".FrmHyokaKikanEnt.cmdUpdate").show();
            $(".FrmHyokaKikanEnt.cmdCancel").show();
            $(".FrmHyokaKikanEnt.cmdDelete").show();
            $(".FrmHyokaKikanEnt.cmdUpdate").text("修正");
            $(".FrmHyokaKikanEnt.cmdCancel").button("enable");
            $(".FrmHyokaKikanEnt.cmdDelete").button("enable");
        } else {
            //ボタン
            $(".FrmHyokaKikanEnt.cmdDelete").show();
            $(".FrmHyokaKikanEnt.cmdCancel").show();
            $(".FrmHyokaKikanEnt.cmdUpdate").button("disable");
            $(".FrmHyokaKikanEnt.cmdCancel").button("enable");
            $(".FrmHyokaKikanEnt.cmdDelete").button("enable");
        }
        //初期色セット
        me.subResetColor();
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録／修正ボタン
	 '関 数 名：cmdUpdate_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdUpdate_Click = function () {
        //入力チェック
        if (!me.fncInputChk()) {
            return;
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力チェック
	 '関 数 名：fncInputChk
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fncInputChk = function () {
        var dtpJisshiYM = $(".FrmHyokaKikanEnt.dtpJisshiYM").val();
        var dtpTaisyouKS = $(".FrmHyokaKikanEnt.dtpTaisyouKS").val();
        var dtpTaisyouKE = $(".FrmHyokaKikanEnt.dtpTaisyouKE").val();
        //評価履歴データ確認
        if (!$(".FrmHyokaKikanEnt.dtpJisshiYM").prop("disabled")) {
            var flag = 1;
            var msg =
                "既に登録済みです。登録内容の修正を行う場合は下の評価期間一覧から該当のデータを選択してください。";
        }
        //評価データ取込チェック
        else {
            var flag = 2;
            var msg =
                "既に評価データが取り込まれているため評価期間を修正することは出来ません。";
        }
        if ($(".FrmHyokaKikanEnt.rdbBonus").prop("checked")) {
            //初期色セット
            me.subResetColor();
            //期間チェック
            if (dtpTaisyouKS > dtpTaisyouKE) {
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(".FrmHyokaKikanEnt.dtpTaisyouKS");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "評価対象期間開始～終了の範囲が不正です"
                );
                return false;
            }
            //期間重複チェック
            var data = {
                flag: flag,
                dtpJisshiYM: dtpJisshiYM,
                dtpTaisyouKS: dtpTaisyouKS,
                dtpTaisyouKE: dtpTaisyouKE,
            };
            var url = me.sys_id + "/" + me.id + "/" + "fncHyoukaKikanRepChkSQL";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return false;
                }
                if (result["data"]["rep"]["row"] > 0) {
                    $(".FrmHyokaKikanEnt.dtpTaisyouKS").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    $(".FrmHyokaKikanEnt.dtpTaisyouKE").css(
                        me.clsComFnc.GC_COLOR_ERROR
                    );
                    me.clsComFnc.ObjFocus = $(".FrmHyokaKikanEnt.dtpTaisyouKS");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "評価対象期間が重複しています！"
                    );
                    return false;
                }
                //存在チェック
                if (result["data"]["check"]["row"] > 0) {
                    me.clsComFnc.FncMsgBox("W9999", msg);
                    return false;
                }
                me.cmdUpdate();
            };
            me.ajax.send(url, data, 0);
        } else {
            //期間重複チェック
            var data = {
                flag: flag,
                dtpJisshiYM: dtpJisshiYM,
            };
            var url = me.sys_id + "/" + me.id + "/" + "CheckExistSyokoukyuData";
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return false;
                }

                //定期昇給の場合
                var currentdate = result["data"]["server_time"];
                if (
                    currentdate == dtpTaisyouKS ||
                    currentdate == dtpTaisyouKE
                ) {
                    //選択年に対応する夏季・冬季の期間が未設定
                    me.clsComFnc.ObjFocus = $(".FrmHyokaKikanEnt.dtpJisshiYM");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "選択年に該当する夏季・冬季の期間が登録されていません。"
                    );
                    return false;
                }
                if (dtpJisshiYM.substring(4, 6) !== me.pstrKakiBonusMonth) {
                    //評価実施年月が人事コントロールマスタの夏季ボーナス月（ID=02）以外
                    me.clsComFnc.ObjFocus = $(".FrmHyokaKikanEnt.dtpJisshiYM");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "定期昇給の評価実施年月ではありません。"
                    );
                    return false;
                }

                if (result["data"]["exist"]["row"] > 0) {
                    //同年のデータが既存
                    me.clsComFnc.ObjFocus = $(".FrmHyokaKikanEnt.dtpJisshiYM");
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "評価対象期間が重複しています！"
                    );
                    return false;
                }
                //存在チェック
                if (result["data"]["check"]["row"] > 0) {
                    me.clsComFnc.FncMsgBox("W9999", msg);
                    return false;
                }
                me.cmdUpdate();
            };
            me.ajax.send(url, data, 0);
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：登録／修正ボタン
	 '関 数 名：cmdUpdate
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdUpdate = function () {
        var data = {
            dtpJisshiYM: $(".FrmHyokaKikanEnt.dtpJisshiYM").val(),
            dtpTaisyouKS: $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(),
            dtpTaisyouKE: $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(),
        };
        //評価対象期間を登録する
        if ($(".FrmHyokaKikanEnt.cmdUpdate").text() == "登録") {
            var url = me.sys_id + "/" + me.id + "/fncInsHyoukaJisshiYMDataSQL";
        }
        //評価対象期間を更新する
        else if ($(".FrmHyokaKikanEnt.cmdUpdate").text() == "修正") {
            var url = me.sys_id + "/" + me.id + "/fncUpdHyoukaJisshiYMDataSQL";
        }
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                me.clsComFnc.FncMsgBox("I0005");
                //画面項目クリア
                me.subReClearForm();
                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.reload();
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：評価実施年月データを削除する
	 '関 数 名：cmdDelete_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdDelete_Click = function () {
        var dtpJisshiYM = $(".FrmHyokaKikanEnt.dtpJisshiYM").val();
        var url = me.sys_id + "/" + me.id + "/" + "fncDelHyoukaJisshiYMDataSQL";
        var data = {
            dtpJisshiYM: dtpJisshiYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                //完了メッセージ
                me.clsComFnc.FncMsgBox("I0004");
                //画面項目クリア
                me.subReClearForm();
                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.reload();
            }
            return;
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：指定年月の末日を取得する
	 '関 数 名：GetEndDate
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.GetEndDate = function (ym) {
        var ymd = ym + "/01";
        var arg = /\d{4}\/\d{2}\/\d{2}/;
        var paddNum = function (num) {
            num += "";
            return num.replace(/^(\d)$/, "0$1");
        };
        if (ymd.match(arg)) {
            var date = new Date(ymd);
            var add_month_ymd = "";
            if (parseInt(date.getMonth()) + 1 != 12) {
                add_month_ymd =
                    date.getFullYear() +
                    "/" +
                    paddNum(date.getMonth() + 2) +
                    "/" +
                    paddNum(date.getDate());
            } else {
                add_month_ymd =
                    parseInt(date.getFullYear()) +
                    1 +
                    "/01" +
                    "/" +
                    paddNum(date.getDate());
            }
            date = new Date(add_month_ymd);
            date = new Date(date.setDate(date.getDate() - 1));
            //翌月の1日前を返す
            return (
                date.getFullYear() +
                "/" +
                paddNum(date.getMonth() + 1) +
                "/" +
                paddNum(date.getDate())
            );
        } else {
            me.clsComFnc.FncMsgBox(
                "E9999",
                "年月が不正です。yyyy/MMを指定してください。"
            );
            return "";
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：reload
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.reload = function () {
        var data = {
            data: "",
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_id,
            data,
            function (_bErrorFlag, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", "0");
            }
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 550);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：ChangeEnableTaisyoKS
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.ChangeEnableTaisyoKS = function () {
        var dtpJisshiYM = $(".FrmHyokaKikanEnt.dtpJisshiYM").val();
        var url = me.sys_id + "/" + me.id + "/GetTaisyoKSKE";
        var data = {
            strJissiYM: dtpJisshiYM.substring(0, 4),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                if (result["data"]["strTaisyoKS"]) {
                    me.dtpTaisyouKS = result["data"]["strTaisyoKS"];
                } else {
                    me.dtpTaisyouKS = me.dtpNowDate;
                }
                if (result["data"]["strTaisyoKE"]) {
                    me.dtpTaisyouKE = result["data"]["strTaisyoKE"];
                } else {
                    me.dtpTaisyouKE = me.dtpNowDate;
                }
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(me.dtpTaisyouKS);
                $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("disable");
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(me.dtpTaisyouKE);
                $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("disable");
            }
        };

        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：rdbSyokoukyu_CheckedChanged
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.rdbSyokoukyu_CheckedChanged = function () {
        if ($(".FrmHyokaKikanEnt.rdbSyokoukyu").prop("checked")) {
            me.subReClearForm(false);
            me.ChangeEnableTaisyoKS();
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：
	 '関 数 名：rdbBonus_CheckedChanged
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.rdbBonus_CheckedChanged = function () {
        if ($(".FrmHyokaKikanEnt.rdbBonus").prop("checked")) {
            $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("enable");
            $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("enable");

            me.setHyoukaYMD();
            me.subReClearForm();
        }
    };
    //画面項目クリア
    me.subClearForm = function (result) {
        //デートタイム
        $(".FrmHyokaKikanEnt.dtpJisshiYM").ympicker("enable");
        $(".FrmHyokaKikanEnt.dtpTaisyouKS").datepicker("enable");
        $(".FrmHyokaKikanEnt.dtpTaisyouKE").datepicker("enable");

        me.dtpJisshiYM = result["ymdate"];
        me.dtpTaisyouKS = result["fulldate"];
        me.dtpTaisyouKE = me.dtpTaisyouKS;
        me.dtpNowDate = me.dtpTaisyouKS;
        $(".FrmHyokaKikanEnt.dtpJisshiYM").val(me.dtpJisshiYM);
        $(".FrmHyokaKikanEnt.dtpTaisyouKS").val(me.dtpTaisyouKS);
        $(".FrmHyokaKikanEnt.dtpTaisyouKE").val(me.dtpTaisyouKE);

        //ボタン
        $(".FrmHyokaKikanEnt.cmdCancel").hide();
        $(".FrmHyokaKikanEnt.cmdDelete").hide();
        $(".FrmHyokaKikanEnt.cmdCancel").button("disable");
        $(".FrmHyokaKikanEnt.cmdDelete").button("disable");

        $(".FrmHyokaKikanEnt.cmdUpdate").text("登録");

        //初期色セット
        me.subResetColor();
    };
    //初期色
    me.subResetColor = function () {
        $(".FrmHyokaKikanEnt.dtpTaisyouKS").css(me.clsComFnc.GC_COLOR_NORMAL);
        $(".FrmHyokaKikanEnt.dtpTaisyouKE").css(me.clsComFnc.GC_COLOR_NORMAL);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmHyokaKikanEnt = new JKSYS.FrmHyokaKikanEnt();
    o_JKSYS_FrmHyokaKikanEnt.load();
    o_JKSYS_JKSYS.FrmHyokaKikanEnt = o_JKSYS_FrmHyokaKikanEnt;
});
