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
 * 20151112           #2271                        BUG                             LI
 * 20151211           #2290                        BUG                             LI
 * 20160527			  #2529						   依頼							   Yinhuaiyu
 * --------------------------------------------------------------------------------------------
 */
Namespace.register("KRSS.FrmKRSSMainMenu");

KRSS.FrmKRSSMainMenu = function () {
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
        $(".KRSS.KRSS-loading-icon").show();
        $(".FrmKRSSMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "KRSS/FrmKRSSMainMenu/menuKRSS",
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
            .bind("loaded.jstree", function () {
                // data.inst.open_all(-1);
                // $("#frmSyasyuArariChkList").css("backgroundColor", "purple");
                // $("#frmChuKaverRankHyo").css("backgroundColor", "purple");
                // $("#frmSinKaverRankHyo").css("backgroundColor", "purple");
                // $("#frmSonekiMeisai").css("backgroundColor", "purple");
                // $("#frmSalesJskList").css("backgroundColor", "purple");
                // $("#frmKanrRankUsed").css("backgroundColor", "purple");
                // $("#frmHknSytKaverRt").css("backgroundColor", "purple");
                //
                // $("#frmKanrRankMente").css("backgroundColor", "purple");
                // $("#frmKanrChkList").css("backgroundColor", "purple");
                // $("#frmLoginSel").css("backgroundColor", "purple");
                // $("#frmHonbuJisseki").css("backgroundColor", "purple");
                // $("#frmHiyouMeisai").css("backgroundColor", "purple");
                // $("#frmKanrRankNew").css("backgroundColor", "purple");
                // $("#frmGENRILIST").css("backgroundColor", "purple");
                // $("#frmAuthCtl").css("backgroundColor", "purple");
                // $("#frmSimBusyoMst").css("backgroundColor", "purple");
                // $("#frmCostAnalyze").css("backgroundColor", "purple");
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
                    ".ui-widget-header.ui-corner-top.KRSS-ContentBar"
                ).prop("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                //var currentFrmIDArr = Array("frmTRKDownLoadFS", "frmKasouDownLoadFS", "frmOkaiagePrint", "frmControl", "frmR2ChumonCSV","frmEverydayDLFS","frmEverydayImpFS","frmDLStateCheck");
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
        //-- 20151112 li INS S.
        if (frmID == "frmLoginSel" || frmID == "frmLoginEdit") {
            frmID = frmID + "KRSS";
        }
        //-- 20151112 li INS E.
        //-- 20151211 li INS S.
        // if (
        //     frmID == "frmGENRILIST" ||
        //     frmID == "frmChuKaverRankHyo" ||
        //     frmID == "frmSinKaverRankHyo" ||
        //     frmID == "frmSyasyuArariChkList"
        // ) {
        //     frmID = frmID + "KRSS";
        // }
        //-- 20151211 li INS E.
        url = "KRSS" + "/" + frmID;

        $(".KRSS.KRSS-layout-center").html("");

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
                    $(".KRSS.KRSS-layout-center").html(result);
                    // frmID == "frmList" ? frmNM = "新車:付属品/特別仕様" : frmNM;
                    // frmID == "frmTRKDownLoadFS" ? frmNM = "登録予定ダウンロード" : frmNM;
                    // frmID == "frmKasouDownLoadFS" ? frmNM += "ダウンロード" : frmNM;i-widget-header.ui-corner-top.KRSS-ContentBar").html(frmNM);
                    $(".ui-widget-header.ui-corner-top.KRSS-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.KRSS-ContentBar").prop(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".KRSS.KRSS-loading-icon").hide();
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
    var o_KRSS_FrmKRSSMainMenu = new KRSS.FrmKRSSMainMenu();
    o_KRSS_FrmKRSSMainMenu.load();

    o_KRSS_KRSS.FrmKRSSMainMenu = o_KRSS_FrmKRSSMainMenu;
});
