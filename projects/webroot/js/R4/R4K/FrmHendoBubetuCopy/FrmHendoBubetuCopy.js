/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                   Feature/Bug                 内容                         担当
 * YYYYMMDD                  #ID                     XXXXXX                      FCSDL
 * 20150928                  #2179                   BUG                         LI
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmHendoBubetuCopy");

R4.FrmHendoBubetuCopy = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmHendoBubetuCopy";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.FrmHendoBubetu = null;
    me.PrpFlg = false;
    me.prpKeijyoYM = "";
    me.prpItemNO = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHendoBubetuCopy.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetuCopy.cmdEnd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoBubetuCopy.cboYMEnd",
        //-- 20150928 LI UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150928 LI UPD E.
        handle: "",
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
    $(".FrmHendoBubetuCopy.cboYMEnd").on("blur", function () {
        //-- 20150928 LI UPD S.
        //if (clsComFnc.CheckDate2($(".FrmHendoBubetuCopy.cboYMEnd")) == false)
        if (clsComFnc.CheckDate3($(".FrmHendoBubetuCopy.cboYMEnd")) == false) {
            //-- 20150928 LI UPD E.
            $(".FrmHendoBubetuCopy.cboYMEnd").val(
                $(".FrmHendoBubetu.cboYM").val()
            );
            $(".FrmHendoBubetuCopy.cboYMEnd").trigger("focus");
            $(".FrmHendoBubetuCopy.cboYMEnd").select();
            $(".FrmHendoBubetuCopy.cmdEnd").button("disable");
            $(".FrmHendoBubetuCopy.cmdAction").button("disable");
        } else {
            $(".FrmHendoBubetuCopy.cmdEnd").button("enable");
            $(".FrmHendoBubetuCopy.cmdAction").button("enable");
        }
    });

    $(".FrmHendoBubetuCopy.cmdEnd").click(function () {
        $("#cmdCopyDialogDiv").dialog("close");
    });

    $(".FrmHendoBubetuCopy.cmdAction").click(function () {
        me.url = me.sys_id + "/" + me.id + "/fncExistCheckSel";
        //-- 20150928 LI UPD S.
        //var TOUGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        //var ZENGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        var TOUGETUVal =
            $(".FrmHendoBubetuCopy.cboYMEnd").val().substring(0, 4) +
            "/" +
            $(".FrmHendoBubetuCopy.cboYMEnd").val().substring(4);
        var ZENGETUVal = TOUGETUVal;
        //-- 20150928 LI UPD E.

        var arr = {
            TOUGETU: TOUGETUVal,
            ZENGETU: ZENGETUVal,
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

            if (result["row"] > 0) {
                clsComFnc.MsgBoxBtnFnc.Yes = me.fncDeleteStaffKoumoku;
                clsComFnc.FncMsgBox(
                    "QY999",
                    "コピー元と重複するコピー先年月のデータが既に存在しています。上書きしてもよろしいですか？"
                );
                return;
            }
            me.fncDeleteStaffKoumoku();
        };

        ajax.send(me.url, me.data, 0);
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.init_control = function () {
        base_init_control();
        me.frmHendoBubetuCopy_Load();
    };

    me.frmHendoBubetuCopy_Load = function () {
        $(".FrmHendoBubetuCopy.cboYMEnd").val($(".FrmHendoBubetu.cboYM").val());
        $(".FrmHendoBubetuCopy.cboYMEnd").trigger("focus");
    };

    me.fncDeleteStaffKoumoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteStaffKoumoku";
        //-- 20150928 LI UPD S.
        // var TOUGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        // var ZENGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        var TOUGETUVal =
            $(".FrmHendoBubetuCopy.cboYMEnd").val().toString().substring(0, 4) +
            "/" +
            $(".FrmHendoBubetuCopy.cboYMEnd").val().toString().substring(4);
        var ZENGETUVal = TOUGETUVal;
        //-- 20150928 LI UPD E.
        var ITEMCDVal = $(".FrmHendoBubetuCopy.txtItemNO").val();

        var arr = {
            TOUGETU: TOUGETUVal,
            ZENGETU: ZENGETUVal,
            ITEMCD: ITEMCDVal,
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

            me.PrpFlg = true;
            me.prpKeijyoYM = $(".FrmHendoBubetuCopy.cboYMEnd").val();
            $("#cmdCopyDialogDiv").dialog("close");
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncInsertStaffKoumoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncInsertStaffKoumoku";
        //-- 20150928 LI UPD S.
        // var TOUGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        // var ZENGETUVal = $(".FrmHendoBubetuCopy.cboYMEnd").val();
        var TOUGETUVal =
            $(".FrmHendoBubetuCopy.cboYMEnd").val().toString().substring(0, 4) +
            "/" +
            $(".FrmHendoBubetuCopy.cboYMEnd").val().toString().substring(4);
        var ZENGETUVal = TOUGETUVal;
        //-- 20150928 LI UPD E.
        // var ITEMCDVal = $(".FrmHendoBubetuCopy.txtItemNO").val();

        var arr = {
            TOUGETU: TOUGETUVal,
            ZENGETU: ZENGETUVal,
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
        };
        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHendoBubetuCopy = new R4.FrmHendoBubetuCopy();
    o_R4_FrmHendoBubetuCopy.load();

    o_R4K_R4K.FrmHendoBubetu.FrmHendoBubetuCopy = o_R4_FrmHendoBubetuCopy;
    o_R4_FrmHendoBubetuCopy.FrmHendoBubetu = o_R4K_R4K.FrmHendoBubetu;
});
