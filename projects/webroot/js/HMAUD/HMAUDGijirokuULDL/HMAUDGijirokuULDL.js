Namespace.register("HMAUD.HMAUDGijirokuULDL");

HMAUD.HMAUDGijirokuULDL = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    me.clsComFnc = new gdmz.common.clsComFnc();
    me.ajax = new gdmz.common.ajax();
    me.id = "HMAUDGijirokuULDL";
    me.sys_id = "HMAUD";
    me.clsComFnc.GSYSTEM_NAME = "内部統制システム";
    me.HMAUD = new HMAUD.HMAUD();
    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.grid_id = "#HMAUDGijirokuULDL_tblMain";
    me.g_url = me.sys_id + "/" + me.id + "/getFileList";
    me.gennzayiCour = "";
    me.allCourData = "";
    me.isfirstload = true;
    me.isAdmin = false;
    //20230313 CAI INS S
    me.isViewer = false;
    //20230313 CAI INS E

    me.option = {
        rowNum: 0,
        caption: "",
        rownumbers: false,
        loadui: "disable",
        multiselect: true,
    };
    me.colModel = [
        {
            name: "FILE_ID",
            label: "ファイルID",
            index: "FILE_ID",
            width: 10,
            align: "left",
            hidden: true,
        },
        {
            name: "FILENAME",
            label: "ファイル名",
            index: "FILENAME",
            width: 380,
            align: "left",
            sortable: false,
        },
        {
            name: "KEYWORD",
            label: "キーワード",
            index: "KEYWORD",
            width: 200,
            align: "left",
            sortable: false,
        },
    ];

    me.controls.push({
        id: ".HMAUDGijirokuULDL .btn",
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
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //[参照]ﾎﾞﾀﾝクリック
    $(".HMAUDGijirokuULDL.btnDialog").click(function () {
        me.btnDialog_click();
    });
    //アップロードﾎﾞﾀﾝクリック
    $(".HMAUDGijirokuULDL.btnUpload").click(function () {
        me.btnUpload_Click();
    });
    //ダウンロードボタンクリック
    $(".HMAUDGijirokuULDL.btnDownload").click(function () {
        me.btnDownload_Click();
    });
    //削除ボタンクリック
    $(".HMAUDGijirokuULDL.btnDelete").click(function () {
        var rowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        if (rowIds.length == 0 || rowIds[0] == "norecs") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "削除するファイルを選択してください"
            );
            return;
        }
        me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnDelete_Click;
        me.clsComFnc.FncMsgBox(
            "QY999",
            "ファイルを削除します。よろしいですか？"
        );
    });
    $(".HMAUDGijirokuULDL.cours").change(function () {
        me.fncCourChange();
    });
    $(".HMAUDGijirokuULDL.keyword").keypress(function (even) {
        if (even.which == 13) {
            $(".HMAUDGijirokuULDL.keyword").trigger("blur");
            me.fncCourChange();
        }
    });
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
        //フォームロード
        me.HMAUDGijirokuULDL_Load();
    };
    /*
     '**********************************************************************
     '処 理 名：フォームロード
     '関 数 名：HMAUDGijirokuULDL_Load
     '引    数：無し
     '戻 り 値 ：無し
     '処理説明 ：
     '**********************************************************************
     */
    me.HMAUDGijirokuULDL_Load = function () {
        var url = me.sys_id + "/" + me.id + "/" + "Page_load";
        me.ajax.receive = function (result) {
            var result = eval("(" + result + ")");
            if (!result["result"]) {
                //検索
                $(".HMAUDGijirokuULDL .btn").button("disable");
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                return;
            } else {
                $(".HMAUDGijirokuULDL.cours").find("option").remove();
                $("<option></option>")
                    .val("")
                    .text("")
                    .appendTo(".HMAUDGijirokuULDL.cours");
                if (result["data"]["cour"].length > 0) {
                    var courAll = result["data"]["cour"];
                    me.allCourData = courAll;
                    for (var i = 0; i < courAll.length; i++) {
                        //クールselect
                        $("<option></option>")
                            .val(courAll[i]["COURS"])
                            .text(courAll[i]["COURS"])
                            .appendTo(".HMAUDGijirokuULDL.cours");
                        if (courAll[i]["COURS_NOW"] == "1") {
                            //現在のクール数
                            me.gennzayiCour = courAll[i]["COURS"];
                        }
                    }
                    if (gdmz.SessionCour != undefined) {
                        $(".HMAUDGijirokuULDL.cours").val(gdmz.SessionCour);
                        delete gdmz.SessionCour;
                    } else {
                        $(".HMAUDGijirokuULDL.cours").val(me.gennzayiCour);
                    }

                    if (result["data"]["admin"].length > 0) {
                        me.isAdmin = true;
                    }
                    //20230313 CAI INS S
                    if (result["data"]["viewer"].length > 0) {
                        me.isViewer = true;
                    }
                    //20230313 CAI INS E
                    me.fncCourChange();
                } else {
                    $(".HMAUDGijirokuULDL .btn").button("disable");
                }
            }
        };
        me.ajax.send(url, "", 0);
        $(".HMAUDGijirokuULDL.cours").trigger("focus");
    };
    me.btnDialog_click = function () {
        //参照ボタンcmdOpen_Click
        me.file = new gdmz.common.file();
        me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
        //		me.file.accept = ".xlsx,.xls,.pdf,.doc";
        me.file.accept = ".xlsx,.xls,.pdf,.docx,.doc,.pptx,.ppt,.zip";

        $("#tmpFileUpload").html("");
        $(".HMAUDGijirokuULDL.txtFile").val("");
        $("#tmpFileUpload").append(me.file.create());
        $("#file").change(function () {
            var i = 0;
            var arr = this.files[i].name.split(".");
            var filelong = arr.length;
            filelong = filelong - 1;
            var fileType = arr[filelong].toLowerCase();
            //			if (this.files[i].size > 2048000)
            if (this.files[i].size > 5120000) {
                //				me.clsComFnc.FncMsgBox("W9999", "添付可能なファイルサイズは、最大 2000KB です。");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "添付可能なファイルサイズは、最大 5MB です。"
                );
                return;
            }
            //			if (fileType != 'xlsx' && fileType != 'pdf' && fileType != 'doc' && fileType != 'xls')
            if (
                fileType != "xlsx" &&
                fileType != "xls" &&
                fileType != "pdf" &&
                fileType != "docx" &&
                fileType != "doc" &&
                fileType != "pptx" &&
                fileType != "ppt" &&
                fileType != "zip"
            ) {
                //				me.clsComFnc.FncMsgBox("W9999", "指定されたファイルはxlsx,xls,pdf,doc形式のファイルではありません。");
                me.clsComFnc.FncMsgBox(
                    "W9999",
                    "指定されたファイルはxlsx,xls,pdf,docx,doc,pptx,ppt,zip形式のファイルではありません。"
                );
                return;
            }
            $(".HMAUDGijirokuULDL.txtFile").val(this.files[i].name);
        });
        me.file.select_file();
    };
    me.func = function (err) {
        if (err) {
            $(".HMAUDGijirokuULDL.txtFile").val("");
            me.file = new gdmz.common.file();
            me.file.action = me.sys_id + "/" + me.id + "/fncCheckFile";
            me.file.accept = ".xlsx,.pdf,.doc";
            $("#tmpFileUpload").html("");
            $("#tmpFileUpload").append(me.file.create());
            me.file.send(me.func);
            return;
        }
        var url = me.sys_id + "/" + me.id + "/" + "btnAction_Click";
        var data = {
            COURS: $(".HMAUDGijirokuULDL.cours").val(),
            txtPath: $(".HMAUDGijirokuULDL.txtFile").val(),
            keyword: $(".HMAUDGijirokuULDL.searchKeyword").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox(
                    "I9999",
                    "ファイルアップロードが完了しました"
                );
                $(".HMAUDGijirokuULDL.txtFile").val("");
                $(".HMAUDGijirokuULDL.searchKeyword").val("");
                me.fncCourChange();
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".HMAUDGijirokuULDL.txtFile").val("");
                $(".HMAUDGijirokuULDL.searchKeyword").val("");
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    me.efunc = function () {
        $(".HMAUDGijirokuULDL.txtFile").val("");
    };
    me.btnUpload_Click = function () {
        if (me.InpuetCheck()) {
            me.IsExstsFile();
        }
    };
    //ファイルアップロード完了
    me.btnUpload_Click_Check = function () {
        me.file.send(me.func, me.efunc);
    };

    me.InpuetCheck = function () {
        var cours = $(".HMAUDGijirokuULDL.cours").val();
        if (cours.trimEnd() == "") {
            me.clsComFnc.ObjFocus = $(".HMAUDGijirokuULDL.cours");
            me.clsComFnc.FncMsgBox("W9999", "クールを指定してください。");
            return false;
        }
        var FileName = $(".HMAUDGijirokuULDL.txtFile").val();
        if (FileName.trimEnd() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "取込対象のファイルを指定してください。"
            );
            return false;
        }
        var keyword = $(".HMAUDGijirokuULDL.searchKeyword").val();
        if (keyword.trimEnd() == "") {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "検索キーワードを入力してください。"
            );
            return false;
        }
        return true;
    };
    me.fncCourChange = function () {
        var cour = $(".HMAUDGijirokuULDL.cours").val();
        var foundDT = undefined;
        if (cour) {
            if (me.allCourData) {
                var foundDT_array = me.allCourData.filter(function (element) {
                    return element["COURS"] == cour;
                });
                if (foundDT_array.length > 0 && cour !== "") {
                    foundDT = foundDT_array[0];
                    $(".HMAUDGijirokuULDL.courPeriod").html(
                        foundDT ? foundDT["PERIOD"] : "&nbsp;"
                    );
                    var data = {
                        COUR: cour,
                        keyword: $(".HMAUDGijirokuULDL.keyword")
                            .val()
                            .trimEnd(),
                    };
                    if (me.isfirstload == true) {
                        gdmz.common.jqgrid.showWithMesgScroll(
                            me.grid_id,
                            me.g_url,
                            me.colModel,
                            "",
                            "",
                            me.option,
                            data,
                            me.complete_fun
                        );
                        $(me.grid_id).jqGrid("bindKeys");
                        gdmz.common.jqgrid.set_grid_width(me.grid_id, 650);
                        me.setTableSize();
                        me.isfirstload = false;
                    } else {
                        gdmz.common.jqgrid.reloadMessage(
                            me.grid_id,
                            data,
                            me.complete_fun
                        );
                    }
                } else {
                    $(me.grid_id).jqGrid("clearGridData");
                }
            } else {
                $(me.grid_id).jqGrid("clearGridData");
            }
        } else {
            $(".HMAUDGijirokuULDL.courPeriod").html("&nbsp;");
            $(me.grid_id).jqGrid("clearGridData");
            $(".HMAUDGijirokuULDL .btn").button("disable");
        }
    };
    me.setTableSize = function () {
        var mainHeight = $(".HMAUD.HMAUD-layout-center").height();
        var tableHeight = mainHeight - (me.ratio === 1.5 ? 280 : 345);
        gdmz.common.jqgrid.set_grid_height(me.grid_id, tableHeight);
    };
    me.complete_fun = function (_returnFLG, data) {
        if (data["error"] && data["error"] !== "") {
            $(".HMAUDGijirokuULDL .btn").button("disable");
            me.clsComFnc.FncMsgBox("E9999", data["error"]);
            return;
        }
        if (data["member"].length > 0 || me.isAdmin == true) {
            $(".HMAUDGijirokuULDL .btn").button("enable");
        }
        //20230313 CAI INS S
        else if (me.isViewer == true) {
            $(".HMAUDGijirokuULDL .btnDownload").button("enable");
            $(".HMAUDGijirokuULDL .btnDialog").button("disable");
            $(".HMAUDGijirokuULDL .btnUpload").button("disable");
            $(".HMAUDGijirokuULDL .btnDelete").button("disable");
        }
        //20230313 CAI INS E
        else {
            $(".HMAUDGijirokuULDL .btn").button("disable");
        }
    };
    me.IsExstsFile = function () {
        var url = me.sys_id + "/" + me.id + "/" + "IsExstsFile";
        var data = {
            COURS: $(".HMAUDGijirokuULDL.cours").val(),
            txtPath: $(".HMAUDGijirokuULDL.txtFile").val(),
            keyword: $(".HMAUDGijirokuULDL.searchKeyword").val(),
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                if (result["data"] == "IsExstsFile") {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpload_Click_Check;
                    me.clsComFnc.FncMsgBox(
                        "QY999",
                        "同名ファイルが存在しています。上書きしてよろしいですか？"
                    );
                } else {
                    me.clsComFnc.MsgBoxBtnFnc.Yes = me.btnUpload_Click_Check;
                    me.clsComFnc.FncMsgBox("QY005");
                }
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
                $(".HMAUDGijirokuULDL.txtFile").val("");
                return;
            }
        };
        me.ajax.send(url, data, 0);
    };
    // **********************************************************************
    // 処 理 名：ダウンロードボタンクリック
    // 関 数 名：btnDownload_Click
    // 戻 り 値：なし
    // 処理説明：ファイル一覧で選択チェックOnのファイルをダウンロードする
    // **********************************************************************
    me.btnDownload_Click = function () {
        var cour = $(".HMAUDGijirokuULDL.cours").val();
        var fileUrl = "";
        var rowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        if (rowIds.length > 0 && rowIds[0] !== "norecs") {
            for (var k = 0; k < rowIds.length; k++) {
                var rowData = $(me.grid_id).jqGrid("getRowData", rowIds[k]);
                fileUrl = "files/HMAUD/" + cour + "/" + rowData["FILENAME"];
                me.download(fileUrl, rowData["FILENAME"]);
            }
        } else {
            me.clsComFnc.FncMsgBox(
                "W9999",
                "ダウンロードしたいファイルを選択してください。"
            );
        }
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
    me.btnDelete_Click = function () {
        var rowIds = $(me.grid_id).jqGrid("getGridParam", "selarrrow");
        var rowDatas = "";
        for (var i = 0; i < rowIds.length; i++) {
            var rowData = $(me.grid_id).jqGrid("getRowData", rowIds[i]);
            rowDatas += ",'" + rowData["FILE_ID"] + "'";
        }
        rowDatas = rowDatas.substring(1);

        var url = me.sys_id + "/" + me.id + "/" + "btnDelete_Click";
        var data = {
            data: rowDatas,
        };

        me.ajax.receive = function (result) {
            result = eval("(" + result + ")");
            if (result["result"] == true) {
                me.clsComFnc.FncMsgBox("I0017");
                me.fncCourChange();
                return;
            } else {
                me.clsComFnc.FncMsgBox("E9999", result["error"]);
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
    o_HMAUDGijirokuULDL_HMAUDGijirokuULDL = new HMAUD.HMAUDGijirokuULDL();
    o_HMAUDGijirokuULDL_HMAUDGijirokuULDL.load();
});
