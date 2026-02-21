/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20230103           機能追加　　　　　　             20221226_内部統制_仕様変更         YIN
 * 20230801           機能変更　　　           画面を戻る時、選択行を保持しておく      lujunxia
 * 20240612           要望対応            20240611_内部統制_要望対応                   YIN
 * 20240704           BUG      同一拠点内で罫線があるものとないものが混在しているようです  YIN
 * 20250219           機能変更               20250219_内部統制_改修要望.xlsx                    LHB
 * 20250403           機能変更               202504_内部統制_要望.xlsx               lujunxia
 * 20250409           機能変更               202504_内部統制_要望.xlsx               lujunxia
 * 20250410           機能変更             監査実施者の列を監査予定日の左へ            lujunxia
 * 20251016           機能追加      202510_内部統制システム_仕様変更対応.xlsx         YIN
 * 20251224     「副社長」——> 「社長」      202512_内部統制_変更要望.xlsx         YIN
 * 20260126     「社長」欄を１つ廃止     202601_内部統制_変更要望.xlsx               YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDKansaJissekiShokai");

HMAUD.HMAUDKansaJissekiShokai = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDKansaJissekiShokai";
    me.HMAUD = new HMAUD.HMAUD();

    // jqgrid
    me.grid_id = "#HMAUDKansaJissekiShokai_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearch_Click";
    //ステータス
    me.sessionStatus = "";
    //領域
    me.sessionTerritoryArr = [];
    //クール
    me.sessionCourShokai = "";
    me.allCourData = "";
    // 20250219 LHB INS S
    me.posSearchSelect_data = [];
    me.carSevenChbox = true;
    // 20250219 LHB INS E
    //拠点
    me.sessionKyotenCDShokai = "";
    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: false,
        // 20250403 lujunxia ins s
        shrinkToFit: true,
        // 20250403 lujunxia ins e
    };
    me.colModel = [
        {
            name: "no",
            label: "NO",
            index: "no",
            width: 45,
            align: "left",
            sortable: false,
            cellattr: function (rowId) {
                return "id='no" + rowId + "'";
            },
        },
        {
            name: "CHECK_ID",
            label: "監査ID",
            index: "CHECK_ID",
            width: 40,
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "STATUSVAL",
            label: "ステータス",
            index: "STATUSVAL",
            // 20240612 YIN UPD S
            // width: 158,
            width: 165,
            // 20240612 YIN UPD E
            align: "left",
            sortable: false,
        },
        {
            name: "KYOTEN_NAME",
            label: "拠点",
            index: "KYOTEN_NAME",
            width: 98,
            align: "left",
            sortable: false,
            cellattr: function (rowId) {
                return "id='KYOTEN_NAME" + rowId + "'";
            },
        },
        {
            name: "KYOTEN_CD",
            label: "",
            index: "KYOTEN_CD",
            hidden: true,
        },
        {
            name: "TERRITORYVAL",
            label: "領域",
            index: "TERRITORYVAL",
            width: 90,
            align: "left",
            sortable: false,
        },
        {
            name: "TERRITORY",
            label: "",
            index: "TERRITORY",
            width: 80,
            align: "left",
            sortable: false,
            hidden: true,
        },
        // 20250403 lujunxia upd s
        // {
        // 	name: "PLAN_DT",
        // 	label: "監査予定日",
        // 	index: "PLAN_DT",
        // 	width: 87,
        // 	align: "left",
        // 	sortable: false,
        // },
        // {
        // 	name: "CHECK_DT",
        // 	label: "監査実施日",
        // 	index: "CHECK_DT",
        // 	width: 87,
        // 	align: "left",
        // 	sortable: false,
        // },
        // 20250410 lujunxia ins s
        {
            name: "SYAIN_NM",
            label: "監査実施者",
            index: "SYAIN_NM",
            width: 205,
            align: "left",
            sortable: false,
        },
        // 20250410 lujunxia ins e
        {
            name: "PLAN_DT",
            label: "監査<br>予定日",
            index: "PLAN_DT",
            width: 50,
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "CHECK_DT",
            label: "監査<br>実施日",
            index: "CHECK_DT",
            width: 50,
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        // 20250403 lujunxia upd e
        // 20250410 lujunxia del s
        // {
        // 	name: "SYAIN_NM",
        // 	label: "監査実施者",
        // 	index: "SYAIN_NM",
        // 	width: 205,
        // 	align: "left",
        // 	sortable: false,
        // },
        // 20250410 lujunxia del e
        {
            name: "COMP_DT1",
            label: "監査実績<br>入力完了日",
            index: "COMP_DT1",
            // 20250403 lujunxia upd s
            // width: 87,
            width: 76,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "COMP_DT2",
            label: "指摘事項<br>入力完了日",
            index: "COMP_DT2",
            // 20250403 lujunxia upd s
            // width: 87,
            width: 76,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "COMP_DT3",
            label: "改善取組<br>入力完了日",
            index: "COMP_DT3",
            // 20250403 lujunxia upd s
            // width: 87,
            width: 76,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "RESPONSIBLE_CHECK_DT2",
            label: "領域責任者<br>確認日",
            index: "RESPONSIBLE_CHECK_DT2",
            // 20250403 lujunxia upd s
            // width: 87,
            width: 80,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "RESPONSIBLE_CHECK_DT3",
            label: "キーマン<br>確認日",
            index: "RESPONSIBLE_CHECK_DT3",
            // 20250403 lujunxia upd s
            //width: 87,
            width: 60,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "RESPONSIBLE_CHECK_DT4",
            label: "総括責任者<br>確認日",
            index: "RESPONSIBLE_CHECK_DT4",
            // 20250403 lujunxia upd s
            // width: 87,
            width: 75,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        // 20230103 YIN UPD S
        // {
        // name : "RESPONSIBLE_CHECK_DT5",
        // label : "社長<br>確認日",
        // index : "RESPONSIBLE_CHECK_DT5",
        // width : 87,
        // align : "left",
        // sortable : false,
        // },
        //20240313 caina ups s
        {
            name: "RESPONSIBLE_CHECK_DT5",
            // label: "常務<br>確認日",
            label: "取締役<br>確認日",
            index: "RESPONSIBLE_CHECK_DT5",
            // 202050403 lujunxia upd s
            // width: 87,
            width: 53,
            // 20250403 lujunxia upd e
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        //20240313 caina ups e
        // 20250403 lujunxia upd s
        // {
        // 	name: "RESPONSIBLE_CHECK_DT6",
        // 	label: "社長<br>確認日",
        // 	index: "RESPONSIBLE_CHECK_DT6",
        // 	width: 87,
        // 	align: "left",
        // 	sortable: false,
        // },

        // 20230103 YIN UPD E
        {
            name: "RESPONSIBLE_CHECK_DT6",
            label: "社長<br>確認日",
            index: "RESPONSIBLE_CHECK_DT6",
            width: 53,
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        {
            name: "RESPONSIBLE_CHECK_DT7",
            label: "社長<br>確認日",
            index: "RESPONSIBLE_CHECK_DT7",
            width: 53,
            // 20250409 lujunxia upd s
            // align: "left",
            align: "right",
            // 20250409 lujunxia upd e
            sortable: false,
        },
        // 20250403 lujunxia upd e
        {
            name: "COURS",
            label: "クール",
            index: "COURS",
            width: 85,
            align: "left",
            sortable: false,
            hidden: true,
        },
    ];
    //ステータス
    me.statusSelectList = [
        {
            val: "00",
            text: "未実施",
        },
        {
            val: "01",
            text: "監査実績入力済",
        },
        {
            val: "02",
            text: "指摘事項入力済",
        },
        {
            val: "03",
            text: "改善報告書担当確認済",
        },
        {
            val: "04",
            text: "改善取組入力済",
        },
        {
            val: "05",
            text: "領域責任者確認済",
        },
        {
            val: "06",
            text: "キーマン確認済",
        },
        {
            val: "07",
            text: "総括責任者確認済",
        },
        // 20230103 YIN UPD S
        // {
        // val : '08',
        // text : '社長確認済'
        // },
        //20240313 caina upd s
        {
            val: "08",
            // text: "常務確認済",
            text: "取締役確認済",
        },
        //20240313 caina upd e
        // 20250403 lujunxia upd s
        // {
        // 	val: "09",
        // 	text: "社長確認済",
        // },
        {
            val: "09",
            text: "社長確認済",
        },
        {
            val: "10",
            text: "社長確認済",
        },
        // 20250403 lujunxia upd e
        // 20240612 YIN UPD S
        {
            val: "91",
            text: "差戻（監査人）",
        },
        {
            val: "94",
            text: "差戻（改善取組責任者）",
        },
        {
            val: "95",
            text: "差戻（各領域責任者）",
        },
        {
            val: "96",
            text: "差戻（キーマン）",
        },
        {
            val: "97",
            text: "差戻（総括責任者）",
        },
        {
            val: "98",
            text: "差戻（取締役）",
        },
        // 20240612 YIN UPD E
        // 20230103 YIN UPD E
        // 20250403 lujunxia upd s
        // {
        // 	val: "99",
        // 	text: "差戻",
        // },
        {
            val: "99",
            text: "差戻（社長）",
        },
        // 20250403 lujunxia upd e
    ];
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDKansaJissekiShokai.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //検索ボタンクリック
    $(".HMAUDKansaJissekiShokai.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //実績入力ボタンクリック
    $(".HMAUDKansaJissekiShokai.btnJisseki").click(function () {
        me.btnPage_JumpClick("HMAUDKansaJissekiInput");
    });
    //報告書入力ボタンクリック
    $(".HMAUDKansaJissekiShokai.btnReport").click(function () {
        me.btnPage_JumpClick("HMAUDReportInput");
    });
    //クール
    $(".HMAUDKansaJissekiShokai.coursSearchInput").change(function () {
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        //to prevent:実績入力/報告書入力ボタンをクリックしてページにジャンプできるという問題
        $(me.grid_id).jqGrid("clearGridData");
        //クールchange
        me.fncCourChange();
    });
    // 20250219 LHB INS S
    $(".HMAUDKansaJissekiShokai.carSevenChbox").change(function () {
        me.carSevenChbox = $(this).prop("checked");
    });
    // 20250219 LHB INS E
    //拠点
    $(".HMAUDKansaJissekiShokai.posSearchSelect").change(function () {
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        //to prevent:実績入力/報告書入力ボタンをクリックしてページにジャンプできるという問題
        $(me.grid_id).jqGrid("clearGridData");
    });
    //ステータス
    $(".HMAUDKansaJissekiShokai.statusSelect").change(function () {
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        //to prevent:実績入力/報告書入力ボタンをクリックしてページにジャンプできるという問題
        $(me.grid_id).jqGrid("clearGridData");
    });
    //領域
    $(".HMAUDKansaJissekiShokai.territoryChbox").change(function () {
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        //to prevent:実績入力/報告書入力ボタンをクリックしてページにジャンプできるという問題
        $(me.grid_id).jqGrid("clearGridData");
    });
    //左メニューを閉じたときに明細の幅を広げて表示
    $(".ui-layout-toggler-open.ui-layout-toggler-west-open").click(function () {
        setTimeout(function () {
            gdmz.common.jqgrid.set_grid_width(
                me.grid_id,
                $(".HMAUDKansaJissekiShokai fieldset").width(),
            );
        }, 500);
    });
    //指摘事項NO58:ウインドウサイズ変更時にグリッドの大きさも追従
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
        );
        me.setTableSize();
        $(me.grid_id).jqGrid("bindKeys");
        //ステータスselect
        $(".HMAUDKansaJissekiShokai.statusSelect").find("option").remove();
        $("<option></option>")
            .val("")
            .text("")
            .appendTo(".HMAUDKansaJissekiShokai.statusSelect");
        for (var i = 0; i < me.statusSelectList.length; i++) {
            $("<option></option>")
                .val(me.statusSelectList[i].val)
                .text(me.statusSelectList[i].text)
                .appendTo(".HMAUDKansaJissekiShokai.statusSelect");
        }
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        $(".HMAUDKansaJissekiShokai.coursSearchInput").trigger("focus");
        //拠点マスタのデータを取得
        var url = me.sys_id + "/" + me.id + "/" + "fncGetKyoten";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //検索
                $(".HMAUDKansaJissekiShokai.btnSearch").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            var kyotenList = result["data"]["kyoten"];
            //拠点select
            $(".HMAUDKansaJissekiShokai.posSearchSelect")
                .find("option")
                .remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDKansaJissekiShokai.posSearchSelect");
            // 20250219 LHB INS S
            $x = 0;
            // 20250219 LHB INS E
            for (var i = 0; i < kyotenList.length; i++) {
                //指摘事項NO64:拠点プルダウンの表記を拠点名＋領域名にしてほしい
                var terrText = "";
                switch (kyotenList[i].TERRITORY) {
                    case "1":
                        terrText = "営業";
                        break;
                    case "2":
                        terrText = "サービス";
                        break;
                    case "3":
                        terrText = "管理";
                        break;
                    case "4":
                        terrText = "業売";
                        break;
                    case "5":
                        terrText = "業売管理";
                        break;
                    // 20250219 LHB INS S
                    case "6":
                        terrText = "カーセブン";
                        break;
                    // 20250219 LHB INS E
                    default:
                        terrText = "";
                }
                $("<option></option>")
                    .val(kyotenList[i].KYOTEN_CD + kyotenList[i].TERRITORY)
                    .text(kyotenList[i].KYOTEN_NAME + "・" + terrText)
                    .appendTo(".HMAUDKansaJissekiShokai.posSearchSelect");
                // 20250219 LHB INS S
                if (kyotenList[i].TERRITORY == "6") {
                    me.posSearchSelect_data[$x] =
                        kyotenList[i].KYOTEN_CD + kyotenList[i].TERRITORY;
                    $x++;
                    $(
                        ".HMAUDKansaJissekiShokai.posSearchSelect option[value=" +
                            kyotenList[i].KYOTEN_CD +
                            kyotenList[i].TERRITORY +
                            "]",
                    ).hide();
                }
                // 20250219 LHB INS E
            }
            //指摘事項NO65:クール数の欄をプルダウンにする
            $(".HMAUDKansaJissekiShokai.coursSearchInput")
                .find("option")
                .remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDKansaJissekiShokai.coursSearchInput");
            if (result["data"]["cour"].length > 0) {
                var courAll = result["data"]["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDKansaJissekiShokai.coursSearchInput");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //検索条件・クールには 現在のクール数を初期表示
                        $(".HMAUDKansaJissekiShokai.coursSearchInput").val(
                            courAll[i]["COURS"],
                        );
                        //クールchange
                        me.fncCourChange();
                    }
                }
            }
            var searchFlg = false;
            //監査実績照会画面を戻る時
            if (gdmz.SessionCourShokai != undefined) {
                searchFlg = true;
                //クール
                $(".HMAUDKansaJissekiShokai.coursSearchInput").val(
                    gdmz.SessionCourShokai,
                );
                delete gdmz.SessionCourShokai;
                //クールchange
                me.fncCourChange();
            }
            if (gdmz.SessionKyotenCDShokai != undefined) {
                searchFlg = true;
                //拠点
                $(".HMAUDKansaJissekiShokai.posSearchSelect").val(
                    gdmz.SessionKyotenCDShokai,
                );
                delete gdmz.SessionKyotenCDShokai;
            }
            if (gdmz.SessionStatus != undefined) {
                searchFlg = true;
                //ステータス
                $(".HMAUDKansaJissekiShokai.statusSelect").val(
                    gdmz.SessionStatus,
                );
                delete gdmz.SessionStatus;
            }
            if (gdmz.SessionTerritoryArr != undefined) {
                searchFlg = true;
                //領域
                $(".HMAUDKansaJissekiShokai.territoryChbox").each(function () {
                    if (
                        gdmz.SessionTerritoryArr.indexOf($(this).val()) != -1
                    ) {
                        $(this)[0].checked = true;
                    } else {
                        $(this)[0].checked = false;
                    }
                });
                delete gdmz.SessionTerritoryArr;
            }
            if (searchFlg == true) {
                setTimeout(function () {
                    //データを検索
                    me.btnSearch_Click();
                }, 100);
            }
        };
        me.ajax.send(url, "", 0);
    };
    me.setTableSize = function () {
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            $(".HMAUDKansaJissekiShokai fieldset").width(),
        );
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var buttonHeight = $(".HMAUDKansaJissekiShokai.buttonClass").height();
        var fieldsetHeight = $(".HMAUDKansaJissekiShokai fieldset").height();
        var tableHeight = mainHeight - buttonHeight - fieldsetHeight - 88;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            tableHeight = mainHeight - buttonHeight - fieldsetHeight - 90;
        }
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    //'**********************************************************************
    //'処 理 名：検索ボタンクリック
    //'関 数 名：btnSearch_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：検索ボタンクリック
    //'**********************************************************************
    me.btnSearch_Click = function () {
        $(".HMAUDKansaJissekiShokai.pnlList").hide();
        //データバインド
        $(me.grid_id).jqGrid("clearGridData");
        //クール
        var coursSearchInput = $(
            ".HMAUDKansaJissekiShokai.coursSearchInput",
        ).val();
        me.sessionCourShokai = $.trim(coursSearchInput);
        //拠点
        var kyotenCd = $(".HMAUDKansaJissekiShokai.posSearchSelect").val();
        me.sessionKyotenCDShokai = kyotenCd;
        //ステータス
        me.sessionStatus = $(".HMAUDKansaJissekiShokai.statusSelect").val();
        //領域
        me.sessionTerritoryArr = [];
        $(".HMAUDKansaJissekiShokai.territoryChbox").each(function () {
            if ($(this).is(":checked") == true) {
                me.sessionTerritoryArr.push($(this).val());
            }
        });
        var data = {
            COURS: $.trim(coursSearchInput),
            STATUS: me.sessionStatus,
            KYOTEN_CD: kyotenCd.substring(0, kyotenCd.length - 1),
            TERRITORY: kyotenCd.substr(kyotenCd.length - 1),
            TERRITORYArr:
                // 20250219 LHB UPD S
                // me.sessionTerritoryArr.length < 5
                me.sessionTerritoryArr.length < 6
                    ? // 20250219 LHB UPD E
                      me.sessionTerritoryArr.toString()
                    : "",
        };
        var complete_fun = function (returnFLG, result) {
            //指摘事項NO34:ログインIDが存在していなかったら抽出対象外
            // if (result['audit'] == "")
            // {
            // //該当するユーザーは登録されていません！
            // me.clsComFnc.FncMsgBox("W0008", "ユーザー");
            // return;
            // }
            if (result["error"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            if (returnFLG == "nodata") {
                //該当データはありません。
                me.clsComFnc.FncMsgBox("W0024");
                return;
            }
            //20230801 lujunxia upd s
            //「照会」->「監査実績」->「報告書」->「照会」:gdmz.shokaiSelRow存在する、でもデータも違う
            //だからgdmz.shokaiScrollPositionを削除する、第一行を選択する
            if (
                gdmz.shokaiSelRow != undefined &&
                $(me.grid_id).jqGrid("getInd", gdmz.shokaiSelRow) != false
            ) {
                //画面を戻る時に、選択されたデータ不変
                $(me.grid_id).jqGrid(
                    "setSelection",
                    gdmz.shokaiSelRow,
                    true,
                );
                delete gdmz.shokaiSelRow;
            } else {
                //初期化第一行を選択する
                $(me.grid_id).jqGrid("setSelection", 0);
                delete gdmz.shokaiScrollPosition;
            }
            //20230801 lujunxia upd e
            //合并单元格
            me.fomatSet();
            $(".HMAUDKansaJissekiShokai.pnlList").show();
            //20230801 lujunxia ins s
            if (gdmz.shokaiScrollPosition != undefined) {
                //画面を戻る時に、位置不変
                $(me.grid_id)
                    .closest(".ui-jqgrid-bdiv")
                    .scrollTop(gdmz.shokaiScrollPosition);
                delete gdmz.shokaiScrollPosition;
            } else {
                $(me.grid_id).closest(".ui-jqgrid-bdiv").scrollTop(0);
            }
            //20230801 lujunxia ins e
            $(".HMAUDKansaJissekiShokai.coursSearchInput").trigger("focus");
        };
        gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
    };
    //'**********************************************************************
    //'処 理 名：セルを結合する
    //'関 数 名：fomatSet
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：セルを結合する
    //'**********************************************************************
    me.fomatSet = function () {
        var mya = $(me.grid_id).getDataIDs();
        var rowno = 1;
        $("#no0").text(1);
        //set NO
        for (var i = 0; i < mya.length; i++) {
            var before_data = $(me.grid_id).jqGrid("getRowData", mya[i]);
            var after_data = $(me.grid_id).jqGrid("getRowData", mya[i + 1]);
            if (before_data["KYOTEN_NAME"] != after_data["KYOTEN_NAME"]) {
                // 20240704 YIN UPD S
                // $("#no" + (parseInt(mya[i]) + 1)).text(parseInt(rowno) + 1);
                $(
                    "#HMAUDKansaJissekiShokai_tblMain #no" +
                        (parseInt(mya[i]) + 1),
                ).text(parseInt(rowno) + 1);
                // 20240704 YIN UPD E
                rowno++;
            }
            //指摘事項NO53:ステータスが「差戻」の行は 文字色を赤色で表示してほしい
            // 20240612 YIN UPD S
            // if (before_data["STATUSVAL"] == "99.差戻") {
            if (before_data["STATUSVAL"].indexOf("差戻") !== -1) {
                // 20240612 YIN UPD E
                // 20240704 YIN UPD S
                // $("tr#" + mya[i]).css("color", "red");
                $("#HMAUDKansaJissekiShokai_tblMain tr#" + mya[i]).css(
                    "color",
                    "red",
                );
                // 20240704 YIN UPD E
            }
        }
        //セルを結合する
        for (var i = 0; i < mya.length; i++) {
            var before = $(me.grid_id).jqGrid("getRowData", mya[i]);
            var rowspancount = 1;
            for (j = i + 1; j <= mya.length; j++) {
                var end = $(me.grid_id).jqGrid("getRowData", mya[j]);
                if (before["KYOTEN_NAME"] == end["KYOTEN_NAME"]) {
                    rowspancount++;
                    // 20240704 YIN UPD S
                    // $("tr#" + (mya[j] - 1)).css("borderBottomColor", "white");
                    // $("#no" + mya[j]).text("");
                    // $("#KYOTEN_NAME" + mya[j]).text("");
                    $(
                        "#HMAUDKansaJissekiShokai_tblMain tr#" + (mya[j] - 1),
                    ).css("borderBottomColor", "white");
                    $("#HMAUDKansaJissekiShokai_tblMain #no" + mya[j]).text("");
                    $(
                        "#HMAUDKansaJissekiShokai_tblMain #KYOTEN_NAME" +
                            mya[j],
                    ).text("");
                    // 20240704 YIN UPD E
                } else {
                    rowspancount = 1;
                    break;
                }
            }
        }
    };
    //'**********************************************************************
    //'処 理 名：実績入力/報告書入力ボタンクリック
    //'関 数 名：btnPage_JumpClick
    //'引    数：page:画面
    //'戻 り 値：無し
    //'処理説明：実績入力/報告書入力ボタンクリック
    //'**********************************************************************
    me.btnPage_JumpClick = function (page) {
        var id = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (id == null) {
            me.clsComFnc.ObjFocus = $(
                ".HMAUDKansaJissekiShokai.coursSearchInput",
            );
            me.clsComFnc.FncMsgBox("W9999", "表から行を選択して下さい。");
            return;
        }
        //20230801 lujunxia ins s
        //画面を戻る時に、選択されたデータと位置
        gdmz.shokaiSelRow = id;
        gdmz.shokaiScrollPosition = $(me.grid_id)
            .closest(".ui-jqgrid-bdiv")
            .scrollTop();
        //20230801 lujunxia ins e
        //ステータス
        gdmz.SessionStatus = me.sessionStatus;
        //領域
        gdmz.SessionTerritoryArr = me.sessionTerritoryArr;
        var rowData = $(me.grid_id).jqGrid("getRowData", id);
        //クール
        gdmz.SessionCour = rowData["COURS"];
        gdmz.SessionCourShokai = me.sessionCourShokai;
        //拠点コード
        gdmz.SessionKyotenCD = rowData["KYOTEN_CD"];
        gdmz.SessionKyotenCDShokai = me.sessionKyotenCDShokai;
        //領域
        gdmz.territory = rowData["TERRITORY"];
        //監査ID
        gdmz.SessionCheckId = rowData["CHECK_ID"];
        gdmz.SessionPrePG = "HMAUDKansaJissekiShokai";
        //選択された行の監査IDを条件に [監査実績入力]/[報告書入力]画面に遷移
        o_HMAUD_HMAUD.FrmHMAUDMainMenu.blnFlag = false;
        $(".FrmHMAUDMainMenu.Menu").jstree(
            "deselect_node",
            "#HMAUDKansaJissekiShokai",
        );
        $(".FrmHMAUDMainMenu.Menu").jstree("select_node", "#" + page);
    };

    //'**********************************************************************
    //'処 理 名：クールchange
    //'関 数 名：fncCourChange
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：指摘事項NO76:クールを選択したら開始日～終了日を表示
    //'**********************************************************************
    me.fncCourChange = function () {
        var cour = $(".HMAUDKansaJissekiShokai.coursSearchInput").val();
        var foundDT = undefined;
        // 20250219 LHB INS S
        if (parseInt(cour) >= 18 || cour === "") {
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox").css(
                "visibility",
                "visible",
            );
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox").prop(
                "checked",
                me.carSevenChbox,
            );
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox")
                .next("div")
                .css("visibility", "visible");
        } else {
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox").css(
                "visibility",
                "hidden",
            );
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox").prop(
                "checked",
                false,
            );
            $(".HMAUDKansaJissekiShokai.territoryChbox.carSevenChbox")
                .next("div")
                .css("visibility", "hidden");
            var skyotenVal = $(
                ".HMAUDKansaJissekiShokai.posSearchSelect",
            ).val();
            if (skyotenVal != "" && skyotenVal.substring(3) == "6") {
                $(".HMAUDKansaJissekiShokai.posSearchSelect").val("");
            }
        }
        if (me.posSearchSelect_data.length > 0) {
            for (
                let index = 0;
                index < me.posSearchSelect_data.length;
                index++
            ) {
                if (parseInt(cour) >= 18 || cour === "") {
                    $(
                        ".HMAUDKansaJissekiShokai.posSearchSelect option[value=" +
                            me.posSearchSelect_data[index] +
                            "]",
                    ).show();
                } else {
                    $(
                        ".HMAUDKansaJissekiShokai.posSearchSelect option[value=" +
                            me.posSearchSelect_data[index] +
                            "]",
                    ).hide();
                }
            }
        }
        // 20250219 LHB INS E
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDKansaJissekiShokai.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : "",
            );
        }
        // 20251016 YIN INS S
        if (parseInt(cour) > 18) {
            if (
                $(".HMAUDKansaJissekiShokai.statusSelect").val() == "08" ||
                $(".HMAUDKansaJissekiShokai.statusSelect").val() == "98"
            ) {
                $(".HMAUDKansaJissekiShokai.statusSelect").val("");
            }
            $(
                ".HMAUDKansaJissekiShokai.statusSelect option[value=08], .HMAUDKansaJissekiShokai.statusSelect option[value=98]",
            ).hide();
            $(me.grid_id).jqGrid("hideCol", "RESPONSIBLE_CHECK_DT5");
        } else {
            $(
                ".HMAUDKansaJissekiShokai.statusSelect option[value=08], .HMAUDKansaJissekiShokai.statusSelect option[value=98]",
            ).show();
            $(me.grid_id).jqGrid("showCol", "RESPONSIBLE_CHECK_DT5");
        }
        // 20251016 YIN INS E
        if (parseInt(cour) >= 20) {
            if (
                $(".HMAUDKansaJissekiShokai.statusSelect").val() == "10" ||
                $(".HMAUDKansaJissekiShokai.statusSelect").val() == "99"
            ) {
                $(".HMAUDKansaJissekiShokai.statusSelect").val("");
            }
            $(
                ".HMAUDKansaJissekiShokai.statusSelect option[value=10], .HMAUDKansaJissekiShokai.statusSelect option[value=99]",
            ).hide();
            $(me.grid_id).jqGrid("hideCol", "RESPONSIBLE_CHECK_DT7");
        } else {
            $(
                ".HMAUDKansaJissekiShokai.statusSelect option[value=10], .HMAUDKansaJissekiShokai.statusSelect option[value=99]",
            ).show();
            $(me.grid_id).jqGrid("showCol", "RESPONSIBLE_CHECK_DT7");
        }
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDKansaJissekiShokai = new HMAUD.HMAUDKansaJissekiShokai();
    o_HMAUD_HMAUDKansaJissekiShokai.load();
    o_HMAUD_HMAUD.HMAUDKansaJissekiShokai = o_HMAUD_HMAUDKansaJissekiShokai;
});
