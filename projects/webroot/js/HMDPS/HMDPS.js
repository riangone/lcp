/**
 * HMDPS
 * @alias  HMDPS
 * @author FCSDL
 */
Namespace.register("HMDPS.HMDPS");

HMDPS.HMDPS = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "HMDPS";
    me.sys_id = "HMDPS";
    me.CONST_ADMIN_PTN_NO = "000";
    me.CONST_HONBU_PTN_NO = "001";

    // ========== 変数 end ==========

    // ========== コントロール start ==========

    var base_init_control = me.init_control;
    me.init_control = function () {
        base_init_control();
    };

    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    var base_load = me.load;
    me.load = function () {
        base_load();
        var frmId = "FrmHMDPSMainMenu";
        var url = frmId;
        url = "HMDPS" + "/" + url;
        $.ajax({
            type: "POST",
            url: url,
            data: {
                url: url,
            },
            success: function (result) {
                $(".HMDPS.HMDPS-layout-west").html(result);
                $(".HMDPS.HMDPS-loading-icon").hide();
                $(".ui-widget-content.HMDPS.HMDPS-layout-west").css(
                    "overflow",
                    "scroll"
                );
            },
        });
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    //ShiftキーとTabキーのバインド
    me.Shift_TabKeyDown = function (form_id) {
        var $inp = $(".Tab[tabindex]");
        //20210512 zhangxiaolei ins s
        if (form_id) {
            $inp = $("." + form_id).find(".Tab[tabindex]");
        }
        //20210512 zhangxiaolei ins e

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == true) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                // 20210517 lqs ins s
                $inp_enabled = $inp_enabled.filter(":visible");
                // 20210517 lqs ins e
                var nxtIdx = Number($inp_enabled.index(this));
                if (nxtIdx == 0) {
                    //first one : init
                    nxtIdx = $inp_enabled.length;
                }
                $inp_enabled.eq(nxtIdx - 1).select();
                $inp_enabled.eq(nxtIdx - 1).trigger("focus");
            }
        });
    };
    //Tabキーのバインド
    me.TabKeyDown = function (form_id) {
        var $inp = $(".Tab[tabindex]");
        //20210512 zhangxiaolei ins s
        if (form_id) {
            $inp = $("." + form_id).find(".Tab[tabindex]");
        }
        //20210512 zhangxiaolei ins e

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });

        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == false) {
                e.preventDefault();

                var $inp_enabled = $inp.filter(":enabled");
                // 20210517 lqs ins s
                $inp_enabled = $inp_enabled.filter(":visible");
                // 20210517 lqs ins e
                var nxtIdx = Number($inp_enabled.index(this)) + 1;
                if (nxtIdx == $inp_enabled.length) {
                    //last one : init
                    nxtIdx = 0;
                }
                $inp_enabled.eq(nxtIdx).select();
                $inp_enabled.eq(nxtIdx).trigger("focus");

                //20210512 zhangxiaolei ins s
                e.stopPropagation();
                //20210512 zhangxiaolei ins e
            }
        });
    };
    //Enterキーのバインド
    me.EnterKeyDown = function (form_id) {
        var $inp = $(".Enter[tabindex]");
        //20210512 zhangxiaolei ins s
        if (form_id) {
            $inp = $("." + form_id).find(".Enter[tabindex]");
        }
        //20210512 zhangxiaolei ins e

        $inp.sort(function (ctrl1, ctrl2) {
            return (
                Number($(ctrl1).attr("tabindex")) -
                Number($(ctrl2).attr("tabindex"))
            );
        });
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();

                    var $inp_enabled = $inp.filter(":enabled");
                    // 20210517 lqs ins s
                    $inp_enabled = $inp_enabled.filter(":visible");
                    // 20210517 lqs ins e
                    var nxtIdx = Number($inp_enabled.index(this)) + 1;
                    if (nxtIdx == $inp_enabled.length) {
                        //last one : init
                        nxtIdx = 0;
                    }
                    $inp_enabled.eq(nxtIdx).select();
                    $inp_enabled.eq(nxtIdx).trigger("focus");
                }
            }
        });
    };
    // 禁則文字をチェック
    me.KinsokuMojiCheck = function (objTextBox, clsComFnc) {
        var reg = RegExp(/<|>|'/);
        if (objTextBox.val() != "") {
            if (reg.test(objTextBox.val())) {
                clsComFnc.ObjFocus = objTextBox;
                clsComFnc.FncMsgBox("W9999", "禁則文字が入力されています。");
                return false;
            }
        }

        return true;
    };
    me.halfToFull = function (str) {
        var fullStr = "";
        var strTemp = "";
        for (var i = 0; i < str.length; i++) {
            //複合仮名（三つ文字ギャ）半角から全角転換
            strTemp = me.toThreeChar(str.substr(i, 3));
            if (strTemp != "") {
                fullStr = fullStr + strTemp;
                i += 2;
            } else {
                //複合仮名（二文字キャ）半角から全角転換
                strTemp = me.toTwoChar(str.substr(i, 2));
                if (strTemp != "") {
                    fullStr = fullStr + strTemp;
                    i += 1;
                } else {
                    //仮名（二文字ガ）半角から全角転換
                    strTemp = me.toOneChar(str.substr(i, 2));
                    if (strTemp != "") {
                        fullStr = fullStr + strTemp;
                        i += 1;
                    } else {
                        //普通仮名（1文字ア）半角から全角転換
                        strTemp = me.toChar(str.substr(i, 1));
                        if (strTemp != "") {
                            fullStr = fullStr + strTemp;
                        } else {
                            //横棒(－)から縦棒(｜)転換
                            strTemp = me.toLine(str.substr(i, 1));
                            if (strTemp != "") {
                                fullStr = fullStr + strTemp;
                            } else {
                                //仮名以外1文字
                                // strTemp = str.substr(i, 1).trim();
                                // if (strTemp != "")
                                {
                                    fullStr = fullStr + str.substr(i, 1);
                                }
                            }
                        }
                    }
                }
            }
        }
        return fullStr;
    };
    //複合仮名（三つ文字）半角から全角転換
    me.toThreeChar = function (str) {
        var strTempFull = "";
        var strTempHalf = "";
        strTempFull = "ギャギュギョジャジュジョビャビュビョピャピュピョ";
        strTempHalf = "ｷﾞｬｷﾞｭｷﾞｮｼﾞｬｼﾞｭｼﾞｮﾋﾞｬﾋﾞｭﾋﾞｮﾋﾟｬﾋﾟｭﾋﾟｮ";
        for (var i = 0; i < strTempHalf.length; i += 3) {
            var str2 = "";
            str2 = strTempHalf.substr(i, 3);
            if (str === str2) {
                var chrTemp = strTempFull.split("");
                return chrTemp[0] + "<br />" + chrTemp[1];
            }
        }
        return "";
    };
    //複合仮名（二文字）半角から全角転換
    me.toTwoChar = function (str) {
        var strTempFull = "";
        var strTempHalf = "";

        strTempFull =
            "キャキュキョシャシュショチャチュチョニャニュニョヒャヒュヒョミャミュミョリャリュリョ";
        strTempHalf = "ｷｬｷｭｷｮｼｬｼｭｼｮﾁｬﾁｭﾁｮﾆｬﾆｭﾆｮﾋｬﾋｭﾋｮﾐｬﾐｭﾐｮﾘｬﾘｭﾘｮ";
        for (var i = 0; i < strTempHalf.length; i += 2) {
            var str2 = "";
            str2 = strTempHalf.substr(i, 2);
            if (str === str2) {
                return strTempFull.substr(i, 2);
            }
        }
        return "";
    };
    //仮名（二文字）半角から全角転換
    me.toOneChar = function (str) {
        var strTempFull = "";
        var strTempHalf = "";

        strTempFull = "ガギグゲゴザジズゼゾダヂヅデドバビブベボパピプペポ";
        strTempHalf = "ｶﾞｷﾞｸﾞｹﾞｺﾞｻﾞｼﾞｽﾞｾﾞｿﾞﾀﾞﾁﾞﾂﾞﾃﾞﾄﾞﾊﾞﾋﾞﾌﾞﾍﾞﾎﾞﾊﾟﾋﾟﾌﾟﾍﾟﾎﾟ";
        for (var i = 0; i < strTempHalf.length; i += 2) {
            var str2 = "";
            str2 = strTempHalf.substr(i, 2);
            if (str === str2) {
                return strTempFull.substr(i / 2, 1);
            }
        }
        return "";
    };
    //普通仮名半角から全角転換
    me.toChar = function (str) {
        if (str == " ") {
            return "　";
        }
        var strTempFull = "";
        var strTempHalf = "";

        strTempFull =
            "アイウエオサシスセソカキクケコタチツテトナニヌネノハヒフヒホマミムメモヤユヨラリルレロワヲン";
        strTempHalf = "ｱｲｳｴｵｻｼｽｾｿｶｷｸｹｺﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉﾊﾋﾌﾋﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝ";
        for (var i = 0; i < strTempHalf.length; i++) {
            var str2 = "";
            str2 = strTempHalf.substr(i, 1);
            if (str === str2) {
                return strTempFull.substr(i, 1);
            }
        }
        return "";
    };
    //横棒(－)から縦棒(｜)転換
    me.toLine = function (str) {
        var strTempFull = "";
        var strTempHalf = "";

        strTempFull = "ーー∧∨ーー";
        strTempHalf = "-－()ｰー";
        for (var i = 0; i < strTempHalf.length; i++) {
            var str2 = "";
            str2 = strTempHalf.substr(i, 1);
            if (str === str2) {
                return strTempFull.substr(i, 1);
            }
        }
        return "";
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    o_HMDPS_HMDPS = new HMDPS.HMDPS();
    o_HMDPS_HMDPS.load();
});
