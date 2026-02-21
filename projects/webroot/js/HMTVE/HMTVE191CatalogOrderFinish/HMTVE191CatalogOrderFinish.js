/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE191CatalogOrderFinish");

HMTVE.HMTVE191CatalogOrderFinish = function () {
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
    me.id = "HMTVE191CatalogOrderFinish";

    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".HMTVE191CatalogOrderFinish.btnRirekiKakunin",
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
    //注文履歴確認画面へボタンクリック
    $(".HMTVE191CatalogOrderFinish.btnRirekiKakunin").click(function () {
        me.btnRirekiKakunin_Click();
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
            $(".HMTVE191CatalogOrderFinish.body").dialog({
                autoOpen: false,
                modal: true,
                height: 280,
                width: 600,
                resizable: false,
                title: "カタログ注文_完了",
                open: function () {},
                close: function () {
                    $(".HMTVE191CatalogOrderFinish.body").remove();
                    o_HMTVE_HMTVE.HMTVE170CatalogOrderEntry.load();
                },
            });
            $(".HMTVE191CatalogOrderFinish.body").dialog("open");

            //注文番号表示
            $(".HMTVE191CatalogOrderFinish.lblOrderNO").text(
                $("#OrderNO").val()
            );

            //店舗名を表示する
            var url = me.sys_id + "/" + me.id + "/" + "pageload";
            var data = {
                OrderNO: $.trim(
                    $(".HMTVE191CatalogOrderFinish.lblOrderNO").text()
                ),
            };
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                if (result["row"] == 0) {
                    return;
                }
                var objdr = result["data"][0];
                $(".HMTVE191CatalogOrderFinish.lblBusyoNM").text(
                    objdr["BUSYO_RYKNM"]
                );
                $(".HMTVE191CatalogOrderFinish.lblOrderDate").text(
                    objdr["ORDER_DATE"]
                );
                $(".HMTVE191CatalogOrderFinish.lblOrderNum").text(
                    objdr["ORDER_SUM"]
                );
            };
            me.ajax.send(url, data, 0);
        } catch (ex) {
            console.log(ex);
        }
    };
    /*
	 '**********************************************************************
	 '処 理 名：注文内容履歴確認画面に遷移
	 '関 数 名：btnRirekiKakunin_Click
	 '戻 り 値：なし
	 '処理説明：注文内容履歴確認画面に遷移
	 '**********************************************************************
	 */
    me.btnRirekiKakunin_Click = function () {
        try {
            $(".HMTVE191CatalogOrderFinish.body").dialog("close");
            $(".HMTVE180CatalogOrderConfirm.body").dialog("close");

            o_HMTVE_HMTVE.FrmHMTVEMainMenu.blnFlag = false;
            o_HMTVE_HMTVE.FrmHMTVEMainMenu.HMTVE191_data = "params";
            $(".FrmHMTVEMainMenu.Menu").jstree(
                "deselect_node",
                "#HMTVE170CatalogOrderEntry"
            );
            $(".FrmHMTVEMainMenu.Menu").jstree(
                "select_node",
                "#HMTVE190CatalogOrderCareer"
            );
        } catch (ex) {
            console.log(ex);
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE191CatalogOrderFinish =
        new HMTVE.HMTVE191CatalogOrderFinish();
    o_HMTVE_HMTVE191CatalogOrderFinish.load();
});
