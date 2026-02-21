/**
 * 説明：
 * @author wangying,liqiushuang
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                 Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("APPM.FrmPurebyu");

APPM.FrmPurebyu = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();

    // ========== 変数 start ==========

    me.id = "FrmPurebyu";
    me.sys_id = "APPM";
    me.title = "メッセージプレビュー";

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    me.controls.push({
        id: ".FrmPurebyu.btnKuta",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPurebyu.btnContact",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPurebyu.btnShich",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPurebyu.btnRuku",
        type: "button",
        handle: "",
    });
    me.controls.push({
        id: ".FrmPurebyu.btnClose",
        type: "button",
        handle: "",
    });
    // ========== コントロール end ==========

    // ========== イベント start ==========
    //[閉じる]ボタンクリック
    $(".FrmPurebyu.btnClose").click(function () {
        me.btnClose();
    });
    // ========== イベント end ==========

    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.strFLG = requestdata["FLG"];
        me.strMODE = requestdata["MODE"];
        me.strIMG1 = requestdata["img"];
        me.strIMG = requestdata["txtImg"];
        me.strIMGURL = requestdata["imgUrl"];
        me.strSHAKEN = requestdata["txtShaken"];
        me.txtSHAKEN = requestdata["txtShaken1"];
        me.strSHARYO = requestdata["txtSharyo"];
        me.txtSHARYO = requestdata["txtSharyo1"];
        me.strTITLE = requestdata["txtTitle"];
        me.strMSG1 = requestdata["txtMessage1"];
        me.strMSG2 = requestdata["txtMessage2"];
        me.strMSG3 = requestdata["txtMessage3"];
        me.strRINKU = requestdata["txtRinku"];
        me.txtRINKU = requestdata["txtRinku1"];
        me.strSHI = requestdata["txtShi"];
        me.txtSHI = requestdata["txtShi1"];
        me.strRU = requestdata["txtRu"];
        me.txtRU = requestdata["txtRu1"];
    }

    localStorage.removeItem("requestdata");

    me.before_close = function () {};

    if (me.strFLG == "1") {
        var height = 570;
        if (me.strRINKU == "01") {
            height += 35;
        }
        if (me.strSHI == "01") {
            height += 35;
        }
        if (me.strRU == "01") {
            height += 35;
        }
        $(".FrmPurebyu.body").dialog({
            autoOpen: false,
            resizable: true,
            width: 385,
            height: height,
            modal: true,
            title: me.title,
            open: function () {},
            close: function () {
                me.before_close();
                $(".FrmPurebyu.body").remove();
            },
        });
        $(".FrmPurebyu.body").dialog("open");
    } else {
        $(".FrmPurebyu.body").remove();
    }
    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();
        if (me.strSHAKEN == "00") {
            $(".FrmPurebyu.txtShaken").text("");
        } else {
            $(".FrmPurebyu.txtShaken").text(me.txtSHAKEN);
        }
        if (me.strSHARYO == "01") {
            $(".FrmPurebyu.txtSharyo").text("（車両情報）");
        }

        $(".FrmPurebyu.txtTitle").text(me.strTITLE);
        $(".FrmPurebyu.txtMessage1").text(me.strMSG1);
        $(".FrmPurebyu.txtMessage2").text(me.strMSG2);
        $(".FrmPurebyu.txtMessage3").text(me.strMSG3);

        var locationUrl = window.location.href;
        if (me.strIMG1 != "") {
            $(".FrmPurebyu.lblImg").attr(
                "src",
                locationUrl +
                    "/webroot/temp/" +
                    me.strIMG1 +
                    "?t=" +
                    Math.random()
            );
            if (me.strIMGURL != "") {
                if (me.strIMGURL.indexOf("http") == 0) {
                    $(".FrmPurebyu.imgLink").attr("href", me.strIMGURL);
                } else {
                    $(".FrmPurebyu.imgLink").attr(
                        "href",
                        "http://" + me.strIMGURL
                    );
                }
            }
        } else if (me.strIMG != "") {
            $(".FrmPurebyu.lblImg").attr(
                "src",
                locationUrl +
                    "/webroot/temp/" +
                    me.strIMG +
                    "?t=" +
                    Math.random()
            );
            if (me.strIMGURL != "") {
                if (me.strIMGURL.indexOf("http") == 0) {
                    $(".FrmPurebyu.imgLink").attr("href", me.strIMGURL);
                } else {
                    $(".FrmPurebyu.imgLink").attr(
                        "href",
                        "http://" + me.strIMGURL
                    );
                }
            }
        }
        $(".FrmPurebyu.imgLink").attr("target", "_blank");
        if (me.strRINKU == "01") {
            $(".FrmPurebyu.btnContact").css("display", "block");
        }
        if (me.strSHI == "01") {
            $(".FrmPurebyu.btnShich").css("display", "block");
        }
        if (me.strRU == "01") {
            $(".FrmPurebyu.btnRuku").css("display", "block");
        }
    };

    //'**********************************************************************
    //'処 理 名：[閉じる]ボタンクリック
    //'関 数 名：me.btnClose
    //'引 数 　：なし
    //'戻 り 値：なし
    //'処理説明：
    //'**********************************************************************
    me.btnClose = function () {
        $(".FrmPurebyu.body").dialog("close");
    };
    // ========== 関数 end ==========

    return me;
};

$(function () {
    o_APPM_FrmPurebyu = new APPM.FrmPurebyu();
    o_APPM_FrmPurebyu.load();
    o_APPM_APPM.FrmPurebyu = o_APPM_FrmPurebyu;
});
