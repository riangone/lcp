/**
 * 伝票出力担当者マスタメンテナンス
 * @alias  FrmPrintTanto
 * @author FCSDL luchao
 */
Namespace.register("R4.FrmPrintTanto");

R4.FrmPrintTanto = function () {
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.id = "FrmPrintTanto";
    me.sys_id = "R4G";
    me.Array_PrintTanto = new Array();

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmPrintTanto.cmdReg",
        type: "button",
        handle: "",
    });

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmPrintTanto.cmdReg").click(function () {
        if (!me.inputCheck()) {
            return false;
        } else {
            //確認を行なう
            clsComFnc.MsgBoxBtnFnc.Yes = me.UpdateInsert;
            clsComFnc.MessageBox(
                "変更内容をDBに登録しますか？",
                clsComFnc.GSYSTEM_NAME,
                clsComFnc.MessageBoxButtons.YesNo,
                clsComFnc.MessageBoxIcon.Question
            );
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ========== KeyDown start ==========

    //ShifキーとTabキーのバインド
    clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    clsComFnc.TabKeyDown();

    //Enterキーのバインド
    clsComFnc.EnterKeyDown();

    // ========== KeyDown end ==========

    // ==========
    // = メソッド start =
    // ==========

    me.DataSelect = function () {
        var funcName = "fncPrintTantoSelect";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var init_mark = 1;
        var data = "";

        ajax.receive = function (result) {
            var jsObject = JSON.parse(result);
            if (jsObject["result"]) {
                if (jsObject["data"] != "") {
                    $(".FrmPrintTanto.txtTANTO_SEI").val(
                        jsObject["data"][0]["TANTO_SEI"]
                    );
                    $(".FrmPrintTanto.txtTANTO_MEI").val(
                        jsObject["data"][0]["TANTO_MEI"]
                    );
                    $(".FrmPrintTanto.txtBUSYO_NM").val(
                        jsObject["data"][0]["BUSYO_NM"]
                    );
                    $(".FrmPrintTanto.txtTANTO_SEI").trigger("focus");;
                    $(".FrmPrintTanto.txtTANTO_SEI").select();
                    me.Array_PrintTanto = jsObject["data"][0];
                } else {
                    $(".FrmPrintTanto.txtTANTO_SEI").val("");
                    $(".FrmPrintTanto.txtTANTO_MEI").val("");
                    $(".FrmPrintTanto.txtBUSYO_NM").val("");
                }
            }
        };
        ajax.send(url, data, init_mark);

        // ajax.error = function(XMLHttpRequest, textStatus, errorThrown) {
        // clsComFnc.MessageBox('error:' + errorThrown, me.sys_id, clsComFnc.MessageBoxButtons.OK, clsComFnc.MessageBoxIcon.Err);
        // };
    };

    me.DataSelect();

    me.inputCheck = function () {
        switch (
            clsComFnc.FncTextCheck(
                $(".FrmPrintTanto.txtTANTO_SEI"),
                1,
                clsComFnc.INPUTTYPE.CHAR3,
                64
            )
        ) {
            case -1:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_SEI");
                clsComFnc.MessageBox(
                    "担当者姓が入力されていません",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -2:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_SEI");
                clsComFnc.MessageBox(
                    "担当者姓は入力値が不正です！",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -3:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_SEI");
                clsComFnc.MessageBox(
                    "担当者姓が入力可能な文字数を超えています",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
        }

        switch (
            clsComFnc.FncTextCheck(
                $(".FrmPrintTanto.txtTANTO_MEI"),
                1,
                clsComFnc.INPUTTYPE.CHAR3,
                64
            )
        ) {
            case -1:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_MEI");
                clsComFnc.MessageBox(
                    "担当者名が入力されていません",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -2:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_MEI");
                clsComFnc.MessageBox(
                    "担当者名は入力値が不正です！",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -3:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtTANTO_MEI");
                clsComFnc.MessageBox(
                    "担当者名が入力可能な文字数を超えています",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
        }

        switch (
            clsComFnc.FncTextCheck(
                $(".FrmPrintTanto.txtBUSYO_NM"),
                1,
                clsComFnc.INPUTTYPE.CHAR3,
                12
            )
        ) {
            case -1:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtBUSYO_NM");
                clsComFnc.MessageBox(
                    "部署名が入力されていません",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -2:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtBUSYO_NM");
                clsComFnc.MessageBox(
                    "部署名は入力値が不正です！",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
            case -3:
                clsComFnc.ObjFocus = $(".FrmPrintTanto.txtBUSYO_NM");
                clsComFnc.MessageBox(
                    "部署名が入力可能な文字数を超えています",
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Warning
                );
                return false;
        }
        return true;
    };

    me.UpdateInsert = function () {
        var insert_Array = new Array();
        var data = new Array();
        var funcName = "fncDeleteUpdataPrintTanto";
        var url = me.sys_id + "/" + me.id + "/" + funcName;
        var init_mark = 0;

        //データに変更がない場合は必要なし
        if (
            $(".FrmPrintTanto.txtTANTO_SEI").val() ==
                me.Array_PrintTanto["TANTO_SEI"] &&
            $(".FrmPrintTanto.txtTANTO_MEI").val() ==
                me.Array_PrintTanto["TANTO_MEI"] &&
            $(".FrmPrintTanto.txtBUSYO_NM").val() ==
                me.Array_PrintTanto["BUSYO_NM"]
        ) {
            $(".FrmPrintTanto.cmdReg").trigger("focus");;
            return false;
        }

        insert_Array = {
            TANTO_SEI: $(".FrmPrintTanto.txtTANTO_SEI").val(),
            TANTO_MEI: $(".FrmPrintTanto.txtTANTO_MEI").val(),
            BUSYO_NM: $(".FrmPrintTanto.txtBUSYO_NM").val(),
            CREATE_DATE: me.Array_PrintTanto["CREATE_DATE"],
        };

        data = {
            request: insert_Array,
        };

        //更新処理を行なう

        ajax.receive = function (result) {
            console.log(result);
            var jsObject = JSON.parse(result);
            if (jsObject["result"]) {
                clsComFnc.MessageBox(
                    "登録完了しました。",
                    "DB登録",
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Information
                );
            } else {
                clsComFnc.MessageBox(
                    jsObject["data"],
                    me.sys_id,
                    clsComFnc.MessageBoxButtons.OK,
                    clsComFnc.MessageBoxIcon.Err
                );
            }
            me.DataSelect();
        };

        ajax.send(url, data, init_mark);

        // ajax.error = function(XMLHttpRequest, textStatus, errorThrown) {
        // clsComFnc.MessageBox('error:' + errorThrown, me.sys_id, clsComFnc.MessageBoxButtons.OK, clsComFnc.MessageBoxIcon.Err);
        // };
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmPrintTanto = new R4.FrmPrintTanto();
    o_R4_FrmPrintTanto.load();
});
