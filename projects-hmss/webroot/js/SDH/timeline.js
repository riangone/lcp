/**
 * 説明：
 *
 *
 * @author fanzhengzhou
 * @copyright (GD) (ZM)
 * @package default
 *
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                       Feature/Bug                    内容                          担当
 * YYYYMMDD           #ID                               XXXXXX                   FCSDL
 * 20141015          #383,#385                                                   fanzhengzhou
 * 20141202          NO.29   初度登録から１０年以上経過している場合は当日線はひかなくていい          fanzhengzhou
 * 20141202          NO.55                                                       fanzhengzhou
 * 20171124            #2807                       IE线显示问题                   liqiushuang
 * 20201117            bug                       グラフHeightはChromeと違っています。                   cyc
 * --------------------------------------------------------------------------------------------
 */

Namespace.register("gdmz.sdh01.timeline");

gdmz.sdh01.timeline = function () {
    var me = new Object();

    // ==========
    // = 宣言 start =
    // ==========

    // ========== 変数 start ==========
    //me.startdate = "";
    me.FRGMH = "";
    //----20141212 fanzhengzhou ins s.
    me.TimeLinedataset0 = "";
    //----20141212 fanzhengzhou ins e.
    me.TimeLinedataset1 = "";
    me.TimeLinedataset2 = "";
    me.currentMonth = "";
    me.ClassOfDiv = "";
    me.flag = "";
    me.tempflag = true;
    //入庫歴 点数
    me.count = 0;
    //-----20141202 NO.29 fanzhengzhou  ins  s
    me.CurrentMonthDraw = true;
    //-----20141202 NO.29 fanzhengzhou  ins  e

    //---最大が228か月を超える場合、月数の描画を１年単位にしてほしい
    me.morethan228 = false;

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

    //posturl:查询数据的url
    //ClassOfDiv:timeline的div的class
    me.draw_timeline = function (data, ClassOfDiv, selDate) {
        try {
            //*************颜色设定****************
            //current month前的颜色
            var color = "rgb(178,178,178)";
            //パックｄｅメンテ  current month后的颜色
            var color1 = "rgb(9,255,154)";
            //延長保証  current month后的颜色
            var color2 = "rgb(9,255,154)";
            //ボディコーティング  current month后的颜色
            var color3 = "rgb(9,255,154)";
            //クレジット  current month后的颜色
            var color4 = "rgb(255,170,212)";
            //保険  current month后的颜色
            var color5 = "rgb(255,212,170)";
            //*************颜色设定****************
            //-----20141202 NO.29 fanzhengzhou  ins  s
            me.CurrentMonthDraw = true;
            //-----20141202 NO.29 fanzhengzhou  ins  e

            me.morethan228 = false;

            if (data) {
                //---20150413 fanzhengzhou upd s.
                // //入庫歴
                // var data1 = data[0][0];
                // //定期点検
                // var data2 = data[1];
                //入庫歴
                var data1 = data[1];
                //---20150413 fanzhengzhou upd e.

                //リコール
                var data3 = data[2];
                //パックｄｅメンテ,延長保証,ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ
                var data4 = data[3];
                //クレジット
                var data5 = [];
                if (data[4].length != 0) {
                    data5 = data[4][0];
                }
                //保険
                var data6 = data[5];

                //----20141209 NO.55 fanzhengzhou ins s.
                //var stadate = data['VSLSTTDT'][0]['VSLSTTDT'];
                //初度登録年月
                var FRGMH = data["FRGMH"][0]["FRGMH"];
                me.FRGMH = FRGMH;
                //----20141209 NO.55 fanzhengzhou ins e.

                //最終日：各サービスの終了日、クレジットカード支払い終了日、任意保険終了日、選択年月＋2年の最大値
                var tempEndDate = 0;
                for (key in data4) {
                    if (tempEndDate < parseInt(data4[key]["KYK_EXR_DT"])) {
                        tempEndDate = data4[key]["KYK_EXR_DT"];
                    }
                }
                if (tempEndDate < parseInt(data5["KRJ_SHR_KKN_TO"])) {
                    tempEndDate = data5["KRJ_SHR_KKN_TO"];
                }
                if (tempEndDate < parseInt(data6["HOKENSYUKI"])) {
                    tempEndDate = data6["HOKENSYUKI"];
                }
                selDate =
                    (parseInt(selDate.substr(0, 4)) + 2).toString() +
                    selDate.substr(4, 2) +
                    "01";
                if (tempEndDate < parseInt(selDate)) {
                    //20160308 Upd S
                    tempEndDate = selDate;
                    //20160308 Upd E
                }

                var now = new Date();
                var yyyymmdd =
                    now.getFullYear() +
                    ("0" + (now.getMonth() + 1)).slice(-2) +
                    ("0" + now.getDate()).slice(-2);
                if (tempEndDate < parseInt(yyyymmdd)) {
                    tempEndDate = yyyymmdd;
                }

                //----20141209 NO.55 fanzhengzhou upd s.
                //var startEndDif = me.MonthDifVal(stadate, tempEndDate);
                var startEndDif = me.MonthDifVal(FRGMH, tempEndDate);
                //----20141209 NO.55 fanzhengzhou upd e.
                if (startEndDif < 60) {
                    var flag = 12;
                    //me.startdate = data['VSLSTTDT'][0]['VSLSTTDT'];
                } else if (startEndDif <= 132) {
                    var flag = 24;
                    //me.startdate = data['VSLSTTDT'][0]['VSLSTTDT'];
                } else if (startEndDif <= 228) {
                    //---20150413 fanzhengzhou upd s.
                    var flag = 24 + Math.ceil((startEndDif - 132) / 6);
                    //---20150413 fanzhengzhou upd e.
                } else {
                    //---20150504 fanzhengzhou add s.#1833
                    me.morethan228 = true;
                    var flag = 20 + Math.ceil((startEndDif - 228) / 12);
                    //---20150504 fanzhengzhou add e.#1833
                }

                var today = new Date();
                var CurYear = today.getFullYear();
                var CurMonth = today.getMonth() + 1;
                if (CurMonth < 10) {
                    CurMonth = "0" + CurMonth.toString();
                } else {
                    CurMonth = CurMonth.toString();
                }
                var CurYM = CurYear.toString() + CurMonth;
                //----20141209 NO.55 fanzhengzhou ins s.
                //var CurrentMonth = me.MonthDifVal(me.startdate.substr(0, 6), CurYM);
                var CurrentMonth = me.MonthDifVal(FRGMH, CurYM);
                //----20141209 NO.55 fanzhengzhou ins e.
                //----20141212 fanzhengzhou ins s.
                var dataset0 = [];
                //----20141212 fanzhengzhou ins e.
                var dataset = [];
                var dataset1 = [];
                //----20141211 fanzhengzhou del s.
                // var label1 = "申込開始日:";
                // var label2 = "契約満了日:";
                // var label3 = "支払期間開始:";
                // var label4 = "支払期間終了:";
                // var label5 = "始期:";
                //var label6 = "終期:";
                //----20141211 fanzhengzhou del e.
                // //入庫歴
                // for (key in data1) {
                // if (data1[key] == "O") {
                // //----20141212 fanzhengzhou ins s.
                // //dataset.push([parseInt(key.substr(12)), 8]);
                // dataset0.push([parseInt(key.substr(12)), 8]);
                // //----20141212 fanzhengzhou ins e.
                // me.count++;
                // }
                // }
                // //定期点検
                // for (key in data2) {
                // //----20141210 NO.55 fanzhengzhou upd s.
                // //var MonthDifData2 = me.MonthDifVal(me.startdate.substr(0, 6), data2[key]['NKO_DT'].substr(0, 6));
                // var MonthDifData2 = me.MonthDifVal(FRGMH, data2[key]['NKO_DT'].substr(0, 6));
                // //----20141210 NO.55 fanzhengzhou upd e.
                // if (MonthDifData2 >= 0 && MonthDifData2 != "" && MonthDifData2 <= 132) {
                // dataset.push([MonthDifData2, 7, data2[key]['NKO_DT'].substr(0, 4) + "/" + data2[key]['NKO_DT'].substr(4, 2)]);
                // }
                // }
                //入庫歴
                for (key in data1) {
                    var MonthDifData2 = me.MonthDifVal(
                        FRGMH,
                        data1[key]["URG_DT"].substr(0, 6)
                    );
                    if (MonthDifData2 >= 0 && MonthDifData2 != "") {
                        //---20150504 fanzhengzhou upd s.#1833-2
                        //dataset0.push([MonthDifData2, 7, data1[key]['URG_DT'].substr(0, 4) + "/" + data1[key]['URG_DT'].substr(4, 2), data1[key]['NYUKOKBN']]);
                        dataset0.push([
                            MonthDifData2,
                            7,
                            data1[key]["URG_DT"].substr(0, 4) +
                                "/" +
                                data1[key]["URG_DT"].substr(4, 2) +
                                "　" +
                                data1[key]["NYUKOKBNMEI"],
                            data1[key]["NYUKOKBN"],
                        ]);
                        //---20150504 fanzhengzhou upd e.#1833-2
                    }
                }

                // var tempdata = me.MonthDifVal(FRGMH, CurYM);
                //リコール
                for (key in data3) {
                    //----20141210 NO.55 fanzhengzhou upd s.
                    //var MonthDifData3 = me.MonthDifVal(me.startdate.substr(0, 6), data3[key]['NKO_DT'].substr(0, 6));
                    var MonthDifData3 = me.MonthDifVal(
                        FRGMH,
                        data3[key]["NKO_DT"].substr(0, 6)
                    );
                    //----20141210 NO.55 fanzhengzhou upd e.
                    if (
                        MonthDifData3 >= 0 &&
                        MonthDifData3 != "" &&
                        MonthDifData3 <= 132
                    ) {
                        dataset.push([
                            MonthDifData3,
                            6,
                            data3[key]["NKO_DT"].substr(0, 4) +
                                "/" +
                                data3[key]["NKO_DT"].substr(4, 2),
                        ]);
                    }
                }
                //パックｄｅメンテ,延長保証,ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ
                //2014/10/10  fan update start.
                //パックｄｅメンテ   存储最大终了日的记录的栈
                var tempStack1 = [];
                //延長保証   存储最大终了日的记录的栈
                var tempStack2 = [];
                //ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ 存储最大终了日的记录的栈
                var tempStack3 = [];
                //获取最大终了日的记录
                for (key in data4) {
                    //-----20141015  #383,#385  fanzhengzhou  upd  s
                    //if (data4[key]['SOH_NM'].search(/パックdeメンテ/) != -1 || data4[key]['SOH_NM'].search(/パックDEメンテ/) != -1)
                    if (
                        data4[key]["SOH_NM"].search(/パックdeメンテ/) != -1 ||
                        data4[key]["SOH_NM"].search(/パックDEメンテ/) != -1 ||
                        data4[key]["SOH_NM"].search(/パックｄｅ７５３/) != -1
                    ) {
                        //-----20141015  #383,#385  fanzhengzhou  upd  e
                        if (tempStack1.length == 0) {
                            tempStack1.push(data4[key]);
                        } else {
                            if (
                                tempStack1[0]["KYK_EXR_DT"] <
                                data4[key]["KYK_EXR_DT"]
                            ) {
                                tempStack1.pop();
                                tempStack1.push(data4[key]);
                            }
                        }
                    }
                    if (data4[key]["SOH_NM"].search(/延長保証/) != -1) {
                        if (tempStack2.length == 0) {
                            tempStack2.push(data4[key]);
                        } else {
                            if (
                                tempStack2[0]["KYK_EXR_DT"] <
                                data4[key]["KYK_EXR_DT"]
                            ) {
                                tempStack2.pop();
                                tempStack2.push(data4[key]);
                            }
                        }
                    }
                    if (
                        data4[key]["SOH_NM"].search(/ボディコーティング/) != -1
                    ) {
                        if (tempStack3.length == 0) {
                            tempStack3.push(data4[key]);
                        } else {
                            if (
                                tempStack3[0]["KYK_EXR_DT"] <
                                data4[key]["KYK_EXR_DT"]
                            ) {
                                tempStack3.pop();
                                tempStack3.push(data4[key]);
                            }
                        }
                    }
                }

                //パックｄｅメンテ
                if (
                    tempStack1.length != 0 &&
                    $.trim(tempStack1[0]["SSC_STA_DT"]) != "" &&
                    $.trim(tempStack1[0]["KYK_EXR_DT"]) != "" &&
                    $.trim(FRGMH) != ""
                ) {
                    var KYK_EXR_DT1 = tempStack1[0]["KYK_EXR_DT"];
                    //---20150413 fanzhengzhou del s.
                    // //終期が１１年より後になる場合ですね。初度登録年月は変更しなくていいです。線の終端を132か月目の位置にしてもらえればよいです。
                    // if (me.MonthDifVal(FRGMH, tempStack1[0]['KYK_EXR_DT']) > 132) {
                    // KYK_EXR_DT1 = (parseInt(FRGMH.substr(0, 4)) + 11).toString() + FRGMH.substr(4, 2);
                    // }
                    //---20150413 fanzhengzhou del e.
                    if (KYK_EXR_DT1 > CurYM) {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 5, me.tempColNum(me.startdate.substr(0, 6), CurYM), color, "", tempdata, label1 + me.FormatDate(tempStack1[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack1[0]['KYK_EXR_DT']), me.FormatDate(tempStack1[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack1[0]["SSC_STA_DT"]),
                            5,
                            me.tempColNum(tempStack1[0]["SSC_STA_DT"], CurYM),
                            color,
                            "",
                            me.FormatDate(tempStack1[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        if (tempStack1[0]["SSC_STA_DT"] < CurYM) {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, CurYM),
                                5,
                                me.tempColNum(CurYM, KYK_EXR_DT1),
                                color1,
                                tempStack1[0]["SOH_NM"],
                                me.FormatDate(tempStack1[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        } else {
                            dataset1.push([
                                me.MonthDifVal(
                                    FRGMH,
                                    tempStack1[0]["SSC_STA_DT"]
                                ),
                                5,
                                me.tempColNum(
                                    tempStack1[0]["SSC_STA_DT"],
                                    KYK_EXR_DT1
                                ),
                                color1,
                                tempStack1[0]["SOH_NM"],
                                me.FormatDate(tempStack1[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        }
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    } else {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 5, me.tempColNum(me.startdate.substr(0, 6), tempStack1[0]['KYK_EXR_DT'].substr(0, 6)), color, tempStack1[0]['SOH_NM'], tempdata, label1 + me.FormatDate(tempStack1[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack1[0]['KYK_EXR_DT']), me.FormatDate(tempStack1[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack1[0]["SSC_STA_DT"]),
                            5,
                            me.tempColNum(
                                tempStack1[0]["SSC_STA_DT"],
                                KYK_EXR_DT1
                            ),
                            color,
                            tempStack1[0]["SOH_NM"],
                            me.FormatDate(tempStack1[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    }
                }

                //延長保証
                if (
                    tempStack2.length != 0 &&
                    $.trim(tempStack2[0]["SSC_STA_DT"]) != "" &&
                    $.trim(tempStack2[0]["KYK_EXR_DT"]) != "" &&
                    $.trim(FRGMH) != ""
                ) {
                    var KYK_EXR_DT2 = tempStack2[0]["KYK_EXR_DT"];
                    //---20150413 fanzhengzhou del s.
                    // //終期が１１年より後になる場合ですね。初度登録年月は変更しなくていいです。線の終端を132か月目の位置にしてもらえればよいです。
                    // if (me.MonthDifVal(FRGMH, tempStack2[0]['KYK_EXR_DT']) > 132) {
                    // KYK_EXR_DT2 = (parseInt(FRGMH.substr(0, 4)) + 11).toString() + FRGMH.substr(4, 2);
                    // }
                    //---20150413 fanzhengzhou del e.
                    if (KYK_EXR_DT2 > CurYM) {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 4, me.tempColNum(me.startdate.substr(0, 6), CurYM), color, "", tempdata, label1 + me.FormatDate(tempStack2[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack2[0]['KYK_EXR_DT']), me.FormatDate(tempStack2[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack2[0]["SSC_STA_DT"]),
                            4,
                            me.tempColNum(tempStack2[0]["SSC_STA_DT"], CurYM),
                            color,
                            "",
                            me.FormatDate(tempStack2[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        if (tempStack2[0]["SSC_STA_DT"] < CurYM) {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, CurYM),
                                4,
                                me.tempColNum(CurYM, KYK_EXR_DT2),
                                color2,
                                tempStack2[0]["SOH_NM"],
                                me.FormatDate(tempStack2[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        } else {
                            dataset1.push([
                                me.MonthDifVal(
                                    FRGMH,
                                    tempStack2[0]["SSC_STA_DT"]
                                ),
                                4,
                                me.tempColNum(
                                    tempStack2[0]["SSC_STA_DT"],
                                    KYK_EXR_DT2
                                ),
                                color2,
                                tempStack2[0]["SOH_NM"],
                                me.FormatDate(tempStack2[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        }
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    } else {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 4, me.tempColNum(me.startdate.substr(0, 6), tempStack2[0]['KYK_EXR_DT'].substr(0, 6)), color, tempStack2[0]['SOH_NM'], tempdata, label1 + me.FormatDate(tempStack2[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack2[0]['KYK_EXR_DT']), me.FormatDate(tempStack2[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd e.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack2[0]["SSC_STA_DT"]),
                            4,
                            me.tempColNum(
                                tempStack2[0]["SSC_STA_DT"],
                                KYK_EXR_DT2
                            ),
                            color,
                            tempStack2[0]["SOH_NM"],
                            me.FormatDate(tempStack2[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    }
                }
                //ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ
                if (
                    tempStack3.length != 0 &&
                    $.trim(tempStack3[0]["SSC_STA_DT"]) != "" &&
                    $.trim(tempStack3[0]["KYK_EXR_DT"]) != "" &&
                    $.trim(FRGMH) != ""
                ) {
                    var KYK_EXR_DT3 = tempStack3[0]["KYK_EXR_DT"];
                    //---20150413 fanzhengzhou del s.
                    // //終期が１１年より後になる場合ですね。初度登録年月は変更しなくていいです。線の終端を132か月目の位置にしてもらえればよいです。
                    // if (me.MonthDifVal(FRGMH, tempStack3[0]['KYK_EXR_DT']) > 132) {
                    // KYK_EXR_DT3 = (parseInt(FRGMH.substr(0, 4)) + 11).toString() + FRGMH.substr(4, 2);
                    // }
                    //---20150413 fanzhengzhou del e.
                    if (KYK_EXR_DT3 > CurYM) {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 3, me.tempColNum(me.startdate.substr(0, 6), CurYM), color, "", tempdata, label1 + me.FormatDate(tempStack3[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack3[0]['KYK_EXR_DT']), me.FormatDate(tempStack3[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack3[0]["SSC_STA_DT"]),
                            3,
                            me.tempColNum(tempStack3[0]["SSC_STA_DT"], CurYM),
                            color,
                            "",
                            me.FormatDate(tempStack3[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        if (tempStack3[0]["SSC_STA_DT"] < CurYM) {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, CurYM),
                                3,
                                me.tempColNum(CurYM, KYK_EXR_DT3),
                                color3,
                                tempStack3[0]["SOH_NM"],
                                me.FormatDate(tempStack3[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        } else {
                            dataset1.push([
                                me.MonthDifVal(
                                    FRGMH,
                                    tempStack3[0]["SSC_STA_DT"]
                                ),
                                3,
                                me.tempColNum(
                                    tempStack3[0]["SSC_STA_DT"],
                                    KYK_EXR_DT3
                                ),
                                color3,
                                tempStack3[0]["SOH_NM"],
                                me.FormatDate(tempStack3[0]["KYK_EXR_DT"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        }
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    } else {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 3, me.tempColNum(me.startdate.substr(0, 6), tempStack3[0]['KYK_EXR_DT'].substr(0, 6)), color, tempStack3[0]['SOH_NM'], tempdata, label1 + me.FormatDate(tempStack3[0]['SSC_STA_DT']), label2 + me.FormatDate(tempStack3[0]['KYK_EXR_DT']), me.FormatDate(tempStack3[0]['KYK_EXR_DT'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, tempStack3[0]["SSC_STA_DT"]),
                            3,
                            me.tempColNum(
                                tempStack3[0]["SSC_STA_DT"],
                                KYK_EXR_DT3
                            ),
                            color,
                            tempStack3[0]["SOH_NM"],
                            me.FormatDate(tempStack3[0]["KYK_EXR_DT"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    }
                }
                //2014/10/10  fan update end.
                //クレジット
                if (
                    data5.length != 0 &&
                    $.trim(data5["KRJ_SHR_KKN_TO"]) != "" &&
                    $.trim(["KRJ_SHR_KKN_FRO"]) != "" &&
                    $.trim(FRGMH) != ""
                ) {
                    var KRJ_SHR_KKN_TO = data5["KRJ_SHR_KKN_TO"];
                    //---20150413 fanzhengzhou del s.
                    // //終期が１１年より後になる場合ですね。初度登録年月は変更しなくていいです。線の終端を132か月目の位置にしてもらえればよいです。
                    // if (me.MonthDifVal(FRGMH, data5['KRJ_SHR_KKN_TO']) > 132) {
                    // KRJ_SHR_KKN_TO = (parseInt(FRGMH.substr(0, 4)) + 11).toString() + FRGMH.substr(4, 2);
                    // }
                    //---20150413 fanzhengzhou del e.
                    if (KRJ_SHR_KKN_TO > CurYM) {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 2, me.tempColNum(me.startdate.substr(0, 6), CurYM), color, "", tempdata, label3 + me.FormatDate(data5['KRJ_SHR_KKN_FRO']), label4 + me.FormatDate(data5['KRJ_SHR_KKN_TO']), me.FormatDate(data5['KRJ_SHR_KKN_TO'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, data5["KRJ_SHR_KKN_FRO"]),
                            2,
                            me.tempColNum(data5["KRJ_SHR_KKN_FRO"], CurYM),
                            color,
                            "",
                            me.FormatDate(data5["KRJ_SHR_KKN_TO"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        if (data5["KRJ_SHR_KKN_FRO"] < CurYM) {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, CurYM),
                                2,
                                me.tempColNum(CurYM, KRJ_SHR_KKN_TO),
                                color4,
                                data5["SCD_NM"],
                                me.FormatDate(data5["KRJ_SHR_KKN_TO"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        } else {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, data5["KRJ_SHR_KKN_FRO"]),
                                2,
                                me.tempColNum(
                                    data5["KRJ_SHR_KKN_FRO"],
                                    KRJ_SHR_KKN_TO
                                ),
                                color4,
                                data5["SCD_NM"],
                                me.FormatDate(data5["KRJ_SHR_KKN_TO"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        }
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    } else {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 2, me.tempColNum(me.startdate.substr(0, 6), data5['KRJ_SHR_KKN_TO'].substr(0, 6)), color, data5['SCD_NM'], tempdata, label3 + me.FormatDate(data5['KRJ_SHR_KKN_FRO']), label4 + me.FormatDate(data5['KRJ_SHR_KKN_TO']), me.FormatDate(data5['KRJ_SHR_KKN_TO'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, data5["KRJ_SHR_KKN_FRO"]),
                            2,
                            me.tempColNum(
                                data5["KRJ_SHR_KKN_FRO"],
                                KRJ_SHR_KKN_TO
                            ),
                            color,
                            data5["SCD_NM"],
                            me.FormatDate(data5["KRJ_SHR_KKN_TO"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    }
                }

                //保険
                if (
                    $.trim(data6["HOKENSYUKI"]) != "" &&
                    $.trim(data6["HOKENSIKI"]) != "" &&
                    $.trim(FRGMH) != ""
                ) {
                    var HOKENSYUKI = data6["HOKENSYUKI"];
                    //---20150413 fanzhengzhou del s.
                    // //終期が１１年より後になる場合ですね。初度登録年月は変更しなくていいです。線の終端を132か月目の位置にしてもらえればよいです。
                    // if (me.MonthDifVal(FRGMH, data6['HOKENSYUKI']) > 132) {
                    // HOKENSYUKI = (parseInt(FRGMH.substr(0, 4)) + 11).toString() + FRGMH.substr(4, 2);
                    // }
                    //---20150413 fanzhengzhou del e.
                    //if (HOKENSYUKI > CurYM) {
                    // 20160309 Upd S
                    if (HOKENSYUKI > CurYM + "01") {
                        // 20160309 Upd E
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 1, me.tempColNum(me.startdate.substr(0, 6), CurYM), color, "", tempdata, label5 + me.FormatDate(data6['HOKENSIKI']), label6 + me.FormatDate(data6['HOKENSYUKI']), me.FormatDate(data6['HOKENSYUKI'])]);
                        //---20150414 fanzhengzhou upd s.
                        dataset1.push([
                            me.MonthDifVal(FRGMH, data6["HOKENSIKI"]),
                            1,
                            me.tempColNum(data6["HOKENSIKI"], CurYM),
                            color,
                            "",
                            me.FormatDate(data6["HOKENSYUKI"]),
                            me.MonthDifVal(FRGMH, CurYM),
                        ]);
                        if (data6["HOKENSIKI"] < CurYM) {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, CurYM),
                                1,
                                me.tempColNum(CurYM, HOKENSYUKI),
                                color5,
                                data6["SONPO_NM"],
                                me.FormatDate(data6["HOKENSYUKI"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        } else {
                            dataset1.push([
                                me.MonthDifVal(FRGMH, data6["HOKENSIKI"]),
                                1,
                                me.tempColNum(data6["HOKENSIKI"], HOKENSYUKI),
                                color5,
                                data6["SONPO_NM"],
                                me.FormatDate(data6["HOKENSYUKI"]),
                                me.MonthDifVal(FRGMH, CurYM),
                            ]);
                        }
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    } else {
                        //----20141209 NO.55 fanzhengzhou upd s.
                        //dataset1.push([0, 1, me.tempColNum(me.startdate.substr(0, 6), data6['HOKENSYUKI'].substr(0, 6)), color, data6['SONPO_NM'], tempdata, label5 + me.FormatDate(data6['HOKENSIKI']), label6 + me.FormatDate(data6['HOKENSYUKI']), me.FormatDate(data6['HOKENSYUKI'])]);
                        //---20150414 fanzhengzhou upd s.
                        // 20160309 Upd S
                        //dataset1.push([me.MonthDifVal(FRGMH, data6['HOKENSIKI']), 1, me.tempColNum(data6['HOKENSIKI'], HOKENSYUKI), color, data6['SONPO_NM'], me.FormatDate(data6['HOKENSYUKI']), me.MonthDifVal(FRGMH, CurYM)]);
                        dataset1.push([
                            me.MonthDifVal(FRGMH, data6["HOKENSIKI"]),
                            1,
                            me.tempColNum(data6["HOKENSIKI"], HOKENSYUKI),
                            color,
                            data6["SONPO_NM"],
                            me.FormatDate(data6["HOKENSYUKI"]),
                            me.MonthDifVal(FRGMH, data6["HOKENSIKI"]),
                        ]);
                        // 20160309 Upd E
                        //---20150414 fanzhengzhou upd e.
                        //----20141209 NO.55 fanzhengzhou upd e.
                    }
                }
                //----20141212 fanzhengzhou ins s.
                me.TimeLinedataset0 = dataset0;
                //----20141212 fanzhengzhou ins e.
                me.TimeLinedataset1 = dataset;
                me.TimeLinedataset2 = dataset1;
                me.currentMonth = CurrentMonth;
                me.ClassOfDiv = ClassOfDiv;
                me.flag = flag;
                //----20141212 fanzhengzhou upd s.
                //me.draw(dataset, dataset1, CurrentMonth, ClassOfDiv, flag);
                me.draw(
                    dataset0,
                    dataset,
                    dataset1,
                    CurrentMonth,
                    ClassOfDiv,
                    flag
                );
                //----20141212 fanzhengzhou upd e.
                //添加Timeline<div>大小改变的监听
                $(ClassOfDiv).exResize(function () {
                    if (me.tempflag) {
                        me.draw(
                            me.TimeLinedataset0,
                            me.TimeLinedataset1,
                            me.TimeLinedataset2,
                            me.currentMonth,
                            me.ClassOfDiv,
                            me.flag
                        );
                    }
                });
            }
        } catch (e) {
            console.log("error:draw_timeline");
            console.log(e);
        }
    };

    //flag=12 :描画0-60月(0-60坐标轴分12段) ; flag=24:描画0-132月（0-132坐标轴分24段）.
    me.draw = function (
        postdata0,
        postdata1,
        postdata2,
        CurrentMonth,
        ClassOfDiv,
        flag
    ) {
        try {
            $(ClassOfDiv).empty();
            var w = $(ClassOfDiv).width();
            var h = $(ClassOfDiv).height() * 0.99;
            //var h = 250;
            var lineheight = h;
            //20180416 lqs INS S
            var explorer = window.navigator.userAgent;
            if (explorer.indexOf("Firefox") >= 0) {
                //20201117 CI UPD S
                //lineheight = 220;
                if (
                    navigator.userAgent.toUpperCase().indexOf("FIREFOX") == -1
                ) {
                    lineheight = 220;
                }
                //20201117 CI UPD E
            }
            if (explorer.indexOf("Firefox") >= 0) {
                //20201117 CI UPD S
                //h=260;
                if (
                    navigator.userAgent.toUpperCase().indexOf("FIREFOX") == -1
                ) {
                    h = 260;
                }
                //20201117 CI UPD E
            }
            //20180416 lqs INS E
            var padding = 80;

            var marginleft = 40;
            //the width of cell.
            var cell = (w - 2 * padding - marginleft) / flag;
            //参照线
            //---20150504 fanzhengzhou upd s.#1833
            if (me.morethan228 == false) {
                if (flag == 12) {
                    var dataset1 = [
                        0, 1, 3, 6, 12, 18, 24, 30, 36, 42, 48, 54, 60,
                    ];
                } else if (flag == 24) {
                    var dataset1 = [
                        0, 1, 3, 6, 12, 18, 24, 30, 36, 42, 48, 54, 60, 66, 72,
                        78, 84, 90, 96, 102, 108, 114, 120, 126, 132,
                    ];
                } else {
                    var dataset1 = [
                        0, 1, 3, 6, 12, 18, 24, 30, 36, 42, 48, 54, 60, 66, 72,
                        78, 84, 90, 96, 102, 108, 114, 120, 126, 132,
                    ];
                    for (var i = 1; i <= flag - 24; i++) {
                        dataset1.push(132 + i * 6);
                    }
                }
            } else {
                var dataset1 = [
                    0, 1, 12, 24, 36, 48, 60, 72, 84, 96, 108, 120, 132, 144,
                    156, 168, 180, 192, 204, 216, 228,
                ];
                for (var i = 1; i <= flag - 20; i++) {
                    dataset1.push(228 + i * 12);
                }
            }
            //---20150504 fanzhengzhou upd e.#1833

            //label.
            //---20150413 fanzhengzhou upd s.
            //var dataset2 = [["入庫歴", 8], ["定期点検", 7], ["リコール", 6], ["パックDEメンテ", 5], ["延長保証", 4], ["ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ", 3], ["クレジット", 2], ["保険", 1]];
            var dataset2 = [
                ["入庫歴", 7],
                ["リコール", 6],
                ["パックDEメンテ", 5],
                ["延長保証", 4],
                ["ﾎﾞﾃﾞｨｺｰﾃｨﾝｸﾞ", 3],
                ["クレジット", 2],
                ["保険", 1],
            ];
            //---20150413 fanzhengzhou upd e.
            //単位:月 label
            var dataset3 = ["単位:月"];
            //the info of Rectangular.
            var dataset4 = postdata2;
            //----20141212 fanzhengzhou ins s.
            //the info of "■". 入庫歴
            var dataset0 = postdata0;
            //----20141212 fanzhengzhou ins e.
            //the info of "■". 定期点検,リコール
            var dataset = postdata1;
            //the current month.
            var dataset5 = [];
            dataset5.push(CurrentMonth);
            //■ 車検 label
            var dataset6 = ["■ 車検"];
            //■ 車検以外 label
            var dataset7 = ["■ 車検以外"];

            // **************************************************************
            // Create scale functions
            // **************************************************************
            //---20150504 fanzhengzhou upd s.#1833
            if (me.morethan228 == false) {
                var xScale = d3.scale
                    .linear()
                    .domain([0, 1])
                    .range([marginleft + padding, marginleft + padding + cell]);
                var xScale1 = d3.scale
                    .linear()
                    .domain([1, 3])
                    .range([
                        marginleft + padding + cell,
                        marginleft + padding + 2 * cell,
                    ]);
                var xScale2 = d3.scale
                    .linear()
                    .domain([3, 6])
                    .range([
                        marginleft + padding + 2 * cell,
                        marginleft + padding + 3 * cell,
                    ]);
                if (flag == 12) {
                    var xScale3 = d3.scale
                        .linear()
                        .domain([6, 60])
                        .range([marginleft + padding + 3 * cell, w - padding]);
                } else if (flag == 24) {
                    var xScale3 = d3.scale
                        .linear()
                        .domain([6, 132])
                        .range([marginleft + padding + 3 * cell, w - padding]);
                } else {
                    var xScale3 = d3.scale
                        .linear()
                        .domain([6, 132 + (flag - 24) * 6])
                        .range([marginleft + padding + 3 * cell, w - padding]);
                }
            } else {
                var xScale = d3.scale
                    .linear()
                    .domain([0, 1])
                    .range([marginleft + padding, marginleft + padding + cell]);
                var xScale1 = d3.scale
                    .linear()
                    .domain([1, 12])
                    .range([
                        marginleft + padding + cell,
                        marginleft + padding + 2 * cell,
                    ]);
                var xScale3 = d3.scale
                    .linear()
                    .domain([12, 228 + (flag - 20) * 12])
                    .range([marginleft + padding + 2 * cell, w - padding]);
            }
            //---20150504 fanzhengzhou upd e.#1833

            //---20150413 fanzhengzhou upd s.
            //var yScale = d3.scale.linear().domain([0, 8]).range([h, 40]);
            var yScale = d3.scale.linear().domain([0, 7]).range([h, 40]);
            //---20150413 fanzhengzhou upd e.
            //设置刻度的格式
            var format = d3.format("1");

            //Create SVG element
            var svg = d3
                .select(ClassOfDiv)
                .append("svg")
                .attr("width", w)
                .attr("height", h);
            //----20141211 fanzhengzhou ins s.  tooltip
            var tip = d3
                .tip()
                .attr("class", "d3-tip")
                .offset([-10, 0])
                .html(function (d) {
                    return d[2];
                });
            svg.call(tip);
            //----20141211 fanzhengzhou ins e.
            //**********************************************************************
            //参照线
            //***********************************************************************
            svg.selectAll("rect")
                .data(dataset1)
                .enter()
                .append("rect")
                .attr("x", function (_d, i) {
                    return (
                        ((w - 2 * padding - marginleft) / flag) * i +
                        padding +
                        40
                    );
                })
                .attr("y", function () {
                    return 40;
                    //20171124 lqs UPD S
                    // }).attr("width", 0.25).attr("height", lineheight).attr("fill", function(d) {
                    // return "rgb(220,220,220)";
                })
                .attr("width", 1)
                .attr("height", lineheight)
                .attr("fill", function () {
                    return "rgb(247,247,247)";
                    //20171124 lqs UPD E
                });
            //----20141212 fanzhengzhou ins s.
            //********************************************************************
            //draw "■"    入庫歴
            //********************************************************************
            //---20150413 fanzhengzhou upd s.
            svg.selectAll("rect0")
                .data(dataset0)
                .enter()
                .append("rect")
                .attr("x", function (d) {
                    if (d[0] >= 0 && d[0] <= 1) {
                        return xScale(d[0]) - 4;
                    } else if (d[0] > 1 && d[0] <= 3) {
                        return xScale1(d[0]) - 4;
                    } else if (d[0] > 3 && d[0] <= 6) {
                        return xScale2(d[0]) - 4;
                    } else {
                        return xScale3(d[0]) - 4;
                    }
                    //设置原点坐标，以及横轴位移量
                })
                .attr("y", function (d) {
                    return yScale(d[1]);
                })
                .attr("width", 8)
                .attr("height", 8)
                .attr("class", function (d) {
                    if (d[0] == dataset5[0]) {
                        return "current";
                    } else {
                        if (d[3] == "01") {
                            return "bar1";
                        } else {
                            return "bar";
                        }
                    }
                })
                .on("mouseover", tip.show)
                .on("mouseout", tip.hide);
            //----20141212 fanzhengzhou ins e.
            //---20150413 fanzhengzhou upd e.
            //********************************************************************
            //draw "■"     リコール
            //********************************************************************
            svg.selectAll("rect1")
                .data(dataset)
                .enter()
                .append("rect")
                .attr("x", function (d) {
                    if (d[0] >= 0 && d[0] <= 1) {
                        return xScale(d[0]) - 4;
                    } else if (d[0] > 1 && d[0] <= 3) {
                        return xScale1(d[0]) - 4;
                    } else if (d[0] > 3 && d[0] <= 6) {
                        return xScale2(d[0]) - 4;
                    } else {
                        return xScale3(d[0]) - 4;
                    }
                    //设置原点坐标，以及横轴位移量
                })
                .attr("y", function (d) {
                    return yScale(d[1]);
                })
                .attr("width", 8)
                .attr("height", 8)
                .attr("class", function (d) {
                    if (d[0] == dataset5[0]) {
                        return "current";
                    } else {
                        return "bar2";
                    }
                })
                .on("mouseover", tip.show)
                .on("mouseout", tip.hide);

            //************************************************************************s
            //draw Rectangular.
            //************************************************************************s
            svg.selectAll("rect2")
                .data(dataset4)
                .enter()
                .append("rect")
                .attr("x", function (d) {
                    if (d[0] >= 0 && d[0] <= 1) {
                        return xScale(d[0]);
                    } else if (d[0] > 1 && d[0] <= 3) {
                        return xScale1(d[0]);
                    } else if (d[0] > 3 && d[0] <= 6) {
                        return xScale2(d[0]);
                    } else {
                        return xScale3(d[0]);
                    }
                    //设置原点坐标，以及横轴位移量
                })
                .attr("y", function (d) {
                    return yScale(d[1]);
                })
                .attr("width", function (d) {
                    return ((w - 2 * padding - marginleft) / flag) * d[2];
                })
                .attr("height", function () {
                    return 14;
                })
                .attr("fill", function (d) {
                    return d[3];
                });
            //on("mouseover", tip.show).on('mouseout', tip.hide);
            //**********************************************************************
            //draw the line of current month.
            //***********************************************************************
            //-----20141202 NO.29 fanzhengzhou  upd s
            //---20150413 fanzhengzhou upd s.
            // if (me.CurrentMonthDraw) {
            svg.selectAll("rect3")
                .data(dataset5)
                .enter()
                .append("rect")
                .attr("x", function (d) {
                    if (d >= 0 && d <= 1) {
                        return xScale(d);
                    } else if (d > 1 && d <= 3) {
                        return xScale1(d);
                    } else if (d > 3 && d <= 6) {
                        return xScale2(d);
                    } else {
                        return xScale3(d);
                    }
                })
                .attr("y", function () {
                    return padding - 50;
                    // }).attr("width", 1).attr("height", lineheight).attr("fill", function(d) {
                })
                .attr("width", 1)
                .attr("height", lineheight + 10)
                .attr("fill", function () {
                    return "rgb(255,127,0)";
                });
            //}
            //---20150413 fanzhengzhou upd e.
            //-----20141202 NO.29 fanzhengzhou  upd e
            //************************************************************************
            //draw the left info.
            //*************************************************************************
            svg.selectAll("text")
                .data(dataset2)
                .enter()
                .append("text")
                .text(function (d) {
                    return d[0];
                })
                .attr("x", function () {
                    return 0;
                })
                .attr("y", function (d) {
                    return yScale(d[1]) + 10;
                })
                .attr("font-family", "sans-serif")
                .attr("font-size", "12px")
                .attr("font-weight", "bold");

            //**************************************************************************
            //単位:月
            //**************************************************************************
            svg.selectAll("text1")
                .data(dataset3)
                .enter()
                .append("text")
                .text(function (d) {
                    return d;
                })
                .attr("x", w - padding + 10)
                .attr("y", 30)
                .attr("font-family", "sans-serif")
                .attr("font-size", "12px")
                .attr("fill", function () {
                    return "rgb(0,0,255)";
                })
                .attr("font-weight", "bold");

            //---20150413 fanzhengzhou add s.
            //**************************************************************************
            //■ 車検
            //**************************************************************************
            svg.selectAll("text2")
                .data(dataset6)
                .enter()
                .append("text")
                .text(function (d) {
                    return d;
                })
                .attr("x", w - padding + 10)
                .attr("y", 52)
                .attr("font-family", "sans-serif")
                .attr("font-size", "12px")
                .attr("fill", function () {
                    return "rgb(255,127,0)";
                });

            //**************************************************************************
            //■ 車検以外
            //**************************************************************************
            svg.selectAll("text3")
                .data(dataset7)
                .enter()
                .append("text")
                .text(function (d) {
                    return d;
                })
                .attr("x", w - padding + 10)
                .attr("y", 70)
                .attr("font-family", "sans-serif")
                .attr("font-size", "12px");
            //---20150413 fanzhengzhou add e.

            //***********************************************************************s
            //draw the info of Rectangular.
            //************************************************************************
            svg.selectAll("text2")
                .data(dataset4)
                .enter()
                .append("text")
                .text(function (d) {
                    //----20141211 fanzhengzhou upd s.
                    //if (d[2] > 0) {
                    //---20150413 fanzhengzhou upd s.商品名　＆　全角スペース３個　＆　最終年月
                    //return d[4];
                    if (d[4] != "" && d[4] != null) {
                        return d[4] + "　　　" + d[5].substr(0, 7);
                    } else {
                        return d[4];
                    }
                    //---20150413 fanzhengzhou upd e.
                    // } else {
                    // return "";
                    // }
                    //----20141211 fanzhengzhou upd e.
                })
                .attr("x", function (d) {
                    //---20150414 fanzhengzhou upe s.
                    // if (d[0] >= 0 && d[0] <= 1) {
                    // return xScale(d[0]);
                    // } else if (d[0] > 1 && d[0] <= 3) {
                    // return xScale1(d[0]);
                    // } else if (d[0] > 3 && d[0] <= 6) {
                    // return xScale2(d[0]);
                    // }
                    // //----20141211 fanzhengzhou ins s.
                    // else if (d[0] >= 90) {
                    // return xScale3(90);
                    // }
                    // //----20141211 fanzhengzhou ins s.
                    // else {
                    // return xScale3(d[0]);
                    // }

                    if (d[6] >= 0 && d[6] <= 1) {
                        return xScale(d[6]);
                    } else if (d[6] > 1 && d[6] <= 3) {
                        return xScale1(d[6]);
                    } else if (d[6] > 3 && d[6] <= 6) {
                        return xScale2(d[6]);
                    }
                    //----20141211 fanzhengzhou ins s.
                    else if (d[6] >= 90) {
                        return xScale3(90);
                    }
                    //----20141211 fanzhengzhou ins s.
                    else {
                        return xScale3(d[6]);
                    }
                    //---20150414 fanzhengzhou upe e.
                    //return xScale(0);
                })
                .attr("y", function (d) {
                    return yScale(d[1]) + 12;
                })
                .attr("font-family", "sans-serif");
            //on("mouseover", tip.show).on('mouseout', tip.hide);

            //************************************************************************
            //draw the end time.
            //*************************************************************************
            //---20150413 fanzhengzhou del s.
            // svg.selectAll("text3").data(dataset4).enter().append("text").text(function(d)
            // {
            // //----20141210 NO.55 fanzhengzhou upd s.
            // //return d[8].substr(0, 7);
            // return d[5].substr(0, 7);
            // //----20141210 NO.55 fanzhengzhou upd e.
            // }).attr("x", function(d)
            // {
            // return w - padding + 10;
            // }).attr("y", function(d)
            // {
            // return yScale(d[1]) + 10;
            // }).attr("font-family", "sans-serif").attr("font-size", "12px");
            //---20150413 fanzhengzhou del e.

            //---20150504 fanzhengzhou upd s.#1833
            if (me.morethan228 == false) {
                //Define X axis[0,1]
                var xAxis = d3.svg
                    .axis()
                    .scale(xScale)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues([0, 1])
                    .orient("bottom")
                    .tickFormat(format);
                //Define X1 axis[1,3]
                var xAxis1 = d3.svg
                    .axis()
                    .scale(xScale1)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues([1, 3])
                    .orient("bottom")
                    .tickFormat(format);
                //Define X2 axis[3,6]
                var xAxis2 = d3.svg
                    .axis()
                    .scale(xScale2)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues([3, 6])
                    .orient("bottom")
                    .tickFormat(format);
                //Define X3 axis[6,132]
                var tmpdataset1 = dataset1.slice(3);
                var xAxis3 = d3.svg
                    .axis()
                    .scale(xScale3)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues(tmpdataset1)
                    .orient("bottom")
                    .tickFormat(format);
                //Create X axis[0,1]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis);
                //Create X1 axis[1,3]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis1);
                //Create X2 axis[3,6]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis2);
                //Create X3 axis[6,132]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis3);
            } else {
                //Define X axis[0,1]
                var xAxis = d3.svg
                    .axis()
                    .scale(xScale)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues([0, 1])
                    .orient("bottom")
                    .tickFormat(format);
                //Define X1 axis[1,12]
                var xAxis1 = d3.svg
                    .axis()
                    .scale(xScale1)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues([1, 12])
                    .orient("bottom")
                    .tickFormat(format);
                //Define X3 axis[12,228]
                var tmpdataset1 = dataset1.slice(2);
                var xAxis2 = d3.svg
                    .axis()
                    .scale(xScale3)
                    .tickSize(-5)
                    .tickPadding(2)
                    .tickValues(tmpdataset1)
                    .orient("bottom")
                    .tickFormat(format);
                //Create X axis[0,1]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis);
                //Create X1 axis[1,12]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis1);
                //Create X2 axis[12,228]
                svg.append("g")
                    .attr("class", "axis")
                    .attr("transform", "translate(0," + (padding - 60) + ")")
                    .call(xAxis2);
            }
            //---20150504 fanzhengzhou upd e.#1833
        } catch (e) {
            console.log("error:draw");
            console.log(e);
        }
    };

    //计算在坐标轴上占几列
    me.tempColNum = function (startYM, endYM) {
        try {
            var MonthNum = me.MonthDifVal(startYM, endYM);
            //----20141211 fanzhengzhou ins s.
            if (MonthNum < 0) {
                return 0;
            }
            //----20141211 fanzhengzhou ins e.
            //----20141210 NO.55 fanzhengzhou upd s.
            //var StartMonthDif = me.MonthDifVal(me.startdate.substr(0, 6), startYM);
            var StartMonthDif = me.MonthDifVal(me.FRGMH, startYM);
            //----20141210 NO.55 fanzhengzhou upd e.

            //---20150504 fanzhengzhou upd s.#1833
            if (me.morethan228 == false) {
                switch (StartMonthDif) {
                    case 0:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 1;
                            case 2:
                                return 1.5;
                            case 3:
                                return 2;
                            case 4:
                                return 2.33;
                            case 5:
                                return 2.67;
                            case 6:
                                return 3;
                            default:
                                return 3 + (MonthNum - 6) / 6;
                        }
                    case 1:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 0.5;
                            case 2:
                                return 1;
                            case 3:
                                return 1.33;
                            case 4:
                                return 1.67;
                            case 5:
                                return 2;
                            default:
                                return 2 + (MonthNum - 5) / 6;
                        }
                    case 2:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 0.5;
                            case 2:
                                return 0.83;
                            case 3:
                                return 1.17;
                            case 4:
                                return 1.5;
                            default:
                                return 1.5 + (MonthNum - 4) / 6;
                        }
                    case 3:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 0.33;
                            case 2:
                                return 0.67;
                            case 3:
                                return 1;
                            default:
                                return 1 + (MonthNum - 3) / 6;
                        }
                    case 4:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 0.33;
                            case 2:
                                return 0.67;
                            default:
                                return 0.67 + (MonthNum - 2) / 6;
                        }
                    case 5:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                                return 0.33;
                            default:
                                return 0.33 + (MonthNum - 1) / 6;
                        }
                    default:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            default:
                                return MonthNum / 6;
                        }
                }
            } else {
                switch (StartMonthDif) {
                    case 0:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                            case 6:
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                                return 1 + (MonthNum - 1) / 11;
                            default:
                                return 2 + (MonthNum - 12) / 12;
                        }
                    case 1:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                            case 5:
                            case 6:
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                                return MonthNum / 11;
                            default:
                                return 1 + (MonthNum - 11) / 12;
                        }
                    default:
                        switch (MonthNum) {
                            case 0:
                                return 0;
                            default:
                                return MonthNum / 12;
                        }
                }
            }
            //---20150504 fanzhengzhou upd e.#1833
        } catch (e) {
            console.log("error:tempColNum");
            console.log(e);
        }
    };
    //计算年月差值
    me.MonthDifVal = function (startYM, endYM) {
        try {
            //----20141210 NO.55 fanzhengzhou upd s.
            if (startYM != "" && endYM != "") {
                return (
                    parseInt(endYM.substr(0, 4)) * 12 +
                    parseInt(endYM.substr(4, 2)) -
                    parseInt(startYM.substr(0, 4)) * 12 -
                    parseInt(startYM.substr(4, 2))
                );
            } else {
                return "";
            }
            //----20141210 NO.55 fanzhengzhou upd e.
        } catch (e) {
            console.log("error:MonthDifVal");
            console.log(e);
        }
    };
    //字符串 日期格式化  YYYY/MM/DD
    me.FormatDate = function (postdata) {
        try {
            if (postdata == "") {
                return "";
            } else {
                return (
                    postdata.substr(0, 4) +
                    "/" +
                    postdata.substr(4, 2) +
                    "/" +
                    postdata.substr(6, 2)
                );
            }
        } catch (e) {
            console.log("error:FormatDate");
            console.log(e);
        }
    };
    // ==========
    // = メソッド end =
    // ==========

    return me;
};
