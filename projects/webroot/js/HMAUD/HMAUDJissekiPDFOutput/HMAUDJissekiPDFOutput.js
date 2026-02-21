/**
 * 本システムでUI（Windows,Dialogなど）を構成する基本クラス
 * @alias  panel
 * @author FCSDL
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                          FCSDL
 * 20241030           202410_内部統制システム_集計機能改善対応.xlsx                    caina
 * 20250219           20250219_内部統制_改修要望.xlsx                                caina
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("HMAUD.HMAUDJissekiPDFOutput");

HMAUD.HMAUDJissekiPDFOutput = function () {
    // ==========
    // = 宣言 start =
    // ==========

    var me = new gdmz.base.panel();

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.ajax = new gdmz.common.ajax();
    me.sys_id = "HMAUD";
    me.id = "HMAUDJissekiPDFOutput";
    me.HMAUD = new HMAUD.HMAUD();

    me.gennzayiCour = "";
    me.allCourData = "";
    //20230314 LIU INS S
    me.isViewer = false;
    //20230314 LIU INS E
    // 20241030 caina ins s
    // 切り替え前のcheckbox選択状態を保存する
    me.previousSelections = [];
    // 20241030 caina ins e
    // 20250219 caina ins s
    me.carSevenChboxChd = true;
    // 20250219 caina ins e

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.controls.push({
        id: ".HMAUDJissekiPDFOutput.button",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HMAUD.Shift_TabKeyDown();

    //Tabキーのバインド
    me.HMAUD.TabKeyDown();

    //Enterキーのバインド
    me.HMAUD.EnterKeyDown();

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //クール
    $(".HMAUDJissekiPDFOutput.cours").change(function () {
        //クールchange
        me.fncCourChange();
    });
    // 20241030 caina ins s
    //集計種類
    $(".HMAUDJissekiPDFOutput.summery").change(function () {
        //集計種類change
        me.fncSummeryChange();
    });
    // 20241030 caina ins e
    //集計ボタンクリック
    $(".HMAUDJissekiPDFOutput.btnJisseki").click(function () {
        me.btnDownload_Click("jisseki");
    });
    //PDFダウンロードボタンクリック
    $(".HMAUDJissekiPDFOutput.pdfDownload").click(function () {
        me.btnDownload_Click("pdf");
    });
    //検XLSXダウンロードボタンクリック
    $(".HMAUDJissekiPDFOutput.xlsxDownload").click(function () {
        me.btnDownload_Click("xlsx");
    });
    // 20250219 caina ins s
    $(".HMAUDJissekiPDFOutput.carSevenChbox").change(function () {
        me.carSevenChboxChd = $(this).prop("checked");
    });
    // 20250219 caina ins e
    window.onresize = function () {
        setTimeout(function () {
            me.setTableSize();
        }, 500);
    };
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
    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：Page_Load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.Page_Load = function () {
        me.setTableSize();
        $(".HMAUDJissekiPDFOutput.pnlList").hide();
        $(".HMAUDJissekiPDFOutput.cours").focus();
        var url = me.sys_id + "/" + me.id + "/" + "Page_load";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //ボタン
                $(".HMAUDJissekiPDFOutput .button").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }
            $(".HMAUDJissekiPDFOutput.cours").find("option").remove();
            $("<option></option>")
                .val("")
                .text("")
                .appendTo(".HMAUDJissekiPDFOutput.cours");
            if (result["data"]["cour"].length > 0) {
                var courAll = result["data"]["cour"];
                me.allCourData = courAll;
                for (var i = 0; i < courAll.length; i++) {
                    //クールselect
                    $("<option></option>")
                        .val(courAll[i]["COURS"])
                        .text(courAll[i]["COURS"])
                        .appendTo(".HMAUDJissekiPDFOutput.cours");
                    if (courAll[i]["COURS_NOW"] == "1") {
                        //現在のクール数
                        me.gennzayiCour = courAll[i]["COURS"];
                    }
                }
                //20230314 LIU INS S
                if (result["data"]["viewer"].length > 0) {
                    me.isViewer = true;
                }
                //20230314 LIU INS E
            }
            if (result["data"]["cour"].length > 0) {
                $(".HMAUDJissekiPDFOutput.cours").val(me.gennzayiCour);
            }
            me.fncCourChange();
        };
        me.ajax.send(url, "", 0);
    };
    //'**********************************************************************
    //'処 理 名：クールchange
    //'関 数 名：fncCourChange
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：クールを選択したら開始日～終了日を表示
    //'**********************************************************************
    me.fncCourChange = function () {
        var cour = $(".HMAUDJissekiPDFOutput.cours").val();
        var foundDT = undefined;
        if (me.allCourData) {
            var foundDT_array = me.allCourData.filter(function (element) {
                return element["COURS"] == cour;
            });
            if (foundDT_array.length > 0) {
                foundDT = foundDT_array[0];
            }
            $(".HMAUDJissekiPDFOutput.courPeriod").text(
                foundDT ? foundDT["PERIOD"] : ""
            );
        }
        // 20241030 caina ins s
        // 選択した「cour」が8であるかどうかをチェック
        if (cour == "8") {
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(1)
                .prop("disabled", true);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(3)
                .prop("disabled", true);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(4)
                .prop("disabled", true);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(6)
                .prop("disabled", true);
        } else {
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(1)
                .prop("disabled", false);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(3)
                .prop("disabled", false);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(4)
                .prop("disabled", false);
            $(".HMAUDJissekiPDFOutput.summery")
                .find("option")
                .eq(6)
                .prop("disabled", false);
        }
        // 20241030 caina ins e
        // 20250219 caina ins s
        // 18クールからは新規に領域「カーセブン」を追加して
        if (parseInt(cour) >= 18) {
            $(".HMAUDJissekiPDFOutput.carSevenChbox").css(
                "display",
                "inline-block"
            );
            var summeryValue = $(".HMAUDJissekiPDFOutput.summery").val();
            if (
                summeryValue === "cumulative_issue_table" ||
                summeryValue === "consecutive_issue_table"
            ) {
                $(".HMAUDJissekiPDFOutput.carSevenChbox").prop(
                    "checked",
                    me.carSevenChboxChd
                );
            }
            $(".HMAUDJissekiPDFOutput.carSevenlbl").css(
                "visibility",
                "visible"
            );
        } else {
            $(".HMAUDJissekiPDFOutput.carSevenChbox").css("display", "none");
            $(".HMAUDJissekiPDFOutput.carSevenChbox").prop("checked", false);
            $(".HMAUDJissekiPDFOutput.carSevenlbl").css("visibility", "hidden");
        }
        // 20250219 caina ins e
        if (cour != "" && cour != null) {
            var data = {
                COUR: cour,
            };
            var url = me.sys_id + "/" + me.id + "/" + "courChange";
            me.ajax.receive = function (result) {
                var result = eval("(" + result + ")");
                if (!result["result"]) {
                    //ボタン
                    $(".HMAUDJissekiPDFOutput .button").button("disable");
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                    return;
                }
                // 20230314 LIU UPD S
                //if (result['data']['member'].length > 0)
                if (
                    result["data"]["member"].length > 0 ||
                    me.isViewer == true
                ) {
                    // 20230314 LIU UPD E
                    //ボタン
                    $(".HMAUDJissekiPDFOutput .button").button("enable");
                } else {
                    //ボタン
                    $(".HMAUDJissekiPDFOutput .button").button("disable");
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            //ボタン
            $(".HMAUDJissekiPDFOutput .button").button("disable");
        }
    };
    // 20241030 caina ins s
    //'**********************************************************************
    // '処 理 名：集計種類change
    // '関 数 名：fncSummeryChange
    // '引    数：無し
    // '戻 り 値：無し
    // '処理説明：集計種類を選択したら領域を設定
    // '**********************************************************************
    me.fncSummeryChange = function () {
        var summeryValue = $(".HMAUDJissekiPDFOutput.summery").val();

        // 20250219 caina ins s
        var cour = $(".HMAUDJissekiPDFOutput.cours").val();
        // 20250219 caina ins e
        // checkboxが無効になっていない場合は、現在の選択状態を保存します
        if (!$(".HMAUDJissekiPDFOutput.territoryChbox").prop("disabled")) {
            $(".HMAUDJissekiPDFOutput.territoryChbox:checked").each(
                function () {
                    if (!me.previousSelections.includes($(this).val())) {
                        me.previousSelections.push($(this).val());
                    }
                }
            );
            $(".HMAUDJissekiPDFOutput.territoryChbox:not(:checked)").each(
                function () {
                    var value = $(this).val();
                    var index = me.previousSelections.indexOf(value);
                    if (index !== -1) {
                        me.previousSelections.splice(index, 1);
                    }
                }
            );
        }
        if (
            summeryValue !== "cumulative_issue_table" &&
            summeryValue !== "consecutive_issue_table"
        ) {
            // デフォルトの選択営業、サービス、管理
            $(".HMAUDJissekiPDFOutput.territoryChbox").prop("checked", false);
            $('.HMAUDJissekiPDFOutput.territoryChbox[value="1"]').prop(
                "checked",
                true
            );
            $('.HMAUDJissekiPDFOutput.territoryChbox[value="2"]').prop(
                "checked",
                true
            );
            $('.HMAUDJissekiPDFOutput.territoryChbox[value="3"]').prop(
                "checked",
                true
            );

            // 変更不可に設定
            $(".HMAUDJissekiPDFOutput.territoryChbox").prop("disabled", true);
        } else {
            // 他のオプションの選択を許可
            $(".HMAUDJissekiPDFOutput.territoryChbox").prop("disabled", false);

            // リカバリ前の選択状態（既存の選択肢を復元）
            $(".HMAUDJissekiPDFOutput.territoryChbox").prop("checked", false);
            me.previousSelections.forEach(function (value) {
                // 20250219 caina ins s
                if (value == 6 && parseInt(cour) < 18) {
                    return;
                }
                // 20250219 caina ins e
                $(
                    '.HMAUDJissekiPDFOutput.territoryChbox[value="' +
                        value +
                        '"]'
                ).prop("checked", true);
            });
        }
    };
    // 20241030 caina ins e
    //'**********************************************************************
    //'処 理 名：PDF出力
    //'関 数 名：btnDownload_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：PDF出力
    //'**********************************************************************
    me.btnDownload_Click = function (flg) {
        var cour = $(".HMAUDJissekiPDFOutput.cours").val();
        if (cour == "" || cour == null) {
            me.clsComFnc.ObjFocus = $(".HMAUDJissekiPDFOutput.cours");
            //クールを選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "クールを選択して下さい！");
            return false;
        }
        //領域
        var sessionTerritoryArr = [];
        $(".HMAUDJissekiPDFOutput.territoryChbox").each(function () {
            if ($(this).is(":checked") == true) {
                sessionTerritoryArr.push($(this).val());
            }
        });
        if (sessionTerritoryArr.length == 0) {
            //領域を選択して下さい！
            me.clsComFnc.FncMsgBox("W9999", "領域を選択して下さい！");
            return false;
        }
        // 20241030 caina ins s
        // 集計種類
        var summeryValue = $(".HMAUDJissekiPDFOutput.summery").val();
        // 20241030 caina ins e
        var data = {
            COUR: cour,
            TERRITORYArr: sessionTerritoryArr,
            // 20241030 caina ins s
            SUMMERY: summeryValue,
            // 20241030 caina ins e
            flg: flg,
        };
        var url = me.sys_id + "/" + me.id + "/" + "btnDownload_Click";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                if (result["error"] == "nodetail") {
                    me.clsComFnc.FncMsgBox("E0026", "チェックリスト項目データ");
                    return;
                }
                if (result["error"] == "nokyoten") {
                    me.clsComFnc.FncMsgBox("E0026", "拠点データ");
                    return;
                }
                if (result["error"] == "W9999") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "テンプレートファイルが存在しません。"
                    );
                    return;
                }
                // 20241030 caina ins s
                if (result["error"] == "nodata") {
                    //該当するデータは存在しません。
                    me.clsComFnc.FncMsgBox("I0001");
                    return;
                }
                // 20241030 caina ins e
                //ダウンロードボタン
                $(".HMAUDJissekiPDFOutput.pnlList").hide();
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            }

            if (result["report"]) {
                if (flg == "jisseki") {
                    //集計ボタンでＰＤＦを画面表示
                    var url = window.location.href;
                    url = url.replace("#", "");
                    $(".HMAUDJissekiPDFOutput.pnlList").show();
                    var href =
                        url +
                        "js/common/PDF.js/web/viewer.html?file=" +
                        url +
                        result["report"] +
                        "#zoom=130";
                    $(".HMAUDJissekiPDFOutput.temp").prop("src", href);
                } else if (flg == "pdf") {
                    // 20241114 caina ins s
                    var summeryValue = $(
                        ".HMAUDJissekiPDFOutput.summery"
                    ).val();
                    //PDFダウンロードボタンで PＤＦダウンロード
                    if (summeryValue === "cumulative_issue_table") {
                        $fileName = "指摘事項表（累計）";
                    } else if (summeryValue === "consecutive_issue_table") {
                        $fileName = "指摘事項表（連続）";
                    } else if (summeryValue === "issue_ranking") {
                        $fileName = "指摘事項数ランキング";
                    } else if (
                        summeryValue === "cumulative_multiple_issue_ranking"
                    ) {
                        $fileName = "複数回指摘事項数ランキング（累計）";
                    } else if (
                        summeryValue === "consecutive_multiple_issue_ranking"
                    ) {
                        $fileName = "複数回指摘事項数ランキング（連続）";
                    } else if (summeryValue === "issue_ranking_per_territory") {
                        $fileName = "各領域ごと指摘項目ランキング";
                    } else if (
                        summeryValue ===
                        "cumulative_multiple_issue_ranking_per_territory"
                    ) {
                        $fileName =
                            "各領域ごと複数回指摘項目ランキング（累計）";
                    }
                    // 20241114 caina ins e
                    // 20241114 caina upd s
                    // me.download(result['report'], '実績集計');
                    me.download(result["report"], $fileName);
                    // 20241114 caina upd e
                } else {
                    //XLSXダウンロードボタンでEXCELダウンロード
                    window.location.href = result["data"];
                }
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.download = function (url, fileName) {
        const a = document.createElement("a");
        a.style.display = "none";
        a.setAttribute("target", "_blank");
        /*
         * download的属性是HTML5新增的属性
         * href属性的地址必须是非跨域的地址，如果引用的是第三方的网站或者说是前后端分离的项目(调用后台的接口)，这时download就会不起作用。
         * 此时，如果是下载浏览器无法解析的文件，例如.exe,.xlsx..那么浏览器会自动下载，但是如果使用浏览器可以解析的文件，比如.txt,.png,.pdf....浏览器就会采取预览模式
         * 所以，对于.txt,.png,.pdf等的预览功能我们就可以直接不设置download属性(前提是后端响应头的Content-Type: application/octet-stream，如果为application/pdf浏览器则会判断文件为 pdf ，自动执行预览的策略)
         */
        fileName && a.setAttribute("download", fileName);
        a.href = url;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };
    me.setTableSize = function () {
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var searchHeight1 = $(".HMAUDJissekiPDFOutput.search-panel1").height();
        var searchHeight2 = $(".HMAUDJissekiPDFOutput.search-panel2").height();
        var buttonHeight = $(".HMAUDJissekiPDFOutput.HMS-button-pane").height();
        var viewHeight =
            mainHeight - searchHeight1 - searchHeight2 - buttonHeight - 60;
        //firefox
        if (navigator.userAgent.toLowerCase().indexOf("firefox") > -1) {
            viewHeight =
                mainHeight - searchHeight1 - searchHeight2 - buttonHeight - 54;
        }
        $(".HMAUDJissekiPDFOutput.temp").height(viewHeight);
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HMAUD_HMAUDJissekiPDFOutput = new HMAUD.HMAUDJissekiPDFOutput();
    o_HMAUD_HMAUDJissekiPDFOutput.load();
    o_HMAUD_HMAUD.HMAUDJissekiPDFOutput = o_HMAUD_HMAUDJissekiPDFOutput;
});
