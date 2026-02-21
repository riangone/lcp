/**
 * Namespaceを登録する
 * @alias  register
 * @param  {String} fullNS 登録するNamespaceのフルパス
 * @author FCSDL
 */
Namespace = new Object();
Namespace.register = function (fullNS) {
    //名前空間パスを"."で分割する
    //例えばfcsdl.indexをfcsdlとindex
    var nsArray = fullNS.split(".");
    var sEval = "";
    var sNS = "";

    for (var i = 0; i < nsArray.length; i++) {
        if (i != 0) {
            sNS = sNS + ".";
        }

        sNS = sNS + nsArray[i];

        //順番に名前空間Objectを生成する
        //例：最初fcsdl、次index

        sEval = sEval + "if (typeof(" + sNS + ") == 'undefined')";
        sEval = sEval + "{";
        sEval = sEval + sNS + " = new Object();";
        sEval = sEval + "}";

        eval(sEval);

        sEval = "";
    }
};
