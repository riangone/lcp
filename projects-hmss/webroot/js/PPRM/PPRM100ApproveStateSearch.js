/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20201117            bug                          表示倍率：125％の場合は、「Chrome」でjqGridの見出しが間違っています。       WL
 * 20201119            bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる        WL
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("PPRM.PPRM100ApproveStateSearch");

PPRM.PPRM100ApproveStateSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ODR_Jscript = new gdmz.PPRM.ODR_JScript();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "PPRM100ApproveStateSearch";
    me.ajax = ajax;
    me.sys_id = "PPRM";
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    me.url = "";
    me.data = new Array();
    me.flag1 = true;
    me.flag2 = true;

    me.hidGamenFLG = "";
    me.strReadOnlyFlg = "";
    me.strMODE = "";
    me.strTCD = "";
    me.strHDATE = "";
    me.strHNO = "";
    me.grid1FirstLoad = true;

    //20170908 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170908 ZHANGXIAOLEI INS E

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E
    {
        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnFromTenpoSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnHJMSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnToTenpoSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.txtHJMFromDate",
            type: "datepicker",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.txtHJMToDate",
            type: "datepicker",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnFromSyainCDSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnToSyainCDSearch",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnKinsyuInput",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnEditOrDelete",
            type: "button",
            handle: "",
        });

        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnSyonin",
            type: "button",
            handle: "",
        });
        me.controls.push({
            id: ".PPRM100ApproveStateSearch.btnSyonin1",
            type: "button",
            handle: "",
        });
    }

    //jqgrid
    {
        me.grid_id1 = "#PPRM100ApproveStateSearch_jqGrid1";
        me.grid_id2 = "#PPRM100ApproveStateSearch_jqGrid2";
        me.g_url = "PPRM/PPRM100ApproveStateSearch/btnSearch_click";
        me.pager = "";
        me.sidx = "";

        me.option1 = {
            rowNum: 100,
            recordpos: "left",
            multiselect: false,
            rownumbers: true,
            rownumWidth: 40,
            caption: "",
            multiselectWidth: 30,
            scroll: 50,
        };

        me.option2 = {
            rowNum: 9999,
            recordpos: "left",
            multiselect: false,
            rownumbers: true,
            caption: "",
            multiselectWidth: 30,
            scroll: true,
        };

        me.colModel1 = [
            {
                name: "HJM_SYR_DTM",
                label: "日締日",
                index: "HJM_SYR_DTM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 114,
            },
            {
                name: "TENPO_CD",
                label: " ",
                index: "TENPO_CD",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 32,
            },
            {
                name: "BUSYO_RYKNM",
                label: "店舗",
                index: "BUSYO_RYKNM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 75,
            },
            {
                name: "TEN_HJM_NO",
                label: "日締№",
                index: "TEN_HJM_NO",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 112,
            },
            {
                name: "HJM_DATA_SONZAI",
                //20201117 WL UPD S
                //label : "  日締  データ",
                label: "日締<br />データ",
                //20201117 WL UPD E
                index: "HJM_DATA_SONZAI",
                //20201117 WL UPD S
                //width : 45,
                width: 50,
                //20201117 WL UPD E
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                align: "center",
            },
            {
                name: "HJM_CHK",
                label: "",
                index: "HJM_CHK",
                hidden: true,
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 60,
            },
            {
                name: "MNY_CHK",
                label: "登録状態",
                formatter: "checkbox",
                align: "center",
                index: "MNY_CHK",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 60,
            },
            {
                name: "TANTO1",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO1",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 20,
            },
            {
                name: "KEIRI_SNN_TANTO_NM",
                label: "経理担当",
                index: "KEIRI_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 59,
            },
            {
                name: "TANTO2",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO2",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 20,
            },
            {
                name: "TENCHO_SNN_TANTO_NM",
                label: "店長",
                index: "TENCHO_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 57,
            },
            {
                name: "TANTO3",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO3",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 20,
            },
            {
                name: "KACHO_SNN_TANTO_NM",
                label: "課長",
                index: "KACHO_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 57,
            },
            {
                name: "TANTO4",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO4",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 20,
            },
            {
                name: "TAN_SNN_TANTO_NM",
                label: "担当",
                index: "TAN_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 57,
            },
            {
                name: "JYOUTAI_FLG",
                label: "",
                index: "JYOUTAI_FLG",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                hidden: true,
                width: 50,
            },
            {
                name: "SYOUNIN_DISP_FLG",
                label: "",
                index: "SYOUNIN_DISP_FLG",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                hidden: true,
                width: 50,
            },
            {
                name: "KINSYU_DISP_FLG",
                label: "",
                index: "KINSYU_DISP_FLG",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                hidden: true,
                width: 50,
            },
            {
                name: "FUICHI_RIYU",
                label: "",
                index: "FUICHI_RIYU",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                hidden: true,
                width: 50,
            },
        ];

        me.colModel2 = [
            {
                name: "URIAGEDT",
                label: "売上日",
                index: "URIAGEDT",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 120,
            },
            {
                name: "TENPO_CD",
                label: " ",
                index: "TENPO_CD",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 40,
            },
            {
                name: "BUSYO_RYKNM",
                label: "店舗",
                index: "BUSYO_RYKNM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 120,
            },
            //VB中table列有，数据库没有字段
            // {
            // name : "HJM_DATA_SONZAI",
            // label : " ",
            // index : "HJM_DATA_SONZAI",
            // hidden : true,
            // width : 35
            // },
            // {
            // name : "MNY_CHK",
            // label : " ",
            // index : "MNY_CHK",
            // hidden : true,
            // width : 35
            // },
            {
                name: "TANTO1",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO1",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 40,
            },
            {
                name: "KEIRI_SNN_TANTO_NM",
                label: "経理担当",
                index: "KEIRI_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 78,
            },
            {
                name: "TANTO2",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO2",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 40,
            },
            {
                name: "TENCHO_SNN_TANTO_NM",
                label: "店長",
                index: "TENCHO_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 78,
            },
            {
                name: "TANTO3",
                label: " ",
                formatter: "checkbox",
                align: "center",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                index: "TANTO3",
                width: 40,
            },
            {
                name: "KACHO_SNN_TANTO_NM",
                label: "課長",
                index: "KACHO_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 78,
            },
            {
                name: "TANTO4",
                label: " ",
                formatter: "checkbox",
                align: "center",
                index: "TANTO4",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 40,
            },
            {
                name: "TAN_SNN_TANTO_NM",
                label: "担当",
                index: "TAN_SNN_TANTO_NM",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                width: 78,
            },
            {
                name: "SYOUNIN_DISP_FLG",
                label: " ",
                index: "SYOUNIN_DISP_FLG",
                //20171115 YIN INS S
                sortable: false,
                //20171115 YIN INS E
                hidden: true,
                width: 35,
            },
        ];
    }

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //20170908 ZHANGXIAOLEI UPD S
        // me.PPRM100ApproveStateSearch_load();
        me.getAllBusyoNM();
        //20170908 ZHANGXIAOLEI UPD E
    };

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部の店舗コードと店舗名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の店舗コードと店舗名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetALLBusyoAndSyain";
        var selectObj = {};
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                me.BusyoArr = result["data"];
            }

            me.PPRM100ApproveStateSearch_load();
        };
        me.ajax.send(url, selectObj, 0);
    };

    me.FncGetBusyoNM = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }

            var busyoData = me.BusyoArr["BusyoData"];
            for (key in busyoData) {
                if (strCD == busyoData[key]["TENPO_CD"]) {
                    return busyoData[key]["BUSYO_NM"];
                }
            }
        } catch (e) {
            return "";
        }
    };
    me.FncGetSyainNM = function (strCD) {
        try {
            if (strCD == "") {
                return "";
            }

            var syainData = me.BusyoArr["SyainData"];
            for (key in syainData) {
                if (strCD == syainData[key]["SYAIN_NO"]) {
                    return syainData[key]["SYAIN_NM"];
                }
            }
        } catch (e) {
            return "";
        }
    };
    //'**********************************************************************
    //'処 理 名：フォーカスアウト
    //'関 数 名：me.blurDeal
    //'引 数 　：strCD:コード
    //'      　：ctrlNM:名称
    //'      　：ctrlFocus:次のコントロール
    //'      　：flgBysyoSyain:0:部署;1:社員
    //'戻 り 値：なし
    //'処理説明：フォーカスアウト
    //'**********************************************************************
    me.blurDeal = function (strCD, ctrlNM, ctrlFocus, flgBusyoSyain) {
        var txtFromTenpoNM = ctrlNM.val();
        switch (flgBusyoSyain) {
            case 0:
                ctrlNM.val(me.FncGetBusyoNM(strCD));
                break;
            case 1:
                ctrlNM.val(me.FncGetSyainNM(strCD));
                break;
        }
        if (txtFromTenpoNM != ctrlNM.val()) {
            ctrlFocus.trigger("focus");
        }
    };
    //20170908 ZHANGXIAOLEI INS E

    //変更時スプレッドクリア
    {
        $(".PPRM100ApproveStateSearch.txtFromTenpoCD").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtToTenpoCD").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtFromTenpoCD").on("blur", function () {
            var txtFromTenpoCD = $(
                ".PPRM100ApproveStateSearch.txtFromTenpoCD"
            ).val();
            //20170908 ZHANGXIAOLEI UPD S
            // var txtToTenpoCD = $(".PPRM100ApproveStateSearch.txtToTenpoCD").val();
            // var txtFromSyainCD = $(".PPRM100ApproveStateSearch.txtFromSyainCD").val();
            // var txtToSyainCD = $(".PPRM100ApproveStateSearch.txtToSyainCD").val();
            // getBusyoNM(txtFromTenpoCD, txtToTenpoCD, txtFromSyainCD, txtToSyainCD, false);
            me.blurDeal(
                txtFromTenpoCD,
                $(".PPRM100ApproveStateSearch.lblFromTenpo"),
                $(".PPRM100ApproveStateSearch.txtToTenpoCD"),
                0
            );
            //20170908 ZHANGXIAOLEI UPD E
        });

        $(".PPRM100ApproveStateSearch.txtToTenpoCD").on("blur", function () {
            //20170908 ZHANGXIAOLEI UPD S
            // var txtFromTenpoCD = $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val();
            var txtToTenpoCD = $(
                ".PPRM100ApproveStateSearch.txtToTenpoCD"
            ).val();
            // var txtFromSyainCD = $(".PPRM100ApproveStateSearch.txtFromSyainCD").val();
            // var txtToSyainCD = $(".PPRM100ApproveStateSearch.txtToSyainCD").val();
            // getBusyoNM(txtFromTenpoCD, txtToTenpoCD, txtFromSyainCD, txtToSyainCD, false);
            me.blurDeal(
                txtToTenpoCD,
                $(".PPRM100ApproveStateSearch.lblToTenpo"),
                $(".PPRM100ApproveStateSearch.txtHJMFromDate"),
                0
            );
            //20170908 ZHANGXIAOLEI UPD E
        });

        $(".PPRM100ApproveStateSearch.txtHJMFromDate").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtHJMFromDate").on("blur", function () {
            ODR_Jscript.DateFOut($(this));
        });

        $(".PPRM100ApproveStateSearch.txtHJMToDate").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtHJMToDate").on("blur", function () {
            ODR_Jscript.DateFOut($(this));
        });

        $(".PPRM100ApproveStateSearch.txtHJMNo").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtFromSyainCD").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtToSyainCD").change(function () {
            spdClear();
        });

        $(".PPRM100ApproveStateSearch.txtFromSyainCD").on("blur", function () {
            //20170908 ZHANGXIAOLEI UPD S
            // var txtFromTenpoCD = $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val();
            // var txtToTenpoCD = $(".PPRM100ApproveStateSearch.txtToTenpoCD").val();
            var txtFromSyainCD = $(
                ".PPRM100ApproveStateSearch.txtFromSyainCD"
            ).val();
            // var txtToSyainCD = $(".PPRM100ApproveStateSearch.txtToSyainCD").val();
            // getBusyoNM(txtFromTenpoCD, txtToTenpoCD, txtFromSyainCD, txtToSyainCD, false);
            me.blurDeal(
                txtFromSyainCD,
                $(".PPRM100ApproveStateSearch.lblFromSyain"),
                $(".PPRM100ApproveStateSearch.txtToSyainCD"),
                1
            );
            //20170908 ZHANGXIAOLEI UPD E
        });

        $(".PPRM100ApproveStateSearch.txtToSyainCD").on("blur", function () {
            //20170908 ZHANGXIAOLEI UPD S
            // var txtFromTenpoCD = $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val();
            // var txtToTenpoCD = $(".PPRM100ApproveStateSearch.txtToTenpoCD").val();
            // var txtFromSyainCD = $(".PPRM100ApproveStateSearch.txtFromSyainCD").val();
            var txtToSyainCD = $(
                ".PPRM100ApproveStateSearch.txtToSyainCD"
            ).val();
            // getBusyoNM(txtFromTenpoCD, txtToTenpoCD, txtFromSyainCD, txtToSyainCD, false);
            me.blurDeal(
                txtToSyainCD,
                $(".PPRM100ApproveStateSearch.lblToSyain"),
                $(".PPRM100ApproveStateSearch.btnSearch"),
                1
            );
            //20170908 ZHANGXIAOLEI UPD E
        });
    }

    //事務のchange
    $(".PPRM100ApproveStateSearch.rdbTaisyo1").change(function () {
        rdbTaisyo1_CheckedChanged();
    });
    //整備のchange
    $(".PPRM100ApproveStateSearch.rdbTaisyo2").change(function () {
        rdbTaisyo2_CheckedChanged();
    });
    //指定なしのchange
    $(".PPRM100ApproveStateSearch.rdbJyoutai1").change(function () {
        spdClear();
    });
    //日締データ有り・金種表登録済みのchange
    $(".PPRM100ApproveStateSearch.rdbJyoutai2").change(function () {
        spdClear();
    });
    //日締データ有り・金種表未登録のchange
    $(".PPRM100ApproveStateSearch.rdbJyoutai3").change(function () {
        spdClear();
    });
    //日締データ無し・金種表登録済み のchange
    $(".PPRM100ApproveStateSearch.rdbJyoutai4").change(function () {
        spdClear();
    });
    //未のchange
    $(".PPRM100ApproveStateSearch.rdbJyokyo1").change(function () {
        spdClear();
    });
    //済のchange
    $(".PPRM100ApproveStateSearch.rdbJyokyo2").change(function () {
        spdClear();
    });
    //指定なしのchange
    $(".PPRM100ApproveStateSearch.rdbJyokyo3").change(function () {
        spdClear();
    });
    //経理担当のchange
    $(".PPRM100ApproveStateSearch.rdbKakunin1").change(function () {
        spdClear();
    });
    //店長のchange
    $(".PPRM100ApproveStateSearch.rdbKakunin2").change(function () {
        spdClear();
    });
    //課長のchange
    $(".PPRM100ApproveStateSearch.rdbKakunin3").change(function () {
        spdClear();
    });
    //担当のchange
    $(".PPRM100ApproveStateSearch.rdbKakunin4").change(function () {
        spdClear();
    });

    //確認状況反映（確認状況で選択した値に対するラジオボタンにチェックをつける）
    $(".PPRM100ApproveStateSearch.ddlKakunin").change(function () {
        listCheck();
        spdClear();
    });

    //店舗コード検索ボタン（From）
    $(".PPRM100ApproveStateSearch.btnFromTenpoSearch").click(function () {
        var fromOrTo = "from";
        openTenpoSearch(fromOrTo);
    });

    //店舗コード検索ボタン（To）
    $(".PPRM100ApproveStateSearch.btnToTenpoSearch").click(function () {
        var fromOrTo = "to";
        openTenpoSearch(fromOrTo);
    });

    //日締№検索ボタン
    $(".PPRM100ApproveStateSearch.btnHJMSearch").click(function () {
        btnHJMSearch_click();
    });

    //社員コード検索ボタン（From）
    $(".PPRM100ApproveStateSearch.btnFromSyainCDSearch").click(function () {
        var fromSyainCD = $(".PPRM100ApproveStateSearch.txtFromSyainCD").val();
        var fromOrTo = "from";
        openSyainSearch(fromSyainCD, fromOrTo);
    });

    //社員コード検索ボタン（To）
    $(".PPRM100ApproveStateSearch.btnToSyainCDSearch").click(function () {
        var toSyainCD = $(".PPRM100ApproveStateSearch.txtToSyainCD").val();
        var fromOrTo = "to";
        openSyainSearch(toSyainCD, fromOrTo);
    });

    //検索ボタン
    $(".PPRM100ApproveStateSearch.btnSearch").click(function () {
        btnSearch_click();
    });

    //当日分金種表入力ボタン
    $(".PPRM100ApproveStateSearch.btnKinsyuInput").click(function () {
        openKinsyuInNew();
    });

    //金種表入力画面遷移（編集・削除ボタン）
    $(".PPRM100ApproveStateSearch.btnEditOrDelete").click(function () {
        var id = $("#PPRM100ApproveStateSearch_jqGrid1").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM100ApproveStateSearch_jqGrid1").jqGrid(
            "getRowData",
            id
        );
        var no1 = rowData["TENPO_CD"];
        var no2 = rowData["HJM_SYR_DTM"];
        var no3 = rowData["TEN_HJM_NO"];
        openKinsyuInEdit(no1, no2, no3);
    });

    //承認画面遷移（承認ボタン）
    $(".PPRM100ApproveStateSearch.btnSyonin").click(function () {
        var id = $("#PPRM100ApproveStateSearch_jqGrid1").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM100ApproveStateSearch_jqGrid1").jqGrid(
            "getRowData",
            id
        );
        var no1 = "1";
        var no2 = rowData["TENPO_CD"];
        var no3 = rowData["HJM_SYR_DTM"];
        var no4 = rowData["TEN_HJM_NO"];
        openSyonin(no1, no2, no3, no4);
    });

    //承認画面遷移（承認を行うボタン）
    $(".PPRM100ApproveStateSearch.btnSyonin1").click(function () {
        var id = $("#PPRM100ApproveStateSearch_jqGrid2").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM100ApproveStateSearch_jqGrid2").jqGrid(
            "getRowData",
            id
        );
        var no1 = "2";
        var no2 = rowData["TENPO_CD"];
        var no3 = rowData["URIAGEDT"];
        openSyonin(no1, no2, no3, "");
    });

    // 2017/09/08 CI INS S
    $(".PPRM100ApproveStateSearch.txtFromTenpoCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    $(".PPRM100ApproveStateSearch.txtToTenpoCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    $(".PPRM100ApproveStateSearch.txtHJMNo").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    $(".PPRM100ApproveStateSearch.txtFromSyainCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });

    $(".PPRM100ApproveStateSearch.txtToSyainCD").on("blur", function () {
        ODR_Jscript.KinsokuMojiCheck($(this));
    });
    // 2017/09/08 CI INS E

    // '**********************************************************************
    // '処 理 名：ページロード
    // '関 数 名：PPRM100ApproveStateSearch_load
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：ページ初期化
    // '**********************************************************************
    me.PPRM100ApproveStateSearch_load = function () {
        //'セッションが切れている場合は処理を抜ける
        if (FncCheckSession() == false) {
            return;
        }
        //'画面初期設定
        subFormInt();

        //'日締日に前日日付をセット
        $(".PPRM100ApproveStateSearch.txtHJMFromDate").val("");
        $(".PPRM100ApproveStateSearch.txtHJMToDate").val("");

        //'権限設定（初期値）
        var url =
            me.sys_id + "/" + me.id + "/" + "pprm100ApproveStateSearchLoad";
        var data = {};

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            for (key in result["data"]) {
                $(".PPRM100ApproveStateSearch." + key).prop(
                    "disabled",
                    result["data"][key]
                );
            }
            //'対象による画面設定
            if (gdmz.SessionBusyoKB == "F") {
                $(".PPRM100ApproveStateSearch.rdbTaisyo2").prop(
                    "checked",
                    true
                );
                rdbTaisyo2_CheckedChanged();
            } else {
                $(".PPRM100ApproveStateSearch.rdbTaisyo1").prop(
                    "checked",
                    true
                );
                rdbTaisyo1_CheckedChanged();
            }
        };
        ajax.send(url, data, 0);

        //'フォーカス設定
        $(".PPRM100ApproveStateSearch.txtFromTenpoCD").trigger("focus");
    };

    // '**********************************************************************
    // '処 理 名：セッション状態のチェック
    // '関 数 名：FncCheckSession
    // '引 数   ：なし
    // '戻 り 値：true/false
    // '処理説明：ログイン画面かどうかを判断する
    // '**********************************************************************
    function FncCheckSession() {
        if (gdmz.SessionUserId != undefined) {
            if (
                gdmz.SessionUserId.toString() == null ||
                gdmz.SessionUserId.toString() == ""
            ) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    // '**********************************************************************
    // '処 理 名：店舗コード検索
    // '関 数 名：openTenpoSearch
    // '引 数 1 ：fromOrTo
    // '戻 り 値：なし
    // '処理説明：店舗コード検索（From/To）画面遷移
    // '**********************************************************************
    function openTenpoSearch(fromOrTo) {
        // me.TKB = 1;
        me.url = "PPRM/PPRM705R4BusyoSearch";

        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: me.TKB,
        //     })
        // );

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;

                    switch (fromOrTo) {
                        case "from":
                            if (busyocd != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.txtFromTenpoCD"
                                ).val(busyocd);
                                spdClear();
                            }
                            if (busyonm != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.lblFromTenpo"
                                ).val(busyonm);
                                spdClear();
                            }
                            break;
                        case "to":
                            if (busyocd != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.txtToTenpoCD"
                                ).val(busyocd);
                                spdClear();
                            }
                            if (busyonm != "") {
                                $(".PPRM100ApproveStateSearch.lblToTenpo").val(
                                    busyonm
                                );
                                spdClear();
                            }
                            break;
                    }
                }
            }

            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };
        me.ajax.send(me.url, me.data, 0);
    }

    // '**********************************************************************
    // '処 理 名：日締№検索
    // '関 数 名：btnHJMSearch_click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：日締№検索画面遷移
    // '**********************************************************************
    function btnHJMSearch_click() {
        me.url = "PPRM/PPRM202DCSearch";
        var REFPRG = "PPRM100ApproveStateSearch";
        var FTCD = $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val();
        var TTCD = $(".PPRM100ApproveStateSearch.txtToTenpoCD").val();
        var FDATE = $(".PPRM100ApproveStateSearch.txtHJMFromDate").val();
        var TDATE = $(".PPRM100ApproveStateSearch.txtHJMToDate").val();

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: REFPRG,
                FTCD: FTCD,
                TTCD: TTCD,
                FDATE: FDATE,
                TDATE: TDATE,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            function before_close() {
                var HJMNo = o_PPRM_PPRM.PPRM202DCSearch.HJMNo;
                if (HJMNo != undefined) {
                    $(".PPRM100ApproveStateSearch.txtHJMNo").val(HJMNo);
                    $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val("");
                    $(".PPRM100ApproveStateSearch.txtToTenpoCD").val("");
                    $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val("");
                    $(".PPRM100ApproveStateSearch.txtToTenpoCD").val("");
                    $(".PPRM100ApproveStateSearch.lblFromTenpo").val("");
                    $(".PPRM100ApproveStateSearch.lblToTenpo").val("");
                    $(".PPRM100ApproveStateSearch.txtHJMFromDate").val("");
                    $(".PPRM100ApproveStateSearch.txtHJMToDate").val("");
                    spdClear();
                }
            }

            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM202DCSearch.before_close = before_close;
        };
        me.ajax.send(me.url, me.data, 0);
    }

    // '**********************************************************************
    // '処 理 名：社員コード検索
    // '関 数 名：openSyainSearch
    // '引 数 1 ：syainCD  社員NO
    // '引 数 2 ：fromOrTo
    // '戻 り 値：なし
    // '処理説明：社員コード検索（From/To）画面遷移
    // '**********************************************************************
    function openSyainSearch(_syainCD, fromOrTo) {
        me.url = "PPRM/PPRM703SyainSearch";

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM703SyainSearch.flg == 1) {
                    //Else
                    var syaincd = o_PPRM_PPRM.PPRM703SyainSearch.syainCD;
                    var syainnm = o_PPRM_PPRM.PPRM703SyainSearch.syainNM;

                    switch (fromOrTo) {
                        case "from":
                            if (syaincd != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.txtFromSyainCD"
                                ).val(syaincd);
                                spdClear();
                            }
                            if (syainnm != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.lblFromSyain"
                                ).val(syainnm);
                                spdClear();
                            }
                            break;
                        case "to":
                            if (syaincd != "") {
                                $(
                                    ".PPRM100ApproveStateSearch.txtToSyainCD"
                                ).val(syaincd);
                                spdClear();
                            }
                            if (syainnm != "") {
                                $(".PPRM100ApproveStateSearch.lblToSyain").val(
                                    syainnm
                                );
                                spdClear();
                            }
                            break;
                    }
                }
            }

            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM703SyainSearch.before_close = before_close;
        };
        me.ajax.send(me.url, me.data, 0);
    }

    // '**********************************************************************
    // '処 理 名：検索ボタン
    // '関 数 名：btnSearch_click
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：検索ボタン押下処理
    // '**********************************************************************
    function btnSearch_click() {
        //'セッションが切れてたら検索処理は行わない
        if (FncCheckSession() == false) {
            return;
        }
        //項目値取得
        var txtFromTenpoCD = $(
            ".PPRM100ApproveStateSearch.txtFromTenpoCD"
        ).val();
        var txtToTenpoCD = $(".PPRM100ApproveStateSearch.txtToTenpoCD").val();
        var txtHJMFromDate = $(
            ".PPRM100ApproveStateSearch.txtHJMFromDate"
        ).val();
        var txtHJMToDate = $(".PPRM100ApproveStateSearch.txtHJMToDate").val();
        var txtHJMNo = $(".PPRM100ApproveStateSearch.txtHJMNo").val();
        var txtFromSyainCD = $(
            ".PPRM100ApproveStateSearch.txtFromSyainCD"
        ).val();
        var txtToSyainCD = $(".PPRM100ApproveStateSearch.txtToSyainCD").val();
        var rdbJyoutai = "";
        $(".rdo1 input").each(function () {
            if ($(this).prop("checked") == true) {
                rdbJyoutai = $(this).val();
            }
        });
        var ddlKakunin = $(".PPRM100ApproveStateSearch.ddlKakunin").val();
        var rdbJyokyo = "";
        $(".rdo2 input").each(function () {
            if ($(this).prop("checked") == true) {
                rdbJyokyo = $(this).val();
            }
        });
        var rdbKakunin = "";
        $(".rdo3 input").each(function () {
            if ($(this).prop("checked") == true) {
                rdbKakunin = $(this).val();
            }
        });

        //'チェック（店舗）
        if (txtFromTenpoCD != "" && txtToTenpoCD != "") {
            if (txtFromTenpoCD > txtToTenpoCD) {
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "店舗コード（前）",
                    "店舗コード（後）"
                );
                return;
            }
        }

        //'チェック（日締日）
        if (txtHJMFromDate != "" && txtHJMToDate != "") {
            if (txtHJMFromDate > txtHJMToDate) {
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "日締日（前）",
                    "日締日（後）"
                );
                return;
            }
            //2017/09/25 CI INS S
            var startTime = new Date(
                Date.parse(txtHJMFromDate.replace(/-/g, "/"))
            ).getTime();
            var endTime = new Date(
                Date.parse(txtHJMToDate.replace(/-/g, "/"))
            ).getTime();
            var dates = Math.abs(startTime - endTime) / (1000 * 60 * 60 * 24);
            if (dates > 30) {
                clsComFnc.FncMsgBox("W9999", "指定された期間が長すぎます。");
                return;
            }
            //2017/09/25 CI INS E
        }

        //'チェック（社員コード）
        if (txtFromSyainCD != "" && txtToSyainCD != "") {
            if (txtFromSyainCD > txtToSyainCD) {
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "社員コード（前）",
                    "社員コード（後）"
                );
                return;
            }
        }
        spdClear();

        //'ラベル再表示(店舗名，社員名)
        //20170908 ZHANGXIAOLEI UPD S
        // getBusyoNM(txtFromTenpoCD, txtToTenpoCD, txtFromSyainCD, txtToSyainCD, false);
        $(".PPRM100ApproveStateSearch.lblFromTenpo").val(
            me.FncGetBusyoNM(txtFromTenpoCD)
        );
        $(".PPRM100ApproveStateSearch.lblToTenpo").val(
            me.FncGetBusyoNM(txtToTenpoCD)
        );
        $(".PPRM100ApproveStateSearch.lblFromSyain").val(
            me.FncGetSyainNM(txtFromSyainCD)
        );
        $(".PPRM100ApproveStateSearch.lblToSyain").val(
            me.FncGetSyainNM(txtToSyainCD)
        );
        //20170908 ZHANGXIAOLEI UPD E

        //'対象が『事務』の場合
        if ($(".PPRM100ApproveStateSearch.rdbTaisyo1").prop("checked")) {
            var url = me.sys_id + "/" + me.id + "/" + "fncSearch1";
            var arr = {
                txtFromTenpoCD: txtFromTenpoCD,
                txtToTenpoCD: txtToTenpoCD,
                txtHJMFromDate: txtHJMFromDate,
                txtHJMToDate: txtHJMToDate,
                txtHJMNo: txtHJMNo,
                txtFromSyainCD: txtFromSyainCD,
                txtToSyainCD: txtToSyainCD,
                rdbJyoutai: rdbJyoutai,
                ddlKakunin: ddlKakunin,
                rdbJyokyo: rdbJyokyo,
                rdbKakunin: rdbKakunin,
            };
            var data = {
                request: arr,
            };

            me.complete_fun = function () {
                //20170921 lqs INS S
                setTimeout(() => {
                    $("#PPRM100ApproveStateSearch_jqGrid1_TENPO_CD").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid1_TANTO1").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid1_TANTO2").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid1_TANTO3").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid1_TANTO4").remove();
                }, 50);
                $("#PPRM100ApproveStateSearch_jqGrid1_BUSYO_RYKNM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid1_KEIRI_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                $(
                    "#PPRM100ApproveStateSearch_jqGrid1_TENCHO_SNN_TANTO_NM"
                ).prop("colspan", 2);
                $("#PPRM100ApproveStateSearch_jqGrid1_KACHO_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid1_TAN_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                //20170921 lqs INS E
                var dataNum = $(".PPRM100ApproveStateSearch_jqGrid1").jqGrid(
                    "getGridParam",
                    "records"
                );

                var ids = $("#PPRM100ApproveStateSearch_jqGrid1").jqGrid(
                    "getDataIDs"
                );
                for (var i = 0; i < ids.length; i++) {
                    var rowData = $(
                        "#PPRM100ApproveStateSearch_jqGrid1"
                    ).jqGrid("getRowData", ids[i]);
                    switch (rowData["JYOUTAI_FLG"]) {
                        case "1":
                            $(
                                "#PPRM100ApproveStateSearch_jqGrid1 " +
                                    "#" +
                                    ids[i]
                            )
                                .find("td")
                                .css("background-color", "#FF3333");
                            break;
                        case "2":
                            $(
                                "#PPRM100ApproveStateSearch_jqGrid1 " +
                                    "#" +
                                    ids[i]
                            )
                                .find("td")
                                .css("background-color", "#0066FF");
                            break;
                        case "3":
                            $(
                                "#PPRM100ApproveStateSearch_jqGrid1 " +
                                    "#" +
                                    ids[i]
                            )
                                .find("td")
                                .css("background-color", "#FFFF33");
                            break;
                    }
                }

                $(".PPRM100ApproveStateSearch_jqGrid1").jqGrid("setGridParam", {
                    onSelectRow: function (rowid, status) {
                        var rowData = $(
                            "#PPRM100ApproveStateSearch_jqGrid1"
                        ).jqGrid("getRowData", rowid);
                        if (status) {
                            //'編集・削除ボタン
                            //'経理担当が承認している場合
                            if (rowData["TANTO1"] == "Yes") {
                                $(
                                    ".PPRM100ApproveStateSearch.btnEditOrDelete"
                                ).button("disable");
                            } else {
                                if (
                                    clsComFnc.FncNz(
                                        rowData["KINSYU_DISP_FLG"]
                                    ) == 0
                                ) {
                                    $(
                                        ".PPRM100ApproveStateSearch.btnEditOrDelete"
                                    ).button("disable");
                                } else {
                                    $(
                                        ".PPRM100ApproveStateSearch.btnEditOrDelete"
                                    ).button("enable");
                                }
                            }
                            //'承認を行うボタン
                            //'経理担当が承認している場合
                            if (rowData["TANTO1"] == "Yes") {
                                //'経理課の場合
                                if (
                                    gdmz.SessionBusyoCD == "122" ||
                                    gdmz.SessionBusyoCD == "125"
                                ) {
                                    if (
                                        clsComFnc.FncNz(
                                            rowData["SYOUNIN_DISP_FLG"]
                                        ) == 0
                                    ) {
                                        $(
                                            ".PPRM100ApproveStateSearch.btnSyonin"
                                        ).button("disable");
                                    } else {
                                        $(
                                            ".PPRM100ApproveStateSearch.btnSyonin"
                                        ).button("enable");
                                    }
                                } else {
                                    $(
                                        ".PPRM100ApproveStateSearch.btnSyonin"
                                    ).button("disable");
                                }
                            } else {
                                //'承認可能状態（色がつかない場合）
                                if (rowData["JYOUTAI_FLG"] == 0) {
                                    if (
                                        clsComFnc.FncNz(
                                            rowData["SYOUNIN_DISP_FLG"]
                                        ) == 0
                                    ) {
                                        $(
                                            ".PPRM100ApproveStateSearch.btnSyonin"
                                        ).button("disable");
                                    } else {
                                        $(
                                            ".PPRM100ApproveStateSearch.btnSyonin"
                                        ).button("enable");
                                    }
                                } else {
                                    //'承認不可データの場合
                                    if (rowData["JYOUTAI_FLG"] == 3) {
                                        if (
                                            rowData["FUICHI_RIYU"].trimEnd() ==
                                            ""
                                        ) {
                                            $(
                                                ".PPRM100ApproveStateSearch.btnSyonin"
                                            ).button("disable");
                                        } else {
                                            $(
                                                ".PPRM100ApproveStateSearch.btnSyonin"
                                            ).button("enable");
                                        }
                                    } else {
                                        $(
                                            ".PPRM100ApproveStateSearch.btnSyonin"
                                        ).button("disable");
                                    }
                                }
                            }
                        }
                    },
                });
                //20201119 CI DEL S
                //20170905 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                //20170905 YIN INS E
                //20201119 CI DEL E
                //'存在しない場合
                if (dataNum <= 0) {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    if (me.grid1FirstLoad) {
                        //20201119 CI UPD S
                        //gdmz.common.jqgrid.set_grid_width(me.grid_id1, 880);
                        gdmz.common.jqgrid.set_grid_width(me.grid_id1, 890);
                        //20201119 CI UPD E
                        gdmz.common.jqgrid.set_grid_height(me.grid_id1, me.ratio === 1.5 ? 80 : 130);
                        me.grid1FirstLoad = false;
                    }
                    //$("#PPRM100ApproveStateSearch_jqGrid1_rn").html('No');

                    $(".PPRM100ApproveStateSearch.jqgrid1").css(
                        "display",
                        "block"
                    );
                    $(".PPRM100ApproveStateSearch.tdlblSetsumei").css(
                        "display",
                        "block"
                    );
                    $(".PPRM100ApproveStateSearch.btnEditOrDelete").button(
                        "disable"
                    );
                    $(".PPRM100ApproveStateSearch.btnSyonin").button("disable");
                }
            };

            if (me.flag1) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id1,
                    url,
                    me.colModel1,
                    me.pager,
                    me.sidx,
                    me.option1,
                    data,
                    me.complete_fun
                );
                $(me.grid_id1).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        //{
                        //startColumnName : 'HJM_DATA_SONZAI',
                        //numberOfColumns : 1,
                        //titleText : '日締',
                        //}//
                        //,
                        {
                            startColumnName: "MNY_CHK",
                            numberOfColumns: 1,
                            titleText: "金種表",
                        }, //
                        {
                            startColumnName: "TANTO1",
                            numberOfColumns: 8,
                            titleText: "確認状況",
                        }, //
                    ],
                });
                me.flag1 = false;
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id1,
                    data,
                    me.complete_fun
                );
            }
        } else if ($(".PPRM100ApproveStateSearch.rdbTaisyo2").prop("checked")) {
            var url = me.sys_id + "/" + me.id + "/" + "fncSearch2";
            var arr = {
                txtFromTenpoCD: txtFromTenpoCD,
                txtToTenpoCD: txtToTenpoCD,
                txtHJMFromDate: txtHJMFromDate,
                txtHJMToDate: txtHJMToDate,
                txtFromSyainCD: txtFromSyainCD,
                txtToSyainCD: txtToSyainCD,
                ddlKakunin: ddlKakunin,
                rdbJyokyo: rdbJyokyo,
                rdbKakunin: rdbKakunin,
            };
            var data = {
                request: arr,
            };

            me.complete_fun = function () {
                //20170921 lqs INS S
                setTimeout(() => {
                    $("#PPRM100ApproveStateSearch_jqGrid2_TENPO_CD").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid2_TANTO1").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid2_TANTO2").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid2_TANTO3").remove();
                    $("#PPRM100ApproveStateSearch_jqGrid2_TANTO4").remove();
                }, 10);

                $("#PPRM100ApproveStateSearch_jqGrid2_BUSYO_RYKNM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_BUSYO_RYKNM").css(
                    "width",
                    "165px"
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_KEIRI_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_KEIRI_SNN_TANTO_NM").css(
                    "width",
                    "123px"
                );
                $(
                    "#PPRM100ApproveStateSearch_jqGrid2_TENCHO_SNN_TANTO_NM"
                ).prop("colspan", 2);
                $("#PPRM100ApproveStateSearch_jqGrid2_TENCHO_SNN_TANTO_NM").css(
                    "width",
                    "123px"
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_KACHO_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_KACHO_SNN_TANTO_NM").css(
                    "width",
                    "123px"
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_TAN_SNN_TANTO_NM").prop(
                    "colspan",
                    2
                );
                $("#PPRM100ApproveStateSearch_jqGrid2_TAN_SNN_TANTO_NM").css(
                    "width",
                    "123px"
                );
                //20170921 lqs INS E
                var dataNum = $(".PPRM100ApproveStateSearch_jqGrid2").jqGrid(
                    "getGridParam",
                    "records"
                );
                $(".PPRM100ApproveStateSearch_jqGrid2").jqGrid("setGridParam", {
                    onSelectRow: function (rowid, status) {
                        var rowData = $(
                            "#PPRM100ApproveStateSearch_jqGrid2"
                        ).jqGrid("getRowData", rowid);
                        if (status) {
                            //'ボタン設定
                            if (
                                rowData["TANTO1"] == "Yes" &&
                                gdmz.SessionBusyoCD != "122" &&
                                gdmz.SessionBusyoCD != "125"
                            ) {
                                $(
                                    ".PPRM100ApproveStateSearch.btnSyonin1"
                                ).button("disable");
                            } else {
                                if (
                                    clsComFnc.FncNz(
                                        rowData["SYOUNIN_DISP_FLG"]
                                    ) == 0
                                ) {
                                    $(
                                        ".PPRM100ApproveStateSearch.btnSyonin1"
                                    ).button("disable");
                                } else {
                                    $(
                                        ".PPRM100ApproveStateSearch.btnSyonin1"
                                    ).button("enable");
                                }
                            }
                        }
                    },
                });
                //20201119 WL DEL S
                // //20170905 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                // //20170905 YIN INS E
                //20201119 WL DEL E
                if (dataNum == 0) {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                } else {
                    //20201119 WL UPD S
                    //gdmz.common.jqgrid.set_grid_width(me.grid_id2, 870);
                    gdmz.common.jqgrid.set_grid_width(me.grid_id2, 880);
                    //20201119 WL UPD E
                    gdmz.common.jqgrid.set_grid_height(me.grid_id2, 100);

                    //$("#PPRM100ApproveStateSearch_jqGrid2_rn").html('No');
                    $(".PPRM100ApproveStateSearch.jqgrid2").css(
                        "display",
                        "block"
                    );
                    $(".PPRM100ApproveStateSearch.btnSyonin1").button(
                        "disable"
                    );
                }
            };

            if (me.flag2) {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id2,
                    url,
                    me.colModel2,
                    me.pager,
                    me.sidx,
                    me.option2,
                    data,
                    me.complete_fun
                );
                $(me.grid_id2).jqGrid("setGroupHeaders", {
                    useColSpanStyle: true,
                    groupHeaders: [
                        {
                            startColumnName: "TANTO1",
                            numberOfColumns: 8,
                            titleText: "確認状況",
                        }, //
                    ],
                });
                me.flag2 = false;
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id2,
                    data,
                    me.complete_fun
                );
            }
        }
    }

    // '**********************************************************************
    // '処 理 名：当日金種表入力ボタン
    // '関 数 名：openKinsyuInEdit
    // '引 数 1 ：no1
    // '引 数 2 ：no2
    // '引 数 3 ：no3
    // '戻 り 値：なし
    // '処理説明：金種表入力画面遷移（編集・削除）
    // '**********************************************************************
    function openKinsyuInEdit(no1, no2, no3) {
        me.url = "PPRM/PPRM203DCMonyKindInput";
        var REFPRG = "PPRM100ApproveStateSearch";
        var MODE = "UPD";
        var TCD = no1;
        var HDATE = no2;
        var HNO = no3;

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: REFPRG,
                MODE: MODE,
                TCD: TCD,
                HDATE: HDATE,
                HNO: HNO,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            $("." + me.id + "." + "dialogs").append(result);
        };
        me.ajax.send(me.url, me.data, 0);
    }

    // '**********************************************************************
    // '処 理 名：承認ボタン
    // '関 数 名：openSyonin
    // '引 数 1 ：no1
    // '引 数 2 ：no2
    // '引 数 3 ：no3
    // '引 数 4 ：no4
    // '戻 り 値：なし
    // '処理説明：承認画面遷移
    // '**********************************************************************
    function openSyonin(no1, no2, no3, no4) {
        me.url = "PPRM/PPRM101ApproveAct";
        var TAISYO = no1;
        var TCD = no2;
        var HDATE = no3;
        var HNO = no4;

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                TAISYO: TAISYO,
                TCD: TCD,
                HDATE: HDATE,
                HNO: HNO,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            function before_close() {
                //20180301 lqs INS S
                var keiri = o_PPRM_PPRM.PPRM101ApproveAct.keiri;
                var tencho = o_PPRM_PPRM.PPRM101ApproveAct.tencho;
                var kacho = o_PPRM_PPRM.PPRM101ApproveAct.kacho;
                var tantou = o_PPRM_PPRM.PPRM101ApproveAct.tantou;
                if (keiri || tencho || kacho || tantou) {
                    btnSearch_click();
                }
                //20180301 lqs INS E
            }

            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM101ApproveAct.before_close = before_close;
        };
        me.ajax.send(me.url, me.data, 0);
    }
    // '**********************************************************************
    // '処 理 名：文字列処理
    // '関 数 名：padleft
    // '引 数 1 ：str  原文字列
    // '引 数 2 ：length  全長
    // '戻 り 値：str   文字列
    // '処理説明：新しい文字列を返すのが所定の長さであり、現在の文字列の先頭のスペースまたは指定された文字で埋められる。
    // '**********************************************************************
    function padleft(str, length) {
        var strlength = str.length;
        if (strlength < length) {
            for (var i = 0; i < length - strlength; i++) {
                str = " " + str;
            }
        }
        return str;
    }

    // '**********************************************************************
    // '処 理 名：金種表入力画面遷移
    // '関 数 名：openKinsyuInNew
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：金種表入力画面遷移（新規）
    // '**********************************************************************
    function openKinsyuInNew() {
        me.url = "PPRM/PPRM203DCMonyKindInput";
        var REFPRG = "PPRM100ApproveStateSearch";
        var MODE = "NEW";

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: REFPRG,
                MODE: MODE,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            $("." + me.id + "." + "dialogs").append(result);
        };
        me.ajax.send(me.url, me.data, 0);
    }

    // '**********************************************************************
    // '処 理 名：変更時スプレッドクリア
    // '関 数 名：spdClear
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：変更時スプレッドクリア
    // '**********************************************************************
    function spdClear() {
        $(".PPRM100ApproveStateSearch.jqgrid1").css("display", "none");
        $(".PPRM100ApproveStateSearch.jqgrid2").css("display", "none");
        $(".PPRM100ApproveStateSearch.tdlblSetsumei").css("display", "none");
    }

    // '**********************************************************************
    // '処 理 名：画面初期化
    // '関 数 名：subFormInt
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：画面初期化
    // '**********************************************************************
    function subFormInt() {
        //'スプレッド
        $(".PPRM100ApproveStateSearch.jqgrid1").css("display", "none");
        $(".PPRM100ApproveStateSearch.jqgrid2").css("display", "none");
        //'説明
        $(".PPRM100ApproveStateSearch.tdlblSetsumei").css("display", "none");
    }

    // '**********************************************************************
    // '処 理 名：画面初期化
    // '関 数 名：subFormInt2
    // '引 数 　：なし
    // '戻 り 値：なし
    // '処理説明：画面初期化
    // '**********************************************************************
    function subFormInt2() {
        $(".PPRM100ApproveStateSearch.rdbJyoutai1").prop("checked", true);
        $(".PPRM100ApproveStateSearch.rdbJyokyo1").prop("checked", true);
        $(".PPRM100ApproveStateSearch.rdbKakunin1").prop("checked", true);

        $(".PPRM100ApproveStateSearch.ddlKakunin").val("0");
        listCheck();

        //'テキストボックス
        $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val("");
        $(".PPRM100ApproveStateSearch.txtToTenpoCD").val("");

        // 2017/09/25 CI UPD S
        $(".PPRM100ApproveStateSearch.txtHJMFromDate").datepicker(
            "setDate",
            -6
        );
        $(".PPRM100ApproveStateSearch.txtHJMToDate").datepicker(
            "setDate",
            "null"
        );
        //$(".PPRM100ApproveStateSearch.txtHJMFromDate").val("");
        //$(".PPRM100ApproveStateSearch.txtHJMToDate").val("");
        // 2017/09/25 CI UPD E

        $(".PPRM100ApproveStateSearch.txtHJMNo").val("");
        $(".PPRM100ApproveStateSearch.txtFromSyainCD").val("");
        $(".PPRM100ApproveStateSearch.txtToSyainCD").val("");

        //'ラベル
        $(".PPRM100ApproveStateSearch.lblFromTenpo").val("");
        $(".PPRM100ApproveStateSearch.lblToTenpo").val("");
        $(".PPRM100ApproveStateSearch.lblFromSyain").val("");
        $(".PPRM100ApproveStateSearch.lblToSyain").val("");
        //'スプレッド
        subFormInt();
        //'初期設定
        SetInt();
        //'フォーカス設定
        $(".PPRM100ApproveStateSearch.txtFromTenpoCD").trigger("focus");
    }

    // '**********************************************************************
    // '処 理 名：初期設定
    // '関 数 名：SetInt
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：リストの初期設定を行う
    // '**********************************************************************
    function SetInt() {
        //'セッションから取得
        var strBCD = gdmz.SessionBusyoCD;
        //'部署コードにより判定
        if (strBCD == "122" || strBCD == "125") {
            //'経理担当をセット
            $(".PPRM100ApproveStateSearch.ddlKakunin").val("0");
            listCheck();
        } else {
            //20170908 ZHANGXIAOLEI INS S
            var strKBN = "";
            var url = me.sys_id + "/" + me.id + "/" + "SetInt";
            var arr = {
                strMCD: gdmz.SessionUserId,
            };
            var data = {
                request: arr,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] != false) {
                    //20170908 ZHANGXIAOLEI INS E
                    //'店舗コードセット
                    $(".PPRM100ApproveStateSearch.txtFromTenpoCD").val(
                        gdmz.SessionTenpoCD
                    );
                    $(".PPRM100ApproveStateSearch.txtToTenpoCD").val(
                        gdmz.SessionTenpoCD
                    );
                    //'店舗名セット
                    //20170908 ZHANGXIAOLEI UPD S
                    // getBusyoNM(gdmz.SessionTenpoCD, gdmz.SessionTenpoCD, "", "", true);
                    $(".PPRM100ApproveStateSearch.lblFromTenpo").val(
                        me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                    );
                    $(".PPRM100ApproveStateSearch.lblToTenpo").val(
                        me.FncGetBusyoNM(gdmz.SessionTenpoCD)
                    );

                    if (result["data"].length > 0) {
                        //'区分
                        strKBN = clsComFnc.FncNv(
                            result["data"][0]["SYUKEI_KB"]
                        );
                        var checkStr = padleft(
                            clsComFnc.FncNv(result["data"][0]["BUSYO_CD"]),
                            3
                        ).substr(2, 1);
                        if (strKBN == "1" && checkStr == "0") {
                            //'店長をセット
                            $(".PPRM100ApproveStateSearch.ddlKakunin").val("1");
                            listCheck();
                        } else {
                            //'課長をセット
                            $(".PPRM100ApproveStateSearch.ddlKakunin").val("2");
                            listCheck();
                        }
                    } else {
                        //'担当をセット
                        $(".PPRM100ApproveStateSearch.ddlKakunin").val("3");
                        listCheck();
                    }
                }
            };
            me.ajax.send(url, data, 0);
            //20170908 ZHANGXIAOLEI UPD E
        }
    }

    // '**********************************************************************
    // '処 理 名：事務のchange
    // '関 数 名：rdbTaisyo1_CheckedChanged
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：事務のchange
    // '**********************************************************************
    function rdbTaisyo1_CheckedChanged() {
        if ($(".PPRM100ApproveStateSearch.rdbTaisyo1").is(":checked")) {
            $(".PPRM100ApproveStateSearch.lblTitle2").html("日締日");
            $(".PPRM100ApproveStateSearch.table4").css("display", "block");
            $(".PPRM100ApproveStateSearch.lblTitle3").css("display", "block");
            $(".PPRM100ApproveStateSearch.txtHJMNo").css("display", "block");
            $(".PPRM100ApproveStateSearch.btnHJMSearch").css(
                "display",
                "block"
            );
            $(".PPRM100ApproveStateSearch.btnKinsyuInput").show();
            // '画面設定
            subFormInt2();
        }
    }

    // '**********************************************************************
    // '処 理 名：整備のchange
    // '関 数 名：rdbTaisyo2_CheckedChanged
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：整備のchange
    // '**********************************************************************
    function rdbTaisyo2_CheckedChanged() {
        if ($(".PPRM100ApproveStateSearch.rdbTaisyo2").is(":checked")) {
            $(".PPRM100ApproveStateSearch.lblTitle2").html("売上日");
            $(".PPRM100ApproveStateSearch.table4").css("display", "none");
            $(".PPRM100ApproveStateSearch.lblTitle3").css("display", "none");
            $(".PPRM100ApproveStateSearch.txtHJMNo").css("display", "none");
            $(".PPRM100ApproveStateSearch.btnHJMSearch").css("display", "none");
            $(".PPRM100ApproveStateSearch.btnKinsyuInput").hide();
            // '画面設定
            subFormInt2();
        }
    }

    // '**********************************************************************
    // '処 理 名：確認状況反映
    // '関 数 名：listCheck
    // '引 数   ：なし
    // '戻 り 値：なし
    // '処理説明：確認状況反映（確認状況で選択した値に対するラジオボタンにチェックをつける）
    // '**********************************************************************
    function listCheck() {
        var listIndex = $(".PPRM100ApproveStateSearch.ddlKakunin").val();

        switch (listIndex) {
            case "0":
                $(".PPRM100ApproveStateSearch.rdbKakunin1").prop(
                    "checked",
                    true
                );
                break;
            case "1":
                $(".PPRM100ApproveStateSearch.rdbKakunin2").prop(
                    "checked",
                    true
                );
                break;
            case "2":
                $(".PPRM100ApproveStateSearch.rdbKakunin3").prop(
                    "checked",
                    true
                );
                break;
            case "3":
                $(".PPRM100ApproveStateSearch.rdbKakunin4").prop(
                    "checked",
                    true
                );
                break;
        }
    }

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM100ApproveStateSearch = new PPRM.PPRM100ApproveStateSearch();
    o_PPRM_PPRM100ApproveStateSearch.load();
    o_PPRM_PPRM.PPRM100ApproveStateSearch = o_PPRM_PPRM100ApproveStateSearch;
});
