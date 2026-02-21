Namespace.register("R4K.FrmR4KMainMenu");

R4K.FrmR4KMainMenu = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "";
    me.sys_id = "";
    me.blnFlag = false;
    me.currentFrmID = "";
    me.FrmList = null;
    me.FrmR2KAIKEI = null;
    me.setNodeID = "";
    me.setfrmNM = "";
    me.setUrl = "";

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
        $(".R4K.R4K-loading-icon").show();
        $(".FrmR4KMainMenu.Menu")
            .jstree({
                //20240605 zhangxiaolei upd s
                core: {
                    animation: 0,
                    data: {
                        url: "R4K/FrmR4KMainMenu/menuR4K",
                        dataType: "json",
                    },
                    themes: {
                        variant: "small",
                        stripes: true,
                    },
                },
                //20240605 zhangxiaolei upd e
            })
            .bind("loaded.jstree", function () {
                //20240605 zhangxiaolei upd s
                $(this).jstree("open_all");
                //20240605 zhangxiaolei upd e
            })
            .bind("select_node.jstree", function (_event, data) {
                //20240605 zhangxiaolei upd s
                var nodeID;
                if (isNaN(parseInt(data.node.id))) {
                    nodeID = data.node.id;
                }
                //20240605 zhangxiaolei upd e

                var frmNM = $("#" + nodeID).text();
                url = nodeID + "/index";

                var currentContent = $(
                    ".ui-widget-header.ui-corner-top.R4K-ContentBar"
                ).prop("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                //var currentFrmIDArr = Array("frmTRKDownLoadFS", "frmKasouDownLoadFS", "frmOkaiagePrint", "frmControl", "frmR2ChumonCSV","frmEverydayDLFS","frmEverydayImpFS","frmDLStateCheck");
                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            console.log(me.currentFrmID);
                            clsComFnc.MsgBoxBtnFnc.Yes = function () {
                                shortcut.remove("F9");
                                shortcut.remove("F5");
                                me.getHtml(nodeID, frmNM, url);
                            };

                            clsComFnc.MsgBoxBtnFnc.No = function () {
                                $(".FrmR4KMainMenu.Menu").jstree(
                                    "deselect_node",
                                    "#" + nodeID
                                );
                                $(".FrmR4KMainMenu.Menu").jstree(
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
                                "",
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

    me.getHtml = function (frmID, frmNM, url) {
        console.log(frmID);

        url = "R4K" + "/" + frmID;

        $(".R4K.R4K-layout-center").html("");

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
                    $("#sessionoutpassword").trigger("focus");
                    me.blnFlag = true;
                } else {
                    $(".R4K.R4K-layout-center").html(result);
                    $(".ui-widget-header.ui-corner-top.R4K-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.R4K-ContentBar").prop(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".R4K.R4K-loading-icon").hide();
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
    var o_R4K_FrmR4KMainMenu = new R4K.FrmR4KMainMenu();
    o_R4K_FrmR4KMainMenu.load();

    o_R4K_R4K.FrmR4KMainMenu = o_R4K_FrmR4KMainMenu;
});
