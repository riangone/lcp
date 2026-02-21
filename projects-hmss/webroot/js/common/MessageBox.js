/**
 * クライアント共通関数
 * @author FCSDL　luchao
 */

Namespace.register("gdmz.common.MessageBox");

var MessageBoxIndex = 0;
var MessageBoxOpenFlag = 0;

gdmz.common.MessageBox = function () {
    var me = new Object();
    var MessageBoxId = "";
    // var strMsg = "";
    // var strTitle = "";
    // var MessageBoxButtonType = "";
    // var MessageBoxIconType = "";
    // var MessageBoxDefaultFocus = "";

    me.MsgBoxBtnFnc = {
        Yes: "",
        No: "",
        //20220402 HMTVE ciyuanchen add s
        OK: "",
        //20220402 HMTVE ciyuanchen add e
        Close: "",
        //20150805 krss zhenghuiyun add s
        Cancel: "",
        //20150805 krss zhenghuiyun add e
    };

    me.MessageBoxDefaultButton = {
        Button1: 0,
        Button2: 1,
    };

    me.MessageBoxButtons = {
        YesNo: "YesNo",
        OKCancel: "OKCancel",
        OK: "OK",
        //20150805 krss zhenghuiyun add s
        YesNoCancel: "YesNoCancel",
        //20150805 krss zhenghuiyun add e
    };

    me.MessageBoxIcon = {
        Err: "Error",
        Information: "Information",
        Warning: "Warning",
        Question: "Question",
    };

    me.GSYSTEM_NAME = "R4→（GD）（DZM）データ連携サブシステム";
    me.ObjFocus = "";
    me.ObjSelect = "";

    me.MakeDialog = function () {
        var Dialog = $("<div></div>");
        MessageBoxId = "MsgBox_" + MessageBoxIndex;
        Dialog.prop("id", MessageBoxId);
        Dialog.appendTo("body");
        MessageBoxIndex += 1;
    };

    me.MessageBox = function (
        strMsg,
        strTitle,
        MessageBoxButtonType,
        MessageBoxIconType,
        MessageBoxDefaultFocus
    ) {
        me.MakeDialog();
        $("#" + MessageBoxId).html("");
        MessageBoxDefaultFocus = arguments[4] != undefined ? arguments[4] : 0;
        var MessageDialog = $("#" + MessageBoxId);
        var MessageBoxIconPath = "";
        var MessageBoxButton = "";

        MessageDialog.dialog({
            width: "auto",
            resizable: false,
            height: "auto",
            autoOpen: false,
            overflow: false,
            modal: true,
            open: function () {
                MessageDialog.addClass("HMS_F9");
                setTimeout(() => {
                    $(this)
                        .parent()
                        .find(
                            ".ui-dialog-buttonpane button:eq(" +
                                MessageBoxDefaultFocus +
                                ")"
                        )
                        .focus();
                }, 0);
                MessageBoxOpenFlag = true;
                if (MessageBoxButtonType == "YesNo") {
                    $(this)
                        .parent()
                        .keydown(function (e) {
                            if (e.keyCode == 89) {
                                $(this)
                                    .find(".ui-dialog-buttonpane button:eq(0)")
                                    .click();
                            }
                            if (e.keyCode == 78) {
                                $(this)
                                    .find(".ui-dialog-buttonpane button:eq(1)")
                                    .click();
                            }
                        });
                }
            },
            close: function () {
                me.MsgBoxBtnFnc.Yes = "";
                me.MsgBoxBtnFnc.No = "";
                MessageDialog.dialog("destroy");

                MessageDialog.remove();
                if (me.ObjFocus != "") {
                    me.ObjFocus.focus();
                    me.ObjFocus = "";
                }
                if (me.ObjSelect != "") {
                    me.ObjSelect.select();
                    me.ObjSelect = "";
                }
                if (me.MsgBoxBtnFnc.Close != "") {
                    me.MsgBoxBtnFnc.Close();
                }
                MessageBoxOpenFlag = false;
            },
        });

        if ($.trim(strTitle) == "") {
            strTitle = me.GSYSTEM_NAME;
        }

        switch (MessageBoxIconType) {
            case "Error":
                MessageBoxIconPath = "img/error.png";
                break;
            case "Information":
                MessageBoxIconPath = "img/information.png";
                break;
            case "Warning":
                MessageBoxIconPath = "img/warning.png";
                break;
            case "Question":
                MessageBoxIconPath = "img/question.png";
                break;
        }

        MessageDialog.dialog("option", "title", strTitle);
        switch (MessageBoxButtonType) {
            case "OK":
                MessageBoxButton = {
                    OK: function () {
                        if (me.MsgBoxBtnFnc.Yes != "") {
                            me.MsgBoxBtnFnc.Yes();
                        }
                        //20220402 HMTVE ciyuanchen add s
                        if (me.MsgBoxBtnFnc.OK != "") {
                            me.MsgBoxBtnFnc.OK();
                        }
                        //20240419 lujunxia del s
                        //管理会計システム-車両売上-データ作成-売上データチェック（CSV作成）:「処理が正常に終了しました」メッセージが2回ポップアップされた問題
                        // if (me.MsgBoxBtnFnc.Close != '')
                        // {
                        // 	me.MsgBoxBtnFnc.Close();
                        // }
                        //20240419 lujunxia del e
                        //20220402 HMTVE ciyuanchen add e
                        MessageDialog.dialog("close");
                    },
                };
                break;
            case "OKCancel":
                MessageBoxButton = {
                    OK: function () {
                        if (me.MsgBoxBtnFnc.Yes != "") {
                            me.MsgBoxBtnFnc.Yes();
                        }
                        MessageDialog.dialog("close");
                    },
                    キャンセル: function () {
                        if (me.MsgBoxBtnFnc.No != "") {
                            me.MsgBoxBtnFnc.No();
                        }
                        MessageDialog.dialog("close");
                    },
                };
                break;
            case "YesNo":
                MessageBoxButton = {
                    "はい(Y)": function () {
                        if (me.MsgBoxBtnFnc.Yes != "") {
                            me.MsgBoxBtnFnc.Yes();
                        }
                        MessageDialog.dialog("close");
                    },
                    "いいえ(N)": function () {
                        if (me.MsgBoxBtnFnc.No != "") {
                            me.MsgBoxBtnFnc.No();
                        }
                        MessageDialog.dialog("close");
                    },
                };
                break;
            //20150805 krss zhenghuiyun add s
            case "YesNoCancel":
                MessageBoxButton = {
                    "はい(Y)": function () {
                        if (me.MsgBoxBtnFnc.Yes != "") {
                            me.MsgBoxBtnFnc.Yes();
                        }
                        MessageDialog.dialog("close");
                    },
                    "いいえ(N)": function () {
                        if (me.MsgBoxBtnFnc.No != "") {
                            me.MsgBoxBtnFnc.No();
                        }
                        MessageDialog.dialog("close");
                    },
                    "キャンセル(C)": function () {
                        if (me.MsgBoxBtnFnc.Cancel != "") {
                            me.MsgBoxBtnFnc.Cancel();
                        }
                        MessageDialog.dialog("close");
                    },
                };
                break;
            //20150805 krss zhenghuiyun add e
        }

        MessageDialog.dialog("option", "buttons", MessageBoxButton);
        var html = "";
        html = html + "<table>";
        html = html + "<tr>";
        html = html + "<td>";
        html = html + "<img src='" + MessageBoxIconPath + "'></img>";
        html = html + "<td>";
        html = html + "<td>";
        html = html + "<label>" + strMsg + "</label>";
        html = html + "<td>";
        html = html + "<tr>";
        html = html + "</table>";
        MessageDialog.html(html);
        MessageDialog.dialog("open");
        //20180420 YIN INS S
        $(".ui-dialog-content.ui-widget-content.HMS_F9").css(
            "overflow",
            "visible"
        );
        //20180420 YIN INS E
    };
    return me;
};
