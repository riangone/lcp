/**
 * 説明：
 *
 *
 * @author wangying
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD            #ID                          XXXXXX                          FCSDL
 * 20201117            bug                          プレビューのダイアログ：イメージが存在していないと、様式がChrome・IEと違っています。       WL
 * * --------------------------------------------------------------------------------------------
 */
Namespace.register("PPRM.PPRMjpgView");

PPRM.PPRMjpgView = function () {
    // ==========
    // = 宣言 start =
    // ==========
    var me = new gdmz.base.panel();
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";

    // ========== 変数 start ==========

    me.ajax = new gdmz.common.ajax();
    me.id = "PPRMjpgView";
    me.sys_id = "PPRM";
    me.url = "";
    me.strMODE = "";
    me.strTCD = "";
    me.strHNO = "";
    me.strID = "";

    // ========== 変数 end ==========

    //引数を画面に表示する
    var localStorage = window.localStorage;
    var requestdata = JSON.parse(localStorage.getItem("requestdata"));

    if (requestdata) {
        me.strMODE = requestdata["MODE"];
        me.strTCD = requestdata["TCD"];
        me.strHNO = requestdata["HNO"];
        me.strID = requestdata["ID"];
    }
    localStorage.removeItem("requestdata");

    if (me.strMODE != "" && me.strMODE != undefined && me.strMODE != null) {
        $(".PPRMjpgView.body").dialog({
            autoOpen: false,
            width: 960,
            //20170906 ZHANGXIAOLEI UPD S
            //height : 750,
            height: me.ratio === 1.5 ? 540 : 600,
            //20170906 ZHANGXIAOLEI UPD E
            modal: true,
            title: "プレビュー画面",
            open: function () {},
            close: function () {
                $(".PPRMjpgView.body").remove();
            },
        });
        $(".PPRMjpgView.body").dialog("open");
    }

    // ========== 関数 start ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
    };

    var base_load = me.load;
    me.load = function () {
        base_load();

        if (me.strMODE == "0") {
            var url = me.sys_id + "/" + me.id + "/" + "fncImgPath1";
            var arr = {
                ID: me.strID,
            };
            var data = {
                request: arr,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] && result["row"] > 0) {
                    var strPass = clsComFnc.FncNv(
                        result["data"][0]["SAVE_PATH"]
                    );
                    if (strPass.endsWith("/")) {
                        strPass = strPass.slice(0, strPass.length - 1);
                    }
                    //20201117 WL INS S
                    var img = new Image();
                    img.src = strPass;
                    img.onload = function () {
                        if (img.width > 0 || img.height > 0) {
                            //20201117 WL INS E
                            $(".PPRMjpgView.Image1").css("display", "block");
                            $(".PPRMjpgView.Image1").prop("src", strPass);
                            //20201117 WL INS S
                        }
                    };
                    //20201117 WL INS E
                }
            };
            me.ajax.send(url, data, 0);
        } else {
            var url = me.sys_id + "/" + me.id + "/" + "fncImgPath2";
            var arr = {
                TENPO_CD: me.strTCD,
                TEN_HJM_NO: me.strHNO,
            };
            var data = {
                request: arr,
            };
            me.ajax.receive = function (result) {
                result = eval("(" + result + ")");

                if (result["result"] && result["row"] > 0) {
                    for (
                        let index = 0;
                        index < (result["row"] >= 100 ? 100 : result["row"]);
                        index++
                    ) {
                        const element = result["data"][index];
                        var strPass = clsComFnc.FncNv(element["SAVE_PATH"]);
                        if (strPass.endsWith("/")) {
                            strPass = strPass.slice(0, strPass.length - 1);
                        }
                        var img = new Image();
                        img.src = strPass;
                        img.onload = (function (currentStrPass) {
                            return function () {
                                if (img.width > 0 || img.height > 0) {
                                    $(".PPRMjpgView.Image" + (index + 1)).css(
                                        "display",
                                        "block"
                                    );
                                    $(".PPRMjpgView.Image" + (index + 1)).prop(
                                        "src",
                                        currentStrPass
                                    );
                                }
                            };
                        })(strPass);
                    }
                }
            };
            me.ajax.send(url, data, 0);
        }
    };
    // ========== 関数 end ==========

    return me;
};

$(function () {
    var o_PPRM_PPRMjpgView = new PPRM.PPRMjpgView();
    o_PPRM_PPRMjpgView.load();
    o_PPRM_PPRM.PPRMjpgView = o_PPRM_PPRMjpgView;
});
