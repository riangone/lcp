/**
 *  ペーパレス化支援システム
 * @author FCSDL　lijun
 */

Namespace.register("gdmz.PPRM.ODR_JScript");

gdmz.PPRM.ODR_JScript = function () {
    var me = new Object();
    //20170220 YIN INS S
    var clsComFnc = new gdmz.common.clsComFnc();
    clsComFnc.GSYSTEM_NAME = "ペーパーレス化支援システム";
    //20170220 YIN INS E
    /*
	 -------------------------------------------------------------
	 日付の変換　ﾛｽﾄﾌｫｰｶｽ
	 20080101 ----> 2008/01/01

	 使い方：Page_Load に次のように追加する
	 txtDate.Attributes.Add("onblur", "DateFOut(this)")
	 -------------------------------------------------------------
	 */
    me.DateFOut = function (obj) {
        if (!KinsokuMojiCheck(obj)) return false;
        var strDate;
        var strYear;
        var strMonth;
        var strDay;
        var i;
        var iLen;
        //20170220 YIN UPD S
        //strDate = obj.value;
        strDate = obj.val();
        //20170220 YIN UPD E
        iLen = strDate.length;
        if (iLen == 0) {
            return false;
        }

        strDate = strDate.replace(" ", "");
        for (i = 0; i < 10; i++) {
            strDate = strDate.replace("/", "");
        }

        strDay = strDate.substring(6, 8);

        strYear = strDate.substring(0, 4);

        strMonth = strDate.substring(4, 6);

        strDate = strYear + "/" + strMonth + "/" + strDay;

        nday = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        uday = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        var reg = /^(\d\d\d\d)\/(\d\d)\/(\d\d)$/;
        ret = strDate.match(reg);

        if (!ret) {
            //20170220 YIN UPD S
            // alert("日付以外が入力されています。");
            // obj.focus();
            clsComFnc.ObjFocus = obj;
            clsComFnc.FncMsgBox("W9999", "日付以外が入力されています。");
            //20170220 YIN UPD E
            return false;
        }

        yy = eval(reg.exec(strDate)[1]);
        mm = eval(reg.exec(strDate)[2]);
        dd = eval(reg.exec(strDate)[3]);

        if (mm < 1 || mm > 12) {
            //20170220 YIN UPD S
            // alert("日付以外が入力されています。");
            // obj.focus();
            clsComFnc.ObjFocus = obj;
            clsComFnc.FncMsgBox("W9999", "日付以外が入力されています。");
            //20170220 YIN UPD E
            return false;
        }

        if (chkUruYear(yy)) {
            if (dd < 1 || dd > uday[mm - 1]) {
                //20170220 YIN UPD S
                // alert("日付以外が入力されています。");
                // obj.focus();
                clsComFnc.ObjFocus = obj;
                clsComFnc.FncMsgBox("W9999", "日付以外が入力されています。");
                //20170220 YIN UPD E
                return false;
            }
        } else {
            if (dd < 1 || dd > nday[mm - 1]) {
                //20170220 YIN UPD S
                // alert("日付以外が入力されています。");
                // obj.focus();
                clsComFnc.ObjFocus = obj;
                clsComFnc.FncMsgBox("W9999", "日付以外が入力されています。");
                //20170220 YIN UPD E
                return false;
            }
        }
        //20170220 YIN UPD S
        //obj.value = strDate;
        obj.val(strDate);
        //20170220 YIN UPD E
        return true;
    };

    /*
	 -------------------------------------------------------------
	 閏年のチェックを行う
	 -------------------------------------------------------------
	 */
    me.chkUruYear = function (year) {
        if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) {
            return true;
        } else {
            return false;
        }
    };
    var chkUruYear = me.chkUruYear;

    /*
	 -------------------------------------------------------------
	 日付の変換　ﾛｽﾄﾌｫｰｶｽ
	 200801 ----> 2008/01
	 -------------------------------------------------------------
	 */
    me.YYYYMMFOut = function (obj) {
        if (!KinsokuMojiCheck(obj)) return false;
        var strDate;
        var strYear;
        var strMonth;
        var strDay;
        var rtn;
        var i;
        var iLen;

        strDate = obj.value;
        iLen = strDate.length;
        if (iLen == 0) {
            return false;
        }

        strDate = strDate.replace(" ", "");
        for (i = 0; i < 10; i++) {
            strDate = strDate.replace("/", "");
        }

        strDay = "01";
        strYear = strDate.substring(0, 4);
        strMonth = strDate.substring(4, 6);

        strDate = strYear + "/" + strMonth + "/" + strDay;
        strRet = strYear + "/" + strMonth;

        nday = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        uday = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        var reg = /^(\d\d\d\d)\/(\d\d)\/(\d\d)$/;
        ret = strDate.match(reg);

        if (!ret) {
            alert("日付以外が入力されています。");
            obj.trigger("focus");
            return false;
        }

        yy = eval(reg.exec(strDate)[1]);
        mm = eval(reg.exec(strDate)[2]);
        dd = eval(reg.exec(strDate)[3]);

        if (mm < 1 || mm > 12) {
            alert("日付以外が入力されています。");
            obj.trigger("focus");
            return false;
        }

        if (chkUruYear(yy)) {
            if (dd < 1 || dd > uday[mm - 1]) {
                alert("日付以外が入力されています。");
                obj.trigger("focus");
                return false;
            }
        } else {
            if (dd < 1 || dd > nday[mm - 1]) {
                alert("日付以外が入力されています。");
                obj.trigger("focus");
                return false;
            }
        }

        obj.value = strRet;
        return true;
    };

    /*
	 -------------------------------------------------------------
	 コンマと通貨記号を削除
	 -------------------------------------------------------------
	 */
    me.RemoveYenComma = function (strVal) {
        var strVal;
        var strTmp;
        var iLen;
        var iStartPos;

        strTmp = "";

        iLen = strVal.length;
        if (iLen == 0) {
            strVal = "";
            return;
        }

        var chTmp = parseInt(strVal);
        if (isNaN(parseInt(chTmp))) {
            return strVal;
        }

        iStartPos = 0;
        var ch = strVal.charAt(0);
        if (ch == "-") {
            var ch1 = parseInt(strVal.charAt(1), 10);
            if (isNaN(ch1)) {
                strTmp += "-";
                iStartPos = 2;
            }
        } else {
            if (isNaN(parseInt(ch, 10))) {
                iStartPos = 1;
            }
        }
        for (var i = iStartPos; i < iLen; i++) {
            ch = strVal.charAt(i);
            if (ch != ",") {
                strTmp += ch;
            }
        }

        return strTmp;
    };

    /*
	 -------------------------------------------------------------
	 金額を変換
	 12,345 ----> 12,345
	 -------------------------------------------------------------
	 */
    me.toMoney = function (obj) {
        if (!KinsokuMojiCheck(obj)) return false;
        var intval = obj.value;
        if (intval.length == 0) return false;

        var strNewval = intval.split(".");

        if (strNewval.length > 2) {
            alert("数字以外が入力されています。");
            obj.trigger("focus");
            return false;
        }

        var val = RemoveYenComma(strNewval[0]);

        val = val * 1;

        var bTmp = false;

        if (val == undefined) {
            alert("数字以外が入力されています。");
            obj.trigger("focus");
            return false;
        }

        if (isNaN(val)) {
            alert("数字以外が入力されています。");
            obj.trigger("focus");
            return false;
        }

        if (val < 0) {
            bTmp = true;
            val = val * -1;
        }

        var i = 1;
        var strVal = new String();
        val = val + "";

        while (i <= (val.length - 1) / 3) {
            strVal = "," + val.substring(val.length - 3, val.length) + strVal;
            val = val.substring(0, val.length - 3);
        }

        strVal = val + strVal;

        if (bTmp) {
            strVal = "-" + strVal;
        }

        if (intval.indexOf(".") != -1) {
            obj.value = strVal + "." + strNewval[1];
        } else {
            obj.value = strVal;
        }
        return true;
    };

    /*
	 -------------------------------------------------------------
	 禁則文字をチェック
	 -------------------------------------------------------------
	 */
    function KinsokuMojiCheck(obj) {
        var KinsokuMoji = "<>'";
        strChkVal = obj.val() + "";
        for (var i = 0; i < KinsokuMoji.length; i++) {
            if (strChkVal.indexOf(KinsokuMoji.charAt(i)) != -1) {
                //20170220 YIN UPD S
                //alert("禁則文字が入力されています。");
                clsComFnc.ObjFocus = obj;
                clsComFnc.FncMsgBox("W9999", "禁則文字が入力されています。");
                //20170220 YIN UPD E
                return false;
            }
        }
        return true;
    }
    me.KinsokuMojiCheck = KinsokuMojiCheck;

    //PHP移行時　add　e

    me.IsDate = function (strDate) {
        if (!KinsokuMojiCheckStr(strDate)) return false;

        var strDate;
        var strYear;
        var strMonth;
        var strDay;
        var i;
        var iLen;

        iLen = strDate.length;
        if (iLen == 0) {
            return false;
        }

        strDate = strDate.replace(" ", "");
        for (i = 0; i < 10; i++) {
            strDate = strDate.replace("/", "");
        }

        strDay = strDate.substring(6, 8);

        strYear = strDate.substring(0, 4);

        strMonth = strDate.substring(4, 6);

        strDate = strYear + "/" + strMonth + "/" + strDay;

        nday = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        uday = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        var reg = /^(\d\d\d\d)\/(\d\d)\/(\d\d)$/;
        ret = strDate.match(reg);

        if (!ret) {
            return false;
        }

        yy = eval(reg.exec(strDate)[1]);
        mm = eval(reg.exec(strDate)[2]);
        dd = eval(reg.exec(strDate)[3]);

        if (mm < 1 || mm > 12) {
            return false;
        }

        if (chkUruYear(yy)) {
            if (dd < 1 || dd > uday[mm - 1]) {
                return false;
            }
        } else {
            if (dd < 1 || dd > nday[mm - 1]) {
                return false;
            }
        }

        return true;
    };

    /*
	 -------------------------------------------------------------
	 禁則文字をチェック
	 -------------------------------------------------------------
	 */
    me.KinsokuMojiCheckStr = function (strChkVal) {
        var KinsokuMoji = "<>'";
        for (var i = 0; i < KinsokuMoji.length; i++) {
            if (strChkVal.indexOf(KinsokuMoji.charAt(i)) != -1) {
                return false;
            }
        }
        return true;
    };
    var KinsokuMojiCheckStr = me.KinsokuMojiCheckStr;

    //PHP移行時　add　e

    return me;
};
