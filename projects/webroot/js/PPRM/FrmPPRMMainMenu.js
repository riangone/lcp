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
Namespace.register("PPRM.FrmPPRMMainMenu");

PPRM.FrmPPRMMainMenu = function () {
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
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    me.ajax = new gdmz.common.ajax();

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
        $(".PPRM.PPRM-loading-icon").show();
        $(".FrmPPRMMainMenu.Menu")
            .jstree({
                core: {
                    animation: 0,
                    data: {
                        url: "PPRM/FrmPPRMMainMenu/menuPPRM",
                        dataType: "json",
                    },
                    themes: {
                        variant: "small",
                        stripes: true,
                    },
                },
            })
            .bind("loaded.jstree", function () {
                $(this).jstree("open_all");
                // 20170301 LQS INS S
                getSession();
                // 20170301 LQS INS E
            })
            .bind("dblclick.jstree", function () {
                // Do my action
            })
            .bind("select_node.jstree", function (_event, data) {
                var nodeID;
                if (isNaN(parseInt(data.node.id))) {
                    nodeID = data.node.id;
                }
                var frmNM = $("#" + nodeID).text();
                url = nodeID + "/index";
                var currentContent = $(
                    ".ui-widget-header.ui-corner-top.PPRM-ContentBar"
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

    // 20170301 LQS INS S
    function getSession() {
        var url = "PPRM/FrmPPRMMainMenu/getSession";
        var arr = {};

        var data = {
            request: arr,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] != false) {
                gdmz.SessionUserId = result["data"]["UserId"];
                gdmz.SessionBusyoCD = result["data"]["BusyoCD"];
                gdmz.SessionBusyoKB = result["data"]["BusyoKB"];
                gdmz.SessionTenpoCD = result["data"]["TenpoCD"];
                gdmz.SessionSyainNM = result["data"]["SyainNM"];
                gdmz.SessionMachineNM = result["data"]["MachineNM"];
                gdmz.SessionPatternID = result["data"]["PatternID"];
            }
        };
        me.ajax.send(url, data, 0);
    }
    // 20170301 LQS INS E

    me.getHtml = function (frmID, frmNM, url) {
        console.log(frmID);

        url = "PPRM" + "/" + frmID;

        $(".PPRM.PPRM-layout-center").html("");

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
                    $(".PPRM.PPRM-layout-center").html(result);
                    $(".ui-widget-header.ui-corner-top.PPRM-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.PPRM-ContentBar").attr(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".PPRM.PPRM-loading-icon").hide();
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
    var o_PPRM_FrmPPRMMainMenu = new PPRM.FrmPPRMMainMenu();
    o_PPRM_FrmPPRMMainMenu.load();

    o_PPRM_PPRM.FrmPPRMMainMenu = o_PPRM_FrmPPRMMainMenu;
});
