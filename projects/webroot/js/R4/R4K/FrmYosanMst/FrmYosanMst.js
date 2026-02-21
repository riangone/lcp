/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922           #2162                        BUG                              Yuanjh
 * 20150925           #2109                        BUG                              LI
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmYosanMst");

R4.FrmYosanMst = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4K/FrmYosanMst";
    me.grid_id = "#FrmYosanMst_sprList";
    me.lastsel = 0;
    me.strTougetu = "";
    me.arrInputData = new Array();
    me.option = {
        rowNum: 500000,
        recordpos: "center",
        multiselect: false,
        rownumbers: true,
        caption: "",
        multiselectWidth: 40,
    };
    me.addData = {
        LINE_NO: "",
        UPD_FPG: "",
        YSN_GK10: "",
        YSN_GK11: "",
        YSN_GK12: "",
        YSN_GK1: "",
        YSN_GK2: "",
        YSN_GK3: "",
        YSN_GK4: "",
        YSN_GK5: "",
        YSN_GK6: "",
        YSN_GK7: "",
        YSN_GK8: "",
        YSN_GK9: "",
        CREATE_DATE: "",
        Check_FLAG: "0",
    };

    me.colModel = [
        {
            name: "LINE_NO",
            label: "ラインNo.",
            index: "LINE_NO",
            hidden: true,
        },
        {
            name: "UPD_FPG",
            label: "更新フラグ",
            index: "UPD_FPG",
            hidden: true,
        },
        {
            name: "YSN_GK10",
            label: "10月",
            index: "YSN_GK10",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK11",
            label: "11月",
            index: "YSN_GK11",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK12",
            label: "12月",
            index: "YSN_GK12",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK1",
            label: "1月",
            index: "YSN_GK1",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK2",
            label: "2月",
            index: "YSN_GK2",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK3",
            label: "3月",
            index: "YSN_GK3",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK4",
            label: "4月",
            index: "YSN_GK4",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK5",
            label: "5月",
            index: "YSN_GK5",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK6",
            label: "6月",
            index: "YSN_GK6",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK7",
            label: "7月",
            index: "YSN_GK7",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK8",
            label: "8月",
            index: "YSN_GK8",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "YSN_GK9",
            label: "9月",
            index: "YSN_GK9",
            width: 83,
            sortable: false,
            editable: true,
            align: "right",
            editoptions: {
                class: "numeric",
                maxlength: "10",
                dataEvents: [
                    {
                        type: "keypress",
                        fn: function (e) {
                            if (!me.inputReplace(e.target, 9, e.keyCode)) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function (e) {
                            var inputValue = $(e.target).val();

                            if (inputValue == "-") {
                                $(e.target).val("-0");
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "CREATE_DATE",
            label: "作成日",
            index: "CREATE_DATE",
            hidden: true,
        },
        {
            name: "Check_FLAG",
            label: "判定",
            index: "Check_FLAG",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmYosanMst.cmdSearchBs",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanMst.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanMst.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanMst.cmdClear",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmYosanMst.cboYM",
        //-- 20150922 Yuanjh UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150922 Yuanjh UPD E.
        handle: "",
    });

    //ShiftキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    me.clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmYosanMst.cboYM").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (me.clsComFnc.CheckDate2($(".FrmYosanMst.cboYM")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (me.clsComFnc.CheckDate3($(".FrmYosanMst.cboYM")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmYosanMst.cboYM").val(me.strTougetu.substr(0, 7));
                $(".FrmYosanMst.cboYM").trigger("focus");
                $(".FrmYosanMst.cboYM").trigger("select");
                $(".FrmYosanMst.cmdSearch").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmYosanMst.cmdSearch").button("enable");
        }
    });

    // '**********************************************************************
    // '処理概要：検索ボタン押下時
    // '**********************************************************************
    $(".FrmYosanMst.cmdSearch").click(function () {
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmYosanMst.cmdAction").button("disable");

        //必須ﾁｪｯｸ
        if ($(".FrmYosanMst.txtBusyoCD").val().trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".FrmYosanMst.txtBusyoCD");
            me.clsComFnc.FncMsgBox("W0001", "部署コード");
            return;
        }

        //予算ファイルから抽出
        var url = me.id + "/fncYosanSelect";
        var data = {
            BUSYOCD: $(".FrmYosanMst.txtBusyoCD").val(),
            //-- 20150922 Yuanjh UPD S.
            //"KI" : $(".FrmYosanMst.cboYM").val()
            KI:
                $(".FrmYosanMst.cboYM").val().substr(0, 4) +
                "/" +
                $(".FrmYosanMst.cboYM").val().substr(4, 2),
            //-- 20150922 Yuanjh UPD E.
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                for (var i = 0; i < 82; i++) {
                    $(me.grid_id).jqGrid("addRowData", i, me.addData);
                }

                //スプレッドに取得データをセットする
                var intRow = 0;
                for (var i = 0; i < result["data"].length; i++) {
                    while (true) {
                        if (result["data"][i]["LINE_NO"] == intRow + 1) {
                            break;
                        }
                        intRow += 1;
                    }

                    for (key in result["data"][i]) {
                        result["data"][i][key] = me.clsComFnc
                            .FncNv(result["data"][i][key])
                            .trimEnd();
                    }

                    $(me.grid_id).jqGrid(
                        "setRowData",
                        intRow,
                        result["data"][i]
                    );
                }

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", 0, true);

                //画面設定
                $(".FrmYosanMst.cmdAction").button("enable");
                $(".FrmYosanMst.txtBusyoCD").attr("readonly", "readonly");
                $(".FrmYosanMst.cboYM").attr("disabled", "disabled");
                $(".FrmYosanMst.cmdSearchBs").button("disable");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    });

    // '**********************************************************************
    // '処理概要：クリアボタン押下時
    // '**********************************************************************
    $(".FrmYosanMst.cmdClear").click(function () {
        //画面項目ｸﾘｱ
        me.subFormClear();
        //ｽﾌﾟﾚｯﾄﾞｸﾘｱ
        $(me.grid_id).jqGrid("clearGridData");
        $(".FrmYosanMst.cmdAction").button("disable");
        $(".FrmYosanMst.txtBusyoCD").removeAttr("readonly");
        $(".FrmYosanMst.cboYM").removeAttr("disabled");
        $(".FrmYosanMst.cmdSearchBs").button("enable");
        $(".FrmYosanMst.txtBusyoCD").trigger("focus");
    });

    $(".FrmYosanMst.cmdSearchBs").click(function () {
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($("#FrmYosanMst"));
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($("#FrmYosanMst"));
        $("<div></div>").attr("id", "BUSYONM").insertAfter($("#FrmYosanMst"));
        $("<div></div>").attr("id", "RtnCD").insertAfter($("#FrmYosanMst"));
        $("<div></div>")
            .attr("id", "KKRBusyoCD")
            .insertAfter($("#FrmYosanMst"));

        $("<div></div>").attr("id", "BUSYOCD").hide();
        $("<div></div>").attr("id", "BUSYONM").hide();
        $("<div></div>").attr("id", "RtnCD").hide();
        $("<div></div>").attr("id", "KKRBusyoCD").hide();

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 554 : 680,
            width: 550,
            resizable: false,
            close: function () {
                var flgRtnCD = $("#RtnCD").html();

                if (flgRtnCD == 1) {
                    $(".FrmYosanMst.txtBusyoCD").val($("#BUSYOCD").html());
                    $(".FrmYosanMst.lblBusyoNM").val($("#BUSYONM").html());
                    $(".FrmYosanMst.lblKKR").val($("#KKRBusyoCD").html());
                    $(".FrmYosanMst.cmdSearch").trigger("focus");
                } else {
                    $(".FrmYosanMst.txtBusyoCD").trigger("focus");
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#KKRBusyoCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = "R4K/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);

            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
    });

    // '**********************************************************************
    // '処 理 名：名称取得
    // '関 数 名：txtBusyoCDValidating
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：部署名称を取得する
    // '**********************************************************************
    $(".FrmYosanMst.txtBusyoCD").on("blur", function () {
        if ($(".FrmYosanMst.txtBusyoCD").prop("readonly")) {
            return;
        }
        $(".FrmYosanMst.lblBusyoNM").val("");
        $(".FrmYosanMst.lblKKR").val("");

        if ($(".FrmYosanMst.txtBusyoCD").val().trimEnd() != "") {
            var url = me.id + "/fncGetBusyoMstValue";
            var data = {
                Busyo_CD: $(".FrmYosanMst.txtBusyoCD").val().trimEnd(),
            };

            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] == true) {
                    $(".FrmYosanMst.lblBusyoNM").val(
                        result["data"]["strBusyoNM"]
                    );
                    $(".FrmYosanMst.lblKKR").val(result["data"]["strKKRBusyo"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            };
            me.ajax.send(url, data, 0);
        }
    });

    $(".FrmYosanMst.txtBusyoCD").keydown(function (e) {
        var key = e.charCode || e.keyCode;

        if (key == 222) {
            return false;
        }
    });

    // '**********************************************************************
    // '処理概要：更新ボタン押下時
    // '**********************************************************************
    $(".FrmYosanMst.cmdAction").click(function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");

        //入力チェック
        if (me.fncInputChk() == false) {
            return;
        } else {
            //確認メッセージ
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncDelUpdData;
            me.clsComFnc.FncMsgBox("QY010");
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_load = me.load;
    // '**********************************************************************
    // '処理概要：フォームロード
    // '**********************************************************************
    me.load = function () {
        base_load();

        $(".FrmYosanMst.cmdAction").button("disable");

        var url = me.id + "/frmGetYearMonth";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            var todayDate = new Date();
            $(".FrmYosanMst.cboYM").ympicker("setDate", todayDate);

            if (result["result"] == true) {
                //コントロールマスタが存在してる場合は年度に期首年月を設定
                if (result["data"].length > 0) {
                    //-- 20150922 Yuanjh UPD S.
                    me.strTougetu = me.clsComFnc.FncNv(
                        result["data"][0]["TOUGETU"]
                    );
                    arrTougetu = me.clsComFnc
                        .FncNv(result["data"][0]["TOUGETU"])
                        .split("/");
                    //$(".FrmYosanMst.cboYM").val(me.strTougetu.substr(0, 7));
                    $(".FrmYosanMst.cboYM").val(arrTougetu[0] + arrTougetu[1]);
                    //-- 20150922 Yuanjh UPD E.
                }

                $(".FrmYosanMst.cboYM").trigger("focus");
            } else {
                me.clsComFnc.ObjFocus = $(".FrmYosanMst.cboYM");
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
            //-- 20150922 Yuanjh UPD S.
            //me.strTougetu = $(".FrmYosanMst.cboYM").val() + "/01";
            me.strTougetu = $(".FrmYosanMst.cboYM").val();
            //-- 20150922 Yuanjh UPD E.
            me.subFormClear();
        };
        me.ajax.send(url, "", 1);
    };

    $(me.grid_id).jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        height: me.ratio === 1.5 ? 228 : 261,
        width: me.ratio === 1.5 ? 1020 : 1114,
        colModel: me.colModel,
        rownumbers: true,
        shrinkToFit: me.ratio === 1.5,
        // '**********************************************************************
        // '処理概要：スプレッドセルクリック
        // '**********************************************************************
        onSelectRow: function (rowid, _status, e) {
            if (typeof e != "undefined") {
                //---20150925 li UPD S.
                //{s
                //---20150925 li UPD E.
                var cellIndex =
                    e.target.cellIndex !== undefined
                        ? e.target.cellIndex
                        : e.target.parentElement.cellIndex;

                if (cellIndex != 0) {
                    if (rowid && rowid != me.lastsel) {
                        $(me.grid_id).jqGrid(
                            "saveRow",
                            me.lastsel,
                            null,
                            "clientArray"
                        );
                        me.lastsel = rowid;
                    }

                    $(me.grid_id).jqGrid("editRow", rowid, {
                        keys: true,
                        focusField: cellIndex,
                    });
                } else {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );

                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.delRowData;
                    me.clsComFnc.MessageBox(
                        "削除します、よろしいですか？",
                        me.clsComFnc.GSYSTEM_NAME,
                        "YesNo",
                        "Question",
                        me.clsComFnc.MessageBoxDefaultButton.Button2
                    );
                }
            } else {
                if (rowid && rowid != me.lastsel) {
                    $(me.grid_id).jqGrid(
                        "saveRow",
                        me.lastsel,
                        null,
                        "clientArray"
                    );
                    me.lastsel = rowid;
                }

                $(me.grid_id).jqGrid("editRow", rowid, {
                    keys: true,
                    focusField: false,
                });
            }

            $(".numeric").numeric({
                decimal: false,
                negative: true,
            });

            gdmz.common.jqgrid.setKeybordEvents(me.grid_id, e, me.lastsel);
        },
    });
    //20150820	Yuanjh ADD S.
    $(me.grid_id).jqGrid("bindKeys");
    //20150820	Yuanjh ADD E.

    me.inputReplace = function (targetVal, inputLength, keycode) {
        var inputValue = $(targetVal).val();

        if (inputValue == "" && keycode == 45) {
            $(targetVal).val("-0");
            return false;
        } else if (inputValue.indexOf("-") == -1) {
            if (keycode == 45 && inputValue.length <= inputLength) {
                $(targetVal).val("-" + inputValue);
                return false;
            } else if (inputValue.length == inputLength) {
                if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
                    inputValue =
                        inputValue.substr(0, 1) + (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                } else if (
                    inputValue == "0" &&
                    keycode >= 49 &&
                    keycode <= 57
                ) {
                    inputValue = (keycode - 48).toString();
                    $(targetVal).val(inputValue);
                }

                return false;
            }
        } else {
            if (keycode == 45) {
                $(targetVal).val(inputValue.substr(1));
                return false;
            } else if (keycode >= 48 && keycode <= 57 && inputValue == "-0") {
                $(targetVal).val(
                    inputValue.substr(0, 1) + (keycode - 48).toString()
                );
                return false;
            }
        }

        if (inputValue == "-0" && keycode >= 49 && keycode <= 57) {
            inputValue = inputValue.substr(0, 1) + (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        } else if (inputValue == "0" && keycode >= 49 && keycode <= 57) {
            inputValue = (keycode - 48).toString();
            $(targetVal).val(inputValue);
            return false;
        }

        return true;
    };

    me.subFormClear = function () {
        $(".FrmYosanMst.txtBusyoCD").val("");
        $(".FrmYosanMst.lblBusyoNM").val("");
        $(".FrmYosanMst.lblKKR").val("");
        $(".FrmYosanMst.cboYM").val(me.strTougetu.substr(0, 7));
    };

    me.delRowData = function () {
        var rowID = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var url = me.id + "/frmHYOSANDeleteRow";

        var data = {
            KI: $(".FrmYosanMst.cboYM").val(),
            BUSYOCD: $(".FrmYosanMst.txtBusyoCD").val(),
            LINENO: parseInt(rowID),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                //選択行の内容クリアする
                $(me.grid_id).jqGrid("setRowData", parseInt(rowID), me.addData);

                //選択状態設定する
                $(me.grid_id).jqGrid("setSelection", 0);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };

        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    // '処 理 名：スプレッドの入力チェック
    // '関 数 名：fncInputChk
    // '引    数：lntTeika  (I)定価合計
    // '戻 り 値：True:正常終了 False:異常終了
    // '処理説明：スプレッドの入力チェック
    // '**********************************************************************
    me.fncInputChk = function () {
        var intRtn = 0;
        var blnInputFlg = false;
        var data = $(me.grid_id).jqGrid("getDataIDs");
        me.arrInputData = new Array();

        for (rowID in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rowID);

            //入力されているかどうかの判定用("0":入力されていない　"1"：入力されている)
            rowData["Check_FLAG"] = "0";

            //どれか一列でも入力されていた場合
            if (
                rowData["YSN_GK10"].trimEnd() != "" ||
                rowData["YSN_GK11"].trimEnd() != "" ||
                rowData["YSN_GK12"].trimEnd() != "" ||
                rowData["YSN_GK1"].trimEnd() != "" ||
                rowData["YSN_GK2"].trimEnd() != "" ||
                rowData["YSN_GK3"].trimEnd() != "" ||
                rowData["YSN_GK4"].trimEnd() != "" ||
                rowData["YSN_GK5"].trimEnd() != "" ||
                rowData["YSN_GK6"].trimEnd() != "" ||
                rowData["YSN_GK7"].trimEnd() != "" ||
                rowData["YSN_GK8"].trimEnd() != "" ||
                rowData["YSN_GK9"].trimEnd() != ""
            ) {
                var iColNo = 0;

                //入力チェック
                for (colID in rowData) {
                    switch (colID) {
                        //3-14
                        case "LINE_NO":
                        case "UPD_FPG":
                        case "CREATE_DATE":
                        case "Check_FLAG":
                            break;
                        default:
                            var length =
                                rowData[colID].length > 9 && rowData[colID] > 0
                                    ? 1
                                    : 0;
                            intRtn = me.clsComFnc.FncSprCheck(
                                rowData[colID],
                                0,
                                me.clsComFnc.INPUTTYPE.NUMBER2,
                                me.colModel[iColNo]["editoptions"][
                                    "maxlength"
                                ] - length
                            );
                            break;
                    }

                    if (intRtn != 0) {
                        me.setFocus(rowID, colID);
                        me.clsComFnc.FncMsgBox(
                            "W000" + intRtn * -1,
                            me.colModel[iColNo]["label"].replace(/<br \/>/g, "")
                        );
                        return false;
                    }

                    iColNo += 1;
                }

                //入力されているかどうかの判定用("0":入力されていない　"1"：入力されている)
                rowData["Check_FLAG"] = "1";
                blnInputFlg = true;
            }

            $(me.grid_id).jqGrid("setRowData", rowID, rowData);
            me.arrInputData.push(rowData);
        }

        if (!blnInputFlg) {
            me.setFocus(0, "YSN_GK10");
            me.clsComFnc.FncMsgBox("W0017", "データ");
            return false;
        }

        return true;
    };

    me.setFocus = function (rowID, colID) {
        var rowNum = parseInt(rowID);
        $(me.grid_id).jqGrid("setSelection", rowNum);

        var ceil = rowNum + "_" + colID;
        me.clsComFnc.ObjFocus = $("#" + ceil);
        me.clsComFnc.ObjSelect = $("#" + ceil);
    };

    me.fncDelUpdData = function () {
        var url = me.id + "/fncDelUpdDataMst";
        var sendData = {
            inputData: me.arrInputData,
            KI: $(".FrmYosanMst.cboYM").val(),
            BUSYOCD: $(".FrmYosanMst.txtBusyoCD").val(),
            KKRBUSYO: $(".FrmYosanMst.lblKKR").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            } else {
                //正常終了ﾒｯｾｰｼﾞ
                me.clsComFnc.ObjFocus = $(".FrmYosanMst.txtBusyoCD");
                me.clsComFnc.FncMsgBox("I0008");
                // 画面ｸﾘｱ処理
                me.subFormClear();
                //ｽﾌﾟﾚｯﾄﾞｸﾘｱ
                $(me.grid_id).jqGrid("clearGridData");
                $(".FrmYosanMst.txtBusyoCD").removeAttr("readonly");
                $(".FrmYosanMst.cmdSearchBs").button("enable");
                $(".FrmYosanMst.cboYM").removeAttr("disabled");
                $(".FrmYosanMst.cmdAction").button("disable");
            }
        };
        me.ajax.send(url, JSON.stringify(sendData), 0);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_R4_FrmYosanMst = new R4.FrmYosanMst();
    o_R4_FrmYosanMst.load();
});
