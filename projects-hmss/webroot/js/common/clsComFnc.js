/**
 * クライアント共通関数
 * @author FCSDL　luchao
 */

Namespace.register("gdmz.common.clsComFnc");

gdmz.common.clsComFnc = function () {
    var me = new Object();
    // ========== 共用定数宣言 start ==========
    //入力タイプ

    me.HMMsg = new Array();

    me.INPUTTYPE = {
        NUMBER1: 0,
        NUMBER2: 1,
        NUMBER3: 2,
        CHAR1: 3,
        CHAR2: 4,
        CHAR3: 5,
        CHAR4: 6,
        CHAR5: 7,
        //20170601 YIN INS S
        CHAR7: 13,
        //20170601 YIN INS E
        DATE1: 8,
        DATE2: 9,
        DATE3: 10,
        DATE4: 11,
        NONE: 12,
    };

    //フォーマットタイプ
    me.FORMATTYPE = {
        YMD1: 0,
        YMD2: 1,
        YMD3: 2,
        YM1: 3,
        YM2: 4,
        Y1: 5,
        Y2: 6,
    };

    me.MsgBoxBtnFnc = {
        Yes: "",
        No: "",
        //20220402 HMTVE ciyuanchen add s
        OK: "",
        //20220402 HMTVE ciyuanchen add e
        Close: "",
    };

    me.MessageBoxDefaultButton = {
        Button1: 0,
        Button2: 1,
    };

    me.MessageBoxButtons = {
        YesNo: "YesNo",
        OKCancel: "OKCancel",
        OK: "OK",
    };

    me.MessageBoxIcon = {
        Err: "Error",
        Information: "Information",
        Warning: "Warning",
        Question: "Question",
    };
    $(document).ready(function () {
        // div 内のすべての label タグをループ処理する
        $("div label").each(function () {
            const $label = $(this);
            const existingFor = $label.attr("for");

            const $checkRadio = $label.find(
                "input[type='checkbox'], input[type='radio']"
            );
            if (
                $checkRadio.length &&
                (!existingFor || existingFor.trim() === "")
            ) {
                if (!$checkRadio.attr("id")) {
                    $checkRadio.attr(
                        "id",
                        "input_" + Math.random().toString(36).substring(2, 9)
                    );
                }
                $label.attr("for", $checkRadio.attr("id"));
                return;
            }

            if (!existingFor || existingFor.trim() === "") {
                // inputでない場合、またはcheckbox/radioの場合、labelをdivに置換する
                const originalClasses = $label.attr("class") || "";
                const id = $label.attr("id");
                const style = $label.attr("style") || "";
                const labelContents = $label.contents();

                const $div = $("<div>").addClass(originalClasses + " Label");

                labelContents.each(function () {
                    if (this.nodeType === 3) {
                        $div.append(document.createTextNode(this.nodeValue));
                    } else {
                        $div.append($(this).clone());
                    }
                });

                if (id) $div.attr("id", id);
                if (style) $div.attr("style", style);

                $label.replaceWith($div);
            }
        });

        // div 内のすべての Input Selectタグをループ処理する
        $("div input, div select, div textarea").each(function () {
            const $input = $(this);

            // 前の要素がlabelでない、またはidが未設定の場合
            if (!$input.attr("id") && $input.prev("label").length === 0) {
                const inputId =
                    "input_" + Math.random().toString(36).substring(2, 9);
                $input.attr("id", inputId);
            }
        });
        // jqgridの中にInput Select textarea
        const observer = new MutationObserver(function () {
            $(".ui-jqgrid input, .ui-jqgrid select, .ui-jqgrid textarea").each(
                function () {
                    const $input = $(this);
                    if (
                        !$input.attr("id") &&
                        $input.prev("label").length === 0
                    ) {
                        $input.attr(
                            "id",
                            "input_" +
                                Math.random().toString(36).substring(2, 9)
                        );
                    }
                }
            );
        });
        // 监听 jqGrid
        $(".ui-jqgrid").each(function () {
            observer.observe(this, { childList: true, subtree: true });
        });
        // Datepickerの月/年ドロップダウン・メニューにIDを追加する
        $(document).on(
            "change focus",
            "input.hasDatepicker, input.hasYmpicker",
            function () {
                const $input = $(this);
                const inputId =
                    $input.attr("id") ||
                    "datepicker-" +
                        Math.random().toString(36).substring(2, 9) +
                        "-" +
                        Date.now();

                setTimeout(function () {
                    $(".ui-datepicker").each(function () {
                        const $datepicker = $(this);
                        const $monthPicker = $datepicker.find(
                            ".ui-datepicker-month"
                        );
                        const $yearPicker = $datepicker.find(
                            ".ui-datepicker-year"
                        );
                        const suffix = inputId + "-" + $datepicker.index();

                        if (!$monthPicker.attr("id")) {
                            $monthPicker.attr("id", "month-" + suffix);
                        }
                        if (!$yearPicker.attr("id")) {
                            $yearPicker.attr("id", "year-" + suffix);
                        }
                        // if (!$monthPicker.attr("name")) {
                        //     $monthPicker.attr("name", "month-name" + suffix);
                        // }
                        // if (!$yearPicker.attr("name")) {
                        //     $yearPicker.attr("name", "year-name" + suffix);
                        // }
                    });
                }, 100);
            }
        );
        $(document).on("dialogopen", function () {
            $(".ui-dialog-content label").each(function () {
                const $label = $(this);
                if (!$label.attr("for") || $label.attr("for").trim() === "") {
                    const originalClasses = $label.attr("class") || "";
                    const html = $label.html();
                    const id = $label.attr("id");
                    const style = $label.attr("style") || "";

                    const $div = $("<div>")
                        .addClass(originalClasses + " Label")
                        .html(html);

                    if (id) $div.attr("id", id);
                    if (style) $div.attr("style", style);

                    $label.replaceWith($div);
                }
            });
        });
        const observer1 = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                // 新しいSelect 2ドロップダウンボックスが表示されているかどうかを確認します
                const $select2Dropdown = $(mutation.target).find(
                    ".select2-dropdown"
                );
                if ($select2Dropdown.length) {
                    const $searchInput = $select2Dropdown.find(
                        ".select2-search__field"
                    );

                    if ($searchInput.length && !$searchInput.attr("id")) {
                        const inputId =
                            "select2-search-" +
                            Math.random().toString(36).substring(2, 9);
                        $searchInput.attr("id", inputId);

                        const $resultsList = $select2Dropdown.find(
                            ".select2-results__options"
                        );
                        if ($resultsList.length) {
                            $searchInput.attr(
                                "aria-controls",
                                $resultsList.attr("id")
                            );
                        }
                    }
                }
            });
        });

        observer1.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
    $.ajax({
        type: "POST",
        url: "Master/Master/GetXml",
        success: function (result) {
            var tmpJsonPa = {};
            var txtPa = '{ "json" : [' + result + "]}";
            tmpJsonPa = eval("(" + txtPa + ")");
            me.HMMsg = tmpJsonPa;
        },
    });

    //背景色
    me.GC_COLOR_ERROR = {
        backgroundColor: "tomato",
    };
    me.GC_COLOR_NORMAL = {
        backgroundColor: "",
    };

    me.GSYSTEM_NAME = "R4→（GD）（DZM）データ連携サブシステム";

    me.ObjFocus = "";
    me.ObjSelect = "";
    // ========== 共用定数宣言 end ==========

    // 20210525 lqs ins S
    // 兼容ie数组没有findIndex方法
    if (!Array.prototype.findIndex) {
        Object.defineProperty(Array.prototype, "findIndex", {
            value: function (predicate) {
                // 1. Let O be ? ToObject(this value).
                if (this == null) {
                    throw new TypeError('"this" is null or not defined');
                }

                var o = Object(this);

                // 2. Let len be ? ToLength(? Get(O, "length")).
                var len = o.length >>> 0;

                // 3. If IsCallable(predicate) is false, throw a TypeError exception.
                if (typeof predicate !== "function") {
                    throw new TypeError("predicate must be a function");
                }

                // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
                var thisArg = arguments[1];

                // 5. Let k be 0.
                var k = 0;

                // 6. Repeat, while k < len
                while (k < len) {
                    // a. Let Pk be ! ToString(k).
                    // b. Let kValue be ? Get(O, Pk).
                    // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
                    // d. If testResult is true, return k.
                    var kValue = o[k];
                    if (predicate.call(thisArg, kValue, k, o)) {
                        return k;
                    }
                    // e. Increase k by 1.
                    k++;
                }

                // 7. Return -1.
                return -1;
            },
        });
    }
    // 20210525 lqs ins E

    //**********************************************************************
    //処　理　名：	項目チェック
    //関　数　名：	FncTextCheck
    //引　　　数 ：	objTextBox　　　　		(I)入力項目
    //		  ：　	intHissuFlg　　　　　	(I)必須フラグ [0：任意/1：必須]
    //		  ：　	intInputType　　　　	(I)入力タイプ
    //		  ：　	intByteLength　　　	(I)バイト数
    //戻　り　値：　	0 ：正常
    //		 　　	-1：必須異常
    //		　　	-2：入力異常
    //		　　	-3：桁数異常
    //処理説明：	項目のエラーチェックを行う。
    //**********************************************************************
    me.FncTextCheck = function (objTextBox, intHissuFlg, intInputType) {
        var intByteLength = arguments[3] != undefined ? arguments[3] : null;
        var intByteCount = "";
        // var intIdx = "";

        //-----背景色設定（異常）-----
        objTextBox.css(me.GC_COLOR_ERROR);

        //-----必須チェック-----
        if (intHissuFlg == 1) {
            if ($.trim(objTextBox.val()) == "") {
                return -1;
            }
        }

        //-----入力チェック-----
        switch (intInputType) {
            //数値
            case me.INPUTTYPE.NUMBER1:
                var patrn = /^[0-9]*?$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;

            //数値/特殊文字(-,.)
            case me.INPUTTYPE.NUMBER2:
                var patrn = /^[0-9-,.]*?$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;

            //数値/特殊文字(-)
            case me.INPUTTYPE.NUMBER3:
                var patrn = /^[0-9-]*?$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;

            //[半角]大文字/小文字/数値/特殊文字
            case me.INPUTTYPE.CHAR1:
                var patrn = /^[0-9\uff66-\uff9f\x00-\xff]*?$/;
                var patrn1 = /[,\'\"]+/;
                if (patrn.exec(objTextBox.val())) {
                    if (patrn1.exec(objTextBox.val())) {
                        //---入力異常---
                        return -2;
                    }
                } else {
                    //---入力異常---
                    return -2;
                }
                break;

            //大文字/小文字/数値/特殊文字(-)
            case me.INPUTTYPE.CHAR2:
                var patrn = /^[0-9A-Za-z-]*?$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;

            //全角/大文字/小文字/数値/カナ/特殊文字（ﾞﾟ）
            case me.INPUTTYPE.CHAR3:
                var patrn =
                    /^[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[ｱｰｳﾞｰ]+|[0-9A-Za-z]+|\s+$/;
                for (var i = 0; i < objTextBox.val().length; i++) {
                    var ch = objTextBox.val().charAt(i);
                    if (!patrn.test(ch)) {
                        if (me.GetByteCount(ch) != 2) {
                            return -2;
                        }
                    }
                }
                break;

            //全角
            case me.INPUTTYPE.CHAR4:
                for (var i = 0; i < objTextBox.val().length; i++) {
                    var ch = objTextBox.val().charAt(i);

                    if (me.GetByteCount(ch) != 2) {
                        return -2;
                    }
                }
                break;

            //[半角]大文字/小文字/数値/特殊文字
            case me.INPUTTYPE.CHAR5:
                var patrn = /^[\uff66-\uff9f]*?$/;
                var patrn1 = /[,\'\"]+/;
                if (patrn.exec(objTextBox.val())) {
                    if (patrn1.exec(objTextBox.val())) {
                        //---入力異常---
                        return -2;
                    }
                } else {
                    //---入力異常---
                    return -2;
                }
                break;
            //---20150810 li INS S.
            //[半角]大文字/小文字/数値/特殊文字
            case me.INPUTTYPE.CHAR6:
                var patrn = /^[\uff66-\uff9f]*?$/;
                var patrn1 = /[,\'\"]+/;
                if (patrn.exec(objTextBox.val().replace(" ", ""))) {
                    if (patrn1.exec(objTextBox.val().replace(" ", ""))) {
                        //---入力異常---
                        return -2;
                    }
                } else {
                    //---入力異常---
                    return -2;
                }
                break;
            //---20150810 li INS E.

            //20170601 YIN INS S
            //大文字/小文字/数値/
            case me.INPUTTYPE.CHAR7:
                var patrn = /^[0-9A-Za-z]*?$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;
            //20170601 YIN INS E

            //日付(YYYY/MM/DD)
            case me.INPUTTYPE.DATE1:
                var patrn = /^(\d{4})\/(\d{2})\/(\d{2})$/;
                var patrn1 = /^(\d{4})\-(\d{2})\-(\d{2})$/;
                if (!patrn.exec(objTextBox.val())) {
                    if (!patrn1.exec(objTextBox.val())) {
                        //---入力異常---
                        return -2;
                    }
                }
                break;

            //日付(YYYY/MM)
            case me.INPUTTYPE.DATE2:
                var patrn = /^(\d{4})\/(\d{2})$/;
                if (!patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;

            //日付(MM)
            case me.INPUTTYPE.DATE3:
                if ($.trim(objTextBox.val()) != "") {
                    var patrn = /^\+?[1-9][0-9]*?$/;
                    if (!patrn.exec(objTextBox.val())) {
                        //---入力異常---
                        return -2;
                    } else {
                        if (
                            parseInt(objTextBox.val()) < 1 ||
                            parseInt(objTextBox.val()) > 12
                        ) {
                            //---入力異常---
                            return -2;
                        }
                    }
                }
                break;

            //日付(DD)
            case me.INPUTTYPE.DATE4:
                if ($.trim(objTextBox.val()) != "") {
                    var patrn = /^\+?[1-9][0-9]*?$/;
                    if (!patrn.exec(objTextBox.val())) {
                        //---入力異常---
                        return -2;
                    } else {
                        if (
                            parseInt(objTextBox.val()) < 1 ||
                            parseInt(objTextBox.val()) > 31
                        ) {
                            //---入力異常---
                            return -2;
                        }
                    }
                }
                break;

            //特殊文字
            case me.INPUTTYPE.NONE:
                var patrn = /[,\'\"]+/;
                if (patrn.exec(objTextBox.val())) {
                    //---入力異常---
                    return -2;
                }
                break;
        }
        //バイト数指定がない場合
        if (intByteLength == null) {
            //inputのmaxlengthを指定する
            intByteLength = objTextBox.prop("maxlength");
        }
        //バイト数
        intByteCount = me.GetByteCount(objTextBox.val());
        if (intByteLength < intByteCount) {
            //---桁数異常---
            return -3;
        }
        //-----背景色設定（正常）-----
        objTextBox.css(me.GC_COLOR_NORMAL);
        return 0;
    };

    //**********************************************************************
    //処　理　名：	文字チェック
    //関　数　名：	FncSprCheck
    //引　　　数：	objSprText　　   　　	(I)入力項目
    //       ：	intHissuFlg　　  　　	(I)必須フラグ [0：任意/1：必須]
    //　　　　　   ：	intInputType　   　　	(I)入力タイプ
    //       ：	intByteLength　　　	(I)バイト数
    //戻 り 値：　　　	0 ：正常
    //         	-1：必須異常
    //         	-2：入力異常
    //         	-3：桁数異常
    //処理説明：	文字のエラーチェックを行う。
    //**********************************************************************

    me.FncSprCheck = function (
        objSprText,
        intHissuFlg,
        intInputType,
        intByteLength
    ) {
        var intByteCount = "";
        // var intIdx = "";

        //-----必須チェック-----
        if (intHissuFlg == 1) {
            if ($.trim(objSprText) == "") {
                //---必須異常---
                return -1;
            }
        }

        //-----入力チェック-----
        switch (intInputType) {
            //数値
            case me.INPUTTYPE.NUMBER1:
                var patrn = /^[0-9]*?$/;
                if (!patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //数値/特殊文字(-,.)
            case me.INPUTTYPE.NUMBER2:
                var patrn = /^[0-9-,.]*?$/;
                if (!patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //数値/特殊文字(-)
            case me.INPUTTYPE.NUMBER3:
                var patrn = /^[0-9-]*?$/;
                if (!patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //[半角]大文字/小文字/数値/特殊文字
            case me.INPUTTYPE.CHAR1:
                var patrn = /^[0-9\uff66-\uff9f\x00-\xff]*?$/;
                var patrn1 = /[,\'\"]+/;
                if (patrn.exec(objSprText)) {
                    if (patrn1.exec(objSprText)) {
                        //---入力異常---
                        return -2;
                    }
                } else {
                    //---入力異常---
                    return -2;
                }
                break;

            //大文字/小文字/数値/特殊文字(-)
            case me.INPUTTYPE.CHAR2:
                var patrn = /^[0-9A-Za-z-]*?$/;
                if (!patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //全角/大文字/小文字/数値/カナ/特殊文字（ﾞﾟ）
            case me.INPUTTYPE.CHAR3:
                var patrn =
                    /^[一-龠]+|[ぁ-ん]+|[ァ-ヴー]+|[ｱｰｳﾞｰ]+|[0-9A-Za-z]+|\s+$/;
                for (var i = 0; i < objSprText.length; i++) {
                    var ch = objSprText.charAt(i);
                    if (!patrn.test(ch)) {
                        if (me.GetByteCount(ch) != 2) {
                            return -2;
                        }
                    }
                }
                break;

            //全角
            case me.INPUTTYPE.CHAR4:
                var patrn = /[\x00-\xff]+/;
                if (patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //日付(YYYY/MM/DD)
            case me.INPUTTYPE.DATE1:
                var patrn = /^(\d{4})\/(\d{2})\/(\d{2})$/;
                var patrn1 = /^(\d{4})\-(\d{2})\-(\d{2})$/;
                if (!patrn.exec(objSprText)) {
                    if (!patrn.exec(objSprText)) {
                        //---入力異常---
                        return -2;
                    }
                }
                break;

            //日付(YYYY/MM)
            case me.INPUTTYPE.DATE2:
                var patrn = /^(\d{4})\/(\d{2})$/;
                if (!patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;

            //日付(MM)
            case me.INPUTTYPE.DATE3:
                if ($.trim(objSprText) != "") {
                    var patrn = /^\+?[1-9][0-9]*?$/;
                    if (!patrn.exec(objSprText)) {
                        //---入力異常---
                        return -2;
                    } else {
                        if (
                            parseInt(objSprText) < 1 ||
                            parseInt(objSprText) > 12
                        ) {
                            //---入力異常---
                            return -2;
                        }
                    }
                }
                break;

            //日付(DD)
            case me.INPUTTYPE.DATE4:
                if ($.trim(objSprText) != "") {
                    var patrn = /^\+?[1-9][0-9]*?$/;
                    if (!patrn.exec(objSprText)) {
                        //---入力異常---
                        return -2;
                    } else {
                        if (
                            parseInt(objSprText) < 1 ||
                            parseInt(objSprText) > 31
                        ) {
                            //---入力異常---
                            return -2;
                        }
                    }
                }
                break;

            //特殊文字
            case me.INPUTTYPE.NONE:
                var patrn = /[,\'\"]+/;
                if (patrn.exec(objSprText)) {
                    //---入力異常---
                    return -2;
                }
                break;
        }
        //バイト数
        intByteCount = me.GetByteCount(objSprText);
        if (intByteLength < intByteCount) {
            //---桁数異常---
            return -3;
        }
        return 0;
    };

    //**********************************************************************
    //処　理　名：	バイト数取得
    //関　数　名：	GetByteCount
    //引　　　数：	str　　　　			文字列
    //戻　り　値：　	バイト数
    //処理説明：	バイト数取得。
    //**********************************************************************

    me.GetByteCount = function (str) {
        var bytesCount = 0;

        var uFF61 = parseInt("FF61", 16);
        var uFF9F = parseInt("FF9F", 16);
        var uFFE8 = parseInt("FFE8", 16);
        var uFFEE = parseInt("FFEE", 16);

        if (str != null) {
            for (var i = 0; i < str.length; i++) {
                var c = parseInt(str.charCodeAt(i));
                if (c < 256) {
                    bytesCount = bytesCount + 1;
                } else {
                    if (uFF61 <= c && c <= uFF9F) {
                        bytesCount = bytesCount + 1;
                    } else if (uFFE8 <= c && c <= uFFEE) {
                        bytesCount = bytesCount + 1;
                    } else {
                        bytesCount = bytesCount + 2;
                    }
                }
            }
        }
        return bytesCount;
    };

    //**********************************************************************
    //処　理　名：	西暦⇒和暦変換
    //関　数　名：	FncChgDateFormat
    //引　　　数：	dtmYmd   	(I)指定西暦日付
    //		 ：	intFmt　 		(I)フォーマットタイプ
    //戻　り　値：　	和暦日付
    //処理説明：	西暦日付を和暦日付に変換する。
    //**********************************************************************

    me.FncChgDateFormat = function (dtmYMD, intFmt) {
        var dtmYMD = new Date(dtmYMD.val());
        var dtmYYYY = dtmYMD.getFullYear();
        var dtmM = (dtmYMD.getMonth() + 1).toString();
        var dtmMM = "";
        var dtmD = dtmYMD.getDate().toString();
        var dtmDD = "";
        var strRtn = "";

        if (dtmM.length == 1) {
            dtmMM = "0" + dtmM;
        } else {
            dtmMM = dtmM;
        }
        if (dtmD.length == 1) {
            dtmDD = "0" + dtmD;
        } else {
            dtmDD = dtmD;
        }

        switch (intFmt) {
            //ggyy/MM/dd
            case me.FORMATTYPE.YMD1:
                strRtn = me.convert_wareki(dtmYYYY) + "/" + dtmMM + "/" + dtmDD;
                break;
            //ggyy年MM月dd日
            case me.FORMATTYPE.YMD2:
                strRtn =
                    me.convert_wareki(dtmYYYY) +
                    "年" +
                    dtmMM +
                    "月" +
                    dtmDD +
                    "日";
                break;
            //ggyy年 M月 d日
            case me.FORMATTYPE.YMD3:
                strRtn =
                    me.convert_wareki(dtmYYYY) +
                    "年" +
                    dtmM +
                    "月" +
                    dtmD +
                    "日";
                break;
            //ggyy/MM
            case me.FORMATTYPE.YM1:
                strRtn = me.convert_wareki(dtmYYYY) + "/" + dtmMM;
                break;
            //ggyy年MM月
            case me.FORMATTYPE.YM2:
                strRtn = me.convert_wareki(dtmYYYY) + "年" + dtmMM + "月";
                break;
            //ggyy
            case me.FORMATTYPE.Y1:
                strRtn = me.convert_wareki(dtmYYYY);
                break;
            //ggyy年
            case me.FORMATTYPE.Y2:
                strRtn = me.convert_wareki(dtmYYYY) + "年";
                break;
        }
        return strRtn;
    };

    me.convert_wareki = function (year) {
        var tmp = "";
        //平成
        if (year > 1988) {
            tmp = year - 1988;
            tmp = "平成" + tmp;
            return tmp;
        }
        //昭和
        if (year > 1925) {
            tmp = year - 1925;
            tmp = "昭和" + tmp;
            return tmp;
        }
        //大正
        if (year > 1911) {
            tmp = year - 1911;
            tmp = "大正" + tmp;
            return tmp;
        }
        //明治
        if (year > 1867) {
            tmp = year - 1867;
            tmp = "明治" + tmp;
            return tmp;
        }
    };

    me.FncMsgBox = function (strMsgNO, strRepText1, strRepText2) {
        strRepText1 = arguments[1] != undefined ? arguments[1] : null;
        strRepText2 = arguments[2] != undefined ? arguments[2] : null;
        //タイトル
        var strTitle = "";
        //メッセージ
        var strMsg = "";
        //デフォルトボタン
        var strDButton = "";
        //名前
        var strName = "";

        for (strName in me.HMMsg.json[0]) {
            switch (strName) {
                //タイトル
                case strMsgNO + "_TITLE":
                    if (me.HMMsg.json[0][strName] != "[object Object]") {
                        strTitle = me.HMMsg.json[0][strName];
                    } else {
                        strTitle = "";
                    }
                    break;
                //メッセージ
                case strMsgNO + "_MESSAGE":
                    strMsg = me.HMMsg.json[0][strName];
                    break;
                //デフォルトボタン
                case strMsgNO + "_DBUTTON":
                    strDButton = me.HMMsg.json[0][strName];
                    if (strDButton == "[object Object]") {
                        strDButton = me.MessageBoxDefaultButton.Button1;
                    }
                    break;
            }
        }
        if ($.trim(strTitle) == "") {
            strTitle = me.GSYSTEM_NAME;
        }

        if ($.trim(strMsg) == "") {
            strTitle = me.GSYSTEM_NAME;
            strMsg = "メッセージが登録されていません。";
            strMsg = "【" + strMsgNO + "】" + "<br />" + strMsg;
            me.MessageBox(strMsg, strTitle, "OK", "Error");
            return -1;
        } else {
            //置換え
            strMsg = strMsg.replace("%1", strRepText1);
            if (strMsgNO != "E9997") {
                strMsg = strMsg.replace("%2", strRepText2);
            }

            strMsg = "【" + strMsgNO + "】" + "<br />" + strMsg;
        }

        switch (strMsgNO.substring(0, 1)) {
            //---エラー---
            case "E":
                //ﾌｫｰﾑﾛｰﾄﾞ時のｴﾗｰ専用
                if (strMsgNO == "E9997") {
                    me.MessageBox(
                        strMsg,
                        strRepText2,
                        me.MessageBoxButtons.OK,
                        me.MessageBoxIcon.Err
                    );
                } else {
                    me.MessageBox(
                        strMsg,
                        strTitle,
                        me.MessageBoxButtons.OK,
                        me.MessageBoxIcon.Err
                    );
                }
                break;
            //---インフォメーション---
            case "I":
                me.MessageBox(
                    strMsg,
                    strTitle,
                    me.MessageBoxButtons.OK,
                    me.MessageBoxIcon.Information
                );
                break;
            //---警告---
            case "W":
                me.MessageBox(
                    strMsg,
                    strTitle,
                    me.MessageBoxButtons.OK,
                    me.MessageBoxIcon.Warning
                );
                break;
            //---問合せ---
            case "Q":
                switch (strMsgNO.substring(1, 2)) {
                    //OK・Cancel
                    case "O":
                        me.MessageBox(
                            strMsg,
                            strTitle,
                            me.MessageBoxButtons.OKCancel,
                            me.MessageBoxIcon.Question,
                            strDButton
                        );
                        break;
                    //はい・いいえ
                    case "Y":
                        me.MessageBox(
                            strMsg,
                            strTitle,
                            me.MessageBoxButtons.YesNo,
                            me.MessageBoxIcon.Question,
                            strDButton
                        );
                        break;
                    default:
                        return -1;
                }
                break;
            default:
                return -1;
            // break;
        }
    };

    me.MessageBox = function (
        strMsg,
        strTitle,
        MessageBoxButtonType,
        MessageBoxIconType,
        MessageBoxDefaultFocus
    ) {
        var MsgBox = new gdmz.common.MessageBox();
        //20190625 zhangxiaolei ins s
        MsgBox.GSYSTEM_NAME = me.GSYSTEM_NAME;
        //20190625 zhangxiaolei ins e
        if (me.MsgBoxBtnFnc.Yes != "") {
            MsgBox.MsgBoxBtnFnc.Yes = me.MsgBoxBtnFnc.Yes;
            me.MsgBoxBtnFnc.Yes = "";
        }
        if (me.MsgBoxBtnFnc.No != "") {
            MsgBox.MsgBoxBtnFnc.No = me.MsgBoxBtnFnc.No;
            me.MsgBoxBtnFnc.No = "";
        }
        if (me.MsgBoxBtnFnc.Close != "") {
            MsgBox.MsgBoxBtnFnc.Close = me.MsgBoxBtnFnc.Close;
            me.MsgBoxBtnFnc.Close = "";
        }
        //20220402 HMTVE ciyuanchen add s
        if (me.MsgBoxBtnFnc.OK != "") {
            MsgBox.MsgBoxBtnFnc.OK = me.MsgBoxBtnFnc.OK;
            me.MsgBoxBtnFnc.OK = "";
        }
        //20220402 HMTVE ciyuanchen add e
        if (me.ObjFocus != "") {
            MsgBox.ObjFocus = me.ObjFocus;
            me.ObjFocus = "";
        }
        if (me.ObjSelect != "") {
            MsgBox.ObjSelect = me.ObjSelect;
            me.ObjSelect = "";
        }
        MsgBox.MessageBox(
            strMsg,
            strTitle,
            MessageBoxButtonType,
            MessageBoxIconType,
            MessageBoxDefaultFocus
        );
    };

    me.fncGetFixVal = function (strStr, intLen) {
        var bytesCount = 0;
        var patrn1 = /^[\uff66-\uff9f]$/;
        var patrn2 = /^[\u0000-\u00ff]$/;
        var str = "";
        var Len = 0;
        for (var i = 0; i < strStr.length; i++) {
            var c = strStr.charAt(i);
            if (patrn2.test(c)) {
                bytesCount = 1;
            } else if (patrn1.test(c)) {
                bytesCount = 1;
            } else {
                bytesCount = 2;
            }
            if (Len + bytesCount > intLen) {
                break;
            } else {
                Len += bytesCount;
                str += c;
            }
        }
        return str;
    };

    //**********************************************************************
    //処 理 名：Null変換関数(文字)
    //関 数 名：FncNv
    //引     数：objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(文字)を行う。
    //**********************************************************************

    me.FncNv = function (objValue, objReturn) {
        objReturn = arguments[1] != undefined ? arguments[1] : "";
        //---NULLの場合---
        if (objValue == null) {
            return objReturn;
        }
        //---以外の場合---
        else {
            return objValue;
        }
    };

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNv
    //引     数：objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************

    me.FncSqlNv = function (objValue, objReturn) {
        objReturn = arguments[1] != undefined ? arguments[1] : "";
        //---NULLの場合---
        if (objValue == null) {
            if (objReturn != "") {
                return objReturn;
            } else {
                return "''";
            }
        }
        //---以外の場合---
        else {
            return "'" + objValue.replace(/\'/g, "''") + "'";
        }
    };

    //**********************************************************************
    //処 理 名：Null変換関数(SqlDate文字)
    //関 数 名：FncSqlDate
    //引     数：objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(SqlDate文字)を行う。
    //**********************************************************************

    me.FncSqlDate = function (objValue, objReturn) {
        objReturn = arguments[1] != undefined ? arguments[1] : "Null";
        //---NULLの場合---
        if (objValue == null) {
            return objReturn;
        }
        //---以外の場合---
        else {
            return "TO_DATE('" + objValue + "','YYYY/MM/DD HH24:MI:SS')";
        }
    };

    //**********************************************************************

    //**********************************************************************
    //処 理 名：Null変換関数(Sql文字)
    //関 数 名：FncSqlNz
    //引     数：objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(Sql文字)を行う。
    //**********************************************************************

    me.FncSqlNz = function (objValue, objReturn) {
        objReturn = arguments[1] != undefined ? arguments[1] : "Null";
        //---''の場合---
        if (objValue == null) {
            if (objReturn != "") {
                return objReturn;
            } else {
                return "Null";
            }
        }
        //---以外の場合---
        else {
            return "'" + objValue.replace(/\'/g, "''") + "'";
        }
    };

    //**********************************************************************
    //処 理 名：Null変換関数(数値)
    //関 数 名：FncNz
    //引     数：objValue     (I)文字列
    //戻 り 値：変換後の値
    //処理説明：Null変換(数値)を行う。
    //**********************************************************************

    me.FncNz = function (objValue) {
        //---NULLの場合---
        if (objValue == null) {
            return 0;
        }
        //---空白の場合---
        else if ($.trim(objValue) == "") {
            return 0;
        }
        //---その他---
        else {
            return objValue;
        }
    };

    me.CheckDate = function (Object) {
        var ObjectValue = Object.val();
        var patrn = /^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            var d = new Date(r[1], r[3] - 1, r[4]);
            var RigDate =
                d.getFullYear() +
                r[2] +
                (d.getMonth() + 1) +
                r[2] +
                d.getDate();
            var s = ObjectValue.substring(4, 5);
            var newdateArr = ObjectValue.split(s);
            newdateArr[1] = newdateArr[1].trimStart("0");
            newdateArr[2] = newdateArr[2].trimStart("0");
            var newdate = newdateArr[0] + s + newdateArr[1] + s + newdateArr[2];
            if (RigDate == newdate) {
                if (newdateArr[1] < 10) {
                    newdateArr[1] = "0" + newdateArr[1];
                }
                if (newdateArr[2] < 10) {
                    newdateArr[2] = "0" + newdateArr[2];
                }
                Object.val(
                    newdateArr[0] + s + newdateArr[1] + s + newdateArr[2]
                );
                return true;
            } else {
                return false;
            }
        }
    };

    me.CheckDate2 = function (Object) {
        var ObjectValue = Object.val();
        var patrn = /^(\d{4})(-|\/)(\d{1,2})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            if (r[1] < 1753 || r[1] > 9998) {
                return false;
            }
            if (r[3] > 12 || r[3] <= 0) {
                return false;
            } else if (r[3].length < 2) {
                Object.val(r[1] + r[2] + "0" + r[3]);
            }
        }
        return true;
    };

    me.CheckDate3 = function (Object) {
        var ObjectValue = Object.val();
        var patrn = /^(\d{4})(\d{1,2})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            if (r[1] < 1753 || r[1] > 9998) {
                return false;
            }
            if (r[2] > 12 || r[2] <= 0) {
                return false;
            } else if (r[2].length < 2) {
                Object.val(r[1] + "0" + r[2]);
            }
        }
        return true;
    };

    //20191213 WY INS S
    //対象年
    me.CheckDate4 = function (Object) {
        var ObjectValue = Object.val();
        var patrn = /^(\d{4})$/;
        var r = ObjectValue.match(patrn);
        if (r == null) {
            return false;
        } else {
            if (r[1] < 1753 || r[1] > 9998) {
                return false;
            }
            if (r[2] > 12 || r[2] <= 0) {
                return false;
            }
        }
        return true;
    };

    me.CheckEmail = function (Object) {
        var patrn =
            /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if (!patrn.exec(Object)) {
            return false;
        }
        return true;
    };
    //20191213 WY INS E
    // 20250428 lujunxia upd s
    // me.Shif_TabKeyDown = function () {
    //     var $inp = $(".Tab");
    //     $inp.on("keydown", function (e) {
    //         var key = e.which;
    //         if (key == 9 && e.shiftKey == true) {
    //             e.preventDefault();
    //             var nxtIdx = $inp.index(this);
    //             for (var i = nxtIdx; i >= 0; i--) {
    //                 if (i != 0) {
    //                     if (me.isElementInDialog($(this))) {
    //                         if (
    //                             me.isElementInDialog(
    //                                 $(".Tab:eq(" + (i - 1) + ")")
    //                             ) &&
    //                             $(".Tab:eq(" + (i - 1) + ")").prop(
    //                                 "disabled"
    //                             ) != true
    //                         ) {
    //                             $(".Tab:eq(" + (i - 1) + ")").focus();
    //                             $(".Tab:eq(" + (i - 1) + ")").select();
    //                             return;
    //                         }
    //                     } else {
    //                         if (
    //                             $(".Tab:eq(" + (i - 1) + ")").prop(
    //                                 "disabled"
    //                             ) != true
    //                         ) {
    //                             $(".Tab:eq(" + (i - 1) + ")").focus();
    //                             $(".Tab:eq(" + (i - 1) + ")").select();
    //                             return;
    //                         }
    //                     }
    //                 } else {
    //                     for (var j = $inp.length - 1; j >= 0; j--) {
    //                         if (me.isElementInDialog($(this))) {
    //                             if (
    //                                 me.isElementInDialog(
    //                                     $(".Tab:eq(" + j + ")")
    //                                 ) &&
    //                                 $(".Tab:eq(" + j + ")").prop("disabled") !=
    //                                     true
    //                             ) {
    //                                 $(".Tab:eq(" + j + ")").focus();
    //                                 $(".Tab:eq(" + j + ")").select();
    //                                 return false;
    //                             }
    //                         } else {
    //                             if (
    //                                 $(".Tab:eq(" + j + ")").prop("disabled") !=
    //                                 true
    //                             ) {
    //                                 $(".Tab:eq(" + j + ")").focus();
    //                                 $(".Tab:eq(" + j + ")").select();
    //                                 return false;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };
    me.Shif_TabKeyDown = function () {
        var $inp = $(".Tab");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == true) {
                e.preventDefault();
                if (me.isElementInDialog($(this))) {
                    var topDialog = $('.ui-dialog[style*="z-index"]').sort(
                        function (a, b) {
                            return parseInt($(a).css("z-index"), 10) >
                                parseInt($(b).css("z-index"), 10)
                                ? -1
                                : 1;
                        }
                    )[0];
                    var $inp_filter = $(topDialog).find(".Tab");
                } else {
                    var $inp_filter = $inp;
                }
                var $inp_enabled = $inp_filter.filter(":enabled");
                $inp_enabled = $inp_enabled.filter(":visible");
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
    // me.EnterKeyDown = function () {
    //     var $inp = $(".Enter");
    //     $inp.on("keydown", function (e) {
    //         var key = e.which;
    //         if (key == 13) {
    //             if (
    //                 this.type != "submit" &&
    //                 this.type != "textarea" &&
    //                 this.type != "checkbox"
    //             ) {
    //                 e.preventDefault();
    //                 var nxtIdx = $inp.index(this);
    //                 for (var i = nxtIdx; i < $inp.length; i++) {
    //                     if (i != $inp.length - 1) {
    //                         if (
    //                             $(".Enter:eq(" + (i + 1) + ")").prop(
    //                                 "disabled"
    //                             ) != true
    //                         ) {
    //                             $(".Enter:eq(" + (i + 1) + ")").focus();
    //                             $(".Enter:eq(" + (i + 1) + ")").select();
    //                             return false;
    //                         }
    //                     } else {
    //                         for (var j = 0; j < $inp.length; j++) {
    //                             if (me.isElementInDialog($(this))) {
    //                                 if (
    //                                     me.isElementInDialog(
    //                                         $(".Enter:eq(" + j + ")")
    //                                     ) &&
    //                                     $(".Enter:eq(" + j + ")").prop(
    //                                         "disabled"
    //                                     ) != true
    //                                 ) {
    //                                     $(".Enter:eq(" + j + ")").focus();
    //                                     $(".Enter:eq(" + j + ")").select();
    //                                     return false;
    //                                 }
    //                             } else {
    //                                 if (
    //                                     $(".Enter:eq(" + j + ")").prop(
    //                                         "disabled"
    //                                     ) != true
    //                                 ) {
    //                                     $(".Enter:eq(" + j + ")").focus();
    //                                     $(".Enter:eq(" + j + ")").select();
    //                                     return false;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };
    me.EnterKeyDown = function () {
        var $inp = $(".Enter");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 13) {
                if (
                    this.type != "submit" &&
                    this.type != "textarea" &&
                    this.type != "checkbox"
                ) {
                    e.preventDefault();
                    if (me.isElementInDialog($(this))) {
                        var topDialog = $('.ui-dialog[style*="z-index"]').sort(
                            function (a, b) {
                                return parseInt($(a).css("z-index"), 10) >
                                    parseInt($(b).css("z-index"), 10)
                                    ? -1
                                    : 1;
                            }
                        )[0];
                        var $inp_filter = $(topDialog).find(".Enter");
                    } else {
                        var $inp_filter = $inp;
                    }
                    var $inp_enabled = $inp_filter.filter(":enabled");
                    $inp_enabled = $inp_enabled.filter(":visible");
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
    // me.TabKeyDown = function () {
    //     var $inp = $(".Tab");
    //     $inp.on("keydown", function (e) {
    //         var key = e.which;
    //         if (key == 9 && e.shiftKey == false) {
    //             e.preventDefault();
    //             var nxtIdx = $inp.index(this);
    //             for (var i = nxtIdx; i < $inp.length; i++) {
    //                 if (i != $inp.length - 1) {
    //                     if (
    //                         $(".Tab:eq(" + (i + 1) + ")").prop("disabled") !=
    //                         true
    //                     ) {
    //                         $(".Tab:eq(" + (i + 1) + ")").focus();
    //                         $(".Tab:eq(" + (i + 1) + ")").select();
    //                         return false;
    //                     }
    //                 } else {
    //                     for (var j = 0; j < $inp.length; j++) {
    //                         if (me.isElementInDialog($(this))) {
    //                             if (
    //                                 me.isElementInDialog(
    //                                     $(".Tab:eq(" + j + ")")
    //                                 ) &&
    //                                 $(".Tab:eq(" + j + ")").prop("disabled") !=
    //                                     true
    //                             ) {
    //                                 $(".Tab:eq(" + j + ")").focus();
    //                                 $(".Tab:eq(" + j + ")").select();
    //                                 return false;
    //                             }
    //                         } else {
    //                             if (
    //                                 $(".Tab:eq(" + j + ")").prop("disabled") !=
    //                                 true
    //                             ) {
    //                                 $(".Tab:eq(" + j + ")").focus();
    //                                 $(".Tab:eq(" + j + ")").select();
    //                                 return false;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // };
    me.TabKeyDown = function () {
        var $inp = $(".Tab");
        $inp.on("keydown", function (e) {
            var key = e.which;
            if (key == 9 && e.shiftKey == false) {
                e.preventDefault();
                if (me.isElementInDialog($(this))) {
                    var topDialog = $('.ui-dialog[style*="z-index"]').sort(
                        function (a, b) {
                            return parseInt($(a).css("z-index"), 10) >
                                parseInt($(b).css("z-index"), 10)
                                ? -1
                                : 1;
                        }
                    )[0];
                    var $inp_filter = $(topDialog).find(".Tab");
                } else {
                    var $inp_filter = $inp;
                }
                var $inp_enabled = $inp_filter.filter(":enabled");
                $inp_enabled = $inp_enabled.filter(":visible");
                var nxtIdx = Number($inp_enabled.index(this)) + 1;
                if (nxtIdx == $inp_enabled.length) {
                    //last one : init
                    nxtIdx = 0;
                }
                $inp_enabled.eq(nxtIdx).select();
                $inp_enabled.eq(nxtIdx).trigger("focus");

                e.stopPropagation();
            }
        });
    };
    // 20250428 lujunxia upd e
    me.isElementInDialog = function ($element) {
        // 检查元素是否具有dialog内容的class
        var isInDialog = $element.closest(".ui-dialog-content").length > 0;

        // 获取元素的 class，并标准化为空格分隔的数组
        var classArray = $element[0].className.trim().split(/\s+/);

        // 将class转换成用 . 连接的格式,检查元素是否是某个 dialog 的直接子元素
        var isDirectChildOfDialog =
            $(".ui-dialog").find("." + classArray.join(".")).length > 0;

        return isInDialog || isDirectChildOfDialog;
    };

    String.prototype.substr = function (start, length = null) {
        var temp = this;
        var str = "";
        if (length !== null) {
            if (length < 0) {
                str = "";
            } else {
                str = temp.substring(start, start + length);
            }
        } else {
            str = temp.substring(start, this.length);
        }
        return String(str);
    };

    /**
     * 前後にあるスペース（空白）を除いた文字列を取得
     * Stringクラスを拡張
     * @alias trim
     * @return {String} 処理後の文字列
     */
    String.prototype.trim = function () {
        str = this.returnSpace();
        str = str.replace(/^[ \t\r\n]+|[ \t\r\n]+$/g, "");
        //20250327 lujunxia upd s
        //str = str.replace(/^[&nbsp;\t\r\n]+|[&nbsp;\t\r\n]+$/g, "");
        str = str.replace(
            /^(?:&nbsp;|\u00A0|\t|\r|\n)+|(?:&nbsp;|\u00A0|\t|\r|\n)+$/g,
            ""
        );
        //20250327 lujunxia upd e
        return str;
    };

    String.prototype.trimStart = function (trimStr) {
        trimStr = arguments[0] != undefined ? arguments[0] : " ";
        var temp = this;
        while (true) {
            if (temp.substring(0, trimStr.length) != trimStr) {
                break;
            }
            temp = temp.substring(trimStr.length);
        }
        return String(temp);
    };

    String.prototype.numFormat = function (strNum) {
        var strNum = this;
        strNum = strNum.toString().replace(/\,/g, "");

        sign = strNum == (strNum = Math.abs(strNum));
        strNum = Math.floor(strNum * 10 + 0.50000000001);
        strNum = Math.floor(strNum / 10).toString();
        for (var i = 0; i < Math.floor((strNum.length - (1 + i)) / 3); i++)
            strNum =
                strNum.substring(0, strNum.length - (4 * i + 3)) +
                "," +
                strNum.substring(strNum.length - (4 * i + 3));
        return (sign ? "" : "-") + strNum;
    };

    me.mailMatch = function (strMail) {
        if (
            strMail.search(
                /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/
            ) != -1
        ) {
            return true;
        } else {
            return false;
        }
    };

    String.prototype.trimEnd = function (trimStr) {
        trimStr = arguments[0] != undefined ? arguments[0] : " ";
        if (!trimStr) {
            return this;
        }
        var temp = this;
        while (true) {
            if (
                temp.substring(temp.length - trimStr.length, temp.length) !=
                trimStr
            ) {
                break;
            }
            temp = temp.substring(0, temp.length - trimStr.length);
        }
        return String(temp);
    };

    String.prototype.padLeft = function (len, ch) {
        ch = arguments[1] != undefined ? arguments[1] : " ";
        var s = String(this);
        if (s != "") {
            while (s.length < len) s = ch + s;
        }
        return s;
    };

    String.prototype.padRight = function (len, ch) {
        ch = arguments[1] != undefined ? arguments[1] : " ";
        var s = String(this);
        if (s != "") {
            while (s.length < len) s = s + ch;
        }
        return s;
    };

    /**
     * 全置換：全ての文字列 org を dest に置き換える
     * Stringクラスを拡張
     * @alias replaceAll
     * @return {String} 処理後の文字列
     */
    String.prototype.replaceAll = function (org, dest) {
        return this.split(org).join(dest);
    };

    /**
     * 文字列内の半角SPACEを文字コード"160"で置き換える
     * Stringクラスを拡張
     * @alias convertSpace
     * @return {String} 処理後の文字列
     */
    String.prototype.convertSpace = function () {
        return this.replaceAll(" ", String.fromCharCode("160"));
    };

    /**
     * 文字列内の「&nbsp;」と文字コード"160"を半角SPACEで置き換える
     * Stringクラスを拡張
     * @alias returnSpace
     * @return {String} 処理後の文字列
     */
    String.prototype.returnSpace = function () {
        var str = this.replaceAll("&nbsp;", " ");
        //20250327 lujunxia ins s
        var str = this.replaceAll("\u00A0", " ");
        //20250327 lujunxia ins e
        return str.replaceAll(String.fromCharCode("160"), " ");
    };

    /**
     * ゼロ詰め
     * 数値の先頭からゼロを除去
     * @alias zeroShift
     * @return ゼロ除去後の文字列
     */
    String.prototype.zeroShift = function () {
        var rep = new RegExp("^0+0?");

        var minus = "";
        var str = this.trim();

        if (str.indexOf("-") == 0) {
            minus = "-";
            str = str.substring(1);
        }

        str = str.replace(rep, "");

        if (str.indexOf(".") == 0) {
            str = "0" + str;
        }
        if (str == "") {
            str = "0";
        }

        str = minus + str;

        return str;
    };

    /**
     * 半角へ変換
     * @alias toHankaku
     * @return 半角へ変換後の文字列
     */
    String.prototype.toHankaku = function () {
        return this.replace(/[！-～]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) - 0xfee0);
        });
    };

    /**
     * 全角のカタカナを半角のカタカナに変換します。
     * @example
     * "アイウエオ".toHankanaCase(); // ｱｲｳｴｵ
     * @return
     */
    String.prototype.toHankanaCase = function () {
        var i,
            f,
            c,
            a = [],
            m = String.prototype.toHankanaCase.MAPPING;

        for (i = 0, f = this.length; i < f; ) {
            c = this.charCodeAt(i++);
            switch (true) {
                case c in m:
                    a.push(m[c]);
                    break;
                case 0x30ab <= c && c <= 0x30c9:
                    a.push(m[c - 1], 0xff9e);
                    break;
                case 0x30cf <= c && c <= 0x30dd:
                    a.push(m[c - (c % 3)], [0xff9e, 0xff9f][(c % 3) - 1]);
                    break;
                default:
                    a.push(c);
                    break;
            }
        }

        // replaceAll("ャ", "ｬ").replaceAll("ュ", "ｭ").replaceAll("ョ", "ｮ")
        return String.fromCharCode
            .apply(null, a)
            .replaceAll("ャ", "ｬ")
            .replaceAll("ュ", "ｭ")
            .replaceAll("ョ", "ｮ");
    };

    String.prototype.toHankanaCase.MAPPING = {
        0x30a1: 0xff67,
        0x30a3: 0xff68,
        0x30a5: 0xff69,
        0x30a7: 0xff6a,
        0x30a9: 0xff6b,
        0x30fc: 0xff70,
        0x30a2: 0xff71,
        0x30a4: 0xff72,
        0x30a6: 0xff73,
        0x30a8: 0xff74,
        0x30aa: 0xff75,
        0x30ab: 0xff76,
        0x30ad: 0xff77,
        0x30af: 0xff78,
        0x30b1: 0xff79,
        0x30b3: 0xff7a,
        0x30b5: 0xff7b,
        0x30b7: 0xff7c,
        0x30b9: 0xff7d,
        0x30bb: 0xff7e,
        0x30bd: 0xff7f,
        0x30bf: 0xff80,
        0x30c1: 0xff81,
        0x30c4: 0xff82,
        0x30c6: 0xff83,
        0x30c8: 0xff84,
        0x30ca: 0xff85,
        0x30cb: 0xff86,
        0x30cc: 0xff87,
        0x30cd: 0xff88,
        0x30ce: 0xff89,
        0x30cf: 0xff8a,
        0x30d2: 0xff8b,
        0x30d5: 0xff8c,
        0x30d8: 0xff8d,
        0x30db: 0xff8e,
        0x30de: 0xff8f,
        0x30df: 0xff90,
        0x30e0: 0xff91,
        0x30e1: 0xff92,
        0x30e2: 0xff93,
        0x30e4: 0xff94,
        0x30e6: 0xff95,
        0x30e8: 0xff96,
        0x30e9: 0xff97,
        0x30ea: 0xff98,
        0x30eb: 0xff99,
        0x30ec: 0xff9a,
        0x30ed: 0xff9b,
        0x30ef: 0xff9c,
        0x30f2: 0xff66,
        0x30f3: 0xff9d,
        0x30c3: 0xff6f,
    };

    /**
     * 全角へ変換
     * @alias toHankaku
     * @return 全角へ変換後の文字列
     */
    String.prototype.toZenkaku = function () {
        return this.replace(/[\!-\~]/g, function (s) {
            return String.fromCharCode(s.charCodeAt(0) + 0xfee0);
        });
    };

    String.prototype.toHanUp = function () {
        return this.trim().toHankaku().toUpperCase();
    };
    // 20210121 YIN INS S
    Date.prototype.Format = function (fmt) {
        //author: meizz
        var o = {
            "M+": this.getMonth() + 1, //月份
            "d+": this.getDate(), //日
            "H+": this.getHours(), //小时
            "m+": this.getMinutes(), //分
            "s+": this.getSeconds(), //秒
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            "S+": this.getMilliseconds(), //毫秒
        };
        //20240827 upd s
        // var reg = /(y+)/;
        // if (reg.test(fmt));
        // fmt = fmt.replace(
        //     reg.exec(fmt)[1],
        //     (this.getFullYear() + "").substr(4 - reg.exec(fmt)[1].length)
        // );
        // for (var k in o)
        //     if (new RegExp("(" + k + ")").test(fmt))
        //         fmt = fmt.replace(
        //             reg.exec(fmt)[1],
        //             reg.exec(fmt)[1].length == 1
        //                 ? o[k]
        //                 : ("00" + o[k]).substr(("" + o[k]).length)
        //         );
        var yearMatch = /(y+)/.exec(fmt);
        if (yearMatch) {
            fmt = fmt.replace(
                yearMatch[0],
                (this.getFullYear() + "").slice(-yearMatch[0].length)
            );
        }
        for (var k in o) {
            var reg = new RegExp("(" + k + ")");
            if (reg.test(fmt)) {
                var match = reg.exec(fmt);
                if (match) {
                    var str = "" + o[k];
                    fmt = fmt.replace(
                        match[0],
                        match[0].length === 1
                            ? str
                            : ("00" + str).slice(-match[0].length)
                    );
                }
            }
        }
        //20240827 upd e
        return fmt;
    };
    // 20210121 YIN INS E

    /**
     * 定文字列のバイト数を取得
     * @alias  getByte
     * @param {String} str
     * @return {Number} バイト数
     */
    me.getByte = function (str) {
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            var ch = str.charAt(i);
            if (me.isMultibyte(ch) == true) {
                len += 2;
            } else {
                len += 1;
            }
        }
        return len;
    };
    /**
     * 指定文字列の指定開始位置から指定長さを文字列を取得
     * @alias mySubStr
     * @param {String} str
     * @param {Number} startIndex
     * @param {Number} byteLen
     * @return {String} 処理後の文字列
     */
    me.mySubStr = function (str, startIndex, byteLen) {
        var outStr = "";
        var len = 0;
        var idx = 0;
        for (var i = 0; i < str.length; i++) {
            var ch = str.charAt(i);
            if (me.isMultibyte(ch) == true) {
                idx = idx + 2;
                if (idx >= startIndex) {
                    outStr = outStr + ch;
                    len = len + 2;
                }
            } else {
                idx = idx + 1;
                if (idx >= startIndex) {
                    outStr = outStr + ch;
                    len = len + 1;
                }
            }

            if (len >= byteLen) {
                break;
            }
        }
        return outStr;
    };

    /**
     * 指定文字列がマルチバイトかどうかチェックす
     * @alias isMultibyte
     * @param {String} str
     * @return {Boolean} マルチバイトの場合：true,マルチバイトではない場合：false
     */
    me.isMultibyte = function (c) {
        var c = c.charCodeAt(0);
        // Shift_JIS: 0x0 ～ 0x80, 0xa0 , 0xa1 ～ 0xdf , 0xfd ～ 0xff
        // Unicode : 0x0 ～ 0x80, 0xf8f0, 0xff61 ～ 0xff9f, 0xf8f1 ～ 0xf8f3
        if (
            (c >= 0x0 && c < 0x81) ||
            c == 0xf8f0 ||
            (c >= 0xff61 && c < 0xffa0) ||
            (c >= 0xf8f1 && c < 0xf8f4)
        ) {
            return false;
        } else {
            return true;
        }
    };

    /**
     * 指定バイト配列から文字列へ変換
     * @alias bytesToString
     * @param {ByteArray} bytes
     * @return {String} 処理後の文字列
     */
    me.bytesToString = function (bytes) {
        var str = "";
        // var reBytes;
        var ch, ch1;
        var strArr = new Array();
        for (var i = 0; i < bytes.length; i++) {
            ch = bytes[i] & 0xff;
            if ((ch >= 0x81 && ch <= 0x9f) || (ch >= 0xe0 && ch <= 0xfc)) {
                ch = ch.toString(16);
                if (i + 1 < bytes.length) {
                    ch1 = bytes[i + 1] & 0xff;
                    if (
                        (ch1 >= 0x40 && ch1 <= 0x7e) ||
                        (ch1 >= 0x80 && ch1 <= 0xfc)
                    ) {
                        ch1 = ch1.toString(16);
                        strArr.push(UnescapeSJIS("%" + ch + "%" + ch1));
                        i = i + 1;
                    }
                }
            } else {
                ch = ch.toString(16);
                strArr.push(UnescapeSJIS("%" + ch));
            }
        }
        str = strArr.join("");
        return UnescapeSJIS(str);
    };

    /**
     *
     * @alias getBytesSJIS
     * @param {String} str
     */
    me.getBytesSJIS = function (str) {
        //str = "営"
        var bytes = new Array();
        var tmp = "";
        for (var i = 0; i < str.length; i++) {
            //文字を順番に取得する
            //ch = "営"
            var ch = str.charAt(i);

            if (me.isMultibyte(ch)) {
                //SJISコードに変換する
                //ch = "%89c"
                ch = EscapeSJIS(ch);
                ch = ch.toLowerCase();

                //最初の文字が"%"か確認する
                if (ch.indexOf("%") == 0) {
                    //"%"以降の文字列を取得する
                    //ch = "89c"
                    ch = ch.substring(1);

                    //"%"が含まれるか確認する
                    if (ch.indexOf("%") > -1) {
                        //"%"の位置（Index）を取得する
                        var idx = ch.indexOf("%");
                        //最初から"%"までの文字列を取得する
                        tmp = ch.substring(0, idx);
                        //"%"を付ける
                        tmp = "%" + tmp;
                        //配列に追加する
                        bytes.push(tmp);

                        //"%"以降の文字列を取得する
                        tmp = ch.substring(idx + 1);
                        //"%"を付ける
                        tmp = "%" + tmp;
                        //配列に追加する
                        bytes.push(tmp);
                    } else {
                        //2文字以上か確認する
                        if (ch.length > 2) {
                            //"%"以降の2文字を取得する
                            //tmp = "89"
                            tmp = ch.substring(0, 2);
                            //"%"を付ける
                            //tmp = "%89"
                            tmp = "%" + tmp;
                            //配列に追加する
                            //bytes = ("0: "%89")
                            bytes.push(tmp);

                            //3文字目以降を取得する
                            //tmp = "c"
                            tmp = ch.substring(2);
                            //文字のコードを取得する
                            //tmp = 99
                            tmp = tmp.charCodeAt(0);
                            //16進数に変換する
                            //tmp = "63"
                            tmp = tmp.toString(16);
                            //"%"を付ける
                            //tmp = "%63"
                            tmp = "%" + tmp;
                            //配列に追加する
                            //bytes = ("0: "%89","1: "%63")
                            bytes.push(tmp);
                        } else {
                            //"%"を付ける
                            tmp = "%" + tmp;
                            //配列に追加する
                            bytes.push(tmp);
                        }
                    }
                } else {
                    //文字を取得する
                    tmp = ch;
                    //配列に追加する
                    bytes.push(tmp);
                }
            } else {
                //文字を取得する
                tmp = ch;
                //配列に追加する
                bytes.push(tmp);
            }
        }

        //配列を返す
        return bytes;
    };

    // me.GetByteShiftJIS = function(str)
    // {
    // for (var i = 0; i < str.length; i++)
    // {
    // var ch = str.charAt(i);
    // var ch = c.charCodeAt(0);
    // // Shift_JIS: 0x0 ～ 0x80, 0xa0 , 0xa1 ～ 0xdf , 0xfd ～ 0xff
    // // Unicode : 0x0 ～ 0x80, 0xf8f0, 0xff61 ～ 0xff9f, 0xf8f1 ～ 0xf8f3
    // if ((ch >= 0x0 && ch < 0x81) || (ch == 0xf8f0) || (ch >= 0xff61 && ch < 0xffa0) || (ch >= 0xf8f1 && ch < 0xf8f4))
    // {
    // return false;
    // }
    // else
    // {
    // return true;
    // }
    // }
    //
    // };
    //20131030 luchao add end

    //---20150717 ysj add start---
    me.fncRoundDou = function (dblDou, intRoundKeta, strRoundKbn) {
        console.log("me.fncRoundDou");
        var dCom1 = 0.0;
        var dCom2 = 0.0;
        strRoundKbn = strRoundKbn.toString();
        var finalVal = 0.0;
        switch (strRoundKbn) {
            case "0":
                //切り捨て
                // finalVal = Math.floor(dblDou * Math.pow(10, intRoundKeta)) / Math.pow(10, intRoundKeta);
                finalVal =
                    Math.round(dblDou * Math.pow(10, intRoundKeta)) /
                    Math.pow(10, intRoundKeta);
                break;
            case "1":
                //四捨五入
                // finalVal = Math.floor((dblDou * Math.pow(10, intRoundKeta)) + ((dblDou < 0) ? 0.5 - 1 : 0.5)) / Math.pow(10, intRoundKeta);
                finalVal = Math.round(dblDou);
                break;
            case "2":
                //切り上げ
                dCom1 = dblDou * Math.pow(10, intRoundKeta);
                dCom2 = dblDou < 0 ? -0.999 : 0.999;
                // finalVal = Math.floor(dCom1 + dCom2) / Math.pow(10, intRoundKeta);
                finalVal =
                    Math.round(dCom1 + dCom2) / Math.pow(10, intRoundKeta);
                break;
            default:
                finalVal = dblDou;
                break;
        }
        return finalVal;
    };
    //---20150717 ysj add end---
    return me;
};

//20190909 zhangXL INS S
$.fn.inputFilter = function (inputFilter) {
    return this.on(
        "input keydown keyup mousedown mouseup select contextmenu drop",
        function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(
                    this.oldSelectionStart,
                    this.oldSelectionEnd
                );
            }
        }
    );
};
//20190909 zhangXL INS E
