/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE110PublicityOrderBase");

HMTVE.HMTVE110PublicityOrderBase = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE110PublicityOrderBase";
    me.hmtve = new HMTVE.HMTVE();

    // jqgrid
    me.grid_id = "#HMTVE110PublicityOrderBase_tblMain";
    me.grid_id1 = "#HMTVE110PublicityOrderBase_tblMain1";
    me.flg_reload = false;
    me.lastsel = "";
    me.lastsel1 = "";
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: true,
        rownumWidth: 40,
        multiselect: false,
        colModel: me.colModel,
        pager: me.pager, //分页容器
        recordpos: "right",
        datatype: "json",
    };
    me.colModel = [
        {
            name: "NO",
            label: "NO",
            index: "NO",
            width: 20,
            align: "center",
            hidden: true,
            sortable: false,
        },
        {
            name: "HINMEI1",
            label: "品名",
            index: "HINMEI1",
            width: me.ratio === 1.5 ? 250 : 302,
            align: "left",
            editable: true,
            sortable: false,
            editoptions: {
                maxlength: "50",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
        {
            name: "TANKA1",
            label: "単価",
            index: "TANKA1",
            width: 75,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                class: "width align_right",
                maxlength: "6",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    me.option1 = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        rownumWidth: 40,
        loadui: "disable",
        multiselect: false,
        colModel: me.colModel1,
        datatype: "json",
    };
    me.colModel1 = [
        {
            name: "KIKAN",
            label: "展示会開催期間",
            index: "KIKAN",
            width: 167,
            align: "left",
            sortable: false,
        },
        {
            name: "IVENT_NM",
            label: "イベント名",
            index: "IVENT_NM",
            width: 193,
            align: "left",
            sortable: false,
        },
        {
            name: "BIKOU",
            label: "備考",
            index: "BIKOU",
            width: 157,
            align: "left",
            sortable: false,
            editable: true,
            editoptions: {
                class: "width2",
                maxlength: "100",
                dataEvents: [
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                e.preventDefault();
                                e.stopPropagation();
                            }
                        },
                    },
                ],
            },
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE110PublicityOrderBase.button",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMTVE110PublicityOrderBase.txtDate",
        type: "datepicker",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =objdrShopSya
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //登録ボタンクリック
    $(".HMTVE110PublicityOrderBase.btnLogin").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnLogin_Click;
        me.clsComFnc.FncMsgBox("QY999", "登録します。よろしいですか？");
    });

    //削除ボタンクリック
    $(".HMTVE110PublicityOrderBase.btnDel").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDel_Click;
        me.clsComFnc.FncMsgBox("QY999", "削除します。よろしいですか？");
    });

    //表示ボタンクリック
    $(".HMTVE110PublicityOrderBase.btnETSearch").click(function () {
        me.btnETSearch_Click();
    });
    $(".HMTVE110PublicityOrderBase.ddlYear").change(function () {
        me.ddlYear_SelectedIndexChanged();
    });
    $(".HMTVE110PublicityOrderBase.ddlMonth").change(function () {
        me.ddlMonth_SelectedIndexChanged();
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
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        //表示設定
        me.pageClear();
        var url = me.sys_id + "/" + me.id + "/" + "fncpageload";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                if (result["error"] == "E9999") {
                    me.clsComFnc.MsgBoxBtnFnc.OK = me.open090ExhibitionEntry;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.open090ExhibitionEntry;
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "展示会が設定されていません。先に展示会データ登録を行ってください！"
                    );
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    $(".HMTVE110PublicityOrderBase.btnETSearch").button(
                        "disable"
                    );
                    return;
                }
            } else {
                //コンボリストに日付を設定する
                me.ddl_YMSet(result["data"]["getYM"]);
                me.fncJqgrid();
                $(".HMTVE110PublicityOrderBase.ddlYear").trigger("focus");
            }
        };
        me.ajax.send(url, "", 0);
    };
    me.open090ExhibitionEntry = function () {
        o_HMTVE_HMTVE.FrmHMTVEMainMenu.blnFlag = false;
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "deselect_node",
            "#HMTVE110PublicityOrderBase"
        );
        $(".FrmHMTVEMainMenu.Menu").jstree(
            "select_node",
            "#HMTVE090ExhibitionEntry"
        );
    };
    // '**********************************************************************
    // '処 理 名：日付コンボリストの設定
    // '関 数 名：ddl_YMSet
    // '引 数 １：objdr
    // '戻 り 値：なし
    // '処理説明：日付コンボリストの設定します
    // '**********************************************************************
    me.ddl_YMSet = function (objdr) {
        try {
            var max =
                objdr["IVENTMAX"].substring(0, 4) +
                "/" +
                objdr["IVENTMAX"].substring(4, 6) +
                "/" +
                objdr["IVENTMAX"].substring(6, 8);
            var min =
                objdr["IVENTMIN"].substring(0, 4) +
                "/" +
                objdr["IVENTMIN"].substring(4, 6) +
                "/" +
                objdr["IVENTMIN"].substring(6, 8);
            var td = objdr["TD"];
            for (
                var index = parseInt(max.substring(0, 4));
                index >= parseInt(min.substring(0, 4));
                index--
            ) {
                $("<option></option>")
                    .val(index)
                    .text(index)
                    .appendTo(".HMTVE110PublicityOrderBase.ddlYear");
            }
            if (min <= td && td <= max) {
                $(".HMTVE110PublicityOrderBase.ddlYear").val(
                    td.substring(0, 4)
                );
            }
            if (max < td) {
                $(".HMTVE110PublicityOrderBase.ddlYear").val(
                    max.substring(0, 4)
                );
            }
            if (min > td) {
                $(".HMTVE110PublicityOrderBase.ddlYear").val(
                    min.substring(0, 4)
                );
            }
            for (var index = 1; index <= 12; index++) {
                value = "" + index;
                if (index < 10) {
                    value = "0" + index;
                }
                $("<option></option>")
                    .val(value)
                    .text(value)
                    .appendTo(".HMTVE110PublicityOrderBase.ddlMonth");
            }
            if (min <= td && td <= max) {
                $(".HMTVE110PublicityOrderBase.ddlMonth").val(
                    td.substring(5, 7)
                );
            }
            if (max < td) {
                $(".HMTVE110PublicityOrderBase.ddlMonth").val(
                    max.substring(5, 7)
                );
            }
            if (min > td) {
                $(".HMTVE110PublicityOrderBase.ddlMonth").val(
                    min.substring(5, 7)
                );
            }
        } catch (ex) {
            console.log(ex);
        }
    };
    me.fncJqgrid = function () {
        var data = {
            ddlYear: $(".HMTVE110PublicityOrderBase.ddlYear").val(),
            ddlMonth: $(".HMTVE110PublicityOrderBase.ddlMonth").val(),
        };
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                me.btnExSearchClear();
                return;
            } else {
                if (returnFLG == "nodata") {
                    //該当するデータは存在しません。
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                } else {
                    //edit cell
                    $(me.grid_id).jqGrid("setGridParam", {
                        //選択行の修正画面を呼び出す
                        onSelectRow: function (rowId, _status, e) {
                            if (typeof e != "undefined") {
                                //編集可能なセルをクリック、上下キー
                                var cellIndex =
                                    e.target.cellIndex !== undefined
                                        ? e.target.cellIndex
                                        : e.target.parentElement.cellIndex;
                                //ヘッダークリック以外
                                if (cellIndex != 0) {
                                    if (rowId && rowId != me.lastsel) {
                                        $(me.grid_id).jqGrid(
                                            "saveRow",
                                            me.lastsel,
                                            null,
                                            "clientArray"
                                        );
                                        me.lastsel = rowId;
                                    }
                                    $(me.grid_id).jqGrid("editRow", rowId, {
                                        keys: true,
                                        focusField: cellIndex,
                                    });
                                }
                            } else {
                                //tab、enter、tab+shift
                                if (rowId && rowId != me.lastsel) {
                                    $(me.grid_id).jqGrid(
                                        "saveRow",
                                        me.lastsel,
                                        null,
                                        "clientArray"
                                    );
                                    me.lastsel = rowId;
                                }
                                $(me.grid_id).jqGrid("editRow", rowId, {
                                    keys: true,
                                    focusField: false,
                                });
                            }
                            gdmz.common.jqgrid.setKeybordEvents(
                                me.grid_id,
                                e,
                                rowId
                            );
                            $(me.grid_id).find(".width").css("width", "91%");
                            //靠右
                            $(me.grid_id)
                                .find(".align_right")
                                .css("text-align", "right");
                        },
                        //ヘッダー選択を無効にする
                        beforeSelectRow: function (_rowid, e) {
                            var cellIndex = e.target.cellIndex;
                            if (cellIndex == 0) {
                                setTimeout(() => {
                                    var selNextId =
                                        "#" + me.lastsel + "_HINMEI1";
                                    $(selNextId).trigger("focus");
                                    $(selNextId).select();
                                }, 0);
                                return false;
                            }
                            return true;
                        },
                    });
                    $(me.grid_id1).jqGrid("setGridParam", {
                        //選択行の修正画面を呼び出す
                        onSelectRow: function (rowId, _status, e) {
                            if (typeof e != "undefined") {
                                var cellIndex = e.target.cellIndex;
                                //ヘッダークリック以外
                                if (cellIndex != 0) {
                                    if (rowId && rowId != me.lastsel1) {
                                        $(me.grid_id1).jqGrid(
                                            "saveRow",
                                            me.lastsel1,
                                            null,
                                            "clientArray"
                                        );
                                        me.lastsel1 = rowId;
                                    }
                                    $(me.grid_id1).jqGrid(
                                        "editRow",
                                        rowId,
                                        true
                                    );
                                }
                            } else {
                                if (rowId && rowId != me.lastsel1) {
                                    $(me.grid_id1).jqGrid(
                                        "saveRow",
                                        me.lastsel1,
                                        null,
                                        "clientArray"
                                    );
                                    me.lastsel1 = rowId;
                                }
                                $(me.grid_id1).jqGrid("editRow", rowId, {
                                    keys: true,
                                    focusField: false,
                                });
                            }
                            gdmz.common.jqgrid.setKeybordEvents(
                                me.grid_id1,
                                e,
                                rowId
                            );
                            $(me.grid_id1).find(".width2").css("width", "96%");
                        },
                        //ヘッダー選択を無効にする
                        beforeSelectRow: function (_rowid, e) {
                            var cellIndex = e.target.cellIndex;
                            if (cellIndex == 0) {
                                setTimeout(() => {
                                    var selNextId =
                                        "#" + me.lastsel1 + "_BIKOU";
                                    $(selNextId).trigger("focus");
                                }, 0);
                                return false;
                            }
                            return true;
                        },
                    });
                    me.url =
                        me.sys_id + "/" + me.id + "/" + "btnETSearch_Click";
                    var data = {
                        ddlYear: $(".HMTVE110PublicityOrderBase.ddlYear").val(),
                        ddlMonth: $(
                            ".HMTVE110PublicityOrderBase.ddlMonth"
                        ).val(),
                    };
                    me.ajax.receive = function (result) {
                        var result = eval("(" + result + ")");

                        if (result["result"] == false) {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                            me.btnExSearchClear();
                            return;
                        } else {
                            if (result["data"]["getDate"]["data"].length != 0) {
                                var data =
                                    result["data"]["getDate"]["data"][0][
                                        "KIGEN_YM"
                                    ];
                                $(".HMTVE110PublicityOrderBase.txtDate").val(
                                    data.substring(0, 4) +
                                        "/" +
                                        data.substring(4, 6) +
                                        "/" +
                                        data.substring(6, 8)
                                );
                                $(".HMTVE110PublicityOrderBase.txtTime").val(
                                    result["data"]["getDate"]["data"][0][
                                        "CREATE_DATE"
                                    ]
                                );
                            } else {
                                $(".HMTVE110PublicityOrderBase.txtDate").val(
                                    ""
                                );
                                $(".HMTVE110PublicityOrderBase.txtTime").val(
                                    ""
                                );
                            }
                            me.creatExDetailTable(
                                result["data"]["getExDetailGrdView"]["data"]
                            );
                            me.btnExSearchPageSet();
                        }
                    };
                    me.ajax.send(me.url, data, 1);
                }
            }
        };
        if (me.flg_reload) {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id1,
                data,
                complete_fun
            );
            $("#HMTVE110PublicityOrderBase_tblMain_rn").html("NO");
        } else {
            var url = me.sys_id + "/" + me.id + "/" + "getExGrdView";
            gdmz.common.jqgrid.init2(
                me.grid_id1,
                url,
                me.colModel1,
                me.pager,
                "",
                me.option1
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id1, 550);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_id1,
                me.ratio === 1.5 ? 157 : 170
            );
            $(me.grid_id1).jqGrid("bindKeys");
            var url2 = me.sys_id + "/" + me.id + "/" + "getExDetailGrdView";
            gdmz.common.jqgrid.init(
                me.grid_id,
                url2,
                me.colModel,
                me.pager,
                "",
                me.option
            );
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                me.ratio === 1.5 ? 400 : 450
            );
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 78);
            $(me.grid_id).jqGrid("bindKeys");
        }
    };
    //'**********************************************************************
    //'処 理 名：表示ボタンのイベント
    //'関 数 名：btnETSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：取得データをグリッドビューにバインドする
    //'**********************************************************************
    me.btnETSearch_Click = function () {
        me.btnExSearchClear();
        me.flg_reload = true;
        me.fncJqgrid();
    };
    //'**********************************************************************
    //'処 理 名：データソースを生成する
    //'関 数 名：creatExDetailTable
    //'引    数：objdr　データソース
    //'戻 り 値：無し
    //'処理説明：指定されたデータソースを生成する
    //'**********************************************************************
    me.creatExDetailTable = function (objdr) {
        $(me.grid_id).jqGrid("clearGridData");

        if (objdr && objdr.length > 0) {
            $(me.grid_id).jqGrid("addRowData", 0, {});
            $(me.grid_id).jqGrid("setCell", 0, "NO", 1);
            $(me.grid_id).jqGrid("setCell", 0, "HINMEI1", objdr[0]["HINMEI1"]);
            $(me.grid_id).jqGrid("setCell", 0, "TANKA1", objdr[0]["TANKA1"]);
            $(me.grid_id).jqGrid("addRowData", 1, {});
            $(me.grid_id).jqGrid("setCell", 1, "NO", 2);
            $(me.grid_id).jqGrid("setCell", 1, "HINMEI1", objdr[0]["HINMEI2"]);
            $(me.grid_id).jqGrid("setCell", 1, "TANKA1", objdr[0]["TANKA2"]);
            $(me.grid_id).jqGrid("addRowData", 2, {});
            $(me.grid_id).jqGrid("setCell", 2, "NO", 3);
            $(me.grid_id).jqGrid("setCell", 2, "HINMEI1", objdr[0]["HINMEI3"]);
            $(me.grid_id).jqGrid("setCell", 2, "TANKA1", objdr[0]["TANKA3"]);
        } else {
            $(me.grid_id).jqGrid("addRowData", 0, {});
            $(me.grid_id).jqGrid("setCell", 0, "NO", 1);
            $(me.grid_id).jqGrid("setCell", 0, "HINMEI1", "");
            $(me.grid_id).jqGrid("setCell", 0, "TANKA1", "");
            $(me.grid_id).jqGrid("addRowData", 1, {});
            $(me.grid_id).jqGrid("setCell", 1, "NO", 2);
            $(me.grid_id).jqGrid("setCell", 1, "HINMEI1", "");
            $(me.grid_id).jqGrid("setCell", 1, "TANKA1", "");
            $(me.grid_id).jqGrid("addRowData", 2, {});
            $(me.grid_id).jqGrid("setCell", 2, "NO", 3);
            $(me.grid_id).jqGrid("setCell", 2, "HINMEI1", "");
            $(me.grid_id).jqGrid("setCell", 2, "TANKA1", "");
        }
        $(me.grid_id).jqGrid("setSelection", 0, true);
        $(me.grid_id1).jqGrid("setSelection", 0, true);
    };
    // '**********************************************************************
    // '処 理 名：入力チェック
    // '関 数 名：btnLoginCheck
    // '引　　数：無し
    // '戻 り 値：なし
    // '処理説明：入力チェック
    // '**********************************************************************
    me.btnLoginCheck = function () {
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            var txtHINMEI1 = $.trim(rowdata["HINMEI1"]);
            var txtTANKA1 = $.trim(rowdata["TANKA1"]);
            //品名・単価設定テーブルの桁数チェック
            if (me.clsComFnc.GetByteCount(txtHINMEI1) > 50) {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_HINMEI1");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "品名は指定されている桁数をオーバーしています。"
                );
                return false;
            }
            if (me.clsComFnc.GetByteCount(txtTANKA1) > 6) {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA1");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "単価は指定されている桁数をオーバーしています。"
                );
                return false;
            }
            //単価又は品名の必須チェック
            if (txtHINMEI1 != "" && txtTANKA1 == "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA1");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "品名を入力した場合は単価も入力してください。"
                );
                return false;
            }
            if (txtHINMEI1 == "" && txtTANKA1 != "") {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_HINMEI1");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "単価を入力した場合は品名も入力してください。"
                );
                return false;
            }
        }
        // '回収期限のチェック
        // '回収期限の必須チェック(txtTANKA1))
        if ($(".HMTVE110PublicityOrderBase.txtDate").val() == "") {
            $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            me.clsComFnc.ObjFocus = $(".HMTVE110PublicityOrderBase.txtDate");
            me.clsComFnc.FncMsgBox("W9999", "回収期限を入力してください。");
            return false;
        }
        //回収期限の桁数チェック
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE110PublicityOrderBase.txtDate").val())
            ) > 10
        ) {
            $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            me.clsComFnc.ObjFocus = $(".HMTVE110PublicityOrderBase.txtDate");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "回収期限は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE110PublicityOrderBase.txtDate").val())
            ) < 10
        ) {
            $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            me.clsComFnc.ObjFocus = $(".HMTVE110PublicityOrderBase.txtDate");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "入力されている値が不正です。回収期限は「YYYY/MM/DD」書式のようにご入力ください。"
            );
            return false;
        }
        //回収期限の整合性チェック
        if (
            me.clsComFnc.CheckDate($(".HMTVE110PublicityOrderBase.txtDate")) ==
            false
        ) {
            $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            me.clsComFnc.ObjFocus = $(".HMTVE110PublicityOrderBase.txtDate");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "入力されている値が不正です。回収期限は「YYYY/MM/DD」書式のようにご入力ください。"
            );
            return false;
        }
        var txtDate = $(".HMTVE110PublicityOrderBase.txtDate").val();
        if (
            $.trim(txtDate).substring(4, 5) != "/" ||
            $.trim(txtDate).substring(7, 8) != "/"
        ) {
            $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
            me.clsComFnc.ObjFocus = $(".HMTVE110PublicityOrderBase.txtDate");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "入力されている値が不正です。回収期限は「YYYY/MM/DD」書式のようにご入力ください。"
            );
            return false;
        }

        $(me.grid_id1).jqGrid("saveRow", me.lastsel1, null, "clientArray");
        var ids = $(me.grid_id1).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id1).jqGrid("getRowData", ids[i]);
            var txtRemark = $.trim(rowdata["BIKOU"]);
            //品名・単価設定テーブルの桁数チェック
            if (me.clsComFnc.GetByteCount(txtRemark) > 100) {
                $(me.grid_id1).jqGrid("setSelection", ids[i], true);
                $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_BIKOU");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "備考は指定されている桁数をオーバーしています。"
                );
                return false;
            }
        }
        //整合性チェック
        $(me.grid_id).jqGrid("saveRow", me.lastsel, null, "clientArray");
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            rowdata = $(me.grid_id).jqGrid("getRowData", ids[i]);
            var txtPrice = $.trim(rowdata["TANKA1"]);
            var patt = /^-?[1-9][0-9]*$/g;
            if (
                (txtPrice.match(patt) == null && txtPrice != "") ||
                txtPrice.indexOf(".") >= 0 ||
                txtPrice.indexOf("-") >= 0 ||
                txtPrice.indexOf("+") >= 0 ||
                me.clsComFnc.GetByteCount(txtPrice) != txtPrice.length
            ) {
                $(me.grid_id).jqGrid("setSelection", ids[i], true);
                me.clsComFnc.ObjFocus = $("#" + ids[i] + "_TANKA1");
                $(me.grid_id1).jqGrid("setSelection", me.lastsel1, true);
                me.clsComFnc.FncMsgBox("W9999", "入力されている値が不正です。");
                return false;
            }
        }
        var rowdata1 = $(me.grid_id).jqGrid("getRowData", 0);
        var rowdata2 = $(me.grid_id).jqGrid("getRowData", 1);
        var rowdata3 = $(me.grid_id).jqGrid("getRowData", 2);
        if (
            $.trim(rowdata1["HINMEI1"]) == "" &&
            $.trim(rowdata1["TANKA1"]) == "" &&
            $.trim(rowdata2["HINMEI1"]) == "" &&
            $.trim(rowdata2["TANKA1"]) == "" &&
            $.trim(rowdata3["HINMEI1"]) == "" &&
            $.trim(rowdata3["TANKA1"]) == ""
        ) {
            $(me.grid_id).jqGrid("setSelection", 0, true);
            me.clsComFnc.ObjFocus = $("#0_HINMEI1");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "品名、単価が入力されておりません。"
            );
            return false;
        }
        return true;
    };
    //'**********************************************************************
    //'処 理 名：登録ボタンのイベント
    //'関 数 名：btnLogin_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：データ追加する
    //'**********************************************************************
    me.btnLogin_Click = function () {
        if (!me.btnLoginCheck()) {
            return;
        }
        me.url = me.sys_id + "/" + me.id + "/" + "btnLogin_Click";
        var rowdatas = $(me.grid_id1).jqGrid("getRowData");
        var rowdatas2 = $(me.grid_id).jqGrid("getRowData");
        var data = {
            arr: rowdatas,
            ddlYear: $(".HMTVE110PublicityOrderBase.ddlYear").val(),
            ddlMonth: $(".HMTVE110PublicityOrderBase.ddlMonth").val(),
            txtTime: $(".HMTVE110PublicityOrderBase.txtTime").val(),
            txtName1: rowdatas2[0]["HINMEI1"],
            txtPrice1: rowdatas2[0]["TANKA1"],
            txtName2: rowdatas2[1]["HINMEI1"],
            txtPrice2: rowdatas2[1]["TANKA1"],
            txtName3: rowdatas2[2]["HINMEI1"],
            txtPrice3: rowdatas2[2]["TANKA1"],
            txtDate: $(".HMTVE110PublicityOrderBase.txtDate").val(),
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.setSelect();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE110PublicityOrderBase.ddlYear"
                );
                me.clsComFnc.FncMsgBox("I0016");
                me.PageSet();
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：削除ボタンのイベント
    //'関 数 名：btnDel_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：データを削除する
    //'**********************************************************************
    me.btnDel_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/" + "btnDel_Click";
        var data = {
            ddlYear: $(".HMTVE110PublicityOrderBase.ddlYear").val(),
            ddlMonth: $(".HMTVE110PublicityOrderBase.ddlMonth").val(),
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //削除が完了しました
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE110PublicityOrderBase.ddlYear"
                );
                me.clsComFnc.FncMsgBox("I0017");
                me.PageSet();
            }
        };
        me.ajax.send(me.url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：ページクリアする
    // '関 数 名：pageClear
    // '引　　数：無し
    // '戻 り 値：なし
    // '処理説明：ページクリアする
    // '**********************************************************************
    me.pageClear = function () {
        $(".HMTVE110PublicityOrderBase.View1").hide();
        $(".HMTVE110PublicityOrderBase.View2").hide();
        $(".HMTVE110PublicityOrderBase.ddlYear").val("");
        $(".HMTVE110PublicityOrderBase.ddlMonth").val("");
    };
    // '**********************************************************************
    // '処 理 名：表示ボタンのクリアする
    // '関 数 名：btnExSearchClear
    // '引　　数：無し
    // '戻 り 値：なし
    // '処理説明：表示ボタンのクリアする
    // '**********************************************************************
    me.btnExSearchClear = function () {
        $(".HMTVE110PublicityOrderBase.View1").hide();
        $(".HMTVE110PublicityOrderBase.View2").hide();
    };
    me.setSelect = function () {
        $(me.grid_id1).jqGrid("setSelection", me.lastsel1, true);
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
    };

    // '**********************************************************************
    // '処 理 名：表示ボタンの画面制御
    // '関 数 名：btnExSearchPageSet
    // '引　　数：無し
    // '戻 り 値：なし
    // '処理説明：表示ボタンの画面制御
    // '**********************************************************************
    me.btnExSearchPageSet = function () {
        $(".HMTVE110PublicityOrderBase.View1").show();
        $(".HMTVE110PublicityOrderBase.View2").show();
    };
    // '**********************************************************************
    // '処 理 名：登録ボタンの画面制御
    // '関 数 名：PageSet
    // '引　　数：無し
    // '戻 り 値：なし
    // '処理説明：登録ボタンの画面制御
    // '**********************************************************************
    me.PageSet = function () {
        $(".HMTVE110PublicityOrderBase.View1").hide();
        $(".HMTVE110PublicityOrderBase.View2").hide();
    };
    // '**********************************************************************
    // '処 理 名：コンボリストのインデックスの変換処理
    // '関 数 名：ddlYear_SelectedIndexChanged
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：コンボリストのインデックスの変換処理
    // '**********************************************************************
    me.ddlYear_SelectedIndexChanged = function () {
        me.PageSet();
    };
    // '**********************************************************************
    // '処 理 名：コンボリストのインデックスの変換処理
    // '関 数 名：ddlMonth_SelectedIndexChanged
    // '引 数 １：(I)sender イベントソース
    // '引 数 ２：(I)e      イベントパラメータ
    // '戻 り 値：なし
    // '処理説明：コンボリストのインデックスの変換処理
    // '**********************************************************************
    me.ddlMonth_SelectedIndexChanged = function () {
        me.PageSet();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE110PublicityOrderBase =
        new HMTVE.HMTVE110PublicityOrderBase();
    o_HMTVE_HMTVE110PublicityOrderBase.load();
    o_HMTVE_HMTVE.HMTVE110PublicityOrderBase =
        o_HMTVE_HMTVE110PublicityOrderBase;
});
