/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmAPPMMainMenu");

APPM.FrmAPPMMainMenu = function () {
    var me = new gdmz.base.panel();
    // var clsComFnc = new gdmz.common.clsComFnc();

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
        $(".APPM.APPM-loading-icon").show();
        $(".FrmAPPMMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "APPM/FrmAPPMMainMenu/menuAPPM",
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
                    ".ui-widget-header.ui-corner-top.APPM-ContentBar"
                ).attr("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];

                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            me.getHtml(nodeID, frmNM, url);
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

        url = "APPM" + "/" + frmID;

        $(".APPM.APPM-layout-center").html("");

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
                    $(".APPM.APPM-layout-center").html(result);
                    $(".ui-widget-header.ui-corner-top.APPM-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.APPM-ContentBar").attr(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".APPM.APPM-loading-icon").hide();
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
    var o_APPM_FrmAPPMMainMenu = new APPM.FrmAPPMMainMenu();
    o_APPM_FrmAPPMMainMenu.load();

    o_APPM_APPM.FrmAPPMMainMenu = o_APPM_FrmAPPMMainMenu;
});
