/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150911                  #2114                   BUG                         LI
 * 20150917                  #2117                   BUG                         LI
 * 20150922					#2162				 	 BUG						 yin
 * 20160317           		#2376                    依頼                         LI
 * 20180122           		#2807                    依頼                         YIN
 * 20201117                 bug                      年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ------------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSCUriageMake");

R4.FrmSCUriageMake = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSCUriageMake";
    me.sys_id = "R4K";
    me.pdfpath = "";
    me.cboUCNO = "";
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
        id: ".FrmSCUriageMake.cmdAction.Tab.Enter",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter",
        //20150922 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd e
        handle: "",
    });
    me.controls.push({
        id: ".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter",
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
        me.FrmSCUriageMake_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $("#FrmSCUriageMake_sprList").jqGrid({
        datatype: "local",
        //-- 20150911 li UPD S.
        //height : '180',
        // 20180122 YIN UPD S
        // height : '130',
        height: "110",
        // 20180122 YIN UPD E
        //-- 20150911 li UPD E.
        rownumbers: true,
        colModel: me.colModel,
    });
    $("#FrmSCUriageMake_sprList").closest(".ui-jqgrid-bdiv").css({
        "overflow-y": "scroll",
    });
    //To make the date format.Start
    //処理年月
    $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").on("blur", function () {
        //201509 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter")) == false)
        if (
            me.clsComFnc.CheckDate3(
                $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter")
            ) == false
        ) {
            //201509 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").val(
                    me.cboUCNO
                );
                $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").trigger(
                    "focus"
                );
                $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").select();
                $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("enable");
        }
    });
    //更新年月日From
    $(".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter").on(
        "blur",
        function () {
            if (
                me.clsComFnc.CheckDate(
                    $(".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter")
                ) == false
            ) {
                //20201117 wangying ins S
                window.setTimeout(function () {
                    //20201117 wangying ins E
                    var currentDate = new Date();
                    $(
                        ".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter"
                    ).datepicker("setDate", currentDate);
                    $(
                        ".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter"
                    ).trigger("focus");
                    $(
                        ".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter"
                    ).select();
                    $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("disable");
                    return;
                    //20201117 wangying ins S
                }, 0);
                //20201117 wangying ins E
            } else {
                $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("enable");
            }
        }
    );
    //更新年月日To

    $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter").on("blur", function () {
        if (
            me.clsComFnc.CheckDate(
                $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter")
            ) == false
        ) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                var currentDate = new Date();
                $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter").datepicker(
                    "setDate",
                    currentDate
                );
                $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter").trigger(
                    "focus"
                );
                $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter").select();
                $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSCUriageMake.cmdAction.Tab.Enter").button("enable");
        }
    });

    //To make the date format.End

    //実行ﾎﾞﾀﾝ押下時
    $(".FrmSCUriageMake.cmdAction.Tab.Enter").click(function () {
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
    //関 数 名：FrmSCUriageMake_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期処理
    //**********************************************************************
    me.FrmSCUriageMake_Load = function () {
        me.subClearForm();
        var currentDate = new Date();
        $(".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter").datepicker(
            "setDate",
            currentDate
        );
        $(".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter").datepicker(
            "setDate",
            currentDate
        );
        var url = me.sys_id + "/" + me.id + "/" + "FrmSCUriageMake_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //-- 20150922 yin UPD S.
                    //me.cboUCNO = result['data'][0]['TOUGETU'].substr(0, 7);
                    //--- 20160317 li UPD S
                    // strTougetu= result['data'][0]['TOUGETU'].substr(0, 7).split('/');
                    var strTougetu = me.clsComFnc
                        .FncNv(result["data"][0]["TOUGETU"])
                        .substr(0, 7)
                        .split("/");
                    //--- 20160317 li UPD E
                    me.cboUCNO = strTougetu[0] + strTougetu[1];
                    //-- 20150922 yin UPD E.
                    $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").val(
                        me.cboUCNO
                    );
                    me.fncGetCTLInfo();
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
        $(".FrmSCUriageMake.GroupBox1.rdoAll.Enter.Tab").prop(
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
        $(".FrmSCUriageMake.labels.lblMSG").html("");
        $(".FrmSCUriageMake.labels.lblMSG2").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNew").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNewCenter").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNewA").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsed").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsedCenter").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsedA").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNewChg").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgCenter").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgA").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChg").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChgCenter").html("");
        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChgA").html("");
        $(".FrmSCUriageMake.GroupBox3").css("visibility", "hidden");
        $("#FrmSCUriageMake_sprList").jqGrid("clearGridData");
    };

    //**********************************************************************
    //処 理 名：前回抽出条件表示
    //関 数 名：fncGetCTLInfo
    //引    数：無し
    //戻 り 値：無し
    //処理説明：前回抽出条件を表示する
    //**********************************************************************
    me.fncGetCTLInfo = function () {
        var url = me.sys_id + "/" + me.id + "/" + "fncGetCTLInfo";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    $(".FrmSCUriageMake.GroupBox4.lblUPSYRYM").html(
                        me.clsComFnc.FncNv(result["data"][0]["UP_SYR_YMD"])
                    );
                    $(".FrmSCUriageMake.GroupBox4.lblUPFromDT").html(
                        me.clsComFnc.FncNv(result["data"][0]["UP_DT_FROM"])
                    );
                    $(".FrmSCUriageMake.GroupBox4.lblUPToDT").html(
                        me.clsComFnc.FncNv(result["data"][0]["UP_DT_TO"])
                    );
                    var radionum = me.clsComFnc.FncNv(
                        result["data"][0]["UP_KB"]
                    );
                    switch (radionum) {
                        case "1":
                            $(
                                ".FrmSCUriageMake.GroupBox4.rdoUPNew.Enter.Tab"
                            ).prop("checked", "checked");
                        case "2":
                            $(
                                ".FrmSCUriageMake.GroupBox4.rdoUPUsed.Enter.Tab"
                            ).prop("checked", "checked");
                        case "9":
                            $(
                                ".FrmSCUriageMake.GroupBox4.rdoUPAll.Enter.Tab"
                            ).prop("checked", "checked");
                    }
                    $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").trigger(
                        "focus"
                    );
                } else {
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };

    //获取单选按钮的选择的值
    me.judgeRadioClicked = function () {
        //获得 单选选按钮name集合
        var radios = document.getElementsByName("radio");

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
    //処 理 名：実行ﾎﾞﾀﾝ押下時
    //関 数 名：cmdAction
    //引    数：無し
    //戻 り 値：無し
    //処理説明：売上データ作成処理
    //**********************************************************************
    me.cmdAction = function () {
        me.subClearForm2();
        $(".FrmSCUriageMake.labels.lblMSG2").html(
            "新中売上データ作成処理中です"
        );
        var selectradio = me.judgeRadioClicked();
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction";
        var data_arr = {
            cboUCNO: $(".FrmSCUriageMake.GroupBox1.cboUCNO.Tab.Enter").val(),
            cboDateFrom: $(
                ".FrmSCUriageMake.GroupBox1.cboDateFrom.Tab.Enter"
            ).val(),
            cboDateTo: $(
                ".FrmSCUriageMake.GroupBox1.cboDateTo.Tab.Enter"
            ).val(),
            radio: selectradio,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            $(".FrmSCUriageMake.labels.lblMSG2").html("");
            if (result["result"] == true) {
                switch (result["data"]) {
                    case 1:
                        $(".FrmSCUriageMake.GroupBox2.lblCntNew").html(
                            result["frm1"]["lblCntNew"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewA").html(
                            result["lblCnt"]["NewDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsed").html(
                            result["frm1"]["lblCntUsed"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedA").html(
                            result["lblCnt"]["UsedDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChg").html(
                            result["frm1"]["lblCntNewChg"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgA").html(
                            result["lblCnt"]["NewChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChg").html(
                            result["frm1"]["lblCntUsedChg"]
                        );
                        $(
                            ".FrmSCUriageMake.GroupBox2.lblCntUsedChgCenter"
                        ).html(" /");
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChgA").html(
                            result["lblCnt"]["UsedChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );

                        if (result["pdfmark"] == true) {
                            me.pdfpath = result["pdfpath"];
                        }

                        if (result["strErrLogName"] != undefined) {
                            me.clsComFnc.MsgBoxBtnFnc.Yes =
                                me.subErrSpreadShowData;
                            //-- 20150917 li UPD S.
                            //me.clsComFnc.MessageBox("エラーデータが存在します。" + "</br>" + "ログファイル(" + "\\\\192.168.2.62\\temp\\log\\SCURICNV.Log" + ")を確認してください。", me.clsComFnc.GSYSTEM_NAME, me.clsComFnc.MessageBoxButtons.OK, me.clsComFnc.MessageBoxIcon.Warning);
                            me.clsComFnc.MessageBox(
                                "エラーデータが存在します。" +
                                "</br>" +
                                "ログファイル(" +
                                "R:\\log\\SCURICNV.Log" +
                                ")を確認してください。",
                                me.clsComFnc.GSYSTEM_NAME,
                                me.clsComFnc.MessageBoxButtons.OK,
                                me.clsComFnc.MessageBoxIcon.Warning
                            );
                            //-- 20150917 li UPD E.
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
                            $("#FrmSCUriageMake_sprList").jqGrid(
                                "addRowData",
                                parseInt(key) + 1,
                                columns
                            );
                        }

                        //****************************************************
                        break;
                    case 2:
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "該当するデータは存在しません。"
                        );
                        $(".FrmSCUriageMake.labels.lblMSG").html(
                            "該当するデータは存在しません。"
                        );
                        break;
                    case 3:
                        $(".FrmSCUriageMake.labels.lblMSG").html(
                            "処理に失敗しました。"
                        );
                        break;
                    default:
                        $(".FrmSCUriageMake.GroupBox2.lblCntNew").html(
                            result["frm1"]["lblCntNew"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewA").html(
                            result["lblCnt"]["NewDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsed").html(
                            result["frm1"]["lblCntUsed"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedA").html(
                            result["lblCnt"]["UsedDataA"].replace(/ /g, "&nbsp")
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChg").html(
                            result["frm1"]["lblCntNewChg"]
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgCenter").html(
                            " /"
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntNewChgA").html(
                            result["lblCnt"]["NewChangeDataA"].replace(
                                / /g,
                                "&nbsp"
                            )
                        );
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChg").html(
                            result["frm1"]["lblCntUsedChg"]
                        );
                        $(
                            ".FrmSCUriageMake.GroupBox2.lblCntUsedChgCenter"
                        ).html(" /");
                        $(".FrmSCUriageMake.GroupBox2.lblCntUsedChgA").html(
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
        var rownum = $("#FrmSCUriageMake_sprList").jqGrid(
            "getGridParam",
            "records"
        );
        if (rownum > 0) {
            $(".FrmSCUriageMake.GroupBox3").css("visibility", "visible");
        }
        me.clsComFnc.MessageBox(
            "処理が正常に終了しました。",
            me.clsComFnc.GSYSTEM_NAME,
            me.clsComFnc.MessageBoxButtons.OK,
            ""
        );
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
    var o_R4_FrmSCUriageMake = new R4.FrmSCUriageMake();
    o_R4_FrmSCUriageMake.load();
});
