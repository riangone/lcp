Namespace.register("HMTVE.FrmHMTVEMainMenu");

HMTVE.FrmHMTVEMainMenu = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "データ集計システム";

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

        $(".HMTVE.HMTVE-loading-icon").show();
        $(".HMTVE.HMTVE-layout-center fieldset").hide();

        $(".FrmHMTVEMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "HMTVE/FrmHMTVEMainMenu/menuHMTVE",
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
                    ".ui-widget-header.ui-corner-top.HMTVE-ContentBar"
                ).prop("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            console.log(me.currentFrmID);
                            clsComFnc.MsgBoxBtnFnc.Yes = function () {
                                //20200910 lqs ins S
                                // 不需要纵向滚动条的画面，样式还原(需要滚动条的在自己的画面设置样式)
                                $(".HMTVE.HMTVE-layout-center").css(
                                    "overflow-y",
                                    "hidden"
                                );
                                //20210624 ci ins S
                                // 不需要横向滚动条的画面，样式还原(需要滚动条的在自己的画面设置样式)
                                $(".HMTVE.HMTVE-layout-center").css(
                                    "overflow-x",
                                    "hidden"
                                );
                                //20210624 ci ins E
                                $(".HMTVE-content-fixed-width").css(
                                    "width",
                                    "1113px"
                                );
                                //20200910 lqs ins E
                                shortcut.remove("F9");
                                shortcut.remove("F5");
                                me.getHtml(nodeID, frmNM, url);
                            };

                            clsComFnc.MsgBoxBtnFnc.No = function () {
                                $(".FrmHMTVEMainMenu.Menu").jstree(
                                    "deselect_node",
                                    "#" + nodeID
                                );
                                $(".FrmHMTVEMainMenu.Menu").jstree(
                                    "select_node",
                                    "#" + me.currentFrmID
                                );
                            };
                            //20131031 zhenghuiyun update start
                            //clsComFnc.MessageBox("別の画面へ移動すると保存していないデータは失われます移動してよろしいですか？", "", "YesNo", "Information");
                            var msg =
                                "別の画面へ移動すると保存していないデータは失われます。<br/>移動してよろしいですか？";
                            clsComFnc.MessageBox(
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
        var url = "HMTVE/FrmHMTVEMainMenu/getSession";

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

        url = "HMTVE" + "/" + frmID;

        $(".HMTVE.HMTVE-layout-center").html("");
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
                    $(".HMTVE.HMTVE-layout-center").html(res);
                    $(".ui-widget-header.ui-corner-top.HMTVE-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.HMTVE-ContentBar").prop(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".HMTVE.HMTVE-loading-icon").hide();
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
    var o_HMTVE_FrmHMTVEMainMenu = new HMTVE.FrmHMTVEMainMenu();
    o_HMTVE_FrmHMTVEMainMenu.load();

    o_HMTVE_HMTVE.FrmHMTVEMainMenu = o_HMTVE_FrmHMTVEMainMenu;
});
