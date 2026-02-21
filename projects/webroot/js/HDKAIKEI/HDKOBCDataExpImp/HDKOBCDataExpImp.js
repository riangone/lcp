Namespace.register("HDKAIKEI.HDKOBCDataExpImp");

HDKAIKEI.HDKOBCDataExpImp = function () {
    var me = new gdmz.base.panel();
    me.panel = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.MessageBox = new gdmz.common.MessageBox();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "HDKOBCDataExpImp";
    me.sys_id = "HDKAIKEI";
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.url = "";
    me.data = new Array();
    me.blnStart = false;
    me.BusyoArrCode = new Array();
    me.pager = "";
    me.sidx = "";
    me.filename = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HDKOBCDataExpImp.btnExport",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HDKOBCDataExpImp.btnImport",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown();

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown();
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".HDKOBCDataExpImp.btnExport").click(function () {
        me.exportclick();
    });
    $(".HDKOBCDataExpImp.btnImport").click(function () {
        me.importclick();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //'**********************************************************************
    //'処 理 名：Excel出力
    //'関 数 exportclick
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：Excel出力
    //'**********************************************************************
    me.exportclick = function () {
        var selectVal = $(
            ".HDKOBCDataExpImp.selectTable option:selected"
        ).text();

        if (selectVal == "" || selectVal == null) {
            me.clsComFnc.FncMsgBox("W9999", "対象を選択してください。");
            return;
        }
        var selecttable = $(
            ".HDKOBCDataExpImp.selectTable option:selected"
        ).val();

        var data = {
            selecttable: selecttable,
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnDownloadClick";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                }
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (result["report"]) {
                //XLSXダウンロードボタンでEXCELダウンロード
                window.location.href = result["data"];
            }
        };
        me.ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：Excelのインポート
    //'関 数 importclick
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：Excelのインポート
    //'**********************************************************************
    me.importclick = function () {
        var selectVal = $(
            ".HDKOBCDataExpImp.selectTable option:selected"
        ).text();

        if (selectVal == "" || selectVal == null) {
            me.clsComFnc.FncMsgBox("W9999", "対象を選択してください。");
            return;
        }
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".xlsx";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (fileType != "xlsx") {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "指定されたファイルはxlsx形式のファイルではありません。"
                );
                return;
            }
            me.filename = this.files[i].name;
            me.btnUpload_Click_Check();
        });
        me.file.select_file();
    };

    //ファイルアップロード完了
    me.btnUpload_Click_Check = function () {
        me.file.send(me.func);
        return;
    };

    me.func = function (err) {
        if (err) {
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xls";
            $("#tmpFileUpload").html("");
            $("#tmpFileUpload").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var selectVal = $(
            ".HDKOBCDataExpImp.selectTable option:selected"
        ).text();

        if (selectVal == "") {
            me.clsComFnc.ObjFocus = $(".HDKOBCDataExpImp.selectTable");
            me.clsComFnc.FncMsgBox("W9999", "対象を選択してください。");
            return;
        }
        var selecttable = $(
            ".HDKOBCDataExpImp.selectTable option:selected"
        ).val();

        var data = {
            selecttable: selecttable,
            filename: me.filename,
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnActionClick";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0015");
            } else {
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
    var o_HDKAIKEI_HDKOBCDataExpImp = new HDKAIKEI.HDKOBCDataExpImp();
    o_HDKAIKEI_HDKOBCDataExpImp.load();

    o_HDKAIKEI_HDKAIKEI.HDKOBCDataExpImp = o_HDKAIKEI_HDKOBCDataExpImp;
});
