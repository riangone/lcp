/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20250512           BUG    セッションが期限切れの状態でシステムを切り替えた場合に      caina
 *                                         情報が混在する不具合の修正
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.Relogin");

R4.Relogin = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    //me.clsComFnc = new gdmz.common.clsComFnc();
    me.id = "Relogin";
    me.sys_id = "R4";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
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

    $("#sessionoutdate").dialog({
        title: "ログイン",
        autoOpen: false,
        width: 470,
        resizable: false,
        height: 250,
        modal: true,
        closeOnEscape: false,
        classes: {
            "ui-dialog": "closeHide",
        },
        open: function () {
            $(".closeHide .ui-dialog-titlebar-close").hide();
        },
        buttons: {
            ログイン: function () {
                var frmId = "Login";
                var url = frmId + "/" + frmId + "/login";
                var usr_id = $.trim($("#sessionoutuser").val());
                var usr_pass = $.trim($("#sessionoutpassword").val());
                if (usr_pass == "") {
                    $("#reloginErrMessage").html(
                        "パスワードを入力してください。"
                    );
                    $("#reloginErrMessage").width("210px");
                    return;
                }
                //20211122 ZHANGBOWEN UPD S
                // var arrayVal =
                // {
                // "usr_id" : usr_id,
                // "pass" : usr_pass
                // };
                var tabId = currentTabId.replace("#tabs_", "");
                var arrayVal = {
                    usr_id: usr_id,
                    pass: usr_pass,
                    tabId: tabId,
                };
                //20211122 ZHANGBOWEN UPD E
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
                            $("#reloginErrMessage").html(result["ERRORData"]);
                        } else {
                            if (result["result"] == true) {
                                if (result["loginInfo"] == "loginSuc") {
                                    // 20250512 caina ins s
                                    const tabSuffix =
                                        currentTabId.split("_")[1];
                                    if (
                                        (tabSuffix == "HDKAIKEI" ||
                                            tabSuffix == "HMDPS" ||
                                            tabSuffix == "HMTVE" ||
                                            tabSuffix == "PPRM" ||
                                            tabSuffix == "HMAUD" ||
                                            tabSuffix == "R4K") &&
                                        currentTabId !== lastTabId
                                    ) {
                                        var o_ajax = new gdmz.common.ajax();
                                        var url =
                                            tabSuffix +
                                            "/Frm" +
                                            tabSuffix +
                                            "MainMenu/getSession";

                                        var data = {
                                            request: {},
                                        };

                                        o_ajax.receive = function (result) {
                                            result = eval("(" + result + ")");
                                            if (
                                                result["result"] != false &&
                                                result["data"]
                                            ) {
                                                Object.entries(
                                                    result["data"]
                                                ).forEach(([key, val]) => {
                                                    if (
                                                        val !== undefined &&
                                                        val !== null
                                                    ) {
                                                        gdmz[
                                                            "Session" + key
                                                        ] = val;
                                                    }
                                                });
                                            }
                                            loadPage();
                                        };
                                        o_ajax.send(url, data, 0);
                                        lastTabId = currentTabId;
                                    } else {
                                        loadPage();
                                    }
                                    // 20250512 caina ins e
                                } else {
                                    if (result["loginInfo"] == "loginFail") {
                                        $("#reloginErrMessage").html(
                                            "ユーザー名又はパスワードが間違っています。"
                                        );
                                        $("#reloginErrMessage").width("300px");
                                    }
                                }
                            }
                        }
                    },
                });
            },
            リセット: function () {
                $("#sessionoutpassword").val("");
                $("#reloginErrMessage").html("");
                $("#sessionoutpassword").focus();
            },
        },
    });
    // 20250512 caina upd s
    function loadPage() {
        $("#sessionoutdate").dialog("close");
        $("#sessionoutuser").val("");
        $("#sessionoutpassword").val("");
        $("#reloginErrMessage").html("");
        //20141205 fanzhengzhou upd s. For session outdate when change tab.
        //---20161128 li UPD S.
        // if ((currentTabId == "#tabs_R4G" || currentTabId == "#tabs_R4K" || currentTabId == "#tabs_KRSS") && LoadTabFlag == false)
        //---20170710 li UPD S.
        // if ((currentTabId == "#tabs_R4G" || currentTabId == "#tabs_R4K" || currentTabId == "#tabs_KRSS" || currentTabId == "#tabs_PPR") && LoadTabFlag == false)
        //20211122 ZHANGBOWEN UPD S
        // if ((currentTabId == "#tabs_R4G" || currentTabId == "#tabs_R4K" || currentTabId == "#tabs_KRSS") && LoadTabFlag == false)
        //20220617 YIN UPD S
        // if ((currentTabId == "#tabs_R4G" || currentTabId == "#tabs_R4K" || currentTabId == "#tabs_KRSS" || currentTabId == "#tabs_HMTVE" || currentTabId == "#tabs_HMDPS" || currentTabId == "#tabs_JKSYS") && LoadTabFlag == false)
        if (
            (currentTabId == "#tabs_R4G" ||
                currentTabId == "#tabs_R4K" ||
                currentTabId == "#tabs_KRSS" ||
                currentTabId == "#tabs_HMTVE" ||
                currentTabId == "#tabs_HMDPS" ||
                currentTabId == "#tabs_JKSYS" ||
                currentTabId == "#tabs_HMAUD" ||
                currentTabId == "#tabs_HDKAIKEI" ||
                // 20240823 lhb INS S
                currentTabId == "#tabs_PPRM") &&
            // 20240823 lhb INS e
            LoadTabFlag == false
        ) {
            //20220617 YIN UPD E
            //20211122 ZHANGBOWEN UPD E
            //---20170710 li UPD E.
            //---20161128 li UPD E.
            var id = currentTabId.replace("#tabs_", "");
            //20240606 caina upd s
            // var nodeid = $(
            //     ".Frm" + id + "MainMenu.Menu"
            // )
            //     .jstree("get_selected")
            //     .prop("id");
            var nodeid = $(".Frm" + id + "MainMenu.Menu").jstree(
                "get_selected"
            );
            //20240606 caina upd e
            var nodetxt = $(
                ".Frm" + id + "MainMenu.Menu #" + nodeid + " a"
            ).text();
            var currentFrmID = $(
                ".ui-widget-header.ui-corner-top." + id + "-ContentBar"
            ).prop("id");
            // console.log("*******");
            // console.log("nodeid:" + nodeid);
            // console.log("currentFrmID:" + currentFrmID);
            // console.log("*******");
            var currentFrmIDarr = currentFrmID.split("_");
            currentFrmID = currentFrmIDarr[1];
            var url = nodeid + "/index";
            if (nodeid != "" && nodeid != null && nodeid != "undefined") {
                if (
                    currentTabId == "#tabs_HMTVE" ||
                    currentTabId == "#tabs_HMDPS" ||
                    currentTabId == "#tabs_JKSYS" ||
                    currentTabId == "#tabs_HMAUD" ||
                    currentTabId == "#tabs_HDKAIKEI"
                ) {
                    var els = $(
                        ".ui-dialog.ui-widget.ui-widget-content.ui-corner-all"
                    );
                    if (els.length > 0) {
                        //20250208 lujunxia upd s
                        // for (
                        //     var j1 = els.length;
                        //     j1 != 0;
                        //     j1--
                        // ) {
                        for (var j1 = els.length - 1; j1 >= 0; j1--) {
                            //20250208 lujunxia upd e
                            if (
                                $(els[j1]).prop("aria-describedby") !=
                                    "SDH_session_outdate" &&
                                $(els[j1]).prop("aria-describedby") !=
                                    "sessionoutdate"
                            ) {
                                $(
                                    "#" + $(els[j1]).attr("aria-describedby")
                                ).dialog("close");
                            }
                        }
                    }

                    me.getHtml(nodeid, nodetxt, url);
                } else if (currentFrmID != nodeid) {
                    me.getHtml(nodeid, nodetxt, url);
                } else if (
                    currentFrmID == "frmBillSitoInput" &&
                    nodeid == "frmBillSitoInput"
                ) {
                    $(".FrmBillSitoInput.txtCMN_NO").focus();
                }
            }
        } else if (currentTabId == "CkChkzaiko" && LoadTabFlag == false) {
            var nodeid = currentTabId;
            var nodetxt = "";
            var url = nodeid + "/index";
            me.getHtml(nodeid, nodetxt, url);
        } else if (LoadTabFlag == true) {
            me.load_tab(currentTabId);
        }

        LoadTabFlag = false;
        //20141205 fanzhengzhou upd e.
    }
    // 20250512 caina upd e
    $("#sessionoutpassword").focus(function () {
        $("#reloginErrMessage").html("");
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //----------------------20141205 fanzhengzhou upd s. For session outdate when change tab.--------------

    // me.getHtml = function(frmID, frmNM)
    // {
    // //url = "R4K" + "/" + frmID;
    // var r4name = $('#r4name').val();
    // if (r4name == '管理会計システム')
    // {
    // url = "R4K" + "/" + frmID;
    // }
    // else
    // if (r4name == '車両業務システム')
    // {
    // url = "R4G" + "/" + frmID;
    // }
    // else
    // {
    // url = "R4G" + "/" + frmID;
    // }
    //
    // $(".R4.R4-layout-center").html("");
    //
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
    //
    // $(".R4.R4-layout-center").html(result);
    //
    // frmID == "frmList" ? frmNM = "新車:付属品/特別仕様" : frmNM;
    // frmID == "frmTRKDownLoadFS" ? frmNM = "登録予定ダウンロード" : frmNM;
    // frmID == "frmKasouDownLoadFS" ? frmNM += "ダウンロード" : frmNM;
    // $(".ui-widget-header.ui-corner-top.R4-ContentBar").html(frmNM);
    // $(".ui-widget-header.ui-corner-top.R4-ContentBar").prop("id", "mainTtl_" + frmID);
    // me.blnFlag = true;
    //
    // me.currentFrmID = frmID;
    //
    // $(".R4.R4-loading-icon").hide();
    // }
    // });
    // };
    me.getHtml = function (frmID, frmNM, url) {
        if (currentTabId == "CkChkzaiko") {
            var id = currentTabId;
        } else {
            var id = currentTabId.replace("#tabs_", "");
        }
        url = id + "/" + frmID;

        $("." + id + "." + id + "-layout-center").html("");

        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                if (result == '{"result":true,"data":"session is outdate"}') {
                    $("#sessionoutdate").dialog("open");
                    client = $(".LogineduserID").html();
                    $("#sessionoutuser").val(client);
                    $("#sessionoutpassword").focus();
                    me.blnFlag = true;
                } else {
                    if (currentTabId == "CkChkzaiko") {
                        $(".Main.Main-ck_chkzaiko").html(result);
                    } else {
                        $("." + id + "." + id + "-layout-center").html(result);
                    }
                    frmID == "frmList"
                        ? (frmNM = "新車:付属品/特別仕様")
                        : frmNM;
                    frmID == "frmTRKDownLoadFS"
                        ? (frmNM = "登録予定ダウンロード")
                        : frmNM;
                    frmID == "frmKasouDownLoadFS"
                        ? (frmNM += "ダウンロード")
                        : frmNM;
                    $(
                        ".ui-widget-header.ui-corner-top." + id + "-ContentBar"
                    ).html(frmNM);
                    $(
                        ".ui-widget-header.ui-corner-top." + id + "-ContentBar"
                    ).prop("id", "mainTtl_" + frmID);
                    if (currentTabId !== "CkChkzaiko") {
                        $("." + id + "." + id + "-loading-icon").hide();
                    }
                }
                me.blnFlag = true;
                me.currentFrmID = frmID;
            },
        });
    };

    me.load_tab = function (tab) {
        var id = tab.replace("#tabs_", "");

        var url = id + "/" + id;

        if (id == "SDH") {
            url = id + "/" + "SDH01";
        }
        var ajax = new gdmz.common.ajax();
        ajax.receive = function (result) {
            //---20161128 li UPD S.
            // if (id == 'R4G' || id == 'R4K' || id == 'KRSS')
            //20211116 lqs UPD S
            // if (id == 'R4G' || id == 'R4K' || id == 'KRSS')
            // 20220916 lujunxia upd s
            //ログイン後（[内部統制]は一度もロードされていない）、セッションが切れるのを待ってからシステムを[内部統制]に切り替えると、画面表示が正しくない問題
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
                // 20220916 lujunxia upd e
                //20211116 lqs UPD E
                //---20161128 li UPD E.
                $(".Main.Main-" + id).html(result);
                //20220916 lujunxia ins s
                if (id == "HMAUD") {
                    //内部統制 指摘事項NO55:メニュー開閉の幅を広げる
                    $(tab).layout(tabLayoutOptionsHMAUD);
                }
                //20220916 lujunxia ins e
                //課題管理表 002 li 20150702	S
                else if (id == "KRSS") {
                    $(tab).layout(tabLayoutOptionsKrss);
                } else {
                    $(tab).layout(tabLayoutOptions);
                }
                //課題管理表 002 li 20150702	E
            } else {
                $(".Main.Main-" + id).html(result);
            }
        };
        ajax.send(url, "", 0);
    };
    //----------------------20141205 fanzhengzhou upd e.-------------------------------
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_Relogin = new R4.Relogin();
    o_R4_Relogin.load();
});
