Namespace.register("HMTVE.HMTVE350HBUSYOEntry");

HMTVE.HMTVE350HBUSYOEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE350HBUSYOEntry";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE350HBUSYOEntry.btnLogin",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE350HBUSYOEntry.btnShowAll",
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

    //処理説明：登録ボタン押下時
    $(".HMTVE350HBUSYOEntry.btnLogin").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.btnLogin_Click();
        };
        me.clsComFnc.FncMsgBox(
            "QY999",
            "部署マスタを更新します。よろしいですか？"
        );
    });

    //処理説明：一覧へボタン押下時
    $(".HMTVE350HBUSYOEntry.btnShowAll").click(function () {
        $(".HMTVE350HBUSYOEntry.body").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：init_control
    //引    数：無し
    //戻 り 値 ：無し
    //処理説明 ：
    //**********************************************************************
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        //初期設定処理
        $(".HMTVE350HBUSYOEntry.body").dialog({
            autoOpen: false,
            height: me.ratio === 1.5 ? 410 : 490,
            width: 795,
            modal: true,
            title: "部署マスタメンテナンス_入力",
            open: function () {},
            close: function () {
                me.before_close();
                $(".HMTVE350HBUSYOEntry.body").remove();
            },
        });
        $(".HMTVE350HBUSYOEntry.body").dialog("open");

        me.Page_Load();
    };

    me.before_close = function () {};

    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        try {
            //画面初期化
            $(".HMTVE350HBUSYOEntry.txtID").val("");
            $(".HMTVE350HBUSYOEntry.txtName").val("");
            $(".HMTVE350HBUSYOEntry.txtNameKa").val("");
            $(".HMTVE350HBUSYOEntry.txtSName").val("");
            $(".HMTVE350HBUSYOEntry.txtPartment").val("");
            $(".HMTVE350HBUSYOEntry.txtPartChange").val("");
            $(".HMTVE350HBUSYOEntry.txtShopID").val("");
            $(".HMTVE350HBUSYOEntry.txtSetDistinction").val("");
            $(".HMTVE350HBUSYOEntry.txtPartDistinction").val("");
            $(".HMTVE350HBUSYOEntry.txtToPartDistinction").val("");
            $(".HMTVE350HBUSYOEntry.txtMsgID").val("");
            $(".HMTVE350HBUSYOEntry.txtSetDay").val("");
            $(".HMTVE350HBUSYOEntry.txtCloseDay").val("");
            $(".HMTVE350HBUSYOEntry.txtShowIndex").val("");
            $(".HMTVE350HBUSYOEntry.txtNewCar").val("");
            $(".HMTVE350HBUSYOEntry.txtOldCar").val("");
            $(".HMTVE350HBUSYOEntry.txtMeanwhile").val("");
            $(".HMTVE350HBUSYOEntry.txtPandLS").val("");
            $(".HMTVE350HBUSYOEntry.txtObjRes").val("");
            $(".HMTVE350HBUSYOEntry.txtFactObj").val("");
            $(".HMTVE350HBUSYOEntry.txtRate").val("");
            $(".HMTVE350HBUSYOEntry.txtSetShopID").val("");
            $(".HMTVE350HBUSYOEntry.txtSetShowIndex").val("");
            $(".HMTVE350HBUSYOEntry.txtTandFShowIndex").val("");
            $(".HMTVE350HBUSYOEntry.txtShopIndex").val("");

            if ($("#param").html() == 2) {
                //登録画面起動時の引継ぎパラメータ(モード)＝"2"の場合
                //入力不可の項目を不活性にする
                $(".HMTVE350HBUSYOEntry.txtID").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtName").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtNameKa").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtSName").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtPartment").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtPartChange").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtShopID").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtSetDistinction").prop(
                    "disabled",
                    true
                );
                $(".HMTVE350HBUSYOEntry.txtPartDistinction").prop(
                    "disabled",
                    true
                );
                $(".HMTVE350HBUSYOEntry.txtToPartDistinction").prop(
                    "disabled",
                    true
                );
                $(".HMTVE350HBUSYOEntry.txtMsgID").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtSetDay").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtCloseDay").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtShowIndex").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtNewCar").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtOldCar").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtMeanwhile").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtPandLS").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtObjRes").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtFactObj").prop("disabled", true);
                $(".HMTVE350HBUSYOEntry.txtRate").prop("disabled", true);
            }

            var url = me.sys_id + "/" + me.id + "/" + "fncFormload";
            var data = {
                PartmentID: $("#PartmentID").html(),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");

                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    $(".HMTVE350HBUSYOEntry.btnLogin").button("disable");
                    return;
                }

                if (result["data"].length > 0) {
                    $(".HMTVE350HBUSYOEntry.txtID").val(
                        result["data"][0]["BUSYO_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtName").val(
                        result["data"][0]["BUSYO_NM"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtNameKa").val(
                        result["data"][0]["BUSYO_KANANM"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtSName").val(
                        result["data"][0]["BUSYO_RYKNM"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtPartment").val(
                        result["data"][0]["KKR_BUSYO_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtPartChange").val(
                        result["data"][0]["CNV_BUSYO_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtShopID").val(
                        result["data"][0]["TENPO_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtSetDistinction").val(
                        result["data"][0]["SYUKEI_KB"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtPartDistinction").val(
                        result["data"][0]["BUSYO_KB"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtToPartDistinction").val(
                        result["data"][0]["TORIKOMI_BUSYO_KB"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtMsgID").val(
                        result["data"][0]["MANEGER_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtSetDay").val(
                        result["data"][0]["START_DATE"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtCloseDay").val(
                        result["data"][0]["END_DATE"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtShowIndex").val(
                        result["data"][0]["DSP_SEQNO"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtNewCar").val(
                        result["data"][0]["PRN_KB1"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtOldCar").val(
                        result["data"][0]["PRN_KB2"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtMeanwhile").val(
                        result["data"][0]["PRN_KB3"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtPandLS").val(
                        result["data"][0]["PRN_KB4"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtObjRes").val(
                        result["data"][0]["PRN_KB5"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtFactObj").val(
                        result["data"][0]["PRN_KB6"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtRate").val(
                        result["data"][0]["HKNSYT_DSP_KB"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtSetShopID").val(
                        result["data"][0]["HDT_TENPO_CD"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtSetShowIndex").val(
                        result["data"][0]["IVENT_TENPO_DISP_NO"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtTandFShowIndex").val(
                        result["data"][0]["HDT_TENPO_DISP_NO"]
                    );
                    $(".HMTVE350HBUSYOEntry.txtShopIndex").val(
                        result["data"][0]["STD_TENPO_DISP_NO"]
                    );
                }

                //focus設定
                $(".HMTVE350HBUSYOEntry.txtSetShopID").trigger("focus");
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };

    //**********************************************************************
    //処 理 名：登録ボタンのイベント
    //関 数 名：btnLogin_Click
    //引 数  ：
    //戻 り 値：なし
    //処理説明：
    //**********************************************************************
    me.btnLogin_Click = function () {
        if (
            gdmz.SessionUserId.toString() == null ||
            gdmz.SessionUserId.toString() == ""
        ) {
            return;
        }

        //入力チェック
        if (!me.checkNull()) {
            window.setTimeout(function () {
                var len = $(".ui-dialog-buttons").find(".ui-button").length;
                if (len > 0) {
                    $(".ui-dialog-buttons")
                        .find(".ui-button")
                        .eq(len - 1)
                        .trigger("focus");
                }
            }, 0);
            return;
        }

        //部署マスタの更新処理
        var url = me.sys_id + "/" + me.id + "/" + "btnLogin_Click";
        var data = {
            txtSetShopID: $(".HMTVE350HBUSYOEntry.txtSetShopID").val(),
            txtSetShowIndex: $(".HMTVE350HBUSYOEntry.txtSetShowIndex").val(),
            txtTandFShowIndex: $(
                ".HMTVE350HBUSYOEntry.txtTandFShowIndex"
            ).val(),
            txtShopIndex: $(".HMTVE350HBUSYOEntry.txtShopIndex").val(),
            txtID: $(".HMTVE350HBUSYOEntry.txtID").val(),
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"]) {
                setTimeout(function () {
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.close;
                    //登録が完了しました。
                    me.clsComFnc.FncMsgBox("I0016");
                }, 100);
            } else {
                if (result["error"] == "W0025") {
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE350HBUSYOEntry.btnShowAll"
                    );
                    //他のユーザーにより更新されています。最新の情報を確認してください。
                    me.clsComFnc.FncMsgBox("W0025");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.close = function () {
        $(".HMTVE350HBUSYOEntry.body").dialog("close");
    };

    //************************************************************************
    //処 理 名：入力チェック
    //関 数 名：checkNull
    //引    数：なし
    //戻 り 値：なし
    //処理説明：入力チェック
    //************************************************************************
    me.checkNull = function () {
        var txtID = $(".HMTVE350HBUSYOEntry.txtID");
        var txtSetShopID = $(".HMTVE350HBUSYOEntry.txtSetShopID");
        var txtSetShowIndex = $(".HMTVE350HBUSYOEntry.txtSetShowIndex");
        var txtTandFShowIndex = $(".HMTVE350HBUSYOEntry.txtTandFShowIndex");
        var txtShopIndex = $(".HMTVE350HBUSYOEntry.txtShopIndex");

        //部署コードを入力してください。
        var ckval = $.trim(txtID.val());
        var lblID = $(".HMTVE350HBUSYOEntry.lblID");
        if (ckval.length == 0) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                lblID.html() + "を入力してください。"
            );
            return false;
        }

        //イベント集計店舗コードは指定されている桁数をオーバーしています。
        var ckval = $.trim(txtSetShopID.val());
        var lblSetShopID = $(".HMTVE350HBUSYOEntry.lblSetShopID");
        if (ckval.length > 3) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtSetShopID");
            me.clsComFnc.FncMsgBox(
                "E9999",
                lblSetShopID.html() +
                    "は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        //イベント集計用表示順位は指定されている桁数をオーバーしています。
        var ckval = $.trim(txtSetShowIndex.val());
        var lblSetShowIndex = $(".HMTVE350HBUSYOEntry.lblSetShowIndex");
        if (ckval.length > 2) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtSetShowIndex");
            me.clsComFnc.FncMsgBox(
                "E9999",
                lblSetShowIndex.html() +
                    "は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        // 目標と実績用表示順位は指定されている桁数をオーバーしています。
        var ckval = $.trim(txtTandFShowIndex.val());
        var lblTandFShowIndex = $(".HMTVE350HBUSYOEntry.lblTandFShowIndex");
        if (ckval.length > 2) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtTandFShowIndex");
            me.clsComFnc.FncMsgBox(
                "E9999",
                lblTandFShowIndex.html() +
                    "は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        //店舗表示順位は指定されている桁数をオーバーしています。
        var ckval = $.trim(txtShopIndex.val());
        var lblShopIndex = $(".HMTVE350HBUSYOEntry.lblShopIndex");
        if (ckval.length > 2) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtShopIndex");
            me.clsComFnc.FncMsgBox(
                "E9999",
                lblShopIndex.html() +
                    "は指定されている桁数をオーバーしています。"
            );
            return false;
        }

        //イベント集計店舗コードが不正です。
        var ckval = $.trim(txtSetShopID.val());
        var lblSetShopID = $(".HMTVE350HBUSYOEntry.lblSetShopID");
        if (!/^[0-9]+$/.test(ckval) && ckval.length != 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtSetShopID");
            me.clsComFnc.FncMsgBox(
                "W9999",
                lblSetShopID.html() + "が不正です。"
            );
            return false;
        }

        //イベント集計用表示順位が不正です。
        var ckval = $.trim(txtSetShowIndex.val());
        var lblSetShowIndex = $(".HMTVE350HBUSYOEntry.lblSetShowIndex");
        if (!/^[0-9]+$/.test(ckval) && ckval.length != 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtSetShowIndex");
            me.clsComFnc.FncMsgBox(
                "W9999",
                lblSetShowIndex.html() + "が不正です。"
            );
            return false;
        }

        //目標と実績用表示順位が不正です。
        var ckval = $.trim(txtTandFShowIndex.val());
        var lblTandFShowIndex = $(".HMTVE350HBUSYOEntry.lblTandFShowIndex");
        if (!/^[0-9]+$/.test(ckval) && ckval.length != 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtTandFShowIndex");
            me.clsComFnc.FncMsgBox(
                "W9999",
                lblTandFShowIndex.html() + "が不正です。"
            );
            return false;
        }

        //店舗表示順位が不正です。
        var ckval = $.trim(txtShopIndex.val());
        var lblShopIndex = $(".HMTVE350HBUSYOEntry.lblShopIndex");
        if (!/^[0-9]+$/.test(ckval) && ckval.length != 0) {
            me.clsComFnc.ObjFocus = $(".HMTVE350HBUSYOEntry.txtShopIndex");
            me.clsComFnc.FncMsgBox(
                "W9999",
                lblShopIndex.html() + "が不正です。"
            );
            return false;
        }

        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE350HBUSYOEntry = new HMTVE.HMTVE350HBUSYOEntry();
    o_HMTVE_HMTVE350HBUSYOEntry.load();
    o_HMTVE_HMTVE.HMTVE340HBUSYOList.HMTVE350HBUSYOEntry =
        o_HMTVE_HMTVE350HBUSYOEntry;
});
