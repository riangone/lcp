/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmExcelTorikomi");

JKSYS.FrmExcelTorikomi = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmExcelTorikomi";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    me.dtpYM = "";

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmExcelTorikomi.btnDialog",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmExcelTorikomi.btnAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmExcelTorikomi.dtpYM",
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
    //年月blur
    $(".FrmExcelTorikomi.dtpYM").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmExcelTorikomi.dtpYM")) == false) {
            $(".FrmExcelTorikomi.dtpYM").val(me.dtpYM);
            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmExcelTorikomi.dtpYM").trigger("focus");
                    $(".FrmExcelTorikomi.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmExcelTorikomi.dtpYM").trigger("focus");
                        $(".FrmExcelTorikomi.dtpYM").select();
                    }, 0);
                }
            }
            $(".FrmExcelTorikomi.btnAction").button("disable");
        } else {
            $(".FrmExcelTorikomi.btnAction").button("enable");
        }
    });
    //[...]ﾎﾞﾀﾝ
    $(".FrmExcelTorikomi.btnDialog").click(function () {
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".xls,.xlsx";
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
            $(".FrmExcelTorikomi.txtPath").val(this.files[i].name);
        });
        me.file.select_file();
    });

    //********************************************************************
    //   [実行]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmExcelTorikomi.btnAction").click(function () {
        if (me.fncInputChk()) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
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
        //コンボボックスに初期値設定
        me.dateGet();
    };
    //取得対象年月
    me.dateGet = function () {
        var url = me.sys_id + "/" + me.id + "/" + "dateGet";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            me.dtpYM = result["data"];
            $(".FrmExcelTorikomi.dtpYM").val(me.dtpYM);
            $(".FrmExcelTorikomi.btnAction").trigger("focus");
        };
        me.ajax.send(url, "", 0);
    };

    //********************************************************************
    //処理概要：入力チェック
    //引　　数：なし
    //戻 り 値：Boolean   （True:正常 / False:ｴﾗｰ）
    //********************************************************************
    me.fncInputChk = function () {
        //年月指定不正
        var dtpYM = $(".FrmExcelTorikomi.dtpYM").val();
        if (dtpYM.trimEnd() == "") {
            me.clsComFnc.FncMsgBox("W9999", "年月を指定してください");
            return false;
        }
        //取込ﾌｧｲﾙのﾁｪｯｸ処理
        var FileName = $(".FrmExcelTorikomi.txtPath").val();
        //取込ファイル指定なし
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox("W9999", "ファイルを指定してください");
            return false;
        }
        //種類指定なし
        var selectradio = $(
            'input[name="FrmExcelTorikomi_radio"]:checked'
        ).val();
        if (typeof selectradio == "undefined") {
            me.clsComFnc.FncMsgBox("W9999", "種類を指定してください");
            return false;
        }
        return true;
    };
    //実行ボタン
    me.cmdAct_Click = function () {
        //ファイルアップロード
        me.file.send(me.func);
    };
    me.func = function (err) {
        if (err) {
            $(".FrmExcelTorikomi.txtPath").val("");
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xls";
            $("#tmpFileUpload").html("");
            $("#tmpFileUpload").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var data = {
            cboYM: $(".FrmExcelTorikomi.dtpYM").val(),
            pMode: $('input[name="FrmExcelTorikomi_radio"]:checked').val(),
            txtPath: $(".FrmExcelTorikomi.txtPath").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] == "I0007") {
                    me.clsComFnc.FncMsgBox("I0007");
                    $(".FrmExcelTorikomi.txtPath").val("");
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".FrmExcelTorikomi.txtPath").val("");
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
    o_FrmExcelTorikomi_FrmExcelTorikomi = new JKSYS.FrmExcelTorikomi();
    o_FrmExcelTorikomi_FrmExcelTorikomi.load();
});
