/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("JKSYS.FrmKyuyoInfoTake");

JKSYS.FrmKyuyoInfoTake = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmKyuyoInfoTake";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmKyuyoInfoTake.dtpYM",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyuyoInfoTake.btnDialog",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmKyuyoInfoTake.btnImport",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown = function () {
        var $inp = $(".FrmKyuyoInfoTake.Enter");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                e.preventDefault();
                var nxtIdx = $inp.index(this);
                var tabindex = Number($($inp[nxtIdx]).attr("tabindex"));
                if (this.type != "submit") {
                    $("[tabindex=" + (tabindex + 1) + "]").trigger("focus");

                    return false;
                }
            }
        });
    };
    me.clsComFnc.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //年月blur
    $(".FrmKyuyoInfoTake.dtpYM").on("blur", function (e) {
        if (me.clsComFnc.CheckDate3($(".FrmKyuyoInfoTake.dtpYM")) == false) {
            $(".FrmKyuyoInfoTake.dtpYM").val(me.dtpYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmKyuyoInfoTake.dtpYM").trigger("focus");
                    $(".FrmKyuyoInfoTake.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmKyuyoInfoTake.dtpYM").trigger("focus");
                        $(".FrmKyuyoInfoTake.dtpYM").select();
                    }, 0);
                }
            }

            $(".FrmKyuyoInfoTake.btnImport").button("disable");
            return;
        } else {
            $(".FrmKyuyoInfoTake.btnImport").button("enable");
        }
    });
    //[...]ﾎﾞﾀﾝクリック
    $(".FrmKyuyoInfoTake.btnDialog").click(function () {
        me.btnDialog_click();
    });
    //取込ﾎﾞﾀﾝクリック
    $(".FrmKyuyoInfoTake.btnImport").click(function () {
        me.btnImport_Click();
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
        //フォームロード
        me.FrmKyuyoInfoTake_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：FrmKyuyoInfoTake_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.FrmKyuyoInfoTake_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "FrmKyuyoInfoTake_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                $(".FrmKyuyoInfoTake").ympicker("disable");
                $(".FrmKyuyoInfoTake").attr("disabled", true);
                $(".FrmKyuyoInfoTake button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            } else {
                if (result["data"]["SYORI_YM"]) {
                    me.dtpYM = result["data"]["SYORI_YM"];
                    $(".FrmKyuyoInfoTake.dtpYM").val(me.dtpYM);
                    $(".FrmKyuyoInfoTake.dtpYM").select();
                }
            }
        };
        me.ajax.send(url, "", 0);
    };
    /*
     '**********************************************************************
     '処 理 名：ファイルダイアログ
     '関 数 名：btnDialog_click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnDialog_click = function () {
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".csv";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
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
            $(".FrmKyuyoInfoTake.txtFile").val(this.files[i].name);
        });
        me.file.select_file();
    };
    /*
     '**********************************************************************
     '処 理 名：取込ボタンクリック
     '関 数 名：btnImport_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnImport_Click = function () {
        //1.指定パスのファイルチェック
        if (me.procFilePathCheck()) {
            //2.データの存在チェック
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnImport_Click_Check;
            me.clsComFnc.FncMsgBox("QY005");
        }
    };
    /*
     '**********************************************************************
     '処 理 名：取込_はいボタンクリック
     '関 数 名：btnImport_Yes_Click
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.btnImport_Yes_Click = function () {
        //ファイルアップロード
        me.file.send(me.func);
    };
    //ファイルアップロード完了
    me.func = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnImport_Click";
        var data = {
            dtpYM: $(".FrmKyuyoInfoTake.dtpYM").val(),
            kbn: $('input[name="FrmKyuyoInfoTake_radio"]:checked').val(),
            txtFile: $(".FrmKyuyoInfoTake.txtFile").val(),
        };
        //取込先
        $(".FrmKyuyoInfoTake.txtFile").val("");
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                me.clsComFnc.FncMsgBox("I0007");
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
     '**********************************************************************
     '処 理 名：指定パスのファイルチェック
     '関 数 名：procFilePathCheck
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.procFilePathCheck = function () {
        //対象年月のﾁｪｯｸ処理
        var dtpYM = $(".FrmKyuyoInfoTake.dtpYM").val();
        if (dtpYM.trimEnd() == "") {
            me.clsComFnc.FncMsgBox("W9999", "年月を指定してください。");
            return false;
        }
        //種類のﾁｪｯｸ処理
        var selectradio = $(
            'input[name="FrmKyuyoInfoTake_radio"]:checked'
        ).val();
        if (typeof selectradio == "undefined") {
            me.clsComFnc.FncMsgBox("W9999", "種類を指定してください。");
            return false;
        }
        //取込ﾌｧｲﾙのﾁｪｯｸ処理
        var FileName = $(".FrmKyuyoInfoTake.txtFile").val();
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "取込対象のファイルを指定してください。"
            );
            return false;
        }
        return true;
    };
    //データの存在チェック
    me.btnImport_Click_Check = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnImport_Click_check";
        var kbn = $('input[name="FrmKyuyoInfoTake_radio"]:checked').val();
        var data = {
            dtpYM: $(".FrmKyuyoInfoTake.dtpYM").val().replace("/", ""),
            kbn: kbn,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["row"] <= 0) {
                    me.btnImport_Yes_Click();
                } else {
                    var dtpYM = $(".FrmKyuyoInfoTake.dtpYM").val();
                    if (kbn == "1") {
                        var strMsg =
                            dtpYM.substring(0, 4) +
                            "年" +
                            dtpYM.substring(4, 6) +
                            "月分の給与";
                    } else {
                        var strMsg =
                            dtpYM.substring(0, 4) +
                            "年" +
                            dtpYM.substring(4, 6) +
                            "月分の賞与";
                    }

                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnImport_Yes_Click;
                    me.clsComFnc.FncMsgBox(
                        "QY999",
                        "既に" +
                            strMsg +
                            "データが取り込まれていますが、再取込を行いますか？"
                    );
                }
            }
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
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
    o_FrmKyuyoInfoTake_FrmKyuyoInfoTake = new JKSYS.FrmKyuyoInfoTake();
    o_FrmKyuyoInfoTake_FrmKyuyoInfoTake.load();
});
