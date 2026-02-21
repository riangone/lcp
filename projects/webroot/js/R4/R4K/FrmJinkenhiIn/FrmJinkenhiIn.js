/**
 * 説明：
 *
 *
 * @author FCS
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922           #2162                        BUG                              Yuanjh
 * 20171222           #2807                        依頼                              YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmJinkenhiIn");

R4.FrmJinkenhiIn = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();

    // ========== 変数 start ==========

    me.id = "FrmJinkenhiIn";
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
        id: ".FrmJinkenhiIn.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiIn.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiIn.cboYM",
        //-- 20150922 Yuanjh UPD S.
        //type : "datepicker2",
        type: "datepicker3",
        //-- 20150922 Yuanjh UPD E.
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

    $(".FrmJinkenhiIn.cmdAct").click(function () {
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

    $(".FrmJinkenhiIn.cmdOpen").click(function () {
        //参照ボタンcmdOpen_Click
        me.fileMark = 0;
        $(".FrmJinkenhiIn.txtFile").val("");
        me.fu = new gdmz.common.file();
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.fu.create());

        //20171222 YIN DEL S
        // me.fu.select_file();
        //20171222 YIN DEL E
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
                $(".FrmJinkenhiIn.txtFile").trigger("focus");
                return;
            }

            if (fileType != "xls" && fileType != "xlsx") {
                MessageBox.MessageBox(
                    "使用できるファイルは.xls,.xlsxです。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJinkenhiIn.txtFile").trigger("focus");
                return;
            }

            $(".FrmJinkenhiIn.txtFile").val(this.files[i].name);
        });
        //20171222 YIN INS S
        me.fu.select_file();
        //20171222 YIN INS E
    });

    $(".FrmJinkenhiIn.cboYM").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmJinkenhiIn.cboYM")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (clsComFnc.CheckDate3($(".FrmJinkenhiIn.cboYM")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmJinkenhiIn.cboYM").val(me.cboYM);
                $(".FrmJinkenhiIn.cboYM").trigger("focus");
                $(".FrmJinkenhiIn.cboYM").select();
                $(".FrmJinkenhiIn.cmdAct").button("disable");

                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmJinkenhiIn.cmdAct").button("enable");
        }
    });

    // $(".FrmJinkenhiIn.cboYM").bind('keydown', function(e)
    // {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 9 || key == 13)
    // {
    // $(".FrmJinkenhiIn.cmdAct").button('enable');
    // $(".FrmJinkenhiIn.cmdAct").trigger("focus");
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

        //-- 20150922 Yuanjh UPD S.
        //var KEIJOBIVal = $(".FrmJinkenhiIn.cboYM").val();
        var KEIJOBIVal =
            $(".FrmJinkenhiIn.cboYM").val().substr(0, 4) +
            "/" +
            $(".FrmJinkenhiIn.cboYM").val().substr(4, 2);
        //-- 20150922 Yuanjh UPD E.

        var FileName = $(".FrmJinkenhiIn.txtFile").val();

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
                $(".FrmJinkenhiIn.txtFile").val("");
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
            }
            if (result["result"] == true) {
                $(".FrmJinkenhiIn.txtFile").val("");
                $(".FrmJinkenhiIn.cboYM").trigger("focus");
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "HMReports",
                    "OK"
                );
                me.fileMark = 0;
                $(".FrmJinkenhiIn.txtFile").val("");
                if (result["data"].length > 0) {
                    window.open(result["path"]);
                }
                return;
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncCheckFile = function () {
        var FileName = $(".FrmJinkenhiIn.txtFile").val();

        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmJinkenhiIn.txtFile");
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
            // var tmpNowDate =
            //     myDate.getFullYear().toString() + "/" + tmpMonth.toString();
            //-- 20150922 Yuanjh UPD S.
            //$(".FrmJinkenhiIn.cboYM").val(tmpNowDate);
            $(".FrmJinkenhiIn.cboYM").val(
                myDate.getFullYear().toString() + tmpMonth.toString()
            );
            //-- 20150922 Yuanjh UPD E.

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
                //-- 20150922 Yuanjh UPD S.
                //$(".FrmJinkenhiIn.cboYM").val(strTougetu[0] + '/' + strTougetu[1]);
                $(".FrmJinkenhiIn.cboYM").val(strTougetu[0] + strTougetu[1]);
                //me.cboYM = strTougetu[0] + '/' + strTougetu[1];
                me.cboYM = strTougetu[0] + strTougetu[1];
                //-- 20150922 Yuanjh UPD S.
            }

            $(".FrmJinkenhiIn.txtFile").val("");
        };
        ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmJinkenhiIn = new R4.FrmJinkenhiIn();
    o_R4_FrmJinkenhiIn.load();
});
