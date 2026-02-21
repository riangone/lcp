/**
 *
 * R4連携集計システムダウンロード
 * @alias FrmGenka
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                   XXXXXX                         FCSDL
 * 20151109	         BUG#2264											     Yuanjh
 * 20151110	         BUG#2248											     Yuanjh
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmWDTSImportFS");

R4.FrmWDTSImportFS = function () {
    var me = new gdmz.base.panel();
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    // ==========
    // = 宣言 start =
    // ==========
    me.id = "FrmWDTSImportFS";
    me.sys_id = "R4K";

    // ========== 変数 start ==========
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmWDTSImportFS.cmdAction",
        type: "button",
        handle: "",
    });
    //--20151011   Yuanjh  DEL S.
    // $('.FrmWDTSDownLoadFS.Dialog').dialog({
    // autoOpen : false,
    // modal : true,
    // resizable : false,
    // width : 1105,
    // height : 600,
    // open : function(event, ui) {
    // alert(2);
    // },
    // close : function() {
    //
    // }
    // });
    //--20151011   Yuanjh  DEL E.

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();
    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();
    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //実行ボタンクリック
    $(".FrmWDTSImportFS.cmdAction").click(function () {
        base_load();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.MessageBox(
                    result["data"],
                    "R4→（GD）（DZM）データ連携サブシステム",
                    me.clsComFnc.MessageBoxButtons.OK,
                    me.clsComFnc.MessageBoxIcon.Err
                );
                return;
            } else if (result["result"] == true) {
                me.loadFrmDLStateCheck();
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "buttonclick";
        me.ajax.send(url, "data", 0);
    });
    me.loadFrmDLStateCheck = function () {
        me.ajax.receive = function (result) {
            //--20151011   Yuanjh  ADD S.
            $(".FrmWDTSImportFS.R4-content").append(
                '<div class="FrmWDTSImportFS Dialog"></div>'
            );
            //--20151011   Yuanjh  ADD E.
            $(".FrmWDTSImportFS.Dialog").dialog({
                autoOpen: false,
                modal: true,
                resizable: false,
                width: 1105,
                height: 600,
                open: function (_event) {},
                close: function () {
                    //--20151011   Yuanjh  ADD S.
                    $(".FrmWDTSImportFS.Dialog").remove();
                    //--20151011   Yuanjh  ADD E.
                },
            });
            $(".FrmWDTSImportFS.Dialog").html(result);
            $(".FrmWDTSImportFS.Dialog").dialog(
                "option",
                "title",
                "ダウンロード実行状況確認"
            );
            $(".FrmWDTSImportFS.Dialog").dialog("open");
        };
        var url = me.sys_id + "/" + "frmDLStateCheck";
        me.ajax.send(url, "data", 0);
    };
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //初期処理
    var base_load = me.load;

    me.load = function () {
        base_load();
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmWDTSImportFS = new R4.FrmWDTSImportFS();
    o_R4_FrmWDTSImportFS.load();
});
