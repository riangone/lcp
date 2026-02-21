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

Namespace.register("R4.FrmHendoKobetuCopy");

R4.FrmHendoKobetuCopy = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmHendoKobetuCopy";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.cboYM = "";
    me.FrmHendoKobetu = null;
    me.PrpFlg = false;
    me.prpKeijyoYM = "";
    me.prpItemNO = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmHendoKobetuCopy.cmdAction",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetuCopy.cmdEnd",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmHendoKobetuCopy.cboYMEnd",
        //-- 20150928 LI UPD S.
        // type : "datepicker2",
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
    $(".FrmHendoKobetuCopy.cboYMEnd").on("blur", function () {
        //-- 20150928 LI UPD S.
        // if (clsComFnc.CheckDate2($(".FrmHendoKobetuCopy.cboYMEnd")) == false)
        if (clsComFnc.CheckDate3($(".FrmHendoKobetuCopy.cboYMEnd")) == false) {
            //-- 20150928 LI UPD E.
            $(".FrmHendoKobetuCopy.cboYMEnd").val(
                $(".FrmHendoKobetu.cboYM").val()
            );
            $(".FrmHendoKobetuCopy.cboYMEnd").trigger("focus");
            $(".FrmHendoKobetuCopy.cboYMEnd").select();
            $(".FrmHendoKobetu.cmdAction").button("disable");
        } else {
            $(".FrmHendoKobetuCopy.cmdAction").button("enable");
        }
    });

    $(".FrmHendoKobetuCopy.cmdEnd").click(function () {
        $("#cmdCopyDialogDiv").dialog("close");
    });

    $(".FrmHendoKobetuCopy.cmdAction").click(function () {
        me.url = me.sys_id + "/" + me.id + "/fncExistCheckSel";
        //-- 20150928 LI UPD S.
        // var TOUGETUVal = $(".FrmHendoKobetuCopy.cboYMEnd").val();
        // var ZENGETUVal = $(".FrmHendoKobetuCopy.cboYMEnd").val();
        var TOUGETUVal =
            $(".FrmHendoKobetuCopy.cboYMEnd").val().substring(0, 4) +
            "/" +
            $(".FrmHendoKobetuCopy.cboYMEnd").val().substring(4);
        var ZENGETUVal = TOUGETUVal;
        //-- 20150928 LI UPD E.
        var ITEMCDVal = $(".FrmHendoKobetuCopy.txtItemNO").val();

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

    $(".FrmHendoKobetuCopy.cboItemNO").change(function () {
        $(".FrmHendoKobetuCopy.txtItemNO").val(
            $(".FrmHendoKobetuCopy.cboItemNO").val()
        );
        $(".FrmHendoKobetuCopy.lblItemNM").val(
            $(".FrmHendoKobetuCopy.cboItemNO option:selected").text()
        );
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
        $(".FrmHendoKobetuCopy.cboYMEnd").val($(".FrmHendoKobetu.cboYM").val());
        me.subComboSet2();
        $(".FrmHendoKobetuCopy.cboYMEnd").trigger("focus");
    };

    me.subComboSet2 = function () {
        me.url = me.sys_id + "/" + me.id + "/subComboSet2";
        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }

            if (result["row"] > 0) {
                for (key in result["data"]) {
                    $("<option></option>")
                        .val(result["data"][key]["MEISYOU_CD"])
                        .text(result["data"][key]["MEISYOU"])
                        .appendTo(".FrmHendoKobetuCopy.cboItemNO");
                }
                $(".FrmHendoKobetuCopy.txtItemNO").val(
                    $(".FrmHendoKobetuCopy.cboItemNO").val()
                );
            } else {
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".FrmHendoKobetuCopy.cboItemNO");
            }
            var prpItemNO = $(".FrmHendoKobetu.txtItemNO").val().trimEnd();
            $(".FrmHendoKobetuCopy.txtItemNO").val(prpItemNO);
            var tmpId =
                ".FrmHendoKobetuCopy.cboItemNO option[value='" +
                prpItemNO +
                "']";
            $(tmpId).prop("selected", true);
            $(".FrmHendoKobetuCopy.lblItemNM").val(
                $(".FrmHendoKobetuCopy.cboItemNO option:selected").text()
            );
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncDeleteStaffKoumoku = function () {
        me.url = me.sys_id + "/" + me.id + "/fncDeleteStaffKoumoku";
        //-- 20150928 LI UPD S.
        // var TOUGETUVal = $(".FrmHendoKobetuCopy.cboYMEnd").val();
        // var ZENGETUVal = $(".FrmHendoKobetuCopy.cboYMEnd").val();
        var TOUGETUVal =
            $(".FrmHendoKobetuCopy.cboYMEnd").val().substring(0, 4) +
            "/" +
            $(".FrmHendoKobetuCopy.cboYMEnd").val().substring(4);
        var ZENGETUVal = TOUGETUVal;
        //-- 20150928 LI UPD E.
        var ITEMCDVal = $(".FrmHendoKobetuCopy.txtItemNO").val();
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
            me.prpKeijyoYM = $(".FrmHendoKobetuCopy.cboYMEnd").val();
            me.prpItemNO = $(".FrmHendoKobetuCopy.cboItemNO").val();
            $("#cmdCopyDialogDiv").dialog("close");
        };
        ajax.send(me.url, me.data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmHendoKobetuCopy = new R4.FrmHendoKobetuCopy();
    o_R4_FrmHendoKobetuCopy.load();

    o_R4K_R4K.FrmHendoKobetu.FrmHendoKobetuCopy = o_R4_FrmHendoKobetuCopy;
    o_R4_FrmHendoKobetuCopy.FrmHendoKobetu = o_R4K_R4K.FrmHendoKobetu;
});
