/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("R4.FrmAkaden");

R4.FrmAkaden = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "FrmAkaden";
    me.sys_id = "R4G";
    me.class_id = ".FrmAkaden.body";
    me.parent_class_id = ".R4.R4-layout-center";
    me.bFlagStart = false;
    me.row_count = 0;
    me.nowDate1 = new Date();
    me.nowDate2 = me.nowDate1.getFullYear() + "/";
    // get date
    if (me.nowDate1.getMonth() < 10) {
        me.nowDate2 += "0" + (me.nowDate1.getMonth() + 1) + "/";
    } else {
        me.nowDate2 += me.nowDate1.getMonth() + 1 + "/";
    }

    if (me.nowDate1.getDate() < 10) {
        me.nowDate2 += "0" + me.nowDate1.getDate();
    } else {
        me.nowDate2 += me.nowDate1.getDate();
    }

    me.nowDate2 = me.nowDate2.toString();
    me.data = new Array();
    me.sprList_Sheet1 = new Array();
    me.DsKasouPrintArray = new Array();
    me.selectedIdsArray = new Array();
    me.selectedDataArray = new Array();
    me.selectRowFlg = false;
    me.DsDeleteTbl = [];
    me.intState = 0;
    me.lngOutCntK = 0;
    me.lngOutCntG = 0;
    me.grid_id = "#FrmAkaden_sprList";
    me.g_url = me.sys_id + "/" + me.id + "/" + "fnc" + me.id;
    me.pager = "#divFrmAkaden_pager";
    me.sidx = "CMN_NO";

    me.option = {
        pagerpos: "left",
        multiselect: true,
        caption: "",
        rowNum: 500000,
        multiselectWidth: 30,
        rownumWidth: 40,
    };
    me.colModel = [
        {
            name: "CMN_NO",
            label: "注文書番号",
            index: "CMN_NO",
            width: 100,
            sortable: false,
            align: "left",
        },
        {
            name: "EDA_NO",
            label: "枝番号",
            index: "EDA_NO",
            width: 50,
            sortable: false,
            align: "left",
        },
        {
            name: "KASOUNO",
            label: "架装番号",
            index: "KASOUNO",
            width: 100,
            sortable: false,
            align: "left",
        },
        {
            name: "SYADAIKATA",
            label: "車台型式",
            index: "SYADAIKATA",
            width: 85,
            sortable: false,
            align: "left",
        },
        {
            name: "CAR_NO",
            label: "CarNo",
            index: "CAR_NO",
            width: 75,
            align: "left",
            sortable: false,
        },
        {
            name: "HANBAISYASYU",
            label: "販売車種",
            index: "HANBAISYASYU",
            width: 80,
            sortable: false,
            align: "left",
        },
        {
            name: "TOIAWASENM",
            label: "問合呼称",
            index: "TOIAWASENM",
            width: 70,
            sortable: false,
            align: "left",
        },
        {
            name: "SYASYU_NM",
            label: "車種名",
            index: "SYASYU_NM",
            width: 280,
            sortable: false,
            align: "left",
        },
        {
            name: "MEMO",
            label: "メモ",
            index: "MEMO",
            width: 215,
            sortable: false,
            align: "left",
        },
        {
            name: "FUZOKUHINKBN",
            label: "付属品区分",
            index: "FUZOKUHINKBN",
            width: 80,
            sortable: false,
            align: "left",
        },
        {
            name: "GYOUSYA_CD",
            label: "業者コード",
            index: "GYOUSYA_CD",
            width: 80,
            sortable: false,
            align: "left",
        },
        {
            name: "GYOUSYA_NM",
            label: "業者名",
            index: "GYOUSYA_NM",
            width: 215,
            sortable: false,
            align: "left",
        },
        {
            name: "MEDALCD",
            label: "メダルコード",
            index: "MEDALCD",
            width: 90,
            sortable: false,
            align: "left",
        },
        {
            name: "BUHINNM",
            label: "部品名称",
            index: "BUHINNM",
            width: 280,
            sortable: false,
            align: "left",
        },
        {
            name: "BIKOU",
            label: "備考",
            index: "BIKOU",
            width: 170,
            sortable: false,
            align: "left",
        },
        {
            name: "SUURYOU",
            label: "数量",
            index: "SUURYOU",
            width: 35,
            sortable: false,
            align: "right",
        },
        {
            name: "TEIKA",
            label: "定価",
            index: "TEIKA",
            width: 80,
            sortable: false,
            align: "right",
        },
        {
            name: "BUHIN_SYANAI_GEN",
            label: "部品社内原価",
            index: "BUHIN_SYANAI_GEN",
            width: 90,
            sortable: false,
            align: "right",
        },
        {
            name: "BUHIN_SYANAI_ZITU",
            label: "部品社内実原価",
            index: "BUHIN_SYANAI_ZITU",
            width: 110,
            sortable: false,
            align: "right",
        },
        {
            name: "GAICYU_GEN",
            label: "外注原価",
            index: "GAICYU_GEN",
            width: 60,
            sortable: false,
            align: "right",
        },
        {
            name: "GAICYU_ZITU",
            label: "外注実原価",
            index: "GAICYU_ZITU",
            width: 75,
            sortable: false,
            align: "right",
        },
        {
            name: "ZEIRITU",
            label: "消費税率",
            index: "ZEIRITU",
            width: 60,
            sortable: false,
            align: "right",
        },
        {
            name: "KAZEIKBN",
            label: "課税非課税区分",
            index: "KAZEIKBN",
            width: 110,
            sortable: false,
            align: "left",
        },
        {
            name: "DELKBN",
            label: "削除区分",
            index: "DELKBN",
            width: 60,
            sortable: false,
            align: "left",
        },
        {
            name: "UPD_DATE",
            label: "更新日時",
            index: "UPD_DATE",
            width: 155,
            sortable: false,
            align: "left",
        },
        {
            name: "CREATE_DATE",
            label: "作成日時",
            index: "CREATE_DATE",
            width: 155,
            sortable: false,
            align: "left",
        },
        {
            name: "SIYOSYA",
            label: "使用者名",
            index: "SIYOSYA",
            width: 150,
            sortable: false,
            align: "left",
        },
        {
            name: "BUSYO",
            label: "部署",
            index: "BUSYO",
            width: 280,
            sortable: false,
            align: "left",
        },
        {
            name: "SYAIN",
            label: "社員名",
            index: "SYAIN",
            width: 150,
            sortable: false,
            align: "left",
        },
        {
            name: "BUSYO_CD",
            label: "部署コード",
            index: "BUSYO_CD",
            width: 80,
            sortable: false,
            align: "left",
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmAkaden.button_search",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmAkaden.button_print",
        type: "button",
        enable: "false",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    /*
     **********************************************************************
     '処 理 名：検索ボタンクリック
     '関 数 名：cmdSearch_Click
     '引    数：無し
     '戻 り 値：無し
     '処理説明：検索ボタンクリック
     **********************************************************************
     */
    $(".FrmAkaden.button_search").click(function () {
        me.FrmAkaden_subFormClear(true);
        me.FrmAkaden_search_clickFun();
    });

    /*
     **********************************************************************
     '処 理 名：発行ボタンリック
     '関 数 名：cmdSearch_Click
     '引    数：無し
     '戻 り 値：無し
     '処理説明：発行ボタンリック
     **********************************************************************
     */
    $(".FrmAkaden.button_print").click(function () {
        $(".FrmAkaden.button_print").button("disable");

        me.intState = 0;
        me.lngOutCntK = 0;
        var intDelCnt = 0;
        var intGaichuStart = -1;

        me.lngOutCntG = 0;
        me.DsDeleteTbl = [];
        var fncNzDataArr = [
            "SUURYOU",
            "TEIKA",
            "BUHIN_SYANAI_GEN",
            "BUHIN_SYANAI_ZITU",
            "GAICYU_ZITU",
            "ZEIRITU",
        ];
        var id = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        me.sprList_Sheet1 = $(me.grid_id).jqGrid("getRowData");

        me.row_count = $(me.grid_id).jqGrid("getGridParam", "records");

        //データ存在チェック
        for (var i = 0; i < id.length; i++) {
            for (var j = i; j < id.length; j++) {
                if (id[i] > id[j]) {
                    var tmp = "";
                    tmp = id[i];
                    id[i] = id[j];
                    id[j] = tmp;
                }
            }
        }
        for (var t = 0; t < id.length; t++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", id[t]);
            var tmpArr = new Array();
            for (key in rowData) {
                for (var f1 = 0; f1 < fncNzDataArr.length; f1++) {
                    if (fncNzDataArr[f1] == key) {
                        var ttt = clsComFnc.FncNz(rowData[key]);
                        tmpArr[key] = ttt;
                    } else {
                        var ttt1 = clsComFnc.FncNv(rowData[key]);
                        tmpArr[key] = ttt1;
                    }
                }
            }
            me.DsDeleteTbl.push(tmpArr);
            if (rowData["GYOUSYA_CD"] != "" && intGaichuStart == -1) {
                intGaichuStart = intDelCnt;
            }
            intDelCnt += 1;
        }

        if (me.DsDeleteTbl.length == 0) {
            clsComFnc.FncMsgBox(
                "E9999",
                "削除するデータにチェックを入れてください!"
            );
            return;
        }
        me.intState = 9;

        //標準添付品カウント
        var strOptCNt = "1";
        //'特別添付品カウント
        var strSpcCnt = "1";
        //'架装依頼先ｶｳﾝﾄ
        var intToriCnt = 0;
        var intRptCnt = 0;
        //'架装データ件数
        var intKasouCnt = 0;

        var funcName = "fncHPRINTTANT";
        //印刷担当者を取得する
        var ajax = new gdmz.common.ajax();
        var data = "";
        var url = me.sys_id + "/" + me.id + "/" + funcName;

        ajax.receive = function (result) {
            me.DsKasouPrintArray.length = 0;
            //印刷処理
            fncPrintSection1 = false;
            objTanDs = eval("(" + result + ")");
            if (intGaichuStart != 0) {
                if (intGaichuStart == -1) {
                    intKasouCnt = intDelCnt;
                } else {
                    intKasouCnt = intGaichuStart;
                }
                for (var i = 0; i < intKasouCnt; i++) {
                    intRptCnt = 1;
                    var intPage = 0;
                    while (intPage < me.DsKasouPrintArray.length) {
                        if (
                            clsComFnc.FncNv(
                                me.DsKasouPrintArray[intPage]["KASOUNO"]
                            ) != clsComFnc.FncNv(me.DsDeleteTbl[i]["KASOUNO"])
                        ) {
                            intPage++;
                        } else {
                            break;
                        }
                    }
                    //同一架装番号
                    if (intPage >= me.DsKasouPrintArray.length) {
                        var ttArr = {};
                        var LenObjTanDs = 0;
                        for (key in objTanDs["data"]) {
                            LenObjTanDs++;
                        }
                        if (LenObjTanDs > 0) {
                            ttArr["HAKKOUNIN"] = clsComFnc.FncNv(
                                objTanDs["data"][0]["TANTO_SEI"]
                            );
                            ttArr["HAKKOUBI"] = me.nowDate2;
                            ttArr["CMNNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["CMN_NO"]
                            );
                            ttArr["SIYOSYA_KN"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SIYOSYA"]
                            );
                            ttArr["BUSYOMEI"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["BUSYO"]
                            );
                            ttArr["SYAINMEI"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYAIN"]
                            );
                            ttArr["SYADAIKATA"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYADAIKATA"]
                            );
                            ttArr["CARNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["CAR_NO"]
                            );
                            ttArr["SYASYU_NM"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYASYU_NM"]
                            );
                            ttArr["HANBAISYASYU"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["HANBAISYASYU"]
                            );
                            ttArr["MEMO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["MEMO"]
                            );
                            ttArr["KASOUNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["KASOUNO"]
                            );
                        } else {
                            ttArr["HAKKOUBI"] = me.nowDate2;
                            ttArr["CMNNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["CMN_NO"]
                            );
                            ttArr["SIYOSYA_KN"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SIYOSYA"]
                            );
                            ttArr["BUSYOMEI"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["BUSYO"]
                            );
                            ttArr["SYAINMEI"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYAIN"]
                            );
                            ttArr["SYADAIKATA"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYADAIKATA"]
                            );
                            ttArr["CARNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["CAR_NO"]
                            );
                            ttArr["SYASYU_NM"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["SYASYU_NM"]
                            );
                            ttArr["HANBAISYASYU"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["HANBAISYASYU"]
                            );
                            ttArr["MEMO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["MEMO"]
                            );
                            ttArr["KASOUNO"] = clsComFnc.FncNv(
                                me.DsDeleteTbl[i]["KASOUNO"]
                            );
                        }
                        //加装

                        me.DsKasouPrintArray[intPage] = ttArr;
                        var strSaveTori;
                        var intSpreadCnt = 0;
                        //取引先が6件になるか、ｽﾌﾟﾚｯﾄﾞの最終行まで検索したら処理を抜ける , vb line 3201
                        while (intToriCnt < 6 && intSpreadCnt < me.row_count) {
                            //取引先が入力されていた場合
                            if (
                                clsComFnc.FncNv(
                                    me.sprList_Sheet1[intSpreadCnt][
                                        "GYOUSYA_NM"
                                    ]
                                ) != ""
                            ) {
                                //違う取引先が出現したら、データセットに格納
                                if (
                                    me.DsKasouPrintArray[intPage]["KASOUNO"] ==
                                    me.sprList_Sheet1[intSpreadCnt]["KASOUNO"]
                                ) {
                                    if (
                                        strSaveTori !=
                                        me.sprList_Sheet1[intSpreadCnt][
                                            "GYOUSYA_NM"
                                        ]
                                    ) {
                                        strSaveTori =
                                            me.sprList_Sheet1[intSpreadCnt][
                                                "GYOUSYA_NM"
                                            ];
                                        //データセットにセット
                                        me.DsKasouPrintArray[intPage][
                                            "TORIHIKI_" +
                                                (intToriCnt + 1).toString()
                                        ] = clsComFnc.FncNv(
                                            me.sprList_Sheet1[intSpreadCnt][
                                                "GYOUSYA_NM"
                                            ]
                                        );
                                        intToriCnt++;
                                    }
                                }
                            }
                            intSpreadCnt++;
                        }
                    }

                    //～～～～～架装明細～～～～～
                    switch (
                        clsComFnc.FncNv(me.DsDeleteTbl[i]["FUZOKUHINKBN"])
                    ) {
                        case "0":
                            if (
                                Math.round(
                                    clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strOptCNt"
                                        ]
                                    )
                                ) < 13
                            ) {
                                if (
                                    clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strOptCNt"
                                        ]
                                    ) == 0
                                ) {
                                    strOptCNt = 1;
                                    me.DsKasouPrintArray[intPage]["strOptCNt"] =
                                        "1";
                                } else {
                                    strOptCNt = clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strOptCNt"
                                        ]
                                    );
                                }

                                me.DsKasouPrintArray[intPage][
                                    "OMEDALCD_" + strOptCNt
                                ] = clsComFnc.FncNv(
                                    me.DsDeleteTbl[i]["MEDALCD"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "OBUHINNM_" + strOptCNt
                                ] = clsComFnc.FncNv(
                                    me.DsDeleteTbl[i]["BUHINNM"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "OBIKOU_" + strOptCNt
                                ] = clsComFnc.FncNv(me.DsDeleteTbl[i]["BIKOU"]);
                                me.DsKasouPrintArray[intPage][
                                    "OSURYO_" + strOptCNt
                                ] = clsComFnc.FncNv(
                                    me.DsDeleteTbl[i]["SUURYOU"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "OTEIKA_" + strOptCNt
                                ] = (
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i]["TEIKA"]
                                        )
                                    ) * -1
                                ).toString();
                                me.DsKasouPrintArray[intPage][
                                    "OGENKA_" + strOptCNt
                                ] = (
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i][
                                                "BUHIN_SYANAI_GEN"
                                            ]
                                        )
                                    ) * -1
                                ).toString();
                                me.DsKasouPrintArray[intPage][
                                    "OJITUGEN_" + strOptCNt
                                ] = (
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i][
                                                "BUHIN_SYANAI_ZITU"
                                            ]
                                        )
                                    ) * -1
                                ).toString();

                                me.DsKasouPrintArray[intPage]["OTEIKAKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "OTEIKAKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i]["TEIKA"]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["OGENKAKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "OGENKAKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_GEN"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["OJITUGENKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "OJITUGENKEYI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_ZITU"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["TEIKAGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "TEIKAGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i]["TEIKA"]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["GENKAGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "GENKAGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_GEN"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["JITUGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "JITUGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_ZITU"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["strOptCNt"] =
                                    Math.round(strOptCNt) + 1;
                            }
                            break;

                        case "1":
                            if (
                                Math.round(
                                    clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strSpcCnt"
                                        ]
                                    )
                                ) < 27
                            ) {
                                if (
                                    clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strSpcCnt"
                                        ]
                                    ) == 0
                                ) {
                                    strSpcCnt = 1;
                                    me.DsKasouPrintArray[intPage]["strSpcCnt"] =
                                        "1";
                                } else {
                                    strSpcCnt = clsComFnc.FncNz(
                                        me.DsKasouPrintArray[intPage][
                                            "strSpcCnt"
                                        ]
                                    );
                                }

                                me.DsKasouPrintArray[intPage][
                                    "SMEDALCD_" + strSpcCnt
                                ] = clsComFnc.FncNv(
                                    me.DsDeleteTbl[i]["MEDALCD"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "SBUHINNM_" + strSpcCnt
                                ] = clsComFnc.FncNv(
                                    me.DsDeleteTbl[i]["BUHINNM"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "SBIKOU_" + strSpcCnt
                                ] = clsComFnc.FncNv(me.DsDeleteTbl[i]["BIKOU"]);
                                me.DsKasouPrintArray[intPage][
                                    "SSURYO_" + strSpcCnt
                                ] = clsComFnc.FncNz(
                                    me.DsDeleteTbl[i]["SUURYOU"]
                                );
                                me.DsKasouPrintArray[intPage][
                                    "STEIKA_" + strSpcCnt
                                ] = String(
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i]["TEIKA"]
                                        )
                                    ) * -1
                                );
                                me.DsKasouPrintArray[intPage][
                                    "SGENKA_" + strSpcCnt
                                ] = String(
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i][
                                                "BUHIN_SYANAI_GEN"
                                            ]
                                        )
                                    ) * -1
                                );
                                me.DsKasouPrintArray[intPage][
                                    "SJITUGEN_" + strSpcCnt
                                ] = String(
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsDeleteTbl[i][
                                                "BUHIN_SYANAI_ZITU"
                                            ]
                                        )
                                    ) * -1
                                ).toString();

                                me.DsKasouPrintArray[intPage]["STEIKAKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "STEIKAKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i]["TEIKA"]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["SGENKAKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "SGENKAKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_GEN"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["SJITUGENKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "SJITUGENKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_ZITU"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["TEIKAGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "TEIKAGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i]["TEIKA"]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["GENKAGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "GENKAGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_GEN"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["JITUGOUKEI"] =
                                    Math.round(
                                        clsComFnc.FncNz(
                                            me.DsKasouPrintArray[intPage][
                                                "JITUGOUKEI"
                                            ]
                                        )
                                    ) +
                                    -1 *
                                        Math.round(
                                            clsComFnc.FncNz(
                                                me.DsDeleteTbl[i][
                                                    "BUHIN_SYANAI_ZITU"
                                                ]
                                            )
                                        );
                                me.DsKasouPrintArray[intPage]["strSpcCnt"] =
                                    Math.round(strSpcCnt) + 1;
                            }
                            break;
                    }
                }
            }
            //～外注加工依頼書～
            var intGaiCnt = 0;
            var ingSyoukei = 0;
            var strSaveTorihiki = "";
            var intChgBefCnt = 0;
            var intTotalCnt = 0;
            var intMaisu = 0;
            var j = intGaichuStart;
            var intChgBefCnt = intGaichuStart;
            var DsGaichuPrintArray = new Array();
            var tmpArrSYOUKEI = new Array();

            if (j != -1) {
                while (j < me.DsDeleteTbl.length) {
                    console.log(me.DsDeleteTbl.length);
                    //業者コードが"88888"以外のもののみ対象
                    console.log(
                        "***********GYOUSYA_CD=" +
                            clsComFnc.FncNv(me.DsDeleteTbl[j]["GYOUSYA_CD"])
                    );
                    if (
                        clsComFnc.FncNv(me.DsDeleteTbl[j]["GYOUSYA_CD"]) !=
                        "88888"
                    ) {
                        var tttArr = {};
                        //new Array();
                        if (intRptCnt == 1 || intRptCnt == 3) {
                            intRptCnt = 3;
                        } else {
                            intRptCnt = 2;
                        }
                        //取引コードが変わった場合
                        if (
                            strSaveTorihiki != me.DsDeleteTbl[j]["GYOUSYA_CD"]
                        ) {
                            strSaveTorihiki = me.DsDeleteTbl[j]["GYOUSYA_CD"];
                            intMaisu += 1;
                            //一回目は何も処理しない
                            if (j != intGaichuStart) {
                                //同一取引先コattrードのデータセットに請求金額合計をセットしていく
                                while (intChgBefCnt < j) {
                                    tttArr["SYOUKEI"] = ingSyoukei * -1;
                                    intTotalCnt += 1;
                                    intChgBefCnt += 1;
                                    ingSyoukei = 0;
                                }
                            }
                        }
                        //担当者をセット
                        var objTanDsLength = 0;
                        for (key in objTanDs["data"]) {
                            objTanDsLength++;
                        }
                        if (objTanDsLength > 0) {
                            tttArr["TANTO_NM"] = clsComFnc.FncNv(
                                objTanDs["data"][0]["TANTO_SEI"]
                            );
                            tttArr["TANTO_BUSYO"] = clsComFnc.FncNv(
                                objTanDs["data"][0]["BUSYO_NM"]
                            );
                        }
                        //明細データをセット
                        tttArr["TORIHIKI_CD"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["GYOUSYA_CD"]
                        );
                        tttArr["TORIHIKI_NM"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["GYOUSYA_NM"]
                        );
                        tttArr["SIYOSYA_KN"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["SIYOSYA"]
                        );
                        tttArr["TOIAWASENM"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["TOIAWASENM"]
                        );
                        tttArr["SYADAI_NO"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["HANBAISYASYU"]
                        );
                        tttArr["SYASYUMEI"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["SYASYU_NM"]
                        );
                        tttArr["KASOU_NO"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["KASOUNO"]
                        );
                        tttArr["HAKKOBI"] = me.nowDate2;
                        tttArr["CMN_NO"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["CMN_NO"]
                        );
                        tttArr["KYOTN_CD"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["BUSYO_CD"]
                        );
                        tttArr["BUSYO_NM"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["BUSYO"]
                        );
                        tttArr["SYAIN_KNJ_SEI"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["SYAIN"]
                        );
                        tttArr["BUHINNM"] = clsComFnc.FncNv(
                            me.DsDeleteTbl[j]["BUHINNM"]
                        );
                        tttArr["SEIKYU"] =
                            Math.round(
                                clsComFnc.FncNz(
                                    me.DsDeleteTbl[j]["GAICYU_ZITU"]
                                )
                            ) * -1;
                        //請求金額合計を計算
                        ingSyoukei = Math.round(
                            clsComFnc.FncNz(me.DsDeleteTbl[j]["GAICYU_ZITU"])
                        );
                        tttArr["SYOUKEI"] = ingSyoukei * -1;
                        tmpArrSYOUKEI.push(
                            tttArr["TORIHIKI_CD"] + "_" + ingSyoukei
                        );
                        DsGaichuPrintArray[intGaiCnt] = tttArr;
                        tttArr = [];
                        intGaiCnt += 1;
                    }
                    j += 1;
                }
                var tTotalArr = new Array();
                var tt = "";
                for (var i = 0; i < tmpArrSYOUKEI.length; i++) {
                    var tMax = 0;
                    var t2 = tmpArrSYOUKEI[i].split("_");
                    tMax = parseInt(t2[1]);
                    if (t2[0] == tt) {
                    } else {
                        for (var j = i + 1; j < tmpArrSYOUKEI.length; j++) {
                            var t3 = tmpArrSYOUKEI[j].split("_");
                            if (t2[0] == t3[0]) {
                                tMax = tMax + parseInt(t3[1]);
                                tt = t2[0];
                            }
                        }
                        tTotalArr[t2[0]] = tMax;
                    }
                }

                while (intTotalCnt < intGaiCnt) {
                    intTotalCnt += 1;
                    intChgBefCnt += 1;
                }
                for (key in DsGaichuPrintArray) {
                    for (key1 in tTotalArr) {
                        if (DsGaichuPrintArray[key]["TORIHIKI_CD"] == key1) {
                            DsGaichuPrintArray[key]["SYOUKEI"] =
                                "-" + tTotalArr[key1];
                        }
                    }
                }
            }
            switch (intRptCnt) {
                case 0:
                    me.DeleteKasou();
                    break;
                case 1:
                    //アクティブレポート関連

                    //架装部用品注文書

                    //プレビュー表示
                    me.lngOutCntK = me.DsKasouPrintArray.length;
                    //印刷账票…………。
                    console.log(
                        "-----------------------DsKasouPrintArray---------------------------"
                    );
                    console.log(me.DsKasouPrintArray);
                    console.log(
                        "-------------------------------------------------------------------"
                    );
                    var tmpPrintUrl = me.sys_id + "/" + me.id + "/fncPrintTbl";
                    me.data = {
                        request: me.DsKasouPrintArray,
                    };
                    var data = {
                        tmpVal: me.data,
                        intRptCnt: intRptCnt,
                    };
                    //--
                    var ajax1 = new gdmz.common.ajax();
                    ajax1.receive = function (response) {
                        response = $.parseJSON(response);
                        // response = response.substring(1, response.length - 1);
                        window.open(response);
                        me.DeleteKasou();
                    };
                    ajax1.send(tmpPrintUrl, data, 0);
                    break;
                case 2:
                    //アクティブレポート関連

                    //架装部用品注文書

                    //プレビュー表示
                    me.lngOutCntG = DsGaichuPrintArray.length;
                    //印刷账票…………。
                    var tmpPrintUrl = me.sys_id + "/" + me.id + "/fncPrintTbl";
                    console.log(
                        "-----------------------DsGaichuPrintArray---------------------------"
                    );
                    console.log(DsGaichuPrintArray);
                    console.log(
                        "-------------------------------------------------------------------"
                    );
                    me.data = {
                        request: DsGaichuPrintArray,
                    };
                    var data = {
                        tmpVal: me.data,
                        intRptCnt: intRptCnt,
                    };
                    var ajax2 = new gdmz.common.ajax();
                    ajax2.receive = function (response) {
                        response = $.parseJSON(response);
                        // response = response.substring(1, response.length - 1);
                        window.open(response);
                        me.DeleteKasou();
                    };
                    ajax2.send(tmpPrintUrl, data, 0);
                    break;
                case 3:
                    //アクティブレポート関連
                    //架装部用品注文書
                    //プレビュー表示
                    me.lngOutCntK = me.DsKasouPrintArray.length;
                    me.lngOutCntG = DsGaichuPrintArray.length;
                    //印刷账票…………。
                    var tmpPrintUrl = me.sys_id + "/" + me.id + "/fncPrintTbl";
                    console.log(
                        "-----------------------DsKasouPrintArray & DsGaichuPrintArray---------------------------"
                    );
                    console.log("-------DsGaichuPrintArray-------");
                    console.log(DsGaichuPrintArray);
                    console.log("-------DsKasouPrintArray-------");
                    console.log(me.DsKasouPrintArray);
                    console.log(
                        "-------------------------------------------------------------------"
                    );
                    me.data = {
                        request1: me.DsKasouPrintArray,
                        request2: DsGaichuPrintArray,
                    };
                    var data = {
                        tmpVal: me.data,
                        intRptCnt: intRptCnt,
                    };
                    var ajax3 = new gdmz.common.ajax();
                    ajax3.receive = function (response) {
                        response = $.parseJSON(response);
                        // response = response.substring(1, response.length - 1);
                        window.open(response);
                        me.DeleteKasou();
                    };
                    ajax3.send(tmpPrintUrl, data, 0);
                    break;
            }
            $(".FrmAkaden.button_print").button("enable");
        };
        ajax.send(url, data, 0);
        ajax.beforeLogin = me.buttonable;
    });
    me.buttonable = function () {
        $(".FrmAkaden.button_print").button("enable");
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    base_load = me.load;

    /*
     **********************************************************************
     '処 理 名：フォームロード
     '関 数 名：
     '引    数：無し
     '戻 り 値：無し
     '処理説明：画面読み込み処理
     **********************************************************************
     */
    me.load = function () {
        base_load();

        gdmz.common.jqgrid.init(
            me.grid_id,
            me.g_url,
            me.colModel,
            me.pager,
            me.sidx,
            me.option
        );
        me.t = document.getElementById("divFrmAkaden_pager_center");
        me.t.childNodes[1].innerHTML = "";
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1018 : 1065
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 243 : 270
        );
        $("#jqgh_FrmAkaden_sprList_cb").append("<br>削除");
        //$(".ui-pg-selbox option[value = 100000]").text("all");
        me.FrmAkaden_subFormClear(true);
    };

    me.DeleteKasou = function () {
        fncPrintSection1 = true;

        if ((fncPrintSection1 = false)) {
            return;
        }

        //～削除処理～
        var DeleteArray1 = new Array();
        var DsDeleteTbl_kk = [];
        for (key in me.DsDeleteTbl) {
            var tmparrr = {
                CMN_NO: "",
                EDA_NO: "",
                KASOUNO: "",
                SYADAIKATA: "",
                CAR_NO: "",
                HANBAISYASYU: "",
                TOIAWASENM: "",
                SYASYU_NM: "",
                MEMO: "",
                FUZOKUHINKBN: "",
                GYOUSYA_CD: "",
                GYOUSYA_NM: "",
                MEDALCD: "",
                BUHINNM: "",
                BIKOU: "",
                SUURYOU: "",
                TEIKA: "",
                BUHIN_SYANAI_GEN: "",
                BUHIN_SYANAI_ZITU: "",
                GAICYU_GEN: "",
                GAICYU_ZITU: "",
                ZEIRITU: "",
                KAZEIKBN: "",
                DELKBN: "",
                UPD_DATE: "",
                CREATE_DATE: "",
                SIYOSYA: "",
                BUSYO: "",
                SYAIN: "",
                BUSYO_CD: "",
            };
            for (key1 in me.DsDeleteTbl[key]) {
                tmparrr[key1] = me.DsDeleteTbl[key][key1];
            }
            DsDeleteTbl_kk.push(tmparrr);
        }
        //削除対象ﾃｰﾌﾞﾙのデータが終了するまで
        for (var k = 0; k < me.DsDeleteTbl.length; k++) {
            var DeleteArray = {
                CMN_NO: "",
                EDA_NO: "",
                KASOUNO: "",
                FUZOKUHINKBN: "",
            };
            DeleteArray["CMN_NO"] = me.DsDeleteTbl[k]["CMN_NO"];
            DeleteArray["EDA_NO"] = me.DsDeleteTbl[k]["EDA_NO"];
            DeleteArray["KASOUNO"] = me.DsDeleteTbl[k]["KASOUNO"];
            DeleteArray["FUZOKUHINKBN"] = me.DsDeleteTbl[k]["FUZOKUHINKBN"];
            DeleteArray1.push(DeleteArray);
        }

        var funcID = "fncDeleteKasou";
        var tmpR = "";
        tmpR = {
            request: DeleteArray1,
            DsDeleteTbl: DsDeleteTbl_kk,
            intState: me.intState,
            lngOutCntK: me.lngOutCntK,
            lngOutCntG: me.lngOutCntG,
        };
        var tmpPrintUrl = me.sys_id + "/" + me.id + "/" + funcID;
        var ajax4 = new gdmz.common.ajax();
        ajax4.receive = function () {
            me.FrmAkaden_subFormClear(true);
            me.FrmAkaden_search_clickFun();
        };
        ajax4.send(tmpPrintUrl, tmpR, 0);
    };
    /*
     ********************************
     '処 理 名：画面項目初期化
     '関 数 名：subFormClear
     '引    数：無し
     '戻 り 値：無し
     '処理説明：画面項目を初期化する
     ********************************
     */
    me.FrmAkaden_subFormClear = function (HedderClear) {
        console.log("clear body....");
        HedderClear = arguments[0] != undefined ? arguments[0] : true;
        if (HedderClear == true) {
            $(".FrmAkaden.inputText_SiyFgn").val("");
            $(".FrmAkaden.inputText_EmpNO").val("");
            $(".FrmAkaden.inputText_CMN_NO").val("");
            $(me.grid_id).clearGridData();
            $(me.grid_id).jqGrid("resetSelection");
            me.selectedIdsArray = new Array();
            me.selectedDataArray = new Array();
        }
        $(".FrmAkaden.button_print").button("disable");
        $(".FrmAkaden.inputＴext_CMNNO").trigger("focus");
    };

    me.FrmAkaden_search_clickFun = function () {
        console.log("clickFunc....");
        if (
            $.trim($(".FrmAkaden.inputＴext_CMNNO").val()) == "" &&
            $.trim($(".FrmAkaden.inputText_SiyFgn").val()) == "" &&
            $.trim($(".FrmAkaden.inputText_EmpNO").val()) == ""
        ) {
            clsComFnc.FncMsgBox("W0009");
            return;
        }
        me.data = {
            CMN_NO: $(".FrmAkaden.inputＴext_CMNNO").val(),
            SIYFGN: $(".FrmAkaden.inputText_SiyFgn").val(),
            EMPNO: $(".FrmAkaden.inputText_EmpNO").val(),
        };
        me.complete_fun = function () {
            if ($(me.grid_id).jqGrid("getGridParam", "records") > 0) {
                $(".FrmAkaden.button_print").button("enable");
                me.fncCompleteDeal();
            } else {
                clsComFnc.FncMsgBox("W0016");
                $(".FrmAkaden.button_print").button("disable");
            }
        };

        gdmz.common.jqgrid.reload(me.grid_id, me.data, me.complete_fun);
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1018 : 1065
        );
        //gdmz.common.jqgrid.set_grid_width(me.grid_id, 1050);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 243 : 270
        );
    };

    me.SelectRow_fun = function () {
        $(me.grid_id).jqGrid("setGridParam", {
            onSelectRow: function (rowId, status) {
                var tmpRowData = $(me.grid_id).jqGrid("getRowData", rowId);
                var tmpPageVal = parseInt($(".ui-pg-input").val());

                if (me.selectRowFlg == false) {
                    ids = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
                    me.selectedIdsArray[tmpPageVal] = ids;

                    if (status == true) {
                        var tmpKey = tmpPageVal + "_" + rowId;
                        me.selectedDataArray[tmpKey] = tmpRowData;
                    } else {
                        var tmpKey = tmpPageVal + "_" + rowId;
                        me.selectedDataArray[tmpKey] = null;
                    }
                }
            },
        });
    };

    me.fncCompleteDeal = function () {
        for (key in me.selectedIdsArray) {
            var tmpPageVal = parseInt($(".ui-pg-input").val());
            if (tmpPageVal == parseInt(key)) {
                for (key1 in me.selectedIdsArray[key]) {
                    me.selectRowFlg = true;
                    $(me.grid_id).jqGrid(
                        "setSelection",
                        me.selectedIdsArray[key][key1]
                    );
                    me.selectRowFlg = false;
                }
                break;
            }
        }
        me.SelectRow_fun();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmAkaden = new R4.FrmAkaden();
    o_R4_FrmAkaden.load();
});
