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
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM803MenuNameMstMnt");

PPRM.PPRM803MenuNameMstMnt = function () {
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

    me.id = "PPRM803MenuNameMstMnt";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.grid_id = "#PPRM803MenuNameMstMnt_jqGrid";
    me.g_url = "PPRM/PPRM803MenuNameMstMnt/fncGetSqlHPROGRAMMST";
    me.pager = "";
    me.sidx = "";

    me.option = {
        rowNum: 9999,
        recordpos: "left",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 30,
        scroll: 1,
    };
    me.colModel = [
        {
            name: "NO",
            label: "NO",
            index: "NO",
            sortable: false,
            hidden: true,
        },
        {
            name: "PRO_NM",
            label: "メニュー名",
            index: "PRO_NM",
            width: 360,
            sortable: false,
            align: "left",
        },
        {
            name: "USER_AUTH_CTL_FLG",
            label: "USER_AUTH_CTL_FLG",
            index: "USER_AUTH_CTL_FLG",
            sortable: false,
            hidden: true,
        },
        {
            name: "USER_AUTH_CTL_NM",
            label: "部署やボタンごとに権限管理する",
            index: "USER_AUTH_CTL_NM",
            width: 220,
            sortable: false,
            align: "left",
        },
        {
            name: "PRO_NO",
            label: "PRO_NO",
            index: "PRO_NO",
            sortable: false,
            hidden: true,
        },
        {
            name: "UPD_DATE",
            label: "更新日",
            index: "UPD_DATE",
            sortable: false,
            hidden: true,
        },
        {
            name: "USES_COUNT",
            label: "USES_COUNT",
            index: "USES_COUNT",
            sortable: false,
            hidden: true,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".PPRM803MenuNameMstMnt.btnEdit",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM803MenuNameMstMnt.btnUpd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM803MenuNameMstMnt.btnCan",
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

    $(".PPRM803MenuNameMstMnt.btnEdit").click(function () {
        me.btnEdit_Click();
    });

    //更新ボタン押下
    $(".PPRM803MenuNameMstMnt.btnUpd").click(function () {
        me.btnUpd_Click();
    });

    //ｷｬﾝｾﾙボタン押下
    $(".PPRM803MenuNameMstMnt.btnCan").click(function () {
        me.btnCan_Click();
    });

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM803MenuNameMstMnt_load();
    };

    //ページ初期化
    me.PPRM803MenuNameMstMnt_load = function () {
        //フォーカスを設定
        $(".PPRM803MenuNameMstMnt.btnEdit").trigger("focus");

        //プログラムマスタを表示する
        var data = {
            aaa: "aaa",
        };

        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            data,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 652);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 225 : 288);
        // 2017/09/07 CI INS S
        $("#jqgh_PPRM803MenuNameMstMnt_jqGrid_rn").html("No.");
        // 2017/09/07 CI INS E
    };

    //'***********************************************************************
    //'処 理 名：メニュー名称マスタメンテナンス表示
    //'関 数 名：me.FncInitDisp
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：プログラムマスタからメニュー名称マスタメンテナンス画面へ表示
    //'***********************************************************************/
    me.FncInitDisp = function () {
        var data = {
            aaa: "aaa",
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 652);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 225 : 288);
    };

    me.complete_fun = function (bErrorFlag) {
        //プログラムマスタの件数　<=0　の場合
        if (bErrorFlag == "nodata") {
            clsComFnc.FncMsgBox("W0003_PPRM");
            return;
        }
    };

    //'***********************************************************************
    //'処 理 名：修正ボタン押下
    //'関 数 名：me.btnEdit_Click
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：
    //'***********************************************************************/
    me.btnEdit_Click = function () {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", id);

        if (id == null || id == undefined) {
            //20170911 CI INS S
            clsComFnc.FncMsgBox("E0015_PPRM", "表から行");
            return;
            //20170911 CI INS E
        } else {
            $(".PPRM803MenuNameMstMnt.footer").css("display", "block");
            //20170906 CI INS S
            $(".PPRM803MenuNameMstMnt.txtProName").css("backgroundColor", "");
            //20170906 CI INS E

            //PRO_NO
            $(".PPRM803MenuNameMstMnt.lblProNO").text(rowData["PRO_NO"]);
            //メニュー名
            $(".PPRM803MenuNameMstMnt.txtProName").val(rowData["PRO_NM"]);
            //更新日
            $(".PPRM803MenuNameMstMnt.lblUpdDate").text(rowData["UPD_DATE"]);

            //権限を管理する
            switch (rowData["USER_AUTH_CTL_FLG"]) {
                case "":
                    $(
                        ".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg option[value='']"
                    ).prop("selected", true);
                    $(".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg").prop(
                        "disabled",
                        true
                    );
                    break;
                case "0":
                    $(
                        ".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg option[value='0']"
                    ).prop("selected", true);
                    $(".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg").prop(
                        "disabled",
                        false
                    );
                    break;
                case "1":
                    $(
                        ".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg option[value='1']"
                    ).prop("selected", true);
                    $(".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg").prop(
                        "disabled",
                        false
                    );
                    break;
                default:
                    break;
            }
        }
    };

    //'***********************************************************************
    //'処 理 名：更新ボタン押下
    //'関 数 名：me.btnUpd_Click
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：
    //'***********************************************************************/
    me.btnUpd_Click = function () {
        //メニュー名の入力チェック
        if (me.InputCheck() == false) {
            return;
        }

        //プログラムマスタ存在チェック
        me.FncCheckSQL();
    };

    //'***********************************************************************
    //'処 理 名：ｷｬﾝｾﾙボタン押下
    //'関 数 名：me.btnCan_Click
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：
    //'***********************************************************************/
    me.btnCan_Click = function () {
        $(".PPRM803MenuNameMstMnt.lblProNO").text("");
        $(".PPRM803MenuNameMstMnt.txtProName").val("");
        $(".PPRM803MenuNameMstMnt.lblUpdDate").text("");
        $(".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg option[value='']").prop(
            "selected",
            true
        );
        $(".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg").prop("disabled", false);
        $(".PPRM803MenuNameMstMnt.footer").css("display", "none");

        //再表示
        me.FncInitDisp();
    };

    //'***********************************************************************
    //'処 理 名：メニュー名の入力チェック
    //'関 数 名：me.InputCheck
    //'引 数 1 ：なし
    //'戻 り 値：Boolean
    //'処理説明：入力内容のチェックを行う
    //'***********************************************************************/
    me.InputCheck = function () {
        var txtProName = $(".PPRM803MenuNameMstMnt.txtProName");

        intRtnCD = clsComFnc.FncTextCheck(
            txtProName,
            1,
            clsComFnc.INPUTTYPE.CHAR3,
            50
        );

        switch (intRtnCD) {
            case -1:
                //必須エラー
                $(".PPRM803MenuNameMstMnt.txtProName").trigger("focus");
                clsComFnc.FncMsgBox("E0001_PPRM", "メニュー名");
                return false;

            case -2:
                //不正文字エラー
                $(".PPRM803MenuNameMstMnt.txtProName").trigger("focus");
                clsComFnc.FncMsgBox("E0003_PPRM", "メニュー名");
                return false;

            case -3:
                //桁数エラー
                $(".PPRM803MenuNameMstMnt.txtProName").trigger("focus");
                clsComFnc.FncMsgBox("E0013_PPRM", "メニュー名", "50");
                return false;
        }
    };

    //'***********************************************************************
    //'処 理 名：プログラムマスタ存在チェック
    //'関 数 名：me.FncCheckSQL
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：更新ボタン押下行の存在チェック処理
    //'***********************************************************************/
    me.FncCheckSQL = function () {
        var lblProNO = $(".PPRM803MenuNameMstMnt.lblProNO").text();

        var url = me.sys_id + "/" + me.id + "/fncCheckSQL";
        var data = {
            lblProNO: lblProNO,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            //更新日
            var lblUpdDate = $(".PPRM803MenuNameMstMnt.lblUpdDate").text();
            var rtnUpdDate = "";

            if (result["result"] == true) {
                if (result["row"] > 0) {
                    //最終更新日
                    rtnUpdDate = result["data"][0]["UPD_DATE"];

                    //更新日付チェック
                    if (lblUpdDate != rtnUpdDate) {
                        clsComFnc.FncMsgBox("W0004_PPRM");
                        return;
                    }

                    //プログラムマスタ更新処理
                    me.FncUpdate_HPROGRAMMST();
                } else {
                    clsComFnc.FncMsgBox("W0005_PPRM");
                    return;
                }
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    //'***********************************************************************
    //'処 理 名：プログラムマスタ更新処理
    //'関 数 名：me.FncUpdate_HPROGRAMMST
    //'引 数 1 ：なし
    //'戻 り 値：なし
    //'処理説明：プログラムマスタを更新する
    //'***********************************************************************/
    me.FncUpdate_HPROGRAMMST = function () {
        var lblProNO = $(".PPRM803MenuNameMstMnt.lblProNO").text();
        var txtProName = $(".PPRM803MenuNameMstMnt.txtProName").val();
        var ddlUserAuthCtlFlg = $(
            ".PPRM803MenuNameMstMnt.ddlUserAuthCtlFlg option:selected"
        ).val();

        var url = me.sys_id + "/" + me.id + "/fncUpdateHPROGRAMMST";
        var data = {
            lblProNO: lblProNO,
            txtProName: txtProName,
            ddlUserAuthCtlFlg: ddlUserAuthCtlFlg,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);

            if (result["result"] == true) {
                clsComFnc.FncMsgBox("I0001_PPRM");

                //再表示
                me.FncInitDisp();
                $(".PPRM803MenuNameMstMnt.footer").css("display", "none");
                return;
            } else {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM803MenuNameMstMnt = new PPRM.PPRM803MenuNameMstMnt();
    o_PPRM_PPRM803MenuNameMstMnt.load();
    o_PPRM_PPRM.PPRM803MenuNameMstMnt = o_PPRM_PPRM803MenuNameMstMnt;
});
