/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */
Namespace.register("HMSS.Login");

HMSS.Login = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    //20180426 lqs INS S
    document.oncontextmenu = nocontextmenu;
    document.onmousedown = norightclick;
    function nocontextmenu(event) {
        event.cancelBubble = true;
        event.returnValue = false;

        return false;
    }

    function norightclick(e) {
        if (window.Event) {
            if (e.which == 2 || e.which == 3) return false;
        } else if (e.button == 2 || e.button == 3) {
            e.cancelBubble = true;
            e.returnValue = false;
            return false;
        }
    }

    jQuery(document).ready(function () {
        if (window.history && window.history.pushState) {
            $(window).on("popstate", function () {
                //当点击浏览器的 后退和前进按钮 时才会被触发
                window.history.pushState("forward", null, "");
                window.history.forward(1);
                // window.history.go('forward', null, '');
            });
        }

        //在IE中必须得有这两行
        window.history.pushState("forward", null, "");
        window.history.forward(1);
    });

    $(document).keydown(function (event) {
        //屏蔽 Alt+ 方向键 ←
        //屏蔽 Alt+ 方向键 →
        if (event.altKey && (event.keyCode == 37 || event.keyCode == 39)) {
            event.returnValue = false;
            return false;
        }
        //屏蔽F5刷新键
        if (event.keyCode == 116) {
            return false;
        }
        //屏蔽F1键
        if (event.keyCode == 112) {
            window.onhelp = function () {
                return false;
            };
            return false;
        }
        //屏蔽ctrl+R
        if (event.ctrlKey && event.keyCode == 82) {
            return false;
        }
    });
    //20180426 lqs INS E

    me.id = "Login";
    me.sys_id = "HMSS";

    me.HMSS = null;
    me.data = "";
    me.times = 0;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".Login.btnLogin",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".Login.btnReset",
        type: "button",
        handle: "",
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();

        $("#mainDialog").dialog({
            autoOpen: false,
            modal: true,
            height: 900,
            width: 1200,
        });

        $("#LoginForgetAllStep_dialog").dialog({
            autoOpen: false,
            width: 550,
            height: "auto",
            modal: true,
            resizable: false,
            classes: {
                "ui-dialog": "RemoveCloseMark",
            },
            open: function () {
                $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
            },
        });

        $("#LoginForgetIdStep_dialog").dialog({
            autoOpen: false,
            width: 550,
            height: "auto",
            modal: true,
            resizable: false,
            classes: {
                "ui-dialog": "RemoveCloseMark",
            },
            open: function () {
                $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
            },
        });

        $("#LoginForgetPasswordStep_dialog").dialog({
            autoOpen: false,
            width: 550,
            height: "auto",
            modal: true,
            resizable: false,
            classes: {
                "ui-dialog": "RemoveCloseMark",
            },
            open: function () {
                $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
            },
        });

        $("#LoginSendMailSuc_dialog").dialog({
            autoOpen: false,
            width: 550,
            height: 210,
            modal: true,
            resizable: false,
            classes: {
                "ui-dialog": "RemoveCloseMark",
            },
            open: function () {
                $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
            },
        });
        $("#indexLoading").dialog({
            autoOpen: false,
            width: 330,
            height: 91,
            modal: true,
            resizable: false,
        });
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    $(".Login.btnLogin").focus();
    $(".Login.ErrorInfo").hide();

    $(".Login.txtUser").focus(function () {
        $(".Login.ErrorInfo").hide();
    });

    $(".Login.txtPassword").focus(function () {
        $(".Login.ErrorInfo").hide();
    });

    $(".Login.btnReset").click(function () {
        $(".Login.txtUser").val("");
        $(".Login.txtPassword").val("");
        $(".Login.txtUser").focus();
    });

    $(".Login.btnLogin").click(function () {
        var frmId = "Login";
        var url = frmId + "/" + frmId + "/login";
        var usr_id = $.trim($(".Login.txtUser").val());
        var usr_pass = $.trim($(".Login.txtPassword").val());
        if (usr_id == "") {
            $(".Login.ErrorInfo").html("ユーザーIDを入力してください。");
            $(".Login.ErrorInfo").show();
            $(".Login.ErrorInfo").width("300px");

            return;
        }
        if (usr_pass == "") {
            $(".Login.ErrorInfo").html("パスワードを入力してください。");
            $(".Login.ErrorInfo").show();
            $(".Login.ErrorInfo").width("300px");
            return;
        }
        var arrayVal = {
            usr_id: usr_id,
            pass: usr_pass,
        };
        me.data = {
            request: arrayVal,
        };
        // 20230523 wangying ins s
        const windowWidth = $(window).width();
        const loadingWidth = 200;
        const left = (windowWidth - loadingWidth) / 2;
        $.blockUI({
            css: {
                border: "none",
                padding: "10px",
                backgroundColor: "#fff",
                "-webkit-border-radius": "8px",
                "-moz-border-radius": "8px",
                left: left,
                color: "#000",
                width: "200px",
            },
            message: '<img src="img/1.gif" width="64" height="64" /><br />',
        });
        // 20230523 wangying ins e
        $.ajax({
            type: "POST",
            url: url,
            data: me.data,
            success: function (result) {
                result = $.parseJSON(result);

                if (result["result"] == true) {
                    if (result["loginInfo"] == "loginFail") {
                        $.unblockUI();
                        $(".Login.ErrorInfo").html(
                            "ユーザーID又はパスワードが間違っています。"
                        );
                        $(".Login.ErrorInfo").show();
                        $(".Login.ErrorInfo").width("360px");
                        return;
                    }

                    if (result["loginInfo"] == "loginSuc") {
                        url = "";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                func: "check_login_state",
                            },
                            success: function () {
                                location.reload();
                            },
                        });
                        return;
                    }
                } else {
                    // $(".Login.ErrorInfo").html('DB Connect ERROR');
                    // $(".Login.ErrorInfo").show();
                    // 20230523 wangying ins s
                    $.unblockUI();
                    // 20230523 wangying ins e
                    clsComFnc.FncMsgBox("E9999", result["ERRORData"]);
                    return;
                }
            },
            error: function () {
                // 20230523 wangying ins s
                $.unblockUI();
                // 20230523 wangying ins e
            },
        });
    });

    $(".Login.lblForgetAll").click(function () {
        $(".Login.ErrorInfo").hide();
        var frmId = "Login";
        var url = frmId + "/" + frmId + "/forgetAllStep";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: frmId,
            },
            success: function (result) {
                $("#LoginForgetAllStep_dialog").html(result);
                $(".LoginForgetAllStep.btnCancel").button();
                me.ForgetIdStep();
                me.ForgetPasswordStep();
                me.cancelDialog();
                $("#LoginForgetAllStep_dialog").dialog(
                    "option",
                    "title",
                    "ログインできない場合のお手続き"
                );
                $("#LoginForgetAllStep_dialog").dialog("open");
            },
        });
    });

    me.cancelDialog = function () {
        $(".LoginForgetAllStep.btnCancel").click(function () {
            $("#LoginForgetAllStep_dialog").dialog("close");
        });
        $(".LoginSendMailSuc.btnFinish").click(function () {
            $("#LoginSendMailSuc_dialog").dialog("close");
        });
        $(".LoginSendMailSuc.btnBack").click(function () {
            $("#LoginSendMailSuc_dialog").dialog("close");
            $("#LoginForgetAllStep_dialog").dialog("open");
        });
    };

    me.ForgetIdStep = function () {
        $(".LoginForgetAllStep.divChooseForgetID").click(function () {
            var frmId = "Login";
            var url = frmId + "/" + frmId + "/forgetIdStep";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    url: frmId,
                },
                success: function (result) {
                    $("#LoginForgetAllStep_dialog").dialog("close");
                    $("#LoginForgetIdStep_dialog").html(result);

                    $("LoginForgetIdStep.lblEmailFormatErrInfo").html("");
                    $(".LoginForgetIdStep.divEmailFormatErrInfo").hide();

                    // $('.LoginForgetIdStep.btnSendMail').button();
                    $(".LoginForgetIdStep.btnCancel").button();
                    $(".LoginForgetIdStep.btnBack").button();
                    $(".LoginForgetIdStep.btnSendMail").button({
                        text: true,
                        icon: " ui-icon-mail-open",
                    });
                    me.ForgetIdStepSendMail();
                    $("#LoginForgetIdStep_dialog").dialog(
                        "option",
                        "title",
                        "IDの取得"
                    );
                    $("#LoginForgetIdStep_dialog").dialog("open");
                },
            });
        });
    };

    me.ForgetIdStepSendMail = function () {
        $(".LoginForgetIdStep.btnCancel").click(function () {
            $("#LoginForgetIdStep_dialog").dialog("close");
        });

        $(".LoginForgetIdStep.btnBack").click(function () {
            $("#LoginForgetIdStep_dialog").dialog("close");

            $("#LoginForgetAllStep_dialog").dialog("open");
        });

        $(".LoginForgetIdStep.txtEmailAddress").focus(function () {
            $(".LoginForgetIdStep.divEmailFormatErrInfo").hide();
        });

        $(".LoginForgetIdStep.btnSendMail").click(function () {
            var emailAddressVal = $.trim(
                $(".LoginForgetIdStep.txtEmailAddress").val()
            );

            if (emailAddressVal == "") {
                $(".LoginForgetIdStep.lblEmailFormatErrInfo").html(
                    "eメールアドレスを入力してください。"
                );
                $(".LoginForgetIdStep.divEmailFormatErrInfo").show();
                return;
            }

            if (clsComFnc.mailMatch(emailAddressVal)) {
                $(".LoginForgetIdStep.btnSendMail").button("disable");
                $("#indexLoading")
                    .dialog("widget")
                    .find(".ui-dialog-titlebar")
                    .hide();
                $("#indexLoading").dialog("open");
                var frmId = "Login";
                var url = frmId + "/" + frmId + "/IDsendToEmail";
                var arrayVal = {
                    EmailAddress: emailAddressVal,
                };
                me.data = {
                    request: arrayVal,
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: me.data,
                    success: function (result) {
                        result = $.parseJSON(result);

                        if (result["result"] == false) {
                            //console.log('DB　连接　错误');
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                        if (result["mailResult"] == false) {
                            //console.log('mail　send fail');
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }

                        var frmId = "Login";
                        var url = frmId + "/" + frmId + "/sendMailSuc";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                url: frmId,
                            },
                            success: function (result) {
                                $("#LoginForgetIdStep_dialog").dialog("close");
                                $(
                                    ".LoginForgetIdStep.divEmailFormatErrInfo"
                                ).hide();
                                $("#LoginSendMailSuc_dialog").html(result);
                                $(".LoginSendMailSuc.btnFinish").button();
                                $(".LoginSendMailSuc.btnBack").button();
                                var message =
                                    "ユーザーIDは「" +
                                    emailAddressVal +
                                    "」へeメール送信成功です。";
                                $(".LoginSendMailSuc.sendMailSucInfo").html(
                                    message
                                );
                                $("#indexLoading").dialog("close");
                                $("#LoginSendMailSuc_dialog").dialog(
                                    "option",
                                    "title",
                                    "送信成功"
                                );
                                $("#LoginSendMailSuc_dialog").dialog("open");

                                me.cancelDialog();
                            },
                        });
                    },
                });
            } else {
                $(".LoginForgetIdStep.lblEmailFormatErrInfo").html(
                    "eメールアドレスの形式が正しく入力されているか確認してください。"
                );
                $(".LoginForgetIdStep.divEmailFormatErrInfo").show();
            }
        });
    };

    me.ForgetPasswordStep = function () {
        $(".LoginForgetAllStep.divChooseForgetPassword").click(function () {
            var frmId = "Login";
            var url = frmId + "/" + frmId + "/forgetPasswordStep";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    url: frmId,
                },
                success: function (result) {
                    $("#LoginForgetAllStep_dialog").dialog("close");
                    $("#LoginForgetPasswordStep_dialog").html(result);
                    // $('.LoginForgetPasswordStep.btnSendMail').button();
                    $(".LoginForgetPasswordStep.btnSendMail").button({
                        text: true,
                        icon: " ui-icon-mail-open",
                    });
                    // $('.LoginForgetPasswordStep.btnSendMailById').button();
                    $(".LoginForgetPasswordStep.btnSendMailById").button({
                        text: true,
                        icon: " ui-icon-mail-open",
                    });
                    $(".LoginForgetPasswordStep.btnCancel").button();
                    $(".LoginForgetPasswordStep.btnBack").button();
                    $(".LoginForgetPasswordStep.lblEmailFormatErrInfo").html(
                        ""
                    );
                    $(".LoginForgetPasswordStep.divEmailFormatErrInfo").hide();
                    $(".LoginForgetPasswordStep.lblIdErrInfo").html("");
                    $(".LoginForgetPasswordStep.divIdErrInfo").hide();
                    me.ForgetPasswordStepSendMail();
                    $("#LoginForgetPasswordStep_dialog").dialog(
                        "option",
                        "title",
                        "パスワードの取得"
                    );
                    $("#LoginForgetPasswordStep_dialog").dialog("open");
                },
            });
        });
    };
    me.ForgetPasswordStepSendMail = function () {
        $(".LoginForgetPasswordStep.btnCancel").click(function () {
            $("#LoginForgetPasswordStep_dialog").dialog("close");
        });

        $(".LoginForgetPasswordStep.txtId").focus(function () {
            $(".LoginForgetPasswordStep.divIdErrInfo").hide();
            $(".LoginForgetPasswordStep.divEmailFormatErrInfo").hide();
        });

        $(".LoginForgetPasswordStep.txtEmailAddress").focus(function () {
            $(".LoginForgetPasswordStep.divIdErrInfo").hide();
            $(".LoginForgetPasswordStep.divEmailFormatErrInfo").hide();
        });
        $(".LoginForgetPasswordStep.btnBack").click(function () {
            $("#LoginForgetPasswordStep_dialog").dialog("close");

            $("#LoginForgetAllStep_dialog").dialog("open");
        });

        $(".LoginForgetPasswordStep.btnSendMailById").click(function () {
            var usrId = $.trim($(".LoginForgetPasswordStep.txtId").val());
            if (usrId == "") {
                $(".LoginForgetPasswordStep.lblIdErrInfo").html(
                    "ユーザーID を入力してください。"
                );
                $(".LoginForgetPasswordStep.divIdErrInfo").show();
                return;
            }
            $(".LoginForgetPasswordStep.btnSendMailById").button("disable");
            $("#indexLoading")
                .dialog("widget")
                .find(".ui-dialog-titlebar")
                .hide();
            $("#indexLoading").dialog("open");
            var frmId = "Login";
            var url = frmId + "/" + frmId + "/PasswordsendToEmailById";
            var arrayVal = {
                Usr_Id: usrId,
            };
            me.data = {
                request: arrayVal,
            };
            $.ajax({
                type: "POST",
                url: url,
                data: me.data,
                success: function (result) {
                    result = $.parseJSON(result);

                    if (result["result"] == false) {
                        //console.log('DB　连接　错误');
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    if (result["mailResult"] == false) {
                        //console.log('mail　send fail');
                        clsComFnc.FncMsgBox("E9999", result["data"]);
                        return;
                    }
                    var frmId = "Login";
                    var url = frmId + "/" + frmId + "/sendMailSuc";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            url: frmId,
                        },
                        success: function (result) {
                            $("#LoginForgetPasswordStep_dialog").dialog(
                                "close"
                            );
                            $(".LoginForgetPasswordStep.divIdErrInfo").hide();
                            $(
                                ".LoginForgetPasswordStep.divEmailFormatErrInfo"
                            ).hide();
                            $("#LoginSendMailSuc_dialog").html(result);
                            $(".LoginSendMailSuc.btnFinish").button();
                            $(".LoginSendMailSuc.btnBack").button();
                            var message =
                                "パスワードはユーザー「" +
                                usrId +
                                "」 のメールボックスへeメール送信成功です。";
                            $(".LoginSendMailSuc.sendMailSucInfo").html(
                                message
                            );
                            $("#indexLoading").dialog("close");
                            $("#LoginSendMailSuc_dialog").dialog(
                                "option",
                                "title",
                                "送信成功"
                            );
                            $("#LoginSendMailSuc_dialog").dialog("open");

                            me.cancelDialog();
                        },
                    });
                },
            });
        });

        $(".LoginForgetPasswordStep.btnSendMail").click(function () {
            var emailAddressVal = $.trim(
                $(".LoginForgetPasswordStep.txtEmailAddress").val()
            );
            if (emailAddressVal == "") {
                $(".LoginForgetPasswordStep.lblEmailFormatErrInfo").html(
                    "eメールアドレスを入力してください。"
                );
                $(".LoginForgetPasswordStep.divEmailFormatErrInfo").show();
                return;
            }
            if (clsComFnc.mailMatch(emailAddressVal)) {
                $(".LoginForgetPasswordStep.btnSendMail").button("disable");
                $("#indexLoading")
                    .dialog("widget")
                    .find(".ui-dialog-titlebar")
                    .hide();
                $("#indexLoading").dialog("open");
                var frmId = "Login";
                var url = frmId + "/" + frmId + "/PasswordsendToEmail";
                var arrayVal = {
                    EmailAddress: emailAddressVal,
                };
                me.data = {
                    request: arrayVal,
                };
                $.ajax({
                    type: "POST",
                    url: url,
                    data: me.data,
                    success: function (result) {
                        result = $.parseJSON(result);

                        if (result["result"] == false) {
                            //console.log('DB　连接　错误');
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                        if (result["mailResult"] == false) {
                            //console.log('mail　send fail');
                            clsComFnc.FncMsgBox("E9999", result["data"]);
                            return;
                        }
                        var frmId = "Login";
                        var url = frmId + "/" + frmId + "/sendMailSuc";
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                url: frmId,
                            },
                            success: function (result) {
                                $("#LoginForgetPasswordStep_dialog").dialog(
                                    "close"
                                );
                                $(
                                    ".LoginForgetPasswordStep.divIdErrInfo"
                                ).hide();
                                $(
                                    ".LoginForgetPasswordStep.divEmailFormatErrInfo"
                                ).hide();
                                $("#LoginSendMailSuc_dialog").html(result);
                                $(".LoginSendMailSuc.btnFinish").button();
                                $(".LoginSendMailSuc.btnBack").button();
                                var message =
                                    "パスワードは「" +
                                    emailAddressVal +
                                    "」 へeメール送信成功です。";
                                $(".LoginSendMailSuc.sendMailSucInfo").html(
                                    message
                                );
                                $("#indexLoading").dialog("close");
                                $("#LoginSendMailSuc_dialog").dialog(
                                    "option",
                                    "title",
                                    "送信成功"
                                );
                                $("#LoginSendMailSuc_dialog").dialog("open");

                                me.cancelDialog();
                            },
                        });
                    },
                });
            } else {
                $(".LoginForgetPasswordStep.lblEmailFormatErrInfo").html(
                    "eメールアドレスの形式が正しく入力されているか確認してください。"
                );
                $(".LoginForgetPasswordStep.divEmailFormatErrInfo").show();
            }
        });
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMSS_Login = new HMSS.Login();
    o_HMSS_Login.load();

    o_HMSS_Master.Login = o_HMSS_Login;
    o_HMSS_Login.HMSS = o_HMSS_Master;
});
