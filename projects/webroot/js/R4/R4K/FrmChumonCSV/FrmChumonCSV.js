/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150807           #1951          実行時に、「原価マスタ未登録データ」が存在する場合、「処理が正常に終了しました」メッセージは不要です。  FANZHENGZHOU
 * 20150807           #1939                                                         FANZHENGZHOU
 * 20150922           #2162                        BUG                              LI
 * 20201117           bug            年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。 WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmChumonCSV");

R4.FrmChumonCSV = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmChumonCSV";
    me.sys_id = "R4K";
    me.cboUCNO = "";
    me.pdfpath = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.colModel = [
        {
            name: "CMN_NO",
            label: "注文番号",
            index: "CMN_NO",
            sortable: false,
        },
        {
            name: "HBSS_CD",
            label: "問合呼称",
            index: "HBSS_CD",
            sortable: false,
        },
        {
            name: "SRY_HT_PRC_ZEINK",
            label: "本体価格",
            index: "SRY_HT_PRC_ZEINK",
            formatter: "integer",
            align: "right",
            sortable: false,
        },
    ];

    me.controls.push({
        id: ".FrmChumonCSV.cmdAction.Tab.Enter",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter",
        //-- 20150922 li UPD S.
        // type : "datepicker2",
        type: "datepicker3",
        //-- 20150922 li UPD E.
        handle: "",
    });
    me.controls.push({
        id: ".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter",
        type: "datepicker",
        handle: "",
    });
    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //初期処理
        me.frmChumonCSV_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $("#FrmChumonCSV_sprList").jqGrid({
        datatype: "local",
        height: "207",
        rownumbers: true,
        colModel: me.colModel,
    });
    $("#FrmChumonCSV_sprList").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });
    //To make the date format.Start
    //処理年月
    $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").on("blur", function () {
        //-- 20150922 li UPD S.
        //if (me.clsComFnc.CheckDate2($(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter")) == false) {
        if (
            me.clsComFnc.CheckDate3(
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter")
            ) == false
        ) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                //-- 20150922 li UPD E.
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").val(me.cboUCNO);
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").trigger("focus");
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").select();
                $(".FrmChumonCSV.cmdAction.Tab.Enter").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmChumonCSV.cmdAction.Tab.Enter").button("enable");
        }
    });
    //更新年月日From
    $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter").on("blur", function () {
        if (
            me.clsComFnc.CheckDate(
                $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter")
            ) == false
        ) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                var currentDate = new Date();
                $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter").datepicker(
                    "setDate",
                    currentDate
                );
                $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter").trigger(
                    "focus"
                );
                $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter").select();
                $(".FrmChumonCSV.cmdAction.Tab.Enter").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmChumonCSV.cmdAction.Tab.Enter").button("enable");
        }
    });
    //更新年月日To

    $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").on("blur", function () {
        if (
            me.clsComFnc.CheckDate(
                $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter")
            ) == false
        ) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                var currentDate = new Date();
                $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").datepicker(
                    "setDate",
                    currentDate
                );
                $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").trigger(
                    "focus"
                );
                $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").select();
                $(".FrmChumonCSV.cmdAction.Tab.Enter").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmChumonCSV.cmdAction.Tab.Enter").button("enable");
        }
    });

    //To make the date format.End

    //実行ﾎﾞﾀﾝ押下時
    $(".FrmChumonCSV.cmdAction.Tab.Enter").click(function () {
        //出力確認ﾒｯｾｰｼﾞ
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAction;
        me.clsComFnc.FncMsgBox("QY009");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmChumonCSV_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.frmChumonCSV_Load = function () {
        me.subClearForm();
        var currentDate = new Date();
        $(".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter").datepicker(
            "setDate",
            currentDate
        );
        $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").datepicker(
            "setDate",
            currentDate
        );
        var url = me.sys_id + "/" + me.id + "/" + "frmChumonCSV_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //-- 20150922 li UPD S.
                    //me.cboUCNO = result['data'][0]['TOUGETU'].substr(0, 7);
                    strTougetu = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .split("/");
                    me.cboUCNO = strTougetu[0] + strTougetu[1];
                    //-- 20150922 li UPD E.
                    $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").val(
                        me.cboUCNO
                    );
                    $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter").trigger(
                        "focus"
                    );
                } else {
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };

    //**********************************************************************
    //処 理 名：画面項目ｸﾘｱ
    //関 数 名：subClearForm
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目ｸﾘｱ
    //**********************************************************************
    me.subClearForm = function () {
        $(".FrmChumonCSV.GroupBox1.rdoAll.Enter.Tab").prop(
            "checked",
            "checked"
        );
        me.subClearForm2();
    };

    //**********************************************************************
    //処 理 名：画面項目ｸﾘｱ
    //関 数 名：subClearForm2
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目ｸﾘｱ
    //**********************************************************************
    me.subClearForm2 = function () {
        $(".FrmChumonCSV.labels.lblMSG").html("");
        $(".FrmChumonCSV.labels.lblMSG2").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNew").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNewCenter").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNewA").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsed").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsedCenter").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsedA").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNewChg").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNewChgCenter").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntNewChgA").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsedChg").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgCenter").html("");
        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgA").html("");
        $(".FrmChumonCSV.GroupBox3").css("visibility", "hidden");
        $("#FrmChumonCSV_sprList").jqGrid("clearGridData");
    };

    //获取单选按钮的选择的值
    me.judgeRadioClicked = function () {
        //获得 单选选按钮name集合
        var radios = document.getElementsByName("FrmChumonCSV_radio");

        //判断那个单选按钮为选中状态
        if (radios[0].checked) {
            return 1;
        }
        if (radios[1].checked) {
            return 2;
        }
        if (radios[2].checked) {
            return 9;
        }
    };
    //**********************************************************************
    //処 理 名：CSVファイル出力
    //関 数 名：cmdAction
    //引    数：無し
    //戻 り 値：無し
    //処理説明：CSVファイル出力処理
    //**********************************************************************
    me.cmdAction = function () {
        me.subClearForm2();
        $(".FrmChumonCSV.labels.lblMSG2").html("ＣＳＶデータ作成処理中です");
        var selectradio = me.judgeRadioClicked();
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction";
        var data_arr = {
            //-- 20150922 li UPD S.
            //'cboUCNO' : $('.FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter').val(),
            cboUCNO:
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter")
                    .val()
                    .substr(0, 4) +
                "/" +
                $(".FrmChumonCSV.GroupBox1.cboUCNO.Tab.Enter")
                    .val()
                    .substr(4, 2),
            //-- 20150922 li UPD E.
            cboDateFrom: $(
                ".FrmChumonCSV.GroupBox1.cboDateFrom.Tab.Enter"
            ).val(),
            cboDateTo: $(".FrmChumonCSV.GroupBox1.cboDateTo.Tab.Enter").val(),
            radio: selectradio,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            $(".FrmChumonCSV.labels.lblMSG2").html("");
            if (result["result"] == true) {
                switch (result["data"]) {
                    case 1:
                        $(".FrmChumonCSV.GroupBox2.lblCntNew").html(
                            result["frm1"]["lblCntNew"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewCenter").html(" /");
                        $(".FrmChumonCSV.GroupBox2.lblCntNewA").html(
                            result["lblCnt"]["NewDataA"].replace(/ /g, "&nbsp")
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntUsed").html(
                            result["frm1"]["lblCntUsed"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedA").html(
                            result["lblCnt"]["UsedDataA"].replace(/ /g, "&nbsp")
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntNewChg").html(
                            result["frm1"]["lblCntNewChg"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgA").html(
                            result["lblCnt"]["NewChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChg").html(
                            result["frm1"]["lblCntUsedChg"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgA").html(
                            result["lblCnt"]["UsedChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );
                        if (result["pdfmark"] == true) {
                            me.pdfpath = result["pdfpath"];
                        }

                        if (result["strErrLogName"] != undefined) {
                            me.clsComFnc.MsgBoxBtnFnc.Close =
                                me.subErrSpreadShowData;
                            //---20150807 #1939 fanzhengzhou upd s.
                            //me.clsComFnc.MessageBox("エラーデータが存在します。" + "</br>" + "ログファイル(" + "\\\\192.168.2.62\\temp\\log\\N5200CSVERR.Log" + ")を確認してください。", me.clsComFnc.GSYSTEM_NAME, me.clsComFnc.MessageBoxButtons.OK, me.clsComFnc.MessageBoxIcon.Warning);
                            me.clsComFnc.MessageBox(
                                "エラーデータが存在します。" +
                                "</br>" +
                                "ログファイルを確認してください。",
                                me.clsComFnc.GSYSTEM_NAME,
                                me.clsComFnc.MessageBoxButtons.OK,
                                me.clsComFnc.MessageBoxIcon.Warning
                            );
                            //---20150807 #1939 fanzhengzhou upd e.
                        } else {
                            me.subErrSpreadShowData();
                        }
                        //****************************************************
                        for (key in result["subErrSpreadShowData"]) {
                            var columns = {
                                CMN_NO: result["subErrSpreadShowData"][key][
                                    "CMN_NO"
                                ],
                                HBSS_CD:
                                    result["subErrSpreadShowData"][key][
                                    "HBSS_CD"
                                    ],
                                SRY_HT_PRC_ZEINK:
                                    result["subErrSpreadShowData"][key][
                                    "SRY_HT_PRC_ZEINK"
                                    ],
                            };
                            $("#FrmChumonCSV_sprList").jqGrid(
                                "addRowData",
                                parseInt(key) + 1,
                                columns
                            );
                        }

                        //****************************************************
                        break;
                    case 2:
                        $(".FrmChumonCSV.GroupBox2.lblCntNewCenter").html(" /");
                        $(".FrmChumonCSV.GroupBox2.lblCntNewA").html(
                            result["lblCnt"]["NewDataA"].replace(/ /g, "&nbsp")
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntUsedCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedA").html(
                            result["lblCnt"]["UsedDataA"].replace(/ /g, "&nbsp")
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgA").html(
                            result["lblCnt"]["NewChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );

                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgA").html(
                            result["lblCnt"]["UsedChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );

                        if (result["strErrLogName"] != undefined) {
                            me.clsComFnc.MsgBoxBtnFnc.Close =
                                me.subErrSpreadShowData;
                            //---20150807 #1939 fanzhengzhou upd s.
                            //me.clsComFnc.MessageBox("エラーデータが存在します。" + "</br>" + "ログファイル(" + "\\\\192.168.2.62\\temp\\log\\N5200CSVERR.Log" + ")を確認してください。", me.clsComFnc.GSYSTEM_NAME, me.clsComFnc.MessageBoxButtons.OK, me.clsComFnc.MessageBoxIcon.Warning);
                            me.clsComFnc.MessageBox(
                                "エラーデータが存在します。" +
                                "</br>" +
                                "ログファイルを確認してください。",
                                me.clsComFnc.GSYSTEM_NAME,
                                me.clsComFnc.MessageBoxButtons.OK,
                                me.clsComFnc.MessageBoxIcon.Warning
                            );
                            //---20150807 #1939 fanzhengzhou upd e.
                        } else {
                            me.subErrSpreadShowData();
                        }
                        //****************************************************
                        for (key in result["subErrSpreadShowData"]) {
                            var columns = {
                                CMN_NO: result["subErrSpreadShowData"][key][
                                    "CMN_NO"
                                ],
                                HBSS_CD:
                                    result["subErrSpreadShowData"][key][
                                    "HBSS_CD"
                                    ],
                                SRY_HT_PRC_ZEINK:
                                    result["subErrSpreadShowData"][key][
                                    "SRY_HT_PRC_ZEINK"
                                    ],
                            };
                            $("#FrmChumonCSV_sprList").jqGrid(
                                "addRowData",
                                parseInt(key) + 1,
                                columns
                            );
                        }

                        //****************************************************
                        break;
                    case 3:
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "該当するデータは存在しません。"
                        );
                        $(".FrmChumonCSV.labels.lblMSG").html(
                            "該当するデータは存在しません。"
                        );
                        break;
                    case 4:
                        $(".FrmChumonCSV.labels.lblMSG").html(
                            "ＣＳＶ出力処理に失敗しました。"
                        );
                        break;
                    default:
                        $(".FrmChumonCSV.GroupBox2.lblCntNew").html(
                            result["frm1"]["lblCntNew"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewCenter").html(" /");
                        $(".FrmChumonCSV.GroupBox2.lblCntNewA").html(
                            result["lblCnt"]["NewDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsed").html(
                            result["frm1"]["lblCntUsed"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedA").html(
                            result["lblCnt"]["UsedDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChg").html(
                            result["frm1"]["lblCntNewChg"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntNewChgA").html(
                            result["lblCnt"]["NewChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChg").html(
                            result["frm1"]["lblCntUsedChg"]
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgCenter").html(
                            " /"
                        );
                        $(".FrmChumonCSV.GroupBox2.lblCntUsedChgA").html(
                            result["lblCnt"]["UsedChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );
                        if (result["pdfmark"] == true) {
                            me.pdfpath = result["pdfpath"];
                        }
                        me.subErrSpreadShowData();
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
            }
        };
        me.ajax.send(url, data_arr, 0);
    };
    me.subErrSpreadShowData = function () {
        var rownum = $("#FrmChumonCSV_sprList").jqGrid(
            "getGridParam",
            "records"
        );
        if (rownum > 0) {
            $(".FrmChumonCSV.GroupBox3").css("visibility", "visible");
        }
        //---20150807 #1951 fanzhengzhou upd s.
        //me.clsComFnc.MessageBox("処理が正常に終了しました。", me.clsComFnc.GSYSTEM_NAME, me.clsComFnc.MessageBoxButtons.OK, "");
        else {
            me.clsComFnc.MessageBox(
                "処理が正常に終了しました。",
                me.clsComFnc.GSYSTEM_NAME,
                me.clsComFnc.MessageBoxButtons.OK,
                ""
            );
        }
        //---20150807 #1951 fanzhengzhou upd e.
        if (me.pdfpath != "") {
            window.open(me.pdfpath);
            me.pdfpath = "";
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmChumonCSV = new R4.FrmChumonCSV();
    o_R4_FrmChumonCSV.load();
});
