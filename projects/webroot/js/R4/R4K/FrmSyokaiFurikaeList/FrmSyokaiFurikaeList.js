/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -------------------------------------------------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150922                  #2162                   BUG                         Yuanjh
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * -------------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSyokaiFurikaeList");

R4.FrmSyokaiFurikaeList = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmSyokaiFurikaeList";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboKeiriBi = "";

    me.col = {
        KEIJO_DT: "",
        DENPY_NO: "",
        BUSYO_CD: "",
        MOT_SYAIN_NO: "",
        SYAIN_NM: "",
        GOUKEI: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmSyokaiFurikaeList.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikaeList.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikaeList.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikaeList.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSyokaiFurikaeList.cboKeiriBi",
        //-- 20150922 Yuanjh UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150922 Yuanjh UPD E.
        handle: "",
    });

    me.colModel = [
        {
            name: "KEIJO_DT",
            label: "年月",
            index: "KEIJO_DT",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "DENPY_NO",
            label: "伝票No.",
            index: "DENPY_NO",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "BUSYO_CD",
            label: "部署",
            index: "BUSYO_CD",
            width: 100,
            align: "left",
            sortable: false,
        },
        {
            name: "MOT_SYAIN_NO",
            label: "社員番号",
            index: "MOT_SYAIN_NO",
            width: 180,
            align: "left",
            sortable: false,
        },
        {
            name: "SYAIN_NM",
            label: "社員名",
            index: "SYAIN_NM",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "GOUKEI",
            label: "合計金額",
            index: "GOUKEI",
            width: 160,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
    ];

    $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid({
        datatype: "local",
        height: me.ratio === 1.5 ? 230 : 282,
        rownumbers: true,
        emptyRecordRow: false,
        colModel: me.colModel,
        ondblClickRow: function (rowId) {
            me.sprMeisai_CellClick(rowId);
        },
    });

    $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid("setGroupHeaders", {
        useColSpanStyle: true,
        groupHeaders: [
            {
                startColumnName: "BUSYO_CD",
                numberOfColumns: 3,
                titleText: "振替元",
            },
        ],
    });

    //スプレッド上でエンター押下時に修正処理
    $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid("bindKeys", {
        onEnter: function (rowid) {
            me.sprMeisai_CellClick(rowid);
        },
    });

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
    $(".FrmSyokaiFurikaeList.txtDenpyoNOFrom").on("blur", function () {
        $(".FrmSyokaiFurikaeList.txtDenpyoNOTo").val(
            $(".FrmSyokaiFurikaeList.txtDenpyoNOFrom").val()
        );
    });

    $(".FrmSyokaiFurikaeList.cmdDelete").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteFurikae;
        clsComFnc.FncMsgBox("QY004");
    });

    $(".FrmSyokaiFurikaeList.cboKeiriBi").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmSyokaiFurikaeList.cboKeiriBi")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (
            clsComFnc.CheckDate3($(".FrmSyokaiFurikaeList.cboKeiriBi")) == false
        ) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSyokaiFurikaeList.cboKeiriBi").val(me.cboKeiriBi);
                $(".FrmSyokaiFurikaeList.cboKeiriBi").trigger("focus");
                $(".FrmSyokaiFurikaeList.cboKeiriBi").select();
                $(".FrmSyokaiFurikaeList.cmdInsert").button("disable");
                $(".FrmSyokaiFurikaeList.cmdUpdate").button("disable");
                $(".FrmSyokaiFurikaeList.cmdDelete").button("disable");
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSyokaiFurikaeList.cmdInsert").button("enable");
            $(".FrmSyokaiFurikaeList.cmdUpdate").button("enable");
            $(".FrmSyokaiFurikaeList.cmdDelete").button("enable");
        }
    });
    $(".FrmSyokaiFurikaeList.cmdUpdate").click(function () {
        me.frmInputShow("cmdUpdate");
    });
    $(".FrmSyokaiFurikaeList.cmdInsert").click(function () {
        me.PrpMenteFlg = "INS";
        me.frmInputShow("cmdInsert");
    });

    $(".FrmSyokaiFurikaeList.cmdSearch").click(function () {
        var txtDenpyoNOFrom = $(".FrmSyokaiFurikaeList.txtDenpyoNOFrom")
            .val()
            .trimEnd();
        var txtDenpyoNOTo = $(".FrmSyokaiFurikaeList.txtDenpyoNOTo")
            .val()
            .trimEnd();
        if (txtDenpyoNOFrom != "" || txtDenpyoNOTo != "") {
            if (txtDenpyoNOFrom > txtDenpyoNOTo) {
                clsComFnc.ObjSelect = $(
                    ".FrmSyokaiFurikaeList.txtDenpyoNOFrom"
                );
                clsComFnc.FncMsgBox("W0006", "伝票番号");
                return;
            }
        }

        $(".FrmSyokaiFurikaeList.cmdUpdate").button("disable");
        $(".FrmSyokaiFurikaeList.cmdDelete").button("disable");

        me.subSpreadReShow(false);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.init_control = function () {
        base_init_control();

        me.frmFurikae_Load();
    };

    me.frmInputShow = function (sender) {
        if (
            sender == "cmdUpdate" ||
            sender == "sprList" ||
            sender == "cmdDelete"
        ) {
            if (sender == "cmdDelete") {
                me.PrpMenteFlg = "DEL";
            } else {
                me.PrpMenteFlg = "UPD";
            }
            var rowID = $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
                "getRowData",
                rowID
            );

            me.prpKeijyoBi = rowData["KEIJO_DT"];
            me.prpDenpy_NO = rowData["DENPY_NO"];
        }
        me.ShowDialog();
    };

    me.ShowDialog = function () {
        $("<div></div>")
            .prop("id", "DialogDiv")
            .insertAfter($("#FrmSyokaiFurikaeList"));

        $("#DialogDiv").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 558 : 700,
            width: 900,
            resizable: false,
            close: function () {
                shortcut.remove("F9");
                shortcut.remove("F3");
                $("#DialogDiv").remove();
                me.subSpreadReShow(false);
            },
        });

        var frmId = "FrmSyokaiFurikae";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#DialogDiv").html(result);

            $("#DialogDiv").dialog("option", "title", "中古紹介料振替入力");
            $("#DialogDiv").dialog("open");
        };
        ajax.send(url, me.data, 0);
    };

    me.frmFurikae_Load = function () {
        //コントロールマスタ存在ﾁｪｯｸ
        me.url = me.sys_id + "/" + me.id + "/frmFurikae_Load";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            var myDate = new Date();
            var tmpMonth = (myDate.getMonth() + 1).toString();
            if (tmpMonth.length < 2) {
                tmpMonth = "0" + tmpMonth.toString();
            }
            // var tmpNowDate =
            //     myDate.getFullYear().toString() + tmpMonth.toString();
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                //-- 20150922 Yuanjh UPD S.
                //$(".FrmSyokaiFurikaeList.cboKeiriBi").val(tmpNowDate);
                $(".FrmSyokaiFurikaeList.cboKeiriBi").val(
                    myDate.getFullYear().toString() + tmpMonth.toString()
                );
                //-- 20150922 Yuanjh UPD S.
                $(".FrmSyokaiFurikaeList.cmdUpdate").button("disable");
                $(".FrmSyokaiFurikaeList.cmdDelete").button("disable");
                return;
            }
            var strTougetu = clsComFnc
                .FncNv(result["data"][0]["TOUGETU"])
                .toString();
            strTougetu = strTougetu.split("/");
            //-- 20150922 Yuanjh UPD S.
            //$(".FrmSyokaiFurikaeList.cboKeiriBi").val(strTougetu[0] + '/' + strTougetu[1]);
            $(".FrmSyokaiFurikaeList.cboKeiriBi").val(
                strTougetu[0] + strTougetu[1]
            );
            me.cboKeiriBi = $(".FrmSyokaiFurikaeList.cboKeiriBi").val();
            //-- 20150922 Yuanjh UPD E.
            $(".FrmSyokaiFurikaeList.cboKeiriBi").trigger("focus");
            $(".FrmSyokaiFurikaeList.cmdUpdate").button("disable");
            $(".FrmSyokaiFurikaeList.cmdDelete").button("disable");

            //スプレッドを表示
            me.subSpreadReShow(true);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.subSpreadReShow = function (blnStart) {
        //データグリッドの再表示
        $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncSearchFurikae";

        var arr = {
            //-- 20150922 Yuanjh UPD S.
            //'KEIJYOBI' : $(".FrmSyokaiFurikaeList.cboKeiriBi").val(),
            KEIJYOBI:
                $(".FrmSyokaiFurikaeList.cboKeiriBi").val().substr(0, 4) +
                "/" +
                $(".FrmSyokaiFurikaeList.cboKeiriBi").val().substr(4, 2),
            //-- 20150922 Yuanjh UPD E.
            DENPYOF: $(".FrmSyokaiFurikaeList.txtDenpyoNOFrom").val(),
            DENPYOT: $(".FrmSyokaiFurikaeList.txtDenpyoNOTo").val(),
            SYAINNO: $(".FrmSyokaiFurikaeList.txtSyainNO").val(),
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
            if (result["row"] == 0) {
                if (!blnStart) {
                    clsComFnc.ObjSelect = $(
                        ".FrmSyokaiFurikaeList.txtDenpyoNOFrom"
                    );
                    clsComFnc.FncMsgBox("I0001");
                }

                $(".FrmSyokaiFurikaeList.cmdUpdate").button("disable");
                $(".FrmSyokaiFurikaeList.cmdDelete").button("disable");
                return;
            } else {
                for (key in result["data"]) {
                    var strKeijyo = result["data"][key]["KEIJO_DT"];
                    strKeijyo = String(strKeijyo).padRight(6);
                    strKeijyo = strKeijyo.substr(0, 4) + strKeijyo.substr(4, 2);
                    me.col["KEIJO_DT"] = strKeijyo;
                    me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                    me.col["BUSYO_CD"] = result["data"][key]["BUSYO_CD"];
                    me.col["MOT_SYAIN_NO"] =
                        result["data"][key]["MOT_SYAIN_NO"];
                    me.col["SYAIN_NM"] = result["data"][key]["SYAIN_NM"];
                    me.col["GOUKEI"] = result["data"][key]["GOUKEI"];

                    $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );
                }
                if (!blnStart) {
                    $("#FrmSyokaiFurikaeList_sprMeisai").trigger("focus");
                } else {
                    $(".FrmSyokaiFurikaeList.cboKeiriBi").trigger("focus");
                }

                $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
                    "setSelection",
                    0,
                    true
                );
                $(".FrmSyokaiFurikaeList.cmdUpdate").button("enable");
                $(".FrmSyokaiFurikaeList.cmdDelete").button("enable");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteFurikae = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteFurikae";

        var rowID = $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
            "getGridParam",
            "selrow"
        );
        var rowData = $("#FrmSyokaiFurikaeList_sprMeisai").jqGrid(
            "getRowData",
            rowID
        );

        var arr = {
            KEIJYO: rowData["KEIJO_DT"],
            DENPYO: rowData["DENPY_NO"],
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                clsComFnc.FncMsgBox("E0004");
                return;
            }
            me.subSpreadReShow(false);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.sprMeisai_CellClick = function () {
        me.frmInputShow("sprList");
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSyokaiFurikaeList = new R4.FrmSyokaiFurikaeList();
    o_R4_FrmSyokaiFurikaeList.load();

    o_R4K_R4K.FrmSyokaiFurikaeList = o_R4_FrmSyokaiFurikaeList;
});
