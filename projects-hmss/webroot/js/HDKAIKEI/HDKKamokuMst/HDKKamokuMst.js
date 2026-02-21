Namespace.register("HDKAIKEI.HDKKamokuMst");

HDKAIKEI.HDKKamokuMst = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.grid_grdGroupList_id = "#HDKKamokuMst_grdGroupList";
    me.grid_pnlKamokuList_id = "#HDKKamokuMst_kamokuList";
    me.sys_id = "HDKAIKEI";

    me.id = "HDKKamokuMst";
    //科目データの取得
    me.g_urlKamoku = me.sys_id + "/" + me.id + "/" + "fncSelKamokuData";
    //検索
    me.g_urlKensaku = me.sys_id + "/" + me.id + "/" + "kensakuClick";
    //科目コード
    me.allKamoku = "";
    me.data = "";
    //jqgrid reload-me.flg='':検索 me.flg=save:保存後 me.flg=delete:削除後 me.flg=callback:エラーコールバック
    me.flg = "";
    me.option1 = {
        rowNum: 0,
        multiselect: false,
        caption: "",
        rownumbers: false,
        autowidth: true,
    };

    me.option2 = {
        rowNum: 0,
        multiselect: true,
        caption: "",
        rownumbers: true,
    };

    me.colModel1 = [
        {
            label: "関係ＮＯ",
            name: "RELATION_CD",
            index: "RELATION_CD",
            align: "left",
            search: false,
            width: 148,
            sortable: false,
            hidden: true,
        },
        {
            label: "更新日付",
            name: "UPD_DATE",
            index: "UPD_DATE",
            align: "left",
            search: false,
            width: 148,
            sortable: false,
            hidden: true,
        },
        {
            label: "関係名",
            name: "RELATION_NM",
            index: "RELATION_NM",
            align: "left",
            search: false,
            width: 238,
            sortable: false,
        },
        {
            name: "",
            index: "lblSum",
            width: 64,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"grdRelationList_RowCommand('" +
                    rowObject["RELATION_CD"] +
                    "','" +
                    rowObject["RELATION_NM"] +
                    "','" +
                    "2" +
                    "')\" id = '" +
                    rowObject.clid +
                    "_btnSelect' class=\"HDKKamokuMst btnSelect Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size:" +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>選択</button>";
                return detail;
            },
        },
    ];
    me.colModel2 = [
        {
            label: "科目コード",
            name: "KAMOK_CD",
            index: "KAMOK_CD",
            align: "left",
            search: false,
            width: 100,
            sortable: false,
        },
        {
            label: "科目名",
            name: "KAMOK_NAME",
            index: "KAMOK_NAME",
            align: "left",
            search: false,
            width: 230,
            sortable: false,
        },
        {
            label: "",
            name: "PARENT_ID",
            index: "PARENT_ID",
            align: "left",
            search: false,
            width: 134,
            sortable: false,
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HDKKamokuMst .btn",
        type: "button",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //科目コードボタンクリック
    $(".HDKKamokuMst.btnKamoku").click(function () {
        me.openSearchDialog("btnKamoku");
    });

    //新規ボタンクリック
    $(".HDKKamokuMst.btnAdd").click(function () {
        $(me.grid_grdGroupList_id).jqGrid("resetSelection");
        me.flag = "";
        grdRelationList_RowCommand("null", "", 1);
    });

    //検索ボタンクリック
    $(".HDKKamokuMst.Kensaku").click(function () {
        me.Kensaku_click();
    });

    //全て選択ボタンクリック
    $(".HDKKamokuMst.btnSelectAll").click(function () {
        me.SelectAll_click();
    });

    //選択解除ボタンクリック
    $(".HDKKamokuMst.btnUnSelectAll").click(function () {
        me.UnSelectAll_click();
    });

    //保存ボタンクリック
    $(".HDKKamokuMst.btnSave").click(function () {
        // 関係名入力チェック
        if (me.inputCheck() == false) {
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.save_click;
        me.clsComFnc.FncMsgBox("QY010");
    });

    //削除ボタンクリック
    $(".HDKKamokuMst.btnDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.delete_click;
        me.clsComFnc.FncMsgBox("QY004");
    });

    //科目コード変更してフォーカスを失う
    $(".HDKKamokuMst.txtKamokuCD").on("blur", function () {
        $(".HDKKamokuMst.lblkamokuNM").val("");
        if ($(this).val() !== "") {
            me.txtKamokuCD_CheckedChanged($(this).val());
        }
    });
    //関係名,科目コード変更
    $(".HDKKamokuMst.txtKamokuCD,.HDKKamokuMst.txtRelationName").change(
        function () {
            me.txtRelationName_CheckedChanged();
        }
    );

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };
    //**********************************************************************
    //処 理 名：LOAD
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：LOAD
    //**********************************************************************
    me.Page_Load = function () {
        //jqgrid初期
        {
            //関係名一覧
            gdmz.common.jqgrid.init2(
                me.grid_grdGroupList_id,
                me.g_urlKensaku,
                me.colModel1,
                "",
                "",
                me.option1
            );
            //科目一覧
            gdmz.common.jqgrid.init(
                me.grid_pnlKamokuList_id,
                me.g_urlKamoku,
                me.colModel2,
                "",
                "",
                me.option2
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_grdGroupList_id, 340);
            gdmz.common.jqgrid.set_grid_width(me.grid_pnlKamokuList_id, 450);
            gdmz.common.jqgrid.set_grid_height(
                me.grid_grdGroupList_id,
                me.ratio === 1.5 ? 278 : 420
            );
            gdmz.common.jqgrid.set_grid_height(
                me.grid_pnlKamokuList_id,
                me.ratio === 1.5 ? 260 : 395
            );

            //No追加タイトル
            $("#HDKKamokuMst_kamokuList_rn").html("№");
            //科目一覧のスタイルの設定
            $("#HDKKamokuMst_kamokuList_cb").css("width", "36px");
            $("#HDKKamokuMst_kamokuList tbody tr td")
                .eq(1)
                .css("width", "36px");
        }

        //科目
        var url = me.sys_id + "/" + me.id + "/" + "fncFormload";
        var data = {};
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            //科目コード
            me.allKamoku = result["data"]["GetKamokuMstValue"];
        };
        me.ajax.send(url, data, 0);

        $(".HDKKamokuMst.txtRelationName").trigger("focus");
        $(".HDKKamokuMst.grdGroupListTableRow").hide();
        $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
        $(me.grid_grdGroupList_id).jqGrid("bindKeys");
    };
    //**********************************************************************
    //処 理 名：検索ボタンクリックのイベント
    //関 数 名：Kensaku_click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.Kensaku_click = function () {
        var txtRelationName = $.trim($(".HDKKamokuMst.txtRelationName").val());
        var txtKamokuCD = $.trim($(".HDKKamokuMst.txtKamokuCD").val());
        me.data = {
            txtRelationName: txtRelationName,
            txtKamokuCD: txtKamokuCD,
        };
        me.flg = "";
        me.fncJqgridReload();
    };
    //**********************************************************************
    //処 理 名：検索/保存/削除後、jqgrid reload
    //関 数 名 fncJqgridReload
    //引    数：なし
    //戻 り 値：なし
    //処理説明：検索/CSV出力後、jqgrid reload
    //**********************************************************************
    me.fncJqgridReload = function () {
        //選択した行のidを取得
        var selId = 0;
        // 保存/エラーコールバック時
        if (me.flg == "save" || me.flg == "callback") {
            selId = $(me.grid_grdGroupList_id).jqGrid("getGridParam", "selrow");
        }
        $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
        var complete_fun = function (returnFLG, result) {
            if (result["error"]) {
                $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
                $(".HDKKamokuMst.grdGroupListTableRow").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
                $(".HDKKamokuMst.grdGroupListTableRow").hide();
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
            } else {
                if (selId == null) {
                    selId = result["records"] - 1;
                }

                $(me.grid_grdGroupList_id).jqGrid("setSelection", selId, true);
                if (me.flg == "save" || me.flg == "callback") {
                    var rowData = $(me.grid_grdGroupList_id).jqGrid(
                        "getRowData",
                        selId
                    );
                    var data = {
                        relationCD: rowData["RELATION_CD"],
                        relationName: rowData["RELATION_NM"],
                    };
                    postData = {
                        request: data,
                        is_first_ajax: "",
                    };
                    $(me.grid_pnlKamokuList_id)
                        .jqGrid("setGridParam", { postData: postData })
                        .trigger("reloadGrid");
                    $(".HDKKamokuMst.btnDelete").show();
                    $(".HDKKamokuMst.pnlKamokuListTableRow").show();
                    $(".HDKKamokuMst.Kensaku").trigger("focus");
                }
            }

            $(".HDKKamokuMst.grdGroupListTableRow").show();
        };
        gdmz.common.jqgrid.reloadMessage(
            me.grid_grdGroupList_id,
            me.data,
            complete_fun
        );
    };
    //**********************************************************************
    //処 理 名：関係名一覧の選択ボタン押下時
    //関 数 名：grdRelationList_RowCommand
    //引    数：無し
    //戻 り 値：なし
    //処理説明：関係名一覧の選択ボタンが押下された行の関係名の科目を一覧に表示する
    //**********************************************************************
    grdRelationList_RowCommand = function (relationCD, relationName, flg) {
        me.relationCD = relationCD;
        me.relationName = relationName;
        $(".HDKKamokuMst.txtRelationNameS").val(me.relationName);
        var data = {
            relationCD: me.relationCD,
            relationName: relationName,
        };

        $(me.grid_pnlKamokuList_id).jqGrid("clearGridData");
        //右側のテーブルのデータを検索します
        //科目データの取得
        var completeFnc = function (_returnFLG, result) {
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (flg == 2) {
                $(".HDKKamokuMst.btnDelete").show();
            } else {
                if (me.flg !== "save") {
                    $(".HDKKamokuMst.btnDelete").hide();
                }
            }
            //取得したデータをグリッドビューにセットする
            $(".HDKKamokuMst.pnlKamokuListTableRow .ui-jqgrid-bdiv").animate({
                scrollTop: 0,
            });
            $(".HDKKamokuMst.pnlKamokuListTableRow").show();

            //jqgridデータはすべて選択状態です
            me.btnAllSelect_Click();
        };

        gdmz.common.jqgrid.reloadMessage(
            me.grid_pnlKamokuList_id,
            data,
            completeFnc
        );
    };

    me.btnAllSelect_Click = function () {
        $("#cb_HDKKamokuMst_kamokuList").hide();
        var ids = $(me.grid_pnlKamokuList_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            var rowData = $(me.grid_pnlKamokuList_id).jqGrid(
                "getRowData",
                ids[i]
            );
            if (rowData["PARENT_ID"] == me.relationCD) {
                $(me.grid_pnlKamokuList_id).jqGrid(
                    "setSelection",
                    ids[i],
                    true
                );
            } else if (rowData["PARENT_ID"] !== "") {
                $("#jqg_HDKKamokuMst_kamokuList_" + ids[i]).attr(
                    "disabled",
                    "disabled"
                );
            }
        }
        $(me.grid_pnlKamokuList_id).jqGrid("setGridParam", {
            beforeSelectRow: function (rowId) {
                var rowData = $(me.grid_pnlKamokuList_id).jqGrid(
                    "getRowData",
                    rowId
                );
                if (
                    rowData["PARENT_ID"] !== "" &&
                    rowData["PARENT_ID"] !== me.relationCD
                ) {
                    return false;
                } else {
                    return true;
                }
            },
        });
    };

    me.SelectAll_click = function () {
        var ids = $(me.grid_pnlKamokuList_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            var rowData = $(me.grid_pnlKamokuList_id).jqGrid(
                "getRowData",
                ids[i]
            );
            if (
                (rowData["PARENT_ID"] == me.relationCD ||
                    rowData["PARENT_ID"] == "") &&
                !$(
                    "input[name='jqg_HDKKamokuMst_kamokuList_" + ids[i] + "']"
                ).is(":checked")
            ) {
                $(me.grid_pnlKamokuList_id).jqGrid(
                    "setSelection",
                    ids[i],
                    true
                );
            }
        }
    };

    me.UnSelectAll_click = function () {
        var ids = $(me.grid_pnlKamokuList_id).jqGrid("getDataIDs");
        for (var i = 0; i < ids.length; i++) {
            if (
                $(
                    "input[name='jqg_HDKKamokuMst_kamokuList_" + ids[i] + "']"
                ).is(":checked")
            ) {
                $(me.grid_pnlKamokuList_id).jqGrid(
                    "setSelection",
                    ids[i],
                    true
                );
            }
        }
    };
    me.inputCheck = function () {
        var objRegEx_NG = /[\'\""]/;
        if ($(".HDKKamokuMst.txtRelationNameS").val() == "") {
            $(".HDKKamokuMst.txtRelationNameS").trigger("focus");
            me.clsComFnc.FncMsgBox("W9999", "関係名が未入力です！");
            return false;
        } else {
            // '/** 禁則 **/
            if (
                objRegEx_NG.test(
                    $(".HDKKamokuMst.txtRelationNameS").val().trimEnd()
                )
            ) {
                $(".HDKKamokuMst.txtRelationNameS").trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "関係名に不正な文字が入力されています！"
                );
                return false;
            }

            //関係名桁数のチェック
            if (
                me.clsComFnc.GetByteCount(
                    $(".HDKKamokuMst.txtRelationNameS").val()
                ) > 20
            ) {
                $(".HDKKamokuMst.txtRelationNameS").trigger("focus");
                me.clsComFnc.FncMsgBox("E0027", "関係名", 20);
                return false;
            }
        }
    };
    me.save_click = function () {
        me.flg = "save";
        var url = me.sys_id + "/" + me.id + "/" + "btnSaveClick";
        var checkData = [];
        var checkStr = "";
        var ids = $(me.grid_pnlKamokuList_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (ids.length > 0) {
            for (var i = 0; i < ids.length; i++) {
                var rowData = $(me.grid_pnlKamokuList_id).jqGrid(
                    "getRowData",
                    ids[i]
                );
                checkData.push(rowData);
                checkStr = checkStr + ",'" + rowData["KAMOK_CD"] + "'";
            }
            checkStr = checkStr.substring(1);
        }
        var update = "";
        var selId = $(me.grid_grdGroupList_id).jqGrid("getGridParam", "selrow");
        if (selId !== null) {
            var rowData = $(me.grid_grdGroupList_id).jqGrid(
                "getRowData",
                selId
            );
            update = rowData["UPD_DATE"];
        }

        var data = {
            checkData: checkData,
            checkStr: checkStr,
            relationCD: me.relationCD,
            relationName: $(".HDKKamokuMst.txtRelationNameS").val(),
            update: update,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"]) {
                me.clsComFnc.FncMsgBox("I0016");
                me.relationCD = result["relationCD"];
                me.fncJqgridReload();
            } else {
                if (result["error"] == "W0025") {
                    me.clsComFnc.MsgBoxBtnFnc.OK = me.fncJqgridReload;
                    me.clsComFnc.FncMsgBox("W0025", result["error"]);
                } else if (result["error"] == "W0034") {
                    $(".HDKKamokuMst." + result["html"]).trigger("focus");
                    me.clsComFnc.FncMsgBox("W0034", result["data"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.delete_click = function () {
        me.flg = "delete";
        var url = me.sys_id + "/" + me.id + "/" + "btnDeleteClick";

        var update = "";
        var selId = $(me.grid_grdGroupList_id).jqGrid("getGridParam", "selrow");
        if (selId !== null) {
            var rowData = $(me.grid_grdGroupList_id).jqGrid(
                "getRowData",
                selId
            );
            update = rowData["UPD_DATE"];
        }

        var data = {
            relationCD: me.relationCD,
            relationName: $(".HDKKamokuMst.txtRelationNameS").val(),
            update: update,
        };

        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");

            if (result["result"]) {
                me.clsComFnc.FncMsgBox("I0017");
                me.fncJqgridReload();
            } else {
                if (result["error"] == "W0025") {
                    me.flg = "callback";
                    me.clsComFnc.MsgBoxBtnFnc.OK = me.fncJqgridReload;
                    me.clsComFnc.FncMsgBox("W0025", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：科目コードがフォーカスを失う
    //関 数 名：txtKamokuCD_CheckedChanged
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.txtKamokuCD_CheckedChanged = function (thisValue) {
        var foundNM = undefined;
        var selCellVal = me.clsComFnc.FncNv(thisValue);
        if (me.allKamoku) {
            var foundNM_array = me.allKamoku.filter(function (element) {
                return element["KAMOK_CD"] == selCellVal;
            });
            if (foundNM_array.length > 0) {
                foundNM = foundNM_array[0];
            }
            $(".HDKKamokuMst.Kensaku").trigger("focus");
        }
        $(".HDKKamokuMst.lblkamokuNM").val(
            foundNM ? foundNM["KAMOK_NAME"] : ""
        );
        $(".HDKKamokuMst.grdGroupListTableRow").hide();
        $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
    };

    //**********************************************************************
    //処 理 名：検索ﾎﾞﾀﾝクリック
    //関 数 名：openSearchDialog
    //引    数：無し
    //戻 り 値：なし
    //処理説明：検索ボタンの処理
    //**********************************************************************
    me.openSearchDialog = function (searchButton) {
        var dialogId = "";
        var divCD = "";
        var divNM = "";
        var frmId = "";
        var title = "";
        var $txtSearchCD = undefined;
        var $txtSearchNM = undefined;
        var cd = "RtnCD";
        var koumkuCd = "koumkuCd";

        //科目検索
        dialogId = "HDKKamokuSearchDialogDiv";
        $txtSearchCD = $(".HDKKamokuMst.txtKamokuCD");
        $txtSearchNM = $(".HDKKamokuMst.lblkamokuNM");
        divCD = "KamokuCD";
        divNM = "KamokuNM";
        frmId = "HDKKamokuSearch";
        title = "科目マスタ検索";

        var $rootDiv = $(".HDKKamokuMst.HDKAIKEI-content");
        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }
        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", cd).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divCD).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", divNM).insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", koumkuCd).insertAfter($rootDiv).hide();

        var $SearchCD = $rootDiv.parent().find("#" + divCD);
        var $koumkuCd = $rootDiv.parent().find("#" + koumkuCd);
        $SearchCD.val($.trim($txtSearchCD.val()));
        $koumkuCd.val("10");
        $(".HDKKamokuMst.txtRelationName").trigger("focus");
        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 530 : 630,
            width: me.ratio === 1.5 ? 696 : 720,
            resizable: false,
            close: function () {
                var $RtnCD = $rootDiv.parent().find("#" + cd);
                var $SearchCD = $rootDiv.parent().find("#" + divCD);
                var $SearchNM = $rootDiv.parent().find("#" + divNM);
                if ($RtnCD.html() == 1) {
                    $txtSearchCD.val($SearchCD.html());
                    $txtSearchNM.val($SearchNM.html());
                    $(".HDKKamokuMst.grdGroupListTableRow").hide();
                    $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
                }
                $RtnCD.remove();
                $SearchCD.remove();
                $SearchNM.remove();

                if (searchButton == "btnTantou") {
                    $syainSearch.remove();
                }

                $("#" + dialogId).remove();
                setTimeout(function () {
                    $(".HDKKamokuMst." + searchButton).trigger("focus");
                }, 100);
            },
        });

        var url = me.sys_id + "/" + frmId;
        me.ajax.send(url, "", 0);
        me.ajax.receive = function (result) {
            $("#" + dialogId).html(result);
            $("#" + dialogId).dialog("option", "title", title);
            $("#" + dialogId).dialog("open");
        };
    };

    me.txtRelationName_CheckedChanged = function () {
        $(".HDKKamokuMst.grdGroupListTableRow").hide();
        $(".HDKKamokuMst.pnlKamokuListTableRow").hide();
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HDKKamokuMst_HDKKamokuMst = new HDKAIKEI.HDKKamokuMst();
    o_HDKKamokuMst_HDKKamokuMst.load();
    o_HDKAIKEI_HDKAIKEI.HDKKamokuMst = o_HDKKamokuMst_HDKKamokuMst;
});
