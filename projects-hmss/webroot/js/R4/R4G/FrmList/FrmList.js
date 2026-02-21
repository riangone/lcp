/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                    GSDL
 * 20201117            bug                          AJAX.SEND パラメータ数                     lqs
 * 20201117            bug                          ChromeとFireFoxのgridの行高が違います。     lqs
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmList");

R4.FrmList = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "FrmList";
    me.sys_id = "R4G";
    me.data = "";
    me.intFlgCnt = 0;
    me.CustomerData = "";
    me.blnHedderClear = true;
    me.strJokenCmn = "";
    me.strJokenKna = "";
    me.strJokenEmp = "";
    me.strSetCmn = "";
    me.statusFlag = false;
    me.intBtnKind = "";
    me.DataSlect = "";
    me.PrpFlg = false;
    me.FrmMainMenu = null;
    me.FrmListSelect = null;
    me.InfoTbl = "";
    me.methodFlag = false;
    me.CMNNO = "";

    me.colModel1 = [
        {
            name: "defaultCol",
            label: "　　　　",
            index: "defaultCol",
            width: 125,
            align: "right",
            sortable: false,
        },
        {
            name: "aa",
            label: "付属品",
            index: "aa",
            width: 165,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            sortable: false,
        },
        {
            name: "bb",
            label: "特別仕様",
            index: "bb",
            width: 165,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            sortable: false,
        },
    ];

    me.colModel2 = [
        {
            name: "GYOUSYA_CD",
            label: "取引先コード",
            index: "GYOUSYA_CD",
            width: 125,
            align: "left",
            sortable: false,
        },
        {
            name: "GYOUSYA_NM",
            label: "取引先名",
            index: "GYOUSYA_NM",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "GAICYU_ZITU",
            label: "外注実原価",
            index: "GAICYU_ZITU",
            width: 160,
            align: "right",
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
            sortable: false,
        },
    ];

    me.mydata = [
        {
            defaultCol: "定価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "値引",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "契約価格",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "部品社内価格",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "部品社内実原価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "外注社内原価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "外注社内実原価",
            aa: "",
            bb: "",
        },
    ];

    me.mydataClear = [
        {
            defaultCol: "定価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "値引",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "契約価格",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "部品社内価格",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "部品社内実原価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "外注社内原価",
            aa: "",
            bb: "",
        },
        {
            defaultCol: "外注社内実原価",
            aa: "",
            bb: "",
        },
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmList.cmdSearch",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmList.cmdUpdate",
        type: "button",
        handle: "",
    });

    //20180515 YIN INS S
    me.controls.push({
        id: ".FrmList.cmdsave",
        type: "button",
        handle: "",
    });
    //20180515 YIN INS E

    me.controls.push({
        id: ".FrmList.cmdPrintKasou",
        type: "button",
        enable: "false",
        handle: "",
    });

    me.controls.push({
        id: ".FrmList.cmdOption",
        type: "button",
        handle: "",
        enable: "false",
    });

    me.controls.push({
        id: ".FrmList.cmdSpecial",
        type: "button",
        handle: "",
        enable: "false",
    });

    me.controls.push({
        id: ".FrmList.cmdDelete",
        type: "button",
        enable: "false",
        handle: "",
    });

    me.controls.push({
        id: ".FrmList.cmdBack",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    me.init_control = function () {
        /**********************************************************************
         '処理概要：フォームロード
         '**********************************************************************/
        base_init_control();

        $(".FrmList.txtCMNNO").trigger("focus");

        $("<div></div>")
            .prop("id", "FrmListDialogDiv")
            .insertAfter($("#FrmList"));
        $("<div></div>")
            .prop("id", "FrmListOptionDialogDiv")
            .insertAfter($("#FrmList"));
        $("<div></div>")
            .prop("id", "FrmListSpecialDialogDiv")
            .insertAfter($("#FrmList"));

        $("#FrmListDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: 480,
            width: 650,
            resizable: false,
            open: function () {
                me.FrmListSelect = null;
            },
            close: function () {
                me.fncFrmListDialogClose();
            },
        });

        $("#FrmListOptionDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 556 : 720,
            width: me.ratio === 1.5 ? 1260 : 1280,
            resizable: false,
            open: function () {
                me.PrpFlg = false;
            },
            close: function () {
                me.closeSpecial();
            },
        });

        $("#FrmListSpecialDialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 556 : 720,
            width: me.ratio === 1.5 ? 1260 : 1280,
            resizable: false,
            open: function () {
                me.PrpFlg = false;
            },
            close: function () {
                me.closeSpecial();
            },
        });

        me.subFormClear(me.blnHedderClear);
    };

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    me.closeSpecial = function () {
        $(".FrmList.cmdSpecial").button("enable");
        $(".FrmList.cmdOption").button("enable");

        if (me.PrpFlg) {
            $("#FrmList_sprCustomer").jqGrid("clearGridData");

            me.fncStandardInfoSet();
        }
    };

    me.leaveFrmListPage = function () {
        var Text1 = $(".FrmList.txtCMNNO").val();
        var Text2 = $(".FrmList.txtSiyFgn").val();
        var Text3 = $(".FrmList.txtEmpNO").val();
        var Text4 = $(".FrmList.txtCopyStart").val();
        var Text5 = $(".FrmList.txtCopyEnd").val();
        if (
            Text1 != "" ||
            Text2 != "" ||
            Text3 != "" ||
            Text4 != "" ||
            Text5 != ""
        ) {
            me.subFormClear(me.blnHedderClear);
            me.subClearFormGrid();
        }

        me.deleteWKClear(true);
    };

    me.deleteWKClear = function (meFlag) {
        //ワークテーブルを削除する
        console.log("ワークテーブルを削除する");
        var funcName = "deleteWKClear";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        $(".FrmList.txtCMNNO").trigger("focus");
        $(".FrmList.txtCMNNO").select();
        $(".FrmList.cmdSearch").button("enable");

        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (meFlag) {
                $("#FrmListOptionDialogDiv").remove();
                $("#FrmListSpecialDialogDiv").remove();
                $("#FrmListDialogDiv").remove();
                me.FrmR4GMainMenu.getHtml(
                    me.FrmR4GMainMenu.setNodeID,
                    me.FrmR4GMainMenu.setfrmNM,
                    me.FrmR4GMainMenu.setUrl
                );
            } else {
                me.subClearFormGrid();
            }
        };
        ajax.send(url, "", 0);
    };

    me.Validating = function () {
        var Text1 = $(".FrmList.txtCMNNO").val().trimEnd();
        var Text2 = $(".FrmList.txtSiyFgn").val().trimEnd();
        var Text3 = $(".FrmList.txtEmpNO").val().trimEnd();
        if (
            Text1 != me.strSetCmn ||
            Text2 != me.strJokenKna ||
            Text3 != me.strJokenEmp
        ) {
            me.subFormClear(false);
            me.subClearFormGrid();
            return false;
        }

        return true;
    };

    $(".FrmList.txtCopyEnd").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.txtCopyStart").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.txtSiyFgn").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.txtEmpNO").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.txtCMNNO").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.cboMemo").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.txtHaisouSiji").on("focus", function () {
        me.Validating();
    });

    $(".FrmList.cboMemo").change(function () {
        var cboMemoVal = $(".FrmList.cboMemo").val();
        $(".FrmList.txtHaisouSiji").val(cboMemoVal);
    });

    $(".FrmList.cmdOption").click(function () {
        $(".FrmList.cmdOption").button("disable");
        var rtn = me.Validating();

        if (rtn) {
            var frmId = "FrmOptionInput";
            var url = me.sys_id + "/" + frmId + "/index";

            console.log("dialogOption");

            ajax.receive = function (result) {
                //console.log(result);

                $("#FrmListOptionDialogDiv").dialog(
                    "option",
                    "title",
                    "新車：付属品入力"
                );
                $("#FrmListOptionDialogDiv").dialog("open");
                $("#FrmListOptionDialogDiv").html(result);
            };
            ajax.send(url, "", 0);
            ajax.beforeLogin = me.enableOption;
        } else {
            return;
        }
    });

    $(".FrmList.cmdSpecial").click(function () {
        $(".FrmList.cmdSpecial").button("disable");
        var rtn = me.Validating();
        if (rtn) {
            var frmId = "FrmSpecialInput";
            var url = me.sys_id + "/" + frmId + "/index";
            var data = {
                url: frmId,
            };

            ajax.receive = function (result) {
                $("#FrmListSpecialDialogDiv").dialog(
                    "option",
                    "title",
                    "新車：特別仕様入力"
                );
                $("#FrmListSpecialDialogDiv").dialog("open");
                $("#FrmListSpecialDialogDiv").html(result);
            };
            ajax.send(url, data, 0);
            ajax.beforeLogin = me.enableSpecial;
        } else {
            return;
        }
    });

    $(".FrmList.cmdDelete").click(function () {
        var rtn = me.Validating();
        if (!rtn) {
            return;
        } else {
            //架装ﾃﾞｰﾀの削除
            console.log("架装ﾃﾞｰﾀの削除");
            var txtCMNNOVal = $(".FrmList.txtCMNNO").val().trimEnd();
            var lblKasouNOVal = $(".FrmList.lblKasouNO").val().trimEnd();

            if (txtCMNNOVal == "" && lblKasouNOVal == "") {
                //削除対象が選択されていない場合
                $(".FrmList.txtCMNNO").trigger("focus");
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("W9999", "削除対象が存在しません");
                return;
            }

            var funcName = "fncSelHkasou";
            var url = me.sys_id + "/" + me.id + "/" + funcName;
            var arrayVal = {
                CMN_NO: txtCMNNOVal,
                KASOUNO: lblKasouNOVal,
            };
            me.data = {
                request: arrayVal,
            };

            ajax.receive = function (result) {
                console.log(result);
                var jsonResult = {};
                var txtResult = '{ "json" : [' + result + "]}";
                jsonResult = eval("(" + txtResult + ")");
                if (jsonResult.json[0]["result"] == false) {
                    clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                    return;
                }
                if (jsonResult.json[0]["cRow"] != "noData") {
                    clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteKasou;
                    clsComFnc.FncMsgBox("QY004");
                }

                if (jsonResult.json[0]["cRow"] == "noData") {
                    clsComFnc.FncMsgBox("W9999", "削除対象が存在しません");
                }
            };
            ajax.send(url, me.data, 0);
        }
    });

    me.fncCopyInputChk = function (param1) {
        /**********************************************************************
         '処 理 名：コピー元・コピー先の入力チェック
         '関 数 名：fncCopyInputChk
         '引    数：なし
         '戻 り 値：True：正常終了 False:異常終了
         '処理説明：架装明細テーブルから原価を抽出
         '**********************************************************************/
        console.log("fncM41E12Checkコピー元・コピー先の入力チェック:");
        var funcName = "fncM41E12Check";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        var arrayVal = {
            CMN_NO: param1,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            //マスタ存在ﾁｪｯｸ
            if (jsonResult.json[0]["cRow"] == "noData") {
                //架装明細ﾃｰﾌﾞﾙ存在ﾁｪｯｸ
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncKasouTblCheck;
                clsComFnc.MessageBox(
                    "注文書付属品マスタに該当データが存在しません！登録しますか？",
                    clsComFnc.GSYSTEM_NAME,
                    "YesNo",
                    "Information",
                    clsComFnc.MessageBoxDefaultButton.Button2
                );
            }
            if (jsonResult.json[0]["cRow"] != "noData") {
                //架装明細ﾃｰﾌﾞﾙ存在ﾁｪｯｸ
                me.fncKasouTblCheck();
            }

            ajax.beforeLogin = me.buttonableUpdate;
        };
        ajax.send(url, me.data, 0);
    };

    $(".FrmList.cmdUpdate").click(function () {
        /**********************************************************************
         '処理概要：更新ボタン押下時
         '**********************************************************************/
        console.log("更新ボタン押下時");
        $(".FrmList.cmdUpdate").button("disable");
        var txtCopyStartVal = $(".FrmList.txtCopyStart").val().trimEnd();
        var txtCopyEndVal = $(".FrmList.txtCopyEnd").val().trimEnd();
        if (txtCopyStartVal == "") {
            clsComFnc.ObjFocus = $(".FrmList.txtCopyStart");
            clsComFnc.FncMsgBox("W0001", "コピー元 注文書番号");
        } else if (txtCopyEndVal == "") {
            clsComFnc.ObjFocus = $(".FrmList.txtCopyEnd");
            clsComFnc.FncMsgBox("W0001", "コピー先 注文書番号");
        } else {
            //入力チェック
            me.fncCopyInputChk(txtCopyStartVal, txtCopyEndVal);
        }
        $(".FrmList.cmdUpdate").button("enable");
    });

    $(".FrmList.cmdSearch").click(function () {
        $(".FrmList.cmdSearch").button("disable");
        me.cmdSearch_Click(true);
    });

    $(".FrmList.cmdPrintKasou").click(function () {
        var rtn = me.Validating();
        if (!rtn) {
            return;
        } else {
            //20180515 YIN INS S
            $(".FrmList.cmdsave").button("disable");
            //20180515 YIN INS E
            $(".FrmList.cmdPrintKasou").button("disable");
            $(".FrmList.cmdSearch").button("disable");
            $(".FrmList.cmdUpdate").button("disable");
            $(".FrmList.cmdOption").button("disable");
            $(".FrmList.cmdSpecial").button("disable");
            $(".FrmList.cmdDelete").button("disable");
            me.cmdPrintKasou_Click();
        }
    });

    //20180515 YIN INS S
    $(".FrmList.cmdsave").click(function () {
        var rtn = me.Validating();
        if (!rtn) {
            return;
        } else {
            clsComFnc.MsgBoxBtnFnc.Yes = me.cmdsave_Click;
            clsComFnc.FncMsgBox("QY999", "保存します。よろしいですか？");
        }
    });
    //20180515 YIN INS E

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    $("#FrmList_sprMoneyList").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,

        //20180305 ciyuanchen UPD S
        // height : 182,
        // 20201117 lqs upd S
        // height : 165,
        height: me.ratio === 1.5 ? 125 : 155,
        // 20201117 lqs upd E
        //20180305 ciyuanchen UPD E

        colModel: me.colModel1,
    });

    $("#FrmList_sprCustomer").jqGrid({
        datatype: "local",
        emptyRecordRow: false,
        //20180305 ciyuanchen UPD S
        // height : 182,
        // 20201117 lqs upd S
        // height : 165,
        height: me.ratio === 1.5 ? 125 : 155,
        // 20201117 lqs upd E
        //20180305 ciyuanchen UPD E
        colModel: me.colModel2,
        rownumbers: true,
    });

    for (var i = 0; i <= me.mydata.length; i++)
        $("#FrmList_sprMoneyList").jqGrid("addRowData", i + 1, me.mydata[i]);

    var columns = {
        GYOUSYA_CD: "",
        GYOUSYA_NM: "",
        GAICYU_ZITU: "",
    };

    for (var i = 0; i <= 6; i++) {
        $("#FrmList_sprCustomer").jqGrid("addRowData", i + 1, columns);
    }

    me.subFormClear = function (blnHedderClear) {
        if (blnHedderClear) {
            $(".FrmList.txtCMNNO").val("");
            $(".FrmList.txtSiyFgn").val("");
            $(".FrmList.txtEmpNO").val("");
            //2014/02/15 Delete Y0009 Start
            //$(".FrmList.txtCopyStart").val('');
            //2014/02/15 Delete Y0009 End
            $(".FrmList.txtCopyEnd").val("");
        }

        $(".FrmList.lblKeiyakusya").val("");
        $(".FrmList.lblBusyoCD").val("");
        $(".FrmList.lblBusyoNM").val("");
        $(".FrmList.lblKasouNO").val("");
        $(".FrmList.lblSiyosya").val("");
        $(".FrmList.lblSyainNO").val("");
        $(".FrmList.lblSyainNM").val("");
        $(".FrmList.lblKosyo").val("");
        $(".FrmList.lblSiyosyaKN").val("");
        $(".FrmList.lblHanbaitenNO").val("");
        $(".FrmList.lblHanbaitenNM").val("");
        $(".FrmList.lblZei").val("");
        $(".FrmList.txtUPD_DAT").val("");

        $(".FrmList.lblGenkaGK").html("");
        $(".FrmList.lblGaiJituGen").html("");
        $(".FrmList.lblSyadaiCarNO").val("");
        $(".FrmList.txtHaisouSiji").val("");

        var tmpId = ".FrmList.cboMemo option[value='']";
        $(tmpId).prop("selected", true);

        $(".FrmList.txtHaisouSiji").prop("disabled", "disabled");
        $(".FrmList.cboMemo").prop("disabled", "disabled");

        $(".FrmList.cmdDelete").button("disable");
        $(".FrmList.cmdSpecial").button("disable");
        $(".FrmList.cmdOption").button("disable");
        $(".FrmList.cmdPrintKasou").button("disable");
        //20180515 YIN INS S
        $(".FrmList.cmdsave").button("disable");
        //20180515 YIN INS E
    };

    me.subClearFormGrid = function () {
        $("#FrmList_sprCustomer").jqGrid("clearGridData");
        $("#FrmList_sprMoneyList").jqGrid("clearGridData");
        for (var i = 0; i <= me.mydataClear.length; i++)
            $("#FrmList_sprMoneyList").jqGrid(
                "addRowData",
                i + 1,
                me.mydataClear[i]
            );

        var columns = {
            GYOUSYA_CD: "",
            GYOUSYA_NM: "",
            GAICYU_ZITU: "",
        };

        for (var i = 0; i <= 6; i++) {
            $("#FrmList_sprCustomer").jqGrid("addRowData", i + 1, columns);
        }
    };

    //20180515 YIN INS S
    /**********************************************************************
     処理概要：架装明細保存
     **********************************************************************/
    me.cmdsave_Click = function () {
        $(".FrmList.cmdsave").button("disable");
        $(".FrmList.cmdPrintKasou").button("disable");
        $(".FrmList.cmdSearch").button("disable");
        $(".FrmList.cmdUpdate").button("disable");
        $(".FrmList.cmdOption").button("disable");
        $(".FrmList.cmdSpecial").button("disable");
        $(".FrmList.cmdDelete").button("disable");

        var funcName = "cmdsave";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var init_mark = 0;

        var strChumon = $(".FrmList.txtCMNNO").val().trimEnd();
        var strKasou = $(".FrmList.lblKasouNO").val().trimEnd();
        var strHaisouSiji = $(".FrmList.txtHaisouSiji").val().trimEnd();
        var strSyadaiKata = $(".FrmList.lblSyadaiKata").html().trimEnd();
        var strCar_NO = $(".FrmList.lblCar_NO").html().trimEnd();
        var strHanbaiSyasyu = $(".FrmList.lblHanbaiSyasyu").html().trimEnd();
        var strKosyo = $(".FrmList.lblKosyo").val().trimEnd();
        var strSyasyu = $(".FrmList.lblSyasyu_NM").val().trimEnd();
        var strKeiyakusya = $(".FrmList.lblKeiyakusya").val().trimEnd();
        var strBusyoNM = $(".FrmList.lblBusyoNM").val().trimEnd();
        var strSyainNM = $(".FrmList.lblSyainNM").val().trimEnd();
        var strSyasyu_NM = $(".FrmList.lblSyasyu_NM").html().trimEnd();

        var insertArray = {
            strChumon: strChumon,
            strKasou: strKasou,
            strHaisouSiji: strHaisouSiji,
            strSyadaiKata: strSyadaiKata,
            strCar_NO: strCar_NO,
            strHanbaiSyasyu: strHanbaiSyasyu,
            strKosyo: strKosyo,
            strSyasyu: strSyasyu,
            strKeiyakusya: strKeiyakusya,
            strBusyoNM: strBusyoNM,
            strSyainNM: strSyainNM,
            strSyasyu_NM: strSyasyu_NM,
            CustomerData: me.CustomerData,
        };

        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (typeof result["resultLog1"] != "undefined") {
                clsComFnc.MsgBoxBtnFnc.Close = function () {
                    clsComFnc.MsgBoxBtnFnc.Close = function () {
                        if (typeof result["resultLog2"] != "undefined") {
                            clsComFnc.MsgBoxBtnFnc.Close = function () {
                                clsComFnc.FncMsgBox(
                                    result["resultLog2"]["MsgID"],
                                    result["resultLog2"]["Msg"]
                                );
                            };
                            clsComFnc.FncMsgBox(
                                result["resultLog2"]["MsgID"],
                                result["resultLog2"]["data"]
                            );
                        }
                    };
                    clsComFnc.FncMsgBox(
                        result["resultLog1"]["MsgID"],
                        result["resultLog1"]["Msg"]
                    );
                };
                clsComFnc.FncMsgBox(
                    result["resultLog1"]["MsgID"],
                    result["resultLog1"]["data"]
                );
            }
            switch (result["result"]) {
                case "warning":
                case false:
                    $(".FrmList.cmdsave").button("enable");
                    $(".FrmList.cmdPrintKasou").button("enable");
                    $(".FrmList.cmdSearch").button("enable");
                    $(".FrmList.cmdUpdate").button("enable");
                    $(".FrmList.cmdOption").button("enable");
                    $(".FrmList.cmdSpecial").button("enable");
                    $(".FrmList.cmdDelete").button("enable");
                    clsComFnc.ObjFocus = $(".FrmList.cmdPrintKasou");
                    clsComFnc.FncMsgBox(result["MsgID"]);
                    break;
                case true:
                    me.subFormClear(me.blnHedderClear);
                    me.subClearFormGrid();
                    $(".FrmList.cmdSearch").button("enable");
                    $(".FrmList.cmdUpdate").button("enable");
                    $(".FrmList.txtCMNNO").trigger("focus");
                    clsComFnc.FncMsgBox("I9999", "保存しました。");
                    break;
                default:
                    break;
            }
        };
        ajax.send(url, me.data, init_mark);

        ajax.beforeLogin = function () {
            $(".FrmList.cmdsave").button("enable");
            $(".FrmList.cmdPrintKasou").button("enable");
            $(".FrmList.cmdSearch").button("enable");
            $(".FrmList.cmdUpdate").button("enable");
            $(".FrmList.cmdOption").button("enable");
            $(".FrmList.cmdSpecial").button("enable");
            $(".FrmList.cmdDelete").button("enable");
        };
    };
    //20180515 YIN INS E
    /**********************************************************************
     処理概要：架装明細プレビュー画面表示
     **********************************************************************/
    me.cmdPrintKasou_Click = function () {
        var funcName = "cmdPrintKasouClick";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var init_mark = 0;

        var strChumon = $(".FrmList.txtCMNNO").val().trimEnd();
        var strKasou = $(".FrmList.lblKasouNO").val().trimEnd();
        var strHaisouSiji = $(".FrmList.txtHaisouSiji").val().trimEnd();
        var strSyadaiKata = $(".FrmList.lblSyadaiKata").html().trimEnd();
        var strCar_NO = $(".FrmList.lblCar_NO").html().trimEnd();
        var strHanbaiSyasyu = $(".FrmList.lblHanbaiSyasyu").html().trimEnd();
        var strKosyo = $(".FrmList.lblKosyo").val().trimEnd();
        var strSyasyu = $(".FrmList.lblSyasyu_NM").val().trimEnd();
        var strKeiyakusya = $(".FrmList.lblKeiyakusya").val().trimEnd();
        var strBusyoNM = $(".FrmList.lblBusyoNM").val().trimEnd();
        var strSyainNM = $(".FrmList.lblSyainNM").val().trimEnd();
        var strSyasyu_NM = $(".FrmList.lblSyasyu_NM").html().trimEnd();

        var insertArray = {
            strChumon: strChumon,
            strKasou: strKasou,
            strHaisouSiji: strHaisouSiji,
            strSyadaiKata: strSyadaiKata,
            strCar_NO: strCar_NO,
            strHanbaiSyasyu: strHanbaiSyasyu,
            strKosyo: strKosyo,
            strSyasyu: strSyasyu,
            strKeiyakusya: strKeiyakusya,
            strBusyoNM: strBusyoNM,
            strSyainNM: strSyainNM,
            strSyasyu_NM: strSyasyu_NM,
            CustomerData: me.CustomerData,
        };

        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            result = $.parseJSON(result);
            if (typeof result["resultLog1"] != "undefined") {
                clsComFnc.MsgBoxBtnFnc.Close = function () {
                    clsComFnc.MsgBoxBtnFnc.Close = function () {
                        if (typeof result["resultLog2"] != "undefined") {
                            clsComFnc.MsgBoxBtnFnc.Close = function () {
                                clsComFnc.FncMsgBox(
                                    result["resultLog2"]["MsgID"],
                                    result["resultLog2"]["Msg"]
                                );
                            };
                            clsComFnc.FncMsgBox(
                                result["resultLog2"]["MsgID"],
                                result["resultLog2"]["data"]
                            );
                        }
                    };
                    clsComFnc.FncMsgBox(
                        result["resultLog1"]["MsgID"],
                        result["resultLog1"]["Msg"]
                    );
                };
                clsComFnc.FncMsgBox(
                    result["resultLog1"]["MsgID"],
                    result["resultLog1"]["data"]
                );
            }
            switch (result["result"]) {
                case "warning":
                case false:
                    //20180515 YIN INS S
                    $(".FrmList.cmdsave").button("enable");
                    //20180515 YIN INS E
                    $(".FrmList.cmdPrintKasou").button("enable");
                    $(".FrmList.cmdSearch").button("enable");
                    $(".FrmList.cmdUpdate").button("enable");
                    $(".FrmList.cmdOption").button("enable");
                    $(".FrmList.cmdSpecial").button("enable");
                    $(".FrmList.cmdDelete").button("enable");
                    clsComFnc.ObjFocus = $(".FrmList.cmdPrintKasou");
                    clsComFnc.FncMsgBox(result["MsgID"]);
                    break;
                case true:
                    var objrpt = result["report"];
                    window.open(objrpt);
                    me.subFormClear(me.blnHedderClear);
                    me.subClearFormGrid();
                    $(".FrmList.cmdSearch").button("enable");
                    $(".FrmList.cmdUpdate").button("enable");
                    $(".FrmList.txtCMNNO").trigger("focus");
                    break;
                default:
                    break;
            }
        };
        ajax.send(url, me.data, init_mark);

        ajax.beforeLogin = function () {
            //20180515 YIN INS S
            $(".FrmList.cmdsave").button("enable");
            //20180515 YIN INS E
            $(".FrmList.cmdPrintKasou").button("enable");
            $(".FrmList.cmdSearch").button("enable");
            $(".FrmList.cmdUpdate").button("enable");
            $(".FrmList.cmdOption").button("enable");
            $(".FrmList.cmdSpecial").button("enable");
            $(".FrmList.cmdDelete").button("enable");
        };
    };

    me.fncCustomerSelect = function () {
        console.log("fncCustomerSelect架装依頼先：");
        //架装依頼先
        var funcName = "fncCustomerSelect";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var txtCMNNOVal = $(".FrmList.txtCMNNO").val();
        var lblKasouNO = $(".FrmList.lblKasouNO").val();
        txtCMNNOVal = txtCMNNOVal.trimEnd();

        var insertArray = {
            CMN_NO: txtCMNNOVal,
            KASOUNO: lblKasouNO,
        };

        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }
            if (jsonResult.json[0]["cRow"] == "noData") {
                var objCusDr = Array();
                for (var i = 0; i < 7; i++) {
                    var columns = {
                        GYOUSYA_CD: "",
                        GYOUSYA_NM: "",
                        GAICYU_ZITU: "",
                    };

                    objCusDr.push(columns);
                }

                me.CustomerData = objCusDr;
                for (var i = 0; i <= objCusDr.length; i++) {
                    $("#FrmList_sprCustomer").jqGrid(
                        "addRowData",
                        i + 1,
                        objCusDr[i]
                    );
                }

                $(".FrmList.lblGaiJituGen").html("0");
                me.fncKasouTblCheck1();
                return;
            }
            var objCusDr = Array();
            var lngGaichuGK = 0;

            for (key in jsonResult.json[0]["data"]) {
                var columns = {
                    GYOUSYA_CD: "",
                    GYOUSYA_NM: "",
                    GAICYU_ZITU: "",
                };
                // columns['defaultCol'] = parseInt(key) + 1;
                columns["GYOUSYA_CD"] =
                    jsonResult.json[0]["data"][key]["GYOUSYA_CD"];
                columns["GYOUSYA_NM"] =
                    jsonResult.json[0]["data"][key]["GYOUSYA_NM"];
                columns["GAICYU_ZITU"] =
                    jsonResult.json[0]["data"][key]["GAICYU_ZITU"];
                objCusDr.push(columns);

                //外注実原価合計を算出する
                lngGaichuGK =
                    parseInt(lngGaichuGK) +
                    parseInt(jsonResult.json[0]["data"][key]["GAICYU_ZITU"]);
            }
            if (jsonResult.json[0]["data"].length < 7) {
                var lngColumns = 7 - jsonResult.json[0]["data"].length;
                for (k = 0; k < lngColumns; k++) {
                    var columns = {
                        GYOUSYA_CD: "",
                        GYOUSYA_NM: "",
                        GAICYU_ZITU: "",
                    };
                    objCusDr.push(columns);
                }
            }

            me.CustomerData = objCusDr;
            for (var i = 0; i <= objCusDr.length; i++) {
                $("#FrmList_sprCustomer").jqGrid(
                    "addRowData",
                    i + 1,
                    objCusDr[i]
                );
            }
            lngGaichuGK = lngGaichuGK.toString();
            lngGaichuGK = lngGaichuGK.numFormat();
            $(".FrmList.lblGaiJituGen").html(lngGaichuGK);
            me.fncKasouTblCheck1();
        };
        ajax.send(url, me.data, 0);
    };

    me.fncMoneyKasouMeisai = function (data, txtCMNNOVal) {
        console.log("fncMoneyKasouMeisai");
        var funcName = "fncMoneyKasouMeisai";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var lblKasouNO = $(".FrmList.lblKasouNO").val();
        me.mydata = data;
        var insertArray = {
            CMN_NO: txtCMNNOVal,
            KASOUNO: lblKasouNO,
        };

        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            console.log("********************");
            console.log(result);
            console.log("********************");
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }
            var tmpArr = jsonResult.json[0]["cRow"].split("+");
            if (tmpArr[0] == "noData" && tmpArr[1] == "noData") {
                // return;
            } else if (tmpArr[1] != "noData" && tmpArr[0] == "noData") {
                me.mydata[3]["bb"] = jsonResult.json[0]["data"][0]["SYA_GEN"];
                me.mydata[4]["bb"] = jsonResult.json[0]["data"][0]["SYAJITU"];
                me.mydata[5]["bb"] =
                    jsonResult.json[0]["data"][0]["GAI_SYA_GEN"];
                me.mydata[6]["bb"] =
                    jsonResult.json[0]["data"][0]["GAI_SYAJITU"];
            } else if (tmpArr[0] != "noData" && tmpArr[1] == "noData") {
                me.mydata[3]["aa"] = jsonResult.json[0]["data"][0]["SYA_GEN"];
                me.mydata[4]["aa"] = jsonResult.json[0]["data"][0]["SYAJITU"];
                me.mydata[5]["aa"] =
                    jsonResult.json[0]["data"][0]["GAI_SYA_GEN"];
                me.mydata[6]["aa"] =
                    jsonResult.json[0]["data"][0]["GAI_SYAJITU"];
            } else {
                me.mydata[3]["aa"] = jsonResult.json[0]["data"][0]["SYA_GEN"];
                me.mydata[4]["aa"] = jsonResult.json[0]["data"][0]["SYAJITU"];
                me.mydata[5]["aa"] =
                    jsonResult.json[0]["data"][0]["GAI_SYA_GEN"];
                me.mydata[6]["aa"] =
                    jsonResult.json[0]["data"][0]["GAI_SYAJITU"];
                me.mydata[3]["bb"] = jsonResult.json[0]["data"][1]["SYA_GEN"];
                me.mydata[4]["bb"] = jsonResult.json[0]["data"][1]["SYAJITU"];
                me.mydata[5]["bb"] =
                    jsonResult.json[0]["data"][1]["GAI_SYA_GEN"];
                me.mydata[6]["bb"] =
                    jsonResult.json[0]["data"][1]["GAI_SYAJITU"];
            }

            var data = $("#FrmList_sprMoneyList").jqGrid("getDataIDs");
            for (key in data) {
                $("#FrmList_sprMoneyList").jqGrid("delRowData", data[key]);
            }
            for (var i = 0; i <= me.mydata.length; i++) {
                $("#FrmList_sprMoneyList").jqGrid(
                    "addRowData",
                    i + 1,
                    me.mydata[i]
                );
            }

            //社内原価合計を表示
            var lblGenkaGKVal1 = clsComFnc
                .FncNv(me.mydata[3]["bb"])
                .toString()
                .replace(/\,/g, "");
            var lblGenkaGKVal2 = clsComFnc
                .FncNv(me.mydata[5]["bb"])
                .toString()
                .replace(/\,/g, "");
            if (lblGenkaGKVal1 == "" || lblGenkaGKVal1 == null) {
                lblGenkaGKVal1 = 0;
            }
            if (lblGenkaGKVal2 == "" || lblGenkaGKVal2 == null) {
                lblGenkaGKVal2 = 0;
            }
            var blGenkaGKVal =
                parseInt(lblGenkaGKVal1) + parseInt(lblGenkaGKVal2);
            blGenkaGKVal = blGenkaGKVal.toString();
            blGenkaGKVal = blGenkaGKVal.numFormat();
            $(".FrmList.lblGenkaGK").html(blGenkaGKVal);

            //架装依頼先
            me.fncCustomerSelect();
        };
        ajax.send(url, me.data, 0);
    };

    me.subSpreadReShow = function (jsonResult, SelectRow) {
        // スプレッドにﾃﾞｰﾀを表示する
        console.log("subSpreadReShowスプレッドにﾃﾞｰﾀを表示する:");
        var funcName = "fncMoneyM41E12";
        //金額を抽出
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var txtCMNNOVal = $(".FrmList.txtCMNNO").val();
        txtCMNNOVal = txtCMNNOVal.trimEnd();
        me.mydata = new Array();
        var insertArray = {
            CMN_NO: txtCMNNOVal,
        };
        me.data = {
            request: insertArray,
        };

        //金額表(付属品)の表示
        ajax.receive = function (result) {
            var Result = {};
            var txtResult = '{ "json" : [' + result + "]}";
            Result = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", Result.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }
            //基本情報を表示する
            $(".FrmList.lblKeiyakusya").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["KEIYAKUSYA"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //契約者
            $(".FrmList.lblSiyosya").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["SIYOSYA"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //使用者
            $(".FrmList.lblSiyosyaKN").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["CSRKNANM"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //使用者カナ
            $(".FrmList.lblBusyoCD").val(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["KYOTN_CD"]
                )
            );
            //部署コード
            $(".FrmList.lblBusyoNM").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["BUSYOMEI"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //部署名
            $(".FrmList.lblSyainNO").val(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["HNB_TAN_EMP_NO"]
                )
            );
            //社員コード
            $(".FrmList.lblSyainNM").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["SYAIN"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //社員名
            $(".FrmList.lblHanbaitenNO").val(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["HNB_KTN_CD"]
                )
            );
            //販売店コード
            $(".FrmList.lblHanbaitenNM").val(
                clsComFnc
                    .fncGetFixVal(
                        clsComFnc.FncNv(
                            jsonResult.json[0]["data"][SelectRow]["HANBAITEN"]
                        ),
                        30
                    )
                    .trimEnd()
            );
            //販売店名
            $(".FrmList.lblKasouNO").val(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["KASOU_NO"]
                )
            );
            //伝票NO

            if (
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["UPD_DATE"]
                ) != ""
            ) {
                $(".FrmList.txtUPD_DAT").val(
                    jsonResult.json[0]["data"][SelectRow]["UPD_DATE"]
                );
            } else {
                $(".FrmList.txtUPD_DAT").val("");
            }
            //前回発効日
            var str = clsComFnc.FncNv(
                jsonResult.json[0]["data"][SelectRow]["HANBAISYASYU"]
            );
            str = String(str).padRight(8);
            strEnd1 = str.substring(0, 5);
            strEnd2 = str.substring(7, 8);
            strEnd1 = strEnd1.trimEnd();
            strEnd2 = strEnd2.trimEnd();

            str = String(strEnd1) + String(strEnd2);
            $(".FrmList.lblKosyo").val(str);
            //fuxl
            $(".FrmList.lblZei").val(
                clsComFnc.FncNv(jsonResult.json[0]["data"][SelectRow]["SHZ_RT"])
            );
            $(".FrmList.lblHanbaiSyasyu").html(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["HANBAISYASYU"]
                )
            );
            //3
            $(".FrmList.lblSyadaiKata").html(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["SDI_KAT"]
                )
            );
            //1
            $(".FrmList.lblCar_NO").html(
                clsComFnc.FncNv(jsonResult.json[0]["data"][SelectRow]["CAR_NO"])
            );
            //2
            $(".FrmList.lblSyasyu_NM").html(
                clsComFnc.FncNv(
                    jsonResult.json[0]["data"][SelectRow]["BASEH_KN"]
                )
            );
            //4
            var str = $(".FrmList.lblSyadaiKata").html();
            var str1 = $(".FrmList.lblCar_NO").html();

            str = str.trimEnd();
            str1 = str1.trimStart();
            str1 = str1 != "" ? "-" + String(str1) : "";
            str = String(str) + String(str1);
            $(".FrmList.lblSyadaiCarNO").val(str);

            var tmpArr = Result.json[0]["cRow"].split("+");
            if (tmpArr[0] == "noData" && tmpArr[1] == "noData") {
                for (var i = 1; i <= 7; i++) {
                    var columns = {
                        defaultCol: "",
                        aa: "",
                        bb: "",
                    };
                    switch (i) {
                        case 1:
                            columns["defaultCol"] = "定価";
                            me.mydata.push(columns);
                            break;
                        case 2:
                            columns["defaultCol"] = "値引";
                            me.mydata.push(columns);
                            break;
                        case 3:
                            columns["defaultCol"] = "契約価格";
                            me.mydata.push(columns);
                            break;
                        case 4:
                            columns["defaultCol"] = "部品社内価格";
                            me.mydata.push(columns);
                            break;
                        case 5:
                            columns["defaultCol"] = "部品社内実原価";
                            me.mydata.push(columns);
                            break;
                        case 6:
                            columns["defaultCol"] = "外注社内原価";
                            me.mydata.push(columns);
                            break;
                        case 7:
                            columns["defaultCol"] = "外注社内実原価";
                            me.mydata.push(columns);
                            break;
                        default:
                            break;
                    }
                }
            } else if (tmpArr[1] != "noData" && tmpArr[0] == "noData") {
                me.intFlgCnt = me.intFlgCnt + 1;
                for (var i = 1; i <= 7; i++) {
                    var columns = {
                        defaultCol: "",
                        aa: "",
                        bb: "",
                    };
                    switch (i) {
                        case 1:
                            columns["defaultCol"] = "定価";
                            columns["bb"] = Result.json[0]["data"][0]["TEIKA"];
                            me.mydata.push(columns);
                            break;
                        case 2:
                            columns["defaultCol"] = "値引";
                            columns["bb"] = Result.json[0]["data"][0]["NEBIKI"];
                            me.mydata.push(columns);
                            break;
                        case 3:
                            columns["defaultCol"] = "契約価格";
                            columns["bb"] =
                                Result.json[0]["data"][0]["KEI_KIN"];
                            me.mydata.push(columns);
                            break;
                        case 4:
                            columns["defaultCol"] = "部品社内価格";
                            me.mydata.push(columns);
                            break;
                        case 5:
                            columns["defaultCol"] = "部品社内実原価";
                            me.mydata.push(columns);
                            break;
                        case 6:
                            columns["defaultCol"] = "外注社内原価";
                            me.mydata.push(columns);
                            break;
                        case 7:
                            columns["defaultCol"] = "外注社内実原価";
                            me.mydata.push(columns);
                            break;
                        default:
                            break;
                    }
                }
            } else if (tmpArr[0] != "noData" && tmpArr[1] == "noData") {
                me.intFlgCnt = me.intFlgCnt + 1;
                for (var i = 1; i <= 7; i++) {
                    var columns = {
                        defaultCol: "",
                        aa: "",
                        bb: "",
                    };
                    switch (i) {
                        case 1:
                            columns["defaultCol"] = "定価";
                            columns["aa"] = Result.json[0]["data"][0]["TEIKA"];
                            me.mydata.push(columns);
                            break;
                        case 2:
                            columns["defaultCol"] = "値引";
                            columns["aa"] = Result.json[0]["data"][0]["NEBIKI"];
                            me.mydata.push(columns);
                            break;
                        case 3:
                            columns["defaultCol"] = "契約価格";
                            columns["aa"] =
                                Result.json[0]["data"][0]["KEI_KIN"];
                            me.mydata.push(columns);
                            break;
                        case 4:
                            columns["defaultCol"] = "部品社内価格";

                            me.mydata.push(columns);
                            break;
                        case 5:
                            columns["defaultCol"] = "部品社内実原価";

                            me.mydata.push(columns);
                            break;
                        case 6:
                            columns["defaultCol"] = "外注社内原価";

                            me.mydata.push(columns);
                            break;
                        case 7:
                            columns["defaultCol"] = "外注社内実原価";

                            me.mydata.push(columns);
                            break;
                        default:
                            break;
                    }
                }
            } else {
                me.intFlgCnt = me.intFlgCnt + 1;
                for (var i = 1; i <= 7; i++) {
                    var columns = {
                        defaultCol: "",
                        aa: "",
                        bb: "",
                    };
                    switch (i) {
                        case 1:
                            columns["defaultCol"] = "定価";
                            columns["aa"] = Result.json[0]["data"][0]["TEIKA"];
                            columns["bb"] = Result.json[0]["data"][1]["TEIKA"];
                            me.mydata.push(columns);
                            break;
                        case 2:
                            columns["defaultCol"] = "値引";
                            columns["aa"] = Result.json[0]["data"][0]["NEBIKI"];
                            columns["bb"] = Result.json[0]["data"][1]["NEBIKI"];
                            me.mydata.push(columns);
                            break;
                        case 3:
                            columns["defaultCol"] = "契約価格";
                            columns["aa"] =
                                Result.json[0]["data"][0]["KEI_KIN"];
                            columns["bb"] =
                                Result.json[0]["data"][1]["KEI_KIN"];
                            me.mydata.push(columns);
                            break;
                        case 4:
                            columns["defaultCol"] = "部品社内価格";

                            me.mydata.push(columns);
                            break;
                        case 5:
                            columns["defaultCol"] = "部品社内実原価";

                            me.mydata.push(columns);
                            break;
                        case 6:
                            columns["defaultCol"] = "外注社内原価";

                            me.mydata.push(columns);
                            break;
                        case 7:
                            columns["defaultCol"] = "外注社内実原価";

                            me.mydata.push(columns);
                            break;
                        default:
                            break;
                    }
                }
            }

            // console.log(me.mydata);
            var data = $("#FrmList_sprMoneyList").jqGrid("getDataIDs");
            for (key in data) {
                $("#FrmList_sprMoneyList").jqGrid("delRowData", data[key]);
            }
            for (var i = 0; i <= me.mydata.length; i++) {
                $("#FrmList_sprMoneyList").jqGrid(
                    "addRowData",
                    i + 1,
                    me.mydata[i]
                );
            }

            me.fncMoneyKasouMeisai(me.mydata, txtCMNNOVal);
        };
        ajax.send(url, me.data, 0);
    };

    me.fncSubStandardInfoSet = function (jsonResult, SelectRow) {
        // //基本情報を表示する
        // $(".FrmList.lblKeiyakusya").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['KEIYAKUSYA']), 30).trimEnd());
        // //契約者
        // $(".FrmList.lblSiyosya").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['SIYOSYA']), 30).trimEnd());
        // //使用者
        // $(".FrmList.lblSiyosyaKN").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['CSRKNANM']), 30).trimEnd());
        // //使用者カナ
        // $(".FrmList.lblBusyoCD").val(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['KYOTN_CD']));
        // //部署コード
        // $(".FrmList.lblBusyoNM").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['BUSYOMEI']), 30).trimEnd());
        // //部署名
        // $(".FrmList.lblSyainNO").val(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['HNB_TAN_EMP_NO']));
        // //社員コード
        // $(".FrmList.lblSyainNM").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['SYAIN']), 30).trimEnd());
        // //社員名
        // $(".FrmList.lblHanbaitenNO").val(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['HNB_KTN_CD']));
        // //販売店コード
        // $(".FrmList.lblHanbaitenNM").val(clsComFnc.fncGetFixVal(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['HANBAITEN']), 30).trimEnd());
        // //販売店名
        // $(".FrmList.lblKasouNO").val(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['KASOU_NO']));
        // //伝票NO
        //
        // if (clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['UPD_DATE']) != '') {
        // $(".FrmList.txtUPD_DAT").val(jsonResult.json[0]['data'][SelectRow]['UPD_DATE']);
        // } else {
        // $(".FrmList.txtUPD_DAT").val('');
        // }
        // //前回発効日
        // var str = clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['HANBAISYASYU']);
        // str = String(str).padRight(8);
        // strEnd1 = str.substr(0, 5);
        // strEnd2 = str.substr(7, 1);
        // strEnd1 = strEnd1.trimEnd();
        // strEnd2 = strEnd2.trimEnd();
        //
        // str = String(strEnd1) + String(strEnd2);
        // $(".FrmList.lblKosyo").val(str);
        // //fuxl
        // $(".FrmList.lblZei").val(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['SHZ_RT']));
        // $(".FrmList.lblHanbaiSyasyu").html(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['HANBAISYASYU']));
        // //3
        // $(".FrmList.lblSyadaiKata").html(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['SDI_KAT']));
        // //1
        // $(".FrmList.lblCar_NO").html(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['CAR_NO']));
        // //2
        // $(".FrmList.lblSyasyu_NM").html(clsComFnc.FncNv(jsonResult.json[0]['data'][SelectRow]['BASEH_KN']));
        // //4
        // var str = $(".FrmList.lblSyadaiKata").html();
        // var str1 = $(".FrmList.lblCar_NO").html();
        //
        // str = str.trimEnd();
        // str1 = str1.trimStart();
        // str1 = (str1 != "") ? ("-" + String(str1)) : "";
        // str = String(str) + String(str1);
        // $(".FrmList.lblSyadaiCarNO").val(str);
        me.subSpreadReShow(jsonResult, SelectRow);
        me.strSetCmn = $.trim($(".FrmList.txtCMNNO").val());
    };

    me.fncStandardInfoSet = function () {
        /**********************************************************************
         '処 理 名：基本情報をSELECTする
         '関 数 名：fncStandardInfoSet
         '引    数：無し
         '戻 り 値：True：正常終了 False:異常終了
         '処理説明：基本情報をSELECTする
         '**********************************************************************/
        console.log("fncStandardInfoSet基本情報をSELECTする:");
        $("#FrmList_sprCustomer").jqGrid("clearGridData");
        $("#FrmList_sprMoneyList").jqGrid("clearGridData");
        me.subFormClear(false);
        funcName = "fncStandardInfoSet";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        me.strJokenCmn = $(".FrmList.txtCMNNO").val().trimEnd();
        me.strJokenKna = $(".FrmList.txtSiyFgn").val().trimEnd();
        me.strJokenEmp = $(".FrmList.txtEmpNO").val().trimEnd();
        me.strSetCmn = $.trim($(".FrmList.txtCMNNO").val());

        var txtCMNNOVal = $.trim($(".FrmList.txtCMNNO").val());
        var txtSiyFgnVal = $.trim($(".FrmList.txtSiyFgn").val());
        var txtEmpNOVal = $.trim($(".FrmList.txtEmpNO").val());

        var insertArray = {
            CMN_NO: txtCMNNOVal,
            SIY_FGN: txtSiyFgnVal,
            HNB_TAN_EMP_NO: txtEmpNOVal,
        };
        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            console.log(result);
            me.InfoTbl = result;
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }
            //該当データが存在しない場合は処理を抜ける
            if (jsonResult.json[0]["cRow"] == "noData") {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("I0001");
                me.subClearFormGrid();
                $(".FrmList.cmdSearch").button("enable");
                return;
            }

            if (jsonResult.json[0]["cRow"] == "1") {
                me.fncSubStandardInfoSet(jsonResult, 0, false, "");
            } else {
                //該当データが複数件存在している場合はList画面を表示する
                console.log(
                    "該当データが複数件存在している場合はList画面を表示する"
                );
                var frmId = "FrmListSelect";
                var url = me.sys_id + "/" + frmId;
                // 20201117 lqs upd S
                // ajax.send(url, '', 0, true);
                ajax.send(url, "", 0);
                // 20201117 lqs upd E
                ajax.receive = function (result) {
                    $("#FrmListDialogDiv").html(result);
                };
                // $.ajax({
                // type : "POST",
                // url : url,
                // data : {
                // "url" : frmId
                // },
                // success : function(result) {
                // $("#FrmListDialogDiv").html(result);
                //
                // }
                // });
            }
        };
        ajax.send(url, me.data, 0);
    };

    me.cmdSearch_Click = function () {
        /**********************************************************************
         '処理概要：検索ボタン押下時
         '**********************************************************************/
        console.log("cmdSearch_Click検索ボタン押下時:");

        me.statusFlag = false;
        me.intFlgCnt = 0;
        $("#FrmList_sprCustomer").jqGrid("clearGridData");
        $("#FrmList_sprMoneyList").jqGrid("clearGridData");
        var txtCMNNOVal = $(".FrmList.txtCMNNO").val().trimEnd();
        var txtSiyFgnVal = $(".FrmList.txtSiyFgn").val().trimEnd();
        var txtEmpNOVal = $(".FrmList.txtEmpNO").val().trimEnd();
        if (txtCMNNOVal == "" && txtSiyFgnVal == "" && txtEmpNOVal == "") {
            clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
            clsComFnc.FncMsgBox("W0009");
            me.subClearFormGrid();
            $(".FrmList.cmdSearch").button("enable");
            return;
        }
        //WK_HKASOUMEISAIﾃｰﾌﾞﾙにHKASOUMEISAIの該当データをINSERTする
        var funcName = "subDeleteAndInsertOfWKHKASOUMEISAI";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var insertArray = {
            CMN_NO: txtCMNNOVal,
            SIY_FGN: txtSiyFgnVal,
            HNB_TAN_EMP_NO: txtEmpNOVal,
        };
        me.data = {
            request: insertArray,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }
            if (jsonResult.json[0]["result"]) {
                //基本情報を設定
                if (jsonResult.json[0]["cRow"] > 0) {
                    me.statusFlag = true;
                }
                me.fncStandardInfoSet();
            }
        };
        ajax.send(url, me.data, 0);

        ajax.beforeLogin = me.buttonableSearch;
    };

    me.buttonableSearch = function () {
        $("#FrmList_sprCustomer").jqGrid("clearGridData");
        $("#FrmList_sprMoneyList").jqGrid("clearGridData");
        me.subFormClear(false);
        me.subClearFormGrid();

        $(".FrmList.cmdSearch").button("enable");
    };

    me.buttonableUpdate = function () {
        $(".FrmList.cmdUpdate").button("enable");
    };

    me.enableSpecial = function () {
        $(".FrmList.cmdSpecial").button("enable");
    };

    me.enableOption = function () {
        $(".FrmList.cmdOption").button("enable");
    };

    me.fncCopyKasouInsert = function (
        txtCopyStartVal,
        txtCopyEndVal,
        strKasouNO
    ) {
        //INSERT
        console.log("fncCopyKasouInsert:");
        funcName = "fncCopyKasouInsert";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        var CMN_NO = $(".FrmList.txtCopyEnd").val();

        var arrayVal = {
            WHERECMNNO: txtCopyStartVal,
            SETCMNNO: txtCopyEndVal,
            strKasouNO: strKasouNO,
            CMN_NO: CMN_NO,
        };

        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            $(".FrmList.txtCMNNO").val("");
            $(".FrmList.txtCMNNO").val(txtCopyEndVal);
            // $(".FrmList.txtCopyStart").val('');
            $(".FrmList.txtCopyEnd").val("");
            // $(".FrmList.cmdUpdate").button('enable');
            //検索する
            me.cmdSearch_Click(false);
        };
        ajax.send(url, me.data, 0);
    };

    me.fncUpdSaiban = function () {
        //採番する
        console.log("fncUpdSaiban採番する:");
        var funcName = "fncUpdSaiban";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var txtCopyEndVal = $.trim($(".FrmList.txtCopyEnd").val());
        var txtCopyStartVal = $.trim($(".FrmList.txtCopyStart").val());

        var arrayVal = {
            CMN_NO: txtCopyEndVal,
            blnUpdate: "false",
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] != true) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
            }

            var strKasouNO = jsonResult.json[0]["fncUpdSaiban"];
            console.log(strKasouNO);
            //更新処理
            me.fncCopyKasouInsert(txtCopyStartVal, txtCopyEndVal, strKasouNO);
        };
        ajax.send(url, me.data, 0);
    };

    me.fncKasouTblCheck = function () {
        console.log("fncKasouTblCheck架装明細ﾃｰﾌﾞﾙ存在ﾁｪｯｸ:");
        var funcName = "fncKasouTblCheck";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        var txtCopyEndVal = $(".FrmList.txtCopyEnd").val().trimEnd();
        var KASOUNOVal = $(".FrmList.lblKasouNO").val().trimEnd();

        var arrayVal = {
            CMN_NO: txtCopyEndVal,
            KASOUNO: KASOUNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
            if (jsonResult.json[0]["cRow"] != "noData") {
                clsComFnc.FncMsgBox("W0013", "注文書番号の架装明細データ");
                return;
            }
            if (jsonResult.json[0]["cRow"] == "noData") {
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncUpdSaiban;
                clsComFnc.MessageBox(
                    "コピーします。よろしいですか？",
                    clsComFnc.GSYSTEM_NAME,
                    "YesNo",
                    "Question",
                    clsComFnc.MessageBoxDefaultButton.Button1
                );
            }
        };
        ajax.send(url, me.data, 0);
    };

    me.fncKasouTblCheck1 = function () {
        console.log("fncKasouTblCheck1");
        var funcName = "fncKasouTblCheck";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        var txtCopyEndVal = $.trim($(".FrmList.txtCopyEnd").val());
        var KASOUNOVal = $(".FrmList.lblKasouNO").val().trimEnd();
        var arrayVal = {
            CMN_NO: txtCopyEndVal,
            KASOUNO: KASOUNOVal,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.ObjFocus = $(".FrmList.txtCMNNO");
                clsComFnc.ObjSelect = $(".FrmList.txtCMNNO");
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                $(".FrmList.cmdSearch").button("enable");
                return;
            }

            if (jsonResult.json[0]["cRow"] != "noData") {
                var txtHaisouSijiVal = clsComFnc.FncNv(
                    jsonResult.json[0]["data"][0]["MEMO"]
                );
                $(".FrmList.txtHaisouSiji").val(txtHaisouSijiVal);
            }
            if (jsonResult.json[0]["cRow"] == "noData") {
                var lblBusyoCDVal = $(".FrmList.lblBusyoCD").val().trimEnd();
                var lblBusyoNMVal = $(".FrmList.lblBusyoNM").val().trimEnd();
                if (
                    lblBusyoCDVal == "191" ||
                    lblBusyoCDVal == "441" ||
                    lblBusyoCDVal == "443"
                ) {
                    var txtHaisouSijiVal = "441" + " " + lblBusyoNMVal;
                    $(".FrmList.txtHaisouSiji").val(txtHaisouSijiVal);
                } else {
                    var txtHaisouSijiVal = lblBusyoCDVal + " " + lblBusyoNMVal;
                    $(".FrmList.txtHaisouSiji").val(txtHaisouSijiVal);
                }
            }

            if (me.intFlgCnt == 0) {
                $(".FrmList.cmdOption").button("disable");
                $(".FrmList.cmdSpecial").button("disable");
                $(".FrmList.cmdDelete").button("disable");
            } else {
                $(".FrmList.cmdOption").button("enable");
                $(".FrmList.cmdSpecial").button("enable");
            }
            //20180515 YIN INS S
            $(".FrmList.cmdsave").button("enable");
            //20180515 YIN INS E
            $(".FrmList.cmdPrintKasou").button("enable");
            $(".FrmList.txtHaisouSiji").removeAttr("disabled");
            $(".FrmList.cboMemo").removeAttr("disabled");

            if (me.statusFlag) {
                $(".FrmList.cmdDelete").button("enable");
            }

            $(".FrmList.txtCMNNO").trigger("focus");
            $(".FrmList.txtCMNNO").select();

            if (me.methodFlag) {
                me.fncDeleteWKNotAll(me.CMNNO);
            }
            me.methodFlag = false;
            me.CMNNO = "";
            $(".FrmList.cmdSearch").button("enable");
        };
        ajax.send(url, me.data, 0);
    };

    me.fncDeleteKasou = function () {
        var funcName = "fncDeleteKasou";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        ajax.receive = function (result) {
            console.log(result);
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");
            if (jsonResult.json[0]["result"] == true) {
                me.subFormClear(me.blnHedderClear);

                me.subClearFormGrid();

                $(".FrmList.txtCMNNO").trigger("focus");
                return;
            }

            if (jsonResult.json[0]["result"] == false) {
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            }
        };
        ajax.send(url, me.data, 0);
    };

    //20131210 LuChao 既存バグ修正 Start
    me.fncDeleteWKNotAll = function (CMN_NO) {
        console.log("fncDeleteWKNotAll");
        var funcName = "fncDeleteWKOther";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var init_mark = 0;
        var data = new Array();
        ajax = new gdmz.common.ajax();
        data = {
            request: CMN_NO,
        };

        ajax.receive = function (result) {
            console.log(result);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        ajax.send(url, data, init_mark);
    };
    //20131210 LuChao 既存バグ修正 End

    me.fncFrmListDialogClose = function () {
        var jsonInfoTbl = {};
        var txtResult = '{ "json" : [' + me.InfoTbl + "]}";
        jsonInfoTbl = eval("(" + txtResult + ")");
        switch (me.FrmListSelect.intBtnKind) {
            case 1:
                //コピーﾎﾞﾀﾝ
                var CMN_NO = clsComFnc.FncNv(
                    jsonInfoTbl.json[0]["data"][me.FrmListSelect.SelectRow][
                        "CMN_NO"
                    ]
                );

                $(".FrmList.txtCopyStart").val(CMN_NO);
                $(".FrmList.txtCopyEnd").trigger("focus");
                $(".FrmList.cmdSearch").button("enable");
                me.subClearFormGrid();
                break;
            case 2:
                //選択ボタン
                var CMN_NO = clsComFnc.FncNv(
                    jsonInfoTbl.json[0]["data"][me.FrmListSelect.SelectRow][
                        "CMN_NO"
                    ]
                );
                //var KASOUNO = clsComFnc.FncNv(jsonInfoTbl.json[0]['data'][me.FrmListSelect.SelectRow]['KASOU_NO']);
                $(".FrmList.txtCMNNO").val(CMN_NO);
                me.methodFlag = true;
                me.CMNNO = CMN_NO;
                me.fncSubStandardInfoSet(
                    jsonInfoTbl,
                    me.FrmListSelect.SelectRow
                );
                //20131210 LuChao 既存バグ修正 Start
                // me.fncDeleteWKNotAll(CMN_NO);
                //20131210 LuChao 既存バグ修正 End
                break;
            default:
                //戻るﾎﾞﾀﾝが押された場合は処理を抜ける
                me.deleteWKClear(false);
                break;
        }
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmList = new R4.FrmList();
    o_R4_FrmList.load();

    o_R4_R4.FrmList = o_R4_FrmList;
});
