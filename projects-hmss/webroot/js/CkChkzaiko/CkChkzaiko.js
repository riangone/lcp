/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("CkChkzaiko.CkChkzaiko");

CkChkzaiko.CkChkzaiko = function () {
    var me = new gdmz.base.panel();
    var ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.arrList_CMN_NO = new Array();
    me.id = "CkChkzaiko/CkChkzaiko";
    me.sys_id = "CkChkzaiko";
    //----jqGrid 変数 start----
    me.grid_id = "#CkChkzaiko_sprList";
    me.g_url = "CkChkzaiko/CkChkzaiko/fncCkChkzaikoSelect";
    me.pager = "#CkChkzaiko_pager";
    //me.sidx = "CMN_NO";
    me.data = new Array();
    //----jqGrid 変数  end----
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "中古車在庫管理";

    // 自動検索フラグ
    var Ck_timeoutHnd;
    var Ck_flAuto = true;
    currentTabId = me.sys_id;

    me.option = {
        pagerpos: "center",
        recordpos: "right",
        multiselect: true,
        rownumbers: true,
        rowNum: 30,
        rowList: [10, 20, 30, 40, 50],
        caption: "一覧",
        multiselectWidth: 30,
        pager: me.pager,
        //loadui:"disable"
        //sortorder : "desc"
    };

    me.colModel = [
        {
            label: "整理No",
            name: "CKO_CAR_SER_NO",
            index: "CKO_CAR_SER_NO",
            width: "100",
        },
        {
            label: "UCNO",
            name: "UC_NO",
            index: "UC_NO",
            width: "120",
        },
        {
            label: "注文書No",
            name: "CMN_NO",
            index: "CMN_NO",
            width: "100",
        },
        {
            label: "中古車No",
            name: "CHUKOSYA_NO",
            index: "CHUKOSYA_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "車種",
            name: "VCLNM",
            index: "VCLNM",
            width: "100",
        },
        {
            label: "登録No陸自名",
            name: "TOU_NO_RKJ_NM",
            index: "TOU_NO_RKJ_NM",
            width: me.ratio === 1.5 ? 110 : 120,
        },
        {
            label: "登録No-種別",
            name: "VCLRGTNO_SYU",
            index: "VCLRGTNO_SYU",
            width: me.ratio === 1.5 ? 110 : 120,
        },
        {
            label: "登録No-カナ",
            name: "TOU_NO_KNA",
            index: "TOU_NO_KNA",
            width: me.ratio === 1.5 ? 110 : 120,
        },
        {
            label: "登録No-連番",
            name: "TOU_NO_RBN",
            index: "TOU_NO_RBN",
            width: me.ratio === 1.5 ? 110 : 120,
        },
        {
            label: "下買取先",
            name: "USR_NM",
            index: "USR_NM",
            width: "100",
        },
        {
            label: "印刷日時",
            name: "OUT_PUT_DTM",
            index: "OUT_PUT_DTM",
            width: "100",
        },
        {
            label: "下取車シーケンスNo",
            name: "TRA_CAR_SEQ_NO",
            index: "TRA_CAR_SEQ_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "銘柄コード",
            name: "BRD_CD",
            index: "BRD_CD",
            width: "100",
            hidden: true,
        },
        {
            label: "銘柄名",
            name: "MEIGARA_MEI",
            index: "MEIGARA_MEI",
            width: "100",
            hidden: true,
        },
        {
            label: "年製",
            name: "SYD_TOU_YM",
            index: "SYD_TOU_YM",
            width: "100",
            hidden: true,
        },
        {
            label: "認可型式",
            name: "NINKATA_CD",
            index: "NINKATA_CD",
            width: "100",
            hidden: true,
        },
        {
            label: "車体No",
            name: "CAR_NO",
            index: "CAR_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "査定価格",
            name: "SATEI_GK",
            index: "SATEI_GK",
            width: "100",
            hidden: true,
        },
        {
            label: "登録日",
            //20140218 yushuangji edit start
            //--name : 'SAT_DT',
            //--index:'SAT_DT',
            //2014-02-25 修正 START 登録日を REC_CRE_DT から TOU_DTに修正
            //name : 'REC_CRE_DT',
            //index : 'REC_CRE_DT',
            name: "TOU_DT",
            index: "TOU_DT",
            //2014-02-25 修正 END 登録日を REC_CRE_DT から TOU_DTに修正
            //20140218 yushuangji edit end
            width: "100",
            hidden: true,
        },
        {
            label: "部署",
            //20140218 yushuangji edit start
            //--name : 'KYOTN_NM',
            //--index : 'KYOTN_NM',
            //20140218 yushuangji edit end
            name: "KYOTN_RKN",
            index: "KYOTN_RKN",
            width: "100",
            hidden: true,
        },
        {
            label: "扱者姓",
            name: "SYAIN_KNJ_SEI",
            index: "SYAIN_KNJ_SEI",
            width: "100",
            hidden: true,
        },
        {
            label: "扱者名",
            name: "SYAIN_KNJ_MEI",
            index: "SYAIN_KNJ_MEI",
            width: "100",
            hidden: true,
        },
        {
            label: "型式指定番号",
            name: "SITEI_NO",
            index: "SITEI_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "類別区分",
            name: "RUIBETU_NO",
            index: "RUIBETU_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "受入価格",
            name: "TRA_GK",
            index: "TRA_GK",
            width: "100",
            hidden: true,
        },
        {
            label: "部署コード",
            name: "HNB_KTN_CD",
            index: "HNB_KTN_CD",
            width: "100",
            hidden: true,
        },
        {
            label: "扱者コード",
            name: "HNB_TAN_EMP_NO",
            index: "HNB_TAN_EMP_NO",
            width: "100",
            hidden: true,
        },

        //20140218 yushuangji edit start
        /*{
	label : '車両状態コード',
	name : 'SYR_JT_CD',
	index : 'SYR_JT_CD',
	width : '100',
	hidden : true
	},
	*/
        //20140218 yushuangji edit end
        {
            label: "リサイクル券No",
            name: "RCYL_KEN_NO",
            index: "RCYL_KEN_NO",
            width: "100",
            hidden: true,
        },
        {
            label: "リサイクル預託金合計額",
            name: "YOTAK_GK",
            index: "YOTAK_GK",
            width: "100",
            hidden: true,
        },
        {
            label: "リサイクル料金合計額",
            name: "RCYL_GK",
            index: "RCYL_GK",
            width: "100",
            hidden: true,
        },
        {
            label: "シュレッダーダスト料金",
            name: "ASR_RYOKIN",
            index: "ASR_RYOKIN",
            width: "100",
            hidden: true,
        },
        {
            label: "エアバッグ類料金",
            name: "AIRBUG_RYOKIN",
            index: "AIRBUG_RYOKIN",
            width: "100",
            hidden: true,
        },
        {
            label: "フロン類料金",
            name: "FULON_RYOKIN",
            index: "FULON_RYOKIN",
            width: "100",
            hidden: true,
        },
        {
            label: "情報管理料金",
            name: "JOHO_KNR_RYOKIN",
            index: "JOHO_KNR_RYOKIN",
            width: "100",
            hidden: true,
        },
        {
            label: "資金管理料金",
            name: "SHIKIN_KNR_RYOKIN",
            index: "SHIKIN_KNR_RYOKIN",
            width: "100",
            hidden: true,
        },
        {
            label: "エアバック類装備有無",
            name: "AIRBUG_EQU_UM",
            index: "AIRBUG_EQU_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "フロン類装備有無",
            name: "FULON_EQU_UM",
            index: "FULON_EQU_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "シュレッダーダスト預託有無",
            name: "ASR_YOTAK_UM",
            index: "ASR_YOTAK_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "エアバック類預託有無",
            name: "AIRBUG_YOTAK_UM",
            index: "AIRBUG_YOTAK_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "フロン類預託有無",
            name: "FULON_YOTAK_UM",
            index: "FULON_YOTAK_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "情報管理預託有無",
            name: "JOHO_KNR_YOTAK_UM",
            index: "JOHO_KNR_YOTAK_UM",
            width: "100",
            hidden: true,
        },
        {
            label: "抹消登録手続き代行費用",
            name: "MSY_TOU_TTK_DAIKO_HYO",
            index: "MSY_TOU_TTK_DAIKO_HYO",
            width: "100",
            hidden: true,
        },
        {
            label: "抹消登録預かり法定費用",
            name: "MSY_TOU_AZK_HTE_HYO",
            index: "MSY_TOU_AZK_HTE_HYO",
            width: "100",
            hidden: true,
        },
        {
            label: "使用済自動車処理費用",
            name: "SIY_SMI_CAR_SYR_HYO",
            index: "SIY_SMI_CAR_SYR_HYO",
            width: "100",
            hidden: true,
        },
        {
            label: "印刷済みフラグ",
            name: "OUT_PUT_FLG",
            index: "OUT_PUT_FLG",
            width: "100",
            hidden: true,
        },
        {
            label: "印刷ユーザID",
            name: "OUT_PUT_ID",
            index: "OUT_PUT_ID",
            width: "100",
            hidden: true,
        },
        {
            label: "SHZ_RT",
            name: "SHZ_RT",
            index: "SHZ_RT",
            width: "100",
            hidden: true,
        },
        {
            label: "SHZ_KB",
            name: "SHZ_KB",
            index: "SHZ_KB",
            width: "100",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".CkChkzaiko.comment_demo",
        type: "button",
        handle: "",
        icons: "ui-icon-print",
    });
    me.controls.push({
        id: ".CkChkzaiko.comment_demo_SVG",
        type: "button",
        handle: "",
        icons: "ui-icon-print",
    });
    me.controls.push({
        id: ".CkChkzaiko.txt_ck_datepickerFrom",
        type: "datepicker1",
        handle: "",
    });
    me.controls.push({
        id: ".CkChkzaiko.txt_ck_datepickerTo",
        type: "datepicker1",
        handle: "",
    });
    //PDF dialog
    $(".CkChkzaiko.div_ck_PrintDialog_PDF").dialog({
        autoOpen: false,
        width: 860,
        height: me.ratio === 1.5 ? 480 : 620,
        modal: true,
        resizable: false,
        title: "詳細表示",
        //dialogClass : 'closeHide',
        buttons: [
            {
                id: "print_p",
                text: "印刷",
                click: function () {
                    console.log(this);
                    //$(".ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-text-only").button("disable");
                    $("#print_p").button("disable");
                    me.print_ck_chkzaiko("fncOutput");
                },
            },
        ],
        /*{
		 "印刷" : function()
		 {
		 $(".closeHide .ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-text-only").button("disable");
		 me.print_ck_chkzaiko("fncOutput");
		 //$( '.CkChkzaiko.div_ck_PrintDialog_PDF' ).dialog( "option","buttons", "disabled", true );
		 }
		 }
		 */
    });

    // ========== コントロール end ==========
    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    $(".CkChkzaiko.chk_ck_showPrinted").click(function () {
        me.ck_gridReload();
    });

    $(".CkChkzaiko.txt_ck_datepickerFrom").change(function () {
        me.ck_gridReload();
    });

    $(".CkChkzaiko.txt_ck_datepickerTo").change(function () {
        me.ck_gridReload();
    });

    $(".CkChkzaiko.comment_demo").click(function () {
        me.fun_comment_demo();
    });

    $(".CkChkzaiko.comment_demo_SVG").click(function () {
        me.print_ck_chkzaiko("fncOutputSVG");
    });

    var base_load = me.load;

    me.load = function () {
        base_load();
        me.load_show_fun();
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    me.load_show_fun = function () {
        // init();
        //init datepicker
        //2014-03-01 修正 START システム日付前日の計算結果不正を修正
        ////2014-02-25 修正 START 日付表示初期値をシステム日付前日に修正
        ////var currentDay = new Date();
        ////var data1 = currentDay.getFullYear().toString() + "-" + currentDay.getMonth().toString() + "-" + (currentDay.getDate() - 1).toString();
        ////var data2 = currentDay.getFullYear().toString() + "-" + currentDay.getMonth().toString() + "-" + (currentDay.getDate()).toString();
        ////2014-02-25 修正 END 日付表示初期値をシステム日付前日に修正
        //var data1 = currentDay.getFullYear().toString() + "-" + ('0' + (currentDay.getMonth() + 1).toString()).slice(-2) + "-" + ('0' + (currentDay.getDate() - 1)).toString().slice(-2);
        //var data2 = currentDay.getFullYear().toString() + "-" + ('0' + (currentDay.getMonth() + 1).toString()).slice(-2) + "-" + ('0' + (currentDay.getDate() - 1)).toString().slice(-2);
        var currentDay = new Date();
        currentDay.setDate(currentDay.getDate() - 1);
        var data1 =
            currentDay.getFullYear().toString() +
            "-" +
            ("0" + (currentDay.getMonth() + 1).toString()).slice(-2) +
            "-" +
            ("0" + currentDay.getDate()).toString().slice(-2);
        var data2 = data1;
        //2014-03-01 修正 END システム日付前日の計算結果不正を修正

        $(".CkChkzaiko.txt_ck_datepickerFrom").val(data1);
        $(".CkChkzaiko.txt_ck_datepickerTo").val(data1);

        me.complete_fun = function () {
            $(".CkChkzaiko.comment_demo").button("disable");
            $(".CkChkzaiko.comment_demo_SVG").button("disable");
            me.selectRowFun();
        };

        tmpdata = {
            search_key: "",
            checkTF: "",
            preDate: data1,
            nextDate: data2,
        };
        gdmz.common.jqgrid.show_2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1230 : 1280
        );
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 270);

        $(".ui-pg-input").css("width", "30px");
    };

    /*
     * 自動検索
     */
    me.doSearch = function () {
        if (!Ck_flAuto) {
            return;
        }
        if (Ck_timeoutHnd) {
            clearTimeout(Ck_timeoutHnd);
        }
        Ck_timeoutHnd = setTimeout(me.ck_gridReload, 500);
    };

    me.ck_gridReload = function () {
        var preDate = "";
        var nextDate = "";

        preDate = $(".CkChkzaiko.txt_ck_datepickerFrom").val();
        nextDate = $(".CkChkzaiko.txt_ck_datepickerTo").val();
        if (preDate != "" && nextDate != "") {
            if (preDate > nextDate) {
                return false;
            }
        }

        var checkTF = "";

        if ($(".CkChkzaiko.chk_ck_showPrinted").prop("checked")) {
            checkTF = $(".CkChkzaiko.chk_ck_showPrinted").prop("checked");
        }

        tmpdata = {
            search_key: "", //search_key,
            checkTF: checkTF,
            preDate: preDate,
            nextDate: nextDate,
        };
        me.complete_fun = function () {
            me.CloseLoading();
            me.selectRowFun();
        };
        me.ShowLoading();
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun);
    };

    me.ShowLoading = function () {
        // $.blockUI();
        $.blockUI({
            css: {
                border: "none",
                padding: "10px",
                backgroundColor: "#fff",
                "-webkit-border-radius": "8px",
                "-moz-border-radius": "8px",
                top: "45%",
                left: "40%",
                color: "#000",
                width: "200px",
            },
            message:
                '<img src="img/1.gif" width="64" height="64" /><br /><B>読み込み中...</B>',
            bindEvents: false,
        });
    };

    me.CloseLoading = function () {
        $.unblockUI();
    };

    me.selectRowFun = function () {
        var ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        me.selRowLength = ids.length;
        me.buttonSituation();
        //select single
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (_rowId, _status, _e) {
                var ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
                me.selRowLength = ids.length;
                me.buttonSituation();
            },
        });
        //select all
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectAll: function (aRowids, status) {
                if ($(me.grid_id).jqGrid("getGridParam", "reccount") === 0) {
                    me.selRowLength = 0;
                    return;
                }
                if (status) {
                    me.selRowLength = aRowids.length;
                } else {
                    me.selRowLength = 0;
                }
                me.buttonSituation();
            },
        });
    };

    me.buttonSituation = function () {
        switch (me.selRowLength) {
            case 0:
                //all disable
                $(".CkChkzaiko.comment_demo").button("disable");
                $(".CkChkzaiko.comment_demo_SVG").button("disable");
                break;
            default:
                //all enable
                $(".CkChkzaiko.comment_demo").button("enable");
                $(".CkChkzaiko.comment_demo_SVG").button("enable");
                break;
        }
    };

    me.print_ck_chkzaiko = function (urlName) {
        //
        var data = "";
        var url = me.id + "/" + urlName;

        var ck_ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var selectedCk_chkzaikoArr = new Array();
        if (ck_ids.length > 0) {
            for (idx in ck_ids) {
                var data = $(me.grid_id).jqGrid("getRowData", ck_ids[idx]);
                selectedCk_chkzaikoArr.push(data);
                me.arrList_CMN_NO.push(data["CMN_NO"]);
            }
        }

        data = {
            selectedCk_chkzaikoArr: selectedCk_chkzaikoArr,
        };
        ajax.receive = function (response) {
            console.log(selectedCk_chkzaikoArr);
            if (url == "CkChkzaiko/CkChkzaiko/fncOutput") {
                //20140219 yushuangji add end
                me.fnc_insert_ck_chkzaiko(data, response);
                //window.open(response);
            } else if (url == "CkChkzaiko/CkChkzaiko/fncOutputSVG") {
                // 印刷ダイアログ
                $(".CkChkzaiko.div_ck_PrintArea").html(response);
                $(".CkChkzaiko.div_ck_PrintDialog").css(
                    "visibility",
                    "visible"
                );
                $(".CkChkzaiko.div_ck_PrintDialog").css("display", "block");
                $(".CkChkzaiko.div_ck_PrintDialog").dialog("open");
                // var buttons = $(".CkChkzaiko.div_ck_PrintDialog").dialog(
                //     "option",
                //     "buttons"
                // );
            }
        };
        ajax.send(url, data, 0);
    };

    me.ck_confirm_printout = function (list_CMN_NO) {
        $(".CkChkzaiko.div_ck_confirmDialog").css("visibility", "visible");
        $(".CkChkzaiko.div_ck_confirmDialog").dialog({
            resizable: false,
            height: me.ratio === 1.5 ? 100 : 190,
            modal: true,
            buttons: {
                Ok: function () {
                    var url = me.id + "/fncConfirm";
                    var data = {
                        list_CMN_NO: list_CMN_NO,
                    };

                    ajax.receive = function (response) {
                        var jsonResult = {};
                        var txtResult = '{ "json" : [' + response + "]}";
                        jsonResult = eval("(" + txtResult + ")");

                        if (jsonResult.json[0]["result"] == true) {
                            $(".CkChkzaiko.div_ck_confirmDialog").dialog(
                                "close"
                            );
                            me.ck_gridReload();
                        }
                    };
                    ajax.send(url, data, 0);
                },
                Cancel: function () {
                    $(".CkChkzaiko.div_ck_confirmDialog").dialog("close");
                },
            },
        });
    };

    me.fun_comment_demo = function () {
        //20140219 yushuangji edit start
        //fnc_insert_ck_chkzaiko
        var urlName = "fncOutPutPDF_Single";
        var data = "";
        var url = me.id + "/" + urlName;
        var ck_ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var selectedCk_chkzaikoArr = new Array();
        if (ck_ids.length > 0) {
            for (idx in ck_ids) {
                var data = $(me.grid_id).jqGrid("getRowData", ck_ids[idx]);
                selectedCk_chkzaikoArr.push(data["CMN_NO"]);
                me.arrList_CMN_NO.push(data["CMN_NO"]);
            }
        }

        data = {
            selectedCk_chkzaikoArr: JSON.stringify(selectedCk_chkzaikoArr),
        };
        ajax.receive = function (response) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + response + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == true) {
                var heightValue = me.ratio === 1.5 ? 400 : 510;
                $(".CkChkzaiko.div_ck_PrintArea_PDF").html(
                    "<embed src='" +
                        jsonResult.json[0]["data"] +
                        "' style='height:" +
                        heightValue +
                        "px;' width='820'></embed>"
                );

                $(".CkChkzaiko.div_ck_PrintDialog_PDF").dialog("open");
                //$(".closeHide .ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-text-only").button("enable");
                $("#print_p").button("enable");
            } else {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
            }
        };
        ajax.send(url, data, 0);
        //20140219 yushuangji edit end
    };

    shortcut.add("F1", function () {
        if (
            currentTabId == "#tabs_ck_chkzaiko" ||
            currentTabId == "CkChkzaiko"
        ) {
            var ck_ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
            if (ck_ids.length > 0) {
                me.shortCut(currentTabId, "F1");
            }
        }
    });

    me.shortCut = function (selTabIdStr, shortCut) {
        if (selTabIdStr == "CkChkzaiko" || selTabIdStr == "#tabs_ck_chkzaiko") {
            selTabIdStr = "ck_chkzaiko";
        }

        switch (shortCut) {
            case "F1": {
                switch (selTabIdStr) {
                    case "ck_chkzaiko":
                        //me.print_ck_chkzaiko("fncOutput");
                        me.fun_comment_demo();
                        break;
                }
                break;
            }

            case "F2": {
                switch (selTabIdStr) {
                    case "ck_chkzaiko":
                        //me.print_ck_chkzaiko("fncOutputSVG");
                        //me.fun_comment_demo();
                        break;
                }
                break;
            }
        }
    };

    me.fnc_insert_ck_chkzaiko = function (data, path) {
        var url1 = "CkChkzaiko/CkChkzaiko/fncInsertCk";
        ajax.receive = function (response) {
            var txtResult = '{ "json" : [' + response + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                //window.open(jsonResult.json[0]['report_path']);
                //clsComFnc.FncMsgBox(jsonResult.json[0]['data']);
                //me.subSpreadRe_reload();
            } else {
                me.ck_gridReload();
                path = path.replace(/\\\//g, "/").replace(/"/g, ""); // 同时去掉反斜杠和双引号
                window.open(path);
            }
        };
        ajax.send(url1, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_CkChkzaiko_CkChkzaiko = new CkChkzaiko.CkChkzaiko();
    o_CkChkzaiko_CkChkzaiko.load();
});
