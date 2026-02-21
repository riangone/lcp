/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230801           機能変更　　　データを更新する後、選択行を保持しておく            caina
 * 20250219           機能変更         20250219_内部統制_改修要望.xlsx                 YIN
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMAUD.HMAUDKyotenMenteSetting");

HMAUD.HMAUDKyotenMenteSetting = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.sys_id = "HMAUD";
    me.id = "HMAUDKyotenMenteSetting";
    me.syainData = [];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDKyotenMenteSetting.Button3",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMAUDKyotenMenteSetting.Button2",
        type: "button",
        handle: "",
    });
    //ステータス
    me.statusSelectList = [
        {
            val: "1",
            text: "営業",
        },
        {
            val: "2",
            text: "サービス",
        },
        {
            val: "3",
            text: "管理",
        },
        {
            val: "4",
            text: "業売",
        },
        {
            val: "5",
            text: "業売管理",
        },
        // 20250219 YIN INS S
        {
            val: "6",
            text: "カーセブン",
        },
        // 20250219 YIN INS E
    ];
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

    // '**********************************************************************
    // '更新イベント
    // '**********************************************************************
    $(".HMAUDKyotenMenteSetting.Button3").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.fncUpdataMst();
        };
        me.clsComFnc.FncMsgBox("QY012");
    });

    //戻るボタン押下
    $(".HMAUDKyotenMenteSetting.Button2").click(function () {
        me.btnBack_Click();
    });

    $(".HMAUDKyotenMenteSetting.kyoten_userid").on("blur", function () {
        $(".HMAUDKyotenMenteSetting.kyoten_username").val(
            me.getSyainNM(this.value)
        );
    });
    $(".HMAUDKyotenMenteSetting.responsible_userid").on("blur", function () {
        $(".HMAUDKyotenMenteSetting.responsible_username").val(
            me.getSyainNM(this.value)
        );
    });
    $(".HMAUDKyotenMenteSetting.keyperson_userid").on("blur", function () {
        $(".HMAUDKyotenMenteSetting.keyperson_username").val(
            me.getSyainNM(this.value)
        );
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
    var base_load = me.init_control;
    me.init_control = function () {
        base_load();

        $(".HMAUDKyotenMenteSetting.body").dialog({
            autoOpen: false,
            width: 500,
            height: 300,
            modal: true,
            title: "担当者設定",
            open: function () {
                var localStorage = window.localStorage;
                var requestdata = JSON.parse(
                    localStorage.getItem("requestdata")
                );

                if (requestdata) {
                    me.kyoten_cd = requestdata["kyoten_cd"];
                    me.kyoten_name = requestdata["kyoten_name"];
                    me.territory = requestdata["territory"];
                    //領域
                    $("<option></option>")
                        .val("")
                        .text("")
                        .appendTo(".HMAUDKyotenMenteSetting.statusSelect");
                    for (var i = 0; i < me.statusSelectList.length; i++) {
                        $("<option></option>")
                            .val(me.statusSelectList[i].val)
                            .text(me.statusSelectList[i].text)
                            .appendTo(".HMAUDKyotenMenteSetting.statusSelect");
                    }
                    $(".HMAUDKyotenMenteSetting.statusSelect").val(
                        me.territory
                    );
                    $(".HMAUDKyotenMenteSetting.kyoten_cd").val(me.kyoten_cd);
                    $(".HMAUDKyotenMenteSetting.kyoten_name").val(
                        me.kyoten_name
                    );

                    // me.HMAUDKyotenMenteSetting_Load();
                }
                localStorage.removeItem("requestdata");
            },
            close: function () {
                me.before_close();
                $(".HMAUDKyotenMenteSetting.body").remove();
            },
        });
        $(".HMAUDKyotenMenteSetting.body").dialog("open");
        var url = me.sys_id + "/" + me.id + "/pageLoad";
        var data = {
            kyoten_cd: me.kyoten_cd,
            territory: me.territory,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".HMAUDKyotenMenteSetting.Button3").button("disable");
                return;
            }
            if (result["data"].length != 0) {
                me.syainData = result["data"]["GetSyainMstValue"];
                if (result["data"][0]) {
                    $(".HMAUDKyotenMenteSetting.kyoten_userid").val(
                        result["data"][0]["RESPONSIBLE_EIGYO"]
                    );
                    $(".HMAUDKyotenMenteSetting.kyoten_username").val(
                        me.getSyainNM(result["data"][0]["RESPONSIBLE_EIGYO"])
                    );
                    $(".HMAUDKyotenMenteSetting.responsible_userid").val(
                        result["data"][0]["RESPONSIBLE_TERRITORY"]
                    );
                    $(".HMAUDKyotenMenteSetting.responsible_username").val(
                        me.getSyainNM(
                            result["data"][0]["RESPONSIBLE_TERRITORY"]
                        )
                    );
                    $(".HMAUDKyotenMenteSetting.keyperson_userid").val(
                        result["data"][0]["KEY_PERSON"]
                    );
                    $(".HMAUDKyotenMenteSetting.keyperson_username").val(
                        me.getSyainNM(result["data"][0]["KEY_PERSON"])
                    );
                }

                $(".HMAUDKyotenMenteSetting.kyoten_userid").trigger("focus");
            }
        };

        me.ajax.send(url, data, 0);
    };
    me.before_close = function () {};
    me.fncUpdataMst = function () {
        //20230801 caina ins s
        var scrollPosition = $(o_HMAUD_HMAUD.HMAUDKyotenMente.grid_id)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop();
        //20230801 caina ins e
        var url = me.sys_id + "/" + me.id + "/fncUpdataMst";
        me.data = {
            kyoten_cd: $(".HMAUDKyotenMenteSetting.kyoten_cd").val(),
            territory: $(".HMAUDKyotenMenteSetting.statusSelect").val(),
            kyoten_userid: $(".HMAUDKyotenMenteSetting.kyoten_userid").val(),
            responsible_userid: $(
                ".HMAUDKyotenMenteSetting.responsible_userid"
            ).val(),
            keyperson_userid: $(
                ".HMAUDKyotenMenteSetting.keyperson_userid"
            ).val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                setTimeout(function () {
                    //20230801 caina upd s
                    // o_HMAUD_HMAUD.HMAUDKyotenMente.refreshFlg = true;
                    $(".HMAUDKyotenMenteSetting.body").dialog("close");
                    o_HMAUD_HMAUD.HMAUDKyotenMente.Page_Load(1, scrollPosition);
                    //20230801 caina upd e
                    me.clsComFnc.FncMsgBox("I0008");
                }, 100);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, me.data, 0);
    };

    // '**********************************************************************
    // '閉めます
    // '**********************************************************************
    me.btnBack_Click = function () {
        $(".HMAUDKyotenMenteSetting.body").remove();
        $(".HMAUDKyotenMenteSetting.body").dialog("close");
    };
    me.getSyainNM = function (syainCD) {
        for (var i = 0; i < me.syainData.length; i++) {
            if (me.syainData[i]["SYAIN_NO"] == syainCD) {
                return me.syainData[i]["SYAIN_NM"];
            }
        }
        return "";
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDKyotenMenteSetting = new HMAUD.HMAUDKyotenMenteSetting();
    o_HMAUD_HMAUD.HMAUDKyotenMente.HMAUDKyotenMenteSetting =
        o_HMAUD_HMAUDKyotenMenteSetting;
    o_HMAUD_HMAUDKyotenMenteSetting.load();
});
