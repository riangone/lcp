/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151102           #2245                        BUG                              LI
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmKaikeiMake");
R4.FrmKaikeiMake = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmKaikeiMake";
    me.sys_id = "R4K";
    //---customer start---
    me.strSaveYM = "";
    me.strSaveItem = "";
    me.validatingArr = {
        current: "",
        before: "",
    };
    me.grid_id = "#FrmKaikeiMake_sprErrList";
    //me.g_url = me.sys_id + "/" + me.id + "/" + "fncFrmKaikeiMakeSelect";
    //---customer end---

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmKaikeiMake.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKaikeiMake.cboDateFrom",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmKaikeiMake.cboDateTo",
        type: "datepicker",
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
    //--click events--
    $(".FrmKaikeiMake.cmdAction").click(function () {
        me.fnc_click_cmdAction();
    });
    //--blur events--
    $(".FrmKaikeiMake.cboDateFrom").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmKaikeiMake.cboDateFrom")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                me.initDate(".FrmKaikeiMake.cboDateFrom");
                return false;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
        }
    });
    $(".FrmKaikeiMake.cboDateTo").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmKaikeiMake.cboDateTo")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                me.initDate(".FrmKaikeiMake.cboDateTo");
                return false;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
        }
    });
    // ==========
    // = イベント end =
    // ==========
    // ==========
    // = メソッド start =
    // ==========
    //--event functions--
    /*
	 '**********************************************************************
	 '処 理 名：CSVファイル出力
	 '関 数 名：cmdAction_Click
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：CSVファイル出力処理
	 '**********************************************************************

	 */
    me.fnc_click_cmdAction = function () {
        //$(".FrmKaikeiMake.cmdAction").button('disable');
        if (!me.fnc_inputChk()) {
            return;
        }
        //出力確認ﾒｯｾｰｼﾞ
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.YesActionFnc;
        me.clsComFnc.MsgBoxBtnFnc.No = me.NoActionFnc;
        me.clsComFnc.FncMsgBox("QY009");
    };
    //--functions--
    me.initGrid1 = function () {
        $(me.grid_id).jqGrid({
            datatype: "local",
            // jqgridにデータがなし場合、文字表示しない
            emptyRecordRow: false,
            width: 150,
            height: 150,
            rownumbers: true,
            colModel: [
                {
                    name: "R_KAMOK_CD",
                    label: "ｴﾗｰ科目ｺｰﾄﾞ",
                    index: "R_KAMOK_CD",
                    width: 100,
                    sortable: false,
                    align: "left",
                },
            ],
        });
    };
    me.YesActionFnc = function () {
        $(me.grid_id).clearGridData();
        $(".FrmKaikeiMake.lblCnt").html("");
        $(".FrmKaikeiMake.frame3").css("visibility", "hidden");
        var data = {
            strDepend1: $(".FrmKaikeiMake.cboDateFrom").val(),
            strDepend2: $(".FrmKaikeiMake.cboDateTo").val(),
            sprErrList_data: $(me.grid_id).jqGrid("getRowData"),
        };
        me.actionUrl = me.sys_id + "/" + me.id + "/" + "formDeal";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["mesgID"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    $(".FrmKaikeiMake.lblMsg").html("");
                    $(".FrmKaikeiMake.cmdAction").button("enable");
                }
                if (result["mesgID"] == "") {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    $(".FrmKaikeiMake.lblMsg").html("");
                    $(".FrmKaikeiMake.cmdAction").button("enable");
                }
                if (result["jqgrid_data"] !== undefined) {
                    if (result["jqgrid_data"].length <= 0) {
                        $(".FrmKaikeiMake.lblMsg").html(result["lblmsg"]);
                        me.clsComFnc.MessageBox(
                            result["data"],
                            me.clsComFnc.GSYSTEM_NAME,
                            "OK",
                            "Warning"
                        );
                    } else {
                        $(".FrmKaikeiMake.frame3").css("visibility", "visible");
                        for (var i = 0; i < result["jqgrid_data"].length; i++) {
                            me.fnc_addrow(result["jqgrid_data"][i]);
                        }

                        $(".FrmKaikeiMake.lblMsg").html(result["lblmsg"]);
                        me.clsComFnc.MessageBox(
                            result["data"],
                            me.clsComFnc.GSYSTEM_NAME,
                            "OK",
                            "Warning"
                        );
                    }
                }
                $(".FrmKaikeiMake.cmdAction").button("enable");
                return;
            } else {
                if (result["mesgID"] == "I0011") {
                    $(".FrmKaikeiMake.lblMsg").html("");
                    me.clsComFnc.FncMsgBox("I0011");
                    $(".FrmKaikeiMake.lblCnt").html(
                        result["number_of_rows"].toString().numFormat()
                    );
                    if (result["pdfpath"] != "") {
                        window.open(result["pdfpath"]);
                    }
                }
            }
            //-- 20151102 li DEL S.
            // alert("true");
            //-- 20151102 li DEL E.
            $(".FrmKaikeiMake.cmdAction").button("enable");
        };
        me.ajax.send(me.actionUrl, data, 0);
    };
    me.NoActionFnc = function () {
        $(".FrmKaikeiMake.cboDateFrom").trigger("focus");
        $(".FrmKaikeiMake.cboDateFrom").select();
        return;
    };
    me.fnc_addrow = function (data) {
        var tmpcnt = $(me.grid_id).jqGrid("getDataIDs");
        rowdata = {
            R_KAMOK_CD: data,
        };
        $(me.grid_id).jqGrid("addRowData", tmpcnt.length, rowdata);
    };
    me.fnc_inputChk = function () {
        //出力条件の日付From>日付Toの場合、エラー
        var cboDateFrom = $(".FrmKaikeiMake.cboDateFrom").val();
        var cboDateTo = $(".FrmKaikeiMake.cboDateTo").val();
        if (cboDateFrom > cboDateTo) {
            $(".FrmKaikeiMake.cboDateFrom").trigger("focus");
            $(".FrmKaikeiMake.cboDateFrom").select();
            me.clsComFnc.FncMsgBox("E9999", "計上日範囲が不正です");
            $(".FrmKaikeiMake.cmdAction").button("enable");
            return false;
        }
        return true;
    };

    /*
	 '**********************************************************************
	 '処 理 名：画面項目ｸﾘｱ
	 '関 数 名：subClearForm
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：画面項目ｸﾘｱ
	 '**********************************************************************
	 */
    me.fnc_subClearForm = function () {
        me.initGrid1();
        $(".FrmKaikeiMake.lblMsg").html("");
        $(".FrmKaikeiMake.lblMsg2").html("");
        $(".FrmKaikeiMake.lblCnt").html("");
        $(me.grid_id).clearGridData();
        $(".FrmKaikeiMake.frame3").css("visibility", "hidden");
        me.initDate(".FrmKaikeiMake.cboDateFrom");
        me.initDate(".FrmKaikeiMake.cboDateTo");
    };

    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：FrmKaikeiMake_load
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：初期処理
	 '**********************************************************************
	 */
    me.FrmKaikeiMake_load = function () {
        me.fnc_subClearForm();
        me.initDate(".FrmKaikeiMake.cboDateTo");
        me.initDate(".FrmKaikeiMake.cboDateFrom");
    };
    //--common--
    me.initDate = function (dateClassName) {
        var myDate = new Date();
        $(dateClassName).datepicker(
            "setDate",
            myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
        );
        $(dateClassName).datepicker(
            "setDate",
            myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
        );
        $(dateClassName).trigger("focus");
        $(dateClassName).select();
    };
    //--base--
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmKaikeiMake_load();
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmKaikeiMake = new R4.FrmKaikeiMake();
    o_R4_FrmKaikeiMake.load();
});
