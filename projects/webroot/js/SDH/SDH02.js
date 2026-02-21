/**
 * 説明：
 *
 *
 * @author lijun
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20141017           $1                      MessageBox的button修改	            fuxiaolin
 * 20141017           $1                      MessageBox的button修改	            zhenghuiyun
 * 20141126           NO.10                  初始化时法人営業对应的リスト種類不正确      fanzhengzhou
 * 20141218           NO.64                                                     fanzhengzhou
 * 20160127           #2373                   依頼                               li
 * 20160201			  #2373                   依頼                               YIN
 * 20190226			  #2870                   依頼                               ci
 * 20210224          \99.提供資料\20210217\20210217_SDH_ログイン後の仕様変更.xlsx                       依頼                           CI
 * 20220121           機能追加　　　　　　　　　　　N6対応　　　　　　　　　　　　　　　　　 Sun
 * 20220217           機能追加　　　　　　        20220212ーN6対応指摘事項(No9)        lujunxia
 * 20220218           機能追加　　　　　　        20220212ーN6対応指摘事項(No14)        YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH02");

gdmz.SDH.SDH02 = function () {
    var me = new gdmz.base.panel_dialog();
    me.ajax = new gdmz.common.ajax();
    me.clsComFnc = new gdmz.common.clsComFnc();
    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.id = "SDH02";
    me.sys_id = "SDH";

    //20150313 fuxiaolin edit start
    //----20210121 sun upd s
    //me.width = 450;
    me.width = 500;
    //----20210121 sun upd e
    //--- 20160127 li UPD S
    // me.height = 376;

    //20160309 Upd S
    //me.height = 410;
    // 20220209 YIN UPD S
    // me.height = 480;
    me.height = 510;
    // 20220209 YIN UPD E
    //--- 20160127 li UPD E
    //20160309 Upd E
    //20150313 fuxiaolin edit end

    me.dialog_title = "検索条件変更";

    me.html_id = ".sdh.sdh02.dialog";
    me.parent = ".sdh.sdh01.dialog_area";

    me.data = null;

    //---20150520 fanzhengzhou add s.#1898
    //---20210224 CI DEL S
    // me.focus = function() {
    // if ($(".sdh.sdh02.busyo_name").css('display') == "block") {
    // $(".sdh.sdh02.sel_user").focus();
    // }
    // };
    //---20210224 CI DEL E
    //---20150520 fanzhengzhou add e.#1898

    //-----20141017  $1  zhenghuiyun  ins  s
    var MessageBox = new gdmz.common.MessageBox();
    //-----20141017  $1  zhenghuiyun  ins  e

    // ========== 変数 end ==========

    // ========== コントロール start ==========
    //日本語仕様オプション
    $(".sdh.sdh02.input_data").ympicker({
        ////---20150520 fanzhengzhou add s.#1898
        // showOn: "button",
        // buttonImage: "/css/jquery/images/calendar.gif",
        // buttonImageOnly: true,
        //---20150520 fanzhengzhou add e.#1898
        //dateFormat : 'yy年mm月',
        dateFormat: "yymm",
        beforeShow: function (_input, inst) {
            setTimeout(function () {
                const inputId =
                    Math.random().toString(36).substring(2, 9) +
                    "-" +
                    Date.now();
                var $dpDiv = $(inst.dpDiv);
                $dpDiv
                    .find(".ui-datepicker-month")
                    .attr("id", "datepicker-month-" + inputId);
                $dpDiv
                    .find(".ui-datepicker-year")
                    .attr("id", "datepicker-year-" + inputId);
            }, 10);
        },
        onChangeMonthYear: function (_year, _month, inst) {
            setTimeout(function () {
                const inputId =
                    Math.random().toString(36).substring(2, 9) +
                    "-" +
                    Date.now();
                var $dpDiv = $(inst.dpDiv);
                $dpDiv
                    .find(".ui-datepicker-month")
                    .attr("id", "datepicker-month-" + inputId);
                $dpDiv
                    .find(".ui-datepicker-year")
                    .attr("id", "datepicker-year-" + inputId);
            }, 10);
        },
    });
    // ========== コントロース end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========
    //店舗を選択場合
    $(".sdh.sdh02.sel_busyo").change(function () {
        //リスト種類を空を変更する
        $(".sdh.sdh02.sel_user").empty();
        //帰る処理
        me.ajax.receive = function (result) {
            $(result).appendTo($(".sdh.sdh02.sel_user"));
        };
        //send処理
        var url = me.sys_id + "/" + "SDH02" + "/" + "SDH02";
        var data = {
            tenpo_cd: $(".sdh.sdh02.sel_busyo").val(),
        };
        //----20141218 NO.64 fanzhengzhou ins s.
        //---20210224 CI DEL S
        // if ($('.sdh.sdh02.busyo_name').css('display') == 'block') {
        // var data = {
        // "tenpo_cd" : $.trim($('.sdh.sdh02.busyo_cd').html()),
        // };
        // }
        //---20210224 CI DEL E
        //----20141218 NO.64 fanzhengzhou ins e.
        me.ajax.send(url, data, 0);
    });

    me.ok_click = function () {
        //fuxiaolin 20150401 edit start
        var selCondition = "0";
        selCondition = $(".sdh.sdh02.selectconditions").val();

        var selCondition1 = "000";
        //----20220121 sun upd s
        //if (($(".sdh.sdh02.selectconditions4").val() == 0))
        if (
            $(".sdh.sdh02.selectconditions4").val() == 0 ||
            $(".sdh.sdh02.selectconditions4").val() == 4
        ) {
            selCondition1 = "";
            var selected = $(".sdh.sdh02.selectconditions1m").select2("data");
            //20220218 YIN INS S
            if (
                $(".sdh.sdh02.selectconditions4").val() == 4 &&
                selected.length == 1 &&
                selected[0]["id"] == "000"
            ) {
                var options = JSON.parse(list1m_options);
                var temp = "";
                for (var i = 0; i < options.length; i++) {
                    temp = temp + options[i]["TEIKEI_CD"] + ",";
                }
                selCondition1 = temp.substring(0, temp.length - 1);
            } else {
                //20220218 YIN INS E
                var temp = "";
                for (var i = 0; i < selected.length; i++) {
                    temp = temp + selected[i]["id"] + ",";
                }
                selCondition1 = temp.substring(0, temp.length - 1);
                //20220218 YIN INS S
            }
            //20220218 YIN INS E
        } else {
            selCondition1 = $(".sdh.sdh02.selectconditions1").val();
        }
        //----20220121 sun upd e

        var selCondition2 = "000";
        selCondition2 = $(".sdh.sdh02.selectconditions2").val();

        var selCondition3 = "0";
        selCondition3 = $(".sdh.sdh02.selectconditions3").val();

        //20160201 YIN INS S
        var selCondition4 = "0";
        selCondition4 = $(".sdh.sdh02.selectconditions4").val();
        //20160201 YIN INS E

        //fuxiaolin 20150401 edit end
        var busyo = "";
        //20210224 CI UPD S
        //if ($(".sdh.sdh02.sel_busyo").css("display") == "block") {
        busyo = $(".sdh.sdh02.sel_busyo").val();
        // } else {
        // busyo = $(".sdh.sdh02.busyo_cd").text();
        // };
        //20210224 CI UPD E
        busyo = busyo.trim();
        var date = $(".sdh.sdh02.input_data").val();
        date = date.replace("年", "/");
        date = date.replace("月", "");
        var user = $(".sdh.sdh02.sel_user").val();
        //対象年月を選択してください。
        if (date.trim() == "") {
            // me.clsComFnc = new gdmz.common.clsComFnc();
            me.clsComFnc.MessageBox(
                "対象年月を選択してください。",
                "SDH",
                "OK",
                "Warning",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        //----20141212 fanzhengzhou del s.
        // //正しい年月を入力して下さい。
        // var intYear = date.substr(0, 4);
        // var intMonth = date.substr(5, 2);
        // var strTem = date.substr(4, 1);
        // if (isNaN(intYear) || isNaN(intMonth) || intMonth > 12 || intMonth < 1 || strTem != "/")
        // {
        // // me.clsComFnc = new gdmz.common.clsComFnc();
        // me.clsComFnc.MessageBox("正しい年月を入力して下さい。", "SDH", "OK", "Warning", MessageBox.MessageBoxIcon.Warning);
        // return;
        // };
        //----20141212 fanzhengzhou del e.
        //対象店舗を選択して下さい。
        if (busyo == "") {
            // me.clsComFnc = new gdmz.common.clsComFnc();
            me.clsComFnc.MessageBox(
                "対象店舗を選択して下さい。",
                "SDH",
                "OK",
                "Warning",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        //店舗「ユーカー」リスト種類「サービス」を選択したとき
        //ユーカーのサービス用はありません。
        if (
            $(".sdh.sdh02.sel_busyo").val() == "220" &&
            $(".sdh.sdh02.sel_user").val() == "002"
        ) {
            // me.clsComFnc = new gdmz.common.clsComFnc();
            me.clsComFnc.MessageBox(
                "ユーカーのサービス用はありません。",
                "SDH",
                "OK",
                "Warning",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        //店舗「カーセブン」リスト種類「サービス」を選択したとき
        //カーセブンのサービス用はありません。
        if (
            $(".sdh.sdh02.sel_busyo").val() == "261" &&
            $(".sdh.sdh02.sel_user").val() == "002"
        ) {
            // me.clsComFnc = new gdmz.common.clsComFnc();
            me.clsComFnc.MessageBox(
                "カーセブンのサービス用はありません。",
                "SDH",
                "OK",
                "Warning",
                MessageBox.MessageBoxIcon.Warning
            );
            return;
        }
        //fuxiaolin 20150401 edit start
        var arrayVal = {
            busyo: busyo,
            date: date,
            user: user,
            condition: selCondition,
            condition1: selCondition1,
            condition2: selCondition2,
            condition3: selCondition3,
            //20160201 YIN INS S
            condition4: selCondition4,
            //20160201 YIN INS E
        };
        //fuxiaolin 20150401 edit end
        me.do_ok_handle(arrayVal);

        $(me.html_id).dialog("close");
    };

    me.cancel_click = function () {
        me.do_cancel_handle();

        $(me.html_id).dialog("close");
    };

    //--- 20160127 li INS S
    $(".sdh.sdh02.selectconditions4").change(function () {
        $(".sdh.sdh02.selectconditions2").empty();
        switch ($(".sdh.sdh02.selectconditions4").val()) {
            //車検代替判定
            case "0":
                $(".sdh.sdh02.selectconditions").prop("disabled", "");
                $(".sdh.sdh02.selectconditions1").prop("disabled", "");
                me.conditions4Chick();
                //並び順は最終結果順、車両区分順の２種類
                $(".sdh.sdh02.selectconditions3").empty();
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("車両区分順", "0")
                );
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("活動状況順", "1")
                );
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("最終結果順", "2")
                );

                //----20220121 sun add s
                $(".sdh.sdh02.selectconditions1m").html(option_list1);
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions1").hide();
                // $(".sdh.sdh02.selectconditions1m").next().show();
                $(".sdh.sdh02.selectconditions1m").prop("disabled", "");
                // 20220209 YIN UPD E
                $(".sdh.sdh02.selectconditions1m")
                    .val(["000"])
                    .trigger("change");
                break;
            //----20220121 sun add e
            //新車１ヶ月点検判定
            //新車６ヶ月点検判定
            case "1":
            case "2":
                //要注意リスト抽出用 活動状況は使用不可
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions").val("0");
                $(".sdh.sdh02.selectconditions").val("0").trigger("change");
                // 20220209 YIN UPD E
                $(".sdh.sdh02.selectconditions").prop("disabled", "disabled");
                $(".sdh.sdh02.selectconditions1").val("000");
                $(".sdh.sdh02.selectconditions1").prop("disabled", "disabled");
                //最終結果は未入庫のみ、全て　の２種類
                me.conditions4Chick();
                //並び順は最終結果順、車両区分順の２種類
                $(".sdh.sdh02.selectconditions3").empty();
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("車両区分順", "0")
                );
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("最終結果順", "2")
                );

                //----20220121 sun add s
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions1").show();
                // $(".sdh.sdh02.selectconditions1m").next().hide();
                $(".sdh.sdh02.selectconditions1m").prop("disabled", "disabled");
                $(".sdh.sdh02.selectconditions1m")
                    .val(["000"])
                    .trigger("change");
                $("#select2-selectconditions2-results li").css(
                    "width",
                    "200px"
                );
                // 20220209 YIN UPD E
                //----20220121 sun add e
                break;
            //--- 20190221 CI INS S
            //中古１ヶ月点検判定
            case "3":
                //要注意リスト抽出用 活動状況は使用不可
                $(".sdh.sdh02.selectconditions").val("0");
                $(".sdh.sdh02.selectconditions").prop("disabled", "disabled");
                $(".sdh.sdh02.selectconditions1").val("000");
                $(".sdh.sdh02.selectconditions1").prop("disabled", "disabled");
                //最終結果は未入庫のみ、全て　の２種類
                me.conditions4Chick();
                //並び順は最終結果順、車両区分順の２種類
                $(".sdh.sdh02.selectconditions3").empty();
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("車両区分順", "0")
                );
                $(".sdh.sdh02.selectconditions3").append(
                    new Option("最終結果順", "2")
                );

                //----20220121 sun add s
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions1").show();
                // $(".sdh.sdh02.selectconditions1m").next().hide();
                $(".sdh.sdh02.selectconditions1m").prop("disabled", "disabled");
                $(".sdh.sdh02.selectconditions1m")
                    .val(["000"])
                    .trigger("change");
                // 20220209 YIN UPD E
                //----20220121 sun add e
                break;
            //--- 20190221 CI INS E
            case "4":
                $(".sdh.sdh02.selectconditions1m").html(option_list1m);
                //$(".sdh.sdh02.selectconditions1m").html("<?php echo $option_list1m; ?>");
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions").val("0");
                $(".sdh.sdh02.selectconditions").val("0").trigger("change");
                // 20220209 YIN UPD E
                $(".sdh.sdh02.selectconditions").prop("disabled", "disabled");
                // 20220209 YIN UPD S
                // $(".sdh.sdh02.selectconditions1").hide();
                // $(".sdh.sdh02.selectconditions1m").next().show();
                $(".sdh.sdh02.selectconditions1m").prop("disabled", "");
                // 20220209 YIN UPD E
                $(".sdh.sdh02.selectconditions1m")
                    .val(["000"])
                    .trigger("change");
                //最終結果は未入庫のみ、全て　の２種類
                me.conditions4Chick();
                break;
            //他の
            default:
            // tantousya_type = "E2";
        }
    });

    //----20220121 sun add s
    $("#selectconditions1m").on("select2:select", function (e) {
        var data = e.params.data;
        if (data["id"] == "000") {
            $(".sdh.sdh02.selectconditions1m").val(["000"]).trigger("change");
            //20220217 lujunxia ins s
            $("#selectconditions1m").select2("close");
            //20220217 lujunxia ins e
            return;
        }
        var selected = $(".sdh.sdh02.selectconditions1m").select2("data");
        if (selected.length == 0) {
            $(".sdh.sdh02.selectconditions1m").val(["000"]).trigger("change");
        } else {
            var selarr = [];
            for (var i = 0; i < selected.length; i++) {
                var id = selected[i]["id"];
                if (id != "000") {
                    selarr.push(id);
                }
            }
            //20220217 lujunxia ins s
            $(
                ".select2-results__option[aria-selected]"
            )[0].ariaSelected = false;
            //20220217 lujunxia ins e
            $(".sdh.sdh02.selectconditions1m").val(selarr).trigger("change");
        }
    });

    $("#selectconditions1m").on("select2:unselect", function (e) {
        var selected = $(".sdh.sdh02.selectconditions1m").select2("data");
        if (selected.length == 0) {
            $(".sdh.sdh02.selectconditions1m").val(["000"]).trigger("change");
            //20220217 lujunxia ins s
            var data = e.params.data;
            if (data["id"] == "000") {
                $("#selectconditions1m").select2("close");
            } else {
                $(
                    ".select2-results__option[aria-selected]"
                )[0].ariaSelected = true;
            }
            //20220217 lujunxia ins e
        }
    });

    function select2handler(event) {
        try {
            if (event.keyCode == 8) {
                var focusedElement = document.activeElement;
                if (focusedElement.className != "select2-search__field") {
                    return true;
                }

                var selected = $(".sdh.sdh02.selectconditions1m").select2(
                    "data"
                );
                var str = $(".select2-search__field").val();
                if (
                    selected.length == 1 &&
                    selected[0]["id"] == "000" &&
                    str == ""
                ) {
                    event.preventDefault();

                    event.stopPropagation();
                }
                return false;
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh02.selectconditions1m").keydown');
            console.log(e);
        }
    }

    //----20220121 sun add e

    //モード選択を選択場合
    me.conditions4Chick = function () {
        //モード選択判定を変更する
        $(".sdh.sdh02.selectconditions2").empty();
        //帰る処理
        me.ajax.receive = function (result) {
            $(result).appendTo($(".sdh.sdh02.selectconditions2"));
        };
        //send処理
        var url = me.sys_id + "/" + "SDH02" + "/" + "SDH02_02";
        var data = {
            type_cd: $(".sdh.sdh02.selectconditions4").val(),
        };
        me.ajax.send(url, data, 0);
    };
    //--- 20160127 li INS E

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========
    var base_init_control = me.init_control;

    me.init_control = function () {
        base_init_control();
        //-----20141126  NO.10   fanzhengzhou  ins  s
        $(".sdh.sdh02.sel_busyo").change();
        //-----20141126  NO.10   fanzhengzhou  ins  e

        // getter
        // var buttons = $(".sdh.sdh02.dialog").dialog("option", "buttons");
        //
        // // setter
        $(me.html_id).dialog("option", "buttons", [
            {
                text: "確定",

                click: function () {
                    me.ok_click();
                },
            },
            {
                text: "戻る",
                click: function () {
                    me.cancel_click();
                },
            },
        ]);
        //----20220121 sun add s
        $("#selectconditions1m").select2({
            //20220217 lujunxia ins s
            //prevent the dropdown from closing when a result is selected
            closeOnSelect: false,
            //20220217 lujunxia ins e
        });
        // 20220209 YIN INS S
        $("#selectconditions4").select2({
            minimumResultsForSearch: -1,
        });
        $("#busyoData").select2({
            minimumResultsForSearch: -1,
        });
        $("#selectData").select2({
            minimumResultsForSearch: -1,
        });
        $("#selectconditions").select2({
            minimumResultsForSearch: -1,
        });
        $("#selectconditions2").select2({
            minimumResultsForSearch: -1,
        });
        $("#selectconditions3").select2({
            minimumResultsForSearch: -1,
        });
        // 20220209 YIN INS E
        $(".select2-selection__rendered").css("min-height", "30px");
        $(".select2-selection__rendered").css("max-height", "80px");
        $(".select2-selection__rendered").css("overflow-y", "auto");
        $(".sdh.sdh02.selectconditions1m").val("000").trigger("change");
        document.addEventListener("keydown", select2handler, true);
        //----20220121 sun add e

        // $(me.html_id).dialog(
        // {
        // autoOpen : false,
        // height : me.height,
        // width : me.width,
        // modal : true,
        // title : me.dialog_title,
        // resizable : false,
        // buttons :
        // {
        // "確定" : function()
        // {
        // me.ok_click();
        // },
        // "戻る" : function()
        // {
        // me.cancel_click();
        // }
        // },
        // close : function()
        // {
        // }
        // });
    };

    // ==========
    // = メソッド end =
    // ==========

    return me;
};

$(function () {
    var o_SDH_SDH02 = new gdmz.SDH.SDH02();
    o_SDH_SDH02.load();

    o_HMSS_Master.SDH.SDH02 = o_SDH_SDH02;
    o_SDH_SDH02.SDH = o_HMSS_Master.SDH;
});
