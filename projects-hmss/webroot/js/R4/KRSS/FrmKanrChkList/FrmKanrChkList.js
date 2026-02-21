/**
 * 説明：
 *
 *
 * @author yushuangji
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmKanrChkList");

KRSS.FrmKanrChkList = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.sys_id = "KRSS";
    me.id = "FrmKanrChkList";
    //期
    me.strKI = "";
    me.strKisyuYM = "";

    me.cboKisyu = "";
    me.cboYM = "";
    me.cboYMInit = "";
    me.BusyoArr = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmKanrChkList.Excel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmKanrChkList.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmKanrChkList.cboKisyu",
        type: "datepicker3",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmKanrChkList.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //部署CD From. blur
    $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmKanrChkList.lblKamokuNMFrom").val("");
        if ($(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val().trimEnd() ==
                    me.BusyoArr[key]["KAMOK_CD"]
                ) {
                    $(".KRSS.FrmKanrChkList.lblKamokuNMFrom").val(
                        me.BusyoArr[key]["KAMOK_NM"]
                    );
                    $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val(
                        $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val()
                    );
                }
            }
        } else {
            $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val("");
        }
    });
    //部署CD To. blur
    $(".KRSS.FrmKanrChkList.txtKamokuCDTo").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmKanrChkList.txtKamokuCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmKanrChkList.lblKamokuNMTo").val("");
        if ($(".KRSS.FrmKanrChkList.txtKamokuCDTo").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val().trimEnd() ==
                    me.BusyoArr[key]["KAMOK_CD"]
                ) {
                    $(".KRSS.FrmKanrChkList.lblKamokuNMTo").val(
                        me.BusyoArr[key]["KAMOK_NM"]
                    );
                }
            }
        }
    });

    $(".KRSS.FrmKanrChkList.cboYM").on("blur", function () {
        if (me.clsComFnc.CheckDate3($(".KRSS.FrmKanrChkList.cboYM")) == false) {
            $(".KRSS.FrmKanrChkList.cboYM").val(me.cboYMInit);
            $(".KRSS.FrmKanrChkList.cboYM").trigger("focus");
        }
    });
    //**********************************************************************
    //処 理 名：処理月変更
    //関 数 名：cboYM_ValueChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：処理月から期と期首を求めて表示する
    //**********************************************************************
    $(".KRSS.FrmKanrChkList.cboYM").change(function () {
        var cboYM = $(".KRSS.FrmKanrChkList.cboYM").val();
        if (cboYM.substring(4, 6) < 10) {
            $(".KRSS.FrmKanrChkList.cboKisyu").val(
                cboYM.substring(0, 4) - 1 + "10"
            );
        } else {
            $(".KRSS.FrmKanrChkList.cboKisyu").val(
                cboYM.substring(0, 4) + "10"
            );
        }
        $(".KRSS.FrmKanrChkList.lblKi").val(
            $(".KRSS.FrmKanrChkList.cboKisyu").val().substring(0, 4) - 1917
        );
    });

    //Excel出力 Button.
    $(".KRSS.FrmKanrChkList.Excel").click(function () {
        me.cmdAction_Click();
    });

    //印刷Button
    $(".KRSS.FrmKanrChkList.cmdAction").click(function () {
        me.cmdAction_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    var base_load = me.load;

    me.load = function () {
        base_load();
        me.fncGetKamokuCD();
    };

    me.fncGetKamokuCD = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetKamokuNM";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.BusyoArr = result["data"];
                me.frmKanrSyukei_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmKanrSyukei_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmKanrSyukei_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();
        //コントロールマスタ存在ﾁｪｯｸ
        url = me.sys_id + "/" + me.id + "/" + "frmKanrSyukei_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".KRSS.FrmKanrChkList.cboYM").trigger("focus");
                if (result["data"].length == 0) {
                    $("#KRSS_FrmKanrChkList_KRSS").block();
                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    $(".KRSS.FrmKanrChkList.cboKisyu").ympicker(
                        "setDate",
                        new Date()
                    );
                    $(".KRSS.FrmKanrChkList.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
                    return;
                } else {
                    //コンボボックスに当月年月を設定
                    $(".KRSS.FrmKanrChkList.cboYM").val(
                        me.clsComFnc.FncNv(
                            result["data"][0]["TOUGETU"]
                                .substring(0, 7)
                                .replace("/", "")
                        )
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substring(0, 7)
                        .replace("/", "");
                    me.cboYMInit = me.cboYM;
                    //期を変数に格納
                    me.strKI = me.clsComFnc
                        .FncNv(result["data"][0]["KI"])
                        .toString();
                    //当期以前のデータを抽出できるように変更
                    $(".KRSS.FrmKanrChkList.lblKi").val(me.strKI);
                    //当期以前のデータを抽出できるように変更
                    $(".KRSS.FrmKanrChkList.cboKisyu_block").block({
                        overlayCSS: {
                            opacity: 0,
                        },
                    });

                    $(".KRSS.FrmKanrChkList.cboKisyu").attr(
                        "disabled",
                        "disabled"
                    );

                    me.strKisyuYM = me.clsComFnc
                        .FncNv(result["data"][0]["KISYU"])
                        .toString();

                    $(".KRSS.FrmKanrChkList.cboKisyu").val(
                        me.strKisyuYM.substring(0, 4) +
                            me.strKisyuYM.substring(4, 6)
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：画面項目をｸﾘｱする
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目をｸﾘｱする
    //**********************************************************************
    me.subClearForm = function () {
        $(".KRSS.FrmKanrChkList.lblKamokuNMFrom").val("");
        $(".KRSS.FrmKanrChkList.lblKamokuNMTo").val("");
        $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val("");
        $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val("");
    };

    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdAction_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：部署別実績ファイルを作成する
    //**********************************************************************
    me.cmdAction_Click = function () {
        //開始科目コード・終了科目コードがどちらとも入力されていた場合
        //開始科目コード＞終了科目コードの場合ｴﾗｰ
        if (
            $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val().trimEnd() != "" &&
            $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val().trimEnd() != ""
        ) {
            if (
                $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").val().trimEnd() >
                $(".KRSS.FrmKanrChkList.txtKamokuCDTo").val().trimEnd()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".KRSS.FrmKanrChkList.txtKamokuCDFrom"
                );
                //$(".KRSS.FrmKanrChkList.txtKamokuCDFrom").focus();
                me.clsComFnc.FncMsgBox("W9999", "部署コードの範囲が不正です");
                $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                $(".KRSS.FrmKanrChkList.txtKamokuCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                return;
            } else {
                $(".KRSS.FrmKanrChkList.txtKamokuCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                $(".KRSS.FrmKanrChkList.txtKamokuCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
            }
        }
        //印刷処理
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var t =
            $(".KRSS.FrmKanrChkList.cboKisyu").val().substring(0, 4) +
            "/" +
            $(".KRSS.FrmKanrChkList.cboKisyu").val().substring(4, 6);
        var t1 =
            $(".KRSS.FrmKanrChkList.cboYM").val().substring(0, 4) +
            "/" +
            $(".KRSS.FrmKanrChkList.cboYM").val().substring(4, 6);
        var data = {
            cboKisyu: t,
            cboYM: t1,
            txtKamokuCDFrom: $(".KRSS.FrmKanrChkList.txtKamokuCDFrom")
                .val()
                .trimEnd(),
            txtKamokuCDTo: $(".KRSS.FrmKanrChkList.txtKamokuCDTo")
                .val()
                .trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
                //印刷出力
                window.location.href = result["data"];
                //画面ｸﾘｱ処理
                me.subClearForm();
                //ﾌｫｰｶｽ移動
                me.clsComFnc.FncMsgBox("I0011");

                $(".KRSS.FrmSonekiMeisai.cboYM").trigger("focus");
            } else {
                if (result["MsgID"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
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
    var o_KRSS_FrmKanrChkList = new KRSS.FrmKanrChkList();
    o_KRSS_FrmKanrChkList.load();
});
