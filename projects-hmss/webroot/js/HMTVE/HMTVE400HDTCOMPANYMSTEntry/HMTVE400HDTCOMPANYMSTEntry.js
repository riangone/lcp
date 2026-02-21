/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 */

Namespace.register("HMTVE.HMTVE400HDTCOMPANYMSTEntry");

HMTVE.HMTVE400HDTCOMPANYMSTEntry = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "データ集計システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMTVE";
    me.id = "HMTVE400HDTCOMPANYMSTEntry";
    me.hmtve = new HMTVE.HMTVE();
    me.grid_id = "#HMTVE400HDTCOMPANYMSTEntry_grdGroupList";
    me.g_url = me.sys_id + "/" + me.id + "/btnSearchClick";
    me.colModel = [
        {
            label: "窓口会社コード",
            name: "COMPANY_CD",
            index: "COMPANY_CD",
            align: "left",
            search: false,
            width: 110,
            sortable: false,
        },
        {
            label: "窓口会社名",
            name: "COMPANY_NM",
            index: "COMPANY_NM",
            align: "left",
            search: false,
            width: 280,
            sortable: false,
        },
        {
            name: "",
            index: "operate",
            width: 70,
            align: "left",
            formatter: function (_cellvalue, options) {
                var detail =
                    "<button onclick=\"btnEdit_Click('" +
                    options.rowId +
                    "')\" id = '" +
                    options.rowId +
                    "_btnEdit' class=\"HMTVE400HDTCOMPANYMSTEntry btnEdit Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>修正</button>";
                return detail;
            },
        },
        {
            name: "",
            index: "operate",
            width: 70,
            align: "left",
            formatter: function (_cellvalue, options) {
                var detail =
                    "<button onclick=\"btnDelete_Click('" +
                    options.rowId +
                    "')\" id = '" +
                    options.rowId +
                    "_btnDelete' class=\"HMTVE400HDTCOMPANYMSTEntry btnDelete Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;'>削除</button>";
                return detail;
            },
        },
    ];

    me.option = {
        rowNum: 0,
        recordpos: "center",
        multiselect: false,
        rownumbers: false,
        caption: "",
        multiselectWidth: 40,
    };
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMTVE400HDTCOMPANYMSTEntry.btnAdd",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMTVE400HDTCOMPANYMSTEntry.btnClear",
        type: "button",
        handle: "",
    });

    me.controls.push({
        id: ".HMTVE400HDTCOMPANYMSTEntry.btnSearch",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.hmtve.Shift_TabKeyDown();

    //Tabキーのバインド
    me.hmtve.TabKeyDown();

    //Enterキーのバインド
    me.hmtve.EnterKeyDown();
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //絞り込むボタンクリック
    $(".HMTVE400HDTCOMPANYMSTEntry.btnSearch").click(function () {
        me.btnSearch_Click();
    });
    //クリアボタンクリック
    $(".HMTVE400HDTCOMPANYMSTEntry.btnClear").click(function () {
        me.btnClear_Click();
    });
    //登録ボタンクリック
    $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").click(function () {
        if (
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly") !=
            "readonly"
        ) {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnAdd_Click;
            me.clsComFnc.FncMsgBox(
                "QY999",
                "窓口会社マスタデータを登録します。よろしいですか？"
            );
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnAdd_Click;
            me.clsComFnc.FncMsgBox(
                "QY999",
                "窓口会社マスタデータを削除します。よろしいですか？"
            );
        }
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
        //プロシージャ:画面初期化
        me.Page_Load();
    };
    //**********************************************************************
    //処 理 名：ページロード
    //関 数 名：Page_Load
    //引    数：無し
    //戻 り 値：なし
    //処理説明：ページ初期化
    //**********************************************************************
    me.Page_Load = function () {
        me.PageClear();

        var data = {
            COMPANY_CD: "",
            COMPANY_NM: "",
        };
        var url = me.sys_id + "/" + me.id + "/btnSearchClick";
        //スプレッドに取得データをセットする
        gdmz.common.jqgrid.showWithMesg(
            me.grid_id,
            url,
            me.colModel,
            "",
            "",
            me.option,
            data,
            function (_bErrorFlag, result_jqgrid) {
                if (result_jqgrid["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result_jqgrid["error"]);
                    return;
                }
                if (result_jqgrid["records"] == 0) {
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").trigger(
                        "focus"
                    );
                }

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", "0");
            }
        );
        gdmz.common.jqgrid.set_grid_width(me.grid_id, 570);
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 300 : 370
        );
        $(me.grid_id).jqGrid("bindKeys");
    };
    //**********************************************************************
    //処 理 名：クリアボタンのイベント
    //関 数 名：PageClear
    //引    数：無し
    //戻 り 値：なし
    //処理説明：画面項目No.9、画面項目No.10の値をクリアする
    //**********************************************************************
    me.PageClear = function () {
        $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").html("登録");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").attr("readonly", false);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly", false);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").val("");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").val("");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val("");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val("");
    };
    //**********************************************************************
    //処 理 名：クリアボタンのイベント
    //関 数 名：btnClear_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：窓口会社データを登録する
    //**********************************************************************
    me.btnClear_Click = function () {
        me.PageReSet();
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val("");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val("");
        $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").html("登録");
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").trigger("focus");
    };
    //**********************************************************************
    //処 理 名：登録ボタンのイベント(登録、修正、削除)
    //関 数 名：btnAdd_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：窓口会社データを登録する
    //**********************************************************************
    me.btnAdd_Click = function () {
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        if (
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly") !=
            "readonly"
        ) {
            if (me.DataCheck2()) {
                me.btnAdd_execute();
            }
        } else {
            me.btnAdd_execute();
        }
    };

    me.btnAdd_execute = function () {
        if (
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly") !=
            "readonly"
        ) {
            try {
                //データバインドする
                var url = me.sys_id + "/" + me.id + "/check";
                var data = me.getContent();
                me.ajax.receive = function (result) {
                    result = eval("(" + result + ")");
                    if (result["result"]) {
                        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").trigger(
                            "focus"
                        );
                        //登録が完了しました。
                        me.clsComFnc.FncMsgBox("I0016");
                        //画面制御
                        me.PageClear();
                        //データの取得
                        me.DataGetSQL();
                    } else {
                        if (result["error"] == "W0004") {
                            //他のユーザーにより更新されています。最新の情報を確認してください。
                            me.clsComFnc.FncMsgBox("W0025");
                        } else if (result["error"] == "E0005") {
                            //既に登録されています。
                            me.clsComFnc.FncMsgBox("E0016");
                        } else {
                            me.clsComFnc.FncMsgBox("E9999", result["error"]);
                        }
                    }
                };
                me.ajax.send(url, data, 0);
            } catch (ex) {
                console.log(ex);
            }
        } else if (
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly") ==
            "readonly"
        ) {
            //データバインドする
            var url = me.sys_id + "/" + me.id + "/delete";
            var data = me.getContent();
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");
                if (result["result"]) {
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").trigger(
                        "focus"
                    );
                    //削除が完了しました。
                    me.clsComFnc.FncMsgBox("I0017");
                    //画面制御
                    me.PageClear();
                    //データの取得
                    me.DataGetSQL();
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
            };
            me.ajax.send(url, data, 0);
        }
    };
    //**********************************************************************
    //処 理 名：修正ボタンのイベント
    //関 数 名：btnEdit_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：窓口会社データを修正する
    //**********************************************************************
    btnEdit_Click = function (rowid) {
        var rowData = $(me.grid_id).jqGrid("getRowData", rowid);

        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val(rowData["COMPANY_CD"]);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val(rowData["COMPANY_NM"]);

        me.PageReSet();
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").attr("readonly", true);
        setTimeout(function () {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").trigger("focus");
        }, 0);

        $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").html("登録");
    };
    //**********************************************************************
    //処 理 名：削除ボタンのイベント
    //関 数 名：btnDelete_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：窓口会社データを削除する
    //**********************************************************************
    btnDelete_Click = function (rowid) {
        var rowData = $(me.grid_id).jqGrid("getRowData", rowid);

        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val(rowData["COMPANY_CD"]);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val(rowData["COMPANY_NM"]);
        me.PageReSet();
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").attr("readonly", true);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly", true);
        setTimeout(function () {
            $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").trigger("focus");
        }, 0);
        $(".HMTVE400HDTCOMPANYMSTEntry.btnAdd").html("削除");
    };
    //**********************************************************************
    //処 理 名：検索ボタンのイベント
    //関 数 名：btnSearch_Click
    //引    数：無し
    //戻 り 値：なし
    //処理説明：窓口会社データを取得する
    //**********************************************************************
    me.btnSearch_Click = function () {
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        //入力チェック
        if (me.DataCheck1()) {
            //データの取得
            me.DataGetSQL();
        }
    };
    //**********************************************************************
    //処理名：コンテンツ取得
    //関数名：getContent
    //引   数：なし
    //戻り値：data
    //処理説明：画面の内容を取得する
    //**********************************************************************
    me.getContent = function () {
        var strMode = "";
        if (
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").attr("readonly") !=
            "readonly"
        ) {
            strMode = "INSERT";
        } else {
            strMode = "UPDATE";
        }
        var COMPANY_CD = $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val();
        var COMPANY_NM = $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val();
        var data = {
            strMode: strMode,
            COMPANY_CD: COMPANY_CD,
            COMPANY_NM: COMPANY_NM,
        };
        return data;
    };
    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：DataCheck1
    //引    数：無し
    //戻 り 値：なし
    //処理説明：入力文字に不正がないかをチェックする
    //**********************************************************************
    me.DataCheck1 = function () {
        var objRegEx_NG1 = /[\'\""]/g;
        var objRegEx_NG2 = /[\,\""]/g;
        //会社コード <> "" の場合
        if ($.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").val()) != "") {
            if (
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").val()).match(
                    objRegEx_NG1
                )
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComCode"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社コード");
                return false;
            } else if (
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").val()).match(
                    objRegEx_NG2
                )
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComCode"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社コード");
                return false;
            }
        }
        //会社名 <> "" の場合
        if ($.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName").val()) != "") {
            if (
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName").val()).match(
                    objRegEx_NG1
                )
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComName"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社名");
                return false;
            } else if (
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName").val()).match(
                    objRegEx_NG2
                )
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComName"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社名");
                return false;
            }
        }
        return true;
    };
    //**********************************************************************
    //処 理 名：入力チェック
    //関 数 名：DataCheck2
    //引    数：無し
    //戻 り 値：なし
    //処理説明：入力文字に不正がないかをチェックする
    //**********************************************************************
    me.DataCheck2 = function () {
        var objRegEx_NG1 = /[\'\""]/g;
        var objRegEx_NG2 = /[\,\""]/g;
        var objRegEX_AN = /[^a-zA-Z0-9\-]/g;
        //必須チェックを行う
        if ($.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val()) == "") {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.ObjFocus = $(
                ".HMTVE400HDTCOMPANYMSTEntry.txtComCode2"
            );
            me.clsComFnc.FncMsgBox("E0012", "窓口会社コード");
            return false;
        } else if (
            $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val()) == ""
        ) {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.ObjFocus = $(
                ".HMTVE400HDTCOMPANYMSTEntry.txtComName2"
            );
            me.clsComFnc.FncMsgBox("E0012", "窓口会社名");
            return false;
        }
        //桁数チェックを行う
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val())
            ) > 5
        ) {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.ObjFocus = $(
                ".HMTVE400HDTCOMPANYMSTEntry.txtComCode2"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "窓口会社コードは指定されている桁数をオーバーしています。"
            );
            return false;
        }
        if (
            me.clsComFnc.GetByteCount(
                $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val())
            ) > 100
        ) {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.ObjFocus = $(
                ".HMTVE400HDTCOMPANYMSTEntry.txtComName2"
            );
            me.clsComFnc.FncMsgBox(
                "W9999",
                "窓口会社名は指定されている桁数をオーバーしています。"
            );
            return false;
        }
        //整合性チェックを行う
        if (
            $.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val()).match(
                objRegEX_AN
            )
        ) {
            $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
                me.clsComFnc.GC_COLOR_ERROR
            );
            me.clsComFnc.ObjFocus = $(
                ".HMTVE400HDTCOMPANYMSTEntry.txtComCode2"
            );
            me.clsComFnc.FncMsgBox("E0013", "窓口会社コード");
            return false;
        }
        //会社コードに"'"or","が入力されている場合
        if ($.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val()) != "") {
            if (
                $.trim(
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val()
                ).match(objRegEx_NG1)
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComCode2"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社コード");
                return false;
            } else if (
                $.trim(
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").val()
                ).match(objRegEx_NG2)
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComCode2"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社コード");
                return false;
            }
        }

        //会社名に"'"or","が入力されている場合
        if ($.trim($(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val()) != "") {
            if (
                $.trim(
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val()
                ).match(objRegEx_NG1)
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComName2"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社名");
                return false;
            } else if (
                $.trim(
                    $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").val()
                ).match(objRegEx_NG2)
            ) {
                $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
                    me.clsComFnc.GC_COLOR_ERROR
                );
                me.clsComFnc.ObjFocus = $(
                    ".HMTVE400HDTCOMPANYMSTEntry.txtComName2"
                );
                me.clsComFnc.FncMsgBox("E0013", "窓口会社名");
                return false;
            }
        }
        return true;
    };
    //**********************************************************************
    //処 理 名：値のクリア
    //関 数 名：PageReSet
    //引    数：無し
    //戻 り 値：なし
    //処理説明：登録・削除ボタンの場所の値をクリアする
    //**********************************************************************
    me.PageReSet = function () {
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").css(
            me.clsComFnc.GC_COLOR_NORMAL
        );
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode2").attr("readonly", false);
        $(".HMTVE400HDTCOMPANYMSTEntry.txtComName2").attr("readonly", false);
    };
    me.DataGetSQL = function () {
        try {
            var COMPANY_CD = $(".HMTVE400HDTCOMPANYMSTEntry.txtComCode").val();
            var COMPANY_NM = $(".HMTVE400HDTCOMPANYMSTEntry.txtComName").val();
            var data = {
                COMPANY_CD: COMPANY_CD,
                COMPANY_NM: COMPANY_NM,
            };
            var complete_fun = function (_returnFLG, result) {
                if (result["error"]) {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                var objDR = $(me.grid_id).jqGrid("getRowData");
                if (objDR.length == 0) {
                    //該当するデータは存在しません。
                    me.clsComFnc.ObjFocus = $(
                        ".HMTVE400HDTCOMPANYMSTEntry.txtComCode"
                    );
                    me.clsComFnc.FncMsgBox("W0024");
                    return;
                }

                //１行目を選択状態にする
                $(me.grid_id).jqGrid("setSelection", "0");
            };
            gdmz.common.jqgrid.reloadMessage(me.grid_id, data, complete_fun);
        } catch (ex) {
            console.log(ex);
        }
    };
    // ==========
    // = メソッド end =
    // ==========
    return me;
};

$(function () {
    var o_HMTVE_HMTVE400HDTCOMPANYMSTEntry =
        new HMTVE.HMTVE400HDTCOMPANYMSTEntry();
    o_HMTVE_HMTVE400HDTCOMPANYMSTEntry.load();
});
