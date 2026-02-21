<?php
/**
 * 履歴：
 * --------------------------------------------------------------------------------------------
 * 日付                Feature/Bug             内容                               担当
 * YYYYMMDD           #ID                     XXXXXX                            FCSDL
 * 20150804           #1938            実行後一定時間経つと「error」が表示される        FANZHENGZHOU
 * 20150910           #2119 #2120 #2116       BUG                               LI
 * 20150922           #2160 #2159 #2124       BUG                               LI
 * 20151116           #2275				      BUG                               YIN
 * 20160111           #2326       			  BUG                               LI
 * --------------------------------------------------------------------------------------------
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use App\Model\R4\Component\ClsCreateCsv;
use Cake\Controller\ComponentRegistry;

class ClsCreateCsvComponent extends Component
{
    public $ClsComFnc;
    public $ClsComFncadd;
    public $Do_conn;
    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
        $this->ClsComFncadd = $registry->load('ClsComFncadd');
    }

    //Public Shared strDownLoadPath As String 'ダウンロードログ出力パス
    public $strgetdate = "";
    //開始日付
    public $strEndDate = "";
    //終了日付
    //Public Shared strLogPath As String    'LOG出力ﾌｧｲﾙﾊﾟｽ
    public $strLogName = "";
    //Log出力名
    public $strErrLogName = "";
    //Log出力名
    public $intDownLoadType = 0;
    //ダウンロードのﾀｲﾌﾟ
    public $intTableID = 0;
    //テーブルID
    public $postData = "";
    public $ClsCreateCsv = "";
    public $Do_Excute = "";
    public $OrderInfo = array();
    public $orderinfo = array();
    public $orderinfo_INT = array();
    public $subErrSpreadShowData = array();
    public $result = "";
    public $frm1 = array();

    public $strOrderFileName = "FDSCURI";
    //新･中注文書ﾌｧｲﾙ名
    public $strChangeFileName = "FDSCJYO";
    //条件変更注文書ﾌｧｲﾙ名
    public $strNewCsvPath = "";
    //新車CSV出力ﾌｧｲﾙﾊﾟｽ
    public $strUsedCsvPath = "";
    //中古CSV出力ﾌｧｲﾙﾊﾟｽ
    public $strNewChangeCsvPath = "";
    //新車条変CSV出力ﾌｧｲﾙﾊﾟｽ
    public $strUsedChangeCsvPath = "";
    //中古条変CSV出力ﾌｧｲﾙﾊﾟｽ
    public $strLogPath = "";
    //LOG出力ﾌｧｲﾙﾊﾟｽ
    public $strBackUpPath = "";
    //CSV BACKUP パス名
    public $strErrLogPath = "";
    //N5200CSVｴﾗｰパス名
    //---20160111 li INS S.
    public $strUPDYM_DATE = "";
    //---20160111 li INS E.
    public $objSw = "";

    public $FrmCom = array();

    public $GS_OUTPUTLOG = array(
        "strState" => '',
        //OK:正常終了 NG:異常終了
        "strID" => '',
        //処理名
        "strNaiyou" => '',
        //処理内容//---20150922 li UPD S.
        "strStartDate" => '',
        //処理開始システム日付
        "strEndDate" => '',
        //処理終了システム日付
        "strDataNM" => '',
        //作成CSVデータ名
        "lngCount" => '',
        //作成件数
        "ErrCount" => '',
        //作成件数
        "ChkCount" => '',
        //チェック件数   '2007/04/29 INS
        "strErrMsg" => '' //エラーメッセージ
    );

    //新･中･条注文書1

    public $OrderInfo1 = array(
        "ID" => "",
        "処理日" => "",
        "処理時間" => "",
        "予備" => "",
        "UCNO" => "",
        "AB" => "",
        "注文書NO2" => "",
        "売上部署" => "",
        "売上セールス" => "",
        "売上業者" => "",
        "サービス" => "",
        "売掛部署" => "",
        "契約店" => "",
        "登録店" => "",
        "認定特需ユーザーコード" => "",
        "登録日" => "",
        "売上日" => "",
        "経理日" => "",
        "解約日" => "",
        "車台" => "",
        "CARNO" => "",
        "銘柄" => "",
        "年製" => "",
        "指定類別型式指定" => "",
        "指定類別区分" => "",
        "車種コード" => "",
        "問合呼称" => "",
        "桁８コード" => "",
        "新車架装整理NO" => "",
        "用品A" => "",
        "用品C" => "",
        "用品H" => "",
        "用品S" => "",
        "用品予備" => "",
        "陸事" => "",
        "登録NO1" => "",
        "登録NO2" => "",
        "登録NO3" => "",
        "H59" => "",
        "車検年" => "",
        "くくりコード" => "",
        "認可型式" => "",
        "社内呼称" => "",
        "中古車初度年月" => "",
        "中古車入荷年月" => ""
    );

    //新･中･条注文書2
    public $OrderInfo2 = array(
        "区分01" => "",
        //--(1)
        "区分02" => "",
        //--(1)
        "区分03" => "",
        //--(1)
        "区分04" => "",
        //--(1)
        "区分05" => "",
        //--(1)
        "区分06" => "",
        //--(1)
        "区分07" => "",
        //--(1)
        "区分08" => "",
        //--(1)
        "区分09" => "",
        //--(1)
        "区分10" => "",
        //--(1)
        "区分11" => "",
        //--(1)
        "区分12" => "",
        //--(1)
        "区分13" => "",
        //--(1)
        "区分14" => "",
        //--(3)
        "区分15" => "",
        //--(1)
        "区分16" => "",
        //--(3)
        "区分17" => "",
        //--(1)
        "区分18" => "",
        //--(1)
        "区分19" => "",
        //--(1)
        "区分20" => "",
        //--(2)
        "区分21" => "",
        //--(1)
        "区分22" => "",
        //--(1)
        "区分23" => "",
        //--(1)
        "区分24" => "",
        //--(1)
        "区分25" => "",
        //--(2)
        "区分26" => "",
        //--(1)
        "区分27" => "",
        //--(1)
        "区分28" => "",
        //--(1)
        "区分29" => "",
        //--(1)
        "区分30" => "",
        //--(1)
        "区分31" => "",
        //--(1)
        "区分32" => "",
        //--(1)
        "区分33" => "",
        //--(1)
        "区分34" => "",
        //--(1)
        "区分35" => "",
        //--(1)
        "区分36" => "",
        //--(1)
        "区分37" => "",
        //--(1)
        "区分38" => "",
        //--(1)
        "区分39" => "",
        //--(1)
        "中古車売上親UCNO" => "",
        //--(10)
        "中古車売車整理NO" => "",
        //--(9)
        "条件変更赤黒" => "",
        //--(1)
        "条件変更内容" => "",
        //--(1)
        "条件変更年月日" => "",
        //--(8)
        "条件変更NO" => "",
        //--(7)
        "下取車整理NO1" => "",
        //--(9)
        "下取車整理NO2" => "",
        //--(9)
        "下取車整理NO3" => "",
        //--(9)
        "業者名" => "",
        //--(20)
        "予備1" => "",
        //--(7)
        "契約者名称カナ" => "",
        //--(27)
        "名義人区分" => "",
        //--(1)
        "予備2" => "",
        //--(1)
        "名義人誕生日" => "",
        //--(8)
        "名義人TEL" => "",
        //--(12)
        "名義人地区CD" => "",
        //--(13)
        "名義人軒番カナ" => "" //--(20)
    );

    //新･中･条注文書3
    public $OrderInfo3 = array(
        "名義人名称" => "",
        //--(40)
        "親2桁コード" => "",
        //--(2)
        "手形据置日数" => 0,
        //--(3)
        "車両価格" => 0,
        //--(9)
        "車両値引" => 0,
        //--(9)
        "車両注文書原価" => 0,
        //--(9)
        "車両拠点原価" => 0,
        //--(9)
        "車両新車車両部署別用原価" => 0,
        //--(9)
        "車両消費税率" => 0,
        //--(2)
        "車両消費税額" => 0,
        //--(9)
        "添付品定価" => 0,
        //--(9)
        "添付品値引" => 0,
        //--(9)
        "添付品契約" => 0,
        //--(9)
        "添付品原価" => 0,
        //--(9)
        "添付品消費税" => 0,
        //--(9)
        "特別仕様3定価" => 0,
        //--(9)
        "特別仕様3値引" => 0,
        //--(9)
        "特別仕様3契約" => 0,
        //--(9)
        "特別仕様3原価" => 0,
        //--(9)
        "特別仕様3消費税" => 0,
        //--(9)
        "特別仕様6定価" => 0,
        //--(9)
        "特別仕様6値引" => 0,
        //--(9)
        "特別仕様6契約" => 0,
        //--(9)
        "特別仕様6原価" => 0,
        //--(9)
        "特別仕様6消費税" => 0,
        //--(9)
        "割賦手数料契約" => 0,
        //--(9)
        "割賦手数料基準" => 0,
        //--(9)
        "割賦手数料消費税率" => "",
        //--(2)
        "割賦手数料消費税額" => 0,
        //--(9)
        "登録諸費用3契約" => 0,
        //--(9)
        "登録諸費用3基準" => 0,
        //--(9)
        "登録諸費用3契約NEW" => 0,
        //--(9)
        "登録諸費用3基準NEW" => 0,
        //--(9)
        "登録諸費用3消費税" => 0,
        //--(9)
        "預り法廷費用" => 0,
        //--(9)
        "税金保険料" => 0,
        //--(9)
        "残債" => 0,
        //--(9)
        "支払金合計" => 0,
        //--(9)
        "支払条件下取価格" => 0,
        //--(9)
        "支払条件下取車消費税" => 0,
        //--(9)
        "支払条件頭金" => 0,
        //--(9)
        "支払条件登録諸費用" => 0,
        //--(9)
        "支払条件中古車負担金" => 0,
        //--(9)
        "支払条件手形回数" => "",
        //--(2)
        "支払条件手形金額" => 0,
        //--(9)
        "支払条件ｸﾚｼﾞｯﾄ回数" => "",
        //--(2)
        "支払条件ｸﾚｼﾞｯﾄ金額" => 0, //--(9)
    );

    //新･中･条注文書4
    public $OrderInfo4 = array(
        "ｸﾚｼﾞｯﾄ会社" => "",
        //--(2)
        "ｸﾚｼﾞｯﾄ承認NO" => "",
        //--(20)
        "下取ﾘｻｲｸﾙ料" => 0,
        //--(9)
        "割賦元金" => 0,
        //--(9)
        "下取者買取価格" => 0,
        //--(9)
        "下取者査定価格" => 0,
        //--(9)
        "税金自動車税" => 0,
        //--(9)
        "税金車両取得税" => 0,
        //--(9)
        "税金ｴｱｺﾝ取得税" => 0,
        //--(9)
        "税金ｽﾃﾚｵ取得税" => 0,
        //--(9)
        "税金重量税" => 0,
        //--(9)
        "税金消費税" => 0,
        //--(9)
        "自賠責指定" => "",
        //--(1)
        "自賠責会社" => "",
        //--(2)
        "自賠責自動車種類" => "",
        //--(2)
        "自賠責色コード" => "",
        //--(1)
        "自賠責月数" => 0,
        //--(2)
        "自賠責保険料" => 0,
        //--(9)
        "任意保険料" => 0,
        //--(9)
        "販売手数料課税非課税" => "",
        //--(1)
        "販売手数料支払先コード" => "",
        //--(5)
        "販売手数料額" => 0,
        //--(9)
        "販売消費税" => 0,
        //--(9)
        "予備" => "",
        //--(1)
        "登録諸費用3検査" => 0,
        //--(9)
        "登録諸費用3持込車検" => 0,
        //--(9)
        "登録諸費用3車庫証明" => 0,
        //--(9)
        "登録諸費用3納車費用" => 0,
        //--(9)
        "登録諸費用3下取諸手続" => 0,
        //--(9)
        "登録諸費用3査定料" => 0,
        //--(9)
        "登録諸費用3字光式" => 0,
        //--(9)
        "登録諸費用3その他" => 0,
        //--(9)
        "パックDE753" => 0,
        //--(9)    '2007/07/06 INS
        "パックDEメンテ" => 0,
        //--(9)    '2007/07/06 INS
        "預り法定費用検査" => 0,
        //--(9)
        "預り法定費用持込車検" => 0,
        //--(9)
        "預り法定費用車庫証明" => 0,
        //--(9)
        "預り法定費用下取" => 0,
        //--(9)
        "本部負担金" => 0,
        //--(9)
        "打込金収入手数料" => 0,
        //--(9)
        "打込金申請奨励金" => 0,
        //--(9)
        "割賦手数料差額" => 0,
        //--(9)
        "その他紹介料" => 0,
        //--(9)
        "車両F号限界利益" => 0,
        //--(9)
        "ﾍﾟﾅﾙﾃｨ" => 0,
        //--(9)
        "営業外収益" => 0,
        //--(9)
        "最終損益" => 0,
        //--(9)
        "特約店契約基本ﾏｰｼﾞﾝ" => 0,
        //--(9)
        "特約店契約累進ﾏｰｼﾞﾝ" => 0,
        //--(9)
        "特約店契約拡販奨励金" => 0,
        //--(9)
        "特約店契約特別価格" => 0,
        //--(9)
        "原価標準原価" => 0,
        //--(9)
        "原価下取車売上仕切" => 0 //--(9)
    );

    //新･中･条注文書5
    public $OrderInfo5 = array(
        "中古車売車下取価格" => 0,
        //--9(9)
        "中古車売車査定" => 0,
        //--9(9)
        "中古車再生見積" => 0,
        //--9(9)
        "中古車諸掛" => 0,
        //--9(9)
        "中古車売車査定ソカイ" => 0,
        //--9(9)
        "中古車未経過自動車税金額" => 0,
        //--9(9)
        "中古車未経過自動車税消費税" => 0,
        //--9(9)
        "中古車未経過自賠責金額" => 0,
        //--9(9)
        "中古車未経過自賠責消費税" => 0,
        //--9(9)
        "入庫約束" => "",
        //--X(1)
        "DM送付" => "",
        //--X(1)
        "キョウシンカイ顧客" => "",
        //--X(1)
        "キョウシンカイ紹介" => "",
        //--X(1)
        "キョウシンカイコウケン" => "",
        //--X(1)
        "値引率" => 0,
        //--9(2.2)
        "基準値引率" => 0,
        //--9(2.2)
        "公正証書" => 0,
        //--9(9)
        "JAF" => 0,
        //--9(9)
        "KB" => 0,
        //--9(9)
        "預託区分" => "",
        //--X(1)
        "ﾘｻｲｸﾙ預託金" => 0,
        //--S9(9)
        "ﾘｻｲｸﾙ資金管理費" => 0,
        //--S9(9)
        "FIL" => "" //--X(163)
    );

    //新･中･条注文書6
    public $OrderInfo6 = array(
        "新中区分" => "",
        //--X(1)
        "データ区分" => "",
        //--X(1)
        "売上台数" => "",
        //--9(1)
        "登録台数" => "",
        //--9(1)
        "注文書NO" => "",
        //--X(10)
        "作成日" => "",
        //--X(10)
        "更新日" => "",
        //--X(10)
        //2009/12/21 INS Start
        //'''"UC件数FLG" => '' ,                                 //--X(10)
        //'''"未実績FLG" => '' ,                                 //--X(10)
        //'''"登録実績FLG" => '' ,                               //--X(10)
        //'''"他契自登FLG" => '' ,                               //--X(10)
        //'''"自契他登FLG" => '' ,                               //--X(10)
        //'''"メーカーFLG" => '' ,                               //--X(10)
        //'''"福祉FLG" => '' ,                                   //--X(10)
        //'''"社名FLG" => '' ,                                   //--X(10)
        //'''"売上実績FLG" => '' ,                               //--X(10)
        //'''"ﾘｰｽFLG" => '' ,                                    //--X(10)
        //'''"サービスカーFLG" => '' ,                           //--X(10)
        //'''"再売FLG" => '' ,                                   //--X(10)
        //'''"カルテFLG" => '' ,                                 //--X(10)
        //'''"登録_登録区分_FLG" => '' ,                         //--X(10)
        //'''"売上_登録区分_FLG" => '' ,                         //--X(10)
        //'''"その他_登録区分_FLG" => '' ,                       //--X(10)
        "UC件数" => "",
        //--X(10)
        "未実績台数" => "",
        //--X(10)
        "登録実績台数" => "",
        //--X(10)
        "他契自登台数" => "",
        //--X(10)
        "自契他登台数" => "",
        //--X(10)
        "メーカー台数" => "",
        //--X(10)
        "福祉台数" => "",
        //--X(10)
        "社名台数" => "",
        //--X(10)
        "売上実績台数" => "",
        //--X(10)
        "ﾘｰス台数" => "",
        //--X(10)
        "サービスカー台数" => "",
        //--X(10)
        "再売台数" => "",
        //--X(10)
        "カルテ台数" => "",
        //--X(10)
        "登録_登録区分_台数" => "",
        //--X(10)
        "売上_登録区分_台数" => "",
        //--X(10)
        "その他_登録区分_台数" => "",
        //--X(10)
        "解約台数" => "" //--X(10)
        //2009/12/21 INS End
    );

    //新･中･条下取･氏名A
    public $OrderInfoA = array(
        "ID" => "",
        //--X(2)
        "処理日" => "",
        //--X(8)
        "処理時間" => "",
        //--X(6)
        "予備1" => "",
        //--X(4)
        "UCNO" => "",
        //--X(10)
        "AB" => "",
        //--X(1)
        "注文書NO2" => "",
        //--X(7)
        "予備2" => "",
        //--X(2)
        "下取車１台目整理NO" => "",
        //--X(9)
        "下取車１台目親車注文書NO" => "",
        //--X(7)
        "下取車１台目売上注文書NO" => "",
        //--X(7)
        "下取車１台目下取SW" => "",
        //--X(1)
        "下取車１台目買下理由" => "",
        //--X(2)
        "下取車１台目現地仕切" => "",
        //--X(1)
        "下取車１台目銘柄" => "",
        //--X(3)
        "下取車１台目西暦年制" => "",
        //--X(4)
        "下取車１台目車検証型式" => "",
        //--X(15)
        "下取車１台目CARNO" => "",
        //--X(8)
        "下取車１台目車名" => "",
        //--X(12)
        "下取車１台目型式指定" => "",
        //--X(4)
        "下取車１台目類別区分" => "",
        //--X(3)
        "下取車１台目登録年月日" => "",
        //--X(8)
        "下取車１台目陸事名称" => "",
        //--X(8)
        "下取車１台目登録NO1" => "",
        //--X(8)
        "下取車１台目登録NO2" => "",
        //--X(1)
        "下取車１台目登録NO3" => "",
        //--X(4)
        "下取車１台目H59" => "",
        //--X(3)
        "下取車１台目下取価格" => 0,
        //--S9(9)
        "下取車１台目査定価格" => 0,
        //--S9(9)
        "下取車１台目実査定価格" => 0,
        //--S9(9)
        "下取車１台目消費税率" => 0,
        //--X(2)
        "下取車１台目消費税額" => 0,
        //--S9(9)
        "下取車１台目ﾘｻｲｸﾙ預託金" => 0,
        //--S9(9)
        "下取車１台目ﾘｻｲｸﾙ資金管理料" => 0,
        //--S9(9)
        "下取車１台目預託区分" => "",
        //--X(1)
        "下取車１台目手放区分" => "",
        //--X(1)
        "予備3" => "" //--X(18)
    );

    //新･中･条下取･氏名B
    public $OrderInfoB = array(
        "下取車２台目整理NO" => "",
        //--X(9)
        "下取車２台目親車注文書NO" => "",
        //--X(7)
        "下取車２台目売上注文書NO" => "",
        //--X(7)
        "下取車２台目下取SW" => "",
        //--X(1)
        "下取車２台目買下理由" => "",
        //--X(2)
        "下取車２台目現地仕切" => "",
        //--X(1)
        "下取車２台目銘柄" => "",
        //--X(3)
        "下取車２台目西暦年制" => "",
        //--X(4)
        "下取車２台目車検証型式" => "",
        //--X(15)
        "下取車２台目CARNO" => "",
        //--X(8)
        "下取車２台目車名" => "",
        //--X(12)
        "下取車２台目型式指定" => "",
        //--X(4)
        "下取車２台目類別区分" => "",
        //--X(3)
        "下取車２台目登録年月日" => "",
        //--X(8)
        "下取車２台目陸事名称" => "",
        //--X(4)
        "下取車２台目登録NO1" => "",
        //--X(8)
        "下取車２台目登録NO2" => "",
        //--X(1)
        "下取車２台目登録NO3" => "",
        //--X(4)
        "下取車２台目H59" => "",
        //--X(3)
        "下取車２台目下取価格" => 0,
        //--S9(9)
        "下取車２台目査定価格" => 0,
        //--S9(9)
        "下取車２台目実査定価格" => 0,
        //--S9(9)
        "下取車２台目消費税率" => 0,
        //--X(2)
        "下取車２台目消費税額" => 0,
        //--S9(9)
        "下取車２台目ﾘｻｲｸﾙ預託金" => 0,
        //--S9(9)
        "下取車２台目ﾘｻｲｸﾙ資金管理料" => 0,
        //--S9(9)
        "下取車２台目預託区分" => "",
        //--X(1)
        "下取車２台目手放区分" => "",
        //--X(1)
        "予備" => "" //--X(18)
    );

    //新･中･条下取･氏名C
    public $OrderInfoC = array(
        "下取車３台目整理NO" => "",
        //--X(9)
        "下取車３台目親車注文書NO" => "",
        //--X(7)
        "下取車３台目売上注文書NO" => "",
        //--X(7)
        "下取車３台目下取SW" => "",
        //--X(1)
        "下取車３台目買下理由" => "",
        //--X(2)
        "下取車３台目現地仕切" => "",
        //--X(1)
        "下取車３台目銘柄" => "",
        //--X(3)
        "下取車３台目西暦年制" => "",
        //--X(4)
        "下取車３台目車検証型式" => "",
        //--X(15)
        "下取車３台目CARNO" => "",
        //--X(8)
        "下取車３台目車名" => "",
        //--X(12)
        "下取車３台目型式指定" => "",
        //--X(4)
        "下取車３台目類別区分" => "",
        //--X(3)
        "下取車３台目登録年月日" => "",
        //--X(8)
        "下取車３台目陸事名称" => "",
        //--X(4)
        "下取車３台目登録NO1" => "",
        //--X(8)
        "下取車３台目登録NO2" => "",
        //--X(1)
        "下取車３台目登録NO3" => "",
        //--X(4)
        "下取車３台目H59" => "",
        //--X(3)
        "下取車３台目下取価格" => 0,
        //--S9(9)
        "下取車３台目査定価格" => 0,
        //--S9(9)
        "下取車３台目実査定価格" => 0,
        //--S9(9)
        "下取車３台目消費税率" => 0,
        //--X(2)
        "下取車３台目消費税額" => 0,
        //--S9(9)
        "下取車３台目ﾘｻｲｸﾙ預託金" => 0,
        //--S9(9)
        "下取車３台目ﾘｻｲｸﾙ資金管理料" => 0,
        //--S9(9)
        "下取車３台目預託区分" => "",
        //--X(1)
        "下取車３台目手放区分" => "",
        //--X(1)
        "予備" => "",
        //--X(18)
        "予備2" => "" //--X(05)
    );

    //新･中･条下取･氏名D
    public $OrderInfoD = array(
        "契約者キー名寄せ" => "",
        //--X(30)
        "契約者キー地区コード" => "",
        //--X(13)
        "契約者キーTEL" => "",
        //--X(12)
        "契約者住所軒番漢字" => "",
        //--X(30)
        "契約者住所通称地漢字" => "",
        //--X(30)
        "契約者名称1漢字" => "",
        //--X(40)
        "契約者名称2漢字" => "",
        //--X(30)
        "契約者住所カナ" => "",
        //--X(20)
        "契約者名称カナ" => "",
        //--X(40)
        "契約者郵便番号" => "",
        //--X(7)
        "契約者住所１" => "",
        //--X(44)
        "契約者住所２" => "",
        //--X(44)
        "契約者住所３" => "",
        //--X(44)
        "契約者カテゴリーランク" => "" //--X(1) '2009/12/21 INS
    );

    //新･中･条下取･氏名E
    public $OrderInfoE = array(
        "名義人キー名寄せ" => "",
        //--X(30)
        "名義人キー地区コード" => "",
        //--X(13)
        "名義人キーTEL" => "",
        //--X(12)
        "名義人住所軒番漢字" => "",
        //--X(30)
        "名義人住所通称地漢字" => "",
        //--X(30)
        "名義人名称1漢字" => "",
        //--X(40)
        "名義人名称2漢字" => "",
        //--X(30)
        "名義人住所カナ" => "",
        //--X(20)
        "名義人名称カナ" => "",
        //--X(40)
        "FIL" => "",
        //--X(9)
        "名義人郵便番号" => "",
        //--X(7)
        "名義人住所１" => "",
        //--X(44)
        "名義人住所２" => "",
        //--X(44)
        "名義人住所３" => "",
        //--X(44)
        "名義人カテゴリーランク" => "" //--X(1)  '009/12/21 INS
    );

    //Function beforeFilter
    function initialize($config): void
    {
        $this->OrderInfo = array(
            "OrderInfo1" => $this->OrderInfo1,
            "OrderInfo2" => $this->OrderInfo2,
            "OrderInfo3" => $this->OrderInfo3,
            "OrderInfo4" => $this->OrderInfo4,
            "OrderInfo5" => $this->OrderInfo5,
            "OrderInfo6" => $this->OrderInfo6,
            "OrderInfoA" => $this->OrderInfoA,
            "OrderInfoB" => $this->OrderInfoB,
            "OrderInfoC" => $this->OrderInfoC,
            "OrderInfoD" => $this->OrderInfoD,
            "OrderInfoE" => $this->OrderInfoE
        );
        $this->orderinfo = $this->OrderInfo;
        $this->orderinfo_INT = $this->OrderInfo;
        $this->ClsCreateCsv = new ClsCreateCsv();
        $this->Do_conn = $this->ClsCreateCsv->Do_conn();

    }

    //**********************************************************************
    //処 理 名：前回取得日取得
    //関 数 名：fncGetBEFGETDT
    //引    数：strTableId：ﾃｰﾌﾞﾙID　
    //戻 り 値：取得日
    //処理説明：ﾃﾞｰﾀ受信ﾃｰﾌﾞﾙより前回CSV作成日付を取得する
    //**********************************************************************
    public function fncGetBEFGETDT($strTableId)
    {
        //ﾃﾞｰﾀﾘｰﾀﾞ(注文データ）
        // $objDr = "";
        $strMsg = "";
        $strGetDate = "";
        $result = "";
        $Do_Excute = [];

        try {
            $Do_Excute = $this->ClsCreateCsv->fncGetBEFGETDT($strTableId);
            if ($Do_Excute['result'] == false) {
                throw new \Exception($Do_Excute["data"]);
            }
            if (count((array) $Do_Excute['data']) > 0) {
                $strGetDate = $this->ClsComFnc->FncNv($result['data']['BEF_CSVPUT_DT']);
            }
            $result['result'] = TRUE;
            $result['data'] = $strGetDate;
        } catch (\Exception $e) {
            $strMsg = "clsCSVCreate " . "\r\n" . "fncGetBEFGETDT " . "\r\n" . $e->getMessage();
            $result['result'] = FALSE;
            $result['data'] = $strMsg;
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncStartLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************

    public function FncStartLog($strFileNM, &$objLog)
    {
        $strOut = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        //--------
        //出力処理
        //--------
        $strOut .= $objLog['strID'] . " ";
        $strOut .= "START ";
        $strOut .= $objLog['strStartDate'] . " ";
        $strOut .= $objLog['strState'] . " ";
        $strOut .= "\r\n";
        //ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        if (isset($this->objSw)) {
            unset($this->objSw);
        }
        return true;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncEndLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function FncEndLog($strFileNM, &$objLog)
    {

        $strOut = "";
        $this->objSw = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        //--------
        //出力処理
        //--------
        $strOut .= $objLog['strID'] . " ";
        $strOut .= "END   ";
        $strOut .= $objLog['strEndDate'] . " ";
        if ($objLog['strState'] == "NG") {
            $strOut .= "処理が異常終了しました。";
        } else {
            $strOut .= "処理が正常に終了しました。";
        }
        $strOut .= "\r\n";

        //終了情報ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        if (isset($this->objSw)) {
            unset($this->objSw);
        }
        return true;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncOutLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function fncOutLog($strFileNM, &$objLog)
    {
        $strOut = "";
        $this->objSw = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        //--------
        //出力処理
        //--------
        $strOut .= "     ";
        $strOut .= $objLog['strDataNM'] . " ";
        $strOut .= $objLog['lngCount'] . "件 ";
        $strOut .= $objLog['strState'] . " ";
        $strOut .= $objLog['strErrMsg'] . " ";
        $strOut .= "\r\n";

        //終了情報ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        if (isset($this->objSw)) {
            unset($this->objSw);
        }
        return true;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncErrLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function FncErrLog($strFileNM, &$objLog)
    {
        $strOut = "";
        $this->objSw = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        //--------
        //出力処理
        //--------
        $strOut .= "     ";
        $strOut .= $objLog['strDataNM'] . " ";
        $strOut .= $objLog['strErrMsg'] . " ";
        $strOut .= "\r\n";

        //終了情報ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        if (isset($this->objSw)) {
            unset($this->objSw);
        }
        return true;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncErrLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ダウンロード時のログファイルを出力する
    //**********************************************************************
    public function fncDownLoadLog($strFileNM, &$objLog, $intDataKind = 3)
    {
        $this->objSw = "";
        //ストリームライター
        $strOut = "";
        //ストリングビルダー

        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');
        //--------
        //出力処理
        //--------

        //データが存在する場合
        //While objDr.Read
        //初期化

        switch ($intDataKind) {
            case 0:
                //開始ログ
                $strOut .= $objLog['strID'] . " ";
                $strOut .= "Start ";
                $strOut .= $objLog['strStartDate'] . " ";
                break;
            case 1:
                //終了ログ
                $strOut .= $objLog['strID'] . " ";
                $strOut .= "END ";
                $strOut .= $objLog['strEndDate'] . " ";
                break;
            case 2:
                //
                $strOut .= "    ";
                $strOut .= $objLog['strDataNM'];
                break;
            case 3:
                //処理ごとのログ
                $strOut .= "        ";
                $strOut .= $objLog['strDataNM'] . " ";
                $strOut .= $objLog['lngCount'] . " ";
                $strOut .= $objLog['strState'] . " ";
                $strOut .= $this->ClsComFnc->FncGetSysDate("Y/m/d H:i:s");
                break;
        }
        $strOut .= "\r\n";
        //ファイル出力
        fwrite($this->objSw, $strOut);
        //終了ログ出力後に改行
        if ($intDataKind == 1) {
            fwrite($this->objSw, "");
        }
        fclose($this->objSw);
        //正常終了
        if (isset($this->objSw)) {
            unset($this->objSw);
        }
        return true;
    }

    //**********************************************************************
    //処 理 名：入力ﾁｪｯｸ
    //関 数 名：fncOutput
    //引    数：strFileNM (I)ﾊﾟｽ
    //戻 り 値：エラー番号
    //処理説明：出力先のﾊﾟｽﾁｪｯｸ
    //**********************************************************************
    public function fncOutChk($strFileNm, &$strErrMsg)
    {
        $intHitNum = "";
        //ヒットした位置
        $strPath = "";
        //パス名

        //出力先が未入力の場合はｴﾗｰ
        if (rtrim($strFileNm) == "") {
            $strErrMsg = "出力先";
            return "W0001";
        } else {
            //パス名
            if (rtrim($strFileNm) != "") {
                //最後に出現する"\"の位置をintHitNumに代入
                $intHitNum = strrpos($strFileNm, '\\');
                if ($intHitNum == false) {
                    //'"\"が存在しない場合は、出力先の文字数を代入
                    $intHitNum = $this->ClsComFnc->StringLength($strFileNm);
                }
                //フォルダのパスを求める
                $strPath = mb_substr($strFileNm, 0, $intHitNum);
            }
            //フォルダーが存在するかどうかのﾁｪｯｸ
            if (!$this->ClsComFnc->FncFileExists($strPath)) {
                $strErrMsg = "";
                return "W0015";
            }
        }
    }

    //20140121 luchao add start
    //**********************************************************************
    //処 理 名：新中売上データ登録処理
    //関 数 名：fncSCURICreate
    //引    数：無し
    //戻 り 値：無し
    //処理説明：新中売上データの登録処理を行う
    //**********************************************************************

    public function fncSCURICreate()
    {
        $strFromDate = "";
        $strToDate = "";
        $strSCKbn = "";
        $objDrErr = "";
        // $lngcnt = 0;
        // $NewData = array();
        // $UsedData = array();
        // $NewChangeData = array();
        // $UsedChangeData = array();
        // $strDATE = "";
        // $strYMD = "";
        // $strTIME = "";
        // $objDr = array();
        // $strId = "";
        $strErrMsg = array();

        $result = [];

        try {
            if (isset($_POST['data'])) {
                $this->postData = $_POST['data'];
            }
            if ($this->postData == "") {
                $this->result = array(
                    'result' => FALSE,
                    'data' => 'param error'
                );
            } else {
                $objLog = $_POST['data']['objLog'];
                $strFromDate = $_POST['data']['strFromDate'];
                $strToDate = $_POST['data']['strToDate'];
                $strSCKbn = $_POST['data']['strSCKbn'];
                // $strUpdPro = $_POST['data']['strUpdPro'];
                $this->frm1 = $_POST['data']['frm1'];

                // //$this -> ClsCreateCsv = new ClsCreateCsv();
                $this->Do_Excute = $this->ClsCreateCsv->fncChkUCNO($strFromDate, $strToDate, $strSCKbn);

                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 1);
                }

                $objDrErr = $this->Do_Excute['data'];

                for ($i = 0; $i < count($objDrErr); $i++) {
                    //1列目
                    $objLog['strErrMsg'] = "　　注文書№=" . $this->ClsComFnc->FncNv($objDrErr[$i]["CMN_NO"]);
                    $objLog['strErrMsg'] .= " UC_NO=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["UC_NO"]), 12);
                    $objLog['strErrMsg'] .= " 条件変更日=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["JKN_HKD"]), 8);
                    $objLog['strErrMsg'] .= " 条件変更稟議書ＮＯ=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["HNB_JKN_HKO_RIN_LST_NO"]), 8);
                    $objLog['strErrMsg'] .= " 解約日=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["CEL_DT"]), 8);

                    $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                    //2列目
                    $objLog['strErrMsg'] = $this->ClsComFnc->FncNv($objDrErr[$i]["ERR_MSG"]);
                    $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                    //3列目
                    $objLog['strErrMsg'] = " ";
                    $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                    //チェック件数をセット
                    $objLog['ChkCount'] = $objLog['ChkCount'] + 1;
                }

                return;
                //データリーダの開放
                if (isset($objDrErr)) {
                    unset($objDrErr);
                }
                //抽出対象注文書番号ﾃｰﾌﾞﾙを削除する

                $this->Do_Excute = $this->ClsCreateCsv->fncDeleteWK_CHMNO();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 1);
                }

                //抽出対象注文書番号ﾃｰﾌﾞﾙを作成する
                // $this->Do_Excute = $this->ClsCreateCsv->fncInsertWK_CHMNO_UCNO($strDepend, $strFromDate, $strToDate, $strSCKbn);
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 1);
                } else {
                    $lngcnt = $this->Do_Excute['number_of_rows'];
                }
                // $objLog["lngCount"] = $lngcnt;
                if ($lngcnt <= 0) {
                    $objLog["strState"] = "NG";
                    $objLog["lngCount"] = 0;
                    $objLog["strErrMsg"] = "該当ﾃﾞｰﾀは存在しません";
                }
                $objLog["lngCount"] = $lngcnt;

                //---------------------------------------------------------
                //データリーダに格納
                //---------------------------------------------------------
                $this->Do_Excute = $this->ClsCreateCsv->fncChuSelect();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 1);
                }
                // $objDr = $this->Do_Excute['data'];
                if ($this->Do_Excute['row'] > 0) {
                    $NewData = array();
                    $UsedData = array();
                    $NewChangeData = array();
                    $UsedChangeData = array();
                    $strDATE = $this->ClsComFnc->FncGetSysDate('Ymdhis');
                    $strYMD = mb_substr($strDATE, 0, 8);
                    $strTIME = mb_substr($strDATE, 8, 6);
                    //データが存在する場合
                    for ($i = 0; $i < $this->Do_Excute['row']; $i++) {
                        $this->orderinfo = $this->orderinfo_INT;
                        $this->orderinfo['OrderInfo1']['処理日'] = $strYMD;
                        $this->orderinfo['OrderInfo1']['処理時間'] = $strTIME;
                        //---------------------------------------------------------
                        //注文データ情報編集
                        //---------------------------------------------------------

                        $this->Do_Excute = $this->FncOrderInfoEDIT($objDr, $strDepend, $strId, $objLog, $strErrMsg);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data'], 2);
                        }
                        //---------------------------------------------------------
                        //注文下取データ情報編集
                        //---------------------------------------------------------
                        if ($this->orderinfo['OrderInfo1']['ID'] !== "") {
                            $this->orderinfo['OrderInfoA']['処理日'] = $strYMD;
                            $this->orderinfo['OrderInfoA']['処理時間'] = $strTIME;
                            $this->Do_Excute = $this->FncOldCarInfoEDIT($objDr, $orderinfo, $objLog, $strErrMsg);

                            if (!$this->Do_Excute['result']) {
                                throw new \Exception($this->Do_Excute['data'], 2);
                            }
                            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                                if ($strId == "1") {
                                    //新車情報
                                    array_push($NewData, $orderinfo);
                                } else {
                                    //新車条件変更情報
                                    array_push($NewChangeData, $orderinfo);
                                }
                            } else {
                                if ($strId == "1") {
                                    //中古車情報
                                    array_push($UsedData, $orderinfo);
                                } else {
                                    //中古車条件変更情報
                                    array_push($UsedChangeData, $orderinfo);
                                }
                            }
                        }
                    }
                }
                //20140213 luchao 此处代码在js部分处理，此处只进行返回值处理
                // frm1.lblCntNewA.Text = " /" & NewData.Count.ToString("0").PadLeft(4)
                // frm1.lblCntNewChgA.Text = " /" & NewChangeData.Count.ToString("0").PadLeft(4)
                // frm1.lblCntUsedA.Text = " /" & UsedData.Count.ToString("0").PadLeft(4)
                // frm1.lblCntUsedChgA.Text = " /" & UsedChangeData.Count.ToString("0").PadLeft(4)
                // Application.DoEvents()
                $result['lblCnt'] = array(
                    'NewDataA' => " /" . $this->ClsComFnc->mb_str_pad(count($NewData), 4, " ", STR_PAD_LEFT),
                    "NewChangeDataA" => " /" . $this->ClsComFnc->mb_str_pad(count($NewChangeData), 4, " ", STR_PAD_LEFT),
                    "UsedDataA" => " /" . $this->ClsComFnc->mb_str_pad(count($UsedData), 4, " ", STR_PAD_LEFT),
                    "UsedChangeDataA" => " /" . $this->ClsComFnc->mb_str_pad(count($UsedChangeData), 4)
                );
                //20140213 luchao 此处代码在js部分处理，此处只进行返回值处理

                //---------------------------------------------------------
                //新車情報出力
                //---------------------------------------------------------
                //2006/12/11 UPD 引数追加　Start
                if (count($NewData) > 0) {
                    $this->Do_Excute = $this->fncSCURITouroku($NewData, $this->frm1['lblCntNew'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //中古車情報出力
                //---------------------------------------------------------
                if (count($UsedData) > 0) {
                    $this->Do_Excute = $this->fncSCURITouroku($UsedData, $this->frm1['lblCntUsed'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //新車条件変更情報出力
                //---------------------------------------------------------
                if (count($UsedData) > 0) {
                    $this->Do_Excute = $this->fncSCURITouroku($NewChangeData, $this->frm1['lblCntNewChg'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //中古車条件変更情報出力
                //---------------------------------------------------------
                if (count($UsedData) > 0) {
                    $this->Do_Excute = $this->fncSCURITouroku($UsedChangeData, $this->frm1['lblCntUsedChg'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

            }

        } catch (\Exception $e) {
            if ($e->getCode() == 1) {
                $strErrMsg = "clsCreateCsv" . "\r\n" . "fncSCURICreate " . "\r\n" . $e->getMessage();
            } else {
                $strErrMsg = $e->getMessage();
            }
            $objLog['strErrMsg'] = $e->getMessage();
            $objLog['lngCount'] = -1;
            $result['result'] = FALSE;
        }
        $result['strErrMsg'] = $strErrMsg;
        $this->result = $result;
        return $this->result;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncErrLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************

    public function fncN5200ErrLog($strFileNM, &$objLog)
    {
        $strOut = "";
        $this->objSw = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        $strOut = $objLog['strErrMsg'] . " ";
        $strOut .= "\r\n";

        //終了情報ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        return true;
    }

    //**********************************************************************
    //処 理 名：下取車情報セット
    //関 数 名：FncOldCarInfoEDIT
    //引    数：objdr     (I)ﾃﾞｰﾀﾘｰﾀﾞ
    //　    　　OrderInfo (I//O)　
    //　    　　ObjLog    (I/O)ﾛｸﾞ情報
    //　    　　strCmnNo  (O)注文番号
    //戻 り 値：
    //処理説明：注文書情報をセットする
    //**********************************************************************
    //2008/07/27 INS ログ管理 intstate を追加
    function FncOldCarInfoEDIT($objDr, &$orderinfo, &$objLog, &$strErrMsg)
    {
        // $objDs = array();
        // $intColCnt = 0;
        // //列数
        // $lngTranCnt = 0;
        // //処理件数
        // $objSw = "";
        // //ストリームライター
        // $strOut = "";
        // //ストリングビルダー
        // $blnChk = "";
        // //Dim strID As String            '新中条区分
        // $strCmnNO = "";
        // //注文書№
        // $strSeiriNO = "";
        // //中古車整理№
        // $strDairitnCD = "";
        // //業販店コード
        // $strHanbaiKbn = "";
        // //販売区分
        // $strKappuKbn = "";
        // //割賦区分
        // $strAddress = "";
        // //住所コード
        // $strUriBu = "";
        // //売上部署
        // $strUrkBu = "";
        // //売掛部署
        // $lngJibaiTukisu = "";
        // //自賠責月数
        // $strCreditCD = "";
        // //ｸﾚｼﾞｯﾄ会社CD
        // $strTRKKbn = "";
        // //登録区分
        // // $frm1 As frmChumonCSV
        // // $frm2 As frmSCUriageMake
        // $RtnCode = "";
        $aryErrMsg = array();
        $intIdx = 0;
        $result = [];

        try {
            //データリーダに格納
            $this->Do_Excute = $this->ClsCreateCsv->fncSitSelect($this->ClsComFnc->FncNv($objDr["CMN_NO"]));
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            $objDr2 = $this->Do_Excute['data'];
            $lngCnt = 0;
            if (count($objDr2) > 0) {
                $lngCnt = 0;
                // 'データが存在する場合
                // '/*--- 2013/08/03 Upd Start
                // 'While objDr2.Read Or lngCnt = 3
                for ($i = 0; $i < count($objDr2); $i++) {
                    if ($lngCnt < 3) {
                        // /*--- 2013/08/03 Upd End
                        // '銘柄変換
                        switch ($this->ClsComFnc->FncNz($objDr2[$i]["BRD_CD"])) {
                            case "01":
                                $strMakerCd = "DAI";
                                break;
                            case "02":
                                $strMakerCd = "FUJ";
                                break;
                            case "03":
                                $strMakerCd = "HIN";
                                break;
                            case "04":
                                $strMakerCd = "HON";
                                break;
                            case "05":
                                $strMakerCd = "ISU";
                                break;
                            case "06":
                                $strMakerCd = "MIT";
                                break;
                            case "07":
                                $strMakerCd = "NIS";
                                break;
                            case "08":
                                $strMakerCd = "NID";
                                break;
                            case "09":
                                $strMakerCd = "SUZ";
                                break;
                            case "10":
                                $strMakerCd = "MAZ";
                                break;
                            case "11":
                                $strMakerCd = "TOY";
                                break;
                            case "19":
                                $strMakerCd = "JAP";
                                break;
                            default:
                                $strMakerCd = "FOR";
                                break;
                        }

                        switch ($lngCnt) {
                            //新･中･条下取･氏名1
                            case 0:
                                $this->orderinfo['OrderInfoA']['ID'] = $this->orderinfo['OrderInfo1']['ID'];
                                //--X(2)
                                //OrderInfo.OrderInfoA.処理日 = ""                                      '--X(8)
                                //OrderInfo.OrderInfoA.処理時間 = ""                                    '--X(6)
                                $this->orderinfo['OrderInfoA']['予備1'] = "";
                                //--X(4)
                                $this->orderinfo['OrderInfoA']['UCNO'] = $this->orderinfo['OrderInfo1']['UCNO'];
                                //--X(10)
                                $this->orderinfo['OrderInfoA']['AB'] = "B";
                                //--X(1)
                                $this->orderinfo['OrderInfoA']['注文書NO2'] = $this->orderinfo['OrderInfo1']['注文書NO2'];
                                //--X(7)
                                $this->orderinfo['OrderInfoA']['予備2'] = "";
                                //--X(2)
                                $this->orderinfo['OrderInfoA']['下取車１台目整理NO'] = "S" . rtrim($this->orderinfo['OrderInfo1']['注文書NO2']) . "1";
                                //--X(9)
                                $this->orderinfo['OrderInfo2']['下取車整理NO1'] = $this->orderinfo['OrderInfoA']['下取車１台目整理NO'];
                                $this->orderinfo['OrderInfoA']['下取車１台目親車注文書NO'] = $this->orderinfo['OrderInfo1']['注文書NO2'];
                                //--X(7)
                                $this->orderinfo['OrderInfoA']['下取車１台目売上注文書NO'] = "";
                                //--X(7)
                                $this->orderinfo['OrderInfoA']['下取車１台目下取SW'] = "1";
                                //--X(1)
                                $this->orderinfo['OrderInfoA']['下取車１台目買下理由'] = "ｼﾀ";
                                //--X(2)
                                $this->orderinfo['OrderInfoA']['下取車１台目現地仕切'] = "";
                                //--X(1)
                                $this->orderinfo['OrderInfoA']['下取車１台目銘柄'] = $strMakerCd;
                                //--X(3)
                                $this->orderinfo['OrderInfoA']['下取車１台目西暦年制'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 6), 0, 4);
                                //--X(4)
                                $this->orderinfo['OrderInfoA']['下取車１台目車検証型式'] = $this->ClsComFnc->FncNv($objDr2[$i]["SDI_KAT"]);
                                //--X(15)
                                $this->orderinfo['OrderInfoA']['下取車１台目CARNO'] = $this->ClsComFnc->FncNv($objDr2[$i]["CAR_NO"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoA']['下取車１台目車名'] = $this->ClsComFnc->FncNv($objDr2[$i]["VCLNM"]);
                                //--X(12)
                                $this->orderinfo['OrderInfoA']['下取車１台目型式指定'] = $this->ClsComFnc->FncNv($objDr2[$i]["SITEI_NO"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoA']['下取車１台目類別区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["RUIBETU_NO"]);
                                //--X(3)
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]) != "" && $this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]) != "") {
                                    // 'OrderInfo.OrderInfoA.下取車１台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).AddDays(1).ToString("yyyyMMdd")
                                    // '2007/03/19 UPDATE Start
                                    // 'OrderInfo.OrderInfoA.下取車１台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                             clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // 'clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).ToString("yyyyMMdd")

                                    if ($this->ClsComFnc->IsLeapYear(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4)) == FALSE && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) == "02" && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2) == "29") {
                                        $this->orderinfo['OrderInfoA']['下取車１台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . "28"));
                                    } else {
                                        $this->orderinfo['OrderInfoA']['下取車１台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2)));
                                    }

                                }
                                //2007/03/19 UPDATE End
                                else {
                                    $this->orderinfo['OrderInfoA']['下取車１台目登録年月日'] = $this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]);
                                }

                                $this->orderinfo['OrderInfoA']['下取車１台目陸事名称'] = $this->ClsComFnc->FncNv($objDr["RIKUJI_NM"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoA']['下取車１台目登録NO1'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RKJ_CD"]) . " " . $this->ClsComFnc->FncNv($objDr2[$i]["VCLRGTNO_SYU"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoA']['下取車１台目登録NO2'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_KNA"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoA']['下取車１台目登録NO3'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RBN"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoA']['下取車１台目H59'] = "";
                                //--X(3)
                                $this->orderinfo['OrderInfoA']['下取車１台目下取価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoA']['下取車１台目査定価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoA']['下取車１台目実査定価格'] = 0;
                                //--S9(9)
                                $this->orderinfo['OrderInfoA']['下取車１台目消費税率'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_RT"]);
                                //--X(2)
                                $this->orderinfo['OrderInfoA']['下取車１台目消費税額'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ預託金'] = $this->ClsComFnc->FncNz($objDr2[$i]["YOTAK_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ資金管理料'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHIKIN_KNR_RYOKIN"]);
                                //--S9(9)
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["YOTAK_UM"]) == "1") {
                                    $this->orderinfo['OrderInfoA']['下取車１台目預託区分'] = "1";
                                    //--X(1)
                                } else {
                                    $this->orderinfo['OrderInfoA']['下取車１台目預託区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOUROKU_UM"]);
                                    //--X(1)
                                }

                                $this->orderinfo['OrderInfoA']['下取車１台目手放区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["ATSUKAI_KB"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoA']['予備3'] = "";
                                //--X(18)

                                if ($this->orderinfo['OrderInfoA']['下取車１台目手放区分'] == "1" && $this->orderinfo['OrderInfoA']['下取車１台目預託区分']) {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                } else {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                }

                                $this->orderinfo['OrderInfo4']['下取者査定価格'] = $this->orderinfo['OrderInfo4']['下取者査定価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取価格'] = $this->orderinfo['OrderInfo3']['支払条件下取価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] = $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo3']['登録諸費用3契約'] = $this->orderinfo['OrderInfo3']['登録諸費用3契約'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] = $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] = $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['預り法廷費用'] = $this->orderinfo['OrderInfo3']['預り法廷費用'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['預り法定費用下取'] = $this->orderinfo['OrderInfo4']['預り法定費用下取'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['税金消費税'] = $this->orderinfo['OrderInfo4']['税金消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));
                                break;
                            case 1:
                                $this->orderinfo['OrderInfoB']['下取車２台目整理NO'] = "S" . rtrim($this->orderinfo['OrderInfo1']['注文書NO2']) . "2";
                                //--X(9)
                                $this->orderinfo['OrderInfo2']['下取車整理NO2'] = $this->orderinfo['OrderInfoB']['下取車２台目整理NO'];
                                $this->orderinfo['OrderInfoB']['下取車２台目親車注文書NO'] = $this->orderinfo['OrderInfo1']['注文書NO2'];
                                //--X(7)
                                $this->orderinfo['OrderInfoB']['下取車２台目売上注文書NO'] = "";
                                //--X(7)
                                $this->orderinfo['OrderInfoB']['下取車２台目下取SW'] = "1";
                                //--X(1)
                                $this->orderinfo['OrderInfoB']['下取車２台目買下理由'] = "ｼﾀ";
                                //--X(2)
                                $this->orderinfo['OrderInfoB']['下取車２台目現地仕切'] = "";
                                //--X(1)
                                $this->orderinfo['OrderInfoB']['下取車２台目銘柄'] = $strMakerCd;
                                //--X(3)
                                $this->orderinfo['OrderInfoB']['下取車２台目西暦年制'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 6), 0, 4);
                                //--X(4)
                                $this->orderinfo['OrderInfoB']['下取車２台目車検証型式'] = $this->ClsComFnc->FncNv($objDr2[$i]["SDI_KAT"]);
                                //--X(15)
                                $this->orderinfo['OrderInfoB']['下取車２台目CARNO'] = $this->ClsComFnc->FncNv($objDr2[$i]["CAR_NO"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoB']['下取車２台目車名'] = $this->ClsComFnc->FncNv($objDr2[$i]["VCLNM"]);
                                //--X(12)
                                $this->orderinfo['OrderInfoB']['下取車２台目型式指定'] = $this->ClsComFnc->FncNv($objDr2[$i]["SITEI_NO"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoB']['下取車２台目類別区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["RUIBETU_NO"]);
                                //--X(3)
                                //---20150922 li UPD S.
                                //if ($this -> ClsComFnc -> FncNv($objDr2[$i]["SYD_TOU_YM"]) == "" && $this -> ClsComFnc -> FncNv($objDr2[$i]["SYAKEN_EXP_DT"]) == "")
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]) != "" && $this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]) != "")
                                //---20150922 li UPD E.
                                {
                                    // 'OrderInfo.OrderInfoB.下取車２台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).AddDays(1).ToString("yyyyMMdd")
                                    //
                                    // '2007/03/19 UPDATE Start
                                    // 'OrderInfo.OrderInfoB.下取車２台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // 'clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).ToString("yyyyMMdd")
                                    if ($this->ClsComFnc->IsLeapYear(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4)) == FALSE && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) == "02" && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2) == "29") {
                                        $this->orderinfo['OrderInfoB']['下取車２台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . "28"));
                                    } else {

                                        $this->orderinfo['OrderInfoB']['下取車２台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2)));
                                    }

                                    //2007/03/19 UPDATE End
                                } else {
                                    $this->orderinfo['OrderInfoB']['下取車２台目登録年月日'] = $this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]);
                                }

                                $this->orderinfo['OrderInfoB']['下取車２台目陸事名称'] = $this->ClsComFnc->FncNv($objDr["RIKUJI_NM"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoB']['下取車２台目登録NO1'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RKJ_CD"]) . " " . $this->ClsComFnc->FncNv($objDr2[$i]["VCLRGTNO_SYU"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoB']['下取車２台目登録NO2'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_KNA"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoB']['下取車２台目登録NO3'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RBN"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoB']['下取車２台目H59'] = "";
                                //--X(3)
                                $this->orderinfo['OrderInfoB']['下取車２台目下取価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoB']['下取車２台目査定価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoB']['下取車２台目実査定価格'] = 0;
                                //--S9(9)
                                $this->orderinfo['OrderInfoB']['下取車２台目消費税率'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_RT"]);
                                //--X(2)
                                $this->orderinfo['OrderInfoB']['下取車２台目消費税額'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ預託金'] = $this->ClsComFnc->FncNz($objDr2[$i]["YOTAK_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ資金管理料'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHIKIN_KNR_RYOKIN"]);
                                //--S9(9)
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["YOTAK_UM"]) == "1") {
                                    $this->orderinfo['OrderInfoB']['下取車２台目預託区分'] = "1";
                                    //--X(1)
                                } else {
                                    $this->orderinfo['OrderInfoB']['下取車２台目預託区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOUROKU_UM"]);
                                    //--X(1)
                                }

                                $this->orderinfo['OrderInfoB']['下取車２台目手放区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["ATSUKAI_KB"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoB']['予備'] = "";
                                //--X(18)

                                if ($this->orderinfo['OrderInfoB']['下取車２台目手放区分'] == "1" && $this->orderinfo['OrderInfoB']['下取車２台目預託区分']) {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                } else {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                }
                                $this->orderinfo['OrderInfo4']['下取者査定価格'] = $this->orderinfo['OrderInfo4']['下取者査定価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取価格'] = $this->orderinfo['OrderInfo3']['支払条件下取価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] = $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo3']['登録諸費用3契約'] = $this->orderinfo['OrderInfo3']['登録諸費用3契約'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] = $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] = $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['預り法廷費用'] = $this->orderinfo['OrderInfo3']['預り法廷費用'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['預り法定費用下取'] = $this->orderinfo['OrderInfo4']['預り法定費用下取'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['税金消費税'] = $this->orderinfo['OrderInfo4']['税金消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));
                                break;
                            case 2:
                                $this->orderinfo['OrderInfoC']['下取車３台目整理NO'] = "S" . rtrim($this->orderinfo['OrderInfo1']['注文書NO2']) . "3";
                                //--X(9)
                                $this->orderinfo['OrderInfo2']['下取車整理NO3'] = $this->orderinfo['OrderInfoC']['下取車３台目整理NO'];
                                $this->orderinfo['OrderInfoC']['下取車３台目親車注文書NO'] = "";
                                //--X(7)
                                $this->orderinfo['OrderInfoC']['下取車３台目売上注文書NO'] = "";
                                //--X(7)
                                $this->orderinfo['OrderInfoC']['下取車３台目下取SW'] = "1";
                                //--X(1)
                                $this->orderinfo['OrderInfoC']['下取車３台目買下理由'] = "ｼﾀ";
                                //--X(2)
                                $this->orderinfo['OrderInfoC']['下取車３台目現地仕切'] = "";
                                //--X(1)
                                $this->orderinfo['OrderInfoC']['下取車３台目銘柄'] = $strMakerCd;
                                //--X(3)
                                $this->orderinfo['OrderInfoC']['下取車３台目西暦年制'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 6), 0, 4);
                                //--X(4)
                                $this->orderinfo['OrderInfoC']['下取車３台目車検証型式'] = $this->ClsComFnc->FncNv($objDr2[$i]["SDI_KAT"]);
                                //--X(15)
                                $this->orderinfo['OrderInfoC']['下取車３台目CARNO'] = $this->ClsComFnc->FncNv($objDr2[$i]["CAR_NO"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoC']['下取車３台目車名'] = $this->ClsComFnc->FncNv($objDr2[$i]["VCLNM"]);
                                //--X(12)
                                $this->orderinfo['OrderInfoC']['下取車３台目型式指定'] = $this->ClsComFnc->FncNv($objDr2[$i]["SITEI_NO"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoC']['下取車３台目類別区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["RUIBETU_NO"]);
                                //--X(3)
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]) != "" && $this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]) != "") {
                                    // 'OrderInfo.OrderInfoC.下取車３台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).AddDays(1).ToString("yyyyMMdd")
                                    //
                                    // '2007/03/19 UPDATE Start
                                    // 'OrderInfo.OrderInfoC.下取車３台目登録年月日 = CType(clsComFnc.FncNv(objDr2("SYD_TOU_YM")).ToString.Substring(0, 4) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(4, 2) & "/" & _
                                    // '                                              clsComFnc.FncNv(objDr2("SYAKEN_EXP_DT")).ToString.Substring(6, 2), Date).ToString("yyyyMMdd")

                                    if ($this->ClsComFnc->IsLeapYear(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4)) == FALSE && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) == "02" && mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2) == "29") {

                                        $this->orderinfo['OrderInfoC']['下取車３台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . "28"));
                                    } else {
                                        $this->orderinfo['OrderInfoC']['下取車３台目登録年月日'] = date('Ymd', strtotime(mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]), 0, 4) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 4, 2) . "/" . mb_substr($this->ClsComFnc->FncNv($objDr2[$i]["SYAKEN_EXP_DT"]), 6, 2)));
                                    }

                                    //2007/03/19 UPDATE End
                                } else {
                                    $this->orderinfo['OrderInfoC']['下取車３台目登録年月日'] = $this->ClsComFnc->FncNv($objDr2[$i]["SYD_TOU_YM"]);
                                }
                                $this->orderinfo['OrderInfoC']['下取車３台目陸事名称'] = $this->ClsComFnc->FncNv($objDr["RIKUJI_NM"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoC']['下取車３台目登録NO1'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RKJ_CD"]) . " " . $this->ClsComFnc->FncNv($objDr2[$i]["VCLRGTNO_SYU"]);
                                //--X(8)
                                $this->orderinfo['OrderInfoC']['下取車３台目登録NO2'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_KNA"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoC']['下取車３台目登録NO3'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOU_NO_RBN"]);
                                //--X(4)
                                $this->orderinfo['OrderInfoC']['下取車３台目H59'] = "";
                                //--X(3)
                                $this->orderinfo['OrderInfoC']['下取車３台目下取価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoC']['下取車３台目査定価格'] = $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoC']['下取車３台目実査定価格'] = 0;
                                //--S9(9)
                                $this->orderinfo['OrderInfoC']['下取車３台目消費税率'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_RT"]);
                                //--X(2)
                                $this->orderinfo['OrderInfoC']['下取車３台目消費税額'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ預託金'] = $this->ClsComFnc->FncNz($objDr2[$i]["YOTAK_GK"]);
                                //--S9(9)
                                $this->orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ資金管理料'] = $this->ClsComFnc->FncNz($objDr2[$i]["SHIKIN_KNR_RYOKIN"]);
                                //--S9(9)
                                if ($this->ClsComFnc->FncNv($objDr2[$i]["YOTAK_UM"]) == "1") {
                                    $this->orderinfo['OrderInfoC']['下取車３台目預託区分'] = "1";
                                    //--X(1)
                                } else {

                                    $this->orderinfo['OrderInfoC']['下取車３台目預託区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["TOUROKU_UM"]);
                                    //--X(1)
                                }
                                $this->orderinfo['OrderInfoC']['下取車３台目手放区分'] = $this->ClsComFnc->FncNv($objDr2[$i]["ATSUKAI_KB"]);
                                //--X(1)
                                $this->orderinfo['OrderInfoC']['予備'] = "";
                                //--X(18)

                                if ($this->orderinfo['OrderInfoC']['下取車３台目手放区分'] == "1" && $this->orderinfo['OrderInfoC']['下取車３台目預託区分']) {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                } else {
                                    $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["RCYL_GK"]));
                                    //--S9(9)
                                }
                                $this->orderinfo['OrderInfo4']['下取者査定価格'] = $this->orderinfo['OrderInfo4']['下取者査定価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SATEI_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取価格'] = $this->orderinfo['OrderInfo3']['支払条件下取価格'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["TRA_GK"]));
                                //--S9(9)
                                $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] = $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SHZ_GKU"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo3']['登録諸費用3契約'] = $this->orderinfo['OrderInfo3']['登録諸費用3契約'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] = $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] + $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"]) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] = $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));

                                $this->orderinfo['OrderInfo3']['預り法廷費用'] = $this->orderinfo['OrderInfo3']['預り法廷費用'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['預り法定費用下取'] = $this->orderinfo['OrderInfo4']['預り法定費用下取'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_AZK_HTE_HYO"]));
                                //--S9(9)

                                $this->orderinfo['OrderInfo4']['税金消費税'] = $this->orderinfo['OrderInfo4']['税金消費税'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["MSY_TOU_TTK_DAIKO_HYO_SHZ"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr2[$i]["SIY_SMI_CAR_SYR_HYO_SHZ"]));
                                break;
                        }
                        //ｶｳﾝﾄｱｯﾌﾟ(処理件数)
                        $lngCnt = $lngCnt + 1;
                    }
                }
            }

            //課税区分
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                //---20150922 li UPD S.
                //if ($this -> orderinfo['OrderInfo3']['支払条件下取車消費税'] != 0 || $this -> orderinfo['OrderInfo1']['認定特需ユーザーコード'] !== "")
                if ($this->orderinfo['OrderInfo3']['支払条件下取車消費税'] != 0 || $this->orderinfo['OrderInfo1']['認定特需ユーザーコード'] != "")
                //---20150922 li UPD E.
                {
                    $this->orderinfo['OrderInfo2']['区分27'] = "1";
                    //--（課税区分）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分27'] = "0";
                    //--（課税区分）(1)
                }
            } else {
                //---20150922 li UPD S.
                //if ($this -> orderinfo['OrderInfo3']['支払条件下取車消費税'] != 0 || $this -> orderinfo['OrderInfo1']['注文書NO2'] > "6")
                if ($this->orderinfo['OrderInfo3']['支払条件下取車消費税'] != 0 || (mb_substr($this->orderinfo['OrderInfo1']['注文書NO2'], 0, 1) >= "6") && $this->orderinfo['OrderInfo1']['注文書NO2'] > "6")
                //---20150922 li UPD E.
                {
                    $this->orderinfo['OrderInfo2']['区分27'] = "1";
                    //--（課税区分）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分27'] = "0";
                    //--（課税区分）(1)
                }
            }

            if ($this->ClsComFnc->FncNv($objDr["HNB_KB"]) == "5") {
                $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金'] = $this->ClsComFnc->FncNz($objDr["JIP_DTL_SUM"]);
            } elseif ($this->ClsComFnc->FncNv($objDr["HNB_KB"]) == "9") {
                $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金'] = 0;
            }

            // '2006/10/16 DELETE Start
            // '登録諸費用3基準
            // 'OrderInfo.OrderInfo3.登録諸費用3基準 = OrderInfo.OrderInfo3.登録諸費用3基準 - _
            // '                                      (OrderInfo.OrderInfo3.預り法廷費用 - CType(clsComFnc.FncNz(objDr("HOUTEIHI")), Double))
            //
            // ''登録諸費用3基準 中古車の場合
            // 'If clsComFnc.FncNv(objDr("NAU_KB")) = "2" Then
            // '    OrderInfo.OrderInfo3.登録諸費用3基準 = OrderInfo.OrderInfo3.登録諸費用3基準 _
            // '                                      + OrderInfo.OrderInfo5.ﾘｻｲｸﾙ預託金
            // 'End If
            // '2006/10/16 DELETE End

            //登録諸費用3基準新（2006/07/26)
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                $this->orderinfo['OrderInfo3']['登録諸費用3基準NEW'] = $this->orderinfo['OrderInfo3']['登録諸費用3基準'];
            } else {
                $this->orderinfo['OrderInfo3']['登録諸費用3基準NEW'] = $this->ClsComFnc->FncNz($objDr["SYOHIKIJN_NEW"]);
            }
            $this->orderinfo['OrderInfo3']['登録諸費用3契約NEW'] = $this->ClsComFnc->FncNz($objDr["RIEKI_NEW"]);
            // '2008/03/03 UPDATE Start
            // 'OrderInfo.OrderInfo3.預り法廷費用 = OrderInfo.OrderInfo3.預り法廷費用 _
            // '                                  + OrderInfo.OrderInfo5.ﾘｻｲｸﾙ預託金 _
            // '                                  + OrderInfo.OrderInfo5.JAF

            // $this->orderinfo['OrderInfo3']['預り法廷費用'] = $this->orderinfo['OrderInfo3']['預り法廷費用'];
            //2008/03/03 UPDATE end

            //支払合計
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "2") {
                $this->orderinfo['OrderInfo3']['支払金合計'] = $this->orderinfo['OrderInfo3']['車両価格'] - $this->orderinfo['OrderInfo3']['車両値引'] + $this->orderinfo['OrderInfo3']['添付品契約'] + $this->orderinfo['OrderInfo3']['特別仕様3契約'] + $this->orderinfo['OrderInfo3']['特別仕様6契約'] + $this->orderinfo['OrderInfo3']['割賦手数料契約'] + $this->orderinfo['OrderInfo3']['登録諸費用3契約'] + $this->orderinfo['OrderInfo3']['預り法廷費用'] + $this->orderinfo['OrderInfo3']['税金保険料'] + $this->orderinfo['OrderInfo3']['残債'] + $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'];
            } else {
                $this->orderinfo['OrderInfo3']['支払金合計'] = $this->orderinfo['OrderInfo3']['車両価格'] - $this->orderinfo['OrderInfo3']['車両値引'] + $this->orderinfo['OrderInfo3']['添付品契約'] + $this->orderinfo['OrderInfo3']['特別仕様3契約'] + $this->orderinfo['OrderInfo3']['特別仕様6契約'] + $this->orderinfo['OrderInfo3']['割賦手数料契約'] + $this->orderinfo['OrderInfo3']['登録諸費用3契約'] + $this->orderinfo['OrderInfo3']['預り法廷費用'] + $this->orderinfo['OrderInfo3']['税金保険料'] + $this->orderinfo['OrderInfo4']['税金消費税'] + $this->orderinfo['OrderInfo3']['残債'] + $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'];
            }

            //頭金
            $this->orderinfo['OrderInfo3']['支払条件頭金'] = $this->orderinfo['OrderInfo3']['支払金合計'] - $this->orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ金額'] - $this->orderinfo['OrderInfo4']['割賦元金'] - $this->orderinfo['OrderInfo3']['支払条件下取価格'] - $this->orderinfo['OrderInfo3']['支払条件下取車消費税'];

            //Ｆ号限界利益
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "2") {

                $this->orderinfo['OrderInfo4']['車両F号限界利益'] = $this->orderinfo['OrderInfo3']['車両価格'] - $this->orderinfo['OrderInfo3']['車両拠点原価'] + $this->orderinfo['OrderInfo3']['添付品契約'] - $this->orderinfo['OrderInfo3']['添付品原価'] + $this->orderinfo['OrderInfo3']['特別仕様3契約'] - $this->orderinfo['OrderInfo3']['特別仕様3原価'] + $this->orderinfo['OrderInfo3']['特別仕様6契約'] - $this->orderinfo['OrderInfo3']['特別仕様6原価'] + $this->orderinfo['OrderInfo3']['割賦手数料契約'] - $this->orderinfo['OrderInfo3']['割賦手数料基準'] + $this->orderinfo['OrderInfo3']['登録諸費用3契約'] - $this->orderinfo['OrderInfo3']['登録諸費用3基準'] + $this->orderinfo['OrderInfo3']['預り法廷費用'] - $this->orderinfo['OrderInfo4']['販売手数料額'] - $this->orderinfo['OrderInfo4']['その他紹介料'] + $this->orderinfo['OrderInfo4']['下取者査定価格'] - $this->orderinfo['OrderInfo3']['支払条件下取価格'];
                //最終損益
                $this->orderinfo['OrderInfo4']['最終損益'] = $this->orderinfo['OrderInfo4']['車両F号限界利益'] - $this->orderinfo['OrderInfo4']['本部負担金'] + $this->orderinfo['OrderInfo4']['打込金収入手数料'] + $this->orderinfo['OrderInfo4']['打込金申請奨励金'];
            } else {

                $this->orderinfo['OrderInfo4']['車両F号限界利益'] = $this->orderinfo['OrderInfo3']['車両価格'] - $this->orderinfo['OrderInfo3']['車両値引'] - $this->orderinfo['OrderInfo3']['車両注文書原価'] + $this->orderinfo['OrderInfo3']['添付品契約'] - $this->orderinfo['OrderInfo3']['添付品原価'] + $this->orderinfo['OrderInfo3']['特別仕様3契約'] - $this->orderinfo['OrderInfo3']['特別仕様3原価'] + $this->orderinfo['OrderInfo3']['特別仕様6契約'] - $this->orderinfo['OrderInfo3']['特別仕様6原価'] + $this->orderinfo['OrderInfo3']['割賦手数料契約'] - $this->orderinfo['OrderInfo3']['割賦手数料基準'] + $this->orderinfo['OrderInfo3']['登録諸費用3契約'] - $this->orderinfo['OrderInfo3']['登録諸費用3基準'] + $this->orderinfo['OrderInfo4']['下取者査定価格'] - $this->orderinfo['OrderInfo3']['支払条件下取価格'] + $this->orderinfo['OrderInfo4']['打込金収入手数料'] + $this->orderinfo['OrderInfo4']['打込金申請奨励金'] - $this->orderinfo['OrderInfo4']['販売手数料額'] - $this->orderinfo['OrderInfo4']['その他紹介料'] - $this->orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ'] + $this->orderinfo['OrderInfo5']['KB'];
                //+ OrderInfo.OrderInfo3.預り法廷費用 _
                $this->orderinfo['OrderInfo4']['最終損益'] = $this->orderinfo['OrderInfo4']['車両F号限界利益'] - $this->orderinfo['OrderInfo4']['本部負担金'] + $this->orderinfo['OrderInfo3']['車両注文書原価'] - $this->orderinfo['OrderInfo3']['車両拠点原価'];

            }

            //値引率
            $this->orderinfo['OrderInfo5']['値引率'] = 0.0;

            // 'If OrderInfo.OrderInfo1.契約店 = "" Or OrderInfo.OrderInfo1.契約店 = "3634" Then
            // '    If (OrderInfo.OrderInfo3.車両価格 _
            // '          + OrderInfo.OrderInfo3.添付品契約 _
            // '          + OrderInfo.OrderInfo3.特別仕様3契約 _
            // '          + OrderInfo.OrderInfo3.特別仕様6契約 _
            // '          + OrderInfo.OrderInfo3.割賦手数料契約 _
            // '          + OrderInfo.OrderInfo3.登録諸費用3契約 _
            // '          + OrderInfo.OrderInfo3.預り法廷費用) <> 0 Then
            // '        OrderInfo.OrderInfo5.値引率 = ((OrderInfo.OrderInfo3.車両値引 _
            // '                                        + OrderInfo.OrderInfo4.販売手数料額 _
            // '                                        - OrderInfo.OrderInfo4.打込金収入手数料 _
            // '                                        - OrderInfo.OrderInfo4.打込金申請奨励金 _
            // '                                        + OrderInfo.OrderInfo4.下取者査定価格 _
            // '                                        - OrderInfo.OrderInfo4.下取者買取価格) _
            // '                                      / (OrderInfo.OrderInfo3.車両価格 _
            // '                                        + OrderInfo.OrderInfo3.添付品契約 _
            // '                                        + OrderInfo.OrderInfo3.特別仕様3契約 _
            // '                                        + OrderInfo.OrderInfo3.特別仕様6契約 _
            // '                                        + OrderInfo.OrderInfo3.割賦手数料契約 _
            // '                                        + OrderInfo.OrderInfo3.登録諸費用3契約 _
            // '                                        + OrderInfo.OrderInfo3.預り法廷費用) _
            // '                                     * 100).ToString("00.00")
            // '    Else
            // '        OrderInfo.OrderInfo5.値引率 = 0.0
            // '    End If
            // 'Else
            // '    OrderInfo.OrderInfo5.値引率 = 0.0
            // 'End If

            //売上台数・登録台数
            //---20150910 li UPD S.
            //if ($this -> orderinfo['OrderInfo1']['解約日'] === "")
            if ($this->orderinfo['OrderInfo1']['解約日'] == "")
            //---20150910 li UPD E.
            {
                if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                    //---20150914 li UPD S.
                    //switch ($this->OrderInfo['OrderInfo2']['区分22'])
                    switch ($this->orderinfo['OrderInfo2']['区分22'])
                        //---20150914 li UPD E.
                    {
                        case "1":
                            $this->orderinfo['OrderInfo6']['売上台数'] = 1;
                            $this->orderinfo['OrderInfo6']['登録台数'] = 1;
                            break;
                        case "2":
                            $this->orderinfo['OrderInfo6']['売上台数'] = 0;
                            $this->orderinfo['OrderInfo6']['登録台数'] = 1;
                            break;
                        case "3":
                            $this->orderinfo['OrderInfo6']['売上台数'] = 1;
                            $this->orderinfo['OrderInfo6']['登録台数'] = 0;
                            break;
                        default:
                            $this->orderinfo['OrderInfo6']['売上台数'] = 0;
                            $this->orderinfo['OrderInfo6']['登録台数'] = 0;
                            break;
                    }
                } else {
                    if ($this->orderinfo['OrderInfo3']['車両価格'] > 0) {
                        $this->orderinfo['OrderInfo6']['売上台数'] = 1;
                        $this->orderinfo['OrderInfo6']['登録台数'] = 0;
                    } else {
                        $this->orderinfo['OrderInfo6']['売上台数'] = 0;
                        $this->orderinfo['OrderInfo6']['登録台数'] = 0;
                    }
                }
            } else {
                $this->orderinfo['OrderInfo6']['売上台数'] = 0;
                $this->orderinfo['OrderInfo6']['登録台数'] = 0;
            }
            //2009/12/21 INS Start R4連携集計システムで使用するため追加
            //UC件数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['UCNO']) !== "") {
                $this->orderinfo['OrderInfo6']['UC件数'] = 1;
                //'''OrderInfo.OrderInfo6.UC件数FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['UC件数'] = 0;
                //'''OrderInfo.OrderInfo6.UC件数FLG = "0"
            }
            //未実績
            //---20150910 li UPD S.
            //if ($this -> orderinfo['OrderInfo1']['契約店'] == "17349" && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE || (($this -> orderinfo['OrderInfo1']['契約店'] == "00000" || $this -> orderinfo['OrderInfo1']['契約店'] == "3634") && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE && $this -> ClsComFnc -> FncNz($this -> orderinfo['OrderInfo3']['車両価格']) == 0) || ($this -> orderinfo['OrderInfo1']['契約店'] == "3634" && $this -> orderinfo['OrderInfo1']['登録店'] === "3634" && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE) || ($this -> orderinfo['OrderInfo1']['契約店'] === "3634" && $this -> orderinfo['OrderInfo1']['登録店'] === "3634"))
            if (($this->orderinfo['OrderInfo1']['契約店'] == "17349" && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE) || (($this->orderinfo['OrderInfo1']['契約店'] == "00000" || $this->orderinfo['OrderInfo1']['契約店'] == "3634") && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE && $this->ClsComFnc->FncNz($this->orderinfo['OrderInfo3']['車両価格']) == 0) || ($this->orderinfo['OrderInfo1']['契約店'] == "3634" && $this->orderinfo['OrderInfo1']['登録店'] != "3634" && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE) || ($this->orderinfo['OrderInfo1']['契約店'] != "3634" && $this->orderinfo['OrderInfo1']['登録店'] != "3634"))
            //---20150910 li UPD E.
            {
                $this->orderinfo['OrderInfo6']['未実績台数'] = 1;
                //'''OrderInfo.OrderInfo6.未実績FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['未実績台数'] = 0;
                //'''OrderInfo.OrderInfo6.未実績FLG = "0"
            }
            //登録実績台数
            //---20150910 li UPD S.
            //if ($this -> ClsComFnc -> FncNv($this -> orderinfo['OrderInfo1']['解約日']) !== "")
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) != "")
            //---20150910 li UPD E.
            {
                //       解約されている場合
                //---20150910 li UPD S.
                //if (((is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == TRUE && $this -> ClsComFnc -> FncNv(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) != "ZZZ") || $this -> ClsComFnc -> FncNv(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) == "TAT"))
                if ((is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == TRUE && $this->ClsComFnc->FncNv(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) != "ZZZ") || $this->ClsComFnc->FncNv(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) == "TAT")
                //---20150910 li UPD E.
                {
                    $this->orderinfo['OrderInfo6']['登録実績台数'] = 1;
                    //'''OrderInfo.OrderInfo6.登録実績FLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['登録実績台数'] = 0;
                    //'''OrderInfo.OrderInfo6.登録実績FLG = "0"
                }
            } else {
                //解約されていない場合
                //---20150910 li UPD S.
                //if (is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == TRUE && $this -> ClsComFnc -> FncNv(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) != "ZZZ" && $this -> ClsComFnc -> FncNv(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) == "TAT")
                if (is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == TRUE && $this->ClsComFnc->FncNv(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) != "ZZZ" && $this->ClsComFnc->FncNv(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3)) != "TAT")
                //---20150910 li UPD E.
                {
                    $this->orderinfo['OrderInfo6']['登録実績台数'] = 1;
                    //'''OrderInfo.OrderInfo6.登録実績FLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['登録実績台数'] = 0;
                    //'''OrderInfo.OrderInfo6.登録実績FLG = "0"
                }
            }
            //他契自登台数
            //契約店が福祉、メーカー、（TMrh）以外　かつ　車両価格＝0で登録店が3634のもの
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                //解約されている場合
                //元には解約日の条件がないが、自契他登は解約日を見ているので見るように変更
                $this->orderinfo['OrderInfo6']['他契自登台数'] = 0;
            } else {
                if (($this->orderinfo['OrderInfo1']['契約店'] != "17349" && $this->orderinfo['OrderInfo1']['契約店'] != "00000" && $this->orderinfo['OrderInfo1']['契約店'] != "3634") && $this->orderinfo['OrderInfo3']['車両価格'] == 0 && $this->orderinfo['OrderInfo1']['登録店'] == "3634") {
                    $this->orderinfo['OrderInfo6']['他契自登台数'] = 1;
                    //'''OrderInfo.OrderInfo6.他契自登FLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['他契自登台数'] = 0;
                    //'''OrderInfo.OrderInfo6.他契自登FLG = "0"
                }
            }

            //自契他登台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                //解約されている場合
                //元には解約日の条件がないが、自契他登は解約日を見ているので見るように変更
                $this->orderinfo['OrderInfo6']['自契他登台数'] = 0;
                //'''OrderInfo.OrderInfo6.自契他登FLG = "0"
            } else {
                //解約されていない場合
                //福祉(契約店が"17349"又は販売形態＝"28")以外　かつ　UCNOの7桁目から3桁が"TAT"のもの
                if (!($this->orderinfo['OrderInfo1']['契約店'] == "17349" || $this->orderinfo['OrderInfo2']['区分20'] == "28") && mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3) == "TAT") {
                    $this->orderinfo['OrderInfo6']['自契他登台数'] = 1;
                    //'''OrderInfo.OrderInfo6.自契他登FLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['自契他登台数'] = 0;
                    //'''OrderInfo.OrderInfo6.自契他登FLG = "0"
                }
            }
            //メーカー台数
            //　契約店が"00000"のもの
            if ($this->orderinfo['OrderInfo1']['契約店'] == "00000") {
                $this->orderinfo['OrderInfo6']['メーカー台数'] = 1;
                //'''OrderInfo.OrderInfo6.メーカーFLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['メーカー台数'] = 0;
                //'''OrderInfo.OrderInfo6.メーカーFLG = "0"
            }
            //福祉台数
            //　契約店が"17349"又は販売形態＝"28"のもの
            if ($this->orderinfo['OrderInfo1']['契約店'] == "17349" || $this->orderinfo['OrderInfo2']['区分20'] == "28") {
                $this->orderinfo['OrderInfo6']['福祉台数'] = 1;
                //'''OrderInfo.OrderInfo6.福祉FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['福祉台数'] = 0;
                //'''OrderInfo.OrderInfo6.福祉FLG = "0"
            }

            //社名台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                //解約されている場合
                //'''OrderInfo.OrderInfo6.社名FLG = "0"
                $this->orderinfo['OrderInfo6']['社名台数'] = 0;
            } else {
                //解約されていない場合
                if ($this->orderinfo['OrderInfo1']['契約店'] == "3634" && $this->orderinfo['OrderInfo3']['車両価格'] == 0) {
                    //'''OrderInfo.OrderInfo6.社名FLG = "1"
                    $this->orderinfo['OrderInfo6']['社名台数'] = 1;
                } else {
                    //'''OrderInfo.OrderInfo6.社名FLG = "0"
                    $this->orderinfo['OrderInfo6']['社名台数'] = 0;
                }
            }

            //売上実績台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                //解約されている場合
                $this->orderinfo['OrderInfo6']['売上実績台数'] = 0;
                //'''OrderInfo.OrderInfo6.売上実績FLG = "0"
            } else {
                //解約されていない場合
                //福祉以外で車両価格<>0の場合
                if (!($this->orderinfo['OrderInfo1']['契約店'] == "17349" || $this->orderinfo['OrderInfo2']['区分20'] == "28") && $this->orderinfo['OrderInfo3']['車両価格'] != 0) {
                    $this->orderinfo['OrderInfo6']['売上実績台数'] = 1;
                    //'''OrderInfo.OrderInfo6.売上実績FLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['売上実績台数'] = 0;
                    //'OrderInfo.OrderInfo6.売上実績FLG = "0"
                }
            }

            //リース台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分15']) == "1") {
                //'''OrderInfo.OrderInfo6.ﾘｰｽFLG = "1"
                $this->orderinfo['OrderInfo6']['ﾘｰス台数'] = 1;
            } else {
                //'''OrderInfo.OrderInfo6.ﾘｰｽFLG = "0"
                $this->orderinfo['OrderInfo6']['ﾘｰス台数'] = 0;
            }

            //サービスカー台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                //解約されている場合
                $this->orderinfo['OrderInfo6']['サービスカー台数'] = 0;
                //'''OrderInfo.OrderInfo6.サービスカーFLG = "0"
            } else {
                //解約されていない場合
                if (!($this->orderinfo['OrderInfo1']['契約店'] == "17349" || $this->orderinfo['OrderInfo2']['区分20'] == "28") && mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3) != "TAT" && $this->orderinfo['OrderInfo2']['区分20'] == "A") {
                    $this->orderinfo['OrderInfo6']['サービスカー台数'] = 1;
                    //'''OrderInfo.OrderInfo6.サービスカーFLG = "1"
                } else {
                    $this->orderinfo['OrderInfo6']['サービスカー台数'] = 0;
                    //'''OrderInfo.OrderInfo6.サービスカーFLG = "0"
                }
            }

            //再売台数
            //---20150922 li UPD S.
            // if (($this -> orderinfo['OrderInfo1']['契約店'] == "17349" && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE)
            // || (($this -> orderinfo['OrderInfo1']['契約店'] == "00000" || $this -> orderinfo['OrderInfo1']['契約店'] == "3634") && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE && $this -> orderinfo['OrderInfo3']['車両価格'] == 0)
            // || ($this -> orderinfo['OrderInfo1']['契約店'] == "3734" && $this -> orderinfo['OrderInfo1']['登録店'] === "3634" && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE)
            // || ($this -> orderinfo['OrderInfo3']['車両価格'] > 0 && mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 6, 3) === "TAT" && is_numeric(mb_substr($this -> ClsComFnc -> mb_str_pad($this -> orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE))
            if (
                ($this->orderinfo['OrderInfo1']['契約店'] == "17349" && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE) || (($this->orderinfo['OrderInfo1']['契約店'] == "00000" || $this->orderinfo['OrderInfo1']['契約店'] == "3634") && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE && $this->orderinfo['OrderInfo3']['車両価格'] == 0)
                || ($this->orderinfo['OrderInfo1']['契約店'] == "3734" && $this->orderinfo['OrderInfo1']['登録店'] != "3634" && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE)
                || ($this->orderinfo['OrderInfo3']['車両価格'] > 0 && mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 6, 3) != "TAT" && is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['UCNO'], 12), 9, 1)) == FALSE)
            )
            //---20150922 li UPD E.
            {
                $this->orderinfo['OrderInfo6']['再売台数'] = 1;
                //'''OrderInfo.OrderInfo6.再売FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['再売台数'] = 0;
                //'''OrderInfo.OrderInfo6.再売FLG = "0"

            }
            //カルテ台数
            //---20150910 li UPD S.
            //if ($this -> orderinfo['OrderInfo1']['サービス'] === "00")
            if ($this->orderinfo['OrderInfo1']['サービス'] != "00")
            //---20150910 li UPD E.
            {
                $this->orderinfo['OrderInfo6']['カルテ台数'] = 1;
                //'''OrderInfo.OrderInfo6.カルテFLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['カルテ台数'] = 0;
                //'''OrderInfo.OrderInfo6.カルテFLG = "0"
            }

            //売上(登録区分)台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分22']) == "1" || $this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分22']) == "3") {
                $this->orderinfo['OrderInfo6']['売上_登録区分_台数'] = 1;
                //'''OrderInfo.OrderInfo6.売上_登録区分_FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['売上_登録区分_台数'] = 0;
                //'''OrderInfo.OrderInfo6.売上_登録区分_FLG = "0"
            }
            //登録(登録区分)台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分22']) == "1" || $this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分22']) == "2") {
                $this->orderinfo['OrderInfo6']['登録_登録区分_台数'] = 1;
                //'''OrderInfo.OrderInfo6.登録_登録区分_FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['登録_登録区分_台数'] = 0;
                //'''OrderInfo.OrderInfo6.登録_登録区分_FLG = "0"
            }

            //その他(登録区分)台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo2']['区分22']) == "4") {
                $this->orderinfo['OrderInfo6']['その他_登録区分_台数'] = 1;
                //'''OrderInfo.OrderInfo6.その他_登録区分_FLG = "1"
            } else {
                $this->orderinfo['OrderInfo6']['その他_登録区分_台数'] = 0;
                //'''OrderInfo.OrderInfo6.その他_登録区分_FLG = "0"
            }
            //解約台数
            if ($this->ClsComFnc->FncNv($this->orderinfo['OrderInfo1']['解約日']) !== "") {
                $this->orderinfo['OrderInfo6']['解約台数'] = 1;
            } else {
                //解約されていない場合
                $this->orderinfo['OrderInfo6']['解約台数'] = 0;
            }
            //2009/12/21 INS End

            //2007/04/20 INS START
            //データリーダの解放
            if (isset($objDr2)) {
                unset($objDr2);
            }

            //2007/04/20 INS END
            //---------------------------------------------------------------------------------
            //エラーチェック処理
            //---------------------------------------------------------------------------------
            $aryErrMsg = array();
            //2007/04/20 INS START
            $aryChkMsg = array();
            $this->Do_Excute = $this->ClsCreateCsv->fncErrChkUri($this->ClsComFnc->FncNv($objDr["CMN_NO"]), $this->orderinfo['OrderInfo1']['UCNO'], $this->orderinfo['OrderInfo1']['売上部署'], $this->orderinfo['OrderInfo1']['売掛部署'], $this->orderinfo['OrderInfo1']['売上セールス'], $this->orderinfo['OrderInfo1']['経理日'], $this->orderinfo['OrderInfo6']['新中区分']);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data']);
            }
            $objErrDr = $this->Do_Excute['data'];
            for ($i = 0; $i < count($objErrDr); $i++) {
                switch ($objErrDr[$i]["ERR_NO"]) {
                    case '1':
                        array_push($aryChkMsg, "  　　①　売上部署が未登録です　部署コード＝" . $objErrDr[$i]["ERR_MSG1"]);
                        break;
                    case '2':
                        array_push($aryChkMsg, "  　　②　売掛部署が未登録です　部署コード＝" . $objErrDr[$i]["ERR_MSG1"]);
                        break;
                    case '3':
                        array_push($aryChkMsg, "  　　③　社員番号が未登録です　社員番号＝" . $objErrDr[$i]["ERR_MSG1"]);
                        break;
                    case '4':
                        array_push($aryChkMsg, "  　　④　配属先マスタの部署が違います　社員番号＝" . $objErrDr[$i]["ERR_MSG1"] . "　売掛部署＝" . $objErrDr[$i]["ERR_MSG2"] . " 配属部署＝" . $objErrDr[$i]["ERR_MSG3"]);
                        break;
                    case '5':
                        array_push($aryChkMsg, "  　　⑤　配属先マスタの職種区分が新車、中古車ではありません　職種＝" . $objErrDr[$i]["ERR_MSG1"] . "　新中区分＝" . $objErrDr[$i]["ERR_MSG2"]);
                        break;
                    case '6':
                        array_push($aryChkMsg, "  　　⑥　売上部署変換マスタにより部署を変更しました　社員番号＝" . $objErrDr[$i]["ERR_MSG1"] . "　売掛部署＝" . $objErrDr[$i]["ERR_MSG2"] . "　販売拠点" . $objErrDr[$i]["ERR_MSG3"]);
                        break;
                }
            }
            //2007/04/20 INS END
            //---20150922 li UPD S.
            //if (($this -> orderinfo['OrderInfo1']['契約店'] == "3634" || $this -> orderinfo['OrderInfo1']['契約店'] === "") && $this -> orderinfo['OrderInfo3']['車両価格'] == 0)
            //---20151116 Yin UPD S.
            //if (($this -> orderinfo['OrderInfo1']['契約店'] == "3634" || $this -> orderinfo['OrderInfo1']['契約店'] != "") && $this -> orderinfo['OrderInfo3']['車両価格'] == 0)
            if (($this->orderinfo['OrderInfo1']['契約店'] == "3634" || $this->orderinfo['OrderInfo1']['契約店'] == "") && $this->orderinfo['OrderInfo3']['車両価格'] == 0)
            //---20150922 Yin UPD E.
            //---20150922 li UPD E.
            {
                if (($this->orderinfo['OrderInfo2']['区分20'] == "40" && $this->ClsComFnc->FncNv($objDr["EC_JUCHU_KB"]) == "60")) {
                } else {
                    array_push($aryErrMsg, "  　　契約店が3634で本体価格が０です。");
                }
            }
            //---20150922 li UPD S.
            //if ($this -> orderinfo['OrderInfo1']['くくりコード'] === "")
            //---20151116 Yin UPD S.
            //if ($this -> orderinfo['OrderInfo1']['くくりコード'] != "")
            if ($this->orderinfo['OrderInfo1']['くくりコード'] == "")
            //---20150922 Yin UPD E.
            //---20150922 li UPD E.
            {
                array_push($aryErrMsg, "  　　ＵＣ親コードが未設定です。" . " 問合呼称=" . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 7, 1));
            }
            if (($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1" && $this->orderinfo['OrderInfo3']['車両価格'] != 0 && $this->orderinfo['OrderInfo3']['車両注文書原価'] == 0)) {
                array_push($aryErrMsg, "  　　原価マスタが未設定です。" . " 問合呼称=" . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 7, 1) . " 本体価格=" . $this->ClsComFnc->FncNv($objDr["SRY_HT_PRC_ZEINK"]));
                array_push(
                    $this->subErrSpreadShowData,
                    array(
                        "CMN_NO" => $this->ClsComFnc->FncNv(rtrim($objDr["CMN_NO"])),
                        "HBSS_CD" => mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 7, 1),
                        "SRY_HT_PRC_ZEINK" => $this->ClsComFnc->FncNz($objDr["SRY_HT_PRC_ZEINK"])
                    )
                );
                // $this -> subErrSpreadShowData = array(
                // "CMN_NO" => $this -> ClsComFnc -> FncNv(rtrim($objDr["CMN_NO"])),
                // "HBSS_CD" => mb_substr($this -> ClsComFnc -> mb_str_pad($this -> ClsComFnc -> FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this -> ClsComFnc -> mb_str_pad($this -> ClsComFnc -> FncNv($objDr["HBSS_CD"]), 8), 7, 1),
                // "SRY_HT_PRC_ZEINK" => $this -> ClsComFnc -> FncNz($objDr["SRY_HT_PRC_ZEINK"])
                // );
                //$this -> result['subErrSpreadShowData'] = $this -> subErrSpreadShowData;
            }
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                if ($objDr["SS_CD"] == NULL || $objDr["SS_CD"] == "") {
                    array_push($aryErrMsg, "  　　車種マスタが未登録です。" . " 問合呼称=" . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 7, 1) . " UC親=" . $this->ClsComFnc->FncNv($objDr["UCOYA_CD"]));
                }
            }
            if ($objDr["RIE_CMN_NO"] == NULL || $objDr["RIE_CMN_NO"] == "") {
                array_push($aryErrMsg, "  　　利益計算データが未入力です。");
            }
            if (($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1" && ($this->ClsComFnc->FncNz($objDr["FZH_SUM_GKU_ZEINK"]) != 0 || $this->ClsComFnc->FncNz($objDr["TKB_KSH_SUM_GKU_ZEINK"]) != 0) && $this->ClsComFnc->FncNz($objDr["KASO_CNT"]) == 0)) {
                array_push($aryErrMsg, "  　　架装データが未入力です。");
            }
            //---20150922 li UPD S.
            //if (($this -> orderinfo['OrderInfo4']['販売手数料額'] !== 0 && $this -> orderinfo['OrderInfo4']['販売手数料支払先コード'] === ""))
            //---20151116 Yin UPD S.
            //if (($this -> orderinfo['OrderInfo4']['販売手数料額'] !== 0 && $this -> orderinfo['OrderInfo4']['販売手数料支払先コード'] != ""))
            if (($this->orderinfo['OrderInfo4']['販売手数料額'] != 0 && $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] == ""))
            //---20151116 Yin UPD E.
            //---20150922 li UPD E.
            {
                array_push($aryErrMsg, "  　　販売手数料支払先コードが設定されていません。" . " 販売手数料額=" . $this->orderinfo['OrderInfo4']['販売手数料額']);
            }
            if ($this->orderinfo['OrderInfo4']['割賦元金'] > 0) {
                if ($objDr["SITO"] == NULL || $objDr["SITO"] == "") {
                    array_push($aryErrMsg, "  　　手形据置日数が入力されていません。" . " 割賦元金=" . $this->orderinfo['OrderInfo4']['割賦元金']);
                }
            }

            if ($this->orderinfo['OrderInfoA']['下取車１台目整理NO'] != "") {
                //---20150922 li UPD S.
                //if ($this -> orderinfo['OrderInfo4']['下取者査定価格'] === 0)
                //---20151116 Yin UPD S.
                //if ($this -> orderinfo['OrderInfo4']['下取者査定価格'] != 0)
                if ($this->orderinfo['OrderInfo4']['下取者査定価格'] == 0)
                //---20151116 Yin UPD E.
                //---20150922 li UPD E.
                {
                    array_push($aryErrMsg, "  　　下取車がある場合、下取車査定価格＝０ではいけません。" . " 下取車１台目整理NO =" . $this->orderinfo['OrderInfoA']['下取車１台目整理NO']);
                }
            }
            if ($this->ClsComFnc->FncNv($objDr["KYK_TOU_HNS"]) == "00000") {
                if ($this->ClsComFnc->FncNv($objDr["EC_JUCHU_KB"]) != "22" && $this->ClsComFnc->FncNv($objDr["EC_JUCHU_KB"]) != "23") {
                    array_push($aryErrMsg, "  　　契・登録店＝00000の場合、ＥＣ受注区分は２２又は２３のみ使用可能です。" . " ＥＣ受注区分 =" . $this->ClsComFnc->FncNv($objDr["EC_JUCHU_KB"]));
                }
            }
            //エラーメッセージをLOGに出力する
            //If CType(aryErrMsg.Count, Long) > 0 Then   '2007/04/20 UPD Start

            if (count($aryErrMsg) > 0 || count($aryChkMsg) > 0) {
                //2007/04/20 UPD End
                $objLog['strErrMsg'] = "　　注文書№=" . $this->orderinfo['OrderInfo1']['注文書NO2'] . " (" . $this->ClsComFnc->FncNv(rtrim($objDr["CMN_NO"])) . ")" . " UC_NO=" . $this->orderinfo['OrderInfo1']['UCNO'] . " 条件変更日=" . mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo2']['条件変更年月日'], 8), 0, 8) . " 条件変更稟議書ＮＯ=" . mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo2']['条件変更NO'], 8), 0, 8) . " 解約日=" . $this->orderinfo['OrderInfo1']['解約日'];

                $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                if (count($aryErrMsg) > 0) {
                    //2007/04/20 INS 条件追加

                    $objLog['ErrCount'] = $objLog['ErrCount'] + 1;

                    for ($intIdx = 0; $intIdx <= count($aryErrMsg) - 1; $intIdx++) {
                        $objLog['strErrMsg'] = $aryErrMsg[$intIdx];
                        $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                    }
                }
                //2007/04/20 INS Start   売上部署チェック、売掛部署チェック、社員存在チェック、配属先チェック、職種チェック
                if (count($aryChkMsg) > 0) {
                    $objLog['ChkCount'] = $objLog['ChkCount'] + 1;
                    for ($intIdx = 0; $intIdx <= count($aryChkMsg) - 1; $intIdx++) {
                        $objLog['strErrMsg'] = $aryChkMsg[$intIdx];
                        $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                    }
                }
                //2007/04/20 INS End
                $objLog['strErrMsg'] = " ";
                $this->fncN5200ErrLog($this->strErrLogName, $objLog);
            }
            $objLog['strErrMsg'] = "";

            //正常終了
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $strErrMsg = "clsCreateCsv" . "\r\n" . "FncOldCarInfoEDIT " . "\r\n" . $e->getMessage();
            $result['result'] = FALSE;
            $result['data'] = $strErrMsg;
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：注文書情報セット
    //関 数 名：FncOrderInfoEDIT
    //引    数：objdr     (I)ﾃﾞｰﾀﾘｰﾀﾞ
    //　    　　OrderInfo (I//O)　
    //　    　　ObjLog    (I/O)ﾛｸﾞ情報
    //　    　　strErrMsg (O)ｴﾗｰﾒｯｾｰｼﾞ
    //戻 り 値：
    //処理説明：注文書情報をセットする
    //**********************************************************************
    function FncOrderInfoEDIT($objDr, &$strDepend, &$strID, &$objLog, &$strErrMsg)
    {
        $objDs = array();
        // $intColCnt = 0;
        // //列数
        // $lngTranCnt = 0;
        // //処理件数
        // $objSw = "";
        // //ストリームライター
        // $strOut = "";
        // //ストリングビルダー
        // $blnChk = "";
        //$strID As String
        //新中条区分
        $strCmnNO = "";
        //注文書№
        $strSeiriNO = "";
        //中古車整理№
        $strDairitnCD = "";
        //業販店コード
        $strHanbaiKbn = "";
        //販売区分
        $strKappuKbn = "";
        //割賦区分
        // $strAddress = "";
        //住所コード
        $strUriBu = "";
        //売上部署
        $strUrkBu = "";
        //売掛部署
        // $lngJibaiTukisu = "";
        //自賠責月数
        $strCreditCD = "";
        //ｸﾚｼﾞｯﾄ会社CD
        // $strTRKKbn = "";
        //登録区分

        // $RtnCode = "";
        // $aryErrMsg = array();
        // $intIdx = 0;

        $result = [];

        try {
            //--------
            //出力処理
            //--------
            $strErrMsg = "";
            // $blnChk = True;
            // 'ID設定
            // 'If clsComFnc.FncNv(objDr("UC_NO")).ToString.Substring(0, 6) < strDepend Or _
            // '(clsComFnc.FncNv(objDr("JKN_HKD")) <> "" And _
            // '(clsComFnc.FncNv(objDr("UC_NO")).ToString.Substring(0, 6) <> clsComFnc.FncNv(objDr("JKN_HKD")).ToString.PadRight(8).Substring(0, 6))) Or _
            // '(clsComFnc.FncNv(objDr("CEL_DT")) <> "" And _
            // '(clsComFnc.FncNv(objDr("UC_NO")).ToString.Substring(0, 6) <> clsComFnc.FncNv(objDr("CEL_DT")).ToString.PadRight(8).Substring(0, 6))) Then
            // '    strID = "J"
            // 'Else
            // '    strID = "1"
            // 'End If
            $strID = "";
            if (mb_substr($this->ClsComFnc->FncNv($objDr["UC_NO"]), 0, 6) >= $strDepend || ($this->ClsComFnc->FncNv($objDr["JKN_HKD"]) != "" && mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["JKN_HKD"]), 8), 0, 6) >= $strDepend) || ($this->ClsComFnc->FncNv($objDr["CEL_DT"]) != "" && mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["CEL_DT"]), 8), 0, 6) >= $strDepend)) {
                if ($this->ClsComFnc->FncNv($objDr["JKN_HKD"]) != "" && (mb_substr($this->ClsComFnc->FncNv($objDr["UC_NO"]), 0, 6) != mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["JKN_HKD"]), 8), 0, 6)) || ($this->ClsComFnc->FncNv($objDr["CEL_DT"]) != "") && (mb_substr($this->ClsComFnc->FncNv($objDr["UC_NO"]), 0, 6) != mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["CEL_DT"]), 8), 0, 6))) {
                    $strID = "J";
                } else {
                    $strID = "1";
                }
            } else {
                //条件変更日がｾｯﾄされていない場合、利益計算ﾃﾞｰﾀ更新日が処理年月内の場合　条件変更ﾃﾞｰﾀとする

                if (mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"]), 8), 0, 6) >= $strDepend) {
                    $strID = "J";
                } else {
                    //2017-01-06 Update Start 当月売上とみなされないデータの救済措置
                    //$result['result'] = TRUE;
                    //return $result;
                    if ($this->ClsComFnc->FncNv($objDr["UC_NO"]) == '201611TAT037' && $strDepend == '201612') {
                        $strID = "1";
                    } else {
                        $result['result'] = TRUE;
                        return $result;
                    }
                    //2017-01-06 Update End 当月売上とみなされないデータの救済措置
                }
            }
            //注文書№を変換情報をｾｯﾄする
            $strDairitnCD = rtrim($this->ClsComFnc->FncNv($objDr["DAIRITN_CD"]));
            $strHanbaiKbn = rtrim($this->ClsComFnc->FncNv($objDr["HNB_KB"]));
            $strCmnNO = rtrim($this->ClsComFnc->FncNv($objDr["CMN_NO"]));
            //注文書№変換
            if (!$this->ClsComFncadd->fncChangeCmnNO($strCmnNO, $strDairitnCD, $strHanbaiKbn)) {

                //print_r("**********************");
                $objLog['strErrMsg'] = " 注文書№の変換に失敗しました。元№で出力します。" . " 注文書№＝" . $strCmnNO;
                //ログファイル作成
                $this->FncErrLog($this->strLogName, $objLog);
            }
            $strCmnNO = str_replace("-", "", $strCmnNO);

            $strSeiriNO = $this->ClsComFnc->FncNv($objDr["CHUKOSYA_NO"]);
            $strSeiriNO = rtrim($this->ClsComFnc->FncNv($objDr["CKO_CAR_SER_NO"]));
            //-----------luchao 此处无意义-----------
            if (!$this->ClsComFncadd->fncChangeCmnNO($strSeiriNO, $strDairitnCD, $strHanbaiKbn)) {
            }
            //-----------luchao 此处无意义-----------
            $strSeiriNO = str_replace("-", "", $strSeiriNO);

            //売掛部署変換
            $strUriBu = rtrim($this->ClsComFnc->FncNv($objDr["HNB_KTN_CD"]));
            // '2007/04/03 DELETE START 売上データ部署変換マスタより部署を変更するため、必要なくなった
            // ''2007/02/02 INSERT Start 月の途中で部署が変更になり、新部署ではなく前部署でデータを発生させるために処理を追加(注文書№：443N100774)
            // 'If CType(clsComFnc.FncNv(objDr("CMN_NO")).ToString.TrimEnd, String) = "443N100774" Then
            // '    strUriBu = "441"
            // 'End If
            // ''2007/02/02 INSERT End
            //
            // ''2007/03/06 INSERT Start 前部署で発生してしまったデータを新部署で発生させるために処理を追加(注文書№：441N100598)
            // 'If CType(clsComFnc.FncNv(objDr("CMN_NO")).ToString.TrimEnd, String) = "441N100598" Then
            // '    strUriBu = "443"
            // 'End If
            // ''2007/03/06 INSERT End
            // '2007/04/03 DELETE END

            switch ($strUriBu) {
                case $strUriBu >= 192 && $strUriBu <= 195:
                    $strUrkBu = "191";
                    break;
                case "221":
                case "222":
                case "223":
                case "225":
                    $strUrkBu = "221";
                    break;
                case "232":
                    $strUriBu = "231";
                    break;
                //-----------luchao 重复代码无意义-----------
                // strUriBu = "231"
                //-----------luchao 重复代码无意义-----------
                case "332":
                case "333":
                    $strUrkBu = "331";
                    break;
                default:
                    $strUrkBu = $strUriBu;
                    break;
            }

            //ｽｸﾗｯﾌﾟの場合売上部署を211とする
            if (rtrim($this->ClsComFnc->FncNv($objDr["HNB_KB"])) == "5" || rtrim($this->ClsComFnc->FncNv($objDr["HNB_KB"])) == "9") {
                $strUriBu = "211";
                //--ｽｸﾗｯﾌﾟ
            }
            //新車の場合 UCNO下3桁≠数値の場合　売上部署="168"とする
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                if (!is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["UC_NO"]), 4), 9, 3))) {
                    $strUriBu = "168";
                }
            }

            //割賦区分
            switch (rtrim($this->ClsComFnc->FncNv($objDr["SHR_KB"]))) {
                case "2":
                    $strKappuKbn = "4";
                    break;
                case "3":
                    $strKappuKbn = "1";
                    break;
                case "4":
                    $strKappuKbn = "2";
                    break;
                case "5":
                    $strKappuKbn = "3";
                    break;
                default:
                    $strKappuKbn = "";
                    break;
            }
            //ｸﾚｼﾞｯﾄ会社CD変換
            switch (rtrim($this->ClsComFnc->FncNv($objDr["CREDITCD"]))) {
                case "01":
                    $strCreditCD = "14";
                    break;
                case "02":
                    $strCreditCD = "13";
                    break;
                case "03":
                    $strCreditCD = "23";
                    break;
                case "04":
                    $strCreditCD = "22";
                    break;
                case "05":
                    $strCreditCD = "24";
                    break;
                case "06":
                    $strCreditCD = "17";
                    break;
                case "07":
                    $strCreditCD = "19";
                    break;
                case "08":
                    $strCreditCD = "28";
                    break;
                default:
                    $strCreditCD = "";
                    break;
            }
            //新中売上1編集
            //OrderInfo.OrderInfo6.作成日 = clsComFnc.FncNv(objDr("REC_CRE_DT")).ToString
            if ($this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"]) == "") {
                $this->orderinfo['OrderInfo6']['更新日'] = $this->ClsComFnc->FncNv($objDr["REC_UPD_DT"]);
            } else {
                if ($strID == "J") {
                    $this->orderinfo['OrderInfo6']['更新日'] = $this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"]);
                } else {
                    $this->orderinfo['OrderInfo6']['更新日'] = $this->ClsComFnc->FncNv($objDr["REC_UPD_DT"]);
                }
            }

            $this->orderinfo['OrderInfo6']['新中区分'] = $this->ClsComFnc->FncNv($objDr["NAU_KB"]);
            $this->orderinfo['OrderInfo6']['データ区分'] = $this->ClsComFnc->FncNv($objDr["NAU_KB"]) . $strID;
            $this->orderinfo['OrderInfo1']['ID'] = $this->ClsComFnc->FncNv($objDr["NAU_KB"]) . $strID;
            //OrderInfo.OrderInfo1.処理日 = ""
            //OrderInfo.OrderInfo1.処理時間 = ""
            $this->orderinfo['OrderInfo1']['予備'] = "";
            $this->orderinfo['OrderInfo1']['UCNO'] = $this->ClsComFnc->FncNv($objDr["UC_NO"]);
            $this->orderinfo['OrderInfo1']['AB'] = "A";
            $this->orderinfo['OrderInfo1']['注文書NO2'] = $strCmnNO;
            $this->orderinfo['OrderInfo6']['注文書NO'] = $this->ClsComFnc->FncNv(rtrim($objDr["CMN_NO"]));
            $this->orderinfo['OrderInfo1']['売上部署'] = $strUriBu;
            $this->orderinfo['OrderInfo1']['売上セールス'] = $this->ClsComFnc->FncNv($objDr["HNB_TAN_EMP_NO"]);
            $this->orderinfo['OrderInfo1']['売上業者'] = $this->ClsComFnc->FncNv($objDr["DAIRITN_CD"]);
            $this->orderinfo['OrderInfo1']['サービス'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["SVKYOTN_CD"]), 2), 0, 2);
            $this->orderinfo['OrderInfo1']['売掛部署'] = $strUrkBu;
            $this->orderinfo['OrderInfo1']['認定特需ユーザーコード'] = $this->ClsComFnc->FncNv($objDr["NTI_COP_USR_CD"]);
            $this->orderinfo['OrderInfo1']['登録日'] = $this->ClsComFnc->FncNv($objDr["TOU_DT"]);

            if ($strID == "J") {
                if ($this->ClsComFnc->FncNv($objDr["CEL_DT"]) != "" && mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["CEL_DT"]), 8), 0, 6) >= $strDepend) {
                    $this->orderinfo['OrderInfo1']['経理日'] = $this->ClsComFnc->FncNv($objDr["CEL_DT"]);
                } elseif ($this->ClsComFnc->FncNv($objDr["JKN_HKD"]) != "" && mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["JKN_HKD"]), 8), 0, 6) >= $strDepend) {
                    $this->orderinfo['OrderInfo1']['経理日'] = $this->ClsComFnc->FncNv($objDr["JKN_HKD"]);
                } else {
                    $this->orderinfo['OrderInfo1']['経理日'] = $this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"]);
                }

                //2011/02/09 UPDATE Start 条件変更で登録日が設定されないデータが存在するため、車輌売上日を設定を設定する
                //$this->OrderInfo['OrderInfo1']['売上日 = clsComFnc.FncNv(objDr("TOU_DT")).ToString
                $this->orderinfo['OrderInfo1']['売上日'] = $this->ClsComFnc->FncNv($objDr["SRY_URG_DT"]);
                //2011/02/09 UPDATE End
            } else {
                $this->orderinfo['OrderInfo1']['経理日'] = $this->ClsComFnc->FncNv($objDr["SRY_URG_DT"]);
                $this->orderinfo['OrderInfo1']['売上日'] = $this->ClsComFnc->FncNv($objDr["SRY_URG_DT"]);
            }
            $this->orderinfo['OrderInfo1']['解約日'] = $this->ClsComFnc->FncNv($objDr["CEL_DT"]);
            $this->orderinfo['OrderInfo1']['車台'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["SDI_KAT"]), 8), 0, 8);
            $this->orderinfo['OrderInfo1']['CARNO'] = $this->ClsComFnc->FncNv($objDr["CAR_NO"]);
            $this->orderinfo['OrderInfo1']['年製'] = mb_substr($this->ClsComFnc->mb_str_pad(($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") ? $this->ClsComFnc->FncNv($objDr["TOU_DT"]) : $this->ClsComFnc->FncNv($objDr["SYODO_YM"]), 4), 0, 4);
            $this->orderinfo['OrderInfo1']['指定類別型式指定'] = $this->ClsComFnc->FncNv($objDr["SITEI_NO"]);
            $this->orderinfo['OrderInfo1']['指定類別区分'] = $this->ClsComFnc->FncNv($objDr["RUIBETU_NO"]);
            $this->orderinfo['OrderInfo1']['問合呼称'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 0, 5) . mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["HBSS_CD"]), 8), 7, 1);
            $this->orderinfo['OrderInfo1']['桁８コード'] = $this->ClsComFnc->FncNv($objDr["HBSS_CD"]);
            $this->orderinfo['OrderInfo1']['新車架装整理NO'] = "";
            $this->orderinfo['OrderInfo1']['用品A'] = "";
            $this->orderinfo['OrderInfo1']['用品C'] = "";
            $this->orderinfo['OrderInfo1']['用品H'] = "";
            $this->orderinfo['OrderInfo1']['用品S'] = "";
            $this->orderinfo['OrderInfo1']['用品予備'] = "";
            $this->orderinfo['OrderInfo1']['陸事'] = $this->ClsComFnc->FncNv($objDr["RIKUJI_NM"]);
            $this->orderinfo['OrderInfo1']['登録NO1'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["TOURK_NO"]), 13), 0, 8);
            $this->orderinfo['OrderInfo1']['登録NO2'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["TOURK_NO"]), 13), 8, 1);
            $this->orderinfo['OrderInfo1']['登録NO3'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["TOURK_NO"]), 13), 9, 4);
            $this->orderinfo['OrderInfo1']['H59'] = "";

            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                //新車
                //車検年

                switch (mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["TOURK_NO"]), 13), 5, 1)) {
                    case "3":
                    case "5":
                    case "7":
                        $this->orderinfo['OrderInfo1']['車検年'] = "3";
                        break;
                    case "0":
                    case "1":
                    case "2":
                        $this->orderinfo['OrderInfo1']['車検年'] = "1";
                        break;
                    case "4":
                        $this->orderinfo['OrderInfo1']['車検年'] = "2";
                        break;
                    case "8":
                        $this->orderinfo['OrderInfo1']['車検年'] = "2";
                    default:
                        break;
                }

                // 'If clsComFnc.FncNv(objDr("SYAKEN_EXP_DT")) >= clsComFnc.FncNv(objDr("TOU_DT")) Then
                // '    $this->OrderInfo['OrderInfo1']['車検年 = DateDiff(DateInterval.Year, CDate(CInt(clsComFnc.FncNv(objDr("TOU_DT"))).ToString("00000/00/00")), CDate(CInt(clsComFnc.FncNv(objDr("SYAKEN_EXP_DT"))).ToString("00000/00/00"))).ToString("0")
                // '    If $this->OrderInfo['OrderInfo1']['車検年 > 0 And CDate(CInt(clsComFnc.FncNv(objDr("SYAKEN_EXP_DT"))).ToString("00000/00/00")).ToString("MM") < CDate(CInt(clsComFnc.FncNv(objDr("TOU_DT"))).ToString("00000/00/00")).ToString("MM") Then
                // '        $this->OrderInfo['OrderInfo1']['車検年 = CType($this->OrderInfo['OrderInfo1']['車検年, Integer) - 1
                // '    End If
                // 'End If

                $this->orderinfo['OrderInfo1']['くくりコード'] = "";
                if ($this->ClsComFnc->FncNv($objDr["UCOYA_CD"]) != "") {
                    $this->orderinfo['OrderInfo1']['くくりコード'] = $this->ClsComFnc->FncNv($objDr["UCOYA_CD"]);
                } elseif ($this->ClsComFnc->FncNv($objDr["GENKA_ID"]) != "") {
                    $this->orderinfo['OrderInfo1']['くくりコード'] = $this->ClsComFnc->FncNv($objDr["GENKA_ID"]);
                }

                $this->orderinfo['OrderInfo1']['車種コード'] = $this->ClsComFnc->FncNv($objDr["SS_CD"]);
                $this->orderinfo['OrderInfo1']['銘柄'] = "MAZ";
                $this->orderinfo['OrderInfo1']['認可型式'] = $this->ClsComFnc->FncNv($objDr["NINKATA_CD"]);
                $this->orderinfo['OrderInfo1']['社内呼称'] = "";
            } else {
                //中古車
                //車検年
                switch (mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["TOURK_NO"]), 13), 5, 1)) {
                    case "3":
                    case "5":
                    case "7":
                        if ($this->ClsComFnc->StringLength($this->ClsComFnc->FncNv($objDr["SYODO_YM"])) == 6) {
                            //---20150804 #1938 fanzhengzhou upd s.
                            //$SRY_URG_DT = new DateTime($this -> ClsComFnc -> FncNv(objDr("SRY_URG_DT")));
                            $SRY_URG_DT = new \DateTime($this->ClsComFnc->FncNv($objDr["SRY_URG_DT"]));
                            //---20150804 #1938 fanzhengzhou upd e.
                            $SRY_URG_DT = (int) $SRY_URG_DT->format('Ym');

                            $SYODO_YM = new \DateTime($this->ClsComFnc->FncNv($objDr["SYODO_YM"]) . "01");
                            $SYODO_YM = $SYODO_YM->add(new \DateInterval('P3Y'));
                            $SYODO_YM = (int) $SYODO_YM->format('Ym');
                            if ($SRY_URG_DT < $SYODO_YM) {
                                $this->orderinfo['OrderInfo1']['車検年'] = "3";
                            } else {
                                $this->orderinfo['OrderInfo1']['車検年'] = "2";
                            }
                        }
                        break;
                    case "0":
                    case "1":
                    case "2":
                        $this->orderinfo['OrderInfo1']['車検年'] = "1";
                        break;
                    case "4":
                        $this->orderinfo['OrderInfo1']['車検年'] = "2";
                        break;
                    case "8":
                        $this->orderinfo['OrderInfo1']['車検年'] = "2";
                    default:
                        break;
                }
                if ($this->ClsComFnc->FncNv($objDr["UCOYA_CD"]) != "") {
                    $this->orderinfo['OrderInfo1']['くくりコード'] = $this->ClsComFnc->FncNv($objDr["UCOYA_CD"]);
                } elseif ($this->ClsComFnc->FncNv($objDr["GENKA_ID"]) != "") {
                    $this->orderinfo['OrderInfo1']['くくりコード'] = $this->ClsComFnc->FncNv($objDr["GENKA_ID"]);
                } else {
                    $this->orderinfo['OrderInfo1']['くくりコード'] = "F";
                }
                $this->orderinfo['OrderInfo1']['中古車初度年月'] = $this->ClsComFnc->FncNv($objDr["SYODO_YM"]);
                $this->orderinfo['OrderInfo1']['車種コード'] = $this->ClsComFnc->FncNv($objDr["SS_CD"]);
                $this->orderinfo['OrderInfo1']['認可型式'] = $this->ClsComFnc->FncNv($objDr["SDI_KAT"]);
                //OrderInfo.OrderInfo1.社内呼称 = ""
                $this->orderinfo['OrderInfo1']['社内呼称'] = $this->ClsComFnc->FncNv($objDr["SDI_KAT"]);
                switch (rtrim($this->ClsComFnc->FncNv($objDr["MAKER_CD"]))) {
                    case "01":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "DAI";
                        break;
                    case "02":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "FUJ";
                        break;
                    case "03":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "HIN";
                        break;
                    case "04":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "HON";
                        break;
                    case "05":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "ISU";
                        break;
                    case "06":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "MIT";
                        break;
                    case "07":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "NIS";
                        break;
                    case "08":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "NID";
                        break;
                    case "09":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "SUZ";
                        break;
                    case "10":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "MAZ";
                        break;
                    case "11":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "TOY";
                        break;
                    case "19":
                        $this->orderinfo['OrderInfo1']['銘柄'] = "JAP";
                        break;
                    default:
                        $this->orderinfo['OrderInfo1']['銘柄'] = "FOR";
                        break;
                }
            }
            $this->orderinfo['OrderInfo1']['中古車入荷年月'] = "";
            //新中売上2編集

            //新車
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                $this->orderinfo['OrderInfo2']['区分01'] = "A";
                //--（注文書区分）(1)
                $this->orderinfo['OrderInfo2']['区分02'] = "";
                //--（架装処理区分）(1)
                $this->orderinfo['OrderInfo2']['区分03'] = "";
                //--（AIM-FLG）(1)
                $this->orderinfo['OrderInfo2']['区分04'] = $this->ClsComFnc->FncNv($objDr["AIM_KEISAKI_KB"]);
                //--（AIM-KBN）(1)
                $this->orderinfo['OrderInfo2']['区分05'] = "";
                //--（契約区分）(1)
                $this->orderinfo['OrderInfo2']['区分06'] = "";
                //--（）(1)
                $this->orderinfo['OrderInfo2']['区分07'] = "2";
                //--（所有権）(1)
                $this->orderinfo['OrderInfo2']['区分08'] = "";
                //--（所有権移転２）(1)
                $this->orderinfo['OrderInfo2']['区分09'] = $this->ClsComFnc->FncNv($objDr["VCLYOTOKBN"]);
                //--（用途区分）(1)
                $this->orderinfo['OrderInfo2']['区分10'] = $this->ClsComFnc->FncNv($objDr["CSR_KB"]);
                //--（管理区分）(1)
                $this->orderinfo['OrderInfo2']['区分11'] = $this->ClsComFnc->FncNv($objDr["HNB_ARA"]);
                //--（販売地域）(1)
                $this->orderinfo['OrderInfo2']['区分12'] = $this->ClsComFnc->FncNv($objDr["KGO_KB"]);
                //--（競合区分）(1)
                $this->orderinfo['OrderInfo2']['区分13'] = $this->ClsComFnc->FncNv($objDr["BUY_SHP"]);
                //--（購入形態）(1)
                $this->orderinfo['OrderInfo2']['区分14'] = "001";
                //--（（DZM）使用暦）(3)
                $this->orderinfo['OrderInfo2']['区分15'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["LEASE_KB"]), 3), 2, 1);
                //--（リース区分）(1)
                $this->orderinfo['OrderInfo2']['区分16'] = "";
                //--（リース区分４）(3)
                $this->orderinfo['OrderInfo2']['区分17'] = $this->ClsComFnc->FncNv($objDr["LES_SHP"]);
                //--（リース形態1）(1)
                $this->orderinfo['OrderInfo2']['区分18'] = "";
                //--（リース形態2）(1)
                $this->orderinfo['OrderInfo2']['区分19'] = $strKappuKbn;
                //--（割賦区分）(1)
                $this->orderinfo['OrderInfo2']['区分20'] = $this->ClsComFnc->FncNv($objDr["ABHOT_KB"]);
                //--（販売形態(ABﾎｯﾄ区分）(2)
                if ($this->ClsComFnc->FncNz($objDr["OPT_HOK_KIN"]) != 0) {
                    $this->orderinfo['OrderInfo2']['区分21'] = "1";
                    //--（任意保険）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分21'] = "3";
                    //--（任意保険）(1)
                }
                $this->orderinfo['OrderInfo2']['区分22'] = "";
                //--（登録区分）(1)
                $this->orderinfo['OrderInfo2']['区分23'] = "H";
                //--（在庫区分）(1)
                $this->orderinfo['OrderInfo2']['区分24'] = "2";
                //--（架装検査YN）(1)
                $this->orderinfo['OrderInfo2']['区分25'] = $this->ClsComFnc->FncNv($objDr["TKS_NYO"]);
                //--（特装内容）(2)
                $this->orderinfo['OrderInfo2']['区分26'] = $this->ClsComFnc->FncNv($objDr["TKS_KB"]);
                //--（架装区分）(1)
                $this->orderinfo['OrderInfo2']['区分28'] = "";
                //--（代納FLG）(1)
                $this->orderinfo['OrderInfo2']['区分29'] = "";
                //--（自賠責FLG）(1)
                $this->orderinfo['OrderInfo2']['区分30'] = "";
                //--（支払指定日区分）(1)
                if ($strID == "J") {
                    $this->orderinfo['OrderInfo2']['区分31'] = "";
                    //--（完済FLG）(1)
                    $this->orderinfo['OrderInfo2']['区分32'] = "";
                    //--（仕訳済FLG）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分31'] = "0";
                    //--（完済FLG）(1)
                    $this->orderinfo['OrderInfo2']['区分32'] = "*";
                    //--（仕訳済FLG）(1)
                }

                $this->orderinfo['OrderInfo2']['区分33'] = "";
                //--（直業）(1)
                $this->orderinfo['OrderInfo2']['区分34'] = "";
                //--（中古車販売区分）(1)
                $this->orderinfo['OrderInfo2']['区分35'] = "";
                //--（中古車車種区分）(1)
                $this->orderinfo['OrderInfo2']['区分36'] = "";
                //--（中古車仕入区分）(1)
                $this->orderinfo['OrderInfo2']['区分37'] = "";
                //--（中古車整備区分）(1)
                $this->orderinfo['OrderInfo2']['区分38'] = "";
                //--（中古車業名義）(1)
                $this->orderinfo['OrderInfo2']['区分39'] = "";
                //--（中古車名変FLG）(1)
                $this->orderinfo['OrderInfo2']['中古車売上親UCNO'] = "";
                //--(10)
                $this->orderinfo['OrderInfo2']['中古車売車整理NO'] = "";
            } else {
                //中古車
                $this->orderinfo['OrderInfo2']['区分01'] = "";
                //--（注文書区分）(1)
                $this->orderinfo['OrderInfo2']['区分02'] = "";
                //--（架装処理区分）(1)
                $this->orderinfo['OrderInfo2']['区分03'] = "";
                //--（AIM-FLG）(1)
                $this->orderinfo['OrderInfo2']['区分04'] = "";
                //--（AIM-KBN）(1)
                $this->orderinfo['OrderInfo2']['区分05'] = "";
                //--（契約区分）(1)
                $this->orderinfo['OrderInfo2']['区分06'] = "";
                //--（）(1)
                $this->orderinfo['OrderInfo2']['区分07'] = "";
                //--（所有権）(1)
                $this->orderinfo['OrderInfo2']['区分08'] = "";
                //--（所有権移転２）(1)
                $this->orderinfo['OrderInfo2']['区分09'] = $this->ClsComFnc->FncNv($objDr["VCLYOTOKBN"]);
                //--（用途区分）(1)
                $this->orderinfo['OrderInfo2']['区分10'] = $this->ClsComFnc->FncNv($objDr["CSR_KB"]);
                //--（管理区分）(1)
                $this->orderinfo['OrderInfo2']['区分11'] = $this->ClsComFnc->FncNv($objDr["HNB_ARA"]);
                //--（販売地域）(1)
                $this->orderinfo['OrderInfo2']['区分12'] = $this->ClsComFnc->FncNv($objDr["KGO_KB"]);
                //--（競合区分）(1)
                $this->orderinfo['OrderInfo2']['区分13'] = $this->ClsComFnc->FncNv($objDr["BUY_SHP"]);
                //--（購入形態）(1)
                $this->orderinfo['OrderInfo2']['区分14'] = "001";
                //--（（DZM）使用暦）(3)
                $this->orderinfo['OrderInfo2']['区分15'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["LEASE_KB"]), 3), 2, 1);
                //--（リース区分）(1)
                $this->orderinfo['OrderInfo2']['区分16'] = "";
                //--（リース区分４）(3)
                $this->orderinfo['OrderInfo2']['区分17'] = "";
                //--（リース形態1）(1)
                $this->orderinfo['OrderInfo2']['区分18'] = "";
                //--（リース形態2）(1)
                $this->orderinfo['OrderInfo2']['区分19'] = $strKappuKbn;
                //--（割賦区分）(1)
                $this->orderinfo['OrderInfo2']['区分20'] = $this->ClsComFnc->FncNv($objDr["ABHOT_KB"]);
                //--（販売形態(ABﾎｯﾄ区分）(2)
                if ($this->ClsComFnc->FncNz($objDr["OPT_HOK_KIN"]) != 0) {
                    $this->orderinfo['OrderInfo2']['区分21'] = "1";
                    //--（任意保険）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分21'] = "3";
                    //--（任意保険）(1)
                }

                $this->orderinfo['OrderInfo2']['区分22'] = "";
                //--（登録区分）(1)
                $this->orderinfo['OrderInfo2']['区分23'] = "H";
                //--（在庫区分）(1)
                $this->orderinfo['OrderInfo2']['区分24'] = "";
                //--（架装検査YN）(1)
                $this->orderinfo['OrderInfo2']['区分25'] = $this->ClsComFnc->FncNv($objDr["TKS_NYO"]);
                //--（特装内容）(2)
                $this->orderinfo['OrderInfo2']['区分26'] = $this->ClsComFnc->FncNv($objDr["TKS_KB"]);
                //--（架装区分）(1)
                // If clsComFnc.FncNz(objDr("HNB_TES_SKI_RYO")) <> 0 Then
                //     OrderInfo.OrderInfo2.区分27 = "1"                                              '--（課税区分）(1)
                // Else
                //     OrderInfo.OrderInfo2.区分27 = "0"                                              '--（課税区分）(1)
                // End If
                $this->orderinfo['OrderInfo2']['区分28'] = "";
                //--（代納FLG）(1)
                $this->orderinfo['OrderInfo2']['区分29'] = "";
                //--（自賠責FLG）(1)
                $this->orderinfo['OrderInfo2']['区分30'] = "";
                //--（支払指定日区分）(1)
                $this->orderinfo['OrderInfo2']['区分31'] = "0";
                //--（完済FLG）(1)
                $this->orderinfo['OrderInfo2']['区分32'] = "*";
                //--（仕訳済FLG）(1)
                if (mb_substr($this->orderinfo['OrderInfo1']['注文書NO2'], 0, 1) >= "6") {
                    $this->orderinfo['OrderInfo2']['区分33'] = "2";
                    //--（直業）(1)
                    $this->orderinfo['OrderInfo2']['区分38'] = "2";
                    //--（中古車名義FLG）(1)
                } else {
                    $this->orderinfo['OrderInfo2']['区分33'] = "1";
                    //--（直業）(1)
                    $this->orderinfo['OrderInfo2']['区分38'] = "";
                    //--（中古車名義FLG）(1)
                }

                $this->orderinfo['OrderInfo2']['区分34'] = $this->ClsComFnc->FncNv($objDr["HNB_KB"]);
                //--（中古車販売区分）(1)
                $this->orderinfo['OrderInfo2']['区分35'] = "";
                //--（中古車車種区分）(1)
                if ($this->ClsComFnc->FncNv($objDr["CKO_CAR_SER_SEQ"]) != "") {
                    //--（中古車仕入区分）(1)
                    //---20150922 li UPD S.
                    // if ($strSeiriNO >= "4")
                    if (mb_substr($strSeiriNO, 0, 1) >= "4")
                    //---20150922 li UPD E.
                    {
                        $this->orderinfo['OrderInfo2']['区分36'] = "2";
                    } else {
                        $this->orderinfo['OrderInfo2']['区分36'] = "1";
                    }
                } else {
                    $this->orderinfo['OrderInfo2']['区分36'] = "4";
                }

                $this->orderinfo['OrderInfo2']['区分37'] = "N";
                //--（中古車整備区分）(1)
                $this->orderinfo['OrderInfo2']['区分39'] = ($this->ClsComFnc->FncNz($objDr["JUURYO_ZEI"]) == 0) ? "2" : "1";
                //--（中古車業名義）(1)
                $this->orderinfo['OrderInfo2']['中古車売上親UCNO'] = "";
                //--(10)
                $this->orderinfo['OrderInfo2']['中古車売車整理NO'] = $this->ClsComFnc->FncNv($objDr["CHUKOSYA_NO"]);
                //--(9)
            }
            $this->orderinfo['OrderInfo2']['条件変更赤黒'] = "";
            //--(1)
            $this->orderinfo['OrderInfo2']['条件変更内容'] = "";
            $this->orderinfo['OrderInfo2']['条件変更年月日'] = "";
            $this->orderinfo['OrderInfo2']['条件変更NO'] = "";

            if ($strID == "J") {
                if ($this->ClsComFnc->FncNv($objDr["JKN_HKD"]) >= $this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"])) {
                    $this->orderinfo['OrderInfo2']['条件変更年月日'] = $this->ClsComFnc->FncNv($objDr["JKN_HKD"]);
                    //--(8)
                    $this->orderinfo['OrderInfo2']['条件変更NO'] = $this->ClsComFnc->FncNv($objDr["HNB_JKN_HKO_RIN_LST_NO"]);
                    //--(7)
                } else {
                    $this->orderinfo['OrderInfo2']['条件変更年月日'] = $this->ClsComFnc->FncNv($objDr["JHN_REC_UPD_DT"]);
                    //--(8)
                    if ($this->ClsComFnc->FncNv($objDr["HNB_JKN_HKO_RIN_LST_NO"]) != "") {
                        $this->orderinfo['OrderInfo2']['条件変更NO'] = $this->ClsComFnc->FncNv($objDr["HNB_JKN_HKO_RIN_LST_NO"]);
                        //--(7)
                    } else {
                        $this->orderinfo['OrderInfo2']['条件変更NO'] = "9999999";
                        //--(7)
                    }
                }
            } else {
                //2006/08/04 追加　当月条変データの場合も条変日をｾｯﾄする
                if ($this->ClsComFnc->FncNv($objDr["JKN_HKD"]) != "") {
                    $this->orderinfo['OrderInfo2']['条件変更年月日'] = $this->ClsComFnc->FncNv($objDr["JKN_HKD"]);
                    //--(8)
                    $this->orderinfo['OrderInfo2']['条件変更NO'] = $this->ClsComFnc->FncNv($objDr["HNB_JKN_HKO_RIN_LST_NO"]);
                    //--(7)
                }
            }
            $this->orderinfo['OrderInfo2']['下取車整理NO1'] = "";
            //--(9)
            $this->orderinfo['OrderInfo2']['下取車整理NO2'] = "";
            //--(9)
            $this->orderinfo['OrderInfo2']['下取車整理NO3'] = "";
            //--(9)
            $this->orderinfo['OrderInfo2']['業者名'] = "";
            //--(20)
            $this->orderinfo['OrderInfo2']['予備1'] = "";
            //--(7)
            $this->orderinfo['OrderInfo2']['契約者名称カナ'] = $this->ClsComFnc->FncNv($objDr["KYK_FGN"]);
            //--(27)
            $this->orderinfo['OrderInfo2']['名義人区分'] = $this->ClsComFnc->FncNv($objDr["USR_CSRDOSID"]);
            //--(1)
            $this->orderinfo['OrderInfo2']['予備2'] = "";
            //--(1)
            $this->orderinfo['OrderInfo2']['名義人誕生日'] = $this->ClsComFnc->FncNv($objDr["BRTDT"]);
            //--(8)
            $this->orderinfo['OrderInfo2']['名義人TEL'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["SIY_TEL"]), 15), 0, 12);
            //--(12)
            //PHP8:Only variables should be passed by reference
            //$this->orderinfo['OrderInfo2']['名義人地区CD'] = $this->ClsComFncadd->fncChangeAddrCD(rtrim($this->ClsComFnc->FncNv($objDr["USR_ADRSCD"])));
            $areaCd = rtrim($this->ClsComFnc->FncNv($objDr["USR_ADRSCD"]));
            $this->orderinfo['OrderInfo2']['名義人地区CD'] = $this->ClsComFncadd->fncChangeAddrCD($areaCd);
            //--(13)
            $this->orderinfo['OrderInfo2']['名義人軒番カナ'] = "";
            //--(20)

            //新･中･条注文書3
            $this->orderinfo['OrderInfo3']['名義人名称'] = $this->ClsComFnc->FncNv($objDr["SIY_FGN"]);
            //--(3)
            $this->orderinfo['OrderInfo3']['親2桁コード'] = "";
            //--(2)
            $this->orderinfo['OrderInfo3']['手形据置日数'] = $this->ClsComFnc->FncNz($objDr["SITO"]);
            //--(3)

            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                //新車
                if (rtrim($this->ClsComFnc->FncNv($objDr["KYK_TOU_HNS"])) == "17349" || rtrim($this->ClsComFnc->FncNv($objDr["ABHOT_KB"])) == "28") {
                    $this->orderinfo['OrderInfo3']['車両価格'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["SRY_HT_PRC_ZEINK"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["SRY_HT_NBK_GKU_ZEINK"])) + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["SRY_HTA_SHZ_GKU"]));
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両値引'] = 0;
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両注文書原価'] = $this->orderinfo['OrderInfo3']['車両価格'];
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両拠点原価'] = $this->orderinfo['OrderInfo3']['車両価格'];
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両消費税額'] = 0;
                    //--(9)
                } else {
                    $this->orderinfo['OrderInfo3']['車両価格'] = $this->ClsComFnc->FncNz($objDr["SRY_HT_PRC_ZEINK"]);
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両値引'] = $this->ClsComFnc->FncNz($objDr["SRY_HT_NBK_GKU_ZEINK"]);
                    //--(9)
                    if ($this->orderinfo['OrderInfo3']['車両価格'] != 0) {
                        $this->orderinfo['OrderInfo3']['車両注文書原価'] = $this->ClsComFnc->FncNz($objDr["TYK_PCS"]);
                        //--(9)
                        $this->orderinfo['OrderInfo3']['車両拠点原価'] = $this->ClsComFnc->FncNz($objDr["KTN_PCS"]);
                        //--(9)
                        $this->orderinfo['OrderInfo3']['車両新車車両部署別用原価'] = $this->ClsComFnc->FncNz($objDr["KTN_PCS"]);
                    } else {
                        $this->orderinfo['OrderInfo3']['車両注文書原価'] = 0;
                        $this->orderinfo['OrderInfo3']['車両拠点原価'] = 0;
                        $this->orderinfo['OrderInfo3']['車両新車車両部署別用原価'] = 0;
                    }
                    //--(9)
                    $this->orderinfo['OrderInfo3']['車両消費税額'] = $this->ClsComFnc->FncNz($objDr["SRY_HTA_SHZ_GKU"]);
                    //--(9)
                }
                $this->orderinfo['OrderInfo3']['添付品定価'] = $this->ClsComFnc->FncNz($objDr["FZH_SUM_GKU_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品値引'] = $this->ClsComFnc->FncNz($objDr["FZH_NBK_SUM_GKU_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品契約'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["FZH_SUM_GKU_ZEINK"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["FZH_NBK_SUM_GKU_ZEINK"]));
                //--(9)

                $this->orderinfo['OrderInfo3']['添付品原価'] = $this->ClsComFnc->FncNz($objDr["FZK_GNK"]);
                //--(9)

                $this->orderinfo['OrderInfo3']['添付品消費税'] = $this->ClsComFnc->FncNz($objDr["FZH_SHZ_SUM_GKU"]);
                //--(9)

                $this->orderinfo['OrderInfo3']['特別仕様3定価'] = $this->ClsComFnc->FncNz($objDr["TKB_KSH_SUM_GKU_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3値引'] = $this->ClsComFnc->FncNz($objDr["TKB_KSH_NBK_SUM_GKU_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3契約'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["TKB_KSH_SUM_GKU_ZEINK"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["TKB_KSH_NBK_SUM_GKU_ZEINK"]));
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3原価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3消費税'] = $this->ClsComFnc->FncNz($objDr["TKB_KSH_SHZ_SUM_GKU"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6定価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6値引'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6契約'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6原価'] = $this->ClsComFnc->FncNz($objDr["TKS_GNK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6消費税'] = 0;
            } else {
                //中古車
                $this->orderinfo['OrderInfo3']['車両価格'] = $this->ClsComFnc->FncNz($objDr["SRY_HT_PRC_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['車両値引'] = $this->ClsComFnc->FncNz($objDr["SRY_HT_NBK_GKU_ZEINK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['車両注文書原価'] = $this->ClsComFnc->FncNz($objDr["SRY_KNR_PCS"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['車両拠点原価'] = $this->ClsComFnc->FncNz($objDr["SRY_KNR_PCS"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['車両消費税額'] = $this->ClsComFnc->FncNz($objDr["SRY_HTA_SHZ_GKU"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['車両新車車両部署別用原価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品定価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品値引'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品契約'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["FZH_SUM_GKU_ZEINK"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["FZH_NBK_SUM_GKU_ZEINK"]));
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品原価'] = $this->ClsComFnc->FncNz($objDr["FZK_GNK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['添付品消費税'] = $this->ClsComFnc->FncNz($objDr["FZH_SHZ_SUM_GKU"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3定価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3値引'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3契約'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["TKB_KSH_SUM_GKU_ZEINK"])) - sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["TKB_KSH_NBK_SUM_GKU_ZEINK"]));
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3原価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様3消費税'] = $this->ClsComFnc->FncNz($objDr["TKB_KSH_SHZ_SUM_GKU"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6定価'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6値引'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6契約'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6原価'] = $this->ClsComFnc->FncNz($objDr["TKS_GNK"]);
                //--(9)
                $this->orderinfo['OrderInfo3']['特別仕様6消費税'] = 0;
            }
            $this->orderinfo['OrderInfo3']['車両消費税率'] = $this->ClsComFnc->FncNz($objDr["HTA_SHZ_RT"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['割賦手数料契約'] = $this->ClsComFnc->FncNz($objDr["KAP_TES"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['割賦手数料基準'] = $this->ClsComFnc->FncNz($objDr["KAP_TESURYO_KJN"]);
            $this->orderinfo['OrderInfo3']['割賦手数料消費税率'] = "00";
            //--(2)
            $this->orderinfo['OrderInfo3']['割賦手数料消費税額'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['登録諸費用3契約'] = $this->ClsComFnc->FncNz($objDr["SHOKEI"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['登録諸費用3基準'] = $this->ClsComFnc->FncNz($objDr["SYOHIKIJN"]);
            //2006/11/14 UPDATE Start    '条件を追加(法定費をプラスするのは新車の場合のみ)
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                //ABﾎｯﾄ区分＝'50'の場合
                if ($this->orderinfo['OrderInfo2']['区分20'] == "50") {
                    $this->orderinfo['OrderInfo3']['登録諸費用3基準'] = $this->orderinfo['OrderInfo3']['登録諸費用3基準'] + sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["HOUTEIHI"]));
                }
            }
            //2006/11/14 UPDATE End
            $this->orderinfo['OrderInfo3']['登録諸費用3消費税'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["SHOKEI_SHZ"]));
            //--(9)

            $this->orderinfo['OrderInfo3']['預り法廷費用'] = sprintf("%.1f", $this->ClsComFnc->FncNz($objDr["HOUTEIHI"]));
            //--(9)

            $this->orderinfo['OrderInfo3']['税金保険料'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['残債'] = $this->ClsComFnc->FncNz($objDr["TRA_CAR_ZSI_SUM"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['支払金合計'] = 0;
            //--(9)
            //OrderInfo.OrderInfo3.支払条件下取価格 = clsComFnc.FncNz(objDr("TRA_CAR_PRC_SUM"))                           '--(9)
            //OrderInfo.OrderInfo3.支払条件下取車消費税 = clsComFnc.FncNz(objDr("TRA_CAR_SHZ_SUM"))                       '--(9)
            $this->orderinfo['OrderInfo3']['支払条件下取価格'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件下取車消費税'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件頭金'] = $this->ClsComFnc->FncNz($objDr["SHR_GKN_DPS"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件登録諸費用'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件中古車負担金'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件手形回数'] = $this->ClsComFnc->FncNz($objDr["TGT_MSU"]);
            //--(2)
            $this->orderinfo['OrderInfo3']['支払条件手形金額'] = $this->ClsComFnc->FncNz($objDr["KAP_MOT_KIN"]);
            //--(9)
            $this->orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ回数'] = $this->ClsComFnc->FncNz($objDr["KRJ_BUN_KSU"]);
            //--(2)
            $this->orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ金額'] = $this->ClsComFnc->FncNz($objDr["KRJ_MOT_KIN"]);
            //--(9)

            //新･中･条注文書4
            $this->orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ会社'] = $strCreditCD;
            //--(2)
            $this->orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ承認NO'] = $this->ClsComFnc->FncNv($objDr["CRE_NO"]);
            //--(20)
            $this->orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['割賦元金'] = $this->ClsComFnc->FncNz($objDr["KAP_MOT_KIN"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['下取者買取価格'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['下取者査定価格'] = 0;
            //--(9)
            //OrderInfo.OrderInfo4.下取者査定価格 = clsComFnc.FncNz(objDr("TRA_CAR_PRC_SUM"))                            '--(9)
            $this->orderinfo['OrderInfo4']['税金自動車税'] = $this->ClsComFnc->FncNz($objDr["JIDOSYA_ZEI"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['税金車両取得税'] = $this->ClsComFnc->FncNz($objDr["SYUTOKU_ZEI"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['税金ｴｱｺﾝ取得税'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['税金ｽﾃﾚｵ取得税'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['税金重量税'] = $this->ClsComFnc->FncNz($objDr["JUURYO_ZEI"]);
            //--(9)

            $this->orderinfo['OrderInfo4']['税金消費税'] = $this->ClsComFnc->FncNz($objDr["ZEIGOUKEI"]);
            if ($this->ClsComFnc->FncNz($objDr["JIBAIHO_ZEI"]) != 0) {
                if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                    $this->orderinfo['OrderInfo4']['自賠責指定'] = "3";
                    //--(1)
                    $this->orderinfo['OrderInfo4']['自賠責会社'] = "ﾄｳ";
                    $this->orderinfo['OrderInfo4']['自賠責色コード'] = "8";
                } else {
                    $this->orderinfo['OrderInfo4']['自賠責指定'] = "";
                    //--(1)
                    $this->orderinfo['OrderInfo4']['自賠責会社'] = "";
                    $this->orderinfo['OrderInfo4']['自賠責色コード'] = "";
                }
                //--(1)
            } else {
                $this->orderinfo['OrderInfo4']['自賠責指定'] = "";
                //--(1)
                $this->orderinfo['OrderInfo4']['自賠責会社'] = "";
                $this->orderinfo['OrderInfo4']['自賠責色コード'] = "";
            }
            $this->orderinfo['OrderInfo4']['自賠責自動車種類'] = "";
            if ($this->ClsComFnc->FncNz($objDr["JIBAIHO_ZEI"]) != 0) {
                $this->orderinfo['OrderInfo4']['自賠責月数'] = $this->ClsComFnc->FncNz($objDr["JIBAIHO_TUKISU"]);
            } else {
                $this->orderinfo['OrderInfo4']['自賠責月数'] = 0;
            }
            $this->orderinfo['OrderInfo4']['自賠責保険料'] = $this->ClsComFnc->FncNz($objDr["JIBAIHO_ZEI"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['任意保険料'] = $this->ClsComFnc->FncNz($objDr["OPT_HOK_KIN"]);
            // 'If clsComFnc.FncNz(objDr("HNB_TES_SKI_RYO")) <> 0 And clsComFnc.FncNv(objDr("SKP_CD")).ToString.TrimEnd = "" Then
            // '    OrderInfo.OrderInfo4.販売手数料支払先コード = OrderInfo.OrderInfo1.売上業者
            // 'Else
            // '    OrderInfo.OrderInfo4.販売手数料支払先コード = clsComFnc.FncNv(objDr("SKP_CD"))
            // 'End If       '--(5)
            if ($this->ClsComFnc->FncNz($objDr["HNB_TES_SKI_RYO"]) != 0) {
                $this->orderinfo['OrderInfo4']['販売手数料課税非課税'] = $this->ClsComFnc->FncNz($objDr["HNB_SHZ_RT_KB"]);
                if ($this->orderinfo['OrderInfo1']['売上業者'] !== "") {
                    //--(1)
                    $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] = $this->orderinfo['OrderInfo1']['売上業者'];
                    //--(5)
                } else {
                    if ($this->ClsComFnc->FncNz($objDr["SKP_KB"]) == "2") {
                        $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] = $this->ClsComFnc->FncNv($objDr["SKP_CD"]);
                        //---20150917 li UPD S.
                        //if ($this -> orderinfo['OrderInfo4']['販売手数料支払先コード'] !== "")
                        if ($this->orderinfo['OrderInfo4']['販売手数料支払先コード'] == "")
                        //---20150917 li UPD E.
                        {
                            $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] = "9998";
                        }
                    } else {
                        $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] = "9998";
                    }
                }
                $this->orderinfo['OrderInfo4']['販売手数料額'] = $this->ClsComFnc->FncNz($objDr["HNB_TES_SKI_RYO"]);
                //--(9)
                $this->orderinfo['OrderInfo4']['販売消費税'] = $this->ClsComFnc->FncNz($objDr["HNB_SHZ_GKU"]);
            } else {
                $this->orderinfo['OrderInfo4']['販売手数料課税非課税'] = "";
                //--(1)
                $this->orderinfo['OrderInfo4']['販売手数料支払先コード'] = "";
                //--(5)
                $this->orderinfo['OrderInfo4']['販売手数料額'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo4']['販売消費税'] = 0;
                //--(9)
            }
            $this->orderinfo['OrderInfo4']['予備'] = "";
            //--(1)
            $this->orderinfo['OrderInfo4']['登録諸費用3検査'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_KENSA"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3持込車検'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3車庫証明'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_SYAKO"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3納車費用'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_NOUSYA"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3下取諸手続'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_SITA"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3査定料'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_SATEI"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3字光式'] = $this->ClsComFnc->FncNz($objDr["M_TOUROKU_JIKOU"]);
            //--(9)
            //2007/07/06 UPD Start   パックＤＥ753を登録諸費用その他より独立　パックＤＥメンテ追加
            //$this -> orderinfo['OrderInfo4']['登録諸費用3その他 = clsComFnc.FncNz(objDr("M_TOUROKU_TA"))                                                        //--(9)
            $this->orderinfo['OrderInfo4']['登録諸費用3その他'] = $this->ClsComFnc->FncNz($objDr["SONOTAGK"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['パックDE753'] = $this->ClsComFnc->FncNz($objDr["PACK753"]);
            $this->orderinfo['OrderInfo4']['パックDEメンテ'] = $this->ClsComFnc->FncNz($objDr["PACKMENTE"]);
            //2007/07/06 UPD End
            $this->orderinfo['OrderInfo4']['預り法定費用検査'] = $this->ClsComFnc->FncNz($objDr["M_AZU_KENSA"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['預り法定費用持込車検'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['預り法定費用車庫証明'] = $this->ClsComFnc->FncNz($objDr["M_AZU_SYAKO"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['預り法定費用下取'] = $this->ClsComFnc->FncNz($objDr["M_AZU_SITA"]);
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == 1) {
                $this->orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ'] = $this->ClsComFnc->FncNz($objDr["PENALTY"]);
                //--(9)
                //OrderInfo.OrderInfo4.本部負担金 = clsComFnc.FncNz(objDr("JIP_DTL_SUM"))                                 '--(9)
                $this->orderinfo['OrderInfo4']['本部負担金'] = 0;
            } else {
                //2006/10/20 UPDATE Start
                //OrderInfo.OrderInfo4.本部負担金 = CType(clsComFnc.FncNz(objDr("MJIBAIHO_ZEI")), Double) * -1
                $this->orderinfo['OrderInfo4']['本部負担金'] = 0;
                //2006/10/20 UPDATE End
                $this->orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ'] = 0;
            }
            if ($this->ClsComFnc->FncNv($objDr["ABHOT_KB"]) != "31") {
                //--購入形態
                $this->orderinfo['OrderInfo4']['打込金収入手数料'] = $this->ClsComFnc->FncNz($objDr["MUC_SUM"]);
                //対策費合計                   '--(9)
                $this->orderinfo['OrderInfo4']['打込金申請奨励金'] = 0;
                //--(9)
            } else {
                $this->orderinfo['OrderInfo4']['打込金収入手数料'] = 0;
                //--(9)
                $this->orderinfo['OrderInfo4']['打込金申請奨励金'] = $this->ClsComFnc->FncNz($objDr["MUC_SUM"]);
                //--(9)
            }
            $this->orderinfo['OrderInfo4']['割賦手数料差額'] = $this->orderinfo['OrderInfo3']['割賦手数料基準'] - $this->orderinfo['OrderInfo3']['割賦手数料契約'];
            //--(9)

            $this->orderinfo['OrderInfo4']['その他紹介料'] = $this->ClsComFnc->FncNz($objDr["M_ETCSYOKAI"]);
            //--(9)
            $this->orderinfo['OrderInfo4']['車両F号限界利益'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['営業外収益'] = $this->orderinfo['OrderInfo3']['割賦手数料契約'] - $this->orderinfo['OrderInfo3']['割賦手数料基準'];
            //--(9)
            $this->orderinfo['OrderInfo4']['最終損益'] = 0;
            if ($this->ClsComFnc->FncNv($objDr["ABHOT_KB"]) == "50") {
                //OrderInfo.OrderInfo4.特約店契約基本ﾏｰｼﾞﾝ = clsComFnc.FncNz(objDr("SRY_HT_NBK_GKU_ZEINK"))    '--(9)
                $this->orderinfo['OrderInfo4']['特約店契約基本ﾏｰｼﾞﾝ'] = 0;
            } else {
                $this->orderinfo['OrderInfo4']['特約店契約基本ﾏｰｼﾞﾝ'] = 0;
            }
            $this->orderinfo['OrderInfo4']['特約店契約累進ﾏｰｼﾞﾝ'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['特約店契約拡販奨励金'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['特約店契約特別価格'] = 0;
            //--(9)
            $this->orderinfo['OrderInfo4']['原価下取車売上仕切'] = 0;

            //新･中･条注文書5
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "2") {
                $this->orderinfo['OrderInfo4']['原価標準原価'] = 0;
                //--(9)
                //OrderInfo.OrderInfo5.中古車売車下取価格 = clsComFnc.FncNz(objDr("HNKANRI_GK"))               '--9(9)
                //OrderInfo.OrderInfo5.中古車売車査定 = clsComFnc.FncNz(objDr("HNKANRI_GK"))                   '--9(9)
                $this->orderinfo['OrderInfo5']['中古車売車下取価格'] = $this->ClsComFnc->FncNz($objDr["SIR_GK"]);
                //--9(9)
                if ($this->ClsComFnc->FncNz($objDr["SATEI_GK"]) == 0) {
                    $this->orderinfo['OrderInfo5']['中古車売車査定'] = $this->ClsComFnc->FncNz($objDr["SIR_GK"]);
                    //--9(9)
                } else {
                    $this->orderinfo['OrderInfo5']['中古車売車査定'] = $this->ClsComFnc->FncNz($objDr["SATEI_GK"]);
                    //--9(9)
                }

                $this->orderinfo['OrderInfo5']['中古車再生見積'] = $this->ClsComFnc->FncNz($objDr["SAISEI_GK"]);
                //--9(9)

                $this->orderinfo['OrderInfo5']['中古車諸掛'] = $this->orderinfo['OrderInfo3']['車両注文書原価'] - $this->orderinfo['OrderInfo5']['中古車売車査定'] - $this->orderinfo['OrderInfo5']['中古車再生見積'];

                $this->orderinfo['OrderInfo5']['中古車売車査定ソカイ'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自動車税金額'] = $this->ClsComFnc->FncNz($objDr["MJIDOSYA_ZEI"]);
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自動車税消費税'] = $this->ClsComFnc->FncNz($objDr["MJIDOSYA_SHZ"]);
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自賠責金額'] = $this->ClsComFnc->FncNz($objDr["MJIBAIHO_ZEI"]);
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自賠責消費税'] = $this->ClsComFnc->FncNz($objDr["MJIBAIHO_SHZ"]);
                //--9(9)
                $this->orderinfo['OrderInfo5']['入庫約束'] = "";
                //--X(1)
                $this->orderinfo['OrderInfo5']['DM送付'] = "";
                //--X(1)

            } else {
                $this->orderinfo['OrderInfo4']['原価標準原価'] = $this->ClsComFnc->FncNz($objDr["SIK_PCS"]);
                //--(9)
                $this->orderinfo['OrderInfo5']['中古車売車下取価格'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車売車査定'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車再生見積'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車諸掛'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車売車査定ソカイ'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自動車税金額'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自動車税消費税'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自賠責金額'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['中古車未経過自賠責消費税'] = 0;
                //--9(9)
                $this->orderinfo['OrderInfo5']['入庫約束'] = $this->ClsComFnc->FncNv($objDr["NYK_YAKUSOKU"]);
                //--X(1)
                $this->orderinfo['OrderInfo5']['DM送付'] = $this->ClsComFnc->FncNv($objDr["SRY_RRK_FKA_KB_DM"]);
                //--X(1)
            }
            $this->orderinfo['OrderInfo5']['キョウシンカイ顧客'] = "";
            //--X(1)
            $this->orderinfo['OrderInfo5']['キョウシンカイ紹介'] = "";
            //--X(1)
            $this->orderinfo['OrderInfo5']['キョウシンカイコウケン'] = "";
            //--X(1)
            $this->orderinfo['OrderInfo5']['値引率'] = 0;
            //--9(2.2)
            $this->orderinfo['OrderInfo5']['基準値引率'] = 0;
            //--9(2.2)
            $this->orderinfo['OrderInfo5']['公正証書'] = 0;
            //--9(9)
            $this->orderinfo['OrderInfo5']['JAF'] = $this->ClsComFnc->FncNz($objDr["JAF"]);
            //--9(9)
            $this->orderinfo['OrderInfo5']['KB'] = $this->ClsComFnc->FncNz($objDr["M_KB"]);
            //--9(9)
            $this->orderinfo['OrderInfo5']['預託区分'] = $this->ClsComFnc->FncNv($objDr["TOUROKU_UM"]);
            //--X(1)
            $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金'] = $this->ClsComFnc->FncNz($objDr["YOTAK_GK"]);
            //--S9(9)
            $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ資金管理費'] = $this->ClsComFnc->FncNz($objDr["SHIKIN_KNR_RYOKIN"]);
            //--S9(9)
            $this->orderinfo['OrderInfo5']['FIL'] = "";
            //--X(163)
            //税金保険料算出     '2008/03/03 INS ＪＡＦ＋パックDEメンテ＋パックDE753＋ﾘｻｲｸﾙ預託金＋ﾘｻｲｸﾙ資金管理費
            $this->orderinfo['OrderInfo3']['税金保険料'] = $this->orderinfo['OrderInfo4']['税金自動車税'] + $this->orderinfo['OrderInfo4']['税金車両取得税'] + $this->orderinfo['OrderInfo4']['税金重量税'] + $this->orderinfo['OrderInfo4']['自賠責保険料'] + $this->orderinfo['OrderInfo4']['任意保険料'] + $this->orderinfo['OrderInfo5']['中古車未経過自動車税金額'] + $this->orderinfo['OrderInfo5']['中古車未経過自賠責金額'] + $this->orderinfo['OrderInfo5']['JAF'] + $this->orderinfo['OrderInfo4']['パックDEメンテ'] + $this->orderinfo['OrderInfo4']['パックDE753'] + $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金'] + $this->orderinfo['OrderInfo5']['ﾘｻｲｸﾙ資金管理費'];
            // '税金消費税算出
            // 'OrderInfo.OrderInfo4.税金消費税 = OrderInfo.OrderInfo4.税金消費税 _
            // '                                 + OrderInfo.OrderInfo5.中古車未経過自動車税消費税 _
            // '                                 + OrderInfo.OrderInfo5.中古車未経過自賠責消費税on $e)
            // $this->orderinfo['OrderInfo4']['税金消費税'] = $this->orderinfo['OrderInfo4']['税金消費税'];

            //中古車の場合税金消費税を保険料に加算
            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "2") {
                $this->orderinfo['OrderInfo3']['税金保険料'] = $this->orderinfo['OrderInfo3']['税金保険料'] + $this->orderinfo['OrderInfo4']['税金消費税'];
            }
            $this->orderinfo['OrderInfo5']['基準値引率'] = 0.0;
            //業者名取得
            if (rtrim($this->orderinfo['OrderInfo1']['売上業者']) !== "") {
                $this->orderinfo['OrderInfo2']['業者名'] = $this->ClsComFnc->FncNv($objDr["SIY_CUS_NM1"]);
                $this->Do_Excute = $this->ClsCreateCsv->fncGYOSYASelect($this->orderinfo['OrderInfo1']['売上業者']);
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data']);
                }
                $objDs = $this->Do_Excute['data'];
                if (count($objDs) > 0) {
                    $this->orderinfo['OrderInfo2']['業者名'] = $this->ClsComFnc->FncNv($objDs[0]["DAIRITN_NM"]);
                } else {
                    if ($this->orderinfo['OrderInfo4']['販売手数料支払先コード'] !== "") {
                        $this->orderinfo['OrderInfo2']['業者名'] = $this->ClsComFnc->FncNv($objDr["SKP_NM1"]);
                        //objDs = clsComDB.Fnc_Fill(fncGYOSYASelect(OrderInfo.OrderInfo4.販売手数料支払先コード), "M27M08")
                        //If objDs.Tables("M27M08").Rows.Count > 0 Then
                        //    OrderInfo.OrderInfo2.業者名 = clsComFnc.FncNv(objDs.Tables("M27M08").Rows(0).Item("DAIRITN_NM"))
                        //End If
                    }
                }
            } else {
                if ($this->orderinfo['OrderInfo4']['販売手数料支払先コード'] !== "") {
                    $this->orderinfo['OrderInfo2']['業者名'] = $this->ClsComFnc->FncNv($objDr["SKP_NM1"]);
                    //objDs = clsComDB.Fnc_Fill(fncGYOSYASelect(OrderInfo.OrderInfo4.販売手数料支払先コード), "M27M08")
                    //If objDs.Tables("M27M08").Rows.Count > 0 Then
                    //    OrderInfo.OrderInfo2.業者名 = clsComFnc.FncNv(objDs.Tables("M27M08").Rows(0).Item("DAIRITN_NM"))
                    //End If
                }
            }

            if ($this->ClsComFnc->FncNv($objDr["NAU_KB"]) == "1") {
                //契約店／登録店
                if (trim($this->ClsComFnc->FncNv($objDr["KYK_TOU_HNS"])) == "") {
                    $this->orderinfo['OrderInfo1']['契約店'] = "3634";
                    $this->orderinfo['OrderInfo1']['登録店'] = "3634";
                } else {
                    if ($this->orderinfo['OrderInfo3']['車両価格'] == 0) {
                        $this->orderinfo['OrderInfo1']['契約店'] = $this->ClsComFnc->FncNv($objDr["KYK_TOU_HNS"]);
                        $this->orderinfo['OrderInfo1']['登録店'] = "3634";
                    } else {
                        $this->orderinfo['OrderInfo1']['契約店'] = "3634";
                        $this->orderinfo['OrderInfo1']['登録店'] = $this->ClsComFnc->FncNv($objDr["KYK_TOU_HNS"]);
                    }
                }
                if ($this->orderinfo['OrderInfo1']['登録店'] == "17349") {
                    $this->orderinfo['OrderInfo1']['登録店'] = "3634";
                }
                if ($this->orderinfo['OrderInfo2']['区分20'] == "28" && $this->ClsComFnc->FncNv($objDr["EC_JUCHU_KB"]) == "11") {
                    $this->orderinfo['OrderInfo1']['契約店'] = "17349";
                }
                //登録区分
                if ($this->orderinfo['OrderInfo1']['契約店'] == "17349" || $this->orderinfo['OrderInfo2']['区分20'] == "28" || $this->orderinfo['OrderInfo3']['車両価格'] == 0) {
                    if (is_numeric(mb_substr($this->ClsComFnc->mb_str_pad($this->orderinfo['OrderInfo1']['車種コード'], 1), 0, 1))) {
                        $this->orderinfo['OrderInfo2']['区分22'] = "4";
                    } else {
                        //---20150910 li UPD S.
                        //if ($this -> orderinfo['OrderInfo1']['登録店'] = "3634")
                        if ($this->orderinfo['OrderInfo1']['登録店'] == "3634")
                        //---20150910 li UPD E
                        {
                            $this->orderinfo['OrderInfo2']['区分22'] = "2";
                        } else {
                            $this->orderinfo['OrderInfo2']['区分22'] = "4";
                        }
                    }
                } else {
                    if ($this->orderinfo['OrderInfo1']['売上部署'] == "168") {
                        $this->orderinfo['OrderInfo2']['区分22'] = "3";
                    } else {
                        //---20150910 li UPD S.
                        //if ($this -> orderinfo['OrderInfo1']['登録店'] = "3634")
                        if ($this->orderinfo['OrderInfo1']['登録店'] == "3634")
                        //---20150910 li UPD E.
                        {
                            $this->orderinfo['OrderInfo2']['区分22'] = "1";
                        } else {
                            $this->orderinfo['OrderInfo2']['区分22'] = "3";
                        }
                    }
                }
            } else {
                //中古車
                $this->orderinfo['OrderInfo1']['契約店'] = "3634";
                $this->orderinfo['OrderInfo1']['登録店'] = "3634";
                $this->orderinfo['OrderInfo2']['区分22'] = "";
            }
            //新･中･条下取･氏名A
            $this->orderinfo['OrderInfoA']['ID'] = $this->orderinfo['OrderInfo1']['ID'];
            //--X(2)
            $this->orderinfo['OrderInfoA']['処理日'] = "";
            //--X(8)
            $this->orderinfo['OrderInfoA']['処理時間'] = "";
            //--X(6)
            $this->orderinfo['OrderInfoA']['予備1'] = "";
            //--X(4)
            $this->orderinfo['OrderInfoA']['UCNO'] = $this->orderinfo['OrderInfo1']['UCNO'];
            //--X(10)
            $this->orderinfo['OrderInfoA']['AB'] = "B";
            //--X(1)
            $this->orderinfo['OrderInfoA']['注文書NO2'] = $this->orderinfo['OrderInfo1']['注文書NO2'];
            //--X(7)
            //新･中･条下取･氏名B

            //新･中･条下取･氏名C

            //新･中･条下取･氏名D

            $this->orderinfo['OrderInfoD']['契約者キー名寄せ'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["KYK_FGN"]), 30), 0, 30);
            //--X(30)
            //PHP8:Only variables should be passed by reference
            //$this->orderinfo['OrderInfoD']['契約者キー地区コード'] = $this->ClsComFncadd->fncChangeAddrCD(rtrim($this->ClsComFnc->FncNv($objDr["KYK_ADRSCD"])));
            $regionCode = rtrim($this->ClsComFnc->FncNv($objDr["KYK_ADRSCD"]));
            $this->orderinfo['OrderInfoD']['契約者キー地区コード'] = $this->ClsComFncadd->fncChangeAddrCD($regionCode);
            //--X(13)
            $this->orderinfo['OrderInfoD']['契約者キーTEL'] = $this->ClsComFnc->FncNv($objDr["KYK_TEL"]);
            //--X(12)
            $this->orderinfo['OrderInfoD']['契約者住所軒番漢字'] = "";
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者住所通称地漢字'] = $this->ClsComFnc->FncNv($objDr["KYK_ADR3"]);
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者名称1漢字'] = $this->ClsComFnc->FncNv($objDr["KYK_CUS_NM1"]);
            //--X(40)
            $this->orderinfo['OrderInfoD']['契約者名称2漢字'] = $this->ClsComFnc->FncNv($objDr["KYK_CUS_NM2"]);
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者住所カナ'] = "";
            //--X(20)
            $this->orderinfo['OrderInfoD']['契約者名称カナ'] = $this->ClsComFnc->FncNv($objDr["KYK_FGN"]);
            //--X(40)
            $this->orderinfo['OrderInfoD']['契約者郵便番号'] = $this->ClsComFnc->FncNv($objDr["KYK_YBN_NO"]);
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者住所１'] = $this->ClsComFnc->FncNv($objDr["KYK_ADR1"]);
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者住所２'] = $this->ClsComFnc->FncNv($objDr["KYK_ADR2"]);
            //--X(30)
            $this->orderinfo['OrderInfoD']['契約者住所３'] = $this->ClsComFnc->FncNv($objDr["KYK_ADR3"]);
            //--X(30)

            //2009/12/21 INS Start   R4連携集計システムで使用するため追加
            $this->orderinfo['OrderInfoD']['契約者カテゴリーランク'] = $this->ClsComFnc->FncNv($objDr["K_CSRRANK"]);
            //契約者カテゴリーランク
            //2009/12/21 INS End

            //新･中･条下取･氏名E
            $this->orderinfo['OrderInfoE']['名義人キー名寄せ'] = mb_substr($this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDr["SIY_FGN"]), 30), 0, 30);
            //--X(30)
            //PHP8:Only variables should be passed by reference
            //$this->orderinfo['OrderInfoE']['名義人キー地区コード'] = $this->ClsComFncadd->fncChangeAddrCD(rtrim($this->ClsComFnc->FncNv($objDr["USR_ADRSCD"])));
            $regCode = rtrim($this->ClsComFnc->FncNv($objDr["USR_ADRSCD"]));
            $this->orderinfo['OrderInfoE']['名義人キー地区コード'] = $this->ClsComFncadd->fncChangeAddrCD($regCode);
            //--X(13)
            $this->orderinfo['OrderInfoE']['名義人キーTEL'] = $this->ClsComFnc->FncNv($objDr["SIY_TEL"]);
            //--X(12)
            $this->orderinfo['OrderInfoE']['名義人住所軒番漢字'] = "";
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人住所通称地漢字'] = $this->ClsComFnc->FncNv($objDr["SIY_ADR3"]);
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人名称1漢字'] = $this->ClsComFnc->FncNv($objDr["SIY_CUS_NM1"]);
            //--X(40)
            $this->orderinfo['OrderInfoE']['名義人名称2漢字'] = $this->ClsComFnc->FncNv($objDr["SIY_CUS_NM2"]);
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人住所カナ'] = "";
            //--X(20)
            $this->orderinfo['OrderInfoE']['名義人名称カナ'] = $this->ClsComFnc->FncNv($objDr["SIY_FGN"]);
            //--X(40)
            $this->orderinfo['OrderInfoE']['FIL'] = "";
            //--X(2)
            $this->orderinfo['OrderInfoE']['名義人郵便番号'] = $this->ClsComFnc->FncNv($objDr["SIY_YBN_NO"]);
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人住所１'] = $this->ClsComFnc->FncNv($objDr["SIY_ADR1"]);
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人住所２'] = $this->ClsComFnc->FncNv($objDr["SIY_ADR2"]);
            //--X(30)
            $this->orderinfo['OrderInfoE']['名義人住所３'] = $this->ClsComFnc->FncNv($objDr["SIY_ADR3"]);
            //--X(30)

            //2009/12/21 INS Start   R4連携集計システムで使用するため追加
            $this->orderinfo['OrderInfoE']['名義人カテゴリーランク'] = $this->ClsComFnc->FncNv($objDr["M_CSRRANK"]);
            //使用者カテゴリーランク

            // '2009/12/21 INS End
            // 'チェック
            // ''If strID = "J" Then
            // ''    If OrderInfo.OrderInfo2.条件変更年月日 = "" Then
            // ''        aryErrMsg.Add("  条件変更日が入力されていません。")
            // ''    End If
            // ''Else
            // ''    If OrderInfo.OrderInfo1.売上日 = "" Then
            // ''        aryErrMsg.Add("  売上日が入力されていません。")
            // ''    End If
            // ''End If
            // 'aryErrMsg.Clear()
            //
            // 'If (OrderInfo.OrderInfo1.契約店 = "3634" Or OrderInfo.OrderInfo1.契約店 = "") And _
            // '   OrderInfo.OrderInfo3.車両価格 = 0 Then
            // '    aryErrMsg.Add("  　　契約店が3634で本体価格が０です。")
            // 'End If
            // 'If OrderInfo.OrderInfo1.くくりコード = "" Then
            // '    aryErrMsg.Add("  　　ＵＣ親コードが未設定です。" & _
            // '                  " 問合呼称=" & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(0, 5) & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(7, 1))
            // 'End If
            // 'If (clsComFnc.FncNv(objDr("NAU_KB")) = "1" And OrderInfo.OrderInfo3.車両価格 <> 0 And OrderInfo.OrderInfo3.車両注文書原価 = 0) Then
            // '    aryErrMsg.Add("  　　原価マスタが未設定です。" & _
            // '                       " 問合呼称=" & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(0, 5) & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(7, 1) & _
            // '                       " 本体価格=" & clsComFnc.FncNv(objDr("SRY_HT_PRC_ZEINK")).ToString)
            // '    If FrmCom.Name = "frmChumonCSV" Then
            // '        Call subErrSpreadShow(frm1.sprList_Sheet1, clsComFnc.FncNv(objDr("CMN_NO").ToString.TrimEnd), _
            // '                             clsComFnc.FncNv(objDr("HBSS_CD").ToString).PadRight(8).Substring(0, 5) & clsComFnc.FncNv(objDr("HBSS_CD").ToString).PadRight(8).Substring(7, 1), _
            // '                             clsComFnc.FncNz(objDr("SRY_HT_PRC_ZEINK")).ToString)
            // '    Else
            // '        Call subErrSpreadShow(frm2.sprList_Sheet1, clsComFnc.FncNv(objDr("CMN_NO").ToString.TrimEnd), _
            // '                             clsComFnc.FncNv(objDr("HBSS_CD").ToString).PadRight(8).Substring(0, 5) & clsComFnc.FncNv(objDr("HBSS_CD").ToString).PadRight(8).Substring(7, 1), _
            // '                             clsComFnc.FncNz(objDr("SRY_HT_PRC_ZEINK")).ToString)
            // '    End If
            // 'End If
            // 'If clsComFnc.FncNv(objDr("NAU_KB")) = "1" Then
            // '    If (objDr("SS_CD") Is Nothing Or objDr("SS_CD") Is DBNull.Value) Then
            // '        aryErrMsg.Add("  　　車種マスタが未登録です。" & _
            // '                        " 問合呼称=" & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(0, 5) & clsComFnc.FncNv(objDr("HBSS_CD")).ToString.PadRight(8).Substring(7, 1) & _
            // '                            " UC親=" & clsComFnc.FncNv(objDr("UCOYA_CD")).ToString)
            // '    End If
            // 'End If
            // 'If objDr("RIE_CMN_NO") Is Nothing Or objDr("RIE_CMN_NO") Is DBNull.Value Then
            // '    aryErrMsg.Add("  　　利益計算データが未入力です。")
            // 'End If
            // 'If (clsComFnc.FncNv(objDr("NAU_KB")) = "1" And _
            // '   (clsComFnc.FncNz(objDr("FZH_SUM_GKU_ZEINK")) <> 0 Or clsComFnc.FncNz(objDr("TKB_KSH_SUM_GKU_ZEINK")) <> 0) And _
            // '    clsComFnc.FncNz(objDr("KASO_CNT")) = 0) Then
            // '    aryErrMsg.Add("  　　架装データが未入力です。")
            // 'End If
            // 'If (OrderInfo.OrderInfo4.販売手数料額 <> 0 And _
            // '   OrderInfo.OrderInfo4.販売手数料支払先コード = "") Then
            // '    aryErrMsg.Add("  　　販売手数料支払先コードが設定されていません。" & _
            // '                   " 販売手数料額=" & OrderInfo.OrderInfo4.販売手数料額)
            // 'End If
            // 'If OrderInfo.OrderInfo4.割賦元金 > 0 Then
            // '    If (objDr("SITO") Is Nothing Or objDr("SITO") Is DBNull.Value) Then
            // '        aryErrMsg.Add("  　　手形据置日数が入力されていません。" & _
            // '                       " 割賦元金=" & OrderInfo.OrderInfo4.割賦元金)
            // '    End If
            // 'End If
            //
            // ''エラーメッセージをLOGに出力する
            // 'If CType(aryErrMsg.Count, Long) > 0 Then
            // '    objLog.strErrMsg = "　　注文書№=" & OrderInfo.OrderInfo1.注文書NO2 & " (" & clsComFnc.FncNv(objDr("CMN_NO").ToString.TrimEnd) & ")" & _
            // '                       " UC_NO=" & OrderInfo.OrderInfo1.UCNO & _
            // '                       " 条件変更日=" & OrderInfo.OrderInfo2.条件変更年月日.PadRight(8).Substring(0, 8) & _
            // '                       " 条件変更稟議書ＮＯ=" & OrderInfo.OrderInfo2.条件変更NO.PadRight(8).Substring(0, 8) & _
            // '                       " 解約日=" & OrderInfo.OrderInfo1.解約日
            //
            // '    fncN5200ErrLog(strErrLogName, objLog)
            // '    objLog.ErrCount = objLog.ErrCount + 1
            //
            // '    For intIdx = 0 To CType(aryErrMsg.Count, Long) - 1
            // '        objLog.strErrMsg = aryErrMsg(intIdx)
            // '        fncN5200ErrLog(strErrLogName, objLog)
            // '    Next
            // 'End If
            $objLog['strErrMsg'] = "";
            //正常終了
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            $result['result'] = FALSE;
            $strErrMsg = "clsCreateCsv" . "\r\n" . "FncOrderInfoEDIT " . "\r\n" . $e->getMessage();
            $result['data'] = $strErrMsg;
            $objLog['strErrMsg'] = $e->getMessage();
        }
        return $result;
    }

    //20140121 luchao add end

    //20140214 luchao add strat
    //**********************************************************************
    //処 理 名：新中売上データ登録処理
    //関 数 名：fncSCURITOUROKU
    //引    数：strFileNM (I)ﾌｧｲﾙﾊﾟｽ
    //　    　　objdr     (I)ﾃﾞｰﾀﾘｰﾀﾞ
    //　    　　strErrMsg (I)ｴﾗｰﾒｯｾｰｼﾞ
    //　    　　blnTitle  (I)True:ﾀｲﾄﾙを出力する　False:ﾀｲﾄﾙを出力しない
    //　    　　blnCmnNO  (I)True:注文書№を変換する
    //戻 り 値：処理件数(エラーの場合は-1)
    //処理説明：新中売上データ登録処理を行う
    //**********************************************************************
    //2006/12/11 UPD 引数追加
    function fncSCURITouroku(&$orderData, &$ctlText, &$objLog, $strUpdPro)
    {
        // $lngTranCnt = 0;
        //処理件数
        // $objSw = "";
        //ストリームライター
        // $strOut = "";
        //ストリングビルダー
        $orderinfo = array();
        $lngIdx = 0;
        // $strFileSyuNm = "";
        // $strFileNm = "";
        $strErrMsg = "";
        $strUPD_DATE = "";
        $intRET = "";
        $intJyoRtn = "";
        $result = [];

        try {
            $objLog['strErrMsg'] = "";
            //データが存在する場合
            for ($lngIdx = 0; $lngIdx <= count($orderData) - 1; $lngIdx++) {
                $orderinfo = $orderData[$lngIdx];
                // '2006/09/11 UPDATE Start
                // '新規登録
                // 'If strDataKbn = "1" Then
                // '    '既存データ存在チェック
                // '    If fncEXISTSSCURI(orderinfo.OrderInfo6.注文書NO, strUPD_DATE) > 0 Then
                // '        '    strErrMsg = "同一注文書№のデータが既に登録されています。上書きしますか？" & _
                // '        '                "注文書№＝" & orderinfo.OrderInfo6.注文書NO
                // '        '    If MessageBox.Show(strErrMsg, clsComFnc.GSYSTEM_NAME, MessageBoxButtons.YesNo, _
                // '        '        MessageBoxIcon.Warning, MessageBoxDefaultButton.Button1) = DialogResult.Yes Then
                // '        '        If clsComDB.Fnc_ExecuteNonQuery(fncSCURIDelete(orderinfo.OrderInfo6.注文書NO)) < 0 Then
                // '        '            objLog.strErrMsg = strErrMsg
                // '        '            Return False
                // '        '        End If
                // '        '        If clsComDB.Fnc_ExecuteNonQuery(fncSCSITDelete(orderinfo.OrderInfo6.注文書NO)) < 0 Then
                // '        '            objLog.strErrMsg = strErrMsg
                // '        '            Return False
                // '        '        End If
                // '        '    Else
                // '        '        Return False
                // '        '    End If
                // '        If clsComDB.Fnc_ExecuteNonQuery(fncSCURIDelete(orderinfo.OrderInfo6.注文書NO)) < 0 Then
                // '            objLog.strErrMsg = strErrMsg
                // '            Return False
                // '        End If
                // '        If clsComDB.Fnc_ExecuteNonQuery(fncSCSITDelete(orderinfo.OrderInfo6.注文書NO)) < 0 Then
                // '            objLog.strErrMsg = strErrMsg
                // '            Return False
                // '        End If
                // '    End If
                // '    '売上データ登録
                // '    If clsComDB.Fnc_ExecuteNonQuery(fncSCURIInsert(orderinfo)) < 0 Then
                // '        Return False
                // '    End If
                // '    '下取データ登録
                // '    If clsComFnc.FncNv(orderinfo.OrderInfoA.下取車１台目整理NO).ToString.TrimEnd <> "" Then
                // '        If clsComDB.Fnc_ExecuteNonQuery(fncSCSITInsert(orderinfo, 1)) < 0 Then
                // '            Return False
                // '        End If
                // '    End If
                // '    If clsComFnc.FncNv(orderinfo.OrderInfoB.下取車２台目整理NO).ToString.TrimEnd <> "" Then
                // '        If clsComDB.Fnc_ExecuteNonQuery(fncSCSITInsert(orderinfo, 2)) < 0 Then
                // '            Return False
                // '        End If
                // '    End If
                // '    If clsComFnc.FncNv(orderinfo.OrderInfoC.下取車３台目整理NO).ToString.TrimEnd <> "" Then
                // '        If clsComDB.Fnc_ExecuteNonQuery(fncSCSITInsert(orderinfo, 3)) < 0 Then
                // '            Return False
                // '        End If
                // '    End If
                // '    '条変登録
                // 'Else

                //既存データ存在チェック(売上)
                $result = $this->fncEXISTSSCURI($orderinfo['OrderInfo6']['注文書NO'], $strUPD_DATE);
                $intRET = $result['result'];
                if ($intRET > 0) {
                    //---20160111 li INS S.
                    $strUPD_DATE = $this->strUPDYM_DATE;
                    //---20160111 li INS E.
                    //2006/09/11 INSERT Start
                    //既存データ存在チェック(条変)
                    $result = $this->fncEXISTSJYOUHEN($orderinfo['OrderInfo6']['注文書NO'], $strUPD_DATE);
                    $intJyoRtn = $result['result'];
                    if ($intJyoRtn == 0) {
                        //2006/09/11 INSERT End
                        //---20160111 li UPD S.
                        //if ($orderinfo['OrderInfo1']['経理日'] !== "")
                        if ($orderinfo['OrderInfo1']['経理日'] == "")
                        //---20160111 li UPD E.
                        {
                            // $a = 1;
                        }
                        // 'If intRET < 1 Then
                        // 'strErrMsg = "変更前データが登録されていません。登録しますか？" & _
                        // '               "注文書№＝" & orderinfo.OrderInfo6.注文書NO
                        // 'If MessageBox.Show(strErrMsg, clsComFnc.GSYSTEM_NAME, MessageBoxButtons.YesNo, _
                        // '    MessageBoxIcon.Warning, MessageBoxDefaultButton.Button1) = DialogResult.Yes Then
                        // 'If intRET > 0 Then
                        if (mb_substr($orderinfo['OrderInfo1']['経理日'], 0, 6) > $strUPD_DATE) {
                            //今回抽出ﾃﾞｰﾀの経理日＞現在の売上データの計上年月の場合、売上データを条件変更ﾃﾞｰﾀにINSERT
                            $result = $this->ClsCreateCsv->fncJOHENInsert($orderinfo['OrderInfo6']['注文書NO'], $strUpdPro);
                            if (!$result['result']) {
                                $objLog['strErrMsg'] = $strErrMsg;
                                throw new \Exception($result['data'], 1);
                            }
                            $result = $this->fncGetMAXNO($orderinfo['OrderInfo6']['注文書NO']);
                            if (!$result['result']) {
                                throw new \Exception($result['data'], 2);
                            } else {
                                $strErrMsg = $result['data'];
                            }
                            $result = $this->ClsCreateCsv->fncJOHENSITInsert($orderinfo['OrderInfo6']['注文書NO'], $strUpdPro);
                            if (!$result['result']) {
                                $objLog['strErrMsg'] = $strErrMsg;
                                throw new \Exception($result['data'], 1);
                            }
                        }
                    }
                    //20140218 luchao 既存エラー処理問題対応
                    elseif ($intJyoRtn < 0) {
                        throw new \Exception($result['data'], 2);
                    }
                    //20140218 luchao 既存エラー処理問題対応
                }
                //20140218 luchao 既存エラー処理問題対応
                elseif ($intRET < 0) {
                    throw new \Exception($result['data'], 2);
                }
                //20140218 luchao 既存エラー処理問題対応

                //2006/09/11 INSERT Start 今回対象の処理年月の条件変更履歴データがいた場合は削除する
                //条件変更データ削除
                $result = $this->ClsCreateCsv->fncJyuhenDelete($orderinfo['OrderInfo6']['注文書NO'], mb_substr($orderinfo['OrderInfo1']['経理日'], 0, 6));
                if (!$result['result']) {
                    $objLog['strErrMsg'] = $strErrMsg;
                    throw new \Exception($result['data'], 1);
                }
                //2006/09/11 INSERT End

                //2006/11/30 INSERT Start　今回抽出ﾃﾞｰﾀと同一年月の条件変更下取ﾃﾞｰﾀが存在している場合は削除する
                //条件変更下取ﾃﾞｰﾀ削除
                $result = $this->ClsCreateCsv->fncJYOHENSITDelete($orderinfo['OrderInfo6']['注文書NO'], mb_substr($orderinfo['OrderInfo1']['経理日'], 0, 6));
                if (!$result['result']) {
                    $objLog['strErrMsg'] = $strErrMsg;
                    throw new \Exception($result['data'], 1);
                }
                //2006/11/30 INSERT End

                //売上データをDEL/INSする(今回抽出分)
                $result = $this->ClsCreateCsv->fncSCURIDelete($orderinfo['OrderInfo6']['注文書NO']);
                if (!$result['result']) {
                    $objLog['strErrMsg'] = $strErrMsg;
                    throw new \Exception($result['data'], 1);
                }
                $result = $this->ClsCreateCsv->fncSCURIInsert($orderinfo, $strUpdPro);
                if (!$result['result']) {
                    throw new \Exception($result['data'], 1);
                }
                //下取データをDEL/INSする(今回抽出分)
                $result = $this->ClsCreateCsv->fncSCSITDelete($orderinfo['OrderInfo6']['注文書NO']);
                if (!$result['result']) {
                    $objLog['strErrMsg'] = $strErrMsg;
                    throw new \Exception($result['data'], 1);
                }
                if (rtrim($this->ClsComFnc->FncNv($orderinfo['OrderInfoA']['下取車１台目整理NO'])) !== "") {
                    $result = $this->ClsCreateCsv->fncSCSITInsert($orderinfo, 1, $strUpdPro);
                    if (!$result['result']) {
                        throw new \Exception($result['data'], 1);
                    }
                }
                if (rtrim($this->ClsComFnc->FncNv($orderinfo['OrderInfoB']['下取車２台目整理NO'])) !== "") {
                    $result = $this->ClsCreateCsv->fncSCSITInsert($orderinfo, 2, $strUpdPro);
                    if (!$result['result']) {
                        throw new \Exception($result['data'], 1);
                    }
                }
                if (rtrim($this->ClsComFnc->FncNv($orderinfo['OrderInfoC']['下取車３台目整理NO'])) !== "") {
                    $result = $this->ClsCreateCsv->fncSCSITInsert($orderinfo, 3, $strUpdPro);
                    if (!$result['result']) {
                        throw new \Exception($result['data'], 1);
                    }
                }
                //End If
                //2006/09/11 UPDATE End

                //ｶｳﾝﾄｱｯﾌﾟ(処理件数)
                $ctlText = (int) $ctlText + 1;
            }
            $ctlText = number_format($ctlText);
        } catch (\Exception $e) {
            if ($e->getCode() == 1) {
                $strErrMsg = "clsCreateCsv" . "\r\n" . "fncSCURITOUROKU" . "\r\n" . $e->getMessage();
            } else {
                $strErrMsg = $e->getMessage();
            }
            $result['result'] = FALSE;
            $result['data'] = $strErrMsg;
        }
        return $result;
    }

    public function fncEXISTSSCURI($strCMNNO, &$strUPD_Date)
    {
        $Do_Excute = "";
        $objDr = "";
        $strErrMsg = "";
        $result = array();
        try {
            $Do_Excute = $this->ClsCreateCsv->fncEXISTSSCURI($strCMNNO);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $objDr = $Do_Excute['data'];
            //該当データなし
            if (count((array) $objDr) == 0) {
                $result['result'] = 0;
            } else {
                //---20160111 li UPD S.
                // $strUPD_Date = "";
//
                // $strUPD_Date = $this -> ClsComFnc -> FncNv($objDr[0]["KEIJYO_YM"]);
                $this->strUPDYM_DATE = "";
                $this->strUPDYM_DATE = $this->ClsComFnc->FncNv($objDr[0]["KEIJYO_YM"]);
                //---20160111 li UPD E.
                $result['result'] = 1;
            }
            $result['data'] = "";
        } catch (\Exception $e) {
            $strErrMsg = "clsCreateCsv" . "\r\n" . "fncEXISTSSCURI " . "\r\n" . $e->getMessage();
            $result['result'] = -1;
            $result['data'] = $strErrMsg;
        }
        return $result;
    }

    public function fncEXISTSJYOUHEN($strCMNNO, $strUPD_Date)
    {
        $objDr = "";
        // $strMsg = "";
        $Do_Excute = "";
        $result = [];
        try {
            $Do_Excute = $this->ClsCreateCsv->fncEXISTSJYOUHEN($strCMNNO, $strUPD_Date);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $objDr = $Do_Excute['data'];
            //該当データなし
            if (count((array) $objDr) == 0) {
                $result['result'] = 0;
                //---20160111 li INS S.
                $result['data'] = "";
                //---20160111 li INS E.
            }
            //---20160111 li UPD S.
            // $result['result'] = 1;
            else {
                $result['result'] = 1;
            }
            //---20160111 li UPD E.

        } catch (\Exception $e) {
            $strErrMsg = "clsCreateCsv" . "\r\n" . "fncEXISTSJYOUHEN " . "\r\n" . $e->getMessage();
            $result['result'] = -1;
            $result['data'] = $strErrMsg;
        }
        return $result;
    }

    //**********************************************************************
    //処 理 名：前回取得日取得
    //関 数 名：fncGetMAXNO
    //引    数：strTableId：ﾃｰﾌﾞﾙID　
    //戻 り 値：取得日
    //処理説明：ﾃﾞｰﾀ受信ﾃｰﾌﾞﾙより前回CSV作成日付を取得する
    //**********************************************************************
    public function fncGetMAXNO($strNO)
    {
        $objDr = "";
        $strMsg = "";
        $strGetDate = "";
        $result = [];
        $Do_Excute = "";
        try {
            $Do_Excute = $this->ClsCreateCsv->fncGetMAXNO($strNO);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $objDr = $Do_Excute['data'];
            //該当データあり
            if (count((array) $objDr) > 0) {
                $strGetDate = $this->ClsComFnc->FncNv($objDr[0]["NO"]);
            }
            $result['result'] = TRUE;
            $result['data'] = $strGetDate;
        } catch (\Exception $e) {
            $strMsg = "clsCSVCreate " . "\r\n" . "fncGetNO " . "\r\n" . $e->getMessage();
            $result['result'] = FALSE;
            $result['data'] = $strMsg;
        }
        return $result;
    }

    public function fncEXISTSJYOHEN($strCMNNO, &$strDate)
    {
        $objDr = "";
        $strMsg = "";
        $result = [];
        $Do_Excute = "";
        try {
            $Do_Excute = $this->ClsCreateCsv->fncEXISTSJYOHEN($strCMNNO, $strDate);
            if (!$Do_Excute['result']) {
                throw new \Exception($Do_Excute['data']);
            }
            $objDr = $Do_Excute['data'];
            //該当データなし
            if (count((array) $objDr) == 0) {
                $result['result'] = 0;
            }
            $result['result'] = 1;
        } catch (\Exception $e) {
            // $strErrMsg = "clsCreateCsv" . "\r\n" . "fncEXISTSSCURIKEIJYO " . "\r\n" . $e->getMessage();
            $result['result'] = FALSE;
            $result['data'] = $strMsg;
        }
        return $result;
    }

    //20140214 luchao add end

    //**********************************************************************
    //処 理 名：新中売上データ登録処理
    //関 数 名：fncSCURICreate2
    //引    数：無し
    //戻 り 値：無し
    //処理説明：新中売上データの登録処理を行う
    //**********************************************************************

    public function fncSCURICreate2(&$objLog, $frm1, $strUpdPro, $strDepend = "", $strFromDate = "", $strToDate = "")
    {
        $strSCKbn = "";
        $objDrErr = "";
        $lngcnt = 0;
        $NewData = array();
        $UsedData = array();
        $NewChangeData = array();
        $UsedChangeData = array();
        $strDATE = "";
        $strYMD = "";
        $strTIME = "";
        $objDr = array();
        $strId = "";
        $strErrMsg = array();

        $result = [];

        try {

            //CSV注文書データ
            $objLog['strDataNM'] = "新車中古車ﾃﾞｰﾀ作成";

            //新中区分選択区分ｾｯﾄ

            if ($frm1['rdoFlag'] == "1") {
                $strSCKbn = "1";
            } elseif ($frm1['rdoFlag'] == "2") {
                $strSCKbn = "2";
            } else {
                $strSCKbn = "";
            }

            $this->Do_Excute = $this->ClsCreateCsv->fncChkUCNO($strFromDate, $strToDate, $strSCKbn);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data'], 1);
            }

            $objDrErr = $this->Do_Excute['data'];

            for ($i = 0; $i < count($objDrErr); $i++) {
                //1列目
                $objLog['strErrMsg'] = "　　注文書№=" . $this->ClsComFnc->FncNv($objDrErr[$i]["CMN_NO"]);
                $objLog['strErrMsg'] .= " UC_NO=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["UC_NO"]), 12);
                $objLog['strErrMsg'] .= " 条件変更日=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["JKN_HKD"]), 8);
                $objLog['strErrMsg'] .= " 条件変更稟議書ＮＯ=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["HNB_JKN_HKO_RIN_LST_NO"]), 8);
                $objLog['strErrMsg'] .= " 解約日=" . $this->ClsComFnc->mb_str_pad($this->ClsComFnc->FncNv($objDrErr[$i]["CEL_DT"]), 8);

                $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                //2列目
                $objLog['strErrMsg'] = $this->ClsComFnc->FncNv($objDrErr[$i]["ERR_MSG"]);
                $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                //3列目
                $objLog['strErrMsg'] = " ";
                $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                //チェック件数をセット
                $objLog['ChkCount'] = $objLog['ChkCount'] + 1;
            }

            //データリーダの開放
            if (isset($objDrErr)) {
                unset($objDrErr);
            }
            //抽出対象注文書番号ﾃｰﾌﾞﾙを削除する

            $this->Do_Excute = $this->ClsCreateCsv->fncDeleteWK_CHMNO();
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data'], 1);
            }

            //抽出対象注文書番号ﾃｰﾌﾞﾙを作成する
            $this->Do_Excute = $this->ClsCreateCsv->fncInsertWK_CHMNO_UCNO($strDepend, $strFromDate, $strToDate, $strSCKbn);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data'], 1);
            } else {
                $lngcnt = $this->Do_Excute['number_of_rows'];
            }
            $objLog["lngCount"] = $lngcnt;

            if ($lngcnt <= 0) {
                $objLog["strState"] = "NG";
                $objLog["lngCount"] = 0;
                $objLog["strErrMsg"] = "該当ﾃﾞｰﾀは存在しません";
                $result['result'] = FALSE;
            } else {
                $objLog["lngCount"] = $lngcnt;

                //---------------------------------------------------------
                //データリーダに格納
                //---------------------------------------------------------
                $this->Do_Excute = $this->ClsCreateCsv->fncChuSelect();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 1);
                }
                $objDr = $this->Do_Excute['data'];

                if (count($objDr) > 0) {
                    $NewData = array();
                    $UsedData = array();
                    $NewChangeData = array();
                    $UsedChangeData = array();
                    $strDATE = $this->ClsComFnc->FncGetSysDate('Ymdhis');
                    $strYMD = mb_substr($strDATE, 0, 8);
                    $strTIME = mb_substr($strDATE, 8, 6);
                    //データが存在する場合

                    for ($i = 0; $i < count($objDr); $i++) {
                        $this->orderinfo = $this->orderinfo_INT;
                        $this->orderinfo['OrderInfo1']['処理日'] = $strYMD;
                        $this->orderinfo['OrderInfo1']['処理時間'] = $strTIME;
                        //---------------------------------------------------------
                        //注文データ情報編集
                        //---------------------------------------------------------

                        $this->Do_Excute = $this->FncOrderInfoEDIT($objDr[$i], $strDepend, $strId, $objLog, $strErrMsg);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data'], 2);
                        }

                        //---------------------------------------------------------
                        //注文下取データ情報編集
                        //---------------------------------------------------------
                        if ($this->orderinfo['OrderInfo1']['ID'] !== "") {
                            $this->orderinfo['OrderInfoA']['処理日'] = $strYMD;
                            $this->orderinfo['OrderInfoA']['処理時間'] = $strTIME;
                            $this->Do_Excute = $this->FncOldCarInfoEDIT($objDr[$i], $orderinfo, $objLog, $strErrMsg);

                            if (!$this->Do_Excute['result']) {
                                throw new \Exception($this->Do_Excute['data'], 2);
                            }
                            if ($this->ClsComFnc->FncNv($objDr[$i]["NAU_KB"]) == "1") {
                                if ($strId == "1") {
                                    //新車情報
                                    array_push($NewData, $this->orderinfo);
                                } else {
                                    //新車条件変更情報
                                    array_push($NewChangeData, $this->orderinfo);
                                }
                            } else {
                                if ($strId == "1") {
                                    //中古車情報
                                    array_push($UsedData, $this->orderinfo);
                                } else {
                                    //中古車条件変更情報
                                    array_push($UsedChangeData, $this->orderinfo);
                                }
                            }
                        }
                    }

                }

                $result['frmData']['lblCnt'] = array(
                    'NewDataA' => $this->ClsComFnc->mb_str_pad(count($NewData), 4, " ", STR_PAD_LEFT),
                    "NewChangeDataA" => $this->ClsComFnc->mb_str_pad(count($NewChangeData), 4, " ", STR_PAD_LEFT),
                    "UsedDataA" => $this->ClsComFnc->mb_str_pad(count($UsedData), 4, " ", STR_PAD_LEFT),
                    "UsedChangeDataA" => $this->ClsComFnc->mb_str_pad(count($UsedChangeData), 4, " ", STR_PAD_LEFT)
                );

                //---------------------------------------------------------
                //新車情報出力
                //---------------------------------------------------------
                //2006/12/11 UPD 引数追加　Start

                if (count($NewData) > 0) {

                    $this->Do_Excute = $this->fncSCURITouroku($NewData, $frm1['lblCntNew'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //中古車情報出力
                //---------------------------------------------------------
                if (count($UsedData) > 0) {

                    $this->Do_Excute = $this->fncSCURITouroku($UsedData, $frm1['lblCntUsed'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //新車条件変更情報出力
                //---------------------------------------------------------
                if (count($NewChangeData) > 0) {

                    $this->Do_Excute = $this->fncSCURITouroku($NewChangeData, $frm1['lblCntNewChg'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                //---------------------------------------------------------
                //中古車条件変更情報出力
                //---------------------------------------------------------
                if (count($UsedChangeData) > 0) {

                    $this->Do_Excute = $this->fncSCURITouroku($UsedChangeData, $frm1['lblCntUsedChg'], $objLog, $strUpdPro);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                }

                $objLog['strState'] = "OK";
                $objLog['lngCount'] = $lngcnt;
                //ログ出力
                $this->fncDownLoadLog($this->strLogPath, $objLog);

                $result['result'] = TRUE;

            }
        } catch (\Exception $e) {
            if ($e->getCode() == 1) {
                $strErrMsg = "clsCreateCsv" . "\r\n" . "fncSCURICreate " . "\r\n" . $e->getMessage();
                $objLog['strErrMsg'] = $e->getMessage();
                $objLog['lngCount'] = -1;
                $result['result'] = FALSE;
            } else {
                $strErrMsg = $e->getMessage();
                $result['result'] = FALSE;
            }

        }

        $result['subErrSpreadShowData'] = $this->subErrSpreadShowData;
        $result['strErrMsg'] = $strErrMsg;
        $result['frm1'] = $frm1;
        $this->result = $result;

        return $this->result;
    }

    //********************************************************************
    //売上データチェック（CSV作成）         fan add  start.
    //********************************************************************
    //**********************************************************************
    //処 理 名：注文書CSVファイル編集処理
    //関 数 名：fncCsvChuCreate
    //引    数：strSelDate:処理時間  objLog:ﾛｸﾞ情報
    //戻 り 値：無し
    //処理説明：PTOS用新中売上データ取込形式データの編集処理
    //**********************************************************************
    // Optional ByRef intState As Integer = 0追加　ログ管理
    // strFileNM追加
    public function fncCsvChuCreate(&$objLog, $frm1, $strDepend, $strFromDate, $strToDate, &$intState, &$lngOutCnt)
    {
        // $strRtn = "";
        $strErrMsg = "";
        $lngcnt = 0;
        // $strSelDate = "";
        $strSCKbn = "";
        // $objSw = "";
        $objDr = array();
        $objDrErr = array();
        $NewData = array();
        $UsedData = array();
        $NewChangeData = array();
        $UsedChangeData = array();
        $strDATE = "";
        $strYMD = "";
        $strTIME = "";
        $strId = "";
        $strErrMsg = array();
        $blnCon = FALSE;
        $result = [];
        try {

            // $FrmCom = $frm1;

            //新中区分選択区分ｾｯﾄ
            if ($frm1['rdoFlag'] == "1") {
                $strSCKbn = "1";
            } elseif ($frm1['rdoFlag'] == "2") {
                $strSCKbn = "2";
            } else {
                $strSCKbn = "";
            }
            $this->ClsCreateCsv = new ClsCreateCsv();
            $Do_conn = $this->ClsCreateCsv->Do_conn();
            if (!$Do_conn) {
                throw new \Exception($Do_conn['data'], 1);
            }
            $blnCon = TRUE;
            $this->ClsCreateCsv->Do_transaction();
            //UC№エラーチェック
            $objDr = $this->ClsCreateCsv->fncChkUCNO($strFromDate, $strToDate, $strSCKbn);

            if (!$objDr['result']) {
                throw new \Exception($objDr['data'], 1);
            }

            $objDrErr = $objDr['data'];

            for ($i = 0; $i < count((array) $objDrErr); $i++) {
                //1列目
                $objLog['strErrMsg'] = "　　注文書№=" . $this->ClsComFnc->FncNv($objDrErr[$i]["CMN_NO"]);
                $objLog['strErrMsg'] .= " UC_NO=" . $this->ClsComFnc->FncNv($objDrErr[$i]["UC_NO"]);
                $objLog['strErrMsg'] .= " 条件変更日=" . $this->ClsComFnc->FncNv($objDrErr[$i]["JKN_HKD"]);
                $objLog['strErrMsg'] .= " 条件変更稟議書ＮＯ=" . $this->ClsComFnc->FncNv($objDrErr[$i]["HNB_JKN_HKO_RIN_LST_NO"]);
                $objLog['strErrMsg'] .= " 解約日=" . $this->ClsComFnc->FncNv($objDrErr[$i]["CEL_DT"]);

                $this->fncN5200ErrLog($this->strErrLogName, $objLog);
                //2列目
                $objLog['strErrMsg'] = $this->ClsComFnc->FncNv($objDrErr[$i]["ERR_MSG"]);
                $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                //3列目
                $objLog['strErrMsg'] = " ";
                $this->fncN5200ErrLog($this->strErrLogName, $objLog);

                //チェック件数をセット
                $objLog['ChkCount'] = $objLog['ChkCount'] + 1;
            }

            //データリーダの開放
            if (isset($objDrErr)) {
                unset($objDrErr);
            }
            //抽出対象注文書番号ﾃｰﾌﾞﾙを削除する

            $this->Do_Excute = $this->ClsCreateCsv->fncDeleteWK_CHMNO();
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data'], 1);
            }

            //抽出対象注文書番号ﾃｰﾌﾞﾙを作成する
            $this->Do_Excute = $this->ClsCreateCsv->fncInsertWK_CHMNO_UCNO($strDepend, $strFromDate, $strToDate, $strSCKbn);
            if (!$this->Do_Excute['result']) {
                throw new \Exception($this->Do_Excute['data'], 1);
            } else {
                $lngcnt = $this->Do_Excute['number_of_rows'];
            }
            $objLog["lngCount"] = $lngcnt;
            if ($lngcnt <= 0) {
                $objLog["strState"] = "NG";
                $objLog["lngCount"] = 0;
                /** ログ管理対応 **/
                for ($intStateIdx = 0; $intStateIdx < 8; $intStateIdx++) {
                    $intState[$intStateIdx] = 1;
                }
                $objLog["strErrMsg"] = "該当ﾃﾞｰﾀは存在しません";
                $result['result'] = FALSE;
            } else {
                $objLog['lngCount'] = $lngcnt;

                //CSV注文書データ
                $objLog['strDataNM'] = "N5200用新車中古車ﾃﾞｰﾀCSV作成";
                // intState, lngoutcntを追加  ログ管理
                // strOutFileNM を追加　ログ管理
                // If Not fncChuSelect(strDepend, objLog, intState, lngOutCnt) Then
                // objLog.strState = "NG"
                // objLog.lngCount = 0
                // //ログ出力
                // clsCreateCsv.fncDownLoadLog(clsCreateCsv.strLogPath, objLog)
                // Return False
                // End If
                //---------------------------------------------------------
                //データリーダに格納
                //---------------------------------------------------------
                $this->Do_Excute = $this->ClsCreateCsv->fncChuSelect();
                if (!$this->Do_Excute['result']) {
                    throw new \Exception($this->Do_Excute['data'], 2);
                }
                $objDr = $this->Do_Excute['data'];
                if (count($objDr) > 0) {
                    $NewData = array();
                    $UsedData = array();
                    $NewChangeData = array();
                    $UsedChangeData = array();
                    $strDATE = $this->ClsComFnc->FncGetSysDate('Ymdhis');
                    $strYMD = mb_substr($strDATE, 0, 8);
                    $strTIME = mb_substr($strDATE, 8, 6);
                    //データが存在する場合

                    for ($i = 0; $i < count($objDr); $i++) {
                        $this->orderinfo = $this->orderinfo_INT;
                        $this->orderinfo['OrderInfo1']['処理日'] = $strYMD;
                        $this->orderinfo['OrderInfo1']['処理時間'] = $strTIME;
                        //---------------------------------------------------------
                        //注文データ情報編集
                        //---------------------------------------------------------

                        $this->Do_Excute = $this->FncOrderInfoEDIT($objDr[$i], $strDepend, $strId, $objLog, $strErrMsg);
                        if (!$this->Do_Excute['result']) {
                            throw new \Exception($this->Do_Excute['data'], 2);
                        }

                        //---------------------------------------------------------
                        //注文下取データ情報編集
                        //---------------------------------------------------------
                        if ($this->orderinfo['OrderInfo1']['ID'] !== "") {
                            $this->orderinfo['OrderInfoA']['処理日'] = $strYMD;
                            $this->orderinfo['OrderInfoA']['処理時間'] = $strTIME;
                            $this->Do_Excute = $this->FncOldCarInfoEDIT($objDr[$i], $orderinfo, $objLog, $strErrMsg);
                            if (!$this->Do_Excute['result']) {
                                throw new \Exception($this->Do_Excute['data'], 2);
                            }
                            if ($this->ClsComFnc->FncNv($objDr[$i]["NAU_KB"]) == "1") {
                                if ($strId == "1") {
                                    //新車情報
                                    array_push($NewData, $this->orderinfo);
                                } else {
                                    //新車条件変更情報
                                    array_push($NewChangeData, $this->orderinfo);
                                }
                            } else {
                                if ($strId == "1") {
                                    //中古車情報
                                    array_push($UsedData, $this->orderinfo);
                                } else {
                                    //中古車条件変更情報
                                    array_push($UsedChangeData, $this->orderinfo);
                                }
                            }
                        }
                    }

                }

                $result['frmData']['lblCnt'] = array(
                    'NewDataA' => $this->ClsComFnc->mb_str_pad(count($NewData), 4, " ", STR_PAD_LEFT),
                    "NewChangeDataA" => $this->ClsComFnc->mb_str_pad(count($NewChangeData), 4, " ", STR_PAD_LEFT),
                    "UsedDataA" => $this->ClsComFnc->mb_str_pad(count($UsedData), 4, " ", STR_PAD_LEFT),
                    "UsedChangeDataA" => $this->ClsComFnc->mb_str_pad(count($UsedChangeData), 4, " ", STR_PAD_LEFT)
                );

                //---------------------------------------------------------
                //新車情報出力
                //---------------------------------------------------------

                if (count($NewData) > 0) {
                    $this->Do_Excute = $this->fncOrderOutput($this->strNewCsvPath, "11", $NewData, $frm1['lblCntNew'], $objLog, $intState[0], $lngOutCnt[0], $intState[1], $lngOutCnt[1]);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                } else {
                    //ログ管理
                    $intState[0] = 1;
                    $intState[1] = 1;
                }

                //---------------------------------------------------------
                //中古車情報出力
                //---------------------------------------------------------
                if (count($UsedData) > 0) {

                    $this->Do_Excute = $this->fncOrderOutput($this->strUsedCsvPath, "21", $UsedData, $frm1['lblCntUsed'], $objLog, $intState[2], $lngOutCnt[2], $intState[3], $lngOutCnt[3]);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                } else {
                    //ログ管理
                    $intState[2] = 1;
                    $intState[3] = 1;
                }

                //---------------------------------------------------------
                //新車条件変更情報出力
                //---------------------------------------------------------
                if (count($NewChangeData) > 0) {

                    $this->Do_Excute = $this->fncOrderOutput($this->strNewChangeCsvPath, "1J", $NewChangeData, $frm1['lblCntNewChg'], $objLog, $intState[4], $lngOutCnt[4], $intState[5], $lngOutCnt[5]);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                } else {
                    //ログ管理
                    $intState[4] = 1;
                    $intState[5] = 1;
                }

                //---------------------------------------------------------
                //中古車条件変更情報出力
                //---------------------------------------------------------
                if (count($UsedChangeData) > 0) {

                    $this->Do_Excute = $this->fncOrderOutput($this->strUsedChangeCsvPath, "2J", $UsedChangeData, $frm1['lblCntUsedChg'], $objLog, $intState[6], $lngOutCnt[6], $intState[7], $lngOutCnt[7]);
                    if (!$this->Do_Excute['result']) {
                        $this->Do_Excute['result'] = FALSE;
                        throw new \Exception($this->Do_Excute['data'], 2);
                    }
                } else {
                    //ログ管理
                    $intState[6] = 1;
                    $intState[7] = 1;
                }
                $this->ClsCreateCsv->Do_commit();
                $objLog['strState'] = "OK";
                $objLog['lngCount'] = $lngcnt;
                //ログ出力
                $this->fncDownLoadLog($this->strLogPath, $objLog);
                $result['result'] = TRUE;
            }

        } catch (\Exception $e) {
            if ($e->getCode() == 1) {
                $strErrMsg = "HMPTOS" . "\r\n" . "fncCsvChuCreate " . "\r\n" . $e->getMessage();
                $objLog['strErrMsg'] = $e->getMessage();
                $objLog['lngCount'] = -1;
                $result['result'] = FALSE;
            } else {
                $strErrMsg = $e->getMessage();
                $result['result'] = FALSE;
                $objLog['strState'] = "NG";
                $objLog['lngCount'] = 0;
                //ログ出力
                $this->fncDownLoadLog($this->strLogPath, $objLog);
            }

        }
        if ($result['result'] == FALSE) {
            $this->ClsCreateCsv->Do_rollback();
        }
        if ($blnCon == TRUE) {
            $this->ClsCreateCsv->Do_close();
        }
        $result['subErrSpreadShowData'] = $this->subErrSpreadShowData;
        $result['strErrMsg'] = $strErrMsg;
        $result['frm1'] = $frm1;
        $this->result = $result;
        return $this->result;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncCHUOutput
    //引    数：strFileNM (I)ﾌｧｲﾙﾊﾟｽ
    //　    　　objdr     (I)ﾃﾞｰﾀﾘｰﾀﾞ
    //　    　　strErrMsg (I)ｴﾗｰﾒｯｾｰｼﾞ
    //　    　　blnTitle  (I)True:ﾀｲﾄﾙを出力する　False:ﾀｲﾄﾙを出力しない
    //    　　blnCmnNO  (I)True:注文書№を変換する
    //戻 り 値：処理件数(エラーの場合は-1)
    //処理説明：CSVファイルを出力する
    //**********************************************************************
    public function fncOrderOutput($strCsvPath, $strDataKbn, &$orderData, &$ctlText, $objLog, &$intStateA = 0, &$lngOutCntA = 0, &$intStateB = 0, &$lngOutCntB = 0)
    {
        $lngTranCnt = 0;
        //処理件数
        $objSw = "";
        //ストリームライター
        $strOut = "";
        //ストリングビルダー
        $orderinfo = array();
        $lngIdx = 0;
        $strFileSyuNm = "";
        $strFileNm = "";
        // $strErrMsg = "";
        $result = array();
        try {

            if ($strDataKbn == "1J" || $strDataKbn == "2J") {
                $strFileSyuNm = $this->strChangeFileName . $strDataKbn;
            } else {
                $strFileSyuNm = $this->strOrderFileName . $strDataKbn;
            }
            //--------------------------------------------------------------------------------
            //新中条売上ﾌｧｲﾙ１出力
            //--------------------------------------------------------------------------------
            $strFileNm = $strCsvPath . $strFileSyuNm . "A.CSV";

            $objSw = fopen($strFileNm, "w+");
            if (!$objSw) {
                throw new \Exception("Can't open file.");
            }
            $objLog['strErrMsg'] = "";
            //print_r($orderData[0]);
            //データが存在する場合
            for ($lngIdx = 0; $lngIdx <= count($orderData) - 1; $lngIdx++) {
                $orderinfo = $orderData[$lngIdx];
                //初期化
                $strOut = "";
                $strOut .= "\"" . $orderinfo['OrderInfo1']['ID'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['処理日'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['処理時間'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['予備'] . "\"";
                //$strOut.=",\"" . orderinfo.OrderInfo1.UCNO.ToString.PadRight(12).Substring(2, 10) . "\"";
                $strOut .= ",\"" . substr(str_pad($orderinfo['OrderInfo1']['UCNO'], 12), 2, 10) . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['AB'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['注文書NO2'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['売上部署'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['売上セールス'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['売上業者'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['サービス'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['売掛部署'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['契約店'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['登録店'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['認定特需ユーザーコード'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['登録日'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['売上日'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['経理日'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['解約日'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['車台'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['CARNO'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['銘柄'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['年製'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['指定類別型式指定'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['指定類別区分'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['車種コード'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['問合呼称'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['桁８コード'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['新車架装整理NO'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['用品A'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['用品C'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['用品H'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['用品S'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['用品予備'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['陸事'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['登録NO1'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['登録NO2'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['登録NO3'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['H59'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['車検年'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['くくりコード'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['認可型式'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['社内呼称'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['中古車初度年月'] . "\"";
                $strOut .= ",\"" . $orderinfo['OrderInfo1']['中古車入荷年月'] . "\"";

                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分01'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分02'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分03'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分04'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分05'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分06'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分07'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分08'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分09'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分10'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分11'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分12'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分13'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分14'] . "\"";
                //--(3)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分15'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分16'] . "\"";
                //--(3)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分17'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分18'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分19'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分20'] . "\"";
                //--(2)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分21'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分22'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分23'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分24'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分25'] . "\"";
                //--(2)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分26'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分27'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分28'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分29'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分30'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分31'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分32'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分33'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分34'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分35'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分36'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分37'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分38'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['区分39'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['中古車売上親UCNO'] . "\"";
                //--(10)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['中古車売車整理NO'] . "\"";
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['条件変更赤黒'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['条件変更内容'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['条件変更年月日'] . "\"";
                //--(8)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['条件変更NO'] . "\"";
                //--(7)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['下取車整理NO1'] . "\"";
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['下取車整理NO2'] . "\"";
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['下取車整理NO3'] . "\"";
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['業者名'] . "\"";
                //--(20)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['予備1'] . "\"";
                //--(7)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['契約者名称カナ'] . "\"";
                //--(27)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['名義人区分'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['予備2'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['名義人誕生日'] . "\"";
                //--(8)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['名義人TEL'] . "\"";
                //--(12)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['名義人地区CD'] . "\"";
                //--(13)
                $strOut .= ",\"" . $orderinfo['OrderInfo2']['名義人軒番カナ'] . "\"";
                //--(20)

                $strOut .= ",\"" . $orderinfo['OrderInfo3']['名義人名称'] . "\"";
                //--(3)
                $strOut .= ",\"" . $orderinfo['OrderInfo3']['親2桁コード'] . "\"";
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo3']['手形据置日数'];
                //--(3)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両価格'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両値引'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両注文書原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両拠点原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両新車車両部署別用原価'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo3']['車両消費税率'] . "\"";
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo3']['車両消費税額'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['添付品定価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['添付品値引'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['添付品契約'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['添付品原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['添付品消費税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様3定価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様3値引'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様3契約'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様3原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様3消費税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様6定価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様6値引'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様6契約'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様6原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['特別仕様6消費税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['割賦手数料契約'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['割賦手数料基準'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo3']['割賦手数料消費税率'] . "\"";
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo3']['割賦手数料消費税額'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['登録諸費用3契約'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['登録諸費用3基準'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['登録諸費用3消費税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['預り法廷費用'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['税金保険料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['残債'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払金合計'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件下取価格'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件下取車消費税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件頭金'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件登録諸費用'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件中古車負担金'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo3']['支払条件手形回数'] . "\"";
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件手形金額'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ回数'] . "\"";
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo3']['支払条件ｸﾚｼﾞｯﾄ金額'];
                //--(9)

                $strOut .= ",\"" . $orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ会社'] . "\"";
                //--(2)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['ｸﾚｼﾞｯﾄ承認NO'] . "\"";
                //--(20)
                $strOut .= "," . $orderinfo['OrderInfo4']['下取ﾘｻｲｸﾙ料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['割賦元金'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['下取者買取価格'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['下取者査定価格'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金自動車税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金車両取得税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金ｴｱｺﾝ取得税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金ｽﾃﾚｵ取得税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金重量税'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['税金消費税'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['自賠責指定'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['自賠責会社'] . "\"";
                //--(2)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['自賠責自動車種類'] . "\"";
                //--(2)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['自賠責色コード'] . "\"";
                //--(1)
                $strOut .= "," . $orderinfo['OrderInfo4']['自賠責月数'];
                //--(2)
                $strOut .= "," . $orderinfo['OrderInfo4']['自賠責保険料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['任意保険料'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['販売手数料課税非課税'] . "\"";
                //--(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['販売手数料支払先コード'] . "\"";
                //--(5)
                $strOut .= "," . $orderinfo['OrderInfo4']['販売手数料額'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['販売消費税'];
                //--(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo4']['予備'] . "\"";
                //--(1)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3検査'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3持込車検'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3車庫証明'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3納車費用'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3下取諸手続'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3査定料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3字光式'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['登録諸費用3その他'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['預り法定費用検査'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['預り法定費用持込車検'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['預り法定費用車庫証明'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['預り法定費用下取'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['本部負担金'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['打込金収入手数料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['打込金申請奨励金'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['割賦手数料差額'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['その他紹介料'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['車両F号限界利益'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['ﾍﾟﾅﾙﾃｨ'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['営業外収益'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['最終損益'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['特約店契約基本ﾏｰｼﾞﾝ'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['特約店契約累進ﾏｰｼﾞﾝ'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['特約店契約拡販奨励金'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['特約店契約特別価格'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['原価標準原価'];
                //--(9)
                $strOut .= "," . $orderinfo['OrderInfo4']['原価下取車売上仕切'];
                //--(9)

                $strOut .= "," . $orderinfo['OrderInfo5']['中古車売車下取価格'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車売車査定'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車再生見積'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車諸掛'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車売車査定ソカイ'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車未経過自動車税金額'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車未経過自動車税消費税'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車未経過自賠責金額'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['中古車未経過自賠責消費税'];
                //--9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['入庫約束'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['DM送付'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['キョウシンカイ顧客'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['キョウシンカイ紹介'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['キョウシンカイコウケン'] . "\"";
                //--X(1)
                $strOut .= "," . $orderinfo['OrderInfo5']['値引率'];
                //--9(2.2)
                $strOut .= "," . $orderinfo['OrderInfo5']['基準値引率'];
                //--9(2.2)
                $strOut .= "," . $orderinfo['OrderInfo5']['公正証書'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['JAF'];
                //--9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['KB'];
                //--9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfo5']['預託区分'] . "\"";
                //--X(1)
                $strOut .= "," . $orderinfo['OrderInfo5']['ﾘｻｲｸﾙ預託金'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfo5']['ﾘｻｲｸﾙ資金管理費'];
                //--S9(9)
                $strOut .= "\r\n";

                //convert encoding
                $strOut = mb_convert_encoding($strOut, "SJIS");
                //ファイル出力
                fwrite($objSw, $strOut);

                $intStateA = 1;
                // ログ管理
                $lngOutCntA = $lngOutCntA + 1;
                // ログ管理

                //ｶｳﾝﾄｱｯﾌﾟ(処理件数)
                $lngTranCnt = $lngTranCnt + 1;
            }
            fclose($objSw);

            //--------------------------------------------------------------------------------
            //下取住所ﾌｧｲﾙＡ出力
            //--------------------------------------------------------------------------------
            $strFileNm = $strCsvPath . $strFileSyuNm . "B.CSV";

            //インスタンス作成
            $objSw = fopen($strFileNm, 'w+');
            if (!$objSw) {
                throw new \Exception("Can't open file.");
            }
            $objLog['strErrMsg'] = "";

            //データが存在する場合
            for ($lngIdx = 0; $lngIdx <= count($orderData) - 1; $lngIdx++) {
                $orderinfo = $orderData[$lngIdx];
                //初期化
                $strOut = "";
                $strOut .= "\"" . $orderinfo['OrderInfoA']['ID'] . "\"";
                //--X(2)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['処理日'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['処理時間'] . "\"";
                //--X(6)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['予備1'] . "\"";
                //--X(4)
                $strOut .= ",\"" . substr(str_pad($orderinfo['OrderInfoA']['UCNO'], 12), 2, 10) . "\"";
                //--X(10)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['AB'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['注文書NO2'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['予備2'] . "\"";
                //--X(2)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目整理NO'] . "\"";
                //--X(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目親車注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目売上注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目下取SW'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目買下理由'] . "\"";
                //--X(2)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目現地仕切'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目銘柄'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目西暦年制'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目車検証型式'] . "\"";
                //--X(15)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目CARNO'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目車名'] . "\"";
                //--X(12)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目型式指定'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目類別区分'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目登録年月日'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目陸事名称'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目登録NO1'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目登録NO2'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目登録NO3'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目H59'] . "\"";
                //--X(3)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目下取価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目査定価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目実査定価格'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目消費税率'] . "\"";
                //--X(2)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目消費税額'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ預託金'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoA']['下取車１台目ﾘｻｲｸﾙ資金管理料'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目預託区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['下取車１台目手放区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoA']['予備3'] . "\"";
                //--X(18)

                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目整理NO'] . "\"";
                //--X(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目親車注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目売上注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目下取SW'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目買下理由'] . "\"";
                //--X(2)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目現地仕切'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目銘柄'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目西暦年制'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目車検証型式'] . "\"";
                //--X(15)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目CARNO'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目車名'] . "\"";
                //--X(12)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目型式指定'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目類別区分'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目登録年月日'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目陸事名称'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目登録NO1'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目登録NO2'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目登録NO3'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目H59'] . "\"";
                //--X(3)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目下取価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目査定価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目実査定価格'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目消費税率'] . "\"";
                //--X(2)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目消費税額'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ預託金'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoB']['下取車２台目ﾘｻｲｸﾙ資金管理料'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目預託区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['下取車２台目手放区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoB']['予備'] . "\"";
                //--X(18)

                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目整理NO'] . "\"";
                //--X(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目親車注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目売上注文書NO'] . "\"";
                //--X(7)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目下取SW'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目買下理由'] . "\"";
                //--X(2)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目現地仕切'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目銘柄'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目西暦年制'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目車検証型式'] . "\"";
                //--X(15)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目CARNO'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目車名'] . "\"";
                //--X(12)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目型式指定'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目類別区分'] . "\"";
                //--X(3)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目登録年月日'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目陸事名称'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目登録NO1'] . "\"";
                //--X(8)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目登録NO2'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目登録NO3'] . "\"";
                //--X(4)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目H59'] . "\"";
                //--X(3)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目下取価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目査定価格'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目実査定価格'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目消費税率'] . "\"";
                //--X(2)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目消費税額'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ預託金'];
                //--S9(9)
                $strOut .= "," . $orderinfo['OrderInfoC']['下取車３台目ﾘｻｲｸﾙ資金管理料'];
                //--S9(9)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目預託区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['下取車３台目手放区分'] . "\"";
                //--X(1)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['予備'] . "\"";
                //--X(18)
                $strOut .= ",\"" . $orderinfo['OrderInfoC']['予備2'] . "\"";
                //--X(05)

                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者キー名寄せ'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者キー地区コード'] . "\"";
                //--X(13)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者キーTEL'] . "\"";
                //--X(12)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者住所軒番漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者住所通称地漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者名称1漢字'] . "\"";
                //--X(40)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者名称2漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者住所カナ'] . "\"";
                //--X(20)
                $strOut .= ",\"" . $orderinfo['OrderInfoD']['契約者名称カナ'] . "\"";
                //--X(40)

                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人キー名寄せ'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人キー地区コード'] . "\"";
                //--X(13)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人キーTEL'] . "\"";
                //--X(12)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人住所軒番漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人住所通称地漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人名称1漢字'] . "\"";
                //--X(40)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人名称2漢字'] . "\"";
                //--X(30)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人住所カナ'] . "\"";
                //--X(20)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['名義人名称カナ'] . "\"";
                //--X(40)
                $strOut .= ",\"" . $orderinfo['OrderInfoE']['FIL'] . "\"";
                //--X(2)
                $strOut .= "\r\n";
                //ファイル出力

                //convert encoding
                $strOut = mb_convert_encoding($strOut, "SJIS");
                fwrite($objSw, $strOut);
                $intStateB = 1;
                // ログ管理
                $lngOutCntB = $lngOutCntB + 1;
                // ログ管理

            }

            fclose($objSw);
            $objLog['lngCount'] = $objLog['lngCount'] + $lngTranCnt;
            $ctlText = $lngTranCnt;
            //正常終了
            $result['result'] = TRUE;
        } catch (\Exception $e) {
            // $strErrMsg = "HMPTOS" . "\r\n" . "fncOrderOutput " . "\r\n" . $e->getMessage();
            $objLog['strErrMsg'] = $e->getMessage();
            $result['result'] = FALSE;
            $result['data'] = $e->getMessage();
        }
        return $result;
    }

    //********************************************************************
    //売上データチェック（CSV作成）         fan add  end.
    //********************************************************************
}
