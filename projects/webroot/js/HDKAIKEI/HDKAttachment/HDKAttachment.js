Namespace.register("HDKAIKEI.HDKAttachment");

HDKAIKEI.HDKAttachment = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.HDKAIKEI = new HDKAIKEI.HDKAIKEI();
    me.clsComFnc.GSYSTEM_NAME = "（TMRH）HD伝票集計システム";
    me.id = "HDKAttachment";
    me.SYOHY_NO = "";
    me.EDA_NO = "";
    me.GYO_NO = 0;
    me.newRow = false;
    me.maxSEQ = 0;

    // ========== 変数 start ==========

    me.grid_id = "#HDKAIKEI_HDKAttachment_grid";
    me.sys_id = "HDKAIKEI";
    me.g_url = me.sys_id + "/" + me.id + "/" + "search_files";
    me.option = {
        rowNum: 0,
        multiselect: false,
        rownumbers: false,
        caption: "",
        autowidth: true,
        multiselectWidth: 40,
        shrinkToFit: true,
    };

    me.colModel = [
        {
            name: "FILE_NAME",
            label: "ファイル名",
            index: "FILE_NAME",
            width: 845,
            align: "left",
            sortable: false,
        },
        {
            name: "SEQ",
            label: "SEQ",
            index: "SEQ",
            align: "left",
            sortable: false,
            hidden: true,
        },
        {
            name: "",
            label: "",
            index: "",
            width: 200,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"preview_click('" +
                    rowObject.FILE_NAME +
                    "')\" id = '" +
                    rowObject.clid +
                    "_btnPreview' class=\"HDKAttachment btnPreview Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size: " +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>プレビュー</button>";
                return detail;
            },
        },
        {
            name: "",
            label: "",
            index: "",
            width: 200,
            align: "left",
            formatter: function (_cellvalue, _options, rowObject) {
                var detail =
                    "<button onclick=\"download_click('" +
                    rowObject.FILE_NAME +
                    "')\" id = '" +
                    rowObject.clid +
                    "_btnDownload' class=\"HDKAttachment btnDownload Tab Enter\" style='border: 1px solid #77d5f7;background: #16b1e9;width: 100%;font-size: " +
                    (me.ratio === 1.5 ? "10" : "13") +
                    "px;'>ダウンロード</button>";
                return detail;
            },
        },
    ];

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    //追加ボタン
    me.controls.push({
        id: ".HDKAttachment.btnAdd",
        type: "button",
        handle: "",
    });

    //削除ボタン
    me.controls.push({
        id: ".HDKAttachment.btnDelete",
        type: "button",
        handle: "",
    });

    //戻るボタン
    me.controls.push({
        id: ".HDKAttachment.btnClose",
        type: "button",
        handle: "",
    });

    //ShifキーとTabキーのバインド
    me.HDKAIKEI.Shift_TabKeyDown(me.id);

    //Tabキーのバインド
    me.HDKAIKEI.TabKeyDown(me.id);

    //Enterキーのバインド
    me.HDKAIKEI.EnterKeyDown(me.id);

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    //処理説明：追加ボタン押下時
    $(".HDKAttachment.btnAdd").click(function () {
        me.btnAdd_Click();
    });
    //処理説明：削除ボタン押下時
    $(".HDKAttachment.btnDelete").click(function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        if (SelectRow == null) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "削除するファイルを一覧から選択してください。"
            );
            return false;
        } else {
            me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
            me.clsComFnc.FncMsgBox("QY999", "ファイルを削除しますか");
        }
    });
    //処理説明：戻るボタン押下時
    $(".HDKAttachment.btnClose").click(function () {
        $("#HDKAttachmentDialogDiv").dialog("close");
    });
    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    $(window).resize(function () {
        $(me.grid_id).setGridWidth($(".HDKAttachment.sprItyp").width() - 16);
        $(".HDKAttachment.temp").height(
            $("#HDKAttachmentDialogDiv").height() -
                (me.ratio === 1.5 ? 320 : 360)
        );
        // $(me.grid_id).setGridHeight($("#HDKAttachmentDialogDiv").height() - 495);
    });

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
        me.HDKAttachment_load();
    };

    //'**********************************************************************
    //'処 理 名：ページロード
    //'関 数 名：HDKAttachment_load
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：ページ初期化
    //'**********************************************************************
    me.HDKAttachment_load = function () {
        $(".HDKAttachment.pnlList").hide();
        $(".HDKAttachment.temp").height(me.ratio === 1.5 ? 180 : 272);
        me.SYOHY_NO = $("#SYOHY_NO15").html();
        me.EDA_NO = $("#EDA_NO").html();
        me.GYO_NO = $("#GYO_NO").html();
        me.MAX_SYORI_FLG = $("#MAX_SYORI_FLG").html();
        me.fromView = $("#From_View").html();
        if (
            me.SYOHY_NO == undefined ||
            me.EDA_NO == undefined ||
            me.GYO_NO == undefined ||
            me.fromView == undefined
        ) {
            $(".HDKAttachment .btn").button("disable");
            $(".HDKAttachment .btnClose").trigger("focus");
            return;
        }
        $(".HDKAttachment.txtSyohy_no").val(me.SYOHY_NO + me.EDA_NO);
        $(".HDKAttachment.txtTorihiki").val(
            $("." + me.fromView + " .lblKensakuNM").val()
        );
        $(".HDKAttachment.txtTekyo").val(
            $("." + me.fromView + " .txtTekyo").val()
        );
        me.data = {
            SYOHY_NO: me.SYOHY_NO,
            EDA_NO: me.EDA_NO,
            GYO_NO: me.GYO_NO,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };
        gdmz.common.jqgrid.showWithMesgScroll(
            me.grid_id,
            me.g_url,
            me.colModel,
            "",
            "",
            me.option,
            me.data,
            me.complete_fun
        );
        gdmz.common.jqgrid.set_grid_width(
            me.grid_id,
            me.ratio === 1.5 ? 1034 : 1082
        );
        gdmz.common.jqgrid.set_grid_height(
            me.grid_id,
            me.ratio === 1.5 ? 90 : 132
        );
        if (me.fromView == "HDKShiharaiInput") {
            $(".HDKAttachment.temp").height(me.ratio === 1.5 ? 180 : 272);
        }
    };
    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            $(".HDKAttachment .btn").button("disable");
            // 20241226 YIN UPD S
            // setTimeout(() => {
            setTimeout(function () {
                // 20241226 YIN UPD E
                me.clsComFnc.FncMsgBox("E9999", data["error"]);
            }, 100);
            return;
        }
        if (data["result"] == false) {
            $(".HDKAttachment .btn").button("disable");
        } else {
            if (data["dispMode"] == "nodata") {
                $(".HDKAttachment .btn").button("disable");

                me.clsComFnc.FncMsgBox("W9999", "該当する伝票は存在しません！");
                return;
            }
            if (data["records"] > 0) {
                preview_click(data["rows"][0]["cell"]["FILE_NAME"]);
            }
            if (data["dispMode"] == "none") {
                $(".HDKAttachment .btn").button("disable");
                return;
            }
        }
        $(".HDKAttachment .btnAdd").trigger("focus");
    };
    me.before_close = function () {};

    //'**********************************************************************
    //'処 理 名：追加ボタンクリック
    //'関 数 名：btnAdd_Click
    //'引    数：無し
    //'戻 り 値：無し
    //'処理説明：追加ボタンの処理
    //'**********************************************************************
    me.btnAdd_Click = function () {
        var rows = $(me.grid_id).jqGrid("getDataIDs");
        if (rows.length == 5) {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "5個以上のファイルは添付できません！"
            );
            return;
        }
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        me.file.accept = ".pdf";
        me.file.isfiles = true;
        me.file.res = "HDKAttachment";
        me.filesArr = [];

        $("#tmpFileUpload").html("");
        $("#tmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            var rows = $(me.grid_id).jqGrid("getDataIDs");
            if (rows.length + this.files.length > 5) {
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "5個以上のファイルは添付できません！"
                );
                return;
            }
            for (var i = 0; i < this.files.length; i++) {
                var arr = this.files[i].name.split(".");
                var filelong = arr.length;
                filelong = filelong - 1;
                var fileType = arr[filelong].toLowerCase();
                if (this.files[i].size > 5120000) {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "5MB以上のファイルはアップロードできません！"
                    );
                    return;
                }
                if (fileType != "pdf") {
                    me.clsComFnc.FncMsgBox(
                        "W9999",
                        "PDFファイル以外はアップロードできません！"
                    );
                    return;
                }
                me.filesArr.push(this.files[i].name);
            }
            me.file.send(me.func, me.efunc);
        });
        me.file.select_file();
    };
    me.func = function () {
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var data = {
            SYOHY_NO: me.SYOHY_NO,
            EDA_NO: me.EDA_NO,
            GYO_NO: me.GYO_NO,
            files: me.filesArr,
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HDKAttachment.pnlList").hide();
                    gdmz.common.jqgrid.reloadMessage(
                        me.grid_id,
                        me.data,
                        me.complete_fun
                    );
                };
                me.clsComFnc.FncMsgBox(
                    "I9999",
                    "ファイルアップロードが完了しました。"
                );
                return;
            } else {
                if (result["error"] == "W0025") {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(".HDKAttachment.pnlList").hide();
                        gdmz.common.jqgrid.reloadMessage(
                            me.grid_id,
                            me.data,
                            me.complete_fun
                        );
                    };
                    me.clsComFnc.FncMsgBox("W0025");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.efunc = function () {};

    //**********************************************************************
    //処 理 名：削除ボタンクリックの設定
    //関 数 btnDelete_Click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：選択されたら ファイル削除処理を実行する。
    //**********************************************************************
    me.btnDelete_Click = function () {
        var SelectRow = $(me.grid_id).jqGrid("getGridParam", "selrow");
        var rowData = $(me.grid_id).jqGrid("getRowData", SelectRow);
        var data = {
            SYOHY_NO: me.SYOHY_NO,
            EDA_NO: me.EDA_NO,
            GYO_NO: me.GYO_NO,
            SEQ: rowData["SEQ"],
            CONST_ADMIN_PTN_NO: me.HDKAIKEI.CONST_ADMIN_PTN_NO,
            CONST_HONBU_PTN_NO: me.HDKAIKEI.CONST_HONBU_PTN_NO,
        };

        var url = me.sys_id + "/" + me.id + "/" + "btnDelete_Click";

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                    $(".HDKAttachment.pnlList").hide();
                    gdmz.common.jqgrid.reloadMessage(
                        me.grid_id,
                        me.data,
                        me.complete_fun
                    );
                };
                me.clsComFnc.FncMsgBox("I0017");
                return;
            } else {
                if (result["error"] == "W0025") {
                    me.clsComFnc.MsgBoxBtnFnc.Close = function () {
                        $(".HDKAttachment.pnlList").hide();
                        gdmz.common.jqgrid.reloadMessage(
                            me.grid_id,
                            me.data,
                            me.complete_fun
                        );
                    };
                    me.clsComFnc.FncMsgBox("W0025");
                } else {
                    me.clsComFnc.FncMsgBox("E9999", result["error"]);
                }
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    //**********************************************************************
    //処 理 名：プレビューボタンクリックの設定
    //関 数 preview_click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：選択したPDFをプレビュー表示する。
    //**********************************************************************
    preview_click = function (fileName) {
        var url = window.location.href;
        url = url.replace("#", "");
        $(".HDKAttachment.pnlList").show();
        var href =
            url +
            "js/common/PDF.js/web/viewer.html?file=" +
            url +
            "files/HDKAIKEI/" +
            me.SYOHY_NO +
            "/" +
            fileName +
            "&DEFAULT_SCALE_VALUE='page-width'";
        $(".HDKAttachment.temp").attr("src", href);
    };

    //**********************************************************************
    //処 理 名：ダウンロードボタンクリックの設定
    //関 数 download_click
    //引    数：無し
    //戻 り 値：無し
    //処理説明：選択したPDFをダウンロードする。
    //**********************************************************************
    download_click = function (fileName) {
        var url = window.location.href;
        url = url.replace("#", "");
        var href = url + "files/HDKAIKEI/" + me.SYOHY_NO + "/" + fileName;
        me.download(href, fileName);
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
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_HDKAIKEI_HDKAttachment = new HDKAIKEI.HDKAttachment();
    o_HDKAIKEI_HDKAttachment.load();
});
