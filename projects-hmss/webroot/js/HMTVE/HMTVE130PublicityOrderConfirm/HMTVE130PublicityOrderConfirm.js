/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author GSDL
 *
 * 履歴：
 * ------------------------------------------------------------------------------------------------------------------------------------
 * 日付							Feature/Bug					　　　　内容															   担当
 * YYYYMMDD						#ID							　　　　XXXXXX															  GSDL
 * 20240329        受入検証.xlsx NO5     見出し部分の表示内容が不正             	  		YIN
 * -------------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("HMTVE.HMTVE130PublicityOrderConfirm");

HMTVE.HMTVE130PublicityOrderConfirm = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.HMTVE = new HMTVE.HMTVE();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";

    me.sys_id = "HMTVE";
    me.id = "HMTVE130PublicityOrderConfirm";
    me.grid_id = "#HMTVE130PublicityOrderConfirm_talGrd";

    me.lblDay = "";
    me.option = {
        //datatype : "local",
        caption: "",
        height: 172,
        rownumbers: false,
        rowNum: 0,
        loadui: "disable",
        autoScroll: true,
        colModel: me.colModel,
        multiselect: false,
    };
    me.colModel = [
        {
            label: "展示会開始日",
            width: 225,
            align: "left",
            name: "START_DATE",
            index: "START_DATE",
            sortable: false,
            hidden: true,
        },
        {
            label: "日時/展示会名",
            width: 225,
            align: "center",
            name: "HIDUKE_NM",
            index: "HIDUKE_NM",
            sortable: false,
            classes: "hasBack",
        },
        {
            // 20240329 YIN UPD S
            // label: '',
            label: "ＤＢセット<br /> @150",
            // 20240329 YIN UPD E
            width: 145,
            align: "right",
            name: "ORDER_VAL1",
            index: "ORDER_VAL1",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            // 20240329 YIN UPD S
            // label: '',
            label: "DH<br /> @100",
            // 20240329 YIN UPD E
            width: 145,
            align: "right",
            name: "ORDER_VAL2",
            index: "ORDER_VAL2",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            // 20240329 YIN UPD S
            // label: '',
            label: "来場プレゼント<br /> @300",
            // 20240329 YIN UPD E
            width: 145,
            align: "right",
            name: "ORDER_VAL3",
            index: "ORDER_VAL3",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "合計金額",
            width: 150,
            align: "right",
            name: "GOUKEI",
            index: "GOUKEI",
            sortable: false,
            formatter: "currency",
            formatoptions: {
                thousandsSeparator: ",",
            },
        },
        {
            label: "備考",
            width: 245,
            align: "left",
            name: "BIKOU",
            index: "BIKOU",
            sortable: false,
        },
    ];

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE130PublicityOrderConfirm.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMTVE.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HMTVE.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HMTVE.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //入力画面に戻るボタンクリック
    $(".HMTVE130PublicityOrderConfirm.btnReturn").click(function () {
        me.btnReturn_Click();
    });
    //注文を確定ボタンクリック
    $(".HMTVE130PublicityOrderConfirm.btnValidate").click(function () {
        me.btnValidate_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        try {
            base_init_control();
            //ページロード
            me.Page_Load();
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：ページロード

	 '関 数 名：Page_Load
	 '戻 り 値：なし

	 '処理説明：ページ初期化
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        try {
            $(".HMTVE130PublicityOrderConfirm.body").dialog({
                autoOpen: false,
                modal: true,
                height: me.ratio === 1.5 ? 420 : 500,
                width: 1175,
                resizable: false,
                title: "展示会宣材注文_注文内容確認",
                open: function () {},
                close: function () {
                    me.before_close();
                    $(".HMTVE130PublicityOrderConfirm.body").remove();
                },
            });
            $(".HMTVE130PublicityOrderConfirm.body").dialog("open");

            //画面初期化
            //表示設定

            var IVENTYM = $("#IVENTYM").html();
            if (IVENTYM == null || IVENTYM == "") {
                me.clsComFnc.FncMsgBox("E9999", "展示会開催年月は未設定です。");
                return;
            }
            me.lblDay = $("#IVENTYM").html();

            var g_url = me.sys_id + "/" + me.id + "/pageLoad";
            var data = {
                NENGETU: me.lblDay.replace("/", ""),
            };
            gdmz.common.jqgrid.showWithMesg(
                me.grid_id,
                g_url,
                me.colModel,
                "",
                "",
                me.option,
                data,
                me.complete_fun
            );
            gdmz.common.jqgrid.set_grid_width(me.grid_id, 1110);
            gdmz.common.jqgrid.set_grid_height(me.grid_id, 168);

            $(me.grid_id).jqGrid("bindKeys");
        } catch (ex) {
            console.log(ex);
        }
    };

    me.complete_fun = function (_bErrorFlag, result) {
        if (result["error"] == "") {
            //店舗名を表示する
            if (result["datasp"].length > 0) {
                $(".HMTVE130PublicityOrderConfirm.lblShop").html(
                    result["datasp"][0]["BUSYO_RYKNM"]
                );
            } else {
                $(".HMTVE130PublicityOrderConfirm.lblShop").html("&nbsp;");
            }
            //展示会宣材注文_入力画面の値をセットする
            $(".HMTVE130PublicityOrderConfirm.lblDay").html(me.lblDay + "月分");

            //展示会テーブルの生成(GRIDVIEW)
            $(
                ".HMTVE130PublicityOrderConfirm.pnlList .ui-jqgrid tr.jqgrow td.hasBack"
            ).css(
                "background",
                "#16b1e9 url(css/jquery/images/ui-bg_gloss-wave_75_16b1e9_500x100.png) 50% 50% repeat-x"
            );
            $(
                ".HMTVE130PublicityOrderConfirm.pnlList .ui-jqgrid tr.jqgrow td.hasBack"
            ).css("color", "#222222");

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ取得
            if (result["datahd"].length > 0) {
                me.grdHeaderSet(result["datahd"][0]);
            }

            //注文金額合計を求める
            me.getSum();

            //フォーカス移動
            $(".HMTVE130PublicityOrderConfirm.btnReturn").trigger("focus");
        } else {
            me.clsComFnc.FncMsgBox("E9999", result["error"]);
        }
    };

    // '**********************************************************************
    // '処 理 名：展示会ﾍｯﾀﾞの設定
    // '関 数 名：grdHeaderSet
    // '引 数 １：(I)objdr
    // '戻 り 値：なし
    // '処理説明：展示会ﾃｰﾌﾞﾙﾍｯﾀﾞーの設定
    // '**********************************************************************
    me.grdHeaderSet = function (objdr) {
        try {
            //展示会ﾍｯﾀﾞｰﾃｰﾌﾞﾙに①－１の取得データをセットする
            $(me.grid_id).setLabel("HIDUKE_NM", "日時/展示会名");

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_1")＝0の場合
            if (objdr["COL_HED_1"].toString() == " @") {
                $(me.grid_id).setLabel("ORDER_VAL1", "&nbsp;");
                for (var i = 0; i < $(me.grid_id).getRowData().length; i++) {
                    $(me.grid_id).setCell(i, "ORDER_VAL1", "&nbsp");
                }
            } else {
                $(me.grid_id).setLabel("ORDER_VAL1", objdr["COL_HED_1"]);
            }

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_2")＝0の場合
            if (objdr["COL_HED_2"].toString() == " @") {
                $(me.grid_id).setLabel("ORDER_VAL2", "&nbsp;");
                for (var i = 0; i < $(me.grid_id).getRowData().length; i++) {
                    $(me.grid_id).setCell(i, "ORDER_VAL2", "&nbsp");
                }
            } else {
                $(me.grid_id).setLabel("ORDER_VAL2", objdr["COL_HED_2"]);
            }

            //展示会ﾍｯﾀﾞｰﾃﾞｰﾀ("HANDAN_3")＝0の場合
            if (objdr["COL_HED_3"].toString() == " @") {
                $(me.grid_id).setLabel("ORDER_VAL3", "&nbsp;");
                for (var i = 0; i < $(me.grid_id).getRowData().length; i++) {
                    $(me.grid_id).setCell(i, "ORDER_VAL3", "&nbsp");
                }
            } else {
                $(me.grid_id).setLabel("ORDER_VAL3", objdr["COL_HED_3"]);
            }

            $(me.grid_id).setLabel("GOUKEI", "合計金額");
            $(me.grid_id).setLabel("BIKOU", "備考");
        } catch (ex) {
            console.log(ex);
        }
    };
    // '**********************************************************************
    // '処 理 名：合計の取得
    // '関 数 名：getSum
    // '戻 り 値：なし
    // '処理説明：合計の取得します
    // '**********************************************************************
    me.getSum = function () {
        try {
            var sum = 0;
            var datas = $(me.grid_id).getRowData();
            for (var i = 0; i < datas.length; i++) {
                sum += parseInt(datas[i]["GOUKEI"]);
            }
            $(".HMTVE130PublicityOrderConfirm.lblSum").html(
                sum.toLocaleString()
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：入力画面に戻るボタンのイベント
	 '関 数 名：btnReturn_Click
	 '戻 り 値：なし
	 '処理説明：入力画面に戻る
	 '**********************************************************************
	 */
    me.btnReturn_Click = function () {
        try {
            $("#RtnCD").html("0");
            $(".HMTVE130PublicityOrderConfirm.body").dialog("close");
        } catch (ex) {
            console.log(ex);
        }
    };
    // '**********************************************************************
    // '処 理 名：注文を確定ボタンのイベント
    // '関 数 名：btnValidate_Click
    // '戻 り 値：なし
    // '処理説明：注文を確定
    // '**********************************************************************
    me.btnValidate_Click = function () {
        try {
            var url = me.sys_id + "/" + me.id + "/btnValidateClick";
            var data = {
                NENGETU: me.lblDay.replace("/", ""),
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    $("#RtnCD").html("1");
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.close;
                    me.clsComFnc.MsgBoxBtnFnc.Close = me.close;
                    me.clsComFnc.FncMsgBox("I0016");
                } else {
                    if (result["error"] == "E9999") {
                        me.clsComFnc.FncMsgBox(
                            "E9999",
                            "既に出力が行われていますので、注文を確定できません！"
                        );
                        return;
                    } else {
                        me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    }
                }
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };

    me.close = function () {
        $(".HMTVE130PublicityOrderConfirm.body").dialog("close");
    };

    me.before_close = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE130PublicityOrderConfirm =
        new HMTVE.HMTVE130PublicityOrderConfirm();
    o_HMTVE_HMTVE130PublicityOrderConfirm.load();

    o_HMTVE_HMTVE.o_HMTVE_HMTVE130PublicityOrderConfirm =
        o_HMTVE_HMTVE130PublicityOrderConfirm;

    o_HMTVE_HMTVE.HMTVE120PublicityOrderEntry.HMTVE130PublicityOrderConfirm =
        o_HMTVE_HMTVE130PublicityOrderConfirm;
    o_HMTVE_HMTVE130PublicityOrderConfirm.HMTVE120PublicityOrderEntry =
        o_HMTVE_HMTVE.HMTVE120PublicityOrderEntry;
});
