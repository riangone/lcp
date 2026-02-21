/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151009           #2204                        BUG                              li
 * 20151019          #2221							BUG								yin
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * * -----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmRankingInput");

R4.FrmRankingInput = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmRankingInput";
    me.sys_id = "R4K";
    me.url = "";
    me.getData = "";
    me.data = new Array();

    me.col = {
        NENGETU: "",
        ZENSYA_JININ: "",
        HONSYA_JININ: "",
        ATHER_JININ: "",
        SINSYA_DAISU: "",
        CHUKO_DAISU: "",
        SEIBI_JININ: "",
        CRE_DT: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmRankingInput.cboYM",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    me.controls.push({
        id: ".FrmRankingInput.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmRankingInput.cmdDelete",
        type: "button",
        handle: "",
    });

    me.colModel = [
        {
            name: "NENGETU",
            label: "当月年月",
            index: "NENGETU",
            width: 70,
            align: "left",
            sortable: false,
        },
        {
            name: "ZENSYA_JININ",
            label: "全社",
            index: "ZENSYA_JININ",
            width: 80,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "HONSYA_JININ",
            label: "本社",
            index: "HONSYA_JININ",
            width: 80,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "ATHER_JININ",
            label: "本社除く",
            index: "ATHER_JININ",
            width: 80,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "SINSYA_DAISU",
            label: "新車売上台数",
            index: "SINSYA_DAISU",
            width: 120,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "CHUKO_DAISU",
            label: "中古車売上台数",
            index: "CHUKO_DAISU",
            width: 120,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "SEIBI_JININ",
            label: "整備人員",
            index: "SEIBI_JININ",
            width: 80,
            align: "right",
            sortable: false,
            formatter: "integer",
            formatoptions: {
                defaultValue: "",
            },
        },
        {
            name: "CRE_DT",
            label: "CRE_DT",
            index: "CRE_DT",
            width: 80,
            align: "right",
            sortable: false,
            hidden: true,
        },
    ];

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    me.init_control = function () {
        base_init_control();

        $("#FrmRankingInput_sprList").jqGrid({
            datatype: "local",
            // jqgridにデータがなし場合、文字表示しない
            emptyRecordRow: false,
            //20151019 yin upd S
            height: me.ratio === 1.5 ? 166 : 208,
            //20151019 yin upd E
            rownumWidth: 60,
            rownumbers: true,
            colModel: me.colModel,
            onSelectRow: function (_rowid, _status, e) {
                if (typeof e != "undefined") {
                    me.sprHauthList_CellClick();
                }
            },
        });

        $("#FrmRankingInput_sprList").jqGrid("setGroupHeaders", {
            useColSpanStyle: true,
            groupHeaders: [
                {
                    startColumnName: "ZENSYA_JININ",
                    numberOfColumns: 3,
                    titleText: "本社除く人員",
                },
            ],
        });
        $("#FrmRankingInput_sprList").jqGrid("bindKeys");
        $(".numeric").numeric({
            decimal: false,
            negative: false,
        });
        me.frmAuthority_Load();
    };

    $(".FrmRankingInput.cmdDelete").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDelete;
        clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmRankingInput.cmdAction").click(function () {
        if (!me.IsInputCheck()) {
            return;
        }

        clsComFnc.MsgBoxBtnFnc.Yes = me.fncInsert;
        clsComFnc.FncMsgBox("QY010");
    });

    $(".FrmRankingInput.cboYM").change(function () {
        //20150923 yin upd S
        // if (clsComFnc.CheckDate2($(".FrmRankingInput.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmRankingInput.cboYM")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmRankingInput.cboYM").val(me.cboYM);
                $(".FrmRankingInput.cboYM").trigger("focus");
                $(".FrmRankingInput.cboYM").select();
                me.SubClearInput();
                $(".FrmRankingInput.cmdDelete").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            me.SubClearInput();
            $(".FrmRankingInput.cmdDelete").button("disable");

            var tmpVal =
                $(".FrmRankingInput.cboYM").val().substring(0, 4) +
                "/" +
                $(".FrmRankingInput.cboYM").val().substring(4, 6);
            for (key in me.getData) {
                if (tmpVal == me.getData[key]["NENGETU"]) {
                    $(".FrmRankingInput.txtZensya").val(
                        me.getData[key]["ZENSYA_JININ"]
                    );
                    $(".FrmRankingInput.txtHonsya").val(
                        me.getData[key]["HONSYA_JININ"]
                    );
                    $(".FrmRankingInput.lblNozoku").val(
                        me.getData[key]["ATHER_JININ"]
                    );
                    $(".FrmRankingInput.txtSinUriDaisu").val(
                        me.getData[key]["SINSYA_DAISU"]
                    );
                    $(".FrmRankingInput.txtChuUriDaisu").val(
                        me.getData[key]["CHUKO_DAISU"]
                    );
                    $(".FrmRankingInput.txtSeibiJinin").val(
                        me.getData[key]["SEIBI_JININ"]
                    );
                    $(".FrmRankingInput.txtSeibiJinin").val(
                        me.getData[key]["SEIBI_JININ"]
                    );
                    $(".FrmRankingInput.txtCreDt").val(
                        me.getData[key]["CRE_DT"]
                    );
                    $(".FrmRankingInput.cmdDelete").button("enable");
                }
            }
        }
    });

    $(".FrmRankingInput.txtZensya").on("blur", function () {
        $(".FrmRankingInput.txtZensya").css(clsComFnc.GC_COLOR_NORMAL);
        if (
            $(".FrmRankingInput.txtZensya").val().trimEnd() == "" &&
            $(".FrmRankingInput.txtHonsya").val().trimEnd() == ""
        ) {
            $(".FrmRankingInput.lblNozoku").val("");
            return;
        } else {
            var val =
                clsComFnc.FncNz(
                    $(".FrmRankingInput.txtZensya").val().trimEnd()
                ) -
                clsComFnc.FncNz(
                    $(".FrmRankingInput.txtHonsya").val().trimEnd()
                );
            $(".FrmRankingInput.lblNozoku").val(val);
        }
    });

    $(".FrmRankingInput.txtHonsya").on("blur", function () {
        $(".FrmRankingInput.txtHonsya").css(clsComFnc.GC_COLOR_NORMAL);
        if (
            $(".FrmRankingInput.txtZensya").val().trimEnd() == "" &&
            $(".FrmRankingInput.txtHonsya").val().trimEnd() == ""
        ) {
            $(".FrmRankingInput.lblNozoku").val("");
            return;
        } else {
            var val =
                clsComFnc.FncNz(
                    $(".FrmRankingInput.txtZensya").val().trimEnd()
                ) -
                clsComFnc.FncNz(
                    $(".FrmRankingInput.txtHonsya").val().trimEnd()
                );
            $(".FrmRankingInput.lblNozoku").val(val);
        }
    });

    $(".FrmRankingInput.txtSinUriDaisu").on("blur", function () {
        $(".FrmRankingInput.txtSinUriDaisu").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmRankingInput.txtChuUriDaisu").on("blur", function () {
        $(".FrmRankingInput.txtChuUriDaisu").css(clsComFnc.GC_COLOR_NORMAL);
    });

    $(".FrmRankingInput.txtSeibiJinin").on("blur", function () {
        $(".FrmRankingInput.txtSeibiJinin").css(clsComFnc.GC_COLOR_NORMAL);
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.frmAuthority_Load = function () {
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        //20150923 yin upd S
        // var tmpNowDate = myDate.getFullYear().toString() + "/" + tmpMonth.toString();
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        //20150923 yin upd E

        $(".FrmRankingInput.cboYM").val(tmpNowDate);
        me.cboYM = tmpNowDate;
        me.url = me.sys_id + "/" + me.id + "/fncControlNenChk";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                $(".FrmRankingInput.cmdDelete").button("disable");
                $(".FrmRankingInput.cmdAction").button("disable");
                return;
            }
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                $(".FrmRankingInput.cmdDelete").button("disable");
                $(".FrmRankingInput.cmdAction").button("disable");
                return;
            }
            me.fncDisplay();
        };
        ajax.send(me.url, "", 0);
    };

    me.SubClearInput = function () {
        $(".FrmRankingInput.txtZensya").val("");
        $(".FrmRankingInput.txtHonsya").val("");
        $(".FrmRankingInput.lblNozoku").val("");
        $(".FrmRankingInput.txtSinUriDaisu").val("");
        $(".FrmRankingInput.txtChuUriDaisu").val("");
        $(".FrmRankingInput.txtSeibiJinin").val("");
        $(".FrmRankingInput.txtCreDt").val("");
    };

    me.fncDisplay = function () {
        $("#FrmRankingInput_sprList").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncRankingDataSel";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] > 0) {
                me.getData = result["data"];
                for (key in result["data"]) {
                    me.col["NENGETU"] = result["data"][key]["NENGETU"];
                    me.col["ZENSYA_JININ"] =
                        result["data"][key]["ZENSYA_JININ"];
                    me.col["HONSYA_JININ"] =
                        result["data"][key]["HONSYA_JININ"];
                    me.col["ATHER_JININ"] = result["data"][key]["ATHER_JININ"];
                    me.col["SINSYA_DAISU"] =
                        result["data"][key]["SINSYA_DAISU"];
                    me.col["CHUKO_DAISU"] = result["data"][key]["CHUKO_DAISU"];
                    me.col["SEIBI_JININ"] = result["data"][key]["SEIBI_JININ"];
                    me.col["CRE_DT"] = result["data"][key]["CRE_DT"];
                    $("#FrmRankingInput_sprList").jqGrid(
                        "addRowData",
                        parseInt(key) + 1,
                        me.col
                    );
                }
                $("#FrmRankingInput_sprList").jqGrid("setSelection", 1, true);
            }
            $(".FrmRankingInput.cmdDelete").button("disable");
        };
        ajax.send(me.url, "", 0);
    };

    me.sprHauthList_CellClick = function () {
        var rowID = $("#FrmRankingInput_sprList").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#FrmRankingInput_sprList").jqGrid("getRowData", rowID);
        //---20151009 li UPD S.
        //$(".FrmRankingInput.cboYM").val(rowData['NENGETU']);
        $(".FrmRankingInput.cboYM").val(rowData["NENGETU"].replace("/", ""));
        //---20151009 li UPD E.
        $(".FrmRankingInput.txtZensya").val(rowData["ZENSYA_JININ"]);
        $(".FrmRankingInput.txtHonsya").val(rowData["HONSYA_JININ"]);
        $(".FrmRankingInput.lblNozoku").val(rowData["ATHER_JININ"]);
        $(".FrmRankingInput.txtSinUriDaisu").val(rowData["SINSYA_DAISU"]);
        $(".FrmRankingInput.txtChuUriDaisu").val(rowData["CHUKO_DAISU"]);
        $(".FrmRankingInput.txtSeibiJinin").val(rowData["SEIBI_JININ"]);
        $(".FrmRankingInput.txtCreDt").val(rowData["CRE_DT"]);
        $(".FrmRankingInput.cmdDelete").button("enable");
    };

    me.IsInputCheck = function () {
        var intRtn = "";
        //画面明細情報.[全社人員]をチェック
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmRankingInput.txtZensya"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn != 0) {
            clsComFnc.ObjSelect = $(".FrmRankingInput.txtZensya");
            clsComFnc.FncMsgBox("W000" + intRtn * -1, "全社人員");
            $(".FrmRankingInput.txtZensya").css(clsComFnc.GC_COLOR_ERROR);
            // $(".FrmRankingInput.txtZensya").select();
            return false;
        }

        //画面明細情報.[本社人員]をチェック
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmRankingInput.txtHonsya"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn != 0) {
            clsComFnc.ObjSelect = $(".FrmRankingInput.txtHonsya");
            clsComFnc.FncMsgBox("W000" + intRtn * -1, "本社人員");
            $(".FrmRankingInput.txtHonsya").css(clsComFnc.GC_COLOR_ERROR);
            // $(".FrmRankingInput.txtHonsya").select();
            return false;
        }

        //画面明細情報.[新車売上台数]をチェック
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmRankingInput.txtSinUriDaisu"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn != 0) {
            clsComFnc.ObjSelect = $(".FrmRankingInput.txtSinUriDaisu");
            clsComFnc.FncMsgBox("W000" + intRtn * -1, "新車売上台数");
            $(".FrmRankingInput.txtSinUriDaisu").css(clsComFnc.GC_COLOR_ERROR);
            // $(".FrmRankingInput.txtSinUriDaisu").select();
            return false;
        }

        //画面明細情報.[中古車売上台数]をチェック
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmRankingInput.txtChuUriDaisu"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn != 0) {
            clsComFnc.ObjSelect = $(".FrmRankingInput.txtChuUriDaisu");
            clsComFnc.FncMsgBox("W000" + intRtn * -1, "中古車売上台数");
            $(".FrmRankingInput.txtChuUriDaisu").css(clsComFnc.GC_COLOR_ERROR);
            // $(".FrmRankingInput.txtChuUriDaisu").select();
            return false;
        }

        //画面明細情報.[整備人員]をチェック
        intRtn = clsComFnc.FncTextCheck(
            $(".FrmRankingInput.txtSeibiJinin"),
            1,
            clsComFnc.INPUTTYPE.NUMBER1
        );
        if (intRtn != 0) {
            clsComFnc.ObjSelect = $(".FrmRankingInput.txtSeibiJinin");
            clsComFnc.FncMsgBox("W000" + intRtn * -1, "整備人員");
            $(".FrmRankingInput.txtSeibiJinin").css(clsComFnc.GC_COLOR_ERROR);
            // $(".FrmRankingInput.txtSeibiJinin").select();
            return false;
        }

        return true;
    };

    me.fncInsert = function () {
        me.url = me.sys_id + "/" + me.id + "/fncInsert";

        var arr = {
            //---20151009 li UPD S.
            //'NENGETU' : $(".FrmRankingInput.cboYM").val(),
            NENGETU:
                $(".FrmRankingInput.cboYM").val().substr(0, 4) +
                "/" +
                $(".FrmRankingInput.cboYM").val().substr(4, 2),
            //---20151009 li UPD E.
            HONSYA_JININ: $(".FrmRankingInput.txtHonsya").val(),
            ZENSYA_JININ: $(".FrmRankingInput.txtZensya").val(),
            ATHER_JININ: $(".FrmRankingInput.lblNozoku").val(),
            SINSYA_DAISU: $(".FrmRankingInput.txtSinUriDaisu").val(),
            CHUKO_DAISU: $(".FrmRankingInput.txtChuUriDaisu").val(),
            SEIBI_JININ: $(".FrmRankingInput.txtSeibiJinin").val(),
            CREATE_DATE: $(".FrmRankingInput.txtCreDt").val(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            me.SubClearInput();
            me.fncDisplay();
            $(".FrmRankingInput.cboYM").select();
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncDelete = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDelete";

        var arr = {
            //---20151009 li UPD S.
            //'NENGETU' : $(".FrmRankingInput.cboYM").val()
            NENGETU:
                $(".FrmRankingInput.cboYM").val().substr(0, 4) +
                "/" +
                $(".FrmRankingInput.cboYM").val().substr(4, 2),
            //---20151009 li UPD E.
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            me.SubClearInput();
            me.fncDisplay();
            $(".FrmRankingInput.cboYM").select();
        };
        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmRankingInput = new R4.FrmRankingInput();
    o_R4_FrmRankingInput.load();
});
