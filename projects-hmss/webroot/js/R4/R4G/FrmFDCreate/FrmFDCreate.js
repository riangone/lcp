/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                                      担当
 * YYYYMMDD            #ID                          XXXXXX                                                   GSDL
 * 20201119            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。  LQS
 * 20201120              bug                    データが多い時、jqgridに横向スクロールがあります。               LQS
 * * ----------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmFDCreate");

R4.FrmFDCreate = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmFDCreate";
    me.sys_id = "R4G";

    me.class_id = ".FrmFDCreate.body";
    me.parent_class_id = ".R4.R4-layout-center";

    me.grid_id = "#" + me.id + "_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fnc" + me.id;
    me.getAllData_url = me.sys_id + "/" + me.id + "/" + "fncGetAllData";
    me.pager = "#div" + me.id + "_pager";
    me.sidx = "INP_FLG";
    me.option = {
        pagerpos: "left",
        multiselect: true,
        //20201120 lqs del S
        // multiselectWidth : 150,
        //20201120 lqs del E
        rownumbers: true,
        rowNum: 500000,
        caption: "",
        multiselectWidth: 30,
        shrinkToFit: me.ratio === 1.5,
    };

    me.colModel = [
        {
            name: "FD_CRE",
            label: "F D<br>作成",
            index: "FD_CRE",
            width: "35",
            formatter: "checkbox",
            sortable: false,
            align: "center",
        },
        {
            name: "INP_FLG",
            label: "補完<br>入力",
            index: "INP_FLG",
            formatter: "checkbox",
            width: "35",
            align: "center",
            sortable: false,
        },
        {
            name: "KATASIKI",
            label: "型式類別",
            index: "KATASIKI",
            width: "90",
            sortable: false,
        },
        {
            name: "CARNO",
            label: "車台番号",
            index: "CARNO",
            width: "120",
            sortable: false,
        },
        {
            name: "SHI_USER_NM",
            label: "氏名",
            index: "SHI_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            name: "SHI_ADDRESS",
            label: "住所",
            index: "SHI_ADDRESS",
            width: "170",
            sortable: false,
        },
        {
            name: "SYO_USER_NM",
            label: "氏名",
            index: "SYO_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            name: "SYO_ADDRESS",
            label: "住所",
            index: "SYO_ADDRESS",
            width: "170",
            sortable: false,
        },
        {
            name: "CHUMN_NO",
            label: "注文書番号",
            index: "CHUMN_NO",
            width: "100",
            key: true,
            sortable: false,
        },
        {
            name: "TOU_Y_DT",
            label: "登録予定日",
            index: "TOU_Y_DT",
            width: "90",
            hidden: true,
            sortable: false,
        },
    ];

    me.intState = 0;
    me.gridTFCacheArr = new Array();
    me.selectedGridData = new Array();
    me.selectedAllGridData = new Array();
    me.selectAllFlag = true;
    me.nowDate1 = new Date();
    me.nowDate2 =
        me.nowDate1.getFullYear() +
        "/" +
        me.nowDate1.getMonth() +
        "/" +
        me.nowDate1.getDate();
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmFDCreate.button_action",
        type: "button",
        enable: "false",
        handle: "",
    });

    me.controls.push({
        id: ".FrmFDCreate.button_search",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmFDCreate.search_touroku_inputText",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmFDCreate.button_action").click(function () {
        $(".FrmFDCreate.button_action").button("disable");
        me.fnc_cmdCsvOut_Click();
    });
    $(".FrmFDCreate.button_search").click(function () {
        me.subSpreadRe_reload();
    });
    $(".FrmFDCreate.search_touroku_inputText").on("blur", function () {
        if (
            clsComFnc.CheckDate($(".FrmFDCreate.search_touroku_inputText")) ==
            false
        ) {
            var myDate = new Date();

            $(".FrmFDCreate.search_touroku_inputText").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201119 lqs upd S
            // $(".FrmFDCreate.search_touroku_inputText").focus();
            // $(".FrmFDCreate.search_touroku_inputText").select();
            window.setTimeout(function () {
                $(".FrmFDCreate.search_touroku_inputText").trigger("focus");
                $(".FrmFDCreate.search_touroku_inputText").select();
            }, 0);
            // 20201119 lqs upd E
            $(".FrmFDCreate.button_search").button("disable");
            return false;
        } else {
            $(".FrmFDCreate.button_search").button("enable");
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_load = me.load;

    me.load = function () {
        base_load();
        me.FrmFDCreate_Load();
    };

    me.FrmFDCreate_Load = function () {
        // 画面項目ｸﾘｱ
        me.subFormClear();
        //'スプレッドを表示
        me.subSpreadRe_show();
    };

    /*
	 ********************************
	 '処 理 名：画面項目初期化
	 '関 数 名：subFormClear
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：画面項目を初期化する
	 ********************************
	 */
    me.subFormClear = function () {
        var currentDay = new Date();
        var data1 =
            currentDay.getFullYear +
            currentDay.getMonth +
            (currentDay.getDate + 1);
        $(".FrmFDCreate.search_touroku_inputText").datepicker("setDate", data1);
        $(".FrmFDCreate.search_misakusei_inputCheck").prop("checked", false);
        $(me.grid_id).clearGridData();
    };

    /*
	 ********************************
	 '処 理 名：データグリッドの再表示
	 '関 数 名：subSpreadRe_show
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：データグリッドを再表示する
	 '*******************************
	 */
    me.subSpreadRe_show = function () {
        me.selectedAllGridData = {};
        me.gridTFCacheArr = {};
        tmpdata = {
            Touroku: $(".FrmFDCreate.search_touroku_inputText").val(),
            Misakusei: $(".FrmFDCreate.search_misakusei_inputCheck").prop(
                "checked"
            ),
        };

        $(".FrmFDCreate.search_touroku_inputText").trigger("focus");
        me.complete_fun = function () {
            me.fncCompleteDeal(false);
        };
        gdmz.common.jqgrid.show(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option,
            tmpdata,
            me.complete_fun
        );

        $(me.grid_id).jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "SHI_USER_NM",
                    numberOfColumns: 2,
                    titleText: "使用者",
                },
                {
                    startColumnName: "SYO_USER_NM",
                    numberOfColumns: 2,
                    titleText: "所有者",
                },
            ],
        });
        //20201120 lqs upd S
        // gdmz.common.jqgrid.set_grid_width(me.grid_id, 1050);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1030 : 1065
        );
        //20201120 lqs upd E
        //20180305 ciyuanchen UPD S
        // gdmz.common.jqgrid.set_grid_height(me.grid_id, 270);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 208 : 250
        );
        //20180305 ciyuanchen UPD E
        $("#jqgh_FrmFDCreate_sprList_FD_CRE").css("top", "4px");
        $("#jqgh_FrmFDCreate_sprList_INP_FLG").css("top", "4px");
    };

    /*
	 ********************************
	 '処 理 名：データグリッドの再表示
	 '関 数 名：subSpreadRe_reload
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：データグリッドを再表示する
	 '*******************************
	 */
    me.subSpreadRe_reload = function () {
        $(me.grid_id).clearGridData();
        me.selectedAllGridData = {};
        me.gridTFCacheArr = {};

        tmpdata = {
            Touroku: $(".FrmFDCreate.search_touroku_inputText").val(),
            Misakusei: $(".FrmFDCreate.search_misakusei_inputCheck").prop(
                "checked"
            ),
        };
        me.complete_fun_1 = function () {
            if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
            } else {
                clsComFnc.ObjFocus = $(".FrmFDCreate.search_touroku_inputText");
                clsComFnc.FncMsgBox("I0001");
                $(".FrmFDCreate.button_action").button("disable");
                return;
            }
            me.fncCompleteDeal(true);
        };
        gdmz.common.jqgrid.reload(me.grid_id, tmpdata, me.complete_fun_1);
    };

    /*
	 ********************************
	 '処 理 名：選択レコードの方法
	 '関 数 名：SelectRow_fun
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：選択レコードの方法
	 '*******************************
	 */
    me.SelectRow_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function () {
                ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
                if (ids.length > 0) {
                    $(".FrmFDCreate.button_action").button("enable");
                } else {
                    $(".FrmFDCreate.button_action").button("disable");
                }
            },
        });
        me.selectAllFlag = false;
    };

    /*
	 ********************************
	 '処 理 名：全て選択の方法
	 '関 数 名：SelectAllRow_fun
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：全て選択の方法
	 '*******************************
	 */
    me.SelectAllRow_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectAll: function (_rowIds, status) {
                var tmpID = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
                if (status == false) {
                    $(".FrmFDCreate.button_action").button("disable");
                } else if (status == true && tmpID != 0) {
                    $(".FrmFDCreate.button_action").button("enable");
                }
            },
        });
    };

    /*
	 ********************************
	 '処 理 名：jqGridロード完成の執行方法
	 '関 数 名：fncCompleteDeal
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：jqGridロード完成の執行方法
	 '*******************************
	 */
    me.fncCompleteDeal = function (bFlagStart) {
        if (!bFlagStart) {
            $("#jqgh_FrmFDCreate_sprList_cb").append("<br/>対象");
        }

        var rowArray = $(me.grid_id).jqGrid("getGridParam", "records");

        var rowIds = $(me.grid_id).jqGrid("getDataIDs");
        me.selectAllFlag = true;
        for (key in rowIds) {
            $(me.grid_id).jqGrid("setSelection", rowIds[key]);
        }
        if (rowArray < 0) {
            if (bFlagStart) {
                clsComFnc.ObjFocus = $(".FrmFDCreate.search_touroku_inputText");
                clsComFnc.FncMsgBox("I0001");
            } else {
                $(".FrmFDCreate.search_touroku_inputText").trigger("focus");
            }

            $(".FrmFDCreate.button_action").button("disable");
            return;
        }
        me.SelectRow_fun();
        me.SelectAllRow_fun();

        $(me.grid_id).jqGrid("resetSelection");

        for (key in rowIds) {
            var tmpRowData = $(me.grid_id).jqGrid("getRowData", rowIds[key]);
            var cssprop = {
                background: "red",
            };
            if (tmpRowData["INP_FLG"] == "No") {
                $(me.grid_id).jqGrid("setRowData", rowIds[key], false, cssprop);
                me.gridTFCacheArr[rowIds[key]] = false;
            } else {
                $(me.grid_id).jqGrid("setSelection", rowIds[key]);
                me.gridTFCacheArr[rowIds[key]] = true;
            }
        }
        var tmpID = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        if (tmpID.length > 0) {
            $(".FrmFDCreate.button_action").button("enable");
        } else {
            $(".FrmFDCreate.button_action").button("disable");
        }
    };

    /*
	 ***************************
	 '処 理 名：CSV出力
	 '関 数 名：fnc_cmdCsvOut_Click
	 '引    数：無し
	 '戻 り 値：SQL文
	 '処理説明：振替データをCSV出力する
	 '******************************
	 */
    me.fnc_cmdCsvOut_Click = function () {
        var fncCsvOut_Click = me.sys_id + "/" + me.id + "/fncCmdCsvOutClick";
        var FDChumnno = new Array();
        var selectedGridData_count = 0;
        var strTouroku = $(".FrmFDCreate.search_touroku_inputText").val();

        //エラーチェック

        var tmpID = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        for (key in tmpID) {
            var rowData = $(me.grid_id).jqGrid("getRowData", tmpID[key]);
            me.selectedAllGridData[tmpID[key]] = rowData;
        }

        for (var key in me.selectedAllGridData) {
            selectedGridData_count++;
        }

        if (selectedGridData_count > 0) {
            var idss = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
            for (key in idss) {
                var tmpRowData_ = $(me.grid_id).jqGrid("getRowData", idss[key]);
                FDChumnno.push(tmpRowData_["CHUMN_NO"]);
                if (tmpRowData_["INP_FLG"] != "Yes") {
                    clsComFnc.FncMsgBox(
                        "W9999",
                        "補完入力が行われていません。補完入力を行ってからFD作成を行ってください！"
                    );
                    var t = document.getElementById(idss[key]);
                    var t1 = document.getElementsByClassName("ui-jqgrid-view");
                    var tbody =
                        document.getElementsByClassName("ui-jqgrid-bdiv");
                    tbody[0].scrollTop =
                        parseInt(t.offsetTop) - parseInt(t1[0].offsetTop);
                    return;
                }
            }
        }

        var tmpStrTouroku = "";
        var arrStrTouroku = strTouroku.split("/");
        tmpStrTouroku =
            me.convert_wareki(arrStrTouroku[0], true) +
            "年" +
            arrStrTouroku[1].toZenkaku() +
            "月" +
            arrStrTouroku[2].toZenkaku() +
            "日";
        //CSV出力

        var data = "";
        data = {
            strTouroku: tmpStrTouroku,
            FDChumnno: FDChumnno,
        };
        var ajax1 = new gdmz.common.ajax();
        ajax1.receive = function (response) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + response + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["TF"] == true) {
                window.open(jsonResult.json[0]["report_path"]);
                clsComFnc.FncMsgBox(jsonResult.json[0]["msg"]);
                me.subSpreadRe_reload();
            }
            if (jsonResult.json[0]["TF"] == false) {
                var ii = 0;
                for (key in jsonResult.json[0]) {
                    ii++;
                }
                if (ii == 3) {
                    clsComFnc.ObjFocus = $(
                        ".FrmFDCreate.search_touroku_inputText"
                    );
                    clsComFnc.FncMsgBox(
                        jsonResult.json[0]["msg"],
                        jsonResult.json[0]["msgContent"]
                    );
                } else {
                    clsComFnc.ObjFocus = $(
                        ".FrmFDCreate.search_touroku_inputText"
                    );
                    clsComFnc.FncMsgBox(jsonResult.json[0]["msg"]);
                }
            }
            $(".FrmFDCreate.button_action").button("enable");
        };
        ajax1.send(fncCsvOut_Click, data, 0);
        ajax1.beforeLogin = me.buttonable;
    };
    me.buttonable = function () {
        $(".FrmFDCreate.button_action").button("enable");
    };
    me.convert_wareki = function (y, b) {
        var tmp;
        //bがfalseの場合、西暦をそのまま返す
        if (b == false) {
            return y;
        }
        //令和
        if (y > 2018) {
            tmp = y - 2018;
            tmp = "令和" + tmp.toString().toZenkaku();
            return tmp;
        }
        //平成
        if (y > 1988) {
            tmp = y - 1988;
            tmp = "平成" + tmp.toString().toZenkaku();
            return tmp;
        }
        //昭和
        if (y > 1925) {
            tmp = y - 1925;
            tmp = "昭和" + tmp.toString().toZenkaku();
            return tmp;
        }
        //大正
        if (y > 1911) {
            tmp = y - 1911;
            tmp = "大正" + tmp.toString().toZenkaku();
            return tmp;
        }
        //明治
        if (y > 1867) {
            tmp = y - 1867;
            tmp = "明治" + tmp.toString().toZenkaku();
            return tmp;
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFDCreate = new R4.FrmFDCreate();
    o_R4_FrmFDCreate.load();
});
