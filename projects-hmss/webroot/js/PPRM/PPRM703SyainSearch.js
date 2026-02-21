/**
 * 説明：
 *
 *
 * @author CIYUANCHEN
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           GSDL
 * 20201120           bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる            WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM703SyainSearch");

PPRM.PPRM703SyainSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ajax = new gdmz.common.ajax();
    var ODR = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========
    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    me.id = "PPRM703SyainSearch";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.strProgramID = "";
    me.strTenpoKB = "";

    //20170908 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170908 ZHANGXIAOLEI INS E

    //jqgrid
    {
        me.grid_id = "#PPRM703SyainSearch_gdvShainnBetuKG";
        me.g_url = "PPRM/PPRM703SyainSearch/btnHyoujiClick";
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
                name: "SYAIN_NO",
                label: "社員№",
                index: "SYAIN_NO",
                //20171201 lqs INS S
                sortable: false,
                //20171201 lqs INS E
                width: 113,
            }, //
            {
                name: "SYAIN_NM",
                label: "社員名",
                index: "SYAIN_NM",
                //20171201 lqs INS S
                sortable: false,
                //20171201 lqs INS E
                width: 275,
            }, //
        ];
    }

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //選択ボタン
    me.controls.push({
        id: ".PPRM703SyainSearch.btnSenntaku",
        type: "button",
        handle: "",
    });
    //戻るボタン
    me.controls.push({
        id: ".PPRM703SyainSearch.btnModoru",
        type: "button",
        handle: "",
    });
    //表示ボタン
    me.controls.push({
        id: ".PPRM703SyainSearch.btnHyouji",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".PPRM703SyainSearch.btnSearch",
        type: "button",
        handle: "",
    });
    //'処理説明：ページ初期化
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //20170908 ZHANGXIAOLEI UPD S
        // me.PPRM703SyainSearch_load();
        me.getAllBusyoNM();
        //20170908 ZHANGXIAOLEI UPD E
    };

    //20170905 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部の店舗コードと店舗名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の店舗コードと店舗名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetALLBusyoNM";
        var selectObj = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                me.BusyoArr = result["data"];
            }
            me.PPRM703SyainSearch_load();
        };
        ajax.send(url, selectObj, 0);
    };

    me.FncGetBusyoNM = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }
            for (key in me.BusyoArr) {
                if (strCD == me.BusyoArr[key]["BUSYO_CD"]) {
                    return me.BusyoArr[key]["BUSYO_NM"];
                }
            }
        } catch (e) {
            return "";
        }
    };
    //20170905 ZHANGXIAOLEI INS E

    me.before_close = function () {};
    me.PPRM703SyainSearch_load = function () {
        $(".PPRM703SyainSearch.body").dialog({
            autoOpen: false,
            width: 500,
            height: me.ratio === 1.5 ? 525 : 650,
            modal: true,
            title: "社員番号検索",
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM703SyainSearch.body").remove();
            },
        });

        $(".PPRM703SyainSearch.body").dialog("open");
        $(".PPRM703SyainSearch.btnSenntaku").css("visibility", "hidden");

        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 460);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 210 : 285);

        $("#jqgh_PPRM703SyainSearch_gdvShainnBetuKG_rn").html("No");
        //20201120 WL DEL S
        //20170913 YIN INS S
        // $('.ui-jqgrid-labels').block(
        // {
        // "overlayCSS" :
        // {
        // opacity : 0,
        // }
        // });
        // //20170913 YIN INS E
        //20201120 WL DEL E
    };

    //選択ボタン押下
    $(".PPRM703SyainSearch.btnSenntaku").click(function () {
        me.windowClose();
    });
    //戻るボタン押下
    $(".PPRM703SyainSearch.btnModoru").click(function () {
        me.windowClose2();
    });
    //表示ボタン押下
    $(".PPRM703SyainSearch.btnHyouji").click(function () {
        me.btnHyouji_Click();
    });
    //部署コード検索ボタン
    $(".PPRM703SyainSearch.btnSearch").click(function () {
        openFromTenpoSearch();
    });

    $(".PPRM703SyainSearch.txtShainnNo").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM703SyainSearch.txtShainnNo").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM703SyainSearch.txtShainnNM").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM703SyainSearch.txtShainnNM").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM703SyainSearch.txtShainnNM_Kana").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM703SyainSearch.txtShainnNM_Kana").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM703SyainSearch.txtBusyo").on("blur", function () {
        me.txtBusyoBlur();
    });
    // //'**********************************************************************
    // //'処 理 名：表示ボタンクリックのイベント
    // //'関 数 名：btnHyouji_Click
    // //'引 数 １：(I)sender イベントソース
    // //'引 数 ２：(I)e      イベントパラメータ
    // //'戻 り 値：なし
    // //'処理説明：表示ボタンの処理
    // //'**********************************************************************
    me.btnHyouji_Click = function () {
        var txtShainnNo = $(".PPRM703SyainSearch.txtShainnNo").val();
        var txtShainnNM = $(".PPRM703SyainSearch.txtShainnNM").val();
        // 2017/09/22 CI UPD S
        //var txtShainnNMKana = $(".PPRM703SyainSearch.txtShainnNMKana").val();
        var txtShainnNM_Kana = $(".PPRM703SyainSearch.txtShainnNM_Kana").val();
        // 2017/09/22 CI UPD E
        var txtBusyo = $(".PPRM703SyainSearch.txtBusyo").val();

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "nodata") {
                $(".PPRM703SyainSearch.txtShainnNo").trigger("focus");
                clsComFnc.FncMsgBox("W0003_PPRM");
                $(".PPRM703SyainSearch.btnSenntaku").css(
                    "visibility",
                    "hidden"
                );
                return;
            } else {
            }
        };

        var data = {
            txtShainnNo: txtShainnNo,
            txtShainnNM: txtShainnNM,
            // 2017/09/22 CI UPD S
            //txtShainnNMKana : txtShainnNMKana,
            txtShainnNM_Kana: txtShainnNM_Kana,
            // 2017/09/22 CI UPD S
            txtBusyo: txtBusyo,
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 460);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 210 : 285);
        $(".PPRM703SyainSearch.btnSenntaku").css("visibility", "visible");
    };
    // //'**********************************************************************
    // //'処 理 名：社員グリッド行選択のイベント
    // //'関 数 名：windowClose
    // //'引 数 １：(I)sender イベントソース
    // //'引 数 ２：(I)e      イベントパラメータ
    // //'戻 り 値：なし
    // //'処理説明：社員グリッド行選択の処理
    // //'**********************************************************************
    me.windowClose = function () {
        var id = $("#PPRM703SyainSearch_gdvShainnBetuKG").jqGrid(
            "getGridParam",
            "selrow"
        );
        if (id == null) {
            clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
        } else {
            var rowData = $("#PPRM703SyainSearch_gdvShainnBetuKG").jqGrid(
                "getRowData",
                id
            );

            if ($.trim(rowData["SYAIN_NO"]) != "") {
                me.syainCD = rowData["SYAIN_NO"];
                me.syainNM = rowData["SYAIN_NM"];
            }
            me.flg = 1;
            $(".PPRM703SyainSearch.body").dialog("close");
        }
    };

    me.windowClose2 = function () {
        me.flg = 2;
        $(".PPRM703SyainSearch.body").dialog("close");
    };

    //テキストエリアを全選択する
    function TextAreaSelect(obj) {
        obj.select();
    }

    //'**********************************************************************
    //'処 理 名：【部署コード】のblur
    //'関 数 名：me.txtBusyoBlur
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.txtBusyoBlur = function () {
        //20170908 ZHANGXIAOLEI UPD S
        /*
		 var url = me.sys_id + "/" + me.id + "/" + "FncGetBusyoNM";

		 var arr =
		 {
		 "txtBusyo" : $(".PPRM703SyainSearch.txtBusyo").val(),
		 };

		 var data =
		 {
		 request : arr
		 };

		 ajax.receive = function(result)
		 {

		 result = eval("(" + result + ")");

		 if (result["records"] <= 0)
		 {
		 $(".PPRM703SyainSearch.lblBusyo").val("");
		 }
		 else
		 {
		 $(".PPRM703SyainSearch.lblBusyo").val(result["rows"][0]["cell"]["BUSYO_NM"]);
		 }
		 };

		 ajax.send(url, data, 0);
		 */
        $(".PPRM703SyainSearch.lblBusyo").val(
            me.FncGetBusyoNM($(".PPRM703SyainSearch.txtBusyo").val())
        );
        //20170908 ZHANGXIAOLEI UPD E
    };
    function openFromTenpoSearch() {
        // me.TKB = "1";
        me.url = "PPRM/PPRM702BusyoSearch";

        //20171010 lqs DEL S
        //保存
        // localStorage.setItem('requestdata', JSON.stringify(
        // {
        // 'TKB' : me.TKB
        // }));
        //20171010 lqs DEL E

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM702BusyoSearch.flg == 1) {
                    //Else
                    var busyocd = o_PPRM_PPRM.PPRM702BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM702BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM703SyainSearch.txtBusyo").val(busyocd);
                    } else {
                        $(".PPRM703SyainSearch.txtBusyo").val("");
                    }
                    if (busyonm != "") {
                        $(".PPRM703SyainSearch.lblBusyo").val(busyonm);
                    } else {
                        $(".PPRM703SyainSearch.lblBusyo").val("");
                    }
                }
            }
            //20171011 lqs UPD S
            //$("." + me.id + "." + "dialogs").append(result);
            $("#PPRM703SyainSearch_dialogs").append(result);
            //20171011 lqs UPD E

            o_PPRM_PPRM.PPRM702BusyoSearch.before_close = before_close;
        };
        ajax.send(me.url, me.data, 0);
    }

    return me;
};

$(function () {
    var o_PPRM_PPRM703SyainSearch = new PPRM.PPRM703SyainSearch();
    o_PPRM_PPRM703SyainSearch.load();
    o_PPRM_PPRM.PPRM703SyainSearch = o_PPRM_PPRM703SyainSearch;
});
