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
 * 20201020 		  MAPのデータ取込追加			   依頼								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmShikakariTorikomi");

R4.FrmShikakariTorikomi = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmShikakariTorikomi";
    me.sys_id = "R4K";
    me.cboYM = "";
    me.fileMark = 0;
    me.strExt = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmShikakariTorikomi.cmdOpen",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmShikakariTorikomi.cmdAct",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmShikakariTorikomi.cboYM",
        //20150923 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150923 yin upd E
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.frmSample_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmShikakariTorikomi.cboYM").on("blur", function () {
        //20150923 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmShikakariTorikomi.cboYM")) == false)
        if (
            me.clsComFnc.CheckDate3($(".FrmShikakariTorikomi.cboYM")) == false
        ) {
            //20150923 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmShikakariTorikomi.cboYM").val(me.cboYM);
                $(".FrmShikakariTorikomi.cboYM").trigger("focus");
                $(".FrmShikakariTorikomi.cboYM").select();
                $(".FrmShikakariTorikomi.cmdAct").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmShikakariTorikomi.cmdAct").button("enable");
        }
    });

    //[参照]ﾎﾞﾀﾝ
    $(".FrmShikakariTorikomi.cmdOpen").click(function () {
        me.fileMark = 0;
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        //20201020 YIN DEL S
        // me.file.accept = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/plain";
        //20201020 YIN DEL E
        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
        //20171215 YIN DEL S
        // me.file.select_file();
        //20171215 YIN DEL E
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 2048000) {
                me.clsComFnc.MessageBox(
                    "添付可能なファイルサイズは、最大 2000KB です。",
                    "HMReports",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            }
            //20201020 YIN UPD S
            //if (fileType != 'xls' && fileType != 'xlsx' && fileType != 'txt')
            if (fileType != "xls" && fileType != "xlsx") {
                //20201020 YIN UPD E
                //20201020 YIN UPD S
                //me.clsComFnc.MessageBox("使用できるファイルは.xls,.xlsx,.txtです。", "HMReports", "OK", me.clsComFnc.MessageBoxIcon.Warning);
                me.clsComFnc.MessageBox(
                    "使用できるファイルは.xls,.xlsxです。",
                    "HMReports",
                    "OK",
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                //20201020 YIN UPD E
                return;
            }

            $(".FrmShikakariTorikomi.txtFile").val(this.files[i].name);
        });
        //20171215 YIN INS S
        me.file.select_file();
        //20171215 YIN INS E
    });

    //********************************************************************
    //   [実行]ﾎﾞﾀﾝ
    //********************************************************************
    $(".FrmShikakariTorikomi.cmdAct").click(function () {
        if (me.fileMark == 0) {
            me.fncCheckFile();
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
            me.clsComFnc.MessageBox(
                "実行します。よろしいですか？",
                "HMReports",
                "YesNo",
                "Question"
            );
        }
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmSample_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmSample_Load = function () {
        //画面項目ｸﾘｱ
        $(".FrmShikakariTorikomi.txtFile").val("");
        $(".FrmShikakariTorikomi.lblStartTime").html("");
        $(".FrmShikakariTorikomi.lblEndTime").html("");
        var url = me.sys_id + "/" + me.id + "/" + "frmSample_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            $(".FrmShikakariTorikomi.cboYM").trigger("focus");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150923 yin upd S
                    // $('.FrmShikakariTorikomi.cboYM').val(me.clsComFnc.FncNv(result['data'][0]['TOUGETU']).substr(0, 7));
                    // me.cboYM = me.clsComFnc.FncNv(result['data'][0]['TOUGETU']).substr(0, 7);
                    $(".FrmShikakariTorikomi.cboYM").val(
                        me.clsComFnc
                            .FncNv(result["data"][0]["TOUGETU"])
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = me.clsComFnc
                        .FncNv(result["data"][0]["TOUGETU"])
                        .substr(0, 7)
                        .replace("/", "");
                    //20150923 yin upd S
                    //20201020 YIN INS S
                    $(".FrmShikakariTorikomi.radMap").prop("checked", true);
                    //20201020 YIN INS E
                } else {
                    $(".FrmShikakariTorikomi.cboYM").ympicker(
                        "setDate",
                        new Date()
                    );
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };

    me.func = function () {
        me.fileMark = 1;
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.cmdAct_Click;
        me.clsComFnc.MsgBoxBtnFnc.No = me.cmdAct_ClickNo;
        me.clsComFnc.MessageBox(
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
        var myDate = new Date();
        //set this label now.(HH24:MI:SS)
        $(".FrmShikakariTorikomi.lblStartTime").html(
            myDate.toLocaleTimeString()
        );
        //add 3 minutes.
        myDate.setMinutes(myDate.getMinutes() + 3);
        //set this label now+3minutes.(HH24:MI:SS)
        $(".FrmShikakariTorikomi.lblEndTime").html(myDate.toLocaleTimeString());

        //20201020 YIN INS S
        var readFomart = "buhan";
        if ($(".FrmShikakariTorikomi.radMap").prop("checked")) {
            readFomart = "map";
        }
        //20201020 YIN INS E

        var url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";
        var data = {
            //20201020 YIN INS S
            readFomart: readFomart,
            //20201020 YIN INS E
            strExt: me.strExt,
            cboYM: $(".FrmShikakariTorikomi.cboYM").val(),
            txtFile: $(".FrmShikakariTorikomi.txtFile").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            //クリア処理
            $(".FrmShikakariTorikomi.txtFile").val("");
            $(".FrmShikakariTorikomi.cboYM").val(me.cboYM);
            $(".FrmShikakariTorikomi.cboYM").trigger("focus");
            $(".FrmShikakariTorikomi.lblStartTime").html("");
            $(".FrmShikakariTorikomi.lblEndTime").html("");
            me.fileMark = 0;
            if (result["result"] == true) {
                me.clsComFnc.MessageBox(
                    "取込処理は正常に終了しました。",
                    me.clsComFnc.GSYSTEM_NAME,
                    "OK",
                    ""
                );
            } else {
                if (result["data"] != "") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.ShowMessageBox;
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                } else {
                    me.ShowMessageBox();
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.ShowMessageBox = function () {
        me.clsComFnc.MessageBox(
            "取込処理はエラー終了しました。ログファイルを確認してください。",
            me.clsComFnc.GSYSTEM_NAME,
            "OK",
            ""
        );
    };
    //********************************************************************
    //処理概要：ﾌｧｲﾙのﾁｪｯｸ処理
    //引　　数：なし
    //戻 り 値：
    //********************************************************************
    me.fncCheckFile = function () {
        var intHitNum = 0;
        var FileName = $(".FrmShikakariTorikomi.txtFile").val();
        //取込ﾌｧｲﾙが未入力の場合はｴﾗｰ
        if (FileName.trimEnd() == "") {
            $(".FrmShikakariTorikomi.txtFile").trigger("focus");
            me.clsComFnc.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "HMReports",
                "OK",
                me.clsComFnc.MessageBoxIcon.Warning
            );
            return;
        }
        //最後に出現する"\"の位置をintHitNumに代入
        intHitNum = FileName.lastIndexOf(".");
        if (intHitNum == -1) {
            $(".FrmShikakariTorikomi.txtFile").trigger("focus");
            me.clsComFnc.MessageBox(
                "拡張子を入力して下さい！",
                "HMReports",
                "OK",
                me.clsComFnc.MessageBoxIcon.Warning
            );
            return;
        }
        //フォルダのパスを求める
        var strExt = FileName.substr(intHitNum + 1, 3).toUpperCase();
        me.strExt = strExt;
        if (strExt != "TXT" && strExt != "XLS") {
            $(".FrmShikakariTorikomi.txtFile").trigger("focus");
            me.clsComFnc.MessageBox(
                "指定された拡張子のデータを取り込むことは出来ません。",
                "HMReports",
                "OK",
                me.clsComFnc.MessageBoxIcon.Warning
            );
            return;
        }
        me.file.send(me.func);

        return;
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmShikakariTorikomi = new R4.FrmShikakariTorikomi();
    o_R4_FrmShikakariTorikomi.load();
});
