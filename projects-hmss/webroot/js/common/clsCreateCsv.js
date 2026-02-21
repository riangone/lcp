/**
 * @author fcsdalian-028
 */
Namespace.register("gdmz.common.clsCreateCsv");

gdmz.common.clsCreateCsv = function () {
    var me = new Object();
    // var frmSCUriageMake = "";
    // var ajax = gdmz.common.ajax();
    me.id = "ClsCreateCsv";
    me.sys_id = "CLS";

    me.GS_OUTPUTLOG = {
        //OK:正常終了 NG:異常終了
        strState: "",
        //処理名
        strID: "",
        //処理内容
        strNaiyou: "",
        //処理開始システム日付
        strStartDate: "",
        //処理終了システム日付
        strEndDate: "",
        //作成CSVデータ名
        strDataNM: "",
        //作成件数
        lngCount: 0,
        //作成件数
        ErrCount: 0,
        //チェック件数   '2007/04/29 INS
        ChkCount: 0,
        //エラーメッセージ
        strErrMsg: "",
    };

    //**************************************************
    //   共用定数宣言
    //**************************************************
    me.strOrderFileName = "FDSCURI";
    //新･中注文書ﾌｧｲﾙ名
    me.strChangeFileName = "FDSCJYO";
    //条件変更注文書ﾌｧｲﾙ名

    //**********************************************************************
    //処 理 名：新中売上データ登録処理
    //関 数 名：fncSCURICreate
    //引    数：strSelDate:処理時間  objLog:ﾛｸﾞ情報
    //戻 り 値：無し
    //処理説明：新中売上データの登録処理を行う
    //**********************************************************************
    me.fncSCURICreate = function (
        objLog,
        frm1,
        strUpdPro,
        strDepend,
        strFromDate,
        strToDate
    ) {
        objLog = arguments[0] != undefined ? arguments[0] : me.GS_OUTPUTLOG;
        frm1 = arguments[1] != undefined ? arguments[1] : Object;
        strDepend = arguments[3] != undefined ? arguments[3] : "";
        strFromDate = arguments[4] != undefined ? arguments[4] : "";
        strToDate = arguments[5] != undefined ? arguments[5] : "";
        var strSCKbn = "";

        // try
        // {
        FrmCom = frm1;

        //CSV注文書データ
        objLog.strDataNM = "新車中古車ﾃﾞｰﾀ作成";

        //新中区分選択区分ｾｯﾄ

        //20140128 luchao 此处地方需要根据画面进行修正，正式开发后再进行修正
        if (frm1.rdoNew.Checked) {
            strSCKbn = "1";
        } else if (frm1.rdoUsed.Checked) {
            strSCKbn = "2";
        } else {
            strSCKbn = "";
        }
        //20140128 luchao 此处地方需要根据画面进行修正，正式开发后再进行修正

        //UC№エラーチェック
        var funcName = "fncSCURICreate";
        var url = FrmCom.sys_id + "/" + FrmCom.id + "/" + funcName;
        var init_mark = 1;
        // var UPDAPP = FrmCom.id.split("Frm")[1];
        var data = {
            objLog: objLog,
            strDepend: strDepend,
            strFromDate: strFromDate,
            strToDate: strToDate,
            strSCKbn: strSCKbn,
            //20140113 luchao 这个地方可能不对
            frm1: {
                lblCntNew: 1,
                lblCntUsed: 2,
                lblCntNewChg: 3,
                lblCntUsedChg: 4,
            },
            // frm1 :
            // {
            // lblCntNew : frm1.lblCntNew,
            // lblCntUsed : frm1.lblCntUsed,
            // lblCntNewChg : frm1.lblCntNewChg,
            // lblCntUsedChg : frm1.lblCntUsedChg
            // },
            strUpdPro: strUpdPro,
            //20140113 luchao 这个地方可能不对
        };
        frm1.ajax.send(url, data, init_mark);
    };

    return me;
};
