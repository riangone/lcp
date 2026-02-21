<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;


class ClsFncLogComponent extends Component
{
    public $objSw = '';
    public $ClsComFnc;
    public $strLogPath;
    public function __construct(ComponentRegistry $registry)
    {
        parent::__construct($registry);
        $this->ClsComFnc = $registry->load('ClsComFnc');
    }
    public $GS_OUTPUTLOG = array(
        "strState" => '',
        //OK:正常終了 NG:異常終了
        "strID" => '',
        //処理名
        "strNaiyou" => '',
        //処理内容
        "strStartDate" => '',
        //処理開始システム日付
        "strEndDate" => '',
        //処理終了システム日付
        "strDataNM" => '',
        //作成CSVデータ名
        "strErrNO" => '',
        //ｴﾗｰ注文書NO
        "strErrFlg" => '',
        //ｴﾗｰ件数ﾌﾗｸﾞ
        "lngCount" => '',
        //作成件数
        "strErrMsg" => '', //エラーメッセージ
    );

    // 複数コンポーネント読込み確認用
    public function fncOutChk($strFileNm)
    {
        //ヒットした位置
        $intHitNum = "";
        //パス名
        $strPath = "";
        //出力先が未入力の場合はｴﾗｰ
        $strPathname = array();
        if (trim($strFileNm) == "") {
            $this->GS_OUTPUTLOG['strErrMsg'] = "出力先";
            return "W0001";
        } else {
            //最後に出現する"\"の位置をintHitNumに代入
            $strPathname = explode(" ", $strFileNm);
            $intHitNum = count($strPathname);
            $strPathname = array_slice($strPathname, 1, $intHitNum - 1);
            $strPath = implode("/", $strPathname);
            if (!$this->ClsComFnc->FncFileExists($strPath)) {
                $this->GS_OUTPUTLOG['strErrMsg'] = "";
                return "W0015";
            }

        }
        //$logpath = TMP . "logs/";
        return "";
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncStartLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************

    public function fncStartLog($strFileNM, &$objLog)
    {
        $strOut = "";
        $this->objSw = "";
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
        return TRUE;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncEndLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function fncEndLog($strFileNM, &$objLog)
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
        $strOut .= $objLog['strEndDate'];
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
        return TRUE;
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
        return TRUE;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncErrLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　  objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function fncErrLog($strFileNM, &$objLog)
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
        return TRUE;
    }

    public function fncDownLoadLog($strFileNM, &$objLog, $intDataKind = 3)
    {
        $this->objSw = "";
        $strOut = "";
        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        switch ($intDataKind) {
            //開始ログ
            case 0:
                $strOut .= $objLog['strID'] . " ";
                $strOut .= "Start ";
                $strOut .= $objLog['strStartDate'] . " ";
                break;
            //終了ログ
            case 1:
                $strOut .= $objLog['strID'] . " ";
                $strOut .= "END ";
                $strOut .= $objLog['strEndDate'] . " ";
                break;
            case 2:
                $strOut .= "    ";
                $strOut .= $objLog['strDataNM'];
                break;
            //処理ごとのログ
            case 3:
                $strOut .= "        ";
                $strOut .= $objLog['strDataNM'] . " ";
                $strOut .= $objLog['lngCount'] . " ";
                $strOut .= $objLog['strState'] . " ";
                $strOut .= $this->ClsComFnc->FncGetSysDate("y/m/d H:i:s");
                break;
            //注文書ｴﾗｰログ
            case 4:
                $strOut .= "    ";
                $strOut .= "注文書番号：" . $objLog['strErrNO'];
                $strOut .= "        ";
                $strOut .= "特別架装品合計額(TKB_KSH_SUM_GKU_ZEINK)がオーバーフローしました";
                break;
            //ｴﾗｰログ
            case 5:
                $strOut .= "    ";
                $strOut .= "ERR:" . $objLog['strErrMsg'];
                break;

            default:
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

        return TRUE;
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

        //終了情報ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        return TRUE;
    }

    //**********************************************************************
    //処 理 名：出力する
    //関 数 名：fncOutLog
    //引    数：strFileNM (I)出力ファイル名
    //　    　　objLog    (I)構造体(ログ)
    //戻 り 値：true:正常　false:異常
    //処理説明：ログファイルを出力する
    //**********************************************************************
    public function fncLogMsg($strFileNM, &$objLog)
    {
        $strOut = "";
        //初期化
        $this->objSw = "";

        //インスタンス作成
        $this->objSw = fopen($strFileNM, 'a+');

        $strOut .= $objLog['strErrMsg'] . " ";

        //ファイル出力
        fwrite($this->objSw, $strOut);
        fclose($this->objSw);
        //正常終了
        return TRUE;
    }

}