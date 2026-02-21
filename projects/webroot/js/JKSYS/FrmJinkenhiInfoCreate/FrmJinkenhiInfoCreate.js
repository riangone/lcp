/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20240307   202402_人事給与システム_人件費データexce入出力機能追加l                caina
 * * --------------------------------------------------------------------------------------------
 */

Namespace.register("JKSYS.FrmJinkenhiInfoCreate");

JKSYS.FrmJinkenhiInfoCreate = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "FrmJinkenhiInfoCreate";
    me.sys_id = "JKSYS";
    me.clsComFnc.GSYSTEM_NAME = "人事給与システム";
    //20240307 caina ins s
    me.filename = "";
    //20240307 caina ins e
    // ========== 変数 end ==========
    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmJinkenhiInfoCreate.cmdExecute",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiInfoCreate.dtpYM",
        type: "datepicker3",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiInfoCreate.cmdXlsxOut",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".FrmJinkenhiInfoCreate.cmdXlsxIn",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.clsComFnc.Shif_TabKeyDown();

    //Tabキーのバインド
    me.clsComFnc.TabKeyDown();

    //Enterキーのバインド
    me.clsComFnc.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //年月blur
    $(".FrmJinkenhiInfoCreate.dtpYM").blur(function (e) {
        if (
            me.clsComFnc.CheckDate3($(".FrmJinkenhiInfoCreate.dtpYM")) == false
        ) {
            $(".FrmJinkenhiInfoCreate.dtpYM").val(me.dtpYM);

            if (document.documentMode) {
                //IE11
                if (
                    $(document.activeElement).is("." + me.id) ||
                    $(document.activeElement).is(".JKSYS-layout-center")
                ) {
                    $(".FrmJinkenhiInfoCreate.dtpYM").trigger("focus");
                    $(".FrmJinkenhiInfoCreate.dtpYM").select();
                }
            } else {
                if (!e.relatedTarget || $(e.relatedTarget).is("." + me.id)) {
                    //Firefox
                    window.setTimeout(function () {
                        $(".FrmJinkenhiInfoCreate.dtpYM").trigger("focus");
                        $(".FrmJinkenhiInfoCreate.dtpYM").select();
                    }, 0);
                }
            }

            $(".FrmJinkenhiInfoCreate.cmdExecute").button("disable");
        } else {
            $(".FrmJinkenhiInfoCreate.cmdExecute").button("enable");
        }
    });

    $(".FrmJinkenhiInfoCreate.dtpYM").on("keydown", function (e) {
        var key = e.which;
        // var oEvent = window.event;
        if (key == 9 || key == 13) {
            $(".FrmJinkenhiInfoCreate.cmdExecute").button("enable");
            $(".FrmJinkenhiInfoCreate.cmdExecute").trigger("focus");
        }
    });

    //実行ボタン
    $(".FrmJinkenhiInfoCreate.cmdExecute").click(function () {
        me.cmdExecute_Click();
    });
    //EXCELに出力ボタン
    $(".FrmJinkenhiInfoCreate.cmdXlsxOut").click(function () {
        me.cmdXlsxOut_Click();
    });
    //EXCELを取込ボタン
    $(".FrmJinkenhiInfoCreate.cmdXlsxIn").click(function () {
        me.cmdXlsxIn_Click();
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        //ページロード
        me.Page_Load();
    };
    /*
	 '**********************************************************************
	 '処 理 名：ページロード
	 '関 数 名：Page_Load
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.Page_Load = function () {
        url = me.sys_id + "/" + me.id + "/" + "FrmJinkenhiInfoCreate_Load";
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                $(".FrmJinkenhiInfoCreate.dtpYM").val(
                    result["data"]["SYORI_YM"]
                );
                //対象年月
                me.dtpYM = result["data"]["SYORI_YM"];
                me.clsComFnc.ObjFocus = $(".FrmJinkenhiInfoCreate.dtpYM");
                $(".FrmJinkenhiInfoCreate.dtpYM").select();
            } else {
                $(".FrmJinkenhiInfoCreate").ympicker("disable");
                $(".FrmJinkenhiInfoCreate").attr("disabled", true);
                $(".FrmJinkenhiInfoCreate button").button("disable");

                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, "", 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：実行ボタン
	 '関 数 名：cmdExecute_Click
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.cmdExecute_Click = function () {
        //対象年月データ存在チェック
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.checkDB;
        me.clsComFnc.FncMsgBox("QY005");
    };
    /*
	 '**********************************************************************
	 '処 理 名：対象年月データ存在チェック
	 '関 数 名：checkDB
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.checkDB = function () {
        var dtpYM = $(".FrmJinkenhiInfoCreate.dtpYM").val();
        var url = me.sys_id + "/" + me.id + "/" + "checkDB";
        var data = {
            dtpYM: dtpYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //支給データ取込みが完了しているか確認
                me.selShikyuSyoreiKinData();
            } else {
                var dtpYM =
                    $(".FrmJinkenhiInfoCreate.dtpYM").val().substring(0, 4) +
                    "/" +
                    $(".FrmJinkenhiInfoCreate.dtpYM").val().substring(4, 6);
                //対象年月チェック
                if (result["error"] == "selShoriYM") {
                    //処理年月以前の場合、エラー
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "既に締め処理が行われているため該当の年月の人件費情報生成は行えません"
                    );
                }
                //支給データ取得
                else if (result["error"] == "selShikyuData") {
                    //存在しない場合、エラー
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "" +
                            dtpYM +
                            "月分の支給データが存在しません。 <br /> 給与データの取込を行ってください"
                    );
                }
                //事業主データ取得
                else if (result["error"] == "selJigyonushiData") {
                    //存在しない場合、エラー
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "" +
                            dtpYM +
                            "月分の事業主データが存在しません。<br /> 給与データの取込を行ってください"
                    );
                }
                //人件費振替比率データ取得
                else if (result["error"] == "selFurikaehiritsuData") {
                    //存在しない場合、エラー
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "" +
                            dtpYM +
                            "月分の人件費振替比率データが存在しません。<br /> 人件費振替比率入力を行ってください"
                    );
                } else if (result["error"] == "QaShow") {
                    //存在する場合、実行確認メッセージ
                    var strMsg =
                        "既に該当処理年月の人件費データが存在します。既に人件費入力を行っている場合、<br /> 入力したデータは再作成を行いますと消えてしまいます。続行しますか？";
                    //支給データ取込みが完了しているか確認
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.selShikyuSyoreiKinData;
                    me.clsComFnc.FncMsgBox("QY999", strMsg);
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：支給データ取込みが完了しているか確認
	 '関 数 名：selShikyuSyoreiKinData
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.selShikyuSyoreiKinData = function () {
        var dtpYM = $(".FrmJinkenhiInfoCreate.dtpYM").val();
        var url = me.sys_id + "/" + me.id + "/" + "selShikyuSyoreiKinData";
        var data = {
            dtpYM: dtpYM,
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"]) {
                //存在しない場合、処理続行確認
                if (result["data"][0]["CNTYM"] == 0) {
                    var strMsg =
                        dtpYM.substring(0, 4) +
                        "/" +
                        dtpYM.substring(4, 6) +
                        "月分の業績奨励金がありません。<br /> 処理を続行しますか。";
                    //人件費データ生成
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.createJinkenhiData;
                    me.clsComFnc.FncMsgBox("QY999", strMsg);
                    return;
                }
                //人件費データ生成
                me.createJinkenhiData();
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
	 '**********************************************************************
	 '処 理 名：人件費データ生成
	 '関 数 名：createJinkenhiData
	 '引    数：無し
	 '戻 り 値 ：無し
	 '処理説明 ：
	 '**********************************************************************
	 */
    me.createJinkenhiData = function () {
        var url = me.sys_id + "/" + me.id + "/" + "createJinkenhiData";
        var data = {
            dtpYM: $(".FrmJinkenhiInfoCreate.dtpYM").val(),
        };
        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (!result["result"]) {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return false;
            } else {
                //更新完了メッセージ
                me.clsComFnc.FncMsgBox("I0008");
            }
        };
        me.ajax.send(url, data, 0);
    };
    //20240307 caina ins s
    /*
 '**********************************************************************
 '処 理 名：EXCELに出力
 '関 数 名：cmdXlsxOut_Click
 '引    数：無し
 '戻 り 値 ：無し
 '処理説明 ：
 '**********************************************************************
 */
    me.cmdXlsxOut_Click = function () {
        var data = {
            dtpYM: $(".FrmJinkenhiInfoCreate.dtpYM").val(),
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnDownload_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                } else if (result["error"] == "W0024") {
                    me.clsComFnc.FncMsgBox(result["error"]);
                    return;
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }

            if (result["data"]["url"]) {
                //XLSXダウンロードボタンでEXCELダウンロード
                window.location.href = result["data"]["url"];
            }
        };
        me.ajax.send(url, data, 0);
    };
    /*
 '**********************************************************************
 '処 理 名：EXCELを取込
 '関 数 名：cmdXlsxIn_Click
 '引    数：無し
 '戻 り 値 ：無し
 '処理説明 ：
 '**********************************************************************
 */
    me.cmdXlsxIn_Click = function () {
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".xlsx";
        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            if (this.files[i].size > 5120000) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 5000KB です。"
                );
                return;
            }
            if (fileType != "xlsx") {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "指定されたファイルはxlsx形式のファイルではありません。"
                );
                return;
            }
            me.filename = this.files[i].name;
            me.btnUpload_Click_Check();
        });
        me.file.select_file();
    };
    //ファイルアップロード完了
    me.btnUpload_Click_Check = function () {
        me.file.send(me.func);
        return;
    };

    me.func = function (err) {
        if (err) {
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xlsx";
            $("#tmpFileUpload").html("");
            $("#tmpFileUpload").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var data = {
            filename: me.filename,
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox(
                    "I9999",
                    "取込処理が終了しました。(更新された行数：" +
                        result["row"] +
                        "行)"
                );
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    //20240307 caina ins e
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    o_FrmJinkenhiInfoCreate_FrmJinkenhiInfoCreate =
        new JKSYS.FrmJinkenhiInfoCreate();
    o_FrmJinkenhiInfoCreate_FrmJinkenhiInfoCreate.load();
});
