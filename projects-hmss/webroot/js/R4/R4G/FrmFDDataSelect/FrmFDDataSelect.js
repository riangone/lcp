/**
 * 説明：
 *
 *
 * @author ciyuanchen
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                                       担当
 * YYYYMMDD            #ID                          XXXXXX                                   GSDL
 * 20171206            #2807                        登録予定データ抽出画面横スクロールバーがある             ciyuanchen
 * 20201117            bug                             AJAX.SEND パラメータ数                     lqs
 * 20201117            表示倍率：125％の場合は、「Chrome」でjqGridの見出しが間違っていま          lqs
 * 20201119           年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。  lqs
 * * --------------------------------------------------------------------------------------------
 */
/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmFDDataSelect");

R4.FrmFDDataSelect = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "R4G/FrmFDDataSelect";
    me.sys_id = "R4G";
    //----jqGrid 変数 start----
    me.grid_id = "#FrmFDDataSelect_sprList";
    me.g_url = "R4G/FrmFDDataSelect/fncFrmFDDataSelect";
    me.pager = "#divFrmFDDataSelect_pager";
    me.sidx = "CHUMN_NO";
    me.data = new Array();

    me.option = {
        pagerpos: "left",
        multiselect: true,
        rownumbers: true,
        rowNum: 500000,
        // rowList : [10, 50, 100],
        caption: "",
        multiselectWidth: 30,
        // 20250609 lujunxia ins s
        shrinkToFit: me.ratio === 1.5,
        // 20250609 lujunxia ins s
    };

    me.colModel = [
        {
            // 20201117 lqs upd S
            // label : 'FD作成',
            label: "FD<br>作成",
            // 20201117 lqs upd E
            name: "FD_CRE",
            index: "FD_CRE",
            width: "35",
            formatter: "checkbox",
            sortable: false,
            align: "center",
        },
        {
            // 20250609 lujunxia upd s
            label: "補完<br>入力",
            // 20250609 lujunxia upd s
            name: "INP_FLG",
            index: "INP_FLG",
            width: "35",
            formatter: "checkbox",
            sortable: false,
            align: "center",
        },
        {
            label: "型式類別",
            name: "KATASIKI",
            index: "KATASIKI",
            width: "90",
            sortable: false,
        },
        {
            label: "車台番号",
            name: "CARNO",
            index: "CARNO",
            width: "120",
            sortable: false,
        },
        {
            label: "氏名",
            name: "SHI_USER_NM",
            index: "SHI_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            label: "住所",
            name: "SHI_ADDRESS",
            index: "SHI_ADDRESS",
            width: "170",
            sortable: false,
        },
        {
            label: "氏名",
            name: "SYO_USER_NM",
            index: "SYO_USER_NM",
            width: "100",
            sortable: false,
        },
        {
            label: "住所",
            name: "SYO_ADDRESS",
            index: "SYO_ADDRESS",
            width: "170",
            sortable: false,
        },
        {
            label: "登録予定日",
            name: "TOU_Y_DT",
            index: "TOU_Y_DT",
            width: "71",
            hidden: true,
            sortable: false,
        },
        {
            label: "注文書番号",
            name: "CHUMN_NO",
            index: "CHUMN_NO",
            width: "100",
            sortable: false,
            key: true,
        },
    ];
    //----jqGrid 変数 end----
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmFDDataSelect.cmdAction",
        type: "button",
        handle: "",
        enable: "false",
    });
    me.controls.push({
        id: ".FrmFDDataSelect.cmdSearch",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFDDataSelect.cboTourokuFrom",
        type: "datepicker",
        handle: "",
    });
    me.controls.push({
        id: ".FrmFDDataSelect.cboTourokuTo",
        type: "datepicker",
        handle: "",
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    //ShiftキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.EnterKeyDown();

    //Enterキーのバインド
    clsComFnc.TabKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //**********************************************************************
    //処 理 名：検索ボタンクリック
    //関 数 名：cmdSearch_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：検索ボタンクリックして、検索結果を表示する
    //**********************************************************************
    $(".FrmFDDataSelect.cmdSearch").click(function () {
        //登録予定日のﾁｪｯｸ
        var startDate = $(".FrmFDDataSelect.cboTourokuFrom").val();
        var endDate = $(".FrmFDDataSelect.cboTourokuTo").val();

        startDate = startDate.replace(/\//g, "");
        endDate = endDate.replace(/\//g, "");

        if (startDate > endDate) {
            clsComFnc.ObjFocus = $(".FrmFDDataSelect.cboTourokuFrom");
            clsComFnc.FncMsgBox("W0017", "登録予定日の範囲");
            return;
        }

        //----jqGrid start----
        funcSearchAllData(true);
        //----jqGrid end----
    });

    //**********************************************************************
    //処 理 名：更新ボタンクリック
    //関 数 名：cmdAction.click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：データ更新後、 データグリッドを再表示する
    //				 既に存在しているものはUPDATE、新規のものはINSERTする
    //**********************************************************************
    $(".FrmFDDataSelect.cmdAction").click(function () {
        clsComFnc.MsgBoxBtnFnc.Yes = UpdateDeal;
        clsComFnc.FncMsgBox("QY999", "更新します。よろしいですか？");
    });

    //**********************************************************************
    //処 理 名：登録予定日From自動検証
    //関 数 名：cboTourokuFrom.change
    //引    数：無し
    //戻 り 値：無し
    //処理説明：登録予定日From自動検証
    //**********************************************************************
    $(".FrmFDDataSelect.cboTourokuFrom").change(function () {
        if (clsComFnc.CheckDate($(".FrmFDDataSelect.cboTourokuFrom"))) {
            $(".FrmFDDataSelect.cboTourokuTo").val(
                $(".FrmFDDataSelect.cboTourokuFrom").val()
            );
        }
    });

    //登録予定日Fromフォーカスアウト
    $(".FrmFDDataSelect.cboTourokuFrom").on("blur", function () {
        if (
            clsComFnc.CheckDate($(".FrmFDDataSelect.cboTourokuFrom")) == false
        ) {
            var myDate = new Date();

            $(".FrmFDDataSelect.cboTourokuFrom").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201119 lqs upd S
            // $(".FrmFDDataSelect.cboTourokuFrom").focus();
            // $(".FrmFDDataSelect.cboTourokuFrom").select();
            window.setTimeout(function () {
                $(".FrmFDDataSelect.cboTourokuFrom").trigger("focus");
                $(".FrmFDDataSelect.cboTourokuFrom").select();
            }, 0);
            // 20201119 lqs upd E
            $(".FrmFDDataSelect.cmdSearch").button("disable");
            return false;
        } else {
            $(".FrmFDDataSelect.cmdSearch").button("enable");
            $(".FrmFDDataSelect.cboTourokuTo").val(
                $(".FrmFDDataSelect.cboTourokuFrom").val()
            );
        }
    });

    //登録予定日Toフォーカスアウト
    $(".FrmFDDataSelect.cboTourokuTo").on("blur", function () {
        if (clsComFnc.CheckDate($(".FrmFDDataSelect.cboTourokuTo")) == false) {
            var myDate = new Date();

            $(".FrmFDDataSelect.cboTourokuTo").datepicker(
                "setDate",
                myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
            );
            // 20201119 lqs upd S
            // $(".FrmFDDataSelect.cboTourokuTo").focus();
            // $(".FrmFDDataSelect.cboTourokuTo").select();
            window.setTimeout(function () {
                $(".FrmFDDataSelect.cboTourokuTo").trigger("focus");
                $(".FrmFDDataSelect.cboTourokuTo").select();
            }, 0);
            // 20201119 lqs upd E
            $(".FrmFDDataSelect.cmdSearch").button("disable");
            return false;
        } else {
            $(".FrmFDDataSelect.cmdSearch").button("enable");
        }
    });

    //**********************************************************************
    //処 理 名：フォームロード
    //関 数 名：frmFDDataSelect_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面読み込み処理
    //**********************************************************************
    var base_load = me.load;
    me.load = function () {
        base_load();
        subFormClear();

        //----jqGrid start----
        funcSearchAllData(false);
        //----jqGrid end----
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    function fncCompleteDeal(bFlagStart) {
        if (!bFlagStart) {
            // 20250609 lujunxia upd s
            $("#jqgh_FrmFDDataSelect_sprList_cb").append("<br>更新");
            // 20250609 lujunxia upd e
        }

        var rowArray = $(me.grid_id).jqGrid("getGridParam", "records");

        if (rowArray > 0) {
            $(".FrmFDDataSelect.cmdAction").button("enable");
            //compare data
            funcCompareData();
            $("#cb_FrmFDDataSelect_sprList").click();
        } else {
            if (bFlagStart) {
                clsComFnc.ObjFocus = $(".FrmFDDataSelect.cboTourokuFrom");
                clsComFnc.FncMsgBox("I0001");
            } else {
                $(".FrmFDDataSelect.cboTourokuFrom").trigger("focus");
            }

            $(".FrmFDDataSelect.cmdAction").button("disable");
            return;
        }
    }

    //データを表示
    function funcSearchAllData(bStartFlag) {
        me.data = {
            Misakusei: $(".FrmFDDataSelect.chkMisakusei").prop("checked"),
            KAISHI: $(".FrmFDDataSelect.cboTourokuFrom").val(),
            SYURYO: $(".FrmFDDataSelect.cboTourokuTo").val(),
        };

        me.complete_fun = function () {
            fncCompleteDeal(bStartFlag);
        };
        //------ show jqgrid
        if (bStartFlag) {
            gdmz.common.jqgrid.reload(me.grid_id, me.data, me.complete_fun);
        } else {
            gdmz.common.jqgrid.show(
                me.grid_id,
                me.g_url,
                me.colModel,
                me.pager,
                me.sidx,
                me.option,
                me.data,
                me.complete_fun
            );
            // 20171206 CIYUANCHEN UPD S
            //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1050);
            // 20250609 lujunxia ins s
            if (me.ratio === 1.5) {
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 1030);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 208);
            } else {
                // 20250609 lujunxia ins e
                gdmz.common.jqgrid.set_grid_width(me.grid_id, 1070);
                // 20171206 CIYUANCHEN UPD E
                // 20180305 CIYUANCHEN UPD S
                //gdmz.common.jqgrid.set_grid_height(me.grid_id, 250);
                gdmz.common.jqgrid.set_grid_height(me.grid_id, 260);
                // 20180305 CIYUANCHEN UPD E
            }
            $("#FrmFDDataSelect_sprList").jqGrid("setGroupHeaders", {
                useColSpanStyle: true,
                groupHeaders: [
                    {
                        startColumnName: "SHI_USER_NM",
                        numberOfColumns: 2,
                        titleText: "使用者",
                    },
                    {
                        startColumnName: "SYO_USER_NM",
                        numberOfColumns: 2,
                        titleText: "所有者",
                    },
                ],
            });

            $("#jqgh_FrmFDDataSelect_sprList_FD_CRE").css("top", "4px");
            $("#jqgh_FrmFDDataSelect_sprList_INP_FLG").css("top", "4px");
        }
    }

    //**********************************************************************
    //処 理 名：画面項目初期化
    //関 数 名：subFormClear
    //引    数：無し
    //戻 り 値：無し
    //処理説明：画面項目を初期化する
    //**********************************************************************
    function subFormClear() {
        var myDate = new Date();

        $(".FrmFDDataSelect.cboTourokuFrom").datepicker(
            "setDate",
            myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
        );
        $(".FrmFDDataSelect.cboTourokuTo").datepicker(
            "setDate",
            myDate.getFullYear + myDate.getMonth + (myDate.getDate + 1)
        );
        $(".FrmFDDataSelect.chkMisakusei").prop("checked", false);
    }

    function funcCompareData() {
        var data = $(me.grid_id).jqGrid("getDataIDs");

        for (key in data) {
            var rowData = $(me.grid_id).jqGrid("getRowData", data[key]);

            if (
                clsComFnc.FncNv(rowData["SHI_USER_NM"]) ==
                clsComFnc.FncNv(rowData["SYO_USER_NM"])
            ) {
                $(me.grid_id).setCell(key, "SYO_USER_NM", null);
            }

            if (
                clsComFnc.FncNv(rowData["SHI_ADDRESS"]) ==
                clsComFnc.FncNv(rowData["SYO_ADDRESS"])
            ) {
                $(me.grid_id).setCell(key, "SYO_ADDRESS", null);
            }
        }
    }

    function UpdateDeal() {
        //使用者名
        var strShiyouNm = "";
        //帳票データ
        me.DsErrorListPrintArray = new Array();
        //更新データ
        updateArray = new Array();
        //選択行
        var selectedGridData = $(me.grid_id).jqGrid(
            "getGridParam",
            "selarrrow"
        );

        //更新にチェックが1件も入っていない場合
        if (selectedGridData.length == 0) {
            clsComFnc.FncMsgBox("W9999", "更新対象を選択してください");
            return;
        }

        //ｽﾌﾟﾚｯﾄﾞの行分繰り返す
        //更新ﾌﾗｸﾞが立っているもののみINSERT
        for (keyIds in selectedGridData) {
            var tableData = $(me.grid_id).jqGrid(
                "getRowData",
                selectedGridData[keyIds]
            );

            strShiyouNm = "";

            strShiyouNm = String(clsComFnc.FncNv(tableData["SHI_USER_NM"]));

            strShiyouNm = strShiyouNm.replace("㈱", "株式会社");
            strShiyouNm = strShiyouNm.replace("㈲", "有限会社");

            if (strShiyouNm.indexOf("株式会社") == 0) {
                if (strShiyouNm.indexOf("株式会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("株式会社") > 0) {
                if (strShiyouNm.indexOf("　株式会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("株式会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("株式会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("株式会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("株式会社"));
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") == 0) {
                if (strShiyouNm.indexOf("有限会社　") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, 4) + "　" + strShiyouNm.substr(4);
                    strShiyouNm =
                        strShiyouNm.substring(0, 4) + strShiyouNm.substring(4);
                    //20160601 Upd End
                }
            } else if (strShiyouNm.indexOf("有限会社") > 0) {
                if (strShiyouNm.indexOf("　有限会社") == -1) {
                    //20160601 Upd Start
                    //					strShiyouNm = strShiyouNm.substr(0, strShiyouNm.indexOf("有限会社")) + "　" + strShiyouNm.substr(strShiyouNm.indexOf("有限会社"));
                    strShiyouNm =
                        strShiyouNm.substring(
                            0,
                            strShiyouNm.indexOf("有限会社")
                        ) +
                        strShiyouNm.substring(strShiyouNm.indexOf("有限会社"));
                    //20160601 Upd End
                }
            }

            //所有者は所有者CD入力の場合があるので、ここでのイレギュラーリストの対象から外すように変更
            //使用者名と所有者名にエラーが存在する場合は、イレギュラーリストに出力し、DBには登録しない。正常なもののみDBに登録
            if (clsComFnc.GetByteCount(strShiyouNm) > 34) {
                var tmpArr = {
                    CHUMN_NO: "",
                    TOU_Y_DT: "",
                    SHI_USER_NM: "",
                    SYO_USER_NM: "",
                };

                tmpArr["TOU_Y_DT"] = tableData["TOU_Y_DT"];
                tmpArr["CHUMN_NO"] = tableData["CHUMN_NO"];
                tmpArr["SHI_USER_NM"] = tableData["SHI_USER_NM"];
                tmpArr["SYO_USER_NM"] = tableData["SYO_USER_NM"];

                me.DsErrorListPrintArray.push(tmpArr);
            } else {
                updateArray.push(tableData["CHUMN_NO"]);
            }
        }

        if (me.DsErrorListPrintArray.length > 0 && updateArray.length <= 0) {
            //プレビュー表示
            //イレギュラーデータが存在する場合はリストに出力
            // 20201117 lqs upd S
            // subLogManage("");
            subLogManage();
            // 20201117 lqs upd E
        } else if (updateArray.length > 0) {
            //既に軽報告データに登録されているかのチェック
            // 20201117 lqs upd S
            // subUpdateAjax(updateArray, "");
            subUpdateAjax(updateArray);
            // 20201117 lqs upd E
        } else {
            //正常終了
            clsComFnc.ObjFocus = $(".FrmFDDataSelect.cboTourokuFrom");
            clsComFnc.FncMsgBox("I0008");
        }
    }

    //ログ管理
    // 20201117 lqs upd S
    // function subLogManage(isFirstAjax)
    function subLogManage() {
        // 20201117 lqs upd E
        //ログ管理テーブルに登録
        var funcName = "funLogManage";
        var url = me.id + "/" + funcName;
        var arrayVal = {
            ERR_LIST: me.DsErrorListPrintArray,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == false) {
                //エラーの場合
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            } else {
                //showpdf
                window.open(jsonResult.json[0]["report_path"]);

                if (updateArray.length <= 0) {
                    //正常終了
                    clsComFnc.ObjFocus = $(".FrmFDDataSelect.cboTourokuFrom");
                    clsComFnc.FncMsgBox("I0008");
                }
            }
        };
        // 20201117 lqs upd S
        // ajax.send(url, me.data, 0, isFirstAjax);
        ajax.send(url, me.data, 0);
        // 20201117 lqs upd E
        // $.ajax(
        // {
        // type : "POST",
        // url : url,
        // data : me.data,
        //
        // success : function(result)
        // {
        // var jsonResult =
        // {
        // };
        // var txtResult = '{ "json" : [' + result + ']}';
        // jsonResult = eval("(" + txtResult + ")");
        //
        // if (jsonResult.json[0]['result'] == false)
        // {
        // //エラーの場合
        // clsComFnc.FncMsgBox("E9999", jsonResult.json[0]['data']);
        // return;
        // }
        // else
        // {
        // //showpdf
        // window.open(jsonResult.json[0]['report_path']);
        // }
        // }
        // });
    }

    //既に軽報告データに登録されているかのチェック
    // 20201117 lqs upd S
    // function subUpdateAjax(upArray, isFirstAjax)
    function subUpdateAjax(upArray) {
        // 20201117 lqs upd E
        var funcName = "funExistCheck";
        var url = me.id + "/" + funcName;

        me.data = {
            request: upArray,
        };

        ajax.receive = function (result) {
            var jsonResult = {};
            var txtResult = '{ "json" : [' + result + "]}";
            jsonResult = eval("(" + txtResult + ")");

            if (jsonResult.json[0]["result"] == false) {
                //エラーの場合
                clsComFnc.FncMsgBox("E9999", jsonResult.json[0]["data"]);
                return;
            } else {
                if (me.DsErrorListPrintArray.length > 0) {
                    // 20201117 lqs upd S
                    // subLogManage("");
                    subLogManage();
                    // 20201117 lqs upd E
                }

                //正常終了
                clsComFnc.ObjFocus = $(".FrmFDDataSelect.cboTourokuFrom");
                clsComFnc.FncMsgBox("I0008");
            }
        };
        // 20201117 lqs upd S
        // ajax.send(url, me.data, 0, isFirstAjax);
        ajax.send(url, me.data, 0);
        // 20201117 lqs upd E
        // $.ajax(
        // {
        // type : "POST",
        // url : url,
        // data : me.data,
        //
        // success : function(result)
        // {
        // var jsonResult =
        // {
        // };
        // var txtResult = '{ "json" : [' + result + ']}';
        // jsonResult = eval("(" + txtResult + ")");
        //
        // if (jsonResult.json[0]['result'] == false)
        // {
        // //エラーの場合
        // clsComFnc.FncMsgBox("E9999", jsonResult.json[0]['data']);
        // return;
        // }
        //
        // else
        // {
        // //正常終了
        // clsComFnc.ObjFocus = $('.FrmFDDataSelect.cboTourokuFrom');
        // clsComFnc.FncMsgBox("I0008");
        // }
        // }
        // });
    }

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmFDDataSelect = new R4.FrmFDDataSelect();
    o_R4_FrmFDDataSelect.load();
});
