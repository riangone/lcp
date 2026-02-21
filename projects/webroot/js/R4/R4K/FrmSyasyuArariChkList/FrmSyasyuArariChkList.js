/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150923 		  #2162						   BUG								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSyasyuArariChkList");
R4.FrmSyasyuArariChkList = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSyasyuArariChkList";
    me.sys_id = "R4K";
    me.cboYMStartState = "";
    me.cboYMEndState = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmSyasyuArariChkList.button_action",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuArariChkList.cboYMStart",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyasyuArariChkList.cboYMEnd",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmSyasyuArariChkList.button_action").click(function () {
        me.button_action_Click();
    });
    $(".FrmSyasyuArariChkList.radChkList").click(function () {
        if ($(".FrmSyasyuArariChkList.radChkList").prop("checked")) {
            var tmpVal = $(".FrmSyasyuArariChkList.cboYMStart").val();
            //20150922 yin upd S
            // $('.FrmSyasyuArariChkList.cboYMStart').datepicker("option",
            // {
            // disabled : true
            // });
            $(".FrmSyasyuArariChkList.cboYMStartdiv").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            $(".FrmSyasyuArariChkList.cboYMStart").attr("disabled", true);
            //20150922 yin upd E

            $(".FrmSyasyuArariChkList.cboYMStart").val(tmpVal);
        }
    });
    $(".FrmSyasyuArariChkList.radMeisai").click(function () {
        if ($(".FrmSyasyuArariChkList.radMeisai").prop("checked")) {
            var tmpVal = $(".FrmSyasyuArariChkList.cboYMStart").val();

            //20150922 yin upd S
            // $('.FrmSyasyuArariChkList.cboYMStart').datepicker("option",
            // {
            // disabled : true
            // });
            $(".FrmSyasyuArariChkList.cboYMStartdiv").unblock();
            $(".FrmSyasyuArariChkList.cboYMStart").attr("disabled", false);
            //20150922 yin upd E
            $(".FrmSyasyuArariChkList.cboYMStart").val(tmpVal);
        }
    });
    $(".FrmSyasyuArariChkList.radDouble").click(function () {
        if ($(".FrmSyasyuArariChkList.radDouble").prop("checked")) {
            var tmpVal = $(".FrmSyasyuArariChkList.cboYMStart").val();

            //20150922 yin upd S
            // $('.FrmSyasyuArariChkList.cboYMStart').datepicker("option",
            // {
            // disabled : true
            // });
            $(".FrmSyasyuArariChkList.cboYMStartdiv").unblock();
            $(".FrmSyasyuArariChkList.cboYMStart").attr("disabled", false);
            //20150922 yin upd E
            $(".FrmSyasyuArariChkList.cboYMStart").val(tmpVal);
        }
    });

    //-----
    $(".FrmSyasyuArariChkList.cboYMStart").on("blur", function () {
        //20150922 yin upd S
        // if (me.clsComFnc.CheckDate2($(".FrmSyasyuArariChkList.cboYMStart")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuArariChkList.cboYMStart")) ==
            false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuArariChkList.cboYMStart").trigger("focus");
                $(".FrmSyasyuArariChkList.cboYMStart").val(me.cboYMStartState);
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        }
    });
    $(".FrmSyasyuArariChkList.cboYMEnd").on("blur", function () {
        //20150922 yin upd S
        // if (me.clsComFnc.CheckDate2($(".FrmSyasyuArariChkList.cboYMEnd")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmSyasyuArariChkList.cboYMEnd")) ==
            false
        ) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyasyuArariChkList.cboYMStart").trigger("focus");
                $(".FrmSyasyuArariChkList.cboYMEnd").val(me.cboYMEndState);
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        }
    });
    //-----
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmSyasyuArariChkList_load();
    };
    me.FrmSyasyuArariChkList_load = function () {
        me.formLoad();
        //20150922 yin upd S
        // $('.FrmSyasyuArariChkList.cboYMStart').datepicker("option",
        // {
        // disabled : true
        // });
        $(".FrmSyasyuArariChkList.cboYMStartdiv").block({
            overlayCSS: {
                opacity: 0,
            },
        });
        $(".FrmSyasyuArariChkList.cboYMStart").attr("disabled", true);
        //20150922 yin upd E
        $(".FrmSyasyuArariChkList.radChkList").prop("checked", true);
        $(".FrmSyasyuArariChkList.radChkList").trigger("focus");
    };
    /*
	 '**********************************************************************
	 '処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
	 '関 数 名：frmKanrSyukei_Load
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：初期設定
	 '**********************************************************************
	 */
    me.formLoad = function () {
        var url = me.sys_id + "/" + me.id + "/" + "formLoad";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                //コントロールマスタ存在ﾁｪｯｸ
                if (result["data"].length > 0) {
                    //コンボボックスに当月年月を設定
                    //20150923 yin upd S
                    // $tmpDateEnd = result['data'][0]['TOUGETU'].substr(0, 7);
                    // $tmpDateStart = result['data'][0]['KISYU_YMD'].substr(0, 7);
                    $tmpDateEnd = result["data"][0]["TOUGETU"]
                        .substring(0, 7)
                        .replace("/", "");
                    $tmpDateStart = result["data"][0]["KISYU_YMD"]
                        .substring(0, 7)
                        .replace("/", "");
                    //20150923 yin upd E
                    me.cboYMStartState = $tmpDateStart;
                    me.cboYMEndState = $tmpDateEnd;
                    $(".FrmSyasyuArariChkList.cboYMEnd").val($tmpDateEnd);
                    $(".FrmSyasyuArariChkList.cboYMStart").val($tmpDateStart);
                    //画面項目ｸﾘｱ
                    //me.subFormClear();
                    //コンボボックスに値を設定
                    $(".FrmSyasyuArariChkList.cboYMEnd").trigger("focus");
                } else {
                    var myDate = new Date();
                    var tmpMonth = (myDate.getMonth() + 1).toString();
                    if (tmpMonth.length < 2) {
                        tmpMonth = "0" + tmpMonth.toString();
                    }
                    var tmpNowDate =
                        myDate.getFullYear().toString() +
                        "/" +
                        tmpMonth.toString();
                    $(".FrmSyasyuArariChkList.cboYMEnd").val(tmpNowDate);
                    $(".FrmSyasyuArariChkList.cboYMStart").val(tmpNowDate);

                    //コントロールマスタが存在していない場合
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 1);
    };

    /*
	 '**********************************************************************
	 '処 理 名：実行
	 '関 数 名：button_action_Click
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：印刷する
	 '**********************************************************************
	 */
    me.button_action_Click = function () {
        //入力チェック
        if (
            $(".FrmSyasyuArariChkList.radMeisai").prop("checked") ||
            $(".FrmSyasyuArariChkList.radDouble").prop("checked")
        ) {
            if (
                $(".FrmSyasyuArariChkList.cboYMEnd").val() <
                $(".FrmSyasyuArariChkList.cboYMStart").val()
            ) {
                me.clsComFnc.FncMsgBox("W9999", "日付の大小関係が不正です！");
                if ($(".FrmSyasyuArariChkList.cboYMStart").prop("disabled")) {
                    $(".FrmSyasyuArariChkList.cboYMEnd").trigger("focus");
                }
                return;
            }
        }

        /*if ($(".FrmSyasyuArariChkList.radChkList").prop("checked"))
		 {*/
        var url = me.sys_id + "/" + me.id + "/" + "fncCmdAction";
        $data = {
            cboYMEnd: $(".FrmSyasyuArariChkList.cboYMEnd")
                .val()
                .replace("/", ""),
            cboYMStart: $(".FrmSyasyuArariChkList.cboYMStart")
                .val()
                .replace("/", ""),
            radChkList: $(".FrmSyasyuArariChkList.radChkList").prop("checked"),
            radMeisai: $(".FrmSyasyuArariChkList.radMeisai").prop("checked"),
            radDouble: $(".FrmSyasyuArariChkList.radDouble").prop("checked"),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                window.open(result["data"]);
            } else {
                if (result["data"]["TFException"]) {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        result["data"]["messageContent"]
                    );
                } else {
                    if (!result["data"]["messageContent"] == "") {
                        me.clsComFnc.FncMsgBox(
                            result["data"]["messageCode"],
                            result["data"]["messageContent"]
                        );
                    } else {
                        me.clsComFnc.FncMsgBox(result["data"]["messageCode"]);
                    }
                }
            }
        };
        me.ajax.send(url, $data, 1);
    };

    //};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    var o_R4_FrmSyasyuArariChkList = new R4.FrmSyasyuArariChkList();
    o_R4_FrmSyasyuArariChkList.load();
});
