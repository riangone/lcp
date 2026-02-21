/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 * 履歴：
 * -----------------------------------------------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20150922	 		  #2162						   BUG								YIN
 * 20201117           bug                          年月の内容が日付ではない場合、フォーカスアウトするとFireFoxで年月がフォーカスされないです。WANGYING
 * ----------------------------------------------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmSeikyuHakko");

R4.FrmSeikyuHakko = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmSeikyuHakko";
    me.sys_id = "R4K";
    me.cboYM = "";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // me.controls.push(
    // {
    // id : ".FrmSeikyuHakko.cmdOpen",
    // type : "button",
    // handle : ""
    // });
    me.controls.push({
        id: ".FrmSeikyuHakko.Button1",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmSeikyuHakko.cmdAction",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmSeikyuHakko.cboYM",
        //20150922 yin upd S
        //type : "datepicker2",
        type: "datepicker3",
        //20150922 yin upd E
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
        me.frmLeaseUriageMeisai_Load();
    };
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    $(".FrmSeikyuHakko.cboYM").on("blur", function () {
        //20150922 yin upd S
        //if (me.clsComFnc.CheckDate2($(".FrmSeikyuHakko.cboYM")) == false)
        if (me.clsComFnc.CheckDate3($(".FrmSeikyuHakko.cboYM")) == false) {
            //20150922 yin upd E
            //20201117 wangying ins S
            window.setTimeout(function () {
                //20201117 wangying ins E
                $(".FrmSeikyuHakko.cboYM").val(me.cboYM);
                $(".FrmSeikyuHakko.cboYM").trigger("focus");
                $(".FrmSeikyuHakko.cboYM").select();
                $(".FrmSeikyuHakko.cmdAction").button("disable");
                return;
                //20201117 wangying ins S
            }, 0);
            //20201117 wangying ins E
        } else {
            $(".FrmSeikyuHakko.cmdAction").button("enable");
        }
    });

    //実行Button
    $(".FrmSeikyuHakko.cmdAction").click(function () {
        me.cmdAction_Click();
    });

    // //参照
    // $('.FrmSeikyuHakko.cmdOpen').click(function()
    // {
    // //参照ボタンcmdOpen_Click
    // me.file = new gdmz.common.file();
    //
    // $("#tmpFileUpload").html('');
    // $("#tmpFileUpload").append(me.file.create());
    // me.file.select_file();
    // $("#file").change(function()
    // {
    // var i = 0;
    // var arr = (this.files[i].name).split('.');
    // var filelong = arr.length;
    // filelong = filelong - 1;
    // var fileType = arr[filelong].toLowerCase();
    // if (this.files[i].size > 2048000)
    // {
    // me.clsComFnc.MessageBox("添付可能なファイルサイズは、最大 2000KB です。", "HMReports", "OK", me.clsComFnc.MessageBoxIcon.Warning);
    // $(".FrmSeikyuHakko.txtFile").trigger("focus");
    // return;
    // }
    //
    // if (fileType != 'xls' && fileType != 'xlsx')
    // {
    // me.clsComFnc.MessageBox("使用できるファイルは.xls,.xlsxです。", "HMReports", "OK", me.clsComFnc.MessageBoxIcon.Warning);
    // $(".FrmSeikyuHakko.txtFile").trigger("focus");
    // return;
    // }
    //
    // $(".FrmSeikyuHakko.txtFile").val(this.files[i].name);
    // });
    //
    // });

    //Excel出力
    $(".FrmSeikyuHakko.Button1").click(function () {
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.Button1_Click;
        me.clsComFnc.FncMsgBox("QY999", "上書きします。よろしいですか？");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //**********************************************************************
    //処 理 名：ﾌｫｰﾑﾛｰﾄﾞ
    //関 数 名：frmLeaseUriageMeisai_Load
    //引    数：無し
    //戻 り 値：無し
    //処理説明：初期設定
    //**********************************************************************
    me.frmLeaseUriageMeisai_Load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "frmLeaseUriageMeisai_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"].length != 0) {
                    //20150922 yin upd S
                    // $(".FrmSeikyuHakko.cboYM").val(result['data'][0]['TOUGETU'].substr(0, 7));
                    // me.cboYM = result['data'][0]['TOUGETU'].substr(0, 7);
                    $(".FrmSeikyuHakko.cboYM").val(
                        result["data"][0]["TOUGETU"]
                            .substr(0, 7)
                            .replace("/", "")
                    );
                    me.cboYM = result["data"][0]["TOUGETU"]
                        .substr(0, 7)
                        .replace("/", "");
                    //20150922 yin upd E
                    $(".FrmSeikyuHakko.txtFile").val("");
                    $(".FrmSeikyuHakko.cboYM").trigger("focus");
                } else {
                    //20240522 lujunxia upd s
                    me.clsComFnc.FncMsgBox(
                        "E9999",
                        "コントロールマスタが存在しません！"
                    );
                    //$('.FrmSeikyuHakko.cboYM').datepicker('setDate', new Date());
                    $(".FrmSeikyuHakko.cboYM").ympicker("setDate", new Date());
                    //20240522 lujunxia upd e
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, "", 1);
    };
    //**********************************************************************
    //処 理 名：実行
    //関 数 名：cmdEnd_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：印刷する
    //**********************************************************************
    me.cmdAction_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "cmdAction_Click";
        var data = {
            cboYM: $(".FrmSeikyuHakko.cboYM").val().replace("/", ""),
            cboYM1: $(".FrmSeikyuHakko.cboYM").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");

            if (result["result"] == true) {
                if (result["data"].length == 0) {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    window.open(result["pdfpath"]);
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };

    //**********************************************************************
    //処 理 名：エクセル出力
    //関 数 名：Button1_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：エクセル出力
    //**********************************************************************
    me.Button1_Click = function () {
        var url = me.sys_id + "/" + me.id + "/" + "Button1_Click";
        var data = $(".FrmSeikyuHakko.cboYM").val();
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] === "I0001") {
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                } else {
                    //20181026 YIN INS S
                    downloadExcel = 0;
                    //20181026 YIN INS E
                    window.location.href = result["data"];
                    //出力処理が正常に終了しました
                    me.clsComFnc.FncMsgBox("I0011");
                    return;
                }
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmSeikyuHakko = new R4.FrmSeikyuHakko();
    o_R4_FrmSeikyuHakko.load();
});
