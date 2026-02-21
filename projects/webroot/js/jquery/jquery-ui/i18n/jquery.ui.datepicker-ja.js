/* Japanese initialisation for the jQuery UI date picker plugin. */
/* Written by Kentaro SATO (kentaro@ranvis.com). */
jQuery(function ($) {
    $.datepicker.regional["ja"] = {
        closeText: "閉じる",
        prevText: "&#x3c;前",
        nextText: "次&#x3e;",
        currentText: "今日",
        monthNames: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        monthNamesShort: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        dayNames: [
            "日曜日",
            "月曜日",
            "火曜日",
            "水曜日",
            "木曜日",
            "金曜日",
            "土曜日",
        ],
        dayNamesShort: ["日", "月", "火", "水", "木", "金", "土"],
        dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
        weekHeader: "週",
        dateFormat: "yy/mm/dd",
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: "年",
    };
    $.datepicker.setDefaults($.datepicker.regional["ja"]);
    //20240605 luohaibing add start
    $.ympicker.regional["ja"] = {
        showOn: "button",
        buttonImage: "css/jquery/images/calendar.gif",
        buttonImageOnly: true,
        closeText: "閉じる",
        prevText: "<前",
        nextText: "次>",
        currentText: "",
        monthNames: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        monthNamesShort: [
            "1月",
            "2月",
            "3月",
            "4月",
            "5月",
            "6月",
            "7月",
            "8月",
            "9月",
            "10月",
            "11月",
            "12月",
        ],
        dateFormat: "yymm",
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: "年",
    };
    $.ympicker.setDefaults($.ympicker.regional["ja"]);
    //20240605 luohaibing add end
});

function convert_wareki(y, b) {
    var tmp;
    //bがfalseの場合、西暦をそのまま返す
    if (b == false) {
        return y;
    }
    //平成
    if (y > 1988) {
        tmp = y - 1988;
        tmp = "平" + tmp;
        return tmp;
    }
    //昭和
    if (y > 1925) {
        tmp = y - 1925;
        tmp = "昭" + tmp;
        return tmp;
    }
    //大正
    if (y > 1911) {
        tmp = y - 1911;
        tmp = "大" + tmp;
        return tmp;
    }
    //明治
    if (y > 1867) {
        tmp = y - 1867;
        tmp = "明" + tmp;
        return tmp;
    }
}
