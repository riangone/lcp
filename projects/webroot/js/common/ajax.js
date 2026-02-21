/**
 * クライアント共通関数
 * @author FCSDL
 */
Namespace.register("gdmz.common.ajax");

gdmz.common.ajax = function () {
    var me = new Object();
    me.clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // $("#indexLoading").dialog(
    // {
    // width : 40,
    // resizable : false,
    // height : 73,
    // autoOpen : false,
    // modal : true,
    // closeOnEscape : false,
    // //stack : false,
    // dialogClass : "no-title",
    // draggable : false,
    // });
    //------20141204  fanzhengzhou ins s.
    $("#SDH_session_outdate").dialog({
        width: 500,
        height: 150,
        resizable: false,
        autoOpen: false,
        modal: true,
        closeOnEscape: false,
        classes: {
            "ui-dialog": "RemoveCloseMark",
        },
        open: function () {
            $(".RemoveCloseMark .ui-dialog-titlebar-close").hide();
        },
        close: function () {
            me.relogin();
        },
        buttons: {
            OK: function () {
                $(this).dialog("close");
            },
        },
    });
    //------20141204  fanzhengzhou ins s.
    // ========== 変数 start ==========

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    //20131213 fan
    //"initmark":If this operating of ajax happened when init, set this parameter=1,else parameter=0.
    //20190514 YIN UPD S
    // me.send = function(url, data, init_mark)
    //20201113 YIN UPD S
    // me.send = function(url, data, init_mark,asyncTF)
    me.send = function (
        url,
        data,
        init_mark //20201113 YIN UPD E //20190514 YIN UPD E
    ) {
        $.ajax({
            //type : me.type,
            type: "POST",
            url: url,
            //20180514 YIN INS S
            //20201113 YIN DEL S
            // async : asyncTF,
            //20201113 YIN DEL E
            //20180514 YIN INS E
            //dataType : me.dataType,
            data: {
                data: data,
            },
            beforeSend: function () {
                me.ShowLoading();
                // alert('1');
            },
            success: function (result) {
                try {
                    if (result == null) {
                        throw "result is null!";
                    } else if (result == undefined) {
                        throw "result is undefined!";
                    }
                    if (
                        result == '{"result":true,"data":"session is outdate"}'
                    ) {
                        me.CloseLoading();
                        //20141017 sdh fanzhengzhou add s.
                        //----20141209 NO.54 fanzhengzhou upd s. For session out time when change tab.
                        //if (currentTabId == "#tabs_SDH")
                        if (
                            currentTabId == "#tabs_SDH" &&
                            LoadTabFlag == false
                        ) {
                            //----20141209 NO.54 fanzhengzhou upd e.
                            //------20141204  fanzhengzhou upd s.
                            //me.clsComFnc.MsgBoxBtnFnc.Yes = me.relogin;
                            //me.clsComFnc.MessageBox("セッションがタイムアウトしました。もう一度ログインしてください。", 'SDH', "OK", "Information", me.clsComFnc.MessageBoxIcon.Information);
                            $("#SDH_session_outdate").dialog(
                                "option",
                                "title",
                                "SDH"
                            );
                            $("#SDH_session_outdate").dialog("open");
                            //------20141204  fanzhengzhou upd e.
                        }
                        //20141017 sdh fanzhengzhou add e.
                        else if (init_mark == 0) {
                            me.beforeLogin();
                            $("#sessionoutdate").dialog("open");
                            client = $(".LogineduserID").html();
                            $("#sessionoutuser").val(client);
                            $("#sessionoutpassword").focus();
                        }
                    } else {
                        me.CloseLoading();
                        me.receive(result);
                    }
                } catch (err) {
                    console.log(result);
                    result = null;
                    console.log("Error: " + err);
                }
            },
            error: function () {
                alert("error");
                me.error();
                me.CloseLoading();
            },
        });
    };

    me.receive = function () {};

    me.beforeLogin = function () {};

    me.ShowLoading = function () {
        // $.blockUI();
        // 20230523 wangying ins s
        const windowWidth = $(window).width();
        const loadingWidth = 200;
        const left = (windowWidth - loadingWidth) / 2;
        // 20230523 wangying ins e
        $.blockUI({
            css: {
                border: "none",
                padding: "10px",
                backgroundColor: "#fff",
                "-webkit-border-radius": "8px",
                "-moz-border-radius": "8px",
                top: "45%",
                // 20230523 wangying upd s
                // left: "40%",
                left: left,
                color: "#000",
                // width: "200px",
                // 20230523 wangying upd e
                width: loadingWidth + "px",
                zIndex: 102,
            },
            message:
                '<img src="img/1.gif" width="64" height="64" /><br /><B>読み込み中...</B>',
            baseZ: 102,
        });

        //$.blockUI({ message: "<h1>LOADING...</h1>" });
        // $("#indexLoading").dialog('open');
        // $("#indexLoading").blur();
    };

    me.CloseLoading = function () {
        $.unblockUI();
        // $("#indexLoading").dialog('close');
        // $("#indexLoading").blur();
    };

    me.error = function () {};
    //2014/10/20 fan add start.
    me.relogin = function () {
        var url = "Login/Login/loginout";
        $.ajax({
            type: "POST",
            url: url,
            success: function (result) {
                result = $.parseJSON(result);
                if (result["result"] == "false") {
                    alert("loginoutfail");
                } else {
                    var frmId = "Login";
                    var url = "Login" + "/" + frmId;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            url: url,
                        },
                        success: function (result) {
                            $("body").html(result);
                        },
                    });
                }
            },
        });
    };
    //2014/10/20 fan add end.
    // ==========
    // = メソッド end =
    // ==========

    return me;
};
