/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmExcelTorikomiKouka");

JKSYS.FrmExcelTorikomiKouka = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmExcelTorikomiKouka";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.dtpFROM = "";
    me.dtpTO = "";
    me.dtpFROMDay = "";
    me.dtpTODay = "";
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmExcelTorikomiKouka.btnDialog",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmExcelTorikomiKouka.btnAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmExcelTorikomiKouka.dtpYMFROM",
        type: "datepicker2",
        handle: "",
    });
    me.controls.push({
        id: ".FrmExcelTorikomiKouka.dtpYMTO",
        type: "datepicker2",
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

    //年月blur
    //From
    $(".FrmExcelTorikomiKouka.dtpYMFROM").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate2($(".FrmExcelTorikomiKouka.dtpYMFROM")) ==
            false
        ) {
            me.FrmExcelTorikomiKouka_Load();
            $(".FrmExcelTorikomiKouka.dtpYMFROM").val(me.dtpFROM);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmExcelTorikomiKouka.dtpYMFROM").trigger("focus");
                    $(".FrmExcelTorikomiKouka.dtpYMFROM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmExcelTorikomiKouka.dtpYMFROM").trigger("focus");
                        $(".FrmExcelTorikomiKouka.dtpYMFROM").select();
                    }, 0);
                }
            }
            $(".FrmExcelTorikomiKouka.btnAction").button("disable");
        } else {
            $(".FrmExcelTorikomiKouka.btnAction").button("enable");
        }
    });
    //To
    $(".FrmExcelTorikomiKouka.dtpYMTO").on("blur", function (e) {
        if (
            me.clsComFnc.CheckDate2($(".FrmExcelTorikomiKouka.dtpYMTO")) ==
            false
        ) {
            me.FrmExcelTorikomiKouka_Load();
            $(".FrmExcelTorikomiKouka.dtpYMTO").val(me.dtpTO);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmExcelTorikomiKouka.dtpYMTO").trigger("focus");
                    $(".FrmExcelTorikomiKouka.dtpYMTO").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmExcelTorikomiKouka.dtpYMTO").trigger("focus");
                        $(".FrmExcelTorikomiKouka.dtpYMTO").select();
                    }, 0);
                }
            }
            $(".FrmExcelTorikomiKouka.btnAction").button("disable");
        } else {
            $(".FrmExcelTorikomiKouka.btnAction").button("enable");
        }
    });
    //ファイル選択ボタン([...]ﾎﾞﾀﾝ)
    $(".FrmExcelTorikomiKouka.btnDialog").click(function () {
        me.btnDialog_Click();
    });
    //実行ボタン
    $(".FrmExcelTorikomiKouka.btnAction").click(function () {
        //入力チェック
        if (me.fncInputChk()) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnAction_Click;
            me.clsComFnc.FncMsgBox("QY005");
        }
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
        //初期処理
        me.FrmExcelTorikomiKouka_Load();
        //選択した日付を取得する
        $(".FrmExcelTorikomiKouka.dtpYMFROM").datepicker(
            "option",
            "onSelect",
            function (_a, b) {
                var dateStr = b.selectedDay + "";
                me.dtpFROMDay = dateStr.length == 1 ? "0" + dateStr : dateStr;
            }
        );
        $(".FrmExcelTorikomiKouka.dtpYMTO").datepicker(
            "option",
            "onSelect",
            function (_a, b) {
                var dateStr = b.selectedDay + "";
                me.dtpTODay = dateStr.length == 1 ? "0" + dateStr : dateStr;
            }
        );
        $(".FrmExcelTorikomiKouka.dtpYMFROM").val(me.dtpFROM);
        $(".FrmExcelTorikomiKouka.dtpYMTO").val(me.dtpTO);
    };
    //********************************************************************
    //処理概要：初期処理
    //引　　数：なし
    //戻 り 値：なし
    //********************************************************************
    me.FrmExcelTorikomiKouka_Load = function () {
        var now = new Date();
        //システム日付が４月～９月なら、画面の期間を前年１０月～３月に
        if (now.getMonth() >= 3 && now.getMonth() <= 8) {
            me.dtpFROMDay = "01";
            me.dtpFROM = now.getFullYear() - 1 + "/10";
            me.dtpTODay = "31";
            me.dtpTO = now.getFullYear() + "/03";
        } else {
            //システム日付が１０月～３月なら、画面の期間を４月～９月に
            me.dtpFROMDay = "01";
            me.dtpFROM = now.getFullYear() + "/04";
            me.dtpTODay = "30";
            me.dtpTO = now.getFullYear() + "/09";
        }
    };
    //********************************************************************
    //処理概要：ファイル選択ボタン([...]ﾎﾞﾀﾝ)
    //引　　数：なし
    //戻 り 値：なし
    //********************************************************************
    me.btnDialog_Click = function () {
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".xls,.xlsx";

        $("#tmpFileUpload_Kouka").html("");
        $("#tmpFileUpload_Kouka").append(me.file.create());

        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 5120000) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 5000KB です。"
                );
                return;
            }

            //ファイル拡張子のチェック
            if (fileType != "xls" && fileType != "xlsx") {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "EXCELファイルを指定してください"
                );
                return;
            }
            $(".FrmExcelTorikomiKouka.txtPath").val(this.files[i].name);
        });
        me.file.select_file();
    };
    //********************************************************************
    //処理概要：入力チェック
    //引　　数：なし
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    me.fncInputChk = function () {
        var dtpYMFROM =
            $(".FrmExcelTorikomiKouka.dtpYMFROM").val() + "/" + me.dtpFROMDay;
        var dtpYMTO =
            $(".FrmExcelTorikomiKouka.dtpYMTO").val() + "/" + me.dtpTODay;
        //年月指定不正
        if (dtpYMFROM.trim() == "") {
            me.clsComFnc.FncMsgBox("W9999", "年月を指定してください");
            return false;
        }
        if (
            dtpYMFROM.substring(5, 7) != "04" &&
            dtpYMFROM.substring(5, 7) != "10"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "年月が不正です");
            return false;
        }
        if (dtpYMTO.trim() == "") {
            me.clsComFnc.FncMsgBox("W9999", "年月を指定してください");
            return false;
        }
        if (
            dtpYMTO.substring(5, 7) != "09" &&
            dtpYMTO.substring(5, 7) != "03"
        ) {
            me.clsComFnc.FncMsgBox("W9999", "年月が不正です");
            return false;
        }
        //種類指定なし
        var selectradio = $(
            'input[name="FrmExcelTorikomiKouka_radio"]:checked'
        ).val();
        if (typeof selectradio == "undefined") {
            me.clsComFnc.FncMsgBox("W9999", "種類を指定してください");
            return false;
        }
        if (dtpYMFROM > dtpYMTO) {
            me.clsComFnc.FncMsgBox("W0006", "対象期間");
            return false;
        }
        //取込ﾌｧｲﾙのﾁｪｯｸ処理
        var FileName = $(".FrmExcelTorikomiKouka.txtPath").val();
        //取込ファイル指定なし
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox("W9999", "ファイルを指定してください");
            return false;
        }
        return true;
    };
    //********************************************************************
    //処理概要：実行ボタン
    //引　　数：なし
    //戻 り 値：なし
    //********************************************************************
    me.btnAction_Click = function () {
        //ファイルアップロード
        me.file.send(me.func);
    };
    me.func = function (err) {
        if (err) {
            $(".FrmExcelTorikomiKouka.txtPath").val("");
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xls";
            $("#tmpFileUpload_Kouka").html("");
            $("#tmpFileUpload_Kouka").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var dtpYMFROM =
            $(".FrmExcelTorikomiKouka.dtpYMFROM").val() + "/" + me.dtpFROMDay;
        var dtpYMTO =
            $(".FrmExcelTorikomiKouka.dtpYMTO").val() + "/" + me.dtpTODay;
        var data = {
            pMode: $('input[name="FrmExcelTorikomiKouka_radio"]:checked').val(),
            txtPath: $(".FrmExcelTorikomiKouka.txtPath").val(),
            dateFrom: dtpYMFROM,
            dateTo: dtpYMTO,
        };
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0007");
                $(".FrmExcelTorikomiKouka.txtPath").val("");
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".FrmExcelTorikomiKouka.txtPath").val("");
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_FrmExcelTorikomiKouka_FrmExcelTorikomiKouka =
        new JKSYS.FrmExcelTorikomiKouka();
    o_FrmExcelTorikomiKouka_FrmExcelTorikomiKouka.load();
});
