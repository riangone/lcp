/**
 * 説明：
 *
 *
 * @author yinhuaiyu
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20201117            bug                          ボタンが非活性化の場合は、マウスオーバーも発生させる       WL
 * 20201120            bug                          表示倍率：125%の場合は、ChromeでjqGridの見出しと明細行の 罫線がずれる      WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRM201DCImageRelation");

PPRM.PPRM201DCImageRelation = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパレス化支援システム";
    var ajax = new gdmz.common.ajax();
    var ODR = new gdmz.PPRM.ODR_JScript();

    // ========== 変数 start ==========

    me.id = "PPRM201DCImageRelation";
    me.sys_id = "PPRM";
    me.url = "";
    me.data = new Array();

    me.Listfirst1 = "isfirst";
    me.Listfirst2 = "isfirst";
    me.btnflag = true;

    //20170908 ZHANGXIAOLEI INS S
    me.BusyoArr = new Array();
    //20170908 ZHANGXIAOLEI INS E

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // 20170922 lqs INS S
    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    clsComFnc.TabKeyDown();
    // 20170922 lqs INS E

    //jqgrid
    {
        me.grid_id1 = "#PPRM201DCImageRelation_spdList1";
        me.grid_id2 = "#PPRM201DCImageRelation_spdList2";
        me.g_url1 = "PPRM/PPRM201DCImageRelation/btnSearchClick";
        me.g_url2 = "PPRM/PPRM201DCImageRelation/subMeisaiDisp";
        me.pager = "";
        me.sidx = "";

        me.option1 = {
            rowNum: 9999,
            recordpos: "left",
            multiselect: false,
            rownumbers: true,
            rownumWidth: 30,
            caption: "",
            scroll: 1,
        };
        me.option2 = {
            rowNum: 9999,
            recordpos: "left",
            multiselect: false,
            rownumbers: true,
            rownumWidth: 30,
            caption: "",
            scroll: 1,
        };

        me.colModel1 = [
            {
                name: "HJM_SYR_DTM",
                label: "日締日時",
                index: "HJM_SYR_DTM",
                width: 160,
                sortable: false,
            },
            {
                name: "BUSYO_RYKNM",
                label: "店舗",
                index: "BUSYO_RYKNM",
                width: 110,
                sortable: false,
            },
            {
                name: "TEN_HJM_NO",
                label: "日締№",
                index: "TEN_HJM_NO",
                width: 115,
                sortable: false,
            },
            {
                name: "TENPO_CD",
                label: "店舗コード",
                index: "TENPO_CD",
                hidden: true,
                width: 140,
            },
            {
                name: "IMAGE_EXISTS",
                label: "イメージ存在フラグ",
                index: "IMAGE_EXISTS",
                hidden: true,
                width: 140,
            },
            {
                name: "PRINT_DISP_FLG",
                label: "日締出力帳票flag",
                index: "PRINT_DISP_FLG",
                hidden: true,
                width: 140,
            },
            {
                name: "IMAGE_DISP_FLG",
                label: "ｲﾒｰｼﾞと関連付けるflag",
                index: "IMAGE_DISP_FLG",
                hidden: true,
                width: 200,
            },
            {
                name: "MEISAI_DISP_FLG",
                label: "明細flag",
                index: "MEISAI_DISP_FLG",
                hidden: true,
                width: 200,
            },
        ];

        me.colModel2 = [
            {
                name: "IMAGE_FILE_NM",
                label: "ファイル名",
                index: "IMAGE_FILE_NM",
                width: 200,
            },
            {
                name: "IMAGE_FILE_ID",
                label: "ファイルID",
                index: "IMAGE_FILE_ID",
                hidden: true,
                width: 100,
            },
            {
                name: "SAVE_PATH",
                label: "ファイルパス",
                index: "SAVE_PATH",
                hidden: true,
                width: 130,
            },
        ];
    }

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnFromTenpoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnHJMSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnToTenpoSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnopenHijimeOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnImgFileAdd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnopendetails",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnImgOpenFile",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnImgDelFile",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnBack",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnUpdate",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.btnCancel",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.txtHJMFromDate",
        type: "datepicker",
        handle: "",
    });

    me.controls.push({
        id: ".PPRM201DCImageRelation.txtHJMToDate",
        type: "datepicker",
        handle: "",
    });

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //20170908 ZHANGXIAOLEI UPD S
        // me.PPRM201DCImageRelation_load();
        me.getAllBusyoNM();
        //20170908 ZHANGXIAOLEI UPD E
    };

    //20170908 ZHANGXIAOLEI INS S
    //'**********************************************************************
    //'処 理 名：全部の店舗コードと店舗名を取得
    //'関 数 名：me.getAllBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：全部の店舗コードと店舗名を取得
    //'**********************************************************************
    me.getAllBusyoNM = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetALLBusyoNM";
        var selectObj = {};
        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
            } else {
                me.BusyoArr = result["data"];
            }
            me.PPRM201DCImageRelation_load();
        };
        ajax.send(url, selectObj, 0);
    };
    //20170908 ZHANGXIAOLEI INS E

    //【検索(店舗コードfrom)】ボタン押下
    $(".PPRM201DCImageRelation.btnFromTenpoSearch").click(function () {
        openFromTenpoSearch();
    });
    //【検索(店舗コードto)】ボタン押下
    $(".PPRM201DCImageRelation.btnToTenpoSearch").click(function () {
        openToTenpoSearch();
    });
    //【検索】ボタン押下
    $(".PPRM201DCImageRelation.btnSearch").click(function () {
        me.btnSearch_click();
    });
    //【登録】ボタン押下
    $(".PPRM201DCImageRelation.btnUpdate").click(function () {
        me.btnUpdate_Click();
    });
    //【ｷｬﾝｾﾙ】ボタン押下
    $(".PPRM201DCImageRelation.btnCancel").click(function () {
        me.btnSearch_click();
    });
    //【検索(日締№)】ボタン押下
    $(".PPRM201DCImageRelation.btnHJMSearch").click(function () {
        me.openHJMSearch();
    });
    //【明細表示】ボタン押下
    $(".PPRM201DCImageRelation.btnopendetails").click(function () {
        me.subMeisaiDisp();
    });
    //【日締ﾌﾟﾚﾋﾞｭｰ】ボタン押下
    $(".PPRM201DCImageRelation.btnopenHijimeOut").click(function () {
        me.openHijimeOut();
    });
    //【イメージファイル追加】ボタン押下
    $(".PPRM201DCImageRelation.btnImgFileAdd").click(function () {
        me.ImgFileAdd();
    });
    //【表示】ボタン押下
    $(".PPRM201DCImageRelation.btnImgOpenFile").click(function () {
        ImgOpenFile();
    });
    //【削除】ボタン押下
    $(".PPRM201DCImageRelation.btnImgDelFile").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = function () {
            me.cmdEvent_Click();
        };
        clsComFnc.MsgBoxBtnFnc.No = function () {
            return;
        };
        clsComFnc.FncMsgBox("QY014_PPRM", "関連付け ");
    });
    //【店舗コード(from)】のchange
    $(".PPRM201DCImageRelation.txtFromTenpoCD").change(function () {
        spdClear();
        me.FncGetBusyoNM(
            $(this),
            $(".PPRM201DCImageRelation.lblFromTenpo"),
            "F"
        );
    });
    //【店舗コード(to)】のchange
    $(".PPRM201DCImageRelation.txtToTenpoCD").change(function () {
        spdClear();
        me.FncGetBusyoNM($(this), $(".PPRM201DCImageRelation.lblToTenpo"), "T");
    });
    //【日締日(from)】のchange
    $(".PPRM201DCImageRelation.txtHJMFromDate").change(function () {
        spdClear();
    });
    //【日締日(to)】のchange
    $(".PPRM201DCImageRelation.txtHJMToDate").change(function () {
        spdClear();
    });
    //【日締№】のchange
    $(".PPRM201DCImageRelation.txtHJMNo").change(function () {
        spdClear();
    });
    //【イメージファイル割当無し】のchange
    $(".PPRM201DCImageRelation.rdbImage1").change(function () {
        spdClear();
    });
    //【イメージファイル割当有り】のchange
    $(".PPRM201DCImageRelation.rdbImage2").change(function () {
        spdClear();
    });
    //【指定無し】のchange
    $(".PPRM201DCImageRelation.rdbImage3").change(function () {
        spdClear();
    });
    //【日締日(from)】のblur
    $(".PPRM201DCImageRelation.txtHJMFromDate").on("blur", function () {
        ODR.DateFOut($(this));
    });
    //【日締日(to)】のblur
    $(".PPRM201DCImageRelation.txtHJMToDate").on("blur", function () {
        ODR.DateFOut($(this));
    });
    // 2017/09/08 CI INS S
    $(".PPRM201DCImageRelation.txtFromTenpoCD").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });

    $(".PPRM201DCImageRelation.txtToTenpoCD").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    $(".PPRM201DCImageRelation.txtHJMNo").on("blur", function () {
        ODR.KinsokuMojiCheck($(this));
    });
    // 2017/09/08 CI INS E

    me.PPRM201DCImageRelation_load = function () {
        $(".PPRM201DCImageRelation.List").css("display", "none");
        var url = me.sys_id + "/" + me.id + "/pprm201DCImageRelationLoad";
        var data = {};

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            for (key in result["data"]) {
                $(".PPRM201DCImageRelation." + key).prop(
                    "disabled",
                    result["data"][key]
                );
            }
            $(".PPRM201DCImageRelation.txtFromTenpoCD").trigger("focus");
        };
        ajax.send(url, data, 0);
    };
    // '**********************************************************************
    // '処 理 名：検索を行う
    // '関 数 名：btnSearch_Click
    // '戻 り 値：なし
    // '処理説明：条件に一致する検索結果を一覧に表示する
    // '**********************************************************************
    me.btnSearch_click = function () {
        spdClear();
        //入力値のチェック
        var txtFromTenpoCD = $(".PPRM201DCImageRelation.txtFromTenpoCD").val();
        var txtToTenpoCD = $(".PPRM201DCImageRelation.txtToTenpoCD").val();
        var txtHJMFromDate = $(".PPRM201DCImageRelation.txtHJMFromDate").val();
        var txtHJMToDate = $(".PPRM201DCImageRelation.txtHJMToDate").val();
        var txtHJMNo = $(".PPRM201DCImageRelation.txtHJMNo").val();
        var rdbImage = "";
        txtHJMFromDate = txtHJMFromDate.replace(/\//g, "");
        txtHJMToDate = txtHJMToDate.replace(/\//g, "");

        $(".rdbImage input").each(function () {
            if ($(this).prop("checked") == true) {
                rdbImage = $(this).val();
            }
        });

        if (txtFromTenpoCD != "" && txtToTenpoCD != "") {
            if (parseInt(txtFromTenpoCD) > parseInt(txtToTenpoCD)) {
                $(".PPRM201DCImageRelation.txtFromTenpoCD").trigger("focus");
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "店舗コード（前）",
                    "店舗コード（後）"
                );
                return;
            }
        }

        if (txtHJMFromDate != "" && txtHJMToDate != "") {
            if (parseInt(txtHJMFromDate) > parseInt(txtHJMToDate)) {
                $(".PPRM201DCImageRelation.txtHJMFromDate").trigger("focus");
                clsComFnc.FncMsgBox(
                    "E0006_PPRM",
                    "日締日（前）",
                    "日締日（後）"
                );
                return;
            }
        }

        var data = {
            txtFromTenpoCD: txtFromTenpoCD,
            txtToTenpoCD: txtToTenpoCD,
            txtHJMFromDate: txtHJMFromDate,
            txtHJMToDate: txtHJMToDate,
            txtHJMNo: txtHJMNo,
            rdbImage: rdbImage,
        };
        me.complete_fun1 = function (bErrorFlag) {
            //20201120 WL DEL S
            //20170913 YIN INS S
            // $('.ui-jqgrid-labels').block(
            // {
            // "overlayCSS" :
            // {
            // opacity : 0,
            // }
            // });
            // //20170913 YIN INS E
            //20201120 WL DEL E
            if (bErrorFlag == "nodata") {
                clsComFnc.FncMsgBox("W0003_PPRM");
                return;
            } else {
                $(".PPRM201DCImageRelation.List").css("display", "block");
                //20201117 WL UPD S
                //$(".PPRM201DCImageRelation.btnopenHijimeOut").attr("disabled", "disabled");
                //$(".PPRM201DCImageRelation.btnImgFileAdd").attr("disabled", "disabled");
                //$(".PPRM201DCImageRelation.btnopendetails").attr("disabled", "disabled");
                $(".PPRM201DCImageRelation.btnopenHijimeOut").button("disable");
                $(".PPRM201DCImageRelation.btnImgFileAdd").button("disable");
                $(".PPRM201DCImageRelation.btnopendetails").button("disable");
                //20201117 WL UPD E
            }

            $("#PPRM201DCImageRelation_spdList1").jqGrid("setGridParam", {
                onSelectRow: function (rowid, status) {
                    if (me.btnflag) {
                        var rowData = $(
                            "#PPRM201DCImageRelation_spdList1"
                        ).jqGrid("getRowData", rowid);

                        if (clsComFnc.FncNz(rowData["PRINT_DISP_FLG"]) == 0) {
                            //20201117 WL UPD S
                            //$(".PPRM201DCImageRelation.btnopenHijimeOut").attr("disabled", "disabled");
                            $(
                                ".PPRM201DCImageRelation.btnopenHijimeOut"
                            ).button("disable");
                            //20201117 WL UPD E
                        } else {
                            //20201117 WL UPD S
                            //$(".PPRM201DCImageRelation.btnopenHijimeOut").attr("disabled", false);
                            $(
                                ".PPRM201DCImageRelation.btnopenHijimeOut"
                            ).button("enable");
                            //20201117 WL UPD E
                        }

                        if (clsComFnc.FncNz(rowData["IMAGE_DISP_FLG"]) == 0) {
                            //20201117 WL UPD S
                            //$(".PPRM201DCImageRelation.btnImgFileAdd").attr("disabled", "disabled");
                            $(".PPRM201DCImageRelation.btnImgFileAdd").button(
                                "disable"
                            );
                            //20201117 WL UPD E
                        } else {
                            //20201117 WL UPD S
                            //$(".PPRM201DCImageRelation.btnImgFileAdd").attr("disabled", false);
                            $(".PPRM201DCImageRelation.btnImgFileAdd").button(
                                "enable"
                            );
                            //20201117 WL UPD E
                        }

                        if (rowData["IMAGE_EXISTS"] == 0) {
                            //20201117 WL UPD S
                            //$(".PPRM201DCImageRelation.btnopendetails").attr("disabled", "disabled");
                            $(".PPRM201DCImageRelation.btnopendetails").button(
                                "disable"
                            );
                            //20201117 WL UPD E
                        } else {
                            if (
                                clsComFnc.FncNz(rowData["MEISAI_DISP_FLG"]) == 0
                            ) {
                                //20201117 WL UPD S
                                //$(".PPRM201DCImageRelation.btnopendetails").attr("disabled", "disabled");
                                $(
                                    ".PPRM201DCImageRelation.btnopendetails"
                                ).button("disable");
                                //20201117 WL UPD E
                            } else {
                                //20201117 WL UPD S
                                //$(".PPRM201DCImageRelation.btnopendetails").attr("disabled", false);
                                $(
                                    ".PPRM201DCImageRelation.btnopendetails"
                                ).button("enable");
                                //20201117 WL UPD E
                            }
                        }
                    }
                    if (status) {
                        $(".PPRM201DCImageRelation.tblMeisai").css(
                            "display",
                            "none"
                        );
                    }
                },
            });
            me.btnflag = true;
        };
        if (me.Listfirst1 == "isfirst") {
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id1,
                me.g_url1,
                me.colModel1,
                me.pager,
                me.sidx,
                me.option1,
                data,
                me.complete_fun1
            );
            me.Listfirst1 = "no";
        } else {
            gdmz.common.jqgrid.reloadMessage(
                me.grid_id1,
                data,
                me.complete_fun1
            );
        }

        gdmz.common.jqgrid.set_grid_width(me.grid_id1, 455);
        gdmz.common.jqgrid.set_grid_height(me.grid_id1, 103);
        //20170905 YIN INS S
        $(".PPRM201DCImageRelation.Ruri").unblock();
        //20170905 YIN INS E
    };
    //'**********************************************************************
    //'処 理 名：登録ボタン設定
    //'関 数 名：btnUpdate_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ登録
    //'**********************************************************************
    me.btnUpdate_Click = function () {
        var url = "PPRM/PPRM201DCImageRelation/btnUpdateClick";
        var liArr = me.checkedData();
        var data = {
            liArr: liArr,
            tenpoCD: $(".PPRM201DCImageRelation.lblTenpoCD").val(),
            HJMNo: $(".PPRM201DCImageRelation.lblHJMNo").val(),
        };
        if (liArr.length < 1) {
            clsComFnc.FncMsgBox(
                "E0011_PPRM",
                "ファイルまたはフォルダを選択してください。"
            );
            return;
        }
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["data"] == "max") {
                clsComFnc.FncMsgBox(
                    "E0011_PPRM",
                    "100件以上登録することはできません。"
                );
                return;
            }
            if (result["data"] == "nodatains") {
                clsComFnc.FncMsgBox("E0012_PPRM", "JPEGファイル(.jpg)。");
                return;
            }
            me.subMeisaiDisp($(".PPRM201DCImageRelation.lblHJMNo").val());
        };
        ajax.send(url, data, 0);
    };
    //'**********************************************************************
    //'処 理 名：削除処理
    //'関 数 名：cmdEvent_Click
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイルデータ削除
    //'**********************************************************************
    me.cmdEvent_Click = function () {
        var url = "PPRM/PPRM201DCImageRelation/cmdEventClick";

        var id = $("#PPRM201DCImageRelation_spdList2").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM201DCImageRelation_spdList2").jqGrid(
            "getRowData",
            id
        );

        var strID = rowData["IMAGE_FILE_ID"];

        var data = {
            strID: strID,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            var getDataCount = $("#PPRM201DCImageRelation_spdList2").jqGrid(
                "getGridParam",
                "records"
            );
            $("#PPRM201DCImageRelation_spdList2").jqGrid("delRowData", id);

            if (getDataCount == 1) {
                me.btnSearch_click();
            }
            //201709018 lqs INS S
            else {
                var id = $("#PPRM201DCImageRelation_spdList1").jqGrid(
                    "getGridParam",
                    "selrow"
                );
                var rowData = $("#PPRM201DCImageRelation_spdList1").jqGrid(
                    "getRowData",
                    id
                );

                strHjmNo = rowData["TEN_HJM_NO"];
                me.subMeisaiDisp(strHjmNo);
            }
            //201709018 lqs INS E
        };
        ajax.send(url, data, 0);
    };

    me.subMeisaiDisp = function (strHjmNo) {
        var url = "PPRM/PPRM201DCImageRelation/getButton";

        var id = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getRowData",
            id
        );

        tenpoCD = rowData["TENPO_CD"];

        var data = {
            tenpoCD: tenpoCD,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            var btnImgOpenFile = result["data"]["blnIdisp"];
            var btnImgDelFile = result["data"]["blnIdel"];

            if (strHjmNo == undefined) {
                var id = $("#PPRM201DCImageRelation_spdList1").jqGrid(
                    "getGridParam",
                    "selrow"
                );
                var rowData = $("#PPRM201DCImageRelation_spdList1").jqGrid(
                    "getRowData",
                    id
                );

                strHjmNo = rowData["TEN_HJM_NO"];
            }
            var data = {
                strHjmNo: strHjmNo,
            };
            me.complete_fun2 = function (bErrorFlag) {
                //20201120 WL DEL S
                // //20170913 YIN INS S
                // $('.ui-jqgrid-labels').block(
                // {
                // "overlayCSS" :
                // {
                // opacity : 0,
                // }
                // });
                // //20170913 YIN INS E
                //20201120 WL DEL E
                if (bErrorFlag == "nodata") {
                    clsComFnc.FncMsgBox("W0003_PPRM");
                    return;
                } else {
                    $(".PPRM201DCImageRelation.tblMeisai").css(
                        "display",
                        "block"
                    );
                    //20201117 WL UPD S
                    //$(".PPRM201DCImageRelation.btnImgOpenFile").attr("disabled", "disabled");
                    //$(".PPRM201DCImageRelation.btnImgDelFile").attr("disabled", "disabled");
                    $(".PPRM201DCImageRelation.btnImgOpenFile").button(
                        "disable"
                    );
                    $(".PPRM201DCImageRelation.btnImgDelFile").button(
                        "disable"
                    );
                    //20201117 WL UPD E
                }
                //20170915 lqs INS S
                var dataNum = $("#PPRM201DCImageRelation_spdList2").jqGrid(
                    "getGridParam",
                    "records"
                );
                $(".PPRM201DCImageRelation.detailNum").text(dataNum);
                //20170915 lqs INS E

                $("#PPRM201DCImageRelation_spdList2").jqGrid("setGridParam", {
                    onSelectRow: function () {
                        //20201117 WL UPD S
                        //$(".PPRM201DCImageRelation.btnImgOpenFile").attr("disabled", !btnImgOpenFile);
                        //$(".PPRM201DCImageRelation.btnImgDelFile").attr("disabled", !btnImgDelFile);
                        $(".PPRM201DCImageRelation.btnImgOpenFile").button(
                            !btnImgOpenFile ? "disable" : "enable"
                        );
                        $(".PPRM201DCImageRelation.btnImgDelFile").button(
                            !btnImgDelFile ? "disable" : "enable"
                        );
                        //20201117 WL UPD E
                    },
                });
            };
            if (me.Listfirst2 == "isfirst") {
                gdmz.common.jqgrid.showWithMesg(
                    me.grid_id2,
                    me.g_url2,
                    me.colModel2,
                    me.pager,
                    me.sidx,
                    me.option2,
                    data,
                    me.complete_fun2
                );
                $(me.grid_id2).jqGrid("setGroupHeaders", {
                    useColSpanStyle: false,
                    groupHeaders: [
                        {
                            startColumnName: "IMAGE_FILE_NM",
                            numberOfColumns: 1,
                            titleText: "関連付け済イメージファイル",
                        },
                    ],
                });
                me.Listfirst2 = "no";
            } else {
                gdmz.common.jqgrid.reloadMessage(
                    me.grid_id2,
                    data,
                    me.complete_fun2
                );
            }

            gdmz.common.jqgrid.set_grid_width(me.grid_id2, 257);
            gdmz.common.jqgrid.set_grid_height(me.grid_id2, 284);
        };
        ajax.send(url, data, 0);
    };

    me.ImgFileAdd = function () {
        $(".PPRM201DCImageRelation.treeList").jstree("destroy").empty();
        var id = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getRowData",
            id
        );

        $(".PPRM201DCImageRelation.lblTenpoCD").val(rowData["TENPO_CD"]);
        $(".PPRM201DCImageRelation.lblTenpo").val(
            clsComFnc.FncNv(rowData["BUSYO_RYKNM"])
        );
        $(".PPRM201DCImageRelation.lblHJMNo").val(rowData["TEN_HJM_NO"]);
        $(".PPRM201DCImageRelation.lblHJMDate").val(rowData["HJM_SYR_DTM"]);

        var url = "PPRM/PPRM201DCImageRelation/getTreeView";
        var data = {};

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            data = result["reports"]["data"];
            var tbl = "";

            showView(data);
            $(".PPRM201DCImageRelation.imgPath").html(
                result["reports"]["path"] + "/"
            );
            var path;
            $(".PPRM201DCImageRelation.treeList").append(tbl);

            $(".PPRM201DCImageRelation.treeList")
                .jstree({
                    core: {
                        themes: {
                            variant: "small",
                            stripes: true,
                        },
                    },
                    //if clicking anywhere on the node should not act as clicking on the checkbox
                    checkbox: {
                        whole_node: false,
                        tie_selection: false,
                    },
                    plugins: ["checkbox"],
                })
                .bind("click.jstree", function (event) {
                    if (
                        event.target.className ===
                            "jstree-icon jstree-checkbox" ||
                        event.target.className ===
                            "jstree-icon jstree-themeicon" ||
                        event.target.className === "jstree-icon jstree-ocl"
                    ) {
                        return;
                    }
                    //20170919 lqs INS S
                    $(".PPRM201DCImageRelation.imgDialog").append(
                        "<img class='PPRM201DCImageRelation imgView body' src='' style='width: 100%;height: 100%' hidden />"
                    );
                    //20170919 lqs INS E
                    var parent_id = $(event.target)
                        .parent("li")
                        .prop("parentId");
                    var text = event.target.lastChild.textContent ?? "";
                    me.pathArr = [];
                    getPath(parent_id);
                    if (me.pathArr && me.pathArr.length) {
                        for (var i = me.pathArr.length - 1; i >= 0; i--) {
                            path =
                                result["reports"]["path"] +
                                "/" +
                                me.pathArr[i] +
                                "/";
                        }
                        if (text) path += text;
                    } else {
                        if (text) path = result["reports"]["path"] + "/" + text;
                    }
                    $(".PPRM201DCImageRelation.imgPath").html(path);
                    $(".PPRM201DCImageRelation.imgView").prop("src", path);

                    if (text && text.indexOf(".") > -1) {
                        $(".PPRM201DCImageRelation.imgView.body").dialog({
                            autoOpen: false,
                            width: 550,
                            height: 600,
                            modal: true,
                            title: "イメージファイル",
                            open: function () {},
                            close: function () {
                                //20170919 lqs INS S
                                $(
                                    ".PPRM201DCImageRelation.imgView.body"
                                ).remove();
                                //20170919 lqs INS E
                            },
                        });
                        $(".PPRM201DCImageRelation.imgView.body").dialog(
                            "open"
                        );
                    }
                });

            $(".PPRM201DCImageRelation.tblNyuryoku").css("display", "block");
            $(".PPRM201DCImageRelation.btnBack").css("display", "none");
            //20201117 WL UPD S
            // $(".PPRM201DCImageRelation.btnopenHijimeOut").attr("disabled", "disabled");
            // $(".PPRM201DCImageRelation.btnImgFileAdd").attr("disabled", "disabled");
            // $(".PPRM201DCImageRelation.btnopendetails").attr("disabled", "disabled");
            $(".PPRM201DCImageRelation.btnopenHijimeOut").button("disable");
            $(".PPRM201DCImageRelation.btnImgFileAdd").button("disable");
            $(".PPRM201DCImageRelation.btnopendetails").button("disable");
            //20201117 WL UPD E
            //20170905 YIN INS S
            $(".PPRM201DCImageRelation.Ruri").block({
                overlayCSS: {
                    opacity: 0,
                },
            });
            //20170905 YIN INS E
            me.btnflag = false;

            me.checkedData = function () {
                $(".PPRM201DCImageRelation.treeList").jstree("open_all");
                var liArr = new Array();
                var lis = $(".PPRM201DCImageRelation.treeList").find("li");
                for (var i = 0; i < lis.length; i++) {
                    var liClass = lis.eq(i).prop("class");
                    var liText = lis.eq(i).children("a")[0]
                        .lastChild.textContent;
                    var parent_id = lis.eq(i).prop("parentId");
                    var selected =
                        lis
                            .eq(i)
                            .children("a")[0]
                            .className.indexOf("jstree-checked") > -1
                            ? "true"
                            : "false";

                    if (
                        selected === "true" &&
                        liClass.indexOf("jstree-close") > -1
                    ) {
                        var liPath;
                        me.pathArr = [];
                        getPath(parent_id);
                        if (me.pathArr && me.pathArr.length) {
                            for (var j = me.pathArr.length - 1; j >= 0; j--) {
                                liPath =
                                    result["reports"]["path"] +
                                    "/" +
                                    me.pathArr[j] +
                                    "/";
                            }
                            if (liText) liPath += liText + "/";
                        } else {
                            if (liText)
                                liPath =
                                    result["reports"]["path"] +
                                    "/" +
                                    liText +
                                    "/";
                        }
                        var arr = {
                            IMAGE_FILE_NM: liText,
                            SAVE_PATH: liPath,
                        };
                        liArr.push(arr);
                    }
                }

                $(".PPRM201DCImageRelation.treeList").jstree("close_all");
                return liArr;
            };

            function getPath(parentId) {
                var text = $(".tree" + parentId).text();
                parentId = $(".tree" + parentId).prop("parentId");
                if (parentId) {
                    me.pathArr.push(text.replace(/(^\s*)|(\s*$)/g, ""));
                    getPath(parentId);
                }
            }

            function showView(data) {
                for (var key in data) {
                    tbl += "<ul>";
                    tbl +=
                        '<li class="jstree-node  jstree-closed" text="' +
                        data[key]["text"] +
                        '" parentId="' +
                        data[key]["parent_id"] +
                        '">';
                    tbl +=
                        '<a class="treeClick tree' +
                        data[key]["id"] +
                        '" text="' +
                        data[key]["text"] +
                        '" parentId="' +
                        data[key]["parent_id"] +
                        '">';
                    tbl += data[key]["text"];
                    tbl += "</a>";
                    if (data[key]["children"]) {
                        var dataArr = data[key]["children"];
                        showView(dataArr);
                    }
                    tbl += "</li>";
                    tbl += "</ul>";
                }
            }

            if (
                $(".PPRM201DCImageRelation.tblMeisai").css("display") == "block"
            ) {
                me.subMeisaiDisp();
            }
        };

        ajax.send(url, data, 0);
    };

    //'**********************************************************************
    //'処 理 名：日締№検索
    //'関 数 名：openHJMSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：日締№検索
    //'**********************************************************************
    me.openHJMSearch = function () {
        me.url = "PPRM/PPRM202DCSearch";
        var REFPRG = "PPRM201DCImageRelation";
        var FTCD = $(".PPRM201DCImageRelation.txtFromTenpoCD").val();
        var TTCD = $(".PPRM201DCImageRelation.txtToTenpoCD").val();
        var FDATE = $(".PPRM201DCImageRelation.txtHJMFromDate").val();
        var TDATE = $(".PPRM201DCImageRelation.txtHJMToDate").val();

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                REFPRG: REFPRG,
                FTCD: FTCD,
                TTCD: TTCD,
                FDATE: FDATE,
                TDATE: TDATE,
            })
        );

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                var HJMNo = o_PPRM_PPRM.PPRM202DCSearch.HJMNo;
                if (HJMNo != "" && HJMNo != undefined) {
                    $(".PPRM201DCImageRelation.txtHJMNo").val(HJMNo);
                    $(".PPRM201DCImageRelation.txtFromTenpoCD").val("");
                    $(".PPRM201DCImageRelation.txtToTenpoCD").val("");
                    $(".PPRM201DCImageRelation.lblFromTenpo").val("");
                    $(".PPRM201DCImageRelation.lblToTenpo").val("");
                    $(".PPRM201DCImageRelation.txtHJMFromDate").val("");
                    $(".PPRM201DCImageRelation.txtHJMToDate").val("");
                    spdClear();
                }
            }

            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM202DCSearch.before_close = before_close;
        };
        ajax.send(me.url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：印刷ﾌﾟﾚﾋﾞｭｰを行う
    //'関 数 名：me.openHijimeOut
    //'引 数 １：なし
    //'引 数 ２：なし
    //'戻 り 値：なし
    //'処理説明：日締出力帳票画面遷移
    //'**********************************************************************
    me.openHijimeOut = function () {
        var id = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM201DCImageRelation_spdList1").jqGrid(
            "getRowData",
            id
        );

        $(".PPRM201DCImageRelation.lblHJMNo").val(rowData["TEN_HJM_NO"]);

        var url = me.sys_id + "/" + "PPRM204DCOutput";

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "REF",
                TAISYO: 1,
                HNO: rowData["TEN_HJM_NO"],
                timestamp: new Date().getTime(),
            })
        );

        me.data = {};
        ajax.receive = function (result) {
            $(".PPRM201DCImageRelation.PPRM204_DC_Output_dialog").html(result);
        };
        ajax.send(url, me.data, 0);
    };

    //'**********************************************************************
    //'処 理 名：店舗コード検索（From）
    //'関 数 名：openFromTenpoSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索（From）
    //'**********************************************************************
    function openFromTenpoSearch() {
        // me.TKB = "1";
        me.url = "PPRM/PPRM705R4BusyoSearch";

        // //保存
        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: me.TKB,
        //     })
        // );

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    //Else
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM201DCImageRelation.txtFromTenpoCD").val(
                            busyocd
                        );
                    } else {
                        $(".PPRM201DCImageRelation.txtFromTenpoCD").val("");
                    }
                    if (busyonm != "") {
                        $(".PPRM201DCImageRelation.lblFromTenpo").val(busyonm);
                    } else {
                        $(".PPRM201DCImageRelation.lblFromTenpo").val("");
                    }
                    spdClear();
                }
            }
            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };
        ajax.send(me.url, me.data, 0);
    }

    //'**********************************************************************
    //'処 理 名：店舗コード検索（To）
    //'関 数 名：openToTenpoSearch
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：店舗コード検索（To）
    //'**********************************************************************
    function openToTenpoSearch() {
        // me.TKB = "1";
        me.url = "PPRM/PPRM705R4BusyoSearch";

        // //保存
        // localStorage.setItem(
        //     "requestdata",
        //     JSON.stringify({
        //         TKB: me.TKB,
        //     })
        // );

        var arr = {};

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            function before_close() {
                if (o_PPRM_PPRM.PPRM705R4BusyoSearch.flg == 1) {
                    //Else
                    var busyocd = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyocd;
                    var busyonm = o_PPRM_PPRM.PPRM705R4BusyoSearch.busyonm;
                    if (busyocd != "") {
                        $(".PPRM201DCImageRelation.txtToTenpoCD").val(busyocd);
                    } else {
                        $(".PPRM201DCImageRelation.txtToTenpoCD").val("");
                    }
                    if (busyonm != "") {
                        $(".PPRM201DCImageRelation.lblToTenpo").val(busyonm);
                    } else {
                        $(".PPRM201DCImageRelation.lblToTenpo").val("");
                    }
                    spdClear();
                }
            }
            $("." + me.id + "." + "dialogs").append(result);
            o_PPRM_PPRM.PPRM705R4BusyoSearch.before_close = before_close;
        };
        ajax.send(me.url, me.data, 0);
    }

    //'**********************************************************************
    //'処 理 名：イメージファイル開く
    //'関 数 名：ImgOpenFile
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：イメージファイル開く
    //'**********************************************************************
    function ImgOpenFile() {
        var url = "PPRM/PPRMjpgView";
        var id = $("#PPRM201DCImageRelation_spdList2").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#PPRM201DCImageRelation_spdList2").jqGrid(
            "getRowData",
            id
        );

        var strID = rowData["IMAGE_FILE_ID"];

        localStorage.setItem(
            "requestdata",
            JSON.stringify({
                MODE: "0",
                ID: strID,
                timestamp: new Date().getTime(),
            })
        );

        var data = {};

        ajax.receive = function (result) {
            $("." + me.id + "." + "dialogs").append(result);
        };
        ajax.send(url, data, 0);
    }

    //'**********************************************************************
    //'処 理 名：店舗コード(from)のblur
    //'関 数 名：FncGetBusyoNM
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：値変更時に店舗名を取得する
    //'**********************************************************************
    me.FncGetBusyoNM = function (objTenpoCD, objTenpoNM, FoT) {
        //20170908 ZHANGXIAOLEI UPD S
        // var TenpoNM = objTenpoNM.val();
        // me.url = me.sys_id + '/' + me.id + '/' + 'FncGetBusyoNM';
        // var arr =
        // {
        // 'txtTenpoCD' : objTenpoCD.val()
        // };
        // me.data =
        // {
        // request : arr,
        // };
        // ajax.receive = function(result)
        // {
        // result = eval('(' + result + ')');
        //
        // if (result['result'] == false)
        // {
        // clsComFnc.FncMsgBox("E9999", result["data"]);
        // return;
        // }
        // else
        // {
        // if (result["data"]["intRtnCD"] == 1)
        // {
        // objTenpoNM.val(result["data"]["strBusyoNM"]);
        // }
        // else
        // {
        // objTenpoNM.val("");
        // }
        try {
            var strCD = objTenpoCD.val();
            var TenpoNM = objTenpoNM.val();

            objTenpoNM.val("");

            if (strCD != "") {
                for (key in me.BusyoArr) {
                    if (strCD == me.BusyoArr[key]["TENPO_CD"]) {
                        objTenpoNM.val(me.BusyoArr[key]["BUSYO_NM"]);
                        break;
                    }
                }
            }
            //20170908 ZHANGXIAOLEI UPD E
            if (FoT == "F") {
                if (TenpoNM != objTenpoNM.val()) {
                    $(".PPRM201DCImageRelation.txtToTenpoCD").trigger("focus");
                }
            } else {
                if (TenpoNM != objTenpoNM.val()) {
                    $(".PPRM201DCImageRelation.txtHJMFromDate").trigger(
                        "focus"
                    );
                }
            }
            //20170908 ZHANGXIAOLEI UPD S
            // }
            // };
            // ajax.send(me.url, me.data, 0);
        } catch (e) {}
        //20170908 ZHANGXIAOLEI UPD E
    };

    //'**********************************************************************
    //'処 理 名：変更時スプレッドクリア
    //'関 数 名：spdClear
    //'引 数 　：なし
    //'戻 り 値：なし
    //'**********************************************************************
    function spdClear() {
        $(".PPRM201DCImageRelation.List").css("display", "none");
        $(".PPRM201DCImageRelation.tblNyuryoku").css("display", "none");
        $(".PPRM201DCImageRelation.tblMeisai").css("display", "none");
    }

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_PPRM_PPRM201DCImageRelation = new PPRM.PPRM201DCImageRelation();
    o_PPRM_PPRM201DCImageRelation.load();
    o_PPRM_PPRM.PPRM201DCImageRelation = o_PPRM_PPRM201DCImageRelation;
});
