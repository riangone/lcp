Namespace.register("JKSYS.FrmJKSYSMainMenu");

JKSYS.FrmJKSYSMainMenu = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "人事給与システム";

    me.ajax = new gdmz.common.ajax();

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

        $(".JKSYS.JKSYS-loading-icon").show();
        $(".JKSYS.JKSYS-layout-center fieldset").hide();

        $(".FrmJKSYSMainMenu.Menu")
            .jstree({
                //20240606 caina upd s
                core: {
                    animation: 0,
                    data: {
                        url: "JKSYS/FrmJKSYSMainMenu/menuJKSYS",
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
                    ".ui-widget-header.ui-corner-top.JKSYS-ContentBar"
                ).prop("id");
                var currentFrmIDarr = currentContent.split("_");
                me.currentFrmID = currentFrmIDarr[1];
                //var currentFrmIDArr = Array("frmTRKDownLoadFS", "frmKasouDownLoadFS", "frmOkaiagePrint", "frmControl", "frmR2ChumonCSV","frmEverydayDLFS","frmEverydayImpFS","frmDLStateCheck");
                if (nodeID != "" && nodeID != null && nodeID != "undefined") {
                    if (me.blnFlag) {
                        if (nodeID != me.currentFrmID) {
                            console.log(me.currentFrmID);
                            clsComFnc.MsgBoxBtnFnc.Yes = function () {
                                //20200910 lqs ins S
                                // 不需要纵向滚动条的画面，样式还原(需要滚动条的在自己的画面设置样式)
                                $(".JKSYS.JKSYS-layout-center").css(
                                    "overflow-y",
                                    "hidden"
                                );
                                $(".JKSYS-content-fixed-width").css(
                                    "width",
                                    "1113px"
                                );
                                //20200910 lqs ins E
                                shortcut.remove("F9");
                                shortcut.remove("F5");
                                me.getHtml(nodeID, frmNM, url);
                            };

                            clsComFnc.MsgBoxBtnFnc.No = function () {
                                $(".FrmJKSYSMainMenu.Menu").jstree(
                                    "deselect_node",
                                    "#" + nodeID
                                );
                                $(".FrmJKSYSMainMenu.Menu").jstree(
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
                                "人事給与システム",
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

        //奉行の取込情報を表示
        me.BugyouTrkInfoSet();
    };

    me.getHtml = function (frmID, frmNM, url) {
        console.log(frmID);

        url = "JKSYS" + "/" + frmID;

        $(".JKSYS.JKSYS-layout-center").html("");

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
                    $(".JKSYS.JKSYS-layout-center").html(result);
                    $(".ui-widget-header.ui-corner-top.JKSYS-ContentBar").html(
                        frmNM
                    );
                    $(".ui-widget-header.ui-corner-top.JKSYS-ContentBar").prop(
                        "id",
                        "mainTtl_" + frmID
                    );

                    $(".JKSYS.JKSYS-loading-icon").hide();
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

    me.BugyouTrkInfoSet = function () {
        $(".FrmMainContainer.lblErrMsg").html("");

        var url = "JKSYS/FrmJKSYSMainMenu/BugyouTrkInfoSet";
        me.ajax.receive = function (result) {
            $(".JKSYS.JKSYS-layout-center fieldset").show();

            var result = eval("(" + result + ")");

            //異常
            if (!result["result"] && !result["errmsg"]) {
                clsComFnc.FncMsgBox("E9999", result["error"]);
            }

            if (result["data"]["lblSyainDate"] !== "") {
                $(".FrmMainContainer.lblSyainDate").html(
                    result["data"]["lblSyainDate"]
                );
            }
            if (result["data"]["lblKyuyoDate"] !== "") {
                $(".FrmMainContainer.lblKyuyoDate").html(
                    result["data"]["lblKyuyoDate"]
                );
            }
            if (result["data"]["lblHyoukaDate"] !== "") {
                $(".FrmMainContainer.lblHyoukaDate").html(
                    result["data"]["lblHyoukaDate"]
                );
            }
            if (result["data"]["lblErrMsg"] != "") {
                $(".FrmMainContainer.lblErrMsg").html(
                    result["data"]["lblErrMsg"]
                );
                //異常
                if (result["errmsg"]) {
                    clsComFnc.FncMsgBox(result["error"], result["errmsg"]);
                }
            }
        };
        me.ajax.send(url, "", 1);
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_JKSYS_FrmJKSYSMainMenu = new JKSYS.FrmJKSYSMainMenu();
    o_JKSYS_FrmJKSYSMainMenu.load();

    o_JKSYS_JKSYS.FrmJKSYSMainMenu = o_JKSYS_FrmJKSYSMainMenu;
});
