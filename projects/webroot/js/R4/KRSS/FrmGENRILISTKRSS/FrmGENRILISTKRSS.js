/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmGENRILISTKRSS");

KRSS.FrmGENRILISTKRSS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmGENRILISTKRSS";
    me.sys_id = "KRSS";
    me.cboYM = "";
    //0：全社の権限なし(権限マスタに登録されている部署のみ) 1:全社の権限あり
    me.intAuth = 0;

    me.BusyoArr = [];

    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".KRSS.FrmGENRILISTKRSS.cmd003",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmGENRILISTKRSS.cancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmGENRILISTKRSS.cboYM",
        type: "datepicker3",
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
        // 初期処理
        me.fncGetBusyo();
        //me.frmGenkaiMake_Load();
        $(".KRSS.FrmGENRILISTKRSS.cboYM").trigger("focus");
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理年月 blur
    $(".KRSS.FrmGENRILISTKRSS.cboYM").on("blur", function () {
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmGENRILISTKRSS.cboYM")) == false
        ) {
            $(".KRSS.FrmGENRILISTKRSS.cboYM").val(me.cboYM);
        }
    });

    //部署CD From. blur
    $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMFrom").val("");
        if ($(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom")
                        .val()
                        .trimEnd() == me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMFrom").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                }
            }
            // copy
            $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val(
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").val()
            );
        } else {
            $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val("");
        }
    });

    //部署CD To. blur
    $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMTo").val("");
        if ($(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val().trimEnd() ==
                    me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMTo").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                }
            }
        }
    });

    //**********************************************************************
    //処理説明：一覧表ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmGENRILISTKRSS.cmd003.ExcelOut").click(function () {
        me.cmdAct_Click("cmdExcelOut");
    });

    //**********************************************************************
    //処理説明：チェックリストボタン押下時
    //**********************************************************************
    $(".KRSS.FrmGENRILISTKRSS.cmd003.checklist").click(function () {
        me.cmdAct_Click("cmdchecklist");
    });

    //**********************************************************************
    //処理説明：キャンセルボタン押下時
    //**********************************************************************
    $(".KRSS.FrmGENRILISTKRSS.cancel").click(function () {
        me.frmGenkaiMake_Load();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：getBusyo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.fncGetBusyo = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyo";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.BusyoArr = result["data"];
                //console.log(me.BusyoArr);
                me.frmGenkaiMake_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmGenkaiMake_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.frmGenkaiMake_Load = function () {
        //画面項目ｸﾘｱ
        me.subClearForm();
        //コントロールマスタ存在ﾁｪｯｸ
        url = me.sys_id + "/" + me.id + "/" + "frmGenkaiMake_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    //コントロールマスタが存在していない場合
                    $(".KRSS.FrmGENRILISTKRSS.cboYM").trigger("blur");
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    $(".KRSS.FrmGENRILISTKRSS.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
                    $("#KRSS_FrmGENRILISTKRSS").block();
                    return;
                } else {
                    //コンボボックスに当月年月を設定
                    $(".KRSS.FrmGENRILISTKRSS.cboYM").val(
                        me.clsComFnc
                            .FncNv(result["data"][0]["TOUGETU"].substring(0, 7))
                            .replace(/\//, "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substring(0, 7)
                        .replace(/\//, "");
                    //権限のﾁｪｯｸを行う
                    var url = me.sys_id + "/" + me.id + "/" + "fncAuthCheck";
                    me.ajax.receive = function (result) {
                        result = eval("(" + result + ")");
                        //console.log(result);
                        if (result["result"] == true) {
                            tblCTL = result["data"];
                            if (tblCTL.length == 0) {
                                //0件の場合
                                // $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").attr("disabled", "true");
                                // $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").attr("disabled", "true");
                                // $(".KRSS.FrmGENRILISTKRSS.cmd003").button('disable');
                                $(".KRSS.FrmGENRILISTKRSS.cboYM").trigger(
                                    "blur"
                                );
                                me.clsComFnc.FncMsgBox(
                                    "W9999",
                                    "権限の設定がされていません。管理者にご連絡ください！"
                                );
                                $("#KRSS_FrmGENRILISTKRSS").block();
                                return;
                            } else if (tblCTL.length == 1) {
                                //1件の場合
                                if (
                                    me.fncDataNullStr(tblCTL[0]["BUSYO_CD"]) ==
                                    "000"
                                ) {
                                    //全社の権限あり
                                    me.intAuth = 1;
                                } else {
                                    $(
                                        ".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom"
                                    ).attr("disabled", "true");
                                    //$(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").css("backgroundColor", "white");
                                    $(
                                        ".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo"
                                    ).attr("disabled", "true");
                                    //$(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").css("backgroundColor", "white");
                                    //部署コード
                                    $(
                                        ".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom"
                                    ).val(
                                        me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                    );
                                    $(
                                        ".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo"
                                    ).val(
                                        me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                    );

                                    if (result["BusyoMst"]["intRtnCD"] == 1) {
                                        $(
                                            ".KRSS.FrmGENRILISTKRSS.lblBusyoNMFrom"
                                        ).val(result["BusyoMst"]["strBusyoNM"]);
                                        $(
                                            ".KRSS.FrmGENRILISTKRSS.lblBusyoNMTo"
                                        ).val(result["BusyoMst"]["strBusyoNM"]);
                                    } else {
                                        $(
                                            ".KRSS.FrmGENRILISTKRSS.lblBusyoNMFrom"
                                        ).val("");
                                        $(
                                            ".KRSS.FrmGENRILISTKRSS.lblBusyoNMTo"
                                        ).val("");
                                    }
                                }
                                //権限を付与する
                                me.fncAuthorityInvest(
                                    me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                );
                            } else {
                                //>1件の場合
                                var i = 0;
                                for (key in tblCTL) {
                                    if (tblCTL[key]["BUSYO_CD"] == "000") {
                                        i++;
                                    }
                                }
                                if (i > 0) {
                                    me.intAuth = 1;
                                }
                            }
                            //$('.KRSS.FrmGENRILISTKRSS.cboYM').focus();
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["data"]);
                            $("#KRSS_FrmGENRILISTKRSS").block();
                            return;
                        }
                    };
                    me.ajax.send(url, "", 0);
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmGENRILISTKRSS").block();
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：画面項目ｸﾘｱ
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //**********************************************************************
    me.subClearForm = function () {
        $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").val("");
        $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val("");
        $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMFrom").val("");
        $(".KRSS.FrmGENRILISTKRSS.lblBusyoNMTo").val("");
    };

    //********************************************************************
    //処理概要：字符変化
    //引　　数：obj : 対象
    //戻 り 値：字符
    //********************************************************************
    me.fncDataNullStr = function (val) {
        if (val == "" || val == null) {
            return "";
        } else {
            return val.toString();
        }
    };

    //********************************************************************
    //処理概要：入力チェック
    //引　　数：無し
    //戻 り 値：true/false
    //********************************************************************
    me.fncInputCheck = function () {
        //部署コード大小ﾁｪｯｸ
        if (
            $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").val().trimEnd() != "" &&
            $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val().trimEnd() != ""
        ) {
            if (
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").val().trimEnd() >
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").val().trimEnd()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom"
                );
                me.clsComFnc.FncMsgBox("W9999", "部署コードの範囲が不正です");
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                return false;
            } else {
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
            }
        }
        //正常終了
        return true;
    };

    //権限を付与する
    me.fncAuthorityInvest = function (BusyoCd) {
        var tempArr = [];
        var i = 0;
        //获取本画面所有的input,button
        for (i = 0; i < $(".KRSS.FrmGENRILISTKRSS:input").length; i++) {
            //过滤掉lbl.因为有些lbl是<input> disabled.
            if (
                $($(".KRSS.FrmGENRILISTKRSS:input")[i])
                    .attr("class")
                    .split(" ")[2]
                    .match("lbl") == null
            ) {
                tempArr.push(
                    $($(".KRSS.FrmGENRILISTKRSS:input")[i])
                        .attr("class")
                        .split(" ")[2]
                );
            }
        }
        console.log(tempArr);
        var url = me.sys_id + "/" + me.id + "/" + "fncAuthorityInvest";
        var data = {
            controls: tempArr,
            BusyoCd: BusyoCd,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    for (key in result["data"]) {
                        //権限あり
                        if (result["data"][key] == 1) {
                            if (key.match("txt")) {
                                $(".KRSS.FrmGENRILISTKRSS." + key).attr(
                                    "disabled",
                                    "false"
                                );
                            } else {
                                $(".KRSS.FrmGENRILISTKRSS." + key).button(
                                    "enable"
                                );
                            }
                        }
                        //権限なし
                        else {
                            if (key.match("txt")) {
                                $(".KRSS.FrmGENRILISTKRSS." + key).attr(
                                    "disabled",
                                    "true"
                                );
                                $(".KRSS.FrmGENRILISTKRSS." + key).css(
                                    "backgroundColor",
                                    "white"
                                );
                            } else {
                                $(".KRSS.FrmGENRILISTKRSS." + key).button(
                                    "disable"
                                );
                            }
                        }
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：EXCEL出力
    //関 数 名：cmdAct_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：限界利益一覧表を作成する
    //**********************************************************************
    me.cmdAct_Click = function (btnNM) {
        //入力ﾁｪｯｸ
        if (me.fncInputCheck() == false) {
            return;
        }
        if (btnNM == "cmdExcelOut") {
            var url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";
        } else {
            var url = me.sys_id + "/" + me.id + "/" + "checklist_Click";
        }
        var data = {
            intAuth: me.intAuth,
            cboYM:
                $(".KRSS.FrmGENRILISTKRSS.cboYM").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmGENRILISTKRSS.cboYM").val().substring(4, 6),
            txtBusyoCDFrom: $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom")
                .val()
                .trimEnd(),
            txtBusyoCDTo: $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDTo")
                .val()
                .trimEnd(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //画面項目ｸﾘｱ
                    if (
                        $(".KRSS.FrmGENRILISTKRSS.txtBusyoCDFrom").prop(
                            "disabled"
                        ) == false
                    ) {
                        me.subClearForm();
                    }
                    $(".KRSS.FrmGENRILISTKRSS.cboYM").trigger("focus");
                    window.location.href = result["data"];
                } else {
                    me.clsComFnc.FncMsgBox("I0001");
                }
            } else {
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
    var o_KRSS_FrmGENRILISTKRSS = new KRSS.FrmGENRILISTKRSS();
    o_KRSS_FrmGENRILISTKRSS.load();
});
