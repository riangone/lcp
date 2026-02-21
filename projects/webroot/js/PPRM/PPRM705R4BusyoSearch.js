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
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20201120           bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる              WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM705R4BusyoSearch");

PPRM.PPRM705R4BusyoSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    var ODR = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    me.id = "PPRM705R4BusyoSearch";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.strProgramID = "";
    me.strTenpoKB = "";
    me.title = "";
    //查询
    // var localStorage = window.localStorage;
    // var requestdata = JSON.parse(localStorage.getItem("requestdata"));
    // if (requestdata) {
    //     me.strTenpoKB = requestdata["TKB"];
    // }

    // if (me.strTenpoKB == "1") {
    me.title = "店舗コード検索";
    // } else {
    //     me.title = "部署コード検索";
    // }
    localStorage.removeItem("requestdata");
    //jqgrid
    {
        me.grid_id = "#PPRM705R4BusyoSearch_gvInfo5";
        me.g_url = "PPRM/PPRM705R4BusyoSearch/btnViewClick";
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
                name: "BUSYO_CD",
                label: "コード",
                index: "BUSYO_CD",
                //20171201 lqs INS S
                sortable: false,
                //20171201 lqs INS E
                width: 109,
            },
            {
                name: "BUSYO_NM",
                label: "名称",
                index: "BUSYO_NM",
                //20171201 lqs INS S
                sortable: false,
                //20171201 lqs INS E
                width: 280,
            },
        ];
    }

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //選択ボタン
    me.controls.push({
        id: ".PPRM705R4BusyoSearch.btnSelect",
        type: "button",
        handle: "",
    });
    //戻るボタン
    me.controls.push({
        id: ".PPRM705R4BusyoSearch.btnClose",
        type: "button",
        handle: "",
    });
    //表示ボタン
    me.controls.push({
        id: ".PPRM705R4BusyoSearch.btnView",
        type: "button",
        handle: "",
    });
    //'処理説明：ページ初期化
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        me.PPRM705R4BusyoSearch_load();
        // 20170922 lqs INS S
        //Enterキーのバインド
        me.EnterKeyDown();
        clsComFnc.TabKeyDown();
        // 20170922 lqs INS E
    };
    me.before_close = function () {};
    me.EnterKeyDown = function () {
        var $inp = $(".Enter705");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();
                    var nxtIdx = $inp.index(this);
                    for (var i = nxtIdx; i < $inp.length; i++) {
                        if (i != $inp.length - 1) {
                            if (
                                $(".Enter705:eq(" + (i + 1) + ")").prop(
                                    "disabled"
                                ) != true
                            ) {
                                $(".Enter705:eq(" + (i + 1) + ")").trigger(
                                    "focus"
                                );
                                $(".Enter705:eq(" + (i + 1) + ")").select();
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        } else {
                            for (var j = 0; j < $inp.length; j++) {
                                if (
                                    $(".Enter705:eq(" + j + ")").prop(
                                        "disabled"
                                    ) != true
                                ) {
                                    $(".Enter705:eq(" + j + ")").trigger(
                                        "focus"
                                    );
                                    $(".Enter705:eq(" + j + ")").select();
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                            }
                        }
                    }
                }
            }
        });
    };
    me.PPRM705R4BusyoSearch_load = function () {
        $(".PPRM705R4BusyoSearch.body").dialog({
            autoOpen: false,
            width: 500,
            height: me.ratio === 1.5 ? 525 : 660,
            modal: true,
            title: me.title,
            open: function () {},
            close: function () {
                me.before_close();
                $(".PPRM705R4BusyoSearch.body").remove();
            },
        });

        try {
            {
                // if (me.strTenpoKB == "1") {
                $(".PPRM705R4BusyoSearch.lblBusyoCDLabelNM").text("店舗コード");
                $(".PPRM705R4BusyoSearch.lblBusyoKanaLabelName").text(
                    "店舗名ｶﾅ"
                );
                $(".PPRM705R4BusyoSearch.lblBusyoRKNLabelNM").text(
                    "店舗略称名"
                );
                // } else {
                //     $(".PPRM705R4BusyoSearch.lblBusyoCDLabelNM").text(
                //         "部署コード"
                //     );
                //     $(".PPRM705R4BusyoSearch.lblBusyoKanaLabelName").text(
                //         "部署名ｶﾅ"
                //     );
                //     $(".PPRM705R4BusyoSearch.lblBusyoRKNLabelNM").text(
                //         "部署略称名"
                //     );
                // }

                $(".PPRM705R4BusyoSearch.body").dialog("open");
                $(".PPRM705R4BusyoSearch.btnSelect").css(
                    "visibility",
                    "hidden"
                );

                gdmz.common.jqgrid.init(
                    me.grid_id,
                    me.g_url,
                    me.colModel,
                    me.pager,
                    me.sidx,
                    me.option
                );
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 461);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 250 : 340);

                $("#jqgh_PPRM705R4BusyoSearch_gvInfo5_rn").html("No");
                //20201120 WL DEL S
                // //20170913 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                // //20170913 YIN INS E
                //20201120 WL DEL E
            }
        } catch (ex) {
            console.log(ex);
        }
    };

    //選択ボタン押下
    $(".PPRM705R4BusyoSearch.btnSelect").click(function () {
        me.windowClose();
    });
    //戻るボタン押下
    $(".PPRM705R4BusyoSearch.btnClose").click(function () {
        me.windowClose2();
    });
    //表示ボタン押下
    $(".PPRM705R4BusyoSearch.btnView").click(function () {
        me.btnView_Click();
    });

    $(".PPRM705R4BusyoSearch.txtDeployCode").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM705R4BusyoSearch.txtDeployCode").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM705R4BusyoSearch.txtdeployName").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM705R4BusyoSearch.txtdeployName").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM705R4BusyoSearch.txtdeployKN").on("focus", function () {
        TextAreaSelect($(this));
    });

    $(".PPRM705R4BusyoSearch.txtdeployKN").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引 数 １：(I)sender イベントソース
    //'引 数 ２：(I)e      イベントパラメータ
    //'戻 り 値：なし
    //'処理説明：画面項目の表示,取得データを部署グリッドにバインドする
    //'**********************************************************************

    me.btnView_Click = function () {
        var txtDeployCode = $(".PPRM705R4BusyoSearch.txtDeployCode").val();
        var txtdeployName = $(".PPRM705R4BusyoSearch.txtdeployName").val();
        var txtdeployKN = $(".PPRM705R4BusyoSearch.txtdeployKN").val();

        me.complete_fun = function (bErrorFlag) {
            if (bErrorFlag == "nodata") {
                $(".PPRM705R4BusyoSearch.txtDeployCode").trigger("focus");
                clsComFnc.FncMsgBox("W0003_PPRM");
                $(".PPRM705R4BusyoSearch.btnSelect").css(
                    "visibility",
                    "hidden"
                );
                return;
            } else {
            }
        };

        var data = {
            // hidTKB: me.strTenpoKB,
            txtDeployCode: txtDeployCode,
            txtdeployName: txtdeployName,
            txtdeployKN: txtdeployKN,
        };

        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 461);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, me.ratio === 1.5 ? 250 : 340);
        $(".PPRM705R4BusyoSearch.btnSelect").css("visibility", "visible");
    };
    // //'**********************************************************************
    // //'処 理 名：店舗グリッド行選択のイベント
    // //'関 数 名：windowClose
    // //'引 数 １：(I)sender イベントソース
    // //'引 数 ２：(I)e      イベントパラメータ
    // //'戻 り 値：なし
    // //'処理説明：店舗グリッド行選択の処理
    // //'**********************************************************************
    me.windowClose = function () {
        var id = $("#PPRM705R4BusyoSearch_gvInfo5").jqGrid(
            "getGridParam",
            "selrow"
        );
        if (id == null) {
            clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
        } else {
            var rowData = $("#PPRM705R4BusyoSearch_gvInfo5").jqGrid(
                "getRowData",
                id
            );

            if ($.trim(rowData["BUSYO_CD"]) != "") {
                me.busyocd = rowData["BUSYO_CD"];
                me.busyonm = rowData["BUSYO_NM"];
            }
            me.flg = 1;
            $(".PPRM705R4BusyoSearch.body").dialog("close");
        }
    };

    me.windowClose2 = function () {
        me.flg = 2;
        $(".PPRM705R4BusyoSearch.body").dialog("close");
    };
    //テキストエリアを全選択する
    function TextAreaSelect(obj) {
        obj.select();
    }

    return me;
};

$(function () {
    var o_PPRM_PPRM705R4BusyoSearch = new PPRM.PPRM705R4BusyoSearch();
    o_PPRM_PPRM705R4BusyoSearch.load();
    o_PPRM_PPRM.PPRM705R4BusyoSearch = o_PPRM_PPRM705R4BusyoSearch;
});
