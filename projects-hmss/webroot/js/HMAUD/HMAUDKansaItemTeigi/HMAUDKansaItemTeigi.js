/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20250219           機能変更               20250219_内部統制_改修要望.xlsx                    LHB
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKansaItemTeigi");

HMAUD.HMAUDKansaItemTeigi = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMAUDKansaItemTeigi";
    me.sys_id = "HMAUD";
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.HMAUD = new HMAUD.HMAUD();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.gennzayiCour = "";
    me.SessionPrePG = "";
    me.allCourData = "";
    me.controls.push({
        id: ".HMAUDKansaItemTeigi.btnDialog",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDKansaItemTeigi.btnReturn",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".HMAUDKansaItemTeigi.btnUpload",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //[参照]ﾎﾞﾀﾝクリック
    $(".HMAUDKansaItemTeigi.btnDialog").click(function () {
        me.btnDialog_click();
    });
    //アップロードﾎﾞﾀﾝクリック
    $(".HMAUDKansaItemTeigi.btnUpload").click(function () {
        me.btnUpload_Click();
    });
    //戻るボタンクリック
    $(".HMAUDKansaItemTeigi.btnReturn").click(function () {
        me.btnReturn_Click();
    });
    $(".HMAUDKansaItemTeigi.cours").change(function () {
        me.checkcours();
        me.fncCourChange();
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
        me.HMAUDKansaItemTeigi_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：フォームロード
	 '関 数 名：HMAUDKansaItemTeigi_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.HMAUDKansaItemTeigi_Load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "pageload";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //検索
                $(".HMAUDKansaItemTeigi.btnUpload").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                //指摘事項NO65:クール数の欄をプルダウンにする
                $(".HMAUDKansaItemTeigi.cours").find("option").remove();
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".HMAUDKansaItemTeigi.cours");
                if (result["data"]["cour"].length > 0) {
                    var courAll = result["data"]["cour"];
                    me.allCourData = courAll;
                    for (var i = 0; i < courAll.length; i++) {
                        //クールselect
                        $("<option></option>")
                            .val(courAll[i]["COURS"])
                            .text(courAll[i]["COURS"])
                            .appendTo(".HMAUDKansaItemTeigi.cours");
                        if (courAll[i]["COURS_NOW"] == "1") {
                            //現在のクール数
                            me.gennzayiCour = courAll[i]["COURS"];
                        }
                    }
                }
                if (result["data"]["cour"].length > 0) {
                    // me.coursnow = result['data']['cour'][0]['COURS'];
                    $(".HMAUDKansaItemTeigi.cours").val(me.gennzayiCour);
                }
                if (result["data"]["admin"].length > 0) {
                    me.admin = result["data"]["admin"];
                }
                me.fncCourChange();
            }
        };
        me.ajax.send(url, "", 0);
        $("<option></option>")
            .val("0")
            .text("")
            .appendTo(".HMAUDKansaItemTeigi.field");
        $("<option></option>")
            .val("1")
            .text("営業")
            .appendTo(".HMAUDKansaItemTeigi.field");
        $("<option></option>")
            .val("2")
            .text("サービス")
            .appendTo(".HMAUDKansaItemTeigi.field");
        $("<option></option>")
            .val("3")
            .text("管理")
            .appendTo(".HMAUDKansaItemTeigi.field");
        $("<option></option>")
            .val("4")
            .text("業売")
            .appendTo(".HMAUDKansaItemTeigi.field");
        $("<option></option>")
            .val("5")
            .text("業売管理")
            .appendTo(".HMAUDKansaItemTeigi.field");
        if (gdmz.SessionPrePG) {
            $(".HMAUDKansaItemTeigi.btnReturn").show();
            me.SessionPrePG = gdmz.SessionPrePG;
        } else {
            $(".HMAUDKansaItemTeigi.btnReturn").hide();
        }
        $(".HMAUDKansaItemTeigi.cours").trigger("focus");
    };
    me.checkcours = function () {
        if (
            !(
                parseInt($(".HMAUDKansaItemTeigi.cours").val()) <
                    parseInt(me.gennzayiCour) && me.admin == "0"
            )
        ) {
            $(".HMAUDKansaItemTeigi.btnUpload").button("enable");
            $(".HMAUDKansaItemTeigi.btnDialog").button("enable");
        } else {
            $(".HMAUDKansaItemTeigi.btnUpload").button("disable");
            $(".HMAUDKansaItemTeigi.btnDialog").button("disable");
        }
    };
    me.btnDialog_click = function () {
        //参照ボタンcmdOpen_Click
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
            if (this.files[i].size > 2048000) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 2000KB です。"
                );
                return;
            }
            if (fileType != "xlsx") {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "指定されたファイルはxlsx形式のファイルではありません。"
                );
                return;
            }
            $(".HMAUDKansaItemTeigi.txtFile").val(this.files[i].name);
        });
        me.file.select_file();
    };
    me.func = function (err) {
        if (err) {
            $(".HMAUDKansaItemTeigi.txtFile").val("");
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xls";
            $("#tmpFileUpload").html("");
            $("#tmpFileUpload").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnActionClick";
        var data = {
            COURS: $(".HMAUDKansaItemTeigi.cours").val(),
            TERRITORY: $(".HMAUDKansaItemTeigi.field").val(),
            txtPath: $(".HMAUDKansaItemTeigi.txtFile").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] == "I0007") {
                    me.clsComFnc.FncMsgBox("I0007");
                    $(".HMAUDKansaItemTeigi.txtFile").val("");
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".HMAUDKansaItemTeigi.txtFile").val("");
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.btnUpload_Click = function () {
        if (me.InpuetCheck()) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpload_Click_Check;
            me.clsComFnc.FncMsgBox("QY005");
        }
    };
    //ファイルアップロード完了
    me.btnUpload_Click_Check = function () {
        me.file.send(me.func);
        return;
    };

    me.InpuetCheck = function () {
        var cours = $(".HMAUDKansaItemTeigi.cours").val();
        if (cours.trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDKansaItemTeigi.cours");
            me.clsComFnc.FncMsgBox("W9999", "クールを指定してください。");
            return false;
        }
        var field = $(".HMAUDKansaItemTeigi.field").val();
        if (field == 0) {
            me.clsComFnc.ObjFocus = $(".HMAUDKansaItemTeigi.field");
            me.clsComFnc.FncMsgBox("W9999", "領域を指定してください。");
            return false;
        }
        var FileName = $(".HMAUDKansaItemTeigi.txtFile").val();
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "取込対象のファイルを指定してください。"
            );
            return false;
        }
        return true;
    };
    me.fncCourChange = function () {
        var cour = $(".HMAUDKansaItemTeigi.cours").val();
        var foundDT = undefined;
        // 20250219 LHB INS S
        if (parseInt(cour) >= 18) {
            if ($('.HMAUDKansaItemTeigi.field option[value="6"]').length == 0) {
                $("<option></option>")
                    .val("6")
                    .text("カーセブン")
                    .appendTo(".HMAUDKansaItemTeigi.field");
            }
        } else {
            $('.HMAUDKansaItemTeigi.field option[value="6"]').remove();
        }
        // 20250219 LHB INS E
        if (cour) {
            if (me.allCourData) {
                var foundDT_array = me.allCourData.filter(function (element) {
                    return element["COURS"] == cour;
                });
                if (foundDT_array.length > 0 && cour !== "") {
                    foundDT = foundDT_array[0];
                    $(".HMAUDKansaItemTeigi.courPeriod").text(
                        foundDT ? foundDT["PERIOD"] : ""
                    );
                }
            }
        } else {
            $(".HMAUDKansaItemTeigi.courPeriod").text("");
        }
    };
    // **********************************************************************
    // 処 理 名：戻るボタンクリック
    // 関 数 名：btnReturn_Click
    // 戻 り 値：なし
    // 処理説明：前のペッジを遷移
    // **********************************************************************
    me.btnReturn_Click = function () {
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDKansaItemTeigi"
        );
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "select_node",
            "#" + me.SessionPrePG
        );
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_HMAUDKansaItemTeigi_HMAUDKansaItemTeigi = new HMAUD.HMAUDKansaItemTeigi();
    o_HMAUDKansaItemTeigi_HMAUDKansaItemTeigi.load();
});
