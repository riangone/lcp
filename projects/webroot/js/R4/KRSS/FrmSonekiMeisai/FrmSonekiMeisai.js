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

Namespace.register("KRSS.FrmSonekiMeisai");

KRSS.FrmSonekiMeisai = function () {
    var me = new gdmz.base.panel();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSonekiMeisai";
    me.sys_id = "KRSS";
    //期
    me.strKI = "";

    me.cboKisyu = "";
    me.cboYM = "";

    me.BusyoArr = new Array();

    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmSonekiMeisai.cmd003",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmSonekiMeisai.cboKisyu",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmSonekiMeisai.cboYM",
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

    //**********************************************************************
    //処 理 名：cboYMのblurイベント
    //関 数 名：cboYM_blur
    //引    数：無し
    //戻 り 値：無し
    //処理説明：cboYMの値  非YYYY/MMの場合  画面は初期値に設定
    //**********************************************************************
    // $('.KRSS.FrmSonekiMeisai.cboYM').blur(function() {
    // if (me.clsComFnc.CheckDate2($(".KRSS.FrmSonekiMeisai.cboYM")) == false) {
    // $(".KRSS.FrmSonekiMeisai.cboYM").val(me.cboYM);
    // $('.KRSS.FrmSonekiMeisai.cboKisyu').val(me.cboKisyu);
    // $('.KRSS.FrmSonekiMeisai.lblKi').val(me.strKI);
    // $(this).focus();
    // }
    // });

    //**********************************************************************
    //処 理 名：処理月変更
    //関 数 名：cboYM_ValueChanged
    //引    数：無し
    //戻 り 値：無し
    //処理説明：処理月から期と期首を求めて表示する
    //**********************************************************************
    $(".KRSS.FrmSonekiMeisai.cboYM").change(function () {
        var cboYM = $(".KRSS.FrmSonekiMeisai.cboYM").val();
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmSonekiMeisai.cboYM")) == false
        ) {
            $(".KRSS.FrmSonekiMeisai.cboYM").val(me.cboYM);
            $(".KRSS.FrmSonekiMeisai.cboKisyu").val(me.cboKisyu);
            $(".KRSS.FrmSonekiMeisai.lblKi").val(me.strKI);
            $(this).trigger("focus");
        } else {
            if (cboYM.substring(4, 6) < 10) {
                $(".KRSS.FrmSonekiMeisai.cboKisyu").val(
                    cboYM.substring(0, 4) - 1 + "10"
                );
            } else {
                $(".KRSS.FrmSonekiMeisai.cboKisyu").val(
                    cboYM.substring(0, 4) + "10"
                );
            }
            $(".KRSS.FrmSonekiMeisai.lblKi").val(
                $(".KRSS.FrmSonekiMeisai.cboKisyu").val().substring(0, 4) - 1917
            );
        }
    });

    //************************************************************************************
    //処 理 名：部署CD From. blur
    //関 数 名：部署CDFrom_blur
    //引    数：無し
    //戻 り 値：無し
    //処理説明：部署CDFromは条件、HBUSYO.BusyoNMを取得する；部署CDFromの値をコピー，部署CDToの値を設定
    //************************************************************************************
    $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmSonekiMeisai.lblBusyoNMFrom").val("");
        if ($(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val().trimEnd() ==
                    me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmSonekiMeisai.lblBusyoNMFrom").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                }
            }
            //copy
            $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val(
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val()
            );
        } else {
            $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val("");
        }
    });

    //*****************************************************************
    //処 理 名：部署CD To. blur
    //関 数 名：部署CDTo_blur
    //引    数：無し
    //戻 り 値：無し
    //処理説明：部署CDToは条件、HBUSYO.BusyoNMを取得する；
    //*****************************************************************
    $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").on("blur", function () {
        //背景色をエラー色から正常色へと変更する
        $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").css(
            "backgroundColor",
            me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
        );

        $(".KRSS.FrmSonekiMeisai.lblBusyoNMTo").val("");
        if ($(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val().trimEnd() != "") {
            //名称取得
            for (key in me.BusyoArr) {
                if (
                    $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val().trimEnd() ==
                    me.BusyoArr[key]["BUSYO_CD"]
                ) {
                    $(".KRSS.FrmSonekiMeisai.lblBusyoNMTo").val(
                        me.BusyoArr[key]["BUSYO_NM"]
                    );
                }
            }
        }
    });

    //**********************************************************************
    //処理説明：Excel出力ボタン押下時
    //**********************************************************************
    $(".KRSS.FrmSonekiMeisai.cmd003").click(function () {
        me.cmdAct_Click();
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    me.load = function () {
        base_load();
        me.fncGetBusyo();
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
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    $(".KRSS.FrmSonekiMeisai.cboKisyu").ympicker(
                        "setDate",
                        new Date()
                    );
                    $(".KRSS.FrmSonekiMeisai.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
                    $("#KRSS_FrmSonekiMeisai").block();
                    return;
                } else {
                    //コンボボックスに当月年月を設定
                    $(".KRSS.FrmSonekiMeisai.cboYM").val(
                        me.clsComFnc
                            .FncNv(result["data"][0]["TOUGETU"].substring(0, 7))
                            .replace(/\//, "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substring(0, 7)
                        .replace(/\//, "");

                    //期を変数に格納
                    me.strKI = me.clsComFnc
                        .FncNv(result["data"][0]["KI"])
                        .toString();
                    //当期以前のデータを抽出できるように変更
                    $(".KRSS.FrmSonekiMeisai.lblKi").val(me.strKI);
                    //当期以前のデータを抽出できるように変更
                    // $('.KRSS.FrmSonekiMeisai.cboKisyu').ympicker("option", {
                    // disabled : true
                    // });

                    $(".KRSS.FrmSonekiMeisai.DIVcboKisyu").block({
                        overlayCSS: {
                            opacity: 0,
                        },
                    });
                    $(".KRSS.FrmSonekiMeisai.cboKisyu").attr(
                        "disabled",
                        "disabled"
                    );
                    me.cboKisyu =
                        me.clsComFnc
                            .FncNv(result["data"][0]["KISYU"])
                            .substring(0, 4) +
                        me.clsComFnc
                            .FncNv(result["data"][0]["KISYU"])
                            .substring(4, 6);
                    $(".KRSS.FrmSonekiMeisai.cboKisyu").val(me.cboKisyu);

                    //権限のﾁｪｯｸを行う
                    var url = me.sys_id + "/" + me.id + "/" + "fncAuthCheck";
                    me.ajax.receive = function (result) {
                        result = JSON.parse(result);
                        if (result["result"] == true) {
                            tblCTL = result["data"];
                            if (tblCTL.length == 0) {
                                //0件の場合
                                me.clsComFnc.FncMsgBox(
                                    "W9999",
                                    "権限の設定がされていません。管理者にご連絡ください！"
                                );
                                $("#KRSS_FrmSonekiMeisai").block();
                                return;
                            } else if (tblCTL.length == 1) {
                                $(".KRSS.FrmSonekiMeisai.cboYM").trigger(
                                    "focus"
                                );
                                //1件の場合
                                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").attr(
                                    "disabled",
                                    "true"
                                );
                                //$(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").css("backgroundColor", "white");
                                $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").attr(
                                    "disabled",
                                    "true"
                                );
                                //$(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").css("backgroundColor", "white");
                                //部署コード
                                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val(
                                    me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                );
                                $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val(
                                    me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                );

                                if (result["BusyoMst"]["intRtnCD"] == 1) {
                                    $(
                                        ".KRSS.FrmSonekiMeisai.lblBusyoNMFrom"
                                    ).val(result["BusyoMst"]["strBusyoNM"]);
                                    $(".KRSS.FrmSonekiMeisai.lblBusyoNMTo").val(
                                        result["BusyoMst"]["strBusyoNM"]
                                    );
                                } else {
                                    $(
                                        ".KRSS.FrmSonekiMeisai.lblBusyoNMFrom"
                                    ).val("");
                                    $(".KRSS.FrmSonekiMeisai.lblBusyoNMTo").val(
                                        ""
                                    );
                                }

                                //権限を付与する
                                me.fncAuthorityInvest(
                                    me.fncDataNullStr(tblCTL[0]["BUSYO_CD"])
                                );
                            }
                            // else
                            // {
                            // //>1件の場合
                            // //何も処理しない
                            // }
                            $(".KRSS.FrmSonekiMeisai.cboYM").trigger("focus");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["data"]);
                            $("#KRSS_FrmSonekiMeisai").block();
                            return;
                        }
                    };
                    me.ajax.send(url, "", 0);
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmSonekiMeisai").block();
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //**********************************************************************
    //処 理 名：getBusyo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.fncGetBusyo = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetBusyo";
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                me.BusyoArr = result["data"];
                //console.log(me.BusyoArr);
                me.frmKanrSyukei_Load();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmSonekiMeisai").block();
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
        $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val("");
        $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val("");
        $(".KRSS.FrmSonekiMeisai.lblBusyoNMFrom").val("");
        $(".KRSS.FrmSonekiMeisai.lblBusyoNMTo").val("");
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

    //**********************************************************************
    //処 理 名：権限付与
    //関 数 名：fncAuthorityInvest
    //引    数：strBusyoCD       (I)部署コード
    //戻 り 値：
    //処理説明：対象ﾌｫｰﾑ上に存在するｺﾝﾄﾛｰﾙに対して
    //          該当社員が該当部署に対して権限がある場合はｺﾝﾄﾛｰﾙを活性に、権限がない場合は不活性にする
    //**********************************************************************
    me.fncAuthorityInvest = function (BusyoCd) {
        var tempArr = [];
        var i = 0;
        //获取本画面所有的input,button
        for (i = 0; i < $(".KRSS.FrmSonekiMeisai:input").length; i++) {
            //过滤掉lbl.因为有些lbl是<input> disabled.
            if (
                $($(".KRSS.FrmSonekiMeisai:input")[i])
                    .attr("class")
                    .split(" ")[2]
                    .match("lbl") == null
            ) {
                tempArr.push(
                    $($(".KRSS.FrmSonekiMeisai:input")[i])
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
            result = JSON.parse(result);
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    for (key in result["data"]) {
                        //権限あり
                        if (result["data"][key] == 1) {
                            // if (key.match("txt")) {
                            // $('.KRSS.FrmSonekiMeisai.' + key).attr('disabled', 'false');
                            // } else {
                            $(".KRSS.FrmSonekiMeisai." + key).button("enable");
                            //}
                        }
                        //権限なし
                        else {
                            // if (key.match("txt")) {
                            // $('.KRSS.FrmSonekiMeisai.' + key).attr('disabled', 'true');
                            // $('.KRSS.FrmSonekiMeisai.' + key).css('backgroundColor', "white");
                            // } else {
                            $(".KRSS.FrmSonekiMeisai." + key).button("disable");
                            //}
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

    //********************************************************************
    //処理概要：入力チェック
    //引　　数：無し
    //戻 り 値：true/false
    //********************************************************************
    me.fncInputCheck = function () {
        //部署コード大小ﾁｪｯｸ
        if (
            $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val().trimEnd() != "" &&
            $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val().trimEnd() != ""
        ) {
            if (
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").val().trimEnd() >
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").val().trimEnd()
            ) {
                me.clsComFnc.ObjFocus = $(
                    ".KRSS.FrmSonekiMeisai.txtBusyoCDFrom"
                );
                me.clsComFnc.FncMsgBox("W9999", "部署コードの範囲が不正です");
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_ERROR["backgroundColor"]
                );
                return false;
            } else {
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
                $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo").css(
                    "backgroundColor",
                    me.clsComFnc.GC_COLOR_NORMAL["backgroundColor"]
                );
            }
        }
        //正常終了
        return true;
    };

    //**********************************************************************
    //処 理 名：印刷
    //関 数 名：cmdAct_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：限界利益一覧表を作成する
    //**********************************************************************
    me.cmdAct_Click = function () {
        //入力ﾁｪｯｸ
        if (me.fncInputCheck() == false) {
            return;
        }
        //印刷処理
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var data = {
            strKI: $(".KRSS.FrmSonekiMeisai.lblKi").val().trimEnd(),
            cboKisyu:
                $(".KRSS.FrmSonekiMeisai.cboKisyu").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmSonekiMeisai.cboKisyu").val().substring(4, 6),
            cboYM:
                $(".KRSS.FrmSonekiMeisai.cboYM").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmSonekiMeisai.cboYM").val().substring(4, 6),
            txtBusyoCDFrom: $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom")
                .val()
                .trimEnd(),
            txtBusyoCDTo: $(".KRSS.FrmSonekiMeisai.txtBusyoCDTo")
                .val()
                .trimEnd(),
            AUTHID: "cmd003",
        };
        me.ajax.receive = function (result) {
            result = JSON.parse(result);
            if (result["result"] == true) {
                //console.log(result['data']);
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    //画面ｸﾘｱ処理
                    if (
                        $(".KRSS.FrmSonekiMeisai.txtBusyoCDFrom").prop(
                            "disabled"
                        ) == false
                    ) {
                        me.subClearForm();
                    }
                    //ﾌｫｰｶｽ移動
                    $(".KRSS.FrmSonekiMeisai.cboYM").trigger("focus");
                    //Excel出力
                    window.location.href = result["data"];
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
    var o_KRSS_FrmSonekiMeisai = new KRSS.FrmSonekiMeisai();
    o_KRSS_FrmSonekiMeisai.load();
});
