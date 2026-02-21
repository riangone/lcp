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

Namespace.register("R4.FrmJibaiExcelIn");

R4.FrmJibaiExcelIn = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    var MessageBox = new gdmz.common.MessageBox();
    var ajax = new gdmz.common.ajax();
    // me.fu = new gdmz.common.file();

    // ========== 変数 start ==========

    me.id = "FrmJibaiExcelIn";
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
        id: ".FrmJibaiExcelIn.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJibaiExcelIn.cmdAct",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJibaiExcelIn.cboYM",
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

    $(".FrmJibaiExcelIn.cmdAct").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            // clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
            clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "HMReports",
                "YesNo",
                "Question"
            );
        }
    });

    $(".FrmJibaiExcelIn.cmdOpen").click(function () {
        //参照ボタンcmdOpen_Click
        $(".FrmJibaiExcelIn.txtFile").val("");
        me.fu = new gdmz.common.file();
        // $(".FrmJibaiExcelIn.txtFile").removeAttr('readonly');
        me.fu.action = me.sys_id + "/" + me.id + "/fncCheckFile";

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.fu.create());

        //20171222 YIN DEL S
        // me.fu.select_file();
        //20171222 YIN DEL E
        $("#file").change(function () {
            // for (var i = 0; i < this.files.length; i++)
            // {
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
                $(".FrmJibaiExcelIn.txtFile").trigger("focus");
                return;
            }

            if (fileType != "xls" && fileType != "xlsx") {
                MessageBox.MessageBox(
                    "使用できるファイルは.xls,.xlsxです。",
                    "HMReports",
                    "OK",
                    MessageBox.MessageBoxIcon.Warning
                );
                $(".FrmJibaiExcelIn.txtFile").trigger("focus");
                return;
            }

            $(".FrmJibaiExcelIn.txtFile").val(this.files[i].name);
            // $(".FrmJibaiExcelIn.txtFile").attr('readonly', 'readonly');

            // }
        });
        //20171222 YIN INS S
        me.fu.select_file();
        //20171222 YIN INS E
    });

    $(".FrmJibaiExcelIn.cboYM").on("blur", function () {
        //-- 20150922 Yuanjh UPD S.
        //if (clsComFnc.CheckDate2($(".FrmJibaiExcelIn.cboYM")) == false)
        //-- 20150922 Yuanjh UPD E.
        if (clsComFnc.CheckDate3($(".FrmJibaiExcelIn.cboYM")) == false) {
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmJibaiExcelIn.cboYM").val(me.cboYM);
                $(".FrmJibaiExcelIn.cboYM").trigger("focus");
                $(".FrmJibaiExcelIn.cboYM").select();
                $(".FrmJibaiExcelIn.cmdAct").button("disable");

                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmJibaiExcelIn.cmdAct").button("enable");
        }
    });

    // $(".FrmJibaiExcelIn.cboYM").bind('keydown', function(e)
    // {
    // var key = e.which;
    // var oEvent = window.event;
    // if (key == 9 || key == 13)
    // {
    // $(".FrmJibaiExcelIn.cmdAct").button('enable');
    // $(".FrmJibaiExcelIn.cmdAct").trigger("focus");
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
        //var KEIJOBIVal = $(".FrmJibaiExcelIn.cboYM").val();
        var KEIJOBIVal =
            $(".FrmJibaiExcelIn.cboYM").val().substr(0, 4) +
            "/" +
            $(".FrmJibaiExcelIn.cboYM").val().substr(4, 2);
        //-- 20150922 Yuanjh UPD E.
        var FileName = $(".FrmJibaiExcelIn.txtFile").val();

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
                $(".FrmJibaiExcelIn.txtFile").val("");
                if (result["MsgID"] == "E9999") {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                } else if (result["MsgID"] == "W9997") {
                    MessageBox.MessageBox(
                        result["data"],
                        "HMReports",
                        "OK",
                        MessageBox.MessageBoxIcon.Warning
                    );
                    return;
                } else if (result["MsgID"] == "W9999") {
                    clsComFnc.FncMsgBox("W9999", result["data"]);
                    // MessageBox.MessageBox(result['data'], "HMReports", "OK");
                    return;
                } else {
                    clsComFnc.FncMsgBox("E9999", result["data"]);
                    return;
                }
            }
            if (result["result"] == true) {
                $(".FrmJibaiExcelIn.txtFile").val("");
                $(".FrmJibaiExcelIn.cboYM").trigger("focus");
                MessageBox.MessageBox(
                    "取込処理は正常に終了しました。",
                    "HMReports",
                    "OK"
                );
                me.fileMark = 0;
                $(".FrmJibaiExcelIn.txtFile").val("");
                return;
            }
        };
        ajax.send(me.url, me.data, 0);
    };

    me.fncCheckFile = function () {
        var FileName = $(".FrmJibaiExcelIn.txtFile").val();

        if (FileName.trimEnd() == "") {
            clsComFnc.ObjFocus = $(".FrmJibaiExcelIn.txtFile");
            MessageBox.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "HMReports",
                "OK",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        me.fu.send(me.func);
        // return;
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
            //$(".FrmJibaiExcelIn.cboYM").val(tmpNowDate);
            $(".FrmJibaiExcelIn.cboYM").val(
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
                //$(".FrmJibaiExcelIn.cboYM").val(strTougetu[0] + '/' + strTougetu[1]);
                $(".FrmJibaiExcelIn.cboYM").val(strTougetu[0] + strTougetu[1]);

                //me.cboYM = strTougetu[0] + '/' + strTougetu[1];
                me.cboYM = strTougetu[0] + strTougetu[1];
                //-- 20150922 Yuanjh UPD E.
            }

            $(".FrmJibaiExcelIn.txtFile").val("");
        };
        ajax.send(me.url, me.data, 0);
    };

    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmJibaiExcelIn = new R4.FrmJibaiExcelIn();
    o_R4_FrmJibaiExcelIn.load();
});
