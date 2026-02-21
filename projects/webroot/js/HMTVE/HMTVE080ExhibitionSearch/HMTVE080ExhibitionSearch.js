Namespace.register("HMTVE.HMTVE080ExhibitionSearch");

HMTVE.HMTVE080ExhibitionSearch = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.hmtve = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMTVE080ExhibitionSearch";
    me.sys_id = "HMTVE";

    // ========== 変数 start ==========
    me.grid_id = "#HMTVE080ExhibitionSearchtblMain";
    me.pager = "#HMTVE080ExhibitionSearch_pager";
    me.sidx = "";
    me.g_url = "HMTVE/HMTVE080ExhibitionSearch/btnView_Click";
    me.option = {
        pagerpos: "center",
        viewrecords: false,
        multiselect: false,
        caption: "",
        rowNum: 10,
        rowList: [10, 20, 30],
        rownumbers: false,
        scroll: false,
        autowidth: true,
        pager: me.pager,
    };
    me.colModel = [
        {
            name: "KIKAN",
            label: "展示会開催期間",
            index: "KIKAN",
            width: 200,
            align: "left",
            sortable: false,
            labelClasses: "custom-header",
        },
        {
            name: "IVENT_NM",
            label: "イベント名",
            index: "IVENT_NM",
            width: 300,
            align: "left",
            sortable: false,
            labelClasses: "custom-header",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //表示ボタン
    me.controls.push({
        id: ".HMTVE080ExhibitionSearch.btnView",
        type: "button",
        handle: "",
    });

    //選択ボタン
    me.controls.push({
        id: ".HMTVE080ExhibitionSearch.btnSel",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HMTVE080ExhibitionSearch.btnClose",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.hmtve.TabKeyDown(me.id);

    //Enterキーのバインド
    me.hmtve.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：表示ボタン押下時
    $(".HMTVE080ExhibitionSearch.btnView").click(function () {
        me.btnView_Click();
    });
    //処理説明：選択ボタン押下時
    $(".HMTVE080ExhibitionSearch.btnSel").click(function () {
        me.btnSel_Click();
    });
    //処理説明：戻るボタン押下時
    $(".HMTVE080ExhibitionSearch.btnClose").click(function () {
        $(".HMTVE080ExhibitionSearch.body").dialog("close");
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.HMTVE080ExhibitionSearch_load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HMTVE080ExhibitionSearch_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HMTVE080ExhibitionSearch_load = function () {
        //初期設定処理
        $(".HMTVE080ExhibitionSearch.body").dialog({
            autoOpen: false,
            height: me.ratio === 1.5 ? 470 : 491,
            width: me.ratio === 1.5 ? 587 : 600,
            modal: true,
            title: "展示会検索",
            open: function () {},
            close: function () {
                me.before_close();
                $(".HMTVE080ExhibitionSearch.body").remove();
            },
        });

        $(".HMTVE080ExhibitionSearch.body").dialog("open");
        $(".HMTVE080ExhibitionSearch.pnlList").hide();
        //システム日付を取得する
        var url = me.sys_id + "/" + me.id + "/" + "GetSysDate";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //システム日付を取得する
            var dtSysdate = new Date(result["data"]);

            if (dtSysdate != undefined) {
                //年
                var intYear = dtSysdate.getFullYear();
                var Yeararray = new Array();

                Yeararray[0] = intYear + 1;
                Yeararray[1] = intYear;
                Yeararray[2] = intYear - 1;
                Yeararray[3] = intYear - 2;
                Yeararray[4] = intYear - 3;
                Yeararray[5] = intYear - 4;

                for (var index = 0; index < Yeararray.length; index++) {
                    $("<option></option>")
                        .val(Yeararray[index])
                        .text(Yeararray[index])
                        .appendTo(".HMTVE080ExhibitionSearch.ddlExhibitDay");
                }

                //現在の年をデフォルトで設定します。
                $(".HMTVE080ExhibitionSearch.ddlExhibitDay").val(intYear);

                //月
                for (var i = 1; i <= 12; i++) {
                    i = i < 10 ? "0" + i : i;
                    $("<option></option>")
                        .val(i)
                        .text(i)
                        .appendTo(".HMTVE080ExhibitionSearch.mmExhibitDay");
                }

                //デフォルトの値を現在の月に設定します。
                $(".HMTVE080ExhibitionSearch.mmExhibitDay").val(
                    dtSysdate.getMonth() + 1 < 10
                        ? "0" + (dtSysdate.getMonth() + 1)
                        : dtSysdate.getMonth() + 1
                );
            }
        };

        me.ajax.send(url, data, 0);

        gdmz.common.jqgrid.init2(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );

        //フォーカスの設定
        $(".HMTVE080ExhibitionSearch.ddlExhibitDay").trigger("focus");
        $(".HMTVE080ExhibitionSearch.btnSel").hide();

        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function () {
                //OSelectRowは現在選択されている行を取得し、行を選択したら、選択ボタンが表示されます。
                $(".HMTVE080ExhibitionSearch.btnSel").show();
            },
            //ページをめくる事件
            onPaging: function () {
                $(".HMTVE080ExhibitionSearch.btnSel").hide();
            },
        });

        gdmz.common.jqgrid.set_grid_width(me.grid_id, 536);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 263);

        //KEYDOWN
        $(me.grid_id).jqGrid("setGridParam", {
            ondblClickRow: function () {
                //選択値の設定
                me.btnSel_Click();
            },
        });
        $(me.grid_id).jqGrid("bindKeys", {
            onEnter: function () {
                //選択値の設定
                me.btnSel_Click();
            },
        });

        $("#RtnCD").html("-1");
    };

    me.before_close = function () {};

    //'**********************************************************************
    //'処 理 名：表示ボタンクリック
    //'関 数 名：btnView_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：表示ボタンの処理
    //'**********************************************************************
    me.btnView_Click = function () {
        $(".HMTVE080ExhibitionSearch.pnlList").hide();

        var data = {
            ddlExhibitDay: $(".HMTVE080ExhibitionSearch.ddlExhibitDay").val(),
            mmExhibitDay: $(".HMTVE080ExhibitionSearch.mmExhibitDay").val(),
        };

        var complete_fun = function (returnFLG, result) {
            $(".HMTVE080ExhibitionSearch.btnSel").hide();
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (returnFLG == "nodata") {
                $(".HMTVE080ExhibitionSearch.ddlExhibitDay").trigger("focus");
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                $(".ui-jqgrid .ui-jqgrid-pager .ui-pager-table").css(
                    "table-layout",
                    "auto"
                );
                $(".HMTVE080ExhibitionSearch.pnlList").show();
                $(".HMTVE080ExhibitionSearch.ddlExhibitDay").trigger("focus");
            }
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };

    //'**********************************************************************
    //'処 理 名：展示会グリッド行選択のイベント
    //'関 数 名：close
    //'戻 り 値：なし
    //'処理説明：展示会グリッド行選択の処理
    //'**********************************************************************
    me.btnSel_Click = function () {
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (rowid != null) {
            //選択値の設定
            if (me.FncSetRtnData() != true) {
                return;
            }

            //閉じる
            $(".HMTVE080ExhibitionSearch.body").dialog("close");
        }
    };

    //**********************************************************************
    //処 理 名：選択データの設定
    //関 数 名：FncSetRtnData
    //引    数：無し
    //戻 り 値：True ：正常
    //       　False：異常
    //処理説明：選択したデータを構造体に設定する。
    //**********************************************************************
    me.FncSetRtnData = function () {
        var selectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", selectRow);
        if (rowData && $.trim(rowData["KIKAN"]) != "") {
            var sysDate = rowData["KIKAN"];
            var sysDates = sysDate.split("～");
            var kikanStart = sysDates[0];
            var kikanEnd = sysDates[1];

            //リターン値
            $("#RtnCD").html("1");
            $("#lblETStart").html(kikanStart);
            $("#lblETEnd").html(kikanEnd);
        } else {
            return false;
        }

        return true;
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMTVE_HMTVE080ExhibitionSearch = new HMTVE.HMTVE080ExhibitionSearch();

    if (o_HMTVE_HMTVE.HMTVE040InputDataS) {
        o_HMTVE_HMTVE.HMTVE040InputDataS.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE050TotalS) {
        o_HMTVE_HMTVE.HMTVE050TotalS.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE030InputDataK) {
        o_HMTVE_HMTVE.HMTVE030InputDataK.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE060TotalKShop) {
        o_HMTVE_HMTVE.HMTVE060TotalKShop.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE070TotalKHonbu) {
        o_HMTVE_HMTVE.HMTVE070TotalKHonbu.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE100AttendanceControl) {
        o_HMTVE_HMTVE.HMTVE100AttendanceControl.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE200PresentOrderBase) {
        o_HMTVE_HMTVE.HMTVE200PresentOrderBase.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE210PresentOrderEntry) {
        o_HMTVE_HMTVE.HMTVE210PresentOrderEntry.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }
    if (o_HMTVE_HMTVE.HMTVE220PresentOrderTotal) {
        o_HMTVE_HMTVE.HMTVE220PresentOrderTotal.HMTVE080ExhibitionSearch =
            o_HMTVE_HMTVE080ExhibitionSearch;
    }

    o_HMTVE_HMTVE080ExhibitionSearch.load();
});
