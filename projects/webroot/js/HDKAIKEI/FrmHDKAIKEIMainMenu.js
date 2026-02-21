Namespace.register("HDKAIKEI.FrmHDKAIKEIMainMenu");

HDKAIKEI.FrmHDKAIKEIMainMenu = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計";

    me.ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.blnFlag = false;
    me.currentFrmID = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

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

    var base_load = me.load;
    me.load = function () {
        base_load();

        $(".HDKAIKEI.HDKAIKEI-loading-icon").show();
        $(".HDKAIKEI.HDKAIKEI-layout-center fieldset").hide();

        $(".FrmHDKAIKEIMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "HDKAIKEI/FrmHDKAIKEIMainMenu/menuHDKAIKEI",
                        dataType: "json",
                    },
                    themes: {
                        variant: "small",
                        stripes: true,
                    },
                },
                //20240606 caina upd e
            })
            .bind("loaded.jstree", function () {
                //20240606 caina upd s
                $(this).jstree("open_all");
                //20240606 caina upd e
                getSession();
            })
            .bind("select_node.jstree", function (_event, data) {
                //20240606 caina upd s
                var nodeID;
                if (isNaN(parseInt(data.node.id))) {
                    nodeID = data.node.id;
                }
                //20240606 caina upd e

                var frmNM = $("#" + nodeID).text();
                var url = nodeID + "/index";

                var currentContent = $(
                    ".ui-widget-header.ui-corner-top.HDKAIKEI-ContentBar"
                ).prop("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            console.log(me.currentFrmID);
                            me.clsComFnc.MsgBoxBtnFnc.Yes = function () {
                                //20200910 lqs ins S
                                // 不需要纵向滚动条的画面，样式还原(需要滚动条的在自己的画面设置样式)
                                $(".HDKAIKEI.HDKAIKEI-layout-center").css(
                                    "overflow-y",
                                    "hidden"
                                );
                                $(".HDKAIKEI-content-fixed-width").css(
                                    "width",
                                    "1113px"
                                );
                                //20200910 lqs ins E
                                shortcut.remove("F9");
                                shortcut.remove("F5");
                                me.getHtml(nodeID, frmNM, url);
                            };

                            me.clsComFnc.MsgBoxBtnFnc.No = function () {
                                $(".FrmHDKAIKEIMainMenu.Menu").jstree(
                                    "deselect_node",
                                    "#" + nodeID
                                );
                                $(".FrmHDKAIKEIMainMenu.Menu").jstree(
                                    "select_node",
                                    "#" + me.currentFrmID
                                );
                            };
                            //20131031 zhenghuiyun update start
                            //clsComFnc.MessageBox("別の画面へ移動すると保存していないデータは失われます移動してよろしいですか？", "", "YesNo", "Information");
                            var msg =
                                "別の画面へ移動すると保存していないデータは失われます。<br/>移動してよろしいですか？";
                            me.clsComFnc.MessageBox(
                                msg,
                                "伝票集計システム",
                                "YesNo",
                                "Information"
                            );
                            //20131031 zhenghuiyun update end
                        }
                    } else {
                        console.log(nodeID, frmNM, url);
                        me.getHtml(nodeID, frmNM, url);
                    }
                }
            });
    };

    function getSession() {
        var url = "HDKAIKEI/FrmHDKAIKEIMainMenu/getSession";

        var data = {
            request: {},
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] != false) {
                gdmz.SessionPatternID = result["data"]["PatternID"];
            }
        };
        me.ajax.send(url, data, 0);
    }

    me.getHtml = function (frmID, frmNM, url) {
        console.log(frmID);

        url = "HDKAIKEI" + "/" + frmID;

        $(".HDKAIKEI.HDKAIKEI-layout-center").html("");

        $.ajax({
            type: "POST",
            url: "HDKAIKEI" + "/FrmHDKAIKEIMainMenu/FncChkUserAuthority",
            data: {
                data: {
                    PRO_ID: frmID.substring(0, 3) + "_" + frmID.substring(3),
                },
            },
            success: function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    if (result["data"] == "session is outdate") {
                        $("#sessionoutdate").dialog("open");
                        client = $(".LogineduserID").html();
                        $("#sessionoutuser").val(client);
                        $("#sessionoutpassword").trigger("focus");
                        me.blnFlag = true;
                    } else if (result["data"][0]["COUNT(*)"] == "0") {
                        me.clsComFnc.FncMsgBox(
                            "W9999",
                            "アクセス権限がありません。"
                        );
                    } else {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: {
                                url: url,
                            },
                            success: function (res) {
                                $(".HDKAIKEI.HDKAIKEI-layout-center").html(res);
                                $(
                                    ".ui-widget-header.ui-corner-top.HDKAIKEI-ContentBar"
                                ).html(frmNM);
                                $(
                                    ".ui-widget-header.ui-corner-top.HDKAIKEI-ContentBar"
                                ).prop("id", "mainTtl_" + frmID);

                                $(".HDKAIKEI.HDKAIKEI-loading-icon").hide();
                                me.blnFlag = true;
                                me.currentFrmID = frmID;
                            },
                        });
                    }
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            },
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
    var o_HDKAIKEI_FrmHDKAIKEIMainMenu = new HDKAIKEI.FrmHDKAIKEIMainMenu();
    o_HDKAIKEI_FrmHDKAIKEIMainMenu.load();

    o_HDKAIKEI_HDKAIKEI.FrmHDKAIKEIMainMenu = o_HDKAIKEI_FrmHDKAIKEIMainMenu;
});
