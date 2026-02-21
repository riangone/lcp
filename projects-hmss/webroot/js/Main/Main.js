/**
 * Main
 * @alias  Main
 * @author FCSDL
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20210114			   add						   ログインパスワード変更機能の追加	    WL
 * 20210310           BUG                         ブラウザウインドウを最大化して画面表示すると、画面に不要な表示がされていた   WY
 * 20250423 20250512   BUG    セッションが期限切れの状態でシステムを切り替えた場合に      caina
 *                                         情報が混在する不具合の修正
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMSS.Main");
//20180516 YIN INS S
var relogin = "";
//20180516 YIN INS E
//20181026 YIN INS S
var downloadExcel = 0;
var ua = navigator.userAgent.toLowerCase();
if (ua.match(/msie/) != null || ua.match(/trident/) != null) {
    var downloadExcelflag = 3;
} else {
    var downloadExcelflag = 2;
}
//20181026 YIN INS E

HMSS.Main = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "（GD）（DZM）社内システム";

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "Main";
    me.sys_id = "HMSS";
    me.tabArr = new Array();
    //me.is_create_tab = '';
    me.HMSS = null;

    me.logined = false;

    me.selTabId = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //20180426 lqs INS S
    //右键
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

    window.onbeforeunload = onbeforeunload_handler;
    function onbeforeunload_handler() {
        if (relogin == "true") {
            relogin = "false";
            //20181026 YIN INS S
        } else if (downloadExcel < downloadExcelflag) {
            //20181026 YIN INS E
        } else {
            return "";
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
    //20180322 lqs INS S  禁止backspace回退返回页面
    $(document).keydown(function (e) {
        var keyEvent;
        if (e.keyCode == 8) {
            var d = e.srcElement || e.target;
            if (
                d.tagName.toUpperCase() == "INPUT" ||
                d.tagName.toUpperCase() == "TEXTAREA"
            ) {
                keyEvent = d.readOnly || d.disabled;
                if (
                    d.type.toUpperCase() == "CHECKBOX" ||
                    d.type.toUpperCase() == "RADIO"
                ) {
                    keyEvent = true;
                }
            } else {
                keyEvent = true;
            }
        } else {
            keyEvent = false;
        }
        if (keyEvent) {
            e.preventDefault();
        }
    });
    //20180322 lqs INS E

    //20210114 WL INS S
    $(".LoginedInfo").click(function () {
        me.Logineduser();
    });
    //20210114 WL INS E
    //20210310 WY INS S
    //comboSelect会在画面上出现多余的文字
    // $(".Main.tabClick").click(function () {
    //     if ($(".HMHRMS")) {
    //         $(".HMHRMS").comboSelect("dispose");
    //     }
    // });
    //20210310 WY INS E
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    me.controls.push({
        id: ".logout",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    var base_load = me.load;
    me.load = function () {
        base_load();
        //fan add s. Show the client number and name.
        url = "Login/Login/logineduser";
        $.ajax({
            type: "POST",
            url: url,
            success: function (result) {
                result = $.parseJSON(result);
                $(".LogineduserID").html(result["userid"]);
                //20210114 WL UPD S
                //$('.LogineduserName').html(result['username']);
                var data = result["username"]
                    .replace(/[<]/g, "&lt;")
                    .replace(/[>]/g, "&gt;")
                    .replace(/\r\n/g, "")
                    .replace(/\n/g, "")
                    .replace(/[\"]/g, "&quot;")
                    .replace(/[\']/g, "&apos;")
                    .replace(/[\r\n]/g, "<br>");
                $(".LogineduserName").html(data);
                //20210114 WL UPD E
            },
        });
        //fan add e

        var tabnum = $("#tabnum").val();
        var tabname = new Array();
        tabname = tabnum.split(",");
        for (i = 0; i < tabname.length; i++) {
            var t = document.getElementById("tabs_" + tabname[i]);
            if (t != null) {
                var pageLayout = $("body").layout(pageLayoutOptions);

                pageLayout.center.pane.tabs({
                    create: function (_event, ui) {
                        //diyici
                        //console.log("create");
                        //console.log(ui);

                        // tabのidを取得
                        var tab = "#" + ui.panel[0].id;
                        currentTabId = tab;
                        me.tabArr.push(tab);
                        //me.is_create_tab = tab;
                        me.load_tab(tab);
                        $(tab).css("visibility", "visible");
                    },
                    activate: function (_evt, ui) {
                        //console.log("activate");
                        //console.log(ui);

                        // tabのidを取得

                        var tab = "#" + ui.newPanel[0].id;
                        currentTabId = tab;
                        //if (tab != me.is_create_tab)
                        //{

                        if (me.tabArr.toString().match(tab) == null) {
                            me.tabArr.push(tab);
                            me.load_tab(tab);
                        }
                        // 20250423 caina ins s
                        else {
                            const tabSuffix = currentTabId.split("_")[1];
                            if (
                                tabSuffix == "HDKAIKEI" ||
                                tabSuffix == "HMDPS" ||
                                tabSuffix == "HMTVE" ||
                                tabSuffix == "PPRM" ||
                                tabSuffix == "HMAUD" ||
                                tabSuffix == "R4K"
                            ) {
                                getSession(tabSuffix);
                            }
                        }
                        // 20250423 caina ins e
                        $(tab).css("visibility", "visible");

                        //}
                    },
                    load: function () {
                        // console.log("load");
                        // console.log(ui);
                    },
                    beforeActivate: function (_event, ui) {
                        // console.log("beforeActivate");
                        // console.log(ui);
                        // 20250512 caina ins s
                        lastTabId = "#" + ui.oldPanel[0].id;
                        // 20250512 caina ins e
                    },
                });

                pageLayout.center.children.tabsContainerLayout.resizeAll();
            }
        }
    };

    me.load_tab = function (tab) {
        var id = tab.replace("#tabs_", "");

        var url = id + "/" + id;

        // zhenghuiyun sdh add s
        if (id == "SDH") {
            url = id + "/" + "SDH01";
        }
        if (id == "ck_chkzaiko") {
            url = "CkChkzaiko" + "/" + "CkChkzaiko";
        }
        // zhenghuiyun sdh add e
        //----20141209 NO.54 fanzhengzhou upd s. For session out time when change tab.
        // $.ajax(
        // {
        // type : "POST",
        // url : url,
        // data :
        // {
        // "url" : url
        // },
        // success : function(result)
        // {
        // if (id == 'R4')
        // {
        // $(".Main.Main-" + id).html(result);
        //
        // $(tab).layout(tabLayoutOptions);
        // }
        // else
        // {
        // $(".Main.Main-" + id).html(result);
        //
        // }
        // }
        // });
        LoadTabFlag = true;
        var o_ajax = new gdmz.common.ajax();
        o_ajax.receive = function (result) {
            LoadTabFlag = false;
            //---20161128 li UPD S.
            // if (id == 'R4G' || id == 'R4K' || id == 'KRSS'||id == 'HIROAPP')
            //---20170710 li UPD S.
            // if (id == 'R4G' || id == 'R4K' || id == 'KRSS'||id == 'HIROAPP' ||id == 'PPR')
            //---20180418 yuan UPD S.
            //if (id == 'R4G' || id == 'R4K' || id == 'KRSS' || id == 'HIROAPP' || id == 'PPRM' || id == 'APPM')
            //20210114 WL UPD S
            //if (id == 'R4G' || id == 'R4K' || id == 'KRSS' || id == 'HIROAPP' || id == 'PPRM' || id == 'APPM' || id == 'JKSYS')
            // 20220617 YIN UPD S
            // if (id == 'R4G' || id == 'R4K' || id == 'KRSS' || id == 'HIROAPP' || id == 'PPRM' || id == 'APPM' || id == 'JKSYS' || id == 'HMHRMS' || id == 'HMDPS' || id == 'HMTVE')
            if (
                id == "R4G" ||
                id == "R4K" ||
                id == "KRSS" ||
                id == "HIROAPP" ||
                id == "PPRM" ||
                id == "APPM" ||
                id == "JKSYS" ||
                id == "HMHRMS" ||
                id == "HMDPS" ||
                id == "HMTVE" ||
                id == "HMAUD" ||
                id == "HDKAIKEI"
            ) {
                // 20220617 YIN UPD E
                //20210114 WL UPD E
                //---20170710 li UPD E.
                //---20161128 li UPD E.
                //---20180418 yuan UPD E.
                $(".Main.Main-" + id).html(result);
                if (id == "KRSS") {
                    $(tab).layout(tabLayoutOptionsKrss);
                    //課題管理表 002 li 20150702
                }
                //20220914 lujunxia ins s
                //内部統制 指摘事項NO55:メニュー開閉の幅を広げる
                else if (id == "HMAUD") {
                    $(tab).layout(tabLayoutOptionsHMAUD);
                }
                //20220914 lujunxia ins e
                else {
                    $(tab).layout(tabLayoutOptions);
                }
            } else {
                $(".Main.Main-" + id).html(result);
            }
        };
        o_ajax.send(url, "", 0);
        //----20141209 NO.54 fanzhengzhou upd e.
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

    //20210114 WL INS S
    //隠し内容を展開
    $(".lbluserInformation").click(function () {
        var lblusershow = $(this).next($(".lblusershow")).css("display");
        if (lblusershow == "block") {
            $(this).next($(".lblusershow")).slideDown(600);
        } else {
            $(this).next($(".lblusershow")).slideUp(600);
        }
    });

    $(".lbluserPassword").click(function () {
        var lbluserhide = $(this).next($(".lbluserhide")).css("display");
        if (lbluserhide == "block") {
            $(this).next($(".lbluserhide")).slideDown(600);
        } else {
            $(this).next($(".lbluserhide")).slideUp(600);
        }
    });

    me.Logineduser = function () {
        $(".div_personinfo").dialog({
            width: 850,
            resizable: false,
            height: 610,
            autoOpen: false,
            modal: true,
            close: function () {
                var lblusershow = $(".lblusershow").css("display");
                if (lblusershow == "none") {
                    $(".lbluserInformation").click();
                }
                var lblusershow = $(".lbluserhide").css("display");
                if (lblusershow == "none") {
                    $(".lbluserPassword").click();
                }
            },
        });
        $(".div_personinfo").dialog("option", "title", "ユーザー情報設定");
        $(".div_personinfo").dialog("open");
        me.personinfo();
        me.userload();
    };

    me.personinfo = function () {
        $("#txtOldPs").val("");
        $("#txtNewPs").val("");
        $("#txtNewConfirmationPs").val("");

        var url = me.id + "/" + me.id + "/" + "FunLoadData";

        var data = {
            USR_ID: $(".LogineduserID").html(),
        };
        var o_ajax = new gdmz.common.ajax();
        o_ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".lbluserID").html(result["data"]["USR_ID"]);
                $(".txtUserName").val(result["data"]["USR_NAME"]);
                $(".txtUserMail").val(result["data"]["email"]);
                $(".txtUserPosition").html(result["data"]["POSITION"]);
            } else {
                clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        o_ajax.send(url, data, 0);
    };

    me.userload = function () {
        $("#div_userInformation").accordion({
            collapsible: true,
        });
        $("#div_changePassword").accordion({
            collapsible: true,
        });
        $("#btn_saveInfo").hide();
        $("#divOldPS").hide();
        $("#divNewPS").hide();
        $("#divNewConfirmationPS").hide();
        $("#btn_savePS").hide();
        $("#divNameformat").hide();
        $("#divMailformat").hide();
    };

    $(".txtUserName").focus();

    $("#div_userInformation").click(function () {
        $("#btn_saveInfo").show();
    });

    $("#div_changePassword").click(function () {
        $("#btn_savePS").show();
    });

    $(".txtUserName").mousedown(function () {
        $("#divNameformat").hide();
    });

    $(".txtUserMail").mousedown(function () {
        $("#divMailformat").hide();
    });
    $("#btn_saveInfo").click(function () {
        // var lblIdVal = $(".lbluserID").text();
        var nameVal = $(".txtUserName").val();
        var mailVal = $(".txtUserMail").val();
        var legName = nameVal.replace(/[^x00-xFF]/g, "**").length;
        if (legName == 0) {
            lableShow(
                "nameformatWrong",
                "ユーザー名を入力してください。",
                "divNameformat"
            );
        } else if (legName > 12) {
            lableShow(
                "nameformatWrong",
                "名前の入力可能文字数を超えています。",
                "divNameformat"
            );
        } else {
            var legMail = mailVal.length;

            if (
                mailVal.search(
                    /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
                ) != -1 &&
                legMail <= 45
            ) {
                var flg = true;
            } else {
                var flg = false;
            }

            if (flg == true) {
                var url = me.id + "/" + me.id + "/" + "FunUserUpd";

                var data = {
                    USR_ID: $(".LogineduserID").html(),
                    USR_NAME: $(".txtUserName").val(),
                    email: $(".txtUserMail").val(),
                };
                var o_ajax = new gdmz.common.ajax();
                o_ajax.receive = function (result) {
                    var result = eval("(" + result + ")");
                    if (result["result"] == true) {
                        $("#divMailformat").hide();
                        $("#divNameformat").hide();
                        $("#btn_saveInfo").hide();
                        var data = $(".txtUserName")
                            .val()
                            .replace(/[<]/g, "&lt;")
                            .replace(/[>]/g, "&gt;")
                            .replace(/\r\n/g, "")
                            .replace(/\n/g, "")
                            .replace(/[\"]/g, "&quot;")
                            .replace(/[\']/g, "&apos;")
                            .replace(/[\r\n]/g, "<br>");
                        $(".LogineduserName").html(data);
                        clsComFnc.MsgBoxBtnFnc.Close = function () {
                            $(".div_personinfo").dialog("close");
                        };
                        clsComFnc.FncMsgBox(
                            "I9999",
                            "ユーザー情報を更新しました"
                        );
                    } else {
                        clsComFnc.FncMsgBox("E9999", result["error"]);
                        return;
                    }
                };
                o_ajax.send(url, data, 0);
            } else {
                lableShow(
                    "emailformatWrong",
                    "イーメールアドレスの形式が正しく入力されているか確認してください。",
                    "divMailformat"
                );
            }
        }
    });

    $("#txtOldPs").focus(function () {
        $("#divOldPS").hide();
    });

    $("#txtOldPs").blur(function () {
        var str = $("#txtOldPs").val();
        if (str == "" || str == " ") {
            $("#divOldPS").css("width", "425px");
            $("#divOldPS").show();
            $("#lblOldPs").html("古いパスワードを入力してください");
            return;
        }

        if (/^([a-zA-z_0-9]{1})([\w]*)$/g.test(str) == false) {
            $("#divOldPS").css("width", "425px");
            $("#divOldPS").show();
            $("#lblOldPs").html(
                "the password format is only English,figure,.,_~,please input again"
            );
            return;
        }
    });

    $("#txtNewPs").focus(function () {
        $("#divNewPS").hide();
    });

    $("#txtNewPs").blur(function () {
        var str = $("#txtNewPs").val();
        var leg = str.length;
        if (str == "" || str == " ") {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("新しいパスワードを入力してください");
            return;
        }

        if (leg < 6) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("6文字以上入力してください");
            return;
        }

        if (leg > 8) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("パスワードは8文字以内で入力してください");
            return;
        }

        if (/^([a-zA-z_0-9]{1})([\w]*)$/g.test(str) == false) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html(
                "the password format is only English,figure,.,_~,please input again"
            );
            return;
        }
    });

    $("#txtNewConfirmationPs").focus(function () {
        $("#divNewConfirmationPS").hide();
    });

    $("#txtNewConfirmationPs").blur(function () {
        var str = $("#txtNewConfirmationPs").val();
        var newPs = $("#txtNewPs").val();
        var strleg = $("#txtNewPs").val();
        var leg = strleg.length;
        if (str == "" || str == " ") {
            $("#divNewConfirmationPS").css("width", "425px");
            $("#divNewConfirmationPS").show();
            $("#lblNewConfirmationPs").html(
                "もう一度新しいパスワードを入力してください"
            );
            return;
        }

        if (/^([a-zA-z_0-9]{1})([\w]*)$/g.test(str) == false) {
            $("#divNewConfirmationPS").css("width", "425px");
            $("#divNewConfirmationPS").show();
            $("#lblNewConfirmationPs").html(
                "the password format is only English,figure,.,_~,please input again"
            );
            return;
        }

        if (leg >= 6 && leg <= 8) {
            if (newPs != str) {
                $("#divNewConfirmationPS").css("width", "425px");
                $("#divNewConfirmationPS").show();
                $("#lblNewConfirmationPs").html(
                    "確認用パスワードが一致しません，再度入力してください"
                );
                return;
            }
        }
    });

    $("#btn_savePS").click(function () {
        var oldPs = $("#txtOldPs").val();
        var newPs = $("#txtNewPs").val();
        var newConfirmationPs = $("#txtNewConfirmationPs").val();

        var leg = newPs.length;

        if (oldPs == "" || oldPs == " ") {
            $("#divOldPS").css("width", "425px");
            $("#divOldPS").show();
            $("#lblOldPs").html("古いパスワードを入力してください");
            return;
        }

        if (newPs == "" || newPs == " ") {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("新しいパスワードを入力してください");
            return;
        }

        if (leg < 6) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("6文字以上入力してください");
            return;
        }

        if (leg > 8) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("パスワードは8文字以内で入力してください");
            return;
        }

        if (/^([a-zA-z_0-9]{1})([\w]*)$/g.test(oldPs) == false) {
            $("#divOldPS").css("width", "425px");
            $("#divOldPS").show();
            $("#lblOldPs").html(
                "the password format is only English,figure,.,_~,please input again"
            );
            return;
        }

        if (/^([a-zA-z_0-9]{1})([\w]*)$/g.test(newPs) == false) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html(
                "the password format is only English,figure,.,_~,please input again"
            );
            return;
        }

        if (newConfirmationPs == "" || newConfirmationPs == " ") {
            $("#divNewConfirmationPS").css("width", "425px");
            $("#divNewConfirmationPS").show();
            $("#lblNewConfirmationPs").html(
                "もう一度新しいパスワードを入力してください"
            );
            return;
        }

        if (newConfirmationPs != newPs) {
            $("#divNewConfirmationPS").css("width", "425px");
            $("#divNewConfirmationPS").show();
            $("#lblNewConfirmationPs").html(
                "確認用パスワードが一致しません，再度入力してください"
            );
            return;
        }

        if (oldPs == newPs) {
            $("#divNewPS").css("width", "425px");
            $("#divNewPS").show();
            $("#lblNewPs").html("同じパスワードは使用できません。");

            return;
        }

        if (oldPs != newPs) {
            $("#divNewPS").hide();
        }

        if (newConfirmationPs == newPs) {
            $("#divNewConfirmationPS").hide();
        }

        var url = me.id + "/" + me.id + "/" + "FunPassUpd";

        var data = {
            USR_ID: $(".LogineduserID").html(),
            OldPASS: $("#txtOldPs").val(),
            NewPASS: $("#txtNewPs").val(),
        };

        var o_ajax = new gdmz.common.ajax();
        o_ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (result["result"] == true) {
                $("#divNewPS").hide();
                $("#divNewConfirmationPS").hide();
                $("#divOldPS").hide();
                clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".div_personinfo").dialog("close");
                };
                clsComFnc.FncMsgBox("I9999", "パスワードを更新しました");
            } else {
                if (
                    result["error"] ==
                    "古いパスワードが間違っています，入力し直してください"
                ) {
                    clsComFnc.FncMsgBox("W9999", result["error"]);
                    return;
                } else {
                    clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
            }
        };
        o_ajax.send(url, data, 0);
    });

    function lableShow(name, content, divID) {
        var name = "#" + name;
        $(name).html(content);
        var divID = "#" + divID;
        $(divID).show();
    }

    //20210114 WL INS E

    return me;
};

$(".logout").click(function () {
    //20180515 YIN INS S
    relogin = "true";
    //20180515 YIN INS E
    var url = "Login/Login/loginout";
    $.ajax({
        type: "POST",
        url: url,
        success: function (result) {
            result = $.parseJSON(result);
            if (result["result"] == "false") {
                alert("loginoutfail");
            } else {
                var frmId = "Login";
                var url = "Login" + "/" + frmId;
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        url: url,
                    },
                    success: function (result) {
                        $("body").html(result);
                    },
                });
            }
        },
    });
});
// 20250423 caina ins s
function getSession(tabSuffix) {
    var o_ajax = new gdmz.common.ajax();
    var url = tabSuffix + "/Frm" + tabSuffix + "MainMenu/getSession";

    var data = {
        request: {},
    };

    o_ajax.receive = function (result) {
        result = eval("(" + result + ")");
        if (result["result"] != false && result["data"]) {
            Object.entries(result["data"]).forEach(([key, val]) => {
                if (val !== undefined && val !== null) {
                    gdmz["Session" + key] = val;
                }
            });
        }
    };
    o_ajax.send(url, data, 0);
}
// 20250423 caina ins e
var currentTabId = "";
// 20250512 caina ins s
var lastTabId = "";
// 20250512 caina ins e
//----20141209 NO.54 fanzhengzhou ins s. For session out time when change tab.
var LoadTabFlag = false;
//----20141209 NO.54 fanzhengzhou ins e.
$(function () {
    var o_HMSS_Main = new HMSS.Main();
    o_HMSS_Main.load();

    o_HMSS_Master.Main = o_HMSS_Main;
    o_HMSS_Main.HMSS = o_HMSS_Master;
});
