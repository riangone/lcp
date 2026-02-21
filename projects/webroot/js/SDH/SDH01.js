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
 * 日付                Feature/Bug                  内容                             担当
 * YYYYMMDD           #ID                          XXXXXX                           FCSDL
 * 20141016           $1                           保存完了メッセージ追加               zhenghuiyun
 * 20141016           #424                         FreeMemo保存バグ修正               zhenghuiyun
 * 20141016           $2                           判定文入力欄ReadOnlyにする            zhenghuiyun
 * 20141016           $3                           担当リスト、年月リスト選択前          zhenghuiyun
 *                                                        のチェック処理追加
 * 20141017           $4                           MessageBox的button修改            fuxiaolin
 * 20141017           $4                           MessageBox的button修改            zhenghuiyun
 * 20141017           #434                         修正バグ                              zhenghuiyun
 * 20141017           $5                           最終結果表示処理                    zhenghuiyun
 * 20141020           #512                         本部以外の場合 担当者一覧
 *                                                        デフォルト選択は「店舗全員」であること   fanzhengzhou
 * 20141021           $6                           管理担当履歴右揃え                     jinmingai
 * 20141022           #759                         判定内容コード保存バグ修正            zhenghuiyun
 * 20141022           #761                         修正バグ                              zhenghuiyun
 * 20141022           #795                         修正バグ                              zhenghuiyun
 * 20141022           #762                         修正バグ                              fanzhengzhou
 * 20141023           #954                         修正バグ                              fanzhengzhou
 * 20141127           NO.4                         修正バグ                              fanzhengzhou
 * 20141128           No.26                        保険と注文書ボタン　　　　　　　　　　　fuxiaolin
 * 20141202           No.33   データが読み込まれた際にスクロールバーが読み込み前のままの状態。　fuxiaolin
 * 20141202           No.34                          記号が表示しないとき、クリックできる　jinmingai
 * 20141208           No.45   PGUPとPGDNショットカットキー使用時、連続して押して、session outdate問題があるfanzhengzhou
 * 20141209           No.51   						nengetuに追加する　　　　　　　　　　fuxiaolin
 * 20150825           	     						SDH改善要望(20150819)　　　　　　　 Yuanjh
 * 20150831           	     						SDH改善要望(20150819)　　　　　　　 LI
 * 20150902           	     						SDH改善要望(20150819)　　　　　　　 Yuanjh
 * 20151029											SDH改善要望(20150914)				Yinhuaiyu
 * 20151104           #2254							SDH改善要望(20151104)				Yinhuaiyu
 * 20160116                                 コンタクト履歴に商品追加			SDH改善要望(201601)				HM
 * 20151104           #2254							SDH改善要望(20151104)				Yinhuaiyu
 * 20160127           #2373                   	 	依頼                             li
 * 20160229           #2389                   	 	Q&A                             YIN
 * 20160229           #2394                   	 	依頼                             YIN
 * 20160229           #2392                         依頼                             Sun
 * 20160229           #2396                   	 	依頼                             YIN
 * 20160907           #2573                   	 	依頼                             YIN
 * 20171127           #2807               tooltip功能修正                             lqs
 * 20171129           #2807                   右侧错位问题                             lqs
 * 20190227           #2870                   	 	依頼                              YIN
 * 20201117           bug                     DIVのHeightが間違っています。                                     cyc
 * 20201117           bug                         textareaの単文字数が多すぎて、異常に表示される           　ciyuanchen
 * 20220121           機能追加　　　　　　　　　　　　　　 N6要望対応　　　　　　　　　　　　　　  Sun
 * 20220217           機能追加　　　　　　              20220212ーN6対応指摘事項(No6,7)     lujunxia
 * 20220217           機能追加　　　　　　              20220212ーN6対応指摘事項(No2,5,11)     YIN
 * 20220218           機能追加　　　　　　              20220212ーN6対応指摘事項(No14)     YIN
 * 20220222           No15　　　　　　                20220212ーN6対応指摘事項           lujunxia
 * 20220610           機能追加           車検代替判定モードで更新処理を行った後、一覧リストのスクロー          ciyuanchen
 *                                                      ルバーが先頭に戻る一覧リストの位置は更新前の状態を保持している
 * 20230911           要望対応            年月表示箇所の表記を修正                     YIN
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.SDH.SDH01");

var fromMe;

gdmz.SDH.SDH01 = function () {
    var me = new gdmz.base.panel();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========

    me.cur_hantei_item = undefined;
    //20220610 ci ins s
    me.scrolltop = "";
    //20220610 ci ins e
    me.data = undefined;

    me.timeline = new gdmz.sdh01.timeline();

    me.pre_tanto = "";
    me.pre_nengetu = "";

    var MessageBox = new gdmz.common.MessageBox();

    me.PgUpPgDnFlag = true;
    // ========== 変数 end ==========

    // ========== コントロール start ==========

    me.id = "SDH01";
    me.sys_id = "SDH";
    me.dialog_area = ".sdh.sdh01.dialog_area";

    //20150414  fuxiaolin edit start
    //me.hantei_list_w = 200;
    me.hantei_list_w = 400;
    //20150414  fuxiaolin edit end

    me.firstloadflag = true;

    //--- 20160127 li INS S
    me.condition4 = "";
    //0 車検代替判定,1 新車１ヶ月点検判定,2 新車６ヶ月点検判定
    //--- 20160127 li INS E

    //---- 20220121 sun add s
    me.selected_data02 = null;
    me.tmp_clicked_item = null;
    //---- 20220121 sun add e

    /**
     * 集計 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sdh_05",
        type: "button",
        handle: "",
        icons: "ui-icon-clipboard",
    });

    /**
     * 検索条件変更 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sdh_02",
        type: "button",
        handle: "",
        icons: "ui-icon-search",
    });
    /**
     * 注文書情報 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sdh_03",
        type: "button",
        handle: "",
        icons: "ui-icon-newwin",
    });
    /**
     * 保険・クレジット ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sdh_04",
        type: "button",
        handle: "",
        icons: "ui-icon-newwin",
    });
    //20150820	Yuanjh	ADD S.
    /**
     * tmp集計 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sdh_06",
        type: "button",
        handle: "",
        icons: "ui-icon-clipboard",
    });
    //20150820	Yuanjh	ADD E.

    /**
     * 画面更新 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_reload",
        type: "button",
        handle: "",
        icons: "ui-icon-refresh",
    });
    /**
     * 前の車両 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_prev_syaryou",
        type: "button",
        handle: "",
        icons: "ui-icon-seek-prev",
    });
    //----20220121 sun add s
    /**
     * 進捗確認ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_sinchoku",
        type: "button",
        handle: "",
        icons: "ui-icon-transferthick-e-w",
    });
    //----20220121 sun add e
    /**
     * 保存ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_save",
        type: "button",
        handle: "",
        icons: "ui-icon-disk",
    });
    /**
     * 次の車両 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_next_syaryou",
        type: "button",
        handle: "",
        icons: "ui-icon-seek-next",
    });
    /**
     * 新注文書 ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.result_button_08",
        type: "button",
        handle: "",
        icons: "ui-icon-newwin",
    });

    //20160322 add start
    /**
     * ヘルプ ボタン
     */
    me.controls.push({
        id: ".sdh.sdh01.btn_help",
        type: "button",
        handle: "",
        icons: "ui-icon-help",
    });
    //20160322 add end

    // ========== コントロール end ==========

    // ==========
    // = 宣言 end =
    // ==========

    // ==========
    // = イベント start =
    // ==========

    var base_load = me.load;

    /**
     * 画面ロード
     */
    me.load = function () {
        try {
            base_load();

            //--- 20160304 li INS S
            me.sdh_sdh01_result_type = true;
            //--- 20160304 li INS E

            me.get_data();
        } catch (e) {
            console.log("error:load");
            console.log(e);
        }
    };

    var base_init_control = me.init_control;
    /**
     * 初期化
     */
    me.init_control = function () {
        try {
            base_init_control();

            //popup iconボタンを隠す s
            $(".sdh.sdh01.listpopup").hide();
            //popup iconボタンを隠す e

            //---20150609 fanzhengzhou add s.#1911-2
            $(".sdh.sdh01.hanteinengetu_btn_01").hide();
            //---20150609 fanzhengzhou add e.#1911-2
            for (var i = 1; i < 8; i++) {
                $(".sdh.sdh01.btn_rireki_0" + i).click(function () {
                    var selected_data = {
                        idx: $(this).data("idx"),
                        data: me.data["sdh01_hantei_naiyou"],
                        title: $.trim(
                            $(
                                ".sdh.sdh01.hanteinengetu_0" +
                                    $(this).data("idx")
                            )
                                .text()
                                .replace(/：/, "")
                        ),
                    };
                    me.open_dialog(
                        "SDH05",
                        null,
                        null,
                        JSON.stringify(selected_data)
                    );
                    //20171127 lqs INS S
                    $(this).find("span").hide();
                    //20171127 lqs INS E
                });
                //20171127 lqs INS S
                $(".sdh.sdh01.btn_rireki_0" + i).mousemove(function () {
                    $(this).find("span").show();
                });
                //20171127 lqs INS E

                //--    -20150609 fanzhengzhou add s.#1911-2
                $(".sdh.sdh01.hanteinengetu_btn_0" + i).click(function () {
                    var str = $(this).attr("class").toString();
                    //var num = str.charAt(str.length - 1);
                    var num = str.substr(29, 1);
                    if (num > 1) {
                        $(".sdh.sdh01.result_select_0" + num).val(
                            $(".sdh.sdh01.result_select_0" + (num - 1)).val()
                        );
                        $(".sdh.sdh01.result_text_0" + num).data(
                            "code",
                            $(".sdh.sdh01.result_text_0" + (num - 1)).data(
                                "code"
                            )
                        );
                    }
                });

                //---20150609 fanzhengzhou add e.#1911-2

                // $(".sdh.sdh01.result_select_0" + i).mcDropdown(".sdh.sdh01.result_menu_0" + i,
                // {
                // select : function(value, name)
                // {
                // console.log("--------------判定内容コード");
                // console.log(value);
                // if (value.length != 4)
                // {
                // value = value + '00';
                // }
                //
                // var id = "#" + this.$id;
                // var idx = $(id).data("idx");
                // //fuxiaolin edit 20150408 start
                // //	$(".sdh.sdh01.result_text_0" + idx).val(name);
                // $(".sdh.sdh01.result_text_0" + idx).val('');
                // //fuxiaolin edit 20150408 end
                // $(".sdh.sdh01.result_text_0" + idx).data("code", value);
                // //--- 20160127 li INS S
                // //HANTEILST_SINSYAの更新年月日がシステム日付より過去　AND コンボボックスの値が「入庫済」の場合は入力不可にする
                // console.log(me.data["sdh01_hantei_naiyou"]);
                // if (name == "入庫済" && (me.condition4 == "1" || me.condition4 == "2" ) && me.data["sdh01_hantei_naiyou"].length > 0)
                // {
                // ymdhm = $.trim(me.data["sdh01_hantei_naiyou"][me.data["sdh01_hantei_naiyou"].length - 1]["UPDYMDHM"]);
                // //20160127 YIN INS S
                // ymdhm = ymdhm.substr(0, 8);
                // //20160127 YIN INS E
                // ydate = $.trim(me.data["sdh01_hantei_naiyou"][me.data["sdh01_hantei_naiyou"].length - 1]["SYSYMDHM"]);
                // //20160127 YIN INS S
                // ydate = ydate.substr(0, 8);
                // //20160127 YIN INS E
                // if (ydate >= ymdhm)
                // {
                // $(".sdh.sdh01.result_text_0" + idx).attr("disabled", "none");
                // }
                // else
                // {
                // $(".sdh.sdh01.result_text_0" + idx).attr("disabled", false);
                // }
                // }
                // else
                // {
                // $(".sdh.sdh01.result_text_0" + idx).attr("disabled", false);
                // }
                // //--- 20160127 li INS E
                // }
                // }, "sdh_mcDropdown" + i);
                // $("#sdh_mcDropdown" + i).data("idx", i);
                //
                // $(".sdh.sdh01.result_text_0" + i).empty();
                // $(".sdh.sdh01.result_text_0" + i).width("95%");
            }

            $(".sdh.sdh01.btn_tanto_rireki").click(function () {
                me.open_dialog(
                    "SDH06",
                    null,
                    null,
                    me.data["sdh01_tantou_henkou_rireki"]
                );
                //20171127 lqs INS S
                $(this).find("span").hide();
                //20171127 lqs INS E
            });
            //20171127 lqs INS S
            $(".sdh.sdh01.btn_tanto_rireki").mousemove(function () {
                $(this).find("span").show();
            });
            //20171127 lqs INS E

            //最終結果 s
            //20150521 fuxiaolin #1899 add start
            $(".sdh.sdh01.btn_rireki_08").click(function () {
                //--- 20160127 li INS S
                if (me.condition4 == "1" || me.condition4 == "2") {
                    strTitle = "新車６ヶ月点検判定";
                }
                //20190227 YIN INS S
                else if (me.condition4 == "3") {
                    strTitle = "中古１ヶ月点検判定";
                }
                //20190227 YIN INS E
                else {
                    strTitle = "最終結果：";
                }
                //--- 20160127 li INS E
                var selected_data = {
                    idx: $(this).data("idx"),
                    data: me.data["sdh01_hantei_naiyou"],
                    //--- 20160127 li UPD S
                    //title : "最終結果"
                    title: strTitle,
                    //--- 20160127 li UPD E
                };
                me.open_dialog("SDH05", null, null, selected_data);
                //20171127 lqs INS S
                $(this).find("span").hide();
                //20171127 lqs INS E
            });
            //20171127 lqs INS S
            $(".sdh.sdh01.btn_rireki_08").mousemove(function () {
                $(this).find("span").show();
            });
            //20171127 lqs INS E
            //20150521 fuxiaolin #1899 add end
            //--- 20160301 li DEL S
            // $(".sdh.sdh01.result_select_08").mcDropdown(".sdh.sdh01.result_menu_08",
            // {
            // select : function(value, name)
            // {
            // //fuxiaolin 20150410 edit
            // if (value.length != 4)
            // {
            // value = value + '00';
            // }
            // console.log("--------------判定内容コード88");
            // console.log(value);
            //
            // //$(".sdh.sdh01.result_text_08").val(name);
            // $(".sdh.sdh01.result_text_08").val('');
            // $(".sdh.sdh01.result_text_08").data("code", value);
            // //--- 20160127 li INS S
            // //HANTEILST_SINSYAの更新年月日がシステム日付より過去　AND コンボボックスの値が「入庫済」の場合は入力不可にする
            // console.log(me.data["sdh01_hantei_naiyou"]);
            // if (name == "入庫済" && (me.condition4 == "1" || me.condition4 == "2" ) && me.data["sdh01_hantei_naiyou"].length > 0)
            // {
            // ymdhm = $.trim(me.data["sdh01_hantei_naiyou"][me.data["sdh01_hantei_naiyou"].length - 1]["UPDYMDHM"]);
            // //20160127 YIN INS S
            // ymdhm = ymdhm.substr(0, 8);
            // //20160127 YIN INS E
            // ydate = $.trim(me.data["sdh01_hantei_naiyou"][me.data["sdh01_hantei_naiyou"].length - 1]["SYSYMDHM"]);
            // //20160127 YIN INS S
            // ydate = ydate.substr(0, 8);
            // //20160127 YIN INS E
            // if (ydate >= ymdhm)
            // {
            // $(".sdh.sdh01.result_text_08").attr("disabled", "none");
            // }
            // else
            // {
            // $(".sdh.sdh01.result_text_08").attr("disabled", false);
            // }
            // }
            // else
            // {
            // $(".sdh.sdh01.result_text_08").attr("disabled", false);
            // }
            // //--- 20160127 li INS E
            // }
            // }, "sdh_mcDropdown8");
            // $(".sdh.sdh01.result_text_08").width("95%");
            //--- 20160301 li DEL E

            //最終結果 e

            $(".sdh.sdh01.content.left.panel").width(me.hantei_list_w + 20);
            $(".sdh.sdh01.content.left.hantei_list").width(me.hantei_list_w);

            me.add_shortcut();

            //20150611 Add Start
            me.resize_all();
            //20150611 Add End
            //20171129 lqs INS S
            me.explorer = window.navigator.userAgent;
            if (me.explorer.indexOf("Safari") >= 0) {
                $(".sdh.sdh01.rightpart").css("height", "100%");
            }
            if (me.explorer.indexOf("Firefox") >= 0) {
                //20201117 CI UPD S
                //$(".sdh.sdh01.rightpart").css("height", "91%");
                if (navigator.userAgent.toUpperCase().indexOf("FIREFOX") > -1) {
                    $(".sdh.sdh01.rightpart").css("height", "100%");
                } else {
                    $(".sdh.sdh01.rightpart").css("height", "91%");
                }
                //20201117 CI UPD E
            }
            //20171129 lqs INS E
        } catch (e) {
            console.log("error:init_control");
            console.log(e);
        }
    };

    /**
     * 画面リサイズ
     */
    $(".sdh.sdh01.all").exResize(function () {
        try {
            me.resize_all();
        } catch (e) {
            console.log('error:$(".sdh.sdh01.all").exResize');
            console.log(e);
        }
    });

    //20150404 ADD start
    $(".sdh.sdh01.accumulation_dialog_area").dialog({
        autoOpen: false,
        height: me.ratio === 1.5 ? 550 : 630,
        width: 1200,
        modal: true,
        resizable: false,
    });

    //201508	Yuanjh	ADD S.
    $(".sdh.sdh01.syasyu_accumulation_dialog_area").dialog({
        autoOpen: false,

        //20150902	Yuanjh	UPD S.
        //height : 565,
        height: me.ratio === 1.5 ? 550 : 634,
        //20150902	Yuanjh	UPD E.
        width: me.ratio === 1.5 ? 840 : 960,
        modal: true,
        resizable: false,
    });

    $(".sdh.sdh01.btn_sdh_06").click(function () {
        try {
            var getTantosyacd = $(".sdh.sdh01.sel_tenpo").val();
            var getNengetu = $(".sdh.sdh01.sel_nengetu").val();
            //20151103 Yin ADD S
            //busyocdを追加
            var busyocd = $(".sdh.sdh02.sel_busyo").val();

            if (busyocd == undefined) {
                busyocd = me.data["sdh01_tenpo"]["KYOTN_CD"];
            }
            //20151103 Yin ADD S

            //１－１－１．年月を表示
            //パラメータ「年月」 の値を表示する
            //表示形式：　yyyy/mm
            var y = getNengetu.substr(0, 4);
            var m = getNengetu.substr(4, 2);
            $(".sdh.sdh10.nengetu").html(y + "/" + m);
            //１－１－２．担当者を表示
            //パラメータ「担当者名」 の値を表示する
            var item = $(".sdh.sdh01.sel_tenpo option:selected").text();
            $(".sdh.sdh10.tantocd").html(item);

            //20151102 Yin UPD S

            //パラメータ「年月」の値を 列12 にセット　（書式：　ｙｙｙｙ年mm月）
            //列11 から 列01 まで 、 パラメータ「年月」の値 を １年づつ減らした年月を編集
            // var y = getNengetu.substr(0, 4);
            // var m = getNengetu.substr(4, 2);
            // var dt = $.exDate(y + "/" + m + "/" + "01");
            // for (var i = 0; i < 12; i++)
            // {
            // //20151029 Yin INS S
            // var str = dt.addMonths(-i * 12).toChar("yyyy年  ");
            // if (i == 11)
            // {
            // str = str + "以前";
            // }
            // else
            // {
            // str = str + "&nbsp;&nbsp;";
            // }
            // //20151029 Yin INS E
            // if (i < 3)
            // {
            // var ym = ".sdh.sdh10.10hanteinengetu_" + (12 - i);
            // }
            // else
            // {
            // var ym = ".sdh.sdh10.10hanteinengetu_0" + (12 - i);
            // }
            // $(ym).html(str);
            // }
            //20151102 Yin UPD E

            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;
            var url = me.sys_id + "/" + me.id + "/" + "getHanteisyasyu";

            var data = {
                //20151103 Yin INS S
                //busyocdを追加
                busyocd: busyocd,
                //20151103 Yin INS E
                tantocd: getTantosyacd,
                nengetu: y + "" + m,
                //--- 20160127 li INS S
                condition4: me.condition4,
                //--- 20160127 li INS E
                hantei: "全て",
            };
            o_ajax.send(url, data, 0);
            function receive(result) {
                result = JSON.parse(result);
                $("#selhantei").html(result["data1"]);

                //20151102 Yin INS S
                var y = result["data2"]["GETDATE"].substr(0, 4) - 1;
                var m = result["data2"]["GETDATE"].substr(4, 2);
                var dt = $.exDate(y + "/" + m + "/" + "01");
                for (var i = 0; i < 12; i++) {
                    var str = dt.addMonths(-i * 12).toChar("yyyy年  ");
                    if (i == 11) {
                        str = str + "以前";
                    } else {
                        str = str + "&nbsp;&nbsp;";
                    }
                    if (i < 3) {
                        var ym = ".sdh.sdh10.10hanteinengetu_" + (12 - i);
                    } else {
                        var ym = ".sdh.sdh10.10hanteinengetu_0" + (12 - i);
                    }
                    $(ym).html(str);
                }
                //20151102 Yin INS E

                //20151102 Yin UPD S
                //$("#getSyasyuAccDetailsTable").html(result['data2']);
                $("#getSyasyuAccDetailsTable").html(result["data2"]["TABLE"]);
                //20151102 Yin UPD E

                $(".sdh.sdh01.syasyu_accumulation_dialog_area").dialog(
                    "option",
                    "title",
                    "車種別集計情報"
                );
                $(".sdh.sdh01.syasyu_accumulation_dialog_area").css(
                    "visibility",
                    "visible"
                );
                $(".sdh.sdh01.syasyu_accumulation_dialog_area").dialog("open");
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_sdh_06").click');
            console.log(e);
        }
    });
    //車種コンボボックス 変更時
    $(".sdh.sdh10.sel_hantei").change(function () {
        try {
            var getTantosyacd = $(".sdh.sdh01.sel_tenpo").val();
            var getNengetu = $(".sdh.sdh01.sel_nengetu").val();
            var getHantei = $(".sdh.sdh10.sel_hantei").val();
            //20151103 Yin ADD S
            //busyocdを追加
            var busyocd = $(".sdh.sdh02.sel_busyo").val();

            if (busyocd == undefined) {
                busyocd = me.data["sdh01_tenpo"]["KYOTN_CD"];
            }
            //20151103 Yin ADD S

            var y = getNengetu.substr(0, 4);
            var m = getNengetu.substr(4, 2);

            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;
            var url = me.sys_id + "/" + me.id + "/" + "getHanteisyasyudetail";
            var data = {
                //20151103 Yin INS S
                //busyocdを追加
                busyocd: busyocd,
                //20151103 Yin INS E
                tantocd: getTantosyacd,
                nengetu: y + "" + m,
                //--- 20160127 li INS S
                condition4: me.condition4,
                //--- 20160127 li INS E
                hantei: getHantei,
            };
            o_ajax.send(url, data, 0);
            function receive(result) {
                result = JSON.parse(result);
                //20151102 Yin UPD S
                //$("#getSyasyuAccDetailsTable").html(result['data']);
                $("#getSyasyuAccDetailsTable").html(result["data"]["TABLE"]);
                //20151102 Yin UPD E
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.sel_hantei").click');
            console.log(e);
        }
    });
    //201508	Yuanjh	ADD E.

    $(".sdh.sdh01.btn_sdh_05").click(function () {
        try {
            var getTantosyacd = $(".sdh.sdh01.sel_tenpo").val();
            var getNengetu = $(".sdh.sdh01.sel_nengetu").val();
            //20150417 fuxiaolin edit start
            //busyocdを追加
            var busyocd = $(".sdh.sdh02.sel_busyo").val();
            //20150417 fuxiaolin edit end

            //20150422 zhenghuiyun edit start
            if (busyocd == undefined) {
                busyocd = me.data["sdh01_tenpo"]["KYOTN_CD"];
            }
            //20150422 zhenghuiyun edit start

            var y = getNengetu.substr(0, 4);
            var m = getNengetu.substr(4, 2);
            var dt = $.exDate(y + "/" + m + "/" + "01");
            for (var i = 0; i < 7; i++) {
                // 20230911 YIN UPD S
                // var str = dt.addMonths(-i).toChar("yyyy年mm月");
                if (i == 4) {
                    var str = dt.addMonths(-6).toChar("yyyy年mm月");
                } else if (i == 5) {
                    var str = dt.addMonths(-7).toChar("yyyy年mm月");
                } else if (i == 6) {
                    var str = dt.addMonths(-13).toChar("yyyy年mm月");
                } else {
                    var str = dt.addMonths(-i).toChar("yyyy年mm月");
                }
                // 20230911 YIN UPD E
                var ym = ".sdh.sdh09.09hanteinengetu_0" + (7 - i);
                $(ym).html(str);
            }
            $(".sdh.sdh09.09hanteinengetu_saisyu").html(y + "年" + m + "月");
            $(".sdh.sdh09.nengetu").html(y + "年" + m + "月");
            var item = $(".sdh.sdh01.sel_tenpo option:selected").text();

            $(".sdh.sdh09.tantocd").html(item);
            var tantoMark = "false";
            if (
                getTantosyacd == "000" ||
                getTantosyacd == "001" ||
                getTantosyacd == "002"
            ) {
                tantoMark = "false";
            } else {
                tantoMark = "true";
            }
            //20150417 fuxiaolin edit start
            //busyocdを追加
            var data = {
                //20151104 Yin INS S
                tantomark: tantoMark,
                busyocd: busyocd,
                //20151104 Yin INS E
                tantocd: getTantosyacd,
                nengetu: y + "" + m,
                //--- 20160127 li INS S
                condition4: me.condition4,
                //--- 20160127 li INS E
                hantei: "",
            };

            //20150417 fuxiaolin edit end
            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;

            var url = me.sys_id + "/" + me.id + "/" + "getAccumulation";

            o_ajax.send(url, data, 0);

            function receive(result) {
                result = JSON.parse(result);
                $("#getAccDetailsTable").html(result["data1"]);
                $("#getAccDetailsSaisyuTable").html(result["data2"]);
                $("#getAccTotal").html(result["data3"]);
                $("#getAccTotal1").html(result["data4"]);
                $(".sdh.sdh01.accumulation_dialog_area").dialog(
                    "option",
                    "title",
                    "集計情報"
                );
                $(".sdh.sdh01.accumulation_dialog_area").css(
                    "visibility",
                    "visible"
                );
                $(".sdh.sdh01.accumulation_dialog_area").dialog("open");
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_sdh_05").click');
            console.log(e);
        }
    });
    //20150404 ADD end

    //20150317 add start
    $("#centerTable").click(function () {
        try {
            if ($(".sdh.sdh01.content.left.panel")[0]["children"].length == 1) {
                me.hidelist();
                return;
            }

            var offset = $(".sdh.sdh01.content.left.panel").offset();
            if (offset.left > 0) {
                me.hidelist();
            }
            //20150320  fanzhengzhou del s.副８
            // else {
            // me.showlist();
            // }
            //20150320  fanzhengzhou del e.副８
        } catch (e) {
            console.log(
                'error:$(".sdh01.content.left.hantei_list_showhide").click'
            );
            console.log(e);
        }
    });
    //20150317 add end

    /**
     * 検索条件変更　ボタンクリック
     */
    $(".sdh.sdh01.btn_sdh_02").click(function () {
        try {
            //---20150420 fanzhengzhou add s.
            if (me["SDH02"] != null) {
                //---20150520 fanzhengzhou upd s.#1898
                if ($(".sdh.sdh01.sel_nengetu.ListSelect").val() != null) {
                    $(".sdh.sdh02.input_data.value.hasYmpicker").val(
                        $(".sdh.sdh01.sel_nengetu.ListSelect").val()
                    );
                }
                if ($(".sdh.sdh01.sel_tenpo.ListSelect").val() != null) {
                    //20220217 YIN UPD S
                    // $(".sdh.sdh02.sel_user").val($(".sdh.sdh01.sel_tenpo.ListSelect").val());
                    $(".sdh.sdh02.sel_user")
                        .val($(".sdh.sdh01.sel_tenpo.ListSelect").val())
                        .trigger("change");
                    //20220217 YIN UPD E
                }
                //---20150520 fanzhengzhou upd e.#1898
            }
            //---20150420 fanzhengzhou add e.

            var data = {
                tenpo_cd: "",
            };
            if (me.data && me.data["sdh01_tenpo"]) {
                data["tenpo_cd"] = me.data["sdh01_tenpo"]["KYOTN_CD"];
            }

            me.open_dialog("SDH02", btn_sdh_02_ok_handle, null, data);

            function btn_sdh_02_ok_handle(selected_data) {
                try {
                    //----20220121 sun add s
                    me.selected_data02 = selected_data;
                    //----20220121 sun add e
                    var is_first_load = true;

                    var o_ajax = new gdmz.common.ajax();
                    o_ajax.receive = receive;

                    var tenpo_cd = selected_data["busyo"];

                    //--- 20160127 li INS S
                    me.condition4 = selected_data["condition4"];
                    // me.condition4 = "1";   //test
                    //--- 20160127 li INS E

                    var tantousya_type = "";
                    //店舗全員					・・・区分"E","S"
                    //営業全員					・・・区分"E1"
                    //営業スタッフ					・・・区分"E2"
                    //サービス					・・・区分"S"
                    var tantousya_code = selected_data["user"];
                    if (tantousya_code) {
                        switch (tantousya_code) {
                            //店舗全員
                            case "000":
                                tantousya_type = "ES";
                                break;
                            //営業全員
                            case "001":
                                tantousya_type = "E1";
                                break;
                            //サービス
                            case "002":
                                tantousya_type = "S";
                                break;
                            //営業スタッフ
                            default:
                                tantousya_type = "E2";
                        }
                    } else {
                        tantousya_code = "";
                        tantousya_type = "";
                    }

                    var nengetu = selected_data["date"];
                    if (nengetu) {
                        nengetu = nengetu.replace("/", "");
                    }

                    var url = me.sys_id + "/" + me.id + "/" + "SDH01";

                    var data = {
                        tenpo_cd: tenpo_cd,
                        tantousya_type: tantousya_type,
                        tantousya_code: tantousya_code,
                        nengetu: nengetu,
                        is_tenpo_changed: true,
                        condition: selected_data["condition"],
                        condition1: selected_data["condition1"],
                        condition2: selected_data["condition2"],
                        condition3: selected_data["condition3"],
                        //20160127 YIN INS S
                        condition4: me.condition4,
                        //20160127 YIN INS E
                    };

                    o_ajax.send(url, data, 0);

                    function receive(result) {
                        //20220126 sun add s
                        var dt = JSON.parse(result);
                        if (dt["result"] == false) {
                            me.clsComFnc = new gdmz.common.clsComFnc();
                            me.clsComFnc.MessageBox(
                                "【E9999】 " + dt["data"],
                                "SDH",
                                "OK",
                                "Error",
                                MessageBox.MessageBoxIcon.Err
                            );
                            //return;
                        }
                        //20220126 sun add e
                        var parameters = data;
                        parameters["is_rebuild_nengetu_option_list"] = true;
                        //--- 20160127 li UPD S
                        // me.get_data_receive(result, is_first_load, parameters);
                        me.get_data_receive(
                            result,
                            is_first_load,
                            parameters,
                            me.condition4
                        );
                        //--- 20160127 li UPD E
                        //20220610 ci ins s
                        me.scrolltop = "";
                        //20220610 ci ins e
                    }
                } catch (e) {
                    console.log("error:btn_sdh_02_ok_handle");
                    console.log(e);
                }
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_sdh_02").click');
            console.log(e);
        }
    });

    /**
     *　注文書情報　ボタンクリック
     */
    $(".sdh.sdh01.btn_sdh_03").click(function () {
        try {
            $(".sdh.sdh03.dialog").remove();

            if (me["SDH03"]) {
                me["SDH03"] = null;
            }

            me.open_dialog("SDH03", null, null, me.cur_hantei_item.data());
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_sdh_03").click');
            console.log(e);
        }
    });

    /**
     * 保険・クレジット　ボタンクリック
     */
    $(".sdh.sdh01.btn_sdh_04").click(function () {
        try {
            me.open_dialog("SDH04", null, null, me.cur_hantei_item.data());
        } catch (e) {
            console.log('$(".sdh.sdh01.btn_sdh_04").click');
            console.log(e);
        }
    });

    /**
     *
     */
    $(".sdh01.content.left.hantei_list_showhide").click(function () {
        try {
            if ($(".sdh.sdh01.content.left.panel")[0]["children"].length == 1) {
                me.hidelist();
                return;
            }

            var offset = $(".sdh.sdh01.content.left.panel").offset();
            if (offset.left < 0) {
                me.showlist();
            } else {
                me.hidelist();
            }
        } catch (e) {
            console.log(
                'error:$(".sdh01.content.left.hantei_list_showhide").click'
            );
            console.log(e);
        }
    });

    /**
     *
     */
    $(".sdh.sdh01.result_button_08").click(function () {
        try {
            $(".sdh.sdh03.dialog").remove();

            if (me["SDH03"]) {
                me["SDH03"] = null;
            }

            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;
            var url = me.sys_id + "/" + me.id + "/" + "check_cmnno";
            var data = $.trim($(".sdh.sdh01.result_button_08").data("cmn_no"));
            data = data.replace("　", "");
            o_ajax.send(url, data, 0);
            function receive(result) {
                result = JSON.parse(result);
                if (result["result"] == true) {
                    if (result["row"] != 0) {
                        var temp = {
                            //----20150318 fanzhengzhou upd s.
                            //'CMN_NO' : $(".sdh.sdh01.result_button_08").data("cmn_no"),
                            CMN_NO: $.trim(
                                $(".sdh.sdh01.result_button_08").data("cmn_no")
                            ),
                            //----20150318 fanzhengzhou upd e.
                        };
                        me.open_dialog("SDH03", null, null, temp);
                    } else {
                        me.clsComFnc = new gdmz.common.clsComFnc();
                        me.clsComFnc.MessageBox(
                            "選択した注文書が存在しません。",
                            "SDH",
                            "OK",
                            "Warning",
                            MessageBox.MessageBoxIcon.Warning
                        );
                    }
                }
            }

            // fan update e
        } catch (e) {
            console.log('error:$(".sdh.sdh01.result_button_08").click');
            console.log(e);
        }
    });

    $(".sdh.sdh01.listpopup").click(function () {
        try {
            var dialog = "<div id='dialog' title='Basic dialog'>";
            dialog += "</div>";
            $(".sdh.sdh01.all").after(dialog);

            $(function () {
                $("#dialog").dialog({
                    autoOpen: false,
                    height: 600,
                    close: function () {
                        me.showlist();
                        $(".sdh.sdh01.content.left.panel").append(
                            $(".sdh.sdh01.content.left.hantei_list")
                        );
                        $(".sdh.sdh01.content.left.hantei_list").width(
                            me.hantei_list_w
                        );
                    },
                });
            });
            $("#dialog").append($(".sdh.sdh01.content.left.hantei_list"));
            $(".sdh.sdh01.content.left.hantei_list").width("100%");
            $("#dialog").dialog("open");
        } catch (e) {
            console.log('error:$(".sdh.sdh01.listpopup").click');
            console.log(e);
        }
    });

    //----20220121 sun add s
    /**
     * 進捗確認　ボタンクリック
     */
    $(".sdh.sdh01.btn_sinchoku").click(function () {
        try {
            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;
            var url = me.sys_id + "/" + me.id + "/" + "sinchokuUpd";
            var checked = 0;
            if ($(".sdh.sdh01.btn_sinchoku").text() == "進捗確認") {
                checked = 1;
            }
            var data = {
                SYADAI: me.cur_hantei_item.data("VIN_WMIVDS"),
                CARNO: me.cur_hantei_item.data("VIN_VIS"),
                TENPO: me.cur_hantei_item.data("KNR_STRCD"),
                CHECKED: checked,
            };

            o_ajax.send(url, data, 0);

            function receive(result) {
                if (result) {
                    result = JSON.parse(result);
                    if (!result["result"]) {
                        me.clsComFnc = new gdmz.common.clsComFnc();
                        me.clsComFnc.MessageBox(
                            "【E9999】 " + result["data"],
                            "SDH",
                            "OK",
                            "Error",
                            MessageBox.MessageBoxIcon.Err
                        );
                    } else {
                        if ($(".sdh.sdh01.btn_sinchoku").text() == "進捗確認") {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認済";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                            );
                            for (var i = 0; i < me.color_data.length; i++) {
                                if (
                                    me.color_data[i]["idx"] ==
                                    me.cur_hantei_item.data()["idx"]
                                ) {
                                    me.color_data[i]["CHECKED_YM"] = "1";
                                    me.cur_hantei_item.data()["CHECKED_YM"] =
                                        "1";
                                    break;
                                }
                            }
                        } else {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                            );
                            for (var i = 0; i < me.color_data.length; i++) {
                                if (
                                    me.color_data[i]["idx"] ==
                                    me.cur_hantei_item.data()["idx"]
                                ) {
                                    if (
                                        me.cur_hantei_item.data()[
                                            "HANTEINAME"
                                        ] == "代替確定" ||
                                        me.cur_hantei_item.data()[
                                            "HANTEINAME"
                                        ] == "入庫確定"
                                    ) {
                                        me.color_data[i]["CHECKED_YM"] = "2";
                                        me.cur_hantei_item.data()[
                                            "CHECKED_YM"
                                        ] = "2";
                                    } else if (
                                        me.cur_hantei_item.data()["KEKKA_CD"] ==
                                            "" ||
                                        me.cur_hantei_item.data()["KEKKA_CD"] ==
                                            null
                                    ) {
                                        me.color_data[i]["CHECKED_YM"] = "0";
                                        me.cur_hantei_item.data()[
                                            "CHECKED_YM"
                                        ] = "0";
                                    } else {
                                        me.color_data[i]["CHECKED_YM"] = "2";
                                        me.cur_hantei_item.data()[
                                            "CHECKED_YM"
                                        ] = "2";
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_sinchoku").click');
            console.log(e);
        }
    });

    me.save_refresh = function () {
        try {
            var is_first_load = true;

            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;

            var tenpo_cd = me.selected_data02["busyo"];

            var tantousya_type = "";
            //店舗全員					・・・区分"E","S"
            //営業全員					・・・区分"E1"
            //営業スタッフ					・・・区分"E2"
            //サービス					・・・区分"S"
            var tantousya_code = me.selected_data02["user"];
            if (tantousya_code) {
                switch (tantousya_code) {
                    //店舗全員
                    case "000":
                        tantousya_type = "ES";
                        break;
                    //営業全員
                    case "001":
                        tantousya_type = "E1";
                        break;
                    //サービス
                    case "002":
                        tantousya_type = "S";
                        break;
                    //営業スタッフ
                    default:
                        tantousya_type = "E2";
                }
            } else {
                tantousya_code = "";
                tantousya_type = "";
            }

            var nengetu = me.selected_data02["date"];
            if (nengetu) {
                nengetu = nengetu.replace("/", "");
            }

            var url = me.sys_id + "/" + me.id + "/" + "SDH01";

            var data = {
                tenpo_cd: tenpo_cd,
                tantousya_type: tantousya_type,
                tantousya_code: tantousya_code,
                nengetu: nengetu,
                is_tenpo_changed: true,
                condition: me.selected_data02["condition"],
                condition1: me.selected_data02["condition1"],
                condition2: me.selected_data02["condition2"],
                condition3: me.selected_data02["condition3"],
                //20160127 YIN INS S
                condition4: me.condition4,
                //20160127 YIN INS E
            };

            o_ajax.send(url, data, 0);

            function receive(result) {
                var parameters = data;
                parameters["is_rebuild_nengetu_option_list"] = true;
                //--- 20160127 li UPD S
                // me.get_data_receive(result, is_first_load, parameters);
                me.get_data_receive(
                    result,
                    is_first_load,
                    parameters,
                    me.condition4
                );

                for (var i = 0; i < me.data["sdh01_hantei_list"].length; i++) {
                    if (
                        me.data["sdh01_hantei_list"][i]["VIN_VIS"] ==
                            me.tmp_clicked_item["VIN_VIS"] &&
                        me.data["sdh01_hantei_list"][i]["VIN_WMIVDS"] ==
                            me.tmp_clicked_item["VIN_WMIVDS"]
                    ) {
                        var idx = me.data["sdh01_hantei_list"][i]["idx"];
                        me.cur_hantei_item.css("background-color", "");
                        var hantei_list = me.data["sdh01_hantei_list"];
                        var next_idx = idx;
                        var next_hantei_data = hantei_list[next_idx];

                        var next_item = $(
                            ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                                "_" +
                                next_hantei_data["idx"]
                        );
                        me.cur_hantei_item = next_item;

                        if (me.condition4 == 4) {
                            for (var i = 0; i < me.color_data.length; i++) {
                                var dt = me.color_data[i];
                                var hantei_item = $(
                                    ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                                        "_" +
                                        dt["idx"]
                                );

                                if (dt["CHECKED_YM"] == 1) {
                                    hantei_item.css(
                                        "background-color",
                                        "darkgray"
                                    );
                                } else if (dt["CHECKED_YM"] == 2) {
                                    hantei_item.css("background-color", "gold");
                                }
                            }
                            if (me.cur_hantei_item.data()["CHECKED_YM"] == 1) {
                                $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                    "進捗確認済";
                                $(".sdh.sdh01.btn_sinchoku").css(
                                    "background",
                                    "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                                );
                            } else {
                                $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                    "進捗確認";
                                $(".sdh.sdh01.btn_sinchoku").css(
                                    "background",
                                    "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                                );
                            }
                        }

                        me.cur_hantei_item.css("background-color", "#FFCCCC");

                        me.get_hantei_item_data(me.cur_hantei_item.data());
                        //20220610 ci ins s
                        $(".sdh.sdh01.content.left.hantei_list").scrollTop(
                            me.scrolltop
                        );
                        //20220610 ci ins e
                        break;
                    }
                }
                //--- 20160127 li UPD E
            }
        } catch (e) {
            console.log("error:btn_sdh_02_ok_handle");
            console.log(e);
        }
    };
    //----20220121 sun add e

    /**
     * 保存　ボタンクリック
     */
    $(".sdh.sdh01.btn_save").click(function () {
        try {
            //20160303 YIN INS S
            if (is_changed() == true) {
                //20160303 YIN INS E
                var o_ajax = new gdmz.common.ajax();
                o_ajax.receive = receive;
                //管理部署コード
                var strKanribu = "";
                //-----20141022  #795  zhenghuiyun  ins  e
                //管理サービス部署コード
                var strKanrisv = "";
                //管理サービス部署コード
                var strKanrisls = "";
                //判定コード
                var strResultCode08 = "";
                var strResultVale08 = "";
                var strResultName08 = "";
                var strResultMemo = "";
                var strResultCode = new Array();
                var strResultVale = new Array();
                var strResultName = new Array();

                for (var i = 1; i < 8; i++) {
                    if (
                        $(".sdh.sdh01.result_text_0" + i).data("code") != null
                    ) {
                        strResultCode[i - 1] = $(
                            ".sdh.sdh01.result_text_0" + i
                        ).data("code");
                    } else {
                        strResultCode[i - 1] = "";
                    }

                    strResultName[i - 1] = $(
                        ".sdh.sdh01.result_select_0" + i
                    ).val();
                    var strLength = 128;

                    if ($(".sdh.sdh01.result_text_0" + i).val() != null) {
                        strResultVale[i - 1] = $(".sdh.sdh01.result_text_0" + i)
                            .val()
                            .trim();
                        if (me.condition4 == "1") {
                            strLength = 100;
                        }
                        if (
                            !me.btn_save_check(
                                $(".sdh.sdh01.hanteinengetu_0" + i).text(),
                                strResultVale[i - 1],
                                strLength,
                                $(".sdh.sdh01.result_text_0" + i)
                            )
                        ) {
                            return;
                        }
                    } else {
                        strResultVale[i - 1] = "";
                    }
                }

                //最終結果
                if ($(".sdh.sdh01.result_text_08").data("code") != null) {
                    strResultCode08 = $(".sdh.sdh01.result_text_08").data(
                        "code"
                    );
                }

                strResultName08 = $(".sdh.sdh01.result_select_08").val();

                if ($(".sdh.sdh01.result_text_08").val() != null) {
                    strResultVale08 = $(".sdh.sdh01.result_text_08")
                        .val()
                        .trim();
                    //--- 20160127 li UPD S
                    //if (!me.btn_save_check("最終結果", strResultVale08, 120, $(".sdh.sdh01.result_text_08")))
                    if (me.condition4 == "1" || me.condition4 == "2") {
                        strTitle = "新車６ヶ月点検判定";
                        strLength = 100;
                    }
                    //20190227 YIN INS S
                    else if (me.condition4 == "3") {
                        strTitle = "中古１ヶ月点検判定";
                    }
                    //20190227 YIN INS E
                    else {
                        //20190312 wy UPD S
                        //strTitle = "最終結果：";
                        strTitle = "最終結果";
                        //20190312 wy UPD E
                    }
                    if (
                        !me.btn_save_check(
                            strTitle,
                            strResultVale08,
                            strLength,
                            $(".sdh.sdh01.result_text_08")
                        )
                    ) {
                        //--- 20160127 li UPD E
                        return;
                    }
                }
                //
                if ($(".sdh.sdh01.sdh01_07.MEMO.value").val() != null) {
                    strResultMemo = $(".sdh.sdh01.sdh01_07.MEMO.value")
                        .val()
                        .trim();
                    if (
                        !me.btn_save_check(
                            "フリーメモ",
                            strResultMemo,
                            250,
                            $(".sdh.sdh01.sdh01_07.MEMO.value")
                        )
                    ) {
                        return;
                    }
                }

                if (me.data["sdh01_keiyakusya"]) {
                    if (me.data["sdh01_keiyakusya"].length > 0) {
                        strKanribu =
                            me.data["sdh01_keiyakusya"][0]["KNR_STRCD"];
                        strKanrisv =
                            me.data["sdh01_keiyakusya"][0]["SRV_SRVSTRCD"];
                        strKanrisls =
                            me.data["sdh01_keiyakusya"][0]["SYAIN_NO"];
                    }
                }

                var url = me.sys_id + "/" + me.id + "/" + "SDH01_01";
                var data = {
                    SYAKENBI: me.cur_hantei_item.data("VCLIPEDT"),
                    YYMM: $(".sdh.sdh01.sel_nengetu").val(), //判定年月
                    SYADAI: me.cur_hantei_item.data("VIN_WMIVDS"),
                    CARNO: me.cur_hantei_item.data("VIN_VIS"),
                    KANRIBU: strKanribu, //管理部署コード
                    KANRISV: strKanrisv, //管理サービス部署コード
                    KANRISLS: strKanrisls, //管理担当スタッフ
                    HANTEI1_CD: strResultCode[0],
                    HANTEI1: strResultVale[0],
                    HANTEI2_CD: strResultCode[1],
                    HANTEI2: strResultVale[1],
                    HANTEI3_CD: strResultCode[2],
                    HANTEI3: strResultVale[2],
                    HANTEI4_CD: strResultCode[3],
                    HANTEI4: strResultVale[3],
                    HANTEI5_CD: strResultCode[4],
                    HANTEI5: strResultVale[4],
                    HANTEI6_CD: strResultCode[5],
                    HANTEI6: strResultVale[5],
                    HANTEI7_CD: strResultCode[6],
                    HANTEI7: strResultVale[6],
                    KEKKA_CD: strResultCode08,
                    KEKKA: strResultVale08,
                    SYAKEN_SU: me.timeline.count, //車検数
                    REVISION: "", //リビジョン　SDH01Controllerを作成して、Add 1です
                    UPDSYACD: "", //更新担当者
                    UPDYMDHM: "", //更新日時
                    MEMO: strResultMemo,
                    //--- 20160127 li INS S
                    condition4: me.condition4,
                    //--- 20160127 li INS E
                    //----20220121 sun add s
                    TENPO: me.cur_hantei_item.data("KNR_STRCD"),
                    //----20220121 sun add e
                };

                o_ajax.send(url, data, 0);

                function receive(result) {
                    if (result) {
                        result = JSON.parse(result);
                        if (result["result"] == true) {
                            var saved_data = Array();
                            for (var i = 0; i < strResultVale.length; i++) {
                                var idx = i + 1;
                                saved_data["HANTEI" + idx] = strResultVale[i];
                                //fuxiaolin 20150420 add
                                saved_data["NAME" + idx] = strResultName[i];
                                saved_data["HANTEI" + idx + "_CD"] =
                                    strResultCode[i];
                            }
                            saved_data["KEKKA"] = strResultVale08;
                            //20150422 fugyorin 20150420 #1806 add start
                            saved_data["KEKKA_CD"] = strResultCode08;
                            saved_data["NAME"] = strResultName08;
                            //20150422 fugyorin 20150420 add end
                            saved_data["MEMO"] = strResultMemo;
                            me.data["saved_data"] = saved_data;

                            //20150422 fugyorin #1820 add start
                            //20160127 YIN INS S
                            //----20220121 sun upd s
                            //if (me.condition4 == "0")
                            if (me.condition4 == "0" || me.condition4 == "4") {
                                //----20220121 sun upd e
                                //20160127 YIN INS E
                                //20160229 YIN UPD S
                                //me.cur_hantei_item.find(".sdh.sdh01.sdh01_08.item.KEKKA").text("(最終)" + saved_data["KEKKA"].substr(0, 7));
                                //me.cur_hantei_item.find(".sdh.sdh01.sdh01_08.item.MAEGETU").text("(前月)" + saved_data["HANTEI6"].substr(0, 7));
                                if (saved_data["NAME"].indexOf(":") > 0) {
                                    me.cur_hantei_item
                                        .find(".sdh.sdh01.sdh01_08.item.KEKKA")
                                        .text(
                                            saved_data["NAME"].substr(
                                                0,
                                                saved_data["NAME"].indexOf(":")
                                            )
                                        );
                                } else {
                                    me.cur_hantei_item
                                        .find(".sdh.sdh01.sdh01_08.item.KEKKA")
                                        .text(saved_data["NAME"].substr(0, 7));
                                }
                                if (strResultName[5].indexOf(":") > 0) {
                                    me.cur_hantei_item
                                        .find(
                                            ".sdh.sdh01.sdh01_08.item.MAEGETU"
                                        )
                                        .text(
                                            strResultName[5].substr(
                                                0,
                                                strResultName[5].indexOf(":")
                                            )
                                        );
                                } else {
                                    me.cur_hantei_item
                                        .find(
                                            ".sdh.sdh01.sdh01_08.item.MAEGETU"
                                        )
                                        .text(strResultName[5].substr(0, 7));
                                }
                                if (strResultName[6].indexOf(":") > 0) {
                                    me.cur_hantei_item
                                        .find(
                                            ".sdh.sdh01.sdh01_08.item.TOUGETU"
                                        )
                                        .text(
                                            strResultName[6].substr(
                                                0,
                                                strResultName[6].indexOf(":")
                                            )
                                        );
                                } else {
                                    me.cur_hantei_item
                                        .find(
                                            ".sdh.sdh01.sdh01_08.item.TOUGETU"
                                        )
                                        .text(strResultName[6].substr(0, 7));
                                }

                                //20160229 YIN UPD E
                                //20160127 YIN INS S
                            } else if (
                                me.condition4 == "1" ||
                                me.condition4 == "2"
                            ) {
                                strResultName08 = $(
                                    ".sdh.sdh01.result_select_08"
                                ).val();
                                strResultName07 = $(
                                    ".sdh.sdh01.result_select_07"
                                ).val();
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.KEKKA1_CD")
                                    .text(strResultName07);
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.TOUGETU")
                                    .text("　" + strResultVale[6]);
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.KEKKA6_CD")
                                    .text(strResultName08);
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.KEKKA")
                                    .text("　" + saved_data["KEKKA"]);
                            }
                            //20160127 YIN INS E
                            //20190227 YIN INS S
                            else if (me.condition4 == "3") {
                                strResultName08 = $(
                                    ".sdh.sdh01.result_select_08"
                                ).val();
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.KEKKA1_CD")
                                    .text(strResultName08);
                                me.cur_hantei_item
                                    .find(".sdh.sdh01.sdh01_08.item.TOUGETU")
                                    .text("　" + saved_data["KEKKA"]);
                            }
                            //20190227 YIN INS E

                            //20150422 fugyorin #1820 add end
                            if (me.data["first_data"]) {
                                delete me.data["first_data"];
                            }
                            //20150609 Update start
                            //me.clsComFnc = new gdmz.common.clsComFnc();
                            //me.clsComFnc.MsgBoxBtnFnc.Yes = me.func_button_ok;
                            //me.clsComFnc.MessageBox("保存完了しました。", "SDH", "OK", "Warning", MessageBox.MessageBoxIcon.Warning);

                            //----20220121 sun upd s
                            //me.get_hantei_item_data(me.cur_hantei_item.data());
                            //20220217 YIN UPD S
                            // if (me.condition4 == "4")
                            // {
                            // me.tmp_clicked_item = me.cur_hantei_item.data();
                            // me.save_refresh();
                            // }
                            // else
                            // {
                            // me.get_hantei_item_data(me.cur_hantei_item.data());
                            // };
                            me.tmp_clicked_item = me.cur_hantei_item.data();
                            me.save_refresh();
                            //20220217 YIN UPD E
                            //----20220121 sun upd e

                            //20150609 Update End

                            var kekka = strResultVale08;
                            if (kekka != null) {
                                kekka = kekka.trim();
                                if (kekka.indexOf("代替　注文書=") > -1) {
                                    var cmn_no = kekka.replace(
                                        "代替　注文書=",
                                        ""
                                    );
                                    cmn_no = cmn_no.trim();
                                    $(".sdh.sdh01.result_button_08").data(
                                        "cmn_no",
                                        cmn_no
                                    );
                                    $("#sdh_mcDropdown8").parent().hide();
                                    $(".sdh.sdh01.result_button_08").show();
                                    //20160907 YIN INS S
                                    $(".sdh.sdh01.result_button_08").click(
                                        function () {
                                            try {
                                                $(".sdh.sdh03.dialog").remove();

                                                if (me["SDH03"]) {
                                                    me["SDH03"] = null;
                                                }

                                                var o_ajax =
                                                    new gdmz.common.ajax();
                                                o_ajax.receive = receive;
                                                var url =
                                                    me.sys_id +
                                                    "/" +
                                                    me.id +
                                                    "/" +
                                                    "check_cmnno";
                                                var data = $.trim(
                                                    $(
                                                        ".sdh.sdh01.result_button_08"
                                                    ).data("cmn_no")
                                                );
                                                data = data.replace("　", "");
                                                o_ajax.send(url, data, 0);
                                                function receive(result) {
                                                    result = JSON.parse(result);
                                                    if (
                                                        result["result"] == true
                                                    ) {
                                                        if (
                                                            result["row"] != 0
                                                        ) {
                                                            var temp = {
                                                                //----20150318 fanzhengzhou upd s.
                                                                //'CMN_NO' : $(".sdh.sdh01.result_button_08").data("cmn_no"),
                                                                CMN_NO: $.trim(
                                                                    $(
                                                                        ".sdh.sdh01.result_button_08"
                                                                    ).data(
                                                                        "cmn_no"
                                                                    )
                                                                ),
                                                                //----20150318 fanzhengzhou upd e.
                                                            };
                                                            me.open_dialog(
                                                                "SDH03",
                                                                null,
                                                                null,
                                                                temp
                                                            );
                                                        } else {
                                                            me.clsComFnc =
                                                                new gdmz.common.clsComFnc();
                                                            me.clsComFnc.MessageBox(
                                                                "選択した注文書が存在しません。",
                                                                "SDH",
                                                                "OK",
                                                                "Warning",
                                                                MessageBox
                                                                    .MessageBoxIcon
                                                                    .Warning
                                                            );
                                                        }
                                                    }
                                                }

                                                // fan update e
                                            } catch (e) {
                                                console.log(
                                                    'error:$(".sdh.sdh01.result_button_08").click'
                                                );
                                                console.log(e);
                                            }
                                        }
                                    );
                                    //20160907 YIN INS E
                                } else {
                                    $("#sdh_mcDropdown8").parent().show();
                                    $(".sdh.sdh01.result_button_08").hide();
                                    $(".sdh.sdh01.result_text_08").val(kekka);
                                }
                            } else {
                                kekka = "";
                                $("#sdh_mcDropdown8").parent().show();
                                $(".sdh.sdh01.result_button_08").hide();
                                $(".sdh.sdh01.result_text_08").val(kekka);
                            }

                            //----20220121 sun add s
                            if (me.condition4 == "4") {
                                if (
                                    strResultCode08 != "" &&
                                    strResultCode08 != null
                                ) {
                                    for (
                                        var i = 0;
                                        i < me.color_data.length;
                                        i++
                                    ) {
                                        if (
                                            me.color_data[i]["idx"] ==
                                            me.cur_hantei_item.data()["idx"]
                                        ) {
                                            me.color_data[i]["CHECKED_YM"] =
                                                "1";
                                            break;
                                        }
                                    }
                                }
                            }
                            //----20220121 sun add e
                        } else {
                            me.clsComFnc = new gdmz.common.clsComFnc();
                            me.clsComFnc.MessageBox(
                                "保存失敗しました。",
                                "SDH",
                                "OK",
                                "Warning",
                                MessageBox.MessageBoxIcon.Warning
                            );
                        }
                    }
                }
                //20160303 YIN INS S
            } else {
            }

            function is_changed() {
                for (var i = 1; i < 9; i++) {
                    var sel = $(".sdh.sdh01.result_select_0" + i)
                        .val()
                        .trim();
                    if (sel == "") {
                        sel = null;
                    }

                    var txt = $(".sdh.sdh01.result_text_0" + i)
                        .val()
                        .trim();
                    if (txt == "") {
                        txt = null;
                    }

                    var code = $(".sdh.sdh01.result_text_0" + i).data("code");
                    if (code == "") {
                        code = null;
                    }
                    // if (txt.trim() != "" || sel.trim() != "") {

                    if (me.data["first_data"]) {
                        if (i == 8) {
                            if (txt != me.data["first_data"]["KEKKA"]) {
                                return true;
                            }

                            if (code != me.data["first_data"]["KEKKA_CD"]) {
                                return true;
                            }
                        } else if (i < 8) {
                            if (txt != me.data["first_data"]["HANTEI" + i]) {
                                return true;
                            }

                            if (
                                code !=
                                me.data["first_data"]["HANTEI" + i + "_CD"]
                            ) {
                                return true;
                            }
                        }
                    }
                    if (me.data["saved_data"]) {
                        if (i == 8) {
                            if (txt != me.data["saved_data"]["KEKKA"]) {
                                return true;
                            }

                            if (code != me.data["saved_data"]["KEKKA_CD"]) {
                                return true;
                            }
                        } else if (i < 8) {
                            if (txt != me.data["saved_data"]["HANTEI" + i]) {
                                return true;
                            }

                            if (
                                code !=
                                me.data["saved_data"]["HANTEI" + i + "_CD"]
                            ) {
                                return true;
                            }
                        }
                    }
                }
                var memo = $(".sdh.sdh01.sdh01_07.MEMO.value").val();
                memo = memo.trim();
                if (me.data["first_data"]) {
                    if (memo != me.data["first_data"]["MEMO"]) {
                        return true;
                    }
                }
                if (me.data["saved_data"]) {
                    if (memo != me.data["saved_data"]["MEMO"]) {
                        return true;
                    }
                }

                return false;
            }
            //20160303 YIN INS E
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_save").click');
            console.log(e);
        }
    });

    $(".sdh.sdh01.sel_tenpo").on("focus", function () {
        me.pre_tanto = $(".sdh.sdh01.sel_tenpo").val();
    });

    /**
     * 担当者リスト
     */
    $(".sdh.sdh01.sel_tenpo").change(function () {
        try {
            me.check_change(func_yes, func_no);

            function func_yes() {
                //20220217 YIN INS S
                me.selected_data02["user"] = $(".sdh.sdh01.sel_tenpo").val();
                //20220217 YIN INS E
                me.get_data(false);
            }

            function func_no() {
                $(".sdh.sdh01.sel_tenpo").val(me.pre_tanto);
            }
        } catch (e) {
            console.log('$(".sdh.sdh01.sel_tenpo").change');
            console.log(e);
        }
    });

    $(".sdh.sdh01.sel_nengetu").on("focus", function () {
        me.pre_nengetu = $(".sdh.sdh01.sel_nengetu").val();
    });

    /**
     * 対象年月リスト
     */
    $(".sdh.sdh01.sel_nengetu").change(function () {
        try {
            me.check_change(func_yes, func_no);

            function func_yes() {
                //20220217 YIN INS S
                me.selected_data02["date"] = $(".sdh.sdh01.sel_nengetu").val();
                //20220217 YIN INS E
                me.get_data(false);
            }

            function func_no() {
                $(".sdh.sdh01.sel_nengetu").val(me.pre_nengetu);
            }
        } catch (e) {
            console.log('$(".sdh.sdh01.sel_nengetu").change');
            console.log(e);
        }
    });

    /**
     * 画面更新　ボタンクリック
     */
    $(".sdh.sdh01.btn_reload").click(function () {
        try {
            me.check_change(func_yes, func_no);

            function func_yes() {
                //20220217 lujunxia upd s
                //me.get_hantei_item_data(me.cur_hantei_item.data());
                //20220217 YIN UPD S
                // if (me.condition4 == "4")
                // {
                // me.tmp_clicked_item = me.cur_hantei_item.data();
                // me.save_refresh();
                // }
                // else
                // {
                // me.get_hantei_item_data(me.cur_hantei_item.data());
                // };
                me.tmp_clicked_item = me.cur_hantei_item.data();
                me.save_refresh();
                //20220217 YIN UPD E
                //20220217 lujunxia upd e
            }

            function func_no() {}
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_reload").click');
            console.log(e);
        }
    });

    /**
     * 前の車両　ボタンクリック
     */
    $(".sdh.sdh01.btn_prev_syaryou").click(function () {
        try {
            // var prev_btn = $(".sdh.sdh01.btn_prev_syaryou");
            var item = me.cur_hantei_item;
            var cur_idx = item.data("idx");
            cur_idx = parseInt(cur_idx);
            var hantei_list = me.data["sdh01_hantei_list"];

            if (cur_idx > 0) {
                me.check_change(func_yes, func_no);

                function func_yes() {
                    me.cur_hantei_item.css("background-color", "");

                    var prev_idx = cur_idx - 1;
                    var prev_hantei_data = hantei_list[prev_idx];
                    var prev_item = $(
                        ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                            "_" +
                            prev_hantei_data["idx"]
                    );
                    me.cur_hantei_item = prev_item;

                    //----20220121 sun add s
                    if (me.condition4 == 4) {
                        for (var i = 0; i < me.color_data.length; i++) {
                            var dt = me.color_data[i];
                            var hantei_item = $(
                                ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                                    "_" +
                                    dt["idx"]
                            );

                            if (dt["CHECKED_YM"] == 1) {
                                hantei_item.css("background-color", "darkgray");
                            } else if (dt["CHECKED_YM"] == 2) {
                                hantei_item.css("background-color", "gold");
                            }
                        }

                        if (me.cur_hantei_item.data()["CHECKED_YM"] == 1) {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認済";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                            );
                        } else {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                            );
                        }
                    }
                    //----20220121 sun add e

                    me.cur_hantei_item.css("background-color", "#FFCCCC");

                    me.get_hantei_item_data(me.cur_hantei_item.data());
                }

                function func_no() {}
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_prev_syaryou").click');
            console.log(e);
        }
    });

    /**
     * 次の車両　ボタンクリック
     */
    $(".sdh.sdh01.btn_next_syaryou").click(function () {
        try {
            //次へボタン　オブジェクト
            // var next_btn = $(".sdh.sdh01.btn_next_syaryou");
            //現在選択中の判定項目
            var item = me.cur_hantei_item;
            //現在選択中の判定項目の判定リスト内のインデクス
            var cur_idx = item.data("idx");
            //整数に変換
            cur_idx = parseInt(cur_idx);
            //判定リスト
            var hantei_list = me.data["sdh01_hantei_list"];

            if (cur_idx < hantei_list.length - 1) {
                me.check_change(func_yes, func_no);

                function func_yes() {
                    me.cur_hantei_item.css("background-color", "");

                    var next_idx = cur_idx + 1;
                    var next_hantei_data = hantei_list[next_idx];

                    var next_item = $(
                        ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                            "_" +
                            next_hantei_data["idx"]
                    );
                    me.cur_hantei_item = next_item;

                    //----20220121 sun add s
                    if (me.condition4 == 4) {
                        for (var i = 0; i < me.color_data.length; i++) {
                            var dt = me.color_data[i];
                            var hantei_item = $(
                                ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                                    "_" +
                                    dt["idx"]
                            );

                            if (dt["CHECKED_YM"] == 1) {
                                hantei_item.css("background-color", "darkgray");
                            } else if (dt["CHECKED_YM"] == 2) {
                                hantei_item.css("background-color", "gold");
                            }
                        }
                        if (me.cur_hantei_item.data()["CHECKED_YM"] == 1) {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認済";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                            );
                        } else {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                            );
                        }
                    }
                    //----20220121 sun add e

                    me.cur_hantei_item.css("background-color", "#FFCCCC");

                    me.get_hantei_item_data(me.cur_hantei_item.data());
                }

                function func_no() {}
            }
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_next_syaryou").click');
            console.log(e);
        }
    });

    //20160322 add start
    $(".sdh.sdh01.help_area").dialog({
        autoOpen: false,
        modal: false,
        width: 550,
        height: 420,
        position: {
            of: ".sdh.sdh01.content",
            at: "right bottom",
            my: "right bottom",
        },
        resizable: false,
    });

    /**
     * ヘルプボタンクリック
     */
    $(".sdh.sdh01.btn_help").click(function () {
        try {
            $(".sdh.sdh01.help_area").dialog("option", "title", "ヘルプ");
            $(".sdh.sdh01.help_area").css("visibility", "visible");
            $(".sdh.sdh01.help_area").dialog("open");
        } catch (e) {
            console.log('error:$(".sdh.sdh01.btn_help").click');
            console.log(e);
        }
    });
    //20160322 add end

    /**
     * ショートカット登録
     */
    me.add_shortcut = function () {
        /**
         * ページアップ ショートカット登録
         */
        shortcut.add("PAGEUP", function () {
            if (me.PgUpPgDnFlag) {
                $(".sdh.sdh01.btn_prev_syaryou").click();
            }
        });

        /**
         * ページダウン ショートカット登録
         */
        shortcut.add("PAGEDOWN", function () {
            if (me.PgUpPgDnFlag) {
                $(".sdh.sdh01.btn_next_syaryou").click();
            }
        });
    };

    /**
     * ショートカット削除
     */
    me.remove_shortcut = function () {
        /**
         * ページアップ ショートカット削除
         */
        shortcut.remove("PAGEUP");

        /**
         * ページダウン ショートカット削除
         */
        shortcut.remove("PAGEDOWN");
    };

    // ==========
    // = イベント end =
    // ==========

    // ==========
    // = メソッド start =
    // ==========

    me.get_data = function (is_first_load) {
        try {
            if (is_first_load == undefined) {
                is_first_load = true;
            }

            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;

            var tenpo_cd = "";
            if (me.data && me.data["sdh01_tenpo"]) {
                tenpo_cd = me.data["sdh01_tenpo"]["KYOTN_CD"];
            }

            var tantousya_type = "";
            //店舗全員					・・・区分"E","S"
            //営業全員					・・・区分"E1"
            //営業スタッフ					・・・区分"E2"
            //サービス					・・・区分"S"
            var tantousya_code = $(".sdh.sdh01.sel_tenpo").val();
            if (tantousya_code) {
                switch (tantousya_code) {
                    //店舗全員
                    case "000":
                        tantousya_type = "ES";
                        break;
                    //営業全員
                    case "001":
                        tantousya_type = "E1";
                        break;
                    //サービス
                    case "002":
                        tantousya_type = "S";
                        break;
                    //営業スタッフ
                    default:
                        tantousya_type = "E2";
                }
            } else {
                tantousya_code = "";
                tantousya_type = "";
            }

            var nengetu = $(".sdh.sdh01.sel_nengetu").val();
            if (nengetu) {
            } else {
                nengetu = "";
            }
            var selCondition = "0";
            selCondition = $(".sdh.sdh02.selectconditions").val();

            var selCondition1 = "000";
            //20220217 YIN UPD S
            // selCondition1 = $(".sdh.sdh02.selectconditions1").val();
            if (
                $(".sdh.sdh02.selectconditions4").val() == 0 ||
                $(".sdh.sdh02.selectconditions4").val() == 4
            ) {
                selCondition1 = "";
                var selected = $(".sdh.sdh02.selectconditions1m").select2(
                    "data"
                );
                var temp = "";
                for (var i = 0; i < selected.length; i++) {
                    temp = temp + selected[i]["id"] + ",";
                }
                selCondition1 = temp.substring(0, temp.length - 1);

                //20220218 YIN INS S
                if ($(".sdh.sdh02.selectconditions4").val() == 4) {
                    selCondition1 = me.selected_data02["condition1"];
                }
                //20220218 YIN INS E
            } else {
                selCondition1 = $(".sdh.sdh02.selectconditions1").val();
            }
            //20220217 YIN UPD E

            var selCondition2 = "000";
            selCondition2 = $(".sdh.sdh02.selectconditions2").val();

            var selCondition3 = "0";
            selCondition3 = $(".sdh.sdh02.selectconditions3").val();

            var url = me.sys_id + "/" + me.id + "/" + "SDH01";

            var data = {};

            if (tantousya_type != "" && tantousya_code != "" && nengetu != "") {
                data = {
                    tenpo_cd: tenpo_cd,
                    tantousya_type: tantousya_type,
                    tantousya_code: tantousya_code,
                    nengetu: nengetu,
                    condition: selCondition,
                    condition1: selCondition1,
                    condition2: selCondition2,
                    condition3: selCondition3,
                    //--- 20160127 li INS S
                    condition4: me.condition4,
                    //--- 20160127 li INS E
                };
            }

            data["is_first_load"] = is_first_load;
            o_ajax.send(url, data, 0);

            function receive(result) {
                //20220126 sun add s
                var dt = JSON.parse(result);
                if (dt["result"] == false) {
                    me.clsComFnc = new gdmz.common.clsComFnc();
                    me.clsComFnc.MessageBox(
                        dt["data"],
                        "SDH",
                        "OK",
                        "Warning",
                        MessageBox.MessageBoxIcon.Warning
                    );
                    return;
                }
                //20220126 sun add e
                var parameters = data;
                parameters["is_rebuild_nengetu_option_list"] = false;
                //--- 20160127 li UPD S
                // me.get_data_receive(result, is_first_load, parameters);
                me.get_data_receive(
                    result,
                    is_first_load,
                    parameters,
                    me.condition4
                );
                //--- 20160127 li UPD E
                //--- 20160304 li INS S
                if (me.sdh_sdh01_result_type) {
                    me.sdh_sdh01_result_01 = $(".sdh.sdh01.result_01").html();
                    me.sdh_sdh01_result_02 = $(".sdh.sdh01.result_02").html();
                    me.sdh_sdh01_result_03 = $(".sdh.sdh01.result_03").html();
                    me.sdh_sdh01_result_04 = $(".sdh.sdh01.result_04").html();
                    me.sdh_sdh01_result_05 = $(".sdh.sdh01.result_05").html();
                    me.sdh_sdh01_result_06 = $(".sdh.sdh01.result_06").html();
                    me.sdh_sdh01_result_07 = $(".sdh.sdh01.result_07").html();
                    //--- 20160304 li INS E
                    //--- 20160304 zhenghuiyun INS S
                    me.sdh_sdh01_result_08 = $(".sdh.sdh01.result_08").html();
                    //--- 20160304 zhenghuiyun INS E
                    me.sdh_sdh01_result_type = false;
                }
                //20220610 ci ins s
                me.scrolltop = "";
                //20220610 ci ins e
            }
        } catch (e) {
            console.log("error:get_data");
            console.log(e);
        }
    };

    /**
     * 検索結果取得後の処理
     * @param {String} result 検索結果
     * @param {Boolean} is_first_load 初期表示かどうかのフラグ
     * @param {Array} parameters 各パラメータ
     *  {String} tenpo_cd 店舗コード
     *  {String} tantousya_type 担当者タイプ
     *  {String} tantousya_code 担当者コード
     *  {String} nengetu 指定の判定年月
     *  {Boolean} is_rebuild_nengetu_option_list 判定年月一覧を再構築するかどうかのフラグ
     */
    //--- 20160127 li UPD S
    // me.get_data_receive = function(result, is_first_load, parameters)
    me.get_data_receive = function (
        result,
        is_first_load,
        parameters,
        con4 //--- 20160127 li UPD E
    ) {
        try {
            if (is_first_load == undefined) {
                is_first_load = true;
            }

            me.data = JSON.parse(result);

            //初期化 s
            $(".sdh.sdh01.content.left.hantei_list").empty();
            $(".sdh.sdh01.content.left.hantei_list").scrollTop(0);
            $(".sdh.sdh01.sdh01_02.value").text("");
            $(".sdh.sdh01.sdh01_03.value").text("");
            $(".sdh.sdh01.sdh01_04.nyuko_rireki").empty();

            me.timeline.tempflag = false;
            $(".sdh.sdh01.div_timeline").empty();
            $(".sdh.sdh01.sdh01_07.value").text("");

            for (i = 1; i < 8; i++) {
                $(".sdh.sdh01.btn_rireki_0" + i).empty();
                $(".sdh.sdh01.result_select_0" + i).val("");
                $(".sdh.sdh01.result_select_0" + i).prop(
                    "disabled",
                    "disabled"
                );
                $(".sdh.sdh01.result_text_0" + i).val("");
                $(".sdh.sdh01.result_text_0" + i).data("code", "");
            }

            $("#sdh_mcDropdown8").parent().show();
            $(".sdh.sdh01.result_button_08").hide();
            $(".sdh.sdh01.result_select_08").val("");
            $(".sdh.sdh01.result_select_08").prop("disabled", "disabled");
            $(".sdh.sdh01.result_text_08").val("");
            $(".sdh.sdh01.result_text_08").data("code", "");
            //20180130 lqs INS S
            $(".sdh.sdh01.btn_rireki_08").empty();
            $(".sdh.sdh01.sdh01_07.MEMO").val("");
            //20180130 lqs INS E

            $(".sdh.sdh01.btn_sdh_03").button("disable");
            $(".sdh.sdh01.btn_sdh_05").button("disable");
            //---20150831 li INS S.
            $(".sdh.sdh01.btn_sdh_06").button("disable");
            //---20150831 li INS E.
            $(".sdh.sdh01.btn_sdh_04").button("disable");
            $(".sdh.sdh01.btn_reload").button("disable");
            //----20220121 sun add s
            $(".sdh.sdh01.btn_sinchoku").button("disable");
            //----20220121 sun add e
            $(".sdh.sdh01.btn_save").button("disable");
            $(".sdh.sdh01.btn_prev_syaryou").button("disable");
            $(".sdh.sdh01.btn_next_syaryou").button("disable");

            $(".sdh.sdh01.current_all.lbl_count.page_no").text("0");
            $(".sdh.sdh01.current_all.lbl_count.total").text("0");
            $(".sdh.sdh01.btn_tanto_rireki.tooltip.yellow-tooltip").hide();

            //----20220121 sun add s
            if (con4 == 4) {
                $(".sdh.sdh01.btn_sinchoku").show();
                $(".sdh.sdh01.btn_sinchoku").parent().show();
                if (
                    me.data.hasOwnProperty("sdh01_tencyou") &&
                    me.data["sdh01_tencyou"] == 1
                ) {
                    $(".sdh.sdh01.btn_sinchoku").button("enable");
                } else {
                    $(".sdh.sdh01.btn_sinchoku").button("disable");
                }
            } else {
                $(".sdh.sdh01.btn_sinchoku").hide();
                $(".sdh.sdh01.btn_sinchoku").parent().hide();
            }
            //----20220121 sun add e
            //初期化 e

            if (me.data["sdh01_tenpo"]) {
                var data = me.data["sdh01_tenpo"];
                if (data["KYOTN_CD"] == "honbu") {
                    $(".sdh.sdh01.btn_sdh_02").click();
                    return;
                }
                //---20150505 fanzhengzhou add s.#1817
                if (me.data["firsttime_load"] != undefined) {
                    $(".sdh.sdh01.btn_sdh_02").click();
                    return;
                }
                //---20150505 fanzhengzhou add e.#1817
            }

            if (me.data["sdh01_error"]) {
                me.clsComFnc = new gdmz.common.clsComFnc();
                me.clsComFnc.MessageBox(
                    "データが存在しません。",
                    "SDH",
                    "OK",
                    "Warning",
                    MessageBox.MessageBoxIcon.Warning
                );

                me.build_tenpo_option_list(me.data["sdh01_syain"], parameters);

                var rebuild = false;
                if (parameters["is_rebuild_nengetu_option_list"]) {
                    rebuild = parameters["is_rebuild_nengetu_option_list"];
                }
                if (rebuild) {
                    me.build_nengetu_option_list(parameters);
                }
                $(".sdh.sdh01.result_menu_08.mcdropdown_menu").remove();
                $(".sdh.sdh01.result_08").html(me.sdh_sdh01_result_08);
                me.build_hanteinengetu(parameters["nengetu"]);

                return;
            }

            if (is_first_load == true) {
                if (!parameters["nengetu"]) {
                    if (me.data["sdh01_server_date"]) {
                        parameters["nengetu"] = me.data[
                            "sdh01_server_date"
                        ].substr(0, 6);
                    }
                }

                me.build_tenpo_option_list(me.data["sdh01_syain"], parameters);
                me.build_nengetu_option_list(parameters);
            }
            me.build_hantei_list(me.data["sdh01_hantei_list"], parameters);

            me.build_hanteinengetu(me.cur_hantei_item.data("VCLIPEDT"));
            me.build_nyuko_rireki(me.data["sdh01_nyuko_rireki"]);
            me.build_keiyakusya(me.data["sdh01_keiyakusya"]);

            me.build_tantou_henkou_rireki(
                me.data["sdh01_tantou_henkou_rireki"]
            );
            me.build_hantei_naiyou(me.data["sdh01_hantei_naiyou"]);
            me.build_memo(me.data["sdh01_memo"]);
            me.timeline.tempflag = true;
            me.build_timeline(me.data["sdh01_tiemline"]);

            $(".sdh.sdh01.btn_sdh_03").button("enable");
            //--- 20160127 li UPD S
            // $(".sdh.sdh01.btn_sdh_05").button('enable');
            // //---20150831 li INS S.
            // $(".sdh.sdh01.btn_sdh_06").button('enable');
            // //---20150831 li INS E.
            //--- 20190304 li UPD S
            if (con4 == "1" || con4 == "2" || con4 == "3") {
                //--- 20190304 li UPD S
                $(".sdh.sdh01.btn_sdh_05").button("disable");
                $(".sdh.sdh01.btn_sdh_06").button("disable");
            } else {
                $(".sdh.sdh01.btn_sdh_05").button("enable");
                $(".sdh.sdh01.btn_sdh_06").button("enable");
            }
            //--- 20160127 li UPD E

            $(".sdh.sdh01.btn_sdh_04").button("enable");
            $(".sdh.sdh01.btn_reload").button("enable");
            $(".sdh.sdh01.btn_save").button("enable");
            $(".sdh.sdh01.btn_prev_syaryou").button("enable");
            $(".sdh.sdh01.btn_next_syaryou").button("enable");

            if (
                me.data["sdh01_hantei_list"][0]["CMN_NO"] == "" ||
                me.data["sdh01_hantei_list"][0]["CMN_NO"] == null
            ) {
                $(".sdh.sdh01.btn_sdh_03").button("disable");
                $(".sdh.sdh01.btn_sdh_04").button("disable");
                //$(".sdh.sdh01.btn_sdh_05").button('disable');
            }

            $(".sdh.sdh03.dialog").remove();
            $(".sdh.sdh04.dialog").remove();
            $(".sdh.sdh07.dialog").remove();

            if (me["SDH03"]) {
                me["SDH03"] = null;
            }
            if (me["SDH04"]) {
                me["SDH04"] = null;
            }
            if (me["SDH07"]) {
                me["SDH07"] = null;
            }

            me.resize_all();
        } catch (e) {
            console.log("error:get_data receive");
            console.log(result);
            console.log(e);
        }
    };

    me.get_hantei_item_data = function (data) {
        var selDate = $(".sdh.sdh01.sel_nengetu").val();
        try {
            me.PgUpPgDnFlag = false;
            data["tenpo_cd"] = me.data["sdh01_tenpo"]["KYOTN_CD"];
            data["nengetu"] = selDate;
            //--- 20160127 li INS S
            data["condition4"] = me.condition4;
            //--- 20160127 li INS S
            var o_ajax = new gdmz.common.ajax();
            o_ajax.receive = receive;
            if (
                data["CMN_NO"] == "" ||
                data["CMN_NO"] == null ||
                data["CMN_NO"] == "null"
            ) {
                data["CMN_NO"] = "null";
                //-----20141128 NO.26 fuxiaolin  ins  s
                $(".sdh.sdh01.btn_sdh_03").button("disable");
                //$(".sdh.sdh01.btn_sdh_05").button('disable');
                $(".sdh.sdh01.btn_sdh_04").button("disable");
            } else {
                $(".sdh.sdh01.btn_sdh_03").button("enable");
                $(".sdh.sdh01.btn_sdh_04").button("enable");
                //$(".sdh.sdh01.btn_sdh_05").button('enable');
            }

            var url = me.sys_id + "/" + me.id + "/" + "SDH01";
            o_ajax.send(url, data, 0);
        } catch (e) {
            console.log("get_hantei_item_data");
            console.log(e);
        }

        function receive(result) {
            try {
                me.PgUpPgDnFlag = true;

                var tmp_data = JSON.parse(result);

                //初期化 s
                $(".sdh.sdh01.sdh01_02.value").text("");
                $(".sdh.sdh01.sdh01_03.value").text("");
                $(".sdh.sdh01.sdh01_04.nyuko_rireki").empty();
                me.timeline.tempflag = false;
                $(".sdh.sdh01.div_timeline").empty();
                $(".sdh.sdh01.sdh01_07.value").text("");

                for (i = 1; i < 8; i++) {
                    $(".sdh.sdh01.btn_rireki_0" + i).empty();
                    $(".sdh.sdh01.result_select_0" + i).val("");
                    $(".sdh.sdh01.result_text_0" + i).val("");
                    $(".sdh.sdh01.result_text_0" + i).data("code", "");
                }
                $("#sdh_mcDropdown8").parent().show();
                $(".sdh.sdh01.result_button_08").hide();
                $(".sdh.sdh01.result_select_08").val("");
                $(".sdh.sdh01.result_text_08").val("");
                $(".sdh.sdh01.result_text_08").data("code", "");
                //20180130 lqs INS S
                $(".sdh.sdh01.btn_rireki_08").empty();
                $(".sdh.sdh01.sdh01_07.MEMO").val("");
                //20180130 lqs INS E

                $(".sdh.sdh01.btn_tanto_rireki.tooltip.yellow-tooltip").hide();
                //初期化 e

                for (key in tmp_data) {
                    me.data[key] = tmp_data[key];
                }
                me.build_hanteinengetu(me.cur_hantei_item.data("VCLIPEDT"));
                me.build_nyuko_rireki(me.data["sdh01_nyuko_rireki"]);
                me.build_keiyakusya(me.data["sdh01_keiyakusya"]);
                me.build_tantou_henkou_rireki(
                    me.data["sdh01_tantou_henkou_rireki"]
                );
                me.build_hantei_naiyou(me.data["sdh01_hantei_naiyou"]);
                me.build_memo(me.data["sdh01_memo"]);
                me.build_timeline(me.data["sdh01_tiemline"]);

                var idx = me.cur_hantei_item.data("idx");
                idx = parseInt(idx) + 1;

                $(".sdh.sdh01.current_all.lbl_count.page_no").text(idx);
                $(".sdh.sdh01.current_all.lbl_count.total").text(
                    me.data["sdh01_hantei_list"].length
                );

                $(".sdh.sdh03.dialog").remove();
                $(".sdh.sdh04.dialog").remove();

                if (me["SDH03"]) {
                    me["SDH03"] = null;
                }
                if (me["SDH04"]) {
                    me["SDH04"] = null;
                }
            } catch (e) {
                console.log("get_hantei_item_data receive");
                console.log(e);
            }
        }
    };

    me.build_timeline = function (data) {
        try {
            if (!data) {
                return;
            }
            if (data.length == 0) {
                return;
            }

            var classOfDiv = ".sdh.sdh01.div_timeline";
            var selDate = $(".sdh.sdh01.sel_nengetu").val();

            me.timeline.draw_timeline(data, classOfDiv, selDate);
        } catch (e) {
            console.log("build_timeline");
            console.log(e);
        }
    };

    me.build_hantei_naiyou = function (data) {
        try {
            for (i = 1; i < 8; i++) {
                $(".sdh.sdh01.btn_rireki_0" + i).empty();
                $(".sdh.sdh01.result_select_0" + i).val("");
                $(".sdh.sdh01.result_text_0" + i).val("");
                $(".sdh.sdh01.result_text_0" + i).data("code", "");
            }
            $("#sdh_mcDropdown8").parent().show();
            $(".sdh.sdh01.result_button_08").hide();
            $(".sdh.sdh01.result_select_08").val("");
            $(".sdh.sdh01.result_text_08").val("");
            $(".sdh.sdh01.result_text_08").data("code", "");
            //20180130 lqs INS S
            $(".sdh.sdh01.btn_rireki_08").empty();
            $(".sdh.sdh01.sdh01_07.MEMO").val("");
            //20180130 lqs INS E

            var first_data_1 = Array();

            //20150422 fugyorin edit #1806 start

            for (var idx = 1; idx < 8; idx++) {
                first_data_1["HANTEI" + idx] = "";
                first_data_1["HANTEI" + idx + "_CD"] = "";
                first_data_1["NAME" + idx] = "";
            }

            first_data_1["KEKKA"] = "";
            first_data_1["KEKKA_CD"] = "";
            first_data_1["NAME"] = "";

            //20150422 fugyorin edit end

            me.data["first_data"] = first_data_1;
            if (me.data["saved_data"]) {
                delete me.data["saved_data"];
            }

            var html_01_str = "";

            //--- 20160127 li UPD S
            // for ( j = 1; j < 8; j++)
            // {
            // ymdhm = "";
            //
            // html_01_str = "";
            // html_01_str += "<table style='width:100%'>";
            //
            // html_01_str += "<tr>";
            // html_01_str += "<td style='width:130px;color:gray' align='center'>";
            // html_01_str += "日 時1";
            // html_01_str += "</td>";
            // html_01_str += "<td align='center' style='color:gray'>";
            // html_01_str += "内 容";
            // html_01_str += "</td>";
            // html_01_str += "<td style='width:60px;color:gray' align='center'>";
            // html_01_str += "変更者";
            // html_01_str += "</td>";
            // html_01_str += "</tr>";
            //
            // hantei_bef = "";
            // hantei_bef_name = "";
            // for ( k = 0; k < data.length; k++)
            // {
            // hantei_now = data[k]["HANTEI" + j];
            // if (hantei_now == null)
            // {
            // hantei_now = "";
            // }
            // hantei_now_name = data[k]["NAME" + j];
            // if (hantei_now_name == null)
            // {
            // hantei_now_name = "";
            // }
            // //if ((hantei_now != "" && hantei_now != null && hantei_now != hantei_bef) || (hantei_now_name != "" && hantei_now_name != null && hantei_now_name != hantei_bef_name)) {
            // if ((hantei_now != hantei_bef || hantei_now_name != hantei_bef_name) && ((hantei_now != "" && hantei_now != null) || (hantei_now_name != "" && hantei_now_name != null)))
            // {
            // html_01_str += "<tr>";
            // html_01_str += "<td>";
            // //-----20141202  upd  jinmingai  s
            // // ymdhm = data[k]["UPDYMDHM"];
            // ymdhm = $.trim(data[k]["UPDYMDHM"]);
            // //-----20141202  upd  jinmingai  e
            // if (ymdhm != "" && ymdhm != null)
            // {
            // Y = ymdhm.slice(0, 4);
            // m = ymdhm.slice(4, 6);
            // d = ymdhm.slice(6, 8);
            // h = ymdhm.slice(8, 10);
            // i = ymdhm.slice(10, 12);
            // html_01_str += Y + "/" + m + "/" + d + " " + h + ":" + i;
            // }
            // //-----20141218 fuxiaolin NO.66 add -s
            // else
            // {
            // html_01_str += "(不明)";
            // };
            // //-----20141218 fuxiaolin NO.66 add -e
            // html_01_str += "</td>";
            // html_01_str += "<td style='word-break:normal'>";
            // if (data[k]["HANTEI" + j] == null)
            // {
            // data[k]["HANTEI" + j] = "";
            // };
            // //---20150421 fanzhengzhou upd s. redmine #1799
            // if (data[k]["NAME" + j] != "" && data[k]["NAME" + j] != null)
            // {
            // html_01_str += data[k]["NAME" + j];
            // }
            // //if (data[k]["NAME" + j] != "" && data[k]["NAME" + j] != null && data[k]["HANTEI" + j] != "" && data[k]["HANTEI" + j] != null) {
            // html_01_str += " <br/> ";
            // //}
            // if (data[k]["HANTEI" + j] != "" && data[k]["HANTEI" + j] != null)
            // {
            // html_01_str += data[k]["HANTEI" + j];
            // }
            // //---20150421 fanzhengzhou upd e. redmine #1799
            // html_01_str += "</td>";
            // html_01_str += "<td align='center'>";
            // //-----20141218 fuxiaolin NO.66 add -s
            // var ttsname = $.trim(data[k]["TTS_SEIMEI"]);
            //
            // if (ttsname == null || ttsname == "")
            // {
            // html_01_str += "(不明)";
            //
            // }
            // else
            // {
            // html_01_str += data[k]["TTS_SEIMEI"];
            // }
            // //-----20141218 fuxiaolin NO.66 add -e
            // html_01_str += "</td>";
            // html_01_str += "</tr>";
            //
            // hantei_bef = hantei_now;
            // hantei_bef_name = hantei_now_name;
            // }
            // }
            // html_01_str += "</table>";
            //
            // if (hantei_bef != "" || hantei_bef_name != "")
            // {
            // //---20150421 fanzhengzhou upd s. redmine #1799
            // //show_list_bf = "※<span style='width:300px;'>";
            // show_list_bf = "※<span style='width:360px;' class='hanteinai'>";
            // //---20150421 fanzhengzhou upd e. redmine #1799
            // show_list_af = "</span>";
            // $(".sdh.sdh01.btn_rireki_0" + j).show();
            // $(".sdh.sdh01.btn_rireki_0" + j).html(show_list_bf + html_01_str + show_list_af);
            //
            // }
            // else
            // {
            // $(".sdh.sdh01.btn_rireki_0" + j).empty();
            // $(".sdh.sdh01.btn_rireki_0" + j).hide();
            // }
            //
            // $(".sdh.sdh01.btn_rireki_0" + j).data("idx", j);
            // }

            //----20220121 sun upd s
            //if (me.condition4 == "0" || me.condition4 == "1" || me.condition4 == "2")
            if (
                me.condition4 == "0" ||
                me.condition4 == "1" ||
                me.condition4 == "2" ||
                me.condition4 == "4"
            ) {
                //----20220121 sun upd e
                for (j = 1; j < 8; j++) {
                    ymdhm = "";

                    html_01_str = "";
                    html_01_str += "<table style='width:100%'>";

                    html_01_str += "<tr>";
                    html_01_str +=
                        "<td style='width:130px;color:gray' align='center'>";
                    html_01_str += "日 時";
                    html_01_str += "</td>";
                    html_01_str += "<td align='center' style='color:gray'>";
                    html_01_str += "内 容";
                    html_01_str += "</td>";
                    html_01_str +=
                        "<td style='width:60px;color:gray' align='center'>";
                    html_01_str += "変更者";
                    html_01_str += "</td>";
                    html_01_str += "</tr>";

                    hantei_bef = "";
                    hantei_bef_name = "";
                    for (k = 0; k < data.length; k++) {
                        hantei_now = data[k]["HANTEI" + j];
                        if (hantei_now == null) {
                            hantei_now = "";
                        }
                        hantei_now_name = data[k]["NAME" + j];
                        if (hantei_now_name == null) {
                            hantei_now_name = "";
                        }
                        if (
                            (hantei_now != hantei_bef ||
                                hantei_now_name != hantei_bef_name) &&
                            ((hantei_now != "" && hantei_now != null) ||
                                (hantei_now_name != "" &&
                                    hantei_now_name != null))
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
                            html_01_str += "<td style='word-break:normal'>";
                            if (data[k]["HANTEI" + j] == null) {
                                data[k]["HANTEI" + j] = "";
                            }
                            if (
                                data[k]["NAME" + j] != "" &&
                                data[k]["NAME" + j] != null
                            ) {
                                html_01_str += data[k]["NAME" + j];
                            }
                            html_01_str += " <br/> ";
                            if (
                                data[k]["HANTEI" + j] != "" &&
                                data[k]["HANTEI" + j] != null
                            ) {
                                //20201117 CI UPD S
                                //html_01_str += data[k]["HANTEI" + j];
                                var leg = data[k]["HANTEI" + j].length;
                                html_01_str += data[k]["HANTEI" + j].substr(
                                    0,
                                    20
                                );
                                if (leg > 20) {
                                    html_01_str += " <br/> ";
                                    html_01_str += data[k]["HANTEI" + j].substr(
                                        20,
                                        20
                                    );
                                }
                                if (leg > 40) {
                                    html_01_str += " <br/> ";
                                    html_01_str += data[k]["HANTEI" + j].substr(
                                        40,
                                        20
                                    );
                                }
                                if (leg > 60) {
                                    html_01_str += " <br/> ";
                                    html_01_str += data[k]["HANTEI" + j].substr(
                                        60,
                                        20
                                    );
                                }
                                //20201117 CI UPD E
                            }
                            html_01_str += "</td>";
                            html_01_str += "<td align='center'>";
                            var ttsname = $.trim(data[k]["TTS_SEIMEI"]);

                            if (ttsname == null || ttsname == "") {
                                //20160402 Upd S
                                //html_01_str += "(不明)";
                                html_01_str += "(システム)";
                                //20160402 Upd E
                            } else {
                                html_01_str += data[k]["TTS_SEIMEI"];
                            }
                            html_01_str += "</td>";
                            html_01_str += "</tr>";

                            hantei_bef = hantei_now;
                            hantei_bef_name = hantei_now_name;
                        }
                    }
                    html_01_str += "</table>";

                    if (hantei_bef != "" || hantei_bef_name != "") {
                        show_list_bf =
                            "※<span style='width:360px;' class='hanteinai'>";
                        show_list_af = "</span>";
                        //20171124 lqs UPD S
                        //$(".sdh.sdh01.btn_rireki_0" + j).show();
                        $(".sdh.sdh01.btn_rireki_0" + j).css(
                            "visibility",
                            "visible"
                        );
                        //20171124 lqs UPD E
                        $(".sdh.sdh01.btn_rireki_0" + j).html(
                            show_list_bf + html_01_str + show_list_af
                        );
                    } else {
                        //20171124 lqs UPD S
                        // $(".sdh.sdh01.btn_rireki_0" + j).hide();
                        // $(".sdh.sdh01.btn_rireki_0" + j).empty();
                        show_list_bf =
                            "※<span style='width:360px;' class='hanteinai'>";
                        show_list_af = "</span>";
                        $(".sdh.sdh01.btn_rireki_0" + j).html(
                            show_list_bf + show_list_af
                        );
                        $(".sdh.sdh01.btn_rireki_0" + j).css(
                            "visibility",
                            "hidden"
                        );
                        //20171124 lqs UPD E
                    }

                    $(".sdh.sdh01.btn_rireki_0" + j).data("idx", j);
                }
            }
            //--- 20160127 li UPD E

            //20150520 fugyorin #1899 add start

            var html_01_str = "";
            //--- 20160127 li UPD S
            // html_01_str += "<table style='width:100%' border='0'>";
            //
            // html_01_str += "<tr>";
            // html_01_str += "<td style='width:130px;color:gray' align='center'>";
            // html_01_str += "日 時2";
            // html_01_str += "</td>";
            // html_01_str += "<td align='center' style='color:gray'>";
            // html_01_str += "内 容";
            // html_01_str += "</td>";
            // html_01_str += "<td style='width:60px;color:gray' align='center'>";
            // html_01_str += "変更者";
            // html_01_str += "</td>";
            // html_01_str += "</tr>";
            //
            // hantei_bef = "";
            // hantei_bef_name = "";
            // for ( k = 0; k < data.length; k++)
            // {
            // console.log(data[k]["NAME"]);
            // hantei_now = data[k]["KEKKA"];
            // if (hantei_now == null)
            // {
            // hantei_now = "";
            // }
            // hantei_now_name = data[k]["NAME"];
            // if (hantei_now_name == null)
            // {
            // hantei_now_name = "";
            // }
            //
            // if ((hantei_now != hantei_bef || hantei_now_name != hantei_bef_name) && ((hantei_now != "" && hantei_now != null) || (hantei_now_name != "" && hantei_now_name != null)))
            // {
            // html_01_str += "<tr>";
            // html_01_str += "<td>";
            // ymdhm = $.trim(data[k]["UPDYMDHM"]);
            // if (ymdhm != "" && ymdhm != null)
            // {
            // Y = ymdhm.slice(0, 4);
            // m = ymdhm.slice(4, 6);
            // d = ymdhm.slice(6, 8);
            // h = ymdhm.slice(8, 10);
            // i = ymdhm.slice(10, 12);
            // html_01_str += Y + "/" + m + "/" + d + " " + h + ":" + i;
            // }
            // else
            // {
            // html_01_str += "(不明)";
            // };
            // html_01_str += "</td>";
            // html_01_str += "<td>";
            // if (data[k]["KEKKA"] == null)
            // {
            // data[k]["KEKKA"] = "";
            // };
            // if (data[k]["NAME"] != "" && data[k]["NAME"] != null)
            // {
            // html_01_str += data[k]["NAME"];
            // }
            // html_01_str += " <br/> ";
            // if (data[k]["KEKKA"] != "" && data[k]["KEKKA"] != null)
            // {
            // var leg = data[k]["KEKKA"].length;
            // //alert(leg);
            // html_01_str += data[k]["KEKKA"].substr(0, 20);
            // if (leg > 20)
            // {
            // html_01_str += " <br/> ";
            // html_01_str += data[k]["KEKKA"].substr(20, 20);
            // }
            // if (leg > 40)
            // {
            // html_01_str += " <br/> ";
            // html_01_str += data[k]["KEKKA"].substr(40, 20);
            // }
            // if (leg > 60)
            // {
            // html_01_str += " <br/> ";
            // html_01_str += data[k]["KEKKA"].substr(60, 20);
            // }
            // // html_01_str += " <br/> ";
            // }
            // html_01_str += "</td>";
            // html_01_str += "<td align='center'>";
            // var ttsname = $.trim(data[k]["TTS_SEIMEI"]);
            //
            // if (ttsname == null || ttsname == "")
            // {
            // html_01_str += "(不明)";
            //
            // }
            // else
            // {
            // html_01_str += data[k]["TTS_SEIMEI"];
            // }
            // html_01_str += "</td>";
            // html_01_str += "</tr>";
            //
            // hantei_bef = hantei_now;
            // hantei_bef_name = hantei_now_name;
            // }
            //
            // }
            // html_01_str += "</table>";
            //
            // if (hantei_bef != "" || hantei_bef_name != "")
            // {
            //
            // show_list_bf = "※<span style='width:360px;' class='hanteikekka'>";
            //
            // show_list_af = "</span>";
            // $(".sdh.sdh01.btn_rireki_08").show();
            // $(".sdh.sdh01.btn_rireki_08").html(show_list_bf + html_01_str + show_list_af);
            //
            // }
            // else
            // {
            // $(".sdh.sdh01.btn_rireki_08").empty();
            // $(".sdh.sdh01.btn_rireki_08").hide();
            // }
            // $(".sdh.sdh01.btn_rireki_08").data("idx", 8);
            //20190227 YIN UPD S
            // if (me.condition4 == "0" || me.condition4 == "1" || me.condition4 == "2")
            //----20220121 sun upd s
            //if (me.condition4 == "0" || me.condition4 == "1" || me.condition4 == "2" || me.condition4 == "3")
            if (
                me.condition4 == "0" ||
                me.condition4 == "1" ||
                me.condition4 == "2" ||
                me.condition4 == "3" ||
                me.condition4 == "4"
            ) {
                //----20220121 sun upd e
                //20190227 YIN UPD E
                html_01_str += "<table style='width:100%' border='0'>";
                html_01_str += "<tr>";
                html_01_str +=
                    "<td style='width:130px;color:gray' align='center'>";
                html_01_str += "日 時";
                html_01_str += "</td>";
                html_01_str += "<td align='center' style='color:gray'>";
                html_01_str += "内 容";
                html_01_str += "</td>";
                html_01_str +=
                    "<td style='width:60px;color:gray' align='center'>";
                html_01_str += "変更者";
                html_01_str += "</td>";
                html_01_str += "</tr>";

                hantei_bef = "";
                hantei_bef_name = "";
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
                        if (data[k]["KEKKA"] == null) {
                            data[k]["KEKKA"] = "";
                        }
                        if (data[k]["NAME"] != "" && data[k]["NAME"] != null) {
                            html_01_str += data[k]["NAME"];
                        }
                        html_01_str += " <br/> ";
                        if (
                            data[k]["KEKKA"] != "" &&
                            data[k]["KEKKA"] != null
                        ) {
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
                        html_01_str += "<td align='center'>";
                        var ttsname = $.trim(data[k]["TTS_SEIMEI"]);

                        if (ttsname == null || ttsname == "") {
                            //20160402 Upd S
                            //html_01_str += "(不明)";
                            html_01_str += "(システム)";
                            //20160402 Upd E
                        } else {
                            html_01_str += data[k]["TTS_SEIMEI"];
                        }
                        html_01_str += "</td>";
                        html_01_str += "</tr>";

                        hantei_bef = hantei_now;
                        hantei_bef_name = hantei_now_name;
                    }
                }
                html_01_str += "</table>";

                if (hantei_bef != "" || hantei_bef_name != "") {
                    show_list_bf =
                        "※<span style='width:360px;' class='hanteikekka'>";

                    show_list_af = "</span>";
                    //20171124 lqs UPD S
                    // $(".sdh.sdh01.btn_rireki_08").show();
                    $(".sdh.sdh01.btn_rireki_08").css("visibility", "visible");
                    //20171124 lqs UPD E
                    $(".sdh.sdh01.btn_rireki_08").html(
                        show_list_bf + html_01_str + show_list_af
                    );
                } else {
                    //20171124 lqs UPD S
                    // $(".sdh.sdh01.btn_rireki_08").empty();
                    // $(".sdh.sdh01.btn_rireki_08").hide();
                    show_list_bf =
                        "※<span style='width:360px;' class='hanteikekka'>";
                    show_list_af = "</span>";
                    $(".sdh.sdh01.btn_rireki_08").html(
                        show_list_bf + show_list_af
                    );
                    $(".sdh.sdh01.btn_rireki_08").css("visibility", "hidden");
                    //20171124 lqs UPD E
                }
                $(".sdh.sdh01.btn_rireki_08").data("idx", 8);
            }
            //--- 20160127 li UPD E
            //add end

            $(".sdh.sdh01.result_text_08").val("");
            $(".sdh.sdh01.result_text_08").data("code", "");
            //--- 20160301 li DEL S
            // var first_data = Array();
            // if (data.length > 0)
            // {
            // var last_idx = data.length - 1;
            //
            // for ( i = 1; i < 8; i++)
            // {
            // var hantei = data[last_idx]["HANTEI" + i];
            //
            // //20150422 fugyorin edit #1806 start
            // if (hantei == "" || hantei == null)
            // {
            // hantei = null;
            // }
            //
            // first_data["HANTEI" + i] = hantei;
            //
            // $(".sdh.sdh01.result_text_0" + i).val(hantei);
            //
            // var code = data[last_idx]["HANTEI" + i + "_CD"];
            // if (code == "" || code == null)
            // {
            // code = null;
            // }
            //
            // var name = data[last_idx]["NAME" + i];
            // if (name == "" || name == null)
            // {
            // name = null;
            // }
            //
            // first_data["HANTEI" + i + "_CD"] = code;
            // first_data["NAME" + i] = name;
            //
            // $(".sdh.sdh01.result_select_0" + i).val(name);
            //
            // $(".sdh.sdh01.result_text_0" + i).data("code", code);
            //
            // }
            //
            // var kekka = data[last_idx]["KEKKA"];
            // var name = data[last_idx]["NAME"];
            // var code = data[last_idx]["KEKKA_CD"];
            // if (code == "")
            // {
            // code = null;
            // }
            //
            // if (kekka == "")
            // {
            // kekka = null;
            // }
            //
            // if (name == "")
            // {
            // name = null;
            // }
            //
            // first_data["KEKKA"] = kekka;
            // first_data["NAME"] = name;
            // first_data["KEKKA_CD"] = code;
            // $(".sdh.sdh01.result_select_08").val(name);
            // $(".sdh.sdh01.result_text_08").val(kekka);
            // $(".sdh.sdh01.result_text_08").data("code", code);
            //
            // if (kekka != null)
            // {
            // kekka = kekka.trim();
            // //--- 20160127 li UPD S
            // //if (kekka.indexOf("代替　注文書=") > -1)
            // if (kekka.indexOf("代替　注文書=") > -1 && me.condition4 != "1" && me.condition4 != "2")
            // //--- 20160127 li UPD E
            // {
            // cmn_no = kekka.replace("代替　注文書=", "");
            // cmn_no = cmn_no.trim();
            // $(".sdh.sdh01.result_button_08").data("cmn_no", cmn_no);
            // $("#sdh_mcDropdown8").parent().hide();
            // $(".sdh.sdh01.result_button_08").show();
            // }
            // else
            // {
            // $("#sdh_mcDropdown8").parent().show();
            // $(".sdh.sdh01.result_button_08").hide();
            // $(".sdh.sdh01.result_text_08").val(kekka);
            // }
            // }
            // else
            // {
            // kekka = "";
            // $("#sdh_mcDropdown8").parent().show();
            // $(".sdh.sdh01.result_button_08").hide();
            // $(".sdh.sdh01.result_text_08").val(kekka);
            // }
            // };
            //--- 20160301 li DEL E

            //--- 20160127 li INS S
            //----20220121 sun upd s
            //if (me.condition4 == "0")
            if (me.condition4 == "0" || me.condition4 == "4") {
                //----20220121 sun upd e
                $(".sdh.sdh01.titleName_08").text("最終結果：");
            } else if (me.condition4 == "1" || me.condition4 == "2") {
                $(".sdh.sdh01.titleName_08").text("新車６ヶ月結果");
            }
            //20190227 YIN INS S
            else if (me.condition4 == "3") {
                $(".sdh.sdh01.titleName_08").text("中古１ヶ月結果");
            }
            //20190227 YIN INS E
            $(".sdh.sdh01.result_menu_08").html("");
            var tttArr = {};
            var arrTop = new Array();
            var arr2 = new Array();
            if (me.data["sdh01_menuLast"].length != 0) {
                for (key in me.data["sdh01_menuLast"]) {
                    if (me.data["sdh01_menuLast"][key]["MENU_TYPE"] == "0") {
                        tttArr = {};
                        tttArr["TEIKEI_CD"] =
                            me.data["sdh01_menuLast"][key]["TEIKEI_CD"];
                        tttArr["ITEMNAME"] =
                            me.data["sdh01_menuLast"][key]["ITEMNAME1"];
                        arrTop.push(tttArr);
                    }
                    if (me.data["sdh01_menuLast"][key]["MENU_TYPE"] == "1") {
                        tttArr = {};
                        tttArr["TEIKEI_CD"] =
                            me.data["sdh01_menuLast"][key]["TEIKEI_CD"];
                        tttArr["ITEMNAME"] =
                            me.data["sdh01_menuLast"][key]["ITEMNAME2"];
                        arr2.push(tttArr);
                    }
                }
                html_01_str = "";
                for (var m = 0; m < arrTop.length; m++) {
                    mark = false;
                    for (var m1 = 0; m1 < arr2.length; m1++) {
                        if (
                            arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                            arr2[m1]["TEIKEI_CD"].substr(0, 2)
                        ) {
                            mark = true;
                            break;
                        }
                    }
                    if (mark) {
                        html_01_str +=
                            '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                        html_01_str += arrTop[m]["ITEMNAME"];

                        html_01_str += "<ul>";
                        for (var m1 = 0; m1 < arr2.length; m1++) {
                            if (
                                arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                                arr2[m1]["TEIKEI_CD"].substr(0, 2)
                            ) {
                                html_01_str +=
                                    '<li rel="' +
                                    arr2[m1]["TEIKEI_CD"] +
                                    '"style="width:100px;">';
                                html_01_str += arr2[m1]["ITEMNAME"];
                                html_01_str += "</li>";
                            }
                        }
                        html_01_str += "</ul>";
                        html_01_str += "</li>";
                    } else {
                        html_01_str +=
                            '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                        html_01_str += arrTop[m]["ITEMNAME"];
                        html_01_str += "</li>";
                    }
                }
            }

            //--- 20160304 zhenghuiyun INS S
            $(".sdh.sdh01.result_menu_08.mcdropdown_menu").remove();
            $(".sdh.sdh01.result_08").html(me.sdh_sdh01_result_08);
            //--- 20160304 zhenghuiyun INS E

            $(".sdh.sdh01.result_menu_08").html(html_01_str);

            $(".sdh.sdh01.result_select_08").mcDropdown(
                ".sdh.sdh01.result_menu_08",
                {
                    select: function (value, name) {
                        if (value.length != 4) {
                            value = value + "00";
                        }

                        $(".sdh.sdh01.result_text_08").val("");
                        $(".sdh.sdh01.result_text_08").data("code", value);
                        //HANTEILST_SINSYAの更新年月日がシステム日付より過去　AND コンボボックスの値が「入庫済」の場合は入力不可にする
                        if (
                            name == "入庫済" &&
                            (me.condition4 == "1" ||
                                me.condition4 == "2" ||
                                me.condition4 == "3") &&
                            me.data["sdh01_hantei_naiyou"].length > 0
                        ) {
                            ymdhm = $.trim(
                                me.data["sdh01_hantei_naiyou"][
                                    me.data["sdh01_hantei_naiyou"].length - 1
                                ]["UPDYMDHM"]
                            );
                            ymdhm = ymdhm.substr(0, 8);
                            ydate = $.trim(
                                me.data["sdh01_hantei_naiyou"][
                                    me.data["sdh01_hantei_naiyou"].length - 1
                                ]["SYSYMDHM"]
                            );
                            ydate = ydate.substr(0, 8);
                            if (ydate >= ymdhm) {
                                $(".sdh.sdh01.result_text_08").attr(
                                    "disabled",
                                    "none"
                                );
                            } else {
                                $(".sdh.sdh01.result_text_08").attr(
                                    "disabled",
                                    false
                                );
                            }
                        } else {
                            $(".sdh.sdh01.result_text_08").attr(
                                "disabled",
                                false
                            );
                        }
                    },
                },
                "sdh_mcDropdown8"
            );
            $(".sdh.sdh01.result_text_08").width("95%");

            var first_data = Array();
            if (data.length > 0) {
                var last_idx = data.length - 1;

                for (i = 1; i < 8; i++) {
                    var hantei = data[last_idx]["HANTEI" + i];

                    //20150422 fugyorin edit #1806 start
                    if (hantei == "" || hantei == null) {
                        hantei = null;
                    }

                    first_data["HANTEI" + i] = hantei;

                    $(".sdh.sdh01.result_text_0" + i).val(hantei);

                    var code = data[last_idx]["HANTEI" + i + "_CD"];
                    if (code == "" || code == null) {
                        code = null;
                    }

                    var name = data[last_idx]["NAME" + i];
                    if (name == "" || name == null) {
                        name = null;
                    }

                    first_data["HANTEI" + i + "_CD"] = code;
                    first_data["NAME" + i] = name;

                    $(".sdh.sdh01.result_select_0" + i).val(name);

                    $(".sdh.sdh01.result_text_0" + i).data("code", code);
                }

                var kekka = data[last_idx]["KEKKA"];
                var name = data[last_idx]["NAME"];
                var code = data[last_idx]["KEKKA_CD"];
                if (code == "") {
                    code = null;
                }

                if (kekka == "") {
                    kekka = null;
                }

                if (name == "") {
                    name = null;
                }

                first_data["KEKKA"] = kekka;
                first_data["NAME"] = name;
                first_data["KEKKA_CD"] = code;
                $(".sdh.sdh01.result_select_08").val(name);
                $(".sdh.sdh01.result_text_08").val(kekka);
                $(".sdh.sdh01.result_text_08").data("code", code);

                if (kekka != null) {
                    kekka = kekka.trim();
                    if (
                        kekka.indexOf("代替　注文書=") > -1 &&
                        me.condition4 != "1" &&
                        me.condition4 != "2" &&
                        me.condition4 != "3"
                    ) {
                        cmn_no = kekka.replace("代替　注文書=", "");
                        cmn_no = cmn_no.trim();
                        $(".sdh.sdh01.result_button_08").data("cmn_no", cmn_no);
                        $("#sdh_mcDropdown8").parent().hide();
                        $(".sdh.sdh01.result_button_08").show();
                        //20160907 YIN INS S
                        $(".sdh.sdh01.result_button_08").click(function () {
                            try {
                                $(".sdh.sdh03.dialog").remove();

                                if (me["SDH03"]) {
                                    me["SDH03"] = null;
                                }

                                var o_ajax = new gdmz.common.ajax();
                                o_ajax.receive = receive;
                                var url =
                                    me.sys_id +
                                    "/" +
                                    me.id +
                                    "/" +
                                    "check_cmnno";
                                var data = $.trim(
                                    $(".sdh.sdh01.result_button_08").data(
                                        "cmn_no"
                                    )
                                );
                                data = data.replace("　", "");
                                o_ajax.send(url, data, 0);
                                function receive(result) {
                                    result = JSON.parse(result);
                                    if (result["result"] == true) {
                                        if (result["row"] != 0) {
                                            var temp = {
                                                //----20150318 fanzhengzhou upd s.
                                                //'CMN_NO' : $(".sdh.sdh01.result_button_08").data("cmn_no"),
                                                CMN_NO: $.trim(
                                                    $(
                                                        ".sdh.sdh01.result_button_08"
                                                    ).data("cmn_no")
                                                ),
                                                //----20150318 fanzhengzhou upd e.
                                            };
                                            me.open_dialog(
                                                "SDH03",
                                                null,
                                                null,
                                                temp
                                            );
                                        } else {
                                            me.clsComFnc =
                                                new gdmz.common.clsComFnc();
                                            me.clsComFnc.MessageBox(
                                                "選択した注文書が存在しません。",
                                                "SDH",
                                                "OK",
                                                "Warning",
                                                MessageBox.MessageBoxIcon
                                                    .Warning
                                            );
                                        }
                                    }
                                }

                                // fan update e
                            } catch (e) {
                                console.log(
                                    'error:$(".sdh.sdh01.result_button_08").click'
                                );
                                console.log(e);
                            }
                        });
                        //20160907 YIN INS E
                    } else {
                        $("#sdh_mcDropdown8").parent().show();
                        $(".sdh.sdh01.result_button_08").hide();
                        $(".sdh.sdh01.result_text_08").val(kekka);
                    }
                } else {
                    kekka = "";
                    $("#sdh_mcDropdown8").parent().show();
                    $(".sdh.sdh01.result_button_08").hide();
                    $(".sdh.sdh01.result_text_08").val(kekka);
                }
            }
            //--- 20160127 li INS E

            // var first_data = Array();
            // for (var idx = 1; idx < 8; idx++) {
            // first_data["HANTEI" + idx] = $(".sdh.sdh01.result_text_0" + idx).val();
            // };
            // first_data["KEKKA"] = $(".sdh.sdh01.result_text_08").val();
            //
            // //20150420 fuxiaolin add start
            // for (var idx = 1; idx < 8; idx++) {
            // first_data["NAME" + idx] = $(".sdh.sdh01.result_sel_0" + idx).val();
            // };
            // first_data["NAME"] = $(".sdh.sdh01.result_sel_08").val();

            //20150422 fugyorin edit #1806  end

            me.data["first_data"] = first_data;

            if (me.data["saved_data"]) {
                delete me.data["saved_data"];
            }
            //console.log(me.data["first_data"]);

            //20160127 YIN INS S
            if (me.condition4 == "1") {
                //20160402 Del S
                $(".mcdropdown.sdh_mcDropdown7" + " a").css("display", "none");
                //20160402 Del E
                $(".mcdropdown.sdh_mcDropdown8" + " a").css("display", "none");
                $(".sdh.sdh01.result_text_08").attr("disabled", "disable");
                var selname = $(".sdh.sdh01.result_select_07").val().trim();
                if (
                    selname == "入庫済" &&
                    (me.condition4 == "1" || me.condition4 == "2") &&
                    me.data["sdh01_hantei_naiyou"].length > 0
                ) {
                    ymdhm = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["UPDYMDHM"]
                    );
                    ymdhm = ymdhm.substr(0, 8);
                    ydate = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["SYSYMDHM"]
                    );
                    ydate = ydate.substr(0, 8);
                    if (ydate >= ymdhm) {
                        $(".sdh.sdh01.result_text_07").attr("disabled", "none");
                    } else {
                        $(".sdh.sdh01.result_text_07").attr("disabled", false);
                    }
                } else {
                    $(".sdh.sdh01.result_text_07").attr("disabled", false);
                }
            }
            if (me.condition4 == "2") {
                //20160402 Upd S
                $(".mcdropdown.sdh_mcDropdown7" + " a").css("display", "none");
                //				$(".mcdropdown.sdh_mcDropdown8" + " a").css("display", "inline-block");
                $(".mcdropdown.sdh_mcDropdown8" + " a").css("display", "none");
                //20160402 Upd S
                $(".sdh.sdh01.result_text_08").attr("disabled", false);
                var selname = $(".sdh.sdh01.result_select_07").val().trim();
                if (
                    selname == "入庫済" &&
                    (me.condition4 == "1" || me.condition4 == "2") &&
                    me.data["sdh01_hantei_naiyou"].length > 0
                ) {
                    ymdhm = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["UPDYMDHM"]
                    );
                    ymdhm = ymdhm.substr(0, 8);
                    ydate = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["SYSYMDHM"]
                    );
                    ydate = ydate.substr(0, 8);
                    if (ydate >= ymdhm) {
                        $(".sdh.sdh01.result_text_07").attr("disabled", "none");
                    } else {
                        $(".sdh.sdh01.result_text_07").attr("disabled", false);
                    }
                } else {
                    $(".sdh.sdh01.result_text_07").attr("disabled", false);
                }
                selname = $(".sdh.sdh01.result_select_08").val().trim();
                if (
                    selname == "入庫済" &&
                    (me.condition4 == "1" || me.condition4 == "2") &&
                    me.data["sdh01_hantei_naiyou"].length > 0
                ) {
                    ymdhm = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["UPDYMDHM"]
                    );
                    ymdhm = ymdhm.substr(0, 8);
                    ydate = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["SYSYMDHM"]
                    );
                    ydate = ydate.substr(0, 8);
                    if (ydate >= ymdhm) {
                        $(".sdh.sdh01.result_text_08").attr("disabled", "none");
                    } else {
                        $(".sdh.sdh01.result_text_08").attr("disabled", false);
                    }
                } else {
                    $(".sdh.sdh01.result_text_08").attr("disabled", false);
                }
            }
            //20190227 YIN INS S
            if (me.condition4 == "3") {
                $(".mcdropdown.sdh_mcDropdown7" + " a").css("display", "none");
                $(".mcdropdown.sdh_mcDropdown8" + " a").css("display", "none");
                $(".sdh.sdh01.result_text_08").attr("disabled", false);

                var selname = $(".sdh.sdh01.result_select_08").val().trim();
                if (
                    selname == "入庫済" &&
                    me.condition4 == "3" &&
                    me.data["sdh01_hantei_naiyou"].length > 0
                ) {
                    ymdhm = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["UPDYMDHM"]
                    );
                    ymdhm = ymdhm.substr(0, 8);
                    ydate = $.trim(
                        me.data["sdh01_hantei_naiyou"][
                            me.data["sdh01_hantei_naiyou"].length - 1
                        ]["SYSYMDHM"]
                    );
                    ydate = ydate.substr(0, 8);
                    if (ydate >= ymdhm) {
                        $(".sdh.sdh01.result_text_08").attr("disabled", "none");
                    } else {
                        $(".sdh.sdh01.result_text_08").attr("disabled", false);
                    }
                } else {
                    $(".sdh.sdh01.result_text_08").attr("disabled", false);
                }
            }
            //20190227 YIN INS E
            //----20220121 sun upd s
            //if (me.condition4 == "0")
            if (me.condition4 == "0" || me.condition4 == "4") {
                //----20220121 sun upd e
                $(".mcdropdown.sdh_mcDropdown8" + " a").css(
                    "display",
                    "inline-block"
                );
                $(".sdh.sdh01.result_text_08").attr("disabled", false);
                $(".sdh.sdh01.result_text_07").attr("disabled", false);
            }
            //20160127 YIN INS S
        } catch (e) {
            console.log("build_hantei_naiyou");
            console.log(e);
        }
    };

    me.build_tantou_henkou_rireki = function (data) {
        try {
            if (!data) {
                return;
            }
            if (data.length == 0) {
                return;
            }

            var html_str = "";
            var B1_KATACHGDAY = "";
            var B2_KATACHGDAY = "";
            var B3_KATACHGDAY = "";
            if (data[0]["B1_KATACHGDAY"].length == 8) {
                B1_KATACHGDAY =
                    data[0]["B1_KATACHGDAY"].substr(0, 4) +
                    "/" +
                    data[0]["B1_KATACHGDAY"].substr(4, 2) +
                    "/" +
                    data[0]["B1_KATACHGDAY"].substr(6, 2);
            }
            if (data[0]["B2_KATACHGDAY"].length == 8) {
                B2_KATACHGDAY =
                    data[0]["B2_KATACHGDAY"].substr(0, 4) +
                    "/" +
                    data[0]["B2_KATACHGDAY"].substr(4, 2) +
                    "/" +
                    data[0]["B2_KATACHGDAY"].substr(6, 2);
            }
            if (data[0]["B3_KATACHGDAY"].length == 8) {
                B3_KATACHGDAY =
                    data[0]["B3_KATACHGDAY"].substr(0, 4) +
                    "/" +
                    data[0]["B3_KATACHGDAY"].substr(4, 2) +
                    "/" +
                    data[0]["B3_KATACHGDAY"].substr(6, 2);
            }

            html_str = "";

            html_str += "<style type='text/css'>";
            html_str += ".sdh.table {";
            html_str += "width: 390px;";
            html_str += "background-color: #ffeaa6;";
            html_str += "border-style:none;";
            html_str += "font-weight: bold;";
            html_str += "}";
            html_str += ".sdh.td {";
            html_str += "width: 130px;";
            html_str += "background-color: #ffeaa6;";
            html_str += "border-style:none;";
            html_str += "color:gray;";
            html_str += "}";
            //-----20141021 $6 jinmingai  ins  s
            html_str += ".sdh.midasi {";
            html_str += "text-align:left;";
            html_str += "}";
            //-----20141021 $6 jinmingai  ins  e
            html_str += "</style>";

            html_str += "<table class='sdh sdh_06 table'>";
            html_str += "<tr>";
            html_str +=
                "<td colspan='3' style='background-color: #ffeaa6;border-style:none;'>";
            html_str += "<div for=''>";
            html_str += "担当変更履歴";
            html_str += "</div>";
            html_str += "</td>";
            html_str += "</tr>";
            html_str += "<tr>";
            html_str += "<td colspan='3>";
            html_str += "<div style='border:1px solid #555555'></div>";
            html_str += "</td>";
            html_str += "</tr>";

            html_str += "<tr>";
            //-----20141021 $6 jinmingai  upd  s
            // html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            //-----20141021 $6 jinmingai  upd  e
            html_str += "<div for=''>";
            html_str += "前管理担当";
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<div for=''>";
            html_str += data[0]["B1_KATANNM"];
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            html_str += "<div for=''>";
            html_str += B1_KATACHGDAY;
            html_str += "</div>";
            html_str += "</td>";

            html_str += "</tr>";
            html_str += "<tr>";
            //-----20141021 $6 jinmingai  upd  s
            // html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            //-----20141021 $6 jinmingai  upd  e
            html_str += "<div for=''>";
            html_str += "前々管理担当";
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<div for=''>";
            html_str += data[0]["B2_KATANNM"];
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            html_str += "<div for=''>";
            html_str += B2_KATACHGDAY;
            html_str += "</div>";
            html_str += "</td>";

            html_str += "</tr>";
            html_str += "<tr>";
            //-----20141021 $6 jinmingai  upd  s
            // html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            //-----20141021 $6 jinmingai  upd  e
            html_str += "<div for=''>";
            html_str += "前々前管理担当名";
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<div for=''>";
            html_str += data[0]["B3_KATANNM"];
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            html_str += "<div for=''>";
            html_str += B3_KATACHGDAY;
            html_str += "</div>";
            html_str += "</td>";

            html_str += "</tr>";
            html_str += "<tr>";
            //-----20141021 $6 jinmingai  upd  s
            // html_str += "<td class='sdh sdh_06 td'>";
            html_str += "<td class='sdh sdh_06 td midasi'>";
            //-----20141021 $6 jinmingai  upd  e
            html_str += "<div for=''>";
            html_str += "販売担当";
            html_str += "</div>";
            html_str += "</td>";
            html_str += "<td class='sdh sdh_06 td' colspan='2'>";
            html_str += "<div for=''>";
            html_str += data[0]["TANTOSYA_NM"];
            html_str += "</div>";
            html_str += "</td>";
            html_str += "</tr>";
            html_str += "</table>";

            $(".sdh.sdh01.sdh01_02.tantou_henkou_rireki").empty();
            $(".sdh.sdh01.sdh01_02.tantou_henkou_rireki").html(html_str);

            $(".sdh.sdh01.btn_tanto_rireki.tooltip.yellow-tooltip").show();
        } catch (e) {
            console.log("build_tantou_henkou_rireki");
            console.log(e);
        }
    };

    me.build_memo = function (data) {
        try {
            if (data && data.length > 0) {
                var memo = data[0]["MEMO"];
                if (memo == null) {
                    memo = "";
                }
                $(".sdh.sdh01.sdh01_07.MEMO.value").val(memo.trim());
            } else {
                $(".sdh.sdh01.sdh01_07.value").val("");
            }
            //-----20141016  #424  zhenghuiyun  ins  s
            if (me.data["first_data"]) {
                me.data["first_data"]["MEMO"] = $(
                    ".sdh.sdh01.sdh01_07.MEMO.value"
                ).val();
            }
            //-----20141016  #424  zhenghuiyun  ins  e
        } catch (e) {
            console.log("error:build_memo");
            console.log(e);
        }
    };

    me.build_keiyakusya = function (data) {
        try {
            if (data && data.length != 0) {
                for (idx in data) {
                    var item = data[idx];
                    for (key in item) {
                        if (typeof item[key] == "object") {
                            //20190306 Ci UPD S
                            if (item[key]) {
                                $(".sdh.sdh01.sdh01_02." + key + ".value").text(
                                    item[key]["id"] + ":" + item[key]["name"]
                                );
                                var ktk_color = item[key]["font-col"];
                                $(".sdh.sdh01.sdh01_02." + key + ".value").css(
                                    "color",
                                    ktk_color
                                );
                            }
                            //20190306 Ci UPD E
                        } else {
                            if ($(".sdh.sdh01.sdh01_02." + key + ".value")) {
                                var val = item[key];
                                switch (key) {
                                    //生年月日
                                    case "BRTDT":
                                        if (val.trim() != "") {
                                            var brtdt = val;
                                            var y = brtdt.substr(0, 4);
                                            var m = brtdt.substr(4, 2);
                                            var d = brtdt.substr(6, 2);
                                            val =
                                                y + "年" + m + "月" + d + "日";
                                        }
                                        break;
                                    //年齢
                                    case "AGE":
                                        if (val != "") {
                                            val = val + "歳";
                                        }
                                        break;
                                    //走行距離value
                                    // case "NKSIN1_SOKOKM":
                                    // if (val != "")
                                    // {
                                    // val = val + "km";
                                    // };
                                    // break;
                                    default:
                                        break;
                                }
                                $(".sdh.sdh01.sdh01_02." + key + ".value").text(
                                    val
                                );
                            }
                            //20160121 Update Start
                            if (key == "FRE_MEM") {
                                if (
                                    $(".sdh.sdh01.sdh01_03." + key + ".value")
                                ) {
                                    $(
                                        ".sdh.sdh01.sdh01_03." + key + ".value"
                                    ).text(item[key]);
                                }
                            }
                            //20160121 Update End

                            if (key == "SRY_FRE_MEM") {
                                if (
                                    $(".sdh.sdh01.sdh01_03." + key + ".value")
                                ) {
                                    $(
                                        ".sdh.sdh01.sdh01_03." + key + ".value"
                                    ).text(item[key]);
                                }
                            }

                            //HIT進捗状況: fuxiaolin add 20150316 start
                            if (key == "SITU") {
                                if (
                                    $(".sdh.sdh01.sdh01_03." + key + ".value")
                                ) {
                                    $(
                                        ".sdh.sdh01.sdh01_03." + key + ".value"
                                    ).text(item[key]);
                                }
                            }
                            //HIT進捗状況: fuxiaolin add 20150316 end

                            //走行距離set
                            if (key == "NKSIN1_SOKOKM") {
                                // var tobj = new gdmz.common.clsComFnc();
                                $(".sdh.sdh01.sdh01_02." + key + ".value").html(
                                    val.toString().numFormat() + "km"
                                );
                                if (val > 80000) {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "red");
                                } else {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "black");
                                }
                            }

                            //---20150417 fanzhengzhou add s.
                            //予想距離set
                            if (key == "YOSOUKILO") {
                                // var tobj = new gdmz.common.clsComFnc();
                                $(".sdh.sdh01.sdh01_02." + key + ".value").html(
                                    val.toString().numFormat() + "km"
                                );
                                if (val > 80000) {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "red");
                                } else {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "black");
                                }
                            }
                            //---20150417 fanzhengzhou add e.
                            //---20150609 fanzhengzhou add s.#1911-1
                            //車台番号・登録番号set
                            if (key == "SYADAI_BG" || key == "TOUROKU_NO") {
                                if (
                                    $.trim(item["MAS_DT"]) == "" ||
                                    $.trim(item["MAS_DT"]) == null
                                ) {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "black");
                                } else {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "red");
                                }
                            }
                            //---20150609 fanzhengzhou add e.#1911-1
                            //---20181022 HM add s.
                            //サービス拠点
                            if (key == "SRV_SRVSTRNM") {
                                if ($.trim(item["SRV_SRVSTRNM"]) == "管理外") {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "red");
                                } else {
                                    $(
                                        ".sdh.sdh01.sdh01_02." + key + ".value"
                                    ).css("color", "black");
                                }
                            }
                            //---20181022 HM add e.
                        }
                    }
                }
            } else {
                $(".sdh.sdh01.sdh01_02.value").text("");
                $(".sdh.sdh01.sdh01_03.value").text("");
            }
        } catch (e) {
            console.log("error:build_keiyakusya");
            console.log(e);
        }
    };

    me.build_nyuko_rireki = function (data) {
        try {
            if (!data) {
                return;
            }
            if (data.length == 0) {
                return;
            }

            var html_str = "";
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                //入庫履歴
                if (item["DATA_TYPE"] == "0") {
                    html_str += "<p id='fusen01' class='RecordB'>";
                    html_str +=
                        "<a href='javascript:void(0)' style='color: #880000' class='sdh sdh01 nyuko yellow-tooltip " +
                        i +
                        "'>";
                    // 入庫履歴 s
                    //フォーマット：入庫日+"　入庫　"+受付拠点+　"　"　+　代表入庫区分
                    html_str +=
                        item["URG_DT"] +
                        "　入庫　" +
                        item["KYOTN_NM"] +
                        "　" +
                        item["SYAIN_KNJ_SEI"] +
                        " " +
                        item["SYAIN_KNJ_MEI"];
                    // 入庫履歴 e
                    html_str += "</a>";
                    html_str += "<br>";
                    html_str += "入庫区分：" + item["NYUKOKBNMEI"];
                    html_str += "</p>";
                }
                //コンタクト履歴
                if (item["DATA_TYPE"] == "1") {
                    html_str += "<p class='RecordA'>";
                    //null check
                    if (
                        !item["C_SYUDAN"] &&
                        typeof item["C_SYUDAN"] != "undefined" &&
                        item["C_SYUDAN"] != 0
                    ) {
                        item["C_SYUDAN"] = "";
                    }
                    if (
                        !item["C_TAIOU"] &&
                        typeof item["C_TAIOU"] != "undefined" &&
                        item["C_TAIOU"] != 0
                    ) {
                        item["C_TAIOU"] = "";
                    }
                    if (
                        !item["C_NAIYO"] &&
                        typeof item["C_NAIYO"] != "undefined" &&
                        item["C_NAIYO"] != 0
                    ) {
                        item["C_NAIYO"] = "";
                    }
                    //20160115 add start
                    if (
                        !item["SYOHIN"] &&
                        typeof item["SYOHIN"] != "undefined" &&
                        item["SYOHIN"] != 0
                    ) {
                        item["SYOHIN"] = "";
                    }
                    //20160115 add end

                    //コンタクト日時+"　" + コンタクト手段 + "　" + コンタクト対応者
                    //20160115 update start
                    html_str +=
                        item["C_DATE"] +
                        "　" +
                        item["SYOHIN"] +
                        "　" +
                        item["C_SYUDAN"] +
                        "　" +
                        item["C_TAIOU"];
                    //20160115 update end

                    html_str += "<br>";
                    //+　"　"　+　コンタクト内容
                    html_str += "メモ：" + item["C_NAIYO"];
                    html_str += "</p>";
                }
            }
            $(".sdh.sdh01.sdh01_04.nyuko_rireki").empty();
            //-------2014/12/02 No.33 fuxiaolin add s
            $(".sdh.sdh01.sdh01_04.nyuko_rireki").scrollTop(0);
            //-------2014/12/02 No.33 fuxiaolin add e
            $(".sdh.sdh01.sdh01_04.nyuko_rireki").html(html_str);
            $(".sdh.sdh01.nyuko").click(function () {
                try {
                    $(".sdh.sdh07.dialog").remove();

                    if (me["SDH07"]) {
                        me["SDH07"] = null;
                    }

                    var tempnum = $(this).attr("class").substr(31);
                    var postdata = {
                        URG_DT: data[tempnum]["URG_DT"],
                        NYUKOKBNMEI: data[tempnum]["NYUKOKBNMEI"],
                        SYAIN_KNJ_MEI:
                            data[tempnum]["SYAIN_KNJ_SEI"] +
                            " " +
                            data[tempnum]["SYAIN_KNJ_MEI"],
                        VIN_RBN: me.cur_hantei_item.data("VIN_VIS"),
                        VIN_SDI_KAT: me.cur_hantei_item.data("VIN_WMIVDS"),
                        NYUKOKBN: data[tempnum]["NYUKOKBN"],
                        SEB_NOU_NO: data[tempnum]["SEB_NOU_NO"],
                    };
                    me.open_dialog("SDH07", null, null, postdata);
                } catch (e) {
                    console.log(e);
                }
            });
        } catch (e) {
            console.log("error:build_nyuko_rireki");
            console.log(e);
        }
    };

    me.build_hantei_list = function (data, parameters) {
        try {
            if (!data) {
                return;
            }
            if (data.length == 0) {
                return;
            }

            //車両区分 s
            var arr_syaryo_kubun = {
                0: "00：自新直",
                1: "01：自新業",
                2: "02：自新特",
                3: "03：他社新",
                4: "04：自新リ",
                5: "05：自中直",
            };
            //車両区分 e
            var selCondition3 = "0";
            selCondition3 = $(".sdh.sdh02.selectconditions3").val();
            //----20220121 sun add s
            var selCondition4 = "0";
            selCondition4 = $(".sdh.sdh02.selectconditions4").val();
            var hantei_name = "";
            //----20220121 sun add e

            var syaryo_kubun = "";

            var html_str = "";

            html_str += '<table style="width:100%">';

            var num = 0;
            for (idx in data) {
                var item = data[idx];
                num = num + 1;
                //車両区分
                if (
                    selCondition3 == "0" ||
                    typeof selCondition3 == "undefined"
                ) {
                    //----20220121 sun add s
                    if (selCondition4 == "4" && item["HANTEINAME"] == null) {
                        item["HANTEINAME"] = "";
                    }
                    if (
                        selCondition4 == "4" &&
                        hantei_name + syaryo_kubun !==
                            item["HANTEINAME"] + item["XH10CAID"] &&
                        item["XH10CAID"] in arr_syaryo_kubun
                    ) {
                        syaryo_kubun = item["XH10CAID"];
                        hantei_name = item["HANTEINAME"];

                        html_str += '<tr style="width:100%">';
                        html_str += '<td style="width:100%">';
                        //20220217 YIN UPD S
                        // html_str += "<div class=\"sdh sdh01 sdh01_08 title carlistTitle\" style=\"width:100%\">";
                        if (
                            item["HANTEINAME"] == "代替確定" ||
                            item["HANTEINAME"] == "入庫確定"
                        ) {
                            html_str +=
                                '<div class="sdh sdh01 sdh01_08 title carlistTitle" style="width:100%;background-color: #ffa500;color: #000000;">';
                        } else if (
                            item["HANTEINAME"] == "代替促進◎" ||
                            item["HANTEINAME"] == "入庫促進(営)◎" ||
                            item["HANTEINAME"] == "入庫促進(サ)◎"
                        ) {
                            html_str +=
                                '<div class="sdh sdh01 sdh01_08 title carlistTitle" style="width:100%;background-color: #ffa50080;color: #000000;">';
                        } else {
                            html_str +=
                                '<div class="sdh sdh01 sdh01_08 title carlistTitle" style="width:100%">';
                        }
                        //20220217 YIN UPD E
                        html_str +=
                            hantei_name + " " + arr_syaryo_kubun[syaryo_kubun];
                        html_str += "</div>";
                        html_str += "</td>";
                        html_str += "</tr>";
                    }
                    //----20220121 sun add e
                    else if (
                        syaryo_kubun !== item["XH10CAID"] &&
                        item["XH10CAID"] in arr_syaryo_kubun
                    ) {
                        syaryo_kubun = item["XH10CAID"];

                        html_str += '<tr style="width:100%">';
                        html_str += '<td style="width:100%">';
                        html_str +=
                            '<div class="sdh sdh01 sdh01_08 title carlistTitle" style="width:100%">';
                        html_str += arr_syaryo_kubun[syaryo_kubun];
                        html_str += "</div>";
                        html_str += "</td>";
                        html_str += "</tr>";
                    }
                }

                html_str += '<tr style="width:100%">';
                html_str += '<td style="width:100%">';

                html_str += '<div class="sdh sdh01 sdh01_08 item ALL carlist';
                html_str += "_";
                html_str += idx;
                html_str +=
                    ' " style="width: 100%;height: 100%;cursor: pointer">';

                // index s
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item index" style="display: none">';
                html_str += idx;
                html_str += "</div>";

                // 注文書NO s
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item CMN_NO" style="display: none">';
                html_str += item["CMN_NO"];
                html_str += "</div>";

                // 車台番号 s
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item VIN_WMIVDS" style="display: none">';
                html_str += item["VIN_WMIVDS"];
                html_str += "</div>";

                // カーNo s
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item VIN_VIS" style="display: none">';
                html_str += item["VIN_VIS"];
                html_str += "</div>";

                // 顧客コード s
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item DLRCSRNO" style="display: none">';
                html_str += item["DLRCSRNO"];
                html_str += "</div>";

                //20150714 Update Start
                //20160229 Sun UPD S.
                if (parameters["condition4"] == "0") {
                    //20180408 YIN UPD S
                    var explorer = window.navigator.userAgent;
                    if (explorer.indexOf("Safari") >= 0) {
                        html_str +=
                            '<table style="width:100%;border:outset;" border=0>';
                    } else if (explorer.indexOf("Firefox") >= 0) {
                        html_str +=
                            '<table style="width:100%;border:3px solid;border-top-color:#EEE;border-bottom-color:#999;border-left-color:#EEE;border-right-color:#999" border=0>';
                    } else {
                        html_str +=
                            '<table style="width:100%;border:outset" border=0>';
                    }
                    //20180408 YIN UPD E
                } else {
                    //20180408 YIN UPD S
                    var explorer = window.navigator.userAgent;
                    if (explorer.indexOf("Safari") >= 0) {
                        html_str +=
                            '<table style="width:100%;border:outset;table-layout:fixed" border=0>';
                    } else if (explorer.indexOf("Firefox") >= 0) {
                        html_str +=
                            '<table style="width:100%;border:3px solid;border-top-color:#EEE;border-bottom-color:#999;border-left-color:#EEE;border-right-color:#999;table-layout:fixed" border=0>';
                    } else {
                        html_str +=
                            '<table style="width:100%;border:outset;table-layout:fixed" border=0>';
                    }
                    //20180408 YIN UPD E
                }
                //20160229 Sun UPD E.
                //１行目
                html_str += '<tr style="width:100%">';

                // お客様名 s
                html_str += '<td style="width:60%" colspan="1">';
                html_str += '<div style="width:100%">';
                html_str +=
                    '<span class="sdh sdh01 sdh01_08 item CSRNM1" style="width:100%;text-align: left;font-size:12px">';
                html_str += item["CSRNM1"];
                html_str += "</span>";
                html_str += "</div>";
                html_str += "</td>";

                html_str += '<td style="width:30%">';
                html_str +=
                    '<div class="sdh sdh01 sdh01_08 item VCLNM" style="width:100%;text-align: left">';
                html_str += item["VCLNM"];
                html_str += "</div>";
                html_str += "</td>";

                // idx
                html_str += "<td>";
                html_str +=
                    '<span class="sdh sdh01 sdh01_08 item num" style="float:right">';
                html_str += num;
                html_str += "</span>";
                html_str += "</td>";
                html_str += "</tr>";

                //----20220121 sun upd s
                //if (parameters["condition4"] == "0")
                if (
                    parameters["condition4"] == "0" ||
                    parameters["condition4"] == "4"
                ) {
                    //----20220121 sun upd e
                    //２行目
                    html_str += '<tr style="width:100%">';
                    //20220217 lujunxia ins s
                    //モードが「代替・入庫見込」（４）なら、表示しない
                    if (parameters["condition4"] != "4") {
                        //20220217 lujunxia ins e
                        html_str += '<td style="width:40%">';
                        // 初度登録
                        //				html_str += "<span class=\"sdh sdh01 sdh01_08 label FRGMH\" style=\"text-align: left\">";
                        html_str += "<span>";
                        html_str += "　(初度登録)　";
                        html_str += "</span>";

                        var frgMH = item["FRGMH"];
                        frgMH = frgMH.substr(0, 4) + "/" + frgMH.substr(4, 2);

                        html_str += frgMH;
                        html_str += "</span>";
                        html_str += "</td>";
                        //20220217 lujunxia ins s
                    }
                    //20220217 lujunxia ins e
                    //前月
                    html_str += '<td style="width:60%">';
                    //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                    html_str += "<span>";
                    html_str += "(前月)　";
                    html_str += "</span>";

                    var NAME6Val = "";
                    if (item["NAME6"] != "" && item["NAME6"] != null) {
                        NAME6Val = item["NAME6"];
                    }
                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item MAEGETU" style="width:100%;text-align: left">';
                    html_str += NAME6Val;
                    html_str += "</span>";
                    html_str += "</td>";
                    html_str += "</tr>";

                    //３行目
                    html_str += '<tr style="width:100%">';
                    //20220217 lujunxia ins s
                    //モードが「代替・入庫見込」（４）なら、表示しない
                    if (parameters["condition4"] != "4") {
                        //20220217 lujunxia ins e
                        html_str += '<td style="width:40%">';
                        html_str += "<div>";
                        // 車検年月 s
                        //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                        html_str += "<span>";
                        html_str += "　(車検満了)　";
                        html_str += "</span>";

                        var vclipedt = item["DISP_VCLIPEDT"];
                        vclipedt =
                            vclipedt.substr(0, 4) +
                            "/" +
                            vclipedt.substr(4, 2) +
                            "/" +
                            vclipedt.substr(6, 2);
                        html_str +=
                            '<span class="sdh sdh01 sdh01_08 item VCLIPEDT" style="width:100%;text-align: left">';
                        html_str += vclipedt;
                        html_str += "</span>";
                        html_str += "</div>";
                        html_str += "</td>";
                        //20220217 lujunxia ins s
                    }
                    //20220217 lujunxia ins e
                    // 当月
                    html_str += '<td style="width:60%">';

                    //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                    html_str += "<span>";
                    html_str += "(当月)　";
                    html_str += "</span>";

                    var NAME7Val = "";
                    if (item["NAME7"] != "" && item["NAME7"] != null) {
                        NAME7Val = item["NAME7"];
                    }
                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item TOUGETU" style="width:100%;text-align: left">';
                    html_str += NAME7Val;
                    html_str += "</span>";
                    html_str += "</div>";
                    html_str += "</td>";

                    //４行目

                    html_str += '<tr style="width:100%">';
                    //20220217 lujunxia ins s
                    //モードが「代替・入庫見込」（４）なら、表示しない
                    if (parameters["condition4"] != "4") {
                        //20220217 lujunxia ins e
                        html_str += '<td style="width:40%">';

                        //走行距離
                        html_str += "<div>";
                        //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"text-align: left\">";
                        html_str += "<span>";
                        html_str += "　(予想距離)　";
                        html_str += "</span>";

                        var sokoKm = item["YOSOKILO"];

                        if (sokoKm > 80000) {
                            html_str +=
                                '<span class="sdh sdh01 sdh01_08 item SOKOKM" style="text-align: left;color:red">';
                        } else {
                            html_str +=
                                '<span class="sdh sdh01 sdh01_08 item SOKOKM" style="text-align: left;color:black">';
                        }
                        html_str += sokoKm.toString().numFormat() + " Km";
                        html_str += "</span>";
                        html_str += "</div>";
                        html_str += "</td>";
                        //20220217 lujunxia ins s
                    }
                    //20220217 lujunxia ins e
                    // 最終
                    html_str += '<td style="width:60%">';
                    html_str += "<div>";
                    //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                    html_str += "(最終)　";
                    html_str += "</span>";

                    var KEKKAVal = "";
                    if (item["KEKKA"] != "" && item["KEKKA"] != null) {
                        KEKKAVal = item["KEKKA"];
                    }
                    //20220217 lujunxia ins s
                    //20220222 lujunxia upd s
                    //「最終結果」に「代替　注文書NO」があるの場合、代替注文書の車種を表示
                    if (
                        parameters["condition4"] == "4" &&
                        item["BASEH_KN"] != "" &&
                        item["BASEH_KN"] != null
                    ) {
                        //20220222 lujunxia upd e
                        KEKKAVal += "（" + item["BASEH_KN"] + "）";
                    }
                    //20220217 lujunxia ins e

                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item KEKKA" style="width:100%;text-align: left">';
                    html_str += KEKKAVal + "</span>";

                    html_str += "</td>";
                    html_str += "</tr>";
                    html_str += "</table>";
                    html_str += "</div>";
                    html_str += "</td>";

                    // BLANK
                    html_str += "<td>";
                    html_str += "</td>";
                    html_str += "</tr>";
                } else {
                    //２行目
                    html_str += '<tr style="width:100%">';

                    html_str += '<td style="width:40%">';
                    // 初度登録
                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item FRGMH" style="text-align: left">';
                    html_str += "<span>";
                    //20190318 CI UPD S
                    if (parameters["condition4"] == "3") {
                        html_str += "　(売上日)　";
                    } else {
                        html_str += "　(初度登録)　";
                    }
                    //20190318 CI UPD E
                    html_str += "</span>";

                    var frgMH = item["FRGMH"];
                    frgMH = frgMH.substr(0, 4) + "/" + frgMH.substr(4, 2);
                    //20190318 CI Add S
                    var touDT = item["TOU_DT"];
                    touDT = touDT.substr(0, 4) + "/" + touDT.substr(4, 2);
                    //20190318 CI Add E
                    //20160309 Add S
                    //html_str += "<span class=\"sdh sdh01 sdh01_08 item FRGMH\" style=\"width:100%;text-align: left\">";
                    var onscheKb = item["ONSCHEDULEKBN"];
                    if (onscheKb == "遅延") {
                        html_str +=
                            '<span style="width:100%;text-align: left;color:red">';
                    } else {
                        html_str +=
                            '<span style="width:100%;text-align: left;color:black">';
                    }
                    //20160309 Add E

                    //20190318 CI Add S
                    if (parameters["condition4"] == "3") {
                        html_str += touDT;
                    } else {
                        html_str += frgMH;
                    }
                    //20190318 CI Add E
                    html_str += "</span>";
                    html_str += "</td>";

                    // //前月
                    // html_str += "<td style=\"width:60%\">";
                    // //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                    // html_str += "<span>";
                    // html_str += "　(前月)　";
                    // html_str += "</span>";
                    //
                    // var NAME6Val = "";
                    // if (item["NAME6"] != "" && item["NAME6"] != null)
                    // {
                    // NAME6Val = item["NAME6"];
                    // }
                    // html_str += "<span class=\"sdh sdh01 sdh01_08 item MAEGETU\" style=\"width:100%;text-align: left\">";
                    // html_str += NAME6Val;
                    // html_str += "</span>";
                    // html_str += "</td>";
                    html_str += "</tr>";

                    //３行目
                    html_str += '<tr style="width:100%">';
                    //20160229 Sun UPD S.
                    //html_str += "<td style=\"width:65%\">";
                    html_str += '<td style="width=65%;white-space: nowrap">';
                    //20160229 Sun UPD E.
                    html_str += "<div>";
                    // 車検年月 s
                    //				html_str += "<span class=\"sdh sdh01 sdh01_08 label\" style=\"width:100%;text-align: left\">";
                    html_str += "<span>";
                    //20190304 ci UPD S
                    if (parameters["condition4"] == "3") {
                        html_str += "　(中古１ヶ月)　";
                    } else {
                        html_str += "　(新車１ヶ月)　";
                    }
                    //20190304 ci UPD E
                    html_str += "</span>";
                    var NAME6Val = "";
                    if (item["SNAME7"] != "" && item["SNAME7"] != null) {
                        NAME6Val = item["SNAME7"];
                    }
                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item KEKKA1_CD" style="width:100%;text-align: left">';
                    html_str += NAME6Val;
                    html_str += "</span>";

                    var KEKKA1Val = "";
                    if (
                        item["SNAMEKEKKA1"] != "" &&
                        item["SNAMEKEKKA1"] != null
                    ) {
                        KEKKA1Val = item["SNAMEKEKKA1"];
                    }
                    html_str +=
                        '<span class="sdh sdh01 sdh01_08 item TOUGETU" style="width:100%;text-align: left">';
                    html_str += "　" + KEKKA1Val;
                    html_str += "</span>";
                    html_str += "</div>";
                    html_str += "</td>";

                    //４行目

                    html_str += '<tr style="width:100%">';
                    //20160229 Sun UPD S.
                    //html_str += "<td style=\"width:65%\">";
                    html_str += '<td style="width:65%;white-space: nowrap">';
                    //20160229 Sun UPD E.

                    //走行距離
                    if (parameters["condition4"] == "2") {
                        html_str += "<div>";
                        html_str += "<span>";
                        html_str += "　(新車６ヶ月)　";
                        html_str += "</span>";
                        var KEKKAVal = "";
                        if (item["SNAME"] != "" && item["SNAME"] != null) {
                            KEKKAVal = item["SNAME"];
                        }
                        html_str +=
                            '<span class="sdh sdh01 sdh01_08 item KEKKA6_CD" style="width:100%;text-align: left">';
                        html_str += KEKKAVal;
                        html_str += "</span>";
                        var KEKKA6Val = "";
                        if (
                            item["SNAMEKEKKA6"] != "" &&
                            item["SNAMEKEKKA6"] != null
                        ) {
                            KEKKA6Val = item["SNAMEKEKKA6"];
                        }
                        html_str +=
                            '<span class="sdh sdh01 sdh01_08 item KEKKA" style="width:100%;text-align: left">';
                        html_str += "　" + KEKKA6Val + "</span>";
                        html_str += "</div>";
                    }
                    html_str += "</td>";

                    html_str += "</tr>";
                    html_str += "</table>";
                    html_str += "</div>";
                    html_str += "</td>";

                    // BLANK
                    html_str += "<td>";
                    html_str += "</td>";
                    html_str += "</tr>";
                }
            }
            html_str += "</table>";
            //20150714 Update End

            $(".sdh.sdh01.content.left.hantei_list").empty();
            $(".sdh.sdh01.content.left.hantei_list").html(html_str);

            for (idx in data) {
                var item = data[idx];
                item["idx"] = idx;
                // var cmn_no = item["CMN_NO"];
                //-----20141127 NO.4 fanzhengzhou  upd  s
                //$(".sdh.sdh01.sdh01_08.item.ALL" + "." + cmn_no).data(item);
                $(
                    ".sdh.sdh01.sdh01_08.item.ALL.carlist" + "_" + item["idx"]
                ).data(item);
                //-----20141127 NO.4 fanzhengzhou  upd  e
            }

            if (data.length > 0) {
                me.color_data = [];
                //----20220121 sun add s
                for (var i = 0; i < data.length; i++) {
                    var hantei_item = $(
                        ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                            "_" +
                            data[i]["idx"]
                    );
                    if (selCondition4 == 4) {
                        if (data[i]["CHECKED_YM"] == 1) {
                            hantei_item.css("background-color", "darkgray");
                        } else if (data[i]["CHECKED_YM"] == 2) {
                            hantei_item.css("background-color", "gold");
                        } else if (me.backgroundcolor_set(data[i])) {
                            hantei_item.css("background-color", "gold");
                            data[i]["CHECKED_YM"] = 2;
                        }
                    }
                    var colorarr = {
                        idx: data[i]["idx"],
                        CHECKED_YM: data[i]["CHECKED_YM"],
                    };
                    me.color_data.push(colorarr);
                }
                //----20220121 sun add e

                var item = data[0];

                if (me.cur_hantei_item) {
                    me.cur_hantei_item.css("background-color", "");
                }
                //-----20141127 NO.4 fanzhengzhou  upd  s
                //me.cur_hantei_item = $(".sdh.sdh01.sdh01_08.item.ALL" + "." + item["CMN_NO"]);
                me.cur_hantei_item = $(
                    ".sdh.sdh01.sdh01_08.item.ALL.carlist" + "_" + item["idx"]
                );
                //-----20141127 NO.4 fanzhengzhou  upd  s
                me.cur_hantei_item.css("background-color", "#FFCCCC");
                //----20220121 sun add s
                if (selCondition4 == 4) {
                    if (data[0]["CHECKED_YM"] == 1) {
                        $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                            "進捗確認済";
                        $(".sdh.sdh01.btn_sinchoku").css(
                            "background",
                            "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                        );
                    } else {
                        $(".sdh.sdh01.btn_sinchoku")[0].innerHTML = "進捗確認";
                        $(".sdh.sdh01.btn_sinchoku").css(
                            "background",
                            "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                        );
                    }
                }
                //----20220121 sun add e

                $(".sdh.sdh01.current_all.lbl_count.page_no").text("1");
                $(".sdh.sdh01.current_all.lbl_count.total").text(data.length);
            }

            var tmp_clicked_item = undefined;

            /**
             * 判定リスト項目クリック
             */
            $(".sdh.sdh01.sdh01_08.item.ALL").click(function () {
                tmp_clicked_item = $(this);

                if (me.cur_hantei_item) {
                    if (
                        tmp_clicked_item[0].className ==
                        me.cur_hantei_item[0].className
                    ) {
                        return;
                    }
                }

                me.check_change(func_yes, func_no);

                function func_yes() {
                    if (me.cur_hantei_item) {
                        me.cur_hantei_item.css("background-color", "");
                    }

                    me.cur_hantei_item = tmp_clicked_item;

                    //----20220121 sun add s
                    if ((selCondition4 = 4)) {
                        for (var i = 0; i < me.color_data.length; i++) {
                            var dt = me.color_data[i];
                            var hantei_item = $(
                                ".sdh.sdh01.sdh01_08.item.ALL.carlist" +
                                    "_" +
                                    dt["idx"]
                            );

                            if (dt["CHECKED_YM"] == 1) {
                                hantei_item.css("background-color", "darkgray");
                            } else if (dt["CHECKED_YM"] == 2) {
                                hantei_item.css("background-color", "gold");
                            }
                        }

                        if (me.cur_hantei_item.data()["CHECKED_YM"] == 1) {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認済";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #9f9f9f,#EEEEEE)"
                            );
                        } else {
                            $(".sdh.sdh01.btn_sinchoku")[0].innerHTML =
                                "進捗確認";
                            $(".sdh.sdh01.btn_sinchoku").css(
                                "background",
                                "linear-gradient(#EEEEEE, #FFCC55,#EEEEEE)"
                            );
                        }
                    }
                    //----20220121 sun add e

                    me.cur_hantei_item.css("background-color", "#FFCCCC");

                    me.get_hantei_item_data(me.cur_hantei_item.data());
                    //20220610 ci ins s
                    me.scrolltop = $(
                        ".sdh.sdh01.content.left.hantei_list"
                    ).scrollTop();
                    //20220610 ci ins e
                }

                function func_no() {}
            });
        } catch (e) {
            console.log("error:build_hantei_list");
            console.log(e);
        }
    };

    //----20220121 sun add s
    me.backgroundcolor_set = function (data) {
        if (data["HANTEI7_CD"] != null) {
            var cd = data["HANTEI7_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI6_CD"] != null) {
            var cd = data["HANTEI6_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI5_CD"] != null) {
            var cd = data["HANTEI5_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI4_CD"] != null) {
            var cd = data["HANTEI4_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI3_CD"] != null) {
            var cd = data["HANTEI3_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI2_CD"] != null) {
            var cd = data["HANTEI2_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else if (data["HANTEI1_CD"] != null) {
            var cd = data["HANTEI1_CD"].substring(0, 2);
            if (cd == "04" || cd == "06") {
                return true;
            }
        } else {
            return false;
        }
    };
    //----20220121 sun add e

    /**
     * 担当者リストを取得SDH_02_LOAD_SEL
     * @return {String} $option_list 担当者リスト <option value =""></option>のリスト
     */
    me.build_tenpo_option_list = function (data, parameters) {
        try {
            if (!data) {
                return;
            }
            if (data.length == 0) {
                return;
            }

            var option = "";
            var option_list = "";
            var option_tmp = '<option value="{val}">{txt}</option>';

            //店舗全員 s
            var replaces = {
                val: "000",
                txt: "店舗全員",
            };

            option = replace(option_tmp, replaces);
            option_list += option;
            //店舗全員 e

            //営業全員 s
            replaces = {
                val: "001",
                txt: "営業全員",
            };

            option = replace(option_tmp, replaces);
            option_list += option;
            //営業全員 e

            //担当者 s
            for (var i = 0; i < data.length; i++) {
                var obj = data[i];
                //--------fuxiaolin No.35&36 upd s
                replaces.val = obj["SYAIN_NO"];
                replaces.txt = obj["SYAIN_NM"];
                //--------fuxiaolin No.35&36 upd e
                option = replace(option_tmp, replaces);
                option_list += option;
            }
            //担当者 e

            //サービス s
            replaces = {
                val: "002",
                txt: "サービス",
            };

            option = replace(option_tmp, replaces);
            option_list += option;
            //サービス e

            $(".sdh.sdh01.sel_tenpo").empty();
            $(".sdh.sdh01.sel_tenpo").html(option_list);

            if (parameters) {
                //2014/10/20 fanzhengzhou add s.
                if (typeof parameters["tantousya_code"] == "undefined") {
                    parameters["tantousya_code"] = "000";
                }
                //2014/10/20 fanzhengzhou add e.
                $(".sdh.sdh01.sel_tenpo").val(parameters["tantousya_code"]);
            }
        } catch (e) {
            console.log("error:build_tenpo_option_list");
            console.log(e);
        }
    };

    me.build_hanteinengetu = function (nengetu) {
        try {
            //----20220121 sun upd s
            //if (me.condition4 == "0")
            if (me.condition4 == "0" || me.condition4 == "4") {
                //----20220121 sun upd e
                var y = nengetu.substr(0, 4);
                var m = nengetu.substr(4, 2);
                var dt = $.exDate(y + "/" + m + "/" + "01");

                for (var i = 0; i < 7; i++) {
                    // 20230911 YIN UPD S
                    // var str = dt.addMonths(-i).toChar("yyyy年mm月");
                    if (i == 4) {
                        var str = dt.addMonths(-6).toChar("yyyy年mm月");
                    } else if (i == 5) {
                        var str = dt.addMonths(-7).toChar("yyyy年mm月");
                    } else if (i == 6) {
                        var str = dt.addMonths(-13).toChar("yyyy年mm月");
                    } else {
                        var str = dt.addMonths(-i).toChar("yyyy年mm月");
                    }
                    // 20230911 YIN UPD E
                    $(".sdh.sdh01.hanteinengetu_0" + (7 - i)).text(str);
                    $(".mcdropdown.sdh_mcDropdown" + i + " a").css(
                        "display",
                        "inline-block"
                    );
                    $(".sdh.sdh01.hantei_0" + i).css("opacity", "1");
                    $(".sdh.sdh01.result_text_0" + i).attr("disabled", false);
                    $(".sdh.sdh01.hanteinengetu_btn_0" + (7 - i)).css(
                        "display",
                        "inline-block"
                    );

                    //--- 20160127 li INS S
                    $(".sdh.sdh01.hanteinengetu_btn_01").css("display", "none");
                    $(".sdh.sdh01.result_menu_0" + (7 - i)).html("");
                    var tttArr = {};
                    var arrTop = new Array();
                    var arr2 = new Array();

                    //20190306 YIN UPD S
                    // if (me.data["sdh01_menu"].length != 0)
                    html_01_str = "";
                    if (
                        me.data["sdh01_menu"] &&
                        me.data["sdh01_menu"].length != 0
                    ) {
                        //20190306 YIN UPD E
                        for (key in me.data["sdh01_menu"]) {
                            if (
                                me.data["sdh01_menu"][key]["MENU_TYPE"] == "0"
                            ) {
                                tttArr = {};
                                tttArr["TEIKEI_CD"] =
                                    me.data["sdh01_menu"][key]["TEIKEI_CD"];
                                tttArr["ITEMNAME"] =
                                    me.data["sdh01_menu"][key]["ITEMNAME1"];
                                arrTop.push(tttArr);
                            }
                            if (
                                me.data["sdh01_menu"][key]["MENU_TYPE"] == "1"
                            ) {
                                tttArr = {};
                                tttArr["TEIKEI_CD"] =
                                    me.data["sdh01_menu"][key]["TEIKEI_CD"];
                                tttArr["ITEMNAME"] =
                                    me.data["sdh01_menu"][key]["ITEMNAME2"];
                                arr2.push(tttArr);
                            }
                        }
                        //20190306 YIN DEL S
                        // html_01_str = "";
                        //20190306 YIN DEL E

                        for (var m = 0; m < arrTop.length; m++) {
                            mark = false;
                            for (var m1 = 0; m1 < arr2.length; m1++) {
                                if (
                                    arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                                    arr2[m1]["TEIKEI_CD"].substr(0, 2)
                                ) {
                                    mark = true;
                                    break;
                                }
                            }
                            if (mark) {
                                html_01_str +=
                                    '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                                html_01_str += arrTop[m]["ITEMNAME"];

                                html_01_str += "<ul>";
                                for (var m1 = 0; m1 < arr2.length; m1++) {
                                    if (
                                        arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                                        arr2[m1]["TEIKEI_CD"].substr(0, 2)
                                    ) {
                                        html_01_str +=
                                            '<li rel="' +
                                            arr2[m1]["TEIKEI_CD"] +
                                            '"style="width:100px;">';
                                        html_01_str += arr2[m1]["ITEMNAME"];
                                        html_01_str += "</li>";
                                    }
                                }
                                html_01_str += "</ul>";
                                html_01_str += "</li>";
                            } else {
                                html_01_str +=
                                    '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                                html_01_str += arrTop[m]["ITEMNAME"];
                                html_01_str += "</li>";
                            }
                        }
                    }
                    $(".sdh.sdh01.result_menu_0" + (7 - i)).html(html_01_str);

                    //--- 20160304 li INS S
                    $(
                        ".sdh.sdh01.result_menu_0" +
                            (7 - i) +
                            ".mcdropdown_menu"
                    ).remove();
                    switch (7 - i) {
                        case 7:
                            $(".sdh.sdh01.result_07").html(
                                me.sdh_sdh01_result_07
                            );
                            break;
                        case 6:
                            $(".sdh.sdh01.result_06").html(
                                me.sdh_sdh01_result_06
                            );
                            break;
                        case 5:
                            $(".sdh.sdh01.result_05").html(
                                me.sdh_sdh01_result_05
                            );
                            break;
                        case 4:
                            $(".sdh.sdh01.result_04").html(
                                me.sdh_sdh01_result_04
                            );
                            break;
                        case 3:
                            $(".sdh.sdh01.result_03").html(
                                me.sdh_sdh01_result_03
                            );
                            break;
                        case 2:
                            $(".sdh.sdh01.result_02").html(
                                me.sdh_sdh01_result_02
                            );
                            break;
                        case 1:
                            $(".sdh.sdh01.result_01").html(
                                me.sdh_sdh01_result_01
                            );
                            break;
                        default:
                            break;
                    }
                    //--- 20160304 li INS E

                    $(".sdh.sdh01.result_select_0" + (7 - i)).mcDropdown(
                        ".sdh.sdh01.result_menu_0" + (7 - i),
                        {
                            select: function (value) {
                                if (value.length != 4) {
                                    value = value + "00";
                                }
                                var id = "#" + this.$id;
                                var idx = $(id).data("idx");
                                $(".sdh.sdh01.result_text_0" + idx).val("");
                                $(".sdh.sdh01.result_text_0" + idx).data(
                                    "code",
                                    value
                                );
                                //HANTEILST_SINSYAの更新年月日がシステム日付より過去　AND コンボボックスの値が「入庫済」の場合は入力不可にする
                                $(".sdh.sdh01.result_text_0" + idx).attr(
                                    "disabled",
                                    false
                                );
                            },
                        },
                        "sdh_mcDropdown" + (7 - i)
                    );
                    $("#sdh_mcDropdown" + (7 - i)).data("idx", 7 - i);
                    $(".sdh.sdh01.result_text_0" + (7 - i)).empty();
                    $(".sdh.sdh01.result_text_0" + (7 - i)).width("95%");
                    //--- 20160127 li INS E
                }
                //20160229 YIn INS S
                $(".sdh.sdh01.hanteinengetu_07").css("width", "80%");
                //20160229 YIn INS E

                $(".sdh.sdh01.hantei_01").css("background-color", "#FFDDDD");
                $(".sdh.sdh01.hantei_02").css("background-color", "#FFCCAA");
                $(".sdh.sdh01.hantei_03").css("background-color", "#FFEEAA");
                $(".sdh.sdh01.hantei_04").css("background-color", "#AAFFAA");
                $(".sdh.sdh01.hantei_05").css("background-color", "#AAFFCC");
                $(".sdh.sdh01.hantei_06").css("background-color", "#AAEEFF");
                //20190227 YIN INS S
                $(".sdh.sdh01.hantei_07").css("opacity", "1");
                $(".sdh.sdh01.hantei_07").css("background-color", "#BBCCEE");
                //20190227 YIN INS E
            }
            //20190227 YIN INS S
            else if (me.condition4 == "3") {
                for (var i = 0; i < 8; i++) {
                    $(".sdh.sdh01.hantei_0" + i).css(
                        "background-color",
                        "#CCCCCC"
                    );
                    $(".sdh.sdh01.hantei_0" + i).css("opacity", "0.5");
                    $(".sdh.sdh01.result_text_0" + i).attr(
                        "disabled",
                        "disabled"
                    );
                    $(".mcdropdown.sdh_mcDropdown" + i + " a").css(
                        "display",
                        "none"
                    );
                    $(".sdh.sdh01.hanteinengetu_0" + i).text("");
                    $(".sdh.sdh01.hanteinengetu_btn_0" + (8 - i)).css(
                        "display",
                        "none"
                    );

                    //--- 20160127 li INS S
                    $(".sdh.sdh01.result_menu_0" + (8 - i)).html("");
                }
            }
            //20190227 YIN INS E
            else {
                for (var i = 0; i < 7; i++) {
                    $(".sdh.sdh01.hantei_0" + i).css(
                        "background-color",
                        "#CCCCCC"
                    );
                    $(".sdh.sdh01.hantei_0" + i).css("opacity", "0.5");
                    $(".sdh.sdh01.result_text_0" + i).attr(
                        "disabled",
                        "disabled"
                    );
                    $(".mcdropdown.sdh_mcDropdown" + i + " a").css(
                        "display",
                        "none"
                    );
                    $(".sdh.sdh01.hanteinengetu_0" + i).text("");
                    $(".sdh.sdh01.hanteinengetu_btn_0" + (7 - i)).css(
                        "display",
                        "none"
                    );
                    //20160229 YIn INS S
                    $(".sdh.sdh01.hanteinengetu_07").css("width", "100%");
                    //20160229 YIn INS E
                    //20190312 wy UPD S
                    //$(".sdh.sdh01.hanteinengetu_07").text("　新車１ヶ月結果");
                    $(".sdh.sdh01.hanteinengetu_07").text("新車１ヶ月点検判定");
                    //20190312 wy UPD E

                    //--- 20160127 li INS S
                    $(".sdh.sdh01.result_menu_0" + (7 - i)).html("");
                    var tttArr = {};
                    var arrTop = new Array();
                    var arr2 = new Array();

                    //20190306 YIN UPD S
                    // if (me.data["sdh01_menu"].length != 0)
                    html_01_str = "";
                    if (
                        me.data["sdh01_menu"] &&
                        me.data["sdh01_menu"].length != 0
                    ) {
                        //20190306 YIN UPD E
                        for (key in me.data["sdh01_menu"]) {
                            if (
                                me.data["sdh01_menu"][key]["MENU_TYPE"] == "0"
                            ) {
                                tttArr = {};
                                tttArr["TEIKEI_CD"] =
                                    me.data["sdh01_menu"][key]["TEIKEI_CD"];
                                tttArr["ITEMNAME"] =
                                    me.data["sdh01_menu"][key]["ITEMNAME1"];
                                arrTop.push(tttArr);
                            }
                            if (
                                me.data["sdh01_menu"][key]["MENU_TYPE"] == "1"
                            ) {
                                tttArr = {};
                                tttArr["TEIKEI_CD"] =
                                    me.data["sdh01_menu"][key]["TEIKEI_CD"];
                                tttArr["ITEMNAME"] =
                                    me.data["sdh01_menu"][key]["ITEMNAME2"];
                                arr2.push(tttArr);
                            }
                        }
                        //20190306 YIN DEL S
                        // html_01_str = "";
                        //20190306 YIN DEL E

                        for (var m = 0; m < arrTop.length; m++) {
                            mark = false;
                            for (var m1 = 0; m1 < arr2.length; m1++) {
                                if (
                                    arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                                    arr2[m1]["TEIKEI_CD"].substr(0, 2)
                                ) {
                                    mark = true;
                                    break;
                                }
                            }
                            if (mark) {
                                html_01_str +=
                                    '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                                html_01_str += arrTop[m]["ITEMNAME"];

                                html_01_str += "<ul>";
                                for (var m1 = 0; m1 < arr2.length; m1++) {
                                    if (
                                        arrTop[m]["TEIKEI_CD"].substr(0, 2) ==
                                        arr2[m1]["TEIKEI_CD"].substr(0, 2)
                                    ) {
                                        html_01_str +=
                                            '<li rel="' +
                                            arr2[m1]["TEIKEI_CD"] +
                                            '"style="width:100px;">';
                                        html_01_str += arr2[m1]["ITEMNAME"];
                                        html_01_str += "</li>";
                                    }
                                }
                                html_01_str += "</ul>";
                                html_01_str += "</li>";
                            } else {
                                html_01_str +=
                                    '<li rel="' + arrTop[m]["TEIKEI_CD"] + '">';
                                html_01_str += arrTop[m]["ITEMNAME"];
                                html_01_str += "</li>";
                            }
                        }
                    }

                    //--- 20160304 YIN INS S
                    //--- 20160304 li INS S
                    $(".sdh.sdh01.result_menu_07.mcdropdown_menu").remove();
                    $(".sdh.sdh01.result_07").html(me.sdh_sdh01_result_07);
                    //--- 20160304 li INS E
                    //--- 20160304 YIN INS E

                    $(".sdh.sdh01.result_menu_07").html(html_01_str);
                    $(".sdh.sdh01.result_select_07").mcDropdown(
                        ".sdh.sdh01.result_menu_07",
                        {
                            select: function (value, name) {
                                if (value.length != 4) {
                                    value = value + "00";
                                }
                                // var idx = $(id).data("idx");
                                $(".sdh.sdh01.result_text_0" + 7).val("");
                                $(".sdh.sdh01.result_text_0" + 7).data(
                                    "code",
                                    value
                                );
                                //HANTEILST_SINSYAの更新年月日がシステム日付より過去　AND コンボボックスの値が「入庫済」の場合は入力不可にする
                                if (
                                    name == "入庫済" &&
                                    (me.condition4 == "1" ||
                                        me.condition4 == "2") &&
                                    me.data["sdh01_hantei_naiyou"].length > 0
                                ) {
                                    ymdhm = $.trim(
                                        me.data["sdh01_hantei_naiyou"][
                                            me.data["sdh01_hantei_naiyou"]
                                                .length - 1
                                        ]["UPDYMDHM"]
                                    );
                                    ymdhm = ymdhm.substr(0, 8);
                                    ydate = $.trim(
                                        me.data["sdh01_hantei_naiyou"][
                                            me.data["sdh01_hantei_naiyou"]
                                                .length - 1
                                        ]["SYSYMDHM"]
                                    );
                                    ydate = ydate.substr(0, 8);
                                    if (ydate >= ymdhm) {
                                        $(".sdh.sdh01.result_text_07").attr(
                                            "disabled",
                                            "none"
                                        );
                                    } else {
                                        $(".sdh.sdh01.result_text_07").attr(
                                            "disabled",
                                            false
                                        );
                                    }
                                } else {
                                    $(".sdh.sdh01.result_text_07").attr(
                                        "disabled",
                                        false
                                    );
                                }
                            },
                        },
                        "sdh_mcDropdown" + 7
                    );
                    $("#sdh_mcDropdown" + 7).data("idx", 7);

                    $(".sdh.sdh01.result_text_0" + 7).empty();
                    $(".sdh.sdh01.result_text_0" + 7).width("95%");
                    //--- 20160127 li INS E
                    //20190227 YIN INS S
                    $(".sdh.sdh01.hantei_07").css("opacity", "1");
                    $(".sdh.sdh01.hantei_07").css(
                        "background-color",
                        "#BBCCEE"
                    );
                    //20190227 YIN INS E
                }
            }
        } catch (e) {
            console.log("error:build_hanteinengetu");
            console.log(e);
        }
    };

    /**
     * 対象年月リストを取得
     * @return {String} $option_list 対象年月リスト <option value =""></option>のリスト
     */
    me.build_nengetu_option_list = function (parameters) {
        try {
            var option = "";
            var option_list = "";
            var option_tmp = '<option value="{val}">{txt}</option>';

            var replaces = {
                val: "",
                txt: "",
            };

            var dt = $.exDate();
            dt = $.exDate(dt.toChar("yyyy/mm/01"));
            if (parameters && parameters["nengetu"]) {
                var nengetu = parameters["nengetu"];
                if (nengetu != "") {
                    nengetu = nengetu.replace("/", "");
                    //--20150820	Yuanjh	UPD  S.
                    //dt = $.exDate(nengetu.substr(0, 4) + "/" + nengetu.substr(4, 2) + "/01");
                    dt = $.exDate(
                        nengetu.substr(0, 4) -
                            1 +
                            "/" +
                            nengetu.substr(4, 2) +
                            "/01"
                    );
                    //--20150820	Yuanjh	UPD E.
                }
            }
            //--20150820	Yuanjh	UPD  S.
            // for (var i = 0; i < 7; i++) {
            // replaces.val = dt.addMonths(i).toChar("yyyymm");
            // replaces.txt = dt.addMonths(i).toChar("yyyy年mm月");
            // option = replace(option_tmp, replaces);
            // option_list += option;
            // };
            for (var i = 0; i < 25; i++) {
                replaces.val = dt.addMonths(i).toChar("yyyymm");
                replaces.txt = dt.addMonths(i).toChar("yyyy年mm月");
                option = replace(option_tmp, replaces);
                option_list += option;
            }
            // --20150820	Yuanjh	UPD E.

            // $(".sdh.sdh01.sel_nengetu").empty();
            $(".sdh.sdh01.sel_nengetu").html(option_list);

            if (parameters && parameters["nengetu"]) {
                var nengetu = parameters["nengetu"];
                if (nengetu != "") {
                    nengetu = nengetu.replace("/", "");
                    $(".sdh.sdh01.sel_nengetu").val(nengetu);
                }
            }
        } catch (e) {
            console.log("error:build_nengetu_option_list");
            console.log(e);
        }
    };

    me.check_change = function (func_yes, func_no) {
        if (is_changed() == true) {
            me.clsComFnc = new gdmz.common.clsComFnc();
            me.clsComFnc.MsgBoxBtnFnc.Yes = func_yes;
            me.clsComFnc.MsgBoxBtnFnc.No = func_no;
            me.clsComFnc.MessageBox(
                "入力変更があります。破棄して進めますか？",
                "SDH",
                "YesNo",
                "Question",
                me.clsComFnc.MessageBoxDefaultButton.Button2
            );
        } else {
            func_yes();
        }

        function is_changed() {
            for (var i = 1; i < 9; i++) {
                var sel = $(".sdh.sdh01.result_select_0" + i)
                    .val()
                    .trim();
                if (sel == "") {
                    sel = null;
                }

                var txt = $(".sdh.sdh01.result_text_0" + i)
                    .val()
                    .trim();
                if (txt == "") {
                    txt = null;
                }

                var code = $(".sdh.sdh01.result_text_0" + i).data("code");
                if (code == "") {
                    code = null;
                }

                // if (txt.trim() != "" || sel.trim() != "") {

                if (me.data["first_data"]) {
                    if (i == 8) {
                        if (txt != me.data["first_data"]["KEKKA"]) {
                            return true;
                        }

                        if (code != me.data["first_data"]["KEKKA_CD"]) {
                            return true;
                        }
                    } else if (i < 8) {
                        if (txt != me.data["first_data"]["HANTEI" + i]) {
                            return true;
                        }

                        if (
                            code != me.data["first_data"]["HANTEI" + i + "_CD"]
                        ) {
                            return true;
                        }
                    }
                }
                if (me.data["saved_data"]) {
                    if (i == 8) {
                        if (txt != me.data["saved_data"]["KEKKA"]) {
                            return true;
                        }

                        if (code != me.data["saved_data"]["KEKKA_CD"]) {
                            return true;
                        }
                    } else if (i < 8) {
                        if (txt != me.data["saved_data"]["HANTEI" + i]) {
                            return true;
                        }

                        if (
                            code != me.data["saved_data"]["HANTEI" + i + "_CD"]
                        ) {
                            return true;
                        }
                    }
                }
                // };
            }

            //-----20141016  #424  zhenghuiyun  upd  s
            // var memo1 = $(".sdh.sdh01.sdh01_07.MEMO.value").val();
            // var memo2 = "";
            // if (me.data["sdh01_memo"] && me.data["sdh01_memo"].length > 0)
            // {
            // memo2 = me.data["sdh01_memo"][0]["MEMO"];
            // if (memo2 == null)
            // {
            // memo2 = "";
            // };
            // };
            // if (memo1.trim() != memo2.trim())
            // {
            // return true;
            // };
            var memo = $(".sdh.sdh01.sdh01_07.MEMO.value").val();
            memo = memo.trim();
            if (me.data["first_data"]) {
                if (memo != me.data["first_data"]["MEMO"]) {
                    return true;
                }
            }
            if (me.data["saved_data"]) {
                if (memo != me.data["saved_data"]["MEMO"]) {
                    return true;
                }
            }
            //-----20141016  #424  zhenghuiyun  upd  e

            return false;
        }
    };

    function replace(option_tmp, replaces) {
        try {
            for (key in replaces) {
                option_tmp = option_tmp.replace("{" + key + "}", replaces[key]);
            }
            return option_tmp;
        } catch (e) {
            console.log("replace");
            console.log(e);
        }
    }

    me.resize_all = function () {
        var w = $(".sdh.sdh01.all").width();
        $(".sdh.sdh01.content.center").width(w - 20);

        var h = $(".sdh.sdh01.all").height();
        var h1 = $(".sdh.sdh01.title").height();

        $(".sdh.sdh01.content.center").height(h - h1);
        $(".sdh.sdh01.content.left.panel").height(h - h1);
    };

    me.hidelist = function () {
        try {
            var w = 0 - me.hantei_list_w;
            $(".sdh.sdh01.content.left.panel").animate({
                left: w,
            });
            $(".sdh.sdh01.listshow").removeClass("ui-icon-circle-triangle-w");
            $(".sdh.sdh01.listshow").addClass("ui-icon-circle-triangle-e");
        } catch (e) {
            console.log("hidelist");
            console.log(e);
        }
    };

    me.showlist = function () {
        try {
            $(".sdh.sdh01.content.left.panel").animate({
                left: 0,
            });
            $(".sdh.sdh01.listshow").removeClass("ui-icon-circle-triangle-e");
            $(".sdh.sdh01.listshow").addClass("ui-icon-circle-triangle-w");
        } catch (e) {
            console.log("showlist");
            console.log(e);
        }
    };

    me.hidehelp = function () {
        try {
        } catch (e) {
            console.log("hidehelp");
            console.log(e);
        }
    };

    me.showhelp = function () {
        try {
        } catch (e) {
            console.log("showhelp");
            console.log(e);
        }
    };

    /**
     * 最終結果のcheck
     */
    me.btn_save_check = function (strName, strVale, intLen, objFocus) {
        var checkBoll = true;

        if (strVale.length > intLen) {
            objFocus.focus();
            me.clsComFnc = new gdmz.common.clsComFnc();
            //me.clsComFnc.MessageBox("項目「 " + strName + "」の長さは「" + intLen + "」以下を入力ください、<br> ご確認お願いします", "SDH", "OK", "Warning", MessageBox.MessageBoxIcon.Warning);
            me.clsComFnc.MessageBox(
                "項目「" +
                    strName +
                    "」が長すぎます。<br> (最大:" +
                    intLen +
                    "バイト、現在:" +
                    strVale.length +
                    "バイト)",
                "SDH",
                "OK",
                "Warning",
                MessageBox.MessageBoxIcon.Warning
            );
            checkBoll = false;
        }
        return checkBoll;
    };

    //---20141023 #954 fanzhengzhou ins s.
    /**
     * 保存完了しました。OKボタンを押す
     */
    me.func_button_ok = function () {
        me.get_hantei_item_data(me.cur_hantei_item.data());
    };
    //---20141023 #954 fanzhengzhou ins e.

    // me.getMenu = function() {
    // console.log('--------------fuxiaolin 20150330-------------------------');
    // var o_ajax = new gdmz.common.ajax();
    // o_ajax.receive = receive;
    // var url = me.sys_id + "/" + me.id + "/" + "getMenu";
    //
    // o_ajax.send(url, "", 0);
    //
    // function receive(result) {
    // console.log(result);
    //
    // //result = JSON.parse(result);
    //
    // $("#sdh_mcDropdown3").html(result);
    //
    // me.get_data();
    //
    // }
    //
    // };

    // ==========
    // = メソッド end =
    // ==========

    fromMe = me;

    return me;
};

$(function () {
    var o_SDH_SDH01 = new gdmz.SDH.SDH01();
    o_SDH_SDH01.load();

    o_HMSS_Master.SDH = o_SDH_SDH01;
    o_SDH_SDH01.HMSS = o_HMSS_Master;
});
