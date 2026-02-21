/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                担当
 * YYYYMMDD           #ID                          XXXXXX                              FCSDL
 * 20160511　　　　　　　　   #2437                        実績取込機能改修                       Sun
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("KRSS.FrmJissekiTorikomi");

KRSS.FrmJissekiTorikomi = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========
    me.id = "FrmJissekiTorikomi";
    me.sys_id = "KRSS";
    me.url = "";
    me.data = "";
    //20160511 Sun Del. Start
    //clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    //20160511 Sun Del. End
    me.fileMark = 0;
    me.action = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJissekiTorikomi.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJissekiTorikomi.cancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmJissekiTorikomi.fileopen",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //初期化
    me.init_control = function () {
        base_init_control();
        $(".FrmJissekiTorikomi.cmdAction").button("disable");
    };

    //キャンセル
    $(".KRSS.FrmJissekiTorikomi.cancel").click(function () {
        $(".FrmJissekiTorikomi.txtFile").val("");
        $(".KRSS.FrmJissekiTorikomi.comment").prop("checked", true);
        $(".FrmJissekiTorikomi.cmdAction").button("disable");
    });

    //取込ファイルを開けます
    $(".FrmJissekiTorikomi.fileopen").click(function () {
        $(".FrmJissekiTorikomi.txtFile").val("");
        me.fu = new gdmz.common.file();
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.fu.create());

        me.fu.select_file();
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 2048000) {
                MessageBox.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "経常利益シミュレーション",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJissekiTorikomi.cmdAction").button("disable");
                $(".FrmJissekiTorikomi.txtFile").trigger("focus");
                return;
            }
            if (fileType != "xlsx") {
                MessageBox.MessageBox(
                    "使用できるファイルは.xlsxです。",
                    "経常利益シミュレーション",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJissekiTorikomi.cmdAction").button("disable");
                $(".FrmJissekiTorikomi.txtFile").trigger("focus");
                return;
            }
            me.fileMark = 0;
            $(".FrmJissekiTorikomi.cmdAction").button("enable");
            $(".FrmJissekiTorikomi.txtFile").val(this.files[i].name);
        });
    });

    //登録
    $(".FrmJissekiTorikomi.cmdAction").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "経常利益シミュレーション",
                "YesNo",
                "Question"
            );
        }
    });

    //取込ﾌｧｲﾙのチェック
    me.fncCheckFile = function () {
        var FileName = $(".FrmJissekiTorikomi.txtFile").val();
        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmJissekiTorikomi.txtFile");
            MessageBox.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "経常利益シミュレーション",
                "OK",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        me.fu.send(me.func);
    };
    me.func = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        clsComFnc.MsgBoxBtnFnc.Close = me.cmdAct_ClickNo;
        clsComFnc.MessageBox(
            "実行します。よろしいですか？",
            "経常利益シミュレーション",
            "YesNo",
            "Question"
        );
    };

    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };

    //登録
    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/cmdActClick";
        me.flg = "1";
        var FileName = $(".FrmJissekiTorikomi.txtFile").val();

        if ($(".KRSS.FrmJissekiTorikomi.comment").prop("checked") == true) {
            me.flg = "1";
        } else if (
            $(".KRSS.FrmJissekiTorikomi.service").prop("checked") == true
        ) {
            me.flg = "2";
        } else if (
            $(".KRSS.FrmJissekiTorikomi.hoken").prop("checked") == true
        ) {
            me.flg = "3";
        }
        //20160511 Sun Add Start
        else if (
            $(".KRSS.FrmJissekiTorikomi.tougetueigyo").prop("checked") == true
        ) {
            me.flg = "4";
        } else if (
            $(".KRSS.FrmJissekiTorikomi.tougetusabisu").prop("checked") == true
        ) {
            me.flg = "5";
        }
        //20160511 Sun Add End
        //20161012 Sun Add Start
        else if (
            $(".KRSS.FrmJissekiTorikomi.tougetuchuko").prop("checked") == true
        ) {
            me.flg = "6";
        }
        //20161012 Sun Add End
        else {
            me.flg = "0";
        }
        var arrayVal = {
            FILENAME: FileName,
            MARK: me.flg,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //20160511  Sun Del Start
            //me.fileMark = 0;
            //20160511  Sun Del End
            if (result["result"] == true) {
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "経常利益シミュレーション",
                    "OK"
                );
                $(".FrmJissekiTorikomi.txtFile").val("");
                $(".KRSS.FrmJissekiTorikomi.comment").prop("checked", true);
                $(".FrmJissekiTorikomi.cmdAction").button("disable");
                //20160511  Sun Add Start
                me.fileMark = 0;
                //20160511  Sun Add End
            } else {
                //20160511  Sun Del Start
                // $(".FrmJissekiTorikomi.txtFile").val('');
                //20160511  Sun Del End
                if (result["MsgID"] == "W9997" || result["MsgID"] == "W9999") {
                    MessageBox.MessageBox(
                        result["data"],
                        "経常利益シミュレーション",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                }
                //20160511  Sun Add Start
                me.fileMark = 1;
                //20160511  Sun Add End
                return;
            }
        };

        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_KRSS_FrmJissekiTorikomi = new KRSS.FrmJissekiTorikomi();
    o_KRSS_FrmJissekiTorikomi.load();
});
