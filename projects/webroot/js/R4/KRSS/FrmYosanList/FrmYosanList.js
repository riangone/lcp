/**
 * 説明：
 *
 *
 * @author
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                                      担当
 * YYYYMMDD           #ID                          XXXXXX                                                   FCSDL
 * 20171225           #2807                        依頼                                                      YIN
 * 20201117            bug                         2.FireFox inputのtype=numberタイプは正常に使用できません。     ZhangBoWen
 * * -----------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmYosanList_KRSS");
KRSS.FrmYosanList_KRSS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    var MessageBox = new gdmz.common.MessageBox();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "経常利益シミュレーション";
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmYosanList";
    me.sys_id = "KRSS";
    me.cboYMInit = "";

    me.fileMark = 0;
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".KRSS.FrmYosanList_KRSS.cmd002",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmYosanList_KRSS.cmd004",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmYosanList_KRSS.btn_cancel",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".KRSS.FrmYosanList_KRSS.cmd001",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".KRSS.FrmYosanList_KRSS.cboYM",
        type: "datepicker3",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    base_load = me.load;
    me.load = function () {
        base_load();
        me.frmYosanList_load();
    };

    /*
	 *****************
	 参照ボタンcmd001_Click
	 *****************
	 */
    $(".KRSS.FrmYosanList_KRSS.cmd001").click(function () {
        me.fileMark = 0;
        //参照ボタンcmd001_Click
        $(".KRSS.FrmYosanList_KRSS.file1Text").val("");
        me.fu = new gdmz.common.file();
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.fu.create());

        //20171225 YIN DEL S
        // me.fu.select_file();
        //20171225 YIN DEL E
        $("#file").change(function () {
            var i = 0;
            const file = this.files[i];
            const fileType = file.name.split(".").pop().toLowerCase();

            if (file.size > 2048000) {
                MessageBox.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "経常利益シミュレーション",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".KRSS.FrmYosanList_KRSS.cmd001").trigger("focus");
                return;
            }

            if (fileType != "xls" && fileType != "xlsx") {
                MessageBox.MessageBox(
                    "使用できるファイルは.xls,.xlsxです。",
                    "経常利益シミュレーション",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".KRSS.FrmYosanList_KRSS.cmd001").trigger("focus");
                return;
            }

            $(".KRSS.FrmYosanList_KRSS.file1Text").val(file.name);
        });
        //20171225 YIN INS S
        me.fu.select_file();
        //20171225 YIN INS E
    });
    //20201117 zhangbowen add S
    $(".FrmYosanList_KRSS.KI").numeric({
        decimal: false,
    });
    //20201117 zhangbowen add E
    /*
	 *****************
	 登録ボタンcmd002
	 *****************
	 */
    $(".KRSS.FrmYosanList_KRSS.cmd002 ").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            me.clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "経常利益シミュレーション",
                "YesNo",
                "Question"
            );
        }
    });

    /*
	 *****************
	 実績集計表ボタンcmd004
	 *****************
	 */
    $(".KRSS.FrmYosanList_KRSS.cmd004 ").click(function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnExcelOutput";
        var data = {
            KI: $(".KRSS.FrmYosanList_KRSS.KI").val(),
            cboYM:
                $(".KRSS.FrmYosanList_KRSS.cboYM").val().substring(0, 4) +
                "/" +
                $(".KRSS.FrmYosanList_KRSS.cboYM").val().substring(4, 6),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (result["MsgID"] == "E9999") {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["MsgID"] == "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("I0011");
                //20181026 YIN INS S
                downloadExcel = 0;
                //20181026 YIN INS E
                window.location.href = result["data"];
            }
        };
        me.ajax.send(url, data, 1);
    });

    $(".KRSS.FrmYosanList_KRSS.btn_cancel ").click(function () {
        me.clearMainPage();
    });

    $(".KRSS.FrmYosanList_KRSS.cboYM").on("blur", function () {
        if (
            me.clsComFnc.CheckDate3($(".KRSS.FrmYosanList_KRSS.cboYM")) == false
        ) {
            $(".KRSS.FrmYosanList_KRSS.cboYM").val(me.cboYMInit);
            $(".KRSS.FrmYosanList_KRSS.cboYM").trigger("focus");
        }
    });

    $(".KRSS.FrmYosanList_KRSS.KI").on("blur", function () {
        var tt = $(".KRSS.FrmYosanList_KRSS.KI").val();
        if (parseInt(tt) > 100) {
            //$(".KRSS.FrmYosanList_KRSS.KI").val(100);
        } else {
            if (parseInt(tt) < 0) {
                $(".KRSS.FrmYosanList_KRSS.KI").val(0);
            } else {
                if (isNaN(parseInt(tt)) == true) {
                    //	$(".KRSS.FrmYosanList_KRSS.KI").val(100);
                } else {
                    $(".KRSS.FrmYosanList_KRSS.KI").val(parseInt(tt));
                }
            }
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.clearMainPage = function () {
        $(".KRSS.FrmYosanList_KRSS.file1Text").val("");
        me.frmYosanList_load();
    };

    me.frmYosanList_load = function () {
        //ﾊﾟﾗﾒｰﾀを使用して初期表示
        var url = me.sys_id + "/" + me.id + "/" + "formLoad";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (
                    result["data"]["KI"].length > 0 &&
                    result["data"]["cboYM"].length > 0
                ) {
                    $(".KRSS.FrmYosanList_KRSS.KI").val(
                        result["data"]["KI"][0]["RKI"]
                    );
                    $(".KRSS.FrmYosanList_KRSS.cboYM").val(
                        result["data"]["cboYM"][0]["TOU_YM"]
                            .toString()
                            .substring(0, 7)
                            .replace("/", "")
                    );
                    me.cboYMInit = result["data"]["cboYM"][0]["TOU_YM"]
                        .toString()
                        .substring(0, 7)
                        .replace("/", "");
                    $(".KRSS.FrmYosanList_KRSS.KI").trigger("focus");
                    me.frmYosanList_checkAuth();
                } else {
                    $("#KRSS_FrmYosanList_KRSS").block();
                    $(".KRSS.FrmYosanList_KRSS.KI").prop("disabled", "true");
                    $(".KRSS.FrmYosanList_KRSS.cmd002").button("disable");
                    $(".KRSS.FrmYosanList_KRSS.cmd004").button("disable");
                }
            } else {
                $("#KRSS_FrmYosanList_KRSS").block();
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, "", 1);
    };

    me.frmYosanList_checkAuth = function () {
        var data = {
            controls: ["cmd002", "cmd001", "cmd004"],
        };
        var url = me.sys_id + "/" + me.id + "/" + "formLoadcheckAuthority";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    for (key in result["data"]) {
                        //権限あり
                        if (result["data"][key] == 1) {
                            $(".KRSS.FrmYosanList_KRSS." + key).button(
                                "enable"
                            );
                        }
                        //権限なし
                        else {
                            $(".KRSS.FrmYosanList_KRSS." + key).button(
                                "disable"
                            );
                        }
                    }
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                $("#KRSS_FrmYosanList_KRSS").block();
            }
        };
        me.ajax.send(url, data, 1);
    };

    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };

    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/cmdActClick";

        var KEIJOBIVal = $(".KRSS.FrmYosanList_KRSS.KI").val();
        var FileName = $(".KRSS.FrmYosanList_KRSS.file1Text").val();
        var arrayVal = {
            KEIJOBI: KEIJOBIVal,
            FILENAME: FileName,
        };
        me.data = {
            request: arrayVal,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            me.fileMark = 0;
            if (result["result"] == false) {
                $(".KRSS.FrmYosanList_KRSS.file1Text").val("");
                if (result["MsgID"] == "E9999") {
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["MsgID"] == "W9997") {
                    MessageBox.MessageBox(
                        result["data"],
                        "経常利益シミュレーション",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                    return;
                }
                if (result["MsgID"] == "W9999") {
                    me.clsComFnc.FncMsgBox("W9999", result["data"]);
                    return;
                }
            }
            if (result["result"] == true) {
                $(".KRSS.FrmYosanList_KRSS.file1Text").val("");
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "経常利益シミュレーション",
                    "OK"
                );
                $(".KRSS.FrmYosanList_KRSS.file1Text").val("");
                return;
            }
        };
        me.ajax.send(me.url, me.data, 0);
    };

    me.func = function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        me.clsComFnc.MsgBoxBtnFnc.Close = me.cmdAct_ClickNo;
        me.clsComFnc.MessageBox(
            "実行します。よろしいですか？",
            "経常利益シミュレーション",
            "YesNo",
            "Question"
        );
    };

    me.fncCheckFile = function () {
        var FileName = $(".KRSS.FrmYosanList_KRSS.file1Text").val();

        if (FileName.trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".KRSS.FrmYosanList_KRSS.file1Text");
            MessageBox.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "経常利益シミュレーション",
                "OK",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        me.fu.send(me.func);
        return;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};
$(function () {
    var o_FrmYosanList_KRSS = new KRSS.FrmYosanList_KRSS();
    o_FrmYosanList_KRSS.load();
});
