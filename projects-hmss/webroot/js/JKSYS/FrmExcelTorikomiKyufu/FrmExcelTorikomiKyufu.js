/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmExcelTorikomiKyufu");

JKSYS.FrmExcelTorikomiKyufu = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmExcelTorikomiKyufu";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmExcelTorikomiKyufu.btnDialog",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmExcelTorikomiKyufu.btnAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmExcelTorikomiKyufu.btnClose",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmExcelTorikomiKyufu.dtpYM",
        type: "datepicker3",
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
    //[...]ﾎﾞﾀﾝ
    $(".FrmExcelTorikomiKyufu.btnDialog").click(function () {
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".xls,.xlsx";
        me.file.res = "FrmExcelTorikomiKyufu";
        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());

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
            $(".FrmExcelTorikomiKyufu.txtPath").val(this.files[i].name);
        });
        me.file.select_file();
    });
    $(".FrmExcelTorikomiKyufu.btnClose").click(function () {
        $("#FrmExcelTorikomiKyufuDialogDiv").dialog("close");
    });
    //********************************************************************
    //   [取込]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmExcelTorikomiKyufu.btnAction").click(function () {
        var FileName = $(".FrmExcelTorikomiKyufu.txtPath").val();
        //取込ファイル指定なし
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox("W9999", "ファイルを指定してください");
            return false;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        me.clsComFnc.FncMsgBox("QY005");
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
        me.Page_load();
    };
    me.Page_load = function () {
        var cboYM = $("#CboYM").val();
        if (cboYM) {
            $(".FrmExcelTorikomiKyufu.dtpYM").val(cboYM);
        }
        $(".FrmExcelTorikomiKyufu.dtpYM").ympicker("disable");
    };
    //取込ボタン
    me.cmdAct_Click = function () {
        //ファイルアップロード
        me.file.send(me.func, me.cfunc);
    };
    me.cfunc = function () {
        $(".FrmExcelTorikomiKyufu.txtPath").val("");
    };
    me.func = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var data = {
            cboYM: $(".FrmExcelTorikomiKyufu.dtpYM").val(),
            txtPath: $(".FrmExcelTorikomiKyufu.txtPath").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox(
                    "I9999",
                    "取込処理が終了しました。(更新された行数：" +
                        result["row"] +
                        "行)"
                );
                $("#FrmExcelTorikomiKyufuDialogDiv").dialog("close");
                // $('.FrmExcelTorikomiKyufu.txtPath').val('');
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".FrmExcelTorikomiKyufu.txtPath").val("");
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_FrmExcelTorikomiKyufu_FrmExcelTorikomiKyufu =
        new JKSYS.FrmExcelTorikomiKyufu();
    o_FrmExcelTorikomiKyufu_FrmExcelTorikomiKyufu.load();
});
