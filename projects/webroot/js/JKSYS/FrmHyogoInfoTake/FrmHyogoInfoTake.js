/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("JKSYS.FrmHyogoInfoTake");

JKSYS.FrmHyogoInfoTake = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.id = "FrmHyogoInfoTake";
    me.sys_id = "JKSYS";
    me.url = "";
    me.data = "";
    me.taisyouKikan = null;
    me.comboData = null;
    me.fileMark == 0;

    me.g_url = "JKSYS/FrmHyogoInfoTake/fncSearchSpread";
    me.grid_id = "#FrmHyogoInfoTake_DataTable";
    me.pager = "";
    me.sidx = "";
    me.option = {
        multiselect: false,
        rownumbers: true,
        rowNum: 0,
        caption: "",
        loadui: "enable",
    };
    me.colModel = [
        {
            name: "JISSHI_YM",
            label: "実施年月",
            index: "JISSHI_YM",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "HYOUKA_KIKAN_START",
            label: "開始",
            index: "HYOUKA_KIKAN_START",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "HYOUKA_KIKAN_END",
            label: "終了",
            index: "HYOUKA_KIKAN_END",
            width: 120,
            align: "left",
            sortable: false,
        },
        {
            name: "TRK_KENSU",
            label: "取込件数",
            index: "TRK_KENSU",
            width: 90,
            align: "right",
            sortable: false,
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHyogoInfoTake.cmdOpen",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyogoInfoTake.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyogoInfoTake.cmdCancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyogoInfoTake.cmdTorikomi",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHyogoInfoTake.cmdSelect",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmHyogoInfoTake.cboJisshiYM").change(function () {
        me.setKikan();
    });
    $(".FrmHyogoInfoTake.cboJisshiYM").on("search", function () {
        if ("" == this.value) {
            $(".FrmHyogoInfoTake.cboJisshiYM").trigger("blur");
            $(".FrmHyogoInfoTake.cboJisshiYM").trigger("select");
        }
    });
    $(".FrmHyogoInfoTake.cmdOpen").click(function () {
        me.cmdOpen_Click();
    });
    $(".FrmHyogoInfoTake.cmdTorikomi").click(function () {
        me.fncInputChk();
    });
    $(".FrmHyogoInfoTake.cmdCancel").click(function () {
        me.subReClearForm();
    });
    $(".FrmHyogoInfoTake.cmdDelete").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdDelete_Click;
        me.clsComFnc.FncMsgBox("QY004");
    });
    $(".FrmHyogoInfoTake.cmdSelect").click(function () {
        me.subSelClearForm();
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

        me.frmSyokusyubetuKamokuMente_Load();
    };

    //フォームロード
    me.frmSyokusyubetuKamokuMente_Load = function () {
        //'初期処理
        me.url = me.sys_id + "/" + me.id + "/frmSyokusyubetuKamokuMente_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmHyogoInfoTake.txtFile").val("");
                $(".FrmHyogoInfoTake.cmdDelete").hide();
                $(".FrmHyogoInfoTake.cmdCancel").hide();
                $(".FrmHyogoInfoTake.cmdSelect").hide();
                $(".FrmHyogoInfoTake.cmdTorikomi").show();
                $(".FrmHyogoInfoTake.cmdTorikomi").text("取込");

                $(".FrmHyogoInfoTake").attr("disabled", true);
                $(".FrmHyogoInfoTake button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (result["combo"]["row"] > 0) {
                me.comboData = result["combo"]["data"];
                me.taisyouKikan = result["kikan"]["data"];
                //'画面項目ｸﾘｱ
                me.subClearForm();

                //ｽﾌﾟﾚｯﾄﾞの初期設定
                me.initSpread(false);
                $(".FrmHyogoInfoTake.cboJisshiYM").trigger("select");
            }
        };

        me.ajax.send(me.url, "", 0);
    };

    //画面項目クリア
    me.subClearForm = function () {
        $(".FrmHyogoInfoTake.cboJisshiYM").attr("disabled", false);
        $(".FrmHyogoInfoTake.txtFile").val("");
        $(".FrmHyogoInfoTake.cmdDelete").hide();
        $(".FrmHyogoInfoTake.cmdCancel").hide();
        $(".FrmHyogoInfoTake.cmdDelete").attr("disabled", true);
        $(".FrmHyogoInfoTake.cmdCancel").attr("disabled", true);
        $(".FrmHyogoInfoTake.cmdTorikomi").show();
        $(".FrmHyogoInfoTake.cmdTorikomi").text("取込");
        $(".FrmHyogoInfoTake.cmdSelect").hide();
        $(".FrmHyogoInfoTake.cmdSelect").attr("disabled", true);
        $(".FrmHyogoInfoTake.cboJisshiYM").empty();
        //コンボボックスセット
        me.setCombo();
        //初期色セット
        me.subResetColor();
    };
    //初期色
    me.subResetColor = function () {
        $(".FrmHyogoInfoTake.txtFile").css(me.clsComFnc.GC_COLOR_NORMAL);
    };
    //コンボボックスセット
    me.setCombo = function () {
        var tmpStr = '<datalist id="selectconditions">';
        for (var i = 0; i < me.comboData.length; i++) {
            tmpStr += '<option value="' + me.comboData[i]["JISSHI_YM"] + '">';
        }
        tmpStr = tmpStr + "</datalist>";
        $(".FrmHyogoInfoTake.cboJisshiYM").append(tmpStr);
        $(".FrmHyogoInfoTake.cboJisshiYM").val(me.comboData[0]["JISSHI_YM"]);
        //評価対象期間セット
        me.setKikan();
    };
    //画面項目再クリア
    me.subReClearForm = function () {
        $(".FrmHyogoInfoTake.cboJisshiYM").attr("disabled", false);
        $(".FrmHyogoInfoTake.txtFile").val("");
        $(".FrmHyogoInfoTake.cmdDelete").hide();
        $(".FrmHyogoInfoTake.cmdCancel").hide();
        $(".FrmHyogoInfoTake.cmdDelete").attr("disabled", true);
        $(".FrmHyogoInfoTake.cmdCancel").attr("disabled", true);
        $(".FrmHyogoInfoTake.cmdTorikomi").text("取込");
        //初期色セット
        me.subResetColor();
    };

    //画面項目選択クリア
    me.subSelClearForm = function () {
        var rowid = $(me.grid_id).jqGrid("getGridParam", "selrow");
        me.selRow = $("#FrmHyogoInfoTake_DataTable").jqGrid(
            "getRowData",
            rowid
        );
        $(".FrmHyogoInfoTake.cboJisshiYM").val(me.selRow["JISSHI_YM"]);
        $(".FrmHyogoInfoTake.cboJisshiYM").attr("disabled", "disabled");
        $(".FrmHyogoInfoTake.TaisyouKikanFrom").text(
            me.selRow["HYOUKA_KIKAN_START"]
        );
        $(".FrmHyogoInfoTake.TaisyouKikanTo").text(
            me.selRow["HYOUKA_KIKAN_END"]
        );
        $(".FrmHyogoInfoTake.txtFile").val("");

        if ($(".FrmHyogoInfoTake.cboJisshiYM").val() !== null) {
            $(".FrmHyogoInfoTake.cmdDelete").show();
            $(".FrmHyogoInfoTake.cmdCancel").show();
            $(".FrmHyogoInfoTake.cmdDelete").attr("disabled", false);
            $(".FrmHyogoInfoTake.cmdCancel").attr("disabled", false);
            $(".FrmHyogoInfoTake.cmdTorikomi").text("再取込");
        } else {
            $(".FrmHyogoInfoTake.cmdDelete").hide();
            $(".FrmHyogoInfoTake.cmdCancel").hide();
            $(".FrmHyogoInfoTake.cmdDelete").attr("disabled", true);
            $(".FrmHyogoInfoTake.cmdCancel").attr("disabled", true);
            $(".FrmHyogoInfoTake.cmdTorikomi").text("取込");
        }
    };

    //評価対象期間セット
    me.setKikan = function () {
        //初期値
        $(".FrmHyogoInfoTake.TaisyouKikanFrom").text("");
        $(".FrmHyogoInfoTake.TaisyouKikanTo").text("");

        var cboJisshiYM = $(".FrmHyogoInfoTake.cboJisshiYM")
            .val()
            .replace("/", "");
        if (me.taisyouKikan != null && me.taisyouKikan.length > 0) {
            var findhyouka = me.taisyouKikan.filter(function (element) {
                return element["JISSHI_YM"] == cboJisshiYM;
            });
            if (findhyouka.length !== 0) {
                if (findhyouka[0]["HYOUKA_KIKAN_START"] == null) {
                    findhyouka[0]["HYOUKA_KIKAN_START"] = "";
                }
                if (findhyouka[0]["HYOUKA_KIKAN_END"] == null) {
                    findhyouka[0]["HYOUKA_KIKAN_END"] = "";
                }
                $(".FrmHyogoInfoTake.TaisyouKikanFrom").text(
                    findhyouka[0]["HYOUKA_KIKAN_START"]
                );
                $(".FrmHyogoInfoTake.TaisyouKikanTo").text(
                    findhyouka[0]["HYOUKA_KIKAN_END"]
                );
            }
        }
    };

    //入力チェック
    me.fncInputChk = function () {
        me.subResetColor();
        if ($(".FrmHyogoInfoTake.cboJisshiYM").val() == "") {
            me.clsComFnc.FncMsgBox("W0001", "評価実施年月");
            return;
        }
        if ($(".FrmHyogoInfoTake.TaisyouKikanFrom").text() == "") {
            me.clsComFnc.FncMsgBox("W0001", "評価対象期間");
            return;
        }
        //取込ﾌｧｲﾙのﾁｪｯｸ処理
        var FileName = $(".FrmHyogoInfoTake.txtFile").val();
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "取込対象のファイルを指定してください。"
            );
            return false;
        }

        //拡張子
        var arr = $(".FrmHyogoInfoTake.txtFile").val().split(".");
        var len = arr.length;
        var fileType = arr[len - 1].toLowerCase();

        if (!(fileType == "csv" || fileType == "CSV")) {
            me.clsComFnc.ObjFocus = $(".FrmHyogoInfoTake.txtFile");
            $(".FrmHyogoInfoTake.txtFile").css(me.clsComFnc.GC_COLOR_ERROR);
            me.clsComFnc.FncMsgBox("W9999", "拡張子が不正です");
            return;
        } else {
            if (me.fileMark == 0) {
                me.file.send(me.func);
            } else {
                me.func();
            }
        }
    };

    //スプレッドの初期値設定
    me.initSpread = function (reload) {
        me.fileMark = 0;
        me.complete_fun = function (bErrorFlag, result) {
            if (result["data"]) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (bErrorFlag == "error" || bErrorFlag == "nodata") {
                return;
            } else {
                $(".FrmHyogoInfoTake.cmdSelect").show();
                $(".FrmHyogoInfoTake.cmdSelect").attr("disabled", false);
                $(me.grid_id).jqGrid("setSelection", 0, true);
            }
        };
        if (reload) {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id,
                "",
                me.complete_fun
            );
        } else {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                "",
                me.complete_fun
            );
            $("#FrmHyogoInfoTake_DataTable").jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "HYOUKA_KIKAN_START",
                        numberOfColumns: 2,
                        titleText: "評価対象期間",
                    },
                ],
            });
        }
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 540);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 200 : 265
        );
    };

    //
    me.cmdOpen_Click = function () {
        $(".FrmHyogoInfoTake.txtFile").val("");
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".csv";
        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            if (this.files[0].size > 2048000) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 2000KB です。"
                );
                return;
            }
            $(".FrmHyogoInfoTake.txtFile").val(this.files[0].name);
        });
        me.file.select_file();
    };

    //削除ボタン
    me.cmdDelete_Click = function () {
        var url = me.sys_id + "/" + me.id + "/fncDeleteClick";
        me.data = {
            jisshi_ym: $(".FrmHyogoInfoTake.cboJisshiYM")
                .val()
                .replace("/", ""),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            //完了メッセージ
            me.clsComFnc.FncMsgBox("I0004");

            //ｽﾌﾟﾚｯﾄﾞの初期設定
            me.initSpread(true);

            //画面項目クリア
            me.subReClearForm();
        };

        me.ajax.send(url, me.data, 0);
    };

    me.func = function () {
        if (!$(".FrmHyogoInfoTake.cboJisshiYM").attr("disabled")) {
            //評価履歴データ確認
            var url = me.sys_id + "/" + me.id + "/fncHyoukaRirekiCheck";

            me.data = {
                jisshi_ym: $(".FrmHyogoInfoTake.cboJisshiYM")
                    .val()
                    .replace("/", ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"] == false) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }

                if (result["row"] > 0) {
                    //確認メッセージ
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncTorikomi;
                    me.clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.cmdAct_ClickNo;
                    me.clsComFnc.FncMsgBox(
                        "QY999",
                        "既に" +
                            $(".FrmHyogoInfoTake.cboJisshiYM").val() +
                            "の評価データが取り込まれていますが、再取込をおこないますか？"
                    );
                } else {
                    me.fncTorikomi();
                }
            };
            me.ajax.send(url, me.data, 0);
        } else {
            me.fncTorikomi();
        }
    };
    //取込／再取込ボタン
    me.fncTorikomi = function () {
        var url = me.sys_id + "/" + me.id + "/fncTorikomi";

        me.data = {
            jisshi_ym: $(".FrmHyogoInfoTake.cboJisshiYM")
                .val()
                .replace("/", ""),
            TaisyouKikanFrom: $(".FrmHyogoInfoTake.TaisyouKikanFrom")
                .text()
                .replace("/", ""),
            TaisyouKikanTo: $(".FrmHyogoInfoTake.TaisyouKikanTo")
                .text()
                .replace("/", ""),
            Kensu: 0,
            FileName: $(".FrmHyogoInfoTake.txtFile").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["error"] != "") {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                } else {
                    me.clsComFnc.FncMsgBox("W9999", result["msg"]);
                }
                $(".FrmHyogoInfoTake.txtFile").val("");
                return;
            } else {
                me.clsComFnc.FncMsgBox("I0007");
                me.initSpread(true);
                me.subReClearForm();
            }
        };
        me.ajax.send(url, me.data, 0);
    };
    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_JKSYS_FrmHyogoInfoTake = new JKSYS.FrmHyogoInfoTake();
    o_JKSYS_FrmHyogoInfoTake.load();

    o_JKSYS_JKSYS.FrmHyogoInfoTake = o_JKSYS_FrmHyogoInfoTake;
});
