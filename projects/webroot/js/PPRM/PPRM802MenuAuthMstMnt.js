/**
 * 説明：
 *
 *
 * @author YANGYANG
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * 20201120           bug                          ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM802MenuAuthMstMnt");

PPRM.PPRM802MenuAuthMstMnt = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    me.id = "PPRM802MenuAuthMstMnt";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.rowData = new Array();
    me.strFlg = "";
    me.reload = "";

    //左側のjqGridテーブル
    me.Lgrid_id = "#PPRM802MenuAuthMstMnt_gvRights";
    me.Lg_url = "PPRM/PPRM802MenuAuthMstMnt/getLjqGridData";
    me.Lpager = "";
    me.Lsidx = "";

    me.Loption = {
        rowNum: 9999,
        recordpos: "left",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.LcolModel = [
        {
            name: "PATTERN_ID",
            label: "権限ID",
            index: "PATTERN_ID",
            width: 85,
            sortable: false,
            align: "left",
        },
        {
            name: "PATTERN_NM",
            label: "権限名",
            index: "PATTERN_NM",
            width: 310,
            sortable: false,
            align: "left",
        },
    ];

    //右側のjqGridテーブル
    me.Rgrid_id = "#PPRM802MenuAuthMstMnt_gvProgramInfo";
    me.Rg_url = "PPRM/PPRM802MenuAuthMstMnt/getRjqGridData";
    me.Rpager = "";
    me.Rsidx = "";
    me.Roption = {
        rowNum: 9999,
        recordpos: "left",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.RcolModel = [
        {
            name: "PRO_NO",
            label: "プログラム№",
            index: "PRO_NO",
            sortable: false,
            hidden: true,
        },
        {
            name: "KBN",
            label: "追加",
            index: "KBN",
            width: 50,
            formatter: "checkbox",
            formatoptions: {
                disabled: false,
            },
            sortable: false,
            align: "center",
        },
        {
            name: "PRO_NM",
            label: "プログラム名",
            index: "PRO_NM",
            width: me.ratio === 1.5 ? 290 : 340,
            sortable: false,
            align: "left",
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM802MenuAuthMstMnt.btnSelect",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM802MenuAuthMstMnt.btnAdd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM802MenuAuthMstMnt.btnLogin",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM802MenuAuthMstMnt.btnDelete",
        type: "button",
        handle: "",
    });

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //選択ボタン押下
    $(".PPRM802MenuAuthMstMnt.btnSelect").click(function () {
        me.btnSelect_click();
    });

    //追加ボタン押下
    $(".PPRM802MenuAuthMstMnt.btnAdd").click(function () {
        me.btnAdd_click();
    });

    //登録ボタン押下
    $(".PPRM802MenuAuthMstMnt.btnLogin").click(function () {
        me.btnLogin_confirm();
    });

    //削除ボタン押下
    $(".PPRM802MenuAuthMstMnt.btnDelete").click(function () {
        me.btnDelete_confirm();
    });

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM802MenuAuthMstMnt_load();
    };

    //ページ初期化
    me.PPRM802MenuAuthMstMnt_load = function () {
        //20170907 ZHANGXIAOLEI DEL S
        // $(".PPRM802MenuAuthMstMnt.tblThirdMain").css("display", "none");
        // $(".PPRM802MenuAuthMstMnt.txtRightsID").val("");
        // $(".PPRM802MenuAuthMstMnt.txtRightsName").val("");
        //20170907 ZHANGXIAOLEI DEL E

        //20170907 ZHANGXIAOLEI INS S
        me.Page_Clear(true);
        //20170907 ZHANGXIAOLEI INS E

        var data = {
            //20170907 ZHANGXIAOLEI DEL S
            // 'aaa' : 'aaa'
            //20170907 ZHANGXIAOLEI DEL E
        };

        gdmz.common.jqgrid.showWithMesg(
            me.Lgrid_id,
            me.Lg_url,
            me.LcolModel,
            me.Lpager,
            me.Lsidx,
            me.Loption,
            data,
            me.Lcomplete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.Lgrid_id, 468);
        gdmz.common.jqgrid.set_grid_height(me.Lgrid_id, 260);
    };

    //'***********************************************************************
    //'処 理 名：左側のjqGridテーブルを表示する
    //'関 数 名：me.setLjqGridData
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：左側のjqGridテーブルを表示する
    //'***********************************************************************
    me.setLjqGridData = function () {
        var data = {
            //20170907 ZHANGXIAOLEI DEL S
            // 'aaa' : 'aaa'
            //20170907 ZHANGXIAOLEI DEL E
        };

        gdmz.common.jqgrid.reloadMessage(
            me.Lgrid_id,
            data,
            me.Lcomplete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.Lgrid_id, 468);
        gdmz.common.jqgrid.set_grid_height(me.Lgrid_id, 260);
    };

    me.Lcomplete_fun = function (bErrorFlag) {
        //20170907 ZHANGXIAOLEI INS S
        me.Page_Clear(true);
        //20170907 ZHANGXIAOLEI INS E

        if (bErrorFlag != "nodata") {
            $(".PPRM802MenuAuthMstMnt.btnSelect").trigger("focus");

            //20170907 ZHANGXIAOLEI INS S
            $(me.Lgrid_id).jqGrid("setGridParam", {
                onSelectRow: function (_rowid, status) {
                    if (status) {
                        me.Page_Clear(true);
                    }
                },
            });
            //20170907 ZHANGXIAOLEI INS E
        } else {
            $(".PPRM802MenuAuthMstMnt.btnAdd").trigger("focus");
            //20170907 ZHANGXIAOLEI DEL S
            // clsComFnc.FncMsgBox("W0003_PPRM");
            // return;
            //20170907 ZHANGXIAOLEI DEL E
        }
    };

    //'***********************************************************************
    //'処 理 名：選択ボタン押下
    //'関 数 名：me.btnSelect_click
    //'引 数   ：なし
    //'戻 り 値：なし
    //'処理説明：選択ボタン押下
    //'***********************************************************************
    me.btnSelect_click = function () {
        var id = $(me.Lgrid_id).jqGrid("getGridParam", "selrow");
        me.rowData = $(me.Lgrid_id).jqGrid("getRowData", id);

        if (id == null || id == undefined) {
            //20170911 CI INS S
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            return;
            //20170911 CI INS E
        } else {
            PTNID = me.rowData["PATTERN_ID"];
            me.gvRights_SelectedIndexChanged(PTNID);
        }
    };

    //'***********************************************************************
    //'処 理 名：選択の場合、右側のjqGridテーブルを表示する
    //'関 数 名：me.gvRights_SelectedIndexChanged
    //'引 数   ：PTNID
    //'戻 り 値：なし
    //'処理説明：選択の場合、右側のjqGridテーブルを表示する
    //'***********************************************************************
    me.gvRights_SelectedIndexChanged = function (PTNID) {
        me.strFlg = "1";

        var data = {
            PTNID: PTNID,
            strFlg: "1",
        };

        if (me.reload == "") {
            gdmz.common.jqgrid.showWithMesg(
                me.Rgrid_id,
                me.Rg_url,
                me.RcolModel,
                me.Rpager,
                me.Rsidx,
                me.Roption,
                data,
                me.Rcomplete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.Rgrid_id, me.ratio === 1.5 ? 400 : 460);
            gdmz.common.jqgrid.set_grid_height(me.Rgrid_id, 285);
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.Rgrid_id,
                data,
                me.Rcomplete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.Rgrid_id, me.ratio === 1.5 ? 400 : 460);
            gdmz.common.jqgrid.set_grid_height(me.Rgrid_id, 285);
        }
    };

    me.Rcomplete_fun = function (bErrorFlag) {
        me.reload = "1";

        if (bErrorFlag != "nodata") {
            if (me.strFlg == "1") {
                //選択の場合
                $(".PPRM802MenuAuthMstMnt.tblThirdMain").css(
                    "display",
                    "block"
                );

                $(".PPRM802MenuAuthMstMnt.txtRightsID").val(
                    me.rowData["PATTERN_ID"]
                );
                $(".PPRM802MenuAuthMstMnt.txtRightsName").val(
                    me.rowData["PATTERN_NM"]
                );

                $(".PPRM802MenuAuthMstMnt.txtRightsID").prop("disabled", true);
                $(".PPRM802MenuAuthMstMnt.txtRightsName").prop(
                    "disabled",
                    false
                );
                //20201120 WL UPD S
                //$(".PPRM802MenuAuthMstMnt.btnLogin").prop("disabled", false);
                //$(".PPRM802MenuAuthMstMnt.btnDelete").prop("disabled", false);
                $(".PPRM802MenuAuthMstMnt.btnLogin").button("enable");
                $(".PPRM802MenuAuthMstMnt.btnDelete").button("enable");
                //20201120 WL UPD E
            } else {
                //追加の場合
                $(".PPRM802MenuAuthMstMnt.tblThirdMain").css(
                    "display",
                    "block"
                );

                $(".PPRM802MenuAuthMstMnt.txtRightsID").val("");
                $(".PPRM802MenuAuthMstMnt.txtRightsName").val("");

                $(".PPRM802MenuAuthMstMnt.txtRightsID").prop("disabled", false);
                $(".PPRM802MenuAuthMstMnt.txtRightsName").prop(
                    "disabled",
                    false
                );
                //20201120 WL UPD S
                //$(".PPRM802MenuAuthMstMnt.btnDelete").prop("disabled", true);
                $(".PPRM802MenuAuthMstMnt.btnDelete").button("disable");
                //20201120 WL UPD E
            }
        }
        //20170907 ZHANGXIAOLEI DEL S
        // else
        // {
        // clsComFnc.FncMsgBox("W0003_PPRM");
        // return;
        // }
        //20170907 ZHANGXIAOLEI DEL E
    };

    //'**********************************************************************
    //'処 理 名：追加ボタンのイベント
    //'関 数 名：me.btnAdd_click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：ユーザーの権限を追加する
    //'**********************************************************************
    me.btnAdd_click = function () {
        me.strFlg = "0";

        var data = {
            strFlg: "0",
        };

        if (me.reload == "") {
            gdmz.common.jqgrid.showWithMesg(
                me.Rgrid_id,
                me.Rg_url,
                me.RcolModel,
                me.Rpager,
                me.Rsidx,
                me.Roption,
                data,
                me.Rcomplete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.Rgrid_id, me.ratio === 1.5 ? 400 : 460);
            gdmz.common.jqgrid.set_grid_height(me.Rgrid_id, 285);
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.Rgrid_id,
                data,
                me.Rcomplete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.Rgrid_id, me.ratio === 1.5 ? 400 : 460);
            gdmz.common.jqgrid.set_grid_height(me.Rgrid_id, 285);
        }
    };

    //'**********************************************************************
    //'処 理 名：登録の確認
    //'関 数 名：me.btnLogin_confirm
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：登録の確認
    //'**********************************************************************
    me.btnLogin_confirm = function () {
        //20170907 ZHANGXIAOLEI INS S
        if (me.Login_check()) {
            //20170907 ZHANGXIAOLEI INS E
            //20170907 ZHANGXIAOLEI UPD S
            // clsComFnc.MsgBoxBtnFnc.Yes = me.Login_check;
            clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_click;
            //20170907 ZHANGXIAOLEI UPD E
            clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
            //20170907 ZHANGXIAOLEI INS S
        }
        //20170907 ZHANGXIAOLEI INS E
    };

    //'**********************************************************************
    //'処 理 名：登録のチェック
    //'関 数 名：me.Login_check
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：登録のチェック
    //'**********************************************************************
    me.Login_check = function () {
        var txtRightsID = $(".PPRM802MenuAuthMstMnt.txtRightsID").val();
        var txtRightsName = $(".PPRM802MenuAuthMstMnt.txtRightsName").val();

        //権限ID未入力の場合、エラー
        if (txtRightsID == "") {
            $(".PPRM802MenuAuthMstMnt.txtRightsID").trigger("focus");
            clsComFnc.FncMsgBox("E0001_PPRM", "権限ID");
            //20170907 ZHANGXIAOLEI UPD S
            return false;
            //20170907 ZHANGXIAOLEI UPD E
        }

        //権限名未入力の場合、エラー
        if (txtRightsName == "") {
            $(".PPRM802MenuAuthMstMnt.txtRightsName").trigger("focus");
            clsComFnc.FncMsgBox("E0001_PPRM", "権限名");
            //20170907 ZHANGXIAOLEI UPD S
            return false;
            //20170907 ZHANGXIAOLEI UPD E
        }

        //権限IDの桁数を超える場合は、エラー
        //20170907 ZHANGXIAOLEI UPD S
        // if (me.trim(txtRightsID).length > 3)
        if (clsComFnc.GetByteCount(me.trim(txtRightsID)) > 3) {
            //20170907 ZHANGXIAOLEI UPD E
            $(".PPRM802MenuAuthMstMnt.txtRightsID").trigger("focus");
            clsComFnc.FncMsgBox(
                "E0011_PPRM",
                "権限IDの桁数は指定されている桁数をオーバーしています。"
            );
            //20170907 ZHANGXIAOLEI UPD S
            return false;
            //20170907 ZHANGXIAOLEI UPD E
        }

        //権限名の桁数を超える場合は、エラー
        //20170907 ZHANGXIAOLEI UPD S
        //if (me.trim(txtRightsName).length > 50)
        if (clsComFnc.GetByteCount(me.trim(txtRightsName)) > 50) {
            //20170907 ZHANGXIAOLEI UPD E
            $(".PPRM802MenuAuthMstMnt.txtRightsName").trigger("focus");
            clsComFnc.FncMsgBox(
                "E0011_PPRM",
                "権限名の桁数は指定されている桁数をオーバーしています。"
            );
            //20170907 ZHANGXIAOLEI UPD S
            return false;
            //20170907 ZHANGXIAOLEI UPD E
        }

        //20170907 ZHANGXIAOLEI UPD S
        //me.btnLogin_click();
        return true;
        //20170907 ZHANGXIAOLEI UPD E
    };

    //'**********************************************************************
    //'処 理 名：登録処理
    //'関 数 名：me.btnLogin_click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：登録処理
    //'**********************************************************************
    me.btnLogin_click = function () {
        var url = me.sys_id + "/" + me.id + "/btnLoginClick";

        var txtRightsID = $(".PPRM802MenuAuthMstMnt.txtRightsID").val();
        var txtRightsName = $(".PPRM802MenuAuthMstMnt.txtRightsName").val();
        var txtRightsIDEnabled = $(".PPRM802MenuAuthMstMnt.txtRightsID").prop(
            "disabled"
        );
        //20170907 ZHANGXIAOLEI DEL S
        // if (txtRightsIDDisabled == true)
        // {
        // txtRightsIDEnabled = false;
        // }
        // else
        // {
        // txtRightsIDEnabled = true;
        // }
        //20170907 ZHANGXIAOLEI DEL E
        var ids = $(me.Rgrid_id).jqGrid("getDataIDs");
        var arr = new Array();

        for (i = 0; i < ids.length; i++) {
            var id = ids[i];
            var rowData = $(me.Rgrid_id).jqGrid("getRowData", id);

            if (rowData["KBN"] == "Yes") {
                arr.push(rowData);
            }
        }

        var data = {
            txtRightsID: txtRightsID,
            txtRightsName: txtRightsName,
            //20170907 ZHANGXIAOLEI UPD S
            // txtRightsIDEnabled : txtRightsIDEnabled,
            txtRightsIDEnabled: !txtRightsIDEnabled,
            //20170907 ZHANGXIAOLEI UPD E
            arr: arr,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                if (
                    result["result1"] != null ||
                    result["result1"] != undefined
                ) {
                    if (result["result1"] == "E0005_PPRM") {
                        $(".PPRM802MenuAuthMstMnt.txtRightsID").trigger(
                            "focus"
                        );
                        clsComFnc.FncMsgBox("E0005_PPRM");
                    } else if (result["result1"] == "W0004_PPRM") {
                        clsComFnc.FncMsgBox("W0004_PPRM");
                    }
                }

                if (result["result2"] == true) {
                    me.Page_Clear();
                    clsComFnc.FncMsgBox("I0002_PPRM");
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：削除の確認
    //'関 数 名：me.btnDelete_confirm
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：削除の確認
    //'**********************************************************************
    me.btnDelete_confirm = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_click;
        clsComFnc.FncMsgBox("QY014_PPRM", "選択されたパターンの権限情報");
    };

    //'**********************************************************************
    //'処 理 名：削除処理
    //'関 数 名：me.btnDelete_click
    //'引 数 １：なし
    //'戻 り 値：なし
    //'処理説明：削除処理
    //'**********************************************************************
    me.btnDelete_click = function () {
        var txtRightsID = $(".PPRM802MenuAuthMstMnt.txtRightsID").val();

        var url = me.sys_id + "/" + me.id + "/btnDeleteClick";
        var data = {
            txtRightsID: txtRightsID,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                me.Page_Clear();
                clsComFnc.FncMsgBox("I0003_PPRM");
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：当ページを初期化する
    //'関 数 名：me.Page_Clear
    //'引 数 １：flgReload: gvRightsリロード以外時、「true」をセットする
    //'戻 り 値：なし
    //'処理説明：当ページを初期の状態にセットする
    //'**********************************************************************
    me.Page_Clear = function (flgReload) {
        $(".PPRM802MenuAuthMstMnt.tblThirdMain").css("display", "none");
        $(".PPRM802MenuAuthMstMnt.txtRightsID").val("");
        $(".PPRM802MenuAuthMstMnt.txtRightsName").val("");

        //20170907 ZHANGXIAOLEI INS S
        if (flgReload === undefined) {
            //20170907 ZHANGXIAOLEI INS E
            me.setLjqGridData();
            //20170907 ZHANGXIAOLEI INS S
        }
        //20170907 ZHANGXIAOLEI INS E
    };

    me.trim = function (str) {
        return str.replace(/(^\s*)|(\s*$)/g, "");
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM802MenuAuthMstMnt = new PPRM.PPRM802MenuAuthMstMnt();
    o_PPRM_PPRM802MenuAuthMstMnt.load();
    o_PPRM_PPRM.PPRM802MenuAuthMstMnt = o_PPRM_PPRM802MenuAuthMstMnt;
});
