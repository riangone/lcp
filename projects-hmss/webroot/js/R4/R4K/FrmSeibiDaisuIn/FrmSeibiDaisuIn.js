/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150923 		  #2162						   BUG								YIN
 * 20171215 		  #2807						   依頼								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSeibiDaisuIn");

R4.FrmSeibiDaisuIn = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmSeibiDaisuIn";
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
        id: ".FrmSeibiDaisuIn.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSeibiDaisuIn.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSeibiDaisuIn.cboYM",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
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

    $(".FrmSeibiDaisuIn.cmdAct").click(function () {
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

    $(".FrmSeibiDaisuIn.cmdOpen").click(function () {
        me.fileMark = 0;
        //参照ボタンcmdOpen_Click
        $(".FrmSeibiDaisuIn.txtFile").val("");
        me.fu = new gdmz.common.file();
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";

        $("#tmpFileUpload").html("");
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
                $(".FrmSeibiDaisuIn.txtFile").trigger("focus");
                return;
            }

            if (fileType != "xls" && fileType != "xlsx") {
                MessageBox.MessageBox(
                    "使用できるファイルは.xls,.xlsxです。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmSeibiDaisuIn.txtFile").trigger("focus");
                return;
            }

            $(".FrmSeibiDaisuIn.txtFile").val(this.files[i].name);
        });
        //20171215 YIN INS S
        me.fu.select_file();
        //20171215 YIN INS E
    });

    $(".FrmSeibiDaisuIn.cboYM").on("blur", function () {
        //20150923 yin upd S
        // if (clsComFnc.CheckDate2($(".FrmSeibiDaisuIn.cboYM")) == false)
        if (clsComFnc.CheckDate3($(".FrmSeibiDaisuIn.cboYM")) == false) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSeibiDaisuIn.cboYM").val(me.cboYM);
                $(".FrmSeibiDaisuIn.cboYM").trigger("focus");
                $(".FrmSeibiDaisuIn.cboYM").select();
                $(".FrmSeibiDaisuIn.cmdAct").button("disable");

                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSeibiDaisuIn.cmdAct").button("enable");
        }
    });

    // $(".FrmSeibiDaisuIn.cboYM").bind('keydown', function(e)
    // {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 9 || key == 13)
    // {
    // $(".FrmSeibiDaisuIn.cmdAct").button('enable');
    // $(".FrmSeibiDaisuIn.cmdAct").focus();
    // }
    // });
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

    me.cmdAct_ClickNo = function () {
        me.fileMark = 1;
    };

    me.cmdAct_Click = function () {
        me.url = me.sys_id + "/" + me.id + "/cmdAct_Click";

        var KEIJOBIVal = $(".FrmSeibiDaisuIn.cboYM").val();

        var FileName = $(".FrmSeibiDaisuIn.txtFile").val();

        var arrayVal = {
            KEIJOBI: KEIJOBIVal,
            FILENAME: FileName,
        };

        me.data = {
            request: arrayVal,
        };

        ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                $(".FrmSeibiDaisuIn.txtFile").val("");
                if (result["MsgID"] == "E9999") {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
                if (result["MsgID"] == "W9997") {
                    MessageBox.MessageBox(
                        result["data"],
                        "HMReports",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                    return;
                }
                if (result["MsgID"] == "W9999") {
                    clsComFnc.FncMsgBox("W9999", result["data"]);
                    return;
                }
                if (result["MsgID"] == "I0001") {
                    clsComFnc.FncMsgBox("I0001", result["data"]);
                    return;
                }
            }
            if (result["result"] == true) {
                $(".FrmSeibiDaisuIn.txtFile").val("");
                $(".FrmSeibiDaisuIn.cboYM").trigger("focus");
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "HMReports",
                    "OK"
                );
                me.fileMark = 0;
                $(".FrmSeibiDaisuIn.txtFile").val("");
                return;
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncCheckFile = function () {
        var FileName = $(".FrmSeibiDaisuIn.txtFile").val();

        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmSeibiDaisuIn.txtFile");
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
            var myDate = new Date();
            var tmpMonth = (myDate.getMonth() + 1).toString();
            if (tmpMonth.length < 2) {
                tmpMonth = "0" + tmpMonth.toString();
            }
            var tmpNowDate =
                myDate.getFullYear().toString() + tmpMonth.toString();
            $(".FrmSeibiDaisuIn.cboYM").val(tmpNowDate);
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["row"] == 0) {
                clsComFnc.FncMsgBox(
                    "E9999",
                    "コントロールマスタが存在しません！"
                );
            } else {
                var strTougetu = clsComFnc
                    .FncNv(result["data"][0]["TOUGETU"])
                    .toString();
                strTougetu = strTougetu.split("/");
                $(".FrmSeibiDaisuIn.cboYM").val(strTougetu[0] + strTougetu[1]);
                me.cboYM = strTougetu[0] + strTougetu[1];
            }

            $(".FrmSeibiDaisuIn.txtFile").val("");
        };
        ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSeibiDaisuIn = new R4.FrmSeibiDaisuIn();
    o_R4_FrmSeibiDaisuIn.load();
});
