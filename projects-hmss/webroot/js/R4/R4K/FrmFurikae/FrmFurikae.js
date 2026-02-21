/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150923 		  #2162						   BUG								YIN
 * 20151019			  #2221						   BUG								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmFurikae");

R4.FrmFurikae = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmFurikae";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboKeiriBi = "";

    me.col = {
        KEIJO_DT: "",
        DENPY_NO: "",
        KAMOK_CD: "",
        KAMOKNM: "",
        AITE_KMK_CD: "",
        AITE_KMK_NM: "",
        KEIJO_GK: "",
    };

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // me.controls.push(
    // {
    // id : ".FrmFurikae.cmdCsvOut",
    // type : "button",
    // handle : ""
    // });
    me.controls.push({
        id: ".FrmFurikae.cmdInsert",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikae.cmdUpdate",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikae.cmdDelete",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikae.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFurikae.cboKeiriBi",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    me.colModel = [
        {
            name: "KEIJO_DT",
            label: "経理日",
            index: "KEIJO_DT",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "DENPY_NO",
            label: "伝票No.",
            index: "DENPY_NO",
            width: 130,
            align: "left",
            sortable: false,
        },
        {
            name: "KAMOK_CD",
            label: "借方科目ｺｰﾄﾞ",
            index: "KAMOK_CD",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "KAMOKNM",
            label: "借方科目名",
            index: "KAMOKNM",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "AITE_KMK_CD",
            label: "貸方科目ｺｰﾄﾞ",
            index: "AITE_KMK_CD",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "AITE_KMK_NM",
            label: "貸方科目名",
            index: "AITE_KMK_NM",
            width: 160,
            align: "left",
            sortable: false,
        },
        {
            name: "KEIJO_GK",
            label: "金額",
            index: "KEIJO_GK",
            width: 150,
            align: "right",
            sortable: false,
            formatter: "integer",
        },
    ];

    $("#FrmFurikae_sprMeisai").jqGrid({
        datatype: "local",
        // jqgridにデータがなし場合、文字表示しない
        emptyRecordRow: false,
        //20151019 yin upd S
        height: me.ratio === 1.5 ? 230 : 285,
        //20151019 yin upd E
        rownumbers: true,
        colModel: me.colModel,
        ondblClickRow: function (rowId) {
            me.sprMeisai_CellClick(rowId);
        },
    });

    //スプレッド上でエンター押下時に修正処理
    $("#FrmFurikae_sprMeisai").jqGrid("bindKeys", {
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
    $(".FrmFurikae.txtDenpyoNOFrom").on("blur", function () {
        $(".FrmFurikae.txtDenpyoNOTo").val(
            $(".FrmFurikae.txtDenpyoNOFrom").val()
        );
    });

    $(".FrmFurikae.cmdDelete").click(function () {
        me.frmInputShow("cmdDelete");
    });

    $(".FrmFurikae.cboKeiriBi").on("blur", function () {
        //20150923 yin upd S
        //if (clsComFnc.CheckDate2($(".FrmFurikae.cboKeiriBi")) == false)
        if (clsComFnc.CheckDate3($(".FrmFurikae.cboKeiriBi")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmFurikae.cboKeiriBi").val(me.cboKeiriBi);
                $(".FrmFurikae.cboKeiriBi").trigger("focus");
                $(".FrmFurikae.cboKeiriBi").select();
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            return;
        }
    });
    $(".FrmFurikae.cmdUpdate").click(function () {
        me.frmInputShow("cmdUpdate");
    });
    $(".FrmFurikae.cmdInsert").click(function () {
        me.PrpMenteFlg = "INS";
        me.frmInputShow("cmdInsert");
    });

    $(".FrmFurikae.cmdSearch").click(function () {
        $(".FrmFurikae.cmdInsert").button("enable");
        //伝票番号のﾁｪｯｸ
        var txtDenpyoNOFrom = $(".FrmFurikae.txtDenpyoNOFrom")
            .val()
            .toString()
            .trimEnd();
        var txtDenpyoNOTo = $(".FrmFurikae.txtDenpyoNOTo")
            .val()
            .toString()
            .trimEnd();

        if (txtDenpyoNOFrom != "" || txtDenpyoNOTo != "") {
            if (txtDenpyoNOFrom == "" || txtDenpyoNOTo == "") {
                clsComFnc.ObjFocus = $(".FrmFurikae.txtDenpyoNOFrom");
                clsComFnc.FncMsgBox("W0017", "伝票番号の範囲");
                return;
            }
        }
        if (txtDenpyoNOFrom > txtDenpyoNOTo) {
            clsComFnc.ObjSelect = $(".FrmFurikae.txtDenpyoNOFrom");
            clsComFnc.FncMsgBox("W0006", "伝票番号");
            return;
        }

        $("#FrmFurikae_sprMeisai").jqGrid("clearGridData");
        $(".FrmFurikae.cmdUpdate").button("disable");
        $(".FrmFurikae.cmdDelete").button("disable");

        me.subSpreadReShow(false);
    });

    // $(".FrmFurikae.cmdCsvOut").click(function()
    // {
    //
    // });

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
            var rowID = $("#FrmFurikae_sprMeisai").jqGrid(
                "getGridParam",
                "selrow"
            );
            var rowData = $("#FrmFurikae_sprMeisai").jqGrid(
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
            .attr("id", "DialogDivFurikae")
            .insertAfter($("#FrmFurikae"));

        $("#DialogDivFurikae").dialog({
            autoOpen: false,
            modal: true,
            height: me.ratio === 1.5 ? 558 : 700,
            width: 670,
            resizable: false,
            close: function () {
                shortcut.remove("F9");
                $("#DialogDivFurikae").remove();

                if (me.PrpMenteFlg == "INS") {
                    me.subSpreadReShow(true);
                } else {
                    me.subSpreadReShow(false);
                }
            },
        });

        var frmId = "FrmFurikaeEdit";
        var url = me.sys_id + "/" + frmId;

        ajax.receive = function (result) {
            $("#DialogDivFurikae").html(result);

            $("#DialogDivFurikae").dialog("option", "title", "振替データ入力");
            $("#DialogDivFurikae").dialog("open");
        };
        ajax.send(url, me.data, 0);
    };

    me.frmFurikae_Load = function () {
        //画面項目ｸﾘｱ
        me.subFormClear();

        //コントロールマスタ存在ﾁｪｯｸ
        me.url = me.sys_id + "/" + me.id + "/ControlCheck";

        ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);

                $(".FrmFurikae.cmdUpdate").button("disable");
                $(".FrmFurikae.cmdDelete").button("disable");
                $(".FrmFurikae.cmdInsert").button("disable");
                $(".FrmFurikae.cmdSearch").button("disable");
                $(".FrmFurikae.cboKeiriBi").attr("disabled", "disabled");
                // $(".FrmFurikae.cboKeiriBi").val(tmpNowDate);
                return;
            }
            if (result["row"] == 0) {
                $(".FrmFurikae.cmdUpdate").button("disable");
                $(".FrmFurikae.cmdDelete").button("disable");
                $(".FrmFurikae.cmdInsert").button("disable");
                $(".FrmFurikae.cmdSearch").button("disable");
                $(".FrmFurikae.cboKeiriBi").attr("disabled", "disabled");
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
                return;
            }

            //振替パターンデータ読み込み
            // me.url = me.sys_id + '/' + me.id + '/fncTorikomiPatternData';
            // ajax.receive = function(result)
            // {
            // console.log(result);
            // result = eval('(' + result + ')');
            //
            // if (result['result'] == false)
            // {
            // if (result['errMsg'] != "I9999")
            // {
            // clsComFnc.FncMsgBox("E9999", result['data']);
            // clsComFnc.FncMsgBox("E9999", "振替パターンデータの取込みに失敗しました！");
            // return;
            // }
            // clsComFnc.FncMsgBox("I9999", result['data']);
            //
            // }

            $(".FrmFurikae.cmdUpdate").button("disable");
            $(".FrmFurikae.cmdDelete").button("disable");
            //スプレッドを表示
            me.subSpreadReShow(true);
            // }
            //		ajax.send(me.url, me.data, 0);
        };
        ajax.send(me.url, me.data, 0);
    };

    me.subSpreadReShow = function (blnStart) {
        //データグリッドの再表示
        $("#FrmFurikae_sprMeisai").jqGrid("clearGridData");
        me.url = me.sys_id + "/" + me.id + "/fncSearchFurikae";

        var arr = {
            KEIJYOBI: $(".FrmFurikae.cboKeiriBi").val(),
            DENPYOF: $(".FrmFurikae.txtDenpyoNOFrom").val(),
            DENPYOT: $(".FrmFurikae.txtDenpyoNOTo").val(),
        };

        me.data = {
            request: arr,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                if (!blnStart) {
                    $(".FrmFurikae.cmdInsert").button("disable");
                } else {
                    $(".FrmFurikae.cboKeiriBi").attr("disabled", "disabled");
                    $(".FrmFurikae.cmdSearch").button("disable");
                    $(".FrmFurikae.cmdInsert").button("disable");
                }

                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                if (!blnStart) {
                    clsComFnc.ObjSelect = $(".FrmFurikae.txtDenpyoNOFrom");
                    clsComFnc.FncMsgBox("I0001");
                }

                $(".FrmFurikae.cmdUpdate").button("disable");
                $(".FrmFurikae.cmdDelete").button("disable");
                return;
            } else {
                for (key in result["data"]) {
                    var strKeijyo = result["data"][key]["KEIJO_DT"];
                    // strKeijyo = String(strKeijyo).padRight(6);
                    strKeijyo =
                        strKeijyo.substr(0, 4) +
                        "/" +
                        strKeijyo.substr(4, 2) +
                        "/" +
                        strKeijyo.substr(6, 2);
                    me.col["KEIJO_DT"] = strKeijyo;
                    me.col["DENPY_NO"] = result["data"][key]["DENPY_NO"];
                    me.col["KAMOK_CD"] = result["data"][key]["KAMOK_CD"];
                    me.col["KAMOKNM"] = result["data"][key]["KAMOKNM"];
                    me.col["AITE_KMK_CD"] = result["data"][key]["AITE_KMK_CD"];
                    me.col["AITE_KMK_NM"] = result["data"][key]["AITE_KMK_NM"];
                    me.col["KEIJO_GK"] = result["data"][key]["KEIJO_GK"];

                    $("#FrmFurikae_sprMeisai").jqGrid(
                        "addRowData",
                        parseInt(key),
                        me.col
                    );
                }
                if (!blnStart) {
                    $("#FrmFurikae_sprMeisai").trigger("focus");
                } else {
                    $(".FrmFurikae.cboKeiriBi").trigger("focus");
                }

                $("#FrmFurikae_sprMeisai").jqGrid("setSelection", 0, true);
                $(".FrmFurikae.cmdUpdate").button("enable");
                $(".FrmFurikae.cmdDelete").button("enable");
            }
        };

        ajax.send(me.url, me.data, 0);
    };

    me.sprMeisai_CellClick = function () {
        me.frmInputShow("sprList");
    };

    me.subFormClear = function () {
        var myDate = new Date();
        var tmpMonth = (myDate.getMonth() + 1).toString();
        if (tmpMonth.length < 2) {
            tmpMonth = "0" + tmpMonth.toString();
        }
        var tmpNowDate = myDate.getFullYear().toString() + tmpMonth.toString();
        // $(".FrmFurikae.cboKeiriBi").val('2013/05');
        $(".FrmFurikae.cboKeiriBi").val(tmpNowDate);
        $(".FrmFurikae.txtDenpyoNOFrom").val("");
        $(".FrmFurikae.txtDenpyoNOTo").val("");
        $("#FrmFurikae_sprMeisai").jqGrid("clearGridData");
        me.cboKeiriBi = tmpNowDate;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFurikae = new R4.FrmFurikae();
    o_R4_FrmFurikae.load();

    o_R4K_R4K.FrmFurikae = o_R4_FrmFurikae;
});
