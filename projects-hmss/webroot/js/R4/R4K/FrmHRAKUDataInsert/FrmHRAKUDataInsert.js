/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmHRAKUDataInsert");

R4.FrmHRAKUDataInsert = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmHRAKUDataInsert";
    me.sys_id = "R4K";
    me.strTougetu = "";
    me.fileMark = 0;
    me.getAllBusyo = "";
    me.getAllSyain = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmHRAKUDataInsert.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.cmdSet",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.cboYM",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.cmdComplete",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.busyoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmHRAKUDataInsert.syainSearch",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.Page_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmHRAKUDataInsert.cmdSet").click(function () {
        me.cmdSetBtn_Click();
    });

    $(".FrmHRAKUDataInsert.cboYM").on("blur", function () {
        if (me.clsComFnc.CheckDate($(".FrmHRAKUDataInsert.cboYM")) == false) {
            window.setTimeout(function () {
                $(".FrmHRAKUDataInsert.cboYM").val(me.strTougetu.substr(0, 10));
                $(".FrmHRAKUDataInsert.cboYM").trigger("focus");
                $(".FrmHRAKUDataInsert.cboYM").select();
            }, 0);
        }
    });
    $(".FrmHRAKUDataInsert.txtSYAIN_NO").on("blur", function () {
        $(".FrmHRAKUDataInsert.lblSYAIN_NM").html(
            me.getSyainNM($(".FrmHRAKUDataInsert.txtSYAIN_NO").val())
        );
    });
    $(".FrmHRAKUDataInsert.txtBUSYO_CD").on("blur", function () {
        $(".FrmHRAKUDataInsert.lblBUSYO_NM").html(
            me.getBusyoNM($(".FrmHRAKUDataInsert.txtBUSYO_CD").val())
        );
    });

    //********************************************************************
    //   [参照]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmHRAKUDataInsert.cmdOpen").click(function () {
        me.fileMark = 0;
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".csv";
        $("#FrmHRAKUDataInsertFileUpload").html("");
        $("#FrmHRAKUDataInsertFileUpload").append(me.file.create());
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 2048000) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 2000KB です。"
                );
                return;
            }
            if (fileType != "csv") {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "指定されたファイルはCSV形式のファイルではありません。"
                );
                return;
            }

            $(".FrmHRAKUDataInsert.txtFile").val(this.files[i].name);
        });
        me.file.select_file();
    });
    //********************************************************************
    //   [実行]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmHRAKUDataInsert.cmdAct").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            me.clsComFnc.FncMsgBox("QY999", "実行します。よろしいですか？");
        }
    });
    //********************************************************************
    //   [担当者]検索ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmHRAKUDataInsert.syainSearch").click(function () {
        me.showSyainDialog();
    });
    //********************************************************************
    //   [入力拠点]検索ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmHRAKUDataInsert.busyoSearch").click(function () {
        me.showBusyoDialog();
    });
    //********************************************************************
    //   [作成]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmHRAKUDataInsert.cmdComplete").click(function () {
        me.openOutputDialog();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.Page_Load = function () {
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpDay = myDate.getDate().toString();
        if (tmpDay.length < 2) {
            tmpDay = "0" + tmpDay.toString();
        }
        var tmpNowDate =
            myDate.getFullYear().toString() +
            "/" +
            tmpMonth.toString() +
            "/" +
            tmpDay.toString();
        $(".FrmHRAKUDataInsert.txtSYAIN_NO").val($(".LogineduserID").html());
        var url = me.sys_id + "/" + me.id + "/" + "frmSample_Load";
        me.ajax.receive = function (result) {
            $(".FrmHRAKUDataInsert.cmdOpen").trigger("focus");
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".FrmHRAKUDataInsert.txtBUSYO_CD").val(result["BusyoCD"]);
                me.getAllBusyo = result["data"]["busyo"];
                me.getAllSyain = result["data"]["syain"];
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
            $(".FrmHRAKUDataInsert.lblSYAIN_NM").html(
                me.getSyainNM($(".FrmHRAKUDataInsert.txtSYAIN_NO").val())
            );
            $(".FrmHRAKUDataInsert.lblBUSYO_NM").html(
                me.getBusyoNM($(".FrmHRAKUDataInsert.txtBUSYO_CD").val())
            );
        };
        me.ajax.send(url, "", 0);
        me.strTougetu = tmpNowDate;
        $(".FrmHRAKUDataInsert.cboYM").val(me.strTougetu);
    };

    me.cmdAct_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnImport_Click";
        var data = {
            txtFile: $(".FrmHRAKUDataInsert.txtFile").val(),
        };
        //取込先
        $(".FrmHRAKUDataInsert.txtFile").val("");
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.clsComFnc.FncMsgBox("I9999", "楽楽データを取り込みました。");
                me.fileMark = 0;
                return;
            } else if (result["msg"] === "") {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["msg"]);
                me.fileMark = 0;
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    //********************************************************************
    //処理概要：ﾌｧｲﾙのﾁｪｯｸ処理
    //引　　数：なし
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    me.fncCheckFile = function () {
        var FileName = $(".FrmHRAKUDataInsert.txtFile").val();
        //取込ﾌｧｲﾙが未入力の場合はｴﾗｰ

        if (FileName.trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".FrmHRAKUDataInsert.cmdOpen");
            me.clsComFnc.FncMsgBox("W9999", "取込ファイルを指定してください。");
            return;
        }
        me.file.send(me.func);
    };

    me.func = function () {
        me.fileMark = 1;
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        me.clsComFnc.FncMsgBox("QY999", "実行します。よろしいですか？");
    };
    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };
    // [作成]ﾎﾞﾀﾝ
    me.openOutputDialog = function () {
        var dialogId = "FrmHRAKUOutputDialogDiv";
        var frmId = "FrmHRAKUOutput";
        var title = "データ出力";

        var $rootDiv = $(".FrmHRAKUDataInsert.content.R4-content");

        if ($("#" + dialogId).length > 0) {
            $("#" + dialogId).remove();
        }

        $("<div></div>").attr("id", dialogId).insertAfter($rootDiv);
        $("<div></div>").attr("id", "SYAINNO").insertAfter($rootDiv).hide();
        $("<div></div>").attr("id", "BUSYOCD").insertAfter($rootDiv).hide();

        $("#BUSYOCD").html($(".FrmHRAKUDataInsert.txtBUSYO_CD").val());
        $("#SYAINNO").html($(".FrmHRAKUDataInsert.txtSYAIN_NO").val());

        $("#" + dialogId).dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 517 : 640,
            width: 720,
            resizable: false,
            close: function () {
                $("#BUSYOCD").remove();
                $("#SYAINNO").remove();
                $("#" + dialogId).remove();
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
    //========== 設定関連 start ==========
    me.cmdSetBtn_Click = function () {
        //未入力チェック
        var groupName = $.trim($(".FrmHRAKUDataInsert.groupName").val());
        if (groupName == "") {
            me.clsComFnc.ObjFocus = $(".FrmHRAKUDataInsert.groupName");
            me.clsComFnc.FncMsgBox("W9999", "グループ名を入力してください");
            return;
        }
        var nameCheckContent = /\||<|>|\?|\*|:|\/|\\|"/;
        if (nameCheckContent.test(groupName)) {
            me.clsComFnc.ObjFocus = $(".FrmHRAKUDataInsert.groupName");
            me.clsComFnc.FncMsgBox(
                "W9999",
                'グループ名に次の文字を含めることはできません：「\\/:*?"<>|」'
            );
            return;
        }
        //重複チェック
        var url = me.sys_id + "/" + me.id + "/" + "repeatCheck";
        var data = {
            grNm: groupName,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "グループ名がすでに使用されています") {
                    me.clsComFnc.ObjFocus = $(".FrmHRAKUDataInsert.groupName");
                }
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var $root_div = $(".FrmHRAKUDataInsert.R4-content");
            if ($("#FrmHRAKUDataSetDialogDiv").length <= 0) {
                $("<div></div>")
                    .attr("id", "FrmHRAKUDataSetDialogDiv")
                    .insertAfter($root_div);
                $("<div></div>").attr("id", "grNm").insertAfter($root_div);
                $("<div></div>").attr("id", "keiriDt").insertAfter($root_div);
            }

            var $groupName = $root_div.parent().find("#grNm");
            var $dealDate = $root_div.parent().find("#keiriDt");

            $groupName.val($.trim($(".FrmHRAKUDataInsert.groupName").val()));
            $dealDate.val($(".FrmHRAKUDataInsert.cboYM").val());
            var dialog_url = "R4K/FrmHRAKUDataSet";
            me.ajax.receive = function (result) {
                $("#FrmHRAKUDataSetDialogDiv").html(result);
            };
            me.ajax.send(dialog_url, "", 0);
        };
        me.ajax.send(url, data, 0);
    };
    me.getSyainNM = function (tmp) {
        var res = "";
        for (key in me.getAllSyain) {
            if (me.getAllSyain[key]["SYAIN_NO"] == tmp) {
                res = me.getAllSyain[key]["SYAIN_NM"];
            }
        }
        return res;
    };
    me.getBusyoNM = function (tmp) {
        var res = "";
        for (key in me.getAllBusyo) {
            if (me.getAllBusyo[key]["BUSYO_CD"] == tmp) {
                res = me.getAllBusyo[key]["BUSYO_NM"];
            }
        }
        return res;
    };
    me.showSyainDialog = function () {
        me.RtnCD = "";
        $("<div></div>")
            .attr("id", "FrmSyainSearchDialogDiv")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();

        $("<div></div>")
            .attr("id", "BUSYOCD")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();
        $("<div></div>")
            .attr("id", "BUSYONM")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();
        $("<div></div>")
            .attr("id", "SYAINNO")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();
        $("<div></div>")
            .attr("id", "SYAINNM")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();

        $("<div></div>")
            .attr("id", "RtnCD")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();

        $("#BUSYOCD").html($(".FrmHRAKUDataInsert.txtBUSYO_CD").val());
        $("#SYAINNO").html($(".FrmHRAKUDataInsert.txtSYAIN_NO").val());

        $("#FrmSyainSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 557 : 625,
            width: 740,
            resizable: false,
            close: function () {
                me.RtnCD = $("#RtnCD").html();

                if (me.RtnCD == 1) {
                    $(".FrmHRAKUDataInsert.txtBUSYO_CD").val(
                        $("#BUSYOCD").html()
                    );
                    $(".FrmHRAKUDataInsert.lblBUSYO_NM").html(
                        $("#BUSYONM").html()
                    );
                    $(".FrmHRAKUDataInsert.txtSYAIN_NO").val(
                        $("#SYAINNO").html()
                    );
                    $(".FrmHRAKUDataInsert.lblSYAIN_NM").html(
                        $("#SYAINNM").html()
                    );

                    $(".FrmSimulationAllList.txtSYAIN_NO").trigger("focus");
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#SYAINNO").remove();
                $("#SYAINNM").remove();
                $("#FrmSyainSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmSyainSearch";
        var url = me.sys_id + "/" + frmId;

        me.ajax.receive = function (result) {
            $("#FrmSyainSearchDialogDiv").html(result);

            $("#FrmSyainSearchDialogDiv").dialog(
                "option",
                "title",
                "社員番号検索"
            );
            $("#FrmSyainSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, me.data, 0);
    };
    me.showBusyoDialog = function () {
        $("<div></div>")
            .attr("id", "FrmBusyoSearchDialogDiv")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();

        $("<div></div>")
            .attr("id", "BUSYOCD")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();
        $("<div></div>")
            .attr("id", "BUSYONM")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();
        $("<div></div>")
            .attr("id", "RtnCD")
            .insertAfter($(".FrmHRAKUDataInsert .R4-content"))
            .hide();

        $("#FrmBusyoSearchDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 557 : 680,
            width: 550,
            resizable: false,
            open: function () {
                $("#RtnCD").hide();
                $("#BUSYONM").hide();
                $("#BUSYOCD").hide();
            },
            close: function () {
                me.RtnCD = $("#RtnCD").html();

                if (me.RtnCD == 1) {
                    $(".FrmHRAKUDataInsert.txtBUSYO_CD").val(
                        $("#BUSYOCD").html()
                    );
                    $(".FrmHRAKUDataInsert.lblBUSYO_NM").html(
                        $("#BUSYONM").html()
                    );
                }

                $("#RtnCD").remove();
                $("#BUSYONM").remove();
                $("#BUSYOCD").remove();
                $("#FrmBusyoSearchDialogDiv").remove();
            },
        });

        var frmId = "FrmBusyoSearch";
        var url = me.sys_id + "/" + frmId;

        me.ajax.receive = function (result) {
            $("#FrmBusyoSearchDialogDiv").html(result);

            $("#FrmBusyoSearchDialogDiv").dialog(
                "option",
                "title",
                "部署コード検索"
            );
            $("#FrmBusyoSearchDialogDiv").dialog("open");
        };
        me.ajax.send(url, me.data, 0);
    };
    //========== 設定関連 end ==========

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHRAKUDataInsert = new R4.FrmHRAKUDataInsert();
    o_R4_FrmHRAKUDataInsert.load();
});
