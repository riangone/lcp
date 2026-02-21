/**
 * 説明：
 *
 *
 * @author zhenghuiyun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                       Feature/Bug                    内容                          担当
 * YYYYMMDD           #ID                               XXXXXX                   FCSDL
 * 20141017                                          判定文変更履歴の「変更履歴」文字削除       jinmingai
 * 20141202           No.34                       記号が表示しないとき、クリックできる　jinmingai
 * 20141218           No.66                                                          　fuxiaolin
 * 20160722           -----                      夜間処理更新者を「不明」から「システム」へ変更      HM
 * 20201117           BUG                         textareaの単文字数が多すぎて、異常に表示される           　ciyuanchen
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH05");

gdmz.SDH.SDH05 = function () {
    var me = new gdmz.base.panel_dialog();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH05";
    me.sys_id = "SDH";

    me.width = 500;
    me.height = 300;

    me.dialog_title = "判定文変更履歴";

    me.html_id = ".sdh.sdh05.dialog";
    me.parent = ".sdh.sdh01.dialog_area";

    me.data = null;

    // ========== 変数 end ==========

    // ========== コントロール start ==========

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
    };

    me.init_control = function () {
        $(me.html_id).dialog({
            autoOpen: false,
            height: me.height,
            width: me.width,
            modal: true,
            title: me.dialog_title,
            resizable: false,
        });
    };

    me.open = function () {
        if (!me.data) {
            return;
        }
        //20240802 caina upd s
        // else
        else if (me.data.length) {
            //20240802 caina upd e
            me.data = JSON.parse(me.data);
        }

        var idx = me.data.idx;
        var data = me.data.data;

        var html_01_str = "";

        ymdhm = "";

        html_01_str = "";
        html_01_str += "<table style='width:100%' border='0'>";

        html_01_str += "<tr>";
        html_01_str += "<td style='width:160px;color:gray' align='center'>";
        html_01_str += "日 時";
        html_01_str += "</td>";
        html_01_str += "<td align='center' style='color:gray'>";
        html_01_str += "内 容";
        html_01_str += "</td>";
        html_01_str += "<td style='width:85px;color:gray' align='center'>";
        html_01_str += "変更者";
        html_01_str += "</td>";
        html_01_str += "</tr>";

        hantei_bef = "";
        hantei_bef_name = "";

        if (idx == 8) {
            for (k = 0; k < data.length; k++) {
                hantei_now = data[k]["KEKKA"];
                if (hantei_now == null) {
                    hantei_now = "";
                }
                hantei_now_name = data[k]["NAME"];
                if (hantei_now_name == null) {
                    hantei_now_name = "";
                }
                if (
                    (hantei_now != hantei_bef ||
                        hantei_now_name != hantei_bef_name) &&
                    ((hantei_now != "" && hantei_now != null) ||
                        (hantei_now_name != "" && hantei_now_name != null))
                ) {
                    html_01_str += "<tr>";
                    html_01_str += "<td>";
                    ymdhm = $.trim(data[k]["UPDYMDHM"]);
                    if (ymdhm != "" && ymdhm != null) {
                        Y = ymdhm.slice(0, 4);
                        m = ymdhm.slice(4, 6);
                        d = ymdhm.slice(6, 8);
                        h = ymdhm.slice(8, 10);
                        i = ymdhm.slice(10, 12);
                        html_01_str +=
                            Y + "/" + m + "/" + d + " " + h + ":" + i;
                    } else {
                        html_01_str += "(不明)";
                    }
                    html_01_str += "</td>";
                    html_01_str += "<td>";
                    if (data[k]["NAME"] != "" && data[k]["NAME"] != null) {
                        html_01_str += data[k]["NAME"];
                    }
                    html_01_str += " <br/> ";
                    if (data[k]["KEKKA"] != "" && data[k]["KEKKA"] != null) {
                        var leg = data[k]["KEKKA"].length;
                        //alert(leg);
                        html_01_str += data[k]["KEKKA"].substr(0, 20);
                        if (leg > 20) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["KEKKA"].substr(20, 20);
                        }
                        if (leg > 40) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["KEKKA"].substr(40, 20);
                        }
                        if (leg > 60) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["KEKKA"].substr(60, 20);
                        }
                    }
                    html_01_str += "</td>";
                    html_01_str += "<td  align='center'>";
                    var ttsname = $.trim(data[k]["TTS_SEIMEI"]);

                    if (ttsname == null || ttsname == "") {
                        //20160722 Upd Start
                        //html_01_str += "(不明)";
                        html_01_str += "(システム)";
                        //20160722 Upd End
                    } else {
                        html_01_str += data[k]["TTS_SEIMEI"];
                    }

                    html_01_str += "</td>";
                    html_01_str += "</tr>";

                    hantei_bef = hantei_now;
                    hantei_bef_name = hantei_now_name;
                }
            }
        } else {
            for (k = 0; k < data.length; k++) {
                hantei_now = data[k]["HANTEI" + idx];
                if (hantei_now == null) {
                    hantei_now = "";
                }
                hantei_now_name = data[k]["NAME" + idx];
                if (hantei_now_name == null) {
                    hantei_now_name = "";
                }
                //if ((hantei_now != "" && hantei_now != null && hantei_now != hantei_bef) || (hantei_now_name != "" && hantei_now_name != null && hantei_now_name != hantei_bef_name)) {
                if (
                    (hantei_now != hantei_bef ||
                        hantei_now_name != hantei_bef_name) &&
                    ((hantei_now != "" && hantei_now != null) ||
                        (hantei_now_name != "" && hantei_now_name != null))
                ) {
                    html_01_str += "<tr>";
                    html_01_str += "<td>";
                    //-----20141202  upd  jinmingai  s
                    // ymdhm = data[k]["UPDYMDHM"];
                    ymdhm = $.trim(data[k]["UPDYMDHM"]);
                    //-----20141202  upd  jinmingai  e
                    if (ymdhm != "" && ymdhm != null) {
                        Y = ymdhm.slice(0, 4);
                        m = ymdhm.slice(4, 6);
                        d = ymdhm.slice(6, 8);
                        h = ymdhm.slice(8, 10);
                        i = ymdhm.slice(10, 12);
                        html_01_str +=
                            Y + "/" + m + "/" + d + " " + h + ":" + i;
                    }
                    //-----20141218 fuxiaolin NO.66 add -s
                    else {
                        html_01_str += "(不明)";
                    }
                    //-----20141218 fuxiaolin NO.66 add -e
                    html_01_str += "</td>";
                    html_01_str += "<td>";
                    //---20150421 fanzhengzhou upd s. redmine #1799
                    if (
                        data[k]["NAME" + idx] != "" &&
                        data[k]["NAME" + idx] != null
                    ) {
                        html_01_str += data[k]["NAME" + idx];
                    }
                    // if (data[k]["NAME" + idx] != "" && data[k]["NAME" + idx] != null && data[k]["HANTEI" + idx] != "" && data[k]["HANTEI" + idx] != null) {
                    html_01_str += " <br/> ";
                    // }
                    if (
                        data[k]["HANTEI" + idx] != "" &&
                        data[k]["HANTEI" + idx] != null
                    ) {
                        //20201117 CI UPD S
                        //html_01_str += data[k]["HANTEI" + idx];
                        var leg = data[k]["HANTEI" + idx].length;
                        html_01_str += data[k]["HANTEI" + idx].substr(0, 20);
                        if (leg > 20) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["HANTEI" + idx].substr(
                                20,
                                20
                            );
                        }
                        if (leg > 40) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["HANTEI" + idx].substr(
                                40,
                                20
                            );
                        }
                        if (leg > 60) {
                            html_01_str += " <br/> ";
                            html_01_str += data[k]["HANTEI" + idx].substr(
                                60,
                                20
                            );
                        }
                        //20201117 CI UPD E
                    }
                    //---20150421 fanzhengzhou upd e. redmine #1799
                    html_01_str += "</td>";
                    html_01_str += "<td align='center'>";
                    //-----20141218 fuxiaolin NO.66 add -s
                    var ttsname = $.trim(data[k]["TTS_SEIMEI"]);

                    if (ttsname == null || ttsname == "") {
                        //20160722 Upd Start
                        //html_01_str += "(不明)";
                        html_01_str += "(システム)";
                        //20160722 Upd End
                    } else {
                        html_01_str += data[k]["TTS_SEIMEI"];
                    }

                    // html_01_str += data[k]["TTS_SEIMEI"];
                    //-----20141218 fuxiaolin NO.66 add -e
                    html_01_str += "</td>";
                    html_01_str += "</tr>";

                    hantei_bef = hantei_now;
                    hantei_bef_name = hantei_now_name;
                }
            }
        }
        html_01_str += "</table>";

        $(".sdh.sdh05.dialog").html(html_01_str);

        $(me.html_id).dialog(
            "option",
            "title",
            me.dialog_title + "(" + me.data.title + ")"
        );
        $(me.html_id).dialog("open");
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_SDH_SDH05 = new gdmz.SDH.SDH05();
    o_SDH_SDH05.load();

    o_HMSS_Master.SDH.SDH05 = o_SDH_SDH05;
    o_SDH_SDH05.SDH = o_HMSS_Master.SDH;
});
