/**
 * HMHRMS
 * @alias  HMHRMS
 * @author FCSDL
 */
Namespace.register("HMHRMS.HMHRMS");

HMHRMS.HMHRMS = function () {
    var me = new gdmz.base.panel();
    me.ajax = new gdmz.common.ajax();
    me.MessageBox = new gdmz.common.MessageBox();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "社員個人記録入力";
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "HMHRMS";
    me.sys_id = "HMHRMS";
    me.g_url = me.id + "/" + me.sys_id + "/showTable";
    me.emp = [];
    me.empId = [];
    me.columns = [];
    me.schoolTypeOpt = [];
    me.commuteMethodOpt = [];
    me.flag = true;
    me.empID = "";
    me.praiseYearValue = "";
    me.qualicationYearValue = "";
    me.praiseMonthValue = "";
    me.qualicationMonthValue = "";

    // 家族状況
    me.family_grid_id = "#family_jqgridTable";
    me.family_colModel = [
        {
            name: "name",
            label: "氏名",
            index: "name",
            align: "left",
        },
        {
            name: "namePhonetic",
            label: "フリガナ",
            index: "namePhonetic",
            align: "left",
        },
        {
            name: "relation",
            label: "続柄",
            index: "relation",
            align: "left",
        },
        {
            name: "birthday",
            label: "生年月日",
            index: "birthday",
            align: "left",
        },
        {
            name: "age",
            label: "年齢",
            index: "age",
            align: "left",
        },
        // {
        //     name: "together_name",
        //     label: "同居別居",
        //     index: "together_name",
        //     align: "left",
        // },
        // {
        //     name: "together",
        //     label: "同居別居",
        //     index: "together",
        //     align: "left",
        //     hidden: true,
        // },
        {
            name: "estid",
            label: "数据处理",
            index: "estid",
            align: "left",
            hidden: true,
        },
    ];

    // 学歴
    me.education_grid_id = "#education_jqgridTable";
    me.education_colModel = [
        {
            name: "kinds_of_schools_name",
            label: "学校種別",
            index: "kinds_of_schools_name",
            align: "left",
        },
        {
            name: "kinds_of_schools",
            label: "学校種別",
            index: "kinds_of_schools",
            align: "left",
            hidden: true,
        },
        {
            name: "school_name",
            label: "学校名",
            index: "school_name",
            align: "left",
        },
        {
            name: "disciplines",
            label: "学部・学科",
            index: "disciplines",
            align: "left",
        },
        {
            name: "address_country",
            label: "所在地（国）",
            index: "address_country",
            align: "left",
        },
        {
            name: "address_prefecture",
            label: "所在地（都道府県）",
            index: "address_prefecture",
            align: "left",
        },
        {
            name: "address_city",
            label: "所在地（市）",
            index: "address_city",
            align: "left",
        },
        {
            name: "estid",
            label: "数据处理",
            index: "estid",
            align: "left",
            hidden: true,
        },
    ];

    // 社外職歴
    me.othercompany_grid_id = "#othercompany_jqgridTable";
    me.othercompany_colModel = [
        {
            name: "company_start",
            label: "年月Start",
            index: "company_start",
            align: "left",
        },
        {
            name: "company_end",
            label: "年月End",
            index: "company_end",
            align: "left",
        },
        {
            name: "company_country",
            label: "勤務地（国）",
            index: "company_country",
            align: "left",
        },
        {
            name: "company_prefecture",
            label: "勤務地（都道府県）",
            index: "company_prefecture",
            align: "left",
        },
        {
            name: "company_city",
            label: "勤務地（市）",
            index: "company_city",
            align: "left",
        },
        {
            name: "company_name",
            label: "社名",
            index: "company_name",
            align: "left",
        },
        {
            name: "company_position",
            label: "ポジション",
            index: "company_position",
            align: "left",
        },
        {
            name: "job_content",
            label: "職務内容",
            index: "job_content",
            align: "left",
        },
        {
            name: "estid",
            label: "数据处理",
            index: "estid",
            align: "left",
            hidden: true,
        },
    ];

    // 表彰歴
    me.praise_grid_id = "#praise_jqgridTable";
    me.praise_colModel = [
        {
            name: "praise_date",
            label: "年月",
            index: "praise_date",
            align: "left",
        },
        {
            name: "praise_content",
            label: "表彰内容",
            index: "praise_content",
            align: "left",
        },
        {
            name: "estid",
            label: "数据处理",
            index: "estid",
            align: "left",
            hidden: true,
        },
    ];

    // 資格・免許
    me.qualication_grid_id = "#qualication_jqgridTable";
    me.qualication_colModel = [
        {
            name: "public_content",
            label: "資格・免許",
            index: "public_content",
            align: "left",
        },
        {
            name: "get_date",
            label: "取得時期",
            index: "get_date",
            align: "left",
        },
        {
            name: "estid",
            label: "数据处理",
            index: "estid",
            align: "left",
            hidden: true,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMHRMS.editBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.cancelBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.updateBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.FamilyBirthDate",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.family_addBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.family_editBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.family_delBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.education_addBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.education_editBtn",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMHRMS.education_delBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.othercompany_delBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.praise_delBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.qualication_delBtn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".btn.Tab",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.company_start",
        type: "datepicker2",
        handle: "",
    });
    me.controls.push({
        id: ".HMHRMS.company_end",
        type: "datepicker2",
        handle: "",
    });
    //   キーボードイベント
    // 個人情報_特技・趣味
    $(".HMHRMS.textarea").keyup(function () {
        var $this = $(this);
        if (!$this.prop("initAttrH")) {
            $this.prop("initAttrH", $this.outerHeight());
        }
        setAutoHeight(this).on("input", function () {
            return setAutoHeight(this);
        });
        function setAutoHeight(elem) {
            var $obj = $(elem);
            return $obj
                .css({
                    height: $obj.prop("initAttrH"),
                    "overflow-y": "hidden",
                })
                .height(elem.scrollHeight);
        }
    });

    $(".HMHRMS.praiseyear").keyup(function (elem) {
        if (elem.keyCode == 8) {
            if ($(".HMHRMS.praiseyear").val() == "") {
                me.praiseYearValue = "";
            }
        }
    });
    $(".HMHRMS.praisemonth").keyup(function (elem) {
        if (elem.keyCode == 8) {
            if ($(".HMHRMS.praisemonth").val() == "") {
                me.praiseMonthValue = "";
            }
        }
    });
    $(".HMHRMS.qualicationyear").keyup(function (elem) {
        if (elem.keyCode == 8) {
            if ($(".HMHRMS.qualicationyear").val() == "") {
                me.qualicationYearValue = "";
            }
        }
    });
    $(".HMHRMS.qualicationmonth").keyup(function (elem) {
        if (elem.keyCode == 8) {
            if ($(".HMHRMS.qualicationmonth").val() == "") {
                me.qualicationMonthValue = "";
            }
        }
    });
    $(".HMHRMS.empZipCode").keyup(function () {
        if (
            $(".HMHRMS.empZipCode").val() !== me.empZipCodeOld ||
            $(".HMHRMS.empAddress").val() == ""
        ) {
            reg = /^\d{3}-\d{4}$/;
            if (!reg.test($(this).val())) {
                $(".HMHRMS.empAddress").val("");
            } else {
                me.empZipCodeOld = $(".HMHRMS.empZipCode").val();
                AjaxZip3.onFailure = function () {
                    $(".HMHRMS.empAddress").val("");
                };
                AjaxZip3.zip2addr(this, "", "addr00", "addr00");
            }
        }
    });
    $(".HMHRMS.emergencyZipCode").keyup(function () {
        if (
            $(".HMHRMS.emergencyZipCode").val() !== me.emergencyZipCodeOld ||
            $(".HMHRMS.emergencyAddress").val() == ""
        ) {
            reg = /^\d{3}-\d{4}$/;
            if (!reg.test($(this).val())) {
                $(".HMHRMS.emergencyAddress").val("");
            } else {
                me.emergencyZipCodeOld = $(".HMHRMS.emergencyZipCode").val();
                AjaxZip3.onFailure = function () {
                    $(".HMHRMS.emergencyAddress").val("");
                };
                AjaxZip3.zip2addr(this, "", "addr01", "addr01");
            }
        }
    });
    $(".HMHRMS.emergencyZipCode2").keyup(function () {
        if (
            $(".HMHRMS.emergencyZipCode2").val() !== me.emergencyZipCode2Old ||
            $(".HMHRMS.emergencyAddress2").val() == ""
        ) {
            reg = /^\d{3}-\d{4}$/;
            if (!reg.test($(this).val())) {
                $(".HMHRMS.emergencyAddress2").val("");
            } else {
                me.emergencyZipCode2Old = $(".HMHRMS.emergencyZipCode2").val();
                AjaxZip3.onFailure = function () {
                    $(".HMHRMS.emergencyAddress2").val("");
                };
                AjaxZip3.zip2addr(this, "", "addr02", "addr02");
            }
        }
    });
    $(document).ready(function () {
        const $select = $(".HMHRMS.livingcondition");
        const $input = $select.parent().children("input[type='text']");

        $input.on("focus", function () {
            var val = $(this).val();
            var validOptions = $select
                .find("option")
                .map(function () {
                    return $(this).val();
                })
                .get();

            if (!validOptions.includes(val)) {
                $select.val("");
                $(".combo-dropdown .option-item").removeClass(
                    "option-selected option-hover"
                );
            }
            $select.comboSelect("show");
        });
    });
    //20250425 lujunxia upd s
    me.isElementInDialog = function ($element) {
        var isInDialog = $element.closest(".ui-dialog-content").length > 0;
        var classArray = $element[0].className.trim().split(/\s+/);
        var isDirectChildOfDialog =
            $(".ui-dialog").find("." + classArray.join(".")).length > 0;

        return isInDialog || isDirectChildOfDialog;
    };
    // ShiftキーとTabキーのバインド
    me.Shift_TabKeyDown = function () {
        var $inp = $(".HMHRMS .Tab[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == true) {
                e.preventDefault();
                if (me.isElementInDialog($(this))) {
                    var $inp_dialog = $(".ui-dialog .HMHRMS .Tab[tabindex]");
                    var $inp_enabled = $inp_dialog.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
                    var nxtIdx =
                        $inp_enabled[0] == this
                            ? Number(
                                  $inp_enabled.index(
                                      $inp_enabled[$inp_enabled.length - 1]
                                  ) + 1
                              )
                            : Number($inp_enabled.index(this));
                } else {
                    var $inp_enabled = $inp.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
                    var nxtIdx = Number($inp_enabled.index(this));
                    if (nxtIdx == 0) {
                        //first one : init
                        nxtIdx = $inp_enabled.length;
                    }
                }
                $inp_enabled.eq(nxtIdx - 1).select();
                $inp_enabled.eq(nxtIdx - 1).trigger("focus");
            }
        });
    };
    me.Shift_TabKeyDown();

    // Tabキーのバインド
    me.TabKeyDown = function () {
        var $inp = $(".HMHRMS .Tab[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
            );
        });

        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == false) {
                e.preventDefault();
                if (me.isElementInDialog($(this))) {
                    var $inp_dialog = $(".ui-dialog .HMHRMS .Tab[tabindex]");
                    var $inp_enabled = $inp_dialog.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
                    var nxtIdx =
                        $inp_enabled[$inp_enabled.length - 1] == this
                            ? Number($inp_enabled.index($inp_enabled[0]))
                            : Number($inp_enabled.index(this)) + 1;
                } else {
                    var $inp_enabled = $inp.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
                    var nxtIdx = Number($inp_enabled.index(this)) + 1;
                    if (nxtIdx == $inp_enabled.length) {
                        //last one : init
                        nxtIdx = 0;
                    }
                }
                $inp_enabled.eq(nxtIdx).select();
                $inp_enabled.eq(nxtIdx).trigger("focus");
            }
        });
    };
    me.TabKeyDown();

    // Enterキーのバインド
    me.EnterKeyDown = function () {
        var $inp = $(".HMHRMS .Enter[tabindex]");
        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).prop("tabindex")) -
                Number($(ctrl2).prop("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();
                    if (me.isElementInDialog($(this))) {
                        var $inp_dialog = $(
                            ".ui-dialog .HMHRMS .Tab[tabindex]"
                        );
                        var $inp_enabled = $inp_dialog.filter(":enabled");
                        $inp_enabled = $inp_enabled.filter(":visible");
                        var nxtIdx =
                            $inp_enabled[$inp_enabled.length - 1] == this
                                ? Number($inp_enabled.index($inp_enabled[0]))
                                : Number($inp_enabled.index(this)) + 1;
                    } else {
                        var $inp_enabled = $inp.filter(":enabled");
                        $inp_enabled = $inp_enabled.filter(":visible");
                        var nxtIdx = Number($inp_enabled.index(this)) + 1;
                        if (nxtIdx == $inp_enabled.length) {
                            //last one : init
                            nxtIdx = 0;
                        }
                    }

                    $inp_enabled.eq(nxtIdx).select();
                    $inp_enabled.eq(nxtIdx).trigger("focus");
                }
            }
        });
    };
    //20250425 lujunxia upd e
    me.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    // 個人情報_編集ボタンクリック
    $(".HMHRMS.editBtn").click(function () {
        me.edit();
    });
    // 個人情報_更新ボタンクリック
    $(".HMHRMS.updateBtn").click(function () {
        if (me.updateCheck() == false) {
            return;
        }
        if (
            $(".HMHRMS.txtFile").val() !== "" &&
            $(".HMHRMS.txtFile").val() !== null
        ) {
            me.file.send(me.update, me.errorFunc);
        } else {
            me.update();
        }
    });
    // 個人情報_キャンセルボタンクリック
    $(".HMHRMS.cancelBtn").click(function () {
        me.cancel();
    });
    $(".HMHRMS.cmdOpen").click(function () {
        me.cmdOpen_Click();
    });
    $(".HMHRMS.cmdPDelete").click(function () {
        me.cmdPDelete_Click();
    });
    /*
	'**********************************************************************
	' jqgrid 履历追加ボタン
	'**********************************************************************
	*/
    // jqgrid_家族状況_追加ボタンクリック
    $(".HMHRMS.family_addBtn").click(function () {
        me.dialogType("family", "add");
    });
    // jqgrid_学歴_追加ボタンクリック
    $(".HMHRMS.education_addBtn").click(function () {
        me.dialogType("education", "add");
    });
    // jqgrid_社外職歴_追加ボタンクリック
    $(".HMHRMS.othercompany_addBtn").click(function () {
        me.dialogType("othercompany", "add");
    });
    // jqgrid_表彰歴_追加ボタンクリック
    $(".HMHRMS.praise_addBtn").click(function () {
        me.dialogType("praise", "add");
    });
    // jqgrid_資格・免許_追加ボタンクリック
    $(".HMHRMS.qualication_addBtn").click(function () {
        me.dialogType("qualication", "add");
    });
    /*
	'**********************************************************************
	' jqgrid 履历編集ボタン
	'**********************************************************************
	*/
    // jqgrid_家族状況_編集ボタンクリック
    $(".HMHRMS.family_editBtn").click(function () {
        var rowids = $(me.family_grid_id).jqGrid("getGridParam", "selarrrow");

        if (rowids.length > 1 || rowids.length < 1 || rowids[0] === "norecs") {
            me.clsComFnc.FncMsgBox("W9999", "1件データを選択してください。");
            return;
        } else {
            me.dialogType("family", "edit");
        }
    });
    // jqgrid_学歴_編集ボタンクリック
    $(".HMHRMS.education_editBtn").click(function () {
        var rowids = $(me.education_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (rowids.length > 1 || rowids.length < 1 || rowids[0] === "norecs") {
            me.clsComFnc.FncMsgBox("W9999", "1件データを選択してください。");
            return;
        } else {
            me.dialogType("education", "edit");
        }
    });
    // jqgrid_社外職歴_編集ボタンクリック
    $(".HMHRMS.othercompany_editBtn").click(function () {
        var rowids = $(me.othercompany_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (rowids.length > 1 || rowids.length < 1 || rowids[0] === "norecs") {
            me.clsComFnc.FncMsgBox("W9999", "1件データを選択してください。");
            return;
        } else {
            me.dialogType("othercompany", "edit");
        }
    });
    // jqgrid_表彰歴_編集ボタンクリック
    $(".HMHRMS.praise_editBtn").click(function () {
        var rowids = $(me.praise_grid_id).jqGrid("getGridParam", "selarrrow");
        if (rowids.length > 1 || rowids.length < 1 || rowids[0] === "norecs") {
            me.clsComFnc.FncMsgBox("W9999", "1件データを選択してください。");
            return;
        } else {
            me.dialogType("praise", "edit");
        }
    });
    // jqgrid_資格・免許_編集ボタンクリック
    $(".HMHRMS.qualication_editBtn").click(function () {
        var rowids = $(me.qualication_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (rowids.length > 1 || rowids.length < 1 || rowids[0] === "norecs") {
            me.clsComFnc.FncMsgBox("W9999", "1件データを選択してください。");
            return;
        } else {
            me.dialogType("qualication", "edit");
        }
    });
    /*
	'**********************************************************************
	' jqgrid 履历削除ボタン
	'**********************************************************************
	*/
    // jqgrid_家族状況_削除ボタンクリック
    $(".HMHRMS.family_delBtn").click(function () {
        var family_sel_idxs = $(me.family_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        if (
            family_sel_idxs == undefined ||
            family_sel_idxs.length <= 0 ||
            family_sel_idxs[0] === "norecs"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "データを選択してください。");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.familyDel;
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    // jqgrid_学歴_削除ボタンクリック
    $(".HMHRMS.education_delBtn").click(function () {
        var education_sel_idxs = $(me.education_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        if (
            education_sel_idxs == undefined ||
            education_sel_idxs.length <= 0 ||
            education_sel_idxs[0] === "norecs"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "データを選択してください。");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.educationDel;
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    // jqgrid_社外職歴_削除ボタンクリック
    $(".HMHRMS.othercompany_delBtn").click(function () {
        var othercompany_sel_idxs = $(me.othercompany_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        if (
            othercompany_sel_idxs == undefined ||
            othercompany_sel_idxs.length <= 0 ||
            othercompany_sel_idxs[0] === "norecs"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "データを選択してください。");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.othercompanyDel;
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    // jqgrid_表彰歴_削除ボタンクリック
    $(".HMHRMS.praise_delBtn").click(function () {
        var praise_sel_idxs = $(me.praise_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        if (
            praise_sel_idxs == undefined ||
            praise_sel_idxs.length <= 0 ||
            praise_sel_idxs[0] === "norecs"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "データを選択してください。");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.praiseDel;
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    // jqgrid_資格・免許_削除ボタンクリック
    $(".HMHRMS.qualication_delBtn").click(function () {
        var qualication_sel_idxs = $(me.qualication_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        if (
            qualication_sel_idxs == undefined ||
            qualication_sel_idxs.length <= 0 ||
            qualication_sel_idxs[0] === "norecs"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "データを選択してください。");
            return;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.qualicationDel;
            me.clsComFnc.FncMsgBox("QY004");
        }
    });
    /*
	'**********************************************************************
	' dialog 日付コントロール
	'**********************************************************************
	*/
    // 家族状況_生年月日
    $(".HMHRMS.FamilyBirthDate").click(function () {
        if ($(this).datepicker("widget").css("display") == "none") {
            $(this).datepicker("show");
        } else {
            $(this).datepicker("hide");
        }
    });
    // 社外職歴_年月Start
    $(".HMHRMS.company_start").click(function () {
        if ($(this).datepicker("widget").css("display") == "none") {
            $(this).datepicker("show");
        } else {
            $(this).datepicker("hide");
        }
    });
    // 社外職歴_年月End
    $(".HMHRMS.company_end").click(function () {
        if ($(this).datepicker("widget").css("display") == "none") {
            $(this).datepicker("show");
        } else {
            $(this).datepicker("hide");
        }
    });
    /*
	'**********************************************************************
	' dialog 保 存ボタン
	'**********************************************************************
	*/
    // 家族状況_保 存ボタンクリック
    $(".HMHRMS.family_saveBtn").click(function () {
        me.FunfamilySaveBtn();
    });
    // 学歴_保 存ボタンクリック
    $(".HMHRMS.Education_saveBtn").click(function () {
        me.FuneducationSaveBtn();
    });
    // 社外職歴_保 存ボタンクリック
    $(".HMHRMS.othercompany_saveBtn").click(function () {
        me.FunothercompanySaveBtn();
    });
    // 表彰歴_保 存ボタンクリック
    $(".HMHRMS.praise_saveBtn").click(function () {
        me.FunpraiseSaveBtn();
    });
    // 資格・免許_保 存ボタンクリック
    $(".HMHRMS.qualication_saveBtn").click(function () {
        me.FunqualicationSaveBtn();
    });
    /*
	'**********************************************************************
	' dialog キャンセルボタン
	'**********************************************************************
	*/
    // 家族状況_キャンセルクリック
    $(".HMHRMS.family_cancelBtn").click(function () {
        $(".HMHRMS.Family").dialog("close");
    });
    // 学歴_キャンセルクリック
    $(".HMHRMS.Education_cancelBtn").click(function () {
        $(".HMHRMS.Education").dialog("close");
    });
    // 社外職歴_キャンセルクリック
    $(".HMHRMS.othercompany_cancelBtn").click(function () {
        $(".HMHRMS.othercompany").dialog("close");
    });
    // 表彰歴_キャンセルクリック
    $(".HMHRMS.praise_cancelBtn").click(function () {
        $(".HMHRMS.praise").dialog("close");
    });
    // 資格・免許_キャンセルクリック
    $(".HMHRMS.qualication_cancelBtn").click(function () {
        $(".HMHRMS.qualication").dialog("close");
    });
    // 個人情報と個人記録収縮制御
    $(".HMHRMS.toggle").click(function () {
        d = $(this).next($(".slide")).css("display");
        if (d == "block") {
            $(this).next($(".slide")).slideUp(200);
            $(this).children().prop({
                src: "./img/mcdropdown/ico1.gif",
            });
        } else {
            $(this).next($(".slide")).slideDown(200);
            $(this).children().prop({
                src: "./img/mcdropdown/ico2.gif",
            });
        }
    });
    // 表彰歴_年 フォーカス
    $(".HMHRMS.praiseyear").on("focus", function () {
        if ($(this).val() !== "") {
            me.praiseYearValue = "";
            me.praiseYearValue = $(this).val();
            $(this).val("");
        }
    });
    // 資格・免許_年 フォーカス
    $(".HMHRMS.qualicationyear").on("focus", function () {
        if ($(this).val() !== "") {
            me.qualicationYearValue = "";
            me.qualicationYearValue = $(this).val();
            $(this).val("");
        }
    });
    // 表彰歴_月 フォーカス
    $(".HMHRMS.praisemonth").on("focus", function () {
        if ($(this).val() !== "") {
            me.praiseMonthValue = "";
            me.praiseMonthValue = $(this).val();
            $(this).val("");
        }
    });
    // 資格・免許_月 フォーカス
    $(".HMHRMS.qualicationmonth").on("focus", function () {
        if ($(this).val() !== "") {
            me.qualicationMonthValue = "";
            me.qualicationMonthValue = $(this).val();
            $(this).val("");
        }
    });
    // 表彰歴_年 blur
    $(".HMHRMS.praiseyear").on("blur", function () {
        me.praiseYear();
    });
    // 表彰歴_月 blur
    $(".HMHRMS.praisemonth").on("blur", function () {
        if ($(".HMHRMS.praisemonth").val() == "") {
            $(".HMHRMS.praisemonth").val(me.praiseMonthValue);
        }
        $bor = true;
        $("#praisetimemonth option").each(function () {
            if ($(this).val() == $(".HMHRMS.praisemonth").val()) {
                $bor = false;
            }
        });
        if ($bor) {
            $(".HMHRMS.praisemonth").val(me.praiseMonthValue);
        }
    });
    // 資格・免許_年 blur
    $(".HMHRMS.qualicationyear").on("blur", function () {
        me.publicYear();
    });
    // 資格・免許_月 blur
    $(".HMHRMS.qualicationmonth").on("blur", function () {
        if ($(".HMHRMS.qualicationmonth").val() == "") {
            $(".HMHRMS.qualicationmonth").val(me.qualicationMonthValue);
        }
        $bor = true;
        $("#timemonth option").each(function () {
            if ($(this).val() == $(".HMHRMS.qualicationmonth").val()) {
                $bor = false;
            }
        });
        if ($bor) {
            $(".HMHRMS.qualicationmonth").val(me.qualicationMonthValue);
        }
    });
    // 通勤方法 change
    $(".HMHRMS.commuteMethod").change(function () {
        me.SelectChange($(this).val());
    });
    // 家族状況_生年月日 change
    $(".HMHRMS.FamilyBirthDate").change(function () {
        me.BDChange($(this).val());
    });
    // 表彰歴_年 change
    $(".HMHRMS.praiseyear").change(function () {
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") > -1) {
            $(".HMHRMS.praiseyear").trigger("blur");
        }
    });
    // 表彰歴_月 change
    $(".HMHRMS.praisemonth").change(function () {
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") > -1) {
            $(".HMHRMS.praisemonth").trigger("blur");
        }
    });
    // 資格・免許_年 change
    $(".HMHRMS.qualicationyear").change(function () {
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") > -1) {
            $(".HMHRMS.qualicationyear").trigger("blur");
        }
    });
    // 資格・免許_月 change
    $(".HMHRMS.qualicationmonth").change(function () {
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") > -1) {
            $(".HMHRMS.qualicationmonth").trigger("blur");
        }
    });
    $(".HMHRMS.livingcondition").comboSelect();
    //家族状況 table
    $(me.family_grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        rownumbers: true,
        multiselectWidth: 50,
        multiselect: true,
        loadui: "disable",
        height: 180,
        width: 1430,
        colModel: me.family_colModel,
        ondblClickRow: function (rowId) {
            $(me.family_grid_id).jqGrid("resetSelection");
            $(me.family_grid_id).jqGrid("setSelection", rowId);
            me.dialogType("family", "edit", rowId);
        },
    });
    //学歴 table
    $(me.education_grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        rownumbers: true,
        multiselectWidth: 50,
        multiselect: true,
        scroll: false,
        loadui: "disable",
        height: 180,
        width: 1430,
        colModel: me.education_colModel,
        ondblClickRow: function (rowId) {
            $(me.education_grid_id).jqGrid("resetSelection");
            $(me.education_grid_id).jqGrid("setSelection", rowId);
            me.dialogType("education", "edit", rowId);
        },
    });
    //社外職歴 table
    $(me.othercompany_grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        rownumbers: true,
        multiselectWidth: 50,
        multiselect: true,
        scroll: false,
        loadui: "disable",
        height: 180,
        width: 1430,
        colModel: me.othercompany_colModel,
        ondblClickRow: function (rowId) {
            $(me.othercompany_grid_id).jqGrid("resetSelection");
            $(me.othercompany_grid_id).jqGrid("setSelection", rowId);
            me.dialogType("othercompany", "edit", rowId);
        },
    });
    //表彰歴 table
    $(me.praise_grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        rownumbers: true,
        multiselectWidth: 50,
        multiselect: true,
        scroll: false,
        loadui: "disable",
        height: 180,
        width: 1430,
        colModel: me.praise_colModel,
        ondblClickRow: function (rowId) {
            $(me.praise_grid_id).jqGrid("resetSelection");
            $(me.praise_grid_id).jqGrid("setSelection", rowId);
            me.dialogType("praise", "edit", rowId);
        },
    });
    //資格・免許 table
    $(me.qualication_grid_id).jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        rownumbers: true,
        multiselectWidth: 50,
        multiselect: true,
        scroll: false,
        loadui: "disable",
        height: 180,
        width: 1430,
        colModel: me.qualication_colModel,
        ondblClickRow: function (rowId) {
            $(me.qualication_grid_id).jqGrid("resetSelection");
            $(me.qualication_grid_id).jqGrid("setSelection", rowId);
            me.dialogType("qualication", "edit", rowId);
        },
    });
    $(me.family_grid_id).setGridWidth($(window).width() * 0.85);
    $(me.education_grid_id).setGridWidth($(window).width() * 0.85);
    $(me.othercompany_grid_id).setGridWidth($(window).width() * 0.85);
    $(me.praise_grid_id).setGridWidth($(window).width() * 0.85);
    $(me.qualication_grid_id).setGridWidth($(window).width() * 0.85);
    $(window).resize(function () {
        $(me.family_grid_id).setGridWidth($(window).width() * 0.85);
        $(me.education_grid_id).setGridWidth($(window).width() * 0.85);
        $(me.othercompany_grid_id).setGridWidth($(window).width() * 0.85);
        $(me.praise_grid_id).setGridWidth($(window).width() * 0.85);
        $(me.qualication_grid_id).setGridWidth($(window).width() * 0.85);
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
        me.HMHRMS_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォーム初期化
	 '関 数 名：HMHRMS_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：無し
	 '**********************************************************************
	 */
    me.HMHRMS_Load = function () {
        $(".HMHRMS.uploadDiv").hide();
        var url = me.sys_id + "/" + me.id + "/getColumns";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            me.columns = result["columns"];

            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            me.empID = $.trim($(".LogineduserID").html());
            var arr = {
                data: me.empID,
            };
            var url = me.sys_id + "/" + me.id + "/fncEmpSearch";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                if (result["rows"].length == 0) {
                    $(".HMHRMS .EmpMsg").hide();
                    me.clsComFnc.FncMsgBox("W9999", "検索結果は存在しません。");
                    return;
                }

                me.search(result);
            };
            me.ajax.send(url, arr, 0);
        };
        me.ajax.send(url, "", 0);

        $(".ui-widget-content.HMHRMS.HMHRMS-layout-center").css(
            "overflow-y",
            "scroll"
        );
        $(".ui-jqgrid").css("margin-top", "10px");
    };

    // 家族状況 生年月日チェンジ
    me.BDChange = function () {
        var now = new Date();
        var strBirthday = new Date($(".HMHRMS.FamilyBirthDate").val());
        if (strBirthday > now) {
            $(".HMHRMS.FamilyBirthDate").val("");
            $(".HMHRMS.FamilyAge").val("");
            return;
        }
        var birthday = new Date(
            now.getFullYear(),
            strBirthday.getMonth(),
            strBirthday.getDate()
        );
        if (now >= birthday) {
            $(".HMHRMS.FamilyAge").val(
                now.getFullYear() - strBirthday.getFullYear()
            );
        } else {
            $(".HMHRMS.FamilyAge").val(
                now.getFullYear() - strBirthday.getFullYear() - 1
            );
        }
    };

    // 通勤方法
    me.selectGetData = function () {
        //コンボボックス設定
        for (key in me.commuteMethodOpt) {
            $("<option></option>")
                .val(me.commuteMethodOpt[key]["code_value"])
                .text(me.commuteMethodOpt[key]["code_name"])
                .appendTo(".HMHRMS.commuteMethod");
        }
    };

    // ページデータ全体取得
    me.search = function (result) {
        me.emp = result["rows"][0];
        me.empId = result["rows"][0]["empId"];
        me.schoolTypeOpt = result["selopts"]["schoolTypeOpt"];
        me.commuteMethodOpt = result["selopts"]["commuteMethodOpt"];
        me.sys_kb = result["sys_kb"];

        //写真
        if (me.emp["facePhotobase"]) {
            $(".HMHRMS.photo").prop("src", me.emp["facePhotobase"]);
        }

        if (me.emp["dispName"]) {
            $(".HMHRMS.showName").text(me.emp["dispName"]);
            $(".HMHRMS.showPhonetic").text(me.emp["dispNamePhonetic"]);
        } else {
            $(".HMHRMS.showName").text(me.emp["name"]);
            $(".HMHRMS.showPhonetic").text(me.emp["namePhonetic"]);
        }
        $(".HMHRMS.empZipCode").val(me.emp["empZipCode"]);
        me.empZipCodeOld = me.emp["empZipCode"];
        $(".HMHRMS.empAddress").val(me.emp["empAddress"]);
        if (
            me.emp["livingcondition"] == "自己所有" ||
            me.emp["livingcondition"] == "親族所有" ||
            me.emp["livingcondition"] == "借家等"
        ) {
            $(".HMHRMS.livingcondition").val(me.emp["livingcondition"]);
            $(".HMHRMS.livingcondition").comboSelect("_updateInput");
        }
        $(".HMHRMS.livingcondition")
            .parent()
            .children("input[type='text']")
            .val(me.emp["livingcondition"]);

        $(".HMHRMS.empTel").val(me.emp["empTel"]);
        $(".HMHRMS.empMobile").val(me.emp["empMobile"]);
        $(".HMHRMS.mail_address_personal").val(me.emp["mail_address_personal"]);
        $(".HMHRMS.mail_address_company").val(me.emp["mail_address_company"]);

        $(".HMHRMS.emergencyZipCode").val(me.emp["emergencyZipCode"]);
        me.emergencyZipCodeOld = me.emp["emergencyZipCode"];
        $(".HMHRMS.emergencyName").val(me.emp["emergencyName"]);
        $(".HMHRMS.emergencyPhonetic").val(me.emp["emergencyPhonetic"]);
        $(".HMHRMS.emergencyRelation").val(me.emp["emergencyRelation"]);
        $(".HMHRMS.emergencyAddress").val(me.emp["emergencyAddress"]);
        $(".HMHRMS.emergencyMobile").val(me.emp["emergencyMobile"]);
        $(".HMHRMS.emergencyTel").val(me.emp["emergencyTel"]);

        $(".HMHRMS.emergencyZipCode2").val(me.emp["emergencyZipCode2"]);
        me.emergencyZipCode2Old = me.emp["emergencyZipCode2"];
        $(".HMHRMS.emergencyName2").val(me.emp["emergencyName2"]);
        $(".HMHRMS.emergencyPhonetic2").val(me.emp["emergencyPhonetic2"]);
        $(".HMHRMS.emergencyRelation2").val(me.emp["emergencyRelation2"]);
        $(".HMHRMS.emergencyAddress2").val(me.emp["emergencyAddress2"]);
        $(".HMHRMS.emergencyMobile2").val(me.emp["emergencyMobile2"]);
        $(".HMHRMS.emergencyTel2").val(me.emp["emergencyTel2"]);

        if (me.flag) {
            if ($(".HMHRMS.commuteMethod").val("")) {
                me.selectGetData();
            }
        }
        $(".HMHRMS.commuteMethod").val(me.emp["commuteMethod"]);
        me.SelectChange($(".HMHRMS.commuteMethod").val());

        $(".HMHRMS.commuteDistance").val(me.emp["commuteDistance"]);
        $(".HMHRMS.hobbies").val(me.emp["hobbies"]);
        $(".HMHRMS.freeDescription").val(me.emp["freeDescription"]);
        $(".HMHRMS.microinformation").val(me.emp["microinformation"]);
        // 機微情報パーミッション
        if (me.sys_kb == "0" || me.sys_kb == "4" || me.empId == "11101") {
            $(".HMHRMS.microinformationshow").show();
        } else {
            $(".HMHRMS.microinformationshow").hide();
        }

        $(".HMHRMS.textarea").each(function () {
            var $this = $(this);
            if (!$this.prop("initAttrH")) {
                $this.prop("initAttrH", $this.outerHeight());
            }
            setAutoHeight(this).on("input", function () {
                return setAutoHeight(this);
            });
        });
        function setAutoHeight(elem) {
            var $obj = $(elem);
            return $obj
                .css({
                    height: $obj.prop("initAttrH"),
                    "overflow-y": "hidden",
                })
                .height(elem.scrollHeight);
        }

        me.family_jqgridTable = result["data"]["family"];
        me.education_jqgridTable = result["data"]["education"];
        me.othercompany_jqgridTable = result["data"]["othercompany"];
        me.praise_jqgridTable = result["data"]["praise"];
        me.qualication_jqgridTable = result["data"]["qualication"];

        // 家族状況
        for (keyIndex in me.family_jqgridTable) {
            var groupBy = function (arr, fn) {
                return arr
                    .map(
                        typeof fn === "function"
                            ? fn
                            : function (val) {
                                  return val[fn];
                              }
                    )
                    .reduce(function (acc, val, i) {
                        acc[val] = (acc[val] || []).concat(arr[i]);
                        return acc;
                    }, {});
            };
            var historyDatasfamily = me.family_jqgridTable
                ? groupBy(me.family_jqgridTable, "estid")
                : [];
        }
        var transToChildArrfamily = [];
        for (var item in historyDatasfamily) {
            var transToChildObjfamily = {};
            transToChildObjfamily["estid"] = item;

            var historyDatasArrfamily = historyDatasfamily[item];
            historyDatasArrfamily.forEach(function (data) {
                if (data["name"] == "birthday") {
                    if (
                        isNaN(data["value"]) &&
                        !isNaN(Date.parse(data["value"]))
                    ) {
                        data["value"] = data["value"]
                            ? new Date(data["value"]).Format("yyyy/MM/dd")
                            : null;
                    } else {
                        data["value"] = "";
                    }
                    transToChildObjfamily[data["name"]] = data["value"];

                    var ageContent = parseInt(me.jsGetfamilyAge(data["value"]));

                    transToChildObjfamily["age"] = ageContent ? ageContent : 0;
                }
                // else if (data["name"] == "together") {
                //     transToChildObjfamily[data["name"]] = data["value"];
                //     if (data["value"] == "0") {
                //         transToChildObjfamily["together_name"] = "别";
                //     } else {
                //         transToChildObjfamily["together_name"] = "同";
                //     }
                // }
                else if (data["name"] != "age") {
                    transToChildObjfamily[data["name"]] = data["value"];
                }
            });
            transToChildArrfamily.push(transToChildObjfamily);
        }

        for (var i = 0; i < transToChildArrfamily.length; i++) {
            for (var item in transToChildArrfamily[i]) {
                if (item != "age") {
                    transToChildArrfamily[i][item] = me.format(
                        transToChildArrfamily[i][item]
                    );
                }
            }
            $(me.family_grid_id).jqGrid(
                "addRowData",
                i,
                transToChildArrfamily[i]
            );
        }
        // 学歴
        for (keyIndex in me.education_jqgridTable) {
            var groupBy = function (arr, fn) {
                return arr
                    .map(
                        typeof fn === "function"
                            ? fn
                            : function (val) {
                                  return val[fn];
                              }
                    )
                    .reduce(function (acc, val, i) {
                        acc[val] = (acc[val] || []).concat(arr[i]);
                        return acc;
                    }, {});
            };
            var historyDataseducation = me.education_jqgridTable
                ? groupBy(me.education_jqgridTable, "estid")
                : [];
        }
        var transToChildArreducation = [];
        for (var item in historyDataseducation) {
            var transToChildObjeducation = {};
            transToChildObjeducation["estid"] = item;
            var historyDatasArreducation = historyDataseducation[item];
            historyDatasArreducation.forEach(function (data) {
                if (data["name"] == "kinds_of_schools") {
                    transToChildObjeducation[data["name"]] = data["value"];
                    for (schoolType in me.schoolTypeOpt) {
                        if (
                            me.schoolTypeOpt[schoolType]["code_value"] ==
                            data["value"]
                        ) {
                            transToChildObjeducation["kinds_of_schools_name"] =
                                me.schoolTypeOpt[schoolType]["code_name"];
                        }
                    }
                } else {
                    transToChildObjeducation[data["name"]] = data["value"];
                }
            });
            transToChildArreducation.push(transToChildObjeducation);
        }
        for (var i = 0; i < transToChildArreducation.length; i++) {
            for (var item in transToChildArreducation[i]) {
                transToChildArreducation[i][item] = me.format(
                    transToChildArreducation[i][item]
                );
            }
            $(me.education_grid_id).jqGrid(
                "addRowData",
                i,
                transToChildArreducation[i]
            );
        }

        // 社外職歴
        for (keyIndex in me.othercompany_jqgridTable) {
            var groupBy = function (arr, fn) {
                return arr
                    .map(
                        typeof fn === "function"
                            ? fn
                            : function (val) {
                                  return val[fn];
                              }
                    )
                    .reduce(function (acc, val, i) {
                        acc[val] = (acc[val] || []).concat(arr[i]);
                        return acc;
                    }, {});
            };
            var historyDatasothercompany = me.othercompany_jqgridTable
                ? groupBy(me.othercompany_jqgridTable, "estid")
                : [];
        }
        var transToChildArrothercompany = [];
        for (var item in historyDatasothercompany) {
            var transToChildObj = {};
            transToChildObj["estid"] = item;

            var historyDatasArr = historyDatasothercompany[item];
            historyDatasArr.forEach(function (data) {
                transToChildObj[data["name"]] = data["value"];
            });
            transToChildArrothercompany.push(transToChildObj);
        }

        for (var i = 0; i < transToChildArrothercompany.length; i++) {
            for (var item in transToChildArrothercompany[i]) {
                transToChildArrothercompany[i][item] = me.format(
                    transToChildArrothercompany[i][item]
                );
            }
            $(me.othercompany_grid_id).jqGrid(
                "addRowData",
                i,
                transToChildArrothercompany[i]
            );
        }

        // 表彰歴
        for (keyIndex in me.praise_jqgridTable) {
            var groupBy = function (arr, fn) {
                return arr
                    .map(
                        typeof fn === "function"
                            ? fn
                            : function (val) {
                                  return val[fn];
                              }
                    )
                    .reduce(function (acc, val, i) {
                        acc[val] = (acc[val] || []).concat(arr[i]);
                        return acc;
                    }, {});
            };
            var historyDatasObj = me.praise_jqgridTable
                ? groupBy(me.praise_jqgridTable, "estid")
                : [];
        }
        var transToChildArr = [];
        for (var item in historyDatasObj) {
            var transToChildObj = {};
            transToChildObj["estid"] = item;

            var historyDatasArr = historyDatasObj[item];
            historyDatasArr.forEach(function (data) {
                transToChildObj[data["name"]] = data["value"];
            });
            transToChildArr.push(transToChildObj);
        }

        for (var i = 0; i < transToChildArr.length; i++) {
            for (var item in transToChildArr[i]) {
                transToChildArr[i][item] = me.format(transToChildArr[i][item]);
            }
            $(me.praise_grid_id).jqGrid("addRowData", i, transToChildArr[i]);
        }
        // 資格・免許
        for (keyIndex in me.qualication_jqgridTable) {
            var groupBy = function (arr, fn) {
                return arr
                    .map(
                        typeof fn === "function"
                            ? fn
                            : function (val) {
                                  return val[fn];
                              }
                    )
                    .reduce(function (acc, val, i) {
                        acc[val] = (acc[val] || []).concat(arr[i]);
                        return acc;
                    }, {});
            };
            var historyDatasObjQualication = me.qualication_jqgridTable
                ? groupBy(me.qualication_jqgridTable, "estid")
                : [];
        }
        var transToChildArrQualication = [];
        for (var item in historyDatasObjQualication) {
            var transToChildObjQualication = {};
            transToChildObjQualication["estid"] = item;

            var historyDatasArr = historyDatasObjQualication[item];
            historyDatasArr.forEach(function (data) {
                transToChildObjQualication[data["name"]] = data["value"];
            });
            transToChildArrQualication.push(transToChildObjQualication);
        }
        for (var i = 0; i < transToChildArrQualication.length; i++) {
            for (var item in transToChildArrQualication[i]) {
                transToChildArrQualication[i][item] = me.format(
                    transToChildArrQualication[i][item]
                );
            }
            $(me.qualication_grid_id).jqGrid(
                "addRowData",
                i,
                transToChildArrQualication[i]
            );
        }

        me.dataReload();

        $(".HMHRMS .editBtn").trigger("focus");
    };

    // 家族状況年齢
    me.jsGetfamilyAge = function (strBirthday) {
        // 満年齢に戻る
        if (strBirthday === null) {
            me.familyAge = "";
            return;
        }
        var now = new Date();
        var strBirthday = new Date(strBirthday);
        var birthday = new Date(
            now.getFullYear(),
            strBirthday.getMonth(),
            strBirthday.getDate()
        );
        if (now >= birthday)
            me.familyAge = now.getFullYear() - strBirthday.getFullYear();
        else me.familyAge = now.getFullYear() - strBirthday.getFullYear() - 1;

        if (me.familyAge < 0) {
            //me.birthDate = null;
            me.familyAge = null;
        }

        return me.familyAge;
    };

    me.dataReload = function () {
        $(".HMHRMS.stretch *").prop("disabled", true);
        $(".HMHRMS.textarea").prop("disabled", true);
        $(".HMHRMS.textarea").css("background-color", "#e9e9e9");
        $(".HMHRMS.commuteMethod").css("background-color", "#e9e9e9");
        $(".HMHRMS.editBtn").button("enable");

        $(".HMHRMS.cancelBtn").hide();
        $(".HMHRMS.updateBtn").hide();
    };

    me.SelectChange = function (val) {
        if (val == "car") {
            $(".HMHRMS.hideControl").show();
            $(".HMHRMS.hideControl .commuteDistance").prop("disabled", false);
        } else {
            $(".HMHRMS.commuteDistance").val("");
            $(".HMHRMS.hideControl").hide();
            $(".HMHRMS.hideControl .commuteDistance").prop("disabled", true);
        }
    };

    me.cmdOpen_Click = function () {
        $(".HMHRMS.txtFile").val("");
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".jpeg,.png,.jpg";
        me.file.res = "HMHRMS";
        $("#HMHRMStmpFileUpload").html("");
        $("#HMHRMStmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            var arr = this.files[0].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[0].size > 2048000) {
                me.clsComFnc.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "社員個人記録入力",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            }
            if (fileType != "jpeg" && fileType != "png" && fileType != "jpg") {
                me.clsComFnc.MessageBox(
                    "使用できるファイルは.jpeg,.png,.jpgです。",
                    "社員個人記録入力",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            }
            $(".HMHRMS.txtFile").val(this.files[0].name);
            var file = this.files[0];
            var reader = new FileReader();
            reader.addEventListener(
                "load",
                function () {
                    $(".HMHRMS.photo").prop("src", reader.result);
                },
                false
            );

            if (file) {
                reader.readAsDataURL(file);
                $(".HMHRMS.cmdPDelete").css("display", "inline-block");
                $(".HMHRMS.cmdOpen").css("display", "none");
                $("#frmUpload").prop("value", me.emp["facePhoto"]);
            }
        });
        me.file.select_file();
    };
    me.errorFunc = function () {
        me.cancel();
    };
    me.cmdPDelete_Click = function () {
        $(".HMHRMS.txtFile").val("");
        $(".HMHRMS.cmdPDelete").css("display", "none");
        $(".HMHRMS.cmdOpen").css("display", "inline-block");
        $(".HMHRMS.photo").prop("src", me.emp["facePhotobase"]);
    };

    /*
	 '**********************************************************************
	 '処 理 名：編集
	 '関 数 名：edit
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：無し
	 ''**********************************************************************
	 */
    me.edit = function () {
        $(".HMHRMS.editBtn").css("display", "none");
        $(".HMHRMS.cancelBtn").css("display", "block");
        $(".HMHRMS.updateBtn").css("display", "block");
        $(".HMHRMS.stretch *").prop("disabled", false);
        $(".HMHRMS.textarea").prop("disabled", false);
        $(".HMHRMS.uploadDiv").show();
        $(".HMHRMS.cmdOpen").css("display", "inline-block");
        if (me.sys_kb != "0" && me.sys_kb != "4" && me.empId != "11101") {
            $(".HMHRMS.microinformation").prop("disabled", true);
        }
        $(".HMHRMS.textarea").css("background-color", "#FFFFFF");
        $(".HMHRMS.commuteMethod").css("background-color", "#FFFFFF");
        // 通勤距離 km
        me.SelectChange($(".HMHRMS.commuteMethod").val());
    };
    /*
	 '**********************************************************************
	 '処 理 名：更新
	 '関 数 名：update
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：編集の初期値設定、表示後
	 '　　　　　   データグリッドの再表示
	 ''**********************************************************************
	 */
    me.update = function (res) {
        if ($(".HMHRMS.commuteDistance").val() != "") {
            var distance = parseFloat($(".HMHRMS.commuteDistance").val());
        } else {
            var distance = "";
        }
        var arr = {
            // 写真
            facePhoto:
                res == undefined
                    ? me.emp["facePhoto"]
                    : "face/" + res["filename"],
            empZipCode: $(".HMHRMS.empZipCode").val(),
            empAddress: $(".HMHRMS.empAddress").val(),
            livingcondition: $(".HMHRMS.livingcondition")
                .parent()
                .children("input[type='text']")
                .val(),
            empTel: $(".HMHRMS.empTel").val(),
            empMobile: $(".HMHRMS.empMobile").val(),
            mail_address_personal: $(".HMHRMS.mail_address_personal").val(),
            mail_address_company: $(".HMHRMS.mail_address_company").val(),
            emergencyZipCode: $(".HMHRMS.emergencyZipCode").val(),
            emergencyName: $(".HMHRMS.emergencyName").val(),
            emergencyPhonetic: $(".HMHRMS.emergencyPhonetic").val(),
            emergencyRelation: $(".HMHRMS.emergencyRelation").val(),
            emergencyAddress: $(".HMHRMS.emergencyAddress").val(),
            emergencyMobile: $(".HMHRMS.emergencyMobile").val(),
            emergencyTel: $(".HMHRMS.emergencyTel").val(),

            emergencyZipCode2: $(".HMHRMS.emergencyZipCode2").val(),
            emergencyName2: $(".HMHRMS.emergencyName2").val(),
            emergencyPhonetic2: $(".HMHRMS.emergencyPhonetic2").val(),
            emergencyRelation2: $(".HMHRMS.emergencyRelation2").val(),
            emergencyAddress2: $(".HMHRMS.emergencyAddress2").val(),
            emergencyMobile2: $(".HMHRMS.emergencyMobile2").val(),
            emergencyTel2: $(".HMHRMS.emergencyTel2").val(),
            commuteMethod: $(".HMHRMS.commuteMethod").val(),
            commuteDistance: distance,
            hobbies: $(".HMHRMS.hobbies").val(),
            freeDescription: $(".HMHRMS.freeDescription").val(),
            microinformation: $(".HMHRMS.microinformation").val(),
        };

        // 比較後、ページ入力のデータ
        var ArrayDataNew = {};
        // 比較後、データベースデータ
        var ArrayDataOld = {};

        for (var i in arr) {
            if (me.emp[i] != arr[i]) {
                if (me.emp[i] === null && arr[i] === "") {
                    continue;
                }
                ArrayDataOld[i] = me.emp[i];
                ArrayDataNew[i] = arr[i];
            }
        }

        var data = {
            empId: me.empID,
            // 更新前
            pre_emp: ArrayDataOld,
            // 更新後
            emp: ArrayDataNew,
        };
        var url = me.sys_id + "/" + me.id + "/fncEmpUpdate";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.cmdPDelete_Click();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.emp = result["rows"][0];
            $(".HMHRMS.commuteDistance").val(distance);
            $(".HMHRMS.stretch *").prop("disabled", true);
            $(".HMHRMS.editBtn").css("display", "block");
            $(".HMHRMS.cancelBtn").css("display", "none");
            $(".HMHRMS.updateBtn").css("display", "none");
            $(".HMHRMS.cmdOpen").css("display", "none");
            $(".HMHRMS.cmdPDelete").css("display", "none");
            $(".HMHRMS.uploadDiv").hide();
            $(".HMHRMS.txtFile").val("");
            me.dataReload();
            me.clsComFnc.FncMsgBox("I0008");
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：キャンセル
	 '関 数 名：cancel
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：無し
	 ''**********************************************************************
	 */
    me.cancel = function () {
        $(me.family_grid_id).jqGrid("clearGridData");
        $(me.education_grid_id).jqGrid("clearGridData");
        $(me.othercompany_grid_id).jqGrid("clearGridData");
        $(me.praise_grid_id).jqGrid("clearGridData");
        $(me.qualication_grid_id).jqGrid("clearGridData");

        $(".HMHRMS.editBtn").css("display", "block");
        $(".HMHRMS.cancelBtn").css("display", "none");
        $(".HMHRMS.updateBtn").css("display", "none");
        $(".HMHRMS.cmdOpen").css("display", "none");
        $(".HMHRMS.cmdPDelete").css("display", "none");
        $(".HMHRMS.txtFile").val("");

        me.flag = false;
        me.HMHRMS_Load();
    };
    me.praiseYear = function () {
        if ($(".HMHRMS.praiseyear").val() == "") {
            $(".HMHRMS.praiseyear").val(me.praiseYearValue);
        }
        var selectyear = $(".HMHRMS.praiseyear").val();
        var objfocus = ".HMHRMS.praiseyear";
        if (selectyear != "") {
            var dataname = "年";
            if (me.watchYearFnc(selectyear, dataname, objfocus) == false) {
                $(".HMHRMS.praiseyear").val("");
                return;
            }
            var myDate = new Date();
            var curYear = myDate.getFullYear();
            if (selectyear < 1970) {
                $(".HMHRMS.praisedatalist").html("");
                for (i = curYear; i >= selectyear; i--) {
                    $("<option></option>")
                        .val(i)
                        .appendTo(".HMHRMS.praisedatalist");
                }
            }
            if (selectyear >= 1970) {
                $(".HMHRMS.praisedatalist").html("");
                for (i = curYear; i >= 1970; i--) {
                    $("<option></option>")
                        .val(i)
                        .appendTo(".HMHRMS.praisedatalist");
                }
            }
        }
    };
    me.publicYear = function () {
        if ($(".HMHRMS.qualicationyear").val() == "") {
            $(".HMHRMS.qualicationyear").val(me.qualicationYearValue);
        }
        var selectyear = $(".HMHRMS.qualicationyear").val();
        var objfocus = ".HMHRMS.qualicationyear";
        if (selectyear != "") {
            var dataname = "取得時期(年)";
            if (me.watchYearFnc(selectyear, dataname, objfocus) == false) {
                $(".HMHRMS.qualicationyear").val("");
                return;
            }
            var myDate = new Date();
            var curYear = myDate.getFullYear();
            $(".HMHRMS.qualicationdatalist option").remove();
            if (selectyear < 1970) {
                $(".HMHRMS.qualicationdatalist").html("");
                for (i = curYear; i >= selectyear; i--) {
                    $("<option></option>")
                        .val(i)
                        .appendTo(".HMHRMS.qualicationdatalist");
                }
            }
            if (selectyear >= 1970) {
                $(".HMHRMS.qualicationdatalist").html("");
                for (i = curYear; i >= 1970; i--) {
                    $("<option></option>")
                        .val(i)
                        .appendTo(".HMHRMS.qualicationdatalist");
                }
            }
        }
    };
    me.watchYearFnc = function (newVal, dataname, objfocus) {
        var myDate = new Date();
        var curYear = myDate.getFullYear();
        var regu = /^(1949|19[5-9]\d|20\d{2}|2100)$/;
        if (!regu.exec(newVal)) {
            setTimeout(function () {
                me.clsComFnc.ObjFocus = $(objfocus);
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    dataname +
                        "は「yyyy」形式で1949以降の年度を入力してください。"
                );
            }, 0);
            return false;
        }
        if (newVal > curYear) {
            setTimeout(function () {
                me.clsComFnc.ObjFocus = $(objfocus);
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    dataname + "は当年以前の年を入力してください。"
                );
            }, 0);
            return false;
        }
        return true;
    };
    /*
	 '**********************************************************************
	 '処 理 名：編集画面の表示
	 '関 数 名：dialogType
	 '引    数：無し
	 '戻 り 値：無し
	 '処理説明：編集画面の初期値設定、表示後
	 '　　　　　   データグリッドの再表示
	 ''**********************************************************************
	 */
    me.dialogType = function (diatype, buttype, rowId) {
        // dialog種類
        me.diatype = diatype;
        me.dialogurl = "";
        var dialogwidth = 0;
        var dialogheight = 0;
        var dialogtitle = "";
        switch (me.diatype) {
            case "family":
                me.dialogurl = ".HMHRMS.Family";
                dialogwidth = me.ratio === 1.5 ? 396 : 420;
                dialogheight = me.ratio === 1.5 ? 215 : 280;
                dialogtitle = "家族状況";
                break;
            case "education":
                me.dialogurl = ".HMHRMS.Education";
                dialogwidth = me.ratio === 1.5 ? 427 : 470;
                dialogheight = me.ratio === 1.5 ? 230 : 320;
                dialogtitle = "学歴";
                break;
            case "othercompany":
                me.dialogurl = ".HMHRMS.othercompany";
                dialogwidth = me.ratio === 1.5 ? 447 : 493;
                dialogheight = me.ratio === 1.5 ? 265 : 325;
                dialogtitle = "社外職歴";
                break;
            case "praise":
                me.dialogurl = ".HMHRMS.praise";
                dialogwidth = me.ratio === 1.5 ? 396 : 420;
                dialogheight = me.ratio === 1.5 ? 155 : 220;
                dialogtitle = "表彰歴";
                break;
            case "qualication":
                me.dialogurl = ".HMHRMS.qualication";
                dialogwidth = me.ratio === 1.5 ? 416 : 448;
                dialogheight = me.ratio === 1.5 ? 150 : 220;
                dialogtitle = "資格・免許";
                break;
        }
        $(me.dialogurl).dialog({
            autoOpen: false,
            width: dialogwidth,
            height: dialogheight,
            modal: true,
            resizable: false,
            title: dialogtitle,
            close: function () {},
        });
        $(me.dialogurl).dialog("open");
        switch (me.diatype) {
            case "family":
                $(".HMHRMS.FamilyBirthDate").prop("readonly", "readonly");
                $("input[type='text'][readonly='readonly']").css(
                    "background-color",
                    "#FFFFFF"
                );
                me.Frmfamily_Load(buttype, rowId);
                break;
            case "education":
                me.Frmeducation_Load(buttype, rowId);
                break;
            case "praise":
                me.Frmpraise_Load(buttype, rowId);
                break;
            case "qualication":
                me.Frmqualication_Load(buttype, rowId);
                break;
            case "othercompany":
                $(".HMHRMS.company_start").prop("readonly", "readonly");
                $(".HMHRMS.company_end").prop("readonly", "readonly");
                $("input[type='text'][readonly='readonly']").css(
                    "background-color",
                    "#FFFFFF"
                );
                me.Frmothercompany_Load(buttype, rowId);
                break;
        }
    };

    // 家族状況編集画面の表示
    me.Frmfamily_Load = function (burrontype, rowId) {
        $(".HMHRMS.FamilyName").val("");
        $(".HMHRMS.FamilyNamePhonetic").val("");
        $(".HMHRMS.FamilyRelation").val("");
        $(".HMHRMS.FamilyBirthDate").val("");
        $(".HMHRMS.FamilyAge").val("");
        $("#bie").prop("checked", true);
        if (me.columns[me.diatype].length > 0) {
            for (var i = 0; i < me.columns[me.diatype].length; i++) {
                if (me.columns[me.diatype][i]["name"] == "name") {
                    me.familyName = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "namePhonetic"
                ) {
                    me.familyNamePhonetic = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "relation") {
                    me.familyRalation = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "birthday") {
                    me.birthDate = me.columns[me.diatype][i]["id"];
                }
                // else if (me.columns[me.diatype][i]["name"] == "together") {
                //     me.familyLive = me.columns[me.diatype][i]["id"];
                // }
                else if (me.columns[me.diatype][i]["name"] == "age") {
                    me.familyAge = me.columns[me.diatype][i]["id"];
                }
            }
        }
        me.burrontype = burrontype;
        if (!rowId) {
            rowId = $(me.family_grid_id).jqGrid("getGridParam", "selrow");
        }
        me.familyrowData = $(me.family_grid_id).jqGrid("getRowData", rowId);
        if (me.burrontype == "edit") {
            $(".HMHRMS.FamilyName").val(me.familyrowData["name"]);
            $(".HMHRMS.FamilyNamePhonetic").val(
                me.familyrowData["namePhonetic"]
            );
            $(".HMHRMS.FamilyRelation").val(me.familyrowData["relation"]);
            $(".HMHRMS.FamilyBirthDate").val(me.familyrowData["birthday"]);
            $(".HMHRMS.FamilyAge").val(me.familyrowData["age"]);
            // if (me.familyrowData["together"] == "1") {
            //     $("#tong").prop("checked", true);
            // } else {
            //     $("#bie").prop("checked", true);
            // }

            var now = new Date();
            var strBirthday = new Date($(".HMHRMS.FamilyBirthDate").val());
            var birthday = new Date(
                now.getFullYear(),
                strBirthday.getMonth(),
                strBirthday.getDate()
            );

            if (now >= birthday)
                me.familyAge = now.getFullYear() - strBirthday.getFullYear();
            else
                me.familyAge =
                    now.getFullYear() - strBirthday.getFullYear() - 1;

            if (me.familyAge < 0) {
                //me.birthDate = null;
                me.familyAge = null;
            }
        }
    };
    // 学歴編集画面の表示
    me.Frmeducation_Load = function (burrontype, rowId) {
        $(".HMHRMS.SchoolName").val("");
        $(".HMHRMS.Disciplines").val("");
        $(".HMHRMS.AdressCountry").val("");
        $(".HMHRMS.AdressPrefecture").val("");
        $(".HMHRMS.AdressCity").val("");
        if (me.columns[me.diatype].length > 0) {
            for (var i = 0; i < me.columns[me.diatype].length; i++) {
                if (me.columns[me.diatype][i]["name"] == "kinds_of_schools") {
                    me.school = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "school_name") {
                    me.schoolName = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "disciplines") {
                    me.disciplines = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "address_country"
                ) {
                    me.country = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "address_prefecture"
                ) {
                    me.address = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "address_city"
                ) {
                    me.city = me.columns[me.diatype][i]["id"];
                }
            }
        }
        $(".HMHRMS.SchoolType").html("");
        for (key in me.schoolTypeOpt) {
            $("<option></option>")
                .val(me.schoolTypeOpt[key]["code_value"])
                .text(me.schoolTypeOpt[key]["code_name"])
                .appendTo(".HMHRMS.SchoolType");
        }
        $(".HMHRMS.SchoolType").val("");
        me.burrontype = burrontype;
        if (me.burrontype == "edit") {
            if (!rowId) {
                rowId = $(me.education_grid_id).jqGrid(
                    "getGridParam",
                    "selrow"
                );
            }
            var educationrowData = $(me.education_grid_id).jqGrid(
                "getRowData",
                rowId
            );
            $(".HMHRMS.SchoolType").val(educationrowData["kinds_of_schools"]);
            $(".HMHRMS.SchoolName").val(educationrowData["school_name"]);
            $(".HMHRMS.Disciplines").val(educationrowData["disciplines"]);
            $(".HMHRMS.AdressCountry").val(educationrowData["address_country"]);
            $(".HMHRMS.AdressPrefecture").val(
                educationrowData["address_prefecture"]
            );
            $(".HMHRMS.AdressCity").val(educationrowData["address_city"]);
        }
    };

    // 社外職歴編集画面の表示
    me.Frmothercompany_Load = function (burrontype, rowId) {
        me.burrontype = burrontype;
        $(".company_start").val("");
        $(".company_end").val("");
        $(".company_country").val("");
        $(".company_prefecture").val("");
        $(".company_city").val("");
        $(".company_name").val("");
        $(".company_position").val("");
        $(".job_content").val("");
        if (me.columns[me.diatype].length > 0) {
            for (var i = 0; i < me.columns[me.diatype].length; i++) {
                if (me.columns[me.diatype][i]["name"] == "company_start") {
                    me.companystart = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "company_end") {
                    me.companyend = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "company_country"
                ) {
                    me.companycountry = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "company_prefecture"
                ) {
                    me.companyprefecture = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "company_city"
                ) {
                    me.companycity = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "company_name"
                ) {
                    me.companyname = me.columns[me.diatype][i]["id"];
                } else if (
                    me.columns[me.diatype][i]["name"] == "company_position"
                ) {
                    me.companyposition = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "job_content") {
                    me.jobcontent = me.columns[me.diatype][i]["id"];
                }
            }
        }
        if (me.burrontype == "edit") {
            if (!rowId) {
                rowId = $(me.othercompany_grid_id).jqGrid(
                    "getGridParam",
                    "selrow"
                );
            }
            var praiserowData = $(me.othercompany_grid_id).jqGrid(
                "getRowData",
                rowId
            );
            $(".company_start").val(praiserowData["company_start"]);
            $(".company_end").val(praiserowData["company_end"]);
            $(".company_country").val(praiserowData["company_country"]);
            $(".company_prefecture").val(praiserowData["company_prefecture"]);
            $(".company_city").val(praiserowData["company_city"]);
            $(".company_name").val(praiserowData["company_name"]);
            $(".company_position").val(praiserowData["company_position"]);
            $(".job_content").val(praiserowData["job_content"]);
        }
    };

    // 表彰歴編集画面の表示
    me.Frmpraise_Load = function (burrontype, rowId) {
        me.praiseYearValue = "";
        me.praiseMonthValue = "";
        $(".praisecontent").val("");
        $(".praisemonth").val("");
        $(".praiseyear").val("");
        if (navigator.userAgent.toUpperCase().indexOf("CHROME") <= -1) {
            $(".HMHRMS.praise_saveBtn").trigger("focus");
        }

        if (me.columns[me.diatype].length > 0) {
            for (var i = 0; i < me.columns[me.diatype].length; i++) {
                if (me.columns[me.diatype][i]["name"] == "praise_content") {
                    me.praisecontent = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "praise_date") {
                    me.praisedate = me.columns[me.diatype][i]["id"];
                }
            }
        }
        me.burrontype = burrontype;
        var myDate = new Date();
        var year = myDate.getFullYear();

        $(".praisedatalist").html("");
        for (i = year; i >= 1970; i--) {
            $("<option></option>").val(i).appendTo(".praisedatalist");
        }

        if (me.burrontype == "edit") {
            if (!rowId) {
                rowId = $(me.praise_grid_id).jqGrid("getGridParam", "selrow");
            }
            var praiserowData = $(me.praise_grid_id).jqGrid(
                "getRowData",
                rowId
            );
            var str = praiserowData["praise_date"].split("/");
            $(".praisecontent").val(praiserowData["praise_content"]);

            var curYear = myDate.getFullYear();
            var regu = /^(1949|19[5-9]\d|20\d{2}|2100)$/;
            if (!regu.exec(str[0])) {
                str[0] = "";
            }
            if (str[0] > curYear) {
                str[0] = "";
            }
            $bor = true;
            $("#praisetimemonth option").each(function () {
                if ($(this).val() == str[1]) {
                    $bor = false;
                }
            });
            if ($bor) {
                str[1] = "";
            }
            $(".praisemonth").val(str[1]);
            $(".praiseyear").val(str[0]);
        }
    };
    // 資格・免許編集画面の表示
    me.Frmqualication_Load = function (burrontype, rowId) {
        me.qualicationYearValue = "";
        me.qualicationMonthValue = "";
        $(".qualicationlicense").val("");
        $(".qualicationmonth").val("");
        $(".qualicationyear").val("");
        if (me.columns[me.diatype].length > 0) {
            for (var i = 0; i < me.columns[me.diatype].length; i++) {
                if (me.columns[me.diatype][i]["name"] == "public_content") {
                    me.publiccontent = me.columns[me.diatype][i]["id"];
                } else if (me.columns[me.diatype][i]["name"] == "get_date") {
                    me.getdate = me.columns[me.diatype][i]["id"];
                }
            }
        }
        me.burrontype = burrontype;
        var myDate = new Date();
        var year = myDate.getFullYear();
        $(".qualicationdatalist").html("");
        for (i = year; i >= 1970; i--) {
            $("<option></option>").val(i).appendTo(".qualicationdatalist");
        }

        if (me.burrontype == "edit") {
            if (!rowId) {
                rowId = $(me.qualication_grid_id).jqGrid(
                    "getGridParam",
                    "selrow"
                );
            }
            var qualicationrowData = $(me.qualication_grid_id).jqGrid(
                "getRowData",
                rowId
            );
            var str = qualicationrowData["get_date"].split("/");
            $(".qualicationlicense").val(qualicationrowData["public_content"]);

            var curYear = myDate.getFullYear();
            var regu = /^(1949|19[5-9]\d|20\d{2}|2100)$/;
            if (!regu.exec(str[0])) {
                str[0] = "";
            }
            if (str[0] > curYear) {
                str[0] = "";
            }
            $bor = true;
            $("#timemonth option").each(function () {
                if ($(this).val() == str[1]) {
                    $bor = false;
                }
            });
            if ($bor) {
                str[1] = "";
            }
            $(".qualicationmonth").val(str[1]);
            $(".qualicationyear").val(str[0]);
        }
    };
    //  データフォーマット
    me.format = function (data) {
        if (data == "") {
            return "";
        } else {
            data = data
                .replace(/[<]/g, "&lt;")
                .replace(/[>]/g, "&gt;")
                .replace(/\r\n/g, "")
                .replace(/\n/g, "")
                .replace(/[\"]/g, "&quot;")
                .replace(/[\']/g, "&apos;")
                .replace(/[\r\n]/g, "<br>");
            return data;
        }
    };
    // 学歴_保存
    me.FuneducationSaveBtn = function () {
        var seldata_type = $(".HMHRMS.SchoolType").val();
        var obfocus_type = ".HMHRMS.SchoolType";
        var dataname_type = "学校種別";
        if (
            me.selectdatacheck(dataname_type, seldata_type, obfocus_type) ==
            false
        ) {
            return;
        }
        var seldata_name = $(".HMHRMS.SchoolName").val();
        var obfocus_name = ".HMHRMS.SchoolName";
        var dataname_name = "学校名";
        if (
            me.inputdatacheck(dataname_name, seldata_name, obfocus_name) ==
            false
        ) {
            return;
        }
        var obfocus_country = ".HMHRMS.AdressCountry";
        var seldata_country = $(".HMHRMS.AdressCountry").val();
        var dataname_country = "所在地（国）";
        if (
            me.inputdatacheck(
                dataname_country,
                seldata_country,
                obfocus_country
            ) == false
        ) {
            return;
        }
        var obfocus_prefecture = ".HMHRMS.AdressPrefecture";
        var seldata_prefecture = $(".HMHRMS.AdressPrefecture").val();
        var dataname_prefecture = "所在地（都道府県）";
        if (
            me.inputdatacheck(
                dataname_prefecture,
                seldata_prefecture,
                obfocus_prefecture
            ) == false
        ) {
            return;
        }
        var obfocus_city = ".HMHRMS.AdressCity";
        var seldata_city = $(".HMHRMS.AdressCity").val();
        var dataname_city = "所在地（市）";
        if (
            me.inputdatacheck(dataname_city, seldata_city, obfocus_city) ==
            false
        ) {
            return;
        }

        var selIRow = 0;
        var rowData = $(me.education_grid_id).jqGrid("getRowData");
        if (rowData.length > 0) {
            var selIRow = rowData.length;
        }

        var arr = {
            kinds_of_schools: $(".HMHRMS.SchoolType").val(),
            school_name: $(".HMHRMS.SchoolName").val(),
            disciplines: $(".HMHRMS.Disciplines").val(),
            address_country: $(".HMHRMS.AdressCountry").val(),
            address_prefecture: $(".HMHRMS.AdressPrefecture").val(),
            address_city: $(".HMHRMS.AdressCity").val(),
        };
        var custom_values = {};
        custom_values[me.school] = $(".HMHRMS.SchoolType").val();
        custom_values[me.schoolName] = $(".HMHRMS.SchoolName").val();
        custom_values[me.disciplines] = $(".HMHRMS.Disciplines").val();
        custom_values[me.country] = $(".HMHRMS.AdressCountry").val();
        custom_values[me.address] = $(".HMHRMS.AdressPrefecture").val();
        custom_values[me.city] = $(".HMHRMS.AdressCity").val();
        if (me.burrontype == "add") {
            for (var i = 0; i < rowData.length; i++) {
                if (rowData[i]["kinds_of_schools"] == seldata_type) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "「" +
                            rowData[i]["kinds_of_schools_name"] +
                            "」" +
                            "は既に存在します。"
                    );
                    return;
                }
            }
            var employee_sub_table = {
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            var custom_values_history = [];
            for (var i in arr) {
                values = {};
                values = {
                    item2: i,
                    row: selIRow + 1,
                    update_before: "",
                    update_after: arr[i],
                };
                custom_values_history.push(values);
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };

            var url = me.sys_id + "/" + me.id + "/funInsert";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    result["error"] = result["error"].replace("%d", "学歴");
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                // 行追加
                $(me.education_grid_id).jqGrid("addRowData", selIRow, {
                    kinds_of_schools_name: $(".HMHRMS.SchoolType")
                        .find("option:selected")
                        .text(),
                    kinds_of_schools: $(".HMHRMS.SchoolType").val(),
                    school_name: me.format($(".HMHRMS.SchoolName").val()),
                    disciplines: me.format($(".HMHRMS.Disciplines").val()),
                    address_country: me.format(
                        $(".HMHRMS.AdressCountry").val()
                    ),
                    address_prefecture: me.format(
                        $(".HMHRMS.AdressPrefecture").val()
                    ),
                    address_city: me.format($(".HMHRMS.AdressCity").val()),
                    estid: result["estid"],
                });
                me.clsComFnc.FncMsgBox("I0002");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }

        if (me.burrontype == "edit") {
            var rowid = $(me.education_grid_id).jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowDataSame = jQuery(me.education_grid_id).jqGrid(
                "getRowData",
                rowid
            );
            if (seldata_type != rowDataSame["kinds_of_schools"]) {
                for (var i = 0; i < rowData.length; i++) {
                    if (rowData[i]["kinds_of_schools"] == seldata_type) {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "「" +
                                rowData[i]["kinds_of_schools_name"] +
                                "」" +
                                "は既に存在します。"
                        );
                        return;
                    }
                }
            }

            var rowID = $(me.education_grid_id).jqGrid(
                "getGridParam",
                "selrow"
            );
            var educationrowData = $(me.education_grid_id).jqGrid(
                "getRowData",
                rowID
            );
            var employee_sub_table = {
                estid: educationrowData["estid"],
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            //履历
            var custom_values_history = [];
            for (var i in arr) {
                if (educationrowData[i] != arr[i]) {
                    values = {};
                    values = {
                        item2: i,
                        row: parseInt(rowID) + 1,
                        update_before: educationrowData[i],
                        update_after: arr[i],
                    };
                    custom_values_history.push(values);
                }
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };
            var url = me.sys_id + "/" + me.id + "/funUpdate";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    result["error"] = result["error"].replace("%d", "学歴");
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "kinds_of_schools_name",
                    $(".HMHRMS.SchoolType").find("option:selected").text()
                );
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "kinds_of_schools",
                    $(".HMHRMS.SchoolType").val()
                );
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "school_name",
                    me.format($(".HMHRMS.SchoolName").val())
                );
                if ($(".HMHRMS.Disciplines").val() == "") {
                    $(me.education_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "disciplines",
                        null
                    );
                } else {
                    $(me.education_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "disciplines",
                        me.format($(".HMHRMS.Disciplines").val())
                    );
                }
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "address_country",
                    me.format($(".HMHRMS.AdressCountry").val())
                );
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "address_prefecture",
                    me.format($(".HMHRMS.AdressPrefecture").val())
                );
                $(me.education_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "address_city",
                    me.format($(".HMHRMS.AdressCity").val())
                );
                me.clsComFnc.FncMsgBox("I0008");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
    };

    // 家族状況_保存
    me.FunfamilySaveBtn = function () {
        var seldata_FN = $(".HMHRMS.FamilyName").val();
        var obfocus_FN = ".HMHRMS.FamilyName";
        var dataname_FN = "氏名";
        if (me.inputdatacheck(dataname_FN, seldata_FN, obfocus_FN) == false) {
            return;
        }
        var seldata_FNP = $(".HMHRMS.FamilyNamePhonetic").val();
        var obfocus_FNP = ".HMHRMS.FamilyNamePhonetic";
        var dataname_FNP = "フリガナ";
        if (
            me.inputdatacheck(dataname_FNP, seldata_FNP, obfocus_FNP) == false
        ) {
            return;
        }
        var seldata_FR = $(".HMHRMS.FamilyRelation").val();
        var obfocus_FR = ".HMHRMS.FamilyRelation";
        var dataname_FR = "続柄";
        if (me.inputdatacheck(dataname_FR, seldata_FR, obfocus_FR) == false) {
            return;
        }
        var obfocus_FBD = ".HMHRMS.FamilyBirthDate";
        var seldata_FBD = $(".HMHRMS.FamilyBirthDate").val();
        var dataname_FBD = "生年月日";
        if (
            me.selectdatacheck(dataname_FBD, seldata_FBD, obfocus_FBD) == false
        ) {
            return;
        }
        // var together_name = "";
        // if ($("#tong").is(":checked")) {
        //     together = "1";
        //     together_name = "同";
        // }
        // if ($("#bie").is(":checked")) {
        //     together = "0";
        //     together_name = "别";
        // }
        var arr = {
            name: $(".HMHRMS.FamilyName").val(),
            namePhonetic: $(".HMHRMS.FamilyNamePhonetic").val(),
            relation: $(".HMHRMS.FamilyRelation").val(),
            birthday: $(".HMHRMS.FamilyBirthDate").val(),
            // together: together,
        };
        var custom_values = {};
        custom_values[me.familyName] = $(".HMHRMS.FamilyName").val();
        custom_values[me.familyNamePhonetic] = $(
            ".HMHRMS.FamilyNamePhonetic"
        ).val();
        custom_values[me.familyRalation] = $(".HMHRMS.FamilyRelation").val();
        custom_values[me.birthDate] = $(".HMHRMS.FamilyBirthDate").val();
        // custom_values[me.familyLive] = together;
        if (me.burrontype == "add") {
            var employee_sub_table = {
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            //取得最大行数
            var selIRow = 0;

            var rowData = $(me.family_grid_id).jqGrid("getRowData");
            if (rowData.length > 0) {
                var selIRow = rowData.length;
            }

            var custom_values_history = [];
            for (var i in arr) {
                values = {};
                values = {
                    item2: i,
                    row: selIRow + 1,
                    update_before: "",
                    update_after: arr[i],
                };
                custom_values_history.push(values);
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };

            var url = me.sys_id + "/" + me.id + "/funInsert";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "家族状況"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                //行追加
                $(me.family_grid_id).jqGrid("addRowData", selIRow, {
                    name: me.format($(".HMHRMS.FamilyName").val()),
                    namePhonetic: me.format(
                        $(".HMHRMS.FamilyNamePhonetic").val()
                    ),
                    relation: me.format($(".HMHRMS.FamilyRelation").val()),
                    birthday: $(".HMHRMS.FamilyBirthDate").val(),
                    age: $(".HMHRMS.FamilyAge").val(),
                    // together: together,
                    // together_name: together_name,
                    estid: result["estid"],
                });
                me.clsComFnc.FncMsgBox("I0002");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
        if (me.burrontype == "edit") {
            var rowID = $(me.family_grid_id).jqGrid("getGridParam", "selrow");
            var familyrowData = $(me.family_grid_id).jqGrid(
                "getRowData",
                rowID
            );
            var employee_sub_table = {
                estid: familyrowData["estid"],
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            var custom_values_history = [];
            for (var i in arr) {
                if (familyrowData[i] != arr[i]) {
                    values = {};
                    values = {
                        item2: i,
                        row: parseInt(rowID) + 1,
                        update_before: familyrowData[i],
                        update_after: arr[i],
                    };
                    custom_values_history.push(values);
                }
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };

            var url = me.sys_id + "/" + me.id + "/funUpdate";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "家族状況"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                $(me.family_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "name",
                    me.format($(".HMHRMS.FamilyName").val())
                );
                $(me.family_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "namePhonetic",
                    me.format($(".HMHRMS.FamilyNamePhonetic").val())
                );
                $(me.family_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "relation",
                    me.format($(".HMHRMS.FamilyRelation").val())
                );
                $(me.family_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "birthday",
                    $(".HMHRMS.FamilyBirthDate").val()
                );
                $(me.family_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "age",
                    $(".HMHRMS.FamilyAge").val()
                );
                // $(me.family_grid_id).jqGrid(
                //     "setCell",
                //     rowID,
                //     "together",
                //     together
                // );
                // $(me.family_grid_id).jqGrid(
                //     "setCell",
                //     rowID,
                //     "together_name",
                //     together_name
                // );
                me.clsComFnc.FncMsgBox("I0008");
                $(me.dialogurl).dialog("close");
            };

            me.ajax.send(url, data, 0);
        }
    };

    // 表彰歴_保存
    me.FunpraiseSaveBtn = function () {
        var seldata_year = $(".HMHRMS.praiseyear").val();
        var seldata_month = $(".HMHRMS.praisemonth").val();
        if (seldata_month != "") {
            var dataname_year = "年";
        } else {
            var dataname_year = "年や月";
        }
        var obfocus_year = ".praiseyear";

        if (
            me.selectdatacheck(dataname_year, seldata_year, obfocus_year) ==
            false
        ) {
            return;
        }

        var seldata_content = $(".praisecontent").val();
        var obfocus_content = ".praisecontent";
        var dataname_content = "表彰内容";
        if (
            me.inputdatacheck(
                dataname_content,
                seldata_content,
                obfocus_content
            ) == false
        ) {
            return;
        }

        if (me.burrontype == "add") {
            if ($(".praisemonth").val() != "") {
                var getdate =
                    $(".praiseyear").val() + "/" + $(".praisemonth").val();
            } else {
                var getdate = $(".praiseyear").val();
            }

            var employee_sub_table = {
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            var custom_values = {};
            custom_values[me.praisedate] = getdate;
            custom_values[me.praisecontent] = $(".praisecontent").val();
            // 最大行数を取得する
            var selIRow = 0;
            var rowData = $(me.praise_grid_id).jqGrid("getRowData");
            if (rowData.length > 0) {
                var selIRow = rowData.length;
            }

            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
            };

            var url = me.sys_id + "/" + me.id + "/funInsert";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "表彰歴"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                if ($(".praisemonth").val() != "") {
                    var month = "/" + $(".praisemonth").val();
                } else {
                    var month = "";
                }

                //行追加
                $(me.praise_grid_id).jqGrid("addRowData", selIRow, {
                    praise_content: me.format($(".praisecontent").val()),
                    praise_date: $(".praiseyear").val() + month,
                    estid: result["estid"],
                });
                me.clsComFnc.FncMsgBox("I0002");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
        if (me.burrontype == "edit") {
            if ($(".praisemonth").val() != "") {
                var getdate =
                    $(".praiseyear").val() + "/" + $(".praisemonth").val();
            } else {
                var getdate = $(".praiseyear").val();
            }

            var rowID = $(me.praise_grid_id).jqGrid("getGridParam", "selrow");
            var praiserowData = $(me.praise_grid_id).jqGrid(
                "getRowData",
                rowID
            );
            var employee_sub_table = {
                estid: praiserowData["estid"],
                employee_id: me.emp["empId"],
                type: me.diatype,
            };
            var custom_values = {};
            custom_values[me.praisedate] = getdate;
            custom_values[me.praisecontent] = $(".praisecontent").val();

            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
            };

            var url = me.sys_id + "/" + me.id + "/funUpdate";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "表彰歴"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                if ($(".praisemonth").val() != "") {
                    $(me.praise_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "praise_date",
                        $(".praiseyear").val() + "/" + $(".praisemonth").val()
                    );
                } else {
                    $(me.praise_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "praise_date",
                        $(".praiseyear").val()
                    );
                }

                $(me.praise_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "praise_content",
                    me.format($(".praisecontent").val())
                );
                me.clsComFnc.FncMsgBox("I0008");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
    };

    // 資格・免許_保存
    me.FunqualicationSaveBtn = function () {
        var seldata_license = $(".qualicationlicense").val();
        var obfocus_license = ".qualicationlicense";
        var dataname_license = "資格・免許";
        if (
            me.inputdatacheck(
                dataname_license,
                seldata_license,
                obfocus_license
            ) == false
        ) {
            return;
        }
        var seldata =
            $(".qualicationyear").val() + $(".qualicationmonth").val();
        var obfocus = ".qualicationyear";
        var dataname = "取得時期(年)と取得時期(月)";
        if (me.selectdatacheck(dataname, seldata, obfocus) == false) {
            return;
        }
        var seldata_qyear = $(".qualicationyear").val();
        var obfocus_qyear = ".qualicationyear";
        var dataname_qyear = "取得時期(年)";
        if (
            me.selectdatacheck(dataname_qyear, seldata_qyear, obfocus_qyear) ==
            false
        ) {
            return;
        }
        var obfocus_qmonth = ".qualicationmonth";
        var seldata_qmonth = $(".qualicationmonth").val();
        var dataname_qmonth = "取得時期(月)";
        if (
            me.selectdatacheck(
                dataname_qmonth,
                seldata_qmonth,
                obfocus_qmonth
            ) == false
        ) {
            return;
        }

        // 履历
        var selIRow = 0;

        var rowData = $(me.qualication_grid_id).jqGrid("getRowData");
        if (rowData.length > 0) {
            var selIRow = rowData.length;
        }

        var arr = {
            public_content: $(".qualicationlicense").val(),
            get_date:
                $(".qualicationyear").val() +
                "/" +
                $(".qualicationmonth").val(),
        };
        if (me.burrontype == "add") {
            var getdate =
                $(".qualicationyear").val() == null ||
                $(".qualicationmonth").val() == null
                    ? ""
                    : $(".qualicationyear").val() +
                      "/" +
                      $(".qualicationmonth").val();

            var employee_sub_table = {
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            var custom_values = {};
            custom_values[me.getdate] = getdate;
            custom_values[me.publiccontent] = $(".qualicationlicense").val();

            var custom_values_history = [];
            for (var i in arr) {
                values = {};
                values = {
                    item2: i,
                    row: selIRow + 1,
                    update_before: "",
                    update_after: arr[i],
                };
                custom_values_history.push(values);
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };
            var url = me.sys_id + "/" + me.id + "/funInsert";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "資格・免許"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                // 行追加
                $(me.qualication_grid_id).jqGrid("addRowData", selIRow, {
                    public_content: me.format($(".qualicationlicense").val()),
                    get_date:
                        $(".qualicationyear").val() +
                        "/" +
                        $(".qualicationmonth").val(),
                    estid: result["estid"],
                });

                me.clsComFnc.FncMsgBox("I0002");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
        if (me.burrontype == "edit") {
            var getdate =
                $(".qualicationyear").val() == null ||
                $(".qualicationmonth").val() == null
                    ? ""
                    : $(".qualicationyear").val() +
                      "/" +
                      $(".qualicationmonth").val();

            var rowID = $(me.qualication_grid_id).jqGrid(
                "getGridParam",
                "selrow"
            );
            var qualicationrowData = $(me.qualication_grid_id).jqGrid(
                "getRowData",
                rowID
            );
            var employee_sub_table = {
                estid: qualicationrowData["estid"],
                employee_id: me.emp["empId"],
                type: me.diatype,
            };
            var custom_values = {};
            custom_values[me.getdate] = getdate;
            custom_values[me.publiccontent] = $(".qualicationlicense").val();

            // 履历
            var custom_values_history = [];
            for (var i in arr) {
                if (qualicationrowData[i] != arr[i]) {
                    values = {};
                    values = {
                        item2: i,
                        row: parseInt(rowID) + 1,
                        update_before: qualicationrowData[i],
                        update_after: arr[i],
                    };
                    custom_values_history.push(values);
                }
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };
            var url = me.sys_id + "/" + me.id + "/funUpdate";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "資格・免許"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                $(me.qualication_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "public_content",
                    me.format($(".qualicationlicense").val())
                );
                $(me.qualication_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "get_date",
                    $(".qualicationyear").val() +
                        "/" +
                        $(".qualicationmonth").val()
                );
                me.clsComFnc.FncMsgBox("I0008");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
    };

    // 社外職歴_保存
    me.FunothercompanySaveBtn = function () {
        var seldata_start = $(".company_start").val();
        var obfocus_start = ".company_start";
        var dataname_start = "年月Start";
        if (
            me.selectdatacheck(dataname_start, seldata_start, obfocus_start) ==
            false
        ) {
            return;
        }
        var seldata_end = $(".company_end").val();
        var obfocus_end = ".company_end";
        var dataname_end = "年月End";
        if (
            me.selectdatacheck(dataname_end, seldata_end, obfocus_end) == false
        ) {
            return;
        }
        var seldata1 = $(".company_start").val()
            ? $(".company_start").val().substring(0, 7)
            : null;
        var seldata2 = $(".company_end").val()
            ? $(".company_end").val().substring(0, 7)
            : null;
        var dataname1 = "年月Start";
        var dataname2 = "年月End";
        var obfocus2 = ".company_start";
        if (
            me.twodatecheck(
                seldata1,
                seldata2,
                dataname1,
                dataname2,
                obfocus2
            ) == false
        ) {
            return;
        }
        var obfocus_country = ".company_country";
        var seldata_country = $(".company_country").val();
        var dataname_country = "勤務地（国）";
        if (
            me.inputdatacheck(
                dataname_country,
                seldata_country,
                obfocus_country
            ) == false
        ) {
            return;
        }
        var obfocus_city = ".company_city";
        var seldata_city = $(".company_city").val();
        var dataname_city = "勤務地（市）";
        if (
            me.inputdatacheck(dataname_city, seldata_city, obfocus_city) ==
            false
        ) {
            return;
        }
        var obfocus_name = ".company_name";
        var seldata_name = $(".company_name").val();
        var dataname_name = "社名";
        if (
            me.inputdatacheck(dataname_name, seldata_name, obfocus_name) ==
            false
        ) {
            return;
        }
        var obfocus_position = ".company_position";
        var seldata_position = $(".company_position").val();
        var dataname_position = "ポジション";
        if (
            me.inputdatacheck(
                dataname_position,
                seldata_position,
                obfocus_position
            ) == false
        ) {
            return;
        }
        var obfocus_job = ".job_content";
        var seldata_job = $(".job_content").val();
        var dataname_job = "職務内容";
        if (
            me.inputdatacheck(dataname_job, seldata_job, obfocus_job) == false
        ) {
            return;
        }
        // 履历
        var selIRow = 0;
        var rowData = $(me.othercompany_grid_id).jqGrid("getRowData");
        if (rowData.length > 0) {
            var selIRow = rowData.length;
        }

        var arr = {
            company_start: $(".company_start").val(),
            company_end: $(".company_end").val(),
            company_country: $(".company_country").val(),
            company_prefecture: $(".company_prefecture").val(),
            company_city: $(".company_city").val(),
            company_name: $(".company_name").val(),
            company_position: $(".company_position").val(),
            job_content: $(".job_content").val(),
        };
        if (me.burrontype == "add") {
            var employee_sub_table = {
                employee_id: me.emp["empId"],
                type: me.diatype,
            };

            var custom_values = {};
            custom_values[me.companystart] = $(".company_start").val()
                ? $(".company_start").val().substring(0, 7)
                : null;
            custom_values[me.companyend] = $(".company_end").val()
                ? $(".company_end").val().substring(0, 7)
                : null;
            custom_values[me.companycountry] = $(".company_country").val();
            custom_values[me.companyprefecture] = $(
                ".company_prefecture"
            ).val();
            custom_values[me.companycity] = $(".company_city").val();
            custom_values[me.companyname] = $(".company_name").val();
            custom_values[me.companyposition] = $(".company_position").val();
            custom_values[me.jobcontent] = $(".job_content").val();

            var custom_values_history = [];
            for (var i in arr) {
                values = {};
                values = {
                    item2: i,
                    row: selIRow + 1,
                    update_before: "",
                    update_after: arr[i],
                };
                custom_values_history.push(values);
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };

            var url = me.sys_id + "/" + me.id + "/funInsert";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "社外職歴"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                // 行追加
                $(me.othercompany_grid_id).jqGrid("addRowData", selIRow, {
                    company_start: $(".company_start").val(),
                    company_end: $(".company_end").val(),
                    company_country: me.format($(".company_country").val()),
                    company_prefecture: me.format(
                        $(".company_prefecture").val()
                    ),
                    company_city: me.format($(".company_city").val()),
                    company_name: me.format($(".company_name").val()),
                    company_position: me.format($(".company_position").val()),
                    job_content: me.format($(".job_content").val()),
                    estid: result["estid"],
                });
                me.clsComFnc.FncMsgBox("I0002");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
        if (me.burrontype == "edit") {
            var rowID = $(me.othercompany_grid_id).jqGrid(
                "getGridParam",
                "selrow"
            );
            var othercompanyrowData = $(me.othercompany_grid_id).jqGrid(
                "getRowData",
                rowID
            );

            var employee_sub_table = {
                estid: othercompanyrowData["estid"],
                employee_id: me.emp["empId"],
                type: me.diatype,
            };
            var custom_values = {};
            custom_values[me.companystart] = $(".company_start").val()
                ? $(".company_start").val().substring(0, 7)
                : null;
            custom_values[me.companyend] = $(".company_end").val()
                ? $(".company_end").val().substring(0, 7)
                : null;
            custom_values[me.companycountry] = $(".company_country").val();
            custom_values[me.companyprefecture] = $(
                ".company_prefecture"
            ).val();
            custom_values[me.companycity] = $(".company_city").val();
            custom_values[me.companyname] = $(".company_name").val();
            custom_values[me.companyposition] = $(".company_position").val();
            custom_values[me.jobcontent] = $(".job_content").val();

            // 履历
            var custom_values_history = [];
            for (var i in arr) {
                if (othercompanyrowData[i] != arr[i]) {
                    values = {};
                    values = {
                        item2: i,
                        row: parseInt(rowID) + 1,
                        update_before: othercompanyrowData[i],
                        update_after: arr[i],
                    };
                    custom_values_history.push(values);
                }
            }
            var data = {
                employee_sub_table: employee_sub_table,
                custom_values: custom_values,
                custom_values_history: custom_values_history,
            };
            var url = me.sys_id + "/" + me.id + "/funUpdate";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    if (result["error"] == "データは既に存在します") {
                        me.clsComFnc.FncMsgBox("W9999", result["error"]);
                        return;
                    } else {
                        result["error"] = result["error"].replace(
                            "%d",
                            "社外職歴"
                        );
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                }
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_start",
                    $(".company_start").val()
                );
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_end",
                    $(".company_end").val()
                );
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_country",
                    me.format($(".company_country").val())
                );
                if ($(".company_prefecture").val() == "") {
                    $(me.othercompany_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "company_prefecture",
                        null
                    );
                } else {
                    $(me.othercompany_grid_id).jqGrid(
                        "setCell",
                        rowID,
                        "company_prefecture",
                        me.format($(".company_prefecture").val())
                    );
                }

                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_city",
                    me.format($(".company_city").val())
                );
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_name",
                    me.format($(".company_name").val())
                );
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "company_position",
                    me.format($(".company_position").val())
                );
                $(me.othercompany_grid_id).jqGrid(
                    "setCell",
                    rowID,
                    "job_content",
                    me.format($(".job_content").val())
                );
                me.clsComFnc.FncMsgBox("I0008");
                $(me.dialogurl).dialog("close");
            };
            me.ajax.send(url, data, 0);
        }
    };

    // 家族状況_削除
    me.familyDel = function () {
        var estiddArray = [];
        var family_sel_datas = new Array();
        var family_sel_idxs = $(me.family_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        for (var idx = 0; idx < family_sel_idxs.length; idx++) {
            var data = $(me.family_grid_id).jqGrid(
                "getRowData",
                family_sel_idxs[idx]
            );
            family_sel_datas.push({
                item2: "name",
                update_before: data["name"],
            });
            family_sel_datas.push({
                item2: "namePhonetic",
                update_before: data["namePhonetic"],
            });
            family_sel_datas.push({
                item2: "relation",
                update_before: data["relation"],
            });
            family_sel_datas.push({
                item2: "birthday",
                update_before: data["birthday"],
            });
            // family_sel_datas.push({
            //     item2: "together",
            //     update_before: data["together"],
            // });
            estiddArray.push(data["estid"]);
        }

        var sub_table = {
            estid: estiddArray,
            type: "family",
        };
        me.data = {
            empId: me.empID,
            custom_values_history: family_sel_datas,
            employee_sub_table: sub_table,
        };

        me.url = "HMHRMS/HMHRMS/fncDelete";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var rowCount = family_sel_idxs.length;
            for (var idx = rowCount - 1; idx >= 0; idx--) {
                $(me.family_grid_id).jqGrid("delRowData", family_sel_idxs[idx]);
            }
            var allrowData = $(me.family_grid_id).jqGrid("getRowData");
            $(me.family_grid_id).jqGrid("clearGridData");

            for (var i = 0; i < allrowData.length; i++) {
                for (var item in allrowData[i]) {
                    allrowData[i][item] = allrowData[i][item]
                        .replace(/[<]/g, "&lt;")
                        .replace(/[>]/g, "&gt;")
                        .replace(/\r\n/g, "")
                        .replace(/\n/g, "")
                        .replace(/[\"]/g, "&quot;")
                        .replace(/[\']/g, "&apos;")
                        .replace(/[\r\n]/g, "<br>");
                }
                $(me.family_grid_id).jqGrid("addRowData", i, allrowData[i]);
            }
            $("#cb_family_jqgridTable").prop("checked", false);
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
        };
        me.ajax.send(me.url, me.data, 0);
    };

    // 学歴_削除
    me.educationDel = function () {
        var estiddArray = [];
        var education_sel_datas = new Array();
        var education_sel_idxs = $(me.education_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        for (var idx = 0; idx < education_sel_idxs.length; idx++) {
            var data = $(me.education_grid_id).jqGrid(
                "getRowData",
                education_sel_idxs[idx]
            );

            education_sel_datas.push({
                item2: "kinds_of_schools",
                update_before: data["kinds_of_schools"],
            });
            education_sel_datas.push({
                item2: "school_name",
                update_before: data["school_name"],
            });
            education_sel_datas.push({
                item2: "disciplines",
                update_before: data["disciplines"],
            });
            education_sel_datas.push({
                item2: "address_country",
                update_before: data["address_country"],
            });
            education_sel_datas.push({
                item2: "address_prefecture",
                update_before: data["address_prefecture"],
            });
            education_sel_datas.push({
                item2: "address_city",
                update_before: data["address_city"],
            });
            estiddArray.push(data["estid"]);
        }

        var sub_table = {
            estid: estiddArray,
            type: "education",
        };
        me.data = {
            empId: me.empID,
            custom_values_history: education_sel_datas,
            employee_sub_table: sub_table,
        };

        me.url = "HMHRMS/HMHRMS/fncDelete";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var rowCount = education_sel_idxs.length;
            for (var idx = rowCount - 1; idx >= 0; idx--) {
                $(me.education_grid_id).jqGrid(
                    "delRowData",
                    education_sel_idxs[idx]
                );
            }
            var allrowData = $(me.education_grid_id).jqGrid("getRowData");
            $(me.education_grid_id).jqGrid("clearGridData");

            for (var i = 0; i < allrowData.length; i++) {
                for (var item in allrowData[i]) {
                    allrowData[i][item] = allrowData[i][item]
                        .replace(/[<]/g, "&lt;")
                        .replace(/[>]/g, "&gt;")
                        .replace(/\r\n/g, "")
                        .replace(/\n/g, "")
                        .replace(/[\"]/g, "&quot;")
                        .replace(/[\']/g, "&apos;")
                        .replace(/[\r\n]/g, "<br>");
                }
                $(me.education_grid_id).jqGrid("addRowData", i, allrowData[i]);
            }

            $("#cb_education_jqgridTable").prop("checked", false);
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
        };
        me.ajax.send(me.url, me.data, 0);
    };

    // 社外職歴_削除
    me.othercompanyDel = function () {
        var estiddArray = [];
        var othercompany_sel_datas = new Array();
        var othercompany_sel_idxs = $(me.othercompany_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        for (var idx = 0; idx < othercompany_sel_idxs.length; idx++) {
            var data = $(me.othercompany_grid_id).jqGrid(
                "getRowData",
                othercompany_sel_idxs[idx]
            );

            othercompany_sel_datas.push({
                item2: "company_start",
                update_before: data["company_start"],
            });
            othercompany_sel_datas.push({
                item2: "company_end",
                update_before: data["company_end"],
            });
            othercompany_sel_datas.push({
                item2: "company_country",
                update_before: data["company_country"],
            });
            othercompany_sel_datas.push({
                item2: "company_prefecture",
                update_before: data["company_prefecture"],
            });
            othercompany_sel_datas.push({
                item2: "company_city",
                update_before: data["company_city"],
            });
            othercompany_sel_datas.push({
                item2: "company_name",
                update_before: data["company_name"],
            });
            othercompany_sel_datas.push({
                item2: "company_position",
                update_before: data["company_position"],
            });
            othercompany_sel_datas.push({
                item2: "job_content",
                update_before: data["job_content"],
            });
            estiddArray.push(data["estid"]);
        }

        var sub_table = {
            estid: estiddArray,
            type: "othercompany",
        };
        me.data = {
            empId: me.empID,
            custom_values_history: othercompany_sel_datas,
            employee_sub_table: sub_table,
        };

        me.url = "HMHRMS/HMHRMS/fncDelete";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var rowCount = othercompany_sel_idxs.length;
            for (var idx = rowCount - 1; idx >= 0; idx--) {
                $(me.othercompany_grid_id).jqGrid(
                    "delRowData",
                    othercompany_sel_idxs[idx]
                );
            }
            var allrowData = $(me.othercompany_grid_id).jqGrid("getRowData");
            $(me.othercompany_grid_id).jqGrid("clearGridData");

            for (var i = 0; i < allrowData.length; i++) {
                for (var item in allrowData[i]) {
                    allrowData[i][item] = allrowData[i][item]
                        .replace(/[<]/g, "&lt;")
                        .replace(/[>]/g, "&gt;")
                        .replace(/\r\n/g, "")
                        .replace(/\n/g, "")
                        .replace(/[\"]/g, "&quot;")
                        .replace(/[\']/g, "&apos;")
                        .replace(/[\r\n]/g, "<br>");
                }
                $(me.othercompany_grid_id).jqGrid(
                    "addRowData",
                    i,
                    allrowData[i]
                );
            }

            $("#cb_othercompany_jqgridTable").prop("checked", false);
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
        };
        me.ajax.send(me.url, me.data, 0);
    };

    // 表彰歴_削除
    me.praiseDel = function () {
        var estiddArray = [];
        var praise_sel_idxs = $(me.praise_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        for (var idx = 0; idx < praise_sel_idxs.length; idx++) {
            var data = $(me.praise_grid_id).jqGrid(
                "getRowData",
                praise_sel_idxs[idx]
            );
            estiddArray.push(data["estid"]);
        }

        var sub_table = {
            estid: estiddArray,
            type: "praise",
        };
        me.data = {
            empId: me.empID,
            employee_sub_table: sub_table,
        };

        me.url = "HMHRMS/HMHRMS/fncDelete";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var rowCount = praise_sel_idxs.length;
            for (var idx = rowCount - 1; idx >= 0; idx--) {
                $(me.praise_grid_id).jqGrid("delRowData", praise_sel_idxs[idx]);
            }

            $("#cb_praise_jqgridTable").prop("checked", false);
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
        };

        me.ajax.send(me.url, me.data, 0);
    };

    // 資格・免許_削除
    me.qualicationDel = function () {
        var estiddArray = [];
        var qualication_sel_datas = new Array();
        var qualication_sel_idxs = $(me.qualication_grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );
        for (var idx = 0; idx < qualication_sel_idxs.length; idx++) {
            var data = $(me.qualication_grid_id).jqGrid(
                "getRowData",
                qualication_sel_idxs[idx]
            );

            qualication_sel_datas.push({
                item2: "public_content",
                update_before: data["public_content"],
            });
            qualication_sel_datas.push({
                item2: "get_date",
                update_before: data["get_date"],
            });
            estiddArray.push(data["estid"]);
        }

        var sub_table = {
            estid: estiddArray,
            type: "qualication",
        };
        me.data = {
            empId: me.empID,
            custom_values_history: qualication_sel_datas,
            employee_sub_table: sub_table,
        };

        me.url = "HMHRMS/HMHRMS/fncDelete";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            var rowCount = qualication_sel_idxs.length;
            for (var idx = rowCount - 1; idx >= 0; idx--) {
                $(me.qualication_grid_id).jqGrid(
                    "delRowData",
                    qualication_sel_idxs[idx]
                );
            }
            var allrowData = $(me.qualication_grid_id).jqGrid("getRowData");
            $(me.qualication_grid_id).jqGrid("clearGridData");
            for (var i = 0; i < allrowData.length; i++) {
                for (var item in allrowData[i]) {
                    allrowData[i][item] = allrowData[i][item]
                        .replace(/[<]/g, "&lt;")
                        .replace(/[>]/g, "&gt;")
                        .replace(/\r\n/g, "")
                        .replace(/\n/g, "")
                        .replace(/[\"]/g, "&quot;")
                        .replace(/[\']/g, "&apos;")
                        .replace(/[\r\n]/g, "<br>");
                }
                $(me.qualication_grid_id).jqGrid(
                    "addRowData",
                    i,
                    allrowData[i]
                );
            }

            $("#cb_qualication_jqgridTable").prop("checked", false);
            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");
        };

        me.ajax.send(me.url, me.data, 0);
    };

    // 日付大小関係チェック
    (me.twodatecheck = function (data1, data2, dataname1, dataname2, obfocus) {
        if (data1 > data2) {
            me.clsComFnc.ObjFocus = $(obfocus);
            me.clsComFnc.FncMsgBox(
                "W9999",
                dataname1 + "と" + dataname2 + "の大小関係が不正です。"
            );
            return false;
        }
    }),
        // inputチェック
        (me.inputdatacheck = function (dataname, seldata, obfocus) {
            if (!seldata) {
                me.clsComFnc.ObjFocus = $(obfocus);
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    dataname + "を入力してください。"
                );
                return false;
            }
        });

    // selectチェック
    me.selectdatacheck = function (dataname, seldata, obfocus) {
        if (!seldata) {
            me.clsComFnc.ObjFocus = $(obfocus);
            me.clsComFnc.FncMsgBox("W9999", dataname + "を選んでください。");
            return false;
        }
    };

    // 更新チェック
    me.updateCheck = function () {
        // 基本情報_自宅
        var type_left = "自宅_";
        var empZipCode_input = ".HMHRMS.empZipCode";
        var empZipCode = $(".HMHRMS.empZipCode").val();
        if (me.codecheck(empZipCode, type_left, empZipCode_input) == false) {
            return false;
        }
        var empTel_input = ".HMHRMS.empTel";
        var empTel = $(".HMHRMS.empTel").val();
        if (me.telcheck(empTel, type_left, empTel_input) == false) {
            return false;
        }
        var empMobile_input = ".HMHRMS.empMobile";
        var empMobile = $(".HMHRMS.empMobile").val();
        if (me.telphcheck(empMobile, type_left, empMobile_input) == false) {
            return false;
        }

        // 基本情報_緊急連絡先１
        var type_righttop = "緊急連絡先１_";
        var emergencyZipCode_input = ".HMHRMS.emergencyZipCode";
        var emergencyZipCode = $(".HMHRMS.emergencyZipCode").val();
        if (
            me.codecheck(
                emergencyZipCode,
                type_righttop,
                emergencyZipCode_input
            ) == false
        ) {
            return false;
        }
        var emergencyMobile_input = ".HMHRMS.emergencyMobile";
        var emergencyMobile = $(".HMHRMS.emergencyMobile").val();
        if (
            me.telphcheck(
                emergencyMobile,
                type_righttop,
                emergencyMobile_input
            ) == false
        ) {
            return false;
        }
        var emergencyTel_input = ".HMHRMS.emergencyTel";
        var emergencyTel = $(".HMHRMS.emergencyTel").val();
        if (
            me.telcheck(emergencyTel, type_righttop, emergencyTel_input) ==
            false
        ) {
            return false;
        }

        // 基本情報_緊急連絡先２
        var type_rightbottom = "緊急連絡先２_";
        var emergencyZipCode2_input = ".HMHRMS.emergencyZipCode2";
        var emergencyZipCode2 = $(".HMHRMS.emergencyZipCode2").val();
        if (
            me.codecheck(
                emergencyZipCode2,
                type_rightbottom,
                emergencyZipCode2_input
            ) == false
        ) {
            return false;
        }
        var emergencyMobile2_input = ".HMHRMS.emergencyMobile2";
        var emergencyMobile2 = $(".HMHRMS.emergencyMobile2").val();
        if (
            me.telphcheck(
                emergencyMobile2,
                type_rightbottom,
                emergencyMobile2_input
            ) == false
        ) {
            return false;
        }
        var emergencyTel2_input = ".HMHRMS.emergencyTel2";
        var emergencyTel2 = $(".HMHRMS.emergencyTel2").val();
        if (
            me.telcheck(emergencyTel2, type_rightbottom, emergencyTel2_input) ==
            false
        ) {
            return false;
        }

        var mail_address_personal = $(".HMHRMS.mail_address_personal").val();
        if (me.mailcheck(mail_address_personal) == false) {
            $(".HMHRMS.mail_address_personal").trigger("focus");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "メールアドレス（個人）は正しい形式で入力してください。"
            );
            return false;
        }
        var mail_address_company = $(".HMHRMS.mail_address_company").val();
        if (me.mailcheck(mail_address_company) == false) {
            $(".HMHRMS.mail_address_company").trigger("focus");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "メールアドレス（会社）は正しい形式で入力してください。"
            );
            return false;
        }
        var commuteDistance = $(".HMHRMS.commuteDistance").val();
        if (me.numbercheck(commuteDistance) == false) {
            $(".HMHRMS.commuteDistance").trigger("focus");
            me.clsComFnc.FncMsgBox(
                "W9999",
                "通勤距離は数字を入力してください。"
            );
            return false;
        }
    };
    // 郵便番号999-9999形式チェック
    me.codecheck = function (data, type, inputType) {
        reg = /^\d{3}-\d{4}$/;
        if (data !== "" && data !== null) {
            if (!reg.test(data)) {
                $(inputType).trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    type + "郵便番号は999-9999形式で入力してください。"
                );
                return false;
            }
        }
    };

    // 携帯電話999-9999-9999入力形式チェック
    me.telphcheck = function (data, type, inputType) {
        reg = /^\d{3}-\d{4}-\d{4}$/;
        if (data !== "" && data !== null) {
            if (!reg.test(data)) {
                $(inputType).trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    type + "携帯電話は999-9999-9999形式で入力してください。"
                );
                return false;
            }
        }
    };
    // TEL入力形式チェック
    me.telcheck = function (data, type, inputType) {
        reg = /^[0-9\-]*$/;
        if (data !== "" && data !== null) {
            if (!reg.test(data)) {
                $(inputType).trigger("focus");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    type + "ＴＥＬは数字と「-」を入力してください。"
                );
                return false;
            }
        }
    };
    // 数字入力チェック
    me.numbercheck = function (data) {
        reg = /^\d+(\.\d+)?$/;
        if (data !== "" && data !== null) {
            if (!reg.test(data)) {
                return false;
            }
        }
    };

    // メールボックスの形式チェック
    me.mailcheck = function (data) {
        reg =
            /^([a-zA-Z]|[0-9])(\w|\.|\-)+@([a-zA-Z0-9])(\w|\-)+\.([a-zA-Z]{2,4})$/;
        if (data !== "" && data !== null) {
            if (!reg.test(data)) {
                return false;
            }
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_HMHRMS_HMHRMS = new HMHRMS.HMHRMS();
    o_HMHRMS_HMHRMS.load();
});
