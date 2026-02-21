Namespace.register("HMAUD.FrmHMAUDMainMenu");

HMAUD.FrmHMAUDMainMenu = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "内部統制システム";

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

        $(".HMAUD.HMAUD-loading-icon").show();
        $(".HMAUD.HMAUD-layout-center fieldset").hide();

        $(".FrmHMAUDMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "HMAUD/FrmHMAUDMainMenu/menuHMAUD",
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
                    ".ui-widget-header.ui-corner-top.HMAUD-ContentBar"
                ).attr("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            console.log(me.currentFrmID);
                            clsComFnc.MsgBoxBtnFnc.Yes = function () {
                                $(".HMAUD.HMAUD-layout-center").css(
                                    "overflow-y",
                                    "hidden"
                                );
                                $(".HMAUD.HMAUD-layout-center").css(
                                    "overflow-x",
                                    "hidden"
                                );
                                $(".HMAUD-content-fixed-width").css(
                                    "width",
                                    "1113px"
                                );
                                shortcut.remove("F9");
                                shortcut.remove("F5");
                                me.getHtml(nodeID, frmNM, url);
                            };

                            clsComFnc.MsgBoxBtnFnc.No = function () {
                                $(".FrmHMAUDMainMenu.Menu").jstree(
                                    "deselect_node",
                                    "#" + nodeID
                                );
                                $(".FrmHMAUDMainMenu.Menu").jstree(
                                    "select_node",
                                    "#" + me.currentFrmID
                                );
                            };
                            var msg =
                                "別の画面へ移動すると保存していないデータは失われます。<br/>移動してよろしいですか？";
                            clsComFnc.MessageBox(
                                msg,
                                "内部統制システム",
                                "YesNo",
                                "Information"
                            );
                        }
                    } else {
                        console.log(nodeID, frmNM, url);
                        me.getHtml(nodeID, frmNM, url);
                    }
                }
            });
    };

    function getSession() {
        var url = "HMAUD/FrmHMAUDMainMenu/getSession";

        var data = {
            request: {},
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] != false) {
                gdmz.SessionUserId = result["data"]["UserId"];
                gdmz.SessionPatternID = result["data"]["PatternID"];
            }
        };
        me.ajax.send(url, data, 0);
    }

    me.getHtml = function (frmID, frmNM, url) {
        console.log(frmID);

        url = "HMAUD" + "/" + frmID;

        $(".HMAUD.HMAUD-layout-center").html("");
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (res) {
                if (res == '{"result":true,"data":"session is outdate"}') {
                    $("#sessionoutdate").dialog("open");
                    var client = $(".LogineduserID").html();
                    $("#sessionoutuser").val(client);
                    $("#sessionoutpassword").trigger("focus");
                    me.blnFlag = true;
                } else {
                    $(".HMAUD.HMAUD-layout-center").html(res);
                    $(".ui-widget-header.ui-corner-top.HMAUD-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.HMAUD-ContentBar").attr(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".HMAUD.HMAUD-loading-icon").hide();
                }
                me.blnFlag = true;
                me.currentFrmID = frmID;
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
    var o_HMAUD_FrmHMAUDMainMenu = new HMAUD.FrmHMAUDMainMenu();
    o_HMAUD_FrmHMAUDMainMenu.load();

    o_HMAUD_HMAUD.FrmHMAUDMainMenu = o_HMAUD_FrmHMAUDMainMenu;
});
