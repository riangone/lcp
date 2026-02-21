/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug               内容                           担当
 * YYYYMMDD           #ID                       XXXXXX                         FCSDL
 * 20150914           ---                       BUG#2111                       Yuanjh
 * 20150918           ---                       BUG#2111                       Yuanjh
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("R4.FrmGenkaIn");

R4.FrmGenkaIn = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmGenkaIn";
    me.sys_id = "R4K";

    me.strGenkaPath = "";
    me.strCsvOutPath = "";
    me.strErrLogPath = "";
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".FrmGenkaIn.cmdAct.Enter.Tab",
        type: "button",
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
    $(".FrmGenkaIn.cmdAct.Enter.Tab").click(function () {
        //取込ﾌｧｲﾙが未入力の場合はｴﾗｰ
        var txtFile = $(".FrmGenkaIn.txtFile").val().trimEnd();
        if (txtFile == "" || txtFile == null) {
            me.clsComFnc.MessageBox(
                "取込ﾌｧｲﾙを指定してください。",
                "R4→（GD）（DZM）データ連携サブシステム",
                me.clsComFnc.MessageBoxButtons.OK,
                me.clsComFnc.MessageBoxIcon.Warning
            );
            return;
        }
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.MessageBox(
                    result["data"],
                    "R4→（GD）（DZM）データ連携サブシステム",
                    me.clsComFnc.MessageBoxButtons.OK,
                    me.clsComFnc.MessageBoxIcon.Warning
                );
                return;
            } else if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Yes = me.fncMsgYes;
                me.clsComFnc.MsgBoxBtnFnc.No = me.fncMsgNo;
                me.clsComFnc.MessageBox(
                    "実行します。よろしいですか？",
                    "R4→（GD）（DZM）データ連携サブシステム",
                    me.clsComFnc.MessageBoxButtons.YesNo,
                    me.clsComFnc.MessageBoxIcon.Question
                );
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "fncCheckFile";
        var data = me.strGenkaPath;
        me.ajax.send(url, data, 0);
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    me.frmSample_Load = function () {
        //画面初期化
        $(".FrmGenkaIn.txtFile").val("");

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                me.clsComFnc.FncMsgBox("E9999", result["data"]);
                return;
            }
            if (result["result"] == true) {
                //console.log(result['data']);
                //LOG出力ﾊﾟｽを取得する
                me.strGenkaPath = result["data"]["strGenkaPath"];
                //CSV出力先のファイル設定
                me.strCsvOutPath = result["data"]["strCsvOutPath"];
                //ErrLOG出力ﾊﾟｽを取得する
                me.strErrLogPath = result["data"]["strErrLogPath"];

                //--20150914  Yuanjh  UPD S.
                //$('.FrmGenkaIn.txtFile').val("\\\\" + "192.168.2.166" + "\\" + "temp" + "\\" + "GenkaMst" + "\\" + "GenkaMst.csv");
                $(".FrmGenkaIn.txtFile").val("R:\\GenkaMst\\GenkaMst.csv");
                //--20150914  Yuanjh  UPD E.
            }
        };
        var url = me.sys_id + "/" + me.id + "/" + "frmSample_Load";
        me.ajax.send(url, "", 0);
    };
    me.fncMsgYes = function () {
        //获得 单选选按钮name集合
        var radios = document.getElementsByName("rdoDEL");
        var rdoDEL = 0;
        //判断那个单选按钮为选中状态
        if (radios[0].checked) {
            rdoDEL = 1;
        }
        if (radios[1].checked) {
            rdoDEL = 2;
        }

        var data = {
            txtFile: me.strGenkaPath,
            strCsvOutPath: me.strCsvOutPath,
            strErrLogPath: me.strErrLogPath,
            radiosel: rdoDEL,
        };
        var url = me.sys_id + "/" + me.id + "/" + "cmdAct_Click";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == false) {
                //20150918 Yuanjh UPD S.
                /*				
                if (result['data'] == "対象ﾌｧｲﾙが存在していません。" || result['data'] == "CSV取込み中にエラーが発生しました。")
                {
                    me.showMsgBox();
                }
                */
                if (result["data"] == "対象ﾌｧｲﾙが存在していません。") {
                    me.showMsgBox();
                } else if (
                    result["data"] == "CSV取込み中にエラーが発生しました。"
                ) {
                    $(".FrmGenkaIn.lblKensu").html("");
                    $(".FrmGenkaIn.lblMSG2").html("");
                    $(".FrmGenkaIn.lblMSG2").html("取込件数：");
                    $(".FrmGenkaIn.lblKensu").html(result["cnt"]);
                    me.showMsgBox();
                }
                //20150918 Yuanjh UPD E.
                else if (result["data"] == "対象ﾌｧｲﾙが存在していません。") {
                    me.showMsgBox();
                } else if (
                    result["data"] ==
                    "Length of argument 'String' must be greater than zero."
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.showMsgBox;
                    me.clsComFnc.MessageBox(
                        result["data"],
                        "R4→（GD）（DZM）データ連携サブシステム",
                        me.clsComFnc.MessageBoxButtons.OK,
                        me.clsComFnc.MessageBoxIcon.Err
                    );
                } else if (
                    result["data"] ==
                    "Index was outside the bounds of the array."
                ) {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.showMsgBox;
                    me.clsComFnc.MessageBox(
                        result["data"],
                        "R4→（GD）（DZM）データ連携サブシステム",
                        me.clsComFnc.MessageBoxButtons.OK,
                        me.clsComFnc.MessageBoxIcon.Err
                    );
                } else {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.showMsgBox;
                    me.clsComFnc.FncMsgBox("E9999", result["data"]);
                }
                return;
            }
            if (result["result"] == true) {
                $(".FrmGenkaIn.lblKensu").html("");
                $(".FrmGenkaIn.lblMSG2").html("");
                $(".FrmGenkaIn.lblMSG2").html("取込件数：");
                $(".FrmGenkaIn.lblKensu").html(result["data"]);
                me.clsComFnc.MessageBox(
                    "取込処理は正常に終了しました。",
                    "R4→（GD）（DZM）データ連携サブシステム",
                    me.clsComFnc.MessageBoxButtons.OK,
                    ""
                );
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.fncMsgNo = function () {
        me.clsComFnc.MessageBox(
            "取込処理を中断しました。",
            "R4→（GD）（DZM）データ連携サブシステム",
            me.clsComFnc.MessageBoxButtons.OK,
            ""
        );
        return;
    };
    me.showMsgBox = function () {
        me.clsComFnc.MessageBox(
            "取込処理はエラー終了しました。ログファイルを確認してください。",
            "R4→（GD）（DZM）データ連携サブシステム",
            me.clsComFnc.MessageBoxButtons.OK,
            ""
        );
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_R4_FrmGenkaIn = new R4.FrmGenkaIn();
    o_R4_FrmGenkaIn.load();
});
