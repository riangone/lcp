/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230103           機能追加　　　　　　             20221226_内部統制_仕様変更         YIN
 * 20240520           機能追加　　　　　　店舗が改善結果入力期限に自動で日付をセット         YIN
 * 20250403           機能追加　　　　　　     202504_内部統制_要望.xlsx               YIN
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
 * 20260128             修正          社員番号英字を入力できるように修正               YIN
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("HMAUD.HMAUDSKDToroku");

HMAUD.HMAUDSKDToroku = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.HMAUD = new HMAUD.HMAUD();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMAUDSKDToroku";
    me.sys_id = "HMAUD";

    // ========== 変数 start ==========
    me.grid_id = "#HMAUDSKDTorokutblMain";
    me.pager = "#HMAUDSKDToroku_pager";
    me.sidx = "";
    me.g_url = "HMAUD/HMAUDSKDToroku/btnView_Click";
    me.lastsel = 0;
    me.check_id = "";

    me.colModel = [
        {
            name: "MEMBER",
            label: "社員番号",
            index: "MEMBER",
            width: 100,
            align: "right",
            editable: true,
            sortable: false,
            editoptions: {
                class: "align_right",
                maxlength: 5,
                dataEvents: [
                    //blurイベント
                    {
                        type: "blur",
                        fn: function () {
                            //当前id
                            // var nowId = this.parentElement.parentElement.id;
                            var syainNM = this.parentElement.nextSibling;
                            var syainCD = this.value;
                            syainNM.innerText = me.getSyainNM(syainCD);
                        },
                    },
                    {
                        type: "keydown",
                        fn: function (e) {
                            var key = e.charCode || e.keyCode;
                            //Esc:keep edit
                            if (key == 27) {
                                return false;
                            }
                            if (
                                key == 13 ||
                                (key == 9 && !e.shiftKey) ||
                                (e.shiftKey && key == 9) ||
                                key == 38 ||
                                key == 40
                            ) {
                                //当前id
                                // var nowId = this.parentElement.parentElement.id;
                                var syainNM = this.parentElement.nextSibling;
                                var syainCD = this.value;
                                syainNM.innerText = me.getSyainNM(syainCD);
                            }
                        },
                    },
                    {
                        type: "keyup",
                        fn: function () {
                            var tmptxt = $(this).val();
                            $(this).val(tmptxt.replace(/[^0-9a-zA-Z]/g, ""));
                        },
                    },
                ],
            },
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 120,
            align: "left",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //行追加ボタン
    me.controls.push({
        id: ".HMAUDSKDToroku.btnAdd",
        type: "button",
        handle: "",
    });

    //行削除ボタン
    me.controls.push({
        id: ".HMAUDSKDToroku.btnDel",
        type: "button",
        handle: "",
    });

    //更新ボタン
    me.controls.push({
        id: ".HMAUDSKDToroku.btnUpd",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HMAUDSKDToroku.btnClose",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMAUDSKDToroku .Datepicker",
        type: "datepicker",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HMAUD.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //処理説明：行追加ボタン押下時
    $(".HMAUDSKDToroku .PLAN_DT").change(function () {
        me.planDT_Change();
    });

    //処理説明：行追加ボタン押下時
    $(".HMAUDSKDToroku.btnAdd").click(function () {
        me.btnRowAdd_Click();
    });
    //処理説明：行削除ボタン押下時
    $(".HMAUDSKDToroku.btnDel").click(function () {
        me.grd_RowDeleting();
    });
    //処理説明：戻るボタン押下時
    $(".HMAUDSKDToroku.btnClose").click(function () {
        $(".HMAUDSKDToroku.body").dialog("close");
    });
    //処理説明：更新ボタン押下時
    $(".HMAUDSKDToroku.btnUpd").click(function () {
        if (!me.inputCheck()) {
            return false;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpd_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.BtnCancel_OnClick;
        me.clsComFnc.MsgBoxBtnFnc.Close = me.BtnCancel_OnClick;
        me.clsComFnc.FncMsgBox("QY012");
    });

    $(".HMAUDSKDToroku .IMPROVEMENT_REPORT").on("blur", function () {
        $(".HMAUDSKDToroku .IMPROVEMENT_REPORT_NAME").val(
            me.getSyainNM(this.value),
        );
    });
    $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").on("blur", function () {
        $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN_NAME").val(
            me.getSyainNM(this.value),
        );
    });
    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").on("blur", function () {
        $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_NAME").val(
            me.getSyainNM(this.value),
        );
    });
    $(".HMAUDSKDToroku .KEY_PERSON").on("blur", function () {
        $(".HMAUDSKDToroku .KEY_PERSON_NAME").val(me.getSyainNM(this.value));
    });
    $(".HMAUDSKDToroku .DIRECTOR_GENERAL").on("blur", function () {
        $(".HMAUDSKDToroku .DIRECTOR_GENERAL_NAME").val(
            me.getSyainNM(this.value),
        );
    });
    // 20230103 YIN INS S
    $(".HMAUDSKDToroku .EXECUTIVE").on("blur", function () {
        $(".HMAUDSKDToroku .EXECUTIVE_NAME").val(me.getSyainNM(this.value));
    });
    // 20230103 YIN INS E
    // 20250403 YIN INS S
    $(".HMAUDSKDToroku .VICE_PRESIDENT").on("blur", function () {
        $(".HMAUDSKDToroku .VICE_PRESIDENT_NAME").val(
            me.getSyainNM(this.value),
        );
    });
    // 20250403 YIN INS E
    $(".HMAUDSKDToroku .PRESIDENT").on("blur", function () {
        $(".HMAUDSKDToroku .PRESIDENT_NAME").val(me.getSyainNM(this.value));
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
        me.HMAUDSKDToroku_load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HMAUDSKDToroku_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HMAUDSKDToroku_load = function () {
        //初期設定処理
        $(".HMAUDSKDToroku.body").dialog({
            autoOpen: false,
            // 20250403 YIN UPD S
            // height: 755,
            // width: 605,
            height: me.ratio === 1.5 ? 550 : 590,
            width: me.ratio === 1.5 ? 930 : 1050,
            // 20250403 YIN UPD E
            modal: true,
            title: "監査スケジュール登録",
            open: function () {},
            close: function () {
                me.before_close();
                $(".HMAUDSKDToroku.body").remove();
            },
        });

        $(".HMAUDSKDToroku.body").dialog("open");

        $(".HMAUDSKDToroku .COUR").val($("#cour").html());
        $(".HMAUDSKDToroku .KYOTEN_CD").val($("#kyotenCD").html());
        $(".HMAUDSKDToroku .KYOTEN_NAME").val($("#kyotenNM").html());
        $(".HMAUDSKDToroku .COUR_DATE").html($("#courDate").html());
        me.territory = $("#territory").html();

        // 20251016 YIN INS S
        if ($(".HMAUDSKDToroku .COUR").val() > 18) {
            $(".HMAUDSKDToroku .executive-display").hide();
        }
        // 20251016 YIN INS E
        if ($(".HMAUDSKDToroku .COUR").val() >= 20) {
            $(".HMAUDSKDToroku .PRESIDENT-DISPLAY").hide();
        }

        $(me.grid_id).jqGrid({
            datatype: "local",
            caption: "",
            emptyRecordRow: false,
            multiselect: false,
            rownumbers: false,
            autoScroll: true,
            colModel: me.colModel,
        });
        $(me.grid_id).jqGrid("setGridParam", {
            //選択行の修正画面を呼び出す
            onSelectRow: function (rowId, _status, e) {
                if (me.lastsel != "") {
                    $(me.grid_id).jqGrid("saveRow", me.lastsel);
                }

                $(me.grid_id).jqGrid("editRow", rowId, true);
                me.lastsel = rowId;

                if (e) {
                    $("#" + me.lastsel + "_MEMBER").trigger("focus");
                    $("#" + me.lastsel + "_MEMBER").select();
                }
                var up_next_sel = gdmz.common.jqgrid.setKeybordEvents(
                    me.grid_id,
                    e,
                    rowId,
                    null,
                );
                if (up_next_sel && up_next_sel.length == 2) {
                    me.upsel = up_next_sel[0];
                    me.nextsel = up_next_sel[1];
                }
                //靠右
                $(me.grid_id).find(".align_right").css("text-align", "right");
                $(me.grid_id).find(".width").css("width", "97%");
            },
        });
        $(me.grid_id).jqGrid("bindKeys");
        gdmz.common.jqgrid.set_grid_height(me.grid_id, 104);

        var url = me.sys_id + "/" + me.id + "/getMainData";
        var data = {
            cour: $(".HMAUDSKDToroku .COUR").val(),
            kyotenCD: $(".HMAUDSKDToroku .KYOTEN_CD").val(),
            territory: me.territory,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"]["data"] !== "W0024") {
                    me.mainData = result["data"]["mainData"];
                    me.check_id = me.mainData["CHECK_ID"];
                    var memberData = result["data"]["memberData"];
                    $(".HMAUDSKDToroku .PLAN_DT").val(me.mainData["PLAN_DT"]);
                    $(".HMAUDSKDToroku .PLAN_TIME").val(
                        me.mainData["PLAN_TIME"],
                    );
                    $(".HMAUDSKDToroku .PLAN_LIMIT").val(
                        me.mainData["PLAN_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .AUDIT_PRESENT").val(
                        me.mainData["REPORT0_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .REPORT_TERRITORY_LIMIT").val(
                        me.mainData["REPORT1_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .REPORT_LIMIT").val(
                        me.mainData["REPORT2_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_LIMIT").val(
                        me.mainData["CHECK1_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .KEY_PERSON_LIMIT").val(
                        me.mainData["CHECK2_LIMIT"],
                    );
                    $(".HMAUDSKDToroku .AUDIT_MEET_DT").val(
                        me.mainData["AUDIT_MEET_DT"],
                    );
                    $(".HMAUDSKDToroku .IMPROVEMENT_REPORT").val(
                        me.mainData["IMPROVEMENT_REPORT"],
                    );
                    $(".HMAUDSKDToroku .IMPROVEMENT_REPORT_NAME").val(
                        me.mainData["IMPROVEMENT_REPORT_NAME"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").val(
                        me.mainData["RESPONSIBLE_KYOTEN"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN_NAME").val(
                        me.mainData["RESPONSIBLE_KYOTEN_NAME"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").val(
                        me.mainData["RESPONSIBLE_TERRITORY"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_NAME").val(
                        me.mainData["RESPONSIBLE_TERRITORY_NAME"],
                    );
                    $(".HMAUDSKDToroku .KEY_PERSON").val(
                        me.mainData["KEY_PERSON"],
                    );
                    $(".HMAUDSKDToroku .KEY_PERSON_NAME").val(
                        me.mainData["KEY_PERSON_NAME"],
                    );
                    $(".HMAUDSKDToroku .DIRECTOR_GENERAL").val(
                        me.mainData["DIRECTOR_GENERAL"],
                    );
                    $(".HMAUDSKDToroku .DIRECTOR_GENERAL_NAME").val(
                        me.mainData["DIRECTOR_GENERAL_NAME"],
                    );
                    // 20230103 YIN INS S
                    // 20251016 YIN UPD S
                    if ($(".HMAUDSKDToroku .COUR").val() <= 18) {
                        $(".HMAUDSKDToroku .EXECUTIVE").val(
                            me.mainData["EXECUTIVE"],
                        );
                        $(".HMAUDSKDToroku .EXECUTIVE_NAME").val(
                            me.mainData["EXECUTIVE_NAME"],
                        );
                    }
                    // 20251016 YIN UPD E
                    // 20230103 YIN INS E
                    // 20250403 YIN INS S
                    $(".HMAUDSKDToroku .VICE_PRESIDENT").val(
                        me.mainData["VICE_PRESIDENT"],
                    );
                    $(".HMAUDSKDToroku .VICE_PRESIDENT_NAME").val(
                        me.mainData["VICE_PRESIDENT_NAME"],
                    );
                    // 20250403 YIN INS E
                    if ($(".HMAUDSKDToroku .COUR").val() < 20) {
                        $(".HMAUDSKDToroku .PRESIDENT").val(
                            me.mainData["PRESIDENT"],
                        );
                        $(".HMAUDSKDToroku .PRESIDENT_NAME").val(
                            me.mainData["PRESIDENT_NAME"],
                        );
                    }

                    $(me.grid_id)
                        .setGridParam({
                            data: memberData,
                        })
                        .trigger("reloadGrid");

                    $(me.grid_id).jqGrid("setSelection", 1, true);
                } else {
                    me.defaultMembers = result["data"]["defaultMembers"];
                    $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").val(
                        me.defaultMembers["RESPONSIBLE_EIGYO"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN_NAME").val(
                        me.defaultMembers["RESPONSIBLE_EIGYO_NAME"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").val(
                        me.defaultMembers["RESPONSIBLE_TERRITORY"],
                    );
                    $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_NAME").val(
                        me.defaultMembers["RESPONSIBLE_TERRITORY_NAME"],
                    );
                    $(".HMAUDSKDToroku .KEY_PERSON").val(
                        me.defaultMembers["KEY_PERSON"],
                    );
                    $(".HMAUDSKDToroku .KEY_PERSON_NAME").val(
                        me.defaultMembers["KEY_PERSON_NAME"],
                    );
                }

                me.syainData = result["data"]["syainmst"];

                $(".HMAUDSKDToroku .PLAN_TIME").trigger("focus");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                me.dialogClose();
            }
        };
        me.ajax.send(url, data, 0);
    };

    me.btnRowAdd_Click = function () {
        //获得所有行的ID数组
        var ids = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = 0;
        if (ids.length > 0) {
            //获得当前最大行号（数据编号）
            rowid = parseInt(ids.pop()) + 1;
        } else {
            rowid = 1;
        }
        var data = {
            MEMBER: "",
            SYAIN_NM: "",
        };
        //插入一行
        $(me.grid_id).jqGrid("addRowData", rowid, data);
        $(me.grid_id).jqGrid("saveRow", me.lastsel);

        $(me.grid_id).jqGrid("setSelection", rowid, true);
    };

    // '**********************************************************************
    // '処 理 名：本カｶﾀﾛｸﾞテープルの行削除ボタンのイベント
    // '関 数 名：Page_Load
    // '戻 り 値：なし
    // '処理説明：行削除ボタンを押下された行に表示されているﾃﾞｰﾀをクリアする
    // '**********************************************************************
    me.grd_RowDeleting = function () {
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (allIds.length == 0 || rowid == null) {
            me.clsComFnc.FncMsgBox("W9999", "削除対象の行を選択してください。");
            return;
        }

        for (i = 0; i < allIds.length; i++) {
            if (allIds[i] == rowid) {
                if (allIds[i] != allIds.pop()) {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.nextsel, true);
                } else {
                    $(me.grid_id).jqGrid("delRowData", rowid);

                    $(me.grid_id).jqGrid("setSelection", me.upsel, true);
                }
                break;
            }
        }
    };

    me.planDT_Change = function () {
        var plan_dt = $(".HMAUDSKDToroku .PLAN_DT").val();
        var plan_date = new Date(plan_dt);
        plan_date.setDate(plan_date.getDate() - 1);
        $(".HMAUDSKDToroku .PLAN_LIMIT").val(plan_date.Format("yyyy/MM/dd"));
        plan_date = new Date(plan_dt);
        plan_date.setDate(plan_date.getDate() + 7);
        $(".HMAUDSKDToroku .AUDIT_PRESENT").val(plan_date.Format("yyyy/MM/dd"));
        plan_date.setDate(plan_date.getDate() + 7);
        $(".HMAUDSKDToroku .REPORT_TERRITORY_LIMIT").val(
            plan_date.Format("yyyy/MM/dd"),
        );
        plan_date = new Date(plan_dt);
        plan_date.setMonth(plan_date.getMonth() + 1);
        // $(".HMAUDSKDToroku .REPORT_LIMIT").val(plan_date.Format('yyyy/MM/dd'));
        // 20240520 YIN INS S
        $(".HMAUDSKDToroku .REPORT_LIMIT").val(plan_date.Format("yyyy/MM/dd"));
        // 20240520 YIN INS E
        $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_LIMIT").val(
            plan_date.Format("yyyy/MM/dd"),
        );
        plan_date = new Date(plan_dt);
        plan_date.setMonth(plan_date.getMonth() + 2);
        $(".HMAUDSKDToroku .KEY_PERSON_LIMIT").val(
            plan_date.Format("yyyy/MM/dd"),
        );

        $(".HMAUDSKDToroku .AUDIT_MEET_DT").val($("#auditMeetDt").html());
    };

    me.getSyainNM = function (syainCD) {
        for (var i = 0; i < me.syainData.length; i++) {
            if (me.syainData[i]["SYAIN_NO"] == syainCD) {
                return me.syainData[i]["SYAIN_NM"];
            }
        }
        return "";
    };

    me.btnUpd_Click = function () {
        var checkMemberData = $(me.grid_id).jqGrid("getRowData");
        for (var i = 0; i < checkMemberData.length; i++) {
            checkMemberData[i]["ROLE"] = 1;
        }
        if ($(".HMAUDSKDToroku .IMPROVEMENT_REPORT").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .IMPROVEMENT_REPORT").val(),
                ROLE: 2,
            });
        }
        if ($(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").val(),
                ROLE: 3,
            });
        }
        if ($(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").val(),
                ROLE: 4,
            });
        }
        if ($(".HMAUDSKDToroku .KEY_PERSON").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .KEY_PERSON").val(),
                ROLE: 5,
            });
        }
        if ($(".HMAUDSKDToroku .DIRECTOR_GENERAL").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .DIRECTOR_GENERAL").val(),
                ROLE: 6,
            });
        }
        // 20230103 YIN INS S
        // 20251016 YIN UPD S
        if (
            $(".HMAUDSKDToroku .EXECUTIVE").val() !== "" &&
            $(".HMAUDSKDToroku .COUR").val() <= 18
        ) {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .EXECUTIVE").val(),
                ROLE: 7,
            });
        }
        // 20251016 YIN UPD E
        // 20230103 YIN INS E
        // 20250403 YIN INS S
        if ($(".HMAUDSKDToroku .VICE_PRESIDENT").val() !== "") {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .VICE_PRESIDENT").val(),
                ROLE: 8,
            });
        }
        // 20250403 YIN INS E
        if (
            $(".HMAUDSKDToroku .PRESIDENT").val() !== "" &&
            $(".HMAUDSKDToroku .COUR").val() < 20
        ) {
            checkMemberData.push({
                MEMBER: $(".HMAUDSKDToroku .PRESIDENT").val(),
                // 20250403 YIN UPD S
                // ROLE: 8
                ROLE: 9,
                // 20250403 YIN UPD E
            });
        }
        var mainData = {
            cour: $(".HMAUDSKDToroku .COUR").val(),
            kyotenCD: $(".HMAUDSKDToroku .KYOTEN_CD").val(),
            territory: me.territory,
            PLAN_DT: $(".HMAUDSKDToroku .PLAN_DT").val(),
            PLAN_TIME: $(".HMAUDSKDToroku .PLAN_TIME").val(),
            PLAN_LIMIT: $(".HMAUDSKDToroku .PLAN_LIMIT").val(),
            REPORT0_LIMIT: $(".HMAUDSKDToroku .AUDIT_PRESENT").val(),
            REPORT1_LIMIT: $(".HMAUDSKDToroku .REPORT_TERRITORY_LIMIT").val(),
            REPORT2_LIMIT: $(".HMAUDSKDToroku .REPORT_LIMIT").val(),
            CHECK1_LIMIT: $(
                ".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_LIMIT",
            ).val(),
            CHECK2_LIMIT: $(".HMAUDSKDToroku .KEY_PERSON_LIMIT").val(),
            AUDIT_MEET_DT: $(".HMAUDSKDToroku .AUDIT_MEET_DT").val(),
        };

        var data = {
            mainData: mainData,
            checkMemberData: checkMemberData,
        };
        var url = me.sys_id + "/" + me.id + "/updMainData";
        var data = {
            mainData: mainData,
            checkMemberData: checkMemberData,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                $("#RtnCD").html(1);
                me.clsComFnc.MsgBoxBtnFnc.OK = me.dialogClose;
                me.clsComFnc.MsgBoxBtnFnc.Close = me.dialogClose;
                me.clsComFnc.FncMsgBox("I0015");
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.dialogClose = function () {
        $(".HMAUDSKDToroku.body").dialog("close");
    };
    me.inputCheck = function () {
        // if ($('.HMAUDSKDToroku .PLAN_DT').val() == '')
        // {
        // me.clsComFnc.FncMsgBox("W0017", "監査予定日時");
        // return false;
        // }
        // if ($('.HMAUDSKDToroku .PLAN_TIME').val() == '')
        // {
        // me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .PLAN_TIME");
        // me.clsComFnc.FncMsgBox("W0017", "監査予定日時");
        // return false;
        // }
        var reDateTime = /^(?:(?:[0-2][0-3])|(?:[0-1][0-9])):[0-5][0-9]$/;
        if (
            $(".HMAUDSKDToroku .PLAN_TIME").val() !== "" &&
            !reDateTime.test($(".HMAUDSKDToroku .PLAN_TIME").val())
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .PLAN_TIME");
            me.clsComFnc.FncMsgBox("W0002", "監査予定日時");
            return false;
        }
        $(me.grid_id).jqGrid("saveRow", me.lastsel);
        var checkMemberData = $(me.grid_id).jqGrid("getRowData");
        var allIds = $(me.grid_id).jqGrid("getDataIDs");
        if (checkMemberData.length == 0) {
            me.clsComFnc.FncMsgBox("W0017", "監査人");
            return false;
        } else {
            for (var i = 0; i < checkMemberData.length; i++) {
                if (checkMemberData[i]["MEMBER"] == "") {
                    $(me.grid_id).jqGrid("setSelection", allIds[i], true);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "監査人データから空白行を削除してください。",
                    );
                    return false;
                }
                if (checkMemberData[i]["SYAIN_NM"] == "") {
                    $(me.grid_id).jqGrid("setSelection", allIds[i], true);
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "入力した社員番号が存在していませんので、再度入力してください。",
                    );
                    return false;
                }
            }
        }

        // if ($('.HMAUDSKDToroku .RESPONSIBLE_KYOTEN').val() == '')
        // {
        // me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN");
        // me.clsComFnc.FncMsgBox("W0017", "改善取組責任者");
        // return false;
        // }
        var msg =
            "入力した社員番号が拠点に存在していませんので、再度入力してください。";
        if (
            $(".HMAUDSKDToroku .IMPROVEMENT_REPORT").val() !== "" &&
            $(".HMAUDSKDToroku .IMPROVEMENT_REPORT_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .IMPROVEMENT_REPORT");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        if (
            $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN").val() !== "" &&
            $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .RESPONSIBLE_KYOTEN");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        // if ($('.HMAUDSKDToroku .RESPONSIBLE_TERRITORY').val() == '')
        // {
        // me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY");
        // me.clsComFnc.FncMsgBox("W0017", "各領域責任者");
        // return false;
        // }
        if (
            $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY").val() !== "" &&
            $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .RESPONSIBLE_TERRITORY");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        // if ($('.HMAUDSKDToroku .KEY_PERSON').val() == '')
        // {
        // me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .KEY_PERSON");
        // me.clsComFnc.FncMsgBox("W0017", "キーマン");
        // return false;
        // }
        if (
            $(".HMAUDSKDToroku .KEY_PERSON").val() !== "" &&
            $(".HMAUDSKDToroku .KEY_PERSON_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .KEY_PERSON");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        if (
            $(".HMAUDSKDToroku .DIRECTOR_GENERAL").val() !== "" &&
            $(".HMAUDSKDToroku .DIRECTOR_GENERAL_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .DIRECTOR_GENERAL");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        // 20230103 YIN INS S
        if (
            $(".HMAUDSKDToroku .EXECUTIVE").val() !== "" &&
            $(".HMAUDSKDToroku .EXECUTIVE_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .EXECUTIVE");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        // 20230103 YIN INS E
        // 20250403 YIN INS S
        if (
            $(".HMAUDSKDToroku .VICE_PRESIDENT").val() !== "" &&
            $(".HMAUDSKDToroku .VICE_PRESIDENT_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .VICE_PRESIDENT");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        // 20250403 YIN INS E
        if (
            $(".HMAUDSKDToroku .PRESIDENT").val() !== "" &&
            $(".HMAUDSKDToroku .PRESIDENT_NAME").val() == ""
        ) {
            me.clsComFnc.ObjFocus = $(".HMAUDSKDToroku .PRESIDENT");
            me.clsComFnc.FncMsgBox("W9999", msg);
            return false;
        }
        return true;
    };

    me.BtnCancel_OnClick = function () {
        $(me.grid_id).jqGrid("setSelection", me.lastsel, true);
    };

    me.before_close = function () {};

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDSKDToroku = new HMAUD.HMAUDSKDToroku();
    o_HMAUD_HMAUD.HMAUDSKDList.HMAUDSKDToroku = o_HMAUD_HMAUDSKDToroku;
    o_HMAUD_HMAUDSKDToroku.load();
});
