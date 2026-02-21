/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20151014 		  #2190						   BUG								LI
 * 20151021 		  #2191						   BUG								LI
 * 20151026 		  #2237						   BUG								LI
 * 20171215 		  #2807						   依頼								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */
Namespace.register("R4.FrmSCUrkIn");

R4.FrmSCUrkIn = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();
    // me.fu = new gdmz.common.file();

    // ========== 変数 start ==========

    me.id = "FrmSCUrkIn";
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
        id: ".FrmSCUrkIn.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSCUrkIn.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSCUrkIn.cboYM",
        type: "datepicker3",
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

    $(".FrmSCUrkIn.cmdAct").click(function () {
        $(".FrmSCUrkIn.lblJijCnt").val("");
        $(".FrmSCUrkIn.lblMsg").html("");
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

    $(".FrmSCUrkIn.cmdOpen").click(function () {
        me.fileMark = 0;
        //参照ボタンcmdOpen_Click
        $(".FrmSCUrkIn.txtFileName").val("");
        me.fu = new gdmz.common.file();

        me.fu.accept = "text/plain";
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        //---20151021 li INS S.
        $("#tmpFileUpload").html("");
        //---20151021 li INS E.
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
            if (fileType != "txt") {
                MessageBox.MessageBox(
                    "使用できるファイルはtxtです。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmSCUrkIn.txtFileName").focus();
                return;
            }
            if (this.files[i].size > 2048000) {
                MessageBox.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmSCUrkIn.txtFileName").focus();
                return;
            }
            $(".FrmSCUrkIn.txtFileName").val(this.files[i].name);
        });
        //20171215 YIN INS S
        me.fu.select_file();
        //20171215 YIN INS E
    });

    $(".FrmSCUrkIn.cboYM").blur(function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmKeikenNensuIN.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmSCUrkIn.cboYM")) == false) {
            //-- 20150922 Yuanjh UPD E.
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSCUrkIn.cboYM").val(me.cboYM);
                $(".FrmSCUrkIn.cboYM").focus();
                $(".FrmSCUrkIn.cboYM").select();
                $(".FrmSCUrkIn.cmdAct").button("disable");

                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSCUrkIn.cmdAct").button("enable");
        }
    });

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.func = function () {
        me.fileMark = 1;
        clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        clsComFnc.MessageBox(
            "実行します。よろしいですか？",
            "HMReports",
            "YesNo",
            "Question"
        );
    };

    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };

    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/cmdAct_Click";
        var CBOYMVal =
            $(".FrmSCUrkIn.cboYM").val().substr(0, 4) +
            "/" +
            $(".FrmSCUrkIn.cboYM").val().substr(4, 2);
        var FileName = $(".FrmSCUrkIn.txtFileName").val();
        var RadioChk = "1";
        if ($(".FrmSCUrkIn.radSinsya").prop("checked") == true) {
            //---20151026 li UPD S.
            //radioChk = "1";
            RadioChk = "1";
            //---20151026 li UPD E.
        }
        if ($(".FrmSCUrkIn.radChukosya").prop("checked") == true) {
            //---20151026 li UPD S.
            //radioChk = "2";
            RadioChk = "2";
            //---20151026 li UPD E.
        }

        var arrayVal = {
            cboYM: CBOYMVal,
            FILENAME: FileName,
            radioChk: RadioChk,
        };
        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            me.fileMark = 0;

            if (result["result"] == false) {
                $(".FrmSCUrkIn.txtFileName").val("");
                if (result["MsgID"] == "E9999") {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (typeof result["msgContent"] !== "undefined") {
                    if (result["msgContent"] != "") {
                        clsComFnc.MessageBox(
                            result["msgContent"],
                            clsComFnc.GSYSTEM_NAME,
                            "OK",
                            "Error"
                        );
                        $(".FrmSCUrkIn.lblMsg").html(result["lblMSG"]);
                    }
                }
            }

            if (result["result"] == true) {
                $(".FrmSCUrkIn.txtFileName").val("");
                $(".FrmSCUrkIn.cboYM").focus();
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "HMReports",
                    "OK"
                );
                $(".FrmSCUrkIn.lblJijCnt").val(result["lbljijCnt"]);
                return;
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncCheckFile = function () {
        var FileName = $(".FrmSCUrkIn.txtFileName").val();

        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmSCUrkIn.txtFileName");
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

    me.frmSample_Load = function () {
        //年月コントロールマスタ存在ﾁｪｯｸ
        me.url = me.sys_id + "/" + me.id + "/frmSample_Load";

        ajax.receive = function (result) {
            $(".FrmSCUrkIn.radSinsya").prop("checked", "checked");

            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["msgContent"]);
                return;
            }
            if (result["row"] == 0) {
                //20151014 LI DEL S
                //$(".FrmSCUrkIn.cboYM").val('2006/04');
                //20151014 LI DEL E
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
            } else {
                var strTougetu = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();
                strTougetu = strTougetu.split("/");
                $(".FrmSCUrkIn.cboYM").val(strTougetu[0] + strTougetu[1]);
                me.cboYM = strTougetu[0] + strTougetu[1];
            }
            $(".FrmSCUrkIn.lbljijCnt").val("");
        };
        ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSCUrkIn = new R4.FrmSCUrkIn();
    o_R4_FrmSCUrkIn.load();
});
