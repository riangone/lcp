/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151208           #2287						   BUG                              LI
 * 20171215 		  #2807						   依頼								YIN
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmJinjiIn");

R4.FrmJinjiIn = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmJinjiIn";
    me.sys_id = "R4K";
    me.url = "";
    me.data = "";
    me.action = "";
    me.strPath = "";
    me.cboYM = "";
    me.fileMark = 0;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmJinjiIn.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinjiIn.cmdAct",
        type: "button",
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

    me.init_control = function () {
        base_init_control();
        me.frmSample_Load();
    };

    $(".FrmJinjiIn.cmdAct").click(function () {
        $(".FrmJinjiIn.lblJijCnt").val("");
        $(".FrmJinjiIn.lblMsg").html("");
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "HMReports",
                "YesNo",
                "Question"
            );
        }
    });

    $(".FrmJinjiIn.cmdOpen").click(function () {
        //参照ボタンcmdOpen_Click
        $(".FrmJinjiIn.txtJinjiName").val("");
        me.fu = new gdmz.common.file();
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.fu.accept = $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.fu.create());

        //20171215 YIN DEL S
        // me.fu.select_file();
        //20171215 YIN DEL E
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 2048000) {
                MessageBox.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJinjiIn.cmdOpen").trigger("focus");
                return;
            }

            if (fileType != "csv") {
                MessageBox.MessageBox(
                    "使用できるファイルはcsvです。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJinjiIn.cmdOpen").trigger("focus");
                return;
            }

            $(".FrmJinjiIn.txtJinjiName").val(this.files[i].name);
        });
        //20171215 YIN INS S
        me.fu.select_file();
        //20171215 YIN INS E
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.func = function () {
        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        clsComFnc.MsgBoxBtnFnc.Close = me.cmdAct_ClickNo;
        clsComFnc.MessageBox(
            "実行します。よろしいですか？",
            "HMReports",
            "YesNo",
            "Question"
        );
    };
    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/cmdAct_Click";
        var FileName = $(".FrmJinjiIn.txtJinjiName").val();
        var chkRtraiJin = $(".FrmJinjiIn.chkRtraiJin").prop("checked");
        var arrayVal = {
            FILENAME: FileName,
            chkRtraiJin: chkRtraiJin,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.fileMark = 0;
                $(".FrmJinjiIn.txtJinjiName").val("");
                $(".FrmJinjiIn.lblJijCnt").val(result["lbljijCnt"]);
                MessageBox.MessageBox(
                    "処理が正常に終了しました。",
                    "R4→（GD）（DZM）データ連携サブシステム",
                    "OK"
                );
                return;
            } else {
                if (typeof result["msgContent"] !== "undefined") {
                    if (result["msgContent"] != "") {
                        //--20151208  LI UPD S.
                        // clsComFnc.FncMsgBox("E9999", result['msgContent']);
                        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdActOK_Click;
                        clsComFnc.MessageBox(
                            result["msgContent"],
                            clsComFnc.GSYSTEM_NAME,
                            "OK",
                            "Error"
                        );
                        //--20151208  LI UPD E.
                    }
                }
                $(".FrmJinjiIn.lblMsg").html(result["lblMSG"]);
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    //--20151208  LI INS S.
    me.cmdActOK_Click = function () {
        me.fileMark = 0;
        $(".FrmJinjiIn.txtJinjiName").val("");
        $(".FrmJinjiIn.lblJijCnt").val(result["lbljijCnt"]);
        return;
    };
    //--20151208  LI INS E.

    me.cmdAct_ClickNo = function () {
        //--20151208  LI UPD S.
        // return;
        me.fileMark = 1;
        //--20151208  LI UPD E.
    };
    me.fncCheckFile = function () {
        var FileName = $(".FrmJinjiIn.txtJinjiName").val();
        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmJinjiIn.txtJinjiName");
            MessageBox.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "HMReports",
                "OK",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        me.fu.send(me.func);
        return;
    };
    me.FrmJinjiIn_load = function () {};
    base_load = me.load;
    me.load = function () {
        base_load();
        me.FrmJinjiIn_load();
    };
    me.frmSample_Load = function () {};
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmJinjiIn = new R4.FrmJinjiIn();
    o_R4_FrmJinjiIn.load();
});
