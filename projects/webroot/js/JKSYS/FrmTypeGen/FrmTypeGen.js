/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmTypeGen");

JKSYS.FrmTypeGen = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmTypeGen";
    me.sys_id = "JKSYS";

    // ========== 変数 start ==========
    // 評価最終年月
    me.dtpTaisyouKE = "";
    // 支給予定日
    me.dtpShikyuYD = "";
    // 処理年月
    me.prvSyoriYM = "";
    // 夏季ボーナス月
    me.prvKakiBonusMonth = "";
    // 夏季評価期間開始
    me.prvKakiBonusStartMt = "";
    // 夏季評価期間終了
    me.prvKakiBonusEndMt = "";
    // 冬季ボーナス月
    me.prvToukiBonusMonth = "";
    // 冬季評価期間開始
    me.prvToukiBonusStartMt = "";
    // 冬季評価期間終了
    me.prvToukiBonusEndMt = "";
    // ========== 変数 end ============

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmTypeGen.dtpTaisyouKE",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".FrmTypeGen.dtpShikyuYD",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmTypeGen.cmdApply",
        type: "button",
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    // 評価最終年月チェック
    $(".FrmTypeGen.dtpTaisyouKE").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmTypeGen.dtpTaisyouKE")) == false) {
            $(".FrmTypeGen.dtpTaisyouKE").val(me.dtpTaisyouKE);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmTypeGen.dtpTaisyouKE").trigger("focus");
                    $(".FrmTypeGen.dtpTaisyouKE").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmTypeGen.dtpTaisyouKE").trigger("focus");
                        $(".FrmTypeGen.dtpTaisyouKE").select();
                    }, 0);
                }
            }
            $(".FrmTypeGen.cmdApply").button("disable");
        } else {
            $(".FrmTypeGen.cmdApply").button("enable");
        }
    });

    // 支給予定日チェック
    $(".FrmTypeGen.dtpShikyuYD").on("blur", function (e) {
        if (me.clsComFnc.CheckDate($(".FrmTypeGen.dtpShikyuYD")) == false) {
            $(".FrmTypeGen.dtpShikyuYD").val(me.dtpShikyuYD);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmTypeGen.dtpShikyuYD").trigger("focus");
                    $(".FrmTypeGen.dtpShikyuYD").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmTypeGen.dtpShikyuYD").trigger("focus");
                        $(".FrmTypeGen.dtpShikyuYD").select();
                    }, 0);
                }
            }
            $(".FrmTypeGen.cmdApply").button("disable");
        } else {
            $(".FrmTypeGen.cmdApply").button("enable");
        }
    });

    // 生成ボタンクリック（処理）
    $(".FrmTypeGen.cmdApply").click(function () {
        me.cmdApply_Click();
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
        me.FrmTypeGen_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：画面起動時
	 '関 数 名：FrmTypeGen_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.FrmTypeGen_Load = function () {
        // DTPに値を設定する
        me.setfrminit();
    };

    me.setfrminit = function () {
        var url = me.sys_id + "/" + me.id + "/" + "FrmTypeGen_Load";

        me.ajax.receive = function (res) {
            var res = eval("(" + res + ")");
            if (res["result"] == true) {
                // 処理年月
                me.prvSyoriYM = res["data"]["prvSyoriYM"];
                // 夏季ボーナス月
                me.prvKakiBonusMonth = res["data"]["prvKakiBonusMonth"];
                // 夏季評価期間開始
                me.prvKakiBonusStartMt = res["data"]["prvKakiBonusStartMt"];
                // 夏季評価期間終了
                me.prvKakiBonusEndMt = res["data"]["prvKakiBonusEndMt"];
                // 冬季ボーナス月
                me.prvToukiBonusMonth = res["data"]["prvToukiBonusMonth"];
                // 冬季評価期間開始
                me.prvToukiBonusStartMt = res["data"]["prvToukiBonusStartMt"];
                // 冬季評価期間終了
                me.prvToukiBonusEndMt = res["data"]["prvToukiBonusEndMt"];

                // データが存在しません
                var Year = new Date(me.prvSyoriYM);
                if (
                    me.prvSyoriYM <=
                    me.GetEndDate(
                        parseInt(Year.getFullYear()) +
                            1 +
                            "/" +
                            me.prvToukiBonusEndMt
                    )
                ) {
                    if (
                        me.prvSyoriYM <=
                        me.GetEndDate(
                            parseInt(Year.getFullYear()) +
                                1 +
                                "/" +
                                me.prvKakiBonusEndMt
                        )
                    ) {
                        if (
                            me.prvSyoriYM <=
                            me.GetEndDate(
                                parseInt(Year.getFullYear()) +
                                    "/" +
                                    me.prvToukiBonusEndMt
                            )
                        ) {
                            if (
                                me.prvSyoriYM <=
                                me.GetEndDate(
                                    parseInt(Year.getFullYear()) +
                                        "/" +
                                        me.prvKakiBonusEndMt
                                )
                            ) {
                                if (
                                    me.prvSyoriYM <=
                                    me.GetEndDate(
                                        parseInt(Year.getFullYear()) -
                                            1 +
                                            "/" +
                                            me.prvToukiBonusEndMt
                                    )
                                ) {
                                    if (
                                        me.prvSyoriYM <=
                                        me.GetEndDate(
                                            parseInt(Year.getFullYear()) -
                                                1 +
                                                "/" +
                                                me.prvKakiBonusEndMt
                                        )
                                    ) {
                                        me.dtpTaisyouKE = me
                                            .GetEndDate(
                                                parseInt(Year.getFullYear()) +
                                                    "/" +
                                                    me.prvToukiBonusEndMt
                                            )
                                            .substring(0, 7)
                                            .replace("/", "");
                                        me.dtpShikyuYD =
                                            parseInt(Year.getFullYear()) +
                                            "/" +
                                            me.prvToukiBonusMonth +
                                            "/" +
                                            "01";
                                    }
                                } else {
                                    me.dtpTaisyouKE = me
                                        .GetEndDate(
                                            parseInt(Year.getFullYear()) -
                                                1 +
                                                "/" +
                                                me.prvToukiBonusEndMt
                                        )
                                        .substring(0, 7)
                                        .replace("/", "");
                                    me.dtpShikyuYD =
                                        parseInt(Year.getFullYear()) -
                                        1 +
                                        "/" +
                                        me.prvToukiBonusMonth +
                                        "/" +
                                        "01";
                                }
                            } else {
                                me.dtpTaisyouKE = me
                                    .GetEndDate(
                                        parseInt(Year.getFullYear()) +
                                            "/" +
                                            me.prvKakiBonusEndMt
                                    )
                                    .substring(0, 7)
                                    .replace("/", "");
                                me.dtpShikyuYD =
                                    parseInt(Year.getFullYear()) +
                                    "/" +
                                    me.prvKakiBonusMonth +
                                    "/" +
                                    "01";
                            }
                        } else {
                            me.dtpTaisyouKE = me
                                .GetEndDate(
                                    parseInt(Year.getFullYear()) +
                                        "/" +
                                        me.prvToukiBonusEndMt
                                )
                                .substring(0, 7)
                                .replace("/", "");
                            me.dtpShikyuYD =
                                parseInt(Year.getFullYear()) +
                                "/" +
                                me.prvToukiBonusMonth +
                                "/" +
                                "01";
                        }
                    } else {
                        me.dtpTaisyouKE = me
                            .GetEndDate(
                                parseInt(Year.getFullYear()) +
                                    "/" +
                                    me.prvToukiBonusEndMt
                            )
                            .substring(0, 7)
                            .replace("/", "");
                        me.dtpShikyuYD =
                            parseInt(Year.getFullYear()) +
                            "/" +
                            me.prvToukiBonusMonth +
                            "/" +
                            "01";
                    }
                }

                $(".FrmTypeGen.dtpTaisyouKE").val(me.dtpTaisyouKE);
                $(".FrmTypeGen.dtpShikyuYD").val(me.dtpShikyuYD);
                $(".FrmTypeGen.dtpTaisyouKE").select();
            } else {
                $(".FrmTypeGen").attr("disabled", true);
                $(".FrmTypeGen button").button("disable");
                $(".FrmTypeGen.dtpTaisyouKE").ympicker("disable");
                $(".FrmTypeGen.dtpShikyuYD").datepicker("disable");

                me.clsComFnc.FncMsgBox("E9999", res["error"]);
            }
        };
        me.ajax.send(url, "", 0);
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
            $(".FrmTypeGen").attr("disabled", true);
            $(".FrmTypeGen button").button("disable");

            me.clsComFnc.FncMsgBox(
                "E9999",
                "年月が不正です。yyyyMMを指定してください。"
            );
            return "";
        }
    };

    /*
	 '**********************************************************************
	 '処 理 名：生成ボタンクリック（処理）
	 '関 数 名：cmdApply_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdApply_Click = function () {
        // 入力チェック
        if (!me.fncInputChk()) {
            return;
        }

        // 社員別考課表タイプデータを作成する
        if (!me.fncCreateData()) {
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
        var Year = new Date($(".FrmTypeGen.dtpShikyuYD").val());

        // 評価最終年月
        if (
            $(".FrmTypeGen.dtpTaisyouKE").val().substring(4, 6) !=
                me.prvKakiBonusEndMt &&
            $(".FrmTypeGen.dtpTaisyouKE").val().substring(4, 6) !=
                me.prvToukiBonusEndMt
        ) {
            me.clsComFnc.FncMsgBox("W0002", "評価最終年月（月）");
            me.clsComFnc.ObjFocus = $(".FrmTypeGen.dtpTaisyouKE");
            return false;
        }

        // 支給予定日
        if (
            $(".FrmTypeGen.dtpTaisyouKE").val() >=
            Year.getFullYear() +
                (Year.getMonth() + 1 < 10
                    ? "0" + (Year.getMonth() + 1)
                    : (Year.getMonth() + 1).toString())
        ) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "支給予定日は評価最終年月移行の日付を入力してください。"
            );
            me.clsComFnc.ObjFocus = $(".FrmTypeGen.dtpTaisyouKE");
            return false;
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：社員別考課表タイプデータを作成する
	 '関 数 名：fncCreateData
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.fncCreateData = function () {
        var data = {
            dtpTaisyouKE: $(".FrmTypeGen.dtpTaisyouKE").val(),
            dtpShikyuYD: $(".FrmTypeGen.dtpShikyuYD").val(),
        };

        var url = me.sys_id + "/" + me.id + "/" + "fncCreateData";

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                // 存在チェック
                if (result["data"]["CNT"] > 0) {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdApplyQY;
                    me.clsComFnc.FncMsgBox(
                        "QY011",
                        "該当期間の社員別効果表タイプデータ"
                    );
                } else {
                    me.cmdApplyQY();
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.cmdApplyQY = function () {
        var data = {
            dtpTaisyouKE: $(".FrmTypeGen.dtpTaisyouKE").val(),
            dtpShikyuYD: $(".FrmTypeGen.dtpShikyuYD").val(),
        };

        var url = me.sys_id + "/" + me.id + "/" + "cmdApply_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return false;
            } else {
                //終了メッセージ
                me.clsComFnc.FncMsgBox("I0013");
                return true;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_JKSYS_FrmTypeGen = new JKSYS.FrmTypeGen();
    o_JKSYS_FrmTypeGen.load();
});
